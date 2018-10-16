<!--导入所有reoprt页面的input和select样式文件-->
<?php echo $this->element('magic_css_three');?>
<div id="title">
 <h1><?php  __('Finance')?>&gt;&gt;
<?php __('MutualSettlements')?></h1> 
</div>

<div id="container">

<?php if (isset ( $exception_msg ) && $exception_msg) :	?>
	<?php	echo $this->element ( 'common/exception_msg' );?>		
<?php else : ?>	
<?php 

 $size=count($client_org);
if($size==0){?>
<div class="msg"><?php echo __('There were no data found for report')?></div>
<?php }else{?>
<?php echo $this->element('report/real_period')?>
<table class="list nowrap with-fields"  style="width: 100%">
    
	<thead>
		<tr>
 			<td width="8%" rel="0" > <?php echo $appCommon->show_order('time',__('date',true));?>	</td>
		  <td width="8%" rel="0" >&nbsp;
		         <?php echo $appCommon->show_order('client_name',__('Carriers',true))?>
					
			</td><!--
 			<td width="6%" rel="1" ></td>
			--><td width="6%" rel="2" class="cset-1">
			
			        <?php echo $appCommon->show_order('type',__('trans_type',true)); ?>
		
 		</td>
 			<td width="6%" rel="2" class="cset-1">
 			<?php echo $appCommon->show_order('amount',__('Sum',true)) ;echo $appCommon->show_sys_curr(); ?>
			</td>
  		<td width="6%" rel="2" class="cset-1"  style="text-align: center;">&nbsp; 
  		   <?php echo $appCommon->show_order('balance',__('balance',true));echo $appCommon->show_sys_curr(); ?>
			</td>
    </tr>
   </thead>
  <tbody >
 <?php 
 $Invoices_incoming=0.00;
 $Invoices_outgoing=0.00;
 $payments_received=0.00;
 $Paymentsent=0.00; 	
 $size=count($client_org);     for ($i=0;$i<$size;$i++){?>
         <tr    style="color: #4B9100">
  <td class="in-decimal"><?php  echo      $appCommon->cutomer_cdr_field('time',$client_org[$i][0]['time']);  ?> </td>
 <td class="in-decimal"><?php  echo  $client_org[$i][0]['client_name']; ?> </td><!--
 
    <td class="in-decimal"><?php if($client_org[$i][0]['type']==3){ 
    	$payments_received=$payments_received+$client_org[$i][0]['amount'];
    	echo  "<img width='16' height='16' src='<?php echo $this->webroot?>/images/type-0.gif'>";}
            if($client_org[$i][0]['type']==0){
            		$Invoices_outgoing=$Invoices_outgoing+$client_org[$i][0]['amount'];
            	echo  "<img width='16' height='16' src='<?php echo $this->webroot?>/images/menuIcon.gif'>";}
            if($client_org[$i][0]['type']==1){ 
            		$Invoices_incoming=$Invoices_incoming+$client_org[$i][0]['amount'];
            	echo  "<img width='16' height='16' src='<?php echo $this->webroot?>/images/menuIcon.gif'>";}
   ?></td>
   --><td class="in-decimal">
   		<?php 
   				if($client_org[$i][0]['type']==3){ echo  __('Payments received',true);}
         if($client_org[$i][0]['type']==0){ echo  __('buy invoice',true);}
         if($client_org[$i][0]['type']==1){  echo __('sell invoice',true);}
   				 ?>
   </td>
 		<td class="in-decimal"><?php  echo  $appCommon->currency_rate_conversion($client_org[$i][0]['amount']);   ?></td>
	  <td class="in-decimal">
                <?php  echo   $appCommon->currency_rate_conversion($client_org[$i][0]['balance']) < 0 ? '(' . str_replace('-', '',$appCommon->currency_rate_conversion($client_org[$i][0]['balance'])) . ')' : $appCommon->currency_rate_conversion($client_org[$i][0]['balance']); ?>
          </td>
  </tr>
 <?php }?>
   <tr class="totals row-1">
    <td align="right" colspan="3"><?php echo __('sell invoice',true);?>:</td>
    <td class="in-decimal pos"><?php  echo $appCommon->currency_rate_conversion($Invoices_incoming); ?> </td>
    <td class="last"></td>
		</tr>
		<tr class="totals row-2">
    <td align="right" colspan="3"><?php echo __('buy invoice',true);?>:</td>
    <td class="in-decimal neg"><?php echo  $appCommon->currency_rate_conversion($Invoices_outgoing);  ?> </td>
    <td class="last"></td>
		</tr>
		<tr class="totals row-1">
    <td align="right" colspan="3"><?php echo __('Payments received',true);?>:</td>
    <td class="in-decimal pos"><?php  echo  $appCommon->currency_rate_conversion($payments_received);  ?> </td>
    <td class="last"></td>
		</tr>
		<tr class="totals row-2">
    <td align="right" colspan="3"><?php echo __('Payments sent',true);?>:</td>
    <td class="in-decimal neg"><?php
if(empty($payment_sent[0][0]['sum'])){
	$sent=0.00;
}else{
	$sent=$payment_sent[0][0]['sum'];
	
}
    echo  $appCommon->currency_rate_conversion($sent); ?></td>
    <td class="last"></td>
		</tr>
 	</tbody>
</table>
<?php }?>
 <?php //***********************报表查询参数*********************?>
