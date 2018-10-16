<?php
/**
 * 
 * @author weifeng
 * 	Daily Origination Usage Detail Report
 * 	Daily Termination Usage Detail Report 
 *
 */
class UsagedetailsController extends AppController {
	var $name = 'Usagedetails';
	var $uses = array ('Cdr' );
	var $helpers = array ('javascript', 'html');
        function  index()
	{
		$this->redirect('orig_summary_reports');
	}
	//读取该模块的执行和修改权限
	public function beforeFilter(){
		$this->checkSession ( "login_type" );//核查用户身份
		$login_type = $this->Session->read('login_type');
		if (1){//($login_type==1){  
			//admin
			$this->Session->write('executable',true);
			$this->Session->write('writable',true);
		}else{
			$limit = $this->Session->read('sst_retail_rcardpools');
			$this->Session->write('executable',$limit['executable']);
			$this->Session->write('writable',$limit['writable']);
		}
			parent::beforeFilter();
	}

	public  function beforeRender(){
		$_SESSION['time2']=time();
	}
	
	
        
        function search_exist($arr, $v1, $v2) {
            foreach($arr as $key => $val) {
                if(in_array($v1, $val) && in_array($v2, $val)) {
                    
                    return $key;
                }
            } 
            return FALSE;
        }
        
