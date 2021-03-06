<?php

class DisconnectreportsController extends AppController
{

    var $name = 'Disconnectreports';
    var $uses = array('Cdr', 'Cdrs');
    var $helpers = array('javascript', 'html', 'AppDis', 'Common');

    public function mismatch_cause_list($key)
    {
        $array = array(
            'RF_INVAILED_ARGS' => 'Invalid Argument',
            'RF_POOL_SESSION' => 'Internal Error',
            'RF_IN_SYS_LIMIT' => 'System Limit Exceeded',
            'RF_INGRESS_IP_CHECK' => 'Unauthorized IP Address',
            'RF_INGRESS_RESOURCE' => 'No Ingress Resource Found',
            'RF_ROUTE_STRATAGY' => ' No Routing Plan Found',
            'RF_PRODUCT_NOT_FOUND' => 'No Product Found',
            'RF_IN_RESORUCE_LIMIT' => 'Ingress Trunk Limit Exceeded',
            'RF_RESOURCE_CODEC' => 'Invalid Codec Negotiation',
            'RF_INGRESS_LRN_BLOCK' => 'Block due to LRN',
            'RF_INGRESS_RATE' => 'Original Rate Not Found',
            'RF_EGRESS_NOT_FOUND' => 'Egress Trunk Not Found',
            'RF_NORMAL12' => 'Normal'
        );
        $v = isset($array[$key]) ? $array[$key] : '';
        return $v;
    }

    function index()
    {
        $this->redirect('summary_reports');
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
            $limit = $this->Session->read('sst_retail_rcardpools');
            $this->Session->write('executable', $limit['executable']);
            $this->Session->write('writable', $limit['writable']);
        }
        parent::beforeFilter();
    }

