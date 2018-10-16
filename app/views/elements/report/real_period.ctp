<ul id="stats-extra"
	style="font-weight: bolder; font-size: 1.1em; color: #6694E3; visibility: visible; height: 18px;">
	<li id="stats-period" style="position: relative; visibility: visible;">
	<span rel="helptip" class="helptip" id="ht-100012"><?php __('RealPeriod')?></span>
	<span><?php  echo $start;?></span> &mdash; <span><?php echo $end?></span>
	<span style="width: 20px;">&nbsp;&nbsp;&nbsp;&nbsp;</span></li>
	<li id="stats-time"><?php __('QueryTime')?>: <?php echo $quey_time?> ms</li>
	<input type="hidden" name="currentdate" id="currentdate" value="<?php  echo $start;?>" />
</ul>
