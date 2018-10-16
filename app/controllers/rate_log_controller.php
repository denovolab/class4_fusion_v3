<?php

class RateLogController extends AppController {
    var $name = 'RateLog';
    var $helpers = array();
    var $uses = array('ImportRateStatus');
    var $components =array();
    
    
    
    public function beforeFilter(){
	$this->checkSession ( "login_type" );//核查用户身份exprot
        if( $this->params['action'] == 'get_file')
            return true;
        parent::beforeFilter();//调用父类方法
    }
    
    public function import($rate_table_id = null) {
        $this->paginate = array(
            'fields' => array(
                'ImportRateStatus.status', 'ImportRateStatus.delete_queue', 'ImportRateStatus.update_queue', 
                'ImportRateStatus.insert_queue', 'ImportRateStatus.error_counter', 
                'ImportRateStatus.reimport_counter', 'ImportRateStatus.error_log_file', 'ImportRateStatus.reimport_log_file',
                'ImportRateStatus.time', 'ImportRateStatus.upload_file_name', 'ImportRateStatus.local_file',
                'ImportRateStatus.method', 'rate_table.name', 'rate_table.rate_table_id', 'users.name',
                'ImportRateStatus.start_epoch','ImportRateStatus.end_epoch',
            ),
            'limit' => 100,
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
            'order' => array(
                'ImportRateStatus.id' => 'desc',
            ),
        );
        $status = array("Error!", "Running", "Insert", "Update", "Delete", "Done", "Done");
        $status["-1"] = 'Waiting';
        $status["-2"] = "End Date";
        $this->set('status', $status);
        $this->data = $this->paginate('ImportRateStatus');
        $this->set('rate_table_id', $rate_table_id);
    }
    
    public function stop($ratetable_id) {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $cmd = "kill -9 `cat /tmp/rate_import_{$ratetable_id}_pid`";
        shell_exec($cmd);
        $sql = "update import_rate_status set status = 6 where rate_table_id = {$ratetable_id}";
        $this->ImportRateStatus->query($sql);
        $this->redirect('/rate_log/import');
    }
    
    public function get_file() {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $file = base64_decode($_GET['file']);
        header("Content-type: application/octet-stream");
        header('Content-Disposition: attachment; filename="' . basename($file) . '"');
        header("Content-Length: ". filesize($file));
        readfile($file);
    }
    
    
}

?>
