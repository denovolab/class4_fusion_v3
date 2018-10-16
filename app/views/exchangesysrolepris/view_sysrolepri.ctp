<script src="<?php echo $this->webroot?>js/ajaxTable.js" type="text/javascript"></script>
<div id="title">
   <h1><?php __('Exchange Manage')?>&gt;&gt;<?php echo __('Roles')?></h1>  
		<ul id="title-search">
        	<li>
        		<?php //Pr($searchkey);    //****************************模糊搜索**************************?>
                        <form  action="<?php echo $this->webroot?>exchangesysrolepris/view_sysrolepri/<?php echo $type;?>"  method="get">
        			<input type="text" id="search-_q_j" class="in-search default-value input in-text defaultText" title="<?php echo __('search')?>..." value="<?php if(isset($searchkey)){echo $searchkey;}else{ echo __('search');}?>"  onclick="this.value=''" name="searchkey">
                                <input type="submit" id="submit" class="search_submit input in-submit" value="" name="submit">
        		</form>
        	</li>
       </ul>
   
 
       <ul id="title-menu">
        	<?php if (isset($edit_return)) {?>
        	<li>
                    <a class="link_back" href="<?php echo $this->webroot?>exchangesysrolepris/view_sysrolepri/<?php echo $type;?>">
    				<img width="16" height="16"  alt="" src="<?php echo $this->webroot?>images/icon_back_white.png">
    				&nbsp;<?php echo __('gobackall')?>
    			</a>
    		</li>
        	<?php }?>
            <?php  if ($_SESSION['role_menu']['Exchange Manage']['exchangesysrolepris:sysrolepris']['model_w']) {?>
        	<li>
                    <a class="link_btn" title="<?php echo __('createroletitle')?>"  href="<?php echo $this->webroot?>exchangesysrolepris/add_sysrolepri/<?php echo $type?>">
       				<img width="16" height="16" alt="" src="<?php echo $this->webroot?>images/add.png"><?php echo __('createnew')?>
       			</a>
       		</li>
            <?php }?>
       	</ul>
    </div>
<div id="container">

<?php 
    if($type == 'agent'){
        echo $this->element('tabs',Array('tabs'=>Array('Agent Role'=>Array('url'=>'exchangesysrolepris/view_sysrolepri/agent','active'=>true),'Partition Role'=>Array('url'=>'exchangesysrolepris/view_sysrolepri/partition'),'Exchange Role'=>Array('url'=>'exchangesysrolepris/view_sysrolepri/exchange') )));
    }else if($type == 'partition'){
        echo $this->element('tabs',Array('tabs'=>Array('Agent Role'=>Array('url'=>'exchangesysrolepris/view_sysrolepri/agent'),'Partition Role'=>Array('url'=>'exchangesysrolepris/view_sysrolepri/partition','active'=>true),'Exchange Role'=>Array('url'=>'exchangesysrolepris/view_sysrolepri/exchange') )));
    }else if($type == 'exchange'){
        echo $this->element('tabs',Array('tabs'=>Array('Agent Role'=>Array('url'=>'exchangesysrolepris/view_sysrolepri/agent'),'Partition Role'=>Array('url'=>'exchangesysrolepris/view_sysrolepri/partition'),'Exchange Role'=>Array('url'=>'exchangesysrolepris/view_sysrolepri/exchange','active'=>true) )));
    }
?>



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
 			<td ><?php echo $appCommon->show_order('role_name',__('RolesName',true))?> </td>
		<!-- <td > <?php echo $appCommon->show_order('active',__('Rellerusable',true)) ?>  </td>
        -->
		 <td > <?php echo $appCommon->show_order('role_cnt',__('usercount',true)) ?>	</td>
		    <?php  if ($_SESSION['role_menu']['Exchange Manage']['exchangesysrolepris:sysrolepris']['model_w']) {?><td  class="last"><?php echo __('action')?></td><?php }?>
		</tr>
	</thead>
	<tbody>
		<?php 

			for ($i=0;$i<$loop;$i++){
		?>
		<tr class="row-1">
		    <td align="center">
			   <a style="width:80%;display:block" title="<?php __('viewfunc')?>" href="<?php echo $this->webroot?>exchangesysrolepris/add_sysrolepri/<?php echo $type.'/'.$mydata[$i][0]['role_id']?>" class="link_width">
						<?php echo $mydata[$i][0]['role_name']?>
					</a>
				</td>
		  <!--
            <td>
		    <?php
				if(!empty($mydata[$i][0]['active'])){
					echo 'active';
				}else{
					echo  'disable';
				}
		    ?>
		</td>
        -->
		<td>
		    <?php
				if(empty($mydata[$i][0]['role_users'])){
					echo 0;
				}else{
					echo $mydata[$i][0]['role_users'];
				}
			?>
		</td>
<?php  if ($_SESSION['role_menu']['Exchange Manage']['exchangesysrolepris:sysrolepris']['model_w']) {?>
		      <td class="last"  >		      
		  
		      	<a   title="<?php echo __('editrole')?>"  href="<?php echo $this->webroot?>exchangesysrolepris/add_sysrolepri/<?php echo $type.'/'.$mydata[$i][0]['role_id']?>">
		      		<img width="16" height="16" src="<?php echo $this->webroot?>images/editicon.gif">
                    
		      	</a>
		       <?php if(empty($mydata[$i][0]['role_users'])){?>
		        <a title="<?php echo __('del')?>" onclick="return confirm('Are you sure to delete, roles <?php echo $mydata[$i][0]['role_name'] ?> ? ');" href="<?php echo $this->webroot?>exchangesysrolepris/del/<?php echo $mydata[$i][0]['role_id']?>/<?php echo $mydata[$i][0]['role_name']?>/<?php echo $type;?>">
					    <img width="16" height="16" src="<?php echo $this->webroot?>images/delete.png">
				     </a>
					<?php }else if($mydata[$i][0]['role_users'] >= 1){ ?>
				
                                        <a  onclick="return alert('<?php echo __('Are you sure to delete, roles '.$mydata[$i][0]['role_name'].'')?>');" href="<?php echo $this->webroot?>exchangesysrolepris/del/<?php echo $mydata[$i][0]['role_id']?>/<?php echo $mydata[$i][0]['role_name']?>/<?php echo $type;?>">
					    <img width="16" height="16" src="<?php echo $this->webroot?>images/delete.png">
				  </a>
				<?php }else if($mydata[$i][0]['role_users']>1){?>
				
				<?php }?>
		       
		      </td><?php }?>
		</tr>
			<?php }?>
		</tbody>
		</table>
	</div>
<div>
<div id="tmppage">

<?php echo $this->element('page');?>
</div>
<?php }?>
</div>

