<?php

class SwitchProfilerController extends AppController {
    var $name = "SwitchProfiler";
    var $uses = array('SwitchProfile');
    var $helpers = array ('Javascript', 'Html');
    
    
    public function beforeFilter() {
        $this->checkSession("login_type"); //核查用户身份
    }
    
    public function index($server_id) {
        $this->pageTitle = "Configuration/SIP Profile";
        $this->paginate = array(
            'limit' => 100,
            'order' => array(
                'id' => 'desc',
            ),
            'conditions' => array(
                'voip_gateway_id' => $server_id
            ),
        );
        $voip_gateway_name = $this->SwitchProfile->get_voip_server_name($server_id);
        $this->set('server_id', $server_id);
        $this->set('status', array('INIT','ACTIVE','DEACTIVE','SHUTDOWN','DESDROY'));
        $this->set('gateway_name', $voip_gateway_name);
        $this->data = $this->paginate('SwitchProfile');
    }
    
    
    public function action_edit_panel($server_id, $id=null) {
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        if ($this->isPost()) {
            $voip_gateway_name = $this->SwitchProfile->get_voip_server_name($server_id);
            $this->data['SwitchProfile']['voip_gateway_id'] = $server_id;
            $this->data['SwitchProfile']['switch_name'] = $voip_gateway_name;
            if($id != null)
            {
                $this->data['SwitchProfile']['id'] = $id;
                $this->Session->write('m', $this->SwitchProfile->create_json(201, __('The SIP Profile [' . $this->data['SwitchProfile']['profile_name'] . '] is modified successfully!', true)));
            }
            else
            {
                $this->_check_data($server_id, $this->data['SwitchProfile']['profile_name'], $this->data['SwitchProfile']['sip_ip'], $this->data['SwitchProfile']['sip_port']);
                $this->Session->write('m', $this->SwitchProfile->create_json(201, __('The SIP Profile [' . $this->data['SwitchProfile']['profile_name'] . '] is created successfully!', true)));
            }
            $this->SwitchProfile->save($this->data);
            $this->xredirect("/switch_profiler/index/".$server_id);
        }
        $this->data = $this->SwitchProfile->find('first', Array('conditions' => Array('id' => $id)));
        $this->set('server_id', $server_id);
        $this->set('id', $id);
        $this->set('isedit', $id != null);
    }
    
    public function _check_data($server_id, $profile_name, $ip, $port)
    {
        Configure::write('debug', 0);
        $result = $this->SwitchProfile->check_data($server_id, $profile_name, $ip, $port);
        if (!$result)
        {
            $this->Session->write('m', $this->SwitchProfile->create_json(101, __('The SIP Profile must be unique!', true)));
            $this->xredirect("/switch_profiler/index/".$server_id);
        }
    }
    
    public function delete($server_id, $id) {
        $this->autoLayout = false;
        $this->autoRender = false;
        $this->data = $this->SwitchProfile->find('first', Array('conditions' => Array('id' => $id)));
        $this->SwitchProfile->del($id);
        $this->Session->write('m', $this->SwitchProfile->create_json(201, __('The SIP Profile [' . $this->data['SwitchProfile']['switch_name'] . '] is deleted successfully!', true)));
        $this->xredirect("/switch_profiler/index/{$server_id}");
    }
    
    public function reload() {
        $this->autoLayout = false;
        $this->autoRender = false;
        $cmd = "sip_profile_start";
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
        $this->Session->write('m', $this->SwitchProfile->create_json(201, __('Successfully!', true)));
        $this->xredirect("/server_config/index");
    }
}

?>
