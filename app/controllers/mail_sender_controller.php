<?php

class MailSenderController extends AppController 
{
    var $name = "MailSender"; 
    var $helpers = array('Javascript','Html', 'Text'); 
    var $components = array('RequestHandler');  
    var $uses = array('MailSender');
    
    public function beforeFilter() 
    {
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
        $this->paginate = array(
            'limit' => 100,
            'order' => array(
                'MailSender.id' => 'desc',
            ),
        );
        
        $this->data = $this->paginate('MailSender');
        $this->set('secures', array(0 => '', 1 => 'TLS', 2 => 'SSL', 3 => 'NTLM'));
    }
    
    public function modify_panel($id = null)
    {
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        if ($this->isPost()) {
            if($id != null)
            {
                $this->data['MailSender']['id'] = $id;
                $this->Session->write('m', $this->MailSender->create_json(201, __('The Mail Sender [' . $this->data['MailSender']['name'] . '] is modified successfully!', true)));
            }
            else
            {
                $this->Session->write('m', $this->MailSender->create_json(201, __('The Mail Sender [' . $this->data['MailSender']['name'] . '] is created successfully!', true)));
            }
            $this->MailSender->save($this->data);
            $this->xredirect("/mail_sender/index/");
        }
        $this->data = $this->MailSender->find('first', Array('conditions' => Array('id' => $id)));
    }
    
    public function delete($id)
    {
        $this->autoLayout = false;
        $this->autoRender = false;
        
        $mail_sender = $this->MailSender->findById($id);
        $this->MailSender->del($id);
        
        $this->Session->write('m', $this->MailSender->create_json(201, __('The Mail Sender [' . $mail_sender['MailSender']['name'] . '] is created successfully!', true)));
        $this->xredirect("/mail_sender/index/");
    }
}

