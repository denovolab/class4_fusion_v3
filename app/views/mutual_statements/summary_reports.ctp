<!--导入所有reoprt页面的input和select样式文件-->
<?php echo $this->element('magic_css_three');?>
<div id="title">
  <h1>
   <?php  __('Finance')?>
    &gt;&gt;
    <?php __('Mutual Statements')?>
  </h1>
  <ul id="title-search">
    <li>
      <?php //********************模糊搜索**************************?>
      <form   id="like_form"  action=""  method="get">
          <select name="overdue">
              <option value="0" <?php echo !isset($_GET['overdue']) || $_GET['overdue'] == 0 ? 'selected="selected"' : ''; ?>>Overdue > 0</option>
              <option value="1" <?php echo isset($_GET['overdue']) && $_GET['overdue'] == 1 ? 'selected="selected"' : ''; ?>>No Overdue</option>
              <option value="2" <?php echo isset($_GET['overdue']) && $_GET['overdue'] == 2 ? 'selected="selected"' : ''; ?>>All</option>
          </select>
        <input type="text" id="search-_q" class="in-search default-value input in-text defaultText" title="<?php echo __('namesearch')?>" value="<?php if (!empty($search)) echo $search;?>" name="search">
        <input type="submit" name="submit" value="" class="search_submit"/>
      </form>
    </li>
    <li title="<?php echo __('advancedsearch')?> »" onClick="advSearchToggle();" id="title-search-adv" style="display: list-item;"></li>
  </ul>
  <ul id="title-menu">
    <?php if (isset($extraSearch)) {?>
    <li> <a class="link_back" href="<?php echo $extraSearch?>" onClick="history.go(-1)"> <img width="16" height="16"  alt="" src="<?php echo $this->webroot?>images/icon_back_white.png"> &nbsp;<?php echo __('goback')?> </a> </li>
    <?php }?>
  </ul>

