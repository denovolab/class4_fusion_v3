<?php

class GatewaygroupsController extends AppController { 

    var $name = 'Gatewaygroups';
    var $helpers = array('javascript', 'html', 'appGetewaygroup');
    var $defaultHelper = 'appGetewaygroup';
    var $uses = array("Client", "Blocklist", "prresource.Gatewaygroup", "ResourceNextRouteRule", 'Resource');

    function index() {
        $this->redirect('view_egress');
    }

    function init_info() {
        $this->set('c', $this->Gatewaygroup->findClient());
        $this->set('r', $this->Gatewaygroup->findDigitMapping());
        $this->set('d', $this->Gatewaygroup->findcodecs());
        //$this->set('p', $this->Gatewaygroup->findAllProduct());
        $this->set('rate', $this->Gatewaygroup->findAllRate());
        $this->set('switch_profiles', $this->Gatewaygroup->get_gateway_profiles());
        $this->set('timepro', $this->Gatewaygroup->find_timeprofile());
        $this->set('route_policy', $this->Gatewaygroup->find_routepolicy());
        $this->loadModel('Blocklist');
        $reseller_id = $this->Session->read('sst_reseller_id');
        $this->set('timeprofiles', $this->Blocklist->getTimeProfiles($reseller_id));
        $this->loadModel('Client');
        //$this->set('product', $this->Client->findAllProduct());
        //$this->set('dyn_route', $this->Client->findDyn_route());
        $this->set('routepolicy', $this->Client->query("select * from route_strategy"));
        //$this->set('staticlist', $this->Client->query("select product_id, name from product order by name asc"));
        //$this->set('dynamiclist', $this->Client->query("select dynamic_route_id, name from dynamic_route order by name"));
        $this->set('default_timeout', $this->Gatewaygroup->getTimeout());
    }
    
    public function replace_action($egress_id, $type='egress')
    {
        if ($this->RequestHandler->ispost())
    	{
            $sql = "delete from resource_replace_action where resource_id = {$egress_id}";
            $this->Gatewaygroup->query($sql);
            if ($_POST)
            {
                $cnt = count($_POST['ani_prefix']);
                for($i= 0; $i < $cnt; $i++)
                {
                    $ani_prefix = @$_POST['ani_prefix'][$i];
                    $ani = @$_POST['ani'][$i];
                    $ani_min_length = @$_POST['ani_min_length'][$i];
                    if (empty($ani_min_length))
                        $ani_min_length = 'NULL';
                    $ani_max_length = @$_POST['ani_max_length'][$i];
                    if (empty($ani_max_length))
                        $ani_max_length = 'NULL';
                    $sql = "insert into resource_replace_action(resource_id, ani_prefix, ani, ani_min_length, ani_max_length)
                        values ($egress_id,'{$ani_prefix}', '{$ani}', {$ani_min_length}, {$ani_max_length})";
                    $this->Gatewaygroup->query($sql);    
                }
            }
        }
        
        
        $res = $this->Gatewaygroup->findByResourceId($egress_id);
    	$client_name = $this->Gatewaygroup->query("select name from client where client_id = (select client_id from resource where resource_id = {$egress_id});");
    	
        $sql = "select * from resource_replace_action where resource_id = {$egress_id}";
        $result = $this->Gatewaygroup->query($sql);
        $this->set('result', $result);
        $this->set('client_name', $client_name[0][0]['name']);
    	$this->set('res', $res);
        $this->set('type', $type);
    }
    
    public function pass_trusk($res_id, $type)
    {
    	
    	if ($this->RequestHandler->ispost())
    	{
    		$this->data['Gatewaygroup']['resource_id'] = $res_id;
    		$this->Gatewaygroup->save($this->data);
    		$res = $this->Gatewaygroup->findByResourceId($res_id);
    		$this->Gatewaygroup->create_json_array('#ClientOrigRateTableId', '201', 'The Egress Trunk ['.$res['Gatewaygroup'] ['alias'].'] is modified successfully !');
    		$this->Session->write("m", Gatewaygroup::set_validator());
    	} else {
    	
    		$res = $this->Gatewaygroup->findByResourceId($res_id);
    	
    	}
    	
    	$client_name = $this->Gatewaygroup->query("select name from client where client_id = (select client_id from resource where resource_id = {$res_id});");
    	
    	$this->set('res', $res);
    	$this->set('client_name', $client_name[0][0]['name']);
    	$this->set('type', $type);
    }
    
    public function sip_profile($res_id, $type)
    {
    	if ($this->RequestHandler->ispost())
    	{
	    	$profiles = $_POST['profiles'];
	    	$server_names = $_POST['server_names'];
                $ingresses = $_POST['ingress'];
	    	$this->Gatewaygroup->_update_profiles($res_id, $profiles, $server_names, $ingresses);
	    	$res = $this->Gatewaygroup->findByResourceId($res_id);
	    	$this->Gatewaygroup->create_json_array('#ClientOrigRateTableId', '201', 'The Egress Trunk ['.$res['Gatewaygroup'] ['alias'].'] is modified successfully !');
	    	$this->Session->write("m", Gatewaygroup::set_validator());
    	} else {
    		$res = $this->Gatewaygroup->findByResourceId($res_id);
    	}
    	
    	
    	 
    	$client_name = $this->Gatewaygroup->query("select name from client where client_id = (select client_id from resource where resource_id = {$res_id});");
    	 
    	$this->set('res', $res);
    	$this->set('switch_profiles', $this->Gatewaygroup->get_gateway_profiles());
        /*
        $ingresses = $this->Gatewaygroup->find('all', array(
            'conditions' => array('Gatewaygroup.ingress' => true),
            'fields' => array('Gatewaygroup.resource_id', 'Gatewaygroup.alias'),
            'order' => array('Gatewaygroup.alias'),
        ));
         * 
         */
        $this->set('ingresses', $this->Gatewaygroup->find_ingress_resource());
        $use_ingresses = $this->Gatewaygroup->get_profile_ingresses($res_id);
    	$profiles = $this->Gatewaygroup->get_profiles($res_id);
    	$this->set('sip_profiles', $profiles);
    	$this->set('client_name', $client_name[0][0]['name']);
        $this->set('use_ingresses', $use_ingresses);
    }
    
    public function automatic_rate_processing($egress_id)
    {
        $sql = "select * from automatic_rate where egress_id = {$egress_id}";
        $result = $this->Gatewaygroup->query($sql);
        
         $cols = array(
            'country',
            'code_name',
            'code',
            'rate',
            'inter_rate',
            'intra_rate',
            'local_rate',
            'effective_date',
        );
        
        if($this->RequestHandler->ispost())
        {
            $from_email = $_POST['from_email'];
            $day = $_POST['day'];
            $start_line = $_POST['start_line'];
            //$formats = implode(',', $_POST['formats']);
            $orders = $_POST['orders'];
            $change_orders = array_flip($orders);
            $new_orders = array();
            $num_columns = (int)$_POST['number_column'];
            for ($i = 1; $i <= $num_columns; $i++)
            {
                array_push($new_orders, isset($change_orders[$i]) ? $cols[$change_orders[$i]] : '');
            }
            
            $formats = implode(',', $new_orders);
            
            $end_date_type = $_POST['end_date_type'];
            $end_date_when = $_POST['end_date_when'];
            if (empty($result))
            {
                $sql = "insert into automatic_rate(from_email, day, start_line, format, end_date_type, end_date_when, egress_id) values ('{$from_email}', {$day}, {$start_line}, '{$formats}', {$end_date_type}, {$end_date_when}, {$egress_id})";
            }
            else
            {
                $sql = "update automatic_rate set from_email = '{$from_email}', day = {$day}, start_line = {$start_line}, format = '{$formats}', end_date_type = {$end_date_type}, end_date_when = {$end_date_when} where id = {$result[0][0]['id']}";
            }
            $this->Gatewaygroup->query($sql);
            $sql = "select alias, (select name from client where client_id = resource.client_id) as name from resource where resource_id = {$egress_id}";
            $name_result = $this->Gatewaygroup->query($sql);
            $this->Gatewaygroup->create_json(201, __('The Trunk [' .  $name_result[0][0]['name'] . ']\'s Automatic Rate Processing is modified successfully', true));
            $this->Session->write("m", Gatewaygroup::set_validator());
            $this->redirect('/prresource/gatewaygroups/automatic_rate_processing/' . $egress_id);
        }
        $sql = "select alias, (select name from client where client_id = resource.client_id) as name from resource where resource_id = {$egress_id}";
        $name_result = $this->Gatewaygroup->query($sql);
        $this->set('name', $name_result[0][0]['alias']);
        $this->set('client_name', $name_result[0][0]['name']);
        /*
        $cols = array(
            'country' => 'Country',
            'code_name' => 'Code Name',
            'code' => 'Code',
            'rate' => 'Rate',
            'inter_rate' => 'Interstate Rate',
            'intra_rate' => 'Intrastate Rate',
            'local_rate' => 'Local Rate',
            'effective_date' => 'Effective Date'
        );
         * 
         */
        $this->set('cols', $cols);
        if (empty($result))
        {
            $this->set('data', NULL);
        }
        else
        {
            $result[0][0]['format'] = explode(',', $result[0][0]['format']);
            $this->set('data', $result[0][0]);
        }
    }
    
