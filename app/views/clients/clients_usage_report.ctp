<div id="title">
     <h1><?php  __('Management')?> &gt;&gt; <?php echo __('Usage Report')?></h1>
</div>

<div id="container">
    <?php if(!empty($data)): ?>
    <table class="list">
        <thead>
            <tr>
                <td><?php __('Date') ?></td>
                <td><?php __('Minutes') ?></td>
                <td><?php __('Number Calls') ?></td>
                <td><?php __('Cost') ?></td>
            </tr>
        </thead>
        <tbody>
            <?php foreach($data as $item): ?>
            <tr>
                <td><?php echo $item[0]['report_time'] ?></td>
                <td><?php echo number_format($item[0]['bill_time'] / 60, 2); ?></td>
                <td><?php echo number_format($item[0]['success_calls']); ?></td>
                <td><?php echo number_format($item[0]['call_cost'] + $item[0]['lnp_cost'], 5); ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php endif; ?>
    
    <?php echo $form->create ('Cdr', array ('type'=>'get','url' => "/clients/clients_usage_report/" ,'onsubmit'=>"loading();"));?>
<fieldset class="query-box" style="clear:both;overflow:hidden;margin-top:10px;">
    <div class="search_title">
        <img src="<?php echo $this->webroot ?>images/search_title_icon.png">
        Search
    </div>
    <?php echo $this->element('search_report/search_js');?> <?php echo $this->element('search_report/search_hide_input');?>

    <?php
    $optionString = '<option value="all">All</option>';

    foreach($prefixs as $prefix){
        $selected = (isset($_GET['prefix'])&& $_GET['prefix'] == $prefix[0]['tech_prefix']) ? 'selected' : '';
        $optionString .= "<option value='{$prefix[0]['tech_prefix']}' {$selected}>{$prefix[0]['tech_prefix']}</option>";
    }
    ?>

    <table class="form" style="width:100%">
        <?php echo $this->element('report/form_period',array('group_time'=>false, 'gettype'=>"Prefix:
        <select name='prefix' style='width: 100px'>
            {$optionString}
        </select>"))?>
    </table>
    
</fieldset>
<?php echo $form->end();?>
</div>


    
<script>
$(function() {
    $('#query-start_date-wDt').val('<?php echo $start_date;?>');
});
</script>