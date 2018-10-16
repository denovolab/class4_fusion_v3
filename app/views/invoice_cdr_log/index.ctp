<div id="title">
    <h1><?php echo __('Log',true);?>&gt;&gt;<?php echo __('Invoice CDR Log',true);?></h1>
</div>

<div id="container">
    <ul class="tabs">
        <li <?php if ($type == 0) echo 'class="active"' ?>>
            <a href="<?php echo $this->webroot ?>invoice_cdr_log/index/0">
                Ingress			
            </a>
        </li>
        <li <?php if ($type == 1) echo 'class="active"' ?>>
            <a href="<?php echo $this->webroot ?>invoice_cdr_log/index/1">
                Egress			
            </a>
        </li>
    </ul>
    <?php if (empty($this->data)): ?>
    <div class="msg">No data found</div>
    <?php else: ?>
    <?php echo $this->element("xpage")?>
    <table class="list">
        <thead>
            <tr>
                <td>Start Time</td>
                <td>End Time</td>
                <td>Carrier</td>
                <td>Attachment</td>
            </tr>
        </thead>
        
        <tbody>
            <?php foreach($this->data as $item): ?>
            <tr>
                <td><?php echo $item['InvoiceCdrLog']['start_time']; ?></td>
                <td><?php echo $item['InvoiceCdrLog']['end_time']; ?></td>
                <td><?php echo $item['InvoiceCdrLog']['carrier_name']; ?></td>
                <td>
                   <a href="<?php echo $this->webroot ?>pr/pr_invoices/cdr_download/<?php echo $item['InvoiceCdrLog']['type'] == 0 ? 'ingress' : 'egress' ?>/<?php echo $item['InvoiceCdrLog']['invoice_number']; ?>">
                        Download
                   </a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php echo $this->element("xpage")?>
    <?php endif; ?>
</div>

