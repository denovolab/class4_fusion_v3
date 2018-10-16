<style type="text/css">
.list .help {
    color: #7D8084;
    font-size: 0.86em;
    padding-left: 5px;
    width: 48%;
}

.list .label, .list .value {width:25%;}
span.red {color:red;}
</style>
<script type="text/javascript">

$('#timezone2').val('<?php  echo $post[0][0]['sys_timezone'];?>');
function postsysparameter(){
	var currency = $('#currency').val();
	var timezone = $('#timezone2').val();

	var mail_host = $('#mail_host').val();
	var mail_from = $('#mail_from').val();
	
	var yourpay_store_number = $('#yourpay_store_number').val();
	var paypal_account = $('#paypal_account').val();
	
	var ftp_username = $('#ftp_username').val();
	var ftp_pass = $('#ftp_pass').val();
	var system_admin_email = $('#system_admin_email').val();
	var dateFormat = $('#dateFormat').val();
	var datetimeFormat = $('#datetimeFormat').val();
	var csv_delimiter = $('#csv_delimiter').val();
	var invoices_tplNo = $('#invoices_tplNo').val();
	var invoices_lastNo = $('#invoices_lastNo').val();
	var invoices_fields = $('#invoices_fields').val();
	var invoices_delay = $('#invoices_delay').val();
	var invoices_separate = $('#invoices_separate').val();
	var invoices_cdr_fields = $('#invoices_cdr_fields').val();
	var dr_period = $('#dr_period').val();
	var radius_log_routes = $('#radius_log_routes').val();
        var realm = $('#realm').val();
        var workstation = $('#workstation').val();
        var is_preload = $("#is_preload").val();
        
        var qos_sample_period = $('#qos_sample_period').val();
        var minimal_call_attempt_required = $('#minimal_call_attempt_required').val();
        var low_call_attempt_handling = $('#low_call_attempt_handling').val();
	
	var lowBalance_period = $('#lowBalance_period').val();
	var events_deleteAfterDays = $('#events_deleteAfterDays').val();
	var stats_rotate_delay = $('#stats_rotate_delay').val();
	var rates_deleteAfterDays = $('#rates_deleteAfterDays').val();
	var cdrs_deleteAfterDays = $('#cdrs_deleteAfterDays').val();
	var logs_deleteAfterDays = $('#logs_deleteAfterDays').val();
	
	var backup_period = $('#backup_period').val();
	var backup_leave_last = $('#backup_leave_last').val();
        
        var smtp_secure = $('#smtp_secure').val();
        var report_count = $('#report_count').val();
	var smtphost = $('#smtphost').val();
	var smtpport = $('#smtpport').val();
	var emailusername = $('#emailusername').val();
	var emailpassword = $('#emailpassword').val();
	var fromemail = $('#fromemail').val();
	var emailname = $('#emailname').val();
	var loginemail=$('#loginemail').val();
	var switch_port=$('#switch_port').val();
	var switch_ip=$("#switch_ip").val();
	var pdf_tpl=$("#pdf_tpl").val();
	var finance_email=$("#finance_email").val();
	var noc_email=$("#noc_email").val();
        var withdraw_email=$("#withdraw_email").val();
	var tpl_number=$("#tpl_number").val();
        var search_code_deck = $('#search_code_deck').val();
        var welcome_message = $('#welcome_message').val();
        var landing_page = $('#landing_page').val();
        var bar_color = $('#bar_color').val();
        var inactivity_timeout = $('#inactivity_timeout').val();
        var switch_alias = $('#switch_alias').val();
        var ingress_pdd_timeout = $('#ingress_pdd_timeout').val();
        var egress_pdd_timeout = $('#egress_pdd_timeout').val();
        var ring_timeout = $('#ring_timeout').val();
        var call_timeout = $('#call_timeout').val();
        
        if (ingress_pdd_timeout == '') {
            jQuery.jGrowl('Ingress PDD Timeout is required!',{theme:'jmsg-error'});
            return false;
        }
        if (egress_pdd_timeout == '') {
            jQuery.jGrowl('Egress PDD Timeout is required!',{theme:'jmsg-error'});
            return false;
        }
        if (ring_timeout == '') {
            jQuery.jGrowl('Ring Timeout is required!',{theme:'jmsg-error'});
            return false;
        }
        if (call_timeout == '') {
            jQuery.jGrowl('Call Timeout Timeout is required!',{theme:'jmsg-error'});
            return false;
        }
	
	$.post("<?php echo $this->webroot?>/systemparams/ajax_update.json",
	{
		withdraw_email:withdraw_email,
                noc_email:noc_email,
		finance_email:finance_email,
		pdf_tpl:pdf_tpl,
		tpl_number:tpl_number,
		switch_ip:switch_ip,
		switch_port:switch_port,
		system_admin_email:system_admin_email,
		loginemail:loginemail,
		emailname:emailname,
		fromemail:fromemail,
		emailpassword:emailpassword,
		emailusername:emailusername,
		smtpport:smtpport,
		smtphost:smtphost,
		currency:currency,
		timezone:timezone,
		mail_host:mail_host,
		mail_from:mail_from,
                report_count:report_count,
		ftp_username:ftp_username,
		ftp_pass:ftp_pass,
		dateFormat:dateFormat,
		datetimeFormat:datetimeFormat,
		csv_delimiter:csv_delimiter,
		invoices_tplNo:invoices_tplNo,
		invoices_lastNo:invoices_lastNo,
		invoices_fields:invoices_fields,
		invoices_delay:invoices_delay,
		invoices_separate:invoices_separate,
		smtp_secure:smtp_secure,
		invoices_cdr_fields:invoices_cdr_fields,
		dr_period:dr_period,
		radius_log_routes:radius_log_routes,
		lowBalance_period:lowBalance_period,
		events_deleteAfterDays:events_deleteAfterDays,
		stats_rotate_delay:stats_rotate_delay,
		events_deleteAfterDays:events_deleteAfterDays,
		rates_deleteAfterDays:rates_deleteAfterDays,
		search_code_deck:search_code_deck,
		cdrs_deleteAfterDays:cdrs_deleteAfterDays,
		logs_deleteAfterDays:logs_deleteAfterDays,
		backup_period:backup_period,
                realm:realm,
                workstation:workstation,
		backup_leave_last:backup_leave_last,
                qos_sample_period:qos_sample_period,
                minimal_call_attempt_required:minimal_call_attempt_required,
                low_call_attempt_handling:low_call_attempt_handling,
                welcome_message:welcome_message,
                landing_page:landing_page,
                bar_color:bar_color,
                inactivity_timeout:inactivity_timeout,
                is_preload:is_preload,
                yourpay_store_number:yourpay_store_number,
                paypal_account:paypal_account,
                switch_alias:switch_alias,
                ingress_pdd_timeout:ingress_pdd_timeout,
                egress_pdd_timeout:egress_pdd_timeout,
                ring_timeout:ring_timeout,
                call_timeout:call_timeout
	        },
   function(text){ 
      showMessages("[{'code':'201','msg':'<?php __('Systemparametershavebeenupdatedyouneedtorestartthesystemtotakeeffect')?>'}]");},

        'json');
        
     // window.setTimeout("window.location.reload();", 3000);


    }


