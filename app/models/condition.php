<?php
class Condition extends AppModel{
	var $name = 'Condition';
	var $useTable = 'alert_condition';
	var $primaryKey = 'id';
	
	public function ListCondition($currPage=1, $pageSize=15, $search_arr=array(), $search_type = 0)
	{
		require_once 'MyPage.php';
		$page = new MyPage();
		
		$totalrecords = 0;
		
		$sql_where = '';
		if ($search_type == 1)
		{
		if (!empty($search_arr['name'])) $sql_where .= " and name like '%".addslashes($search_arr['name'])."%'";
		if (!empty($search_arr['acd'])) $sql_where .= " and (( acd_comparator = 0 and acd_value_min >= ".floatval($search_arr['acd'])." ) or ( acd_comparator = 1 and acd_value_min <= ".floatval($search_arr['acd'])." and acd_value_max >= ".floatval($search_arr['acd'])."))";
		if (!empty($search_arr['asr'])) $sql_where .= " and (( asr_comparator = 0 and asr_value_min >= ".floatval($search_arr['asr'])." ) or ( asr_comparator = 1 and asr_value_min <= ".floatval($search_arr['asr'])." and asr_value_max >= ".floatval($search_arr['asr'])."))";
		if (!empty($search_arr['margin'])) $sql_where .= " and (( margin_comparator = 0 and margin_value_min >= ".floatval($search_arr['margin'])." ) or ( margin_comparator = 1 and margin_value_min <= ".floatval($search_arr['margin'])." and margin_value_max >= ".floatval($search_arr['margin'])."))";
		}
		else
		{
			$sql_where = " and ( (name like '%".addslashes($search_arr['name'])."%') or (( acd_comparator = 0 and acd_value_min >= ".floatval($search_arr['acd'])." ) or ( acd_comparator = 1 and acd_value_min <= ".floatval($search_arr['acd'])." and acd_value_max >= ".floatval($search_arr['acd']).")) or (( margin_comparator = 0 and margin_value_min >= ".floatval($search_arr['margin'])." ) or ( margin_comparator = 1 and margin_value_min <= ".floatval($search_arr['margin'])." and margin_value_max >= ".floatval($search_arr['margin']).")) )";
		}
		
		$sql = "select count(id) as c from alert_condition where 1=1".$sql_where;
		$totalrecords = $this->query($sql);
	 	
		$page->setTotalRecords($totalrecords[0][0]['c']);//总记录数
		$page->setCurrPage($currPage);//当前页
		$page->setPageSize($pageSize);//页大小
		
		//$page = $page->checkRange($page);//检查当前页范围
		
		$currPage = $page->getCurrPage()-1;
		$pageSize = $page->getPageSize();
		$offset = $currPage * $pageSize;
		
		//查询Client groups
		$sql = "select * from alert_condition where 1=1".$sql_where;
		
		$sql .= " order by name asc limit '$pageSize' offset '$offset'";
		//echo $sql;
		$results = $this->query($sql);
		
		$page->setDataArray($results);//Save Data into $page
		//////////////////////////////////////////
		
		return $page;
	}
	
	public function getConditionNameArr()
	{
		$return = array();
		$sql = "select id,name from alert_condition ORDER BY id DESC";
		$results = $this->query($sql);
		foreach($results as $k=>$v)
		{
			$return[$v[0]['id']] = $v[0]['name'];
		}
		return $return;
		
	}
	
}
?>