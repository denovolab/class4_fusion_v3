<div id="title">
 <h1><?php  __('Management')?> &gt;&gt;<?php echo __(' Credit Management')?></h1>
        <ul id="title-search">
            <form method="get" id="myform1">
                <li>
                    <span style="float:left; height:25px; line-height:25px; font-size:14px; color:#EEEEEE; font-weight:bold;">Client Type:</span>
                    <select id="filter_client_type" name="filter_client_type" class="input in-select select">
                        <option value="0" <?php echo $common->set_get_select('filter_client_type', 0) ?>>All</option>
                        <option value="1" <?php echo $common->set_get_select('filter_client_type', 1, TRUE) ?>>All Active Clients</option>
                        <option value="2" <?php echo $common->set_get_select('filter_client_type', 2) ?>>All Inactive Clients</option>
                    </select>	
                </li>
                <li> 
                    <input type="text" id="search-_q" class="in-search default-value input in-text defaultText in-input" title="Search" value="Search" name="search">
                </li>
                    <input type="submit" id="submit" class="search_submit input in-submit" value="" name="submit">
            </form>
        </ul>
</div>

<div id="container">
    <?php
    	$is_exchange = Configure::read('system.type') === 2 ? TRUE : FALSE;
        $data =$p->getDataArray();
    ?>
    <div id="toppage"></div>
    <table class="list">
        <thead>
            <tr>
                <td><?php echo $appCommon->show_order('name',__('Name',true));?></td>
                <td><?php echo $appCommon->show_order('allowed_credit',__('Credit Limit',true))?></td>
                <td><?php echo $appCommon->show_order('cps_limit',__('CPS Limit',true));?></td>
                <td><?php echo $appCommon->show_order('call_limit', __('Call Limit',true));?></td>
                <td><?php echo $appCommon->show_order('update_at', __('Update At',true));?></td>
                <td><?php echo $appCommon->show_order('update_by', __('Update By',true));?></td>
                <td class="last"><?php echo __('action')?></td>
            </tr>
        </thead>
        <tbody>
            <?php foreach($data as $item): ?>
            <tr style="<?php if($item[0]['status'] == 0) echo 'background:#ccc;';?>">
                <td><a  href="<?php echo $this->webroot?>clients/edit/<?php echo $item[0]['client_id']?>" style="width:100%;display:block;"><?php echo $item[0]['name']; ?></a></td>
                <td><?php echo number_format(abs($item[0]['allowed_credit']), 3); ?></td>
                <td><?php echo $item[0]['cps_limit'] ?></td>
                <td><?php echo $item[0]['call_limit'] ?></td>
                <td><?php echo $item[0]['update_at'] ?></td>
                <td><?php echo $item[0]['update_by'] ?></td>
                <td>
                    <a title="Edit" class="edit_item" href="###" control="<?php echo $item[0]['client_id'] ?>" >
                        <img src="<?php echo $this->webroot?>images/editicon.gif"/>
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div id="tmppage"> <?php echo $this->element('page');?> </div>
</div>

<script>
    jQuery(function() {
        
        jQuery('a.edit_item').click(function(){
            jQuery(this).parent().parent().trAdd({
                action:'<?php echo $this->webroot ?>credit_managements/action_edit_panel/'+jQuery(this).attr('control'),
                ajax:'<?php echo $this->webroot ?>credit_managements/action_edit_panel/'+jQuery(this).attr('control'),
                saveType:'edit'
            });
        });
    });
</script>