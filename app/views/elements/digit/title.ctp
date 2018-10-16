    <script type="text/javascript" src="<?php echo $this->webroot?>js/jquery_002.js"></script>
    <script type="text/javascript" src="<?php echo $this->webroot?>js/jquery.js"></script>
    <script type="text/javascript" src="<?php echo $this->webroot?>js/bb-functions.js"></script>
    <script type="text/javascript" src="<?php echo $this->webroot?>js/bb-interface.js"></script>
<div id="title">
  <h1><?php echo __('Routing',true);?>&gt;&gt;<?php echo __('Digit Mapping',true);?></h1>
		<ul id="title-search">
			<li>
				<form method="get" action="">
					<input type="text" name="search" onclick="this.value=''" value="Search" title="Search" class="in-search default-value input in-text defaultText" id="search-_q">
				</form>
			</li>
		</ul>
		<ul id="title-menu">
        <?php  if ($_SESSION['role_menu']['Routing']['digits']['model_w']) {?>
			<li>
				<a class="link_btn" id="add" href="#"/>
					<img width="16" height="16" src="<?php echo $this->webroot?>images/add.png" alt=""><?php echo __('createnew',true);?>
				</a>
			</li>
            <?php }?>
		</ul>
</div>