<ul class="tabs">
    <li <?php if ($active == 'single') echo 'class="active"' ?>>
        <a href="<?php echo $this->webroot; ?>did/orders/shopping_cart">
            Single
        </a>
    </li>
    <li <?php if ($active == 'multiples') echo 'class="active"' ?>>
        <a href="<?php echo $this->webroot; ?>did/orders/shopping_cart_mutiples">
            Multiples
        </a>
    </li>
</ul>