<?php echo $this->element("users/last_title")?>
<div id="container">
<?php echo $this->element('tabs',Array('tabs'=>Array('System Users'=>Array('url'=>'users/index'),'Carrier Users'=>Array('url'=>'users/show_carrier'),'Online Users'=>Array('url'=>'users/show_online'),'Never Login Users'=>Array('url'=>'users/view'),'Ever Login Users'=>Array('url'=>'users/last_login','active'=>true))))?>
<?php echo $this->element('users/search')?>
<?php echo $this->element('users/list')?>
</div>
<?php return ?>
