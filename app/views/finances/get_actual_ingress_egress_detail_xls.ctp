<table class="list">
    <thead>
        <tr>
            <td colspan="6">Beginning Balance on <?php echo $start_time; ?> 00:00:00 is <?php echo $begin_balance; ?></td>
            <td colspan="7">Ending Balance on <?php echo $end_time; ?> 23:59:59 is <?php echo $end_balance; ?></td>
        </tr>
        <tr>
            <td></td>
            <td colspan="5">Ingress</td>
            <td colspan="4">Egress</td>
            <td></td>
        </tr>
        <tr>
            <td>Date</td>
            <td>Payment Received</td>
            <td>Credit Note Sent</td>
            <td>Debit Note Sent</td>
            <td>Unbilled Incoming Traffic</td>
            <td>Short Charges</td>
            <td>Reset</td>
            <td>Payment Sent</td>
            <td>Credit Note Received</td>
            <td>Debit Note Received</td>
            <td>Unbilled Outgoing Traffic</td>
            <td>Reset</td>
            <td>Balance</td>
        </tr>
    </thead>    

       <tbody>
        <?php foreach($financehistories as $financehistory): ?>
            <tr>
                <td><?php echo $financehistory['FinanceHistoryActual']['date'];  ?></td>
                <td><?php echo $financehistory['FinanceHistoryActual']['payment_received'];  ?></td>
                <td><?php echo $financehistory['FinanceHistoryActual']['credit_note_sent'];  ?></td>
                <td><?php echo $financehistory['FinanceHistoryActual']['debit_note_sent'];  ?></td>
                <td><?php echo $financehistory['FinanceHistoryActual']['unbilled_incoming_traffic'];  ?></td>
                <td><?php echo $financehistory['FinanceHistoryActual']['short_charges'];  ?></td>
                <td><?php echo $financehistory['FinanceHistoryActual']['payment_sent'];  ?></td>
                <td><?php echo $financehistory['FinanceHistoryActual']['credit_note_received'];  ?></td>
                <td><?php echo $financehistory['FinanceHistoryActual']['debit_note_received'];  ?></td>
                <td><?php echo $financehistory['FinanceHistoryActual']['unbilled_outgoing_traffic'];  ?></td>
                <td><?php echo $financehistory['FinanceHistoryActual']['actual_balance'];  ?></td>
            </tr>
            <?php endforeach; ?>
    </tbody>
</table>