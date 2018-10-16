<div id="title">
  <h1><?php __('Configuration')?>&gt;&gt;<?php echo __('usermanager')?>
  
  </h1>
	<?php echo $this->element('search')?>
	<ul id="title-menu">
	<?php  if ($_SESSION['role_menu']['Configuration']['users']['model_w']) {?>
<!--			<li>
			<a class="link_btn" href="<?php echo $this->webroot?>users/add_carrier_user">
				<img width="16" height="16" src="<?php echo $this->webroot?>images/add.png" alt=""/><?php echo __('Create Carrrier User',true);?>
			</a>
      	</li>
	-->
	
		<li>
			<a  class="link_btn"href="<?php echo $this->webroot?>users/add">
				<img width="16" height="16" src="<?php echo $this->webroot?>images/add.png" alt=""/><?php echo __('Create Users',true);?>
			</a>
      	</li>
        <?php }?>
		<?php if($this->params['hasGet']){?>
    	<li>
    		<a class="link_back" href="<?php echo $this->webroot?>roles/view">
    			<img width="16" height="16"  alt="" src="<?php echo $this->webroot?>images/icon_back_white.png">&nbsp;<?php echo __('gobackall')?>
    		</a>
    	</li>
    <?php }?>
	</ul>
</div>