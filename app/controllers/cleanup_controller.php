<?php

class CleanupController extends AppController
{
    var $name = "Cleanup";
    var $uses = array('Cleanup');
    
    public function beforeFilter() 
    {
        parent::beforeFilter();
        $this->checkSession("login_type"); 
    }
    
    public function index()
    {    
        $this->pageTitle = 'Configuration/Back-Up and Data Cleansing';
        $cleanups = $this->Cleanup->find('all', array(
            'order' => array('Cleanup.id'),            
        ));
        
        $this->set('cleanups', $cleanups);
    }
    
    public function edit_panel($id = NULL)
    {
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        if ($this->isPost()) {
            if($id != null)
            {
                $this->data['Cleanup']['id'] = $id;
                $this->Session->write('m', $this->Cleanup->create_json(201, __('The  [' . $this->data['Cleanup']['name'] . '] is modified successfully!', true)));
            }
            else
            {
                $this->Session->write('m', $this->Cleanup->create_json(201, __('The  [' . $this->data['Cleanup']['name'] . '] is created successfully!', true)));
            }
            $this->Cleanup->save($this->data);
            $this->xredirect("/cleanup/index");
        }
        $this->data = $this->Cleanup->find('first', Array('conditions' => Array('id' => $id)));
        $this->set('id', $id);
    }
    
    public function change_status($id)
    {
        Configure::write('debug', 0);
        $this->autoLayout = false;
        $this->autoRender = false;
        $cleanup = $this->Cleanup->findById($id);
        
        if ($cleanup['Cleanup']['actived']) {
            $cleanup['Cleanup']['actived'] = false;
            $this->Cleanup->create_json_array("", 201, 'The [' . $cleanup['Cleanup']['name'] . '] is inactived successfully !');
        } else {
            $cleanup['Cleanup']['actived'] = true;
            $this->Cleanup->create_json_array("", 201, 'The [' . $cleanup['Cleanup']['name'] . '] is actived successfully !');
        }     
        $this->Cleanup->save($cleanup);
        $this->xredirect(array('controller' => 'cleanup', 'action' => 'index')); 
    }
    
}