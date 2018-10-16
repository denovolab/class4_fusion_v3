<?php

class BlackHoleController extends AppController {

    const FGI_API_URL = 'http://ip-api.com/php/';

    var $name = 'BlackHole';
    var $components = array('RequestHandler');
    var $helpers = array('javascript', 'html');
    var $uses = array("SpamTrafficIp");

    public function beforeFilter() {
        parent::beforeFilter();
        $this->checkSession("login_type");
    }

    public function index() {
        $this->redirect('ip_list');
    }

    public function upload()
    {
        if($this->RequestHandler->isPost()) {
            $path = APP . 'tmp' . DS . 'upload' . DS . 'csv';
            $myfile_guid = trim(preg_replace('/\s\s+/', '', $_POST['myfile_guid'] ));
            $fullPath = $path . DS . $myfile_guid . '.csv';

            $created_by = $_SESSION["sst_user_name"]?:'';

            $array = $fields = array(); $i = 0;
            $handle = @fopen($fullPath, "r");
            //echo "<pre>";var_dump($fullPath,$_POST['myfile_guid'],  file_exists($fullPath));die;
            if ($handle) {
                while (($row = fgetcsv($handle, 4096)) !== false) {
                    if (empty($fields)) {
                        $fields = $row;
                        continue;
                    }
                    foreach ($row as $k=>$value) {
                        $array[$i][$fields[$k]] = $value;
                    }
                    $i++;
                }
                fclose($handle);
            }

            foreach ($array as $key => $item) {
                $brief = '';
                $content = $this->_get_ip_data(self::FGI_API_URL.$item['ip']);
                if($content) {
                    $brief = $content["org"];
                }
                $array[$key]['brief'] = $brief;
                $array[$key]['created_by'] = $created_by ;
            }
            $saveResult = $this->SpamTrafficIp->saveAll($array);

            if($saveResult) {
                $this->Session->write('m', $this->SpamTrafficIp->create_json(201, 'Uploaded successfully!'));
                $this->redirect('/black_hole/ip_list');
            } else {
                $this->Session->write('m', $this->SpamTrafficIp->create_json(101, 'Upload failed!'));
                $this->redirect('/black_hole/upload');
            }
        }
    }

    public function ip_list() {

        $data = $this->SpamTrafficIp->find('all');
        $this->pageTitle = __('Block Media IP', true);
        $conditions = array();
        $search_char = trim($this->_get('search_char'));
        if ($search_char) {
            $conditions = "ip like '%" . $search_char . "%' or brief like '%" . $search_char . "%'";
        }
        $pageSize = $this->_get('size') ? $this->_get('size') : 100;

        $order_arr = array('create_time' => 'desc');
        if (isset($this->params['url']['order_by'])) {
            $order_by = $this->params['url']['order_by'];
            $order_arr = explode('-', $order_by);
            if (count($order_arr) == 2) {
                $field = $order_arr[0];
                $sort = $order_arr[1];
                $order_arr = array($field => $sort);
            }
        }

        $this->paginate = array('fields' => array(), 'limit' => $pageSize, 'order' => $order_arr, 'conditions' => $conditions,);
        $this->data = $this->paginate('SpamTrafficIp');
        foreach($this->data as &$item){
            if(!$item["SpamTrafficIp"]["brief"] && $item["SpamTrafficIp"]["ip"]){
                $get_ip_data = $this->_get_ip_data(self::FGI_API_URL.$item["SpamTrafficIp"]["ip"]);
                if($get_ip_data){
                    $item["SpamTrafficIp"]["brief"] = $get_ip_data["org"];
                }
            }
            $item["SpamTrafficIp"]["ip"] = $item["SpamTrafficIp"]["netmask"] ?: $item["SpamTrafficIp"]["ip"];
        }
    }

    private function _get_ip_data($url) {
        $get_ip_data = file_get_contents($url);
        if($get_ip_data){
            return  unserialize($get_ip_data);
        }
        return false;
    }

    public function ajax_check_ip() {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;

        $ip = $this->params['url']['fieldValue'];
        $ajax_id = $this->params['url']['fieldId'];

        $count = $this->SpamTrafficIp->find('count', array('conditions' => array('ip' => $ip),));
        if ($count) {
            return json_encode(array($ajax_id, false));
        } else {
            return json_encode(array($ajax_id, true));
        }
    }

