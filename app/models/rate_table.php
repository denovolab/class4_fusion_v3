<?php
// 名字被其他哥们儿占用了，  没办法。
class RateTable extends AppModel {
	var $name = 'RateTable';
	var $useTable = 'rate';
	var $primaryKey = 'rate_id';
	var $order = "rate_id DESC";

	var $validate = array(			
//			'code_name' => array(
//				'blank' => array(
//					'required' => false,
//					'rule' => 'notEmpty',
//					'message' => 'code name cannot be NULL!',						
//					'last' => true
//					),
//				'alphaNumeric' => array(
//					'required' => true,
//					'rule' => '/^[\w\-\_\s]+$/',
//					'message' => 'code name must contain numeric characters only.'
//				)				
//			),
			'code' => array(
				'blank' => array(
					'required' => true,
					'rule' => 'notEmpty',
					'message' => 'prefix cannot be NULL!',						
					'last' => true
					),
				'numeric' => array(
					'required' => true,
					'rule' => array('numeric'),
					'message' => 'prefix must contain numeric only.'
				)		
			),
			'setup_fee' => array(
				'blank' => array(
					'required' => true,
					'rule' => 'notEmpty',
					'message' => 'setup fee cannot be NULL!',						
					'last' => true
					),
				'numeric' => array(
					'required' => true,
					'rule' => array('numeric'),
					'message' => 'setup fee must contain numeric only.'
				)				
			),
			'min_time' => array(
				'blank' => array(
					'required' => true,
					'rule' => 'notEmpty',
					'message' => 'min time cannot be NULL!',						
					'last' => true
					),
				'numeric' => array(
					'required' => true,
					'rule' => array('numeric'),
					'message' => 'min time must contain numeric only.'
				)				
			),
			'interval' => array(
				'blank' => array(
					'required' => true,
					'rule' => 'notEmpty',
					'message' => 'interval cannot be NULL!',						
					'last' => true
					),
				'numeric' => array(
					'required' => true,
					'rule' => array('numeric'),
					'message' => 'interval must contain numeric only.'
				)				
			),
			'grace_time' => array(
				'blank' => array(
					'required' => true,
					'rule' => 'notEmpty',
					'message' => 'grace time cannot be NULL!',						
					'last' => true
					),
				'numeric' => array(
					'required' => true,
					'rule' => array('numeric'),
					'message' => 'grace time must contain numeric only.'
				)
			),		
			'intra_rate' => array(
				'blank' => array(
					'required' => true,
					'rule' => 'notEmpty',
					'message' => 'intrastate rate cannot be NULL!',						
					'last' => true
					),
				'numeric' => array(
					'required' => true,
					'rule' => array('numeric'),
					'message' => 'intrastate rate must be float.'
				)				
			),
			'inter_rate' => array(
				'blank' => array(
					'required' => true,
					'rule' => 'notEmpty',
					'message' => 'interstate rate cannot be NULL!',						
					'last' => true
					),
				'numeric' => array(
					'required' => true,
					'rule' => array('numeric'),
					'message' => 'interstate rate must be float.'
				)				
			),
			'local_rate' => array(
				'blank' => array(
					'required' => true,
					'rule' => 'notEmpty',
					'message' => 'local rate cannot be NULL!',						
					'last' => true
					),
				'numeric' => array(
					'required' => true,
					'rule' => array('numeric'),
					'message' => 'local rate must be float.'
				)				
			),
			'rate' => array(
				'blank' => array(
					'required' => true,
					'rule' => 'notEmpty',
					'message' => 'rate cannot be NULL!',						
					'last' => true
					),
				'float' => array(
					'required' => true,
					'rule' => array('numeric'),
					'message' => 'rate must be float.'
				)				
			),
		);
		
