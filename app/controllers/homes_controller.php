<?php

class HomesController extends AppController {

    var $name = 'Homes';
    var $uses = array('User', 'Onlineuser', 'Client', 'Reseller');
    var $helper = array('html', 'javascript', 'RequestHandler');

    /* 	public function beforeFilter(){
      //parent::beforeFilter();//调用父类方法
      } */

    public function permission_denied() {
        
    }
    
    public function beforeFilter()
    {
        
    }

    public function no_data() {
        
    }

    public function bad_url() {
        
    }

    public function test() {
        $this->auth_ip(1024, '192.168.1.120');
    }

    public function check_ip() {
        $ip = "192.168.1.115";
        $user_ip = '192.168.1.101';
        $user_ip2 = '192.168.2.115';

        //explode()
        $netmask = "255.255.255.0"; //24
        $ip_int = bindec(decbin(ip2long($ip)));
        $mask_int = bindec(decbin(ip2long($netmask)));

        $user_ip_int = bindec(decbin(ip2long($user_ip)));
        $user_ip2_int = bindec(decbin(ip2long($user_ip2)));

        if ($ip_int & $mask_int == $user_ip_int & $mask_int) {
            pr(' equ');
        } else {

            pr("不相等");
        }
    }

    public function beforeFilter1() {
        $this->checkSession("login_type"); //核查用户身份
        $login_type = $this->Session->read('login_type');
        if ($login_type == 1) {
            $this->Session->write('executable', true);
            $this->Session->write('writable', true);
        } else {
            $limit = $this->Session->read('sst_tools_sipcapture');
            $this->Session->write('executable', $limit['executable']);
            $this->Session->write('writable', $limit['writable']);
        }
        parent::beforeFilter();
    }

    public function show_charts() {
        $type = $_POST['type'];
        $group_time = $_POST['group_time'];
        $report_type = $_POST['report_type'];
        $timezone = $_POST['timezone'];
        $start_time = $_POST['start_time'];
        $end_time = $_POST['end_time'];
        $country = $_POST['country'];
        $destination = $_POST['destination'];
        $ingress_trunk = $_POST['ingress_trunk'];
        $egress_trunk = $_POST['egress_trunk'];

        $this->set('start_time', $start_time);
        $this->set('end_time', $end_time);
        $this->set('timezone', $timezone);

        $params = array();

        array_push($params, "type=" . urlencode($type));
        array_push($params, "report_type=" . urlencode($report_type));
        array_push($params, "group_time=" . urlencode($group_time));
        if (!empty($start_time))
            array_push($params, "start_time=" . urlencode($start_time));
        if (!empty($end_time))
            array_push($params, "end_time=" . urlencode($end_time));
        if (!empty($country))
            array_push($params, "country_search=" . urlencode($country));
        if (!empty($destination))
            array_push($params, "destination=" . urlencode($destination));
        if (!empty($ingress_trunk))
            array_push($params, "ingress_trunk=" . urlencode($ingress_trunk));
        if (!empty($egress_trunk))
            array_push($params, "egress_trunk=" . urlencode($egress_trunk));
        if (!empty($timezone))
            array_push($params, "timezone=" . urlencode($timezone));

        $param = implode("&", $params);
        $this->set('param', bin2hex(gzcompress($param, 9)));
    }

    public function get_charts_data() {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $type = $_GET['type'];
        $group_time = $_GET['group_time'];
        $report_type = $_GET['report_type'];
        $timezone = $_GET['timezone'];
        $start_time = $_GET['start_time'];
        $end_time = $_GET['end_time'];
        $country = $_GET['country'];
        $destination = $_GET['destination'];
        $ingress_trunk = $_GET['ingress_trunk'];
        $egress_trunk = $_GET['egress_trunk'];

        $group_time_str = "";
        switch ($group_time) {
            case 0:
                $group_time_str = "date_trunc('day', report_time)";
                break;
            case 1:
                $group_time_str = "date_trunc('hour', report_time)";
                break;
        }

        $content = "";

        switch ($type) {
            case 0:
                $content = $this->Onlineuser->get_asr_chart($report_type, $start_time, $end_time, $timezone, $country, $destination, $ingress_trunk, $egress_trunk, $group_time_str);
                break;
            case 1:
                $content = $this->Onlineuser->get_acd_chart($report_type, $start_time, $end_time, $timezone, $country, $destination, $ingress_trunk, $egress_trunk, $group_time_str);
                break;
            case 2:
                $content = $this->Onlineuser->get_total_calls_chart($report_type, $start_time, $end_time, $timezone, $country, $destination, $ingress_trunk, $egress_trunk, $group_time_str);
                break;
            case 3:
                $content = $this->Onlineuser->get_total_billable_time_chart($report_type, $start_time, $end_time, $timezone, $country, $destination, $ingress_trunk, $egress_trunk, $group_time_str);
                break;
            case 4:
                $content = $this->Onlineuser->get_total_pdd_chart($report_type, $start_time, $end_time, $timezone, $country, $destination, $ingress_trunk, $egress_trunk, $group_time_str);
                break;
            case 5:
                $content = $this->Onlineuser->get_total_cost_chart($report_type, $start_time, $end_time, $timezone, $country, $destination, $ingress_trunk, $egress_trunk, $group_time_str);
                break;
            case 6:
                $content = $this->Onlineuser->get_total_margin_chart($report_type, $start_time, $end_time, $timezone, $country, $destination, $ingress_trunk, $egress_trunk, $group_time_str);
                break;
            case 7:
                $content = $this->Onlineuser->get_total_call_attemp($report_type, $start_time, $end_time, $timezone, $country, $destination, $ingress_trunk, $egress_trunk, $group_time_str);
        }

        echo $content;
    }

