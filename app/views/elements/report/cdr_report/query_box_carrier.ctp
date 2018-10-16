<fieldset class="query-box" style=" clear:both;overflow:hidden;margin-top:10px;">
    <div class="search_title"><img src="<?php
                                   echo $this->webroot?>images/search_title_icon.png" />
        <?php __('search')?>
    </div>
    <script
        type="text/javascript">

            //设置每个字段所对应的隐藏域
            var _ss_ids_client = {'id_clients': 'query-id_clients', 'id_clients_name': 'query-id_clients_name'};
            var _ss_ids_rate = {'id_rates': 'query-id_rates', 'id_rates_name': 'query-id_rates_name',	'account': 'query-account', 'tz': 'query-tz', 'id_currencies': 'query-id_currencies'};
            var _ss_ids_client_term = {'id_clients': 'query-id_clients_term', 'id_clients_name': 'query-id_clients_name_term'};
            var _ss_ids_code_name = {'code_name': 'query-code_name'};
            var _ss_ids_code = {'code': 'query-code', 'id_code_decks': 'query-id_code_decks'};
    </script>
    <?php
    $url="/".$this->params['url']['url'];
    //if($rate_type=='spam'){$url='/cdrreports/summary_reports/spam/';}else{$url='/cdrreports/summary_reports/';}
    echo $form->create ('Cdr', array ('type'=>'get','url' => $url ,'id'=>'report_form',
    'onsubmit'=>"if ($('#query-output').val() == 'web') loading();"));?>
    <?php echo $this->element('search_report/search_js_show')?> <?php echo $appCommon->show_page_hidden();?>
    <input type="hidden" value="searchkey" name="searchkey" />
    <input
        class="input in-hidden" type="hidden" name="query[id_clients_term]"
        value="" id="query-id_clients_term" />
    <input class="input in-hidden"
           name="query[id_clients]" value="" id="query-id_clients" type="hidden">
    <input class="input in-hidden" name="query[id_rates]" value=""
           id="query-id_rates" type="hidden">
    <table class="form" style="width: 100%">
        <tbody>
            <?php echo $this->element('report/form_period',array('group_time'=>false, 'gettype'=>'<select id="query-output"
                                                                                                      onchange="repaintOutput();" name="query[output]"
                                                                                                      class="input in-select">
            <option value="web">Web</option>
            <option value="csv">Excel CSV</option>
<!--            <option value="xls">Excel XLS</option>-->
            <option value="email">Email when done</option>
        </select>'))?>
        <tr>
            <td class="label"><?php echo __('TERM',true);?> <?php echo __('code_name')?>:</td>
            <td class="value" ><input type="text"
                                      id="query-code_name" 
                                      value="" name="query[code_name]" class="input in-text">
                <?php echo $this->element('search_report/ss_clear_input_select');?></td>
            <td class="label"  ><?php echo __('Duration',true);?>:</td>
            <td class="value"  ><select id="query-duration" name="query[duration]"
                                        class="input in-select">
                    <option value="" selected="selected">all</option>
                    <option value="nonzero">non-zero</option>
                    <option value="zero">zero</option>
                </select></td>
            <td colspan="2" style="padding-left: 10px;width: 25%;" valign="top" rowspan="6" ><div align="left"><?php echo __('Show Fields',true);?>:</div>
                <?php 
                echo $form->select('Cdr.field', $cdr_field , $show_field_array,array('id'=>'query-fields',  'style'=>'width:100%; height: 200px;', 'name'=>'query[fields]','type' => 'select', 'multiple' => true),false);
                ?></td>
        </tr>
        <tr>
            <td class="label" ><?php echo __('TERM',true);?>
                <?php __('code')?>
                :</td>
            <td class="value"><input type="text"
                                     id="query-code" 
                                     name="query[code]" class="input in-text">
                <?php echo $this->element('search_report/ss_clear_input_select');?></td>
            <td class="label"><?php echo __('Result/Code',true);?>:</td>
            <td class="value"><select id="query-res_status"
                                      onchange="$('#query-disconnect_cause').val($('#query-res_status').val());"
                                      name="query[res_status]" class="input in-select">
                    <option value="">all</option>
                    <option value="200">success</option>
                    <option value="300">multiple</option>
                    <option value="301">moved permanently</option>
                    <option value="302">moved temporaily</option>
                    <option value="305">use proxy</option>
                    <option value="380">alternative service</option>
                    <option value="400">bad request</option>
                    <option value="401">unauthorized</option>
                    <option value="402">payment required</option>
                    <option value="403">forbidden</option>
                    <option value="404">not found</option>
                    <option value="405">method no allowed</option>
                    <option value="406">not acceptable</option>
                    <option value="407">proxy authentication required</option>
                    <option value="408">request timeout</option>
                    <option value="410">gone</option>
                    <option value="413">request entity too large</option>
                    <option value="414">request-url too long</option>
                    <option value="415">unsupported media type</option>
                    <option value="416">unsupported url scheme</option>
                    <option value="420">bad extension</option>
                    <option value="421">extension required</option>
                    <option value="423">interval too brief</option>
                    <option value="480">temporarily unavailable</option>
                    <option value="481">call/transaction does not exist</option>
                    <option value="482">loop detected</option>
                    <option value="483">too many hops</option>
                    <option value="484">address incomplete</option>
                    <option value="485">ambiguous</option>
                    <option value="486">busy here</option>
                    <option value="487">request terminated</option>
                    <option value="488">not acceptable here</option>
                    <option value="491">request pending</option>
                    <option value="493">undecipherable</option>
                    <option value="500">server internal error</option>
                    <option value="501">not implemented</option>
                    <option value="502">bad gateway</option>
                    <option value="503">service unavailable</option>
                    <option value="504">server time-out </option>
                    <option value="505">version not supported </option>
                    <option value="513">message too large </option>
                    <option value="600">busy everywhere </option>
                    <option value="603">decline </option>
                    <option value="604">does not exist anywhere </option>
                    <option value="606">not acceptable </option>
                </select>
                <input type="text" id="query-disconnect_cause"
                       style="width: 35px;" value="" name="query[disconnect_cause]"
                       class="input in-text"></td>
        </tr>
        <tr>
            <td class="label"><?php echo __('Release Cause',true);?>:</td>
            <td class="value"><?php 
                $type=$appCdr->show_release_cause();
                echo $form->input('cdr_release_cause',
                array('options'=>$type,'name'=>'cdr_release_cause','label'=>false ,'div'=>false,'type'=>'select'));
                ?>
                <?php echo $this->element('search_report/ss_clear_input_select');?></td>

            <!--  
                            <td class="label"><span rel="helptip" class="helptip" id="ht-100001">TERM <?php __('codedecks')?></span>
                            <span class="tooltip" id="ht-100001-tooltip"> <b>Use pre-assigned</b>
                            &mdash; means usage of code decks assigned to each pulled client or
                            rate table. <br>
                            <br>
                            If you will <b>specify</b> a code deck, all code names will be
                            rewritten using names from selected code deck, so all data will be
                            unified by code names. </span>:</td>
                            
                            
                            
                            <td class="value">
                            <?php echo $form->input('code_deck',	array('options'=>$code_deck,'empty'=>'','label'=>false ,'div'=>false,'type'=>'select'));?>
        </td>
            -->
            <td class="label"><?php __('currency')?></td>
            <td id="client_cell" class="value"><?php echo $form->input('currency',	array('options'=>$currency,'empty'=>'','label'=>false ,'div'=>false,'type'=>'select'));?><?php echo $this->element('search_report/ss_clear_input_select');?></td>
        </tr>
        <tr>
            <td class="label"><?php echo __('type')?>:</td>
            <td class="value"><?php 
                $type=array(''=>__('all',true),'orig'=>__('origination',true),'term'=>__('termination',true));
                echo $form->input('report_type',
                array('options'=>$type,'label'=>false ,'div'=>false,'type'=>'select'));
                ?>
                <?php echo $this->element('search_report/ss_clear_input_select');?></td>
            <td class="label"><?php echo __('Cost',true);?>:</td>
            <td class="value"><select id="query-cost" name="query[cost]"
                                      class="input in-select">
                    <option value="">all</option>
                    <option value="nonzero">non-zero</option>
                    <option value="zero">zero</option>
                </select>
                <?php echo $this->element('search_report/ss_clear_input_select');?></td>
        </tr>
        <tr>
            <td class="label"><?php echo __('dnis',true);?> :</td>
            <td class="value"><input type="text" id="query-dst_number" value=""
                                     name="query[dst_number]" class="input in-text">
                <?php echo $this->element('search_report/ss_clear_input_select');?></td>
            <td class="label"><?php echo __('ani',true);?>:</td>
            <td class="value"><input type="text" id="query-src_number" value=""
                                     name="query[src_number]" class="input in-text">
                <?php echo $this->element('search_report/ss_clear_input_select');?></td>
        </tr>
        <tr>
            <td class="label"><span rel="helptip" class="helptip" id="ht-100002"><?php echo __('Interval second',true);?></span> <span class="tooltip" id="ht-100002-tooltip">Duration
                    interval in seconds</span>:</td>
            <td class="value"><input type="text" id="query-interval_from"
                                     class="in-digits input in-text" style="width: 53px;" value=""
                                     name="query[interval_from]">
                &mdash;
                <input type="text"
                       id="query-interval_to" class="in-digits input in-text"
                       style="width: 54px;" value="" name="query[interval_to]">
                <?php echo $this->element('search_report/ss_clear_input_select');?></td>
            <?php  if ($_SESSION['role_menu']['Statistics']['cdrreports']['model_x']) {?>
            <td class="label"></td>
            <td class="value"></td>
            <?php }else{?>
            <td></td>
            <td></td>
            <?php }?>
        </tr>
        <?php if($rate_type=='spam'){

        ?>
        <tr>
            <td class="label"><input type="checkbox"
                                     <?php  if(isset($_GET['invalid_ingress_ip'])){  echo "checked='checked'";}?>
                                     class="input in-checkbox" name="invalid_ingress_ip" value="false"
                                     id="invalid_ingress_ip"
                                     onclick="$(this).attr('checked')==true?$(this).attr('value','true'):$(this).attr('value','false');"></td>
            <td class="value"><label for="query-output_subgroups"><span
                        id="ht-100146"><?php echo __('Invalid Ingress IP',true);?></span></label></td>
            <td class="label"><input type="checkbox"
                                     <?php  if(isset($_GET['no_product_found'])){  echo "checked='checked'";}?>
                                     onclick="$(this).attr('checked')==true?$(this).attr('value','true'):$(this).attr('value','false');"
                                     class="input in-checkbox" name="no_product_found" value="false"
                                     id="no_product_found"></td>
            <td class="value"><label for="query-output_subtotals"><span
                        id="ht-100147"><?php echo __('No Product Found',true);?></span></label></td>
            <td class="label"><input type="checkbox"
                                     <?php  if(isset($_GET['no_code_found'])){  echo "checked='checked'";}?>
                                     onclick="$(this).attr('checked')==true?$(this).attr('value','true'):$(this).attr('value','false');"
                                     class="input in-checkbox" name="no_code_found" value="false"
                                     id="no_code_found"></td>
            <td class="value"><label for="query-output_subtotals"><span
                        id="ht-100147"><?php echo __('No Code Found',true);?></span></label></td>
        </tr>
        <?php }?>
        </tbody>
    </table>
</fieldset>
<?php echo $form->end();?>

<div id="pop-div" class="pop-div" style="width: 320px; height: 80px; position: absolute; left: 50%; top: 50%; z-index: 9999; margin-top: 0px;display:none;">
    <label style="color:red;">You did not set your email address!</label>
    <input type="text" id="send_email" />
    <input type="button" value="submit" id="send_email_btn" />
</div>

<script>
    $(function() {
        $('#formquery').click(function() {
            if($('#query-output').val() == 'email') {
                $.ajax({
                    'url'      : '<?php echo $this->webroot ?>cdrreports/check_email',
                    'type'     : 'GET',
                    'dataType' : 'text',
                    'async'    : false,
                    'success'  : function(data) {
                        if(data == '0') {
                            $('#pop-div').show();
                            $('#send_email_btn').click(function() {
                                var email = $('#send_email').val();
                                $.ajax({
                                    'url'      : '<?php echo $this->webroot ?>cdrreports/update_email',
                                    'type'     : 'POST',
                                    'dataType' : 'text',
                                    'data'     : {'email':email},
                                    'success'  : function(data) {
                                        $('#report_form').submit();
                                    }
                                });
                            });
                        } else {
                            $('#report_form').submit();
                        }
                        return false;
                    }
                });
                
                return false;
            }
        });
    });    
</script>