	var $default_schema = Array(
			'code' => array( 'type' => 'text', 'null' => '', 'default' => '', 'length' => ''),
			'code_name' => array (  'type' => 'string',  'null' => 1,  'default' => '',  'length' => 100),
			'country' => array (  'type' => 'string',  'null' => 1,  'default' => '',  'length' => 100),
			'setup_fee' => array( 'type' => 'float', 'null' => '', 'default' => 0, 'length' => ''),
			'effective_date' => array( 'type' => 'datetime', 'null' => '', 'default' => '',      'length' => ''),
			'end_date' => array (  'type' => 'datetime',  'null' => 1,  'default' => '',  'length' => ''),
			'min_time' => array (  'type' => 'integer',  'null' => '',  'default' => 0,  'length' => ''),
			'interval' => array (  'type' => 'integer',  'null' => '',  'default' => 1,  'length' => ''),
			'grace_time' => array (  'type' => 'integer',  'null' => '',  'default' => 0,  'length' => ''),
			'seconds' => array (  'type' => 'integer',  'null' => '',  'default' => 60,  'length' => ''),
			'time_profile_id' => array ( 'name' => "Profile", 'type' => 'integer',  'null' => '',  'default' => '',  'length' => ''),
//			'basic_percentages' => array (  'type' => 'integer',  'null' => 1,  'default' => 100,  'length' => ''),
//			'gift_percentages' => array (  'type' => 'integer',  'null' => 1,  'default' => 0,  'length' => ''),
//			'rate_type' => array (  'type' => 'integer',  'null' => '',  'default' => 3,  'length' => ''),
			'intra_rate' => array ('type' => 'float',  'null' => 1,  'default' => 0,  'length' => ''),
			'inter_rate' => array ('type' => 'float',  'null' => 1,  'default' => 0,  'length' => ''),
			'local_rate' => array (  'type' => 'float',  'null' => 1,  'default' => 0,  'length' => ''),
			'rate' => array('type' => 'float', 'null' => '', 'default' => 0, 'length' => ''),
			'zone' => array ( 'name' => "RateZoneTime", 'type' => 'string',  'null' => '',  'default' => '',  'length' => ''),
                        'ocn' => array ('type' => 'string',  'null' => 1,  'default' => '',  'length' => 10),
                        'lata' => array ('type' => 'string',  'null' => 1,  'default' => '',  'length' => 10),
		);

	static $time_profiles = null;
	static $codes = array();
	
/////////////////////////// for download /////////////	
	
	
	
	
	
	//自定义下载方法
	function find_all_for_download($fields,$conditions,$order){
		$column=join(',',$fields);
	 return  	$this->query("select  $column   from  rate,
	  ( select rate_table_id, code as table_code,max(effective_date) as max_effect from rate as RateTable
   where $conditions group by code,rate_table_id ) as table_rate  
   where rate.code=table_rate.table_code and rate.effective_date=table_rate.max_effect and rate.rate_table_id=table_rate.rate_table_id order by rate.code desc ");
		
		
	}
	
	
	
	/**
	 * 
	 * 
	 * 格式化时间段
	 * @param $value
	 * @param $data
	 */
	function format_time_profile_id_for_download($value,$data){
		if(empty($value)){
			return null;
		}
		if(!self::$time_profiles){
			App::import("Model",'Timeprofile');
			$model = new Timeprofile;
			self::$time_profiles = $model->find("all"); 
		}
		foreach(self::$time_profiles as $time_profile ){
			if($time_profile['Timeprofile']['time_profile_id'] == $value){
				return $time_profile['Timeprofile']['name'];
			}
		}
	}
	
	
	
	
// for upload check duplicate
	function check_duplicate_for_upload($data){
		return $this->find("first",array('conditions' => "rate_table_id = ".$data['RateTable']['rate_table_id']." AND code = '{$data['RateTable']['code']}'"));	
	}
		// for effective
//		function check_duplicate_for_upload($data){
//		if(!isset(self::$codes[$data[$this->alias]['rate_table_id']])){
///			$this->find("all",array('conditions' => "rate_table_id = ".$data['RateTable']['rate_table_id'] , 'fields' => 'code'));
//			$rate_codes = $this->query("SELECT code FROM rate WHERE rate_table_id = ".$data[$this->alias]['rate_table_id']);
//			self::$codes[$data[$this->alias]['rate_table_id']] = array();
//			foreach($rate_codes as $rate_code){
//				self::$codes[$data[$this->alias]['rate_table_id']][] = $rate_code[0]['code'];	
//			}
//		}
//		return in_array($data[$this->alias]['code'],self::$codes[$data[$this->alias]]);
//	}
//
//	function after_save_for_upload($data){
//		if(!isset(self::$codes[$data[$this->alias]['rate_table_id']])){
//			self::$codes[$data[$this->alias]['rate_table_id']] = array();
//		}
//		self::$codes[$data[$this->alias]['rate_table_id']][] = $data[$this->alias]['code'];
//	}
	
	function format_time_profile_id_for_upload($value,$data){
		if(empty($value)){
			return null;
		}
		if(!self::$time_profiles){
			App::import("Model",'Timeprofile');
			$model = new Timeprofile;
			self::$time_profiles = $model->find("all"); 
		}
		foreach(self::$time_profiles as $time_profile ){
			if($time_profile['Timeprofile']['name'] == $value){
				return $time_profile['Timeprofile']['time_profile_id'];
			}
		}
		throw new Exception('Profile '.$value." does not exist");
	}
	
	function get_foreign_name($id){
	  return  $this->query(" select  name from  rate_table  where  rate_table_id=$id;");
	}		
}
?>