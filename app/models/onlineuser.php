<?php

class Onlineuser extends AppModel {

    var $name = 'Onlineuser';
    var $useTable = 'online_users';
    var $primaryKey = 'online_id';

    function add_online_user($data) {
        $data['Onlineuser']['create_time'] = date("Y-m-d   H:i:s");
        $this->save($data ['Onlineuser']);
        $online_id = $this->getlastinsertId();
        return $online_id;
    }

    /**
     * 普通查询
     * @paranknown_type $currPage
     * @param unknown_type $pageSize
     */
    public function findAll($currPage = 1, $pageSize = 10) {
        $login_type = $_SESSION['login_type'];
        //damin
        if ($login_type == 1) {
            $sql1 = "select count(online_id) as c from online_users ";
            $sql2 = " ";
        }
        //reseller
        if ($login_type == 2) {
            $reseller_id = $_SESSION['sst_reseller_id'];
            $sql1 = "select count(online_id) as c from online_users where  reseller_id=$reseller_id ";
            $sql2 = "  where  e.reseller_id=$reseller_id";
        }
        //client
        if ($login_type == 3) {
            $client_id = $_SESSION['sst_client_id'];
            $sql1 = "select count(online_id) as c from online_users  where  client_id=$client_id ";
            $sql2 = "  where  e.client_id=$client_id";
        }

        if ($login_type == 4) {
            $card_id = $_SESSION['sst_card_id'];
            $sql1 = "select count(online_id) as c from online_users  where  card_id=$card_id ";
            $sql2 = "  where  e.card_id=$card_id";
        }

        //user
        if ($login_type == 5 || $login_type == 6) {
            $user_id = $_SESSION['sst_user_id'];
            $sql1 = "select count(online_id) as c from online_users where  user_id=$user_id ";
            $sql2 = "  where e.user_id=$user_id";
        }






        //分页信息
        require_once 'MyPage.php';
        $page = new MyPage();
        $totalrecords = $this->query($sql1);

        $page->setTotalRecords($totalrecords[0][0]['c']); //总记录数
        $page->setCurrPage($currPage); //当前页
        $page->setPageSize($pageSize); //页大小
        //$page = $page->checkRange($page);//检查当前页范围

        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();
        $offset = $currPage * $pageSize;
        $sql = "select  user_name  ,login_time,re.name as  reseller_name   ,user_type  from  online_users  as onl
left join (select name,reseller_id   from  reseller)  re  on  re.reseller_id=onl.reseller_id  $sql2  " . "order by online_id    desc  	limit '$pageSize' offset '$offset'";

        $results = $this->query($sql);

        $page->setDataArray($results);
        return $page;
    }

    /**
     * 普通查询(通过角色)
     * @paranknown_type $currPage
     * @param unknown_type $pageSize
     */
    public function findAllby_role($currPage = 1, $pageSize = 10, $role_id) {

        //分页信息
        require_once 'MyPage.php';
        $page = new MyPage();
        $totalrecords = $this->query("select count(user_id) as c from users  where role_id =$role_id");

        $page->setTotalRecords($totalrecords[0][0]['c']); //总记录数
        $page->setCurrPage($currPage); //当前页
        $page->setPageSize($pageSize); //页大小
        //$page = $page->checkRange($page);//检查当前页范围

        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();
        $offset = $currPage * $pageSize;
        $sql = "select u.user_id,u.name,reseller_name,role_name ,u.create_time,u.password,u.active
		    from  users as u 
	  left join (select name as reseller_name,reseller_id  from reseller  ) reseller   on  reseller.reseller_id=u.reseller_id
	left join (select   role_name,role_id from role ) role   on  role.role_id=u.role_id	

	where u.role_id=$role_id
	order by user_id  	limit '$pageSize' offset '$offset'";

        $results = $this->query($sql);

        $page->setDataArray($results);
        return $page;
    }