     public function get_schema_ingress()
    {
        $fields = array(
            'trunk_id' => 'resource_id',
            'trunk_name' => 'alias',
            'carrier_name' => '(SELECT name FROM client WHERE client_id = resource.client_id)',
            'media_type' => "(case media_type when 1 then 'proxy' when 2 then 'bypass' end)",
            'cps_limit'  => "cps_limit",
            'call_limit' => 'capacity',
            'protocol'   => "(case proto when 1 then 'sip' when 2 then 'h323' when 3 then 'all' end)",
            'pdd_timeout' => 'ring_timeout',
            'ignore_early_media' => "(case when ignore_ring = false and ignore_early_media = false then 'None' when ignore_ring = true and ignore_early_media = true then '180 and 183' when ignore_ring =true and ignore_early_media = false then '180' case when ignore_ring = false and ignore_early_media = true then '183' end)",
            'active' => "(case active when true then 'true' else 'false' end)",
            't38' => "(case t38 when true then 'enable' else 'disable' end)",
            'rfc2833' => "(case rfc_2833 when true then 'true' else 'false' end)",
            'dip_from' => "(case lnp_dipping when true then 'client' else 'server' end)",
            'min_duration' => 'delay_bye_second',
            'max_duration' => 'max_duration',
            'lrn_block' => "(case lrn_block when true then 'true' else 'false' end)",
            'rate_table_name' => '(SELECT name FROM rate_table WHERE rate_table_id = resource_prefix.rate_table_id)',
            'route_strategy_name' => '(SELECT name FROM route_strategy WHERE route_strategy_id = resource_prefix.route_strategy_id)',
            'tech_prefix' => 'resource_prefix.tech_prefix',
        );
        
        return $fields;
    }
    
    public function get_schema_digit_mapping()
    {
        $fields = array(
            'trunk_name' => '(select alias from resource where resource_id = resource_translation_ref.resource_id)',
            'translation_name' => '(select translation_name from digit_translation where translation_id = resource_translation_ref.translation_id)',
            'time_profile_name' => "(select name from time_profile where time_profile_id = resource_translation_ref.time_profile_id)",
        );
        
        return $fields;
    }
    
    public function get_schema_host()
    {
        $fields = array(
            'trunk_name' => '(select alias from resource where resource_id = resource_ip.resource_id)',
            'ip' => 'ip',
            'port' => "port"
        );
        
        return $fields;
    }

    function save($type, $resource_id=null) {
        if (!$_SESSION['role_menu']['Routing']['gatewaygroups:view']['model_w']) {
            $this->redirect_denied();
        }
        $this->pageTitle = "Add Ingress Trunk";
        $type = _filter_array(Array('ingress' => 'ingress', 'egress' => 'egress'), $type);
        if ($this->isPost('data')) {
            if ($resource_id) {
                $this->data['Resource']['resource_id'] = $resource_id;
            }
            $this->data['Resource']['accounts'] = array_keys_value_empty($this->params, 'form.accounts', Array());
            if ($this->Resource->save($this->data)) {
                if (!$resource_id) {
                    $resource_id = $this->Resource->getlastinsertId();
                    $this->Gatewaygroup->log('add_ingress_trunk');
                    $this->Gatewaygroup->create_json_array('#ClientOrigRateTableId', 201, 'Add success');
                } else {
                    $this->Gatewaygroup->log('edit_ingress_trunk');
                    $this->Gatewaygroup->create_json_array('#ClientOrigRateTableId', 201, 'Edit success');
                }
                $this->xredirect("/prresource/gatewaygroups/save/$type/$resource_id");
                return;
            }
            $this->set('m', Gatewaygroup::set_validator()); //向界面设置验证信息
            $this->set('post', $this->data);
        }
        if ($resource_id) {
            $this->loadModel('Resource');
            $this->data = $this->Resource->find('first', Array('conditions' => Array("resource_id=$resource_id")));
        }
        $this->init_info();
        $this->init_codes($resource_id);
        $this->set('type', $type);
        $this->set('resource_id', $resource_id);
    }
    
    public function delete_ip_id()
    {
        Configure::write('debug', 0);
        $this->autoLayout = false;
        $this->autoRender = false;
        $ip_id = $_POST['ip_id'];
        $sql = "delete from resource_ip where resource_ip_id = {$ip_id}";
        $this->Gatewaygroup->query($sql);
        echo 1;
    }

    function init_codes($res_id=null) {
        if ($res_id) {
            $this->set('nousecodes', $this->Gatewaygroup->findNousecodecs($res_id));
            $this->set('usecodes', $this->Gatewaygroup->findUsecodecs($res_id));
        } else {
            $this->set('nousecodes', Array());
            $this->set('usecodes', Array());
        }
    }

    public function download_codepart() {
        if (!$_SESSION['role_menu']['Routing']['gatewaygroups:view']['model_x']) {
            $this->redirect_denied();
        }
        $rate_table_id = $this->params ['pass'] [0];
        $download_sql = "select    code,rate,setup_fee,effective_date,end_date,min_time,grace_time,interval,time_profile_id,seconds
		from  rate  where rate_table_id=$rate_table_id";
        $this->Rate->export__sql_data('Download', $download_sql, 'rate');
        Configure::write('debug', 0);
        $this->layout = '';
    }

    public function download_egress() {
        if (!$_SESSION['role_menu']['Routing']['gatewaygroups:view']['model_x']) {
            $this->redirect_denied();
        }
        Configure::write('debug', 0);
        $download_sql = "select client.name  as  client_name ,alias, resource.name,ingress,egress,active,t38,res_strategy,cps_limit,capacity,lnp,lrn_block,media_type,pass_through from  resource
left join client  on client.client_id=resource.client_id
		where egress=true ";
        $this->Gatewaygroup->export__sql_data('download Egress', $download_sql, 'egress');
        $this->layout = '';
    }

    public function download_ingress() {
        if (!$_SESSION['role_menu']['Routing']['gatewaygroups:view']['model_x']) {
            $this->redirect_denied();
        }
        Configure::write('debug', 0);
        $download_sql = "select resource.name,ingress,client.name as  client_name ,egress,active,t38,alias,res_strategy,cps_limit,capacity,lnp,lrn_block,media_type,pass_through from  resource
left join client  on client.client_id=resource.client_id
		where ingress=true ";
        $this->Gatewaygroup->export__sql_data(__('DownloadIngress', true), $download_sql, 'ingress');
        $this->layout = '';
    }

    public function view_cdr() {
        $res_id = $this->params ['pass'] [0];
        $this->init_info();
        empty($_GET ['page']) ? $currPage = 1 : $currPage = $_GET ['page'];
        empty($_GET ['size']) ? $pageSize = 100 : $pageSize = $_GET ['size'];
        //模糊搜索
        if (isset($_POST ['searchkey'])) {
            $results = $this->Gatewaygroup->likequery($_POST ['searchkey'], $currPage, $pageSize);
            $this->set('searchkey', $_POST ['searchkey']);
            $this->set('p', $results);
            return;
        }

        //高级搜索 
        if (!empty($this->data ['Gatewaygroup'])) {
            $results = $this->Gatewaygroup->Advancedquery($this->data, $currPage, $pageSize);
            $this->set('search', 'search'); //搜索设置
        } else {
            //普通查询
            $results = $this->Gatewaygroup->view_all_cdr($currPage, $pageSize, $res_id);
        }

        $this->set('p', $results);
    }

    //查看指定号段的cdr
    public function code_cdr() {
        $start_code = $this->params ['pass'] [0];
        $end_code = $this->params ['pass'] [1];
        $this->Gatewaygroup->query("select * from  cdr   where  origination_destination_number >'$start_code' and  origination_destination_number <'$end_code'");

        empty($_GET ['page']) ? $currPage = 1 : $currPage = $_GET ['page'];
        empty($_GET ['size']) ? $pageSize = 100 : $pageSize = $_GET ['size'];
        //模糊搜索
        if (isset($_POST ['searchkey'])) {
            $results = $this->Gatewaygroup->likequery($_POST ['searchkey'], $currPage, $pageSize);
            $this->set('searchkey', $_POST ['searchkey']);
            $this->set('p', $results);
            return;
        }

        //高级搜索 
        if (!empty($this->data ['Gatewaygroup'])) {
            $results = $this->Gatewaygroup->Advancedquery($this->data, $currPage, $pageSize);
            $this->set('search', 'search'); //搜索设置
        } else {
            $results = $this->Gatewaygroup->code_cdr($currPage, $pageSize, $start_code, $end_code);
        }
        $this->set('p', $results);
    }

    public function del_all_codepart() {
        if (!$_SESSION['role_menu']['Routing']['gatewaygroups:view']['model_w']) {
            $this->redirect_denied();
        }
        $egress_id = $_SESSION ['codepartengress'];
        $this->Gatewaygroup->query("delete from  code_part  where egress_id=$egress_id");
        $this->Gatewaygroup->log('delete_all_code_part');
        $this->redirect("/gatewaygroups/codepart/$egress_id");
    }

