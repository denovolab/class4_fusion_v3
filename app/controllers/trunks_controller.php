<?php
class TrunksController extends AppController {
	var $name = 'Trunks';
	var $uses=Array('Resource');
	
public function beforeFilter(){
//	$this->checkSession ( "login_type" );//核查用户身份
//   parent::beforeFilter();//调用父类方法
}
	function _render_index_bindModel(){
		$this->Resource->unbindModel(Array('hasMany'=>Array('ResourceIp','InRealcdr','ERealcdr')),false);
		$bindModel=Array();
		$bindModel['belongsTo']=Array();
		$bindModel['belongsTo']['Client']=Array('className'=>'Client','fields'=>Array('client_id','name'));
		$bindModel['belongsTo']['RateTable']=Array('className'=>'Rate','fields'=>Array('rate_table_id','name'));
		$this->Resource->bindModel($bindModel,false);
	}
	function _render_index_fields(){
		$this->paginate['Resource']['fields']=Array('resource_id','alias','Resource.name','cps_limit','capacity','ingress','egress','active','proto');
	}
	function _render_index_data(){
		$this->_render_index_bindModel();
		$this->_render_index_fields();
		$this->data=$this->paginate('Resource');
	}
	function index(){
		$this->layout='ajax';
		$this->_render_index_data();
	}
	function ajax_options(){
		Configure::write('debug',0);
		$this->layout='ajax';
                if(!isset($this->params['url']['trunk_type2']))
                {
                    $this->params['url']['trunk_type2'] = 0;
                }
                
                
		$conditions=$this->_filter_conditions(Array('id'=>'Resource.client_id','type', 'trunk_type2'=> 'Resource.trunk_type2', 'show_type' => 'Resource.ingress'));
                
		$options=Array(
			'conditions'=>$conditions,
			'fields'=>Array('resource_id','alias'),
			'order'=>'alias asc'
		);
		$this->Resource->unbindModel(Array('hasMany'=>Array('ResourceIp','InRealcdr','ERealcdr')));
		$this->data=$this->Resource->find('all',$options);
	}
	
	public function _filter_trunk_type2()
	{
		$type=array_keys_value($this->params,'url.trunk_type2');
		$return = "trunk_type2 = {$type}";
		return $return;
	}
        
        public function _filter_show_type()
	{
            if(array_keys_value($this->params,'url.show_type') !== NULL)
            {
		$type=array_keys_value($this->params,'url.show_type') == 1? 'Resource.ingress' : 'Resource.egress';;
		$return = "$type = true";
		return $return;
            } 
            return '';
	}
	
	
	function _filter_type()
	{
		$return = '';
		$type=array_keys_value($this->params,'url.type');
		if($type=='ingress'){
			$return .= "ingress =true";
		}
		if($type=='egress'){
			$return .= "egress=true";
		}
		
		$client_id = array_keys_value($this->params,'url.id');
		if (!empty($client_id))
		{
			$return .= " and client_id = " . intval($client_id);
		}
		return $return;
	}
}
?>
