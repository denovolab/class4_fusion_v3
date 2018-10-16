<?php

class BlocklistsController extends AppController {

    var $name = 'Blocklists';
    var $uses = Array('ResourceBlock', 'Blocklist');
    var $helpers = array('javascript', 'html', 'appBlocklists');

//读取该模块的执行和修改权限
    public function beforeFilter() {
        $this->checkSession("login_type"); //核查用户身份
        $login_type = $this->Session->read('login_type');
        if ($login_type == 1) {
            //admin
            $this->Session->write('executable', true);
            $this->Session->write('writable', true);
        } else {
            $limit = $this->Session->read('sst_route_blocklist');
            $this->Session->write('executable', $limit['executable']);
            $this->Session->write('writable', $limit['writable']);
        }
        parent::beforeFilter();
    }

    //上传拒绝号码	
    public function import_rate() {
        if (!$_SESSION['role_menu']['Routing']['blocklists']['model_x']) {
            $this->redirect_denied();
        }
    }

//上传成功 记录上传
    public function upload_code2() {
        if (!$_SESSION['role_menu']['Routing']['blocklists']['model_x']) {
            $this->redirect_denied();
        }
        $code_deck_id = $_POST['upload_table_id'];
        $list = $this->Blocklist->import_data(__('UploadBlockList', true)); //上传数据
        $this->Blocklist->create_json_array("", 201, 'UploadBlockList ');
        $this->Session->write('m', Blocklist::set_validator());
        $this->redirect('/importlogs/view'); //验证上传数据
    }

    /**
     * 初始化信息
     */
    function init_info() {
        $reseller_id = $this->Session->read('sst_reseller_id');
        $this->set('ingress', $this->Blocklist->findIngress());
        $this->set('egress', $this->Blocklist->findEgress());
        $this->set('client', $this->Blocklist->findClient());
        $this->set('timeprofiles', $this->Blocklist->getTimeProfiles($reseller_id));
    }

    /**
     * 编辑客户信息
     */
    function edit($id=null, $type='') {
        if (!$_SESSION['role_menu']['Routing']['blocklists']['model_w']) {
            $this->redirect_denied();
        }
        if ($this->RequestHandler->isPost()) {
            if ($this->_render_save_impl($id)) {

                //$this->Blocklist->create_json_array("",201,'Block List,  Edit successfullyfully !');
                $this->Blocklist->create_json_array("", 201, 'The Block digit [' . $this->data['ResourceBlock'][digit] . '] is modified successfully.');
                $this->xredirect(array('controller' => 'blocklists', 'action' => 'index', $type)); // succ
            }
        }
    }

    public function ajax_ingress() {

        Configure::write('debug', 0);
        $this->set('extensionBeans', $this->Blocklist->ajaxfindIngressbyClientId($this->params['pass'][0]));
    }

    public function ajax_egress() {
        Configure::write('debug', 0);
        $client_id = $this->params['pass'][0];
        if (empty($client_id)) {
            $r = $this->Blocklist->query("select resource_id ,alias from resource  where egress  is true order by alias ");
        } else {
            $r = $this->Blocklist->query("select resource_id ,alias from resource  where egress  is true   and client_id =$client_id  order by alias ");
        }


        $html = "<option value=''>ALL</option>";
        foreach ($r as $k => $v) {
            $html.="<option value='{$v[0]['resource_id']}'>{$v[0]['alias']}</option>";
        }

        echo $html;
    }

    /**
     * 添加
     */
    function add($type = 1) {
        if (!$_SESSION['role_menu']['Routing']['blocklists']['model_w']) {
            $this->redirect_denied();
        }
        if ($this->RequestHandler->isPost()) {
            if ($this->_render_save_impl()) {
                $id = $this->ResourceBlock->getlastinsertId();
                //$this->ResourceBlock->create_json_array("",201,'Block List, create successfully !');
                $this->ResourceBlock->create_json_array("", 201, 'The Block List [' . $this->data['ResourceBlock']['ani_prefix'] . '] is created successfully !');
                $this->xredirect(array('controller' => 'blocklists', 'action' => 'index', $type)); // succ
            }
        }
    }

    function _render_save_impl($id=null) {
        $this->_format_save_data($id);

        return $this->ResourceBlock->save($this->data);
    }

