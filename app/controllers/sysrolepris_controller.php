<?php

/**
 * 
 * @author root
 *
 */
class SysroleprisController extends AppController {

    var $name = 'Sysrolepris';
    var $helpers = array('javascript', 'html');

    //读取该模块的执行和修改权限
    function index() {
        $this->redirect('view_sysrolepri');
    }

    function active_role($role_id=null, $name=null) {
        if (!$_SESSION['role_menu']['Configuration']['sysrolepris']['model_w']) {
            $this->redirect_denied();
        }
        $id = $this->params['pass'][0];
        $this->Sysrolepri->query("update  role  set  active=true  where  role_id=$id");
        $this->Sysrolepri->query("update  users  set  active=true  where  role_id=$id");
        $name = empty($name) ? 'Sysrolepris' : $name;
        $this->Sysrolepri->create_json_array('', 201, 'The Role ['. $name . '] is actived successfully');
        $this->xredirect('/roles/view');
    }

    function dis_role($role_id=null, $name=null) {
        if (!$_SESSION['role_menu']['Configuration']['sysrolepris']['model_w']) {
            $this->redirect_denied();
        }
        $id = $this->params['pass'][0];
        $this->Sysrolepri->query("update  role  set  active=false  where  role_id=$id");
        $this->Sysrolepri->query("update  users  set  active=false  where  role_id=$id");
        $name = empty($name) ? 'Sysrolepris' : $name;
        $this->Sysrolepri->create_json_array('', 201, 'The Role [' . $name . '] is disabled successfully');
        $this->xredirect('/roles/view');
    }

    function check_form($id) {
        $role_name = $this->data['Sysrole']['role_name'];
        $c = $this->Sysrolepri->check_name($id, $role_name);
        if ($c != 0) {
            $this->Sysrolepri->create_json_array('#RoleRoleName', 101, __('The Role [' . $role_name . '] has already been used', true));
            $this->Session->write('m', $this->Sysrolepri->set_validator());
            return false;
        }
        return true;
    }

    /*
      function add_sysrolepri($id=null){
      $this->pageTitle = "Configuration/Add  Role";
      $this->set('name',$this->select_role_name($id));
      $this->_catch_exception_msg(array($this,'_add_impl'),array('id' => $id));
      $this->render('add_sysrolepri');
      }
     */

    function add_sysrolepri($id=null) {
        if (!$_SESSION['role_menu']['Configuration']['sysrolepris']['model_w']) {
            $this->redirect_denied();
        }
        $this->pageTitle = "Configuration/Add  Role";
        $this->set('role_name', $this->select_role_name($id));
        $this->init_module();
        /* if (!empty($_POST['data']['Sysrole']['role_name']))
          {
          $this->saveOrUpdate_rolepri($role_id=null);
          } */
        $this->init_info_byroleId($id);
        $this->_catch_exception_msg(array($this, '_add_impl'), array('id' => $id));
        //$this->render('add_sysrolepri');
    }

    function _add_impl($params=array()) {
        #post
        $name = $this->data['Sysrole']['role_name'];
        if ($this->RequestHandler->isPost()) {
            $role_id = $this->Sysrolepri->saveOrUpdate_rolepri($this->data, $_POST);
            if (isset($this->data['Sysrole']['role_id']) && !empty($this->data['Sysrole']['role_id'])) {
                //$this->_create_or_update_role_data ($params);
                if ($role_id > 0) {                    
                    $this->set_tip("Then Role[".$name."] modified Successfully!");
                    $this->redirect('add_sysrolepri/' . $role_id);
                }
            } else {
                if (!$this->check_form('')) {
                    return;
                }
                $this->set_tip("The Role[".$name."] is added successfully!");
                $this->redirect('add_sysrolepri/' . $role_id);
                //$this->Sysrolepri->create_json_array('',101,'Please add permission');
            }
        }
        #get
        else {
            if (isset($params['id']) && !empty($params['id'])) {
                $sql = "select count(*) as cnt from sys_role_pri where role_id={$params['id']}";
                $cnt = $this->Sysrolepri->query($sql);
                //pr($this->data);
                //$this->data = $this->Sysrolepri->find('first',array('conditions'=>"Sysrolepri.role_id = {$params['id']}"));

                if (empty($cnt[0][0]['cnt'])) {
                    $this->Sysrolepri->create_json_array('', 101, 'Please add permission');
                    //throw new Exception("Permission denied");
                }
            }
        }
    }

