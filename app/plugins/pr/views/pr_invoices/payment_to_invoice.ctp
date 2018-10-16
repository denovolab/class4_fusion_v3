<style type="text/css">
.myform {
  border-collapse: collapse;
  border-spacing: 0;
  font-size: 0.97em;
  margin: 0 auto;
  width: 100%;
}
.myform .label {
    width:40%;
    text-align:right;
    padding-right:12px;
}
.myform .value {
    width:60%;
    text-align:left;
}
</style>

<div id="title">
    <h1>
        <?php  __('Finance')?>&gt;&gt;<?php __('Payment')?>
    </h1>
    <ul id="title-menu">
        <li>
            <a href="javascript:history.go(-1)" class="link_back">
                <img width="16" height="16" src="<?php echo $this->webroot; ?>images/icon_back_white.png" alt="Back">
                &nbsp;Back         
            </a>
        </li>
    </ul>
</div>

<div id="container">
    <table class="list">
        <thead>
            <tr>
                <td>Invoice Number</td>
                <td>Invoice Amount</td>
                <td>Due Amount</td>
                <td>Period</td>
                <td>Due Date</td>
                <td>Pay Amount</td>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><?php echo $invoice_info[0][0]['invoice_number']; ?></td>
                <td><?php echo round($invoice_info[0][0]['invoice_amount'], 2); ?></td>
                <td><?php echo round($invoice_info[0][0]['due_amount'], 2); ?></td>
                <td><?php echo $invoice_info[0][0]['invoice_start']; ?>~<?php echo $invoice_info[0][0]['invoice_end']; ?></td>
                <td><?php echo $invoice_info[0][0]['due_date']; ?></td>
                <td><?php echo round($invoice_info[0][0]['pay_amount'], 2); ?></td>
            </tr>
        </tbody>
    </table>
    <form name="myform" method="post">
    <table class="myform">
        <tr>
            <td class="label"><?php __("Payment Date"); ?>:</td>
            <td class="value"><input type="text" name="payment_date" onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss',lang:'en'})" style="width:120px;" /></td>
        </tr>
        <tr>
            <td class="label"><?php __("Payment"); ?>:</td>
            <td class="value"><input type="text"  style="width:120px;" name="payment" /></td>
        </tr>
        <tr>
            <td class="label"><?php __("Note"); ?>:</td>
            <td class="value"><textarea name="note" style="width:300px;height:80px;"></textarea></td>
        </tr>
        <tr>
            <td colspan="2">
                <input type="submit" value="Submit" />
            </td>
        </tr>
    </table>
    </form>
</div>