    function _format_save_data($id=null) {
        if (!empty($id)) {
            $this->data['ResourceBlock']['res_block_id'] = $id;
        }
    }

    function del($id, $type) {
        if (!$_SESSION['role_menu']['Routing']['blocklists']['model_w']) {
            $this->redirect_denied();
        }
        $this->_render_js_save_impl($id);        
        if ($this->ResourceBlock->del($id)) {
            $this->Session->write('m', $this->Blocklist->create_json(201, 'The Block list ['.$this->data['ResourceBlock']['digit'].'] is deleted successfully!'));
            $this->xredirect(array('action' => 'index', $type));
        } else {
            $this->Session->write('m', $this->Blocklist->create_json(201, 'Fail to delete Block list.'));
            $this->xredirect(array('action' => 'index', $type));
        }
    }

    /**
     * 查询客户
     */
    public function view() {
        $this->init_info();
        empty($_GET['page']) ? $currPage = 1 : $currPage = $_GET['page'];
        empty($_GET['size']) ? $pageSize = 100 : $pageSize = $_GET['size'];
        //模糊搜索
        if (isset($_POST['searchkey'])) {
            $results = $this->Blocklist->likequery($_POST['searchkey'], $currPage, $pageSize);
            $this->set('searchkey', $_POST['searchkey']);
            $this->set('p', $results);

            return;
        }

        //高级搜索 
        if (!empty($this->data['Blocklist'])) {


            $results = $this->Blocklist->Advancedquery($this->data, $currPage, $pageSize);
            $this->set('search', 'search'); //搜索设置
        } else {
            if (!empty($_REQUEST['edit_id'])) {
                $sql = "select resource_block.res_block_id,e.egress_name,i.ingress_name,digit,
		(select name from time_profile where time_profile_id = resource_block.time_profile_id) as time_profile
		    from  resource_block
		    left join (select alias as egress_name,resource_id  from resource where egress=true  ) e   on  e.resource_id=resource_block.engress_res_id
		    left join (select alias as ingress_name,resource_id  from resource where ingress=true  ) i   on  i.resource_id=resource_block.ingress_res_id
		    where res_block_id  = {$_REQUEST['edit_id']}
	  		";
                $result = $this->Blocklist->query($sql);
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
                $results = $this->Blocklist->findAll($currPage, $pageSize, $this->_order_condtions(
                                array('res_block_id', 'egress_name', 'ingress_name', 'digit', 'time_profile')));
            }
        }
        $this->set('p', $results);
    }

    function _render_save_bindModel() {
        $bindModel = Array();
        $bindModel['belongsTo'] = Array();
        $bindModel['belongsTo']['TimeProfile'] = Array('className' => 'TimeProfile', 'fields' => 'name');
        $bindModel['belongsTo']['Egress'] = Array('className' => 'Resource', 'fields' => 'alias', 'foreignKey' => 'engress_res_id');
        $bindModel['belongsTo']['EgressClient'] = Array('className' => 'Client', 'fields' => Array('client_id', 'name'), 'foreignKey' => 'egress_client_id');
        $bindModel['belongsTo']['Ingress'] = Array('className' => 'Resource', 'fields' => 'alias', 'foreignKey' => 'ingress_res_id');
        $bindModel['belongsTo']['IngressClient'] = Array('className' => 'Client', 'fields' => Array('client_id', 'name'), 'foreignKey' => 'ingress_client_id');
        $this->loadModel('ResourceBlock');
        $this->ResourceBlock->bindModel($bindModel, false);
    }

    function js_save($type) {
        $this->set('type', $type);
        if (!$_SESSION['role_menu']['Routing']['blocklists']['model_w']) {
            $this->redirect_denied();
        }
        Configure::write('debug', 0);
        $id = array_keys_value($this->params, 'url.id');
        $this->_render_js_save_options();
        $this->_render_js_save_impl($id);
    }

    function _render_js_save_impl($id=null) {
        if (!empty($id)) {
            $this->_render_save_bindModel();
            $this->data = $this->ResourceBlock->find('first', Array('conditions' => Array('res_block_id' => $id)));
        }
    }