    public function del__codepart() {
        if (!$_SESSION['role_menu']['Routing']['gatewaygroups:view']['model_w']) {
            $this->redirect_denied();
        }
        $id = $this->params ['pass'] [0];
        $this->Gatewaygroup->query("delete from  code_part  where code_part_id=$id");
        $this->Gatewaygroup->log('delete_code_part');
        $egress_id = $_SESSION ['codepartengress'];
        $this->redirect("/gatewaygroups/codepart/$egress_id");
    }

    public function update_codepart() {
        if (!$_SESSION['role_menu']['Routing']['gatewaygroups:view']['model_x']) {
            $this->redirect_denied();
        }
        $start_code = $_GET ['start'];
        $end_code = $_GET ['end'];
        $card = $_GET ['card'];
        $id = $_GET ['id'];
        $egress_id = $_SESSION ['codepartengress'];
        $this->Gatewaygroup->query("update  code_part set start_code='$start_code',end_code='$end_code',account_id=$card,egress_id=$egress_id
			   where code_part_id=$id");
        $this->Gatewaygroup->log('update_code_part');
        $this->redirect("/gatewaygroups/codepart/$egress_id");
    }

    //读取该模块的执行和修改权限
    public function beforeFilter() {
        $this->checkSession("login_type"); //核查用户身份
        $login_type = $this->Session->read('login_type');
        if ($login_type == 1) {
            //admin
            $this->Session->write('executable', true);
            $this->Session->write('writable', true);
        } else {
            $limit = $this->Session->read('sst_voipgateway');
            $this->Session->write('executable', $limit ['executable']);
            $this->Session->write('writable', $limit ['writable']);
        }
        if (!$_SESSION['role_menu']['Routing']['gatewaygroups:view']['model_r']) {
            $this->redirect_denied();
        }
        if ($this->RequestHandler->isGet()) {
            $url = $this->get_curr_url();
            if (!isset($_SESSION['back_url'])) {
                $last_url = $url;
                $curr_url = $url;
                $_SESSION['back_url'] = $last_url;
                $_SESSION['curr_url'] = $curr_url;
            } else {
                if ($_SESSION['curr_url'] != $url) {
                    $_SESSION['curr_url'] = $url;
                }
                if (strpos($url, "view")) {
                    $_SESSION['back_url'] = $url;
                }
            }
        }
        parent::beforeFilter();
    }

    public function get_prefix($static_id) {
        Configure::write('debug', 0);
        $this->autoRender = FALSE;
        $this->autoLayout = FALSE;
        $prefix_results = $this->Gatewaygroup->query("select digits from product_items where product_id = {$static_id}");
        echo json_encode($prefix_results);
    }

    /* 通过Resid查找
     * 
     */

    public function init__info_byResID($res_id) {
        $this->set('g', $this->Gatewaygroup->findResByres_id($res_id));
        $this->set('res_ip', $this->Gatewaygroup->findAllres_ip($res_id));
        $this->set('res_direct', $this->Gatewaygroup->findresdirectByRes_id($res_id));
        // $this->set('res_product',$this->Gatewaygroup->findresproductByRes_id($res_id));
        $this->set('user_codes', $this->Gatewaygroup->findUsecodecs($res_id));
        $this->set('nouser_codes', $this->Gatewaygroup->findNousecodecs($res_id));
    }
    
    public function show_prefixs($static_id) {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $sql = "SELECT digits FROM product_items WHERE product_id = {$static_id} ORDER by digits ASC";
        $prefixs = $this->Gatewaygroup->query($sql);
        $result = array();
        foreach($prefixs as $prefix)
            array_push ($result, $prefix[0]['digits']);
        echo json_encode($result);
    }
    
    public function add_prefix() {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $prefix = $_POST['prefix'];
        $static_id = $_POST['static_id'];
        $sql_p = "SELECT count(*) FROM product_items WHERE digits = '{$prefix}' and product_id = {$static_id}";
        $count_r = $this->Gatewaygroup->query($sql_p);
        if($count_r[0][0]['count'] > 0) {
            echo 0;
        } else {
            $sql = "INSERT INTO product_items(digits, strategy, product_id) VALUES('{$prefix}', 1, {$static_id})";
            $this->Gatewaygroup->query($sql);
            echo 1;
        }
    }

    /*
     * 删除网关
     */

    function del() {        
        if (!$_SESSION['role_menu']['Routing']['gatewaygroups:view']['model_w']) {
            $this->redirect_denied();
        }
        $result = $this->Resource->findByResourceId($this->params['pass'][0]);
        $alias = $result['Resource']['alias'];  
        $dynamic_count = $this->Gatewaygroup->query("SELECT COUNT(*) FROM dynamic_route_items WHERE resource_id = {$this->params ['pass'] [0]}");

        $static_count = $this->Gatewaygroup->query("SELECT COUNT(*) FROM product_items_resource WHERE resource_id = {$this->params ['pass'] [0]}");

        if ($dynamic_count[0][0]['count'] > 0 || $static_count[0][0]['count'] > 0) {
            $this->Gatewaygroup->create_json_array('', 101, __('Egress trunk is being used; therefore, it cannot be deleted.', true));
        } else {
            if ($this->Gatewaygroup->del($this->params ['pass'] [0])) {
                $this->Gatewaygroup->logging(1, 'Trunk', "Trunk Name:{$alias}");
                $this->Gatewaygroup->log('delete_getwaygroup');
                $this->Gatewaygroup->create_json_array('', 201, __('The Trunk ['.$alias.'] is deleted successfully', true));
            } else {
                $this->Gatewaygroup->create_json_array('', 101, __('Fail to delete Trunk', true));
            }
        }

        $this->Session->write('m', Gatewaygroup::set_validator());
        $type = $this->params ['pass'] [1];

        if (isset($_GET['viewtype'])) {
            $this->redirect("/prresource/gatewaygroups/{$type}?query[id_clients]={$_GET['query']['id_clients']}&viewtype=client");
        } else {
            $this->redirect(array('action' => $type));
        }
    }
    
    public function get_schema_egress()
    {
        $fields = array(
            'trunk_id' => 'resource_id',
            'trunk_name' => 'alias',
            'carrier_name' => '(SELECT name FROM client WHERE client_id = resource.client_id)',
            'media_type' => "(case media_type when 1 then 'proxy' when 2 then 'bypass' end)",
            'call_limit' => 'capacity',
            'cps_limit'  => "cps_limit",
            'protocol'   => "(case proto when 1 then 'sip' when 2 then 'h323' when 3 then 'all' end)",
            'pdd_timeout' => 'ring_timeout',
            'active' => "(case active when true then 'true' else 'false' end)",
            't38' => "(case t38 when true then 'enable' else 'disable' end)",
            'rate_table_name' => '(select name from rate_table where rate_table_id = resource.rate_table_id)',
            'host_route_strategy' => "(case res_strategy when 1 then 'top-down' else 'round-robin' end)",
            'rfc2833' => "(case rfc_2833 when true then 'true' else 'false' end)",
            'pass_dip_head' => "(case lnp_dipping when true then 'true' else 'false' end)",
            'min_duration' => 'delay_bye_second',
            'max_duration' => 'max_duration',
            'lrn_block' => "(case lrn_block when true then 'true' else 'false' end)",
        );
        
        return $fields;
    }

    function del_selected() {
        if (!$_SESSION['role_menu']['Routing']['gatewaygroups:view']['model_w']) {
            $this->redirect_denied();
        }
        $result = $this->Gatewaygroup->getAliasByids($_REQUEST ['ids']);
        $tip = '';
        foreach ($result as $alias){
            $tip.=$alias[0]['alias'].',';
        }
        $tip = '['.substr($tip,0,-1).']';
        if ($this->Gatewaygroup->del($_REQUEST ['ids'])) {
            $this->Gatewaygroup->log('delete_select_gatewaygroup');
            $this->Gatewaygroup->create_json_array('', 201, __('The Trunk '.$tip.' is deleted successfully.', true));
        } else {
            $this->Gatewaygroup->create_json_array('', 101, __('Fail to delete Trunk.', true));
        }

        $this->Session->write('m', Gatewaygroup::set_validator());
        $type = $_REQUEST ['type'];
        $this->redirect(array('action' => $type));
    }

    /**
     * 查询ip
     */
    public function ajax_ip() {

        Configure::write('debug', 2);
        $this->set('extensionBeans', $this->Gatewaygroup->findAllres_ip($this->params ['pass'] [0]));
    }

    /**
     * 落地网关
     */
    public function view_egress() {
        $this->pageTitle = "Routing/Egress Trunk";
        $this->init_info();
        $this->select_naem($this->_get('query.id_clients'));
        $this->set('p', $this->Gatewaygroup->findAll_egress(
                        $this->_order_condtions(
                                array('client_id', 'resource_id', 'alias', 'capacity', 'cps_limit', 'active', 'ip_cnt')
                        )
                ));
        if ($this->RequestHandler->isAjax()) {
            $this->layout = 'ajax';
            Configure::write('debug', 2);
            $this->render("view_egress_ajax");
        }
        
        $fields = $this->get_schema_egress();
        $keys = array_keys($fields);
    	$this->set('fields', $keys);
        $fields = $this->get_schema_host();
        $keys = array_keys($fields);
    	$this->set('host_fields', $keys);
        $fields = $this->get_schema_action();
        $keys = array_keys($fields);
    	$this->set('action_fields', $keys);
        
        $this->set('type', 11);
    }

    public function view_ingress() {
        $this->pageTitle = "Routing/Ingress Trunk";
        $this->init_info();
        $this->select_naem($this->_get('query.id_clients'));
        $this->set('p', $this->Gatewaygroup->findAll_ingress(
                        $this->_order_condtions(
                                array('client_id', 'resource_id', 'capacity', 'cps_limit', 'active', 'ip_cnt', 'profit_margin')
                        )
                ));
        $fields = $this->get_schema_ingress();
        $keys = array_keys($fields);
    	$this->set('fields', $keys);
        
        $fields = $this->get_schema_host();
        $keys = array_keys($fields);
    	$this->set('host_fields', $keys);
        
        $fields = $this->get_schema_action();
        $keys = array_keys($fields);
    	$this->set('action_fields', $keys);
        
        $fields = $this->get_schema_digit_mapping();
        $keys = array_keys($fields);
    	$this->set('digits_fields', $keys);
        
        $this->set('type', 12);
    }
    
    public function get_schema_action()
    {
        $fields = array(
            'trunk_name' => '(select alias from resource where resource_id = resource_direction.resource_id)',
            'time_profile_name' => '(select name from time_profile where time_profile_id = resource_direction.time_profile_id)',
            'target' => "(case type when 0 then 'ani' else 'dnis' end)",
            'code' => 'dnis',
            'action' => "(case action when 1 then 'add_prefix' when 2 then 'add_suffix' when 3 then 'del_prefix' when 4 then 'del_suffix' end)",
            'chars' => 'digits',
            'number_type' => "(case number_type when 0 then 'all' when 1 then '>' when 2 then '=' when 3 then '<' end)",
            'number_length' => 'number_length',
        );
        
        return $fields;
    }

    function dis_able() {
        if (!$_SESSION['role_menu']['Routing']['gatewaygroups:view']['model_w']) {
            $this->redirect_denied();
        }
        $id = $this->params ['pass'] [0];
        $page = $this->params ['pass'] [1];
        $this->Gatewaygroup->dis_able($id);
        $this->Gatewaygroup->log('disable_trunk');
        $this->redirect(array('plugin' => 'prresource', 'action' => $page));
    }

    function active() {
        if (!$_SESSION['role_menu']['Routing']['gatewaygroups:view']['model_w']) {
            $this->redirect_denied();
        }
        $id = $this->params ['pass'] [0];
        $page = $this->params ['pass'] [1];

        $this->Gatewaygroup->active($id);
        $this->Gatewaygroup->log('active_trunk');
        $this->redirect(array('plugin' => 'prresource', 'action' => $page));
    }

    /**
     * 
     * 
     * @param unknown_type $id
     */
    function delete($id) {
        if (!$_SESSION['role_menu']['Routing']['gatewaygroups:view']['model_w']) {
            $this->redirect_denied();
        }
        $sql = "SELECT alias FROM resource WHERE resource_id = {$id}";
        $data = $this->Gatewaygroup->query($sql);
        $this->Gatewaygroup->logging(1, 'Trunk', "Trunk Name:{$data[0][0]['alias']}");
        $this->Gatewaygroup->delete($id);
        $this->Gatewaygroup->log('delete_trunk');
        $this->Session->setFlash('');
        $this->redirect(array('action' => 'view'));
    }

    //电路使用报表---这个报表显示每一个落地网关的电路使用情况。
    function egress_report() {
        $this->loadModel('Resource');
        $this->Resource->recursive = 2;
        $lists = $this->Resource->findAll();
        $this->set('lists', $lists);
    }

    /**
     * 查询ip
     */
    public function ajax_host_report() {
        Configure::write('debug', 2);
        $res_id = $this->params ['pass'] [0];
        $this->set('extensionBeans', $this->Gatewaygroup->query("select  ip ,fqdn,port , use_cnt from resource_ip 
		left join (select count(* ) as use_cnt,  egress_id,callee_ip_address from real_cdr   group by egress_id  ,callee_ip_address )  a  
	 	on  a.egress_id=resource_id::text and a.callee_ip_address::text=resource_ip.ip::text
		where resource_id=$res_id   order  by use_cnt "));
    }

    /**
     * 
     * 添加 ingress
     * 
     * 
     */
    function add_resouce_ingress() {
        if (!$_SESSION['role_menu']['Routing']['gatewaygroups:view']['model_w']) {
            $this->redirect_denied();
        }
        $this->pageTitle = "Add Ingress Trunk";
        $is_did_enable = Configure::read('did.enable');
        $this->set('is_did_enable', $is_did_enable);
            $is_enable_type = Configure::read('system.enable_trunk_type');
            $this->set('is_enable_type', $is_enable_type);
        //post 请求
        if (!empty($this->data ['Gatewaygroup'])) {
            $ips = array_keys_value($this->params, 'form.accounts');

            $resource_id = $this->Gatewaygroup->saveOrUpdate($this->data, $_POST, $ips);
            $this->set('rout_list', $this->Gatewaygroup->find_route_strategy());
            $this->set("rate_table", $this->Gatewaygroup->find_rate_table());
            $this->set('resouce_list', $this->Gatewaygroup->find_resource());
            $this->set('ips', $ips);
            //添加fail
            if ($resource_id == 'fail') {
                $this->set('m', Gatewaygroup::set_validator()); //向界面设置验证信息
                $this->set('post', $this->data);
                $this->init_info();
            } else {
                //添加成功
                $this->set('resource_id', $resource_id);
                $this->set('resource_name', $this->data ['Gatewaygroup'] ['alias']);
                $this->set('gress', ingress);
                $this->Gatewaygroup->log('add_ingress_trunk');
                $this->Gatewaygroup->create_json_array('', 201, __('The Ingress Trunk[' . $this->data ['Gatewaygroup'] ['alias'] .'] is added successfully.', true));
                $this->Session->write("m", Gatewaygroup::set_validator());
                if ($this->_get('viewtype') == 'wizard') {
                    $client_id = $this->_get('query.id_clients');
                    $action = _filter_array(Array('egress' => 'add_resouce_egress', 'ingress' => 'add_resouce_ingress'), $this->_get('nextType'), 'add_resouce_egress');
                    if (Configure::read('project_name') == 'exchange') {
                        $this->xredirect("/gatewaygroups/$action?query[id_clients]=$client_id&viewtype=wizard");
                    } else {
                        $this->xredirect("/prresource/gatewaygroups/$action?query[id_clients]=$client_id&viewtype=wizard");
                    }
                } else {
                    $this->redirect("/prresource/gatewaygroups/edit_resouce_ingress/$resource_id/ingress");
                }
            }
        } else {
            $is_enable_type = Configure::read('system.enable_trunk_type');
            $this->set('is_enable_type', $is_enable_type);
            //get request
            $this->set('rout_list', $this->Gatewaygroup->find_route_strategy());
            $this->set("rate_table", $this->Gatewaygroup->find_rate_table());
            $this->set('resouce_list', $this->Gatewaygroup->find_resource());
            $this->set('transation_fees', $this->Gatewaygroup->find_transation_fee());
            $this->init_info();
        }
    }

    /**
     * 
     * 
     * 添加egress
     * 
     */
    function add_resouce_egress() {
        if (!$_SESSION['role_menu']['Routing']['gatewaygroups:view']['model_w']) {
            $this->redirect_denied();
        }
        $is_did_enable = Configure::read('did.enable');
        $this->set('is_did_enable', $is_did_enable);
            $is_enable_type = Configure::read('system.enable_trunk_type');
            $this->set('is_enable_type', $is_enable_type);
        if (!empty($this->data ['Gatewaygroup'])) {
            
            $resource_id = $this->Gatewaygroup->saveOrUpdate($this->data, $_POST, array_keys_value($this->params, 'form.accounts'));

            if ($resource_id == false || $resource_id == 'fail') {
                $this->set('m', Gatewaygroup::set_validator()); //向界面设置验证信息
                $this->set('post', $this->data);
                $this->init_info();
            } else {
                $this->Gatewaygroup->log('add_egress_trunk');
                if ($this->_get('viewtype') == 'wizard') {
                    $this->Gatewaygroup->create_json_array('', 201, __('The ' . $this->data['Gatewaygroup']['alias'] . ' is added successfully.', true));
                    $client_id = $this->_get('query.id_clients');
                    $action = _filter_array(Array('egress' => 'add_resouce_egress', 'ingress' => 'add_resouce_ingress'), $this->_get('nextType'), 'add_resouce_egress');
                    if (Configure::read('project_name') == 'exchange') {
                        $this->redirect("/gatewaygroups/$action?query[id_clients]=$client_id&viewtype=wizard");
                    } else {
                        $this->redirect("/prresource/gatewaygroups/$action?query[id_clients]=$client_id&viewtype=wizard");
                    }
                } else {
                    $this->Gatewaygroup->create_json_array('', 201, __('The ' . $this->data['Gatewaygroup']['alias'] . ' is added successfully.', true));
                    $this->Session->write("m", Gatewaygroup::set_validator());
                    $this->redirect("/prresource/gatewaygroups/edit_resouce_egress/$resource_id/");
                }
            }
        } else {
            $this->init_info();
            $this->set("routing_rules", $this->Gatewaygroup->getBillingRules());
        }
    }

    /**
     * 
     * 
     * 修改ingress
     * @param unknown_type $res_id
     */
    function edit_resouce_ingress($res_id=null) {
    	
    	$is_did_enable = Configure::read('did.enable');
    	$this->set('is_did_enable', $is_did_enable);
            $is_enable_type = Configure::read('system.enable_trunk_type');
            $this->set('is_enable_type', $is_enable_type);
        if (!empty($this->data ['Gatewaygroup'])) {
            if (!$_SESSION['role_menu']['Routing']['gatewaygroups:view']['model_w']) {
                $this->redirect_denied();
            }
            $resource_id = $this->Gatewaygroup->saveOrUpdate($this->data, $_POST, array_keys_value($this->params, 'form.accounts'));
            if ($resource_id == 'fail') {
                $this->set('m', Gatewaygroup::set_validator()); //向界面设置验证信息

                $resource_id = $_POST['data']['Gatewaygroup']['resource_id'];
                $this->set('resource_id', $resource_id);
                $this->set('resource_name', $this->data['Gatewaygroup']['alias']);
                $this->data ['Gatewaygroup'] ['resource_id'] = $resource_id;
                $this->set('post', $this->data);
                $this->set('rout_list', $this->Gatewaygroup->find_route_strategy());
                $this->set("rate_table", $this->Gatewaygroup->find_rate_table());
                $this->set('resouce_list', $this->Gatewaygroup->find_resource());
                $this->set('resouce_prefix_list', $this->Gatewaygroup->find_resouce_prefix($resource_id));


                $this->init_codes($resource_id);
                $this->init_info();
                $this->Session->write("m", Gatewaygroup::set_validator());
                $this->redirect("/prresource/gatewaygroups/view_ingress");
                //$this->redirect("/prresource/gatewaygroups/edit_resouce_ingress/{$resource_id}/ingress");
            } else {
                $this->set('resource_id', $resource_id);
                $this->set('resource_name', $this->data['Gatewaygroup']['alias']);
                $this->Gatewaygroup->log('edit_ingress_trunk');
                $this->Gatewaygroup->create_json_array('#ClientOrigRateTableId', 201, 'The Ingress Trunk ['.$this->data['Gatewaygroup']['alias'].'] is modified successfully!');
                $this->Session->write("m", Gatewaygroup::set_validator());
                $this->data ['Gatewaygroup'] ['resource_id'] = $resource_id;
                /* $ignore_ring_early_media = $this->data['Gatewaygroup']['ignore_ring'] == 't' ? 
                  ($this->data['Gatewaygroup']['ignore_early_media'] == 't' ? 1 : 2 ) : ($this->data['Gatewaygroup']['ignore_early_media'] == 't' ? 3 : 0);
                  $this->data['Gatewaygroup']['ignore_ring_early_media'] = $ignore_ring_early_media; */
                $this->set('post', $this->data);

                $this->set('rout_list', $this->Gatewaygroup->find_route_strategy());
                $this->set("rate_table", $this->Gatewaygroup->find_rate_table());
                $this->set('resouce_list', $this->Gatewaygroup->find_resource());
                $this->set('resouce_prefix_list', $this->Gatewaygroup->find_resouce_prefix($resource_id));
                

                $this->init_codes($resource_id);
                $this->init_info();
                $this->redirect("/prresource/gatewaygroups/edit_resouce_ingress/{$resource_id}/ingress");
            }
        } else {
            //get  request
            if (!empty($this->params ['pass'][0])) {
                $resource_id = array_keys_value($this->params, 'pass.0');
            } else {
                $this->redirect("/homes/bad_url/");
            }
            $this->init_info();
            $this->init_codes($resource_id);
            $this->Gatewaygroup->resource_id = $resource_id;
            $tmp1 = $this->Gatewaygroup->findResByres_id($resource_id);
            $data ['Gatewaygroup'] = $tmp1 [0] [0];
            $ignore_ring_early_media = $tmp1 [0] [0]['ignore_ring'] == 't' ?
                    ($tmp1 [0] [0]['ignore_early_media'] == 't' ? 1 : 2 ) : ($tmp1 [0] [0]['ignore_early_media'] == 't' ? 3 : 0);
            $data['Gatewaygroup']['ignore_ring_early_media'] = $ignore_ring_early_media;
            $this->set('post', $data);
            $this->set('rout_list', $this->Gatewaygroup->find_route_strategy());
            $this->set("rate_table", $this->Gatewaygroup->find_rate_table());
            $this->set('resouce_list', $this->Gatewaygroup->find_resource());
            $this->set('transation_fees', $this->Gatewaygroup->find_transation_fee());
            $this->set('resouce_prefix_list', $this->Gatewaygroup->find_resouce_prefix($res_id));
        }
        $hosts = $this->Gatewaygroup->query("select resource_id,resource_ip_id,ip,port,fqdn,username,password,need_register from  resource_ip where resource_id=$resource_id order by resource_ip_id asc");
        $this->set('hosts', $hosts);
    }

    function edit_resouce_egress() {
        $is_did_enable = Configure::read('did.enable');
        $this->set('is_did_enable', $is_did_enable);
        if (!empty($this->data ['Gatewaygroup'])) {
            if (!$_SESSION['role_menu']['Routing']['gatewaygroups:view']['model_w']) {
                $this->redirect_denied();
            }
            $resource_id = $this->Gatewaygroup->saveOrUpdate($this->data, $_POST, array_keys_value($this->params, 'form.accounts'));
            if ($resource_id == 'fail') {
                $this->set('m', Gatewaygroup::set_validator()); //向界面设置验证信息
                $this->set('post', $this->data);
                $this->init_info();
                $this->Session->write("m", Gatewaygroup::set_validator());
                $this->redirect("/prresource/gatewaygroups/view_egress");
            } else {
                $this->set('resource_id', $resource_id);
                $this->set('resource_name', $this->data ['Gatewaygroup'] ['alias']);
                $this->data ['Gatewaygroup'] ['resource_id'] = $resource_id;
                $this->set('post', $this->data);
                $this->init_info();
                $this->Gatewaygroup->log('edit_egress_trunk');
                $this->Gatewaygroup->create_json_array('#ClientOrigRateTableId', '201', 'The Egress Trunk ['.$this->data ['Gatewaygroup'] ['alias'].'] is modified successfully !');
                $this->Session->write("m", Gatewaygroup::set_validator());
                $this->redirect("/prresource/gatewaygroups/edit_resouce_egress/{$resource_id}/");
            }
        } else {
            $resource_id = $this->get_param_pass(0);
            $this->init_info();
            $this->init_codes($resource_id);
            $this->set("routing_rules", $this->Gatewaygroup->getBillingRules());
            
            /*
            $alreay_dynamic = $this->Gatewaygroup->query("select dynamic_route_id from dynamic_route_items where resource_id = {$resource_id}");

            $alreay_static = $this->Gatewaygroup->query("select product_id,digits from 
     product_items join product_items_resource on product_items.item_id = product_items_resource.item_id
where product_items_resource.resource_id = {$resource_id}");
           
            $relation_routes = array();
            foreach($alreay_dynamic as $al_dy) {
                array_push($relation_routes, array(
                    'type'    =>   0,
                    'dynamic' =>   $al_dy[0]['dynamic_route_id'],
                    'static'  => '',
                    'prefix'  => '',
                ));
            } 
            
            $statics = array();
            
            foreach($alreay_static as $al_st) {
                
                if(array_key_exists($al_st[0]['product_id'], $statics)) {
                    $statics[$al_st[0]['product_id']] .= "," .$al_st[0]['digits'];
                } else {
                    $statics[$al_st[0]['product_id']] = $al_st[0]['digits'];
                }
                
            } 
            
            foreach($statics as $key => $al_st) {
                array_push($relation_routes, array(
                    'type'    =>   1,
                    'dynamic' =>   '',
                    'static'  => $key,
                    'prefix'  => $al_st,
                ));
            }
            $this->set('relation_routes', $relation_routes);
            */
            $profiles = $this->Gatewaygroup->get_profiles($resource_id);
            $this->set('sip_profiles', $profiles);
            $this->Gatewaygroup->resource_id = $resource_id;
            $tmp1 = $this->Gatewaygroup->findResByres_id($resource_id);
            $data ['Gatewaygroup'] = $tmp1 [0] [0];
            $this->set('post', $data);
        }
        $hosts = $this->Gatewaygroup->query("select resource_id,resource_ip_id,ip,port,fqdn,username,password,need_register from  resource_ip where resource_id=$resource_id order by resource_ip_id asc");
        $this->set('hosts', $hosts);
        $this->init_codes($resource_id);
    }

    function check_route_plan_error($resource_id, $route_plan_id) {
        #  find current  route plan prefix
        $is_repeat = false;
        //	$route_plan_id=147;
        $current_prefix_arr = array();
        $list = $this->Gatewaygroup->query(" select  digits from  route   where  route_strategy_id=$route_plan_id");
        if (isset($list[0][0]['digits']) && !empty($list[0][0]['digits'])) {
            foreach ($list as $key => $value) {
                $current_prefix_arr[$key] = $value[0]['digits'];
            }
        }



        #查找相同ip地址的ingress resource  route_plan 的前缀   
        $ip_prefix_arr = array();
        //$resource_id=731;
        $list = $this->Gatewaygroup->query("
				select digits from route where route_strategy_id in ( 
				select  distinct  route_strategy_id from resource_ip 
				left join resource on resource.resource_id=resource_ip.resource_id 
				where ip  in (select distinct ip from resource_ip where resource_id=$resource_id) and  resource_ip.resource_id<>$resource_id
				and  route_strategy_id is not null
)
				  
				  ");
        if (isset($list[0][0]['digits']) && !empty($list[0][0]['digits'])) {
            foreach ($list as $key => $value) {
                $ip_prefix_arr[$key] = $value[0]['digits'];
            }
        }
        //	pr($ip_prefix_arr);
        #这里比较2个prefix数组
        foreach ($current_prefix_arr as $key => $value) {
            $prefix = $value;
            foreach ($ip_prefix_arr as $k2 => $v2) {
                $p2 = $v2;
                $list = $this->Gatewaygroup->query("select  '$prefix'::prefix_range		<@ '$p2'  as  t ;");
                if ($list[0][0]['t']) {
                    $is_repeat = true;
                    break;
                }
            }
        }

        return $is_repeat;
    }

    function add_host() {
        if (!$_SESSION['role_menu']['Routing']['gatewaygroups:view']['model_w']) {
            //$this->redirect_denied();
        }
        $this->init_info();
        $resource_id = $_SESSION ['resource_id'];
        $list = $this->Gatewaygroup->query("select resource_id,resource_ip_id,ip,port,fqdn,username,password,need_register from  resource_ip where resource_id=$resource_id order by resource_ip_id asc");
        $this->set("resource_id", $resource_id);
        $this->set("host", $list);
        $list = $this->Gatewaygroup->query("select alias,route_strategy_id from  resource where resource_id=$resource_id");
        $this->set('name', $list[0][0]['alias']);
        if (empty($_SESSION ['route_plan_id'])) {
            if (isset($list[0][0]['alias'])) {
                $_SESSION ['route_plan_id'] = $list[0][0]['route_strategy_id'];
            } else {
                $_SESSION ['route_plan_id'] = '';
            }
        }
        $_SESSION ['resource_name'] = $list[0][0]['alias'];
    }

    function add_rule($type) {
        if (!$_SESSION['role_menu']['Routing']['gatewaygroups:view']['model_w']) {
            //$this->redirect_denied();
        }
        $resource_id = $this->get_param_pass(0);
        $type = $this->get_param_pass(1);
        $this->set_name_gress($resource_id);
        $list = $this->Gatewaygroup->query("select * from  resource_next_route_rule where resource_id=$resource_id    order by id");
        $this->set("resource_id", $resource_id);
        $this->set("host", $list);
        $this->set("type", $type);
    }

    function add_direction($resource_id=null) {
        if (!$_SESSION['role_menu']['Routing']['gatewaygroups:view']['model_w']) {
            //$this->redirect_denied();
        }
        $resource = $this->Gatewaygroup->findByResourceId($resource_id);
        $title = $resource['Gatewaygroup']['ingress'] == 1 ? 'Ingress' : 'Egress';
        $list = $this->Gatewaygroup->query("select  direction_id,  time_profile_id,type,dnis,action,digits,number_length,number_type  from  resource_direction  where resource_id='$resource_id' order by direction_id");
        $this->set_name_gress($resource_id);
        $this->set("resource_id", $resource_id);
        $this->set("host", $list);
        $this->set('timepro', $this->Gatewaygroup->find_timeprofile());
        $this->set('smaill_title', $title);
    }

    function add_lrn_action($resource_id=null) {
        if (!$_SESSION['role_menu']['Routing']['gatewaygroups:view']['model_w']) {
            //$this->redirect_denied();
        }
        $resource_id = $this->get_param_pass(0);
        $this->set_name_gress($resource_id);
        $list = $this->Gatewaygroup->query("select  id,  direction,dnis,action,digits  from  resource_lrn_action  where resource_id='$resource_id' order by id");

        $this->set("resource_id", $resource_id);
        $this->set("host", $list);
    }

    public function add_rule_post() {
        if (!$_SESSION['role_menu']['Routing']['gatewaygroups:view']['model_w']) {
            //$this->redirect_denied();
        }
        $res_id = $_POST ['resource_id'];
        $delete_rate_id = $_POST['delete_rate_id'];
        $addtype = $_POST['addtype'];
        $delete_rate_id = substr($delete_rate_id, 1);
        $tmp = (isset($_POST ['accounts'])) ? $_POST ['accounts'] : '';
        $size = count($tmp);
        //pr($tmp);
        if (!empty($tmp)) {
            foreach ($tmp as $el) {
                $model = new ResourceNextRouteRule;
                $this->data['ResourceNextRouteRule'] = $el;
                $this->data['ResourceNextRouteRule']['resource_id'] = $res_id;
                $model->save($this->data ['ResourceNextRouteRule']);
                $this->data['ResourceNextRouteRule']['id'] = false;
            }
        }
        if (!empty($delete_rate_id)) {
            $this->ResourceNextRouteRule->query("delete  from  resource_next_route_rule where id in($delete_rate_id)");
        }
        $this->Gatewaygroup->log('add_rule_report');
        $this->ResourceNextRouteRule->create_json_array('#ClientOrigRateTableId', 201, 'Succeeded');
        $this->Session->write("m", ResourceNextRouteRule::set_validator());
        $this->redirect("/prresource/gatewaygroups/add_rule/{$res_id}/{$addtype}");
    }

    function add_lrn_action_post() {
        if (!$_SESSION['role_menu']['Routing']['gatewaygroups:view']['model_w']) {
            $this->redirect_denied();
        }
        $direction = ($_POST['gress'] == 'ingress') ? '1' : '2';
        $res_id = $_POST ['resource_id'];

        if (isset($_POST['resource_id'])) {
            $tmp = (isset($_POST ['accounts'])) ? $_POST ['accounts'] : '';
            $arr = array();
            foreach ($tmp as $val) {
                array_push($arr, $val['digits'] + $val['dnis'] + $val['direction'] + $val['action']);
            }
            $len1 = count($arr);

            $arr2 = array_unique($arr);


            $len2 = count($arr2);

            if ($len1 > $len2) {
                $this->Gatewaygroup->create_json_array('#ClientOrigRateTableId', 301, 'Succeeded');
                $this->Session->write("m", Gatewaygroup::set_validator());
                $this->redirect("/prresource/gatewaygroups/add_lrn_action/{$res_id}");
            }

            $size = count($tmp);
            $this->Gatewaygroup->begin();
            $this->Gatewaygroup->query("delete from  resource_lrn_action  where resource_id=$res_id");
            foreach ($tmp as $el) {
                $direction = $el ['direction'];
                $action = $el ['action'];
                $digits = $el ['digits'];
                $dnis = $el ['dnis'];
                $this->Gatewaygroup->query("insert into resource_lrn_action (direction,resource_id,dnis,action,digits)  
					  values($direction,$res_id,'$dnis'::prefix_range,$action,'$digits')");
            }
            $this->Gatewaygroup->commit();
            $this->Gatewaygroup->log('add_lrn_action');
            $this->Gatewaygroup->create_json_array('#ClientOrigRateTableId', 201, 'Succeeded');
            $this->Session->write("m", Gatewaygroup::set_validator());
        }
        $this->redirect("/prresource/gatewaygroups/add_lrn_action/{$res_id}");
    }
    
    
    
    
     function del_lrn_action_post($resource_id=null,$id=null) {
         
        if (!$_SESSION['role_menu']['Routing']['gatewaygroups:view']['model_w']) {
            $this->redirect_denied();
        }
        
        if(!empty($resource_id) && !empty($id)){
            $this->Gatewaygroup->query("delete from  resource_lrn_action  where resource_id=" . $resource_id . " and id=". $id);
        }
       
        $this->redirect("/prresource/gatewaygroups/add_lrn_action/{$resource_id}");
    }
    
    
    
    

    function add_direction_post() {
        if (!$_SESSION['role_menu']['Routing']['gatewaygroups:view']['model_w']) {
            $this->redirect_denied();
        }
        $res_id = $_POST ['resource_id'];
        if (isset($_POST['resource_id'])) {
            $list_gress = $this->Gatewaygroup->query("select egress,ingress  from  resource  where resource_id=$res_id");
            if (!empty($list_gress[0][0]['egress'])) {
                $direction = '2';
            } else {
                $direction = '1';
            }

            $tmp = (isset($_POST ['accounts'])) ? $_POST ['accounts'] : '';
            /*
            $size = count($tmp);
            $arr = array();
            if ($tmp) {
                foreach ($tmp as $value) {
                    array_push($arr, $value['time_profile_id'] . $value['type'] . $value['dnis'] . $value['action'] . $value['digits'] . $value['num_type']);
                }
            }
            $len1 = count($arr);
            $arr2 = array_unique($arr);
            $len2 = count($arr2);
            if ($len1 > $len2) {
                $this->Gatewaygroup->create_json_array('#ClientOrigRateTableId', 301, 'Actions cannot be duplicated.! ');
                $this->Session->write("m", Gatewaygroup::set_validator());
                $this->redirect("/prresource/gatewaygroups/add_direction/{$res_id}/");
            }
             * 
             */
            $this->Gatewaygroup->begin();
            $this->Gatewaygroup->query("delete from  resource_direction  where resource_id=$res_id");
            if ($tmp) {
                foreach ($tmp as $el) {
                    $time_profile_id = $el ['time_profile_id'];
                    $type = $el ['type'];
                    $dnis = $el ['dnis'];
                    $action = $el ['action'];
                    $digits = $el ['digits'];
                    if ($action == 3 || $action == 4) {
                        $digits = $el['deldigits'];
                    }
                    $number_type = $el ['number_type'];
                    if (empty($time_profile_id)) {
                        $time_profile_id = 'null';
                    }
                    if ($number_type == '0') {
                        $this->Gatewaygroup->query("insert into resource_direction (direction,resource_id,time_profile_id,type,dnis,action,digits,number_type,number_length)  
						  values($direction,$res_id,$time_profile_id,$type,'$dnis'::prefix_range,$action,'$digits',0,NULL)");
                    } else {
                        $number_length = $el ['number_length'];
                        $this->Gatewaygroup->query("insert into resource_direction (direction,resource_id,time_profile_id,type,dnis,action,digits,number_type,number_length)  
						  values($direction,$res_id,$time_profile_id,$type,'$dnis'::prefix_range,$action,'$digits',$number_type,$number_length)");
                    }
                }
            }
            $this->Gatewaygroup->commit();
            $this->Gatewaygroup->log('add_direct_action');
            $this->Gatewaygroup->create_json_array('#ClientOrigRateTableId', 201, 'Succeeded');
            $this->Session->write("m", Gatewaygroup::set_validator());
        }
        $this->redirect("/prresource/gatewaygroups/add_direction/{$res_id}/");
    }

    /**
     * 
     * 
     * post request
     */
    function add_host_post() {
        if (!$_SESSION['role_menu']['Routing']['gatewaygroups:view']['model_w']) {
            $this->redirect_denied();
        }
        if (isset($_POST ['resource_id'])) {
            $res_id = $_POST ['resource_id'];
            $this->Gatewaygroup->begin();
            $this->Gatewaygroup->query("delete from  resource_ip_limit  where ip_id in (select resource_ip_id  from  resource_ip  where resource_id=$res_id)");
            $this->Gatewaygroup->query("delete from  resource_ip  where resource_id=$res_id");
            $tmp = isset($_POST ['accounts']) ? $_POST ['accounts'] : '';
            $checkip = true;
            if ($checkip === true) {
                $size = count($tmp);
                $i = 0;
                foreach ($tmp as $el) {
                    $ip = $el ['ip'];
                    $netmask = $el ['netmask'];
                    $port = $el ['port'];
                    /* $ip1 = "'" . $ip . "/" . $netmask . "'" . "::ip4r"; //普通ip
                      $ip2 = "'" . $ip . "/" . $netmask . "'"; //域名
                      $list=$this->Gatewaygroup->query ( " select  {$ip1}" );
                      if(empty($list)){
                      $this->Gatewaygroup->create_json_array ( '#ip-ip-'.($i+1), 101, 'Please fill IP field correctly (only IP allowed).' );
                      $this->Session->write ( "m", Gatewaygroup::set_validator () );
                      $this->redirect ( "/prresource/gatewaygroups/add_host/$res_id?gress={$_GET['gress']}&res_id={$res_id}" );
                      } */
                    $ip1 = null;
                    $ip2 = null;
                    $check = null;
                    if ($this->checkipaddres($ip)) {
                        $ip2 = $ip1 = "'" . $ip . "'";
                    } else {
                        $ip2 = "'" . $ip . "'";
                        $ip1 = 'null';
                    }

                    $check = $this->Gatewaygroup->query("select COUNT(*) as count_num  from resource_ip where resource_id = $res_id  and (fqdn=$ip2 OR IP =$ip1) and port=$port ");
                    if ($check[0][0]['count_num'] != 0) {//重复则返回
                        $this->Gatewaygroup->create_json_array('', 101, __('IP repeat', true));
                        $this->redirect("/prresource/gatewaygroups/add_host/$res_id?gress={$_GET['gress']}&resource_id={$res_id}");
                    }

                    $r = $this->Gatewaygroup->query("insert into resource_ip (ip,resource_id,fqdn,port)  
						  values($ip1,$res_id,$ip2,$port)");
                    if (!is_array($r)) {
                        $this->Gatewaygroup->rollback();
                        $this->Gatewaygroup->create_json_array('#ip-ip-' . ($i + 1), 201, 'Please fill IP field correctly (only IP allowed).');
                        $this->redirect("/prresource/gatewaygroups/add_host/$res_id?gress={$_GET['gress']}&resource_id={$res_id}");
                    }
                    $i++;
                }
                $this->Gatewaygroup->commit();

                #update  route plan

                if ($_GET['gress'] == 'ingress') {
                    $route_plan_id = $_POST['route_plan_id'];
                    $this->Session->write("route_plan_id", $route_plan_id);
                    if ($this->check_route_plan_error($res_id, $route_plan_id)) {
                        $this->Gatewaygroup->create_json_array('#route_plan_id', 101, "The same ip address ingress resource can not choose the same or overlapping prefix of the route plan.");
                        $this->Session->write("m", Gatewaygroup::set_validator());
                        $this->redirect("/prresource/gatewaygroups/add_host/$res_id?gress={$_GET['gress']}&resource_id={$res_id}");
                    } else {

                        $this->Gatewaygroup->query("update  resource  set route_strategy_id=$route_plan_id  where  resource_id=$res_id");
                    }
                }
                $this->Gatewaygroup->log('add_trunk_host');
                $this->Gatewaygroup->create_json_array('#ClientOrigRateTableId', 201, 'Add Success');
                $this->redirect("/prresource/gatewaygroups/add_host/$res_id?gress={$_GET['gress']}&resource_id={$res_id}");
            } else {
                $this->Gatewaygroup->create_json_array('', 101, "IP:" . $checkip . "Already Exists");
            }
            $this->Session->write("m", Gatewaygroup::set_validator());
        }

        $this->redirect("/gatewaygroups/add_host/$res_id?gress={$_GET['gress']}&resource_id={$res_id}");
    }

    public function legalIP($res_id, $ips) {
        $old_ip = $this->Gatewaygroup->query("select * from resource_ip where resource_id = $res_id");
        $repeat_array = "";
        foreach ($ips as $el) {
            $_not_exists = true;
            for ($i = 0; $i < count($old_ip); $i++) {
                if ($old_ip[$i][0]['ip'] == $el['ip']) {
                    $_not_exists = false;
                }
            }
            if (!isset($el ['need_register'])) {
                if ($_not_exists) {
                    $qs = $this->Gatewaygroup->query("select resource_ip_id from resource_ip where ip = '{$el['ip']}'");
                    if (count($qs) > 0) {
                        $repeat_array = str_ireplace($el['ip'], '', $repeat_array);
                        $repeat_array .= $el['ip'] . "&nbsp;&nbsp;&nbsp;&nbsp;";
                    }
                }
            }
        }
        return!empty($repeat_array) ? $repeat_array : true;
    }

    function add_host_time() {

        if (!empty($this->params ['pass'])) {
            $res_ip_id = $this->params ['pass'] [0];
            $ip = $this->params ['pass'] [1];
            $_SESSION ['resource_ip'] = $ip;
            $_SESSION ['resource_ip_id'] = $res_ip_id;
        } else {
            $res_ip_id = $_SESSION ['resource_ip_id'];
            $ip = $_SESSION ['resource_ip'];
        }
        $list = $this->Gatewaygroup->query("select  limit_id,ip_id,cps,capacity ,time_profile_id   from   resource_ip_limit   where ip_id=$res_ip_id    order by limit_id");
        $this->set("resource_ip_id", $res_ip_id);
        $this->set("host", $list);
        $this->set('timepro', $this->Gatewaygroup->find_timeprofile());
    }

    /**
     * 
     * 对接网关的主被叫转换规则 配置
     */
    function set_name_gress($resource_id) {

        $list = $this->Gatewaygroup->query("select ingress , egress, alias as name, (select name FROM client WHERE client_id = resource.client_id) as client_name from resource where resource_id=$resource_id");
        $this->set('name', $list[0][0]['name']);
        $this->set('client_name', $list[0][0]['client_name']);
        if ($list[0][0]['ingress']) {
            $this->set('gress', 'ingress');
        } else {
            $this->set('gress', 'egress');
        }
    }

    function add_translation_time($id=null) {

        $res_id = $this->get_param_pass(0);
        $this->set_name_gress($res_id);
        $list = $this->Gatewaygroup->query("select  ref_id,resource_id,translation_id,time_profile_id    from   resource_translation_ref   where resource_id=$res_id    order by ref_id");
        $this->set("resource_id", $res_id);
        $this->set("host", $list);
        $this->set('timepro', $this->Gatewaygroup->find_timeprofile());
        $this->set('r', $this->Gatewaygroup->findDigitMapping());
    }

    function view_did() {
        if (!empty($this->params ['pass'])) {
            $resource_id = $this->params ['pass'] [0];
            $_SESSION ['resource_id'] = $resource_id;
            $_SESSION ['gress'] = 'egress';
        } else {
            $resource_id = $_SESSION ['resource_id'];
        }

        $list = $this->Gatewaygroup->query("select  card_id,(select card_number from card where card_id = card_code_part.card_id) as account,id,did,card_sip_id ,sip_code,resource_id ,active  from   card_code_part where resource_id = $resource_id      order by id");
        $this->set("host", $list);
        $this->set('timepro', $this->Gatewaygroup->find_sipcode());
        $this->set('resource_id', $resource_id);
    }

    function active_did() {
        if (!$_SESSION['role_menu']['Routing']['gatewaygroups:view']['model_w']) {
            $this->redirect_denied();
        }
        $id = $this->params ['pass'] [0];
        $t = $this->params ['pass'] [1];
        $this->Gatewaygroup->query("update card_code_part set active=$t  where id=$id");
        if ($t == 'false') {
            $str = "DID已经被禁用";
        } else {
            $str = "DID已经被启动";
        }
        $this->Gatewaygroup->create_json_array('#ClientOrigRateTableId', 201, $str);
        $this->Session->write("m", Gatewaygroup::set_validator());
        $this->redirect("/gatewaygroups/view_did/");
    }

    /**
     * 
     * 
     * class4配置
     */
    function add_server() {

        $list = $this->Gatewaygroup->query("select server_id,server_type,ip,enable_register from   server_platform    order by server_id");
        $this->set("host", $list);
    }

    function add_server_post() {
        if (!$_SESSION['role_menu']['Routing']['gatewaygroups:view']['model_w']) {
            $this->redirect_denied();
        }
        if (isset($_POST ['is_post'])) {
            $tmp = $_POST ['accounts'];
            //pr($tmp);
            $size = count($tmp);
            $this->Gatewaygroup->query("delete from  server_platform ");
            foreach ($tmp as $el) {
                $ip = $el ['ip'];
                $ip1 = "'" . $ip . "'::ip4r"; //普通ip
                $this->Gatewaygroup->query("insert into server_platform (ip)  
					  values('$ip')");
            }
            $this->Gatewaygroup->log('add_server_platform');
            $this->Gatewaygroup->create_json_array('#ClientOrigRateTableId', 201, 'Add success');
            $this->Session->write("m", Gatewaygroup::set_validator());
        }

        $this->redirect("/gatewaygroups/add_server/");
    }

    function add_host_time_post() {
        if (!$_SESSION['role_menu']['Routing']['gatewaygroups:view']['model_w']) {
            $this->redirect_denied();
        }
        if (isset($_POST ['resource_ip_id'])) {
            $res_ip = $_POST ['resource_ip_id'];

            $tmp = $_POST ['accounts'];
            $size = count($tmp);
            $this->Gatewaygroup->query("delete from  resource_ip_limit  where ip_id=$res_ip");
            foreach ($tmp as $el) {
                $time_profile_id = $el ['time_profile_id'];
                $cps = !empty($el ['cps']) ? $el ['cps'] : 'null';
                $capacity = !empty($el ['capacity']) ? $el ['capacity'] : 'null';

                $this->Gatewaygroup->query("insert into resource_ip_limit (ip_id,cps,capacity,time_profile_id)  
					  values($res_ip,$cps,$capacity,$time_profile_id)");
            }
            $this->Gatewaygroup->log('add_resource_ip_limit');
            $this->Gatewaygroup->create_json_array('#ClientOrigRateTableId', 201, 'Action  Success');
            $this->Session->write("m", Gatewaygroup::set_validator());
        }

        $this->redirect("/prresource/gatewaygroups/add_host_time/");
    }

    /**
     * 
     * 配置 对接网关的主被叫转换规则 
     * 
     */
    function check_trans_time_profile($time_profile_id) {
        $list = $this->Gatewaygroup->query("select count(*)as c  from resource_translation_ref where time_profile_id=$time_profile_id");
        if (empty($list) || $list[0][0]['c'] == 0) {
            return 'true';
        } else {
            return 'false';
        }
    }

    function add_translation_time_post() {
        if (!$_SESSION['role_menu']['Routing']['gatewaygroups:view']['model_w']) {
            $this->redirect_denied();
        }
        if (isset($_POST ['resource_id'])) {
            $res_id = $_POST ['resource_id'];
            $tmp = $_POST ['accounts'];
            $size = count($tmp);
            $this->Gatewaygroup->query("delete from  resource_translation_ref  where resource_id=$res_id");
            foreach ($tmp as $el) {
                $time_profile_id = $el ['time_profile_id'];
                $translation_id = $el ['translation_id'];
                /* 				if($this->check_trans_time_profile($time_profile_id)=='false'){
                  $this->Gatewaygroup->create_json_array ( '#ClientOrigRateTableId', 301, 'time profile is  exisit' );
                  $this->Session->write ( "m", Gatewaygroup::set_validator () );
                  $this->redirect ( "/prresource/gatewaygroups/add_translation_time?gress={$_POST ['gress']}" );

                  } */
                #
                if (empty($time_profile_id)) {
                    $time_profile_id = 'null';
                }
                $this->Gatewaygroup->query("insert into resource_translation_ref (resource_id,translation_id,time_profile_id)  
					  values($res_id,$translation_id,$time_profile_id)");
            }
            $this->Gatewaygroup->log('add_resource_translation_ref');
            $this->Gatewaygroup->create_json_array('#ClientOrigRateTableId', 201, 'Ddigit Mapping,Action Successfully !');
            $this->Session->write("m", Gatewaygroup::set_validator());
        }

        $this->redirect("/prresource/gatewaygroups/add_translation_time/{$res_id}/");
    }

    function view_did_post() {
        if (isset($_POST ['resource_id'])) {
            $resource_id = $_POST ['resource_id'];

            $tmp = $_POST ['accounts'];
            $size = count($tmp);
            $this->Gatewaygroup->query("delete from  card_code_part  where resource_id=$resource_id");
            foreach ($tmp as $el) {
                $card_sip_id = $el ['card_sip_id'];
                $did = $el ['code'];

                $list = $this->Gatewaygroup->query("select card_id from  card  where card_number='{$el['card_id']}'");
                $card_id = $list [0] [0] ['card_id'];
                $sip_code = $this->Gatewaygroup->query("select sip_code from card_sip where card_sip_id = $card_sip_id");

                $this->Gatewaygroup->query("insert into card_code_part (resource_id,card_sip_id,did,card_id,sip_code)  
					  values($resource_id,$card_sip_id,'$did',$card_id,'{$sip_code[0][0]['sip_code']}')");
            }

            $this->Gatewaygroup->create_json_array('#ClientOrigRateTableId', 201, 'Action  Success');
            $this->Session->write("m", Gatewaygroup::set_validator());
        }

        $this->redirect("/gatewaygroups/view_did/");
    }

    public function edit_ip() {
        if (!$_SESSION['role_menu']['Routing']['gatewaygroups:view']['model_w']) {
            $this->redirect_denied();
        }
        Configure::write('debug', 2);
        $username = $_REQUEST ['user_name'];
        $password = $_REQUEST ['pass'];
        $id = $_REQUEST ['id'];
        $qs = $this->Gatewaygroup->query("update resource_ip set username = '$username',password='$password' where resource_ip_id = $id");
        if (count($qs) == 0) {
            $this->Gatewaygroup->log('update_resource_ip');
            $this->Gatewaygroup->create_json_array('', 201, __('manipulated_suc', true));
        } else {
            $this->Gatewaygroup->create_json_array('', 101, __('manipulated_fail', true));
        }
        $this->Session->write('m', Gatewaygroup::set_validator());
    }

    /**
     * 根据帐户号码查询SIP
     */
    public function get_sip_by_card($card_number) {
        Configure::write('debug', 2);
        $rs = $this->Gatewaygroup->query("select card_sip_id,sip_code from card_sip where card_id = (select card_id from card where card_number = '$card_number')");
        echo str_ireplace("\"", "'", json_encode($rs));
    }

    //校
    function checkipaddres($ipaddres) {
        $preg = "/\A((([0-9]?[0-9])|(1[0-9]{2})|(2[0-4][0-9])|(25[0-5]))\.){3}(([0-9]?[0-9])|(1[0-9]{2})|(2[0-4][0-9])|(25[0-5]))\Z/";
        if (preg_match($preg, $ipaddres))
            return true;
        return false;
    }

    public function select_naem($id=null) {
        if (!empty($id)) {
            $sql = "select name from client where client_id=$id ";
            $list = $this->Client->query($sql);
            $this->set('name', $list[0][0]['name']);
        } else {
            $this->set('name', '');
        }
    }

}

?>
