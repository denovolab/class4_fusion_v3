<?php if($report_type=='spam_report'){?>
		<li>
			 <span style="float:left; height:25px; line-height:25px; font-size:14px; color:#EEEEEE; font-weight:bold;">
             <?php echo __('Refresh Period',true);?>:</span>
			<select   id="refresh_select"  name="refresh_select" onchange=" change_time(this);"	>
				<option value="3">3 Min</option>
				<option value="5">5 Min</option>
				<option    value="15">15 Min</option>
				</select>
		</li>
<?php }?>