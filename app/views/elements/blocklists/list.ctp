<?php if (empty($this->data)) { ?>
    <?php echo $this->element('listEmpty') ?>
    <table class="list" style="display:none;">
        <thead>
            <tr>
                <td ><input id="selectAll" value="" type="checkbox"></input></td>
                <!--<td><?php echo $appCommon->show_order('res_block_id', __('ID', true)) ?> </td>-->
                <td><?php echo $appCommon->show_order('ani_prefix', __('ANI Prefix', true)) ?> </td>
                <td><?php echo $appCommon->show_order('digit', __('DNIS Prefix', true)) ?> </td>
                <?php /*
                  <td><?php echo $appCommon->show_order('egress_client_id',__('Egress Carriers',true))?> </td>
                  <td><?php echo $appCommon->show_order('ealias',__('Egress Trunk',true))?> </td>
                 * 
                 */ ?>
                <td><?php echo $appCommon->show_order('ingress_client_id', __('Carrier', true)) ?> </td>
                <td><?php echo $appCommon->show_order('inalias', __('Trunk', true)) ?> </td>

                <td><?php echo $appCommon->show_order('tname', __('Time Profile', true)) ?> </td>
                <td><?php echo $appCommon->show_order('ani_length', __('ANI Length', true)) ?> </td>
                <td><?php echo $appCommon->show_order('dnis_length', __('DNIS Length', true)) ?> </td>
                <td><?php echo $appCommon->show_order('dnis_length', __('Blocked By', true)) ?> </td>
                <td><?php echo $appCommon->show_order('dnis_length', __('Blocked On', true)) ?> </td>
                <?php if ($_SESSION['role_menu']['Routing']['blocklists']['model_w']) { ?><td  class="last"><?php echo __('action') ?></td><?php } ?>
            </tr>
        </thead>
    </table>
<?php } else { ?>
    <?php echo $this->element("xpage") ?>
    <div>
        <table class="list" id="list_id">
            <thead>
                <tr>
                    <td ><input id="selectAll" value="" type="checkbox"></input></td>
                    <!--<td><?php echo $appCommon->show_order('res_block_id', __('ID', true)) ?> </td>-->
                    <td><?php echo $appCommon->show_order('ani_prefix', __('ANI Prefix', true)) ?> </td>
                    <td><?php echo $appCommon->show_order('digit', __('DNIS Prefix', true)) ?> </td>
                    <?php /*
                      <td><?php echo $appCommon->show_order('egress_client_id',__('Egress Carriers',true))?> </td>
                      <td><?php echo $appCommon->show_order('ealias',__('Egress Trunk',true))?> </td>
                     * 
                     */ ?>
                    <td><?php echo $appCommon->show_order('ingress_client_id', __('Carrier', true)) ?> </td>
                    <td><?php echo $appCommon->show_order('inalias', __('Trunk', true)) ?> </td>

                    <td><?php echo $appCommon->show_order('tname', __('Time Profile', true)) ?> </td>
                    <td><?php echo $appCommon->show_order('ani_length', __('ANI Length', true)) ?> </td>
                    <td><?php echo $appCommon->show_order('dnis_length', __('DNIS Length', true)) ?> </td>
                    <td><?php echo $appCommon->show_order('dnis_length', __('Blocked By', true)) ?> </td>
                    <td><?php echo $appCommon->show_order('dnis_length', __('Blocked On', true)) ?> </td>
                    <?php if ($_SESSION['role_menu']['Routing']['blocklists']['model_w']) { ?><td  class="last"><?php echo __('action') ?></td><?php } ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($this->data as $list) { ?>
                    <tr >
                        <td><input selectDelete id="<?php echo $list['ResourceBlock']['res_block_id'] ?>" value="<?php echo $list['ResourceBlock']['res_block_id'] ?>" type="checkbox"></input></td>


        <!--		<td style="width:10%"><?php echo $list['ResourceBlock']['res_block_id'] ?></td>-->
                        <td style="width:30%">
                            <?php echo array_keys_value($list, 'ResourceBlock.ani_prefix'); ?>
                        </td>

                        <td style="width:20%">

                            <?php if ($_SESSION['role_menu']['Routing']['blocklists']['model_w']) { ?><a title="<?php echo __('edit') ?>" class="edit" href="<?php echo $this->webroot ?>blocklists/edit/<?php echo $list['ResourceBlock']['res_block_id'] ?>" res_block_id="<?php echo $list['ResourceBlock']['res_block_id'] ?>">
                                <?php echo array_keys_value($list, 'ResourceBlock.digit') ?>
                                </a>
                            <?php } else {
                                echo array_keys_value($list, 'ResourceBlock.digit');
                            } ?>

                        </td>
                        <?php if ($type == 2): ?>
                        <td style="width:10%"><?php echo array_keys_value($list, 'EgressClient.name') ?></td>
                        <td style="width:10%"><?php echo array_keys_value($list, 'Egress.alias') ?></td>
                        <?php else: ?>
                        <td style="width:20%"><?php echo array_keys_value($list, 'IngressClient.name') ?></td>
                        <td style="width:20%"><?php echo array_keys_value($list, 'Ingress.alias') ?></td>
                        <?php endif; ?>
                        <td style="width:30%"><?php echo array_keys_value($list, 'TimeProfile.name') ?></td>

                        <td style="width:30%">
                            <?php
                            $method = array_keys_value($list, 'ResourceBlock.ani_method');
                            if ($method === '0')
                                echo '<';
                            else if ($method === '1')
                                echo '=';
                            else if ($method === '2')
                                echo '>';
                            else
                                echo 'Any';
                            ?>
                            <?php echo array_keys_value($list, 'ResourceBlock.ani_length'); ?></td>
                        <td style="width:30%">
                            <?php
                            $method = array_keys_value($list, 'ResourceBlock.dnis_method');
                            if ($method === '0')
                                echo '<';
                            else if ($method === '1')
                                echo '=';
                            else if ($method === '2')
                                echo '>';
                            else
                                echo 'Any';
                            ?>
                            <?php echo array_keys_value($list, 'ResourceBlock.dnis_length'); ?>
                        </td>
                        <td>
                            <?php echo array_keys_value($list, 'ResourceBlock.action_type') == 0 ? 'Manual' : 'Alert' ?>
                        </td>
                        <td>
                        <?php echo array_keys_value($list, 'ResourceBlock.block_on'); ?>
                        </td>
        <?php if ($_SESSION['role_menu']['Routing']['blocklists']['model_w']) { ?><td style="width:10%" class="last">
                                <a title="<?php echo __('edit') ?>" class="edit" href="<?php echo $this->webroot ?>blocklists/edit/<?php echo $list['ResourceBlock']['res_block_id'] ?>/<?php echo $type; ?>" res_block_id="<?php echo $list['ResourceBlock']['res_block_id'] ?>">
                                    <img width="16" height="16" src="<?php echo $this->webroot ?>images/editicon.gif">
                                </a>
                                <a title="<?php echo __('del') ?>" class="delete" onclick="return confirm('Are you sure do this?');" href="<?php echo $this->webroot ?>blocklists/del/<?php echo $list['ResourceBlock']['res_block_id'] ?>/<?php echo $type; ?>">
                                    <img width="16" height="16" src="<?php echo $this->webroot ?>images/delete.png">
                                </a>
                            </td>
                    <?php } ?>
                    </tr>
    <?php } ?>
            </tbody></table>
    </div>
    <?php echo $this->element("xpage", array('id' => $type)) ?>
<?php } ?>
<script type="text/javascript">

    jQuery('.edit').click(
    function(){
        var action=jQuery(this).attr('href');
        var res_block_id=jQuery(this).attr('res_block_id');
        jQuery(this).parent().parent().trAdd(
        {
            ajax:"<?php echo $this->webroot ?>blocklists/js_save/<?php echo $type; ?>/?id="+res_block_id,
            action:action,
            saveType:'edit',
            callback:function(options){$('.method_select').trigger('change');return blocklist.trAddCallback(options);},
            onsubmit:function(options){return blocklist.trAddOnsubmit(options);},
            id:res_block_id
        }
    );
        return false;
    }
);
</script>

<script type="text/javascript">
    <!--
    jQuery(document).ready(function(){
        jQuery('#selectAll').selectAll('input[selectDelete]');
        
        
    });
    //-->
</script>

<script type="text/javascript">
    $(function() {
        $('.prefix_chk').live('change', function() {
            var $this = $(this);
            $this.siblings().val('').attr('disabled', $this.attr('checked'));
        });
    
        var $method_select = $('.method_select');

    
        $method_select.live('change', function() {
            var $this = $(this);
            var val = $this.val();
            if (val == '') {
                $this.next().hide();
            } else {
                $this.next().show();
            }
        });
    });
</script>