<dl id="addproduct" class="tooltip-styled" style="display:none;position:absolute;left:35%;top:25%;z-idnex:99;width:500px;height:200px;">
<dd style="text-align:center;width:100%;height:25px;font-size: 16px;"><?php echo __('addfeedback')?></dd>
	<dd style="margin-top:10px;">
		<textarea id="ComplainContent" class="input in-text in-textarea" style="float: left; width: 400px; height: 94px;" rows="6" cols="30" name="data[Complain][content]"></textarea>
	</dd>
	<dd style="text-align:center">
			<input checked type="radio" class="ss" name="status" value="1"/><?php echo __('open')?>&nbsp;&nbsp;&nbsp;&nbsp;
			<input type="radio" name="status" class="ss" value="2"/><?php echo __('closed')?>&nbsp;&nbsp;&nbsp;&nbsp;
			<input type="radio" name="status" class="ss" value="3"/><?php echo __('resolved')?>&nbsp;&nbsp;&nbsp;&nbsp;
	</dd>
	<dd style="margin-top:10px; margin-left:25%;width:150px;height:auto;">
		<input type="button" onclick="addfeedback('ComplainContent','<?php echo $this->webroot?>complainfeedbacks/add/<?php echo $id?>',<?php echo $status?>);" value="<?php echo __('submit')?>" class="input in-button">
		<input type="button" onclick="closeCover('addproduct');" value="<?php echo __('cancel')?>" class="input in-button">
	</dd>
</dl>
<dl id="viewmessage" class="tooltip-styled" style="display:none;position:absolute;left:35%;top:25%;z-idnex:99;width:500px;height:200px;">
	<dd style="text-align:center;width:100%;height:25px;font-size: 16px;">
		<?php echo __('viewmessage')?>
		<a style="float:right;" href="javascript:void(0)" onclick="closeCover('viewmessage');" title="<?php echo __('close')?>">
			<img width="16" height="16" src="<?php echo $this->webroot?>images/delete.png">
		</a>
	</dd>
	<dd style="margin-top:10px;">
		<textarea id="CompleteContent" class="input in-text in-textarea" style="float: left; width: 400px; height: 94px;" rows="6" cols="30" ></textarea>
	</dd>
</dl>
<div id="toppage" style="display:none"><?php echo $this->element('page');?></div>
<?php echo $this->element("jur_prefix/list")?>
<div id="tmppages" style="display:none">
<?php echo $this->element('page');?>
</div>  <?php  if ($_SESSION['role_menu']['Switch']['jurisdictionprefixs']['model_w']) {?> 
<div id="form_footer">
 <input id="sub" type="button" value="<?php echo __('submit',true);?>" class="input in-button"/>
   
    <input type="button" value="<?php echo __('cancel',true);?>" onclick="winClose();" class="input in-button">
    
 </div><?php }?>
<script type="text/javascript">
    jQuery(document).ready(function(){
       jQuery('#sub').click(function(){
    	   		this.disabled=true;
           te = true;
           jQuery('input[rel=format_number]').map(
             function(){
               if(/\D/.test(jQuery(this).val())){
                 jQuery(this).addClass('invalid');
                 jQuery.jGrowl('Prefix must contain alphanumeric characters only.',{theme:'jmsg-error'});
                 te=false;           
                                      }      
                                 }
                            );
	      
         var arrs=Array();
 					 jQuery('#rows tr').each(
							function(){
								var prefix=jQuery(this).find('input[id*=prefix]').val();
								var jurisdiction_country_name=jQuery(this).find('input[id*=jurisdiction_country_name]').val();
								var arr;
								arr=Array(prefix,jurisdiction_country_name);
								for(var i in arrs){
									if(isReport(arr,arrs[i])){
										jQuery.jGrowlError('The Jurisdiction Country Name and Prefix combination cannot be duplicate!');
										te=false;
										break;
									}
								}
								if(arr[0]){
									arrs.push(arr);
								}else{
									jQuery(this).find('input[id*=prefix]').jGrowlError('Prefix, cannot be null！');
									te=false;
								}
							}
 		 				);
         if(te){
        	 $('#objectForm').submit();
                        }
         this.disabled=false;
          return te;
                  })
            })
		 function isReport(arr1,arr2){
			 if(arr1[0]==arr2[0] && arr1[1]==arr2[1]){
					return true;
			  }
			 return false;
			}
</script>
