<?php

class ReportsController extends AppController
{

    var $name = "Reports";
    var $uses = array('Cdrs', 'Cdr');
    var $components = array('RequestHandler');
    var $helpers = array('javascript', 'html', 'Common');

    public function beforeFilter()
    {
        $this->checkSession("login_type");
        parent::beforeFilter();
    }

    public function summary($type = 1)
    {
        $this->pageTitle = "Statistics/Summary Report";

        $table_name = 'cdr_report';

        extract($this->Cdr->get_start_end_time());

        $start_date = $start_date;
        $end_date = $end_date;

        $gmt = $tz;

        $field_arr = array();
        $out_fields_arr = array();
        $group_arr = array();
        $group_arr_main = array();
        $show_fields = array();
        $order_num = 0;
        $where_arr = array();
        $group_select_arr = array();

        if (isset($_GET['start_date']) && !empty($_GET['start_date']))
            $start_date = $_GET['start_date'] . ' ' . $_GET['start_time'];
        if (isset($_GET['stop_date']) && !empty($_GET['stop_date']))
            $end_date = $_GET['stop_date'] . ' ' . $_GET['stop_time'];
        if (isset($_GET['query']['tz']) && !empty($_GET['query']['tz']))
            $gmt = $_GET['query']['tz'];
        $start_date .= ' ' . $gmt;
        $end_date .= ' ' . $gmt;

        if (isset($_GET['group_by_date']) && !empty($_GET['group_by_date']))
        {
            array_push($field_arr, "to_char(report_time, '{$_GET['group_by_date']}') as report_time");
            array_push($out_fields_arr, 'report_time as group_time');
            $show_fields['group_time'] = "group_time";
            array_push($group_arr, "to_char(report_time, '{$_GET['group_by_date']}')");
            array_push($group_arr_main, "report_time");
            $order_num++;
        }

        if (!empty($_GET['ingress_id']))
        {
            $res = $this->Cdr->findTechPerfix($_GET['ingress_id']);
            $this->set('tech_perfix', $res);
            
            $ingress_options = $this->Cdrs->get_ingress_options($_GET['ingress_id']);
            
            $this->set('ingress_options', $ingress_options);
        }


        if (isset($_GET['route_prefix']) && $_GET['route_prefix'] != 'all')
        {
            if ($_GET['route_prefix'] == '')
            {
                array_push($where_arr, "(ingress_prefix = '\"\"' or ingress_prefix='' or ingress_prefix is null)");
            } else
            {
                array_push($where_arr, "ingress_prefix = '{$_GET['route_prefix']}'");
            }
        }
        
        if (isset($_GET['ingress_rate_table']) && $_GET['ingress_rate_table'] != 'all')
        {
            
            array_push($where_arr, "ingress_rate_table_id = {$_GET['ingress_rate_table']}");
        }
        
        if (isset($_GET['ingress_routing_plan']) && $_GET['ingress_routing_plan'] != 'all')
        {
            
            array_push($where_arr, "route_plan_id = {$_GET['ingress_routing_plan']}");
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


        if (isset($_GET['ingress_client_id']) && !empty($_GET['ingress_client_id']))
            array_push($where_arr, "ingress_client_id = {$_GET['ingress_client_id']}");
        if (isset($_GET['ingress_id']) && !empty($_GET['ingress_id']))
            array_push($where_arr, "ingress_id = {$_GET['ingress_id']}");
        if (isset($_GET['orig_country']) && !empty($_GET['orig_country']))
        {
            array_push($where_arr, "ingress_country = '{$_GET['orig_country']}'");
            $table_name = 'cdr_report_detail';
        }
        if (isset($_GET['orig_code_name']) && !empty($_GET['orig_code_name']))
        {
            array_push($where_arr, "ingress_code_name = '{$_GET['orig_code_name']}'");
            $table_name = 'cdr_report_detail';
        }
        if (isset($_GET['orig_code']) && !empty($_GET['orig_code']))
        {
            array_push($where_arr, "ingress_code::prefix_range <@  '{$_GET['orig_code']}'");
            $table_name = 'cdr_report_detail';
        }
        if (isset($_GET['egress_client_id']) && !empty($_GET['egress_client_id']))
            array_push($where_arr, "egress_client_id = {$_GET['egress_client_id']}");
        if (isset($_GET['egress_id']) && !empty($_GET['egress_id']))
            array_push($where_arr, "egress_id = {$_GET['egress_id']}");
        if (isset($_GET['term_country']) && !empty($_GET['term_country']))
        {
            array_push($where_arr, "egress_country = '{$_GET['term_country']}'");
            $table_name = 'cdr_report_detail';
        }
        if (isset($_GET['term_code_name']) && !empty($_GET['term_code_name']))
        {
            array_push($where_arr, "egress_code_name = '{$_GET['term_code_name']}'");
            $table_name = 'cdr_report_detail';
        }
        if (isset($_GET['term_code']) && !empty($_GET['term_code']))
        {
            array_push($where_arr, "egress_code::prefix_range <@ '{$_GET['term_code']}'");
            $table_name = 'cdr_report_detail';
        }

        if (isset($_GET['group_select']) && !empty($_GET['group_select']))
        {
            $group_select_arr = $_GET['group_select'];
            foreach ($group_select_arr as $group_select)
            {
                if (!empty($group_select) && !in_array($group_select, $group_arr))
                {
                    array_push($group_arr, $group_select);
                    array_push($group_arr_main, $group_select);
                    if ($group_select == 'ingress_client_id') {
                        array_push($field_arr, "(SELECT name FROM client WHERE client_id = ingress_client_id) AS ingress_client_id");
                        array_push($out_fields_arr, 'ingress_client_id');
                    } elseif ($group_select == 'egress_client_id') {
                        array_push($field_arr, "(SELECT name FROM client WHERE client_id = egress_client_id) AS egress_client_id");
                        array_push($out_fields_arr, 'egress_client_id');
                    } elseif ($group_select == 'ingress_id') {
                        array_push($field_arr, "(SELECT alias FROM resource WHERE resource_id = ingress_id) AS ingress_id");
                        array_push($out_fields_arr, 'ingress_id');
                    } elseif ($group_select == 'egress_id') {
                        array_push($field_arr, "(SELECT alias FROM resource WHERE resource_id = egress_id) AS egress_id");
                        array_push($out_fields_arr, 'egress_id');
                    } else {
                        array_push($field_arr, $group_select);
                        array_push($out_fields_arr, $group_select);
                    }
                    $show_fields[$group_select] = $group_select;
                    $order_num++;
                }
            }
        }
        
        if (isset($_GET['rate_display_as']) && $_GET['rate_display_as'] == 1)
        {
            $table_name = 'cdr_report_detail';
            if ($type == 1)
            {
                array_push($group_arr, 'ingress_rate');
                array_push($field_arr, 'ingress_rate as actual_rate');
                array_push($out_fields_arr, 'actual_rate');
                $show_fields['actual_rate'] = 'actual_rate';
            }
            else
            {
                array_push($group_arr, 'egress_rate');
                array_push($group_arr_main, 'egress_rate');
                array_push($field_arr, 'egress_rate as actual_rate');
                array_push($out_fields_arr, 'actual_rate');
                $show_fields['actual_rate'] = 'actual_rate';
            }
        }

        $fields = "";
        $out_fields = "";
        $groups = "";
        $groups_main = "";
        $orders = "";
        $wheres = "";
        if (count($field_arr))
        {
            $fields = implode(',', $field_arr) . ",";
        }
        if (count($out_fields_arr))
        {
            $out_fields = implode(',', $out_fields_arr) . ",";
        }
        if (count($group_arr))
        {
            $from_detail = array('ingress_country', 'ingress_code_name', 'ingress_code', 'ingress_rate',
                'egress_country', 'egress_code_name', 'egress_code', 'origination_destination_host_name', 'termination_source_host_name');
            $compare_detail = array_intersect($group_arr, $from_detail);
            if (count($compare_detail))
                $table_name = 'cdr_report_detail';
            $groups = "GROUP BY " . implode(',', $group_arr);
        }
        if (count($group_arr_main))
        {
            $from_detail = array('ingress_country', 'ingress_code_name', 'ingress_code', 'ingress_rate',
                'egress_country', 'egress_code_name', 'egress_code', 'origination_destination_host_name', 'termination_source_host_name');
            $compare_detail = array_intersect($group_arr_main, $from_detail);
            if (count($compare_detail))
                $table_name = 'cdr_report_detail';
            $groups_main = "GROUP BY " . implode(',', $group_arr_main);
        }

        if ($order_num > 0)
        {
            $orders = "ORDER BY " . implode(',', range(1, $order_num));
        }


        if (count($where_arr))
        {
            $wheres = " and " . implode(' and ', $where_arr);
        }

        $replace_fields = array(
            'group_time' => 'Group Time',
            'ingress_client_id' => 'Ingress Carrier',
            'ingress_id' => 'Ingress Trunk',
            'ingress_country' => 'ORIG Country',
            'ingress_code_name' => 'ORIG Code Name',
            'ingress_code' => 'ORIG Code',
            'ingress_rate' => 'ORIG Rate',
            'egress_client_id' => 'Engress Carrier',
            'egress_id' => 'Egress Trunk',
            'egress_country' => 'TERM Country',
            'egress_code_name' => 'TERM Code Name',
            'egress_code' => 'TERM Code',
            'origination_destination_host_name' => 'Orig Server',
            'termination_source_host_name' => 'Term Server',
        );

        $ingress_clients = $this->Cdrs->get_ingress_clients();
        $egress_clients = $this->Cdrs->get_egress_clients();
        $switch_ips = $this->Cdrs->get_switch_ip();
        $ingress_trunks = $this->Cdrs->get_ingress_trunks();
        $egress_trunks = $this->Cdrs->get_egress_trunks();

        $report_max_time = $this->Cdr->get_report_maxtime($start_date, $end_date);


        $select_time_end = strtotime($end_date);

        $is_from_client_cdr = false;

        if (empty($report_max_time))
        {
            $is_from_client_cdr = true;
            $report_max_time = $start_date;
        }
        $system_max_end = strtotime($report_max_time);
        
        
        $sql = "SELECT is_preload FROM system_parameter LIMIT 1";
        $is_preload_result = $this->Cdrs->query($sql);
        $is_preload = $is_preload_result[0][0]['is_preload'];
        $this->set('show_nodata', true);
        session_write_close();
        if(isset($_GET['show_type']) || $is_preload)
        {

//            if ($select_time_end > $system_max_end)
//            {
//                if ($is_from_client_cdr)
//                {
//                    $sql = $this->Cdrs->get_summary_report_from_client_cdr($report_max_time, $end_date, $type);
//                    $data = $this->Cdrs->query($sql);
//                } else
//                {
//                    $sql1 = $this->Cdrs->get_cdrs($start_date, $report_max_time, $fields, $groups, $orders, $wheres, $type, $table_name);
//                    $sql2 = $this->Cdrs->get_summary_report_from_client_cdr($report_max_time, $end_date, $type);
//                    $data = $this->Cdrs->get_cdrs_two($sql1, $sql2, $type, $orders, $show_fields);
//                }
//            } else
//            {
                $sql = $this->Cdrs->get_cdrs($start_date, $end_date, $fields, $groups, $orders, $wheres, $type, $table_name, $out_fields, $groups_main);
//                die(var_dump($sql));

                $data = $this->Cdrs->query($sql);
//            }
        
        } else 
        {
            $data = array();
            $this->set('show_nodata', false);
        }

        //var_dump($data);
        
        $rate_tables=$this->Cdrs->get_rate_tables();
        $routing_plans=$this->Cdrs->get_routing_plans();

        
        $this->set('servers', $this->Cdr->find_server());
        $this->set('ingress_clients', $ingress_clients);
        $this->set('egress_clients', $egress_clients);
        //$this->set('switch_ips', $switch_ips);
        $this->set('ingress_trunks', $ingress_trunks);
        $this->set('egress_trunks', $egress_trunks);
        if (isset($show_fields['actual_rate']))
            unset($show_fields['actual_rate']);
        $this->set('show_fields', $show_fields);
        $this->set('replace_fields', $replace_fields);
        $this->set('start_date', $start_date);
        $this->set('end_date', $end_date);
        $this->set('type', $type);
        $this->set('data', $data);
        $this->set('rate_tables', $rate_tables);
        $this->set('routing_plans', $routing_plans);

        if (isset($_GET['show_type']) && $_GET['show_type'] == '1')
        {
            header("Pragma: public");
            header("Expires: 0");
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("Content-Type: text/csv");
            header("Content-Type: application/octet-stream");
            header("Content-Type: application/download");
            header("Content-Disposition: attachment;filename=summary_report_{$_GET['start_date']}_{$_GET['stop_date']}.csv");
            header("Content-Transfer-Encoding: binary ");
            Configure::write('debug', 0);
            $this->autoLayout = FALSE;
            $this->render('summary_down_csv');
        } else if (isset($_GET['show_type']) && $_GET['show_type'] == '2')
        {
            header("Pragma: public");
            header("Expires: 0");
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("Content-Type: application/force-download");
            header("Content-Type: application/octet-stream");
            header("Content-Type: application/download");
            header("Content-Disposition: attachment;filename=summary_report_{$_GET['start_date']}_{$_GET['stop_date']}.xls");
            header("Content-Transfer-Encoding: binary ");
            Configure::write('debug', 0);
            $this->autoLayout = FALSE;
            $this->render('summary_down_xls');
        }
    }

    public function inout_report()
    {
        $this->pageTitle = "Statistics/Inbound/Outbound Report";

        extract($this->Cdr->get_start_end_time());

        $table_name = 'cdr_report';

        $start_date = $start_date;
        $end_date = $end_date;

        $gmt = $tz;


        $field_arr = array();
        $group_arr = array();
        $show_fields = array();
        $order_num = 0;
        $where_arr = array();
        $group_select_arr = array();


        



        if (isset($_GET['start_date']) && !empty($_GET['start_date']))
            $start_date = $_GET['start_date'] . ' ' . $_GET['start_time'];
        if (isset($_GET['stop_date']) && !empty($_GET['stop_date']))
            $end_date = $_GET['stop_date'] . ' ' . $_GET['stop_time'];
        if (isset($_GET['query']['tz']) && !empty($_GET['query']['tz']))
            $gmt = $_GET['query']['tz'];

        $start_date .= ' ' . $gmt;
        $end_date .= ' ' . $gmt;

        if (isset($_GET['group_by_date']) && !empty($_GET['group_by_date']))
        {
            array_push($field_arr, "to_char(report_time, '{$_GET['group_by_date']}') as group_time");
            $show_fields['group_time'] = "group_time";
            array_push($group_arr, "to_char(report_time, '{$_GET['group_by_date']}')");
            $order_num++;
        }

        if (!empty($_GET['ingress_id']))
        {
            $res = $this->Cdr->findTechPerfix($_GET['ingress_id']);
            $this->set('tech_perfix', $res);
            
            $ingress_options = $this->Cdrs->get_ingress_options($_GET['ingress_id']);
            
            $this->set('ingress_options', $ingress_options);
        }


        if (isset($_GET['route_prefix']) && $_GET['route_prefix'] != 'all')
        {
            if ($_GET['route_prefix'] == '')
            {
                array_push($where_arr, "(ingress_prefix = '\"\"' or ingress_prefix='' or ingress_prefix is null)");
            } else
            {
                array_push($where_arr, "ingress_prefix = '{$_GET['route_prefix']}'");
            }
        }
        
         if (isset($_GET['ingress_rate_table']) && $_GET['ingress_rate_table'] != 'all')
        {
            
            array_push($where_arr, "ingress_rate_table_id = {$_GET['ingress_rate_table']}");
        }
        
        if (isset($_GET['ingress_routing_plan']) && $_GET['ingress_routing_plan'] != 'all')
        {
            
            array_push($where_arr, "route_plan_id = {$_GET['ingress_routing_plan']}");
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

        if (isset($_GET['ingress_client_id']) && !empty($_GET['ingress_client_id']))
            array_push($where_arr, "ingress_client_id = {$_GET['ingress_client_id']}");
        if (isset($_GET['ingress_id']) && !empty($_GET['ingress_id']))
            array_push($where_arr, "ingress_id = {$_GET['ingress_id']}");
        if (isset($_GET['orig_country']) && !empty($_GET['orig_country']))
        {
            array_push($where_arr, "ingress_country = '{$_GET['orig_country']}'");
            $table_name = 'cdr_report_detail';
        }
        if (isset($_GET['orig_code_name']) && !empty($_GET['orig_code_name']))
        {
            array_push($where_arr, "ingress_code_name = '{$_GET['orig_code_name']}'");
            $table_name = 'cdr_report_detail';
        }
        if (isset($_GET['orig_code']) && !empty($_GET['orig_code']))
        {
            array_push($where_arr, "ingress_code::prefix_range <@  '{$_GET['orig_code']}'");
            $table_name = 'cdr_report_detail';
        }
        if (isset($_GET['egress_client_id']) && !empty($_GET['egress_client_id']))
            array_push($where_arr, "egress_client_id = {$_GET['egress_client_id']}");
        if (isset($_GET['egress_id']) && !empty($_GET['egress_id']))
            array_push($where_arr, "egress_id = {$_GET['egress_id']}");
        if (isset($_GET['term_country']) && !empty($_GET['term_country']))
        {
            array_push($where_arr, "egress_country = '{$_GET['term_country']}'");
            $table_name = 'cdr_report_detail';
        }
        if (isset($_GET['term_code_name']) && !empty($_GET['term_code_name']))
        {
            array_push($where_arr, "egress_code_name = '{$_GET['term_code_name']}'");
            $table_name = 'cdr_report_detail';
        }
        if (isset($_GET['term_code']) && !empty($_GET['term_code']))
        {
            array_push($where_arr, "egress_code::prefix_range <@ '{$_GET['term_code']}'");
            $table_name = 'cdr_report_detail';
        }
        

        if (isset($_GET['group_select']) && !empty($_GET['group_select']))
        {
            $group_select_arr = $_GET['group_select'];
            foreach ($group_select_arr as $group_select)
            {
                if (!empty($group_select) && !in_array($group_select, $group_arr))
                {
                    array_push($group_arr, $group_select);
                    if ($group_select == 'ingress_client_id')
                        array_push($field_arr, "(SELECT name FROM client WHERE client_id = ingress_client_id) AS ingress_client_id");
                    elseif ($group_select == 'egress_client_id')
                        array_push($field_arr, "(SELECT name FROM client WHERE client_id = egress_client_id) AS egress_client_id");
                    elseif ($group_select == 'ingress_id')
                        array_push($field_arr, "(SELECT alias FROM resource WHERE resource_id = ingress_id) AS ingress_id");
                    elseif ($group_select == 'egress_id')
                        array_push($field_arr, "(SELECT alias FROM resource WHERE resource_id = egress_id) AS egress_id");
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
        if (count($field_arr))
        {
            $fields = implode(',', $field_arr) . ",";
        }
        if (count($group_arr))
        {
            $from_detail = array('ingress_country', 'ingress_code_name', 'ingress_code', 'ingress_rate',
                'egress_country', 'egress_code_name', 'egress_code');
            $compare_detail = array_intersect($group_arr, $from_detail);
            if (count($compare_detail))
                $table_name = 'cdr_report_detail';
            $groups = "GROUP BY " . implode(',', $group_arr);
        }

        if ($order_num > 0)
        {
            $orders = "ORDER BY " . implode(',', range(1, $order_num));
        }
        if (count($where_arr))
        {
            $wheres = " and " . implode(' and ', $where_arr);
        }

        $replace_fields = array(
            'group_time' => 'Group Time',
            'ingress_client_id' => 'Ingress Carrier',
            'ingress_id' => 'Ingress Trunk',
            'ingress_country' => 'ORIG Country',
            'ingress_code_name' => 'ORIG Code Name',
            'ingress_code' => 'ORIG Code',
            'ingress_rate' => 'ORIG Rate',
            'egress_client_id' => 'Engress Carrier',
            'egress_id' => 'Egress Trunk',
            'egress_country' => 'TERM Country',
            'egress_code_name' => 'TERM Code Name',
            'egress_code' => 'TERM Code'
        );

        $ingress_clients = $this->Cdrs->get_ingress_clients();
        $egress_clients = $this->Cdrs->get_egress_clients();
        $switch_ips = $this->Cdrs->get_switch_ip();
        $ingress_trunks = $this->Cdrs->get_ingress_trunks();
        $egress_trunks = $this->Cdrs->get_egress_trunks();



        $report_max_time = $this->Cdr->get_report_maxtime($start_date, $end_date);


        $select_time_end = strtotime($end_date);

        $is_from_client_cdr = false;

        if (empty($report_max_time))
        {
            $is_from_client_cdr = true;
            $report_max_time = $start_date;
        }
        $system_max_end = strtotime($report_max_time);
        
        $sql = "SELECT is_preload FROM system_parameter LIMIT 1";
        $is_preload_result = $this->Cdrs->query($sql);
        $is_preload = $is_preload_result[0][0]['is_preload'];
        $this->set('show_nodata', true);
        session_write_close();
        if(isset($_GET['show_type']) || $is_preload)
        {

            if ($select_time_end > $system_max_end)
            {
                if ($is_from_client_cdr)
                {
                    $sql = $this->Cdrs->get_inout_from_client_cdr($report_max_time, $end_date);
                    $data = $this->Cdrs->query($sql);
                } else
                {
                    $sql1 = $this->Cdrs->get_inout_cdrs($start_date, $report_max_time, $fields, $groups, $orders, $wheres, $table_name);
                    $sql2 = $this->Cdrs->get_inout_from_client_cdr($report_max_time, $end_date);
                    $data = $this->Cdrs->get_inout_from_two($sql1, $sql2, $orders, $show_fields);
                }
            } else
            {
                $sql = $this->Cdrs->get_inout_cdrs($start_date, $end_date, $fields, $groups, $orders, $wheres, $table_name);
                $data = $this->Cdrs->query($sql);
            }
        
        }
        else
        {
            $data = array();
            $this->set('show_nodata', false);
        }



        $this->set('ingress_clients', $ingress_clients);
        $this->set('egress_clients', $egress_clients);
        //$this->set('switch_ips', $switch_ips);
        $this->set('ingress_trunks', $ingress_trunks);
        $this->set('egress_trunks', $egress_trunks);
        $this->set('show_fields', $show_fields);
        $this->set('replace_fields', $replace_fields);
        $this->set('start_date', $start_date);
        $this->set('end_date', $end_date);
        $this->set('data', $data);
        $rate_tables=$this->Cdrs->get_rate_tables();
        $routing_plans=$this->Cdrs->get_routing_plans();
        $this->set('rate_tables', $rate_tables);
        $this->set('routing_plans', $routing_plans);

        if (isset($_GET['show_type']) && $_GET['show_type'] == '1')
        {
            header("Pragma: public");
            header("Expires: 0");
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("Content-Type: text/csv");
            header("Content-Type: application/octet-stream");
            header("Content-Type: application/download");
            ;
            header("Content-Disposition: attachment;filename=inbound_outbound_report_{$_GET['start_date']}_{$_GET['stop_date']}.csv");
            header("Content-Transfer-Encoding: binary ");
            Configure::write('debug', 0);
            $this->autoLayout = FALSE;
            $this->render('inout_report_down_csv');
        } else if (isset($_GET['show_type']) && $_GET['show_type'] == '2')
        {
            header("Pragma: public");
            header("Expires: 0");
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("Content-Type: application/force-download");
            header("Content-Type: application/octet-stream");
            header("Content-Type: application/download");
            ;
            header("Content-Disposition: attachment;filename=inbound_outbound_report_{$_GET['start_date']}_{$_GET['stop_date']}.xls");
            header("Content-Transfer-Encoding: binary ");
            Configure::write('debug', 0);
            $this->autoLayout = FALSE;
            $this->render('inout_report_down_xls');
        }
    }

    public function usagereport($type = 1)
    {
        $this->pageTitle = "Statistics/Usage Report";

        $table_name = 'cdr_report';

        extract($this->Cdr->get_start_end_time());

        $start_date = $start_date;
        $end_date = $end_date;

        $gmt = $tz;
        $field_arr = array();
        $group_arr = array();
        $show_fields = array();
        $order_num = 0;
        $where_arr = array();
        $group_select_arr = array();

        if (isset($_GET['start_date']) && !empty($_GET['start_date']))
            $start_date = $_GET['start_date'] . ' ' . $_GET['start_time'];
        if (isset($_GET['stop_date']) && !empty($_GET['stop_date']))
            $end_date = $_GET['stop_date'] . ' ' . $_GET['stop_time'];
        if (isset($_GET['query']['tz']) && !empty($_GET['query']['tz']))
            $gmt = $_GET['query']['tz'];

        $start_date .= ' ' . $gmt;
        $end_date .= ' ' . $gmt;


        if (isset($_GET['group_by_date']) && !empty($_GET['group_by_date']))
        {
            array_push($field_arr, "to_char(report_time, '{$_GET['group_by_date']}') as group_time");
            $show_fields['group_time'] = "group_time";
            array_push($group_arr, "to_char(report_time, '{$_GET['group_by_date']}')");
            $order_num++;
        }
        
        if (!empty($_GET['ingress_id']))
        {
            $res = $this->Cdr->findTechPerfix($_GET['ingress_id']);
            $this->set('tech_perfix', $res);
            
            $ingress_options = $this->Cdrs->get_ingress_options($_GET['ingress_id']);
            
            $this->set('ingress_options', $ingress_options);
        }


        if (isset($_GET['route_prefix']) && $_GET['route_prefix'] != 'all')
        {
            if ($_GET['route_prefix'] == '')
            {
                array_push($where_arr, "(ingress_prefix = '\"\"' or ingress_prefix='' or ingress_prefix is null)");
            } else
            {
                array_push($where_arr, "ingress_prefix = '{$_GET['route_prefix']}'");
            }
        }
        
        if (isset($_GET['ingress_rate_table']) && $_GET['ingress_rate_table'] != 'all')
        {
            
            array_push($where_arr, "ingress_rate_table_id = {$_GET['ingress_rate_table']}");
        }
        
        if (isset($_GET['ingress_routing_plan']) && $_GET['ingress_routing_plan'] != 'all')
        {
            
            array_push($where_arr, "route_plan_id = {$_GET['ingress_routing_plan']}");
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

        

        if (isset($_GET['ingress_client_id']) && !empty($_GET['ingress_client_id']))
            array_push($where_arr, "ingress_client_id = {$_GET['ingress_client_id']}");
        if (isset($_GET['ingress_id']) && !empty($_GET['ingress_id']))
            array_push($where_arr, "ingress_id = {$_GET['ingress_id']}");
        if (isset($_GET['orig_country']) && !empty($_GET['orig_country']))
            array_push($where_arr, "ingress_country = '{$_GET['orig_country']}'");
        if (isset($_GET['orig_code_name']) && !empty($_GET['orig_code_name']))
            array_push($where_arr, "ingress_code_name = '{$_GET['orig_code_name']}'");
        if (isset($_GET['orig_code']) && !empty($_GET['orig_code']))
            array_push($where_arr, "ingress_code::prefix_range <@  '{$_GET['orig_code']}'");
        if (isset($_GET['egress_client_id']) && !empty($_GET['egress_client_id']))
            array_push($where_arr, "egress_client_id = {$_GET['egress_client_id']}");
        if (isset($_GET['egress_id']) && !empty($_GET['egress_id']))
            array_push($where_arr, "egress_id = {$_GET['egress_id']}");
        if (isset($_GET['term_country']) && !empty($_GET['term_country']))
            array_push($where_arr, "egress_country = '{$_GET['term_country']}'");
        if (isset($_GET['term_code_name']) && !empty($_GET['term_code_name']))
            array_push($where_arr, "egress_code_name = '{$_GET['term_code_name']}'");
        if (isset($_GET['term_code']) && !empty($_GET['term_code']))
            array_push($where_arr, "egress_code::prefix_range <@ '{$_GET['term_code']}'");

        if (isset($_GET['group_select']) && !empty($_GET['group_select']))
        {
            $group_select_arr = $_GET['group_select'];
            foreach ($group_select_arr as $group_select)
            {
                if (!empty($group_select) && !in_array($group_select, $group_arr))
                {
                    array_push($group_arr, $group_select);
                    if ($group_select == 'ingress_client_id')
                        array_push($field_arr, "(SELECT name FROM client WHERE client_id = ingress_client_id) AS ingress_client_id");
                    elseif ($group_select == 'egress_client_id')
                        array_push($field_arr, "(SELECT name FROM client WHERE client_id = egress_client_id) AS egress_client_id");
                    elseif ($group_select == 'ingress_id')
                        array_push($field_arr, "(SELECT alias FROM resource WHERE resource_id = ingress_id) AS ingress_id");
                    elseif ($group_select == 'egress_id')
                        array_push($field_arr, "(SELECT alias FROM resource WHERE resource_id = egress_id) AS egress_id");
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
        if (count($field_arr))
        {
            $table_name = 'cdr_report_detail';
            $fields = implode(',', $field_arr) . ",";
        }
        if (count($group_arr))
        {
            $table_name = 'cdr_report_detail';
            $groups = "GROUP BY " . implode(',', $group_arr);
        }

        if ($order_num > 0)
        {
            $orders = "ORDER BY " . implode(',', range(1, $order_num));
        }


        if (count($where_arr))
        {
            $table_name = 'cdr_report_detail';
            $wheres = " and " . implode(' and ', $where_arr);
        }


        $replace_fields = array(
            'group_time' => 'Group Time',
            'ingress_client_id' => 'Ingress Carrier',
            'ingress_id' => 'Ingress Trunk',
            'ingress_country' => 'ORIG Country',
            'ingress_code_name' => 'ORIG Code Name',
            'ingress_code' => 'ORIG Code',
            'egress_client_id' => 'Engress Carrier',
            'egress_id' => 'Egress Trunk',
            'egress_country' => 'TERM Country',
            'egress_code_name' => 'TERM Code Name',
            'egress_code' => 'TERM Code'
        );

        $report_max_time = $this->Cdr->get_report_maxtime($start_date, $end_date);


        $select_time_end = strtotime($end_date);

        $is_from_client_cdr = false;

        if (empty($report_max_time))
        {
            $is_from_client_cdr = true;
            $report_max_time = $start_date;
        }
        $system_max_end = strtotime($report_max_time);
        
        
        $sql = "SELECT is_preload FROM system_parameter LIMIT 1";
        $is_preload_result = $this->Cdrs->query($sql);
        $is_preload = $is_preload_result[0][0]['is_preload'];
        $this->set('show_nodata', true);
        session_write_close();
        if(isset($_GET['show_type']) || $is_preload)
        {

            if ($select_time_end > $system_max_end)
            {
                if ($is_from_client_cdr)
                {
                    $data = $this->Cdrs->get_usage_from_client_cdr($report_max_time, $end_date, $type);
                } else
                {
                    $data1 = $this->Cdrs->get_usagereport($start_date, $report_max_time, $fields, $groups, $orders, $wheres, $type, $table_name);
                    $data = $this->Cdrs->get_usage_from_client_cdr($report_max_time, $end_date, $type);



                    foreach ($data1 as $key1 => $item1)
                    {
                        foreach ($item1[0] as $key2 => $item2)
                        {
                            if (!array_key_exists($key2, $replace_fields))
                                @$data[$key1][0][$key2] += $item2;
                            else
                                $data[$key1][0][$key2] = $item2;
                        }
                    }
                }
            } else
            {
                $data = $this->Cdrs->get_usagereport($start_date, $end_date, $fields, $groups, $orders, $wheres, $type, $table_name);
            }
        
            
        } else {
            $data = array();$this->set('show_nodata', false);
        }


        //$data = $this->Cdrs->get_usagereport($start_date, $end_date, $gmt, $fields, $groups, $orders, $wheres, $type, $table_name);

        $ingress_clients = $this->Cdrs->get_ingress_clients();
        $egress_clients = $this->Cdrs->get_egress_clients();
        $switch_ips = $this->Cdrs->get_switch_ip();
        $ingress_trunks = $this->Cdrs->get_ingress_trunks();
        $egress_trunks = $this->Cdrs->get_egress_trunks();
        $this->set('type', $type);
        $this->set('ingress_clients', $ingress_clients);
        $this->set('egress_clients', $egress_clients);
        //$this->set('switch_ips', $switch_ips);
        $this->set('ingress_trunks', $ingress_trunks);
        $this->set('egress_trunks', $egress_trunks);
        $this->set('start_date', $start_date);
        $this->set('end_date', $end_date);
        $this->set('show_fields', $show_fields);
        $this->set('replace_fields', $replace_fields);
        $this->set('data', $data);
        $rate_tables=$this->Cdrs->get_rate_tables();
        $routing_plans=$this->Cdrs->get_routing_plans();
        $this->set('rate_tables', $rate_tables);
        $this->set('routing_plans', $routing_plans);
        if (isset($_GET['show_type']) && $_GET['show_type'] == '1')
        {
            header("Pragma: public");
            header("Expires: 0");
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("Content-Type: text/csv");
            header("Content-Type: application/octet-stream");
            header("Content-Type: application/download");
            ;
            header("Content-Disposition: attachment;filename=org_usage_report_{$_GET['start_date']}_{$_GET['stop_date']}.csv");
            header("Content-Transfer-Encoding: binary ");
            Configure::write('debug', 0);
            $this->autoLayout = FALSE;
            $this->render('org_usage_report_csv');
        } else if (isset($_GET['show_type']) && $_GET['show_type'] == '2')
        {
            header("Pragma: public");
            header("Expires: 0");
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("Content-Type: application/force-download");
            header("Content-Type: application/octet-stream");
            header("Content-Type: application/download");
            ;
            header("Content-Disposition: attachment;filename=term_usage_report_{$_GET['start_date']}_{$_GET['stop_date']}.xls");
            header("Content-Transfer-Encoding: binary ");
            Configure::write('debug', 0);
            $this->autoLayout = FALSE;
            $this->render('term_usage_report_csv');
        }
    }
    
    public function location()
    {
        $this->pageTitle = "Statistics/Location Report";

        extract($this->Cdr->get_start_end_time());

        $table_name = 'cdr_report';

        $start_date = $start_date;
        $end_date = $end_date;

        $gmt = $tz;


        $field_arr = array();
        $group_arr = array();
        $show_fields = array();
        $order_num = 0;
        $where_arr = array();
        $group_select_arr = array();


      

        if (isset($_GET['start_date']) && !empty($_GET['start_date']))
            $start_date = $_GET['start_date'] . ' ' . $_GET['start_time'];
        if (isset($_GET['stop_date']) && !empty($_GET['stop_date']))
            $end_date = $_GET['stop_date'] . ' ' . $_GET['stop_time'];
        if (isset($_GET['query']['tz']) && !empty($_GET['query']['tz']))
            $gmt = $_GET['query']['tz'];

        $start_date .= ' ' . $gmt;
        $end_date .= ' ' . $gmt;

        if (isset($_GET['group_by_date']) && !empty($_GET['group_by_date']))
        {
            array_push($field_arr, "to_char(report_time, '{$_GET['group_by_date']}') as group_time");
            $show_fields['group_time'] = "group_time";
            array_push($group_arr, "to_char(report_time, '{$_GET['group_by_date']}')");
            $order_num++;
        }

       if (!empty($_GET['ingress_id']))
        {
            $res = $this->Cdr->findTechPerfix($_GET['ingress_id']);
            $this->set('tech_perfix', $res);
            
            $ingress_options = $this->Cdrs->get_ingress_options($_GET['ingress_id']);
            
            $this->set('ingress_options', $ingress_options);
        }


        if (isset($_GET['route_prefix']) && $_GET['route_prefix'] != 'all')
        {
            if ($_GET['route_prefix'] == '')
            {
                array_push($where_arr, "(ingress_prefix = '\"\"' or ingress_prefix='' or ingress_prefix is null)");
            } else
            {
                array_push($where_arr, "ingress_prefix = '{$_GET['route_prefix']}'");
            }
        }
        
        if (isset($_GET['ingress_rate_table']) && $_GET['ingress_rate_table'] != 'all')
        {
            
            array_push($where_arr, "ingress_rate_table_id = {$_GET['ingress_rate_table']}");
        }
        
        if (isset($_GET['ingress_routing_plan']) && $_GET['ingress_routing_plan'] != 'all')
        {
            
            array_push($where_arr, "route_plan_id = {$_GET['ingress_routing_plan']}");
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

        if (isset($_GET['ingress_client_id']) && !empty($_GET['ingress_client_id']))
            array_push($where_arr, "ingress_client_id = {$_GET['ingress_client_id']}");
        if (isset($_GET['ingress_id']) && !empty($_GET['ingress_id']))
            array_push($where_arr, "ingress_id = {$_GET['ingress_id']}");
        if (isset($_GET['orig_country']) && !empty($_GET['orig_country']))
        {
            array_push($where_arr, "ingress_country = '{$_GET['orig_country']}'");
            $table_name = 'cdr_report_detail';
        }
        if (isset($_GET['orig_code_name']) && !empty($_GET['orig_code_name']))
        {
            array_push($where_arr, "ingress_code_name = '{$_GET['orig_code_name']}'");
            $table_name = 'cdr_report_detail';
        }
        if (isset($_GET['orig_code']) && !empty($_GET['orig_code']))
        {
            array_push($where_arr, "ingress_code::prefix_range <@  '{$_GET['orig_code']}'");
            $table_name = 'cdr_report_detail';
        }
        if (isset($_GET['egress_client_id']) && !empty($_GET['egress_client_id']))
            array_push($where_arr, "egress_client_id = {$_GET['egress_client_id']}");
        if (isset($_GET['egress_id']) && !empty($_GET['egress_id']))
            array_push($where_arr, "egress_id = {$_GET['egress_id']}");
        if (isset($_GET['term_country']) && !empty($_GET['term_country']))
        {
            array_push($where_arr, "egress_country = '{$_GET['term_country']}'");
            $table_name = 'cdr_report_detail';
        }
        if (isset($_GET['term_code_name']) && !empty($_GET['term_code_name']))
        {
            array_push($where_arr, "egress_code_name = '{$_GET['term_code_name']}'");
            $table_name = 'cdr_report_detail';
        }
        if (isset($_GET['term_code']) && !empty($_GET['term_code']))
        {
            array_push($where_arr, "egress_code::prefix_range <@ '{$_GET['term_code']}'");
            $table_name = 'cdr_report_detail';
        }
        
        $_GET['group_select'][0] = 'ingress_country';
        
        if (isset($_GET['group_select']) && !empty($_GET['group_select']))
        {
            $group_select_arr = $_GET['group_select'];
            foreach ($group_select_arr as $group_select)
            {
                if (!empty($group_select) && !in_array($group_select, $group_arr))
                {
                    array_push($group_arr, $group_select);
                    if ($group_select == 'ingress_client_id')
                        array_push($field_arr, "(SELECT name FROM client WHERE client_id = ingress_client_id) AS ingress_client_id");
                    elseif ($group_select == 'egress_client_id')
                        array_push($field_arr, "(SELECT name FROM client WHERE client_id = egress_client_id) AS egress_client_id");
                    elseif ($group_select == 'ingress_id')
                        array_push($field_arr, "(SELECT alias FROM resource WHERE resource_id = ingress_id) AS ingress_id");
                    elseif ($group_select == 'egress_id')
                        array_push($field_arr, "(SELECT alias FROM resource WHERE resource_id = egress_id) AS egress_id");
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
        if (count($field_arr))
        {
            $fields = implode(',', $field_arr) . ",";
        }
        if (count($group_arr))
        {
            $from_detail = array('ingress_country', 'ingress_code_name', 'ingress_code', 'ingress_rate',
                'egress_country', 'egress_code_name', 'egress_code');
            $compare_detail = array_intersect($group_arr, $from_detail);
            if (count($compare_detail))
                $table_name = 'cdr_report_detail';
            $groups = "GROUP BY " . implode(',', $group_arr);
        }

        if ($order_num > 0)
        {
            $orders = "ORDER BY " . implode(',', range(1, $order_num));
        }
        if (count($where_arr))
        {
            $wheres = " and " . implode(' and ', $where_arr);
        }

        $replace_fields = array(
            'group_time' => 'Group Time',
            'ingress_client_id' => 'Ingress Carrier',
            'ingress_id' => 'Ingress Trunk',
            'ingress_country' => 'ORIG Country',
            'ingress_code_name' => 'ORIG Code Name',
            'ingress_code' => 'ORIG Code',
            'ingress_rate' => 'ORIG Rate',
            'egress_client_id' => 'Engress Carrier',
            'egress_id' => 'Egress Trunk',
            'egress_country' => 'TERM Country',
            'egress_code_name' => 'TERM Code Name',
            'egress_code' => 'TERM Code'
        );

        $ingress_clients = $this->Cdrs->get_ingress_clients();
        $egress_clients = $this->Cdrs->get_egress_clients();
        $switch_ips = $this->Cdrs->get_switch_ip();
        $ingress_trunks = $this->Cdrs->get_ingress_trunks();
        $egress_trunks = $this->Cdrs->get_egress_trunks();



        $report_max_time = $this->Cdr->get_report_maxtime($start_date, $end_date);


        $select_time_end = strtotime($end_date);

        $is_from_client_cdr = false;

        if (empty($report_max_time))
        {
            $is_from_client_cdr = true;
            $report_max_time = $start_date;
        }
        $system_max_end = strtotime($report_max_time);
        
        
        $sql = "SELECT is_preload FROM system_parameter LIMIT 1";
        $is_preload_result = $this->Cdrs->query($sql);
        $is_preload = $is_preload_result[0][0]['is_preload'];
        $this->set('show_nodata', true);
        session_write_close();
        if(isset($_GET['show_type']) || $is_preload)
        {

            if ($select_time_end > $system_max_end)
            {
                if ($is_from_client_cdr)
                {
                    $sql = $this->Cdrs->get_location_from_client_cdr($report_max_time, $end_date);
                    $data = $this->Cdrs->query($sql);
                } else
                {
                    $sql1 = $this->Cdrs->get_location($start_date, $report_max_time, $fields, $groups, $orders, $wheres, $table_name);
                    $sql2 = $this->Cdrs->get_location_from_client_cdr($report_max_time, $end_date);
                    $data = $this->Cdrs->get_location_from_two($sql1, $sql2, $orders, $show_fields);
                }
            } else
            {
                $sql = $this->Cdrs->get_inout_cdrs($start_date, $end_date, $fields, $groups, $orders, $wheres, $table_name);
                $data = $this->Cdrs->query($sql);
            }
        
            
        }
        else
        {
            $data = array();$this->set('show_nodata', false);
        }



        $this->set('ingress_clients', $ingress_clients);
        $this->set('egress_clients', $egress_clients);
        //$this->set('switch_ips', $switch_ips);
        $this->set('ingress_trunks', $ingress_trunks);
        $this->set('egress_trunks', $egress_trunks);
        $this->set('show_fields', $show_fields);
        $this->set('replace_fields', $replace_fields);
        $this->set('start_date', $start_date);
        $this->set('end_date', $end_date);
        $this->set('data', $data);
        $rate_tables=$this->Cdrs->get_rate_tables();
        $routing_plans=$this->Cdrs->get_routing_plans();
        $this->set('rate_tables', $rate_tables);
        $this->set('routing_plans', $routing_plans);

        if (isset($_GET['show_type']) && $_GET['show_type'] == '1')
        {
            header("Pragma: public");
            header("Expires: 0");
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("Content-Type: text/csv");
            header("Content-Type: application/octet-stream");
            header("Content-Type: application/download");
            ;
            header("Content-Disposition: attachment;filename=location_report_{$_GET['start_date']}_{$_GET['stop_date']}.csv");
            header("Content-Transfer-Encoding: binary ");
            Configure::write('debug', 0);
            $this->autoLayout = FALSE;
            $this->render('location_down_csv');
        } else if (isset($_GET['show_type']) && $_GET['show_type'] == '2')
        {
            header("Pragma: public");
            header("Expires: 0");
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("Content-Type: application/force-download");
            header("Content-Type: application/octet-stream");
            header("Content-Type: application/download");
            ;
            header("Content-Disposition: attachment;filename=location_report_{$_GET['start_date']}_{$_GET['stop_date']}.xls");
            header("Content-Transfer-Encoding: binary ");
            Configure::write('debug', 0);
            $this->autoLayout = FALSE;
            $this->render('location_down_xls');
        }
    }

    public function profit($type = 1)
    {
        $this->pageTitle = "Statistics/Profitability Analysis";

        $table_name = 'cdr_report';

        extract($this->Cdr->get_start_end_time());

        $start_date = $start_date;
        $end_date = $end_date;

        $gmt = $tz;

        $field_arr = array();
        $group_arr = array();
        $show_fields = array();
        $order_num = 0;
        $where_arr = array();
        $group_select_arr = array();

        if (isset($_GET['start_date']) && !empty($_GET['start_date']))
            $start_date = $_GET['start_date'] . ' ' . $_GET['start_time'];
        if (isset($_GET['stop_date']) && !empty($_GET['stop_date']))
            $end_date = $_GET['stop_date'] . ' ' . $_GET['stop_time'];
        if (isset($_GET['query']['tz']) && !empty($_GET['query']['tz']))
            $gmt = $_GET['query']['tz'];
        $start_date .= ' ' . $gmt;
        $end_date .= ' ' . $gmt;

        if (isset($_GET['group_by_date']) && !empty($_GET['group_by_date']))
        {
            array_push($field_arr, "to_char(report_time, '{$_GET['group_by_date']}') as group_time");
            $show_fields['group_time'] = "group_time";
            array_push($group_arr, "to_char(report_time, '{$_GET['group_by_date']}')");
            $order_num++;
        }

       if (!empty($_GET['ingress_id']))
        {
            $res = $this->Cdr->findTechPerfix($_GET['ingress_id']);
            $this->set('tech_perfix', $res);
            
            $ingress_options = $this->Cdrs->get_ingress_options($_GET['ingress_id']);
            
            $this->set('ingress_options', $ingress_options);
        }


        if (isset($_GET['route_prefix']) && $_GET['route_prefix'] != 'all')
        {
            if ($_GET['route_prefix'] == '')
            {
                array_push($where_arr, "(ingress_prefix = '\"\"' or ingress_prefix='' or ingress_prefix is null)");
            } else
            {
                array_push($where_arr, "ingress_prefix = '{$_GET['route_prefix']}'");
            }
        }
        
        
        
        if (isset($_GET['ingress_rate_table']) && $_GET['ingress_rate_table'] != 'all')
        {
            
            array_push($where_arr, "ingress_rate_table_id = {$_GET['ingress_rate_table']}");
        }
        
        if (isset($_GET['ingress_routing_plan']) && $_GET['ingress_routing_plan'] != 'all')
        {
            
            array_push($where_arr, "route_plan_id = {$_GET['ingress_routing_plan']}");
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

        if (isset($_GET['ingress_client_id']) && !empty($_GET['ingress_client_id']))
            array_push($where_arr, "ingress_client_id = {$_GET['ingress_client_id']}");
        if (isset($_GET['ingress_id']) && !empty($_GET['ingress_id']))
            array_push($where_arr, "ingress_id = {$_GET['ingress_id']}");
        if (isset($_GET['orig_country']) && !empty($_GET['orig_country']))
        {
            array_push($where_arr, "ingress_country = '{$_GET['orig_country']}'");
            $table_name = 'cdr_report_detail';
        }
        if (isset($_GET['orig_code_name']) && !empty($_GET['orig_code_name']))
        {
            array_push($where_arr, "ingress_code_name = '{$_GET['orig_code_name']}'");
            $table_name = 'cdr_report_detail';
        }
        if (isset($_GET['orig_code']) && !empty($_GET['orig_code']))
        {
            array_push($where_arr, "ingress_code::prefix_range <@  '{$_GET['orig_code']}'");
            $table_name = 'cdr_report_detail';
        }
        if (isset($_GET['egress_client_id']) && !empty($_GET['egress_client_id']))
            array_push($where_arr, "egress_client_id = {$_GET['egress_client_id']}");
        if (isset($_GET['egress_id']) && !empty($_GET['egress_id']))
            array_push($where_arr, "egress_id = {$_GET['egress_id']}");
        if (isset($_GET['term_country']) && !empty($_GET['term_country']))
        {
            array_push($where_arr, "egress_country = '{$_GET['term_country']}'");
            $table_name = 'cdr_report_detail';
        }
        if (isset($_GET['term_code_name']) && !empty($_GET['term_code_name']))
        {
            array_push($where_arr, "egress_code_name = '{$_GET['term_code_name']}'");
            $table_name = 'cdr_report_detail';
        }
        if (isset($_GET['term_code']) && !empty($_GET['term_code']))
        {
            array_push($where_arr, "egress_code::prefix_range <@ '{$_GET['term_code']}'");
            $table_name = 'cdr_report_detail';
        }

        if ($type == 1)
            $_GET['group_select'][0] = 'ingress_id';
        else
            $_GET['group_select'][0] = 'egress_id';

        if (isset($_GET['group_select']) && !empty($_GET['group_select']))
        {
            $group_select_arr = $_GET['group_select'];
            foreach ($group_select_arr as $group_select)
            {
                if (!empty($group_select) && !in_array($group_select, $group_arr))
                {
                    array_push($group_arr, $group_select);
                    if ($group_select == 'ingress_client_id')
                        array_push($field_arr, "(SELECT name FROM client WHERE client_id = ingress_client_id) AS ingress_client_id");
                    elseif ($group_select == 'egress_client_id')
                        array_push($field_arr, "(SELECT name FROM client WHERE client_id = egress_client_id) AS egress_client_id");
                    elseif ($group_select == 'ingress_id')
                        array_push($field_arr, "(SELECT alias FROM resource WHERE resource_id = ingress_id) AS ingress_id");
                    elseif ($group_select == 'egress_id')
                        array_push($field_arr, "(SELECT alias FROM resource WHERE resource_id = egress_id) AS egress_id");
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
        if (count($field_arr))
        {
            $fields = implode(',', $field_arr) . ",";
        }
        if (count($group_arr))
        {
            $from_detail = array('ingress_country', 'ingress_code_name', 'ingress_code', 'ingress_rate',
                'egress_country', 'egress_code_name', 'egress_code');
            $compare_detail = array_intersect($group_arr, $from_detail);
            if (count($compare_detail))
                $table_name = 'cdr_report_detail';
            $groups = "GROUP BY " . implode(',', $group_arr);
        }

        if ($order_num > 0)
        {
            $orders = "ORDER BY " . implode(',', range(1, $order_num));
        }


        if (count($where_arr))
        {
            $wheres = " and " . implode(' and ', $where_arr);
        }

        $replace_fields = array(
            'group_time' => 'Group Time',
            'ingress_client_id' => 'Ingress Carrier',
            'ingress_id' => 'Ingress Trunk',
            'ingress_country' => 'ORIG Country',
            'ingress_code_name' => 'ORIG Code Name',
            'ingress_code' => 'ORIG Code',
            'ingress_rate' => 'ORIG Rate',
            'egress_client_id' => 'Engress Carrier',
            'egress_id' => 'Egress Trunk',
            'egress_country' => 'TERM Country',
            'egress_code_name' => 'TERM Code Name',
            'egress_code' => 'TERM Code'
        );

        $ingress_clients = $this->Cdrs->get_ingress_clients();
        $egress_clients = $this->Cdrs->get_egress_clients();
        $switch_ips = $this->Cdrs->get_switch_ip();
        $ingress_trunks = $this->Cdrs->get_ingress_trunks();
        $egress_trunks = $this->Cdrs->get_egress_trunks();

        $report_max_time = $this->Cdr->get_report_maxtime($start_date, $end_date);


        $select_time_end = strtotime($end_date);

        $is_from_client_cdr = false;

        if (empty($report_max_time))
        {
            $is_from_client_cdr = true;
            $report_max_time = $start_date;
        }
        $system_max_end = strtotime($report_max_time);
        
        $sql = "SELECT is_preload FROM system_parameter LIMIT 1";
        $is_preload_result = $this->Cdrs->query($sql);
        $is_preload = $is_preload_result[0][0]['is_preload'];
        $this->set('show_nodata', true);
        session_write_close();
        if(isset($_GET['show_type']) || $is_preload)
        {

            if ($select_time_end > $system_max_end)
            {
                if ($is_from_client_cdr)
                {
                    $sql = $this->Cdrs->get_profit_from_client_cdr($report_max_time, $end_date, $type);
                    $data = $this->Cdrs->query($sql);
                } else
                {
                    $sql1 = $this->Cdrs->get_profit_cdrs($start_date, $report_max_time, $fields, $groups, $orders, $wheres, $type, $table_name);
                    $sql2 = $this->Cdrs->get_profit_from_client_cdr($report_max_time, $end_date, $type);
                    $data = $this->Cdrs->get_profit_from_two($sql1, $sql2, $type, $orders, $show_fields);
                }
            } else
            {
                $sql = $this->Cdrs->get_profit_cdrs($start_date, $end_date, $fields, $groups, $orders, $wheres, $type, $table_name);
                $data = $this->Cdrs->query($sql);
            }
            
        }
        else
        {
            $data = array();$this->set('show_nodata', false);
        }

        //var_dump($data);

        $this->set('ingress_clients', $ingress_clients);
        $this->set('egress_clients', $egress_clients);
        //$this->set('switch_ips', $switch_ips);
        $this->set('ingress_trunks', $ingress_trunks);
        $this->set('egress_trunks', $egress_trunks);
        $this->set('show_fields', $show_fields);
        $this->set('replace_fields', $replace_fields);
        $this->set('start_date', $start_date);
        $this->set('end_date', $end_date);
        $this->set('type', $type);
        $this->set('data', $data);
        $rate_tables=$this->Cdrs->get_rate_tables();
        $routing_plans=$this->Cdrs->get_routing_plans();
        $this->set('rate_tables', $rate_tables);
        $this->set('routing_plans', $routing_plans);

        if (isset($_GET['show_type']) && $_GET['show_type'] == '1')
        {
            header("Pragma: public");
            header("Expires: 0");
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("Content-Type: text/csv");
            header("Content-Type: application/octet-stream");
            header("Content-Type: application/download");
            ;
            header("Content-Disposition: attachment;filename=profit_report_{$_GET['start_date']}_{$_GET['stop_date']}.csv");
            header("Content-Transfer-Encoding: binary ");
            Configure::write('debug', 0);
            $this->autoLayout = FALSE;
            $this->render('profit_down_csv');
        } else if (isset($_GET['show_type']) && $_GET['show_type'] == '2')
        {
            header("Pragma: public");
            header("Expires: 0");
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("Content-Type: application/force-download");
            header("Content-Type: application/octet-stream");
            header("Content-Type: application/download");
            ;
            header("Content-Disposition: attachment;filename=profit_report_{$_GET['start_date']}_{$_GET['stop_date']}.xls");
            header("Content-Transfer-Encoding: binary ");
            Configure::write('debug', 0);
            $this->autoLayout = FALSE;
            $this->render('profit_down_xls');
        }
    }
    
    
    public function bandwidth()
    {
        $this->pageTitle = "Statistics/Bandwidth Report";

        $table_name = 'cdr_report';

        extract($this->Cdr->get_start_end_time());

        $start_date = $start_date;
        $end_date = $end_date;

        $gmt = $tz;

        $field_arr = array();
        $group_arr = array();
        $show_fields = array();
        $order_num = 0;
        $where_arr = array();
        $group_select_arr = array();

        if (isset($_GET['start_date']) && !empty($_GET['start_date']))
            $start_date = $_GET['start_date'] . ' ' . $_GET['start_time'];
        if (isset($_GET['stop_date']) && !empty($_GET['stop_date']))
            $end_date = $_GET['stop_date'] . ' ' . $_GET['stop_time'];
        if (isset($_GET['query']['tz']) && !empty($_GET['query']['tz']))
            $gmt = $_GET['query']['tz'];
        $start_date .= ' ' . $gmt;
        $end_date .= ' ' . $gmt;

        if (isset($_GET['group_by_date']) && !empty($_GET['group_by_date']))
        {
            array_push($field_arr, "to_char(report_time, '{$_GET['group_by_date']}') as group_time");
            $show_fields['group_time'] = "group_time";
            array_push($group_arr, "to_char(report_time, '{$_GET['group_by_date']}')");
            $order_num++;
        }

        if (!empty($_GET['ingress_id']))
        {
            $res = $this->Cdr->findTechPerfix($_GET['ingress_id']);
            $this->set('tech_perfix', $res);
            
            $ingress_options = $this->Cdrs->get_ingress_options($_GET['ingress_id']);
            
            $this->set('ingress_options', $ingress_options);
        }


        if (isset($_GET['route_prefix']) && $_GET['route_prefix'] != 'all')
        {
            if ($_GET['route_prefix'] == '')
            {
                array_push($where_arr, "(ingress_prefix = '\"\"' or ingress_prefix='' or ingress_prefix is null)");
            } else
            {
                array_push($where_arr, "ingress_prefix = '{$_GET['route_prefix']}'");
            }
        }
        
        if (isset($_GET['ingress_rate_table']) && $_GET['ingress_rate_table'] != 'all')
        {
            
            array_push($where_arr, "ingress_rate_table_id = {$_GET['ingress_rate_table']}");
        }
        
        if (isset($_GET['ingress_routing_plan']) && $_GET['ingress_routing_plan'] != 'all')
        {
            
            array_push($where_arr, "route_plan_id = {$_GET['ingress_routing_plan']}");
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
        
        

        if (isset($_GET['ingress_client_id']) && !empty($_GET['ingress_client_id']))
            array_push($where_arr, "ingress_client_id = {$_GET['ingress_client_id']}");
        if (isset($_GET['ingress_id']) && !empty($_GET['ingress_id']))
            array_push($where_arr, "ingress_id = {$_GET['ingress_id']}");
        if (isset($_GET['orig_country']) && !empty($_GET['orig_country']))
        {
            array_push($where_arr, "ingress_country = '{$_GET['orig_country']}'");
            $table_name = 'cdr_report_detail';
        }
        if (isset($_GET['orig_code_name']) && !empty($_GET['orig_code_name']))
        {
            array_push($where_arr, "ingress_code_name = '{$_GET['orig_code_name']}'");
            $table_name = 'cdr_report_detail';
        }
        if (isset($_GET['orig_code']) && !empty($_GET['orig_code']))
        {
            array_push($where_arr, "ingress_code::prefix_range <@  '{$_GET['orig_code']}'");
            $table_name = 'cdr_report_detail';
        }
        if (isset($_GET['egress_client_id']) && !empty($_GET['egress_client_id']))
            array_push($where_arr, "egress_client_id = {$_GET['egress_client_id']}");
        if (isset($_GET['egress_id']) && !empty($_GET['egress_id']))
            array_push($where_arr, "egress_id = {$_GET['egress_id']}");
        if (isset($_GET['term_country']) && !empty($_GET['term_country']))
        {
            array_push($where_arr, "egress_country = '{$_GET['term_country']}'");
            $table_name = 'cdr_report_detail';
        }
        if (isset($_GET['term_code_name']) && !empty($_GET['term_code_name']))
        {
            array_push($where_arr, "egress_code_name = '{$_GET['term_code_name']}'");
            $table_name = 'cdr_report_detail';
        }
        if (isset($_GET['term_code']) && !empty($_GET['term_code']))
        {
            array_push($where_arr, "egress_code::prefix_range <@ '{$_GET['term_code']}'");
            $table_name = 'cdr_report_detail';
        }

        if (isset($_GET['group_select']) && !empty($_GET['group_select']))
        {
            $group_select_arr = $_GET['group_select'];
            foreach ($group_select_arr as $group_select)
            {
                if (!empty($group_select) && !in_array($group_select, $group_arr))
                {
                    array_push($group_arr, $group_select);
                    if ($group_select == 'ingress_client_id')
                        array_push($field_arr, "(SELECT name FROM client WHERE client_id = ingress_client_id) AS ingress_client_id");
                    elseif ($group_select == 'egress_client_id')
                        array_push($field_arr, "(SELECT name FROM client WHERE client_id = egress_client_id) AS egress_client_id");
                    elseif ($group_select == 'ingress_id')
                        array_push($field_arr, "(SELECT alias FROM resource WHERE resource_id = ingress_id) AS ingress_id");
                    elseif ($group_select == 'egress_id')
                        array_push($field_arr, "(SELECT alias FROM resource WHERE resource_id = egress_id) AS egress_id");
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
        if (count($field_arr))
        {
            $fields = implode(',', $field_arr) . ",";
        }
        if (count($group_arr))
        {
            $from_detail = array('ingress_country', 'ingress_code_name', 'ingress_code', 'ingress_rate',
                'egress_country', 'egress_code_name', 'egress_code');
            $compare_detail = array_intersect($group_arr, $from_detail);
            if (count($compare_detail))
                $table_name = 'cdr_report_detail';
            $groups = "GROUP BY " . implode(',', $group_arr);
        }

        if ($order_num > 0)
        {
            $orders = "ORDER BY " . implode(',', range(1, $order_num));
        }


        if (count($where_arr))
        {
            $wheres = " and " . implode(' and ', $where_arr);
        }

        $replace_fields = array(
            'group_time' => 'Group Time',
            'ingress_client_id' => 'Ingress Carrier',
            'ingress_id' => 'Ingress Trunk',
            'ingress_country' => 'ORIG Country',
            'ingress_code_name' => 'ORIG Code Name',
            'ingress_code' => 'ORIG Code',
            'ingress_rate' => 'ORIG Rate',
            'egress_client_id' => 'Engress Carrier',
            'egress_id' => 'Egress Trunk',
            'egress_country' => 'TERM Country',
            'egress_code_name' => 'TERM Code Name',
            'egress_code' => 'TERM Code'
        );

        $ingress_clients = $this->Cdrs->get_ingress_clients();
        $egress_clients = $this->Cdrs->get_egress_clients();
        $switch_ips = $this->Cdrs->get_switch_ip();
        $ingress_trunks = $this->Cdrs->get_ingress_trunks();
        $egress_trunks = $this->Cdrs->get_egress_trunks();

        $report_max_time = $this->Cdr->get_report_maxtime($start_date, $end_date);


        $select_time_end = strtotime($end_date);

        $is_from_client_cdr = false;

        if (empty($report_max_time))
        {
            $is_from_client_cdr = true;
            $report_max_time = $start_date;
        }
        $system_max_end = strtotime($report_max_time);
        
        
        $sql = "SELECT is_preload FROM system_parameter LIMIT 1";
        $is_preload_result = $this->Cdrs->query($sql);
        $is_preload = $is_preload_result[0][0]['is_preload'];
        $this->set('show_nodata', true);
        session_write_close();
        if(isset($_GET['show_type']) || $is_preload)
        {

            if ($select_time_end > $system_max_end)
            {
                if ($is_from_client_cdr)
                {
                    $sql = $this->Cdrs->get_bandwidth_from_cdr($report_max_time, $end_date);
                    $data = $this->Cdrs->query($sql);
                } else
                {
                    $sql1 = $this->Cdrs->get_bandwidth($start_date, $report_max_time, $fields, $groups, $orders, $wheres, $table_name);
                    $sql2 = $this->Cdrs->get_bandwidth_from_cdr($report_max_time, $end_date);
                    $data = $this->Cdrs->get_bandwidth_two($sql1, $sql2, $orders, $show_fields);
                }
            } else
            {
                $sql = $this->Cdrs->get_bandwidth($start_date, $end_date, $fields, $groups, $orders, $wheres, $table_name);
                $data = $this->Cdrs->query($sql);
            }
        
        } else 
        {
            $data = array();
            $this->set('show_nodata', false);
        }

        //var_dump($data);

        $this->set('ingress_clients', $ingress_clients);
        $this->set('egress_clients', $egress_clients);
        //$this->set('switch_ips', $switch_ips);
        $this->set('ingress_trunks', $ingress_trunks);
        $this->set('egress_trunks', $egress_trunks);
        $this->set('show_fields', $show_fields);
        $this->set('replace_fields', $replace_fields);
        $this->set('start_date', $start_date);
        $this->set('end_date', $end_date);
        $this->set('data', $data);
        $rate_tables=$this->Cdrs->get_rate_tables();
        $routing_plans=$this->Cdrs->get_routing_plans();
        $this->set('rate_tables', $rate_tables);
        $this->set('routing_plans', $routing_plans);

        if (isset($_GET['show_type']) && $_GET['show_type'] == '1')
        {
            header("Pragma: public");
            header("Expires: 0");
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("Content-Type: text/csv");
            header("Content-Type: application/octet-stream");
            header("Content-Type: application/download");
            header("Content-Disposition: attachment;filename=summary_report_{$_GET['start_date']}_{$_GET['stop_date']}.csv");
            header("Content-Transfer-Encoding: binary ");
            Configure::write('debug', 0);
            $this->autoLayout = FALSE;
            $this->render('bandwidth_down_csv');
        } else if (isset($_GET['show_type']) && $_GET['show_type'] == '2')
        {
            header("Pragma: public");
            header("Expires: 0");
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("Content-Type: application/force-download");
            header("Content-Type: application/octet-stream");
            header("Content-Type: application/download");
            header("Content-Disposition: attachment;filename=summary_report_{$_GET['start_date']}_{$_GET['stop_date']}.xls");
            header("Content-Transfer-Encoding: binary ");
            Configure::write('debug', 0);
            $this->autoLayout = FALSE;
            $this->render('bandwidth_down_xls');
        }
    }
    
    public function did($type = 1)
    {
        $this->pageTitle = "Statistics/DID Report";

        extract($this->Cdr->get_start_end_time());

        $start_date = $start_date;
        $end_date = $end_date;

        $gmt = $tz;

        $field_arr = array();
        $group_arr = array();
        $show_fields = array();
        $order_num = 0;
        $where_arr = array();
        $group_select_arr = array();

        if (isset($_GET['start_date']) && !empty($_GET['start_date']))
            $start_date = $_GET['start_date'] . ' ' . $_GET['start_time'];
        if (isset($_GET['stop_date']) && !empty($_GET['stop_date']))
            $end_date = $_GET['stop_date'] . ' ' . $_GET['stop_time'];
        if (isset($_GET['query']['tz']) && !empty($_GET['query']['tz']))
            $gmt = $_GET['query']['tz'];
        $start_date .= ' ' . $gmt;
        $end_date .= ' ' . $gmt;

        if (isset($_GET['group_by_date']) && !empty($_GET['group_by_date']))
        {
            array_push($field_arr, "to_char(report_time, '{$_GET['group_by_date']}') as group_time");
            $show_fields['group_time'] = "group_time";
            array_push($group_arr, "to_char(report_time, '{$_GET['group_by_date']}')");
            $order_num++;
        }

        if (!empty($_GET['ingress_id']))
        {
            $res = $this->Cdr->findTechPerfix($_GET['ingress_id']);
            $this->set('tech_perfix', $res);
            
            $ingress_options = $this->Cdrs->get_ingress_options($_GET['ingress_id']);
            
            $this->set('ingress_options', $ingress_options);
        }


        if (isset($_GET['route_prefix']) && $_GET['route_prefix'] != 'all')
        {
            if ($_GET['route_prefix'] == '')
            {
                array_push($where_arr, "(ingress_prefix = '\"\"' or ingress_prefix='' or ingress_prefix is null)");
            } else
            {
                array_push($where_arr, "ingress_prefix = '{$_GET['route_prefix']}'");
            }
        }
        
        if (isset($_GET['ingress_rate_table']) && $_GET['ingress_rate_table'] != 'all')
        {
            
            array_push($where_arr, "ingress_rate_table_id = {$_GET['ingress_rate_table']}");
        }
        
        if (isset($_GET['ingress_routing_plan']) && $_GET['ingress_routing_plan'] != 'all')
        {
            
            array_push($where_arr, "route_plan_id = {$_GET['ingress_routing_plan']}");
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


        if (isset($_GET['ingress_client_id']) && !empty($_GET['ingress_client_id']))
            array_push($where_arr, "ingress_client_id = {$_GET['ingress_client_id']}");
        if (isset($_GET['ingress_id']) && !empty($_GET['ingress_id']))
            array_push($where_arr, "ingress_id = {$_GET['ingress_id']}");
        if (isset($_GET['orig_country']) && !empty($_GET['orig_country']))
        {
            array_push($where_arr, "ingress_country = '{$_GET['orig_country']}'");
        }
        if (isset($_GET['orig_code_name']) && !empty($_GET['orig_code_name']))
        {
            array_push($where_arr, "ingress_code_name = '{$_GET['orig_code_name']}'");
        }
        if (isset($_GET['orig_code']) && !empty($_GET['orig_code']))
        {
            array_push($where_arr, "ingress_code::prefix_range <@  '{$_GET['orig_code']}'");
        }
        if (isset($_GET['egress_client_id']) && !empty($_GET['egress_client_id']))
            array_push($where_arr, "egress_client_id = {$_GET['egress_client_id']}");
        if (isset($_GET['egress_id']) && !empty($_GET['egress_id']))
            array_push($where_arr, "egress_id = {$_GET['egress_id']}");
        if (isset($_GET['term_country']) && !empty($_GET['term_country']))
        {
            array_push($where_arr, "egress_country = '{$_GET['term_country']}'");
        }
        if (isset($_GET['term_code_name']) && !empty($_GET['term_code_name']))
        {
            array_push($where_arr, "egress_code_name = '{$_GET['term_code_name']}'");
        }
        if (isset($_GET['term_code']) && !empty($_GET['term_code']))
        {
            array_push($where_arr, "egress_code::prefix_range <@ '{$_GET['term_code']}'");
        }

        if (isset($_GET['group_select']) && !empty($_GET['group_select']))
        {
            $group_select_arr = $_GET['group_select'];
            foreach ($group_select_arr as $group_select)
            {
                if (!empty($group_select) && !in_array($group_select, $group_arr))
                {
                    array_push($group_arr, $group_select);
                    if ($group_select == 'ingress_client_id')
                        array_push($field_arr, "(SELECT name FROM client WHERE client_id = ingress_client_id) AS ingress_client_id");
                    elseif ($group_select == 'egress_client_id')
                        array_push($field_arr, "(SELECT name FROM client WHERE client_id = egress_client_id) AS egress_client_id");
                    elseif ($group_select == 'ingress_id')
                        array_push($field_arr, "(SELECT alias FROM resource WHERE resource_id = ingress_id) AS ingress_id");
                    elseif ($group_select == 'egress_id')
                        array_push($field_arr, "(SELECT alias FROM resource WHERE resource_id = egress_id) AS egress_id");
                    else
                        array_push($field_arr, $group_select);
                    $show_fields[$group_select] = $group_select;
                    $order_num++;
                }
            }
        }
        
        array_push($group_arr, 'did');

        $fields = "";
        $groups = "";
        $orders = "";
        $wheres = "";
        if (count($field_arr))
        {
            $fields = implode(',', $field_arr) . ",";
        }
        if (count($group_arr))
        {
            $from_detail = array('ingress_country', 'ingress_code_name', 'ingress_code', 'ingress_rate',
                'egress_country', 'egress_code_name', 'egress_code', 'ingress_ip', 'egress_ip');
            $groups = "GROUP BY " . implode(',', $group_arr);
        }

        if ($order_num > 0)
        {
            $orders = "ORDER BY " . implode(',', range(1, $order_num));
        }


        if (count($where_arr))
        {
            $wheres = " and " . implode(' and ', $where_arr);
        }

        $replace_fields = array(
            'group_time' => 'Group Time',
            'ingress_client_id' => 'Ingress Carrier',
            'ingress_id' => 'Ingress Trunk',
            'ingress_country' => 'ORIG Country',
            'ingress_code_name' => 'ORIG Code Name',
            'ingress_code' => 'ORIG Code',
            'ingress_rate' => 'ORIG Rate',
            'egress_client_id' => 'Engress Carrier',
            'egress_id' => 'Egress Trunk',
            'egress_country' => 'TERM Country',
            'egress_code_name' => 'TERM Code Name',
            'egress_code' => 'TERM Code'
        );

        $ingress_clients = $this->Cdrs->get_ingress_clients();
        $egress_clients = $this->Cdrs->get_egress_clients();
        $switch_ips = $this->Cdrs->get_switch_ip();
        $ingress_trunks = $this->Cdrs->get_ingress_trunks();
        $egress_trunks = $this->Cdrs->get_egress_trunks();


        $sql = "SELECT is_preload FROM system_parameter LIMIT 1";
        $is_preload_result = $this->Cdrs->query($sql);
        $is_preload = $is_preload_result[0][0]['is_preload'];
        $this->set('show_nodata', true);
        session_write_close();
        if(isset($_GET['show_type']) || $is_preload)
        {

            $data = $this->Cdrs->get_did_report($start_date, $end_date, $fields, $groups, $orders, $wheres, $type);
        
        } else 
        {
            $data = array();
            $this->set('show_nodata', false);
        }

        //var_dump($data);

        $this->set('ingress_clients', $ingress_clients);
        $this->set('egress_clients', $egress_clients);
        //$this->set('switch_ips', $switch_ips);
        $this->set('ingress_trunks', $ingress_trunks);
        $this->set('egress_trunks', $egress_trunks);
        $this->set('show_fields', $show_fields);
        $this->set('replace_fields', $replace_fields);
        $this->set('start_date', $start_date);
        $this->set('end_date', $end_date);
        $this->set('type', $type);
        $this->set('data', $data);
        $rate_tables=$this->Cdrs->get_rate_tables();
        $routing_plans=$this->Cdrs->get_routing_plans();
        $this->set('rate_tables', $rate_tables);
        $this->set('routing_plans', $routing_plans);

        if (isset($_GET['show_type']) && $_GET['show_type'] == '1')
        {
            header("Pragma: public");
            header("Expires: 0");
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("Content-Type: text/csv");
            header("Content-Type: application/octet-stream");
            header("Content-Type: application/download");
            header("Content-Disposition: attachment;filename=summary_report_{$_GET['start_date']}_{$_GET['stop_date']}.csv");
            header("Content-Transfer-Encoding: binary ");
            Configure::write('debug', 0);
            $this->autoLayout = FALSE;
            $this->render('did_csv');
        } else if (isset($_GET['show_type']) && $_GET['show_type'] == '2')
        {
            header("Pragma: public");
            header("Expires: 0");
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("Content-Type: application/force-download");
            header("Content-Type: application/octet-stream");
            header("Content-Type: application/download");
            header("Content-Disposition: attachment;filename=summary_report_{$_GET['start_date']}_{$_GET['stop_date']}.xls");
            header("Content-Transfer-Encoding: binary ");
            Configure::write('debug', 0);
            $this->autoLayout = FALSE;
            $this->render('did_xls');
        }
    }
    
    
    public function qos_summary($type = 1)
    {
        $this->pageTitle = "Statistics/QoS Summary";

        $table_name = 'cdr_report';

        extract($this->Cdr->get_start_end_time());

        $start_date = $start_date;
        $end_date = $end_date;

        $gmt = $tz;

        $field_arr = array();
        $group_arr = array();
        $show_fields = array();
        $order_num = 0;
        $where_arr = array();
        $group_select_arr = array();

        if (isset($_GET['start_date']) && !empty($_GET['start_date']))
            $start_date = $_GET['start_date'] . ' ' . $_GET['start_time'];
        if (isset($_GET['stop_date']) && !empty($_GET['stop_date']))
            $end_date = $_GET['stop_date'] . ' ' . $_GET['stop_time'];
        if (isset($_GET['query']['tz']) && !empty($_GET['query']['tz']))
            $gmt = $_GET['query']['tz'];
        $start_date .= ' ' . $gmt;
        $end_date .= ' ' . $gmt;

        if (isset($_GET['group_by_date']) && !empty($_GET['group_by_date']))
        {
            array_push($field_arr, "to_char(report_time, '{$_GET['group_by_date']}') as group_time");
            $show_fields['group_time'] = "group_time";
            array_push($group_arr, "to_char(report_time, '{$_GET['group_by_date']}')");
            $order_num++;
        }

        if (!empty($_GET['ingress_id']))
        {
            $res = $this->Cdr->findTechPerfix($_GET['ingress_id']);
            $this->set('tech_perfix', $res);
            
            $ingress_options = $this->Cdrs->get_ingress_options($_GET['ingress_id']);
            
            $this->set('ingress_options', $ingress_options);
        }


        if (isset($_GET['route_prefix']) && $_GET['route_prefix'] != 'all')
        {
            if ($_GET['route_prefix'] == '')
            {
                array_push($where_arr, "(ingress_prefix = '\"\"' or ingress_prefix='' or ingress_prefix is null)");
            } else
            {
                array_push($where_arr, "ingress_prefix = '{$_GET['route_prefix']}'");
            }
        }
        
        if (isset($_GET['ingress_rate_table']) && $_GET['ingress_rate_table'] != 'all')
        {
            
            array_push($where_arr, "ingress_rate_table_id = {$_GET['ingress_rate_table']}");
        }
        
        if (isset($_GET['ingress_routing_plan']) && $_GET['ingress_routing_plan'] != 'all')
        {
            
            array_push($where_arr, "route_plan_id = {$_GET['ingress_routing_plan']}");
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


        if (isset($_GET['ingress_client_id']) && !empty($_GET['ingress_client_id']))
            array_push($where_arr, "ingress_client_id = {$_GET['ingress_client_id']}");
        if (isset($_GET['ingress_id']) && !empty($_GET['ingress_id']))
            array_push($where_arr, "ingress_id = {$_GET['ingress_id']}");
        if (isset($_GET['orig_country']) && !empty($_GET['orig_country']))
        {
            array_push($where_arr, "ingress_country = '{$_GET['orig_country']}'");
            $table_name = 'cdr_report_detail';
        }
        if (isset($_GET['orig_code_name']) && !empty($_GET['orig_code_name']))
        {
            array_push($where_arr, "ingress_code_name = '{$_GET['orig_code_name']}'");
            $table_name = 'cdr_report_detail';
        }
        if (isset($_GET['orig_code']) && !empty($_GET['orig_code']))
        {
            array_push($where_arr, "ingress_code::prefix_range <@  '{$_GET['orig_code']}'");
            $table_name = 'cdr_report_detail';
        }
        if (isset($_GET['egress_client_id']) && !empty($_GET['egress_client_id']))
            array_push($where_arr, "egress_client_id = {$_GET['egress_client_id']}");
        if (isset($_GET['egress_id']) && !empty($_GET['egress_id']))
            array_push($where_arr, "egress_id = {$_GET['egress_id']}");
        if (isset($_GET['term_country']) && !empty($_GET['term_country']))
        {
            array_push($where_arr, "egress_country = '{$_GET['term_country']}'");
            $table_name = 'cdr_report_detail';
        }
        if (isset($_GET['term_code_name']) && !empty($_GET['term_code_name']))
        {
            array_push($where_arr, "egress_code_name = '{$_GET['term_code_name']}'");
            $table_name = 'cdr_report_detail';
        }
        if (isset($_GET['term_code']) && !empty($_GET['term_code']))
        {
            array_push($where_arr, "egress_code::prefix_range <@ '{$_GET['term_code']}'");
            $table_name = 'cdr_report_detail';
        }

        if (isset($_GET['group_select']) && !empty($_GET['group_select']))
        {
            $group_select_arr = $_GET['group_select'];
            foreach ($group_select_arr as $group_select)
            {
                if (!empty($group_select) && !in_array($group_select, $group_arr))
                {
                    array_push($group_arr, $group_select);
                    if ($group_select == 'ingress_client_id')
                        array_push($field_arr, "(SELECT name FROM client WHERE client_id = ingress_client_id) AS ingress_client_id");
                    elseif ($group_select == 'egress_client_id')
                        array_push($field_arr, "(SELECT name FROM client WHERE client_id = egress_client_id) AS egress_client_id");
                    elseif ($group_select == 'ingress_id')
                        array_push($field_arr, "(SELECT alias FROM resource WHERE resource_id = ingress_id) AS ingress_id");
                    elseif ($group_select == 'egress_id')
                        array_push($field_arr, "(SELECT alias FROM resource WHERE resource_id = egress_id) AS egress_id");
                    else
                        array_push($field_arr, $group_select);
                    $show_fields[$group_select] = $group_select;
                    $order_num++;
                }
            }
        }
        
        if (isset($_GET['rate_display_as']) && $_GET['rate_display_as'] == 1)
        {
            $table_name = 'cdr_report_detail';
            if ($type == 1)
            {
                array_push($group_arr, 'ingress_rate');
                array_push($field_arr, 'ingress_rate as actual_rate');
                $show_fields['actual_rate'] = 'actual_rate';
            }
            else
            {
                array_push($group_arr, 'egress_rate');
                array_push($field_arr, 'egress_rate as actual_rate');               
                $show_fields['actual_rate'] = 'actual_rate';
            }
        }

        $fields = "";
        $groups = "";
        $orders = "";
        $wheres = "";
        if (count($field_arr))
        {
            $fields = implode(',', $field_arr) . ",";
        }
        if (count($group_arr))
        {
            $from_detail = array('ingress_country', 'ingress_code_name', 'ingress_code', 'ingress_rate',
                'egress_country', 'egress_code_name', 'egress_code');
            $compare_detail = array_intersect($group_arr, $from_detail);
            if (count($compare_detail))
                $table_name = 'cdr_report_detail';
            $groups = "GROUP BY " . implode(',', $group_arr);
        }

        if ($order_num > 0)
        {
            $orders = "ORDER BY " . implode(',', range(1, $order_num));
        }


        if (count($where_arr))
        {
            $wheres = " and " . implode(' and ', $where_arr);
        }

        $replace_fields = array(
            'group_time' => 'Group Time',
            'ingress_client_id' => 'Ingress Carrier',
            'ingress_id' => 'Ingress Trunk',
            'ingress_country' => 'ORIG Country',
            'ingress_code_name' => 'ORIG Code Name',
            'ingress_code' => 'ORIG Code',
            'ingress_rate' => 'ORIG Rate',
            'egress_client_id' => 'Engress Carrier',
            'egress_id' => 'Egress Trunk',
            'egress_country' => 'TERM Country',
            'egress_code_name' => 'TERM Code Name',
            'egress_code' => 'TERM Code'
        );

        $ingress_clients = $this->Cdrs->get_ingress_clients();
        $egress_clients = $this->Cdrs->get_egress_clients();
        $switch_ips = $this->Cdrs->get_switch_ip();
        $ingress_trunks = $this->Cdrs->get_ingress_trunks();
        $egress_trunks = $this->Cdrs->get_egress_trunks();

        $report_max_time = $this->Cdr->get_report_maxtime($start_date, $end_date);


        $select_time_end = strtotime($end_date);

        $is_from_client_cdr = false;

        if (empty($report_max_time))
        {
            $is_from_client_cdr = true;
            $report_max_time = $start_date;
        }
        $system_max_end = strtotime($report_max_time);
        
        
        $sql = "SELECT is_preload FROM system_parameter LIMIT 1";
        $is_preload_result = $this->Cdrs->query($sql);
        $is_preload = $is_preload_result[0][0]['is_preload'];
        $this->set('show_nodata', true);
        session_write_close();
        if(isset($_GET['show_type']) || $is_preload)
        {

            if ($select_time_end > $system_max_end)
            {
                if ($is_from_client_cdr)
                {
                    $sql = $this->Cdrs->get_qos_summary_report_from_client_cdr($report_max_time, $end_date, $type);
                    $data = $this->Cdrs->query($sql);
                } else
                {
                    $sql1 = $this->Cdrs->get_qos_cdrs($start_date, $report_max_time, $fields, $groups, $orders, $wheres, $type, $table_name);
                    $sql2 = $this->Cdrs->get_qos_summary_report_from_client_cdr($report_max_time, $end_date, $type);
                    $data = $this->Cdrs->get_qos_cdrs_two($sql1, $sql2, $type, $orders, $show_fields);
                }
            } else
            {
                $sql = $this->Cdrs->get_qos_cdrs($start_date, $end_date, $fields, $groups, $orders, $wheres, $type, $table_name);
                $data = $this->Cdrs->query($sql);
            }
        
        } else 
        {
            $data = array();
            $this->set('show_nodata', false);
        }

        //var_dump($data);
        
        $rate_tables=$this->Cdrs->get_rate_tables();
        $routing_plans=$this->Cdrs->get_routing_plans();

        $this->set('ingress_clients', $ingress_clients);
        $this->set('egress_clients', $egress_clients);
        //$this->set('switch_ips', $switch_ips);
        $this->set('ingress_trunks', $ingress_trunks);
        $this->set('egress_trunks', $egress_trunks);
        if (isset($show_fields['actual_rate']))
            unset($show_fields['actual_rate']);
        $this->set('show_fields', $show_fields);
        $this->set('replace_fields', $replace_fields);
        $this->set('start_date', $start_date);
        $this->set('end_date', $end_date);
        $this->set('type', $type);
        $this->set('data', $data);
        $this->set('rate_tables', $rate_tables);
        $this->set('routing_plans', $routing_plans);

        if (isset($_GET['show_type']) && $_GET['show_type'] == '1')
        {
            header("Pragma: public");
            header("Expires: 0");
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("Content-Type: text/csv");
            header("Content-Type: application/octet-stream");
            header("Content-Type: application/download");
            header("Content-Disposition: attachment;filename=summary_report_{$_GET['start_date']}_{$_GET['stop_date']}.csv");
            header("Content-Transfer-Encoding: binary ");
            Configure::write('debug', 0);
            $this->autoLayout = FALSE;
            $this->render('qos_summary_down_csv');
        } else if (isset($_GET['show_type']) && $_GET['show_type'] == '2')
        {
            header("Pragma: public");
            header("Expires: 0");
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("Content-Type: application/force-download");
            header("Content-Type: application/octet-stream");
            header("Content-Type: application/download");
            header("Content-Disposition: attachment;filename=summary_report_{$_GET['start_date']}_{$_GET['stop_date']}.xls");
            header("Content-Transfer-Encoding: binary ");
            Configure::write('debug', 0);
            $this->autoLayout = FALSE;
            $this->render('qos_summary_down_xls');
        }
    }

}

?>


