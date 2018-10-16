
<div id="title">
  <h1><?php echo __('Domestic Exchange',true);?>&gt;&gt;<?php echo __('Add Exchange',true);?></h1>
  <ul id="title-menu">
    <li> <a class="link_back" href="<?php echo $this->webroot?>dexs/view"> <img width="16" height="16"  alt="" src="<?php echo $this->webroot?>images/icon_back_white.png"/> &nbsp;<?php echo __('goback')?> </a> </li>
    
  </ul>
</div>
<?php //****************************************************页面主体?>
<div class="container">
<?php $id=array_keys_value($this->params,'pass.0')?>
<form method="post" action="<?php echo $this->webroot;?>dexs/add" id="DexAddForm">
  <table class="form">
    <tbody>
      <tr>
        <td class="label label2"><?php echo __('DEX Name',true);?>:</td>
        <td class="value value2">
        <input type="hidden" name="id" value="<?php if (!empty($p['id'])) echo $p['id']; ?>" >
        <?php echo $form->input('dex_name',
 		array('label'=>false ,'div'=>false,'type'=>'text', 'value'=>(empty($p['dex_name']) ? '' : $p['dex_name']), 'class'=>'input in-text','maxLength'=>'256'));?></td>
      </tr>
      <tr>
        <td class="label label2"><?php echo __('Trunk',true);?>:</td>
        <td class="value value2">
        <?php echo $form->input('resource_alias', array('options'=>$egress, 'name'=>'data[resource]','multiple'=>'multiple', 'label'=>false,'div'=>false,'type'=>'select', 'value'=>'', 'style'=>"width: 300px; height: 120px;"));?>
        
        </td>
      </tr>
      <tr>
        <td class="label label2"><?php echo __('Prefix',true);?>:</td>
        <td class="value value2"><?php echo $form->input('dex_prefix',
 		array('label'=>false ,'div'=>false,'type'=>'text','value'=>(empty($p['dex_prefix']) ? '' : $p['dex_prefix']),'class'=>'input in-text'));?></td>
      </tr>
    </tbody>
  </table>
  </fieldset>
  <div id="form_footer">
  <input type="hidden" name="" value="" />
    <input type="submit" value="<?php echo __('submit')?>" />
  </div>
  </form>
  </div>
