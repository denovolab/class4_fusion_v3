<?php

class RoutestrategysController extends AppController {

    var $name = 'Routestrategys';
    var $uses = array('Client', 'Dynamicroute', 'Resource', 'DynamicRoute', 'Routestrategy', 'Product', 'Productitem');

    function test_case() {
        $this->Routestrategy->update_route();
    }

    function index() {
        $this->redirect('strategy_list');
    }

//上传成功 记录上传
    public function upload_code2() {
        if (!$_SESSION['role_menu']['Routing']['routestrategys']['model_x']) {
            $this->redirect_denied();
        }
        $code_deck_id = $_POST['upload_table_id'];
        $code_name = $_POST['code_name'];
        $list = $this->Routestrategy->import_data("Upload Routing Strategies "); //上传数据
        $this->Routestrategy->create_json_array("", 201, 'Uploaded Successfully');
        $this->Session->write('m', Routestrategy::set_validator());
        $this->redirect('/importlogs/view'); //验证上传数据
    }

    //上传	
    public function import_rate() {
        if (!$_SESSION['role_menu']['Routing']['routestrategys']['model_x']) {
            $this->redirect_denied();
        }
        $rate_table_id = $this->params['pass'][0];
        $list = $this->Routestrategy->query("select name   from  route_strategy where   route_strategy_id=$rate_table_id ");
        $this->set("code_name", array_keys_value($list, '0.0.name'));
        $this->set("rate_table_id", $rate_table_id);
    }

    public function download_rate() {
        if (!$_SESSION['role_menu']['Routing']['routestrategys']['model_x']) {
            $this->redirect_denied();
        }
        Configure::write('debug', 0);
        $rate_table_id = $this->params['pass'][0];
        $download_sql = "select    digits,route_type,dynamic_route_id,static_route_id	from  route  where route_strategy_id=$rate_table_id";
        $this->Routestrategy->export__sql_data('EXport Routing Strategies ', $download_sql, 'route');
        $this->layout = '';
    }

//读取该模块的执行和修改权限
    public function beforeFilter() {
        $this->checkSession("login_type"); //核查用户身份
        $login_type = $this->Session->read('login_type');
        if ($login_type == 1) {
            $this->Session->write('executable', true);
            $this->Session->write('writable', true);
        } else {
            $limit = $this->Session->read('sst_config_ClientGroup');
            $this->Session->write('executable', $limit['executable']);
            $this->Session->write('writable', $limit['writable']);
        }
        parent::beforeFilter();
    }

    public function dynamic_strategy_list($dynamic_id) {
        $this->pageTitle = "Routing/Routing Plan ";
        $currPage = 1;
        $pageSize = 100;
        $search = null;
        if (!empty($_REQUEST ['page'])) {
            $currPage = $_REQUEST ['page'];
        }
        if (!empty($_REQUEST ['size'])) {
            $pageSize = $_REQUEST ['size'];
        }
        if (!empty($_REQUEST['search'])) {
            $search = $_REQUEST['search'];
            $this->set('search', $search);
        }
        if (!empty($_REQUEST['edit_id'])) {
            $sql = "select *,(select count(route_id) from route where route_strategy_id = route_strategy.route_strategy_id) as routes
				 from route_strategy where route_strategy_id = {$_REQUEST['edit_id']}
	  	";
            $result = $this->Routestrategy->query($sql);
            require_once 'MyPage.php';
            $results = new MyPage ();
            $results->setTotalRecords(1); //总记录数
            $results->setCurrPage(1); //当前页
            $results->setPageSize(1); //页大小
            $results->setDataArray($result);
            $this->set('edit_return', true);
        } else {
            $results = $this->Routestrategy->getAll_dynamic($dynamic_id, $currPage, $pageSize, $search, array_keys_value($this->params, 'url.id'), $this->_order_condtions(array('route_strategy_id', 'name', 'routes')), $this->_get('dynamic_route_id'));
        }
        $this->set('p', $results);
    }
    
