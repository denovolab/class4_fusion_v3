<style type="text/css">
#container label {width:80px;display:block;float:left;}
#down_panel {width:80%; margin:0 auto;}
#option_panel {float:left; width:40%;}
#field_panel {float:left;margin-left:100px; width:50%;}
.buttons {text-align:center;}
</style>

<div id="title">
	<h1><?php echo $header; ?></h1>
</div>

<div id="container">
    <ul class="tabs">
        <li><a href="<?php echo $this->webroot ?>products/route_info/<?php echo $route_id ?>"><img width="16" height="16" src="<?php echo $this->webroot ?>images/menuIcon.gif">List</a></li>
        <li><a href="<?php echo $this->webroot ?>uploads/static_route/<?php echo $route_id ?>"><img width="16" height="16" src="<?php echo $this->webroot ?>images/import.png"> Import</a></li> 
        <li class="active"><a href="<?php echo $this->webroot ?>down/static_route/<?php echo $route_id ?>"><img width="16" height="16" src="<?php echo $this->webroot ?>images/export.png"> export</a></li>   
    </ul>
	<div id="down_panel">
		<form name="myform" method="post">
		<div id="option_panel">
			<fieldset>
				<legend>Format Options</legend>
				<p>
					<label>Data Format:</label>
					<select name="data_format">
						<option value="0">CSV</option>
						<option value="1">XLS</option>
					</select>
				</p>
				<p>
					<label>&nbsp;</label>
					<input type="checkbox" name="with_header" checked="checked" />With headers row
				</p>
				<p>
					<label>Header Text:</label>
					<textarea rows="3" cols="10" name="header_text" style="width:220px;"></textarea>
				</p>
				<p>
					<label>Footer Text:</label>
					<textarea rows="3" cols="10" name="footer_text" style="width:220px;"></textarea>
				</p>	
				
			</fieldset>
		</div>
		<div  id="field_panel">
			<fieldset>
				<legend>Columns</legend>
				<?php 
					$size = count($fields); 
					for ($i = 0; $i < $size; $i++):
				?>
				<p>
					<label>Column #<?php echo $i+1; ?>:</label>
					<select name="fields[]">
						<option></option>
						<?php for ($j = 0; $j < $size; $j++): ?>
                                                <option <?php echo $j == $i ? 'selected' : '' ?>><?php echo $fields[$j]; ?></option>
						<?php endfor; ?>
					</select>
				</p>
				<?php endfor; ?>
			</fieldset>
		</div>
		<br style="clear:both;" />	
		<div class="buttons"><input type="submit" value="Download" /></div>
		</form>
	</div>
</div>