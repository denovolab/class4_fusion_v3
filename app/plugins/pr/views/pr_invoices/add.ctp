<style type="text/css">
    .form .value, .list-form .value{text-align:left;}
    span.tag {
        background: none repeat scroll 0 0 #6B9B20;
        border-color: #389ABE;
        border-radius: 3px 3px 3px 3px;
        color: #FFFFFF;
        line-height: normal;
        padding: 4px;
        text-shadow: none;
        display: block;
        float: left;
        font-family: helvetica;
        font-size: 13px;
        margin-bottom: 5px;
        margin-right: 5px;
        text-decoration: none;
    }
</style>
<div id="title">
    <h1><?php __('CreatingInvoice')?></h1>
    <ul id="title-menu">
        <li>
            <a href="<?php echo $this->webroot ?>/pr/pr_invoices/view/1" class="link_back">
                <img width="10" height="5" alt="" src="<?php echo $this->webroot ?>images/icon_back_white.png"><?php echo __('goback',true);?>
            </a>
        </li>
    </ul>
</div>
<div class="container">
    <?php echo $form->create ('Invoice', array ('url' => '/pr/pr_invoices/add/' . $type,'name'=>'form1','id'=>'form1'));?>
    <input class="input in-hidden" name="query[id_clients]" value="" id="query-id_clients" type="hidden">
    <table class="form"  style="width: 80%;text-align:left;">
        <tbody>
            <tr>
                <td colspan="4" id="tag_td">
                    <input type="hidden" id="carriers" name="carriers" value="">
                </td>
            </tr>
            <tr>
                <td class="label"> <?php  __('Carriers')?>  </td>
                <td id="client_cell" class="value">
                    <input type="button" id="choose_client" value="Choose...">
                </td>
                <td class="label label4"><?php echo __('Invoice Date',true);?>(show):</td>
                <td class="value value4">
                    <input type="text" id="invoice_time" value="<?php echo date("Y-m-d");?>" readonly="readonly" class="input in-text wdate" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd'});" name="invoice_time" realvalue="">
                </td>
            </tr>
            <tr>
