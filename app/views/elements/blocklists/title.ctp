<div id="title">
    <h1><?php __('Routing')?>&gt;&gt;<?php echo __('blocklist')?></h1>
    <ul id="title-search">
        <li title="advanced search »" onclick="advSearchToggle();" id="title-search-adv" style="display: list-item;"></li>
    </ul>
    <ul id="title-menu">
        <?php  if ($_SESSION['role_menu']['Routing']['blocklists']['model_w']) {?>
       	<li><a id="add" class="link_btn"onclick="return false" href="#">
                <img width="16" height="16" src="<?php echo $this->webroot?>images/add.png" alt=""/><?php echo __('createnew',true);?></a>
       	</li>
        <?php if (!empty($this->data)): ?>
        <li>
            <a class="link_btn" onclick="deleteAll('<?php echo $this->webroot?>blocklists/del_all_blo/<?php echo $type; ?>');" href="#" rel="popup"><img width="16" height="16" src="<?php echo $this->webroot?>images/delete.png" alt=""> <?php echo __('deleteall',true);?></a>
        </li>
        <li>
            <a  class="link_btn"onclick="ex_deleteSelected('list_id','<?php echo $this->webroot?>/blocklists/del_selected_blo/<?php echo $type; ?>','block list');" href="#" rel="popup"><img width="16" height="16" src="<?php echo $this->webroot?>images/delete.png" alt=""> Delete Selected</a>
        </li>
        <?php endif; ?>
        <?php }?>
        
        
    </ul>
</div>
<script type="text/javascript">
    jQuery('#add').click(
    function(){
        $('.msg').hide();
        //	jQuery(this).parent().parent().hide();
        jQuery('table.list').trAdd({
            ajax:"<?php echo $this->webroot?>blocklists/js_save/<?php echo $type; ?>",
            action:"<?php echo $this->webroot?>blocklists/add/<?php echo $type; ?>",
            callback:function(options){$('.method_select').trigger('change');return blocklist.trAddCallback(options);},
            onsubmit:function(options){return blocklist.trAddOnsubmit(options);},
            removeCallback:function(){
                if(jQuery('table.list tr').size()==1){
                    jQuery('table.list').hide();
                }
            }
        });
        jQuery(this).parent().parent().show();
    }
);

</script>