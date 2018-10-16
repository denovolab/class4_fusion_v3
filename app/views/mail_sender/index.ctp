<div id="title">
    <h1><?php echo __('Configuration',true);?>&gt;&gt;<?php echo __('Email Sender',true);?></h1>
    <ul id="title-menu">
        <li>
            <a class="link_btn" id="add" title="<?php echo __('creataction')?>"  href="###">
                <img width="16" height="16" alt="" src="<?php echo $this->webroot?>images/add.png"><?php echo __('createnew')?>
            </a>
        </li>
    </ul>
</div>

<div id="container">
    <?php if (!count($this->data)): ?>
    <table class="list" style="display:none;">
        <thead>
            <tr>
                <th>Mail Server</th>
                <th>Mail Port</th>
                <th>Username</th>
                <th>Password</th>
                <th>Authentication</th>
                <th>Secure</th>
                <th>Email Address</th>
                <th>Name</th>
                <th>Action</th>
            </tr>
        </thead>
        
        <tbody>
            
        </tbody>
    </table>
    
    <?php echo $this->element('listEmpty')?>
    <?php else: ?>
    <?php echo $this->element("xpage")?>
    <table class="list">
        <thead>
            <tr>
                <th>Mail Server</th>
                <th>Mail Port</th>
                <th>Username</th>
                <th>Password</th>
                <th>Authentication</th>
                <th>Secure</th>
                <th>Email Address</th>
                <th>Name</th>
                <th>Action</th>
            </tr>
        </thead>
        
        <tbody>
            <?php foreach($this->data as $item): ?>
            <tr>
                <td><?php echo $item['MailSender']['smtp_host']; ?></td>
                <td><?php echo $item['MailSender']['smtp_port']; ?></td>
                <td><?php echo $item['MailSender']['username']; ?></td>
                <td>*</td>
                <td><?php echo $item['MailSender']['loginemail']; ?></td>
                <td><?php echo $secures[$item['MailSender']['secure']]; ?></td>
                <td><?php echo $item['MailSender']['email']; ?></td>
                <td><?php echo $item['MailSender']['name']; ?></td>
                <td>
                    <a title="Edit" class="edit_item" href="###" control="<?php echo $item['MailSender']['id']?>" >
                        <img src="<?php echo $this->webroot?>images/editicon.gif"/>
                    </a>

                    <a title="Delete" onclick="return confirm('Are you sure do this?')" class="delete" href='<?php echo $this->webroot ;?>mail_sender/delete/<?php echo $item['MailSender']['id']?>'>
                        <img src="<?php echo $this->webroot?>images/delete.png "/>
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
    jQuery(function() {
        jQuery('#add').click(function(){
            $('.msg').hide();
            $('table.list').show();
            jQuery('table.list tbody').trAdd({
                ajax:"<?php echo $this->webroot ?>mail_sender/modify_panel",
                action:"<?php echo $this->webroot ?>mail_sender/modify_panel",
                removeCallback:function(){
                    if(jQuery('table.list tr').size()==1){
                        jQuery('table.list').hide();
                    }
                }
            });
            jQuery(this).parent().parent().show();
        });
        
        jQuery('a.edit_item').click(function(){
            jQuery(this).parent().parent().trAdd({
                action:'<?php echo $this->webroot ?>mail_sender/modify_panel/'+jQuery(this).attr('control'),
                ajax:'<?php echo $this->webroot ?>mail_sender/modify_panel/'+jQuery(this).attr('control'),
                saveType:'edit'
            });
        });
    });
</script>
    