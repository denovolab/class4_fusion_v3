<?php
class Route extends AppModel {
	var $name = 'Route';
	var $useTable = 'route';
	var $primaryKey = 'route_id';
	var $order = "route_id DESC";
	
	
	
	

			var $default_schema = Array(
			'digits' => array ('type' => 'string',  'null' => 1,  'default' => '',  'length' => 100),
			'route_type' => array ('type' => 'integer',  'null' => '',  'default' => '',  'length' => ''),
			'static_route_id' => array ('name'=>'Static Route Name','type' => 'integer',  'null' => '',  'default' =>'',  'length' => ''),
			'dynamic_route_id' => array ('name'=>'Dynamic Routing Name','type' => 'integer',  'null' => '',  'default' =>'',  'length' => ''),
		

		);
		
		
	
	static $static_route_ids = null;
			function format_static_route_id_for_download($value,$data){
				if(!self::$static_route_ids){
				App::import("Model",'Product');
			$model = new Product;
			self::$static_route_ids = $model->find("all"); 
		}
		foreach(self::$static_route_ids as $time_profile){
			if($time_profile['Product']['product_id'] == $value){
				return $time_profile['Product']['name'];
			}
		}
	}	
		
	
	
		static $dynamic_route_ids = null;
			function format_dynamic_route_id_for_download($value,$data){
				if(!self::$dynamic_route_ids){
				App::import("Model",'DynamicRoute');
			$model = new DynamicRoute;
			self::$dynamic_route_ids = $model->find("all"); 
		}
		foreach(self::$dynamic_route_ids as $time_profile){
			if($time_profile['DynamicRoute']['dynamic_route_id'] == $value){
				return $time_profile['DynamicRoute']['name'];
			}
		}
	}	
		
	function get_foreign_name($id){
  return  $this->query("select name from route_strategy where  route_strategy_id=$id;");

}	
	
	
	function find_all_valid(){
		return $this->findAll();
	}
}
?>