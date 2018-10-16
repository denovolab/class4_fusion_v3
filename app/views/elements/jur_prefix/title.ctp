    <script type="text/javascript" src="<?php echo $this->webroot?>js/jquery_002.js"></script>

<div id="title">
  <h1> <?php __('System')?>&gt;&gt;<?php echo __('Jurisdiction',true);?>  </h1>
		<ul id="title-search">
			<li>
				<form method="get" action="">
					<input type="text" name="search" onclick="this.value=''" value="Search" title="Search" class="in-search default-value input in-text defaultText" id="search-_qs">
				</form>
			</li>
			<!--<li style="display: list-item;" id="title-search-adv" onclick="advSearchToggle();" title="advanced search »" class=""></li>-->
		</ul>
		<ul id="title-menu">
        <?php  if ($_SESSION['role_menu']['Switch']['jurisdictionprefixs']['model_w']) {?>
			<li>
				<?php echo $this->element("createnew",Array('url'=>'javascript:addItem()','jsAdd'=>true))?>
			</li>
			 <li>
      <a class="link_btn"onclick="deleteAll('<?php echo $this->webroot ?>jurisdictionprefixs/del_all_jur');" href="javascript:void(0)" rel="popup"><img width="16" height="16" src="<?php echo $this->webroot ?>images/delete.png" alt=""> <?php echo __('deleteall',true);?></a>
			</li>
			<li>
		  	<a class="link_btn" onclick="deleteSelected('tabid','<?php echo $this->webroot ?>jurisdictionprefixs/del_selected_jur','jurisdiction');" href="javascript:void(0)" rel="popup"><img width="16" height="16" src="<?php echo $this->webroot ?>images/delete.png" alt=""> <?php echo __('Delete Selected',true);?></a>
			</li>
			<?php }?>
		</ul>
</div>