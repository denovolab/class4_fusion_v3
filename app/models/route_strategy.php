<?php
class RouteStrategy extends AppModel {
	var $name = 'RouteStrategy';
	var $useTable = 'route_strategy';
	var $primaryKey = 'route_strategy_id';
	var $order = "route_strategy_id DESC";
	
	function find_all_valid(){
		return $this->findAll();
	}
}
?>