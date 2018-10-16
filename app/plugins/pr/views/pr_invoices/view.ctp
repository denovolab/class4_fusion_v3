<?php $w = $session->read('writable');?>

<?php
    if(!isset($this->params['pass'][0])) {
        $this->params['pass'][0] = 0;
    }

     $class_str = "";
          $login_type = $_SESSION ['login_type'];
          if($login_type == 3){
              $class_str = "display:none;";
          }
?>

<div id="cover"></div>
<div id="cover_tmp"></div>
<div id="title">
  <h1>
    <?php  __('Finance')?>
    &gt;&gt;
    <?php __('Invoices')?>
  </h1>
  <ul id="title-search">
    <li>
      <form   id="like_form"  action=""  method="get">
        <input type="text" id="search-_q" class="in-search default-value input in-text defaultText" title="<?php echo __('namesearch')?>" value="<?php if (!empty($search)) echo $search;?>" name="search">
        <input type="submit" name="submit" value="" class="search_submit"/>
      </form>
    </li>
    <li title="advanced search »" onclick="advSearchToggle();" id="title-search-adv" style="display: list-item;"></li>
  </ul>
  <ul id="title-menu">
  <!--<li> <a class="list-export"  onclick=" document.getElementById('download_invoice_form').action='<?php echo $this->webroot?>invoices/download_rate';document.getElementById('download_invoice_form').submit();" href="javascript:void(0)">&nbsp;
      <?php __('download')?>
      </a></li>
  -->
  <li style="<?php echo $class_str;?>"  >
            <a href="<?php echo $this->webroot ?>pr/pr_invoices/invoice_log" id="refresh_btn" class="link_btn">
                <img width="10" height="5"  src="<?php echo $this->webroot ?>images/log.png"><?php echo __('Invoice Log',true);?>
            </a>
        </li>
    <li>
        <a class="list-export" id="export_excel_btn" style="cursor:pointer;display:block;">
            &nbsp;<?php __('Export'); ?>
        </a>
    </li>
    <?php  if (isset($_SESSION['role_menu']['Finance']['pr_invoices']['model_w']) && $_SESSION['role_menu']['Finance']['pr_invoices']['model_w']) {?>
	<?php if ($w == true) {if($create_type=='1' || $create_type=='3' || $create_type=='5'){?>
    <li> <a class="link_btn" href="<?php echo $this->webroot?>pr/pr_invoices/add/<?php echo $create_type; ?>"> <img width="16" height="16" alt="" src="<?php echo $this->webroot?>images/add.png">Create New </a> </li>
    <?php }}}?>
    
  </ul>
