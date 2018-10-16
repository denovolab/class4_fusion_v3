<?php

class PrInvoicesController extends PrAppController {

    var $name = 'PrInvoices';
    var $helpers = array('Pr.AppPrInvoices');
    var $uses = array("pr.Invoice", 'pr.InvoiceLog');
    var $components = array('RequestHandler');

    public function add_invocie_item($invoice_id) {
        if (!$_SESSION['role_menu']['Finance']['pr_invoices']['model_w']) {
            $this->redirect_denied();
        }
        $flag = true;
        if (isset($_POST['positions'])) {
            foreach ($_POST['positions'] as $key => $value) {
                $name = isset($value['name']) ? $value['name'] : '';
                $price = isset($value['price']) ? $value['price'] : '0';
                if (empty($name) || empty($price)) {
                    continue;
                } else {
                    $sql = "insert into invoice_item(invoice_id,item,price)values($invoice_id,'$name',$price);";
                    $r = $this->Invoice->query($sql);
                    //插入异常
                    if (!is_array($r)) {
                        $flag = false;
                        break;
                    }
                }
            }
        }
        return $flag;
    }
    
   public function mail_invoice()
   {
       Configure::write('debug', 0);
       $this->autoLayout = false;
       $this->autoRender = false;
       
       $ids = $_POST['ids'];
       
       foreach ($ids as $id) {
           $this->change_type($id, 9, NULL);
       }
       
       echo json_encode(array('status' => 1));
   }
  
   public function apply_payment($invoice_id, $create_type)
   {
       $this->autoLayout = false;
       $invoice = $this->Invoice->findByInvoiceId($invoice_id);
       
       if ($this->RequestHandler->isPost()) {
           $payment_ids = $_POST['payment_ids'];
           $should_pay_amount = $invoice['Invoice']['total_amount'] - $invoice['Invoice']['pay_amount'];
           if (!$invoice['Invoice']['paid']) {
               foreach ($payment_ids as $payment_id) {
                   $payment = $this->Invoice->get_client_payment($payment_id);
                   $remain_amount = $payment['amount'] - $payment['used_amount'];
                   if ($remain_amount > 0) {
                       if ($remain_amount >= $should_pay_amount) {                                                   
                           $invoice['Invoice']['pay_amount'] += $should_pay_amount;
                           $invoice['Invoice']['paid']  = true;
                           $this->Invoice->save($invoice);
                           $remain_amount -= $should_pay_amount;
                           if ($payment['remain_id']) {
                               if ($remain_amount > 0)
                                   $this->Invoice->update_payment_invoice($payment['remain_id'], $remain_amount);        
                               else
                                   $this->Invoice->delete_payment_invoice($payment['remain_id']);
                           } else {
                               if ($remain_amount > 0)
                                   $this->Invoice->insert_remain_payment_invoice($payment_id, $remain_amount);
                           }
                           $this->Invoice->insert_payment_invoice($payment_id, $invoice_id, $should_pay_amount);
                           break;
                           
                       } else {
                           $invoice['Invoice']['pay_amount'] += $remain_amount;
                           if ($payment['remain_id']) {
                               $this->Invoice->delete_payment_invoice($payment['remain_id']);
                           }
                           $this->Invoice->insert_payment_invoice($payment_id, $invoice_id, $remain_amount);
                           $this->Invoice->save($invoice);
                       }
                   }
               }
           }
           $this->Invoice->create_json_array('', 201, 'The payments you selected is apply successfully!');
           $this->Session->write("m", Invoice::set_validator());
           if ($create_type == 'incoming')
           {
               $this->redirect('/pr/pr_invoices/incoming_invoice');
           }
           else
           {
               $this->redirect('/pr/pr_invoices/view/' . $create_type);
           }
       }
       $payments = $this->Invoice->get_client_payments($invoice['Invoice']['client_id'], $create_type);
       $this->set('payments', $payments);
       $this->set('invoice_id', $invoice_id);
       $this->set('create_type', $create_type);
   }
    
    public function invoice_log()
    {
        $this->pageTitle = "Finance/Invoice Log";
	$type = $this->Session->read('login_type');
        if ($type == 3)
        {
            $this->xredirect('/clients/carrier/');
        }
        $this->paginate = array(
            'limit' => 100,
            'order' => array(
                'InvoiceLog.id' => 'desc',
            )
        );
        
        $this->data = $this->paginate('InvoiceLog');

        foreach ($this->data as &$item) {
            $item['InvoiceLog']['invoices'] = $this->InvoiceLog->get_invoices($item['InvoiceLog']['id']);
        }
        
        
        $this->set('status', array(
            'In Progress',
            'In Progress',
            'Done',
            'Error',
        ));
        
        $this->set('sub_status', array(
            '-1' => 'Only Support Buy/Sell',
            0 => 'In Progress',
            1 => 'Zero CDR',
            2 => 'Done',
        ));
        
    }

    
    function cdr_download($type='ingress', $invoice_number) {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        
        if ($type == 'ingress') {
            $field = 'ingress_cdr_file';
        } elseif ($type == 'egress') {
            $field = 'egress_cdr_file';
        }
        
        $sql = "select $field from invoice where invoice_number = '{$invoice_number}'";
        $result = $this->Invoice->query($sql);
        Configure::load('myconf');

        $cdr_path = Configure::read('send_invoice.cdr_path');
        
        
        $file = $cdr_path . DS . $result[0][0][$field];
 
        $filename = basename($file);

        header("Content-type: application/octet-stream");

        //处理中文文件名
        $ua = $_SERVER["HTTP_USER_AGENT"];
        $encoded_filename = rawurlencode($filename);
        if (preg_match("/MSIE/", $ua)) {
            header('Content-Disposition: attachment; filename="' . $encoded_filename . '"');
        } else if (preg_match("/Firefox/", $ua)) {
            header("Content-Disposition: attachment; filename*=\"utf8''" . $filename . '"');
        } else {
            header('Content-Disposition: attachment; filename="' . $filename . '"');
        }

        //让Xsendfile发送文件
        header("X-Sendfile: $file");
        
    }
    
    function download_cdr() {
        if (!$_SESSION['role_menu']['Finance']['pr_invoices']['model_x']) {
            $this->redirect_denied();
        }
        Configure::write('debug', 2);
        if (!empty($this->params['pass'][0])) {
            /* $this->Invoice->invoice_id = $this->params ['pass'] [0];
              $data=$this->Invoice->read(); */
            $data = $this->Invoice->find('first', array('conditions' => array('invoice_id' => intval($this->params['pass'][0]))));
            $start = $data['Invoice']['invoice_start'];
            $end = $data['Invoice']['invoice_end'];
            $client_id = $data['Invoice']['client_id'];
            if (!empty($start) && !empty($end)) {
                $client_data = $this->Invoice->query("select * from client where client_id = {$client_id}");
                //$sql="select *  from  client_cdr where time  between   '$start'  and  '$end' ";
                $sql = "SELECT * FROM client_cdr WHERE ingress_client_id={$client_id} AND time >= '{$start}' AND time <= '{$end}'";

                $this->layout = 'csv';
                $compress_format = empty($client_data[0][0]['cdr_list_format']) ? '2' : $client_data[0][0]['cdr_list_format'];
                
                switch ($compress_format) {
                    case '3':
                        $this->Invoice->export__sql_compress('download Cdr', $sql, 'cdr.zip', 'zip');
                        break;
                    case '4':
                        $this->Invoice->export__sql_compress('download Cdr', $sql, 'cdr.tar.gz', 'tar.gz');
                        break;
                    case '2':
                    case "1":
                    default:
                        $this->Invoice->export__sql_data('download Cdr', $sql, 'cdr');
                }
                $this->layout = 'csv';
                exit();
            }
        } else {
            $this->redirect('/pr/pr_invoices/view/');
        }
    }

    public function download_rate($invoice_id=null) {
        if (!$_SESSION['role_menu']['Finance']['pr_invoices']['model_x']) {
            $this->redirect_denied();
        }
        Configure::write('debug', 0);
        $id_where = '';
        if (isset($_POST['ids'])) {
            $id_str = '';
            foreach ($_POST['ids'] as $key => $value) {
                $id_str.="$value,";
            }
            $id_str = substr($id_str, 0, -1);
            $id_where = "where invoice_id in ($id_str)";
        }
        $download_sql = "select *,(select name from client where client_id = invoice.client_id) as client from invoice  $id_where";
        $this->Invoice->export__sql_data('Download Invoice', $download_sql, 'invoice');
        $this->layout = 'csv';
        exit();
    }

    /**
     * 修改invocie的状态
     * 
     * 
     */
    function mass_update() {
        if (!$_SESSION['role_menu']['Finance']['pr_invoices']['model_w']) {
            $this->redirect_denied();
        }
        if (!isset($_POST['ids'])) {
            $this->redirect("/pr/pr_invoices/view/{$this->params['pass'][0]}");
        }
        $ids = $_POST['ids'];
        $id_str = '';
        foreach ($_POST['ids'] as $key => $value) {
            $id_str.="$value,";
        }
        $id_str = substr($id_str, 0, -1);
        $action = $_POST['action'];
        if ($action == '-1' or $action == '0' or $action == '1') {
            $this->Invoice->query("update  invoice set state=$action  where invoice_id  in($id_str)");
        }
        
        if ($action == '9') {
            foreach ($ids as $id) {
                $this->change_type($id, 9, NULL);
            }
        }
        
        if ($action == '00') {
            $this->Invoice->query("update  invoice set disputed=0  where invoice_id  in($id_str)");
        }
        if ($action == '11') {
            $this->Invoice->query("update  invoice set disputed=1  where invoice_id  in($id_str)");
        }
        
        if ($action == '8') {
            
            Configure::load('myconf');
            $invoice_path = Configure::read('generate_invoice.path');
            $invoice_name = $this->_get_invoice_name(); 
            
            $zip = new ZipArchive();
            $zip_path = APP . 'webroot' . DS . 'upload' . DS .'invoice';
            $zip_file = $zip_path . DS . uniqid() . ".zip";
            
            $invoice_file_name = $invoice_name ."_" . date("Y-m-d") . "_".".zip";
            
            if ($zip->open($zip_file, ZIPARCHIVE::CREATE)!==TRUE) {
                //exit("cannot open <$zip_file>\n");
            }
            
            foreach ($ids as $id) {
                $invoice = $this->Invoice->findByInvoiceId($id);
                $client_name = $this->Invoice->get_client_name($invoice['Invoice']['client_id']);
                $invoice_number = $invoice['Invoice']['invoice_number'];                
                $invoice_date = $this->_get_invoice_date($invoice_number);
                $invoice_file = $invoice_path . DS . $invoice_number . '_invoice.pdf';
                $filename = $invoice_name  . '_' . $client_name. '_' . $invoice_number . '_' . $invoice_date .'.pdf';
                if (!file_exists($invoice_file)) {
                    $pdf_contents = file_get_contents($this->getUrl() . "pr/pr_invoices/createpdf_invoice/".$invoice_number);
                    file_put_contents($invoice_file, $pdf_contents);
                } 
                
                $zip->addFile($invoice_file, $filename);
            }
            
            $zip->close();
            ob_clean();
            
            
            if (file_exists($zip_file))
            {
                header("Content-type: application/octet-stream");
                //处理中文文件名
                $ua = $_SERVER["HTTP_USER_AGENT"];
                $encoded_filename = rawurlencode($invoice_file_name);
                if (preg_match("/MSIE/", $ua)) {
                    header('Content-Disposition: attachment; filename="' . $encoded_filename . '"');
                } else if (preg_match("/Firefox/", $ua)) {
                    header("Content-Disposition: attachment; filename*=\"utf8''" . $invoice_file_name . '"');
                } else {
                    header('Content-Disposition: attachment; filename="' . $invoice_file_name . '"');
                }
                readfile($zip_file);
                exit;
                
            } else {
                echo "File does not exist!";
            }
            
            
        }
        
        $this->redirect("/pr/pr_invoices/view/{$this->params['pass'][0]}");
    }