    public function search_charts() {
        $date = date("Y-m-d", strtotime("-1 days"));
        $this->set('date', $date);
        $this->set('ingress_trunks', $this->Onlineuser->getIngressResource());
        $this->set('egress_trunks', $this->Onlineuser->getEgressResource());
    }

    public function report() {
        $date = date("Y-m-d");
        $this->set('date', $date);
        $this->set('ingress_trunks', $this->Onlineuser->getIngressResource());
        $this->set('egress_trunks', $this->Onlineuser->getEgressResource());
    }

    public function show_report() {
        Configure::write('debug', 0);
        $this->autoLayout = FALSE;
        $this->autoRender = FALSE;
        $report_type = $_POST['report_type'];
        $timezone = $_POST['timezone'];
        $start_time = $_POST['start_time'];
        $end_time = $_POST['end_time'];
        $country = $_POST['country'];
        $destination = $_POST['destination'];
        $ingress_trunk = $_POST['ingress_trunk'];
        $egress_trunk = $_POST['egress_trunk'];

        $this->set('start_time', $start_time);
        $this->set('end_time', $end_time);
        $this->set('timezone', $timezone);
        /*
          $this->set('end_time', $country);
          $this->set('destination', $destination);
          $this->set('ingress_trunk', $ingress_trunk);
          $this->set('egress_trunk', $egress_trunk);
         * 
         */
        $params = array();

        if (!empty($start_time))
            array_push($params, "start_time=" . urlencode($start_time));
        if (!empty($end_time))
            array_push($params, "end_time=" . urlencode($end_time));
        if (!empty($country))
            array_push($params, "country_search=" . urlencode($country));
        if (!empty($destination))
            array_push($params, "destination=" . urlencode($destination));
        if (!empty($ingress_trunk))
            array_push($params, "ingress_trunk=" . urlencode($ingress_trunk));
        if (!empty($egress_trunk))
            array_push($params, "egress_trunk=" . urlencode($egress_trunk));
        if (!empty($timezone))
            array_push($params, "timezone=" . urlencode($timezone));

        $param = implode("&", $params);
        $this->set('param', $param);

        switch ($report_type) {
            case 0:
                $this->render('orig_report');
                break;

            case 1:
                $this->render('term_report');
                break;

            case 2:
                $this->render('dest_report');
                break;
        }
    }

    public function dashbroad() {
        $this->beforeFilter1();
    }

    /*
     * param $type:  1.network 2.orig  3.term
     * param $type:  1. 1 hour  2. 24 hour
     */

    public function get_draws_data() {
        Configure::write('debug', 0);
        $this->autoLayout = false;
        $this->autoRender = false;

        $type = $_POST['type'];
        $duration = $_POST['duration'];
        $trunk = $_POST['trunk'];
        $trunk_ip = $_POST['trunk_ip'];
        $data_type = $_POST['data_type'];
        $content = array();
        
        if($data_type == '1')
        {
            switch ($type) {
                case 1:
                    $data = $this->Onlineuser->get_network_total($duration);
                    $content = $this->_ready_network_data($data);
                    break;
                case 2:
                    $content = $this->Onlineuser->get_draw_trunk_data(0, $duration, $trunk, $trunk_ip);
                    break;
                case 3:
                    $content = $this->Onlineuser->get_draw_trunk_data(1, $duration, $trunk, $trunk_ip);
                    break;
            }
        }
        else if($data_type == '2')
        {
        	/*
            switch ($type)
            {
                case 1:
                    $content = $this->Onlineuser->get_network_call_atempts($duration);
                    break;
                case 2:
                	
            }
            */
            $content = $this->Onlineuser->get_network_call_atempts($duration, $type);
        }

        echo json_encode($content);
    }

    public function _ready_network_data($data) {
        $draw_lines_call = array();
        $draw_lines_cps = array();
        $draw_lines_channel = array();
        foreach ($data as $item) {
            if ($item[0]['call'] > 0) 
            array_push($draw_lines_call, sprintf('<point x="%.11E" y="%s" />', strtotime($item[0]['report_time']) * 1000, $item[0]['call']));
            if ($item[0]['cps'] > 0) 
            array_push($draw_lines_cps, sprintf('<point x="%.11E" y="%s" />', strtotime($item[0]['report_time']) * 1000, $item[0]['cps']));
            if ($item[0]['channel'] > 0) 
            array_push($draw_lines_channel, sprintf('<point x="%.11E" y="%s" />', strtotime($item[0]['report_time']) * 1000, $item[0]['channel']));
        }
        if (empty($draw_lines_call)) {
            array_push($draw_lines_call, sprintf('<point x="%.11E" y="%s" />', time() * 1000, 0));
        }
        $draw_line_call = implode("\r\n", $draw_lines_call);
        $content_call = <<<EOT
<chart type="cartesian" legendPlacement="top">
  <xaxis type="datetime" />
  <yaxis type="linear" label="Call" />
  <data>
      <series type="line" label="Total">
          {$draw_line_call}
      </series>
  </data>
</chart>
EOT;
        if (empty($draw_lines_cps)) {
            array_push($draw_lines_cps, sprintf('<point x="%.11E" y="%s" />', time() * 1000, 0));
        }
        $draw_line_cps = implode("\r\n", $draw_lines_cps);
        $content_cps = <<<EOT
<chart type="cartesian" legendPlacement="top">
  <xaxis type="datetime" />
  <yaxis type="linear" label="CPS" />
  <data>
      <series type="line" label="Total">
          {$draw_line_cps}
      </series>
  </data>
</chart>
EOT;
          if (empty($draw_lines_channel)) {
            array_push($draw_lines_channel, sprintf('<point x="%.11E" y="%s" />', time() * 1000, 0));
        }
        $draw_line_channel = implode("\r\n", $draw_lines_channel);
        $content_channel = <<<EOT
<chart type="cartesian" legendPlacement="top">
  <xaxis type="datetime" />
  <yaxis type="linear" label="Channel" />
  <data>
      <series type="line" label="Total">
          {$draw_line_channel}
      </series>
  </data>
</chart>
EOT;
        return array(
            'call' => $content_call,
            'cps' => $content_cps,
            'channel' => $content_channel,
        );
    }

