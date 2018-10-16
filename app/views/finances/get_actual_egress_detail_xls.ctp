
    <table class="list">
            <tr>
                <td colspan="3">Beginning Balance on <?php echo $start_time; ?> 00:00:00 is <?php echo $begin_balance; ?></td>
                <td colspan="3">Ending Balance on <?php echo $end_time; ?> 23:59:59 is <?php echo $end_balance; ?></td>
            </tr>
            <tr>
                <td>Date</td>
                <td>Payment Sent</td>
                <td>Credit Note Received</td>
                <td>Debit Note Received</td>
                <td>Unbilled Outgoing Traffic</td>
                <td>Balance</td>
            </tr>
        
            <?php foreach($financehistories as $financehistory): ?>
            <tr>
                <td><?php echo $financehistory['FinanceHistoryActual']['date'];  ?></td>
                <td><?php echo $financehistory['FinanceHistoryActual']['payment_sent'];  ?></td>
                <td><?php echo $financehistory['FinanceHistoryActual']['credit_note_received'];  ?></td>
                <td><?php echo $financehistory['FinanceHistoryActual']['debit_note_received'];  ?></td>
                <td><?php echo $financehistory['FinanceHistoryActual']['unbilled_outgoing_traffic'];  ?></td>
                <td><?php echo $financehistory['FinanceHistoryActual']['actual_egress_balance'];  ?></td>
            </tr>
            <?php endforeach; ?>
    </table>