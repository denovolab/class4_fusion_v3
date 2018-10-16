<?php
//Configure::write('database_actual_export_path', '/tmp/exports');

Configure::write('database_actual_export_path', '/data2/exports');
//Configure::write('database_actual_export_path', '/opt/teleinx/exports');




// Redis
$config['redis']['host'] = '127.0.0.1';
$config['redis']['port'] = 6379;

$config['redis']['cdr_down_key'] = 'cdr_down_teleinx';





$sections = parse_ini_file(CONF_PATH, TRUE);
foreach ($sections as $section_key => &$section)
{
    foreach ($section as $item_key => &$item) {
        while (strpos($item, '$') !== FALSE) {
            list($key, $value) = explode('.', $item);
            $item = @$sections[$key][trim($value, '$')];
        }
    }
}


//class4脚本配置目录，不要提供末尾目录符
$config['script']['path'] = "/opt/monitor/scripts"; 
$config['script']['conf'] = '/opt/monit/teleinx/class4.conf';


//LRN 测试功能所执行的二进制文件
$config['lrn_test']['lrn_test_bin'] = APP . "binexec/lrn_test/dnl_lrn_testing";


//费率上传相关配置文件
$config['rateimport']['bin'] = APP . "binexec/dnl_import_rate/dnl_import_rate";    // 费率上传执行的二进制文件,777 权限
#$config['rateimport']['conf'] = APP . "binexec/dnl_import_rate/conf/";             // 二进制文件配置文件目录
$config['rateimport']['conf'] = CONF_PATH;             // 二进制文件配置文件目录
$config['rateimport']['out'] = WWW_ROOT . "upload/rates_log";                      // 上传log件存放目录
$config['rateimport']['put'] = WWW_ROOT . "upload/rates";                          // 存放上传文件 


//生成Invoice(PDF)存放目录
$config['generate_invoice']['path'] = APP . 'webroot' . DS . 'upload' . DS . 'invoice_file';


//Mutual Balance 生成PDF存放目录
$config['generate_balance']['path'] = APP . 'webroot' . DS . 'upload' . DS . 'balance_file';


//后台SOCK server 通讯服务器ip & port,command_api_ip/port
$config['backend']['ip'] = "192.168.112.76";
$config['backend']['port'] = 4320;

//LRN Local Bind IP & Port
Configure::write("lrn.ip", "69.27.168.23");
Configure::write("lrn.port", 5025);


//exchange 登录地址
Configure::write('admin_login', $sections['web_feature']['exchange_login_url']);


//数据库导出目录,需要数据库机器网络映射
//Configure::write('database_export_path', '/opt/exports_88');

//--Configure::write('database_export_path', ROOT . DS . 'db_nfs_path');

Configure::write('database_export_path', '/opt/teleinx/exports');


//Configure::write('database_export_path_cdr', '/opt/cdr_exports/teleinx');

Configure::write('database_export_path_cdr', '/opt/teleinx/exports');

//php解释器目录
//Configure::write('php_exe_path', '/opt/http/php5321/bin/php');
Configure::write('php_exe_path', '/usr/bin/php');


///opt/http/php5321/bin/php


//SIP Capture 相关配置,/usr/local/sip_capture/sip_capture_server -i 173.205.112.249 -p 8500 -c /opt/nfs_files/dial/web_sip_capture/
Configure::write('sip_capture', array(

    'host_ip' => '192.168.112.76', //-i
    'port' => '7000', //-p
    'ngrep' => '/usr/bin/ngrep',
    'timeout_shell' => APP . 'vendors/shells/timeout.sh',
    'sip_scenario' => APP . 'vendors/shells/sip_scenario.pl',
    'single' => APP . 'vendors/shells/single.pl',
//    'pcap_dir' => "/opt/nfs_files_17/teleinx/web_sip_capture/", //-c


'pcap_dir' => "/opt/nfs_files_223/teleinx/web_sip_capture/",//-c

    'is_debug' => false, // 是否打开路径调试 (true 打开, false 关闭)
));


//上传License文件放置目录
Configure::write('system.license',  $sections['web_path']['license_path']);


