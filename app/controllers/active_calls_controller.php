<?php 

class ActiveCallsController extends AppController
{
    var $name = "ActiveCalls";
    var $uses = array('ActiveCall');
    var $components = array('RequestHandler');
    var $helpers = array('javascript', 'html', 'Common');
    
    public function beforeFilter() {
        $this->checkSession("login_type");
        $login_type = $this->Session->read('login_type');
        if ($login_type == 1) {
            $this->Session->write('executable', true);
            $this->Session->write('writable', true);
        } else {
            $limit = $this->Session->read('sst_config_CodeDeck');
            $this->Session->write('executable', $limit['executable']);
            $this->Session->write('writable', $limit['writable']);
        }
        parent::beforeFilter();
    }
    
    
    public function reports()
    {
        $this->pageTitle = "Statistics/Active Call Report";
        
        
        
        $active_call_exec = Configure::read('active_call.exec');
        
        $local_ip = Configure::read('active_call.test_local_ip');
        $local_port = Configure::read('active_call.test_local_port');
        $call_server_ip = Configure::read('active_call.active_call_server_ip');
        $call_server_port = Configure::read('active_call.active_call_server_port');
        $billing_servers = Configure::read('active_call.billing_server');
        
        $fields = $this->ActiveCall->fields();
        
        
        $fields_keys =  array_keys($fields);
        
        $show_fields = $this->ActiveCall->default_show_fields();
        
        //$default_show_fields = array_fill_keys($fields, 1);
        
        // TODO 开始替换手动查询字段
        
        if (isset($_GET['query']))
        {
            // Search
            $orig_carrier = trim($_GET['orig_carrier']);
            $term_carrier = trim($_GET['term_carrier']);
            $ingress      = trim($_GET['ingress']);
            $egress       = trim($_GET['egress']);
            $orig_ip      = trim($_GET['orig_ip']);
            $term_ip      = trim($_GET['term_ip']);
            $dnis         = trim($_GET['dnis']);
            $ani          = trim($_GET['ani']);
            $search_show_fields = $_GET['show_fields'];
            
            $show_fields = array_fill_keys($fields_keys, 0);
            
            foreach($search_show_fields as $search_show_field)
                $show_fields[$search_show_field] = 1;
            
            $conditions = array();
            
            if (!empty($orig_carrier))
                array_push ($conditions, "-o {$orig_carrier}");
            if (!empty($term_carrier))
                array_push ($conditions, "-a {$term_carrier}");
            if (!empty($ingress))
                array_push ($conditions, "-u {$ingress}");
            if (!empty($egress))
                array_push ($conditions, "-i {$egress}");
            if (!empty($orig_ip))
                array_push ($conditions, "-t {$orig_ip}");
            if (!empty($term_ip))
                array_push ($conditions, "-y {$term_ip}");
            if (!empty($dnis))
                array_push ($conditions, "-r {$dnis}");
            if (!empty($ani))
                array_push ($conditions, "-e {$ani}");
                
            $condition = implode(' ', $conditions);
        } else {
            //$show_fields = array_fill_keys($fields_keys, 1);
            $condition = '';
            
        }
        
        $billing_server = explode(':', $billing_servers[0]);
        
        $show_fields_index = array_values($show_fields);
        
        
        $data = array();
        
        $cmd = "{$active_call_exec} -H {$local_ip} -P {$local_port} -h {$call_server_ip} -p {$call_server_port} -q {$billing_server[0]} -w {$billing_server[1]} -m 1 {$condition}";
        if (Configure::read('cmd.debug')) {
            echo $cmd;
        }
        
        $handle = popen($cmd, 'r');
        
        while ($row = fgetcsv($handle, 1000))
        {
            array_push($data, $row);
        }
        
        pclose($handle);
        
        array_pop($data);
        
        $ignore_fields = $this->ActiveCall->ignores();
        
        $this->walk($data, $fields_keys);    
        
      
        
        
        $this->set('data', $data);
        $this->set('fields', $fields);
        $this->set('show_fields', $show_fields_index);
        $this->set('fields_keys', $fields_keys);
        $this->set('show_fields_assoc', $show_fields);
        $this->set('ignore_fields', $ignore_fields);
    }
    
