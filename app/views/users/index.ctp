<?php echo $this->element("users/title")?>
<div id="container">
<?php echo $this->element('tabs',Array('tabs'=>Array('System Users'=>Array('url'=>'users/index','active'=>true),'Carrier Users'=>Array('url'=>'users/show_carrier'),'Online Users'=>Array('url'=>'users/show_online'),'Never Login Users'=>Array('url'=>'users/view'),'Ever Login Users'=>Array('url'=>'users/last_login'))))?>
<?php echo $this->element('users/search')?>
<?php echo $this->element('users/list')?>
</div>
	