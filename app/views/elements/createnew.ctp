<?php 

	if(!isset($jsAdd) || !$jsAdd){$url=$this->webroot.$url;}
?>
<a class="link_btn" id="add" href="<?php echo $url?>">
    <img width="16" height="16" src="<?php echo $this->webroot?>images/add.png" alt=""/>
  	<?php echo __('createnew',true);?>
</a>
