<style type="text/css">
.form .value, .list-form .value{ text-align:left;}
</style>
<?php echo $this->element("clients/title_edit")?>
<div class="container"> 
	<ul class="tabs">
     
	 <li class="active"><a href="<?php echo $this->webroot ?>clients/edit/<?php echo $post['Client']['client_id']?>"><img width="16" height="16" src="<?php echo $this->webroot?>images/bDR.gif"/> <?php __('basicinfo')?></a></li>   
	<?php $project_name=Configure::read('project_name');
		 if($project_name=='exchange'){?>
		<li ><a href="<?php echo $this->webroot?>gatewaygroups/view_egress?query[id_clients]=<?php echo $post['Client']['client_id']?>&viewtype=client"><img width="16" height="16" src="<?php echo $this->webroot?>images/menuIcon.gif"/> <?php __('egress')?></a></li> 
			<li ><a href="<?php echo $this->webroot?>gatewaygroups/view_ingress?query[id_clients]=<?php echo $post['Client']['client_id']?>&viewtype=client"><img width="16" height="16" src="<?php echo $this->webroot?>images/menuIcon.gif"/> <?php __('ingress')?></a></li> 
		 <?php }?>
	<?php if($project_name=='wholesales'||$project_name=='partition'){?>
		<li ><a href="<?php echo $this->webroot?>prresource/gatewaygroups/view_egress?query[id_clients]=<?php echo $post['Client']['client_id']?>&viewtype=client"><img width="16" height="16" src="<?php echo $this->webroot?>images/menuIcon.gif"/> <?php __('egress')?></a></li> 
			<li ><a href="<?php echo $this->webroot?>prresource/gatewaygroups/view_ingress?query[id_clients]=<?php echo $post['Client']['client_id']?>&viewtype=client"><img width="16" height="16" src="<?php echo $this->webroot?>images/menuIcon.gif"/> <?php __('ingress')?></a></li> 
	
	
	<?php }?>
	</ul>
	<?php echo $form->create ('Client', array ('action' => "edit/{$this->params['pass'][0]}",'id'=>'ClientEdit' ));?>
    <input type="hidden" name="order_user_id" value="<?php if(isset($_GET['order_user_id'])&&!empty($_GET['order_user_id']))echo $_GET['order_user_id'];?>" />
    
  <?php echo $form->input('client_id',array('label'=>false,'div'=>false,'type'=>'hidden','value'=>$post['Client']['client_id']))?>
 <input type="hidden" value="<?php echo $post['Client']['client_id']?>" name="client_id"/>
<table class="cols"><col width="35%"/><col width="38%"/><col width="27%"/>
<tr>
<td><!-- COLUMN 1 -->
<?php //*******************************系统信息设置*****************************?>
<fieldset><legend><?php echo __('Basic Info',true);?></legend>
<table class="form">
<tr>
    <td align="right"><?php echo __('Name')?>:</td>
    <td align="left">
    <?php echo $form->input('name',array('label'=>false,'div'=>false,'type'=>'text','maxLength'=>500,'value'=>$post['Client']['name']))?>
    </td>
</tr>
<tr>
    <td align="right"><?php echo __('status')?>:</td>
    <td align="left">
    <?php 
         $st=array('true'=>__('Active',true), 'false'=>__('Inactive',true));
        
          empty($post['Client']['status'])?$tmp_s='false':$tmp_s='true';
    echo $form->input('status',array('options'=>$st,'label'=>false,'div'=>false,'type'=>'select','selected'=>$tmp_s))?>

    </td>
