
    <link href="<?php echo $this->webroot?>images/favicon.ico" type="image/x-icon" rel="shortcut Icon">
<div id="title">
        <h1><?php __('Configuration')?>&gt;&gt;<?php echo __('Change Password',true);?></h1>
</div>
<div id="container">
    <!-- DYNAMIC -->
    	<?php echo $form->create ('User', array ('action' => 'changepassword' ));?>
   
<table class="form" style="width:300px;">
<col style="width: 40%;"><col style="width: 60%;">
<tbody><tr>
    <td class="label"><?php echo __('oldpassword')?>:</td>
    <td class="value">
    
   	<?php echo $form->input('old',  	array('label'=>false ,'div'=>false,'type'=>'password', 'class'=>'input in-password'));?>
    </td>
</tr>
<tr>
    <td class="label"><?php echo __('newpassword')?>:</td>
    <td class="value">
    	<?php echo $form->input('new',  	array('label'=>false ,'div'=>false,'type'=>'password', 'class'=>'input in-password'));?>
    </td>
</tr>
<tr>
    <td class="label"><?php echo __('confirmpassword')?>:</td>
    <td class="value">
    
    	<?php echo $form->input('retype',  	array('label'=>false ,'div'=>false,'type'=>'password', 'class'=>'input in-password'));?>

    </td>
</tr>
</tbody></table>
<div class="form-buttons"><input type="submit" value="<?php __('submit')?>" class="input in-submit"></div>
   <!-- DYNAMIC -->
</div>
