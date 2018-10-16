<?php

class PaymentHistoryController extends AppController
{
	var $name = "PaymentHistory";
	var $helpers = array('Javascript','Html', 'Text');
	var $components = array('RequestHandler');
	var $uses = array('PaymentHistory');
	
	public function beforeFilter() {
		$this->checkSession("login_type"); //核查用户身份
		
		parent::beforeFilter();
	}
		
	public function index()
	{
		$login_type = $_SESSION['login_type'];
		$this->paginate = array(
				'fields' => array(
						'PaymentHistory.chargetotal', 'PaymentHistory.method','PaymentHistory.cardnumber', 'PaymentHistory.cardexpmonth',
						'PaymentHistory.cardexpyear', 'PaymentHistory.created_time', 'PaymentHistory.modified_time', 'PaymentHistory.error',
						'PaymentHistory.confirmed', 'client.name','PaymentHistory.status', 'PaymentHistory.fee'
				),
				'limit' => 100,
				'joins' => array(
						array(
								'table' => 'client',
								'type'  => 'left',
								'conditions' => array(
										'PaymentHistory.client_id = client.client_id'
								),
						)
				),
				'order' => array(
						'PaymentHistory.id' => 'desc',
				),
		);
		if ($login_type == 3)
		{
			$this->paginate['conditions'] = array("PaymentHistory.client_id" => $_SESSION['sst_client_id']);
		}
		$method = array('paypal', 'yourpay');
		$status = array('initial', 'error', 'succeeded');
		$this->data = $this->paginate('PaymentHistory');
                
                $sql = "select yourpay_store_number from system_parameter limit 1";
                $result = $this->PaymentHistory->query($sql);
                if (empty($result[0][0]['yourpay_store_number']))
                {
                    $this->set('credit_card', false);
                } 
                else {
                    $this->set('credit_card', true);
                }
                
		$this->set('method', $method);
		$this->set('status', $status);
                $this->set('login_type', $login_type);
	}
	
}