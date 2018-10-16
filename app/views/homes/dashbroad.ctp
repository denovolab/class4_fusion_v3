<div id="title">
    <h1><?php echo __('Statistics'); ?> &gt;&gt; <?php echo __('Dashboard'); ?></h1>
</div>

<div id="container" style="text-align:center;">
    
    <ul class="tabs">
        <li class="active">
            <a href="<?php echo $this->webroot ?>homes/dashbroad">
                <img width="16" height="16" src="<?php echo $this->webroot ?>images/stock.png">Dashboard</a>
        </li>
        <li>
            <a href="<?php echo $this->webroot ?>homes/report">
                <img width="16" height="16" src="<?php echo $this->webroot ?>images/report.png">Report
            </a>
        </li>
        <li>
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

    <input type="hidden" id="editor1" />
    <input type="hidden" id="editor2" />
    <input type="hidden" id="editor3" />
    
    <div style="margin:10px;">
        <!--
        <label>Data Type:</label>
        
        <select id="data_type">
            <option selected="selected" value="1">Connected Call</option>
            <option value="2">Call Attempts</option>
        </select>
        -->
        <input id="data_type" type="hidden" value="1" />
        
        <label>Type:</label>
        <select id="type">
            <option selected="selected" value="1">Network</option>
            <option value="2">Orig Trunks</option>
            <option value="3">Term Trunks</option>
        </select>
        
        &nbsp;
        
        <span id="trunk_list_panel">
        <label>Trunk List:</label>
        <select id="trunks">
            <option value="top5">Top 5</option>
            <option value="top10">Top 10</option>
            <option value="top15">Top 15</option>
            <option value="top20">Top 20</option>
            <option value="all">All</option>
        </select>
        
        &nbsp;
        
        <label>Trunk IP List:</label>
        <select id="trunks_ips">
            <option value="0">All</option>
        </select>
        </span>
        
        &nbsp;
        <label>Interval:</label>
        <select id="duration">
            <option selected="selected" value="1">Last Hour</option>
            <option value="2">Last 24-Hour</option>
            <option value="3">Last 7-Day</option>
            <option value="6">Last 15-Day</option>
            <option value="7">Last 30-Day</option>
            <option value="8">Last 60-Day</option>
            <option value="4">Last 30-Min</option>
            <option value="5">Last 15-Min</option>
        </select>

        <input onclick="repopulate();" type="button" id="refresh" value="Refresh" />
    </div>

    <div id="chart3">
            chart goes here
    </div>
    <br />
    <div id="chart1">
            chart goes here
    </div>
    <br />
    <div id="chart2">
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
        var type = $1F('type');
        var duration = $1F('duration');
        var trunk = $1F('trunks');
        var trunk_ip = $1F('trunks_ips');
        var data_type = $1F('data_type');
        
        jQuery.ajax({
             'url'      : "<?php echo $this->webroot ?>homes/get_draws_data",
             'type'     : "POST",
             'data'     : {'type':type, 'duration':duration, 'trunk':trunk, 'trunk_ip':trunk_ip, 'data_type': data_type},
             "dataType" : "json",
             "success"  : function(data) {
                    $1("editor1").value = data.call;
                    //if(data_type == 1)
                    $1("editor2").value = data.cps;
                    $1("editor3").value = data.channel;
                    injectFromEditor();
             }
        });

}

function injectFromEditor() {
	function f() {
		if ($1("chart1").setDescriptor && $1("chart2").setDescriptor) {
			if (! window.swfloaded1) {
				window.swfloaded1 = true;
			}
			$1("chart1").setDescriptor($1F("editor1"));
                        if (! window.swfloaded2) {
				window.swfloaded2 = true;
			}
			$1("chart2").setDescriptor($1F("editor2"));
                        if (! window.swfloaded3) {
				window.swfloaded3 = true;
			}
			$1("chart3").setDescriptor($1F("editor3"));
                        clearInterval(i);
		}
	};
	var i = setInterval(f, 100);
	f();
}


swfobject.addDomLoadEvent(function() {
	window.swfloaded1 = false;
        window.swfloaded2 = false;
        window.swfloaded3 = false;
	swfobject.embedSWF("<?php echo $this->webroot; ?>flexchart/FlexChart.swf", "chart1", '100%', 300, "9.0.28", false, {
		descriptor: "<chart />"
	},
	{
		bgcolor: "#ffffff",
		wmode: "transparent"
	});
        swfobject.embedSWF("<?php echo $this->webroot; ?>flexchart/FlexChart.swf", "chart2", '100%', 300, "9.0.28", false, {
		descriptor: "<chart />"
	},
	{
		bgcolor: "#ffffff",
		wmode: "transparent"
	});
        swfobject.embedSWF("<?php echo $this->webroot; ?>flexchart/FlexChart.swf", "chart3", '100%', 300, "9.0.28", false, {
		descriptor: "<chart />"
	},
	{
		bgcolor: "#ffffff",
		wmode: "transparent"
	});
	repopulate();
   
});

var $ = jQuery;

window.setInterval('repopulate();', 1000 * 60);

$(function() {
    $('#type').change(function() {
        var val = $(this).val();
        var $trunks = $('#trunks');
        var $trunks_panel = $('#trunk_list_panel');
        if (val != 1 && $('#data_type').val() == 1) {
            $trunks_panel.show();
            $.ajax({
                'url'      : "<?php echo $this->webroot ?>homes/get_trunks/" + val,
                'type'     : "GET",
                "dataType" : "json",
                "success"  : function(data) {
                       $trunks.find('option:gt(5)').remove();
                       $.each(data, function(index, value) {
                           $trunks.append('<option value="' + value[0]['resource_id'] + '">' + value[0]['alias'] + '</option>')
                       });
                       var $trunks_ips = $('#trunks_ips');
                       $trunks_ips.empty();
                       $trunks_ips.append('<option value="0">All</option>');
                }
            });
        } else {
            $trunks_panel.hide();
        }
    });
    
    $('#trunks').change(function() {
        var val = $(this).val();
        var $trunks_ips = $('#trunks_ips');
        if (val != 0) {
            $.ajax({
                'url'      : "<?php echo $this->webroot ?>homes/get_trunk_ips/" + val,
                'type'     : "GET",
                "dataType" : "json",
                "success"  : function(data) {
                       $trunks_ips.empty();
                       $trunks_ips.append('<option value="0">All</option>');
                       $.each(data, function(index, value) {
                           $trunks_ips.append('<option value="' + value[0]['resource_ip_id'] + '">' + value[0]['ip'] + '</option>')
                       });
                }
            });
        } else {
            $trunks_ips.empty();
            $trunks_ips.append('<option value="0" selected>All</option>');
        }
    });
    $('#type').change();
    
    
    $('#data_type').change(function() {
        var $this = $(this);
        if($this.val() == '1')
        {
            $('#type').show();
        }
        else if($this.val() == '2')
        {
            $('#trunk_list_panel').hide();
        }
    });
    
    $('#data_type').change();
    
});



</script>