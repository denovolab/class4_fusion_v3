<div id="title">
    <h1><?php echo __('Configuration'); ?> &gt;&gt; <?php echo __('FTP Configuration'); ?></h1>
    <ul id="title-menu">
        <li>
            <a href="<?php echo $this->webroot ?>systemparams/ftp_conf_create" class="link_btn">
                <img width="16" height="16" src="<?php echo $this->webroot ?>images/add.png"> 
                Create New
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
                <img width="16" height="16" src="/Class4/images/execute.png">
                Trigger
            </a>
        </li>
        <!--
        <li>
            <a href="<?php echo $this->webroot ?>systemparams/ftp_log">
                <img width="16" height="16" src="/Class4/images/log.png">
                Log
            </a>
        </li>
        <li>
            <a href="<?php echo $this->webroot ?>systemparams/ftp_server_log">
                <img width="16" height="16" src="<?php echo $this->webroot; ?>images/ftp.gif">
                Ftp Server Log
            </a>
        </li>
        -->
    </ul>
    <?php
        if(empty($this->data)): 
    ?>
    <div class="msg"><?php echo __('no_data_found',true);?></div>
    <?php else: ?>
    <?php echo $this->element("xpage")?>
    <table class="list">
        <thead>
            <th>Alias</th>
            <th>Active</th>
            <th>Action</th>
        </thead>
        <tbody>
            <?php foreach($this->data as $item): ?>
            <tr>
                <td><?php echo $item['FtpConf']['alias']; ?></td>
                <td>
                    <a href="<?php echo $this->webroot ?>systemparams/ftp_conf_change_status/<?php echo $item['FtpConf']['id'] ?>">
                        <?php if ($item['FtpConf']['active']): ?>
                        <img src="<?php echo $this->webroot ?>images/flag-1.png" />
                        <?php else: ?>
                        <img src="<?php echo $this->webroot ?>images/flag-0.png" />
                        <?php endif; ?>
                    </a>
                </td>
                <td>
                    <a href="###" class="test_ftp" title="Test FTP" control="<?php echo $item['FtpConf']['id'] ?>"> 
                        <img src="<?php echo $this->webroot ?>images/test.png"> 
                    </a>
                    <a href="<?php echo $this->webroot ?>systemparams/ftp_conf_edit/<?php echo $item['FtpConf']['id'] ?>" title="Edit"> 
                        <img src="<?php echo $this->webroot ?>images/editicon.gif"> 
                    </a>
                    <a onclick="return confirm('Are you sure?');" href="<?php echo $this->webroot ?>systemparams/ftp_conf_delete/<?php echo $item['FtpConf']['id'] ?>" title="Delete"> 
                        <img src="<?php echo $this->webroot ?>images/delete.png"> 
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php echo $this->element("xpage")?>
    <?php endif; ?>
</div>

<script>
$(function() {
    var $test_ftp = $('.test_ftp');
    
    $test_ftp.click(function() {
        var control_id = $(this).attr('control')
        
        $.ajax({
            'url' : '<?php echo $this->webroot ?>systemparams/test_ftp/' + control_id,
            'type' : 'POST',
            'dataType' : 'text',
            'success' : function(data) {
                jQuery.jGrowl(data,{theme:'jmsg-default'});
            }
        });
    });
});    
</script>