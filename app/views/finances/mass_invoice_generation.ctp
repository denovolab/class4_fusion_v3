<?php $action_type=empty($_GET['action_type'])?'2':$_GET['action_type'];?>
<div id="title">
  <h1>
   <?php  __('Finance')?>
    &gt;&gt;
    <?php __('Mass Invoice Generation')?>
  </h1>
  <ul id="title-search">
    <li>
      <?php //********************模糊搜索**************************?>
      <form   id="like_form"  action=""  method="get">
        <input type="text" id="search-_q" class="in-search default-value input in-text defaultText" title="<?php echo __('namesearch')?>" value="<?php if (!empty($search)) echo $search;?>" name="search">
        
        <input type="submit" name="submit" value="" class="search_submit"/>
        <input type="hidden" name="action_type" value="<?php echo $action_type;?>" />
        
      </form>
    </li>
    <li title="<?php echo __('advancedsearch')?> »" onClick="advSearchToggle();" id="title-search-adv" style="display: list-item;"></li>
  </ul>
  <!--<ul id="title-menu">
    
   <a class="link_btn" rel="popup" href="javascript:void(0)" onclick="GenerateInvoiceSelected();"><img width="16" height="16" alt="" src="<?php echo $this->webroot?>images/add.png"> <?php echo __('Generate Invoice')?></a></li>
    
  
  </ul>-->
  <?php  
	$action=isset($_SESSION['sst_statis_smslog'])?$_SESSION['sst_statis_smslog']:'';
 $w=isset($action['writable'])?$action['writable']:'';
 ?>
