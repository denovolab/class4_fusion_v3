<?php 
$module_id=array_keys_value($this->params,'pass.0');
$id = array_keys_value($this->params,'pass.1');
?>

<div id="title">
  <h1><?php __('Configuration')?>&gt;&gt;<?php echo __('Edit Module',true);?></h1>
  <ul id="title-menu">
  	<li> <a class="link_back" href="<?php echo $this->webroot?>syspris/view_syspri/<?php  echo $module_id;?>"> <img width="16" height="16"  alt="" src="<?php echo $this->webroot?>images/icon_back_white.png"/> &nbsp;<?php echo __('goback')?> </a> </li>
    
  </ul>
</div>
<?php //****************************************************页面主体?>
<div class="container">

<?php echo $form->create ('Syspri', array ('action' => 'add_syspri' ));?>

  <table class="form">
    <tbody>
    <tr>
        <td class="label label2"><?php echo __('Parent Module',true);?>:</td>
        <td class="value value2">
		<?php echo $form->input('flag', array('options'=>$modules, 'name'=>'data[Syspri][module_id]','label'=>false ,'div'=>false,'type'=>'select', 'value'=>array_keys_value($this->params,'pass.0')));?>
					</td>
      </tr>
      <tr>
        <td class="label label2"><?php echo __('Privilege Name',true);?>:</td>
        <td class="value value2"><?php echo $form->input('pri_name',
 		array('label'=>false ,'div'=>false,'type'=>'text','class'=>'input in-text','maxLength'=>'256'));?></td>
      </tr>
      <tr>
        <td class="label label2"><?php echo __('Privilege List Value',true);?>:</td>
        <td class="value value2"><?php echo $form->input('pri_val',
 		array('label'=>false ,'div'=>false,'type'=>'text','class'=>'input in-text','maxLength'=>'100'));?></td>
      </tr>
      <tr>
        <td class="label label2"><?php echo __('Flag',true);?>:</td>
        <td class="value value2">
		<?php echo $form->input('flag', array('options'=>(array('1'=>'True','0'=>'False')),'name'=>'data[Syspri][flag]','label'=>false ,'div'=>false,'type'=>'select'));?>
		</td>
      </tr>
      <tr>
        <td class="label label2"><?php echo __('Module List Url',true);?>:</td>
        <td class="value value2">
		<?php echo $form->input('pri_url',
 		array('label'=>false ,'div'=>false,'type'=>'text','class'=>'input in-text','maxLength'=>'200'));?>
		
		</td>
      </tr>
    </tbody>
  </table>
  </fieldset>
  <?php  if ($_SESSION['role_menu']['Configuration']['syspris']['model_w']) {?>
  <div id="form_footer">
<input type="hidden" name="data[Syspri][id]" value="<?php echo $id;?>" />
    <input type="submit" value="<?php echo __('submit')?>" />
  </div>
  <?php }?>
  <?php echo $form->end();?> </div>
