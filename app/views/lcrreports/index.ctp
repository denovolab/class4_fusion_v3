<div id="title">
    <h1><?php echo __('Statistics',true);?>&gt;&gt;<?php echo __('LCR Report',true);?></h1>
    <ul id="title-menu">
        <?php  if ($_SESSION['role_menu']['Statistics']['lcrreports']['model_w']) {?>
        <li>
            <a class="link_btn" id="add" title="<?php echo __('creataction')?>"  href="<?php echo $this->webroot?>lcrreports/add">
                <img width="16" height="16" alt="" src="<?php echo $this->webroot?>images/add.png"><?php echo __('createnew')?>
            </a>
        </li>
        <?php }?>
    </ul>
</div>

<div id="container">
    <?php
        if(empty($this->data)): 
    ?>
    <div class="msg"><?php echo __('no_data_found',true);?></div>
    <table class="list" style="display:none;">
        <thead>
            <tr>
                <td>Time</td>
                <td>Type</td>
                <td>Rate Table</td>
                <td>Status</td>
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
                <td>Time</td>
                <td>Type</td>
                <td>Rate Table</td>
                <td>Status</td>
                <td>Action</td>
            </tr>
        </thead>
        
        <tbody>
            <?php foreach($this->data as $item): ?>
            <tr>
                <td><?php echo $item['LcrRecord']['time'] ?></td>
                <td><?php echo $item['LcrRecord']['type'] ?></td>
                <td>
                    <?php 
                    
                    $rate_table_names = array();
                    
                    $rate_tables = explode(',', $item['LcrRecord']['rate_tables']);
                    
                    foreach($rate_tables as $rate_table):
                    
                    array_push($rate_table_names, $common->get_rate_table($rate_table)) ;
                    
                    endforeach;
                    
                    $rate_table_str = implode(',',$rate_table_names);
                    
                    ?>
                    <a href="###" class="showdetail" detail="<?php echo $rate_table_str ?>">
                        <?php echo substr($rate_table_str, 0, 20); ?>
                    </a>
                </td>
                <td><?php echo $item['LcrRecord']['status'] == 0 ? 'Progress' : 'Done' ?></td>
                <td>
                    <?php if($item['LcrRecord']['status'] == 1): ?>
                    <a href="<?php echo $this->webroot ?>lcrreports/get_file/<?php echo $item['LcrRecord']['id'] ?>" class="download" title="Download">
                        <img src="<?php echo $this->webroot ?>images/file.png ">
                    </a>
                    <?php endif; ?>
                    <?php if($_SESSION['role_menu']['Statistics']['lcrreports']['model_w']): ?>
                    <a href="<?php echo $this->webroot ?>lcrreports/delete/<?php echo $item['LcrRecord']['id'] ?>" class="delete" title="Delete">
                        <img src="<?php echo $this->webroot ?>images/delete.png ">
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

<div id="pop-div" class="pop-div" style="width: 400px; height: 180px; position: absolute; left: 50%; top: 50%; z-index: 9999; margin-top: 0px;display:none;word-wrap: break-word; ">
    <p style="text-align:left;padding:10px;">
        
    </p>
</div>

<script>
    $(function() {
        $('.showdetail').click(function() {
            $('#pop-div').show();
            $('#pop-div').find('p').text($(this).attr('detail'));
           
        });
        
        $('#pop-div').click(function() {
            $(this).hide();
        })
    });
</script>
