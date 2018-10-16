<style type="text/css">
    .form .label2{ width:40%;}
    .form tr{ height:30px; line-height:30px;}
</style>
<?php echo $this->element("gatewaygroups_edit_resouce_ingress/js") ?>
<?php echo $this->element("gatewaygroups_edit_resouce_ingress/title") ?>
<div class="container">
    <?php echo $this->element("ingress_tab", array('active_tab' => 'base')) ?>
    <?php echo $form->create('Gatewaygroup', array('action' => 'edit_resouce_ingress', 'onsubmit' => 'return checkform();')); ?>
    <?php echo $form->input('resource_id', array('id' => 'alias', 'label' => false, 'value' => $post['Gatewaygroup']['resource_id'], 'div' => false, 'type' => 'hidden', 'maxlength' => '8')); ?>
    <?php echo $form->input('ingress', array('label' => false, 'value' => 'true', 'div' => false, 'type' => 'hidden')); ?>
    <?php echo $form->input('egress', array('label' => false, 'value' => 'false', 'div' => false, 'type' => 'hidden')); ?>
    <input type="hidden" value="<?php echo $post['Gatewaygroup']['resource_id']; ?>" name="resource_id"/>
    <input type="hidden" value="<?php echo $post['Gatewaygroup']['resource_id']; ?>" name="inputRId"/>
    <table class="cols" style="width:80%;">
        <col width="55%"/>
        <col width="58%"/>
        <col width="27%"/>
        <tr>
            <td>
                <?php echo $this->element("gatewaygroups_edit_resouce_ingress/fieldset1") ?>
                <fieldset style="display:none;"><legend><?php __('rateTable') ?></legend>
                    <table class="form">
                        <tr>
                            <td><?php __('rateTable') ?>:</td>
                            <td>
                                <?php
                                echo $form->input('rate_table_id', array('options' => $rate, 'selected' => $post['Gatewaygroup']['rate_table_id'], 'empty' => '  ', 'label' => false, 'class' => 'select', 'div' => false, 'type' => 'select'));
                                ?>
                            </td>
                        </tr>
                    </table>
                </fieldset>
                <?php if ($$hel->isIngress()) { ?>
                    <fieldset style="display:none;"><legend><?php echo __('Routing Plan', true); ?>:</legend>
                        <table class="form">
                            <tr>
                                <td><?php echo __('Routing Plan', true); ?>:</td>
                                <td>

                                    <?php if (!isset($post)) {
                                        $post = Array();
                                    } ?>
    <?php echo $xform->input('route_strategy_id', Array('options' => $route_policy, 'empty' => '', 'selected' => array_keys_value($post, 'Gatewaygroup.route_strategy_id'))) ?>



                                </td>
                            </tr>
                        </table>
                    </fieldset>
                <?php } ?>

<?php echo $this->element("gatewaygroups_edit_resouce_ingress/fieldset2") ?>	
            </td>
            <td>

<?php echo $this->element("gatewaygroups_edit_resouce_ingress/fieldset3") ?>
            </td>
        </tr>
    </table>
    <div style="width:95%;">
        <?php echo $this->element("gatewaygroups/host_edit") ?>
    <?php #************************************************  ?>
    <?php echo $this->element("gatewaygroups/resource_prefix") ?>
    </div>
<?php if ($_SESSION['role_menu']['Routing']['gatewaygroups:view']['model_w']) { ?>	<div id="form_footer">
            <input type="submit"  value="<?php echo __('submit') ?>" onclick="seleted_codes();" />

            <input type="reset"   value="<?php echo __('reset') ?>"   class="input in-submit"/>
        </div><?php } ?>
<?php echo $form->end(); ?>
</div>

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


<script type="text/javascript" src="<?php echo $this->webroot ?>js/gateway.js"></script>
<script type="text/javascript">
    jQuery(document).ready(function(){
        jQuery('#totalCall,#totalCPS').xkeyvalidate({type:'Num'});
        var did_billing_method_tr =  $('#did_billing_method_tr');
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
				did_rate_table_tr.hide();
                                $('#did_amount_per_port_tr').hide();
				$('#add_resource_prefix').show();
				$('#resource_table').show();
			}
			else
			{
				did_billing_method_tr.show();
                                jQuery('#GatewaygroupBillingMethod').trigger('change');
				//did_rate_table_tr.show();
                                //$('#did_amount_per_port_tr').show();
				$('#add_resource_prefix').hide();
				$('#resource_table').hide();
			}
		}).trigger('change');
                
        
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
    
        /*
        jQuery('#GatewaygroupLnp').click(function(){
            if(jQuery('#GatewaygroupLrnBlock').attr('checked')&&jQuery('#GatewaygroupLnp').attr('checked')){
                jQuery('#GatewaygroupDnisOnly').attr('checked',false);
                jQuery('#GatewaygroupDnisOnly').attr('disabled',true);
            }else{
                jQuery('#GatewaygroupDnisOnly').attr('disabled',false);   
            }
        });
        jQuery('#GatewaygroupLrnBlock').click(function(){
            if(jQuery('#GatewaygroupLrnBlock').attr('checked')&&jQuery('#GatewaygroupLnp').attr('checked')){
                jQuery('#GatewaygroupDnisOnly').attr('checked',false);
                jQuery('#GatewaygroupDnisOnly').attr('disabled',true);
            }else{
                jQuery('#GatewaygroupDnisOnly').attr('disabled',false);   
            }
        });
        */
    });
		  

    function checkform(){
        var flag=true;
        $("input[name='resource[tech_prefix][]']").each(function(i,e)
        {
            if (!/^[\*|\.|\-|_|#|\+|\w]*$/.test($(e).val()))
            {
                jQuery(this).jGrowlError('Tech prefix, invalide format!',{theme:'jmsg-error'});
                flag= false;
            }
        });

        var arr = new Array();
        $('#resource_table').find('input[name^=resource[tech_prefix]]').each(function (){
            arr.push($(this).val());
        });
        var arr2=$.uniqueArray(arr);
        if(arr.length!=arr2.length){
            $('#resource_table').find('input[name^=resource[tech_prefix]]').each(function (){
                flag=false;
            });
            jQuery.jGrowl('Tech Prefix  Happen  Repeat.',{theme:'jmsg-error'});
        }
        
        if(jQuery('#ring_timeout').val() == '' || jQuery('#ring_timeout').val() <1 || jQuery('#ring_timeout').val() > 60) {
            jQuery('#ring_timeout').addClass('invalid');
            jQuery.jGrowl('Ring Timer cant not be greater than 60 or less than 1!',{theme:'jmsg-error'});
            flag =  false;
        }
        
        return flag;
    }

/*					  
    function cb_checke(){
        if(jQuery('#GatewaygroupDnisOnly').attr('checked')){
            jQuery('#GatewaygroupLnp').attr('disabled',true);
            //jQuery('#GatewaygroupLrnBlock').attr('disabled',true);

            jQuery('#GatewaygroupLrnBlock').attr('checked',false);
            jQuery('#GatewaygroupLnp').attr('checked',false);
        }else{
            jQuery('#GatewaygroupLnp').attr('disabled',false);
           // jQuery('#GatewaygroupLrnBlock').attr('disabled',false);
        }
    }
    jQuery('#GatewaygroupDnisOnly').click(function(){
        cb_checke();
    });
    cb_checke();
        */
        
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
    
});
</script>