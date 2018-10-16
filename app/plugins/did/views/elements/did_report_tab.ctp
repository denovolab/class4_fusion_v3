<ul class="tabs">
    <li <?php if($active == 'term_service_buy') echo 'class="active"'; ?>>
        <a href="<?php echo $this->webroot ?>cdrreports/summary_reports/buy">
            Term. Service (Buy)
        </a>
    </li>
<?php if (Configure::read('did.enable')): ?>
    <li <?php if($active == 'orig_service_buy') echo 'class="active"'; ?>>
        <a href="<?php echo $this->webroot ?>did/orders/report/buy">
            Orig. Service (Buy)
        </a>
    </li>
<?php endif; ?>
    <li <?php if($active == 'term_service_sell') echo 'class="active"'; ?>>
        <a href="<?php echo $this->webroot ?>cdrreports/summary_reports/sell">
            Term. Service (Sell)
        </a>
    </li>
<?php if (Configure::read('did.enable')): ?>
    <li <?php if($active == 'orig_service_sell') echo 'class="active"'; ?>>
        <a href="<?php echo $this->webroot ?>did/orders/report/sell">
            Orig. Service (Sell)
        </a>
    </li>
<?php endif; ?>
    <li <?php if($active == 'export_log') echo 'class="active"'; ?>>
            <a href="<?php echo $this->webroot; ?>cdrreports/mail_send_log">
                <img width="16" height="16" src="<?php echo $this->webroot; ?>images/menuIcon.gif">
                Export Log
            </a>
        </li>
</ul>