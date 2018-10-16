<?php 
App::import('Model','PaymentTerm');
class AppPaymenttermsHelper extends AppHelper {	
	function get_options_type(){
		$options_type=Array(
			PaymentTerm::PAYMENT_TERM_DAY_OF_DAYS_SEPARATED=>__('onxdayofmonth',true),
			PaymentTerm::PAYMENT_TERM_DAY_OF_EACH_MONTH=>__('everyxdays',true),
			PaymentTerm::PAYMENT_TERM_DAY_OF_EACH_WEEK=>__('onxdayofweek',true),
			PaymentTerm::PAYMENT_TERM_SOME_DAY_OF_DAYS_SEPARATED=>__('someonxdayofmonth',true)
		);	
		return $options_type;
	}
}
?>
