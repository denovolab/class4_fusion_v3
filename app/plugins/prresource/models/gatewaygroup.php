<?php

class Gatewaygroup extends AppModel {

    var $name = 'Gatewaygroup';
    var $useTable = "resource";
    var $primaryKey = "resource_id";

    public function egress_report_all() {
        empty($_GET['page']) ? $currPage = 1 : $currPage = $_GET['page'];
        empty($_GET['size']) ? $pageSize = 100 : $pageSize = $_GET['size'];
        require_once 'MyPage.php';
        $page = new MyPage();
        $login_type = $_SESSION['login_type'];
        $privilege = ''; //权限条件
        if ($login_type == 3) {
            $privilege = "  and(client_id::integer={$_SESSION['sst_client_id']}) ";
        }
        //模糊搜索
        $like_where = !empty($_GET['search']) ? " and (alias ilike '%{$_GET['search']}%'  or  name ilike '%{$_GET['search']}%'  or
	  resource.resource_id::text ilike '%{$_GET['search']}%')" : '';
        $client_where = !empty($_GET ['query'] ['id_clients']) ? "  and (resource.client_id::integer={$_GET ['query'] ['id_clients']})" : '';
        $name_where = !empty($_GET['name']) ? " and (name='{$_GET['name']}')" : '';
        $alias_where = !empty($_GET['id']) ? " and (alias='{$_GET['id']}')" : '';
        $totalrecords = $this->query("select count(resource_id) as c from resource where egress=true 
	  $like_where    $name_where  $alias_where   $client_where      $privilege");
        $order = !empty($_GET['order']) ? "order by {$_GET['order']}" : "";
        $order.=!empty($_GET['order']) ? "   {$_GET['sc']}" : "";
        $page->setTotalRecords($totalrecords[0][0]['c']); //总记录数
        $page->setCurrPage($currPage); //当前页
        $page->setPageSize($pageSize); //页大小
        //$page = $page->checkRange($page);//检查当前页范围
        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();
        $offset = $currPage * $pageSize;
        $sql = "select resource.alias,resource.resource_id ,resource.name,cps_limit,capacity, a.ip_cnt,real.cdr_cnt from resource left join (select count(*)as ip_cnt,resource_id from resource_ip group by resource_id) a on a.resource_id=resource.resource_id left join (select count(*)as cdr_cnt,egress_id from real_cdr group by egress_id) real on real.egress_id=resource.resource_id::varchar where egress=true
		$like_where $name_where  $alias_where $client_where $privilege $order";
        $sql .= "  	limit '$pageSize' offset '$offset'";
        $results = $this->query($sql);
        $page->setDataArray($results);
        return $page;
    }
    
    public function getTimeout()
    {
        $sql = "select ingress_pdd_timeout, egress_pdd_timeout, ring_timeout, call_timeout from system_parameter limit 1";
        $result = $this->query($sql);
        return $result[0][0];
    }
    
    public function getBillingRules()
    {
        $data = array();
        $sql = "select id, name from did_billing_plan order by name asc";
        $result = $this->query($sql);
        foreach($result as $item)
        {
            $data[$item[0]['id']] = $item[0]['name'];
        }
        return $data;
    }

    public function getAddInfo($reseller_id) {
        $where = '';
        if (!empty($reseller_id)) {
            $where = " where reseller_id = {$reseller_id}";
        }
        $res_sql = "select reseller_id,name from reseller $where";
        $codec_sql = "select card_id,card_number from card ";
        return array($this->query($codec_sql));
    }

    /**
     * 验证信息
     */
    function validate_res($data, $post_arr) {
        $error_flag = 'false'; //错误信息标志
        $res_id = $this->getkeyByPOST('resource_id', $post_arr);
        $client_id = $data['Gatewaygroup']['client_id'];
        if (isset($data['Gatewaygroup']['translation_id'])) {
            $translation_id = $data['Gatewaygroup']['translation_id'];
        }
        $alias = $data['Gatewaygroup']['alias'];
        $capacity = $data['Gatewaygroup']['capacity'];
        $cps_limit = $data['Gatewaygroup']['cps_limit'];
        if (empty($client_id)) {
            $this->create_json_array('#GatewaygroupClientId', 101, __('gate_client', true));
            $error_flag = 'true'; //有错误信息
        }
        //判空 
        if (!empty($cps_limit)) {
            if (!ereg("^[0-9]+$", $cps_limit)) {
                $this->create_json_array('#totalCPS', 101, 'CPS Limit, must be whole number! ');
                $error_flag = 'true'; //有错误信息
            }
            IF ($cps_limit < 0) {
                $this->create_json_array('#totalCPS', 101, 'Can not be allowed to address a number of negative');
                $error_flag = 'true'; //有错误信息
            }
        }
        //判空 
        if (!empty($capacity)) {
            if (!ereg("^[0-9]+$", $capacity)) {
                $this->create_json_array('#totalCall', 101, 'Call limit, must be whole number!');
                $error_flag = 'true'; //有错误信息
            }
            IF ($capacity < 0) {
                $this->create_json_array('#totalCall', 101, 'Allow the number of calls can not be negative');
                $error_flag = 'true'; //有错误信息
            }
        }
        if (empty($alias)) {
            $this->create_json_array('#alias', 201, __('gate_red_id', true));
            $error_flag = 'true'; //有错误信息
        }
        if (!empty($alias)) {
            $c = $this->check_alias($res_id, $alias);
            if ($c != 0) {
                $this->create_json_array('#alias', 301, __($alias . 'is already in use!', true));
                $error_flag = 'true'; //有错误信息
            }
        }

        if ($data['Gatewaygroup']['ingress'] == 'true' && !empty($post_arr['accounts']['ip'])) {
            foreach ($post_arr['accounts']['ip'] as $k => $v) {
                //检查ip+prefix是否重复
                if (!empty($post_arr['resource']['tech_prefix'])) {
                    $source_check = array();
                    foreach ($post_arr['resource']['tech_prefix'] as $k_pre => $v_pre) {
                        if (!empty($v_pre)) {
                            $source_check[] = "resource_id in (select resource_id from resource_prefix where tech_prefix != '' and (tech_prefix <@ '{$v_pre}' or '{$v_pre}' <@ tech_prefix))";
                        }
                    }
                    $source_check_sql = implode(" or ", $source_check);
                    if (!empty($source_check_sql)) {
                        $sql_res = '';
                        if (!empty($data['Gatewaygroup']['resource_id'])) {
                            $sql_res = ' and resource_id != ' . intval($data['Gatewaygroup']['resource_id']);
                        }
                        $sql_prefix_check = "select count(*) as cnt from resource_ip where ip = '{$v}' and ({$source_check_sql}) {$sql_res}";
                        //var_dump($sql_prefix_check);exit;
                        $prefix_check = $this->query($sql_prefix_check);
                        if ($prefix_check[0][0]['cnt'] > 0) {
                            $this->create_json_array('#alias', 301, __($alias . ' Trunk IP and Prefix is duplicate! ', true));
                            $error_flag = 'true'; //有错误信息
                        }
                    }
                }
            }
        }
        
        /*
        if (!empty($post_arr['accounts']['ip']) && $data['Gatewaygroup']['ingress'] == 'true') {
            foreach ($post_arr['accounts']['ip'] as $k => $v) {
                //ip+client_id check
                $sql_client_ip_check = "select count(*) as cnt from resource_ip join resource on resource_ip.resource_id = resource.resource_id where ingress=true and ip = '{$v}' and resource_ip.resource_id in (select resource_id from resource where client_id != {$client_id})";
                $client_ip_check = $this->query($sql_client_ip_check);
                if ($client_ip_check[0][0]['cnt'] > 0) {
                    $this->create_json_array('#alias', 301, __($alias . ' Trunk IP Has Been Used By Other Carrier! ', true));
                    $error_flag = 'true'; //有错误信息
                }
            }
        }
        */
        return $error_flag;
    }

