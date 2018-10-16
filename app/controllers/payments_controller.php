<?php 
class PaymentsController extends AppController {
	var $name = 'Payments';
	var $helpers = array('form','AppCommon');
	var $uses = array('Invoice');
	
		
	public function beforeFilter(){
		$this->checkSession ( "login_type" );//核查用户身份
	   parent::beforeFilter();//调用父类方法
	}
	
	function unpaid_bills(){
		$this->pageTitle="Management/Unpaid Bills Summary";
		$results = $this->Invoice->getUnpaidInvoices ($this->_filter_conditions(array('search_name','range_t_invoice_time','paid_type')));
		$this->set ( 'p', $results);
	}
	
	function _filter_search_name(){
		$value = (string)array_keys_value($this->params,"url.filter_search_name");
		$value = trim($value);
		$value = $this->quote_sql_string($value);
		if(!empty($value)){
			return "(client.name like '%{$value}%' OR invoice.client_id::text like '%{$value}%' OR invoice_number LIKE '%{$value}%')";
		}
		return null;
	}
	function _filter_paid_type(){
		$value = (string)array_keys_value($this->params,"url.filter_paid_type");
		switch($value){
			case '1' : return null;
			case '2' : return "(pay_amount is null or pay_amount = 0)";
			case '3' : return "pay_amount > 0";
			default : return null;			
		}
	}
	
}
?>