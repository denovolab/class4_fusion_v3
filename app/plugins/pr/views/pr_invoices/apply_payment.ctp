<div class="dialog_form">
    <form id="payment_form" action="<?php echo $this->webroot ?>pr/pr_invoices/apply_payment/<?php echo $invoice_id ?>/<?php echo $create_type ?>" method="post">
        <table class="list">
            <thead>
                <tr>
                    <th>Payment ID</th>
                    <th>Received Time</th>
                    <th>Amount</th>
                    <th>Remain Amount</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    foreach ($payments as $payment): 
                        $remain_amount = $payment[0]['amount'] - $payment[0]['used_amount'];
                        if ($remain_amount > 0):
                 ?>
                <tr>
                    <td><?php echo $payment[0]['client_payment_id'] ?></td>
                    <td><?php echo $payment[0]['receiving_time'] ?></td>
                    <td><?php echo number_format($payment[0]['amount'], 5); ?></td>
                    <td><?php echo $remain_amount ?></td>
                    <td><input type="checkbox" name="payment_ids[]" value="<?php echo $payment[0]['client_payment_id']; ?>" /></td>
                </tr>
                <?php
                        endif;
                    endforeach; 
                 ?>
            </tbody>
        </table>
    </form>
</div>