    function likequeryby_role($key, $currPage = 1, $pageSize = 10, $role_id) {

        $condition = "'%" . $key . "%'";

        require_once 'MyPage.php';
        $page = new MyPage();
        $totalrecords = $this->query("select count(user_id) as c 
	 	from users  where  users.role_id=$role_id  and( users.name 
	 	ilike $condition  or ilike like $condition  
	
	 	or (select count(*)>0 from reseller where reseller.reseller_id=users.reseller_id 
	 	and reseller.name ilike $condition )
	 		 	or (select count(*)>0 from client where client.client_id=users.client_id 
	 	and client.name ilike $condition )
	 		 		 	or (select count(*)>0 from role where role.role_id=$role_id 
	 	and role_name ilike $condition ))
	 	");

        $page->setTotalRecords($totalrecords[0][0]['c']); //总记录数
        $page->setCurrPage($currPage); //当前页
        $page->setPageSize($pageSize); //页大小
        //$page = $page->checkRange($page);//检查当前页范围

        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();
        $offset = $currPage * $pageSize;
        $sql = "select u.user_id,u.name,reseller_name,role_name ,u.create_time,u.password,u.active
		    from  users as u 
	  left join (select name as reseller_name,reseller_id  from reseller  ) reseller   on  reseller.reseller_id=u.reseller_id
	left join (select   role_name,role_id from role ) role   on  role.role_id=u.role_id	 

	
	where u.name 
	 	ilike $condition  or fullname ilike $condition  
	
	 	or (select count(*)>0 from reseller where reseller.reseller_id=u.reseller_id 
	 	and reseller.name ilike $condition )
	 		 	or (select count(*)>0 from client where client.client_id=u.client_id 
	 	and client.name ilike $condition )
	 		 		 	or (select count(*)>0 from role where role.role_id=u.role_id 
	 	and role_name ilike $condition )
	
	
	
	order by u.user_id  	limit '$pageSize' offset '$offset'";
        $results = $this->query($sql);

        $page->setDataArray($results);
        return $page;
    }

    function getIngressResource() {
        $sql = "SElECT resource_id, alias FROM resource WHERE ingress=true ORDER BY alias ASC";
        return $this->query($sql);
    }

    function getEgressResource() {
        $sql = "SElECT resource_id, alias FROM resource WHERE egress=true ORDER BY alias ASC";
        return $this->query($sql);
    }

    function likequery($key, $currPage = 1, $pageSize = 10) {

        $condition = "'%" . $key . "%'";

        require_once 'MyPage.php';
        $page = new MyPage();
        $totalrecords = $this->query("select count(user_id) as c 
	 	from users  where users.name 
	 	ilike $condition  or fullname ilike $condition  
	
	 	or (select count(*)>0 from reseller where reseller.reseller_id=users.reseller_id 
	 	and reseller.name ilike $condition )
	 		 	or (select count(*)>0 from client where client.client_id=users.client_id 
	 	and client.name ilike $condition )
	 		 		 	or (select count(*)>0 from role where role.role_id=users.role_id 
	 	and role_name ilike $condition )
	 	");

        $page->setTotalRecords($totalrecords[0][0]['c']); //总记录数
        $page->setCurrPage($currPage); //当前页
        $page->setPageSize($pageSize); //页大小
        //$page = $page->checkRange($page);//检查当前页范围

        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();
        $offset = $currPage * $pageSize;
        $sql = "select u.user_id,u.name,reseller_name,role_name ,u.create_time,u.password,u.active
		    from  users as u 
	  left join (select name as reseller_name,reseller_id  from reseller  ) reseller   on  reseller.reseller_id=u.reseller_id
	left join (select   role_name,role_id from role ) role   on  role.role_id=u.role_id	 

	
	where u.name 
	 	ilike $condition  or fullname ilike $condition  
	
	 	or (select count(*)>0 from reseller where reseller.reseller_id=u.reseller_id 
	 	and reseller.name like $condition )
	 		 	or (select count(*)>0 from client where client.client_id=u.client_id 
	 	and client.name ilike $condition )
	 		 		 	or (select count(*)>0 from role where role.role_id=u.role_id 
	 	and role_name ilike $condition )
	
	
	
	order by u.user_id  	limit '$pageSize' offset '$offset'";
        $results = $this->query($sql);

        $page->setDataArray($results);
        return $page;
    }

    public function Advancedquery($data, $currPage = 1, $pageSize = 10) {

        //解析搜索条件
        $condition = "where   ";
        $i = 0;
        $len = intval(count($data['User']));


        foreach ($data['User'] as $key => $value) {

            //判断是否存在搜索条件
            if ($value == '') {
                continue;
            }
            $tmp = "users." . $key . "='" . $value . "'  and   ";
            $condition = $condition . $tmp;
            $i++;
        }


        $where = substr($condition, 0, strrpos($condition, 'a'));
        //pr($where);
        //分页信息
        require_once 'MyPage.php';
        $page = new MyPage();
        $totalrecords = $this->query("select count(user_id) as c from users  $where");

        $page->setTotalRecords($totalrecords[0][0]['c']); //总记录数
        $page->setCurrPage($currPage); //当前页
        $page->setPageSize($pageSize); //页大小
        //$page = $page->checkRange($page);//检查当前页范围

        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();
        $offset = $currPage * $pageSize;
        $sql = "select users.user_id,users.name,reseller_name,role_name ,users.create_time,users.password,users.active
		    from  users 
	  left join (select name as reseller_name,reseller_id  from reseller  ) reseller   on  reseller.reseller_id=users.reseller_id
	left join (select   role_name,role_id from role ) role   on  role.role_id=users.role_id	 
	$where   
	order by users.user_id  	limit '$pageSize' offset '$offset'";

        $results = $this->query($sql);

        $page->setDataArray($results);
        return $page;
    }

    public function get_network_total($duration) {
        $duration = (int)$duration;
        switch ($duration) {
            case 1:
                $time = "1 hours";
                break;
            case 2:
                $time = "24 hours";
                break;
            case 3:
                $time = "168 hours";
                break;
            case 4:
                $time = "30 minutes";
                break;
            case 5:
                $time = "15 minutes";
                break;
            case 6:
                $time = "15 days";
                break;
            case 7:
                $time = "30 days";
                break;
            case 8:
                $time = "60 days";
                break;
        }
        $sql = "SELECT report_time, sum(call) as call, sum(cps) as cps, sum(channels) as channel  FROM qos_total WHERE  report_time BETWEEN CURRENT_TIMESTAMP - interval '{$time}' 
                AND CURRENT_TIMESTAMP GROUP BY report_time  ORDER BY report_time ASC";
        if ($duration >= 7)
                $sql = "SELECT to_char(report_time,  'YYYY-MM-DD HH24:00:00') as report_time, max(call) as call, max(cps) as cps, max(channel) as channel FROM (SELECT report_time, sum(call) as call, sum(cps) as cps, sum(channels) as channel  FROM qos_total WHERE  report_time BETWEEN CURRENT_TIMESTAMP - interval '{$time}' 
                AND CURRENT_TIMESTAMP GROUP BY report_time) AS t GROUP BY to_char(report_time,  'YYYY-MM-DD HH24:00:00')
ORDER BY 1 ASC";  
        return $this->query($sql);
        
    }
    
    public function get_network_call_atempts($duration ,$type)
    {
        $duration = (int)$duration;
        $type = (int)$type;
        switch ($duration) {
            case 1:
                $time = "1 hours";
                break;
            case 2:
                $time = "24 hours";
                break;
            case 3:
                $time = "168 hours";
                break;
            case 4:
                $time = "30 minutes";
                break;
            case 5:
                $time = "15 minutes";
                break;
            case 6:
                $time = "15 days";
                break;
            case 7:
                $time = "30 days";
                break;
            case 8:
                $time = "60 days";
                break;
        }
        switch ($type) {
        	case 1:
        		$field = "channels";
        		break;
        	case 2:
        		$field = "ingress_channels";
        		break;
        	case 3:
        		$field = "egress_channels";
        		break;
        }
        
        $sql = "SELECT report_time, sum({$field}) as channels  FROM qos_total WHERE  report_time BETWEEN CURRENT_TIMESTAMP - interval '{$time}' 
                AND CURRENT_TIMESTAMP and {$field} is not null GROUP BY report_time  ORDER BY report_time ASC";
        if ($duration >= 7)
                $sql = "SELECT to_char(report_time,  'YYYY-MM-DD HH24:00:00') as report_time, sum({$field}) as channels  FROM (SELECT report_time, sum({$field}) as channels  FROM qos_total WHERE  report_time BETWEEN CURRENT_TIMESTAMP - interval '{$time}' 
                AND CURRENT_TIMESTAMP and {$field} is not null GROUP BY report_time) AS t GROUP BY to_char(report_time,  'YYYY-MM-DD HH24:00:00')
ORDER BY 1 ASC";        
        $result =  $this->query($sql);
        $draw_lines = array();
        foreach($result as $item)
        {
            array_push($draw_lines, sprintf('<point x="%.11E" y="%s" />', strtotime($item[0]['report_time']) * 1000, $item[0]['channels'] == null ? 0 : $item[0]['channels']));
        }
        $draw_line = implode("\r\n", $draw_lines);
        $content = <<<EOT
<chart type="cartesian" legendPlacement="top">
  <xaxis type="datetime" />
  <yaxis type="linear" label="CALL" />
  <data>
      <series type="line" label="Total">
          {$draw_line}
      </series>
  </data>
</chart>
EOT;
        return array(
            'call' => $content,
            'cps' => '<chart type="cartesian" legendPlacement="top">
  <xaxis type="datetime" />
  <yaxis type="linear" label="CPS" />
  <data>
      <series type="line" label="Total">
          
      </series>
  </data>
</chart>',
             'channel' => '<chart type="cartesian" legendPlacement="top">
  <xaxis type="datetime" />
  <yaxis type="linear" label="Channel" />
  <data>
      <series type="line" label="Total">
          
      </series>
  </data>
</chart>',
        );
    }
    
    
    public function get_draw_trunk_data($type, $duration, $trunk, $trunk_ip) {
        
        $draw_lines_call = "";
        $draw_lines_cps = "";
        $draw_lines_channel = "";
        
        $duration = (int)$duration;

        switch ($duration) {
            case 1:
                $time = "1 hours";
                break;
            case 2:
                $time = "24 hours";
                break;
            case 3:
                $time = "168 hours";
                break;
            case 4:
                $time = "30 minutes";
                break;
            case 5:
                $time = "15 minutes";
                break;
            case 6:
                $time = "15 days";
                break;
            case 7:
                $time = "30 days";
                break;
            case 8:
                $time = "60 days";
                break;
        }


        if (empty($trunk_ip)) {
            $sql_total = "SELECT  report_time, sum(call) as call, sum(cps) as cps, sum(channels) as channel  FROM qos_resource  WHERE 
report_time BETWEEN CURRENT_TIMESTAMP - interval '{$time}' AND CURRENT_TIMESTAMP AND direction = {$type}
GROUP BY report_time
ORDER BY report_time ASC";
            if ($duration >= 7)
                $sql_total = "SELECT to_char(report_time,  'YYYY-MM-DD HH24:00:00') as report_time, max(call) as call, max(cps) as cps, max(channel) as channel FROM (SELECT  report_time, sum(call) as call, sum(cps) as cps, sum(channels) as channel  FROM qos_resource  WHERE 
report_time BETWEEN CURRENT_TIMESTAMP - interval '{$time}' AND CURRENT_TIMESTAMP AND direction = {$type}
GROUP BY report_time) AS t GROUP BY to_char(report_time,  'YYYY-MM-DD HH24:00:00')
ORDER BY 1 ASC";

        } else {
            $sql_total = "SELECT  report_time, sum(call) as call, sum(cps) as cps, sum(channels) as channel  FROM qos_ip  WHERE 
report_time BETWEEN CURRENT_TIMESTAMP - interval '{$time}' AND CURRENT_TIMESTAMP AND direction = {$type}
GROUP BY report_time
ORDER BY report_time ASC";
            if ($duration >= 7)
                $sql_total = "SELECT to_char(report_time,  'YYYY-MM-DD HH24:00:00') as report_time, max(call) as call, max(cps) as cps, max(channel) as channel FROM (SELECT  report_time, sum(call) as call, sum(cps) as cps, sum(channels) as channel  FROM qos_ip  WHERE 
report_time BETWEEN CURRENT_TIMESTAMP - interval '{$time}' AND CURRENT_TIMESTAMP AND direction = {$type}
GROUP BY report_time) AS t GROUP BY to_char(report_time,  'YYYY-MM-DD HH24:00:00')
ORDER BY 1 ASC";
        }


        $total_results = $this->query($sql_total);
        $draw_lines_call .= "<series type=\"line\" label=\"Total\">";
        $draw_lines_cps .= "<series type=\"line\" label=\"Total\">";
        $draw_lines_channel .= "<series type=\"line\" label=\"Total\">";
        foreach ($total_results as $total_item) {
            if ($total_item[0]['call'] > 0)
            $draw_lines_call .= sprintf('<point x="%.11E" y="%s" />', strtotime($total_item[0]['report_time']) * 1000, $total_item[0]['call']);
            if ($total_item[0]['cps'] > 0)
            $draw_lines_cps .= sprintf('<point x="%.11E" y="%s" />', strtotime($total_item[0]['report_time']) * 1000, $total_item[0]['cps']);
            if ($total_item[0]['channel'] > 0)
            $draw_lines_channel .= sprintf('<point x="%.11E" y="%s" />', strtotime($total_item[0]['report_time']) * 1000, $total_item[0]['channel']);
        }
        if (empty($total_results)) {
                $draw_lines_call    .= sprintf('<point x="%.11E" y="%s" />', time() * 1000, 0);
                $draw_lines_cps    .= sprintf('<point x="%.11E" y="%s" />', time() * 1000, 0);
                $draw_lines_channel .= sprintf('<point x="%.11E" y="%s" />', time() * 1000, 0);
        }
        
        $draw_lines_call .= "</series>";
        $draw_lines_cps .= "</series>";
        $draw_lines_channel .= "</series>";

        // ORIG
        if (!empty($trunk_ip)) {
            $sql = "select resource_ip_id, ip from resource_ip WHERE resource_ip_id = {$trunk_ip}";
            $result = $this->query($sql);
            foreach ($result as $item) {
                $draw_lines_call .= "<series type=\"line\" label=\"{$item[0]['ip']}\">";
                $draw_lines_cps .= "<series type=\"line\" label=\"{$item[0]['ip']}\">";
                $draw_lines_channel .= "<series type=\"line\" label=\"{$item[0]['ip']}\">";
                $sql_item = "SELECT 
    report_time, sum(call) as call, sum(cps) as cps, sum(channels) as channel
    FROM qos_ip 
    WHERE 
    report_time BETWEEN CURRENT_TIMESTAMP - interval '{$time}' AND CURRENT_TIMESTAMP 
    AND ip_id  = {$item[0]['resource_ip_id']} AND direction = {$type}
    GROUP BY report_time
    ORDER BY report_time ASC";
                if ($duration >= 7)
                    $sql_item = "SELECT to_char(report_time,  'YYYY-MM-DD HH24:00:00') as report_time, max(call) as call, max(cps) as cps, max(channel) as channel FROM (SELECT 
    report_time, sum(call) as call, sum(cps) as cps, sum(channels) as channel
    FROM qos_ip 
    WHERE 
    report_time BETWEEN CURRENT_TIMESTAMP - interval '{$time}' AND CURRENT_TIMESTAMP 
    AND ip_id  = {$item[0]['resource_ip_id']} AND direction = {$type}
    GROUP BY report_time) AS t GROUP BY to_char(report_time,  'YYYY-MM-DD HH24:00:00')
ORDER BY 1 ASC";
                $item_results = $this->query($sql_item);
                foreach ($item_results as $item_item) {
                    if ($item_item[0]['call'] > 0)
                    $draw_lines_call .= sprintf('<point x="%.11E" y="%s" />', strtotime($item_item[0]['report_time']) * 1000, $item_item[0]['call']);
                    if ($item_item[0]['cps'] > 0)
                    $draw_lines_cps .= sprintf('<point x="%.11E" y="%s" />', strtotime($item_item[0]['report_time']) * 1000, $item_item[0]['cps']);
                    if ($item_item[0]['channel'] > 0)
                    $draw_lines_channel .= sprintf('<point x="%.11E" y="%s" />', strtotime($item_item[0]['report_time']) * 1000, $item_item[0]['channel']);
                }
                if (empty($item_results)) {
                        $draw_lines_call    .= sprintf('<point x="%.11E" y="%s" />', time() * 1000, 0);
                        $draw_lines_cps    .= sprintf('<point x="%.11E" y="%s" />', time() * 1000, 0);
                        $draw_lines_channel .= sprintf('<point x="%.11E" y="%s" />', time() * 1000, 0);
                }
                $draw_lines_cps .= "</series>";
                $draw_lines_call .= "</series>";
                $draw_lines_channel .= "</series>";
            }
        } else {
            if (!ctype_digit($trunk)) {          
                $limit = "";
                switch($trunk) {
                    case "top5":
                        $limit = "LIMIT 5";
                        break;
                    case "top10":
                        $limit = "LIMIT 10";
                        break;
                    case "top15":
                        $limit = "LIMIT 15";
                        break;
                    case "top20":
                        $limit = "LIMIT 20";
                        break;
                    case "all":
                        $limit = "";
                        break;
                }
                $sql = "SELECT res_id, (SELECT alias FROM resource WHERE resource_id = res_id) as name FROM qos_resource 
WHERE report_time BETWEEN CURRENT_TIMESTAMP - interval '{$time}' AND CURRENT_TIMESTAMP AND direction = {$type}  GROUP BY res_id ORDER BY sum(call) DESC {$limit}";
                $result = $this->query($sql);
                foreach($result as $item)
                {
                    $draw_lines_call .= "<series type=\"line\" label=\"{$item[0]['name']}\">";
                    $sql_item = "SELECT 
        report_time, sum(call) as call
        FROM qos_resource 
        WHERE 
        report_time BETWEEN CURRENT_TIMESTAMP - interval '{$time}' AND CURRENT_TIMESTAMP 
        AND res_id = {$item[0]['res_id']} AND direction = {$type}
        GROUP BY report_time
        ORDER BY report_time ASC";
                    if ($duration >= 7)
                        $sql_item = "SELECT to_char(report_time,  'YYYY-MM-DD HH24:00:00') as report_time, max(call) as call, max(cps) as cps, max(channel) as channel FROM (SELECT 
        report_time, sum(call) as call
        FROM qos_resource 
        WHERE 
        report_time BETWEEN CURRENT_TIMESTAMP - interval '{$time}' AND CURRENT_TIMESTAMP 
        AND res_id = {$item[0]['res_id']} AND direction = {$type}
        GROUP BY report_time) AS t GROUP BY to_char(report_time,  'YYYY-MM-DD HH24:00:00') as report_time
ORDER BY 1 ASC";
                    $item_results = $this->query($sql_item);
                    foreach ($item_results as $item_item) {
                        if ($item_item[0]['call'] > 0)
                        $draw_lines_call .= sprintf('<point x="%.11E" y="%s" />', strtotime($item_item[0]['report_time']) * 1000, $item_item[0]['call']);
                    }
                    if (empty($item_results))
                        $draw_lines_call .= sprintf('<point x="%.11E" y="%s" />', time() * 1000, 0);
                    $draw_lines_call .= "</series>";
                }
                
                $sql = "SELECT res_id, (SELECT alias FROM resource WHERE resource_id = res_id) as name FROM qos_resource 
WHERE report_time BETWEEN CURRENT_TIMESTAMP - interval '{$time}' AND CURRENT_TIMESTAMP AND direction = {$type}  GROUP BY res_id ORDER BY sum(cps) DESC {$limit}";

                $result = $this->query($sql);
                foreach($result as $item)
                {
                    $draw_lines_cps .= "<series type=\"line\" label=\"{$item[0]['name']}\">";
                    $sql_item = "SELECT 
        report_time, sum(cps) as cps
        FROM qos_resource 
        WHERE 
        report_time BETWEEN CURRENT_TIMESTAMP - interval '{$time}' AND CURRENT_TIMESTAMP 
        AND res_id = {$item[0]['res_id']} AND direction = {$type}
        GROUP BY report_time
        ORDER BY report_time ASC";
                    if ($duration >= 7)
                        $sql_item = "SELECT to_char(report_time,  'YYYY-MM-DD HH24:00:00') as report_time, max(call) as call, max(cps) as cps, max(channel) as channel FROM (SELECT 
        report_time, sum(cps) as cps
        FROM qos_resource 
        WHERE 
        report_time BETWEEN CURRENT_TIMESTAMP - interval '{$time}' AND CURRENT_TIMESTAMP 
        AND res_id = {$item[0]['res_id']} AND direction = {$type}
        GROUP BY report_time) AS t GROUP BY to_char(report_time,  'YYYY-MM-DD HH24:00:00') as report_time
ORDER BY 1 ASC";
                    $item_results = $this->query($sql_item);
                    foreach ($item_results as $item_item) {
                        if ($item_item[0]['cps'] > 0)
                        $draw_lines_cps .= sprintf('<point x="%.11E" y="%s" />', strtotime($item_item[0]['report_time']) * 1000, $item_item[0]['cps']);
                    }
                    if (empty($item_results))
                        $draw_lines_cps .= sprintf('<point x="%.11E" y="%s" />', time() * 1000, 0);
                    $draw_lines_cps .= "</series>";
                }
                
                //beign channel
                 $sql = "SELECT res_id, (SELECT alias FROM resource WHERE resource_id = res_id) as name FROM qos_resource 
