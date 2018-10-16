<?php

class Cdrs extends AppModel{
    var $name = 'Cdrs';
    var $useTable = 'client_cdr';
    var $primaryKey = 'id';
    
    public function get_ingress_clients() {
        $sst_user_id = $_SESSION['sst_user_id'];
        $sql = "SELECT DISTINCT client.client_id, client.name FROM resource 
            INNER JOIN client ON resource.client_id = client.client_id WHERE 
            (exists(SELECT id FROM users_limit WHERE user_id = {$sst_user_id} AND client_id=client.client_id) 
OR 
exists 
(SELECT users.user_id FROM users LEFT JOIN sys_role ON users.role_id = sys_role.role_id WHERE users.user_id = {$sst_user_id} and (role_name = 'admin'
or sys_role.view_all = true)))
                AND
            ingress = true ORDER BY client.name ASC";
        $result = $this->query($sql);
        return $result;
    }
    
     public function get_qos_cdrs_two($sql1, $sql2, $type, $orders, $show_fields) {
        
        $fields = implode(', ', $show_fields);
        $group_by = count($show_fields) ? "group by {$fields}" : '';
        $the_fields = '';
        if(!empty($fields)) $the_fields = $fields . ',';
        if($type == 1) {
            $total_fields = "sum(duration) as duration, sum(bill_time) as bill_time,  
sum(total_calls) as total_calls, sum(not_zero_calls) as not_zero_calls, 
sum(success_calls) as success_calls, sum(busy_calls) as busy_calls, sum(lrn_calls) as lrn_calls, 
sum(pdd) as pdd, sum(cancel_calls) as cancel_calls";
        } else {
            $total_fields = "sum(duration) as duration, sum(bill_time) as bill_time,
sum(total_calls) as total_calls, sum(not_zero_calls) as not_zero_calls, sum(success_calls) as success_calls,
sum(busy_calls) as busy_calls, sum(pdd) as pdd, sum(cancel_calls) as cancel_calls";
        }
        $sql = <<<EOT
SELECT 
$the_fields
$total_fields
FROM 
(        
(
$sql1
)

union
(
$sql2
)
)  
as t 
$group_by $orders   
EOT;
        return $this->query($sql);
    }
    
    public function get_rate_tables()
    {
        $sql = "select rate_table_id as id, name from rate_table order by name asc";
        $result = $this->query($sql);
        return $result;
    }
    
    public function get_routing_plans()
    {
        $sql = "select route_strategy_id as id, name from route_strategy order by name asc";
        $result = $this->query($sql);
        return $result;
    }
    
    public function get_egress_clients() {
        $sst_user_id = $_SESSION['sst_user_id'];
        $sql = "SELECT DISTINCT client.client_id, client.name FROM resource 
            INNER JOIN client ON resource.client_id = client.client_id WHERE
(exists(SELECT id FROM users_limit WHERE user_id = {$sst_user_id} AND client_id=client.client_id) 
OR 
exists 
(SELECT users.user_id FROM users LEFT JOIN sys_role ON users.role_id = sys_role.role_id WHERE users.user_id = {$sst_user_id} and (role_name = 'admin'
or sys_role.view_all = true)))
                AND
            egress = true ORDER BY client.name ASC";
        $result = $this->query($sql);
        return $result;
    }
    
    public function get_switch_ip() {
        $sql = "select ip from server_platform";
        $result = $this->query($sql);
        return $result;
    }
    
    public function get_ingress_trunks() {
        $sql = "select resource_id,alias from resource where ingress=true order by alias asc";
        $result = $this->query($sql);
        return $result;
    }
    
    public function get_egress_trunks() {
        $sql = "select resource_id,alias from resource where egress=true order by alias asc";
        $result = $this->query($sql);
        return $result;
    }
    
    public function get_qos_cdrs($start_date, $end_date,  $fields, $groups, $orders, $wheres, $type, $table_name) {
        $sst_user_id = $_SESSION['sst_user_id'];
        if (!empty($groups)) {
            $filter_client = "and
(exists(SELECT id FROM users_limit WHERE user_id = {$sst_user_id} AND client_id={$table_name}.ingress_client_id) OR exists 
(SELECT users.user_id FROM users LEFT JOIN sys_role ON users.role_id = sys_role.role_id WHERE users.user_id = {$sst_user_id} 
and (role_name = 'admin' or sys_role.view_all = true)))";
        } else {
            $filter_client = '';
        }
        
        if($type == 1) {
        $sql = "SELECT
{$fields}
sum(duration) as duration,
sum(ingress_bill_time) as bill_time,
sum(ingress_total_calls) as total_calls,
sum(not_zero_calls) as not_zero_calls,
sum(ingress_success_calls) as success_calls,
sum(ingress_busy_calls) as busy_calls,
sum(lrn_calls) as lrn_calls,
sum(pdd) as pdd,
sum(ingress_cancel_calls) as cancel_calls
from {$table_name}
where report_time between '{$start_date}' and '{$end_date}' 
{$filter_client}
{$wheres}  {$groups} {$orders}";
        } else if($type == 2) {
        $sql = "SELECT
{$fields}
sum(duration) as duration,
sum(egress_bill_time) as bill_time,
sum(egress_total_calls) as total_calls,
sum(not_zero_calls) as not_zero_calls,
sum(egress_success_calls) as success_calls,
sum(egress_busy_calls) as busy_calls,
sum(pdd) as pdd,
sum(egress_cancel_calls) as cancel_calls
from {$table_name} 
where report_time between '{$start_date}' and '{$end_date}' 
{$filter_client}
{$wheres}  {$groups} {$orders}";
        }
        
        //echo $sql;
        //$result = $this->query($sql);
        //return $result;
        return $sql;
    }
    
    
    public function get_cdrs($start_date, $end_date,  $fields, $groups, $orders, $wheres, $type, $table_name, $out_fields, $groups_main) {
        $sst_user_id = $_SESSION['sst_user_id'];

        $period = new DatePeriod(
            new DateTime($start_date),
            new DateInterval('P1D'),
            new DateTime($end_date)
        );

        $unionSql = "";

        foreach ($period as $key => $value) {
            if ($key > 0) {
                $unionSql .= ' UNION ALL ';
            }

            if (!empty($groups)) {
                $filter_client = "and
(exists(SELECT id FROM users_limit WHERE user_id = {$sst_user_id} AND client_id={$table_name}{$value->format('Ymd')}.ingress_client_id) OR exists 
(SELECT users.user_id FROM users LEFT JOIN sys_role ON users.role_id = sys_role.role_id WHERE users.user_id = {$sst_user_id} 
and (role_name = 'admin' or sys_role.view_all = true)))";
            } else {
                $filter_client = '';
            }

            if ($type == 1) {
                $unionSql .= <<<SQL
SELECT 
{$fields}
sum(duration) as duration,
sum(ingress_bill_time) as bill_time,
sum(ingress_call_cost) as call_cost,
sum(lnp_cost) as lnp_cost,
sum(ingress_total_calls) as total_calls,
sum(not_zero_calls) as not_zero_calls,
sum(ingress_success_calls) as success_calls,
sum(ingress_busy_calls) as busy_calls,
sum(lrn_calls) as lrn_calls,
sum(pdd) as pdd,
sum(ingress_cancel_calls) as cancel_calls,
sum(ingress_call_cost_intra) as inter_cost,
sum(ingress_call_cost_inter) as intra_cost
from {$table_name}{$value->format('Ymd')}
where report_time between '{$start_date}' and '{$end_date}' 
{$filter_client}
{$wheres} {$groups}
SQL;
            } else {
                $unionSql .= <<<SQL
SELECT 
{$fields}
sum(duration) as duration,
sum(egress_bill_time) as bill_time,
sum(egress_call_cost) as call_cost,
sum(egress_total_calls) as total_calls,
sum(not_zero_calls) as not_zero_calls,
sum(egress_success_calls) as success_calls,
sum(egress_busy_calls) as busy_calls,
sum(pdd) as pdd,
sum(egress_cancel_calls) as cancel_calls,
sum(egress_call_cost_inter) as inter_cost,
sum(egress_call_cost_intra) as intra_cost
from {$table_name}{$value->format('Ymd')}
where report_time between '{$start_date}' and '{$end_date}' 
{$filter_client}
{$wheres} {$groups}
SQL;
            }
        }
        
        if($type == 1) {
        $sql = "SELECT
{$out_fields}
sum(duration) as duration,
sum(bill_time) as bill_time,
sum(call_cost) as call_cost,
sum(lnp_cost) as lnp_cost,
sum(total_calls) as total_calls,
sum(not_zero_calls) as not_zero_calls,
sum(success_calls) as success_calls,
sum(busy_calls) as busy_calls,
sum(lrn_calls) as lrn_calls,
sum(pdd) as pdd,
sum(cancel_calls) as cancel_calls,
sum(inter_cost) as inter_cost,
sum(intra_cost) as intra_cost
from ({$unionSql}) as t1 {$groups_main} {$orders}";
        } else if($type == 2) {
        $sql = "SELECT
{$out_fields}
sum(duration) as duration,
sum(bill_time) as bill_time,
sum(call_cost) as call_cost,
sum(total_calls) as total_calls,
sum(not_zero_calls) as not_zero_calls,
sum(success_calls) as success_calls,
sum(busy_calls) as busy_calls,
sum(pdd) as pdd,
sum(cancel_calls) as cancel_calls,
sum(inter_cost) as inter_cost,
sum(intra_cost) as intra_cost
from ({$unionSql}) as t1  {$groups_main} {$orders}";
        }
        
        //echo $sql;
        //$result = $this->query($sql);
        //return $result;
        return $sql;
    }
    
