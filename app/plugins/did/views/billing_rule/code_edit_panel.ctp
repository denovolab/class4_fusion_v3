<?php echo $form->create('DidSpecialCode')?>
<table>
    <tr>
        <td><?php echo $xform->input('code',array('type'=>'text', 'maxlength'=>256))?></td>
        <td><?php echo $xform->input('pricing',array('maxlength'=>256, 'style'=>'width:80px;'))?></td>
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
