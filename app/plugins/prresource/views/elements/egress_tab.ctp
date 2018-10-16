		<ul class="tabs">
   <li     <?php if($active_tab=='base'){ echo "class='active'";  }?> >
   		<a href="<?php echo $this->webroot?>prresource/gatewaygroups/edit_resouce_egress/<?php echo array_keys_value($this->params,'pass.0') ?>?<?php echo $appCommon->get_request_str()?>">
   				<img width="16" height="16" src="<?php echo $this->webroot?>images/list.png"/><?php __('System Information')?>
   		</a>
   </li>
   
   
<li  <?php if($active_tab=='action'){ echo "class='active'";  }?>>
   		<a href="<?php echo $this->webroot?>prresource/gatewaygroups/add_direction/<?php echo array_keys_value($this->params,'pass.0') ?>?<?php echo $appCommon->get_request_str()?>">
   				<img width="16" height="16" src="<?php echo $this->webroot ?>images/bDR.gif"/><?php __('Action')?>
   		</a>        
   </li>
   
      <li  <?php if($active_tab=='rule'){ echo "class='active'";  }?>>
   		<a href="<?php echo $this->webroot?>prresource/gatewaygroups/add_rule/<?php echo array_keys_value($this->params,'pass.0') ?>/egress?<?php echo $appCommon->get_request_str()?>">
   				<img width="16" height="16" src="<?php echo $this->webroot ?>images/bDR.gif"/><?php echo __('Fail-over Rule',true);?>
   		</a>        
   </li>
   
   <li  <?php if($active_tab=='autorate'){ echo "class='active'";  }?>>
   		<a href="<?php echo $this->webroot?>prresource/gatewaygroups/automatic_rate_processing/<?php echo array_keys_value($this->params,'pass.0') ?>/egress?<?php echo $appCommon->get_request_str()?>">
   				<img width="16" height="16" src="<?php echo $this->webroot ?>images/bDR.gif"/><?php echo __('Automatic Rate Processing',true);?>
   		</a>        
   </li>
   
   <li  <?php if($active_tab=='pass_trusk'){ echo "class='active'";  }?>>
   		<a href="<?php echo $this->webroot?>prresource/gatewaygroups/pass_trusk/<?php echo array_keys_value($this->params,'pass.0') ?>/egress?<?php echo $appCommon->get_request_str()?>">
   				<img width="16" height="16" src="<?php echo $this->webroot ?>images/bDR.gif"/><?php echo __('PASS',true);?>
   		</a>        
   </li>
   
   <li  <?php if($active_tab=='sip_profile'){ echo "class='active'";  }?>>
   		<a href="<?php echo $this->webroot?>prresource/gatewaygroups/sip_profile/<?php echo array_keys_value($this->params,'pass.0') ?>/egress?<?php echo $appCommon->get_request_str()?>">
   				<img width="16" height="16" src="<?php echo $this->webroot ?>images/bDR.gif"/><?php echo __('SIP Profile',true);?>
   		</a>        
   </li>
   <li  <?php if($active_tab=='replace_action'){ echo "class='active'";  }?>>
   		<a href="<?php echo $this->webroot?>prresource/gatewaygroups/replace_action/<?php echo array_keys_value($this->params,'pass.0') ?>/egress?<?php echo $appCommon->get_request_str()?>">
   				<img width="16" height="16" src="<?php echo $this->webroot ?>images/bDR.gif"/><?php echo __('Replace Action',true);?>
   		</a>        
   </li>
   
   </ul>
		
		
		
		
		
	