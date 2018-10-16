<style type="text/css">
    .form tr{ height:30px; line-height:30px;}
    .form .label2 {
        font-size: 12px;
        width: 40%;
    }
</style>
<div id="title">
    <h1><?php __('Routing')?>&gt;&gt;<?php __('addegress')?></h1>
    <ul id="title-menu" />

    <li>
        <a href="<?php echo $this->webroot?>prresource/gatewaygroups/view_egress?<?php echo $$hel->getParams('getUrl')?>" class="link_back"><img width="16" height="16" alt="<?php echo __('goback',true);?>" src="<?php echo $this->webroot?>images/icon_back_white.png">&nbsp;<?php echo __('goback',true);?></a>		</li>
</ul>
</div>

<div class="container">
    <!--
                    <ul class="tabs">
      
         <li  class="active"><a><img width="16" height="16" src="<?php echo $this->webroot ?>images/list.png"/>System Information</a></li>
       </ul>
    -->
    <?php echo $form->create ('Gatewaygroup', array ('action' => 'add_resouce_egress' ));?> <?php echo $form->input('ingress',array('label'=>false ,'value'=>'false','div'=>false,'type'=>'hidden'));?> <?php echo $form->input('egress',array('label'=>false ,'value'=>'true','div'=>false,'type'=>'hidden'));?>
    <table class="cols" style="width:80%">
        <col width="55%"/><col width="38%"/><col width="27%"/>
        <tr>
            <td><!-- COLUMN 1 -->
                <?php //**********系统信息**************?>
                <fieldset style="width:350px;"><!--<legend><?php __('Egress Trunk')?></legend>-->
                    <table class="form">
                        <tr>
                            <td><?php echo __('Egress Name',true);?>:</td>
                            <td>
                                <?php echo $form->input('alias',array('id'=>'alias','label'=>false ,'div'=>false,'type'=>'text','maxlength'=>'100'));?>
                            </td>
                        </tr>

                        <?php if(isset($_GET['viewtype'])&& ($_GET['viewtype']=='client' || $_GET['viewtype']=='wizard') ){?>
                        <?php echo $form->input('client_id',array('label'=>false ,'value'=>array_keys_value($_GET,'query.id_clients'),'div'=>false,'type'=>'hidden'));?>
                        <?php }else{?>
                        <tr>
                            <td><?php echo __('client')?>:</td>
                            <td><?php echo $form->input('client_id',array('options'=>$c,'empty'=>'','label'=>false, 'class' =>'select' ,'div'=>false,'type'=>'select','selected'=>array_keys_value($_GET,'query.id_clients')));?></td>
                        </tr>
                        <?php }?>
                        <tr>
                            <td><?php echo __('Media Type',true);?>:</td>
                            <td>
                                <?php 
                                if(Configure::read('project_name')=='partition'){
                                $t=array('2'=>'Bypass Media','1'=>'Proxy Media');
                                }else{
                                $t=array('0'=>'Proxy Media + Transcoding','1'=>'Proxy Media ','2'=>'Bypass Media');
                                }
                                echo $form->input('media_type',array('options'=>$t,'label'=>false, 'class' =>'select' ,'div'=>false,'type'=>'select'));
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="align_right padding-r10"><?php echo __('lowprofit') ?></td>
                            <td>
                                <?php echo $form->input('profit_margin', array('label' => false, 'value' => '0', 'div' => false, 'type' => 'text', 'class' => 'in-decimal input in-text validate[custom[number]]', 'maxlength' => '6', 'style' => 'width:50px')) ?>
                                <?php echo $xform->input('profit_type', array('options' => Array(1 => 'Percentage', 2 => 'Value'), 'style' => 'width:102px')) ?>
                            </td>
                        </tr>
                        <tr>
                            <td><?php __('calllimit')?>:</td>
                            <td>
                                <?php echo $form->input('capacity',array('id'=>'totalCall','label'=>false ,'div'=>false,'type'=>'text','maxlength'=>'8', 'value' => $default_timeout['call_timeout']));?>
                            </td>
                        </tr>
                        <tr>
                            <td><?php __('cps')?>:</td>
                            <td>
                                <?php echo $form->input('cps_limit',array('id'=>'totalCPS','label'=>false ,'div'=>false,'type'=>'text','maxlength'=>'8'));?>
                            </td>
                        </tr>


                        <tr>
                            <td><?php __('proto')?>:</td>
                            <td>
                                <?php echo $form->input('proto',array('label'=>false ,'div'=>false,'type'=>'select','options'=>Array(Resource::RESOURCE_PROTO_ALL=>'ALL',Resource::RESOURCE_PROTO_SIP=>'SIP',Resource::RESOURCE_PROTO_PROTO=>'H323'),'selected'=>Resource::RESOURCE_PROTO_SIP));?>
                            </td>
                        </tr>
                        <tr style="display:none;">
                            <td><?php __('Ignore Early media')?>:</td>
                            <td>
                                <?php 
                                $ignore_arr = array(0=>'NONE', 1=>'180 and 183', 2=>'180 only', 3=>'183 only');
                                echo $form->input('ignore_ring_early_media',array('options'=>$ignore_arr,'label'=>false, 'class' =>'select' ,'div'=>false,'type'=>'select'));?>
                            </td>
                        </tr>
                        <tr>
                            <td><?php __('active')?>:</td>
                            <td>
                                <?php 
                                $au='true';
                                echo $form->input('active',array('options'=>array('true'=>'True', 'false'=>'False'),'label'=>false, 'class' =>'select' ,'div'=>false,'type'=>'select', 'selected'=>$au));
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td><?php __('T38')?>:</td>
                            <td>
                                <?php 
                                $t38='false';
                                echo $form->input('t38', array('options'=>array('true'=>'Enable', 'false'=>'Disable'),'label'=>false, 'class' =>'select' ,'div'=>false,'type'=>'select', 'selected'=>$t38));
                                ?></td>
                        </tr>


                        <td><?php __('pddtimeout')?>:</td>
                        <td>
                            <?php echo $form->input('wait_ringtime180',array('id'=>'wait_ringtime180','label'=>false ,'div'=>false,'type'=>'text','maxlength'=>'5'));?>ms
                        </td>
                        </tr>
