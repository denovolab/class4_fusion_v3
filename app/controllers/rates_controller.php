<?php

class RatesController extends AppController {

    var $name = 'Rates';
    var $helpers = array('javascript', 'html', 'AppRate');
    var $uses = array('Rate', 'Jurisdictionprefix');
 
    function index() {
        $this->redirect('rates_list');
    }

    public function beforeFilter() {
        
        if( $this->params['action'] == 'upload_email')
        {
            return true;
        }
        $this->checkSession("login_type"); //核查用户身份
        $login_type = $this->Session->read('login_type');
        if ($login_type == 1) {
            $this->Session->write('executable', true);
            $this->Session->write('writable', true);
        } else {
            $limit = $this->Session->read('sst_tools_outboundTest');
            $this->Session->write('executable', $limit['executable']);
            $this->Session->write('writable', $limit['writable']);
        }
        parent::beforeFilter();
    }
    
    public function upload_email()
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $targetFolder = WWW_ROOT . "upload/email_list";;

        if (!empty($_FILES)) {
                $fileName =  uniqid() . '.txt';
                $targetFile = $targetFolder . '/' . $fileName;

                // Validate the file type
                $fileTypes = array('txt'); // File extensions
                $fileParts = pathinfo($_FILES['Filedata']['name']);

                if (in_array($fileParts['extension'],$fileTypes)) {
                        move_uploaded_file($_FILES['Filedata']['tmp_name'],$targetFile);
                        
                        $content = file_get_contents($targetFile);
                        
                        $search_arr = array(',', ';',);
                        
                        $content = str_replace($search_arr, "\n", $content);
                        
                        file_put_contents($targetFile, $content);
                        
                        //echo '1';
                        echo $fileName;
                } else {
                        echo 'Invalid file type.';
                }
        }
    }

    function currency($currency=null) {
        if (!empty($courrency)) {
            $courrency = $courrency + 0;
        }
        $this->rates_list($currency);
    }

    function save($id=null) {
        $this->autoRender = false;
        $this->autoLayout = false;
        if (!$_SESSION['role_menu']['Switch']['rates']['model_w']) {
            $this->redirect_denied();
        }
        if ($this->RequestHandler->isPost()) {
            $name = $this->data['Rate']['name'];
            //pr($name);
            if ($id) {
                $this->data['Rate']['rate_table_id'] = $id;
            }
            /* if($this->data['Rate']['jurisdiction_country_id']){
              $this->data['Rate']['rate_type']=2;
              }else{
              $this->data['Rate']['rate_type']=1;
              } */
            $this->data['modify_time'] = time();
            $count = $this->Rate->query("select count(name) as name_num from rate_table where name='$name'");

            $this->data['Rate']['update_at'] = date("Y-m-d H:i:s");
            ;
            $this->data['Rate']['update_by'] = $_SESSION['sst_user_name'];
           
            if ($this->data['Rate']['jur_type'] == 2) {
               $sql = "SELECT id FROM jurisdiction_country WHERE name = 'US'";
               $jur_data = $this->Rate->query($sql);
               if (empty($jur_data)) {
                $sql = "INSERT INTO jurisdiction_country(name) VALUES ('US') returning id";
                $jur_data = $this->Rate->query($sql);
            }
               $this->data['Rate']['jurisdiction_country_id'] = $jur_data[0][0]['id'];
            } else {
               $this->data['Rate']['jurisdiction_country_id'] = NULL; 
            }

            if ($this->data['Rate']['name'] == '') {
                $this->Rate->create_json_array('', 101, __('The field Name cannot be NULL.', true));
                $this->xredirect("/rates/rates_list");
            } elseif ($count[0][0]['name_num'] > 0 && empty($id)) {
                $this->Rate->create_json_array('', 101, __('Name is already in used!', true));
                $this->xredirect("/rates/rates_list");
            } elseif ($this->Rate->save($this->data)) {
                if (empty($id)) {
                    $this->Rate->create_json_array('', 201, __('The Rate Table [' . $this->data['Rate']['name'] . '] is created successfully!', true));
                } else {
                    $this->Rate->create_json_array('', 201, 'The Rate Table [' . $this->data['Rate']['name'] . '] is modified successfully!');
                    $this->Rate->logging(2, 'Rate Table', "Rate Table Name:{$this->data['Rate']['name']}");
                }
                $this->xredirect("/rates/rates_list");
            } else {
                $this->redirect("/rates/rates_list");
            }
        }
    }
    
    public function formatted($file_name)
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        
        ($this->params['url']['ext'] != 'html') and ($file_name = $file_name . "." . $this->params['url']['ext']);
        
        $target = WWW_ROOT . "upload/email_list" . DIRECTORY_SEPARATOR . $file_name;
        $content = file_get_contents($target);       
        ob_clean();
        echo $content;
    }

    public function massedit($ids) {
        if (!$_SESSION['role_menu']['Switch']['rates']['model_w']) {
            $this->redirect_denied();
        }
        $this->set("ids", $ids);
        $ids = explode(',', $ids);
        if ($this->RequestHandler->isPost()) {
            $data = $_POST;
            $count = count($data['code']);
            for ($i = 0; $i < $count; $i++) {
                $code = $data['code'][$i];
                $codename = $data['codename'][$i];
                $country = $data['country'][$i];
                $rate = $data['rate'][$i];
                $setupfee = $data['setupfee'][$i];
                $effectdate = $data['effectdate'][$i];
                $enddate = empty($data['enddate'][$i]) ? 'NULL' : "'" . $data['enddate'][$i] . "'";
                $endbreakouts = $data['endbreakouts'][$i];
                $mintime = $data['mintime'][$i];
                $gracetime = $data['gracetime'][$i];
                $seconds = $data['seconds'][$i];
                $timezone = $data['timezone'][$i];
                $localrate = $data['localrate'][$i];
                if (empty($code)) {
                    // 如果code为空，直接进行替换插入操作。
                    foreach ($ids as $rate_table_id) {
                        $this->Rate->matchEqualEndDate($rate_table_id, $code, $effectdate);
                        $this->Rate->insertMassEdit($rate_table_id, $code, $codename, $country, $rate, $setupfee, $effectdate, $enddate, $mintime, $gracetime, $seconds, $timezone, $localrate);
                    }
                } else {
                    if ($endbreakouts == 'true') {
                        foreach ($ids as $rate_table_id) {
                            // 如果按下了End Break-out
                            $this->Rate->matchPrefixEndDate($rate_table_id, $code, $effectdate);
                            $this->Rate->insertMassEdit($rate_table_id, $code, $codename, $country, $rate, $setupfee, $effectdate, $enddate, $mintime, $gracetime, $seconds, $timezone, $localrate);
                        }
                    } else {
                        foreach ($ids as $rate_table_id) {
                            // 如果没有按下
                            $this->Rate->matchEqualEndDate($rate_table_id, $code, $effectdate);
                            $this->Rate->insertMassEdit($rate_table_id, $code, $codename, $country, $rate, $setupfee, $effectdate, $enddate, $mintime, $gracetime, $seconds, $timezone, $localrate);
                        }
                    }
                }
            }
            $this->Rate->create_json_array('', 201, __('Succeeded', true));
            $this->Session->write('m', Rate::set_validator());
        }
    }

    public function masseditend($ids) {
        if (!$_SESSION['role_menu']['Switch']['rates']['model_w']) {
            $this->redirect_denied();
        }
        $ids = explode(',', $ids);
        if ($this->RequestHandler->isPost()) {
            $data = $_POST;
            $count = count($data['code']);
            for ($i = 0; $i < $count; $i++) {
                $code = $data['code'][$i];
                $enddate = $data['enddate'][$i];
                $endbreakouts = $data['endbreakouts'][$i];
                if (empty($code)) {
                    // 如果code为空，直接进行替换插入操作。
                    foreach ($ids as $rate_table_id) {
                        $this->Rate->matchEqualEndDate1($rate_table_id, $code, $enddate);
                    }
                } else {
                    if ($endbreakouts == 'true') {
                        foreach ($ids as $rate_table_id) {
                            // 如果按下了End Break-out
                            $this->Rate->matchPrefixEndDate1($rate_table_id, $code, $enddate);
                        }
                    } else {
                        foreach ($ids as $rate_table_id) {
                            // 如果没有按下
                            $this->Rate->matchEqualEndDate1($rate_table_id, $code, $enddate);
                        }
                    }
                }
            }
            $this->Rate->create_json_array('', 201, __('Successfully!', true));
            $this->Session->write('m', Rate::set_validator());
            $this->redirect('/rates/rates_list');
        }
    }

    public function code_rates_list($currency=null) {
        $this->set('table_name', $this->Rate->find_rate_table_name());
        $this->pageTitle = "Switch/Rate Table";
        $_SESSION['curr_url'] = '';
        if (isset($this->params['pass'][1]) && isset($this->params['pass'][2])) {
            $p1 = $this->params['pass'][1];
            $p2 = $this->params['pass'][2];
            $_SESSION['curr_url'] = "/$p1/$p2";
        }
        $currPage = 1;
        $pageSize = 100;
        $search = null;
        if (!empty($_REQUEST ['page']))
            $currPage = $_REQUEST ['page'];
        if (!empty($_REQUEST ['size']))
            $pageSize = $_REQUEST ['size'];
        if (!empty($_REQUEST['search']) && empty($_REQUEST['advsearch'])) {
            $search = $_REQUEST['search'];
            $this->set('search', $search);
        }
        $adv_search = '';
        if (!empty($_REQUEST['advsearch'])) {
            $last_conditions = '&advsearch=1';
            $f = empty($this->params['form']) ? $_REQUEST : $this->params['form'];
            if (!empty($f['search_code_deck'])) {
                $adv_search .= " and code_deck_id = {$f['search_code_deck']}";
                $last_conditions .= "&search_code_deck={$f['search_code_deck']}";
            }
            if (!empty($f['name'])) {
                $adv_search .= " and name like '%{$f['name']}%'";
                $last_conditions .= "&name={$f['name']}";
            }
            if (!empty($f['search_currency'])) {
                $adv_search .= " and currency_id = {$f['search_currency']}";
                $last_conditions .= "&search_currency={$f['search_currency']}";
            }
            if (!empty($f['search_res'])) {
                $adv_search .= " and reseller_id = {$f['search_res']}";
                $last_conditions .= "&search_res={$f['search_res']}";
            }
            $this->set('last_conditions', $last_conditions);
            $this->set('searchForm', $f);
        }
        if (!empty($this->params['url']['id'])) {
            $id = $this->params['url']['id'];
            $adv_search.=" and rate_table_id=$id";
        }
        $results = $this->Rate->getAllRates_code($currPage, $pageSize, $search, $currency, $adv_search, $this->_order_condtions(array('rate_table_id', 'name', 'code_deck', 'currency', 'client_rate')));
        $rs = $this->Rate->getAddInfo();
        $this->set('codecs', str_ireplace("\"", "'", json_encode($rs[1])));
        $this->set('currs', str_ireplace("\"", "'", json_encode($rs[2])));
        $this->set('jur_country', str_ireplace("\"", "'", json_encode($rs[3])));
        $search_info = $this->Rate->getSearchInfo();
        $this->set('codecs_s', $search_info[0]);
        $this->set('currs_s', $search_info[1]);
        $this->set('p', $results);
        $this->set('jurisdiction_countries', $this->Jurisdictionprefix->find_all_valid());
        $this->set('code_name', $this->Rate->select_name(array_keys_value($this->params, 'pass.0')));
        if (!empty($currency)) {
            $this->set('curr_search', true);
        }
    }
    
    public function indeteminate($rate_table_id) {
        Configure::write('debug', 0);
        $this->autoLayout = false;
        $sql = "SELECT jurisdiction_prefix, noprefix_min_length, noprefix_max_length, prefix_min_length, prefix_max_length FROM rate_table
WHERE rate_table_id = {$rate_table_id}";
        $result = $this->Rate->query($sql);
        $this->set('rate_table_id', $rate_table_id);
        $this->set('data', $result[0][0]);
    }

    public function rates_list($currency=null) {
        $this->set('table_name', $this->Rate->find_rate_table_name());
        $this->pageTitle = "Switch/Rate Table";
        $_SESSION['curr_url'] = '';
        if (isset($this->params['pass'][1]) && isset($this->params['pass'][2])) {
            $p1 = $this->params['pass'][1];
            $p2 = $this->params['pass'][2];
            $_SESSION['curr_url'] = "/$p1/$p2";
        }
        $currPage = 1;
        $pageSize = 100;
        $search = null;
        $temp = isset($_SESSION['paging_row']) ? $_SESSION['paging_row'] : 100;

        empty($_GET['page']) ? $currPage = 1 : $currPage = $_GET['page'];
        empty($_GET['size']) ? $pageSize = $temp : $pageSize = $_GET['size'];

        $_SESSION['paging_row'] = $pageSize;
        if (!empty($_REQUEST['search']) && empty($_REQUEST['advsearch'])) {
            $search = $_REQUEST['search'];
            $this->set('search', $search);
        }
        $adv_search = '';
        if (!empty($_REQUEST['advsearch'])) {
            $last_conditions = '&advsearch=1';
            $f = empty($this->params['form']) ? $_REQUEST : $this->params['form'];
            if (!empty($f['search_code_deck'])) {
                $adv_search .= " and code_deck_id = {$f['search_code_deck']}";
                $last_conditions .= "&search_code_deck={$f['search_code_deck']}";
            }
            if (!empty($f['name'])) {
                $adv_search .= " and name ilike '%{$f['name']}%'";
                $last_conditions .= "&name={$f['name']}";
            }
            if (!empty($f['search_currency'])) {
                $adv_search .= " and currency_id = {$f['search_currency']}";
                $last_conditions .= "&search_currency={$f['search_currency']}";
            }
            if (!empty($f['search_res'])) {
                $adv_search .= " and reseller_id = {$f['search_res']}";
                $last_conditions .= "&search_res={$f['search_res']}";
            }
            $this->set('last_conditions', $last_conditions);
            $this->set('searchForm', $f);
        }
        if (!empty($this->params['url']['id'])) {
            $id = $this->params['url']['id'];
            $adv_search.=" and rate_table_id=$id";
        }

        $results = $this->Rate->getAllRates($currPage, $pageSize, $search, $currency, $adv_search, $this->_order_condtions(array('rate_table_id', 'name', 'code_deck', 'currency', 'client_rate', 'rate_type')));
        $rs = $this->Rate->getAddInfo();
        $this->set('codecs', str_ireplace("\"", "'", json_encode($rs[1])));
        $this->set('currs', str_ireplace("\"", "'", json_encode($rs[2])));
        $this->set('jur_country', str_ireplace("\"", "'", json_encode($rs[3])));
        $search_info = $this->Rate->getSearchInfo();
        $this->set('codecs_s', $search_info[0]);
        $this->set('currs_s', $search_info[1]);
        $this->set('p', $results);
        $this->set('jurisdiction_countries', $this->Jurisdictionprefix->find_all_valid());
        $this->set('code_name', $this->Rate->select_name(array_keys_value($this->params, 'pass.0')));
        $this->set('jur_lists', array('A-Z', 'US Non-JD', 'US JD', 'OCN-LATA-JD', 'OCN-LATA-NON-JD'));
        if (!empty($currency)) {
            $this->set('curr_search', true);
        }
        $this->set('billing_methods', array('DNIS' ,'LRN', 'LRN BLOCK', 'LRN Block Higher'));
    }

    public function delete_all() {
        if (!$_SESSION['role_menu']['Switch']['rates']['model_w']) {
            $this->redirect_denied();
        }
        if ($this->Rate->deleteAll() != true)
            $this->Rate->create_json_array('', 101, __('ratetmpusing', true));
        else
            $this->Rate->create_json_array('', 201, __('All Rate Table is deleted successfully.', true));

        $this->Session->write('m', Rate::set_validator());
        $this->redirect('/rates/rates_list');
    }

    public function delete_selected() {
        if (!$_SESSION['role_menu']['Switch']['rates']['model_w']) {
            $this->redirect_denied();
        }
        $ids = $_REQUEST['ids'];
        $arrName = $this->Rate->getNameByID($ids);
        foreach($arrName as $name){
            $tip.=$name[0]['name'].",";
        }
        $tip = '['.substr($tip,0,-1).']';
        $r = $this->Rate->deleteSelected($ids);        
        if ($r != true)
            $this->Rate->create_json_array('', 101, __('ratetmpusing', true));
        else            
            $this->Rate->create_json_array('', 201, __('The rate table '.$tip.' is deleted successfully!', true));

        $this->Session->write('m', Rate::set_validator());
        $this->redirect('/rates/rates_list');
    }
    
    
    public function checkused() {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $rate_table_id = $_POST['rate_table_id'];
        $data = array();
        // Ingress
        $sql = "select client.name, client.status,resource.alias, resource.active from resource_prefix inner join resource 
on resource_prefix.resource_id = resource.resource_id left join client on resource.client_id = client.client_id
where resource_prefix.rate_table_id = {$rate_table_id}";
        $result = $this->Rate->query($sql);
        foreach ($result as $item) {
            array_push($data, array(
                'client_name'   => $item[0]['name'],
                'resource_name' => $item[0]['alias'],
                'is_active'     => $item[0]['status'] ? ($item[0]['active'] ? 'Yes' : 'No') : 'No',
                'type'          => 'Ingress',
            ));
        }
        
        // Egress
        $sql = "select client.name, client.status, resource.alias, resource.active 
from resource left join client on resource.client_id = client.client_id
where resource.rate_table_id = {$rate_table_id}";
        $result = $this->Rate->query($sql);
        foreach ($result as $item) {
            array_push($data, array(
                'client_name'   => $item[0]['name'],
                'resource_name' => $item[0]['alias'],
                'is_active'     => $item[0]['status'] ? ($item[0]['active'] ? 'Yes' : 'No') : 'No',
                'type'          => 'Egress',
            ));
        }
        
        echo json_encode($data);
        
    }

    public function del_rate_tmp($id) {
        if (!$_SESSION['role_menu']['Switch']['rates']['model_w']) {
            $this->redirect_denied();
        }
        $this->data = $this->Rate->find('first', Array('conditions' => Array('rate_table_id' => $id))); 
        
        $sql = "update resource_prefix set rate_table_id = null  where rate_table_id = {$id};update resourse set rate_table_id = null where rate_table_id = {$id}";
        $this->Rate->query($sql);
        
        /*
        $ingress_count = $this->Rate->query("select count(*) from resource_prefix 
inner join resource 
on resource.resource_id = resource_prefix.resource_id and resource_prefix.rate_table_id = {$id} and 
active = true");
        $egress_count  = $this->Rate->query("select count(*) from resource where rate_table_id = $id and active = true");
         * 
         */
        if (false) {
            $this->Rate->create_json_array('', 101, __('Rate Table is being used; therefore, it cannot be deleted.', true));
        } else {
            if ($this->Rate->delete_one($id) != true)
                $this->Rate->create_json_array('', 101, __('ratetmpusing', true));
            else {
                $this->Rate->create_json_array('', 201, __('The Rate Table ['.$this->data['Rate']['name'].'] deleted successfully!', true));
                $this->Rate->logging(1, 'Rate Table', "Rate Table Name:{$this->data['Rate']['name']}");
            }
        }
        $this->Session->write('m', Rate::set_validator());
        $this->redirect('/rates/rates_list');
    }

    public function copy_tmp() {
        if (!$_SESSION['role_menu']['Switch']['rates']['model_w']) {
            $this->redirect_denied();
        }
        $old_id = $_REQUEST['id'];
        $name = $_REQUEST['name'];
        if ($this->Rate->copy_rate($old_id, $name) != true)
            $this->Rate->create_json_array('', 101, __('copyfail', true));
        else
            $this->Rate->create_json_array('', 201, __('copysuc', true));

        $this->Session->write('m', Rate::set_validator());
        $this->redirect('/rates/rates_list');
    }

    function add_curr_tmp() {
        if (!$_SESSION['role_menu']['Switch']['rates']['model_w']) {
            $this->redirect_denied();
        }
        $n = $_REQUEST['n'];
        $c = $_REQUEST['c'];
        $cu = $_REQUEST['cu'];
        $order = 'null';
        if ($this->Rate->add($n, $c, $cu) != true)
            $this->Rate->create_json_array('', 101, 'Rate table name is exists');
        else
            $this->Rate->create_json_array('', 201, __('addratesuc', true));

        $this->Session->write('m', Rate::set_validator());
        $this->redirect("/rates/currency/" . $cu . "/currs/currency_list");
    }

    public function add_tmp() {
        if (!$_SESSION['role_menu']['Switch']['rates']['model_w']) {
            $this->redirect_denied();
        }
        $n = $_REQUEST['n'];
        $c = $_REQUEST['c'];
        $cu = $_REQUEST['cu'];
        $country = $_REQUEST['country'];
        //	$use_gf = $_REQUEST['use_gf'];
        $order = 'null';

        if ($this->Rate->add($n, $c, $cu, $country) != true)
            $this->Rate->create_json_array('', 101, 'Rate table name is exists');
        else
            $this->Rate->create_json_array('', 201, __('addratesuc', true));

        $this->Session->write('m', Rate::set_validator());
        $this->redirect('/rates/rates_list');
    }

    public function update_tmp() {
        if (!$_SESSION['role_menu']['Switch']['rates']['model_w']) {
            $this->redirect_denied();
        }
        $n = $_REQUEST['n'];

        $c = $_REQUEST['c'];
        $cu = $_REQUEST['cu'];
        $id = $_REQUEST['id'];
        $country = $_REQUEST['country'];


        if ($this->Rate->update($n, $c, $cu, $id, $country) != true)
            $this->Rate->create_json_array('', 101, __('modifyratefail', true));
        else
            $this->Rate->create_json_array('', 201, __('modifyratesuc', true));

        $this->Session->write('m', Rate::set_validator());

        $list = $this->Rate->query("select name  from rate_table  where  rate_table_id=$id");
        $this->redirect("/rates/rates_list?search={$list[0][0]['name']}");
    }

    /**
     * 上传数据验证
     */
    public function check_uploadcodes() {
        $code_deck_id = $this->params['pass'][0];
        $list = $this->Rate->query("select name   from  rate_table where   rate_table_id=$code_deck_id ");
        $this->set("code_name", $list[0][0]['name']);
        $this->set("rate_table_id", $code_deck_id);
        $this->set("mydata", $this->Rate->query("select *   from  tmp_rate    limit 10 "));
    }

    //上传成功 记录上传
    public function upload_code2() {
        if (!$_SESSION['role_menu']['Switch']['rates']['model_x']) {
            $this->redirect_denied();
        }
        $code_deck_id = $_POST['upload_table_id'];
        $code_name = $_POST['code_name'];
        $list = $this->Rate->import_data(__('UploadRate', true)); //上传数据
        $this->Rate->create_json_array("", 201, __('rateUploadSuccess', true));
        $this->Session->write('m', Rate::set_validator());
        $this->redirect('/importlogs/view'); //验证上传数据
    }

    //上传code	
    public function import_rate() {
        if (!$_SESSION['role_menu']['Switch']['rates']['model_x']) {
            $this->redirect_denied();
        }
        $rate_table_id = $this->params['pass'][0];
        $list = $this->Rate->query("select name   from  rate_table where   rate_table_id=$rate_table_id ");
        $this->set("code_name", $list[0][0]['name']);
        $this->set("rate_table_id", $rate_table_id);
    }

    public function download_rate() {
        if (!$_SESSION['role_menu']['Switch']['rates']['model_x']) {
            $this->redirect_denied();
        }
        $rate_table_id = $this->params['pass'][0];
        $download_sql = "select   code,rate,setup_fee,effective_date,end_date,min_time,grace_time,interval,time_profile_id,seconds,code_name,intra_rate,
		inter_rate,local_rate
		from  rate  where rate_table_id=$rate_table_id";
        $this->Rate->export__sql_data(__('DownloadRate', true), $download_sql, 'rate');
        Configure::write('debug', 0);
        $this->layout = 'csv';
    }

    public function r_rates_list($table_id, $type=null) {
        if (!empty($type)) {
            $this->set('extraClient', true);
        }
        $this->set('rate_table_id', $table_id);
        $currPage = 1;
        $pageSize = 100;
        $search = null;
        $adv_search = '';
        //高级搜索
        if (!empty($_REQUEST['advsearcht'])) {
            $last_conditions = '';
            $adv_form = $this->params['form'];
            if (!empty($adv_form['startrate'])) {
                $adv_search .= " and rate >= {$adv_form['startrate']}";
                $last_conditions .= "&startrate={$adv_form['startrate']}";
            }
            if (!empty($adv_form['endrate'])) {
                $adv_search .= " and rate <= {$adv_form['endrate']}";
                $last_conditions .= "&endrate={$adv_form['endrate']}";
            }

            if (!empty($adv_form['startsetupfee'])) {
                $adv_search .= " and setup_fee >= {$adv_form['startsetupfee']}";
                $last_conditions .= "&startsetupfee={$adv_form['startsetupfee']}";
            }

            if (!empty($adv_form['endsetupfee'])) {
                $adv_search .= " and setup_fee <= {$adv_form['endsetupfee']}";
                $last_conditions .= "&endsetupfee={$adv_form['endsetupfee']}";
            }

            if (!empty($adv_form['startmint'])) {
                $adv_search .= " and min_time >= {$adv_form['startmint']}";
                $last_conditions .= "&startmint={$adv_form['startmint']}";
            }

            if (!empty($adv_form['endmint'])) {
                $adv_search .= " and min_time <= {$adv_form['endmint']}";
                $last_conditions .= "&endmint={$adv_form['endmint']}";
            }

            if (!empty($adv_form['startinterv'])) {
                $adv_search .= " and interval >= {$adv_form['startinterv']}";
                $last_conditions .= "&startinterv={$adv_form['startinterv']}";
            }

            if (!empty($adv_form['endinterv'])) {
                $adv_search .= " and interval <= {$adv_form['endinterv']}";
                $last_conditions .= "&endinterv={$adv_form['endinterv']}";
            }

            if (!empty($adv_form['startgrace'])) {
                $adv_search .= " and grace_time >= {$adv_form['startgrace']}";
                $last_conditions .= "&startgrace={$adv_form['startgrace']}";
            }

            if (!empty($adv_form['endgrace'])) {
                $adv_search .= " and grace_time <= {$adv_form['endgrace']}";
                $last_conditions .= "&endgrace={$adv_form['endgrace']}";
            }

            if (!empty($adv_form['searchtf'])) {
                $adv_search .= " and time_profile_id = {$adv_form['searchtf']}";
                $last_conditions .= "&searchtf={$adv_form['searchtf']}";
            }
            $this->set('last_conditons', $last_conditions);
            $this->set('searchForm', $this->params['form']);
        }

        //批量修改rate  应用或者预览
        if (!empty($_REQUEST['updateForm'])) {
            $f = $this->params['form'];
            $type = $f['type']; //执行方式
            $ids = $f['ids']; //需要操作的rate 的id  格式:125,526,542
            //-------参数-------------------
            $rate = $f['rate_per_min_action']; //费率
            $mintime = $f['min_time_action']; //最小时长
            $starttime = $f['effective_from_action']; //开始时间
            $setupfee = $f['pay_setup_action']; //一分钟的费用
            $interval = $f['pay_interval_action']; //计费周期
            $endtime = $f['end_date_action']; //结束时间
            $gracetime = $f['grace_time_action']; //赠送时长
            $sql = "update rate set rate_id = rate_id";
            $sql_select = "select rate_id,code,seconds,rate_table_id,(select name from time_profile where time_profile_id = rate.time_profile_id) as tf";
            ////////////////////////////////////
            if (!empty($rate)) {
                $rate_v = $f['rate_per_min_value'];
                //设置为该提交的值
                if ($rate == 'set') {
                    $sql .= ",rate=$rate_v";
                    $sql_select.=" ,$rate_v as rate";
                }
                //在基础上加
                else if ($rate == 'inc') {
                    $sql .= ",rate = rate + $rate_v";
                    $sql_select.=" ,rate+$rate_v as rate";
                }
                //在基础上减
                else if ($rate == 'dec') {
                    $sql .= ",rate = rate - $rate_v";
                    $sql_select.=" ,rate-$rate_v as rate";
                }
                //按百分比加
                else if ($rate == 'perin') {
                    $sql .= ",rate = rate +(rate*$rate_v/100)";
                    $sql_select.=" ,rate+(rate*$rate_v/100) as rate";
                }
                //按百分比减
                else if ($rate == 'perde') {
                    $sql .= ",rate = rate -(rate*$rate_v/100)";
                    $sql_select.=" ,rate-(rate*$rate_v/100) as rate";
                }
            } else {
                $sql_select .= ",rate";
            }

            if (!empty($mintime)) {
                $mintime_v = $f['min_time_value'];
                //设置为该提交的值
                if ($mintime == 'set') {
                    $sql .= ",min_time=$mintime_v";
                    $sql_select.=" ,$mintime_v as rate";
                }
                //在基础上加
                else if ($mintime == 'inc') {
                    $sql .= ",min_time=min_time+$mintime_v";
                    $sql_select.=" ,min_time+$mintime_v as min_time";
                }
                //在基础上减
                else if ($mintime == 'dec') {
                    $sql .= ",min_time=min_time-$mintime_v";
                    $sql_select.=" ,min_time-$mintime_v as min_time";
                }
            } else {
                $sql_select .= ",min_time";
            }

            if (!empty($starttime)) {
                if (!empty($f['effective_from_value'])) {
                    $sql .= ",effective_date='{$f['effective_from_value']}'";
                    $sql_select.=" ,{$f['effective_from_value']} as effective_date";
                } else {
                    $sql_select .= ",effective_date";
                }
            }

            if (!empty($setupfee)) {
                $setupfee_v = $f['pay_setup_value'];
                //设置为该提交的值
                if ($setupfee == 'set') {
                    $sql .= ",setup_fee=$setupfee_v";
                    $sql_select.=" ,$setupfee_v as setup_fee";
                }
                //在基础上加
                else if ($setupfee == 'inc') {
                    $sql .= ",setup_fee=setup_fee+$setupfee_v";
                    $sql_select.=" ,setup_fee+$setupfee_v as setup_fee";
                }
                //在基础上减
                else if ($setupfee == 'dec') {
                    $sql .= ",setup_fee=setup_fee-$setupfee_v";
                    $sql_select.=" ,setup_fee-$setupfee_v as setup_fee";
                }
                //按百分比加
                else if ($setupfee == 'perin') {
                    $sql .= ",setup_fee = setup_fee +(setup_fee*$setupfee_v/100)";
                    $sql_select.=" ,setup_fee+(setup_fee*$setupfee_v/100) as setup_fee";
                }
                //按百分比加
                else if ($setupfee == 'perde') {
                    $sql .= ",setup_fee = setup_fee -(setup_fee*$setupfee_v/100)";
                    $sql_select.=" ,setup_fee-(setup_fee*$setupfee_v/100) as setup_fee";
                }
            } else {
                $sql_select .= ",setup_fee";
            }

            if (!empty($interval)) {
                $sql .= ",interval={$f['pay_interval_value']}";
                $sql_select.=" ,{$f['pay_interval_value']} as interval";
            } else {
                $sql_select .= ",interval";
            }

            if (!empty($endtime)) {
                $sql .= ",end_date='{$f['end_date_value']}'";
                $sql_select.=" ,{$f['pay_interval_value']} as end_date";
            } else {
                $sql_select .= ",end_date";
            }

            if (!empty($gracetime)) {
                $sql .= ",grace_time={$f['grace_time_value']}";
                $sql_select.=" ,{$f['grace_time_value']} as grace_time";
            } else {
                $sql_select .= ",grace_time";
            }

            if ($type == 'apply') {//应用
                $sql .= " where rate_id in($ids)";

                $qs = $this->Rate->query($sql);
                if (count($qs) == 0) {
                    $this->Rate->create_json_array('', 201, __('manipulated_suc', true));
                } else {
                    $this->Rate->create_json_array('', 101, __('manipulated_fail', true));
                }
                $this->set('m', Rate::set_validator());
            } else {//预览
                $this->set('previewForm', $f);
                $this->set('previewRates', $this->Rate->query($sql_select . " from rate where rate_id in ($ids)"));
            }
        }

        if (!empty($_REQUEST ['page']))
            $currPage = $_REQUEST ['page'];

        if (!empty($_REQUEST ['size']))
            $pageSize = $_REQUEST ['size'];

        if (!empty($_REQUEST['search'])) {
            $search = $_REQUEST['search'];
            $this->set('search', $search);
        }

        $results = $this->Rate->getRates($currPage, $pageSize, $search, $table_id, $adv_search);

        $this->set('p', $results);

        $reseller_id = $this->Session->read('sst_reseller_id');
        $times = $this->Rate->getTimeProfile($reseller_id);
        $this->set('times', str_ireplace("\"", "'", json_encode($times)));
        $this->set('timeswithoutencode', $times);

        $changerate = $this->Rate->get_rate_tables($reseller_id);
        $this->set('changerate', $changerate);
        $this->set('table_id', $table_id);
        $this->set('now', date('Y-m-d H:i:s', time() + 6 * 60 * 60));
        $ss = $this->Rate->hasCodedeck($table_id);
        if ($ss != false) {
            $this->set('code_deck', $ss);
        } else {
            $this->set('code_deck', '-1');
        }
    }
    
    public function update_indeter() {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $rate_table_id = $_GET['rate_table_id'];
        $jurisdiction_prefix = !empty($_GET['jurisdiction_prefix']) ? "'{$_GET['jurisdiction_prefix']}'" : 'NULL';
        $noprefix_max_length = !empty($_GET['noprefix_max_length']) ? $_GET['noprefix_max_length'] : 'NULL';
        $noprefix_min_length = !empty($_GET['noprefix_min_length']) ? $_GET['noprefix_min_length'] : 'NULL';
        $prefix_max_length = !empty($_GET['prefix_max_length']) ? $_GET['prefix_max_length'] : 'NULL';
        $prefix_min_length = !empty($_GET['prefix_min_length']) ? $_GET['prefix_min_length'] : 'NULL' ;
        $sql = "UPDATE rate_table SET jurisdiction_prefix = {$jurisdiction_prefix}, noprefix_max_length = {$noprefix_max_length},
                noprefix_min_length = {$noprefix_min_length}, prefix_max_length = {$prefix_max_length}, prefix_min_length = {$prefix_min_length} WHERE
                rate_table_id = {$rate_table_id}";
        $this->Rate->query($sql);
        echo 1;
        
    }

    public function add_rate() {
        if (!$_SESSION['role_menu']['Switch']['rates']['model_w']) {
            $this->redirect_denied();
        }
        Configure::write('debug', 0);
        $qs = $this->Rate->add_rate();
        echo $qs;
    }

    public function update_rate() {
        if (!$_SESSION['role_menu']['Switch']['rates']['model_w']) {
            $this->redirect_denied();
        }
        Configure::write('debug', 0);
        $qs = $this->Rate->update_rate();
        echo $qs;
    }

    public function choose_codes($code_deck_id) {
        $this->layout = '';
        $currPage = 1;
        $pageSize = 100;
        $search = null;
        if (!empty($_REQUEST ['page']))
            $currPage = $_REQUEST ['page'];
        if (!empty($_REQUEST ['size']))
            $pageSize = $_REQUEST ['size'];
        if (!empty($_REQUEST['search'])) {
            $search = $_REQUEST['search'];
            $this->set('search', $search);
        }
        $results = $this->Rate->choose_codes($currPage, $pageSize, $search, $code_deck_id);
        $this->set('p', $results);
    }

    public function del_rate($id, $table_id) {
        if (!$_SESSION['role_menu']['Switch']['rates']['model_w']) {
            $this->redirect_denied();
        }
        if ($this->Rate->del_rate($id))
            $this->Rate->create_json_array('', 201, __('del_suc', true));
        else
            $this->Rate->create_json_array('', 101, __('del_fail', true));
        $this->Session->write('m', Rate::set_validator());
        $this->redirect('/rates/r_rates_list/' . $table_id);
    }

    public function generate_by_codedeck() {
        if (!$_SESSION['role_menu']['Switch']['rates']['model_w']) {
            $this->redirect_denied();
        }
        Configure::write('debug', 0);
        $qs = $this->Rate->generate_by_codedeck();
        echo $qs;
    }

    public function simulated() {
        if (!$_SESSION['role_menu']['Switch']['rates']['model_w']) {
            $this->redirect_denied();
        }
        Configure::write('debug', 0);
        $date = $_REQUEST['date'];
        $number = $_REQUEST['number'];
        $durations = $_REQUEST['durations'];
        $tab_id = $_REQUEST['tab_id']; //费率模板

        $qs = $this->Rate->simulated($date, $number, $durations, $tab_id);

        echo $qs;
    }

    function js_save($id=null) {
        if (!$_SESSION['role_menu']['Switch']['rates']['model_w']) {
            $this->redirect_denied();
        }
        if ($id) {
            $this->data = $this->Rate->find('first', Array('conditions' => Array('rate_table_id' => $id)));
        }
//        $this->_render_set_options(Array('Currency', 'Codedeck', 'Jurisdictioncountry'), Array('Jurisdictioncountry' => Array('conditions' => '1=1 group by id,name', 'fields' => Array('id', 'name'))));
        $this->_render_set_options(Array('Currency', 'Codedeck'));
        $this->layout = 'ajax';
        Configure::write('debug', 0);
    }

    public function test() {
        $a = $this->Rate->find('first', array('conditions' => array('name' => 'test_b')));
    }

    public function generate_tmp() {
        if (!$_SESSION['role_menu']['Switch']['rates']['model_w']) {
            $this->redirect_denied();
        }
        $rate_model = $this->Rate->find('first', array('conditions' => array('name' => $this->data['name'])));
        $data = Array();
        $data['name'] = $this->data['name']; #rable name
        $data['rate_type'] = $this->data['rate_type']; #rable name
        $data['currency_id'] = $this->data['currency'];
        $data['create_time'] = date('Y-m-d h:i:s');
        $data['modify_time'] = date('Y-m-d h:i:s');
        if (empty($rate_model)) {
            $this->Rate->save($data);
            $this->data['rate_table_id'] = $this->Rate->getLastInsertId();
        } else {
            $this->data['rate_table_id'] = $rate_model['Rate']['rate_table_id'];
        }

        if (empty($this->data['type_num'])) {
            $this->data['type_num'] = 0;
        }
        if ($this->data['type'] == 4) {
            $this->data['type_num'] = $this->data['type_num'] / 100;
        }
        $this->Rate->query("SELECT egress_profit_fun('{{$this->data['ids']}}',{$this->data['type']},{$this->data['rate_table_id']},{$this->data['type_num']},'{$this->data['code_type']}'); ");
        $this->Rate->create_json_array('', 201, __('Auto create successfully!', true));
        $this->xredirect("/rates/rates_list");
    }
    
    public function create_ratetable() {
        if($this->RequestHandler->IsPost()) {
            $rate_table_name = $_POST['rate_table_name'];
            $code_deck = empty($_POST['code_deck']) ? 'NULL' : $_POST['code_deck'];
            $currency = empty($_POST['currency']) ? 'NULL' : $_POST['currency'];
            $type = $_POST['type'];
            $rate_type = (int)$_POST['rate_type'];
            //$jurisdiction = $_POST['jurisdiction'] == '' ? 'NULL' : $_POST['jurisdiction'];
            $isus = $rate_type == 2 ? true : false;
            if ($currency == 'NULL') {
                $this->Rate->create_json_array('', 101, __('You must create Currency first!', true));
                    $this->xredirect("/currs/index");
            }
            $jurisdiction = 'NULL';
            if(!$this->Rate->alreay_exists_ratetable($rate_table_name)) {
                $rate_table_id = $this->Rate->create_ratetable($rate_table_name, $code_deck, $currency, $type, $isus, $rate_type);
                if ($rate_table_id == false) {
                    $this->Rate->create_json_array('', 101, __('The Rate Table[' .$rate_table_name .'] is added unsuccessfully.', true));
                    $this->xredirect("/rates/create_ratetable");
                }
                if(isset($_POST['code'])) {
                    $count = count($_POST['code']);
                    for($i=0; $i<$count; $i++) {
                        $code = $_POST['code'][$i];
                        $code_name = $_POST['code_name'][$i];
                        $country = $_POST['country'][$i];
                        $rate = $_POST['rate'][$i];
                        $intra_rate = !empty($_POST['intra_rate'][$i]) ? $_POST['intra_rate'][$i] : 'NULL';
                        $inter_rate = !empty($_POST['inter_rate'][$i]) ? $_POST['intra_rate'][$i] : 'NULL';
                        $effective_rate = $_POST['effective_date'][$i];
                        $effective_date_gmt = $_POST['effective_date_gmt'][$i];
                        $end_date = $_POST['end_date'][$i];
                        $end_date_gmt = $_POST['end_date_gmt'][$i];
                        $end_date = !empty($end_date) ? "'" . $end_date . $end_date_gmt . "'" :  'NULL';
                        $setup_fee = $_POST['setup_fee'][$i];
                        $min_time = $_POST['min_time'][$i];
                        $interval = $_POST['interval'][$i];
                        $grace_time = $_POST['grace_time'][$i];
                        $second = $_POST['second'][$i];
                        $profile = empty($_POST['profile'][$i]) ? 'NULL' : $_POST['profile'][$i];
                        $local_rate = empty($_POST['local_rate'][$i]) ? 'NULL' : $_POST['local_rate'][$i];
                        $ocn = empty($_POST['ocn'][$i]) ? 'NULL' : $_POST['ocn'][$i];
                        $lata = empty($_POST['lata'][$i]) ? 'NULL' : $_POST['lata'][$i];
                        // 检测是否存在同code和同生存时间
                        if($this->Rate->has_exists_code($rate_table_id, $code, $effective_rate,$effective_date_gmt)) {
                            $this->Rate->create_json_array('', 101, __("[{$code}] has exists.", true));
                            continue;
                        }
                        $set_early_date = date("Y-m-d H:i:sO", strtotime($effective_rate.$effective_date_gmt) - 1);
                        $this->Rate->end_early_date($rate_table_id, $code, $set_early_date, $effective_rate, $effective_date_gmt);
                        $sql = "INSERT INTO rate(rate_table_id, code, rate, setup_fee, effective_date, end_date, min_time, grace_time,
                            interval, time_profile_id, seconds, code_name, intra_rate, inter_rate, local_rate, country, ocn, lata)
                            VALUES ($rate_table_id, '$code', $rate, $setup_fee, '{$effective_rate}{$effective_date_gmt}', {$end_date}, 
                            $min_time, $grace_time, $interval, $profile, $second, '{$code_name}', $intra_rate, $inter_rate, $local_rate, '{$country}', {$ocn}, {$lata})";
                        $this->Rate->query($sql);
                    }
                }
                $this->Rate->logging(0, 'Rate Table', "Rate Table Name:{$rate_table_name}");
                $this->Rate->create_json_array('', 201, __('The Rate Table[' .$rate_table_name .'] is added successfully.', true));
                $this->xredirect("/clientrates/view/{$rate_table_id}");
            }
        }
        $code_decks = $this->Rate->get_code_decks();
        $currencies = $this->Rate->get_currencies();  
        $jurisdictions = $this->Rate->get_jurisdictions();
        $timeprofiles = $this->Rate->get_timeprofiles();
        $this->set('code_decks', $code_decks);
        $this->set('currencies', $currencies);
        $this->set('jurisdictions', $jurisdictions);
        $this->set('timeprofiles', $timeprofiles);
    }
    
    public function get_code_name() {
        Configure::write('debug', 0);
        $this->autoLayout = false;
        $this->autoRender = false;
        $query = $_GET['q'];
        $limit = $_GET['limit'];
        $sql = "SELECT DISTINCT name FROM code WHERE code_deck_id = (SELECT code_deck_id FROM code_deck WHERE client_id IS NULL LIMIT 1) AND name ilike '{$query}%' ORDER BY name ASC LIMIT {$limit}";
        $results = $this->Rate->query($sql);
        foreach ($results as $result) {
            echo $result[0]['name'] . '|' . $result[0]['name'] . "\n";
        }
    }
    
    public function add_template() {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $name = $_POST['name'];
        $subject = $_POST['subject'];
        $content = $_POST['content'];
        $sql = "INSERT INTO send_rate_template(name, subject, content) VALUES ('{$name}', '{$subject}', '{$content}') RETURNING id";
        $data = $this->Rate->query($sql);
        echo json_encode($data[0]);
    }
    
    public function getcodenames() {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $rate_table_id = $_POST['rate_table_id'];
        $data = $this->Rate->code_names($rate_table_id);
        echo json_encode($data);
    }
    
    public function rate_templates() {
        $sql = "SELECT * FROM send_rate_template ORDER BY id DESC";
        $data = $this->Rate->query($sql);
        $this->set('data', $data);
    }
    
    public function rate_sending_logging() {
        $temp = isset ($_SESSION['paging_row']) ? $_SESSION['paging_row'] : 100;
        empty($_GET['page'])?$currPage = 1:$currPage = $_GET['page'];
        empty($_GET['size'])?$pageSize = $temp:	$pageSize = $_GET['size'];
        $_SESSION['paging_row'] = $pageSize;
         require_once 'MyPage.php';
        $count = $this->Rate->get_rate_sending_logging_count();
        $page = new MyPage ();
        $page->setTotalRecords ($count); 
        $page->setCurrPage ($currPage);
        $page->setPageSize ($pageSize); 
        $currPage = $page->getCurrPage()-1;
        $pageSize = $page->getPageSize();
        $offset=$currPage*$pageSize;
        $data  = $this->Rate->get_rate_sending_logging($pageSize,$offset);  
        $page->setDataArray($data);
        $this->set('p',$page);
        $status = array('succeed','db error','smtp error','other error');
        $this->set('status', $status);
    }
    
    
    public function get_file($file) {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $file = base64_decode($file);
        header("Content-type: application/octet-stream");
        header('Content-Disposition: attachment; filename="' . basename($file) . '"');
        header("Content-Length: ". filesize($file));
        readfile($file);
    }
    
    public function rate_sending() {
        if($this->RequestHandler->isPost()) {
            Configure::load('myconf');
            $ip = Configure::read("sendrate.ip");
            $port = Configure::read("sendrate.port");
            
            $_POST['myfile_guid'] = WWW_ROOT . "upload" . DIRECTORY_SEPARATOR . "email_list" . DIRECTORY_SEPARATOR . $_POST['myfile_guid'];
            
            $data = json_encode($_POST);
            $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
            if ($socket === false) {
                echo "socket_create() failed: reason: " . socket_strerror(socket_last_error()) . "\n";
            }
            $result = socket_connect($socket, $ip, $port);
            if ($result === false) {
                echo "socket_connect() failed.\nReason: ($result) " . socket_strerror(socket_last_error($socket)) . "\n";
            }
            socket_write($socket, $data, strlen($data));
            
            socket_close($socket);
            $this->Rate->create_json_array('', 201, __('Successfully!', true));
            $this->Session->write('m', Rate::set_validator());
        }
        // 取得数据
        $rate_tables = $this->Rate->rate_tables();
        $carriers = $this->Rate->get_carriers();
        $templates = $this->Rate->template_lists();
        $this->set('rate_tables', $rate_tables);
        $this->set('carriers', $carriers);
        $this->set('templates', $templates);
        $fields = array(
            'code','inter_rate', 'intra_rate', 'current_rate', 'new_rate', 'interval' ,'min_time' , 'effective_date', 'country' ,'code_name', 'status'
        );
        $this->set('fields', $fields);
    }
    
    
    public function edit_template($id) {
        if($this->RequestHandler->isPost()) {
            $name = $_POST['name'];
            $subject = $_POST['subject'];
            $content = $_POST['content'];
            $sql = "update send_rate_template set name = '{$name}', subject = '{$subject}', content = '{$content}' WHERE id = {$id}";
            $this->Rate->query($sql);
            $this->Rate->create_json_array('', 201, __('Successfully!', true));
            $this->Session->write('m', Rate::set_validator());
        }
        $sql = "SELECT * FROM send_rate_template WHERE id = {$id}";
        $data = $this->Rate->query($sql);
        $this->set('data', $data);
    }
    
    public function delete_template($id) {
        $this->autoRender = false;
        $this->autoLayout = false;
        $sql = "delete from send_rate_template where id = {$id}";
        $this->Rate->query($sql);
        $this->Rate->create_json_array('', 201, __('Successfully!', true));
        $this->Session->write('m', Rate::set_validator());
        $this->redirect('/rates/rate_templates');
    }

    function export_log()
    {
        $this->loadModel('ImportExportLog');
        $data = $this->ImportExportLog->find('all', array(
            'conditions' => array(
                'upload_type' => 10,
                'log_type' => ImportExportLog::LOG_TYPE_EXPORT
            )
        ));

        $this->set('data', $data);
    }

    function download_exported_rate($encodedId)
    {
        $this->loadModel('ImportExportLog');

        $id = base64_decode($encodedId);
        $record = $this->ImportExportLog->find('first', array(
            'fields' => array('file_path'),
            'conditions' => array(
                'id' => $id
            )
        ));

        $filename = basename($record['ImportExportLog']['file_path']);
        $fileSize = filesize($record['ImportExportLog']['file_path']);

        ob_clean();
        header("Content-Type: text/csv");
        header("Content-Disposition: attachment; filename={$filename}");
        header("Content-Length: {$fileSize}");
        header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
        header('Expires:0');
        header('Pragma:public');
        readfile($record['ImportExportLog']['file_path']);
        exit();
    }
}
