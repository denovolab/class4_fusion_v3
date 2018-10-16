<?php


class EmailLogController extends AppController {
    
    var $name = "EmailLog"; 
    var $helpers = array('Javascript','Html', 'Text'); 
    var $components = array('RequestHandler');  
    var $uses = array('EmailLog');
    
    public function beforeFilter() {
        $this->checkSession("login_type"); //核查用户身份
        $login_type = $this->Session->read('login_type');
        if ($login_type == 1) {
            $this->Session->write('executable', true);
            $this->Session->write('writable', true);
        } else {
            $limit = $this->Session->read('sst_config_CodeDeck');
            $this->Session->write('executable', $limit['executable']);
            $this->Session->write('writable', $limit['writable']);
        }
        parent::beforeFilter();
    }
    
    public function index() {
        
        $start_time = date("Y-m-d 00:00:00");
        $end_time   = date("Y-m-d 23:59:59");
        
        if (isset($_GET['start_time']))
        {
            $start_time = $_GET['start_time'];
            $end_time   = $_GET['end_time'];            
        }
        
        $conditions =  array(
            'EmailLog.send_time BETWEEN ? and ?' => array($start_time, $end_time) 
        );
        
        if (isset($_GET['client']) && !empty($_GET['client']))
        {
            $conditions["EmailLog.client_id"] = $_GET['client'];
        }
        
        if (isset($_GET['type']) && !empty($_GET['type']))
        {
            $conditions["EmailLog.type"] = $_GET['type'];
        }
        
        $this->paginate = array(
            'fields' => array(
                'EmailLog.send_time', 'EmailLog.email_addresses', 'EmailLog.files', 
                'EmailLog.type', 'client.name', 
            ),
            'limit' => 100,
            'joins' => array(
                array(
                    'table' => 'client',
                    'type'  => 'INNER',
                    'conditions' => array(
                        'EmailLog.client_id = client.client_id'
                    ),
                )
            ),
            'order' => array(
                'EmailLog.id' => 'desc',
            ),
            'conditions' => $conditions
        );
        $types = array(
          1 => 'low balance',
          2 => 'daily summary',
          3 => 'auto delivery',
          4 => 'alert route',
          5 => 'cdr_down',
          6 => 'exchange alert route',
          7 => 'invoice',
        );
        $this->data = $this->paginate('EmailLog');
        $this->set('types', $types);
        $this->set('clients', $this->EmailLog->get_carriers());
        $this->set('start_time', $start_time);
        $this->set('end_time', $end_time);
    }
    
    public function get_file($file) {
        Configure::write('debug', 0);
        $this->autoLayout = false;
        $this->autoRender = false;
        $file_path = base64_decode($file);
        $basename = basename($file_path);
	$basename = str_replace(' ','_',$basename);
        header("Content-type: application/octet-stream");
        header("Content-Disposition: attachment; filename={$basename}");
        header("Content-Description: CDR Record");
        readfile($file_path);
    }
    
    
}
