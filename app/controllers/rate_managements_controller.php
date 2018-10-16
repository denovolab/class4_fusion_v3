<?php

class RateManagementsController extends AppController
{

    var $name = "RateManagements";
    var $uses = array('RateMailDecks', 'RateMailDecksFiles', 'ImportRateStatus');
    var $helpers = array('Javascript', 'Html', 'Common');

    public function beforeFilter()
    {
        $this->checkSession("login_type"); //核查用户身份
        parent::beforeFilter(); //调用父类方法
    }

    public function index()
    {
        $this->pageTitle = "Tools/Rate Management";
        $this->paginate = array(
            'limit' => 100,
            'order' => array(
                'id' => 'desc',
            ),
            'conditions' => array(
                'type' => 0,
            ),
        );
        
        $this->RateMailDecks->bindModel(array(
            'hasMany' => array(
                'RateMailDecksFiles' => array(
                    'foreignKey' => 'rate_mail_decks_id',
                )
            ),
            'belongsTo' => array(
                'Client' => array(
                    'foreignKey' => 'client_id'
                )
            )
        ), false);
        
        $this->data = $this->paginate('RateMailDecks');
        
        foreach ($this->data as &$item)
        {
            foreach ($item['RateMailDecksFiles'] as &$deckfile) {
                $deckfile['status'] = $this->RateMailDecks->get_status($deckfile['rate_upload_id']);
            }
        }
        
        $this->set('upload_type', array(
            'Unprocessed',
            'Upload Successful',
            'Upload Failed',
        ));
    }
    
    public function email($id)
    {
        $this->autoLayout = false;
        if ($this->RequestHandler->isPost())
        {
            $type = $_POST['type'];
            $notice = $_POST['notice'];
            $failure_reasons = $_POST['failure_reasons'];
            
            $sql = "SELECT * FROM rate_mail_decks_files left join rate_mail_decks on rate_mail_decks_files.rate_mail_decks_id = rate_mail_decks.id WHERE id = $id";
            $result = $this->RateMailDecks->query($sql);
            
            $absolute_filename = basename($result[0][0]['file_name']);
	    $received_time     = $result[0][0]['upload_time'];
	    $original_filename = $result[0][0]['original_filename'];
	    $file_type 	       = $result[0][0]['file_type'] == 0 ? 'csv' : 'xls';
	    $mail_subject      = $result[0][0]['mail_subject'];
	    $date_process      = date("Y-m-d H:i:s");
	    $to_mail           = $result[0][0]['to'];
	    $file_size         = $result[0][0]['file_size'];
	    $from_address      = $result[0][0]['from_address'];
            
            $sql = "SELECT rate_mail_success_subject, rate_mail_success_content, 
			rate_mail_success_from, rate_mail_fail_subject, rate_mail_fail_content, rate_mail_fail_from FROM mail_tmplate;";
            $mail_template_result = $this->RateMailDecks->query($sql);
            
            if ($type == 0)
            {
                $rate_mail_success_subject = $mail_template_result[0][0]['rate_mail_success_subject'];
                $rate_mail_success_content = $mail_template_result[0][0]['rate_mail_success_content'];
                $rate_mail_success_from    = $mail_template_result[0][0]['rate_mail_success_from'];
                $subject = $rate_mail_success_subject;
                $content = $rate_mail_success_content;
                $from     = $rate_mail_success_from;
            }	
            else
            {                    
                $rate_mail_fail_subject = $mail_template_result[0][0]['rate_mail_fail_subject'];
                $rate_mail_fail_content = $mail_template_result[0][0]['rate_mail_fail_content'];
                $rate_mail_fail_from    = $mail_template_result[0][0]['rate_mail_fail_from'];
                $subject = $rate_mail_fail_subject;
                $content = $rate_mail_fail_content;
                $from     = $rate_mail_fail_from;
            }
            
            $content = str_replace(array('{to}'), array($to_mail), $content);
            
            $rate_file_detail = <<<EOT
   		<table>
			<tr>
				<td>Data Received:{$received_time}</td>
				<td>Data Processed:{$date_process}</td>
			<tr>
			<tr>
				<td>From:{$from_address}</td>
				<td>To:{$to_mail}</td>
			<tr>
			<tr>
				<td conspan="2">Email Subject:{$subject}</td>
			<tr>
			<tr>
				<td conspan="2">Original File Name:{$original_filename}</td>
			<tr>
			<tr>
				<td conspan="2">Processed File Name:{$absolute_filename}</td>
			<tr>
			<tr>
				<td conspan="2">File Size:{$file_size} Kb</td>
			<tr>
		</table>
EOT;
                                
           $rate_file_detail .= $content;
           
           $sql = "SELECT * FROM system_parameter;";
           $system_result = $this->RateMailDecks->query($sql);
           $rate_admin_address = $system_result[0][0]['system_rate_mail'];
           
           if ($notice == 0)
            {
                    $send_address = "-t $from_address";
            }
            elseif ($notice == 1)
            {
                    $send_address = "-t $rate_admin_address";
            }
            else
            {
                    $send_address = "-t $from_address -t {$rate_admin_address}";
            }
           
           $script_path = Configure::read('script.path');
           $script_conf = Configure::read('script.conf');
           $cmd = "perl $script_path/class4_send_mail.pl -c {$script_conf} $send_address -subject '$subject' -content '$content' > /dev/null 2>&1 &";
           shell_exec($cmd);
           $this->RateMailDecks->create_json_array("", 201, 'The Email is sent succesfully!');
            $this->Session->write('m', RateMailDecks::set_validator());
            $this->redirect('/rate_managements/index');
        }
        $this->set('id', $id);
    }
    
    
    public function unrecognized()
    {
        $this->pageTitle = "Tools/Rate Management";
        $this->paginate = array(
            'limit' => 100,
            'order' => array(
                'id' => 'desc',
            ),
            'conditions' => array(
                'type' => 1,
            ),
        );
        
        $this->RateMailDecks->bindModel(array(
            'hasMany' => array(
                'RateMailDecksFiles' => array(
                    'foreignKey' => 'rate_mail_decks_id',
                )
            ),
            'belongsTo' => array(
                'Client' => array(
                    'foreignKey' => 'client_id'
                )
            )
        ), false);
        
        $this->data = $this->paginate('RateMailDecks');
        
        $this->set('upload_type', array(
            'Unprocessed',
            'Upload Successful',
            'Upload Failed',
        ));
    }
    