</div>
<div id="container">
  <fieldset style="margin-left: 1px; width: 100%; display: <?php  echo isset($url_get['advsearch']) ? 'block' :'none';?>;" id="advsearch" class="title-block">
    <form method="get" action="" id="search_panel">
      <input type="hidden" name="advsearch" class="input in-hidden">
      <input type="hidden" id="is_export" name="is_export" value="0">
      <table style="width:100%">
        <tr>
          <td style="width:5%;text-align: right;"><?php echo __('Invoice No',true);?>:</td>
          <td style="text-align: left;"><input type="text" class="input in-text" style="width:120px;"name="invoice_number" value=""  id="invoice_number"></td>
          <td style="width:5%;text-align: right;"><?php echo __('status',true);?>:</td>
          <td style="text-align: left;"><select id="state" name="state" class="input in-select">
              <option selected="selected" value="">all</option>
              <option value="0">normal</option>
              <option value="1">to send</option>
              <option value="2">to verify</option>
              <option value="3">send failure</option>
              <option value="4">re-generate</option>
              <option value="9">send</option>
              <option value="-1">void</option>
            </select></td>
          <td style="width:5%;text-align: right;"><?php echo __('type',true);?>:</td>
          <td style="text-align: left;"><select id="type" onchange="updateDirection()" name="type" class="input in-select">
              <option value="" selected="selected">all</option>
              <option value="0">Client</option>
              <option value="1">Vendor</option>
              <option value="2">Vendor and Client</option>
            </select></td>
          <td style="width:5%;text-align: right;"><?php echo __('Number of Day Overdue',true);?>:</td>
          <td style="text-align: left;"><select id="due_inteval_type" name="due_inteval_type" style="width:80px" class="input in-select">
              <option value="">all</option>
              <option value=">=">&gt;=</option>
              <option value="<=">&lt;= </option>
            </select>
            <input type="text" class="input in-text" name="due_inteval" value="" style="width: 60px;" id="due_inteval">
            <script type="text/javascript">
    			jQuery('#due_inteval_type').change(function(){
        		if(jQuery(this).val()==''){
            		jQuery('#due_inteval').hide();
        		}else{
            		jQuery('#due_inteval').show();
        					}
					}).change();
    			</script></td>
          
          <td style="width:5%;text-align: right;"><?php echo __('Mode',true);?>:</td>
          <td style="text-align: left;">
        <select name="pay_mode">
            <option></option>
            <option value="1" <?php if (isset($_GET['pay_mode']) and $_GET['pay_mode'] == '1') echo 'selected="selected"' ?>>PrePaid</option>
            <option value="2" <?php if (isset($_GET['pay_mode']) and $_GET['pay_mode'] == '2') echo 'selected="selected"' ?>>Post-pay</option>
            
        </select>
          </td>
          <td>
              <input type="submit" class="input in-submit" value="Submit"></td>
        </tr>
        <tr>
          <td style="width:8%;text-align: right;"><?php echo __('Dispute',true);?></td>
          <td style="text-align: left;"><select id="disputed" name="disputed" class="input in-select">
              <option selected="selected" value="0">Non-Disputed</option>
              <option value="1">Disputed</option>
              <option value="2">Dispute Resolved</option>
            </select></td>
          <td style="width:5%;text-align: right;"><?php echo __('Carriers',true);?></td>
          <td style="text-align: left;">
              <select name="query[client]">
                    <option value="">All</option>
                    <?php foreach($clients as $client): ?>
                    <option <?php if(isset($_GET['query']['client']) && $_GET['query']['client'] == $client[0]['client_id']) echo 'selected="selected"'; ?> value="<?php echo $client[0]['client_id'] ?>"><?php echo $client[0]['name'] ?></option>
                    <?php endforeach; ?>
                </select>
              
           <!-- <img width="9" height="9" onclick="showClients()" class="img-button" src="<?php echo $this->webroot?>images/search-small.png"> 
           -->
          <td style="width:5%;text-align: right;"><?php echo __('Amt Paid',true);?></td>
          <td style="text-align: left;"><select id="paid" name="paid" class="input in-select">
              <option selected="selected" value="0">all</option>
              <option value="false">no paid</option>
              <option value="true">already paid</option>
            </select></td>
          <td style="width:8%;text-align:right;"><?php echo __('Invoice Date',true);?>:</td>
          <td style="text-align: left;"><input type="text" class="input in-text wdate" name="invoice_start" value="" style="width:60px;" id="start_date" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd'});" readonly="">
            --
            </td>
          <td><input type="text" class="wdate input in-text" name="invoice_end" value="" style="width: 60px;" id="end_date" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd'});" readonly=""></td>
          <td style="width:8%;text-align:right;"><?php echo __('Amout',true);?>:</td>
          <td style="text-align: left;"><select id="invoice_amount" name="invoice_amount" class="input in-select" style="width:100px;">
              <option <?php if (isset($_GET['invoice_amount']) && 0 == $_GET['invoice_amount'] ){?>selected="selected"<?php }?> value="0">All</option>
              <option <?php if (!isset($_GET['invoice_amount']) || !empty($_GET['invoice_amount']) ){?>selected="selected"<?php }?> value="1">Non-Zero</option>
            </select>
          <td></td>
        </tr>
      </table>
    </form>
  </fieldset>
  <?php if($_SESSION['login_type']=='1'){?>
  <ul class="tabs">
    <li <?php if($create_type=='0'){echo "class='active'";}?> ><a href="<?php echo $this->webroot?>pr/pr_invoices/view/0"><img width="16" height="16" src="<?php echo $this->webroot?>images/invoice1.png"><?php echo __('Auto Inbound Invoice')?></a></li>
    <li <?php if($create_type=='1'){echo "class='active'";}?> ><a href="<?php echo $this->webroot?>pr/pr_invoices/view/1"><img width="16" height="16" src="<?php echo $this->webroot?>images/invoice2.png"><?php echo __('Manual Inbound Invoice')?></a></li>
    <li <?php if($create_type=='2'){echo "class='active'";}?> ><a href="<?php echo $this->webroot?>pr/pr_invoices/view/2"><img width="16" height="16" src="<?php echo $this->webroot?>images/invoice3.png"><?php echo __('Auto Outbound Invoice')?></a></li>
    <li <?php if($create_type=='3'){echo "class='active'";}?> ><a href="<?php echo $this->webroot?>pr/pr_invoices/view/3"><img width="16" height="16" src="<?php echo $this->webroot?>images/invoice4.png"><?php echo __('Vendor Invoice')?></a></li>
<!--    <li <?php if($create_type=='4'){echo "class='active'";}?> ><a href="<?php echo $this->webroot?>pr/pr_invoices/view/4"><img width="16" height="16" src="<?php echo $this->webroot?>images/bDR.gif"><?php echo __('Sent And Received Auto-generated Invoice')?></a></li>-->
<!--    <li <?php if($create_type=='5'){echo "class='active'";}?> ><a href="<?php echo $this->webroot?>pr/pr_invoices/view/5"><img width="16" height="16" src="<?php echo $this->webroot?>images/bDR.gif"><?php echo __('Sent And Received Invoice')?></a></li>-->
    
    <!--<li <?php if($create_type=='1'){echo "class='active'";}?> ><a href="<?php echo $this->webroot?>pr/pr_invoices/view/1"  style="width: 125px;"><img width="16" height="16" src="<?php echo $this->webroot?>images/menuIcon.gif"> <?php echo __('Manual Invoice')?></a></li>
    -->
    <li><a href="<?php echo $this->webroot?>pr/pr_invoices/incoming_invoice"><img width="16" height="16" src="<?php echo $this->webroot?>images/invoice5.png"> <?php echo __('Incoming Outbound Invoice')?></a></li>
  </ul>
  <?php }?>
  <?php $d = $p->getDataArray();if (count($d) == 0) {?>
  <div class="msg"><?php echo __('no_data_found')?></div>
  <?php } else {?>
  <div id="toppage"></div>
  <script language="JavaScript" type="text/javascript">
	
			var _ss_ids_client = {'id_clients': 'query-id_clients', 'id_clients_name': 'query-id_clients_name', 'account': 'query-account', 'tz': 'query-tz', 'id_currencies': 'query-id_currencies'};
			function showClients ()
			{
			    ss_ids_custom['client'] = _ss_ids_client;
			    winOpen('<?php echo $this->webroot?>clients/ss_client?types=2&type=0', 500, 530);
			
			}
			function switchSelection()
			{
			    var t = $('#container .list thead :checkbox');
			    t.closest('table').find('tbody :checkbox')
			     .attr('checked', t.attr('checked'));
			}

			$('#paid').val('<?php  $paid=isset($_GET['paid'])?$_GET['paid']:''  ;echo $paid;?>');
			$('#invoice_number').val('<?php  $paid=isset($_GET['invoice_number'])?$_GET['invoice_number']:''  ;echo $paid;?>');
			$('#type').val('<?php  $paid=isset($_GET['type'])?$_GET['type']:''  ;echo $paid;?>');
			$('#start_date').val('<?php  $paid=isset($_GET['start_date'])?$_GET['start_date']:''  ;echo $paid;?>');
			$('#end_date').val('<?php  $paid=isset($_GET['end_date'])?$_GET['end_date']:''  ;echo $paid;?>');
			$('#state').val('<?php  $paid=isset($_GET['state'])?$_GET['state']:''  ;echo $paid;?>');
	</script>
  <form     id='download_invoice_form' method="post" action="<?php echo $this->webroot?>pr/pr_invoices/mass_update/<?php echo $create_type;?>">
    <fieldset>
      <table class="list">

        <thead>
          <tr>
            <?php  if (isset($_SESSION['role_menu']['Finance']['pr_invoices']['model_x']) && $_SESSION['role_menu']['Finance']['pr_invoices']['model_x']) {?>
            <td>
            <input type="checkbox" id="selector-1" onchange="switchSelection();" value="1" name="selector" class="input in-checkbox"></td>
            <?php }?>
            <td><?php echo $appCommon->show_order('invoice_number',__('Invoice No',true))?></td>
           <?php  if (isset($_SESSION['role_menu']['Finance']['pr_invoices']['model_x']) && $_SESSION['role_menu']['Finance']['pr_invoices']['model_x']) {?>
           <td>&nbsp;</td>
           <?php }?>
            <td><?php echo $appCommon->show_order('paid',__('Status',true))?></td>
            <td><?php echo $appCommon->show_order('client',__('Carriers',true))?></td>
            <td><?php echo $appCommon->show_order('disputed','Invoice Period')?></td>
<!--            <td><?php echo $appCommon->show_order('invoice_start',__('Period',true))?></td>-->
            <td><?php echo $appCommon->show_order('total_amount',__('Amt Gross',true))?></td>
            <td>&nbsp;<?php echo __('Amt Paid')?></td>
            <td><?php echo $appCommon->show_order('due_date',__('Due Date',true))?></td>
            
			<?php  if (isset($_SESSION['role_menu']['Finance']['pr_invoices']['model_x']) && $_SESSION['role_menu']['Finance']['pr_invoices']['model_x']) {?>
            <td>Generation Time</td>
            <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>State</td>
			<?php }?>
           <?php  if (isset($_SESSION['role_menu']['Finance']['pr_invoices']['model_w']) && $_SESSION['role_menu']['Finance']['pr_invoices']['model_w']) {?>
            
            <td class="last">Action</td>
			<?php }?>
          </tr>
          <tr>
          <?php  if (isset($_SESSION['role_menu']['Finance']['pr_invoices']['model_x']) && $_SESSION['role_menu']['Finance']['pr_invoices']['model_x']) {?>
          	<td>&nbsp;</td>
		  <?php }?>
            <td><?php echo $appCommon->show_order('invoice_time',__('Invoice Date',true))?></td>
            
             <?php  if (isset($_SESSION['role_menu']['Finance']['pr_invoices']['model_x']) && $_SESSION['role_menu']['Finance']['pr_invoices']['model_x']) {?>
             <td>&nbsp;</td>
             <td>&nbsp;</td>
            <td>&nbsp;</td>
             <?php }?>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
<!--            <td>&nbsp;</td>-->
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <?php  if (isset($_SESSION['role_menu']['Finance']['pr_invoices']['model_x']) && $_SESSION['role_menu']['Finance']['pr_invoices']['model_x']) {?><td>&nbsp;</td><?php }?>
           <?php  if (isset($_SESSION['role_menu']['Finance']['pr_invoices']['model_w']) && $_SESSION['role_menu']['Finance']['pr_invoices']['model_w']) {?> <td>&nbsp;</td>
            <td class="last">&nbsp;</td>
            <?php }?>
          </tr>
        </thead>
        <tbody>
          <?php
		$mydata = $p->getDataArray();
		$loop = count($mydata);
		for ($i=0;$i<$loop;$i++) { 
		$state = $mydata[$i][0]['state'];
	?>
          <tr class="<?php echo ($state == -1) ? 'row-2 row-3' : 'row-1'; ?>">
            <?php  if (isset($_SESSION['role_menu']['Finance']['pr_invoices']['model_x']) && $_SESSION['role_menu']['Finance']['pr_invoices']['model_x']) {?>
            <td align="center"><input type="checkbox" 
	    value="<?php echo $mydata[$i][0]['invoice_id']?>"
	    id="ids-<?php echo $mydata[$i][0]['invoice_id']?>" name="ids[]" class="input in-checkbox"></td>
        <?php }?>
            <td rel="tooltip" id="ci_<?php echo $i?>">
            <?php  if (isset($_SESSION['role_menu']['Finance']['pr_invoices']['model_w']) && $_SESSION['role_menu']['Finance']['pr_invoices']['model_w']) {?>
            <!--<a  href="<?php echo $this->webroot?>invoices/edit/<?php echo  $mydata[$i][0]['invoice_id']?>" class="link_width"><b><?php echo $mydata[$i][0]['invoice_number']?></b></a>-->
           <a  href="###" class="link_width"><b><?php echo $mydata[$i][0]['invoice_number']?></b></a>
 <?php }else{echo $mydata[$i][0]['invoice_number'];}?>
            <br>
              <small title=""><?php echo $mydata[$i][0]['invoice_time']?></small></td>
            <?php  if (isset($_SESSION['role_menu']['Finance']['pr_invoices']['model_x']) && $_SESSION['role_menu']['Finance']['pr_invoices']['model_x']) {?>
            <td>
                <?php if($mydata[$i][0]['output_type'] == 0):  ?>
                <a title="<?php echo __('download')?>" 
     href="<?php echo $this->webroot.'pr/pr_invoices/createpdf_invoice/'.$mydata[$i][0]['invoice_number']?>/2" > 
                    <img width="16" height="16" src="<?php echo $this->webroot?>images/download.png"> 
                </a>
                <?php elseif ($mydata[$i][0]['output_type'] == 1): ?>
                <a title="<?php echo __('download')?>" 
     href="<?php echo $this->webroot.'pr/pr_invoices/createxls_invoice/'.$mydata[$i][0]['invoice_number']?>/2" > 
                    <img width="16" height="16" src="<?php echo $this->webroot?>images/excel.png"> 
                </a>
                <?php else: ?>
                <a title="<?php echo __('download')?>" 
     href="<?php echo $this->webroot.'pr/pr_invoices/createhtml_invoice/'.$mydata[$i][0]['invoice_number']?>/2" > 
                    <img width="16" height="16" src="<?php echo $this->webroot?>images/html.png"> 
                </a>
                <?php endif; ?>
            </td>
     <?php }?>
            <td align="center">&nbsp;
              <?php 
    			if ($state==0)echo 'Un-verified';
    			else if ($state==-1) echo 'void';
    			//else if ($state==1) echo 'to send state';
    			else if ($state==1) echo 'Verified';
    				else if ($state==3) echo 'sent failure';
    				else if ($state==4) echo 're-generate';
    				else if ($state == 9) echo 'Sent<br>', $mydata[$i][0]['send_time'];
    				?></td>
            <td style="text-align:left;"><?php if (empty($mydata[$i][0]['res'])) {?>
              <img width="16" height="16" title="Client" src="<?php echo $this->webroot?>images/menuIcon.gif"> <?php echo $mydata[$i][0]['client']?></td>
            <?php } else {
    									echo $mydata[$i][0]['res'];
    								}
    				?>
<!--            <td align="center">&nbsp;
              <?php $state = $mydata[$i][0]['disputed'];
    			if ($state==0)echo 'Non-Disputed';
    			else if ($state==1) echo 'Disputed<br />' . number_format($mydata[$i][0]['disputed_amount'], 2);
    			else if ($state==2) echo 'Dispute Resolved';
    			?></td>-->
            <td align="center"><small> <?php echo $mydata[$i][0]['invoice_start']?><br>
              <?php echo $mydata[$i][0]['invoice_end']?> </small></td>
            <td align="right"><strong><?php 
            
            echo number_format($mydata[$i][0]['total_amount'], 2);
            
            /*
            if ($mydata[$i][0]['include_tax'])
            {
            	echo number_format($mydata[$i][0]['total_amount'], 2);
            }
            else
            {
            	echo number_format($mydata[$i][0]['type'] == 0 ? $mydata[$i][0]['buy_total'] : $mydata[$i][0]['sell_total'], 2);
            }
            */
            
            ?></strong> <br>
<!--                <small title="Paid" class="zero">Credit: <?php echo empty($mydata[$i][0]['client_credit'])?'0':$mydata[$i][0]['client_credit'] < 0 ? '(' . str_replace('-', '', $mydata[$i][0]['client_credit']) .')':$mydata[$i][0]['client_credit'];?></small> <br>
              <small title="Paid" class="zero">Offset: <?php echo empty($mydata[$i][0]['client_credit'])?'0':$mydata[$i][0]['client_offset'] < 0 ? '(' . str_replace('-', '', $mydata[$i][0]['client_offset']) .')':$mydata[$i][0]['client_offset'];?></small> <br>
              <small title="Paid" class="zero">Paid: <?php echo (empty($mydata[$i][0]['invoice_payment'])?'0':$mydata[$i][0]['invoice_payment']) < 0 ? '(' . str_replace('-', '', $mydata[$i][0]['invoice_payment']) .')':$mydata[$i][0]['invoice_payment'];?></small> <br>
              <small title="Paid" class="zero">Owe: <?php echo empty($mydata[$i][0]['invoice_payment'])?abs($mydata[$i][0]['total_amount']):number_format(abs($mydata[$i][0]['total_amount'])+$mydata[$i][0]['client_offset']-$mydata[$i][0]['invoice_payment']-$mydata[$i][0]['client_credit'],5,'.',',');?> </small> <br>
              <small title="Paid" class="zero">Balance: <?php echo (empty($mydata[$i][0]['balance']) ? 0 : $mydata[$i][0]['balance']) < 0 ? '(' . str_replace('-', '', $mydata[$i][0]['balance']) .')':$mydata[$i][0]['balance'];?></small>-->
              </td>
            <td align="center"><?php if ($mydata[$i][0]['paid'] == false) {?>
              <img width="16" height="16" title="Invoice is not paid" src="<?php echo $this->webroot?>images/status_notclosed.gif">
              <?php } else {?>
              <img width="16" height="16" title="Invoice is not paid" src="<?php echo $this->webroot?>images/status_closed.gif">
              <?php }?></td>
            <td align="center"><span class="warn">
              <?php 
        	if(strpos($mydata[$i][0]['due_inteval'], 'days') && $mydata[$i][0]['due_inteval']<0){
        	 echo abs($mydata[$i][0]['due_inteval']);
        			 ?>
              days ago
              <?php }?>
              </span> <br>
              <small><?php echo $mydata[$i][0]['due_date']?></small></td>
            <?php  if (isset($_SESSION['role_menu']['Finance']['pr_invoices']['model_x']) && $_SESSION['role_menu']['Finance']['pr_invoices']['model_x']) {?>
            <td>
                <ul>
                    <li>start:<?php echo $mydata[$i][0]['generate_start_time']; ?></li>
                    <li>copy:<?php echo $mydata[$i][0]['generate_copy_time']; ?></li>   
                    <li>statistics:<?php echo $mydata[$i][0]['generate_stats_time']; ?></li>   
                    <li>end:<?php echo $mydata[$i][0]['generate_end_time']; ?></li>  
                </ul>
            </td>
            <td><?php if(!empty($mydata[$i][0]['invoice_show_details'])) {?>
              <a title="Download CDR file" href="<?php echo $this->webroot?>pr/pr_invoices/download_cdr/<?php echo $mydata[$i][0]['invoice_id']?>"> <img width="16" height="16" src="<?php echo $this->webroot?>images/attached_cdr.gif"></a>
              <?php }?></td>
              <?php }?>
           <?php  if (isset($_SESSION['role_menu']['Finance']['pr_invoices']['model_w']) && $_SESSION['role_menu']['Finance']['pr_invoices']['model_w']) {?>
            
            <td>
                <?php
                    if(in_array($create_type, array(0, 1))):
                ?>
                <a  href="<?php echo $this->webroot?>pr/pr_invoices/payment_to_invoice/<?php echo $mydata[$i][0]['invoice_id']?>/1/<?php echo $mydata[$i][0]['client_id']?>/<?php echo $create_type; ?>"> <img width="16" height="16" src="<?php echo $this->webroot?>images/balanceOperations.gif"> </a>
                <?php
                    endif;
                ?>
            </td>
            
            <td>
                <?php echo $mydata[$i][0]['status'] ? $status[$mydata[$i][0]['status']] : '';  ?>
            </td>
            
            <td class="last">
            <?php if ($mydata[$i][0]['state'] != -1){?>
            <a title="re-generate" href="javascript:void(0)" onclick="if(confirm('Are you sure to re-generate?')){regenerate('<?php echo $mydata[$i][0]['invoice_id'];?>');}">
            	<img title="re-generate" src="<?php echo $this->webroot?>images/flag-0.png">
            </a>
			<?php }?>
                <?php if($mydata[$i][0]['state'] != -1): ?>
                <?php if($mydata[$i][0]['state'] == 0): ?>
                <a href="<?php echo $this->webroot?>pr/pr_invoices/change_type/<?php echo $mydata[$i][0]['invoice_id'];?>/1/<?php echo $this->params['pass'][0]; ?>" title="Verify">
                    <img src="<?php echo $this->webroot;?>images/verify.png" />
                </a>
                <?php endif; ?>
                <?php if($mydata[$i][0]['state'] == 1 || $mydata[$i][0]['state'] == 9): ?>
                <a href="<?php echo $this->webroot?>pr/pr_invoices/change_type/<?php echo $mydata[$i][0]['invoice_id'];?>/9/<?php echo $this->params['pass'][0]; ?>" title="<?php if($mydata[$i][0]['state'] == 9): ?> Resend <?php else: ?> Sent <?php endif; ?>">
                    <?php if($mydata[$i][0]['state'] == 9): ?> <img src="<?php echo $this->webroot;?>images/send.png" /> <?php else: ?> <img src="<?php echo $this->webroot;?>images/bOrigTariffs.gif" /> <?php endif; ?>
                </a>
                <?php endif; ?>
                <a href="<?php echo $this->webroot?>pr/pr_invoices/change_type/<?php echo $mydata[$i][0]['invoice_id'];?>/-1/<?php echo $this->params['pass'][0]; ?>" title="Void">
                    <img src="<?php echo $this->webroot;?>images/void.png" />
                </a>
                <?php
                    if(in_array($create_type, array(0, 1))):
                ?>
                <a href="<?php echo $this->webroot?>pr/pr_invoices/credit_note/<?php echo $mydata[$i][0]['invoice_number'];?>" title="Credit Note">
                    <img src="<?php echo $this->webroot;?>images/add.png" />
                </a>
                
                <a href="<?php echo $this->webroot?>pr/pr_invoices/debit/<?php echo $mydata[$i][0]['invoice_number'];?>" title="Debit">
                    <img src="<?php echo $this->webroot;?>images/add.png" />
                </a>
                <a href="###" title="Payment List" class="payment_list" invoice_id="<?php echo $mydata[$i][0]['invoice_id'];?>">
                    <img src="<?php echo $this->webroot; ?>images/payment.png">
                </a>
                <a href="###" title="Apply Payment" class="apply_payment" invoice_id="<?php echo $mydata[$i][0]['invoice_id'];?>">
                    <img src="<?php echo $this->webroot; ?>images/assign_payment.png">
                </a>
                <?php
                    endif;
                ?>
                <?php endif; ?>
            <?php }?>
                
                <?php if(isset($_SESSION['role_menu']['Payment_Invoice']['delete_invoice']) && $_SESSION['role_menu']['Payment_Invoice']['delete_invoice'] == 1): ?>
                <a title="Delete" href="<?php echo $this->webroot ?>pr/pr_invoices/delete_invoice/<?php echo $mydata[$i][0]['invoice_id'];?>/<?php echo $create_type ?>">
                    <img src="<?php echo $this->webroot ?>images/delete.png">
                </a>
                <?php endif; ?>
            </td>
          </tr>
          <tr style="display:none">
            <td><dl id="ci_<?php echo $i?>-tooltip" class="tooltip1">
                    <div style="padding:10px;">
                <dd><b><?php echo __('State',true);?>:</b></dd>
                <dd>
                  <?php $state = $mydata[$i][0]['state'];
    			if ($state==0)echo 'normal';
    			else if ($state==1) echo 'send';
    			else if ($state==2) echo 'vertify';
    			else if ($state==3) echo 'sent failure';
    			else if ($state==9) echo 'sent';
    			else if ($state==-1) echo 'void';
    			?>
                </dd>
                <dt><b><?php echo __('Total',true);?>:</b></dt>
                <dd> <?php echo $mydata[$i][0]['total_amount']?> USD</dd>
                <dt><b><?php echo __('Paid',true);?>:</b></dt>
                <dd> <?php echo empty($mydata[$i][0]['paid'])?'0.000':$mydata[$i][0]['paid']?> USD</dd>
                <dt><b><?php echo __('Period',true);?>:</b></dt>
                <dd> <?php echo $mydata[$i][0]['invoice_start']?><br>
                  <?php echo $mydata[$i][0]['invoice_end']?> </dd>
              </dl></div>
            </td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
      <?php  if (isset($_SESSION['role_menu']['Finance']['pr_invoices']['model_w']) && $_SESSION['role_menu']['Finance']['pr_invoices']['model_w']) {?>
      <?php if ($w == true) {?>
      <div style="margin: 10px 0px;">&nbsp;
        <?php echo __('action',true);?>:
        <select id="action" name="action" class="input in-select"  style="width: 150px;">
          <option value=""></option>
          <option value="0">Un-verify selected</option>
          <option value="1">Verify Selected</option>
          <option value="9">Send Selected</option>
<!--          <option value="3">delete invoices</option>-->
<!--          <option value="00">Non-Disputed</option>-->
<!--          <option value="11">Disputed</option>-->
<!--          <option value="6">Dispute Resolved</option>-->
          <option value="-1">Void Selected</option>
          <option value="8">Download Selected</option>
        </select>
        <input type="submit" value="Submit" class="input in-submit">
      </div>
      <?php }?>
      <?php }?>
      
    </fieldset>
  </form>
  <div id="tmppage"> <?php echo $this->element('page')?> </div>
  <!-- DYNAREA -->
  
  <?php }?>
</div>

<div id="dd"> </div>

<link rel="stylesheet" type="text/css" href="<?php echo $this->webroot?>easyui/themes/default/easyui.css">
<link rel="stylesheet" type="text/css" href="<?php echo $this->webroot?>easyui/themes/icon.css">
<script type="text/javascript" src="<?php echo $this->webroot?>easyui/jquery.easyui.min.js"></script>
<script language="Javascript" type="text/javascript">
function regenerate(invoice_id)
{
	$.get("<?php echo $this->webroot?>pr/pr_invoices/regenerate", {"invoice_id":invoice_id}, function(d){
		alert(d);
		window.location.reload();
	});
	
}
$(function() {
    $('#export_excel_btn').click(function() {
        $('#is_export').val('1');
        $('#search_panel').submit();
    });
    
    var $dd = $('#dd');
    var $payment_list = $('.payment_list');
    var $apply_payment = $('.apply_payment');
    
    $payment_list.click(function() {
            var invoice_id = $(this).attr('invoice_id');
            $dd.dialog({  
               title: 'Payment List',  
               width: 960,  
               height: 600,  
               closed: false,  
               cache: false,  
               resizable: true,
               href: '<?php echo $this->webroot?>pr/pr_invoices/get_invoice_payments/' + invoice_id,  
               modal: true,
               buttons:[{
                       text:'Close',
                       handler:function(){
                           $dd.dialog('close');
                       }
               }]
           });

           $dd.dialog('refresh', '<?php echo $this->webroot?>pr/pr_invoices/get_invoice_payments/' + invoice_id);  
       });
       
       
   $apply_payment.click(function() {
       var invoice_id = $(this).attr('invoice_id');
            $dd.dialog({  
               title: 'Apply Payment',  
               width: 960,  
               height: 600,  
               closed: false,  
               cache: false,  
               resizable: true,
               href: '<?php echo $this->webroot?>pr/pr_invoices/apply_payment/' + invoice_id + '/<?php echo $create_type; ?>',  
               modal: true,
               buttons:[{
                       text:'Submit',
                       handler:function(){
                           var $payment_form = $('#payment_form');
                           $payment_form.submit();
                       }
               },{
                       text:'Close',
                       handler:function(){
                           $dd.dialog('close');
                       }
               }]
           });

           $dd.dialog('refresh', '<?php echo $this->webroot?>pr/pr_invoices/apply_payment/' + invoice_id+ '/<?php echo $create_type; ?>');  
   });
    
});
</script>