</script>

<div id="title">
  <h1>
    <?php __('Configuration')?>
    &gt;&gt;<?php echo __('syssetting')?></h1>
  <ul id="title-menu">
    <li> </li>
  </ul>
</div>
<div id="container"> 
        <table class="list list-form" >
            <thead>
                <tr>
                    <td colspan="3" class="last"><?php __('System Logo')?></td>
                </tr> 
            </thead>
            <tbody>
                <tr>
                    <td class="label"><?php __('Change Logo File') ?>:</td>
                    <td class="value">
                        <form enctype="multipart/form-data" action="<?php echo $this->webroot; ?>systemparams/change_logo" method="post">
                        <input type="file" name="logoimg" style="" />
                        <input type="submit" value="Upload" style="" />
                        </form>
                    </td>
                    <td class="help">
                        <img src="<?php echo $logo; ?>" />
                    </td>
                </tr>
                <tr>
                    <td class="label"><?php __('Change favicon Icon') ?>:</td>
                    <td class="value">
                        <form enctype="multipart/form-data" action="<?php echo $this->webroot; ?>systemparams/change_icon" method="post">
                        <input type="file" name="iconimg" style="" />
                        <input type="submit" value="Upload" style="" />
                        </form>
                    </td>
                    <td class="help">
                        <img src="<?php echo $this->webroot; ?>favicon.ico" />
                    </td>
                </tr>
            </tbody>
        </table>

  <!-- DYNAMIC -->
<!--  <form enctype="multipart/form-data" action="" method="post">-->
    <table class="list list-form" >
      <thead>
        <tr>
          <td colspan="3" class="last"><?php __('Operationsystem')?></td>
        </tr>
      </thead>
      <tbody>
         <tr>
            <td class="label"><?php echo __('Switch Alias')?>:</td>
            <td class="value">
                <input type="text" id="switch_alias" value="<?php echo  $post[0][0]['switch_alias']?>" name="switch_alias" class="input in-text" />
            </td>
            <td class="help last">
                
            </td>
        </tr>
        <tr>
            <td class="label"><?php echo __('Welcome Message')?>:</td>
            <td class="value">
                <input type="text" id="welcome_message" value="<?php echo  $post[0][0]['welcome_message']?>" name="welcome_message" class="input in-text" />
            </td>
            <td class="help last">
                
            </td>
        </tr>
        <tr>
          <td class="label"><?php echo __('System currency')?>:</td>
          <td class="value"><?php echo $form->input('currency',
 		array('id'=>'currency','name'=>'currency','options'=>$currency,'selected'=>$post[0][0]['sys_currency'],'label'=>false ,
 		'div'=>false,'type'=>'select'));?></td>
          <td class="help last"><?php echo __('Default System Currency',true);?></td>
        </tr>
        <tr>
          <td class="label"><?php __('Timezone')?>
            :</td>
          <td class="value"><?php 
       
       $t=array(
       '-1200'=>'GMT -12:00',
        '-1100'=>'GMT -11:00',
        '-1000'=>'GMT -10:00',
        '-0900'=>'GMT -09:00',
        '-0800'=>'GMT -08:00',
        '-0700'=>'GMT -07:00',
        '-0600'=>'GMT -06:00',
        '-0500'=>'GMT -05:00',
        '-0400'=>'GMT -04:00',
        '-0300'=>'GMT -03:00',
        '-0200'=>'GMT -02:00',
        '-0100'=>'GMT -01:00',
       	'+0000'=>'GMT +00:00',
       	'+0100'=>'GMT +01:00',
       	'+0200'=>'GMT +02:00',
       	'+0300'=>'GMT +03:00',
       	'+0400'=>'GMT +04:00',
       	'+0500'=>'GMT +05:00',
       	'+0600'=>'GMT +06:00',
       	'+0700'=>'GMT +07:00',
       	'+0800'=>'GMT +08:00',
       	'+0900'=>'GMT +09:00',
       	'+1000'=>'GMT +10:00',
       	'+1100'=>'GMT +11:00',
       	'+1200'=>'GMT +12:00',
       
       );
       echo $form->input('currency',
 		array('id'=>'timezone2','name'=>'timezone2','options'=>$t,'selected'=>$post[0][0]['sys_timezone'],'label'=>false ,
 		'div'=>false,'type'=>'select'));?></td>
          <td class="help last"><?php echo __('Default System Timezone used for billing and reporting',true);?>
