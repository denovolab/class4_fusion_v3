<link href="<?php echo $this->webroot?>images/favicon.ico" type="image/x-icon" rel="shortcut Icon">
<script type="text/javascript">
	function save_device(){
		var nm = document.getElementById("username").value;
		var pw = document.getElementById("password").value;
		var id = document.getElementById("res").value;
		jQuery.post("<?php echo $this->webroot?>/testdevices/save_device",{n:nm,p:pw,r_id:id},function(d){
			jQuery.jGrowl(d,{theme:'jmsg-alert'});
		});
	}
</script>
<style>
    .form td{
        border-width:1px;
        border-style:solid;
        border-color:rgb(204, 204, 204) rgb(227, 229, 230) rgb(204, 204, 204) rgb(204, 204, 204);
    }
    
</style>
<div id="title">
  <h1>
    <?php __('System')?>
    &gt;&gt;<?php echo __('systemlimit')?></h1>
    <ul id="title-menu">
        <a class="link_btn" href="<?php echo $this->webroot ?>systemlimits/reload">
                <img width="16" height="16" src="<?php echo $this->webroot ?>images/reload.png">Reload License			
            </a>
    </ul>
</div>
<div class="container">
  <table class="list" >
      <thead>
          <tr >
          <th>
              Expiration Date:<?php echo date("Y-m-d H:i:s", intval(substr(strip_tags($date[4]), 0, 10)));?>
          </th>
          
          <th>
              Self-Defined Limit
          </th>
          
          
          <th>
              License Limit
          </th>
          
      </tr>
      </thead>
    <tbody>
        
      
      <tr>
        <td ><?php echo __('calllimit')?>:</td>
        <td ><input style="width:150px;" type="text" id="ingrLimit" 
    value="<?php echo strip_tags($date[0]);?>"  name="ingrLimit" class="input in-text"></td>
        <td style="text-align:center">
            <?php echo strip_tags($date[2]);?>
            <input  value="<?php echo strip_tags($date[2]);?>" disabled="true " style="width:150px" type="hidden" id="capLicense">
        </td>
      </tr>
      <tr>
        <td><?php echo __('cpslimit')?>:</td>
        <td ><input style="width:150px;" type="text" id="ingrpLimit" 
    		
    		    value="<?php echo strip_tags($date[1]);?>"
    		name="ingrpLimit" class="input in-text"></td>
        <td style="text-align:center">
           <?php echo strip_tags($date[3]);?>
            <input value="<?php echo strip_tags($date[3]);?>" disabled="true "  style="width:150px" type="hidden" id="cpsLicense">
        </td>
      </tr>
    </tbody>
  </table>
    <table class="list">
        <tr>
            <td>License</td>
            <td>
                <form enctype="multipart/form-data" action="<?php echo $this->webroot; ?>systemlimits/upload_license" method="post">
                <input type="file" name="license"/>
                <input type="submit" value="Upload">
                </form>
            </td>
            <td>
                <a href="<?php echo $this->webroot ?>systemlimits/down_license_key" title="Download License Key">
                    <img src="<?php echo $this->webroot?>images/license.png" />
                </a>
            </td>
        </tr>
    </table>
  <?php  if ($_SESSION['role_menu']['Switch']['systemlimits']['model_w']) {?>
  <div id="form_footer">
    <input type="button" value="<?php echo __('submit')?>" onClick="javascript:postLimit();return false;" class="input in-submit">
  </div>
<?php }?>
</div>
<script type="text/javascript">
        function postLimit(){
                                var ingrl = $('#ingrLimit').val();
                                var ingrp = $('#ingrpLimit').val();


                                var capLin = $('#capLicense').val();
                                var cpsLin = $('#cpsLicense').val();
                                
                                //alert(ingrl+"::"+ingrp+"::"+capLin+"::"+cpsLin);
                                
                                if(ingrl > capLin){
                                    showMessages("[{'field':'#ingrLimit','code':'101','msg':'Self-Defined Limit can not be greater than License Limit '}]");
                                    return false;
                                }
                                
                               
                                if((cpsLin - ingrp)<0){
                                    showMessages("[{'field':'#ingrLimit','code':'101','msg':'Self-Defined Limit can not be  greater than License Limit '}]");
                                    return false;
                                }
                                
                               
                                
                                var pattern = /^[1-9]{1}[0-9]*$/;

                                if(!pattern.test(ingrl)){
                                        showMessages("[{'field':'#ingrLimit','code':'101','msg':'<?php echo
__('calllimitinvalid',true)?>'}]");
                                        //alert('The Call Limit is invalid!');
                                        return false ;
                                }
                                
                                

                                if(!pattern.test(ingrp)){
                                        showMessages("[{'field':'#ingrpLimit','code':'101','msg':'<?php
echo __('ingrpLimitinvalid',true)?>'}]");
                                        //alert('The CPS Limit is invalid!');
                                        return false ;
                                }
                                
                                
                                if(parseInt(ingrl) > 60000) {
                                        showMessages("[{'field':'#ingrLimit','code':'101','msg':'The `Call limit` must be less or equal than 60000'}]");
                                        return false ;
                                }
                                
                                if(parseInt(ingrp) > 2000) {
                                        showMessages("[{'field':'#ingrpLimit','code':'101','msg':'The `CPS Limit:` must be less or equal than 2000'}]");
                                        return false ;
                                }

                                $.ajax({
                                                url:"<?php echo $this->webroot?>systemlimits/ajax_update.json",
                                                data:{ingressC:ingrl,ingressP:ingrp},
                                                type:'POST',
                                                async:true,

success:function(text){

if(text==1){
	showMessages("[{'field':'','code':'201','msg':'Succeeded!'}]");

	
}
                                                	},

error:function(XmlHttpRequest){showMessages("[{'field':'#ingrLimit','code':'101','msg':'"+XmlHttpRequest.responseText+"'}]");}
                                });
                                        
                }
        
        
        
        </script> 
<script type="text/javascript">
jQuery(document).ready(
		function(){
				jQuery('#ingrLimit,#ingrpLimit').xkeyvalidate({type:'Num'});		
		}
);
</script>