    function _create_or_update_role_data($params=array()) {
        #update
        if (isset($params['id']) && !empty($params['id'])) {
            $id = (int) $params ['id'];
            if (!$this->check_form($id)) {
                return;
            }
            //	$this->data = $this->Sysrolepri->find('first',array('conditions'=>"Sysrolepri.role_id = {$id}"));
            $this->data ['Sysrole'] ['id'] = $id;
            if ($this->Sysrolepri->saveOrUpdate_rolepri($this->data)) {
                $this->set_tip("The Role [".$this->data['Sysrole']['role_name']."] is modified successfully.");
                $this->redirect('add_sysrolepri/' . $id);
            }
        }
        # add
        else {
            if (!$this->check_form('')) {
                return;
            }
            if ($id = $this->Sysrolepri->saveOrUpdate_rolepri($this->data)) {
                //$id = $this->Sysrolepri->getlastinsertId ();
                $this->set_tip("The Role [".$this->data['Sysrole']['role_name']."] is created successfully.");
                $this->redirect('add_sysrolepri/' . $id);
            }
        }
    }

    function set_tip($info) {
        $this->Sysrolepri->create_json_array('', 201, $info);
        $this->Session->write('m', $this->Sysrolepri->set_validator());
    }

    public function beforeFilter() {
        $this->checkSession("login_type"); //核查用户身份
        $login_type = $this->Session->read('login_type');
        if ($login_type == 1) {
            $this->Session->write('executable', true);
            $this->Session->write('writable', true);
        } else {
            $this->Session->write('executable', false);
            $this->Session->write('writable', false);
        }
        parent::beforeFilter();
    }

    //权限测试

    function role_privilege_test() {

        $reseller_id = '';
        $client_id = '';
        $account_id = '';
        $arr = array();
        //初始化不同身份的sql
        $arr['admin'] = "select * from  users";
        $arr['reseller'] = "select * from  users where reseller_id =$reseller_id";
        $arr['client'] = "select * from users where  client_id=$client_id";
        $arr['account'] = "select * from users where account_id=$account_id";
        $_SESSION['arr'] = $arr;
    }

    //登录
    function login() {
        $login_type = $_SESSION['login_type']; //当前用户身份
        $cur_sql = $_SESSION['arr'][$login_type . 'find_user'];

        //查看用户
        $this->query($cur_sql);
    }

    function init_info_byroleId($role_id) {
        $this->set('sysrolepri', $this->Sysrolepri->findSysrolepri($role_id));
    }

    function edit() {
        if (!empty($this->data ['Sysrolepri'])) {
            $flag = $this->Sysrolepri->saveOrUpdate_rolepri($this->data, $_POST); //保存
            if (empty($flag)) {
                $this->set('m', Sysrolepri::set_validator()); //向界面设置验证信息
                $this->set('post', $this->data);
                $this->init_info();
            } else {
                $this->Sysrolepri->create_json_array('#ClientOrigRateTableId', 201, __('Rolehasbeenmodifiedsuccessfully', true));
                $this->Session->write("m", Sysrolepri::set_validator());
                $this->redirect('/roles/view?edit_id=' . $this->params['form']['role_id']); // succ
            }
        } else {
            $this->Sysrolepri->role_id = $this->params ['pass'][0];
            $post = $this->Sysrolepri->read();
            $this->set('post', $post);
            $this->init_info_byroleId($this->params['pass'][0]);
        }
    }

    /**
     * 批量修改角色
     */
    function batchupdate() {
        if (!empty($this->data ['Sysrolepri'])) {
            $error_flag = $this->Sysrolepri->batchupdate($this->data); //保存
            if (empty($error_flag)) {
                $this->redirect(array('action' => 'view'));
            } else {
                $this->set('m', Sysrolepri::set_validator()); //向界面设置验证信息
                $this->set('post', $this->data);
                $this->set('role', $this->Sysrolepri->findRole());
            }
        } else {

            $this->set('role', $this->Sysrolepri->findRole());
        }
    }

    /**
     * 添加
     */
    function add() {
        if (!empty($this->data ['Sysrolepri'])) {
            $flag = $this->Sysrolepri->saveOrUpdate_rolepri($this->data, $_POST); //保存
            if (empty($flag)) {
                //添加失败
                $this->set('m', Sysrolepri::set_validator()); //向界面设置验证信息
                $this->set('post', $this->data);
                $this->init_info();
                $this->redirect(array('controller' => 'roles', 'action' => 'view')); // succ
            } else {
                $this->Sysrolepri->create_json_array('#ClientOrigRateTableId', 201, __('Roleshavecreatesuccess', true));
                $this->Session->write("m", Sysrolepri::set_validator());
                $this->redirect(array('controller' => 'roles', 'action' => 'view')); // succ
            }
        }
        $this->init_info();
    }

