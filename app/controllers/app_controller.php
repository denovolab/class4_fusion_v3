<?php

App::import('Core', 'Sanitize');

class AppController extends Controller
{

    var $components = array('RequestHandler');
    var $helpers = array('javascript', 'html', 'form', 'Session', 'App', "AppCommon", 'Xpaginator', 'Xform', 'AppImportExportLog');
    var $paginate = array('limit' => 100, 'page' => 1);

    function format_flash_data($list = array())
    {
        $org_sql = "select  term_country ,to_char(time, 'YYYY-MM-DD HH24:00:00') as group_time,(sum(case when call_duration='' then 0 else call_duration::numeric end )/60 ) ::numeric(20,2) as total_duration,count(*) as total_calls , sum(ingress_client_cost::numeric) ::numeric(20,5) as total_cdr_cost,sum(egress_cost::numeric) ::numeric(20,5) as total_egress_cost, sum(ingress_client_cost::numeric(20,5))-sum(egress_cost::numeric(20,5)) ::numeric(20,5) as profit, case when sum(ingress_client_cost::numeric)=0 then 0 else ((sum(ingress_client_cost::numeric)-sum(egress_cost::numeric))*100/sum(ingress_client_cost::numeric)) end ::numeric(20,5) as profit_percentage from client_cdr 
		where 
		time between '2011-04-05 16:00:00 +00' and '2011-04-06 15:59:59 +00' and term_country is not null group by term_country ,group_time having 1=1  ";
        $list = $this->Cdr->query($org_sql);
        $new_list = array();
        $pre_country = '';
        $curr_country = '';
        $size = count($list);
        for ($i = 0; $i < $size; $i++) {
            if ($i == 0) {
                $pre_country = $list[0][0]['term_country'];
                $curr_country = $list[0][0]['term_country'];
                $new_list[$curr_country][] = $list[0][0];
                continue;
            } else {
                $pre_country = $list[$i - 1][0]['term_country'];
                $curr_country = $list[$i][0]['term_country'];
            }

            if ($pre_country != $curr_country) {
                $new_list[$curr_country][] = $list[$i][0];
            } else {
                $new_list[$pre_country][] = $list[$i][0];
            }
        }
        return $new_list;
    }

    function get_params($k, $default = null)
    {
        return isset($_REQUEST[$k]) ? $_REQUEST[$k] : $default;
    }

    /**
     *
     * get params
     * @param $index
     */
    function get_param_pass($index)
    {
        if (isset($this->params['pass'][$index])) {
            return $this->params['pass'][$index];
        } else {
            $this->redirect('/homes/bad_url');
        }
    }


