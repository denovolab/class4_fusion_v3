<style>
#optional_col span{
	
}
</style>
<div id="title" style="text-align: justify;">
	<ul style="padding-top:10px;display: inline;  z-index:1;list-style-type:none"><h1> <span><?php echo __('Trunk Management',true);?></span> </h1>	</ul>
	<?php echo $this->element('search')?>
</div>
<div class="container" >
	<!--
    <div align="left">
	<table>
	<tr>
	<td style="text-align: center;">
	<form action="" method="GET">
			<?php //echo $form->submit('',array('label'=>false,'div'=>false,'class'=>"input in-submit"))?>
	</form>
	</td>			
		</tr>
        <tr><td>&nbsp;</td></tr>
		</table>
	</div>
    -->
	<div id="toppage"></div>
	
<!-- list -->
<?php 
$mydata =$p->getDataArray();
$loop = count($mydata);
if(empty($mydata)):?>
	<?php echo $this->element('common/no_result')?>
<?php else:?>
<div style="clear:both;"></div>
<table class="list" style="margin-top:5px;">
	<thead>
		<tr>
			<td><?php echo __('carrier',true);?></td>
			<td><?php echo __('alias',true);?></td>			
			<td><?php echo __('type',true);?></td>	
			<td><?php echo __('host',true);?></td>
			<td><?php echo __('tech_prefix',true);?></td>
			<td><?php echo __('create_time',true);?></td>
			<td><?php echo __('update_time',true);?></td>
			<?php  if ($_SESSION['role_menu']['Exchange Manage']['user_trunks']['model_w']) {?>
            <td><?php echo __('status',true);?></td>
            <?php }?>
		</tr>
	</thead>
	<tbody>	
<?php for ($i=0;$i<$loop;$i++){
$res = $mydata[$i];
	
?>
		<tr rel="tooltip" id="res_<?php echo array_keys_value($res,"0.resource_id")?>">
		<td><?php echo array_keys_value($res,"0.client_name")?></td>
		<td><?php echo array_keys_value($res,"0.alias")?></td>
		<td><?php echo array_keys_value($res,"0.ingress") == 't' ? 'Ingress' : 'Egress';?></td>	
			<td><?php echo trim(array_keys_value($res,"0.ip_port"), "{}" );?></td>
			<td><?php echo trim(array_keys_value($res,"0.tech_prefix"), "{}" )?></td>
			<td><?php echo array_keys_value($res,"0.create_time")?></td>
			<td><?php echo array_keys_value($res,"0.update_time")?></td>
			<?php  if ($_SESSION['role_menu']['Exchange Manage']['user_trunks']['model_w']) {?>
            <td>
			<?php if($mydata[$i][0]['active']==1){?>
								    <a onclick="return confirm('<?php echo __('confirmdisablegate')?>');"  
									      href="<?php echo $this->webroot?>gatewaygroups/dis_able/<?php echo $mydata[$i][0]['resource_id']?>/view_egress" title="<?php echo __('disable')?>">
								    	 		<img  title="<?php echo __('wangtodisable')?>" src="<?php echo $this->webroot?>images/flag-1.png">
								  		</a>
								   	<?php }else{?>
								     <a  onclick="return confirm('<?php echo __('confirmactivegate')?>');"  
									      href="<?php echo $this->webroot?>gatewaygroups/active/<?php echo $mydata[$i][0]['resource_id']?>/view_egress" title="<?php echo __('disable')?>">
												<img  title="<?php echo __('wangtoactive')?>" src="<?php echo $this->webroot?>images/flag-0.png">
											</a>
			<?php }?>
			</td>
            <?php }?>
	</tr>
	<?php }?>
	</tbody>
</table>
<?php endif;?>
			
<!-- list end -->	
	
	<div id="tmppage">
	<?php echo $this->element('page');?>
	</div>
</div>
<script type="text/javascript">
(function($){
		$(document).ready(function(){
			$('#optional_col input[type=checkbox]').bind('click',function(){
				if(this.checked){
					$("td[rel=order_list_col_"+this.value+"]").show();
				}else{
					$("td[rel=order_list_col_"+this.value+"]").hide();
				}
				var val = this.checked ? 'true' : 'false';
				var col = this.value;
				App.Common.updateDivByAjax("<?php echo Router::url(array('plugin'=> $this->plugin,'controller'=>$this->params['controller'],'action'=>'ajax_def_col'))?>","none",{'action':'browsers','col_name':col,'value':val});
			});	
		});
	}
)(jQuery);
</script>
