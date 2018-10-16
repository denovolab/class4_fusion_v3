<style type="text/css">
#ignore {
	float: left;
}

#ignore li {
	padding: 3px;
	padding-left: 40px;
	float: left;
}
</style>
<div style="width: 350px;">
	<table class="form">
		<tr>
			<td><?php echo __('Ingress Name',true);?> :</td>
			<td><?php echo $form->input('alias',array('id'=>'alias','label'=>false ,'value'=>$post['Gatewaygroup']['alias'],'div'=>false,'type'=>'text','maxlength'=>'106','check'=>'strNum'));?>
			</td>
		</tr>
		<?php if(isset($_GET['viewtype'])&&$_GET['viewtype']=='client'){?>
		<tr style="display: none">
			<td align="right"><?php __('Carrier')?>:</td>
			<td align="left"><?php echo $form->input('client_id',array('options'=>$c,'empty'=>'','label'=>false,'selected'=>array_keys_value($_GET,'query.id_clients'), 'class' =>'select' ,'div'=>false,'type'=>'select'));?>
			</td>
		</tr>
		<?php }else{?>
		<tr>
			<td align="right"><?php __('Carrier')?>:</td>
			<td align="left"><?php echo $form->input('client_id',array('options'=>$c,'empty'=>'','label'=>false,'selected'=>array_keys_value($post,'Gatewaygroup.client_id'), 'class' =>'select' ,'div'=>false,'type'=>'select'));?>
			</td>
		</tr>
		<?php }?>
                <?php if($is_enable_type): ?>
                <tr>
                    <td><?php __('Type'); ?></td>
                    <td>
                        <?php 
                        echo $form->input('trunk_type',array('options'=>array(1=>'Class4', 2=>'Exchange'),'label'=>false, 'class' =>'select' ,'div'=>false,'type'=>'select', 'selected'=>$post['Gatewaygroup']['trunk_type']));
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
			<td align="right"><?php echo __('Media Type',true);?>:</td>
			<td align="left"><?php 
			if(Configure::read('project_name')=='partition'){
				$t=array('2'=>'Bypass Media','1'=>'Proxy Media');
			}else{
				$t=array('0'=>'Proxy Media + Transcoding','1'=>'Proxy Media ','2'=>'Bypass Media');
			}
			echo $form->input('media_type',array('options'=>$t,'label'=>false, 'class' =>'select' ,'selected'=>$post['Gatewaygroup']['media_type'],'div'=>false,'type'=>'select'));
			?>
			</td>
		</tr>
                <tr>
                    <td align="right"><?php echo __('lowprofit')?>:</td>
                    <td align="left">
                        <?php echo $form->input('profit_margin',array('label'=>false,'value'=>'0','div'=>false,'type'=>'text','class'=>'in-decimal input in-text','maxlength'=>'6','style'=>'width:33%', 'value'=>$post['Gatewaygroup']['profit_margin']))?>
                        <?php echo $xform->input('profit_type',array('options'=>Array(1=>'Percentage',2=>'Value'),'style'=>'width:45%', 'value'=>$post['Gatewaygroup']['profit_type']))?>
                    </td>
                </tr>

		<tr>
			<td align="right"><?php __('calllimit')?>:</td>
			<td align="left"><?php echo $form->input('capacity',array('id'=>'totalCall','value'=>$post['Gatewaygroup']['capacity'],'label'=>false ,'div'=>false,'type'=>'text','maxlength'=>'6'));?>
			</td>
		</tr>
		<tr>
			<td align="right"><?php __('cps')?>:</td>
			<td align="left"><?php echo $form->input('cps_limit',array('id'=>'totalCPS','label'=>false ,'value'=>$post['Gatewaygroup']['cps_limit'],'div'=>false,'type'=>'text','maxlength'=>'6'));?>
			</td>
		</tr>
		<tr>
			<td align="right"><?php __('proto')?>:</td>
			<td align="left"><?php echo $form->input('proto',array('label'=>false ,'value'=>$post['Gatewaygroup']['proto'],'div'=>false,'type'=>'select','options'=>Array(Resource::RESOURCE_PROTO_ALL=>'All',Resource::RESOURCE_PROTO_SIP=>'SIP',Resource::RESOURCE_PROTO_PROTO=>'H323')));?>
			</td>
		</tr>


		<tr>
			<td><?php __('pddtimeout')?>:</td>
			<td><?php echo $form->input('wait_ringtime180',array('id'=>'wait_ringtime180','label'=>false ,'div'=>false,'value'=>$post['Gatewaygroup']['wait_ringtime180'],'type'=>'text','maxlength'=>'8'));?>&nbsp;&nbsp;ms
			</td>
		</tr>
		<tr>
			<td><?php __('Ignore Early media')?>:</td>
			<td><?php 
			$ignore_arr = array(0=>'NONE', 1=>'180 and 183', 2=>'180 only', 3=>'183 only');
                    echo $form->input('ignore_ring_early_media',array('options'=>$ignore_arr,'label'=>false, 'class' =>'select' ,'div'=>false,'type'=>'select', 'selected'=>$post['Gatewaygroup']['ignore_ring_early_media'] ));?>
			</td>
		</tr>
		<tr>
			<td><?php __('active')?>:</td>
			<td><?php 
			$post['Gatewaygroup']['active']=='t' ? $au='true' : $au='false';
			echo $form->input('active',array('options'=>array('true'=>'True', 'false'=>'False'),'label'=>false, 'class' =>'select' ,'div'=>false,'type'=>'select', 'selected'=>$au));
			?>
			</td>
		</tr>
		<tr>
			<td><?php __('T38')?>:</td>
			<td><?php 
			$post['Gatewaygroup']['t38']=='t' ? $t38='true' : $t38='false';
			echo $form->input('t38', array('options'=>array('true'=>'Enable', 'false'=>'Disable'),'label'=>false, 'class' =>'select' ,'div'=>false,'type'=>'select', 'selected'=>$t38));
			?>
			</td>
		</tr>

		<!--<tr>
    <td><?php __('Dipping Rate')?></td>
    <td>
  		<?php echo $form->input('lnp_dipping_rate',array('id'=>'lnp_dipping_rate','label'=>false ,'div'=>false,'value'=>$post['Gatewaygroup']['lnp_dipping_rate'],'type'=>'text','maxlength'=>'10'));?>
    </td>
