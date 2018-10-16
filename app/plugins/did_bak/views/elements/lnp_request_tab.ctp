<ul class="tabs">
    <li <?php if($active == 'log') echo 'class="active"'; ?>>
        <a href="<?php echo $this->webroot ?>did/lnp_request/index">
            Log			
        </a>
    </li>
    <li <?php if($active == 'submit') echo 'class="active"'; ?>>
        <a href="<?php echo $this->webroot ?>did/lnp_request/push">
            Submit	
        </a>
    </li>
</ul>