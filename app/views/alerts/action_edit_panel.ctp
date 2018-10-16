
<?php echo $form->create('Action')?>
<table>
    <tr>
        <td><?php echo $xform->input('name',array('maxlength'=>256))?></td>
        <td><?php echo $xform->input('block_ani');?></td>
        <td><?php echo $xform->input('loop_detection');?></td>
        <td><?php echo $xform->input('trouble_tickets_template', array('options'=>$templates));?></td>
        <td><?php echo $xform->input('email_notification',Array('options'=>array('None','System\'s NOC','Partner\'s NOC', 'Both NOC')));?></td>
        <td><?php echo $xform->input('disable_route_target',Array('options'=>array('None', 'Entire Trunk', 'Entire Host')));?></td>
        <td><?php echo $xform->input('disable_code_trunk');?></td>
        <td><?php echo $xform->input('disable_duration');?></td>
        <td><?php echo $xform->input('change_prioprity',Array('options'=>array('None', 'Trunk', "Host")));?></td>
        <td><?php echo $xform->input('change_to_priority', Array('options'=>range(0, 10)));?></td>
        <td></td>
        <td></td>
        <td align="center" style="text-align:center" class="last">
            <a id="save" href="###" title="Edit">
                <img title="save" src="<?php echo $this->webroot?>images/menuIcon_004.gif"> 
            </a>
            <a id="delete" title="Exit">
                <img title="del" src="<?php echo $this->webroot?>images/delete.png">
            </a>
        </td>
    </tr>
</table>
<?php echo $form->end()?>




