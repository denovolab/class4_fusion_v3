<style>
#optional_col span{
	
}
</style>
<div id="title" style="text-align: justify;">
	<ul style="padding-top:10px;display: inline;  z-index:1;list-style-type:none"><h1>Exchange Manage&gt;&gt;Direct Seller Enrollment</h1>	</ul>
</div>
<div class="container" >
<!--
	<div align="left">
	<table>
	<tr>
	<td style="text-align:center; padding-bottom:10px;">
	<form action="" method="GET">
			<?php //echo $form->submit('',array('label'=>false,'div'=>false,'class'=>"input in-submit"))?>
	</form>
	</td>			
		</tr>
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
			<td><?php echo __('Request Date',true);?></td>
			<td><?php echo __('Company Name',true);?></td>			
			<td><?php echo __('Account Name',true);?></td>
			<?php  if ($_SESSION['role_menu']['Exchange Manage']['sellers']['model_w']) {?>
            <td><?php echo __('action',true);?></td>
            <?php }?>
		</tr>
	</thead>
	<tbody>	
<?php for ($i=0;$i<$loop;$i++){
$res = $mydata[$i];
	
?>
		<tr rel="tooltip" id="res_<?php echo $mydata[$i][0]["dse_id"];?>">
		<td><?php echo $mydata[$i][0]["request_time"];?></td>
		<td><?php echo $mydata[$i][0]["company_name"];?></td>
		<td><?php echo $mydata[$i][0]["name"];?></td>
		<?php  if ($_SESSION['role_menu']['Exchange Manage']['sellers']['model_w']) {?>
        <td>
		<?php if($mydata[$i][0]['action']==1){?>
								    <a onclick="return confirm('Are You Sure Disapprove This Direct Seller Enrollment');"  
									      href="<?php echo $this->webroot?>order/sellers/dis_able/<?php echo $mydata[$i][0]['dse_id']?>" title="<?php echo __('disable')?>">
								    	 		<img  title="<?php echo __('wangtodisable')?>" src="<?php echo $this->webroot?>images/flag-1.png">
								  		</a>
								   	<?php }else{?>
								     <a  onclick="return confirm('Are You Sure Approve This Direct Seller Enrollment');"  
									      href="<?php echo $this->webroot?>order/sellers/active/<?php echo $mydata[$i][0]['dse_id']?>" title="<?php echo __('disable')?>">
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
