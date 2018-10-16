<?php echo $form->create('Codedeck')?>
<table>
	<tr>
<!--		<td></td>-->
		<td></td>
		<td><?php echo $form->input('name',Array('div'=>false,'label'=>false,'value'=>$this->data[0][0]['name'],'maxlength'=>256))?></td>
	 <td></td>
		<td></td>
                <td></td>
                <td></td>
		<td>
			 <a title="Save" id="save" href="<?php echo $this->webroot?>codedecks/add_codedeck" onclick="return false">
      			<img src="<?php echo $this->webroot?>images/menuIcon_004.gif" height="16" width="16">
			</a>
			<a title="Exit" id="delete"  href="">
				<img src="<?php echo $this->webroot?>images/delete.png" height="16" width="16">
			</a>
		</td>
	</tr>
</table>
<?php echo $form->end()?>