    public function get_mask($mask) {
        $net_mask_array = array(
            '10' => '255.192.0.0', '11' => '255.224.0.0  ', '12' => '255.240.0.0', '13' => '255.248.0.0', '14' => '255.252.0.0', '15' => '255.254.0.0', '16' => ' 255.255.0.0',
            '17' => '255.255.128.0 ', '18' => '255.255.192.0 ', '19' => '255.255.224.0', '20' => '255.255.240.0', '21' => '255.255.248.0', '22' => '255.255.252.0',
            '23' => '255.255.254.0', '24' => '255.255.255.0 ', '25' => '255.255.255.128', '26' => '255.255.255.192 ', '27' => '255.255.255.224', '28' => '255.255.255.240', '29' => ' 255.255.255.248',
            '30' => '255.255.255.252 '
        );
        if (isset($net_mask_array[$mask])) {
            return $net_mask_array[$mask];
        } else {
            'no_mask';
        }
    }

    public function get_trunks($type) {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        if ($type == 2) {
            $sql = "SELECT resource_id, alias FROM resource WHERE ingress=true and active=true ORDER BY alias";
        } else {
            $sql = "SELECT resource_id, alias FROM resource WHERE egress=true and active=true ORDER BY alias";
        }
        echo json_encode($this->User->query($sql));
    }

    public function get_trunk_ips($trunk_id) {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $sql = "SELECT resource_ip_id,ip FROM resource_ip WHERE resource_id = {$trunk_id}";
        echo json_encode($this->User->query($sql));
    }

    public function auth_ip($user_id, $user_ip) {
        $auth_flag = false;
        $user_auth_ip = $this->User->query("select  count(*) as cnt from  user_auth_ip  where user_id = {$user_id}");
        if (0 == $user_auth_ip[0][0]['cnt']) {
            $auth_flag = true;
        } else {
            $ip_list = $this->User->query("select  count(*) as cnt from  user_auth_ip  where user_id = {$user_id} and ip = '{$user_ip}'");
            if (!empty($ip_list[0][0]['cnt'])) {
                $auth_flag = true;
            }
        }
        return $auth_flag;
        if (0) {
            $user_ip_int = bindec(decbin(ip2long($user_ip)));
            $list = $this->User->query("select  ip from  user_auth_ip  where user_id=$user_id");
            if (!empty($list[0])) {
                foreach ($list as $key => $value) {
                    $t = $value[0]['ip'];
                    $arr = split("/", $t);
                    $ip = $arr[0];
                    $mask = isset($arr[1]) ? $arr[1] : '32';
                    $netmask = $this->get_mask($mask);
                    //no  netmask
                    if ($netmask == 'no_mask') {
                        //没有netmask的情况 直接比较IP
                        if ($user_ip == $ip) {
                            return true;
                        } else {
                            continue;
                        }
                    } else {
                        //有netmask
                        $ip_int = bindec(decbin(ip2long($ip)));
                        $mask_int = bindec(decbin(ip2long($netmask)));
                        if (bindec(decbin($ip_int & $mask_int)) == bindec(decbin($user_ip_int & $mask_int))) {
                            //合法IP
                            return true;  //验证通过
                        } else {

                            continue;
                        }
                    }
                }
            } else {
                return true;  //验证通过
            }
        }
    }

    public function init_sys_timezone() {
        $list = $this->User->query("select sys_timezone from  system_parameter limit 1");
        if (isset($list[0][0]['sys_timezone']) && !empty($list[0][0]['sys_timezone'])) {
            $_SESSION['sys_timezone'] = $list[0][0]['sys_timezone'];
        } else {
            $_SESSION['sys_timezone'] = '+0000'; //gmt
        }
    }

