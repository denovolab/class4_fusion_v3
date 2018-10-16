<div id="title">
    <h1>Back-Up and Data Cleansing</h1>
</div>

<div id="container">
    
    <table class="list">
        <thead>
            <tr>
                <th>Data Type</th>
                <th>Backup Frequency</th>
                <th>Data Size(in days)</th>
                <th>Data Cleansing Frequency</th>
                <th>Data Removal(in days)</th>
                <th>FTP Server</th>
                <th>User</th>
                <th>Password</th>
                <th>Active</th>
                <th>Action</th>
            </tr>
        </thead>
        
        <tbody>
           <?php foreach($cleanups as $cleanup): ?>
            <tr>
                <td><?php echo $cleanup['Cleanup']['name']; ?></td>
                <td><?php echo $cleanup['Cleanup']['backup_frequency']; ?></td>
                <td><?php echo $cleanup['Cleanup']['data_size']; ?></td>
                <td><?php echo $cleanup['Cleanup']['data_cleansing_frequency']; ?></td>
                <td><?php echo $cleanup['Cleanup']['data_removal']; ?></td>
                <td><?php echo $cleanup['Cleanup']['ftp_server']; ?></td>
                <td><?php echo $cleanup['Cleanup']['ftp_user']; ?></td>
                <td><?php echo $cleanup['Cleanup']['ftp_password']; ?></td>
                <td>
                    <a href="<?php echo $this->webroot ?>cleanup/change_status/<?php echo $cleanup['Cleanup']['id'] ?>">
                        <?php if ($cleanup['Cleanup']['actived']): ?>
                        <img src="<?php echo $this->webroot ?>images/flag-1.png" />
                        <?php else: ?>
                        <img src="<?php echo $this->webroot ?>images/flag-0.png" />
                        <?php endif; ?>
                    </a>
                </td>
                <td>
                    <a href="###" control="<?php echo $cleanup['Cleanup']['id'] ?>" class="edited_item">
                        <img src="<?php echo $this->webroot ?>images/editicon.gif">
                    </a>
                </td>
            </tr>            
            <?php endforeach; ?>
        </tbody>
    </table>
    
</div>

<script>
    jQuery(function() {
        
        jQuery('a.edited_item').click(function(){
            jQuery(this).parent().parent().trAdd({
                action:'<?php echo $this->webroot ?>cleanup/edit_panel/'+jQuery(this).attr('control'),
                ajax:'<?php echo $this->webroot ?>cleanup/edit_panel/'+jQuery(this).attr('control'),
                saveType:'edit'
            });
        });
    });
</script>