<!--导入所有reoprt页面的input和select样式文件-->
<?php echo $this->element('magic_css');?>

<div id="title">
<h1><?php __('Statistics')?>&gt;&gt;
<?php __('DisconnectCauses')?></h1>
</div>

<div id="container">
<ul class="tabs">
	<li <?php if($rate_type=='org'){  echo "class='active'";}?>><a
		href="<?php echo $this->webroot?>/disconnectreports/summary_reports/org/"><img
		width="16" height="16"
		src="<?php echo $this->webroot?>images/list.png"><?php echo __('Origination',true);?></a></li>
	<li <?php if($rate_type=='term'){  echo "class='active'";}?>><a
		href="<?php echo $this->webroot?>/disconnectreports/summary_reports/term/">
	<img width="16" height="16"
		src="<?php echo $this->webroot?>images/menuIcon.gif"><?php echo __('Termination',true);?></a></li>
</ul>





<?php
echo $this->element ( 'discon_report/result_table' )?>
 <?php
	//echo $this->element ( 'discon_report/report_amchart' );
	//echo $this->element("report/image_report");
 ?>
<?php

	echo $this->element ( 'discon_report/search' )?>
<?php

	echo $this->element ( 'search_report/search_js_show' );
	?>



</div>
<script type="text/javascript">
<!--
 jQuery(document).ready(function(){
        jQuery('#query-dst_number,#query-interval_from,#query-interval_to').xkeyvalidate({type:'checkNum'});	
	 });
//-->
</script>

</body>

</html>