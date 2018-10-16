<script src="<?php echo $this->webroot?>js/ajaxTable.js" type="text/javascript"></script>
<div id="title">
    <h1><?php echo __('Log'); ?> &gt;&gt; <?php echo __('FTP Log'); ?></h1>
</div>

<div id="container">
    <!--
    <ul class="tabs">
        <li>
            <a href="<?php echo $this->webroot ?>systemparams/ftp_conf">
                <img width="16" height="16" src="<?php echo $this->webroot ?>/images/config.png">
                FTP Config
            </a>
        </li>
        <li>
            <a href="<?php echo $this->webroot ?>systemparams/ftp_trigger">
                <img width="16" height="16" src="<?php echo $this->webroot; ?>images/execute.png">
                Trigger
            </a>
        </li>
        <li class="active">
            <a href="<?php echo $this->webroot ?>systemparams/ftp_log">
                <img width="16" height="16" src="<?php echo $this->webroot; ?>images/log.png">
                Log
            </a>
        </li>
        <li>
            <a href="<?php echo $this->webroot ?>systemparams/ftp_server_log">
                <img width="16" height="16" src="<?php echo $this->webroot; ?>images/ftp.gif">
                Ftp Server Log
            </a>
        </li>
    </ul>
    -->
    <?php
        if(empty($this->data)): 
    ?>
    <div class="msg"><?php echo __('no_data_found',true);?></div>
    <?php else: ?>
    <?php echo $this->element("xpage")?>
    <table class="list">
        <thead>
            <tr>
               <th>Task Name</th>
                <th colspan="2">FTP Time</th>
                <th colspan="2">Contains Data</th>
                <th>Status</th>
                <th>Action</th> 
            </tr>
            <tr>
                <th></th>
                <th>Start</th>
                <th>End</th>
                <th>Start</th>
                <th>End</th> 
                <th></th>
                <th></th>
            </tr>
        </thead>
            <?php
                $count = count($this->data);
                for($i = 0; $i < $count; $i++):  
            ?>
            <tbody id="resInfo<?php echo $i?>">
            <tr class="row-<?php echo $i%2 +1;?>">
                <td><?php echo $this->data[$i]['FtpCdrLog']['alias']; ?></td>
                <td><?php echo $this->data[$i]['FtpCdrLog']['ftp_start_time']; ?></td>
                <td><?php echo $this->data[$i]['FtpCdrLog']['ftp_end_time']; ?></td>
                <td><?php echo $this->data[$i]['FtpCdrLog']['cdr_start_time']; ?></td>
                <td><?php echo $this->data[$i]['FtpCdrLog']['cdr_end_time']; ?></td>
                <td><?php echo $status[$this->data[$i]['FtpCdrLog']['status']]; ?></td>
                <td>
                    <img id="image<?php echo $i; ?>"  onclick="pull('<?php echo $this->webroot?>',this,<?php echo $i;?>)"  class="jsp_resourceNew_style_1"  src="<?php echo $this->webroot?>images/+.gif" title="<?php  __('View All')?>"/>
                </td>
            </tr>
            <tr style="height:auto">
                <td colspan="7">
                    <div id="ipInfo<?php echo $i?>" class=" jsp_resourceNew_style_2" style="padding:5px"> 
                        <table class="list">
                            <tr>
                                <td></td>
                                <td>Created On</td>
                                <td>File Name</td>
                                <td>FTP IP</td>
                                <td>FTP Directory</td>
                                <td>Action</td>
                            </tr>
                            <?php
                                $j = 1;
                                foreach($this->data[$i]['details'] as $item): ?>
                            <tr>
                                <td>#<?php echo $j ?></td>
                                <td><?php echo $item['FtpCdrLogDetail']['create_time']; ?></td>
                                <td><a taget="_blank" href="<?php echo $this->webroot; ?>systemparams/ftp_download/<?php  echo $item['FtpCdrLogDetail']['id']; ?>"><?php echo $item['FtpCdrLogDetail']['file_name']; ?></a></td>
                                <td><?php echo $item['FtpCdrLogDetail']['ftp_ip']; ?></td>
                                <td><?php echo $item['FtpCdrLogDetail']['ftp_dir']; ?></td>
                                <td>
                                    <a title="FTP Transfer Detail" control="<?php echo $item['FtpCdrLogDetail']['id']; ?>" href="###" class="transfer_detail">
                                        <img src="<?php echo $this->webroot ?>images/detail.png">
                                    </a>
                                    <a title="Delete" onclick="return confirm('Are you sure?')" href="<?php echo $this->webroot?>systemparams/ftp_log_delete/<?php echo $item['FtpCdrLogDetail']['id']; ?>">
                                        <img src="<?php echo $this->webroot ?>images/delete.png">
                                    </a>
                                </td>
                            </tr>
                            <?php
                            $j++;
                            endforeach;
                            ?>
                        </table>
                    </div>
                </td>
            </tr>
            </tbody>
            <?php endfor; ?>
    </table>
    <?php echo $this->element("xpage")?>
    <?php endif; ?>
</div>
<div id="dd"> </div> 
<link rel="stylesheet" type="text/css" href="<?php echo $this->webroot?>easyui/themes/default/easyui.css">
<link rel="stylesheet" type="text/css" href="<?php echo $this->webroot?>easyui/themes/icon.css">
<script type="text/javascript" src="<?php echo $this->webroot?>easyui/jquery.easyui.min.js"></script>

<script>
    var $dd = $('#dd');
    var $transfer_detail = $(".transfer_detail");
    
    $transfer_detail.click(function() {
        var $this = $(this);
        var control = $this.attr('control');

        $dd.dialog({  
            title: 'FTP Transfer Detail',  
            width: 500,  
            height: 350,  
            closed: false,  
            cache: false,  
            resizable: true,
            href: '<?php echo $this->webroot?>systemparams/ftp_log_detail_message/' + control,  
            modal: true,                
            buttons:[{
                    text:'Close',
                    handler:function(){
                        $dd.dialog('close');
                    }
            }]
        });

        $dd.dialog('refresh', '<?php echo $this->webroot?>systemparams/ftp_log_detail_message/' + control);  
    });
</script>