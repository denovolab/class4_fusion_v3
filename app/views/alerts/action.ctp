<script src="<?php echo $this->webroot?>js/ajaxTable.js" type="text/javascript"></script>
<div id="title">
    <h1><?php __('Monitoring')?>&gt;&gt;<?php echo __('Action')?></h1>  
    <ul id="title-search">
        <li>
            <?php //Pr($searchkey);    //****************************模糊搜索**************************?>
            <form  action="<?php echo $this->webroot;?>alerts/action"  method="get">
                <input type="text" id="search-_q_j" class="in-search default-value input in-text defaultText" title="<?php echo __('search')?>..." value="<?php if(isset($searchkey)){echo $searchkey;}else{ echo __('pleaseinputkey');}?>"  onclick="this.value=''" name="searchkey">
            </form>
        </li>
    </ul>
    <ul id="title-menu">
        <?php if (isset($edit_return)) {?>
        <li>
            <a class="link_back" href="<?php echo $this->webroot;?>alerts/action">
                <img width="16" height="16"  alt="" src="<?php echo $this->webroot?>images/icon_back_white.png">
                &nbsp;<?php echo __('goback')?>
            </a>
        </li>
        <?php }?>
        <?php  if ($_SESSION['role_menu']['Monitoring']['alerts:action']['model_w']) {?>
        <li>
            <a class="link_btn" id="add" title="<?php echo __('creataction')?>"  href="###">
                <img width="16" height="16" alt="" src="<?php echo $this->webroot?>images/add.png"><?php echo __('createnew')?>
            </a>
        </li>
        <?php }?>
    </ul>
