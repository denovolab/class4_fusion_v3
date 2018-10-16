<style type="text/css">
    DIV.ProgressBar { width: 100px; padding: 0; border: 1px solid black; margin-right: 1em; height:.75em; margin-left:1em; display:-moz-inline-stack; display:inline-block; zoom:1; *display:inline; }
    DIV.ProgressBar DIV { background-color: Green; font-size: 1pt; height:100%; float:left; }
    SPAN.asyncUploader OBJECT { position: relative; top: -5px; left: 10px; }
  
</style>

<div id="title">
    <h1><?php __('Management') ?> &gt;&gt;<?php echo __('Upload Reset Balance') ?></h1>
</div>

<div id="container">
    <form method="post" id="myform" action="<?php echo $this->webroot ?>clients/upload_reset_balance_change_header">
     <div  id="static_div"   style="text-align: left; width: 530px;">
            <table class="cols" style="width: 252px; margin: 0px auto;"  >
                <?php if (isset ( $statistics ) && $statistics) :	?>
                <caption><?php echo __('Upload Statistics',true);?>    

                    <span style="color: red;;font-size:11px;"> </span>
                </caption>
                <?php foreach(array('success','failure','duplicate') as $col):?>
                <?php if(isset($statistics[$col])):?>
                <tr>
                    <td style="text-align:right;padding-right:4px;"><?php echo Inflector::humanize($col)?>:</td>
                    <td style="text-align:left;color:red;"><?php echo $statistics[$col]?></td>
                </tr>
                <?php endif;?>
                <?php endforeach;?>
                <?php if(	isset($statistics['failure']) && $statistics['failure'] > 0 && 
                isset($statistics['error_file']) && !empty($statistics['error_file']) &&
                isset($statistics['log_id']) && $statistics['log_id'] > 0
                ):?>
                <tr>
                    <td style="text-align:right;padding-right:4px;"><?php echo Inflector::humanize("error_file")?>:</td>
                    <td style="text-align:left;"><a href="<?php echo $this->webroot?>uploads/download_error_file/<?php echo $statistics['log_id']?>"><?php echo __('download',true);?></a></td>
                </tr>
                <?php endif;?>
                <tr><td>&nbsp;</td><td></td></tr>
                <tr><td>&nbsp;</td><td></td></tr>
                <?php endif;?>

            </table>
        </div>   
    <table class="cols" style="width:700px;margin:0px auto;">
        <tbody>
            <tr>
                <td style="text-align:right;padding-right:4px;"><?php echo __('Import File', true); ?>:</td>
                <td style="text-align:left;padding:5px 0;">
                    <input type="file" id="myfile" name="file" />
                    <span id="analysis" style="display:block;">

                    </span>
                </td>
            </tr>
            <tr>
                <td align="right">
                    <label>Type:</label>
                </td>
                <td align="left" style="padding-left:10px;">
                    <select name="balance_type">
                        <option value="0">Actual</option>
                        <option value="1">Mutual</option>
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
                    Example File Format Available<a href="<?php echo $example ?>" target="_blank" title="click to download">here</a>&nbsp;&nbsp;&nbsp;
                </td>
                <td align="left">
                    <input type="submit" id="import_btn" value="Upload" class="input in-submit" />
                </td>
            </tr>
        </tbody>
    </table>
    </form>
</div>

<?php

if(!empty($statistics['log_id'])){?>
<script  type="text/javascript">
    (function(div_id,status){
        var _div_id=$(div_id);
        var _status=0;
        var _timeoutHander = null;

 
        var  test=function (){
            _timeoutHander = setTimeout(doStartCap,2000);
        } 
        var doStartCap = function (){

            $.post('<?php echo $this->webroot?>uploads/get_upload_log?id=<?php echo $statistics['log_id'];?>',{},
            function(data){
                var s=data.substring(0,2);
                //if(/\d/.test(s)){
                _div_id.html(data.substring(2));
                if(s==6){
                    clearTimeout(_timeoutHander);

                }
                //}
                _timeoutHander = setTimeout(doStartCap,2000);
            }
        );
		
        }


        jQuery(document).ready(doStartCap);     

	
    })('#static_div','#upload_status');
</script>

<?php }?>

<script src="<?php echo $this->webroot; ?>ajaxupload/swfupload.js"></script>
<script src="<?php echo $this->webroot; ?>ajaxupload/jquery-asyncUpload-0.1.js"></script>

<script>
    $(function() {
        $("#myfile").makeAsyncUploader({
            upload_url: '<?php echo $this->webroot ?>uploads/async_upload', 
            flash_url: '<?php echo $this->webroot; ?>ajaxupload/swfupload.swf',
            button_image_url: '<?php echo $this->webroot; ?>ajaxupload/blankButton.png',
            file_size_limit: '1024 MB',
            post_params: {"PHPSESSID": "<?php echo session_id(); ?>"},
            upload_success_handler: function(file, response) {
                $("#analysis").empty();
                $("input[name$=_filename]", container).val(file.name);
                $("input[name$=_guid]", container).val(response);
                //$("#analysis").html('<a target="_blank" href="<?php echo $this->webroot; ?>uploads/analysis_file/12/' + response +'">Show and modify</a>');
                $("span[id$=_completedMessage]", container).html("Uploaded <b>{0}</b> ({1} KB)"
                    .replace("{0}", file.name)
                    .replace("{1}", Math.round(file.size / 1024))
                );
            }
        });
    
         $('#myform').submit(function() {


            if($('input[name=myfile_guid]').val() == '')
            {
                $.jGrowl("You must upload file first!",{theme:'jmsg-error'});
                return false;
            }

        });
    });
</script>