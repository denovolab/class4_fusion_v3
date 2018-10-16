<div id="title">
     <h1><?php  __('Management')?> &gt;&gt; <?php echo __('Payment Record')?></h1>
     <ul id="title-search">
         <li>
             <form method="get" name="myform">
                Period:
                <input type="text" value="<?php echo $start_time ?>" onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss',lang:'en'})" style="width:120px;" name="start_time" class="input in-text in-input">
                ~
                <input type="text" value="<?php echo $end_time ?>" onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss',lang:'en'})" style="width:120px;" name="end_time" class="input in-text in-input">
                <input type="submit" class="input in-submit query_btn" value="Query">
            </form>
         </li>
     </ul>
</div>

<div id="container">
    <?php if(empty($data)): ?>
    <div class="msg">No Data is Available.</div>
    <?php else: ?>
    <table class="list">
        <thead>
            <tr>
                <td><?php __('Enter Date') ?></td>
                <td><?php __('Payment Date') ?></td>
                <td><?php __('Amount') ?></td>
                <td><?php __('Detail') ?></td>
            </tr>
        </thead>
        <tbody>
            <?php foreach($data as $item): ?>
            <tr>
                <td><?php echo substr_replace($item[0]['payment_time'],"",19,-3); ?></td>
                <td><?php echo $item[0]['receiving_time'] ?></td>
                <td><?php echo $item[0]['amount'] ?></td>
                <td><?php echo $item[0]['description'] ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php endif; ?>
    <!--
    <fieldset class="query-box" style=" clear:both;overflow:hidden;margin-top:10px;">
        <div class="search_title">
            <img src="<?php echo $this->webroot; ?>images/search_title_icon.png">
          Search  
        </div>
        <div style="margin:0px auto; text-align:center;">
        <form method="get" name="myform">
            Period:
            <input type="text" value="<?php echo $start_time ?>" onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss',lang:'en'})" style="width:120px;" name="start_time" class="input in-text in-input">
            ~
            <input type="text" value="<?php echo $end_time ?>" onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss',lang:'en'})" style="width:120px;" name="end_time" class="input in-text in-input">
            <input type="submit" value="Submit" class="input in-submit">
        </form>
        </div>
   </fieldset>
   -->
</div>
