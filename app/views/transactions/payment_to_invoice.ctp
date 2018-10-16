<div style="padding:10px;">
<form id="invoice_form" method="post" action="<?php echo $this->webroot ?>transactions/payment_to_invoice/<?php echo $payment_invoice_id; ?>/<?php echo $client_id; ?>/<?php echo $type; ?>">
<table id="invoice_list" class="list">
    <thead>
        <tr>
                <th>Invoice Number</th>
                <th>Invoice Amount</th>
                <th>Pay Amount</th>
                <th>Period</th>
                <th>Given Amount</th>
                <th>Action</th>
            </tr>
    </thead>
    <tbody>
        
    </tbody>
</table>
</form>    
</div>