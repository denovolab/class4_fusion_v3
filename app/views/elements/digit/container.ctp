<fieldset style="width: 100%; display: none;" id="advsearch" class="title-block">
	 <form method="get" action="">
				<table style="width:auto;">
					<tbody>
					<tr>
					  <td>
					    <label> <?php echo __('name',true);?>:</label>
					    <input type="text" id="name" value="" name="name" class="input in-text">
					  </td>
					  <td class="buttons"><input type="submit" class="input in-submit" value="<?php echo __('Search',true);?>"></td>
					</tr>
					</tbody>
				</table>
		</form>
</fieldset>
<dl id="addproduct" class="tooltip-styled" style="display:none;position:absolute;left:35%;top:25%;z-idnex:99;width:500px;height:200px;">
<dd style="text-align:center;width:100%;height:25px;font-size: 16px;"><?php echo __('addfeedback')?></dd>
	<dd style="margin-top:10px;">
		<textarea id="ComplainContent" class="input in-text in-textarea" style="float: left; width: 400px; height: 94px;" rows="6" cols="30" name="data[Complain][content]"></textarea>
	</dd>
	<dd style="text-align:center">
			<input checked type="radio" class="ss" name="status" value="1"/><?php echo __('open')?>&nbsp;&nbsp;&nbsp;&nbsp;
			<input type="radio" name="status" class="ss" value="2"/><?php echo __('closed')?>&nbsp;&nbsp;&nbsp;&nbsp;
			<input type="radio" name="status" class="ss" value="3"/><?php echo __('resolved')?>&nbsp;&nbsp;&nbsp;&nbsp;
	</dd>
	<dd style="margin-top:10px; margin-left:25%;width:150px;height:auto;">
		<input type="button" onclick="addfeedback('ComplainContent','<?php echo $this->webroot?>complainfeedbacks/add/<?php echo $id?>',<?php echo $status?>);" value="<?php echo __('submit')?>" class="input in-button">
		<input type="button" onclick="closeCover('addproduct');" value="<?php echo __('cancel')?>" class="input in-button">
	</dd>
</dl>
<dl id="viewmessage" class="tooltip-styled" style="display:none;position:absolute;left:35%;top:25%;z-idnex:99;width:500px;height:200px;">
	<dd style="text-align:center;width:100%;height:25px;font-size: 16px;">
		<?php echo __('viewmessage')?>
		 <a style="float:right;" href="javascript:void(0)" onclick="" title="<?php echo __('close')?>">
			<img width="16" height="16" src="<?php echo $this->webroot?>images/delete.png">
		</a>
	</dd>
	<dd style="margin-top:10px;">
		<textarea id="CompleteContent" class="input in-text in-textarea" style="float: left; width: 400px; height: 94px;" rows="6" cols="30" ></textarea>
	</dd>
</dl>
<div id="toppage"></div>
<?php echo $this->element("digit/list")?>
<div id="tmppage"><?php echo $this->element('page');?></div>
<script type="text/javascript">
jQuery('#submit').click(function(){
    var re=true;
    var vals=Array();
    jQuery('#objectForm').find('input[rel=format_number]').removeClass('invalid').map(
						function(){
						//	alert(jQuery('#DigitTranslationName').attr('mycheck'));
							if(jQuery(this).val()=='')
							{
								jQuery(this).addClass('invalid');
								jQuery.jGrowl('Name cannot be NULL!',{theme:'jmsg-error'});
								re=false;
							}
							else
							{
							
           //if(jQuery("#DigitTranslationName").attr('mycheck')=="add"){
              
								if(in_array(jQuery(this).val(),vals))
								{
									jQuery(this).addClass('invalid');
									jQuery.jGrowl(jQuery(this).val()+' is already in use!',{theme:'jmsg-error'});
									re=false;
								}
							//}
								vals.push(jQuery(this).val());
							}
						}
            	);


	
			if(re)
			{
				jQuery(this).attr('disabled',true);
		   jQuery('#objectForm').submit();
			}
	    
    	}
	);
</script>

   
   