<!--
                        <tr>
                            <td><?php __('rateTable')?>:</td>
                            <td>
                                <?php echo $form->input('rate_table_id',array('options'=>$rate,
                                'empty'=>'  ','label'=>false, 'class' =>'select' ,'div'=>false,'type'=>'select'));?>
                                <img id="addratetable" style="cursor:pointer;" src="<?php echo $this->webroot?>images/add.png"  onclick="showDiv('pop-div','500','200','<?php echo $this->webroot?>clients/addratetable');" />
                            </td>
                        </tr>
-->
                        <tr style="line-height:1;">
                            <td><?php __('HostStrategy')?></td>
                            <td>	
                                <?php
                                $t=array('1'=>'top-down','2'=>'round-robin');
                                echo $form->input('res_strategy',array('options'=>$t,'label'=>false, 'class' =>'select' ,'div'=>false,'type'=>'select'));
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td><?php __('RFC 2833'); ?></td>
                            <td>
                                <?php 
                                $rfc2833='true';
                                echo $form->input('rfc_2833',array('options'=>array('true'=>'True', 'false'=>'False'),'label'=>false, 'class' =>'select' ,'div'=>false,'type'=>'select', 'selected'=>$rfc2833));
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td><?php __('Pass Dipping Info'); ?></td>
                            <td>
                                <?php 
                                $lnp_dipping='false';
                                echo $form->input('lnp_dipping',array('options'=>array('true'=>'Add Header', 'false'=>'Not Add Header'),'label'=>false, 'class' =>'select' ,'div'=>false,'type'=>'select', 'selected'=>$lnp_dipping));
                                ?>
                            </td>
                        </tr>

                        <tr>
                            <td><?php __('Min Duration'); ?></td>
                            <td>
                                <?php echo $form->input('delay_bye_second',array('id'=>'delay_bye_second','label'=>false ,'div'=>false,'type'=>'text'));?>&nbsp;s
                            </td>
                        </tr>
                        <!--
                        <tr>
                            <td><?php __('Delay Bye Limit'); ?></td>
                            <td>
                        <?php echo $form->input('delay_bye_limit',array('id'=>'delay_bye_limit','label'=>false ,'div'=>false,'type'=>'text'));?>s        
                            </td>
                        </tr>
                        -->
                        <tr>
                            <td><?php __('Max Duration'); ?></td>
                            <td>
                                <?php echo $form->input('max_duration',array('id'=>'max_duration','label'=>false ,'div'=>false,'type'=>'text', 'value' => $default_timeout['call_timeout']));?>&nbsp;s  
                            </td>
                        </tr>
			<?php if($is_did_enable): ?>
                        <tr>
                            <td><?php __('Type'); ?></td>
                            <td>
                                <?php 
                                echo $form->input('trunk_type2',array('options'=>array(0=>'Termination', 1=>'Origination'),'label'=>false, 'class' =>'select' ,'div'=>false,'type'=>'select', 'selected'=>0));
                                ?>
                            </td>
                        </tr>
                        <tr id="did_billing_rule_tr">
                            <td><?php __('Orig. Billing Rule'); ?></td>
                        	<td>
                                <?php echo $form->input('billing_rule',array('options'=>$routing_rules,
                                'empty'=>'  ','label'=>false, 'class' =>'select' ,'div'=>false,'type'=>'select'));?>
                                </td>
                        </tr>
                        <tr id="did_billing_method_tr">
                            <td><?php __('Billing Method'); ?></td>
                            <td>
                                <?php
                                echo $form->input('billing_method',array('options'=>array(0=>'By Minute', 1=>'By Port'),'label'=>false, 'class' =>'select' ,'div'=>false,'type'=>'select', 'selected'=>0));
                                ?>
                            </td>
                        </tr>
                        <?php endif; ?>
                        <tr id="did_rate_table_tr">
                        	<td><?php __('Rate Table'); ?></td>
                        	<td>
                                <?php echo $form->input('rate_table_id',array('options'=>$rate,
                                'empty'=>'  ','label'=>false, 'class' =>'select' ,'div'=>false,'type'=>'select'));?>
                                <img id="addratetable" style="cursor:pointer;" src="<?php echo $this->webroot?>images/add.png"  onclick="showDiv('pop-div','500','200','<?php echo $this->webroot?>clients/addratetable');" />
                                </td>
                        </tr>
                        <?php if($is_did_enable): ?>
                        <tr id="did_amount_per_port_tr">
                            <td><?php __('Per Port Amount'); ?></td>
                            <td>
                                 <?php echo $form->input('amount_per_port',array('id'=>'amount_per_port','label'=>false ,'div'=>false,'type'=>'text'));?>
                            </td>
                        </tr>
                        <?php endif; ?>
                        <tr>
                            <td><?php __('Ring Timer'); ?></td>
                            <td>
                                <?php echo $form->input('ring_timeout',array('id'=>'ring_timeout','label'=>false ,'div'=>false,'type'=>'text','maxlength'=>'5', 'value' => $default_timeout['ring_timeout']));?>s      
                            </td>
                        </tr>