    public function get_did_report($start_date, $end_date, $fields, $groups, $orders, $wheres, $type)
    {
        $sst_user_id = $_SESSION['sst_user_id'];
        if (!empty($groups)) {
            $filter_client = "and
(exists(SELECT id FROM users_limit WHERE user_id = {$sst_user_id} AND client_id=did_report.ingress_client_id) OR exists 
(SELECT users.user_id FROM users LEFT JOIN sys_role ON users.role_id = sys_role.role_id WHERE users.user_id = {$sst_user_id} 
and (role_name = 'admin' or sys_role.view_all = true)))";
        } else {
            $filter_client = '';
        }
        
        if($type == 1) {
        $sql = "SELECT
{$fields}
did,
sum(duration) as duration,
sum(ingress_bill_time) as bill_time,
sum(ingress_call_cost) as call_cost,
sum(ingress_total_calls) as total_calls,
sum(not_zero_calls) as not_zero_calls,
sum(ingress_success_calls) as success_calls
from did_report
where report_time between '{$start_date}' and '{$end_date}' 
{$filter_client}
{$wheres}  {$groups} {$orders}";
        } else if($type == 2) {
        $sql = "SELECT
{$fields}
did,
sum(duration) as duration,
sum(egress_bill_time) as bill_time,
sum(egress_call_cost) as call_cost,
sum(egress_total_calls) as total_calls,
sum(not_zero_calls) as not_zero_calls,
sum(egress_success_calls) as success_calls
from did_report
where report_time between '{$start_date}' and '{$end_date}' 
{$filter_client}
{$wheres}  {$groups} {$orders}";
        }
        return $this->query($sql);
    }
    
    public function get_bandwidth($start_date, $end_date,  $fields, $groups, $orders, $wheres, $table_name) {
        $sst_user_id = $_SESSION['sst_user_id'];
        $sql = "SELECT
{$fields}
sum(incoming_bandwidth) as incoming_bandwidth,
sum(outgoing_bandwidth) as outgoing_bandwidth,
sum(not_zero_calls) as calls
from {$table_name}
where report_time between '{$start_date}' and '{$end_date}' 

and

(exists(SELECT id FROM users_limit WHERE user_id = {$sst_user_id} AND client_id={$table_name}.ingress_client_id) OR exists 

(SELECT users.user_id FROM users LEFT JOIN sys_role ON users.role_id = sys_role.role_id WHERE users.user_id = {$sst_user_id} 

and (role_name = 'admin' or sys_role.view_all = true))) 

{$wheres}  {$groups} {$orders}";
        
        //echo $sql;
        //$result = $this->query($sql);
        //return $result;
        return $sql;
    }
    
    public function get_location_from_two($sql1, $sql2,  $orders, $show_fields) {
        $fields = implode(', ', $show_fields);
        $the_fields = '';
        $group_by = count($show_fields) ? "group by {$fields}" : '';
        if(!empty($fields)) $the_fields = $fields . ',';
        $total_fields = "sum(inbound_call_cost) as inbound_call_cost,
sum(outbound_call_cost) as outbound_call_cost, sum(duration) as duration, sum(total_calls) as total_calls";
        
        $sql = <<<EOT
SELECT 
$the_fields
$total_fields
FROM 
(        
(
$sql1
)

union
(
$sql2
)
)  
as t 
$group_by $orders   
EOT;
        return $this->query($sql);
    }
    
    public function get_location($start_date, $end_date, $fields, $groups, $orders, $wheres, $table_name)
    {
        $sql = "select
{$fields}
sum(ingress_call_cost) as inbound_call_cost,
sum(egress_call_cost) as outbound_call_cost,
sum(duration) as duration,
sum(ingress_total_calls) as total_calls
from {$table_name}
where report_time between '{$start_date}' and '{$end_date}' {$wheres}  {$groups} {$orders}";
        return $sql;
    }
    
    
     public function get_location_from_client_cdr($start_date, $end_date) {
       $field_arr = array();
        $group_arr = array();
        $show_fields = array();
        $order_num = 0;
        $where_arr = array();
        $group_select_arr = array();
        
       
        if(isset($_GET['group_by_date']) && !empty($_GET['group_by_date'])) {
            array_push($field_arr, "to_char(time, '{$_GET['group_by_date']}') as group_time");
            $show_fields['group_time'] = "group_time";
            array_push($group_arr, "to_char(time, '{$_GET['group_by_date']}')");
            $order_num++;
        }
        
        
          if(isset($_GET['route_prefix']) && $_GET['route_prefix'] != 'all'){
            array_push($where_arr, "route_prefix = '{$_GET['route_prefix']}'");  
        }
        
        if (isset($_GET['ingress_rate_table']) && $_GET['ingress_rate_table'] != 'all')
        {            
            array_push($where_arr, "ingress_client_rate_table_id = {$_GET['ingress_rate_table']}");
        }
        
        if (isset($_GET['ingress_routing_plan']) && $_GET['ingress_routing_plan'] != 'all')
        {
            
            array_push($where_arr, "route_plan = {$_GET['ingress_routing_plan']}");
        }
        
        if (isset($_GET['orig_rate_type']) && $_GET['orig_rate_type'] != '0')
        {
            if ($_GET['orig_rate_type'] == '1')
            {
                array_push($where_arr, "orig_jur_type = 0");
            }
            elseif ($_GET['orig_rate_type'] == '2')
            {
                array_push($where_arr, "orig_jur_type in (1, 2)");
            }
            elseif ($_GET['orig_rate_type'] == '3')
            {
                array_push($where_arr, "orig_jur_type in (3, 4)");
            }
        }
        
        if (isset($_GET['term_rate_type']) && $_GET['term_rate_type'] != '0')
        {
            if ($_GET['term_rate_type'] == '1')
            {
                array_push($where_arr, "term_jur_type = 0");
            }
            elseif ($_GET['term_rate_type'] == '2')
            {
                array_push($where_arr, "term_jur_type in (1, 2)");
            }
            elseif ($_GET['term_rate_type'] == '3')
            {
                array_push($where_arr, "term_jur_type in (3, 4)");
            }
        }
        
        if(isset($_GET['ingress_client_id']) && !empty($_GET['ingress_client_id']))
            array_push($where_arr, "ingress_client_id = {$_GET['ingress_client_id']}");
        if(isset($_GET['ingress_id']) && !empty($_GET['ingress_id']))
            array_push($where_arr, "ingress_id = {$_GET['ingress_id']}");    
        if(isset($_GET['orig_country']) && !empty($_GET['orig_country']))
            array_push($where_arr, "orig_country = '{$_GET['orig_country']}'");  
        if(isset($_GET['orig_code_name']) && !empty($_GET['orig_code_name']))
            array_push($where_arr, "orig_code_name = '{$_GET['orig_code_name']}'");  
        if(isset($_GET['orig_code']) && !empty($_GET['orig_code']))
            array_push($where_arr, "orig_code::prefix_range <@  '{$_GET['orig_code']}'");
        if(isset($_GET['egress_client_id']) && !empty($_GET['egress_client_id']))
            array_push($where_arr, "egress_client_id = {$_GET['egress_client_id']}");
        if(isset($_GET['egress_id']) && !empty($_GET['egress_id']))
            array_push($where_arr, "egress_id = {$_GET['egress_id']}");    
        if(isset($_GET['term_country']) && !empty($_GET['term_country']))
            array_push($where_arr, "term_country = '{$_GET['term_country']}'");  
        if(isset($_GET['term_code_name']) && !empty($_GET['term_code_name']))
            array_push($where_arr, "term_code_name = '{$_GET['term_code_name']}'");  
        if(isset($_GET['term_code']) && !empty($_GET['term_code']))
            array_push($where_arr, "term_code::prefix_range <@ '{$_GET['term_code']}'");
            
        if(isset($_GET['group_select']) && !empty($_GET['group_select'])) {
            $group_select_arr = $_GET['group_select'];
            foreach($group_select_arr as $group_select) {
                if(!empty($group_select) && !in_array($group_select, $group_arr)) {
                    array_push($group_arr, $group_select);
                    if($group_select == 'ingress_client_id')
                        array_push($field_arr, "(SELECT name FROM client WHERE client_id = ingress_client_id) AS ingress_client_id"); 
                    elseif ($group_select == 'egress_client_id')
                        array_push($field_arr, "(SELECT name FROM client WHERE client_id = egress_client_id) AS egress_client_id");
                    elseif ($group_select == 'ingress_id')
                        array_push($field_arr, "(SELECT alias FROM resource WHERE resource_id = ingress_id) AS ingress_id"); 
                    elseif ($group_select == 'egress_id')
                        array_push($field_arr, "(SELECT alias FROM resource WHERE resource_id = egress_id) AS egress_id"); 
                    elseif($group_select == 'ingress_country')
                        array_push($field_arr, "orig_country as ingress_country");
                    elseif($group_select == 'ingress_code_name')
                        array_push($field_arr, "orig_code_name as ingress_code_name");
                    elseif($group_select == 'ingress_code')
                        array_push($field_arr, "orig_code as ingress_code");
                    elseif($group_select == 'egress_country')
                        array_push($field_arr, "term_country as egress_country");
                    elseif($group_select == 'egress_code_name')
                        array_push($field_arr, "term_code_name as egress_code_name");
                    elseif($group_select == 'egress_code')
                        array_push($field_arr, "term_code as egress_code");
                    elseif($group_select == 'ingress_rate')
                        array_push($field_arr, 'ingress_client_rate as ingress_rate');
                    else
                        array_push($field_arr, $group_select); 
                    $show_fields[$group_select] = $group_select;
                    $order_num++;
                }
            }
        }
        
        $group_arr = array_unique($group_arr);
        $field_arr = array_unique($field_arr);
        
        $fields = "";
        $groups = "";
        $orders = "";
        $wheres = "";
        if(count($field_arr)) {
            $fields = implode(',', $field_arr) . ",";
        }
        if(count($group_arr)) {
            $groups = "GROUP BY " . implode(',', $group_arr);
        }
        
        if($order_num > 0) {
            $orders = "ORDER BY " . implode(',', range(1, $order_num));
        }
        
        
        if(count($where_arr)) {
            $wheres = " and " . implode(' and ', $where_arr);
        }
        
        $sst_user_id = $_SESSION['sst_user_id'];
        if (!empty($groups)) {
            $filter_client = "and
(exists(SELECT id FROM users_limit WHERE user_id = {$sst_user_id} AND client_id=client_cdr.ingress_client_id) OR exists 
(SELECT users.user_id FROM users LEFT JOIN sys_role ON users.role_id = sys_role.role_id WHERE users.user_id = {$sst_user_id} 
and (role_name = 'admin' or sys_role.view_all = true)))";
        } else {
            $filter_client = '';
        }
        
        $sql = "select
{$fields}
sum(ingress_client_cost) as inbound_call_cost,
sum(egress_cost) as outbound_call_cost,
sum(call_duration) as duration,
count(*) as total_calls
from client_cdr
where   time between '{$start_date}' and '{$end_date}' {$wheres}  and is_final_call = 1 {$filter_client} {$groups} {$orders}";
        return $sql;
    }
    
