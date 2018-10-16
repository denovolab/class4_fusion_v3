<script type="text/javascript">
function refreshPro(){
	loadChartLeft();
	f_history();
}
//刷新数据
var time = window.setInterval("refreshPro()",180000);
</script>
<script language=JavaScript>

 		//加载全局的报表
		var c1;//左侧报表
		var c2;//右侧报表
		var globalUrl = "<?php echo $this->webroot?>";
		var p_id = "<?php echo $pro_id?>";//产品ID
 		function globalChart(id,type,obj,LOrR,url){
 	 		var c;
 			var columns = getcolumns();

			//设置请求同步
			jQuery.ajaxSetup({
				  async: false
			}); 

			var url = globalUrl+"monitors/report?whichTime="+type+"&type=2&pro_id="+p_id;

			//获取数据并且生成报表
			jQuery.get(url,function(data){
				c = Chart(data,columns,id);//生成报表的方法
			});
			

			//显示或取消下划线
			underlineornot(LOrR,obj);

			return c;
 	 	}

 		
 		
 		function f_history() {
 			var url = globalUrl+"monitors/product_history?pro_id="+p_id;
 			pro_res_history(url);
 	 	}
 		
 		google.setOnLoadCallback(loadChartLeft);//加载左侧报表
 		
 		google.setOnLoadCallback(f_history);//加载Historycal信息

 </script>


<div id="proStatsDivPic" class='monitor5'>
<div id="leftChart" class="divStyle10">
<div id="leftTitleChart" class="divStyle11">
<table width='100%' cellspacing=0 cellpadding=0>
	<tr>
		<!--<td width="4px" height='100%' style="vertical-align: top;">  <img
				src='images/rec1.gif' /></td>-->

		<td style="border:1px solid gray;background:#eeeeee;" class='monitor8'><span class="spanStyle2">Volume Metrics</span> <!--  <span
			class="spanStyle3"> <span id='volumeStart' class="spanStyle4">&nbsp;</span>
		<span>--</span> <span id='volumeEnd' class="spanStyle4">&nbsp;</span>
		<input type='hidden' id='isSMV1' name='isSMV1' value='1'> </span>-->
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;
		&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;
		&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;
		&nbsp;&nbsp;&nbsp;&nbsp; <span id="menufive" class='monitor15'><!--  <img src="images/spand.gif" />--></span>
		<input type='hidden' id='isSMV1' name='isSMV1' value='1'>
		<ul id="menufivelist" class='monitor16'>
			<li class='monitor17'>Simple Moving Average &nbsp;&nbsp;&nbsp;&nbsp;<!--  <img src='images/rights.gif' class='picSMV1'
					class='monitor18'></img>--></li>
		</ul>

		</td>

		<!--  <td width='4px' style="vertical-align: top;">  <img
				src='images/rec2.gif' /></td>-->
	</tr>
</table>
</div>
<div id="leftControlBar" class='monitor19'><span class='monitor20'>View:</span>
<input type='hidden' id='currentView1' value='call1,cps1'> <input
	type="hidden" id='currentColumn1' value='2'> <span class='monitor21'><input
	type='checkbox' checked class='monitor22' onclick='onlychoosetwo(this)'
	id="call1" />&nbsp;&nbsp;Calls<input type="hidden" name="call_1"
	id="call_1" value="0"></input></span> <span class='monitor21'><input
	type='checkbox' checked class='monitor22' onclick='onlychoosetwo(this)'
	id="cps1" />&nbsp;&nbsp;CPS<input type="hidden" name="cps_1_m"
	id="cps_1_m" value="1"></input><input type="hidden" name="cps_1"
	id="cps_1" value="1000"></input></span> <span class='monitor21'><input
	type='checkbox' disabled class='monitor22'
	onclick='onlychoosetwo(this)' id="asr1" />&nbsp;&nbsp;ASR<input
	type="hidden" name="asr_1_m" id="asr_1_m" value="1000"></input><input
	type="hidden" name="asr_1" id="asr_1" value="1000"></input></span> <span
	class='monitor21'><input type='checkbox' disabled class='monitor22'
	onclick='onlychoosetwo(this)' id="acd1" />&nbsp;&nbsp;ACD<input
	type="hidden" name="acd_1_m" id="acd_1_m" value="1000"></input><input
	type="hidden" name="acd_1" id="acd_1" value="1000"></input></span><span
	class='monitor21'><input type='checkbox' disabled class='monitor22'
	onclick='onlychoosetwo(this)' id="pdd1" />&nbsp;&nbsp;PDD<input
	type="hidden" name="pdd_1_m" id="pdd_1_m" value="1000"></input><input
	type="hidden" name="pdd_1" id="pdd_1" value="1000"></input></span></div>
