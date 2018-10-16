<ul class="tabs">
    <li <?php if ($current_page == 0) echo ' class="active"';?>>
        <a href="<?php echo $this->webroot ?>loop_detection">
            <img src="<?php echo $this->webroot ?>images/config.png" />
            Criteria</a>
    </li>
    <li <?php if ($current_page == 1) echo ' class="active"';?>>
        <a href="<?php echo $this->webroot ?>loop_detection/logging">
            <img src="<?php echo $this->webroot ?>images/log.png" />
            Log</a>
    </li>
</ul>