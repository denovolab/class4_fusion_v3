<?php echo $form->create('Rate')?>

<table>
	<tr>
		<td></td>
		<td><?php echo $xform->input('name',array('maxlength'=>256))?></td>
		<td><?php echo $xform->input('code_deck_id',Array('options'=>$$hel->_get_select_options($CodedeckList,'Codedeck','code_deck_id','name'),'empty'=>''))?></td>
		<td><?php echo $xform->input('currency_id',Array('options'=>$$hel->_get_select_options($CurrencyList,'Currency','currency_id','code')))?></td>
		<td></td>
		<td><?php echo $xform->input('rate_type',Array('options'=>array(0=>'DNIS', 1=>'LRN', 2=>'LRN BLOCK', 3=>'LRN Block Higher') ));?></td>
<!--		<td><?php echo $xform->input('jurisdiction_country_id',Array('options'=>$$hel->_get_select_options($JurisdictioncountryList,'Jurisdictioncountry','id','name'),'empty'=>''))?></td>-->
<td><?php echo $xform->input('jur_type',Array('options'=>array(0=>'A-Z', 1=>'US Non-JD', 2=>'US JD', 3=>'OCN-LATA-JD', 4 => 'OCN-LATA-NON-JD') ));?></td>
		<td><?php echo $xform->input('lnp_dipping_rate',array('maxlength'=>256))?></td>
                <td></td> <td></td>
                <td>
			<a title="Save" href="#%20" id="save" >
				<img style="float: left; margin-left: 20px;" src="<?php echo $this->webroot?>images/menuIcon_004.gif" height="16" width="16">
			</a>
			<a title="Exit" href="#%20" style="margin-left: 20px;" id="delete" >
				<img style="float: left; margin-left: 20px;" src="<?php echo $this->webroot?>images/delete.png" height="16" width="16">
			</a>
                        <a title="Indeterminate Rate Setting" href="#%20" onclick="showDiv('pop-div','500','200','/Class4/clients/addratetable/<?php echo $this->params['pass'][0] ?>');" id="edit_indeter">
                             <img style="float: left; margin-left: 20px;" src="<?php echo $this->webroot?>images/indeteminate.png" height="16" width="16" />
                        </a>
		</td>
	</tr>
</table>
<?php echo $form->end()?>
