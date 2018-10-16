<?php

class DynamicroutesController extends AppController {

    var $name = 'Dynamicroutes';
    var $helpers = array('javascript', 'html', 'AppDynamicRoute', 'AppProduct', 'Common');
    var $uses = array('Client', 'Dynamicroute', 'Resource', 'DynamicRoute', 'Gatewaygroup');

    function index() {
        $this->redirect('view');
    }

    //读取该模块的执行和修改权限
    public function beforeFilter() {
        $this->checkSession("login_type"); //核查用户身份
        $login_type = $this->Session->read('login_type');
        if ($login_type == 1) {
            $this->Session->write('executable', true);
            $this->Session->write('writable', true);
        } else {
            $limit = $this->Session->read('sst_route_DRPolicies');
            $this->Session->write('executable', $limit['executable']);
            $this->Session->write('writable', $limit['writable']);
        }
        parent::beforeFilter();
    }

    function ajax_egress() {
        Configure::write('debug', 0);
        $this->set('extensionBeans', $this->Dynamicroute->findEgress($this->params['pass'][0]));
    }

    /**
     * 初始化信息
     */
    function init_info() {
        $this->set('egresses', $this->Resource->findAllEgress());
        $this->set('user', $this->Dynamicroute->findAllUser());
        $this->set('clients', $this->Client->find_all_valid());
    }
    
