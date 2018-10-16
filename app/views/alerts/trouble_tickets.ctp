<style type="text/css">
    .list tbody tr span {margin:0 10px;}
</style>

<div id="title">
    <h1><?php echo __('Monitoring',true);?>&gt;&gt;<?php echo __('Trouble Tickets',true);?></h1>
    <ul id="title-search">
        <form id="myform1" method="get">
            <li>
                <select class="input in-select select" name="search_type">
                    <option selected="selected" value="0" <?php if (isset($_GET['search_type']) && $_GET['search_type'] == 0) echo 'selected="selected"'; ?>>Destination</option>
                    <option value="1" <?php if (isset($_GET['search_type']) && $_GET['search_type'] == 1) echo 'selected="selected"'; ?>>Rule Name</option>
                </select>	
            </li>
            <li> 
                <input type="text" name="search" value="Search" title="Search" class="in-search default-value input in-text defaultText in-input" id="search-_q">
            </li>
                <li class="opened" title="advanced search »" onclick="advSearchToggle();" id="title-search-adv" style="display: list-item;"></li>
        </form>
    </ul>
    <ul id="title-menu">
        <li>
            <a href="###" class="link_btn block_unblock_selected" block_type="2">
                Block Selected
            </a>
        </li>
        <li>
            <a href="<?php echo $this->webroot ?>alerts/trouble_block_unblock_all/2" class="link_btn">
                Block All
            </a>
        </li>
        <li>
            <a href="###" class="link_btn block_unblock_selected" block_type="1">
                Unblock Selected
            </a>
        </li>
        <li>
            <a href="<?php echo $this->webroot ?>alerts/trouble_block_unblock_all/1" class="link_btn">
                Ublock All
            </a>
        </li>
        <li>
            <a href="###" class="link_btn delete_selected">
                Delete Selected
            </a>
        </li>
        <li>
            <a href="<?php echo $this->webroot ?>alerts/trouble_tickets_delete_all" class="link_btn">
                Delete All
            </a>
        </li>
    </ul>
</div>

