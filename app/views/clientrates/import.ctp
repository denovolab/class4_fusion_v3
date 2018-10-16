
<script src="<?php echo $this->webroot; ?>ajaxupload/swfupload.js"></script>
<script src="<?php echo $this->webroot; ?>ajaxupload/jquery-asyncUpload-0.1.js"></script>
<style type="text/css">
DIV.ProgressBar { width: 100px; padding: 0; border: 1px solid black; margin-right: 1em; height:.75em; margin-left:1em; display:-moz-inline-stack; display:inline-block; zoom:1; *display:inline; }
DIV.ProgressBar DIV { background-color: Green; font-size: 1pt; height:100%; float:left; }
SPAN.asyncUploader OBJECT { position: relative; top: 5px; left: 10px; }
 #info {
    margin:10px;
    width: 200px;
    padding:10px;
    margin:0 auto;
    display: none;
}
#info span,#info ul li a {
    color:red;
}
#analysis {padding-left:20px; text-decoration:underline;color:red;cursor:pointer;}
#import_info span {color:red;padding-right:10px;}
#end_date_time_exists, #end_date_time_all {display:none;}
</style>

<div id="title">
    <h1> <?php __('Switch') ?>&gt;&gt;<?php echo __('Editing rates',true);?>
       
       	<font class="editname">
    						<?php echo empty($name[0][0]['name'])||$name[0][0]['name']==''?'':'['.$name[0][0]['name'].']' ?>
    				</font>&gt;&gt<?php __('Simulate') ?>
       </h1> 
</div>


<div id="container">
    <ul class="tabs">
    <li><a href="<?php echo $this->webroot ?>clientrates/view/<?php echo $ratetable_id?>"><img width="16" height="16" src="<?php echo $this->webroot ?>images/menuIcon.gif"> Rates</a></li>
    <?php if ($jur_type == 3 || $jur_type == 4): ?>
    <li><a href="<?php echo $this->webroot?>clientrates/view/<?php echo $table_id?>/<?php echo $currency?>/npan"><img width="16" height="16" src="<?php echo $this->webroot?>images/menuIcon.gif"><?php echo __('NPANXX Rate',true);?> </a></li>
    <?php endif; ?>
    <li><a href="<?php echo $this->webroot ?>clientrates/simulate/<?php echo $ratetable_id?>"><img width="16" height="16" src="<?php echo $this->webroot ?>images/simulate.gif"> Simulate</a></li>   
    <li class="active"><a href="<?php echo $this->webroot ?>clientrates/import/<?php echo $ratetable_id?>"><img width="16" height="16" src="<?php echo $this->webroot ?>images/import.png"> <?php echo __('import',true);?></a></li> 
    <li><a href="<?php echo $this->webroot ?>downloads/rate/<?php echo $ratetable_id?>"><img width="16" height="16" src="<?php echo $this->webroot ?>images/export.png"> <?php echo __('export',true);?></a></li>   
