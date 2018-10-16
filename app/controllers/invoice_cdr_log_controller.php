<?php

class InvoiceCdrLogController extends AppController
{

    var $name = 'InvoiceCdrLog';
    var $uses = array('InvoiceCdrLog');
    var $components = array();

    public function beforeFilter()
    {
        $this->checkSession("login_type"); //核查用户身份exprot
        parent::beforeFilter(); //调用父类方法
    }
    
    public function index($type=0)
    {
        $this->pageTitle="Log/Invoice CDR Log";
        $this->paginate = array(
            'limit' => 100,
            'order' => array(
                'id' => 'desc',
            ),
            'conditions' => array(
                'type' => $type,
            )
        );
       $this->data = $this->paginate('InvoiceCdrLog');
       $this->set('type', $type);
    }

}