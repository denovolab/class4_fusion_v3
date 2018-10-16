<style type="text/css">
    DIV.ProgressBar { width: 100px; padding: 0; border: 1px solid black; margin-right: 1em; height:.75em; margin-left:1em; display:-moz-inline-stack; display:inline-block; zoom:1; *display:inline; }
    DIV.ProgressBar DIV { background-color: Green; font-size: 1pt; height:100%; float:left; }
    SPAN.asyncUploader OBJECT { position: relative; top: 5px; left: 10px; }
    SPAN.asyncUploader {display:block;margin-bottom:10px;}
    #analysis {padding-left:20px; text-decoration:underline;color:red;cursor:pointer;}
</style>



<form action="<?php echo $this->webroot?>uploads/egress" method="POST" enctype="multipart/form-data">
	<div id="static_div" style="text-align: left; width: 530px;">
		<table class="cols" style="width: 252px; margin: 0px auto;"></table>
	</div>
	<table class="cols" style="width:700px;margin:0px auto;">
		<tbody>
			<tr>
				<td style="text-align:right;padding-right:4px;" class="first"><?php echo __('Import File',true);?>:</td>
				<td style="text-align:left;" class="last">
                                    <input id="myfile" type="file" name="file">
                                    <span id="analysis" style="display:block;">
                                    </span>
                                </td>
			</tr>
			<tr>
				<td style="text-align:right;padding-right:4px;" class="first"><?php echo __('Duplicate',true);?>:</td>
				<td style="text-align:left;" class="last">
					<input type="radio" name="duplicate_type" value="ignore" id="duplicate_type_ignore" class="">
					<label for="duplicate_type_ignore"><?php echo __('Ignore',true);?></label>			  
<!--					<input type="radio" name="duplicate_type" value="overwrite" id="duplicate_type_overwrite">
					<label for="duplicate_type_overwrite"><?php echo __('Overwrite',true);?></label>			  -->
					<input type="radio" name="duplicate_type" value="delete" id="duplicate_type_delete" checked="checked">
					<label for="duplicate_type_delete"><?php echo __('delete',true);?></label>
				</td>
			</tr>
                        <tr><td align="right">Example:</td><td align="left"><a href="<?php echo $this->webroot?>example/egress.csv" target="_blank" title="Show example file">show</a></td></tr>
			<tr>
		  		<td style="text-align:right;padding-right:4px;" class="first last"></td>
		  </tr>
			<tr>
		  		<td colspan="2" class="first last"><div class="submit"><input type="submit" value="<?php echo __('upload',true);?>" class="input in-submit"></div></td>
			</tr>	
		</tbody>
	</table>
</form>
<script src="<?php echo $this->webroot; ?>ajaxupload/swfupload.js"></script>
<script src="<?php echo $this->webroot; ?>ajaxupload/jquery-asyncUpload-0.1.js"></script>
<script type="text/javascript">
    $(function() {
        
         $("#myfile").makeAsyncUploader({
            upload_url: '<?php echo $this->webroot ?>uploads/async_upload', 
            flash_url: '<?php echo $this->webroot; ?>ajaxupload/swfupload.swf',
            button_image_url: '<?php echo $this->webroot; ?>ajaxupload/blankButton.png',
            post_params: {"PHPSESSID": "<?php echo session_id(); ?>"},
            file_size_limit: '1024 MB',
            upload_success_handler: function(file, response) {
                    $("#analysis").empty();
                    $("input[name$=_filename]", container).val(file.name);
                    //$("input[name$=_guid]", container).val(response).after('<span id="analysis"><a target="_blank" href="<?php echo $this->webroot; ?>uploads/analysis_file/<?php echo $type; ?>/' + response +'">After the analysis of the results</a></span>');
                    $("input[name$=_guid]", container).val(response);
                    $("#analysis").html('<a target="_blank" href="<?php echo $this->webroot; ?>uploads/analysis_file/<?php echo $type; ?>/' + response +'">Show and modify</a>');
                    $("span[id$=_completedMessage]", container).html("Uploaded <b>{0}</b> ({1} KB)"
                                .replace("{0}", file.name)
                                .replace("{1}", Math.round(file.size / 1024))
                            );
                }
        });
    });
</script>