    function capture_report_join1($report_type, $default_group)
    {
        $field_arr = array();
        $group_arr = array();
        $show_fields = array();
        $order_num = 0;
        if (isset($_GET['group_select']) && !empty($_GET['group_select'])) {
            $group_select_arr = $_GET['group_select'];
            foreach ($group_select_arr as $group_select) {
                if (!empty($group_select) && !in_array($group_select, $group_arr)) {
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
        $group_arr = array_merge($group_arr, array('release_cause'));
        $group_by_field_arr = $show_fields;
        $group_by_field = implode(',', $group_by_field_arr);
        $field_list = implode(",", $field_arr);
        $join = '';
        $group_by_where = implode(',', $group_arr);
//$join = " $orig_client_join $term_client_join $term_rate_join $orig_rate_join";


        return compact('group_by_field', 'join', 'group_by_where', 'group_by_field_arr', 'field_list');
    }

    #create report query   join

    function capture_report_join($report_type, $default_group)
    {
        //$org_code_join = "";
        //$term_code_join = 'left  join rate  as  term_code on term_code.rate_id::text=client_cdr.egress_rate_id';
        $orig_client_join = '';
        $term_client_join = '';
        $orig_rate_join = '';
        $term_rate_join = '';
        $group_by_field_arr = array();

        $group_by_where = "";
        $group_by_field = "'$report_type' as report_type ";
        if ($report_type == 'location_report') {
            $group_by_where = "  term_country  ";
            $group_by_field = "  term_country ";
        }


        if ($report_type == 'orig_discon_report') {

            $group_by_where = "  release_cause,binary_value_of_release_cause_from_protocol_stack";
            $group_by_field = "split_part(binary_value_of_release_cause_from_protocol_stack,':',1) as  release_cause_from_protocol_stack,
	    split_part(binary_value_of_release_cause_from_protocol_stack,':',2) as   cause";
        }


        if ($report_type == 'term_discon_report') {
            $group_by_where = "  release_cause,release_cause_from_protocol_stack  ";
            $group_by_field = "split_part(release_cause_from_protocol_stack,':',1) as  release_cause_from_protocol_stack,
	     split_part(release_cause_from_protocol_stack,':',2) as  cause";
        }


        if (!empty($default_group)) {
            $_GET['group_by'][0] = $default_group;
        }


        if (isset($_GET ['group_by'])) {
            foreach ($_GET ['group_by'] as $k => $v) {
                if (empty($v)) {
                    continue;
                }
                if (in_array($v, $group_by_field_arr)) {
                    continue;
                }


                array_push($group_by_field_arr, $v);
                $group_by_where = $group_by_where . "," . $v;


                if ($v == 'orig_client_name') {
                    $group_by_field = $group_by_field . ",orig_client.name as  orig_client_name";
                    $orig_client_join = "left join  client  as orig_client  on orig_client.client_id=client_cdr.ingress_client_id";
                }
                if ($v == 'term_client_name') {
                    $group_by_field = $group_by_field . ",term_client.name as  term_client_name";
                    $term_client_join = "left  join client term_client  on term_client.client_id=client_cdr.egress_client_id";
                }


                if ($v == 'ingress_alias') {
                    $group_by_field = $group_by_field . ",trunk_id_origination as  ingress_alias";
                    //$group_by_field = $group_by_field . ", ingress_alias" ;
                }


                if ($v == 'egress_alias') {
                    $group_by_field = $group_by_field . ",trunk_id_termination as  egress_alias";
                    //$group_by_field = $group_by_field . ", egress_alias" ;
                }

                if ($v == 'ingress_host') {
                    $group_by_field = $group_by_field . ",termination_source_host_name as  ingress_host";
                    //$group_by_field = $group_by_field . ", ingress_host" ;
                }
                if ($v == 'egress_host') {
                    $group_by_field = $group_by_field . ",termination_destination_host_name as  egress_host";
                    //	$group_by_field = $group_by_field . ", egress_host" ;
                }


                if ($v == 'orig_code') {
                    $group_by_field = $group_by_field . ",  orig_code";
                    //  	   $org_code_join="left  join rate  as  orig_code on orig_code.rate_id::text=client_cdr.ingress_rate_id";
                }

                if ($v == 'term_code') {
                    $group_by_field = $group_by_field . ",term_code";
                    //	 	$term_code_join="left  join rate  as  term_code on term_code.rate_id::text=client_cdr.egress_rate_id";
                }


                if ($v == 'orig_code_name') {
                    $group_by_field = $group_by_field . ", orig_code_name";
                    //$org_code_join="left  join rate  as  orig_code on orig_code.rate_id::text=client_cdr.ingress_rate_id";
                }
                if ($v == 'term_code_name') {
                    $group_by_field = $group_by_field . ",  term_code_name";
                    //	$term_code_join="left  join rate  as  term_code on term_code.rate_id::text=client_cdr.egress_rate_id";
                }


                if ($v == 'orig_country') {
                    $group_by_field = $group_by_field . ", orig_country";
                    //	$org_code_join="left  join rate  as  orig_code on orig_code.rate_id::text=client_cdr.ingress_rate_id";
                }
                if ($v == 'term_country') {
                    $group_by_field = $group_by_field . ",  term_country";
                    //    	$term_code_join="left  join rate  as  term_code on term_code.rate_id::text=client_cdr.egress_rate_id";
                }


                if ($v == 'orig_rate') {
                    $group_by_field = $group_by_field . ",orig_rate.name as orig_rate";
                    $orig_rate_join = "left  join rate_table   orig_rate on orig_rate.rate_table_id=client_cdr.ingress_client_rate_table_id";
                }
                if ($v == 'term_rate') {
                    $group_by_field = $group_by_field . ",term_rate.name as  term_rate";
                    $term_rate_join = "left  join rate_table as  term_rate on term_rate.rate_table_id=client_cdr.egress_rate_table_id";
                }


                if ($v == 'termination_source_host_name') {
                    $group_by_field = $group_by_field . ",termination_source_host_name as  termination_source_host_name";
                }
            }
        }


        if (!empty($_GET ['group_by_date'])) {
            $group_by_date = $_GET ['group_by_date'];
            array_push($group_by_field_arr, 'group_time');
            $group_by_field = $group_by_field . ",to_char(time, '$group_by_date') as group_time  ";
            $group_by_where = $group_by_where . ",group_time";
        }
        $this->set('group_by_field_arr', $group_by_field_arr);

        if (strpos($group_by_where, ',') == 0) {

            $group_by_where = substr($group_by_where, 1);
        }


        if (!empty($_GET ['query'] ['id_clients_name'])) {

            $orig_client_join = "left join  client  as orig_client  on orig_client.client_id=client_cdr.ingress_client_id";
        }
        if (!empty($_GET ['query'] ['id_clients_name_term'])) {

            $term_client_join = "left  join client term_client  on term_client.client_id=client_cdr.egress_client_id";
        }

        $join = " $orig_client_join     $term_client_join  $term_rate_join  $orig_rate_join";


        return compact('group_by_field', 'join', 'group_by_where', 'group_by_field_arr');
    }

    /**
     *  查询本日内容（没有写进statistic_cdr数据库的）统计的数据库查询语句
     */
    function getTodayStatistic()
    {
        if (!empty($_GET ['start_date']) && !empty($_GET ['start_time']) && !empty($_GET ['stop_date']) && !empty($_GET ['stop_time']) && !empty($_GET ['query']['tz'])) {
            $start = $_GET ['start_date'] . '  ' . $_GET ['start_time'] . ' ' . $_GET ['query']['tz']; //开始时间
            $end = $_GET ['stop_date'] . '  ' . $_GET ['stop_time'] . '  ' . $_GET ['query']['tz']; //结束时间
        } else {
            #report deault query time
            $_GET ['query']['tz'] = '+0000';
            extract($this->Cdr->get_real_period());
        }
        $today = date("Ymd"); //'20110608';//
        $sql = $this->Cdr->query("SELECT count(*) as num FROM INFORMATION_SCHEMA.tables WHERE table_name = 'client_cdr{$today}' and table_schema = 'public' limit 1");

        $time_start_date = date("Y-m-d 00:00:00", strtotime($start)) . ' ' . $_GET ['query']['tz'];
        $start_date = date("Ymd", strtotime($start)) . ' ' . $_GET ['query']['tz'];
        return " (select route_prefix, orig_country, orig_code_name, orig_code, ingress_client_id, trunk_id_origination, ingress_id, term_country, term_code_name, term_code, egress_client_id, trunk_id_termination, egress_id, to_char(time, 'YYYY-MM-DD HH24:00:00 +00')::timestamp with time zone as time, 
termination_source_host_name, origination_source_host_name, 
termination_destination_host_name, sum(coalesce(is_final_call, '1')::integer) as ca, 
sum( case when trunk_id_termination = '' then null else coalesce(is_final_call, '1')::integer end ) as ok_calls, 
(sum ( case call_duration when '' then 0 else call_duration::numeric end))/60 ::numeric as call_duration, 
sum(case ingress_client_cost when 'nan' then null else ingress_client_cost::numeric end)::numeric as ingress_cost, 
sum(case egress_cost when 'nan' then null else egress_cost::numeric end)::numeric as egress_cost, 
sum(ingress_bill_minutes::numeric) as orig_bill_minute, 
sum(egress_bill_minutes::numeric) as term_bill_minute, 
sum(coalesce(is_final_call, '1')::integer) as ingress_ca, 
count(case egress_id when '' then null else egress_id end) as egress_ca, 
count(NULLIF(call_duration , '0')) as not_zero_calls, 
count(case when split_part(release_cause_from_protocol_stack,':',1)='486' then release_cause_from_protocol_stack else null end) as busy_calls, 
count(case when release_cause::integer >=6 and release_cause::integer <=9 then release_cause else null end) as no_channel_calls,
ingress_client_rate_table_id, 
egress_rate_table_id, sum( case when call_duration = '0' then 0 else pdd::integer end) as pdd,sum(lnp_dipping_cost::numeric) as lnp_dipping_cost,
sum( case trunk_id_termination when '' then null else 1 end ) as egress_ok_calls, 
count(case when split_part(release_cause_from_protocol_stack,':',1)='503' then release_cause_from_protocol_stack else null end) as egress_no_channel_calls,
sum(case when coalesce(is_final_call, '1')='1' and lrn_number_vendor::integer in (2,3) then 1 else 0 end) as lrn_calls
from client_cdr where time >= '{$start}' group by route_prefix, orig_country, orig_code_name, orig_code, ingress_client_id, trunk_id_origination, ingress_id, term_country, term_code_name, term_code, egress_client_id, trunk_id_termination, egress_id, time, termination_source_host_name, origination_source_host_name, termination_destination_host_name, ingress_client_rate_table_id, egress_rate_table_id) as client_cdr";
        //var_dump(strtotime($start) > strtotime($time_start_date));exit;
        if (strtotime($start) >= (strtotime(date("Y-m-d") . ' ' . $_GET ['query']['tz']))) {
            return " (select orig_country, orig_code_name, orig_code, ingress_client_id, trunk_id_origination, ingress_id, term_country, term_code_name, term_code, egress_client_id, trunk_id_termination, egress_id, to_char(time, 'YYYY-MM-DD HH24:00:00 +00')::timestamp with time zone as time, 
termination_source_host_name, origination_source_host_name, 
termination_destination_host_name, sum(coalesce(is_final_call, '1')::integer) as ca, 
sum( case when trunk_id_termination = '' then null else coalesce(is_final_call, '1')::integer end ) as ok_calls, 
(sum ( case call_duration when '' then 0 else call_duration::numeric end))/60 ::numeric as call_duration, 
sum(case ingress_client_cost when 'nan' then null else ingress_client_cost::numeric end)::numeric as ingress_cost, 
sum(case egress_cost when 'nan' then null else egress_cost::numeric end)::numeric as egress_cost, 
sum(ingress_bill_minutes::numeric) as orig_bill_minute, 
sum(egress_bill_minutes::numeric) as term_bill_minute, 
sum(coalesce(is_final_call, '1')::integer) as ingress_ca, 
count(case egress_id when '' then null else egress_id end) as egress_ca, 
count(NULLIF(call_duration , '0')) as not_zero_calls, 
count(case when split_part(release_cause_from_protocol_stack,':',1)='486' then release_cause_from_protocol_stack else null end) as busy_calls, 
count(case when release_cause::integer >=6 and release_cause::integer <=9 then release_cause else null end) as no_channel_calls,
ingress_client_rate_table_id, 
egress_rate_table_id, sum( case when call_duration = '0' then 0 else pdd::integer end) as pdd,sum(lnp_dipping_cost::numeric) as lnp_dipping_cost,
sum( case trunk_id_termination when '' then null else 1 end ) as egress_ok_calls, 
count(case when split_part(release_cause_from_protocol_stack,':',1)='503' then release_cause_from_protocol_stack else null end) as egress_no_channel_calls,
sum(case when coalesce(is_final_call, '1')='1' and lrn_number_vendor::integer in (2,3) then 1 else 0 end) as lrn_calls
from client_cdr where time >= '{$start}' group by orig_country, orig_code_name, orig_code, ingress_client_id, trunk_id_origination, ingress_id, term_country, term_code_name, term_code, egress_client_id, trunk_id_termination, egress_id, time, termination_source_host_name, origination_source_host_name, termination_destination_host_name, ingress_client_rate_table_id, egress_rate_table_id) as client_cdr";
        } //开始时间大于当天0点
        elseif (strtotime($start) > strtotime($time_start_date)) {

            $statistic_start = date("Y-m-d 00:00:00", strtotime($start) + 24 * 3600) . ' ' . $_GET ['query']['tz']; //统计表查询的开始时间开始
            $cdr_start_end = date("Y-m-d 23:59:59", strtotime($start)) . ' ' . $_GET ['query']['tz']; //client_cdr查询的起始日期
            $cdr_end_start = date("Y-m-d 00:00:00", strtotime($end)) . ' ' . $_GET ['query']['tz']; //client_cdr查询结束的其实时间
            $statistic_end = date("Y-m-d 23:59:59", strtotime($end) - 24 * 3600) . ' ' . $_GET ['query']['tz'];
            //var_dump(date("Y-m-d H:i:s", strtotime($end)));
            //结束时间大于当天
            if (strtotime($end) > (strtotime(date("Y-m-d 00:00:00") . ' ' . $_GET ['query']['tz']))) {
                /*
                  return " (select orig_country, orig_code_name, orig_code, ingress_client_id, trunk_id_origination, ingress_id, term_country, term_code_name, term_code, egress_client_id, trunk_id_termination, egress_id, to_char(time, 'YYYY-MM-DD HH24:00:00 +00')::timestamp with time zone as time,
                  termination_source_host_name, origination_source_host_name,
                  termination_destination_host_name, sum(coalesce(is_final_call, '1')::integer) as ca,
                  sum( case when trunk_id_termination = '' then null else coalesce(is_final_call, '1')::integer end ) as ok_calls,
                  (sum ( case call_duration when '' then 0 else call_duration::numeric end))/60 ::numeric as call_duration,
                  sum(case ingress_client_cost when 'nan' then null else ingress_client_cost::numeric end)::numeric as ingress_cost,
                  sum(case egress_cost when 'nan' then null else egress_cost::numeric end)::numeric as egress_cost,
                  sum(ingress_bill_minutes::numeric) as orig_bill_minute,
                  sum(egress_bill_minutes::numeric) as term_bill_minute,
                  sum(coalesce(is_final_call, '1')::integer) as ingress_ca,
                  count(case egress_id when '' then null else egress_id end) as egress_ca,
                  count(NULLIF(call_duration , '0')) as not_zero_calls,
                  count(case when split_part(release_cause_from_protocol_stack,':',1)='486' then release_cause_from_protocol_stack else null end) as busy_calls,
                  count(case when release_cause::integer >=6 and release_cause::integer <=9 then release_cause else null end) as no_channel_calls,
                  ingress_client_rate_table_id,
                  egress_rate_table_id, sum( case when call_duration = '0' then 0 else pdd::integer end) as pdd,
                  sum( case trunk_id_termination when '' then null else 1 end ) as egress_ok_calls,
                  count(case when split_part(release_cause_from_protocol_stack,':',1)='503' then release_cause_from_protocol_stack else null end) as egress_no_channel_calls,
                  sum(case when coalesce(is_final_call, '1')='1' and lrn_number_vendor::integer in (2,3) then 1 else 0 end) as lrn_calls
                  from client_cdr where time between '{$start}' and '{$cdr_start_end}' group by orig_country, orig_code_name, orig_code, ingress_client_id, trunk_id_origination, ingress_id, term_country, term_code_name, term_code, egress_client_id, trunk_id_termination, egress_id, time, termination_source_host_name, origination_source_host_name, termination_destination_host_name, ingress_client_rate_table_id, egress_rate_table_id union all
                  select * from statistic_cdr where time between '{$statistic_start}' and '{$statistic_end}'
                  union all select orig_country, orig_code_name, orig_code, ingress_client_id, trunk_id_origination, ingress_id, term_country, term_code_name, term_code, egress_client_id, trunk_id_termination, egress_id, to_char(time, 'YYYY-MM-DD HH24:00:00 +00')::timestamp with time zone as time,
                  termination_source_host_name, origination_source_host_name,
                  termination_destination_host_name, sum(coalesce(is_final_call, '1')::integer) as ca,
                  sum( case when trunk_id_termination = '' then null else coalesce(is_final_call, '1')::integer end ) as ok_calls,
                  (sum ( case call_duration when '' then 0 else call_duration::numeric end))/60 ::numeric as call_duration,
                  sum(case ingress_client_cost when 'nan' then null else ingress_client_cost::numeric end)::numeric as ingress_cost,
                  sum(case egress_cost when 'nan' then null else egress_cost::numeric end)::numeric as egress_cost,
                  sum(ingress_bill_minutes::numeric) as orig_bill_minute,
                  sum(egress_bill_minutes::numeric) as term_bill_minute,
                  sum(coalesce(is_final_call, '1')::integer) as ingress_ca,
                  count(case egress_id when '' then null else egress_id end) as egress_ca,
                  count(NULLIF(call_duration , '0')) as not_zero_calls,
                  count(case when split_part(release_cause_from_protocol_stack,':',1)='486' then release_cause_from_protocol_stack else null end) as busy_calls,
                  count(case when release_cause::integer >=6 and release_cause::integer <=9 then release_cause else null end) as no_channel_calls,
                  ingress_client_rate_table_id,
                  egress_rate_table_id, sum( case when call_duration = '0' then 0 else pdd::integer end) as pdd,
                  sum( case trunk_id_termination when '' then null else 1 end ) as egress_ok_calls,
                  count(case when split_part(release_cause_from_protocol_stack,':',1)='503' then release_cause_from_protocol_stack else null end) as egress_no_channel_calls,
                  sum(case when coalesce(is_final_call, '1')='1' and lrn_number_vendor::integer in (2,3) then 1 else 0 end) as lrn_calls,sum(lnp_dipping_cost::numeric) as lnp_dipping_cost
                  from client_cdr where time >= '{$cdr_end_start}' group by orig_country, orig_code_name, orig_code, ingress_client_id, trunk_id_origination, ingress_id, term_country, term_code_name, term_code, egress_client_id, trunk_id_termination, egress_id, time, termination_source_host_name, origination_source_host_name, termination_destination_host_name, ingress_client_rate_table_id, egress_rate_table_id) as client_cdr";
                 */

                return "(select * from statistic_cdr where time between '{$start}' and '{$statistic_end}'
union all select orig_country, orig_code_name, orig_code, ingress_client_id, trunk_id_origination, ingress_id, term_country, term_code_name, term_code, egress_client_id, trunk_id_termination, egress_id, to_char(time, 'YYYY-MM-DD HH24:00:00 +00')::timestamp with time zone as time, 
termination_source_host_name, origination_source_host_name, 
termination_destination_host_name, sum(coalesce(is_final_call, '1')::integer) as ca, 
sum( case when trunk_id_termination = '' then null else coalesce(is_final_call, '1')::integer end ) as ok_calls, 
(sum ( case call_duration when '' then 0 else call_duration::numeric end))/60 ::numeric as call_duration, 
sum(case ingress_client_cost when 'nan' then null else ingress_client_cost::numeric end)::numeric as ingress_cost, 
sum(case egress_cost when 'nan' then null else egress_cost::numeric end)::numeric as egress_cost, 
sum(ingress_bill_minutes::numeric) as orig_bill_minute, 
sum(egress_bill_minutes::numeric) as term_bill_minute, 
sum(coalesce(is_final_call, '1')::integer) as ingress_ca, 
count(case egress_id when '' then null else egress_id end) as egress_ca, 
count(NULLIF(call_duration , '0')) as not_zero_calls, 
count(case when split_part(release_cause_from_protocol_stack,':',1)='486' then release_cause_from_protocol_stack else null end) as busy_calls, 
count(case when release_cause::integer >=6 and release_cause::integer <=9 then release_cause else null end) as no_channel_calls,
ingress_client_rate_table_id, 
egress_rate_table_id, sum( case when call_duration = '0' then 0 else pdd::integer end) as pdd,
sum( case trunk_id_termination when '' then null else 1 end ) as egress_ok_calls, 
count(case when split_part(release_cause_from_protocol_stack,':',1)='503' then release_cause_from_protocol_stack else null end) as egress_no_channel_calls, 
sum(case when coalesce(is_final_call, '1')='1' and lrn_number_vendor::integer in (2,3) then 1 else 0 end) as lrn_calls,sum(lnp_dipping_cost::numeric) as lnp_dipping_cost
from client_cdr where time >= '{$cdr_end_start}' group by orig_country, orig_code_name, orig_code, ingress_client_id, trunk_id_origination, ingress_id, term_country, term_code_name, term_code, egress_client_id, trunk_id_termination, egress_id, time, termination_source_host_name, origination_source_host_name, termination_destination_host_name, ingress_client_rate_table_id, egress_rate_table_id) as client_cdr";
            } else {
                return " (select orig_country, orig_code_name, orig_code, ingress_client_id, trunk_id_origination, ingress_id, term_country, term_code_name, term_code, egress_client_id, trunk_id_termination, egress_id, to_char(time, 'YYYY-MM-DD HH24:00:00 +00')::timestamp with time zone as time, 
termination_source_host_name, origination_source_host_name, 
termination_destination_host_name, sum(coalesce(is_final_call, '1')::integer) as ca, 
sum( case when trunk_id_termination = '' then null else coalesce(is_final_call, '1')::integer end ) as ok_calls, 
(sum ( case call_duration when '' then 0 else call_duration::numeric end))/60 ::numeric as call_duration, 
sum(case ingress_client_cost when 'nan' then null else ingress_client_cost::numeric end)::numeric as ingress_cost, 
sum(case egress_cost when 'nan' then null else egress_cost::numeric end)::numeric as egress_cost, 
sum(ingress_bill_minutes::numeric) as orig_bill_minute, 
sum(egress_bill_minutes::numeric) as term_bill_minute, 
sum(coalesce(is_final_call, '1')::integer) as ingress_ca, 
count(case egress_id when '' then null else egress_id end) as egress_ca, 
count(NULLIF(call_duration , '0')) as not_zero_calls, 
count(case when split_part(release_cause_from_protocol_stack,':',1)='486' then release_cause_from_protocol_stack else null end) as busy_calls, 
count(case when release_cause::integer >=6 and release_cause::integer <=9 then release_cause else null end) as no_channel_calls,
ingress_client_rate_table_id, 
egress_rate_table_id, sum( case when call_duration = '0' then 0 else pdd::integer end) as pdd,
sum( case trunk_id_termination when '' then null else 1 end ) as egress_ok_calls, 
count(case when split_part(release_cause_from_protocol_stack,':',1)='503' then release_cause_from_protocol_stack else null end) as egress_no_channel_calls,
sum(case when coalesce(is_final_call, '1')='1' and lrn_number_vendor::integer in (2,3) then 1 else 0 end) as lrn_calls,sum(lnp_dipping_cost::numeric) as lnp_dipping_cost
from client_cdr where time between '{$start}' and '{$cdr_start_end}' group by orig_country, orig_code_name, orig_code, ingress_client_id, trunk_id_origination, ingress_id, term_country, term_code_name, term_code, egress_client_id, trunk_id_termination, egress_id, time, termination_source_host_name, origination_source_host_name, termination_destination_host_name, ingress_client_rate_table_id, egress_rate_table_id union all
select * from statistic_cdr where time between '{$statistic_start}' and '{$end}') as client_cdr";
            }
        } elseif (strtotime($end) > (strtotime(date("Y-m-d 00:00:00") . ' ' . $_GET ['query']['tz']))) {//$sql[0][0]['num'] > 0 && 
            $statistic_start = date("Y-m-d 00:00:00", strtotime($start) + 24 * 3600) . ' ' . $_GET ['query']['tz']; //统计表查询的开始时间开始
            $cdr_start_end = date("Y-m-d 23:59:59", strtotime($start)) . ' ' . $_GET ['query']['tz']; //client_cdr查询的起始日期
            $cdr_end_start = date("Y-m-d 00:00:00", strtotime($end)) . ' ' . $_GET ['query']['tz']; //client_cdr查询结束的其实时间
            $statistic_end = date("Y-m-d 23:59:59", strtotime($end) - 24 * 3600) . ' ' . $_GET ['query']['tz'];

            return "(select * from statistic_cdr where time between '{$start}' and '{$statistic_end}'
union all select orig_country, orig_code_name, orig_code, ingress_client_id, trunk_id_origination, ingress_id, term_country, term_code_name, term_code, egress_client_id, trunk_id_termination, egress_id, to_char(time, 'YYYY-MM-DD HH24:00:00 +00')::timestamp with time zone as time, 
termination_source_host_name, origination_source_host_name, 
termination_destination_host_name, sum(coalesce(is_final_call, '1')::integer) as ca, 
sum( case when trunk_id_termination = '' then null else coalesce(is_final_call, '1')::integer end ) as ok_calls, 
(sum ( case call_duration when '' then 0 else call_duration::numeric end))/60 ::numeric as call_duration, 
sum(case ingress_client_cost when 'nan' then null else ingress_client_cost::numeric end)::numeric as ingress_cost, 
sum(case egress_cost when 'nan' then null else egress_cost::numeric end)::numeric as egress_cost, 
sum(ingress_bill_minutes::numeric) as orig_bill_minute, 
sum(egress_bill_minutes::numeric) as term_bill_minute, 
sum(coalesce(is_final_call, '1')::integer) as ingress_ca, 
count(case egress_id when '' then null else egress_id end) as egress_ca, 
count(NULLIF(call_duration , '0')) as not_zero_calls, 
count(case when split_part(release_cause_from_protocol_stack,':',1)='486' then release_cause_from_protocol_stack else null end) as busy_calls, 
count(case when release_cause::integer >=6 and release_cause::integer <=9 then release_cause else null end) as no_channel_calls,
ingress_client_rate_table_id, 
egress_rate_table_id, sum( case when call_duration = '0' then 0 else pdd::integer end) as pdd,
sum( case trunk_id_termination when '' then null else 1 end ) as egress_ok_calls, 
count(case when split_part(release_cause_from_protocol_stack,':',1)='503' then release_cause_from_protocol_stack else null end) as egress_no_channel_calls, 
sum(case when coalesce(is_final_call, '1')='1' and lrn_number_vendor::integer in (2,3) then 1 else 0 end) as lrn_calls,sum(lnp_dipping_cost::numeric) as lnp_dipping_cost
from client_cdr where time >= '{$cdr_end_start}' group by orig_country, orig_code_name, orig_code, ingress_client_id, trunk_id_origination, ingress_id, term_country, term_code_name, term_code, egress_client_id, trunk_id_termination, egress_id, time, termination_source_host_name, origination_source_host_name, termination_destination_host_name, ingress_client_rate_table_id, egress_rate_table_id) as client_cdr";
        } else {
            return 'statistic_cdr as client_cdr';
        }
    }

    function get_start_end_time()
    {
        if (isset($_GET['open_callmonitor']) && $_GET['open_callmonitor'] == 1) {
            $_GET ['query']['tz'] = '+0000';

            $min_start = $_GET ['min_start_date'] . '  ' . $_GET ['min_start_time'] . ' ' . $_GET ['query']['tz']; //开始时间
            $max_end = $_GET ['max_stop_date'] . '  ' . $_GET ['max_stop_time'] . '  ' . $_GET ['query']['tz']; //结束时间

            if (!isset($_GET ['start_date']) && empty($_GET ['start_date'])) {
                $_GET['start_date'] = $_GET['min_start_date'];
                $_GET['start_time'] = $_GET['min_start_time'];
                $_GET['stop_date'] = $_GET['max_stop_date'];
                $_GET['stop_time'] = $_GET['max_stop_time'];
            }
            $start = $_GET ['start_date'] . '  ' . $_GET ['start_time'] . ' ' . $_GET ['query']['tz']; //开始时间
            $end = $_GET ['stop_date'] . '  ' . $_GET ['stop_time'] . '  ' . $_GET ['query']['tz']; //结束时间

            $min_start_timestamp = strtotime($min_start);
            $cur_start_timestamp = strtotime($start);

            $max_end_timestamp = strtotime($max_end);
            $cur_end_timestamp = strtotime($end);


            if ($cur_start_timestamp < $min_start_timestamp || $cur_start_timestamp > $max_end_timestamp) {
                $start = $min_start;
                $_GET['start_date'] = $_GET['min_start_date'];
                $_GET['start_time'] = $_GET['min_start_time'];
            }

            if ($cur_end_timestamp > $max_end_timestamp || $cur_end_timestamp < $min_start_timestamp) {
                $end = $max_end;
                $_GET['stop_date'] = $_GET['max_stop_date'];
                $_GET['stop_time'] = $_GET['max_stop_time'];
            }
        } else if (!empty($_GET ['start_date']) && !empty($_GET ['start_time']) && !empty($_GET ['stop_date']) && !empty($_GET ['stop_time']) && !empty($_GET ['query']['tz'])) {
            $start = $_GET ['start_date'] . '  ' . $_GET ['start_time'] . ' ' . $_GET ['query']['tz']; //开始时间
            $end = $_GET ['stop_date'] . '  ' . $_GET ['stop_time'] . '  ' . $_GET ['query']['tz']; //结束时间
        } else {
            #report deault query time
            extract($this->Cdr->get_real_period());
        }
        $this->report_query_time = array('start' => $start, 'end' => $end);
        return compact('start', 'end');
    }


    function capture_report_condtions1($report_type)
    {

        $invalid_ingress_ip_where = isset($_GET ['invalid_ingress_ip']) ? " and release_cause =3" : '';
        $no_product_found_where = isset($_GET ['no_product_found']) ? " and release_cause =6" : '';
        $no_code_found_where = isset($_GET ['no_code_found']) ? " and release_cause =11" : '';


// filter egress_cost NaN
//$NaN_egress_cost_where="and egress_cost !='nan'";
// $NaN_ingress_cost_where="and ingress_client_cost !='nan'";
//通话异常
        $spam_where = " and release_cause <>19";
//response from egress
        $response_from_egress_where = !empty($_GET ['query'] ['disconnect_cause']) ? " and release_cause_from_protocol_stack like'%{$_GET ['query'] ['disconnect_cause']}%'" : '';

//response to ingress
        $response_to_ingress_where = !empty($_GET ['query'] ['disconnect_cause_ingress']) ? " and binary_value_of_release_cause_from_protocol_stack like'%{$_GET ['query'] ['disconnect_cause_ingress']}%'" : '';


//Release cause
        $cdr_release_cause_where = (isset($_GET ['cdr_release_cause']) && $_GET ['cdr_release_cause'] != '') ? " and release_cause ={$_GET ['cdr_release_cause']}" : '';

        $origination_source_host_name_where = !empty($_GET ['query'] ['origination_source_host_name']) ? " and origination_source_host_name like'%{$_GET ['query'] ['origination_source_host_name']}%'" : '';

//主叫号
        $source_number_where = '';
//        $source_number_where = !empty($_GET ['query'] ['src_number']) ? " and origination_source_number like '%{$_GET ['query'] ['src_number']}%' " : '';

        $term_number_where = '';
//        $term_number_where = !empty($_GET ['query'] ['term_src_number']) ? " and termination_source_number like '%{$_GET ['query'] ['term_src_number']}%' " : '';
//被叫号
        $dnis_num_arr = explode(",", !empty($_GET ['query'] ['dst_number']) ? $_GET ['query'] ['dst_number'] : '');
        $destination_number_where = '';
        if (!empty($dnis_num_arr)) {
            foreach ($dnis_num_arr as $k => $v) {
                if (!empty($_GET['query']['dnis_type'])) {
                    $destination_number_where .= !empty($_GET ['query'] ['dst_number']) ? " and not origination_destination_number like '%{$v}%'" : '';
                } else {
                    $destination_number_where .= !empty($_GET ['query'] ['dst_number']) ? " and origination_destination_number like '%{$v}%'" : '';
                }
            }
        }
        $where_arr = array();
        if (isset($_GET['ingress_rate_table']) && $_GET['ingress_rate_table'] != 'all') {
            array_push($where_arr, "ingress_rate_table_id = {$_GET['ingress_rate_table']}");
        }
        if (isset($_GET['ingress_routing_plan']) && $_GET['ingress_routing_plan'] != 'all') {
            array_push($where_arr, "route_plan_id = {$_GET['ingress_routing_plan']}");
        }
        if (isset($_GET['orig_rate_type']) && $_GET['orig_rate_type'] != '0') {
            if ($_GET['orig_rate_type'] == '1') {
                array_push($where_arr, "orig_jur_type = 0");
            } elseif ($_GET['orig_rate_type'] == '2') {
                array_push($where_arr, "orig_jur_type in (1, 2)");
            } elseif ($_GET['orig_rate_type'] == '3') {
                array_push($where_arr, "orig_jur_type in (3, 4)");
            }
        }
        if (isset($_GET['term_rate_type']) && $_GET['term_rate_type'] != '0') {
            if ($_GET['term_rate_type'] == '1') {
                array_push($where_arr, "term_jur_type = 0");
            } elseif ($_GET['term_rate_type'] == '2') {
                array_push($where_arr, "term_jur_type in (1, 2)");
            } elseif ($_GET['term_rate_type'] == '3') {
                array_push($where_arr, "term_jur_type in (3, 4)");
            }
        }
        if (!empty($where_arr)) {
            $where_new = " and " . implode(" and ", $where_arr);
        } else {
            $where_new = '';
        }


        $term_dnis_num_arr = explode(",", !empty($_GET ['query'] ['term_dst_number']) ? $_GET ['query'] ['term_dst_number'] : '');
        $term_destination_number_where = "";
        if (!empty($term_dnis_num_arr[0])) {
            foreach ($term_dnis_num_arr as $k => $v) {
                if (!empty($_GET['query']['dnis_type'])) {
                    $term_destination_number_where .= !empty($_GET ['query'] ['term_dst_number']) ? " and not termination_destination_number like '%{$v}%'" : '';
                } else {
                    $term_destination_number_where .= !empty($_GET ['query'] ['term_dst_number']) ? " and termination_destination_number like '%{$v}%'" : '';
                }
            }
        }


//主叫号
//        $source_number_where .= !empty($_GET ['query'] ['src_code']) ? " and orig_code::prefix_range<@'{$_GET ['query'] ['src_code']}' " : '';
//被叫号
        $dnis_num_arr = explode(",", !empty($_GET ['query'] ['dst_code']) ? $_GET ['query'] ['dst_code'] : '');
        if (!empty($dnis_num_arr)) {
            foreach ($dnis_num_arr as $k => $v) {
                if (!empty($_GET['query']['dnis_type'])) {
                    $destination_number_where .= !empty($_GET ['query'] ['dst_code']) ? " and not term_code::prefix_range<@'{$v}'::prefix_range" : '';
                } else {
//                    $destination_number_where .= !empty($_GET ['query'] ['dst_code']) ? " and term_code::prefix_range<@'{$v}'::prefix_range" : '';
                }
            }
        }

        $orig_term_call_id_where = !empty($_GET ['query'] ['term_call_id']) ? " and termination_call_id = '" . trim($_GET ['query'] ['term_call_id']) . "' " : '';
        $orig_term_call_id_where .= !empty($_GET ['query'] ['orig_call_id']) ? " and origination_call_id = '" . trim($_GET ['query'] ['orig_call_id']) . "' " : '';

//通话时长
        $duration_where = '';
        if (isset($_GET ['query'] ['duration'])) {
            $duration = $_GET ['query'] ['duration'];
            if (!empty($duration)) {
                if ($duration == 'nonzero') {
                    $duration_where = " and case when call_duration is null then 0 else call_duration end>0";
                }
                if ($duration == 'zero') {
                    $duration_where = " and case when call_duration is null then 0 else call_duration end=0";
                }
            }
        }
//通话间隔
// $interval_from_where =!empty($_GET ['query'] ['interval_from'])? " and ( case when call_duration='' then '0' else call_duration end ::integer>={$_GET ['query'] ['interval_from']})":'';
// $interval_to_where = !empty($_GET ['query'] ['interval_to'])?" and case when call_duration='' then '0' else call_duration end ::integer<={$_GET ['query'] ['interval_to']}":'';
        $interval_from_where = !empty($_GET ['query'] ['interval_from']) ? " and call_duration>={$_GET ['query'] ['interval_from']}" : '';
        $interval_to_where = !empty($_GET ['query'] ['interval_to']) ? " and call_duration<={$_GET ['query'] ['interval_to']}" : '';


//扣费
        $orig_cost_where = '';
        if (isset($_GET ['query'] ['cost'])) {
            $orig_cost = $_GET ['query'] ['cost'];
            if (!empty($orig_cost)) {
                if ($orig_cost == 'nonzero') {
                    $orig_cost_where = " and ingress_client_cost>0";
                }
                if ($orig_cost == 'zero') {
                    $orig_cost_where = " and ingress_client_cost=0.000";
                }
            }
        }


        $term_cost_where = '';
        if (isset($_GET ['query'] ['cost_term'])) {
            $term_cost = $_GET ['query'] ['cost_term'];
            if (!empty($term_cost)) {
                if ($term_cost == 'nonzero') {
                    $term_cost_where = " and egress_cost>0";
                }
                if ($term_cost == 'zero') {
                    $term_cost_where = " and egress_cost=0.000";
                }
            }
        }


        $country_no_null_where = " and term_country <>'' ";

        $org_client_where = !empty($_GET ['query'] ['id_clients']) ? " and ingress_client_id='{$_GET ['query'] ['id_clients']}'" : '';
        $term_client_where = !empty($_GET ['query'] ['id_clients_term']) ? " and egress_client_id='{$_GET ['query'] ['id_clients_term']}'" : '';

//        if ($report_type == 'orig_discon_report') {
//            $route_prefix_where = !empty($_GET ['route_prefix']) ? " and ingress_prefix='{$_GET ['route_prefix']}'" : '';
//        } else {
//            $route_prefix_where = !empty($_GET ['route_prefix']) ? " and route_prefix='{$_GET ['route_prefix']}'" : '';
//        }
        $route_prefix_where = !empty($_GET ['route_prefix']) ? " and ingress_prefix='{$_GET ['route_prefix']}'" : '';

        $org_carrier_select_where = !empty($_GET ['orig_carrier_select']) ? " and ingress_client_id={$_GET ['orig_carrier_select']}" : '';
        $term_carrier_select_where = !empty($_GET ['term_carrier_select']) ? " and egress_client_id={$_GET ['term_carrier_select'] }" : '';


        $org_client_name_where = !empty($_GET ['query'] ['id_clients_name']) ? " and orig_client.name='{$_GET ['query'] ['id_clients_name']}'" : '';
        $term_client_name_where = !empty($_GET ['query'] ['id_clients_name_term']) ? " and term_client.name='{$_GET ['query'] ['id_clients_name_term']}'" : '';


        $org_code_name_where = !empty($_GET ['query'] ['code_name']) ? " and ingress_code_name like '%{$_GET ['query'] ['code_name']}%'" : '';
        $term_code_name_where = !empty($_GET ['query'] ['code_name_term']) ? " and egress_code_name like '%{$_GET ['query'] ['code_name_term']}%'" : '';

        $org_code_where = '';
        $term_code_where = '';
//        $org_code_where = !empty($_GET ['query'] ['code']) ? " and orig_code::prefix_range <@ '{$_GET ['query'] ['code']}'" : '';
//        $term_code_where = !empty($_GET ['query'] ['code_term']) ? " and term_code::prefix_range <@ '{$_GET ['query'] ['code_term']}'" : '';


        $org_country_where = !empty($_GET ['query'] ['country']) ? " and ingress_country like '%{$_GET ['query'] ['country']}%'" : '';
        $term_country_where = !empty($_GET ['query'] ['country_term']) ? " and egress_country like '%{$_GET ['query'] ['country_term']}%'" : '';


        $server_where = !empty($_GET ['server_ip']) ? " and origination_destination_host_name ='{$_GET ['server_ip']}'" : '';

        $egress_where = !empty($_GET ['egress_alias']) ? " and egress_id={$_GET ['egress_alias']}" : '';
        $ingress_where = !empty($_GET ['ingress_alias']) ? " and ingress_id={$_GET ['ingress_alias']}" : '';


        $orig_host_where = !empty($_GET ['orig_host']) ? " and origination_source_host_name='{$_GET ['orig_host']}'" : '';
        $term_host_where = !empty($_GET ['term_host']) ? " and termination_destination_host_name='{$_GET ['term_host']}'" : '';


        $ingress_rate_table_name = !empty($_GET ['query'] ['rate_name']) ? " and ingress_client_rate_table_id in (select rate_table_id from rate_table where name = '{$_GET ['query'] ['rate_name']}')" : '';
        $egress_rate_table_name = !empty($_GET ['query'] ['rate_name_term']) ? " and egress_rate_table_id in (select rate_table_id from rate_table where name = '{$_GET ['query'] ['rate_name_term']}')" : '';
//费率
        $term_rate_where = !empty($_GET ['query'] ['id_rates_term']) ? " and egress_rate_table_id ={$_GET ['query'] ['id_rates_term']}" : '';
        $orig_rate_where = !empty($_GET ['query'] ['id_rates']) ? " and ingress_client_rate_table_id={$_GET ['query'] ['id_rates']}" : '';

        $lrn_dnis_where = !empty($_GET ['query'] ['lrn_dnis']) ? " and lrn_dnis ='{$_GET ['query'] ['lrn_dnis']}'" : '';

        $add1 = !empty($_GET ['query'] ['lrn_number']) ? " and lrn_dnis ='{$_GET ['query'] ['lrn_number']}'" : '';
        $add2 = !empty($_GET ['query'] ['lrn_number_vendor']) ? " and lrn_number_vendor ={$_GET ['query'] ['lrn_number_vendor']}" : '';

//suppress filter
        $supp_orig_rate_where = !empty($_GET ['query'] ['supp_orig_rate']) ? " and ingress_client_rate_table_id not in (" . join_str(',', $_GET['query']['supp_orig_rate']) . ")" : '';
        $supp_term_rate_where = !empty($_GET ['query'] ['supp_term_rate']) ? " and egress_rate_table_id not in (" . join_str(',', $_GET['query']['supp_term_rate']) . ")" : '';

        $supp_ingress_where = !empty($_GET ['query'] ['supp_ingress']) ? " and ingress_id not in (" . join_str(',', $_GET['query']['supp_ingress']) . ")" : '';
        $supp_egress_where = !empty($_GET ['query'] ['supp_egress']) ? " and egress_id not in (" . join_str(',', $_GET['query']['supp_egress']) . ")" : '';

        if ($this->params['action'] == 'summary_reports' || $this->params['controller'] == 'dips') {
            $supp_orig_host_where = !empty($_GET ['query'] ['supp_orig_host']) ? " and origination_source_host_name in (" . join_str(',', $_GET['query']['supp_orig_host']) . ")" : '';
            $supp_term_host_where = !empty($_GET ['query'] ['supp_term_host']) ? " and termination_destination_host_name in (" . join_str(',', $_GET['query']['supp_term_host']) . ")" : '';
        } else {
            $supp_orig_host_where = !empty($_GET ['query'] ['supp_orig_host']) ? " and origination_source_host_name not in (" . join_str(',', $_GET['query']['supp_orig_host']) . ")" : '';
            $supp_term_host_where = !empty($_GET ['query'] ['supp_term_host']) ? " and termination_destination_host_name not in (" . join_str(',', $_GET['query']['supp_term_host']) . ")" : '';
        }

//权限
        $privilege_where = '';

        if ($_SESSION ['login_type'] == 3) {
//$privilege_where = !empty($_SESSION['sst_client_id']) ? " and ( egress_client_id = {$_SESSION['sst_client_id']} or ingress_client_id = {$_SESSION['sst_client_id']})" : '';
            $cdr_type = isset($this->params['pass'][0]) ? $this->params['pass'][0] : 'buy';
            if ($cdr_type == 'buy') {
                $privilege_where = !empty($_SESSION['sst_client_id']) ? " and (ingress_client_id = {$_SESSION['sst_client_id']})" : '';
            } else {
                $privilege_where = !empty($_SESSION['sst_client_id']) ? " and (egress_client_id = {$_SESSION['sst_client_id']})" : '';
            }
        }
        if (!empty($_GET ['start_date']) && !empty($_GET ['start_time']) && !empty($_GET ['stop_date']) && !empty($_GET ['stop_time']) && !empty($_GET ['query']['tz'])) {
            $start = $_GET ['start_date'] . ' ' . $_GET ['start_time'] . ' ' . $_GET ['query']['tz']; //开始时间
            $end = $_GET ['stop_date'] . ' ' . $_GET ['stop_time'] . ' ' . $_GET ['query']['tz']; //结束时间
        } else {
#report deault query time
            extract($this->Cdr->get_real_period());
        }
        $this->report_query_time = array('start' => $start, 'end' => $end);
        $this->set("start", $start);
        $this->set("end", $end);
        $start = local_time_to_gmt($start);
        $end = local_time_to_gmt($end);

        $sst_user_id = $_SESSION['sst_user_id'];
        if ($_SESSION ['login_type'] == 3) {
            $filter_client = '';
        } else {
            $filter_client = "and
(exists(SELECT id FROM users_limit WHERE user_id = {$sst_user_id} AND client_id=cdr_report.ingress_client_id) OR exists
(SELECT users.user_id FROM users LEFT JOIN sys_role ON users.role_id = sys_role.role_id WHERE users.user_id = {$sst_user_id}
and (role_name = 'admin' or sys_role.view_all = true)))";
        }
//return "time between '$start' and '$end' $org_country_where $org_code_where $org_code_name_where $term_country_where $term_code_where $term_code_name_where $org_client_where $term_client_where $org_client_name_where $term_client_name_where $ingress_where $egress_where $interval_from_where $term_rate_where $orig_rate_where $cdr_release_cause_where $interval_to_where $response_from_egress_where $response_to_ingress_where $duration_where $orig_cost_where $term_cost_where $source_number_where $destination_number_where $server_where $privilege_where $orig_host_where $term_host_where $invalid_ingress_ip_where $no_product_found_where $no_code_found_where $supp_egress_where $supp_ingress_where $supp_orig_host_where $supp_orig_rate_where $supp_term_host_where $supp_term_rate_where $org_carrier_select_where $term_carrier_select_where ";
        return "report_time between '$start' and '$end' $org_country_where $add1 $add2 $ingress_rate_table_name $egress_rate_table_name $org_code_where $lrn_dnis_where $org_code_name_where $term_country_where $term_number_where	$term_code_where $term_code_name_where $org_client_where $term_client_where $org_client_name_where $term_client_name_where $ingress_where $egress_where	$interval_from_where $term_rate_where $orig_rate_where $cdr_release_cause_where $interval_to_where	$response_from_egress_where $response_to_ingress_where $duration_where $orig_cost_where $term_cost_where $source_number_where $destination_number_where $orig_term_call_id_where	$server_where $privilege_where $orig_host_where $term_host_where $invalid_ingress_ip_where $no_product_found_where $no_code_found_where $supp_egress_where $supp_ingress_where $supp_orig_host_where $supp_orig_rate_where	$supp_term_host_where $supp_term_rate_where $term_destination_number_where $where_new $org_carrier_select_where $term_carrier_select_where $route_prefix_where $origination_source_host_name_where $filter_client ";
    }

    /**
     *
     * @return where 条件
     *
     */
    function capture_report_condtions($report_type)
    {

        $invalid_ingress_ip_where = isset($_GET ['invalid_ingress_ip']) ? " and release_cause =3" : '';
        $no_product_found_where = isset($_GET ['no_product_found']) ? " and release_cause =6" : '';
        $no_code_found_where = isset($_GET ['no_code_found']) ? " and release_cause =11" : '';


        // filter egress_cost  NaN  
        //$NaN_egress_cost_where="and  egress_cost !='nan'";
        //	$NaN_ingress_cost_where="and  ingress_client_cost !='nan'";
        //通话异常
        $spam_where = " and release_cause <>19";
        //response from egress
        $response_from_egress_where = !empty($_GET ['query'] ['disconnect_cause']) ? " and release_cause_from_protocol_stack  like'%{$_GET ['query'] ['disconnect_cause']}%'" : '';

        //response to ingress
        $response_to_ingress_where = !empty($_GET ['query'] ['disconnect_cause_ingress']) ? " and binary_value_of_release_cause_from_protocol_stack  like'%{$_GET ['query'] ['disconnect_cause_ingress']}%'" : '';


        //Release cause
        $cdr_release_cause_where = (isset($_GET ['cdr_release_cause']) && $_GET ['cdr_release_cause'] != '') ? " and release_cause  ={$_GET ['cdr_release_cause']}" : '';


        $origination_source_host_name_where = !empty($_GET ['query'] ['origination_source_host_name']) ? " and origination_source_host_name  like'%{$_GET ['query'] ['origination_source_host_name']}%'" : '';

        //主叫号
        $source_number_where = !empty($_GET ['query'] ['src_number']) ? " and origination_source_number like '%{$_GET ['query'] ['src_number']}%' " : '';

        $term_number_where = !empty($_GET ['query'] ['term_src_number']) ? " and termination_source_number like '%{$_GET ['query'] ['term_src_number']}%' " : '';
        //被叫号
        $dnis_num_arr = explode(",", !empty($_GET ['query'] ['dst_number']) ? $_GET ['query'] ['dst_number'] : '');
        $destination_number_where = '';
        if (!empty($dnis_num_arr)) {
            foreach ($dnis_num_arr as $k => $v) {
                if (!empty($_GET['query']['dnis_type'])) {
                    $destination_number_where .= !empty($_GET ['query'] ['dst_number']) ? " and not origination_destination_number like '%{$v}%'" : '';
                } else {
                    $destination_number_where .= !empty($_GET ['query'] ['dst_number']) ? " and origination_destination_number like '%{$v}%'" : '';
                }
            }
        }

        $where_arr = array();

        if (isset($_GET['ingress_rate_table']) && $_GET['ingress_rate_table'] != 'all') {
            array_push($where_arr, "ingress_client_rate_table_id = {$_GET['ingress_rate_table']}");
        }

        if (isset($_GET['ingress_routing_plan']) && $_GET['ingress_routing_plan'] != 'all') {

            array_push($where_arr, "route_plan = {$_GET['ingress_routing_plan']}");
        }

        if (isset($_GET['orig_rate_type']) && $_GET['orig_rate_type'] != '0') {
            if ($_GET['orig_rate_type'] == '1') {
                array_push($where_arr, "orig_jur_type = 0");
            } elseif ($_GET['orig_rate_type'] == '2') {
                array_push($where_arr, "orig_jur_type in (1, 2)");
            } elseif ($_GET['orig_rate_type'] == '3') {
                array_push($where_arr, "orig_jur_type in (3, 4)");
            }
        }

        if (isset($_GET['term_rate_type']) && $_GET['term_rate_type'] != '0') {
            if ($_GET['term_rate_type'] == '1') {
                array_push($where_arr, "term_jur_type = 0");
            } elseif ($_GET['term_rate_type'] == '2') {
                array_push($where_arr, "term_jur_type in (1, 2)");
            } elseif ($_GET['term_rate_type'] == '3') {
                array_push($where_arr, "term_jur_type in (3, 4)");
            }
        }

        if (!empty($where_arr)) {
            $where_new = " and " . implode(" and ", $where_arr);
        } else {
            $where_new = '';
        }


        $term_dnis_num_arr = explode(",", !empty($_GET ['query'] ['term_dst_number']) ? $_GET ['query'] ['term_dst_number'] : '');
        $term_destination_number_where = "";
        if (!empty($term_dnis_num_arr[0])) {
            foreach ($term_dnis_num_arr as $k => $v) {
                if (!empty($_GET['query']['dnis_type'])) {
                    $term_destination_number_where .= !empty($_GET ['query'] ['term_dst_number']) ? " and not termination_destination_number like '%{$v}%'" : '';
                } else {
                    $term_destination_number_where .= !empty($_GET ['query'] ['term_dst_number']) ? " and termination_destination_number like '%{$v}%'" : '';
                }
            }
        }


        //主叫号
//        $source_number_where .= !empty($_GET ['query'] ['src_code']) ? " and orig_code::prefix_range<@'{$_GET ['query'] ['src_code']}' " : '';
        //被叫号
        $dnis_num_arr = explode(",", !empty($_GET ['query'] ['dst_code']) ? $_GET ['query'] ['dst_code'] : '');
        if (!empty($dnis_num_arr)) {
            foreach ($dnis_num_arr as $k => $v) {
                if (!empty($_GET['query']['dnis_type'])) {
                    $destination_number_where .= !empty($_GET ['query'] ['dst_code']) ? " and not term_code::prefix_range<@'{$v}'::prefix_range" : '';
                } else {
                    $destination_number_where .= !empty($_GET ['query'] ['dst_code']) ? " and term_code::prefix_range<@'{$v}'::prefix_range" : '';
                }
            }
        }

        $orig_term_call_id_where = !empty($_GET ['query'] ['term_call_id']) ? " and termination_call_id = '" . trim($_GET ['query'] ['term_call_id']) . "' " : '';
        $orig_term_call_id_where .= !empty($_GET ['query'] ['orig_call_id']) ? " and origination_call_id = '" . trim($_GET ['query'] ['orig_call_id']) . "' " : '';

        //通话时长
        $duration_where = '';
        if (isset($_GET ['query'] ['duration'])) {
            $duration = $_GET ['query'] ['duration'];
            if (!empty($duration)) {
                if ($duration == 'nonzero') {
                    $duration_where = " and case  when call_duration is null then 0  else call_duration end>0";
                }
                if ($duration == 'zero') {
                    $duration_where = " and case  when call_duration is null then 0  else call_duration end=0";
                }
            }
        }
        //通话间隔
//		$interval_from_where =!empty($_GET ['query'] ['interval_from'])? " and (  case  when call_duration='' then '0' else call_duration end ::integer>={$_GET ['query'] ['interval_from']})":'';
//		$interval_to_where = !empty($_GET ['query'] ['interval_to'])?" and  case  when call_duration=''  then '0'  else call_duration end ::integer<={$_GET ['query'] ['interval_to']}":'';
        $interval_from_where = !empty($_GET ['query'] ['interval_from']) ? " and call_duration>={$_GET ['query'] ['interval_from']}" : '';
        $interval_to_where = !empty($_GET ['query'] ['interval_to']) ? " and  call_duration<={$_GET ['query'] ['interval_to']}" : '';


        //扣费
        $orig_cost_where = '';
        if (isset($_GET ['query'] ['cost'])) {
            $orig_cost = $_GET ['query'] ['cost'];
            if (!empty($orig_cost)) {
                if ($orig_cost == 'nonzero') {
                    $orig_cost_where = " and ingress_client_cost>0";
                }
                if ($orig_cost == 'zero') {
                    $orig_cost_where = " and ingress_client_cost=0.000";
                }
            }
        }


        $term_cost_where = '';
        if (isset($_GET ['query'] ['cost_term'])) {
            $term_cost = $_GET ['query'] ['cost_term'];
            if (!empty($term_cost)) {
                if ($term_cost == 'nonzero') {
                    $term_cost_where = " and egress_cost>0";
                }
                if ($term_cost == 'zero') {
                    $term_cost_where = " and egress_cost=0.000";
                }
            }
        }


        $country_no_null_where = "  and   term_country  <>'' ";

        $org_client_where = !empty($_GET ['query'] ['id_clients']) ? " and ingress_client_id='{$_GET ['query'] ['id_clients']}'" : '';
        $term_client_where = !empty($_GET ['query'] ['id_clients_term']) ? " and egress_client_id='{$_GET ['query'] ['id_clients_term']}'" : '';

//        if ($report_type == 'orig_discon_report') {
//            $route_prefix_where = !empty($_GET ['route_prefix']) ? " and ingress_prefix='{$_GET ['route_prefix']}'" : '';
//        } else {
//            $route_prefix_where = !empty($_GET ['route_prefix']) ? " and route_prefix='{$_GET ['route_prefix']}'" : '';
//        }
        $route_prefix_where = !empty($_GET ['route_prefix']) ? " and ingress_prefix='{$_GET ['route_prefix']}'" : '';

        $org_carrier_select_where = !empty($_GET ['orig_carrier_select']) ? " and ingress_client_id={$_GET ['orig_carrier_select']}" : '';
        $term_carrier_select_where = !empty($_GET ['term_carrier_select']) ? " and egress_client_id={$_GET ['term_carrier_select'] }" : '';


        $org_client_name_where = !empty($_GET ['query'] ['id_clients_name']) ? " and orig_client.name='{$_GET ['query'] ['id_clients_name']}'" : '';
        $term_client_name_where = !empty($_GET ['query'] ['id_clients_name_term']) ? " and term_client.name='{$_GET ['query'] ['id_clients_name_term']}'" : '';


        $org_code_name_where = !empty($_GET ['query'] ['code_name']) ? " and orig_code_name   like '%{$_GET ['query'] ['code_name']}%'" : '';
        $term_code_name_where = !empty($_GET ['query'] ['code_name_term']) ? " and term_code_name  like '%{$_GET ['query'] ['code_name_term']}%'" : '';


        $org_code_where = !empty($_GET ['query'] ['code']) ? " and orig_code::prefix_range <@ '{$_GET ['query'] ['code']}'" : '';
        $term_code_where = !empty($_GET ['query'] ['code_term']) ? " and term_code::prefix_range <@ '{$_GET ['query'] ['code_term']}'" : '';


        $org_country_where = !empty($_GET ['query'] ['country']) ? " and orig_country  like '%{$_GET ['query'] ['country']}%'" : '';
        $term_country_where = !empty($_GET ['query'] ['country_term']) ? " and term_country  like '%{$_GET ['query'] ['country_term']}%'" : '';


        $server_where = !empty($_GET ['server_ip']) ? " and origination_destination_host_name ='{$_GET ['server_ip']}'" : '';

        $egress_where = !empty($_GET ['egress_alias']) ? "  and egress_id={$_GET ['egress_alias']}" : '';
        $ingress_where = !empty($_GET ['ingress_alias']) ? "  and ingress_id={$_GET ['ingress_alias']}" : '';


        $orig_host_where = !empty($_GET ['orig_host']) ? "  and origination_source_host_name='{$_GET ['orig_host']}'" : '';
        $term_host_where = !empty($_GET ['term_host']) ? "  and termination_destination_host_name='{$_GET ['term_host']}'" : '';


        $ingress_rate_table_name = !empty($_GET ['query'] ['rate_name']) ? " and ingress_client_rate_table_id in (select rate_table_id from rate_table where name = '{$_GET ['query'] ['rate_name']}')" : '';
        $egress_rate_table_name = !empty($_GET ['query'] ['rate_name_term']) ? " and egress_rate_table_id in (select rate_table_id from rate_table where name = '{$_GET ['query'] ['rate_name_term']}')" : '';

        //费率
        $term_rate_where = !empty($_GET ['query'] ['id_rates_term']) ? " and   egress_rate_table_id  ={$_GET ['query'] ['id_rates_term']}" : '';
        $orig_rate_where = !empty($_GET ['query'] ['id_rates']) ? " and ingress_client_rate_table_id={$_GET ['query'] ['id_rates']}" : '';

        $lrn_dnis_where = !empty($_GET ['query'] ['lrn_dnis']) ? " and lrn_dnis ='{$_GET ['query'] ['lrn_dnis']}'" : '';

        $add1 = !empty($_GET ['query'] ['lrn_number']) ? " and   lrn_dnis  ='{$_GET ['query'] ['lrn_number']}'" : '';
        $add2 = !empty($_GET ['query'] ['lrn_number_vendor']) ? " and   lrn_number_vendor  ={$_GET ['query'] ['lrn_number_vendor']}" : '';

        //suppress  filter  
        $supp_orig_rate_where = !empty($_GET ['query'] ['supp_orig_rate']) ? " and   ingress_client_rate_table_id  not in (" . join_str(',', $_GET['query']['supp_orig_rate']) . ")" : '';
        $supp_term_rate_where = !empty($_GET ['query'] ['supp_term_rate']) ? " and   egress_rate_table_id  not  in (" . join_str(',', $_GET['query']['supp_term_rate']) . ")" : '';

        $supp_ingress_where = !empty($_GET ['query'] ['supp_ingress']) ? " and   ingress_id  not in (" . join_str(',', $_GET['query']['supp_ingress']) . ")" : '';
        $supp_egress_where = !empty($_GET ['query'] ['supp_egress']) ? " and   egress_id  not in (" . join_str(',', $_GET['query']['supp_egress']) . ")" : '';

        if ($this->params['action'] == 'summary_reports' || $this->params['controller'] == 'dips') {
            $supp_orig_host_where = !empty($_GET ['query'] ['supp_orig_host']) ? " and   origination_source_host_name in (" . join_str(',', $_GET['query']['supp_orig_host']) . ")" : '';
            $supp_term_host_where = !empty($_GET ['query'] ['supp_term_host']) ? " and   termination_destination_host_name in (" . join_str(',', $_GET['query']['supp_term_host']) . ")" : '';
        } else {
            $supp_orig_host_where = !empty($_GET ['query'] ['supp_orig_host']) ? " and   origination_source_host_name  not in (" . join_str(',', $_GET['query']['supp_orig_host']) . ")" : '';
            $supp_term_host_where = !empty($_GET ['query'] ['supp_term_host']) ? " and   termination_destination_host_name not in (" . join_str(',', $_GET['query']['supp_term_host']) . ")" : '';
        }

        //权限
        $privilege_where = '';

        if ($_SESSION ['login_type'] == 3) {
            //$privilege_where = !empty($_SESSION['sst_client_id']) ? " and (  egress_client_id  = {$_SESSION['sst_client_id']}  or   ingress_client_id  = {$_SESSION['sst_client_id']})" : '';
            $cdr_type = isset($this->params['pass'][0]) ? $this->params['pass'][0] : 'buy';
            if ($cdr_type == 'buy') {
                $privilege_where = !empty($_SESSION['sst_client_id']) ? " and (ingress_client_id  = {$_SESSION['sst_client_id']})" : '';
            } else {
                $privilege_where = !empty($_SESSION['sst_client_id']) ? " and (egress_client_id  = {$_SESSION['sst_client_id']})" : '';
            }
        }


        if (!empty($_GET ['start_date']) && !empty($_GET ['start_time']) && !empty($_GET ['stop_date']) && !empty($_GET ['stop_time']) && !empty($_GET ['query']['tz'])) {
            $start = $_GET ['start_date'] . '  ' . $_GET ['start_time'] . ' ' . $_GET ['query']['tz']; //开始时间
            $end = $_GET ['stop_date'] . '  ' . $_GET ['stop_time'] . '  ' . $_GET ['query']['tz']; //结束时间
        } else {
            #report deault query time
            extract($this->Cdr->get_real_period());
        }
        $this->report_query_time = array('start' => $start, 'end' => $end);
        $this->set("start", $start);
        $this->set("end", $end);
        $start = local_time_to_gmt($start);
        $end = local_time_to_gmt($end);

        $sst_user_id = $_SESSION['sst_user_id'];

        if ($_SESSION ['login_type'] == 3) {
            $filter_client = '';
        } else {
            $filter_client = "and
(exists(SELECT id FROM users_limit WHERE user_id = {$sst_user_id} AND client_id=client_cdr.ingress_client_id) OR exists 
(SELECT users.user_id FROM users LEFT JOIN sys_role ON users.role_id = sys_role.role_id WHERE users.user_id = {$sst_user_id} 
and (role_name = 'admin' or sys_role.view_all = true)))";
        }
        //return  "time  between  '$start'  and  '$end' $org_country_where   $org_code_where  $org_code_name_where $term_country_where	 $term_code_where $term_code_name_where  $org_client_where $term_client_where $org_client_name_where  $term_client_name_where $ingress_where $egress_where	$interval_from_where $term_rate_where  $orig_rate_where  $cdr_release_cause_where  $interval_to_where	$response_from_egress_where $response_to_ingress_where  $duration_where $orig_cost_where  $term_cost_where   $source_number_where  $destination_number_where	$server_where $privilege_where  $orig_host_where  $term_host_where $invalid_ingress_ip_where  $no_product_found_where $no_code_found_where  $supp_egress_where $supp_ingress_where  $supp_orig_host_where $supp_orig_rate_where	$supp_term_host_where $supp_term_rate_where  $org_carrier_select_where  $term_carrier_select_where ";
        return "time  between  '$start'  and  '$end' $org_country_where $add1 $add2  $ingress_rate_table_name $egress_rate_table_name  $org_code_where $lrn_dnis_where  $org_code_name_where $term_country_where $term_number_where	 $term_code_where $term_code_name_where  $org_client_where $term_client_where $org_client_name_where  $term_client_name_where $ingress_where $egress_where	$interval_from_where $term_rate_where  $orig_rate_where  $cdr_release_cause_where  $interval_to_where	$response_from_egress_where $response_to_ingress_where  $duration_where $orig_cost_where  $term_cost_where   $source_number_where  $destination_number_where $orig_term_call_id_where	$server_where $privilege_where  $orig_host_where  $term_host_where $invalid_ingress_ip_where  $no_product_found_where $no_code_found_where  $supp_egress_where $supp_ingress_where  $supp_orig_host_where $supp_orig_rate_where	$supp_term_host_where $supp_term_rate_where $term_destination_number_where  $where_new $org_carrier_select_where  $term_carrier_select_where $route_prefix_where $origination_source_host_name_where $filter_client ";
    }

    /**
     *
     * @return sql  order  conditions
     */
    function capture_report_order()
    {
        $order = $this->_order_condtions(
            Array('date', 'orig_client_name', 'orig_country', 'term_country', 'total_calls', 'total_duration', 'total_cdr_cost', 'total_egress_cost', 'profit', 'profit_percentage')
        );
        if (empty($order)) {
            $order = 'order by 1,2 desc';
        } else {
            $order = 'order by ' . $order;
        }

        return $order;
    }

    /**
     *
     * @return sql  order  conditions
     */
    function capture_report_order2()
    {
        $order = $this->_order_condtions(
            Array('date', 'start_time_of_date', 'orig_client_name', 'orig_country', 'term_country', 'total_calls', 'total_duration', 'total_cdr_cost', 'total_egress_cost', 'profit', 'profit_percentage')
        );
        if (empty($order)) {
            $order = 'order by start_time_of_date desc';
        } else {
            $order = 'order by ' . $order;
        }

        return $order;
    }

    #通过时间生成amline的横轴

    function generate_amline_series($time)
    {
        $series = array();
        $start_time = strtotime($time['start_date']);
        $total_seconds = ceil((strtotime($time['end_date']) - strtotime($time['start_date'])) / $time['interval']);
        for ($i = 1; $i <= $total_seconds; $i++) {
            $series[$i] = date("Y-m-d H:i:s", $start_time + ($i - 1) * $time['interval']);
        }
        return $series;
    }

    //用数据rander图表
    function render_amline_setting($time, $graphs)
    {
        $amline = new Amline();
        #add横轴 [147] => 2011-01-25 02:00:00
        $amline->add_series($this->generate_amline_series($time));
        #add图表
        #$graph(数据) $name='code'=86   [group_time] =>[call_duration]     [2011-01-25 02:00:00]=>'669'
        foreach ($graphs as $name => $graph) {

            $amline->add_graph($name, $graph);
        }
        return $amline->to_xml();
    }

    function redirect_denied()
    {
        $this->redirect('/homes/login');
    }

    function create_doload_file_name($obj, $start, $end)
    {
        $start = substr($start, 0, 10);
        $end = substr($end, 0, 10);
        $file_name = "$obj" . "_" . $start . "_" . "$end" . ".csv";
        return $file_name;
    }

    function my_exec($cmd, $input = '')
    {
        $proc = proc_open($cmd, array(0 => array('pipe', 'r'), 1 => array('pipe', 'w'), 2 => array('pipe', 'w')), $pipes);
        fwrite($pipes [0], $input);
        fclose($pipes [0]);
        $stdout = stream_get_contents($pipes [1]);
        fclose($pipes [1]);
        $stderr = stream_get_contents($pipes [2]);
        fclose($pipes [2]);
        $rtn = proc_close($proc);
        if ($rtn != 0) {
            throw new Exception('操作执行失败:"' . $stderr . '"');
        }
        return $stdout;
    }

    function _request_string($filter = null)
    {
        $params = $this->params['url'];
        unset($params['url']);
        unset($params['ext']);
        if (!empty($filter)) {
            unset($params[$filter]);
        }
        return http_build_query($params);
    }

    function get_curr_url()
    {
        $r_s = $this->_request_string();
        if (!empty($r_s)) {
            $url = ($this->here) . "?" . ($this->_request_string());
        } else {
            $url = ($this->here);
        }
        return $url;
    }

    function _save_callback()
    {
        if ($this->RequestHandler->isGet() && !$this->RequestHandler->isAjax()) {
            $url = $this->get_curr_url();
            $back_url = $this->Session->read('back_url');
            $curr_url = $this->Session->read('curr_url');
            if ($url != $curr_url) {
                $this->Session->write('back_url', $curr_url);
                $this->Session->write('curr_url', $url);
            }
        }
        $this->_render_callback();
    }

    function _switch_database()
    {
        if (class_exists('DATABASE_CONFIG')) {
            $database = &new DATABASE_CONFIG();
            $test = $database->test;
            if (isset($_SESSION['carrier_panel']['database_name'])) {
                $database_name = $_SESSION['carrier_panel']['database_name'];
                $test['database'] = $database_name;
                $this->useDbConfig = 'test';
                return true;
            }
            return false;
        }
        return false;
    }

    # carrier  can  access 

    function illegal_redirect($type)
    {
        if ($type != 3) {
            $this->redirect_denied();
        }
    }

    #admin  can  access

    function illegal_admin_redirect($type)
    {
        if ($type != 1) {
            $this->redirect_denied();
        } else {

        }
    }

    #only  carrier access url

    function filter_cariier_url()
    {

        //	Configure::write('debug',0);
        $post = $_SESSION['carrier_panel']['Client'];
        $type = $this->Session->read('login_type');
        if ($this->params['controller'] == 'homes') {
            return;
        }
        if (empty($type)) {
            $this->redirect_denied();
        }
        if ($this->params['controller'] == 'clientcdrreports') {
            $this->illegal_redirect($type);
        }
        if ($this->params['controller'] == 'products') {
            $this->illegal_admin_redirect($type);
        }


        if ($this->params ['controller'] == 'Locationreports') {
            $this->illegal_admin_redirect($type);
        }
        if ($this->params ['controller'] == 'locationreports') {
            $this->illegal_admin_redirect($type);
        }
        if ($this->params ['controller'] == 'cdrreports' && $this->params ['action'] == 'summary_reports' && (!empty($this->params ['pass'] [0]) && $this->params ['pass'] [0] == 'spam')) {
            $this->illegal_admin_redirect($type);
        }

        if ($this->params ['controller'] == 'cdrreports' && $this->params ['action'] == 'summary_reports' && empty($post ['is_cdrslist'])) {
            $this->redirect_denied();
        }


        if ($this->params ['controller'] == 'clients' && $this->params['action'] == 'index' && empty($post['is_client_info'])) {
            $this->redirect_denied();
        }

        //		if($this->params['controller']=='clients' && $this->params['action']=='index'){$this->illegal_admin_redirect($type);}
        if ($this->params['controller'] == 'analysis' && $this->params['action'] == 'index') {
            $this->illegal_admin_redirect($type);
        }
        if ($this->params['controller'] == 'roles' && $this->params['action'] == 'view') {
            $this->illegal_admin_redirect($type);
        }
        if ($this->params['controller'] == 'users' && $this->params['action'] == 'index') {
            $this->illegal_admin_redirect($type);
        }
        if ($this->params['controller'] == 'users' && $this->params['action'] == 'view') {
            $this->illegal_admin_redirect($type);
        }


        if ($this->params ['controller'] == 'users' && $this->params['action'] == 'changepassword' && empty($post['is_changepassword'])) {
            $this->redirect_denied();
        }

        if ($this->params['controller'] == 'users' && $this->params['action'] == 'last_login') {
            $this->illegal_admin_redirect($type);
        }
        if ($this->params['controller'] == 'users' && $this->params['action'] == 'add_last') {
            $this->illegal_admin_redirect($type);
        }
        if ($this->params['controller'] == 'users' && $this->params['action'] == 'add') {
            $this->illegal_admin_redirect($type);
        }
        if ($this->params['controller'] == 'systemparams' && $this->params['action'] == 'view') {
            $this->illegal_admin_redirect($type);
        }
        if ($this->params['controller'] == 'import_export_log' && $this->params['action'] == 'import') {
            $this->illegal_admin_redirect($type);
        }
        if ($this->params['controller'] == 'import_export_log' && $this->params['action'] == 'export') {
            $this->illegal_admin_redirect($type);
        }
        if ($this->params['controller'] == 'lrnsettings' && $this->params['action'] == 'view') {
            $this->illegal_admin_redirect($type);
        }
        if ($this->params['controller'] == 'websessions' && $this->params['action'] == 'view') {
            $this->illegal_admin_redirect($type);
        }
        if ($this->params['controller'] == 'paymentterms' && $this->params['action'] == 'payment_term') {
            $this->illegal_admin_redirect($type);
        }
        if ($this->params['controller'] == 'jurisdictionprefixs' && $this->params['action'] == 'view') {
            $this->illegal_admin_redirect($type);
        }
        if ($this->params['controller'] == 'uploads' && $this->params['action'] == 'jur_country') {
            $this->illegal_admin_redirect($type);
        }
        if ($this->params['controller'] == 'downloads' && $this->params['action'] == 'jur_country') {
            $this->illegal_admin_redirect($type);
        }
        if ($this->params['controller'] == 'systemlimits' && $this->params['action'] == 'jur_country') {
            $this->illegal_admin_redirect($type);
        }
        if ($this->params['controller'] == 'rates' && $this->params['action'] == 'rates_list') {
            $this->illegal_admin_redirect($type);
        }
        if ($this->params['controller'] == 'clientrates' && $this->params['action'] == 'view') {
            $this->illegal_admin_redirect($type);
        }
        if ($this->params['controller'] == 'clientrates' && $this->params['action'] == 'simulate') {
            $this->illegal_admin_redirect($type);
        }
        if ($this->params['controller'] == 'uploads' && $this->params['action'] == 'rate') {
            $this->illegal_admin_redirect($type);
        }
        if ($this->params['controller'] == 'downloads' && $this->params['action'] == 'rate') {
            $this->illegal_admin_redirect($type);
        }
        if ($this->params['controller'] == 'codedecks' && $this->params['action'] == 'codedeck_list') {
            $this->illegal_admin_redirect($type);
        }
        if ($this->params['controller'] == 'timeprofiles' && $this->params['action'] == 'profile_list') {
            $this->illegal_admin_redirect($type);
        }
        if ($this->params['controller'] == 'currs' && $this->params['action'] == 'index') {
            $this->illegal_admin_redirect($type);
        }
        if ($this->params['controller'] == 'mailtmps' && $this->params['action'] == 'mail') {
            $this->illegal_admin_redirect($type);
        }
        if ($this->params['controller'] == 'digits' && $this->params['action'] == 'view') {
            $this->illegal_admin_redirect($type);
        }
        if ($this->params['controller'] == 'gatewaygroups') {
            $this->illegal_admin_redirect($type);
        }
        if ($this->params['controller'] == 'dynamicroutes' && $this->params['action'] == 'view') {
            $this->illegal_admin_redirect($type);
        }
        if ($this->params['controller'] == 'products' && $this->params['action'] == 'product_list') {
            $this->illegal_admin_redirect($type);
        }
        if ($this->params['controller'] == 'blocklists' && $this->params['action'] == 'index') {
            $this->illegal_admin_redirect($type);
        }
        if ($this->params['controller'] == 'routestrategys' && $this->params['action'] == 'strategy_list') {
            $this->illegal_admin_redirect($type);
        }
        if ($this->params['controller'] == 'simulatedcalls' && $this->params['action'] == 'simulated_call') {
            $this->illegal_admin_redirect($type);
        }
        if ($this->params['controller'] == 'testdevices' && $this->params['action'] == 'test_device') {
            $this->illegal_admin_redirect($type);
        }
        if ($this->params['controller'] == 'audiotests' && $this->params['action'] == 'audio_test') {
            $this->illegal_admin_redirect($type);
        }
        if ($this->params['controller'] == 'sipcaptures' && $this->params['action'] == 'sip_capture') {
            $this->illegal_admin_redirect($type);
        }
        if ($this->params['controller'] == 'sipcaptures' && $this->params['action'] == 'view') {
            $this->illegal_admin_redirect($type);
        }
        if ($this->params['controller'] == 'analysis' && $this->params['action'] == 'index') {
            $this->illegal_admin_redirect($type);
        }
        if ($this->params['controller'] == 'locationreports' && $this->params['action'] == 'index') {
            $this->illegal_admin_redirect($type);
        }
        if ($this->params['controller'] == 'origtermstatis' && $this->params['action'] == 'summary_reports') {
            $this->illegal_admin_redirect($type);
        }
        if ($this->params['controller'] == 'clientsummarystatis' && $this->params['action'] == 'summary_reports') {
            $this->illegal_admin_redirect($type);
        }
        if ($this->params['controller'] == 'ratereports' && $this->params['action'] == 'summary_reports') {
            $this->illegal_admin_redirect($type);
        }
        if ($this->params['controller'] == 'monitorsreports' && $this->params['action'] == 'globalstats') {
            $this->illegal_admin_redirect($type);
        }
        if ($this->params['controller'] == 'disconnectreports' && $this->params['action'] == 'summary_reports') {
            $this->illegal_admin_redirect($type);
        }
        if ($this->params['controller'] == 'realcdrreports' && $this->params['action'] == 'summary_reports') {
            $this->illegal_admin_redirect($type);
        }
        if ($this->params['controller'] == 'gatewaygroups' && $this->params['action'] == 'egress_report') {
            $this->illegal_admin_redirect($type);
        }
        if ($this->params['controller'] == 'bills' && $this->params['action'] == 'summary') {
            $this->illegal_admin_redirect($type);
        }
        if ($this->params['controller'] == 'transactions' && $this->params['action'] == 'client_pay_view') {
            $this->illegal_admin_redirect($type);
        }


        if ($this->params ['controller'] == 'pr_invoices' && empty($post['is_invoices'])) {
            $this->redirect_denied();
        }


        if ($this->params['controller'] == 'transactions' && $this->params['action'] == 'client_tran_view') {
            $this->illegal_admin_redirect($type);
        }
        if ($this->params['controller'] == '	resclis' && $this->params['action'] == 'make_payment') {
            $this->illegal_admin_redirect($type);
        }
        //	if($this->params['controller']=='	clients' && $this->params['action']=='index'){$this->illegal_admin_redirect($type);}
        if ($this->params['controller'] == '	summary_reports' && $this->params['action'] == 'client') {
            $this->illegal_admin_redirect($type);
        }
        if ($this->params['controller'] == '	clientpayments' && $this->params['action'] == 'add_payment') {
            $this->illegal_admin_redirect($type);
        }
        if ($this->params['controller'] == 'make_payment_one' && $this->params['action'] == 'client') {
            $this->illegal_admin_redirect($type);
        }
        if ($this->params['controller'] == 'roles' && $this->params['action'] == 'add_role') {
            $this->illegal_admin_redirect($type);
        }
        if ($this->params['controller'] == 'roles' && $this->params['action'] == 'c') {
            $this->illegal_admin_redirect($type);
        }
        if ($this->params['controller'] == 'users' && $this->params['action'] == 'add_carrier_user') {
            $this->illegal_admin_redirect($type);
        }
        if ($this->params['controller'] == 'systemlimits' && $this->params['action'] == 'view') {
            $this->illegal_admin_redirect($type);
        }
        if ($this->params['controller'] == 'uploads' && $this->params['action'] == 'code_deck') {
            $this->illegal_admin_redirect($type);
        }
        if ($this->params['controller'] == 'codedecks' && $this->params['action'] == 'codes_list') {
            $this->illegal_admin_redirect($type);
        }
        if ($this->params['controller'] == 'downloads' && $this->params['action'] == 'code_deck') {
            $this->illegal_admin_redirect($type);
        }
        if ($this->params['controller'] == 'rates' && $this->params['action'] == 'currency') {
            $this->illegal_admin_redirect($type);
        }
        if ($this->params['controller'] == 'digits' && $this->params['action'] == 'translation_details') {
            $this->illegal_admin_redirect($type);
        }
        if ($this->params['controller'] == 'uploads' && $this->params['action'] == 'digit_translation') {
            $this->illegal_redirect($type);
        }
        if ($this->params['controller'] == 'downloads' && $this->params['action'] == 'digit_translation') {
            $this->illegal_redirect($type);
        }
        if ($this->params['controller'] == 'digits' && $this->params['action'] == 'c') {
            $this->illegal_admin_redirect($type);
        }
        if ($this->params['controller'] == 'fsconfigs' && $this->params['action'] == 'config_info') {
            $this->illegal_redirect($type);
        }
        if ($this->params['controller'] == 'uploads' && $this->params['action'] == 'static_route') {
            $this->illegal_admin_redirect($type);
        }
        if ($this->params['controller'] == 'downloads' && $this->params['action'] == 'product_item') {
            $this->illegal_admin_redirect($type);
        }
        if ($this->params['controller'] == 'uploads' && $this->params['action'] == 'block_list') {
            $this->illegal_admin_redirect($type);
        }
        if ($this->params['controller'] == 'routestrategys' && $this->params['action'] == 'routes_list') {
            $this->illegal_admin_redirect($type);
        }
        if ($this->params['controller'] == 'uploads' && $this->params['action'] == 'route_plan') {
            $this->illegal_admin_redirect($type);
        }
        if ($this->params['controller'] == 'downloads' && $this->params['action'] == 'route_plan') {
            $this->illegal_admin_redirect($type);
        }
        if ($this->params['controller'] == 'audiotests' && $this->params['action'] == 'audio_test') {
            $this->illegal_admin_redirect($type);
        }
        if ($this->params['controller'] == 'sipcaptures' && $this->params['action'] == 'view') {
            $this->illegal_admin_redirect($type);
        }
        if ($this->params['controller'] == 'monitorsreports' && $this->params['action'] == 'productstats') {
            $this->illegal_admin_redirect($type);
        }
        if ($this->params['controller'] == 'monitorsreports' && $this->params['action'] == 'ingress') {
            $this->illegal_admin_redirect($type);
        }
        if ($this->params['controller'] == 'monitorsreports' && $this->params['action'] == 'egress') {
            $this->illegal_admin_redirect($type);
        }
        if ($this->params['controller'] == 'resclis' && $this->params['action'] == 'make_payment') {
            $this->illegal_admin_redirect($type);
        }
        if ($this->params['controller'] == 'pr_invoices' && $this->params['action'] == 'add') {
            $this->illegal_admin_redirect($type);
        }
        if ($this->params['controller'] == 'clientpayments' && $this->params['action'] == 'add_payment') {
            $this->illegal_admin_redirect($type);
        }
        if ($this->params['controller'] == 'resclis' && $this->params['action'] == 'make_payment_one') {
            $this->illegal_admin_redirect($type);
        }
        if ($this->params['controller'] == 'fsconfigs' && $this->params['action'] == 'config_info') {
            $this->illegal_admin_redirect($type);
        }

        //if($this->params['controller']=='downloads' && $this->params['action']=='carrier'){$this->illegal_redirect($type);}	


        if ($this->params['controller'] == 'downloads' && $this->params['action'] == 'block_list') {
            $this->illegal_admin_redirect($type);
        }
        if ($this->params['controller'] == 'audiotests' && $this->params['action'] == 'audio_test') {
            $this->illegal_admin_redirect($type);
        }
        if ($this->params['controller'] == 'invoices' && $this->params['action'] == 'edit') {
            $this->illegal_admin_redirect($type);
        }
        if ($this->params['controller'] == 'monitorsreports' && $this->params['action'] == 'prefix') {
            $this->illegal_admin_redirect($type);
        }
        if ($this->params['controller'] == 'monitorsreports' && $this->params['action'] == 'host') {
            $this->illegal_admin_redirect($type);
        }
        if ($this->params['controller'] == 'downloads' && $this->params['action'] == 'ingress') {
            $this->illegal_redirect($type);
        }
        if ($this->params['controller'] == 'uploads' && $this->params['action'] == 'ingress') {
            $this->illegal_redirect($type);
        }
        if ($this->params['controller'] == 'codedecks' && $this->params['action'] == 'view_rb') {
            $this->illegal_admin_redirect($type);
        }
        if ($this->params['controller'] == 'downloads' && $this->params['action'] == 'ingress') {
            $this->illegal_admin_redirect($type);
        }
        if ($this->params['controller'] == 'uploads' && $this->params['action'] == 'ingress') {
            $this->illegal_admin_redirect($type);
        }
        if ($this->params['controller'] == 'downloads' && $this->params['action'] == 'digit_translation') {
            $this->illegal_admin_redirect($type);
        }
        if ($this->params['controller'] == 'fsconfigs' && $this->params['action'] == 'config_info') {
            $this->illegal_admin_redirect($type);
        }
        if ($this->params['controller'] == 'sipcaptures' && $this->params['action'] == 'view') {
            $this->illegal_admin_redirect($type);
        }
        if ($this->params['controller'] == 'pr_invoices' && $this->params['action'] == 'add') {
            $this->illegal_admin_redirect($type);
        }
        if ($this->params['controller'] == 'downloads' && $this->params['action'] == 'carrier') {
            $this->illegal_admin_redirect($type);
        }
        if ($this->params['controller'] == 'downloads' && $this->params['action'] == 'ingress') {
            $this->illegal_admin_redirect($type);
        }
        if ($this->params['controller'] == 'uploads' && $this->params['action'] == 'ingress') {
            $this->illegal_admin_redirect($type);
        }
        if ($this->params['controller'] == 'uploads' && $this->params['action'] == 'egress_host') {
            $this->illegal_admin_redirect($type);
        }
        if ($this->params['controller'] == 'downloads' && $this->params['action'] == 'egress_host') {
            $this->illegal_admin_redirect($type);
        }
        if ($this->params['controller'] == 'uploads' && $this->params['action'] == 'egress_action') {
            $this->illegal_admin_redirect($type);
        }
        if ($this->params['controller'] == 'downloads' && $this->params['action'] == 'egress_action') {
            $this->illegal_admin_redirect($type);
        }
        if ($this->params['controller'] == 'uploads' && $this->params['action'] == 'egress') {
            $this->illegal_admin_redirect($type);
        }
        if ($this->params['controller'] == 'downloads' && $this->params['action'] == 'egress') {
            $this->illegal_admin_redirect($type);
        }
        if ($this->params['controller'] == 'downloads' && $this->params['action'] == 'ingress_host') {
            $this->illegal_admin_redirect($type);
        }
        if ($this->params['controller'] == 'uploads' && $this->params['action'] == 'ingress_host') {
            $this->illegal_admin_redirect($type);
        }
        if ($this->params['controller'] == 'downloads' && $this->params['action'] == 'ingress_action') {
            $this->illegal_admin_redirect($type);
        }
        if ($this->params['controller'] == 'uploads' && $this->params['action'] == 'ingress_action') {
            $this->illegal_admin_redirect($type);
        }
        if ($this->params['controller'] == 'uploads' && $this->params['action'] == 'ingress_tran') {
            $this->illegal_admin_redirect($type);
        }
        if ($this->params['controller'] == 'downloads' && $this->params['action'] == 'ingress_tran') {
            $this->illegal_admin_redirect($type);
        }
    }

    function _empty_redirect($menu)
    {
        if (empty($menu)) {

            $this->redirect_denied();
        }
    }

    function filter_admin_url()
    {
        //var_dump($this->params);exit;
        if ($this->params['controller'] == 'homes') {
            return true;
        }
        if (PRI) {
            $role_menu = empty($_SESSION['role_menu']) ? false : $_SESSION['role_menu'];
            //print_r($role_menu);exit;
            $permit = false;
            if (!empty($role_menu)) {
                $var_pri = $this->params['controller'];
                //echo $var_pri;exit;
                foreach ($role_menu as $k => $v) {
                    foreach ($v as $k_pri => $v_pri) {
                        if (empty($v_pri['pri_name'])) {
                            break 2;
                        }
                        if ($v_pri['pri_name'] == $var_pri && $v_pri['model_r'] == 't') {
                            $permit = true;
                            break 2;
                        } elseif (strpos($v_pri['pri_name'], $var_pri . ":") !== false && $v_pri['model_r'] == 't') {
                            $permit = true;
                            break 2;
                        } else {
                            //void
                        }
                    }
                }
            } else {
                $this->redirect("/homes/login");
            }

            if (!$permit) {
                $this->_empty_redirect(0);
            }
        }
        if (!PRI && isset($_SESSION['role_menu'])) {
            extract($_SESSION['role_menu']);


            #management menu
            if ($this->params['controller'] == 'clients' && $this->params['action'] == 'index') {
                $this->_empty_redirect($is_carriers);
            }
            //if($this->params['controller']=='clients'&&$this->params['action']=='add'){	$this->_empty_redirect($is_carriers);}	
            if ($this->params['controller'] == 'clients' && $this->params['action'] == 'edit') {
                $this->_empty_redirect($is_carriers);
            }
            if ($this->params['controller'] == 'clients' && $this->params['action'] == 'view') {
                $this->_empty_redirect($is_carriers);
            }
            if ($this->params['controller'] == 'downloads' && $this->params['action'] == 'carrier') {
                $this->_empty_redirect($is_carriers);
            }


            if ($this->params['controller'] == 'transactions' && $this->params['action'] == 'client_tran_view') {
                $this->_empty_redirect($is_transaction);
            }
            if ($this->params['controller'] == 'clientpayments' && $this->params['action'] == 'add_payment') {
                $this->_empty_redirect($is_transaction);
            }


            if ($this->params['controller'] == 'clientmutualsettlements' && $this->params['action'] == 'summary_reports') {
                $this->_empty_redirect($is_mutual_settlements);
            }
            if ($this->params['controller'] == 'invoices') {
                $this->_empty_redirect($is_invoices);
            }


            if ($this->params['plugin'] == 'pr' && $this->params['controller'] == 'pr_invoices' && $this->params['action'] == 'view') {
                $this->_empty_redirect($is_invoices);
            }

            if ($this->params['controller'] == 'transactions' && $this->params['action'] == 'client_pay_view') {
                $this->_empty_redirect($is_payment);
            }
            if ($this->params['controller'] == 'resclis' && $this->params['action'] == 'make_payment') {
                $this->_empty_redirect($is_payment);
            }
            if ($this->params['controller'] == 'resclis' && $this->params['action'] == 'make_payment_one') {
                $this->_empty_redirect($is_payment);
            }


            if ($this->params['controller'] == 'bills' && $this->params['action'] == 'summary') {
                $this->_empty_redirect($is_unpaid_bills);
            }
            #report menu
            if ($this->params['controller'] == 'cdrreports' && $this->params['action'] == 'summary_reports' && isset($this->params['pass'][0]) && $this->params['pass'][0] == 'spam') {
                $this->_empty_redirect($is_spam_report);
            }
            if ($this->params['controller'] == 'locationreports') {
                $this->_empty_redirect($is_location_report);
            }
            if ($this->params['controller'] == 'Locationreports') {
                $this->_empty_redirect($is_location_report);
            }
            if ($this->params['controller'] == 'origtermstatis' && $this->params['action'] == 'summary_reports') {
                $this->_empty_redirect($is_origterm);
            }
            if ($this->params['controller'] == 'clientsummarystatis' && $this->params['action'] == 'summary_reports') {
                $this->_empty_redirect($is_summary_report);
            }
            if ($this->params['controller'] == 'ratereports' && $this->params['action'] == 'summary_reports') {
                $this->_empty_redirect($is_usage_report);
            }
            if ($this->params['controller'] == 'cdrreports') {

                $this->_empty_redirect($is_cdr_list);
            }
            if ($this->params['controller'] == 'monitorsreports') {
                $this->_empty_redirect($is_qos_report);
            }


            if ($this->params['controller'] == 'disconnectreports' && $this->params['action'] == 'summary_reports') {
                $this->_empty_redirect($is_disconnect_cause);
            }
            if ($this->params['controller'] == 'mismatchesreports' && $this->params['action'] == 'mismatches_report') {
                $this->_empty_redirect($is_billing_mismatch);
            }
            if ($this->params['controller'] == 'realcdrreports' && $this->params['action'] == 'summary_reports') {
                $this->_empty_redirect($is_active_call);
            }
            if ($this->params['controller'] == 'gatewaygroups' && $this->params['action'] == 'egress_report') {
                $this->_empty_redirect($is_termination_report);
            }
            #tools menu
            if ($this->params['controller'] == 'simulatedcalls' && $this->params['action'] == 'simulated_call') {
                $this->_empty_redirect($is_call_simulation);
            }
            if ($this->params['controller'] == 'testdevices' && $this->params['action'] == 'test_device') {
                $this->_empty_redirect($is_ingress_trunk_simulation);
            }
            if ($this->params['controller'] == 'audiotests' && $this->params['action'] == 'audio_test') {
                $this->_empty_redirect($is_egress_trunk_simulation);
            }
            if ($this->params['controller'] == 'sipcaptures' && $this->params['action'] == 'sip_capture') {
                $this->_empty_redirect($is_sip_capture);
            }
            if ($this->params['controller'] == 'analysis') {
                $this->_empty_redirect($is_rates_analysis);
            }

            #routeing menu
            if ($this->params['controller'] == 'digits' && $this->params['action'] == 'view') {
                $this->_empty_redirect($is_digit_mapping);
            }
            if ($this->params['controller'] == 'digits' && $this->params['action'] == 'translation_details') {
                $this->_empty_redirect($is_digit_mapping);
            }
            if ($this->params['controller'] == 'digits' && $this->params['action'] == 'add_tran_detail') {
                $this->_empty_redirect($is_digit_mapping);
            }
            if ($this->params['controller'] == 'uploads' && $this->params['action'] == 'digit_translation') {
                $this->_empty_redirect($is_digit_mapping);
            }

            if ($this->params['controller'] == 'gatewaygroups') {
                $this->_empty_redirect($is_trunk);
            }
            if ($this->params['plugin'] == 'prresource' && $this->params['controller'] == 'gatewaygroups' && $this->params['action'] == 'view_egress') {
                $this->_empty_redirect($is_trunk);
            }

            if ($this->params['controller'] == 'dynamicroutes' && $this->params['action'] == 'view') {
                $this->_empty_redirect($is_dynamic_routing);
            }


            if ($this->params['controller'] == 'products') {
                $this->_empty_redirect($is_static_route_table);
            }
            if ($this->params['controller'] == 'uploads' && $this->params['action'] == 'static_route') {
                $this->_empty_redirect($is_static_route_table);
            }
            if ($this->params['controller'] == 'downloads' && $this->params['action'] == 'product_item') {
                $this->_empty_redirect($is_static_route_table);
            }

            if ($this->params['controller'] == 'blocklists' && $this->params['action'] == 'index') {
                $this->_empty_redirect($is_block_list);
            }
            if ($this->params['controller'] == 'uploads' && $this->params['action'] == 'block_list') {
                $this->_empty_redirect($is_block_list);
            }
            if ($this->params['controller'] == 'downloads' && $this->params['action'] == 'block_list') {
                $this->_empty_redirect($is_block_list);
            }

            if ($this->params['controller'] == 'routestrategys') {
                $this->_empty_redirect($is_routing_plan);
            }
            if ($this->params['controller'] == 'uploads' && $this->params['action'] == 'route_plan') {
                $this->_empty_redirect($is_routing_plan);
            }
            if ($this->params['controller'] == 'downloads' && $this->params['action'] == 'route_plan') {
                $this->_empty_redirect($is_routing_plan);
            }
            #switch menu
            if ($this->params['controller'] == 'websessions' && $this->params['action'] == 'view') {
                $this->_empty_redirect($is_active_web_session);
            }
            if ($this->params['controller'] == 'servicecharges') {
                $this->_empty_redirect($is_service_charge);
            }
            if ($this->params['controller'] == 'paymentterms' && $this->params['action'] == 'payment_term') {
                $this->_empty_redirect($is_payment_term);
            }
            if ($this->params['controller'] == 'jurisdictionprefixs' && $this->params['action'] == 'view') {
                $this->_empty_redirect($is_jurisdiction);
            }
            if ($this->params['controller'] == 'uploads' && $this->params['action'] == 'jur_country') {
                $this->_empty_redirect($is_jurisdiction);
            }
            if ($this->params['controller'] == 'downloads' && $this->params['action'] == 'jur_country') {
                $this->_empty_redirect($is_jurisdiction);
            }

            if ($this->params['controller'] == 'systemlimits' && $this->params['action'] == 'view') {
                $this->_empty_redirect($is_capicity);
            }
            if ($this->params['controller'] == 'clientrates') {
                $this->_empty_redirect($is_rate_table);
            }
            if ($this->params['controller'] == 'rates' && $this->params['action'] == 'rates_list') {
                $this->_empty_redirect($is_rate_table);
            }
            if ($this->params['controller'] == 'uploads' && $this->params['action'] == 'rate') {
                $this->_empty_redirect($is_rate_table);
            }
            if ($this->params['controller'] == 'downloads' && $this->params['action'] == 'rate') {
                $this->_empty_redirect($is_rate_table);
            }


            if ($this->params['controller'] == 'gatewaygroups' && $this->params['action'] == 'add_server') {
                $this->_empty_redirect($is_voip_gateway);
            }

            if ($this->params['controller'] == 'codedecks') {
                $this->_empty_redirect($is_code_deck);
            }
            if ($this->params['controller'] == 'uploads' && $this->params['action'] == 'code_deck') {
                $this->_empty_redirect($is_code_deck);
            }
            if ($this->params['controller'] == 'downloads' && $this->params['action'] == 'code_deck') {
                $this->_empty_redirect($is_code_deck);
            }


            if ($this->params['controller'] == 'timeprofiles' && $this->params['action'] == 'profile_list') {
                $this->_empty_redirect($is_time_profile);
            }
            if ($this->params['controller'] == 'timeprofiles' && $this->params['action'] == 'add_profile') {
                $this->_empty_redirect($is_time_profile);
            }

            if ($this->params['controller'] == 'currs' && $this->params['action'] == 'index') {
                $this->_empty_redirect($is_currency);
            }
            if ($this->params['controller'] == 'rates' && $this->params['action'] == 'currency') {
                $this->_empty_redirect($is_currency);
            }
            if ($this->params['controller'] == 'schedulers' && $this->params['action'] == 'schedulers_list') {
                $this->_empty_redirect($is_task_schedulers);
            }
            if ($this->params['controller'] == 'systems' && $this->params['action'] == 'trouble_shoot') {
                $this->_empty_redirect($is_trouble_shoot);
            }
            if ($this->params['controller'] == 'mailtmps' && $this->params['action'] == 'mail') {
                $this->_empty_redirect($is_mail_template);
            }
            #configure menu

            if ($this->params['controller'] == 'roles') {
                $this->_empty_redirect($is_role);
            }
            //if($this->params['controller']=='users' && $this->params['action'] != 'registration' && $this->params['action'] != 'view_orderuser'){$this->_empty_redirect($is_user);}
            if ($this->params['controller'] == 'eventlogs' && $this->params['action'] == 'events_list') {
                $this->_empty_redirect($is_event);
            }
            if ($this->params['controller'] == 'users' && $this->params['action'] == 'changepassword') {
                $this->_empty_redirect($is_change_password);
            }
            if ($this->params['controller'] == 'systemparams' && $this->params['action'] == 'view') {
                $this->_empty_redirect($is_setting);
            }
            if ($this->params['controller'] == 'import_export_log' && $this->params['action'] == 'import') {
                $this->_empty_redirect($is_import_log);
            }
            if ($this->params['controller'] == 'import_export_log' && $this->params['action'] == 'export') {
                $this->_empty_redirect($is_export_log);
            }
            if ($this->params['controller'] == 'cdrbackups' && $this->params['action'] == 'backup') {
                $this->_empty_redirect($is_cdr_backup);
            }
            if ($this->params['controller'] == 'lrnsettings' && $this->params['action'] == 'view') {
                $this->_empty_redirect($is_lrn_setting);
            }


            #buy  menu 
            if ($this->params['plugin'] == 'order' && $this->params['controller'] == 'order_browsers' && $this->params['action'] == 'sell') {
                $this->_empty_redirect($is_buy_select_country);
            }
            if ($this->params['plugin'] == 'order' && $this->params['controller'] == 'order_browsers' && $this->params['action'] == 'private_buy') {
                $this->_empty_redirect($is_search_private_buy);
            }
            if ($this->params['plugin'] == 'order' && $this->params['controller'] == 'order_contracts' && $this->params['action'] == 'manage' && isset($this->params['pass'][0]) && $this->params['pass'][0] == 'Buy') {
                $this->_empty_redirect($is_buy_confirm_order);
            }

            #sell menu
            if ($this->params['plugin'] == 'order' && $this->params['controller'] == 'order_browsers' && $this->params['action'] == 'buy') {
                $this->_empty_redirect($is_sell_select_country);
            }
            if ($this->params['plugin'] == 'order' && $this->params['controller'] == 'order_browsers' && $this->params['action'] == 'private_sell') {
                $this->_empty_redirect($is_search_private_sell);
            }
            if ($this->params['plugin'] == 'order' && $this->params['controller'] == 'order_contracts' && $this->params['action'] == 'manage' && isset($this->params['pass'][0]) && $this->params['pass'][0] == 'Sell') {
                $this->_empty_redirect($is_sell_confirm_order);
            }
        }
    }

    //JSON设置Content-type
    function beforeFilter()
    {

        Configure::load('myconf');


        $type = $this->Session->read('login_type');
        if ($type == 3) {
            $this->filter_cariier_url();
        } else {
            $this->filter_admin_url();
        }


        if ($this->RequestHandler->isXml()) {
            Configure::write('debug', 0);
            $this->autoRender = false;
            $this->layout = 'csv';
            $this->RequestHandler->setContent('xml', 'text/xml');
        }

        $this->params['hasGet'] = count($this->params['url']) > 2;
        $this->params['hasPost'] = $this->RequestHandler->isPost();
        $this->params['getUrl'] = $this->_request_string();

        if ($this->params['hasPost']) {
            $this->set('history', -2);
        } else {
            $this->set('history', -1);
        }
    }

    function beforeRender()
    {
        $this->loadModel('Client');
        $this->defaultHelper();
        $m = $this->Session->read('m');
        if (empty($m)) {
            $this->Session->write('m', Client::set_validator());
        }
        parent::beforeRender();
    }

    function defaultHelper()
    {
        if (isset($this->defaultHelper)) {
            $this->set('hel', $this->defaultHelper);
        } else {
            $this->set('hel', 'app');
        }
    }

    function _render_callback()
    {
        $this->params['callback'] = $this->Session->read("back_url");
    }

    //核查用户身份
    function checkSession($sessionName)
    {
        if (!$this->Session->check($sessionName)) {
            // $account_id=$this->Session->read('account_id');
            $this->redirect("/homes/logout"); //重定向到登录页面
        }
    }

    function _send_file($download_file, $file_name = null)
    {
        if (empty($file_name)) {
            $file_name = basename($download_file);
        }
        header("Content-type: application/octet-stream;charset=utf8");
        header("Accept-Ranges: bytes");
        header("Content-Disposition: attachment; filename=" . $file_name);
        echo file_get_contents($download_file);
        exit();
        return true;
    }

    function _ajax_request($callback, $extend_params = array())
    {
        $this->layout = '';
        $page = isset($this->params ['url'] ['page']) ? (int)$this->params ['url'] ['page'] : 1;
        $page = $page < 0 ? 1 : $page;
        $params = $this->params;
        $params ['page'] = $page;
        $params = array_merge($params, $extend_params);
        if ($this->RequestHandler->isAjax()) {
            call_user_func_array(array("AppController", "_catch_exception_msg"), array($callback, $params));
        } else {
            $this->cakeError('error404');
        }
    }

    /**
     *
     *
     *
     * @param unknown_type $callback
     * @param unknown_type $params
     */
    function _catch_exception_msg($callback, $params = array())
    {
        $op = func_get_args();
        array_shift($op);
        $error_msg_name = 'exception_msg';
        if (is_array($params) && isset($params ['exception_msg_name']) && !empty($params ['exception_msg_name']))
            $error_msg_name = $params ['exception_msg_name'];
        $this->_set_multi_string_view_vars($error_msg_name, '');

        try {
            call_user_func_array($callback, $op);
            //$this->wrap_call_user_func_array($callback, $op);

        } catch (Exception $e) {
            $this->_set_multi_string_view_vars($error_msg_name, $e->getMessage());
        }
    }

    function wrap_call_user_func_array($c, $p)
    {
        $classname = $c[0];
        $method = $c[1];

        switch (count($p)) {
            case 0:
                $this->{$method}();
                break;
            case 1:
                $this->{$method}($p[0]);
                break;
            case 2:
                $this->{$method}($p[0], $p[1]);
                break;
            case 3:
                $this->{$method}($p[0], $p[1], $p[2]);
                break;
            case 4:
                $this->{$method}($p[0], $p[1], $p[2], $p[3]);
                break;
            case 5:
                $this->{$method}($p[0], $p[1], $p[2], $p[3], $p[4]);
                break;
            //default: call_user_func_array(array($classname, $method), $p);  break;
        }

    }

    function _set_multi_string_view_vars($one, $two = null)
    {
        $data = array();
        if (is_array($one)) {
            if (is_array($two)) {
                $data = array_combine($one, $two);
            } else {
                $data = $one;
            }
        } else {
            $data = array($one => $two);
        }
        foreach ($data as $name => $value) {
            if ($two === null && is_array($one)) {
                if (!isset($this->viewVars[Inflector::variable($name)]))
                    $this->viewVars[Inflector::variable($name)] = '';
                if (!empty($this->viewVars[Inflector::variable($name)]))
                    $this->viewVars[Inflector::variable($name)] .= "<br/>";
                $this->viewVars[Inflector::variable($name)] .= $value;
            } else {
                if (!isset($this->viewVars[$name]))
                    $this->viewVars[$name] = '';
                if (!empty($this->viewVars[$name]))
                    $this->viewVars[$name] .= "<br/>";
                $this->viewVars[$name] .= $value;
            }
        }
    }

    function _order_condtions_all($array, $def_table = '', $default = null, $options = Array())
    {
        $values = $this->_get('order_by');
        $values = explode(';', $values);
        $order = Array();
        foreach ($values as $value) {
            $options['order_by'] = $value;
            $order[] = $this->_order_condtions($array, $def_table = '', $default = null, $options);
        }
        $order = join($order, ' , ');
        return $order;
    }

    function _order_condtions($array, $def_table = '', $default = null, $options = Array())
    {
        $value = array_keys_value($options, 'order_by');
        if (empty($value)) {
            $value = $this->_get('order_by');
        }
        if (empty($value)) {
            return $default;
        }
        $value = strtoupper(trim($value));
        $vs = explode('-', $value);
        $key = isset($vs[0]) ? $vs[0] : null;
        $val = isset($vs[1]) ? $vs[1] : null;
        if (!empty($key) && !empty($val)) {
            $key = strtolower($key);
            $val = strtoupper($val);
            if ($val == 'DESC' || $val == 'ASC') {
                foreach ($array as $defined_key => $defined_value) {
                    if (!is_string($defined_key)) {
                        $defined_key = $defined_value;
                        $table = $def_table;
                        if (stripos($defined_value, '.')) {
                            $t = explode('.', $defined_value);
                            $table = array_shift($t);
                            $defined_key;
                            $defined_key = $defined_value = join('', $t);
                        }
                        if ($key == $defined_key) {
                            if (!empty($table)) {
                                $table = '"' . $table . '".';
                            }
                            return "{$table} \"{$defined_value}\" $val ";
                        }
                    } else {
                        $result = '';
                        foreach (explode(',', $defined_value) as $defined_v) {
                            $table = $def_table;
                            if (stripos($defined_v, '.')) {
                                $t = explode('.', $defined_v);
                                $table = array_shift($t);
                                $defined_v = join('.', $t);
                            }
                            if ($key == $defined_key) {
                                if (!empty($table)) {
                                    $table = '"' . $table . '".';
                                }
                                $result .= "{$table} \"{$defined_v}\" $val ,";
                            }
                        }
                        if (!empty($result)) {
                            $result = substr($result, 0, -1);
                            return $result;
                        }
                    }
                }
            }
        }
        return $default;
    }

    /**
     * 将界面搜索条件拼成sql
     * @param unknown_type $array
     * @param unknown_type $def_table
     */
    function _filter_conditions($array, $def_table = null)
    {
        $this->params['search'] = Array();
        if (!is_array($array)) {
            $array = array($array);
        }
        $returning = array();
        if (empty($this->params['url'])) {
            return null;
        }
        foreach ($array as $key => $column) {
            $table = $def_table;
            if (!is_string($key)) {
                $key = $column;
                if (stripos($column, '.')) {
                    $t = explode('.', $column);
                    $table = array_shift($t);
                    $key = array_shift($t);
                }
            }
            $this->params['search']["filter_$key"] = array_keys_value($this->params, "url.filter_$key");
            $method = "_filter_" . trim(strtolower("$key"));
            if (method_exists($this, $method)) {
                $v = $this->{$method}($table);
                if ($v) {
                    $returning[] = $v;
                }
            } else {
//				trigger_error("$method was not defined",E_USER_NOTICE);
                $v = $this->_default_filter($key, $column, $table);
                if ($v) {
                    $returning[] = $v;
                }
            }
        }
        return join(" AND ", $returning);
    }

    function _range_sql_for_default_filter($table, $key, $start, $end)
    {
        if (empty($start) && empty($end)) {
            return null;
        }
        if ($start && $end) {
            if ($start > $end) {
                $t = $start;
                $start = $end;
                $this->params['url']["filter_{$key}_start"] = $start;
                $end = $t;
                $this->params['url']["filter_{$key}_end"] = $end;
            }
        }

        if (preg_match('/^range_i_/i', $key)) {
            $key = preg_replace('/^range_i_/i', '', $key);
            if ($start && $end) {
                return "{$table}\"{$key}\" BETWEEN $start AND $end";
            } elseif ($start) {
                return "{$table}\"{$key}\" >= $start";
            } elseif ($end) {
                return "{$table}\"{$key}\" <= $end";
            } else {
                return null;
            }
        }
        if (preg_match('/^range_t_/i', $key)) {
            $key = preg_replace('/^range_t_/i', '', $key);
            if ($start && $end) {
                $start = date("Y-m-d H:i:s", $start);
                $end = date("Y-m-d H:i:s", $end);
                return "{$table}\"{$key}\" BETWEEN '$start' AND '$end'";
            } elseif ($start) {
                $start = date("Y-m-d H:i:s", $start);
                return "{$table}\"{$key}\" >= '$start'";
            } elseif ($end) {
                $end = date("Y-m-d H:i:s", $end);
                return "{$table}\"{$key}\" <= '$end'";
            } else {
                return null;
            }
        }
        if (preg_match('/^range_ti_/i', $key)) {
            $key = preg_replace('/^range_ti_/i', '', $key);
            $column = "substring( {$table}\"{$key}\" from 1 for 10) ::bigint";
            if ($start && $end) {
                return "$column BETWEEN $start AND $end";
            } elseif ($start) {
                return "$column >= $start";
            } elseif ($end) {
                return "$column <= $end";
            } else {
                return null;
            }
        }
    }

    function quote_sql_string($str)
    {
//		$db = & ConnectionManager::getDataSource(Configure::read('Acl.database'));
//		return $db->value($str);
        return Sanitize::escape($str);
    }

    function _default_filter($key, $column, $table = null)
    {
//		$key = strtolower(trim($key));
        if (stripos($column, '.')) {
            $t = explode('.', $column);
            $table = array_shift($t);
            $column = join('.', $t);
        }
        if (empty($column)) {
            return null;
        }
        if (!empty($table)) {
            $table = '"' . $table . '".';
        }
        // 如果是数值区间 ， 需要取两个值
        if (preg_match('/^range_i_/i', $column)) {
            $start = (int)array_keys_value($this->params, "url.filter_{$key}_start");
            $end = (int)array_keys_value($this->params, "url.filter_{$key}_end");
            return $this->_range_sql_for_default_filter($table, $column, $start, $end);
        }
        // 如果是时间类型
        if (preg_match('/^range_t_/i', $column) || preg_match('/^range_ti_/i', $column)) {
            $start = strtotime(array_keys_value($this->params, "url.filter_{$key}_start"));
            $end = strtotime(array_keys_value($this->params, "url.filter_{$key}_end"));
            return $this->_range_sql_for_default_filter($table, $column, $start, $end);
        }

        $value = (string)array_keys_value($this->params, "url.filter_$key");
        $value = trim($value);
        $value = $this->quote_sql_string($value);
        if (is_blank($value)) {
            return null;
        } else {

            # 如果是数值类型，则在名字前加一个 i_ 则会生成 数字型的sql,  search_ 为 模糊匹配  , b_ 为 bool 型 , 否则为 string 型
            if (preg_match('/^sear_/i', $column)) {
                if ($value > -1) {
                    $column = preg_replace('/^search_/i', '', $column);
                    return "{$table}\"{$column}\" like '%$value'";
                }
            }
            if (preg_match('/^search_/i', $column)) {
                if ($value > -1) {
                    $column = preg_replace('/^search_/i', '', $column);
                    return "{$table}\"{$column}\"::text like '%$value%'";
                }
            }

            if (preg_match('/^inordercode_/i', $column)) {
                if ($value > -1) {
                    $column = preg_replace('/^inordercode_/i', '', $column);
                    if ($column == 'code') {
                        return "{$table}\"id\" in (select order_id from order_code where \"{$column}\"::prefix_range <@ '$value')";
                    } else {
                        return "{$table}\"id\" in (select order_id from order_code where \"{$column}\" ilike '%$value%')";
                    }
                }
            }

            if (preg_match('/^lt_/i', $column)) {
                if ($value > -1) {
                    $column = preg_replace('/^lt_/i', '', $column);
                    return "{$table}\"{$column}\" <= '{$value}'";
                }
            }

            if (preg_match('/^rt_/i', $column)) {
                if ($value > -1) {
                    $column = preg_replace('/^rt_/i', '', $column);
                    return "{$table}\"{$column}\" >= '{$value}'";
                }
            }

            if (preg_match('/^i_/i', $column)) {
                $value = (int)$value;
                if ($value > -1) {
                    $column = preg_replace('/^i_/i', '', $column);
                    return "{$table}\"{$column}\" = $value";
                }
            }

            if (preg_match('/^b_/i', $column)) {
                if ($value)
                    $value = 'true';
                else
                    $value = 'false';
                if ($value > -1) {
                    $column = preg_replace('/^b_/i', '', $column);
                    return "{$table}\"{$column}\" = $value";
                }
            }

            return "{$table}\"{$column}\" = '$value'";
        }
    }

    function xredirect($url, $status = null, $exit = true)
    {
        $m = $this->Session->read("m");
        if (empty($m) || trim($m) == '[]') {
            $m = AppModel::set_validator();
            $this->Session->write("m", $m);
            $this->redirect($url, $status, $exit);
        }
        $this->redirect($url, $status, $exit);
    }

    function _get($key, $default = null)
    {
        $re = array_keys_value($this->params, 'url.' . $key, $default);
        if (is_array($re)) {
            return $default;
        }
        return $re;
    }

    function _render_set_options($models, $options = Array())
    {
        if (is_string($models)) {
            $models = split('[\.\,]', $models);
        }
        foreach ($models as $model) {
            $model_options = $options;
            if (array_keys_value($options, $model)) {
                $model_options = array_merge($model_options, $options[$model]);
            }
            $this->_render_set_option($model, $model_options);
        }
    }

    function _render_set_option($model, $options = Array())
    {
        $this->set($model . 'List', $this->_render_option($model, $options));
    }

    function _render_option($model, $options)
    {
        $this->loadModel($model);
        $conditions = array_keys_value($options, 'conditions', Array());
        $order = array_keys_value($options, 'order');
        $fields = array_keys_value($options, 'fields');
        if (array_keys_value($options, 'limit') != null) {
            $this->paginate[$model]['limit'] = 2000;
            $this->paginate[$model]['order'] = $order;
            $this->paginate[$model]['conditions'] = $conditions;
            $this->paginate[$model]['fields'] = $fields;
            return $this->paginate($model);
        } else {
            return $this->$model->find('all', Array('conditions' => $conditions, 'order' => $order, 'fields' => $fields));
        }
    }

    function get_time($time = '')
    {
        $time = trim(strtolower($time));
        $now = strtotime('now');
        $interval = HOUR;
        $sql_group = 'YYYY-MM-DD HH24:00:00';
        if ($time == 'today') {
            $start = strtotime("now");
            $previous = strtotime("-1 day");
            $interval = HOUR;
            $sql_group = 'YYYY-MM-DD HH24:00:00';
        } elseif ($time == 'week') {
            $start = strtotime("-6 day");
            $previous = strtotime("-13 day");
            $interval = HOUR;
            $sql_group = 'YYYY-MM-DD HH24:00:00';
        } elseif ($time == 'month') {
            $start = strtotime("-1 month");
            $previous = strtotime("-2 month");
            $interval = DAY;
            $sql_group = 'YYYY-MM-DD 00:00:00';
        } elseif ($time == 'lastmonth') {
            $start = strtotime("");
            $previous = strtotime("");
        } elseif ($time == '3month') {
            $start = strtotime("-3 month");
            $previous = strtotime("-6 month");
            $interval = DAY;
            $sql_group = 'YYYY-MM-DD 00:00:00';
        } elseif ($time == '6month') {
            $start = strtotime("-6 month");
            $previous = strtotime("-1 year");
            $interval = DAY;
            $sql_group = 'YYYY-MM-DD 00:00:00';
        } elseif ($time == '1year') {
            $start = strtotime("-1 year");
            $previous = strtotime("-2 year");
            $interval = DAY;
            $sql_group = 'YYYY-MM-DD 00:00:00';
        } elseif ($time == '2year') {
            $start = strtotime("-2 year");
            $previous = strtotime("-4 year");
            $interval = DAY;
            $sql_group = 'YYYY-MM-DD 00:00:00';
        } else {
            $start = strtotime("now");
            $previous = strtotime("-1 day");
            $interval = HOUR;
            $sql_group = 'YYYY-MM-DD HH24:00:00';
        }

        $start_date = date("Y-m-d 00:00:00", $start);
        $end_date = date("Y-m-d 23:59:59", $now);
        $previous_date = date("Y-m-d 00:00:00", $previous);
        return compact('start_date', 'end_date', 'interval', 'sql_group', 'previous_date');
    }

    function _session_get($isGet, $options = Array())
    {
        $def_options = Array('conditions' => $this->_getController(), 'action' => $this->_getAction());
        $options = array_merge($def_options, $options);
        if ($isGet) {
            $_SESSION[$options['conditions']][$options['action']]['GET'] = $_GET;
            $_SESSION[$options['conditions']][$options['action']]['params'] = $this->params['url'];
        } else {
            if (!empty($_SESSION[$options['conditions']][$options['action']]['GET']['query']['output'])) {
                $_SESSION[$options['conditions']][$options['action']]['GET']['query']['output'] = 'web';
            }
            if (!empty($_SESSION[$options['conditions']][$options['action']]['params']['query']['output'])) {
                $_SESSION[$options['conditions']][$options['action']]['params']['query']['output'] = 'web';
            }
            if (array_keys_value($_SESSION, $options['conditions'] . '.' . $options['action'] . '.GET')) {
                $_GET = array_keys_value($_SESSION, $options['conditions'] . '.' . $options['action'] . '.GET');
            }
            if (array_keys_value($_SESSION, $options['conditions'] . '.' . $options['action'] . '.params')) {
                $this->params['url'] = array_keys_value($_SESSION, $options['conditions'] . '.' . $options['action'] . '.params');
            }
        }
    }

    function _getParams($key)
    {
        return array_keys_value($this->params, $key);
    }

    function _getAction()
    {
        return $this->_getParams('action');
    }

    function _getController()
    {
        return $this->_getParams('controller');
    }

    function _isAjax()
    {
        return $this->_getParams('isAjax');
    }

    function isPost($type = null)
    {
        if ($type == 'data') {
            return !empty($this->data);
        }
        return $this->RequestHandler->isPost();
    }

}

