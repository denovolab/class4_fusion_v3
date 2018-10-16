<?php

class Rate extends AppModel {

    var $name = 'Rate';
    var $useTable = 'rate_table';
    var $primaryKey = 'rate_table_id';

    public function is_show_jur_rate($rate_table_id) {
        $list = $this->query("select  jur_type  from  rate_table  where rate_table_id=$rate_table_id;");
        if
        (($list[0][0]['jur_type']) == 2 || $list[0][0]['jur_type'] == 3) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 分页查询Client groups
     * @param int $currPage 当前页
     * @param int $pageSize 页大小
     */
    public function getAllRates_code($currPage = 1, $pageSize = 15, $search = null, $currency, $adv_search, $order=null) {
        $order = $this->_get_order();
        if (empty($order)) {
            $order = "order by rate_table_id desc";
        }
        //分页信息
        require_once 'MyPage.php';
        $page = new MyPage ();
        $totalrecords = 0;
        $login_type = $_SESSION['login_type'];
        $privilege = '';
        if ($login_type == 3) {
            $list = $this->query("select rate_table_id  from  resource  where  client_id={$_SESSION['sst_client_id']}");
            $table_id = '';
            foreach ($list as $key => $value) {
                if (empty($value[0]['rate_table_id'])) {
                    continue;
                }
                $table_id.=$value[0]['rate_table_id'] . ",";
            }
            $table_id = substr($table_id, 0, -1);
            if (empty($table_id)) {
                $privilege = "  and(1<>1) ";
            } else {
                $privilege = "  and(rate_table.rate_table_id  in({$table_id})) ";
            }
        }
        $sql = "select count(rate_table_id) as c from rate_table where 1=1  $privilege $adv_search";
        if ($_SESSION['login_type'] == 3) {
            $sql = "select count(digits) as c   from  
  (select  digits ,rate_table_id from  route   left  join    resource  on   resource.route_strategy_id=route.route_strategy_id)as  route
  left  join (
select rate_table_id,name,modify_time,create_time,jurisdiction_country_id,code_deck_id, (select name from code_deck where code_deck_id = rate_table.code_deck_id) as code_deck, (select code from currency where currency_id = rate_table.currency_id) as currency, (select count(resource_id) from resource where rate_table_id=rate_table.rate_table_id )::float as client_rate, (select count(resource_id) from resource where egress=true and rate_table_id=rate_table.rate_table_id ) as egress_rate from rate_table
  )rate_table  on rate_table.rate_table_id=route.rate_table_id  where digits is  not  null  $privilege $adv_search  ";
        }
        if (!empty($search))
            $sql .= " and name like '%$search%'";
        if (!empty($currency))
            $sql .= " and currency_id = $currency";
        $totalrecords = $this->query($sql);
        $page->setTotalRecords($totalrecords [0] [0] ['c']); //总记录数
        $page->setCurrPage($currPage); //当前页
        $page->setPageSize($pageSize); //页大小
        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();
        $offset = $currPage * $pageSize;
        $sql = "select rate_table_id,name,modify_time,create_time,jurisdiction_country_id,code_deck_id,
							(select name from code_deck where code_deck_id = rate_table.code_deck_id) as code_deck,
							(select code from currency where currency_id = rate_table.currency_id) as currency,
								(select count(resource_id) from resource 
							where rate_table_id=rate_table.rate_table_id )::float
							as client_rate
			
							from rate_table where 1=1   $privilege $adv_search";

        if ($_SESSION['login_type'] == 3) {
            $sql = "select  route.digits, rate_table.* from  
  (select  digits ,rate_table_id from  route   left  join    resource  on   resource.route_strategy_id=route.route_strategy_id)as  route
  left  join (
select rate_table_id,name,modify_time,create_time,jurisdiction_country_id,code_deck_id, (select name from code_deck where code_deck_id = rate_table.code_deck_id) as code_deck, (select code from currency where currency_id = rate_table.currency_id) as currency, (select count(resource_id) from resource where rate_table_id=rate_table.rate_table_id )::float as client_rate, (select count(resource_id) from resource where egress=true and rate_table_id=rate_table.rate_table_id ) as egress_rate,  from rate_table
  )rate_table  on rate_table.rate_table_id=route.rate_table_id   where digits is  not  null $privilege $adv_search";
        }
        if (!empty($search))
            $sql .= " and name like '%$search%'";
        if (!empty($currency))
            $sql .= " and code_deck_id = $currency";
        $sql .= " $order limit '$pageSize' offset '$offset'";
        $results = $this->query($sql);
        $page->setDataArray($results); //Save Data into $page
        return $page;
    }

    public function checkExists($id, $code='') {
        $result = $this->query("SELECT count(id) FROM rate WHERE rate_table_id = {$id} AND code = ''");
    }

    public function deleteBlackRate($rate_table_id) {
        $sql = "DELETE FROM rate WHERE rate_table_id = {$rate_table_id}  and code = ''";
        $this->query($sql);
    }

    public function insertMassEdit($rate_table_id, $code, $codename, $country, $rate, $setupfee, $effectdate, $enddate, $mintime, $gracetime, $seconds, $timezone, $localrate) {
        $sql = "INSERT INTO rate (rate_table_id, code, code_name, country, rate,
		setup_fee, effective_date, end_date, min_time, grace_time, seconds, zone, local_rate) VALUES ($rate_table_id, '$code', '$codename', '$country', $rate, $setupfee, '$effectdate', $enddate, $mintime, $gracetime, $seconds, '$timezone', $localrate)";
        $result = $this->query($sql);
        //print_r($result);
    }

