<div id="title">
    <h1>
		 Carrier [<?php echo $client_name ?>] >>Edit <?php echo  ($type == 'ingress') ? "Ingress" : "Egress" ; ?>  PASS [<?php echo $res['Gatewaygroup']['alias']; ?>] 
    </h1>
</div>

<div id="container">

	<?php if ($type == 'ingress'): ?>
	<?php echo  $this->element('ingress_tab',array('active_tab'=>'pass_trusk'));?>
	<?php else: ?>
	<?php echo  $this->element('egress_tab',array('active_tab'=>'pass_trusk'));?>
	<?php endif; ?>
	
	
		<?php echo $form->create ('Gatewaygroup', array ('url' => '/' . $this->params['url']['url']));?>
                
		<div id="support_panel" style="text-align:center;padding:20px;">
                 
                    
                	<?php if ($type == 'ingress'): ?>
                        
                        <?php
                        
                        $options = array(
                    'NEVER',
                    'PASS_THROUGH',
                    'ALWAYS',
                );  
                
                ?>
            <label title="Remote-Party-ID">RPID</label>
            <?php echo $form->input('rpid',array('label'=>false ,'div'=>false,'type'=>'select', 'value'=>$res['Gatewaygroup']['rpid'], 'options'=>$options));?>
            <label title="P-Asserted-Identity">PAID</label>
            <?php echo $form->input('paid',array('label'=>false ,'div'=>false,'type'=>'select', 'value'=>$res['Gatewaygroup']['paid'], 'options'=>$options));?>
            <label title="isup-oli">OLI</label>
            <?php echo $form->input('oli',array('label'=>false ,'div'=>false,'type'=>'select', 'value'=>$res['Gatewaygroup']['oli'], 'options'=>$options));?>
            <label title="P-Charge-Info">PCI</label>
            <?php echo $form->input('pci',array('label'=>false ,'div'=>false,'type'=>'select', 'value'=>$res['Gatewaygroup']['pci'], 'options'=>$options));?>
            <label title="Privacy">PRIV</label>
            <?php echo $form->input('priv',array('label'=>false ,'div'=>false,'type'=>'select', 'value'=>$res['Gatewaygroup']['priv'], 'options'=>$options));?>
            <label title="Diversion">DIV</label>
            <?php echo $form->input('div',array('label'=>false ,'div'=>false,'type'=>'select', 'value'=>$res['Gatewaygroup']['div'], 'options'=>$options));?>
            
            <?php else: ?>
            
            <label title="Remote-Party-ID">RPID</label>
            <?php echo $form->input('rpid',array('label'=>false ,'div'=>false,'type'=>'checkbox', 'checked'=>$res['Gatewaygroup']['rpid'] ? true : false));?>
            <label title="P-Asserted-Identity">PAID</label>
            <?php echo $form->input('paid',array('label'=>false ,'div'=>false,'type'=>'checkbox', 'checked'=>$res['Gatewaygroup']['paid']? true : false));?>
            <label title="isup-oli">OLI</label>
            <?php echo $form->input('oli',array('label'=>false ,'div'=>false,'type'=>'checkbox', 'checked'=>$res['Gatewaygroup']['oli']? true : false));?>
            <label title="P-Charge-Info">PCI</label>
            <?php echo $form->input('pci',array('label'=>false ,'div'=>false,'type'=>'checkbox', 'checked'=>$res['Gatewaygroup']['pci']? true : false));?>
            <label title="Privacy">PRIV</label>
            <?php echo $form->input('priv',array('label'=>false ,'div'=>false,'type'=>'checkbox', 'checked'=>$res['Gatewaygroup']['priv']? true : false));?>
            <label title="Diversion">DIV</label>
            <?php echo $form->input('div',array('label'=>false ,'div'=>false,'type'=>'checkbox', 'checked'=>$res['Gatewaygroup']['div']? true : false));?>
            <?php endif; ?>
            <br />
            <br />
            <br />
            
            <div class="button-groups">
            	<input type="submit" value="Submit" />
            </div>
        </div>
        <?php echo $form->end(); ?>
</div>