<!--                        <tr>-->
<!--                            <td>--><?php //__('Rate Profile'); ?><!--</td>-->
<!--                            <td>-->
<!--                                --><?php
//                                echo $form->input('rate_profile',array('options'=>array('False', 'True'),'label'=>false, 'class' =>'select' ,'div'=>false,'type'=>'select', 'selected'=>0));
//                                ?>
<!--                            </td>-->
<!--                        </tr>-->
<!--                        <tr class="rate_profile_control">-->
<!--                            <td>--><?php //__('USA'); ?><!--</td>-->
<!--                            <td>-->
<!--                                --><?php //
//                                echo $form->input('us_route',array('options'=>array('Other', 'Intra', 'Inter', 'Highest'),'label'=>false, 'class' =>'select' ,'div'=>false,'type'=>'select', 'selected'=>0));
//                                ?><!--  -->
<!--                            </td>-->
<!--                        </tr>-->
<!--                        <tr class="rate_profile_control">-->
<!--                            <td>--><?php //__('US Territories'); ?><!--</td>-->
<!--                            <td>-->
<!--                                --><?php //
//                                echo $form->input('us_other',array('options'=>array('Other', 'Intra', 'Inter', 'Highest'),'label'=>false, 'class' =>'select' ,'div'=>false,'type'=>'select', 'selected'=>0));
//                                ?><!--  -->
<!--                            </td>-->
<!--                        </tr>-->
<!--                        <tr class="rate_profile_control">-->
<!--                            <td>--><?php //__('Canada'); ?><!--</td>-->
<!--                            <td>-->
<!--                                --><?php //
//                                echo $form->input('canada_route',array('options'=>array('Other', 'Intra', 'Inter', 'Highest'),'label'=>false, 'class' =>'select' ,'div'=>false,'type'=>'select', 'selected'=>0));
//                                ?><!--  -->
<!--                            </td>-->
<!--                        </tr>-->
<!--                        <tr class="rate_profile_control">-->
<!--                            <td>--><?php //__('Non USA/Canada Territories'); ?><!--</td>-->
<!--                            <td>-->
<!--                                --><?php //
//                                echo $form->input('canada_other',array('options'=>array('Other', 'Intra', 'Inter', 'Highest'),'label'=>false, 'class' =>'select' ,'div'=>false,'type'=>'select', 'selected'=>0));
//                                ?><!--  -->
<!--                            </td>-->
<!--                        </tr>-->
<!--                        <tr class="rate_profile_control">-->
<!--                            <td>--><?php //__('International'); ?><!--</td>-->
<!--                            <td>-->
<!--                                --><?php //
//                                echo $form->input('intl_route',array('options'=>array('Other', 'Intra', 'Inter', 'Highest'),'label'=>false, 'class' =>'select' ,'div'=>false,'type'=>'select', 'selected'=>0));
//                                ?><!--  -->
<!--                            </td>-->
<!--                        </tr>-->
                        <tr>
                            <td><?php __('Rounding Decimal Places'); ?></td>
                            <td>
                                <?php echo $form->input('rate_decimal',array('label'=>false ,'div'=>false,'type'=>'text','maxlength'=>'5', 'value' => 6));?>     
                            </td>
                        </tr>
                        <tr>
                            <td><?php __('Rounding'); ?></td>
                            <td>
                                <?php echo $form->input('rate_rounding',array('options'=>array('Up', 'Down'), 'label'=>false ,'div'=>false, 'value' => 6));?>     
                            </td>
                        </tr>
                    </table>
                </fieldset>
                <!--
                <div id="support_panel">
                    <label title="Remote-Party-ID">RPID</label>
                    <?php echo $form->input('rpid',array('label'=>false ,'div'=>false,'type'=>'checkbox'));?>
                    <label title="P-Asserted-Identity">PAID</label>
                    <?php echo $form->input('paid',array('label'=>false ,'div'=>false,'type'=>'checkbox'));?>
                    <label title="isup-oli">OLI</label>
                    <?php echo $form->input('oli',array('label'=>false ,'div'=>false,'type'=>'checkbox'));?>
                    <label title="P-Charge-Info">PCI</label>
                    <?php echo $form->input('pci',array('label'=>false ,'div'=>false,'type'=>'checkbox'));?>
                    <label title="Privacy">PRIV</label>
                    <?php echo $form->input('priv',array('label'=>false ,'div'=>false,'type'=>'checkbox'));?>
                    <label title="Diversion">DIV</label>
                    <?php echo $form->input('div',array('label'=>false ,'div'=>false,'type'=>'checkbox'));?>
                </div>
				-->
                <?php //***************************************费率设置************************************************************?>

                <table class="form">
                    <col style="width:37%;"/>
                    <col style="width:62%;"/>
                    <tr>
                        <td colspan="2" class="value">
                            <div class="cb_select" style="height:30px; line-height:30px;text-align: left; border:none;">
                                <div>

                                    <!--            
                                                  <?php echo $form->checkbox('lnp',array('style'=>'margin-left: 40px;'))?>
                                                <label for="cp_modules-c_invoices">LRN</label>
                                           <?php echo $form->checkbox('lrn_block',array('style'=>'margin-left: 40px;'))?>
                                                 <label for="cp_modules-c_stats_summary">Block LRN</label></div>-->
                                </div>
                        </td>
                    </tr>
                </table>

            </td><td><!-- COLUMN 2 -->
                <script type="text/javascript" src="<?php echo $this->webroot?>js/jquery.livequery.js"></script>
                <script type="text/javascript">

                    jQuery(function($){
                        $('#addratetable').live('click', function() {
                            $(this).prev().addClass('clicked');
                            // window.open('<?php echo $this->webroot?>clients/addratetable', 'addratetable','height=800,width=1000,top=0,left=0,toolbar=no,menubar=no,scrollbars=no, resizable=no,location=no, status=no');
                        });
                    });

                    function test2(id) {
                        $('#GatewaygroupRateTableId').livequery(function() {
                            var $ratetable = $(this);
                            $.getJSON('<?php echo $this->webroot ?>clients/getratetable', function(data) {
                                $.each(data, function(idx, item) {
                                    var option = $("<option value='" + item['id'] + "'>" + item['name'] + "</option>");
                                    if($ratetable.hasClass('clicked')) {
                                        if(item['id'] == id) {
                                            option.attr('selected','selected');
                                        }
                                    }
                                    $ratetable.append(option);
                                });
                                $ratetable.removeClass('clicked');
                            })
                        });  
                    }

                function test3(id) {
                    var $ratetable = $("#GatewaygroupRateTableId");
                    $.getJSON('<?php echo $this->webroot ?>clients/getratetable', function(data) {
                        $.each(data, function(idx, item) {
                            var option = $("<option value='" + item['id'] + "'>" + item['name'] + "</option>");
                            if(item['id'] == id) {
                                option.attr('selected','selected');
                            }
                            $ratetable.append(option);
                        });
                    }) 
                }
                </script>

                <fieldset><legend> <?php __('codec')?></legend>
                    <table class="form">
                        <tr>
                            <td>
                                <?php echo $form->input('select1',array('id'=>'select1','options'=>$d,'multiple' => true,'style'=>'width: 200px; height: 250px;','label'=>false, 'class' =>'select' ,'div'=>false,'type'=>'select'));?>
                            </td>
                            <td>
                                <input  style="width: 48px; height: 25px; margin-left: 0px;"    onclick="DoAdd();"  type="button"  value="<?php __('add')?>"  class="input in-submit"/>
                                <br/><br/>
                                <input  type="button" style="width: 48px; height: 25px; margin-left: 0px;" onclick="DoDel();" value="<?php __('delete')?>" class="input in-submit" />
                            </td>
                            <td>
                                <?php echo $form->input('select2',array('id'=>'select2','options'=>'','multiple' => true,'style'=>'width: 200px; height: 250px;','label'=>false, 'class' =>'select' ,'div'=>false,'type'=>'select'));?>
                            </td>
                            <td>
                                <input  style="width: 48px; height: 25px; margin-left: 0px;"    onclick="moveOption('select2','up');"  type="button"  value="<?php __('up')?>" class="input in-submit"  />
                                <br/><br/>
                                <input  type="button"   style="width: 48px; height: 25px; margin-left: 0px;"  onclick="moveOption('select2','down');"   value="<?php __('Down')?>"  class="input in-submit"/>
                            </td>
                        </tr>
                    </table>
                </fieldset>
                <!--
                <fieldset>
                    <legend><?php __('SIP Profile') ?></legend>
                    <table class="list">
                        <thead>
                            <tr>
                                <th>VoIP Gateway Name</th>
                                <th>SIP Profile Name</th>
                            </tr>
                        </thead>
                        
                        <tbody>
                            <?php
                                foreach($switch_profiles as $switch_profile):
                                $profiles = $switch_profile['profiles'];
                            ?>
                            <tr>
                                <input type="hidden" name="$server_names[]" value="<?php echo $switch_profile['name'] ?>" />
                                <td>
                                    <?php echo $switch_profile['name'] ?>
                                </td>
                                <td>
                                    <select name="profiles[]">
                                        <option></option>
                                        <?php foreach($profiles as $profile): ?>
                                        <option value="<?php echo $profile[0] ?>"><?php echo $profile[1] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                            </tr>
                            <?php
                                endforeach;
                            ?>
                        </tbody>
                    </table>
                </fieldset>
				-->
            </td>
        </tr>
    </table>
    <?php echo $this->element("gatewaygroups/host")?>
    <?php //echo $this->element("gatewaygroups/add_to_route")?>  
    <?php if($$hel->_get('viewtype')=='wizard'){?>
    <div id="form_footer">
        <input type="submit"    onclick="seleted_codes();jQuery('#GatewaygroupAddResouceEgressForm').attr('action','?nextType=egress&<?php echo $$hel->getParams('getUrl')?>')" value="<?php echo __('Next Egress')?>" style="width:80px" />
        <input type="submit"    onclick="seleted_codes();jQuery('#GatewaygroupAddResouceEgressForm').attr('action','?nextType=ingress&<?php echo $$hel->getParams('getUrl')?>')" value="<?php echo __('Next Ingress')?>" style="width:80px"/>
        <input type="button"  value="<?php echo __('End')?>" class="input in-submit" onclick="location='<?php echo $this->webroot?>clients/index?filter_id=<?php echo $$hel->_get('query.id_clients')?>'"/>
    </div>
    <?php }else{?>
    <?php  if ($_SESSION['role_menu']['Routing']['gatewaygroups:view']['model_w']) {?>
    <div id="form_footer">
        <input type="submit" id="submit_form" value="<?php echo __('submit')?>" class="input in-submit"/>
        <input type="reset"  value="<?php echo __('Reset')?>"  class="input in-submit"/>
    </div>
    <?php }?>
    <?php }?>
    <?php echo $form->end();?>


    <!-----------Add Rate Table----------->
    <div id="pop-div" class="pop-div" style="display:none;">
        <div class="pop-thead">
            <span></span>
            <span class="float_right"><a href="javascript:closeDiv('pop-div')" id="pop-close" class="pop-close">&nbsp;</a></span>
        </div>
        <div class="pop-content" id="pop-content"></div>
    </div>
    <div id="pop-clarity" class="pop-clarity" style="display:none;"></div>
