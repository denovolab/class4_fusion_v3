<?php if (empty($this->data)) {?>
<?php echo $this->element('listEmpty')?>
<?php } else {?>
<div>
<?php echo $this->element("xpage")?>
<table class="list">
<thead>
<tr>
<?php  if ($_SESSION['role_menu']['Configuration']['users']['model_w']) {?>
<td><?php echo __('Active')?></td>
<?php }?>
<td><?php echo $appCommon->show_order('name',__('username',true))?> </td>
<td><?php echo $appCommon->show_order( 'user_type',__('usertype',true))?> </td>
<td> <?php echo __('Role Name',true);?> </td>
<?php if(!isset($n_last_login_time) || !$n_last_login_time){?>
<td><?php echo $appCommon->show_order('last_login_time',__('last_modified',true))?> </td>
 <?php }?>
<?php  if ($_SESSION['role_menu']['Configuration']['users']['model_w']) {?><td  class="last"><?php echo __('action')?></td>
<?php }?>
</tr>
</thead>
<tbody>
<?php foreach ($this->data as $list){?>
	<tr>
    <?php  if ($_SESSION['role_menu']['Configuration']['users']['model_w']) {?>
		<td>
        
			<?php if ($list['User']['active']){?>
                   <a style="width:80%;display:block" href="javascript:void(0)" onclick="inactive(this,'<?php echo $list['User']['user_id']?>','<?php echo $list['User']['name'] ?>');"> 
                   		<img width="16" height="16" title=" <?php echo 'Click to inactive';?>" src="<?php echo $this->webroot?>images/flag-1.png">
                   </a>
        <?php }else{?>
					<a style="width:80%;display:block" href="javascript:void(0)" onclick="active(this,'<?php echo $list['User']['user_id']?>','<?php echo $list['User']['name']?>');"> 
						<img width="16" height="16" title=" <?php echo 'Click to active';?>" src="<?php echo $this->webroot?>images/flag-0.png">
					</a>
			<?php }?>
            
		</td>
        <?php }?>
		<td>
		<a style="width:80%;display:block" title="<?php echo __('edituser')?>" href="<?php echo $this->webroot?>users/add/<?php echo $list['User']['user_id']?>">
			<?php echo array_keys_value($list,'User.name')?>
			</a>
			</td>
			
		<td>
			<?php echo $appUsers->user_type($list)?>
		</td>
		<td>
			<?php echo empty($role[$list['User']['role_id']]) ? '' : $role[$list['User']['role_id']];?>			
			</td>
		<?php if(!isset($n_last_login_time) || !$n_last_login_time){?>
		<td><?php echo array_keys_value($list,'User.last_login_time')?></td>
		<?php }?>
        <?php  if ($_SESSION['role_menu']['Configuration']['users']['model_w']) {?>
		<td class="last">
			<a title="<?php echo __('edituser')?>" href="<?php echo $this->webroot?>users/add/<?php echo $list['User']['user_id']?>">
				<img width="16" height="16" src="<?php echo $this->webroot?>images/editicon.gif">
			</a>
			<a  title="<?php echo __('del')?>"  onclick="return confirm('Are you sure to delete users <?php echo array_keys_value($list,'User.name')?> ? ');" href="<?php echo $this->webroot?>users/del/<?php echo $list['User']['user_id']?>/<?php echo $list['User']['name']?>">
				<img width="16" height="16" src="<?php echo $this->webroot?>images/delete.png">
			</a>
   </td>
   <?php }?>
	</tr>
<?php }?>
</tbody></table>
</div>
<?php echo $this->element("xpage")?>
<?php }?>
<script type="text/javascript">
//启用Reseller
function active(obj,user_id,name){
	if (confirm("Are you sure active the user "+name+"?")) {
		jQuery.get("<?php echo $this->webroot?>users/activeornot?status=true&id="+user_id,function(data){
			if (data.trim() == 'true') {
				obj.getElementsByTagName('img')[0].src = "<?php echo $this->webroot?>images/flag-1.png";
				obj.title = "<?php echo __('disable')?>";
				obj.onclick = function(){inactive(this,user_id,name);};
				jQuery.jGrowl(name+" <?php echo __('is actived successfully')?>",{theme:'jmsg-success'});
			} else {
				jQuery.jGrowl(name +"<?php echo __('is actived unsuccessfully')?>",{theme:'jmsg-alert'});
			}
		});
	}
}

function inactive(obj,user_id,name){
	if (confirm("Are you sure inactive the user "+name+ "?")) {
		jQuery.get("<?php echo $this->webroot?>users/activeornot?status=false&id="+user_id,function(data){
			if (data.trim() == 'true') {
				obj.getElementsByTagName('img')[0].src = "<?php echo $this->webroot?>images/flag-0.png";
				obj.title = "<?php echo __('active')?>";
				obj.onclick = function(){active(this,user_id,name);};
				jQuery.jGrowl(name+" <?php echo __('is inactived successfully')?>",{theme:'jmsg-success'});
			} else {
				jQuery.jGrowl(name + "<?php echo __('is inactived unsuccessfully')?>",{theme:'jmsg-alert'});
			}
		});
	}
}
</script>