</ul>
    <div id="import_panel" style="text-align:center;line-height: 30px; width: 1000px; margin: 0 auto;">
        <form method="post" id="myform" action="<?php echo $this->webroot ?>clientrates/change_header/<?php echo$ratetable_id;  ?>">
        <table>
            <tr>
                <td align="right">
                    <label>Import File:</label></td>
                <td align="left" style="padding-left:10px;">
                    <input type="file" id="myfile" name="myfile" />
                    <span id="analysis" style="display:block;">
                        
                    </span>
                </td>
            </tr>
            <tr>
                <td align="right">
                    <label>For rate record with the same code and effective date is found:</label>
                </td>
                <td align="left">
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <!--<input type="radio" name="method" value="0" checked="checked" /> Ignore-->
                    <input type="radio" name="method" value="1" checked="checked" /> Delete Existing Records
                    <input type="radio" name="method" value="2" /> End-Date Existing Records
                    <input type="radio" name="method" value="0" /> End-Date All Records
                </td>
            </tr>
            
            <tr id="end_date_time_exists">
                <td align="right">
            		<label>End Date Time:</label>
            	</td>
                <td align="left" style="padding-left:10px;">
                    <input class="in-text" type="text" id="end_date" name="end_date1" value="" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'});" /> 
                    <select name="end_date_tz1" class="input in-select">
                        <option value="-1200">GMT -12:00</option>
                        <option value="-1100">GMT -11:00</option>
                        <option value="-1000">GMT -10:00</option>
                        <option value="-0900">GMT -09:00</option>
                        <option value="-0800">GMT -08:00</option>
                        <option value="-0700">GMT -07:00</option>
                        <option value="-0600">GMT -06:00</option>
                        <option value="-0500">GMT -05:00</option>
                        <option value="-0400">GMT -04:00</option>
                        <option value="-0300">GMT -03:00</option>
                        <option value="-0200">GMT -02:00</option>
                        <option value="-0100">GMT -01:00</option>
                        <option selected="selected" value="+0000">GMT +00:00</option>
                        <option value="+0100">GMT +01:00</option>
                        <option value="+0200">GMT +02:00</option>
                        <option value="+0300">GMT +03:00</option>
                        <option value="+0330">GMT +03:30</option>
                        <option value="+0400">GMT +04:00</option>
                        <option value="+0500">GMT +05:00</option>
                        <option value="+0600">GMT +06:00</option>
                        <option value="+0700">GMT +07:00</option>
                        <option value="+0800">GMT +08:00</option>
                        <option value="+0900">GMT +09:00</option>
                        <option value="+1000">GMT +10:00</option>
                        <option value="+1100">GMT +11:00</option>
                        <option value="+1200">GMT +12:00</option>
                    </select>
                </td>
            </tr>
            
            <tr id="end_date_time_all">
                <td align="right">
            		<label>End Date Time:</label>
            	</td>
                <td align="left" style="padding-left:10px;">
                    <input class="in-text" type="text" id="end_date_all" name="end_date" value="<?php echo date("Y-m-d 23:59:59"); ?>" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'});" /> 
                    <select name="end_date_tz" class="input in-select">
                        <option value="-1200">GMT -12:00</option>
                        <option value="-1100">GMT -11:00</option>
                        <option value="-1000">GMT -10:00</option>
                        <option value="-0900">GMT -09:00</option>
                        <option value="-0800">GMT -08:00</option>
                        <option value="-0700">GMT -07:00</option>
                        <option value="-0600">GMT -06:00</option>
                        <option value="-0500">GMT -05:00</option>
                        <option value="-0400">GMT -04:00</option>
                        <option value="-0300">GMT -03:00</option>
                        <option value="-0200">GMT -02:00</option>
                        <option value="-0100">GMT -01:00</option>
                        <option selected="selected" value="+0000">GMT +00:00</option>
                        <option value="+0100">GMT +01:00</option>
                        <option value="+0200">GMT +02:00</option>
                        <option value="+0300">GMT +03:00</option>
                        <option value="+0330">GMT +03:30</option>
                        <option value="+0400">GMT +04:00</option>
                        <option value="+0500">GMT +05:00</option>
                        <option value="+0600">GMT +06:00</option>
                        <option value="+0700">GMT +07:00</option>
                        <option value="+0800">GMT +08:00</option>
                        <option value="+0900">GMT +09:00</option>
                        <option value="+1000">GMT +10:00</option>
                        <option value="+1100">GMT +11:00</option>
                        <option value="+1200">GMT +12:00</option>
                    </select>
                </td>
            </tr>
            
            
            <tr>
            	<td align="right">
            		<label>Effective Date Format:</label>
            	</td>
            	<td align="left" style="padding-left:10px;">
            		<select name="effective_date_format">
                        <option value="mm/dd/yyyy">mm/dd/yyyy</option>
                        <option value="yyyy-mm-dd">yyyy-mm-dd</option>
                        <option value="dd-mm-yyyy">dd-mm-yyyy</option>
                        <option value="mm/dd/yyyy ">mm/dd/yyyy</option>
                        <option value="dd/mm/yyyy ">dd/mm/yyyy</option>
                        <option value="yyyy/mm/dd">yyyy/mm/dd</option>
                    </select>
            	</td>
            </tr>
            <tr>
                <td align="right">
            		<label>File With Header:</label>
            	</td>
                <td align="left" style="padding-left:10px;">
                    <input type="checkbox" name="with_header" checked="checked" /><br />
                    
                </td>
            </tr>
            
            <tr>
                <td align="right">
            		<label>Set Default Value:</label>
            	</td>
                <td align="left" style="padding-left:10px;">
                    <input type="checkbox" name="default_value" id="default_value" /><br />
                    
                </td>
            </tr>
            <tr id="default_value_panel">
                <td colspan="2">
                    <input type="checkbox" checked="checked"  name="is_effective_date" />Effective Date:<input type="text" class="in-text" name="effetive_date" value="<?php echo date("Y-m-d 00:00:00"); ?>" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'});" />
                    <select style="width:100px;" name="effetive_date_timezone" class="input in-select select">
                            <option value="-12">GMT -12:00</option>
                            <option value="-11">GMT -11:00</option>
                            <option value="-10">GMT -10:00</option>
                            <option value="-09">GMT -09:00</option>
                            <option value="-08">GMT -08:00</option>
                            <option value="-07">GMT -07:00</option>
                            <option value="-06">GMT -06:00</option>
                            <option value="-05">GMT -05:00</option>
                            <option value="-04">GMT -04:00</option>
                            <option value="-03">GMT -03:00</option>
                            <option value="-02">GMT -02:00</option>
                            <option value="-01">GMT -01:00</option>
                            <option selected="selected" value="+00">GMT +00:00</option>
                            <option value="+01">GMT +01:00</option>
                            <option value="+02">GMT +02:00</option>
                            <option value="+03">GMT +03:00</option>
                            <option value="+03">GMT +03:30</option>
                            <option value="+04">GMT +04:00</option>
                            <option value="+05">GMT +05:00</option>
                            <option value="+06">GMT +06:00</option>
                            <option value="+07">GMT +07:00</option>
                            <option value="+08">GMT +08:00</option>
                            <option value="+09">GMT +09:00</option>
                            <option value="+10">GMT +10:00</option>
                            <option value="+11">GMT +11:00</option>
                            <option value="+12">GMT +12:00</option>
                            <option value=""></option>
                        </select>
                    &nbsp;
                    <input type="checkbox" name="is_min_time" checked="checked" />Min Time:<input type="text" class="in-text" name="min_time" value="1" />
                    &nbsp;
                    <input type="checkbox" name="is_interval" checked="checked"  />Interval:<input type="text" class="in-text" name="interval" value="1" />
                </td>
            </tr>
      
            <tr>
                <td align="right">
                    Example File Format Available<a href="<?php echo $example ?>" target="_blank" title="click to download">here</a>&nbsp;&nbsp;&nbsp;
                </td>
                <td align="left">
                    <input type="submit" id="import_btn" value="Upload" class="input in-submit" />
                </td>
            </tr>
            </form>
        </table>
        <div id="import_info">
            
        </div>
    </div>
    <br />
    <!--
    <div id="dlg">
    <ul id="info">
        <li>Status:<span id="status"></span>&nbsp;<span id="img_status"></span></li>
        <li>Delete Queue:<span id="delete_queue"></span></li>
        <li>Update Queue:<span id="update_queue"></span></li>
        <li>Insert Queue:<span id="insert_queue"></span></li>
        <li>Error Counter:<span id="error_counter"></span></li>
        <li>Reimport Counter:<span id="reimport_counter"></span></li>
        <li>Error Log File:<a href="###" id="error_log_file">Download</a></li>
        <li>Reimport Log File:<a href="###" id="reimport_log_file">Download</a></li>
        <li>Import time:<span id="import_time"></span></li>
    </ul>
    </div>
    -->