</tr>-->
		<tr>
			<td><?php __('RFC 2833'); ?></td>
			<td><?php 
			$post['Gatewaygroup']['rfc_2833']=='t' ? $rfc2833='true' : $rfc2833='false';
			echo $form->input('rfc_2833',array('options'=>array('true'=>'True', 'false'=>'False'),'label'=>false, 'class' =>'select' ,'div'=>false,'type'=>'select', 'selected'=>$rfc2833));
			?>
			</td>
		</tr>
		<tr>
			<td><?php __('User Dipping From'); ?></td>
			<td><?php 
			$post['Gatewaygroup']['lnp_dipping']=='t' ? $lnp_dipping='true' : $lnp_dipping='false';
			echo $form->input('lnp_dipping',array('options'=>array('false'=>'LRN Server', 'true'=>'Client SIP Header'),'label'=>false, 'class' =>'select' ,'div'=>false,'type'=>'select', 'selected'=>$lnp_dipping));
			?>
			</td>
		</tr>
		<tr>
			<td><?php __('Min Duration'); ?></td>
			<td><?php echo $form->input('delay_bye_second',array('id'=>'delay_bye_second','label'=>false ,'div'=>false,'type'=>'text','maxlength'=>'5','value'=>$post['Gatewaygroup']['delay_bye_second']));?>&nbsp;s
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
			<td><?php __('Ignore Early NOSDP'); ?></td>
			<td><?php 
			echo $form->input('ignore_early_nosdp',array('options'=>array('False', 'True'),'label'=>false, 'class' =>'select' ,'div'=>false,'type'=>'select', 'selected'=>$post['Gatewaygroup']['ignore_early_nosdp']));
			?>
			</td>
		</tr>
                <tr>
                    <td><?php __('Ring Timer'); ?></td>
                    <td>
                        <?php echo $form->input('ring_timeout',array('id'=>'ring_timeout','label'=>false ,'div'=>false,'type'=>'text','maxlength'=>'5', 'value'=>$post['Gatewaygroup']['ring_timeout']));?>s      
                    </td>
                </tr>
                 <tr>
			<td><?php __('Max Duration'); ?></td>
			<td><?php echo $form->input('max_duration',array('id'=>'max_duration','label'=>false ,'div'=>false,'type'=>'text','maxlength'=>'5','value'=>$post['Gatewaygroup']['max_duration']));?>&nbsp;s
			</td>
		</tr>
		
	</table>
</div>