<div id="container">
    <fieldset style="width: 100%; margin-left: 1px; display: <?php echo isset($_GET['advsearch']) ? 'block' : 'none' ?>;" id="advsearch" class="title-block">
    <form method="get">
      <input type="hidden" value="1" name="advsearch" class="input in-hidden">
      <table style="width:auto;">
        <tbody>
          <tr>
            <td><label style="padding-top:3px;">Ingress Trunk:</label>
             <select name="ingress_trunk">
                    <option value="0">All</option>
                    <?php foreach($ingresses as $key => $ingress): ?>
                    <option value="<?php echo $key; ?>" <?php if(isset($_GET['ingress_trunk']) && $_GET['ingress_trunk'] == $key) echo 'selected="selected"'; ?>><?php echo $ingress; ?></option>
                    <?php endforeach; ?>
                </select>
            </td>
            <td><label style="padding-top:3px;">Egress Trunk</label>
              <select name="egress_trunk">
                    <option value="0">All</option>
                    <?php foreach($egresses as $key => $egress): ?>
                    <option value="<?php echo $key; ?>" <?php if(isset($_GET['egress_trunk']) && $_GET['egress_trunk'] == $key) echo 'selected="selected"'; ?>><?php echo $egress; ?></option>
                    <?php endforeach; ?>
                </select>
            </td>
            <td><label style="padding-top:3px;">Code</label>
                <input type="text" name="code" value="<?php echo isset($_GET['code']) ? $_GET['code'] : ''; ?>" />
            </td>
            <td></td>
            <td class="buttons"><input type="submit" class="input in-submit" value="Search"></td>
          </tr>
        </tbody>
      </table>
    </form>
      </fieldset>
    <ul class="tabs">
        <li>
            <a href="<?php echo $this->webroot; ?>alerts/rule">
                <img width="16" height="16" src="<?php echo $this->webroot; ?>images/ruler.png">Rule			
            </a>
        </li>
        <li>
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
        <li class="active">
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
        if(empty($this->data)): 
    ?>
    <div class="msg"><?php echo __('no_data_found',true);?></div>
    <table class="list" style="display:none;">
        
        <thead>
            <tr>
                <td><input type="checkbox" id="select_all" /></td>
                <td>Ingress Trunk</td>
                <td>Egress Trunk</td>
                <td>Blocked Time</td>
                <td>Unblocked Time</td>
                <td>Destination</td>
                <td>Rule Name</td>
                <td colspan="2">Sample Time</td>
                <td colspan="2">Call Count</td>
                <td>Action</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>Start</td>
                <td>End</td>
                <td>Call Attempt</td>
                <td>Succ. Calls</td>
                <td>&nbsp;</td>
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
                <td><input type="checkbox" id="select_all" /></td>
                <td>Ingress Trunk</td>
                <td>Egress Trunk</td>
                <td>Blocked Time</td>
                <td>Unblocked Time</td>
                <td>Destination</td>
                <td>Rule Name</td>
                <td colspan="2">Sample Time</td>
                <td colspan="2">Call Count</td>
                <td>Action</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>Start</td>
                <td>End</td>
                <td>Call Attempt</td>
                <td>Succ. Calls</td>
                <td>&nbsp;</td>
            </tr>
        </thead>
        
        <tbody>
            <?php foreach($this->data as $item): ?>
            <tr>
                <td><input type="checkbox" class="select_option" value="<?php echo $item['BlockTicket']['code_name']?>" /></td>
                <td><?php echo $item['BlockTicket']['ingress'] ? $resources[$item['BlockTicket']['ingress']] : ''; ?></td>
                <td><?php echo $item['BlockTicket']['egress']? $resources[$item['BlockTicket']['egress']] : ''; ?></td>
                <td><?php echo $item['BlockTicket']['blocked_time']; ?></td>
                <td><?php echo $item['BlockTicket']['unblock_time']; ?></td>
                <td><?php echo $item['BlockTicket']['code_name']; ?></td>
                <td><?php echo $item['BlockTicket']['rule_name']; ?></td>
                <td><?php echo $item['BlockTicket']['start_time']; ?></td>
                <td><?php echo $item['BlockTicket']['end_time']; ?></td>
                <td><?php echo $item['BlockTicket']['calls']; ?></td>
                <td><?php echo $item['BlockTicket']['not_zero_calls']; ?></td>
                <td>
                    <?php if($item['BlockTicket']['block']): ?>
                    <a title="Unblock" class="edit_item" href="<?php echo $this->webroot ?>alerts/trouble_block_ani_change/<?php echo $item['BlockTicket']['code_name']?>/1">
                        <img src="<?php echo $this->webroot?>images/flag-1.png"/>
                    </a>
                    <?php else: ?>
                    <a title="Block" class="edit_item" href="<?php echo $this->webroot ?>alerts/trouble_block_ani_change/<?php echo $item['BlockTicket']['code_name']?>/2">
                        <img src="<?php echo $this->webroot?>images/flag-0.png"/>
                    </a>
                    <?php endif; ?>
                    <a title="Delete"  href="<?php echo $this->webroot ?>alerts/trouble_tickets_delete/<?php echo $item['BlockTicket']['code_name']?>">
                         <img src="<?php echo $this->webroot?>images/delete.png"/>
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php echo $this->element("xpage")?>
    <?php endif; ?>
</div>

<script type="text/javascript">
    $(function() {
        $('#select_all').change(function() {
            $('.select_option').attr('checked', $(this).attr('checked'));
        }).trigger('change');
        
        $('.block_unblock_selected').click(function() {
            if (confirm('Are you sure do this?'))
            {
                var $selected_checked = $('.select_option:checked');
                var ids = new Array();
                $selected_checked.each(function(index, item) {
                    ids.push($(this).val());
                });
                
                if (ids.length == 0)
                {
                    jQuery.jGrowl('Please select at least one!',{theme:'jmsg-error'});    
                    return;
                }
                
                $.ajax({
                    'url' : '<?php echo $this->webroot; ?>alerts/trouble_block_unblock_selected/' + $(this).attr('block_type'),
                    'type' : 'POST',
                    'dataType' : 'text',
                    'data' : {'ids[]' : ids},
                    'success' : function(data) {
                        jQuery.jGrowl('You options were modified sucessfully',{theme:'jmsg-success'});
                        window.setTimeout(function() {window.location.reload(true)},3000);
                    }
                });
            }
        });
        
        $('.delete_selected').click(function() {
            if (confirm('Are you sure do this?'))
            {
                var $selected_checked = $('.select_option:checked');
                var ids = new Array();
                $selected_checked.each(function(index, item) {
                    ids.push($(this).val());
                });
                
                if (ids.length == 0)
                {
                    jQuery.jGrowl('Please select at least one!',{theme:'jmsg-error'});    
                    return;
                }
                
                $.ajax({
                    'url' : '<?php echo $this->webroot; ?>alerts/trouble_tickets_delete_selected',
                    'type' : 'POST',
                    'dataType' : 'text',
                    'data' : {'ids[]' : ids},
                    'success' : function(data) {
                        jQuery.jGrowl('You options were deleted succesfully',{theme:'jmsg-success'});
                        window.setTimeout(function() {window.location.reload(true)},3000);
                    }
                });
            }
        });
    });
</script>
    