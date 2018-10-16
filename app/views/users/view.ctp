<?php echo $this->element("users/current_title")?>
<div id="container">
<?php echo $this->element('tabs',Array('tabs'=>Array('System Users'=>Array('url'=>'users/index'),'Carrier Users'=>Array('url'=>'users/show_carrier'),'Online Users'=>Array('url'=>'users/show_online'),'Never Login Users'=>Array('url'=>'users/view','active'=>true),'Ever Login Users'=>Array('url'=>'users/last_login'))))?>
<?php echo $this->element('users/search')?>
<?php echo $this->element('users/list',Array('n_last_login_time'=>true))?>
</div>
	