    /**
     * 验证alias
     * @param unknown_type $res_id
     * @param unknown_type $a
     */
    function check_alias($res_id, $a) {
        $alias = "'" . $a . "'";
        empty($res_id) ? $sql = "select count(*) from resource where alias=$alias " :
                        $sql = "select count(*) from resource where alias=$alias  and resource_id<>$res_id";
        $c = $this->query($sql);
        if (empty($c)) {
            return 0;
        } else {
            return $c[0][0]['count'];
        }
    }

    /**
     * 添加和更新网关组   
     * 
     */
    function saveOrUpdate($data, $post_arr, $account = Array()) {
        $msgs = $this->validate_res($data, $post_arr); //错误信息
        if ($msgs == 'true') {
            return 'fail'; //add fail
        } else {
            $res_id = $this->saveOrUpdate_resource($data, $post_arr); //添加或者更新

            if (!empty($res_id)) {
                $this->add_res_codecs($res_id, $data['Gatewaygroup']['select2'], $data['Gatewaygroup']['rfc_2833']);
                $this->saveHost($account, $res_id);  #保存host 
                isset($post_arr['resource']) ? $this->saveResouce($post_arr['resource'], $res_id) : '';

                $count = isset($_POST['route_type']) ? count($_POST['route_type']) : 0;
                
                
                if ($data['Gatewaygroup']['ingress'] == true && $data['Gatewaygroup']['trunk_type2'] == 1)
                {
                        if ($data['Gatewaygroup']['billing_method'] == '0')
                        {
                            $rate_table_id = $data['Gatewaygroup']['rate_table'];
                        } else {
                            $rate_table_id = 'NULL';
                        }
                        $this->_did_control($rate_table_id, $res_id);
                }
                
               
                
                
                if ($count > 0) {
                    //$this->delete_relation_route($res_id);
                    $this->delete_dynamic_static($res_id);
                    for ($i = 0; $i < $count; $i++) {
                        $route_type = $_POST['route_type'][$i];
                        $dynamic = $_POST['dynamic'][$i];
                        $static = $_POST['static'][$i];
                        $prefix = $_POST['prefix'][$i];
                        switch ($route_type) {
                            case 0:
                                $this->add_to_dynamic($res_id, $dynamic);
                                //$this->add_to_relation($res_id, 0, $dynamic, 'NULL', '');
                                break;
                            case 1:
                                $this->add_to_static($res_id, $static, $prefix);
                                //$this->add_to_relation($res_id, 1, 'NULL', $static, $prefix);
                                break;
                            case 2:
                                $this->add_to_dynamic($res_id, $dynamic);
                                $this->add_to_static($res_id, $static, $prefix);
                                //$this->add_to_relation($res_id, 2, $dynamic, $static, $prefix);
                                break;
                        }
                    }
                }
                
                if(isset($_POST['profiles']))
                {
                    $profiles = $_POST['profiles'];
                    $server_names = $_POST['server_names'];
                    $this->_update_profiles($res_id, $profiles, $server_names);
                }
                
                return $res_id;
            }
        }
    }
    
    public function _did_control($rate_table_id, $res_id)
    {
    	$sql = "select id from resource_prefix where resource_id = {$res_id}";
    	$result = $this->query($sql);
    	if(!empty($result))
    	{
    		$sql = "update resource_prefix set rate_table_id = {$rate_table_id} where id = {$result[0][0]['id']}";
    	}
    	else
    	{
                $sql = "SELECT route_strategy_id from route_strategy where name = 'ORIGINATION_ROUTING_PLAN'";
                $result = $this->query($sql);
                if(empty($result))
                {
                    $sql = "insert into route_strategy (name) values ('ORIGINATION_ROUTING_PLAN') returning route_strategy_id";
                    $result = $this->query($sql);
                    
                    $sql = "select product_id from product where name = 'ORIGINATION_STATIC_ROUTE'";
                    $result_static = $this->query($sql);
                    if(empty($result_static))
                    {
                        $sql = "insert into product(name) values ('ORIGINATION_STATIC_ROUTE') returning product_id";
                        $result_static = $this->query($sql);
                    }
                    $sql = "insert into route(static_route_id, route_type, route_strategy_id) values({$result_static[0][0]['product_id']}, 2, {$result[0][0]['route_strategy_id']});";
                    $this->query($sql);
                }
    		$sql = "insert into resource_prefix (rate_table_id, route_strategy_id, resource_id) 
    				values ({$rate_table_id},{$result[0][0]['route_strategy_id']}, {$res_id})";
    	}
    	$this->query($sql);
    }
    
    public function _update_profiles($res_id, $profiles, $server_names, $ingresses)
    {
        $sql = "delete from egress_profile where egress_id = {$res_id}";
        $this->query($sql);
        $sql_array = array();
        foreach($profiles as $key => $profile)
        {
            $ingress_id = $ingresses[$key] == '' ? 'NULL' : $ingresses[$key];
            
            if(!empty($profile))
                array_push ($sql_array, "insert into egress_profile(egress_id, profile_id, server_name, ingress_id) values ({$res_id}, {$profile}, '{$server_names[$key]}', {$ingress_id})");
        }
        if(count($sql_array))
        {
            $sql_str = implode(';', $sql_array);
            $this->query($sql_str);
        }
    }
    
    public function get_profiles($egress_id)
    {
        $data = array();
        $sql = "select profile_id from egress_profile where egress_id = {$egress_id}";
        $result = $this->query($sql);
        foreach($result as $item)
        {
            array_push($data, $item[0]['profile_id']);
        }
        return $data;
    }
    