    //初始化查询参数
    function init_query() {

        $this->set('currency', $this->Invoice->find_currency());
    }

  
    public function _get_invoice_name() {
        $sql = "SELECT invoice_name FROM system_parameter LIMIT 1";
        $data = $this->Invoice->query($sql);
        return $data[0][0]['invoice_name'];
    }
    
    public function _get_invoice_date($invoice_number) {
        $sql = "SELECT invoice_time FROM invoice WHERE invoice_number = '{$invoice_number}'";
        $data = $this->Invoice->query($sql);
        return $data[0][0]['invoice_time'];
    }
    
    
    public function createpdf_invoice($invoice_number) {
        Configure::load('myconf');
        $invoice_path = Configure::read('generate_invoice.path');
        
        $invoice_name = $this->_get_invoice_name();
        $invoice_date = $this->_get_invoice_date($invoice_number);
        
        $invoice_file = $invoice_path . DS . $invoice_number . '_invoice.pdf';
        
        $filename = $invoice_name . '_' . $invoice_number . '_' . $invoice_date .'.pdf';
        $this->autoRender = false;
        $this->autoLayout = false;
        @unlink($invoice_file);
        if(file_exists($invoice_file)) {
            $data = file_get_contents($invoice_file);
            header("Content-Description: File Transfer");
            header("Cache-Control: public; must-revalidate, max-age=0");
            header("Pragme: public");
            header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
            header("Last-Modified: " . gmdate('D, d m Y H:i:s') . " GMT");
            //header("Content-Type: application/force-download");
            //header("Content-Type: application/octec-stream", false);
            //header("Content-Type: application/download", false);
            header("Content-Type: application/pdf", false);
            header('Content-Disposition: attachment; filename="' . basename($filename) .'";');
            //header("Content-Transfer-Encoding: binary");
            header("Content-Length: " . strlen($data));
            echo $data;
        } else {
            App::import("Vendor", "other", array('file'=>'wkhtmltopdf.php'));
            Configure::write('debug', 0);
            App::import("Model", 'pr.Invoice');
            $invoice_model = new Invoice;
            $num_format = empty($this->params['pass'][1]) ? 5 : intval($this->params['pass'][1]);
            $url = $this->getUrl();
            $html = $invoice_model->generate_pdf_content($invoice_number, $url,$num_format);
            /*
            try {
                $this->Invoice->query("update invoice set pdf_path = '{$invoice_file}' where invoice_number = '{$invoice_number}'");
                $wkhtmltopdf = new Wkhtmltopdf(array('path' => WWW_ROOT . 'upload/html/', 'binpath'=>APP.'binexec'.DS.'wkhtmltopdf'.DS.'wkhtmltopdf-amd64'));
                $wkhtmltopdf->setTitle('invoice_'.$invoice_number);
                $wkhtmltopdf->setHtml($html);
                $wkhtmltopdf->output(Wkhtmltopdf::MODE_DOWNLOAD, $filename, $invoice_file);
            } catch (Exception $e) {
                echo $e->getMessage();
            }
            */
            $binexe = APP.'binexec'.DS.'wkhtmltopdf'.DS.'wkhtmltopdf-amd64';
            $randomhtml =  WWW_ROOT . 'upload' . DS . 'html' . DS  . uniqid() . '.html';
            file_put_contents($randomhtml, $html);
            $cmd =  "$binexe -s Letter $randomhtml $invoice_file";
            $blah = shell_exec($cmd);
            $data = file_get_contents($invoice_file);
            header('Content-Type: application/pdf');
            header('Content-Length: '.strlen($data));
            header('Content-Disposition: inline; filename="'.$filename.'"');
            header('Cache-Control: private, max-age=0, must-revalidate');
            header('Pragma: public');
            ini_set('zlib.output_compression','0');
            die($data);
        }
    }

    public function send_pdf($invoice_number) {
        if (!$_SESSION['role_menu']['Finance']['pr_invoices']['model_x']) {
            $this->redirect_denied();
        }
        Configure::write('debug', 0);
        $this->autoRender = FALSE;
        $this->autoLayout = FALSE;
        App::import("Model", 'pr.Invoice');
        $invoice_model = new Invoice;
        $num_format = empty($this->params['pass'][1]) ? 5 : intval($this->params['pass'][1]);
        $url = $this->getUrl();
        $html = $invoice_model->generate_pdf_content($invoice_number, $url,$num_format);
        file_put_contents('/tmp/invoice.pdf', $html);
    }

//生成xls	
    public function createxls_invoice($invoice_number) {
        if (!$_SESSION['role_menu']['Finance']['pr_invoices']['model_x']) {
            $this->redirect_denied();
        }
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        App::import("Model", 'pr.Invoice');
        $invoice_model = new Invoice;
        
        $invoice_name = $this->_get_invoice_name();
        $invoice_date = $this->_get_invoice_date($invoice_number);
        $filename = $invoice_name . '_' . $invoice_number . '_' . $invoice_date .'.doc';
        $url = $this->getUrl();
        $num_format = empty($this->params['pass'][1]) ? 5 : intval($this->params['pass'][1]);
        $html = $invoice_model->generate_pdf_content($invoice_number, $url,$num_format);
        
        $html =<<<EOT
   <html xmlns:v="urn:schemas-microsoft-com:vml"  
xmlns:o="urn:schemas-microsoft-com:office:office"  
xmlns:w="urn:schemas-microsoft-com:office:word"  
xmlns:m="http://schemas.microsoft.com/office/2004/12/omml"  
xmlns="http://www.w3.org/TR/REC-html40">  
    {$html}
</html>
EOT;
        header("Content-type: application/octet-stream");
        header("Accept-Ranges: bytes");
        header('Content-type: application/doc; charset=UTF-8');
        header("Content-Disposition: inline; filename=\"" . $filename . "\"");
        die($html);
    }
    
    public function getUrl()
    {
        if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] =="on")  
        {
            
            $url = 'https://'.$_SERVER['SERVER_NAME'].':'. $_SERVER["SERVER_PORT"]. $this->webroot;
        }
        else
        {
            $url = 'http://'.$_SERVER['SERVER_NAME'].':'. $_SERVER["SERVER_PORT"]. $this->webroot;
        }
        return $url;
    }

//生成html	
    public function createhtml_invoice($invoice_number) {
        App::import("Model", 'pr.Invoice');
        $invoice_model = new Invoice;
        $num_format = empty($this->params['pass'][1]) ? 5 : intval($this->params['pass'][1]);
        $url = $this->getUrl();
        $html = $invoice_model->generate_pdf_content($invoice_number, $url,$num_format);
        Configure::write('debug', 0);
        $this->autoRender = false;
        //App::import("Vendor","tcpdf",array('file'=>"tcpdf/pdf.php"));
        //$invoice_pdf = create_PDF("invoice",$html);	
        //return $html;
        echo $html;
    }