    private function walk(&$data, $fields)
    {
        
        /* 候选值 */
        $protocol       = array('1' => 'SIP', '2' => 'H323');
        $trunk_type     = array('1' => 'Class4', '2' => 'Exchange');
        $buyer_test     = array('0' => 'False', '1' => 'True');
        $trunk_type2    = array('0' => 'DNIS billing', '1' => 'ANI billing');
        $billing_method = array('0' => 'by minute', '1' => 'by ports');
        $clients = $this->ActiveCall->get_clients();
        $ingress_resources = $this->ActiveCall->get_resources('ingress');
        $egress_resources = $this->ActiveCall->get_resources('egress');
        $ingress_resource_ips = $this->ActiveCall->get_resource_ips('ingress');
        $egress_resource_ips = $this->ActiveCall->get_resource_ips('egress');
        $rate_tables = $this->ActiveCall->get_rate_tables();
        
        $this->set('clients', $clients);
        $this->set('ingress_resources', $ingress_resources);
        $this->set('egress_resources', $egress_resources);
        $this->set('ingress_resource_ips', $ingress_resource_ips);
        $this->set('egress_resource_ips', $egress_resource_ips);
        
        /* 找到这些要替换值下标 */
        $origination_protocol_index         = array_search('origination_protocol', $fields);
        $origination_trunk_type_index       = array_search('origination_trunk_type', $fields);
        $origination_buyer_test_index       = array_search('origination_buyer_test', $fields);
        $origination_trunk_type2_index      = array_search('origination_trunk_type2', $fields);
        $origination_billing_method_index   = array_search('origination_billing_method', $fields);
        $origination_client_id_index        = array_search('origination_client_id', $fields);
        
        //print_r($origination_client_id_index);exit;
        
        $origination_resource_id_index      = array_search('origination_resource_id', $fields);
        $origination_resource_ip_id_index   = array_search('origination_resource_ip_id', $fields);
        $origination_start_epoch_index      = array_search('origination_start_epoch', $fields);
        $origination_rate_table_id_index    = array_search('origination_rate_table_id', $fields);
        $origination_answer_epoch_index     = array_search('origination_answer_epoch', $fields);

        $termination_protocol_index         = array_search('termination_protocol', $fields);
        $termination_trunk_type_index       = array_search('termination_trunk_type', $fields);
        $termination_trunk_type2_index      = array_search('termination_trunk_type2', $fields);
        $termination_billing_method_index   = array_search('termination_billing_method', $fields);
        $termination_client_id_index        = array_search('termination_client_id', $fields);
        $termination_resource_id_index      = array_search('termination_resource_id', $fields);
        $termination_resource_ip_id_index   = array_search('termination_resource_ip_id', $fields);
        $termination_start_epoch_index      = array_search('termination_start_epoch', $fields);
        $termination_rate_table_id_index    = array_search('termination_rate_table_id', $fields);
        $termination_answer_epoch_index     = array_search('termination_answer_epoch', $fields);
        
        
        $termination_uuid_a_index   = array_search('termination_uuid_a', $fields);
        $this->set('termination_uuid_a', $termination_uuid_a_index);
        $current_timestamp = time();
        
        foreach ($data as &$row)
        {
            
            array_unshift($row, ($current_timestamp - intval(substr($row[$origination_answer_epoch_index - 1], 0, 10))));
            
            /* 替换值 */
            $row[$origination_protocol_index]            = $protocol[$row[$origination_protocol_index]];
            $row[$origination_trunk_type_index]          = $trunk_type[$row[$origination_trunk_type_index]];
            $row[$origination_buyer_test_index]          = $buyer_test[$row[$origination_buyer_test_index]];
            $row[$origination_trunk_type2_index]         = $trunk_type2[$row[$origination_trunk_type2_index]];
            $row[$origination_billing_method_index]      = $billing_method[$row[$origination_billing_method_index]];
            $row[$origination_client_id_index]           = $clients[$row[$origination_client_id_index]];
            $row[$origination_resource_id_index]         = $ingress_resources[$row[$origination_resource_id_index]];
            $row[$origination_resource_ip_id_index]      = $ingress_resource_ips[$row[$origination_resource_ip_id_index]];
            $row[$origination_start_epoch_index]         = @(date('Y-m-d H:i:s',intval(substr($row[$origination_start_epoch_index], 0, 10)))) or 0;
            $row[$origination_rate_table_id_index]       = $rate_tables[$row[$origination_rate_table_id_index]];
            $row[$origination_answer_epoch_index]        = @(date('Y-m-d H:i:s',intval(substr($row[$origination_answer_epoch_index], 0, 10)))) or 0;
            
            $row[$termination_protocol_index]            = $protocol[$row[$termination_protocol_index]];
            $row[$termination_trunk_type_index]          = $trunk_type[$row[$termination_trunk_type_index]];
            $row[$termination_trunk_type2_index]         = $trunk_type2[$row[$termination_trunk_type2_index]];
            $row[$termination_billing_method_index]      = $billing_method[$row[$termination_billing_method_index]];
            $row[$termination_client_id_index]           = $clients[$row[$termination_client_id_index]];
            $row[$termination_resource_id_index]         = $egress_resources[$row[$termination_resource_id_index]];
            $row[$termination_resource_ip_id_index]      = $egress_resource_ips[$row[$termination_resource_ip_id_index]];
            $row[$termination_start_epoch_index]         = @(date('Y-m-d H:i:s',intval(substr($row[$termination_start_epoch_index], 0, 10)))) or 0;
            $row[$termination_rate_table_id_index]       = $rate_tables[$row[$termination_rate_table_id_index]];
            $row[$termination_answer_epoch_index]        = @(date('Y-m-d H:i:s',intval(substr($row[$termination_answer_epoch_index], 0, 10)))) or 0;
            
            
            
        }
        
        return $data;
        
    }
    
    /*
     * 通过Socket通信将通话记录kill掉
     * @param string $killid
     * @return back
     */

    function kill($killid)
    {
        set_time_limit(0);
        Configure::write('debug', 0);
        $this->autoLayout = FALSE;
        $this->autoRender = FALSE;
        $socket = socket_create(AF_INET, SOCK_STREAM, getprotobyname("tcp"));
        $sendStr = "kill_channel {$killid}";
        if (socket_connect($socket, Configure::read("backend.ip"), Configure::read("backend.port")))
        {
            socket_write($socket, $sendStr, strlen($sendStr));
            socket_read($socket, 1024, PHP_NORMAL_READ);
        }
        socket_close($socket);
        $this->xredirect("/active_calls/reports");
    }
    
    
    function get_resources() 
    {
        Configure::write('debug', 0);
        $this->autoLayout = FALSE;
        $this->autoRender = FALSE;
        $client_id = $_POST['client_id'];
        $type      = $_POST['type'];
        $resources = $this->ActiveCall->get_resources($type, $client_id);
        echo json_encode($resources);
    }
    
    function get_resource_ips() 
    {
        Configure::write('debug', 0);
        $this->autoLayout = FALSE;
        $this->autoRender = FALSE;
        $resource_id = $_POST['resource_id'];
        $type      = $_POST['type'];
        $ips = $this->ActiveCall->get_resource_ips($type, $resource_id);
        echo json_encode($ips);
    }
    
}
