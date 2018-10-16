<div style="width: 100%;; margin: 0px ">
  <fieldset>
    <legend  style='color:#7D858E;font-size:1.1em;font-weight:bold;'> Hide Inactive Items :
    <input  type="checkbox"   name="hidden_data"  <?php if(isset($_GET['where'])&&$_GET['where']=='active'){echo "checked='checked'";}?>
        onclick="($(this).attr('checked')==true)?(location=location.toString().split('?')[0]+'?where=active'):(location=location.toString().split('?')[0]+'?where=hidden')">
    </legend>
    <?php 
					$mydata =$p->getDataArray();
					if(empty($mydata)){
						?>
    <div class="msg"><?php echo __('no_data_found',true);?></div>
    <?php }else{?>
    <div id="toppage"></div>
    <br />
    <table class="list nowrap with-fields">
      <thead>
      <tr>
        <td width="10%" rowspan="2" style="padding-bottom: 20px;" rel="0"><?php echo __('Resource',true);?></td>
        <td class="cset-1" colspan="4">15 <?php echo __('minutes',true)?></td>
        <td colspan="4" class="cset-2">1 <?php echo __('hour',true)?></td>
        <td colspan="4" class="cset-3"><span id="ht-100002" class="helptip" rel="helptip">24 <?php echo __('hour',true)?>s</span><span id="ht-100002-tooltip" class="tooltip">Average successful rate (percent of successful calls)</span></td>
      </tr>
      <tr>
        <td width="6%" class="cset-1" rel="2">&nbsp;<?php echo $appCommon->show_order('acd15m', __('avgduration', true)) ?>&nbsp;</td>
        <td width="6%" class="cset-1" rel="3">&nbsp;<?php echo $appCommon->show_order('asr15m', __('ASR', true)) ?>&nbsp;</td>
        <td width="6%" class="cset-1" rel="2">&nbsp;<?php echo $appCommon->show_order('ca15m', __('calls', true)) ?>&nbsp;</td>
        <td width="6%" class="cset-1" rel="3">&nbsp;<?php echo $appCommon->show_order('pdd15m', __('calldelay', true)) ?>&nbsp;</td>
        <!-- 
             <td width="6%" class="cset-1" rel="3">&nbsp;profitability&nbsp;</td> 
               
            -->
      <td width="6%" class="cset-1" rel="2">&nbsp;<?php echo $appCommon->show_order('acd1h', __('avgduration', true)) ?>&nbsp;</td>
        <td width="6%" class="cset-1" rel="3">&nbsp;<?php echo $appCommon->show_order('asr1h', __('ASR', true)) ?>&nbsp;</td>
        <td width="6%" class="cset-1" rel="2">&nbsp;<?php echo $appCommon->show_order('ca11h', __('calls', true)) ?>&nbsp;</td>
        <td width="6%" class="cset-1" rel="3">&nbsp;<?php echo $appCommon->show_order('pdd1h', __('calldelay', true)) ?>&nbsp;</td>
        <!--    
              <td width="6%" class="cset-1" rel="3">&nbsp;profitability&nbsp;</td> 
              
              
            -->
        <td width="6%" class="cset-1" rel="2">&nbsp;<?php echo $appCommon->show_order('acd24h', __('avgduration', true)) ?>&nbsp;</td>
        <td width="6%" class="cset-1" rel="3">&nbsp;<?php echo $appCommon->show_order('asr24h', __('ASR', true)) ?>&nbsp;</td>
        <td width="6%" class="cset-1" rel="2">&nbsp;<?php echo $appCommon->show_order('ca124h', __('calls', true)) ?>&nbsp;</td>
        <td width="6%" class="cset-1" rel="3">&nbsp;<?php echo $appCommon->show_order('pdd24h', __('calldelay', true)) ?>&nbsp;</td>
        <!--
             <td width="6%" class="cset-1" rel="3">&nbsp;profitability&nbsp;</td> 
        --></tr>
    </thead>
      <?php 
        $mydata =$p->getDataArray();
        $loop = count($mydata);
        for ($i=0;$i<$loop;$i++) {
      ?>
      <tbody >
        <tr class="row-2">
          <td class="in-decimal"><strong   > <span id="ht-100019" class="helptip" rel="helptip"><a class=" monitor_product_style_19"
         href="<?php echo $this->webroot?>monitorsreports/filterip/<?php echo $this->params['pass']['0'] ?>/<?php echo  $mydata[$i][0]['qos_name']?>"  style='color:#4B9100'>
            <?php  echo $mydata[$i][0]['qos_name'];?>
            </a></span> </strong></td>
          <td class="in-decimal"><?php echo $mydata[$i][0]['not_zero_calls_15'] == 0 ? 0 : number_format($mydata[$i][0]['bill_time_15'] / 60 / $mydata[$i][0]['not_zero_calls_15'], 2)?></td>
          <td class="in-decimal"><?php echo $mydata[$i][0]['total_calls_15'] == 0 ? 0 : number_format($mydata[$i][0]['not_zero_calls_15'] / $mydata[$i][0]['total_calls_15'] * 100, 2)?>%</td>
          <td class="in-decimal"><?php echo number_format($mydata[$i][0]['total_calls_15'], 0)?></td>
          <td class="in-decimal"><?php echo $mydata[$i][0]['total_calls_15'] == 0 ? 0 : number_format($mydata[$i][0]['total_pdd_15'] / $mydata[$i][0]['total_calls_15'], 0)?></td>
          <td class="in-decimal"><?php echo $mydata[$i][0]['not_zero_calls_1'] == 0 ? 0 : number_format($mydata[$i][0]['bill_time_1'] / 60 / $mydata[$i][0]['not_zero_calls_1'], 2)?></td>
          <td class="in-decimal"><?php echo $mydata[$i][0]['total_calls_1'] == 0 ? 0 : number_format($mydata[$i][0]['not_zero_calls_1'] / $mydata[$i][0]['total_calls_1'] * 100, 2)?>%</td>
          <td class="in-decimal"><?php echo number_format($mydata[$i][0]['total_calls_1'], 0)?></td>
          <td class="in-decimal"><?php echo $mydata[$i][0]['total_calls_1'] == 0 ? 0 : number_format($mydata[$i][0]['total_pdd_1'] / $mydata[$i][0]['total_calls_1'], 0)?></td>
          <td class="in-decimal"><?php echo $mydata[$i][0]['not_zero_calls_24'] == 0 ? 0 : number_format($mydata[$i][0]['bill_time_24'] / 60 / $mydata[$i][0]['not_zero_calls_24'], 2)?></td>
          <td class="in-decimal"><?php echo $mydata[$i][0]['total_calls_24'] == 0 ? 0 : number_format($mydata[$i][0]['not_zero_calls_24'] / $mydata[$i][0]['total_calls_24'] * 100, 2)?>%</td>
          <td class="in-decimal"><?php echo number_format($mydata[$i][0]['total_calls_24'], 0)?></td>
          <td class="in-decimal"><?php echo $mydata[$i][0]['total_calls_24'] == 0 ? 0 : number_format($mydata[$i][0]['total_pdd_24'] / $mydata[$i][0]['total_calls_24'], 0)?></td>
        </tr>
      </tbody>

      <?php }?>
    </table>
    <div id="tmppage"> <?php echo $this->element('page');?>
    </div>
    <?php }?>
  </fieldset>
</div>