    //获取用户登录角色权限模块
    public function init_role_menu($role_id) {
        if (!PRI) {
            $list = $this->User->query("select *  from  role where  role_id=$role_id");
            if (isset($list[0][0]) && !empty($list[0][0])) {
                $s = $list[0][0];
                unset($s['role_id']);
                unset($s['client_id']);
                unset($s['default_sysfunc_id']);
                unset($s['view_pw]']);
            }
            $_SESSION['role_menu'] = $s;
        }
        if (PRI) {
            $return = array();
            $sql = "SELECT delete_invoice, delete_payment, delete_credit_note, delete_debit_note, reset_balance, modify_credit_limit, modify_min_profit, role_name FROM sys_role WHERE role_id = {$role_id}";
            $payment_invoice_info = $this->User->query($sql);
            if ('admin' == trim($payment_invoice_info[0][0]['role_name'])) {
                $sql = "select  sys_pri.pri_name, sys_pri.pri_val, sys_pri.pri_url, sys_module.module_name 
from  sys_pri
left join sys_module on sys_pri.module_id = sys_module.id where sys_pri.flag = true 
order by 
module_name, sys_pri.pri_val, order_num, sys_pri.id asc";
                $list = $this->User->query($sql);
                if (!empty($list)) {
                    foreach ($list as $k => $v) {
                        $v[0]['model_r'] = TRUE;
                        $v[0]['model_w'] = TRUE;
                        $v[0]['model_x'] = TRUE;
                        $return[$v[0]['module_name']][$v[0]['pri_name']] = $v[0];
                    }
                }
                $return['Payment_Invoice']['delete_invoice'] = TRUE;
                $return['Payment_Invoice']['delete_payment'] = TRUE;
                $return['Payment_Invoice']['delete_credit_note'] = TRUE;
                $return['Payment_Invoice']['delete_debit_note'] = TRUE;
                $return['Payment_Invoice']['reset_balance'] = TRUE;
                $return['Payment_Invoice']['modify_credit_limit'] = TRUE;
                $return['Payment_Invoice']['modify_min_profit'] = TRUE;
            } else {
                $sql = "select sys_role_pri.*, sys_pri.pri_name, sys_pri.pri_val, sys_pri.pri_url, sys_module.module_name from sys_role_pri left join sys_pri on sys_role_pri.pri_name = sys_pri.pri_name left join sys_module on sys_pri.module_id = sys_module.id where sys_pri.flag = true and sys_role_pri.role_id = " . intval($role_id) . " order by module_name, sys_pri.pri_val, order_num, sys_pri.id asc";
                $list = $this->User->query($sql);
                if (!empty($list)) {
                    foreach ($list as $k => $v) {
                        $return[$v[0]['module_name']][$v[0]['pri_name']] = $v[0];
                    }
                }
                $return['Payment_Invoice']['delete_invoice'] = $payment_invoice_info[0][0]['delete_invoice'];
                $return['Payment_Invoice']['delete_payment'] = $payment_invoice_info[0][0]['delete_payment'];
                $return['Payment_Invoice']['delete_credit_note'] = $payment_invoice_info[0][0]['delete_credit_note'];
                $return['Payment_Invoice']['delete_debit_note'] = $payment_invoice_info[0][0]['delete_debit_note'];
                $return['Payment_Invoice']['reset_balance'] = $payment_invoice_info[0][0]['reset_balance'];
                $return['Payment_Invoice']['modify_credit_limit'] = $payment_invoice_info[0][0]['modify_credit_limit'];
                $return['Payment_Invoice']['modify_min_profit'] = $payment_invoice_info[0][0]['modify_min_profit'];
            }
            Configure::load('myconf');
            if (!Configure::read('did.enable'))
            {
                if (isset($return['Origination']))
                {
                     unset($return['Origination']);
                }
                if (isset($return['Statistics']['reports/did']))
                {
                     unset($return['Statistics']['reports/did']);
                }
            }
            $_SESSION['role_menu'] = $return;
        }
    }

    //初始登录后跳转页面
    public function init_login_url($user_id = 0) {
        if (!PRI) {
            extract($_SESSION['role_menu']);
            if ($is_carriers) {
                //$this->redirect("/clients/index");
                $this->redirect("/homes/dashbroad");
            }
            if ($is_cdr_list) {
                $this->redirect("/cdrreports/summary_reports");
            }
            if ($is_call_simulation) {
                $this->redirect("/simulatedcalls/simulated_call");
            }
            if ($is_digit_mapping) {
                $this->redirect("/digits/view");
            }
            if ($is_rate_table) {
                $this->redirect("/rates/rates_list");
            }
            if ($is_role) {
                $this->redirect("/roles/view");
            }
            if ($is_import_log) {
                $this->redirect("/import_export_log/import");
            }
            if ($is_export_log) {
                $this->redirect("/import_export_log/export");
            }
        }
        if (PRI) {

            $role_menu = $_SESSION['role_menu'];

            if (!empty($role_menu)) {
                /*
                  foreach ($role_menu as $k=>$v)
                  {
                  foreach ($v as $k1=>$v1)
                  {
                  if (!empty($v1['pri_url']) && $v1['model_r'] == 't')
                  {
                  $this->redirect('/'.$v1['pri_url']);
                  break 2;
                  }
                  }
                  }
                 */
                $sql = "SELECT pri_url FROM sys_pri WHERE id = (SELECT default_mod FROM users WHERE user_id = {$user_id})";
                $url_info = $this->User->query($sql);
                if (empty($url_info[0][0]['pri_url'])) {
                    #$this->redirect('/clients/index');
                    $sql = "SELECT landing_page FROM system_parameter LIMIT 1";
                    $landing_page = $this->User->query($sql);
                    $landing_page = empty($landing_page) ? 0: $landing_page;
                    switch ($landing_page[0][0]['landing_page']) {
                        case 0:
                            $url = "/monitorsreports/globalstats";
                            break;
                        case 1:
                            $url = "/reports/summary";
                            break;
                        case 2:
                            $url = "/reports/inout_report";
                            break;
                        case 3:
                            $url = "/clients/index";
                            break;
                    }
                    $this->redirect($url);
                } else {
                    $this->redirect('/' . $url_info[0][0]['pri_url']);
                }
            }
        }


        //$this->redirect('/homes/no_data');
    }

