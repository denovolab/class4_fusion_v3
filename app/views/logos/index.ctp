<div id="title">
    <h1><?php echo __('Configuration',true);?>&gt;&gt;
   <?php echo __('Logo',true);?> </h1>
      <ul id="title-menu">
					<li>
						<a class="link_back" href="javascript:void(0)" onclick="history.go(-1)">
							<img width="10" height="5" alt="" src="<?php echo $this->webroot?>images/icon_back_white.png"><?php echo __('goback',true);?>
						</a>
					</li>                            
				</ul>
</div>

<div class="container">
<form enctype="multipart/form-data" name="ilogo_form" id="ilogo_form" method="post" action="<?php echo $this->webroot?>logos/index">
<table>
<tr>
    <td class="label label2"><?php echo __('Current Logo',true);?>:<img width="120px" height="45px" src="<?php echo $this->webroot;?>logos/ilogo"></td>
    <td class="value value2">
        <input type="file" id="ilogo" name="ilogo" class="input in-file">   
    </td>
    <td>
    			<input type="hidden" name="upload" value="upload"/>
    		<?php  if ($_SESSION['role_menu']['Configuration']['logos']['model_x']) {?>
            <input type="button" value="<?php echo __('Save',true);?>" onclick="file_upload();"/>
    		<?php }?>
    </td>
    
</tr>
</table>
</form>
</div>

<script language="Javascript">
function file_upload()
{
	if (confirm("Are You Sure?"))
	{
		document.ilogo_form.submit();
	}
}
</script>