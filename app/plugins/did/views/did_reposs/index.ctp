<div id="title">
    <h1><?php echo __('Origination',true);?>&gt;&gt;<?php echo __('Vendor DID Repository',true);?><?php if (isset($vendor_name)) echo "[{$vendor_name}]"; ?></h1>
    <ul id="title-search">
        <li style="display: list-item;" id="title-search-adv" onclick="advSearchToggle();" title="advanced search »"></li>
    </ul>
    <ul id="title-menu">
        <?php  if ($_SESSION['role_menu']['Origination']['did_reposs']['model_w']) {?>
        <li>
            <a class="link_btn" id="add" title="<?php echo __('creataction')?>"  href="###">
                <img width="16" height="16" alt="" src="<?php echo $this->webroot?>images/add.png"><?php echo __('createnew')?>
            </a>
        </li>
        <li>
            <a class="link_btn" title="<?php echo __('Upload')?>"  href="<?php echo $this->webroot; ?>did/did_reposs/upload">
                <img width="16" height="16" alt="" src="<?php echo $this->webroot?>images/upload_icon.png"><?php echo __('Upload')?>
            </a>
        </li>
        <li>
            <form id="export_form" method="post">
            <input type="hidden" name="export_csv" value="1">
            <a class="link_btn" id="export_csv" title="<?php echo __('Export')?>"  href="###">
                <img width="16" height="16" alt="" src="<?php echo $this->webroot?>images/export.png"><?php echo __('Export')?>
            </a>
            </form>
        </li>
        <li>
            <a rel="popup" id="delete_selected" class="link_btn" href="###">
                <img width="16" height="16" src="<?php echo $this->webroot?>images/delete.png">Delete Selected
            </a>
        </li>
        <li>
            <a  class="link_btn" href="<?php echo $this->webroot ?>did/did_reposs/delete_uploaded">
                <img width="16" height="16" src="<?php echo $this->webroot?>images/delete.png">Delete Uploaded
            </a>
        </li>
        <li>
            <form>
                <input type="text" name="search" value="<?php echo isset($_GET['number']) ? $_GET['number'] : 'Search'; ?>" class="in-search default-value input in-text defaultText in-input" id="search-_q">
                <input type="submit" name="submit" value="" class="search_submit input in-submit" id="submit">
            </form>
        </li>
        <?php }?>
    </ul>
</div>

