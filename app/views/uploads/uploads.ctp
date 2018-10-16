<style type="text/css">
    DIV.ProgressBar { width: 100px; padding: 0; border: 1px solid black; margin-right: 1em; height:.75em; margin-left:1em; display:-moz-inline-stack; display:inline-block; zoom:1; *display:inline; }
    DIV.ProgressBar DIV { background-color: Green; font-size: 1pt; height:100%; float:left; }
    SPAN.asyncUploader OBJECT { position: relative; top: 5px; left: 10px; }
    SPAN.asyncUploader {display:block;margin-bottom:10px;}
    #analysis {padding-left:20px; text-decoration:underline;color:red;cursor:pointer;}
</style>

<div id="title">
    <h1><?php echo 
        isset($module) ? $module : 
        Inflector::humanize($appUploads->show_upload_title($this->params['action']))

        ?>&gt;&gt;
        Edit <?php echo isset($action) ? $action : __($this->params['action'], true);?>

        <font class="editname"><?php echo @empty($name[0][0]['name'])||$name[0][0]['name']==''?'':"[".$name[0][0]['name']."]" ?></font>
        &gt;&gt; Import
    </h1>

    <form method="GET" action="">
        <ul id="title-menu">
            <?php if(isset($back_url) && !empty($back_url)):?>
            <li><a  class="link_back" href="<?php echo $back_url?>">		
                    <img heigh="16" width="16" src ="<?php echo $this->webroot?>images/icon_back_white.png" ?></img>
                    &nbsp; <?php echo __('goback',true);?></a></li>
            <?php endif;?>	
        </ul>
        <ul id="title-search">		
        </ul>
    </form>
</div>
<div id="container">
    <?php echo $this->element('uploads/'.$this->params['action'].'_tabs')?>
    <?php if (isset ( $exception_msg ) && $exception_msg) :	?>
    <?php	echo $this->element ( 'common/exception_msg' );?>		
    <?php endif;?>
    <form action="" method="POST" enctype="multipart/form-data">
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
            <tr>
                <td style="text-align:right;padding-right:4px;"><?php echo __('Import File',true);?>:</td>
                <td style="text-align:left;">
                    <input type="file" id="myfile" name="file" />
                    <span id="analysis" style="display:block;">
                        
                    </span>
                </td>
            </tr>
            <tr>
                <td style="text-align:right;padding-right:4px;"><?php echo __('Method',true);?>:</td>
                <td style="text-align:left;">
                    <input type="radio" name="duplicate_type" value="ignore" id="duplicate_type_ignore" />
                    <label for="duplicate_type_ignore"><?php echo __('Ignore',true);?></label><!--			  
                    <input type="radio" name="duplicate_type" value="overwrite" id="duplicate_type_overwrite"/>
                    <label for="duplicate_type_overwrite">Overwrite</label>			  
                    --><input type="radio" name="duplicate_type" value="delete" id="duplicate_type_delete"     checked="checked"/>
                    <label for="duplicate_type_delete"><?php echo __('delete',true);?></label>
                </td>
            </tr>
            <tr><td align="right">Example:</td><td align="left"><a href="<?php echo $this->webroot?>example/<?php echo $example_file ?>.csv" target="_blank" title="Show example file">show</a></td></tr>
            <tr>
                <td style="text-align:right;padding-right:4px;"></td><!--
                <td style="text-align:left;">
                        <input type="checkbox" name="with_headers" checked="checked"/>
                        <span>With headers row</span>
        </td>
                --></tr><!--
                <tr>
                        <td style="text-align:right;padding-right:4px;"></td>
                        <td style="text-align:left;">
                                <input type="checkbox" name="rollback_on_error"/>
                                <span>Rollback on error</span>
                </td>
                </tr>
            -->
            <?php  if ($_SESSION['role_menu']['Switch']['jurisdictionprefixs']['model_x']||$_SESSION['role_menu']['Switch']['rates']['model_w']||$_SESSION['role_menu']['Switch']['codedecks']['model_w']) {?>
            <tr>
                <td colspan="2"><?php echo $form->submit('upload')?></td>
            </tr>	
            <?php }?>
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


<script type="text/javascript">
    $(function() {
        $('#custom_date').hide();
        $('#is_custom_enddate').click(function() {
            if($(this).attr('checked')) {
                $('#custom_date').show();
            } else {
                $('#custom_date').hide();
            }
        });
        
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