<div id="title">
    <h1>
        <?php if ($login_type == 3): ?>
        <?php echo __('Billing',true);?>
        <?php else: ?>
        <?php echo __('Finance',true);?>
        <?php endif; ?>
        &gt;&gt;<?php echo __('Auto Payment Log',true);?></h1>
</div>

<div id="container">
    <?php echo $this->element("xpage")?>
    <table class="list">
        <thead>
            <tr>
                <td>Client</td>
                <td>Created Time</td>
                <td>Modified Time</td>
                <?php if ($credit_card): ?>
                <td>Method</td>
                <?php endif; ?>
                <td>Charge Total</td>
                <td>Fee</td>
                <?php if ($credit_card): ?>
                <td>Card Number</td>
                <td>Card Expire Month</td>
                <td>Card Expire Year</td>
                <td>Error</td>
                <?php endif; ?>
                <td>Status</td>
            </tr>
        </thead>
        
        <tbody>
            <?php foreach($this->data as $item): ?>
            <tr>
                <td><?php echo $item['client']['name']; ?></td>
          		<td><?php echo $item['PaymentHistory']['created_time']; ?></td>
          		<td><?php echo $item['PaymentHistory']['modified_time']; ?></td>
                         <?php if ($credit_card): ?>
          		<td><?php echo $method[$item['PaymentHistory']['method']]; ?></td>
                        <?php endif; ?>
          		<td><?php echo $item['PaymentHistory']['chargetotal']; ?></td>
          		<td><?php echo $item['PaymentHistory']['fee']; ?></td>
                        <?php if ($credit_card): ?>
          		<td><?php echo $item['PaymentHistory']['cardnumber']; ?></td>
          		<td><?php echo $item['PaymentHistory']['cardexpmonth']; ?></td>
          		<td><?php echo $item['PaymentHistory']['cardexpyear']; ?></td>
                        <td><?php echo $item['PaymentHistory']['error']; ?></td>
                        <?php endif; ?>
          		<td><?php echo $status[$item['PaymentHistory']['status']]; ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php echo $this->element("xpage")?>
</div>

