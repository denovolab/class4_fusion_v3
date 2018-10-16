#!/usr/bin/env perl
use strict;
use warnings;
use Config::IniFiles;
use Data::Dumper;
use Term::ANSIColor;
use DBI;
use DBD::Pg;
use IO::Socket::INET;

print("Installing some requirements!\n");
print("For generating invoice pdf:\n");
system("yum install xz urw-fonts libXext openssl-devel libXrender");

my $config_file_path = &read_index();
print("Configuration file(Current:$config_file_path): ");
my $conf_input_path = <STDIN>;
chomp($conf_input_path);

if ($conf_input_path) {
	# change config file
	&change_conf($conf_input_path);
	$config_file_path = $conf_input_path;
}

print("\nYou entered the path: $config_file_path.\n");

print("Read config file...");
my %config;
tie %config, 'Config::IniFiles', ( -file => $config_file_path, -handle_trailing_comment => 1 );
for my $k1( keys %config ){
	for my $k2( keys %{$config{$k1}} ){
		while ( $config{$k1}{$k2} =~ /(.*)\.\$(.*)/ ){
			$config{$k1}{$k2} = $config{$1}{$2};
		}
	}
}

&check_db();
&check_export_dir();
&check_php();
&check_switch();
&check_xsend_file();
&check_local_ip();

print("Changing file permissions...\n");
`sh chmod.sh`;

print("Cleaning the cache...\n"); 
# clean cache
`rm -rf app/tmp/cache/*`;

print("Complete!!!!\n");

sub read_index
{
	my $index_file = 'app/webroot/index.php';
	my $conf_file  = '';
	open my $index_file_handle, "<", $index_file
		or die "Can not open '$index_file' for reading: $!";
	local $/;
	my $contents = <$index_file_handle>;
	if ($contents =~ /define\('CONF_PATH', '(.*)'\);/) {
		$conf_file = $1;
	}
	return $conf_file;	
}

sub change_conf
{
	# change conf file
	my $conf_file = shift @_;	

	my $index_file = 'app/webroot/index.php';
	
	open my $index_file_handle, "<", $index_file
		or die "Can not open '$index_file' for reading: $!\n";
	local $/;
	my $contents = <$index_file_handle>;
	$contents =~ s/define\('CONF_PATH', '(.*)'\);/define\('CONF_PATH', '$conf_file'\);/g; 

	close $index_file_handle;

	open my $index_file_handle, ">", $index_file
		or die "Can not open '$index_file' for writing: $!\n";
	print $index_file_handle $contents;
	close $index_file_handle;
}


sub check_db
{
	print("\nCheck DB connection...\n");
	my $db_name = $config{ web_db }{ dbname };
	my $db_host = $config{ web_db }{ host };
	my $db_port = $config{ web_db }{ port };
	my $db_username = $config{ web_db }{ user };
	my $db_password = $config{ web_db }{ password };
	my $dbh = DBI->connect( "dbi:Pg:dbname=$db_name; host=$db_host; port=$db_port", 
		$db_username, 
		$db_password, 
		{ AutoCommit => 1, pg_server_prepare => 1 } );
	if( ! $dbh ){
		 &print_error("DB connection Error! Please check your configuration file... Please check web_db config section!");
	} else {
		 print "DB connection success!\n";
	}
}

sub check_export_dir
{
	print("\nCheck Web DB export Path...\n");
	my $export_dir = $config{ web_path }{ web_export_path };
	if ( -e $export_dir && -d $export_dir) {
		print "Web DB export Path exists!\n";
	} else {
		&print_error("Web Exports Path does not exist, You must create it! Please check web_path.web_export_path config item!");
	}

	open my $read_fh, "mount -l |"
		or die "Cannot execute 'mount -l |' : $!\n";
	my $output = do { local $/; <$read_fh>; };
	if (index($output, $export_dir) != -1) {
		print("You have already mounted the export path!")
	} else {
		&print_error("You have not mounted the export path!");
	}
}

sub check_php 
{
	print("\nCheck PHP Interpreter path.\n");
	my $php_interpreter_path = $config{ web_path }{ php_interpreter_path };

	if ( -e $php_interpreter_path && -f $php_interpreter_path) {
		print "PHP Interpreter'path exists!\n";
	} else {
		&print_error("PHP Interpreter'path doest not exists");
	}
}

sub check_switch
{
	print("\nCheck Switch connection...\n");
	my $switch_ip = $config{ web_switch }{ event_ip };
	my $switch_port = $config{ web_switch }{ event_port };
	my $socket = IO::Socket::INET->new(PeerAddr => $switch_ip,
		PeerPort  => $switch_port,
		Proto => "tcp",
		Type => SOCK_STREAM)
		or &print_error("Couldn't connect to switch @ $switch_ip:$switch_port: $!\n");

	if ($socket) {
		print("Check Switch connection OK...\n");
		close($socket);
	}
	
}

sub check_xsend_file()
{
	print("\nBegin check apache xsend file module!\n");
	print("Please input apache conf file path: ");
	my $apache_conf_file = <STDIN>;
	chomp($apache_conf_file);
	if ( -e $apache_conf_file && -f $apache_conf_file) {
		print "Apache conf file exists!\n";
	} else {
		&print_error("Apache conf file doest not exists");
	}
	open my $apache_conf_file_handle, "<", $apache_conf_file
		or die "Can not open '$apache_conf_file' for reading: $!\n";
	my $contents = do { local $/; <$apache_conf_file_handle>; };
	if ($contents =~ /XSendFile/) {
		print "Xsendfile has already installed";
	} else {
		&print_error("Xsendfile does not installed!(https://tn123.org/mod_xsendfile/)");
	}
}

sub check_local_ip()
{
	print("\nBegin check local IP!\n");
	my $ip = $config{ web_ip }{ web_local_ip };

	open my $read_fh, "ifconfig |"
		or die "Cannot execute 'ifconfig |' : $!\n";
	my $output = do { local $/; <$read_fh>; };
	if (index($output, $ip) != -1) {
		print("Local IP OK!\n")
	} else {
		&print_error("Local IP Error! Please check web_ip.web_local_ip config item!");
	}

}

sub print_error()
{
	my $err = shift @_;
	print color 'bold red';
	print $err;
	print color 'reset';
	print "\n";
	print("Still continue?(Y/N): ");
	my $input = <STDIN>;
	chomp($input);
	if ($input eq 'Y' || $input eq 'y') {

	} else {
		exit;
	}
}

