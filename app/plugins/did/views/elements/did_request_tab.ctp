<ul class="tabs">
    <li <?php if($active == 'active') echo 'class="active"'; ?>>
        <a href="<?php echo $this->webroot ?>did/did_request/index/active">
            Active Requests			
        </a>
    </li>
    <li <?php if($active == 'complete') echo 'class="active"'; ?>>
        <a href="<?php echo $this->webroot ?>did/did_request/index/complete">
            Complete Requests		
        </a>
    </li>
</ul>