</tr>
<tr>
    <td align="right"><span id="ht-100001" class="helptip" rel="helptip"><?php echo __('mode')?></span><span id="ht-100001-tooltip" class="tooltip">
    If Prepaid selected - this client`s Balance+Credit value will be checked on RADIUS authorization, if Postpaid selected -
     RADIUS authorization check is disabled</span>:</td>

    <td align="left">
       <?php 
         $st=array('1'=>__('Prepaid',true), '2'=>__('postpaid',true));
    echo $form->input('mode',array('options'=>$st,'label'=>false,'div'=>false,'type'=>'select','selected'=>$post['Client']['mode']))?>
    </td>
</tr>

<tr>
    <td align="right"><?php echo __('currency')?>:</td>
    <td align="left">
             		<?php echo $form->input('currency_id',
 		array('options'=>$currency,'label'=>false ,'div'=>false,'type'=>'select','selected'=>$post['Client']['currency_id']));?>

    </td>

</tr>
<?php $project_name=Configure::read('project_name');if($project_name=='exchange'){?>
<tr>
    <td align="right"><?php echo __('Service Charge',true);?>:</td>
    <td align="left">
       <?php echo $form->input('service_charge_id',array('options'=>$service_charge,'label'=>false ,'div'=>false,'selected'=>$post['Client']['service_charge_id'],'type'=>'select'));?>
    </td>
</tr>
<?php }?>

	<?php if($_SESSION['role_menu']['Payment_Invoice']['modify_credit_limit'] == 1): ?>	
<tr>
    <td align="right" ><span id="ht-100002" class="helptip" rel="helptip"><?php echo __('allowedcredit')?></span>:</td>
    <td style="text-align: left;">
    		
        <?php 
        echo $form->input('allowed_credit',array('label'=>false,'div'=>false,'type'=>'text',
        'value'=>round(abs($post['Client']['allowed_credit']),5),
        'class'=>'in-decimal input in-text','maxlength'=>'26','style'=>'width:100px'));
        ?>
        
        <label class="money"></label>
        &nbsp;
        <span id="unlimited_panel">
        Unlimited
        <?php echo $form->input('unlimited_credit',array('class'=>'in-decimal input in-checkbox','label'=>false,'div'=>false,'type'=>'checkbox','checked'=>$post['Client']['unlimited_credit']=='1'? 'checked' : 'false'))?>
        </span>
     </td>
</tr>
<?php else: ?>
<?php 
        echo $form->input('allowed_credit',array('label'=>false,'div'=>false,'type'=>'hidden',
        'value'=>round(abs($post['Client']['allowed_credit']),5),
        'class'=>'in-decimal input in-text','maxlength'=>'26','style'=>'width:100px'));
        ?>
<?php endif; ?>
<?php if($_SESSION['role_menu']['Payment_Invoice']['modify_min_profit'] == 1): ?>
<!--
<tr>
     <td  align="right"><span id="ht-100002" class="helptip" rel="helptip"><?php echo __('lowprofit')?></span>:</td>
	    <td style="text-align: left;">
	        <?php echo $form->input('profit_margin',array('label'=>false,'value'=>$post['Client']['profit_margin'],'div'=>false,'type'=>'text','class'=>'in-decimal input in-text','maxlength'=>'6','style'=>'width:33%'))?>
	    			<?php echo $xform->input('profit_type',array('options'=>Array(1=>'Percentage',2=>'Value'),'style'=>'width:33%','value'=>$post['Client']['profit_type']))?>
	     </td>
   
</tr>
-->
<?php endif; ?>
<tr>
    <td align="right"><?php echo __('CPS')?>:</td>
    <td align="left">
    <?php echo $form->input('cps_limit',array('label'=>false,'div'=>false,'type'=>'text','maxLength'=>16,'value'=>$post['Client']['cps_limit']))?>
    </td>
</tr>
<tr>
    <td align="right"><?php echo __('Call limit')?>:</td>
    <td align="left">
    <?php echo $form->input('call_limit',array('label'=>false,'div'=>false,'type'=>'text','maxLength'=>16,'value'=>$post['Client']['call_limit']))?>
    </td>
</tr>
<tr>
<td align="right" width="25%"><?php echo __('Transaction Fee')?>:</td>
<td align="left" width="70%">
    <?php echo $form->input('transaction_fee_id',array('options'=>$transation_fees,'label'=>false ,'div'=>false,'type'=>'select','class'=>'input in-text in-select','selected'=>$post['Client']['transaction_fee_id']));?>
</td>
</tr>
</table>
</fieldset>
<fieldset>
<legend><?php echo __('Companyinfo')?></legend>
	<table class="form">
		<tr>
		    <td align="right"><?php echo __('Companyname')?>:</td>
		    <td align="left">
		        <?php echo $form->input('company',array('label'=>false,'div'=>false,'value'=>$post['Client']['company'],'maxLength'=>256))?>
		   </td>
		</tr>
		<tr>
		    <td align="right"><?php echo __('address')?>:</td>
		    <td align="left">
		    <?php echo $form->input('address',array('label'=>false,'div'=>false,'type'=>'textarea','maxlength'=>'500','value'=>$post['Client']['address']))?>
		   </td>
		</tr>
		<tr>
		    <td align="right"><?php echo __('Main e-mail',true);?>: </td>
		
		    <td align="left"> <?php echo $form->input('email',array('label'=>false,'div'=>false,'value'=>$post['Client']['email'] ))?></td>
		</tr>
		<tr>
		    <td align="right"><?php echo __('NOC e-mail',true);?>: </td>
		
		    <td align="left"> <?php echo $form->input('noc_email',array('label'=>false,'div'=>false,'value'=>$post['Client']['noc_email']  ))?></td>
		</tr>
		<tr>
		    <td align="right"><?php echo __('Billing e-mail',true);?>: </td>
		
		    <td align="left"> <?php echo $form->input('billing_email',array('label'=>false,'div'=>false,'value'=>$post['Client']['billing_email'] ))?></td>
		</tr>
		<tr>
		    <td align="right"><?php echo __('Rates e-mail',true);?>: </td>
		
		    <td align="left"> <?php echo $form->input('rate_email',array('label'=>false,'div'=>false,'value'=>$post['Client']['rate_email']  ))?></td>
		</tr>
                <tr>
                        <td><?php echo __('Rate Delivery Email',true);?>: </td>
                        <td> <?php echo $form->input('rate_delivery_email',array('label'=>false,'div'=>false,  'maxLength'=>'100','class'=>'input in-text in-input'))?></td>
                    </tr>
		<tr>
		    <td align="right"><?php echo __('Tax ID',true);?>: </td>
		    <td align="left"> <?php echo $form->input('tax_id',array('label'=>false,'div'=>false,'value'=>$post['Client']['tax_id']))?></td>
		</tr>
		<tr>
		    <td align="right"><?php echo __('Account Details',true);?>: </td>
		
		    <td align="left"> <?php echo $form->input('details',array('label'=>false,'div'=>false,'value'=>$post['Client']['details'],'rows'=>'8'))?></td>
		</tr>
                <tr>
                    <td><?php echo __('Include Tax in Invoice',true);?>:</td>
                    <td>
                        <?php echo $form->input('include_tax',array('label'=>false,'div'=>false, 'class'=>'input in-text in-input', 'checked' => $post['Client']['include_tax'] ? true : false))?>
                        <span id="tax_area" style="display:none;"> 
                        <?php echo $form->input('tax',array('label'=>false,'div'=>false, 'class'=>'input in-text in-input', 'style'=>'width:30px;', 'value' => $post['Client']['tax']))?>
                        %
                        <span>
                    </td>
                </tr>
	</table>
</fieldset>
    <fieldset>
    <legend><?php echo __('Short Call Charge',true)?></legend>
    <div>
        If <?php echo $form->input('scc_percent',array('label'=>false,'style'=>'width:30px;','div'=>false,'value'=>$post['Client']['scc_percent']))?> % overall invoice minute is below or equal to <?php echo $form->input('scc_bellow',array('label'=>false,'style'=>'width:30px;','div'=>false,'value'=>$post['Client']['scc_bellow']))?> second, then an
additional charge of <?php echo $form->input('scc_charge',array('label'=>false,'style'=>'width:70px;','div'=>false,'value'=>number_format($post['Client']['scc_charge'], 6)))?> will be applied to each call 
<?php echo $form->input('scc_type',array('options'=>array('0'=>'meeting the short duration defined above', '1'=>'that exceed the defined percentage'),'label'=>false ,'div'=>false,'type'=>'select','selected'=>$post['Client']['scc_type'], 'style'=>'width:auto;'));?>
    </div>
</fieldset>  
    
<fieldset>
	<legend>
		<span id="ht-100012" class="helptip" rel="helptip">
		<?php echo $form->input('is_daily_balance_notification',array('class'=>'in-decimal input in-checkbox','label'=>false,'div'=>false,'type'=>'checkbox','checked'=>$post['Client']['is_daily_balance_notification']=='1'? 'checked' : 'false'))?>
		<?php echo __('lowbalancenotice')?>
		</span>
		<span id="ht-100012-tooltip" class="tooltip">Send notification when current balance + credit limit is lower than specified threshold. Leave field empty to disable notification.</span>
	</legend>
	<table class="form">
		<col style="width:20%;"/><col style="width:62%;"/>
		<tr>
		    <td align="right"><?php echo __('notifyclientbalance')?>:</td>
		    <td  style="text-align: left;">
		      <?php echo $form->input('notify_client_balance',array('class'=>'in-decimal input in-text','label'=>false,'div'=>false, 'style'=>'width:150px;',
		        'value'=>empty($post['Client']['notify_client_balance'])?0:round($post['Client']['notify_client_balance'],5),
		      'type'=>'text'))?>
                        
                         <?php echo $form->input('notify_client_balance_type',array('options'=>array(0=>'Actual Balance', 1 => 'Percentage'),'label'=>false, 'style'=>'width:100px;' ,'div'=>false,'type'=>'select','class'=>'input in-text in-select','selected'=>$post['Client']['notify_client_balance_type']));?>
		    </td>
		</tr>
		<!--<tr>
		    <td align="right"><?php echo __('notifyadminbalance')?>:</td>
		    <td style="text-align: left;">
		     <?php echo $form->input('notify_admin_balance',array('class'=>'in-decimal input in-text','label'=>false,'div'=>false,
		       'value'=>empty($post['Client']['notify_admin_balance'])?0:round($post['Client']['notify_admin_balance'],5),
		     'type'=>'text'))?>
		     <label class="money"></label>
		    </td>
		</tr>-->
                <tr>
                    <td></td>
                    <td  style="text-align: left;">
                        <div id="is_daily_balance_panel" style="<?php echo $post['Client']['is_daily_balance_notification']=='1' ? 'display:block;' : 'display:none;'; ?>">
                        <?php __('Number of days'); ?>:
                        <input type="text" name="data[Client][daily_balance_notification]" value="<?php echo $post['Client']['daily_balance_notification'] ?>" style="width:30px;" />
                        <br />
                        <?php __('Recipient'); ?>:
                       
                        <?php
echo $form->input('daily_balance_recipient',array('options'=>array('Partner\'s Billing Contact', 'Owner\'s Billing Contact', 'Both'),'label'=>false ,'div'=>false,'type'=>'select','selected'=>$post['Client']['daily_balance_recipient']));
                        ?>
                        </div>
                    </td>
                </tr>
	</table>
</fieldset>    
</td>
<td style="margin-top:0px; vertical-align: top;">
<!--
<fieldset>
	<legend> <?php echo __('Short Call Charge')?></legend>
	<table class="form">
		<tr>
		    <td align="right"><?php echo __('Below')?>:</td>
		    <td align="left"><?php echo $form->input('scc_bellow',array('label'=>false,'div'=>false,'value'=>$post['Client']['scc_bellow']))?>seconds
		    </td>
		</tr>
		<tr>
		    <td align="right"><?php echo __('Percent')?>:</td>
		    <td align="left"><?php echo $form->input('scc_percent',array('label'=>false,'div'=>false,'value'=>$post['Client']['scc_percent']))?>%
		    </td>
		</tr>
		<tr>
		    <td align="right"><?php echo __('Add charge')?>:</td>
		    <td align="left"><?php echo $form->input('scc_charge',array('label'=>false,'div'=>false,'value'=>number_format($post['Client']['scc_charge'], 6)))?>
		    </td>
		</tr>
	</table>
</fieldset>
-->    
      
    
<fieldset>
	<legend>
		<?php
			empty($post['Client']['auto_invoicing'])?$au='false':$au='checked';
			echo $form->checkbox('auto_invoicing',array('checked'=>$au))
		?>
 <label for="autoinvoice_enabled"><?php echo __('autoinvoicing')?></label>
 </legend>
<table class="form">
<tr>
    <td align="right"><?php echo __('paymentterm')?>:</td>

    <td align="left">
   	<?php echo $form->input('payment_term_id',
 		array('options'=>$paymentTerm,'label'=>false ,'div'=>false,'type'=>'select','selected'=>$post['Client']['payment_term_id']));?>

    </td>
</tr>
<tr>
    <td align="right"><?php __('Starting From')?>:</td>
    <td align="left">
    			<?php  
     				echo $form->input('invoice_start_from',
 							array('onfocus'=>"WdatePicker({dateFmt:'yyyy-MM-dd'});",  'value'=>$post['Client']['invoice_start_from'], 'class'=>"input in-text",'readonly'=>true,'label'=>false ,'div'=>false,'type'=>'text'));
 					?>
    </td>
</tr>
<tr>
    <td align="right"><?php echo __('Invoice type')?>:</td>

    <td align="left">
   	<?php echo $form->input('auto_invoice_type',
 		array('options'=>array('client','vendor','both'),'label'=>false ,'div'=>false,'type'=>'select','selected'=>$post['Client']['auto_invoice_type']));?>

    </td>
</tr>
<tr>
    <td align="right"><?php echo __('invoiceformat')?>:</td>

    <td align="left">
	<?php  $route_type=array('1'=>'PDF', '2'=>'Word');//,'2'=>'Excel','3'=>'HTML');
         	echo $form->input('invoice_format',
 		array('options'=>$route_type,'label'=>false ,'div'=>false,'type'=>'select','selected'=>$post['Client']['invoice_format']));?>

    </td>
</tr>
<tr>
    <td>&nbsp;</td>
    <td style="text-align: left;">
	  <?php 
    	empty($post['Client']['attach_cdrs_list'])?$au='false':$au='checked';
    	echo $form->checkbox('attach_cdrs_list',array('checked'=>$au))?>
		 <?php echo __('attachcdrslist')?>
    <?php 
	    empty($post['Client']['invoice_show_details'])?$au='false':$au='checked';
	    echo $form->checkbox('invoice_show_details',array('checked'=>$au)), 'show detail';	
            empty($post['Client']['invoice_jurisdictional_detail'])?$au='false':$au='checked';
	    echo $form->checkbox('invoice_jurisdictional_detail',array('checked'=>$au)), ' Add Jurisdictional Detail';	
  		  ?>
</td>
</tr>
<tr>
    <td align="right"><?php echo __('Time zone')?>:</td>

    <td align="left">
   	<?php 
  $zone_arr = array();
  for ($i = -12; $i<=12; $i++)
   	{
 		$zone_str = $i<0 ? sprintf("-%02d:00", 0-$i) : sprintf("+%02d:00", $i);  		
 		$zone_arr[$zone_str] = 'GMT ' . $zone_str;
   	}
   	echo $form->input('invoice_zone',	array('options'=>$zone_arr,'label'=>false ,'div'=>false,'type'=>'select', 'selected'=>empty($post['Client']['invoice_zone']) ? '+00:00' : $post['Client']['invoice_zone'] ));
   		?>

    </td>
	</tr>
	<tr>
    <td align="right"><?php echo __('No Invoice for Zero Traffic')?>:</td>

    <td align="left">
   	<?php 
  echo $form->input('invoice_zero',	array('options'=>array(1=>'Yes', 0=>'No'),'label'=>false ,'div'=>false,'type'=>'select', 'selected'=>$post['Client']['invoice_zero']?1:0 ));
   		?>

    </td>
	</tr>
<tr>
    <td align="right"><?php echo __('CDR Compress Format')?>:</td>
    <td align="left">
	    		<?php  
	    			//$route_type=array('1'=>'Excel XLS','2'=>'CSV');
	    			$route_type=array('3'=>'zip', '4'=>'tar.gz');
	     		echo $form->input('cdr_list_format',array('options'=>$route_type,'label'=>false ,'div'=>false,'type'=>'select','selected'=>$post['Client']['cdr_list_format']));
	     			?>
    </td>
</tr>
<tr>
        <td><?php __('Rate Decimal Place'); ?>:</td>
        <td>
            <?php 
                    $decimal_places = range(1, 10);
                    $decimal_places = array_combine($decimal_places, $decimal_places); 
                ?>
               <?php echo $form->input('decimal_place',array('options'=>$decimal_places,
                             'style' => 'width:80px;', 'value' => $post['Client']['decimal_place'],'label'=>false,'div'=>false))?>
        </td>
    </tr>
    <tr>
                        <td><?php __('Rate Value'); ?>:</td>
                        <td>
                             <?php echo $form->input('rate_value',array('options'=>array('Average', 'Actual'),
                             'style' => 'width:80px;', 'value'=>$post['Client']['rate_value'],'label'=>false,'div'=>false))?>
                        </td>
                    </tr>
    

<tr>
                        <td><?php __('Email Invoice'); ?></td>
                        <td>
                            <?php empty($post['Client']['email_invoice'])?$au='false':$au='checked';  ?>
                            <?php echo $form->checkbox('email_invoice', array('checked' => $au));?>
                        </td>
                    </tr>
                <!--    
            <tr>
                <td class="label label2">Include Available Credit:</td>
                <td class="value value2">
                    <select style="width:180px;" name="data[Client][include_available_credit]" class="input in-select select">
                        <option value="0" <?php echo $post['Client']['include_available_credit'] == 0 ? 'selected':'' ?>>No</option>
                        <option value="1" <?php echo $post['Client']['include_available_credit'] == 1 ? 'selected':'' ?>>Yes</option>
                    </select>
                </td>
            </tr>
                -->
            <tr>
                        <td><?php __('Include Available Credit'); ?></td>
                        <td>
                            <?php empty($post['Client']['include_available_credit'])?$au='false':$au='checked';  ?>
                            <?php echo $form->checkbox('include_available_credit', array('checked' => $au));?>
                        </td>
                    </tr>
            <!--
                    <tr>
                <td class="label label2">Include Payment History:</td>
                <td class="value value2">
                    <select style="width:180px;" name="data[Client][include_payment_history]" id="include_payment_history" class="input in-select select">
                        <option value="0" <?php echo $post['Client']['include_payment_history'] == 0 ? 'selected':'' ?>>No</option>
                        <option value="1" <?php echo $post['Client']['include_payment_history'] == 1 ? 'selected':'' ?>>Yes</option>
                    </select>
                    <span id="include_payment_history_days_panel">
                        <input type="text" name="data[Client][include_payment_history_days]" style="width:20px;" value="<?php echo $post['Client']['include_payment_history_days'] == '' ? '1' : $post['Client']['include_payment_history_days'] ?>"  />days
                    </span>
                </td>
            </tr>
            -->
                    <tr>
                        <td><?php __('Include Breakout Summary'); ?></td>
                        <td>
                            <?php empty($post['Client']['is_invoice_account_summary'])?$au='false':$au='checked';  ?>
                            <?php echo $form->checkbox('is_invoice_account_summary', array('checked' => $au));?>
                            <?php 
                            echo $form->input('invoice_use_balance_type',array('options'=>array(0=>'use actual balance', 1=>'use mutual balance'),'label'=>false ,'div'=>false,'type'=>'select', 'selected'=>$post['Client']['invoice_use_balance_type'] , 'style' => 'width:120px;'));
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td><?php __('Show Daily Usage'); ?></td>
                        <td>
                            <?php empty($post['Client']['is_show_daily_usage'])?$au='false':$au='checked';  ?>
                            <?php echo $form->checkbox('is_show_daily_usage', array('checked' => $au));?>
                        </td>
                    </tr>
                    <tr>
                        <td><?php __('Short Duration Call Surcharge detail'); ?></td>
                        <td>
                            <?php empty($post['Client']['is_short_duration_call_surcharge_detail'])?$au='false':$au='checked';  ?>
                            <?php echo $form->checkbox('is_short_duration_call_surcharge_detail', array('checked' => $au));?>
                        </td>
                    </tr>
                    
                    <tr>
                        <td><?php __('Include Summary of Payments'); ?></td>
                        <td>
                            <?php empty($post['Client']['invoice_include_payment'])?$au='false':$au='checked';  ?>
                            <?php echo $form->checkbox('invoice_include_payment', array('checked' => $au));?>
                        </td>
                    </tr>
                    <tr>
                        <td><?php __('Usage Detail Fields'); ?></td>
                        <td>
                            <?php
                                $usage_detail_fields = explode(',', $post['Client']['usage_detail_fields']);
                            ?>
                            <select name="data[Client][usage_detail_fields][]" multiple="multiple">
                                <!--<option value="code_name">Code Name</option>-->
                                <option value="completed_calls" <?php if (array_search('completed_calls', $usage_detail_fields) !== false) echo 'selected="selected"'; ?>>Completed Calls</option>
                                <option value="interstate_minute" <?php if (array_search('interstate_minute', $usage_detail_fields) !== false) echo 'selected="selected"'; ?>>Interstate Minute</option>
                                <option value="intrastate_minute" <?php if (array_search('intrastate_minute', $usage_detail_fields) !== false) echo 'selected="selected"'; ?>>Intrastate Minute</option>
                                <option value="indeterminate_minute" <?php if (array_search('indeterminate_minute', $usage_detail_fields) !== false) echo 'selected="selected"'; ?>>Indeterminate Minute</option>
                                <option value="total_minutes" <?php if (array_search('total_minutes', $usage_detail_fields) !== false) echo 'selected="selected"'; ?>>Total Minutes</option>
                                <option value="total_charges" <?php if (array_search('total_charges', $usage_detail_fields) !== false) echo 'selected="selected"'; ?>>Total Charges</option>
                            </select>
                        </td>
                    </tr>
</table>
</fieldset>


<fieldset>
    <legend><?php echo __('Automatic Report')?></span></legend>
    <table class="form">
        <tr>
            <td><?php __('Period') ?></td>
            <td>
                <?php 
                $send_periods = array(
                    -15 => '15M',
                    -30 => '30M',
                    1 => '1H',
                    2 => '2H',
                    4 => '4H',
                    6 => '6H',
                    8 => '8H',
                    12 => '12H',
                    24 => '24H',
                );
                
                echo $form->input('auto_summary_period',array('options'=>$send_periods,'label'=>false ,'div'=>false,'type'=>'select',  'selected'=>empty($post['Client']['auto_summary_period'])? '0' : $post['Client']['auto_summary_period']));
                ?>
            </td>   
                 </tr>
        
        <tr>
            <td><?php __('Send Hour') ?></td>
            <td>
                <?php 
                $send_times = array();
                for ($i = 0; $i<=23; $i++)
                {
                        $send_times[$i] = $i .":00";
                }
                echo $form->input('auto_summary_hour',array('options'=>$send_times,'label'=>false ,'div'=>false,'type'=>'select',  'selected'=>empty($post['Client']['auto_summary_hour'])? '0' : $post['Client']['auto_summary_hour']));
                ?>
            </td>
        </tr>
        <tr>
            <td><?php __('Time Zone') ?></td>
            <td style="text-align:left;">
                <?php 
                $azone_arr = array();
                for ($i = -12; $i<=12; $i++)
                {
                        $zone_str = $i<0 ? sprintf("-%02d:00", 0-$i) : sprintf("+%02d:00", $i);  		
                        $azone_arr[$zone_str] = 'GMT ' . $zone_str;
                }
                echo $form->input('auto_send_zone',array('options'=>$azone_arr,'label'=>false ,'div'=>false,'type'=>'select',  'selected'=>empty($post['Client']['auto_send_zone'])? '+00:00' : $post['Client']['auto_send_zone']));
                ?>
            </td>
        </tr>
        <tr>
            <td>Include CDR</td>
            <td>
                <?php echo $form->input('auto_summary_include_cdr',array('class'=>'in-decimal input in-checkbox','label'=>false,'div'=>false,'type'=>'checkbox','checked'=>$post['Client']['auto_summary_include_cdr']))?>
            </td>
        </tr>
        <tr>
            <td><?php __('Recipient') ?></td>
            <td>
               <?php
echo $form->input('auto_daily_balance_recipient',array('options'=>array('Partner\'s Billing Contact', 'Owner\'s Billing Contact', 'Both'),'label'=>false ,'div'=>false,'type'=>'select','selected'=>$post['Client']['auto_daily_balance_recipient']));
                ?>
            </td>
        </tr>
        <tr>
            <td><?php __('Send Daily Usage Summary') ?>:</td>
            <td  style="text-align: left;">
            <?php
                empty($post['Client']['is_auto_summary'])?$au='false':$au='checked';
            ?>
            <?php echo $form->input('is_auto_summary',array('class'=>'in-decimal input in-checkbox','label'=>false,'div'=>false,'type'=>'checkbox','checked'=>$au))?>
            <br />
            Non-Zero Only:
            <?php
                empty($post['Client']['auto_summary_not_zero'])?$au='false':$au='checked';
            ?>
            <?php echo $form->input('auto_summary_not_zero',array('class'=>'in-decimal input in-checkbox','label'=>false,'div'=>false,'type'=>'checkbox','checked'=>$au))?>
            <br />
            Group By:
            <?php
echo $form->input('auto_summary_group_by',array('options'=>array('By Country', 'By Code Name', 'By Code'),'label'=>false ,'div'=>false,'type'=>'select','selected'=>$post['Client']['auto_summary_group_by']));
                ?>
            </td>
        </tr>
        <tr>
            <td><?php __('Daily Balance Summary') ?>:</td>
            <td  style="text-align: left;">
                <?php
                empty($post['Client']['is_auto_balance'])?$au='false':$au='checked';
            ?>
            <?php echo $form->input('is_auto_balance',array('class'=>'in-decimal input in-checkbox','label'=>false,'div'=>false,'type'=>'checkbox', 'checked'=>$au))?>
            &nbsp;
            <?php __('Number of days'); ?>:
            <input type="text" name="data[Client][numer_of_days_balance]" style="width:30px;"  value="<?php echo $post['Client']['numer_of_days_balance'] ?>" />
            </td>
        </tr>
        <tr>
            <td>Daily CDR Generation</td>
            <td><input type="checkbox" name="data[Client][daily_cdr_generation]" id="daily_cdr_generation"  <?php if($post['Client']['daily_cdr_generation'] == 't') echo 'checked'  ?> /></td>
        </tr>
        <tr class="daily_cdr_generation_panel">
            <td><?php __('GMT') ?></td>
            <td>
              <?php 
                $azone_arr = array();
                for ($i = -12; $i<=12; $i++)
                {
                        $zone_str = $i<0 ? sprintf("-%02d:00", 0-$i) : sprintf("+%02d:00", $i);  		
                        $azone_arr[$zone_str] = 'GMT ' . $zone_str;
                }
                echo $form->input('daily_cdr_generation_zone',array('options'=>$azone_arr,'label'=>false ,'div'=>false,'type'=>'select', 'selected'=>$post['Client']['daily_cdr_generation_zone']));
              ?>
            </td>
        </tr>
        <tr class="daily_cdr_generation_panel">
            <td><?php __('CDR Type') ?></td>
            <td>
                <?php
echo $form->input('daily_cdr_generation_type',array('options'=>array('Incoming Calls', 'Outgoing Calls', 'Both'),'label'=>false ,'div'=>false,'type'=>'select','selected'=>$post['Client']['daily_cdr_generation_type']));
                ?>
            </td>
        </tr>
    </table>
</fieldset>


</td>
<td style="vertical-align: top;"><!-- COLUMN 3 -->
<fieldset>
<legend>	<?php echo $form->checkbox('is_panelaccess',array('checked'=>$post['Client']['is_panelaccess']))?>
<label for="cp_enabled">Carrier Self-Service Portal</label></legend>
<table class="form">
<col style="width:37%;"/><col style="width:62%;"/>
<tr>
    <td align="right"><?php echo __('login')?>:</td>
    <td align="left">
      <?php echo $form->input('login',array('label'=>false,'maxLength'=>'256','div'=>false,'type'=>'text','value'=>$post['Client']['login']))?>
    </td>
</tr>
<tr>
    <td align="right"><?php echo __('Password')?>:</td>
    <td align="left"> 
		<?php echo $form->input('password',array('label'=>false,'div'=>false,'type'=>'password','maxLength'=>'16','value'=>$post['Client']['password']));?></td>
</tr>
</table>
</fieldset>


<fieldset>
<legend> <?php echo __('Permission',true);?>&nbsp;</legend>
<table class="form">
<tr>
    <td colspan="2" class="value">
    <div class="cb_select" style="height:150px;">
	    <div>
		   		<?php empty($post['Client']['is_client_info'])?$au='false':$au='checked';echo $form->checkbox('is_client_info',array('checked'=>$au))?>
		       <label for="cp_modules-c_info"><?php echo __('isclientinfo')?></label>
	    </div>
	    <div>
		      <?php
						 		empty($post['Client']['is_invoices'])?$au='false':$au='checked';
								echo $form->checkbox('is_invoices',array('checked'=>$au))
						?>
		     	<label for="cp_modules-c_invoices"><?php echo __('isInvoices')?></label>
	     </div>
	     <div>
		      <?php
								empty($post['Client']['is_rateslist'])?$au='false':$au='checked';
								echo $form->checkbox('is_rateslist',array('checked'=>$au))
						?>
		      <label for="cp_modules-c_rates"><?php echo __('israteslist')?></label></div>
	     <!--<div>
	        <?php
						 		empty($post['Client']['is_mutualsettlements'])?$au='false':$au='checked';
								echo $form->checkbox('is_mutualsettlements',array('checked'=>$au))
						?>
	        <label for="cp_modules-c_stats_msa"><?php echo __('ismutualsettlements')?></label>
	     </div>-->
	     <div>
	        <?php
	 							empty($post['Client']['is_changepassword'])?$au='false':$au='checked';
								echo $xform->input('is_changepassword',array('checked'=>$au,'type'=>'checkbox'))
						?>
	        <label for="cp_modules-c_chpass"><?php echo __('ischangepassword')?></label>
	     </div>
	     <div>
	        <?php empty($post['Client']['is_cdrslist'])?$au='false':$au='checked'; echo $form->checkbox('is_cdrslist',array('checked'=>$au))?>
	        <label for="cp_modules-c_stats_cdrs">CDRs List</label>
	     </div>
    	</div>
    </td>
</tr>

</table>
</fieldset>
</td>
</tr>
</table>
<?php  if ($_SESSION['role_menu']['Management']['clients']['model_w']) {?>
<div id="form_footer">
    <input type="submit" value="<?php echo __('submit')?>"  class="input in-submit"/>
    <input type="reset"  value="<?php echo __('reset')?>"  class="input in-submit"/>
</div>
<?php }?>
		<?php echo $form->end();?>
</div>
<script type="text/javascript">
//特殊表单验证（只能为数字（Float））
jQuery(document).ready(
		function(){
				jQuery('#ClientAllowedCredit,#ClientNotifyAdminBalance').xkeyvalidate({type:'Ip'});		
				//jQuery('#ClientProfitMargin').xkeyvalidate({type:'Num'})
			//	jQuery('#ClientEmail,#ClientNocEmail,#ClientBillingEmail,#ClientRateEmail').xkeyvalidate({type:'Email'});	
				jQuery('#ClientName,#ClientCompany').xkeyvalidate({type:'strNum'});
				jQuery('#ClientTaxId').xkeyvalidate({type:'Num'});
				jQuery('#ClientIsDailyBalanceNotification').disabled({id:'#ClientNotifyClientBalance,#ClientNotifyAdminBalance'});
		}
);
</script>
<script type="text/javascript">
    jQuery(document).ready(function(){
        jQuery('#ClientIsDailyBalanceNotification').click(function() {
            if($(this).attr('checked')) {
                $('#is_daily_balance_panel').show();
            } else {
                $('#is_daily_balance_panel').hide();
            }
        });
      jQuery('#ClientEdit').submit(function(){
        		te= true;
			      if(jQuery('#ClientName').val()==''){
			    	  	jQuery('#ClientName').addClass('invalid');
							 	jQuery.jGrowl(' name is not null!',{theme:'jmsg-error'});
							 	te=false;
	      					}
                                                
                                                if(/\-/.test(jQuery('#ClientAllowedCredit').val())) {
            jQuery('#ClientAllowedCredit').addClass('invalid');
            jQuery.jGrowl('Allowed Credit cannot be a negative number!',{theme:'jmsg-error'});
            te = false;
        }
        
              if(/[^0-9A-Za-z-\_\.]+/.test(jQuery('#ClientAllowedCredit').val())){
          	        jQuery('#ClientLogin').addClass('invalid');
          	        jQuery.jGrowl(' Login must contain alphanumeric characters only.',{theme:'jmsg-error'});
        			        te = false;
                                       }
                                       
               
               if($('jQueryClientCurrencyId').val() == '') {
                     jQuery('#ClientCurrencyId').addClass('invalid');
												 	jQuery.jGrowl('You must create Currency first!',{theme:'jmsg-error'});
									 	te=false;
                                     }
              
if(jQuery('#ClientSccPercent').val()>100){
      jQuery.jGrowl('Percent, shuld not bigger than 100!',{theme:'jmsg-error'});
      te=false; 
 }
if(jQuery('#ClientIncludeTax').attr('checked'))
            {
                if(! /\d+|\./.test(jQuery('#ClientTax').val())){
                    jQuery('#ClientTax').addClass('invalid');
                    jQuery.jGrowl('ClientTax must contain numeric characters only!',{theme:'jmsg-error'});
                    te=false; 
                }
            }
   
               if(!/^-?[0-9]+%?$/.test(jQuery('#ClientNotifyClientBalance').val())&& $('#ClientIsDailyBalanceNotification').attr('checked')){
            	   jQuery('#ClientNotifyClientBalance').addClass('invalid');
            	   jQuery.jGrowl('Notify client Balance must contain numeric characters only',{theme:'jmsg-error'});
                 te=false;
                                      }
                                      
                                      if (Number(jQuery('#ClientNotifyClientBalance').val()) > 100 && $('#ClientNotifyClientBalanceType').val() == '1') {
                                          jQuery('#ClientNotifyClientBalance').addClass('invalid');
            	   jQuery.jGrowl('Notify client Balance must not greater than 100',{theme:'jmsg-error'});
                   te=false;
                                      }
                                      
               if(jQuery('#ClientIsPanelaccess').attr('checked')){
	               if(jQuery('#ClientLogin').val()==''){
	               	 jQuery('#ClientLogin').addClass('invalid');
	                  jQuery.jGrowl(' login name is not null',{theme:'jmsg-error'});
	                  te = false;
	                                        }
               					  }
                                                  /*
               if(!jQuery.xkeyvalidate('#ClientNotifyAdminBalance',{type:'Ip'})||/^-{2,}[0-9]+$/.test(jQuery('#ClientNotifyAdminBalance').val())){
            	   jQuery('#ClientNotifyAdminBalance').addClass('invalid');
            	   jQuery.jGrowl('Notify admin must contain numeric characters only',{theme:'jmsg-error'});
                  te=false;
                                          }
                                          */
               if(!jQuery.xkeyvalidate('#ClientAllowedCredit',{type:'Ip'})|| /^-{2,}[0-9]+$/.test(jQuery('#ClientAllowedCredit').val())){
            	   jQuery('#ClientAllowedCredit').addClass('invalid');
            	   jQuery.jGrowl('Notify client must contain numeric characters only',{theme:'jmsg-error'});
                  te=false;
                                          }
               /*                           
               if(!jQuery.xkeyvalidate('#ClientProfitMargin',{type:'Ip'})|| /^-{2,}[0-9]+$/.test(jQuery('#ClientProfitMargin').val())){
            	   jQuery('#ClientProfitMargin').addClass('invalid');
            	   jQuery.jGrowl('Min. Profitability must contain numeric characters only',{theme:'jmsg-error'});
                  te=false;
                                          }
                                          */
               
               if(jQuery('#ClientEmail').val()!=''){
                   if(! check_email(jQuery('#ClientEmail').val())){
                    	jQuery('#ClientEmail').addClass('invalid');
                    	jQuery.jGrowl('Main e-mail must Email and split by ;',{theme:'jmsg-error'});
                    	te=false;
			                                   	}
            	}
		          		if(jQuery('#ClientNocEmail').val()!=''){
			          			 if(! check_email(jQuery('#ClientNocEmail').val())){
			                	jQuery('#ClientNocEmail').addClass('invalid');
			               	 jQuery.jGrowl('NOC e-mail must Email and split by ;',{theme:'jmsg-error'});
			                	te=false;
			          			                     }
		            					}
		          		if(jQuery('#ClientBillingEmail').val()!='' ){
		          			if(! check_email(jQuery('#ClientBillingEmail').val())){
		                    	jQuery('#ClientBillingEmail').addClass('invalid');
		                   	 jQuery.jGrowl('Billing e-mail must Email and split by ;',{theme:'jmsg-error'});
		                    	te=false;
		          			                     }
		                				}
		          		if(jQuery('#ClientRateEmail').val()!='' && !jQuery.xkeyvalidate('#ClientRateEmail',{type:'Email',noMessage:true})){
			          			if(! check_email(jQuery('#ClientRateEmail').val())){
			                    	jQuery('#ClientRateEmail').addClass('invalid');
			                   	 jQuery.jGrowl('Rates e-mail must Email and split by ;',{theme:'jmsg-error'});
			                    	te=false;
			          		                      	}
		                				}
		          		if(jQuery('#ClientIsPanelaccess').attr('checked')){
			             var data=jQuery.ajaxData({'url':"<?php echo $this->webroot?>clients/check_login/<?php echo array_keys_value($this->params,'pass.0')?>?login="+jQuery('#ClientLogin').val(), 'type':'POST'});
                                     if(data.indexOf('false') !== -1){
			                jQuery.jGrowlError("login name is repeat!");
			                te=false;
			             					}
		          	               	}
		            return te;
              });  
         });
</script>
<script type="text/javascript">
<!--
//ClientPaymentTermId,#ClientInvoiceFormat,#ClientAttachCdrsList,#ClientCdrListFormat,#ClientLastInvoiced
   jQuery(document).ready(function(){
       
       jQuery('#ClientPaymentTermId').change(function() {
           var $this = $(this);
           var payment_term_id = $(this).val();
           $.ajax({
               'url' : '<?php echo $this->webroot ?>clients/get_payment_term_type',
               'type' : 'POST',
               'dataType' : 'json',
               'data' : {'payment_term_id' : payment_term_id},
               'success' : function(data) {
                   if (data[0][0]['type'] == 1) {
                       $this.parent().parent().next().show();
                   } else {
                       $this.parent().parent().next().hide();
                   }
               }
           });
       }).trigger('change');
       
       jQuery('#ClientMode').change(function() {
           if(jQuery(this).val() == '2') {
                jQuery('#ClientAllowedCredit').parent().parent().show();
                jQuery('#unlimited_panel').show();
            } else {
                jQuery('#ClientAllowedCredit').val(0).parent().parent().hide();
                jQuery('#unlimited_panel').hide();
                jQuery('#ClientUnlimitedCredit').attr('checked', false);
            }
       });
       
      
      $('#ClientAutoSummaryPeriod').change(function() {
          var $this = $(this);
          if ($this.val() == '24')
              $this.parents('tr').next().show();
          else
              $this.parents('tr').next().hide();
      }).trigger("change");
      
      $('#ClientIsInvoiceAccountSummary').change(function() {
           if ($(this).is(':checked')) {
               $('#ClientInvoiceUseBalanceType').show();
           } else {
               $('#ClientInvoiceUseBalanceType').hide();
           }
       }).trigger("change");
      
       jQuery('#ClientIncludeTax').change(function() {
           if(jQuery(this).attr('checked'))
           {
               $('#tax_area').show();
           }
           else
           {
               $('#tax_area').hide();
           }
       });
       jQuery('#ClientIncludeTax').change();
       jQuery('#include_payment_history').change(function() {
            if($(this).val() == '0') {
                $('#include_payment_history_days_panel').hide();
            } else {
                $('#include_payment_history_days_panel').show();
            }
        });
        jQuery('#include_payment_history').change();
       jQuery('#ClientMode').change();
      jQuery('#daily_cdr_generation').change(function() {
            if($(this).attr('checked')) {
                $('.daily_cdr_generation_panel').show();
            } else {
                $('.daily_cdr_generation_panel').hide();
            }
        }).trigger('change');
        jQuery('#ClientAutoInvoicing').change(function(){
        	   checkCB();
                      });
        jQuery('#ClientIsPanelaccess').change(function(){
           	checkCB();
                     });
					checkCB();
					jQuery('#ClientCurrencyId').change(
						function(){
							jQuery('label.money').html(jQuery(this).find('option:selected').html());
						}
					).change(); 
        });
//-->
</script>