<style type="text/css">
.in-text, .in-password, .in-textarea, .value select, .value textarea, .value .in-text, .value .in-password, .value .in-textarea, .value .in-select{ width:250px;}
</style>
<?php  #pr($name_join_arr); ?>
<div id="title">
   <h1><?php echo __('Monitoring',true);?>&gt;&gt;<?php echo __('Add Rule',true);?>
         <font class="editname" title="Name">   <?php echo empty($pp['name'])||$pp['name']==''?'':"[".$pp['name']."]" ?></font>
   
   </h1>
   <ul id="title-menu">
		<li>
			<?php echo $this->element('xback',Array('backUrl'=>'alerts/rule'))?>
		</li>
  	</ul>
</div>
<div class="container">

<?php $id=array_keys_value($this->params,'pass.0')?>
<?php echo $form->create ('Alert', array ('action' =>'add_rule', 'onsubmit'=>"return save_rule();"));?>

	<table width="100%">
		<col width="50%"/>
		<col width="50%"/>
		<tbody>
			<tr>
				<td class="first" style="vertical-align:top;">
    				<table class="form">
    					<tbody>
    						<tr>
        						<td class="label label2"><?php echo __('Rule name')?>:</td>
        						<td class="value value2">
        						<input  type="hidden"   id="action_id" name="action_id" value="<?php echo array_keys_value($this->params,'pass.0'); ?>">  
        						
        							<?php echo $form->input('name',array('label'=>false ,'div'=>false,'type'=>'text','maxLength'=>'100', 'value'=>empty($pp['name'])?'':$pp['name']));?>
        							</td>
    						</tr>
    						<?php
                                                /*
    						<tr>
        						<td class="label label2"><?php echo __('Switch Ip')?>:</td>
        						<td class="value value2">
        						<?php echo $form->input('switch_ip',array('label'=>false ,'div'=>false,'type'=>'text','maxLength'=>'66', 'value'=>empty($pp['switch_ip'])?'':$pp['switch_ip']));?>
        							</td>
    						</tr>
                                                 * 
                                                 */?>
    						<tr>
        						<td class="label label2"><?php echo __('Monitor For')?>:</td>
        						<td class="value value2">
        						<?php 
 							 //$tmp=array('0'=>'Inbound Trunk','1'=>'Outbound Trunk','2'=>'Both');
                                                         $tmp=array('1'=>'Inbound Trunk','0'=>'Outbound Trunk', '2' => 'Entire System');
         		echo $form->input('is_origin',
 		array('options'=>$tmp, 'label'=>false , 'div'=>false, 'type'=>'select','class'=>'input in-text', 'selected'=>isset($pp['is_origin']) ? $pp['is_origin'] : '0', 'onchange'=>"fill_res();"));
    ?>


    									</td>
    						</tr> 
    						
    						<tr class="control_tr">
        						<td class="label label2"><?php echo __('Trunk')?>:</td>
        						<td class="value value2">
                                                            
        						<div>
                                                            
        									<div id="res_0" style="display:<?php
                                                                                    if(!isset($pp['is_origin'])||$pp['is_origin'] == 'true') {
                                                                                        echo 'block';
                                                                                    } else {
                                                                                        echo 'none';
                                                                                    }
                                                                                ?>;">
        						<?php 
 							 echo $form->input('res_id',
 		array('options'=>$name_join_arr['resource_ingress'], 'label'=>false , 'div'=>false,  'type'=>'select', 'class'=>'input in-text', 'selected'=>empty($pp['res_id'])?0:$pp['res_id'], 'onchange'=>"fill_host_0();"));
    ?>
                                                                       
                                                                                
                                                                                </div>
     <div id="res_1" style="display:<?php
                        if(isset($pp['is_origin'])&&$pp['is_origin'] == 'false') {
                            echo 'block';
                        } else {
                            echo 'none';
                        }
                    ?>;">
    <?php 
    echo $form->input('res_id_1',
 		array('options'=>$name_join_arr['resource_egress'], 'label'=>false , 'div'=>false, 'type'=>'select', 'class'=>'input in-text', 'selected'=>empty($pp['res_id'])?0:$pp['res_id'], 'onchange'=>"fill_host_1();"));
 		?>
    </div>
                               
                                                            
    <div id="res_2" style="display:<?php
                        if(isset($pp['is_origin'])&&intval($pp['is_origin']) == 2) {
                            echo 'block';
                        } else {
                            echo 'none';
                        }
                    ?>;">
    <?php 
    echo $form->input('res_id_2',
 		array('options'=>$name_join_arr['resource_all'], 'label'=>false , 'div'=>false, 'style'=>'width:150px;', 'type'=>'select', 'class'=>'input in-text', 'selected'=>empty($pp['res_id'])?0:$pp['res_id'], 'onchange'=>"fill_host_3();"));
 		?>
    </div>
    									</td>
    						</tr> 
    						
    						<tr class="control_tr">
        						<td class="label label2"><?php echo __('Host')?>:</td>
        						<td class="value value2">
