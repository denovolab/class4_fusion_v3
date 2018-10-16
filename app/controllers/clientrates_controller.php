<?php

class ClientratesController extends AppController
{

    var $name = 'Clientrates';
    var $components = array('RequestHandler');
    var $helpers = array('javascript', 'html', 'AppRate');

    public function ajax_delete_rate()
    {
        Configure::write('debug', 0);
        $rate_id = $_POST['rate_id'];

        $sql1 = "delete  from  rate  where  rate_id=$rate_id ";


        $this->Clientrate->query($sql1);

        try
        {
            $this->set('extensionBeans', 1);
        } catch (Exception $e)
        {
            echo "Server Exception";
        }
    }
    
    public function checkuploading()
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $rate_table_id = $_POST['ratetable_id'];
        $sql = "select status from rate_upload_queue where rate_table_id = {$rate_table_id}";
        $result = $this->Clientrate->query($sql);
        $response_data = array(
            'waiting' => 0,
            'progressing' => 0,
            'ending_date' => 0,
        );
        
        foreach($result as $item)
        {
            if ($item[0]['status'] == '0')
                $response_data['waiting'] += 1;
            else if($item[0]['status'] == '1')
                $response_data['ending_date'] += 1;
            else
                $response_data['progressing'] += 1;
        }
        
        echo json_encode($response_data);
    }
    
    public function upload()
    {
        
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $targetFolder = Configure::read('rateimport.put');

        if (!empty($_FILES)) {
                $fileName = time() . '_' . uniqid();
                $targetFile = $targetFolder . '/' . $fileName .".csv";
               

                // Validate the file type
                $fileTypes = array('csv', 'xls', 'xlsx'); // File extensions
                $fileParts = pathinfo($_FILES['Filedata']['name']);
                

                if (in_array($fileParts['extension'],$fileTypes)) {
                        if ($fileParts['extension'] == 'xls' || $fileParts['extension']  == 'xlsx') {
                            $targetFile1 = $targetFolder . '/' . $fileName . ".". $fileParts['extension'];
                            move_uploaded_file($_FILES['Filedata']['tmp_name'],$targetFile1);
                            Configure::load('myconf');
                            $script_path = Configure::read('script.path');
                            $script_file_path = $script_path . DS . "xls2csv.pl";
                            $cmd = "perl $script_file_path -s '$targetFile1' -d {$targetFile} -t {$fileParts['extension']} ";
                            shell_exec($cmd);
                            file_put_contents('/tmp/test', $cmd);
                        } else {
                            move_uploaded_file($_FILES['Filedata']['tmp_name'],$targetFile);
                        }
                        echo $fileName;
                } else {
                        $this->header('HTTP/1.1 403 Forbidden');
                        echo 'Sorry! We are unable to recognize your file　format.';
                }
        }
    }

//读取该模块的执行和修改权限
    public function beforeFilter()
    {
        if (isset($_POST['PHPSESSID']))
        {
            session_id($_POST['PHPSESSID']);
            session_start();
        }
        if( $this->params['action'] == 'upload')
        {
            Configure::load('myconf');
            return true;
        }
        $this->checkSession("login_type"); //核查用户身份
        $login_type = $this->Session->read('login_type');
        if ($login_type == 1)
        {
            //admin
            $this->Session->write('executable', true);
            $this->Session->write('writable', true);
        } else
        {
            $limit = $this->Session->read('sst_wholesale');
            $this->Session->write('executable', $limit['executable']);
            $this->Session->write('writable', $limit['writable']);
        }
        if ($this->RequestHandler->isGet())
        {
            $url = $this->get_curr_url();
            if (!isset($_SESSION['back_url']))
            {
                $last_url = $url;
                $curr_url = $url;
                $_SESSION['back_url'] = $last_url;
                $_SESSION['curr_url'] = $curr_url;
            } else
            {
                if ($_SESSION['curr_url'] != $url)
                {
                    $_SESSION['curr_url'] = $url;
                }
                if (strpos($url, "view"))
                {
                    $_SESSION['back_url'] = $url;
                }
            }
        }
        parent::beforeFilter(); //调用父类方法
    }

    /**
     * 
     * 模拟计费
     */
    function simulate()
    {
        $this->pageTitle = "Editing rates";
        $table_id = $this->params['pass'][0];
        $defualt_zone = $this->Clientrate->query("select sys_timezone from system_parameter");
        $this->set('dzone', $defualt_zone[0][0]['sys_timezone']);
        $this->set('table_id', $table_id);
        $this->set('name', $this->select_client_name($table_id));
        $this->loadModel('Rate');
        if (isset($_REQUEST['process']) && $_REQUEST['process'] == '1')
        {
            $arr = array();
            for ($i = 0; $i < count($_POST['date']); $i++)
            {

                $result = $this->Rate->simulated1($_POST['date'][$i], $_POST['time'][$i], $_POST['tz'][$i], $_POST['ani'][$i], $_POST['dnis'][$i], $_POST['duration'][$i], $table_id);
                array_push($arr, $result);
            }
            $this->set('data', $arr);


            //$this->set('data',$this->Rate->simulated($date,$number,$durations,$table_id));
        }
        $list =  $this->Rate->query('select jur_type, currency_id from rate_table where rate_table_id = ' . $table_id);
        $this->set('jur_type', $list[0][0]['jur_type']);
        $this->set('currency', $list[0][0]['currency_id']);
    }

    public function test()
    {
        $this->Clientrate->get_prefix_rate(array('89009', '5656'));
    }

