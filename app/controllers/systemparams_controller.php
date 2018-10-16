<?php

class SystemparamsController extends AppController {

    var $name = 'Systemparams';
    var $components = array('PhpTelnet', 'RequestHandler');
    var $uses = array('Curr', 'Systemparam', 'GlobalFailover', 'FtpCdrLog', 'FtpConf', 'FtpCdrLogDetail', 'FtpServerLog');

    function index() {
        $this->redirect('view');
    }

    public function beforeFilter() {
        $this->checkSession("login_type"); //核查用户身份
        parent::beforeFilter(); //调用父类方法
    }

    public function find_all_currency() {
        $r = $this->Systemparam->query("	select  *  from  currency  ");
        $size = count($r);
        $l = array();
        for ($i = 0; $i < $size; $i++) {
            $key = $r [$i] [0] ['code'];
            $l [$key] = $r [$i] [0] ['code'];
        }
        return $l;
    }
    
    public function advance()
    {
        
    }

    public function change_logo() {
        $this->autoRender = false;
        $this->autoLayout = false;
        Configure::write('debug', 0);
        if (is_uploaded_file($_FILES['logoimg']['tmp_name'])) {
            $destpath = APP . 'webroot' .DS. 'upload'  . DS . 'images' . DS . 'logo.png';
            //$invoice_path = APP . 'webroot' .DS. 'upload'  . DS . 'images' . DS . 'ilogo.png';
            //$invoice_path = APP . "vendors/tcpdf/images/ilogo.png";
            //$invoice_path = WWW_ROOT . 'upload' . DS . 'html' . DS . 'ilogo.png';
            $sourcepath = $_FILES['logoimg']['tmp_name'];
            switch ($_FILES['logoimg']['type']) {
                case 'image/png':
                    header("Content-Type: image/png");
                    $im = imagecreatefrompng($sourcepath);
                    imagesavealpha($im, true); 
                    break;
                case 'image/jpeg':
                    header("Content-Type: image/jpeg");
                    $im = imagecreatefromjpeg($sourcepath);
                    break;
                case 'image/gif':
                    header("Content-Type: image/gif");
                    $im = imagecreatefromgif($sourcepath);
                    break;
            }
            imagepng($im, $destpath);
            //imagepng($im, $invoice_path);
            imagedestroy($im);
            $this->Systemparam->create_json_array("", 201, 'Your Logo is uploaded successfully!');
            $this->Session->write('m', Systemparam::set_validator());
        }
        $this->redirect('/systemparams/view');
    }
    
    public function change_icon() {
        $this->autoRender = false;
        $this->autoLayout = false;
        Configure::write('debug', 0);
        if (is_uploaded_file($_FILES['iconimg']['tmp_name'])) {
            $destpath = APP .  'webroot' .  DS . 'favicon.ico';
            $sourcepath = $_FILES['iconimg']['tmp_name'];

            if (strpos($_FILES['iconimg']['type'], 'ico') !== false)
            {
                move_uploaded_file($sourcepath, $destpath);
                header( 'Expires: Mon, 26 Jul 1997 05:00:00 GMT' );
                header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s' ) . ' GMT' );
                header( 'Cache-Control: no-store, no-cache, must-revalidate' );
                header( 'Cache-Control: post-check=0, pre-check=0', false );
                header( 'Pragma: no-cache' );
                $this->Systemparam->create_json_array("", 201, 'Your Icon is Uploaded successfully');
                $this->Session->write('m', Systemparam::set_validator());
            }
            else
            {
                $this->Systemparam->create_json_array("", 101, 'You did not upload an icon file!');
                $this->Session->write('m', Systemparam::set_validator());
            }
        }
        $this->redirect('/systemparams/view');
    }
    
    
    public function change_certfile() {
    	$this->autoRender = false;
    	$this->autoLayout = false;
    	Configure::write('debug', 0);
    	if (is_uploaded_file($_FILES['certifile']['tmp_name'])) {
    		$destpath = APP .  'webroot' .  DS . 'upload' .DS .'yourpay' .DS .'YOURCERT.perm';
    		$sourcepath = $_FILES['certifile']['tmp_name'];
    		move_uploaded_file($sourcepath, $destpath);
    		$this->Systemparam->create_json_array("", 201, 'Succeed!');
    		$this->Session->write('m', Systemparam::set_validator());
    	}
    	$this->redirect('/systemparams/view');
    }

    public function auto_cdr_fields_setting() {
        $incoming_cdr_fields = $this->Systemparam->get_incoming_cdr_fields();
        $outgoing_cdr_fields = $this->Systemparam->get_outgoing_cdr_fields();

        if ($this->RequestHandler->isPost()) {
            $incoming_list = isset($_POST['incoming_list']) ? $_POST['incoming_list'] : 0;
            $outgoing_list = isset($_POST['outgoing_list']) ? $_POST['outgoing_list'] : 0;
            $sql = "truncate daily_cdr_fields";
            $this->Systemparam->query($sql);
            $sql_arr1 = array();
            if (!empty($incoming_list)) {
                foreach ($incoming_list as $incoming_item) {
                    array_push($sql_arr1, "INSERT INTO daily_cdr_fields(type, field, label) VALUES (0, '{$incoming_item}', '$incoming_cdr_fields[$incoming_item]')");
                }
                $this->Systemparam->query(implode(';', $sql_arr1));
            }
            $sql_arr2 = array();
            if (!empty($outgoing_list)) {
                foreach ($outgoing_list as $outgoing_item) {
                    array_push($sql_arr2, "INSERT INTO daily_cdr_fields(type, field, label) VALUES (1, '{$outgoing_item}', '$outgoing_cdr_fields[$outgoing_item]')");
                }
                $this->Systemparam->query(implode(';', $sql_arr2));
            }
        }

        $incoming_data = $this->Systemparam->get_daily_cdr_fields(0);
        $outgoing_data = $this->Systemparam->get_daily_cdr_fields(1);

        $this->set('incoming_cdr_fields', array_diff_assoc($incoming_cdr_fields, $incoming_data));
        $this->set('outgoing_cdr_fields', array_diff_assoc($outgoing_cdr_fields, $outgoing_data));
        $this->set('incoming_data', $incoming_data);
        $this->set('outgoing_data', $outgoing_data);
    }

    /*
     * 向后台发送命令  返回模拟结果
     */

    public function view() {
        header( 'Expires: Mon, 26 Jul 1997 05:00:00 GMT' );
        header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s' ) . ' GMT' );
        header( 'Cache-Control: no-store, no-cache, must-revalidate' );
        header( 'Cache-Control: post-check=0, pre-check=0', false );
        header( 'Pragma: no-cache' );
        $this->pageTitle = "Configuration/System Setting";
        $list = $this->Systemparam->query("select * from system_parameter");
        
        /*
        $content = "";
        $cmd = "sipcapture_set_flag other";
        $socket = socket_create(AF_INET, SOCK_STREAM, getprotobyname("tcp"));
        if (socket_connect($socket, Configure::read("backend.ip"), Configure::read("backend.port"))) {
            socket_write($socket, $cmd, strlen($cmd));
        }
        while ($out = socket_read($socket, 2048)) {
            $content .= $out;
            if (strpos($out, "~!@#$%^&*()") !== FALSE) {
                break;
            }
            unset($out);
        }
        $content = strstr($content, "~!@#$%^&*()", TRUE);

        socket_close($socket);
        $this->set('sip_capture_status', $content);


        $content = "";
        $cmd = "rtpdump_set_flag other";
        $socket = socket_create(AF_INET, SOCK_STREAM, getprotobyname("tcp"));
        if (socket_connect($socket, Configure::read("backend.ip"), Configure::read("backend.port"))) {
            socket_write($socket, $cmd, strlen($cmd));
        }
        while ($out = socket_read($socket, 2048)) {
            $content .= $out;
            if (strpos($out, "~!@#$%^&*()") !== FALSE) {
                break;
            }
            unset($out);
        }
        $content = strstr($content, "~!@#$%^&*()", TRUE);

        socket_close($socket);

        $this->set('rtpdump_status', $content);
         * 
         */

        //	pr($list);
        if (empty($list)) {
            $list = $this->Systemparam->query("select  *  from  currency  where  code='USA' ;");
            if (empty($list)) {
                $this->data['Curr']['code'] = 'USA';
                $this->Curr->save($this->data['Curr']['code']);
                $currency_id = $this->Curr->$this->getLastInsertID();
                $sql1 = "insert  into currency_updates (currency_id,rate)values($currency_id,1); ";
                $this->Systemparam->query($sql1);
                $sql1 = "insert  into system_parameter (sys_timezone,sys_currency)values('+0000','USA'); ";
                $this->Systemparam->query($sql1);
                $sys_currency_rate = '1';
                $sys_currency = 'USA';
                $_SESSION['system_currency'] = compact('sys_currency', 'sys_currency_rate');
                $this->Systemparam->query($sql1);
            }
        }
        
        $logo_path = APP . 'webroot' .DS. 'upload'  . DS . 'images' . DS . 'logo.png';
        
        if(file_exists($logo_path))
        {
            $logo = $this->webroot . 'upload/images/logo.png';
        }
        else
        {
            $logo = $this->webroot . 'images/logo.png';
        }
        
        $this->set('logo', $logo);
        
        //获取code deck
        $this->loadModel('Rate');
        $search_info = $this->Rate->getSearchInfo();
        $this->set('codecs_s', $search_info[0]);
        $this->set('currency', $this->find_all_currency());
        $this->set('post', $this->Systemparam->findsysparam());
    }

