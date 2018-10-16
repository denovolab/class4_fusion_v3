<?php echo $form->create('SwitchProfile')?>
<table>
    <tr>
        <td><?php echo $xform->input('profile_name',array('maxlength'=>256, 'readonly'=>$isedit))?></td>
        <td><?php echo $xform->input('profile_status',array('options'=>array(0 => 'INIT', 1 => 'ACTIVE', 2 => 'DEACTIVE', 3 => 'SHUTDOWN', 4 =>'DESDROY')))?></td>
        <td><?php echo $xform->input('sip_ip',array('maxlength'=>256, 'readonly'=>$isedit, 'style'=>'width:80px;'))?></td>
        <td><?php echo $xform->input('sip_port',array('maxlength'=>256, 'readonly'=>$isedit, 'style'=>'width:40px;'))?></td>
<!--        <td><?php echo $xform->input('sip_debug',array('options'=>range(0, 9)))?></td>
        <td><?php echo $xform->input('sip_trace', array('type'=>'checkbox'))?></td>-->
        <td><?php echo $xform->input('proxy_ip',array('maxlength'=>256, 'style'=>'width:80px;'))?></td>
        <td><?php echo $xform->input('proxy_port',array('maxlength'=>256, 'style'=>'width:40px;'))?></td>
        <td><?php echo $xform->input('lan_ip',array('maxlength'=>256, 'type'=>'text', 'style'=>'width:80px;'))?></td>
        <td><?php echo $xform->input('lan_port',array('maxlength'=>256, 'style'=>'width:40px;'))?></td>
        <td><?php echo $xform->input('paid_replace_ip',array('type'=>'checkbox'))?></td>
        <td align="center" style="text-align:center" class="last">
            <a id="save" href="###" title="Edit">
                <img title="save" src="<?php echo $this->webroot?>images/menuIcon_004.gif"> 
            </a>
            <a id="delete" title="Exit">
                <img title="del" src="<?php echo $this->webroot?>images/delete.png">
            </a>
        </td>
    </tr>
    <tr>
        <td colspan="14">
            <span>RPID:<?php echo $xform->input('support_rpid',array('type'=>'checkbox'))?></span>
            <span>OLI:<?php echo $xform->input('support_oli',array('type'=>'checkbox'))?></span>
            <span>PRIV<?php echo $xform->input('support_priv',array('type'=>'checkbox'))?></span>
            <span>DIV:<?php echo $xform->input('support_div',array('type'=>'checkbox'))?></span>
            <span>PAID:<?php echo $xform->input('support_paid',array('type'=>'checkbox'))?></span>
            <span>PCI:<?php echo $xform->input('support_pci',array('type'=>'checkbox'))?></span>
            <span>X LRN:<?php echo $xform->input('support_x_lrn',array('type'=>'checkbox'))?></span>
            <span>X Header:<?php echo $xform->input('support_x_header',array('type'=>'checkbox'))?></span>
            <span>Sip Capture IP:<?php echo $xform->input('sip_capture_ip',array('maxlength'=>256, 'type'=>'text'))?></span>
            <span>Sip Capture Port:<?php echo $xform->input('sip_capture_port',array('maxlength'=>256))?></span>
            <span>Sip Capture Path:<?php echo $xform->input('sip_capture_path',array('maxlength'=>256))?></span>
        </td>
    </tr>
</table>
<?php echo $form->end()?>
