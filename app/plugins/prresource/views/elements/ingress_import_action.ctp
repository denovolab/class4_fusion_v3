<div style="height:10px"></div>
<form action="<?php echo $this->webroot?>uploads/ingress_action" method="POST" enctype="multipart/form-data">
<div id="static_div" style="text-align: left; width: 530px;">
<table class="cols" style="width: 252px; margin: 0px auto;">
		
	</table>
	</div>
	<table class="cols" style="width:700px;margin:0px auto;">
	<tbody><tr>
		<td style="text-align:right;padding-right:4px;" class="first"><?php echo __('Import File',true);?>:</td>
		<td style="text-align:left;" class="last"><input type="file" name="file"></td>
	</tr>
	<tr>
		<td style="text-align:right;padding-right:4px;" class="first"><?php echo __('Duplicate',true);?>:</td>
		<td style="text-align:left;" class="last">
			<input type="radio" name="duplicate_type" value="ignore" id="duplicate_type_ignore">
			<label for="duplicate_type_ignore"><?php echo __('Ignore',true);?></label>			  
<!--			<input type="radio" name="duplicate_type" value="overwrite" id="duplicate_type_overwrite">
			<label for="duplicate_type_overwrite"><?php echo __('Overwrite',true);?></label>			  -->
			<input type="radio" name="duplicate_type" value="delete" id="duplicate_type_delete"  checked="checked">
			<label for="duplicate_type_delete"><?php echo __('delete',true);?></label>
		</td>
	</tr>
        <tr><td align="right">Example:</td><td align="left"><a href="<?php echo $this->webroot?>example/resource_action.csv" target="_blank" title="Show example file">show</a></td></tr>
	<tr>
  		<td style="text-align:right;padding-right:4px;" class="first last"></td>
  </tr>
  <tr style="height:10px"><td colspan=2></td></tr>
  <tr>
  		<td colspan="2" class="first last"><div class="submit"><input type="submit" value="<?php echo __('upload',true);?>" class="input in-submit"></div></td>
	</tr>	
</tbody></table>
</form>