<?php 
echo $form->input('host_id',
array('options'=>$name_join_arr['host_port'], 'label'=>false , 'div'=>false, 'type'=>'select', 'class'=>'input in-text', 'selected'=>empty($pp['host_id'])?0:$pp['host_id']));
?>
    							
    			<?php 
 							# echo $form->input('port_id', 		array('options'=>$name_join_arr['port'], 'label'=>false , 'div'=>false, 'type'=>'select', 'class'=>'input in-text', 'selected'=>empty($pp['port_id'])?0:$pp['port_id']));
    ?>
    									</td>
    						</tr> 
                                                
                                                
                                                <tr class="control_tr">
                                                    <td class="label label2"><?php echo __('Type')?>:</td>
                                                    <td class="value value2">
<input type="radio" name="data[Alert][monitor_type]" class="monitor_type" value="0"  <?php echo (isset($pp['monitor_type']) && $pp['monitor_type'] == 0) || !isset($pp['monitor_type']) ?'checked' : '' ?> /><label>by code</label>
<input type="radio" name="data[Alert][monitor_type]" class="monitor_type" value="1" <?php 
       if(isset($pp['monitor_type'])) {
            if($pp['monitor_type'] == 1) echo 'checked';
       } 
?> /><label>by code name</label>
                                                    </td>
                                                </tr>
                           
    						<tr class="control_tr">
        						<td class="label label2"><?php echo __('Caller ID')?>:</td>
        						<td class="value value2">
<?php
echo $form->input('apply_type',array('label'=>false ,'div'=>false,'options' => array('Apply To All','Apply Specific Ani','Exclude Specific Ani'), 'value'=>isset($pp['apply_type'])?$pp['apply_type']:0, 'style' => 'width:100px;'));
?>                                                                        
        						<?php
        						echo $form->input('ani',array('label'=>false ,'div'=>false,'type'=>'text', 'value'=>isset($pp['ani'])?$pp['ani']:'', 'style' => 'width:150px;'));
        						?>
<?php

echo $form->input('source_code_name',
            array( 'label'=>false , 'div'=>false, 'type'=>'text', 'class'=>'input in-text', 'value'=>isset($pp['source_code_name'])?$pp['source_code_name']:''));
?>
    									</td>
    						</tr> 
                            
    						
    						<tr class="control_tr">
        						<td class="label label2"><?php echo __('Destination')?>:</td>
        						<td class="value value2">
        						<?php
        						echo $form->input('dnis',array('label'=>false ,'div'=>false,'type'=>'text','maxLength'=>'66', 'value'=>isset($pp['dnis'])?$pp['dnis']:''));
        						?>
                                                        
<?php

$selected_codenames = array();

if(isset($pp['destination_code_name'])) {
    $selected_codenames = explode(',', $pp['destination_code_name']);
} 

?>                   

<select id="AlertDestinationCodeName" class="input in-text in-select select" multiple="multiple" style="height: 150px; display: inline;" name="data[Alert][destination_code_name][]">
    <?php 
        
    foreach($codenames as $codename):
   
    ?>
    <option <?php if(in_array($codename[0]['name'], $selected_codenames)) echo 'selected'; ?>><?php echo $codename[0]['name']; ?></option>
    <?php 
        
    endforeach;
    
    ?>