<fieldset class="query-box">

<div class="search_title"><img src="<?php
echo $this->webroot?>images/search_title_icon.png" />
    <?php __('search')?>
  </div>

<script type="text/javascript">

//设置每个字段所对应的隐藏域
var _ss_ids_client = {'id_clients': 'query-id_clients', 'id_clients_name': 'query-id_clients_name', 'account': 'query-account', 'tz': 'query-tz', 'id_currencies': 'query-id_currencies'};
var _ss_ids_reseller = {'id_resellers': 'query-id_resellers', 'id_resellers_name': 'query-id_resellers_name', 'account': 'query-account', 'tz': 'query-tz', 'id_currencies': 'query-id_currencies'};

</script>
<?php echo $form->create ('Cdr', array ('type'=>'get','url' => '/clientmutualsettlements/summary_reports/' ,'onsubmit'=>"if ($('#query-output').val() == 'web') loading();"));?>
<input   type="hidden"   value="searchkey"    name="searchkey"/>
<input class="input in-hidden" name="query[process]" value="1" id="query-process" type="hidden">
<input class="input in-hidden" name="query[order_by]" value="" id="query-order_by" type="hidden">
<input class="input in-hidden" name="query[id_clients]" value="" id="query-id_clients" type="hidden">
<input class="input in-hidden" name="query[id_resellers]" value="" id="query-id_resellers" type="hidden">
<table class="form">
	<tbody>
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
										$r=array(
												'custom'=>__('custom',true),
												'curDay'=>__('today',true),
												'prevDay'=>__('yesterday',true),
												'curWeek'=>__('currentweek',true),
												'prevWeek'=>__('previousweek',true),
												'curMonth'=>__('currentmonth',true),
												'prevMonth'=>__('previousmonth',true),
												'curYear'=>__('currentyear',true),
												'prevYear'=>__('previousyear',true)
										); 	
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
										 	'div'=>false,'type'=>'select','selected'=>$s));
								?>
								</td>
						    <td>
						    		<input type="text" id="query-start_date-wDt" class="wdate in-text input" onchange="setPeriod('custom')"   readonly="readonly"  onkeydown="setPeriod('custom')" value="" name="start_date"  style="margin-left: 0px; width: 120px;">
						    </td>
						    <td></td>
								</tr>
							</tbody>
						</table>
    			</td>
    			<td>
    				<input type="text" id="query-start_time-wDt" onchange="setPeriod('custom')" onkeydown="setPeriod('custom')" readonly="readonly" style="width: 60px;" value="00:00:00" name="start_time" class="input in-text">
		    		<script type="text/javascript">
		    					jQuery(document).ready(function(){
												jQuery('input[name=start_date]').val('<?php echo array_keys_value($this->params,'url.start_date',date('Y-m-d'))?>');
		        					});
		    		</script>
    			</td>
    			<td>&mdash;</td>
    			<td>
    				<table class="in-date">
							<tbody>
								<tr>
			    				<td>
			    					<input type="text" id="query-stop_date-wDt" class="wdate in-text input"  style="width: 120px;"    onchange="setPeriod('custom')" readonly="readonly" onkeydown="setPeriod('custom')" value="" name="stop_date">
			    				</td>
			    				<td></td>
								</tr>
							</tbody>
						</table>
    			</td>
    			<td>
    					<input type="text" id="query-stop_time-wDt" onchange="setPeriod('custom')"
    						readonly="readonly" 
     					onkeydown="setPeriod('custom')" style="width: 60px;" value="23:59:59" name="stop_time" class="input in-text">
     		</td>
     		<td style="padding: 0pt 10px;">in</td>
     		<td><select id="query-tz" style="width: 100px;" name="query[tz]" class="input in-select">
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
     <option selected="selected" value="+0200">GMT +02:00</option>
     <option value="+0300">GMT +03:00</option>
     <option value="+0330">GMT +03:30</option><option value="+0400">GMT +04:00</option><option value="+0500">GMT +05:00</option><option value="+0600">GMT +06:00</option><option value="+0700">GMT +07:00</option><option value="+0800">GMT +08:00</option><option value="+0900">GMT +09:00</option><option value="+1000">GMT +10:00</option><option value="+1100">GMT +11:00</option><option value="+1200">GMT +12:00</option></select></td>
    </tr></tbody></table>
