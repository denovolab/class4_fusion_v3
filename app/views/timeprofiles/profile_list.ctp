    <script src="<?php echo $this->webroot?>js/BubbleSort.js" type="text/javascript"></script>
    <script>
    		var webroot = "<?php echo $this->webroot?>";//全局路径
    		function getWeek(day){
    			var week = {
    	    			1:"<?php echo __('monday')?>",
    	    			2:"<?php echo __('tuesday')?>",
    	    			3:"<?php echo __('wed')?>",
    	    			4:"<?php echo __('thuesday')?>",
    	    			5:"<?php echo __('friday')?>",
    	    			6:"<?php echo __('sat')?>",
    	    			7:"<?php echo __('sunday')?>"
    	    		};
	    		return week[day];
    			}
    </script>
<div id="title">
<?php $w = $session->read('writable');?>
  <h1><?php __('System')?>&gt;&gt;
    <?php echo __('timeprofile')?>      
  </h1>
  <ul id="title-search">
    <li>
	    	<form>
	    		<input type="text" id="search-_q" class="in-search default-value input in-text defaultText" title="<?php echo __('namesearch')?>" value="<?php if (!empty($search)) echo $search;?>" name="search">
	    	</form>
    </li>
  </ul>
  <ul id="title-menu">
  
      <?php  if ($_SESSION['role_menu']['Switch']['timeprofiles']['model_w']) {?>  
    <li>
    		<?php if ($w == true) {?><a class="link_btn" id="add" href="<?php echo $this->webroot?>timeprofiles/add_profile">
    			<img width="16" height="16" alt="" src="<?php echo $this->webroot?>images/add.png"> <?php echo __('createnew')?>
    		</a><?php }?>
    </li>
    <?php }?>
	<?php if (isset($edit_return)) {?>
        <li>
    			<a class="link_back" href="<?php echo $this->webroot?>/timeprofiles/profile_list">
    				<img width="16" height="16"  alt="" src="<?php echo $this->webroot?>images/new.icon_back_white.png">
    				&nbsp;<?php echo __('gobackall')?>
    			</a>
    		</li>
        <?php }?>
  </ul>
</div>

<div id="container">
<?php $d = $p->getDataArray();if (count($d) == 0) {?>
<div class="msg"><?php echo __('no_data_found')?></div>
<?php } ?>


<div     id="no_data" style="<?php if (count($d) == 0) {  echo  'display:none';}?>"  >   
<div id="toppage"></div>
	<div>
<table class="list">

	<thead>
		<tr>
		    
<!--     <td>
     <?php echo $appCommon->show_order('time_profile_id','ID'); ?>