<!--                <td class="label label4"><?php __('InvoiceNo')?>:</td>
                <td class="value value4"><input type="text" id="invoice_number" value="" name="invoice_number" class="input in-text"> <small class="note">(empty = auto)</small></td>-->
                <td class="label label4"><?php echo __('Invoice Date',true);?> / <span rel="helptip" class="helptip" id="ht-100001">Due (days)</span><span class="tooltip" id="ht-100001-tooltip">A number of days, when invoice is expected to be paid</span>:</td>
                <td class="value value4">
                    <input type="text" id="due_date" value="<?php echo date("Y-m-d"); ?>" readonly="readonly" class="input in-text wdate" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd'});" name="due_date" realvalue="">
                </td>
                <td class="label label4"><?php __('State')?>:</td>
                <td class="value value4">

                    <select id="state" name="state" class="input in-select">
                        <option selected="selected" value="0">normal</option>
                        <option value="1">to send </option>
                        <option value="2"> to verify </option></select>
                </td>
            </tr>
            <tr>
                <td><?php __('Rate Value')?>:</td>
                <td>
                       <select name="rate_value" style="width:100px;">
                           <option value="0" selected>Average</option>
                           <option value="1">Actual</option>
                        </select> 
                </td>
                <td><?php __('Rate Decimal Place')?>:</td>
                <td>
                    <select name="decimal_place" style="width:80px;">
                        <?php for($i = 0; $i <= 10; $i++): ?>
                        <option <?php if ($i == 5) echo 'selected="selected"'; ?>><?php echo $i; ?></option>
                        <?php endfor; ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td><?php __('Usage Detail Fields'); ?></td>
                <td>
                    <select name="usage_detail_fields[]" multiple="multiple">
                        <!--<option value="code_name">Code Name</option>-->
                        <option value="completed_calls" selected="selected">Completed Calls</option>
                        <option value="interstate_minute">Interstate Minute</option>
                        <option value="intrastate_minute">Intrastate Minute</option>
                        <option value="indeterminate_minute">Indeterminate Minute</option>
                        <option value="total_minutes" selected="selected">Total Minutes</option>
                        <option value="total_charges" selected="selected">Total Charges</option>
                    </select>
                </td>
                <td></td>
                <td>
                     <?php
                    $a_type = 0;
                    switch ($type) {
                    case '1':
                    $a_type = 0;
                    break;
                    case '3':
                    $a_type = 1;
                    break;
                    case '5':
                    $a_type = 2;
                    break;
                    }
                    ?>
                    <input type="hidden" name="type" value="<?php echo $a_type; ?>" />
                    <ul>
                        <li>
                            Include Call Detail
                            <input type="checkbox" checked="checked" name="include_detail" />
                        </li>
                        <li>
                            Add Jurisdictional Detail
                            <input type="checkbox" checked="checked" name="jur_detail" />
                        </li>
                        <li>
                            Include Breakout Summary
                            <input type="checkbox" id="ClientIsInvoiceAccountSummary" checked="checked" name="is_invoice_account_summary" />
                            <select id="ClientInvoiceUseBalanceType" style="width:120px;" name="invoice_use_balance_type">
                                <option value="0">use actual balance</option>
                                <option value="1">use mutual balance</option>
                            </select>
                        </li>
                        <li>
                            Show Daily Usage
                            <input type="checkbox" name="is_show_daily_usage" />
                        </li>
                        <li>
                            Short Duration Call Surcharge
                            <input type="checkbox" name="is_show_short_duration_usage" />
                        </li>
                        <li>
                            Include Summary of Payments
                            <input type="checkbox" name="is_show_payments" />
                        </li>
                    </ul>
                </td>
            </tr>
        </tbody>
    </table>
    <fieldset style="margin-top: 15px;">
        <legend>
            <span style="margin-left: 5px; border-left: 2px solid rgb(238, 238, 238);" id="output-block">
                &nbsp; <?php echo __('Output in',true);?> &nbsp;
                <select id="output" style="width: 80px;" name="output_type" class="input in-select">
                    <option value="0">PDF</option>
                    <option value="1">Word</option>
                    <option value="2">HTML</option></select>                &nbsp;
            </span>
        </legend>
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
                                <td><table class="in-date">
                                        <tbody><tr>
                                                <td><input type="text" id="query-stop_date-wDt" class="wdate in-text input"  style="width: 120px;"    onchange="setPeriod('custom')"
                                                           readonly="readonly" 
                                                           onkeydown="setPeriod('custom')" value="" name="stop_date"></td>
                                                <td></td>
                                            </tr>
                                        </tbody></table>

                                </td>
                                <td><input type="text" id="query-stop_time-wDt" onchange="setPeriod('custom')"
                                           readonly="readonly" 
                                           onkeydown="setPeriod('custom')" style="width: 60px;" value="23:59:59" name="stop_time" class="input in-text"></td>


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
        </table>


        <div style="" id="t-generate">
            <table class="form">
                <col style="width: 14%;">
                <col style="width: 86%;">
                <tbody>
                    <tr>
                        <td class="label"></td>
                        <td class="value">
                            <div style="padding: 0pt 0pt 15px 60px; overflow: hidden; display: none;" id="columns">
                                <div style="width: 100px; float: left; margin-right: 10px;">
                                    <input type="checkbox" checked="checked" id="fields-account" value="account" name="fields[]" class="input in-checkbox">                <label for="fields-account"><?php echo __('Account',true);?></label>
                                </div>
                                <div style="width: 100px; float: left; margin-right: 10px;">
                                    <input type="checkbox" id="fields-code_country" value="code_country" name="fields[]" class="input in-checkbox">                <label for="fields-code_country"><?php echo __('Country',true);?></label>
                                </div>
                                <div style="width: 100px; float: left; margin-right: 10px;">
                                    <input type="checkbox" id="fields-code_name" value="code_name" name="fields[]" class="input in-checkbox">                <label for="fields-code_name"><?php echo __('Destination',true);?></label>
                                </div>
                                <div style="width: 100px; float: left; margin-right: 10px;">
                                    <input type="checkbox" id="fields-code" value="code" name="fields[]" class="input in-checkbox">                <label for="fields-code"><?php echo __('Codes',true);?></label>
                                </div>
                                <div style="clear: both;"></div>            <div style="width: 100px; float: left; margin-right: 10px;">
                                    <input type="checkbox" id="fields-rate" value="rate" name="fields[]" class="input in-checkbox">                <label for="fields-rate"><?php echo __('Rate',true);?></label>
                                </div>
                                <div style="width: 100px; float: left; margin-right: 10px;">
                                    <input type="checkbox" id="fields-calls" value="calls" name="fields[]" class="input in-checkbox">                <label for="fields-calls"><?php echo __('Calls',true);?></label>
                                </div>
                                <div style="width: 100px; float: left; margin-right: 10px;">
                                    <input type="checkbox" id="fields-minutes" value="minutes" name="fields[]" class="input in-checkbox">                <label for="fields-minutes"><?php echo __('Minutes',true);?></label>
                                </div>
                                <div style="width: 100px; float: left; margin-right: 10px;">
                                    <input type="checkbox" id="fields-cost" value="cost" name="fields[]" class="input in-checkbox">                <label for="fields-cost"><?php echo __('Cost',true);?></label>
                                </div>
                            </div>
                        </td>
                    </tr>
                </tbody><tbody id="products_block">
                    <tr>
                        <td class="label"></td>
                        <td class="value"></td>
                    </tr>
                </tbody>
                <!--<tbody><tr>
                    <td class="label">&nbsp;</td>
                    <td class="value"><div><input type="checkbox" id="cdr_generate" onchange="updateCdrOutput();" value="1" name="cdr_generate" class="input in-checkbox"> <label for="cdr_generate"><span rel="helptip" class="helptip" id="ht-100005">Add CDRs list to the invoice</span><span class="tooltip" id="ht-100005-tooltip">Creates CDR file for invoicing period and attaches it to invoice.</span></label> 
                    <a class="note" onclick="$('#cdr_columns').toggle();return false;" href="#">select columns »</a>
                  
                   </div></td>
                </tr>
                <tr>
                    <td class="label"></td>
                    <td class="value">
                        <div style="padding: 0pt 0pt 15px 60px; overflow: hidden; display: none;" id="cdr_columns">
                                                <div style="width: 120px; float: left;">
                            <input type="checkbox" checked="checked" id="cdr_fields-call_time" value="call_time" name="cdr_fields[]" class="input in-checkbox">                <label for="cdr_fields-call_time">Call Date</label>
                        </div>
                                                <div style="width: 120px; float: left;">
                            <input type="checkbox" id="cdr_fields-gw_ip" value="gw_ip" name="cdr_fields[]" class="input in-checkbox">                <label for="cdr_fields-gw_ip">Gateway IP</label>
                        </div>
                                                <div style="width: 120px; float: left;">
                            <input type="checkbox" id="cdr_fields-gw_name" value="gw_name" name="cdr_fields[]" class="input in-checkbox">                <label for="cdr_fields-gw_name">Gateway Name</label>
                        </div>
                                                <div style="width: 120px; float: left;">
                            <input type="checkbox" id="cdr_fields-code_country" value="code_country" name="cdr_fields[]" class="input in-checkbox">                <label for="cdr_fields-code_country"><?php echo __('Country',true);?></label>
                        </div>
                                    <div style="clear: both;"></div>            <div style="width: 120px; float: left;">
                            <input type="checkbox" checked="checked" id="cdr_fields-code_name" value="code_name" name="cdr_fields[]" class="input in-checkbox">                <label for="cdr_fields-code_name">Code Name</label>
                        </div>
                                                <div style="width: 120px; float: left;">
                            <input type="checkbox" checked="checked" id="cdr_fields-code" value="code" name="cdr_fields[]" class="input in-checkbox">                <label for="cdr_fields-code">Code</label>
                        </div>
                                                <div style="width: 120px; float: left;">
                            <input type="checkbox" id="cdr_fields-account" value="account" name="cdr_fields[]" class="input in-checkbox">                <label for="cdr_fields-account">Account Name</label>
                        </div>
                                                <div style="width: 120px; float: left;">
                            <input type="checkbox" id="cdr_fields-src_number" value="src_number" name="cdr_fields[]" class="input in-checkbox">                <label for="cdr_fields-src_number">Src Number</label>
                        </div>
                                                <div style="width: 120px; float: left;">
                            <input type="checkbox" checked="checked" id="cdr_fields-dst_number" value="dst_number" name="cdr_fields[]" class="input in-checkbox">                <label for="cdr_fields-dst_number">Dst Number</label>
                        </div>
                                    <div style="clear: both;"></div>            <div style="width: 120px; float: left;">
                            <input type="checkbox" id="cdr_fields-session_time" value="session_time" name="cdr_fields[]" class="input in-checkbox">                <label for="cdr_fields-session_time">Session Time</label>
                        </div>
                                                <div style="width: 120px; float: left;">
                            <input type="checkbox" checked="checked" id="cdr_fields-billed_time" value="billed_time" name="cdr_fields[]" class="input in-checkbox">                <label for="cdr_fields-billed_time">Billed Time</label>
                        </div>
                                                <div style="width: 120px; float: left;">
                            <input type="checkbox" checked="checked" id="cdr_fields-rate" value="rate" name="cdr_fields[]" class="input in-checkbox">                <label for="cdr_fields-rate">Rate</label>
                        </div>
                                                <div style="width: 120px; float: left;">
                            <input type="checkbox" checked="checked" id="cdr_fields-cost" value="cost" name="cdr_fields[]" class="input in-checkbox">                <label for="cdr_fields-cost">Cost</label>
                        </div>
                                    <div style="clear: both;"></div>            <div style="width: 120px; float: left;">
                            <input type="checkbox" id="cdr_fields-vh_name" value="vh_name" name="cdr_fields[]" class="input in-checkbox">                <label for="cdr_fields-vh_name">VoIP Gateway</label>
                        </div>
                                    </div>
                    </td>        
                </tr>
               
                <tr id="cdr_output_row">
                    <td class="label">CDRs List Format:</td>
                    <td class="value"><select id="cdr_output" style="width: 100px;" name="cdr_output" class="input in-select"><option value="csv">Excel CSV</option><option selected="selected" value="xls">Excel XLS</option></select></td>
                </tr>
                
                <tr>
                    <td class="label">&nbsp;</td>
                    <td class="value">
                        <fieldset style="margin-top: 10px;" class="listSection">
                        <legend>Additional items <a onclick="addItem(); return false;" href="#">
                        <img  src="<?php echo $this->webroot?>images/add.png"> Add new item</a></legend>
                        <table width="100%" id="add_positions" style="display: none;">
                        <tbody><tr>
                            <td colspan="2">
                            <table class="list">
                            <thead>
                            <tr>
                                <td width="63%"><span rel="helptip" class="helptip" id="ht-100006">Item</span><span class="tooltip" id="ht-100006-tooltip">An additional invoice row that can hold any service or product in addition to main invoice body</span></td>
                                <td width="18%"><span rel="helptip" class="helptip" id="ht-100007">Price</span><span class="tooltip" id="ht-100007-tooltip">Price value for respective additional invoice line item</span></td>
                                <td width="17%" class="last">&nbsp;</td>
                            </tr>
                            </thead>
                            <tbody id="entries">
                            <tr style="display: none;" id="tpl-entries" class="row-1">
                                <td calss="value"><input type="text" name="_positions[%n][name]" class="input in-text"></td>
                                <td class="value"><input type="text" style="text-align: right;" name="_positions[%n][price]" class="input in-text"></td>
                                <td class="last"><a rel="delete" href="#"><img width="16" height="16" src="<?php echo $this->webroot?>images/delete.png"></a></td>
                            </tr>
                            </tbody>
                            </table>
                            </td>
                        </tr>
                        </tbody></table>
                        </fieldset>
                    
                    </td>
                </tr>
                <tr>
                    <td class="label">&nbsp;</td>
                    <td class="value"><small class="note">Please note, that if client has Tax Value specified, it will be added to the total value of the invoice.</small></td>
                </tr>
               
                </tbody>
                -->
            </table>

        </div>
    </fieldset>

    <div id="form_footer">
        <input type="submit" value="<?php echo __('submit',true);?>" class="input in-submit">
        <input type="button" value="Reset" onclick="winClose();" class="input in-button">
    </div>
    <?php echo $form->end();?>
    <div id="dd"> </div> 
    <link rel="stylesheet" type="text/css" href="<?php echo $this->webroot?>easyui/themes/default/easyui.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $this->webroot?>easyui/themes/icon.css">
    <script type="text/javascript" src="<?php echo $this->webroot?>easyui/jquery.easyui.min.js"></script>
    <script type="text/javascript" src="<?php echo $this->webroot?>js/jquery.autocomplete.js"></script>
    <script type="text/javascript">
        //&lt;![CDATA[
        var _ss_ids_client = {'id_clients': 'query-id_clients', 'id_clients_name': 'query-id_clients_name'};
        function showClients ()
        {
            ss_ids_custom['client'] = _ss_ids_client;
            winOpen('<?php echo $this->webroot ?>clients/ss_client?types=2&type=0', 500, 530);
        }
        var lastId = 0;
        function addItem(row)
        {
            lastId++;
            if (!row || !row['id']) {
                row = {};
            }

            // fix row values
            for (k in row) { if (row[k] == null) row[k] = ''; }

            // prepare row
            var tRow = $('#tpl-entries').clone().appendTo($('#entries'));
            tRow.attr('id', 'row-'+lastId).show();

            // set names / values
            tRow.find('input,select').each(function () {
                var name = $(this).attr('name').substring(1).replace('%n', lastId);
                var field = name.substring(name.lastIndexOf('[')+1, name.length-1);
                $(this).attr('id', field+'-'+lastId);
                $(this).attr('name', name);
                $(this).val(row[field]);
            });
    
            // remove of the row
            tRow.find('a[rel=delete]').click(function () {
                $(this).closest('tr').remove();
                return false;
            });
    
            // styles
            initForms(tRow);
            initList();
            $('#add_positions').show();
        }                                                                                                                                                                                                

        /**
         * Watch for type of invoice generation 
         */


        function updateDirection() {
            if ($('#direction').val() == 'in') {
                $('#products_block').hide();
            } else {
                $('#products_block').show();
            }
        }
        updateDirection();

        function updateCurrency() { 

            var currency = $('#id_currencies option:selected').text();
            $('#total_stats_cur').html(currency);
            $('#total_other_cur').html(currency);
        }
        updateCurrency();

        function updateCdrOutput() {
            if ($('#cdr_generate').attr('checked')) {
                $('#cdr_output_row').show();
            } else {
                $('#cdr_output_row').hide();
            }
        }
        updateCdrOutput();

        //function setPeriod(type, current)
        //{
        //    if (type != 'custom') {
        //       period = calcPeriod(type, current);
        //       $('#period_start-wDt').val(period['startDate']);
        //       $('#start_time-wDt').val(period['startTime']);
        //       $('#period_finish-wDt').val(period['stopDate']);
        //       $('#stop_time-wDt').val(period['stopTime']);
        //    }
        //    $('#smartPeriod').val(type);
        //}
        //setPeriod('prevWeek');


        //]]&gt;
    </script></div>
