<ul class="tabs">
    <li <?php if ($active=='form') echo ' class="active"' ?>>
        <a href="<?php echo $this->webroot ?>quickcdr">
            <img src="<?php echo $this->webroot; ?>images/config.png"> Simple CDR Export
        </a>
    </li>
    <li <?php if ($active=='log') echo ' class="active"' ?>>
        <a href="<?php echo $this->webroot ?>quickcdr/logging">
            <img src="<?php echo $this->webroot; ?>images/log.png"> Log
        </a>
    </li>
</ul>