</div>
<div id="container">

  <?php //*********************  条件********************************?>
  <!--<fieldset class="title-block" id="advsearch"  style="width: 98%;display:block;">
    <form action="" method="get">
      <input name="advsearch" type="hidden"/>
      <input name="action_type" type="hidden" value="<?php echo $action_type?>"/>
      <table style="width:100%">
        <tbody>
          <tr>
            
            <td><label><?php echo __('Transaction Date',true);?> :</label>
              <input type="text" readonly onFocus="WdatePicker({dateFmt:'yyyy-MM-dd'});" id="start_date" style="width:120px;" name="start_date" class="input in-text wdate" value="<?php echo !empty($_REQUEST['start_date']) ? $_REQUEST['start_date'] : ''; ?>">
              --
              <input type="text" readonly onFocus="WdatePicker({dateFmt:'yyyy-MM-dd'});" id="end_date" style="width:120px;"  name="end_date" class="wdate input in-text" value="<?php echo !empty($_REQUEST['end_date']) ? $_REQUEST['end_date'] : ''; ?>"></td>
           
              <td><label><?php echo __('status',true);?>:</label>
              <select id="tran_status" name="tran_status">
                <option value=""><?php echo __('select')?></option>
                <option value="1" <?php echo (!empty($_REQUEST['tran_status']) && $_REQUEST['tran_status'] == 1) ? 'selected' : '';?>>Waiting</option>
                <option value="2" <?php echo (!empty($_REQUEST['tran_status']) && $_REQUEST['tran_status'] == 2) ? 'selected' : '';?>>Completed</option>
             			<?php if ($action_type == 1):?>
                <option value="0" <?php echo (isset($_REQUEST['tran_status']) && $_REQUEST['tran_status']  == 0) ? 'selected' : '';?>>Confirmed</option>
                <option value="3" <?php echo (!empty($_REQUEST['tran_status']) && $_REQUEST['tran_status'] == 3) ? 'selected' : '';?>>Refused</option>
                <?php endif;?>
              </select></td>
            <td><input type="submit" value="<?php echo __('submit')?>" class="input in-submit"></td>
          </tr>
        </tbody>
      </table>
    </form>
  </fieldset>-->
 
 <!-- <div id="toppage"></div>-->
 <?php 			$mydata =$p->getDataArray();
			$loop = count($mydata); 
			if(empty($mydata)){
			?>
<div class="msg">No data found.</div>
<?php }else{

?>


<!-- start 批量生成invoice -->
                    <form id="myform" method="post">
                            <table>
                                <tr>
                                    <td >select carriers:</td>
                                    <td >
                                    <select id="selectCarrier">
                                        <option value="all">
                                            All Carriers
                                        </option>
                                        <option value="select">
                                            Selected Carriers
                                        </option>
                                    </select> 
                                    </td>


                                    <td >Due Date:</td>
                                    <td  >
                                        <input type="text" id="due_date" value="" readonly="readonly" class="input in-text wdate" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd'});" name="due_date" realvalue="">
                                    </td>

                                    <td style="text-align: left;">Type:</td>
                                    <td  style="text-align: left;">
                                        <select name="type" >
                                            <option value="0">received</option>
                                            <option value="1">sent</option>
                                        </select>
                                    </td>
                                    <td class="label label4"><?php echo __('Invoice Date',true);?>(show):</td>
                                    <td class="value value4">
                                        <input type="text" id="invoice_time" value="<?php echo date("Y-m-d");?>" readonly="readonly" class="input in-text wdate" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd'});" name="invoice_time" realvalue="">
                                    </td>

                                </tr>
                            </table>

                    <fieldset style="margin-top: 15px;">

                    <table>
                    <tr class="period-block">
                        <td class="label"><?php __('time')?>:</td>
                        <td colspan="5" class="value">
                        <table class="in-date"><tbody>
                        <tr>
                        <td>
                        <table class="in-date">
                    <tbody>
                    <tr>
                    <td style="padding-right: 15px;">

                                    <?php
                    $r=array('custom'=>__('custom',true),    'curDay'=>__('today',true),    'prevDay'=>__('yesterday',true),    'curWeek'=>__('currentweek',true),    'prevWeek'=>__('previousweek',true),   'curMonth'=>__('currentmonth',true),
                    'prevMonth'=>__('previousmonth',true),   'curYear'=>__('currentyear',true)    ,'prevYear'=>__('previousyear',true)  ); 	
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
                                    'onchange'=>'setPeriod(this.value)','id'=>'query-smartPeriod','style'=>'width: 130px;','name'=>'smartPeriod',
                                    'div'=>false,'type'=>'select','selected'=>$s));?>

                    </td>
                        <td><input type="text" id="query-start_date-wDt" class="wdate in-text input" onchange="setPeriod('custom')"   readonly="readonly"  onkeydown="setPeriod('custom')"
                            value="" name="start_date"  style="margin-left: 0px; width: 156px;"></td>
                        <td></td>
                    </tr>
                    </tbody></table>

                        </td>
                        <td><input type="text" id="query-start_time-wDt" onchange="setPeriod('custom')" onkeydown="setPeriod('custom')"
                            readonly="readonly" 
                            style="width: 60px;" value="00:00:00" name="start_time" class="input in-text"></td>
                        <td>&mdash;</td>
                        <td>
                            <table class="in-date">
                                <tbody><tr>
                                    <td><input type="text" id="query-stop_date-wDt" class="wdate in-text input"  style="width: 120px;"    onchange="setPeriod('custom')"
                                    readonly="readonly" 
                                    onkeydown="setPeriod('custom')" value="" name="stop_date"></td>
                                    <td></td>
                                </tr>
                                </tbody>
                            </table>

                        </td>
                        <td><input type="text" id="query-stop_time-wDt" onchange="setPeriod('custom')"
                        readonly="readonly" 
                        onkeydown="setPeriod('custom')" style="width: 60px;" value="23:59:59" name="stop_time" class="input in-text">
                        
                        </td>

                        <td style="padding: 0pt 10px;"><?php echo __('in',true);?></td>
                        <td><select class="input in-select" name="query[tz]" style="width: 100px;" id="query-tz">
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
                        <option value="+0000"   selected="selected">GMT +00:00</option>
                        <option value="+0100">GMT +01:00</option>
                        <option value="+0200" >GMT +02:00</option>
                        <option value="+0300">GMT +03:00</option>
                        <option value="+0400">GMT +04:00</option><option value="+0500">GMT +05:00</option><option value="+0600">GMT +06:00</option><option value="+0700">GMT +07:00</option><option value="+0800">GMT +08:00</option><option value="+0900">GMT +09:00</option><option value="+1000">GMT +10:00</option><option value="+1100">GMT +11:00</option><option value="+1200">GMT +12:00</option></select></td>
                        </tr></tbody></table>

                    </td>

                    </tr>
                    <tr>
                        <td colspan="6">
                                <input type="submit" id="reset_balance_btn" class="input in-submit in-button" value="submit" />
                        </td>
                    </tr>
                    </table>

                    </fieldset>
                    </form>

