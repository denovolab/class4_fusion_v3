<?php echo $form->create('Digit')?>
<table>
<tr>
<!--<td>

</td>-->
<td><?php echo $xform->input('translation_name', array('maxlength'=>256))?></td>
<td></td>
<td></td>
<td align="center" style="text-align:center" class="last">
   <a id="save" href="#" onclick="check_()" title="Edit">
      <img title="save" src="<?php echo $this->webroot?>images/menuIcon_004.gif"> 
   </a>
   <a id="delete" title="Exit">
      <img title="del" src="<?php echo $this->webroot?>images/delete.png">
   </a>
</td>
</tr>
</table>
<?php echo $form->end()?>




