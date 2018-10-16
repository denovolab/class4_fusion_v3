<div id="title">
  <h1><?php __('Configuration')?>&gt;&gt;<?php echo __('Manage Registration')?>
  
  </h1>
	<?php //echo $this->element('search')?>
	<ul id="title-menu">
	
			<!--<li>
			<a href="<?php echo $this->webroot?>users/add_carrier_user">
				<img width="16" height="16" src="<?php echo $this->webroot?>images/add.png" alt=""/>Create  Carrrier  User
			</a>
      	</li>
	
	
		<li>
			<a href="<?php echo $this->webroot?>users/add">
				<img width="16" height="16" src="<?php echo $this->webroot?>images/add.png" alt=""/>Create Users
			</a>
      	</li>-->		
      	<li>  <!--onsubmit="loading();"-->
            <form action="" method="get" >
                
                <span style="float:left; height:25px; line-height:25px; font-size:14px; color:#EEEEEE; font-weight:bold;">Output:</span>
                    <select name="out_put" style="width:100px;" class="in-select select" id="output">
                        <option value="web">Web</option>
                        <option value="csv">Excel CSV</option>
                        <option value="xls">Excel XLS</option>
                    </select>
                
                <select name="user_type">
                    <option value="all"  <?php echo (isset($_GET['user_type']) && $_GET['user_type'] == 'all') ? 'selected=selected' : '';?>>All</option>
                    <option value="1" <?php echo (empty($_GET['user_type']) || $_GET['user_type'] == 1) ? 'selected=selected' : '';?>>New</option>
                    <option value="2" <?php echo (!empty($_GET['user_type']) && $_GET['user_type'] == 2) ? 'selected=selected' : '';?>>Hold</option>
                    <option value="3" <?php echo (!empty($_GET['user_type']) && $_GET['user_type'] == 3) ? 'selected=selected' : '';?>>Accept</option>
                			 <option value="4" <?php echo (!empty($_GET['user_type']) && $_GET['user_type'] == 4) ? 'selected=selected' : '';?>>Mail Validated</option>
                
                </select>
                <input type="text" name="search" value="<?php echo empty($_GET['search']) ? '' : $_GET['search'];?>" title="Search" class="in-search input in-text defaultText in-input"/>
                <input type="submit" value="Query" class="input in-submit query_btn"/>
			</form>
			</li>
	</ul>
</div>

