<style type="text/css">
    .list tbody tr span {margin:0 10px;}
</style>

<div id="title">
    <h1><?php echo __('Origination',true);?>&gt;&gt;<?php echo __('Billing Rule',true);?>&gt;&gt;<?php echo __('Special Code',true);?></h1>
    <ul id="title-menu">
        <?php  if ($_SESSION['role_menu']['Origination']['billing_rule']['model_w']) {?>
        <li>
            <a class="link_btn" id="add" title="<?php echo __('creataction')?>"  href="###">
                <img width="16" height="16" alt="" src="<?php echo $this->webroot?>images/add.png"><?php echo __('createnew')?>
            </a>
        </li>
        <li>
            <a class="link_btn" title="<?php echo __('Upload')?>"  href="<?php echo $this->webroot; ?>uploads/special_code">
                <img width="16" height="16" alt="" src="<?php echo $this->webroot?>images/upload_icon.png"><?php echo __('Upload')?>
            </a>
        </li>
        <?php }?>
    </ul>
</div>

<div id="container">
    <ul class="tabs">
        <li>
            <a href="<?php echo $this->webroot ?>did/billing_rule/plan">
                Billing Rule			
            </a>
        </li>
        <li class="active">
            <a href="<?php echo $this->webroot ?>did/billing_rule/special_code">
                Special Code			
            </a>
        </li>
    </ul>
    <?php
        if(empty($this->data)): 
    ?>
    <div class="msg"><?php echo __('no_data_found',true);?></div>
    <table class="list" style="display:none;">
        
        <thead>
            <tr>
                <td>Code</td>
                <td>Pricing</td>
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
                <td>Code</td>
                <td>Pricing</td>
                <td>Action</td>
            </tr>
        </thead>
        
        <tbody>
            <?php foreach($this->data as $item): ?>
            <tr>
                <td><?php echo $item['DidSpecialCode']['code']; ?></td>
                <td><?php echo $item['DidSpecialCode']['pricing']; ?></td>
                <td>
                    <a title="Edit" class="edit_item" href="###" control="<?php echo $item['DidSpecialCode']['id']?>" >
                        <img src="<?php echo $this->webroot?>images/editicon.gif"/>
                    </a>

                    <a title="Delete" class="delete" href='<?php echo $this->webroot ;?>did/billing_rule/delete_code/<?php echo $item['DidSpecialCode']['id']?>'>
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
                ajax:"<?php echo $this->webroot ?>did/billing_rule/code_edit_panel",
                action:"<?php echo $this->webroot ?>did/billing_rule/code_edit_panel",
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
                action:'<?php echo $this->webroot ?>did/billing_rule/code_edit_panel/'+jQuery(this).attr('control'),
                ajax:'<?php echo $this->webroot ?>did/billing_rule/code_edit_panel/'+jQuery(this).attr('control'),
                saveType:'edit'
            });
        });
    });
</script>
    