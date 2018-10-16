
<div id="title">
  <h1><?php echo __('Statistics',true);?>&gt;&gt;<?php echo __('LRN Report',true);?></h1>
</div>
<?php
    $data =$p->getDataArray();
?>
<div class="container">
  <?php if(empty($data)): ?>
<?php if($show_nodata): ?><div class="msg">No data found</div><?php endif; ?>
<?php else: ?>  
  <div id="toppage"></div>
  <table class="list">
    <thead>
      <tr>
        <td><?php echo __('Date',true);?></td>
        <?php if($isorder) :?>
        <td><?php echo __('ingress',true);?></td>
        <?php endif; ?>
        <td><?php echo __('Client LRN',true);?></td>
        <td><?php echo __('Cache LNP Cnt',true);?></td>
        <td><?php echo __('Server LNP Cnt',true);?></td>
        <td><?php echo __('Cost/Hit',true);?></td>
        <td><?php echo __('LNP Charge',true);?></td>
        <td><?php echo __('Total Dip',true);?></td>
        <td><?php __('Succ. Dip') ?></td>
        <td><?php  __('LRN No Responce'); ?></td>
        <td><?php  __('Dip with LRN'); ?></td>
        <td><?php  __('Dip w/o LRN'); ?></td>
      </tr>
    </thead>
    <tbody>
      <?php foreach($data as $item): ?>
      <tr>
        <td><?php echo $item[0]['time'] ?></td>
        <?php if($isorder) :?>
        <td><?php echo $ingress_trunk[$item[0]['ingress_id']]; ?></td>
        <?php endif; ?>
        <td><?php echo $item[0]['client_count'] ?></td>
        <td><?php echo $item[0]['cache_count'] ?></td>
        <td><?php echo $item[0]['lrn_server_count'] ?></td>
        <td>0</td>
        <td><?php echo $item[0]['lnp_charge'] ?></td>
        <td>
         <?php 
            echo $item[0]['total_count'];
         ?>
        </td>
        <td>
            <?php echo $item[0]['total_count'] - $item[0]['lrn_no_response'] ?>
        </td>
        <td><?php echo $item[0]['lrn_no_response'] ?></td>
        <td><?php echo $item[0]['total_count'] - $item[0]['lrn_same'] ?></td>
        <td><?php echo $item[0]['lrn_same'] ?></td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  <div id="tmppage"> <?php echo $this->element('page');?> </div>
  <?php endif; ?>
  <fieldset class="query-box" style="clear:both;overflow:hidden;margin-top:10px;">
    <div class="search_title"><img src="<?php echo $this->webroot; ?>images/search_title_icon.png"><?php echo __('search',true);?></div>
    <form name="myform" id="myform" method="get">
      <table class="form" width="100%">
        <input type="hidden" id="isDown" name="isDown" value="FALSE" />
        <tbody>
          <tr class="period-block">
            <td style="width:80px; text-align:right;"><?php echo __('Period',true);?></td>
            <td style="width:auto;" colspan="5"><table class="in-date" style="width: 98%;">
                <tbody>
                  <tr>
                    <td><?php
    $r=array('custom'=>__('custom',true),'curDay'=>__('today',true),'curWeek'=>__('currentweek',true), 'curMonth'=>__('currentmonth',true)); 		
    if(!empty($_POST)){
            if(isset($_POST['smartPeriod'])){
                    $s=$_POST['smartPeriod'];
            }else{
                    $s='curDay';
            }
    }else{

            $s='curDay';
    }
    echo $form->input('smartPeriod',
                    array('options'=>$r,'label'=>false ,
                    'onchange'=>'setPeriod(this.value)','id'=>'query-smartPeriod','name'=>'smartPeriod','style'=>'width:100px;','div'=>false,'type'=>'select','selected'=>$s));
