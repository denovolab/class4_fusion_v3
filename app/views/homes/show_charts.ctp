<div id="title">
    <h1><?php echo __('Statistics'); ?> &gt;&gt; <?php echo __('Dashboard'); ?> &gt;&gt; <?php echo __('Charts'); ?></h1>
    <ul id="title-menu">
        <a class="link_back" href="<?php echo $this->webroot; ?>homes/search_charts">
            <img width="16" height="16" src="<?php echo $this->webroot; ?>images/icon_back_white.png" alt="Back">
            &nbsp;Back</a>
    </ul>
</div>

<div id="container" style="text-align:center;">
    
    <ul class="tabs">
        <li>
            <a href="<?php echo $this->webroot ?>homes/dashbroad">
                <img width="16" height="16" src="<?php echo $this->webroot ?>images/stock.png">Dashboard</a>
        </li>
        <li>
            <a href="<?php echo $this->webroot ?>homes/report">
                <img width="16" height="16" src="<?php echo $this->webroot ?>images/report.png">Report
            </a>
        </li>
        <li class="active">
            <a href="<?php echo $this->webroot ?>homes/search_charts">
                <img width="16" height="16" src="<?php echo $this->webroot ?>images/showcharts.png">Charts
            </a>
        </li>
        <li>
            <a href="<?php echo $this->webroot ?>homes/auto_delivery">
                <img width="16" height="16" src="<?php echo $this->webroot ?>images/delivery.png">Auto Delivery
            </a>
        </li>
    </ul>
    
    <h1 style="padding:0;margin:10px;width:100%;font-size:14px;text-align:center;">
        <?php
            echo $start_time . '&nbsp' . $end_time . '&nbsp' . $timezone . '(GMT)';
        ?>
    </h1>

    <input type="hidden" id="editor" />

    <div id="chart">
            chart goes here
    </div>
    


</div>


<script type="text/javascript" src="<?php echo $this->webroot; ?>flexchart/swfobject.js"></script>
<script type="text/javascript" src="<?php echo $this->webroot; ?>flexchart/prototype.js"></script>
<script type="text/javascript">

var $1 = $;

function $1G(id){
 return document.getElementById(id);
}

function $1F(id){
 return $1G(id).value;
}

function repopulate() {

        jQuery.ajax({
             'url'      : "<?php echo $this->webroot ?>homes/get_charts_data?qs=<?php echo $param; ?>",
             'type'     : "GET",
             "dataType" : "text",
             "success"  : function(data) {
                    $1("editor").value = data;
                    injectFromEditor();
             }
        });

}

function injectFromEditor() {
	function f() {
		if ($1("chart").setDescriptor) {
			if (! window.swfloaded) {
				window.swfloaded = true;
			}
                        clearInterval(i);
			$1("chart").setDescriptor($1F("editor"));
		}
	};
	var i = setInterval(f, 100);
	f();
}




swfobject.addDomLoadEvent(function() {
	window.swfloaded = false;
	swfobject.embedSWF("<?php echo $this->webroot; ?>flexchart/FlexChart.swf", "chart", '100%', 300, "9.0.28", false, {
		descriptor: "<chart />"
	},
	{
		bgcolor: "#ffffff",
		wmode: "transparent"
	});
	repopulate();
   
});

var $ = jQuery;

</script>