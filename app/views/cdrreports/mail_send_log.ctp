<script src="<?php echo $this->webroot?>js/ajaxTable.js" type="text/javascript"></script>
<div id="title">
    <h1>
        <?php __('Statistics')?>
        &gt;&gt;
        <?php __('CDR Export Log'); ?>
    </h1>
</div>

<div id="container">
    <ul class="tabs">
        <?php if ($session->read('login_type') == 3): ?>

        <?php echo $this->element('report/cdr_report/cdr_report_user_tab', array('active' => 'export_log'))?>
    <?php else: ?>
        <li>
            <a href="<?php echo $this->webroot; ?>cdrreports/summary_reports">
                <img width="16" height="16" src="<?php echo $this->webroot; ?>images/list.png">
                CDR Search
            </a>
        </li>
        <li>
            <a href="<?php echo $this->webroot; ?>cdrreports/export_log">
                <img width="16" height="16" src="<?php echo $this->webroot; ?>images/export.png">
                CDR Export Log
            </a>
        </li>
        <li class="active">
            <a href="<?php echo $this->webroot; ?>cdrreports/mail_send_log">
                <img width="16" height="16" src="<?php echo $this->webroot; ?>images/menuIcon.gif">
                Mail CDR Log
            </a>
        </li>
        <?php endif; ?>
    </ul>
    <?php
        $data =$p->getDataArray();
    ?>
    <div id="toppage"></div>
    <table class="list">
        <thead>
            <tr>
                <td></td>
                <td>Job ID</td>
                <td>Start Time</td>
                <td>Finish Time</td>
                <td>CDR Start Time</td>
                <td>CDR End Time</td>
                <td># of Files</td>
                <td>CDR Counts</td>
                <td>Action</td>
            </tr>
        </thead>
        <?php 
        $count = count($data);
        for($i = 0; $i < $count; $i++): 
        ?>
        <tbody id="resInfo<?php echo $i?>">
            <tr class="row-<?php echo $i%2 +1;?>">
                <td>
                    <img id="image<?php echo $i; ?>"  onclick="pull('<?php echo $this->webroot?>',this,<?php echo $i;?>)"  class="jsp_resourceNew_style_1"  src="<?php echo $this->webroot?>images/+.gif" title="<?php  __('View All')?>"/>
                </td>
                <td>#<?php echo $data[$i][0]['id'] ?></td>
                <td><?php echo $data[$i][0]['start_time'] ?></td>
                <td><?php echo $data[$i][0]['finish_time'] ?></td>
                <td><?php echo $data[$i][0]['cdr_start_time'] ?></td>
                <td><?php echo $data[$i][0]['cdr_end_time'] ?></td>
                <td><?php echo $data[$i][0]['file_counts'] ?></td>
                <td><?php echo $data[$i][0]['cdr_counts'] ?></td>
                <td>
                    <a itemvalue="<?php echo $data[$i][0]['id'] ?>" class="emailTo" href="###">
                            <img width="16" height="16" src="<?php echo $this->webroot?>images/email.gif">
                    </a>
                    <a onclick="return confirm('Are you sure?')" href="<?php echo $this->webroot ?>cdrreports/delete_email_log/<?php echo $data[$i][0]['id'] ?>">
                            <img width="16" height="16" src="<?php echo $this->webroot?>images/delete.png">
                    </a>
                </td>
            </tr>
            <tr style="height:auto">
                <td colspan="9">
                    <div id="ipInfo<?php echo $i?>" class=" jsp_resourceNew_style_2" style="padding:5px"> 
                        <table>
                            <tr>
                                <td>CDR Start Time</td>
                                <td>CDR End Time</td>
                                <td>File Name</td>
                                <td># of CDR</td>
                                <td>File Size</td>
                                <td>Action</td>
                            </tr>
                            <?php foreach($data[$i][0]['details'] as $detail): ?>
                            <tr>
                                <td><?php echo $detail[0]['cdr_start_time'] ?></td>
                                <td><?php echo $detail[0]['cdr_end_time'] ?></td>
                                <td><?php echo $detail[0]['filename'] ?></td>
                                <td><?php echo $detail[0]['order'] ?></td>
                                <td><?php echo round($detail[0]['file_size'] / 1024 , 2) ?>K</td>
                                <td>
                                    <a href="<?php echo $this->webroot ?>cdrreports/get_export_file/<?php echo $detail[0]['id'] ?>">
                                            <img width="16" height="16" src="<?php echo $this->webroot?>images/file.png">
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach;?>
                        </table>
                    </div>
                </td>
            </tr>
        </tbody>
        <?php endfor; ?>
    </table>
    <div id="tmppage"><?php echo $this->element('page');?></div>
</div>

<div id="pop-div" closed="true" class="easyui-dialog" title="CDR Email" style="width:400px;height:100px;"  
        data-options="iconCls:'icon-save',resizable:true,modal:true,cache:false">
    <div class="product_list">
        <label>Email Address:</label>
        <input type="text" class="input in-text in-input" id="send_email" />
        <input type="button" id="send_email_btn" value="Submit" />
    </div>
</div> 

<link rel="stylesheet" type="text/css" href="<?php echo $this->webroot?>easyui/themes/default/easyui.css">
<link rel="stylesheet" type="text/css" href="<?php echo $this->webroot?>easyui/themes/icon.css">
<script type="text/javascript" src="<?php echo $this->webroot?>easyui/jquery.easyui.min.js"></script>

<script>
$(function() {
    var $dialog = $('#pop-div');
    $('.emailTo').click(function() {
        var id = $(this).attr('itemvalue');
        $dialog.dialog('open');
        var $btn = $('#send_email_btn');
        $btn.unbind('click');
        $btn.click(function() {
              var email = $('#send_email').val();
              if (email != '') 
              {
                   $.ajax({
                       'url' : '<?php echo $this->webroot ?>cdrreports/re_sendmail',
                       'type' : 'POST',
                       'dataType' : 'json',
                       'data' : {'id' : id, 'email':email},
                       'success' : function(data) {
                           $dialog.dialog('close');
                           jQuery.jGrowl('The email is sent to [' + email + '] successfully!',{theme:'jmsg-success'});
                       }
                   });                   
              }
              return false;
        });
    });
});
</script>