<div id="leftTimeBar" class='monitor23'><span class='monitor21'>Zoom:</span>
<span class="zoomline"
	onclick="loadset('coordinateID',3,this,this.parentNode);"><?php echo __('lasthour')?></span>
<span class="zoomline"
	onclick="loadset('coordinateID',2,this,this.parentNode);"><?php echo __('last3hr')?></span>
<span id="defaultchecked" class="zoomnone"
	onclick="loadset('coordinateID',1,this,this.parentNode);"><?php echo __('last24hrs')?></span>
<span class="zoomline"
	onclick="loadset('coordinateID',4,this,this.parentNode);"><?php echo __('last3days')?></span>
<span class="zoomline"
	onclick="loadset('coordinateID',5,this,this.parentNode);"><?php echo __('last7days')?></span></div>
<div class='monitor24'>
<div id="coordinateID" class='monitor25'></div>
</div>
</div>

<div id="rightChart" class="divStyle12">
<div id="rightTitleChart" class="divStyle13">
<table width='100%' cellspacing=0 cellpadding=0>
	<tr>
		<!--<td width="4px" height='100%'>  <img src='images/rec1.gif' /></td>-->

		<td style="border:1px solid gray;background:#eeeeee;" class='monitor8'><span class="spanStyle2">Quality Metrics</span> <span
			class="spanStyle5"> <!--  <span id='qualStart' class="spanStyle4">&nbsp;</span>
		<span>--</span> --><span id='qualEnd' class="spanStyle4"> </span>
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;
		&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;
		&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;
		&nbsp;&nbsp;&nbsp;&nbsp; <span id="menufives" class='monitor15'><!--  <img src="images/spand.gif" />--></span>

		<input type='hidden' id='isSMV2' name='isSMV2' value='1'> </span>
		&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;
		<ul id="menufivelists" class='monitor16'>
			<li>Simple Moving Average &nbsp;&nbsp;<img src='images/rights.gif'
				class="picSMV2 monitor18"></img></li>
		</ul>
		</td>

		<!--<td width='4px'>  <img src='images/rec2.gif' /></td>-->
	</tr>
</table>
</div>
<div id="rightControlBar" class='monitor19'><span class='monitor20'>View:</span>
<input type='hidden' id='currentView2' value='asr2,acd2'> <input
	type="hidden" id='currentColumn2' value='2'> <span class='monitor21'><input
	type='checkbox' disabled class='monitor22'
	onclick='onlychoosetwo(this)' id="call2" />&nbsp;&nbsp;Calls<input
	type="hidden" name="call_2" id="call_2" value="1000"></input></span> <span
	class='monitor21'><input type='checkbox' disabled class='monitor22'
	onclick='onlychoosetwo(this)' id="cps2" />&nbsp;&nbsp;CPS<input
	type="hidden" name="cps_2_m" id="cps_2_m" value="1000"></input><input
	type="hidden" name="cps_2" id="cps_2" value="1000"></input></span> <span
	class='monitor21'><input type='checkbox' checked class='monitor22'
	onclick='onlychoosetwo(this)' id="asr2" />&nbsp;&nbsp;ASR<input
	type="hidden" name="asr_2_m" id="asr_2_m" value="0"></input><input
	type="hidden" name="asr_2" id="asr_2" value="1000"></input></span> <span
	class='monitor21'><input type='checkbox' checked class='monitor22'
	onclick='onlychoosetwo(this)' id="acd2" />&nbsp;&nbsp;ACD<input
	type="hidden" name="acd_2_m" id="acd_2_m" value="1"></input><input
	type="hidden" name="acd_2" id="acd_2" value="1000"></input></span><span
	class='monitor21'><input type='checkbox' disabled class='monitor22'
	onclick='onlychoosetwo(this)' id="pdd2" />&nbsp;&nbsp;PDD<input
	type="hidden" name="pdd_2_m" id="pdd_2_m" value="1"></input><input
	type="hidden" name="pdd_2" id="pdd_2" value="1000"></input></span></div>