</div>

<link rel="stylesheet" type="text/css" href="<?php echo $this->webroot?>easyui/themes/default/easyui.css">
<link rel="stylesheet" type="text/css" href="<?php echo $this->webroot?>easyui/themes/icon.css">
<script type="text/javascript" src="<?php echo $this->webroot?>easyui/jquery.easyui.min.js"></script>
<script src="<?php echo $this->webroot  ?>js/jquery.jgrowl.js" type="text/javascript"></script>
<script type="text/javascript">
            
$(function() {
    var $import_info = $('#import_info');
    var $showdlg = $('#showdlg');
    var status_err = ["Error!", "Running...", "Insert...", "Update...", "Delete...", "Done!"];
    status_err['-1'] = "Waiting...";
    status_err['-2'] = "End Date..."
    var downlog_baseurl = "<?php echo $this->webroot  ?>clientrates/down_import_log/";
    
    function checkStatus() {
        $.ajax({
            type : 'POST',
            dataType : 'json',
            url:"<?php echo $this->webroot  ?>clientrates/checkstatus",
            data: {ratetable_id : "<?php echo $ratetable_id; ?>"},
            success : function(data, textStatus) {
                if(data != '') {
                    //$('#import_panel').hide();
                    $('#info').show();
                    $('#status').text(status_err[data['status']]);
                    
                    if(data['status'] == 0) {
                        $('#img_status').html('<img src="<?php echo $this->webroot ?>images/rate-error.png" title="Failure" />');
                    } else if (data['status'] == 5) {
                        $('#img_status').html('<img src="<?php echo $this->webroot ?>images/rate-success.png" title="Success" />');
                    } else {
                        $('#img_status').html('<img src="<?php echo $this->webroot ?>images/rate-progress.gif" title="In Progress" />');
                    }
                    
                    $('#delete_queue').text(data['delete_queue']);
                    $('#update_queue').text(data['update_queue']);
                    $('#insert_queue').text(data['insert_queue']);
                    $('#error_counter').text(data['error_counter']);
                    $('#reimport_counter').text(data['reimport_counter']);
                    $('#error_log_file').attr('href', downlog_baseurl + data['id'] + '/' + 'error_log_file');
                    $('#reimport_log_file').attr('href',downlog_baseurl + data['id'] + '/' + 'reimport_log_file');
                    $('#import_time').text(data['time']);
                } 
            }
        });
    }
    
    function checkUploading()
    {
        $.ajax({
            type : 'POST',
            dataType : 'json',
            url: '<?php echo $this->webroot  ?>clientrates/checkuploading',
            data: {ratetable_id : "<?php echo $ratetable_id; ?>"},
            success : function(data) {
                $import_info.html('Waiting:<span>' + data.waiting + '</span>End Date:<span>' + data.ending_date + '</span>In Progress:<span><a href="###" id="showdlg">' + data.progressing +'</a></span>');
            }
        });
    }
    /*
    window.setInterval(checkStatus, 3000);
    window.setInterval(checkUploading, 3000);
    checkUploading();
    
    $('#dlg').window({  
        width:300,  
        height:300,  
        modal:false,
        title: 'In Progress',
        closed: true
    });  
    
    $showdlg.live('click', function() {
        $('#dlg').window('open');
    });
    */
    
    String.prototype.endsWith = function (s) {
    return this.length >= s.length && this.substr(this.length - s.length) == s;
  }
    
    $("#myfile").makeAsyncUploader({
        upload_url: '<?php echo $this->webroot ?>clientrates/upload', 
        flash_url: '<?php echo $this->webroot; ?>ajaxupload/swfupload.swf',
        button_image_url: '<?php echo $this->webroot; ?>ajaxupload/blankButton.png',
        file_size_limit: '1024 MB',
        post_params: {"PHPSESSID": "<?php echo session_id(); ?>"},
        upload_success_handler: function(file, response) {
                    $("#analysis").empty();
                    $("input[name$=_filename]", container).val(file.name);
                    
                    if (file.name.endsWith('xlsx')) {
                        $('select[name=effective_date_format] option[value=yyyy-mm-dd]').attr('selected', true);
                    }
                    
                    $("input[name$=_guid]", container).val(response);
                    //$("#analysis").html('<a target="_blank" href="<?php echo $this->webroot; ?>uploads/analysis_file/12/' + response +'">Show and modify</a>');
                    $("span[id$=_completedMessage]", container).html("Uploaded <b>{0}</b> ({1} KB)"
                                .replace("{0}", file.name)
                                .replace("{1}", Math.round(file.size / 1024))
                            );
                                
                },
        upload_error_handler: function(file, code, msg) { $.jGrowl("Sorry! We are unable to recognize your file　format.",{theme:'jmsg-error'}); }
    });
    
    var $end_date_time_exists = $('#end_date_time_exists');
    var $end_date_time_all = $('#end_date_time_all');
    var $default_value = $('#default_value');
    var $default_value_panel = $('#default_value_panel');
    
    $default_value.change(function() {
        if ($(this).attr('checked')) {
            $default_value_panel.show();
        } else {
            $default_value_panel.hide();
        }
    }).trigger('change');
  
    $('input[name=method]').change(function() {
        var method = $(this).val();
        if (method == '1') {
            $end_date_time_exists.hide();
            $end_date_time_all.hide();
        } else if (method == '2') {
            $end_date_time_exists.show();
            $end_date_time_all.hide();
        } else {
            $end_date_time_exists.hide();
            $end_date_time_all.show();
        }
    });
    
    $('#myform').submit(function() {
        
        var time = $('#end_date_all').val();
        
        var method = $('input[name=method]:checked').val();
        
        if (method == '0' && time == '') 
        {
            $.jGrowl("End Date can not be empty!",{theme:'jmsg-error'});
            return false;
        }
        
        if($('input[name=myfile_guid]').val() == '')
        {
            $.jGrowl("You must upload file first!",{theme:'jmsg-error'});
            return false;
        }
        
        
    });
});    
</script>