</td>
    <td class="buttons"><input type="submit" value="<?php echo __('Search')?>" class="input in-submit"></td>
</tr>
<tr>
    <td class="label"> <?php  __('Carriers')?> </td>
    <td id="client_cell" class="value" >
        <input type="text" id="query-id_clients_name" value="" name="query[id_clients_name]" class="input in-text" style="width:auto;" maxlength="256">        <!--
        <img width="25" height="25" onclick="showClients()"  class="img-button" src="<?php echo $this->webroot?>images/search-small.png">
        -->
        <img width="25" height="25" onclick="ss_clear('client', _ss_ids_client)" class="img-button" src="<?php echo $this->webroot?>images/delete-small.png">
    </td>
    <td class="label" style="min-width:50px;"><?php __('currency')?>:</td>
    <td class="value">
 		<?php echo $form->input('currency',	array('options'=>$currency,'empty'=>'','label'=>false ,'div'=>false,'type'=>'select'));?>
    </td>
     <td class="label"> <?php __('trans_type')?>  </td>
    <td id="client_cell" class="value">
			<?php
				$tran_type=array('0'=>'buy invoice','1'=>'sell invoices','3'=>'Payments received', '4'=>'Payments sent');
				echo $form->input('type',	array('options'=>$tran_type,'empty'=>'','label'=>false ,'div'=>false,'type'=>'select'));
			?>
    </td>
</tr>








<?php  if ($_SESSION['role_menu']['Finance']['clientmutualsettlements']['model_x']) {?>
<tr class="output-block">
    <td class="label"><span><?php __('output')?>:</span></td>
    <td class="value">
    <select id="query-output" onchange="repaintOutput();" style="width:auto;"  name="query[output]" >
    <option selected="selected" value="web"><?php echo __('Web',true)?></option><option value="csv">Excel CSV</option></select></td>
    <td colspan="5" class="label"><div></div></td>
</tr>
<?php }?>

</tbody></table>
<?php echo $form->end();?>
</fieldset>
 
 

<script type="text/javascript">
//<![CDATA[


function my_sort(order_field,order){
	var s = "1";
	var f = document.createElement('a');
	f.style.display = 'none';
	document.body.appendChild(f);
	f.href = "?page=1&size="+s+"&kw="+order_field+"&order="+order;
	window.location = f.href;
}

tz = $('#query-tz').val();
function showClients ()
{
    ss_ids_custom['client'] = _ss_ids_client;
    winOpen('<?php echo $this->webroot?>clients/ss_client?types=2&type=0', 500, 530);

}

function showRsellers()
{
    ss_ids_custom['reseller'] = _ss_ids_reseller;
    winOpen('<?php echo $this->webroot?>/resellers/ss_reseller?type=2&types=8', 500, 530);

}

function repaintOutput() {
    if ($('#query-output').val() == 'web') {
        $('#output-sub').show();
    } else {
        $('#output-sub').hide();
    }
}
setPeriod();
repaintOutput();
//]]>
</script>



</div>
<div>
<?php endif;?>
</div>