//Copyright Hyperlink
Configure::write('is_copyright_hypelink',  $sections['web_feature']['copyright_link']);


//系统类型

$config['system']['type'] = 1;      //1 class4 , 2 exchange
$config['system']['enable_trunk_type'] = true;      // 是否开启 trunk type


//Origination 相关配置
$config['did']['enable'] = true;  //开启或关闭 true or false
$config['did']['upload_path'] =  APP . 'webroot' . DS . 'upload' . DS . 'did';


//SSH Login,需要安装php ssh模块
$config['ssh']['host'] = 'localhost';
$config['ssh']['port'] = 22;
$config['ssh']['username'] = 'hewenxiang';
$config['ssh']['pubkeyfile'] = '/home/hewenxiang/.ssh/id_dsa.pub';  
$config['ssh']['privkeyfile'] = '/home/hewenxiang/.ssh/id_dsa';


//在线支付相关配置


$config['payline']['enable_paypal'] = true;
$config['payline']['yourpay_enabled'] = true;
$config['payline']['yourpay_host'] = 'secure.linkpt.net';
$config['payline']['yourpay_port'] = '1129';
$config['payline']['is_new_window'] = $sections['web_feature']['pay_in_new_window'];  // paypal 是否在新窗口进行支付


//Call Monitor,class4 dnl_softswitch start_sipcapture.sh start_rtpdump.sh使用


//Active Call Application,./active_call_api -H 192.168.112.190 -P 4320 -h 192.168.112.190 -p 4313 -q 192.168.112.190 -w 4305 -m 1
$config['active_call']['exec'] = APP . "binexec/active_call/active_call_api";
$config['active_call']['test_local_ip'] = '192.168.112.200';   //test local IP -H
$config['active_call']['test_local_port'] = '4920'; //test local port -P
$config['active_call']['active_call_server_ip'] = '192.168.112.76'; //active call server IP -h
$config['active_call']['active_call_server_port'] = '4313'; //active call server port -p


//billing listen IP & port :
$config['active_call']['billing_server'] = array(
    '192.168.112.76:4305', //-q -w
);


//update log,class4 dnl_softswitch log
$config['update_log']['current'] = $sections['web_path']['switch_update_log_current'];
$config['update_log']['history'] = $sections['web_path']['switch_update_log_history'];


//发送invoice CDR 存放目录
//$config['send_invoice']['cdr_path'] = '/tmp/class4/invoice_cdr';   // 注意配置为 x-sendfile path
$config['send_invoice']['cdr_path'] = '/opt/monit/teleinx/invoice_cdr';   // 注意配置为 x-sendfile path



// Mail Export CDR Path
$config['export_cdr']['path'] = '/opt/monit/teleinx/cdr_down';

// 报表分组
$config['statistics']['group_all'] = $sections['web_feature']['statistics_group_all'];  // 是否显示报表所有分组

// 是否打开命令调试
$config['cmd']['debug'] = $sections['web_base']['cmd_debug'];

// Log文件 Web需要具备rx权限
$config['logfile']['script_log'] = '/tmp/class4';  // 脚本Log目录
$config['logfile']['switch_log_path'] = '';    // 后台的Log目录

// CDR TMP Path
$config['cdr']['tmp'] = $sections['web_path']['cdr_backup_path'];

// License Path
$config['license']['path'] = $sections['web_path']['license_path'];

$config['system']['token'] = $sections['web_base']['system_token'];

// Hoster Dialer
$config['host_dialer']['enabled'] = TRUE;
$config['host_dialer']['freeswitch_ip'] = '127.0.0.1';
$config['host_dialer']['freeswitch_port'] = 5060;


$config['call_monitor']['port'] = $sections['call_monitor']['port'];
$config['call_monitor']['videosnarf'] = $sections['call_monitor']['videosnarf_path'];

$config['cdr_ftp']['ip'] = '69.27.168.20';
$config['cdr_ftp']['port'] = 21;
$config['cdr_ftp']['username'] = 'teleinxftp';
$config['cdr_ftp']['password'] = 'teleinx568#';

?>
