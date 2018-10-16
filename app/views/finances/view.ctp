<?php $action_type=empty($_GET['action_type'])?'2':$_GET['action_type'];?>
<div id="title">
  <h1>
   <?php  __('Finance')?>
    &gt;&gt;
    <?php __('Finance')?>
  </h1>
  <ul id="title-search">
       <form   id="like_form"  action=""  method="get">
    <li>
                    <span style="float:left; height:25px; line-height:25px; font-size:14px; color:#EEEEEE; font-weight:bold;">Output:</span>
                    <select onchange="set_out_put1(this)" name="out_put" style="width:100px;" class="in-select select" id="output">
                        <option value="web">Web</option>
                        <option value="csv">Excel CSV</option>
                        <option value="xls">Excel XLS</option>
                    </select>
    </li>
    <li>
      <?php //********************模糊搜索**************************?>
     
        <input type="text" id="search-_q" class="in-search default-value input in-text defaultText" title="<?php echo __('namesearch')?>" value="<?php if (!empty($search)) echo $search;?>" name="search">
        
        <input type="submit" name="submit" value="" class="search_submit"/>
        <input type="hidden" name="action_type" value="<?php echo $action_type;?>" />
    </li>
    </form>
    <li title="<?php echo __('advancedsearch')?> »" onClick="advSearchToggle();" id="title-search-adv" style="display: list-item;"></li>
  </ul>
  <ul id="title-menu">
    <?php if (isset($extraSearch)) {?>
    <li> <a class="link_back" href="<?php echo $extraSearch?>" onClick="history.go(-1)"> <img width="16" height="16"  alt="" src="<?php echo $this->webroot?>images/icon_back_white.png"> &nbsp;<?php echo __('goback')?> </a> </li>
    <?php }?>
  </ul>
  <?php  
	$action=isset($_SESSION['sst_statis_smslog'])?$_SESSION['sst_statis_smslog']:'';
 $w=isset($action['writable'])?$action['writable']:'';
 ?>
