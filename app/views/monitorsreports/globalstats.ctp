<div id="title">
  <h1>
    <?php __('Statistics')?>
    &gt;&gt;<?php echo __('QoS Monitor',true);?></h1>
  <ul id="title-menu">
    <li><font class="fwhite"><?php echo __('Refresh Every',true);?>:</font>
      <select id="changetime">
        <option value="180">3 minutes</option>
        <option value="300">5 minutes</option>
        <option value="800">15 minutes</option>
      </select>
    </li>
    <li>
        <font class="fwhite"><?php echo __('Switch Server',true);?>:</font>
        <select id="server_info" style="width:180px;">
            <?php foreach($limit_servers as $limit_server): ?>
<!--            <option value="<?php echo $limit_server[0]['info_ip'] . ':' . $limit_server[0]['info_port'] ?>"><?php echo $limit_server[0]['ip'] . ':' . $limit_server[0]['port'] ?></option>-->
<option value="<?php echo $limit_server[0]['lan_ip'] . ':' . $limit_server[0]['lan_port'] ?>"><?php echo $limit_server[0]['sip_ip'] . ':' . $limit_server[0]['sip_port'] ?></option>
            <?php endforeach;?>
<!--            <option value="all">All Server</option>-->
        </select>
    </li>
    <!--			<li>    
					<font class="fwhite"><?php echo __('serverip')?>:</font>&nbsp;<?php
/*
							if(count($host_ip)==1){
							    echo $form->input('host_ip',
							 		array('options'=>$host_ip,'selected'=>$host_ip,'label'=>false ,'div'=>false,'onchange'=>'change_ip();'   ,'type'=>'select'));
							}else{
								 echo $form->input('host_ip',
							 		array('options'=>$host_ip,'empty'=>__('select',true),'label'=>false ,'div'=>false,'onchange'=>'change_ip();','type'=>'select'));

							}
*/
				   ?>   
		   </li>-->
  </ul>
</div>
<div id="container"> <?php echo  $this->element('qos/qos_tab',array('active_tab'=>'global'))?> 
<?php echo  $this->element('element_stock'); ?>
  <div style="width: 100%;; margin: 0px ">
    <!--
    <fieldset>
      <legend  style='color:#7D858E;font-size:1.1em;font-weight:bold;'> Overall</legend>
      <table class="list list-form">
        <thead>
          <tr>
            <td width="20%"></td>
            <td width="20%"><?php echo __('currently')?></td>
            <td width="20%"><?php echo __('Max')?></td>
          </tr>
        </thead>
        <tbody  id='currentSys'>
          <tr class="row-1">
            <td class="label"><?php echo __('totalcall')?>s</td>
            <td class="in-decimal"><strong style="color: green;" id="current_calls">0</strong></td>
            <td class="in-decimal"><strong style="color: green;" id="system_max_calls">0</strong></td>
          </tr>
          <tr class="row-2">
            <td class="label"><?php echo __('totalpermin')?></td>
            <td class="in-decimal"><strong style="color: red;" id="current_cps">0</strong></td>
            <td class="in-decimal"><strong style="color: red;" id="system_max_cps">0</strong></td>
          </tr>
        </tbody>
      </table>
    </fieldset>
     -->
     <fieldset>
     <legend  style='color:#7D858E;font-size:1.1em;font-weight:bold;'> Session</legend>
      <table class="list list-form">
        <thead>
          <tr>
            <td width="20%"></td>
<!--            <td width="20%"><?php echo __('Total Limit')?></td>-->
            <td width="20%"><?php echo __('Ingress Channel')?></td>
            <td width="20%"><?php echo __('Egress Channel')?></td>
            <td width="20%"><?php echo __('Total Channel')?></td>
            <td width="20%"><?php echo __('Total Call')?></td>
          </tr>
        </thead>
        <tbody  id='currentSys'>
          <tr class="row-1">
            <td class="label"></td>
