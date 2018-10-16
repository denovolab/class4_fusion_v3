
<div id="title">
  <h1>
  <?php __('Statistics')?>
  &gt;&gt;<?php echo __('LCR Report',true);?>
  </h1>
      <ul id="title-menu">
        <li>
            <a class="link_back" href="<?php echo $this->webroot ?>lcrreports">
                <img width="10" height="5" src="<?php echo $this->webroot ?>images/icon_back_white.png" alt="">Back            </a>
        </li>
    </ul>
</div>
<div class="container">
  <form action="<?php echo $this->webroot ?>lcrreports/do_add"  method="post" name="myform" id="myform">
    <div class="block">
      <ul>
          <!--
        <li><span class="block_label">Choose:</span><span class="block_value">
          <select name="choose" class="select in-select">
            <option value="0">Sort Rate</option>
            <option value="1">Generate Rate</option>
          </select>
          </span></li>-->
        <li><span class="block_label"><?php echo __('type',true);?>:</span><span class="block_value">
          <select name="type" class="select in-select">
            <option value="intra_rate">intra_rate</option>
            <option value="inter_rate">inter_rate</option>
            <option value="rate">rate</option>
          </select>
          </span></li>
        <li id="only2">
        <span class="block_label"><?php echo __('No.',true);?>:</span><span class="block_value">
          <input type="text" name="no" class="input in-text in-input"/>
          </span>
          <span class="block_label">&nbsp;</span>
          <span class="block_label"><?php echo __('The percentage of profit',true);?>:</span><span class="block_value">
          <input type="text" name="profit" class="input in-text in-input" />
          %</span></li>
      </ul>
</div>
<div class="rate_table">
 	<div class="block">
      <label style="font-weight:bold;; font-size:14px;"><?php echo __('Rate Table',true);?>:</label>
     
        <?php foreach($ratetables as $ratetable): ?>
        <div class="chkboxgroup">
          <input type="checkbox" name="rate_table[]" value="<?php echo $ratetable[0]['rate_table_id']; ?>" />
          <span><?php echo $ratetable[0]['name'] ?></span> </div>
        <?php endforeach; ?>
        <br class="clear" />
      </div>
</div>
    <div id="form_footer">
      <input type="submit" value="<?php echo __('submit',true);?>" class="input in-submit" />
      &nbsp;
      <input type="reset" value="<?php echo __('reset',true);?>" class="input in-submit" />
    </div>
  </form>
</div>
<script type="text/javascript">
$(function() {
    $('select[name=choose]').change(function() {
        if($(this).val() == '1') {
            $('#only2').show();
        } else {
            $('#only2').hide();
        }
    });
});
</script>