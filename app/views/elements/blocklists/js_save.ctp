<?php echo $form->create('ResourceBlock',Array('action'=>'add','method'=>'post'))?>
<table>
<tr >
<td></td>
<td>    
    
	<?php
        $disabled = false;
            if($this->data['ResourceBlock']['ani_empty'] == 't')
                $disabled = true;
            echo $form->input('ani_prefix',array('label'=>false ,'div'=>false,'type'=>'text', 'style'=>'width:100px;', 'disabled'=>$disabled));
            ?>
<!--        <input type="checkbox" name="data[ResourceBlock][is_disabled]" class="prefix_chk" <?php if(empty($this->data['ResourceBlock']['ani_prefix'])) echo 'checked="checked"'; ?> />-->
        <?php
            echo $form->input('ani_empty', array('label'=>false, 'div'=>false, 'class'=>'prefix_chk', 'type'=>'checkbox', 'style'=>'width:100px;'));
        ?>
</td>
<td>
	<?php echo $form->input('digit',array('label'=>false ,'div'=>false,'type'=>'text'));?>
</td>
<!--
<td>
	<?php echo $form->input('ingress_client_id',array('label'=>false,'empty'=>'' ,'div'=>false,'options'=>$appBlocklists->_get_select_options($IngressClient,'Client','client_id','name')));?>
</td>
<td>
<?php echo $form->input('ingress_res_id',array('label'=>false ,'div'=>false,'type'=>'select','v'=>$this->data['ResourceBlock']['ingress_res_id']));?>
</td>
-->
<td>
<?php

if ($type == 2) {
	echo $form->input('egress_client_id', array('label' => false, 'empty' => '', 'div' => false, 'options' => $appBlocklists->_get_select_options($IngressClient, 'Client', 'client_id', 'name')));
} else {
	echo $form->input('ingress_client_id',array('label'=>false,'empty'=>'' ,'div'=>false,'options'=>$appBlocklists->_get_select_options($IngressClient,'Client','client_id','name')));
}
?>
</td>
<td>
<?php
if ($type == 2) {
	echo $form->input('engress_res_id',array('label'=>false ,'div'=>false,'type'=>'select','v'=>$this->data['ResourceBlock']['engress_res_id']));
} else {
	echo $form->input('ingress_res_id', array('label' => false, 'div' => false, 'type' => 'select', 'v' => $this->data['ResourceBlock']['ingress_res_id']));
}
?>
</td>
<td><?php echo $form->input('time_profile_id',Array('type'=>'select','div'=>false,'label'=>false,'options'=>$appBlocklists->_get_select_options($TimeProfileList,'TimeProfile','time_profile_id','name'),'empty'=>' '))?></td>

<td>
        <?php echo $form->input('ani_method',array('label'=>false,'empty'=>'Any' ,'div'=>false,'options'=>array('<','=', '>'), 'style'=>'width:80px;', 'class'=>'method_select'));?>
	<?php echo $form->input('ani_length',array('label'=>false ,'div'=>false,'type'=>'text', 'style'=>'width:100px;'));?>
</td>
<td>
        <?php echo $form->input('dnis_method',array('label'=>false,'empty'=>'Any' ,'div'=>false,'options'=>array('<','=', '>'), 'style'=>'width:80px;', 'class'=>'method_select'));?>
	<?php echo $form->input('dnis_length',array('label'=>false ,'div'=>false,'type'=>'text', 'style'=>'width:100px;'));?>
</td>
<td>
    
</td>
<td>
    
</td>

<td  class="last">
  <a title="Save" id="save" href="" onclick="return false">
   <img src="<?php echo $this->webroot?>images/menuIcon_004.gif" height="16" width="16">
	</a>
	<a title="Exit" id="delete"  href="">
		<img src="<?php echo $this->webroot?>images/delete.png" height="16" width="16">
	</a>
</td>
</tr>
</table>
<?php echo $form->end()?>

	

