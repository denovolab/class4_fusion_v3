<form id="myform" action="<?php echo $this->webroot ?>finances/quickadd" method="post">  
<input type="hidden" name="back_url" id="back_url" value="" />
<input type="hidden" name="client_id" value="<?php echo $client_id ?>" />
<div id="massadd_panel">
    <ul id="payment_panel_received">
        <li>
            <h3>
                <a class="delete" href="###" title="Delete">
                    <img src="<?php echo $this->webroot ?>images/delete.png ">
                </a>
                Payment Received
            </h3>
            <input type="hidden" name="payment_receiveds[]" value="1" />
        </li>
        <li>
            <span>Payment Type</span>
            <select class="input in-select select payment_type" name="payment_received_types[]" style="width:100px;" >
                <option value="0">Prepayment</option>
                <option value="1">Invoice Payment</option>
            </select>
        </li>
        <li style="display:none;">
            <span>Invoice Number</span>
            <select class="input in-select select" name="payment_received_numbers[]" style="width:100px;">
                <?php foreach($incomings as $incoming): ?>
                <option value="<?php echo $incoming[0]['invoice_id']; ?>"><?php echo $incoming[0]['invoice_number']; ?></option>
                <?php endforeach; ?>
            </select>
        </li>
        <li>
            <span>Received At</span>
            <input type="text" class="input in-text in-input" name="payment_received_dates[]" onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss',lang:'en'})" style="width:100px;" />
        </li>
        <li>
            <span>Amount</span>
            <input type="text" class="input in-text in-input" name="payment_received_amounts[]" style="width:100px;" />
        </li>
    </ul>
    
    <ul id="payment_panel_sent">
        <li>
            <h3>
                <a class="delete" href="###" title="Delete">
                    <img src="<?php echo $this->webroot ?>images/delete.png ">
                </a>
                Payment Sent
            </h3>
            <input type="hidden" name="payment_sents[]" value="1" />
        </li>
        <li>
            <span>Payment Type</span>
            <select class="input in-select select payment_type" name="payment_sent_types[]" style="width:100px;" >
                <option value="0">Prepayment</option>
                <option value="1">Invoice Payment</option>
            </select>
        </li>
        <li style="display:none;">
            <span>Invoice Number</span>
            <select class="input in-select select" name="payment_sent_numbers[]" style="width:100px;">
                <?php foreach($outgoings as $outgoing): ?>
                <option value="<?php echo $outgoing[0]['invoice_id']; ?>"><?php echo $outgoing[0]['invoice_number']; ?></option>
                <?php endforeach; ?>
            </select>
        </li>
        <li>
            <span>Received At</span>
            <input type="text" class="input in-text in-input" name="payment_sent_dates[]" onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss',lang:'en'})" style="width:100px;" />
        </li>
        <li>
            <span>Amount</span>
            <input type="text" class="input in-text in-input" name="payment_sent_amounts[]" style="width:100px;" />
        </li>
    </ul>
    
    <ul id="invoice_panel">
        <li>
            <h3>
                <a class="delete" href="###" title="Delete">
                    <img src="<?php echo $this->webroot ?>images/delete.png ">
                </a>
                Incoming Invoice
            </h3>
            <input type="hidden" name="incoming_invoices[]" value="1" />
        </li>
        <li>
            <span>Invoice Period</span>
            <input type="text" class="input in-text in-input" name="incoming_invoice_periods[]" onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss',lang:'en'})" value="<?php echo date("Y-m-d 00:00:00"); ?>" style="width:100px;" />
        </li>
        <li>
            <span>To</span>
            <input type="text" class="input in-text in-input" name="incoming_invoice_tos[]" onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss',lang:'en'})" value="<?php echo date("Y-m-d 23:59:59"); ?>" style="width:100px;" />
        </li>
        <li>
            <span>GMT</span>
            <select class="input in-select select" name="incoming_invoice_timezones[]" style="width:100px;" >
                <option value="-1200">GMT -12:00</option>
                <option value="-1100">GMT -11:00</option>
                <option value="-1000">GMT -10:00</option>
                <option value="-0900">GMT -09:00</option>
                <option value="-0800">GMT -08:00</option>
                <option value="-0700">GMT -07:00</option>
                <option value="-0600">GMT -06:00</option>
                <option value="-0500">GMT -05:00</option>
                <option value="-0400">GMT -04:00</option>
                <option value="-0300">GMT -03:00</option>
                <option value="-0200">GMT -02:00</option>
                <option value="-0100">GMT -01:00</option>
                <option value="+0000" selected="selected">GMT +00:00</option>
                <option value="+0100">GMT +01:00</option>
                <option value="+0200">GMT +02:00</option>
                <option value="+0300">GMT +03:00</option>
                <option value="+0330">GMT +03:30</option>
                <option value="+0400">GMT +04:00</option>
                <option value="+0500">GMT +05:00</option>
                <option value="+0600">GMT +06:00</option>
                <option value="+0700">GMT +07:00</option>
                <option value="+0800">GMT +08:00</option>
                <option value="+0900">GMT +09:00</option>
                <option value="+1000">GMT +10:00</option>
                <option value="+1100">GMT +11:00</option>
                <option value="+1200">GMT +12:00</option>
            </select>
        </li>
        <li>
            <span>Invoice Date</span>
            <input type="text" class="input in-text in-input" name="incoming_invoice_dates[]" onclick="WdatePicker({dateFmt:'yyyy-MM-dd',lang:'en'})"  style="width:100px;" />
        </li>
        <li>
            <span>Due Date</span>
            <input type="text" class="input in-text in-input" name="incoming_invoice_due_dates[]" onclick="WdatePicker({dateFmt:'yyyy-MM-dd',lang:'en'})"  style="width:100px;" />
        </li>
        <li>
            <span>Amount</span>
            <input type="text" class="input in-text in-input" name="incoming_invoice_amounts[]" style="width:100px;" />
        </li>
    </ul>
</div>
</form>

