<ul class="sub_panel">
    <li <?php if($active == 'browse') echo 'class="active"'; ?>>
        <a href="<?php echo $this->webroot ?>did/orders/browse">
            Browse
        </a>
    </li>
    <li <?php if($active == 'trunk') echo 'class="active"'; ?>>
        <a href="<?php echo $this->webroot ?>did/orders/trunk">
            Trunk
        </a>
    </li>
    <li <?php if($active == 'report') echo 'class="active"'; ?>>
        <a href="<?php echo $this->webroot ?>did/orders/report">
            Report
        </a>
    </li>
</ul>