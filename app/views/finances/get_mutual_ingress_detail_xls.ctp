<table class="list">
            <tr>
                <td colspan="3">Beginning Balance on <?php echo $start_time; ?> 00:00:00 is <?php echo $begin_balance; ?></td>
                <td colspan="3">Ending Balance on <?php echo $end_time; ?> 23:59:59 is <?php echo $end_balance; ?></td>
            </tr>
            <tr>
                <td>Date</td>
                <td>Invoice Sent</td>
                <td>Payment Received</td>
                <td>Credit Note Sent</td>
                <td>Debit Note Sent</td>
                <td>Balance</td>
            </tr>
        
            <?php foreach($financehistories as $financehistory): ?>
            <tr>
                <td><?php echo $financehistory['FinanceHistory']['date'];  ?></td>
                <td><?php echo $financehistory['FinanceHistory']['invoice_set'];  ?></td>
                <td><?php echo $financehistory['FinanceHistory']['payment_received'];  ?></td>
                <td><?php echo $financehistory['FinanceHistory']['credit_note_sent'];  ?></td>
                <td><?php echo $financehistory['FinanceHistory']['debit_note_sent'];  ?></td>
                <td><?php echo $financehistory['FinanceHistory']['mutual_ingress_balance'];  ?></td>
            </tr>
            <?php endforeach; ?>
    </table>