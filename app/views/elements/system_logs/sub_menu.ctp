<ul class="tabs">
    <li <?php if($active == 'switch_update_log') echo 'class="active"'; ?>>
        <a href="<?php echo $this->webroot ?>system_logs/index/1">
            Switch Update Log
        </a>
    </li>
    <li <?php if($active == 'switch_error_log') echo 'class="active"'; ?>>
        <a href="<?php echo $this->webroot ?>system_logs/index/2">
            Switch Error Log
        </a>
    </li>
    <li <?php if($active == 'billing_error_log') echo 'class="active"'; ?>>
        <a href="<?php echo $this->webroot ?>system_logs/index/3">
            Billing Error Log
        </a>
    </li>
    <li <?php if($active == 'script_log') echo 'class="active"'; ?>>
        <a href="<?php echo $this->webroot ?>system_logs/index/4">
            Script Log
        </a>
    </li>
    <li <?php if($active == 'web_log') echo 'class="active"'; ?>>
        <a href="<?php echo $this->webroot ?>system_logs/index/5">
            Web Log
        </a>
    </li>
</ul>