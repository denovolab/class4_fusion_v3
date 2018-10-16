<style type="text/css">
.smallbox {
    background:#fff;
    display:none;
    position:absolute;
    right:20px;
    padding:20px;
    border:1px solid #ccc;
    text-align:left;
    font-weight:bold;
}
</style>
<div id="title">
  <h1><?php echo __('Rate',true);?></h1>
  <ul id="title-search">
      <li>
        <form action="" method="get">
             <input type="text" id="search-_q" class="in-search default-value input in-text defaultText in-input" title="Search..." value="" onclick="this.value=''" name="code">
        </form>
      </li>
   <li title="Advanced Search »" onclick="advSearchToggle();" id="title-search-adv" style="display: list-item; "></li>
  </ul>
</div>

<div id="container">
    <form method="get" name="carriersearch">
    <p>
        <button name="getcsv" class="input in-submit" style="width:100px;" value="Export CSV"><?php echo __('Export CSV',true);?></button>
    </p>
    <fieldset style="width: 100%; margin: 0pt 0pt 10px; display:none;" id="advsearch" class="title-block">
    
      <input type="hidden" value="1" name="adv_search">
      <table style="width:auto;">
        <tbody>
          <tr>
            <td style="width:250px">
                <label><?php echo __('rate',true);?>:</label>
                <input type="text" style="width:80px;" name="rate_begin" class="input in-input in-text">
                -
                <input type="text" style="width:80px;" name="rate_end" class="input in-input in-text">
            </td>
            <td>
                <label><?php echo __('country',true);?>:</label>
                <input type="text" style="width:80px;" name="country" class="input in-input in-text">
            </td>
            <td>
                <label><?php echo __('code_name',true);?>:</label>
                <input type="text" style="width:80px;" name="code_name" class="input in-input in-text">
            </td>
            <td>
                <label><?php echo __('Time',true);?>:</label>
                <select name="time" class="select in-select">
                    <option value="current">current on</option>
                    <option value="new">future for</option>
                    <option value="old">old for</option>
                    <option selected="selected" value="all">all</option>
                    <option value="in">in</option>
                </select>		  	
            </td>
            <td>
                <input type="text" name="time_val" 
                      onfocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'});" value="<?php echo date("Y-m-d H:i:s") ?>"   class="input in-text wdate" readonly="readonly"
                       id="search-now-wDt">
            </td>
            <td>
                <label><?php echo __('Profile',true);?>:</label>
                <select name="profile" class="select in-select">
                    <option value=""></option>
                    <?php foreach($profiles as $profile): ?>
                    <option value="<?php echo $profile[0]['time_profile_id'] ?>"><?php echo $profile[0]['name'] ?></option>
                    <?php endforeach; ?>
                </select>
            </td>
            <td class="buttons"><input type="submit" class="input in-submit" value="Submit"></td>
          </tr>
        </tbody>
     </table>
    </fieldset>

    </form>
    <br />
    <ul class="tabs">
        <li class="active"><a href="<?php echo $this->webroot ?>clientrates/view_rate_egress"> <img alt="" src="<?php echo $this->webroot ?>images/menuIcon.gif">Egress Rate</a></li> 
        <li><a href="<?php echo $this->webroot ?>clientrates/view_rate_ingress"><img alt="" src="<?php echo $this->webroot ?>images/menuIcon.gif">Ingress Rate</a></li> 
    </ul>
    <?php
        if(!empty($p)):
            $rates = $p->getDataArray();
    ?>
    <table id="mytable" class="list">
        <thead>
            <tr>
                <td><?php echo __('code',true);?></td>
                <td><?php echo __('code_name',true);?></td>
                <td><?php echo __('country',true);?></td>
                <td><?php echo __('Rate',true);?></td>
                <td><?php echo __('Intra Rate',true);?></td>
                <td><?php echo __('Inter Rate',true);?></td>
                <td><?php echo __('Setup Fee',true);?></td>
                <td><?php echo __('Effective Date',true);?></td>
                <td><?php echo __('End Date',true);?></td>
                <td><?php echo __('Extra Fields',true);?></td>
            <tr>
        </thead>

        <tbody>
            <?php foreach($rates as $rate): ?>
            <tr>
                <td><?php echo $rate[0]['code'] ?></td>
                <td><?php echo $rate[0]['code_name'] ?></td>
                <td><?php echo $rate[0]['country'] ?></td>
                <td><?php echo $rate[0]['rate'] ?></td>
                <td><?php echo $rate[0]['intra_rate'] ?></td>
                <td><?php echo $rate[0]['inter_rate'] ?></td>
                <td><?php echo $rate[0]['setup_fee'] ?></td>
                <td><?php echo $rate[0]['effective_date'] ?></td>
                <td><?php echo $rate[0]['end_date'] ?></td>
                <td>
                    <a class="showother" href="###">
                         <?php echo $rate[0]['min_time'] ?>/<?php echo $rate[0]['interval'] ?>/<?php echo $rate[0]['grace_time'] ?>/<?php echo $rate[0]['time_profile'] ?>
                    </a>
                    <ul class="smallbox">
                        <li><?php echo __('Min Time',true);?>:<?php echo $rate[0]['min_time'] ?></li>
                        <li><?php echo __('Interval',true);?>:<?php echo $rate[0]['interval'] ?></li>
                        <li><?php echo __('Grace Time',true);?>:<?php echo $rate[0]['grace_time'] ?></li>
                        <li><?php echo __('Seconds',true);?>:<?php echo $rate[0]['seconds'] ?></li>
                        <li><?php echo __('Profile',true);?>:<?php echo $rate[0]['time_profile'] ?></li>
                        <li><?php echo __('Time Zone',true);?>:<?php echo empty($rate[0]['zone']) ? '+00' : $rate[0]['zone'] ?></li>
                    </ul>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div id="tmppage">
        <?php echo $this->element('page');?>
    </div>  
    <?php else: ?>
        <h1 style="text-align:center"><?php echo __('no_data_found',true);?></h1>
    <?php  endif; ?>
</div>

<script type="text/javascript">
jQuery(function($) {
    $('.showother').toggle(function() {
        $(this).next().show();
    }, function() {
        $(this).next().hide();
    });
});
</script>