</div>
<div id="container">
    <ul class="tabs">
        <li>
            <a href="<?php echo $this->webroot; ?>alerts/rule">
                <img width="16" height="16" src="<?php echo $this->webroot; ?>images/ruler.png">Rule			
            </a>
        </li>
        <li class="active">
            <a href="<?php echo $this->webroot; ?>alerts/action">
                <img width="16" height="16" src="<?php echo $this->webroot; ?>images/action.png">Action			
            </a>
        </li>
        <li>
            <a href="<?php echo $this->webroot; ?>alerts/condition">
                <img width="16" height="16" src="<?php echo $this->webroot; ?>images/condition.png">Condition			
            </a>
        </li>
        <li>
            <a href="<?php echo $this->webroot; ?>alerts/block_ani">
                <img width="16" height="16" src="<?php echo $this->webroot; ?>images/fail.png">Block			
            </a>
        </li>
        <li>
            <a href="<?php echo $this->webroot; ?>alerts/trouble_tickets">
                <img width="16" height="16" src="<?php echo $this->webroot; ?>images/trouble_tickets.png">Trouble Tickets			
            </a>
        </li>
        <li>
            <a href="<?php echo $this->webroot; ?>alerts/trouble_tickets_template">
                <img width="16" height="16" src="<?php echo $this->webroot; ?>images/email.gif">Trouble Tickets Mail Template			
            </a>
        </li>
    </ul> 
    <?php 			
    $mydata =$p->getDataArray();
    $loop = count($mydata); 
    if(empty($mydata)):
    ?>
    <div class="msg"><?php echo __('no_data_found',true);?></div>
    <table class="list" style="display:none;">
        <thead style="background:none;">
            <tr>
                <td>Action Name</td>
                <td>Block ANI</td>
                <td>Loop Detection</td>
                <td>Trouble Tickets Mail Template</td>
                <td>Send Email</td>
                <td colspan="3">Disable Route</td>
                <td colspan="2">Change Priority</td>
                <td><?php echo __('Update By'); ?></td>
                <td><?php echo __('Update At'); ?></td>
                <td><?php echo __('Action'); ?></td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>Target</td>
                <td>Target</td>
                <td>Code Only</td>
                <td>Enable After (min)</td>
                <td>Target</td>
                <td>Change to Priority</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
        </thead>
        <tbody>
            
        </tbody>
    </table>
    <?php else:

    ?>
    <div id="toppage"></div>
    <table class="list">
        <thead style="background:none;">
            <tr>
                <td>Action Name</td>
                <td>Block ANI</td>
                <td>Loop Detection</td>
                <td>Trouble Tickets Mail Template</td>
                <td>Send Email</td>
                <td colspan="3">Disable Route</td>
                <td colspan="2">Change Priority</td>
                <td><?php echo __('Update By'); ?></td>
                <td><?php echo __('Update At'); ?></td>
                <td><?php echo __('Action'); ?></td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>Target</td>
                <td>Target</td>
                <td>Code</td>
                <td>Enable After (min)</td>
                <td>Target</td>
                <td>Change to Priority</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
        </thead>
        <tbody>
            <?php foreach($mydata as $item): ?>
            <tr>
                <td><?php echo $item[0]['name']; ?></td>
                <td><?php echo $item[0]['block_ani'] ? "Yes" : "No"; ?></td>
                <td><?php echo $item[0]['loop_detection'] ? "Yes" : "No"; ?></td>
                <td><?php echo $item[0]['template_name']; ?></td>
                <td><?php echo $send_mail_type[(int)$item[0]['email_notification']]; ?></td>
                <td><?php echo $disable_route_target[(int)$item[0]['disable_route_target']]; ?></td>
                <td>
                    <?php 
                         echo $item[0]['disable_code_trunk'] == 0 ? 'NO' : 'Yes';
                    ?>
                </td>
                <td><?php echo $item[0]['disable_duration']; ?></td>
                <td><?php echo $change_prioprity[(int)$item[0]['change_prioprity']]; ?></td>
                <td><?php echo $item[0]['change_to_priority']; ?></td>
                <td><?php echo $item[0]['update_at']; ?></td>
                <td><?php echo $item[0]['update_by']; ?></td>
                <td>
                   <?php
                   /*
                    <?php if($item[0]['block_ani']): ?>
                    <a title="Exclude ANI" class="exclude_ani" href="###" control="<?php echo $item[0]['id']?>" >
                        <img src="<?php echo $this->webroot?>images/unlock.png"/>
                    </a>
                    <?php endif; ?>
                    */
                    ?>
                    <a title="Edit" class="edit_item" href="###" control="<?php echo $item[0]['id']?>" >
                        <img src="<?php echo $this->webroot?>images/editicon.gif"/>
                    </a>

                    <a title="Delete" class="delete" control="<?php echo $item[0]['id']?>" href='###'>
                        <img src="<?php echo $this->webroot?>images/delete.png "/>
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div id="tmppage"><?php echo $this->element('page');?></div>

    <?php endif ;?>
</div>

<div id="popup_window" style="background:#fff;padding:10px;display:none;">
    <h1 style="padding:0;font-size:14px;">Excluded ANI</h1>
    <p>
        <textarea name="exclude_anis" id="exclude_anis" style="width:200px;height:100px;display:block;"></textarea>
    </p>
    <p style="text-align:center;">
        <input type="button" id="exclude_save" value="Save">
        <input type="button" id="exclude_cancel" value="Cancel">
    </p>