</select>
        							
    									</td>
    						</tr> 
                                                <tr>
                                                    <td class="label2">Resend If Problem Persists After:</td> 
                                                    <td class="value2"><?php echo $form->input('mail_duration',array('label'=>false ,'style' => 'width:100px;','div'=>false,'type'=>'text','maxLength'=>'66', 'value'=>isset($pp['mail_duration'])?$pp['mail_duration']:'')); ?> hour</td>
                                                </tr>
    						
						</tbody>
					</table>
				</td>
				
				
					<td class="last" style="vertical-align:top;">
    				<table class="form">
    					<tbody>
    							<tr><td>&nbsp;</td></tr>
    						<tr>
        						<td class="label label2"><?php echo __('Alert Condition')?>:</td>
        						<td class="value value2">
        						<?php 
 							 echo $form->input('alert_condition_id',
 		array('options'=>$name_join_arr['condition'], 'label'=>false , 'div'=>false, 'type'=>'select', 'class'=>'input in-text', 'selected'=>empty($pp['alert_condition_id'])?0:$pp['alert_condition_id']));
    ?>&nbsp;&nbsp;<a href="<?php echo $this->webroot; ?>alerts/add_condition?flag=1"><img src="<?php echo $this->webroot; ?>images/add.png" /></a>
    									</td>
    						</tr>     						
    						<tr>
        						<td class="label label2"><?php echo __('Alert Action')?>:</td>
        						<td class="value value2">
        						<?php 
 							 echo $form->input('alert_action_id',
 		array('options'=>$name_join_arr['action'], 'label'=>false , 'div'=>false, 'type'=>'select', 'class'=>'input in-text', 'selected'=>empty($pp['alert_action_id'])?0:$pp['alert_action_id']));
    ?>&nbsp;&nbsp;<a href="<?php echo $this->webroot; ?>alerts/add_action?flag=1"><img src="<?php echo $this->webroot; ?>images/add.png" /></a>
    									</td>
    						</tr> 
    						
    						</tr> 
    						
    						<tr>
        						<td class="label label2"><?php echo __('Frequency')?>:</td>
        						<td class="value value2">
        						<?php 
 							 $tmp=array('0' => 'Never', '1'=>'Minutes','2'=>'Day(s) of Week');
         		echo $form->input('freq_type',
 		array('options'=>$tmp, 'label'=>false , 'div'=>false, 'type'=>'select','style'=>'float:block;width:inherit;width:250px;' ,'class'=>'input in-text', 'selected'=>empty($pp['freq_type'])?0:$pp['freq_type'], 'onchange'=>"change_freq();"));
    ?>
                                                            
                                                            <a href="###" onclick="return FALSE;" id="addtime"> <img src="<?php echo $this->webroot; ?>images/add.png"></a>
    							<?php
                                                        /*
                                                        </td>
    						</tr> 
    						<tr>
        						<td class="label label2"><?php echo __('Freq Value')?>:</td>
        						<td class="value value2">
                                                         * 
                                                         */?>
        									<div style="display:block;">
        									<div id="fv_0" style="display:none;">
        						<?php 
        						echo $form->input('freq_value_0',array('label'=>false ,'div'=>false,'type'=>'text','maxLength'=>'66', 'value'=>empty($pp['freq_value'])?60:$pp['freq_value']));
        						?>
        						</div>
        						<div id="fv_1" style="display:block;">
        						<?php 
                                                        /*
 							 $tmp=array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');
         		echo $form->input('freq_value',
 		array('options'=>$tmp, 'label'=>false , 'div'=>false, 'type'=>'select', 'class'=>'input in-text', 'style'=>"display:inline;width:220px;",'selected'=>empty($pp['freq_value'])?0:$pp['freq_value']));
    */?>
        <?php
            $tmp=array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');
            $pp['freq_value'] = isset($pp['freq_value']) ? $pp['freq_value'] : "";
            if(isset($pp['weekday_time'])) {
                $week_time_arr = explode('!', $pp['weekday_time']);
                $weeks = explode(',', $week_time_arr[0]);
                $times = explode(',', $week_time_arr[1]);
            } else {
                $weeks = '';
                $times = '';
            }
            
        ?>
     
            <?php for($de = 0;$de < count($weeks); $de++):  ?>
                                                            <?php
                                                            
                                                            ?>
       <li class="timelay"> 
            <select name="data[Alert][week][]" style="width:125px;">
