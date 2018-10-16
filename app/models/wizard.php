<?php

class Wizard extends AppModel {
    
    var $name = 'Wizard';
    var $useTable = FALSE;
    
    public function get_clients() {
        $sql = "select client_id, name FROM client ORDER BY name ASC";
        return $this->query($sql);
    }
    
    public function get_codecs() {
        $sql = "SELECT id,name FROM codecs ORDER BY name ASC";
        return $this->query($sql);
    }    
    
    public function get_egress_trunks() {
        $sql = "SELECT resource_id, alias FROM resource WHERE egress=true ORDER BY resource_id DESC";
        return $this->query($sql);
    }
    
    public function create_carrier($client_name, $credit_limit) {
        $sql = "INSERT INTO client(name, allowed_credit, currency_id) VALUES ('{$client_name}', {$credit_limit}, (SELECT currency_id FROM currency LIMIT 1)) RETURNING client_id";
        $data = $this->query($sql);
        $client_id = $data[0][0]['client_id'];
        $sql = "INSERT INTO client_balance (client_id) VALUES ({$client_id})";
        $this->query($sql);
        return $client_id;
    }
    
    public function create_trunk($trunk_name, $type, $cps_limit, $call_limit, $client_id) {
        $sql = "INSERT INTO resource (alias, {$type}, cps_limit, capacity, client_id) VALUES ('{$trunk_name}', true, {$cps_limit}, 
                {$call_limit}, {$client_id}) RETURNING resource_id";
        $data = $this->query($sql);
        return $data[0][0]['resource_id'];
    }
    
    public function create_ip_port($resource_id, $ips, $ports) {
        if (count($ips)) {
            $sql_arr = array();
            foreach($ips as $key => $ip) {
                array_push($sql_arr, "INSERT INTO resource_ip (resource_id, port, ip) VALUES ({$resource_id}, {$ports[$key]}, '{$ip}')");
            }
            $sql = implode(';', $sql_arr);
            $this->query($sql);
        }
    }
    
    public function create_codes($resource_id, $codecs) {
        if (count($codecs)) {
            $sql_arr = array();
            foreach($codecs as $codec) {
                array_push($sql_arr, "INSERT INTO resource_codecs_ref (resource_id, codec_id) VALUES ({$resource_id}, {$codec})");
            }
            $sql = implode(';', $sql_arr);
            $this->query($sql);
        }
    }
    
    public function create_product($name) {
        $update_at = date("Y-m-d H:i:s");
        $update_by = $_SESSION['sst_user_name'];
        $sql = "insert into product (name,modify_time, update_by,code_type, route_lrn) 
            values('{$name}','{$update_at}', '{$update_by}',0,0) RETURNING product_id";
        $data = $this->query($sql);
        return $data[0][0]['product_id'];
    }
    
    public function create_product_item($product_id, $host_routing) {
        $sql = "insert into product_items (product_id, digits, strategy)
        values ({$product_id}, '', '{$host_routing}') RETURNING item_id;";
        $data = $this->query($sql);
        return $data[0][0]['item_id'];
    }
    
    public function create_product_item_egress($product_item_id, $resources) {
        if (count($resources)) {
            $sql_arr = array();
            foreach($resources as $resource) {
                array_push($sql_arr, "insert into product_items_resource (item_id, resource_id, by_percentage) values
                            ($product_item_id, $resource, NULL)");
            }
            $sql = implode(';', $sql_arr);
            $this->query($sql);
        }
    }
    
    public function create_route_strategy($name) {
        $update_by = $_SESSION['sst_user_name'];
        $sql = "insert into route_strategy(name, update_by) values('$name', '$update_by') returning route_strategy_id";
        $data = $this->query($sql);
        return $data[0][0]['route_strategy_id'];
    }
    
    public function create_route_static($product_id, $route_strategy_id) {
        $update_at = date("Y-m-d H:i:s");
        $update_by = $_SESSION['sst_user_name'];
        $sql = "insert into route (digits,dynamic_route_id,static_route_id,route_type,route_strategy_id,lnp,lrn_block,
            dnis_only,intra_static_route_id,inter_static_route_id,jurisdiction_country_id,update_at,update_by) 
            values ('',NULL,$product_id,2,$route_strategy_id,false,false,false,NULL,NULL,NULL, '$update_at', '$update_by')";
        $this->query($sql);
    }
    
    public function create_resource_prefix($route_strategy_id, $rate_table_id) {
        $sql = "insert into resource_prefix (route_strategy_id, rate_table_id, tech_prefix) 
                VALUES ($route_strategy_id, $rate_table_id, '');";
        $this->query($sql);
    }
    
    public function create_dynamic($name) {
        $update_at = date("Y-m-d H:i:s");
        $update_by = $_SESSION['sst_user_name'];
        $sql = "INSERT INTO dynamic_route(name, routing_rule, time_profile_id, lcr_flag, update_at, update_by) 
            VALUES ('$name', '4', DEFAULT, '1', '$update_at', '$update_by') returning dynamic_route_id;";
        $data = $this->query($sql);
        return $data[0][0]['dynamic_route_id'];
    }
    
    public function create_dynamic_item_egress($dynamic_id, $resources) {
        if (count($resources)) {
            $sql_arr = array();
            foreach($resources as $resource) {
                array_push($sql_arr, "insert into dynamic_route_items (dynamic_route_id, resource_id) 
                            values ($dynamic_id,$resource)");
            }
            $sql = implode(';', $sql_arr);
            $this->query($sql);
        }
    }
    
     public function create_route_dynamic($dynamic_id, $route_strategy_id) {
        $update_at = date("Y-m-d H:i:s");
        $update_by = $_SESSION['sst_user_name'];
        $sql = "insert into route (digits,dynamic_route_id,static_route_id,route_type,route_strategy_id,lnp,lrn_block,
            dnis_only,intra_static_route_id,inter_static_route_id,jurisdiction_country_id,update_at,update_by) 
            values ('',$dynamic_id,NULL,1,$route_strategy_id,false,false,false,NULL,NULL,NULL, '$update_at', '$update_by')";
        $this->query($sql);
    }
    
}

?>