    public function matchPrefixEndDate($rate_table_id, $code, $effectdate) {
        $end_date_post = date("Y-m-d H:i:s", strtotime($effectdate) - 1);
        $sql = "UPDATE rate SET end_date = '$end_date_post' WHERE  code::varchar LIKE '$code%' AND rate_table_id = $rate_table_id AND effective_date < '$effectdate' AND end_date is  null";
        $result = $this->query($sql);
        //print_r($result);
    }

    public function matchEqualEndDate($rate_table_id, $code, $effectdate) {
        $end_date_post = date("Y-m-d H:i:s", strtotime($effectdate) - 1);
        $sql = "UPDATE rate SET end_date = '$end_date_post' WHERE  code::varchar = '$code' AND rate_table_id = $rate_table_id AND effective_date < '$effectdate' AND end_date is null";
        $result = $this->query($sql);
    }

    public function matchPrefixEndDate1($rate_table_id, $code, $enddate) {
        //$end_date_post = date("Y-m-d H:i:s", strtotime($effectdate) - 1);
        $sql = "UPDATE rate SET end_date = '$enddate' WHERE  code::varchar LIKE '$code%' AND rate_table_id = $rate_table_id AND effective_date < '$enddate' AND end_date is null";
        $result = $this->query($sql);
        //print_r($sql);
    }

    public function matchEqualEndDate1($rate_table_id, $code, $enddate) {
        //$end_date_post = date("Y-m-d H:i:s", strtotime($effectdate) - 1);
        $sql = "UPDATE rate SET end_date = '$enddate' WHERE  code::varchar = '$code' AND rate_table_id = $rate_table_id AND effective_date < '$enddate' AND end_date is null";
        $result = $this->query($sql);
        //print_r($sql);
    }

    /*
     * 获取单个rate_table_id的相关信息
     * @param int $rate_table_id
     */