WHERE report_time BETWEEN CURRENT_TIMESTAMP - interval '{$time}' AND CURRENT_TIMESTAMP AND direction = {$type}  GROUP BY res_id ORDER BY sum(channels) DESC {$limit}";
                $result = $this->query($sql);
                foreach($result as $item)
                {
                    $draw_lines_channel .= "<series type=\"line\" label=\"{$item[0]['name']}\">";
                    $sql_item = "SELECT 
        report_time, sum(channels) as channel
        FROM qos_resource 
        WHERE 
        report_time BETWEEN CURRENT_TIMESTAMP - interval '{$time}' AND CURRENT_TIMESTAMP 
        AND res_id = {$item[0]['res_id']} AND direction = {$type}
        GROUP BY report_time
        ORDER BY report_time ASC";
                    if ($duration >= 7)
                        $sql_item = "SELECT to_char(report_time,  'YYYY-MM-DD HH24:00:00') as report_time, max(call) as call, max(cps) as cps, max(channel) as channel FROM (SELECT 
        report_time, sum(channels) as channel
        FROM qos_resource 
        WHERE 
        report_time BETWEEN CURRENT_TIMESTAMP - interval '{$time}' AND CURRENT_TIMESTAMP 
        AND res_id = {$item[0]['res_id']} AND direction = {$type}
        GROUP BY report_time) AS t GROUP BY to_char(report_time,  'YYYY-MM-DD HH24:00:00') as report_time