//读取该模块的执行和修改权限
    public function beforeFilter() {
        if( $this->params['action'] == 'do_reconcile' || $this->params['action'] == 'createpdf_invoice' || $this->params['action'] == 'cdr_download'|| 'createxls_invoice' == $this->params['action'])
            return true;
        $this->checkSession("login_type"); //核查用户身份
        $login_type = $this->Session->read('login_type');
        if ($login_type == 1) {
            //admin
            $this->Session->write('executable', true);
            $this->Session->write('writable', true);
        } else {
            $limit = $this->Session->read('sst_account_invoice');
            $this->Session->write('executable', $limit['executable']);
            $this->Session->write('writable', $limit['writable']);
        }
      	parent::beforeFilter();
        
    }
    
    public function show_carriers($name = '') {
        $this->autoLayout = false;
        if (!empty($name)) {
            $condition = "where name like '%{$name}%'";
        } else {
            $condition = '';
        }
        $sql = "select client_id, name, status from client $condition order by name";
        $result = $this->Invoice->query($sql);
        $this->set("clients", $result);
        $this->set('name', $name);
    }

    public function validate() {
        $error_flag = 'false';
        $invoice_number = '';
        $state = $_POST ['state'];
        $type = $_POST ['type'];
        $due_date = $_POST ['due_date'];
        //$total_amount = $_POST ['total_amount'];
        $start_date = $_POST ['start_date']; //开始日期
        $stop_date = $_POST ['stop_date']; //结束日期
        $gmt = $_POST["query"]['tz'];
        $carriers = $_POST['carriers'];


        #  check invoice number  Repeatability
        if (!empty($invoice_number)) {
            // Never here
            $c = $this->Invoice->query("select  count(*)  from invoice  where  invoice_number='$invoice_number';");
            if ($c[0][0]['count'] > 0) {
                $this->Invoice->create_json_array('#invoice_number', 101, 'invoice Number Repeatability');
                $error_flag = true;
            }
        } else {
            //check invoice date duplicate
            $system_settings = $this->Invoice->query("select overlap_invoice_protection from system_parameter limit 1");
            if ($system_settings[0][0]['overlap_invoice_protection']) {
                $type_where = $type == 2 ? " and (\"type\" = 0 or \"type\" = 1)" : (" and \"type\" = " . intval($type) );
                $dupli_sql = "select *  from invoice where state != -1 and client_id in ($carriers) and ( (invoice_end >= TIMESTAMP '{$start_date}' AT TIME ZONE (substring('{$gmt}' for 3)||':00')::INTERVAL and invoice_start <= TIMESTAMP '{$start_date}' AT TIME ZONE (substring('{$gmt}' for 3)||':00')::INTERVAL) or (invoice_end >= TIMESTAMP '{$stop_date}' AT TIME ZONE (substring('{$gmt}' for 3)||':00')::INTERVAL and invoice_start <= TIMESTAMP '{$stop_date}' AT TIME ZONE (substring('{$gmt}' for 3)||':00')::INTERVAL) ) {$type_where}";
                $dupli_result = $this->Invoice->query($dupli_sql);
                if (!empty($dupli_result)) {
                    $message = "The invoice you are trying to generate is overlapping with the following invoice(s), and will not be executed:";
                   
                    $this->Invoice->create_json_array('#query-start_date-wDt', 101, $message);
                    foreach($dupli_result as $dupli_item)
                    {
                         $message2 = "Invoice:#{$dupli_item[0]['invoice_number']} [{$dupli_item[0]['invoice_start']} ~ {$dupli_item[0]['invoice_end']}]";
                        $this->Invoice->create_json_array('#query-start_date-wDt', 101, $message2);
                    }
                    $error_flag = true;
                }
            }
        }

        if (empty($carriers)) {
            $this->Invoice->create_json_array('#query-id_clients_name', 101, __('clientnamenull', true));
            $error_flag = true;
        }

        if (empty($due_date)) {
            $this->Invoice->create_json_array('#due_date', 101, 'Invoice Date/Due (days) is  Empty!');
            $error_flag = true;
        }

        /* 				if(empty($total_amount)){
          $this->Invoice->create_json_array ( '#total_amount', 101, 'Total is  null');
          $error_flag = true;
          } */
        return $error_flag;
    }

    public function add($type) {
        if (!$_SESSION['role_menu']['Finance']['pr_invoices']['model_w']) {
            $this->redirect_denied();
        }
        $this->set('type', $type);
        //$clients = $this->Invoice->query("select client_id, name from client order by name asc");
        //$this->set('clients', $clients);
        //$arrCarriers = $this->Invoice->query("select name from client order by name asc"); //获取运营商name列表
        //$this->set('arrCarriers', $arrCarriers);
        
        
        if (!empty($_POST)) {
            $error_flag = $this->validate();
            if ($error_flag != '1') {
                $carriers_arr = explode(',', $_POST['carriers']);
                $carriers_arr = array_unique($carriers_arr);
                array_walk($carriers_arr, create_function('&$item, $key', '$item = "-i {$item}";'));
                $carrier_cmd = implode(" ", $carriers_arr);
                
                // TODO Log 
                $sql = "insert into invoice_log(start_time) values (CURRENT_TIMESTAMP(0)) returning id";
                $log_result = $this->Invoice->query($sql);
                $log_id = $log_result[0][0]['id'];
                
                //$result = $this->Invoice->query("select client_id from client where name = '{$_POST ['query'] ['id_clients']}'");
                //$this->data['Invoice']['client_id'] = $result[0][0]['client_id'];
                //			$seq_list=$this->Invoice->query("select    nextval('class4_seq_invoice_no'::regclass)");
                //		$seq=$seq_list[0][0]['nextval'];
                $this->data['Invoice']['invoice_time'] = !empty($_POST ['invoice_time']) ? $_POST['invoice_time'] : '';
                $this->data['Invoice']['invoice_number'] = !empty($_POST ['invoice_number']) ? $_POST['invoice_number'] : '';
                //		$this->data['Invoice']['invoice_number'] = !empty($_POST ['invoice_number'])?$_POST['invoice_number']:$seq.substr(time(),0,6);
                $this->data['Invoice']['state'] = intval($_POST ['state']);
                $this->data['Invoice']['type'] = intval($_POST ['type']);
                $this->data['Invoice']['create_type'] = 1;
                $this->data['Invoice']['due_date'] = $_POST ['due_date'];
                $this->data['Invoice']['invoice_zone'] = $_POST['query']['tz'];
                $start_date = $_POST ['start_date']; //开始日期
                $start_time = $_POST ['start_time']; //开始时间
                $stop_date = $_POST ['stop_date']; //结束日期
                $stop_time = $_POST ['stop_time']; //结束时间
                $tz = $_POST ['query']['tz']; //结束时间
                $this->data['Invoice']['invoice_start'] = $start_date . ' ' . $start_time . ' ' . $tz; //开始时间
                $this->data['Invoice']['invoice_end'] = $stop_date . ' ' . $stop_time . ' ' . $tz; //结束时间
                $list = $this->Invoice->query("select  balance   from  client_balance  where client_id='{$_POST ['query'] ['id_clients']}'");
                $this->data['Invoice']['current_balance'] = !empty($list[0][0]['balance']) ? $list[0][0]['balance'] : '0.000';
                $this->data['Invoice']['output_type'] = $_POST['output_type'];
                $this->data['Invoice']['include_detail'] = isset($_POST['include_detail']) ? 1 : 0;
                $this->data['Invoice']['invoice_jurisdictional_detail'] = isset($_POST['jur_detail']) ? 1 : 0;
                $this->data['Invoice']['decimal_place'] = $_POST['decimal_place'];
                $this->data['Invoice']['rate_value'] = $_POST['rate_value'];
                $this->data['Invoice']['is_invoice_account_summary'] = isset($_POST['is_invoice_account_summary']) ? 1 : 0;
                $this->data['Invoice']['is_show_daily_usage'] = isset($_POST['is_show_daily_usage']) ? 1 : 0;
                $this->data['Invoice']['is_show_short_duration_usage'] = isset($_POST['is_show_short_duration_usage']) ? 1 : 0;
                $this->data['Invoice']['invoice_include_payment'] = isset($_POST['is_show_payments']) ? 1 : 0;
                $this->data['Invoice']['usage_detail_fields'] = isset($_POST['usage_detail_fields']) ? implode(',', $_POST['usage_detail_fields']) : '';
                $this->data['Invoice']['invoice_use_balance_type'] = $_POST['invoice_use_balance_type'];
                $name = $_SESSION['sst_user_name'];
                Configure::load('myconf');
                $script_path =  Configure::read('script.path');
                $exec_path = $script_path . DS . "class4_invoice.pl";
                $exec_conf_path = Configure::read('script.conf');
                $url = $this->getUrl() . "pr/pr_invoices/createpdf_invoice";
                $cmd = "perl {$exec_path} -n '{$this->data['Invoice']['invoice_number']}' -c {$exec_conf_path} -s '{$this->data['Invoice']['invoice_start']}' -e '{$this->data['Invoice']['invoice_end']}' {$carrier_cmd} -y {$this->data['Invoice']['type']} -z '{$this->data['Invoice']['invoice_zone']}' -t '{$this->data['Invoice']['invoice_time']}' -d '{$this->data['Invoice']['due_date']}' -u '{$url}' -o {$this->data['Invoice']['output_type']} -l {$this->data['Invoice']['include_detail']} -j {$this->data['Invoice']['invoice_jurisdictional_detail']} -p {$this->data['Invoice']['decimal_place']} -r {$this->data['Invoice']['rate_value']} -f {$this->data['Invoice']['is_invoice_account_summary']} -v {$this->data['Invoice']['is_show_daily_usage']} -k {$this->data['Invoice']['is_show_short_duration_usage']} -g {$this->data['Invoice']['invoice_include_payment']} -w '{$this->data['Invoice']['usage_detail_fields']}' -q '{$name}' -b 1 --log {$log_id} --btype {$this->data['Invoice']['invoice_use_balance_type']} > /dev/null &";
                if (Configure::read('cmd.debug')) {
                    echo $cmd;exit;
                }
                $result = shell_exec($cmd);
                //$shell = Configure::read('php_exe_path')." ".APP."alert_invoice_email.php  &";
                //shell_exec($shell);
                //$this->Invoice->logging(0, 'Invoice', "Invoice Number:0000");
                $this->Invoice->create_json_array('#credit', 201, 'Successfully!');
                $this->Session->write("m", Invoice::set_validator());
                $this->redirect("/pr/pr_invoices/view/{$type}");
            } else {
               // $this->Invoice->create_json_array('#ClientOrigRateTableId', 101, 'Failed!');
                $this->set('m', Invoice::set_validator()); //向界面设置验证信息
                $this->set('post', $this->data);
                return;
            }
            //pr($r);exit;
            $result_arr = explode("\n", $result);
            $result_line = explode(":", $result_arr[1]);
            $invoice_number = trim($result_line[1]);
            if (!empty($invoice_number)) {
                if ('xls' == $_POST['output']) {
                    $this->createxls_invoice($invoice_number);
                } elseif ('html' == $_POST['output']) {
                    $this->createhtml_invoice($invoice_number);
                    if (!empty($invoice_number)) {
                        $this->createhtml_invoice($r0[0][0]['create_client_invoice']);
                    }
                } else {
                    $ch = curl_init();
                    $fp = fsockopen($_SERVER['SERVER_NAME'], $_SERVER['SERVER_PORT'], $errno, $errstr, 30);
                    if (!$fp) {
                        echo "$errstr ($errno)<br />\n";
                    } else {
                        $out = "GET {$this->webroot}pr/pr_invoices/createpdf_invoice/{$invoice_number} HTTP/1.1\r\n";
                        $out .= "Host: localhost\r\n";
                        $out .= "Connection: Close\r\n\r\n";

                        fwrite($fp, $out);
                        /*
                        忽略执行结果
                        while (!feof($fp)) {
                            echo fgets($fp, 128);
                        }
                        */
                        fclose($fp);
                    }
                    /*
                    echo '111';exit;
                    $pdf_name = $this->createpdf_invoice($invoice_number); //生成invoice
                    pg_query("update invoice set pdf_path='{$pdf_name}'");
                    if (!empty($r0[0][0]['create_client_invoice'])) {
                        $pdf_name0 = $this->createpdf_invoice($invoice_number); //生成invoice
                        pg_query("update invoice set pdf_path='{$pdf_name0}'");
                    }
                     * 
                     */
                    $this->redirect("/pr/pr_invoices/view/{$type}");
                }
            } else {
                $this->Invoice->create_json_array('#ClientOrigRateTableId', 201, 'Create fail!No CDR in this peroid.');
                $this->set('m', Invoice::set_validator()); //向界面设置验证信息
                $this->set('post', $this->data);
                return;
            }
        } else {
            $this->init_query();
        }

        
    }

    public function edit() {
        //	Configure::write('debug',0);
        if (!$_SESSION['role_menu']['Finance']['pr_invoices']['model_w']) {
            $this->redirect_denied();
        }
        if (!empty($_POST)) {
            $invoice_id = $_POST ['invoice_id'];


//			 $state = $_POST ['state'];
//			 $credit=$_POST['credit'];
            if (empty($invoice_id)) {
                $this->Invoice->create_json_array('#credit', 101, 'invoice is corrupted or does not exists.');
                $this->Session->write("m", Invoice::set_validator());
                $this->redirect("/pr/pr_invoices/edit/$invoice_id");
            }
//				$invoice_info = $this->invoice_info($invoice_id);
//				var_dump($invoice_info);exit;
//				if(!preg_match('/^[+\-]?\d+(.\d+)?$/',$credit)){
//					$this->Invoice->create_json_array ( '#credit', 101, 'Invoice has zero credit, no sense in creation.');
//					 $this->Session->write("m",Invoice::set_validator ());
//		     $this->redirect("/pr/pr_invoices/edit/$invoice_id");			
//				}
//				else
//				{
//					$this->Invoice->create_json_array ( '#credit', 101, 'Invoice credit must less than invoice amount .');
//					$this->Session->write("m",Invoice::set_validator ());
//		     $this->redirect("/pr/pr_invoices/edit/$invoice_id");
//				}
//			 $r=$this->Invoice->query("update invoice set state=$state ,credit_amt= $credit  where   invoice_id=$invoice_id");
            if (0) {//(!is_array($r)) {
                $this->Invoice->create_json_array('#ClientOrigRateTableId', 101, 'Database  Error');
                $this->Session->write("m", Invoice::set_validator());
                // $this->redirect("/invoices/edit/$invoice_id");			
            } else {
                $file_arr = $this->_move_upload_file('Invoice');
                //				if (!empty($file_arr[0]))
                //				{
                //					$this->Invoice->query("update invoice set pdf_path='{$file_arr[0]}'  where  invoice_id=$invoice_id");
                //				}
                if (!empty($file_arr)) {
                    $this->Invoice->query("update invoice set cdr_path='{$file_arr}'  where  invoice_id=$invoice_id");
                }
                $this->Invoice->create_json_array('#ClientOrigRateTableId', 201, 'Edit success');
                $this->Session->write("m", Invoice::set_validator());
                $this->redirect('/pr/pr_invoices/view');
            }
        } else {

            $this->init_query();
            $invoice_id = $this->params['pass'][0];
            $list = $this->Invoice->query("SELECT invoice_id,invoice_number,state,type,invoice.client_id,invoice.invoice_time,invoice.invoice_start,
			                            invoice.invoice_end, invoice.send_time, invoice.total_amount::numeric(20,2)  as amount1,   invoice.paid,invoice.due_date,
                                 invoice.pay_amount::numeric(20,2),invoice.credit_amount::numeric(20,2),current_balance::numeric(20,2),client.name as client_name,invoice.cdr_path   from  invoice 
			                             left join client on client.client_id=invoice.client_id where invoice_id=$invoice_id   ");

            if (count($list) == 0) {

                $this->Invoice->create_json_array('#ClientOrigRateTableId', 101, ' invoice is corrupted or does not exists.');
                $this->Session->write("m", Invoice::set_validator());
                $this->redirect('/invoices/view');
            }
            $this->set('list', $list);
        }
    }

    function index() {
        $this->redirect('view');
    }

    public function view() {
        $this->pageTitle = "Finance/Invoices";
        $type = '0';
        if (isset($this->params['pass'][0])) {
            $type = $this->params['pass'][0];
        }
        $results = $this->Invoice->getInvoices($type, $this->_order_condtions(array('invoice_number', 'paid', 'type', 'client', 'invoice_start', 'total_amount', 'invoice_time')));
        
        $this->set('p', $results);
        $this->set('create_type', $type);
        $this->set('url_get', $this->params['url']);
        $this->set('status', array(
            '0' => 'In Progress',
            '1' => 'Zero CDR',
            '2' => 'Done',
            '-1' => 'Only Support buy/sell',
        ));
        $clients = $this->Invoice->query("select client_id, name from client order by name asc");
        $this->set('clients', $clients);
    }

    public function del($id) {
        $this->Invoice->query("delete from  invoice_calls  where invoice_no  in (select invoice_number  from  invoice where  invoice_id=$id)");
        if ($this->Invoice->del($id)) {

            $this->Invoice->create_json_array('', 201, __('del_suc', true));
        } else {
            $this->Invoice->create_json_array('', 101, __('del_fail', true));
        }
        $this->Session->write('m', Invoice::set_validator());
        $this->redirect('/invoices/view');
    }
    
    public function delete_invoice($invoice_id, $type) {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $sql1 =<<<EOT
SELECT 
client_id,
invoice_number,
(SELECT COALESCE(sum(amount), 0) FROM client_payment WHERE invoice_number = invoice.invoice_number AND payment_type in (7, 8)) as credit, 
(SELECT COALESCE(sum(amount), 0) FROM client_payment WHERE invoice_number = invoice.invoice_number AND payment_type in (11, 12)) as debit
FROM 
invoice 
WHERE invoice_id = {$invoice_id};
EOT;
        $invoice_type = $this->Invoice->query($sql1);
        $credit = $invoice_type[0][0]['credit'];
        $debit = $invoice_type[0][0]['debit'];
        $client_id = $invoice_type[0][0]['client_id'];
        $invoice_number = $invoice_type[0][0]['invoice_number'];
        $sql_s = "SELECT amount FROM client_payment where invoice_number = '{$invoice_number}'";
        $result_s = $this->Invoice->query($sql_s);
        if ($type == '0' || $type == '1')
        {
            if (!empty($result_s))
                $s_amount = "+{$result_s[0][0]['amount']}";
            else
                $s_amount = "";
            $sql2 = "UPDATE client_balance SET ingress_balance=ingress_balance::real-{$credit}+{$debit}{$s_amount}, balance=balance::real-{$credit}+{$debit}{$s_amount} WHERE client_id = '{$client_id}'";
            $this->Invoice->query($sql2);
        }
        $sql3 = "UPDATE payment_invoice set invoice_id = NULL WHERE invoice_id = '{$invoice_id}'";
        $this->Invoice->query($sql3);
        $this->Invoice->query("DELETE  FROM invoice WHERE invoice_id = {$invoice_id}");
        $this->Invoice->logging(1, 'Invoice', "Invoice Number:{$invoice_number}");
        $this->Invoice->create_json_array('', 201, __('The Invoice [' . $invoice_number . '] is deleted successfully!', true));
        $this->Session->write('m', Invoice::set_validator());
        $this->redirect('/pr/pr_invoices/view/' . $type);
    }
    
    public function get_invoice_payments($invoice_id)
    {
        Configure::write('debug', 0);
        $this->autoLayout = false;   
        
        $invoice = $this->Invoice->findByInvoiceId($invoice_id);
        //print_r($invoice);
        $total_amount = $invoice['Invoice']['total_amount'];
        
        $payments = $this->Invoice->get_invoice_payments($invoice_id);
        
        $this->set('total_amount', $total_amount);
        $this->set('payments', $payments);
    }
    
    public function delete_incoming($invoice_id) {
        $this->autoRender = false;
        $this->autoLayout = false;
        $sql1 =<<<EOT
SELECT 
client_id,
invoice_number,
(SELECT COALESCE(sum(amount), 0) FROM client_payment WHERE invoice_number = invoice.invoice_number AND payment_type in (7, 8)) as credit, 
(SELECT COALESCE(sum(amount), 0) FROM client_payment WHERE invoice_number = invoice.invoice_number AND payment_type in (11, 12)) as debit,
(SELECT COALESCE(sum(amount), 0) FROm client_payment WHERE invoice_number = invoice.invoice_number AND payment_type = 3) as pay_amount
FROM 
invoice 
WHERE invoice_id = {$invoice_id};
EOT;
        $invoice_type = $this->Invoice->query($sql1);
        $credit = $invoice_type[0][0]['credit'];
        $debit = $invoice_type[0][0]['debit'];
        $client_id = $invoice_type[0][0]['client_id'];
        $invoice_number = $invoice_type[0][0]['invoice_number'];
        $pay_amount = $invoice_type[0][0]['pay_amount'];
        $sql2 = "UPDATE client_balance SET egress_balance=egress_balance::real+{$credit}-{$debit}+{$pay_amount}, balance=balance::real+{$credit}-{$debit}+{$pay_amount} WHERE client_id = '{$client_id}'";
        $this->Invoice->query($sql2);
        $sql3 = "DELETE FROM client_payment WHERE invoice_number = '{$invoice_number}' AND payment_type in (3, 7, 8, 11, 12)";
        $this->Invoice->query($sql3);
        $this->Invoice->query("DELETE  FROM invoice WHERE invoice_id = {$invoice_id}");
        $this->Invoice->logging(1, 'Invoice', "Invoice Number:{$invoice_number}");
        $this->Invoice->create_json_array('', 201, __('Incoming Invoice [' . $invoice_number . '] is deleted successfully!', true));
        $this->Session->write('m', Invoice::set_validator());
        $this->redirect('/pr/pr_invoices/incoming_invoice');
    }

    function _move_upload_file($model) {
        if (!$_SESSION['role_menu']['Finance']['pr_invoices']['model_x']) {
            $this->redirect_denied();
        }
        $model_name = $model;
        App::import("Core", "Folder");
        $path = APP . 'tmp' . DS . 'upload' . DS . $model_name . DS . gmdate("Y-m-d", time());
        if (new Folder($path, true, 0777)) {
            //$file[0] = $path . DS . time() . ".pdf";
            $file = $path . DS . time() . ".csv";
            //move_uploaded_file($_FILES ["file"] ["tmp_name"], $file);
//			if (!move_uploaded_file($_FILES ["attach"] ["tmp_name"], $file[0]))
//			{
//				$file[0] = '';
//			}
            if (!move_uploaded_file($_FILES ["attach_cdr"] ["tmp_name"], $file)) {
                $file = '';
            }
            return $file;
        } else {
            throw new Exception("Create File Error,Please Contact Administrator.");
        }
    }

    function invoice_info($id=null) {
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        $conditions = Array();
        if (!empty($id)) {
            $conditions[] = "invoice.invoice_id='$id'";
        }
        $invoice_number = $this->_get('invoice_number');
        if ($invoice_number) {
            $conditions[] = "invoice.invoice_number='$invoice_number'";
        }
        $conditions = join($conditions, ' and ');
        if (!empty($conditions)) {
            $conditions = ' and ' . $conditions;
        }
        $sql = "select invoice.client_id, client.name  as client_name, invoice.send_time, invoice_number,invoice_start,invoice_end,pay_amount,current_balance,due_date,paid
from invoice left join client on invoice.client_id =client.client_id where 1=1 $conditions";
        $this->data = $this->Invoice->query($sql);
    }

    function inv_clent($inv_id=null) {
        if (!empty($inv_id)) {
            $sql_client = "select client_id as id from invoice where invoice_id=$inv_id	";
            $client_id = $this->Invoice->query($sql_client);
            $this->redirect('/clients/edit/' . $client_id[0][0]['id']);
        }
    }

    function regenerate() {
        if (!$_SESSION['role_menu']['Finance']['pr_invoices']['model_w']) {
            $this->redirect_denied();
        }
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        $invoice_id = $_REQUEST['invoice_id'];
        if (empty($invoice_id)) {
            echo 'Invoice Not Select.';
        } else {
            $this->data = $this->Invoice->findByInvoiceId($invoice_id);
            $this->Invoice->logging(2, 'Invoice', "Invoice Number:{$this->data['Invoice']['invoice_number']}");
            if (empty($this->data)) {
                echo 'The Invoice id not found!';
            } elseif ($this->data['Invoice']['state'] == -1) {    //re-generate
                $c = $this->Invoice->query("select count(*) as cnt from invoice where type={$invoice[0][0]['type']} and state != -1 and client_id = {$invoice[0][0]['client_id']} and ( (invoice_end >= '{$invoice[0][0]['invoice_start']}' and invoice_start <= '{$invoice[0][0]['invoice_start']}') or (invoice_end >= '{$invoice[0][0]['invoice_end']}' and invoice_start <= '{$invoice[0][0]['invoice_end']}') )");
                if ($c[0][0]['cnt'] == 0) {
                $this->Invoice->query("delete from client_payment where payment_type = 15 and invoice_number = '{$this->data['Invoice']['invoice_number']}'");
                Configure::load('myconf');
                $script_path =  Configure::read('script.path');
                $exec_path = $script_path . DS . "class4_invoice.pl";
                $exec_conf_path = Configure::read('script.conf');
                $name = $_SESSION['sst_user_name'];
                $url = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER["SERVER_PORT"].$this->webroot . "pr/pr_invoices/createpdf_invoice";
                
                $sql = "SELECT nextval('class4_seq_invoice_no'::regclass) AS next_number";
                $invoice_number_result = $this->Invoice->query($sql);
                $invoice_number = $invoice_number_result[0][0]['next_number'];
                $sql = "SELECT count(*) FROM invoice WHERE invoice_number = '{$invoice_number}'";
                $invoice_number_result = $this->Invoice->query($sql);
                if($invoice_number_result[0][0]['count'] > 0) {
                   $sql = "SELECT setval('class4_seq_invoice_no', (select max(invoice_number::bigint)+1 from invoice)) as next_number";
                   $invoice_number_result = $this->Invoice->query($sql);
                   $invoice_number = $invoice_number_result[0][0]['next_number'];
                }
                
                $this->data['Invoice']['is_show_daily_usage'] = $this->data['Invoice']['is_show_daily_usage'] ? 1 : 0;
                $this->data['Invoice']['is_short_duration_call_surcharge_detail'] = $this->data['Invoice']['is_short_duration_call_surcharge_detail'] ? 1: 0;
                $this->data['Invoice']['invoice_include_payment'] = $this->data['Invoice']['invoice_include_payment'] ? 1 : 0;
                $this->data['Invoice']['is_invoice_account_summary'] = $this->data['Invoice']['invoice_include_payment'] ? 1 : 0;
                $this->data['Invoice']['invoice_jurisdictional_detail'] = $this->data['Invoice']['invoice_jurisdictional_detail'] ? 1 : 0;
                $this->data['Invoice']['include_detail'] = $this->data['Invoice']['include_detail'] ? 1 : 0;
                
                $sql = "insert into invoice_log(start_time) values (CURRENT_TIMESTAMP) returning id";
                $log_result = $this->Invoice->query($sql);
                $log_id = $log_result[0][0]['id'];
                $cmd = "perl {$exec_path} -n '{$invoice_number}' -c {$exec_conf_path} -s '{$this->data['Invoice']['invoice_start']}' -e '{$this->data['Invoice']['invoice_end']}' -i {$this->data['Invoice']['client_id']} -y {$this->data['Invoice']['type']} -z '{$this->data['Invoice']['invoice_zone']}' -t '{$this->data['Invoice']['invoice_time']}' -d '{$this->data['Invoice']['due_date']}' -u '{$url}' -o {$this->data['Invoice']['output_type']} -l {$this->data['Invoice']['include_detail']} -j {$this->data['Invoice']['invoice_jurisdictional_detail']} -p {$this->data['Invoice']['decimal_place']} -r {$this->data['Invoice']['rate_value']} -f {$this->data['Invoice']['is_invoice_account_summary']} -v {$this->data['Invoice']['is_show_daily_usage']} -k {$this->data['Invoice']['is_short_duration_call_surcharge_detail']} -g {$this->data['Invoice']['invoice_include_payment']} -w '{$this->data['Invoice']['usage_detail_fields']}' -q '{$name}' -b {$this->data['Invoice']['create_type']} --log {$log_id} --btype {$this->data['Invoice']['invoice_use_balance_type']} > /dev/null &";
                if (Configure::read('cmd.debug')) {
                    echo $cmd;exit;
                }
                shell_exec($cmd);
                echo 'Re-generate Invoice Success.';
                } else {
                    echo 'Invoice Period duplicate';
                }
                
            } else {
                $this->Invoice->query("update invoice set state = -1 where invoice_id = " . intval($invoice_id));
                echo 'Set Invoice Void Success.';
                /* $this->Invoice->create_json_array('#ClientOrigRateTableId',201,'Set Invoice Void Success.');
                  $this->set ('m', Invoice::set_validator ()); //向界面设置验证信息
                  $this->redirect('pr/pr_invoices/invoices/view'); */
            }
        }
    }

    public function incoming_invoice() {
        $this->set('url_get', $this->params['url']);
        $results = $this->Invoice->get_in_invoice($this->_order_condtions(array('invoice_number', 'paid', 'type', 'client', 'invoice_start', 'total_amount', 'invoice_time')));
        $this->set('p', $results);
        $clients = $this->Invoice->query("select client_id, name from client order by name asc");
        $this->set('clients', $clients);
    }

    public function add_incoming() {
        $this->loadModel('Transaction');
        if ($this->RequestHandler->isPost()) {
            
            $invoice_number = $_POST['invoice_number'];
            $client_id = $_POST['client_id'];
            $start = $_POST['start'];
            $end = $_POST['end'];
            $gmt = $_POST['gmt'];
            $invoice_amount = $_POST['invoice_amount'];
            $paid_amount = !empty($_POST['paid_amount']) ? $_POST['paid_amount'] : 0;
            $due_date = $_POST['due_date'];
            $invoice_time = $_POST['invoice_date'];
            $file_path = 'NULL';
            
            if(empty($invoice_number)) {
                $sql = "SELECT nextval('class4_seq_invoice_no'::regclass) AS next_number";
                $invoice_number_result = $this->Invoice->query($sql);
                $invoice_number = $invoice_number_result[0][0]['next_number'];
                $sql = "SELECT count(*) FROM invoice WHERE invoice_number = '{$invoice_number}'";
                $invoice_number_result = $this->Invoice->query($sql);
                if($invoice_number_result[0][0]['count'] > 0) {
                   $sql = "SELECT setval('class4_seq_invoice_no', (select max(invoice_number::bigint)+1 from invoice)) as next_number";
                   $invoice_number_result = $this->Invoice->query($sql);
                   $invoice_number = $invoice_number_result[0][0]['next_number'];
                }
            } else {
                $sql = "SELECT count(*) FROM invoice WHERE invoice_number = '{$invoice_number}'";
                $invoice_number_result = $this->Invoice->query($sql);
                if($invoice_number_result[0][0]['count'] > 0) {
                    $this->Transaction->create_json_array('', 101, __('Invoice number duplicate!', true));
                    $this->Session->write('m', Transaction::set_validator());
                    $this->redirect('/pr/pr_invoices/add_incoming');
                }
            }
            
            if(is_uploaded_file($_FILES['invoice_file']['tmp_name'])) {
                $upload_dir = APP . 'webroot' . DS .'upload/incoming_invoice/';
                $extension = pathinfo($_FILES['invoice_file']['name'], PATHINFO_EXTENSION);
                $name = "invoice_" . substr(md5(microtime()), 0, 5) . '.' . $extension;
                $destname = $upload_dir . $name;
                $result = move_uploaded_file($_FILES['invoice_file']['tmp_name'], $destname);
                $file_path = $name;
                
            }
                     
            
            
            //$sql = "INSERT INTO invoice (invoice_number, client_id, invoice_start, invoice_end, total_amount, pay_amount, due_date, type, current_balance)
            //       VALUES ('{$invoice_number}',{$client_id}, TIMESTAMP '{$start} {$gmt}', TIMESTAMP '{$end} {$gmt}', {$invoice_amount}, {$paid_amount}, TIMESTAMP '{$due_date}', {$type}, 0)";
            $sql = <<<EOT
    
   INSERT INTO 

invoice(invoice_number,client_id, invoice_time, invoice_start,invoice_end, 
due_date, type, invoice_zone, pdf_path, total_amount, current_balance, pay_amount)

VALUES('{$invoice_number}', '{$client_id}', '{$invoice_time}', TIMESTAMP '{$start} {$gmt}', 

TIMESTAMP '{$end} {$gmt}', '{$due_date}',3, '{$gmt}', '{$file_path}', {$invoice_amount}, 
(SELECT balance::numeric FROM client_balance WHERE client_id = '{$client_id}')
,0)

EOT;
            $this->Transaction->query($sql);
            $this->Transaction->logging(0, 'Invoice', "Invoice Number:{$invoice_number}");
            $this->Transaction->create_json_array('', 201, __('Successfully!', true));
            $this->Session->write('m', Transaction::set_validator());
            $this->redirect('/pr/pr_invoices/incoming_invoice');
        }
        $clients = $this->Transaction->get_clients();
        $this->set('clients', $clients);
    }

    public function edit_incoming($id) {
        $this->loadModel('Transaction');
        if ($this->RequestHandler->isPost()) {
            $invoice_number = $_POST['invoice_number'];
            $client_id = $_POST['client_id'];
            $start = $_POST['start'];
            $end = $_POST['end'];
            $gmt = $_POST['gmt'];
            $invoice_amount = $_POST['invoice_amount'];
            $paid_amount = !empty($_POST['paid_amount']) ? $_POST['paid_amount'] : 0;
            $due_date = $_POST['due_date'];
            $type = 2;
            $sql = "UPDATE invoice SET invoice_number = '{$invoice_number}', client_id = {$client_id}, invoice_start = TIMESTAMP '{$start} {$gmt}'
                , invoice_end = TIMESTAMP '{$end} {$gmt}', total_amount = {$invoice_amount}, pay_amount = {$paid_amount}, due_date = TIMESTAMP '{$due_date}' 
                WHERE invoice_id = {$id}";
            $this->Transaction->query($sql);
            $this->Transaction->create_json_array('', 201, __('Successfully!', true));
            $this->Session->write('m', Transaction::set_validator());
            $this->redirect('/pr/pr_invoices/incoming_invoice');
        }
        $sql = "SELECT invoice_number, client_id, invoice_start, invoice_end, total_amount, pay_amount, due_date FROM invoice WHERE invoice_id = {$id}";
        $data = $this->Transaction->query($sql);
        $clients = $this->Transaction->get_clients();
        $this->set('clients', $clients);
        $this->set('data', $data);
    }

    public function change_type($invoice_id, $state, $return) {
        $this->autoRender = FALSE;
        $this->autoLayout = FALSE;
        $sql = "SELECT invoice_number, client_id, ingress_cdr_file, egress_cdr_file FROM invoice WHERE invoice_id = {$invoice_id}";
        $data = $this->Invoice->query($sql);
        $invoice_number = $data[0][0]['invoice_number'];
        $invoice_client_id = $data[0][0]['client_id'];
        $egress_cdr_file = $data[0][0]['egress_cdr_file'];
        $ingress_cdr_file = $data[0][0]['ingress_cdr_file'];
        $this->Invoice->logging(2, 'Invoice', "Invoice Number:{$data[0][0]['invoice_number']}");            
        
        if ($state == '-1') {
            $sql1 =<<<EOT
            SELECT 

client_id,
            
type,
            
invoice_number,

(SELECT COALESCE(sum(amount), 0) FROM client_payment WHERE invoice_number = invoice.invoice_number AND payment_type in (7, 8)) as credit, 

(SELECT COALESCE(sum(amount), 0) FROM client_payment WHERE invoice_number = invoice.invoice_number AND payment_type in (11, 12)) as debit,
(SELECT sum(amount) FROM client_payment WHERE invoice_number = invoice.invoice_number AND payment_type = 3) as pay_amount

FROM 

invoice 

WHERE invoice_id = {$invoice_id};
EOT;
            $invoice_type = $this->Invoice->query($sql1);
            $type = $invoice_type[0][0]['type'];
            $credit = $invoice_type[0][0]['credit'];
            $debit = $invoice_type[0][0]['debit'];
            $client_id = $invoice_type[0][0]['client_id'];
            $invoice_number = $invoice_type[0][0]['invoice_number'];
            $pay_amount = (float)$invoice_type[0][0]['pay_amount'];
            $sql_s = "SELECT amount FROM client_payment where invoice_number = '{$invoice_number}'";
            $result_s = $this->Invoice->query($sql_s);
            if (!empty($result_s))
                $s_amount = "+{$result_s[0][0]['amount']}";
            else
                $s_amount = "";
            
            if ($type == '0') {
                $sql2 = "UPDATE client_balance SET ingress_balance=ingress_balance::real-{$credit}+{$debit}{$s_amount}, balance=balance::real-{$credit}+{$debit}{$s_amount} WHERE client_id = '{$client_id}'";
                $this->Invoice->query($sql2);
            } elseif ($type =='1') {
                $sql2 = "UPDATE client_balance SET egress_balance=egress_balance::real+{$credit}-{$debit}, balance=balance::real+{$credit}-{$debit} WHERE client_id = '{$client_id}'";
                $this->Invoice->query($sql2);
            }
            $sql3 = "UPDATE payment_invoice set invoice_id = NULL WHERE invoice_id = '{$invoice_id}'";
            $this->Invoice->query($sql3);
            
        }
        $this->Invoice->query("UPDATE invoice SET state = {$state} WHERE invoice_id = {$invoice_id}");
          if($state == 9) {
            //$sql = "select carrier_invoice_subject,carrier_invoice_content from mail_tmplate";
            $sql = "select invoice_subject,invoice_content,invoice_cc from mail_tmplate";
            $mail_sub_content = $this->Invoice->query($sql);
            $tmpl_sub = $mail_sub_content[0][0]['invoice_subject'];
            $tmpl_cont = $mail_sub_content[0][0]['invoice_content'];
            $tmpl_cc = $mail_sub_content[0][0]['invoice_cc'];
            $sql = "select invoice.invoice_number, client.name,client.billing_email,client.company,invoice.invoice_start,invoice.invoice_end 
            from invoice 
            left join client on invoice.client_id = client.client_id where invoice_id = {$invoice_id}";
            $result = $this->Invoice->query($sql);
            $sql = "SELECT invoice_from from mail_tmplate";
            $invoice_from = $this->Invoice->query($sql);
            if ($invoice_from[0][0]['invoice_from'] && $invoice_from[0][0]['invoice_from']!='Default') {
               $email_info = $this->Invoice->query("SELECT loginemail, smtp_host as smtphost, smtp_port as smtpport, username as username, password,name as name, email as from, secure as smtp_secure FROM mail_sender WHERE id = {$invoice_from[0][0]['invoice_from']}");
            } else {
               $email_info = $this->Invoice->query('SELECT fromemail as "from", smtphost, smtpport,emailusername as username,loginemail, emailpassword as  "password", emailname as "name", smtp_secure,realm,workstation FROM system_parameter');
            }
            if($result[0][0]['billing_email'] == '') {
                $this->Invoice->create_json_array('',101,__('The billing email of [' . $result[0][0]['name'] . '] does not exist!',true));
                $this->Session->write('m',Invoice::set_validator());
                $this->redirect('/pr/pr_invoices/view/0');
                return;
            }
            $send_address = $result[0][0]['billing_email'];
            
            Configure::load('myconf');
            $invoice_path = Configure::read('generate_invoice.path');

            $invoice_name = $this->_get_invoice_name();
            $invoice_date = $this->_get_invoice_date($invoice_number);

            $invoice_file = $invoice_path . DS . $invoice_number . '_invoice.pdf';

            $filename = $invoice_name . '_' . $invoice_number . '_' . $invoice_date .'.pdf';
            
            if (file_exists($invoice_file)) {
                
            } else {
                $pdf_contents = file_get_contents($this->getUrl() . "pr/pr_invoices/createpdf_invoice/".$invoice_number);
                file_put_contents($invoice_file, $pdf_contents);
            }
            
            $cdr_down_url = '';
            if (!empty($ingress_cdr_file)) {
                $cdr_down_url .= $this->getUrl() . 'pr/pr_invoices/cdr_download/ingress/' . $invoice_number;
            }
            
            if (!empty($egress_cdr_file)) {
                $cdr_down_url .= "\n" . $this->getUrl() . 'pr/pr_invoices/cdr_download/egress/' . $invoice_number;
            }
            
            $abs_file_path = realpath($invoice_file);
            try {
            
                App::import('Vendor', 'nmail/phpmailer');
                $mailer = new phpmailer();
                if ($email_info[0][0]['loginemail'] === 'false') {
                    $mailer->IsMail();
                } else {
                    $mailer->IsSMTP();
                }
                $mailer->SMTPDebug = 2;
                $mailer->SMTPAuth 	= $email_info[0][0]['loginemail'] === 'false' ? false : true;
                switch ($email_info[0][0]['smtp_secure']) {
                    case 1:
                        $mailer->SMTPSecure = 'tls';
                        break;
                    case 2:
                        $mailer->SMTPSecure = 'ssl';
                        break;
                    case 3:
                        $mailer->AuthType = 'NTLM';
                        $mailer->Realm = $email_info[0][0]['realm'];
                        $mailer->Workstation = $email_info[0][0]['workstation'];
                }
                $mailer->From 		= $email_info[0][0]['from'];
                $mailer->FromName	= $email_info[0][0]['name'];
                $mailer->Host		= $email_info[0][0]['smtphost'];
                $mailer->Port		= intval($email_info[0][0]['smtpport']);
                $mailer->Username	= $email_info[0][0]['username'];
                $mailer->Password	= $email_info[0][0]['password'];
                $mailer->CharSet = "UTF-8";
                $addresses = explode(';', $send_address);
                foreach ($addresses as $adress) {
                    $mailer->AddAddress($adress);
                }
                if ($tmpl_cc != '') {
                    $tml_ccs = explode(';', $tmpl_cc);
                    foreach ($tml_ccs as $tml_cc)
                        $mailer->AddCC ($tml_cc);
                }
                $subject = str_replace(array('{company_name}', '{start_date}', '{end_date}', '{invoice_number}'), array($result[0][0]['company'], $result[0][0]['invoice_start'], $result[0][0]['invoice_end'], $result[0][0]['invoice_number']), $tmpl_sub); 
                $content = str_replace(array('{company_name}', '{start_date}', '{end_date}', '{invoice_number}', '{cdr_url}'), array($result[0][0]['company'], $result[0][0]['invoice_start'], $result[0][0]['invoice_end'], $result[0][0]['invoice_number'], $cdr_down_url), $tmpl_cont); 
                $mailer->ClearAttachments();
                $mailer->AddAttachment($invoice_file, $filename);
                $mailer->IsHTML(true);
                $mailer->Subject = $subject;
                $mailer->Body = $content;
                if($mailer->Send()) {
                    $current_datetime = date("Y-m-d H:i:s");
                    $sql = "insert into email_log (send_time, client_id, email_addresses, files, type) values('{$current_datetime}', $invoice_client_id, '{$send_address}', '{$abs_file_path}', 7)";
                    $this->Invoice->query($sql);
                } 
            } catch (phpmailerException $e) {
                echo $e->errorMessage();
            }
          }
        $message = "voided";
        if($state == '1'){
            $message ="to verify state";
        }else if($state == '9'){
            $message ="sent";
        }
        $this->Invoice->create_json_array('', 201, __('The Invoice ['.$invoice_number.'] was '.$message, true));
        $this->Session->write('m', Invoice::set_validator());
        if ($return !== NULL)
            //echo '';
            $this->redirect('/pr/pr_invoices/view/' . $return);
        else {
            $this->autoRender = false;
            $this->autoLayout = false;
        }
    }

    public function set_disputed() {
        Configure::write('debug', 0);
        $this->autoRender = FALSE;
        $this->autoLayout = FALSE;
        $invoice_id = $_GET['invoice_id'];
        $disputed_amount = $_GET['amount'];
        $sql = "UPDATE invoice SET disputed_amount = {$disputed_amount}, disputed = 1 WHERE invoice_id = {$invoice_id}";
        $this->Invoice->query($sql);
        echo 1;
    }
    
    public function delete_credit_note($credit_id, $invoice_no) {
        $sql_invoice = "SELECT total_amount,type,client_id FROM invoice WHERE invoice_number = '{$invoice_no}'";
        $invoice_info  = $this->Invoice->query($sql_invoice);
        if($invoice_info[0][0]['type'] == '0') {
            $type = 8;
        } else {
            $type = 7;
        }
        $sql = "SELECT amount FROM client_payment WHERE client_payment_id = {$credit_id}";
        $amount_info = $this->Invoice->query($sql);
        $amount = $amount_info[0][0]['amount'];
        if($type == '7') {
            $sql1 = "UPDATE client_balance SET egress_balance=egress_balance::real+{$amount}, balance=balance::real+{$amount} WHERE client_id = '{$invoice_info[0][0]['client_id']}'";
        } else {
            $sql1 = "UPDATE client_balance SET ingress_balance=ingress_balance::real-{$amount}, balance=balance::real-{$amount} WHERE client_id = '{$invoice_info[0][0]['client_id']}'";
        }
        $this->Invoice->query($sql1);
        $this->Invoice->query("DELETE FROM client_payment WHERE client_payment_id = {$credit_id}");
        $this->Invoice->logging(1, 'Credit Note', "Invoice Number:{$invoice_no}");
        $this->Invoice->create_json_array('', 201, __('Sucessfully!', true));
        $this->Session->write('m', Invoice::set_validator());
        $this->redirect('/pr/pr_invoices/credit_note/' . $invoice_no);
    }

    public function credit_note($invoice_no) {
        $sql_invoice = "SELECT total_amount,type,client_id,invoice_time FROM invoice WHERE invoice_number = '{$invoice_no}'";
        $invoice_info  = $this->Invoice->query($sql_invoice);
        if($invoice_info[0][0]['type'] == '0') {
            $type = 8;
        } else {
            $type = 7;
        }
        $sql2 = "SELECT COALESCE(sum(amount), 0) as sum FROM client_payment WHERE invoice_number = '{$invoice_no}' and payment_type = {$type}";
        $result2 = $this->Invoice->query($sql2);
        $total = $result2[0][0]['sum'];
        if ($this->RequestHandler->isPost()) {
            $note = $_POST['note'];
            $amount = $_POST['amount'];
            if(($total + $amount + 0.5) > $invoice_info[0][0]['total_amount']) {
                $this->Invoice->create_json_array('', 101, __('Can not larger than invoice amount!', true));
            } else {
                $total += $amount;
                $sql = "INSERT INTO client_payment(invoice_number,amount,description, payment_type, payment_time, result, client_id) VALUES ('{$invoice_no}', $amount, '{$note}', {$type}, '{$invoice_info[0][0]['invoice_time']}', true, {$invoice_info[0][0]['client_id']})";
                $this->Invoice->query($sql);
                $this->Invoice->logging(0, 'Credit Note', "Invoice Number:{$invoice_no}");
                if($type == '7') {
                    $sql1 = "UPDATE client_balance SET egress_balance=egress_balance::real-{$amount}, balance=balance::real-{$amount} WHERE client_id = '{$invoice_info[0][0]['client_id']}'";
                } else {
                    $sql1 = "UPDATE client_balance SET ingress_balance=ingress_balance::real+{$amount}, balance=balance::real+{$amount} WHERE client_id = '{$invoice_info[0][0]['client_id']}'";
                }
                $this->Invoice->query($sql1);
                $this->Invoice->query("update client set mail_sended = 0 where client_id = ".$invoice_info[0][0]['client_id']);
                $this->Invoice->create_json_array('', 201, __('Successfully!', true));
            }
            $this->Session->write('m', Invoice::set_validator());
        }
        $sql = "SELECT client_payment_id,invoice_number,amount,description,payment_type FROM client_payment WHERE invoice_number = '{$invoice_no}' and payment_type = {$type} ORDER BY payment_time DESC";
        $result = $this->Invoice->query($sql);
        $this->set('data', $result);
        $this->set('total', $total);
        $this->set('invoice_no', $invoice_no);
    }
    
    
    public function delete_debit($debit_id, $invoice_no) {
        $sql_invoice = "SELECT type,client_id FROM invoice WHERE invoice_number = '{$invoice_no}'";
        $invoice_info  = $this->Invoice->query($sql_invoice);
        if($invoice_info[0][0]['type'] == '0') {
            $type = 12;
        } else {
            $type = 11;
        }
        $sql = "SELECT amount FROM client_payment WHERE client_payment_id = {$credit_id}";
        $amount_info = $this->Invoice->query($sql);
        $amount = $amount_info[0][0]['amount'];
        if($type == '12') {
            $sql1 = "UPDATE client_balance SET ingress_balance=ingress_balance::real+{$amount}, balance=balance::real+{$amount} WHERE client_id = '{$invoice_info[0][0]['client_id']}'";
        }  else {
            $sql1 = "UPDATE client_balance SET egress_balance=egress_balance::real-{$amount}, balance=balance::real-{$amount} WHERE client_id = '{$invoice_info[0][0]['client_id']}'";
        }
        $this->Invoice->query($sql1);
        $this->Invoice->query("DELETE FROM client_payment WHERE client_payment_id = {$debit_id}");
        $this->Invoice->logging(1, 'Debit Note', "Invoice Number:{$invoice_no}");
        $this->Invoice->create_json_array('', 201, __('Sucessfully!', true));
        $this->Session->write('m', Invoice::set_validator());
        $this->redirect('/pr/pr_invoices/debit/' . $invoice_no);
    }
    
    public function debit($invoice_no) {
        $sql_invoice = "SELECT type,client_id,invoice_time FROM invoice WHERE invoice_number = '{$invoice_no}'";
        $invoice_info  = $this->Invoice->query($sql_invoice);
        if($invoice_info[0][0]['type'] == '0') {
            $type = 12;
        } else {
            $type = 11;
        }
        if ($this->RequestHandler->isPost()) {
            $note = $_POST['note'];
            $amount = $_POST['amount'];
            $sql = "INSERT INTO client_payment(invoice_number,amount,description, payment_type, payment_time, result, client_id) VALUES ('{$invoice_no}', $amount, '{$note}', {$type}, '{$invoice_info[0][0]['invoice_time']}', true, {$invoice_info[0][0]['client_id']})";
            $this->Invoice->query($sql);
            if($type == '12') {
                $sql1 = "UPDATE client_balance SET ingress_balance=ingress_balance::real-{$amount}, balance=balance::real-{$amount} WHERE client_id = '{$invoice_info[0][0]['client_id']}'";
                
            }  else {
                $sql1 = "UPDATE client_balance SET egress_balance=egress_balance::real+{$amount}, balance=balance::real+{$amount} WHERE client_id = '{$invoice_info[0][0]['client_id']}'";
            }
            $this->Invoice->query($sql1);
            $this->Invoice->logging(0, 'Debit Note', "Invoice Number:{$invoice_no}");
            $this->Invoice->query("update client set mail_sended = 0 where client_id = ".$invoice_info[0][0]['client_id']);
            $this->Invoice->create_json_array('', 201, __('Successfully!', true));
            $this->Session->write('m', Invoice::set_validator());
        }
        $sql = "SELECT client_payment_id, invoice_number,amount,description,payment_type FROM client_payment WHERE invoice_number = '{$invoice_no}' and payment_type = {$type} ORDER BY payment_time DESC";
        $sql2 = "SELECT COALESCE(sum(amount), 0) as sum FROM client_payment WHERE invoice_number = '{$invoice_no}' and payment_type = {$type}";
        $result = $this->Invoice->query($sql);
        $result2 = $this->Invoice->query($sql2);
        $this->set('data', $result);
        $this->set('total', $result2[0][0]['sum']);
        $this->set('invoice_no', $invoice_no);
        
    }

    public function recon($invoice_id) {
        $datas = array();
        $sql = "select client_id,invoice_start,invoice_end,reconcile_state from invoice where invoice_id = {$invoice_id}";
        $info = $this->Invoice->query($sql);
        if ($this->RequestHandler->isPost()) {
            if (is_uploaded_file($_FILES['upfile']['tmp_name'])) {
                if (!empty($info)) {
                    $name = $_FILES['upfile']['name'];
                    $dest =  APP . 'webroot' . DS . 'upload' . DS . 'invoice_reconcile' .DS .  uniqid('con_') . '.csv' ;
                    $result = move_uploaded_file($_FILES['upfile']['tmp_name'], $dest);
                    /*
                    if ($result) {
                        $sql = "DELETE FROM invoice_reconcile WHERE invoice_id = {$invoice_id}";
                        $this->Invoice->query($sql);
                        $client_id = $info[0][0]['client_id'];
                        $start_time = $info[0][0]['invoice_start'];
                        $end_time = $info[0][0]['invoice_end'];
                        $handle = fopen($dest, 'r');
                        $i = 0;
                        while ($data = fgetcsv($handle)) {
                            $i++;
                            if ($i == 1)
                                continue;
                            $code = $data[0];
                            $minute = $data[1];
                            $cost = $data[2];
                            list($sys_minute, $sys_cost) = $this->Invoice->get_sys_cdr($start_time, $end_time, $client_id, $code);
                            $diff_minute_amt = $minute - $sys_minute;
                            $diff_minute_per = $diff_minute_amt / $minute;
                            $diff_cost_amt = $cost - $sys_cost;
                            $diff_cost_per = $diff_cost_amt / $cost;
                            $sql = "INSERT INTO invoice_reconcile(code, minute, cost, sys_minute, sys_cost, minute_diff_amt, minute_diff_per,
                                cost_diff_amt, cost_diff_per, invoice_id) VALUES ('{$code}', $minute, $cost, $sys_minute, $sys_cost,
                                $diff_minute_amt, $diff_minute_per, $diff_cost_amt, $diff_cost_per, $invoice_id)";
                            $this->Invoice->query($sql);
                            
                        }
                        fclose($handle);
                    }
                    */
                    if($result) {
                        $sql = "update invoice set reconcile_file_path = '{$dest}', reconcile_state = 0 WHERE invoice_id = {$invoice_id}";
                        $this->Invoice->query($sql);
                        $info[0][0]['reconcile_state'] = 0;
                    }
                } else {
                    $this->Invoice->create_json_array('', 101, __('Invoice Not Found!', true));
                    $this->Session->write('m', Invoice::set_validator());
                }
            }
        }
        $temp = isset ($_SESSION['paging_row']) ? $_SESSION['paging_row'] : 100;
        empty($_GET['page'])?$currPage = 1:$currPage = $_GET['page'];
        empty($_GET['size'])?$pageSize = $temp:$pageSize = $_GET['size'];
        $_SESSION['paging_row'] = $pageSize;
        require_once MODELS.DS.'MyPage.php';
        $counts = $this->Invoice->get_reconcile_count($invoice_id);
        $page = new MyPage ();
        $page->setTotalRecords ($counts); 
        $page->setCurrPage ($currPage);
        $page->setPageSize ($pageSize); 
        $currPage = $page->getCurrPage()-1;
        $pageSize = $page->getPageSize();
        $offset=$currPage*$pageSize;
        $data = $this->Invoice->get_reconcile($invoice_id, $pageSize, $offset);  
        $page->setDataArray($data);
        $this->set('p',$page);
        $this->set('invoice_id', $invoice_id);
        $status = array('unexecute', 'executing', 'complete');
        $this->set('status', $status[$info[0][0]['reconcile_state']]);
    }
    
    public function start_reconcile($invoice_id) {
        $this->autoLayout = false;
        $this->autoRender = false;
        $ch = curl_init();
        $fp = fsockopen($_SERVER['SERVER_NAME'], $_SERVER['SERVER_PORT'], $errno, $errstr, 30);
        if (!$fp) {
            echo "$errstr ($errno)<br />\n";
        } else {
            $out = "GET {$this->webroot}pr/pr_invoices/do_reconcile/{$invoice_id} HTTP/1.1\r\n";
            $out .= "Host: localhost\r\n";
            $out .= "Connection: Close\r\n\r\n";

            fwrite($fp, $out);
            /*
            忽略执行结果
            while (!feof($fp)) {
                echo fgets($fp, 128);
            }
            */
            fclose($fp);
        }
        $this->redirect('/pr/pr_invoices/recon/' . $invoice_id);
    }
    
    public function do_reconcile($invoice_id) {
        $this->autoLayout = false;
        $this->autoRender = false;
        $sql = "select client_id,invoice_start,invoice_end,reconcile_file_path from invoice where invoice_id = {$invoice_id}";
        $info = $this->Invoice->query($sql);
        $this->Invoice->query("update invoice set  reconcile_state = 1 WHERE invoice_id = {$invoice_id}");
        if (!empty($info)) {
            $sql = "DELETE FROM invoice_reconcile WHERE invoice_id = {$invoice_id}";
            $this->Invoice->query($sql);
            $client_id = $info[0][0]['client_id'];
            $start_time = $info[0][0]['invoice_start'];
            $end_time = $info[0][0]['invoice_end'];
            $file_path = $info[0][0]['reconcile_file_path'];
            $handle = fopen($file_path, 'r');
            $i = 0;
            while ($data = fgetcsv($handle)) {
                $i++;
                if ($i == 1)
                    continue;
                $code = $data[0];
                $minute = $data[1];
                $cost = $data[2];
                list($sys_minute, $sys_cost) = $this->Invoice->get_sys_cdr($start_time, $end_time, $client_id, $code);
                $diff_minute_amt = $minute - $sys_minute;
                $diff_minute_per = round($diff_minute_amt / $minute * 100, 2);
                $diff_cost_amt = $cost - $sys_cost;
                $diff_cost_per = round($diff_cost_amt / $cost, 2);
                $sql = "INSERT INTO invoice_reconcile(code, minute, cost, sys_minute, sys_cost, minute_diff_amt, minute_diff_per,
                    cost_diff_amt, cost_diff_per, invoice_id) VALUES ('{$code}', $minute, $cost, $sys_minute, $sys_cost,
                    $diff_minute_amt, $diff_minute_per, $diff_cost_amt, $diff_cost_per, $invoice_id)";
                $this->Invoice->query($sql);

            }
            fclose($handle); 
            $this->Invoice->query("update invoice set reconcile_state = 2 WHERE invoice_id = {$invoice_id}");
        }
    }
    
    public function get_recom_example_file() {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        header("Content-Type: text/csv");
        header("Content-Disposition: attachment; filename=reconcile.csv");
        header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
        readfile(APP.'webroot' .DS .'upload' . DS .'reconcile_example.csv');
    }
    
    public function down_reconcile_list($invoice_id) {
        header("Content-Type: text/csv");
        header("Content-Disposition: attachment; filename=reconcile.csv");
        header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
        header('Expires:0');
        header('Pragma:public');
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $sql = "SELECT code, minute, cost, sys_minute, sys_cost, minute_diff_amt, minute_diff_per, cost_diff_amt, cost_diff_per FROM invoice_reconcile
            WHERE invoice_id = {$invoice_id} ORDER by code DESC";
        $data = $this->Invoice->query($sql);
        print(",Partner,,System,,Minute Diff,,Cost Diff\r\n");
        print("Code,Minute,Cost,Minute,Cost,Amt,%,Amt,%\r\n");
        foreach($data as $item) {
            print(implode(",", $item[0]) . "\r\n");
        }
    }
    
    /*
     * 指定位某天Invoice充值
     * 
     * @param integer $invoice_id   Invoice ID
     * @param integer $type         Invoice Type 1.incoming  2.outgoing
     * @return NULL
     */
    
    public function payment_to_invoice($invoice_id, $type, $client_id, $invoice_type) {
        if ($this->RequestHandler->isPost()) {
            $payment = $_POST['payment'];
            $receiving_time = $_POST['payment_date'];
            $note = $_POST['note'];
            if($type == 1) {
                // payment type = 4
                $sql1 = "update client_balance set balance=balance::real+{$payment}, ingress_balance=ingress_balance::real+{$payment} where client_id = '{$client_id}' returning balance";
                $client_balance_info = $this->Invoice->query($sql1);
                $sql2 = "update invoice set pay_amount = pay_amount+{$payment}, current_balance= {$client_balance_info[0][0]['balance']} where invoice_id = {$invoice_id}";
                $this->Invoice->query($sql2);
                $sql3 = "INSERT INTO client_payment(client_id, payment_type, amount, current_balance,invoice_number, payment_time, result, receiving_time)
    VALUES ({$client_id},4,{$payment},{$client_balance_info[0][0]['balance']},(SELECT invoice_number FROM invoice WHERE invoice_id = {$invoice_id}), 'now', TRUE, '{$receiving_time}')";
                $this->Invoice->query($sql3);
            } elseif ($type == 2) {
                // payment type  = 3
                $sql1 = "update client_balance set balance=balance::real-{$payment}, egress_balance=egress_balance::real-{$payment} where client_id = '{$client_id}' returning balance";
                $client_balance_info = $this->Invoice->query($sql1);
                $sql2 = "update invoice set pay_amount = pay_amount+{$payment}, current_balance= {$client_balance_info[0][0]['balance']} where invoice_id = {$invoice_id}";
                $this->Invoice->query($sql2);
                $sql3 = "INSERT INTO client_payment(client_id, payment_type, amount, current_balance,invoice_number, payment_time, result, receiving_time)
    VALUES ({$client_id},3,{$payment},{$client_balance_info[0][0]['balance']},(SELECT invoice_number FROM invoice WHERE invoice_id = {$invoice_id}), 'now', TRUE, '{$receiving_time}')";
                $this->Invoice->query($sql3);
            }
            $this->Invoice->query("update client set daily_balance_notification = low_balance_number where client_id = ".$client_id);
            $this->Invoice->create_json_array('', 201, __('succeeded!', true));
            $this->Session->write('m', Invoice::set_validator());
            
            if($invoice_type == 'incoming_invoice') {
                $this->redirect("/pr/pr_invoices/incoming_invoice");
            } else {
                $this->redirect("/pr/pr_invoices/view/{$invoice_type}");
            }
        }
        $invoice_info = $this->Invoice->get_invoice_info($invoice_id);
        $this->set('invoice_info', $invoice_info);
        
    }
    
    public function export_invoice_excel()  {
        
    }

    
    public function incoming_invoice_mass_edit()
    {
        //Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $ids = isset($_POST['ids']) && count($_POST['ids']) ? $_POST['ids'] : array();
        $action = isset($_POST['action']) ? $_POST['action'] : '';
        if ($action == 1)
        {
            foreach ($ids as $id) {
                $this->Invoice->delete($id);
            }
            $this->Invoice->create_json_array('', 201, __('The Incoming Invoices you selected is deleted successfully!', true));
            $this->Session->write('m', Invoice::set_validator());
            $this->redirect("/pr/pr_invoices/incoming_invoice");
        }
    }
    
}