    public function getOneRate($rate_table_id) {
        $sql = "select rate_table_id,name,modify_time,create_time,jurisdiction_country_id,code_deck_id,lnp_dipping_rate,update_at,update_by,jurisdiction_prefix,noprefix_min_length,noprefix_max_length,prefix_min_length,prefix_max_length, (select name from code_deck where code_deck_id = rate_table.code_deck_id) as code_deck, (select code from currency where currency_id = rate_table.currency_id) as currency, (select count(*) from resource_prefix where rate_table_id = rate_table.rate_table_id) as ingress_count, (select count(*) from resource 
where rate_table_id = rate_table.rate_table_id) as egress_count, (select count(resource_id) from resource where egress=true and rate_table_id=rate_table.rate_table_id ) as egress_rate, rate_type from rate_table where rate_table_id= {$rate_table_id}";
        $results = $this->query($sql);
        return $results;
    }

    public function getAllRates($currPage = 1, $pageSize = 15, $search = null, $currency, $adv_search, $order=null) {
        $order = $this->_get_order();
        if (empty($order)) {
            $order = "order by rate_table.name asc";
        }
        //分页信息
        require_once 'MyPage.php';
        $page = new MyPage ();
        $totalrecords = 0;
        $login_type = $_SESSION['login_type'];
        $privilege = '';
        if ($login_type == 3) {
            $list = $this->query("select rate_table_id  from  resource  where  client_id={$_SESSION['sst_client_id']}");
            $table_id = '';
            foreach ($list as $key => $value) {
                if (empty($value[0]['rate_table_id'])) {
                    continue;
                }
                $table_id.=$value[0]['rate_table_id'] . ",";
            }
            $table_id = substr($table_id, 0, -1);
            if (empty($table_id)) {
                $privilege = "  and(1<>1) ";
            } else {
                $privilege = "  and(rate_table.rate_table_id  in({$table_id})) ";
            }
        }
        $sql = "select count(rate_table_id) as c from rate_table where 1=1  $privilege $adv_search";
        if ($_SESSION['login_type'] == 3) {
            $sql = "select count(digits) as c   from  
  (select  digits ,rate_table_id from  route   left  join    resource  on   resource.route_strategy_id=route.route_strategy_id)as  route
  left  join (
select rate_table_id,name,modify_time,create_time,jurisdiction_country_id,code_deck_id, (select name from code_deck where code_deck_id = rate_table.code_deck_id) as code_deck, (select code from currency where currency_id = rate_table.currency_id) as currency, (select count(resource_id) from resource where rate_table_id=rate_table.rate_table_id )::float as client_rate, (select count(resource_id) from resource where egress=true and rate_table_id=rate_table.rate_table_id ) as egress_rate from rate_table
  )rate_table  on rate_table.rate_table_id=route.rate_table_id  where digits is  not  null  $privilege $adv_search  ";
        }
        if (!empty($search))
            $sql .= " and name ilike '%$search%'";
        if (!empty($currency))
            $sql .= " and currency_id = $currency";
        $totalrecords = $this->query($sql);
        $page->setTotalRecords($totalrecords [0] [0] ['c']); //总记录数
        $page->setCurrPage($currPage); //当前页
        $page->setPageSize($pageSize); //页大小
        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();
        $offset = $currPage * $pageSize;
        $sql = "select rate_table_id,name,modify_time,create_time,jurisdiction_country_id,code_deck_id,lnp_dipping_rate,update_at,update_by,jurisdiction_prefix,noprefix_min_length,noprefix_max_length,prefix_min_length,prefix_max_length,
                (select name from code_deck where code_deck_id = rate_table.code_deck_id) as code_deck,jur_type,
                (select code from currency where currency_id = rate_table.currency_id) as currency, (select count(*) from resource_prefix where rate_table_id = rate_table.rate_table_id) as ingress_count, (select count(*) from resource 
where rate_table_id = rate_table.rate_table_id) as egress_count,
                (select count(resource_id) from resource  where  egress=true and  rate_table_id=rate_table.rate_table_id ) 
                as egress_rate, rate_type
                from rate_table where 1=1   $privilege $adv_search";

        if ($_SESSION['login_type'] == 3) {
            $sql = "select  route.digits, rate_table.* from  
  (select  digits ,rate_table_id from  route   left  join    resource  on   resource.route_strategy_id=route.route_strategy_id)as  route
  left  join (
select rate_table_id,name,modify_time,create_time,jurisdiction_country_id,code_deck_id, (select name from code_deck where code_deck_id = rate_table.code_deck_id) as code_deck, (select code from currency where currency_id = rate_table.currency_id) as currency, (select code from currency where currency_id = rate_table.currency_id) as currency, (select count(*) from resource_prefix where rate_table_id = rate_table.rate_table_id) as ingress_count, (select count(*) from resource 
where rate_table_id = rate_table.rate_table_id) as egress_count, (select count(resource_id) from resource where egress=true and rate_table_id=rate_table.rate_table_id ) as egress_rate from rate_table
  )rate_table  on rate_table.rate_table_id=route.rate_table_id   where digits is  not  null $privilege $adv_search";
        }
        if (!empty($search))
            $sql .= " and name ilike '%$search%'";
        if (!empty($currency))
            $sql .= " and currency_id = $currency";
        $sql .= " $order limit '$pageSize' offset '$offset'";
        $results = $this->query($sql);
        $page->setDataArray($results); //Save Data into $page
        return $page;
    }

    public function deleteAll($reseller_id) {

        $result = $this->query("select rate_table_id from rate_table");
        foreach ($result as $val) {
            $rate_table_id = $val[0]['rate_table_id'];
            $this->query("delete  from rate_table where rate_table_id = {$rate_table_id} 
                        and (select count(*) from resource where resource.rate_table_id = {$rate_table_id}) = 0");
        }
        return true;
    }

    public function deleteSelected($ids) {

        $sql_2 = "select client_id from client where orig_rate_table_id in ($ids) or term_rate_table_id in ($ids) ";

        $res_2 = $this->query($sql_2);

        if (count($res_2) == 0) {
            $qs = $this->query("delete from rate_table where rate_table_id in ($ids)");
            if (count($qs) == 0)
                return true;
            return false;
        }
        return false;
    }

    public function delete_one($id) {

        //$sql_2 = "select client_id from client where orig_rate_table_id = $id or term_rate_table_id = $id ";
        //$res_2 = $this->query ( $sql_2 );

        if (1) {// count ( $res_2 )  == 0) {
            $this->query("delete  from  rate where rate_table_id=$id ");
            if ($this->del($id))
                return true;
            return false;
        }
        return false;
    }

    public function copy_rate($id, $name) {
        # check name
        $list = $this->query("select count(*) from rate_table where name= '$name' ");
        if ($list[0][0]['count'] > 0) {
            $this->create_json_array('', 101, 'Rate Table Name exists');
            return false;
        }
        $old_rate = $this->query("select code_deck_id,currency_id from rate_table where rate_table_id= '$id'");
        $modify_time = date('Y-m-d H:i:s', time() + 6 * 60 * 60);
        $code_deck = empty($old_rate [0] [0] ['code_deck_id']) ? NULL : $old_rate [0] [0] ['code_deck_id'];
        $currency_id = empty($old_rate [0] [0] ['currency_id']) ? NULL : $old_rate [0] [0] ['currency_id'];


        $data = array();
        $data['Rate']['name'] = $name;
        $data['Rate']['modify_time'] = $modify_time;
        $data['Rate']['create_time'] = $modify_time;
        $data['Rate']['code_deck_id'] = $code_deck;
        $data['Rate']['currency_id'] = $currency_id;
        if ($this->save($data['Rate'])) {
            $newtable_id = $this->getLastInsertID();
        }
         /*
        $dbpath = Configure::read('database_export_path');
        $rand_name = uniqid('copy_rate');
        $sql = "COPY (select {$newtable_id}, code,rate,setup_fee,effective_date,end_date,min_time,grace_time,interval,time_profile_id,seconds,
            code_name,rate_type,intra_rate,inter_rate 
            ,country,zone from rate  where rate_table_id={$id}) TO '/tmp/exports/{$rand_name}' WITH DELIMITER ',';
            COPY rate(
                rate_table_id, code,rate,setup_fee,effective_date,
                end_date,min_time,grace_time,
                interval,time_profile_id,seconds,code_name,rate_type,intra_rate,inter_rate,country,
                zone
            )
            FROM '/tmp/exports/{$rand_name}' WITH DELIMITER ',';";
        * 
         */
        $sql = "insert into rate  (
            rate_table_id, code,rate,setup_fee,effective_date,end_date,min_time,grace_time,
            interval,time_profile_id,seconds,code_name,rate_type,intra_rate,inter_rate,country,zone)
            select $newtable_id, code,rate,setup_fee,effective_date,end_date,min_time,grace_time,interval,time_profile_id,seconds,code_name,rate_type,intra_rate,inter_rate 
            ,country,zone from rate  where rate_table_id=$id";
        
        //copy rate
        $qs = $this->query($sql);
        if (count($qs) == 0)
            return true;
        return false;
    }

    /*
     * 查找代理商   号码组   和 币率
     */

    public function getAddInfo() {
        $codec_sql = "select code_deck_id,name from code_deck where 1=1 ";
        $curr_sql = "select currency_id,code from currency where 1=1 ";
        $jur_country_sql = "select  distinct   id,  name from jurisdiction_country";
        return array(array(), $this->query($codec_sql), $this->query($curr_sql), $this->query($jur_country_sql));
        
    }

    public function add($n, $c, $cu, $country) {

        $list = $this->query("select count(*)  from  rate_table where name='$n'");
        if (!empty($list[0][0]['count']) && $list[0][0]['count'] > 0) {
            return false;
        }



        $c = empty($c) ? 'null' : $c;
        $cu = empty($cu) ? 'null' : $cu;
        $country = empty($country) ? 'null' : $country;

        $qs = $this->query("insert into rate_table (name,modify_time,create_time,
													code_deck_id,currency_id,jurisdiction_country_id)
												 values('$n',current_timestamp(0),current_timestamp(0),$c,$cu,$country)");

        if (count($qs) == 0)
            return true;
        return false;
    }

    public function update($n, $c, $cu, $id, $country) {

        $c = empty($c) ? 'null' : $c;
        $country = empty($country) ? 'null' : $country;
        $qs = $this->query("update rate_table set  name='$n',code_deck_id=$c,currency_id=$cu,jurisdiction_country_id=$country,modify_time=current_timestamp(0) where rate_table_id = '$id'");
        if (count($qs) == 0)
            return true;
        return false;
    }

    /**
     * 分页查询Client groups
     * @param int $currPage 当前页
     * @param int $pageSize 页大小
     */
    public function getRates($currPage = 1, $pageSize = 15, $search = null, $table_id, $adv_search) {
        //分页信息
        require_once 'MyPage.php';
        $page = new MyPage ();

        $totalrecords = 0;

        $sql = "select count(rate_id) as c from rate where 1=1 and rate_table_id = '$table_id' $adv_search";
        if (!empty($search))
            $sql .= " and (code  <@ '$search' )";

        $totalrecords = $this->query($sql);

        $page->setTotalRecords($totalrecords [0] [0] ['c']); //总记录数
        $page->setCurrPage($currPage); //当前页
        $page->setPageSize($pageSize); //页大小
        //$page = $page->checkRange($page);//检查当前页范围


        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();
        $offset = $currPage * $pageSize;

        //查询Client groups
        $sql = "select *,(select name from time_profile where time_profile_id = rate.time_profile_id)as tf from rate where rate_table_id = '$table_id' $adv_search";

        if (!empty($search))
            $sql .= " and (code  <@ '$search' )";
        $sql .= " limit '$pageSize' offset '$offset'";

        $results = $this->query($sql);

        $page->setDataArray($results); //Save Data into $page
        //////////////////////////////////////////


        return $page;
    }

    public function getTimeProfile($reseller_id) {
        $sql = "select time_profile_id,name from time_profile";
        if (!empty($reseller_id)) {
            $sql .= " where reseller_id = $reseller_id";
        }
        return $this->query($sql);
    }

    public function add_rate() {
        $code = strlen($_REQUEST ['code']) > 0 ? "'" . $_REQUEST ['code'] . "'" : 'null';
        $codename = empty($_REQUEST ['codename']) ? 'null' : "'" . $_REQUEST ['codename'] . "'";
        $rate = $_REQUEST ['rate'];
        $setup_fee = $_REQUEST ['setup_fee'];
        $effective_date = $_REQUEST ['effective_date'];
        $end_date = empty($_REQUEST ['end_date']) ? 'null' : "'" . $_REQUEST ['end_date'] . "'";
        $min_date = $_REQUEST ['min_date'];
        $interval = $_REQUEST ['interval'];
        $time_profile = empty($_REQUEST ['time_profile']) ? 'null' : $_REQUEST ['time_profile'];
        $seconds = $_REQUEST ['seconds'];
        $tmp_id = $_REQUEST ['tmp_id'];

        $intra_rate = isset($_REQUEST ['intra_rate']) ? $_REQUEST ['intra_rate'] : 'null';
        $inter_rate = isset($_REQUEST ['inter_rate']) ? $_REQUEST ['inter_rate'] : 'null';

        $grace_time = $_REQUEST ['grace_time'];

        $check_sql = "select time_profile_id from rate where code = $code and rate_table_id = $tmp_id ";
        $exists = $this->query($check_sql);
        $add_profile = array();
        if (!empty($time_profile)) {
            $add_profile = $this->query("select * from time_profile where time_profile_id = $time_profile");

            $sametype = $this->query("select rate_id from rate join time_profile
on rate.time_profile_id = time_profile.time_profile_id
where code = $code  and rate_table_id = $tmp_id and time_profile.type != (select type from time_profile where time_profile_id =$time_profile )");
            if (count($sametype) > 0) {
                //	return __('samecodedifftime',true)."|false";
            }
        }
        for ($i = 0; $i < count($exists); $i++) {
            if (empty($time_profile) && empty($exists[$i][0]['time_profile_id'])) {
                //	return __('samepreifxintime',true)."|false";
            }
            if ($exists[$i][0]['time_profile_id'] == $time_profile) {
                //return __('samepreifxintime',true)."|false";
            }

            $t = $this->query("select * from time_profile where time_profile_id = {$exists[$i][0]['time_profile_id']}");
            if ($t[0][0]['type'] == $add_profile[0][0]['type']) {
                if ($t[0][0]['type'] == 0) {
                    //	return __('samepreifxintime',true)."|false";
                }

                if ($t[0][0]['type'] == 1) {
                    if ($add_profile[0][0]['start_week'] <= $t[0][0]['end_week'] || $add_profile[0][0]['end_week'] <= $t[0][0]['end_week']) {
                        //	return __('samepreifxintime',true)."|false";	
                    }
                }

                if ($t[0][0]['type'] == 2) {
                    if (strtotime("2011-01-01 {$add_profile[0][0]['start_time']}") < strtotime("2011-01-01 {$t[0][0]['end_time']}")) {
                        //		return __('samepreifxintime',true)."|false";	
                    }

                    if (strtotime("2011-01-01 {$add_profile[0][0]['end_time']}") < strtotime("2011-01-01 {$t[0][0]['end_time']}")) {
                        //	return __('samepreifxintime',true)."|false";	
                    }
                }
            }
        }

        $qs = $this->query("insert into rate (rate_table_id,code,code_name,rate,setup_fee,
											 effective_date,end_date,min_time,
											 interval,time_profile_id,seconds,grace_time,intra_rate,inter_rate)
											 values ($tmp_id,$code,$codename,'$rate','$setup_fee','$effective_date',$end_date,$min_date,$interval,$time_profile,$seconds,$grace_time
											 ,$intra_rate,$inter_rate)");
        if (count($qs) == 0)
            return __('addratesucc', true) . "|true";
        else
            return __('addratefailed', true) . "|false";
    }

    //判断是否有号码组
    public function hasCodedeck($table_id) {
        $qs = $this->query("select code_deck_id from rate_table where rate_table_id = '$table_id'");
        if (count($qs) > 0)
            return $qs [0] [0] ['code_deck_id'];
        else
            return false;
    }

    /**
     * 分页查询Reseller
     * @param int $currPage 当前页
     * @param int $pageSize 页大小
     */
    public function choose_codes($currPage = 1, $pageSize = 15, $search = null, $code_deck_id = null) {
        //分页信息
        require_once 'MyPage.php';
        $page = new MyPage ();

        $totalrecords = 0;

        $sql = "select count(code_id) as c from code where code_deck_id = '$code_deck_id'";
        if (!empty($search))
            $sql .= " and (code <@ '$search' )";

        $totalrecords = $this->query($sql);

        $page->setTotalRecords($totalrecords [0] [0] ['c']); //总记录数
        $page->setCurrPage($currPage); //当前页
        $page->setPageSize($pageSize); //页大小
        //$page = $page->checkRange($page);//检查当前页范围


        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();
        $offset = $currPage * $pageSize;

        //查询Client groups
        $sql = "select * from code where code_deck_id = '$code_deck_id'";

        if (!empty($search))
            $sql .= " and (code <@ '$search' )";
        $sql .= " limit '$pageSize' offset '$offset'";

        $results = $this->query($sql);

        $page->setDataArray($results); //Save Data into $page
        //////////////////////////////////////////


        return $page;
    }

    public function del_rate($id) {
        $qs = $this->query("delete from rate where rate_id = {$id}");
        return count($qs) == 0;
    }

    public function update_rate() {
        /* 			$code = '111';
          $codename = 'null';
          $rate = '0';
          $setup_fee = '0';
          $effective_date = '2010-11-14 12:31:43';
          $end_date =  'null';
          $min_date = '0';
          $interval = '60';
          $time_profile = '18';
          $seconds = '60';
          $id = '315';
          $grace_time = '0';
          $ratetype ='1'; */
        $code = strlen($_REQUEST ['code']) > 0 ? "'" . $_REQUEST ['code'] . "'" : 'null';
        $codename = empty($_REQUEST ['codename']) ? 'null' : "'" . $_REQUEST ['codename'] . "'";
        $rate = $_REQUEST ['rate'];
        $setup_fee = $_REQUEST ['setup_fee'];
        $effective_date = $_REQUEST ['effective_date'];
        $end_date = empty($_REQUEST ['end_date']) ? 'null' : "'" . $_REQUEST ['end_date'] . "'";
        $min_date = $_REQUEST ['min_date'];
        $interval = $_REQUEST ['interval'];
        $time_profile = empty($_REQUEST ['time_profile']) ? 'null' : $_REQUEST ['time_profile'];
        $seconds = $_REQUEST ['seconds'];
        $id = $_REQUEST ['id'];
        $grace_time = $_REQUEST ['grace_time'];
        $intra_rate = !empty($_REQUEST ['intra_rate']) ? $_REQUEST ['intra_rate'] : 'null';
        $inter_rate = !empty($_REQUEST ['inter_rate']) ? $_REQUEST ['inter_rate'] : 'null';
        $oldcodes = $this->query("select code,rate_table_id,time_profile_id from rate where rate_id = $id");

        if ($oldcodes[0][0]['time_profile_id'] != $time_profile) {
            $check_sql = "select time_profile_id from rate where code = $code and rate_table_id = {$oldcodes[0][0]['rate_table_id']} ";
            $exists = $this->query($check_sql);
            $add_profile = $this->query("select * from time_profile where time_profile_id = $time_profile");
//		if (!empty($add_profile)){
//			$add_profile = $this->query("select * from time_profile where time_profile_id = $time_profile");
//			$sametype = $this->query("select rate_id from rate join time_profile
//on rate.time_profile_id = time_profile.time_profile_id
//where code = '$code'  and rate_table_id = {$oldcodes[0][0]['rate_table_id']} and time_profile.type != (select type from time_profile where time_profile_id =$time_profile )");
//			if (count($sametype) > 0){
//				return __('samecodedifftime',true)."|false";
//			}
//		}
            for ($i = 0; $i < count($exists); $i++) {
                if (empty($time_profile) && empty($exists[$i][0]['time_profile_id'])) {
                    //return __('samepreifxintime',true)."|false";
                }
                if ($exists[$i][0]['time_profile_id'] == $time_profile) {
                    //	return __('samepreifxintime',true)."|false";
                }

                $t = $this->query("select * from time_profile where time_profile_id = {$exists[$i][0]['time_profile_id']}");
                if ($t[0][0]['type'] == $add_profile[0][0]['type']) {
                    if ($t[0][0]['type'] == 0) {
                        //		return __('samepreifxintime',true)."|false";
                    }

                    if ($t[0][0]['type'] == 1) {
                        if ($add_profile[0][0]['start_week'] <= $t[0][0]['end_week'] || $add_profile[0][0]['end_week'] <= $t[0][0]['end_week']) {
                            //		return __('samepreifxintime',true)."|false";	
                        }
                    }

                    if ($t[0][0]['type'] == 2) {
                        if (strtotime("2011-01-01 {$add_profile[0][0]['start_time']}") < strtotime("2011-01-01 {$t[0][0]['end_time']}")) {
                            //			return __('samepreifxintime',true)."|false";	
                        }

                        if (strtotime("2011-01-01 {$add_profile[0][0]['end_time']}") < strtotime("2011-01-01 {$t[0][0]['end_time']}")) {
                            //			return __('samepreifxintime',true)."|false";	
                        }
                    }
                }
            }
        }

        if ($oldcodes[0][0]['code'] == $code) {
            $sql = "update rate set code_name=$codename, rate=$rate,setup_fee=$setup_fee,effective_date='$effective_date',end_date=$end_date,
											min_time='$min_date',grace_time='$grace_time',interval='$interval',time_profile_id=$time_profile,seconds='$seconds'
											where rate_id = '$id'";
            $qs = $this->query($sql);
        } else {
            $sql = "update rate set code_name=$codename, code = $code,rate=$rate,setup_fee=$setup_fee,effective_date='$effective_date',end_date=$end_date,
											min_time='$min_date',grace_time='$grace_time',interval='$interval',time_profile_id=$time_profile,seconds='$seconds',
											intra_rate=$intra_rate,inter_rate=$inter_rate
											
											where rate_id = '$id'";
            $qs = $this->query($sql);
        }

        if (count($qs) == 0) {
            //return $sql;
            return __('update_suc', true) . "|true";
        } else {
            return $sql;
            //	return __ ( 'update_fail', true ) . "|false";
        }
    }

    public function generate_by_codedeck() {
        $rate = $_REQUEST ['rate'];
        $setup_fee = $_REQUEST ['setup_fee'];
        $effective_date = $_REQUEST ['effective_date'];
        $end_date = empty($_REQUEST ['end_date']) ? 'null' : "'" . $_REQUEST ['end_date'] . "'";
        ;
        $min_date = $_REQUEST ['min_date'];
        $interval = $_REQUEST ['interval'];
        $time_profile = empty($_REQUEST ['time_profile']) ? 'null' : $_REQUEST ['time_profile'];
        $seconds = $_REQUEST ['seconds'];
        $code_deck_id = $_REQUEST ['code_deck'];
        $grace_time = $_REQUEST ['grace_time'];
        $tmp_id = $_REQUEST ['tmp_id'];
        $codes = $this->query("select code from code where code_deck_id = '$code_deck_id'");
        if (count($codes) == 0)
            return __('nocodedeck', true) . "|false";
        else {
            $qs_counter = 0;
            $this->begin();
            $loop = count($codes);
            for ($i = 0; $i < $loop; $i++) {
                $code = $codes [$i] [0] ['code'];
                //检查前缀是否已经存在  如果存在则跳过
                $exists = $this->query("select rate_id from rate where rate_table_id='$tmp_id' and code='$code'");
                if (count($exists) > 0)
                    continue;
                $qs = $this->query("insert into rate (rate_table_id,code,rate,setup_fee,
											 effective_date,end_date,min_time,
											 interval,time_profile_id,seconds,grace_time)
											 values ($tmp_id,'$code','$rate','$setup_fee','$effective_date',$end_date,$min_date,$interval,$time_profile,$seconds,$grace_time)");
                $qs_counter += count($qs);
            }
            if ($qs_counter == 0) {
                $this->commit();
                return __('generatedsuc', true) . "|true";
            } else {
                $this->rollback();
                return __('generatedfail', true) . "|false";
            }
        }
    }

    public function simulated1($date, $time, $tz, $ani, $dnis, $duration, $table_id) {
        $anisql = "";
        if (!empty($ani)) {
            $anisql = "(code @>  '{$ani}'  ) and";
        }
        $sql = "select rate,seconds from rate where $anisql (code @> '{$dnis}' ) and rate_table_id = {$table_id}  and   
effective_date < '{$date} {$time}{$tz}' and  (end_date > '{$date} {$time}{$tz}' or end_date is null)";
        $results = $this->query($sql);
        $arr = array();
        $rate = isset($results[0][0]['rate']) ? $results[0][0]['rate'] : '';
        $arr['date'] = "{$date} {$time}{$tz}";
        $arr['ani'] = $ani;
        $arr['dnis'] = $dnis;
        $arr['rate'] = !empty($rate) ? $rate : 'Not Found';
        $arr['cost'] = !empty($rate) ? number_format($rate * ($duration / $results[0][0]['seconds']), 5) : 'Not Found';
        return $arr;
    }

    /*
     * 模拟计费
     */

    public function simulated($date, $number, $durations, $tab_id) {
        $sql = "select rate.*,time_profile.name from rate left join time_profile on rate.time_profile_id=time_profile.time_profile_id   where (code @>  '$number'  ) and rate_table_id = $tab_id";
        echo $sql;
        exit();
        //查找最接近号码的费率
        $codes = $this->query("select rate.*,time_profile.name from rate left join time_profile on rate.time_profile_id=time_profile.time_profile_id   where (code @>  '$number'  ) and rate_table_id = $tab_id");
        if (count($codes) == 0) {

            $this->create_json_array('', 101, 'Sorry, can not find the number of simulation billing! ');

            return "";
        } else {
            $this->create_json_array('', 201, 'Simulate Billing Successfully!');

            #if (strtotime ( $date ) < strtotime ( $codes [0] [0] ['effective_date'] ) || strtotime ( $date ) > strtotime ( $codes [0] [0] ['end_date'] ))
            #return "{}";

            $code = $codes [0] [0] ['code'];
            $cost = 0;
            $rate = $codes [0] [0] ['rate'];
            $min_time = $codes [0] [0] ['min_time']; //首次时长
            $interval = $codes [0] [0] ['interval']; //计费周期
            $seconds = $codes [0] [0] ['seconds']; //每分钟多少秒
            $billed = __('second', true);
            $bill_time = 0;
            //通话时间在赠送时间以内  不计费
            if ($durations <= $codes [0] [0] ['grace_time']) {
                $cost = 0;
            } else if ($durations > $codes [0] [0] ['grace_time'] && $durations < $min_time) { //通话时间小于首次通话时间
                if ($min_time % $interval == 0) {
                    $cost = $min_time / $seconds * $rate;
                } else {
                    $cost = (($min_time / $interval) + 1) * $interval / $seconds * $rate;
                }
            }

            //大于首次时长
            else if ($durations > $min_time) {
                if ($durations % $interval == 0)
                    $cost = $durations / $seconds * $rate;
                else
                    $cost = (($durations / $interval) + 1) * $interval / $seconds * $rate;
            }

            $cost = number_format($cost, 3);
            //return "{code:'$code',cost:'$cost',rate:'$rate',bill_way:'$billed'}";
            return array('code' => $code, 'cost' => $cost, 'rate' => $rate, 'bill_way' => $billed, 'rate_info' => $codes[0][0]);
        }
    }

    public function get_rate_tables($reseller_id) {
        $sql = "select rate_table_id,name from rate_table";
        if (!empty($reseller_id))
            $sql .= " where reseller_id = $reseller_id";
        return $this->query($sql);
    }

    public function getSearchInfo() {
        $sql_code_deck = "select code_deck_id,name from code_deck";
        $sql_currency = "select currency_id,code from currency";

        return array(
            $this->query($sql_code_deck),
            $this->query($sql_currency),
        );
    }

    public function select_name($id=null) {
        $where = '';
        if (!empty($id)) {
            $where = " where  currency_id=$id";
        }
        $sql = "select code from  currency $where";
        $code = $this->query($sql);
        return $code;
    }

    public function ready_rate() {
        $sql = "SELECT rate_table_id || ':' || name AS id,name FROM rate_table ORDER BY name ASC";
        return $this->query($sql);
    }

    public function ready_resource() {
        $sql = "SELECT resource_id, alias FROM resource WHERE ingress = true ORDER BY alias";
        return $this->query($sql);
    }
    
    /*
     * 通过ID获取名称
     */
    public function getNameByID($ids){
        $sql = "SELECT name FROM rate_table where rate_table_id in ($ids)";
        $result = $this->query($sql);
        return $result;
    }
    
    public function get_code_decks() {
        $sql = "SELECT code_deck_id, name FROM code_deck ORDER BY name ASC";
        return $this->query($sql);
    }
    
    public function get_currencies() {
        $sql = "SELECT currency_id, code FROM currency WHERE active = true ORDER by currency_id ASC";
        return $this->query($sql);
    }
    
    public function get_jurisdictions()
    {
        $sql = "SELECT id, name FROM jurisdiction_country";
        return $this->query($sql);
    }
    
    public function get_timeprofiles() 
    {
        $sql = "SELECT time_profile_id,name FROM time_profile ORDER by name ASC";
        return $this->query($sql);
    }
    
    public function alreay_exists_ratetable($name) {
        $sql = "SELECT count(*) FROM rate_table WHERE name = '{$name}'";
        $result = $this->query($sql);
        return $result[0][0]['count'] > 0;
    }
    
    public function create_ratetable($name, $code_deck_id, $currency_id, $rate_type, $isus, $rate_type1) {
        if($isus) {
            $sql = "SELECT id FROM jurisdiction_country WHERE name = 'US'";
            $data = $this->query($sql);
            if (empty($data)) {
                $sql = "INSERT INTO jurisdiction_country(name) VALUES ('US') returning id";
                $data = $this->query($sql);
            }
            $sql = "INSERT INTO rate_table(name, code_deck_id, currency_id, rate_type, jurisdiction_country_id, create_time, jurisdiction_prefix, noprefix_min_length, noprefix_max_length, prefix_min_length, prefix_max_length, jur_type)
VALUES ('{$name}', $code_deck_id, $currency_id, $rate_type, {$data[0][0]['id']}, CURRENT_TIMESTAMP, '1', '10', '10', '11', '11', $rate_type1) RETURNING rate_table_id";
        } else {
            $sql = "INSERT INTO rate_table(name, code_deck_id, currency_id, rate_type, jurisdiction_country_id, create_time, jur_type)
VALUES ('{$name}', $code_deck_id, $currency_id, $rate_type, NULL, CURRENT_TIMESTAMP, $rate_type1) RETURNING rate_table_id";
        }
        $result = $this->query($sql);
        if (empty($result)) {
            return false;
        }
        return $result[0][0]['rate_table_id'];
    }
    
    public function has_exists_code($rate_table_id, $code, $effective_date, $effective_date_gmt) {
        $sql = "SELECT count(*) FROM rate WHERE rate_table_id = {$rate_table_id} AND code = '{$code}' AND effective_date = '{$effective_date}{$effective_date_gmt}'";
        $result = $this->query($sql);
        return $result[0][0]['count'] > 0;
    }
    
    public function end_early_date($rate_table_id, $code, $end_date, $effective_date, $effective_date_gmt) {
        $sql = "UPDATE rate SET end_date = '$end_date' WHERE  end_date is null AND rate_table_id = {$rate_table_id}
AND code = '{$code}' AND effective_date::timestamp with time zone < timestamp with time zone '{$effective_date}{$effective_date_gmt}';";
        $this->query($sql);
    }
    
    public function rate_tables() {
        $sql = "SELECT rate_table_id, name FROM rate_table ORDER BY name ASC";
        return $this->query($sql);
    }
    
    
    public function code_names($rate_table_id) {
        $sql = "SELECT DISTINCT code_name FROM rate WHERE rate_table_id = {$rate_table_id} ORDER BY code_name ASC";
        return $this->query($sql);
    }
    
    public function get_carriers() {
        $sql = "SELECT client_id, name FROM client ORDER BY name ASC";
        return $this->query($sql);
    }
    
    public function template_lists() {
        $sql = "SELECT id, name FROM send_rate_template ORDER BY name ASC";
        return $this->query($sql);
    }
    
    public function get_rate_sending_logging_count() {
        $sql = "SELECT count(*) FROM rate_send_logging";
        $data = $this->query($sql);
        return $data[0][0]['count'];
    }
    
    public function get_rate_sending_logging($pageSize,$offset) {
        $sql = "SELECT * FROM rate_send_logging ORDER BY id DESC LIMIT $pageSize OFFSET $offset";
        return $this->query($sql);
    }

}