ORDER BY 1 ASC";
                    $item_results = $this->query($sql_item);
                    foreach ($item_results as $item_item) {
                        if ($item_item[0]['channel'] > 0)
                        $draw_lines_channel .= sprintf('<point x="%.11E" y="%s" />', strtotime($item_item[0]['report_time']) * 1000, $item_item[0]['channel']);
                    }
                    if (empty($item_results)) {
                            $draw_lines_channel .= sprintf('<point x="%.11E" y="%s" />', time() * 1000, 0);
                    }
                    $draw_lines_channel .= "</series>";
                }
                // end channel

            } else { 
                $sql = "SELECT resource_id as res_id, alias as name FROM resource WHERE resource_id = {$trunk}";
                $result = $this->query($sql);
            foreach ($result as $item) {
                $draw_lines_call .= "<series type=\"line\" label=\"{$item[0]['name']}\">";
                $draw_lines_cps .= "<series type=\"line\" label=\"{$item[0]['name']}\">";
                $draw_lines_channel .= "<series type=\"line\" label=\"{$item[0]['name']}\">";
                $sql_item = "SELECT 
    report_time, sum(call) as call, sum(cps) as cps, sum(channels) as channel
    FROM qos_resource 
    WHERE 
    report_time BETWEEN CURRENT_TIMESTAMP - interval '{$time}' AND CURRENT_TIMESTAMP 
    AND res_id = {$item[0]['res_id']} AND direction = {$type}
    GROUP BY report_time
    ORDER BY report_time ASC";
                if ($duration >= 7)
                        $sql_item = "SELECT to_char(report_time,  'YYYY-MM-DD HH24:00:00') as report_time, max(call) as call, max(cps) as cps, max(channel) as channel FROM (SELECT 
    report_time, sum(call) as call, sum(cps) as cps, sum(channels) as channel
    FROM qos_resource 
    WHERE 
    report_time BETWEEN CURRENT_TIMESTAMP - interval '{$time}' AND CURRENT_TIMESTAMP 
    AND res_id = {$item[0]['res_id']} AND direction = {$type}
    GROUP BY report_time) AS t GROUP BY to_char(report_time,  'YYYY-MM-DD HH24:00:00') as report_time
ORDER BY 1 ASC";
                $item_results = $this->query($sql_item);
                foreach ($item_results as $item_item) {
                    if ($item_item[0]['call'] > 0)
                    $draw_lines_call .= sprintf('<point x="%.11E" y="%s" />', strtotime($item_item[0]['report_time']) * 1000, $item_item[0]['call']);
                    if ($item_item[0]['cps'] > 0)
                    $draw_lines_cps .= sprintf('<point x="%.11E" y="%s" />', strtotime($item_item[0]['report_time']) * 1000, $item_item[0]['cps']);
                    if ($item_item[0]['channel'] > 0)
                    $draw_lines_channel .= sprintf('<point x="%.11E" y="%s" />', strtotime($item_item[0]['report_time']) * 1000, $item_item[0]['channel']);
                }
                if (empty($item_results)) {
                        $draw_lines_call    .= sprintf('<point x="%.11E" y="%s" />', time() * 1000, 0);
                        $draw_lines_cps    .= sprintf('<point x="%.11E" y="%s" />', time() * 1000, 0);
                        $draw_lines_channel .= sprintf('<point x="%.11E" y="%s" />', time() * 1000, 0);
                }
                $draw_lines_cps .= "</series>";
                $draw_lines_call .= "</series>";
                $draw_lines_channel .= "</series>";
            }
            }

            
        }


        $content_call = <<<EOT
