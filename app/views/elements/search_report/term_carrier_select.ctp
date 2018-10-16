<!--
<td class="label"><b class="neg"><?php  __('termination')?>&nbsp;&nbsp;</b><?php  __('Carriers')?></td>
<td id="client_cell" class="value" >
<?php 		echo $form->input('term_carrier_select',		array('options'=>$all_carrier,'empty'=>'','name'=>'term_carrier_select','label'=>false ,'div'=>false,'type'=>'select'));
            ?>
</td>
-->

<td class="label"><?php  __('Carriers')?>:</td>
<td id="client_cell" class="value" >
<?php 		echo $form->input('term_carrier_select',		array('options'=>$egress_carrier,'empty'=>'','name'=>'term_carrier_select','label'=>false ,'div'=>false,'type'=>'select', 'onchange'=>"get_egress();"));
            ?><?php echo $this->element('search_report/ss_clear_input_select');?>
</td>