    public function get_inout_cdrs($start_date, $end_date, $fields, $groups, $orders, $wheres, $table_name) {
        $sst_user_id = $_SESSION['sst_user_id'];
        if (!empty($groups)) {
            $filter_client = "and
(exists(SELECT id FROM users_limit WHERE user_id = {$sst_user_id} AND client_id={$table_name}.ingress_client_id) OR exists 
(SELECT users.user_id FROM users LEFT JOIN sys_role ON users.role_id = sys_role.role_id WHERE users.user_id = {$sst_user_id} 
and (role_name = 'admin' or sys_role.view_all = true)))";
        } else {
            $filter_client = '';
        }
        $sql = "select
{$fields}
sum(ingress_bill_time) as inbound_bill_time,
sum(ingress_call_cost) as inbound_call_cost,
sum(egress_bill_time) as outbound_bill_time,
sum(egress_call_cost) as outbound_call_cost,
sum(duration) as duration,
sum(ingress_total_calls) as total_calls,
sum(not_zero_calls) as not_zero_calls,
sum(ingress_success_calls) as success_calls,
sum(ingress_busy_calls) as busy_calls,
sum(pdd) as pdd
from {$table_name}
where report_time between '{$start_date}' and '{$end_date}' {$wheres} {$filter_client}  {$groups} {$orders}";
        //$result = $this->query($sql);
        //return $result;
        return $sql;
    }
    
    public function get_usagereport($start_date, $end_date, $fields, $groups, $orders, $wheres, $type, $table_name) {
        $sst_user_id = $_SESSION['sst_user_id'];
        if (!empty($groups)) {
            $filter_client = "and
(exists(SELECT id FROM users_limit WHERE user_id = {$sst_user_id} AND client_id={$table_name}.ingress_client_id) OR exists 
(SELECT users.user_id FROM users LEFT JOIN sys_role ON users.role_id = sys_role.role_id WHERE users.user_id = {$sst_user_id} 
and (role_name = 'admin' or sys_role.view_all = true)))";
        } else {
            $filter_client = '';
        }
        if($type == 1) {
            $cdr_count_field = "ingress_total_calls";
        } else {
            $cdr_count_field = "egress_total_calls";
        }
        $sql = <<<EOT
SELECT 
{$fields}    
sum({$cdr_count_field}) as cdr_count,
sum(duration) as duration
from {$table_name} 
where report_time between '{$start_date}' and '{$end_date}' {$wheres} {$filter_client}  {$groups} {$orders}
EOT;
        $result = $this->query($sql);
        return $result;
    }
    
