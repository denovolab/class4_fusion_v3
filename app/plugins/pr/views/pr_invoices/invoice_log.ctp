<script src="<?php echo $this->webroot?>js/ajaxTable.js" type="text/javascript"></script>
<div id="title">
    <h1><?php __('Log')?> &gt;&gt; <?php __('Invoice Log')?></h1>
    <ul id="title-menu">
        <li>
            <a href="###" id="refresh_btn" class="link_btn">
                <img width="10" height="5"  src="<?php echo $this->webroot ?>images/refresh.png"><?php echo __('Refresh',true);?>
            </a>
        </li>
        <li>
            <a href="<?php echo $this->webroot ?>pr/pr_invoices/view/1" class="link_back_new">
                <img width="10" height="5"  src="<?php echo $this->webroot ?>images/icon_back_white.png"><?php echo __('goback',true);?>
            </a>
        </li>
    </ul>
</div>

<div id="container">
    <?php
    if(empty($this->data)): 
    ?>
    <div class="msg"><?php echo __('no_data_found',true);?></div>
    <?php else: ?>
    
    <?php echo $this->element("xpage")?>
    <table class="list">
        <thead>
            <tr>
                <th></th>
                <th>Invoice Request ID</th>
                <th>Start Time</th>
                <th>End Time</th>
                <th>Status</th>
                <th>Progress</th>
                <th>Action</th>
            </tr>
        </thead>
            <?php 
            $count = count($this->data);   
            for($i = 0; $i < $count; $i++): 
                $all_total = 0;
                foreach($this->data[$i]['InvoiceLog']['invoices'] as $item) {
                    $all_total += $item[0]['total_amount'];
                }
                if ($all_total > 0):
            ?>
            <tbody id="resInfo<?php echo $i?>">
            <tr class="row-<?php echo $i%2 +1;?>">
                <td>
                    <img id="image<?php echo $i; ?>"  onclick="pull('<?php echo $this->webroot?>',this,<?php echo $i;?>)"  class="jsp_resourceNew_style_1"  src="<?php echo $this->webroot?>images/+.gif" title="<?php  __('View All')?>"/>
                </td>
                <td>#<?php echo $this->data[$i]['InvoiceLog']['id'] ?></td>
                <td><?php echo $this->data[$i]['InvoiceLog']['start_time'] ?></td>
                <td><?php echo $this->data[$i]['InvoiceLog']['end_time'] ?></td>
                <td><?php echo $status[$this->data[$i]['InvoiceLog']['status']]; ?></td>
                <td><?php echo count($this->data[$i]['InvoiceLog']['invoices']) ?>/<?php echo $this->data[$i]['InvoiceLog']['cnt'] ?></td>
                <td>
                    <a class="send_invoices" title="Sent" href="###">
                        <img src="<?php echo $this->webroot ?>images/send.png">
                    </a>
                </td>
            </tr>
            <tr style="height:auto">
                <td colspan="7">
                    <div id="ipInfo<?php echo $i?>" class=" jsp_resourceNew_style_2" style="padding:5px"> 
                        <table>
                            <tr>
                                <td>Carrier</td>
                                <td>Amount</td>
                                <td>Invoice Period</td>
                                <td>Invoice Due Date</td>
                                <td>Status</td>
                                <td>Action</td>
                            </tr>
                            <?php foreach($this->data[$i]['InvoiceLog']['invoices'] as $invoice): ?>
                            <tr>
                                <td><?php echo $invoice[0]['name']; ?></td>
                                <td><?php echo $invoice[0]['total_amount']; ?></td>
                                <td><?php echo $invoice[0]['invoice_start']; ?> ~ <?php echo $invoice[0]['invoice_end']; ?></td>
                                <td><?php echo $invoice[0]['due_date']; ?></td>
                                <td><?php echo $sub_status[$invoice[0]['status']]; ?></td>
                                <td>
                                    <a class="send_invoice" invoice_id="<?php echo $invoice[0]['invoice_id']; ?>" title="Sent" href="###">
                                    <img src="<?php echo $this->webroot ?>images/send.png">
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </table>
                    </div>
                </td>
            </tr>
            </tbody>
            <?php 
            endif;
            endfor; 
            ?>
    </table>
    <?php echo $this->element("xpage")?>
    <?php endif; ?>
</div>

<script>
    $(function() {
        var $send_invoice = $('.send_invoice');
        var $send_invoices = $('.send_invoices');
        $send_invoice.click(function() {
            var $this = $(this);
            var inovice_id = $this.attr('invoice_id');
            var arr = new Array();
            arr.push(inovice_id);
            $.ajax({
                'url' : '<?php echo $this->webroot ?>pr/pr_invoices/mail_invoice',
                'type' : 'POST',
                'dataType' : 'text',
                'data' : {'ids[]' : arr},
                'success' : function(data) {
                    window.location.reload();
                }
            });
        });
        
        $send_invoices.click(function() {
            var arr = new Array();
            var $this = $(this);
            var $invoices = $this.parents('tr').next().find('a.send_invoice');
            $invoices.each(function() {
               arr.push($(this).attr('invoice_id'));
            });
            if (arr.length) {
                $.ajax({
                    'url' : '<?php echo $this->webroot ?>pr/pr_invoices/mail_invoice',
                    'type' : 'POST',
                    'dataType' : 'text',
                    'data' : {'ids[]' : arr},
                    'success' : function(data) {
                        window.location.reload();
                    }
                });
            }
        });
        
        $('#refresh_btn').click(function() {
            window.location.reload();
        });
    });
</script>