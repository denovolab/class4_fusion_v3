<?php $mydata =$p->getDataArray();?>
<?php if(empty($mydata)){?>
<div id="noRows" class="msg"><?php echo __('no_data_found',true);?></div>
<table style="display:none"  class="list list-form"  id="list_table">
<col style="width: 20%;">
<col style="width: 20%;">
<col style="width: 20%;">
<col style="width: 20%;">
<thead>
		<tr>
<!--		   <td> <?php echo $appCommon->show_order('id',__('ID',true))?></td>-->
		   <td> <?php echo $appCommon->show_order('name',__('Name',true))?></td>
		   <td> <?php echo $appCommon->show_order('trans',__('Digit Translation Count',true))?></td>
		   <td> <?php echo $appCommon->show_order('updateat',__('Updated At',true))?></td>
		  <?php  if ($_SESSION['role_menu']['Routing']['products']['model_w']) {?>
           <td  class="last"><?php echo __('action')?></td>
           <?php }?>
		</tr>
</thead>
<tbody id="rows">
</tbody>
</table>
<?php }else{?>
<table  class="list list-form"  id="list_table">

<thead>
		<tr>
<!--		   <td> <?php echo $appCommon->show_order('id',__('ID',true))?></td>-->
		   <td> <?php echo $appCommon->show_order('name',__('Name',true))?></td>
		   <td> <?php echo $appCommon->show_order('trans',__('Digit Translation Count',true))?></td>
		   <td> <?php echo $appCommon->show_order('updateat',__('Updated At',true))?></td>
		  <?php  if ($_SESSION['role_menu']['Routing']['products']['model_w']) {?> <td  class="last"><?php echo __('action')?></td>
          <?php }?>
		</tr>
</thead>
<tbody id="rows">
<?php foreach($mydata as $list){?>
<tr >
<!--    <td>
     <?php echo array_keys_value($list,'0.id')?>
    </td>-->
    <td>
    		
            <a href="<?php echo $this->webroot?>digits/translation_details/<?php echo array_keys_value($list,'0.id')?>" title="View Details"  class="link_width" style="width:80%;display:block"  id="tpl-trans-text">
    		
    	<?php echo array_keys_value($list,'0.name')?>

    	  		</a>
               
    </td>
    <td> 
    		<a href="<?php echo $this->webroot?>digits/translation_details/<?php echo array_keys_value($list,'0.id')?>" title="View Details"  class="link_width" style="width:100%;display:block"  id="tpl-trans-text">
    			<?php echo array_keys_value($list,'0.trans')?>
    		</a>
    	
  
    </td>
    <td> <?php echo array_keys_value($list,'0.updateat')?> </td>
   <?php  if ($_SESSION['role_menu']['Routing']['products']['model_w']) {?>
    <td align="center" style="text-align:center" class="last">
 					<a id="edit" href="#" title="Edit" list_id=<?php echo array_keys_value($list,'0.id')?>>
           <img title="Edit" src="<?php echo $this->webroot?>images/editicon.gif"> 
        </a>
        <a href="<?php echo $this->webroot?>digits/delete/<?php echo array_keys_value($list,'0.id')?>"  onclick="return  confirm('Are you sure to delete ,digit mapping  <?php echo array_keys_value($list,'0.name'); ?> ? ')" title="Deleted">
			      <img title="Deleted" src="<?php echo $this->webroot?>images/delete.png">
			   </a>
    </td>
    <?php }?>
</tr>
<?php }?>
</tbody>
</table>
<?php }?>
<script type="text/javascript">
jQuery(document).ready(function(){

    jQuery('#objectForm').submit(function(){
   	 var flag=true;
	       jQuery('input[rel=format_number]').map(function(){
	         if(/[^0-9A-Za-z-\_\s]+/.test(jQuery(this).val())){
	        	   jQuery(this).addClass('invalid');
	        	   jQuery.jGrowl('Name,allowed characters:a-z,A-Z,0-9,-,_,space,maximum of 16 characters in length! ',{theme:'jmsg-error'});
	        	   flag=false;
	                       }
	                  })
	            
        
       
        	var arr = new Array();
      		$('#list_table').find('input[name*=name]').each(function (){
      			arr.push($(this).val());

          		});

          	var arr2=$.uniqueArray(arr);
         
      		if(arr.length!=arr2.length){
         		$('#list_table').find('select[name*=name]').each(function (){
         			jQuery(this).addClass('invalid');
         			flag=false;

              		});
			  		
        		jQuery.jGrowl('Name  Happen  Repeat.',{theme:'jmsg-error'});
        		flag=false;
          		}
      return flag;
                 });  

	jQuery('#add').click(function(){
        
                jQuery('#noRows').hide();
		
		jQuery('table.list').show().trAdd({
			action:'<?php echo $this->webroot?>digits/js_save_digits',
			ajax:'<?php echo $this->webroot?>digits/js_save_digits',
			removeCallback:function(){if(jQuery('table.list tr').size()==1){jQuery('table.list').hide()}}
		});
		jQuery('#DigitTranslationName').attr('mycheck','add','maxLength','256');
	});
	jQuery('a[id=edit]').click(function(){
		
		jQuery(this).parent().parent().trAdd({
			action:'<?php echo $this->webroot?>digits/js_save_digits/'+jQuery(this).attr('list_id'),
			ajax:'<?php echo $this->webroot?>digits/js_save_digits/'+jQuery(this).attr('list_id'),
			saveType:'edit'
		});
		jQuery('#DigitTranslationName').attr('mycheck','edit','maxLength','256');
	});

});
</script>
