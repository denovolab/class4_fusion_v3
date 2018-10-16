<div id="title">
    <h1>
        <?php  __('Finance')?>
        &gt;&gt;
        <?php __('Invoices')?>
    </h1>
    <ul id="title-menu">
        <li>
            <a class="link_back" href="javascript:history.go(-1)">
                <img width="16" height="16" alt="Back" src="<?php echo $this->webroot?>images/icon_back_white.png">
                &nbsp;<?php echo __('goback',true);?>
            </a>
        </li>
    </ul>
</div>
<div id="container">
    <form action="<?php echo $this->webroot; ?>pr/pr_invoices/recon/<?php echo $this->params['pass'][0] ?>" method="post" enctype="multipart/form-data" name="myform">
        <div style="text-align:center;">
        <span>Upload file to compare:</span>
        <input type="file" name="upfile" />
        <input type="submit" value="Submit" style="height:25px;" />
        <a  class="input in-button" href="<?php echo $this->webroot ?>pr/pr_invoices/start_reconcile/<?php echo $invoice_id ?>">Start Compare</a>
        <span>Current Status:<?php echo $status ?></span>
        <span><a href="<?php echo $this->webroot ?>pr/pr_invoices/get_recom_example_file">Down Example file</a></span>
        </div>
    </form>
    <?php
        $data = $p->getDataArray();
    ?>
    <?php
        if(empty($data)):
    ?>
    <div id="list" class="second_tab" style="">
        <div class="msg">No data found</div>
    </div>
    <?php else: ?>
    <h1 style="margin:10px;">
        <a class="input in-button" href="<?php echo $this->webroot ?>pr/pr_invoices/down_reconcile_list/<?php echo $invoice_id; ?>">Export</a>
    </h1>
    <br />
    <div id="toppage"></div>
    <table id="list" class="list">
        <thead>
            <tr>
                <td colspan="3"><?php __('Partner'); ?></td>
                <td colspan="2"><?php __('System'); ?></td>
                <td colspan="2"><?php __('Minute Diff'); ?></td>
                <td colspan="2"><?php __('Cost Diff'); ?></td>
            </tr>
            <tr>
                <td><?php __('Code'); ?></td>
                <td><?php __('Minute'); ?></td>
                <td><?php __('Cost'); ?></td>
                <td><?php __('Minute'); ?></td>
                <td><?php __('Cost'); ?></td>
                <td><?php __('Amt'); ?></td>
                <td><?php __('%'); ?></td>
                <td><?php __('Amt'); ?></td>
                <td><?php __('%'); ?></td>
            </tr>
        </thead>
        <tbody>
            <?php foreach($data as $item): ?>
            <tr>
                <td><?php echo $item[0]['code']; ?></td>
                <td><?php echo round($item[0]['minute'], 2); ?></td>
                <td><?php echo round($item[0]['cost'], 2); ?></td>
                <td><?php echo round($item[0]['sys_minute'], 2); ?></td>
                <td><?php echo round($item[0]['sys_cost'], 2); ?></td>
                <td><?php echo round($item[0]['minute_diff_amt'], 2); ?></td>
                <td><?php echo round($item[0]['minute_diff_per'], 2); ?></td>
                <td><?php echo round($item[0]['cost_diff_amt'], 2); ?></td>
                <td><?php echo round($item[0]['cost_diff_per'], 2); ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div id="tmppage"><?php echo $this->element('page');?></div>
    <?php endif; ?>
</div>