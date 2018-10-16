
<ul class="tabs">
    <li <?php if ($type == 1) echo'class="active"'; ?>><a href="<?php echo $this->webroot ?>blocklists/index/1"><img width="16" height="16" src="<?php echo $this->webroot ?>images/ingress.png"> <?php echo __('Ingress', true); ?></a></li>
    <li <?php if ($type == 2) echo'class="active"'; ?>><a href="<?php echo $this->webroot ?>blocklists/index/2"><img width="16" height="16" src="<?php echo $this->webroot ?>images/egress.png"> <?php echo __('Egress', true); ?></a></li>
    <?php if ($_SESSION['role_menu']['Routing']['blocklists']['model_x']) { ?>
        <li><a href="<?php echo $this->webroot ?>uploads/block_list"><img width="16" height="16" src="<?php echo $this->webroot ?>images/import.png"> <?php echo __('import', true); ?></a></li> 
        <li><a href="<?php echo $this->webroot ?>down/block"><img width="16" height="16" src="<?php echo $this->webroot ?>images/export.png"> <?php echo __('export', true); ?></a></li>  
    <?php } ?> 
</ul>
<fieldset class="title-block" id="advsearch" style="display: <?php  echo isset($_GET['advsearch']) ? 'block' :'none';?>;">
    <?php echo $form->create('Blocklist', array('action' => 'index/' . $type, 'type' => 'get')); ?>
    <input type="hidden" name="advsearch" class="input in-hidden">
    <table >
        <tbody>	
            <tr>
                <?php if ($type == 1): ?>
                <td>
                    <label><?php echo __('ingress') ?>:</label>
                    <?php echo $xform->search('filter_ingress_res_id', array('options' => $appBlocklists->_get_select_options($IngressList, 'Resource', 'alias', 'alias'), 'empty' => __('All', true), 'label' => false, 'div' => false, 'type' => 'select', 'style' => 'width:120px')); ?>

                </td>
                <?php else: ?>
                <td> <label><?php echo __('egress') ?>:</label>
                    <?php echo $xform->search('filter_egress_res_id', array('options' => $appBlocklists->_get_select_options($EgressList, 'Resource', 'alias', 'alias'), 'empty' => __('All', true), 'label' => false, 'div' => false, 'type' => 'select', 'style' => 'width:120px')); ?>
                </td>
                <?php endif; ?>
                <td><label><?php echo __('ANI Prefix') ?>:</label>
                    <?php echo $xform->search('filter_ani_prefix', array('label' => false, 'div' => false, 'type' => 'text')); ?>
                </td>
                <td><label><?php echo __('DNIS Prefix') ?>:</label>
                    <?php echo $xform->search('filter_digit', array('label' => false, 'div' => false, 'type' => 'text')); ?>
                </td>
                <td><label><?php echo __('Blocked By') ?>:</label>
                    <?php echo $xform->search('filter_action_type',  array('options' => array('Manual', 'Alert'), 'empty' => __('All', true), 'label' => false, 'div' => false, 'type' => 'select', 'style' => 'width:120px')); ?>
                </td>
                <td>
                    <input type="submit" class="input in-submit" value="<?php echo __('Search', true); ?>">
                </td>
            </tr>
        </tbody></table>
    <?php echo $form->end(); ?>
</fieldset>
<script type="text/javascript">
    <!--
    //取消准确查询
    jQuery('#title-search-adv').attr('style','display:none');
    //-->
</script>