    function _render_js_save_options() {
        $this->loadModel('Client');
        $this->loadModel('TimeProfile');
        $this->Client->bindModel(Array('hasOne' => Array('Resource' => Array('className' => 'Resource', 'fields' => Array('resource_id')))));
        $this->set('IngressClient', $IngressClientt = $this->Client->find('all', Array('conditions' => Array('1=1 group by "Client"."client_id","Client"."name"'), 'order' => array('Client.name'), 'fields' => Array('Client.client_id', 'Client.name'))));
        $this->Client->bindModel(Array('hasOne' => Array('Resource' => Array('className' => 'Resource', 'fields' => Array('resource_id')))));
        $this->set('EgressClient', $this->Client->find('all', Array('conditions' => Array('1=1 group by "Client"."client_id","Client"."name"'), 'fields' => Array('Client.client_id', 'Client.name'))));
        $this->set('TimeProfileList', $this->TimeProfile->find('all'));
    }

    function _render_index_bindModel() {
        $bindModel = Array();
        $bindModel['belongsTo'] = Array();
        $bindModel['belongsTo']['TimeProfile'] = Array('className' => 'TimeProfile', 'fields' => 'name');
        $bindModel['belongsTo']['Egress'] = Array('className' => 'Resource', 'fields' => 'alias', 'foreignKey' => 'engress_res_id');
        $bindModel['belongsTo']['EgressClient'] = Array('className' => 'Client', 'fields' => Array('client_id', 'name'), 'foreignKey' => 'egress_client_id');
        $bindModel['belongsTo']['Ingress'] = Array('className' => 'Resource', 'fields' => array('alias', 'ingress', 'egress'), 'foreignKey' => 'ingress_res_id');
        $bindModel['belongsTo']['IngressClient'] = Array('className' => 'Client', 'fields' => Array('client_id', 'name'), 'foreignKey' => 'ingress_client_id');
        $this->loadModel('ResourceBlock');
        $this->ResourceBlock->bindModel($bindModel, false);
    }

    function _order_index_conditions() {
        $order_Array = array('res_block_id', 'ingress_client_id', 'egress_client_id', 'ealias' => 'Egress.alias', 'inalias' => 'Ingress.alias', 'ResourceBlock.digit', 'tname' => 'TimeProfile.end_week,TimeProfile.start_week,TimeProfile.end_time,TimeProfile.start_time', 'time_profile');
        $this->paginate['order'] = $this->_order_condtions($order_Array, null, 'IngressClient.name,EgressClient.name, ResourceBlock.ani_prefix,ResourceBlock.dnis_method, ResourceBlock.dnis_method, ResourceBlock.dnis_length');
    }

    function _filter_index_conditions($type) {
        $filter_Array = array('egress_res_id' => 'Ingress.alias', 'ingress_res_id' => 'Ingress.alias', 'digit' => 'ResourceBlock.digit', 'ani_prefix' => 'ResourceBlock.ani_prefix', 'action_type' => 'ResourceBlock.action_type','search');
        $filter_conditions[] = $this->_filter_conditions($filter_Array);
        
        if($type == 2) {
            $filter_conditions_t['Ingress.egress'] = true;
            $filter_conditions['OR'] = array(
                'ResourceBlock.ingress_res_id' => NULL,
                $filter_conditions_t,
            );
        } else {
            $filter_conditions_t['Ingress.ingress'] = true;
            $filter_conditions['OR'] = array(
                'ResourceBlock.engress_res_id' => NULL,
                $filter_conditions_t,
            );
        }

        $this->paginate['conditions'] = $filter_conditions;
    }

    function _render_index_data($type) {
        $this->_order_index_conditions();
        $this->_filter_index_conditions($type);
        $this->_render_index_bindModel();
        $this->data = $this->paginate('ResourceBlock');
        foreach ($this->data as &$item)
        {
            $item['ResourceBlock']['block_on'] = $this->ResourceBlock->get_resource_block_time($item['ResourceBlock']['block_log_id'], $item['ResourceBlock']['loop_block_id'], $item['ResourceBlock']['ticket_log_id']);
        }
    }

    function _render_index_options() {
        $this->loadModel('Resource');
        $this->set('IngressList', $this->Resource->find('all', Array('conditions' => Array('ingress is true'),'fields' => Array('resource_id', 'alias'), 'order' => array('alias'))));
        $this->set('EgressList', $this->Resource->find('all', Array('conditions' => Array('egress is true'), 'fields' => Array('resource_id', 'alias'), 'order' => array('alias'))));
    }

