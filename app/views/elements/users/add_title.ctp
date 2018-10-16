<div id="title">
   <h1><?php echo __('User',true);?>&gt;&gt;<?php echo __('Create User',true);?>
         <font  class="editname" title="Name">   <?php echo empty($name)||$name==''?'':"[".$name."]" ?></font>
   
   </h1>
   <ul id="title-menu">
		<li>
			<?php echo $this->element('xback',Array('backUrl'=>'users/index'))?>
		</li>
  	</ul>
</div>