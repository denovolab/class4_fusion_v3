<style>
    td[align=left] {
        padding-left: 10px;
    }
</style>

<div id="title">
    <h1>
        Monitoring&gt;&gt;'Block Media IP'&gt;&gt;Upload
    </h1>
</div>

<div id="container">
    <form id="myform" method="post"  enctype="multipart/form-data">
        <table>
            <tbody>

            <tr>
                <td align="right"><?php __('Duplicate Handling'); ?></td>
                <td align="left">
                    <select name="duplicate_type">
                        <option value="delete"><?php __('Overwrite')?></option>
                        <option value="ignore"><?php __('Ignore')?></option>
                    </select>
                </td>
            </tr>
            <tr>
                <td align="right"><?php __('Upload File'); ?></td>
                <td align="left">
                    <input type="file" name="file" id="myfile" />
                    <span id="analysis" style="display:block;">

                                    </span>
                </td>
            </tr>
            <tr>
                <td align="right"><?php __('Example'); ?></td>
                <td align="left">
                    <a target="_blank" href="<?php echo $this->webroot; ?>example/black_hole.csv"><?php __('show')?></a>
                </td>
            </tr>
            <tr>
                <td colspan="2" class="button-groups center">
                    <input type="submit" id="subbtn" class="input in-submit hover" value="<?php __('Submit')?>">
                </td>
            </tr>
            </tbody>
        </table>
    </form>
</div>

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
                $("input[name$=_guid]", container).val(response);
//                $("#analysis").html('<a target="_blank" href="<?php //echo $this->webroot; ?>//uploads/analysis_file/0/' + response + '">Show and modify</a>');
                $("span[id$=_completedMessage]", container).html("Uploaded <b>{0}</b> ({1} KB)"
                    .replace("{0}", file.name)
                    .replace("{1}", (file.size / 1024).toFixed(3))
                );
            }
        });
    });
</script>
