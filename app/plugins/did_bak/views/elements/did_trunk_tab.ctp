<ul class="tabs">
    <li <?php if($active == 'ingress') echo 'class="active"'; ?>>
        <a href="<?php echo $this->webroot ?>did/orders/ingress_trunk">
            Orig. Service
        </a>
    </li>
    <li <?php if($active == 'egress') echo 'class="active"'; ?>>
        <a href="<?php echo $this->webroot ?>did/orders/egress_trunk">
            Term. Service
        </a>
    </li>
</ul>