
<div id="title">
  <h1><?php echo __('Import Jurisdiction Prefix',true);?> </h1>
    
    <ul id="title-menu">

<li><a class="link_btn" href="<?php echo $this->webroot?>/systemlimits/import_rate/22"><img width="16" height="16" src="<?php echo $this->webroot?>images/import.png"> <?php echo __('upload',true);?></a></li>

<li><a class="link_btn" href="<?php echo $this->webroot?>/systemlimits/download_rate/22"><img width="10" height="5" src="<?php echo $this->webroot?>images/export.png" alt=""><?php echo __('download',true);?></a></li>
     
   <li><a class="link_btn" style="width: 185px;" href="<?php echo $this->webroot?>/systemlimits/add_jurisdiction_prefix">
       <img width="16" height="16" src="<?php echo $this->webroot?>images/add.png" alt=""><?php echo __('Add Jurisdiction Prefix',true);?> </a></li>
           		<li>
    			<a class="link_back" href="<?php echo $this->webroot?>/systemlimits/jurisdiction_view">
    				<img width="16" height="16" alt="" src="<?php echo $this->webroot?>images/icon_back_white.png">
    				&nbsp;<?php echo __('goback',true);?>		</a>
    		</li>

</ul>
</div>

<div class="container">
<ul class="tabs">



      <li  ><a href="<?php echo $this->webroot?>/systemlimits/jurisdiction_view_prefix/<?php echo $_SESSION['jurisdiction_id'];?>"><img width="16" height="16" src="<?php echo $this->webroot?>images/list.png">  <?php echo __('Prefix',true);?></a></li>
      
       <li   class="active" >
       <a href="<?php echo $this->webroot?>/systemlimits/import_rate/<?php echo $_SESSION['jurisdiction_id']?>"><img width="16" height="16" src="<?php echo $this->webroot?>images/import.png"> <?php __('upload')?></a>        </li>
   
       </ul>
       
       
       		<form method="post"   enctype="multipart/form-data" action="<?php echo  $this->webroot?>systemlimits/upload_code2/">
       		
       		
		<input type="hidden" id="id_code_decks" value="<?php echo $_SESSION['jurisdiction_id']?>" name="upload_table_id" class="input in-hidden">
		<input type="hidden" id="id_code_decks" value="jurisdiction_prefix" name="upload_real_table" class="input in-hidden">
		
						<input type="hidden" id="code_name" value="<?php echo $code_name?>" name="code_name" class="input in-hidden">
			
			
			
				
				
					<input type="hidden"  value="tmp_jurisdiction_prefix" name="upload_table" class="input in-hidden">
			<table class="form" style="margin-left:15%;">
				<tbody>
					<tr>
					   <td class="label label2"><?php __('Jurisdiction')?></td>
					   <td class="value value2">
					    		<input type="text" style="float:left;width:300px;"  readonly="readonly" id="code" value="<?php echo $code_name;?>" name="code" class="input in-text">
					   </td>
					</tr>
					
					
										<tr>
					    <td class="label label2"><?php echo __('Select CSV file to import',true);?>:</td>
					    <td class="value value2">
					    <INPUT TYPE = "hidden" NAME = "UploadAction" VALUE = "1">
					 <INPUT TYPE = "hidden" NAME = "MAX_FILE_SIZE" VALUE ="1000000000000">
					    
				<input type="file" size="38"  name="file"     class="input in-file">
					    </td>
					</tr><!--
					
					
															<tr>
					    <td class="label label2">
					    
					    
					    </td>
					    <td class="value value2">
		<fieldset  style="text-align: left;color:#568ABC;
font-size:1.05em;
font-weight:bold;">请选择上传文件中字段的顺序</fieldset>
					    </td>
					</tr>
					
															--><!--<tr>
					    <td class="label label2">号码:</td>
					    <td class="value value2">
				    			<select style="float:left;width:300px;" name="delimiter" id="delimiter">
					    			<option   selected="selected" value="1">1</option>
					    				<option  value="2">2</option>
					    				<option  value="3">3</option>
					    				<option  value="4">4</option>
					        </select>
					    </td>
					</tr>
					
									<tr>
					    <td class="label label2">国家:</td>
					    <td class="value value2">
				    			<select style="float:left;width:300px;" name="delimiter" id="delimiter">
					    			<option  value="1">1</option>
					    				<option    selected="selected" value="2">2</option>
					    				<option  value="3">3</option>
					    				<option  value="4">4</option>
					        </select>
					    </td>
					</tr>
					
					
					
												<tr>
					    <td class="label label2">省份:</td>
					    <td class="value value2">
				    			<select style="float:left;width:300px;" name="delimiter" id="delimiter">
					    			<option  value="1">1</option>
					    				<option     value="2">2</option>
					    				<option   selected="selected" value="3">3</option>
					    				<option  value="4">4</option>
					        </select>
					    </td>
					</tr>
					
					
					
												<tr>
					    <td class="label label2">城市:</td>
					    <td class="value value2">
				    			<select style="float:left;width:300px;" name="delimiter" id="delimiter">
					    			<option  value="1">1</option>
					    				<option     value="2">2</option>
					    				<option  value="3">3</option>
					    				<option   selected="selected" value="4">4</option>
					        </select>
					    </td>
					</tr>
					<tr>
					    --><td class="label label2"></td>
					    <td class="value value2"  style="text-align: left;">
				<input type="radio" name="upload_param" value="1"   checked="" title="该选项会读取CSV文件的每一行插入到数据库中,如果该号码已经存在,将会覆盖数据库中原来有的记录" class="input in-radio"/>
<span><?php echo __('cover',true);?></span>
					  
					  <input type="radio" name="upload_param" value="2" title="CSV文件中与数据库重复的记录将被忽略" style="margin-left: 10px;" class="input in-radio">
					  <span><?php echo __('Ignore duplicate',true);?></span>
					  
					  	  <input type="radio" name="upload_param" value="3" title="CSV文件中与数据库重复的记录将被忽略" style="margin-left: 10px;" class="input in-radio">
					  <span><?php echo __('Delete duplicate',true);?></span>
					  
					  		  	  <input type="radio" name="upload_param" value="4" title="CSV文件中与数据库重复的记录将被忽略" style="margin-left: 10px;" class="input in-radio">
					  <span><?php echo __('File error return error messages',true);?></span>
					    </td>
					</tr>
				</tbody>
			</table>

			<div id="form_footer">
		  	<input type="submit" value="<?php echo __('submit')?>" class="input in-submit">
		  	<input type="reset" value="<?php echo __('reset')?>" class="input in-button">
			</div>
			
			</form>
       
       
       
</div>