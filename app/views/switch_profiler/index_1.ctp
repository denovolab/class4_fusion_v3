<div id="title">
    <h1><?php echo __('Configuration',true);?>&gt;&gt;<?php echo __('SIP Profile',true);?>: [<?php echo $gateway_name; ?>]</h1>
    <ul id="title-menu">
        <li>
            <a href="<?php echo $this->webroot?>switch_profiler/reload" class="link_btn">
                <img width="16" height="16" src="<?php echo $this->webroot?>/images/reload.png"><?php echo __('Reload') ?>			
            </a>
        </li>
        <?php  //if ($_SESSION['role_menu']['Switch']['switch_profiler']['model_w']) {?>
        <li>
            <a class="link_btn" id="add" title="<?php echo __('creataction')?>"  href="###">
                <img width="16" height="16" alt="" src="<?php echo $this->webroot?>images/add.png"><?php echo __('createnew')?>
            </a>
        </li>
        <?php //}?>
        <li>
            <a href="<?php echo $this->webroot ?>server_config" class="link_back_new"> 
                <img width="16" height="16" src="<?php echo $this->webroot ?>images/icon_back_white.png" alt="Back">&nbsp;Back 
            </a>
        </li>
    </ul>
</div>

<div id="container">
    <?php
        if(empty($this->data)): 
    ?>
    <div class="msg"><?php echo __('no_data_found',true);?></div>
    <table class="list" style="display:none;">
        <thead>
            <tr>
                <td>Profile Name</td>
                <td>Profile Status</td>
                <td>SIP IP</td>
                <td>SIP Port</td>
                <td>SIP Debug</td>
                <td>SIP Trace</td>
                <td>Proxy IP</td>
                <td>Proxy Port</td>
                <td>Support RPID</td>
                <td>Support OLI</td>
                <td>Support PRIV</td>
                <td>Support DIV</td>
                <td>Support PAID</td>
                <td>Support PCI</td>
                <td>Support X LRN</td>
                <td>Support X Header</td>
                <td>Info IP</td>
                <td>Info Port</td>
                <td>Sip Capture IP</td>
                <td>Sip Capture Port</td>
                <td>Sip Capture Path</td>
                <td>Action</td>
            </tr>
        </thead>
        
        <tbody>
         
        </tbody>
    </table>
    <?php else: ?>
    <?php echo $this->element("xpage")?>
    <table class="list">
        <thead>
            <tr>
                <td>Profile Name</td>
                <td>Profile Status</td>
                <td>SIP IP</td>
                <td>SIP Port</td>
                <td>SIP Debug</td>
                <td>SIP Trace</td>
                <td>Proxy IP</td>
                <td>Proxy Port</td>
                <td>Support RPID</td>
                <td>Support OLI</td>
                <td>Support PRIV</td>
                <td>Support DIV</td>
                <td>Support PAID</td>
                <td>Support PCI</td>
                <td>Support X LRN</td>
                <td>Support X Header</td>
                <td>Info IP</td>
                <td>Info Port</td>
                <td>Sip Capture IP</td>
                <td>Sip Capture Port</td>
                <td>Sip Capture Path</td>
                <td>Action</td>
            </tr>
        </thead>
        
        <tbody>
            <?php foreach($this->data as $item): ?>
            <tr>
                <td><?php echo $item['SwitchProfile']['profile_name']; ?></td>
                <td><?php echo $status[$item['SwitchProfile']['profile_status']]; ?></td>
                <td><?php echo $item['SwitchProfile']['sip_ip']; ?></td>
                <td><?php echo $item['SwitchProfile']['sip_port']; ?></td>
                <td><?php echo $item['SwitchProfile']['sip_debug']; ?></td>
                <td><?php echo $item['SwitchProfile']['sip_trace'] ? 'True' : 'False'; ?></td>
                <td><?php echo $item['SwitchProfile']['proxy_ip']; ?></td>
                <td><?php echo $item['SwitchProfile']['proxy_port']; ?></td>
                <td><?php echo $item['SwitchProfile']['support_rpid'] ? 'Yes' : 'No'; ?></td>
                <td><?php echo $item['SwitchProfile']['support_oli'] ? 'Yes' : 'No'; ?></td>
                <td><?php echo $item['SwitchProfile']['support_priv'] ? 'Yes' : 'No'; ?></td>
                <td><?php echo $item['SwitchProfile']['support_div'] ? 'Yes' : 'No'; ?></td>
                <td><?php echo $item['SwitchProfile']['support_paid'] ? 'Yes' : 'No'; ?></td>
                <td><?php echo $item['SwitchProfile']['support_pci'] ? 'Yes' : 'No'; ?></td>
                <td><?php echo $item['SwitchProfile']['support_x_lrn'] ? 'Yes' : 'No'; ?></td>
                <td><?php echo $item['SwitchProfile']['support_x_header'] ? 'Yes' : 'No'; ?></td>
                <td><?php echo $item['SwitchProfile']['lan_ip']; ?></td>
                <td><?php echo $item['SwitchProfile']['lan_port']; ?></td>
                <td><?php echo $item['SwitchProfile']['sip_capture_ip']; ?></td>
                <td><?php echo $item['SwitchProfile']['sip_capture_port']; ?></td>
                <td><?php echo $item['SwitchProfile']['sip_capture_path']; ?></td>
                <td>
                    <a title="Edit" class="edit_item" href="###" control="<?php echo $item['SwitchProfile']['id']?>" >
                        <img src="<?php echo $this->webroot?>images/editicon.gif"/>
                    </a>

                    <a title="Delete" class="delete" href='<?php echo $this->webroot ;?>switch_profiler/delete/<?php echo $server_id . '/' .$item['SwitchProfile']['id']?>'>
                        <img src="<?php echo $this->webroot?>images/delete.png "/>
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php echo $this->element("xpage")?>
    <?php endif; ?>
</div>
    
<script>
    jQuery(function() {
        jQuery('#add').click(function(){
            $('.msg').hide();
            $('table.list').show();
            jQuery('table.list tbody').trAdd({
                ajax:"<?php echo $this->webroot ?>switch_profiler/action_edit_panel/<?php echo $server_id; ?>",
                action:"<?php echo $this->webroot ?>switch_profiler/action_edit_panel/<?php echo $server_id; ?>",
                removeCallback:function(){
                    if(jQuery('table.list tr').size()==1){
                        jQuery('table.list').hide();
                    }
                }
            });
            jQuery(this).parent().parent().show();
        });
        
        jQuery('a.edit_item').click(function(){
            jQuery(this).parent().parent().trAdd({
                action:'<?php echo $this->webroot ?>switch_profiler/action_edit_panel/<?php echo $server_id; ?>/'+jQuery(this).attr('control'),
                ajax:'<?php echo $this->webroot ?>switch_profiler/action_edit_panel/<?php echo $server_id; ?>/'+jQuery(this).attr('control'),
                saveType:'edit'
            });
        });
    });
</script>
    