    function del() {
        $size = $this->Sysrolepri->del($this->params['pass'][0]);
        if (empty($size)) {
            $this->Sysrolepri->logging(1, 'Role', "Role Name:{$this->params['pass'][1]}");
            $this->Session->write('m', $this->Sysrolepri->create_json(201, /* $this->params['pass'][1].' '. */ __('The Role['.$this->params['pass'][1].'] deleted successfully!', true)));
        } else {
            $this->Session->write('m', $this->Sysrolepri->create_json(101, __('The Role['.$this->params['pass'][1].'] deleted failed!', true)));
        }
        $this->redirect(array('action' => 'view_sysrolepri'));
    }

    /**
     * 
     * 查询角色
     */
    public function view_sysrolepri() {
        $this->pageTitle = "Configuration/Roles";
        $order = $this->_order_condtions(Array('role_name', 'active', 'role_cnt'));
        $temp = isset($_SESSION['paging_row']) ? $_SESSION['paging_row'] : 100;

        empty($_GET['page']) ? $currPage = 1 : $currPage = $_GET['page'];
        empty($_GET['size']) ? $pageSize = $temp : $pageSize = $_GET['size'];

        $_SESSION['paging_row'] = $pageSize;
        //模糊搜索
        if (isset($_GET['searchkey'])) {
            $results = $this->Sysrolepri->likequery($_GET['searchkey'], $currPage, $pageSize);
            $this->set('searchkey', $_GET['searchkey']);
            $this->set('p', $results);
            return;
        }

        //高级搜索 
        if (!empty($this->data['Sysrolepri'])) {


            $results = $this->Sysrolepri->Advancedquery($this->data, $currPage, $pageSize);
            $this->set('search', 'search'); //搜索设置
        } else {
            if (!empty($_REQUEST['edit_id'])) {
                $sql = "select sys_role.*,(select count(*) as  role_cnt from users where role_id = sys_role.role_id)
		    from  sys_role where sys_role.role_id = {$_REQUEST['edit_id']} $order";

                $result = $this->Sysrolepri->query($sql);
                //分页信息

                require_once 'MyPage.php';
                $results = new MyPage ();
                $results->setTotalRecords(1); //总记录数
                $results->setCurrPage(1); //当前页
                $results->setPageSize(1); //页大小
                $results->setDataArray($result);
                $this->set('edit_return', true);
            } else {
                //
                $results = $this->Sysrolepri->findAll($currPage, $pageSize, $order);
            }
        }
        $this->set('p', $results);
    }

    public function select_role_name($id=null) {

        if (!empty($id)) {
            $sql = "select role_name as role_name,view_all,delete_invoice,delete_payment,delete_credit_note,delete_debit_note,reset_balance,modify_credit_limit,modify_min_profit from sys_role where role_id=$id";
            $result = $this->Sysrolepri->query($sql);
            if (!empty($result)) {
                return $result;
            }
        }
    }

    public function _users_arr($role_id=null) {
        if (!empty($role_id)) {
            
        }
    }

//获取用户登录角色权限模块
    public function init_module() {
        if (true) {
            $return = array();
            $sql = "select sys_pri.*,  sys_module.module_name from sys_pri left join sys_module on sys_pri.module_id = sys_module.id where sys_pri.flag = true  order by sys_module.order_num asc";
            $list = $this->Sysrolepri->query($sql);
            if (!empty($list)) {
                foreach ($list as $k => $v) {
                    $return[$v[0]['module_name']][] = $v[0];
                }
            }
            $results = $return;
            //$_SESSION['role_menu'] = $return;
        }
        $this->set('sysmodule', $results);
    }

    function add_sysrole($role_id=null) {
        $this->loadModel('Sysrole');
        $role_id = empty($this->params['pass'][0]) ? null : $this->params['pass'][0];
        //var_dump($this->data);exit;
        if (!empty($this->data ['Sysrole'])) {
            //pr($_POST); exit();
            $return = $this->Sysrolepri->saveOrUpdate_role($this->data, $_POST); //保存
            if (!empty($return)) {
                $this->set('post', $this->data);
                $this->Sysrole->create_json_array('', 201, 'Add successfully');
                //$this->Session->write('m',Sysrole::set_validator());
                //$this->redirect ( array ('controller' => 'sysrolepris', 'action' => 'view_sysrolepri') ); // succ
            } else {
                $this->Syspri->create_json_array('', 101, 'Add fail');
            }
        }
    }

}

?>