    //更新系统参数

    public function set_capture($status) {
        $this->autoRender = false;
        $this->autoLayout = false;
        $cmd = "sipcapture_set_flag $status";
        $socket = socket_create(AF_INET, SOCK_STREAM, getprotobyname("tcp"));
        if (socket_connect($socket, Configure::read("backend.ip"), Configure::read("backend.port"))) {
            socket_write($socket, $cmd, strlen($cmd));
        }
        while ($out = socket_read($socket, 2048)) {
            if (strpos($out, "~!@#$%^&*()") !== FALSE) {
                break;
            }
            unset($out);
        }
        $this->xredirect('/systemparams/view');
    }

    public function set_rptdump($status) {
        $this->autoRender = false;
        $this->autoLayout = false;
        $cmd = "rtpdump_set_flag $status";
        $socket = socket_create(AF_INET, SOCK_STREAM, getprotobyname("tcp"));
        if (socket_connect($socket, Configure::read("backend.ip"), Configure::read("backend.port"))) {
            socket_write($socket, $cmd, strlen($cmd));
        }
        while ($out = socket_read($socket, 2048)) {
            if (strpos($out, "~!@#$%^&*()") !== FALSE) {
                break;
            }
            unset($out);
        }
        $this->xredirect('/systemparams/view');
    }

    public function failover() {
        $this->pageTitle = "Configuration/Default Failover Rule ";
        $list = $this->Systemparam->query("select * from  global_failover order by id");
        $this->set("host", $list);
    }

    public function add_rule_post() {
        $delete_rate_id = $_POST['delete_rate_id'];
        $delete_rate_id = substr($delete_rate_id, 1);
        $tmp = (isset($_POST ['accounts'])) ? $_POST ['accounts'] : '';
        $size = count($tmp);
        //pr($tmp);
        if (!empty($tmp)) {
            foreach ($tmp as $el) {
                $model = new GlobalFailover;
                $this->data['GlobalFailover'] = $el;
                $model->save($this->data ['GlobalFailover']);
                $this->data['GlobalFailover']['id'] = false;
            }
        }
        if (!empty($delete_rate_id)) {
            $this->GlobalFailover->query("delete  from  global_failover where id in($delete_rate_id)");
        }
        $this->GlobalFailover->create_json_array('#ClientOrigRateTableId', 201, 'The Global Fail Over is modified Successfully !');
        $this->Session->write("m", GlobalFailover::set_validator());
        $this->redirect("/systemparams/failover");
    }

    public function test_update() {
        $_POST['switch_ip'] = '192.168.1.115';
        $_POST['switch_port'] = 5060;
        $_POST['system_admin_email'] = 'w@163.com';
        $_POST['loginemail'] = 'w@163.com';

        $_POST ['smtphost'] = '192.168.1.115';
        $_POST ['smtpport'] = '5060';
        $_POST ['emailusername'] = '88';
        $_POST ['emailpassword'] = '99';
        $_POST ['fromemail'] = '999999';
        $_POST ['emailname'] = '22222222';



        $_POST ['currency'] = 'USA';
        $_POST ['timezone'] = '-800';
        $_POST ['mail_host'] = '192.168.1.115';
        $_POST ['mail_from'] = '5555';

        $this->ajax_update();
    }

