<?php echo $form->create('MailSender')?>
<table>
    <tr>
        <td><?php echo $xform->input('smtp_host',array('maxlength'=>256))?></td>
        <td><?php echo $xform->input('smtp_port',array('maxlength'=>256))?></td>
        <td><?php echo $xform->input('username',array('maxlength'=>256))?></td>
        <td><?php echo $xform->input('password',array('maxlength'=>256, 'type' => 'password'))?></td>
        <td><?php echo $xform->input('loginemail',array('options'=>array('true' => 'true', 'false' => 'false')))?></td>
        <td><?php echo $xform->input('secure',array('options'=>array(0 => '', 1 => 'TLS', 2 => 'SSL')))?></td>
        <td><?php echo $xform->input('email',array('maxlength'=>256))?></td>
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
