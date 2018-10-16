<?php echo $form->create('DidAssign')?>
<table>
    <tr>
        <td></td>
        <td><?php echo $ingresses[$this->data['DidAssign']['ingress_id']]; ?></td>
        <td><?php echo $xform->input('egress_id',array('options'=>$egresses))?></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
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

<script>
    $(function() {
        
    });
</script>