    function index($type = 1) {
        $this->pageTitle = "Routing/Block list";
        $this->_render_index_data($type);
        $this->_render_index_options();
        $this->set('type', $type);
    }

    public function download() {
        if (!$_SESSION['role_menu']['Routing']['blocklists']['model_x']) {
            $this->redirect_denied();
        }
        Configure::write('debug', 0);
        $reseller_id = $this->Session->read('sst_reseller_id');
        $sql = "select ingress_res_id,engress_res_id,digit,time_profile_id from resource_block";
        $this->Blocklist->export__sql_data(__('downloadBlockList', true), $sql, "block_list");
        $this->layout = '';
    }

    function _filter_search() {
        $search = $this->_get('search');
        if (!empty($search)) {
            return " (\"ResourceBlock\".\"digit\"::text like '$search%' or \"Egress\".\"alias\"::text like '%$search%' or \"Ingress\".\"alias\"::text like '%$search%')";
        }
        return "";
    }

    function ajaxValidateRepeat() {
        Configure::write('debug', 2);
        $this->layout = 'ajax';
        $id = $this->_get('id') + 0;
        $digit = $this->_get('digit');
        $ingress_trunk = $this->_get('ingress_trunk');
        if ($ingress_trunk == 'null') {
            $ingress_trunk = null;
        }
        $egress_trunk = $this->_get('egress_trunk');
        if ($egress_trunk == 'null') {
            $egress_trunk = null;
        }
        $conditions = Array('digit' => $digit);
        if (!empty($id)) {
            $conditions[] = "res_block_id <> '$id'";
        }
        if (!empty($egress_trunk)) {
            $conditions['engress_res_id'] = $egress_trunk;
        } else {
            $conditions[] = "engress_res_id is null";
        }
        if (!empty($ingress_trunk)) {
            $conditions['ingress_res_id'] = $ingress_trunk;
        } else {
            $conditions[] = "ingress_res_id is null";
        }
        $list = $this->ResourceBlock->find('count', Array('conditions' => $conditions));
        if ($list > 0) {
            echo 'false';
        }
    }

    //select delete
    public function del_selected_blo($type) {
        if (!$_SESSION['role_menu']['Routing']['blocklists']['model_w']) {
            $this->redirect_denied();
        }
        $ids = $_REQUEST['ids'];
        $arrDigit = $this->Blocklist->getDigitByID($ids);
        $tip = "";
        foreach($arrDigit as $digit){
            $tip.=$digit[0]['digit'].",";
        }
        $tip = "[".substr($tip,0,-1)."]"; 
        $this->Blocklist->begin();
        $qs_c = 0;
        $qs = $this->Blocklist->query("delete from resource_block where res_block_id in ($ids)");
        $qs_c += count($qs);
//		$qs =	$this->Product->query("delete from resource_product_ref where product_id in ($ids)");
//		$qs_c += count($qs);
        if ($qs_c == 0) {
            $this->Blocklist->create_json_array('', 201, __('The Block list '.$tip.' is deleted successfully.', true));
            $this->Blocklist->commit();
        } else {
            $this->Blocklist->create_json_array('', 101, __('Fail to delete Block list.', true));
            $this->Blocklist->rollback();
        }
        $this->Session->write('m', Blocklist::set_validator());
        $this->redirect('/blocklists/index/'.$type);
    }

//delete all
    public function del_all_blo($type) {
        if (!$_SESSION['role_menu']['Routing']['blocklists']['model_w']) {
            $this->redirect_denied();
        }
        $this->Blocklist->begin();
        $qs_c = 0;
        $qs = $this->Blocklist->query("delete from resource_block");
        $qs_c += count($qs);
//		$qs = $this->Product->query("delete from resource_product_ref");
//		$qs_c += count($qs);
        if ($qs_c == 0) {
            $this->Blocklist->create_json_array('', 201, __('Deleted All succesfully', true));
            $this->Blocklist->commit();
        } else {
            $this->Blocklist->create_json_array('', 101, __('Deleted All unsuccessfully', true));
            $this->Blocklist->rollback();
        }
        $this->Session->write('m', Blocklist::set_validator());
        $this->redirect('/blocklists/index/'.$type);
    }

}

?>