<chart type="cartesian" legendPlacement="top">
  <xaxis type="datetime" />
  <yaxis type="linear" label="Call" />
  <data>
      {$draw_lines_call}
  </data>
</chart>
EOT;
        $content_cps = <<<EOT
<chart type="cartesian" legendPlacement="top">
  <xaxis type="datetime" />
  <yaxis type="linear" label="CPS" />
  <data>
      {$draw_lines_cps}
  </data>
</chart>
EOT;
       $content_channel = <<<EOT
<chart type="cartesian" legendPlacement="top">
  <xaxis type="datetime" />
  <yaxis type="linear" label="Channel" />
  <data>
      {$draw_lines_channel}
  </data>
</chart>      
EOT;
        return array('call' => $content_call, 'cps' => $content_cps, 'channel' => $content_channel);
    }

    /*
     * $type 0 Orig 1 Term
     */

    public function get_draw_trunk_data1($type, $duration, $trunk, $trunk_ip) {
        $draw_lines_call = "";
        $draw_lines_cps = "";
        
        $duration = (int)$duration;

        switch ($duration) {
            case 1:
                $time = "1 hours";
                break;
            case 2:
                $time = "24 hours";
                break;
            case 3:
                $time = "168 hours";
                break;
            case 4:
                $time = "30 minutes";
                break;
            case 5:
                $time = "15 minutes";
                break;
        }


        if (empty($trunk_ip)) {
            $sql_total = "SELECT  report_time, sum(call) as call, sum(cps) as cps  FROM qos_resource  WHERE 
report_time BETWEEN CURRENT_TIMESTAMP - interval '{$time}' AND CURRENT_TIMESTAMP AND direction = {$type}
GROUP BY report_time
ORDER BY report_time ASC";
        } else {
            $sql_total = "SELECT  report_time, sum(call) as call, sum(cps) as cps  FROM qos_ip  WHERE 
report_time BETWEEN CURRENT_TIMESTAMP - interval '{$time}' AND CURRENT_TIMESTAMP AND direction = {$type}
GROUP BY report_time
ORDER BY report_time ASC";
        }




        $total_results = $this->query($sql_total);
        $draw_lines_call .= "<series type=\"line\" label=\"Total\">";
        $draw_lines_cps .= "<series type=\"line\" label=\"Total\">";
        foreach ($total_results as $total_item) {
            if ($item_item[0]['call'] > 0)
            $draw_lines_call .= sprintf('<point x="%.11E" y="%s" />', strtotime($total_item[0]['report_time']) * 1000, $total_item[0]['call']);
            if ($item_item[0]['cps'] > 0)
            $draw_lines_cps .= sprintf('<point x="%.11E" y="%s" />', strtotime($total_item[0]['report_time']) * 1000, $total_item[0]['cps']);
        }
        $draw_lines_call .= "</series>";
        $draw_lines_cps .= "</series>";


        // ORIG
        if (!empty($trunk_ip)) {
            $sql = "select resource_ip_id, ip from resource_ip WHERE resource_ip_id = {$trunk_ip}";
            $result = $this->query($sql);
            foreach ($result as $item) {
                $draw_lines_call .= "<series type=\"line\" label=\"{$item[0]['ip']}\">";
                $draw_lines_cps .= "<series type=\"line\" label=\"{$item[0]['ip']}\">";
                $sql_item = "SELECT 
    report_time, sum(call) as call, sum(cps) as cps
    FROM qos_ip 
    WHERE 
    report_time BETWEEN CURRENT_TIMESTAMP - interval '{$time}' AND CURRENT_TIMESTAMP 
    AND ip_id  = {$item[0]['resource_ip_id']} AND direction = {$type}
    GROUP BY report_time
    ORDER BY report_time ASC";
                $item_results = $this->query($sql_item);
                foreach ($item_results as $item_item) {
                    if ($item_item[0]['call'] > 0)
                    $draw_lines_call .= sprintf('<point x="%.11E" y="%s" />', strtotime($item_item[0]['report_time']) * 1000, $item_item[0]['call']);
                    if ($item_item[0]['cps'] > 0)
                    $draw_lines_cps .= sprintf('<point x="%.11E" y="%s" />', strtotime($item_item[0]['report_time']) * 1000, $item_item[0]['cps']);
                }
                $draw_lines_cps .= "</series>";
                $draw_lines_call .= "</series>";
            }
        } else {
            if (!ctype_digit($trunk)) {
                $limit = "";
                switch($trunk) {
                    case "top5":
                        $limit = "LIMIT 5";
                        break;
                    case "top10":
                        $limit = "LIMIT 10";
                        break;
                    case "top15":
                        $limit = "LIMIT 15";
                        break;
                    case "top20":
                        $limit = "LIMIT 20";
                        break;
                    case "all":
                        $limit = "";
                        break;
                }
                $sql = "SELECT res_id, (SELECT alias FROM resource WHERE resource_id = res_id) as name FROM qos_resource 
WHERE report_time BETWEEN CURRENT_TIMESTAMP - interval '{$time}' AND CURRENT_TIMESTAMP AND direction = {$type}  GROUP BY res_id ORDER BY sum(call) DESC {$limit}";
            } else {
                $sql = "SELECT resource_id as res_id, alias as name FROM resource WHERE resource_id = {$trunk}";
            }

            $result = $this->query($sql);
            foreach ($result as $item) {
                $draw_lines_call .= "<series type=\"line\" label=\"{$item[0]['name']}\">";
                $draw_lines_cps .= "<series type=\"line\" label=\"{$item[0]['name']}\">";
                $sql_item = "SELECT 
    report_time, sum(call) as call, sum(cps) as cps
    FROM qos_resource 
    WHERE 
    report_time BETWEEN CURRENT_TIMESTAMP - interval '{$time}' AND CURRENT_TIMESTAMP 
    AND res_id = {$item[0]['res_id']} AND direction = {$type}
    GROUP BY report_time
    ORDER BY report_time ASC";
                $item_results = $this->query($sql_item);
                foreach ($item_results as $item_item) {
                    if ($item_item[0]['call'] > 0)
                    $draw_lines_call .= sprintf('<point x="%.11E" y="%s" />', strtotime($item_item[0]['report_time']) * 1000, $item_item[0]['call']);
                    if ($item_item[0]['cps'] > 0)
                    $draw_lines_cps .= sprintf('<point x="%.11E" y="%s" />', strtotime($item_item[0]['report_time']) * 1000, $item_item[0]['cps']);
                }
                $draw_lines_cps .= "</series>";
                $draw_lines_call .= "</series>";
            }
        }


        $content_call = <<<EOT
<chart type="cartesian" legendPlacement="top">
  <xaxis type="datetime" />
  <yaxis type="linear" label="Channel" />
  <data>
      {$draw_lines_call}
  </data>
</chart>
EOT;
        $content_cps = <<<EOT
<chart type="cartesian" legendPlacement="top">
  <xaxis type="datetime" />
  <yaxis type="linear" label="CPS" />
  <data>
      {$draw_lines_cps}
  </data>
</chart>
EOT;
        return array('call' => $content_call, 'cps' => $content_cps);
    }

    public function get_asr_chart($report_type, $start_time, $end_time, $timezone, $country, $destination, $ingress_trunk, $egress_trunk, $group_time_str) {

        if ($report_type == 0) {
            $where = array();
            if (!empty($country)) {
                array_push($where, "ingress_country = '{$country}'");
            }
            if (!empty($destination)) {
                array_push($where, "ingress_code_name = '{$destination}'");
            }
            if (!empty($ingress_trunk)) {
                array_push($where, "ingress_id = {$ingress_trunk}");
            }
            
            if (!empty($egress_trunk)) {
                array_push($where, "egress_id = {$egress_trunk}");
            }
            array_push($where, "report_time between '{$start_time} {$timezone}' and '{$end_time} {$timezone}'");
            $where_str = implode(" and ", $where);
            $sql = "SELECT 
    $group_time_str as report_time,
    sum(not_zero_calls) as not_zero_calls, 
    sum(ingress_busy_calls) as busy_calls, 
    sum(ingress_cancel_calls) as cancel_calls 
    from cdr_report_detail 
    where $where_str GROUP BY $group_time_str ORDER BY $group_time_str ASC";
        } else {
            $where = array();
            if (!empty($country)) {
                array_push($where, "egress_country = '{$country}'");
            }
            if (!empty($destination)) {
                array_push($where, "egress_code_name = '{$destination}'");
            }
            if (!empty($ingress_trunk)) {
                array_push($where, "ingress_id = {$ingress_trunk}");
            }
            
            if (!empty($egress_trunk)) {
                array_push($where, "egress_id = {$egress_trunk}");
            }
            array_push($where, "report_time between '{$start_time} {$timezone}' and '{$end_time} {$timezone}'");
            $where_str = implode(" and ", $where);
            $sql = "SELECT 
    $group_time_str as report_time,
    sum(not_zero_calls) as not_zero_calls, 
    sum(egress_busy_calls) as busy_calls, 
    sum(egress_cancel_calls) as cancel_calls 
    from cdr_report_detail 
    where $where_str GROUP BY $group_time_str ORDER BY $group_time_str ASC";
        }


        $result = $this->query($sql);


        $draw_lines = array();

        foreach ($result as $item) {
            $line = ($item[0]['busy_calls'] + $item[0]['cancel_calls'] + $item[0]['not_zero_calls']) == 0 ? 0 : round($item[0]['not_zero_calls'] / ($item[0]['busy_calls'] + $item[0]['cancel_calls'] + $item[0]['not_zero_calls']) * 100, 2);
            array_push($draw_lines, sprintf('<point x="%.11E" y="%s" />', strtotime($item[0]['report_time']) * 1000, $line));
        }

        $draw_line = implode("\r\n", $draw_lines);

        $content = <<<EOT
<chart type="cartesian" legendPlacement="bottom">
  <xaxis type="datetime" />
  <yaxis type="linear" label="ASR" />
  <data>
      <series type="line" label="ASR">
          {$draw_line}
      </series>
  </data>
</chart>
EOT;
        return $content;
    }

    public function get_acd_chart($report_type, $start_time, $end_time, $timezone, $country, $destination, $ingress_trunk, $egress_trunk, $group_time_str) {

        if ($report_type == 0) {
            $where = array();
            if (!empty($country)) {
                array_push($where, "ingress_country = '{$country}'");
            }
            if (!empty($destination)) {
                array_push($where, "ingress_code_name = '{$destination}'");
            }
            if (!empty($ingress_trunk)) {
                array_push($where, "ingress_id = {$ingress_trunk}");
            }
            
            if (!empty($egress_trunk)) {
                array_push($where, "egress_id = {$egress_trunk}");
            }
            array_push($where, "report_time between '{$start_time} {$timezone}' and '{$end_time} {$timezone}'");
            $where_str = implode(" and ", $where);
            $sql = "SELECT 
    $group_time_str as report_time,
    sum(duration) as duration,
    sum(not_zero_calls) as not_zero_calls
    from cdr_report_detail 
    where $where_str GROUP BY $group_time_str ORDER BY $group_time_str ASC";
        } else {
            $where = array();
            if (!empty($country)) {
                array_push($where, "egress_country = '{$country}'");
            }
            if (!empty($destination)) {
                array_push($where, "egress_code_name = '{$destination}'");
            }
            if (!empty($ingress_trunk)) {
                array_push($where, "ingress_id = {$ingress_trunk}");
            }
            
            if (!empty($egress_trunk)) {
                array_push($where, "egress_id = {$egress_trunk}");
            }
            array_push($where, "report_time between '{$start_time} {$timezone}' and '{$end_time} {$timezone}'");
            $where_str = implode(" and ", $where);
            $sql = "SELECT 
    $group_time_str as report_time,
    sum(duration) as duration,
    sum(not_zero_calls) as not_zero_calls
    from cdr_report_detail 
    where $where_str GROUP BY $group_time_str ORDER BY $group_time_str ASC";
        }


        $result = $this->query($sql);


        $draw_lines = array();

        foreach ($result as $item) {
            $line = round($item[0]['not_zero_calls'] == 0 ? 0 : $item[0]['duration'] / $item[0]['not_zero_calls'] / 60, 2);
            array_push($draw_lines, sprintf('<point x="%.11E" y="%s" />', strtotime($item[0]['report_time']) * 1000, $line));
        }

        $draw_line = implode("\r\n", $draw_lines);

        $content = <<<EOT
<chart type="cartesian" legendPlacement="bottom">
  <xaxis type="datetime" />
  <yaxis type="linear" label="ACD" />
  <data>
      <series type="line" label="ACD">
          {$draw_line}
      </series>
  </data>
</chart>
EOT;
        return $content;
    }

    public function get_total_calls_chart($report_type, $start_time, $end_time, $timezone, $country, $destination, $ingress_trunk, $egress_trunk, $group_time_str) {

        if ($report_type == 0) {
            $where = array();
            if (!empty($country)) {
                array_push($where, "ingress_country = '{$country}'");
            }
            if (!empty($destination)) {
                array_push($where, "ingress_code_name = '{$destination}'");
            }
            
            if (!empty($ingress_trunk)) {
                array_push($where, "ingress_id = {$ingress_trunk}");
            }
            
            if (!empty($egress_trunk)) {
                array_push($where, "egress_id = {$egress_trunk}");
            }
            array_push($where, "report_time between '{$start_time} {$timezone}' and '{$end_time} {$timezone}'");
            $where_str = implode(" and ", $where);
            $sql = "SELECT 
    $group_time_str as report_time,
    sum(ingress_total_calls) as total_calls
    from cdr_report_detail 
    where $where_str GROUP BY $group_time_str ORDER BY $group_time_str ASC";
        } else {
            $where = array();
            if (!empty($country)) {
                array_push($where, "egress_country = '{$country}'");
            }
            if (!empty($destination)) {
                array_push($where, "egress_code_name = '{$destination}'");
            }
            
            if (!empty($ingress_trunk)) {
                array_push($where, "ingress_id = {$ingress_trunk}");
            }
            
            if (!empty($egress_trunk)) {
                array_push($where, "egress_id = {$egress_trunk}");
            }
            
            array_push($where, "report_time between '{$start_time} {$timezone}' and '{$end_time} {$timezone}'");
            $where_str = implode(" and ", $where);
            $sql = "SELECT 
    $group_time_str as report_time,
    sum(egress_total_calls) as total_calls
    from cdr_report_detail 
    where $where_str GROUP BY $group_time_str ORDER BY $group_time_str ASC";
        }


        $result = $this->query($sql);


        $draw_lines = array();

        foreach ($result as $item) {
            $line = round($item[0]['total_calls']);
            array_push($draw_lines, sprintf('<point x="%.11E" y="%s" />', strtotime($item[0]['report_time']) * 1000, $line));
        }

        $draw_line = implode("\r\n", $draw_lines);

        $content = <<<EOT
<chart type="cartesian" legendPlacement="bottom">
  <xaxis type="datetime" />
  <yaxis type="linear" label="Total Calls" />
  <data>
      <series type="line" label="Total Calls">
          {$draw_line}
      </series>
  </data>
</chart>
EOT;
        return $content;
    }

    public function get_total_billable_time_chart($report_type, $start_time, $end_time, $timezone, $country, $destination, $ingress_trunk, $egress_trunk, $group_time_str) {

        if ($report_type == 0) {
            $where = array();
            if (!empty($country)) {
                array_push($where, "ingress_country = '{$country}'");
            }
            if (!empty($destination)) {
                array_push($where, "ingress_code_name = '{$destination}'");
            }
            if (!empty($ingress_trunk)) {
                array_push($where, "ingress_id = {$ingress_trunk}");
            }
            
            if (!empty($egress_trunk)) {
                array_push($where, "egress_id = {$egress_trunk}");
            }
            array_push($where, "report_time between '{$start_time} {$timezone}' and '{$end_time} {$timezone}'");
            $where_str = implode(" and ", $where);
            $sql = "SELECT 
    $group_time_str as report_time,
    sum(ingress_bill_time) as bill_time
    from cdr_report_detail 
    where $where_str GROUP BY $group_time_str ORDER BY $group_time_str ASC";
        } else {
            $where = array();
            if (!empty($country)) {
                array_push($where, "egress_country = '{$country}'");
            }
            if (!empty($destination)) {
                array_push($where, "egress_code_name = '{$destination}'");
            }
            if (!empty($ingress_trunk)) {
                array_push($where, "ingress_id = {$ingress_trunk}");
            }
            
            if (!empty($egress_trunk)) {
                array_push($where, "egress_id = {$egress_trunk}");
            }
            array_push($where, "report_time between '{$start_time} {$timezone}' and '{$end_time} {$timezone}'");
            $where_str = implode(" and ", $where);
            $sql = "SELECT 
    $group_time_str as report_time,
    sum(egress_bill_time) as bill_time
    from cdr_report_detail 
    where $where_str GROUP BY $group_time_str ORDER BY $group_time_str ASC";
        }


        $result = $this->query($sql);


        $draw_lines = array();

        foreach ($result as $item) {
            $line = round($item[0]['bill_time'] / 60, 2);
            array_push($draw_lines, sprintf('<point x="%.11E" y="%s" />', strtotime($item[0]['report_time']) * 1000, $line));
        }

        $draw_line = implode("\r\n", $draw_lines);

        $content = <<<EOT
<chart type="cartesian" legendPlacement="bottom">
  <xaxis type="datetime" />
  <yaxis type="linear" label="Total Billable Time" />
  <data>
      <series type="line" label="Total Billable Time">
          {$draw_line}
      </series>
  </data>
</chart>
EOT;
        return $content;
    }

    public function get_total_pdd_chart($report_type, $start_time, $end_time, $timezone, $country, $destination, $ingress_trunk, $egress_trunk, $group_time_str) {

        if ($report_type == 0) {
            $where = array();
            if (!empty($country)) {
                array_push($where, "ingress_country = '{$country}'");
            }
            if (!empty($destination)) {
                array_push($where, "ingress_code_name = '{$destination}'");
            }
            if (!empty($ingress_trunk)) {
                array_push($where, "ingress_id = {$ingress_trunk}");
            }
            
            if (!empty($egress_trunk)) {
                array_push($where, "egress_id = {$egress_trunk}");
            }
            array_push($where, "report_time between '{$start_time} {$timezone}' and '{$end_time} {$timezone}'");
            $where_str = implode(" and ", $where);
            $sql = "SELECT 
    $group_time_str as report_time,
    sum(not_zero_calls) as not_zero_calls,
    sum(pdd) as pdd
    from cdr_report_detail 
    where $where_str GROUP BY $group_time_str ORDER BY $group_time_str ASC";
        } else {
            $where = array();
            if (!empty($country)) {
                array_push($where, "egress_country = '{$country}'");
            }
            if (!empty($destination)) {
                array_push($where, "egress_code_name = '{$destination}'");
            }
            if (!empty($ingress_trunk)) {
                array_push($where, "ingress_id = {$ingress_trunk}");
            }
            
            if (!empty($egress_trunk)) {
                array_push($where, "egress_id = {$egress_trunk}");
            }
            array_push($where, "report_time between '{$start_time} {$timezone}' and '{$end_time} {$timezone}'");
            $where_str = implode(" and ", $where);
            $sql = "SELECT 
    $group_time_str as report_time,
    sum(not_zero_calls) as not_zero_calls,
    sum(pdd) as pdd
    from cdr_report_detail 
    where $where_str GROUP BY $group_time_str ORDER BY $group_time_str ASC";
        }


        $result = $this->query($sql);


        $draw_lines = array();

        foreach ($result as $item) {
            $line = round($item[0]['not_zero_calls'] == 0 ? 0 : $item[0]['pdd'] / $item[0]['not_zero_calls']);
            array_push($draw_lines, sprintf('<point x="%.11E" y="%s" />', strtotime($item[0]['report_time']) * 1000, $line));
        }

        $draw_line = implode("\r\n", $draw_lines);

        $content = <<<EOT
<chart type="cartesian" legendPlacement="bottom">
  <xaxis type="datetime" />
  <yaxis type="linear" label="PDD" />
  <data>
      <series type="line" label="PDD">
          {$draw_line}
      </series>
  </data>
</chart>
EOT;
        return $content;
    }

    public function get_total_cost_chart($report_type, $start_time, $end_time, $timezone, $country, $destination, $ingress_trunk, $egress_trunk, $group_time_str) {

        if ($report_type == 0) {
            $where = array();
            if (!empty($country)) {
                array_push($where, "ingress_country = '{$country}'");
            }
            if (!empty($destination)) {
                array_push($where, "ingress_code_name = '{$destination}'");
            }
            if (!empty($ingress_trunk)) {
                array_push($where, "ingress_id = {$ingress_trunk}");
            }
            
            if (!empty($egress_trunk)) {
                array_push($where, "egress_id = {$egress_trunk}");
            }
            array_push($where, "report_time between '{$start_time} {$timezone}' and '{$end_time} {$timezone}'");
            $where_str = implode(" and ", $where);
            $sql = "SELECT 
    $group_time_str as report_time,
    sum(ingress_call_cost) as call_cost,
    sum(lnp_cost) as lnp_cost
    from cdr_report_detail 
    where $where_str GROUP BY $group_time_str ORDER BY $group_time_str ASC";
        } else {
            $where = array();
            if (!empty($country)) {
                array_push($where, "egress_country = '{$country}'");
            }
            if (!empty($destination)) {
                array_push($where, "egress_code_name = '{$destination}'");
            }
            if (!empty($ingress_trunk)) {
                array_push($where, "ingress_id = {$ingress_trunk}");
            }
            
            if (!empty($egress_trunk)) {
                array_push($where, "egress_id = {$egress_trunk}");
            }
            array_push($where, "report_time between '{$start_time} {$timezone}' and '{$end_time} {$timezone}'");
            $where_str = implode(" and ", $where);
            $sql = "SELECT 
    $group_time_str as report_time,
    sum(egress_call_cost) as call_cost
    from cdr_report_detail 
    where $where_str GROUP BY $group_time_str ORDER BY $group_time_str ASC";
        }


        $result = $this->query($sql);


        $draw_lines = array();

        if ($report_type == 0) {
            foreach ($result as $item) {
                $line = round($item[0]['call_cost'] + $item[0]['lnp_cost'], 5);
                array_push($draw_lines, sprintf('<point x="%.11E" y="%s" />', strtotime($item[0]['report_time']) * 1000, $line));
            }
        } else {
            foreach ($result as $item) {
                $line = round($item[0]['call_cost'], 5);
                array_push($draw_lines, sprintf('<point x="%.11E" y="%s" />', strtotime($item[0]['report_time']) * 1000, $line));
            }
        }

        $draw_line = implode("\r\n", $draw_lines);

        $content = <<<EOT
<chart type="cartesian" legendPlacement="bottom">
  <xaxis type="datetime" />
  <yaxis type="linear" label="Total Cost" />
  <data>
      <series type="line" label="Total Cost">
          {$draw_line}
      </series>
  </data>
</chart>
EOT;
        return $content;
    }

    public function get_total_margin_chart($report_type, $start_time, $end_time, $timezone, $country, $destination, $ingress_trunk, $egress_trunk, $group_time_str) {

        if ($report_type == 0) {
            $where = array();
            if (!empty($country)) {
                array_push($where, "ingress_country = '{$country}'");
            }
            if (!empty($destination)) {
                array_push($where, "ingress_code_name = '{$destination}'");
            }
            if (!empty($ingress_trunk)) {
                array_push($where, "ingress_id = {$ingress_trunk}");
            }
            
            if (!empty($egress_trunk)) {
                array_push($where, "egress_id = {$egress_trunk}");
            }
            array_push($where, "report_time between '{$start_time} {$timezone}' and '{$end_time} {$timezone}'");
            $where_str = implode(" and ", $where);
            $sql = "SELECT 
    $group_time_str as report_time,
    sum(lnp_cost) as lnp_cost,
    sum(ingress_call_cost) as ingress_call_cost,
    sum(egress_call_cost) as egress_call_cost
    from cdr_report_detail 
    where $where_str GROUP BY $group_time_str ORDER BY $group_time_str ASC";
        } else {
            $where = array();
            if (!empty($country)) {
                array_push($where, "egress_country = '{$country}'");
            }
            if (!empty($destination)) {
                array_push($where, "egress_code_name = '{$destination}'");
            }
            if (!empty($ingress_trunk)) {
                array_push($where, "ingress_id = {$ingress_trunk}");
            }
            
            if (!empty($egress_trunk)) {
                array_push($where, "egress_id = {$egress_trunk}");
            }
            array_push($where, "report_time between '{$start_time} {$timezone}' and '{$end_time} {$timezone}'");
            $where_str = implode(" and ", $where);
            $sql = "SELECT 
    $group_time_str as report_time,
    sum(lnp_cost) as lnp_cost,
    sum(ingress_call_cost) as ingress_call_cost,
    sum(egress_call_cost) as egress_call_cost
    from cdr_report_detail 
    where $where_str GROUP BY $group_time_str ORDER BY $group_time_str ASC";
        }


        $result = $this->query($sql);


        $draw_lines = array();

        foreach ($result as $item) {
            $line = round($item[0]['ingress_call_cost'] + $item[0]['lnp_cost'] - $item[0]['egress_call_cost'], 5);
            array_push($draw_lines, sprintf('<point x="%.11E" y="%s" />', strtotime($item[0]['report_time']) * 1000, $line));
        }

        $draw_line = implode("\r\n", $draw_lines);

        $content = <<<EOT
<chart type="cartesian" legendPlacement="bottom">
  <xaxis type="datetime" />
  <yaxis type="linear" label="Margin" />
  <data>
      <series type="line" label="Margin">
          {$draw_line}
      </series>
  </data>
</chart>
EOT;
        return $content;
    }

    public function get_total_call_attemp($report_type, $start_time, $end_time, $timezone, $country, $destination, $ingress_trunk, $egress_trunk, $group_time_str) {

        if ($report_type == 0) {
            $where = array();
            if (!empty($country)) {
                array_push($where, "ingress_country = '{$country}'");
            }
            if (!empty($destination)) {
                array_push($where, "ingress_code_name = '{$destination}'");
            }
            if (!empty($ingress_trunk)) {
                array_push($where, "ingress_id = {$ingress_trunk}");
            }
            
            if (!empty($egress_trunk)) {
                array_push($where, "egress_id = {$egress_trunk}");
            }
            array_push($where, "report_time between '{$start_time} {$timezone}' and '{$end_time} {$timezone}'");
            $where_str = implode(" and ", $where);
            $sql = "SELECT 
    $group_time_str as report_time,
    sum(not_zero_calls) as not_zero_calls
    from cdr_report_detail 
    where $where_str GROUP BY $group_time_str ORDER BY $group_time_str ASC";
        } else {
            $where = array();
            if (!empty($country)) {
                array_push($where, "egress_country = '{$country}'");
            }
            if (!empty($destination)) {
                array_push($where, "egress_code_name = '{$destination}'");
            }
            if (!empty($ingress_trunk)) {
                array_push($where, "ingress_id = {$ingress_trunk}");
            }
            
            if (!empty($egress_trunk)) {
                array_push($where, "egress_id = {$egress_trunk}");
            }
            array_push($where, "report_time between '{$start_time} {$timezone}' and '{$end_time} {$timezone}'");
            $where_str = implode(" and ", $where);
            $sql = "SELECT 
    $group_time_str as report_time,
    sum(not_zero_calls) as not_zero_calls
    from cdr_report_detail 
    where $where_str GROUP BY $group_time_str ORDER BY $group_time_str ASC";
        }


        $result = $this->query($sql);


        $draw_lines = array();

        foreach ($result as $item) {
            $line = round($item[0]['not_zero_calls']);
            array_push($draw_lines, sprintf('<point x="%.11E" y="%s" />', strtotime($item[0]['report_time']) * 1000, $line));
        }

        $draw_line = implode("\r\n", $draw_lines);

        $content = <<<EOT
<chart type="cartesian" legendPlacement="bottom">
  <xaxis type="datetime" />
  <yaxis type="linear" label="Not Zero Call" />
  <data>
      <series type="line" label="Margin">
          {$draw_line}
      </series>
  </data>
</chart>
EOT;
        return $content;
    }

}