    public function ajax_update() {
        if (!$_SESSION['role_menu']['Configuration']['systemparams']['model_w']) {
            $this->redirect_denied();
        }
        Configure::write('debug', 0);


        $switch_ip = $_POST['switch_ip'];
        $switch_port = $_POST['switch_port'];
        $system_admin_email = $_POST['system_admin_email'];
        $loginemail = $_POST ['loginemail'];
        $smtphost = $_POST ['smtphost'];
        $smtpport = $_POST ['smtpport'];
        $emailusername = $_POST ['emailusername'];
        $emailpassword = $_POST ['emailpassword'];
        $fromemail = $_POST ['fromemail'];
        $emailname = $_POST ['emailname'];
        $smtp_secure = $_POST['smtp_secure'];
        $report_count = $_POST['report_count'];
        $search_code_deck = empty($_POST['search_code_deck']) ? '0' : $_POST['search_code_deck'];
        $welcome_message = $_POST['welcome_message'];
        $currency = $_POST ['currency'];
        $timezone = $_POST ['timezone'];
        $realm = $_POST['realm'];
        $workstation = $_POST['workstation'];
        $mail_host = $_POST ['mail_host'];
        $mail_from = $_POST ['mail_from'];
        $ftp_username = $_POST ['ftp_username'];
        $ftp_pass = $_POST ['ftp_pass'];
        $cc_pinLength = $_POST ['cc_pinLength'];
        $dateFormat = $_POST ['dateFormat'];
        $datetimeFormat = $_POST ['datetimeFormat'];
        $csv_delimiter = $_POST ['csv_delimiter'];
        $invoices_tplNo = $_POST ['invoices_tplNo'];
        $invoices_lastNo = $_POST ['invoices_lastNo'];
        $invoices_fields = $_POST ['invoices_fields'];
        $invoices_delay = $_POST ['invoices_delay'];
        $invoices_separate = $_POST ['invoices_separate'];
        $invoices_cdr_fields = $_POST ['invoices_cdr_fields'];
        $dr_period = $_POST ['dr_period'];
        $radius_log_routes = $_POST ['radius_log_routes'];
        $lowBalance_period = $_POST ['lowBalance_period'];
        $events_deleteAfterDays = $_POST ['events_deleteAfterDays'];
        $stats_rotamte_delay = $_POST ['stats_rotate_delay'];
        $rates_deleteAfterDays = $_POST ['rates_deleteAfterDays'];
        $cdrs_deleteAfterDays = $_POST ['cdrs_deleteAfterDays'];
        $logs_deleteAfterDays = $_POST ['logs_deleteAfterDays'];
        $backup_period = $_POST ['backup_period'];
        $backup_leave_last = $_POST ['backup_leave_last'];
        $events_notFoundAccount = $_POST ['events_notFoundAccount'];
        $events_notFoundTariff = $_POST ['events_notFoundTariff'];
        $events_unprofitable = $_POST ['events_unprofitable'];
        $events_alertsZeroTime = $_POST ['events_alertsZeroTime'];
        $lowBalance_period = $_POST ['lowBalance_period'];
        $pdf_tpl = $_POST['pdf_tpl'];
        $tpl_number = $_POST['tpl_number'];
        $finance_email = $_POST['finance_email'];
        $noc_email = $_POST['noc_email'];
        $withdraw_email = $_POST['withdraw_email'];
        //var_dump($withdraw_email);
        //exit;
        $qos_sample_period = $_POST['qos_sample_period'] == '' ? 'NULL' : $_POST['qos_sample_period'];
        $minimal_call_attempt_required = $_POST['minimal_call_attempt_required'] == '' ? 'NULL' : $_POST['minimal_call_attempt_required'];
        $low_call_attempt_handling = $_POST['low_call_attempt_handling'] == '' ? 'NULL' : $_POST['low_call_attempt_handling'];
        $landing_page = $_POST['landing_page'];
        $invoice_name = $_POST['invoice_name'];
        $bar_color = $_POST['bar_color'];
        $inactivity_timeout = empty($_POST['inactivity_timeout']) ? 0 :  $_POST['inactivity_timeout'] ;
        $is_preload = $_POST['is_preload'];
        $yourpay_store_number = $_POST['yourpay_store_number'];
        $paypal_account = $_POST['paypal_account'];
        $switch_alias = $_POST['switch_alias'];
        $ingress_pdd_timeout = $_POST['ingress_pdd_timeout'];
        $egress_pdd_timeout  = $_POST['egress_pdd_timeout'];
        $ring_timeout = $_POST['ring_timeout'];
        $call_timeout = $_POST['call_timeout'];

        $list = $this->Systemparam->query("select  count(*) as  c from system_parameter");
        if (empty($list) || empty($list [0] [0] ['c'])) {
            $sql = "insert  into system_parameter (sys_timezone,sys_currency)values('$timezone','$currency'); ";
            $this->Systemparam->query($sql);
        } else {
            
        }

        $sql = "update system_parameter set ingress_pdd_timeout = $ingress_pdd_timeout, egress_pdd_timeout = $egress_pdd_timeout, 
                 ring_timeout = $ring_timeout, call_timeout = $call_timeout,
                withdraw_email='".addslashes($withdraw_email)."',sys_timezone='$timezone',yourpay_store_number='{$yourpay_store_number}',paypal_account='{$paypal_account}',
		sys_currency='$currency' ,smtphost='$smtphost' ,smtpport='$smtpport' ,emailusername='$emailusername' , smtp_secure={$smtp_secure}, inactivity_timeout = {$inactivity_timeout},is_preload = {$is_preload},switch_alias = '{$switch_alias}',
		emailpassword='$emailpassword',fromemail='$fromemail',emailname='$emailname' ,loginemail='$loginemail',system_admin_email='$system_admin_email',landing_page = $landing_page, invoice_name = '{$invoice_name}', bar_color = '$bar_color', 
		pdf_tpl='" . addslashes($pdf_tpl) . "', tpl_number = {$tpl_number}, realm = '{$realm}', workstation =  '{$workstation}',qos_sample_period = {$qos_sample_period}, report_count = {$report_count}, minimal_call_attempt_required = {$minimal_call_attempt_required},  low_call_attempt_handling = {$low_call_attempt_handling}, default_code_deck = {$search_code_deck},finance_email='" . addslashes($finance_email) . "',  welcome_message = '" . addslashes($welcome_message) . "',noc_email='" . addslashes($noc_email) . "'
		";
        $this->Systemparam->query($sql);
        #configure mail server
        Configure::write('smtp_settings', array(
            'sendmailtype' => 1,
            'smtphost' => $smtphost,
            'smtpport' => $smtpport,
            'loginemail' => $loginemail,
            'emailusername' => $emailusername,
            'emailpassword' => $emailpassword,
            'fromemail' => $fromemail,
            'emailname' => $emailname
                )
        );

        $list = $this->Systemparam->query("
	   select rate  from currency_updates where currency_id = (
   				select  currency_id  from  currency  where code='$currency'
			) and modify_time=(
  					select max(modify_time) from currency_updates where currency_id = (
   select  currency_id  from  currency  where code='$currency'
  )
  )
  
  ");
        $sys_currency = $currency;
        $sys_currency_rate = !empty($list[0][0]['rate']) ? $list[0][0]['rate'] : '';
        $_SESSION['system_currency'] = compact('sys_currency', 'sys_currency_rate');
        $this->set('extensionBeans', '1');
    }

    public function testsmtp1() {
        $info = "";
        if ($this->RequestHandler->IsPost()) {
            $sendAddress = $_POST['email'];
            $cmd = "php " . APP . "testmail.php {$sendAddress}";
            $info = shell_exec($cmd);
        }
        $this->set('info', $info);
    }

    public function testsmtp() {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $info = "";
        if ($this->RequestHandler->IsPost()) {
            $php_bin = Configure::read('php_exe_path');
            $sendAddress = $_POST['email'] ? $_POST['email'] : "''";
            $cmd = "{$php_bin} " . APP . "testmail.php {$sendAddress}";
            $info = shell_exec($cmd);
        }
        echo $info;
    }
    
    
    public function upload_license() {
        
        if ($this->RequestHandler->IsPost()) {
            if (is_uploaded_file($_FILES['license']['tmp_name'])) {
                $destination = Configure::read('system.license');
                $result = move_uploaded_file($_FILES['license']['tmp_name'], $destination);
                if($result) {
                    $this->Systemparam->create_json_array('', 201, __('Succeed!',true));
                } else  {
                    $this->Systemparam->create_json_array('', 101, __('Failed!',true));
                }
                $this->Session->write("m",Systemparam::set_validator ());
            }
        }
        
    }
    
    public function allow_cdr_fields() {        
        
        if ($this->RequestHandler->IsPost()) {
            $selected_fields = $_POST['allow_cdr_fields'];
            $allow_cdr_fields = implode(';', $selected_fields);
            $sql = "UPDATE system_parameter SET allow_cdr_fields = '{$allow_cdr_fields}'";
            $this->Systemparam->query($sql);
            $this->Systemparam->create_json_array('', 201, __('The Allow CDR Fields is modified succesfully',true));
            $this->Session->write("m",Systemparam::set_validator());
        }
        
        $result = $this->Systemparam->query("SELECT allow_cdr_fields FROM system_parameter LIMIT 1");
        
        $allow_cdr_fields = explode(';', $result[0][0]['allow_cdr_fields']);
        
        
        $arr=array(
            'connection_type' => 'W/O Media',
            'release_cause' => 'Release Cause',
            '(case start_time_of_date when 0 then null else to_timestamp(start_time_of_date/1000000) end)' => 'Start Time',
            '(case answer_time_of_date when 0 then null else to_timestamp(answer_time_of_date/1000000) end)' => 'Answer Time',
            'egress_code_asr' => 'Egress CODE ASR',
            'egress_code_acd' => 'Egress CODE ACD',
            '(case release_tod when 0 then null else to_timestamp(release_tod/1000000) end)' => 'End Time',
            'release_cause_from_protocol_stack' => 'Response From Egress',
            'binary_value_of_release_cause_from_protocol_stack' => 'Response TO Ingress',
            'first_release_dialogue' => 'ORIG/TERM Release',
            'trunk_id_origination' => 'Ingress Alias',
            'trunk_id_termination' => 'Egress Alias',
            'origination_source_number' => 'ORIG src Number',
            'origination_destination_number' => 'ORIG DST number',
            'origination_source_host_name' => 'ORIG IP',
            'origination_codec_list' => 'ORIG Codecs',
            'final_route_indication' => 'Final Route',
            'routing_digits' => 'Translation DNIS',
            'translation_ani' => 'Translation ANI',
            'lrn_dnis' => 'LRN Number',
            'call_duration' => 'Call Duration',
            'pdd' => 'PDD(ms)',
            'ring_time' => 'Ring Time(s)',
            'callduration_in_ms' => 'Callduration in_ms',
            'ingress_client_bill_time' => 'Ingress Client Bill Time',
            'ingress_client_bill_result' => 'Ingress Client Bill Result',
            'ingress_bill_minutes' => 'Ingress bill Minutes',
            'ingress_client_cost' => 'Ingress Client Cost',
            'time' => 'Time',
            'ingress_dnis_type' => 'Ingress DNIS Type',
            'ingress_rate_type' => 'Ingress Rate Type',
            'rerate_time' => 'Rerate Time',
            'lrn_number_vendor' => 'LRN Number Vendor',
            'route_plan' => 'Routing Plan Name',
            'dynamic_route' => 'Dynamic Route Name',
            'static_route' => 'Static Route Name',
            'origination_remote_payload_ip_address' => 'orig_media_ip ani',
            'origination_remote_payload_udp_address' => 'orig_media_port ani',
            'termination_remote_payload_ip_address' => 'term_media_ip',
            'termination_remote_payload_udp_address' => 'term_media_port dnis',
            'ingress_client_rate' => 'Ingress Client Rate'
	);
        
        $this->set('fields', $arr);
        $this->set('allow_cdr_fields', $allow_cdr_fields);
    }
    
    
    public function invoice_setting() {
        $this->pageTitle = "Configuration/Invoice Setting";
        
        $cdr_fields = array_merge($incoming_cdr_fields = $this->Systemparam->get_incoming_cdr_fields(),
                $outgoing_cdr_fields = $this->Systemparam->get_outgoing_cdr_fields());
        
        $this->set('cdr_fields', $cdr_fields);
        
        $logo_path = APP . 'webroot' .DS. 'upload'  . DS . 'images' . DS . 'ilogo.png';
        
        if(file_exists($logo_path))
        {
            $logo = $this->webroot . 'upload/images/ilogo.png';
        }
        else
        {
            $logo = $this->webroot . 'images/logo.png';
        }
        
        $this->set('logo', $logo);
        
        if ($this->RequestHandler->IsPost()) {
            
                if (is_uploaded_file($_FILES['logoimg']['tmp_name'])) {
                 
                $invoice_path = APP . 'webroot' .DS. 'upload'  . DS . 'images' . DS . 'ilogo.png';
                $sourcepath = $_FILES['logoimg']['tmp_name'];
                switch ($_FILES['logoimg']['type']) {
                    case 'image/png':
                        $im = imagecreatefrompng($sourcepath);
                        break;
                    case 'image/jpeg':
                        $im = imagecreatefromjpeg($sourcepath);
                        break;
                    case 'image/gif':
                        $im = imagecreatefromgif($sourcepath);
                        break;
                }
                imagepng($im, $invoice_path);
                imagedestroy($im);
            }
            
            $selected_cdr_fields = isset($_POST['cdr_fields']) ? $_POST['cdr_fields'] : array();
            $selected_cdr_fields = implode(',', $selected_cdr_fields);
            
            $invoice_name = $_POST['invoice_name'];
            $tpl_number = $_POST['tpl_number'];
            $pdf_tpl = str_replace("'", "''", $_POST['pdf_tpl']);
            $company_info = str_replace("'", "''", $_POST['company_info']);
            $overlap_invoice_protection = isset($_POST['overlap_invoice_protection']) ? 'true' : 'false';
            $sql = "UPDATE system_parameter SET invoice_name = '{$invoice_name}', 
                    tpl_number = {$tpl_number}  , pdf_tpl = '{$pdf_tpl}', company_info = '{$company_info}', overlap_invoice_protection = {$overlap_invoice_protection}, send_cdr_fields='{$selected_cdr_fields}'";
            $this->Systemparam->query($sql);
            $this->Systemparam->create_json_array('', 201, __('Your Invoice Logo is uploaded successfully!',true));
            $this->Session->write("m",Systemparam::set_validator());
        }
        $sql = "SELECT invoice_name, tpl_number, pdf_tpl, company_info,overlap_invoice_protection,send_cdr_fields FROM system_parameter LIMIT 1";
        $data = $this->Systemparam->query($sql);
        $this->set('data', $data);
    }
    
     public function ftp_conf_change_status($id)
    {
        //Configure::write('debug', 0);
        $this->autoLayout = false;
        $this->autoRender = false;
        $ftpconf = $this->FtpConf->findById($id);
        
        if ($ftpconf['FtpConf']['active']) {
            $ftpconf['FtpConf']['active'] = false;
            $this->FtpConf->create_json_array("", 201, 'The FTP Configuration [' . $ftpconf['FtpConf']['alias'] . '] is inactived successfully !');
        } else {
            $ftpconf['FtpConf']['active'] = true;
            $this->FtpConf->create_json_array("", 201, 'The FTP Configuration [' . $ftpconf['FtpConf']['alias'] . '] is actived successfully !');
        }     
        
        $this->FtpConf->save($ftpconf);
        
        $this->xredirect(array('controller' => 'systemparams', 'action' => 'ftp_conf')); 
    }
    
    public function test_ftp($id)
    {
        Configure::write('debug', 0);
        $this->autoLayout = false;
        $this->autoRender = false;
        $ftp_conf = $this->FtpConf->findById($id);
        // set up basic connection
        $conn_id = ftp_connect($ftp_conf['FtpConf']['server_ip'], $ftp_conf['FtpConf']['server_port']); 

        // login with username and password
        $login_result = ftp_login($conn_id, $ftp_conf['FtpConf']['username'], $ftp_conf['FtpConf']['password']); 

        // check connection
        if ((!$conn_id) || (!$login_result)) { 
            if (!$conn_id)
            {
                echo "FTP connection has failed!";
            }
            if (!$login_result)
            {
                echo "Unable to connect to {$ftp_conf['FtpConf']['server_ip']} for user {$ftp_conf['FtpConf']['username']}"; 
            }
            exit; 
        } else {
            echo "Connected to {$ftp_conf['FtpConf']['server_ip']}, for user {$ftp_conf['FtpConf']['username']}";
        }

        // close the FTP stream 
        ftp_close($conn_id); 
    }
    
    public function ftp_conf_edit($id = '') {
        $this->pageTitle = "Configuration/FTP Configuration";
        
        $arr = array(
            /*
            'connection_type' => 'W/O Media',
            'release_cause' => 'Release Cause',
            'start_time_of_date' => 'Start Time',
            'answer_time_of_date' => 'Answer Time',
            'egress_code_asr' => 'Egress CODE ASR',
            'egress_code_acd' => 'Egress CODE ACD',
            'release_tod' => 'End Time',
            'release_cause_from_protocol_stack' => 'Response From Egress',
            'binary_value_of_release_cause_from_protocol_stack' => 'Response TO Ingress',
            'first_release_dialogue' => 'ORIG/TERM Release',
            'trunk_id_origination' => 'Ingress Alias',
            'origination_source_number' => 'ORIG src Number',
            'origination_destination_number' => 'ORIG DST number',
            'origination_source_host_name' => 'ORIG IP',
            'origination_codec_list' => 'ORIG Codecs',
            'final_route_indication' => 'Final Route',
            'routing_digits' => 'Translation DNIS',
            'translation_ani' => 'Translation ANI',
            'lrn_dnis' => 'LRN Number',
            'call_duration' => 'Call Duration',
            'pdd' => 'PDD(ms)',
            'ring_time' => 'Ring Time(s)',
            'callduration_in_ms' => 'Callduration in_ms',
            'ingress_client_bill_time' => 'Ingress Client Bill Time',
            'ingress_client_bill_result' => 'Ingress Client Bill Result',
            'ingress_bill_minutes' => 'Ingress bill Minutes',
            'ingress_client_cost' => 'Ingress Client Cost',
            'time' => 'Time',
            'ingress_dnis_type' => 'Ingress DNIS Type',
            'ingress_rate_type' => 'Ingress Rate Type',
            'rerate_time' => 'Rerate Time',
            'lrn_number_vendor' => 'LRN Number Vendor',
            'route_plan' => 'Routing Plan Name',
            'dynamic_route' => 'Dynamic Route Name',
            'static_route' => 'Static Route Name',
            'origination_remote_payload_ip_address' => 'orig_media_ip ani',
            'origination_remote_payload_udp_address' => 'orig_media_port ani',
            'termination_remote_payload_ip_address' => 'term_media_ip',
            'termination_remote_payload_udp_address' => 'term_media_port dnis',
            'ingress_client_rate' => 'Ingress Client Rate',
            'origination_destination_host_name'=> 'Class4 IP',
            'trunk_id_termination' => 'Egress Alias',
            'termination_destination_host_name' => 'Egress IP',
             * 
             */
            'connection_type' => 'W/O Media',
            'orig_call_duration' => 'Orig Call Duration',
            'orig_delay_second' => 'Orig Delay Second',
            'term_delay_second' => 'Term Delay Second',
            'release_cause' => 'Release Cause',
            '(case start_time_of_date when 0 then null else to_timestamp(start_time_of_date/1000000) end)' => 'Start Time',
            '(case answer_time_of_date when 0 then null else to_timestamp(answer_time_of_date/1000000) end)' => 'Answer Time',
            'egress_code_asr' => 'Egress CODE ASR',
            'egress_code_acd' => 'Egress CODE ACD',
            '(case release_tod when 0 then null else to_timestamp(release_tod/1000000) end)' => 'End Time',
            'release_cause_from_protocol_stack' => 'Response From Egress',
            'binary_value_of_release_cause_from_protocol_stack' => 'Response TO Ingress',
            'first_release_dialogue' => 'ORIG/TERM Release',
            'trunk_id_origination' => 'Ingress Alias',
            'origination_source_number' => 'ORIG src Number',
            'origination_destination_number' => 'ORIG DST Number',
            'origination_call_id' => 'Origination Call ID',
            'origination_source_host_name' => 'ORIG IP',
            'origination_codec_list' => 'ORIG Codecs',
            'trunk_id_termination' => 'Egress Alias',
            'termination_source_number' => 'TERM src Number',
            'termination_destination_number' => 'TERM DST Number',
            'termination_destination_host_name' => 'TERM IP',
            'termination_codec_list' => 'TERM Codecs',
            'termination_source_host_name' => 'Outbound IP address',
            'final_route_indication' => 'Final Route',
            'routing_digits' => 'Translation DNIS',
            'translation_ani' => 'Translation ANI',
            'lrn_dnis' => 'LRN Number',
            'call_duration' => 'Call Duration',
            'pdd' => 'PDD(ms)',
            'ring_time' => 'Ring Time(s)',
            'callduration_in_ms' => 'Callduration in ms',
            'ingress_id' => 'Ingress ID',
            'ingress_client_id' => 'Ingress Client Name',
            'ingress_client_rate_table_id' => 'Ingress Client Rate Table Name',
            'ingress_client_rate' => 'Ingress Client Rate',
            'lnp_dipping_cost' => 'Lnp dipping Cost',
            'ingress_client_currency' => 'Ingress Client Currency',
            'ingress_client_bill_time' => 'Ingress Client Bill Time',
            'ingress_client_bill_result' => 'Ingress Client Bill Result',
            'ingress_bill_minutes' => 'Ingress Bill Minutes',
            'ingress_client_cost' => 'Ingress Client Cost',
            'termination_call_id' => 'Termination Call ID',
            'time' => 'Time',
            'egress_id' => 'Egress Name',
            'egress_rate_table_id' => 'Egress Rate Table Name',
            'egress_rate' => 'Egress Rate',
            'egress_cost' => 'Egress Cost',
            'egress_bill_time' => 'Egress Bill Time',
            'egress_client_id' => 'Egress Client Name',
            'egress_client_currency' => 'Egress Client Currency',
            'egress_six_seconds' => 'Egress Six Seconds',
            'egress_bill_minutes' => 'Egress Bill Minutes',
            'egress_bill_result' => 'Egress Bill Result',
            'ingress_dnis_type' => 'Ingress DNIS Type',
            'ingress_rate_type' => 'Ingress Rate Type',
            'egress_dnis_type' => 'Egress DNIS Type',
            'egress_rate_type' => 'Egress Rate Type',
            'egress_erro_string' => 'Egress Trunk Trace',
            'ingress_rate_id' => 'Ingress Rate ID',
            'egress_rate_id' => 'Egress Rate ID',
            'orig_country' => 'Orig Country',
            'orig_code_name' => 'Orig Code Name',
            'orig_code' => 'Orig Code',
            'term_country' => 'Term Country',
            'term_code_name' => 'Term Code Name',
            'term_code' => 'Term Code',
            'rerate_time' => 'Rerate Time',
            'lrn_number_vendor' => 'LRN Source',
            'route_plan' => 'Routing Plan Name',
            'dynamic_route' => 'Dynamic Route Name',
            'static_route' => 'Static Route Name',
            'origination_remote_payload_ip_address' => 'Orig Media Ip Ani',
            'origination_remote_payload_udp_address' => 'Orig Media Port Ani',
            'termination_remote_payload_ip_address' => 'Term Media Ip',
            'termination_remote_payload_udp_address' => 'Term Media Port Dnis',
            'origination_destination_host_name' => 'Class4_IP',
            'origination_local_payload_ip_address' => 'Origination Local Payload IP',
            'origination_local_payload_udp_address' => 'Origination Local Payload Port',
            'termination_local_payload_ip_address' => 'Termination Local Payload IP',
            'termination_local_payload_udp_address' => 'Termination Local Payload Port',
            'trunk_type' => 'Trunk Type',
            'origination_destination_host_name' => 'Origination Profile IP',
            'origination_profile_port' => 'Origination Profile Port',
            'termination_source_host_name' => 'Termination Profile IP',
            'termination_profile_port' => 'Termination Profile Port',
            '(SELECT name FROM rate_table WHERE rate_table_id = client_cdr.ingress_client_rate_table_id)' => 'Rate Table Name',
            '(select name from route_strategy where route_strategy_id = client_cdr.route_plan)' => 'Routing Plan',
            'route_prefix' => "Teach Prefix",
	);
        
        if ($this->RequestHandler->IsPost()) {
            $alias = $_POST['alias'];
            $time = isset($_POST['time']) ? $_POST['time'] : '';
            $server_ip = $_POST['server_ip'];
            $server_port = $_POST['server_port'];
            $username = $_POST['username'];
            $password = $_POST['password'];
            $frequency = $_POST['frequency'];
            
            $fields = isset($_POST['fields']) ?  $_POST['fields'] : array();
            $headers = array();
            
            foreach ($fields as $field) {
                array_push($headers, $arr[$field]);
            }
            $headers = implode(',', $headers);
            
            $fields = implode(',', $_POST['fields']) ;
            //$headers = $_POST['headers'];
            $include_headers = $_POST['contain_headers'];
            $file_type = $_POST['file_type'];
            $server_dir = $_POST['server_dir'];
            $max_lines = $_POST['max_lines'];
            /*
            $ingress_carriers = @implode(',', $_POST['ingress_carriers']) or '';
            $egress_carriers = @implode(',', $_POST['egress_carriers']) or '';
            $ingress_carriers_all = isset($_POST['ingress_carriers_all']) ? 'true' : 'false';
            $egress_carriers_all  = isset($_POST['egress_carriers_all']) ? 'true' : 'false';
             * 
             */
            $duration = $_POST['duration'];
            $every_hours = isset($_POST['every_hours']) ? $_POST['every_hours'] : NULL;
            $ingress_release_cause = isset($_POST['ingress_release_cause']) ? $_POST['ingress_release_cause'] : array('0');
            $egress_release_cause = isset($_POST['egress_release_cause']) ? $_POST['egress_release_cause'] : array('0');
            
            
            $ingresses = @implode(',', $_POST['ingresses']) or '';
            $egresses = @implode(',', $_POST['egresses']) or '';
            $ingresses_all = isset($_POST['ingresses_all']) ? 'true' : 'false';
            $egresses_all  = isset($_POST['egresses_all']) ? 'true' : 'false';
            $file_breakdown = $_POST['file_breakdown'];
            
            
            
            $conditions = array();         
            /*
            if ($ingress_carriers_all === 'false')
            {
                if (empty($ingress_carriers))
                {
                    $this->Systemparam->create_json_array('', 101, __('You must choose at least one carrier!',true));
                    $this->Session->write("m",Systemparam::set_validator());
                    $this->redirect('/systemparams/ftp_conf');
                }
                    
                array_push($conditions, "ingress_client_id in  ($ingress_carriers)");
            }
            
            if ($egress_carriers_all === 'false')
            {
                if (empty($egress_carriers))
                {
                    $this->Systemparam->create_json_array('', 101, __('You must choose at least one carrier!',true));
                    $this->Session->write("m",Systemparam::set_validator());
                    $this->redirect('/systemparams/ftp_conf');
                }
                array_push($conditions, "egress_client_id in  ($egress_carriers)");
            }
            */
            if ($ingresses_all === 'false')
            {
                if (empty($ingresses))
                {
                    $this->Systemparam->create_json_array('', 101, __('You must choose at least one ingress trunk!',true));
                    $this->Session->write("m",Systemparam::set_validator());
                    $this->redirect('/systemparams/ftp_conf_edit/' . $id);
                }
                array_push($conditions, " ingress_id in  ($ingresses)");
            }
            
            if ($egresses_all === 'false')
            {
                if (empty($egresses))
                {
                    $this->Systemparam->create_json_array('', 101, __('You must choose at least one egress trunk!',true));
                    $this->Session->write("m",Systemparam::set_validator());
                    $this->redirect('/systemparams/ftp_conf_edit/' . $id);
                }
                array_push($conditions, "egress_id in  ($egresses)");
            }
            
            if ($duration == 1)
            {
                array_push($conditions, "case when call_duration is null then 0 else call_duration end>0");
            }
            else if ($duration == 2)
            {
                array_push($conditions, "case when call_duration is null then 0 else call_duration end=0");
            }
            
            if (in_array('0', $ingress_release_cause))
            {                
                $ingress_release_cause = '0';
            }
            else
            {
                $ingress_release_cause_conditions = array();
                foreach ($ingress_release_cause as $ingress_release_item) {
                    array_push($ingress_release_cause_conditions, "binary_value_of_release_cause_from_protocol_stack like''%{$ingress_release_item}%''");
                }
                $ingress_release_cause_condtion = "(" . implode(' or ', $ingress_release_cause_conditions) . ")";
                array_push($conditions, $ingress_release_cause_condtion);
                
                $ingress_release_cause = implode(',', $ingress_release_cause);
            }
            
            if (in_array('0', $egress_release_cause))
            {
                $egress_release_cause = '0';
            }
            else
            {
                $egress_release_cause_conditions = array();
                foreach ($egress_release_cause as $egress_release_item) {
                    array_push($egress_release_cause_conditions, "release_cause_from_protocol_stack like''%{$egress_release_item}%''");
                }
                $egress_release_cause_condtion = "(" . implode(' or ', $egress_release_cause_conditions) . ")";
                array_push($conditions, $egress_release_cause_condtion);
                
                $egress_release_cause = implode(',', $egress_release_cause);
            }
            
            $conditions_str = implode(' and ', $conditions);
            
            
            $sql = "update ftp_conf set server_ip = '$server_ip', server_port = '$server_port', username = '$username', password = '$password', 
                    frequency = $frequency, fields = '$fields', headers = '$headers', contain_headers = {$include_headers}, file_type = {$file_type}, 
                    ingresses = '{$ingresses}', egresses = '{$egresses}', duration = {$duration}, 
                    ingress_release_cause = '{$ingress_release_cause}', egress_release_cause = '{$egress_release_cause}', ingresses_all = {$ingresses_all},
                    egresses_all = {$egresses_all}, conditions = '{$conditions_str}', alias = '{$alias}', time = '{$time}', server_dir = '{$server_dir}', max_lines = {$max_lines}, every_hours = {$every_hours}, file_breakdown = {$file_breakdown} where id = {$id}";
            $this->Systemparam->query($sql);
            $this->Systemparam->create_json_array('', 201, __('The FTP CDR [' . $alias . '] is modified successfully',true));
            $this->Session->write("m",Systemparam::set_validator());
        }
        
        $sql = "SELECT * FROM ftp_conf where id = {$id} LIMIT 1";
        $data = $this->Systemparam->query($sql);
        
        asort($arr);
        $ingress_carriers = $this->Systemparam->get_carriers('ingress');
        $egress_carriers  = $this->Systemparam->get_carriers('egress');
        $ingresses        = $this->Systemparam->get_resource('ingress');
        $egresses         = $this->Systemparam->get_resource('egress');
        $this->set('ingress_carriers', $ingress_carriers);
        $this->set('egress_carriers', $egress_carriers);
        $this->set('ingresses', $ingresses);
        $this->set('egresses', $egresses);
        $this->set('back_selects', $arr);
        $this->set('data', $data);
    }
    
    public function ftp_conf_create() {
        $this->pageTitle = "Configuration/FTP Configuration";
        
        $arr = array(
            /*
            'connection_type' => 'W/O Media',
            'release_cause' => 'Release Cause',
            'start_time_of_date' => 'Start Time',
            'answer_time_of_date' => 'Answer Time',
            'egress_code_asr' => 'Egress CODE ASR',
            'egress_code_acd' => 'Egress CODE ACD',
            'release_tod' => 'End Time',
            'release_cause_from_protocol_stack' => 'Response From Egress',
            'binary_value_of_release_cause_from_protocol_stack' => 'Response TO Ingress',
            'first_release_dialogue' => 'ORIG/TERM Release',
            'trunk_id_origination' => 'Ingress Alias',
            'origination_source_number' => 'ORIG src Number',
            'origination_destination_number' => 'ORIG DST number',
            'origination_source_host_name' => 'ORIG IP',
            'origination_codec_list' => 'ORIG Codecs',
            'final_route_indication' => 'Final Route',
            'routing_digits' => 'Translation DNIS',
            'translation_ani' => 'Translation ANI',
            'lrn_dnis' => 'LRN Number',
            'call_duration' => 'Call Duration',
            'pdd' => 'PDD(ms)',
            'ring_time' => 'Ring Time(s)',
            'callduration_in_ms' => 'Callduration in_ms',
            'ingress_client_bill_time' => 'Ingress Client Bill Time',
            'ingress_client_bill_result' => 'Ingress Client Bill Result',
            'ingress_bill_minutes' => 'Ingress bill Minutes',
            'ingress_client_cost' => 'Ingress Client Cost',
            'time' => 'Time',
            'ingress_dnis_type' => 'Ingress DNIS Type',
            'ingress_rate_type' => 'Ingress Rate Type',
            'rerate_time' => 'Rerate Time',
            'lrn_number_vendor' => 'LRN Number Vendor',
            'route_plan' => 'Routing Plan Name',
            'dynamic_route' => 'Dynamic Route Name',
            'static_route' => 'Static Route Name',
            'origination_remote_payload_ip_address' => 'orig_media_ip ani',
            'origination_remote_payload_udp_address' => 'orig_media_port ani',
            'termination_remote_payload_ip_address' => 'term_media_ip',
            'termination_remote_payload_udp_address' => 'term_media_port dnis',
            'ingress_client_rate' => 'Ingress Client Rate',
            'origination_destination_host_name'=> 'Class4 IP',
            'trunk_id_termination' => 'Egress Alias',
            'termination_destination_host_name' => 'Egress IP',
             * 
             */
            'connection_type' => 'W/O Media',
            'orig_call_duration' => 'Orig Call Duration',
            'orig_delay_second' => 'Orig Delay Second',
            'term_delay_second' => 'Term Delay Second',
            'release_cause' => 'Release Cause',
            '(case start_time_of_date when 0 then null else to_timestamp(start_time_of_date/1000000) end)' => 'Start Time',
            '(case answer_time_of_date when 0 then null else to_timestamp(answer_time_of_date/1000000) end)' => 'Answer Time',
            'egress_code_asr' => 'Egress CODE ASR',
            'egress_code_acd' => 'Egress CODE ACD',
            '(case release_tod when 0 then null else to_timestamp(release_tod/1000000) end)' => 'End Time',
            'release_cause_from_protocol_stack' => 'Response From Egress',
            'binary_value_of_release_cause_from_protocol_stack' => 'Response TO Ingress',
            'first_release_dialogue' => 'ORIG/TERM Release',
            'trunk_id_origination' => 'Ingress Alias',
            'origination_source_number' => 'ORIG src Number',
            'origination_destination_number' => 'ORIG DST Number',
            'origination_call_id' => 'Origination Call ID',
            'origination_source_host_name' => 'ORIG IP',
            'origination_codec_list' => 'ORIG Codecs',
            'trunk_id_termination' => 'Egress Alias',
            'termination_source_number' => 'TERM src Number',
            'termination_destination_number' => 'TERM DST Number',
            'termination_destination_host_name' => 'TERM IP',
            'termination_codec_list' => 'TERM Codecs',
            'termination_source_host_name' => 'Outbound IP address',
            'final_route_indication' => 'Final Route',
            'routing_digits' => 'Translation DNIS',
            'translation_ani' => 'Translation ANI',
            'lrn_dnis' => 'LRN Number',
            'call_duration' => 'Call Duration',
            'pdd' => 'PDD(ms)',
            'ring_time' => 'Ring Time(s)',
            'callduration_in_ms' => 'Callduration in ms',
            'ingress_id' => 'Ingress ID',
            'ingress_client_id' => 'Ingress Client Name',
            'ingress_client_rate_table_id' => 'Ingress Client Rate Table Name',
            'ingress_client_rate' => 'Ingress Client Rate',
            'lnp_dipping_cost' => 'Lnp dipping Cost',
            'ingress_client_currency' => 'Ingress Client Currency',
            'ingress_client_bill_time' => 'Ingress Client Bill Time',
            'ingress_client_bill_result' => 'Ingress Client Bill Result',
            'ingress_bill_minutes' => 'Ingress Bill Minutes',
            'ingress_client_cost' => 'Ingress Client Cost',
            'termination_call_id' => 'Termination Call ID',
            'time' => 'Time',
            'egress_id' => 'Egress Name',
            'egress_rate_table_id' => 'Egress Rate Table Name',
            'egress_rate' => 'Egress Rate',
            'egress_cost' => 'Egress Cost',
            'egress_bill_time' => 'Egress Bill Time',
            'egress_client_id' => 'Egress Client Name',
            'egress_client_currency' => 'Egress Client Currency',
            'egress_six_seconds' => 'Egress Six Seconds',
            'egress_bill_minutes' => 'Egress Bill Minutes',
            'egress_bill_result' => 'Egress Bill Result',
            'ingress_dnis_type' => 'Ingress DNIS Type',
            'ingress_rate_type' => 'Ingress Rate Type',
            'egress_dnis_type' => 'Egress DNIS Type',
            'egress_rate_type' => 'Egress Rate Type',
            'egress_erro_string' => 'Egress Trunk Trace',
            'ingress_rate_id' => 'Ingress Rate ID',
            'egress_rate_id' => 'Egress Rate ID',
            'orig_country' => 'Orig Country',
            'orig_code_name' => 'Orig Code Name',
            'orig_code' => 'Orig Code',
            'term_country' => 'Term Country',
            'term_code_name' => 'Term Code Name',
            'term_code' => 'Term Code',
            'rerate_time' => 'Rerate Time',
            'lrn_number_vendor' => 'LRN Source',
            'route_plan' => 'Routing Plan Name',
            'dynamic_route' => 'Dynamic Route Name',
            'static_route' => 'Static Route Name',
            'origination_remote_payload_ip_address' => 'Orig Media Ip Ani',
            'origination_remote_payload_udp_address' => 'Orig Media Port Ani',
            'termination_remote_payload_ip_address' => 'Term Media Ip',
            'termination_remote_payload_udp_address' => 'Term Media Port Dnis',
            'origination_destination_host_name' => 'Class4_IP',
            'origination_local_payload_ip_address' => 'Origination Local Payload IP',
            'origination_local_payload_udp_address' => 'Origination Local Payload Port',
            'termination_local_payload_ip_address' => 'Termination Local Payload IP',
            'termination_local_payload_udp_address' => 'Termination Local Payload Port',
            'trunk_type' => 'Trunk Type',
            'origination_destination_host_name' => 'Origination Profile IP',
            'origination_profile_port' => 'Origination Profile Port',
            'termination_source_host_name' => 'Termination Profile IP',
            'termination_profile_port' => 'Termination Profile Port',
            '(SELECT name FROM rate_table WHERE rate_table_id = client_cdr.ingress_client_rate_table_id)' => 'Rate Table Name',
            '(select name from route_strategy where route_strategy_id = client_cdr.route_plan)' => 'Routing Plan',
            'route_prefix' => "Teach Prefix",
	);
        
        if ($this->RequestHandler->IsPost()) {
            $alias = $_POST['alias'];
            $time = isset($_POST['time']) ? $_POST['time'] : '';
            $server_ip = $_POST['server_ip'];
            $server_port = $_POST['server_port'];
            $username = $_POST['username'];
            $password = $_POST['password'];
            $frequency = $_POST['frequency'];
            $fields = isset($_POST['fields']) ?  $_POST['fields'] : array();
            $headers = array();
            
            foreach ($fields as $field) {
                array_push($headers, $arr[$field]);
            }
            $headers = implode(',', $headers);
            
            $fields = implode(',', $_POST['fields']) ;
            $include_headers = $_POST['contain_headers'];
            $file_type = $_POST['file_type'];
            $server_dir = $_POST['server_dir'];
            $max_lines = $_POST['max_lines'];
            /*
            $ingress_carriers = @implode(',', $_POST['ingress_carriers']) or '';
            $egress_carriers = @implode(',', $_POST['egress_carriers']) or '';
            $ingress_carriers_all = isset($_POST['ingress_carriers_all']) ? 'true' : 'false';
            $egress_carriers_all  = isset($_POST['egress_carriers_all']) ? 'true' : 'false';
             * 
             */
            $duration = $_POST['duration'];
            $every_hours = isset($_POST['every_hours']) ? $_POST['every_hours'] : NULL;
            $ingress_release_cause = isset($_POST['ingress_release_cause']) ? $_POST['ingress_release_cause'] : array('0');
            $egress_release_cause = isset($_POST['egress_release_cause']) ? $_POST['egress_release_cause'] : array('0');
            $ingresses = @implode(',', $_POST['ingresses']) or '';
            $egresses = @implode(',', $_POST['egresses']) or '';
            $ingresses_all = isset($_POST['ingresses_all']) ? 'true' : 'false';
            $egresses_all  = isset($_POST['egresses_all']) ? 'true' : 'false';
            $file_breakdown = $_POST['file_breakdown'];
            
            $conditions = array();         
            if ($ingresses_all === 'false')
            {
                if (empty($ingresses))
                {
                    $this->Systemparam->create_json_array('', 101, __('You must choose at least one ingress trunk!',true));
                    $this->Session->write("m",Systemparam::set_validator());
                    $this->redirect('/systemparams/ftp_conf_create');
                }
                array_push($conditions, " ingress_id in  ($ingresses)");
            }
            
            if ($egresses_all === 'false')
            {
                if (empty($egresses))
                {
                    $this->Systemparam->create_json_array('', 101, __('You must choose at least one egress trunk!',true));
                    $this->Session->write("m",Systemparam::set_validator());
                    $this->redirect('/systemparams/ftp_conf_create');
                }
                array_push($conditions, "egress_id in  ($egresses)");
            }
            
            if ($duration == 1)
            {
                array_push($conditions, "case when call_duration is null then 0 else call_duration end>0");
            }
            else if ($duration == 2)
            {
                array_push($conditions, "case when call_duration is null then 0 else call_duration end=0");
            }
            
            if (in_array('0', $ingress_release_cause))
            {                
                $ingress_release_cause = '0';
            }
            else
            {
                $ingress_release_cause_conditions = array();
                foreach ($ingress_release_cause as $ingress_release_item) {
                    array_push($ingress_release_cause_conditions, "binary_value_of_release_cause_from_protocol_stack like''%{$ingress_release_item}%''");
                }
                $ingress_release_cause_condtion = "(" + implode(' or ', $ingress_release_cause_conditions) + ")";
                array_push($conditions, $ingress_release_cause_condtion);
                
                $ingress_release_cause = implode(',', $ingress_release_cause);
            }
            
            if (in_array('0', $egress_release_cause))
            {
                $egress_release_cause = '0';
            }
            else
            {
                $egress_release_cause_conditions = array();
                foreach ($egress_release_cause as $egress_release_item) {
                    array_push($egress_release_cause_conditions, "release_cause_from_protocol_stack like''%{$egress_release_item}%''");
                }
                $egress_release_cause_condtion = "(" + implode(' or ', $egress_release_cause_conditions) + ")";
                array_push($conditions, $egress_release_cause_condtion);
                
                $egress_release_cause = implode(',', $egress_release_cause);
            }
            
            $conditions_str = implode(' and ', $conditions);
            
                    
            $sql = "insert into ftp_conf (server_ip,server_port,username,password,frequency,fields,headers,contain_headers,file_type,ingresses,
                    egresses,duration,ingress_release_cause,egress_release_cause,ingresses_all,egresses_all,conditions,alias,time, server_dir,max_lines, every_hours, file_breakdown) values ('$server_ip',
                    '$server_port','$username','$password',$frequency,'$fields','$headers', {$include_headers},{$file_type}, '{$ingresses}','{$egresses}',
                    {$duration}, '{$ingress_release_cause}','{$egress_release_cause}',{$ingresses_all},{$egresses_all},'{$conditions_str}','{$alias}','{$time}', '{$server_dir}', {$max_lines}, {$every_hours}, {$file_breakdown} 
                    )";
                    
            $this->Systemparam->query($sql);
            $this->Systemparam->create_json_array('', 201, __('The FTP CDR [' . $alias . '] is created successfully',true));
            $this->Session->write("m",Systemparam::set_validator());
            $this->redirect('/systemparams/ftp_conf');
        }
        
        $data = array(
            array(
                array(
                     'server_ip' => '',
                    'server_port' => '',
                    'username' => '',
                    'password' => '',
                    'frequency' => 1,
                    'fields' => '',
                    'headers' => '',
                    'contain_headers' => 1,
                    'file_type' => 1,
                    'duration' => '',
                    'ingress_release_cause' => '0',
                    'egress_release_cause' => '0',
                    'conditions' => '',
                    'ingresses' => '',
                    'egresses' => '',
                    'ingresses_all' => 0,
                    'egresses_all' => 0,
                    "time" => '00:00',
                    'alias' => '',
                    'every_hours' => 1,
                    'file_breakdown' => 0,
                )
               
            )
        );
        
        
        
        asort($arr);
        $ingress_carriers = $this->Systemparam->get_carriers('ingress');
        $egress_carriers  = $this->Systemparam->get_carriers('egress');
        $ingresses        = $this->Systemparam->get_resource('ingress');
        $egresses         = $this->Systemparam->get_resource('egress');
        $this->set('ingress_carriers', $ingress_carriers);
        $this->set('egress_carriers', $egress_carriers);
        $this->set('ingresses', $ingresses);
        $this->set('egresses', $egresses);
        $this->set('back_selects', $arr);
        $this->set('data', $data);
    }
    
