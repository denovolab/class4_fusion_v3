<div id="title">
  <h1><?php __('System')?>&gt;&gt;<?php echo __('currencyrate')?></h1>
  <?php echo $this->element("search")?>
  <ul id="title-menu">
  <?php if($this->params['hasGet']){?>
  <li>
  	<?php echo $this->element("xback",Array('backUrl'=>'currs/index'))?>
  </li>
  <?php }?>

<?php  if ($_SESSION['role_menu']['Switch']['currs']['model_w']) {?>
  <li>
  	<a class="link_btn" id="add" href="<?php echo $this->webroot?>currs/add">
  	<img width="16" height="16" alt="" src="<?php echo $this->webroot?>images/add.png"> <?php echo __('createnew')?></a>
  </li>

  <?php }?>
  </ul>
</div>
<script type="text/javascript">
jQuery(document).ready(function(){
	jQuery('#add').click(
		function(){
			var action=jQuery(this).attr('href');
			jQuery('table.list').trAdd({
				action:action,
				ajax:'<?php echo $this->webroot?>currs/js_save',
				onsubmit:function(options){
      var re =true
				re= jsAdd.onsubmit(options);
      if(jQuery('#CurrCode').val()!=''){
          var data=jQuery.ajaxData("<?php echo $this->webroot?>currs/check_repeat_name/"+jQuery('#CurrCode').val());
          if(!data.indexOf("false")){
             jQuery.jGrowlError(jQuery('#CurrCode').val()+" is already in use! ");
             re=false;
          					}
       	     	}
      return re;
				}
			});
			return false;
		}
	);
});
</script>


