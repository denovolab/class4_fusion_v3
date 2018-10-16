<?php

class WizardsController extends AppController {
    
    var $name = 'Wizards';
    var $uses = array('Wizard');
    var $components = array('RequestHandler');
    
    public function beforeFilter() {
        $this->checkSession("login_type"); //核查用户身份
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
    
    public function index() {
        if($this->RequestHandler->isPost()) {
            $client_type = $_POST['client_type'];
            $trunk_type = $_POST['trunk_type'];
            $trunk_name = $_POST['trunk_name'];
            $cps_limit = $_POST['cps_limit'] == '' ? 'NULL': $_POST['cps_limit'];
            $call_limit = $_POST['call_limit'] == '' ? 'NULL' : $_POST['call_limit'];
            $codecs = isset($_POST['codecs']) && count($_POST['codecs']) ? $_POST['codecs'] : array();
            $ips = isset($_POST['ips']) && count($_POST['ips']) ? $_POST['ips'] : array();
            $ports = isset($_POST['ports']) && count($_POST['ports']) ? $_POST['ports'] : array();
            
            $this->Wizard->begin();
            if($client_type == '0') {
                $client_name = $_POST['client_name'];
                $credit_limit = empty($_POST['credit_limit']) ? 0 : $_POST['credit_limit'];
                $client_id = $this->Wizard->create_carrier($client_name, $credit_limit);
            } elseif ($client_type == '1') {
                $client_id = $_POST['client'];
            } else {
                print('Error!');
                $this->Wizard->rollback();
            }
            
            if($trunk_type == '0') {
                // ingress
                $rate_table = $_POST['rate_table'];
                $routing_type = $_POST['routing_type'];
                $egress_trunks = isset($_POST['egress_trunks']) ? $_POST['egress_trunks'] : array();
                
                $resource_id = $this->Wizard->create_trunk($trunk_name, 'ingress', $cps_limit, $call_limit, $client_id);
                $this->Wizard->create_ip_port($resource_id, $ips, $ports);
                $this->Wizard->create_codes($resource_id, $codecs);
                
                $routing_strategy_name = $trunk_name .'_RS';
                $routing_strategy_id = $this->Wizard->create_route_strategy($routing_strategy_name);
                $this->Wizard->create_resource_prefix($routing_strategy_id, $rate_table);
                if ($routing_type == '0') {
                    // static
                    $host_routing = $_POST['host_routing'];
                    $static_route_name = $trunk_name . '_SR';
                    $product_id = $this->Wizard->create_product($static_route_name);
                    $product_item_id = $this->Wizard->create_product_item($product_id, $host_routing);
                    $this->Wizard->create_product_item_egress($product_item_id, $egress_trunks);
                    $this->Wizard->create_route_static($product_id, $routing_strategy_id);
                } else {
                    // dynamic
                    $dynamic_route_name = $trunk_name . '_DR';
                    $dynamic_id = $this->Wizard->create_dynamic($dynamic_route_name);
                    $this->Wizard->create_dynamic_item_egress($dynamic_id, $egress_trunks);
                    $this->Wizard->create_route_dynamic($dynamic_id, $routing_strategy_id);
                }
            } elseif ($trunk_type == '1') {
                // egress
                $resource_id = $this->Wizard->create_trunk($trunk_name, 'egress', $cps_limit, $call_limit, $client_id);
                $this->Wizard->create_ip_port($resource_id, $ips, $ports);
                $this->Wizard->create_codes($resource_id, $codecs);
            } else {
                print('Error!');
                $this->Wizard->rollback();
            }
            $this->Wizard->commit();
            $this->Wizard->create_json_array('#ClientOrigRateTableId', 201, __('Success!', true));
        }
        
        $clients = $this->Wizard->get_clients();
        $codecses = $this->Wizard->get_codecs();
        $this->set('clients', $clients);
        $this->set('codecses', $codecses);
    }
    
    public function get_ratetable() {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $sql = "SELECT rate_table_id, name FROM rate_table ORDER BY rate_table_id DESC";
        $data = $this->Wizard->query($sql);
        echo json_encode($data);
    }
    
    public function get_egress() {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $data = $this->Wizard->get_egress_trunks();
        echo json_encode($data);
    }
    
    public function check_exists() {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $trunk_name = $_POST['trunk_name'];
        $client_name = $_POST['client_name'];
        $sql = "SELECT count(*) FROM resource WHERE alias = '{$trunk_name}'";
        $data = $this->Wizard->query($sql);
        if($data[0][0]['count'] > 0) 
        {   
            echo '1';
            return;
        }
        if(!empty($trunk_name)) {
            $sql = "SELECT count(*) FROM client WHERE name = '{$client_name}'";
            $data = $this->Wizard->query($sql);
            if($data[0][0]['count'] > 0) 
            {   
                echo '2';
                return;
            }
        }
        echo '0';
    }
    
    
}


?>