    public function ftp_conf_delete($id)
    {
        $this->autoRender = false;
        $this->autoLayout = false;
        $ftp = $this->FtpConf->findById($id);
        $alias = $ftp['FtpConf']['alias'];
        $this->FtpConf->del($id);
        $this->Systemparam->create_json_array('', 201, __('The FTP CDR [' . $alias . '] is deleted successfully',true));
        $this->Session->write("m",Systemparam::set_validator());
        $this->redirect('/systemparams/ftp_conf');
    }
    
    public function ftp_trigger() {
        if ($this->RequestHandler->IsPost()) {
            $script_path = Configure::read('script.path');
            $conf_path = Configure::read('script.conf');
            $start_time = $_POST['start_time'];
            $end_time = $_POST['end_time'];
            $timezone = $_POST['gmt'];
            $id = $_POST['alias'];
            $file_breakdown = $_POST['file_breakdown'];
            $cmd = "perl {$script_path}/class4_ftp_cdr.pl -c {$conf_path}  -s '{$start_time} {$timezone}' -e '{$end_time} {$timezone}' -n {$id} -p {$file_breakdown} >/dev/null &";
            $result = shell_exec($cmd);
            $this->Systemparam->create_json_array('', 201, __('Succeeded',true));
            $this->Session->write("m",Systemparam::set_validator());
        }
        $ftp_list = $this->Systemparam->get_all_ftp();
        $this->set('ftp_list', $ftp_list);
    }
    