</td>
        </tr>
        <tr>
          <td class="label"><?php echo __('Code Deck')?>:</td>
          <td class="value">
              <select id="search_code_deck" class="select in-select" name="search_code_deck">
                <option value=""> </option>
                <?php for ($i=0;$i<count($codecs_s);$i++) { ?>
                <option value="<?php echo $codecs_s[$i][0]['code_deck_id']?>"><?php echo $codecs_s[$i][0]['name']?></option>
                <?php } ?>
              </select>
          </td>
          <td class="help last"><?php echo __('Code Deck',true);?></td>
        </tr>
        <tr>
          <td class="label"><?php echo __('Pre-load data on display')?>:</td>
          <td class="value">
              <select name="is_preload" id="is_preload">
                  <option value="true" <?php if($post[0][0]['is_preload'] == 1) echo 'selected="selected"'; ?>>True</option>
                  <option value="false" <?php if($post[0][0]['is_preload'] == 0) echo 'selected="selected"'; ?>>False</option>
              </select>
          </td>
          <td class="help last"></td>
        </tr>
        <tr>
          <td class="label"><?php echo __('Different Report Count')?>:</td>
          <td class="value">
              <select name="report_count" id="report_count">
                  <option value="0" <?php if($post[0][0]['report_count'] == 0) echo 'selected="selected"' ?>>1hr</option>
                  <option value="1" <?php if($post[0][0]['report_count'] == 1) echo 'selected="selected"' ?>>24hr</option>
              </select>
          </td>
          <td class="help last"></td>
        </tr>
        <tr>
          <td class="label"><?php echo __('Landing Page')?>:</td>
          <td class="value">
              <select name="landing_page" id="landing_page">
                  <option value="0" <?php if($post[0][0]['landing_page'] == 0) echo 'selected="selected"' ?>>Qos Report</option>
                  <option value="1" <?php if($post[0][0]['landing_page'] == 1) echo 'selected="selected"' ?>>Summary Report</option>
                  <option value="2" <?php if($post[0][0]['landing_page'] == 2) echo 'selected="selected"' ?>>Orig-Term Report</option>
                  <option value="3" <?php if($post[0][0]['landing_page'] == 3) echo 'selected="selected"' ?>>Carrier Management</option>
              </select>
          </td>
          <td class="help last"></td>
        </tr>
        <!--
        <tr>
          <td class="label"><?php echo __('Invoice Naming Conversion')?>:</td>
          <td class="value">
              <input type="text" name="invoice_name" id="invoice_name" value="<?php echo  $post[0][0]['invoice_name']?>" />
          </td>
          <td class="help last"></td>
        </tr>
        -->
        <tr>
          <td class="label"><?php echo __('Title Bar Color')?>:</td>
          <td class="value">
              <input class="color" type="text" name="bar_color" id="bar_color" value="<?php echo  $post[0][0]['bar_color']?>" />
          </td>
          <td class="help last">Default:6B9B20</td>
        </tr>
        <tr>
          <td class="label"><?php echo __('Inactivity Timeout')?>:</td>
          <td class="value">
              <input type="text" name="inactivity_timeout" id="inactivity_timeout" value="<?php echo $post[0][0]['inactivity_timeout']?>" />
          </td>
          <td class="help last">minute</td>
        </tr>
      </tbody>
    </table>
  
    <table class="list list-form">
        <thead>
            <tr>
                <td colspan="3" class="last"><?php echo __('System Default Timeout Setting',true);?></td>
            </tr>
          </thead>
          <tbody>
              <tr class="row-1">
                <td class="label"><span class="red">*</span><?php __('Ingress PDD Timeout')?>:</td>
                <td class="value"><input type="text" id="ingress_pdd_timeout" value="<?php   echo  $post[0][0]['ingress_pdd_timeout']?>" name="ingress_pdd_timeout" class="input in-text"></td>
                <td class="help last">ms</td>
              </tr>
              <tr class="row-2">
                <td class="label"><span class="red">*</span><?php echo __('Egress PDD Timeout',true);?>:</td>
                <td class="value"><input type="text" id="egress_pdd_timeout" value="<?php   echo  $post[0][0]['egress_pdd_timeout']?>" name="egress_pdd_timeout" class="input in-text"></td>
                <td class="help last">ms</td>
              </tr>
              <tr class="row-1">
                <td class="label"><span class="red">*</span><?php __('Ring Timeout')?>:</td>
                <td class="value"><input type="text" id="ring_timeout" value="<?php   echo  $post[0][0]['ring_timeout']?>" name="ring_timeout" class="input in-text"></td>
                <td class="help last">s</td>
              </tr>
              <tr class="row-2">
                <td class="label"><span class="red">*</span><?php echo __('Call Timeout',true);?>:</td>
                <td class="value"><input type="text" id="call_timeout" value="<?php echo  $post[0][0]['call_timeout']?>" name="call_timeout" class="input in-text"></td>
                <td class="help last">s</td>
              </tr>
          </tbody>
    </table>

    <table class="list list-form">
    <thead>
        <tr>
            <td colspan="3" class="last"><?php echo __('SMTP Setup',true);?></td>
        </tr>
      </thead>
      <tr class="row-1">
        <td class="label"><span class="red">*</span><?php __('Mailserverhost')?>
          :</td>
        <td class="value"><input type="text" id="smtphost" value="<?php   echo  $post[0][0]['smtphost']?>" name="smtphost" class="input in-text"></td>
        <td class="help last"><?php echo __('Hostname or IP address of SMTP server',true);?></td>
      </tr>
      <tr class="row-2">
        <td class="label"><span class="red">*</span><?php echo __('SMTP Port',true);?>:</td>
        <td class="value"><input type="text" id="smtpport" value="<?php   echo  $post[0][0]['smtpport']?>" name="smtpport" class="input in-text"></td>
        <td class="help last"><?php echo __('Port for the SMTP server',true);?></td>
      </tr>
      <tr class="row-1">
        <td class="label"><span class="red">*</span><?php echo __('SMTP Username',true);?>:</td>
        <td class="value"><input type="text" id="emailusername"   value="<?php   echo  $post[0][0]['emailusername']?>"  name="emailusername" class="input in-text"></td>
        <td class="help last"></td>
      </tr>
      <tr class="row-2">
        <td class="label"><span class="red">*</span><?php echo __('SMTP Password',true);?>:</td>
        <td class="value"><input type="password" id="emailpassword"     value="<?php   echo  $post[0][0]['emailpassword']?>"  name="emailpassword" class="input in-text"></td>
        <td class="help last"></td>
      </tr>
      <tr class="row-1">
        <td class="label"><span class="red">*</span><?php echo __('SMTP Login Authentication',true);?>:</td>
        <td class="value"><?php echo $form->input('loginemail',
 		array('id'=>'loginemail','name'=>'loginemail','options'=>array('true'=>'true','false'=>'false'),'selected'=> array_keys_value($post,'0.0.loginemail'),'label'=>false ,
 		'div'=>false,'type'=>'select'));?></td>
        <td class="help last"></td>
      </tr>
      <tr class="row-2">
        <td class="label"><span class="red">*</span><?php echo __('From Email',true);?>:</td>
        <td class="value"><input type="text" id="fromemail"     value="<?php   echo  $post[0][0]['fromemail']?>"  name="fromemail" class="input in-text"></td>
        <td class="help last"></td>
      </tr>
      <tr class="row-1">
        <td class="label"><span class="red">*</span><?php echo __('Email Sender Name',true);?>:</td>
        <td class="value"><input type="text" id="emailname"  value="<?php   echo  $post[0][0]['emailname']?>"  name="emailname" class="input in-text"></td>
        <td class="help last"></td>
      </tr>
      
      <tr class="row-2">
        <td class="label"><span class="red"></span><?php echo __('SMTP Secure',true);?>:</td>
        <td class="value">
            <select name="smtp_secure" id="smtp_secure">
                <option value="0" <?php if($post[0][0]['smtp_secure'] == 0) echo 'selected' ?>></option>
                <option value="1" <?php if($post[0][0]['smtp_secure'] == 1) echo 'selected' ?>>TLS</option>
                <option value="2" <?php if($post[0][0]['smtp_secure'] == 2) echo 'selected' ?>>SSL</option>
                <option value="3" <?php if($post[0][0]['smtp_secure'] == 3) echo 'selected' ?>>NTLM</option>
            </select>
        </td>
        <td class="help last" style="text-align:left;line-height:40px;">
            <p id="ntlm_panel">
              Realm <input type="text" name="realm" id="realm" value="<?php echo $post[0][0]['realm']?>"  /> &nbsp; &nbsp; workstation <input type="text" name="workstation" id="workstation" value="<?php echo $post[0][0]['workstation']?>" />         
            </p>
        </td>
      </tr>
      
      <tr class="row-1">
          <td class="label"><?php echo __('Send Test To',true);?>:</td>
          <td class="value">
              <input type="text" id="test_email_to" name="test_email_to" />
          </td>
          <td class="help last"><input type="button" value="Test" id="testemail" /></td>
      </tr>
    </table>
      
      <table class="list list-form">
    <thead>
        <tr>
          <td colspan="3" class="last"><?php echo __('Notification Setting',true);?></td>
        </tr>
      </thead>
      <tr class="row-2">
        <td class="label"><?php echo __('System Admin Email',true);?>:</td>
        <td class="value"><input type="text" id="system_admin_email"     value="<?php   echo  $post[0][0]['system_admin_email']?>"  name="system_admin_email" class="input in-text"></td>
        <td class="help last"></td>
      </tr>
      <tr class="row-1">
        <td class="label"><?php echo __('Finance Email',true);?>:</td>
        <td class="value"><input type="text" id="finance_email"     value="<?php   echo  $post[0][0]['finance_email']?>"  name="finance_email" class="input in-text"></td>
        <td class="help last"></td>
      </tr>
      <tr class="row-2">
        <td class="label"><?php echo __('NOC Email',true);?>:</td>
        <td class="value"><input type="text" id="noc_email"     value="<?php   echo  $post[0][0]['noc_email']?>"  name="noc_email" class="input in-text"></td>
        <td class="help last"></td>
      </tr>
      <?php if (Configure::read('system.type') == 2): ?>
      <tr class="row-2">
        <td class="label"><?php echo __('Withdraw Email',true);?>:</td>
        <td class="value"><input type="text" id="withdraw_email"     value="<?php   echo  $post[0][0]['withdraw_email']?>"  name="withdraw_email" class="input in-text"></td>
        <td class="help last"></td>
      </tr>
      <?php endif; ?>
    </table>
    
    <table class="list list-form">
      <thead>
        <tr>
          <td colspan="3" class="last"><?php echo __('Payment Information',true);?></td>
        </tr>
      </thead>
      <tbody>
          <tr>
            <td class="label"><?php __('Yourpay Certificate File')?>
              :</td>
            <td class="value">
				<form method="post" action="<?php echo $this->webroot ?>systemparams/change_certfile" enctype="multipart/form-data">
                <input type="file" name="certifile" class="input in-file">
                <input type="submit" style="" value="Upload" class="input in-submit">
                </form>
			</td>
            <td class="help last"></td>
          </tr>
          <tr>
            <td class="label"><?php __('Yourpay Store Number')?>
              :</td>
            <td class="value"><input type="text" id="yourpay_store_number"  value="<?php   echo  $post[0][0]['yourpay_store_number']?>"  name="yourpay_store_number" class="input in-text"></td>
            <td class="help last"></td>
          </tr>
          <tr>
            <td class="label"><?php __('Paypal Account')?>
              :</td>
            <td class="value"><input type="text" id="paypal_account"  value="<?php   echo  $post[0][0]['paypal_account']?>"  name="paypal_account" class="input in-text"></td>
            <td class="help last"></td>
          </tr>
      </tbody>
    </table>
      
    <table class="list list-form">
      <thead>
        <tr>
          <td colspan="3" class="last"><?php echo __('Dynamic Route Setting',true);?></td>
        </tr>
      </thead>
      <tbody>
          <tr class="row-1">
            <td class="label"><?php __('Qos Sample Period')?>
              :</td>
            <td class="value">
                <select id="qos_sample_period" name="qos_sample_period">
                    <option value=""></option>
                    <option value="0" <?php if($post[0][0]['qos_sample_period'] == '0') echo 'selected' ?>>15 min</option>
                    <option value="1" <?php if($post[0][0]['qos_sample_period'] == '1') echo 'selected' ?>>30 min</option>
                    <option value="2" <?php if($post[0][0]['qos_sample_period'] == '2') echo 'selected' ?>>1 hr</option>
                    <option value="3" <?php if($post[0][0]['qos_sample_period'] == '3') echo 'selected' ?>>1 day</option>
                </select>
            </td>
            <td class="help last"></td>
          </tr>
          
          <tr class="row-1">
            <td class="label"><?php __('Minimal Call Attempt Required')?>
              :</td>
            <td class="value">
                <input type="text" id="minimal_call_attempt_required" name="minimal_call_attempt_required" value="<?php echo $post[0][0]['minimal_call_attempt_required'] ?>" />
            </td>
            <td class="help last"></td>
          </tr>
          
          <tr class="row-1">
            <td class="label"><?php __('Low Call Attempt Handling')?>
              :</td>
            <td class="value">
                <select name="low_call_attempt_handling" id="low_call_attempt_handling">
                    <option value=""></option>
                    <option value="0" <?php if($post[0][0]['low_call_attempt_handling'] == '0') echo 'selected' ?>>Use Latest Value</option>
                    <option value="1" <?php if($post[0][0]['low_call_attempt_handling'] == '1') echo 'selected' ?>>Set to &lt;none&gt;</option>
                </select>
            </td>
            <td class="help last"></td>
          </tr>
      </tbody>
    </table>  
    <!--  
    <table class="list list-form">
      <thead>
        <tr>
          <td colspan="3" class="last"><?php echo __('Sip Capture Setting',true);?></td>
        </tr>
      </thead>
      <tbody>
          <tr class="row-1">
            <td class="label"><?php __('Sip Capture Status')?>
              :</td>
            <td class="value"><?php echo trim($sip_capture_status) == 'on'? '<a class="input in-submit" style="display:block;height:15px;margin:0 auto;" href="' . $this->webroot. 'systemparams/set_capture/off">off</a>' : '<a class="input in-submit" style="display:block;height:15px;margin:0 auto;" href="' . $this->webroot. 'systemparams/set_capture/on">on</a>' ?></td>
            <td class="help last"><?php echo __('Current status:',true);?>&nbsp;<span style="font-weight:bold;color:red;"><?php echo $sip_capture_status; ?></span></td>
          </tr>
          
          <tr class="row-1">
            <td class="label"><?php __('RTP dump Status')?>
              :</td>
            <td class="value"><?php echo trim($rtpdump_status) == 'on'? '<a class="input in-submit" style="display:block;height:15px;margin:0 auto;" href="' . $this->webroot. 'systemparams/set_rptdump/off">off</a>' : '<a class="input in-submit" style="display:block;height:15px;margin:0 auto;" href="' . $this->webroot. 'systemparams/set_rptdump/on">on</a>' ?></td>
            <td class="help last"><?php echo __('Current status:',true);?>&nbsp;<span style="font-weight:bold;color:red;"><?php echo $rtpdump_status; ?></span></td>
          </tr>
      </tbody>
    </table>
   -->
    <table class="list list-form">
    		<tr class="row-2">
        <td class="label"><?php echo __('PDF Template Place -> Billing Details Location',true);?>:</td>
        <td class="value">
					<?php
					echo $form->input('tpl_number',
 		array('id'=>'tpl_number','name'=>'tpl_number','options'=>array('2'=>'middle','0'=>'bottom','1'=>'top'),'selected'=> array_keys_value($post,'0.0.tpl_number'),'label'=>false ,
 		'div'=>false,'type'=>'select'));?>
 					</td>
        <td class="help last"><?php echo __('Location of the billing details in the invoice',true);?></td>
      </tr>
      <tr class="row-2">
        <td class="label"><?php echo __('PDF Template Info -> Billing Details',true);?>:</td>
        <td class="value"><textarea class="input in-textarea" wrap="virtual" id="pdf_tpl" name="pdf_tpl" style="height:150px;width:450px;"><?php echo $post[0][0]['pdf_tpl']?></textarea></td>
        <td class="help last"><?php echo __('Billing information that you want to include in the invoice',true);?></td>
      </tr>
    </table>
    
    <!--<table class="list list-form">