<div id="container">
    <fieldset style="margin-left: 1px; width: 100%; display: <?php  echo isset($url_get['advsearch']) ? 'block' :'none';?>;" id="advsearch" class="title-block">
    <form method="get" id="search_panel">
      <input type="hidden" name="advsearch" class="input in-hidden">
      <table>
        <tr>
            <td>Vendor</td>
            <td>
                <select name="ingress_id">
                    <option value="">All</option>
                    <?php foreach($ingresses as $key => $ingress): ?>
                    <option <?php if(isset($_GET['ingress_id']) && $_GET['ingress_id'] == $key) echo 'selected="selected"'; ?> value="<?php echo $key ?>"><?php echo $ingress ?></option>
                    <?php endforeach; ?>
                </select>
            </td>
            <td>Client</td>
            <td>
                <select name="egress_id">
                    <option value="">All</option>
                    <?php foreach($egresses as $key => $egress): ?>
                    <option <?php if(isset($_GET['egress_id']) && $_GET['egress_id'] == $key) echo 'selected="selected"'; ?> value="<?php echo $key ?>"><?php echo $egress ?></option>
                    <?php endforeach; ?>
                </select>
            </td>
            <td>Number</td>
            <td>
                <input type="text" name="number" value="<?php echo $common->set_get_value('number') ?>" />
            </td>
            <td>Show</td>
            <td>
                <select name="show">
                    <option value="" <?php echo $common->set_get_select('show', '', true) ?>>All</option>
                    <option value="1" <?php echo $common->set_get_select('show', 1) ?>>Assigned</option>
                    <option value="2" <?php echo $common->set_get_select('show', 2) ?>>Unassigned</option>
                </select>
            </td>
            <td>
                <input type="submit" value="Search" />
            </td>
        </tr>
      </table>
    </form>
  </fieldset>
    <?php
    if(empty($this->data)): 
    ?>
    <div class="msg"><?php echo __('no_data_found',true);?></div>
    <table class="list" style="display:none;">
        <thead>
            <tr>
                <td>
                    <input type="checkbox" id="selectAll">
                </td>
                <td>DID</td>
                <td>DID Vendor</td>
                <td>DID Client</td>
                <td>Created Time</td>
                <td>Assigned Time</td>
                <td>Country</td>
                <td>Rate Center</td>
                <td>State</td>
                <td>City</td>
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
                <td>
                    <input type="checkbox" id="selectAll">
                </td>
                <td>DID</td>
                <td>DID Vendor</td>
                <td>DID Client</td>
                <td>Created Time</td>
                <td>Assigned Time</td>
                <td>Country</td>
                <td>Rate Center</td>
                <td>State</td>
                <td>City</td>
                <td>Action</td>
            </tr>
        </thead>

        <tbody>
            <?php foreach($this->data as $item): ?>
            <tr>
                <td>
                    <input type="checkbox" class="multi_select" value="<?php echo $item['DidRepos']['number']; ?>">
                </td>
                <td><?php echo $item['DidRepos']['number']; ?></td>
                <td><?php echo $ingresses[$item['DidRepos']['ingress_id']]; ?></td>
                <td><?php echo $item['DidRepos']['egress_id'] ? $egresses[$item['DidRepos']['egress_id']] : ''; ?></td>
                <td><?php echo $item['DidRepos']['created_time']; ?></td>
                <td><?php echo $item['DidRepos']['updated_time']; ?></td>
                <td><?php echo $item['DidRepos']['country']; ?></td>
                <td><?php echo $item['DidRepos']['rate_center']; ?></td>
                <td><?php echo $item['DidRepos']['state']; ?></td>
                <td><?php echo $item['DidRepos']['city']; ?></td>
                <td>
                    <?php if($item['DidRepos']['status'] == 0): ?>
                    <a href="<?php echo $this->webroot ?>did/did_reposs/change_status/<?php echo $item['DidRepos']['number']; ?>/1/<?php echo isset($this->params['pass'][0]) ? $this->params['pass'][0] : '';  ?>"> 
                        <img src="<?php echo $this->webroot ?>images/flag-0.png" title="Active">
                    </a>
                    <?php elseif($item['DidRepos']['status'] == 1): ?>
                    <a href="<?php echo $this->webroot ?>did/did_reposs/change_status/<?php echo $item['DidRepos']['number']; ?>/0/<?php echo isset($this->params['pass'][0]) ? $this->params['pass'][0] : '';  ?>"> 
                        <img src="<?php echo $this->webroot ?>images/flag-1.png" title="Inactive">
                    </a>
                    <?php endif; ?>
                    <a title="Edit" class="edit_item" href="###" control="<?php echo $item['DidRepos']['number']?>" >
                        <img src="<?php echo $this->webroot?>images/editicon.gif"/>
                    </a>

                    <a title="Delete" class="delete" href='<?php echo $this->webroot ;?>did/did_reposs/delete/<?php echo $item['DidRepos']['number']?>/<?php echo isset($this->params['pass'][0]) ? $this->params['pass'][0] : '';  ?>'>
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
                ajax:"<?php echo $this->webroot ?>did/did_reposs/action_edit_panel/<?php echo isset($this->params['pass'][0]) ? $this->params['pass'][0] : '';  ?>",
                action:"<?php echo $this->webroot ?>did/did_reposs/action_edit_panel/<?php echo isset($this->params['pass'][0]) ? $this->params['pass'][0] : '';  ?>",
                insertNumber : 'first',
                removeCallback:function(){
                    if(jQuery('table.list tr').size()==1){
                        jQuery('table.list').hide();
                        $('.msg').show();
                    }
                },
                onsubmit: function(options)
                {
                    var number = $('#DidReposNumber').val();
                    var is_exists = jQuery.ajaxData("<?php echo $this->webroot ?>did/did_reposs/chech_num/" + number);
                    if(is_exists.indexOf("true") != -1)
                    {
                        jQuery.jGrowl("The number [" + number + "] already exists!",{theme:'jmsg-error'});
                        return false;
                    }
                    return true;
                }
            });
            jQuery(this).parent().parent().show();
        });
        
        jQuery('a.edit_item').click(function(){
            jQuery(this).parent().parent().trAdd({
                action:'<?php echo $this->webroot ?>did/did_reposs/action_edit_panel/<?php echo isset($this->params['pass'][0]) ?  $this->params['pass'][0] : '0';  ?>/'+jQuery(this).attr('control'),
                ajax:'<?php echo $this->webroot ?>did/did_reposs/action_edit_panel/<?php echo isset($this->params['pass'][0]) ?  $this->params['pass'][0] : '0';  ?>/'+jQuery(this).attr('control'),
                saveType:'edit'
            });
        });
        
        var $selectAll = $('#selectAll');
        var $multi_select = $('.multi_select');
        var $delete_selected = $('#delete_selected');
        var $export_csv = $('#export_csv');
        var $export_form = $('#export_form');
        
        $selectAll.change(function() {
            $multi_select.attr('checked', $(this).attr('checked'));
        });
        
        
        $export_csv.click(function() {
            $export_form.submit();
        });
        
        
        $delete_selected.click(function() {
            var selected = new Array();
            if (confirm('Are you sure?')) {
                $multi_select.each(function() {
                    var $this = $(this);
                    if ($this.is(':checked')) {
                        selected.push($this.val());
                    }
                });
            }
            if(selected.length) {
                $.ajax({
                    'url' : '<?php echo $this->webroot; ?>did/did_reposs/mutiple_delete',
                    'type' : 'POST',
                    'dataType' : 'json',
                    'data' : {'selecteds[]' : selected},
                    'success' : function(data) {
                        jQuery.jGrowl("The numbers you selected is deleted successfully!",{theme:'jmsg-success'});
                        window.setTimeout("window.location.reload();", 3000);
                    }
                });
            } else {
                jQuery.jGrowl("You did not select any item!",{theme:'jmsg-error'});
            }
        });
    });
</script>