<!--            <td class="in-decimal"><strong style="color: green;" id="session_limit">0</strong></td>-->
            <td class="in-decimal"><strong style="color: green;" id="ingress_channel"><?php echo $callStatistics['o_chan'] ? $callStatistics['o_chan'] : 0;?></strong></td>
            <td class="in-decimal"><strong style="color: green;" id="egress_channel"><?php echo $callStatistics['t_chan'] ? $callStatistics['t_chan'] : 0;?></strong></td>
            <td class="in-decimal"><strong style="color: green;" id="session_count"><?php echo $callStatistics['chan'] ? $callStatistics['chan'] : 0;?></strong></td>
            <td class="in-decimal"><strong style="color: green;" id="total_call"><?php echo $callStatistics['call'] ? $callStatistics['call'] : 0;?></strong></td>
          </tr>
        </tbody>
      </table>
    </fieldset> 
      
      
    <div style="width: 100%;; margin: 0px ">
      <fieldset>
        <legend  style='color:#7D858E;font-size:1.1em;font-weight:bold;'><?php echo __('Point in time',true);?></legend>
        <table class="list list-form">
          <thead>
            <tr>
              <td width="20%"></td>
              <td width="20%"><?php echo __('currently')?></td>
              <td width="20%"><?php echo __('24hrpeak')?></td>
              <td width="20%"><?php echo __('7dayspeak')?></td>
              <td width="20%"><?php echo __('recentpeak')?></td>
              <td width="20%"><?php echo __('Max')?></td>
            </tr>
          </thead>
          <tbody  id='syslimit'>
            <tr class="row-1">
              <td class="label"><?php echo __('Ingress Channel',true);?></td>
              <td class="in-decimal"><strong style="color: green;"><?php echo $callStatistics['o_chan'] ? $callStatistics['o_chan'] : 0;?></strong></td>
              <td class="in-decimal"><strong style="color: green;"><?php echo $peakStatistics['chan_24hr'] ? $peakStatistics['chan_24hr'] : 0;?></strong></td>
              <td class="in-decimal"><strong style="color: green;"><?php echo $peakStatistics['chan_7day'] ? $peakStatistics['chan_7day'] : 0;?></strong></td>
              <td class="in-decimal"><strong style="color: green;"><?php echo $peakStatistics['chan_rece'] ? $peakStatistics['chan_rece'] : 0;?></strong></td>
              <td class="in-decimal"><strong style="color: green;"><?php echo $licenseLimits[0] ? strip_tags($licenseLimits[0]) : 0;?></strong></td>
            </tr>
            <tr class="row-1">
              <td class="label"><?php echo __('Ingress CPS',true);?></td>
              <td class="in-decimal"><?php echo $callStatistics['o_cps'] ? $callStatistics['o_cps'] : 0;?></td>
              <td class="in-decimal"><?php echo $peakStatistics['cps_24hr'] ? $peakStatistics['cps_24hr'] : 0;?></td>
              <td class="in-decimal"><?php echo $peakStatistics['cps_7day'] ? $peakStatistics['cps_7day'] : 0;?></td>
              <td class="in-decimal"><?php echo $peakStatistics['cps_rece'] ? $peakStatistics['cps_rece'] : 0;?></td>
              <td class="in-decimal"><?php echo $licenseLimits[1] ? strip_tags($licenseLimits[1]) : 0;?></td>
            </tr>
            <tr class="row-1">
              <td class="label"><?php echo __('Calls',true);?></td>
              <td class="in-decimal"><?php echo $callStatistics['call'] ? $callStatistics['call'] : 0;?></td>
              <td class="in-decimal"><?php echo $peakStatistics['call_24hr'] ? $peakStatistics['call_24hr'] : 0;?></td>
              <td class="in-decimal"><?php echo $peakStatistics['call_7day'] ? $peakStatistics['call_7day'] : 0;?></td>
              <td class="in-decimal"><?php echo $peakStatistics['call_rece'] ? $peakStatistics['call_rece'] : 0;?></td>
              <td class="in-decimal"></td>
            </tr>
          </tbody>
        </table>
      </fieldset>
      <fieldset>
        <legend  style='color:#7D858E;font-size:1.1em;font-weight:bold;'> <?php echo __('Historical',true);?></legend>
        <table class="list nowrap with-fields">
          <thead>
            <tr>
              <td width="10%" rowspan="2" rel="0"></td>
              <td class="cset-1" colspan="4">15 <?php echo __('minutes',true)?></td>
              <td colspan="4" class="cset-2">1 <?php echo __('hour',true)?></td>
              <td colspan="4" class="cset-3"><span id="ht-100002" class="helptip" rel="helptip">24 <?php echo __('hour',true)?>s</span><span id="ht-100002-tooltip" class="tooltip">Average successful rate (percent of successful calls)</span></td>
            </tr>
            <tr>
              <td width="6%" class="cset-1" rel="2">&nbsp;<?php echo __('avgduration')?>&nbsp;</td>
              <td width="6%" class="cset-1" rel="3">&nbsp;<?php echo __('ASR')?>&nbsp;</td>
              <td width="6%" class="cset-1" rel="2">&nbsp;<?php echo __('calls')?>&nbsp;</td>
              <td width="6%" class="cset-1" rel="3">&nbsp;<?php echo __('calldelay')?>&nbsp;</td>
              <!-- 
             <td width="6%" class="cset-1" rel="3">&nbsp;profitability&nbsp;</td> 
               
            -->
              <td width="6%" class="cset-1" rel="2">&nbsp;<?php echo __('avgduration')?>&nbsp;</td>
              <td width="6%" class="cset-1" rel="3">&nbsp;<?php echo __('ASR')?>&nbsp;</td>
              <td width="6%" class="cset-1" rel="2">&nbsp;<?php echo __('calls')?>&nbsp;</td>
              <td width="6%" class="cset-1" rel="3">&nbsp;<?php echo __('calldelay')?>&nbsp;</td>
              <!--    
              <td width="6%" class="cset-1" rel="3">&nbsp;profitability&nbsp;</td> 
              
              
            -->
              <td width="6%" class="cset-3" rel="10">&nbsp;<?php echo __('avgduration')?></td>
              <td width="6%" class="cset-3 last" rel="10" >&nbsp; <?php echo __('ASR')?> &nbsp;</td>
              <td width="6%" class="cset-3" rel="10" >&nbsp; <?php echo __('calls')?> &nbsp;</td>
              <td width="6%" class="cset-3 last" rel="10" >&nbsp;<?php echo __('calldelay')?></td>
              <!--
             <td width="6%" class="cset-1" rel="3">&nbsp;profitability&nbsp;</td> 
        --></tr>
          </thead>
          <tbody class="orig-calls"   id='tbodyOfShowTable'>
            <tr class="subheader row-1">
              <td align="left" class="last" colspan="17"  style="text-align: left;"><?php echo __('Total Calls',true);?></td> 
            </tr>
            <tr class="row-2">
              <td class="in-decimal"><strong   style='color:#4B9100'></strong></td>
              <td class="in-decimal" id="acd_15"><?php echo round($historyFifteenMinutes[0][0]['bill_time'] / 60 / $historyFifteenMinutes[0][0]['not_zero_calls'], 2) ?></td>
              <td class="in-decimal" id="abr_15"><?php echo round($historyFifteenMinutes[0][0]['not_zero_calls'] / $historyFifteenMinutes[0][0]['total_calls'] * 100, 2); ?>%</td>
              <td class="in-decimal" id="ca_15"><?php echo $historyFifteenMinutes[0][0]['total_calls'] ?></td>
              <td class="in-decimal" id="pdd_15"><?php echo $historyFifteenMinutes[0][0]['total_calls'] == 0 ? 0 : round($historyFifteenMinutes[0][0]['pdd'] / $historyFifteenMinutes[0][0]['total_calls'], 2); ?></td>
              <!--
    <td class="in-decimal"   id="profit_15">0</td>
   
   -->
              <td class="in-decimal"  id="acd_1h"><?php echo round($historyOneHour[0][0]['bill_time'] / 60 / $historyOneHour[0][0]['not_zero_calls'], 2) ?></td>
              <td class="in-decimal"  id="abr_1h"><?php echo round($historyOneHour[0][0]['not_zero_calls'] / $historyOneHour[0][0]['total_calls'] * 100, 2); ?>%</td>
              <td class="in-decimal"  id="ca_1h"><?php echo $historyOneHour[0][0]['total_calls'] ?></td>
              <td class="in-decimal"   id="pdd_1h"><?php echo $historyOneHour[0][0]['total_calls'] == 0 ? 0 : round($historyOneHour[0][0]['pdd'] / $historyOneHour[0][0]['total_calls'], 2); ?></td>
              <!--
   <td class="in-decimal"   id="profit_1h">0</td>
   
   -->
              <td class="in-decimal"  id="acd_24h"><?php echo round($historyOneDay[0][0]['bill_time'] / 60 / $historyOneDay[0][0]['not_zero_calls'], 2) ?></td>
              <td class="in-decimal"  id="abr_24h"><?php echo round($historyOneDay[0][0]['not_zero_calls'] / $historyOneDay[0][0]['total_calls'] * 100, 2); ?>%</td>
              <td class="in-decimal"  id="ca_24h"><?php echo $historyOneDay[0][0]['total_calls'] ?></td>
              <td class="in-decimal last"   id="pdd_24h"><?php echo $historyOneDay[0][0]['total_calls'] == 0 ? 0 : round($historyOneDay[0][0]['pdd'] / $historyOneDay[0][0]['total_calls'], 2); ?></td>
              <!--
    <td class="in-decimal last"   id="profit_24h">0</td>
  
    --></tr>
          </tbody>
        </table>
      </fieldset>
      <fieldset>
        <legend  style='color:#7D858E;font-size:1.1em;font-weight:bold;'></legend>
      </fieldset>
    </div>
  </div>


  <script type="text/javascript" src="<?php echo $this->webroot?>js/My97DatePicker/WdatePicker.js"></script> 
  <script type="text/javascript" src="<?php echo $this->webroot?>js/chart.js"></script> 