<script type="text/javascript">
    $(document).ready(function() {
        $("#query-id_clients_name").autocomplete({
            url: '<?php echo $this->webroot?>clients/getManualClient1',
            sortFunction: function(a, b, filter) {
                var f = filter.toLowerCase();
                var fl = f.length;
                var a1 = a.value.toLowerCase().substring(0, fl) == f ? '0' : '1';
                var a1 = a1 + String(a.data[0]).toLowerCase();
                var b1 = b.value.toLowerCase().substring(0, fl) == f ? '0' : '1';
                var b1 = b1 + String(b.data[0]).toLowerCase();
                if (a1 > b1) {
                    return 1;
                }
                if (a1 < b1) {
                    return -1;
                }
                return 0;
            },
            showResult: function(value, data) {
                return '<span style="color:lack">' + value + '</span>';
            }
        });
        
        
        var $choose_client = $('#choose_client');
        var $dd = $('#dd');
        var $tag_td = $('#tag_td');
        var $assign_client = null;
        var $carriers = $('#carriers');
        var carriers = new Array();
        var $tags = $('span.tag a');
        
        $choose_client.click(function() {
            
            var $search_name = null;
            
            $dd.dialog({  
                title: 'Carriers',  
                width: 960,  
                height: 600,  
                closed: false,  
                cache: false,  
                resizable: true,
                href: '<?php echo $this->webroot?>pr/pr_invoices/show_carriers',  
                modal: true,
                buttons:[{
                        text:'Close',
                        handler:function(){
                            $dd.dialog('close');
                        }
                }],
                onLoad: function() {
                    $search_name = $('#search_name');
                    $assign_client = $('.assign_client');
                    $search_name.bind('keypress', function(event) {
                        if (event.keyCode == "13")
                        {
                            $dd.dialog('refresh', '<?php echo $this->webroot?>pr/pr_invoices/show_carriers/' + $search_name.val()); 
                        }
                    });
                    $assign_client.click(function() {
                        var $this = $(this);
                        carriers.push($this.attr('client_id'));
                        $tag_td.append('<span class="tag"><span>'+ $this.attr('client_name') +'  </span><a client_id="'+ $this.attr('client_id')  +'" href="###">x</a></span>');
                    });
                }
            });

            $dd.dialog('refresh', '<?php echo $this->webroot?>pr/pr_invoices/show_carriers');  
       
       });
       
       $tags.live('click', function() {
           var $this = $(this);
           var client_id = $this.attr('client_id');
           
           for (var i = 0; i < carriers.length; i++) 
           {
                if (client_id == carriers[i]) 
                {
                    carriers.splice(i, 1);
                    break;
                }
           }
           
            $this.parent().remove();
            
       });
       
       $('#ClientIsInvoiceAccountSummary').change(function() {
           if ($(this).is(':checked')) {
               $('#ClientInvoiceUseBalanceType').show();
           } else {
               $('#ClientInvoiceUseBalanceType').hide();
           }
       }).trigger("change");
       
       $("#form1").submit(function() {
           $carriers.val(carriers.join(','));
           return true;
       });
        
    });
</script>