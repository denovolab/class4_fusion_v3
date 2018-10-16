<div id="cover"></div>
<div id="title">
  <h1>
      <?php __('Switch')?>&gt;&gt;
    	<?php echo __('codeslist')?>
    		<font class="editname" title="Name">
    						<?php echo empty($code_name[0][0]['name'])||$code_name[0][0]['name']==''?'':'['.$code_name[0][0]['name'].']' ?>
    				</font>
  </h1>
<ul id="title-search">
    <li>
	    	<form  method="get">
	    		<input type="text" id="search-_q" class="in-search default-value input in-text defaultText" title="<?php echo __('namesearch')?>" value="<?php if (!empty($search)) echo $search;?>" name="search">
	    	</form>
    </li>
    <li title="advanced search »" onclick="advSearchToggle();" id="title-search-adv" style="display: list-item;"></li>
  </ul>
  <ul id="title-menu">
  
            <?php  if ($_SESSION['role_menu']['Switch']['codedecks']['model_w']) {?>
    		<li><a class="link_btn"id="addcode" href="#"><img width="16" height="16" alt="" src="<?php echo $this->webroot?>images/add.png"> <?php echo __('createnew')?></a></li>
    		<!--
    		<li>
    			<a href="<?php echo $this->webroot?>codedecks/download_code/<?php echo $code_deck_id?>">
    				<img width="16" height="16"  alt="" src="<?php echo $this->webroot?>images/ico_download.gif">
    				&nbsp;<?php echo __('download')?>
    			</a>
    		</li>
    		<li>
    			<a href="javascript:void(0)" onclick="cover('uploadcode');">
    				<img width="16" height="16"  alt="" src="<?php echo $this->webroot?>images/ico_upload.gif">
    				&nbsp;<?php echo __('upload')?>
    			</a>
    		</li>
    		--><li>
    		<a class="link_btn" rel="popup" href="javascript:void(0)" onclick="deleteAll('<?php echo $this->webroot?>/codedecks/del_code/all/<?php echo $code_deck_id?>');" ><img width="16" height="16" alt="" src="<?php echo $this->webroot?>images/delete.png"> <?php echo __('deleteall')?></a></li>
    <li><a class="link_btn" rel="popup" href="javascript:void(0)" onclick="ex_deleteSelected('producttab','<?php echo $this->webroot?>/codedecks/del_code/selected/<?php echo $code_deck_id?>','code deck list');"><img width="16" height="16" alt="" src="<?php echo $this->webroot?>images/delete.png"> <?php echo __('deleteselected')?></a></li>
    <?php }?>
    <!--  <li>
  <form action="<?php echo $this->webroot?>products/upload" enctype="multipart/form-data" method="post">
  <input type="submit" value="Upload" class="input in-button"/>
  			<input type="file" class="input in-text" name="MyFile"/>
  </form>
  	 </li>-->
     
     
     <?php if (isset($edit_return)) {?>
        <li>
    			<a class="link_back" href="<?php echo $this->webroot?>/codedecks/codes_list/<?php echo $code_deck_id?>">
    				<img width="16" height="16"  alt="" src="<?php echo $this->webroot?>images/icon_back_white.png">
    				&nbsp;<?php echo __('goback')?>
    			</a>
    			</li>
    			<?php }?>
    <li>
    			<a  class="link_back" href="<?php echo $this->webroot?>codedecks/codedeck_list">
    				<img width="16" height="16"  alt="" src="<?php echo $this->webroot?>images/icon_back_white.png">
    				&nbsp;<?php echo __('goback')?>
    			</a>
    		</li>
  </ul>
