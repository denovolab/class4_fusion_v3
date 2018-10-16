<div id="title">
    <h1><?php echo __('Trouble Tickets',true);?>&gt;&gt;<?php echo __('Mail Templates',true);?></h1>
    <ul id="title-menu">
        <li>
            <a class="link_back_new" href="<?php echo $this->webroot; ?>alerts/trouble_tickets_template"> 
                <img height="16" width="16" alt="Back" src="<?php echo $this->webroot; ?>images/icon_back_white.png">&nbsp;Back 
            </a>
        </li>
    </ul>
</div>

<div id="container">
    <ul class="tabs">
        <li>
            <a href="<?php echo $this->webroot; ?>alerts/rule">
                <img width="16" height="16" src="<?php echo $this->webroot; ?>images/ruler.png">Rule			
            </a>
        </li>
        <li>
            <a href="<?php echo $this->webroot; ?>alerts/action">
                <img width="16" height="16" src="<?php echo $this->webroot; ?>images/action.png">Action			
            </a>
        </li>
        <li>
            <a href="<?php echo $this->webroot; ?>alerts/condition">
                <img width="16" height="16" src="<?php echo $this->webroot; ?>images/condition.png">Condition			
            </a>
        </li>
        <li>
            <a href="<?php echo $this->webroot; ?>alerts/block_ani">
                <img width="16" height="16" src="<?php echo $this->webroot; ?>images/fail.png">Block			
            </a>
        </li>
        <li>
            <a href="<?php echo $this->webroot; ?>alerts/trouble_tickets">
                <img width="16" height="16" src="<?php echo $this->webroot; ?>images/trouble_tickets.png">Trouble Tickets			
            </a>
        </li>
        <li class="active">
            <a href="<?php echo $this->webroot; ?>alerts/trouble_tickets_template">
                <img width="16" height="16" src="<?php echo $this->webroot; ?>images/email.gif">Trouble Tickets Mail Template			
            </a>
        </li>
    </ul> 
    
    <?php echo $this->element("alerts/_trouble_tickets_template_form")?>
</div>
