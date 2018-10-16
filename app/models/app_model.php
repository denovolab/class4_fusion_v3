<?php

/**
 * @author root
 *
 */
class AppModel extends Model {

    var $xvalidatevar = Array();
    var $xvalidate_data = Array();
    var $methods;
    public static $tip_info = array(); //定义静态属性
    public static $show_reseller = array(); //分层显示的代理商
    public static $count = 0;

    /**
     * 
     * report default query time 
     */
    public function get_real_period() {
        #custome  timezone
        $r = $this->query("SELECT report_count FROM system_parameter LIMIT 1");
        $report_count = $r[0][0]['report_count'];
        $tz = $this->get_sys_timezone();
        $current_time = time();
        if ($report_count == '0') {
            //$start = date("Y-m-d H:i:s", $current_time - (60 * 60)) . $tz;
            $start = date("Y-m-d H:i:sO", strtotime(date("Y-m-d H:i:s{$tz}", $current_time - (60 * 60))));
        } else {
            //$start = date("Y-m-d 00:00:00") . $tz;
            $start = date("Y-m-d H:i:sO", strtotime(date("Y-m-d 00:00:00{$tz}")));
        }
        //$end = date("Y-m-d 23:59:59") . $tz;
        
        
        $end = date("Y-m-d H:i:sO", strtotime(date("Y-m-d 23:59:59{$tz}")));
        $start_day = date("Y-m-d  ");
        $end_day = date("Y-m-d ");
        return compact('start', 'end', 'start_day', 'end_day');
    }

    public function get_ui_time() {
        $r = $this->query("SELECT report_count FROM system_parameter LIMIT 1");
        $report_count = $r[0][0]['report_count'];
        $tz = $this->get_sys_timezone();
        $current_time = time();
        if ($report_count == '0') {
            $start = date("H:i:s", $current_time - (60 * 60));
        } else {
            $start = "00:00:00";
        }
        $end = "23:59:59";
        $date = date("Y-m-d");
        return compact('start', 'end', 'date');
    }

    function get_sys_timezone() {
        $r = $this->query("select sys_timezone from system_parameter  offset 0  limit 1");
        if (isset($r[0][0]['sys_timezone']) && !empty($r[0][0]['sys_timezone'])) {
            return $r[0][0]['sys_timezone'];
        } else {
            return '+0000';
        }
    }

    function __construct($id = false, $table = null, $ds = null) {
        parent::__construct($id, $table, $ds);
        $childMethods = get_class_methods($this);
        $parentMethods = get_class_methods('AppModel');

        foreach ($childMethods as $key => $value) {
            $childMethods[$key] = strtolower($value);
        }

        foreach ($parentMethods as $key => $value) {
            $parentMethods[$key] = strtolower($value);
        }
        $this->methods = array_diff($childMethods, $parentMethods);
    }

    function parse_ext($options, $default_table = 'code') {
        $ext_conditions = '';
        $ext_select = '';

        if (isset($options['code'])) {
            if (empty($options['code'])) {
                return array();
            } else {
                $ext_conditions .= "and $default_table.code = '{$options['code']}'";
                $ext_select = "$default_table.code";
            }
        }
        if (isset($options['code_name'])) {
            if (empty($options['code_name'])) {
                return array();
            } else {
                $ext_conditions .= "and $default_table.name = '{$options['code_name']}'";
                $ext_select = "$default_table.name";
            }
        }
        if (isset($options['country'])) {
            if (empty($options['country'])) {
                return array();
            } else {
                $ext_conditions .= "and $default_table.country = '{$options['country']}'";
                $ext_select = "$default_table.country";
            }
        }
        return compact('ext_conditions', 'ext_select');
    }

    #切换到carrier自己的数据库

    function quote_sql_string($str) {
        $db = & ConnectionManager::getDataSource($this->useDbConfig);
        return $db->value($str);
    }

    function upload_validate($model, $data, $validations) {
        App::import('Core', 'Validation');
        $validation = & Validation::getInstance();
    }

    function x_query($sql) {
        $lists = parent::query($sql);
        if ($lists === false) {
            throw new Exception($sql . "查询失败！");
        }
        return $lists;
    }

    function x_save($data, $options = Array()) {
        pr($data);
        $result = $this->save($data, $options);
        if ($result === false) {
            throw new Exception("保存失败！");
        }
        return $result;
    }

    function xsave($data, $options = Array()) {
        if (!$this->xvalidated($data)) {
            return false;
        }
        return $this->save($data, false);
    }

    public function find_routepolicy() {
        $r = $this->query("select * from route_strategy");
        $size = count($r);
        $l = array();
        for ($i = 0; $i < $size; $i++) {
            $key = $r [$i] [0] ['route_strategy_id'];
            $l [$key] = $r [$i] [0] ['name'];
        }
        return $l;
    }

    public function find_all_resource_ip() {
        $r = $this->query("select ip from resource_ip");
        $size = count($r);
        $l = array();
        for ($i = 0; $i < $size; $i++) {
            $key = $r [$i] [0] ['ip'];
            $l [$key] = $r [$i] [0] ['ip'];
        }
        return $l;
    }

    public function find_client_route_prefix() {
        $client_id = $_SESSION['sst_client_id'];
        $r = $this->query("  SELECT digits  from  route  where  route_strategy_id  in ( SELECT   route_strategy_id  from  resource where client_id=$client_id)  and  digits is not  null  ");
        $size = count($r);
        $l = array();
        for ($i = 0; $i < $size; $i++) {
            $key = $r [$i] [0] ['digits'];
            $l [$key] = $r [$i] [0] ['digits'];
        }
        return $l;
    }

    public function find_rate_table_name() {
        $r = $this->query("select name  from rate_table");
        $size = count($r);
        $l = array();
        for ($i = 0; $i < $size; $i++) {
            $key = $r [$i] [0] ['name'];
            $l [$key] = $r [$i] [0] ['name'];
        }
        return $l;
    }

    public function find_all_rate_table() {
        $r = $this->query("select  rate_table_id, name  from rate_table  order by name asc");
        $size = count($r);
        $l = array();
        for ($i = 0; $i < $size; $i++) {
            $key = $r [$i] [0] ['rate_table_id'];
            $l [$key] = $r [$i] [0] ['name'];
        }
        return $l;
    }

