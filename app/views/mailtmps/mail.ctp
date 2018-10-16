<style type="text/css">
.form .value, .list-form .value {
    text-align:left;
}
.form .label, .list-form .label {
    width: 200px;
}

.value input.input {width:800px;}

.mceLayout table  {width:auto;}
</style>
<div id="title">
    <h1>
        <?php __('System')?>
        &gt;&gt;<?php echo __('configmailtmp')?></h1>
</div>
<div class="container">
    <form method="post">
        <input type="hidden" name="id" value="<?php echo $tmp[0][0]['id'];?>">
        <div id="templates">
            <fieldset>
                <legend style="cursor: pointer;" onclick="$('#mail_invoice').toggle()"><span id="ht-100011"><?php echo __('invoiceemailtemp')?></span>»</legend>
                <div id="mail_invoice" >
                    <table class="form">

                        <tbody>
                            <tr>
                                
                                <td class="label">From email:</td>
                                <td class="value">
                                    <select style="width:200px;" name="invoice_from">
                                        <option <?php if (empty($tmp[0][0]['invoice_from'])) echo 'selected="selected"'  ?>>Default</option>
                                        <?php foreach($mail_senders as $mail_sender): ?>
                                        <option value="<?php echo $mail_sender[0]['id'] ?>" <?php if ($mail_sender[0]['id'] == $tmp[0][0]['invoice_from']) echo 'selected="selected"'  ?>><?php echo $mail_sender[0]['email'] ?></option>                                        
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="label">Cc:</td>
                                <td class="value">
                                    <input type="text" name="invoice_cc" id="invoice_cc" value="<?php echo !empty($tmp[0][0]['invoice_cc'])?$tmp[0][0]['invoice_cc']:'';?>">
                                </td>
                            </tr>
                            <!--<tr>
                                            <td class="label"><?php echo __('from')?>:</td>
                                            <td class="value"><input class="input in-text" name="invoice_from" value="<?php echo !empty($tmp[0][0]['invoice_from'])?$tmp[0][0]['invoice_from']:'';?>" id="invoice_from" type="text"></td>
                                          </tr>
                                          <tr>
                                            <td class="label"><?php echo __('replyto')?>:</td>
                                            <td class="value"><input class="input in-text" name="invoice_to" value="<?php echo !empty($tmp[0][0]['invoice_to'])?$tmp[0][0]['invoice_to']:'';?>" id="invoice_to" type="text"></td>
                                          </tr>
                            --><tr>
                                <td class="label"><?php echo __('subject')?>:</td>
                                <td class="value">
                                    <input class="input in-text" name="invoice_subject" value="<?php echo !empty($tmp[0][0]['invoice_subject'])?$tmp[0][0]['invoice_subject']:'';?>" id="invoice_subject" type="text" ><br/>
                                    {switch_alias},{company_name},{invoice_number}, {cdr_url}, {start_date} and {end_date} can't change.
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="label"><?php echo __('content')?>:</td>
                                <td class="value"><textarea class="input in-textarea invoice_content" name="invoice_content" style="height: 100px; font-family: monospace; font-size: 12px;width:800px;" id="invoice_content"><?php echo !empty($tmp[0][0]['invoice_content'])?$tmp[0][0]['invoice_content']:'';?></textarea></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </fieldset>
            <fieldset>
                <legend style="cursor: pointer;" onclick="$('#mail_notify').toggle()"><span id="ht-100012"><?php echo __('paymentnotifytemp')?></span> »</legend>
                <div id="mail_notify" style="display: none;">
                    <table class="form">
                        <tbody>
                            <tr>
                                <td class="label">From email:</td>
                                <td class="value">
                                    <select style="width:200px;" name="payment_from">
                                        <option <?php if (empty($tmp[0][0]['payment_from'])) echo 'selected="selected"'  ?>>Default</option>
                                        <?php foreach($mail_senders as $mail_sender): ?>
                                        <option value="<?php echo $mail_sender[0]['id'] ?>" <?php if ($mail_sender[0]['id'] == $tmp[0][0]['payment_from']) echo 'selected="selected"'  ?>><?php echo $mail_sender[0]['email'] ?></option>                                        
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="label">Cc:</td>
                                <td class="value">
                                    <input type="text" name="payment_from_cc" id="payment_from_cc" value="<?php echo !empty($tmp[0][0]['payment_from_cc'])?$tmp[0][0]['payment_from_cc']:'';?>">
                                </td>
                            </tr>
                            <!--<tr>
                                            <td class="label"><?php echo __('from')?>:</td>
                                            <td class="value"><input class="input in-text" name="payment_from" value="<?php echo !empty($tmp[0][0]['payment_from'])?$tmp[0][0]['payment_from']:'';?>" id="payment_from" type="text"></td>
                                          </tr>
                                          <tr>
                                            <td class="label"><?php echo __('replyto')?>:</td>
                                            <td class="value"><input class="input in-text" name="payment_to" value="<?php echo !empty($tmp[0][0]['payment_to'])?$tmp[0][0]['payment_to']:'';?>" id="payment_to" type="text"></td>
                                          </tr>
                            --><tr>
                                <td class="label"><?php echo __('subject')?>:</td>
                                <td class="value"><input class="input in-text" name="payment_subject" value="<?php echo !empty($tmp[0][0]['payment_subject'])?$tmp[0][0]['payment_subject']:'';?>" id="payment_subject" type="text" >
                                    <br/> {switch_alias},{company_name} can't change. 
                                </td>
                            </tr>
                            <tr>
                                <td class="label"><?php echo __('content')?>:</td>
                                <td  class="value"><textarea class="input in-textarea payment_content" name="payment_content" style="height: 100px; font-family: monospace; font-size: 12px;width:800px;" id="payment_content"><?php echo !empty($tmp[0][0]['payment_content'])?$tmp[0][0]['payment_content']:'';?></textarea></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </fieldset>
            <fieldset>
                <legend style="cursor: pointer;" onclick="$('#mail_balance').toggle()"><span id="ht-100013" ><?php echo __('lowbalancetemp')?></span>»</legend>
                <div id="mail_balance" style="display: none;">
                    <table class="form">

                        <tbody>
                            <tr>
                                <td class="label">From email:</td>
                                <td class="value">
                                    <select style="width:200px;" name="lowbalance_from">
                                        <option <?php if (empty($tmp[0][0]['lowbalance_from'])) echo 'selected="selected"'  ?>>Default</option>
                                        <?php foreach($mail_senders as $mail_sender): ?>
                                        <option value="<?php echo $mail_sender[0]['id'] ?>" <?php if ($mail_sender[0]['id'] == $tmp[0][0]['lowbalance_from']) echo 'selected="selected"'  ?>><?php echo $mail_sender[0]['email'] ?></option>                                        
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="label">Cc:</td>
                                <td class="value">
                                    <input type="text" name="lowbalance_cc" id="payment_from_cc" value="<?php echo !empty($tmp[0][0]['lowbalance_cc'])?$tmp[0][0]['lowbalance_cc']:'';?>">
                                </td>
                            </tr>                                
                            <tr>
                                <td class="label"><?php echo __('subject')?>:</td>
                                <td class="value"><input class="input in-text" name="lowbalance_subject" value="<?php echo !empty($tmp[0][0]['lowbalance_subject'])?$tmp[0][0]['lowbalance_subject']:'';?>" id="lowbalance_subject" type="text" >
                                    <br/>            
                                    {company_name}, {date} can't change.
                                </td>
                            </tr>
                            <tr>
                                <td class="label"><?php echo __('content')?>:</td>
                                <td  class="value"><textarea class="input in-textarea lowbalance_content" name="lowbalance_content" style="height: 100px; font-family: monospace; font-size: 12px;width:800px;" id="lowbalance_content"><?php echo !empty($tmp[0][0]['lowbalance_content'])?$tmp[0][0]['lowbalance_content']:'';?></textarea>
                                    <br />	Switch Alias{switch_alias}, Current Date{date}, Company Name {company_name}, Alarm Low Balance{notify_balance}, Current Balance{balance}, Credit{allow_credit} can't change.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </fieldset>



            <fieldset>
                <legend style="cursor: pointer;" onclick="$('#noc_email').toggle()"><span id="ht-100013" ><?php echo __('NOC Email')?></span>»</legend>
                <div id="noc_email" style="display: none;">
                    <table class="form">

                        <tbody>
                            <tr>
                                <td class="label">From email:</td>
                                <td class="value">
                                    <select style="width:200px;" name="noc_email_from">
                                        <option <?php if (empty($tmp[0][0]['noc_email_from'])) echo 'selected="selected"'  ?>>Default</option>
                                        <?php foreach($mail_senders as $mail_sender): ?>
                                        <option value="<?php echo $mail_sender[0]['id'] ?>" <?php if ($mail_sender[0]['id'] == $tmp[0][0]['noc_email_from']) echo 'selected="selected"'  ?>><?php echo $mail_sender[0]['email'] ?></option>                                        
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="label"><?php echo __('subject')?>:</td>
                                <td  class="value"><input class="input in-input" name="noc_email_subject" value="<?php echo !empty($tmp[0][0]['noc_email_subject'])?$tmp[0][0]['noc_email_subject']:'';?>"  id="noc_email_subject" />
                                </td>
                            </tr>
                            <tr>
                                <td class="label"><?php echo __('content')?>:</td>
                                <td  class="value"><textarea class="input in-textarea noc_email_content" name="noc_email_content" style="height: 100px; font-family: monospace; font-size: 12px;width:800px;" id="noc_email_content"><?php echo !empty($tmp[0][0]['noc_email_content'])?$tmp[0][0]['noc_email_content']:'';?></textarea>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </fieldset> 


            <fieldset>
                <legend style="cursor: pointer;" onclick="$('#carrier_email').toggle()"><span id="ht-100013" ><?php echo __('Carrier Email')?></span>»</legend>
                <div id="carrier_email" style="display: none;">
                    <table class="form">

                        <tbody>
                            <tr>
                                <td class="label">From email:</td>
                                <td class="value">
                                    <select style="width:200px;" name="carrier_email_from">
                                        <option <?php if (empty($tmp[0][0]['carrier_email_from'])) echo 'selected="selected"'  ?>>Default</option>
                                        <?php foreach($mail_senders as $mail_sender): ?>
                                        <option value="<?php echo $mail_sender[0]['id'] ?>" <?php if ($mail_sender[0]['id'] == $tmp[0][0]['carrier_email_from']) echo 'selected="selected"'  ?>><?php echo $mail_sender[0]['email'] ?></option>                                        
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="label"><?php echo __('subject')?>:</td>
                                <td  class="value"><input class="input in-input" name="carrier_email_subject" value="<?php echo !empty($tmp[0][0]['carrier_email_subject'])?$tmp[0][0]['carrier_email_subject']:'';?>"  id="carrier_email_subject" />
                                </td>
                            </tr>
                            <tr>
                                <td class="label"><?php echo __('content')?>:</td>
                                <td  class="value"><textarea class="input in-textarea carrier_email_content" name="carrier_email_content" style="height: 100px; font-family: monospace; font-size: 12px;width:800px;" id="carrier_email_content"><?php echo !empty($tmp[0][0]['carrier_email_content'])?$tmp[0][0]['carrier_email_content']:'';?></textarea>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </fieldset> 


            <fieldset>
                <legend style="cursor: pointer;" onclick="$('#alert_email').toggle()"><span id="ht-100013" ><?php echo __('Alert Email')?></span>»</legend>
                <div id="alert_email" style="display: none;">
                    <table class="form">

                        <tbody>
                            <tr>
                                <td class="label">From email:</td>
                                <td class="value">
                                    <select style="width:200px;" name="alert_email_from">
                                        <option <?php if (empty($tmp[0][0]['alert_email_from'])) echo 'selected="selected"'  ?>>Default</option>
                                        <?php foreach($mail_senders as $mail_sender): ?>
                                        <option value="<?php echo $mail_sender[0]['id'] ?>" <?php if ($mail_sender[0]['id'] == $tmp[0][0]['alert_email_from']) echo 'selected="selected"'  ?>><?php echo $mail_sender[0]['email'] ?></option>                                        
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="label">Cc:</td>
                                <td class="value">
                                    <input type="text" name="alert_email_cc" id="alert_email_cc" value="<?php echo !empty($tmp[0][0]['alert_email_cc'])?$tmp[0][0]['alert_email_cc']:'';?>">
                                </td>
                            </tr>    
                            <tr>
                                <td class="label"><?php echo __('subject')?>:</td>
                                <td  class="value"><input class="input in-input" name="alert_email_subject" value="<?php echo !empty($tmp[0][0]['alert_email_subject'])?$tmp[0][0]['alert_email_subject']:'';?>"  id="carrier_email_subject" />
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2"> 
                                   {switch_alias} {Ingress Name} {Carrier Name} {ASR} {ACD} {ABR} {Margin}  {TT Number} {TT Number} {Code Name} {PDD} {IP Address} {IP Address} {End Date} {End Time} {GMT} {Ticket Number} {CDR}
  
                                </td>
                            </tr>
                            <tr>
                                <td class="label"><?php echo __('content')?>:</td>
                                <td  class="value"><textarea class="input in-textarea alert_email_content" name="alert_email_content" style="height: 100px; font-family: monospace; font-size: 12px;width:800px;" id="alert_email_content"><?php echo !empty($tmp[0][0]['alert_email_content'])?$tmp[0][0]['alert_email_content']:'';?></textarea>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </fieldset> 

            <fieldset>
                <legend style="cursor: pointer;" onclick="$('#auto_summary_mail').toggle()"><span id="ht-100013" ><?php echo __('Daily Summary Email Template')?></span>»</legend>
                <div id="auto_summary_mail" style="display: none;">
                    <table class="form">

                        <tbody>
                            <tr>
                                <td class="label">From email:</td>
                                <td class="value">
                                    <select style="width:200px;" name="auto_summary_from">
                                        <option <?php if (empty($tmp[0][0]['auto_summary_from'])) echo 'selected="selected"'  ?>>Default</option>
                                        <?php foreach($mail_senders as $mail_sender): ?>
                                        <option value="<?php echo $mail_sender[0]['id'] ?>" <?php if ($mail_sender[0]['id'] == $tmp[0][0]['auto_summary_from']) echo 'selected="selected"'  ?>><?php echo $mail_sender[0]['email'] ?></option>                                        
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="label">Cc:</td>
                                <td class="value">
                                    <input type="text" name="auto_summary_cc" id="auto_summary_cc" value="<?php echo !empty($tmp[0][0]['auto_summary_cc'])?$tmp[0][0]['auto_summary_cc']:'';?>">
                                </td>
                            </tr> 
                            <tr>
                                <td class="label"><?php echo __('subject')?>:</td>
                                <td  class="value"><input class="input in-input" name="auto_summary_subject" value="<?php echo !empty($tmp[0][0]['auto_summary_subject'])?$tmp[0][0]['auto_summary_subject']:'';?>"  id="carrier_email_subject" />
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2"><span class="auto_summary_tag">{switch_alias}</span><span class="auto_summary_tag">{company_name}</span><span class="auto_summary_tag">{balance}</span><span class="auto_summary_tag">{total_not_zero_calls_buy}</span><span class="auto_summary_tag">{total_not_zero_calls_sell}</span><span class="auto_summary_tag">{total_call_buy}</span><span class="auto_summary_tag">{total_success_call_buy}</span><span class="auto_summary_tag">{total_billed_min_buy}</span><span class="auto_summary_tag">{total_billed_amount_buy}</span><span class="auto_summary_tag">{total_call_sell}</span><span class="auto_summary_tag">{total_success_call_sell}</span><span class="auto_summary_tag">
                                        {total_billed_min_sell}</span><span class="auto_summary_tag">{total_billed_amount_sell}</span><span class="auto_summary_tag">{buy_total_duration}</span><span class="auto_summary_tag">{sell_total_duration}</span><span class="auto_summary_tag">{customer_gmt}</span><span class="auto_summary_tag">{start_time}</span><span class="auto_summary_tag">{end_time}</span><span class="auto_summary_tag">{cdr_url}</span></td>
                            </tr>
                            <tr>
                                <td class="label"><?php echo __('content')?>:</td>
                                <td  class="value"><textarea class="input in-textarea auto_summary_content" name="auto_summary_content" style="height: 100px; font-family: monospace; font-size: 12px;width:800px;" id="auto_summary_content"><?php echo !empty($tmp[0][0]['auto_summary_content'])?$tmp[0][0]['auto_summary_content']:'';?></textarea>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </fieldset>   

            <fieldset>
                <legend style="cursor: pointer;" onclick="$('#auto_balance_mail').toggle()"><span id="ht-100013" ><?php echo __('Daily Balance Email Template')?></span>»</legend>
                <div id="auto_balance_mail" style="display: none;">
                    <table class="form">

                        <tbody>
                            <tr>
                                <td class="label">From email:</td>
                                <td class="value">
                                    <select style="width:200px;" name="auto_balance_from">
                                        <option <?php if (empty($tmp[0][0]['auto_balance_from'])) echo 'selected="selected"'  ?>>Default</option>
                                        <?php foreach($mail_senders as $mail_sender): ?>
                                        <option value="<?php echo $mail_sender[0]['id'] ?>" <?php if ($mail_sender[0]['id'] == $tmp[0][0]['auto_balance_from']) echo 'selected="selected"'  ?>><?php echo $mail_sender[0]['email'] ?></option>                                        
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="label">Cc:</td>
                                <td class="value">
                                    <input type="text" name="auto_balance_cc" id="auto_balance_cc" value="<?php echo !empty($tmp[0][0]['auto_balance_cc'])?$tmp[0][0]['auto_balance_cc']:'';?>">
                                </td>
                            </tr> 
                            <tr>
                                <td class="label"><?php echo __('subject')?>:</td>
                                <td  class="value"><input class="input in-input" name="auto_balance_subject" value="<?php echo !empty($tmp[0][0]['auto_balance_subject'])?$tmp[0][0]['auto_balance_subject']:'';?>"  id="carrier_email_subject" />
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <span class="auto_summary_tag">{switch_alias}</span>
                                    <span class="auto_summary_tag">{company_name}</span>
                                    <span class="auto_summary_tag">{client_name}</span>
                                    <span class="auto_summary_tag">{start_time}</span> 
                                    <span class="auto_summary_tag">{end_time}</span>
                                    <span class="auto_summary_tag">{beginning_balance}</span>
                                    <span class="auto_summary_tag">{buy_amount}</span>
                                    <span class="auto_summary_tag">{sell_amount}</span>
                                    <span class="auto_summary_tag">{ending_balance}</span>
                                    <span class="auto_summary_tag">{allowed_credit}</span>
                                    <span class="auto_summary_tag">{remaining_credit}</span>
                                </td>
                            </tr>
                            <tr>
                                <td class="label"><?php echo __('content')?>:</td>
                                <td  class="value"><textarea class="input in-textarea auto_balance_content" name="auto_balance_content" style="height: 100px; font-family: monospace; font-size: 12px; width:800px;" id="auto_balance_content"><?php echo !empty($tmp[0][0]['auto_balance_content'])?$tmp[0][0]['auto_balance_content']:'';?></textarea>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </fieldset>    
            
            
            <fieldset>
                <legend style="cursor: pointer;" onclick="$('#auto_cdr_mail').toggle()"><span id="ht-100013" ><?php echo __('Daily CDR Email Template')?></span>»</legend>
                <div id="auto_cdr_mail" style="display: none;">
                    <table class="form">

                        <tbody>
                            <tr>
                                <td class="label">From email:</td>
                                <td class="value">
                                    <select style="width:200px;" name="auto_cdr_from">
                                        <option <?php if (empty($tmp[0][0]['auto_cdr_from'])) echo 'selected="selected"'  ?>>Default</option>
                                        <?php foreach($mail_senders as $mail_sender): ?>
                                        <option value="<?php echo $mail_sender[0]['id'] ?>" <?php if ($mail_sender[0]['id'] == $tmp[0][0]['auto_cdr_from']) echo 'selected="selected"'  ?>><?php echo $mail_sender[0]['email'] ?></option>                                        
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="label">Cc:</td>
                                <td class="value">
                                    <input type="text" name="auto_cdr_cc" id="auto_cdr_cc" value="<?php echo !empty($tmp[0][0]['auto_cdr_cc'])?$tmp[0][0]['auto_cdr_cc']:'';?>">
                                </td>
                            </tr> 
                            <tr>
                                <td class="label"><?php echo __('subject')?>:</td>
                                <td  class="value"><input class="input in-input" name="auto_cdr_subject" value="<?php echo !empty($tmp[0][0]['auto_cdr_subject'])?$tmp[0][0]['auto_cdr_subject']:'';?>"  id="carrier_email_subject" />
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2"><span class="auto_summary_tag">{switch_alias}</span><span class="auto_summary_tag">{client_name}</span></td>
                            </tr>
                            <tr>
                                <td class="label"><?php echo __('content')?>:</td>
                                <td  class="value"><textarea class="input in-textarea auto_cdr_content" name="auto_cdr_content" style="height: 100px; font-family: monospace; font-size: 12px;width:800px;" id="auto_cdr_content"><?php echo !empty($tmp[0][0]['auto_cdr_content'])?$tmp[0][0]['auto_cdr_content']:'';?></textarea>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </fieldset> 
            
            <fieldset>
                <legend style="cursor: pointer;" onclick="$('#payment_sent_mail').toggle()"><span id="ht-100013" ><?php echo __('Payment Sent Email Template')?></span>»</legend>
                <div id="payment_sent_mail" style="display: none;">
                    <table class="form">

                        <tbody>
                            <tr>
                                <td class="label">From email:</td>
                                <td class="value">
                                    <select style="width:200px;" name="payment_sent_from">
                                        <option <?php if (empty($tmp[0][0]['payment_sent_from'])) echo 'selected="selected"'  ?>>Default</option>
                                        <?php foreach($mail_senders as $mail_sender): ?>
                                        <option value="<?php echo $mail_sender[0]['id'] ?>" <?php if ($mail_sender[0]['id'] == $tmp[0][0]['payment_sent_from']) echo 'selected="selected"'  ?>><?php echo $mail_sender[0]['email'] ?></option>                                        
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="label">Cc:</td>
                                <td class="value">
                                    <input type="text" name="payment_sent_cc" id="payment_sent_cc" value="<?php echo !empty($tmp[0][0]['payment_sent_cc'])?$tmp[0][0]['payment_sent_cc']:'';?>">
                                </td>
                            </tr> 
                            <tr>
                                <td class="label"><?php echo __('subject')?>:</td>
                                <td  class="value"><input class="input in-input" name="payment_sent_subject" value="<?php echo !empty($tmp[0][0]['payment_sent_subject'])?$tmp[0][0]['payment_sent_subject']:'';?>"  id="carrier_email_subject" />
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2"><span class="auto_summary_tag">{switch_alias}</span><span class="auto_summary_tag">{amount}</span><span class="auto_summary_tag">{receiving_time}</span></td>
                            </tr>
                            <tr>
                                <td class="label"><?php echo __('content')?>:</td>
                                <td  class="value"><textarea class="input in-textarea payment_sent_content" name="payment_sent_content" style="height: 100px; font-family: monospace; font-size: 12px;width:800px;" id="payment_sent_content"><?php echo !empty($tmp[0][0]['payment_sent_content'])?$tmp[0][0]['payment_sent_content']:'';?></textarea>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </fieldset> 
            
            <fieldset>
                <legend style="cursor: pointer;" onclick="$('#payment_received_mail').toggle()"><span id="ht-100013" ><?php echo __('Payment Received Email Template')?></span>»</legend>
                <div id="payment_received_mail" style="display: none;">
                    <table class="form">

                        <tbody>
                            <tr>
                                <td class="label">From email:</td>
                                <td class="value">
                                    <select style="width:200px;" name="payment_received_from">
                                        <option <?php if (empty($tmp[0][0]['payment_received_from'])) echo 'selected="selected"'  ?>>Default</option>
                                        <?php foreach($mail_senders as $mail_sender): ?>
                                        <option value="<?php echo $mail_sender[0]['id'] ?>" <?php if ($mail_sender[0]['id'] == $tmp[0][0]['payment_received_from']) echo 'selected="selected"'  ?>><?php echo $mail_sender[0]['email'] ?></option>                                        
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="label">Cc:</td>
                                <td class="value">
                                    <input type="text" name="payment_received_cc" id="payment_received_cc" value="<?php echo !empty($tmp[0][0]['payment_received_cc'])?$tmp[0][0]['payment_received_cc']:'';?>">
                                </td>
                            </tr> 
                            <tr>
                                <td class="label"><?php echo __('subject')?>:</td>
                                <td  class="value"><input class="input in-input" name="payment_received_subject" value="<?php echo !empty($tmp[0][0]['payment_received_subject'])?$tmp[0][0]['payment_received_subject']:'';?>"  id="carrier_email_subject" />
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2"><span class="auto_summary_tag">{switch_alias}</span><span class="auto_summary_tag">{amount}</span><span class="auto_summary_tag">{receiving_time}</span></td>
                            </tr>
                            <tr>
                                <td class="label"><?php echo __('content')?>:</td>
                                <td  class="value"><textarea class="input in-textarea payment_received_content" name="payment_received_content" style="height: 100px; font-family: monospace; font-size: 12px;width:800px;" id="payment_received_content"><?php echo !empty($tmp[0][0]['payment_received_content'])?$tmp[0][0]['payment_received_content']:'';?></textarea>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </fieldset> 
            
            <fieldset>
                <legend style="cursor: pointer;" onclick="$('#trouble_tickets_mail').toggle()"><span id="ht-100013" ><?php echo __('Trouble tickets Email Template')?></span>»</legend>
                <div id="trouble_tickets_mail" style="display: none;">
                    <table class="form">

                        <tbody>
                            <tr>
                                <td class="label">From email:</td>
                                <td class="value">
                                    <select style="width:200px;" name="trouble_ticket_from">
                                        <option <?php if (empty($tmp[0][0]['trouble_ticket_from'])) echo 'selected="selected"'  ?>>Default</option>
                                        <?php foreach($mail_senders as $mail_sender): ?>
                                        <option value="<?php echo $mail_sender[0]['id'] ?>" <?php if ($mail_sender[0]['id'] == $tmp[0][0]['trouble_ticket_from']) echo 'selected="selected"'  ?>><?php echo $mail_sender[0]['email'] ?></option>                                        
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="label"><?php echo __('subject')?>:</td>
                                <td  class="value"><input class="input in-input" name="trouble_ticket_subject" value="<?php echo !empty($tmp[0][0]['trouble_ticket_subject'])?$tmp[0][0]['trouble_ticket_subject']:'';?>"  id="trouble_tickets_email_subject" />
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2"><span class="auto_summary_tag">{switch_alias}</span><span class="auto_summary_tag">{company_name}</span><span class="auto_summary_tag">{block_data}</span><span class="auto_summary_tag">{trunk_name}</span></td>
                            </tr>
                            <tr>
                                <td class="label"><?php echo __('content')?>:</td>
                                <td  class="value"><textarea class="input in-textarea trouble_ticket_content" name="trouble_ticket_content" style="height: 100px; font-family: monospace; font-size: 12px;width:800px;" id="trouble_ticket_content"><?php echo !empty($tmp[0][0]['trouble_ticket_content'])?$tmp[0][0]['trouble_ticket_content']:'';?></textarea>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </fieldset> 
            
            <fieldset>
                <legend style="cursor: pointer;" onclick="$('#send_cdr_mail').toggle()"><span id="ht-100013" ><?php echo __('Email CDR When Done Email Template')?></span>»</legend>
                <div id="send_cdr_mail" style="display: none;">
                    <table class="form">

                        <tbody>
                            <tr>
                                <td class="label">From email:</td>
                                <td class="value">
                                    <select style="width:200px;" name="send_cdr_from">
                                        <option <?php if (empty($tmp[0][0]['send_cdr_from'])) echo 'selected="selected"'  ?>>Default</option>
                                        <?php foreach($mail_senders as $mail_sender): ?>
                                        <option value="<?php echo $mail_sender[0]['id'] ?>" <?php if ($mail_sender[0]['id'] == $tmp[0][0]['send_cdr_from']) echo 'selected="selected"'  ?>><?php echo $mail_sender[0]['email'] ?></option>                                        
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="label">Cc:</td>
                                <td class="value">
                                    <input type="text" name="send_cdr_cc" id="send_cdr_cc" value="<?php echo !empty($tmp[0][0]['send_cdr_cc'])?$tmp[0][0]['send_cdr_cc']:'';?>">
                                </td>
                            </tr> 
                            <tr>
                                <td class="label"><?php echo __('subject')?>:</td>
                                <td  class="value"><input class="input in-input" name="send_cdr_subject" value="<?php echo !empty($tmp[0][0]['send_cdr_subject'])?$tmp[0][0]['send_cdr_subject']:'';?>"  id="trouble_tickets_email_subject" />
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2"><span class="auto_summary_tag">{switch_alias}</span><span class="auto_summary_tag">{start_date}</span><span class="auto_summary_tag">{end_date}</span><span class="auto_summary_tag">{customer_gmt}</span><span class="auto_summary_tag">{file_name}</span><span class="auto_summary_tag">{cdr_count}</span><span class="auto_summary_tag">{site_name}</span><span class="auto_summary_tag">{download_link}</span><span class="auto_summary_tag">{share_link}</span></td>
                            </tr>
                            <tr>
                                <td class="label"><?php echo __('content')?>:</td>
                                <td  class="value"><textarea class="input in-textarea send_cdr_content" name="send_cdr_content" style="height: 100px; font-family: monospace; font-size: 12px;width:800px;" id="send_cdr_content"><?php echo !empty($tmp[0][0]['send_cdr_content'])?$tmp[0][0]['send_cdr_content']:'';?></textarea>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </fieldset> 
            
            
            <fieldset>
                <legend style="cursor: pointer;" onclick="$('#rate_mail_success').toggle()"><span id="ht-100013" ><?php echo __('Notice of Receipt Template')?></span>»</legend>
                <div id="rate_mail_success" style="display: none;">
                    <table class="form">

                        <tbody>
                            <tr>
                                <td class="label">From email:</td>
                                <td class="value">
                                    <select style="width:200px;" name="rate_mail_success_from">
                                        <option <?php if (empty($tmp[0][0]['rate_mail_success_from'])) echo 'selected="selected"'  ?>>Default</option>
                                        <?php foreach($mail_senders as $mail_sender): ?>
                                        <option value="<?php echo $mail_sender[0]['id'] ?>" <?php if ($mail_sender[0]['id'] == $tmp[0][0]['rate_mail_success_from']) echo 'selected="selected"'  ?>><?php echo $mail_sender[0]['email'] ?></option>                                        
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="label"><?php echo __('subject')?>:</td>
                                <td  class="value"><input class="input in-input" name="rate_mail_success_subject" value="<?php echo !empty($tmp[0][0]['send_cdr_subject'])?$tmp[0][0]['rate_mail_success_subject']:'';?>"  id="trouble_tickets_email_subject" />
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2"><span class="auto_summary_tag">{to}</span></td>
                            </tr>
                            <tr>
                                <td class="label"><?php echo __('content')?>:</td>
                                <td  class="value"><textarea class="input in-textarea rate_mail_success_content" name="rate_mail_success_content" style="height: 100px; font-family: monospace; font-size: 12px;width:800px;" id="rate_mail_success_content"><?php echo !empty($tmp[0][0]['rate_mail_success_content'])?$tmp[0][0]['rate_mail_success_content']:'';?></textarea>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </fieldset> 
            
            <fieldset>
                <legend style="cursor: pointer;" onclick="$('#rate_mail_fail').toggle()"><span id="ht-100013" ><?php echo __('Notice of Rate Upload Failure')?></span>»</legend>
                <div id="rate_mail_fail" style="display: none;">
                    <table class="form">

                        <tbody>
                            <tr>
                                <td class="label">From email:</td>
                                <td class="value">
                                    <select style="width:200px;" name="rate_mail_fail_from">
                                        <option <?php if (empty($tmp[0][0]['rate_mail_fail_from'])) echo 'selected="selected"'  ?>>Default</option>
                                        <?php foreach($mail_senders as $mail_sender): ?>
                                        <option value="<?php echo $mail_sender[0]['id'] ?>" <?php if ($mail_sender[0]['id'] == $tmp[0][0]['rate_mail_fail_from']) echo 'selected="selected"'  ?>><?php echo $mail_sender[0]['email'] ?></option>                                        
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="label"><?php echo __('subject')?>:</td>
                                <td  class="value"><input class="input in-input" name="rate_mail_fail_subject" value="<?php echo !empty($tmp[0][0]['send_cdr_fail'])?$tmp[0][0]['rate_mail_fail_subject']:'';?>"  id="trouble_tickets_email_subject" />
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2"><span class="auto_summary_tag">{to}</span></td>
                            </tr>
                            <tr>
                                <td class="label"><?php echo __('content')?>:</td>
                                <td  class="value"><textarea class="input in-textarea rate_mail_fail_content" name="rate_mail_fail_content" style="height: 100px; font-family: monospace; font-size: 12px;width:800px;" id="rate_mail_fail_content"><?php echo !empty($tmp[0][0]['rate_mail_fail_content'])?$tmp[0][0]['rate_mail_fail_content']:'';?></textarea>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </fieldset> 
           

        </div><?php  if ($_SESSION['role_menu']['Configuration']['mailtmps']['model_w']) {?>
        <div id="form_footer">

            <input class="input in-submit" value="<?php echo __('submit')?>" type="submit">

            <input class="input in-button" value="<?php echo __('reset')?>" type="reset"   style="margin-left: 20px;">
            <!--  <input class="input in-button" value="<?php echo __('apply')?>" type="button">--> 
        </div><?php }?>
    </form>
 <script type="text/javascript" src="<?php echo $this->webroot; ?>tiny_mce/tiny_mce.js"></script>

