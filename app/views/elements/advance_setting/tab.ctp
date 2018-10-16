<ul class="tabs">
    <li <?php if($active == 'web') echo 'class="active"'; ?>>
        <a href="<?php echo $this->webroot ?>advance_setting/web">
            <img src="<?php echo $this->webroot ?>images/web.png">
            Web
        </a>
    </li>
    <li <?php if($active == 'script') echo 'class="active"'; ?>>
        <a href="<?php echo $this->webroot ?>advance_setting/script">
            <img src="<?php echo $this->webroot ?>images/script.png">
            Script
        </a>
    </li>
    <li <?php if($active == 'switch') echo 'class="active"'; ?>>
        <a href="<?php echo $this->webroot ?>advance_setting/switch">
            <img src="<?php echo $this->webroot ?>images/switch.png">
            Switch
        </a>
    </li>
</ul>