        function search_exist_key($arr, $v1, $v2, $k1, $k2) {
            foreach($arr as $key => $val) {
                //if(in_array($v1, $val) && in_array($v2, $val)) {
                if ($val[$k1] == $v1 and $val[$k2] == $v2) {
                    return $key;
                }
            } 
            return FALSE;
        }
        
        
        function orig_summary_reports() {
            $this->pageTitle="Origination Usage Detail/Spam Report";
            $t = getMicrotime();
            extract($this->Cdr->get_start_end_time());
            $start_date = $start_date;
            $end_date = $end_date;
            $gmt = $tz;
            if(isset($_GET['start_date']) && !empty($_GET['start_date'])) 
            $start_date = $_GET['start_date'] . ' ' . $_GET['start_time'];
            if(isset($_GET['stop_date']) && !empty($_GET['stop_date']))
                $end_date = $_GET['stop_date'] . ' ' . $_GET['stop_time'];
            if(isset($_GET['query']['tz']) && !empty($_GET['query']['tz']))
                $gmt = $_GET['query']['tz'];
            
            $sql = "SELECT is_preload FROM system_parameter LIMIT 1";
            $is_preload_result = $this->Cdr->query($sql);
            $is_preload = $is_preload_result[0][0]['is_preload'];
            $this->set('show_nodata', true);
            if (isset($_GET['show_type']) || $is_preload) {
            $data = $this->Cdr->get_orig_summary_reports($start_date, $end_date, $gmt);
            $result = array();
            foreach($data as $item) {
                $exists_key = $this->search_exist_key($result, $item[0]['ingress_client_id'], $item[0]['ingress_code_name'], 'ingress_client_id', 'orig_code_name');
                if($exists_key !== FALSE) {
                    $result[$exists_key]['total_time'] += $item[0]['total_time'];
                    $result[$exists_key]['calls_30'] += $item[0]['calls_30'];
                    //$result[$exists_key]['time_30'] += $item[0]['time_30'];
                    $result[$exists_key]['calls_6'] += $item[0]['calls_6'];
                    //$result[$exists_key]['time_6'] += $item[0]['time_6'];
                    //$result[$exists_key]['bill_time'] += $item[0]['bill_time'];
                    //$result[$exists_key]['total_calls'] += $item[0]['total_calls'];
                    $result[$exists_key]['not_zero_calls'] += $item[0]['not_zero_calls'];
                    $result[$exists_key]['years'][$item[0]['report_time']] = array(
                        'bill_time' => $item[0]['bill_time'],
                        'total_calls' => $item[0]['total_calls'],
                        'not_zero_calls' => $item[0]['not_zero_calls'],
                        'total_time' => $item[0]['total_time'],
                    );
                } else {
                    array_push($result, array(
                        'ingress_client_id' => $item[0]['ingress_client_id'],
                        'client_name' => $item[0]['client_name'],
                        'orig_code_name' => $item[0]['ingress_code_name'],
                        'total_time' => $item[0]['total_time'],
                        'calls_30' => $item[0]['calls_30'],
                        //'time_30' => $item[0]['time_30'],
                        'calls_6' => $item[0]['calls_6'],
                        //'time_6' => $item[0]['time_6'],
                        //'bill_time' => $item[0]['bill_time'],
                        //'total_calls' => $item[0]['total_calls'],
                        'not_zero_calls' => $item[0]['not_zero_calls'],
                        'years' => array($item[0]['report_time'] => array(
                            'bill_time' => $item[0]['bill_time'],
                            'total_calls' => $item[0]['total_calls'],
                            'not_zero_calls' => $item[0]['not_zero_calls'],
                            'total_time' => $item[0]['total_time'],
                        )),
                    ));
                }
            }
            } else {
                $result = array();
                $this->set('show_nodata', false);
            }
            $this->set('data', $result);
            $this->set('start', $start_date);
            $this->set('end', $end_date);
            $this->set('quey_time', round((getMicrotime() - $t) * 1000, 0));
            if(isset($_GET['show_type']) && $_GET['show_type'] == '1') {
                header("Pragma: public");
                header("Expires: 0");
                header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
                header("Content-Type: text/csv");   
                header("Content-Type: application/octet-stream");
                header("Content-Type: application/download");;
                header("Content-Disposition: attachment;filename=ORIG_REPORT_{$_GET['start_date']}_{$_GET['stop_date']}.csv");
                header("Content-Transfer-Encoding: binary ");
                Configure::write('debug', 0);
                $this->autoLayout = FALSE;
                $this->render('org_report_csv');
            } else if(isset($_GET['show_type']) && $_GET['show_type'] == '2') {
                header("Pragma: public");
                header("Expires: 0");
                header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
                header("Content-Type: application/force-download");
                header("Content-Type: application/octet-stream");
                header("Content-Type: application/download");;
                header("Content-Disposition: attachment;filename=ORIG_REPORT_{$_GET['start_date']}_{$_GET['stop_date']}.xls");
                header("Content-Transfer-Encoding: binary ");
                Configure::write('debug', 0);
                $this->autoLayout = FALSE;
                $this->render('org_report_xls');
            }
        }
        
        
        function term_summary_reports() {
            $this->pageTitle="Termination Usage Detail/Spam Report ";
            $t = getMicrotime();
            extract($this->Cdr->get_start_end_time());
            $start_date = $start_date;
            $end_date = $end_date;
            $gmt = $tz;
            if(isset($_GET['start_date']) && !empty($_GET['start_date'])) 
            $start_date = $_GET['start_date'] . ' ' . $_GET['start_time'];
            if(isset($_GET['stop_date']) && !empty($_GET['stop_date']))
                $end_date = $_GET['stop_date'] . ' ' . $_GET['stop_time'];
            if(isset($_GET['query']['tz']) && !empty($_GET['query']['tz']))
                $gmt = $_GET['query']['tz'];
            $sql = "SELECT is_preload FROM system_parameter LIMIT 1";
            $is_preload_result = $this->Cdr->query($sql);
            $is_preload = $is_preload_result[0][0]['is_preload'];
            $this->set('show_nodata', true);
            if (isset($_GET['show_type']) || $is_preload) {
            $data = $this->Cdr->get_term_summary_reports($start_date, $end_date, $gmt);
            $result = array();
            foreach($data as $item) {
                //$exists_key = $this->search_exist($result, $item[0]['egress_client_id'], $item[0]['egress_code_name']);
                $exists_key = $this->search_exist_key($result, $item[0]['egress_client_id'], $item[0]['egress_code_name'], 'egress_client_id', 'egress_code_name');
                if($exists_key !== FALSE) {
                    $result[$exists_key]['total_time'] += $item[0]['total_time'];
                    $result[$exists_key]['calls_30'] += $item[0]['calls_30'];
                    $result[$exists_key]['time_30'] += $item[0]['time_30'];
                    $result[$exists_key]['calls_6'] += $item[0]['calls_6'];
                    $result[$exists_key]['time_6'] += $item[0]['time_6'];
                    //$result[$exists_key]['bill_time'] += $item[0]['bill_time'];
                    //$result[$exists_key]['total_calls'] += $item[0]['total_calls'];
                    $result[$exists_key]['not_zero_calls'] += $item[0]['not_zero_calls'];
                    $result[$exists_key]['years'][$item[0]['report_time']] = array(
                        'bill_time' => $item[0]['bill_time'],
                        'total_calls' => $item[0]['total_calls'],
                        'not_zero_calls' => $item[0]['not_zero_calls'],
                        'total_time' => $item[0]['total_time'],
                    );
                } else {
                    array_push($result, array(
                        'egress_client_id' => $item[0]['egress_client_id'],
                        'client_name' => $item[0]['client_name'],
                        'term_code_name' => $item[0]['egress_code_name'],
                        'total_time' => $item[0]['total_time'],
                        'calls_30' => $item[0]['calls_30'],
                        'time_30' => $item[0]['time_30'],
                        'calls_6' => $item[0]['calls_6'],
                        'time_6' => $item[0]['time_6'],
                        //'bill_time' => $item[0]['bill_time'],
                        //'total_calls' => $item[0]['total_calls'],
                        'not_zero_calls' => $item[0]['not_zero_calls'],
                        'years' => array($item[0]['report_time'] => array(
                            'bill_time' => $item[0]['bill_time'],
                            'total_calls' => $item[0]['total_calls'],
                            'not_zero_calls' => $item[0]['not_zero_calls'],
                            'total_time' => $item[0]['total_time'],
                        )),
                    ));
                }
            }
            } else {
                $result = array();
                $this->set('show_nodata', false);
            }
            $this->set('data', $result);
            $this->set('start', $start_date);
            $this->set('end', $end_date);
            $this->set('quey_time', round((getMicrotime() - $t) * 1000, 0));
            if(isset($_GET['show_type']) && $_GET['show_type'] == '1') {
                header("Pragma: public");
                header("Expires: 0");
                header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
                header("Content-Type: text/csv");   
                header("Content-Type: application/octet-stream");
                header("Content-Type: application/download");;
                header("Content-Disposition: attachment;filename=ORIG_REPORT_{$_GET['start_date']}_{$_GET['stop_date']}.csv");
                header("Content-Transfer-Encoding: binary ");
                Configure::write('debug', 0);
                $this->autoLayout = FALSE;
                $this->render('term_report_csv');
            } else if(isset($_GET['show_type']) && $_GET['show_type'] == '2') {
                header("Pragma: public");
                header("Expires: 0");
                header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
                header("Content-Type: application/force-download");
                header("Content-Type: application/octet-stream");
                header("Content-Type: application/download");;
                header("Content-Disposition: attachment;filename=ORIG_REPORT_{$_GET['start_date']}_{$_GET['stop_date']}.xls");
                header("Content-Transfer-Encoding: binary ");
                Configure::write('debug', 0);
                $this->autoLayout = FALSE;
                $this->render('term_report_xls');
            }
        }
	
