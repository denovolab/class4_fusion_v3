<?php 
class AppCurrsHelper extends AppHelper {	
	function last_modify($list){
		if (!empty($list['Curr']['last_modify'])){
		 return date('Y-m-d H:i:s',strtotime($list['Curr']['last_modify'])+6*60*60);
		}
		return "";
	}
	function rates($list){
		return "<a href='{$this->webroot}rates/currency/{$list['Curr']['currency_id']}/currs/currency_list'  target='_blank'   style='width:100%;'> 
			<img  src='{$this->webroot}images/bOrigTariffs.gif'/>
			<span>{$list['Curr']['rates']}</span>
			</a>
		";
	}
	function active($list){
		$disable=__('disable',true);
		$active=__('active',true);	
		if ($list['Curr']['active'] == true) {
			return "<a class='active'  title='{$disable}' href='{$this->webroot}currs/disabled/{$list['Curr']['currency_id']}'>
			    		<img src='{$this->webroot}images/flag-1.png' />
			    	</a>";
		} else {
			return "<a class='disabled' title='{$active}'   href='{$this->webroot}currs/active/{$list['Curr']['currency_id']}'>
			    		<img src='{$this->webroot}images/flag-0.png' />
			    	</a>";
		}
	}
}
?>