    public function find_no_repeat_routepolicy($resource_id) {
        $r = $this->query("
		select   route_strategy_id,name from   route_strategy where   route_strategy_id  
not in(
		select  route.route_strategy_id,digits,name    from  resource 
                    left join route  on  route .route_strategy_id=resource.route_strategy_id
                    where  resource_id  
in (select  resource_id  from   resource_ip  where  ip  in (
                    select  ip     from   resource_ip  where resource_id=$resource_id
)) and  resource.route_strategy_id  is  not  null)");
        $size = count($r);
        $l = array();
        for ($i = 0; $i < $size; $i++) {
            $key = $r [$i] [0] ['route_strategy_id'];
            $l [$key] = $r [$i] [0] ['name'];
        }
        return $l;
    }

    public function find_code_deck() {
        $r = $this->query("select code_deck_id,name  from  code_deck  order by name asc");
        $size = count($r);
        $l = array();
        for ($i = 0; $i < $size; $i++) {
            if (empty($r [$i] [0] ['name'])) {
                continue;
            }
            $key = $r [$i] [0] ['code_deck_id'];
            $l [$key] = $r [$i] [0] ['name'];
        }
        return $l;
    }

    /**
     * 
     * 积分对话话费策略
     * 
     */
    public function find_giftscores() {
        $r = $this->query("select * from sales_strategy_points");
        $size = count($r);
        $l = array();
        for ($i = 0; $i < $size; $i++) {
            $key = $r [$i] [0] ['bonus_credit'] . "_" . $r[$i][0]['gift_amount'];
            $l [$key] = $r [$i] [0] ['bonus_credit'] . "(积分)==>" . $r[$i][0]['gift_amount'] . "(话费)";
        }
        return $l;
    }

    #查找系统货币和和其他货币的币率转换

    public function find_currency() {
        $r = $this->query("
select code, 
(
 select rate  from currency_updates where 
 	   currency_id = (
   				select  currency_id  from  currency  where code=(select  sys_currency  from system_parameter)
		) and modify_time=(
		select max(modify_time) from currency_updates where 
		currency_id = (select  currency_id  from  currency  where code=(select  sys_currency  from system_parameter)))

			)as  sys_rate,
		(select rate from currency_updates where currency_id = curr.currency_id and modify_time=(select max(modify_time) from currency_updates where currency_id = curr.currency_id) limit 1) as rate
	 from currency as curr
		   
		  ");
        $size = count($r);
        $l = array();
        for ($i = 0; $i < $size; $i++) {
            $key = $r [$i] [0] ['rate'] . "_" . $r[$i][0]['code'] . "_" . $r [$i] [0] ['sys_rate'];
            $l [$key] = $r[$i][0]['code'];
        }
        return $l;
    }

    public function find_country() {
        $r = $this->query("select country  from  code   ");
        $size = count($r);
        $l = array();
        for ($i = 0; $i < $size; $i++) {
            $key = $r [$i] [0] ['country'];
            $l [$key] = $r [$i] [0] ['country'];
        }
        return $l;
    }

    public function find_account_level() {
        $r = $this->query("select level_name  from  account_level   ");
        $size = count($r);
        $l = array();
        for ($i = 0; $i < $size; $i++) {
            $key = $r [$i] [0] ['level_name'];
            $l [$key] = $r [$i] [0] ['level_name'];
        }
        return $l;
    }

    public function find_server() {
        $r = $this->query("select sip_ip as ip  from  switch_profile");
        $size = count($r);
        $l = array();
        for ($i = 0; $i < $size; $i++) {
            $key = $r [$i] [0] ['ip'];
            $l [$key] = $r [$i] [0] ['ip'];
        }
        return $l;
    }

    /**
     * 
     * 
     * ******************************************************************上传封装(有表单)*********************************************************
     * 
     * 第一版   2010-8-25
     * 用法（需要传入上传的表名,表id,上传参数）
     * @param 下面是表单必须的格式
     * <input type="hidden"  value="code" name="upload_table" >
     * <input type="hidden"  value="<?php echo $code_deck_id?>" name="upload_table_id">
     * <input type="radio" name="upload_param" value="1"   checked="" />
      <span>覆盖</span>
      <input type="radio" name="upload_param" value="2" >
      <span>忽略重复</span>
      <input type="radio" name="upload_param" value="3" >
      <span>删除重复</span>
      <input type="radio" name="upload_param" value="4" >
      <span>出错返回错误信息</span>
     * 
     * 有参数的上传$objectives  --->描述
     * * ********************************************************************************************************************************** 
     * 
     */
    public function import_data($objectives) {
        $upload_table_id = $_POST ['upload_table_id'];
        $upload_table = $_POST ['upload_table'] . time();
        $upload_real_table = $_POST ['upload_real_table'];
        $upload_param = $_POST ['upload_param'];


        $upload_size = __('upload_size', true);
        $upload_path = __('local_upload_path', true);
        $status = $_FILES ["file"] ["error"]; //上传状态
        $form_filename = $_FILES ['file'] ['name'];
        $file_size = $_FILES ["file"] ["size"];
        //上传限制  20k的csv文件

        if ($_FILES ["file"] ["error"] > 0) {
            
        } else {
            $file_name = 'uploadcode_' . time() . '.csv';
            //	  	$file_name='code.csv';
            if (file_exists($upload_path . $file_name)) {
                $status = 6; //文件已经存在
            } else {
                move_uploaded_file($_FILES ["file"] ["tmp_name"], $upload_path . $file_name); //转移文件
                $status = 0;
            }
        }

        $date = date('Y-m-d H:i:s');
        $download_path = __('local_download_path', true);
        $download_file = $download_path . $file_name; // /tmp/exports/

        $login_type = $_SESSION['login_type'];

        if ($login_type == 1) {
            //admin
            $reseller_id = 0;
            $user_id = $_SESSION ['sst_user_id'];
            $sql2 = "insert  into   error_info
     (filename,uploadtime,objectives,filepath,filesize,realfilename,user_id,status,type,upload_table,upload_table_id,upload_param,upload_real_table)
values('$form_filename','$date','$objectives','$download_path',$file_size,'$file_name',$user_id,$status,1,'$upload_table',$upload_table_id,$upload_param,'$upload_real_table');  ";
        }
        if ($login_type == 3) {
            $client_id = $_SESSION ['sst_clinet_id'];
            $sql2 = "insert  into   error_info
  	   (filename,uploadtime,objectives,filepath,filesize,realfilename,status,client_id,type,upload_table,upload_table_id,upload_param,upload_real_table)
values('$form_filename','$date','$objectives','$download_path',$file_size,'$file_name',$status,$client_id,1,'$upload_table',$upload_table_id,$upload_param,'$upload_real_table');  ";
        }
        $this->query($sql2);
    }

    
    function download_by_sql($sql, $options = array())
    {
        if (empty($sql))
        {
            return false;
        }
        $database_export_path = Configure::read('database_export_path');
        if (empty($database_export_path))
        {
            $database_export_path = "/tmp/exports";
        }
        $user_id = 0;
        if (isset($_SESSION ['sst_user_id']))
        {
            $user_id = $_SESSION ['sst_user_id'];
        }

        $objectives = '';
        if (isset($options['objectives']) && !empty($options['objectives']))
        {
            $objectives = $options['objectives'];
        }
        if (isset($options['file_name']) && !empty($options['file_name']))
        {
            $file_name = $options['file_name'];
        }
        else
        {
            if (!empty($objectives))
            {
                $file_name = $objectives . '_' . time() . '.csv';
            }
            else
            {
                $file_name = 'download_' . time() . '.csv';
            }
        }

        $copy_file = $database_export_path . DS . $file_name;

        $db_file = Configure::read('database_actual_export_path') . DS . $file_name;
        #$copy_sql = "COPY ($sql)  TO   '$db_file'  DELIMITER ','  CSV HEADER "; //daochu
        $copy_sql = $sql;

    	if (!empty($_GET ['start_date']) && !empty($_GET ['start_time']) && !empty($_GET ['stop_date']) && !empty($_GET ['stop_time']) && !empty($_GET ['query']['tz'])) {
            $start = $_GET ['start_date'] . '  ' . $_GET ['start_time'] . ' ' . $_GET ['query']['tz']; //开始时间
            $end = $_GET ['stop_date'] . '  ' . $_GET ['stop_time'] . '  ' . $_GET ['query']['tz']; //结束时间
        } else {
            #report deault query time
            extract($this->get_real_period());
        }
	
	$user_id = isset($_SESSION['sst_client_id']) ? $_SESSION['sst_client_id'] : 'NULL';
        if(!isset($options['is_dipp']) || empty($options['is_dipp']) )
        {
            $options['is_dipp'] = false;
         //   $log_sql = "INSERT INTO cdr_export_log(status,user_id) values (0,$user_id) returning id";

	$log_sql = "INSERT INTO cdr_export_log(status,user_id,cdr_start_time,cdr_end_time) values (0,$user_id,'{$start}','{$end}') returning id";

        }
        else
        {
//            $log_sql = "INSERT INTO cdr_export_log(status,user_id,is_dipp) values (0,$user_id,true) returning id";
$log_sql = "INSERT INTO cdr_export_log(status,user_id,is_dipp,cdr_start_time,cdr_end_time) values (0,$user_id,true,'{$start}','{$end}') returning id";
        }

        $log_result = $this->query($log_sql);
        $log_id = $log_result[0][0]['id'];

        $db = new DATABASE_CONFIG();

        $redis_data = array(
            'sql' => $copy_sql,
            'db_info' => $db->default,
            'db_export_path' => Configure::read('database_actual_export_path'),
            'db_web_path' => Configure::read('database_export_path_cdr'),
            'log_id' => $log_id,
        );
        
        $json_data = json_encode($redis_data);
        $loaded_extensions = get_loaded_extensions();
        if (!in_array('redis', $loaded_extensions))
        {
            return false;
        }
        $cdr_down_key = Configure::read('redis.cdr_down_key');
        
        if (empty($cdr_down_key)) {
            $cdr_down_key = 'cdr_down';
        }
        
        $redis = new Redis();
        $redis->connect(Configure::read('redis.host'), Configure::read('redis.port'));
        $redis->rpush($cdr_down_key, $json_data);
        $redis->close();

        //$this->query($copy_sql); //导出数据	
        //App::import('Model', 'Importlog');
        //$new_copy_file = $database_export_path . DS . uniqid() .$file_name;
        // $cmd = "sed -e \"s/,/,'/g\" -e \"s/^/\'/g\" {$copy_file} > $new_copy_file";
        // shell_exec($cmd);
        //$copy_file = $new_copy_file;
        /*
          if (!file_exists($copy_file)) {
          $status = Importlog::ERROR_STATUS_DOWNLOAD_FAIL;
          $file_size = 0;
          } else {
          $status = Importlog::ERROR_STATUS_DOWNLOAD_SUCCESS;
          $file_size = filesize($copy_file);
          }

          $user = new Importlog ();
          $data = array();
          $data ['Importlog'] = array();
          $data ['Importlog']['downloadtime'] = gmtnow();
          $data ['Importlog']['objectives'] = $objectives;
          $data ['Importlog']['filepath'] = $copy_file;

          $data ['Importlog']['realfilename'] = $file_name;
          $data ['Importlog']['user_id'] = $user_id;
          $data ['Importlog']['status'] = $status;

          $data ['Importlog']['download_sql'] = $sql;
          $data ['Importlog']['type'] = Importlog::ERROR_TYPE_DOWNLOAD;
          $data ['Importlog']['filesize'] = $file_size;
          $user->save($data ['Importlog']);
          if ($status == Importlog::ERROR_STATUS_DOWNLOAD_SUCCESS) {
          $this->download_csv($copy_file, $file_name); //下载
          return true;
          } else {
          throw new Exception('Server Configure Error,Please Contact Administrator');
          return false;
          }
         * 
         */
        return $log_id;
    }
    
    function download_by_sql2($sql, $options = array()) {
        if (empty($sql)) {
            return false;
        }
        $database_export_path = Configure::read('database_export_path');
        if (empty($database_export_path)) {
            $database_export_path = "/tmp/exports";
        }
        $user_id = 0;
        if (isset($_SESSION ['sst_user_id'])) {
            $user_id = $_SESSION ['sst_user_id'];
        }

        $objectives = '';
        if (isset($options['objectives']) && !empty($options['objectives'])) {
            $objectives = $options['objectives'];
        }
        if (isset($options['file_name']) && !empty($options['file_name'])) {
            $file_name = $options['file_name'];
        } else {
            if (!empty($objectives)) {
                $file_name = $objectives . '_' . time() . '.csv';
            } else {
                $file_name = 'download_' . time() . '.csv';
            }
        }
        
        //$copy_file = $database_export_path . DS . $file_name;

        //$db_file = Configure::read('database_actual_export_path') .DS . $file_name;
        #$copy_sql = "COPY ($sql)  TO   '$db_file'  DELIMITER ','  CSV HEADER "; //daochu
        //$copy_sql = $sql;

        $user_id = isset($_SESSION['sst_client_id']) ? $_SESSION['sst_client_id'] : 'NULL';
        
        $sql = "select name from voip_gateway where name  is not null order by id";
        $result = $this->query($sql);
        
        $log_ids = array();
        
        foreach ($result as $item) {
            $switch_name = $item[0]['name'];
            $fields = $options['fields'];
            $where = $options['where'];
            $start_time = $options['start'];
            $end_time = $options['end'];
            $log_sql = "INSERT INTO cdr_export_log(status,user_id, fields, conditions, cdr_start_time, cdr_end_time, switch_name) values (0,$user_id, $$$fields$$, $$$where$$, '$start_time', '$end_time', '$switch_name') returning id";
            $log_result = $this->query($log_sql);
            $log_id  = $log_result[0][0]['id'];
            array_push($log_ids, $log_id);
        }
        
        $logs_str = implode(',', $log_ids);
        /*
        $db = new DATABASE_CONFIG();
        
        $redis_data = array(
            'sql' => $copy_sql,
            'db_info' => $db->default,
            'db_export_path' => Configure::read('database_actual_export_path'),
            'db_web_path' => Configure::read('database_export_path'),
            'log_id' => $log_id,
        );
        
        $json_data = json_encode($redis_data);
        
        $redis = new Redis();
        $redis->connect(Configure::read('redis.host'), Configure::read('redis.port'));
        $redis->rpush('cdr_down', $json_data);
        $redis->close();
        */
        
        //$this->query($copy_sql); //导出数据	
        //App::import('Model', 'Importlog');

        //$new_copy_file = $database_export_path . DS . uniqid() .$file_name;
       // $cmd = "sed -e \"s/,/,'/g\" -e \"s/^/\'/g\" {$copy_file} > $new_copy_file";
       // shell_exec($cmd);
        
        //$copy_file = $new_copy_file;
        /*
        if (!file_exists($copy_file)) {
            $status = Importlog::ERROR_STATUS_DOWNLOAD_FAIL;
            $file_size = 0;
        } else {
            $status = Importlog::ERROR_STATUS_DOWNLOAD_SUCCESS;
            $file_size = filesize($copy_file);
        }

        $user = new Importlog ();
        $data = array();
        $data ['Importlog'] = array();
        $data ['Importlog']['downloadtime'] = gmtnow();
        $data ['Importlog']['objectives'] = $objectives;
        $data ['Importlog']['filepath'] = $copy_file;

        $data ['Importlog']['realfilename'] = $file_name;
        $data ['Importlog']['user_id'] = $user_id;
        $data ['Importlog']['status'] = $status;

        $data ['Importlog']['download_sql'] = $sql;
        $data ['Importlog']['type'] = Importlog::ERROR_TYPE_DOWNLOAD;
        $data ['Importlog']['filesize'] = $file_size;
        $user->save($data ['Importlog']);
        if ($status == Importlog::ERROR_STATUS_DOWNLOAD_SUCCESS) {
            $this->download_csv($copy_file, $file_name); //下载
            return true;
        } else {
            throw new Exception('Server Configure Error,Please Contact Administrator');
            return false;
        }
         * 
         */
        return $logs_str;
    }

    /*
     * 下载为xls格式
     */

    function download_xls_by_sql($sql, $options = array()) {
        if (empty($sql)) {
            return false;
        }
        $xls_file_path = "/tmp/xls_down";
        if (!is_dir($xls_file_path)) {
            mkdir($xls_file_path);
        }
        $user_id = 0;
        if (isset($_SESSION ['sst_user_id'])) {
            $user_id = $_SESSION ['sst_user_id'];
        }

        $objectives = '';
        if (isset($options['objectives']) && !empty($options['objectives'])) {
            $objectives = $options['objectives'];
        }
        if (isset($options['file_name']) && !empty($options['file_name'])) {
            $file_name = $options['file_name'];
        } else {
            if (!empty($objectives)) {
                $file_name = $objectives . '_' . time() . '.xls';
            } else {
                $file_name = 'download_' . time() . '.xls';
            }
        }
        $copy_file = $xls_file_path . DS . $file_name;
        $handle = fopen($copy_file, "w");
        //fwrite($handle, "size\tsize1\t\n");
        //fwrite($handle, $sql."\t\n");
        $result = $this->query($sql);
        $size = count($result);
        if ($size > 0) {
            $w_words = implode("\t", array_keys($result[0][0]));
            fwrite($handle, $w_words);
            fwrite($handle, "\n");
        }
        for ($i = 0; $i < $size; $i++) {
            /*
            foreach($result[$i][0] as &$item)
            {
                $item = '"' . $item;
            }
             * 
             */
            //fwrite($handle, $i.chr(9));
            $w_words = implode("\t", $result[$i][0]);
            fwrite($handle, $w_words);
            fwrite($handle, "\n");
        }
        fclose($handle);

        App::import('Model', 'Importlog');

        if (!file_exists($copy_file)) {
            $status = Importlog::ERROR_STATUS_DOWNLOAD_FAIL;
            $file_size = 0;
        } else {
            $status = Importlog::ERROR_STATUS_DOWNLOAD_SUCCESS;
            $file_size = filesize($copy_file);
        }

        $user = new Importlog ();
        $data = array();
        $data ['Importlog'] = array();
        $data ['Importlog']['downloadtime'] = gmtnow();
        $data ['Importlog']['objectives'] = $objectives;
        $data ['Importlog']['filepath'] = $copy_file;

        $data ['Importlog']['realfilename'] = $file_name;
        $data ['Importlog']['user_id'] = $user_id;
        $data ['Importlog']['status'] = $status;

        $data ['Importlog']['download_sql'] = $sql;
        $data ['Importlog']['type'] = Importlog::ERROR_TYPE_DOWNLOAD;
        $data ['Importlog']['filesize'] = $file_size;
        $user->save($data ['Importlog']);
        if ($status == Importlog::ERROR_STATUS_DOWNLOAD_SUCCESS) {
            $this->download_xls($copy_file, $file_name); //下载
            return true;
        } else {
            throw new Exception('Server Configure Error,Please Contact Administrator');
            return false;
        }
    }

    /**
     * 
     * 
     * * ***************************************************下载封装*(有表单)********************************************************************************************
     * 第一版   2010-8-25
     * 表单需要2个设置
     * 设置字段分隔符:
     * 设置是否要带字段头:
     * 
     * 表单格式（分隔符，和头是要按照下面的格式）
     * <select id="delimiter" name="delimiter" >
      <option value="1">,</option>
      <option value="2">|</option>
      <option value="3">;</option>
      </select>
      <select id="header" name="header">
      <option value="1">是</option>
      <option value="2">否</option>
      </select>
     * 
     * 
     * 
     * $download_sql为导出数据要用的sql
     * * ********************************************************************************************************************************************
     */
    public function export__form_data($objectives, $download_sql) {
        if (empty($objectives)) {
            $objectives = '';
        }
        if (empty($download_sql)) {
            return true;
        }
        $user_id = $_SESSION ['sst_user_id'];

        $header = $_POST ['header'];
        $delimiter = $_POST ['delimiter'];
        if ($header == 1) {
            $HEADER = 'HEADER';
        } else {
            $HEADER = '';
        }
        if ($delimiter == 1) {
            $DELIMITER = ',';
        }
        if ($delimiter == 2) {
            $DELIMITER = '|';
        }
        if ($delimiter == 3) {
            $DELIMITER = ';';
        }
        //数据库导出数据的路径
        $database_export_path = __('database_export_path', true);
        $file_name = 'downcode_' . time() . '.csv';
        $copy_file = $database_export_path . $file_name;
        $copy_sql = "COPY ($download_sql)  TO   '$copy_file'  DELIMITER '$DELIMITER'  CSV $HEADER "; //daochu
        $this->query($copy_sql); //导出数据
        //下载记录
        $date = date('Y-m-d H:i:s');
        $download_path = __('local_download_path', true);
        $download_file = $download_path . $file_name; // /tmp/exports/


        if (!file_exists($download_file)) {
            pr('对不起,你要下载的文件不存在。');
            $status = 11; //下载失败
        } else {

            $file_size = filesize($download_file);
            $status = 12; //下载完成
        }
        $sql2 = "insert  into   error_info
  	   (downloadtime,objectives,filepath,filesize,realfilename,user_id,status,download_sql,type)
values('$date','$objectives','$download_path',$file_size,'$file_name',$user_id,$status,'$download_sql',2);  ";
        $this->query($sql2);
        $this->download_csv($download_file, $file_name); //下载
    }

    /**
     * 
     * *  * *************************************************** 适用于报表数据的导出(适合于sql)***************************************************************************************
     *
     * 
     * 第一版   2010-8-25
     * 通过sql导出数据
     * @param $objectives "导出注释"
     * @param unknown_type $download_sql="select name   from  code_deck where   code_deck_id=1"
     * 
     * * *************************************************** **************************************************************************************
     */
    public function export__sql_data($objectives, $download_sql, $dowmload_file_name) {
        if (empty($objectives)) {
            $objectives = '';
        }
        if (empty($download_sql)) {
            return true;
        }
        if (isset($_SESSION ['sst_user_id'])) {
            $user_id = $_SESSION ['sst_user_id'];
        }



        //数据库导出数据的路径
        $database_export_path = __('database_export_path', true);
        $file_name = 'downcode_' . time() . '.csv';
        $copy_file = $database_export_path . $file_name;
        $copy_sql = "COPY ($download_sql)  TO   '$copy_file'  CSV HEADER "; //daochu
        $this->query($copy_sql); //导出数据
        //下载记录
        $date = date('Y-m-d H:i:s');
        $download_path = __('local_download_path', true);
        $download_file = $download_path . $file_name; // /tmp/exports/

        if (!file_exists($download_file)) {
            echo ('对不起,你要下载的文件不存在。');
            $status = 11; //下载失败
        } else {

            $file_size = filesize($download_file);
            $status = 12; //下载完成
        }
        /* 		$sql2 = "insert  into   error_info
          (downloadtime,objectives,filepath,filesize,realfilename,user_id,status,reseller_id,download_sql,type)
          values('$date','$objectives','$download_path',$file_size,'$file_name',$user_id,$status,$reseller_id,\"$download_sql\",2);  ";
          $this->query ( $sql2 ); */

        App::import('Model', 'Importlog');
        $user = new Importlog ();
        $data['Importlog'] = array();
        $data ['Importlog']['downloadtime'] = $date;
        $data ['Importlog']['objectives'] = $objectives;
        $data ['Importlog']['filepath'] = $download_path;
        $data ['Importlog']['filesize'] = $file_size;
        $data ['Importlog']['realfilename'] = $file_name;
        $data ['Importlog']['user_id'] = $user_id;
        $data ['Importlog']['status'] = $status;
        $data ['Importlog']['download_sql'] = $download_sql;
        $data ['Importlog']['type'] = 2;
        $user->save($data ['Importlog']);
        $this->download_csv($download_file, "$dowmload_file_name.csv"); //下载
    }

    /**
     * 
     * @param $objectives
     * @param $download_sql
     * @param $dowmload_file_name
     */
    public function export__sql_compress($objectives, $download_sql, $dowmload_file_name, $file_type = 'zip') {
        if (empty($objectives)) {
            $objectives = '';
        }
        if (empty($download_sql)) {
            return true;
        }
        if (isset($_SESSION ['sst_user_id'])) {
            $user_id = $_SESSION ['sst_user_id'];
        }



        //数据库导出数据的路径
        $database_export_path = __('database_export_path', true);
        $file_name = 'downcode_' . time() . '.csv';
        $copy_file = $database_export_path . $file_name;
        $copy_sql = "COPY ($download_sql)  TO   '$copy_file'  CSV HEADER "; //daochu
        $this->query($copy_sql); //导出数据
        //下载记录
        $date = date('Y-m-d H:i:s');
        $download_path = __('local_download_path', true);
        $download_file = $download_path . $file_name; // /tmp/exports/
        if ($file_type == 'zip') {
            $file_name_tmp = 'downcode_' . time() . '.zip';
            `zip -q {$download_path}{$file_name_tmp} {$download_file}`;
            $download_file = $download_path . $file_name_tmp;
        } elseif ('tar.gz' == $file_type) {
            $file_name_tmp = 'downcode_' . time() . '.tar.gz';
            `tar -cf {$download_path}{$file_name_tmp} {$download_file}`;
            $download_file = $download_path . $file_name_tmp;
        } else {
            //void
        }
        

        if (!file_exists($download_file)) {
            echo ('对不起,你要下载的文件不存在。');
            $status = 11; //下载失败
        } else {

            $file_size = filesize($download_file);
            $status = 12; //下载完成
        }
        /* 		$sql2 = "insert  into   error_info
          (downloadtime,objectives,filepath,filesize,realfilename,user_id,status,reseller_id,download_sql,type)
          values('$date','$objectives','$download_path',$file_size,'$file_name',$user_id,$status,$reseller_id,\"$download_sql\",2);  ";
          $this->query ( $sql2 ); */

        App::import('Model', 'Importlog');
        $user = new Importlog ();
        $data['Importlog'] = array();
        $data ['Importlog']['downloadtime'] = $date;
        $data ['Importlog']['objectives'] = $objectives;
        $data ['Importlog']['filepath'] = $download_path;
        $data ['Importlog']['filesize'] = $file_size;
        $data ['Importlog']['realfilename'] = $file_name;
        $data ['Importlog']['user_id'] = $user_id;
        $data ['Importlog']['status'] = $status;
        $data ['Importlog']['download_sql'] = $download_sql;
        $data ['Importlog']['type'] = 2;
        $user->save($data ['Importlog']);
        $this->download_csv($download_file, "$dowmload_file_name"); //下载
    }

    /**
     * 
     * *  * *************************************************** 适用于报表数据的导出(适合于sql)***************************************************************************************
     *
     * 
     * 第一版   2010-8-25
     * 通过sql导出数据
     * @param $objectives "导出注释"
     * @param unknown_type $download_sql="select name   from  code_deck where   code_deck_id=1"
     * 
     * * *************************************************** **************************************************************************************
     */
    public function export_xls_sql_data($objectives, $download_sql, $download_file_name) {
        if (empty($objectives)) {
            $objectives = '';
        }
        if (empty($download_sql)) {
            return true;
        }
        if (isset($_SESSION ['sst_user_id'])) {
            $user_id = $_SESSION ['sst_user_id'];
        }
        $xls_file_path = "/tmp/xls_down";
        if (!is_dir($xls_file_path)) {
            mkdir($xls_file_path);
        }

        $file_name = $download_file_name . '_' . time() . '.xls';
        $copy_file = $xls_file_path . DS . $file_name;
        $handle = fopen($copy_file, "w");
        //fwrite($handle, "size\tsize1\t\n");
        //fwrite($handle, $download_sql."\t\n");
        $result = $this->query($download_sql);
        $size = count($result);
        if ($size > 0) {
            $w_words = implode(chr(9), array_keys($result[0][0]));
            fwrite($handle, $w_words);
            fwrite($handle, chr(13));
        }
        for ($i = 0; $i < $size; $i++) {
            //fwrite($handle, $i.chr(9));
            $w_words = implode(chr(9), $result[$i][0]);
            fwrite($handle, $w_words);
            fwrite($handle, chr(13));
        }
        fclose($handle);


        //下载记录
        $date = date('Y-m-d H:i:s');

        if (!file_exists($copy_file)) {
            echo ('对不起,你要下载的文件不存在。');
            $status = 11; //下载失败
        } else {

            $file_size = filesize($copy_file);
            $status = 12; //下载完成
        }
        /* 		$sql2 = "insert  into   error_info
          (downloadtime,objectives,filepath,filesize,realfilename,user_id,status,reseller_id,download_sql,type)
          values('$date','$objectives','$download_path',$file_size,'$file_name',$user_id,$status,$reseller_id,\"$download_sql\",2);  ";
          $this->query ( $sql2 ); */

        App::import('Model', 'Importlog');
        $user = new Importlog ();
        $data['Importlog'] = array();
        $data ['Importlog']['downloadtime'] = $date;
        $data ['Importlog']['objectives'] = $objectives;
        $data ['Importlog']['filepath'] = $xls_file_path;
        $data ['Importlog']['filesize'] = $file_size;
        $data ['Importlog']['realfilename'] = $file_name;
        $data ['Importlog']['user_id'] = $user_id;
        $data ['Importlog']['status'] = $status;

        $data ['Importlog']['download_sql'] = $download_sql;
        $data ['Importlog']['type'] = 2;
        $user->save($data ['Importlog']);
        $this->download_xls($copy_file, $download_file_name . ".xls"); //下载
        return true;
    }

    //生成下载记录	
    public function careate_download_log($objectives, $download_path, $realfilename) {
        $download_file = $download_path . $realfilename; // /tmp/exports/
        if (!file_exists($download_file)) {
            $file_size = 0;
            $status = 14; //上传成功 但文件已经被删除
        } else {
            $file_size = filesize($download_file);
            $status = 12; //下载完成
        }
        $user_id = $_SESSION ['sst_user_id'];
        $date = date('Y-m-d H:i:s');

        $sql2 = "insert  into   error_info
  	   (downloadtime,objectives,filepath,filesize,realfilename,user_id,status,type)
values('$date','$objectives','$download_path',$file_size,'$realfilename',$user_id,$status,2);  ";
        $this->query($sql2);

        if ($status == 12) {
            $this->download_csv($download_file, $realfilename); //下载  
        } else {
            return $status;
        }
    }

    /**
     *
     * *  *  * ***************************************************  下载数据库导出的csv文件***************************************************************************************
     *
     * @param unknown_type $download_file    格式: /tmp/exports/code_55645.csv
     * @param unknown_type $file_name   格式: code_55645.csv
     * *  *  *  * *************************************************** ***************************************************************************************
     */
    public function download_csv($download_file, $file_name) {
        /*
        ini_set('memory_limit', '4046M');
        $file_size = filesize($download_file);
        header("Content-type: application/octet-stream;charset=utf8");
        header("Accept-Ranges: bytes");
        header("Accept-Length: $file_size");
        header("Content-Disposition: attachment; filename=" . $file_name);
        readfile($download_file);
        return true;
         * 
         */
        $file = $download_file;
 
        $filename = $file_name;

        header("Content-type: application/octet-stream");

        //处理中文文件名
        $ua = $_SERVER["HTTP_USER_AGENT"];
        $encoded_filename = rawurlencode($filename);
        if (preg_match("/MSIE/", $ua)) {
            header('Content-Disposition: attachment; filename="' . $encoded_filename . '"');
        } else if (preg_match("/Firefox/", $ua)) {
            header("Content-Disposition: attachment; filename*=\"utf8''" . $filename . '"');
        } else {
            header('Content-Disposition: attachment; filename="' . $filename . '"');
        }

        //让Xsendfile发送文件
        header("X-Sendfile: $file");
    }


    /**
     *
     * *  *  * ***************************************************  下载数据库导出的csv文件***************************************************************************************
     *
     * @param unknown_type $download_file    格式: /tmp/exports/code_55645.xls
     * @param unknown_type $file_name   格式: code_55645.xls
     * *  *  *  * *************************************************** ***************************************************************************************
     */
    public function download_xls($download_file, $file_name) {
        $file_name = str_replace(".csv", ".xls", $file_name);
        $file_size = filesize($download_file);
        header("Content-type:application/vnd.ms-excel;charset=utf8");
        header("Accept-Ranges: bytes");
        header("Accept-Length: $file_size");
        header("Content-Disposition:attachment; filename=" . $file_name);

        $fp = fopen($download_file, "r");
        $buffer_size = 1024;
        $cur_pos = 0;
        while (!feof($fp) && $file_size - $cur_pos > $buffer_size) {
            $buffer = fread($fp, $buffer_size);
            echo $buffer;
            $cur_pos += $buffer_size;
        }

        $buffer = fread($fp, $file_size - $cur_pos);
        echo $buffer;
        fclose($fp);
        return true;
    }

    /**
     * 查询客户
     */
    function findClient() {
        $sql1 = "select client_id ,name from client  order by name ";
        $r = $this->query($sql1);
        $size = count($r);
        $l = array();
        for ($i = 0; $i < $size; $i++) {
            $key = $r [$i] [0] ['client_id'];
            $l [$key] = $r [$i] [0] ['name'];
        }

        return $l;
    }
    
    
    /*
     * Logging
     */
    
    public function logging($type, $module, $detail) {
        $name = $_SESSION['sst_user_name'];
        $sql = "INSERT INTO modif_log (time, module, type, name, detail) VALUES (CURRENT_TIMESTAMP(0), '{$module}', {$type},'{$name}', '{$detail}')";
        $this->query($sql);
    }
    

    /**
     * 查询ingress客户
     */
    function findIngressClient() {
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
        $r = $this->query($sql);
        $size = count($r);
        $l = array();
        for ($i = 0; $i < $size; $i++) {
            $key = $r [$i] [0] ['client_id'];
            $l [$key] = $r [$i] [0] ['name'];
        }

        return $l;
    }

    /**
     * 查询egress客户
     */
    function findEgressClient() {
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
        $r = $this->query($sql);
        $size = count($r);
        $l = array();
        for ($i = 0; $i < $size; $i++) {
            $key = $r [$i] [0] ['client_id'];
            $l [$key] = $r [$i] [0] ['name'];
        }

        return $l;
    }

    /**
     * 查询客户
     */
    function findAllUser() {
        $login_type = $_SESSION ['login_type'];
        //damin
        if ($login_type == 1) {
            $sql1 = "select user_id ,name from users ";
        }
        //client
        if ($login_type == 3) {
            $client_id = $_SESSION ['sst_client_id'];
            $sql1 = " select user_id ,name from users  where   client_id= $client_id";
        }

        if ($login_type == 4) {
            $card_id = $_SESSION ['card_id'];
            $sql1 = " ";
        }

        $r = $this->query($sql1);
        $size = count($r);
        $l = array();
        for ($i = 0; $i < $size; $i++) {
            $key = $r [$i] [0] ['user_id'];
            $l [$key] = $r [$i] [0] ['name'];
        }

        return $l;
    }

    //切割egress	
    public function explode_egress($id) {
        $old = explode(",", $id); //被选中的


        $size = count($old);

        $user = array();

        //重组被选的egress
        for ($i = 0; $i < $size; $i++) {
            $key1 = $old [$i];
            $value1 = $old [$i];
            $user [$key1] = $value1;
        }

        $all = $this->findAll_egress();

        $array = array();
        $nouse = array(); //原来没有选择的
        $new = array();

        //循环所有的egress  如果 
        $i = 0;
        foreach ($all as $key => $value) {

            //判断被选中egress是否在all中存在
            if (!isset($user [$key])) {
                $nouse [$key] = $value; //没有被选中的
            } else {
                $user [$key] = $value; //被选中的
            }
            $i++;
        }

        array_push($array, $nouse, $user);
        return $array;
    }

    /**
     * 
     * 
     * 查询城市
     */
    public function find_city() {

        $login_type = $_SESSION ['login_type'];
        //admin
        if ($login_type == 1) {
            $sql1 = "select location_id,city from location_code ";
        }
        //reseller
        if ($login_type == 2) {
            $reseller_id = $_SESSION ['sst_reseller_id'];
            $sql1 = "select location_id,city from location_code ";
        }
        //client
        if ($login_type == 3) {
            $client_id = $_SESSION ['sst_client_id'];
            $sql1 = "select location_id,city from location_code ";
        }

        //user
        if ($login_type == 5 || $login_type == 6) {
            return null;
        }
        $r = $this->query($sql1);
        $size = count($r);
        $l = array();
        for ($i = 0; $i < $size; $i++) {
            $key = $r [$i] [0] ['location_id'];
            $l [$key] = $r [$i] [0] ['city'];
        }
        return $l;
    }

    //月份
    function find_month() {
        $l = array();
        for ($i = 1; $i < 13; $i++) {
            $key = $i;
            $l [$key] = $i;
        }
        return $l;
    }

    /**
     * 
     * 
     * 
     * 查询对接网关的alias
     */
    public function findAll_ingress_alias() {

        $login_type = $_SESSION ['login_type'];
        //admin
        if ($login_type == 1) {
            $sql1 = "select alias from resource  where ingress=true  ";
        }
        //client
        if ($login_type == 3) {
            $client_id = $_SESSION ['sst_client_id'];
            $sql1 = "select alias from resource  where ingress=true  and   client_id=$client_id  ";
        }


        $r = $this->query($sql1);
        $size = count($r);
        $l = array();
        for ($i = 0; $i < $size; $i++) {
            $key = $r[$i][0]['alias'];
            $l[$key] = $r[$i][0]['alias'];
        }
        return $l;
    }

    public function findAll_ingress_id() {
        $login_type = $_SESSION ['login_type'];
        //admin
        if ($login_type == 1) {
            $sql1 = "select resource_id,alias from resource  where ingress=true  order by alias  asc  ";
        }
        //client
        if ($login_type == 3) {
            $client_id = $_SESSION ['sst_client_id'];
            $sql1 = "select resource_id,alias from resource  where ingress=true  and   client_id=$client_id   order by alias  asc";
        }


        $r = $this->query($sql1);
        $size = count($r);
        $l = array("0" => NULL);
        for ($i = 0; $i < $size; $i++) {
            $key = $r[$i][0]['resource_id'];
            $l[$key] = $r[$i][0]['alias'];
        }
        return $l;
    }

    public function findAll_egress_id() {
        $login_type = $_SESSION ['login_type'];
        //admin
        if ($login_type == 1) {
            $sql1 = "select resource_id,alias from resource where egress=true order by   alias   ";
        }
        //client
        if ($login_type == 3) {
            $client_id = $_SESSION ['sst_client_id'];
            $sql1 = "select resource_id,alias from resource  where egress=true  and   client_id=$client_id  order by   alias    ";
        }
        $r = $this->query($sql1);
        $size = count($r);
        $l = array();
        for ($i = 0; $i < $size; $i++) {
            $key = $r[$i][0]['resource_id'];
            $l[$key] = $r[$i][0]['alias'];
        }
        return $l;
    }

    /**
     * 
     * 
     * 
     * 查询代理商
     */
//	public function find_reseller(){		
//		//user
//		if ($login_type == 5 || $login_type == 6) {
//			return null;
//		}
//		$r = $this->query ( $sql1 );
//		$size = count ( $r );
//		$l = array ();
//		for($i = 0; $i < $size; $i ++) {
//			$key = $r [$i] [0] ['alias'];
//			$l [$key] = $key;
//		}
//		return $l;
//	}

    /**
     * 
     * 
     * 
     * 查询对接网关
     */
    public function findAll_ingress() {

        $login_type = $_SESSION ['login_type'];
        //admin
        if ($login_type == 1) {
            $sql1 = "select resource_id,alias from resource  where ingress=true  ";
        }
        //client
        if ($login_type == 3) {
            $client_id = $_SESSION ['sst_client_id'];
            $sql1 = "select resource_id,alias from resource  where ingress=true  and   client_id=$client_id  ";
        }


        $r = $this->query($sql1);
        $size = count($r);
        $l = array();
        for ($i = 0; $i < $size; $i++) {
            $key = $r [$i] [0] ['resource_id'];
            $l [$key] = $r [$i] [0] ['alias'];
        }
        return $l;
    }

    /**
     * 
     * 
     * 
     * 查询落地网关
     */
    public function findAll_egress_alias() {
        $client_where = ($_SESSION ['login_type'] == 3) ? "and  client_id={$_SESSION ['sst_client_id']}" : '';
        $sql = "select  resource_id,alias  from  resource where egress=true $client_where";
        $r = $this->query($sql);
        $size = count($r);
        $l = array();
        for ($i = 0; $i < $size; $i++) {
            $key = $r [$i] [0] ['alias'];
            $l [$key] = $key;
        }
        return $l;
    }

    /**
     * 
     * 
     * 
     * 查询落地网关
     */
    public function findAll_egress() {

        $login_type = $_SESSION ['login_type'];
        //admin
        if ($login_type == 1) {
            $sql1 = "select resource_id,alias from resource  where egress=true  ";
        }
        //reseller
        if ($login_type == 2) {

            $reseller_id = $_SESSION ['sst_reseller_id'];
            $sql1 = "select resource_id,alias from resource  where egress=true   and  reseller_id=$reseller_id ";
        }
        //client
        if ($login_type == 3) {
            $client_id = $_SESSION ['sst_client_id'];
            $sql1 = "select resource_id,alias from resource  where egress=true  and   client_id=$client_id  ";
        }
        $r = $this->query($sql1);
        $size = count($r);
        $l = array();
        for ($i = 0; $i < $size; $i++) {
            $key = $r [$i] [0] ['resource_id'];
            $l [$key] = $r [$i] [0] ['alias'];
        }
        return $l;
    }

    /*
     * 查找时间断
     */

    public function find_timeprofile() {
        $sql1 = "select time_profile_id,name from time_profile  ";
        $r = $this->query($sql1);
        $size = count($r);
        $l = array();
        for ($i = 0; $i < $size; $i++) {
            $key = $r[$i][0]['time_profile_id'];
            $l[$key] = $r[$i][0]['name'];
        }
        return $l;
    }

    /*
     * 查找时间断
     */

    public function find_timeprofile1() {
        $sql1 = "select time_profile_id, name from time_profile order by name asc";
        $r = $this->query($sql1);
        $size = count($r);
        $l = array();
        for ($i = 0; $i < $size; $i++) {
            $key = $r[$i][0]['time_profile_id'];
            $l[$key] = $r[$i][0]['name'];
        }
        return $l;
    }

    /*
     * 查找sip号码
     */

    public function find_sipcode() {


        $sql1 = "select card_sip_id,sip_code from card_sip  ";
        $r = $this->query($sql1);
        $size = count($r);
        $l = array();
        for ($i = 0; $i < $size; $i++) {
            $key = $r[$i][0]['card_sip_id'];
            $l[$key] = $r[$i][0]['sip_code'];
        }
        return $l;
    }

    //记录前台UI日志


    /*     * 第3版
     * 生成多条提示信息()并且将用户的添加的数据返回界面
     * @param 提示信息种类 $code
     * @param 提示信息 $msg
     * @param $field 前台界面form元素的id
     * @param $value 用户界面输入的数据
     * @return 创建前台json数组
     */
    function create_json_array_data($field, $code, $msg, $value) {
        $arr = array('field' => $field, 'code' => $code, 'msg' => $msg, 'value' => $value); //组装一个新数组
        array_push(self::$tip_info, $arr); //向数组添加一个元素
    }

    /**
     * 第3版
     * 向界面设置验证信息并且返回用户输入的数据
     */
    public static function set_validator_data() {
        $tmp = self::$tip_info;
        //pr($tmp);
        $len = count($tmp);
        //pr($len);
        $str = '';
        for ($i = 0; $i < $len; $i++) {
            $field = $tmp [$i] ['field'];
            $code = $tmp [$i] ['code'];
            $msg = $tmp [$i] ['msg'];
            $value = $tmp [$i] ['value'];
            $d = "{'field':'$field','code':'$code','msg':'$msg','value':'$value'},";
            if ($i == $len - 1) {
                $d = "{'field':'$field','code':'$code','msg':'$msg','value':'$value'}";
            }
            $str = $str . $d;
        }
        $_POST ['tip_info'] = '[' . $str . ']';
        // pr($_POST['tip_info']);
        return $_POST ['tip_info'];
    }

    /*     * (第2版)
     * 生成多条提示信息
     * @param 提示信息种类 $code
     * @param 提示信息 $msg
     * @param $field 前台界面form元素的id
     * @return 创建前台json数组
     */

    function create_json_array($field, $code, $msg) {
        $arr = array('field' => $field, 'code' => $code, 'msg' => $msg); //组装一个新数组
        array_push(self::$tip_info, $arr); //向数组添加一个元素
    }

    /**
     * 生成单条提示信息(第一版)
     * @param unknown_type $code
     * @param unknown_type $msg
     * @return 创建前台json数组
     */
    function create_json($code, $msg) {
        return "[{'code':$code,'msg':'$msg'}]";
    }

    /**
     * 向界面设置验证信息
     */
    public static function set_validator() {
        $tmp = self::$tip_info;
        $len = count($tmp);
        $str = '';
        for ($i = 0; $i < $len; $i++) {
            $field = $tmp [$i] ['field'];
            $code = $tmp [$i] ['code'];

            $msg = $tmp [$i] ['msg'];
            $d = "{'field':'$field','code':'$code','msg':'$msg'},";
            if ($i == $len - 1) {
                $d = "{'field':'$field','code':'$code','msg':'$msg'}";
            }
            $str = $str . $d;
        }
        $_POST ['tip_info'] = '[' . $str . ']';
        return $_POST ['tip_info'];
    }

    /**
     * 从界面上的$_POST变量中获取key
     * @param  $post_arr
     * @param $key 
     */
    function getkeyByPOST($key, $post_arr) {
        isset($post_arr [$key]) ? ($id = $post_arr [$key]) : $id = '';
        return $id;
    }

    //事务管理
    function begin() {
        $db = & ConnectionManager::getDataSource($this->useDbConfig);
        $db->begin($db);
    }

    function commit() {
        $db = & ConnectionManager::getDataSource($this->useDbConfig);
        $db->commit($db);
    }

    function rollback() {
        $db = & ConnectionManager::getDataSource($this->useDbConfig);
        $db->rollback($db);
    }

    /*
     * 设置数据库自动提交事务或者手动提交  true  自动提交
     */

    function setautocommit($flag = true) {
        $db = & ConnectionManager::getDataSource($this->useDbConfig);
        $db->autocommit($flag);
    }

    /**
     * 
     * @param String $file_name  The file name you specified
     * @param String $title The head information of this file
     * @param String $yourdata Output data
     * 
     * 调用该方法时首先执行
     * Configure::write('debug', 0);//关闭调试状态
     * $this->layout = '';//不使用布局
     */
    public function downLoadFile($title, $yourdata = array(), $file_name) {
        if (empty($file_name)) {
            $file_name = time();
        }

        $file_name = $file_name . '.csv'; //File Name
        $file_dir = '/tmp/'; //This file will saved into this dir
        //Check if the dir exists,if not,create it.
        if (!file_exists($file_dir)) {
            mkdir($file_dir, 0777); //Create a dir named 'tmp'
        }

        $file_path = $file_dir . $file_name;

        $fp = fopen($file_path, "w+");

        //Output the title
        fwrite($fp, $title);
        fwrite($fp, "\n");

        //Output the data
        $loop = count($yourdata);
        for ($i = 0; $i < $loop; $i++) {
            $tmpdata = $yourdata [$i] [0];
            $d = array_keys($tmpdata);
            $row = '';
            foreach ($d as $j) {
                $h = $tmpdata [$j] == null ? "none" : $tmpdata [$j];
                $row .= $h . ',';
            }
            $row = substr($row, 0, strlen($row) - 1);

            fwrite($fp, $row);
            fwrite($fp, "\n");
        }

        fclose($fp);

        $file_size = filesize($file_path);

        //Download the file
        header("Content-type:application/octet-stream");
        header("Accept-Ranges:bytes");
        header("Accept-Length:$file_size");
        header("Content-Disposition:attachment;filename=" . $file_name);

        $buffer_size = 1024;
        $cur_pos = 0;
        $fp = fopen($file_path, "r");
        while (!feof($fp) && $file_size - $cur_pos > $buffer_size) {
            $buffer = fread($fp, $buffer_size);
            echo $buffer;
            $cur_pos += $buffer_size;
        }

        $buffer = fread($fp, $file_size - $cur_pos);
        echo $buffer;
        fclose($fp);
        return true;
    }

    /**
     * 取得CSV文件的所有数据
     * @param $fileName input标签type=file的文本框的name值
     * @return 一个数组  数组的每个元素是一个对象   该对象就是CSV文件的一行数据
     * 该对象的属性就是CSV文件第一行标题
     * For example:
     * CSV File:
     * product_id,product_name,policy
     * 1001,test,robin
     * 1002,test1,down
     * 
     * Return:[
     * {product_id:1001,product_name:test,policy:robin},
     * {product_id:1002,product_name:test1,policy:down}
     * ]
     */
    function getUploadData($fileName = null) {
        $t = $_FILES ["$fileName"] ['tmp_name'];
        $file = fopen("$t", "r");
        $i = 1;
        $finald = array(); //保存所有的数据
        $properties = array(); //保存对象的属性
        while (!feof($file)) {
            $d = fgetcsv($file, 1000); //读取一行
            if (++$i == 2) { //第一次循环  记录有多少属性
                $loop = count($d);
                for ($j = 0; $j < $loop; $j++) {
                    array_push($properties, $d [$j]);
                }
            } else {
                if (!empty($d)) {
                    $fd = new FileData ();
                    $loop = count($properties);
                    for ($j = 0; $j < $loop; $j++) {
                        $hh = strtolower($properties [$j]);
                        $fd->$hh = $d [$j];
                    }
                    array_push($finald, $fd);
                }
            }
            $i++;
        }
        fclose($file);
        return $finald;
    }

    //下载指定路径的文件
    function download_file($file_path) {
        $file_size = filesize($file_path);

        //判断路径是否包含/字符  包含则从最后一个/开始截取到最后 作为文件名
        $hasspecial = strripos($file_path, "/");
        $file_name = $file_path;
        if ($hasspecial != false) {
            $file_name = substr($file_path, $hasspecial);
        }

        header("Content-type:application/octet-stream");
        header("Accept-Ranges:bytes");
        header("Accept-Length:$file_size");
        header("Content-Disposition:attachment;filename=" . $file_name);

        $fp = fopen($file_path, "r");
        $buffer_size = 1024;
        $cur_pos = 0;

        while (!feof($fp) && $file_size - $cur_pos > $buffer_size) {
            $buffer = fread($fp, $buffer_size);
            echo $buffer;
            $cur_pos += $buffer_size;
        }

        $buffer = fread($fp, $file_size - $cur_pos);
        echo $buffer;
        fclose($fp);
        //	$this->redirect ( array ('controller' => $controller_name, 'action' => $action_name ) );
    }

    /*
     * 生成随即数
     */

    public function randStr($len = 6, $format = 'ALL') {
        switch ($format) {
            case 'ALL' :
                $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-@~';
                break;
            case 'CHAR' :
                $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz-@~';
                break;
            case 'NUMBER' :
                $chars = '0123456789';
                break;
            default :
                $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-@~';
                break;
        }
        mt_srand((double) microtime() * 1000000 * getmypid());
        $password = "";
        while (strlen($password) < $len)
            $password .= substr($chars, (mt_rand() % strlen($chars)), 1);
        return $password;
    }

    /**
     * -------------------------------------------------------------------------------------------
     * PHP表单验证类
     * ---------------------------------------------------------------------------------
     */
    /*
      -----------------------------------------------------------
      函数名称：isNumber
      简要描述：检查输入的是否为数字
      输入：string
      输出：boolean
      修改日志：------
      -----------------------------------------------------------
     */
    function isNumber($val) {
        if (ereg("^[0-9]+$", $val)) {
            return true;
        } else {
            return false;
        }
    }

    /*
      -----------------------------------------------------------
      函数名称：isDate
      简要描述：检查日期是否符合0000-00-00
      输入：string
      输出：boolean
      修改日志：------
      -----------------------------------------------------------
     */

    function isDate($sDate) {
        if (ereg("^[0-9]{4}\-[][0-9]{2}\-[0-9]{2}$", $sDate)) {
            return true;
        } else {
            Return false;
        }
    }

    /*
      -----------------------------------------------------------
      函数名称：isTime
      简要描述：检查日期是否符合0000-00-00 00:00:00
      输入：string
      输出：boolean
      修改日志：------
      -----------------------------------------------------------
     */

    function isTime($sTime) {
        if (ereg("/^[0-9]{4}\-[][0-9]{2}\-[0-9]{2} [0-9]{2}:[0-9]{2}:[0-9]{2}$/", $sTime)) {
            Return true;
        } else {
            Return false;
        }
    }

    /*
      -----------------------------------------------------------
      函数名称:isIp($val)
      简要描述:检查输入IP是否符合要求
      输入:string
      输出:boolean
      修改日志:------
      -----------------------------------------------------------
     */

    function isIp($val) {
        return (bool) ip2long($val);
    }

    /*
      -----------------------------------------------------------
      函数名称：isName
      简要描述：姓名昵称合法性检查，只能输入中文英文
      输入：string
      输出：boolean
      修改日志：------
      -----------------------------------------------------------
     */

    function isName($val) {
        if (preg_match("/^[\x80-\xffa-zA-Z0-9]{3,60}$/", $val)) { //2008-7-24
            return true;
        }
        return false;
    }

//end func	

    function _get_order($default = '') {
        $order = $default;
        if (!empty($_GET['order_by'])) {
            $arr = split('-', $_GET['order_by']);
            $order = " order by {$arr[0]} {$arr [1]}";
        }
        return $order;
    }

    function _get_page($default = 1) {
        empty($_GET['page']) ? $currPage = $default : $currPage = $_GET['page'];
        return $currPage;
    }

    function _get_size($default = 100) {
        if (isset($_SESSION['paging_row'])) {
            $default = $_SESSION['paging_row'];
        }
        empty($_GET['size']) ? $pageSize = $default : $pageSize = $_GET['size'];
        $_SESSION['paging_row'] = $pageSize;
        return $pageSize;
    }

    function xvalidate($data, $Arr) {
        $re = true;
        $this->xvalidate_data = $data;
        foreach ($Arr as $key => $value) {
            $str = array_keys_value($data, $key);
            foreach ($value as $f => $options) {
                $message = $options;
                if (is_array($options)) {
                    $message = $options['message'];
                }
                $fun = 'xvalidate_' . $f;
                if (!$this->$fun($str, $key, $options)) {
                    $id = '#' . $this->name . ucfirst($key);
                    $this->create_json_array($id, 101, $message);
                    $re = false;
                    break;
                }
            }
        }
        return $re;
    }

    function xvalidated($data) {
        return $this->xvalidate($data, $this->xvalidatevar);
    }

    function xvalidate_noEmpty($str, $key, $options) {
        $id = array_keys_value($this->xvalidate_data, $this->primaryKey);
        if (!empty($id) && !array_keys_exists($this->xvalidate_data, $key)) {
            pr($key);
            pr($this->xvalidate_data);
            return true;
        }
        return !empty($str);
    }

    function xvalidate_en($str, $key, $options) {
        if (empty($str)) {
            return true;
        }
        return preg_match("/^[0-9a-zA-Z]*$/", $str);
    }

    function xvalidate_int($str, $key, $options) {
        return preg_match("/^[0-9]*$/", $str);
    }

    function xvalidate_float($str, $key, $options) {
        if (empty($str)) {
            return true;
        }
        return preg_match("/^[0-9.]*$/", $str);
    }

    function xvalidate_between($str, $key, $options = Array()) {
        $options = array_merge(Array('max' => 99, 'min' => 0));
        if (empty($str)) {
            return true;
        }
        if (!is_int($value)) {
            return false;
        }
        $value+=0;
        if ($value > $options['max'] || $value < $options['min']) {
            return false;
        }
        return true;
    }

    function xvalidate_email($str, $key, $options) {
        if (empty($str)) {
            return true;
        }
        return preg_match("/^[a-zA-Z0-9-]+@[a-zA-Z0-9.-]+$/", $str);
    }

    function xvalidate_ip($str, $key, $options) {
        if (empty($str)) {
            return true;
        }
        return preg_match("/^[0-9.]*$/", $str);
    }

    function xvalidate_unique($str, $key, $options) {
        if (empty($str)) {
            return true;
        }
        $id = array_keys_value($this->xvalidate_data, $this->primaryKey);
        $conditions = Array($key => $str);
        if (!empty($id)) {
            $conditions[] = "{$this->primaryKey} <> $id";
        }
        $count = $this->find('count', Array('conditions' => $conditions));
        return !$count > 0;
    }

    function xvalidate_length($str, $key, $options) {
        if (empty($str)) {
            return true;
        }
        $length = strlen($str);
        return (int) $length <= (int) $options['length'];
    }

    /* 	function _get_error_message($name,$type=1,$options=Array())
      {
      $error_message=array(
      1=>"$name cannot be NULL!",
      2=>"$name must contain numeric characters only!",
      3=>"$name must contain alphanumeric characters only!",
      4=>"$name can be either T or R. ‘T’ means Top-Down and ‘R’ means Round-Robin",
      5=>"$name must be between {$options['min']} to {$options['max']}",
      6=>"$name can have at most characters of",
      7=>"$name IPs must be a valid format.  The following IPs are not valid",
      8=>"$name Emails must be a valid format.  The following Emails are not valid"
      );
      } */

    function log($type = '') {
        $model = $this->name;
        $ip = ($_SERVER['REMOTE_ADDR']);
        //$user = $this->query("select * from users where user_id = {$_SESSION['sst_user_id']}");
        $user_id = intval($_SESSION['sst_user_id']);
        $act_info = json_encode($_REQUEST);
        $time = date("Y-m-d H:i:s+00");
        $this->query("insert into logs (user_id, ip, model, act_type, act_info, \"time\") values ({$user_id}, '{$ip}', '{$model}', '{$type}', '{$act_info}', '{$time}')");
    }

    function _get_psql_cmd($sql)
    {
        Configure::load('myconf');
        $class_dbconfig = new DATABASE_CONFIG();
        $conn_config = $class_dbconfig->default;
        $psql_bin = Configure::read('psql_bin');
        if (!$psql_bin)
            $psql_bin = 'psql';
        $password = $conn_config['password'];
        $cmd =  "$psql_bin -p {$conn_config['port']} -h {$conn_config['host']} -U {$conn_config['login']} -d {$conn_config['database']} ";
        if($password)
            $cmd .= "-W ";
        $cmd = "{$cmd} -c \"{$sql}\" > /dev/null";
        $fp = popen($cmd,"w");
        if($password)
            fputs($fp,$password);
        pclose($fp);
    }

    /**
     * This function can make some balance operations, depending on the @param $action:
     * 0 - Added a new client balance
     * 1 - Reset client balance, ingress balance, egress balance
     * 2 - Increase ingress balance
     * 3 - Decrease ingress balance
     * 4 - Increase egress balance
     * 5 - Decrease egress balance
     *
     * @param $clientId
     * @param $amount
     * @param $action
     * @param $returnBalance
     * @return mixed
     */
    public function clientBalanceOperation($clientId, $amount, $action, $returnBalance = false)
    {
        if (!($action >= 0 && $action <= 5)) {
            return false;
        }

        $sqlReturn = $returnBalance ? "returning balance" : "";
        $createBy = isset($_SESSION['sst_user_name'])?$_SESSION['sst_user_name']:'';

        if ($action == 0) {
            $sql = "INSERT INTO client_balance_operation_action(client_id, balance, action, create_by) values ('$clientId', $amount, $action, '$createBy') $sqlReturn";
        } elseif ($action == 1) {
            $sql = "INSERT INTO client_balance_operation_action(client_id, balance, ingress_balance, egress_balance, action, create_by) values ('$clientId', $amount, $amount, $amount, $action, '$createBy') $sqlReturn";
        } elseif ($action == 2) {
            $sql = "INSERT INTO client_balance_operation_action(client_id, balance, ingress_balance, action, create_by) values ('$clientId', $amount, $amount, $action, '$createBy') $sqlReturn";
        } elseif ($action == 3) {
            $sql = "INSERT INTO client_balance_operation_action(client_id, balance, ingress_balance, action, create_by) values ('$clientId', $amount, $amount, $action, '$createBy') $sqlReturn";
        } elseif ($action == 4) {
            $sql = "INSERT INTO client_balance_operation_action(client_id, balance, egress_balance, action, create_by) values ('$clientId', $amount, $amount, $action, '$createBy') $sqlReturn";
        } else {
            $sql = "INSERT INTO client_balance_operation_action(client_id, balance, egress_balance, action, create_by) values ('$clientId', $amount, $amount, $action, '$createBy') $sqlReturn";
        }

        return $this->query($sql);
    }

}

class FileData {
    
}