<!--            <option value="0">Sunday</option>
                <option value="1">Monday</option>
                <option value="2">Tuesday</option>
                <option value="3">Wednesday</option>
                <option value="4">Thursday</option>
                <option value="5">Friday</option>
                <option value="6">Saturday</option>  -->
                <?php
                for($i=0; $i<count($tmp); $i++) {
                    $flagw = ($i == $weeks[$de]) ? 'selected="selected"' : '';
                    echo "<option value=\"{$i}\" {$flagw}>{$tmp[$i]}</option>";
                }
                ?>
            </select>
            
           
            
            <select name="data[Alert][time][]" style="width:125px;">
                <?php
                for($i=0;$i<=23;$i++) {
                    $flag1 = sprintf("%02d:00", $i);
                    $flag2 = sprintf("%02d:30", $i);
                    $flag3 = ($flag1 == $times[$de]) ? 'selected="selected"' : '';
                    $flag4 = ($flag2 == $times[$de]) ? 'selected="selected"' : '';
                    print("<option values=\"{$flag1}\" {$flag3}>{$flag1}</option>");
                    print("<option values=\"{$flag1}\" {$flag4}>{$flag2}</option>");
                }
                 ?>
            </select>
                                                                
            <a href="###" onclick="return FALSE;" class="deletetime">
                <img width="16" height="16" src="<?php echo $this->webroot; ?>images/delete.png">
            </a>
        </li>
            
        <?php endfor; ?>
        							</div></div>
    									</td>
    						</tr> 
    						<tr>
        						<td class="label label2"><?php echo __('Sample Size')?>:</td>
        						<td class="value value2">
   									
<?php 
$size_options = array('1'=>'1','2'=>'2','3'=>'3','4'=>'4','5'=>'5',
        '10'=>'10','15'=>'15','20'=>'20','25'=>'25','30'=>'30', '35'=>'35', '40'=>'40','45'=>'45','60'=>'60', '120'=>'120', '180'=>'180');
echo $form->input('sample_size',array('label'=>false ,'options'=>$size_options,'type'=>'select','div'=>false,'maxLength'=>'66', 'selected'=>empty($pp['sample_size'])?0:$pp['sample_size']));
?>        min

						
    									</td>
    						</tr> 
                                                
                                                <tr>
                                                    <td class="label label2"><?php echo __('Min Call Threshold')?>:</td>
                                                    <td class="value value2">
                                                        
                                                    <?php 
                                                    echo $form->input('min_call_time',array('label'=>false ,'div'=>false,'type'=>'text','maxLength'=>'66', 'value'=>empty($pp['min_call_time'])?0:$pp['min_call_time']));
                                                    ?>
                                                            
                                                            
                                                    </td>
                                                </tr> 
                                                
                                                
    						
						</tbody>
					</table>
				</td>
			</tr>
		</tbody>
	</table>
<div id="form_footer">
            <input type="submit" value="<?php echo __('submit')?>" class="input in-submit"/>
</div>
<?php echo $form->end();?>
<script type="text/javascript">
jQuery(document).ready(function(){			
	change_freq();
    //jQuery('#AlertAddConditionForm').submit(function(){ 
   //     return true;
     //});
  


 });     

function change_freq()
{
	var comp = $("#AlertFreqType").val();
	if (comp == 1)
	{
		$("#fv_0").show();
		$("#fv_1").hide();
                $('#addtime').hide();
	}
	if (comp == 2)
	{
		$("#fv_1").show();
		$("#fv_0").hide();
                $('#addtime').show();
	}
        
        if (comp == 0)
	{
		$("#fv_1").hide();
		$("#fv_0").hide();
                $('#addtime').hide();
	}
}

