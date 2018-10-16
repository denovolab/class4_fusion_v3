<ul class="tabs">
    <li <?php if($active == 'orders') echo 'class="active"'; ?>>
        <a href="<?php echo $this->webroot ?>did/orders">
            DID Search			
        </a>
    </li>
    <li <?php if($active == 'listing') echo 'class="active"'; ?>>
        <a href="<?php echo $this->webroot ?>did/did_assign/listing">
            DID Listing			
        </a>
    </li>
    <li <?php if($active == 'trunk') echo 'class="active"'; ?>>
        <a href="<?php echo $this->webroot ?>did/orders/trunk">
            DID Trunk			
        </a>
    </li>
    <li <?php if($active == 'report') echo 'class="active"'; ?>>
        <a href="<?php echo $this->webroot ?>did/orders/report">
            DID Report			
        </a>
    </li>
</ul>