</div>
<div id="cover"></div>
		    <div id="uploadcode"  style="display:none;height: auto;z-index:99;position: absolute;left:30%;top: 20%;" class="form_panel_upload">
			    <form action="<?php echo $this->webroot?>/codedecks/upload_code/<?php echo $code_deck_id?>" method="post" enctype="multipart/form-data" id="productFile">	
			    <span class="wordFont1 marginSpan1"><?php echo __('selectfile')?>:</span>
			    <div style="height: 100px;" class="up_panel_upload">
			    <input style="margin-top:10px;" type="file" value="Upload" size="45" class="input in-text" id="browse" name="browse">
			    <div style="margin-top:20px;">
			    			<input type="radio" title="<?php echo __('upload_overwrite')?>" checked value="1" name="handleStyle">
					    <span><?php echo __('overwrite')?></span>
					    <input style="margin-left:10px;" type="radio" title="<?php echo __('upload_remove')?>" value="2" name="handleStyle">
					    <span><?php echo __('remove')?></span>
					    <input style="margin-left:10px;" type="radio" title="<?php echo __('upload_refresh')?>" value="3" name="handleStyle">
					    <span><?php echo __('clearrefresh')?></span>
			       <input style="margin-left:10px;" type="checkbox" checked onclick="if(this.value=='false')this.value='true';else this.value='false';document.getElementById('isRoll').value=this.value;">
			       <input type="hidden" value="true" name="isRoll" id="isRoll"/>
			       <span><?php echo __('rollbackonfail')?> </span>   
			    </div>   
			    </div>
			    <div class="form_panel_button_upload">
			    			<span style="float:left"> <?php echo __('downloadtempfile')?><a href="<?php echo $this->webroot?>products/downloadtemplate/f" style="color:red"><?php echo __('clickhere')?></a></span>
			    			<input type="submit" class="input in-button" value="<?php echo __('upload')?>"/>
			    			<input type="button" onclick="closeCover('uploadcode')" style="margin-bottom:6px;" class="input in-button" value="<?php echo __('cancel')?>"/>
			    </div>  
			    </form>
		    </div>
		    <div id="uploadcode_error"  style="display:none;height: auto;z-index:99;position: absolute;left:30%;top: 20%;" class="form_panel_upload">
			    <span class="wordFont1 marginSpan1"><span style="color:red" id="affectrows"></span>&nbsp;&nbsp;<?php echo __('erroroccured')?>:</span>
			    <div style="height: auto;text-align:left;" id="code_upload_errorMsg" class="up_panel_upload"></div>    
			    <div class="form_panel_button_upload">
			    			<span style="float:left"><?php echo __('downloadtempfile')?> .<a href="<?php echo $this->webroot?>products/downloadtemplate/f" style="color:red"><?php echo __('clickhere')?></a></span>
			    			<input type="button" onclick="closeCover('uploadcode_error')" style="margin-bottom:6px;" class="input in-button" value="<?php echo __('close')?>"/>
			    </div>  
		    </div>
<div id="container">
<ul class="tabs">
      <li   class="active" ><a href="<?php echo $this->webroot?>codedecks/codes_list/<?php echo $code_deck_id;?>"><img width="16" height="16" src="<?php echo $this->webroot?>images/list.png"> <?php __('codeslist')?></a></li>
      <?php  if ($_SESSION['role_menu']['Switch']['codedecks']['model_x']) {?>
       <li  ><a href="<?php echo $this->webroot?>uploads/code_deck/<?php echo $code_deck_id?>"><img width="16" height="16" src="<?php echo $this->webroot?>images/import.png"> <?php __('upload')?></a>        </li>
       <li ><a href="<?php echo $this->webroot?>down/code_deck/<?php echo $code_deck_id?>">
       <img width="16" height="16" src="<?php echo $this->webroot?>images/export.png"> <?php __('download')?></a>    </li>
       <?php }?>
       </ul>
       	<?php //*********************  条件********************************?>
<fieldset class="title-block" id="advsearch"  style="display:none;width: 100%;">
	<form method="get">
	<input type="hidden" name="adv_search" value="1"/>
<table>
<tbody>
<tr>
    <td><label><?php echo __('code')?>:</label>
     <input name="code" id="code"/>
  </td>
    <td><label><?php echo __('code name')?>:</label>
     <input name="code_name" id="code_name"/>
  </td>
    <td><label><?php echo __('Country')?>:</label>
     <input name="country" id="country"/>
  </td>
    <td class="buttons"><input type="submit" value="<?php echo __('submit')?>" class="input in-submit"></td>