<!--  end 批量生成invoice -->
  <div id="tmppage"> 
  
  <?php echo $this->element('page');?>
  </div>
  <table class="list">
      <thead>
        <tr>
          <td><input type="checkbox" onclick="checkAllOrNot(this,'producttab');" value=""/></td>
          <td><?php echo $appCommon->show_order('name', __('Carrier',true));?></td>
          <td><?php echo $appCommon->show_order('allowed_credit', __('Credit',true));?></td>
          <td><?php echo $appCommon->show_order('balance', __('Balance',true));?></td>
          <td>Past  Due  Amount</td>
          <td><?php echo $appCommon->show_order('invoice_end', __('Invoiced  Till',true));?></td>
          <td>Un- invoiced  Amount</td>
          <td><?php echo $appCommon->show_order('payment_term', __('Payment Term',true));?></td>
          <td><?php echo $appCommon->show_order('next_invoiced', __('Next Invoiced Date',true));?></td>
          
        </tr>
      </thead>
      <tbody id="producttab">
      <?php 
      for ($i=0;$i<$loop;$i++){
      	$status_val = array(0=>'Confirmed', 1=>'Waiting', 2=>'<font style="color:#FF6D06;">Complete</font>', 3=>'<font style="color:#FF0000;">Refused</font>');
      			?>
        <tr>
          <td style="text-align:center"><input type="checkbox" value="<?php echo $mydata[$i][0]['client_id']?>"/></td>
          <td>
          	<?php echo $mydata[$i][0]['name'] ?>
          </td>
          <td><?php echo $mydata[$i][0]['allowed_credit'] ?></td>
          <td><?php echo $mydata[$i][0]['balance'] ?></td>
          <td>
               <?php
                        echo round($mydata[$i][0]['total_amount'] - $mydata[$i][0]['credit_note']  + $mydata[$i][0]['debit_note'] - $mydata[$i][0]['pay_amount'], 2);
                ?>
          </td>
          <td><?php echo $mydata[$i][0]['invoice_end'] ?></td>
          <td></td>
          <td><?php echo $mydata[$i][0]['payment_term'] ?></td>
          <td><?php echo $mydata[$i][0]['next_invoiced'] ?></td>
        </tr>
        <?php }?>
      </tbody>
    </table>
  <div id="tmppage"> 
  <?php echo $this->element('page');?>
  </div>

<!--start 查询-->

        <form method="get" id="myform3">
                <table>
                    <tr>
                        <td>Carrier Name:</td>
                        <td><input type="text" name="carrier_name" value="<?php if (!empty($_REQUEST['carrier_name']))			
			{
				echo $_REQUEST['carrier_name'];				
			}?>"/></td>
                        
                        <td>Payment term:</td>
                        <td>
                            <select name="payment_term" id="payment_term">
                                <?php
                                    foreach($payment_terms as $payment_term){
                                ?>
                                <option value="<?php echo $payment_term[0]['payment_term_id']?>"><?php echo $payment_term[0]['name']?></option>
                                <?php
                                    }
                                ?>
                            </select>
                        </td>
                        
                        <td>Uninvoiced Amount >= : </td>
                        <td>
                            <input type="text" id="unin_amount" name ="unin_amount" value="<?php if (!empty($_REQUEST['unin_amount']))			
			{
				echo $_REQUEST['unin_amount'];				
			}?>" />
                        </td>
                        
                        <td>Balance:</td>
                        <td>
                            <select style="width:50px;" name="bal" id="bal">
                                <option value=">">></option>
                                <option value="=">=</option>
                                <option value="<"><</option>
                            </select>
                        </td>
                        <td>
                            <input type="text" id="balance" name="balance" value="<?php if (!empty($_REQUEST['balance']))			
			{
				echo $_REQUEST['balance'];				
			}?>" />
                        </td>
                        
                        
                    </tr>
                        
                    <tr>
                        <td>Next Invoiced Date:</td>
                        <td>
                            <select name="next_invoice_date" id="next_invoice_date"> 
                                <option value="<">before today</option>
                                <option value="=">today</option>
                                <option value=">">after today</option>
                            </select>
                        </td>
                        
                        <td>
                            Invoiced Tille:
                        </td>
                        <td>
                            <select name="invoice_compare" id="invoice_compare">
                                <option value="<">before</option>
                                <option value=">">after</option>
                            </select>
                        </td>
                        <td>
                            <input value="<?php if (!empty($_REQUEST['invoice_till_date']))			
			{
				echo $_REQUEST['invoice_till_date'];				
			}?>" name="invoice_till_date" type="text" value="" readonly="readonly" class="input in-text wdate" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd'});"  realvalue="">
                        </td>
                         <td><input value="<?php if (!empty($_REQUEST['invoice_till_time']))			
			{
				echo $_REQUEST['invoice_till_time'];				
			}?>" onfocus="WdatePicker({dateFmt:'HH:mm:ss'});"  class="input in-text in-input" type="text" name="invoice_till_time" value="00:00:00" style="width: 60px;" readonly="readonly" >
                        <td>
                            <select class="input in-select" name="query[tz]" style="width: 100px;" id="querytz">
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
                                <option value="+0000" selected="selected">GMT +00:00</option>
                                <option value="+0100">GMT +01:00</option>
                                <option value="+0200" >GMT +02:00</option>
                                <option value="+0300">GMT +03:00</option>
                                <option value="+0400">GMT +04:00</option>
                                <option value="+0500">GMT +05:00</option>
                                <option value="+0600">GMT +06:00</option>
                                <option value="+0700">GMT +07:00</option>
                                <option value="+0800">GMT +08:00</option>
                                <option value="+0900">GMT +09:00</option>
                                <option value="+1000">GMT +10:00</option>
                                <option value="+1100">GMT +11:00</option>
                                <option value="+1200">GMT +12:00</option>
                            </select>
                        </td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    
                    <tr>
                        <td colspan="9">
                                <input type="submit" id="reset_balance_btn" class="input in-submit in-button" value="submit" />
                        </td>
                    </tr>
                </table>

        </form>