<tr class="row-2">
    <td class="label">Switch IP:</td>
    <td class="value"><input type="text" id="switch_ip"     value="<?php   echo  $post[0][0]['switch_ip']?>"  name="switch_ip" class="input in-text"></td>
    <td class="help last"></td>
</tr>



<tr class="row-2">
    <td class="label">Switch SIP Port:</td>
    <td class="value"><input type="text" id="switch_port"     value="<?php   echo  $post[0][0]['switch_port']?>"  name="switch_port" class="input in-text"></td>
    <td class="help last"></td>
</tr>


</table>






--> 
    
    <!--<table class="list list-form">
<thead>
<tr>
    <td colspan="3" class="last"><?php __('Output and Import/Export')?></td>
</tr>
</thead>
<tbody>
<tr class="row-1">
    <td class="label"><?php __('Date Format')?>:</td>
    <td class="value"><input type="text" id="dateFormat" value="<?php   echo  $post[0][0]['date_format']?>" name="dateFormat" class="input in-text"></td>
    <td class="help last">Output format for dates, you can check syntax for this field in the User Manual</td>
</tr>
<tr class="row-2">
    <td class="label"><?php __('Datetime Format')?>:</td>
    <td class="value"><input type="text" id="datetimeFormat" value="<?php   echo  $post[0][0]['datetime_format']?>" name="datetimeFormat" class="input in-text"></td>
    <td class="help last">Output format for datetimes, you can check syntax for this field in the User Manual</td>
