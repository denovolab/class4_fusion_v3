<style type="text/css"></style>
<div id="title">
  <h1><?php echo __('Statistics',true);?>  &gt;&gt;<?php echo __('Routing Plan',true);?>

  </h1>
  <?php //echo  $this->element('qos/title_menu_ul');?> 

  <ul id="title-menu">
    <li><font class="fwhite"><?php echo __('Refresh Every',true);?>:</font>
      <select id="changetime">
        <option value="180">3 minutes</option>
        <option value="300">5 minutes</option>
        <option value="800">15 minutes</option>
      </select>
    </li>
  </ul>
  
</div>
<div id="container"> <?php echo  $this->element('qos/qos_tab',array('active_tab'=>'product'))?>
  <div id="toppage"></div>
  <table class="list nowrap with-fields">
    <thead>
      <tr>
        <td width="10%" rowspan="2" style="padding-bottom: 20px;" rel="0"><?php echo __('Product Name',true);?></td>
        <td class="cset-1" colspan="4">15 <?php echo __('minutes',true)?></td>
        <td colspan="4" class="cset-2">1 <?php echo __('hour',true)?></td>
        <td colspan="4" class="cset-3"><span id="ht-100002" class="helptip" rel="helptip">24 <?php echo __('hour',true)?>s</span><span id="ht-100002-tooltip" class="tooltip">Average successful rate (percent of successful calls)</span></td>
      </tr>
      <tr>
        <td width="6%" class="cset-1" rel="2">&nbsp;<?php echo __('avgduration')?>&nbsp;</td>
        <td width="6%" class="cset-1" rel="3">&nbsp;<?php echo __('ABR')?>&nbsp;</td>
        <td width="6%" class="cset-1" rel="2">&nbsp;<?php echo __('calls')?>&nbsp;</td>
        <td width="6%" class="cset-1" rel="3">&nbsp;<?php echo __('calldelay')?>&nbsp;</td>
        <!-- 
             <td width="6%" class="cset-1" rel="3">&nbsp;profitability&nbsp;</td> 
               
            -->
        <td width="6%" class="cset-1" rel="2">&nbsp;<?php echo __('avgduration')?>&nbsp;</td>
        <td width="6%" class="cset-1" rel="3">&nbsp;<?php echo __('ABR')?>&nbsp;</td>
        <td width="6%" class="cset-1" rel="2">&nbsp;<?php echo __('calls')?>&nbsp;</td>
        <td width="6%" class="cset-1" rel="3">&nbsp;<?php echo __('calldelay')?>&nbsp;</td>
        <!--    
              <td width="6%" class="cset-1" rel="3">&nbsp;profitability&nbsp;</td> 
              
              
            -->
        <td width="6%" class="cset-3" rel="10">&nbsp;<?php echo __('avgduration')?></td>
        <td width="6%" class="cset-3 last" rel="10" >&nbsp; <?php echo __('ABR')?> &nbsp;</td>
        <td width="6%" class="cset-3" rel="10" >&nbsp; <?php echo __('calls')?> &nbsp;</td>
        <td width="6%" class="cset-3 last" rel="10" >&nbsp;<?php echo __('calldelay')?></td>
        <!--
             <td width="6%" class="cset-1" rel="3">&nbsp;profitability&nbsp;</td> 
        --></tr>
    </thead>
    <tbody class="orig-calls" id='tbodytotal'>
       <?php $data = $p->getDataArray(); ?>
       <?php foreach($data as $val): ?>
        <tr>
<!--            <td><a href="<?php echo $this->webroot ?>monitorsreports/prefix/<?php echo $val['qos_name'] ?>"><?php echo $val['name'] ?></a></td>-->
            <td><a href="###"><?php echo $val['name'] ?></a></td>
            <td><?php echo number_format($val['acd1'] / 60, 2) ?></td>
            <td><?php echo number_format($val['asr1'], 2) ?></td>
            <td><?php echo number_format($val['ca1'], 0) ?></td>
            <td><?php echo number_format($val['pdd1'], 0) ?></td>
            <td><?php echo number_format($val['acd2'] / 60, 2) ?></td>
            <td><?php echo number_format($val['asr2'], 2) ?></td>
            <td><?php echo number_format($val['ca2'], 0) ?></td>
            <td><?php echo number_format($val['pdd2'], 0) ?></td>
            <td><?php echo number_format($val['acd3'] / 60, 2) ?></td>
            <td><?php echo number_format($val['asr3'], 2) ?></td>
            <td><?php echo number_format($val['ca3'], 0) ?></td>
            <td><?php echo number_format($val['pdd3'], 0) ?></td>
        </tr> 
       <?php endforeach; ?>
    </tbody>
  </table>
  <div id="tmppage"> <?php echo $this->element('page');?>
</div>

<script type="text/javascript">

$(function() {
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