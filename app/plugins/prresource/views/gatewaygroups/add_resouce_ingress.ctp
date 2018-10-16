<style type="text/css">
    #ignore {
        float:left;
    }
    #ignore li {
        padding:3px;
        padding-left:40px;
        float:left;
    }
    .form .label2{ width:40%;}
    .form tr{ height:30px; line-height:30px;}
</style>
<div id="title">
    <h1>
        <?php __('Routing')?>
        &gt;&gt;
        <?php __('addingress')?>
    </h1>
    <ul id="title-menu" />

    <li> <a  class="link_back" href="<?php echo $this->webroot?>prresource/gatewaygroups/view_ingress?<?php echo $this->params['getUrl']?>" class="link_back"><img width="16" height="16" alt="<?php echo __('goback',true);?>" src="<?php echo $this->webroot?>images/icon_back_white.png">&nbsp;<?php echo __('goback',true);?></a> </li>
</ul>
</div>
<div class="container">
    <!--
      <ul class="tabs">
        <li  class="active"> <a> <img width="16" height="16" src="<?php echo $this->webroot?>images/list.png"/>
          <?php __('Route Strategy')?>
          </a> </li>
      </ul>
    -->
    <?php echo $form->create ('Gatewaygroup', array ('action' => 'add_resouce_ingress' ));?> <?php echo $form->input('ingress',array('label'=>false ,'value'=>'true','div'=>false,'type'=>'hidden'));?> <?php echo $form->input('egress',array('label'=>false ,'value'=>'false','div'=>false,'type'=>'hidden'));?>
    <table class="cols">
        <col width="35%"/>
        <col width="38%"/>
        <col width="27%"/>
        <tr>
            <td><!-- COLUMN 1 -->

                <?php //**********系统信息**************?>
                <fieldset style="width:350px;border-top: none;">
                    <table class="form">
                        <tr>
                            <td><?php echo __('Ingress Name',true);?>:</td>
                            <td><?php echo $form->input('alias',array('id'=>'alias','label'=>false ,'div'=>false,'type'=>'text','maxlength'=>'100'));?></td>
                        </tr>
                        <?php if(isset($_GET['viewtype'])&& ($_GET['viewtype']=='client' || $_GET['viewtype']=='wizard') ){?>
                        <tr style="display:none">
                            <td></td>
                            <td><?php 
                                echo $form->input('client_id',array('options'=>$c,'selected'=>array_keys_value($_GET,'query.id_clients'),'label'=>false, 'class' =>'select' ,'div'=>false,'type'=>'select' ));
                                ?></td>
                        </tr>
                        <?php  }else{ ?>
                        <tr>
                            <td><?php echo __('client')?>: </td>
                            <td><?php echo $form->input('client_id',array('options'=>$c,'empty'=>'','label'=>false, 'class' =>'select' ,'div'=>false,'type'=>'select'));?></td>
                        </tr>
                        <?php }?>
                        <?php if($is_enable_type): ?>
                        <tr>
                            <td><?php __('Type'); ?></td>
                            <td>
                                <?php 
                                echo $form->input('trunk_type',array('options'=>array(1=>'Class4', 2=>'Exchange'),'label'=>false, 'class' =>'select' ,'div'=>false,'type'=>'select', 'selected'=>1));
                                ?>
                            </td>
                        </tr>
                        <tr id="transaction_fee_panel">
                            <td><?php __('Transaction Fee'); ?></td>
                            <td>
                                    <?php 
                                    echo $form->input('transaction_fee_id',array('options'=>$transation_fees,'label'=>false, 'class' =>'select' ,'div'=>false,'type'=>'select'));
                                    ?>
                            </td>
                        </tr>
                        <?php endif; ?>
                        <tr>
                            <td><?php echo __('Media Type',true);?>:</td>
                            <td><?php 
                                if(Configure::read('project_name')=='partition'){
                                $t=array('2'=>'Bypass Media','1'=>'Proxy Media');
                                }else{
                                $t=array('0'=>'Proxy Media + Transcoding','1'=>'Proxy Media ','2'=>'Bypass Media');
                                }
                                echo $form->input('media_type',array('options'=>$t,'label'=>false, 'class' =>'select' ,'div'=>false,'type'=>'select'));
                                ?></td>
                        </tr>
                        <tr>
                            <td class="align_right padding-r10"><?php echo __('lowprofit') ?></td>
                            <td>
                                <?php echo $form->input('profit_margin', array('label' => false, 'value' => '0', 'div' => false, 'type' => 'text', 'class' => 'in-decimal input in-text validate[custom[number]]', 'maxlength' => '6', 'style' => 'width:50px')) ?>
                                <?php echo $xform->input('profit_type', array('options' => Array(1 => 'Percentage', 2 => 'Value'), 'style' => 'width:102px')) ?>
                            </td>
                        </tr>
                        <tr>
                            <td><?php echo __('lowprofit')?>:</td>
                            <td>
                                <?php echo $form->input('profit_margin',array('label'=>false,'value'=>'0','div'=>false,'type'=>'text','class'=>'in-decimal input in-text','maxlength'=>'6','style'=>'width:33%'))?>
                                <?php echo $xform->input('profit_type',array('options'=>Array(1=>'Percentage',2=>'Value'),'style'=>'width:45%'))?>
                            </td>
                        </tr>
                        <tr>
                            <td><?php __('calllimit')?>:</td>
                            <td><?php echo $form->input('capacity',array('id'=>'totalCall','label'=>false ,'div'=>false,'type'=>'text','maxlength'=>'8'));?></td>
                        </tr>
                        <tr>
                            <td><?php __('cps')?>:</td>
                            <td><?php echo $form->input('cps_limit',array('id'=>'totalCPS','label'=>false ,'div'=>false,'type'=>'text','maxlength'=>'8'));?></td>
                        </tr>
                        <tr>
                            <td><?php __('proto')?>:</td>
                            <td><?php echo $form->input('proto',array('label'=>false ,'div'=>false,'type'=>'select','options'=>Array(Resource::RESOURCE_PROTO_ALL=>'All',Resource::RESOURCE_PROTO_SIP=>'SIP',Resource::RESOURCE_PROTO_PROTO=>'H323'),'selected'=>Resource::RESOURCE_PROTO_SIP));?></td>
                        </tr>

                        <tr>
                            <td><?php __('pddtimeout')?>:</td>
                            <td>
                                <?php echo $form->input('wait_ringtime180',array('id'=>'wait_ringtime180','label'=>false ,'div'=>false,'type'=>'text','maxlength'=>'5', 'value' => $default_timeout['ingress_pdd_timeout']));?>
                            </td>
                        </tr>
                        <tr>
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
                                ?></td>
                        </tr>
                        <tr>
                            <td><?php __('T38')?>:</td>
                            <td>
                                <?php 
                                $t38='false';
                                echo $form->input('t38', array('options'=>array('true'=>'Enable', 'false'=>'Disable'),'label'=>false, 'class' =>'select' ,'div'=>false,'type'=>'select', 'selected'=>$t38));
                                ?></td>
                        </tr>
                        <tr>
                            <td><?php __('Dipping Rate')?></td>
                            <td>
                                <?php echo $form->input('lnp_dipping_rate',array('id'=>'lnp_dipping_rate','label'=>false ,'div'=>false,'type'=>'text','value'=>'0','maxlength'=>'10'));?>
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
                            <td><?php __('User Dipping From'); ?></td>
                            <td>
                                <?php 
                                $lnp_dipping='false';
                                echo $form->input('lnp_dipping',array('options'=>array('false'=>'LRN Server', 'true'=>'Client SIP Header'),'label'=>false, 'class' =>'select' ,'div'=>false,'type'=>'select', 'selected'=>$lnp_dipping));
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td><?php __('Min Duration'); ?></td>
                            <td>
                                <?php echo $form->input('delay_bye_second',array('id'=>'delay_bye_second','label'=>false ,'div'=>false,'type'=>'text','maxlength'=>'5'));?>&nbsp;s      
                            </td>
                        </tr>
                        <!--
                        <tr>
                            <td><?php __('Delay Bye Limit'); ?></td>
                            <td>
                        <?php echo $form->input('delay_bye_limit',array('id'=>'delay_bye_limit','label'=>false ,'div'=>false,'type'=>'text'));?>        
                            </td>
                        </tr>
                        -->
                        <tr>
                            <td><?php __('Ignore Early NOSDP'); ?></td>
                            <td>
                                <?php 
                                $nosdp=0;
                                echo $form->input('ignore_early_nosdp',array('options'=>array('False', 'True'),'label'=>false, 'class' =>'select' ,'div'=>false,'type'=>'select', 'selected'=>$nosdp));
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td><?php __('Ring Timer'); ?></td>
                            <td>
                                <?php echo $form->input('ring_timeout',array('id'=>'ring_timeout','label'=>false ,'div'=>false,'type'=>'text','maxlength'=>'5', 'value' => $default_timeout['ring_timeout']));?>s      
                            </td>
                        </tr>
                        <tr>
                            <td><?php __('Max Duration'); ?></td>
                            <td>
                                <?php echo $form->input('max_duration',array('id'=>'max_duration','label'=>false ,'div'=>false,'type'=>'text'));?>&nbsp;s        
                            </td>
                        </tr>
                        
                    </table>
                </fieldset>

                <?php //***************************************费率设置************************************************************?>

                <table class="form">

                    <tr>
                        <td colspan="2" class="value"><div class="cb_select" style="height:30px; line-height:30px;text-align: left; border:none;">


                                <div  style="display:none;">
                                    <?php echo $form->checkbox('lnp',array('checked'=>'false','style'=>'margin-left: 40px;'))?>
                                    <label for="cp_modules-c_invoices">LRN</label>
                                    <?php echo $form->checkbox('lrn_block',array('checked'=>'false','style'=>'margin-left: 40px;'))?>
                                    <label for="cp_modules-c_stats_summary">Block LRN</label>
                                    <?php echo $form->checkbox('dnis_only',array('checked'=>'checked','style'=>'margin-left: 40px;'))?>
                                    <label for="cp_modules-c_stats_summary">DNIS Only</label>
                                </div>
                            </div></td>
                    </tr>
                </table>

                <fieldset style="display:none;">
                    <legend>
                        <?php __('rateTable')?>
                    </legend>
                    <table class="form">
                        
                        <tr>
                            <td><?php __('rateTable')?>
                                :</td>
                            <td><?php echo $form->input('rate_table_id',array('options'=>$rate,'empty'=>'  ','label'=>false, 'class' =>'select' ,'div'=>false,'type'=>'select','style'=>'width:210px'));?>

                            </td>
                        </tr>
                    </table>
                </fieldset>
                <fieldset style="display:none;">
                    <legend><?php echo __('Route Plan',true);?></legend>
                    <table class="form">
                        <tr>
                            <td><?php echo __('Route Plan',true);?>:</td>
                            <td><?php echo $form->input('route_strategy_id',array('options'=>$route_policy,'empty'=>'  ','label'=>false ,'div'=>false,'type'=>'select'));?></td>
                        </tr>
                    </table>
                </fieldset>

                <!--<fieldset><legend><span id="ht-100012" class="helptip" rel="helptip">
                        
                        主被叫号转换</span><span id="ht-100012-tooltip" class="tooltip">Send notification when current balance + credit limit is lower than specified threshold. Leave field empty to disable notification.</span></legend>
                        <table class="form">
                        <col style="width:37%;"/><col style="width:62%;"/>
                        
                        
                        
                        <tr>
                            <td>转换规则:</td>
                            <td>
                            
                                        <?php echo $form->input('translation_id',
                                        array('options'=>$r,'empty'=>'==select==','label'=>false ,'div'=>false,'type'=>'select'));?>
                            </td>
                        
                        </tr>
                        
                        
                        <tr>
                            <td>时间段:</td>
                            <td>
                            
                                        <?php echo $form->input('time_profile_id',
                                        array('options'=>$timepro,'empty'=>'==select==','label'=>false ,'div'=>false,'type'=>'select'));?>
                            </td>
                        
                        </tr>
                        </table>
                        </fieldset>
                        
                --><!-- / COLUMN 1 --></td>
            <td><!-- COLUMN 2 -->
                <table class="form">
                    
                        <!--
                        <tr>
                            <td><?php __('Block Higher'); ?></td>
                            <td>
                                <?php 
                                $lrn_block='false';
                                echo $form->input('lrn_block',array('options'=>array('true'=>'True', 'false'=>'False'),'label'=>false, 'class' =>'select' ,'div'=>false,'type'=>'select', 'selected'=>$lrn_block));
                                ?>
                            </td>
                        </tr>
                        -->
                        <tr>
                            <td><?php __('Bill By'); ?></td>
                            <td>
                                <?php 
                                echo $form->input('bill_by',array('options'=>array(0=>'DNIS', 1=>'LRN', 2=>'LRN BLOCK', 3=>'LRN Block Higher', 4=>'Follow Rate Deck'),'label'=>false, 'class' =>'select' ,'div'=>false,'type'=>'select', 'selected'=>4));
                                ?>
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
                        <tr id="did_billing_method_tr">
                            <td><?php __('Billing Method'); ?></td>
                            <td>
                                <?php
                                echo $form->input('billing_method',array('options'=>array(0=>'By Minute', 1=>'By Port'),'label'=>false, 'class' =>'select' ,'div'=>false,'type'=>'select', 'selected'=>0));
                                ?>
                            </td>
                        </tr>
                        <tr id="did_rate_table_tr">
                        	<td><?php __('Rate Table'); ?></td>
                        	<td>
                        		<?php 
                        		echo $form->input('rate_table',array('options'=>$rate,'label'=>false, 'class' =>'select' ,'div'=>false,'type'=>'select'));
                        		?>
                        	</td>
                        </tr>
                        <tr id="did_amount_per_port_tr">
                            <td><?php __('Per Port Amount'); ?></td>
                            <td>
                                 <?php echo $form->input('amount_per_port',array('id'=>'amount_per_port','label'=>false ,'div'=>false,'type'=>'text'));?>
                            </td>
                        </tr>                        
                        <?php endif; ?>
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
                <fieldset>
                    <legend>
                        <?php __('codec')?>
                    </legend>
                    <table class="form">
                        <tr>
                            <td><?php echo $form->input('select1',array('id'=>'select1','options'=>$d,'multiple' => true,'style'=>'width: 200px; height: 250px;','label'=>false, 'class' =>'select' ,'div'=>false,'type'=>'select'));?></td>
                            <td><input  style="width: 48px; height: 25px; margin-left: 0px;"    onclick="DoAdd();"  type="button"  value="<?php __('add')?>"  />
                                <br/>
                                <br/>
                                <input  type="button"   style="width: 48px; height: 25px; margin-left: 0px;"  onclick="DoDel();"   value="<?php __('delete')?>"  /></td>
                            <td><?php echo $form->input('select2',array('id'=>'select2','options'=>'','multiple' => true,'style'=>'width: 200px; height: 250px;','label'=>false, 'class' =>'select' ,'div'=>false,'type'=>'select'));?></td>
                            <td><input  style="width: 48px; height: 25px; margin-left: 0px;"    onclick="moveOption('select2','up');"  type="button"  value="<?php __('up')?>"  />
                                <br/>
                                <br/>
                                <input  type="button"   style="width: 48px; height: 25px; margin-left: 0px;"  onclick="moveOption('select2','down');"   value="<?php __('Down')?>"  /></td>
                        </tr>
                    </table>
                </fieldset>

                <!-- / COLUMN 2 --></td>
            <td><!-- COLUMN 3 -->

                <?php //************************client panel**********************************?>
                <?php //************************balancenotice**********************************?>

                <!-- / COLUMN 3 --></td>
        </tr>
    </table>
    <?php echo $this->element("gatewaygroups/host")?>
    <?php  echo $this->element("gatewaygroups/resource_prefix")?>
    <?php if($$hel->_get('viewtype')=='wizard'){?>
    <div id="form_footer">
        <input type="submit" class="input in-submit"   onclick="seleted_codes();jQuery('#GatewaygroupAddResouceIngressForm').attr('action','?nextType=egress&<?php echo $$hel->getParams('getUrl')?>')" value="<?php echo __('Next Egress')?>" style="width:80px" />
        <input type="submit"  class="input in-submit"  onclick="seleted_codes();jQuery('#GatewaygroupAddResouceIngressForm').attr('action','?nextType=ingress&<?php echo $$hel->getParams('getUrl')?>')" value="<?php echo __('Next Ingress')?>" style="width:80px"/>
        <input type="button"  value="<?php echo __('End')?>" class="input in-submit" onclick="location='<?php echo $this->webroot?>clients/index?filter_id=<?php echo $$hel->_get('query.id_clients')?>'"/>
    </div>
    <?php }else{?>
    <div id="form_footer">
        <input type="submit" id ="submit_form" class="input in-submit" name="submit" value="<?php echo __('submit')?>" />
        <input type="reset"  value="<?php echo __('cancel',true);?>" class="input in-submit" />
    </div>
    <?php }?>
    <?php echo $form->end();?> </div>

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

