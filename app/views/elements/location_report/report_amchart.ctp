
<div class="group-title bottom"><a onclick="$('#charts_holder').toggle();return false;" href="#">
<?php	echo __ ( 'viewcharts' )?></a>
</div>


<div style="display: none;" id="charts_holder">
<fieldset  style="display: block; border-top: 1px solid rgb(181, 199, 216);">
<legend style="color: rgb(125, 133, 142); font-size: 1.1em; font-weight: bold;">
<?php echo __('Duration',true);?>
	</legend></fieldset>
<div id="chart_9be11_div" class="amChart">
<div id="chart_9be11_div_inner" class="amChartInner">
<script	type="text/javascript" src="<?php 	echo $this->webroot?>amcolumn/swfobject.js"></script>
	
	
	
<div id="flashcontent">
<strong>You need to upgrade your Flash Player</strong>
</div>
<script type="text/javascript">
	 var so = new SWFObject("<?php echo $this->webroot?>/amstock/amstock.swf", "amstock", "100%", "450", "8", "#FFFFFF");
	 so.addVariable("path", "<?php	echo $this->webroot?>/amstock/");
	 so.addVariable("settings_file", encodeURIComponent("<?php echo $this->webroot?>locationreports/flash_setting.xml?f=duration"));
	 so.write("flashcontent");//将flash放入div
	</script>
	
	
	
	
	

<br/><br/>
<fieldset  style="display: block; border-top: 1px solid rgb(181, 199, 216);">
<legend style="color: rgb(125, 133, 142); font-size: 1.1em; font-weight: bold;">
<?php echo __('Call Count',true);?>
	</legend></fieldset>
<div id="flashcontent2">
<strong>You need to upgrade your Flash Player</strong>
</div>
<script type="text/javascript">
	 var so = new SWFObject("<?php echo $this->webroot?>amstock/amstock.swf", "amstock", "100%", "450", "8", "#FFFFFF");
	 so.addVariable("path", "<?php	echo $this->webroot?>amstock/");
	 so.addVariable("settings_file", encodeURIComponent("<?php echo $this->webroot?>locationreports/flash_setting.xml?f=call_count"));
	 so.write("flashcontent2");//将flash放入div
	</script>
	
	
	
	
	
	<br/><br/>
<fieldset  style="display: block; border-top: 1px solid rgb(181, 199, 216);">
<legend style="color: rgb(125, 133, 142); font-size: 1.1em; font-weight: bold;">
<?php echo __('Revenue',true);?>
	</legend></fieldset>
<div id="flashcontent3">
<strong>You need to upgrade your Flash Player</strong>
</div>
<script type="text/javascript">
	 var so = new SWFObject("<?php echo $this->webroot?>/amstock/amstock.swf", "amstock", "100%", "450", "8", "#FFFFFF");
	 so.addVariable("path", "<?php	echo $this->webroot?>/amstock/");
	 so.addVariable("settings_file", encodeURIComponent("<?php echo $this->webroot?>locationreports/flash_setting.xml?f=revenue"));
	 so.write("flashcontent3");//将flash放入div
	</script>
	
	
	
	
	
	
	
		<br/><br/>
<fieldset  style="display: block; border-top: 1px solid rgb(181, 199, 216);">
<legend style="color: rgb(125, 133, 142); font-size: 1.1em; font-weight: bold;">
<?php echo __('Total Cost',true);?>
	</legend></fieldset>
<div id="flashcontent4">
<strong>You need to upgrade your Flash Player</strong>
</div>
<script type="text/javascript">
	 var so = new SWFObject("<?php echo $this->webroot?>amstock/amstock.swf", "amstock", "100%", "450", "8", "#FFFFFF");
	 so.addVariable("path", "<?php	echo $this->webroot?>amstock/");
	 so.addVariable("settings_file", encodeURIComponent("<?php echo $this->webroot?>locationreports/flash_setting.xml?f=total_cost"));
	 so.write("flashcontent4");//将flash放入div
	</script>
	
	
	
	
	
	
			<br/><br/>
<fieldset  style="display: block; border-top: 1px solid rgb(181, 199, 216);">
<legend style="color: rgb(125, 133, 142); font-size: 1.1em; font-weight: bold;">
<?php echo __('Profit',true);?>
	</legend></fieldset>
<div id="flashcontent5">
<strong>You need to upgrade your Flash Player</strong>
</div>
<script type="text/javascript">
	 var so = new SWFObject("<?php echo $this->webroot?>/amstock/amstock.swf", "amstock", "100%", "450", "8", "#FFFFFF");
	 so.addVariable("path", "<?php	echo $this->webroot?>/amstock/");
	 so.addVariable("settings_file", encodeURIComponent("<?php echo $this->webroot?>locationreports/flash_setting.xml?f=profit"));
	 so.write("flashcontent5");//将flash放入div
	</script>
	
	
	
	
	
	
	
	
				<br/><br/>
<fieldset  style="display: block; border-top: 1px solid rgb(181, 199, 216);">
<legend style="color: rgb(125, 133, 142); font-size: 1.1em; font-weight: bold;">
<?php echo __('Profit',true);?>  %
	</legend></fieldset>
<div id="flashcontent6">
<strong>You need to upgrade your Flash Player</strong>
</div>
<script type="text/javascript">
	 var so = new SWFObject("<?php echo $this->webroot?>/amstock/amstock.swf", "amstock", "100%", "450", "8", "#FFFFFF");
	 so.addVariable("path", "<?php	echo $this->webroot?>/amstock/");
	 so.addVariable("settings_file", encodeURIComponent("<?php echo $this->webroot?>locationreports/flash_setting.xml?f=profit_percentage"));
	 so.write("flashcontent6");//将flash放入div
	</script>
	
	
	</div>
</div>
</div>