<div id="rightTimeBar" class='monitor23'><span class='monitor21'>Zoom:</span>
<span class="zoomline"
	onclick="loadset('longerID',3,this,this.parentNode);"><?php echo __('lasthour')?></span>
<span class="zoomline"
	onclick="loadset('longerID',2,this,this.parentNode);"><?php echo __('last3hr')?></span>
<span id="defaultchecked1" class="loadset"
	onclick="loadset('longerID',1,this,this.parentNode);"><?php echo __('last24hrs')?></span>
<span class="zoomline"
	onclick="loadset('longerID',4,this,this.parentNode);"><?php echo __('last3days')?></span>
<span class="zoomline"
	onclick="loadset('longerID',5,this,this.parentNode);"><?php echo __('last7days')?></span></div>
<div class='monitor24'>
<div id="longerID" class='monitor25'></div>
</div>
</div>
<div style="clear: both;"></div>
</div>
<a name="ur"></a>

<div class="monitor_global_styles"></div>
<div>
<div class=" monitor_global_style_9">
<table style="width: 100% !important; width: 96%;" cellpadding=0
	cellspacing=0>
	<tr>
		<td width='4px' height="100%"><!--  <img src='images/rec1.gif' />--></td>
		<td class='monitor40'><span class=" monitor_global_style_10"><img
			alt="" src="images/angle.gif">&nbsp;&nbsp;<?php echo __('historical')?></span></td>
		<td width='4px'><!--<img src='images/rec2.gif' />--></td>
	</tr>
</table>
</div>
<div class="monitor_global_style_11">
<table id="show_table" cellspacing=0 cellpadding=0 width="100%">
	<thead>
		<tr height="30" bgcolor="#eeeeee">
			<th class="bcess_td">&nbsp;</th>
			<th class="bcess_td">15 <?php echo __('minutes')?></th>
			<th class="bcess_td">1 <?php echo __('hour')?></th>
			<th class="bcess_td">24 <?php echo __('hour')?></th>
		</tr>
		<tr height="28" bgcolor="#eeeeee">
			<td width="200px" class="bcess_td monitor47">Prefix Route Name:</td>
			<!--  <td width="100px" class="bcess_td" class='monitor47'>
				<div class=" monitor_product_style_10" style="margin-left:9px;">Calls</div>
				<div class=" monitor_product_style_11" style="margin-left:35px;">CPS</div>
			</td>-->
			<td class="bcess_td">
			<div class=" monitor_product_style_10">ACD</div>
			<div class=" monitor_product_style_11">ASR</div>
			<div class=" monitor_product_style_11">CA</div>
			<div class=" monitor_product_style_12">PDD</div>
			</td>
			<td class="bcess_td">
			<div class=" monitor_product_style_13">ACD</div>
			<div class=" monitor_product_style_11">ASR</div>
			<div class=" monitor_product_style_11">CA</div>
			<div class=" monitor_product_style_12">PDD</div>
			</td>
			<td class="bcess_td">
			<div class=" monitor_product_style_10">ACD</div>
			<div class=" monitor_product_style_11">ASR</div>
			<div class=" monitor_product_style_11">CA</div>
			<div class=" monitor_product_style_18">PDD</div>
			</td>
		</tr>
	</thead>
	<tbody id="tbodyOfShowTable">

	</tbody>
</table>
</div>
<div class='monitor48'>
<table width="100%" cellpadding=0 cellspacing=0>
	<tr>
		<td width='2px' height="100%"><!--  <img src='images/rec3.gif' />--></td>
		<td class='monitor46'>&nbsp;</td>
		<td width='2px'><!--<img src='images/rec4.gif' />--></td>
	</tr>
</table>
</div>
</div>


<div class='monitor49'><span class="groupBy"><?php echo __('refreshtime')?>:</span> <span>
<input type='radio' name="reTime" onclick="changeTime()" value='0'
	checked>3 <?php echo __('minutes')?> <input type='radio' name="reTime" onclick="changeTime()"
	value='1'>5 <?php echo __('minutes')?> <input type='radio' name="reTime"
	onclick="changeTime()" value='2'>15 <?php echo __('minutes')?> </span>
<input type="button" onclick="changeTime();" value="<?php echo __('refresh')?>"/>
<input type="button" onclick="clearTime();" value="<?php echo __('clear')?>"/>

</div>