</tr>
<tr class="row-1">
    <td class="label"><?php __('CSV Delimiter')?>:</td>
    <td class="value"><input type="text" id="csv_delimiter" value="<?php   echo  $post[0][0]['csv_delimiter']?>" name="csv_delimiter" class="input in-text"></td>
    <td class="help last">Default delimiter used for CSV files when importing/exporting</td>
</tr>
</tbody>
</table>



<table class="list list-form">
<thead>
<tr>
    <td colspan="3" class="last"><?php __('Invoicing Settings')?></td>
</tr>
</thead>
<tbody>
<tr class="row-1">
    <td class="label"><?php __('Invoice No Template')?>:</td>
    <td class="value"><input type="text" id="invoices_tplNo" value="<?php   echo  $post[0][0]['invoices_tplno']?>" name="invoices_tplNo" class="input in-text"></td>
    <td class="help last">Format for Invoices No, you can use next replacements: %Y - current year, %m - current month, %d - current day, %C - client ID, %x - incremental integer</td>
</tr>
<tr class="row-2">
    <td class="label"><?php __('Invoice Last No')?>:</td>
    <td class="value"><input type="text" id="invoices_lastNo" value="<?php   echo  $post[0][0]['invoices_lastno']?>" name="invoices_lastNo" class="input in-text"></td>
    <td class="help last">Last Invoice No used in system for generation of invoices</td>