    public function get_profile_ingresses($egress_id)
    {
        $data = array();
        $sql = "select (select voip_gateway_id from switch_profile where id = profile_id) as gateway_id, ingress_id from egress_profile where egress_id = {$egress_id}";
        $result = $this->query($sql);
        foreach($result as $item)
        {
            $data[$item[0]['gateway_id']] = $item[0]['ingress_id'];
        }
        return $data;
    }

    public function delete_relation_route($trunk_id) {
        $sql = "DELETE FROM dynamic_route_items WHERE resource_id = {$trunk_id};";
        $sql .= "DELETE FROM product_items_resource WHERE resource_id = {$trunk_id};";
        $sql .= "DELETE FROM trunk_relation_route WHERE resource_id = {$trunk_id};";
        $this->query($sql);
    }

    public function add_to_relation($trunk_id, $type, $dynamic, $static, $prefix) {
        $sql = "INSERT INTO trunk_relation_route(resource_id,type, dynamic, static, prefix) VALUES ($trunk_id,$type, $dynamic, $static, '{$prefix}')";
        $this->query($sql);
    }

    public function delete_dynamic_static($trunk_id) {
        $sql = "DELETE FROM dynamic_route_items WHERE resource_id = {$trunk_id};";
        $sql .= "DELETE FROM product_items_resource WHERE resource_id = {$trunk_id};";
        $this->query($sql);
    }

    public function add_to_dynamic($trunk_id, $dynamic) {
        $sql = "insert into dynamic_route_items (dynamic_route_id, resource_id) values ({$dynamic}, {$trunk_id})";
        $this->query($sql);
    }

    public function add_to_static($trunk_id, $static, $prefix) {
        $prefix_arr = explode(',', $prefix);
        $sql = "";
        foreach ($prefix_arr as $prefix_one) {
            $sql .= "INSERT INTO product_items_resource(item_id, resource_id)
VALUES((SELECT item_id FROM product_items WHERE product_id = {$static} AND digits = '{$prefix_one}'), {$trunk_id});";
        }
        $this->query($sql);
    }