<script type="text/javascript">
    
var toolbars = "styleselect,formatselect,fontselect,fontsizeselect,mybutton,bold,italic,underline,separator,strikethrough,justifyleft,justifycenter,justifyright, justifyfull,bullist,numlist,undo,redo,link,unlink";

tinyMCE.init({
        mode : "textareas",
        theme : "advanced",
        editor_selector : "invoice_content",
        theme_advanced_buttons1 : toolbars
});


tinyMCE.init({
        mode : "textareas",
        theme : "advanced",
        editor_selector : "payment_content",
        theme_advanced_buttons1 : toolbars
});

tinyMCE.init({
        mode : "textareas",
        theme : "advanced",
        editor_selector : "lowbalance_content",
        theme_advanced_buttons1 : toolbars
});

tinyMCE.init({
        mode : "textareas",
        theme : "advanced",
        editor_selector : "noc_email_content",
        theme_advanced_buttons1 : toolbars
});

tinyMCE.init({
        mode : "textareas",
        theme : "advanced",
        editor_selector : "carrier_email_content",
        theme_advanced_buttons1 : toolbars
});

tinyMCE.init({
        mode : "textareas",
        theme : "advanced",
        editor_selector : "alert_email_content",
        theme_advanced_buttons1 : toolbars
});

tinyMCE.init({
        mode : "textareas",
        theme : "advanced",
        editor_selector : "auto_summary_content",
        theme_advanced_buttons1 : toolbars
});