    public function get_ingress_options($ingress_id) {
        $sql = "select id, tech_prefix from resource_prefix where resource_id = {$ingress_id}";
        $prefixes = $this->query($sql);
        $sql = "select distinct (select name from rate_table where rate_table.rate_table_id = resource_prefix.
rate_table_id) as rate_table_name, rate_table_id from resource_prefix where resource_id = {$ingress_id}";
        $rate_tables = $this->query($sql);
        $sql = "select distinct (select name from route_strategy where route_strategy_id 
= resource_prefix.route_strategy_id) as route_strategy_name, route_strategy_id from resource_prefix ";
        $routing_plans = $this->query($sql);
        
        return array(
            'prefixes' => $prefixes,
            'rate_tables' => $rate_tables,
            'routing_plans' => $routing_plans,
        );
    }
    
    public function get_qos_summary_report_from_client_cdr($start_date, $end_date, $type) {
        $field_arr = array();
        $group_arr = array();
        $show_fields = array();
        $order_num = 0;
        $where_arr = array();
        $group_select_arr = array();
        
       
        if(isset($_GET['group_by_date']) && !empty($_GET['group_by_date'])) {
            array_push($field_arr, "to_char(time, '{$_GET['group_by_date']}') as group_time");
            $show_fields['group_time'] = "group_time";
            array_push($group_arr, "to_char(time, '{$_GET['group_by_date']}')");
            $order_num++;
        }
        
        
         if(isset($_GET['route_prefix']) && $_GET['route_prefix'] != 'all'){
            array_push($where_arr, "route_prefix = '{$_GET['route_prefix']}'");  
        }
        
        if (isset($_GET['ingress_rate_table']) && $_GET['ingress_rate_table'] != 'all')
        {            
            array_push($where_arr, "ingress_client_rate_table_id = {$_GET['ingress_rate_table']}");
        }
        
        if (isset($_GET['ingress_routing_plan']) && $_GET['ingress_routing_plan'] != 'all')
        {
            
            array_push($where_arr, "route_plan = {$_GET['ingress_routing_plan']}");
        }
        
        if (isset($_GET['orig_rate_type']) && $_GET['orig_rate_type'] != '0')
        {
            if ($_GET['orig_rate_type'] == '1')
            {
                array_push($where_arr, "orig_jur_type = 0");
            }
            elseif ($_GET['orig_rate_type'] == '2')
            {
                array_push($where_arr, "orig_jur_type in (1, 2)");
            }
            elseif ($_GET['orig_rate_type'] == '3')
            {
                array_push($where_arr, "orig_jur_type in (3, 4)");
            }
        }
        
        if (isset($_GET['term_rate_type']) && $_GET['term_rate_type'] != '0')
        {
            if ($_GET['term_rate_type'] == '1')
            {
                array_push($where_arr, "term_jur_type = 0");
            }
            elseif ($_GET['term_rate_type'] == '2')
            {
                array_push($where_arr, "term_jur_type in (1, 2)");
            }
            elseif ($_GET['term_rate_type'] == '3')
            {
                array_push($where_arr, "term_jur_type in (3, 4)");
            }
        }
        
        if(isset($_GET['ingress_client_id']) && !empty($_GET['ingress_client_id']))
            array_push($where_arr, "ingress_client_id = {$_GET['ingress_client_id']}");
        if(isset($_GET['ingress_id']) && !empty($_GET['ingress_id']))
            array_push($where_arr, "ingress_id = {$_GET['ingress_id']}");    
        if(isset($_GET['orig_country']) && !empty($_GET['orig_country']))
            array_push($where_arr, "orig_country = '{$_GET['orig_country']}'");  
        if(isset($_GET['orig_code_name']) && !empty($_GET['orig_code_name']))
            array_push($where_arr, "orig_code_name = '{$_GET['orig_code_name']}'");  
        if(isset($_GET['orig_code']) && !empty($_GET['orig_code']))
            array_push($where_arr, "orig_code::prefix_range <@  '{$_GET['orig_code']}'");
        if(isset($_GET['egress_client_id']) && !empty($_GET['egress_client_id']))
            array_push($where_arr, "egress_client_id = {$_GET['egress_client_id']}");
        if(isset($_GET['egress_id']) && !empty($_GET['egress_id']))
            array_push($where_arr, "egress_id = {$_GET['egress_id']}");    
        if(isset($_GET['term_country']) && !empty($_GET['term_country']))
            array_push($where_arr, "term_country = '{$_GET['term_country']}'");  
        if(isset($_GET['term_code_name']) && !empty($_GET['term_code_name']))
            array_push($where_arr, "term_code_name = '{$_GET['term_code_name']}'");  
        if(isset($_GET['term_code']) && !empty($_GET['term_code']))
            array_push($where_arr, "term_code::prefix_range <@ '{$_GET['term_code']}'");
            
        if(isset($_GET['group_select']) && !empty($_GET['group_select'])) {
            $group_select_arr = $_GET['group_select'];
            foreach($group_select_arr as $group_select) {
                if(!empty($group_select) && !in_array($group_select, $group_arr)) {
                    array_push($group_arr, $group_select);
                    if($group_select == 'ingress_client_id')
                        array_push($field_arr, "(SELECT name FROM client WHERE client_id = ingress_client_id) AS ingress_client_id"); 
                    elseif ($group_select == 'egress_client_id')
                        array_push($field_arr, "(SELECT name FROM client WHERE client_id = egress_client_id) AS egress_client_id");
                    elseif ($group_select == 'ingress_id')
                        array_push($field_arr, "(SELECT alias FROM resource WHERE resource_id = ingress_id) AS ingress_id"); 
                    elseif ($group_select == 'egress_id')
                        array_push($field_arr, "(SELECT alias FROM resource WHERE resource_id = egress_id) AS egress_id"); 
                    elseif($group_select == 'ingress_country')
                        array_push($field_arr, "orig_country as ingress_country");
                    elseif($group_select == 'ingress_code_name')
                        array_push($field_arr, "orig_code_name as ingress_code_name");
                    elseif($group_select == 'ingress_code')
                        array_push($field_arr, "orig_code as ingress_code");
                    elseif($group_select == 'egress_country')
                        array_push($field_arr, "term_country as egress_country");
                    elseif($group_select == 'egress_code_name')
                        array_push($field_arr, "term_code_name as egress_code_name");
                    elseif($group_select == 'egress_code')
                        array_push($field_arr, "term_code as egress_code");
                    elseif($group_select == 'ingress_rate')
                        array_push($field_arr, 'ingress_client_rate as ingress_rate');
                    else
                        array_push($field_arr, $group_select); 
                    $show_fields[$group_select] = $group_select;
                    $order_num++;
                }
            }
        }
        
        if (isset($_GET['rate_display_as']) && $_GET['rate_display_as'] == 1)
        {
            if ($type == 1)
            {
                array_push($group_arr, 'ingress_client_rate');
                array_push($field_arr, 'ingress_client_rate as actual_rate'); 
            }
            else
            {
                array_push($group_arr, 'egress_rate');
                array_push($field_arr, 'egress_rate as actual_rate'); 
            }
        }
        
        $fields = "";
        $groups = "";
        $orders = "";
        $wheres = "";
        if(count($field_arr)) {
            $fields = implode(',', $field_arr) . ",";
        }
        if(count($group_arr)) {
            $groups = "GROUP BY " . implode(',', $group_arr);
        }
        
        if($order_num > 0) {
            $orders = "ORDER BY " . implode(',', range(1, $order_num));
        }
        
        
        if(count($where_arr)) {
            $wheres = " and " . implode(' and ', $where_arr);
        }
        
        $sst_user_id = $_SESSION['sst_user_id'];
        if (!empty($groups)) {
            $filter_client = "and
(exists(SELECT id FROM users_limit WHERE user_id = {$sst_user_id} AND client_id=client_cdr.ingress_client_id) OR exists 
(SELECT users.user_id FROM users LEFT JOIN sys_role ON users.role_id = sys_role.role_id WHERE users.user_id = {$sst_user_id} 
and (role_name = 'admin' or sys_role.view_all = true)))";
        } else {
            $filter_client = '';
        };
        
        if($type == 1) {
        $sql = "SELECT
{$fields}        
sum(call_duration) as duration,
sum(ingress_client_bill_time) as bill_time,count(*) as
total_calls, count(case when call_duration > 0 then 1 else null end)
as not_zero_calls, count(case when egress_id is not null then 1 else null end)
as success_calls,count(case when
binary_value_of_release_cause_from_protocol_stack like '486%' then 1
else null end) as busy_calls,count(case when lrn_number_vendor != 0
then 1 else null end) as lrn_calls, sum(case when call_duration > 0
then pdd else 0 end) as pdd,count( case when
binary_value_of_release_cause_from_protocol_stack like '487%' then 1
else null end ) as cancel_calls
from client_cdr where  time between '{$start_date}' and '{$end_date}' 

and is_final_call=1 


{$wheres} {$filter_client}

{$groups} {$orders}";
        } else if($type == 2) {
        $sql = "SELECT
{$fields}   
sum(call_duration) as duration,
sum(egress_bill_time) as bill_time,count(*) as total_calls,
count(case when call_duration > 0 then 1 else null end) as
not_zero_calls, count(case when egress_id is not null then 1 else null end) as
success_calls,count(case when release_cause_from_protocol_stack like
'486%' then 1 else null end ) as busy_calls,sum(case when call_duration
> 0 then pdd else 0 end) as pdd,count( case when
release_cause_from_protocol_stack like '487%' then 1 else null end ) as
cancel_calls
from client_cdr where time between '{$start_date}' and '{$end_date}' 
{$wheres}  {$filter_client} {$groups} {$orders}";
        }
        
        
        return $sql;
        
    }
    
    public function get_summary_report_from_client_cdr($start_date, $end_date, $type) {
        $field_arr = array();
        $group_arr = array();
        $show_fields = array();
        $order_num = 0;
        $where_arr = array();
        $group_select_arr = array();
        
       
        if(isset($_GET['group_by_date']) && !empty($_GET['group_by_date'])) {
            array_push($field_arr, "to_char(time, '{$_GET['group_by_date']}') as group_time");
            $show_fields['group_time'] = "group_time";
            array_push($group_arr, "to_char(time, '{$_GET['group_by_date']}')");
            $order_num++;
        }
        
        
         if(isset($_GET['route_prefix']) && $_GET['route_prefix'] != 'all'){
            array_push($where_arr, "route_prefix = '{$_GET['route_prefix']}'");  
        }
        
        if (isset($_GET['ingress_rate_table']) && $_GET['ingress_rate_table'] != 'all')
        {            
            array_push($where_arr, "ingress_client_rate_table_id = {$_GET['ingress_rate_table']}");
        }
        
        if (isset($_GET['ingress_routing_plan']) && $_GET['ingress_routing_plan'] != 'all')
        {
            
            array_push($where_arr, "route_plan = {$_GET['ingress_routing_plan']}");
        }
        
        if (isset($_GET['server_ip']) && $_GET['server_ip'] != '')
        {
            
            array_push($where_arr, "origination_destination_host_name = '{$_GET['server_ip']}'");
        }
        
        if (isset($_GET['orig_rate_type']) && $_GET['orig_rate_type'] != '0')
        {
            if ($_GET['orig_rate_type'] == '1')
            {
                array_push($where_arr, "orig_jur_type = 0");
            }
            elseif ($_GET['orig_rate_type'] == '2')
            {
                array_push($where_arr, "orig_jur_type in (1, 2)");
            }
            elseif ($_GET['orig_rate_type'] == '3')
            {
                array_push($where_arr, "orig_jur_type in (3, 4)");
            }
        }
        
        if (isset($_GET['term_rate_type']) && $_GET['term_rate_type'] != '0')
        {
            if ($_GET['term_rate_type'] == '1')
            {
                array_push($where_arr, "term_jur_type = 0");
            }
            elseif ($_GET['term_rate_type'] == '2')
            {
                array_push($where_arr, "term_jur_type in (1, 2)");
            }
            elseif ($_GET['term_rate_type'] == '3')
            {
                array_push($where_arr, "term_jur_type in (3, 4)");
            }
        }
        
        if(isset($_GET['ingress_client_id']) && !empty($_GET['ingress_client_id']))
            array_push($where_arr, "ingress_client_id = {$_GET['ingress_client_id']}");
        if(isset($_GET['ingress_id']) && !empty($_GET['ingress_id']))
            array_push($where_arr, "ingress_id = {$_GET['ingress_id']}");    
        if(isset($_GET['orig_country']) && !empty($_GET['orig_country']))
            array_push($where_arr, "orig_country = '{$_GET['orig_country']}'");  
        if(isset($_GET['orig_code_name']) && !empty($_GET['orig_code_name']))
            array_push($where_arr, "orig_code_name = '{$_GET['orig_code_name']}'");  
        if(isset($_GET['orig_code']) && !empty($_GET['orig_code']))
            array_push($where_arr, "orig_code::prefix_range <@  '{$_GET['orig_code']}'");
        if(isset($_GET['egress_client_id']) && !empty($_GET['egress_client_id']))
            array_push($where_arr, "egress_client_id = {$_GET['egress_client_id']}");
        if(isset($_GET['egress_id']) && !empty($_GET['egress_id']))
            array_push($where_arr, "egress_id = {$_GET['egress_id']}");    
        if(isset($_GET['term_country']) && !empty($_GET['term_country']))
            array_push($where_arr, "term_country = '{$_GET['term_country']}'");  
        if(isset($_GET['term_code_name']) && !empty($_GET['term_code_name']))
            array_push($where_arr, "term_code_name = '{$_GET['term_code_name']}'");  
        if(isset($_GET['term_code']) && !empty($_GET['term_code']))
            array_push($where_arr, "term_code::prefix_range <@ '{$_GET['term_code']}'");
            
        if(isset($_GET['group_select']) && !empty($_GET['group_select'])) {
            $group_select_arr = $_GET['group_select'];
            foreach($group_select_arr as $group_select) {
                if(!empty($group_select) && !in_array($group_select, $group_arr)) {
                    array_push($group_arr, $group_select);
                    if($group_select == 'ingress_client_id')
                        array_push($field_arr, "(SELECT name FROM client WHERE client_id = ingress_client_id) AS ingress_client_id"); 
                    elseif ($group_select == 'egress_client_id')
                        array_push($field_arr, "(SELECT name FROM client WHERE client_id = egress_client_id) AS egress_client_id");
                    elseif ($group_select == 'ingress_id')
                        array_push($field_arr, "(SELECT alias FROM resource WHERE resource_id = ingress_id) AS ingress_id"); 
                    elseif ($group_select == 'egress_id')
                        array_push($field_arr, "(SELECT alias FROM resource WHERE resource_id = egress_id) AS egress_id"); 
                    elseif($group_select == 'ingress_country')
                        array_push($field_arr, "orig_country as ingress_country");
                    elseif($group_select == 'ingress_code_name')
                        array_push($field_arr, "orig_code_name as ingress_code_name");
                    elseif($group_select == 'ingress_code')
                        array_push($field_arr, "orig_code as ingress_code");
                    elseif($group_select == 'egress_country')
                        array_push($field_arr, "term_country as egress_country");
                    elseif($group_select == 'egress_code_name')
                        array_push($field_arr, "term_code_name as egress_code_name");
                    elseif($group_select == 'egress_code')
                        array_push($field_arr, "term_code as egress_code");
                    elseif($group_select == 'ingress_rate')
                        array_push($field_arr, 'ingress_client_rate as ingress_rate');
                    else
                        array_push($field_arr, $group_select); 
                    $show_fields[$group_select] = $group_select;
                    $order_num++;
                }
            }
        }
        
        if (isset($_GET['rate_display_as']) && $_GET['rate_display_as'] == 1)
        {
            if ($type == 1)
            {
                array_push($group_arr, 'ingress_client_rate');
                array_push($field_arr, 'ingress_client_rate as actual_rate'); 
            }
            else
            {
                array_push($group_arr, 'egress_rate');
                array_push($field_arr, 'egress_rate as actual_rate'); 
            }
        }
        
        $fields = "";
        $groups = "";
        $orders = "";
        $wheres = "";
        if(count($field_arr)) {
            $fields = implode(',', $field_arr) . ",";
        }
        if(count($group_arr)) {
            $groups = "GROUP BY " . implode(',', $group_arr);
        }
        
        if($order_num > 0) {
            $orders = "ORDER BY " . implode(',', range(1, $order_num));
        }
        
        
        if(count($where_arr)) {
            $wheres = " and " . implode(' and ', $where_arr);
        }
        
        $sst_user_id = $_SESSION['sst_user_id'];
        if (!empty($groups)) {
            $filter_client = "and
(exists(SELECT id FROM users_limit WHERE user_id = {$sst_user_id} AND client_id=client_cdr.ingress_client_id) OR exists 
(SELECT users.user_id FROM users LEFT JOIN sys_role ON users.role_id = sys_role.role_id WHERE users.user_id = {$sst_user_id} 
and (role_name = 'admin' or sys_role.view_all = true)))";
        } else {
            $filter_client = '';
        };
        
        if($type == 1) {
        $sql = "SELECT
{$fields}        
sum(call_duration) as duration,
sum(ingress_client_bill_time) as bill_time,
sum(ingress_client_cost) as call_cost,
sum(lnp_dipping_cost) as lnp_cost, count(*) as
total_calls, count(case when call_duration > 0 then 1 else null end)
as not_zero_calls, count(case when egress_id is not null then 1 else null end)
as success_calls,count(case when
binary_value_of_release_cause_from_protocol_stack like '486%' then 1
else null end) as busy_calls,count(case when lrn_number_vendor != 0
then 1 else null end) as lrn_calls, sum(case when call_duration > 0
then pdd else 0 end) as pdd,count( case when
binary_value_of_release_cause_from_protocol_stack like '487%' then 1
else null end ) as cancel_calls,
sum(case when ingress_rate_type = 1
then ingress_client_cost else 0 end) as inter_cost,
sum(case when ingress_rate_type = 2
then ingress_client_cost else 0 end) as intra_cost
from client_cdr where  time between '{$start_date}' and '{$end_date}' 

and is_final_call=1 


{$wheres} {$filter_client}

{$groups} {$orders}";
        } else if($type == 2) {
        $sql = "SELECT
{$fields}   
sum(call_duration) as duration,
sum(egress_bill_time) as bill_time,
sum(egress_cost) as call_cost, count(*) as total_calls,
count(case when call_duration > 0 then 1 else null end) as
not_zero_calls, count(case when egress_id is not null then 1 else null end) as
success_calls,count(case when release_cause_from_protocol_stack like
'486%' then 1 else null end ) as busy_calls,sum(case when call_duration
> 0 then pdd else 0 end) as pdd,count( case when
release_cause_from_protocol_stack like '487%' then 1 else null end ) as
cancel_calls,
sum(case when egress_rate_type = 1
then egress_cost else 0 end) as inter_cost,
sum(case when egress_rate_type = 2
then egress_cost else 0 end) as intra_cost
from client_cdr where time between '{$start_date}' and '{$end_date}' 
{$wheres}  {$filter_client} {$groups} {$orders}";
        }
        
        
        return $sql;
        
    }
    
    public function get_bandwidth_from_cdr($start_date, $end_date) {
        $field_arr = array();
        $group_arr = array();
        $show_fields = array();
        $order_num = 0;
        $where_arr = array();
        $group_select_arr = array();
        
       
        if(isset($_GET['group_by_date']) && !empty($_GET['group_by_date'])) {
            array_push($field_arr, "to_char(time, '{$_GET['group_by_date']}') as group_time");
            $show_fields['group_time'] = "group_time";
            array_push($group_arr, "to_char(time, '{$_GET['group_by_date']}')");
            $order_num++;
        }
        
        
          if(isset($_GET['route_prefix']) && $_GET['route_prefix'] != 'all'){
            array_push($where_arr, "route_prefix = '{$_GET['route_prefix']}'");  
        }
        
        if (isset($_GET['ingress_rate_table']) && $_GET['ingress_rate_table'] != 'all')
        {            
            array_push($where_arr, "ingress_client_rate_table_id = {$_GET['ingress_rate_table']}");
        }
        
        if (isset($_GET['ingress_routing_plan']) && $_GET['ingress_routing_plan'] != 'all')
        {
            
            array_push($where_arr, "route_plan = {$_GET['ingress_routing_plan']}");
        }
        
        if (isset($_GET['orig_rate_type']) && $_GET['orig_rate_type'] != '0')
        {
            if ($_GET['orig_rate_type'] == '1')
            {
                array_push($where_arr, "orig_jur_type = 0");
            }
            elseif ($_GET['orig_rate_type'] == '2')
            {
                array_push($where_arr, "orig_jur_type in (1, 2)");
            }
            elseif ($_GET['orig_rate_type'] == '3')
            {
                array_push($where_arr, "orig_jur_type in (3, 4)");
            }
        }
        
        if (isset($_GET['term_rate_type']) && $_GET['term_rate_type'] != '0')
        {
            if ($_GET['term_rate_type'] == '1')
            {
                array_push($where_arr, "term_jur_type = 0");
            }
            elseif ($_GET['term_rate_type'] == '2')
            {
                array_push($where_arr, "term_jur_type in (1, 2)");
            }
            elseif ($_GET['term_rate_type'] == '3')
            {
                array_push($where_arr, "term_jur_type in (3, 4)");
            }
        }
        
        if(isset($_GET['ingress_client_id']) && !empty($_GET['ingress_client_id']))
            array_push($where_arr, "ingress_client_id = {$_GET['ingress_client_id']}");
        if(isset($_GET['ingress_id']) && !empty($_GET['ingress_id']))
            array_push($where_arr, "ingress_id = {$_GET['ingress_id']}");    
        if(isset($_GET['orig_country']) && !empty($_GET['orig_country']))
            array_push($where_arr, "orig_country = '{$_GET['orig_country']}'");  
        if(isset($_GET['orig_code_name']) && !empty($_GET['orig_code_name']))
            array_push($where_arr, "orig_code_name = '{$_GET['orig_code_name']}'");  
        if(isset($_GET['orig_code']) && !empty($_GET['orig_code']))
            array_push($where_arr, "orig_code::prefix_range <@  '{$_GET['orig_code']}'");
        if(isset($_GET['egress_client_id']) && !empty($_GET['egress_client_id']))
            array_push($where_arr, "egress_client_id = {$_GET['egress_client_id']}");
        if(isset($_GET['egress_id']) && !empty($_GET['egress_id']))
            array_push($where_arr, "egress_id = {$_GET['egress_id']}");    
        if(isset($_GET['term_country']) && !empty($_GET['term_country']))
            array_push($where_arr, "term_country = '{$_GET['term_country']}'");  
        if(isset($_GET['term_code_name']) && !empty($_GET['term_code_name']))
            array_push($where_arr, "term_code_name = '{$_GET['term_code_name']}'");  
        if(isset($_GET['term_code']) && !empty($_GET['term_code']))
            array_push($where_arr, "term_code::prefix_range <@ '{$_GET['term_code']}'");
            
        if(isset($_GET['group_select']) && !empty($_GET['group_select'])) {
            $group_select_arr = $_GET['group_select'];
            foreach($group_select_arr as $group_select) {
                if(!empty($group_select) && !in_array($group_select, $group_arr)) {
                    array_push($group_arr, $group_select);
                    if($group_select == 'ingress_client_id')
                        array_push($field_arr, "(SELECT name FROM client WHERE client_id = ingress_client_id) AS ingress_client_id"); 
                    elseif ($group_select == 'egress_client_id')
                        array_push($field_arr, "(SELECT name FROM client WHERE client_id = egress_client_id) AS egress_client_id");
                    elseif ($group_select == 'ingress_id')
                        array_push($field_arr, "(SELECT alias FROM resource WHERE resource_id = ingress_id) AS ingress_id"); 
                    elseif ($group_select == 'egress_id')
                        array_push($field_arr, "(SELECT alias FROM resource WHERE resource_id = egress_id) AS egress_id"); 
                    elseif($group_select == 'ingress_country')
                        array_push($field_arr, "orig_country as ingress_country");
                    elseif($group_select == 'ingress_code_name')
                        array_push($field_arr, "orig_code_name as ingress_code_name");
                    elseif($group_select == 'ingress_code')
                        array_push($field_arr, "orig_code as ingress_code");
                    elseif($group_select == 'egress_country')
                        array_push($field_arr, "term_country as egress_country");
                    elseif($group_select == 'egress_code_name')
                        array_push($field_arr, "term_code_name as egress_code_name");
                    elseif($group_select == 'egress_code')
                        array_push($field_arr, "term_code as egress_code");
                    elseif($group_select == 'ingress_rate')
                        array_push($field_arr, 'ingress_client_rate as ingress_rate');
                    else
                        array_push($field_arr, $group_select); 
                    $show_fields[$group_select] = $group_select;
                    $order_num++;
                }
            }
        }
        
        $fields = "";
        $groups = "";
        $orders = "";
        $wheres = "";
        if(count($field_arr)) {
            $fields = implode(',', $field_arr) . ",";
        }
        if(count($group_arr)) {
            $groups = "GROUP BY " . implode(',', $group_arr);
        }
        
        if($order_num > 0) {
            $orders = "ORDER BY " . implode(',', range(1, $order_num));
        }
        
        
        if(count($where_arr)) {
            $wheres = " and " . implode(' and ', $where_arr);
        }
        $sst_user_id = $_SESSION['sst_user_id'];
        if (!empty($groups)) {
            $filter_client = "and
(exists(SELECT id FROM users_limit WHERE user_id = {$sst_user_id} AND client_id=client_cdr.ingress_client_id) OR exists 
(SELECT users.user_id FROM users LEFT JOIN sys_role ON users.role_id = sys_role.role_id WHERE users.user_id = {$sst_user_id} 
and (role_name = 'admin' or sys_role.view_all = true)))";
        } else {
            $filter_client = '';
        }
        
        $sql = "SELECT
{$fields}   
sum(origination_ingress_packets +termination_ingress_packets) as incoming_bandwidth,
sum(origination_egress_packets +termination_egress_packets) as outgoing_bandwidth,count(id) as calls 
from client_cdr where time between '{$start_date}' and '{$end_date}' {$wheres} {$filter_client}  {$groups} {$orders}";
        
        return $sql;
        
    }
    
    
    
    public function get_cdrs_two($sql1, $sql2, $type, $orders, $show_fields) {
        
        $fields = implode(', ', $show_fields);
        $group_by = count($show_fields) ? "group by {$fields}" : '';
        $the_fields = '';
        if(!empty($fields)) $the_fields = $fields . ',';
        if($type == 1) {
            $total_fields = "sum(duration) as duration, sum(bill_time) as bill_time, sum(call_cost) as call_cost, 
sum(lnp_cost) as lnp_cost, sum(total_calls) as total_calls, sum(not_zero_calls) as not_zero_calls, 
sum(success_calls) as success_calls, sum(busy_calls) as busy_calls, sum(lrn_calls) as lrn_calls, 
sum(pdd) as pdd, sum(cancel_calls) as cancel_calls,sum(inter_cost) as inter_cost, sum(intra_cost) as intra_cost";
        } else {
            $total_fields = "sum(duration) as duration, sum(bill_time) as bill_time, sum(call_cost) as call_cost,
sum(total_calls) as total_calls, sum(not_zero_calls) as not_zero_calls, sum(success_calls) as success_calls,
sum(busy_calls) as busy_calls, sum(pdd) as pdd, sum(cancel_calls) as cancel_calls,sum(inter_cost) as inter_cost, sum(intra_cost) as intra_cost";
        }
        $sql = <<<EOT
SELECT 
$the_fields
$total_fields
FROM 
(        
(
$sql1
)

union
(
$sql2
)
)  
as t 
$group_by $orders   
EOT;
        return $this->query($sql);
    }
    
    public function get_bandwidth_two($sql1, $sql2, $orders, $show_fields) {
        
        $fields = implode(', ', $show_fields);
        $group_by = count($show_fields) ? "group by {$fields}" : '';
        $the_fields = '';
        if(!empty($fields)) $the_fields = $fields . ',';
            $total_fields = "sum(incoming_bandwidth) as incoming_bandwidth, sum(outgoing_bandwidth) as outgoing_bandwidth, sum(calls) as calls";
        $sql = <<<EOT
SELECT 
$the_fields
$total_fields
FROM 
(        
(
$sql1
)

union
(
$sql2
)
)  
as t 
$group_by $orders   
EOT;
        return $this->query($sql);
    }
    
    public function get_inout_from_two($sql1, $sql2,  $orders, $show_fields) {
        $fields = implode(', ', $show_fields);
        $the_fields = '';
        $group_by = count($show_fields) ? "group by {$fields}" : '';
        if(!empty($fields)) $the_fields = $fields . ',';
        $total_fields = "sum(inbound_bill_time) as inbound_bill_time, sum(inbound_call_cost) as inbound_call_cost, sum(outbound_bill_time) as outbound_bill_time, 
sum(outbound_call_cost) as outbound_call_cost, sum(duration) as duration, sum(total_calls) as total_calls, 
sum(not_zero_calls) as not_zero_calls, sum(success_calls) as success_calls, sum(busy_calls) as busy_calls, 
sum(pdd) as pdd";
        
        $sql = <<<EOT
SELECT 
$the_fields
$total_fields
FROM 
(        
(
$sql1
)

union
(
$sql2
)
)  
as t 
$group_by $orders   
EOT;
        return $this->query($sql);
    }
    
    
    public function get_inout_from_client_cdr($start_date, $end_date) {
       $field_arr = array();
        $group_arr = array();
        $show_fields = array();
        $order_num = 0;
        $where_arr = array();
        $group_select_arr = array();
        
       
        if(isset($_GET['group_by_date']) && !empty($_GET['group_by_date'])) {
            array_push($field_arr, "to_char(time, '{$_GET['group_by_date']}') as group_time");
            $show_fields['group_time'] = "group_time";
            array_push($group_arr, "to_char(time, '{$_GET['group_by_date']}')");
            $order_num++;
        }
        
        
         if(isset($_GET['route_prefix']) && $_GET['route_prefix'] != 'all'){
            array_push($where_arr, "route_prefix = '{$_GET['route_prefix']}'");  
        }
        
        if (isset($_GET['ingress_rate_table']) && $_GET['ingress_rate_table'] != 'all')
        {            
            array_push($where_arr, "ingress_client_rate_table_id = {$_GET['ingress_rate_table']}");
        }
        
        if (isset($_GET['ingress_routing_plan']) && $_GET['ingress_routing_plan'] != 'all')
        {
            
            array_push($where_arr, "route_plan = {$_GET['ingress_routing_plan']}");
        }
        
        if (isset($_GET['orig_rate_type']) && $_GET['orig_rate_type'] != '0')
        {
            if ($_GET['orig_rate_type'] == '1')
            {
                array_push($where_arr, "orig_jur_type = 0");
            }
            elseif ($_GET['orig_rate_type'] == '2')
            {
                array_push($where_arr, "orig_jur_type in (1, 2)");
            }
            elseif ($_GET['orig_rate_type'] == '3')
            {
                array_push($where_arr, "orig_jur_type in (3, 4)");
            }
        }
        
        if (isset($_GET['term_rate_type']) && $_GET['term_rate_type'] != '0')
        {
            if ($_GET['term_rate_type'] == '1')
            {
                array_push($where_arr, "term_jur_type = 0");
            }
            elseif ($_GET['term_rate_type'] == '2')
            {
                array_push($where_arr, "term_jur_type in (1, 2)");
            }
            elseif ($_GET['term_rate_type'] == '3')
            {
                array_push($where_arr, "term_jur_type in (3, 4)");
            }
        }
        
        if(isset($_GET['ingress_client_id']) && !empty($_GET['ingress_client_id']))
            array_push($where_arr, "ingress_client_id = {$_GET['ingress_client_id']}");
        if(isset($_GET['ingress_id']) && !empty($_GET['ingress_id']))
            array_push($where_arr, "ingress_id = {$_GET['ingress_id']}");    
        if(isset($_GET['orig_country']) && !empty($_GET['orig_country']))
            array_push($where_arr, "orig_country = '{$_GET['orig_country']}'");  
        if(isset($_GET['orig_code_name']) && !empty($_GET['orig_code_name']))
            array_push($where_arr, "orig_code_name = '{$_GET['orig_code_name']}'");  
        if(isset($_GET['orig_code']) && !empty($_GET['orig_code']))
            array_push($where_arr, "orig_code::prefix_range <@  '{$_GET['orig_code']}'");
        if(isset($_GET['egress_client_id']) && !empty($_GET['egress_client_id']))
            array_push($where_arr, "egress_client_id = {$_GET['egress_client_id']}");
        if(isset($_GET['egress_id']) && !empty($_GET['egress_id']))
            array_push($where_arr, "egress_id = {$_GET['egress_id']}");    
        if(isset($_GET['term_country']) && !empty($_GET['term_country']))
            array_push($where_arr, "term_country = '{$_GET['term_country']}'");  
        if(isset($_GET['term_code_name']) && !empty($_GET['term_code_name']))
            array_push($where_arr, "term_code_name = '{$_GET['term_code_name']}'");  
        if(isset($_GET['term_code']) && !empty($_GET['term_code']))
            array_push($where_arr, "term_code::prefix_range <@ '{$_GET['term_code']}'");
            
        if(isset($_GET['group_select']) && !empty($_GET['group_select'])) {
            $group_select_arr = $_GET['group_select'];
            foreach($group_select_arr as $group_select) {
                if(!empty($group_select) && !in_array($group_select, $group_arr)) {
                    array_push($group_arr, $group_select);
                    if($group_select == 'ingress_client_id')
                        array_push($field_arr, "(SELECT name FROM client WHERE client_id = ingress_client_id) AS ingress_client_id"); 
                    elseif ($group_select == 'egress_client_id')
                        array_push($field_arr, "(SELECT name FROM client WHERE client_id = egress_client_id) AS egress_client_id");
                    elseif ($group_select == 'ingress_id')
                        array_push($field_arr, "(SELECT alias FROM resource WHERE resource_id = ingress_id) AS ingress_id"); 
                    elseif ($group_select == 'egress_id')
                        array_push($field_arr, "(SELECT alias FROM resource WHERE resource_id = egress_id) AS egress_id"); 
                    elseif($group_select == 'ingress_country')
                        array_push($field_arr, "orig_country as ingress_country");
                    elseif($group_select == 'ingress_code_name')
                        array_push($field_arr, "orig_code_name as ingress_code_name");
                    elseif($group_select == 'ingress_code')
                        array_push($field_arr, "orig_code as ingress_code");
                    elseif($group_select == 'egress_country')
                        array_push($field_arr, "term_country as egress_country");
                    elseif($group_select == 'egress_code_name')
                        array_push($field_arr, "term_code_name as egress_code_name");
                    elseif($group_select == 'egress_code')
                        array_push($field_arr, "term_code as egress_code");
                    elseif($group_select == 'ingress_rate')
                        array_push($field_arr, 'ingress_client_rate as ingress_rate');
                    else
                        array_push($field_arr, $group_select); 
                    $show_fields[$group_select] = $group_select;
                    $order_num++;
                }
            }
        }
        
        $fields = "";
        $groups = "";
        $orders = "";
        $wheres = "";
        if(count($field_arr)) {
            $fields = implode(',', $field_arr) . ",";
        }
        if(count($group_arr)) {
            $groups = "GROUP BY " . implode(',', $group_arr);
        }
        
        if($order_num > 0) {
            $orders = "ORDER BY " . implode(',', range(1, $order_num));
        }
        
        
        if(count($where_arr)) {
            $wheres = " and " . implode(' and ', $where_arr);
        }
        
        $sst_user_id = $_SESSION['sst_user_id'];
        if (!empty($groups)) {
            $filter_client = "and
(exists(SELECT id FROM users_limit WHERE user_id = {$sst_user_id} AND client_id=client_cdr.ingress_client_id) OR exists 
(SELECT users.user_id FROM users LEFT JOIN sys_role ON users.role_id = sys_role.role_id WHERE users.user_id = {$sst_user_id} 
and (role_name = 'admin' or sys_role.view_all = true)))";
        } else {
            $filter_client = '';
        }
        
        $sql = "select
{$fields}
sum(ingress_client_bill_time) as inbound_bill_time,
sum(ingress_client_cost) as inbound_call_cost,
sum(egress_bill_time) as outbound_bill_time,
sum(egress_cost) as outbound_call_cost,
sum(call_duration) as duration,
count(*) as total_calls,
count(case when call_duration > 0 then 1 else null end) as not_zero_calls,
count(case when egress_id is not null then 1 else null end) as success_calls,
count(case when release_cause_from_protocol_stack like
'486%' then 1 else null end) as busy_calls,
sum(case when call_duration > 0 then pdd else 0 end) as pdd
from client_cdr
where   time between '{$start_date}' and '{$end_date}' {$wheres} {$filter_client}  and is_final_call = 1 {$groups} {$orders}";
        return $sql;
    }
    
    public function get_usage_from_client_cdr($start_date, $end_date, $type) {
        
        
        $field_arr = array();
        $group_arr = array();
        $show_fields = array();
        $order_num = 0;
        $where_arr = array();
        $group_select_arr = array();
        
       
        if(isset($_GET['group_by_date']) && !empty($_GET['group_by_date'])) {
            array_push($field_arr, "to_char(time, '{$_GET['group_by_date']}') as group_time");
            $show_fields['group_time'] = "group_time";
            array_push($group_arr, "to_char(time, '{$_GET['group_by_date']}')");
            $order_num++;
        }
        
        
         if(isset($_GET['route_prefix']) && $_GET['route_prefix'] != 'all'){
            array_push($where_arr, "route_prefix = '{$_GET['route_prefix']}'");  
        }
        
        if (isset($_GET['ingress_rate_table']) && $_GET['ingress_rate_table'] != 'all')
        {            
            array_push($where_arr, "ingress_client_rate_table_id = {$_GET['ingress_rate_table']}");
        }
        
        if (isset($_GET['ingress_routing_plan']) && $_GET['ingress_routing_plan'] != 'all')
        {
            
            array_push($where_arr, "route_plan = {$_GET['ingress_routing_plan']}");
        }
        
        if (isset($_GET['orig_rate_type']) && $_GET['orig_rate_type'] != '0')
        {
            if ($_GET['orig_rate_type'] == '1')
            {
                array_push($where_arr, "orig_jur_type = 0");
            }
            elseif ($_GET['orig_rate_type'] == '2')
            {
                array_push($where_arr, "orig_jur_type in (1, 2)");
            }
            elseif ($_GET['orig_rate_type'] == '3')
            {
                array_push($where_arr, "orig_jur_type in (3, 4)");
            }
        }
        
        if (isset($_GET['term_rate_type']) && $_GET['term_rate_type'] != '0')
        {
            if ($_GET['term_rate_type'] == '1')
            {
                array_push($where_arr, "term_jur_type = 0");
            }
            elseif ($_GET['term_rate_type'] == '2')
            {
                array_push($where_arr, "term_jur_type in (1, 2)");
            }
            elseif ($_GET['term_rate_type'] == '3')
            {
                array_push($where_arr, "term_jur_type in (3, 4)");
            }
        }
        
        if(isset($_GET['ingress_client_id']) && !empty($_GET['ingress_client_id']))
            array_push($where_arr, "ingress_client_id = {$_GET['ingress_client_id']}");
        if(isset($_GET['ingress_id']) && !empty($_GET['ingress_id']))
            array_push($where_arr, "ingress_id = {$_GET['ingress_id']}");    
        if(isset($_GET['orig_country']) && !empty($_GET['orig_country']))
            array_push($where_arr, "orig_country = '{$_GET['orig_country']}'");  
        if(isset($_GET['orig_code_name']) && !empty($_GET['orig_code_name']))
            array_push($where_arr, "orig_code_name = '{$_GET['orig_code_name']}'");  
        if(isset($_GET['orig_code']) && !empty($_GET['orig_code']))
            array_push($where_arr, "orig_code::prefix_range <@  '{$_GET['orig_code']}'");
        if(isset($_GET['egress_client_id']) && !empty($_GET['egress_client_id']))
            array_push($where_arr, "egress_client_id = {$_GET['egress_client_id']}");
        if(isset($_GET['egress_id']) && !empty($_GET['egress_id']))
            array_push($where_arr, "egress_id = {$_GET['egress_id']}");    
        if(isset($_GET['term_country']) && !empty($_GET['term_country']))
            array_push($where_arr, "term_country = '{$_GET['term_country']}'");  
        if(isset($_GET['term_code_name']) && !empty($_GET['term_code_name']))
            array_push($where_arr, "term_code_name = '{$_GET['term_code_name']}'");  
        if(isset($_GET['term_code']) && !empty($_GET['term_code']))
            array_push($where_arr, "term_code::prefix_range <@ '{$_GET['term_code']}'");
            
        if(isset($_GET['group_select']) && !empty($_GET['group_select'])) {
            $group_select_arr = $_GET['group_select'];
            foreach($group_select_arr as $group_select) {
                if(!empty($group_select) && !in_array($group_select, $group_arr)) {
                     array_push($group_arr, $group_select);
                    if($group_select == 'ingress_client_id')
                        array_push($field_arr, "(SELECT name FROM client WHERE client_id = ingress_client_id) AS ingress_client_id"); 
                    elseif ($group_select == 'egress_client_id')
                        array_push($field_arr, "(SELECT name FROM client WHERE client_id = egress_client_id) AS egress_client_id");
                    elseif ($group_select == 'ingress_id')
                        array_push($field_arr, "(SELECT alias FROM resource WHERE resource_id = ingress_id) AS ingress_id"); 
                    elseif ($group_select == 'egress_id')
                        array_push($field_arr, "(SELECT alias FROM resource WHERE resource_id = egress_id) AS egress_id"); 
                    elseif($group_select == 'ingress_country')
                        array_push($field_arr, "orig_country as ingress_country");
                    elseif($group_select == 'ingress_code_name')
                        array_push($field_arr, "orig_code_name as ingress_code_name");
                    elseif($group_select == 'ingress_code')
                        array_push($field_arr, "orig_code as ingress_code");
                    elseif($group_select == 'egress_country')
                        array_push($field_arr, "term_country as egress_country");
                    elseif($group_select == 'egress_code_name')
                        array_push($field_arr, "term_code_name as egress_code_name");
                    elseif($group_select == 'egress_code')
                        array_push($field_arr, "term_code as egress_code");
                    elseif($group_select == 'ingress_rate')
                        array_push($field_arr, 'ingress_client_rate as ingress_rate');
                    else
                        array_push($field_arr, $group_select); 
                    $show_fields[$group_select] = $group_select;
                    $order_num++;
                }
            }
        }
        
        $fields = "";
        $groups = "";
        $orders = "";
        $wheres = "";
        if(count($field_arr)) {
            $fields = implode(',', $field_arr) . ",";
        }
        if(count($group_arr)) {
            $groups = "GROUP BY " . implode(',', $group_arr);
        }
        
        if($order_num > 0) {
            $orders = "ORDER BY " . implode(',', range(1, $order_num));
        }
        
        
        if(count($where_arr)) {
            $wheres = " and " . implode(' and ', $where_arr);
        }
        
        $sst_user_id = $_SESSION['sst_user_id'];
        if (!empty($groups)) {
            $filter_client = "and
(exists(SELECT id FROM users_limit WHERE user_id = {$sst_user_id} AND client_id=client_cdr.ingress_client_id) OR exists 
(SELECT users.user_id FROM users LEFT JOIN sys_role ON users.role_id = sys_role.role_id WHERE users.user_id = {$sst_user_id} 
and (role_name = 'admin' or sys_role.view_all = true)))";
        } else {
            $filter_client = '';
        }
        
        if($type == 1) {
        $sql = "SELECT
{$fields}        
sum(call_duration) as duration,
count(*) as cdr_count
from client_cdr where time between '{$start_date}' and '{$end_date}' {$wheres} {$filter_client} and is_final_call=1  {$groups} {$orders}";
        } else if($type == 2) {
        $sql = "SELECT
{$fields}   
sum(call_duration) as duration,
count(*) as cdr_count
from client_cdr where time between '{$start_date}' and '{$end_date}' {$wheres} {$filter_client}  {$groups} {$orders}";
        }
        
        //echo $sql;
        $result = $this->query($sql);
        return $result;
        
    }
    
    
    
    public function get_profit_from_client_cdr($start_date, $end_date, $type) {
       $field_arr = array();
        $group_arr = array();
        $show_fields = array();
        $order_num = 0;
        $where_arr = array();
        $group_select_arr = array();
        
       
        if(isset($_GET['group_by_date']) && !empty($_GET['group_by_date'])) {
            array_push($field_arr, "to_char(time, '{$_GET['group_by_date']}') as group_time");
            $show_fields['group_time'] = "group_time";
            array_push($group_arr, "to_char(time, '{$_GET['group_by_date']}')");
            $order_num++;
        }
        
        
          if(isset($_GET['route_prefix']) && $_GET['route_prefix'] != 'all'){
            array_push($where_arr, "route_prefix = '{$_GET['route_prefix']}'");  
        }
        
        if (isset($_GET['ingress_rate_table']) && $_GET['ingress_rate_table'] != 'all')
        {            
            array_push($where_arr, "ingress_client_rate_table_id = {$_GET['ingress_rate_table']}");
        }
        
        if (isset($_GET['ingress_routing_plan']) && $_GET['ingress_routing_plan'] != 'all')
        {
            
            array_push($where_arr, "route_plan = {$_GET['ingress_routing_plan']}");
        }
        
        if (isset($_GET['orig_rate_type']) && $_GET['orig_rate_type'] != '0')
        {
            if ($_GET['orig_rate_type'] == '1')
            {
                array_push($where_arr, "orig_jur_type = 0");
            }
            elseif ($_GET['orig_rate_type'] == '2')
            {
                array_push($where_arr, "orig_jur_type in (1, 2)");
            }
            elseif ($_GET['orig_rate_type'] == '3')
            {
                array_push($where_arr, "orig_jur_type in (3, 4)");
            }
        }
        
        if (isset($_GET['term_rate_type']) && $_GET['term_rate_type'] != '0')
        {
            if ($_GET['term_rate_type'] == '1')
            {
                array_push($where_arr, "term_jur_type = 0");
            }
            elseif ($_GET['term_rate_type'] == '2')
            {
                array_push($where_arr, "term_jur_type in (1, 2)");
            }
            elseif ($_GET['term_rate_type'] == '3')
            {
                array_push($where_arr, "term_jur_type in (3, 4)");
            }
        }
        
        if(isset($_GET['ingress_client_id']) && !empty($_GET['ingress_client_id']))
            array_push($where_arr, "ingress_client_id = {$_GET['ingress_client_id']}");
        if(isset($_GET['ingress_id']) && !empty($_GET['ingress_id']))
            array_push($where_arr, "ingress_id = {$_GET['ingress_id']}");    
        if(isset($_GET['orig_country']) && !empty($_GET['orig_country']))
            array_push($where_arr, "orig_country = '{$_GET['orig_country']}'");  
        if(isset($_GET['orig_code_name']) && !empty($_GET['orig_code_name']))
            array_push($where_arr, "orig_code_name = '{$_GET['orig_code_name']}'");  
        if(isset($_GET['orig_code']) && !empty($_GET['orig_code']))
            array_push($where_arr, "orig_code::prefix_range <@  '{$_GET['orig_code']}'");
        if(isset($_GET['egress_client_id']) && !empty($_GET['egress_client_id']))
            array_push($where_arr, "egress_client_id = {$_GET['egress_client_id']}");
        if(isset($_GET['egress_id']) && !empty($_GET['egress_id']))
            array_push($where_arr, "egress_id = {$_GET['egress_id']}");    
        if(isset($_GET['term_country']) && !empty($_GET['term_country']))
            array_push($where_arr, "term_country = '{$_GET['term_country']}'");  
        if(isset($_GET['term_code_name']) && !empty($_GET['term_code_name']))
            array_push($where_arr, "term_code_name = '{$_GET['term_code_name']}'");  
        if(isset($_GET['term_code']) && !empty($_GET['term_code']))
            array_push($where_arr, "term_code::prefix_range <@ '{$_GET['term_code']}'");
            
        if(isset($_GET['group_select']) && !empty($_GET['group_select'])) {
            $group_select_arr = $_GET['group_select'];
            foreach($group_select_arr as $group_select) {
                if(!empty($group_select) && !in_array($group_select, $group_arr)) {
                    array_push($group_arr, $group_select);
                    if($group_select == 'ingress_client_id')
                        array_push($field_arr, "(SELECT name FROM client WHERE client_id = ingress_client_id) AS ingress_client_id"); 
                    elseif ($group_select == 'egress_client_id')
                        array_push($field_arr, "(SELECT name FROM client WHERE client_id = egress_client_id) AS egress_client_id");
                    elseif ($group_select == 'ingress_id')
                        array_push($field_arr, "(SELECT alias FROM resource WHERE resource_id = ingress_id) AS ingress_id"); 
                    elseif ($group_select == 'egress_id')
                        array_push($field_arr, "(SELECT alias FROM resource WHERE resource_id = egress_id) AS egress_id"); 
                    elseif($group_select == 'ingress_country')
                        array_push($field_arr, "orig_country as ingress_country");
                    elseif($group_select == 'ingress_code_name')
                        array_push($field_arr, "orig_code_name as ingress_code_name");
                    elseif($group_select == 'ingress_code')
                        array_push($field_arr, "orig_code as ingress_code");
                    elseif($group_select == 'egress_country')
                        array_push($field_arr, "term_country as egress_country");
                    elseif($group_select == 'egress_code_name')
                        array_push($field_arr, "term_code_name as egress_code_name");
                    elseif($group_select == 'egress_code')
                        array_push($field_arr, "term_code as egress_code");
                    elseif($group_select == 'ingress_rate')
                        array_push($field_arr, 'ingress_client_rate as ingress_rate');
                    else
                        array_push($field_arr, $group_select); 
                    $show_fields[$group_select] = $group_select;
                    $order_num++;
                }
            }
        }
        
        $fields = "";
        $groups = "";
        $orders = "";
        $wheres = "";
        if(count($field_arr)) {
            $fields = implode(',', $field_arr) . ",";
        }
        if(count($group_arr)) {
            $groups = "GROUP BY " . implode(',', $group_arr);
        }
        
        if($order_num > 0) {
            $orders = "ORDER BY " . implode(',', range(1, $order_num));
        }
        
        
        if(count($where_arr)) {
            $wheres = " and " . implode(' and ', $where_arr);
        }
         if ($type == 1)
            {
                $bill_time = "ingress_client_bill_time";
            }
            else
            {
                $bill_time = "egress_bill_time";
            }
            
        $sst_user_id = $_SESSION['sst_user_id'];
        if (!empty($groups)) {
            $filter_client = "and
(exists(SELECT id FROM users_limit WHERE user_id = {$sst_user_id} AND client_id=client_cdr.ingress_client_id) OR exists 
(SELECT users.user_id FROM users LEFT JOIN sys_role ON users.role_id = sys_role.role_id WHERE users.user_id = {$sst_user_id} 
and (role_name = 'admin' or sys_role.view_all = true)))";
        } else {
            $filter_client = '';
        }    
        
        $sql = "select
{$fields}
sum(ingress_client_cost) as inbound_call_cost,
sum(egress_cost) as outbound_call_cost,
sum(call_duration) as duration,
sum({$bill_time}) as bill_time,
count(*) as total_calls,
count(case when call_duration > 0 then 1 else null end) as not_zero_calls,
count(case when egress_id is not null then 1 else null end) as success_calls
from client_cdr
where   time between '{$start_date}' and '{$end_date}' {$wheres} {$filter_client}  and is_final_call = 1 {$groups} {$orders}";
        return $sql;
    }
    
    public function get_profit_cdrs($start_date, $end_date,  $fields, $groups, $orders, $wheres, $type, $table_name) {
            if ($type == 1)
            {
                $bill_time = "ingress_bill_time";
            }
            else
            {
                $bill_time = "egress_bill_time";
            }
        $sst_user_id = $_SESSION['sst_user_id'];
        if (!empty($groups)) {
            $filter_client = "and
(exists(SELECT id FROM users_limit WHERE user_id = {$sst_user_id} AND client_id={$table_name}.ingress_client_id) OR exists 
(SELECT users.user_id FROM users LEFT JOIN sys_role ON users.role_id = sys_role.role_id WHERE users.user_id = {$sst_user_id} 
and (role_name = 'admin' or sys_role.view_all = true)))";
        } else {
            $filter_client = '';
        }    
            
        $sql = "select
{$fields}
sum(ingress_call_cost) as inbound_call_cost,
sum(egress_call_cost) as outbound_call_cost,
sum(duration) as duration,
sum({$bill_time}) as bill_time,
sum(ingress_total_calls) as total_calls,
sum(not_zero_calls) as not_zero_calls,
sum(ingress_success_calls) as success_calls
from {$table_name}
where report_time between '{$start_date}' and '{$end_date}' {$wheres} {$filter_client}  {$groups} {$orders}";
        return $sql;
    }
    
    
    public function get_profit_from_two($sql1, $sql2, $type, $orders, $show_fields) {
        
        $fields = implode(', ', $show_fields);
        $group_by = count($show_fields) ? "group by {$fields}" : '';
        $the_fields = '';
        if(!empty($fields)) $the_fields = $fields . ',';
        if($type == 1) {
            $total_fields = "sum(inbound_call_cost) as inbound_call_cost,  
sum(outbound_call_cost) as outbound_call_cost, sum(duration) as duration, sum(total_calls) as total_calls, 
sum(not_zero_calls) as not_zero_calls, sum(success_calls) as success_calls, sum(bill_time) as bill_time";
        } else {
            $total_fields = "sum(inbound_call_cost) as inbound_call_cost,  
sum(outbound_call_cost) as outbound_call_cost, sum(duration) as duration, sum(total_calls) as total_calls, 
sum(not_zero_calls) as not_zero_calls, sum(success_calls) as success_calls, sum(bill_time) as bill_time";
        }
        $sql = <<<EOT
SELECT 
$the_fields
$total_fields
FROM 
(        
(
$sql1
)

union
(
$sql2
)
)  
as t 
$group_by $orders   
EOT;
        return $this->query($sql);
    }
        
}

?>


