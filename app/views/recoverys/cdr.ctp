<div id="title"> 
    <h1><?php __('Tools') ?> &gt;&gt; <?php __('CDR Recovery'); ?></h1> 
</div> 

<div id="container">
    <?php
        if(empty($data)): 
    ?>
    <div class="msg"><?php echo __('no_data_found',true);?></div>
    <?php else: ?>
    <table class="list">
        <thead>
            <tr>
                <td>File Name</td>
                <td>File Size</td>
            </tr>
        </thead>
        <tbody>
            <?php foreach($data as $item): ?>
            <tr>
                <td><?php echo $item[8]; ?></td>
                <td><?php echo $item[4]; ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php endif; ?>
    <fieldset class="query-box" style=" clear:both;overflow:hidden;margin-top:10px;">
        <div class="search_title">
          <img src="<?php echo $this->webroot ?>images/recovery.png">
          Recovery  
        </div>
        <div style="margin:0px auto; text-align:center;">
        <form id="myform" method="post" name="myform">
            Period:
            <input type="text" value="<?php echo date("Y-m-d 00:00:00"); ?>" onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss',lang:'en'})" style="width:120px;" name="start" class="input in-text in-input">
            ~
            <input type="text" value="<?php echo date("Y-m-d 23:59:59"); ?>" onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss',lang:'en'})" style="width:120px;" name="end" class="input in-text in-input">
            <input type="submit" value="Submit" class="input in-submit">
        </form>
        </div>
   </fieldset>
</div>