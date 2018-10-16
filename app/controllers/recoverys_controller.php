<?php

class RecoverysController extends AppController
{
    var $name = "Recoverys";
    var $uses = array('Systemparam');
    var $components = array('RequestHandler');
    var $helpers = array('javascript', 'html', 'Common');
    
    public function beforeFilter() {
        $this->checkSession("login_type");
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
        $this->redirect('/recoverys/cdr');
    }
    
    public function cdr()
    {        
        $this->pageTitle = "Tools/CDR Recovery";
        if ($this->RequestHandler->isPost())
        {
            $start_time = $_POST['start'];
            $end_Time   = $_POST['end'];
            $script_path = Configure::read('script.path');
            $script_conf = Configure::read('script.conf');
            $script_file = $script_path . DS . "class4_recover_cdr.pl";
            $cmd = "perl $script_file -c $script_conf -s '$start_time' -e '$end_Time' > /dev/null 2&>1 &";
            shell_exec($cmd);
            $this->Systemparam->create_json_array('', 201, __('The time between ' . $start_time . ' to ' . $end_Time . " is recovered successfully!" ,true));
            $this->Session->write("m",Systemparam::set_validator());
        }
        $data = array();
        $tmp_path = Configure::read('cdr.tmp');
        $cmd = "find {$tmp_path} - -name '*.tmp' -type f -exec ls -alh {} \;";
        $handle = @popen($cmd, 'r');
        if ($handle)
        {
            while (($buffer = fgets($handle, 4096)) !== false)
            {
                $buffer = preg_split('/\s+/', $buffer);
                $buffer[8] = basename($buffer[8]);
                array_push($data, $buffer);
            }
            pclose($handle);
        }        
        
        sort($data);
        
        $this->set('data', $data);        
    }
    
}