<style type="text/css">
    DIV.ProgressBar { width: 100px; padding: 0; border: 1px solid black; margin-right: 1em; height:.75em; margin-left:1em; display:-moz-inline-stack; display:inline-block; zoom:1; *display:inline; }
    DIV.ProgressBar DIV { background-color: Green; font-size: 1pt; height:100%; float:left; }
    SPAN.asyncUploader OBJECT { position: relative; top: 5px; left: 10px; }
    SPAN.asyncUploader {display:block;margin-bottom:10px;}
    #analysis {padding-left:20px; text-decoration:underline;color:red;cursor:pointer;}
</style>

<div id="title">
    <h1><?php echo __('Origination',true);?>&gt;&gt;<?php echo __('Vendor DID Repository',true);?>&gt;&gt;<?php __('Upload'); ?></h1>
    <ul id="title-menu">
        <li>
            <a href="<?php echo $this->webroot ?>did/did_reposs" class="link_back_new"> 
                <img width="16" height="16" src="<?php echo $this->webroot ?>images/icon_back_white.png" alt="Back">&nbsp;Back 
            </a>
        </li>
    </ul>
</div>

<div id="container">
    <form method="post" enctype="multipart/form-data">
    <table class="list list-form">
        <tbody>
            <tr>
                <th><?php __('Ingress Trunk'); ?></th>
                <td>
                    <select name="ingress_id">
                        <?php foreach($ingresses as $key => $ingress): ?>
                        <option value="<?php echo $key; ?>"><?php echo $ingress; ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>
            <tr>
                <th><?php __('Upload File'); ?></th>
                <td>
                    <input type="file" name="file" id="myfile" />
                    <span id="analysis" style="display:block;">
                        
                    </span>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <input type="submit" value="Submit" />
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
                    //$("input[name$=_guid]", container).val(response).after('<span id="analysis"><a target="_blank" href="<?php echo $this->webroot; ?>uploads/analysis_file/<?php echo $type; ?>/' + response +'">After the analysis of the results</a></span>');
                    $("input[name$=_guid]", container).val(response);
                    $("#analysis").html('<a target="_blank" href="<?php echo $this->webroot; ?>uploads/analysis_file/<?php echo $type; ?>/' + response +'">Show and modify</a>');
                    $("span[id$=_completedMessage]", container).html("Uploaded <b>{0}</b> ({1} KB)"
                                .replace("{0}", file.name)
                                .replace("{1}", Math.round(file.size / 1024))
                            );
                }
        });
        
        <?php if (isset($upload_id)): ?>
        
  
        
        window.setInterval(function() {
            $.ajax({
                'url' : '<?php echo $this->webroot ?>/uploads/get_upload_log?id=<?php echo $upload_id; ?>',
                'type' : 'POST',
                'dataType' : 'text',
                'success' : function(data)
                {
                    $('#container').html(data.substr(2));
                }
            });
        } , 2000);
        
        <?php endif; ?>
    });
</script>