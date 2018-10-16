<div id="title">
	<h1>Management &gt;&gt;Online Payment</h1>
	<ul id="title-menu">
        <li>
            <a class="link_back_new" href="<?php echo $this->webroot; ?>clients/client_pay"> 
                <img width="16" height="16" alt="Back" src="<?php echo $this->webroot; ?>images/icon_back_white.png">&nbsp;Back 
            </a>
        </li>
    </ul>
</div>

<div id="container">

    <form id="myform" action="https://www.paypal.com/cgi-bin/webscr" <?php if (Configure::read('payline.is_new_window')) echo 'target="_blank"'; ?> method="post">
        <input type="hidden" name="cmd" value="_xclick" />
        <input type="hidden" name="business" value="<?php echo $business; ?>" />
        <input type="hidden" name="item_name" value="Online Payment" />
        <input type="hidden" name="invoice" value="<?php echo $invoice ?>" />
        <input type="hidden" name="amount" value="<?php echo number_format($amount, 2) ?>" />
        <input type="hidden" name="currency_code" value="USD" />
        <input type="hidden" name="charset" value="utf-8" />
        <input type="hidden" name="lc" value="US" />
        <input type="hidden" name="notify_url" value="<?php echo $domain .$this->webroot; ?>clients/notify" />
        <input type="hidden" name="return" value="<?php echo $domain. $this->webroot; ?>clients/carrier" />
        <input type="hidden" name="cancel_return" value="<?php echo $domain. $this->webroot; ?>clients/carrier" />
        <center>
            <input type="image" src="https://www.paypal.com/en_US/i/btn/btn_buynow_LG.gif" border="0" name="submit" alt="Wait a moment, please.." />
        </center>
    </form>


	<script type="text/javascript">
	$(function() {
		$('#myform').submit();
	});
	</script>

</div>