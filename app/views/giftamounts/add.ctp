
    <link href="<?php echo $this->webroot?>images/favicon.ico" type="image/x-icon" rel="shortcut Icon">
    
    <script type="text/javascript">
    			function checkForm(){
        	var giftamountRefillAmount = document.getElementById("GiftamountRefillAmount").value;
        	var giftamountBasicAmount = document.getElementById("GiftamountBasicAmount").value;
        	var giftamountGiftAmount = document.getElementById("GiftamountGiftAmount").value;
        	var giftamountBonusCredit = document.getElementById("GiftamountBonusCredit").value;
        	var reg = /^[0-9]+(\.[0-9]{1,3})?$/;
						var has_error = false;
        	if (!giftamountRefillAmount){
            	has_error = true;
            	jQuery.jGrowl('<?php echo __('enterrefillamount')?>',{theme:'jmsg-alert'});
         } else {
            if (!reg.test(giftamountRefillAmount)){
                  has_error = true;
                  jQuery.jGrowl('<?php echo __('refillamountformaterror')?>',{theme:'jmsg-alert'});
                    			}
                			}

//			if (!giftamountBasicAmount && !giftamountGiftAmount && !giftamountBonusCredit){
//				  has_error = true;
//				  jQuery.jGrowl('<?php echo __('allnullerror')?>',{theme:'jmsg-alert'});
//				}

			if (giftamountGiftAmount) {
				if (!reg.test(giftamountGiftAmount)){
	                  has_error = true;
	                  jQuery.jGrowl('<?php echo __('giftamountformaterror')?>',{theme:'jmsg-alert'});
	                    			}
			}

			if (giftamountBasicAmount) {
				if (!reg.test(giftamountBasicAmount)){
	                  has_error = true;
	                  jQuery.jGrowl('<?php echo __('basicamountformaterror')?>',{theme:'jmsg-alert'});
	                    			}
			}

			if (giftamountBonusCredit) {
				if (!/^\d+$/.test(giftamountBonusCredit)){
	                  has_error = true;
	                  jQuery.jGrowl('<?php echo __('scoreintergeronly')?>',{theme:'jmsg-alert'});
	                    			}
			}

					if (has_error){return false;}
        			}
    			
    </script>

<div id="title">
        <h1><?php echo __('addsales_strategy_charges')?></h1>
</div>
<div class="container">

	<?php echo $form->create ('Giftamount', array ('action' => 'add','onsubmit'=>'return checkForm();' ));?>
	  <?php echo $form->input('sales_strategy_id',array('label'=>false,'div'=>false,'type'=>'hidden','value'=>$id))?>
<table class="form">
<tbody>

<tr>
    <td class="label label2"><?php echo __('refill_amount')?>(<?php __('money')?>):</td>
    <td class="value value2">
           		<?php echo $form->input('refill_amount',  
 		array('label'=>false ,'div'=>false,'type'=>'text',  'style'=>'float: left; width: 300px;','class'=>'input in-text'));?>
</td>
</tr>

<tr>
    <td class="label label2"><?php echo __('basic_amount')?>(<?php __('money')?>):</td>
    <td class="value value2">
           		<?php echo $form->input('basic_amount',  
 		array('label'=>false ,'div'=>false,'type'=>'text',  'style'=>'float: left; width: 300px;','class'=>'input in-text'));?>
</td>
</tr>

<tr>
    <td class="label label2"><?php echo __('gift_amount')?>(<?php __('money')?>):</td>
    <td class="value value2">
           		<?php echo $form->input('gift_amount',  
 		array('label'=>false ,'div'=>false,'type'=>'text', 'style'=>'float: left; width: 300px;','class'=>'input in-text'));?>
    </td>
</tr>


<tr>
    <td class="label label2"><?php echo __('gift_amount')?><?php __('Rates')?>:</td>
    <td class="value value2">
           	<?php 
   		
   	
   		echo $form->input('gift_amount_rate_table_id',
 		array('options'=>$ratetable,  'name'=>'gift_amount_rate_table_id[]', 'id'=>'gift_amount_rate_table_id0', 'style'=>'width:300px;float:left;', 'empty'=>__('selectratetable',true),'label'=>false ,
 	
 		'div'=>false,'type'=>'select'));?>
    </td>
</tr>




<tr>
    <td class="label label2"><?php echo __('bonus_credit')?>(<?php __('a')?>):</td>
    <td class="value value2">
           		<?php echo $form->input('bonus_credit',  
 		array('label'=>false ,'div'=>false,'type'=>'text','style'=>'float: left; width: 300px;', 'class'=>'input in-text'));?>
    </td>
</tr>
</tbody></table>

<div id="form_footer">
   <input type="submit" value="<?php echo __('submit')?>"  class="input in-submit">
</div>
</div>
