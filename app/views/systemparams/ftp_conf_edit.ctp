<div id="title">
    <h1><?php echo __('Configuration'); ?> &gt;&gt; <?php echo __('FTP Configuration'); ?></h1>
    <ul id="title-menu">
        <li>
            <a href="<?php echo $this->webroot ?>systemparams/ftp_conf" class="link_back_new"> 
                <img width="16" height="16" src="<?php echo $this->webroot ?>images/icon_back_white.png" alt="Back">&nbsp;Back 
            </a>
        </li>
    </ul>
</div>

<div id="container">
    
    <ul class="tabs">
        <li class="active">
            <a href="<?php echo $this->webroot ?>systemparams/ftp_conf">
                <img width="16" height="16" src="<?php echo $this->webroot ?>/images/config.png">
                FTP Config
            </a>
        </li>
        <li>
            <a href="<?php echo $this->webroot ?>systemparams/ftp_trigger">
                <img width="16" height="16" src="<?php echo $this->webroot ?>images/execute.png">
                Trigger
            </a>
        </li>
        <!--
        <li>
            <a href="<?php echo $this->webroot ?>systemparams/ftp_log">
                <img width="16" height="16" src="<?php echo $this->webroot ?>images/log.png">
                Log
            </a>
        </li>
        -->
    </ul>
    
    <?php echo $this->element('/systemparams/_ftp_form'); ?>
    
</div>

<script type="text/javascript" src="<?php echo $this->webroot ?>js/fields_sendrate.js"></script>
<script type="text/javascript">
    
    $(function() {
        var $frequency = $('#frequency');
        var $every_hours = $('#every_hours');
        var $execute_on_tr = $('#execute_on_tr');
        
        $frequency.change(function() {
            var val = $(this).val();
            if (val == '3') {
                $every_hours.show();
                $execute_on_tr.hide();
            } else {
                $every_hours.hide();
                $execute_on_tr.show();
            }
        }).trigger('change');
        
        $('#myform').submit(function() {
            $('#columns option').attr('selected', true);
        });
    });
    
</script>