<!--end  查询 -->

  <?php } ?>
</div>


<script type="text/javascript">
   /* function GenerateInvoiceSelected() {
        showDiv('pop-div','800','200','');
    }
    */
    function checkDate(url){
     
        var ids = '';
        var chx = document.getElementById('producttab').getElementsByTagName("input");
        var loop = chx.length;
        
        var res = true;
        
        if($("#selectCarrier").val() == "select"){
            
            for ( var i = 0; i < loop; i++) {
            var c = chx[i];
                if (c.type == "checkbox") {
                    if (c.checked == true && c.value != '') {
                        ids += c.value + ",";
                    }
                }
            }
            if (ids == '' || ids.length < 1)
            {
                jQuery.jGrowl("Please select the carrier",{theme:'jmsg-error'});
                //$("#selectCarrier").attr('value','all');
                res = false;
            }
            else
            {
                
                ids = ids.substring(0, ids.length - 1);// 去掉最后逗号
                url = url + "/" + ids;
                $("#myform").attr('action', url);
            }
        }else{
            for ( var i = 0; i < loop; i++) {
            var c = chx[i];
                    ids += c.value + ",";
            }
            ids = ids.substring(0, ids.length - 1);
            url = url + "/" + ids;
            $("#myform").attr('action', url);
            
        }
        return res;
        
    }
    
   $(function(){
       $("#myform").submit(function(){
           return checkDate('<?php echo $this->webroot?>/finances/generate_invoice');
       });
       
       var payment_term = "<?php if (!empty($_REQUEST['payment_term'])){ echo $_REQUEST['payment_term'];}else{echo '';}?>";
       
       if(payment_term != ''){
           $('#payment_term').attr('value',payment_term);
       }
       
       
       var invoice_compare = "<?php if (!empty($_REQUEST['invoice_compare'])){ echo $_REQUEST['invoice_compare'];}else{echo '';}?>";
       
       if(invoice_compare != ''){
           $('#invoice_compare').attr('value',invoice_compare);
       }
       
       var querytz = "<?php if (!empty($_REQUEST['query']['tz'])){ echo $_REQUEST['query']['tz'];}else{echo '';}?>";
       
       if(querytz != ''){
           $('#querytz').attr('value',querytz);
       }
       
       
       var bal = "<?php if (!empty($_REQUEST['bal'])){ echo $_REQUEST['bal'];}else{echo '';}?>";
       
       if(bal != ''){
           $('#bal').attr('value',bal);
       }
       
       var next_invoice_date = "<?php if (!empty($_REQUEST['next_invoice_date'])){ echo $_REQUEST['next_invoice_date'];}else{echo '';}?>";
       
       if(next_invoice_date != ''){
           $('#next_invoice_date').attr('value',next_invoice_date);
       }
       
       
       $("#myform3").submit(function(){
           var unin_amount_val = $("#unin_amount").val();
           if(isNaN(unin_amount_val)){
              jQuery.jGrowl("the Uninvoiced Amount must be number",{theme:'jmsg-error'});
              return false;
           }
           var balance_val = $("#balance").val();
           if(isNaN(balance_val)){
              jQuery.jGrowl("the Balance must be number",{theme:'jmsg-error'});
              return false;
           }
          
       });
       
   });
   
   
    
</script>