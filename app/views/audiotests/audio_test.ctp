

	<script type="text/javascript">
	function save_audio(){
		var res = $("#res").val();
		var host = $("#host").val();
		var orig_dnis = $("#orig_dnis").val();
		var orig_ani = $("#orig_ani").val();
		var ivr_path = $("#ivr_path").val();
		var codecs = $("#codecs").val();
		var itv = $("#interval").val();
		var flag=true;
		if(host==''||host==null){
			$("#ingress_ip").attr('class','invalid');
			jQuery.jGrowl('Ingress Ip is  null',{theme:'jmsg-alert'});
			flag=false ;
			}
		else
		{
			$("#ingress_ip").attr('class','input in-text');
		}
		
		if(orig_ani==''||orig_ani==null){
			$("#orig_ani").attr('class','invalid');
			jQuery.jGrowl('orig_ani is  null',{theme:'jmsg-alert'});
			flag=false ;
			}
		else
		{
			$("#orig_ani").attr('class','input in-text');
		}

		if(orig_dnis==''||orig_dnis==null){
			$("#orig_dnis").attr('class','invalid');
			jQuery.jGrowl('orig_dnis is  null',{theme:'jmsg-alert'});
			flag=false ;
			}
		else
		{
			$("#orig_dnis").attr('class','input in-text');
		}



		if(itv==''||itv==null){
			$("#interval").attr('class','invalid');
			jQuery.jGrowl('Interval is  null',{theme:'jmsg-alert'});
			flag=false ;
			}
		else
		{
			$("#interval").attr('class','input in-text');
		}

		

		if(flag==true){
		$("#result").html('command sended,please wait......');
		$.post("<?php echo $this->webroot?>audiotests/send_params",{res:res,host:host,orig_dnis:orig_dnis,orig_ani:orig_ani,ivr_path:ivr_path,codecs:codecs,interval:itv},function(d){
			//jQuery.jGrowl(d,{theme:'jmsg-alert'});
			$("#result").html(d);
		});
		}else{

			return;
			}
}
</script>
	<div id="title">
    <h1>Tools&gt;&gt;Egress Trunk Simulation</h1>
	</div>
	
	<div class="container">
		<!-- <form method="post" action="<?php echo $this->webroot?>audiotests/send_params">-->
			<table class="form">
				<tbody>
					<tr>
    				<td class="label label2"><?php echo __('resource')?>:</td>
    				<td class="value value2">
    						<select id="res" name="res" style="float:left;width:300px;" class="input in-select" onchange="getIngress('<?php echo $this->webroot?>',this);">
	    						<?php 
	    							$loop = count($ingress);
	    							for ($i = 0; $i<$loop;$i++) {
	    							?>
	    							<option value="<?php echo $ingress[$i][0]['resource_id']?>"><?php echo $ingress[$i][0]['name']?></option>
	    						<?php }?>
    						</select>
    				</td>
					</tr><!--
				
					<tr>
					  <td class="label label2"><?php echo __('ing_ip')?>:</td>
					  <td class="value value2"></td>
					</tr>
					
					--><tr>
					  <td class="label label2"><?php echo __('host_ip')?>:</td>
					  <td class="value value2">
					  <input style="float:left;width:300px;" type="hidden" id="ingress_ip" value="" name="ingress_ip" class="input in-text">
					    <select id="host" name="host" onchange="changeIngress(this);" style="float:left;width:300px;" class="input in-select">
					    
					        						<?php 
	    							$loop = count($host);
	    							for ($i = 0; $i<$loop;$i++) {
	    							?>
	    							<option value="<?php echo $host[$i][0]['ip']?>"><?php echo $host[$i][0]['ip']?>:<?php echo $host[$i][0]['port']?></option>
	    						<?php }?>
					    </select>
					  </td>
					</tr>
	
					<tr>
					  <td class="label label2"><?php echo __('origdnis')?>:</td>
					  <td class="value value2"><input style="float:left;width:300px;" type="text" id="orig_dnis" value="" name="orig_dnis" class="input in-text"></td>
					</tr>
	
				<tr>
				  <td class="label label2"><?php echo __('origani')?>:</td>
				  <td class="value value2"><input style="float:left;width:300px;" type="text" id="orig_ani" value="" name="orig_ani" class="input in-text"></td>
				</tr>
				
				
								<tr>
				  <td class="label label2"><?php echo __('IVR Path',true);?>:</td>
				  <td class="value value2"><input style="float:left;width:300px;" type="text" id="ivr_path" value="" name="ivr_path" class="input in-text"></td>
				</tr>
				
											<tr>
				  <td class="label label2"><?php echo __('Codecs',true);?>:</td>
				  <td class="value value2">
				  
				      <?php 
    echo $form->input('status',array('id'=>'codecs','name'=>'codecs','options'=>$codecs,'style'=>'float:left;width:300px;','label'=>false,'div'=>false,'type'=>'select'))?>
				  

				  </td>
			
				</tr>
				
				<tr>
				  <td class="label label2"><?php echo __('duration',true);?>:</td>
				  <td class="value value2"><input style="float:left;width:300px;" type="text" id="interval" value="" name="interval" class="input in-text"></td>
				</tr>
				
			</tbody>
		</table>
		<div id="result"></div>
        <?php  if ($_SESSION['role_menu']['Tools']['audiotests']['model_r']&&$_SESSION['role_menu']['Tools']['audiotests']['model_x']) {?>
		<div id="form_footer">
    <input  type="button" value="<?php echo __('submit')?>" onclick="save_audio();" class="input in-submit">
    <!--<input  type="reset"   style="margin-left: 20px;" value="<?php echo __('reset')?>" class="input in-submit">
 		--></div>
        <?php }?>
	<!-- </form>-->	
</div>


<script type="text/javascript">
<!--
//$(document).ready(function(){
//$('form').submit(function(){
//
//	var f=true;
//if($('#host').val()==''||$('#host').val()==null){
//		$('#host').addClass('invalid');
//		jQuery.jGrowl(' Host must  IP Address only.',{theme:'jmsg-error'});
//		f=false;
//
//	
//}
//return f;
//	
//});
//
//
//	
//});
//-->
</script>
<?php
	$rs = $session->read('audio_res');
	if (!empty($rs)) {
		$session->del('audio_res');
?>
		<script type="text/javascript">
			//<![CDATA[
				jQuery.jGrowl("<?php echo $rs?>",{theme:'jmsg-alert'});
			//]]>
		</script>
<?php
	} 
?>

<?php
			$backform = $session->read('backform');//用户刚刚输入的表单数据
			if (!empty($backform)) {
				$session->del('backform');//清除错误信息

	
			?>
					<script type="text/javascript">
						//<![CDATA[
					$('#res').val('<?php echo $backform['res']?>');
					$('#orig_dnis').val('<?php echo $backform['orig_dnis']?>');
					$('#orig_ani').val('<?php echo $backform['orig_ani']?>');
					$('#host').val('<?php echo $backform['ingress_ip']?>');
				
						
						//]]>
					</script>
<?php 
			 
			}
?>
