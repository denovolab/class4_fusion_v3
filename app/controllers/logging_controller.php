<?php


class LoggingController extends AppController {
    
    var $name = "Logging"; 
    var $helpers = array('Javascript','Html', 'Text', 'Common'); 
    var $components = array('RequestHandler');  
    var $uses = array('Logging');
    
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
        $this->pageTitle="Log/Modification Log";
        
        $start_date = isset($_GET['start_date']) ? $_GET['start_date']:date("Y-m-d");
        $end_date = isset($_GET['stop_date']) ? $_GET['stop_date']:date("Y-m-d");
        
        $start_time = isset($_GET['start_time']) ? $_GET['start_time']:"00:00:00";
        $end_time = isset($_GET['stop_time']) ? $_GET['stop_time']:"23:59:59";
        
        $tz = isset($_GET['gmt']) ? $_GET['gmt'] : "+0000";
      
            
        $start_datetime = $start_date. ' ' .$start_time.$tz;
        $end_datetime = $end_date. ' ' .$end_time.$tz;
        
        $this->paginate = array(
            'fields'=>'DISTINCT time, module, type, name, detail',
            'limit' => 100,
            'order' => array(
                'Logging.time' => 'desc',
            ),
            'conditions' => array(
                "Logging.time BETWEEN '{$start_datetime}' AND '{$end_datetime}'",
            ),
        );
        
        if(isset($_GET['operator'])) {
            array_push($this->paginate['conditions'], "Logging.name ilike '%{$_GET['operator']}%'");
        }
        
        if(isset($_GET['target'])) {
            array_push($this->paginate['conditions'], "Logging.detail ilike '%{$_GET['target']}%'");
        }
        
        if(isset($_GET['action']) && $_GET['action'] != 'all') {
            array_push($this->paginate['conditions'], "Logging.type = {$_GET['action']}");
        }
                
        $actions = array(
            '0' => 'Creation',
            '1' => 'Deletion',
            '2' => 'Modification',
        );
        $this->data = $this->paginate('Logging');
        $this->set('actions', $actions);
        $this->set('start_date', $start_date);
        $this->set('end_date', $end_date);
        $this->set('start_time', $start_time);
        $this->set('end_time', $end_time);
        $this->set('tz', $tz);
    }
    
    public function update_log_current()
    {
        $this->pageTitle="Log/Current Update Log";
        
        $file_path = Configure::read('update_log.current');
        $data = file_get_contents($file_path);
        $data = str_replace("\n", "<br />", $data);
        $data = str_replace("\t", "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;", $data);
        $this->set('data', $data);
    }
    
    public function update_log_history()
    {
        $this->pageTitle="Log/History Update Log";
        
        $file_path = Configure::read('update_log.history');
        
        $data = file_get_contents($file_path);
        $data = str_replace("\n", "<br />", $data);
        $data = str_replace("\t", "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;", $data);
        $this->set('data', $data);
    }
    
}


?>
