<?php $form->create('Timeprofile')?>
<table>
	<tr>
<!--		<td>
		</td>-->
		<td><?php echo $xform->input('name',Array('maxLength'=>256,'style'=>'width:140px'))?></td>
		<td>
				<?php echo $xform->input('type',Array('onchange'=>'typechange(this)','options'=>Array('0'=>'all time','1'=>'weekly','2'=>'daily')))?>
				<script type="text/javascript">
				function typechange(obj){
					if(jQuery(obj).val()==0){
						jQuery('#TimeprofileStartWeek,#TimeprofileEndWeek,#TimeprofileStartTime,#TimeprofileEndTime').hide().val('');
                                                jQuery("#TimeprofileTimeZone").hide();
					}
					if(jQuery(obj).val()==2){
						jQuery('#TimeprofileStartTime,#TimeprofileEndTime,#TimeprofileTimeZone').show();
						jQuery('#TimeprofileStartWeek,#TimeprofileEndWeek').hide().val('');
					}
					if(jQuery(obj).val()==1){
						jQuery('#TimeprofileStartWeek,#TimeprofileEndWeek,#TimeprofileStartTime,#TimeprofileEndTime,#TimeprofileTimeZone').show();
					}
				}
				</script>
		</td>
		<td>
      <?php echo $xform->input('start_week',Array('options'=>Array('7'=>'Sunday','1'=>'monday','2'=>'tuesday','3'=>'Wednesday','4'=>'Thursday','5'=>'Friday','6'=>'Saturday')))?>
		</td>
		<td>
				<?php echo $xform->input('end_week',Array('options'=>Array('7'=>'Sunday','1'=>'monday','2'=>'tuesday','3'=>'Wednesday','4'=>'Thursday','5'=>'Friday','6'=>'Saturday')))?>
		</td>
		<td>
				<?php echo $xform->input('start_time',Array('readonly'=>'readonly','type'=>'text','class'=>'Wdate','onfocus'=>"WdatePicker({dateFmt:'HH:mm'});",'realvalue'=>'00:00:00','style'=>'width:120px'))?>
		</td>
		<td>
				<?php echo $xform->input('end_time',Array('readonly'=>'readonly','type'=>'text','class'=>'Wdate','onfocus'=>"WdatePicker({dateFmt:'HH:mm'});",'realvalue'=>'00:00:00','style'=>'width:120px'))?>
		</td>
                <td>
                    
                                <?php echo $xform->input('time_zone',Array('options'=>Array('-12'=>'GMT -12:00','-11'=>'GMT -11:00','-10'=>'GMT -10:00','-09'=>'GMT -09:00',
                                                                            '-08'=>'GMT -08:00','-07'=>'GMT -07:00','-06'=>'GMT -06:00',
                                                                            '-05'=>'GMT -05:00','-04'=>'GMT -04:00','-03'=>'GMT -03:00',
                                                                            '-02'=>'GMT -02:00','-01'=>'GMT -01:00','+00'=>'GMT +00:00',
                                                                            '+01'=>'GMT +01:00','+02'=>'GMT +02:00','+03'=>'GMT +03:00',
                                                                            '+04'=>'GMT +04:00','+05'=>'GMT +05:00','+06'=>'GMT +06:00',
                                                                            '+07'=>'GMT +07:00','+08'=>'GMT +08:00','+09'=>'GMT +09:00',
                                                                            '+10'=>'GMT +10:00','+11'=>'GMT +11:00','+12'=>'GMT +12:00',
                                                                            )))?>
                </td>
		<td>
			<a id="save" href="#" title="Save">
		    <img src="<?php echo $this->webroot?>images/menuIcon_004.gif">
		  </a>
		  <a id="delete" href="#" title="Deleted">
		    	<img src="<?php echo $this->webroot?>images/delete.png">
		  </a>	
		</td>
	</tr>
</table>
<?php echo $form->end()?>