</tr>
--><!--<tr class="row-1">
    <td class="label"><?php __('Defaultinvoicefields')?>:</td>
    <td class="value">
    <select id="invoices_fields" multiple="1" name="invoices_fields[]" class="input in-select">
    <option selected="selected" value="account"><?php __('Account')?></option>
    <option value="code_country"><?php __('code_country')?></option>
    <option value="code_name"><?php __('Destination')?></option>
    <option value="code"><?php echo __('Codes')?></option>
    <option value="rate"><?php __('cardseriesrate')?></option>
    <option value="calls"><?php __('Calls')?></option>
    <option value="minutes"><?php __('Minutes')?></option>
    <option value="cost"><?php __('Cost')?></option></select></td>
    <td class="help last">List of fields that will be selected by default during invoices generation. Same fields will be used during auto-invoicing generation.</td>
</tr>
--><!--<tr class="row-2">
    <td class="label"><?php   __('AutoinvoicingDelay')?> :</td>
    <td class="value"><input type="text" id="invoices_delay" class="in-decimal input in-text" value="<?php   echo  $post[0][0]['invoices_delay']?>" name="invoices_delay"> hour(s)</td>
    <td class="help last">Time in hours from end of previous day to wait before invoicing. Required to be positive integer, otherwise system will invoice customer before the moment of full statistics process. Recommended value is around 5-6 hours.</td>
