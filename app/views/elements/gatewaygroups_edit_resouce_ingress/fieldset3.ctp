<table class="form">
   
                <!--
		<tr>
			<td><?php __('Block Higher'); ?></td>
			<td><?php 
			$post['Gatewaygroup']['lrn_block']=='t' ? $lrn_block='true' : $lrn_block='false';
			echo $form->input('lrn_block',array('options'=>array('true'=>'True', 'false'=>'False'),'label'=>false, 'class' =>'select' ,'div'=>false,'type'=>'select', 'selected'=>$lrn_block));
			?>
			</td>
		</tr>
                -->
                <tr>
                    <td><?php __('Bill By'); ?></td>
                    <td>
                        <?php 
                        echo $form->input('bill_by',array('options'=>array(0=>'DNIS', 1=>'LRN', 2=>'LRN BLOCK', 3=>'LRN Block Higher', 4=>'Follow Rate Deck'),'label'=>false, 'class' =>'select' ,'div'=>false,'type'=>'select', 'selected'=>$post['Gatewaygroup']['bill_by']));
                        ?>
                    </td>
                </tr>
		<!--
<tr>
<td><?php __('Trunk Type'); ?></td>
<td>
<?php 
echo $form->input('trunk_type',array('options'=>array(1=>'class4', 2=>'exchange'),'label'=>false, 'class' =>'select' ,'div'=>false,'type'=>'select', 'selected'=>$post['Gatewaygroup']['trunk_type']));
?>
</td>
</tr>
-->
<?php if($is_did_enable): ?>
		<tr>
			<td><?php __('Type'); ?></td>
			<td><?php 
			echo $form->input('trunk_type2',array('options'=>array(0=>'Termination', 1=>'Origination'),'label'=>false, 'class' =>'select' ,'div'=>false,'type'=>'select', 'selected'=>$post['Gatewaygroup']['trunk_type2']));
			?>
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
                <tr id="did_rate_table_tr">
                    <td><?php __('Rate Table'); ?></td>
                    <td>
                            <?php 
                            echo $form->input('rate_table',array('options'=>$rate,'label'=>false, 'class' =>'select' ,'div'=>false,'type'=>'select', 'selected'=>$post['Gatewaygroup']['did_rate_table_id']));
                            ?>
                    </td>
                </tr>
                <tr id="did_amount_per_port_tr">
                <td><?php __('Per Port Amount'); ?></td>
                <td>
                        <?php echo $form->input('amount_per_port',array('id'=>'amount_per_port','label'=>false ,'div'=>false,'type'=>'text', 'value'=> $post['Gatewaygroup']['amount_per_port']));?>
                </td>
                </tr>
                <?php endif; ?>
<!--                <tr>-->
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
<fieldset>
	<legend> <?php __('codec')?></legend>
	<table class="form">
			<tr>
    			<td>
    					<?php 
    							echo $form->input('select1',array('id'=>'select1','options'=>$nousecodes,'multiple' => true,
                  'style'=>'width: 200px; height: 250px;','label'=>false, 'class' =>'select' ,'div'=>false,'type'=>'select'));
    						?>
    			</td>
					<td>
  						<input class="input in-submit"  style="width: 48px; height: 25px; margin-left: 0px;" onclick="DoAdd();" type="button" value="<?php __('add')?>"/>
                  <br/><br/>
      			<input class="input in-submit" type="button"   style="width: 48px; height: 25px; margin-left: 0px;"  onclick="DoDel();"   value="<?php __('delete')?>"  />
					</td>
   			<td>
                   <?php 
                   echo $form->input('select2',array('id'=>'select2','options'=>$usecodes,'multiple' => true,
                  'style'=>'width: 200px; height:250px;','label'=>false, 'class' =>'select' ,'div'=>false,'type'=>'select'));
                   				?>
    			</td>
     		<td>
  						<input class="input in-submit" style="width: 48px; height: 25px; margin-left: 0px;" onclick="moveOption('select2','up');"  type="button"  value="<?php __('up')?>"  />
                  <br/><br/>
      			<input  type="button" class="input in-submit"  style="width: 48px; height: 25px; margin-left: 0px;"  onclick="moveOption('select2','down');"   value="<?php __('Down')?>"  />
					</td>
			</tr>
	</table>
</fieldset>