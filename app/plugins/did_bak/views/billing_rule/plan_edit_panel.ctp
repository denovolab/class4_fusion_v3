<?php echo $form->create('DidBillingPlan')?>
<table>
    <tr>
        <td><?php echo $xform->input('name',array('maxlength'=>256))?></td>
        <td><?php echo $xform->input('did_price',array('maxlength'=>256, 'style'=>'width:80px;'))?></td>
        <td><?php echo $xform->input('channel_price',array('maxlength'=>256, 'style'=>'width:40px;'))?></td>
        <td><?php echo $xform->input('min_price',array('maxlength'=>256, 'type'=>'text', 'style'=>'width:80px;'))?></td>
        <td><?php echo $xform->input('billed_channels',array('maxlength'=>256, 'style'=>'width:40px;'))?></td>
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