</tr>
--><!--<tr class="row-1">
    <td class="label"><?php __('Autoinvoice products')?>:</td>
    <td class="value">
    <select id="invoices_separate" name="invoices_separate" class="input in-select">
    <option selected="selected" value="0"><?php __('Combined invoice with statistics')?></option>
    <option value="1"><?php __('Separated invoices')?></option></select></td>
    <td class="help last">Defines the products autoinvoicing: <br><b>Combined invoice with statistics</b> - autoinvoice tool will issue invoices that contain calls and products data; <br><b>Separated invoices</b> - autoinvoicing tool will create separated invoices for calls and products (customer will receive 2 invoices - for calls and for products)</td>
</tr>    
<tr class="row-2">
    <td class="label"><?php __('CDR fields for invoices')?>:</td>
    <td class="value"><select id="invoices_cdr_fields" multiple="1" name="invoices_cdr_fields[]" class="input in-select">
    <option selected="selected" value="call_time"><?php __('callDAte')?></option>
    <option value="gw_ip"><?php __('egressresourceip')?></option>
     <option value="gw_ip"><?php __('egressresourcename')?></option>
      <option value="gw_ip"><?php __('ingressresourceip')?></option>
       <option value="gw_ip"><?php __('ingressresourcename')?></option>
        <option value="gw_ip"><?php __('country')?></option>
         <option value="gw_ip"><?php __('codename')?></option>
          <option value="gw_ip"><?php __('codeprefix')?></option>
           <option value="gw_ip"><?php __('srcnumber')?></option>
            <option value="gw_ip"><?php __('dstnumber')?></option>
             <option value="gw_ip"><?php __('billedtime')?></option>
              <option value="gw_ip"><?php __('Currencies')?></option>
               <option value="gw_ip"><?php __('Cost')?></option>
    
</select></td>
    <td class="help last">List of fields that will be selected by default when adding CDR-list to invoice. Same fields will be used during auto-invoicing generation.</td>
</tr>
--><!--</tbody>
</table>





<table class="list list-form">
<thead>
<tr>
    <td colspan="3" class="last"><?php __('DynamicRouting')?></td>
</tr>
</thead>
<tbody>
<tr class="row-1">
    <td class="label"><?php __('analyzeperiod')?>:</td>
    <td class="value"><input type="text" id="dr_period" class="in-decimal input in-text" value="<?php   echo $post[0][0]['invoices_delay']?>" name="dr_period"> minute(s)</td>
    <td class="help last"><?php __('analyzeperiodtip')?></td>
</tr>
<tr class="row-2">
    <td class="label"><?php __('logroute')?>:</td>
    <td class="value"><select id="radius_log_routes" name="radius_log_routes" class="input in-select">
    <option value=""><?php __('disables')?></option>
    <option value="empty"><?php __('onlyempty')?></option>
    <option value="detailed"><?php __('detailed')?></option>
    </select></td>
    <td class="help last"><br><b><?php __('disables')?></b> -<?php __('disablestip')?><br>
    <b><?php __('onlyempty')?></b> - <?php __('onlyemptytip')?><br><b><?php __('detailed')?></b> - <?php __('detailedtip')?></td>
</tr>
</tbody>
</table>







<table class="list list-form">
<thead>
<tr>
    <td colspan="3" class="last"><?php __('NotificationandMonitoring')?></td>
</tr>
</thead>
<tbody>
<tr class="row-1">
    <td class="label"><input type="checkbox" checked="checked" id="events_notFoundAccount" value="1" name="events_notFoundAccount" class="input in-checkbox"></td>
    <td class="value"><label for="events_notFoundAccount"><?php __('Eventonnotfoundaccoun')?></label></td>
    <td class="help last">Send event to events log, when client/account was not identified during processing of CDRs or RADIUS Authorization</td>
</tr>
<tr class="row-2">
    <td class="label"><input type="checkbox" checked="checked" id="events_notFoundTariff" value="1" name="events_notFoundTariff" class="input in-checkbox"></td>
    <td class="value"><label for="events_notFoundTariff"><?php __('Eventonnotfoundtariff')?></label></td>
    <td class="help last">Send event to events log, when destination/rate was not identified during processing of CDRs or RADIUS Authorization</td>
</tr>
<tr class="row-1">
    <td class="label"><input type="checkbox" checked="checked" id="events_unprofitable" value="1" name="events_unprofitable" class="input in-checkbox"></td>
    <td class="value"><label for="events_unprofitable"><?php __('Eventonunprofitablecalls')?></label></td>
    <td class="help last">Analyze calls for unprofitables and send events with details, analyze is processed each time Reports Generator runs</td>
</tr>
<tr class="row-2">
    <td class="label"><input type="checkbox" id="events_alertsZeroTime" value="1" name="events_alertsZeroTime" class="input in-checkbox"></td>
    <td class="value"><label for="events_alertsZeroTime"><?php __('Eventonzerotimecalls')?></label></td>
    <td class="help last">Send events even if call has zero length</td>
