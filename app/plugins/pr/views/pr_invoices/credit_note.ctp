<style>
#myform {
    display:none;
}
</style>

<div id="title">
  <h1>
    <?php  __('Finance')?>
    &gt;&gt;
    <?php __('Invoices')?>
  </h1>
<ul id="title-menu">
		<li>

          <a href="javascript:history.go(-1)" class="link_back">
            <img width="16" height="16" src="<?php echo $this->webroot; ?>/images/icon_back_white.png" alt="<?php echo __('goback',true);?>">
            &nbsp;<?php echo __('goback',true);?>
          </a>
		</li>
	</ul>
</div>

<div id="container">
    <input type="button" value="Add" id="addbtn" />
    <br /><br />
    <form name="myform" method="post" id="myform">
        <?php echo __('Note',true);?>:
        <input type="text" name="note" />
        &nbsp;&nbsp;&nbsp;&nbsp;
        <?php echo __('Amount',true);?>:
        <input type="text" name="amount" />
        <input type="submit" value="<?php echo __('submit',true);?>" />
    </form>
    <table class="list">
        <thead>
            <tr>
                <td><?php echo __('Description',true);?></td>
                <td><?php echo __('Amount',true);?></td>
                <?php if($_SESSION['role_menu']['Payment_Invoice']['delete_credit_note'] == 1): ?>
                <td><?php __('Action'); ?></td>
                <?php endif; ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach($data as $item): ?>
            <tr>
                <td><?php echo $item[0]['description']; ?></td>
                <td><?php echo number_format($item[0]['amount'], 2); ?></td>
                <?php if($_SESSION['role_menu']['Payment_Invoice']['delete_credit_note'] == 1): ?>
                <td>
                    <a title="Delete" href="<?php echo $this->webroot ?>pr/pr_invoices/delete_credit_note/<?php echo $item[0]['client_payment_id'] ?>/<?php echo $invoice_no ?>">
                        <img src="<?php echo $this->webroot ?>images/delete.png">
                    </a>
                </td>
                <?php endif; ?>
            </tr>
            <?php endforeach; ?>
            <tr>
                <td><b><?php echo __('Total',true);?></b></td>
                <td><?php echo number_format($total, 2); ?></td>
                <?php if($_SESSION['role_menu']['Payment_Invoice']['delete_credit_note'] == 1): ?>
                <td>
                    
                </td>
                <?php endif; ?>
            </tr>
        </tbody>
    </table>
</div>

<script type="text/javascript">
$(function() {
    $('#addbtn').click(function() {
        $('#myform').toggle();
    });
});
</script>