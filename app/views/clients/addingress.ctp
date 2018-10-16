<div id="title">
  <h1>
     Management &gt;&gt; Carrier [<?php echo $client_name ?>] &gt;&gt; Add Ingress Trunk 
  </h1>
  <ul id="title-menu" />
  
  <li> <a  class="link_back" href="<?php echo $this->webroot?>prresource/gatewaygroups/view_ingress?<?php echo $this->params['getUrl']?>" class="link_back"><img width="16" height="16" alt="Back" src="<?php echo $this->webroot?>images/icon_back_white.png">&nbsp;<?php echo __('goback',true);?></a> </li>
  </ul>
</div>
<div class="container">

  <?php echo $form->create ('Clients', array ('id' => 'myform','action' => 'addingress/'.$this->params['pass'][0] ));?> <?php echo $form->input('ingress',array('label'=>false ,'value'=>'true','div'=>false,'type'=>'hidden'));?> <?php echo $form->input('egress',array('label'=>false ,'value'=>'false','div'=>false,'type'=>'hidden'));?>
  <input type="hidden" name="is_finished" id="is_finished" value="0" />
  <table class="cols">
    <col width="35%"/>
    <col width="38%"/>
    <col width="27%"/>
    <tr>
      <td><!-- COLUMN 1 -->
        
        <?php //**********系统信息**************?>
       
          <table class="form">
            <tr>
              <td><?php echo __('Ingress Name',true);?>:</td>
              <td><?php echo $form->input('alias',array('id'=>'alias','label'=>false ,'div'=>false,'type'=>'text','maxlength'=>'256'));?></td>
            </tr>
          </table>
        </td>
        
        
    </tr>
  </table>
  <?php echo $this->element("gatewaygroups/host")?>   
  <?php  echo $this->element("clients/resource_prefix")?>
  <?php if($$hel->_get('viewtype')=='wizard'){?>
  <div id="form_footer">
    <input type="submit"    onclick="seleted_codes();jQuery('#GatewaygroupAddResouceIngressForm').attr('action','?nextType=egress&<?php echo $$hel->getParams('getUrl')?>')" value="<?php echo __('Next Egress')?>" style="width:80px" />
    <input type="submit"    onclick="seleted_codes();jQuery('#GatewaygroupAddResouceIngressForm').attr('action','?nextType=ingress&<?php echo $$hel->getParams('getUrl')?>')" value="<?php echo __('Next Ingress')?>" style="width:80px"/>
    <input type="button"  value="<?php echo __('End')?>" class="input in-submit" onclick="location='<?php echo $this->webroot?>clients/index?filter_id=<?php echo $$hel->_get('query.id_clients')?>'"/>
  </div>
  <?php }else{?>
  <div id="form_footer">
    <input type="submit" id ="submit_form" style="width:auto;" value="<?php echo __('Add Ingress Trunk')?>" />
    <input type="button" id="egress" style="width:140px;" value="<?php echo __('Add Egress Trunk',true);?>" />
<!--    <input type="reset"  value="<?php echo __('Finished')?>" class="input in-submit" />-->
    <input type="button" id="back" value="<?php echo __('Finished')?>" />
  </div>
  <?php }?>
  <?php echo $form->end();?> 
  
  <!-----------Add Rate Table----------->
<div id="pop-div" class="pop-div" style="display:none;">
	<div class="pop-thead">
    	<span></span>
        <span class="float_right"><a href="javascript:closeDiv('pop-div')" id="pop-close" class="pop-close">&nbsp;</a></span>
    </div>
    <div class="pop-content" id="pop-content"></div>
</div>
	<div id="pop-clarity" class="pop-clarity" style="display:none;"></div>
</div>



<!-----------Add dynamic----------->
<div id="pop-div-dynamic" class="pop-div" style="display:none;">
	<div class="pop-thead">
    	<span></span>
        <span class="float_right"><a href="javascript:closeDiv2('pop-div-dynamic')" id="pop-close-dynamic" class="pop-close">&nbsp;</a></span>
    </div>
    <div class="pop-content" id="pop-content-dynamic"></div>
</div>
	<div id="pop-clarity-dynamic" class="pop-clarity" style="display:none;"></div>
</div>

  </div>
<script type="text/javascript" src="<?php echo $this->webroot?>js/gateway.js"></script> 
<script type="text/javascript"><!--
	 jQuery(document).ready(
    	function(){
	       jQuery('#totalCall,#totalCPS').xkeyvalidate({type:'Num'});
           jQuery("form[id^=ClientsAddingress]").submit(function(){
		      var re =true; 
            if(jQuery('#alias').val()==''){
            	jQuery('#alias').addClass('invalid');
            	  jQuery(this).jGrowlError('The field Egress Name cannot be NULL.');
                return false;
                                       
              }else if(/[^0-9A-Za-z-\_\s]/.test(jQuery("#alias").val())){
			     jQuery('#alias').addClass('invalid');
				 jQuery(this).jGrowlError('Ingress Name, allowed characters: a-z,A-Z,0-9,-,_,space, maximum  of 256 characters in length!');
				return false;
		    }
			
			  if(jQuery('#totalCall').val()!=''){
			        if(/\D/.test(jQuery('#totalCall'.val()))){
				        jQuery(this).addClass('invalid');
						     jQuery(this).jGrowlError('Call limit, must be whole number! ');
						     return false;
					}	  
			   }

           if(jQuery('#totalCPS').val()!=''){
		       if(/\D/.test(jQuery('#totalCPS').val())){
			        jQuery(this).addClass('invalid');
					jQuery(this).jGrowlError('CPS Limit, must be whole number!');
                     return false;
			   }	   
		 	   
		   }
     if(jQuery('#ip:visible').val()!=''||!jQuery('#ip:visible').val()){
		    if(!/^(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])$/.test(jQuery('#ip:visible').val())){
				  
			   jQuery(this).addClass('invalid');
			   jQuery(this).jGrowlError('IPs/FQDN must be a valid format ');
			   return false;
				  
		    	} 
	    	}
	    if(jQuery('#GatewaygroupClientId').val()==''){
				        jQuery(this).addClass('invalid');
						     jQuery(this).jGrowlError('Please Select Carriers !');
						     return false;
			   }
	    	
		 return re;
		   })
 });
 
 
 
	
 </script>
 <script type="text/javascript">
 $(function() {
     $('#egress').click(function() {
        window.location.href = "<?php echo $this->webroot ?>clients/addegress/<?php echo $client_id ?>";
    });
     $('#back').click(function() {
        $('#is_finished').val('1');
        $('#myform').submit();
     });
 });    
 </script>