    public function get_products($page, $search_name='')
    {
        Configure::write('debug', 0);
        $pageSize = 200;
        $this->autoLayout = false;    
        $reseller_id = $this->Session->read('sst_reseller_id');
        $products = $this->Routestrategy->get_products($reseller_id, $search_name,$page, $pageSize);
        $this->set('products', $products);
    }
    
    public function get_dynamics($page, $search_name='')
    {
        Configure::write('debug', 0);
        $pageSize = 200;
        $this->autoLayout = false;    
        $reseller_id = $this->Session->read('sst_reseller_id');
        $dynamics = $this->Routestrategy->get_dynamics($reseller_id, $search_name,$page, $pageSize);
        $this->set('dynamics', $dynamics);
    }

    public function strategy_list() {
        $this->pageTitle = "Routing/Routing Plan ";
        $currPage = 1;
        $pageSize = 100;
        $search = null;
//		if (! empty ( $_REQUEST ['page'] )) {
//			$currPage = $_REQUEST ['page'];
//		}
//		if (! empty ( $_REQUEST ['size'] )) {
//			$pageSize = $_REQUEST ['size'];
//		}
        if (!empty($_REQUEST['search'])) {
            $search = $_REQUEST['search'];
            $this->set('search', $search);
        }
        $temp = isset($_SESSION['paging_row']) ? $_SESSION['paging_row'] : 100;

        empty($_GET['page']) ? $currPage = 1 : $currPage = $_GET['page'];
        empty($_GET['size']) ? $pageSize = $temp : $pageSize = $_GET['size'];

        $_SESSION['paging_row'] = $pageSize;
        if (!empty($_REQUEST['edit_id'])) {
            $sql = "select *,(select count(*) from (select resource_id from resource_prefix where route_strategy_id = route_strategy.route_strategy_id and resource_id is not null group by resource_id) as t) as routes
				 from route_strategy where route_strategy_id = {$_REQUEST['edit_id']}
	  	";
            $result = $this->Routestrategy->query($sql);
            require_once 'MyPage.php';
            $results = new MyPage ();
            $results->setTotalRecords(1); //总记录数
            $results->setCurrPage(1); //当前页
            $results->setPageSize(1); //页大小
            $results->setDataArray($result);
            $this->set('edit_return', true);
        } else {
            $results = $this->Routestrategy->getAll($currPage, $pageSize, $search, array_keys_value($this->params, 'url.id'), $this->_order_condtions(array('route_strategy_id', 'name', 'routes')), $this->_get('dynamic_route_id'));
        }
        $this->set('p', $results);
    }

