<div id="title">
  <h1>
    <?php __('Management')?>
    &gt;&gt;
    <?php __('Exchange Transaction')?>
  </h1>
</div>

<?php
    $balance = $begin_balance;
?>

<div id="container">
    <?php if ($data !== NULL): ?>
    <?php
        $buy_total = 0;
        $sell_total = 0;
        $deposit_total = 0;
        $withdraw_total = 0;
    ?>
    <h4>Begin Time: <?php echo $_GET['start_time']; ?> &nbsp; Begin Balance: <?php echo number_format($balance, 3); ?></h4>
    <table class="list">
        <thead>
            <tr>
                <td></td>
                <td colspan="2">Cost</td>
                <td colspan="2">Payment</td>
                <td></td>
            </tr>
            <tr>
                <td>Date</td>
                <td>Buy</td>
                <td>Sell</td>
                <td>Deposit</td>
                <td>Withdraw</td>
                <td>Balance</td>
            </tr>
        </thead>  
        
        <tbody>
            <?php foreach($data as $key => $item): ?>
            <tr>
                <td><?php echo $key; ?></td>
                <td><?php echo $temp3=round((isset($item[1]) ? $item[1] : 0), 3); ?></td>
                <td><?php echo $temp4=round((isset($item[2]) ? $item[2] : 0), 3); ?></td>
                <td><?php echo $temp1=round((isset($item[3]) ? $item[3] : 0), 3); ?></td>
                <td><?php echo $temp2=round((isset($item[4]) ? $item[4] : 0), 3); ?></td>
                <td>
                    <?php
                        echo number_format((isset($item[0]) ? $item[0] : 0), 3);
                    ?>
                </td>
            </tr>
            <?php
                 $buy_total += $temp3;
                 $sell_total += $temp4;
                 $deposit_total += $temp1;
                 $withdraw_total += $temp2;
            ?>
            <?php endforeach; ?>
        </tbody>
    </table>
    <table class="list">
        <tr>
            <td>Buy Total</td>
            <td><?php echo $buy_total; ?></td>
            <td>Sell Total</td>
            <td><?php echo $sell_total; ?></td>
            <td>Deposit Total</td>
            <td><?php echo $deposit_total; ?></td>
            <td>Withdraw Total</td>
            <td><?php echo $withdraw_total; ?></td>
        </tr>
    </table>
    <?php endif; ?>
    
    <fieldset class="query-box" style=" clear:both;overflow:hidden;margin-top:10px;">
        <div class="search_title">
            <img src="<?php echo $this->webroot; ?>images/search_title_icon.png">
          Search  
        </div>
        <div style="margin:0px auto; text-align:center;">
        <form method="get" id="myform" name="myform">
            <input type="hidden" id="is_search" name="is_search" value="1" />
            Carrier:
            <select id="client" name="client_id" style="width:150px;">
                <?php foreach($clients as $client): ?>
                <option value="<?php echo $client[0]['client_id'] ?>" <?php if (isset($_GET['client_id']) && $_GET['client_id']== $client[0]['client_id']) echo 'selected="selected";'?>><?php echo $client[0]['name'] ?></option>
                <?php endforeach; ?>
            </select>
            Period:
            <input type="text" value="<?php echo $start_time ?>" onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss',lang:'en'})" style="width:120px;" name="start_time" class="input in-text in-input">
            ~
            <input type="text" value="<?php echo $end_time ?>" onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss',lang:'en'})" style="width:120px;" name="end_time" class="input in-text in-input">
            <input type="submit" value="Submit" class="input in-submit">
            <input type="button" id="export_pdf" value="Export pdf" />
            <input type="button" id="export_xls" value="Export xls" />
        </form>
        </div>
   </fieldset>    
</div>

<script type="text/javascript">
    $(function() {
        var $is_search =  $('#is_search');
        $is_search.val(1);
        $('#export_pdf').click(function() {
            $is_search.val(2);
            $('#myform').submit();
            $is_search.val(1);
            
        });
        $('#export_xls').click(function() {
            $is_search.val(3);
            $('#myform').submit();
            $is_search.val(1);
        });
    });    
</script>