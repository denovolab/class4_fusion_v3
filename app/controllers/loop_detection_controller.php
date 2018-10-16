<?php 

class LoopDetectionController extends AppController
{
    var $name = "LoopDetection"; 
    var $helpers = array('Javascript','Html', 'Text', 'Common'); 
    var $components = array('RequestHandler');  
    var $uses = array('LoopDetection', 'LoopDetectionLog', 'LoopDetectionLogDetail');
    
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
    
    public function index()
    {
        $this->pageTitle="Monitoring/Loop Detection";
        
        if ($this->RequestHandler->isPost())
        {
            $interval = $_POST['interval'];
            $threshold = $_POST['threshold'];
            $block_length = $_POST['block_length'];
            $block_for = $_POST['block_for'] ? $_POST['block_for'] : 0;
            $avoid_same = isset($_POST['avoid_same']) ? 'true' : 'false';
            
            $sql = "select count(*) from loop_detection";
            $result = $this->LoopDetection->query($sql);
            if ($result[0][0]['count'] > 0) {
                $sql = "update loop_detection set interval = {$interval}, threshold = {$threshold}, block_length = {$block_length}, block_for = {$block_for}
                        , avoid_same = {$avoid_same}";
            } else {
                $sql = "insert into loop_detection(interval, threshold, block_length, block_for, avoid_same) values ($interval, $threshold, $block_length, 
                        $block_for, $avoid_same)";
            }
            $this->LoopDetection->query($sql);
            $this->LoopDetection->create_json_array('', 201, __('Succeeded!', true));
            $this->Session->write('m', LoopDetection::set_validator());
        }
        $sql = "select * from loop_detection limit 1";
        $data = $this->LoopDetection->query($sql);
        $this->set('data', $data);
    }
    
    public function logging()
    {
        $this->pageTitle="Monitoring/Loop Detection";
        $this->paginate = array(
            'limit' => 100,
            'order' => array(
                'id' => 'desc',
            ),
        );
       $this->data = $this->paginate('LoopDetectionLog');
    }
    
    public function logging_detail($id)
    {
        $this->pageTitle="Monitoring/Loop Detection";
        $this->paginate = array(
            'limit' => 100,
            'order' => array(
                'id' => 'desc',
            ),
            'conditions' => array(
                'loop_detection_log_id' => $id,
            )
        );
       $this->data = $this->paginate('LoopDetectionLogDetail');
    }
}