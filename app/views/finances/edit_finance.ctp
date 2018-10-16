<div id="title">
  <h1>
    <?php  __('Finance')?>
    &gt;&gt;<?php echo __('Editing finance',true);?></h1>
  <ul id="title-menu">
    <li> <?php echo $this->element('xback',Array('backUrl'=>'finances/view'))?> </li>
  </ul>
</div>
<div class="container">
  <?php //$urls=split('/',$this->params['url']['url'],2)?>
  <?php $id=array_keys_value($this->params,'pass.0')?>
  <?php echo $form->create ('Finance', array ('action' =>'edit_finance', 'onsubmit'=>"return checkform();"));?>
  <?php //echo $form->create ('Finance', array ('action' =>$urls[1],'onsubmit'=>'return checkform();'));?>
  <table class="form">
    <tbody> 
      <tr>
        <td class="label label2"><?php  __('Serial Number');?></td>
        <td class="value value2"><b><?php echo $p['action_number'];?></b></td>
      </tr>
      <tr>
        <td class="label label2"><?php  __('Submit Date');?></td>
        <td class="value value2"><?php echo $p['action_time'];?></td>
      </tr>
      <tr>
        <td class="label label2"><?php  __('Transaction Type')?></td>
        <td class="value value2">
        
			<select id="type" name="data[Finance][action_type]" style="width:210px;">
                <option value=""><?php echo __('select')?></option>
                <option value="2" <?php echo (!empty($p['action_type']) && $p['action_type'] == 2) ? 'selected' : '';?>><?php echo __('Wire In',true);?></option>
                <option value="1" <?php echo (!empty($p['action_type']) && $p['action_type'] == 1) ? 'selected' : '';?>><?php echo __('Wire Out',true);?></option>
              </select>
        </td>
      </tr>
      
     
      <tr>
        <td class="label label2"><?php  __('Method')?></td>
        <td class="value value2">
		<select id="method" name="data[Finance][action_method]" style="width:210px;">
                <option value=""><?php echo __('select')?></option>
                <option value="1" <?php echo (!empty($p['action_method']) && $p['action_method'] == 1) ? 'selected' : '';?>> Bank Wire</option>
                <option value="2" <?php echo (!empty($p['action_method']) && $p['action_method'] == 2) ? 'selected' : '';?>> Paypal</option>
              </select>
        </td>
      </tr>
      
      		<?php if ($p['action_type'] == 1):?>
      <tr>
        <td class="label label2"><?php echo __('Bank/Paypal Account',true);?>:</td>
        <td class="value value2"><input type="text" id="account" name="data[Finance][account]" value="<?php printf("%s", $p['account']);?>" class="in-input in-text" style="width:210px;"/></td>
      </tr>
      	<?php endif;?>
      <tr>
        <td class="label label2"><?php echo __('Transaction Amount',true);?>:</td>
        <td class="value value2"><input type="text" id="amount" name="data[Finance][amount]" value="<?php printf("%0.2f", $p['amount']);?>" class="in-input in-text" style="width:210px;" readonly="readonly"/></td>
      </tr>
      
      <tr>
        <td class="label label2"><?php echo __('Actual Amount',true);?>:</td>
        <td class="value value2"><input type="text" id="actual_amount" name="data[Finance][actual_amount]" value="<?php printf("%0.2f",$p['actual_amount']);?>" class="in-input in-text"  style="width:210px;" /></td>
      </tr>
      
       <tr>
        <td class="label label2"><?php echo __('Transaction Fee',true);?>:</td>
        <td class="value value2">
        <input type="text" id="fee"  name="data[Finance][action_fee]" value="<?php printf("%0.2f",$p['action_fee']);?>"  class="in-input in-text"  style="width:210px;"/></td>
      </tr>
      
      <tr>
        <td class="label label2"><?php  __('Status')?></td>
        <td class="value value2">
		<select id="status" name="data[Finance][status]" style="width:210px;">
                							<?php if ($p['action_type'] == 2 || ($p['action_type'] == 1 && $p['status'] != 1)):?>
                <option value="2" <?php echo (!empty($p['status']) && $p['status'] == 2) ? 'selected' : '';?>>Completed</option><?php endif;?>
                <?php if ($p['action_type'] == 1):?>                
                <option value="3" <?php echo (!empty($p['status']) && $p['status'] == 3) ? 'selected' : '';?>>Refused</option>
                <?php endif;?>
              </select>
        </td>
      </tr>
    </tbody>
  </table>
  <div id="form_footer">
  			<input type="hidden" name="data[Finance][id]" value="<?php echo $id;?>" />
  		<input type="hidden" name="id" value="<?php echo $id;?>" />
   <?php  if ($_SESSION['role_menu']['Finance']['finances']['model_w']) {?> <input type="submit" value="Submit" class="input in-submit">
   <?php }?>
    <input type="button" value="Cancel" onClick="history.go(-1);" class="input in-button">
  </div>
  <?php echo $form->end();?> </div>

<script type="text/javascript">
function checkform(){
	var flag=true;
	var amount=$('#amount').val();
	var fee=$('#fee').val();
		if (!/^\d[\.|\d]*$/.test(amount))
		{
			jQuery(this).jGrowlError('Amount, invalide format!',{theme:'jmsg-error'});
			flag= false;
		}
		if (!/^\d[\.|\d]*$/.test(fee))
		{
			jQuery(this).jGrowlError('Fee, invalide format!',{theme:'jmsg-error'});
			flag= false;
		}
		if(parseFloat($("#actual_amount").val())>parseFloat($("#amount").val())){
		jQuery(this).jGrowlError('Actual Amount cannot be greater than Transaction Amount!',{theme:'jmsg-error'});
		flag= false;
	}
	return flag;
}

$(document).ready(function(){
   $("#type").focus(function(){
       $(this).attr('defaultIndex',$(this).attr('selectedIndex'));    
   });
   $("#type").change(function(){
       $(this).attr('selectedIndex',$(this).attr('defaultIndex'));    
   });
});

</script>