    function saveOrUpdate_pro($res_id, $post_arr) {
        if (empty($post_arr ['proId'][0])) {

            return;
        }
        if (isset($post_arr['ref_id'])) {
            $ref_id_arr = $post_arr['ref_id'];
            $update_size = intval(count($ref_id_arr)); //更新记录	   
        } else {
            $update_size = 0;
        }
        $proId_arr = $post_arr ['proId'];
        $prefix_arr = $post_arr ['prefix'];
        $all_size = intval(count($proId_arr));
        $add_size = $all_size - $update_size; //添加记录
        for ($i = 0; $i < $update_size; $i++) {
            empty($prefix_arr[$i]) ? $d = 'NULL' : $d = "'" . $prefix_arr[$i] . "'" . "::prefix_range";
            $this->query("update  resource_product_ref  set  product_id=$proId_arr[$i],digit=$d where ref_id=$res_id");
        }
        //批量添加
        for ($i = $update_size; $i < $all_size; $i++) {
            //empty($prefix_arr[$i])? $d='NULL' :  $d="'".$prefix_arr[$i]."'"."::prefix_range";
            if (empty($prefix_arr[$i])) {
                $sql = "insert into resource_product_ref (resource_id,product_id) values($res_id,$proId_arr[$i])";
            } else {
                $d = "'" . $prefix_arr[$i] . "'" . "::prefix_range";
                $sql = "insert into resource_product_ref (resource_id,product_id,digit) values($res_id,$proId_arr[$i],$d)";
            }
            $this->query($sql);
        }
        //抓去界面上删除的host
        if (!empty($post_arr['delProduct'])) {
            $del_arr = substr($post_arr['delProduct'], 1);
            $this->query("delete from resource_product_ref  where  ref_id in($del_arr )");
        }
    }

//添加号码转换
    function saveOrUpdate_direction($res_id, $post_arr) {
        if (isset($post_arr['direction_id'])) {
            $direction_id_arr = $post_arr['direction_id'];
            $update_size = intval(count($direction_id_arr)); //更新记录 
        } else {
            $update_size = 0; //更新记录为0  ，添加操作 
        }
        $direct_arr = $post_arr ['direct'];
        $match_arr = $post_arr ['match'];
        $action_arr = $post_arr ['action'];
        $digit_arr = $post_arr ['digit'];
        $all_size = intval(count($direct_arr));
        $add_size = $all_size - $update_size; //添加记录
        //批量更新
        for ($i = 0; $i < $update_size; $i++) {
            $match_arr[$i] = trim($match_arr[$i]);
            $digit_arr[$i] = trim($digit_arr[$i]);
            if (empty($match_arr[$i]) && empty($digit_arr[$i])) {
                continue;
            }
            empty($match_arr[$i]) ? $dnis = 'NULL' : $dnis = "'" . $match_arr[$i] . "'" . "::prefix_range";
            empty($digit_arr[$i]) ? $d = 'NULL' : $d = "'" . $digit_arr[$i] . "'";
            $this->query("update  resource_direction set time_profile_id=$direct_arr[$i],action=$action_arr[$i],digits=$d,dnis=$dnis  
					  where direction_id =$direction_id_arr[$i]");
        }
        //批量添加
        for ($i = $update_size; $i < $all_size; $i++) {
            empty($match_arr[$i]) ? $dnis = 'NULL' : $dnis = "'" . $match_arr[$i] . "'" . "::prefix_range";
            empty($digit_arr[$i]) ? $d = 'NULL' : $d = "'" . $digit_arr[$i] . "'";
            if ($direct_arr[$i] == '') {
                $this->query("insert into resource_direction (resource_id,action,digits,dnis) values($res_id,$action_arr[$i],$d,$dnis)");
            } else {
                $this->query("insert into resource_direction (resource_id,time_profile_id,action,digits,dnis) values($res_id,$direct_arr[$i],$action_arr[$i],$d,$dnis)");
            }
        }
        //抓去界面上删除的host
        if (!empty($post_arr['deldirectionid'])) {
            $del_arr = substr($post_arr['deldirectionid'], 1);
            $this->query("delete from resource_direction  where  direction_id in($del_arr )");
        }
    }

    /*
     * 添加和更新res_ip
     */

    function saveOrUpdate_res_ip($res_id, $post_arr) {
        if (isset($post_arr['ip_id'])) {
            $ip_id_arr = $post_arr ['ip_id'];
            $update_size = intval(count($ip_id_arr)); //更新记录 
        } else {
            $update_size = 0; //更新记录为0  ，添加操作 
        }
        $ip_arr = $post_arr ['ip'];
        $netmask_arr = $post_arr ['netmask'];
        $port_arr = $post_arr ['port'];
        $capa_arr = $post_arr ['capa'];
        $cps_arr = $post_arr ['cps'];
        $username_arr = $post_arr['username'];
        $need_register_arr = $post_arr['need_register'];
        $pass_arr = $post_arr['pass'];
        $time_profile_id_arr = $post_arr ['time_profile_id'];

        $all_size = intval(count($ip_arr));
        $add_size = $all_size - $update_size; //添加记录
        //批量更新 res_ip
        for ($i = 0; $i < $update_size; $i++) {
            $ip = "'" . $ip_arr[$i] . "/" . $netmask_arr[$i] . "'" . "::ip4r";
            empty($capa_arr[$i]) ? $ca = 'NULL' : $ca = $capa_arr[$i];
            empty($cps_arr[$i]) ? $cps = 'NULL' : $cps = $cps_arr[$i];
            empty($port_arr[$i]) ? $port = 'NULL' : $port = $port_arr[$i];
            empty($time_profile_id_arr[$i]) ? $time_profile_id_arr[$i] = 'null' : null;
            $register = $need_register_arr[$i];
            if ($register == 'true') {
                $this->query("update  resource_ip  set fqdn=$ip,port=$port,capacity=$ca,cps_limit=$cps,username=$username_arr[$i],
				 	password=$pass_arr[$i],need_register=true,
					time_profile_id=$time_profile_id_arr[$i] where resource_ip_id=$ip_id_arr[$i]");
            } else {
                $this->query("update  resource_ip  set ip=$ip,port=$port,capacity=$ca,cps_limit=$cps,need_register=false,
					time_profile_id=$time_profile_id_arr[$i] where resource_ip_id=$ip_id_arr[$i]");
            }
        }
        //批量添加res_ip
        for ($i = $update_size; $i < $all_size; $i++) {
            $ip = "'" . $ip_arr[$i] . "/" . $netmask_arr[$i] . "'" . "::ip4r";
            $ip1 = "'" . $ip_arr[$i] . "/" . $netmask_arr[$i] . "'";
            empty($capa_arr[$i]) ? $ca = 'NULL' : $ca = $capa_arr[$i];
            empty($cps_arr[$i]) ? $cps = 'NULL' : $cps = $cps_arr[$i];
            empty($port_arr[$i]) ? $port = 'NULL' : $port = $port_arr[$i];
            empty($time_profile_id_arr[$i]) ? $time_profile_id_arr[$i] = 'null' : null;
            $register = $need_register_arr[$i];
            if ($register == 'true') {
                $this->query("insert into resource_ip (resource_id,fqdn,port,capacity,cps_limit,time_profile_id,username,password,need_register)  
					  values($res_id,$ip1,$port,$ca,$cps,$time_profile_id_arr[$i],'$username_arr[$i]','$pass_arr[$i]',true)");
            } else {
                $this->query("insert into resource_ip (resource_id,ip,port,capacity,cps_limit,time_profile_id,need_register)  
					  values($res_id,$ip,$port,$ca,$cps,$time_profile_id_arr[$i],false)");
            }
        }
        //抓去界面上删除的host
        if (!empty($post_arr['delHost'])) {
            $del_arr = substr($post_arr['delHost'], 1);
            $this->query("delete from resource_ip  where  resource_ip_id in($del_arr )");
        }
    }

    /**
     * 添加网关组
     */
    function saveOrUpdate_resource($data, $post_arr) {
        //$trunk_type = Configure::read('system.type');
        //$data['Gatewaygroup']['trunk_type'] = $trunk_type;
        $client_id = $data['Gatewaygroup']['client_id'];
        //find client  rateble
        $list = $this->query("select orig_rate_table_id,profit_margin,profit_type,enough_balance from  client  where  client_id=$client_id");
        $rate_table_id = $list[0][0]['orig_rate_table_id'];
        //$data['Gatewaygroup']['profit_margin'] = $list[0][0]['profit_margin'];
        //$data['Gatewaygroup']['profit_type'] = $list[0][0]['profit_type'];
        if ($data['Gatewaygroup']['egress'] == 'true') {
        	
        } else {
            $data['Gatewaygroup']['enough_balance'] = $list[0][0]['enough_balance'];
        }
        $res_id = $this->getkeyByPOST('resource_id', $post_arr);

        //ignore_ring_early_media
        switch ($data['Gatewaygroup']['ignore_ring_early_media']) {
            case 0:
                $data['Gatewaygroup']['ignore_ring'] = false;
                $data['Gatewaygroup']['ignore_early_media'] = false;
                break;
            case 1:
                $data['Gatewaygroup']['ignore_ring'] = true;
                $data['Gatewaygroup']['ignore_early_media'] = true;
                break;
            case 2:
                $data['Gatewaygroup']['ignore_ring'] = true;
                $data['Gatewaygroup']['ignore_early_media'] = false;
                break;
            case 3:
                $data['Gatewaygroup']['ignore_ring'] = false;
                $data['Gatewaygroup']['ignore_early_media'] = true;
                break;
            default:
                $data['Gatewaygroup']['ignore_ring'] = false;
                $data['Gatewaygroup']['ignore_early_media'] = false;
        }
        unset($data['Gatewaygroup']['ignore_ring_early_media']);

        $data['Gatewaygroup']['update_at'] = date("Y-m-d H:i:s");
        $data['Gatewaygroup']['update_by'] = $_SESSION['sst_user_name'];
        
        $client_id = $data['Gatewaygroup']['client_id'];
        
        //$result = $this->query("SELECT unlimited_credit FROM client WHERE client_id = {$client_id}");
        
        //$data['Gatewaygroup']['enough_balance'] = $result[0][0]['unlimited_credit'];
        
        
        if($data['Gatewaygroup']['capacity'] == 0)
        {
            $data['Gatewaygroup']['capacity'] = null;
        }
       
        if($data['Gatewaygroup']['cps_limit'] == 0)
        {
            $data['Gatewaygroup']['cps_limit'] = null;
        }
        
        if (empty($data['Gatewaygroup']['wait_ringtime180']))
        {
            $data['Gatewaygroup']['wait_ringtime180'] = null;
        }

        if (!isset($data['Gatewaygroup']['service_type'])) {
            $data['Gatewaygroup']['service_type'] = 0;
        }

        if (!isset($data['Gatewaygroup']['private'])) {
            $data['Gatewaygroup']['private'] = 0;
        }

        if (!empty($res_id)) {
            $data['Gatewaygroup']['resource_id'] = $res_id;
            $this->save($data ['Gatewaygroup']);
            $this->logging(2, 'Trunk', "Trunk Name:{$data['Gatewaygroup']['alias']}");
        } else {
            if ($data['Gatewaygroup']['trunk_type2'] == 1)
            {
               $data['Gatewaygroup']['account_id'] = md5(uniqid());     
            }
            $this->save($data ['Gatewaygroup']);
            $res_id = $this->getLastInsertID();
            $this->logging(0, 'Trunk', "Trunk Name:{$data['Gatewaygroup']['alias']}");
        }
        return $res_id;
    }

    /*
     * 添加res_codes
     */

    function add_res_codecs($res_id, $codecs, $isRfc2833) {
        $this->query("delete  from  resource_codecs_ref  where  resource_id=$res_id");

        /*
          if(empty($res_id)||empty($codecs)){
          return ;
          }
         */
        if ($isRfc2833 == 'true') {

            $sql = "SELECT id FROM codecs WHERE name = 'telephone-event/dynamic'";
            $tel_id_result = $this->query($sql);
            $tel_id = intval($tel_id_result[0][0]['id']);

            if (count($codecs) && !in_array($tel_id, $codecs)) {
                array_push($codecs, $tel_id);
            }
        }

        foreach ($codecs as $key => $value) {
            $this->query("insert into resource_codecs_ref (resource_id,codec_id)values($res_id,$value)");
        }
    }

    /**
     * 
     */
    function findAllRate() {
        $r = $this->query("select rate_table_id,name from rate_table  order  by  name asc");
        $size = count($r);
        $l = array();
        for ($i = 0; $i < $size; $i++) {
            $key = $r[$i][0]['rate_table_id'];
            $l[$key] = $r[$i][0]['name'];
        }
        return $l;
    }
    
    public function findAllSwitchProfile() {
        $r = $this->query("select id,profile_name from switch_profile order by profile_name asc");
        $size = count($r);
        $l = array();
        for ($i = 0; $i < $size; $i++) {
            $key = $r[$i][0]['id'];
            $l[$key] = $r[$i][0]['profile_name'];
        }
        return $l;
    }
    
    
    public function get_gateway_profiles()
    {
        $data = array();
        $sql = "select * from voip_gateway";
        /*
        $sql = "select 

voip_gateway.id, voip_gateway.name, switch_profile.id as profile_id 

from voip_gateway 

inner join switch_profile on switch_profile.voip_gateway_id = voip_gateway.id";
         * 
         */
        $result = $this->query($sql);
        foreach($result as $item) {
            $arr = array();
            $server_id = $item[0]['id'];
            $server_name = $item[0]['name'];
            $arr['id'] = $server_id;
            $arr['name'] = $server_name;
            $arr['profiles'] = array();
            $sql_child = "select id, profile_name from switch_profile where voip_gateway_id = {$server_id}";
            $result_child = $this->query($sql_child);
            foreach($result_child as $child_item) {
                $profile_id = $child_item[0]['id'];
                $profile_name = $child_item[0]['profile_name'];
                array_push($arr['profiles'], array(
                    $profile_id, $profile_name
                ));
            }
            array_push($data, $arr);
        }
        
        return $data;
    }

    /**
     * 
     */
    function getAllRate() {
        $r = $this->query("select rate_table_id,name from rate_table");
        return $r;
    }

    /**
     *  查询静态路由表
     */
    function findAllProduct() {
        $r = $this->query("select product_id ,name from product order by name asc");
        $size = count($r);
        $l = array();
        for ($i = 0; $i < $size; $i++) {
            $key = $r[$i][0]['product_id'];
            $l[$key] = $r[$i][0]['name'];
        }
        $re = $l;
        return $re;
    }

    /**
     * 查询所有codecs
     */
    function findcodecs() {
        $r = $this->query("select id ,name   from codecs order by name asc");
        $size = count($r);
        $l = array();
        for ($i = 0; $i < $size; $i++) {
            $key = $r[$i][0]['id'];
            $l[$key] = $r[$i][0]['name'];
        }
        return $l;
    }

    /**
     * 查询号码转换
     */
    function findDigitMapping() {
        $r = $this->query("select  translation_id,translation_name from  digit_translation");
        $size = count($r);
        $l = array();
        for ($i = 0; $i < $size; $i++) {
            $key = $r[$i][0]['translation_id'];
            $l[$key] = $r[$i][0]['translation_name'];
        }
        return $l;
    }

    /**
     * 查询客户
     */
    function findClient() {
        $sst_user_id = $_SESSION['sst_user_id'];
        $r = $this->query("select client.client_id ,client.name from client WHERE (exists(SELECT id FROM users_limit WHERE user_id = {$sst_user_id} AND client_id=client.client_id) 
OR 
exists 
(SELECT users.user_id FROM users LEFT JOIN sys_role ON users.role_id = sys_role.role_id WHERE users.user_id = {$sst_user_id} and (role_name = 'admin'
or sys_role.view_all = true))) order  by  client.name asc");
        $size = count($r);
        $l = array();
        for ($i = 0; $i < $size; $i++) {
            $key = $r[$i][0]['client_id'];
            $l[$key] = $r[$i][0]['name'];
        }
        return $l;
    }

    /**
     * 查询客户
     */
    function getClient() {
        $r = $this->query("select client.client_id ,client.name from client");
        return $r;
    }

    /**
     * 删除
     * @param unknown_type $id
     */
    function del($id) {



        $qs_count = 0;
        /* 	$qs = $this->query("delete  from item_resource_ref where resource_id in($id)");
          $qs_count += count($qs); */

        $qs = $this->query("delete from resource_ip where resource_id in($id)");
        $qs_count += count($qs);
        $qs = $this->query("delete from resource_direction where resource_id in( $id)");
        $qs_count += count($qs);

        $qs = $this->query("delete from resource_prefix where resource_id in( $id)");
        $qs_count += count($qs);

        /* 	$qs = $this->query("delete from code_part where egress_id in ( $id)");
          $qs_count += count($qs); */

        $qs = $this->query("delete from resource_codecs_ref where resource_id in( $id)");
        $qs_count += count($qs);

        $qs = $this->query("delete from test_device where resource_id in( $id)");
        $qs_count += count($qs);

        $qs = $this->query("delete from resource where resource_id in($id)");
        $qs_count += count($qs);

        return $qs_count == 0;
    }

    /**
     * 禁用一个resource
     * @param unknown_type $id
     */
    function dis_able($id) {
        return $this->query("update resource  set   active= false  where resource_id=$id");
    }

    function active($id) {
        return $this->query("update resource  set   active= true  where resource_id=$id");
    }

    /**
     * 查询res_IP地址
     * @param unknown_type $currPage
     * @param unknown_type $pageSize
     */
    function findAllres_ip($res_id) {
        $rs = $this->query("select * from resource_ip where resource_id=$res_id  order by resource_ip_id ");
        $size = count($rs);
        for ($index = 0; $index < $size; $index++) {
            $reg = $rs[$index][0]['need_register'];
            if ($reg != '1') {
                if (strrpos($rs[$index][0]['ip'], "/")) {
                    list($ip, $net) = split('[/]', $rs[$index][0]['ip']);
                    $rs[$index][0]['host'] = $ip;
                    $rs[$index][0]['netmask'] = $net;
                } else {
                    $rs[$index][0]['host'] = $rs[$index][0]['ip'];
                    $rs[$index][0]['netmask'] = '';
                }
            } else {
                $rs[$index][0]['host'] = $rs[$index][0]['fqdn'];
                $rs[$index][0]['netmask'] = '';
            }
            empty($rs[$index][0]['capacity']) ? ($rs[$index][0]['capacity'] = '') : ($rs[$index][0]['capacity'] = $rs[$index][0]['capacity']);
            empty($rs[$index][0]['cps_limit']) ? ($rs[$index][0]['cps_limit'] = '') : ($rs[$index][0]['cps_limit'] = $rs[$index][0]['cps_limit']);
        }
        return $rs;
    }

    function ajax_host_report($res_id) {
        $rs = $this->query("select    ip ,capacity,port , use_cnt from resource_ip 
		left join (select count(* ) as use_cnt,  egress_id,callee_ip_address from real_cdr   group by egress_id  ,callee_ip_address )  a  
		on  a.egress_id=resource_id and a.callee_ip_address=resource_ip.ip
	where resource_id=$res_id");
        return $rs;
    }

    public function findAll_egress($order = null) {
        $sst_user_id = $_SESSION['sst_user_id'];
        if (empty($order)) {
            $order = "alias  asc";
        }
        $temp = isset($_SESSION['paging_row']) ? $_SESSION['paging_row'] : 10;

        empty($_GET['page']) ? $currPage = 1 : $currPage = $_GET['page'];
        empty($_GET['size']) ? $pageSize = $temp : $pageSize = $_GET['size'];

        $_SESSION['paging_row'] = $pageSize;
        //分页信息
        require_once 'MyPage.php';
        $page = new MyPage();
        $login_type = $_SESSION['login_type'];
        $privilege = ''; //权限条件
        if ($login_type == 3) {
            $privilege = "  and(resource.client_id={$_SESSION['sst_client_id']}) ";
        }

        $search = isset($_GET['search']) ? trim($_GET['search']) : '';
        $like_where = !empty($search) ? " and (resource.alias ilike '%{$search}%'  or  resource.resource_id::text like '%{$search}%' 
	  or  (select count(*)>0 from resource_ip where resource_ip.resource_id =resource.resource_id and resource_ip.ip::varchar  like '%{$search}%' )
    or  (select count(*)>0 from client where client.client_id =resource.client_id and client.name  ilike '%{$search}%' )
	    or  alias ilike '%{$search}%')" : '';
        $name_where = !empty($_GET['name']) ? "  and (resource.name ilike '%{$_GET['name']}%')" : '';
        $rate_table_id = !empty($_GET['rate_table_id']) ? " and resource.rate_table_id= {$_GET['rate_table_id']}" : "";
        $id_where = !empty($_GET['id']) ? "  and (resource.resource_id::text = '{$_GET['id']}')" : '';
        $ip_where = array_keys_value($_GET, 'filter_ip');
        $ip_where = enumerate_return($ip_where, "and (select count(*) from resource_ip where (resource_id=resource.resource_id) and (ip::text like '%$ip_where%' or fqdn::text like '%$ip_where%'))>0", '');
        //$client_where=!empty($_GET ['query'] ['id_clients_name'])?"  and (client.name='{$_GET ['query'] ['id_clients_name']}')":'';
        $client_where = !empty($_GET ['query'] ['id_clients']) ? "  and (client.client_id::text='{$_GET ['query'] ['id_clients']}')" : '';
        $totalrecords = $this->query("select count(resource.resource_id) as c from resource 
	 	 left join (select count(*)as ip_cnt,resource_id from resource_ip group by resource_id) a on a.resource_id=resource.resource_id
    inner  join client   on client.client_id=resource.client_id
    left join rate_table on resource.rate_table_id=rate_table.rate_table_id
    left join route_strategy on route_strategy.route_strategy_id=resource.route_strategy_id
                 
	 where 
                 (exists(SELECT id FROM users_limit WHERE user_id = {$sst_user_id} AND client_id=client.client_id) 
OR 
exists 
(SELECT users.user_id FROM users LEFT JOIN sys_role ON users.role_id = sys_role.role_id WHERE users.user_id = {$sst_user_id} and (role_name = 'admin'
or sys_role.view_all = true)))
                AND
                 egress=true 
	  $like_where  $name_where $rate_table_id    $client_where  $id_where $ip_where   $privilege");
        $page->setTotalRecords($totalrecords[0][0]['c']); //总记录数
        $page->setCurrPage($currPage); //当前页
        $page->setPageSize($pageSize); //页大小
        //$page = $page->checkRange($page);//检查当前页范围

        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();
        $offset = $currPage * $pageSize;
        $sql = "select resource.alias,resource.resource_id  ,resource.cps_limit ,resource.update_at,resource.update_by,capacity,ingress,egress,active, a.ip_cnt , resource.client_id,proto,wait_ringtime180
    ,client.name as client_name,rate_table.name as rate_table_name,rate_table.rate_table_id as rate_table_id,
    (SELECT COUNT(*) FROM dynamic_route_items WHERE resource_id = resource.resource_id) as dynamic_count,
    (SELECT COUNT(*) FROM product_items_resource WHERE resource_id = resource.resource_id) as static_count
    from  resource
		 left join (select count(*)as ip_cnt,resource_id from resource_ip group by resource_id) a on a.resource_id=resource.resource_id
    inner  join client   on client.client_id=resource.client_id
    left join rate_table on resource.rate_table_id=rate_table.rate_table_id
		 where 
                 (exists(SELECT id FROM users_limit WHERE user_id = {$sst_user_id} AND client_id=client.client_id) 
OR 
exists 
(SELECT users.user_id FROM users LEFT JOIN sys_role ON users.role_id = sys_role.role_id WHERE users.user_id = {$sst_user_id} and (role_name = 'admin'
or sys_role.view_all = true)))
                AND
                egress=true  
		$like_where  $name_where $rate_table_id    $client_where  $id_where $ip_where   $privilege  ";
        $sql.="  order by  $order ";
        $sql .= "   	limit '$pageSize' offset '$offset'";
        $results = $this->query($sql);

        $page->setDataArray($results);
        return $page;
    }

    public function findAll_ingress($order = null) {
        $sst_user_id = $_SESSION['sst_user_id'];
        if (empty($order)) {
            $order = "alias  asc";
        }
        $temp = isset($_SESSION['paging_row']) ? $_SESSION['paging_row'] : 10;

        empty($_GET['page']) ? $currPage = 1 : $currPage = $_GET['page'];
        empty($_GET['size']) ? $pageSize = $temp : $pageSize = $_GET['size'];

        $_SESSION['paging_row'] = $pageSize;

        //分页信息
        require_once 'MyPage.php';
        $page = new MyPage();
        $login_type = $_SESSION['login_type'];
        $privilege = ''; //权限条件
        if ($login_type == 3) {
            $privilege = "  and(resource.client_id={$_SESSION['sst_client_id']}) ";
        }
//模糊搜索
        $like_where = !empty($_GET['search']) ? " and (resource.name ilike '%{$_GET['search']}%'  or  resource.resource_id::text ilike '%{$_GET['search']}%' 
	  or  (select count(*)>0 from resource_ip where resource_ip.resource_id =resource.resource_id and resource_ip.ip::varchar  ilike '%{$_GET['search']}%' )
    or  (select count(*)>0 from client where client.client_id =resource.client_id and client.name  ilike '%{$_GET['search']}%' )
	    or  alias ilike '%{$_GET['search']}%')" : '';
            
        $ratetable_where = '';
        
        if (isset($_GET['rate_table_id']))
            $ratetable_where = "and exists (select * from resource_prefix where rate_table_id = {$_GET['rate_table_id']} and resource_id = resource.resource_id)";

        $name_where = !empty($_GET['name']) ? "  and (resource.name ilike '%{$_GET['name']}%')" : '';
        $id_where = !empty($_GET['id']) ? "  and (resource.resource_id::text = '{$_GET['id']}')" : '';
        $resoure_prefix_has = !empty($_GET['resource_prefix']) ? " and (select count(*) from resource_prefix as t where t.resource_id = resource.resource_id 
and route_strategy_id = {$_GET['resource_prefix']})  > 0" : '';
        $ip_where = array_keys_value($_GET, 'filter_ip');
        $ip_where = enumerate_return($ip_where, "and (select count(*) from resource_ip where (resource_id=resource.resource_id) and (ip::text like '%$ip_where%' or fqdn::text like '%$ip_where%'))>0", '');
        $client_where = !empty($_GET ['query'] ['id_clients']) ? "  and (client.client_id::text='{$_GET ['query'] ['id_clients']}')" : '';
        $totalrecords = $this->query("select count(resource.resource_id) as c from resource
 			left join (select count(*)as ip_cnt,resource_id from resource_ip group by resource_id) a on a.resource_id=resource.resource_id
    inner  join client   on client.client_id=resource.client_id
    
	 where
                 (exists(SELECT id FROM users_limit WHERE user_id = {$sst_user_id} AND client_id=client.client_id) 
OR 
exists 
(SELECT users.user_id FROM users LEFT JOIN sys_role ON users.role_id = sys_role.role_id WHERE users.user_id = {$sst_user_id} and (role_name = 'admin'
or sys_role.view_all = true)))
                AND
                 
                 ingress=true 
	  $like_where  $name_where    $client_where  $id_where  $ip_where $ratetable_where $resoure_prefix_has  $privilege");
        $page->setTotalRecords($totalrecords[0][0]['c']); //总记录数
        $page->setCurrPage($currPage); //当前页
        $page->setPageSize($pageSize); //页大小
        $pageSize = $page->getPageSize();
        $currPage = $page->getCurrPage() - 1;
        $offset = $currPage * $pageSize;
        $sql = "select resource.alias,resource.resource_id , resource.name ,resource.cps_limit,capacity,resource.update_at,resource.update_by,ingress,egress,active, a.ip_cnt , resource.client_id,proto,wait_ringtime180
    ,client.name as client_name	,(select count(*) from route_strategy where route_strategy_id=resource.route_strategy_id) as rs_cnt, proto, resource.profit_margin
    from  resource
		 left join (select count(*)as ip_cnt,resource_id from resource_ip group by resource_id) a on a.resource_id=resource.resource_id
    inner  join client   on client.client_id=resource.client_id
     
		 where 
                (exists(SELECT id FROM users_limit WHERE user_id = {$sst_user_id} AND client_id=client.client_id) 
OR 
exists 
(SELECT users.user_id FROM users LEFT JOIN sys_role ON users.role_id = sys_role.role_id WHERE users.user_id = {$sst_user_id} and (role_name = 'admin'
or sys_role.view_all = true)))
                AND
                
                ingress=true
		$like_where  $name_where    $client_where $id_where  $ip_where $ratetable_where $resoure_prefix_has   $privilege  ";
        $sql.=" order by   $order ";
        $sql .= "   	limit '$pageSize' offset '$offset'";
        $results = $this->query($sql);
        $page->setDataArray($results);
        return $page;
    }

    /**
     * 
     * @param unknown_type $res_id
     * @return 查询网关组
     */
    function findResByres_id($res_id) {
        return $this->query("select *,case when trunk_type2 = 1 then (select rate_table_id from resource_prefix where resource_id = resource.resource_id order by id desc limit 1) else null end as did_rate_table_id
        		 from resource where resource_id=$res_id");
    }

    /**
     * 
     * @param unknown_type $res_id
     * @return 查询号码转换
     */
    function findresdirectByRes_id($res_id) {
        return $this->query("select * from resource_direction  where resource_id=$res_id order by direction_id asc");
    }

    /**
     * 
     * @param unknown_type $res_id
     * @return 静态路由表
     */
    function findresproductByRes_id($res_id) {
        return $this->query("select * from resource_product_ref  where resource_id=$res_id");
    }

    /**
     * 查询没有被使用的codecs
     * @param unknown_type $res_id
     */
    function findNousecodecs($res_id) {
        $r = $this->query("select id,name  from  codecs  where  id not in(select codec_id  from  resource_codecs_ref  where  codec_id  is  not null  and  resource_id=$res_id )");
        $size = count($r);
        if ($size == 0) {
            return NULL;
        }
        $l = array();
        for ($i = 0; $i < $size; $i++) {
            $key = $r[$i][0]['id'];
            $l[$key] = $r[$i][0]['name'];
        }
        return $l;
    }

    /**
     * 
     * @param unknown_type $res_id
     * @return 通过res_id查询codes
     */
    function findUsecodecs($res_id) {
        $r = $this->query("select   codec_id,   codecs.name as codec_name   from   resource_codecs_ref    ref  left join codecs  on  codecs.id=ref.codec_id
where  codec_id  is  not null  and  resource_id=$res_id  order by ref.id asc");
        $size = count($r);
        if ($size == 0) {
            return NULL;
        }
        $l = array();
        for ($i = 0; $i < $size; $i++) {
            $key = $r[$i][0]['codec_id'];
            $l[$key] = $r[$i][0]['codec_name'];
        }

        return $l;
    }

    function likequery_egress_report($key, $currPage = 1, $pageSize = 10) {

        $condition = "'%" . $key . "%'";

        require_once 'MyPage.php';
        $page = new MyPage();
        $totalrecords = $this->query("select count(resource_id) as c from resource  where  egress=true  and
	 	 resource.name   ilike $condition 
	 	or resource.alias ilike $condition
	 	or (select count(*)>0 from resource_ip  where resource_ip.resource_id =resource.resource_id and resource_ip.ip::varchar like $condition )
	 		or (select count(*)>0 from reseller  where reseller.reseller_id =resource.reseller_id and name ilike $condition )
	 				or (select count(*)>0 from client  where client.client_id =resource.client_id and name ilike $condition )

	 	");

        $page->setTotalRecords($totalrecords[0][0]['c']); //总记录数
        $page->setCurrPage($currPage); //当前页
        $page->setPageSize($pageSize); //页大小
        //$page = $page->checkRange($page);//检查当前页范围

        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();
        $offset = $currPage * $pageSize;
        $sql = "select resource.alias,resource.resource_id  ,resource.name,cps_limit,capacity, a.ip_cnt,real.cdr_cnt 
		    from  resource
		    left join (select count(*)as  ip_cnt,resource_id from resource_ip    group by  resource_id) a   on  a.resource_id=resource.resource_id
      left join (select count(*)as  cdr_cnt,egress_id from real_cdr    group by  egress_id) real   on  real.egress_id=resource.resource_id

	 	where   egress=true  and resource.name   ilike $condition 
	or resource.alias ilike $condition
	 	or (select count(*)>0 from resource_ip  where resource_ip.resource_id =resource.resource_id and resource_ip.ip::varchar ilike $condition )
	 		or (select count(*)>0 from reseller  where reseller.reseller_id =resource.reseller_id and name ilike $condition )
	 				or (select count(*)>0 from client  where client.client_id =resource.client_id and name ilike $condition )
	order by resource.resource_id  	limit '$pageSize' offset '$offset'";
        $results = $this->query($sql);

        $page->setDataArray($results);
        return $page;
    }

    /**
     * 查看某个host的cdr
     * @param unknown_type $currPage
     * @param unknown_type $pageSize
     */
    public function view_all_cdr($currPage = 1, $pageSize = 100, $res_id) {
        //分页信息
        require_once 'MyPage.php';
        $page = new MyPage();
        $totalrecords = $this->query("select count(real_cdr_id) as c from real_cdr  where  egress_id::text='$res_id'  ");

        $page->setTotalRecords($totalrecords[0][0]['c']); //总记录数
        $page->setCurrPage($currPage); //当前页
        $page->setPageSize($pageSize); //页大小
        //$page = $page->checkRange($page);//检查当前页范围

        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();
        $offset = $currPage * $pageSize;
        $sql = "select  ani,dnis ,ans_time_a,callee_ani,callee_dnis   from  real_cdr
		   where  egress_id::text='$res_id'
	order by real_cdr_id   	limit '$pageSize' offset '$offset'";

        $results = $this->query($sql);

        $page->setDataArray($results);
        return $page;
    }

    function saveHost($account, $id) {
        $count = count($account['ip']);
        $this->bindModel(Array('hasMany' => Array('ResourceIp')));

        if (!empty($account['resource_ip_id'])) {
            $ids = implode(',', $account['resource_ip_id']);
            $this->query("delete from resource_ip where resource_id = $id  and resource_ip_id not in ($ids)");
        } else {
            $this->ResourceIp->deleteAll(Array("resource_id='$id'"));
        }

        for ($i = 0; $i < $count; $i++) {
            $data = Array();
            $data['resource_id'] = $id;
            $data['port'] = $account['port'][$i];
            if (!empty($account['resource_ip_id'][$i])) {
                $data['resource_ip_id'] = $account['resource_ip_id'][$i];
            }
            if (is_ip($account['ip'][$i])) {
                $data['ip'] = $account['ip'][$i];
                if (array_keys_value($account, 'need_register.' . $i)) {
                    $data['ip'] = $account['ip'][$i];
                }
            } else {
                $data['fqdn'] = $account['ip'][$i];
                if (array_keys_value($account, 'need_register.' . $i)) {
                    $data['fqdn'] = $account['ip'][$i];
                }
            }
          
            $this->ResourceIp->save($data);
            $this->ResourceIp->id = false;
        }
    }

    function find_resouce_prefix($res_id = null) {
        //select 
        $whereresource_id = '';
        if (!empty($res_id)) {
            $whereresource_id = "where resource_id=$res_id";
            $rp_sql = "select id ,tech_prefix,route_strategy_id,rate_table_id from resource_prefix $whereresource_id order by id desc";
            $resouce_prefix_list = $this->query($rp_sql);
            return $resouce_prefix_list;
        }
    }

    function find_rate_table() {
        $rate_table_sql = " select rate_table_id as id ,name from rate_table  order  by  name  asc";
        $rate_list = $this->query($rate_table_sql);
        return $rate_list;
    }
    
    function find_transation_fee() {
        $sql = "select id, name from transaction_fee order by name";
        $r = $this->query($sql);
        $size = count($r);
        $l = array();
        for ($i = 0; $i < $size; $i++) {
            $key = $r[$i][0]['id'];
            $l[$key] = $r[$i][0]['name'];
        }
        return $l;
    }

    function find_resource() {
        $sql = "select resource_id as id ,alias as name from resource";
        $resource_list = $this->query($sql);
        return $resource_list;
    }
    
    
    public function find_ingress_resource()
    {
        $sql = "select resource_id as id, alias as name from resource where ingress=true order by alias";
        $result = $this->query($sql);
        $arr = array();
        foreach($result as $item)
        {
            $arr[$item[0]['id']] = $item[0]['name'];
        }
        return $arr;
    }

    function find_route_strategy() {
        $route_sql = "select route_strategy_id as id ,name from route_strategy  order by name  asc";
        $rout_list = $this->query($route_sql);
        return $rout_list;
    }

#保存resource prefix

    function saveResouce($mydata = null, $res_id = null) {
        if (!empty($mydata)) {
            for ($i = 0; $i < count($mydata['id']); $i++) {
                if (!empty($mydata['id'][$i])) {
                    $mydata ['tech_prefix'] [$i] = trim($mydata ['tech_prefix'] [$i]);
                    $sql = "update resource_prefix set tech_prefix='" . $mydata['tech_prefix'][$i] . "',route_strategy_id=" . $mydata['route_strategy_id'][$i] . ",rate_table_id=" . $mydata['rate_table_id'][$i] . " where id=" . $mydata['id'][$i];

                    $this->query($sql);
                } else {

                    $mydata ['tech_prefix'] [$i] = trim($mydata ['tech_prefix'] [$i]);
                    $sql = "insert into resource_prefix(resource_id,tech_prefix,route_strategy_id,rate_table_id) values(" . $res_id . ",'" . $mydata ['tech_prefix'] [$i] . "'," . $mydata ['route_strategy_id'] [$i] . "," . $mydata ['rate_table_id'] [$i] . ") ";
                    echo $sql;

                    $this->query($sql);
                }
            }
        }
    }

}
