<style type="text/css">
    #error_info {
        background:white;width:300px;height:200px;display:none;
        overflow:hide;word-wrap: break-word; padding:20px;
    }
</style>

<div id="title">
    <h1><?php echo __('Log',true);?>&gt;&gt;<?php echo __('Modification Log ',true);?></h1>
</div>

<div id="container">
    <?php echo $this->element("xpage")?>
    <table class="list">
        <thead>
            <tr>
                <td>Time</td>
                <td>Module</td>
                <td>Operator</td>
                <td>Target</td>
                <td>Action</td>
            </tr>
        </thead>
        
        <tbody>
            <?php foreach($this->data as $item): ?>
            <tr>
                <td><?php echo $item['Logging']['time']; ?></td>
                <td><?php echo $item['Logging']['module']; ?></td>
                <td><?php echo $item['Logging']['name']; ?></td>
                <td><?php echo $item['Logging']['detail']; ?></td>
                <td><?php echo $actions[$item['Logging']['type']]; ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php echo $this->element("xpage")?>
    
    <fieldset style="clear:both;overflow:hidden;margin-top:10px;" class="query-box">
    <div class="search_title"><img src="<?php echo $this->webroot; ?>images/search_title_icon.png">Search</div>
    <form method="get" id="myform" name="myform">
      <table width="100%" class="form">
        <input type="hidden" value="FALSE" name="isDown" id="isDown" class="input in-hidden">
        <tbody>
          <tr class="period-block">
            <td style="width:80px; text-align:right;" class="label label2">Period</td>
            <td colspan="5" style="width:auto;" class="value value2"><table style="width: 98%;" class="in-date">
                <tbody>
                  <tr>
                    <td><select style="width:100px;" id="query-smartPeriod" onchange="setPeriod(this.value)" name="smartPeriod" class="input in-select select">
<option value="custom">Custom</option>
<option selected="selected" value="curDay">Today</option>
<option value="curWeek">Current week</option>
<option value="curMonth">Current month</option>
</select></td>
                    <td><input type="text" style="width:80px;" name="start_date" value="<?php echo $start_date ?>" onkeydown="setPeriod('custom')" readonly="readonly" onchange="setPeriod('custom')" class="in-text input in-input" id="query-start_date-wDt">
                      &nbsp;
                      <input type="text" style="width:80px;" class="input in-text in-input" name="start_time" value="<?php echo $start_time ?>" readonly="readonly" onkeydown="setPeriod('custom')" onchange="setPeriod('custom')" id="query-start_time-wDt"></td>
                    <td>&mdash;</td>
                    <td><input type="text" style="width:80px;" name="stop_date" value="<?php echo $end_date ?>" onkeydown="setPeriod('custom')" readonly="readonly" onchange="setPeriod('custom')" class="in-text input in-input" id="query-stop_date-wDt">
                      &nbsp;
                      <input type="text" style="width:80px;" class="input in-text in-input" name="stop_time" value="<?php echo $end_time ?>" onkeydown="setPeriod('custom')" readonly="readonly" onchange="setPeriod('custom')" id="query-stop_time-wDt"></td>
                    <td>in</td>
                    <td><select style="width:100px;" class="input in-select select" name="gmt" id="query-tz">
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
                      <td>Operator</td>
                      <td>
                          <input type="text" name="operator" value="<?php echo $common->set_get_value('operator') ?>" style="width:120px;" />
                      </td>
                      <td>Target</td>
                      <td>
                          <input type="text" name="target" value="<?php echo $common->set_get_value('target') ?>" style="width:120px;" />
                      </td>
                      <td>Action</td>
                      <td>
                          <select name="action">
                              <option value="all"  <?php echo $common->set_get_select('action', 'all', TRUE); ?>>All</option>
                              <option value="0" <?php echo $common->set_get_select('action', '0'); ?>>Creation</option>
                              <option value="1" <?php echo $common->set_get_select('action', '1'); ?>>Deletion</option>
                              <option value="2" <?php echo $common->set_get_select('action', '2'); ?>>Modification</option>
                          </select>
                      </td>
                  </tr>
                </tbody>
              </table></td>
          </tr>
          </tbody>
        <tfoot>
          <tr>
            <td colspan="8"><input type="submit" value="Submit" class="input in-submit"></td>
          </tr>
        </tfoot>
      </table>
    </form>
  </fieldset>
    
</div>

<script>
$(function() {
    
    $('#query-tz option[value="<?php echo $tz ?>"]').attr('selected', true);
    
});
</script>


