<?php 

class BillingRuleController extends DidAppController
{
    var $name = 'BillingRule';
    var $uses = array('did.DidBillingPlan', 'did.DidSpecialCode');
    var $components = array('RequestHandler');
    
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
        $this->redirect('/did/billing_rule/plan');
    }
    
    public function plan()
    {
        $this->pageTitle = "Origination/Billing Rule";
        $this->paginate = array(
            'limit' => 100,
            'order' => array(
                'id' => 'desc',
            ),
        );
        $this->data = $this->paginate('DidBillingPlan');
    }
    
    public function plan_edit_panel($id=null)
    {
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        if ($this->isPost()) {
            if($id != null)
            {
                $this->data['DidBillingPlan']['id'] = $id;
                $this->Session->write('m', $this->DidBillingPlan->create_json(201, __('The Plan [' . $this->data['DidBillingPlan']['name'] . '] is modified successfully!', true)));
            }
            else
            {
                $this->Session->write('m', $this->DidBillingPlan->create_json(201, __('The Plan [' . $this->data['DidBillingPlan']['name'] . '] is created successfully!', true)));
            }
            $this->DidBillingPlan->save($this->data);
            $this->xredirect("/did/billing_rule/plan");
        }
        $this->data = $this->DidBillingPlan->find('first', Array('conditions' => Array('id' => $id)));
        $this->set('id', $id);
    }
    
    
    
    public function delete_rule($id)
    {
        $data = $this->DidBillingPlan->find('first', Array('conditions' => Array('id' => $id)));
        $this->DidBillingPlan->del($id);
        $this->Session->write('m', $this->DidBillingPlan->create_json(201, __('The Plan [' . $data['DidBillingPlan']['name'] . '] is deleted successfully!', true)));
        $this->xredirect("/did/billing_rule/plan");
    }
    
    public function special_code()
    {
        $this->pageTitle = "Origination/Special Code";
        $this->paginate = array(
            'limit' => 100,
            'order' => array(
                'code' => 'asc',
            ),
        );
        $this->data = $this->paginate('DidSpecialCode');
    }
    
    public function code_edit_panel($id=null)
    {
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        if ($this->isPost()) {
            if($id != null)
            {
                $this->data['DidSpecialCode']['id'] = $id;
                $this->Session->write('m', $this->DidSpecialCode->create_json(201, __('The Special Code [' . $this->data['DidSpecialCode']['code'] . '] is modified successfully!', true)));
            }
            else
            {
                $this->Session->write('m', $this->DidSpecialCode->create_json(201, __('The Special Code [' . $this->data['DidSpecialCode']['code'] . '] is created successfully!', true)));
            }
            $this->DidSpecialCode->save($this->data);
            $this->xredirect("/did/billing_rule/special_code");
        }
        $this->data = $this->DidSpecialCode->find('first', Array('conditions' => Array('id' => $id)));
        $this->set('id', $id);
    }
    
    public function delete_code($id)
    {
        $data = $this->DidSpecialCode->find('first', Array('conditions' => Array('id' => $id)));
        $this->DidSpecialCode->del($id);
        $this->Session->write('m', $this->DidSpecialCode->create_json(201, __('The Special Code [' . $data['DidSpecialCode']['code'] . '] is deleted successfully!', true)));
        $this->xredirect("/did/billing_rule/special_code");
    }
    
    
}