</tr>
<tr class="row-1">
    <td class="label"><?php __('Resendlow-balanceeventsevery')?>:</td>
    <td class="value"><input type="text" id="lowBalance_period" class="in-decimal input in-text" value="24" name="lowBalance_period"> hour(s)</td>
    <td class="help last">How often to resend e-mail regarding low customer's balance</td>
</tr>
</tbody>
</table>



<table class="list list-form">
<thead>
<tr>
    <td colspan="3" class="last"><?php __('Autorotationndcleaning')?></td>
</tr>
</thead>
<tbody>



<tr class="row-1">
    <td class="label"><?php __('Deleteeventsfromlogafter')?>:</td>
    <td class="value"><input type="text" id="events_deleteAfterDays" class="in-decimal input in-text" value="<?php   echo  $post[0][0]['events_deleteafterdays']?>" name="events_deleteAfterDays"> day(s) <small class="note">0 - do not delete</small></td>
    <td class="help last">Delete events from Events Log after specified period of days</td>
</tr>
<tr class="row-2">
    <td class="label"><?php __('Statisticsrotatedelay')?>:</td>
    <td class="value"><input type="text" id="stats_rotate_delay" class="in-decimal input in-text" value="<?php   echo  $post[0][0]['stats_rotate_delay']?>"
     name="stats_rotate_delay"> day(s) <small class="note">0 - do not rotate</small></td>
    <td class="help last">Remove statistics from database after specified number of days</td>
</tr>
<tr class="row-1">
    <td class="label"><?php __('Deletenotactualratesafter')?>:</td>
    <td class="value"><input type="text" id="rates_deleteAfterDays" class="in-decimal input in-text"
     value="<?php   echo  $post[0][0]['rates_deleteafterdays']?>" name="rates_deleteAfterDays"> day(s) <small class="note">0 - do not delete</small></td>
    <td class="help last">Delete rates from system, when they are not actual and effective date is older than specified number of days</td>
</tr>
<tr class="row-2">
    <td class="label"><?php __('DeleteparsedCDR-filesafter')?>:</td>
    <td class="value"><input type="text" id="cdrs_deleteAfterDays" class="in-decimal input in-text"
     value="<?php   echo  $post[0][0]['cdrs_deleteafterdays']?>" name="cdrs_deleteAfterDays"> day(s) <small class="note">0 - do not delete</small></td>
    <td class="help last">After what number of days already parsed and processed CDR-files should be deleted from local server</td>
</tr>


<tr class="row-1">
    <td class="label"><?php __('Deletearchivedlog-filesafter')?>:</td>
    <td class="value"><input type="text" id="logs_deleteAfterDays" class="in-decimal input in-text" 
    value="<?php   echo  $post[0][0]['logs_deleteafterdays']?>"
     name="logs_deleteAfterDays"> day(s) <small class="note">0 - do not delete</small></td>
    <td class="help last">After what number of days archived log-files should be deleted from local server</td>
</tr>
</tbody>
</table>

<table class="list list-form">
<thead>
<tr>
    <td colspan="3" class="last"><?php __('BackupSettings')?></td>
</tr>
</thead>
<tbody>
<tr class="row-1">
    <td class="label"><?php __('Systembackupperiod')?>:</td>
    <td class="value"><input type="text" id="backup_period" class="in-decimal input in-text"value="<?php   echo  $post[0][0]['backup_period']?>" name="backup_period"> hour(s) <small class="note">0 - do not backup</small></td>
    <td class="help last">How often to backup system, please note that actual backup will be done at the time when Backup Manager process runs</td>
</tr>
<tr class="row-2">
    <td class="label"><?php __('LeavelastXpackages')?>:</td>
    <td class="value"><input type="text" id="backup_leave_last" class="in-decimal input in-text" value="<?php   echo  $post[0][0]['backup_leave_last']?>" name="backup_leave_last"></td>
    <td class="help last">How many last backup packages to leave</td>
</tr>
</tbody>
</table>




-->
    <?php  if ($_SESSION['role_menu']['Configuration']['systemparams']['model_w']) {?>
    <div class="form-buttons">
      <input type="button" value="<?php echo __('submit')?>" onclick="javascript:postsysparameter();return false;" class="input in-submit">
    </div>
    <?php }?>
<!--  </form>-->
  <!-- DYNAMIC --> 
</div>
<div id="loading"></div>

<script type="text/javascript" src="<?php echo $this->webroot ?>js/jscolor/jscolor.js"></script>

<script type="text/javascript">
$(function() {
    $('#smtp_secure').change(function() {
        if($(this).val() == '3')  {
            $('#ntlm_panel').show();
        } else {
            $('#ntlm_panel').hide();
        }
    });
    
    

    $('#smtp_secure').change();
    
    $('#testemail').click(function() {
        var address = $('#test_email_to').val();
        $.ajax({
            url : '<?php echo $this->webroot ?>systemparams/testsmtp',
            type : 'POST',
            dataType : 'text',
            data : {email:address},
            'beforeSend' : function() {
                $('#loading').show();
            },
            success : function(data) {
                data = data.replace(/<br \/>/, "\n");
                 $('#loading').hide();
                alert(data);
            }
        });
    });
});
</script>