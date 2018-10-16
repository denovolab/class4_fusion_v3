<?php
class LogsController extends AppController{
	var $name = 'Logs';
	var $components = array('RequestHandler');
	
	public function beforeFilter(){
		$this->checkSession ( "login_type" );//核查用户身份
   parent::beforeFilter();
	}

	function index(){
		$this->pageTitle="Management/Search Logs";
		
			$currPage = 1;
			$pageSize = 100;
			$search_arr = array();
			$order_arr = array();

			
			if (!empty($_REQUEST['search']))			//模糊查询
			{
				$search_type = 0;
				$search_arr['search'] = !empty($_REQUEST['search']) ? $_REQUEST['search'] : '';				
			}
			else																						//按条件搜索
			{
				$search_type = 1;
				$search_arr['type'] = !empty($_REQUEST['type']) ? ($_REQUEST['type']) : '';
				$search_arr['search_val'] = !empty($_REQUEST['search_val']) ? ($_REQUEST['search_val']) : '';
				$search_arr['start_date'] = !empty($_REQUEST['start_date']) ? ($_REQUEST['start_date']) : '';
				$search_arr['end_date'] = !empty($_REQUEST['end_date']) ? ($_REQUEST['end_date']) : '';
				$search_arr['name'] = isset($_REQUEST['name']) ? $_REQUEST['name'] : '';
				
			}
			
			if (! empty ( $_REQUEST ['page'] )) {
				$currPage = $_REQUEST ['page'];
			}
			
                        $pageSize = isset($_SESSION['paging_row']) ? $_SESSION['paging_row'] : 15;
                        
			if (! empty ( $_REQUEST ['size'] )) {
				$pageSize = $_REQUEST ['size'];
			}
		
	
		$results = $this->Log->ListLog ($currPage, $pageSize, $search_arr, $search_type);
		$this->set ( 'p', $results);		
		
	}
}
?>
