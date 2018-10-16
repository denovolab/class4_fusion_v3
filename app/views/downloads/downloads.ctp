<div id="title">
<h1><?php echo 
    isset($module) ? $module : 
    Inflector::humanize($appUploads->show_upload_title($this->params['action']))
    
    ?>&gt;&gt;
	Edit <?php echo isset($action) ? $action : __($this->params['action'], true);?>
  
  <font class="editname"><?php echo @empty($name[0][0]['name'])||$name[0][0]['name']==''?'':"[".$name[0][0]['name']."]" ?></font>
  &gt;&gt; Export
</h1>
	<form method="GET" action="">
	<ul id="title-menu">
		<?php if(isset($back_url) && !empty($back_url)):?>
		<li><a class="link_back" href="<?php echo $back_url?>"><img width="10" height="5" alt="" src="<?php echo $this->webroot?>images/icon_back_white.png">Back</a></li>
		<?php endif;?>	
	</ul>
	<ul id="title-search">		
	</ul>
	</form>
</div>
<div id="container">
<?php echo $this->element('downloads/'.$this->params['action'].'_tabs')?>
<?php if (isset ( $exception_msg ) && $exception_msg) :	?>
	<?php	echo $this->element ( 'common/exception_msg' );?>		
<?php endif;?>

<form action="" method="POST">
<table class="cols" style="width:700px;margin:0px auto;">
	<tr>
		<td class="first" style="width:50%">
			<fieldset>
				<legend><?php echo __('Format Options',true);?></legend>				
				<table>
				<?php echo $this->element('downloads/'.$this->params['action'])?>
                <?php if($this->params['action']=='carrier'){}else{?>
                                <tr>
                                        <td style="text-align:right;padding-right:4px;"><?php echo __('download',true);?>:</td>
                                       <td style="text-align:left;"><?php echo $form->select('',array('effect' => 'Currently Effective','all' => "All"),null,array('name' => "type",'style'=>'width:160px;'),false)?></td>
                                        <!--<td style="text-align:left;"><?php echo $form->select('',array('all' => "All"),null,array('name' => "type",'style'=>'width:160px;'),false)?></td>-->
                                </tr>
                                <?php }?>
				<tr>
					<td style="text-align:right;padding-right:4px;"><?php echo __('Data Format',true);?>:</td>
					<td style="text-align:left;"><?php echo $form->select('',array('csv' => "EXCEL CSV",'xls' => 'EXCEL XLS'),null,array('name' => "format",'style'=>'width:160px;'),false)?></td>
				</tr>
				<tr>
					<td style="text-align:right;padding-right:4px;"></td>
					<td style="text-align:left;"><input name="with_headers" type="checkbox" id="checkbox_with_headers" checked="checked"><label for="checkbox_with_headers"><?php echo __('With headers row',true);?></label></td>
				</tr>
				<tr>
					<td style="text-align:right;padding-right:4px;"><?php echo __('Header Text',true);?>:</td>
					<td style="text-align:left;"><textarea name="header" style="width:100%"></textarea></td>
				</tr>
				<tr>
					<td style="text-align:right;padding-right:4px;"><?php echo __('Footer Text',true);?>:</td>
					<td style="text-align:left;"><textarea name="footer" style="width:100%"></textarea></td>
				</tr>
				</table>
			</fieldset>
		</td>
		<td class="last"  style="width:50%">
			<fieldset>
				<legend><?php echo __('Columns',true);?></legend>
				<?php echo $this->element('common/download_columns')?>
			</fieldset>
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<?php  if ($_SESSION['role_menu']['Switch']['jurisdictionprefixs']['model_x']||$_SESSION['role_menu']['Switch']['rates']['model_w']||$_SESSION['role_menu']['Switch']['codedecks']['model_w']) {?>
			<?php echo $form->submit("Download",array('div' => false,'class'=>'input in-submit'))?>
            <?php }?>
			<input type="reset" value="<?php __('reset')?>" class="input in-submit" />
		</td>
	</tr>
	</table>
</form>
</div>