</div>
<div id="container">

  <?php //*********************  条件********************************?>
  <fieldset class="title-block" id="advsearch"  style="width: 98%;display:block;">
    <form action="" method="get">
     <div style="display:none;">
                    <span style="float:left; height:25px; line-height:25px; font-size:14px; color:#EEEEEE; font-weight:bold;">Output:</span>
                    <select name="out_put" style="width:100px;" class="in-select select" id="output1">
                        <option value="web">Web</option>
                        <option value="csv">Excel CSV</option>
                        <option value="xls">Excel XLS</option>
                    </select>
     </div>
      <input name="advsearch" type="hidden"/>
      <input name="action_type" type="hidden" value="<?php echo $action_type?>"/>
      <table style="width:100%">
        <tbody>
          <tr>
            
            <td><label><?php echo __('Transaction Date',true);?> :</label>
              <input type="text" readonly onFocus="WdatePicker({dateFmt:'yyyy-MM-dd'});" id="start_date" style="width:120px;" name="start_date" class="input in-text wdate" value="<?php echo !empty($_REQUEST['start_date']) ? $_REQUEST['start_date'] : ''; ?>">
              --
              <input type="text" readonly onFocus="WdatePicker({dateFmt:'yyyy-MM-dd'});" id="end_date" style="width:120px;"  name="end_date" class="wdate input in-text" value="<?php echo !empty($_REQUEST['end_date']) ? $_REQUEST['end_date'] : ''; ?>"></td>
            
            <!--
            <td><label><?php echo __('trans_type')?></label>
              <select id="tran_type" name="tran_type">
                <option value=""><?php echo __('select')?></option>
                <option value="2" <?php echo (!empty($_REQUEST['tran_type']) && $_REQUEST['tran_type'] == 2) ? 'selected' : '';?>><?php echo __('Wire In',true);?></option>
                <option value="1" <?php echo (!empty($_REQUEST['tran_type']) && $_REQUEST['tran_type'] == 1) ? 'selected' : '';?>><?php echo __('Wire Out',true);?></option>
              </select></td>
              -->
              <td><label><?php echo __('status',true);?>:</label>
              <select id="tran_status" name="tran_status">
                <option value=""><?php echo __('select')?></option>
                <option value="1" <?php echo (!empty($_REQUEST['tran_status']) && $_REQUEST['tran_status'] == 1) ? 'selected' : '';?>>Waiting</option>
                <option value="2" <?php echo (!empty($_REQUEST['tran_status']) && $_REQUEST['tran_status'] == 2) ? 'selected' : '';?>>Completed</option>
             			<?php if ($action_type == 1):?>
                <option value="4" <?php echo (isset($_REQUEST['tran_status']) && $_REQUEST['tran_status']  == 4) ? 'selected' : '';?>>In Process</option>
                <option value="3" <?php echo (!empty($_REQUEST['tran_status']) && $_REQUEST['tran_status'] == 3) ? 'selected' : '';?>>Refused</option>
                <?php endif;?>
              </select></td>
            <td><input type="submit" value="<?php echo __('submit')?>" class="input in-submit"></td>
          </tr>
        </tbody>
      </table>
    </form>
  </fieldset>
  <ul class="tabs">
       <li <?php if($action_type==2) echo "class='active'";?>><a href="<?php echo $this->webroot?>finances/view?action_type=2"><img width="16" height="16" src="<?php echo $this->webroot?>images/import.png"><?php echo __('Wire In',true);?> </a></li> 
       <li <?php if($action_type==1) echo "class='active'";?> ><a href="<?php echo $this->webroot?>finances/view?action_type=1">
       <img width="16" height="16" src="<?php echo $this->webroot?>images/export.png"><?php echo __('Wire Out',true);?></a></li>

       </ul>
 <!-- <div id="toppage"></div>-->
 <?php 			$mydata =$p->getDataArray();
			$loop = count($mydata); 
			if(empty($mydata)){
			?>
<div class="msg">No data found.</div>
<?php }else{

?>
  <table class="list">
      <thead>
        <tr>
          <td><?php echo __('Serail Number',true);?></td>
          <td><?php echo $appCommon->show_order('action_type', __('Type',true));?></td>
          <td><?php echo $appCommon->show_order('action_method', __('Method',true));?></td>
          <td><?php echo $appCommon->show_order('amount', __('Amount',true));?></td>
          <td><?php echo $appCommon->show_order('action_fee', __('Fee',true));?></td>
          <td><?php echo $appCommon->show_order('action_time', __('Transaction Date',true));?></td>
          <td><?php echo $appCommon->show_order('status', __('Status',true));?></td>
          <td><?php echo $appCommon->show_order('complete_time', __('Completed Date',true));?></td>
          <td><?php echo $appCommon->show_order('name', __('Carrier',true));?></td>
          <?php if ($action_type == 2): ?>
          <td><?php echo $appCommon->show_order('payer_company', __('Company',true));?></td>
          <td><?php echo $appCommon->show_order('payer_email', __('Email',true));?></td>
          <?php endif; ?>
         <?php  if ($_SESSION['role_menu']['Finance']['finances']['model_w']) {?> <td><?php __('action')?></td><?php }?>
        </tr>
      </thead>
      <tbody>
      <?php 
      for ($i=0;$i<$loop;$i++){
      	$status_val = array(0=>'Confirmed', 1=>'Waiting', 2=>'<font style="color:#FF6D06;">Complete</font>', 3=>'<font style="color:#FF0000;">Refused</font>',4=>'<font style="color:#FF0000;">In Process</font>');
      			?>
        <tr>
          <td><?php echo $mydata[$i][0]['action_number']; ?> </td>
          <td>
          	<?php echo ($mydata[$i][0]['action_type'] == 1) ? 'Wire Out' : 'Wire In' ; ?>
          </td>
          <td><?php echo ($mydata[$i][0]['action_method'] == 1) ? 'Bank Wire' : 'Paypal' ; ?></td>
          <td>$<?php echo number_format($mydata[$i][0]['amount'], 2);?></td>
          <td>$<?php echo number_format($mydata[$i][0]['action_fee'], 2);?></td>
          <td><?php echo $mydata[$i][0]['action_time'];?></td>
          <td><?php echo $status_val[$mydata[$i][0]['status']]; ?></td>
          <td><?php echo $mydata[$i][0]['complete_time']; ?></td>
          <td><?php echo $mydata[$i][0]['name']; ?></td>
          <?php if ($action_type == 2): ?>
           <td><?php echo $mydata[$i][0]['payer_company']; ?></td>
           <td><?php echo $mydata[$i][0]['payer_email']; ?></td>
          <?php endif; ?>
          <?php  if ($_SESSION['role_menu']['Finance']['finances']['model_w']) {
          ?><td>
              
          	<?php if (($mydata[$i][0]['action_type']==1 && $mydata[$i][0]['status'] < 3) || $mydata[$i][0]['action_type']==2 || ($mydata[$i][0]['action_type']==1 && $mydata[$i][0]['status'] == 4)){?>
                    <a href="<?php echo $this->webroot; ?>finances/notify_carrier/<?php echo $mydata[$i][0]['id']; ?>">
                            <img width="16" height="16" src="<?php echo $this->webroot; ?>images/email.gif">
                    </a>
                
                <a href="<?php echo $this->webroot?>finances/edit_finance/<?php echo $mydata[$i][0]['id']; ?>"; 
        		title="<?php echo __('edit')?>"> <img src="<?php echo $this->webroot?>images/editicon.gif"> </a>
        		<?php }?>
        		</td>
                <?php 
          					}?>
        </tr>
        <?php }?>
      </tbody>
    </table>
  <div id="tmppage"> 
  <?php echo $this->element('page');?>
  </div>
  <?php } ?>
</div>

<script>
    function set_out_put1(obj){
        $("#output1").attr('value',$(obj).val());
    }
    
</script>