    /**
     * 查询策略详细信息
     */
    public function routes_list($id) {
        $this->pageTitle = "Routing Plan ";
        $currPage = 1;
        $pageSize = 100;
        $search = null;

        if (!empty($_REQUEST ['page'])) {
            $currPage = $_REQUEST ['page'];
        }

        if (!empty($_REQUEST ['size'])) {
            $pageSize = $_REQUEST ['size'];
        }

        if (!empty($_REQUEST['search'])) {
            $search = $_REQUEST['search'];
            $this->set('search', $search);
        }
        $reseller_id = $this->Session->read('sst_reseller_id');

        if (!empty($_REQUEST['edit_id'])) {
            $sql = "select
					route_id,(select name from product where product_id = route.static_route_id) as static_route,static_route_id, 
					(select name from dynamic_route where dynamic_route_id = route.dynamic_route_id) as dynamic_route,dynamic_route_id,
                                        (select name from product where product_id = route.intra_static_route_id) as intra_static_route,intra_static_route_id,
                                        (select name from product where product_id = route.inter_static_route_id) as inter_static_route,inter_static_route_id,
					(select name from route_strategy where route_strategy_id = route.route_strategy_id) as strategy,
					route_type,digits from route where route_id = {$_REQUEST['edit_id']}
	  		";
            $result = $this->Routestrategy->query($sql);
            //分页信息
            require_once 'MyPage.php';
            $results = new MyPage ();
            $results->setTotalRecords(1); //总记录数
            $results->setCurrPage(1); //当前页
            $results->setPageSize(1); //页大小
            $results->setDataArray($result);
            $this->set('edit_return', true);
        } else {

            $results = $this->Routestrategy->getAllRoutes($currPage, $pageSize, $search, $id);
        }
        $this->set('p', $results);

        $info = $this->Routestrategy->getAddInfo($reseller_id);
        /*
        $inter_intra_route = $info[0];
        array_unshift($inter_intra_route, array(
            array('product_id' => '', 'name' => '')
        ));
        $jur_countrys = $info[2];
        array_unshift($jur_countrys, array(
            array('id' => '', 'name' => '')
        ));
        $this->set('products', str_ireplace("\"", "'", json_encode($info[0])));
        $this->set('dynamics', str_ireplace("\"", "'", json_encode($info[1])));
        $this->set('inter_intra', str_ireplace("\"", "'", json_encode($inter_intra_route)));
        */
        array_unshift($info, array(
            array('id' => '', 'name' => '')
        ));
        $this->set('jur_country', str_ireplace("\"", "'", json_encode($info)));
        $this->set('id', $id);
        $this->set('rs_name', $this->Routestrategy->select_name($id));



        $carriers = $this->Dynamicroute->query("select distinct 
                                                        client.client_id as id, client.name 
                                                        from client
                                                        inner join resource on client.client_id = resource.client_id 
                                                        where resource.egress = true
                                                        order by client.name");
        $this->set('carriers', $carriers);
        $this->set('user', $this->Dynamicroute->findAllUser());
        //$this->set('p',$this->Dynamicroute->findAll($order));
    }

    /**
     * 添加路由策略
     */
    public function add() {
        if (!$_SESSION['role_menu']['Routing']['routestrategys']['model_w']) {
            $this->redirect_denied();
        }
        Configure::write('debug', 0);
        $qs = $this->Routestrategy->add();
        $this->Routestrategy->log('add_routestrategy');
        echo $qs;
    }

    /**
     * 修改路由策略
     */
    public function update() {
        if (!$_SESSION['role_menu']['Routing']['routestrategys']['model_w']) {
            $this->redirect_denied();
        }
        Configure::write('debug', 0);
        $qs = $this->Routestrategy->update();
        $this->Routestrategy->log('update_routestrategy');
        echo $qs;
    }

    public function test() {
        Configure::write('debug', 2);
        $_REQUEST['digits'] = '6665';
        $_REQUEST['static_route_id'] = '7049';
        $_REQUEST['dynamic_route_id'] = '';

        $_REQUEST['lnp'] = 'true';
        $_REQUEST['lrn_block'] = 'on';
        $_REQUEST['dnis_only'] = 'on';



        $_REQUEST['route_type'] = '1';
        $_REQUEST['pid'] = '242';

        $qs = $this->Routestrategy->add_route();
        echo $qs;
    }

    public function add_route() {
        if (!$_SESSION['role_menu']['Routing']['routestrategys']['model_w']) {
            $this->redirect_denied();
        }
        Configure::write('debug', 0);
        $qs = $this->Routestrategy->add_route();
        $this->Routestrategy->log('add_route');
        echo $qs;
    }

    public function update_route() {
        if (!$_SESSION['role_menu']['Routing']['routestrategys']['model_w']) {
            $this->redirect_denied();
        }
        Configure::write('debug', 0);
        $qs = $this->Routestrategy->update_route();
        $this->Routestrategy->log('update_route');
        echo $qs;
    }

    public function del_strategy($id, $control = '') {
        if (!$_SESSION['role_menu']['Routing']['routestrategys']['model_w']) {
            $this->redirect_denied();
        }
        //删除时名称提示
        $tip = '';
        switch ($id) {
            case 'all':
                $tip = '';
                break;
            case 'selected':
                $arrName = $this->Routestrategy->getNameByids($_REQUEST['ids']);
                foreach ($arrName as $name) {
                    $tip.=$name[0]['name'] . ",";
                }
                $tip = '[' . substr($tip, 0, -1) . ']';
                break;
            default:
                $arrResult = $this->Routestrategy->select_name($id);
                $name = $arrResult[0][0]['name'];
                $tip = '[' . $name . ']';
                $this->Routestrategy->logging(1, 'Routing Plan', "Routing Plan Name:{$name}");
        }

        if ($id == 'all') {
            $id = "select route_strategy_id from route_strategy";
        }

        if ($id == 'selected') {
            $id = $_REQUEST['ids'];
        }

        $trunk_ingress_use_count = $this->Routestrategy->query("SELECT COUNT(*) FROM resource WHERE route_strategy_id in ({$id})");

        if ($trunk_ingress_use_count[0][0]['count'] > 0) {
            $this->Routestrategy->create_json_array('', 101, __('Routing strategies is being used; therefore, it cannot be deleted.', true));
        } else {
            $this->Routestrategy->query("delete   from  route    where route_strategy_id in( $id)");
            $this->Routestrategy->query("update  resource set  route_strategy_id=null     where route_strategy_id in ($id)");
            if ($this->Routestrategy->deleteAll(Array("route_strategy_id in ($id)"))) {
                $this->Routestrategy->log('delete_routestrategy');
                $this->Routestrategy->create_json_array('', 201, 'The Route Strategiest ' . $tip . ' is deleted successfully.');
            } else {
                $this->Routestrategy->create_json_array('', 101, __('Fail to delete Route plan.', true));
            }
        }
        $this->Session->write('m', Routestrategy::set_validator());
        $this->redirect('/routestrategys/strategy_list');
    }

    public function del_strategy2($id, $flag) {
        if (!$_SESSION['role_menu']['Routing']['routestrategys']['model_w']) {
            $this->redirect_denied();
        }

        if ($id == 'all') {
            $id = "select route_strategy_id from route_strategy";
        }

        if ($id == 'selected') {
            $id = $_REQUEST['ids'];
        }

        if ($flag != 0) {
            $id = "select route_id from route where static_route_id = {$flag}";
        }

        $trunk_ingress_use_count = $this->Routestrategy->query("SELECT COUNT(*) FROM resource WHERE route_strategy_id in ({$id})");

        if ($trunk_ingress_use_count[0][0]['count'] > 0) {
            $this->Routestrategy->create_json_array('', 101, __('Routing strategies is being used; therefore, it cannot be deleted.', true));
        } else {

            $this->Routestrategy->query("delete   from  route    where route_strategy_id in( $id)");
            $this->Routestrategy->query("update  resource set  route_strategy_id=null     where route_strategy_id in ($id)");
            if ($this->Routestrategy->deleteAll(Array("route_strategy_id in ($id)"))) {
                $this->Routestrategy->log('delete_routestrategy');
                $this->Routestrategy->create_json_array('', 201, 'The Route plan is deleted  successfully !');
            } else {
                $this->Routestrategy->create_json_array('', 101, __('del_fail', true));
            }
        }
        $this->Session->write('m', Routestrategy::set_validator());
        if ($flag != 0) {
            $this->redirect('/products/product_list');
        } else {
            $this->redirect('/routestrategys/strategy_list');
        }
    }

    public function del_route($id, $pid) {
        if (!$_SESSION['role_menu']['Routing']['routestrategys']['model_w']) {
            $this->redirect_denied();
        }
        if ($id == 'all') {
            $id = "select route_id from route where route_strategy_id = $pid";
        }
        if ($id == 'selected') {
            $id = $_REQUEST['ids'];
        }
        $delete_logs = $this->Routestrategy->query("select digits, 
(select name from route_strategy 
where route_strategy.route_strategy_id = route.route_strategy_id) as name 
from route where route_id in ({$id})");
        
        $qs = $this->Routestrategy->query("delete from route where route_id in( $id)");
        if (count($qs) == 0) {
        	$messages = array();
        	foreach($delete_logs as $delete_log)
        	{
        		array_push($messages, "Name:" . $delete_log[0]['name'] . " Prefix:" . $delete_log[0]['digits']);
        	}
        	$message = implode(',', $messages);
        	$this->Routestrategy->logging(1, 'Route', $message);
            $this->Routestrategy->log('delete_route');
            $this->Routestrategy->create_json_array('', 201, __('del_suc', true));
        } else {
            $this->Routestrategy->create_json_array('', 101, __('del_fail', true));
        }

        $this->Session->write('m', Routestrategy::set_validator());
        $this->redirect('/routestrategys/routes_list/' . $pid);
    }

    public function check_routing_plan($name = null) {
        Configure::write('debug', 0);
        $this->layout = 'ajax';

        if (!empty($name)) {
            $sql = "select count(*) as count_num from route_strategy  where  name='$name'";
            $conut = $this->Routestrategy->query($sql);
            if ($conut[0][0]['count_num'] > 0) {
                echo "false";
            } else {
                echo "true";
            }
        }
    }

    function massedit() {
        if (!$_SESSION['role_menu']['Routing']['routestrategys']['model_w']) {
            $this->redirect_denied();
        }
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        $this->autoRender = FALSE;
        $temp = '';
        if ($_POST['routetype'] == 1) {
            $temp = "dynamic_route_id={$_POST['dynamic']},static_route_id=null";
        } elseif ($_POST['routetype'] == 2) {
            $temp = "static_route_id={$_POST['static']},dynamic_route_id=null";
        } elseif ($_POST['routetype'] == 3) {
            $temp = "dynamic_route_id={$_POST['dynamic']},static_route_id={$_POST['static']}";
        }
        $_POST['lnp'] = isset($_POST['lnp']) && $_POST['lnp'] == 'on' ? 'true' : 'false';
        $_POST['block'] = isset($_POST['block']) && $_POST['block'] == 'on' ? 'true' : 'false';
        $_POST['dnis'] = isset($_POST['dnis']) && $_POST['dnis'] == 'on' ? 'true' : 'false';
        $this->Routestrategy->query("UPDATE route SET {$temp},lnp={$_POST['lnp']}, 
                    lrn_block={$_POST['block']}, dnis_only={$_POST['dnis']}, route_type={$_POST['routetype']} WHERE route_id IN ({$_POST['idx']})");
    }

    function addDynamicRouting() {
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        $this->autoRender = FALSE;



        //return var_dump($_POST);

        if (!$_SESSION['role_menu']['Routing']['dynamicroutes']['model_w']) {
            $this->redirect_denied();
        }

        if (!empty($this->data ['Dynamicroute'])) {

            $isSameName = $this->Dynamicroute->check_name("", $this->data ['Dynamicroute']['name']);

            if ($isSameName) {
                return "isHavaName";
            } else {
                $flag = $this->Dynamicroute->saveOrUpdate($this->data, $_POST);
                $ids = $this->Dynamicroute->findIdByName($this->data ['Dynamicroute']['name']);
                return $ids;
            }

            //保存
            if (!empty($flag)) {
                return '';
            }
        } else {
            return '';
        }
    }

    function addStaticRouting() {
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        $this->autoRender = FALSE;

        if (!$_SESSION['role_menu']['Routing']['products']['model_w']) {
            $this->redirect_denied();
        }

        $name = trim($_POST['name']);
        if (empty($name)) {

            return 'nameIsNull';
            /* $this->Session->write('m', $this->Product->create_json(101, __('The field name cannot be NULL.', true)));
              $this->Session->write('product_name', ' ');
              $this->redirect(array('controller' => 'products', 'action' => 'product_list')); */
        }
        $pattern = '/^(\w|\-|\_)*$/';
        if (!preg_match($pattern, $name)) {
            return "nameNotPreg";
            /*
              $this->Session->write('m', $this->Product->create_json(101, __('Name,allowed characters: a-z,A-Z,0-9,-,_,space, maximum of 16 characters in length!', true)));
              $this->Session->write('product_name', $name);
              $this->redirect(array('controller' => 'products', 'action' => 'product_list')); */
        }

        //Length < 30
        if (strlen($name) >= 30) {
            return 'nameLength';
            /* $this->Session->write('m', $this->Product->create_json(101, __('pro_name_len', true)));
              $this->Session->write('product_name', $name);
              $this->redirect(array('controller' => 'products', 'action' => 'product_list')); */
        }

        //Check if the name has already exists or not exists
        $ns = $this->Product->query("select product_id from product where name = '$name'");

        if (count($ns) > 0) {
            return 'nameIsHave';
            /* $this->Session->write('m', $this->Product->create_json(101, __($name . 'is already in use!', true)));
              $this->Session->write('product_name', $name);
              $this->redirect(array('controller' => 'products', 'action' => 'product_list')); */
        }

        $rese_id = $this->Session->read('sst_reseller_id');
        $result = $this->Product->addProduct($name, $rese_id);
        if ($result) {
            $this->Product->log('add_product');
            $product_id = $this->Product->get_id("'" . $name . "'");
            return $product_id[0][0]['product_id'];
            //$this->Session->write('m', $this->Product->create_json(201, __('The Static Route [' . $name . '] is created successfully.', true)));
            //$this->redirect(array('controller' => 'products', 'action' => 'route_info', $result));
        } else { //添加失败
            return 'no';
            /* $this->Session->write('m', $this->Product->create_json(101, __('pro_add_failed', true)));
              $this->Session->write('product_name', $name);
              $this->redirect(array('controller' => 'products', 'action' => 'product_list')); */
        }
    }

    public function add_static_route($product_id = null) {
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        $this->autoRender = FALSE;

        if (!$_SESSION['role_menu']['Routing']['products']['model_w']) {
            $this->redirect_denied();
        }


        if (!empty($this->params ['form'])) { //保存数据
            if (empty($product_id)) {
                $id = $this->params ['form'] ['product_id']; //Product ID
            } else {
                $id = $product_id;
                $id = $this->params ['form'] ['product_id'] = $product_id;
            }
            $res = $this->validate_route($id);
            if ($res == 'yes') {
                if (!$this->Productitem->saveinto($this->params ['form'])) {
                    return 'no';
                } else {
                    return "yes";
                }
            } else {
                return $res;
            }
        }
    }

    private function validate_route($id) {
        $digits = empty($this->params['form']['digits']) ? 'null' : "'" . $this->params['form']['digits'] . "'"; //Prefix
        if ($digits != 'null') {
            if (!preg_match("/[0-9]+/", $digits)) {
                return "codePreg";
            }
        } else {
            return "codeNll";
        }

        $ds = $this->Product->query("select item_id from product_items where digits = $digits  and product_id = '$id'");
        if (count($ds) > 0) {
            return 'codeIsHave';
        }

        if ($this->params['form']['strategy'] == 0) {
            $percentageNum = 0;
            foreach ($this->params['form']['percentage'] as $percentage) {
                if (!preg_match('/^[0-9]+$/', $percentage)) {
                    return "PercentPreg";
                }
                $percentageNum += $percentage;
            }

            if ($percentageNum != 100) {
                return "PercentNo100";
            }
        }

        return 'yes';
    }

}

?>