//public  function   view
//_carrier_rate($type='false'){
//	if(!empty($_GET['route_prefix'])){
//		$prefix=$_GET['route_prefix'];
//		$prefix_arr=array($prefix);
//	}else{
//		$prefix_arr=$this->Clientrate->find_client_route_prefix();
//	}
//	$table_id=$this->Clientrate->get_prefix_rate($prefix_arr);
//	if(empty($table_id)){
//		$this->set('p','');
//	}else{
//		$this->set('table_id',$table_id);		
//		$type=_filter_array(Array('true'=>true,'false'=>false),$type,false);
//		if($type){
//			$sql=$this->Clientrate->find_all_rate($table_id,$this->_order_condtions(array('code','code_name','rate','setup_fee','effective_date','end_date')),Array('getSql'=>true));
//			$this->Clientrate->export__sql_data('download Cdr',$sql,'cdr');
//			$this->layout='csv';
//			Configure::write('debug',0);
//			exit();
//		}
//		$this->set('p',$this->Clientrate->find_all_rate($table_id,$this->_order_condtions(array('code','code_name','rate','setup_fee','effective_date','end_date'))));
//		
//	}
//	$this->set('route_prefix',$this->Clientrate->find_client_route_prefix());
//}

    public function view_rate($gress = 0)
    {
        $client_id = $_SESSION['sst_client_id'];
        $rate_table_ids = array();
        if (!$gress)
        {
            $rate_table_ids = $this->Clientrate->query("select rate_table_id from resource where client_id = {$client_id} and egress = true and rate_table_id is not null");
        } else
        {
            $rate_table_ids = $this->Clientrate->query("select resource_id from resource where client_id = {$client_id} and ingress = true");
            if (count($rate_table_ids))
            {
                $temp_arr = array();
                foreach ($rate_table_ids as $rate_table_id)
                {
                    array_push($temp_arr, $rate_table_id[0]['resource_id']);
                }

                $rate_table_ids = $this->Clientrate->query("select rate_table_id from resource_prefix where resource_id in (" . implode(",", $temp_arr) . ") and rate_table_id is not null");
            }
        }
        $rate_table_ids_join = array();
        $page = "";

        if (count($rate_table_ids))
        {

            $search_where = '';

            if (isset($_GET['name']))
            {
                $search_where .= " and rate_table.name ilike '{$_GET['name']}%'";
            }

            foreach ($rate_table_ids as $rate_table_id)
            {
                array_push($rate_table_ids_join, $rate_table_id[0]['rate_table_id']);
            }

            $temp = isset($_SESSION['paging_row']) ? $_SESSION['paging_row'] : 100;

            empty($_GET['page']) ? $currPage = 1 : $currPage = $_GET['page'];
            empty($_GET['size']) ? $pageSize = $temp : $pageSize = $_GET['size'];

            $_SESSION['paging_row'] = $pageSize;

            require_once 'MyPage.php';

            $page = new MyPage();

            $totalrecords = $this->Clientrate->query("select count(rate_table_id) as c from rate_table where rate_table.rate_table_id in (" . implode(",", $rate_table_ids_join) . ")");

            $page->setTotalRecords($totalrecords[0][0]['c']);
            $page->setCurrPage($currPage);
            $page->setPageSize($pageSize);

            $currPage = $page->getCurrPage() - 1;
            $pageSize = $page->getPageSize();
            $offset = $currPage * $pageSize;

            $rate_tables = $this->Clientrate->query("select 
                                                         rate_table.rate_table_id, rate_table.name, code_deck.name as code_deck, currency.code as currency, jurisdiction.name as jurisdiction
                                                         from rate_table 
                                                         left join code_deck on rate_table.code_deck_id = code_deck.code_deck_id
                                                         left join currency on rate_table.currency_id = currency.currency_id
                                                         left join jurisdiction on rate_table.jurisdiction_country_id  = jurisdiction.jurisdiction_country_id
                                                         where rate_table.rate_table_id in (" . implode(",", $rate_table_ids_join) . ") {$search_where}
                                                         limit $pageSize offset $offset");
            if ($totalrecords[0][0]['c'] == 1)
            {
                $this->redirect("/clientrates/view_rate_detail/" . base64_encode($rate_tables[0][0]['rate_table_id']));
            }
            $page->setDataArray($rate_tables);
        }
        $this->set('active_type', $gress);
        $this->set("p", $page);
    }

    public function view_rate_ingress()
    {
        $client_id = $_SESSION['sst_client_id'];
        $temp = isset($_SESSION['paging_row']) ? $_SESSION['paging_row'] : 100;

        empty($_GET['page']) ? $currPage = 1 : $currPage = $_GET['page'];
        empty($_GET['size']) ? $pageSize = $temp : $pageSize = $_GET['size'];

        $_SESSION['paging_row'] = $pageSize;

        require_once 'MyPage.php';

        $page = new MyPage();

        $totalrecords = $this->Clientrate->query("SELECT count(*) FROM resource_prefix 
                INNER JOIN resource ON resource_prefix.resource_id = resource.resource_id WHERE  resource.client_id = $client_id");
        $page->setTotalRecords($totalrecords[0][0]['count']);
        $page->setCurrPage($currPage);
        $page->setPageSize($pageSize);
        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();
        $offset = $currPage * $pageSize;
        $sql = "SELECT resource_prefix.rate_table_id, resource_prefix.tech_prefix FROM resource_prefix 
                INNER JOIN resource ON resource_prefix.resource_id = resource.resource_id WHERE  resource.client_id = $client_id limit $pageSize offset $offset";
        $data = $this->Clientrate->query($sql);

        if ($totalrecords[0][0]['count'] == 1)
        {
            $this->redirect('/clientrates/view_rate_detail/' . base64_encode($data[0][0]['rate_table_id']));
        }
        $page->setDataArray($data);
        $this->set("p", $page);
    }

    public function view_rate_egress()
    {
        ob_start();
        //Configure::write('debug', '0');

        $client_id = $_SESSION['sst_client_id'];
        $rate_tables = array();
        $sql = "select rate_table_id from resource where client_id = {$client_id} and egress = true";
        $result = $this->Clientrate->query($sql);
        foreach ($result as $item)
        {
            array_push($rate_tables, $item[0]['rate_table_id']);
        }
        $rate_tables_str = implode(',', $rate_tables);

        $rates = array();

        $search_where = '';

        if (isset($_GET['code']))
        {
            $search_where .= " and rate.code::text ilike '{$_GET['code']}%'";
        }

        if (isset($_GET['adv_search']) && $_GET['adv_search'] == 1)
        {
            if (isset($_GET['rate_begin']) && !empty($_GET['rate_begin']))
            {
                $search_where .= " and rate.rate > {$_GET['rate_begin']}";
            }
            if (isset($_GET['rate_end']) && !empty($_GET['rate_end']))
            {
                $search_where .= " and rate.rate <= {$_GET['rate_end']}";
            }
            if (isset($_GET['profile']) && !empty($_GET['profile']))
            {
                $search_where .= " and rate.time_profile_id = {$_GET['profile']}";
            }
            if (isset($_GET['code_name']) && !empty($_GET['code_name']))
            {
                $search_where .= " and rate.code_name = '{$_GET['code_name']}'";
            }
            if (isset($_GET['country']) && !empty($_GET['country']))
            {
                $search_where .= " and rate.country = '{$_GET['country']}'";
            }
            if (isset($_GET['time']) && !empty($_GET['time']))
            {
                switch ($_GET['time'])
                {
                    case 'current':
                        $search_where = " and rate.effective_date <= '{$_GET['time_val']}'";
                        break;
                    case 'new':
                        $search_where = " and rate.effective_date >= '{$_GET['time_val']}'";
                        break;
                    case 'old':
                        $search_where = " and rate.end_date <= '{$_GET['time_val']}'";
                        break;
                    case 'in':
                        $search_where = " and (rate.effective_date <= '{$_GET['time_val']}' and rate.end_date >= '{$_GET['time_val']}' or rate.effective_date <= '{$_GET['time_val']}' and rate.end_date is null)";
                        break;
                }
            }
        }


        $temp = isset($_SESSION['paging_row']) ? $_SESSION['paging_row'] : 100;

        empty($_GET['page']) ? $currPage = 1 : $currPage = $_GET['page'];
        empty($_GET['size']) ? $pageSize = $temp : $pageSize = $_GET['size'];

        $_SESSION['paging_row'] = $pageSize;

        require_once 'MyPage.php';

        $page = new MyPage();

        $totalrecords = $this->Clientrate->query("select count(rate_id) as c from rate where rate_table_id in ({$rate_tables_str}) $search_where");

        $page->setTotalRecords($totalrecords[0][0]['c']);
        $page->setCurrPage($currPage);
        $page->setPageSize($pageSize);

        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();
        $offset = $currPage * $pageSize;

        $sql = "select rate.*, time_profile.name as time_profile from rate left join time_profile on rate.time_profile_id = time_profile.time_profile_id where rate_table_id in ({$rate_tables_str}) {$search_where}";



        if (isset($_GET['getcsv']) && $_GET['getcsv'])
        {
            $rates = $this->Clientrate->query($sql);
            ob_end_clean();
            header("Content-Type: text/csv");
            header("Content-Disposition: attachment; filename=rate.csv");
            header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
            header('Expires:0');
            header('Pragma:public');
            echo "Code,Code Name,Country,Rate,Intra Rate,Inter Rate,Setup Fee,Effective Date,End Date,Min Time,Interval,Grace Time,Seconds,Profile,Time Zone\n";
            foreach ($rates as $rate)
            {
                $temp_zone = empty($rate[0]['zone']) ? '"+00"' : $rate[0]['zone'];
                echo "{$rate[0]['code']},{$rate[0]['code_name']},{$rate[0]['country']},{$rate[0]['rate']},{$rate[0]['intra_rate']},{$rate[0]['inter_rate']},{$rate[0]['setup_fee']},{$rate[0]['effective_date']},{$rate[0]['end_date']},{$rate[0]['min_time']},{$rate[0]['interval']},{$rate[0]['grace_time']},{$rate[0]['seconds']},{$rate[0]['time_profile']},{$temp_zone}\n";
            }
            exit;
        }

        $sql .= " limit $pageSize offset $offset";


        $rates = $this->Clientrate->query($sql);

        $page->setDataArray($rates);

        $this->set("p", $page);

        $profiles = $this->Clientrate->query("select time_profile_id, name from time_profile");

        $this->set("profiles", $profiles);
    }

    public function view_rate_detail($id)
    {
        ob_start();
        Configure::write('debug', '0');

        $id = base64_decode($id);

        $rates = array();

        $search_where = '';

        if (isset($_GET['code']))
        {
            $search_where .= " and rate.code::text ilike '{$_GET['code']}%'";
        }

        if (isset($_GET['adv_search']) && $_GET['adv_search'] == 1)
        {
            if (isset($_GET['rate_begin']) && !empty($_GET['rate_begin']))
            {
                $search_where .= " and rate.rate > {$_GET['rate_begin']}";
            }
            if (isset($_GET['rate_end']) && !empty($_GET['rate_end']))
            {
                $search_where .= " and rate.rate <= {$_GET['rate_end']}";
            }
            if (isset($_GET['profile']) && !empty($_GET['profile']))
            {
                $search_where .= " and rate.time_profile_id = {$_GET['profile']}";
            }
            if (isset($_GET['code_name']) && !empty($_GET['code_name']))
            {
                $search_where .= " and rate.code_name = '{$_GET['code_name']}'";
            }
            if (isset($_GET['country']) && !empty($_GET['country']))
            {
                $search_where .= " and rate.country = '{$_GET['country']}'";
            }
            if (isset($_GET['time']) && !empty($_GET['time']))
            {
                switch ($_GET['time'])
                {
                    case 'current':
                        $search_where = " and rate.effective_date <= '{$_GET['time_val']}'";
                        break;
                    case 'new':
                        $search_where = " and rate.effective_date >= '{$_GET['time_val']}'";
                        break;
                    case 'old':
                        $search_where = " and rate.end_date <= '{$_GET['time_val']}'";
                        break;
                    case 'in':
                        $search_where = " and (rate.effective_date <= '{$_GET['time_val']}' and rate.end_date >= '{$_GET['time_val']}' or rate.effective_date <= '{$_GET['time_val']}' and rate.end_date is null)";
                        break;
                }
            }
        }


        $temp = isset($_SESSION['paging_row']) ? $_SESSION['paging_row'] : 100;

        empty($_GET['page']) ? $currPage = 1 : $currPage = $_GET['page'];
        empty($_GET['size']) ? $pageSize = $temp : $pageSize = $_GET['size'];

        $_SESSION['paging_row'] = $pageSize;

        require_once 'MyPage.php';

        $page = new MyPage();

        $totalrecords = $this->Clientrate->query("select count(rate_id) as c from rate where rate_table_id = {$id} $search_where");

        $page->setTotalRecords($totalrecords[0][0]['c']);
        $page->setCurrPage($currPage);
        $page->setPageSize($pageSize);

        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();
        $offset = $currPage * $pageSize;

        $sql = "select rate.*, time_profile.name as time_profile from rate left join time_profile on rate.time_profile_id = time_profile.time_profile_id where rate_table_id = {$id} {$search_where}";



        if (isset($_GET['getcsv']) && $_GET['getcsv'])
        {
            $rates = $this->Clientrate->query($sql);
            ob_end_clean();
            header("Content-Type: text/csv");
            header("Content-Disposition: attachment; filename=rate.csv");
            header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
            header('Expires:0');
            header('Pragma:public');
            echo "Code,Code Name,Country,Rate,Intra Rate,Inter Rate,Setup Fee,Effective Date,End Date,Min Time,Interval,Grace Time,Seconds,Profile,Time Zone\n";
            foreach ($rates as $rate)
            {
                $temp_zone = empty($rate[0]['zone']) ? '"+00"' : $rate[0]['zone'];
                echo "{$rate[0]['code']},{$rate[0]['code_name']},{$rate[0]['country']},{$rate[0]['rate']},{$rate[0]['intra_rate']},{$rate[0]['inter_rate']},{$rate[0]['setup_fee']},{$rate[0]['effective_date']},{$rate[0]['end_date']},{$rate[0]['min_time']},{$rate[0]['interval']},{$rate[0]['grace_time']},{$rate[0]['seconds']},{$rate[0]['time_profile']},{$temp_zone}\n";
            }
            exit;
        }

        $sql .= " limit $pageSize offset $offset";


        $rates = $this->Clientrate->query($sql);

        $page->setDataArray($rates);

        $this->set("p", $page);

        $profiles = $this->Clientrate->query("select time_profile_id, name from time_profile");

        $this->set("profiles", $profiles);
    }

    public function view($table_id, $massEdit = null, $npa='')
    {
        $table_id+=0;
        $this->pageTitle = "Editing rates";
        if ($massEdit == 'massEdit')
        {
            $url = $this->massEdit($table_id);
        }
        $times = $this->Clientrate->getTimeProfile();
        $t = '';
        foreach ($times as $key => $value)
        {
            $time_profile_id = $value[0]['time_profile_id'];
            $name = $value[0]['name'];
            $t.="\"$time_profile_id\": \"$name\",";
        }
        $t = "{" . substr($t, 0, -1) . "}";
        $this->set('table_id', $table_id);
        $this->loadModel('Rate');
        $result = $this->Rate->getOneRate($table_id);
        $addShowResult = $result[0][0];
        switch ($addShowResult)
        {
            case 0:
                $addShowResult['rate_type'] = "DNIS";
                break;
            case 1:
                $addShowResult['rate_type'] = "lrn";
                break;
            case 2:
                $addShowResult['rate_type'] = "lrn block";
                break;
            default:
                $addShowResult['rate_type'] = "DNIS";
        }
        $this->set('addShowResult', $addShowResult);
        $list = $this->Clientrate->query("select name ,jurisdiction_country_id,(select code from currency where currency_id = rate_table.currency_id) as currency, jur_type from  rate_table  where  rate_table_id=$table_id");
        $this->set('table_name', $list[0][0]['name']);
        $this->set('jurisdiction_country_id', $list[0][0]['jurisdiction_country_id']);
        $this->set('currency', $list[0][0]['currency']);
        $this->set('t', $t);
        $this->set('timepro', $this->Clientrate->find_timeprofile1());
        $show = empty($_GET['search']['show']) ? 'html' : $_GET['search']['show'];
        //print_r($_GET);exit;
        $rate_list = $this->Clientrate->find_all_rate($table_id, $this->_order_condtions(array('code', 'code_name', 'rate', 'setup_fee', 'effective_date', 'end_date')), array(), $show);
        //var_dump($rate_list->dataArray);exit;
        if ($show == 'csv')
        {
            Configure::write('debug', 0);
            $this->layout = 'csv';
            $csv_name = "rate_table" . date("YmdHis") . "_" . rand(0, 99) . ".csv";
            header("Content-type: application/octet-stream;charset=utf8");
            header("Accept-Ranges: bytes");
            header("Content-Disposition: attachment; filename=" . $csv_name);
            if (!empty($rate_list->dataArray))
            {
                $rate_arr = $rate_list->dataArray;
                echo implode(",", array_keys($rate_arr[0][0])), "\n";
                foreach ($rate_arr as $k => $v)
                {
                    echo implode(",", $v[0]), "\n";
                }
            }
        }
        $this->set('jur_type', $list[0][0]['jur_type']);
        $this->set('p', $rate_list);
        $this->set('name', $this->select_client_name($table_id));
        if ($npa == 'npan')
        {
            $this->render('view2');
        }
    }

    public function validate_rate()
    {
        $flag = 'true';
        $tmp = (isset($_POST ['rates'])) ? $_POST ['rates'] : '';
        $size = count($tmp);
        foreach ($tmp as $el)
        {
            
        }
        return $flag;
    }

    /**
     * 
     * # previously
     * effective date
     * end date
     * 
     * 
     */
    public function pre_deal_time()
    {

        if (isset($_POST ['rates']))
        {
            $tmp = (isset($_POST ['rates'])) ? $_POST ['rates'] : '';
            $size = count($tmp);

            if ($size > 0)
            {

                foreach ($tmp as $key => $value)
                {
                    $_POST ['rates'][$key]['effective_date'] = $this->time_tools($tmp[$key]['effective_date'], date("Y-m-d"), '00:00:00');

                    if (!empty($_POST ['rates'][$key]['end_date']))
                    {
                        $_POST ['rates'][$key]['end_date'] = $this->time_tools($tmp[$key]['end_date'], date("Y-m-d"), '23:59:59');
                    }
                }
            }
        }
    }

    public function time_tools($date_str, $default_date, $deault_time)
    {
        $arr = explode(" ", $date_str);


        $arr = filter_empty_value($arr);

        if (isset($arr[0]))
        {
            $date = $arr[0];
        } else
        {
            $date = $default_date;
        }


        if (isset($arr[1]))
        {
            $time = $arr[1];
        } else
        {
            $time = $deault_time;
        }


        if (!exchange_isDate($date))
        {

            $date = $default_date;
        }

        if (empty($time))
        {

            $time = $deault_time;
        }

        return $date . ' ' . $time;
    }

    public function test_time()
    {
        $default_start_date = date("Y-m-d");
        $default_end_date = '';
        $default_start_time = '00:00:00';
        $default_end_time = '23:59:59';
        $a = $this->time_tools('2010-02-01    ', $default_start_date, $default_start_time);
        pr($a);
    }

    public function test_cache()
    {
        $sql = "select  count(*) as c  from client ";
        $list = $this->Clientrate->query($sql);
        $list = $this->Clientrate->query($sql);
    }

    function check_restrict($key, $rate_table_id, $data)
    {
        $code_where = !empty($data ['code']) ? " and code='{$data ['code']}'   " : "and( code  is  null  or code='') ";
        $effective_date_where = !empty($data ['effective_date']) ? " and effective_date='{$data ['effective_date']}'  " : "and effective_date  is  null ";

        $zone_where = !empty($data ['zone']) ? " and zone='{$data ['zone']}'  " : "and ( zone  =''  or zone is  null)";
        $time_profile_id_where = !empty($data ['time_profile_id']) ? " and time_profile_id={$data ['time_profile_id']}  " : "and time_profile_id  is  null ";

        $rate_id_where = !empty($data ['rate_id']) ? " and rate_id<>{$data ['rate_id']}  " : "";

        $random_where = "$key=$key";

        $sql = "select  count(*) as c  from rate  where  $random_where and  rate_table_id =$rate_table_id $code_where $effective_date_where  $zone_where  $time_profile_id_where  $rate_id_where";

        $list = $this->Clientrate->query($sql);

        // echo $list[0][0]['c'];exit();

        if (($list[0][0]['c']))
        {

            return false;
        } else
        {

            return true;
        }
    }

    public function add_rate()
    {
        $this->autoLayout = false;
        $this->autoRender = false;
        $jm = bin2hex(gzcompress("page={$_GET['page']}&size={$_GET['size']}", 9));
        $this->pre_deal_time();


        if (empty($_POST['id']))
        {
            $this->redirect('/rates/rates_list/');
        }
        $rate_table_id = $_POST['id'];


        $delete_rate_id = $_POST['delete_rate_id'];
        $delete_rate_id = substr($delete_rate_id, 1);
        if (!empty($delete_rate_id))
        {
            $this->Clientrate->query("delete  from  rate where rate_id in($delete_rate_id)");
        }



        if ($this->validate_rate() == 'false')
        {
            $this->Session->write("m", Clientrate::set_validator());
            $this->redirect("/clientrates/view/$rate_table_id?qs={$jm}");
        };

        //$allow_code_results = $this->Clientrate->checkCodedeck($rate_table_id);


        $tmp = (isset($_POST ['rates'])) ? $_POST ['rates'] : '';

        //$this->Clientrate->query("begin");
        $size = count($tmp);
        $update_enddate_sql = "";
        foreach ($tmp as $key => $el)
        {
            $this->data['Clientrate'] = $el;
            $this->data['Clientrate']['rate_table_id'] = $rate_table_id;
            $flag = $this->check_restrict($key, $rate_table_id, $el);
            if (!$flag)
            {
                $this->Clientrate->create_json_array('#ClientOrigRateTableId', 101, __('duplicated', true));
                $this->Session->write("m", Clientrate::set_validator());
                $this->redirect("/clientrates/view/$rate_table_id?qs={$jm}");
            }

            $flag = $this->valid($this->data['Clientrate']['code'], $this->data['Clientrate']['effective_date'], $this->data['Clientrate']['effective_date_timezone'], $this->data['Clientrate']['time_profile_id'], $rate_table_id, $this->data['Clientrate']['rate_id']);

            if ($flag)
            {
                //$this->Clientrate->query("rollback");
                $this->Clientrate->create_json_array('#ClientOrigRateTableId', 101, $this->data['Clientrate']['code'] . " is duplicated");
                $this->Session->write("m", Clientrate::set_validator());
                $this->redirect("/clientrates/view/$rate_table_id?qs={$jm}");
            }
            /*

              $flag_array = array();

              foreach($allow_code_results as $allow_code) {
              array_push($flag_array,
              preg_match("/^{$allow_code[0]['code']}/", $el['code']));
              }

              if(array_sum($flag_array) == 0 && count($allow_code_results) > 0) {
              $this->Clientrate->create_json_array('',101,'Code does not match the Code deck under the Code prefix!');
              $this->Session->write("m",Clientrate::set_validator ());
              $this->redirect ( "/clientrates/view/$rate_table_id?qs={$jm}" );
              }
             * 
             */

            $code_name = $el['code_name'];
            $end_date_post = date("Y-m-d H:i:sO", strtotime($el['effective_date'] . $el['effective_date_timezone']) - 1);
            $update_enddate_sql .= "UPDATE rate SET end_date = '{$end_date_post}' WHERE  end_date is null AND rate_table_id = {$rate_table_id}
                            AND code = '{$el['code']}' AND effective_date::timestamp with time zone < timestamp with time zone '{$end_date_post}';";

            if (!empty($this->data ['Clientrate']['effective_date_timezone']) && !empty($this->data ['Clientrate']['effective_date']))
                $this->data ['Clientrate']['effective_date'] = $this->data ['Clientrate']['effective_date'] . $this->data ['Clientrate']['effective_date_timezone'];
            if (!empty($this->data ['Clientrate']['end_date_timezone']) && !empty($this->data ['Clientrate']['end_date']))
                $this->data ['Clientrate']['end_date'] = $this->data ['Clientrate']['end_date'] . $this->data ['Clientrate']['end_date_timezone'];


            $this->Clientrate->save($this->data ['Clientrate']);
            $this->data['Clientrate']['rate_id'] = false;
        }
        $this->Clientrate->query($update_enddate_sql);
        //$this->Clientrate->query("commit");
        $update_at = date("Y-m-d H:i:s");
        $update_by = $_SESSION['sst_user_name'];
        $this->Clientrate->query("update rate_table set update_at = '{$update_at}', update_by = '{$update_by}' WHERE rate_table_id = {$rate_table_id}");
        $this->Clientrate->create_json_array('#ClientOrigRateTableId', 201, __('Succeeded',true));
        $this->Session->write("m", Clientrate::set_validator());
        $this->redirect("/clientrates/view/$rate_table_id?qs={$jm}");
    }

    public function valid($code, $effective_date, $effective_timezone, $time_profile_id, $rate_table_id, $rate_id)
    {
        
        return false;
        
        $effective = strtotime($effective_date . $effective_timezone);
        
        $sql = "SELECT effective_date, time_profile_id FROM rate WHERE code = '{$code}' AND rate_table_id = {$rate_table_id}";
        if (!empty($rate_id))
            $sql .= " AND rate_id != {$rate_id}";
        
        $data = $this->Clientrate->query($sql);
        foreach ($data as $item)
        {
            $item_effective = strtotime($item[0]['effective_date']);
            if ($effective == $item_effective)
            {
                $flag = $effective == $item_effective;
                if (empty($time_profile_id))
                {
                    if ($flag)
                        return true;
                } else {
                    if ($time_profile_id == $item[0]['time_profile_id'])
                        return true;
                }
            }
        }
        return false;
    }

    public function isMixTimeProfile($type1, $start_time1, $end_time1, $start_week1, $end_week1, $type2, $start_time2, $end_time2, $start_week2, $end_week2)
    {
        if ($type1 == 0 && $type2 == 0)
        {
            return true;
        }
        
        if (($type1 == 1 && $type2 == 2) || ($type1 == 2 && $type2 == 1))
        {
            return true;
        }
        
        
        if ($type1 == 2)
        {
            if ($this->isMixTime($start_time1, $end_time1, $start_time2, $end_time2))
            {
                return true;
            }
        } elseif ($type1 == 1) {
            $start_week_1 = max($start_week1, $end_week1);
            $end_week_1 = min($start_week1, $end_week1);
            $start_week_2 = max($start_week2, $end_week2);
            $end_week_2 = min($start_week2, $end_week2);
            if ($this->isMixTime($start_week_1, $end_week_1, $start_week_2, $end_week_2))
            {
                return true;
            }
        }
        return false;
    }

    public function isMixTime($begintime1, $endtime1, $begintime2, $endtime2)
    {
        $status = $begintime2 - $begintime1;
        if ($status > 0)
        {
            $status2 = $begintime2 - $endtime1;
            if ($status2 > 0)
            {
                return false;
            } else
            {
                return true;
            }
        } else
        {
            $status2 = $begintime1 - $endtime2;
            if ($status2 > 0)
            {
                return false;
            } else
            {
                return true;
            }
        }
    }

    public function mass_code_name_insert($code_name, $data)
    {
        $list = $this->Clientrate->query(" select code from  code  where  name='{$code_name}';");
        $count = count($list);
        if (!empty($list) && $count > 0)
        {
            for ($index = 0; $index < $count; $index++)
            {
                
            }
        }
    }

    /**
     * 批量更新
     */
    public function massEdit($rate_table_id)
    {
        $action = empty($_REQUEST['action']) ? '' : $_REQUEST['action'];
        if ($action == 'delete')
        {
            if (!empty($_REQUEST['rate_ids']))
            {
                $this->mass_delete($rate_table_id, $_REQUEST['rate_ids']);
            } else
            {
                //$this->mass_delete($rate_table_id);
            }
        }
        if ($action == 'insert')
        {
            if (!empty($_REQUEST['rate_ids']))
            {
                $this->mass_insert($rate_table_id, $_REQUEST['rate_ids']);
                /* $ids = explode(",", $_REQUEST['rate_ids']);
                  foreach ($ids as $k=>$v)
                  {
                  $this->mass_insert($rate_table_id, intval($v));
                  } */
            }
        }
        if ($action == 'update')
        {
            if (!empty($_REQUEST['rate_ids']))
            {
                $ids = explode(",", $_REQUEST['rate_ids']);
                foreach ($ids as $k => $v)
                {
                    $this->mass_update($rate_table_id, intval($v));
                }
            } else
            {
                //$this->mass_update($rate_table_id);
            }
        }
        if ($action == 'updateall')
        {
            $this->mass_update_all($rate_table_id, $_REQUEST['searchstr']);
        }
        $update_at = date("Y-m-d H:i:s");
        $update_by = $_SESSION['sst_user_name'];
        $this->Clientrate->query("update rate_table set update_at = '{$update_at}', update_by = '{$update_by}' WHERE rate_table_id = {$rate_table_id}");
        return "/clientrates/view/$rate_table_id?page={$this->params['pass'][2]}&size={$this->params['pass'][3]}";
    }

    function mass_insert($table_id, $ids = null)
    {
        $data = array();
        $f = $_REQUEST;
        $data['Clientrate']['rate_table_id'] = $table_id;
        $rate = $f['rate_per_min_action']; //费率
        $mintime = $f['min_time_action']; //最小时长
        $starttime = $f['effective_from_action']; //开始时间
        $setupfee = $f['pay_setup_action']; //一分钟的费用
        $interval = $f['pay_interval_action']; //计费周期
        $endtime = $f['end_date_action']; //结束时间
        $gracetime = $f['grace_time_action']; //赠送时长
        $time_profile = $f['id_time_profiles_action'];
        $seconds = $f['notes_action'];
        $inter_rate = $f['inter_rate_action'];
        $intra_rate = $f['intra_rate_action'];
        $local_rate = $f['local_rate_action'];


        //----------------------------------
        if ($time_profile != 'none' && !empty($f['id_time_profiles_value']))
        {
            $data['Clientrate']['time_profile_id'] = $f['id_time_profiles_value'];
        }
        if ($seconds != 'none')
        {
            $data['Clientrate']['seconds'] = $f['seconds_value'];
        }
        if ($inter_rate != 'none')
        {
            $data['Clientrate']['inter_rate'] = $f['inter_rate_value'];
        }
        if ($intra_rate != 'none')
        {
            $data['Clientrate']['intra_rate'] = $f['intra_rate_value'];
        }
        if ($local_rate != 'none')
        {
            $data['Clientrate']['local_rate'] = $f['local_rate_value'];
        }
        #rate
        if ($rate != 'none')
        {
            $rate_v = $f['rate_per_min_value'];
            $data['Clientrate']['rate'] = $rate_v;
//					//设置为该提交的值
//					if ($rate == 'set'){$data['Clientrate']['rate']=$rate_v;}
//					//在基础上加
//					else if ($rate == 'inc'){$data['Clientrate']['rate'] = "rate + $rate_v";}
//					//在基础上减
//					else if ($rate == 'dec'){$data['Clientrate']['rate'] = "rate - $rate_v";}
//					//按百分比加
//					else if ($rate == 'perin'){$data['Clientrate']['rate'] = "rate +(rate*$rate_v/100)";}
//					//按百分比减
//					else if ($rate == 'perde'){$data['Clientrate']['rate'] = "rate -(rate*$rate_v/100)";}
//					
//					else if ($rate == 'mul'){$data['Clientrate']['rate'] = "rate*$rate_v";}
        }
        #min_time 首次时长
        if ($mintime != 'none')
        {
            $mintime_v = $f['min_time_value'];
            $data['Clientrate']['min_time'] = $mintime_v;
            //设置为该提交的值
//					if ($mintime == 'set'){$sql .= ",min_time=$mintime_v";$sql_select.=" ,$mintime_v as rate";}
//					//在基础上加
//					else if ($mintime == 'inc'){$sql .= ",min_time=min_time+$mintime_v";$sql_select.=" ,min_time+$mintime_v as min_time";}
//					//在基础上减
//					else if ($mintime == 'dec'){$sql .= ",min_time=min_time-$mintime_v";$sql_select.=" ,min_time-$mintime_v as min_time";}
        }

#开始时间
        if ($starttime != 'none')
        {
            if (!empty($f['effective_from_value']))
                $data['Clientrate']['effective_date'] = "'" . $f['effective_from_value'] . "'";
        }

        if ($setupfee != 'none')
        {
            $setupfee_v = $f['pay_setup_value'];
            $data['Clientrate']['setup_fee'] = $setupfee_v;
            //设置为该提交的值
//							if ($setupfee == 'set'){$sql .= ",setup_fee=$setupfee_v";$sql_select.=" ,$setupfee_v as setup_fee";}
//							//在基础上加
//							else if ($setupfee == 'inc'){$sql .= ",setup_fee=setup_fee+$setupfee_v";$sql_select.=" ,setup_fee+$setupfee_v as setup_fee";}
//							//在基础上减
//							else if ($setupfee == 'dec'){$sql .= ",setup_fee=setup_fee-$setupfee_v";$sql_select.=" ,setup_fee-$setupfee_v as setup_fee";}
//							//按百分比加
//							else if ($setupfee == 'perin'){$sql .= ",setup_fee = setup_fee +(setup_fee*$setupfee_v/100)";$sql_select.=" ,setup_fee+(setup_fee*$setupfee_v/100) as setup_fee";}
//							//按百分比加
//							else if ($setupfee == 'perde'){$sql .= ",setup_fee = setup_fee -(setup_fee*$setupfee_v/100)";$sql_select.=" ,setup_fee-(setup_fee*$setupfee_v/100) as setup_fee";}
//				
        }

        if ($interval != 'none')
        {
            $data['Clientrate']['interval'] = $f['pay_interval_value'];
        }

        if ($endtime != 'none')
        {
            $data['Clientrate']['end_date'] = "'" . $f['end_date_value'] . "'";
        }

        if ($gracetime != 'none')
        {
            $data['Clientrate']['grace_time'] = $f['grace_time_value'];
        }
        //------------------------------

        $ids_arr = explode(",", $ids);
        foreach ($ids_arr as $k => $v)
        {
            $rate_old_info = $this->Clientrate->query("select * from rate where rate_id = " . intval($v));
            $data['Clientrate']['code'] = "'" . $rate_old_info[0][0]['code'] . "'";
            $data['Clientrate']['code_name'] = "'" . $rate_old_info[0][0]['code_name'] . "'";
            $data['Clientrate']['country'] = "'" . $rate_old_info[0][0]['country'] . "'";
            //$this->data['Clientrate'] = $data['Clientrate'];
            //$return = $this->Clientrate->save( $this->data['Clientrate']);
            if (strstr($rate_old_info[0][0]['effective_date'], '+', TRUE) == $f['effective_from_value'])
            {
                $this->Session->write('m', $this->Clientrate->create_json(101, __('The effective date can not be the same!', true)));
                $this->xredirect("/clientrates/view/" . $table_id);
            }
            $sql_in = implode(",", array_keys($data['Clientrate']));
            $sql_value = implode(",", $data['Clientrate']);
            $end_date_new = date("Y-m-d H:i:s", strtotime($f['effective_from_value']) - 1);
            $this->Clientrate->query("update rate set end_date = '{$end_date_new}' where rate_id = {$v}");
            $return = $this->Clientrate->query("insert into rate ({$sql_in}) values ({$sql_value})");
        }
        $update_at = date("Y-m-d H:i:s");
        $update_by = $_SESSION['sst_user_name'];
        $this->Clientrate->query("update rate_table set update_at = '{$update_at}', update_by = '{$update_by}' WHERE rate_table_id = {$rate_table_id}");
        return $return;
    }

    /**
     * 
     * 批量更新
     * @param $table_id
     */
    public function mass_update($table_id, $id = null)
    {
        $data = array();
        $f = $_POST;
        $type = $f['stage']; //执行方式
        $rate = $f['rate_per_min_action']; //费率
        $mintime = $f['min_time_action']; //最小时长
        $starttime = $f['effective_from_action']; //开始时间
        $setupfee = $f['pay_setup_action']; //一分钟的费用
        $interval = $f['pay_interval_action']; //计费周期
        $endtime = $f['end_date_action']; //结束时间
        $gracetime = $f['grace_time_action']; //赠送时长
        $time_profile = $f['id_time_profiles_action'];
        $seconds = $f['notes_action'];
        $inter_rate = $f['inter_rate_action'];
        $intra_rate = $f['intra_rate_action'];
        $local_rate = $f['local_rate_action'];
        $sql = "update rate set rate_id = rate_id";

        $sql_select = "select rate_id,code,rate_table_id,code_name";
        if ($time_profile != 'none' && !empty($f['id_time_profiles_value']))
        {
            $sql .= ",time_profile_id={$f['id_time_profiles_value']}";
            $sql_select .= ",(select name from time_profile where time_profile_id = {$f['id_time_profiles_value']}) as tf";
        } else
        {
            $sql_select .= ",(select name from time_profile where time_profile_id = rate.time_profile_id) as tf";
        }
        if ($seconds != 'none')
        {
            $sql .= ",seconds={$f['seconds_value']}";
            $sql_select.=" ,{$f['seconds_value']} as seconds";
        } else
        {
            $sql_select .= ",seconds";
        }
        if ($inter_rate != 'none')
        {
            $sql .= ",inter_rate={$f['inter_rate_value']}";
            $sql_select.=" ,{$f['inter_rate_value']} as inter_rate";
        } else
        {
            $sql_select .= ",inter_rate";
        }
        if ($intra_rate != 'none')
        {
            $sql .= ",intra_rate={$f['intra_rate_value']}";
            $sql_select.=" ,{$f['intra_rate_value']} as intra_rate";
        } else
        {
            $sql_select .= ",intra_rate";
        }
        if ($local_rate != 'none')
        {
            $sql .= ",local_rate={$f['local_rate_value']}";
            $sql_select.=" ,{$f['local_rate_value']} as local_rate";
        } else
        {
            $sql_select .= ",local_rate";
        }
        #rate
        if ($rate != 'none')
        {
            $rate_v = $f['rate_per_min_value'];
            //设置为该提交的值
            if ($rate == 'set')
            {
                $sql .= ",rate=$rate_v";
                $sql_select.=" ,$rate_v as rate";
            }
            //在基础上加
            else if ($rate == 'inc')
            {
                $sql .= ",rate = rate + $rate_v";
                $sql_select.=" ,rate+$rate_v as rate";
            }
            //在基础上减
            else if ($rate == 'dec')
            {
                $sql .= ",rate = rate - $rate_v";
                $sql_select.=" ,rate-$rate_v as rate";
            }
            //按百分比加
            else if ($rate == 'perin')
            {
                $sql .= ",rate = rate +(rate*$rate_v/100)";
                $sql_select.=" ,rate+(rate*$rate_v/100) as rate";
            }
            //按百分比减
            else if ($rate == 'perde')
            {
                $sql .= ",rate = rate -(rate*$rate_v/100)";
                $sql_select.=" ,rate-(rate*$rate_v/100) as rate";
            } else if ($rate == 'mul')
            {
                $sql .= ",rate = rate*$rate_v";
                $sql_select.=" ,rate*$rate_v as rate";
            }
        } else
        {
            $sql_select .= ",rate";
        }
        #min_time 首次时长
        if ($mintime != 'none')
        {
            $mintime_v = $f['min_time_value'];
            //设置为该提交的值
            if ($mintime == 'set')
            {
                $sql .= ",min_time=$mintime_v";
                $sql_select.=" ,$mintime_v as rate";
            }
            //在基础上加
            else if ($mintime == 'inc')
            {
                $sql .= ",min_time=min_time+$mintime_v";
                $sql_select.=" ,min_time+$mintime_v as min_time";
            }
            //在基础上减
            else if ($mintime == 'dec')
            {
                $sql .= ",min_time=min_time-$mintime_v";
                $sql_select.=" ,min_time-$mintime_v as min_time";
            }
        } else
        {
            $sql_select .= ",min_time";
        }

#开始时间
        if ($starttime != 'none')
        {
            if (!empty($f['effective_from_value']))
            {
                $sql .= ",effective_date='{$f['effective_from_value']}'";
                $sql_select.=" ,'{$f['effective_from_value']}' as effective_date";
            } else
            {
                $sql_select .= ",effective_date";
            }
        } else
        {
            $sql_select .= ",effective_date";
        }

        if ($setupfee != 'none')
        {
            $setupfee_v = $f['pay_setup_value'];
            //设置为该提交的值
            if ($setupfee == 'set')
            {
                $sql .= ",setup_fee=$setupfee_v";
                $sql_select.=" ,$setupfee_v as setup_fee";
            }
            //在基础上加
            else if ($setupfee == 'inc')
            {
                $sql .= ",setup_fee=setup_fee+$setupfee_v";
                $sql_select.=" ,setup_fee+$setupfee_v as setup_fee";
            }
            //在基础上减
            else if ($setupfee == 'dec')
            {
                $sql .= ",setup_fee=setup_fee-$setupfee_v";
                $sql_select.=" ,setup_fee-$setupfee_v as setup_fee";
            }
            //按百分比加
            else if ($setupfee == 'perin')
            {
                $sql .= ",setup_fee = setup_fee +(setup_fee*$setupfee_v/100)";
                $sql_select.=" ,setup_fee+(setup_fee*$setupfee_v/100) as setup_fee";
            }
            //按百分比加
            else if ($setupfee == 'perde')
            {
                $sql .= ",setup_fee = setup_fee -(setup_fee*$setupfee_v/100)";
                $sql_select.=" ,setup_fee-(setup_fee*$setupfee_v/100) as setup_fee";
            }
        } else
        {
            $sql_select .= ",setup_fee";
        }

        if ($interval != 'none')
        {
            $sql .= ",interval={$f['pay_interval_value']}";
            $sql_select.=" ,{$f['pay_interval_value']} as interval";
        } else
        {
            $sql_select .= ",interval";
        }

        if ($endtime != 'none')
        {
            $sql .= ",end_date='{$f['end_date_value']}'";
            $sql_select.=" ,{$f['pay_interval_value']} as end_date";
        } else
        {
            $sql_select .= ",end_date";
        }

        if ($gracetime != 'none')
        {
            $sql .= ",grace_time={$f['grace_time_value']}";
            $sql_select.=" ,{$f['grace_time_value']} as grace_time";
        } else
        {
            $sql_select .= ",grace_time";
        }
        if ($type == 'process')
        {//应用
            $sql .= " where rate_table_id =$table_id";

            $sql = str_replace('rate_id = rate_id,', '', $sql);
            if (!empty($id))
            {
                $sql .= " and rate_id = $id";
            }

            $qs = $this->Clientrate->query($sql);
            if (count($qs) == 0)
            {
                $this->Clientrate->create_json_array('', 201, __('Succeeded', true));
            } else
            {
                $this->Clientrate->create_json_array('', 101, __('Failed', true));
            }
            $this->set('m', Clientrate::set_validator());
        } else
        {//预览
            $this->set('previewForm', $f);
            $this->set('previewRates', $this->Clientrate->query($sql_select . " from rate where rate_table_id =$table_id"));
        }
    }

    public function mass_update_all($table_id, $searchstr)
    {
        $data = array();
        $f = $_POST;
        $type = $f['stage']; //执行方式
        $rate = $f['rate_per_min_action']; //费率
        $mintime = $f['min_time_action']; //最小时长
        $starttime = $f['effective_from_action']; //开始时间
        $setupfee = $f['pay_setup_action']; //一分钟的费用
        $interval = $f['pay_interval_action']; //计费周期
        $endtime = $f['end_date_action']; //结束时间
        $gracetime = $f['grace_time_action']; //赠送时长
        $time_profile = $f['id_time_profiles_action'];
        $seconds = $f['notes_action'];
        $inter_rate = $f['inter_rate_action'];
        $intra_rate = $f['intra_rate_action'];
        $local_rate = $f['local_rate_action'];
        $sql = "update rate set rate_id = rate_id";

        $sql_select = "select rate_id,code,rate_table_id,code_name";
        if ($time_profile != 'none' && !empty($f['id_time_profiles_value']))
        {
            $sql .= ",time_profile_id={$f['id_time_profiles_value']}";
            $sql_select .= ",(select name from time_profile where time_profile_id = {$f['id_time_profiles_value']}) as tf";
        } else
        {
            $sql_select .= ",(select name from time_profile where time_profile_id = rate.time_profile_id) as tf";
        }
        if ($seconds != 'none')
        {
            $sql .= ",seconds={$f['seconds_value']}";
            $sql_select.=" ,{$f['seconds_value']} as seconds";
        } else
        {
            $sql_select .= ",seconds";
        }
        if ($inter_rate != 'none')
        {
            $sql .= ",inter_rate={$f['inter_rate_value']}";
            $sql_select.=" ,{$f['inter_rate_value']} as inter_rate";
        } else
        {
            $sql_select .= ",inter_rate";
        }
        if ($intra_rate != 'none')
        {
            $sql .= ",intra_rate={$f['intra_rate_value']}";
            $sql_select.=" ,{$f['intra_rate_value']} as intra_rate";
        } else
        {
            $sql_select .= ",intra_rate";
        }
        if ($local_rate != 'none')
        {
            $sql .= ",local_rate={$f['local_rate_value']}";
            $sql_select.=" ,{$f['local_rate_value']} as local_rate";
        } else
        {
            $sql_select .= ",local_rate";
        }
        #rate
        if ($rate != 'none')
        {
            $rate_v = $f['rate_per_min_value'];
            //设置为该提交的值
            if ($rate == 'set')
            {
                $sql .= ",rate=$rate_v";
                $sql_select.=" ,$rate_v as rate";
            }
            //在基础上加
            else if ($rate == 'inc')
            {
                $sql .= ",rate = rate + $rate_v";
                $sql_select.=" ,rate+$rate_v as rate";
            }
            //在基础上减
            else if ($rate == 'dec')
            {
                $sql .= ",rate = rate - $rate_v";
                $sql_select.=" ,rate-$rate_v as rate";
            }
            //按百分比加
            else if ($rate == 'perin')
            {
                $sql .= ",rate = rate +(rate*$rate_v/100)";
                $sql_select.=" ,rate+(rate*$rate_v/100) as rate";
            }
            //按百分比减
            else if ($rate == 'perde')
            {
                $sql .= ",rate = rate -(rate*$rate_v/100)";
                $sql_select.=" ,rate-(rate*$rate_v/100) as rate";
            } else if ($rate == 'mul')
            {
                $sql .= ",rate = rate*$rate_v";
                $sql_select.=" ,rate*$rate_v as rate";
            }
        } else
        {
            $sql_select .= ",rate";
        }
        #min_time 首次时长
        if ($mintime != 'none')
        {
            $mintime_v = $f['min_time_value'];
            //设置为该提交的值
            if ($mintime == 'set')
            {
                $sql .= ",min_time=$mintime_v";
                $sql_select.=" ,$mintime_v as rate";
            }
            //在基础上加
            else if ($mintime == 'inc')
            {
                $sql .= ",min_time=min_time+$mintime_v";
                $sql_select.=" ,min_time+$mintime_v as min_time";
            }
            //在基础上减
            else if ($mintime == 'dec')
            {
                $sql .= ",min_time=min_time-$mintime_v";
                $sql_select.=" ,min_time-$mintime_v as min_time";
            }
        } else
        {
            $sql_select .= ",min_time";
        }

#开始时间
        if ($starttime != 'none')
        {
            if (!empty($f['effective_from_value']))
            {
                $sql .= ",effective_date='{$f['effective_from_value']}'";
                $sql_select.=" ,'{$f['effective_from_value']}' as effective_date";
            } else
            {
                $sql_select .= ",effective_date";
            }
        } else
        {
            $sql_select .= ",effective_date";
        }

        if ($setupfee != 'none')
        {
            $setupfee_v = $f['pay_setup_value'];
            //设置为该提交的值
            if ($setupfee == 'set')
            {
                $sql .= ",setup_fee=$setupfee_v";
                $sql_select.=" ,$setupfee_v as setup_fee";
            }
            //在基础上加
            else if ($setupfee == 'inc')
            {
                $sql .= ",setup_fee=setup_fee+$setupfee_v";
                $sql_select.=" ,setup_fee+$setupfee_v as setup_fee";
            }
            //在基础上减
            else if ($setupfee == 'dec')
            {
                $sql .= ",setup_fee=setup_fee-$setupfee_v";
                $sql_select.=" ,setup_fee-$setupfee_v as setup_fee";
            }
            //按百分比加
            else if ($setupfee == 'perin')
            {
                $sql .= ",setup_fee = setup_fee +(setup_fee*$setupfee_v/100)";
                $sql_select.=" ,setup_fee+(setup_fee*$setupfee_v/100) as setup_fee";
            }
            //按百分比加
            else if ($setupfee == 'perde')
            {
                $sql .= ",setup_fee = setup_fee -(setup_fee*$setupfee_v/100)";
                $sql_select.=" ,setup_fee-(setup_fee*$setupfee_v/100) as setup_fee";
            }
        } else
        {
            $sql_select .= ",setup_fee";
        }

        if ($interval != 'none')
        {
            $sql .= ",interval={$f['pay_interval_value']}";
            $sql_select.=" ,{$f['pay_interval_value']} as interval";
        } else
        {
            $sql_select .= ",interval";
        }

        if ($endtime != 'none')
        {
            $sql .= ",end_date='{$f['end_date_value']}'";
            $sql_select.=" ,{$f['pay_interval_value']} as end_date";
        } else
        {
            $sql_select .= ",end_date";
        }

        if ($gracetime != 'none')
        {
            $sql .= ",grace_time={$f['grace_time_value']}";
            $sql_select.=" ,{$f['grace_time_value']} as grace_time";
        } else
        {
            $sql_select .= ",grace_time";
        }
        if ($type == 'process')
        {//应用
            //$sql .= " where rate_table_id =$table_id";
            $sql .= " where rate_table_id =$table_id";
            if ($_POST['isQuery'])
            {
                if (!$_POST['isAll'])
                {
                    $sql .= " and end_date is null";
                }
            }

            if (!empty($searchstr))
            {
                $sql .= " and rate.code::text ilike '{$searchstr}%' or rate.code_name::text ilike '{$searchstr}%' or rate.country::text ilike '{$searchstr}%'";
            }
            $sql = str_replace('rate_id = rate_id,', '', $sql);
            $qs = $this->Clientrate->query($sql);
            if (count($qs) == 0)
            {
                $this->Clientrate->create_json_array('', 201, __('Succeeded', true));
            } else
            {
                $this->Clientrate->create_json_array('', 101, __('failed', true));
            }
            $this->set('m', Clientrate::set_validator());
        } else
        {//预览
            $this->set('previewForm', $f);
            $this->set('previewRates', $this->Clientrate->query($sql_select . " from rate where rate_table_id =$table_id"));
        }
    }

    function _get_update_all_trunk_set($i, $data)
    {
        $trunk = $data['trunk' . $i];
        return "resource_id_{$i} ={$trunk}";
    }

    function _get_update_all_percentage_set($i, $data)
    {
        $percentage = $data['percentage' . $i];
        return "percentage=$percentage";
    }

    function _render_update_all_update($product_id, $type)
    {
        $this->Clientrate->begin();
        $data = $this->_get('data');
        $sets = Array();
        if ($this->_get('route_strategy_options') != 'none')
        {
            $sets[] = 'strategy=' . $this->_get('strategy');
            if ($this->_get('strategy') == '0')
            {
                for ($i = 1; $i < 0; $i++)
                {
                    $this->_get_update_all_percentage_set($i, $data);
                }
            }
        }
        if ($this->_get('route_time_profile_options') != 'none')
        {
            $sets[] = 'time_profile_id=' . $this->_get('time_profile');
        }
        for ($i = 1; $i < 9; $i++)
        {
            if ($this->_get('route_trunk' . $i . '_options') != 'none')
            {
                $sets[] = $this->_get_update_all_trunk_set($i, $data);
            }
        }
        if (!empty($sets))
        {
            $sets = "set " . join($sets, ',');
            $this->Clientrate->query("update product_items $sets where product_id=$product_id");
        }
        if ($type == 'view')
        {
            $currPage = 1;
            $pageSize = 100;
            if (!empty($_REQUEST ['page']))
            {
                $currPage = $_REQUEST ['page'];
            }
            if (!empty($_REQUEST ['size']))
            {
                $pageSize = $_REQUEST ['size'];
            }
            $search = null;
            if (!empty($_REQUEST ['search']))
            {
                $search = $_REQUEST ['search'];
                $this->set('search', $search);
            }
            if (!empty($_REQUEST['edit_id']))
            {
                $sql = "select item_id,alias,digits,strategy,
									(select name from time_profile where time_profile_id = product_items.time_profile_id) as time_profile,
									(select alias from resource where resource_id = product_items.resource_id_1) as route1,
									(select alias from resource where resource_id = product_items.resource_id_2) as route2,
									(select alias from resource where resource_id = product_items.resource_id_3) as route3,
									(select alias from resource where resource_id = product_items.resource_id_4) as route4,
									(select alias from resource where resource_id = product_items.resource_id_5) as route5,
									(select alias from resource where resource_id = product_items.resource_id_6) as route6,
									(select alias from resource where resource_id = product_items.resource_id_7) as route7,
									(select alias from resource where resource_id = product_items.resource_id_8) as route8
								from product_items
								where item_id = {$_REQUEST['edit_id']}
		  			";
            }
            $result = $this->Product->query($sql);
            $this->Session->write('viewListUpdate', $result);
        }
        if ($type == 'view')
        {
            $this->Clientrate->rollback();
        } else
        {
            $this->Clientrate->commit();
        }
    }

    function _render_update_all_delete($product_id)
    {
        $data = $this->_get('data');
        $sets = Array();
        if ($this->_get('route_strategy_options') != 'none')
        {
            $sets[] = 'strategy=' . $this->_get('strategy');
            if ($this->_get('strategy') == '0')
            {
                for ($i = 1; $i < 0; $i++)
                {
                    $this->_get_update_all_percentage_set($i, $data);
                }
            }
        }
        if ($this->_get('route_time_profile_options') != 'none')
        {
            $sets[] = 'time_profile_id=' . $this->_get('time_profile');
        }
        for ($i = 1; $i < 9; $i++)
        {
            if ($this->_get('route_trunk' . $i . '_options') != 'none')
            {
                $sets[] = $this->_get_update_all_trunk_set($i, $data);
            }
        }
        if (!empty($sets))
        {
            $sets = join($sets, ' and ');
            $this->Clientrate->query("delete from product_items where product_id'=>$product_id and $sets");
        }
    }

    function _render_update_all_insert($product_id)
    {
        $this->loadModel('Productitem');
        $this->Productitem->find('all', Array('conditions' => Array('product_id' => $product_id)));
    }

    function updateAll($id = null)
    {
        $type = $this->_get('type');
        $action = $this->_get('action');
        if ($action == 'insert')
        {
            $this->_render_update_all_insert($id);
            $this->Session->write('m', $this->Clientrate->create_json(201, __('Your options are created successfully', true)));
        }
        if ($action == 'update')
        {
            $this->_render_update_all_update($id, $type);
            $this->Session->write('m', $this->Clientrate->create_json(201, __('Your options are modified successfully', true)));
        }
        if ($action == 'delete')
        {
            $this->_render_update_all_delete($id);
            $this->Session->write('m', $this->Clientrate->create_json(201, __('Your options are deleted successfully', true)));
        }
        $this->xredirect("/products/route_info/" . $id);
    }

    function delete_all($rate_table_id)
    {


        if (!empty($rate_table_id))
        {
            $ids = $this->_get('ids');

            $conditions = Array("rate_table_id='$rate_table_id'");
            if (!empty($ids))
            {
                $conditions[] = "rate_id in ($ids)";
                $this->Session->write('m', $this->Clientrate->create_json(201, __('You options are deleted successfully', true)));
            } else {
                $mesg_result = $this->Clientrate->query("select name from rate_table where rate_table_id = {$table_id}");
                $this->Session->write('m', $this->Clientrate->create_json(201, __('The rates of Rate Table [' . $mesg_result[0][0]['name'] .'] are deleted successfully', true)));
            }
            $conditions = join($conditions, ' and ');

            $this->Clientrate->query("delete from rate where $conditions");
        }
        $this->xredirect("/clientrates/view/" . $rate_table_id);
    }

    function rate_delete($id = null)
    {
        if (!empty($id))
        {
            $ids = $this->_get('ids');
            $conditions = Array("rate_table_id=$id");
            if (!empty($ids))
            {
                $conditions[] = "rate_id in ($ids)";
            }
            $conditions = join($conditions, ' and ');
            $sql = "delete from rate where $conditions";

            $this->Clientrate->query($sql);
        }
        $this->Session->write('m', $this->Clientrate->create_json(201, __('Succeeded', true)));
        $this->xredirect("/clientrates/view/" . $id);
    }

    public function mass_delete($table_id, $ids = null)
    {
        if (!isset($ids))
        {
            $ids = $_REQUEST['ids'];
        }
        if (!empty($table_id))
        {
            if (empty($ids))
            {
                $mesg_result = $this->Clientrate->query("select name from rate_table where rate_table_id = {$table_id}");
                
                $this->Clientrate->query("delete from  rate  where  rate_table_id=$table_id");
                $this->Session->write('m', $this->Clientrate->create_json(201, __('The rates of Rate Table [' . $mesg_result[0][0]['name'] .'] are deleted successfully', true)));
            } else
            {
                $this->Clientrate->query("delete  from  rate  where  rate_table_id=$table_id and rate_id in ($ids)");
                $this->Session->write('m', $this->Clientrate->create_json(201, __('Your options are deleted succesfully', true)));
            }
        }
        $this->xredirect("/clientrates/view/" . $table_id);
    }

    public function select_client_name($id = null)
    {
        if (!empty($id))
        {
            $sql = "select name from rate_table where rate_table_id=$id";
            return $this->Clientrate->query($sql);
        }
    }
    
    public function change_header($ratetable_id)
    {
        if ($this->RequestHandler->ispost())
        {
            
            
            $targetFolder = Configure::read('rateimport.put');
            $rates_filepath = $targetFolder . DIRECTORY_SEPARATOR . trim($_POST['myfile_guid']) . ".csv";
            $end_date = $_POST['end_date'];
            $end_date1 = $_POST['end_date1'];
            
            $tz = $_POST['end_date_tz'];
            $dz1 = $_POST['end_date_tz1'];
            $date_format = $_POST['effective_date_format'];
            $sample_do = $_POST['method'];
            $filename = $_POST['myfile_filename'];
            $with_header = isset($_POST['with_header']);
            $default_value = isset($_POST['default_value']);
            
            $end_effective_date = 'NULL';
           
            if ($sample_do == '2')
            {
                if (!empty($end_date1))
                    $end_date = "-T " . str_replace(" ", "_", $end_date1) . $dz1;
                else
                    $end_date = '';
            } else if ($sample_do == '0') {
                $end_effective_date = "'". $end_date . $tz . "'";
                $end_date = '';
            } else {
                $end_date = '';
            }
            
            
            $binpath = Configure::read('rateimport.bin');
            $confpath = Configure::read('rateimport.conf');
            $confpath_info = pathinfo($confpath);
            $confpath = $confpath_info['dirname'];
            
            $outpath = Configure::read('rateimport.out');

            $rate_table = $this->Clientrate->query("select jur_type, code_deck_id from rate_table where rate_table_id = {$ratetable_id}");
            if ($rate_table[0][0]['jur_type'] == 3 || $rate_table[0][0]['jur_type'] == 4)
            {
                $cmd_parm = '-u 1';
                $is_ocn_lata = 1;
                $rates_filepath_cmd = $targetFolder . DIRECTORY_SEPARATOR . trim($_POST['myfile_guid']) . '_by_ocn_lata' . ".csv";
            }
            else
            {
                $cmd_parm = '-u 0';
                $is_ocn_lata = 0;
                $rates_filepath_cmd = $rates_filepath;
            }
            
            if(empty($rate_table[0][0]['code_deck_id']))
            {
                $cmd_codek = '-C 0';
            }
            else
            {
                $cmd_codek = '-C 1';
            }

            $system_type = Configure::read('system.type');

            $cmd = "{$binpath} $end_date -F '{$filename}' -t $system_type -d {$confpath} -r {$ratetable_id} -c {$date_format} -f '{$rates_filepath_cmd}' -o {$outpath} -m {$sample_do} -U {$_SESSION['sst_user_id']} {$cmd_parm} {$cmd_codek}";
            //$cmd = str_replace("'", "''", $cmd);
            $this->set('cmd', $cmd);
            $this->set('rate_table_id', $ratetable_id);
            $this->set('end_effective_date', $end_effective_date);
            $this->set("is_ocn_lata", $is_ocn_lata);
            $this->set("date_format", $date_format);
            $this->set('rates_file_cmd', $rates_filepath_cmd);
            
            $this->loadModel('RateTable');
            $schema = $this->RateTable->default_schema;
            
            $fields = array_keys($schema);
        
            $abspath = $rates_filepath;
            
            $cmds = array();
            
            array_push($cmds, "'s/\\r/\\n/g'");
            array_push($cmds, "'/^$/d'");
            $cmd_str = implode(' -e ', $cmds);
            $cmd2 = "sed -i -e {$cmd_str} {$abspath}";
            shell_exec($cmd2);
            
            $cmds = array();
            array_push($cmds, "'/^$/d'");
            
            if (!$with_header) {
                // sed 插入第一行插入空行
                //$cmd = "'1i\\\\'";
                $cmd_awk = "awk -F ',' 'NR==1 {print NF}' {$abspath}";
                $awk_result = shell_exec($cmd_awk);
                $line_rows = (int)$awk_result - 1;
                $quote_str = str_repeat(',', $line_rows);
                
                array_push($cmds, "'1i\\{$quote_str}\\'");
                //$cmd = "sed -i '1i\\\\' {$abspath}";
            } 
            if ($default_value) {
                $default_headers = array();
                $default_values = array();
                if (isset($_POST['is_effective_date'])) {
                    array_push($default_headers, 'effective_date');
                    array_push($default_values, $_POST['effetive_date'] . $_POST['effetive_date_timezone']);
                }
                if (isset($_POST['is_min_time'])) {
                    array_push($default_headers, 'min_time');
                    array_push($default_values, $_POST['min_time']);
                }
                if (isset($_POST['is_interval'])) {
                    array_push($default_headers, 'interval');
                    array_push($default_values, $_POST['interval']);
                }
                $default_headers_c = implode(',', $default_headers);
                $default_values_c  = implode(',', $default_values);
                if ($with_header) {
                    // sed 第一行插入 headers
                    //$cmd = "'1s/$/,{$default_headers}/g'";
                    array_push($cmds, "'1s/$/,{$default_headers_c}/g'");
                }
                //$cmd = "'2,\$s/$/BBB/g'";
                array_push($cmds, "'2,\$s/$/,{$default_values_c}/g'");
                
                // sed 插入 values
            }
            
            
            $cmd_str = implode(' -e ', $cmds);
            
            $cmd2 = "sed -i -e {$cmd_str} {$abspath}";
            shell_exec($cmd2);
            
            $table = array();
            $row = 1;

            $handle = popen("head -n 21 {$abspath}", "r");

            while ($row <= 21 && $data = fgetcsv($handle, 1000, ","))
            {
                $row++;
                array_push($table, $data);
            }

            pclose($handle);
            $this->set('table', $table);
            $this->set('columns', $fields);
            $this->set('abspath', $abspath);
        }
    }

    public function import($ratetable_id)
    {
        $this->set('name', $this->select_client_name($ratetable_id));
        Configure::load('myconf');
        if ($this->RequestHandler->ispost())
        {
                $abspath = $_POST['abspath'];
                $new_columns = $_POST['columns'];
                $new_columns_str = implode(',', $new_columns);
                $cmd_ = "sed -i '1s/.*/{$new_columns_str}/g' {$abspath}";
                shell_exec($cmd_);
                
                $cmd = $_POST['cmd'];
                $sql = "insert into import_rate_status(rate_table_id, status) values ({$ratetable_id}, -1) returning id";
                $log_result = $this->Clientrate->query($sql);
                $log_id = $log_result[0][0]['id'];
                $cmd .= " -I {$log_id}";
                
                $end_effective_date = $_POST['end_effective_date'];
                $script_path = Configure::read('script.path');
                $script_conf = Configure::read('script.conf');
                $cmd = addslashes($cmd);
                
                $is_ocn_lata = $_POST['is_ocn_lata'];
                $date_format = $_POST['date_format'];
                $rates_file_cmd = $_POST['rates_file_cmd'];
                
                $sql = "insert into rate_upload_queue(cmd, rate_table_id, status, end_date, log_id, is_ocn_lata, date_format, rates_file_cmd, rates_file) values ('{$cmd}', $ratetable_id, 0, {$end_effective_date}, {$log_id}, {$is_ocn_lata}, '{$date_format}', '{$rates_file_cmd}', '{$abspath}')";
                $this->Clientrate->query($sql);
                $sql = "select count(*) from rate_upload_queue where rate_table_id = {$ratetable_id}";
                $result = $this->Clientrate->query($sql);
                if (intval($result[0][0]['count']) >= 1)
                {
                    $cmd2 = "perl $script_path/rate_import.pl -c {$script_conf} -r {$ratetable_id} > /dev/null 2>&1 &";
                    echo $cmd2;exit;
                    if (Configure::read('cmd.debug')) {
                        echo $cmd2;exit;
                    }
                    shell_exec($cmd2);
                }
                //$this->redirect('/clientrates/import/'. $ratetable_id);
                //$this->redirect('/rate_log/import');
        }
        $sql = "select jurisdiction_country_id, jur_type, currency_id from rate_table where rate_table_id = {$ratetable_id}";
        $result = $this->Clientrate->query($sql);
        $is_us = $result[0][0]['jurisdiction_country_id'];
        $jur_type = $result[0][0]['jur_type'];
        if ($jur_type == 0 || $jur_type == 1)
        {
            $example = $this->webroot . "example" . DS . 'example1.csv';
        } elseif ($jur_type == 2)
        {
            $example = $this->webroot . "example" . DS . 'example2.csv';
        } elseif ($jur_type == 3)
        {
            $example = $this->webroot . "example" . DS . 'example4.csv';
        } elseif ($jur_type == 4)
        {
            $example = $this->webroot . "example" . DS . 'example5.csv';
        }
        $this->set('example', $example);
        $this->set('ratetable_id', $ratetable_id);
        $this->set('table_id', $ratetable_id);
        $this->set('jur_type', $jur_type);
        $this->set('currency', $result[0][0]['currency_id']);
    }

    public function checkstatus()
    {
        Configure::write('debug', 0);
        $this->autoLayout = false;
        $this->autoRender = false;
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . "GMT");
        header("Cache-Control: no-cache, must-revalidate");
        header("Pragma: no-cache");
        set_time_limit(10); //请求超时时间  
        $ratetable_id = $_POST['ratetable_id'];
        $status = $this->Clientrate->get_rate_import_status($ratetable_id);
        if (!empty($status) && $status['status'] == 5)
        {
            $this->Clientrate->update_import_status_over($status['id']);
        }
        echo json_encode($status);
        ob_flush();
        flush();
        $this->db->close();
    }

    public function down_import_log($id, $type)
    {
        Configure::write('debug', 0);
        $this->autoLayout = false;
        $this->autoRender = false;
        $path = $this->Clientrate->get_log_file($id, $type);
        header('Content-type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($path) . '"');
        readfile($path);
    }

}

?>