//初始化查询参数
    function init_query()
    {
        $this->set('ingress_carrier', $this->Cdr->findIngressClient());
        $this->set('egress_carrier', $this->Cdr->findEgressClient());
        $this->set('server', $this->Cdr->find_server());
        $this->set('ingress', $this->Cdr->findAll_ingress_id());
        $this->set('egress', $this->Cdr->findAll_egress_id());

        if (!empty($_GET['ingress_alias'])) {
            $res = $this->Cdr->findTechPerfix($_GET['ingress_alias']);
            $this->set('tech_perfix', $res);
        }
    }

    function create_amchart_csv($report_type, $field)
    {
        $field_sql = $this->get_field_sql($report_type, $field);
        $where = $this->capture_report_condtions($report_type);
        extract($this->capture_report_join($report_type, ''));
        $sql = " copy (
select to_char(time, 'YYYY-MM-DD HH24') as group_time,$field_sql
from client_cdr $join
where $where group by group_time having 1=1
order by group_time desc
) to '" . Configure::read('database_actual_export_path') . "/$report_type" . "$field.csv' csv ";
        $this->Cdr->query($sql);
        copy(Configure::read('database_export_path') . "/$report_type" . "$field.csv", APP . 'webroot' . DS . 'amstock' . DS . "$report_type" . "$field.csv");
    }

    function create_amchart_flash($report_type)
    {
        $this->create_amchart_csv($report_type, 'cdr_count');
        $this->create_amchart_csv($report_type, 'cdr_count_percentage');


        $this->create_amchart_setting_xml($report_type, 'cdr_count');
        $this->create_amchart_setting_xml($report_type, 'cdr_count_percentage');
    }

    function flash_setting($report_type)
    {
        Configure::write('debug', 0);
        $field = !empty($_GET ['f']) ? $_GET ['f'] : 'cdr_count';
        $report_type = !empty($_GET ['report_type']) ? $_GET ['report_type'] : 'orig_discon_report';
        $xml_file = APP . 'webroot' . DS . 'amstock' . DS . "$report_type" . "$field" . "_amstock_settings.xml";
        echo file_get_contents($xml_file);
    }

    function create_amchart_setting_xml($report_type, $field)
    {
        $humanize_field = Inflector::humanize($field);


        $out = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $out = '<settings>';
        $out .= file_get_contents(APP . 'views' . DS . 'locationreports' . DS . "amstock_settings_base.xml");
        $out .= '<data_sets>';

        $out .= "<data_set did=\"0\">\n";
        $out .= " <title>$humanize_field</title>\n";
        $out .= " <short>$humanize_field $humanize_field</short>\n";
        $out .= " <description> $humanize_field;</description>\n";
        $out .= "<file_name>$report_type" . "$field.csv</file_name>\n";
        $out .= "<main_drop_down selected=\"true\"></main_drop_down>\n
<compare_list_box selected=\"false\"></compare_list_box>\n
<csv>
<reverse>true</reverse>
<separator>,</separator>
<date_format>YYYY-MM-DD hh:mm:ss</date_format>
<decimal_separator>.</decimal_separator>
<columns>
<column>date</column>
<column>close</column>
</columns>
</csv>\n";
        $out .= "</data_set>\n";

        $out .= '</data_sets>';
        $out .= file_get_contents(APP . 'views' . DS . 'locationreports' . DS . "amstock_settings_charts.xml");
        $out .= '</settings>';


        $xml_file = APP . 'webroot' . DS . 'amstock' . DS . "$report_type" . "$field" . "_amstock_settings.xml";
        $fp = fopen($xml_file, 'w');
        fwrite($fp, $out);
        fclose($fp);
    }

    function get_field_sql($report_type, $field)
    {
        extract($this->Cdr->get_real_period());
        $where = $this->capture_report_condtions($report_type);
        extract($this->capture_report_join($report_type, ''));
        $arr = array(
            'cdr_count' => " count(*) as disconnect",
            'cdr_count_percentage' => "(count(*)::numeric/(select count(*) from client_cdr $join where $where))::numeric(20,3) as per",
        );

        if (isset($arr[$field])) {

            return $arr[$field];
        } else {

            return $arr['cdr_count'];
        }
    }

    function get_datas($report_type = '', $order_field, $group_by_where2 = '')
    {
        extract($this->Cdr->get_real_period());
        $where = $this->capture_report_condtions1($report_type);
        $order = $this->capture_report_order();
        extract($this->capture_report_join1($report_type, ''));
        $release_cause = "case release_cause
when 0 then 'Invalid Argument'
when 1 then 'System Limit Exceeded'
when 2 then 'SYSTEM_CPS System Limit Exceeded'
when 3 then 'Unauthorized IP Address'
when 4 then ' No Ingress Resource Found'
when 5 then 'No Product Found '
when 6 then 'Trunk Limit CAP Exceeded'
when 7 then 'Trunk Limit CPS Exceeded'
when 8 then 'IP Limit CAP Exceeded'
when 9 then 'IP Limit CPS Exceeded '
when 10 then 'Invalid Codec Negotiation'
when 11 then 'Block due to LRN'
when 12 then 'Ingress Rate Not Found'
when 13 then ' Egress Trunk Not Found'
when 14 then 'From egress response 404'
when 15 then 'From egress response 486 '
when 16 then 'From egress response 487 '
when 17 then 'From egress response 200 '
when 18 then 'All egress not available'
when 19 then 'Normal'
when 20 then 'Ingress Resource disabled'
when 21 then 'Balance Use Up'
when 22 then 'No Routing Plan Route'
when 23 then 'No Routing Plan Prefix'
when 24 then 'Ingress Rate No configure'
when 25 then 'Invalid Codec Negotiation'
when 26 then 'No Codec Found'
when 27 then 'All egress no confirmed'
when 28 then 'LRN response no exist DNIS'
else 'other' end as
release_cause";

//$per = "count(*) as my_count,(select count(*) from cdr_report $join where $where) as all_count";

        $having = '';

        if ($report_type == 'term_discon_report') {
            $per = "sum(egress_total_calls) as my_count,(select sum(egress_total_calls) from cdr_report $join where $where) as all_count";
//$column = " $per, count(*) as disconnect";
            $column = " release_cause as real_release_cause, $per, $release_cause, sum (egress_total_calls)as disconnect";
        } else {
            $per = "sum(ingress_total_calls) as my_count,(select sum(ingress_total_calls) from cdr_report $join where $where) as all_count";
            $column = " release_cause as real_release_cause, $per, $release_cause, sum (ingress_total_calls) as disconnect";
        }
        if (count($group_by_field_arr)) {
            $this->set('is_group', 'true');
            $org_sql = "select $field_list,$column,$order_field	from cdr_report $join where $where group by $group_by_where,$group_by_where2 $order";
        } else {
            $this->set('is_group', 'false');
            $org_sql = "select $column,$order_field	from cdr_report $join where $where group by $group_by_where,$group_by_where2 $order";
        }


//$org_list = $this->Cdr->query($org_sql);
        return compact('org_sql', 'org_list', 'group_by_field_arr');
    }

    function capture_report_order()
    {
        $order = $this->_order_condtions(
            Array('date', 'release_cause_from_protocol_stack', 'cause', 'disconnect', 'per', 'release_cause')
        );
        if (empty($order)) {
            $order = ' order by 1,2';
        } else {
            $order = 'order by ' . $order;
        }
        return $order;
    }

    function get_all_country()
    {
        extract($this->Cdr->get_real_period());
        $where = $this->capture_report_condtions('location_report');
        extract($this->capture_report_join('location_report', ''));

        $org_sql = "select distinct term_country from client_cdr $join where $where";
//$org_sql="select distinct term_country from statistic_cdr $join where $where";
        $org_list = $this->Cdr->query($org_sql);

        $tmp_arr = array();
        if (!empty($org_list)) {
            $size = count($org_list);
            foreach ($org_list as $key => $value) {
                $tmp_arr[] = $value[0]['term_country'];
            }
        }
        return $tmp_arr;
    }

    function summary_reports()
    {
        $this->_session_get(isset($_GET ['searchkey']));
        $this->pageTitle = "Statistics/Disconnect Causes";
        $t = getMicrotime();

        if (!empty($this->params['pass'][0])) {
            $rate_type = $this->params['pass'][0];
            if ($rate_type == 'org') {
                $join_rate_field = 'ingress_rate_id';
                $this->set('rate_type', 'org');
                $order_field = "split_part(binary_value_of_release_cause_from_protocol_stack,':',1) as release_cause_from_protocol_stack,
split_part(binary_value_of_release_cause_from_protocol_stack,':',2) as cause";
                $group_by_where = "release_cause,binary_value_of_release_cause_from_protocol_stack ";
            } elseif ($rate_type == 'term') {
                $join_rate_field = 'egress_rate_id';
                $this->set('rate_type', 'term');
                $order_field = "split_part(release_cause_from_protocol_stack,':',1) as release_cause_from_protocol_stack,
split_part(release_cause_from_protocol_stack,':',2) as cause";
                $group_by_where = "release_cause,release_cause_from_protocol_stack ";
            } else {
                $this->set('rate_type', 'org');
                $join_rate_field = 'ingress_rate_id';
                $order_field = "split_part(binary_value_of_release_cause_from_protocol_stack,':',1) as release_cause_from_protocol_stack,
split_part(binary_value_of_release_cause_from_protocol_stack,':',2) as cause";
                $group_by_where = "release_cause,binary_value_of_release_cause_from_protocol_stack ";
            }
        } else {
            $rate_type = 'org';
            $this->set('rate_type', 'org');
            $join_rate_field = 'ingress_rate_id';
            $order_field = "split_part(binary_value_of_release_cause_from_protocol_stack,':',1)as release_cause_from_protocol_stack,
split_part(binary_value_of_release_cause_from_protocol_stack,':',2) as cause";
            $group_by_where = "release_cause,binary_value_of_release_cause_from_protocol_stack ";
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
        $this->set("replace_fields", $replace_fields);

        $this->init_query();
        $login_type = $_SESSION ['login_type'];
        extract($this->Cdr->get_real_period());
//取数据 sql ==> org_sql , 数据===>org_list, 分组条件====>'group_by_field_arr');
        if ($rate_type == 'org') {
            $report_type = 'orig_discon_report';
        } else {
            $report_type = 'term_discon_report';
        }

        $this->set("report_type", $report_type);
        extract($this->get_datas($report_type, $order_field, $group_by_where));
// $this->create_amchart_flash($report_type);
        $show_comm = true; //普通表格显示
        $show_subgroups = false; //以subgroup方式显示
        $show_subtotals = false; //以subgtotal方式显示
//********************************************************************************************************
// subgroups 分子组显示
//********************************************************************************************************
        $grpup_size = count($group_by_field_arr);
        $this->set('show_comm', $show_comm);
        $this->set('show_nodata', true);
        if (isset($_GET ['query'] ['output'])) {
//下载
            $file_name = $this->create_doload_file_name('DisconnectCauses', $start, $end);
            if ($_GET ['query'] ['output'] == 'csv') {
                Configure::write('debug', 0);
//第一个参数是对导出的描述,第2个参数是导出的sql,第3个是导出的文件名
//$this->Cdr->export__sql_data('DownloadDisconnectCauses', $org_sql, $file_name);
                $org_list = $this->Cdr->query($org_sql);
                $this->set("client_org", $org_list);
                header("Pragma: public");
                header("Expires: 0");
                header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
                header("Content-Type: text/csv");
                header("Content-Type: application/octet-stream");
                header("Content-Type: application/download");
                header("Content-Disposition: attachment;filename=$file_name");
                header("Content-Transfer-Encoding: binary");
                $this->autoLayout = FALSE;
                $this->render('disconnectreports_down_csv');
//$this->layout = 'csv';
            } elseif ($_GET ['query'] ['output'] == 'xls') {
                $path_info = pathinfo($file_name);
//Configure::write('debug', 0);
//$this->Cdr->export_xls_sql_data('DownloadDisconnectCauses', $org_sql, $file_name);
//$this->_catch_exception_msg(array('DownloadDisconnectCauses','_download_xls'),array('download_sql' => $org_sql));
                $org_list = $this->Cdr->query($org_sql);
                $this->set("client_org", $org_list);
                header("Pragma: public");
                header("Expires: 0");
                header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
                header("Content-Type: application/force-download");
                header("Content-Type: application/octet-stream");
                header("Content-Type: application/download");
                header("Content-Disposition: attachment;filename=" . $path_info['filename'] . ".xls");
                header("Content-Transfer-Encoding: binary ");
                Configure::write('debug', 0);
                $this->autoLayout = FALSE;
                $this->render('disconnectreports_down_xls');

                $this->layout = 'csv';
            } elseif ($_GET ['query'] ['output'] == 'delayed') {
//Configure::write('debug',0);
//第一个参数是对导出的描述,第2个参数是导出的sql,第3个是导出的文件名
//$this->Cdr->export__sql_data('DownloadDisconnectCauses',$org_sql,'report');

                $this->layout = 'csv';
            } else {
//web显示
                $org_list = $this->Cdr->query($org_sql);
                $this->set("client_org", $org_list);
                $this->set("group_by_field_arr", $group_by_field_arr);

                /* $image_file = $this->create_image_csv();
                $this->set('image_file', basename($image_file));

                $country_arr = $this->get_all_country ();
                $this->set('country_arr', $country_arr); */
                $this->set('field', 'no_channel_calls');
            }
        } else {
            $sql = "SELECT is_preload FROM system_parameter LIMIT 1";
            $is_preload_result = $this->Cdr->query($sql);
            $is_preload = $is_preload_result[0][0]['is_preload'];
            if ($is_preload)
                $org_list = $this->Cdr->query($org_sql);
            else {
                $org_list = array();
                $this->set('show_nodata', false);
            }
            $this->set("client_org", $org_list);
            $this->set("group_by_field_arr", $group_by_field_arr);
//$image_file = $this->create_image_csv();
//$this->set('image_file', basename($image_file));

            $country_arr = $this->get_all_country();
            $this->set('country_arr', $country_arr);
            $this->set('field', 'ca');
        }
        $this->set('quey_time', round((getMicrotime() - $t) * 1000, 0));
        $rate_tables = $this->Cdrs->get_rate_tables();
        $routing_plans = $this->Cdrs->get_routing_plans();
        $this->set('rate_tables', $rate_tables);
        $this->set('routing_plans', $routing_plans);
    }

    function _download_xls($params = array())
    {
        extract($params);
        if ($this->Cdr->download_xls_by_sql($download_sql, array('objectives' => 'report', 'file_name' => $file_name))) {
            exit(1);
        }
    }

    function image_test()
    {
//var_dump($_REQUEST);
//Configure::write('debug',0);
//$this->layout='csv';
        require_once("vendors/image_test.php");
//App::import ( 'Vendor', 'image_test');
//$this->layout='csv';
    }

//生成image所需查询结果文件，返回文件名
    function create_image_csv()
    {
        $return = '';
//create数据文件
        $where = $this->capture_report_condtions('location_report');
        extract($this->capture_report_join('location_report', ''));
        $today_statistic = $this->getTodayStatistic();


        $sql = "select to_char(time, 'YYYY-MM-DD HH24') as group_time, term_country, sum(ca) as ca, sum(ok_calls) as ok_calls,
sum(call_duration) as call_duration, sum(ingress_cost) as ingress_cost, sum(egress_cost) as egress_cost, sum(orig_bill_minute)
as orig_bill_minute, sum(term_bill_minute) as term_bill_minute, sum(ingress_ca) as ingress_ca, sum(egress_ca) as egress_ca,
sum(not_zero_calls) as not_zero_calls, sum(busy_calls) as busy_calls, sum(no_channel_calls) as no_channel_calls
from $today_statistic
where $where group by group_time, term_country having 1=1
order by group_time desc";
        $result = $this->Cdr->query($sql);
        if (!empty($result)) {
            $return = "/tmp/image_" . date("Ymd-His") . "_" . rand(0, 99);
            file_put_contents($return, json_encode($result));
        }
        return $return;
    }

}
