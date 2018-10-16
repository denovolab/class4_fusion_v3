		
		
		<ul class="tabs">
		   <li      <?php if($active_tab=='base'){ echo "class='active'";  }?>   >
		     <a href="<?php echo $this->webroot?>prresource/gatewaygroups/edit_resouce_ingress/<?php echo array_keys_value($this->params,'pass.0') ?>">
		      <img width="16" height="16" src="<?php echo $this->webroot?>images/list.png"/><?php __('System Information')?>
		     </a>
		    </li>
		    
		     <li      <?php if($active_tab=='lrn_action'){ echo "class='active'";  }?>  >
		     <a href="<?php echo $this->webroot?>prresource/gatewaygroups/add_lrn_action/<?php echo array_keys_value($this->params,'pass.0') ?>">
		      <img width="16" height="16" src="<?php echo $this->webroot?>images/list.png"/><?php echo __('LRN Action',true);?>
		     </a>
		    </li>
<li   <?php if($active_tab=='action'){ echo "class='active'";  }?> >
		     <a href="<?php echo $this->webroot?>prresource/gatewaygroups/add_direction/<?php echo array_keys_value($this->params,'pass.0') ?>/ingress/">
		      <img width="16" height="16" src="<?php echo $this->webroot ?>images/bDR.gif"/><?php __('Action')?>
		     </a>        
		    </li>
		    <li  <?php if($active_tab=='mapping'){ echo "class='active'";  }?>>
		     <a href="<?php echo $this->webroot?>prresource/gatewaygroups/add_translation_time/<?php echo array_keys_value($this->params,'pass.0') ?>/ingress">
		      <img width="16" height="16" src="<?php echo $this->webroot?>images/bDR.gif"/><?php __('DigitMapping')?>
		     </a>
		    </li>
		    <li>
		     <a    <?php if($active_tab=='dis_code'){ echo "class='active'";  }?>   href="<?php echo $this->webroot?>fsconfigs/config_info/<?php echo array_keys_value($this->params,'pass.0') ?>/ingress/">
		      <img width="16" height="16" src="<?php echo $this->webroot?>images/bDR.gif"/>Disconnect Code
		     </a>
		    </li>
		 <li  <?php if($active_tab=='rule'){ echo "class='active'";  }?>>
   		<a href="<?php echo $this->webroot?>prresource/gatewaygroups/add_rule/<?php echo array_keys_value($this->params,'pass.0') ?>/ingress">
   				<img width="16" height="16" src="<?php echo $this->webroot ?>images/bDR.gif"/><?php echo __('Fail-over Rule',true);?>
   		</a>        
   </li>
      <li  <?php if($active_tab=='pass_trusk'){ echo "class='active'";  }?>>
   		<a href="<?php echo $this->webroot?>prresource/gatewaygroups/pass_trusk/<?php echo array_keys_value($this->params,'pass.0') ?>/ingress?<?php echo $appCommon->get_request_str()?>">
   				<img width="16" height="16" src="<?php echo $this->webroot ?>images/bDR.gif"/><?php echo __('PASS',true);?>
   		</a>        
   </li>
   <li  <?php if($active_tab=='replace_action'){ echo "class='active'";  }?>>
   		<a href="<?php echo $this->webroot?>prresource/gatewaygroups/replace_action/<?php echo array_keys_value($this->params,'pass.0') ?>/ingress?<?php echo $appCommon->get_request_str()?>">
   				<img width="16" height="16" src="<?php echo $this->webroot ?>images/bDR.gif"/><?php echo __('Replace Action',true);?>
   		</a>        
   </li>
		</ul>