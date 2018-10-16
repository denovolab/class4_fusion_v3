<style type="text/css">
    .list tbody tr span {margin:0 10px;}
</style>

<div id="title">
    <h1><?php echo __('DID Management',true);?>&gt;&gt;<?php echo __('DID Trunk',true);?></h1>
    <ul id="title-menu">
        <li>
            <a class="link_btn" id="add" title="<?php echo __('creataction')?>"  href="###">
                <img width="16" height="16" alt="" src="<?php echo $this->webroot?>images/add.png"><?php echo __('createnew')?>
            </a>
        </li>
    </ul>
</div>

<div id="container">
   <?php //echo $this->element("did_client_tab", array('active' => 'trunk'))?>
   <?php echo $this->element("did_trunk_tab", array('active' => 'egress'))?>
   <!-- Sub Menu Tab -->
    <?php
        $data =$p->getDataArray();
        if(empty($data)): 
    ?>
    <div class="msg"><?php echo __('no_data_found',true);?></div>
    <table class="list" style="display:none;">
        
        <thead>
            <tr>
                <td>Name</td>
                <td>IP</td>
                <td>Mask</td>
                <td>Prefix:</td>
                <td>Action</td>
            </tr>
        </thead>
        
        <tbody>
         
        </tbody>
    </table>
    <?php else: ?>
    <div id="toppage"></div>
    <table class="list">
        <thead>
            <tr>
                <td>Name</td>
                <td>IP</td>
                <td>Mask</td>
                <td>Prefix</td>
                <td>Action</td>
            </tr>
        </thead>
        
        <tbody>
            <?php foreach($data as $item): ?>
            <tr>
                <td><?php echo $item['Resource']['alias']; ?></td>
                <?php 
                    $trunk_ip_info = explode('/', $item['ResourceIp']['ip']);
                ?>
                <td><?php echo $trunk_ip_info[0]; ?></td>
                <td><?php echo $trunk_ip_info[1]; ?></td>
                <td><?php echo $item['ResourceDirection']['digits']; ?></td>
                <td>
                    <a title="Edit" class="edit_item" href="###" control="<?php echo $item['Resource']['resource_id']?>" >
                        <img src="<?php echo $this->webroot?>images/editicon.gif"/>
                    </a>

                    <a title="Delete" class="delete" href='<?php echo $this->webroot ;?>did/orders/delete_trunk/<?php echo $item['Resource']['resource_id']?>'>
                        <img src="<?php echo $this->webroot?>images/delete.png "/>
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div id="tmppage"> <?php echo $this->element('page');?> </div>
    <?php endif; ?>
</div>
    
<script>
    jQuery(function() {
        jQuery('#add').click(function(){
            $('.msg').hide();
            $('table.list').show();
            jQuery('table.list tbody').trAdd({
                ajax:"<?php echo $this->webroot ?>did/orders/trunk_panel",
                action:"<?php echo $this->webroot ?>did/orders/trunk_panel",
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
                action:'<?php echo $this->webroot ?>did/orders/trunk_panel/'+jQuery(this).attr('control'),
                ajax:'<?php echo $this->webroot ?>did/orders/trunk_panel/'+jQuery(this).attr('control'),
                saveType:'edit'
            });
        });
    });
</script>
    