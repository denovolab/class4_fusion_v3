<div id="title">
    <h1><?php echo __('Origination',true);?>&gt;&gt;<?php echo __('Client DID Assignment',true);?><?php if (isset($vendor_name)) echo "[{$vendor_name}]"; ?></h1>
    <ul id="title-search">
        <li style="display: list-item;" id="title-search-adv" onclick="advSearchToggle();" title="advanced search »"></li>
    </ul>
    <ul id="title-menu">
        <?php  if ($_SESSION['role_menu']['Origination']['did_reposs']['model_w']) {?>
        <li>
            <a class="link_btn" id="add" title="<?php echo __('creataction')?>"  href="<?php echo $this->webroot ?>did/did_assign/create">
                <img width="16" height="16" alt="" src="<?php echo $this->webroot?>images/add.png"><?php echo __('Create New Assignment')?>
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
                <td><?php echo $item['DidAssign']['number']; ?></td>
                <td><?php echo $ingresses[$item['DidAssign']['ingress_id']]; ?></td>
                <td><?php echo $egresses[$item['DidAssign']['egress_id']]; ?></td>
                <td><?php echo $item['DidAssign']['created_time']; ?></td>
                <td><?php echo $item['DidAssign']['assigned_time']; ?></td>
                <td><?php echo $item['DidRepos']['country']; ?></td>
                <td><?php echo $item['DidRepos']['rate_center']; ?></td>
                <td><?php echo $item['DidRepos']['state']; ?></td>
                <td><?php echo $item['DidRepos']['city']; ?></td>
                <td>
                    <a title="Edit" class="edit_item" href="###" control="<?php echo $item['DidAssign']['number']?>" >
                        <img src="<?php echo $this->webroot?>images/editicon.gif"/>
                    </a>
                    <?php if($item['DidAssign']['status'] == 0): ?>
                    <a href="<?php echo $this->webroot ?>did/did_assign/change_status/<?php echo $item['DidAssign']['number']; ?>/1"> 
                        <img src="<?php echo $this->webroot ?>images/flag-0.png" title="Active">
                    </a>
                    <?php elseif($item['DidAssign']['status'] == 1): ?>
                    <a href="<?php echo $this->webroot ?>did/did_assign/change_status/<?php echo $item['DidAssign']['number']; ?>/0"> 
                        <img src="<?php echo $this->webroot ?>images/flag-1.png" title="Inactive">
                    </a>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php echo $this->element("xpage")?>
    <?php endif; ?>
</div>

<script>
    $(function() {
        jQuery('a.edit_item').click(function(){
            jQuery(this).parent().parent().trAdd({
                action:'<?php echo $this->webroot ?>did/did_assign/action_edit_panel/<?php echo isset($this->params['pass'][0]) ?  $this->params['pass'][0] : '0';  ?>/'+jQuery(this).attr('control'),
                ajax:'<?php echo $this->webroot ?>did/did_assign/action_edit_panel/<?php echo isset($this->params['pass'][0]) ?  $this->params['pass'][0] : '0';  ?>/'+jQuery(this).attr('control'),
                saveType:'edit'
            });
        });
    });
</script>