</td>-->
    	
    	<td>
    	    <?php echo $appCommon->show_order('name',__('timeprofilename',true)) ?>
    		<!--<a href="javascript:void(0)" onclick="my_sort('name','asc')">
    				<img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png">
    		</a>&nbsp;<?php echo __('timeprofilename')?>&nbsp;
    		<a href="javascript:void(0)" onclick="my_sort('name','desc')">
    			<img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png">
    		</a>
    	--></td>
    	
    	
    	<td>
    	   <?php  echo $appCommon->show_order('type', __('type',true))?>
    		<!--<a href="javascript:void(0)" onclick="my_sort('type','asc')">
    				<img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png">
    		</a>&nbsp;<?php echo __('type')?>&nbsp;
    		<a href="javascript:void(0)" onclick="my_sort('type','desc')">
    			<img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png">
    		</a>
    	--></td>
    	
    	
    		<td>
    		 <?php echo $appCommon->show_order('start_week',__('startweek',true)) ?> 
    		<!--<a href="javascript:void(0)" onclick="my_sort('start_week','asc')">
    				<img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png">
    		</a>&nbsp;<?php echo __('startweek')?>&nbsp;
    		<a href="javascript:void(0)" onclick="my_sort('start_week','desc')">
    			<img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png">
    		</a>
    	--></td>
    	
    	<td>
    	  <?php echo $appCommon->show_order('end_week',__('endweek',true)) ?>
    		<!--<a href="javascript:void(0)" onclick="my_sort('end_week','asc')">
    				<img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png">
    		</a>&nbsp;<?php echo __('endweek')?>&nbsp;
    		<a href="javascript:void(0)" onclick="my_sort('end_week','desc')">
    			<img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png">
    		</a>
    	--></td>
    	
    	<td>
    	      <?php echo $appCommon->show_order('start_time',__('starttime',true)) ?>
    	    <!--
    		<a href="javascript:void(0)" onclick="my_sort('start_time','asc')">
    				<img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png">
    		</a>&nbsp;<?php echo __('start_time',true);?>&nbsp;
    		<a href="javascript:void(0)" onclick="my_sort('start_time','desc')">
    			<img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png">
    		</a>
    	--></td>
    	
    	<td>
    	   <?php echo $appCommon->show_order('end_time',__('endtime',true)) ?>
    	</td>
        
        <td>
            <?php echo __('GMT')?>
        </td>
        
    <?php  if ($_SESSION['role_menu']['Switch']['timeprofiles']['model_w']) {?> 
    <td class="last"><?php echo __('action')?></td>
    <?php }?>
		</tr>
	</thead>
	<tbody id="producttab">
		<?php 
			$mydata =$p->getDataArray();
			$loop = count($mydata); 
			for ($i=0;$i<$loop;$i++) {?>
				<tr class="row-1">
<!--		    <td class="in-decimal"  style="text-align: center;"><?php echo $mydata[$i][0]['time_profile_id']?></td>-->
		    <td style="font-weight: bold;">
<?php  if ($_SESSION['role_menu']['Switch']['timeprofiles']['model_w']) {?> 
		  	<a class="edit link_width " title="<?php echo __('edit')?>" time_profile_id='<?php echo $mydata[$i][0]['time_profile_id']?>'  href="<?php echo $this->webroot?>timeprofiles/edit_profile/<?php echo $mydata[$i][0]['time_profile_id']?>">
	         <?php echo $mydata[$i][0]['name']?>
		  </a> 
          <?php }else{?>
          <?php echo $mydata[$i][0]['name']?>
          <?php }?>
		   </td>
		    
		    <td style="font-weight: bold;">
		    		<?php
		    			if ($mydata[$i][0]['type'] == 0) echo __('alltime');
		    			else if ($mydata[$i][0]['type'] == 1) echo __('weekly');
		    			else echo __('daily'); 
		    		?>
		    </td>
		    
		    <td><script>if (getWeek(<?php echo $mydata[$i][0]['start_week']?>))document.write(getWeek(<?php echo $mydata[$i][0]['start_week']?>));</script></td>
		    <td align="center"><script>if (getWeek(<?php echo $mydata[$i][0]['end_week']?>)) document.write(getWeek(<?php echo $mydata[$i][0]['end_week']?>));</script></td>
		    
		    <td><?php echo $mydata[$i][0]['start_time'] ? $mydata[$i][0]['start_time'].' GMT' : "" ?> </td>
                    <td align="center"><?php echo $mydata[$i][0]['start_time'] ? $mydata[$i][0]['end_time'].' GMT' : "" ?></td>
                    <!--td>
                        
                        <?php
                            
                        if($mydata[$i][0]['type'] != 0){
                            if(!empty($mydata[$i][0]['time_zone'])){
                            echo "GMT ".$mydata[$i][0]['time_zone'].":00";
                            }else{
                                echo "GMT +00:00";
                            }
                        }
                        
                        ?>
                    </td-->
            <?php  if ($_SESSION['role_menu']['Switch']['timeprofiles']['model_w']) {?> 
		    <td align="center;">
		    		<?php if ($w == true) {?>
		    		<a class="edit" title="<?php echo __('edit')?>" time_profile_id='<?php echo $mydata[$i][0]['time_profile_id']?>'  href="<?php echo $this->webroot?>timeprofiles/edit_profile/<?php echo $mydata[$i][0]['time_profile_id']?>">
		    			<img src="<?php echo $this->webroot?>images/editicon.gif" />
		    		</a>
		    		<a title="<?php echo __('del')?>" style="margin-left:10px;" href="javascript:void(0)" onclick="ex_delConfirm(this,'<?php echo $this->webroot?>timeprofiles/delbyid/<?php echo $mydata[$i][0]['time_profile_id']?>','time profile <?php echo $mydata[$i][0]['name']?>');">
		    			<img src="<?php echo $this->webroot?>images/delete.png" />
		    		</a><?php }?>
		    </td>
            <?php }?>
				</tr>
		<?php }?>
	</tbody>
