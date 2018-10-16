<?php $user_id=array_keys_value($this->params,'pass.0')?>
<?php echo $form->create ('User', array ('action' =>'add_carrier_user'));?>
	<table>
		<col width="50%"/>
		<col width="50%"/>
		<tbody>
			<tr>
				<td class="first" style="vertical-align:top">
    				<table class="form">
    					<tbody>
    						<tr>
        						<td class="label label2"><?php echo __('username')?>:</td>
        						<td class="value value2">
        						<input  type="hidden"   id="user_id" value="<?php echo array_keys_value($this->params,'pass.0'); ?>">  
        							<?php echo $form->input('name',array('label'=>false ,'div'=>false,'type'=>'text','maxLength'=>'256'));?>
        						</td>
    						</tr>
    						<tr>
        						<td class="label label2"><?php echo __('New',true);?> <?php echo __('password')?>:</td>
        						<td class="value value2"><?php echo $form->input('password',array('label'=>false,'maxLength'=>'66' ,'div'=>false,'type'=>'password'));?></td>
    						</tr>
    						<tr>
        						<td class="label label2"><?php echo __('Confirm Password',true);?>:</td>
        						<td class="value value2"><?php echo $form->input('repassword',array('label'=>false,'maxLength'=>'66' ,'div'=>false,'type'=>'password','name'=>''));?></td>
    						</tr>
    						<tr>
        						<td class="label label2"><?php echo __('fullname')?>:</td>
        						<td class="value value2">
     								<?php echo $form->input('fullname',array('label'=>false ,'div'=>false,'type'=>'text','maxLength'=>'256'));?>
								</td>
    						</tr>
    						<tr>
        						<td class="label label2"><?php echo __('email')?>:</td>
								<td class="value value2">	
									<?php echo $form->input('email',array('label'=>false ,'div'=>false,'type'=>'text'));?>
        						</td>
    						</tr>
							<?php $t= $session->read('login_type'); ?>
						</tbody>
					</table>
				</td>
				<td class="last"  style="vertical-align:top">
					<table class="form">
						<tbody>
							<tr>
								<td class="label label2"><?php echo __('client')?>:</td>
        						<td class="value value2">
 									<?php echo $form->input('client_id',array('options'=>$appUsers->_get_select_options($ClientList,'Client','client_id','name'),'label'=>false ,'div'=>false,'type'=>'select'));?>
								</td>
    						</tr>
							<tr>
								<td valign="top" class="label label2"><?php echo __('Status')?>:</td>
								<td class="value value2">
								 <?php echo $form->checkbox('active')?>
						
								</td>
							</tr>

						</tbody>
					</table>
				</td>
			</tr>
		</tbody>
	</table>
<div id="form_footer">
            <input type="submit" value="<?php echo __('submit')?>" class="input in-submit"/>
</div>
<?php echo $form->end();?>
<script type="text/javascript">
jQuery(document).ready(function(){
    jQuery('#UserName,#UserFullname').xkeyvalidate({type:'strNum'})
    jQuery('#UserEmail').xkeyvalidate({type:'Email'});
    jQuery('#UserAddCarrierUserForm').submit(function(){
			   var ret=true;
			   if(!jQuery.xkeyvalidate('#UserName',{type:'noEmpty'}))
			        {
				   Query(this).addClass('invalid');
				   jQuery.jGrowl(" User Name, Cannot Be NULL !",{theme:'jmsg-error'});
			     ret =false;
			        }
			
			
			       /* if($('user_id').val()!=''||$('user_id').val()!=null)
			           			 {
			        	ret =true;
			       				 }
			        else{*/
			     if(!jQuery.xkeyvalidate('#UserPassword',{type:'noEmpty'}))
			            {
			    	    jQuery('#UserPassword').addClass('invalid');
					    jQuery.jGrowl("New Password is required",{theme:'jmsg-error'});
			        ret= false;
			            }
						//=====/^[\w|-]+[.|@]*[\w|-]+$/
		      if(!/^[\w-\.\@\s]+/.test(jQuery('#UserName').val())||jQuery('#UserName').val().length>256){
		    	     jQuery('#UserName').addClass('invalid');
				      jQuery.jGrowl(" User Name, allowed characters: a-z,A-Z,0-9,-,_,.,@,space,and maximum  of 256 characters in length.",{theme:'jmsg-error'});
		         ret= false;
                   
       		             }
		      if(jQuery('#UserRepassword').val()==''){
					 jQuery('#UserRepassword').addClass('invalid');
					 jQuery.jGrowl('Please enter confirm password !',{theme:'jmsg-error'});
					 ret= false;

					}
				if(!password_same(jQuery('#UserPassword'),jQuery('#UserRepassword'))){
             ret=false;
			     	}
			        //    }
			
			
			//        if(!jQuery.xkeyvalidate('#UserEmail',{type:'Email'}))
			//        {
			//        	alert('eeeeeeeeee');
			//        	return false;
			//        }
				//alert(ret);
			        return ret;
			     });

  
  
    jQuery('#UserPassword').val('');

 });     

</script>
