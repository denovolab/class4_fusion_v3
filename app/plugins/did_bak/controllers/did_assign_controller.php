<?php 

class DidAssignController extends DidAppController
{
    var $name = "DidAssign";
    var $uses = array('did.DidAssign','did.DidRepos');
    var $helpers = array('javascript', 'html', 'Common');
    
    function beforeFilter()
    {
        $this->checkSession("login_type"); //核查用户身份
        parent::beforeFilter();
    }
    
    public function index($egress_id = null)
    {
        $this->pageTitle = "Origination/Egress DID Assignment";
        $this->paginate = array(
            'limit' => 100,
            'order' => array(
                'DidAssign.created_time' => 'desc',
            ),
        );
        
        if(isset($_GET['search']) && !empty($_GET['search']))
        {
            $this->paginate['conditions'][] = array("DidAssign.number::text like '%{$_GET['search']}%'");
        }
        if ($egress_id != null) 
        {
            $this->paginate['conditions'][] = array("DidAssign.egress_id = {$egress_id}");
            $this->set('vendor_name', $this->DidRepos->get_vendor_name($egress_id));
        }
        
        if (isset($_GET['advsearch']))
        {
            $ingress_id = $_GET['ingress_id'];
            $egress_id  = $_GET['egress_id'];
            $number     = $_GET['number'];
            
            if (!empty($ingress_id)) 
            {
                $this->paginate['conditions'][] = array("DidAssign.ingress_id = {$ingress_id}");
            }
            if (!empty($egress_id)) 
            {
                $this->paginate['conditions'][] = array("DidAssign.egress_id = {$egress_id}");
            }
            if (!empty($number)) 
            {
                $this->paginate['conditions'][] = array("DidAssign.number like %'{$number}'%");
            }
        }
        
        $this->_get_data();
        $this->data = $this->paginate('DidAssign');
        foreach ($this->data as &$item)
        {
            $item = array_merge($item,$this->DidRepos->findByNumber($item['DidAssign']['number']));
        }
        
    }
    
    public function create()
    {
    	Configure::write('debug', 0);
        $this->_get_data();
        $countries = $this->DidRepos->get_countries();
        $this->set('countries', $countries);
    }
    
    public function search_number()
    {
    	Configure::write('debug', 0);
    	$this->autoRender = false;
    	$this->autoLayout = false;
    	//$page     = intval($_POST['page']);
    	//$pageSize = 50;
    	$offset    = ($page - 1) * $pageSize;
    	$country  = $_POST['country'];
    	$state    = $_POST['state'];
    	$city     = $_POST['city'];
    	$rate_center = $_POST['rate_center'];
    	$number = $_POST['number'];
    	$data = $this->DidAssign->get_number($country, $state, $city, $rate_center, $number);
    	echo json_encode($data);
    }
    
    public function assign()
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
    	$egress_id = $_POST['egress_id'];
    	$numbers = $_POST['numbers'];
        $product_id = $this->DidAssign->check_default_static();
        foreach($numbers as $number)
        {
            $item_id = $this->DidAssign->add_new_number($number, $product_id);
            $this->DidAssign->add_new_resouce($item_id, $egress_id);
            $this->DidAssign->add_assign($number, $egress_id);
        }
        echo json_encode(array('result' => 1));
    }
    
    public function change_status($number, $status)
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        /*
        $product_id = $this->DidAssign->check_default_static();
        if ($status == 0)
        {
            $this->DidAssign->delete_number($number, $product_id);
        }
        else
        {
            $item_id = $this->DidAssign->add_new_number($number, $product_id);
            $this->DidAssign->add_new_resouce($item_id, $egress_id);
            $this->DidAssign->add_assign($number, $egress_id);
        }
         * 
         */
        if ($status == 0)
        {
            $this->Session->write('m', $this->DidAssign->create_json(201, __('The  status of  number  [' . $number . '] is inactived successfully!', true)));
        }
        else
        {
            $this->Session->write('m', $this->DidAssign->create_json(201, __('The  status of  number  [' . $number . '] is actived successfully!', true)));
        }
        $sql = "update did_assign set status = {$status} where number = '{$number}';
        update ingress_did_repository set status = {$status} where number = '{$number}';";
        $this->DidAssign->query($sql);
        
        $this->xredirect("/did/did_assign/index");
    }
    
    
    public function _get_data($client_id = '')
    {
        $this->set('ingresses', $this->DidRepos->get_ingress());
        $this->set('egresses', $this->DidRepos->get_egress($client_id));
    }
    
    public function action_edit_panel($egress_id = '', $number = null)
    {
        Configure::write('debug', 0);
        if ($egress_id == '0') $egress_id = '';
        
        $client_id = '';        
        if (!empty($egress_id)) {
            $sql = "select client_id from resource where resource_id = {$egress_id}";
            $result = $this->DidAssign->query($sql);
            $client_id = $result[0][0]['client_id'];
        }
            
        
        $this->_get_data($client_id);
        $this->layout = 'ajax';
        if ($this->isPost())
        {
            if ($number != null)
            {
                $product_id = $this->DidAssign->check_default_static();
                $this->DidAssign->delete_number($number, $product_id);
                $item_id = $this->DidAssign->add_new_number($number, $product_id);
                $this->DidAssign->add_new_resouce($item_id, $this->data['DidAssign']['egress_id']);
                $this->data['DidAssign']['number'] = $number;
                $this->Session->write('m', $this->DidAssign->create_json(201, __('The number of [' . $this->data['DidAssign']['number'] . '] is modified successfully!', true)));
            }
            else
                $this->Session->write('m', $this->DidAssign->create_json(201, __('The number of [' . $this->data['DidAssign']['number'] . '] is created successfully!', true)));
            $this->DidAssign->save($this->data);
            $this->xredirect("/did/did_assign/index/" . $egress_id);
        }
        $this->data = $this->DidAssign->find('first', Array('conditions' => Array('number' => $number)));
    }
    
    public function listing()
    {
        $client_id = $this->Session->read('sst_client_id');
        
        $this->pageTitle = "DID Listing";
        $this->paginate = array(
            'limit' => 100,
            'order' => array(
                'DidAssign.created_time' => 'desc',
            ),
            'joins'      => array(
                array(
                    'table' => 'resource',
                    'alias' => "Resource",
                    'type'  => 'INNER',
                    'conditions' => array(
                        'DidAssign.egress_id = Resource.resource_id',
                    ),
                ),
             ),
            'conditions' => array('Resource.client_id' => $client_id, 'Resource.egress' => true, 'Resource.trunk_type2' => 1),
        );
        
        if(isset($_GET['search']) && !empty($_GET['search']))
        {
            //$this->paginate['conditions'][] = array("DidAssign.number::text like '%{$_GET['search']}%'");
        }
        
        
        $this->_get_data();
        $this->data = $this->paginate('DidAssign');
    }
    
}

?>