<script type="text/javascript" src="<?php echo $this->webroot?>js/gateway.js"></script> 
<script type="text/javascript"><!--
    jQuery(document).ready(
    function(){
        var did_billing_method_tr =  $('#did_billing_method_tr');
        var did_rate_table_tr = $('#did_rate_table_tr');
        var did_amount_per_port_tr = $('#did_amount_per_port_tr');
        jQuery('#totalCall,#totalCPS').xkeyvalidate({type:'Num'});

		jQuery('#GatewaygroupTrunkType2').change(function() {
			if($(this).val() == '0')
			{
				did_billing_method_tr.hide();
				did_rate_table_tr.hide();
                                $('#did_amount_per_port_tr').hide();
				$('#add_resource_prefix').show();
				$('#resource_table').show();
			}
			else
			{
				did_billing_method_tr.show();
                                jQuery('#GatewaygroupBillingMethod').change();
				//did_rate_table_tr.show();
                                //$('#did_amount_per_port_tr').show();
				$('#add_resource_prefix').hide();
				$('#resource_table').hide();
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
                
        
        var $transaction_fee_panel = $('#transaction_fee_panel');
        
        $('#GatewaygroupTrunkType').change(function() {
            if ($(this).val() == 2)
            {
                $transaction_fee_panel.show();
            }
            else
            {
                $transaction_fee_panel.hide();
            }
        }).trigger('change');
        
        jQuery('#submit_form').click(function(){
            var re =true; 
            if(jQuery('#alias').val()==''){
                jQuery('#alias').addClass('input in-input in-text');
                jQuery(this).jGrowlError('The field ingress name cannot be NULL.');
                re=false;
                                       
            }else if(/[^0-9A-Za-z-\_\s]/.test(jQuery("#alias").val())){
                jQuery('#alias').addClass('input in-input in-text');
                jQuery(this).jGrowlError('Ingress Name,allowed characters: a-z,A-Z,0-9,-,_,space, maximum of 16 characters in length!');
                re = false;
            }
			
            if(jQuery('#totalCall').val()!=''){
                if(/\D/.test(jQuery('#totalCall'.val()))){
                    jQuery(this).addClass('input in-input in-text');
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
                    jQuery(this).addClass('input in-input in-text');
                    jQuery(this).jGrowlError('CPS Limit, must be whole number!');
                    re =false;
                }	   
		 	   
            }
     
            if(jQuery('#GatewaygroupClientId').val()==''){
                jQuery(this).addClass('');
                jQuery(this).jGrowlError('Please Select Carriers !');
                re= false;
            }
            
            if(jQuery('#ring_timeout').val() == '' || jQuery('#ring_timeout').val() <1 || jQuery('#ring_timeout').val() > 60) {
            jQuery('#ring_timeout').addClass('invalid');
            jQuery.jGrowl('Ring Timer cant not be greater than 60 or less than 1!',{theme:'jmsg-error'});
            re =  false;
        }
	    	
            return re;
        })
  
    });	
    
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
    
});
</script>