<!--导入所有reoprt页面的input和select样式文件-->
<?php echo $this->element('magic_css');?>
<div id="title">
  <h1>
    <?php __('Detail Summary Report')?>
    &gt;&gt;
    <?php __('OrigReport')?>
  </h1>
</div>
<div id="container">
	<ul class="tabs">
	  <li><a href="<?php echo $this->webroot?>usagedetails/orig_summary_reports"><img width="16" height="16" src="<?php echo $this->webroot?>images/list.png">Origination</a></li>
	  <li><a href="<?php echo $this->webroot?>usagedetails/term_summary_reports"> <img width="16" height="16" src="<?php echo $this->webroot?>images/menuIcon.gif">Termination</a>  </li>
		 <li><a href="<?php echo $this->webroot?>usagedetails/daily_orig_summary"><img width="16" height="16" src="<?php echo $this->webroot?>images/list.png">Daily_Origination</a></li>
	  <li class='active'><a href="<?php echo $this->webroot?>usagedetails/daily_term_summary"> <img width="16" height="16" src="<?php echo $this->webroot?>images/menuIcon.gif">Daily_Termination</a>  </li>
	</ul>
    <?php if($show_nodata): ?>
<?php echo $this->element('report/real_period')?>
<?php endif ; ?>
<!-- ****************************************普通输出******************************************* -->
<div class="table_container">
  <?php if(empty($data)): ?>
     <?php if($show_nodata): ?>
  <div class="msg">There were no data found for report</div>
  <?php endif; ?>
  <?php else: ?>
 <?php
    $days = array();
    $startdate=strtotime($start);
    $enddate=strtotime($end); 
    $day=round(($enddate-$startdate)/3600/24) ;
    $dt_begin = new DateTime($start);
    for($i=0;$i<$day;$i++) {
        if($i > 0) {
            $dt_begin->modify('+1 days');
        }
        array_push($days, $dt_begin->format('Y-m-d'));
    }
 ?>
  <table class="list">
      <thead>
          <tr>
              <td><?php __('Client Name'); ?></td>
              <?php foreach($days as $item): ?>
              <td><?php echo $item; ?></td>
              <?php endforeach; ?>
              <td><?php echo __('Total(Min)') ?></td>
          </tr>
      </thead>
      <tbody>
          <?php foreach($data as $item): ?>
          <?php
            $item_total = 0;
          ?>
          <tr>
              <td><?php echo $item[array_rand($item)]['client_name'] ?></td>
              
              <?php
                foreach($days as $day_item) {
                  if(array_key_exists($day_item, $item))
                  {
                    echo '<td>' . round($item[$day_item]['total_time'] / 60, 2) . '</td>';
                    $item_total += $item[$day_item]['total_time'] ;
                  } else {
                    echo '<td>0</td>';
                  }
                }
              ?>
              
              <td><?php echo round($item_total / 60, 2); ?></td>
          </tr>
          <?php endforeach; ?>
          <tr>
              <td><?php __('Total(Min)') ?></td>
              <?php $total_item_total = 0; ?>
              <?php foreach($days as $day_item) : ?>
              <td>
                  <?php
                  $total_item = 0;
                  foreach($data as $item):
                      if(array_key_exists($day_item, $item)) {
                        $total_item += $item[$day_item]['total_time'];
                      } 
                  endforeach; 
                  $total_item_total += $total_item;
                  echo round($total_item / 60, 2);
                  ?>
              </td>
              <?php endforeach;?>
              <td>
                  <?php echo round($total_item_total / 60, 2); ?>
              </td>
          </tr>
      </tbody>
  </table>
    
  <?php endif; ?>
    
 <fieldset class="query-box" style=" clear:both;overflow:hidden;margin-top:10px;">
  <div class="search_title"><img src="<?php
echo $this->webroot?>images/search_title_icon.png" />
    <?php __('search')?>
  </div>
  <?php echo $this->element('search_report/search_js');?><?php echo $form->create ('Cdr', array ('type'=>'get','url' => '/usagedetails/daily_term_summary/' ,'onsubmit'=>"if ($('#query-output').val() == 'web') loading();"));?>  <?php echo $this->element('search_report/search_hide_input');?>

  <table class="form" style="width:100%">
    <tbody>
      <?php echo $this->element('report/form_period',array('group_time'=>true, 'gettype'=>'<select id="query-output"  name="show_type" class="input in-select">
            <option value="0">Web</option>
            <option value="1">CSV</option>
            <option value="2">XLS</option>
          </select>'))?>
      <!--
      <tr>
      	<td class="label"><?php echo __('asr',true);?>:</td>
        <td class="value">
            <input type="text" id="query-asr_from"
                    class="in-digits input in-text" style="width: 65px;" value="<?php echo !empty($_GET['query']['asr_from'])?$_GET['query']['asr_from']:'';?>"
                    name="query[asr_from]">
              &mdash;
              <input type="text"
                    id="query-asr_to" class="in-digits input in-text"
                    style="width: 65px;" value="<?php echo !empty($_GET['query']['asr_to'])?$_GET['query']['asr_to']:'';?>" name="query[asr_to]">
             &nbsp;(%)&nbsp;       
            <?php echo $this->element('search_report/ss_clear_input_select');?>
        </td>
         
         <td class="label"><?php echo __('acd',true);?>:</td>
        <td class="value">
            <input type="text" id="query-acd_from"
                    class="in-digits input in-text" style="width: 65px;" value="<?php echo !empty($_GET['query']['acd_from'])?$_GET['query']['acd_from']:'';?>"
                    name="query[acd_from]">
              &mdash;
              <input type="text"
                    id="query-acd_to" class="in-digits input in-text"
                    style="width: 65px;" value="<?php echo !empty($_GET['query']['acd_to'])?$_GET['query']['acd_to']:'';?>" name="query[acd_to]">
              &nbsp;(s)&nbsp;
            <?php echo $this->element('search_report/ss_clear_input_select');?>
        </td>      
                
        <td class="label" align="right"></td>
        <td class="value" align="left"></td>

        
        </tr>
        -->
     
    </tbody>
  </table>
</fieldset>

  <?php echo $this->element('search_report/search_js_show');?> 
 </div>
</div>

<script type="text/javascript">
$(function() {
    $('table.list tbody tr').each(function() {
        var $this = $(this);
        var count = 0;
        $('td:gt(0)', $this).each(function() {
            count += parseFloat($(this).text());
        });
        
        
        if (count == 0)
            $this.remove();
    });    
});
</script>