    public function init_system() {
        $list = $this->User->query("select  sys_currency  from    system_parameter");
        $sys_currency = !empty($list[0][0]['sys_currency']) ? $list[0][0]['sys_currency'] : '';
        $list = $this->User->query("
			   select rate  from currency_updates where currency_id = (
		   				select  currency_id  from  currency  where code=(select  sys_currency  from    system_parameter)
					) and modify_time=(
		  					select max(modify_time) from currency_updates where currency_id = (
		   select  currency_id  from  currency  where code=(select  sys_currency  from    system_parameter)
		  	)
		 	 )
	 	");
        $sys_currency_rate = !empty($list[0][0]['rate']) ? $list[0][0]['rate'] : '';
        $_SESSION['system_currency'] = compact('sys_currency', 'sys_currency_rate');
        
//        //------------------localdata.js自动完成
//        $localdata = "js/localdata.js";
//        if (file_exists($localdata)) {
//            if (is_writable($localdata)) {
//                /*
//                  $code_deck = "";
//                  $default_code_deck = $this->User->query("SELECT default_code_deck FROM system_parameter LIMIT 1");
//                  if($default_code_deck != '') {
//                  $code_deck = "WHERE code_deck_id = {$default_code_deck[0][0]['default_code_deck']}";
//                  }
//                 */
//                //$code_info = $this->User->query("select * from code {$code_deck}");
//                $code_info = $this->User->query("select * from code");
//                $client_info = $this->User->query("select client.client_id,name from  client  where 1=1 order by name");
//                $rate_info = $this->User->query("select rate_table_id,name as table_name,code_name,currency_code from rate_table left join (select code_deck_id,name as code_name from code_deck )deck on deck.code_deck_id=rate_table.code_deck_id left join (select code as currency_code,currency_id from currency) curr on curr.currency_id=rate_table.currency_id where 1=1 limit '10' offset '0' ");
//                $rate_info_term = $this->User->query("select rate_table_id,name as table_name,code_name,currency_code from rate_table left join (select code_deck_id,name as code_name from code_deck )deck on deck.code_deck_id=rate_table.code_deck_id left join (select code as currency_code,currency_id from currency) curr on curr.currency_id=rate_table.currency_id where 1=1 limit '10' offset '0' ");
//                if (!empty($code_info) || !empty($client_info)) {
//                    foreach ($code_info as $k => $v) {
//                        $country_arr[$v[0]['country']] = '"' . $v[0]['country'] . '"';
//                        $code_name_arr[$v[0]['name']] = '"' . $v[0]['name'] . '"';
//                        $code_arr[$v[0]['code']] = '"' . $v[0]['code'] . '"';
//                    }
//                    foreach ($client_info as $m => $n) {
//                        $client_arr[$n[0]['name']] = '"' . $n[0]['name'] . '"';
//                    }
//                    foreach ($rate_info as $i => $j) {
//                        $rate_arr[$j[0]['table_name']] = '"' . $j[0]['table_name'] . '"';
//                    }
//                    foreach ($rate_info_term as $a => $b) {
//                        $rate_arr_term[$b[0]['table_name']] = '"' . $b[0]['table_name'] . '"';
//                    }
//
//                    $jsdata = "var countries = [" . implode(",", $country_arr) . "];\n";
//                    $jsdata .= "var cities = [" . implode(",", $code_name_arr) . "];\n";
//                    $jsdata .= "var codes = [" . implode(",", $code_arr) . "];\n";
//                    $jsdata .= "var names = [" . implode(",", $client_arr) . "];\n";
//                    $jsdata .= "var rates = [" . implode(",", $rate_arr) . "];\n";
//
//                    $jsdata .= "var rates_term = [" . implode(",", $rate_arr_term) . "];\n";
//
//                    $handle = fopen($localdata, "w");
//                    fwrite($handle, $jsdata);
//
//                    if (flock($handle, LOCK_EX)) {
//                        fwrite($handle, $jsdata);
//                        flock($handle, LOCK_UN);
//                    }
//
//                    fclose($handle);
//                }
//            }
//        }
        
    }

    public function add_web_session($msg = '') {
        App::import('Model', 'Websession');
        $web_model = new Websession();
        $this->data ['Websession'] ['user_id'] = @$_SESSION['sst_user_id'] or 0;
        $this->data ['Websession'] ['host'] = $_SERVER['REMOTE_ADDR'];
        $this->data ['Websession'] ['agent'] = $_SERVER['HTTP_USER_AGENT'];
        $this->data ['Websession'] ['msg'] = $msg;
        $web_model->save($this->data ['Websession']);
    }

    public function validate_code() {
        header("content-type:image/png");     //设置创建图像的格式
        $image_width = 100;                      //设置图像宽度
        $image_height = 25;                     //设置图像高度
        srand(microtime() * 100000);            //设置随机数的种子
        $new_number = '';
        for ($i = 0; $i < 4; $i++) {                  //循环输出一个4位的随机数
            $new_number = $new_number . dechex(rand(0, 15));
        }
        $_SESSION['validate_code'] = $new_number;    //将获取的随机数验证码写入到SESSION变量中    
        $num_image = imagecreate($image_width, $image_height);  //创建一个画布
        imagesavealpha($num_image, true);
        imagealphablending($num_image, false);
        $white = imagecolorallocatealpha($num_image, 255, 255, 255, 127);          //设置画布的颜色
        imagefill($num_image, 0, 0, $white);
        for ($i = 0; $i < strlen($_SESSION['validate_code']); $i++) { //循环读取SESSION变量中的验证码
            $font = mt_rand(16, 28);                              //设置随机的字体
            $x = mt_rand(1, 8) + $image_width * $i / 4;               //设置随机字符所在位置的X坐标
            $y = mt_rand(1, $image_height / 4);                   //设置随机字符所在位置的Y坐标
            $color = imagecolorallocate($num_image, mt_rand(0, 100), mt_rand(0, 150), mt_rand(0, 200));  //设置字符的颜色
            imagestring($num_image, $font, $x, $y, $_SESSION['validate_code'][$i], $color);              //水平输出字符
        }
        ob_clean();
        imagepng($num_image);               //生成PNG格式的图像
        imagedestroy($num_image);           //释放图像资源
    }

    public function login() {
        $this->layout = '';
        $welcome_message_result = $this->Onlineuser->query("SELECT welcome_message FROM system_parameter LIMIT 1");
        $this->set('welcome_message', $welcome_message_result[0][0]['welcome_message']);
        $release_note =  $this->_get_release();
        $this->set('release_note', $release_note);
    }
    
    public function _get_release()
    {
        $release_file = ROOT . DS . 'release.note';
        $content = file_get_contents($release_file);
        $content_arr = explode("\n", trim($content));
        foreach($content_arr as $item)
        {
            $item_arr = explode("=", $item);
            if('Web' == trim($item_arr[0]))
            {
                return $item_arr[1];
            }
        }
    }

    public function login_test() {
        $this->layout = '';
        $this->set('f', $_REQUEST);
    }

    //退出
    public function logout() {
        //App::import('Vendor', 'logging');
        //Logging::log($this->Session->read('sst_user_id'), "System", "Logout",$this->User);	
        $time = date("Y-m-d H:i:s");
        $user_name = $_SESSION['sst_user_name'];
        $this->Onlineuser->query("update  users  set last_login_time='$time'  where  name='$user_name'");
        $this->Onlineuser->query("delete from  online_users  where  user_name='$user_name'");
        $_SESSION = array(); //清空所有session变量
        $this->redirect(array('controller' => 'homes', 'action' => 'login'));
    }

    function auth_user() {
        $sql = "select inactivity_timeout from system_parameter limit 1";
        $result = $this->User->query($sql);
        /* set the cache limiter to 'private' */

        session_cache_limiter('private');
        $cache_limiter = session_cache_limiter();

        /* set the cache expire to 30 minutes */
        session_cache_expire((int)($result[0][0]['inactivity_timeout']));
       // $cache_expire = session_cache_expire();

        /* start the session */

        $userName = '';
        $password = '';
        $f = array();
        if (!empty($this->params['form'])) {
            $f = $this->params['form'];
            $captcha = $f['captcha']; //验证码
            if (empty($captcha)) {
                $this->Session->write('login_failed', 'please  input  aptcha');
                $this->Session->write('backform', $f);
                $this->redirect('/homes/login');
                exit();
            }
            $c_code = isset($_SESSION['validate_code']) ? $_SESSION['validate_code'] : '';
            
            if (empty($c_code))
            {
                echo __('You are unable to login now due to system error. Please contact your system administrator.', true);exit;
            }
            
            if ($captcha != $c_code) {
                $this->Session->write('login_failed', __('entercaptchaerror', true));
                $this->Session->write('backform', $f);
                $this->redirect('/homes/login');
                exit();
            }
        } else {
            $f = $this->params['url'];
            if (isset($f['client_id']) && preg_match('/^[0-9]+$/', $f['client_id'])) {
                $client_id = $f['client_id'];
                $list = $this->User->query("select  login,password  from  client  where   client_id={$client_id}");
                if (!empty($list)) {
                    $f['username'] = $list[0][0]['login'];
                    $f['password'] = $list[0][0]['password'];
                    $f['lang'] = $f['lang'];
                }
            }
        }
        $userName = $f['username'];
        $password = $f['password'];
        if (empty($userName) || empty($password)) {
            $this->Session->write('login_failed', __('Plase input user name and password', true));
            $this->Session->write('backform', $f);
            $this->redirect('/homes/login');
            exit();
        }
        if (!preg_match('/^[a-zA-Z0-9_]+$/', $userName)) {
            $this->Session->write('login_failed', __('username_format', true));
            $this->Session->write('backform', $f);
            $this->redirect('/homes/login');
            exit();
        }
//			if (!preg_match('/^[a-zA-Z0-9]+$/',$password)) {
//				$this->Session->write('login_failed',__('userpass_format',true));
//				$this->Session->write('backform',$f);
//				$this->redirect('/homes/login');
//				exit();
//			}
        $_SESSION['role_menu'] = array(
            'Management' => array(
                'clients' => array('model_r' => true, 'model_w' => false, 'model_x' => false),
                'pr_invoices' => array('model_r' => true, 'model_w' => false, 'model_x' => false),
                'clientmutualsettlements' => array('model_r' => true, 'model_w' => false, 'model_x' => false)
            ),
            'Statistics' => array('cdrreports' => array('model_r' => true, 'model_x' => true, 'model_w' => false),
                'monitorsreports' => array('model_r' => true, 'model_x' => true, 'model_w' => false)),
            'Switch' => array('clientrates' => array('model_r' => true, 'model_x' => false, 'model_w' => false)),
            'Configuration' => array('users:changepassword' => array('model_r' => true, 'model_w' => true, 'model_x' => false))
        );
        $this->user_login($f);
    }

    function user_login($f) {
        $userName = $f['username'];
        $password = $f['password'];
        $auth_result = $this->User->auth_user($userName, $password);

        if ($auth_result == true) {
            
            $_SESSION['login_success'] = 'succ';
            $user_type = $auth_result[0][0]['user_type']; //用户身份


            $this->Session->write('Config.language', $f['lang']);
            $this->Session->write('sst_user_id', $auth_result[0][0]['user_id']);
            $this->Session->write('sst_password', $password); //当前用户的密码
            $this->Session->write('sst_user_name', $userName);
            $this->Session->write('sst_role_id', $auth_result[0][0]['role_id']);
            $this->Session->write('login_type', $user_type); //登录身份

            $role_info = $this->User->query("select * from sys_role where role_id = " . intval($auth_result[0][0]['role_id']));
            $this->Session->write('sst_role_name', empty($role_info[0][0]['role_name']) ? '' : $role_info[0][0]['role_name']);


            //$this->add_web_session();
            //App::import('Vendor', 'logging');
            //Logging::log($this->Session->read('sst_user_id'), "System", "Log in",$this->User);	
            $time = date("Y-m-d H:i:s");
            $ip = $this->RequestHandler->getClientIP();
            if (empty($ip)) {
                $ip = 'null';
            }
            //验证ip 

            if (!$this->auth_ip($auth_result[0][0]['user_id'], $ip)) {
                $this->Session->write('login_failed', 'IP has been banned  ');
                $this->Session->write('backform', $f);
                $this->add_web_session("IP is banned");
                $this->redirect('/homes/login');
            }

            
            $this->User->query("update   users set last_login_time='$time',login_ip='$ip'   where  name='$userName'");
            $this->User->query("delete from  online_users  where  user_name='$userName'");
            $this->init_system();
            $this->init_sys_timezone();
            if ($user_type == 1) {
                $this->add_web_session();
                $this->init_admininfo($auth_result, $userName);
            }
            if ($user_type == 3) {
                $this->init_clientinfo($auth_result, $userName);
            }
            if ($user_type == 5) {
                $this->add_web_session();
                $this->init_commoninfo($auth_result, $userName);
            }
        } else {
            Configure::load('myconf');
            $is_enable_host_dialer = Configure::read('host_dialer.enabled');
            if ($is_enable_host_dialer) {
                $reseller = $this->Reseller->find('first', array(
                    'conditions' => array(
                        'login_id' => $userName,
                        'password' => $password,
                    )
                ));
                if (!empty($reseller)) {
                    $this->Session->write('Config.language', $f['lang']);
                    $this->init_reseller($userName, $reseller['Reseller']['id']);
                }
            }
            
            $this->add_web_session("User Name or Password is incorrect! UserName: {$userName}, Password: {$password}");
            $this->Session->write('login_failed', __('nameorpass_incorrect', true));
            $this->Session->write('backform', $f);
            $this->redirect('/homes/login');
            return;
        }
    }
    
    function init_reseller($username, $id) {
        $limits = array(
            'Dialer Management' => array(
              'resellers' => array(
                   'pri_name' => 'resellers:client',
                   'pri_val' => 'Client',
                   'pri_url' => 'resellers/client',
                   'module_name' => 'Dialer Management',
                   'model_r' => 't',
                   'model_w' => 't',
                   'model_x' => 't',
              )
          )  
        );
        $this->Session->write('role_menu', $limits);
        $this->Session->write('login_type', 10);
        $this->Session->write('sst_user_name', $username);
        $this->Session->write('reseller_id', $id);
        
        
        $this->redirect('/resellers/client');
    }

    function init_commoninfo($auth_result, $userName) {
        $reseler_id = $auth_result[0][0]['reseller_id'];
        $user_id = $auth_result[0][0]['user_id'];
        $role_id = $auth_result[0][0]['role_id'];
        $this->Session->write('sst_role', $this->User->findRoleInfo_user_id($user_id)); //初始化用户角色信息
        $role = $this->Session->read('sst_role');
        $this->User->findPrivilegeInfo($role_id); //初始化用户的权限
        $url = $role['func_url'];


        $this->data ['Onlineuser'] ['user_id'] = $user_id;
        $this->data ['Onlineuser'] ['reseller_id'] = $reseler_id;
        $this->data ['Onlineuser'] ['user_type'] = 5;
        $this->data ['Onlineuser'] ['user_name'] = $userName;
        $online_id = $this->Onlineuser->add_online_user($this->data);
        $this->Session->write('sst_online_id', $online_id); //管理员对应的顶极代理商			
        $this->redirect('/' . $url);
        //pr($this->Session->read("sst_manager_reseller"));
        //pr($this->Session->read("sst_retail"));
    }

//初始化管理员信息
    function init_admininfo($auth_result, $userName) {
        $user_id = $auth_result[0][0]['user_id'];
        $role_id = $auth_result[0][0]['role_id'];
        $this->init_role_menu($auth_result[0][0]['role_id']);
        $this->data ['Onlineuser'] ['user_id'] = $user_id;
        $this->data ['Onlineuser'] ['user_type'] = 1;
        $this->data ['Onlineuser'] ['user_name'] = $userName;
        $online_id = $this->Onlineuser->add_online_user($this->data);

        $this->Session->write('sst_online_id', $online_id); //管理员对应的顶极代理商		
        $this->init_login_url($user_id);
    }

    function _switch_database() {
        if (isset($_SESSION['carrier_panel']['database_name'])) {
            $database_name = $_SESSION['carrier_panel']['database_name'];
            $list = $this->Client->query(" select   datname  from  pg_database where datname='$database_name' ;");
            if (empty($list[0][0]['datname'])) {
                return false;
            } else {
                return true;
            }
        }
        return false;
    }

    #初始化partition 项目

    function _init_partition_project() {
        //	return $this->_switch_database();
        return true;
    }

    //初始化客户id,角色
    function init_clientinfo($auth_result, $userName) {
        $user_id = $auth_result[0][0]['user_id'];
        $client_id = $auth_result[0][0]['client_id'];
        $this->Session->write('carrier_panel', $this->Client->find('first', array('conditions' => array('Client.client_id' => $client_id))));
        $role_id = $auth_result[0][0]['role_id'];
        $this->Session->write('sst_client_id', $client_id);
        //记录登录信息
        $this->data ['Onlineuser'] ['user_id'] = $user_id;

        $this->data ['Onlineuser'] ['user_type'] = 3;
        $this->data ['Onlineuser'] ['user_name'] = $userName;
        $online_id = $this->Onlineuser->add_online_user($this->data);
        $this->Session->write('sst_online_id', $online_id);
        $this->init_client_role($client_id);
        

        $project_name = Configure::read('project_name');
        //	var_dump($_SESSION);exit;
        //$sess_info = var_export($_SESSION, true);
        if ($project_name == 'exchange') {
            //$this->redirect('http://'.$_SERVER['HTTP_HOST'].$this->webroot."homes/login?sessinfo=".urlencode($sess_info));
            $this->redirect("/clientcdrreports/credit_balance/");
        } else {

            $post = $_SESSION['carrier_panel']['Client'];
            
            
            if (empty($post['is_client_info']) && empty($post['is_mutualsettlements']) && empty($post['is_invoices']) && empty($post['is_changepassword']) &&
                    empty($post['is_rateslist']) && empty($post['is_cdrslist'])) {
                $this->add_web_session("Do not have any privileges");
                $this->redirect('/homes/login');
            }
            
            $this->add_web_session();
            //$this->redirect('http://'.$_SERVER['HTTP_HOST'].$this->webroot."homes/login?sessinfo=".urlencode($sess_info));
            if ($post['is_client_info'])
                $this->redirect("/clients/view/");
            if ($post['is_invoices'])
                $this->redirect("/pr/pr_invoices/view");
            if ($post['is_cdrslist'])
                $this->redirect("/cdrreports/summary_reports");
            if ($post['is_rateslist'])
                $this->redirect("/clientrates/view_rate");
            if ($post['is_changepassword'])
                $this->redirect("/users/changepassword");
        }
    }

    function init_client_role($client_id) {
        $list = $this->User->query("select   is_client_info, is_rateslist,is_invoices,is_qos,is_summaryreport,is_cdrslist,is_mutualsettlements,is_changepassword
			  from  client where  client_id=$client_id");
        $this->Session->write('sst_client_role', $list);
    }

    function ping_and_traceroute() {
        if ($this->RequestHandler->isPost()) {
            $type = $_POST['type'];
            $ip_address = $_POST['ip_address'];
            if ($type == 0) {
                $shell_type = "ping -c 10";
            } else {
                $shell_type = "traceroute";
            }
            $cmd = "$shell_type $ip_address";
            $result = shell_exec($cmd);
            $result = str_replace("\n", "<br />", $result);
            $this->set('data', $result);
            $this->set('type', $type);
            $this->set('ip_address', $ip_address);
        }
    }
    
    public function auto_delivery_test()
    {
        $this->autoRender = false;
        $this->autoLayout = false;
        $script_path = Configure::read('script.path');
        $script_conf = Configure::read('script.conf');
        $cmd  = "perl $script_path/class4_qos_report_cmd.pl -c $script_conf -a > /dev/null 2&>1 &";
        shell_exec($cmd);
    }
    
    public function auto_delivery() {
        if ($this->RequestHandler->isPost()) {
            $group_by = $_POST['group_by'];
            $timezone = $_POST['timezone'];
            $address  = $_POST['email_address'];
            $auto_delivery_subject = $_POST['auto_delivery_subject'];
            $auto_delivery_content = $_POST['auto_delivery_content'];
            $sql = "update system_parameter set auto_delivery_timezone = '{$timezone}', auto_delivery_address = '{$address}', auto_delivery_group_by = {$group_by};
            update mail_tmplate set auto_delivery_subject = '{$auto_delivery_subject}', auto_delivery_content = '{$auto_delivery_content}'";
            $this->Onlineuser->query($sql);
            $this->Onlineuser->create_json_array('', 201, __('Succeeded',true));
            $this->Session->write("m",Onlineuser::set_validator ());
        }
        $sql = "SELECT auto_delivery_timezone, auto_delivery_address, auto_delivery_subject, auto_delivery_content,auto_delivery_group_by FROM system_parameter left join mail_tmplate on true  LIMIT 1";
        $data = $this->Onlineuser->query($sql);
        $this->set('data', $data);
    }

}
