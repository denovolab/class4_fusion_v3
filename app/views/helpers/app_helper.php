<?php

App::import('Core', 'Helper');
class AppHelper extends Helper {
	function _get_select_options($lists,$model,$key,$value,$options=Array()){
		//$options=Array("0"=>"");
		$options=Array();
		foreach($lists as $list)
		{
			$options[$list[$model][$key]]=$list[$model][$value];
		}
		return $options;
	}
	function _get($key){
		return $this->getParams('url.'.$key);
	}
	function getParams($keys=''){
		if(empty($keys)){
			return $this->params;
		}
		return array_keys_value($this->params,$keys);
	}
	function getPass($key){
		return $this->getParams('pass.'.$key);
	}
	function getAction(){
		return $this->getParams('action');
	}
	function actionIs($key,$type=0){
		$options_type=array(0=>'stripos','i'=>'stripos','n'=>'strpos','strpos'=>'strpos');
		return isNFalse($options_type[$type]($this->getAction(),$key));
	}
	function isIngress($type=null){
		if($type){
			return ($type=='ingress');
		}
		return $this->actionIs('Ingress');
	}
	function isEgress(){
		if($type){
			return ($type='egress');
		}
		return $this->actionIs('Egress');
	}
	function getProductName(){
		return Configure::read('project_name');
	}
}
?>