    public function view_mail_content($id)
    {
        $this->autoLayout = false;
        $rate_mail_deck = $this->RateMailDecks->findById($id);
        $this->set("rate_mail_deck", $rate_mail_deck);
    }
    
    public function move_trash($id)
    {
        $this->autoRender = false;
        $this->autoLayout = false;
        $rate_mail_deck = $this->RateMailDecks->findById($id);
        $rate_mail_deck['RateMailDecks']['type'] = 3;
        $this->RateMailDecks->save($rate_mail_deck);
        $this->RateMailDecks->create_json_array("", 201, 'Move to trash successfully!');
        $this->Session->write('m', RateMailDecks::set_validator());
        $this->redirect('/rate_managements/unrecognized');
    }
    
    public function upload($id)
    {
        
        $rate_mail_deck_file = $this->RateMailDecksFiles->findById($id);
        $rate_mail_deck = $this->RateMailDecks->findById($rate_mail_deck_file['RateMailDecksFiles']['rate_mail_decks_id']);
        $egresses = $this->RateMailDecks->get_egresses($rate_mail_deck['RateMailDecks']['client_id']);
        $rate_file = $rate_mail_deck_file['RateMailDecksFiles']['file_name'];
        
        if ($this->RequestHandler->ispost())
        {
            //$is_email_done = isset($_POST['email_done']);
            $abspath = $_POST['abspath'];
            $new_columns = $_POST['columns'];
            $new_columns_str = implode(',', $new_columns);
            $cmd_ = "sed -i -e '1s/.*/{$new_columns_str}/g' -e 's/\\r\\n\\{0,1\\}/\\n/g' {$abspath}";
            shell_exec($cmd_);
            
            $egress_id = $_POST['egress_id'];
            $ratetable_id = $this->RateMailDecks->get_ratetable_by_egress_id($egress_id);
            $rate_table = $this->RateMailDecks->query("select jur_type from rate_table where rate_table_id = {$ratetable_id}");
            if ($rate_table[0][0]['jur_type'] == 3)
            {
                $cmd_parm = '-u 1  -C 0';
            }
            else
            {
                $cmd_parm = '-u 0';
            }
            
            $sql = "insert into import_rate_status(rate_table_id, status) values ({$ratetable_id}, -1) returning id";
            $log_result = $this->RateMailDecks->query($sql);
            $log_id = $log_result[0][0]['id'];
            $rate_mail_deck_file['RateMailDecksFiles']['rate_upload_id'] = $log_id;
            $this->RateMailDecksFiles->save($rate_mail_deck_file);
            
            $binpath = Configure::read('rateimport.bin');
            $confpath = Configure::read('rateimport.conf');
            $outpath = Configure::read('rateimport.out');
            
            $filename = basename($rate_file);
            $cmd = "{$binpath} -F '{$filename}' -t 1 -d {$confpath} -r {$ratetable_id} -c 'yyyy-mm-dd' -f '{$abspath}' -o {$outpath} -m 2 -U {$_SESSION['sst_user_id']} {$cmd_parm} -I {$log_id} > /dev/null 2>&1 &";
            //shell_exec($cmd);
            /*
            $script_path = Configure::read('script.path');
            $script_conf = Configure::read('script.conf');
            
            $cmd_other = '';            
            if ($is_email_done) {
                $cmd_other = " -y {$_POST['success_notice']} -n {$_POST['failed_notice']}";
            }
            $cmd2 = "perl $script_path/class4_rate_mail.pl -c {$script_conf} -l {$log_id} -d \"{$cmd}\" -i {$id}  {$cmd_other} > /dev/null 2>&1 &";
            
            if (Configure::read('cmd.debug')) {
                echo $cmd2;exit;
            }
            shell_exec($cmd2);
            */
            if (Configure::read('cmd.debug')) {
                echo $cmd;exit;
            }
            shell_exec($cmd);
            
            $this->RateMailDecks->create_json_array("", 201, 'The file has already begun to upload!');
            $this->Session->write('m', RateMailDecks::set_validator());
            $this->redirect('/rate_managements/index');
        }
        
        
        
        $table = array();
        $row = 1;
        $handle = popen("head -n 21 {$rate_file}", "r");
        while ($row <= 21 && $data = fgetcsv($handle, 1000, ","))
        {
            $row++;
            array_push($table, $data);
        }
        pclose($handle);
        
        $this->loadModel('RateTable');
        $schema = $this->RateTable->default_schema;
        $fields = array_keys($schema);
        
        $this->set('table', $table);
        $this->set('egresses', $egresses);
        $this->set('rate_file', $rate_file);
        $this->set('columns', $fields);
    }
    
