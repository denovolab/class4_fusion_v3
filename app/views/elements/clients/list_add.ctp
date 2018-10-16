<?php echo $form->create ('Client', array ('action' => 'add','url'=>'/clients/add','id'=>'ClientForm'));?>
<?php 
if (!empty($post[0][0]['id'])) 
{
echo "<input type=\"hidden\" name=\"order_user_id\" value=\"{$post[0][0]['id']}\"></input>";
}
?>
<table class="cols">
    <col width="35%"/>
    <col width="38%"/>
    <col width="27%"/>
    <tr>
        <td style="vertical-align: top;"><!-- COLUMN 1 -->
            <?php //**********系统信息**************?>


            <fieldset>
                <legend><?php echo __('basicinfo',true)?></legend>
                <table class="form">
                    <tr>
                        <td align="right" width="25%"><?php echo __('Name')?>:</td>
                        <td align="left" width="70%">
                            <?php echo $form->input('name',array('label'=>false,'div'=>false,'type'=>'text','maxLength'=>'500','class'=>'input in-text', 'value'=>empty($post[0][0]['name'])?'':$post[0][0]['name'] ))?>
                        </td>
                    </tr>
                    <tr>
                        <td><?php echo __('status')?>:</td>
                        <td>
                            <?php 
                            $st=array('true'=>__('Active',true), 'false'=>__('Inactive',true));
                            echo $form->input('status',array('options'=>$st,'label'=>false,'div'=>false,'type'=>'select','class'=>'input in-text in-select'))
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td><span id="ht-100001" class="helptip" rel="helptip"><?php echo __('mode')?></span><span id="ht-100001-tooltip" class="tooltip">
                                If Prepaid selected - this client`s Balance+Credit value will be checked on RADIUS authorization, if Postpaid selected -
                                RADIUS authorization check is disabled</span>:</td>
                        <td>
                            <?php 
                            $st=array('1'=>__('Prepaid',true), '2'=>__('postpaid',true));
                            echo $form->input('mode',array('options'=>$st,'label'=>false,'div'=>false,'type'=>'select','class'=>'input in-text in-select'))
                            ?>
                        </td>
                    </tr>

                    <tr>
                        <td align="right" width="25%"><?php echo __('currency')?>:</td>
                        <td align="left" width="70%">
                            <?php echo $form->input('currency_id',array('options'=>$currency,'label'=>false ,'div'=>false,'type'=>'select','class'=>'input in-text in-select'));?>
                        </td>
                    </tr>
                    <?php  
                    $project_name=Configure::read('project_name');
                    if($project_name=='exchange'){
                    ?>		
                    <tr>
                        <td><?php echo __('Service Charge',true);?>:</td>
                        <td>
                            <?php echo $form->input('service_charge_id',array('options'=>$service_charge,'label'=>false ,'div'=>false,'type'=>'select'));?>
                        </td>
                    </tr>
                    <?php }?>


                    <tr>
                        <td><span id="ht-100002" class="helptip" rel="helptip"><?php echo __('allowedcredit')?></span>:</td>
                        <td style="text-align: left;">

                            <?php echo $form->input('allowed_credit',array('label'=>false,'value'=>'0.000', 'div'=>false, 'type'=>'text', 'class'=>'in-decimal input in-text', 'maxlength'=>'30','style'=>'width:100px'))?>
                            <label class='money'></label>
                            &nbsp;
                            <span id="unlimited_panel">
                            Unlimited
                            <?php echo $form->input('unlimited_credit',array('class'=>'in-decimal input in-checkbox','label'=>false,'div'=>false,'type'=>'checkbox'))?>
                            </span>
                        </td>
                    </tr>
                    <!--
                    <tr>
                        <td ><span id="ht-100002" class="helptip" rel="helptip"><?php echo __('lowprofit')?></span>:</td>
                        <td style="text-align: left;">
                            <?php echo $form->input('profit_margin',array('label'=>false,'value'=>'0','div'=>false,'type'=>'text','class'=>'in-decimal input in-text','maxlength'=>'6','style'=>'width:33%'))?>
                            <?php echo $xform->input('profit_type',array('options'=>Array(1=>'Percentage',2=>'Value'),'style'=>'width:33%'))?>
                        </td>
                    </tr>
                    -->
                    <tr>
                        <td align="right" width="25%"><?php echo __('CPS')?>:</td>
                        <td align="left" width="70%">
                            <?php echo $form->input('cps_limit',array('label'=>false,'div'=>false,'type'=>'text','maxLength'=>'100','class'=>'input in-text', 'value'=>empty($post[0][0]['cps_limit'])?'':$post[0][0]['cps_limit'] ))?>
                        </td>
                    </tr>
                    <tr>
                        <td align="right" width="25%"><?php echo __('Call limit')?>:</td>
                        <td align="left" width="70%">
                            <?php echo $form->input('call_limit',array('label'=>false,'div'=>false,'type'=>'text','maxLength'=>'100','class'=>'input in-text', 'value'=>empty($post[0][0]['call_limit'])?'':$post[0][0]['call_limit'] ))?>
                        </td>
                    </tr>
                    <tr>
                        <td align="right" width="25%"><?php echo __('Transaction Fee')?>:</td>
                        <td align="left" width="70%">
                            <?php echo $form->input('transaction_fee_id',array('options'=>$transation_fees,'label'=>false ,'div'=>false,'type'=>'select','class'=>'input in-text in-select'));?>
                        </td>
                    </tr>
                </table>
            </fieldset>
            <fieldset>
                <legend><?php echo __('Companyinfo')?></legend>
                <table class="form">
                    <tr>
                        <td align="right" width="25%"><?php echo __('Companyname')?>:</td>
                        <td align="left" width="70%">
                            <?php echo $form->input('company',array('maxlength'=>256,'label'=>false,'div'=>false,'class'=>'input in-text in-input', 'value'=>empty($post[0][0]['company_name']) ? '' : $post[0][0]['company_name'] ))?>
                        </td>
                    </tr>
                    <tr>
                        <td><?php echo __('address')?>:</td>
                        <td>
                            <?php echo $form->input('address',array('label'=>false,'div'=>false,'maxlength'=>'500','type'=>'textarea','class'=>'input in-text in-input', 'value'=>empty($post[0][0]['addr1']) ? '' : $post[0][0]['addr1']))?>
                        </td>
                    </tr>
                    <tr>
                        <td><?php echo __('Main e-mail',true);?>: </td>
                        <td> <?php echo $form->input('email',array('label'=>false,'div'=>false,  'maxLength'=>'100','class'=>'input in-text in-input', 'value'=>empty($post[0][0]['corporate_contact_email']) ? '' : $post[0][0]['corporate_contact_email']))?></td>
                    </tr>
                    <tr>
                        <td><?php echo __('NOC e-mail',true);?>: </td>

                        <td> <?php echo $form->input('noc_email',array('label'=>false,'div'=>false,  'maxLength'=>'100','class'=>'input in-text in-input'))?></td>
                    </tr>
                    <tr>
                        <td><?php echo __('Billing e-mail',true);?>: </td>
                        <td> <?php echo $form->input('billing_email',array('label'=>false,'div'=>false,  'maxLength'=>'100','class'=>'input in-text in-input', 'value'=>empty($post[0][0]['billing_contact_email']) ? '' : $post[0][0]['billing_contact_email']))?></td>
                    </tr>
                    <tr>
                        <td><?php echo __('Rates e-mail',true);?>: </td>
                        <td> <?php echo $form->input('rate_email',array('label'=>false,'div'=>false,  'maxLength'=>'100','class'=>'input in-text in-input'))?></td>
                    </tr>
                    <tr>
                        <td><?php echo __('Rate Delivery Email',true);?>: </td>
                        <td> <?php echo $form->input('rate_delivery_email',array('label'=>false,'div'=>false,  'maxLength'=>'100','class'=>'input in-text in-input'))?></td>
                    </tr>
                    <tr>
                        <td><?php echo __('Tax ID',true);?>: </td>
                        <td> <?php echo $form->input('tax_id',array('label'=>false,'div'=>false,'validate'=>'email','class'=>'input in-text in-input'))?></td>
                    </tr>
                    <tr>
                        <td ><?php echo __('Account Details',true);?>: </td>
                        <td> <?php echo $form->input('details',array('label'=>false,'div'=>false,  'rows'=>'8','class'=>'input in-text in-input'))?></td>
                    </tr>
                    <tr>
                        <td><?php echo __('Include Tax in Invoice',true);?>:</td>
                        <td>
                            <?php echo $form->input('include_tax',array('label'=>false,'div'=>false, 'class'=>'input in-text in-input'))?>
                            <span id="tax_area" style="display:none;"> 
                            <?php echo $form->input('tax',array('label'=>false,'div'=>false, 'class'=>'input in-text in-input', 'style'=>'width:30px;'))?>
                            %
                            <span>
                        </td>
                    </tr>
                </table>
            </fieldset>
             <fieldset>
                <legend><?php echo __('Short Call Charge',true)?></legend>
                <div>
                    If <?php echo $form->input('scc_percent',array('label'=>false,'style'=>'width:30px;','div'=>false))?> % overall invoice minute is below or equal to <?php echo $form->input('scc_bellow',array('label'=>false,'style'=>'width:30px;','div'=>false))?> second, then an
                    additional charge of <?php echo $form->input('scc_charge',array('label'=>false,'style'=>'width:40px;','div'=>false))?> will be applied to each call
                    <?php echo $form->input('scc_type',array('options'=>array('0'=>'meeting the short duration defined above', '1'=>'that exceed the defined percentage'),'label'=>false ,'div'=>false,'type'=>'select', 'style'=>'width:auto;'));?>
                    .
                </div>
            </fieldset>  
            <fieldset><legend><span id="ht-100012" class="helptip" rel="helptip">
                         <?php echo $form->input('is_daily_balance_notification',array('class'=>'in-decimal input in-checkbox','label'=>false,'div'=>false,'type'=>'checkbox'))?>
                        <?php echo __('lowbalancenotice')?></span><span id="ht-100012-tooltip" class="tooltip">Send notification when current balance + credit limit is lower than specified threshold. Leave field empty to disable notification.</span>
                </legend>
                <table class="form">
                    <tr>
                        <td><?php echo __('notifyclientbalance',true)?>:</td>
                        <td  style="text-align: left;">
                            <?php echo $form->input('notify_client_balance',array('class'=>'in-decimal input in-text','maxlength'=>'30','label'=>false,'div'=>false,'type'=>'text', 'style'=>'width:150px;' ))?>
                            <?php echo $form->input('notify_client_balance_type',array('options'=>array(0=>'Actual Balance', 1 => 'Percentage'),'label'=>false, 'style'=>'width:100px;' ,'div'=>false,'type'=>'select','class'=>'input in-text in-select','selected'=>0));?>
                        </td>
                    </tr>
                    <!--
                    <tr>
                        <td><?php echo __('notifyadminbalance',true)?>:</td>
                        <td style="text-align: left;">
                         <?php echo $form->input('notify_admin_balance',array('class'=>'in-decimal input in-text','maxlength'=>'30','label'=>false,'div'=>false,'type'=>'text'))?>
                         <label class="money"></label>
                        </td>
                    </tr>
                    -->
                    <tr>
                        <td></td>
                        <td  style="text-align: left;">
                            <div id="is_daily_balance_panel" style="display:none;">
                                <?php __('Number of days'); ?>:
                                <input type="text" name="data[Client][daily_balance_notification]" style="width:30px;" />
                                <br />
                                <?php __('Recipient'); ?>:
                                <select name="data[Client][daily_balance_recipient]" style="width:180px;">
                                    <option value="0">Partner's Billing Contact</option>
                                    <option value="1">Owner's Billing Contact</option>
                                    <option value="2">Both</option>
                                </select>
                            </div>
                        </td>
                    </tr>
                </table>
            </fieldset>
            <!-- / COLUMN 1 --></td><td style="vertical-align: top;"><!-- COLUMN 2 -->
            <?php //***************************************费率设置************************************************************?>
            <!--
            <fieldset><legend> <?php echo __('Short Call Charge',true)?></legend>
            <table class="form">
            <tr>
                <td><?php echo __('Below',true)?>:</td>
                <td><?php echo $form->input('scc_bellow',array('label'=>false,'div'=>false))?>seconds
                </td>
            
            </tr>
            <tr>
                <td><?php echo __('Percent',true)?>:</td>
                <td><?php echo $form->input('scc_percent',array('label'=>false,'div'=>false))?>%
                </td>
            </tr>
            <tr>
                <td><?php echo __('Add Charge',true)?>:</td>
                <td><?php echo $form->input('scc_charge',array('label'=>false,'div'=>false))?>
                </td>
            </tr>
            
            </table>
            </fieldset>
            -->                    
                             

            <?php //************************paymentsetting**********************************?>
            <fieldset>

                <legend>
                    <?php echo $form->checkbox('auto_invoicing',array('checked'=>false))?>
                    <label for="autoinvoice_enabled"><?php echo __('autoinvoicing',true)?></label></legend>
                <table class="form">
                    <tr>
                        <td><?php echo __('paymentterm',true)?>:</td>
                        <td>
                            <?php echo $form->input('payment_term_id',array('options'=>$paymentTerm,'label'=>false ,'div'=>false,'type'=>'select'));?>
                        </td>
                    </tr>
                    <tr>
                        <td align="right"><?php echo __('invoice type',true)?>:</td>

                        <td align="left">
                            <?php echo $form->input('auto_invoice_type',
                            array('options'=>array('client','vendor','both'),'label'=>false ,'div'=>false,'type'=>'select'));?>

                        </td>
                    </tr>
                    <tr>
                        <td align="right"><?php __('Starting From')?>:</td>
                        <td align="left">
                                            <?php  
                                                    echo $form->input('invoice_start_from',
                                                                            array('onfocus'=>"WdatePicker({dateFmt:'yyyy-MM-dd'});",  'class'=>"input in-text",'readonly'=>true,'label'=>false ,'div'=>false,'type'=>'text'));
                                                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td><?php echo __('invoiceformat',true)?>:</td>
                        <td><?php  
                            $route_type=array('1'=>'PDF','2'=>'Word');//,'2'=>'Excel','3'=>'HTML');
                            echo $form->input('invoice_format',
                            array('options'=>$route_type,'label'=>false ,'div'=>false,'type'=>'select'));
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td style="text-align: left;">

                            <?php //echo $form->checkbox('is_link_cdr'), ' Include link to CDR';?>



                            <?php echo $form->checkbox('attach_cdrs_list')?>
                            <?php echo __('attachcdrslist')?>
                            <?php echo $form->checkbox('invoice_show_details'), ' show detail';?>
                            <?php echo $form->checkbox('invoice_jurisdictional_detail'), ' Add Jurisdictional Detail';?>
                        </td>
                    </tr>
                    <tr>
                        <td align="right"><?php echo __('Time zone',true)?>:</td>

                        <td align="left">
                            <?php 
                            $zone_arr = array();
                            for ($i = -12; $i<=12; $i++)
                            {
                            $zone_str = $i<0 ? sprintf("-%02d:00", 0-$i) : sprintf("+%02d:00", $i);  		
                            $zone_arr[$zone_str] = 'GMT ' . $zone_str;
                            }
                            echo $form->input('invoice_zone',	array('options'=>$zone_arr,'label'=>false ,'div'=>false,'type'=>'select', 'selected'=>'+00:00'));
                            ?>

                        </td>
                    </tr>
                    <tr>
                        <td align="right"><?php echo __('No Invoice for Zero Traffic')?>:</td>

                        <td align="left">
                            <?php 
                            echo $form->input('invoice_zero',	array('options'=>array(1=>'Yes', 0=>'No'),'label'=>false ,'div'=>false,'type'=>'select', 'selected'=>1 ));
                            ?>

                        </td>
                    </tr>
                    <tr>
                        <td><?php echo __('CDR Compress Format',true)?>:</td>
                        <td>
                            <?php  
                            //$route_type=array('1'=>'Excel XLS','2'=>'CSV');
                            $route_type=array('3'=>'zip', '4'=>'tar.gz');
                            echo $form->input('cdr_list_format',
                            array('options'=>$route_type,'label'=>false ,'div'=>false,'type'=>'select'));
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
                             'style' => 'width:80px;', 'value' => 5,'label'=>false,'div'=>false))?>
                        </td>
                    </tr>
                    <tr>
                        <td><?php __('Rate Value'); ?>:</td>
                        <td>
                             <?php echo $form->input('rate_value',array('options'=>array('Average', 'Actual'),
                             'style' => 'width:80px;', 'value' => 0,'label'=>false,'div'=>false))?>
                        </td>
                    </tr>
                   
                    <tr>
                        <td><?php __('Email Invoice'); ?></td>
                        <td>
                            <?php echo $form->checkbox('email_invoice');?>
                        </td>
                    </tr>
                    <tr>
                        <td><?php __('Include Available Credit'); ?></td>
                        <td>
                            <?php empty($post['Client']['include_available_credit'])?$au='false':$au='checked';  ?>
                            <?php echo $form->checkbox('include_available_credit', array('checked' => $au));?>
                        </td>
                    </tr>
                    <tr>
                        <td><?php __('Include Breakout Summary'); ?></td>
                        <td>
                            <?php echo $form->checkbox('is_invoice_account_summary');?>
                             <?php 
                            echo $form->input('invoice_use_balance_type',array('options'=>array(0=>'use actual balance', 1=>'use mutual balance'),'label'=>false ,'div'=>false,'type'=>'select', 'selected'=>0 , 'style' => 'width:120px;'));
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td><?php __('Show Daily Usage'); ?></td>
                        <td>
                            <?php echo $form->checkbox('is_show_daily_usage');?>
                        </td>
                    </tr>
                    
                    <tr>
                        <td><?php __('Short Duration Call Surcharge detail'); ?></td>
                        <td>
                            <?php echo $form->checkbox('is_short_duration_call_surcharge_detail');?>
                        </td>
                    </tr>
                    
                    <tr>
                        <td><?php __('Include Summary of Payments'); ?></td>
                        <td>
                            <?php echo $form->checkbox('invoice_include_payment');?>
                        </td>
                    </tr>
                    <tr>
                        <td><?php __('Usage Detail Fields'); ?></td>
                        <td>
                            <select name="data[Client][usage_detail_fields][]" multiple="multiple">
                                <!--<option value="code_name">Code Name</option>-->
                                <option value="completed_calls"  selected="selected">Completed Calls</option>
                                <option value="interstate_minute">Interstate Minute</option>
                                <option value="intrastate_minute">Intrastate Minute</option>
                                <option value="indeterminate_minute">Indeterminate Minute</option>
                                <option value="total_minutes" selected="selected">Total Minutes</option>
                                <option value="total_charges" selected="selected">Total Charges</option>
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
                
                echo $form->input('auto_summary_period',array('options'=>$send_periods,'label'=>false ,'div'=>false,'type'=>'select',  'selected'=>'24' ));
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
                echo $form->input('auto_summary_hour',array('options'=>$send_times,'label'=>false ,'div'=>false,'type'=>'select',  'selected'=>'0' ));
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
                            echo $form->input('auto_send_zone',array('options'=>$azone_arr,'label'=>false ,'div'=>false,'type'=>'select', 'selected'=>'+00:00'));
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Include CDR</td>
                        <td>
                            <?php echo $form->input('auto_summary_include_cdr',array('class'=>'in-decimal input in-checkbox','label'=>false,'div'=>false,'type'=>'checkbox'))?>
                        </td>
                    </tr>
                    <tr>
                        <td><?php __('Recipient') ?></td>
                        <td>
                            <select name="data[Client][auto_daily_balance_recipient]" style="width:180px;">
                                <option value="0">Partner's Billing Contact</option>
                                <option value="1">Owner's Billing Contact</option>
                                <option value="2">Both</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td><?php __('Daily Usage Summary') ?>:</td>
                        <td  style="text-align: left;">
                            <?php echo $form->input('is_auto_summary',array('class'=>'in-decimal input in-checkbox','label'=>false,'div'=>false,'type'=>'checkbox'))?>
                            <br />
                            Non-Zero Only:
                            <?php echo $form->input('auto_summary_not_zero',array('class'=>'in-decimal input in-checkbox','label'=>false,'div'=>false,'type'=>'checkbox'))?>
                            <br />
                            Group By:
                            <select name="data[Client][auto_summary_group_by]" style="width:180px;">
                                <option value="0">By Country</option>
                                <option value="1">By Code Name</option>
                                <option value="2">By Code</option>
                            </select>
                        </td>
                    </tr>
                    
                    <tr>
                        <td><?php __('Daily Balance Summary') ?>:</td>
                        <td  style="text-align: left;">
                            <?php echo $form->input('is_auto_balance',array('class'=>'in-decimal input in-checkbox','label'=>false,'div'=>false,'type'=>'checkbox'))?>
                            &nbsp;
                            <?php __('Number of days'); ?>:
                            <input type="text" name="data[Client][numer_of_days_balance]" style="width:30px;"  value="1" />
                        </td>
                    </tr>
                    <tr>
                        <td>Daily CDR Generation</td>
                        <td><input type="checkbox" name="data[Client][daily_cdr_generation]" id="daily_cdr_generation" /></td>
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
                            echo $form->input('daily_cdr_generation_zone',array('options'=>$azone_arr,'label'=>false ,'div'=>false,'type'=>'select', 'selected'=>'+00:00'));
                            ?>
                        </td>
                    </tr>
                    <tr class="daily_cdr_generation_panel">
                        <td><?php __('CDR Type') ?></td>
                        <td>
                            <select name="data[Client][daily_cdr_generation_type]">
                                <option value="0">Incoming Calls</option>
                                <option value="1">Outgoing Calls</option>
                                <option value="2">Both</option>
                            </select>
                        </td>
                    </tr>
                </table>
            </fieldset>

            <!-- / COLUMN 2 --></td><td style="vertical-align: top;"><!-- COLUMN 3 -->
            <?php //************************client panel**********************************?>
            <fieldset>
                <legend>

                    <?php 

                    /*
                    if (empty($post[0][0]['id']))
                    {
                    echo $form->checkbox('is_panelaccess',array('checked'=>'checked'));
                    }
                    else*/
                    //{
                    echo $form->checkbox('is_panelaccess');
                    //}
                    ?>
                    <label for="cp_enabled"><?php echo __('Carrier Self-Service Portal',true);?></label>
                </legend>

                <table class="form">
                    <tr>
                        <td align="right" width="25%"><?php echo __('User Name',true)?>:</td>
                        <td align="left" width="70%">
                            <?php echo $form->input('login',array('label'=>false,'div'=>false,'type'=>'text','maxLength'=>'256'))?>
                        </td>
                    </tr>
                    <tr>
                        <td   style="width: 30%"><?php echo __('New Password',true)?>:</td>
                        <td> <?php echo $form->input('password',array('label'=>false,'div'=>false,'type'=>'password','maxLength'=>'16'));?></td>
                    </tr>

                    <!--<tr>
                        <td   style="width: 30%">DataBase Name:</td>
                        <td> <?php echo $form->input('database_name',array('label'=>false,'div'=>false,'type'=>'text','maxLength'=>'16'))?></td>
                    </tr>
                    --></table>
            </fieldset>
            <fieldset>
                <legend> <?php echo __('Permission',true);?> &nbsp;</legend>
                <table class="form">
                    <tr>
                        <td colspan="2" class="value">
                            <div class="cb_select" style="height:150px;"><!--
                             <div>
                                        <?php echo $form->checkbox('is_route')?>
                                    <label for="cp_modules-c_info">Route</label></div>
                                --><div>
                                    <?php echo $form->checkbox('is_client_info',array('checked'=>'checked'))?>
                                    <label for="cp_modules-c_info">Credit Balance</label></div>
                                <div>
                                    <?php echo $form->checkbox('is_invoices',array('checked'=>'checked'))?>
                                    <label for="cp_modules-c_invoices"><?php echo __('isInvoices')?></label></div>
                                <div>
                                    <?php echo $form->checkbox('is_rateslist')?>
                                    <label for="cp_modules-c_rates"><?php echo __('israteslist')?></label></div>
                                <!--<div>
                                    <?php echo $form->checkbox('is_mutualsettlements')?>
                                    <label for="cp_modules-c_stats_msa"><?php echo __('ismutualsettlements')?></label>
                                </div>-->
                                <div>
                                    <?php echo $form->checkbox('is_changepassword',array('checked'=>'checked'))?>
                                    <label for="cp_modules-c_chpass"><?php echo __('ischangepassword')?></label></div>
                                <div>
                                    <?php echo $form->checkbox('is_cdrslist',array('checked'=>'checked'))?>
                                    <label for="cp_modules-c_stats_cdrs"><?php echo __('CDRs List',true);?></label>
                                </div>
                            </div>





                        </td>
                    </tr>
                </table>
            </fieldset>
            <!-- / COLUMN 2  Report -->
            <!--<fieldset>
            <legend> Report  &nbsp;</legend>
            <table class="form">
            <tr>
                <td colspan="2" class="value">
                <div class="cb_select" style="height:150px;">
                        <div>
                            <?php echo $form->checkbox('is_spam')?>
                            <label for="cp_modules-c_info">Spam Report</label>
                            </div>
                            <div>
                            <?php echo $form->checkbox('is_location')?>
                        <label for="cp_modules-c_info">Location Report</label></div>
                        <div>
                            <?php echo $form->checkbox('is_orig_term')?>
                        <label for="cp_modules-c_info">Orig-Term Report</label></div>
                            <div>
                                                    <?php echo $form->checkbox('is_summaryreport')?>
                         <label for="cp_modules-c_stats_summary"><?php echo __('issummaryreport')?></label></div>
                        <div>
                                            <?php echo $form->checkbox('is_usage')?>
                        <label for="cp_modules-c_rates">Usage Report</label></div>
                        <div>
                         <?php echo $form->checkbox('is_cdrslist',array('checked'=>'checked'))?>
                         <label for="cp_modules-c_stats_cdrs">CDRs List</label>
                        </div>
                        <div>
                         <?php echo $form->checkbox('is_qos')?>
                            <label for="cp_modules-c_stats_msa">QoS Report</label></div>
                        
                        <div>
                 <?php echo $form->checkbox('is_discon')?>
                         <label for="cp_modules-c_chpass">Disconnect Causes</label></div>
                         
                         
                                  <div>
                 <?php echo $form->checkbox('is_bill_mismatch')?>
                         <label for="cp_modules-c_chpass">Billing Mismatch Report</label></div>
                         
                         
                   <div>
                 <?php echo $form->checkbox('is_active_call')?>
                         <label for="cp_modules-c_chpass">Active Call Report</label></div>
                         
                                <div>
                 <?php echo $form->checkbox('is_termin')?>
                         <label for="cp_modules-c_chpass">Termination Report</label></div>
                    </div>
                </td>
            </tr>
            </table>
            </fieldset>
            -->

            <!--<fieldset>
            <legend> Debug Tools  &nbsp;</legend>
            <table class="form">
            <tr>
                <td colspan="2" class="value">
                <div class="cb_select" style="height:150px;">
                
                            <div>
                            <?php echo $form->checkbox('is_rate_anal' )?>
                        <label for="cp_modules-c_info">Rates Analysis</label></div>
                        
                        
                                  <div>
                            <?php echo $form->checkbox('is_capture' )?>
                        <label for="cp_modules-c_info">SIP  Capture </label></div>
                        <div>
                            <?php echo $form->checkbox('is_call_simulation' )?>
                        <label for="cp_modules-c_info">Call  Simulation</label></div>
                        
                        
                      <div>
                                                    <?php echo $form->checkbox('is_ingress_sim' )?>
                         <label for="cp_modules-c_stats_summary">Ingress Trunk Simulation</label></div>
                      
                        <div>
                                            <?php echo $form->checkbox('is_egress_sim' )?>
                        <label for="cp_modules-c_rates">Egress  Trunk simulation</label></div>
                       
            
                    </div>
                </td>
            </tr>
            </table>
            </fieldset>
            --><?php //************************balancenotice**********************************?>

        </td></tr></table>
<?php if(0/**true/**@$list_add_type=='wizard'**/){?>


<div id="form_footer">
    <input type="submit" style='width:120px' id="submit"   value="Add Egress Trunk" class="input in-submit" onclick="jQuery('#ClientForm').attr('action','<?php echo $this->webroot?>clients/wizard?type=egress')"/>
    <input type="submit" style='width:120px' id="submit" value="Add Ingress Trunk" class="input in-submit" onclick="jQuery('#ClientForm').attr('action','<?php echo $this->webroot?>clients/wizard?type=ingress')"/>
    <input type="button"  value="<?php echo __('cancel',true);?>" onclick="location='<?php echo $this->webroot?>clients/index'"  class="input in-submit"/>
</div>
<?php }else{?>
<div id="form_footer">
    <input type="submit" id="submit" value="<?php echo __('submit')?>" class="input in-submit" />
    <input type="reset"  value="<?php echo __('reset')?>"  class="input in-submit"/>
</div>
<?php }?>
<?php echo $form->end();?>
<script type="text/javascript">
    //特殊表单验证（只能为数字（Float））
    jQuery(document).ready(
    function(){
        // jQuery('#ClientName,#ClientLogin').xkeyvalidate({type:'strNum'});
        jQuery('#ClientAllowedCredit,#ClientNotifyAdminBalance').xkeyvalidate({type:'Ip'});		
        // jQuery('#ClientProfitMargin').xkeyvalidate({type:'Num'});
        //jQuery('input[maxLength=32]').xkeyvalidate({type:'Email'});	
        jQuery('#ClientTaxId').xkeyvalidate({type:'Num'});
    }
);
</script>
<script type="text/javascript">
    jQuery('#ClientIsDailyBalanceNotification').disabled({id:'#ClientNotifyClientBalance,#ClientNotifyAdminBalance'});
</script>
<script type="text/javascript">
    jQuery(document).ready(function(){
        jQuery('#ClientForm').submit(function(){
            te= true;
            if(jQuery('#ClientName').val()==''){
                jQuery('#ClientName').addClass('invalid');
                jQuery.jGrowl(' Name is required',{theme:'jmsg-error'});
                te=false;
            }
                                
            if(/\-/.test(jQuery('#ClientAllowedCredit').val())) {
                jQuery('#ClientAllowedCredit').addClass('invalid');
                jQuery.jGrowl('Allowed Credit cannot be a negative number!',{theme:'jmsg-error'});
                te = false;
            }
		
            if(/[^0-9A-Za-z-\_\.\s]+/.test(jQuery('#ClientName').val())){
                jQuery('#ClientName').addClass('invalid');
                jQuery.jGrowl('Name,allowed characters: a-z,A-Z,0-9,-,_,space, maximum of 16 characters in length!',{theme:'jmsg-error'});
                te = false;
            }
            if(isNaN(jQuery('#ClientAllowedCredit').val())){
                jQuery('#ClientAllowedCredit').addClass('invalid');
                jQuery.jGrowl(' Allowed Credit mast number',{theme:'jmsg-error'});
                te = false;
            }
            if(jQuery('#ClientLogin').val()=='' && jQuery('#ClientIsPanelaccess').attr('checked')){
                jQuery('#ClientLogin').addClass('invalid');
                jQuery.jGrowl('User Name is required',{theme:'jmsg-error'});
                te = false;
            }
            if(jQuery('#ClientPassword').val()=='' && jQuery('#ClientIsPanelaccess').attr('checked')){
                jQuery('#ClientPassword').addClass('invalid');
                jQuery.jGrowl('New Password is required',{theme:'jmsg-error'});
                te = false;
            }
            if(jQuery('#ClientLogin').val()=='admin'){
                jQuery('#ClientLogin').jGrowlError(' login name must not as admin');
                te=false;
            }
            /*
            if(isNaN(jQuery('#ClientProfitMargin').val())){
                jQuery('#ClientProfitMargin').addClass('invalid');
                jQuery.jGrowl(' Profit Margin Credit mast number',{theme:'jmsg-error'});
                te = false;
            }
             */  		
               		
            if(jQuery('#ClientCurrencyId').val()==null) {
                jQuery('#ClientCurrencyId').addClass('invalid');
                jQuery.jGrowl('Currency must be created first!',{theme:'jmsg-error'});
                te = false;
            }
               		
            if(isNaN(jQuery('#ClientNotifyClientBalance').val()) && $('#ClientIsDailyBalanceNotification').attr('checked')){
                jQuery('#ClientNotifyClientBalance').addClass('invalid');
                jQuery.jGrowl('Notify Carriers, must contain numeric characters only!',{theme:'jmsg-error'});
                te = false;
            }
            /*
        if(isNaN(jQuery('#ClientNotifyAdminBalance').val())){
           jQuery('#ClientNotifyAdminBalance').addClass('invalid');
           jQuery.jGrowl('Notify admin: Notify Admin, must contain numeric characters only!',{theme:'jmsg-error'});
           te = false;
                                }
             */
            if(!/^-?[0-9]+%?$/.test(jQuery('#ClientNotifyClientBalance').val())&& $('#ClientIsDailyBalanceNotification').attr('checked')){
            	   jQuery('#ClientNotifyClientBalance').addClass('invalid');
            	   jQuery.jGrowl('Notify client must contain numeric characters only',{theme:'jmsg-error'});
                 te=false;
            }
            /*
       if(!jQuery.xkeyvalidate('#ClientNotifyAdminBalance',{type:'Ip'})||/^-{2,}[0-9]+/.test(jQuery('#ClientNotifyAdminBalance').val())){
           jQuery('#ClientNotifyAdminBalance').addClass('invalid');
           jQuery.jGrowl('Notify admin must contain numeric characters only',{theme:'jmsg-error'});
           te=false;
                    }*/
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
            if(!isNaN(jQuery('#ClientAllowedCredit').val())){
                if(!jQuery.xkeyvalidate('#ClientAllowedCredit',{type:'Ip'})||/^-{2,}[0-9]+/.test(jQuery('#ClientAllowedCredit').val())){
                    jQuery('#ClientAllowedCredit').addClass('invalid');
                    jQuery.jGrowl('Notify client must contain numeric characters only',{theme:'jmsg-error'});
                    te=false;
                }
            }
            /*
            if(!isNaN(jQuery('#ClientProfitMargin').val())){
                if(!jQuery.xkeyvalidate('#ClientProfitMargin',{type:'Ip'})||/^-{2,}[0-9]+/.test(jQuery('#ClientProfitMargin').val())){
                    jQuery('#ClientProfitMargin').addClass('invalid');
                    jQuery.jGrowl('Min. Profitability must contain numeric characters only',{theme:'jmsg-error'});
                    te=false;
                }
            }
            */
            if(jQuery('#ClientSccBellow').val()!=''){
                if(! /\d+|\./.test(jQuery('#ClientSccBellow').val())){
                    jQuery('#ClientSccBellow').addClass('invalid');
                    jQuery.jGrowl('Bellow, must contain numeric characters only!',{theme:'jmsg-error'});
                    te=false; 
                }
            }

	       
            if(jQuery('#ClientSccPercent').val()!=''){
                if(! /\d+|\./.test(jQuery('#ClientSccPercent').val())){
                    jQuery('#ClientSccPercent').addClass('invalid');
                    jQuery.jGrowl('Percent, must contain numeric characters only!',{theme:'jmsg-error'});
                    te=false; 
                }
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




            if(jQuery('#ClientSccCharge').val()!=''){
                if(! /\d+|\./.test(jQuery('#ClientSccCharge').val())){
                    jQuery('#ClientSccCharge').addClass('invalid');
                    jQuery.jGrowl('Add Charge, must contain numeric characters only!',{theme:'jmsg-error'});
                    te=false; 
                }
            }

            if(jQuery('#ht-100012').attr('checked')){
                if(jQuery('#ClientNotifyClientBalance').val()!=''){
            
                    /*
            if(! /^\D+\.?\D+$/.test(jQuery('#ClientNotifyAdminBalance').val())){
                jQuery.jGrowl('Notify admin: Notify Admin, must contain numeric characters only!',{theme:'jmsg-error'});
               te= false;
                               }
                     */
                               
             
                    if(! /^\D+\.?\D+$/.test(jQuery('#ClientNotifyClientBalance').val())){
                        jQuery.jGrowl('Notify client Balance must contain numeric characters only!',{theme:'jmsg-error'});
                        te= false;
                    }
                }
                if (Number(jQuery('#ClientNotifyClientBalance').val()) > 100 && $('#ClientNotifyClientBalanceType').val() == '1') {
                                          jQuery('#ClientNotifyClientBalance').addClass('invalid');
            	   jQuery.jGrowl('Notify client Balance must not greater than 100',{theme:'jmsg-error'});
                   te=false;
                                      }
            }
		   



			
            if(/\D/.test(jQuery('#ClientTaxId').val())){
                jQuery('#ClientTaxId').addClass('invalid');
                jQuery.jGrowl('Tax must nuber',{theme:'jmsg-error'});
                te=false;
            }
                                    
            var name_data=jQuery.ajaxData("<?php echo $this->webroot?>clients/check_name/"+jQuery('#ClientName').val());
            name_data=name_data.replace(/\n|\r|\t/g,"");
            if(name_data == 'false'){
                jQuery.jGrowlError(jQuery('#ClientName').val()+" is already in use!");
                te=false;
                                      
            }
            var data=jQuery.ajaxData({'url':"<?php echo $this->webroot?>clients/check_login/<?php echo array_keys_value($this->params,'pass.0')?>?login="+jQuery('#ClientLogin').val(), 'type':'POST'});
            if(data.indexOf('false') !== -1 &&  jQuery('#ClientIsPanelaccess').attr('checked')){
                jQuery.jGrowlError("login name is repeat!");
                te=false;
            }
            return te;
        });  
    });
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
        
        jQuery('#daily_cdr_generation').change(function() {
            if($(this).attr('checked')) {
                $('.daily_cdr_generation_panel').show();
            } else {
                $('.daily_cdr_generation_panel').hide();
            }
        }).trigger('change');
        
        jQuery('#include_payment_history').change(function() {
            if($(this).val() == '0') {
                $('#include_payment_history_days_panel').hide();
            } else {
                $('#include_payment_history_days_panel').show();
            }
        });
        
        jQuery('#include_payment_history').change();
       
        jQuery('#ClientAutoInvoicing').change(function(){
            checkCB();
            //jQuery('#ClientInvoiceShowDetails, #ClientInvoiceJurisdictionalDetail').attr('checked', $(this).attr('checked'));
            if ($(this).attr('checked')) {
                jQuery('#ClientInvoiceShowDetails, #ClientInvoiceJurisdictionalDetail').attr('checked', true).val('1');
            } else {
                jQuery('#ClientInvoiceShowDetails, #ClientInvoiceJurisdictionalDetail').val('1').removeAttr('checked');
            }
        });
        jQuery('#ClientIsPanelaccess').change(function(){
            checkCB();
        });
        checkCB();
        jQuery('#ClientLogin').attr('value','');
        jQuery('#ClientPassword').attr('value','');
    });
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
                $('#ht-100002').text('Allowed Credit');
            } else {
                jQuery('#ClientAllowedCredit').val(0).next().hide();
                jQuery('#unlimited_panel').hide();
                jQuery('#ClientUnlimitedCredit').attr('checked', false);
                $('#ht-100002').text('Test Credit');
            }
       });
       
       $('#ClientIsInvoiceAccountSummary').change(function() {
           if ($(this).is(':checked')) {
               $('#ClientInvoiceUseBalanceType').show();
           } else {
               $('#ClientInvoiceUseBalanceType').hide();
           }
       }).trigger("change");
       
       $('#ClientAutoSummaryPeriod').change(function() {
          var $this = $(this);
          if ($this.val() == '24')
              $this.parents('tr').next().show();
          else
              $this.parents('tr').next().hide();
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
       
       jQuery('#ClientMode').change();
        jQuery('#ClientCurrencyId').change(
        function(){
            jQuery('label.money').html(jQuery(this).find('option:selected').html());
        }
    ).change();
    });
       
</script>



