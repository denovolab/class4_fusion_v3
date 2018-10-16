<?php
class Servicecharge extends AppModel{
	var $name = 'Servicecharge';
	var $useTable = 'service_charge';
	var $primaryKey = 'service_charge_id';
	
public  function validate_data($data){
	$error_flag = 'false'; //错误信息标志
	$name = $data ['Servicecharge'] ['name'];
		if (empty ( $name )) {
			$this->create_json_array ('#ServicechargeName', 101, __ ( 'clientnamenull', true ) );
			$error_flag = 'true'; //有错误信息
		}
	
		return $error_flag;
}
	



	
		public function findAll($order){
			if(!empty($order)){
			    $order="order dy".$order;
			              
			}
			
			
	 empty($_GET['page'])?$currPage = 1:$currPage = $_GET['page'];
		 empty($_GET['size'])?$pageSize = 10:	$pageSize = $_GET['size'];
		 $order=$this->_get_order();
		 
		//分页信息
		require_once 'MyPage.php';
		$page = new MyPage();
	 $login_type=$_SESSION['login_type'];
	 $privilege='';//权限条件

		 if($login_type==3){
	 		$privilege="  and(client_id::integer={$_SESSION['sst_client_id']}) ";

	 }
	 

//模糊搜索
	 $like_where=!empty($_GET['search'])?" and (name like '%{$_GET['search']}%'  or  service_charge_id::text like '%{$_GET['search']}%'  or
	  buy_rate::text like '%{$_GET['search']}%')":'';
 	 //起始金额
	 $name_where=!empty($_GET['name'])?"  and (name='{$_GET['name']}')":'';




	 $totalrecords = $this->query("select count(*) as c from service_charge where 1=1 
	  $like_where  $name_where");
		$page->setTotalRecords($totalrecords[0][0]['c']);//总记录数
		$page->setCurrPage($currPage);//当前页
		$page->setPageSize($pageSize);//页大小
		
		//$page = $page->checkRange($page);//检查当前页范围
		
		$currPage = $page->getCurrPage()-1;
		
		$pageSize = $page->getPageSize();
		$offset=$currPage*$pageSize;
		$sql = "select  * from  service_charge where 1=1  
		$like_where  $name_where  ";
		$sql.=$order;		
	 $sql .= "   	limit '$pageSize' offset '$offset'";
		$results = $this->query($sql);
		
		$page->setDataArray($results);
		return $page;
	}	
	
	
	
}