    public function ftp_log() {
        $this->pageTitle = "Configuration/FTP Configuration";
        $this->paginate = array(
            'limit' => 100,
            'order' => array(
                'id' => 'desc',
            ),
        );
        $this->set('status', array(-1 => 'FTP Connect Error', -2 => 'FTP Auth Error', -3 => 'FTP Upload Error', 1 => 'Open CDR Directory Error', 2=> 'In Progress', 3 => 'Completed'));
        $this->data = $this->paginate('FtpCdrLog');
        
        foreach ($this->data as &$item)
        {
            $item['details'] = $this->FtpCdrLogDetail->find('all', array(
               'conditions' => array(
                   'ftp_cdr_log_id' => $item['FtpCdrLog']['id'],
               ) 
            ));
        }
        
    }
    
    public function ftp_log_detail($ftp_log_id) {
        $this->pageTitle = "Configuration/FTP Configuration";
        $this->paginate = array(
            'limit' => 100,
            'order' => array(
                'id' => 'asc',
            ),
            'conditions' => array(
                'ftp_cdr_log_id' => $ftp_log_id
            ),
        );
        $this->data = $this->paginate('FtpCdrLogDetail');
    }
    
    public function ftp_log_delete($ftp_log_detail_id) {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $sql = "select *,ftp_cdr_log_detail.ftp_dir as ftp_dir_real from ftp_cdr_log_detail left join 

ftp_cdr_log on ftp_cdr_log_detail.ftp_cdr_log_id = ftp_cdr_log.id where ftp_cdr_log_detail.id = {$ftp_log_detail_id}";
        $result = $this->FtpCdrLogDetail->query($sql);
        $sql1 = "select * from ftp_conf where id = {$result[0][0]['ftp_conf_id']}";
        $result1 = $this->FtpCdrLogDetail->query($sql1);
        $server_file = $result[0][0]['ftp_dir_real'] . '/' . $result[0][0]['file_name'];
        
        // set up basic connection
        $conn_id = ftp_connect($result1[0][0]['server_ip'], $result1[0][0]['server_port']);
        
        if ($conn_id)
        {
            $this->FtpServerLog->insert_log("CONNECT {$result1[0][0]['server_ip']}", "SUCCESS");
        }
        else
        {
            $this->FtpServerLog->insert_log("CONNECT {$result1[0][0]['server_ip']}", "Fail");
        }
        $basename = basename($server_file);

        // login with username and password
        $login_result = ftp_login($conn_id, $result1[0][0]['username'], $result1[0][0]['password']);
        
        if ($login_result)
        {
            $this->FtpServerLog->insert_log("AUTH {$result1[0][0]['username']}", "SUCCESS");
        }
        else
        {
            $this->FtpServerLog->insert_log("AUTH {$result1[0][0]['username']}", "Fail");
        }
        
        // try to download $server_file and save to $local_file
        if (ftp_delete($conn_id, $server_file)) {
            $this->FtpServerLog->insert_log("DEL {$server_file}", "SUCCESS");
           $this->FtpCdrLogDetail->del($ftp_log_detail_id);
           $this->FtpCdrLogDetail->create_json_array('', 201, __('The FTP CDR FILE [' . $basename . '] is deleted successfully',true));
           $this->Session->write("m",FtpCdrLogDetail::set_validator());
           $this->redirect('/systemparams/ftp_log');
        } else {
            echo "Could not delete $server_file";
            $this->FtpServerLog->insert_log("DEL {$server_file}", "Fail");
        }

        // close the connection
        ftp_close($conn_id);
        
    }
    
