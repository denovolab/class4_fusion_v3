<?php
class Paymentterm extends AppModel{
	var $name = 'Paymentterm';
	var $useTable = 'payment_term';
	var $primaryKey = 'payment_term_id';
	
	CONST PAYMENT_TERM_DAY_OF_DAYS_SEPARATED=2;
	CONST PAYMENT_TERM_DAY_OF_EACH_MONTH=1;
	CONST PAYMENT_TERM_DAY_OF_EACH_WEEK=3;
	CONST PAYMENT_TERM_SOME_DAY_OF_DAYS_SEPARATED=4;
/**
	 *  分页查询Client groups
	 * @param int $currPage 当前页
	 * @param int $pageSize 页大小
	 */
	public function getAllTerms($currPage=1,$pageSize=15,$search=null,$order=null,$where = ''){
			if(empty($order)){
				$order="name asc";
		}
		//分页信息
		require_once 'MyPage.php';
		$page = new MyPage();
		
		$totalrecords = 0;
		
		$sql  = "select count(payment_term_id) as c from payment_term where 1=1";
		if (!empty($search)) $sql .= " and name like '%$search%'";
		$totalrecords = $this->query($sql);
	 	
		$page->setTotalRecords($totalrecords[0][0]['c']);//总记录数
		$page->setCurrPage($currPage);//当前页
		$page->setPageSize($pageSize);//页大小
		
		//$page = $page->checkRange($page);//检查当前页范围
		
		$currPage = $page->getCurrPage()-1;
		$pageSize = $page->getPageSize();
		$offset = $currPage * $pageSize;
		
		//查询Client groups
		$sql = "select payment_term_id,name,type,days,
							grace_days,notify_days,more_days,finance_rate,
							(select count(client_id) from client 
							where client.payment_term_id=payment_term.payment_term_id) as clients
							from payment_term where 1=1".$where;
		
		
		if (!empty($search)) $sql .= " and name like '%$search%'";
		$sql .= "  order by $order limit '$pageSize' offset '$offset'";
		
		$results = $this->query($sql);
		
		$page->setDataArray($results);//Save Data into $page
		//////////////////////////////////////////
		
		return $page;
	}
	
	public function getTermById($id){
		return $this->query("select payment_term_id,name,type,days,grace_days,notify_days from payment_term where payment_term_id='$id'");
	}
}