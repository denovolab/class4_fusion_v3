<?php $form->create('Product')?>
<table>
<tr>
<td><?php echo $xform->input('product_id',Array('type'=>'hidden','name'=>'id'))?></td>
<td>
	<?php echo $xform->input('name',Array('name'=>'name','style'=>'width:100px'))?>
</td>
<td>
    <?php //echo $xform->input('code_type',Array('options'=>Array('0'=>'By Code','1'=>'By Code Name'),'style'=>'width:150px','onchange'=>'changeCodeType(this)'))?>
</td>
<td>
   <?php 
       /* $codeDecks =array("0"=>"");
        if(!empty($code_decks)){
            foreach($code_decks as $code_deck){
                $codeDecks[$code_deck[0]['code_deck_id']] = $code_deck[0]['name'];
            }
        }*/
   ?>
   
   
    <?php
           
           /* if($this->data['Product']['code_type'] == '1'){
                echo $xform->input('code_deck_id',Array('options'=>$codeDecks,'style'=>'width:150px'));
            }else{
                echo $xform->input('code_deck_id',Array('options'=>$codeDecks,'style'=>'display:none;width:150px','selected'=>'0'));
            }*/
    
    ?>
    
</td>
<td>
    <?php echo $xform->input('route_lrn',Array('options'=>Array('0'=>'DNIS','1'=>'LRN'),'style'=>'width:150px'))?>
</td>
<td></td>
<td></td>
<td></td>
<td></td>
<td>
<a title="Save" href="#%20" id="save" >
				<img style="float: left; margin-left: 20px;" src="<?php echo $this->webroot?>images/menuIcon_004.gif" height="16" width="16">
</a>
			<a title="Exit" href="#%20" style="margin-left: 20px;" id="delete" >
				<img style="float: left; margin-left: 20px;" src="<?php echo $this->webroot?>images/delete.png" height="16" width="16">
			</a>
</td>
</tr>
</table>
<?php $form->end()?>
