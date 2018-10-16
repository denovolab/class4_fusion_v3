<div style="padding:10px;">
    <table class="list">
        <thead>
            <tr>
                <th>Payment ID</th>
                <th>Payment Amount</th>
                <th>Paid Amount</th>
                <th>Unpaid Amount</th>
                <th>Payment On</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($payments as $payment): ?>
            <tr>
                <td>#<?php echo $payment[0]['client_payment_id'] ?></td>
                <td><?php echo $payment[0]['amount'] ?></td>
                <td><?php echo $payment[0]['paid_amount'] ?></td>
                <td><?php echo $total_amount -= $payment[0]['paid_amount']; ?></td>
                <td><?php echo $payment[0]['payment_time'] ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>