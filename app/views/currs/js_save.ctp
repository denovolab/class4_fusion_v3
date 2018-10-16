<?php echo $form->create('Curr')?>
<table>
	<tr>
<!--		<td></td>-->
		<td><?php echo $form->input('code',Array('div'=>false,'label'=>false,'maxlength'=>256))?></td>
		<td><?php echo $form->input('rate',Array('div'=>false,'label'=>false))?></td>
		<td></td>
		<td></td>
                <td></td>
		<td><?php echo $form->input('active',Array('div'=>false,'label'=>false))?></td>
		<td>
			<a title="Save" id="save" href="" onclick="return false">
      			<img src="<?php echo $this->webroot?>images/menuIcon_004.gif" height="16" width="16">
			</a>
			<a title="Exit" id="delete"  href="">
				<img src="<?php echo $this->webroot?>images/delete.png" height="16" width="16">
			</a>
		</td>
	</tr>
</table>
<?php echo $form->end()?>