    public function save_ip() {
        //Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;

        $ip = $this->params['form']['ip'];
        // $brief = $this->params['form']['detail'];
        //$brief = gethostbyaddr($ip);
        $created_by = $_SESSION["sst_user_name"]?:'';
        $content = $this->_get_ip_data(self::FGI_API_URL.$ip);
        if($content) {
            $brief = $content["country_name"];
        }
        $this->SpamTrafficIp->save(array('ip' => $ip, 'brief' => $brief, 'created_by' => $created_by));
        if ($this->SpamTrafficIp->save(array('ip' => $ip, 'brief' => $brief, 'created_by' => $created_by)) === false) {
            $this->Session->write('m', $this->SpamTrafficIp->create_json(101, 'Save Failed!'));
        } else {
            $this->Session->write('m', $this->SpamTrafficIp->create_json(201, 'BlackHole IP [' . $ip . '] is created successfully!'));
        }
        $this->redirect('ip_list');
    }

    public function delete_ip() {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;

        $encode_ip = $this->params['pass'][0];
        if (!$encode_ip) {
            $this->Session->write('m', $this->SpamTrafficIp->create_json(101, 'Illegal operation!'));
            $this->redirect('ip_list');
        }
        $ip = base64_decode($encode_ip);

        if ($this->SpamTrafficIp->deleteAll(array('ip' => $ip)) === false) {
            $this->Session->write('m', $this->SpamTrafficIp->create_json(101, 'Save Failed!'));
        } else {
            $this->Session->write('m', $this->SpamTrafficIp->create_json(201, 'BlackHole IP [' . $ip . '] is deleted successfully!'));
        }
        $this->redirect('ip_list');
    }


    function mask2bin($n) {
        $n = intval($n);
        if ($n < 0 || $n > 32) {
            return false;
        }
        return str_repeat("1", $n) . str_repeat("0", 32 - $n);
    }

    function revBin($s) {
        $p = array('0', '1', '2');
        $r = array('2', '0', '1');

        return str_replace($p, $r, $s);
    }

    function startIp($str, $bSub) {
        $bIp = decbin($str);
        $bIp = str_pad($bIp, 8, "0", STR_PAD_LEFT);
        $sIp = bindec($bIp & $bSub);
        return $sIp;
    }

    function endIp($str, $bSub) {
        $bIp = decbin($str);
        $bIp = str_pad($bIp, 8, "0", STR_PAD_LEFT);
        $eIp = bindec($bIp | $this->revBin($bSub));
        return $eIp;
    }

    function get_ip_by_netmask() {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $ip = explode('.', $this->params['form']['ip']);
        $mask = $this->params['form']['netMask'];          //设置掩码
        $bSub = $this->mask2bin($mask);     //将子网掩码转换二进制
        $mask = array();
        $mask[] = substr($bSub, "0", 8);  //将子网掩码每8位分一段
        $mask[] = substr($bSub, "8", 8);
        $mask[] = substr($bSub, "16", 8);
        $mask[] = substr($bSub, "24", 8);

        $start_ip_arr = array();
        $start_flg = '';
        for ($i = 0; $i < 3; $i++) {
            $start_ip_arr[] = $this->startIp($ip[$i], $mask[$i]);
            $start_flg .= $this->startIp($ip[$i], $mask[$i]);
        }
        $ip_4 = $this->startIp($ip[3], $mask[3]);
        $start_ip_arr[] = ++$ip_4;

        $msg = "start ip is :" . implode('.', $start_ip_arr);

        $end_ip_arr = array();
        $end_flg = '';
        for ($i = 0; $i < 3; $i++) {
            $end_ip_arr[] = $this->endIp($ip[$i], $mask[$i]);
            $end_flg .= $this->endIp($ip[$i], $mask[$i]);
        }
        $ip_4 = $this->endIp($ip[3], $mask[3]);
        $end_ip_arr[] = --$ip_4;
        $msg .= " And end ip is :" . implode('.', $end_ip_arr);

        $return_arr = array('status' => 1, 'msg' => "<p style='color: green'>$msg</p>",);
        if (strcmp($start_flg, $end_flg)) {
            $return_arr = array('status' => 0, 'msg' => "<p style='color: red'>$msg</p>",);
        }

        return json_encode($return_arr);
    }