<div id="container">
<?php $mydata =$p->getDataArray();
			$loop = count($mydata); 
			if(empty($mydata)){
			?>
<div class="msg"><?php echo __('no_data_found',true);?></div>
<?php }else{
?>
<div>
<?php echo $this->element("page")?>

<table class="list">
<col width="14%"></col>
<col width="14%"></col>
<col width="14%"></col>
<col width="14%"></col>
<col width="14%"></col>
<col width="14%"></col>
<col width="14%"></col>
<thead>
<tr>

<td><?php echo __('Company Name',true);?></td>
<td><?php echo __('Corporate Contact Name',true);?></td>
<td><?php echo __('Corporate Contact Phone',true);?></td>
<td><?php echo __('Corporate Email',true);?></td>
<td><?php echo $appCommon->show_order('name',__('name',true))?> </td>
<td><?php echo __('status',true);?></td>
<td><?php echo __('Login',true);?></td>
<?php  if ($_SESSION['role_menu']['Exchange Manage']['users:registration']['model_w']) {?>
<td  class="last"><?php echo __('action')?></td>
<?php }?>
</tr>
</thead>
<tbody>
<?php 
$status_arr = array(1=>'New', 2=>'Hold', 3=>'Accepted', 4=>'Mail Validated');
for ($i=0;$i<$loop;$i++){
?>
	<tr>
	
		<td><?php echo $mydata[$i][0]['company_name'];?></td>
		<td><?php echo $mydata[$i][0]['corporate_contact_name'];?></td>
		<td><?php echo $mydata[$i][0]['corporate_contact_phone'];?></td>
		<td><?php echo $mydata[$i][0]['corporate_contact_email'];?></td>
		<td>
		<?php  if ($_SESSION['role_menu']['Exchange Manage']['users:registration']['model_w']) {?>
        <a style="width:80%;display:block" title="<?php echo __('edituser')?>" 

href="<?php echo $this->webroot?>users/view_orderuser/<?php echo 

$mydata[$i][0]['id']?>">
			<?php echo array_keys_value($mydata[$i][0],'name')?>
			</a>
            <?php }else{?><?php echo array_keys_value($mydata[$i][0],'name');}?>
		</td>
		<td>
        <?php
		if (!empty($mydata[$i][0]['status']))
		{
			echo $status_arr[$mydata[$i][0]['status']];
		}
		/*
		if($mydata[$i][0]['status']==1){
			echo $status_arr[1];
		}
		else if($mydata[$i][0]['status']==2){
			echo $status_arr[2];
		}else{
			echo $status_arr[3];
		}
		*/
		/*
		if (!empty($mydata[$i][0]['client_status']))
		{
			echo $status_arr[$mydata[$i][0]['status']];
		}*/
		
		?>

		</td>
                <td>
                    <?php if($mydata[$i][0]['status'] != 0): ?>
                    <a title="Disable" href="<?php echo $this->webroot; ?>users/changestatus/<?php echo $mydata[$i][0]['id'];?>/0">
                        <img src="<?php echo $this->webroot ?>images/flag-1.png" />
                    </a>
                    <?php else: ?>
                    <a title="Enable" href="<?php echo $this->webroot; ?>users/changestatus/<?php echo $mydata[$i][0]['id'];?>/1">
                        <img src="<?php echo $this->webroot ?>images/flag-0.png" />
                    </a>
                    <?php endif; ?>
                </td>
		<?php  if ($_SESSION['role_menu']['Exchange Manage']['users:registration']['model_w']) {?>
		
		<td class="last">
        
        <?php
        	if($mydata[$i][0]['status']==1){
		?>
        <!--
			<a title="<?php echo __('accept user')?>" href="<?php echo $this->webroot?>clients/add/?order_user_id=<?php echo $mydata[$i][0]['id'];?>">
				<img width="16" height="16" src="<?php echo $this->webroot?>images/editicon.gif">
			</a>
        -->
		<?php }?>
        
        <?php
        	if($mydata[$i][0]['status']==4){
		?>
			<a title="<?php echo __('accept user')?>" href="<?php echo $this->webroot?>clients/add/?order_user_id=<?php echo $mydata[$i][0]['id'];?>">
				<img width="16" height="16" src="<?php echo $this->webroot?>images/editicon.gif">
			</a>
		<?php }?> 
		<?php
                //$mydata[$i][0]['status']==3
                if (true){?>
        <a title="Reset Password" href="<?php echo $this->webroot?>users/reset_password/<?php echo $mydata[$i][0]['id'];?>">
				<img width="16" height="16" src="<?php echo $this->webroot?>images/menuIcon_014.gif">
			</a>
        <a href="javascript:void(0)" onclick="inactive(this,'<?php echo $mydata[$i][0]['id']?>');"> 
                   		<img width="16" height="16" title=" <?php echo __('Change To Hold')?>" src="<?php echo $this->webroot?>images/flag-1.png">
                   </a>
            <?php }?>
            <?php if ($mydata[$i][0]['status']==2){?>
            
            <a href="javascript:void(0)" onclick="active(this,'<?php echo $mydata[$i][0]['id']?>');"> 
                <img width="16" height="16" title=" <?php echo __('Change To Accept')?>" src="<?php echo $this->webroot?>images/flag-0.png">
            </a>
			<?php }?>
			
			<a  title="<?php echo __('del')?>"  onclick="return confirm('Are you sure to delete users <?php echo array_keys_value($mydata[$i][0],'name')?> ? ');" href="<?php echo $this->webroot?>users/del_order_user/<?php echo $mydata[$i][0]['id']?>/<?php echo $mydata[$i][0]['name']?>">
				<img width="16" height="16" src="<?php echo $this->webroot?>images/delete.png">
			</a>
   </td>
 <?php }?>
	</tr>
<?php }?>
</tbody></table>
</div>
<?php echo $this->element("page")?>
<?php }?>
<script type="text/javascript">
//启用Reseller
function active(obj,user_id){
	if (confirm("<?php echo __('Are you sure to change this user status to accept?') ?>")) {
		jQuery.get("<?php echo $this->webroot?>users/holdornot?status=3&id="+user_id,function(data){
			if (data.trim()  != null) {
				obj.getElementsByTagName('img')[0].src = "<?php echo $this->webroot?>images/flag-1.png";
				obj.title = "<?php echo __('change to hold')?>";
				obj.onclick = function(){inactive(this,user_id);};
				window.location.reload();
				jQuery.jGrowl("<?php echo __('Change User Status to accept success')?>",{theme:'jmsg-success'});
				
			} else {
				jQuery.jGrowl("<?php echo __('activefailed')?>",{theme:'jmsg-alert'});
			}
		});
	}
}
function inactive(obj,user_id){
	if (confirm("<?php echo __('Are you sure you to change this user status to hold?') ?>")) {
		jQuery.get("<?php echo $this->webroot?>users/holdornot?status=2&id="+user_id,function(data){
			if (data.trim()  != null) {
				obj.getElementsByTagName('img')[0].src = "<?php echo $this->webroot?>images/flag-0.png";
				obj.title = "<?php echo __('Change User Status to Hold success')?>";
				obj.onclick = function(){active(this,user_id);};
				window.location.reload();
				jQuery.jGrowl("<?php echo __('Hold user success')?>",{theme:'jmsg-success'});
			} else {
				jQuery.jGrowl("<?php echo __('inactivefailed')?>",{theme:'jmsg-alert'});
			}
		});
	}
}
</script>

</div>
	