</tr>
</tbody></table>
</form></fieldset>
<div id="toppage"></div>
<?php $d = $p->getDataArray();if (count($d) == 0) {?>
<div class="msg" id="no_data_found" style="display:<?php echo  (count($d) == 0)? 'block':'none' ?>"><?php echo __('no_data_found')?></div>
<?php }?>
<table class="list" style="display: <?php echo  (count($d) == 0)? 'none':' ' ?>" id="listData" >
	
	<thead>
		<tr>
		<?php  if ($_SESSION['role_menu']['Switch']['codedecks']['model_w']) {?><td><input type="checkbox" onclick="checkAllOrNot(this,'producttab');" value=""/></td>
        <?php }?>
    <td>
     <?php echo $appCommon->show_order('code_id',__('ID',true))?>
    </td>
    <td>
          <?php echo $appCommon->show_order('code',__('code',true))  ?>
    </td>
    <td>
      <?php echo $appCommon->show_order('codenames',__('codenames',true))?>
    </td><!--
    <td><a href="javascript:void(0)" onclick="my_sort('city','asc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>&nbsp;<?php echo __('city')?>&nbsp;<a href="javascript:void(0)" onclick="my_sort('city','desc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a></td>
    <td><a href="javascript:void(0)" onclick="my_sort('state','asc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>&nbsp;<?php echo __('state')?>&nbsp;<a href="javascript:void(0)" onclick="my_sort('state','desc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a></td>
    --><td>
    <?php echo $appCommon->show_order('country',__('country',true))?>
    
    </td>
   <?php  if ($_SESSION['role_menu']['Switch']['codedecks']['model_w']) {?>
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
				<?php  if ($_SESSION['role_menu']['Switch']['codedecks']['model_w']) {?><td style="text-align:center"><input type="checkbox" value="<?php echo $mydata[$i][0]['code_id']?>"/></td>
                <?php }?>
		    <td>
		    <input type="hidden"name="id" value="<?php echo $mydata[$i][0]['code_id']?>"/>
		    	<?php echo $mydata[$i][0]['code_id']?></td>
		    <td style="font-weight: bold;"><?php echo $mydata[$i][0]['code']?></td>
		    <td><?php echo $mydata[$i][0]['name']?></td><td><?php echo $mydata[$i][0]['country']?></td>
		   
           <?php  if ($_SESSION['role_menu']['Switch']['codedecks']['model_w']) {?>
            <td  style="text-align: center;">
		    		<a class="edit" title="<?php echo __('edit')?>"id="<?php echo $mydata[$i][0]['code_id'] ?>" href="<?php echo $this->webroot?>codedecks/edit_code/<?php echo $mydata[$i][0]['code_id'] ?>">
		    			<img src="<?php echo $this->webroot?>images/editicon.gif" />
		    		</a>
		    		<a title="<?php echo __('del')?>" href="javascript:void(0)" onclick="ex_delConfirm(this,'<?php echo $this->webroot?>codedecks/del_code/<?php echo $mydata[$i][0]['code_id'] ?>/<?php echo $code_deck_id?>','code deck  <?php echo $mydata[$i][0]['code']?>');">
		    			<img src="<?php echo $this->webroot?>images/delete.png" />
		    		</a>
		    </td>
            <?php }?>
				</tr>
		<?php }?>		
	</tbody>
	<tbody>
</tbody>
</table>
<div id="tmppage">
<?php echo $this->element('page');?>
</div>
</div>
<div id="livemargins_control" style="position: absolute; display: none; z-index: 9999;"><img width="77" height="5" style="position: absolute; left: -77px; top: -5px;" src="chrome://livemargins/skin/monitor-background-horizontal.png">	<img style="position: absolute; left: 0pt; top: -5px;" src="chrome://livemargins/skin/monitor-background-vertical.png">	<img style="position: absolute; left: 1px; top: 0pt; opacity: 0.5; cursor: pointer;" onmouseout="this.style.opacity=0.5" onmouseover="this.style.opacity=1" src="chrome://livemargins/skin/monitor-play-button.png" id="monitor-play-button"></div>
<!-- 上传文件 如果有错误信息则显示 -->
<?php 
		$upload_error = $session->read('upload_route_error');
		if (!empty($upload_error)) {
			$session->del('upload_route_error');
			$affectRows = $session->read('upload_commited_rows');
			$session->del('upload_commited_rows');
?>
		<script language=JavaScript>
		//提交的行数
			document.getElementById("affectrows").innerHTML = "<?php echo $affectRows?>";
			//错误信息
			var errormsg = eval("<?php echo $upload_error?>");
			var loop = errormsg.length;
			var msg = "";
			for (var i = 1;i<=loop; i++) {
				msg += errormsg[i-1].row+"<?php echo __('row')?>"+" : "+errormsg[i-1].name+errormsg[i-1].msg+"&nbsp;&nbsp;&nbsp;&nbsp;";
				if (i % 2 == 0) {
					msg += "<br/>";
				}
				if (i == loop) {
					msg += "<p>&nbsp;&nbsp;<p/>";
				}
				document.getElementById('code_upload_errorMsg').innerHTML = msg;
			}
			cover('uploadcode_error');
		</script>
<?php 
		}
?>
<script type="text/javascript">
jQuery(document).ready(function(){
	jQuery('#addcode').click(
		function(){
			var action="<?php echo $this->webroot?>codedecks/add_code";
			jQuery('table.list').trAdd({
				action:action,
				ajax:'<?php echo $this->webroot?>codedecks/js_code_save?id=<?php echo $code_deck_id?>',
				onsubmit:function(options){return jsAdd.onsubmit(options);}
			});
			jQuery('#Country').autocomplete({source:'<?php echo $this->webroot?>codedecks/ajax_options_ext?type=country',width:'auto'});
			jQuery('#no_data_found').attr('style','display:none');
			jQuery('#listData').attr('style','display:  ');
			return false;
		});

	//修改方法```
	jQuery('.edit').click(
			function(){
				var id=jQuery(this).attr('id');
				var action="<?php echo $this->webroot?>/codedecks/edit_code";
				jQuery(this).parent().parent().trAdd(
					{
						ajax:"<?php echo $this->webroot?>codedecks/js_code_save/"+id,
						action:action,
						saveType:'edit',
						onsubmit:function(options){return jsAdd.onsubmit(options);}
					}
				);
				jQuery('#Country').autocomplete({source:'<?php echo $this->webroot?>codedecks/ajax_options?type=country',width:'auto'});
				return false;
			}
	);

	
});
</script>
<script type="text/javascript">
var jsAdd={
	onsubmit:function(options){
		var re=true;
		var tr=jQuery('#'+options.log);
		var code=tr.find('#code').val();
//	  var name=tr.find('#name').val();
//	  var country = tr.find('#Country').val();
//	 if(name==''){jQuery.jGrowlError('Code Name is required！');re=false;}else if(/[^0-9A-Za-z-\_\.]+/.test(name)||name.length>16){jQuery.jGrowlError(': Code Name,allowed characters:a-z,A-Z,0-9,-,_,space,maximum of 16 characters in length! ');re=false;}
//	 if(/[^0-9A-Za-z-\_\.\s]+/.test(country)||country.length>50){jQuery.jGrowlError(' Country, allowed characters: a-z,A-Z,0-9,-,_,space, maximum  of 50 characters in length! ');re=false;}
	 if(/[^0-9A-Za-z-\_\.\s]+/.test(name)||name.length>16){jQuery.jGrowlError(' Code Name, allowed characters: a-z,A-Z,0-9,-,_,space, maximum  of 50 characters in length! ');re=false;}
		if(code==''){
			jQuery.jGrowlError('Code ,cannot be null！!');
			re=false;
		}	else if(/\D/.test(code)){
			jQuery.jGrowlError('Code Only a number!');
			re=false;
		}
		return re;
	}
}
</script>

<script type="text/javascript">
/*
jQuery(document).ready(function(){
	jQuery('#add').click(function(){
		jQuery('table.list').trAdd({});
	});
});
*/
</script>