    function search_exist1($arr, $v) {
        foreach($arr as $key => $val) {
            foreach($val as $item) {
                if(in_array($v, $item)) {
                    return $key;
                }
            }
        } 
        return FALSE;
    }
            
    function daily_orig_summary() {
            $this->pageTitle="Daily Origination Summary Report";
            $t = getMicrotime();
            extract($this->Cdr->get_start_end_time());
            $start_date = $start_date;
            $end_date = $end_date;
            $gmt = $tz;
            if(isset($_GET['start_date']) && !empty($_GET['start_date'])) 
            $start_date = $_GET['start_date'] . ' ' . $_GET['start_time'];
            if(isset($_GET['stop_date']) && !empty($_GET['stop_date']))
                $end_date = $_GET['stop_date'] . ' ' . $_GET['stop_time'];
            if(isset($_GET['query']['tz']) && !empty($_GET['query']['tz']))
                $gmt = $_GET['query']['tz'];
            $sql = "SELECT is_preload FROM system_parameter LIMIT 1";
            $is_preload_result = $this->Cdr->query($sql);
            $is_preload = $is_preload_result[0][0]['is_preload'];
            $this->set('show_nodata', true);
            if (isset($_GET['show_type']) || $is_preload) {
            $data = $this->Cdr->get_daily_orig_summary($start_date, $end_date, $gmt);
            $result = array();
            foreach($data as $item) {
                $key = $this->search_exist1($result, $item[0]['ingress_client_id']);
                if($key !== FALSE) {
                    $result[$key][$item[0]['report_time']] = array(
                        'ingress_client_id' => $item[0]['ingress_client_id'],
                        'total_time' => $item[0]['total_time'],
                        'client_name' => $item[0]['client_name'],
                    ); 
                } else {
                    $result[] = array($item[0]['report_time'] => array(
                        'ingress_client_id' => $item[0]['ingress_client_id'],
                        'total_time' => $item[0]['total_time'],
                        'client_name' => $item[0]['client_name'],
                    )); 
                }
            } 
            } else {
                $result = array();
                $this->set('show_nodata', false);
            }
            
            $this->set('data', $result);
            $this->set('start', $start_date);
            $this->set('end', $end_date);
            $this->set('quey_time', round((getMicrotime() - $t) * 1000, 0));
            if(isset($_GET['show_type']) && $_GET['show_type'] == '1') {
                header("Pragma: public");
                header("Expires: 0");
                header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
                header("Content-Type: text/csv");   
                header("Content-Type: application/octet-stream");
                header("Content-Type: application/download");;
                header("Content-Disposition: attachment;filename=DAILY_ORIG_REPORT_{$_GET['start_date']}_{$_GET['stop_date']}.csv");
                header("Content-Transfer-Encoding: binary ");
                Configure::write('debug', 0);
                $this->autoLayout = FALSE;
                $this->render('daily_orig_report_csv');
            } else if(isset($_GET['show_type']) && $_GET['show_type'] == '2') {
                header("Pragma: public");
                header("Expires: 0");
                header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
                header("Content-Type: application/force-download");
                header("Content-Type: application/octet-stream");
                header("Content-Type: application/download");;
                header("Content-Disposition: attachment;filename=DAILY_ORIG_REPORT_{$_GET['start_date']}_{$_GET['stop_date']}.xls");
                header("Content-Transfer-Encoding: binary ");
                Configure::write('debug', 0);
                $this->autoLayout = FALSE;
                $this->render('daily_orig_report_xls');
            }
        }	

        
    function daily_term_summary() {
            $this->pageTitle="Daily Termination Summary Report";
            $t = getMicrotime();
            extract($this->Cdr->get_start_end_time());
            $start_date = $start_date;
            $end_date = $end_date;
            $gmt = $tz;
            if(isset($_GET['start_date']) && !empty($_GET['start_date'])) 
            $start_date = $_GET['start_date'] . ' ' . $_GET['start_time'];
            if(isset($_GET['stop_date']) && !empty($_GET['stop_date']))
                $end_date = $_GET['stop_date'] . ' ' . $_GET['stop_time'];
            if(isset($_GET['query']['tz']) && !empty($_GET['query']['tz']))
                $gmt = $_GET['query']['tz'];
            $sql = "SELECT is_preload FROM system_parameter LIMIT 1";
            $is_preload_result = $this->Cdr->query($sql);
            $is_preload = $is_preload_result[0][0]['is_preload'];
            $this->set('show_nodata', true);
            if (isset($_GET['show_type']) || $is_preload) {
            $data = $this->Cdr->get_daily_term_summary($start_date, $end_date, $gmt);
            $result = array();
            foreach($data as $item) {
                $key = $this->search_exist1($result, $item[0]['egress_client_id']);
                if($key !== FALSE) {
                    $result[$key][$item[0]['report_time']] = array(
                        'egress_client_id' => $item[0]['egress_client_id'],
                        'total_time' => $item[0]['total_time'],
                        'client_name' => $item[0]['client_name'],
                    ); 
                } else {
                    $result[] = array($item[0]['report_time'] => array(
                        'egress_client_id' => $item[0]['egress_client_id'],
                        'total_time' => $item[0]['total_time'],
                        'client_name' => $item[0]['client_name'],
                    )); 
                }
            }
            } else {
                $result = array();
                $this->set('show_nodata', false);
            }
            $this->set('data', $result);
            $this->set('start', $start_date);
            $this->set('end', $end_date);
            $this->set('quey_time', round((getMicrotime() - $t) * 1000, 0));
            if(isset($_GET['show_type']) && $_GET['show_type'] == '1') {
                header("Pragma: public");
                header("Expires: 0");
                header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
                header("Content-Type: text/csv");   
                header("Content-Type: application/octet-stream");
                header("Content-Type: application/download");;
                header("Content-Disposition: attachment;filename=DAILY_ORIG_REPORT_{$_GET['start_date']}_{$_GET['stop_date']}.csv");
                header("Content-Transfer-Encoding: binary ");
                Configure::write('debug', 0);
                $this->autoLayout = FALSE;
                $this->render('daily_term_report_csv');
            } else if(isset($_GET['show_type']) && $_GET['show_type'] == '2') {
                header("Pragma: public");
                header("Expires: 0");
                header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
                header("Content-Type: application/force-download");
                header("Content-Type: application/octet-stream");
                header("Content-Type: application/download");;
                header("Content-Disposition: attachment;filename=DAILY_ORIG_REPORT_{$_GET['start_date']}_{$_GET['stop_date']}.xls");
                header("Content-Transfer-Encoding: binary ");
                Configure::write('debug', 0);
                $this->autoLayout = FALSE;
                $this->render('daily_term_report_xls');
            }
        }
	
	
	function group_time( $start = null, $end = null )
	{
		$return = array();
		$start = empty($start) ? date("Y-m-d") : date("Y-m-d", strtotime($start));
		$end = empty($end) ? date("Y-m-d") : date("Y-m-d", strtotime($end));
		
		for ($i = strtotime($end); $i >= strtotime($start); $i -= 3600*24)
		{
			$return[] = date("Ymd", $i);
		}
                
		return $return;
	}
	
	
	
	function _download_impl($params=array()){
		
	Configure::write('debug',0);
		extract($params);
		if($this->Cdr->download_by_sql($download_sql,array('objectives'=>'client_cdr','file_name'=>$file_name))){
			exit(1);
		}
	}
	
	function _download_xls($params=array()){
		extract($params);
		if($this->Cdr->download_xls_by_sql($download_sql,array('objectives'=>'client_cdr','file_name'=>$file_name))){
			exit(1);
		}
	}
}
?>