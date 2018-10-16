<table>
	<tr>
		<td></td>
		<td></td>
		<td><input type="text" style="width:80px" class="input in-text in-input" name="digits" value="" maxlength="32" id="digits"/></td>
		<td>
			<select class="input in-select select" name="strategy" onchange="bypercentage(this);" id="strategy">
				<option selected="" value="1">» Top-Down</option>
				<option value="0">» By Percentage </option>
				<option value="2">» Round Robin</option>
			</select>
		</td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td>
		  <a style="float: left; margin-left: 15px;" title="Save" id="save" href="" onclick="return false">
		   <img src="<?php echo $this->webroot?>images/menuIcon_004.gif" height="16" width="16">
			</a>
			<a style="float: left; margin-left: 15px;" title="Exit" id="delete" href="">
				<img src="<?php echo $this->webroot?>images/delete.png" height="16" width="16">
			</a>
		</td>
	</tr>
</table>