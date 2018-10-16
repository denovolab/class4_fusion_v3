<?php echo $form->create('TranslationItem')?>
			<?php echo $xform->input('translation_id',Array('type'=>'hidden'))?>
			<table class="form" style="margin-left:15%;">
				<tbody>
					<tr>
							<td></td>
					   <td >
					    	<?php echo $xform->input('ani',Array('style'=>'width:100px','type'=>'text')) ?>
					   </td>
					   <td >
					   	<?php echo $xform->input('dnis',Array('style'=>'width:100px','type'=>'text')) ?>
					   </td>
					   <td >
					   	<?php echo $xform->input('action_ani',Array('style'=>'width:100px','type'=>'text'))?>
					   </td>
					   <td>
					   	<?php echo $xform->input('action_dnis',Array('style'=>'width:100px','type'=>'text'))?>
					   </td>
					   <td>
					    	<?php echo $xform->input('ani_method',Array('options'=>Array(0=>'ignore',1=>'compare',2=>'replace'),'style'=>'width:100px'))?>
					   </td>
					   <td>
					    	<?php echo $xform->input('dnis_method',Array('options'=>Array(0=>'ignore',1=>'compare',2=>'replace'),'style'=>'width:100px'))?>
					   </td>
					   <td>
						   <a title="Save" id="save" href="" onclick="return false">
							   <img src="<?php echo $this->webroot?>images/menuIcon_004.gif" height="16" width="16">
								</a>
								<a title="Exit" id="delete"  href="">
									<img src="<?php echo $this->webroot?>images/delete.png" height="16" width="16">
								</a>
					   </td>
					</tr>
				</tbody>
			</table>
<?php echo $form->end()?>