tinyMCE.init({
        mode : "textareas",
        theme : "advanced",
        editor_selector : "auto_balance_content",
        theme_advanced_buttons1 : toolbars
});

tinyMCE.init({
        mode : "textareas",
        theme : "advanced",
        editor_selector : "auto_cdr_content",
        theme_advanced_buttons1 : toolbars
});

tinyMCE.init({
        mode : "textareas",
        theme : "advanced",
        editor_selector : "payment_sent_content",
        theme_advanced_buttons1 : toolbars
});

tinyMCE.init({
        mode : "textareas",
        theme : "advanced",
        editor_selector : "payment_received_content",
        theme_advanced_buttons1 : toolbars
});

tinyMCE.init({
        mode : "textareas",
        theme : "advanced",
        editor_selector : "trouble_ticket_content",
        theme_advanced_buttons1 : toolbars
});

tinyMCE.init({
        mode : "textareas",
        theme : "advanced",
        editor_selector : "rate_mail_success_content",
        theme_advanced_buttons1 : toolbars
});

tinyMCE.init({
        mode : "textareas",
        theme : "advanced",
        editor_selector : "rate_mail_fail_content",
        theme_advanced_buttons1 : toolbars
});

</script>




    <script type="text/javascript">
        //<![CDATA[
        function repaintType()
        {
            if ($('#type').val() == '2') {
                $('#templates').hide();
                //$('#reseller_info').hide();
                $('#cell-percentage').show();
                $('#cell-percentage-type').show();
                $('#cell-orig-rate').show();
                $('#cell-term-rate').hide();
                $('#cell-msg-orig').hide();
                $('#cell-msg-term').hide();
                $('#cell-billinginfo').hide();
                $('#cell-mode').hide();
                $('#cell-calc-profit').show();
                $('#cell-profit_calc_way').show();
                $('#cell-sub').hide();
            } else {
                $('#templates').show();
                //$('#reseller_info').show();
                $('#cell-profit_calc_way').hide();
                $('#cell-percentage').hide();
                $('#cell-percentage-type').hide();
                $('#cell-orig-rate').hide();
                $('#cell-term-rate').show();
                $('#cell-msg-orig').show();
                $('#cell-msg-term').show();
                $('#cell-billinginfo').show();
                $('#cell-mode').show();
                $('#cell-calc-profit').hide();
                $('#cell-sub').show();
            }				
        }
        $(function () {
            repaintType();
            $('#type').bind('change', repaintType);
        });

        /*
         * Hide allowed credit in post-paid mode
         */
        function checkMode()
        {
            var mode = $('select[name=mode]').val();
            if (mode == '1') {
                $('#cell-credit input').attr('disabled', '').removeClass('disabled');
                $('#cell-term').hide();
            } else {
                $('#cell-credit input').attr('disabled', 'disabled').addClass('disabled');
                $('#cell-term').show();
            }
        }
        $(function () {
            checkMode();
            $('select[name=mode]').bind('change', checkMode);
            $('.whide').hide();
            if($('#calc_profit_date_type').val()==2){$('#combide').show();$('#calc_profit_day').show();}
            if($('#calc_profit_date_type').val()==3){$('#calc_profit_week').show();}
        });
        //]]>
    </script> 
</div>
<div class=" " style="display: none; top: 530px; left: 128px; right: auto;" id="tooltip">
    <h3 style="display: none;"></h3>
    <div class="body"><span id="ht-100013-tooltip" class=" ">This template is used when yht sends a low balance notification to client</span></div>
    <div style="display: none;" class="url"></div>
</div>
<!-- 如果验证没通过  将用户输入的表单信息重新显示 -->
<?php
$backform = $session->read('backform');//用户刚刚输入的表单数据
if (!empty($backform)) {
$session->del('backform');//清除错误信息

//将用户刚刚输入的数据显示到页面上
$d = array_keys($backform);
foreach($d as $k) {?>
<script type="text/javascript">
    //<![CDATA[
    document.getElementById("<?php echo $k?>").value = "<?php echo $backform[$k]?>";							
    //]]>
</script>
<?php
}
?>
<script>$('.whide').hide();if($('#calc_profit_date_type').val()==2){$('#combide').show();$('#calc_profit_day').show();}if($('#calc_profit_date_type').val()==3){$('#calc_profit_week').show();}</script>
<?php }?>
