<div id="title">
    <h1><?php  __('Finance')?> &gt;&gt;<?php echo __('Carrier Invoice History')?></h1>
</div>

<div id="container">
    <?php if (empty($this->data)): ?>
    <div class="msg">No data found</div>
    <?php else: ?>
    <?php echo $this->element("xpage")?>
    <table class="list">
        <thead>
            <tr>
                <td>Invoice Date</td>
            </tr>
        </thead>
        
        <tbody>
            <?php foreach($this->data as $item): ?>
            <tr>
                <td><?php echo $item['InvoiceHistory']['last_invoice_for']; ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php echo $this->element("xpage")?>
    <?php endif; ?>
</div>

