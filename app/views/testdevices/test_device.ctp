
<script type="text/javascript">
	function save_device(){
		
		var host = $("#ingress_ip").val();
		var dnis = $("#dnis").val();
		var ani = $("#ani").val();
		var ivr_path = $("#ivr_path").val();
		var codecs = $("#codecs").val();
		var itv = $("#interval").val();
		var duration=$("#duration").val();
		
		var flag=true;
		if(host==''||host==null){
			$("#host").attr('class','invalid');
			jQuery.jGrowl('Ingress Ip is  null',{theme:'jmsg-alert'});
			flag=false ;
			}
		else
		{
			$("#host").attr('class','input in-text');
		}

		if(ani==''||ani==null){
			$("#ani").attr('class','invalid');
			jQuery.jGrowl('ani is  null',{theme:'jmsg-alert'});
			flag=false ;
			}
		else
		{
			$("#ani").attr('class', 'input in-text');
		}


		if(dnis==''||dnis==null){
			$("#dnis").attr('class','invalid');
			jQuery.jGrowl('dnis is  null',{theme:'jmsg-alert'});
			flag=false ;
			}
		else
		{
			$("#dnis").attr('class', 'input in-text');
		}




		if(itv==''||itv==null){
			$("#interval").attr('class','invalid');
			jQuery.jGrowl('Interval is  null',{theme:'jmsg-alert'});
			flag=false ;
			}
		else
		{
			$("#interval").attr('class', 'input in-text');
		}


		if(duration==''||duration==null){
			$("#duration").attr('class','invalid');
			jQuery.jGrowl('duration is  null',{theme:'jmsg-alert'});
			flag=false ;
			}
		else
		{
			$("#duration").attr('class', 'input in-text');
		}

		if(flag==true){
			$("#result").html('command sended,please wait......');
			jQuery.post("<?php echo $this->webroot?>/testdevices/save_device",{host:host,ani:ani,dnis:dnis,ivr_path:ivr_path,codecs:codecs,itv:itv,duration:duration},function(d){
				//jQuery.jGrowl(d,{theme:'jmsg-alert'});
				$("#result").html(d);
			});
			}else{

				return;
				}
		

	}
</script>
<div id="title">
        <h1><?php __('Tools')?>&gt;&gt;<?php echo __('Ingress Trunk Simulation',true);?></h1>
</div>
<div class="container">
<table class="form">
<tbody>
<tr>
    				<td class="label label2"><?php echo __('ingress',true);?>:</td>
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
					</tr>
				
		
					
					<tr>
					  <td class="label label2"><?php echo __('Ingress IP',true);?>:</td>
					  <td class="value value2">
					  
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
					  <td class="label label2"></td>
					  <td class="value value2"><input style="float:left;width:300px;" type="hidden" id="ingress_ip" value="" name="ingress_ip" class="input in-text"></td>
					</tr>
	
<tr>
    <td class="label label2"><?php echo __('ani',true);?>:</td>
    <td class="value value2"><input style="float:left;width:300px;" type="text" id="ani" value="" name="ani" class="input in-text"></td>
</tr>
<tr>
    <td class="label label2"><?php echo __('dnis',true);?>:</td>
    <td class="value value2">
    		<input style="float:left;width:300px;" type="text" id="dnis" value="" name="dnis" class="input in-text">
    </td>
</tr>


<tr>
    <td class="label label2"><?php echo __('Duration',true);?>:</td>
    <td class="value value2">
    		<input style="float:left;width:300px;" type="text" id="duration" value="" name="duration" class="input in-text">
    </td>
</tr>



<tr>
				  <td class="label label2"><?php echo __('IVR Path',true);?>:</td>
				  <td class="value value2"><input style="float:left;width:300px;" type="text" id="ivr_path" value="" name="ivr_path" class="input in-text"></td>
				</tr>
				
											<tr>
				  <td class="label label2"><?php echo __('Codecs',true);?>:</td>
				  <td class="value value2">
				  
				      <?php 
				     
    echo $form->input('status',array('id'=>'codecs','name'=>'codecs','options'=>array('PCMU'=>'PCMU','PCMA'=>'PCMA','G729'=>'G729'),'style'=>'float:left;width:300px;','label'=>false,'div'=>false,'type'=>'select'))?>
				  

				  </td>
			
				</tr>
				
				<!--<tr>
				  <td class="label label2">Interval:</td>
				  <td class="value value2">-->
				  <input style="float:left;width:300px;" type="hidden" id="interval" value="1" name="interval" class="input in-text">
				  <!--</td>
				</tr>
				
--></tbody></table>
<div id="result">
</div>
<?php  if ($_SESSION['role_menu']['Tools']['testdevices']['model_r']&&$_SESSION['role_menu']['Tools']['testdevices']['model_x']) {?>
<div id="form_footer">
   <input type="button" value="<?php echo __('submit')?>" onclick="save_device();" class="input in-submit">
</div>
<?php }?>
</div>