</table>
</div>
	<div id="tmppage">
<?php echo $this->element('page');?>
</div>

</div>


</div>
<div>
</div>
<script type="text/javascript">
jQuery('#add').click(function(){

	jQuery('#no_data').show();
	jQuery('.msg').hide();
	jQuery('table.list').trAdd({
		ajax:'<?php echo $this->webroot?>timeprofiles/js_save',
		action:'<?php echo $this->webroot?>timeprofiles/save',
		callback:function(options){typechange(jQuery('#TimeprofileType'));},
		onsubmit:trAddSubmit
	});
	return false;
});
jQuery('.edit').click(function(){
	var id=jQuery(this).attr('time_profile_id');
	jQuery(this).parent().parent().trAdd({
		ajax:'<?php echo $this->webroot?>timeprofiles/js_save/'+id,
		action:'<?php echo $this->webroot?>timeprofiles/save/'+id,
		callback:function(options){typechange(jQuery('#TimeprofileType'));},
		saveType:'edit',
		onsubmit:trEditSubmit
	});
	return false;
});
function trAddSubmit(options){
	var obj=jQuery('#'+options.log);
	if(obj.find('#TimeprofileName').val()==''){
		jQuery.jGrowlError('The field name cannot be NULL.');
		return false;
	}else{
		if(!/^(\w|\-|\_)*$/.test(obj.find('#TimeprofileName').val())){
			jQuery.jGrowlError('Name, allowed characters: a-z,A-Z,0-9,-,_,space, maximum of 100 characters!');
			return false;
		}
	}
	var id=options.time_profile_id;
	if(!id){
		id=-1;
	}
	var name=obj.find('#TimeprofileName').val();
		var data=jQuery.ajaxData("<?php echo $this->webroot?>timeprofiles/check_name/"+id+"?name="+name);
		if(data.indexOf('false')!=-1){
			jQuery.jGrowlError(name+' is already in use! ');
			return false;
		}

	if (obj.find("#TimeprofileType").val()==2)
	{
		if (obj.find("#TimeprofileStartTime").val()=='')
		{
			jQuery.jGrowlError('The field Start Time cannot be NULL.');
			return false;
		}
	}
		if (obj.find("#TimeprofileType").val()==1)
		{
			if (obj.find("#TimeprofileStartWeek").val()==''||obj.find("#TimeprofileEndWeek").val()=='')
			{
				jQuery.jGrowlError('Start Week && End Week, cannot be null!');
				return false;
			}
		}
	return true;
}

function trEditSubmit(options){
	var obj=jQuery('#'+options.log);
	if(obj.find('#TimeprofileName').val()==''){
		jQuery.jGrowlError('The field Name cannot be NULL.');
		return false;
	}
	var id=options.time_profile_id;
	if(!id){
		id=-1;
	}
//	var name=obj.find('#TimeprofileName').val();
//		var data=jQuery.ajaxData("<?php echo $this->webroot?>timeprofiles/check_name/"+id+"?name="+name);
//		if(data.indexOf('false')!=-1){
//			jQuery.jGrowlError(name+' is already in use! ');
//			return false;
//		}

	if (obj.find("#TimeprofileType").val()==2)
	{
		if (obj.find("#TimeprofileStartTime").val()=='')
		{
			jQuery.jGrowlError('Start Time is required');
			return false;
		}
	}
		if (obj.find("#TimeprofileType").val()==1)
		{
			if (obj.find("#TimeprofileStartWeek").val()==''||obj.find("#TimeprofileEndWeek").val()=='')
			{
				jQuery.jGrowlError('Start Week && End Week, cannot be null!');
				return false;
			}
		}
	return true;
}
</script>
