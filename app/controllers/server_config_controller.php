<?php

class ServerConfigController extends AppController
{
    var $name = "ServerConfig";
    var $uses = array('ServerConfig');
    
    public function beforeFilter() 
    {
        $this->checkSession("login_type"); //核查用户身份
        parent::beforeFilter();
    }
    
    public function index() 
    {
        $this->pageTitle = "Configuration/VoIP Gateway";
        $this->paginate = array(
            'limit' => 100,
            'order' => array(
                'id' => 'desc',
            ),
        );
        $this->data = $this->paginate('ServerConfig');
    }
    
    public function action_edit_panel($id=null) 
    {
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        if ($this->isPost()) {
            
            
            if ($this->ServerConfig->exists_name($this->data['ServerConfig']['name'], $id))
            {
                $this->Session->write('m', $this->ServerConfig->create_json(101, __('The VoIP Server Name [' . $this->data['ServerConfig']['name'] . '] is already exists!', true)));
                $this->xredirect("/server_config/index");
            }
            if($id != null)
            {
                $this->data['ServerConfig']['id'] = $id;
                $this->Session->write('m', $this->ServerConfig->create_json(201, __('The VoIP Server [' . $this->data['ServerConfig']['name'] . '] is modified successfully!', true)));
            }
            else
                $this->Session->write('m', $this->ServerConfig->create_json(201, __('The VoIP Server [' . $this->data['ServerConfig']['name'] . '] is created successfully!', true)));
            $this->ServerConfig->save($this->data);
            $this->xredirect("/server_config/index");
        }
        $this->data = $this->ServerConfig->find('first', Array('conditions' => Array('id' => $id)));
    }
    
    public function _check_server_name()
    {
        
    }

}

?>