    public function save_ip_by_netmask(){
        $ip = explode('.', $this->params['form']['netMask_ip']);
        $mask = $this->params['form']['netMask'];          //设置掩码
        $bSub = $this->mask2bin($mask);     //将子网掩码转换二进制

        if ($bSub === false){
            $this->Session->write('m', $this->SpamTrafficIp->create_json(101, 'Save Failed!'));
            $this->redirect('ip_list');
        }
        $mask = array();
        $mask[] = substr($bSub, "0", 8);  //将子网掩码每8位分一段
        $mask[] = substr($bSub, "8", 8);
        $mask[] = substr($bSub, "16", 8);
        $mask[] = substr($bSub, "24", 8);
        $start_ip_arr = array();
        $start_flg = array();
        for ($i = 0; $i < 3; $i++) {
            $start_ip_arr[] = $this->startIp($ip[$i], $mask[$i]);
            $start_flg[] = $this->startIp($ip[$i], $mask[$i]);
        }
        $start_ip_4 = $this->startIp($ip[3], $mask[3]);
        $start_ip_arr[] = ++$start_ip_4;

        $end_ip_arr = array();
        $end_flg = array();
        for ($i = 0; $i < 3; $i++) {
            $end_ip_arr[] = $this->endIp($ip[$i], $mask[$i]);
            $end_flg[] = $this->endIp($ip[$i], $mask[$i]);
        }
        $end_ip_4 = $this->endIp($ip[3], $mask[3]);
        $end_ip_arr[] = --$end_ip_4;

        if (!empty(array_diff($start_flg, $end_flg))) {
            $this->Session->write('m', $this->SpamTrafficIp->create_json(101, 'The IP is not in the same subnet'));
            $this->redirect('ip_list');
        }

        $insert_arr = array();
        $start_code = $start_ip_4;
        $end_code = $end_ip_4;
        if ($start_ip_4 > $end_ip_4){
            $start_code = $end_ip_4;
            $end_code = $start_ip_4;
        }
        for ($start_code; $start_code <= $end_code; $start_code ++){

            $insert_arr[] = array(
                'ip' => implode('.',$start_flg) . '.' . $start_code,
                'brief' => $this->params['form']['detail'],
                'netmask' => intval($bSub)
            );
        }
        $flg = $this->SpamTrafficIp->saveAll($insert_arr);
        if ($flg === false){
            $this->Session->write('m', $this->SpamTrafficIp->create_json(101, 'Save Failed!'));
        }else{
            $this->Session->write('m', $this->SpamTrafficIp->create_json(201, 'BlackHole IP [' . $ip . '] is created successfully!'));
        }
        $this->redirect('ip_list');
    }


    public function delete_ip_by_netmask(){
        $ip = explode('.', $this->params['form']['netMask_ip']);
        $mask = $this->params['form']['netMask'];          //设置掩码
        $bSub = $this->mask2bin($mask);     //将子网掩码转换二进制
        if ($bSub === false){
            $this->Session->write('m', $this->SpamTrafficIp->create_json(101, 'Delete Failed!'));
            $this->redirect('ip_list');
        }
        $mask = array();
        $mask[] = substr($bSub, "0", 8);  //将子网掩码每8位分一段
        $mask[] = substr($bSub, "8", 8);
        $mask[] = substr($bSub, "16", 8);
        $mask[] = substr($bSub, "24", 8);

        $start_ip_arr = array();
        $start_flg = array();
        for ($i = 0; $i < 3; $i++) {
            $start_ip_arr[] = $this->startIp($ip[$i], $mask[$i]);
            $start_flg[] = $this->startIp($ip[$i], $mask[$i]);
        }
        $start_ip_4 = $this->startIp($ip[3], $mask[3]);
        $start_ip_arr[] = ++$start_ip_4;

        $end_ip_arr = array();
        $end_flg = array();
        for ($i = 0; $i < 3; $i++) {
            $end_ip_arr[] = $this->endIp($ip[$i], $mask[$i]);
            $end_flg[] = $this->endIp($ip[$i], $mask[$i]);
        }
        $end_ip_4 = $this->endIp($ip[3], $mask[3]);
        $end_ip_arr[] = --$end_ip_4;

        if (!empty(array_diff($start_flg, $end_flg))) {
            $this->Session->write('m', $this->SpamTrafficIp->create_json(101, 'The IP is not in the same subnet'));
            $this->redirect('ip_list');
        }

        $delete_arr = array();
        $start_code = $start_ip_4;
        $end_code = $end_ip_4;
        if ($start_ip_4 > $end_ip_4){
            $start_code = $end_ip_4;
            $end_code = $start_ip_4;
        }
        for ($start_code; $start_code <= $end_code; $start_code ++){

            $delete_arr[] = implode('.',$start_flg) . '.' . $start_code;
        }
        $flg = $this->SpamTrafficIp->deleteAll(array('ip' => $delete_arr));
        if ($flg === false){
            $this->Session->write('m', $this->SpamTrafficIp->create_json(101, 'Delete Failed!'));
        }else{
            $this->Session->write('m', $this->SpamTrafficIp->create_json(201, 'BlackHole IP [' . $ip . '] is deleted successfully!'));
        }
        $this->redirect('ip_list');
    }


}

?>