?></td>
                    <td><input type="text" id="query-start_date-wDt"
                            class="in-text input" onChange="setPeriod('custom')"
                            readonly="readonly" onKeyDown="setPeriod('custom')" value=""
                            name="start_date"  style="width:80px;" >
                      &nbsp;
                      <input type="text" id="query-start_time-wDt"
                            onchange="setPeriod('custom')" onKeyDown="setPeriod('custom')"
                            readonly="readonly" value="00:00:00"
                            name="start_time" class="input in-text" style="width:80px;"></td>
                    <td>&mdash;</td>
                    <td><input type="text" id="query-stop_date-wDt"
                            class="in-text input" onChange="setPeriod('custom')"
                            readonly="readonly" onKeyDown="setPeriod('custom')" value=""
                            name="stop_date" style="width:80px;">
                      &nbsp;
                      <input type="text" id="query-stop_time-wDt"
                            onchange="setPeriod('custom')" readonly="readonly"
                            onkeydown="setPeriod('custom')"
                            value="23:59:59" name="stop_time" class="input in-text" style="width:80px;"></td>
                    <td>
                        <select name="group_time">
                            <option value="0" <?php if(!isset($_GET['group_time']) || $_GET['group_time'] == 0) echo 'selected="selected"' ?>>By Day</option>
                            <option value="1" <?php if(isset($_GET['group_time']) &&  $_GET['group_time'] == 1) echo 'selected="selected"' ?>>By Month</option>
                        </select>
                        
                    </td>
                    <td><select id="query-tz"
                             name="gmt" class="input in-select" style="width:100px;">
                        <option value="-1200">GMT -12:00</option>
                        <option value="-1100">GMT -11:00</option>
                        <option value="-1000">GMT -10:00</option>
                        <option value="-0900">GMT -09:00</option>
                        <option value="-0800">GMT -08:00</option>
                        <option value="-0700">GMT -07:00</option>
                        <option value="-0600">GMT -06:00</option>
                        <option value="-0500">GMT -05:00</option>
                        <option value="-0400">GMT -04:00</option>
                        <option value="-0300">GMT -03:00</option>
                        <option value="-0200">GMT -02:00</option>
                        <option value="-0100">GMT -01:00</option>
                        <option value="+0000">GMT +00:00</option>
                        <option value="+0100">GMT +01:00</option>
                        <option value="+0200">GMT +02:00</option>
                        <option value="+0300">GMT +03:00</option>
                        <option value="+0330">GMT +03:30</option>
                        <option value="+0400">GMT +04:00</option>
                        <option value="+0500">GMT +05:00</option>
                        <option value="+0600">GMT +06:00</option>
                        <option value="+0700">GMT +07:00</option>
                        <option value="+0800">GMT +08:00</option>
                        <option value="+0900">GMT +09:00</option>
                        <option value="+1000">GMT +10:00</option>
                        <option value="+1100">GMT +11:00</option>
                        <option value="+1200">GMT +12:00</option>
                      </select></td>
                  </tr>
                </tbody>
              </table></td>
          </tr>
            </tr>
          
          <?php
                    /*
                    <td>Group By Ingress Trunk?</td>
                    <td>
                        <input type="checkbox" name="isorder" <?php if(isset($isorder)) echo 'checked'; ?> />
                    </td>
                    */ 
                    ?>
        <td><?php echo __('ingress',true);?>:</td>
          <td style="text-align:left;"><select name="ingress_trunk" style="width:160px;">
              <option></option>
              <?php foreach($ingress_trunk as $key => $trunk): ?>
              <option <?php if(isset($_GET['ingress_trunk']) && $_GET['ingress_trunk'] == $key) echo 'selected'; ?> value="<?php echo $key; ?>"><?php echo $trunk; ?></option>
              <?php endforeach; ?>
            </select></td>
          <td><?php echo __('Output',true);?>:</td>
          <td style="text-align:left;"><select name="show_type" style="width:160px;">
              <option value="0">Web</option>
              <option value="1">Excel CSV</option>
            </select></td>
          <td></td>
          <td>&nbsp;</td>
        </tr>
        <tr id="group_by">
          <td colspan="8" class="single"><div class="group_by"> <?php echo __('Group By',true);?></div></td>
        </tr>
        <tr class="group_by_list">
          <td ><?php echo __('Group By',true);?>:</td>
          <td style=" text-align:left;"><select name="order_in" id="order_in" style="width:160px;">
              <option></option>
              <option value="1">Ingress Trunk</option>
            </select></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
        </tr>
          </tbody>
        
        <tfoot>
          <tr>
            <td colspan="8"><input type="submit" value="<?php echo __('submit',true);?>" /></td>
          </tr>
        </tfoot>
      </table>
    </form>
  </fieldset>
</div>
<script refer="refer" type="text/javascript">
$(function() {
    $('#down').click(function() {
        $('#isDown').val('TRUE');
        $('#myform').submit();
    });
    
    $('#order_in').val('<?php echo isset($_GET['order_in'])? $_GET['order_in']:'' ?>');
});
</script>