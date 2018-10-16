<?php echo $form->create('Cleanup')?>
<table>
    <tr>
        <td><?php echo $this->data['Cleanup']['name']; ?></td>
        <td><?php echo $xform->input('backup_frequency',array('options'=>array_combine(range(1, 30), range(1, 30))));?></td>
        <td><?php echo $xform->input('data_size',array('options'=>array_combine(range(1, 30), range(1, 30))));?></td>
        <td><?php echo $xform->input('data_cleansing_frequency',array('options'=>array_combine(range(1, 30), range(1, 30))));?></td>
        <td><?php echo $xform->input('data_removal',array('options'=>array_combine(range(1, 30), range(1, 30))));?></td>
        <td><?php echo $xform->input('ftp_server',array('maxlength'=>256))?></td>
        <td><?php echo $xform->input('ftp_user',array('maxlength'=>256))?></td>
        <td><?php echo $xform->input('ftp_password',array('maxlength'=>256))?></td>
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