</div>
<div id="container">
  <?php //*********************  条件********************************?>
  <fieldset class="title-block" id="advsearch"  style="width: 98%;display:block;">
    <form action="" method="get">
      <input name="advsearch" type="hidden"/>
      <table style="width:100%">
        <tbody>
        </tbody>
      </table>
    </form>
  </fieldset>
 <!-- <div id="toppage"></div>-->
 <?php 			$mydata =$p->getDataArray();
			$loop = count($mydata); 
			if(empty($mydata)){
			?>
<div class="msg"><?php echo __('no_data_found',true);?></div>
<?php }else{

?>






 <table class="list">
      <thead>
          
        <tr>
            <td ><?php echo $appCommon->show_order('name', __('Client Name',true));?></td>
         
            <td ><?php echo $appCommon->show_order('name', __('Balance',true));?></td>
            <td colspan="5"><?php echo __('Incoming Overdue',true);?></td>
            <td colspan="5"><?php echo __('Outgoing Overdue',true);?></td>
           
            
            <td ><?php echo __('Detail',true);?></td>
        </tr>

        <tr>
          <td></td>
          <td></td>
          <td><?php echo __('Total',true);?></td>  
          <td><?php echo __('7 days overdue',true);?></td>
          <td><?php echo __('15 days overdue',true);?></td>
          <td><?php echo __('30 days overdue',true);?></td>
          <td><?php echo __('> 30 days overdue',true);?></td>
          
          <td><?php echo __('Total',true);?></td>  
          <td><?php echo __('7 days overdue',true);?></td>
          <td><?php echo __('15 days overdue',true);?></td>
          <td><?php echo __('30 days overdue',true);?></td>
          <td><?php echo __('> 30 days overdue',true);?></td>
          <td></td>
          
        </tr>
      </thead>
      <tbody>
      <?php 
      for ($i=0;$i<$loop;$i++){
      
      			$payment_tmp = empty($mydata[$i][0]['last_payment']) ? array('', '') : explode('|', $mydata[$i][0]['last_payment']);
      			$invoice_tmp = empty($mydata[$i][0]['last_invoice']) ? array('', '') : explode('|', $mydata[$i][0]['last_invoice']);
      			?>
        <tr>
          <td><?php echo $mydata[$i][0]['name']; ?></td>
          <td><?php if ($mydata[$i][0]['balance'] < 0) printf("(%.3f) USD", abs($mydata[$i][0]['balance']) ); else printf("%.3f USD", $mydata[$i][0]['balance']); ?> </td>
          
          <td><?php if($mydata[$i][0]['incoming_overdue']==0){echo "<span style='color: #9F9F9F;'>0 USD</span>";}else{ printf("%.3f USD", $mydata[$i][0]['incoming_overdue']);} ?></td>
          <td><?php if($mydata[$i][0]['incoming_overdue_7']==0){echo "<span style='color: #9F9F9F;'>0 USD</span>";}else{ printf("%.3f USD", $mydata[$i][0]['incoming_overdue_7']);}?></td>
          <td><?php if($mydata[$i][0]['incoming_overdue_15']==0){echo "<span style='color: #9F9F9F;'>0 USD</span>";}else{printf("%.3f USD", $mydata[$i][0]['incoming_overdue_15']);}?></td>
          <td><?php if($mydata[$i][0]['incoming_overdue_30']==0){echo "<span style='color: #9F9F9F;'>0 USD</span>";}else{printf("%.3f USD", $mydata[$i][0]['incoming_overdue_30']);}?></td>
          <td><?php if($mydata[$i][0]['incoming_overdue_gt_30']==0){echo "<span style='color: #9F9F9F;'>0 USD</span>";}else{printf("%.3f USD", $mydata[$i][0]['incoming_overdue_gt_30']);}?></td>
          
          
          <td><?php if($mydata[$i][0]['outgoing_overdue']==0){echo "<span style='color: #9F9F9F;'>0 USD</span>";}else{printf("%.3f USD", $mydata[$i][0]['outgoing_overdue']); }?></td>
          <td><?php if($mydata[$i][0]['outgoing_overdue_7']==0){echo "<span style='color: #9F9F9F;'>0 USD</span>";}else{printf("%.3f USD", $mydata[$i][0]['outgoing_overdue_7']);}?></td>
          <td><?php if($mydata[$i][0]['outgoing_overdue_15']==0){echo "<span style='color: #9F9F9F;'>0 USD</span>";}else{printf("%.3f USD", $mydata[$i][0]['outgoing_overdue_15']);}?></td>
          <td><?php if($mydata[$i][0]['outgoing_overdue_30']==0){echo "<span style='color: #9F9F9F;'>0 USD</span>";}else{printf("%.3f USD", $mydata[$i][0]['outgoing_overdue_30']);}?></td>
          <td><?php if($mydata[$i][0]['outgoing_overdue_gt_30']==0){echo "<span style='color: #9F9F9F;'>0 USD</span>";}else{printf("%.3f USD", $mydata[$i][0]['outgoing_overdue_gt_30']);}?></td>
          <!--<td><a href="<?php echo $this->webroot;?>mutual_statements/detail_report/<?php echo $mydata[$i][0]['client_id'];?>">detail</a></td>-->
          <td><a href="<?php echo $this->webroot;?>finances/get_mutual_ingress_egress_detail/<?php echo $mydata[$i][0]['client_id'];?>">detail</a></td>
        </tr>
        <?php }?>
      </tbody>
    </table>














  <!--<table class="list">
      <thead>
        <tr>
         
          <td><?php echo $appCommon->show_order('name', __('Client Name',true));?></td>
          <td><?php echo __('Last payment',true);?></td>
          <td><?php echo __('Last Invoice',true);?></td>
          <td><?php echo $appCommon->show_order('name', __('Balance',true));?></td>
          <td><?php echo __('Overdue',true);?></td>
          <td><?php echo __('7 days overdue',true);?></td>
          <td><?php echo __('15 days overdue',true);?></td>
          <td><?php echo __('30 days overdue',true);?></td>
          <td><?php echo __('> 30 days overdue',true);?></td>
          <td><?php echo __('Detail',true);?></td>
        </tr>
      </thead>
      <tbody>
      <?php 
      for ($i=0;$i<$loop;$i++){
      			$payment_tmp = empty($mydata[$i][0]['last_payment']) ? array('', '') : explode('|', $mydata[$i][0]['last_payment']);
      			$invoice_tmp = empty($mydata[$i][0]['last_invoice']) ? array('', '') : explode('|', $mydata[$i][0]['last_invoice']);
      			?>
        <tr>
          <td><?php echo $mydata[$i][0]['name']; ?> </td>
          <td><?php if (empty($payment_tmp[0])) echo ''; else printf("%.3f USD", $payment_tmp[0]); echo "<br />", $payment_tmp[1]; ?> </td>
          <td><?php if (empty($invoice_tmp[0])) echo ''; else printf("%.3f USD", $invoice_tmp[0]); echo "<br />", $invoice_tmp[1]; ?> </td>
          <td><?php if ($mydata[$i][0]['balance'] < 0) printf("(%.3f) USD", abs($mydata[$i][0]['balance']) ); else printf("%.3f USD", $mydata[$i][0]['balance']); ?> </td>
          <td><?php printf("%.3f USD", $mydata[$i][0]['overdue']); ?></td>
          <td><?php printf("%.3f USD", $mydata[$i][0]['overdue_7']);?></td>
          <td><?php printf("%.3f USD", $mydata[$i][0]['overdue_15']);?></td>
          <td><?php printf("%.3f USD", $mydata[$i][0]['overdue_30']);?></td>
          <td><?php printf("%.3f USD", $mydata[$i][0]['overdue_gt_30']);?></td>
          <td><a href="<?php echo $this->webroot;?>mutual_statements/detail_report/<?php echo $mydata[$i][0]['client_id'];?>">detail</a></td>
        </tr>
        <?php }?>
      </tbody>
    </table>-->
  <div id="tmppage"> 
  <?php echo $this->element('page');?>
  </div>
  <?php } ?>
</div>