</div>     



</div>

<script type="text/javascript" src="<?php echo $this->webroot?>js/gateway.js"></script>
<script type="text/javascript">
jQuery(document).ready(
function(){
    jQuery('#totalCall,#totalCPS').xkeyvalidate({type:'Num'});
    jQuery('#alias').xkeyvalidate({type:'strNum'});
    jQuery('#submit_form').click(function(){
        var pa="/[^0-9A-Za-z-\_\s]+/";
        var re =true;
        if(jQuery('#alias').val()==''){
            jQuery(this).addClass('invalid');
            jQuery(this).jGrowlError('Egress Name, is required!');
            re=false;
                                       
        }else if(/[^0-9A-Za-z-\_\s]/.test(jQuery("#alias").val())){
            jQuery(this).addClass('invalid');
            jQuery(this).jGrowlError('Egress Name,allowed characters: a-z,A-Z,0-9,-,_,space, maximum of 16 characters in length!');
            re = false;
				  
        }

        if(jQuery('#totalCall').val()!=''){
            if(/\D/.test(jQuery('#totalCall'.val()))){
                jQuery(this).addClass('invalid');
                jQuery(this).jGrowlError('Call limit, must be whole number! ');
                re= false;
            }	  
        }
        if(parseInt(jQuery('#wait_ringtime180').val()) < 1000 || parseInt(jQuery('#wait_ringtime180').val()) > 60000) {
            jQuery(this).addClass('invalid');
            jQuery(this).jGrowlError('PDD Timeout must a number less than 60000 and greater than 1000!');
            re =false;
        }
        if(jQuery('#totalCPS').val()!=''){
            if(/\D/.test(jQuery('#totalCPS').val())){
                jQuery(this).addClass('invalid');
                jQuery(this).jGrowlError('CPS Limit, must be whole number!');
                re =false;
            }	   
		 	   
        }
        
        /*
        
        if(jQuery('#ip:visible').val()!=''||!jQuery('#ip:visible').val()){

            if(!/^([\w-]+\.)+((com)|(net)|(org)|(gov\.cn)|(info)|(cc)|(com\.cn)|(net\.cn)|(org\.cn)|(name)|(biz)|(tv)|(cn)|(mobi)|(name)|(sh)|(ac)|(io)|(tw)|(com\.tw)|(hk)|(com\.hk)|(ws)|(travel)|(us)|(tm)|(la)|(me\.uk)|(org\.uk)|(ltd\.uk)|(plc\.uk)|(in)|(eu)|(it)|(jp))$/.test(jQuery('#ip:visible').val()))
            {

            } 
            if(!/^(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])(\.(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])){3}$/.test(jQuery('#ip:visible').val())||

                !/[a-zA-Z0-9][-a-zA-Z0-9]{0,62}(\.[a-zA-Z0-9][-a-zA-Z0-9]{0,62})+\.?/.test(jQuery('#ip:visible').val())

        ){
				  
                jQuery(this).addClass('invalid');
                jQuery(this).jGrowlError('IPs/FQDN must be a valid format ');
                re = false;
				  
            } 
            if(jQuery('#port:visible').val()!=''||!jQuery('#port:visible').val()){
                if(/\D/.test(jQuery('#port:visible').val())){
                    jQuery(this).addClass('invalid');
                    //	jQuery(this).jGrowlError('Port,must be whole number!');
                    //		re = false;					
                }	 
				 
				 
            } 
			  
        }  
        */
        if(jQuery('#ring_timeout').val() == '' || jQuery('#ring_timeout').val() <1 || jQuery('#ring_timeout').val() > 60) {
            jQuery('#ring_timeout').addClass('invalid');
            jQuery.jGrowl('Ring Timer cant not be greater than 60 or less than 1!',{theme:'jmsg-error'});
            re =  false;
        }


        return re;

    });
					
}
				
);