function save_rule()
{
	
	var flag = true;
	var action_name = $("#AlertName").val();

	if(action_name==''||action_name==null){
		$("#AlertName").attr('class','input in-input in-text');
		jQuery.jGrowl('Action Name,allowed characters:a-z,A-Z,0-9,-,_,space,maximum of 16 characters in length! ',{theme:'jmsg-alert'});
		flag=false ;

		}
	var AlertSwitchIp = jQuery('#AlertSwitchIp').val();
	if(/[^0-9A-Za-z-\_\. ]+/.exec(action_name)){
		$("#AlertName").attr('class','input in-input in-text');
		jQuery.jGrowl('Action Name,allowed characters:a-z,A-Z,0-9,-,_,space,maximum of 16 characters in length! ',{theme:'jmsg-alert'});
		flag=false ;
	}
	else
	{
		//$("#AlertName").attr('class','input in-text');
	}


	if(AlertSwitchIp!=''  &&  AlertSwitchIp!=null){

		
  if(! /^(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])(\.(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])){3}$/.test(AlertSwitchIp)){
		jQuery("#AlertSwitchIp").attr('class','input in-input in-text');
		jQuery.jGrowl('IPs must be a valid format. The following IPs are not valid: <<192.168.500.600、a.b.c.d>>',{theme:'jmsg-alert'});
		flag=false ;
	}else{
  	jQuery("#AlertSwitchIp").attr('class','input in-input in-text');
	}
	}
	var ani = $("#AlertAni").val();
	if (ani == '')
	{
//		jQuery("#AlertAni").attr('class','invalid');
//		jQuery.jGrowl('Ani can not be empty',{theme:'jmsg-alert'});
//		flag=false ;
	}
	else
	{
		jQuery("#AlertAni").attr('class','input in-text');
	}

	var dnis = $("#AlertDnis").val();
	if (dnis == '')
	{
//		jQuery("#AlertDnis").attr('class','invalid');
//		jQuery.jGrowl('Dnis can not be empty',{theme:'jmsg-alert'});
//		flag=false ;
	}
	else
	{
		jQuery("#AlertDnis").attr('class','input in-text');
	}
	
        var AlertAlertConditionId = $('#AlertAlertConditionId').val();
        
        
        if(AlertAlertConditionId == null)
        {
            flag = false;
            jQuery.jGrowl('You need select a item of the Alert Condition!',{theme:'jmsg-alert'});
        }
        
        var AlertAlertActionId = $('#AlertAlertActionId').val();
        
        if(AlertAlertActionId == null)
        {
            flag = false;
            jQuery.jGrowl('You need select a item of the Alert Action!',{theme:'jmsg-alert'});
        }
	
	return flag;
}
function fill_res()
{
	var is_orig = parseInt($("#AlertIsOrigin").val());
        
        $('.control_tr').show();
        
	if (is_orig == 0) {
            $('#res_0').hide();
            $('#res_1').show();
            $('#res_2').hide();
        } else if (is_orig == 1) {
            $('#res_0').show();
            $('#AlertResId').change();
            $('#res_1').hide();
            $('#res_2').hide();
        } else if (is_orig == 2) {
            //$('#res_0').hide();
            //$('#res_1').hide();
            //$('#res_2').show();
            $('.control_tr').hide();
        }
}
function fill_host_0()
{
	var res_id = $("#AlertResId").val();
	$.getJSON("<?php echo $this->webroot;?>alerts/find_host/",	{act:'getHost', res_id:res_id},function(d){
		 			$("#AlertHostId").empty();	
		 			if (d!='')
		 			{
		 				$("<option value='0'>All</option>").appendTo("#AlertHostId");
		 			}
				 	$.each(d,function(idx,item){
					$("<option value='"+idx+"'>"+item+"</option>").appendTo("#AlertHostId");
				});
	});
}

function fill_host_1()
{
	var res_id = $("#AlertResId1").val();
	$.getJSON("<?php echo $this->webroot;?>alerts/find_host/",	{act:'getHost', res_id:res_id},function(d){
		 		$("#AlertHostId").empty();	
		 		if (d!='')
	 			{
		 			$("<option value='0'>All</option>").appendTo("#AlertHostId");
	 			}
				$.each(d,function(idx,item){
					$("<option value='"+idx+"'>"+item+"</option>").appendTo("#AlertHostId");
				});
	});
}

