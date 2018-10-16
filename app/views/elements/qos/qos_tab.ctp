	<ul class="tabs">
		<li  <?php if($active_tab=='global'){ echo "class='active'";  }?> >
				<a href="<?php echo $this->webroot?>monitorsreports/globalstats">
						<img width="16" height="16" src="<?php echo $this->webroot?>images/list.png"><?php echo __('globlestatus')?>
				</a>
		</li>
		<li  <?php if($active_tab=='product'){ echo "class='active'";  }?>>
			<a href="<?php echo $this->webroot?>monitorsreports/productstats">
				<img width="16" height="16" src="<?php echo $this->webroot?>images/bDR.gif"><?php echo __('routestatus')?>
			</a>  
		</li>
		<li  <?php if($active_tab=='ingress'){ echo "class='active'";  }?>>
			<a href="<?php echo $this->webroot?>monitorsreports/carrier/ingress">
				<img width="16" height="16" src="<?php echo $this->webroot?>images/client_stat1.png"><?php echo __('Ingress Carrier',true);?>
			</a>
		</li>
		<li   <?php if($active_tab=='egress'){ echo "class='active'";  }?>>
			<a href="<?php echo $this->webroot?>monitorsreports/carrier/egress">
				<img width="16" height="16" src="<?php echo $this->webroot?>images/client_stat2.png"><?php echo __('Egress Carrier',true);?>
			</a>
		</li>
	</ul>