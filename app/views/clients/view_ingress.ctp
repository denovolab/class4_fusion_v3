<script src="<?php echo $this->webroot?>js/ajaxTable.js" type="text/javascript"></script>
  <style type="text/css">
.list {
font-size:1em;
background:url("../images/list-row-1.png") repeat-x scroll center bottom #FDFDFD;
height:37px;
width:100%;
border:0px solid #809DBA;
margin:0 auto 0px;
border-collapse:collapse;
}

.list tbody td {
border-right:1px solid #E3E5E6;
border-left:1px solid #809DBA;
line-height:1.6;
padding:1px 4px;
}
</style>
<?php $w = $session->read('writable');?>
<div id="title">
            <h1>
        <?php __('Routing')?>&gt;&gt;
      <?php echo __('ingress')?>     
 </h1>
    
<ul id="title-search">
        <li>
        <form  action=""  method="get">
        <input type="text" id="search-_q" class="in-search default-value input in-text defaultText" title="<?php echo __('search')?>..." 
        value="<?php if(isset($searchkey)){echo $searchkey;}else{ echo __('pleaseinputkey');}?>"  onclick="this.value=''" name="search">
        </form>
        </li>
        <li title="<?php echo __('advancedsearch')?> »" onclick="advSearchToggle();" id="title-search-adv" style="display: list-item;"></li>
    </ul>
        <ul id="title-menu">
<?php if ($w == true) {?><li><a class="link_btn" href="<?php echo $this->webroot?>/gatewaygroups/download_ingress/"><img width="10" height="5" alt="" src="<?php echo $this->webroot?>images/export.png"><?php __('download')?></a></li>
<li><a class="link_btn" href="<?php echo $this->webroot?>gatewaygroups/add_resouce_ingress">
       <img width="16" height="16" alt="" src="<?php echo $this->webroot?>images/add.png"><?php echo __('addvoipgateway')?></a></li><?php }?>
        <li><a class="link_btn" rel="popup" href="javascript:void(0)" onclick="deleteSelected('container','<?php echo $this->webroot?>/gatewaygroups/del_selected?type=view_ingress');">
        		<img width="16" height="16" alt="" src="<?php echo $this->webroot?>images/delete.png"> <?php echo __('deleteselected')?></a></li>
       <li>
    			<a  class="link_back"href="histoy.go(-1)">
    				<img width="16" height="16" src="<?php echo $this->webroot?>images/icon_back_white.png" alt="">
    				&nbsp;<?php echo __('goback',true);?>    			</a>
    		</li>
        </ul>
    </div>
	<div id="container">
		<div id="cover"></div>
	<dl id="edit_ip" class="tooltip-styled" style="display:none;position:absolute;left:40%;top:30%;z-idnex:99;width:300px;height:auto;">
<dd style="text-align:center;width:100%;height:25px;font-size: 16px;"><?php echo __('register')?></dd>
    <dd style="margin-top:10px;">
    			<span>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo __('username')?></span>:<input id="ip_username" name="ip_username" style="height:20px;width:200px;float:right">
    			<input id="ip_id" style="display:none"/>
    </dd>
    
    <dd style="margin-top:20px;">
    			<span>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo __('password')?></span>:<input id="ip_pass" name="ip_pass" style="height:20px;width:200px;float:right">
    </dd>
	<dd style="margin-top:10px; margin-left:26%;width:150px;height:auto;">
		<input type="button" onclick="updateIp();" value="<?php echo __('submit')?>" class="input in-button">
		<input type="button" onclick="closeCover('edit_ip');" value="<?php echo __('cancel')?>" class="input in-button">
	</dd>
</dl>
	

<ul class="tabs">
 <li  ><a href="<?php echo $this->webroot ?>clients/edit/<?php echo $gate_client_id?>"><img width="16" height="16" src="<?php echo $this->webroot?>images/bDR.gif"/> <?php __('basicinfo')?></a></li>   
   
    <li ><a href="<?php echo $this->webroot?>clients/view_egress/<?php echo $gate_client_id?>"><img width="16" height="16" src="<?php echo $this->webroot?>images/menuIcon.gif"/> <?php __('egress')?></a></li> 
       <li   class="active"><a href="<?php echo $this->webroot?>clients/view_ingress/<?php echo $gate_client_id?>"><img width="16" height="16" src="<?php echo $this->webroot?>images/bAccounts.gif"/> <?php __('ingress')?></a></li>

  </ul>