    public function get_all_egress()
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $sql = "SELECT 
resource_id, client_id from resource
WHERE egress=true and trunk_type2=0";
        $data = $this->Dynamicroute->query($sql);
        echo json_encode($data);
    }

    /**
     * 编辑客户信息
     */
    function edit($id) {
        if (!$_SESSION['role_menu']['Routing']['dynamicroutes']['model_w']) {
            $this->redirect_denied();
        }
        $this->data['Dynamicroute']['dynamic_route_id'] = $id;
        if (!empty($this->data)) {
            $flag = $this->Dynamicroute->saveOrUpdate($this->data, $_POST); //保存
            if (empty($flag)) {
                //有错误信息
                $this->set('m', Dynamicroute::set_validator()); //向界面设置验证信息
                $this->set('post', $this->data);
                $this->init_info();
                $this->xredirect(array('controller' => 'dynamicroutes', 'action' => 'view')); // succ
            } else {
                //操作成功
                $this->Dynamicroute->log('edit_dynamic_route');
                //$this->Dynamicroute->create_json_array('',201,__('Edit Dynamic Routing successfully!',true));
                $this->Dynamicroute->logging(2, 'Dynamic Route', "Dynamic Route Name:{$this->data['Dynamicroute']['name']}");
                $this->Dynamicroute->create_json_array('', 201, __('The Dynamic Routing [' . $this->data['Dynamicroute']['name'] . '] is modified successfully!', true));
                $this->Session->write('m', Dynamicroute::set_validator());
                $this->xredirect(array('controller' => 'dynamicroutes', 'action' => 'view')); // succ
            }
        } else {
            
        }
        $this->set('post', $this->Dynamicroute->find('first', array('conditions' => 'dynamic_route_id = ' . $this->params ['pass'][0])));
        $this->set('res_dynamic', $this->Dynamicroute->findEgressbydynamic_id($this->params ['pass'][0]));
        $this->init_info();
        $this->xredirect(array('controller' => 'dynamicroutes', 'action' => 'view')); // succ
    }

    /**
     * 添加客户信息
     */
    function add() {
        if (!$_SESSION['role_menu']['Routing']['dynamicroutes']['model_w']) {
            $this->redirect_denied();
        }
        if (!empty($this->data ['Dynamicroute'])) {
            $flag = $this->Dynamicroute->saveOrUpdate($this->data, $_POST); //保存
            if (empty($flag)) {
                $this->set('m', Dynamicroute::set_validator()); //向界面设置验证信息
                $this->set('post', $this->data);
                $this->init_info();
                $this->xredirect(array('controller' => 'dynamicroutes', 'action' => 'view')); // succ
            } else {
                $this->Dynamicroute->log('add_dynamic_route');
                $this->Dynamicroute->logging(0, 'Dynamic Route', "Dynamic Route Name:{$this->data['Dynamicroute']['name']}");
                $this->Dynamicroute->create_json_array("", 201, 'The Dynamic Routing [' . $this->data['Dynamicroute']['name'] . '] is created successfully!');
                $this->Session->write('m', Dynamicroute::set_validator());
                $this->xredirect(array('controller' => 'dynamicroutes', 'action' => 'view')); // succ
            }
        } else {
            $this->init_info();
            $this->xredirect(array('controller' => 'dynamicroutes', 'action' => 'view')); // succ
        }
    }

    function del() {
        if (!$_SESSION['role_menu']['Routing']['dynamicroutes']['model_w']) {
            $this->redirect_denied();
        }
        $roueplan_use_count = $this->Dynamicroute->query("SELECT COUNT(*) FROM route where dynamic_route_id = {$this->params['pass'][0]}");
        if ($roueplan_use_count[0][0]['count'] > 0) {
            $this->Dynamicroute->create_json_array("", 101, 'Dynamic routing is being used; therefore, it cannot be deleted.');
        } else {
            $name = $this->params['pass'][1];
            $this->Dynamicroute->del($this->params['pass'][0]);
            $this->Dynamicroute->log('delete_dynamic_route');
            $this->Dynamicroute->logging(1, 'Dynamic Route', "Dynamic Route Name:{$name}");
            $this->Dynamicroute->create_json_array("", 201, 'The Dynamic Route [' . $this->params['pass'][1] . '] is deleted successfully.');
        }
        $this->Session->write('m', Dynamicroute::set_validator());
        $this->redirect(array('action' => 'view'));
    }

    /**
     * 查询
     */
    function view() {
        $this->pageTitle = "Routing/Dynamic Routing";
        $this->init_info();
        $order = $this->_order_condtions_all(Array('dynamic_route_id', 'use_count', 'routing_rule', 'name', 'time_profile_id'));
        $carriers = $this->Dynamicroute->query("select distinct 
                                                        client.client_id as id, client.name 
                                                        from client
                                                        inner join resource on client.client_id = resource.client_id 
                                                        where resource.egress = true
                                                        order by client.name");
        $pdata = $this->Dynamicroute->findAll($order);
        
        foreach ($pdata->dataArray as &$item)
        {
            $item[0]['slist'] = $this->Gatewaygroup->searchdyna($item[0]['dynamic_route_id']);
        }
        
        
        $this->set('carriers', $carriers);
        $this->set('p', $pdata);
        $this->set('routing_rule', $this->_get('routing_rule'));
    }

    public function qos_import($dynamic_id) {
        define("FILEPATH", APP . "upload/dynamic_up/");
        if ($this->RequestHandler->ispost()) {
            if (is_uploaded_file($_FILES['upfile']['tmp_name'])) {
                $dest_file = FILEPATH . uniqid('qos') . '.csv';
                $result = move_uploaded_file($_FILES['upfile']['tmp_name'], $dest_file);
                if ($result) {
                    $this->handle_import_qos($dest_file);
                }
            }
        }
    }

    public function handle_import_qos($dest_file) {
        
    }

    public function qos($dynamic_id) {
        $temp = isset($_SESSION['paging_row']) ? $_SESSION['paging_row'] : 100;
        empty($_GET['page']) ? $currPage = 1 : $currPage = $_GET['page'];
        empty($_GET['size']) ? $pageSize = $temp : $pageSize = $_GET['size'];
        $_SESSION['paging_row'] = $pageSize;
        require_once 'MyPage.php';
        $search = isset($_GET['search']) ? $_GET['search'] : '';

        $where = "";

        if (isset($_GET['advsearch'])) {
            $where_arr = array();
            if (isset($_GET['asr_min']) && !empty($_GET['asr_min'])) {
                array_push($where_arr, "min_asr >= {$_GET['asr_min']}");
            }
            if (isset($_GET['asr_max']) && !empty($_GET['asr_max'])) {
                array_push($where_arr, "max_asr <= {$_GET['asr_max']}");
            }
            if (isset($_GET['acd_min']) && !empty($_GET['acd_min'])) {
                array_push($where_arr, "min_acd >= {$_GET['acd_min']}");
            }
            if (isset($_GET['acd_max']) && !empty($_GET['acd_max'])) {
                array_push($where_arr, "max_acd <= {$_GET['acd_max']}");
            }
            if (isset($_GET['aloc_min']) && !empty($_GET['aloc_min'])) {
                array_push($where_arr, "min_aloc >= {$_GET['aloc_min']}");
            }
            if (isset($_GET['aloc_max']) && !empty($_GET['aloc_max'])) {
                array_push($where_arr, "max_aloc <= {$_GET['aloc_max']}");
            }
            if (isset($_GET['pdd_min']) && !empty($_GET['pdd_min'])) {
                array_push($where_arr, "min_pdd >= {$_GET['pdd_min']}");
            }
            if (isset($_GET['pdd_max']) && !empty($_GET['pdd_max'])) {
                array_push($where_arr, "max_pdd <= {$_GET['pdd_max']}");
            }
            if (isset($_GET['abr_min']) && !empty($_GET['abr_min'])) {
                array_push($where_arr, "min_abr >= {$_GET['abr_min']}");
            }
            if (isset($_GET['abr_max']) && !empty($_GET['abr_max'])) {
                array_push($where_arr, "max_abr <= {$_GET['abr_max']}");
            }
            $where = implode(' AND ', $where_arr);
        }


        $counts = $this->Dynamicroute->get_qoss_count($dynamic_id, $search, $where);
        $page = new MyPage ();
        $page->setTotalRecords($counts);
        $page->setCurrPage($currPage);
        $page->setPageSize($pageSize);
        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();
        $offset = $currPage * $pageSize;
        $pris = $this->Dynamicroute->get_qoss($dynamic_id, $search, $where, $pageSize, $offset);
        $page->setDataArray($pris);
        $this->set('dynamic_id', $dynamic_id);
        $this->set('p', $page);
    }

    public function create_qos() {
        Configure::write('debug', 0);
        $this->autoLayout = FALSE;
        $this->autoRender = FALSE;
        $dynamic_id = $_POST['dynamic_id'];
        $digits = empty($_POST['digits']) ? '' : $_POST['digits'];
        $min_asr = empty($_POST['min_asr']) ? 'NULL' : $_POST['min_asr'];
        $max_asr = empty($_POST['max_asr']) ? 'NULL' : $_POST['max_asr'];
        $min_abr = empty($_POST['min_abr']) ? 'NULL' : $_POST['min_abr'];
        $max_abr = empty($_POST['max_abr']) ? 'NULL' : $_POST['max_abr'];
        $min_acd = empty($_POST['min_acd']) ? 'NULL' : $_POST['min_acd'];
        $max_acd = empty($_POST['max_acd']) ? 'NULL' : $_POST['max_acd'];
        $min_pdd = empty($_POST['min_pdd']) ? 'NULL' : $_POST['min_pdd'];
        $max_pdd = empty($_POST['max_pdd']) ? 'NULL' : $_POST['max_pdd'];
        $min_aloc = empty($_POST['min_aloc']) ? 'NULL' : $_POST['min_aloc'];
        $max_aloc = empty($_POST['max_aloc']) ? 'NULL' : $_POST['max_aloc'];
        $limit_price = empty($_POST['limit_price']) ? 'NULL' : $_POST['limit_price'];
        
        $count = $this->Dynamicroute->check_digit_count($dynamic_id, $digits);
        if ($count > 0) {
            echo 2;
            return;
        }
        //$dynamic_route= $this->Dynamicroute->findByDynamicId($dynamic_id);
        $this->Dynamicroute->insert_qos($dynamic_id, $digits, $min_asr, $max_asr, $min_abr, $max_abr, $min_acd, $max_acd, $min_pdd, $max_pdd, $max_aloc, $min_aloc, $limit_price);
        $this->Dynamicroute->create_json_array("", 201, __('Successfully!', TRUE));
        $this->Session->write('m', Dynamicroute::set_validator());
        echo 1;
    }

    public function update_qos() {
        Configure::write('debug', 0);
        $this->autoLayout = FALSE;
        $this->autoRender = FALSE;
        $qos_id = $_POST['qos_id'];
        $prefix = empty($_POST['prefix']) ? '' : $_POST['prefix'];
        $min_asr = empty($_POST['min_asr']) ? 'NULL' : $_POST['min_asr'];
        $max_asr = empty($_POST['max_asr']) ? 'NULL' : $_POST['max_asr'];
        $min_abr = empty($_POST['min_abr']) ? 'NULL' : $_POST['min_abr'];
        $max_abr = empty($_POST['max_abr']) ? 'NULL' : $_POST['max_abr'];
        $min_acd = empty($_POST['min_acd']) ? 'NULL' : $_POST['min_acd'];
        $max_acd = empty($_POST['max_acd']) ? 'NULL' : $_POST['max_acd'];
        $min_pdd = empty($_POST['min_pdd']) ? 'NULL' : $_POST['min_pdd'];
        $max_pdd = empty($_POST['max_pdd']) ? 'NULL' : $_POST['max_pdd'];
        $min_aloc = empty($_POST['min_aloc']) ? 'NULL' : $_POST['min_aloc'];
        $max_aloc = empty($_POST['max_aloc']) ? 'NULL' : $_POST['max_aloc'];
        $limit_price = empty($_POST['limit_price']) ? 'NULL' : $_POST['limit_price'];
        $dynamic_id = $_POST['dynamic_id'];
        $count = $this->Dynamicroute->check_digit_count($dynamic_id, $prefix, $qos_id);
        if ($count > 0) {
            echo 2;
            return;
        }
        $this->Dynamicroute->update_qos($qos_id, $prefix, $min_asr, $max_asr, $min_abr, $max_abr, $min_acd, $max_acd, $min_pdd, $max_pdd, $max_aloc, $min_aloc, $limit_price);
        echo 1;
    }

    public function priority($dynamic_id) {
        $temp = isset($_SESSION['paging_row']) ? $_SESSION['paging_row'] : 100;
        empty($_GET['page']) ? $currPage = 1 : $currPage = $_GET['page'];
        empty($_GET['size']) ? $pageSize = $temp : $pageSize = $_GET['size'];
        $_SESSION['paging_row'] = $pageSize;
        $search = isset($_GET['search']) ? $_GET['search'] : '';
        require_once 'MyPage.php';
        $where = "";
        if (isset($_GET['egress_trunk']) && !empty($_GET['egress_trunk'])) {
            $where .= " AND resource_id = {$_GET['egress_trunk']}";
        }
        if (isset($_GET['p_start']) && !empty($_GET['p_start'])) {
            $where .= " AND resource_pri >= {$_GET['p_start']}";
        }
        if (isset($_GET['p_end']) && !empty($_GET['p_end'])) {
            $where .= " AND resource_pri <= {$_GET['p_end']}";
        }
        $counts = $this->Dynamicroute->get_pris_count($dynamic_id, $search, $where);
        $page = new MyPage ();
        $page->setTotalRecords($counts);
        $page->setCurrPage($currPage);
        $page->setPageSize($pageSize);
        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();
        $offset = $currPage * $pageSize;
        $egress_trunks = $this->Dynamicroute->findDynamicAllEgress($dynamic_id);
        $pris = $this->Dynamicroute->get_pris($dynamic_id, $search, $where, $pageSize, $offset);
        $page->setDataArray($pris);
        $this->set('egress_trunks', $egress_trunks);
        $this->set('dynamic_id', $dynamic_id);
        $this->set('p', $page);
    }

    public function create_pri() {
        Configure::write('debug', 0);
        $this->autoLayout = FALSE;
        $this->autoRender = FALSE;
        $dynamic_id = $_POST['dynamic_id'];
        $digits = $_POST['digits'];
        $resource_pri = $_POST['resource_pri'];
        $egress_trunk = $_POST['egress_trunk'];
        $count = $this->Dynamicroute->check_pri_count($dynamic_id, $digits, $egress_trunk);
        if ($count > 0) {
            echo 2;
            return;
        }
        $this->Dynamicroute->insert_pri($dynamic_id, $digits, $resource_pri, $egress_trunk);
        $dynamic_route= $this->Dynamicroute->findByDynamicId($dynamic_id);
        $this->Dynamicroute->create_json_array("", 201, __('Successfully!', TRUE));
        $this->Session->write('m', Dynamicroute::set_validator());
        echo 1;
    }

    public function delete_pri($pri_id, $dynamic_id) {
        Configure::write('debug', 0);
        $this->autoLayout = FALSE;
        $this->autoRender = FALSE;
        $sql = "select digits from dynamic_route_pri where id = {$pri_id} limit 1";
        $messg_info = $this->Dynamicroute->query($sql);
        $this->Dynamicroute->delete_pri($pri_id);
        $this->Dynamicroute->create_json_array("", 201, __('The prefix [' . $messg_info[0][0]['digits'] .'] is deleted successfully', TRUE));
        $this->Session->write('m', Dynamicroute::set_validator());
        $this->redirect("/dynamicroutes/priority/{$dynamic_id}");
    }

    public function delete_qos($qos_id, $dynamic_id) {
        Configure::write('debug', 0);
        $this->autoLayout = FALSE;
        $this->autoRender = FALSE;
        $sql = "select digits from dynamic_route_qos where id = {$qos_id} limit 1";
        $messg_info = $this->Dynamicroute->query($sql);
        $this->Dynamicroute->delete_qos($qos_id);
        $this->Dynamicroute->create_json_array("", 201, __('The prefix [' . $messg_info[0][0]['digits'] .'] is deleted successfully', TRUE));
        $this->Session->write('m', Dynamicroute::set_validator());
        $this->redirect("/dynamicroutes/qos/{$dynamic_id}");
    }

    public function override($dynamic_id) {
        $temp = isset($_SESSION['paging_row']) ? $_SESSION['paging_row'] : 100;
        empty($_GET['page']) ? $currPage = 1 : $currPage = $_GET['page'];
        empty($_GET['size']) ? $pageSize = $temp : $pageSize = $_GET['size'];
        $_SESSION['paging_row'] = $pageSize;
        $search = isset($_GET['search']) ? $_GET['search'] : '';
        require_once 'MyPage.php';
        $where = "";
        if (isset($_GET['egress_trunk']) && !empty($_GET['egress_trunk'])) {
            $where .= " AND resource_id = {$_GET['egress_trunk']}";
        }
        if (isset($_GET['p_start']) && !empty($_GET['p_start'])) {
            $where .= " AND percentage >= {$_GET['p_start']}";
        }
        if (isset($_GET['p_end']) && !empty($_GET['p_end'])) {
            $where .= " AND percentage <= {$_GET['p_end']}";
        }
        $counts = $this->Dynamicroute->get_overrides_count($dynamic_id, $search, $where);
        $page = new MyPage ();
        $page->setTotalRecords($counts);
        $page->setCurrPage($currPage);
        $page->setPageSize($pageSize);
        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();
        $offset = $currPage * $pageSize;
        $egress_trunks = $this->Dynamicroute->findDynamicAllEgress($dynamic_id);
        $overrides = $this->Dynamicroute->get_overrides($dynamic_id, $search, $where, $pageSize, $offset);
        $page->setDataArray($overrides);
        $this->set('egress_trunks', $egress_trunks);
        $this->set('dynamic_id', $dynamic_id);
        $this->set('p', $page);
    }

    public function create_override() {
        Configure::write('debug', 0);
        $this->autoLayout = FALSE;
        $this->autoRender = FALSE;
        $dynamic_id = $_POST['dynamic_id'];
        $digits = $_POST['digits'];
        $percentage = $_POST['percentage'];
        $egress_trunk = $_POST['egress_trunk'];
        $count = $this->Dynamicroute->check_override_count($dynamic_id, $digits, $egress_trunk);
        if ($count > 0) {
            echo 2;
            return;
        }
        $total = $this->Dynamicroute->check_override_total($dynamic_id);
        if (($total + $percentage) > 100) {
            echo 3;
            return;
        }
        $this->Dynamicroute->insert_override($dynamic_id, $digits, $percentage, $egress_trunk);
        $this->Dynamicroute->create_json_array("", 201, __('Successfully!', TRUE));
        $this->Session->write('m', Dynamicroute::set_validator());
        echo 1;
    }

    public function delete_override($override_id, $dynamic_id) {
        Configure::write('debug', 0);
        $this->autoLayout = FALSE;
        $this->autoRender = FALSE;
        $sql = "select digits from dynamic_route_override where id = {$override_id} limit 1";
        $messg_info = $this->Dynamicroute->query($sql);
        $this->Dynamicroute->delete_override($override_id);
        $this->Dynamicroute->create_json_array("", 201, __('The prefix [' . $messg_info[0][0]['digits'] .'] is deleted successfully', TRUE));
        $this->Session->write('m', Dynamicroute::set_validator());
        $this->redirect("/dynamicroutes/override/{$dynamic_id}");
    }

    public function delete_priority($priority_id, $dynamic_id) {
        Configure::write('debug', 0);
        $this->autoLayout = FALSE;
        $this->autoRender = FALSE;
        $sql = "select digits from dynamic_route_pri where id = {$priority_id} limit 1";
        $messg_info = $this->Dynamicroute->query($sql);
        $this->Dynamicroute->delete_priority($priority_id);
        $this->Dynamicroute->create_json_array("", 201, __('The prefix [' . $messg_info[0][0]['digits'] .'] is deleted successfully', TRUE));
        $this->Session->write('m', Dynamicroute::set_validator());
        $this->redirect("/dynamicroutes/priority/{$dynamic_id}");
    }

    public function delete_mul_priority($dynamic_id) {
        Configure::write('debug', 0);
        $this->autoLayout = FALSE;
        $this->autoRender = FALSE;
        $delete_list = $_POST['ids'];
        $this->Dynamicroute->delete_mul_priority($delete_list);
        $this->Dynamicroute->create_json_array("", 201, __('Successfully!', TRUE));
        $this->Session->write('m', Dynamicroute::set_validator());
        $this->redirect("/dynamicroutes/priority/{$dynamic_id}");
    }

    public function update_override() {
        Configure::write('debug', 0);
        $this->autoLayout = FALSE;
        $this->autoRender = FALSE;
        $override_id = $_POST['override_id'];
        $prefix = $_POST['prefix'];
        $percentage = $_POST['percentage'];
        $egress_trunk = $_POST['trunk'];
        $dynamic_id = $_POST['dynamic_id'];
        $count = $this->Dynamicroute->check_override_count($dynamic_id, $prefix, $egress_trunk, $override_id);
        if ($count > 0) {
            echo 2;
            return;
        }
        $total = $this->Dynamicroute->check_override_total($dynamic_id, $override_id);
        if (($total + $percentage) > 100) {
            echo 3;
            return;
        }
        $this->Dynamicroute->update_override($override_id, $prefix, $egress_trunk, $percentage);
        echo 1;
    }

    public function update_pri() {
        Configure::write('debug', 0);
        $this->autoLayout = FALSE;
        $this->autoRender = FALSE;
        $id = $_POST['id'];
        $prefix = $_POST['prefix'];
        $egress_trunk = $_POST['trunk'];
        $resource_pri = $_POST['resource_pri'];
        $dynamic_id = $_POST['dynamic_id'];
        $count = $this->Dynamicroute->check_pri_count($dynamic_id, $prefix, $egress_trunk, $id);
        if ($count > 0) {
            echo 2;
            return;
        }
        $this->Dynamicroute->update_pri($id, $prefix, $resource_pri, $egress_trunk);
        echo 1;
    }

    /**
     * 禁用客户
     */
    function dis_able() {
        if (!$_SESSION['role_menu']['Routing']['dynamicroutes']['model_w']) {
            $this->redirect_denied();
        }
        $id = $this->params['pass'][0];
        $this->Client->dis_able($id);
        $this->redirect(array('action' => 'view'));
    }

    function active() {
        if (!$_SESSION['role_menu']['Routing']['dynamicroutes']['model_w']) {
            $this->redirect_denied();
        }
        $id = $this->params['pass'][0];
        $this->Client->active($id);
        $this->redirect(array('action' => 'view'));
    }

    function ajax_dis_able() {
        $id = $this->params['pass'][0];
        if ($this->Client->dis_able($id)) {
            echo "true";
        }
    }

    function ajax_active() {
        $id = $this->params['pass'][0];
        if ($this->Client->active($id)) {
            echo 'true';
        }
    }

    function checkName($id = null) {
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        $name = $this->_get('name');
        $sql = "select count(*) from dynamic_route where name='$name'";
        if (!empty($id)) {
            $sql.=" and dynamic_route_id <> $id";
        }
        $list = $this->Dynamicroute->query($sql);
        if ($list[0][0]['count'] > 0) {
            echo "false";
        }
    }

    function js_save($id = null) {
        if (!$_SESSION['role_menu']['Routing']['dynamicroutes']['model_w']) {
            $this->redirect_denied();
        }
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        $this->_render_set_options('Client', Array('Client' => Array('order' => 'name asc')));
        
        $resources = $this->Resource->find('all', array(
            'conditions' => array('Resource.egress' => true, 'Resource.trunk_type2' => 0),
        ));
        
        $client_resources = array();
        
        foreach ($resources as $resource_item) {
            $client_resources[$resource_item['Resource']['client_id']][$resource_item['Resource']['resource_id']] = $resource_item['Resource']['alias']; 
        }
        
        $this->set('client_resources', $client_resources);
        
        if ($id) {
            $post = $this->Dynamicroute->find('first', array('conditions' => 'dynamic_route_id = ' . $id));
            $this->set('post', $post);
            $this->set('sel', $this->Dynamicroute->jsresource($id));
            $this->set('res_dynamic', $this->Dynamicroute->findEgressbydynamic_id($this->params ['pass'][0]));
        } else {
            $this->set('post', Array());
        }

        //$this->init_info ();
        $this->set('user', $this->Dynamicroute->findAllUser());
    }

    function massedit() {
        if (!$_SESSION['role_menu']['Routing']['dynamicroutes']['model_w']) {
            $this->redirect_denied();
        }
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        $this->autoRender = FALSE;
        $this->Dynamicroute->query("DELETE FROM dynamic_route_items WHERE dynamic_route_id IN ({$_POST['ids']})");
        $this->Dynamicroute->query("UPDATE dynamic_route SET routing_rule={$_POST['routingrule']},
                                        time_profile_id={$_POST['timeprofile']} WHERE dynamic_route_id IN ({$_POST['ids']})");
        $idx = explode(',', $_POST['ids']);
        if (isset($_POST['trunks'])) {
            foreach ($idx as $id) {
                foreach ($_POST['trunks'] as $val) {
                    $this->Dynamicroute->query("INSERT INTO dynamic_route_items (dynamic_route_id,
                            resource_id) VALUES ({$id}, {$val})");
                }
            }
        }
    }
    
    public function delete_selected() {
        if (!$_SESSION['role_menu']['Routing']['dynamicroutes']['model_w']) {
            $this->redirect_denied();
        }
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        $this->autoRender = FALSE;
        $ids = $_POST['ids'];
        $idss = implode(',', $ids);
        $sql = "DELETE FROM dynamic_route_items WHERE dynamic_route_id in ({$idss}); DELETE FROM dynamic_route WHERE dynamic_route_id in ({$idss});";
        $this->Dynamicroute->query($sql);
    }

}

?>
