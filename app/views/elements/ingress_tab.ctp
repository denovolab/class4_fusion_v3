		
		
		<ul class="tabs">
		   <li      <?php if($active_tab=='base'){ echo "class='active'";  }?>   >
		     <a href="<?php echo $this->webroot?>prresource/gatewaygroups/edit_resouce_ingress/<?php echo array_keys_value($this->params,'pass.0') ?>?resource_id=<?php echo array_keys_value($this->params,'pass.0') ?>">
		      <img width="16" height="16" src="<?php echo $this->webroot?>images/list.png"/><?php __('System Information')?>
		     </a>
		    </li>
		    
		    
		    
		     <li     <?php if($active_tab=='lrn_action'){ echo "class='active'";  }?>    >
		     <a href="<?php echo $this->webroot?>prresource/gatewaygroups/add_lrn_action/<?php echo array_keys_value($this->params,'pass.0') ?>?resource_id=<?php echo array_keys_value($this->params,'pass.0') ?>">
		      <img width="16" height="16" src="<?php echo $this->webroot?>images/list.png"/><?php echo __('LRN Action',true);?>
		     </a>
		    </li>
		    
		    
		    
		    <!--
		    <li>
		     <a href="<?php echo $this->webroot?>prresource/gatewaygroups/add_host/<?php echo array_keys_value($this->params,'pass.0') ?>?gress=ingress&<?php echo $this->params['getUrl'] ?>"> 
		     		<img width="16" height="16" src="<?php echo $this->webroot ?>images/menuIcon.gif"/>Host
		     </a>  
		    </li>
		    --><li>
		     <a href="<?php echo $this->webroot?>prresource/gatewaygroups/add_direction/<?php echo array_keys_value($this->params,'pass.0') ?>?gress=ingress&resource_id=<?php echo array_keys_value($this->params,'pass.0') ?>">
		      <img width="16" height="16" src="<?php echo $this->webroot ?>images/bDR.gif"/><?php __('Action')?>
		     </a>        
		    </li>
		    <li>
		     <a href="<?php echo $this->webroot?>prresource/gatewaygroups/add_translation_time/<?php echo array_keys_value($this->params,'pass.0') ?>?gress=ingress&resource_id=<?php echo array_keys_value($this->params,'pass.0') ?>">
		      <img width="16" height="16" src="<?php echo $this->webroot?>images/bDR.gif"/><?php __('DigitMapping')?>
		     </a>
		    </li>
		    <li   <?php if($active_tab=='dis_code'){ echo "class='active'";  }?>>
		     <a      href="<?php echo $this->webroot?>fsconfigs/config_info/<?php echo array_keys_value($this->params,'pass.0') ?>?gress=ingress&resource_id=<?php echo array_keys_value($this->params,'pass.0') ?>">
		      <img width="16" height="16" src="<?php echo $this->webroot?>images/bDR.gif"/><?php echo __('Disconnect Code',true);?>
		     </a>
		    </li>
      <li  <?php if($active_tab=='rule'){ echo "class='active'";  }?>>
   		<a href="<?php echo $this->webroot?>prresource/gatewaygroups/add_rule/<?php echo array_keys_value($this->params,'pass.0') ?>/ingress">
   				<img width="16" height="16" src="<?php echo $this->webroot ?>images/bDR.gif"/><?php echo __('Fail-over Rule',true);?>
   		</a>        
   </li>
		</ul>