    public function view_result($id) 
    {
        Configure::write('debug', 0);
        $this->autoLayout = false;
        
        $rate_file = $this->RateMailDecksFiles->findById($id);
        $import_log = $this->ImportRateStatus->find('first', array(
            'fields' => array(
                'ImportRateStatus.status', 'ImportRateStatus.delete_queue', 'ImportRateStatus.update_queue', 
                'ImportRateStatus.insert_queue', 'ImportRateStatus.error_counter', 
                'ImportRateStatus.reimport_counter', 'ImportRateStatus.error_log_file', 'ImportRateStatus.reimport_log_file',
                'ImportRateStatus.time', 'ImportRateStatus.upload_file_name', 'ImportRateStatus.local_file',
                'ImportRateStatus.method', 'rate_table.name', 'rate_table.rate_table_id', 'users.name',
                'ImportRateStatus.start_epoch','ImportRateStatus.end_epoch',
            ),
            'joins' => array(
                array(
                    'table' => 'rate_table',
                    'type'  => 'INNER',
                    'conditions' => array(
                        'ImportRateStatus.rate_table_id = rate_table.rate_table_id'
                    ),
                ),
                array(
                    'table' => 'users',
                    'type'  => 'left',
                    'conditions' => array(
                        'ImportRateStatus.user_id = users.user_id'
                    ),
                )
            ),
            'conditions' => array(
                'ImportRateStatus.id' => $rate_file['RateMailDecksFiles']['rate_upload_id']
            )
        ));
        
        $this->set("data", $import_log);
        $status = array("Error!", "Running", "Insert", "Update", "Delete", "Done", "Done");
        $status["-1"] = 'Waiting';
        $status["-2"] = "End Date";
        $this->set('status', $status);
    }

}