</div>

<script type="text/javascript">
function set_sys_limit(data) {
    if(data != '[]') {
        data = eval('(' + data + ')');
//        $('#current_calls').text(data.curr_cap);
//        $('#system_max_calls').text(data.max_cap);
//        $('#current_cps').text(data.curr_cps);
//        $('#system_max_cps').text(data.max_cps);
        $('#syslimit tr:nth-child(1) td:nth-child(2) strong').eq(0).text(data.curr_chan);
        $('#syslimit tr:nth-child(1) td:nth-child(3) strong').eq(0).text(data['chan_24hr']);
        $('#syslimit tr:nth-child(1) td:nth-child(4) strong').eq(0).text(data['chan_7day']);
        $('#syslimit tr:nth-child(1) td:nth-child(5) strong').eq(0).text(data['chan_rece']);
        $('#syslimit tr:nth-child(1) td:nth-child(6) strong').eq(0).text(data['max_chan']);

        $('#syslimit tr:nth-child(2) td:nth-child(2)').eq(0).text(data.curr_cps);
        $('#syslimit tr:nth-child(2) td:nth-child(3)').eq(0).text(data['cps_24hr']);
        $('#syslimit tr:nth-child(2) td:nth-child(4)').eq(0).text(data['cps_7day']);
        $('#syslimit tr:nth-child(2) td:nth-child(5)').eq(0).text(data['cps_rece']);
        $('#syslimit tr:nth-child(2) td:nth-child(6)').eq(0).text(data['max_cps']);
        
        $('#syslimit tr:nth-child(3) td:nth-child(2)').eq(0).text(data.curr_call);
        $('#syslimit tr:nth-child(3) td:nth-child(3)').eq(0).text(data['call_24hr']);
        $('#syslimit tr:nth-child(3) td:nth-child(4)').eq(0).text(data['call_7day']);
        $('#syslimit tr:nth-child(3) td:nth-child(5)').eq(0).text(data['call_rece']);
        
        $('#session_count').text(data['chan']);
        $('#ingress_channel').text(data['o_chan']);
        $('#egress_channel').text(data['t_chan']);
        $('#total_call').text(data['call']);
    } else {
//        $('#current_calls').text(0);
//        $('#system_max_calls').text(0);
//        $('#current_cps').text(0);
//        $('#system_max_cps').text(0);
        $('#syslimit tr:nth-child(1) td:nth-child(2) strong').eq(0).text(0);
        $('#syslimit tr:nth-child(1) td:nth-child(3) strong').eq(0).text(0);
        $('#syslimit tr:nth-child(1) td:nth-child(4) strong').eq(0).text(0);
        $('#syslimit tr:nth-child(1) td:nth-child(5) strong').eq(0).text(0);
        $('#syslimit tr:nth-child(1) td:nth-child(6) strong').eq(0).text(0);

        $('#syslimit tr:nth-child(2) td:nth-child(2)').eq(0).text(0);
        $('#syslimit tr:nth-child(2) td:nth-child(3)').eq(0).text(0);
        $('#syslimit tr:nth-child(2) td:nth-child(4)').eq(0).text(0);
        $('#syslimit tr:nth-child(2) td:nth-child(5)').eq(0).text(0);
        $('#syslimit tr:nth-child(2) td:nth-child(6)').eq(0).text(0);
        
        
        $('#syslimit tr:nth-child(3) td:nth-child(2)').eq(0).text(0);
        $('#syslimit tr:nth-child(3) td:nth-child(3)').eq(0).text(0);
        $('#syslimit tr:nth-child(3) td:nth-child(4)').eq(0).text(0);
        $('#syslimit tr:nth-child(3) td:nth-child(5)').eq(0).text(0);
        
        
        $('#session_count').text(0);
//        $('#session_limit').text(0);
        $('#ingress_channel').text(0);
        $('#egress_channel').text(0);
        $('#total_call').text(0);
    }
}

$(function() {

//    $('#server_info').change(function() {
//        var server_info = $(this).val();
//        var server_info_arr = server_info.split(':');
//        var ip = server_info_arr[0];
//        var port = server_info_arr[1];
//        $.ajax({
//            'url' : "<?php //echo $this->webroot; ?>//monitorsreports/get_sys_limit",
//            'type' : 'POST',
//            'dataType' : 'text',
//            'data' : {'ip':ip, 'port':port},
//            'success' : function(data) {
//                set_sys_limit(data);
//            }
//        });
//    });
//
//
//    $('#server_info').change();

    var interv = null;

    $('#changetime').change(function() {
        if(interv) 
            window.clearInterval(interv);
        var time = $(this).val() * 1000;
        interv = window.setInterval("loading();window.location.reload()", time); 
    });

    $('#changetime').change();

});
</script>
