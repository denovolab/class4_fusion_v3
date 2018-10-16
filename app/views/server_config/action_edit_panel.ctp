<?php echo $form->create('ServerConfig')?>
<table>
    <tr>
        <td><?php echo $xform->input('name',array('maxlength'=>256))?></td>
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