</div>
<script type="text/javascript" src="<?php echo $this->webroot ?>js/jquery.center.js"></script>
<script type="text/javascript">
    $(function() {
        $('.delete').click(function() {
            var $this = $(this);
            var action_id = $this.attr('control');
            $.ajax({
                url : '<?php echo $this->webroot; ?>alerts/action_used/' + action_id,
                type : 'GET',
                dataType : 'json',
                success : function(data) {
                    var result = false;
                    if(data.length > 0) {
                        result = window.confirm("The Action is being used by the following Rules:\n" + data.join(', ') + "\nDeleting this Action will causes the above rules to be removed.");
                    } else {
                        result = window.confirm("Are your sure?");
                    }
                    if(result) 
                        window.location.href = "<?php echo $this->webroot; ?>alerts/delete_alert_action/" + action_id;
                    else
                        return false;
                }
            });
        });
        
        jQuery('a.edit_item').click(function(){
            jQuery(this).parent().parent().trAdd({
                    action:'<?php echo $this->webroot ?>alerts/action_edit_panel/'+jQuery(this).attr('control'),
                    ajax:'<?php echo $this->webroot ?>/alerts/action_edit_panel/'+jQuery(this).attr('control'),
                    saveType:'edit',
                    callback : function() {
                        /*
                        $('#ActionBlockAni').live('change', function() {
                            var $this = $(this);
                            var $select = $this.parent().next().find('select');
                            if ($this.attr('checked'))
                            {
                                $select.show();
                            }
                            else
                            {
                                //$select.hide();
                                $('option[value=""]', $select).attr('selected', true);
                            }
                        }).trigger('change');
                        */
                    },
                    onsubmit: function(options)
                    {
                        var name = $("#ActionName").val();
                        if ('' == name)
                        {
                            jQuery.jGrowl("The action's name is required!",{theme:'jmsg-error'});
                            return false;
                        }
                        return true;
                    }
            });
	});
        
        /*
        jQuery('#add').click(
                function(){
                    $('.msg').hide();
                    jQuery('table.list body').trAdd({
                            ajax:"<?php echo $this->webroot ?>/alerts/action_edit_panel",
                            action:"<?php echo $this->webroot ?>/alerts/action_edit_panel",
                            saveType:'add',
                            removeCallback:function(){
                                    if(jQuery('table.list tr').size()==1){
                                            jQuery('table.list').hide();
                                    }
                            },
                            onsubmit: function(options)
                            {
                                var name = $("#ActionName").val();
                                if ('' == name)
                                {
                                    jQuery.jGrowl("The action's name is required!",{theme:'jmsg-error'});
                                    return false;
                                }
                                return true;
                            }
                    });
                    jQuery(this).parent().parent().show();
                }
        );
        */
        
        jQuery('a.link_btn').click(function(){
		jQuery('table.list').show().trAdd({
			action:'<?php echo $this->webroot ?>alerts/action_edit_panel',
			ajax:'<?php echo $this->webroot ?>alerts/action_edit_panel',
			removeCallback:function(){if(jQuery('table.list tr').size()==1){jQuery('table.list').hide()}},
                        onsubmit: function(options)
                        {
                            var name = $("#ActionName").val();
                            if ('' == name)
                            {
                                jQuery.jGrowl("The action's name is required!",{theme:'jmsg-error'});
                                return false;
                            }
                            return true;
                        }
		});
		//jQuery('#DigitTranslationName').attr('mycheck','add','maxLength','256');
	});
        
        var $popup_window = $('#popup_window');
        var $exclude_anis = $('#exclude_anis');
        
        
        $('#exclude_cancel').click(function() {
            $popup_window.hide();
        });
        
        jQuery('.exclude_ani').click(function() {
             var $this = $(this);
             var control = $this.attr('control');
             
              $('#exclude_save').unbind('click');
             
             $.ajax({
                'url' : '<?php echo $this->webroot ?>alerts/get_exclude_anis',
                'type' : 'POST',
                'dataType' : 'json',
                'data' : {'id' : control},
                'success' : function(data) {
                    $exclude_anis.val(data.data);
                    
                    $popup_window.center().show();
                }
            });
             
             
             $('#exclude_save').click(function() {
                 $.ajax({
                     'url' : '<?php echo $this->webroot ?>alerts/change_exclude_ani',
                     'type' : 'POST',
                     'dataType' : 'json',
                     'data' : {'id' : control, 'anis' : $exclude_anis.val()},
                     'success' : function(data) {
                         jQuery.jGrowl("Saved excluded anis!",{theme:'jmsg-success'});
                     }
                 });
             });
        });
        
    });
</script>