    public function ftp_download($ftp_log_detail_id) {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $sql = "select *,ftp_cdr_log_detail.ftp_dir as ftp_dir_real from ftp_cdr_log_detail left join 

ftp_cdr_log on ftp_cdr_log_detail.ftp_cdr_log_id = ftp_cdr_log.id where ftp_cdr_log_detail.id = {$ftp_log_detail_id}";
        $result = $this->FtpCdrLogDetail->query($sql);
        
        $sql1 = "select * from ftp_conf where id = {$result[0][0]['ftp_conf_id']}";
        $result1 = $this->FtpCdrLogDetail->query($sql1);
        
        // define some variables
        
        
        $local_file = $path = APP . 'tmp' . DS . 'ftp' . DS . $result[0][0]['file_name'];
        $server_file = $result[0][0]['ftp_dir_real'] . '/' . $result[0][0]['file_name'];      
        

        // set up basic connection
        $conn_id = ftp_connect($result1[0][0]['server_ip'], $result1[0][0]['server_port']);
        
        
        if ($conn_id)
        {
            $this->FtpServerLog->insert_log("CONNECT {$result1[0][0]['server_ip']}", "SUCCESS");
        }
        else
        {
            $this->FtpServerLog->insert_log("CONNECT {$result1[0][0]['server_ip']}", "Fail");
        }

        // login with username and password
        $login_result = ftp_login($conn_id, $result1[0][0]['username'], $result1[0][0]['password']);
        
        if ($login_result)
        {
            $this->FtpServerLog->insert_log("AUTH {$result1[0][0]['username']}", "SUCCESS");
        }
        else
        {
            $this->FtpServerLog->insert_log("AUTH {$result1[0][0]['username']}", "Fail");
        }
        
        // try to download $server_file and save to $local_file
        if (ftp_get($conn_id, $local_file, $server_file, FTP_BINARY)) {
            
            $this->FtpServerLog->insert_log("GET {$server_file}", "SUCCESS");
            ob_clean();
            
            header("Content-type: application/octet-stream");

            //处理中文文件名
            $ua = $_SERVER["HTTP_USER_AGENT"];
            $encoded_filename = rawurlencode($result[0][0]['file_name']);
            if (preg_match("/MSIE/", $ua)) {
                header('Content-Disposition: attachment; filename="' . $encoded_filename . '"');
            } else if (preg_match("/Firefox/", $ua)) {
                header("Content-Disposition: attachment; filename*=\"utf8''" . $result[0][0]['file_name'] . '"');
            } else {
                header('Content-Disposition: attachment; filename="' . $result[0][0]['file_name'] . '"');
            }
            readfile($local_file);
            
        } else {
             $this->FtpServerLog->insert_log("GET {$server_file}", "Fail");
            echo "There was a problem\n";
        }

        // close the connection
        ftp_close($conn_id);
    }
    
    
    public function ftp_conf() {
        $this->pageTitle = "Configuration/FTP Configuration";
        $this->paginate = array(
            'limit' => 100,
            'order' => array(
                'id' => 'desc',
            ),
        );
        $this->data = $this->paginate('FtpConf');
    }
    
    public function ftp_server_log()
    {
        if(isset($_GET['start']))
        {
            $start_time = $_GET['start'];
            $timezone = $_GET['gmt'];
        }    
        else
        {
            $start_time = date("Y-m-d 00:00:00");
            $timezone = "+00";
        }
        
        if(isset($_GET['end']))
        {
            $end_time = $_GET['end'];
        }    
        else
        {
            $end_time = date("Y-m-d 23:59:59");
        }
            
        $this->pageTitle = "Configuration/FTP Server Log";
        $this->paginate = array(
            'limit' => 100,
            'order' => array(
                'time' => 'desc',
            ),
            'conditions' => array(
                "time between '$start_time {$timezone}' and '$end_time $timezone'",
            )
        );
        $this->data = $this->paginate('FtpServerLog');
        $this->set('start_time', $start_time);
        $this->set('end_time', $end_time);
    }
    
    public function ftp_log_detail_message($id)
    {
        $this->autoLayout = false;
        $detail = $this->FtpCdrLogDetail->findById($id);
        $message = $detail['FtpCdrLogDetail']['detail'];
        $message = str_replace("\n", "<br />", $message);
        $this->set('message', $message);
    }

}