$(function() {
    
    var $rate_profile_control = $('.rate_profile_control');
    
    $('#GatewaygroupRateProfile').change(function() {
        var $this = $(this);
        var val = $this.val();
        if (val == 0)
        {
            $rate_profile_control.hide();
        }
        else
        {
            $rate_profile_control.show();
        }
    }).trigger('change');
    
    $('#GatewaygroupEditResouceEgressForm').submit(function() {
        
        var flag = true;
        
        if(jQuery('#wait_ringtime180').val() != '') {
            if(! /\d+|\./.test(jQuery('#wait_ringtime180').val())){
                jQuery('#wait_ringtime180').addClass('invalid');
                jQuery.jGrowl('PDD Timeout must contain numeric characters only!',{theme:'jmsg-error'});
                flag = false;
            }
        }
        if(jQuery('#delay_bye_second').val()!='') {
            if(! /\d+|\./.test(jQuery('#delay_bye_second').val())){
                jQuery('#delay_bye_second').addClass('invalid');
                jQuery.jGrowl('Min Duration must contain numeric characters only!',{theme:'jmsg-error'});
                flag =false;
            }
        }
        if(jQuery('#max_duration').val() != '') {
            if(! /\d+|\./.test(jQuery('#max_duration').val())){
                jQuery('#max_duration').addClass('invalid');
                jQuery.jGrowl('Max Duration must contain numeric characters only!',{theme:'jmsg-error'});
                flag =  false;
            }
        }
        
        return flag;
    });
    
    var did_billing_method_tr =  $('#did_billing_method_tr');
    var did_billing_rule_tr = $('#did_billing_rule_tr');
    var did_rate_table_tr = $('#did_rate_table_tr');
    var did_amount_per_port_tr = $('#did_amount_per_port_tr');
    
    jQuery('#GatewaygroupTrunkType2').change(function() {
            if($(this).val() == '0')
            {
                    did_billing_method_tr.hide();
                    did_billing_rule_tr.hide();
                    did_rate_table_tr.show();
                    $('#did_amount_per_port_tr').hide();
            }
            else
            {
                    did_billing_method_tr.show();
                    did_billing_rule_tr.show();
                    jQuery('#GatewaygroupBillingMethod').change();
            }
    }).trigger('change');

    jQuery('#GatewaygroupBillingMethod').change(function() {
            if($(this).val() == '0')
            {
                    did_rate_table_tr.show();
                    did_amount_per_port_tr.hide();
            }
            else
            {
                    did_rate_table_tr.hide();
                    did_amount_per_port_tr.show();
            }
    });
    
});
</script>