function fill_host_3()
{
	var res_id = $("#AlertResId2").val();
	$.getJSON("<?php echo $this->webroot;?>alerts/find_host/",	{act:'getHost', res_id:res_id},function(d){
		 		$("#AlertHostId").empty();	
		 		if (d!='')
	 			{
		 			$("<option value='0'>All</option>").appendTo("#AlertHostId");
	 			}
				$.each(d,function(idx,item){
					$("<option value='"+idx+"'>"+item+"</option>").appendTo("#AlertHostId");
				});
	});
}


$(function() {
    
    
    
    $('#addtime').click(function() {
        $('.timelay').eq(0).clone(true).appendTo('#fv_1');
    });
    
    $('.deletetime').live('click', function() {
        if($('.timelay').size() > 1) {
            $(this).parent().remove();
        }
    });
    
    
    $('#AlertApplyType').change(function() {
        if ($(this).val() == 0) 
        {
            $('#AlertAni').hide();
        }
        else
        {
            $('#AlertAni').show();
        }
    });


    /*
    $('#AlertResId').change(function() {
        var $prefix = $('#AlertIngressTrunkPrefix');
        var res_id = $(this).val();
        $.ajax({
            'url'  : '<?php echo $this->webroot ?>alerts/get_ingress_prefix',
            'type' : 'POST',
            'dataType' : 'json',
            'data' : {'res_id':res_id},
            'success' : function(data) {
                $prefix.empty();
                $.each(data, function(index, val) {
                    $prefix.append('<option value="' + val[0]['id'] +'">' + val[0]['tech_prefix'] + '</option>');
                });
                $('#AlertIngressTrunkPrefix').change();
            }
        });
    });
    */
    
    $('input.monitor_type').change(function() {
        var val = $(this).val();
        if (val == 0) {
            $('#AlertAni').show();
            $('#AlertApplyType').show();
            $('#AlertDnis').show();
            $('#AlertSourceCodeName').hide();
            $('#AlertDestinationCodeName').hide();
        } else {
            $('#AlertAni').hide();
            $('#AlertApplyType').hide();
            $('#AlertDnis').hide();
            $('#AlertSourceCodeName').show();
            $('#AlertDestinationCodeName').show();
        }
    });
    
    
    <?php
        if ((isset($pp['monitor_type']) && $pp['monitor_type'] == 0) || !isset($pp['monitor_type']))
            echo "$('#AlertAni').show();
            $('#AlertApplyType').show();
            $('#AlertApplyType').change();
            $('#AlertDnis').show();
            $('#AlertSourceCodeName').hide();
            $('#AlertDestinationCodeName').hide();";
        else if(isset($pp['monitor_type']) && $pp['monitor_type'] == 1)
            echo "$('#AlertAni').hide();
             $('#AlertApplyType').hide();
            $('#AlertDnis').hide();
            $('#AlertSourceCodeName').show();
            $('#AlertDestinationCodeName').show();";
        
    ?>
    
    
    /*
    $('#AlertIngressTrunkPrefix').change(function() {
        var prefix_id = $(this).val();
        var $AlertSourceCodeName = $('#AlertSourceCodeName');
        var $AlertDestinationCodeName = $('#AlertDestinationCodeName');
        
        $.ajax({
            'url'  : '<?php echo $this->webroot ?>alerts/get_code_name_by_prefix',
            'type' : 'POST',
            'dataType' : 'json',
            'data' : {'prefix_id':prefix_id},
            'success' : function(data) {
                $AlertSourceCodeName.empty();
                $AlertDestinationCodeName.empty();
                $.each(data, function(index, val) {
                    $AlertSourceCodeName.append('<option>' + val[0]['code_name'] + '</option>');
                    $AlertDestinationCodeName.append('<option>' + val[0]['code_name'] + '</option>');
                });
            }
        });
        
    });
    */
    
    
    
    fill_res();
    
    <?php
        /*
        if(isset($this->params['pass'][0]))
        {
            echo <<<EOT
            $('#AlertIngressTrunkPrefix option[value={$pp['ingress_trunk_prefix']}]').attr('selected', true);
            
            $('#AlertSourceCodeName option[value="{$pp['source_code_name']}"]').attr('selected', true);
            
            $('#AlertDestinationCodeName option[value="{$pp['destination_code_name']}"]').attr('selected', true);
EOT;

        }
        */
    ?>		

});
</script>
  	
</div>