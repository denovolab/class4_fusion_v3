<div id="title">
    <h1><?php echo __('Switch',true);?>&gt;&gt;<?php echo __('VoIP Gateway',true);?></h1>
    <ul id="title-menu">
        <li>
            <a href="<?php echo $this->webroot?>switch_profiler/reload" class="link_btn">
                <img width="16" height="16" src="<?php echo $this->webroot?>/images/reload.png"><?php echo __('Reload') ?>			
            </a>
        </li>
        <?php  if ($_SESSION['role_menu']['Switch']['server_config']['model_w']) {?>
        <li>
            <a class="link_btn" id="add" title="<?php echo __('creataction')?>"  href="###">
                <img width="16" height="16" alt="" src="<?php echo $this->webroot?>images/add.png"><?php echo __('createnew')?>
            </a>
        </li>
        <?php }?>
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
                <td>Name</td>
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
                <td>Name</td>
                <td>Action</td>
            </tr>
        </thead>
        
        <tbody>
            <?php foreach($this->data as $item): ?>
            <tr>
                <td><?php echo $item['ServerConfig']['name']; ?></td>
                <td>
                    <a title="SIP Profile" href="<?php echo $this->webroot ?>switch_profiler/index/<?php echo $item['ServerConfig']['id']?>">
                        <img src="<?php echo $this->webroot ?>images/switch_profile.png" />
                    </a>
                    <!--
                    <a title="Edit" class="edit_item" href="###" control="<?php echo $item['ServerConfig']['id']?>" >
                        <img src="<?php echo $this->webroot?>images/editicon.gif"/>
                    </a>
                    -->
                    <!--
                    <a title="Delete" class="delete" href='<?php echo $this->webroot ;?>switch_profiler/delete/<?php echo $item['ServerConfig']['id']?>'>
                        <img src="<?php echo $this->webroot?>images/delete.png "/>
                    </a>
                    -->
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
                ajax:"<?php echo $this->webroot ?>server_config/action_edit_panel",
                action:"<?php echo $this->webroot ?>server_config/action_edit_panel",
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
                action:'<?php echo $this->webroot ?>server_config/action_edit_panel/'+jQuery(this).attr('control'),
                ajax:'<?php echo $this->webroot ?>server_config/action_edit_panel/'+jQuery(this).attr('control'),
                saveType:'edit'
            });
        });
    });
</script>
