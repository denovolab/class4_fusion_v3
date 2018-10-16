<div id="title">
  <h1>
    <?php __('Configuration')?>
    &gt;&gt;<?php echo __('Modules',true);?></h1>
  <ul id="title-menu">
  <?php  if ($_SESSION['role_menu']['Configuration']['sysmodules']['model_w']) {?>
    <li> <a class="link_btn" title="<?php echo __('createroletitle')?>"  href="<?php echo $this->webroot?>sysmodules/add_sysmodule"> <img width="16" height="16" alt="" src="<?php echo $this->webroot?>images/add.png"><?php echo __('createnew')?> </a> </li>
    <?php }?>
  </ul>
</div>
<div id="container">
  <?php 			$mydata =$p->getDataArray();
			$loop = count($mydata); 
			if(empty($mydata)){
			?>
  <div class="msg"><?php echo __('no_data_found',true);?></div>
  <?php }else{

?>
  <div id="toppage"></div>
  <table class="list">

    <thead>
      <tr>
        <td ><?php echo __('Module Name',true);?></td>
        <td class="last"><?php echo __('action')?></td>
      </tr>
    </thead>
    <tbody>
      <?php 

			for ($i=0;$i<$loop;$i++){
		?>
      <tr class="row-1">
        <td align="center"><a title="<?php echo "View sub-module"?>"  href="<?php echo $this->webroot?>syspris/view_syspri/<?php echo $mydata[$i][0]['id']?>"><?php echo $mydata[$i][0]['module_name']?> </a></td>
        
        <td class="last">
		<?php  if ($_SESSION['role_menu']['Configuration']['sysmodules']['model_w']) {?>
          <a title="<?php echo __('editmodule')?>"  href="<?php echo $this->webroot?>sysmodules/edit_sysmodule/<?php echo $mydata[$i][0]['id']?>"> <img width="16" height="16" src="<?php echo $this->webroot?>images/editicon.gif"> </a>
          
          <a title="<?php echo __('del')?>" onClick="return confirm('Are you sure to delete,this module <?php echo $mydata[$i][0]['module_name'] ?> ? ');" href="<?php echo $this->webroot?>sysmodules/del/<?php echo $mydata[$i][0]['id']?>/<?php echo $mydata[$i][0]['module_name']?>"> <img width="16" height="16" src="<?php echo $this->webroot?>images/delete.png"> </a>
          <?php }?>
			<a title="<?php echo "View sub-module"?>"  href="<?php echo $this->webroot?>syspris/view_syspri/<?php echo $mydata[$i][0]['id']?>"> <img width="16" height="16" src="<?php echo $this->webroot?>images/bLogins.gif"> </a>
          </td>
      </tr>
      <?php }?>
    </tbody>
  </table>
</div>
<div>
  <div id="tmppage"> <?php echo $this->element('page');?> </div>
  <?php }?>
</div>