<?php $d = $p->getDataArray();if (count($d) == 0) {?>
<div class="msg"><?php echo __('no_data_found')?></div>
<?php } else {?>
<fieldset style="width: 100%; display: block;" id="advsearch" class="title-block">
	 <form method="get" action="">
<table style="width:auto;">
<tbody>
<tr>
    <td><label><?php echo __('Trunk Name',true);?>:</label>
    
     <input type="text" id="name" value="" name="name" class="input in-text"><input type="hidden" id="query-id_clients" value="" name="query[id_clients]" class="input in-hidden">
  </td>
    <td>
    		<label><?php echo __('Trunk ID',true);?><input type="hidden" id="query-id_clients" value="" name="query[id_clients]" class="input in-hidden"> :</label>
				<input  name="id" value="" id="id" type="text" class="input in-text">    </td>
    <td class="buttons"><input type="submit" class="input in-submit" value="Search"></td>
</tr>

</tbody></table>
</form></fieldset>





	<div id="toppage"></div>	
	<?php //*********************查询条件********************************?>

	<div style="height:10px"></div>
<?php //*********************表格头*************************************?>
		<div>	
			<table class="list"  style="border:1px solid #809DBA;height: 14px;">
			<col style="width: 3%;">
				<col style="width: 5.5%;">
				<col style="width: 5.5%;">
			
				<col style="width: 10%;">
				<col style="width: 10%;">
				<col style="width: 9.4%;">
				<col style="width: 9.4%;">
				<col style="width: 9.4%;">

				<col style="width: 7.5%;">
	<col style="width: 3.5%;">
				<col style="width: 12.5%;">

			<thead>
				<tr>
				<td><input type="checkbox" onclick="checkAll(this,'container');" value=""/></td>
		 			<td  >
    			<?php echo __('host_ip')?>&nbsp;</td>

    				    							<td>	
    		<a onclick="my_sort('resource.resource_id','asc')" href="javascript:void(0)"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>						
    							
    							<?php echo __('id',true);?>
    								<a onclick="my_sort('resource.resource_id','desc')" href="javascript:void(0)"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a>
    							</td>
    			<td>	<?php echo __('alias',true);?></td>
  <td ><?php echo __('gatewayname')?></td>
    
    <td>
        		<a onclick="my_sort('resource.capacity','asc')" href="javascript:void(0)"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>						
    					
    <?php echo __('calllimit')?>
        		<a onclick="my_sort('resource.capacity','desc')" href="javascript:void(0)"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a>						
    					</td>
    
    <td>
    		<a onclick="my_sort('resource.cps_limit','asc')" href="javascript:void(0)"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>						
    					
  <?php echo __('cps')?>
  		<a onclick="my_sort('resource.cps_limit','asc')" href="javascript:void(0)"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a>						
    			</td>
    
   
          <td>
    <?php echo __('active')?></td>
   
             <td>
              		<a onclick="my_sort('a.ip_cnt','asc')" href="javascript:void(0)"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>	
    <?php echo __('ofingress')?>
     		<a onclick="my_sort('a.ip_cnt','desc')" href="javascript:void(0)"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a>	
    </td>

             <td>  
            		<a onclick="my_sort('a.ip_cnt','desc')" href="javascript:void(0)"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>	  
    <?php echo __('ofusers')?>
     		<a onclick="my_sort('a.ip_cnt','desc')" href="javascript:void(0)"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a>	
    </td>
    

   <td class="last"><?php echo __('action')?></td>
   
    
    
		</tr>
	</thead>
	</table>
	</div>
	<?php //*********************表格头*************************************?>	
	
	
	
		<?php //*********************循环输出的动态部分*************************************?>	
			<?php 	$mydata =$p->getDataArray();	$loop = count($mydata); for ($i=0;$i<$loop;$i++) {?>
		<div id="resInfo<?php echo $i?>">
	<table class="list">
	<col style="width: 3%;">
		<col style="width: 5.5%;">
	<col style="width: 5.5%;">

	<col style="width: 10%;">
	<col style="width: 10%;">
	<col style="width: 9.4%;">
	<col style="width: 9.4%;">
	<col style="width: 9.4%;">
	<col style="width: 7.5%;">

	<col style="width: 3.5%;">
	<col style="width: 12.5%;">

								
	<tbody>

				<tr class="row-<?php echo $i%2+1;?>">
				<td style="text-align:center"><input type="checkbox" value="<?php echo $mydata[$i][0]['resource_id']?>"/></td>
				 <td  align="center"  style="font-weight: bold;">
			 <img   id="image<?php echo $i; ?>"  		onclick="pull('<?php echo $this->webroot?>',this,<?php echo $i;?>)"    class=" jsp_resourceNew_style_1"  src="<?php echo $this->webroot?>images/+.gif"   title="<?php echo __('viewip')?>"/>

		</td >
		

		
		
			    <td  align="center">
		    
		    <a  href="<?php echo $this->webroot?>gatewaygroups/edit_resouce_ingress/<?php echo $mydata[$i][0]['resource_id']?>"  title="<?php echo __('edit')?>">
		    	<?php echo $mydata[$i][0]['resource_id']?>	
		    		</a>
		    </td>
		    <td  align="center">
		    
		    <a  href="<?php echo $this->webroot?>gatewaygroups/edit_resouce_ingress/<?php echo $mydata[$i][0]['resource_id']?>"  title="<?php echo __('edit')?>">
		    	<?php echo $mydata[$i][0]['alias']?>	
		    		</a>
		    </td>
		    <td  align="center"  ><?php echo $mydata[$i][0]['name']?></td >
		   
		    <td  align="center"><?php  if(empty($mydata[$i][0]['capacity'])) {echo "0";}else{echo  $mydata[$i][0]['capacity']; }?></td>
		     <td ><?php  if(empty($mydata[$i][0]['cps_limit'])) {echo "0";}else{echo  $mydata[$i][0]['cps_limit']; }?></td>


		    <td align="center">
		    <?php if($mydata[$i][0]['active']==1){?>
		    
		    <a         onclick="return confirm('<?php echo __('confirmdisablegate')?>');"  
			      href="<?php echo $this->webroot?>gatewaygroups/dis_able/<?php echo $mydata[$i][0]['resource_id']?>/view_ingress" title="<?php echo __('disable')?>">
		    	 	<img  title="<?php echo __('wangtodisable')?>" src="<?php echo $this->webroot?>images/flag-1.png">
		  </a>
		   <?php }else{?>
		   
		     <a         onclick="return confirm('<?php echo __('confirmactivegate')?>');"  
			      href="<?php echo $this->webroot?>gatewaygroups/active/<?php echo $mydata[$i][0]['resource_id']?>/view_ingress" title="<?php echo __('disable')?>">
		<img  title="<?php echo __('wangtoactive')?>" src="<?php echo $this->webroot?>images/flag-0.png"><?php }?>
		</a>
		  </td>
		
		    <td align="center"><?php echo $mydata[$i][0]['ip_cnt']?></td>
		    

		    
		    <td align="center"><?php  if(empty($mydata[$i][0]['client_id'])) {echo 0;}else{echo  1; }?></td>

          

          
               <td align="center">
 
                  		    <?php if ($w == true) {?><a  style="float: left; margin-left: 40px;"  href="<?php echo $this->webroot?>gatewaygroups/edit_resouce_ingress/<?php echo $mydata[$i][0]['resource_id']?>"  title="<?php echo __('edit')?>">
                       		      <img  title="<?php echo __('edit')?>"   src="<?php echo $this->webroot?>images/editicon.gif" > </a>
         <a     style="float: left; margin-left: 40px;"     onclick="return confirm('Note:Do you want to delete this object ?');"
			      href="<?php echo $this->webroot?>gatewaygroups/del/<?php echo $mydata[$i][0]['resource_id']?>/view_ingress" title="<?php echo __('del')?>">
			        <img  title="<?php echo __('del')?>" 
		  src="<?php echo $this->webroot?>images/delete.png" >
			      </a><?php }?>
          </td>
          

				</tr>
		
		</tbody>
	<tbody>
</tbody>
</table>
</div>


<?php //＊＊＊＊＊＊＊＊＊＊＊＊＊res_ip＊＊＊＊＊＊＊＊＊＊＊＊＊＊?>
			<div id="ipInfo<?php echo $i?>" class=" jsp_resourceNew_style_2">
			<table>
				<tr>
					<td>
					<div id="ipTable<?php echo $i?>" class=" jsp_resourceNew_style_3">

					</div>
					<script type="text/javascript">
				            createTable('<?php echo $this->webroot?>',<?php echo $mydata[$i][0]['resource_id']?>,<?php echo $i?>);
				          </script></td>
				</tr>
			</table>

			</div>
			<?php //＊＊＊＊＊＊＊＊＊＊＊＊＊res_ip＊＊＊＊＊＊＊＊＊＊＊＊＊＊?>
<?php }?>
<div style="height:10px"></div>
<?php //*****************************************循环输出的动态部分*************************************?>	



			<div id="tmppage"><?php echo $this->element('page');?></div>
<?php }?>
</div>

<div id="livemargins_control" style="position: absolute; display: none; z-index: 9999;"><img width="77" height="5" style="position: absolute; left: -77px; top: -5px;" src="chrome://livemargins/skin/monitor-background-horizontal.png">	
<img style="position: absolute; left: 0pt; top: -5px;" src="chrome://livemargins/skin/monitor-background-vertical.png">
	<img style="position: absolute; left: 1px; top: 0pt; opacity: 0.5; cursor: pointer;" onmouseout="this.style.opacity=0.5" onmouseover="this.style.opacity=1" src="chrome://livemargins/skin/monitor-play-button.png" id="monitor-play-button"></div>