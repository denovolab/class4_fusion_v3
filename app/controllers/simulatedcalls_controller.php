<?php

class SimulatedcallsController extends AppController
{
 
    var $name = 'Simulatedcalls';
    var $uses = array('Productitem', 'RouteStrategy', 'Resource', 'Client', 'ResourceIp', 'Code');
    //var $components =array('PhpFreeswitchInterface');

    var $components = array('PhpTelnet');
    var $helpers = array('AppResource', 'AppSimulateCall');

    function index()
    {
        $this->redirect('simulated_call');
    }

    //读取该模块的执行和修改权限
    public function beforeFilter()
    {
        $this->checkSession("login_type"); //核查用户身份
        $login_type = $this->Session->read('login_type');
        if ($login_type == 1) {
            //admin
            $this->Session->write('executable', true);
            $this->Session->write('writable', true);
        } else {
            $limit = $this->Session->read('sst_tools_simulatedCallTrace');
            $this->Session->write('executable', $limit['executable']);
            $this->Session->write('writable', $limit['writable']);
        }
        parent::beforeFilter();
    }

    public function simulated_call()
    {
        $this->pageTitle = "Tools/Call Simulation";
        if ($this->RequestHandler->isPost()) {
//			pr($this);
            $this->exec_simulate();
            $resource_id = (int) $this->data['ingress'];
            if ($resource_id > 0) {
                $this->set('selected_hosts', $this->Productitem->query("select ip,port from resource_ip where resource_id = '$resource_id'"));
            } else {
                $this->set('selected_hosts', array());
            }
        } else {
            $this->set('selected_hosts', array());
        }
        $ingress = $this->Productitem->query("select resource_id,alias from resource where ingress = true  order  by  alias asc");
        $this->set('ingress', $ingress);
        $route_strategies = $this->RouteStrategy->find_all_valid();
        $this->set('route_strategies', $route_strategies);
    }

    public function get_ingress_by_resource()
    {
        Configure::write('debug', 0);
        $this->layout = '';
        $this->autoRender = false;
        $resource_id = $_REQUEST['r_id'];
        if (!empty($resource_id)) {
            $host = $this->Productitem->query("select ip,port from resource_ip where resource_id = '$resource_id'");

            $finalData = "[";
            $hasData = false;

            $loop = count($host);
            for ($i = 0; $i < $loop; $i++) {
                $hasData = true;
                $ip = $host[$i][0]['ip'];
                $port = $host[$i][0]['port'];
                $finalData .= "{ip:'$ip',port:'$port'},";
            }

            if ($hasData == true) {
                $finalData = substr($finalData, 0, strlen($finalData) - 1) . "]";
                echo $finalData;
            } else
                echo "[]";
        }
    }

    /*
     * 向后台发送命令  返回模拟结果
     */

    public function exec_simulate()
    {
        $host = '';
        if (isset($this->data['host'])) {
            $host = trim($this->data['host']); //Ingress Host
        }
        $ani = trim($this->data['ani']); //Origination ANI
        $dnis = trim($this->data['dnis']); //Origination DNIS
        $resource_id = (int) $this->data['ingress'];
        $has_error = false;
        if ($resource_id > 0) {
            $resource = $this->Productitem->query("select * from resource where resource_id = $resource_id");
            if (empty($resource)) {
                $this->Productitem->create_json_array("", 101, 'Please select ingress');
                $has_error = true;
            }
        } else {
            $this->Productitem->create_json_array("", 101, 'Please select ingress');
            $has_error = true;
        }

        if (empty($host)) {
            $this->Productitem->create_json_array("#host", 101, 'Please select host');
            $has_error = true;
        }

        if (empty($ani)) {
            $this->Productitem->create_json_array("#ani", 101, __('ani_null', true));
            $has_error = true;
        }

        if (empty($dnis)) {
            $this->Productitem->create_json_array("#dnis", 101, __('dnis_null', true));
            $has_error = true;
        }

        if ($has_error == true) {
            $this->Session->write('m', Productitem::set_validator());
        } else {
            list($host, $ip) = split(':', $host);
            #freeswitch 命令
            //$cmd="simulate_class4 192.168.1.51,5060,1001,9911000,,";
            //$cmd="simulate_class4 192.168.1.51,5060,1001,89911000,,";

 /*           $time = $this->data['time'];
            unset($time);  //TODO 暂时不使用时间参数
            $route_strategy = $this->data['route_strategy'];

            if (empty($time) && !empty($route_strategy)) {
                $cmd = "call_simulation $host,$ip,$ani,$dnis,,$route_strategy";
            }


            if (!empty($time) && empty($route_strategy)) {
                $cmd = "call_simulation $host,$ip,$ani,$dnis,$time,";
            }


            if (!empty($time) && !empty($route_strategy)) {
                $cmd = "call_simulation $host,$ip,$ani,$dnis,$time,$route_strategy";
            }

            if (empty($time) && empty($route_strategy)) {
                $cmd = "call_simulation $host,$ip,$ani,$dnis,,";
            }
   */        
            $cmd =  $cmd = "call_simulation $host,$ip,$ani,$dnis,";
            if (Configure::read('cmd.debug')) {
                echo $cmd;exit;
            }

            $data = $this->socket_fun($cmd);
            $data = iconv('ISO-8859-1',"UTF-8//TRANSLIT",$data);
// echo "<pre>";var_dump($data);die;           
 $this->set('xdata', $data);
        }
    }

    function socket_fun($sendStr)
    {

        $content = '';
        $server_arr = explode(":",$this->data['server']);
        $server_ip = $server_arr[0];
        $server_port = $server_arr[1];
        $server_ip = Configure::read("backend.ip");
        $server_port =  Configure::read("backend.port");
        App::import("Vendor", "connect_backend", array('file' => "connect_backend.php"));
        $backend_connect = new ConnectBackend();
        if($backend_connect->get_connect($server_ip, $server_port) !== false)
        {
            $cmd = $sendStr;
            if($backend_connect->send_cmd($cmd) !== false)
            {
                $content = $backend_connect->get_result();
            }
        }
        $backend_connect->close_connect();
        if (!$content)
        {
            $this->Productitem->create_json_array("", 101, __('Unable to connect to the back end engine at %s and Port %s.', true, array($server_ip,$server_port)));
            $this->Session->write('m', Productitem::set_validator());
            $this->redirect('/simulatedcalls/simulated_call');
        }
        return $content;

    }

    private function send($cmd = null)
    {
        $result = $this->PhpTelnet->getResult("api " . $cmd);
        return $result;
    }
 
}
