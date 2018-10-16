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
                <legend style="cursor: pointer;" onclick="$('#no_route_available_mail').toggle()"><span ><?php echo __('No Route Available Alert Email Template')?></span>»</legend>
                <div id="no_route_available_mail" style="display: none;">
                    <table class="form">

                        <tbody>
                            
                            <tr>
                                
                                <td class="label">From email:</td>
                                <td class="value">
                                    <select  name="no_route_available_alert_email_from">
                                        <option <?php if (empty($tmp[0][0]['no_route_available_alert_email_from'])) echo 'selected="selected"'  ?>>Default</option>
                                        <?php foreach($mail_senders as $mail_sender): ?>
                                        <option value="<?php echo $mail_sender[0]['id'] ?>" <?php if ($mail_sender[0]['id'] == $tmp[0][0]['no_route_available_alert_email_from']) echo 'selected="selected"'  ?>><?php echo $mail_sender[0]['email'] ?></option>                                        
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                            </tr>
                            
                            <tr>
                                <td class="label"><?php echo __('subject')?>:</td>
                                <td  class="value"><input class="input in-input" name="no_route_available_alert_email_subject" value="<?php echo !empty($tmp[0][0]['no_route_available_alert_email_subject'])?$tmp[0][0]['no_route_available_alert_email_subject']:'';?>"  />
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2"><span class="auto_summary_tag">{code_name}{company_name}{Trunk_Name_Destination}</span></td>
                            </tr>
                            <tr>
                                <td class="label"><?php echo __('content')?>:</td>
                                <td class="value"><textarea class="input in-textarea" name="no_route_available_alert_email_content" style="height: 100px; font-family: monospace; font-size: 12px;" ><?php echo !empty($tmp[0][0]['no_route_available_alert_email_content'])?$tmp[0][0]['no_route_available_alert_email_content']:'';?></textarea>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </fieldset> 
             
            
            <fieldset>
                <legend style="cursor: pointer;" onclick="$('#target_match_mail').toggle()"><span ><?php echo __('Target Match Alert Email Template')?></span>»</legend>
                <div id="target_match_mail" style="display: none;">
                    <table class="form">

                        <tbody>
                            
                            <tr>
                                
                                <td class="label">From email:</td>
                                <td class="value">
                                    <select  name="target_match_alert_email_from">
                                        <option <?php if (empty($tmp[0][0]['target_match_alert_email_from'])) echo 'selected="selected"'  ?>>Default</option>
                                        <?php foreach($mail_senders as $mail_sender): ?>
                                        <option value="<?php echo $mail_sender[0]['id'] ?>" <?php if ($mail_sender[0]['id'] == $tmp[0][0]['target_match_alert_email_from']) echo 'selected="selected"'  ?>><?php echo $mail_sender[0]['email'] ?></option>                                        
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                            </tr>
                            
                            <tr>
                                <td class="label"><?php echo __('subject')?>:</td>
                                <td  class="value"><input class="input in-input" name="target_match_alert_email_subject" value="<?php echo !empty($tmp[0][0]['target_match_alert_email_subject'])?$tmp[0][0]['target_match_alert_email_subject']:'';?>"  />
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2"><span class="auto_summary_tag">{code_name}{company_name}</span></td>
                            </tr>
                            <tr>
                                <td class="label"><?php echo __('content')?>:</td>
                                <td  class="value"><textarea class="input in-textarea" name="target_match_alert_email_content" style="height: 100px; font-family: monospace; font-size: 12px;" ><?php echo !empty($tmp[0][0]['target_match_alert_email_content'])?$tmp[0][0]['target_match_alert_email_content']:'';?></textarea>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </fieldset>
            
            <fieldset>
                <legend style="cursor: pointer;" onclick="$('#rate_watch_mail').toggle()"><span ><?php echo __('Rate Watch Alert Email Template')?></span>»</legend>
                <div id="rate_watch_mail" style="display: none;">
                    <table class="form">

                        <tbody>
                            
                            <tr>
                                
                                <td class="label">From email:</td>
                                <td class="value">
                                    <select  name="rate_watch_alert_email_from">
                                        <option <?php if (empty($tmp[0][0]['rate_watch_alert_email_from'])) echo 'selected="selected"'  ?>>Default</option>
                                        <?php foreach($mail_senders as $mail_sender): ?>
                                        <option value="<?php echo $mail_sender[0]['id'] ?>" <?php if ($mail_sender[0]['id'] == $tmp[0][0]['rate_watch_alert_email_from']) echo 'selected="selected"'  ?>><?php echo $mail_sender[0]['email'] ?></option>                                        
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                            </tr>
                            
                            <tr>
                                <td class="label"><?php echo __('subject')?>:</td>
                                <td  class="value"><input class="input in-input" name="rate_watch_alert_email_subject" value="<?php echo !empty($tmp[0][0]['rate_watch_alert_email_subject'])?$tmp[0][0]['rate_watch_alert_email_subject']:'';?>"  />
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2"><span class="auto_summary_tag">{code_name}{company_name}{Destination_Request_Rate_Sell_Trunk_Sell_Rate}</span></td>
                            </tr>
                            <tr>
                                <td class="label"><?php echo __('content')?>:</td>
                                <td  class="value"><textarea class="input in-textarea" name="rate_watch_alert_email_content" style="height: 100px; font-family: monospace; font-size: 12px;" ><?php echo !empty($tmp[0][0]['rate_watch_alert_email_content'])?$tmp[0][0]['rate_watch_alert_email_content']:'';?></textarea>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </fieldset>
            
            <fieldset>
                <legend style="cursor: pointer;" onclick="$('#route_update_mail').toggle()"><span ><?php echo __('Route Update Alert Email Template')?></span>»</legend>
                <div id="route_update_mail" style="display: none;">
                    <table class="form">

                        <tbody>
                            
                            <tr>
                                
                                <td class="label">From email:</td>
                                <td class="value">
                                    <select  name="route_update_alert_email_from">
                                        <option <?php if (empty($tmp[0][0]['route_update_alert_email_from'])) echo 'selected="selected"'  ?>>Default</option>
                                        <?php foreach($mail_senders as $mail_sender): ?>
                                        <option value="<?php echo $mail_sender[0]['id'] ?>" <?php if ($mail_sender[0]['id'] == $tmp[0][0]['route_update_alert_email_from']) echo 'selected="selected"'  ?>><?php echo $mail_sender[0]['email'] ?></option>                                        
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                            </tr>
                            
                            <tr>
                                <td class="label"><?php echo __('subject')?>:</td>
                                <td  class="value"><input class="input in-input" name="route_update_alert_email_subject" value="<?php echo !empty($tmp[0][0]['route_update_alert_email_subject'])?$tmp[0][0]['route_update_alert_email_subject']:'';?>"  />
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2"><span class="auto_summary_tag">{company_name}{Trunk_Name_Destination}</span></td>
                            </tr>
                            <tr>
                                <td class="label"><?php echo __('content')?>:</td>
                                <td  class="value"><textarea class="input in-textarea" name="route_update_alert_email_content" style="height: 100px; font-family: monospace; font-size: 12px;" ><?php echo !empty($tmp[0][0]['route_update_alert_email_content'])?$tmp[0][0]['route_update_alert_email_content']:'';?></textarea>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </fieldset>
            
            <fieldset>
                <legend style="cursor: pointer;" onclick="$('#rate_update_mail').toggle()"><span ><?php echo __('Rate Update Alert Email Template')?></span>»</legend>
                <div id="rate_update_mail" style="display: none;">
                    <table class="form">

                        <tbody>
                            
                            <tr>
                                
                                <td class="label">From email:</td>
                                <td class="value">
                                    <select  name="rate_update_alert_email_from">
                                        <option <?php if (empty($tmp[0][0]['rate_update_alert_email_from'])) echo 'selected="selected"'  ?>>Default</option>
                                        <?php foreach($mail_senders as $mail_sender): ?>
                                        <option value="<?php echo $mail_sender[0]['id'] ?>" <?php if ($mail_sender[0]['id'] == $tmp[0][0]['rate_update_alert_email_from']) echo 'selected="selected"'  ?>><?php echo $mail_sender[0]['email'] ?></option>                                        
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                            </tr>
                            
                            <tr>
                                <td class="label"><?php echo __('subject')?>:</td>
                                <td  class="value"><input class="input in-input" name="rate_update_alert_email_subject" value="<?php echo !empty($tmp[0][0]['rate_update_alert_email_subject'])?$tmp[0][0]['rate_update_alert_email_subject']:'';?>"  />
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2"><span class="auto_summary_tag">{company_name}{Destination_Rate_Effective_Date_Interval_Min_Time}{trunk_name}</span></td>
                            </tr>
                            <tr>
                                <td class="label"><?php echo __('content')?>:</td>
                                <td  class="value"><textarea class="input in-textarea" name="rate_update_alert_email_content" style="height: 100px; font-family: monospace; font-size: 12px;" ><?php echo !empty($tmp[0][0]['rate_update_alert_email_content'])?$tmp[0][0]['rate_update_alert_email_content']:'';?></textarea>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </fieldset>
            
            <fieldset>
                <legend style="cursor: pointer;" onclick="$('#low_balance_mail').toggle()"><span ><?php echo __('Low Balance Alert Email Template')?></span>»</legend>
                <div id="low_balance_mail" style="display: none;">
                    <table class="form">

                        <tbody>
                            
                            <tr>
                                
                                <td class="label">From email:</td>
                                <td class="value">
                                    <select  name="low_balance_alert_email_from">
                                        <option <?php if (empty($tmp[0][0]['low_balance_alert_email_from'])) echo 'selected="selected"'  ?>>Default</option>
                                        <?php foreach($mail_senders as $mail_sender): ?>
                                        <option value="<?php echo $mail_sender[0]['id'] ?>" <?php if ($mail_sender[0]['id'] == $tmp[0][0]['low_balance_alert_email_from']) echo 'selected="selected"'  ?>><?php echo $mail_sender[0]['email'] ?></option>                                        
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                            </tr>
                            
                            <tr>
                                <td class="label"><?php echo __('subject')?>:</td>
                                <td  class="value"><input class="input in-input" name="low_balance_alert_email_subject" value="<?php echo !empty($tmp[0][0]['low_balance_alert_email_subject'])?$tmp[0][0]['low_balance_alert_email_subject']:'';?>"  />
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2"><span class="auto_summary_tag">{company_name}{balance}</span></td>
                            </tr>
                            <tr>
                                <td class="label"><?php echo __('content')?>:</td>
                                <td  class="value"><textarea class="input in-textarea" name="low_balance_alert_email_content" style="height: 100px; font-family: monospace; font-size: 12px;" ><?php echo !empty($tmp[0][0]['low_balance_alert_email_content'])?$tmp[0][0]['low_balance_alert_email_content']:'';?></textarea>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </fieldset>
            
            <fieldset>
                <legend style="cursor: pointer;" onclick="$('#select_route_up_mail').toggle()"><span ><?php echo __('Selected Route Updated Alert Template')?></span>»</legend>
                <div id="select_route_up_mail" style="display: none;">
                    <table class="form">

                        <tbody>
                            
                            <tr>
                                
                                <td class="label">From email:</td>
                                <td class="value">
                                    <select  name="select_route_up_email_from">
                                        <option <?php if (empty($tmp[0][0]['select_route_up_email_from'])) echo 'selected="selected"'  ?>>Default</option>
                                        <?php foreach($mail_senders as $mail_sender): ?>
                                        <option value="<?php echo $mail_sender[0]['id'] ?>" <?php if ($mail_sender[0]['id'] == $tmp[0][0]['select_route_up_email_from']) echo 'selected="selected"'  ?>><?php echo $mail_sender[0]['email'] ?></option>                                        
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                            </tr>
                            
                            <tr>
                                <td class="label"><?php echo __('subject')?>:</td>
                                <td  class="value"><input class="input in-input" name="select_route_up_email_subject" value="<?php echo !empty($tmp[0][0]['select_route_up_email_subject'])?$tmp[0][0]['select_route_up_email_subject']:'';?>"  />
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2"><span class="auto_summary_tag">{company_name}</span></td>
                            </tr>
                            <tr>
                                <td class="label"><?php echo __('content')?>:</td>
                                <td  class="value"><textarea class="input in-textarea" name="select_route_up_email_content" style="height: 100px; font-family: monospace; font-size: 12px;" ><?php echo !empty($tmp[0][0]['select_route_up_email_content'])?$tmp[0][0]['select_route_up_email_content']:'';?></textarea>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </fieldset>
            
            <fieldset>
                <legend style="cursor: pointer;" onclick="$('#new_invoice_posted_mail').toggle()"><span ><?php echo __('New Invoice Posted Alert Email Template')?></span>»</legend>
                <div id="new_invoice_posted_mail" style="display: none;">
                    <table class="form">

                        <tbody>
                            
                            <tr>
                                
                                <td class="label">From email:</td>
                                <td class="value">
                                    <select  name="new_invoice_posted_mail_alert_email_from">
                                        <option <?php if (empty($tmp[0][0]['new_invoice_posted_mail_alert_email_from'])) echo 'selected="selected"'  ?>>Default</option>
                                        <?php foreach($mail_senders as $mail_sender): ?>
                                        <option value="<?php echo $mail_sender[0]['id'] ?>" <?php if ($mail_sender[0]['id'] == $tmp[0][0]['new_invoice_posted_mail_alert_email_from']) echo 'selected="selected"'  ?>><?php echo $mail_sender[0]['email'] ?></option>                                        
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                            </tr>
                            
                            <tr>
                                <td class="label"><?php echo __('subject')?>:</td>
                                <td  class="value"><input class="input in-input" name="new_invoice_posted_mail_alert_email_subject" value="<?php echo !empty($tmp[0][0]['new_invoice_posted_mail_alert_email_subject'])?$tmp[0][0]['new_invoice_posted_mail_alert_email_subject']:'';?>"  />
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2"><span class="auto_summary_tag">{company_name}</span></td>
                            </tr>
                            <tr>
                                <td class="label"><?php echo __('content')?>:</td>
                                <td  class="value"><textarea class="input in-textarea" name="new_invoice_posted_mail_alert_email_content" style="height: 100px; font-family: monospace; font-size: 12px;" ><?php echo !empty($tmp[0][0]['new_invoice_posted_mail_alert_email_content'])?$tmp[0][0]['new_invoice_posted_mail_alert_email_content']:'';?></textarea>
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
                                    <select  name="exchange_auto_summary_from">
                                        <option <?php if (empty($tmp[0][0]['exchange_auto_summary_from'])) echo 'selected="selected"'  ?>>Default</option>
                                        <?php foreach($mail_senders as $mail_sender): ?>
                                        <option value="<?php echo $mail_sender[0]['id'] ?>" <?php if ($mail_sender[0]['id'] == $tmp[0][0]['exchange_auto_summary_from']) echo 'selected="selected"'  ?>><?php echo $mail_sender[0]['email'] ?></option>                                        
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                            </tr>
                            
                            <tr>
                                <td class="label"><?php echo __('subject')?>:</td>
                                <td  class="value"><input class="input in-input" name="exchange_auto_summary_subject" value="<?php echo !empty($tmp[0][0]['exchange_auto_summary_subject'])?$tmp[0][0]['exchange_auto_summary_subject']:'';?>"  id="carrier_email_subject" />
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2"><span class="auto_summary_tag">
                                        {client_name}{Beginning_Balance}{Buy_Amount}{Sell_Amount}{Ending_Balance}{Allowed_Credit}{Remaining_Credit}{date}
                                </td>
                            </tr>
                            <tr>
                                <td class="label"><?php echo __('content')?>:</td>
                                <td  class="value"><textarea class="input in-textarea" name="exchange_auto_summary_content" style="height: 100px; font-family: monospace; font-size: 12px;" id="noc_email_content"><?php echo !empty($tmp[0][0]['exchange_auto_summary_content'])?$tmp[0][0]['exchange_auto_summary_content']:'';?></textarea>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </fieldset>
            
            <fieldset>
                <legend style="cursor: pointer;" onclick="$('#finance_alert_mail').toggle()"><span id="ht-100013" ><?php echo __('Finance Alert Email Template')?></span>»</legend>
                <div id="finance_alert_mail" style="display: none;">
                    <table class="form">

                        <tbody>
                            
                            <tr>
                                
                                <td class="label">From email:</td>
                                <td class="value">
                                    <select  name="finance_alert_from">
                                        <option <?php if (empty($tmp[0][0]['finance_alert_from'])) echo 'selected="selected"'  ?>>Default</option>
                                        <?php foreach($mail_senders as $mail_sender): ?>
                                        <option value="<?php echo $mail_sender[0]['id'] ?>" <?php if ($mail_sender[0]['id'] == $tmp[0][0]['finance_alert_from']) echo 'selected="selected"'  ?>><?php echo $mail_sender[0]['email'] ?></option>                                        
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                            </tr>
                            
                            <tr>
                                <td class="label"><?php echo __('subject')?>:</td>
                                <td  class="value"><input class="input in-input" name="finance_alert_subject" value="<?php echo !empty($tmp[0][0]['finance_alert_subject'])?$tmp[0][0]['finance_alert_subject']:'';?>"  id="finance_alert_subject" />
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2"><span class="auto_summary_tag">
                                       {minutes}{alert_name}{client_balance}{client_name}
                                </td>
                            </tr>
                            <tr>
                                <td class="label"><?php echo __('content')?>:</td>
                                <td  class="value"><textarea class="input in-textarea" name="finance_alert_content" style="height: 100px; font-family: monospace; font-size: 12px;" id="finance_alert_content"><?php echo !empty($tmp[0][0]['finance_alert_content'])?$tmp[0][0]['finance_alert_content']:'';?></textarea>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </fieldset>
            
            <fieldset>
                <legend style="cursor: pointer;" onclick="$('#buy_qos_alert_mail').toggle()"><span id="ht-100013" ><?php echo __('Buy Qos Alert Email Template')?></span>»</legend>
                <div id="buy_qos_alert_mail" style="display: none;">
                    <table class="form">

                        <tbody>
                            
                            <tr>
                                
                                <td class="label">From email:</td>
                                <td class="value">
                                    <select  name="buy_qos_alert_from">
                                        <option <?php if (empty($tmp[0][0]['buy_qos_alert_from'])) echo 'selected="selected"'  ?>>Default</option>
                                        <?php foreach($mail_senders as $mail_sender): ?>
                                        <option value="<?php echo $mail_sender[0]['id'] ?>" <?php if ($mail_sender[0]['id'] == $tmp[0][0]['buy_qos_alert_from']) echo 'selected="selected"'  ?>><?php echo $mail_sender[0]['email'] ?></option>                                        
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                            </tr>
                            
                            <tr>
                                <td class="label"><?php echo __('subject')?>:</td>
                                <td  class="value"><input class="input in-input" name="buy_qos_alert_subject" value="<?php echo !empty($tmp[0][0]['buy_qos_alert_subject'])?$tmp[0][0]['buy_qos_alert_subject']:'';?>"  id="buy_qos_alert_subject" />
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2"><span class="auto_summary_tag">
                                        {alert_name}{trunk_name}{asr}{acd}{pdd}
                                </td>
                            </tr>
                            <tr>
                                <td class="label"><?php echo __('content')?>:</td>
                                <td  class="value"><textarea class="input in-textarea" name="buy_qos_alert_content" style="height: 100px; font-family: monospace; font-size: 12px;" id="buy_qos_alert_content"><?php echo !empty($tmp[0][0]['buy_qos_alert_content'])?$tmp[0][0]['buy_qos_alert_content']:'';?></textarea>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </fieldset>
            
            <fieldset>
                <legend style="cursor: pointer;" onclick="$('#sell_qos_alert_mail').toggle()"><span id="ht-100013" ><?php echo __('Sell Qos Alert Email Template')?></span>»</legend>
                <div id="sell_qos_alert_mail" style="display: none;">
                    <table class="form">

                        <tbody>
                            
                            <tr>
                                
                                <td class="label">From email:</td>
                                <td class="value">
                                    <select  name="sell_qos_alert_from">
                                        <option <?php if (empty($tmp[0][0]['sell_qos_alert_from'])) echo 'selected="selected"'  ?>>Default</option>
                                        <?php foreach($mail_senders as $mail_sender): ?>
                                        <option value="<?php echo $mail_sender[0]['id'] ?>" <?php if ($mail_sender[0]['id'] == $tmp[0][0]['sell_qos_alert_from']) echo 'selected="selected"'  ?>><?php echo $mail_sender[0]['email'] ?></option>                                        
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                            </tr>
                            
                            <tr>
                                <td class="label"><?php echo __('subject')?>:</td>
                                <td  class="value"><input class="input in-input" name="sell_qos_alert_subject" value="<?php echo !empty($tmp[0][0]['sell_qos_alert_subject'])?$tmp[0][0]['sell_qos_alert_subject']:'';?>"  id="sell_qos_alert_subject" />
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2"><span class="auto_summary_tag">
                                        {alert_name}{trunk_name}{asr}{acd}{pdd}
                                </td>
                            </tr>
                            <tr>
                                <td class="label"><?php echo __('content')?>:</td>
                                <td  class="value"><textarea class="input in-textarea" name="sell_qos_alert_content" style="height: 100px; font-family: monospace; font-size: 12px;" id="sell_qos_alert_content"><?php echo !empty($tmp[0][0]['sell_qos_alert_content'])?$tmp[0][0]['sell_qos_alert_content']:'';?></textarea>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </fieldset>
            
            
            
            
           

        </div><?php  if ($_SESSION['role_menu']['Configuration']['mailtmpsexchange']['model_w']) {?>
        <div id="form_footer">

            <input class="input in-submit" value="<?php echo __('submit')?>" type="submit">

            <input class="input in-button" value="<?php echo __('reset')?>" type="reset"   style="margin-left: 20px;">
            <!--  <input class="input in-button" value="<?php echo __('apply')?>" type="button">--> 
        </div><?php }?>
    </form>
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