		<?php 
			$login_type = $session->read('login_type');
			$project_name=Configure::read('project_name');
			
			if (PRI && $session->read('login_type') != 3)
			{
			$role_menu = $_SESSION['role_menu'];	//var_dump($role_menu);
			if (!empty($role_menu))
			{
				echo "<ul id=\"topmenu-menu\" class=\"topmenu-left\">";
				foreach ($role_menu as $k=>$v)
				{
					echo "<li><span>", __($k), "</span>\r\n";
					echo "<ul style=\"display: none;\">";						
					foreach ($v as $k1=>$v1)
					{
						if (!empty($v1['pri_url']) && $v1['model_r'] == 't')
						{
							echo "<li><a href=\"", $this->webroot, $v1['pri_url'], "\">", __($v1['pri_val']), "</a></li>";
						}
					}
					
					echo "</ul>\r\n</li>";
				}
				echo "</ul>";
			}
			}
                        
                        
                        
			//if (0) { 
			if (!PRI && $login_type==1){
				
			
				?>
		<ul id="topmenu-menu" class="topmenu-left">
			<?php
		 extract($_SESSION['role_menu']);	
		if($is_carriers||$is_transaction||$is_mutual_settlements||$is_invoices||$is_payment){?>
		
			<li><span><?php  __('Management')?></span>
			<ul style="display: none;">
			
			<?php 
     
				if($is_carriers){?>
				<li><a href="<?php echo $this->webroot?>clients/index"><?php  __('Carriers')?></a></li>
				<?php }?>
				
				<?php  if($is_transaction){?>
				<li><a href="<?php echo $this->webroot?>transactions/client_tran_view/"><?php __('Transaction')?></a></li>
				<?php }?>
				<?php  if($is_mutual_settlements){?>
				<li><a href="<?php echo $this->webroot?>clientmutualsettlements/summary_reports">	<?php __('MutualSettlements')?></a></li>
			<?php }?>
			<?php  if($is_invoices){?>
			<?php if($project_name=='exchange'){?>
				<li><a href="<?php echo $this->webroot?>invoices/view">	<?php __('Invoices')?></a></li>
			<?php }?>
			<?php if($project_name=='wholesales'||$project_name=='partition'){?>
				<li><a  href="<?php echo $this->webroot?>pr/pr_invoices/view">	<?php __('Invoices')?></a></li>
			<?php }?>
				<?php }?>
				<?php  if($is_payment){?>
				<li><a href="<?php echo $this->webroot?>transactions/client_pay_view">	<?php __('payment')?>	</a></li>
				<?php }?>
			<?php  if($is_unpaid_bills){?>
				<li><a href="<?php echo $this->webroot?>bills/summary"><?php echo __("Unpaid Bills")?></a></li>	
				<?php }?>			
				<?php  if($is_unpaid_bills){?>
				<!--<li><a href="<?php echo $this->webroot?>clients/wizard"><?php echo __("Wizard")?></a></li>	
				--><?php }?>
                
				<li><a href="<?php echo $this->webroot?>finances/view"><?php echo __("Finance")?></a></li>
	          
   			<li><a href="<?php echo $this->webroot?>mutual_statements/summary_reports"><?php echo __("Mutual Statement")?></a></li>
	            
                
                			
			</ul>
	</li>
	<?php }?>
	
	
		
		
	<?php 	if($is_spam_report||$is_location_report||$is_origterm||$is_summary_report||$is_usage_report||$is_cdr_list){?>
	<li>
		<span><?php __('Statistics')?></span>
		<ul style="display: none;">
		<?php  if($is_spam_report){?>
			<li><a href="<?php echo $this->webroot?>cdrreports/summary_reports/spam/">	Spam Report</a></li>
			<?php }?>		
				
				<?php  if($is_location_report){?>
			<li><a	href="<?php echo $this->webroot?>locationreports/summary_reports"><?php __('LocationReport')?></a></li>
			<?php }?>		
			<?php  if($is_origterm){?>
			<li><a href="<?php echo $this->webroot?>origtermstatis/summary_reports"><?php __('Orig-TermReport')?></a></li>
			<?php }?>		
			
				<?php  if($is_summary_report){?>
			<li><a href="<?php echo $this->webroot?>clientsummarystatis/summary_reports"><?php  __('SummaryReport')?></a></li>
			<?php }?>		
			
				<?php  if($is_usage_report){?>
			<li><a href="<?php echo $this->webroot?>ratereports/summary_reports"><?php __('UsageReport')?></a></li>
					<?php }?>		
			
				<?php  if($is_cdr_list){?>
			<li><a href="<?php echo $this->webroot?>cdrreports/summary_reports">	<?php __('CDRsList')?></a></li>
					<?php }?>		
					
				<?php  if($is_qos_report){?>
			<li><a href="<?php echo $this->webroot?>monitorsreports/globalstats"><?php __('QoSReport')?></a></li>
					<?php }?>		
					
					
				<?php  if($is_disconnect_cause){?>
			<li><a href="<?php echo $this->webroot?>disconnectreports/summary_reports"><?php __('DisconnectCauses')?></a></li>
				<?php }?>		
				
				
			<?php  if($is_billing_mismatch){?><!--
			
			<li><a href="<?php echo $this->webroot?>mismatchesreports/mismatches_report"><?php __('MismatchesReport')?></a></li>
			
			
				--><?php }?>		
		
		<?php  if($is_active_call){?>
		<li><a href="<?php echo $this->webroot?>realcdrreports/summary_reports"><?php __('Active Call Report ')?></a></li>
			<?php }?>
					
			<?php  if($is_termination_report){?>
			<li><a href="<?php echo $this->webroot?>gatewaygroups/egress_report"><?php __('TerminationReport')?>	</a></li>
			<?php }?>		
			
				<li><a	href="<?php	echo $this->webroot?>cdrreports/rerating">	<?php echo __('CDR Rerating',true);?>				</a></li>
					<li><a	href="<?php	echo $this->webroot?>bandwidths/summary_reports">	<?php echo __('Bandwidth Report',true);?>		</a></li>
						<li><a	href="<?php	echo $this->webroot?>profitreports/summary_reports">	<?php echo __('Profitability Analysis',true);?>	</a></li>
						
					<li><a	href="<?php	echo $this->webroot?>usagedetails/orig_summary_reports">	<?php echo __('Daily Usage Detail Report',true);?>		</a></li>
		</ul>
	</li>
	<?php }?>
	
	
		<?php 	if($is_call_simulation||$is_ingress_trunk_simulation||$is_egress_trunk_simulation||$is_sip_capture||$is_rates_analysis){?>
	<li><span><?php	echo __('Tools')?></span>
		<ul style="display: none;">
			<?php  if($is_call_simulation){?>
					<li><a	href="<?php		echo $this->webroot?>simulatedcalls/simulated_call">	<?php echo __ ('CallSimulation')?>	</a></li>
				<?php }?>		
				
				<?php  if($is_ingress_trunk_simulation){?>
			<li><a href="<?php	echo $this->webroot?>testdevices/test_device"><?php		__ ( 'IngressTrunkSimulation' )?></a></li>
			<?php }?>		
			<?php  if($is_egress_trunk_simulation){?>
			<li><a href="<?php	echo $this->webroot?>audiotests/audio_test"><?php	__ ( 'EgressTrunkSimulation' )?></a></li>
			<?php }?>		
		
			<?php  if($is_sip_capture){?>
			<li><a href="<?php	echo $this->webroot?>sipcaptures/sip_capture">	<?php		__ ( 'SIPCapture' )?>						</a></li>
			<?php }?>		
			
			
			<?php  if($is_rates_analysis){?>
				<li><a	href="<?php	echo $this->webroot?>analysis/view">	<?php		__ ( 'RatesAnalysis' )?>				</a></li>
			<?php }?>
			
		<!--
        <li>
        <a	href="<?php	//echo $this->webroot?>sipcaptures/ladder">Ladder</a>
        </li>
        -->
		</ul>
	</li>
	<?php }?>
	
	
	
	
	<?php 	if($is_digit_mapping||$is_trunk||$is_dynamic_routing||$is_static_route_table||$is_block_list||$is_routing_plan){?>
	
	<li><span><?php __('Routing')?></span>
		<ul style="display: none;">
		
			<?php  if($is_digit_mapping){?>
			<li><a href="<?php echo $this->webroot?>digits/view"><?php __('DigitTranslation')?>	</a></li>
		<?php }?>
			<?php  if($is_trunk){?>
			<?php if($project_name=='exchange'){?>
				<li><a href="<?php echo $this->webroot?>gatewaygroups/view_egress"><?php __('Trunks')?> </a></li>
			<?php }else{?>
				<li><a href="<?php echo $this->webroot?>prresource/gatewaygroups/view_egress"><?php __('Trunks')?> </a></li>
			<?php }?>
			<?php }?>
			
			
			<?php  if($is_dynamic_routing){?>
			<li><a href="<?php echo $this->webroot?>dynamicroutes/view"><?php __('DynamicRoute')?> </a></li>
		<?php }?>
		
		
			<?php  if($is_static_route_table){?>
			<li><a href="<?php echo $this->webroot?>products/product_list"> <?php __('StaticRoute')?></a></li>
			<?php }?>
			
			
				<?php  if($is_block_list){?>
			<li><a href="<?php echo $this->webroot?>blocklists/index"> <?php __('BlockList')?> </a></li>
		
		<?php }?>
		
			<?php  if($is_routing_plan){?>
			<li><a href="<?php echo $this->webroot?>routestrategys/strategy_list"><?php __('RoutingStrategies')?></a></li>
			<?php }?>
			
		</ul>
	</li>
	<?php }?>
	
	
		<?php 	if($is_task_schedulers||$is_currency||$is_active_web_session||$is_payment_term||$is_jurisdiction||$is_capicity||$is_rate_table||$is_code_deck||$is_time_profile){?>
	
	<li><span><?php __('System')?></span>
		<ul style="display: none;">
		
		<?php  if($is_active_web_session){?>
			<li><a href="<?php echo $this->webroot?>websessions/view">	Active WEB Session</a></li>
                        <li><a href="<?php echo $this->webroot?>gatewaygroups/add_server">	VoIP Gateway</a></li>
			<?php }?>
			
			<?php  if($is_service_charge){?>
			<?php if($project_name=='exchange'){?>
			<li><a href="<?php echo $this->webroot?>servicecharges/view">	<?php __("Service Charge");?></a></li>
			<?php }?>
			<?php }?>
			
			
			<?php  if($is_payment_term){?>
			<li><a href="<?php echo $this->webroot?>paymentterms/payment_term">	<?php __('Payment Term')?>	</a></li>
		<?php }?>
		
		
		<?php  if($is_jurisdiction){?>
			<li><a	href="<?php echo $this->webroot?>jurisdictionprefixs/view">	<?php __('Jurisdiction')?></a></li>
			<?php }?>
			
			
			<?php  if($is_capicity){?>
			<li><a href="<?php echo $this->webroot?>systemlimits/view"> <?php __('Capacity')?></a></li>
			<?php }?>
			
		
			<?php  if($is_rate_table){?>
			<li><a href="<?php echo $this->webroot?>rates/rates_list"> <?php __('RateTable')?> </a></li>
			<?php }?>



<?php  if($is_voip_gateway){?>
<?php if($project_name!='partition'){?>
			<li><a href="<?php echo $this->webroot?>gatewaygroups/add_server/"><?php __('VoIPGateway')?>	</a></li>
			<?php }?>
		<?php }?>
			
			
			
			<?php  if($is_code_deck){?>
			<li><a href="<?php echo $this->webroot?>codedecks/codedeck_list">	<?php __('CodeDeck')?> </a></li>
			<?php }?>
			
		
		<?php  if($is_time_profile){?>
			<li><a href="<?php echo $this->webroot?>timeprofiles/profile_list">	<?php __('TimeProfile')?> </a></li>
			<?php }?>
		
		
		<?php  if($is_currency){?>
			<li><a href="<?php echo $this->webroot?>currs/index"> <?php __('Currency')?> </a></li>
		
		<?php }?>
                        
		<?php  if($is_task_schedulers){?><!--
			<li><a href="<?php echo $this->webroot?>schedulers/schedulers_list">	<?php __('TaskSchedulers')?> </a></li>
			--><?php }?>
			
				<?php  if($is_trouble_shoot){?>
				<?php if($project_name!='partition'){?>
				<li><a href="<?php echo $this->webroot?>systems/trouble_shoot">Trouble Shoot</a></li>
			<?php }?>
			<?php }?>
			
			<?php  if($is_mail_template){?>
			<li><a href="<?php echo $this->webroot?>mailtmps/mail"> <?php __('EmailTemplate')?> </a></li>
<?php }?>
		</ul>
	</li>
			<?php }?>
	
			<?php 	if($is_role||$is_user||$is_change_password||$is_setting||$is_import_log||$is_export_log||$is_lrn_setting){?>
	
	<li><span><?php __('Configuration')?></span>
	<ul style="display: none;">
	
		<?php  if($is_role){?>
		<li><a href="<?php echo $this->webroot?>roles/view"><?php __('Roles')?> </a></li>
		<?php }?>
		
			<?php  if($is_user){?>
		<li><a href="<?php echo $this->webroot?>users/index">  <?php __('Users')?></a></li>
		<?php }?>
		

		
			
				<?php  if($is_event){?>
			<?php if($project_name!='partition'){?>
		<li><a href="<?php echo $this->webroot?>eventlogs/events_list"><?php __('Events')?> </a></li>
		<?php }?>
		<?php }?>
		
		
		<?php  if($is_change_password){?>
		<li><a href="<?php echo $this->webroot?>users/changepassword"> <?php __('ChangePassword')?> </a></li>
		<?php }?>
		
		
		<?php  if($is_setting){?>
		<li><a href="<?php echo $this->webroot?>systemparams/view"><?php __('Settings')?> </a></li>
		<?php }?>
		
		
	<?php  if($is_import_log){?>
		<li><a href="<?php echo $this->webroot?>import_export_log/import"> <?php __('ImportLog')?> </a></li>
	<?php }?>
	
	
	
	<?php  if($is_export_log){?>
		<li><a href="<?php echo $this->webroot?>import_export_log/export"><?php __('ExportLog')?> </a></li>
		<?php }?>
		
		
		<?php  if($is_cdr_backup){?>
		<!--<li><a href="<?php echo $this->webroot?>cdrbackups/backup"> <?php __('CDRArchives')?> </a></li>
		-->
		
		<!--<li><a href="<?php echo $this->webroot?>cdr_archive/view"> <?php __('CDRArchives')?> </a></li>
--><?php }?>

<?php  if($is_lrn_setting){?>
		<li><a href="<?php echo $this->webroot?>lrnsettings/view"> <?php __('LRNSetting')?></a></li>
		<?php }?>
		

		<li><a href="<?php echo $this->webroot?>tasks/view"> <?php __('Task Schedule')?></a></li>
       
		<li><a href="<?php echo $this->webroot?>clients/credit_view"> <?php __('Credit Application')?></a></li>
       
       
       <?php if ($is_role) {?>
   <li><a href="<?php echo $this->webroot?>logos/index"> <?php __('Change Logo')?></a></li>
   <?php }?>
   <li><a href="<?php echo $this->webroot?>sysmodules/view_sysmodule"><?php echo __("Sys Module")?></a></li>
   
   <li><a href="<?php echo $this->webroot?>sysrolepris/view_sysrolepri"><?php echo __("Sys Role Pri")?></a></li>
	</ul>
	</li>
	<?php }?>
	
	
	
	<?php  if($is_ticket){?>
	
			<li style=" display:none;"><span><?php __('Support')?></span>
			<ul style="display: none;">
				<li><a  href="http://support.intlcx.com" target="_blank"><?php __('Ticket')?></a></li>
			</ul>
		</li>
	<?php }?>

<!-- 				alert -->	


	<li><span><?php __('Alert')?></span>
	<ul style="display: none;">
	
		<?php  if($is_sip_capture){?>
		<li><a href="<?php echo $this->webroot?>alerts/condition"><?php __('Condition')?> </a></li>
		<?php }?>
		<?php  if($is_sip_capture){?>
		<li><a href="<?php echo $this->webroot?>alerts/action"><?php __('Action')?> </a></li>
		<?php }?>
		<?php  if($is_sip_capture){?>
		<li><a href="<?php echo $this->webroot?>alerts/rule"><?php __('Rule')?> </a></li>
		<?php }?>
		<?php  if($is_sip_capture){?>
		<li><a href="<?php echo $this->webroot?>alerts/report"><?php __('Report')?> </a></li>
		<?php }?>
		<?php  if($is_sip_capture){?>
		<li><a href="<?php echo $this->webroot?>alerts/view_log"><?php __('View Execution Log')?> </a></li>
		<?php }?>
	</ul>
	</li>	

	<?php if($project_name=='exchange'){
		if($is_buy_select_country||$is_search_private_buy||$is_buy_confirm_order){
		?>
	<li><span><?php __('Buy')?></span>
	<ul style="display: none;">
	
	<?php  if($is_buy_select_country){?>
		<li><a href="<?php echo Router::url(array('plugin'=>'order','controller'=>"order_browsers",'action' => 'sell'))?>"><?php __("Select Country");?></a></li>
	<?php }?>
	<?php  if($is_search_private_buy){?>
		<li><a href="<?php echo Router::url(array('plugin'=>'order','controller'=>"order_browsers",'action' => 'private_buy'))?>"><?php __("Search Private Buy")?></a></li>
	<?php }?>
	
	<?php  if($is_buy_confirm_order){?>
		<li><a href="<?php echo Router::url(array('plugin'=>'order','controller'=>"order_contracts",'action' => 'manage','Buy'))?>"><?php __("All Confirmed Orders");?></a></li>
	<?php }?>
	
	</ul>
	</li>
	<?php }?>
<?php if($is_sell_select_country||$is_search_private_sell||$is_sell_confirm_order){?>	
	<li><span><?php __('Sell')?></span>
	<ul style="display: none;">
		<?php  if($is_sell_select_country){?>
			<li><a  href="<?php echo Router::url(array('plugin'=>'order','controller'=>"order_browsers",'action' => 'buy'))?>"><?php __("Select Country");?></a></li>
		<?php }?>
		<?php  if($is_search_private_sell){?>
			<li><a href="<?php echo Router::url(array('plugin'=>'order','controller'=>"order_browsers",'action' => 'private_sell'))?>"><?php __("Search Private Sell");?></a></li>
		<?php }?>
		<?php  if($is_sell_confirm_order){?>
		<li><a href="<?php echo Router::url(array('plugin'=>'order','controller'=>"order_contracts",'action' => 'manage','Sell'))?>"><?php __("All Confirmed Orders");?></a></li>
		<?php }?>
	</ul>
	</li>
	<?php }}?>
	
	
	
	<!--exchange manage -->
	
        <li><span><?php __('Exchange Manage')?></span>
	<ul style="display: none;">
		<?php  if($is_cdr_list){?>
		<li><a href="<?php echo $this->webroot?>users/registration">  <?php __('Manage Registration')?></a></li>
		<?php }?>
		<li><a href="<?php echo $this->webroot?>order/user_trunks/view">  <?php __('Trunk Management')?></a></li>
		<li><a href="<?php echo $this->webroot?>order/order_browsers/buy">  <?php __('Order Management')?></a></li>
		<li><a href="<?php echo $this->webroot?>order/sellers/view">  <?php __('Direct Seller Enrollment')?></a></li>
	</ul>
	</li>
	
</ul>	
<?php }?>