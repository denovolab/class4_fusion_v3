<style type="text/css">
    .form tr{ height:30px; line-height:30px;}
    .form .label2 {
        font-size: 12px;
        width: 40%;
    }
</style>
<div id="title">
    <h1>
        <?php if(isset($_GET['query']['id_clients'])):?><?php echo __('Carrier'); echo ' ['.$c[$_GET['query']['id_clients']].'] ';?><?php else:?><?php echo __('Carrier',true);?> [<?php print($c[array_keys_value($post,'Gatewaygroup.client_id')]); ?>]<?php endif;?>
        &gt;&gt;<?php echo __('edit',true);?> <?php __('Egress')?> <font  class="editname" title="Name">   <?php echo empty($post['Gatewaygroup']['alias'])||$post['Gatewaygroup']['alias']==''?'':"[".$post['Gatewaygroup']['alias']."]"?> </font>

    </h1>
    <ul id="title-menu">
        <li>
            <?php echo $appCommon->show_back_href()?>
        </li>
    </ul>
</div>
<div class="container">
    <?php echo  $this->element('egress_tab',array('active_tab'=>'base'));?>
    <?php echo $form->create ('Gatewaygroup', array ('action' => 'edit_resouce_egress' ));?>
    <?php echo $form->input('resource_id',array('id'=>'alias','label'=>false ,'value'=>$post['Gatewaygroup']['resource_id'],'div'=>false,'type'=>'hidden','maxlength'=>'6'));?>
    <?php echo $form->input('ingress',array('label'=>false ,'value'=>'false','div'=>false,'type'=>'hidden'));?>
    <?php echo $form->input('egress',array('label'=>false ,'value'=>'true','div'=>false,'type'=>'hidden'));?>
    <input type="hidden" value="<?php echo $post['Gatewaygroup']['resource_id'];?>" name="resource_id"/>
    <table class="cols" style="width:80%;"><col width="35%"/><col width="38%"/><col width="27%"/><tr>
            <td><!-- COLUMN 1 -->
                <?php //**********系统信息**************?>
                <fieldset style="width:350px;">
                    <table class="form">
                        <tr>
                            <td><?php echo __('Egress Name',true);?> :</td>
                            <td>
                                <?php echo $form->input('alias',array('id'=>'alias','label'=>false ,'div'=>false,  'value'=>$post['Gatewaygroup']['alias'],'type'=>'text','maxlength'=>'100'));?>
                            </td>
                        </tr>


                        <?php if(isset($_GET['viewtype'])&&$_GET['viewtype']=='client'){?>
                        <tr style="display:none">
                            <td><?php __('Carrier')?>:</td>
                            <td>
                                <?php echo $form->input('client_id',array('options'=>$c,'empty'=>'','label'=>false,
                                'selected'=>array_keys_value($_GET, 'query.id_clients'),'class' =>'select' ,'div'=>false,'type'=>'select'));?>
                            </td>
                        </tr>
                        <?php }else{?>
                        <tr>
                            <td><?php __('Carrier')?>:</td>
                            <td>
                                <?php echo $form->input('client_id',array('options'=>$c,'empty'=>'','label'=>false,
                                'selected'=>array_keys_value($post,'Gatewaygroup.client_id'),'class' =>'select' ,'div'=>false,'type'=>'select'));?>
                            </td>
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

                                echo $form->input('media_type',array('options'=>$t,'label'=>false, 'class' =>'select' ,'selected'=>$post['Gatewaygroup']['media_type'],'div'=>false,'type'=>'select'));?>

                            </td>

                        </tr>
                        <tr>
                            <td class="align_right padding-r10"><?php echo __('lowprofit') ?></td>
                            <td align="left">
                                <?php echo $form->input('profit_margin', array('label' => false, 'value' => '0', 'div' => false, 'type' => 'text', 'class' => 'in-decimal input in-text validate[custom[number]]', 'maxlength' => '6', 'style' => 'width:50px', 'value' => $post['Gatewaygroup']['profit_margin'])) ?>
                                <?php echo $xform->input('profit_type', array('options' => Array(1 => 'Percentage', 2 => 'Value'), 'style' => 'width:102px', 'value' => $post['Gatewaygroup']['profit_type'])) ?>
                            </td>
                        </tr>
                        <tr>
                            <td><?php __('calllimit')?>:</td>
                            <td>
                                <?php echo $form->input('capacity',array('id'=>'totalCall','label'=>false ,'div'=>false,'value'=>$post['Gatewaygroup']['capacity'],'type'=>'text','maxlength'=>'8'));?>
                            </td>
                        </tr>

                        <tr>
                            <td><?php __('cps')?>:</td>
                            <td>
                                <?php echo $form->input('cps_limit',array('id'=>'totalCPS','label'=>false ,'div'=>false,'value'=>$post['Gatewaygroup']['cps_limit'],'type'=>'text','maxlength'=>'8'));?>
                            </td>
                        </tr>
                        <tr>
                            <td><?php __('proto')?>:</td>
                            <td>
                                <?php echo $form->input('proto',array('label'=>false ,'value'=>$post['Gatewaygroup']['proto'],'div'=>false,'type'=>'select','options'=>Array(Resource::RESOURCE_PROTO_ALL=>'ALL',Resource::RESOURCE_PROTO_SIP=>'SIP',Resource::RESOURCE_PROTO_PROTO=>'H323')));?>
                            </td>
                        </tr>
                        <tr>
                            <td><?php __('pddtimeout')?>:</td>
                            <td>
                                <?php echo $form->input('wait_ringtime180',array('id'=>'wait_ringtime180','label'=>false ,'div'=>false,'value'=>$post['Gatewaygroup']['wait_ringtime180'],'type'=>'text','maxlength'=>'5'));?>&nbsp;&nbsp;ms
                            </td>
                        </tr>
                        <tr>
                            <td><?php __('active')?>:</td>
                            <td>
                                <?php 
                                $post['Gatewaygroup']['active']=='1' ? $au='true' : $au='false';
                                echo $form->input('active',array('options'=>array('true'=>'True', 'false'=>'False'),'label'=>false, 'class' =>'select' ,'div'=>false,'type'=>'select', 'selected'=>$au));
                                ?></td>
                        </tr>
                        <tr>
                            <td><?php __('T38')?>:</td>
                            <td>
                                <?php                   
                                $post['Gatewaygroup']['t38']=='t' ? $t38='true' : $t38='false';
                                echo $form->input('t38', array('options'=>array('true'=>'Enable', 'false'=>'Disable'),'label'=>false, 'class' =>'select' ,'div'=>false,'type'=>'select', 'selected'=>$t38));
                                ?></td>
                        </tr>
<!--
                        <tr>
                            <td><?php __('rateTable')?>:</td>
                            <td>
                                <?php echo $form->input('rate_table_id',array('options'=>$rate,
                                'empty'=>'  ','label'=>false, 'class' =>'select' ,'selected'=>$post['Gatewaygroup']['rate_table_id'],'div'=>false,'type'=>'select'));?>
                                <?php  if ($_SESSION['role_menu']['Routing']['gatewaygroups:view']['model_w']) {?>
                                <img id="addratetable" style="cursor:pointer;" src="<?php echo $this->webroot?>images/add.png" onclick="showDiv('pop-div','500','200','<?php echo $this->webroot?>clients/addratetable');" /> 
                                <?php }?>
                            </td>
                        </tr>
-->
                        <tr style="line-height:1;">
                            <td><?php __('HostStrategy')?></td>
                            <td>

                                <?php

                                $post['Gatewaygroup']['res_strategy']=='1' ? $res_strategy='1' : $res_strategy='2';

                                echo $form->input('res_strategy',array('options'=>array('1'=>'top-down', '2'=>'round-robin'),'label'=>false, 'class' =>'select' ,'div'=>false,'type'=>'select', 'selected'=>$res_strategy));
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td><?php __('RFC 2833'); ?></td>
                            <td>
                                <?php 
                                $post['Gatewaygroup']['rfc_2833']=='t' ? $rfc2833='true' : $rfc2833='false';
                                echo $form->input('rfc_2833',array('options'=>array('true'=>'True', 'false'=>'False'),'label'=>false, 'class' =>'select' ,'div'=>false,'type'=>'select', 'selected'=>$rfc2833));
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td><?php __('Pass Dipping Info'); ?></td>
                            <td>
                                <?php 
                                $post['Gatewaygroup']['lnp_dipping']=='t' ? $lnp_dipping='true' : $lnp_dipping='false';
                                echo $form->input('lnp_dipping',array('options'=>array('true'=>'Add Header', 'false'=>'Not Add Header'),'label'=>false, 'class' =>'select' ,'div'=>false,'type'=>'select', 'selected'=>$lnp_dipping));
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td><?php __('Min Duration'); ?></td>
                            <td>
                                <?php echo $form->input('delay_bye_second',array('id'=>'delay_bye_second','label'=>false ,'div'=>false,'type'=>'text','maxlength'=>'5','value'=>$post['Gatewaygroup']['delay_bye_second']));?>&nbsp;s
                            </td>
                        </tr>
                        <!--
                        <tr>
                            <td><?php __('Delay Bye Limit'); ?></td>
                            <td>
                        <?php echo $form->input('delay_bye_limit',array('id'=>'delay_bye_limit','label'=>false ,'div'=>false,'type'=>'text','maxlength'=>'5','value'=>$post['Gatewaygroup']['delay_bye_limit']));?>        
                            </td>
                        </tr>
                        -->
                        <tr>
                            <td><?php __('Max Duration'); ?></td>
                            <td>
                                <?php echo $form->input('max_duration',array('id'=>'max_duration','label'=>false ,'div'=>false,'type'=>'text','maxlength'=>'5','value'=>$post['Gatewaygroup']['max_duration']));?>&nbsp;s
                            </td>
                        </tr>
                        <!--  
                        <tr>
                            <td><?php __('LRN/DNIS BLOCK'); ?></td>
                            <td>
                                <?php 
                                $post['Gatewaygroup']['lrn_block']=='t' ? $lrn_block='true' : $lrn_block='false';
                                echo $form->input('lrn_block',array('options'=>array('true'=>'True', 'false'=>'False'),'label'=>false, 'class' =>'select' ,'div'=>false,'type'=>'select', 'selected'=>$lrn_block));
                                ?>
                            </td>
                        </tr>-->
                        <!--
                        <tr>
                            <td><?php __('Switch Profile'); ?></td>
                            <td>
                                <?php echo $form->input('switch_profile_id',array('options'=>$switch_profiles,
                                'empty'=>'  ','label'=>false, 'class' =>'select' ,'selected'=>$post['Gatewaygroup']['switch_profile_id'],'div'=>false,'type'=>'select'));?>
                            </td>
                        </tr>
                        -->
                        
                        <?php if($is_did_enable): ?>
                        <tr>
                            <td><?php __('Type'); ?></td>
                            <td>
                                <?php 
                                echo $form->input('trunk_type2',array('options'=>array(0=>'Termination', 1=>'Origination'),'label'=>false, 'class' =>'select' ,'div'=>false,'type'=>'select', 'selected'=>$post['Gatewaygroup']['trunk_type2']));
                                ?>
                            </td>
                        </tr>
                        <tr id="did_billing_rule_tr">
                            <td><?php __('Orig. Billing Rule'); ?></td>
                        	<td>
                                <?php echo $form->input('billing_rule',array('options'=>$routing_rules,'selected'=>$post['Gatewaygroup']['billing_rule'],
                                'empty'=>'  ','label'=>false, 'class' =>'select' ,'div'=>false,'type'=>'select'));?>
                                </td>
                        </tr>
                        <tr id="did_billing_method_tr">
                            <td><?php __('Billing Method'); ?></td>
                            <td>
                                <?php
                                echo $form->input('billing_method',array('options'=>array(0=>'By Minute', 1=>'By Port'),'label'=>false, 'class' =>'select' ,'div'=>false,'type'=>'select', 'selected'=>$post['Gatewaygroup']['billing_method']));
                                ?>
                            </td>
                        </tr>
                        <?php endif; ?>
                        <tr id="did_rate_table_tr">
                        	<td><?php __('Rate Table'); ?></td>
                        	<td>
                                <?php echo $form->input('rate_table_id',array('options'=>$rate,
                                'empty'=>'  ','label'=>false, 'class' =>'select' ,'selected'=>$post['Gatewaygroup']['rate_table_id'],'div'=>false,'type'=>'select'));?>
                                <?php  if ($_SESSION['role_menu']['Routing']['gatewaygroups:view']['model_w']) {?>
                                <img id="addratetable" style="cursor:pointer;" src="<?php echo $this->webroot?>images/add.png" onclick="showDiv('pop-div','500','200','<?php echo $this->webroot?>clients/addratetable');" /> 
                                <?php }?>
                                </td>
                        </tr>
                        <?php if($is_did_enable): ?>
                        <tr id="did_amount_per_port_tr">
                            <td><?php __('Per Port Amount'); ?></td>
                            <td>
                                 <?php echo $form->input('amount_per_port',array('id'=>'amount_per_port','label'=>false ,'div'=>false,'type'=>'text', 'value'=>$post['Gatewaygroup']['amount_per_port']));?>
                            </td>
                        </tr>
                        <?php endif; ?>
                        <tr>
                            <td><?php __('Ring Timer'); ?></td>
                            <td>
                                <?php echo $form->input('ring_timeout',array('id'=>'ring_timeout','label'=>false ,'div'=>false,'type'=>'text','maxlength'=>'5', 'value'=>$post['Gatewaygroup']['ring_timeout']));?>s      
                            </td>
                        </tr>
<!--                        <tr>-->
<!--                            <td>--><?php //__('Rate Profile'); ?><!--</td>-->
<!--                            <td>-->
<!--                                --><?php //
//                                echo $form->input('rate_profile',array('options'=>array('False', 'True'),'label'=>false, 'class' =>'select' ,'div'=>false,'type'=>'select', 'selected'=>$post['Gatewaygroup']['rate_profile']));
//                                ?><!--  -->
<!--                            </td>-->
<!--                        </tr>-->
<!--                        <tr class="rate_profile_control">-->
<!--                            <td>--><?php //__('USA'); ?><!--</td>-->
<!--                            <td>-->
<!--                                --><?php //
//                                echo $form->input('us_route',array('options'=>array('Other', 'Intra', 'Inter', 'Highest'),'label'=>false, 'class' =>'select' ,'div'=>false,'type'=>'select', 'selected'=>$post['Gatewaygroup']['us_route']));
//                                ?><!--  -->
<!--                            </td>-->
<!--                        </tr>-->
<!--                        <tr class="rate_profile_control">-->
<!--                            <td>--><?php //__('US Territories'); ?><!--</td>-->
<!--                            <td>-->
<!--                                --><?php //
//                                echo $form->input('us_other',array('options'=>array('Other', 'Intra', 'Inter', 'Highest'),'label'=>false, 'class' =>'select' ,'div'=>false,'type'=>'select', 'selected'=>$post['Gatewaygroup']['us_other']));
//                                ?><!--  -->
<!--                            </td>-->
<!--                        </tr>-->
<!--                        <tr class="rate_profile_control">-->
<!--                            <td>--><?php //__('Canada'); ?><!--</td>-->
<!--                            <td>-->
<!--                                --><?php //
//                                echo $form->input('canada_route',array('options'=>array('Other', 'Intra', 'Inter', 'Highest'),'label'=>false, 'class' =>'select' ,'div'=>false,'type'=>'select', 'selected'=>$post['Gatewaygroup']['canada_route']));
//                                ?><!--  -->
<!--                            </td>-->
<!--                        </tr>-->
<!--                        <tr class="rate_profile_control">-->
<!--                            <td>--><?php //__('Non USA/Canada Territories'); ?><!--</td>-->
<!--                            <td>-->
<!--                                --><?php //
//                                echo $form->input('canada_other',array('options'=>array('Other', 'Intra', 'Inter', 'Highest'),'label'=>false, 'class' =>'select' ,'div'=>false,'type'=>'select', 'selected'=>$post['Gatewaygroup']['canada_other']));
//                                ?><!--  -->
<!--                            </td>-->
<!--                        </tr>-->
<!--                        <tr class="rate_profile_control">-->
<!--                            <td>--><?php //__('International'); ?><!--</td>-->
<!--                            <td>-->
<!--                                --><?php //
//                                echo $form->input('intl_route',array('options'=>array('Other', 'Intra', 'Inter', 'Highest'),'label'=>false, 'class' =>'select' ,'div'=>false,'type'=>'select', 'selected'=>$post['Gatewaygroup']['intl_route']));
//                                ?><!--  -->
<!--                            </td>-->
<!--                        </tr>-->
                        <tr>
                            <td><?php __('Rounding Decimal Places'); ?></td>
                            <td>
                                <?php echo $form->input('rate_decimal',array('label'=>false ,'div'=>false,'type'=>'text','maxlength'=>'5', 'value' => $post['Gatewaygroup']['rate_decimal']));?>     
                            </td>
                        </tr>
                        <tr>
                            <td><?php __('Rounding'); ?></td>
                            <td>
                                <?php echo $form->input('rate_rounding',array('options'=>array('Up', 'Down'), 'label'=>false ,'div'=>false, 'value' => $post['Gatewaygroup']['rate_rounding']));?>     
                            </td>
                        </tr>
                    </table>
                </fieldset>
                <!--
				<div id="support_panel">
                    <label title="Remote-Party-ID">RPID</label>
                    <?php echo $form->input('rpid',array('label'=>false ,'div'=>false,'type'=>'checkbox', 'checked'=>$post['Gatewaygroup']['rpid'] ? true : false));?>
                    <label title="P-Asserted-Identity">PAID</label>
                    <?php echo $form->input('paid',array('label'=>false ,'div'=>false,'type'=>'checkbox', 'checked'=>$post['Gatewaygroup']['paid']? true : false));?>
                    <label title="isup-oli">OLI</label>
                    <?php echo $form->input('oli',array('label'=>false ,'div'=>false,'type'=>'checkbox', 'checked'=>$post['Gatewaygroup']['oli']? true : false));?>
                    <label title="P-Charge-Info">PCI</label>
                    <?php echo $form->input('pci',array('label'=>false ,'div'=>false,'type'=>'checkbox', 'checked'=>$post['Gatewaygroup']['pci']? true : false));?>
                    <label title="Privacy">PRIV</label>
                    <?php echo $form->input('priv',array('label'=>false ,'div'=>false,'type'=>'checkbox', 'checked'=>$post['Gatewaygroup']['priv']? true : false));?>
                    <label title="Diversion">DIV</label>
                    <?php echo $form->input('div',array('label'=>false ,'div'=>false,'type'=>'checkbox', 'checked'=>$post['Gatewaygroup']['div']? true : false));?>
                </div>
                -->
                <?php //***************************************费率设置************************************************************?>
                <table class="form">
                    <col style="width:37%;"/><col style="width:62%;"/>
                    <tr>
                        <td colspan="2" class="value">

                            <div class="cb_select" style="height:30px; line-height:30px;text-align: left;border:none;">
                                <div>


                                    <!--					     <?php
                                                                                                                    empty($post['Gatewaygroup']['lnp'])?$au='false':$au='checked';
                                                                                    echo $form->checkbox('lnp',array('checked'=>$au,'style'=>'margin-left: 40px;'))
                                                                                                    ?>
                                                                                  <label for="cp_modules-c_invoices">LRN</label>
                                                                 
                                                                   <?php
                                                                                                                    empty($post['Gatewaygroup']['lrn_block'])?$au='false':$au='checked';
                                                                                    echo $form->checkbox('lrn_block',array('checked'=>$au,'style'=>'margin-left: 40px;'))
                                                                                      ?>
                                                                   <label for="cp_modules-c_stats_summary">Block LRN</label>-->
                                </div>
                            </div>
                        </td>
                    </tr>
                </table>
                <!-- / COLUMN 1 --></td><td><!-- COLUMN 2 -->
                <script type="text/javascript" src="<?php echo $this->webroot?>js/jquery.livequery.js"></script>
                <script type="text/javascript">
                    jQuery(function($){
                        $('#addratetable').live('click', function() {
                            $(this).prev().addClass('clicked');
                            //window.open('<?php echo $this->webroot?>clients/addratetable', 'addratetable', 'height=800,width=1000,top=0,left=0,toolbar=no,menubar=no,scrollbars=no, resizable=no,location=no, status=no');
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
                                <?php echo $form->input('select1',array('id'=>'select1','options'=>$nousecodes,'multiple' => true,
                                'style'=>'width: 200px; height: 250px;',
                                'label'=>false, 'class' =>'select' ,'div'=>false,'type'=>'select'));?>
                            </td>
                            <td>
                                <input  style="width: 48px; height: 25px; margin-left: 0px;"    onclick="DoAdd();"  type="button"  value="<?php __('add')?>"class="input in-submit"  />
                                <br/><br/>
                                <input  type="button"   style="width: 48px; height: 25px; margin-left: 0px;"  onclick="DoDel();"   value="<?php __('delete')?>" class="input in-submit" />
                            </td>
                            <td>
                                <?php echo $form->input('select2',array('id'=>'select2','options'=>$usecodes,'multiple' => true,
                                'style'=>'width: 200px; height: 250px;',
                                'label'=>false, 'class' =>'select' ,'div'=>false,'type'=>'select'));?>
                            </td>
                            <td>
                                <input class="input in-submit"  style="width: 48px; height: 25px; margin-left: 0px;"    onclick="moveOption('select2','up');"  type="button"  value="<?php __('up')?>"  />
                                <br/><br/>
                                <input  type="button" class="input in-submit"  style="width: 48px; height: 25px; margin-left: 0px;"  onclick="moveOption('select2','down');"   value="<?php __('Down')?>"  />
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
                                <td><?php 
                                    echo $switch_profile['name'] 
                                    ?>
                                    <input type="hidden" name="server_names[]" value="<?php echo $switch_profile['name'] ?>" />
                                </td>
                                <td>
                                    <select name="profiles[]">
                                        <option></option>
                                        <?php foreach($profiles as $profile): ?>
                                        <option value="<?php echo $profile[0] ?>" <?php if (in_array($profile[0],$sip_profiles)) echo 'selected="selected"'  ?>><?php echo $profile[1] ?></option>
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
            </td><td>
            </td></tr></table>
    <div>
        <?php echo $this->element("gatewaygroups/host_edit")?>
    </div>

    <?php //echo $this->element("gatewaygroups/editegsd"); ?>                                

    <?php  if ($_SESSION['role_menu']['Routing']['gatewaygroups:view']['model_w']) {?> <div id="form_footer">
        <input type="submit"   onclick="seleted_codes();" value="<?php echo __('submit')?>" />

        <input type="reset" class="input in-submit" value="<?php echo __('reset',true);?>" />
    </div><?php }?>
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
        
        if(jQuery('#ring_timeout').val() == '' || jQuery('#ring_timeout').val() <1 || jQuery('#ring_timeout').val() > 60) {
            jQuery('#ring_timeout').addClass('invalid');
            jQuery.jGrowl('Ring Timer cant not be greater than 60 or less than 1!',{theme:'jmsg-error'});
            flag =  false;
        }
        
        return flag;
    });
    
    
    var did_billing_method_tr =  $('#did_billing_method_tr');
    var did_billing_rule_tr =  $('#did_billing_rule_tr');
    var did_rate_table_tr = $('#did_rate_table_tr');
    var did_amount_per_port_tr = $('#did_amount_per_port_tr');
    
    
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

   
    
});
</script>
