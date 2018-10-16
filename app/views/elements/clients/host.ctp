<a id="addHost" onclick="return false;" href="#"> <img  src="<?php echo $this->webroot?>images/add.png"> <?php echo __('Add Host',true);?> </a>
<table class="list list-form"  id="host_table">
  <thead>
    <tr>
      <td style="width:25%"><span rel="helptip" class="helptip" id="ht-100002" title="Name of an account in JeraSoft yht system (for statistics and reports)"><?php echo __('IP/FQDN',true);?></span><!-- <span class="tooltip" id="ht-100002-tooltip"</span>--></td>
     <td  style="width:25%"><span rel="helptip" class="helptip" id="ht-100004" title="Technical prefix, that is used to identify users, when multiple clients use same gateway"><?php echo __('port',true);?></span><!-- <span class="tooltip" id="ht-100004-tooltip"></span>--></td>
      <td  style="width:25%" ><?php echo __('Capacity',true);?></td>
      <td  style="width: 55px;" class="last">&nbsp;</td>
    </tr>
  </thead>
  <tbody>
    <tr class="rows" id="rows">
      <td class="value"><input type="text" style="width: 200px;" name="ip[]" id="ip" /></td>
      <td class="value"><input type="text" name="port[]" id="port" maxlength="16" style="width: 200px;"/></td>
      <td></td>
      <td style="width: 55px;" class="value last"><a href="#" title="delete" rel="delete"> <img style="margin-top:5px;" width="16" height="16" src="<?php echo $this->webroot?>images/delete.png"> </a></td>
    </tr>
  </tbody>
</table>

<script type="text/javascript">

jQuery(document).ready(function(){
	$row = $('#rows').remove();
    jQuery(function($) {
        $('#addHost').click(function() {
            $row.clone(true).appendTo('#host_table tbody').show();
			return false;        
        });
    });

	jQuery('input[id=port]').xkeyvalidate({type:'Num'});
	jQuery('form[id=ClientAddegressForm]').submit(function(){
    var re=true;
	
    if(jQuery('input[id=alias]').val()==''){
		jQuery('input[id=alias]').jGrowlError('Name is required');
		re=false;
    }
	//if($('#alias').val()==''){
	//	jQuery($('#alias')).jGrowlError('Egress Name is required');
	//	re=false;
   // }
    jQuery('input[id=port]').each(function(){
		if(jQuery(this).val()==''){
			jQuery(jQuery(this)).jGrowlError('Port is required');
			re=false;
    	}
        if(jQuery(this).val()!='' && isNaN(jQuery(this).val())){
              jQuery(this).jGrowlError('Port,must be whole number! ');
              re=false;
        }
    });
	
	jQuery('input[id=ip]').each(function(){
                
                var arr = jQuery(this).val().split('.');
                
                for(var i=0;i<arr.length;i++) {
                    if(isNaN(arr[i])||arr[i] > 255||((arr.length-1)!=3)) {
                        jQuery(this).jGrowlError('Invalid IP Address.');
                        re = false;
                        break;
                    }
                }
                
                if(jQuery(this).val().indexOf('.')==-1 || jQuery(this).val().indexOf('/')!=-1){
                  jQuery(this).jGrowlError('Invalid IP Address.');
                  re=false;
                 }
				
				
                });
    
	var arr=Array();
    
	jQuery('#host_table tr').each(function(){
	    for(var i in arr){
	       if(jQuery(this).find('input[id=ip]').val()==arr[i].ip && jQuery(this).find('input[id=port]').val()==arr[i].port){
	             jQuery.jGrowlError('Ip '+arr[i].ip+" is Repeat!");
	             re=false;
	             return;
	       }
	    }
	    if(jQuery(this).find('input[id=ip]').val()!=''){
			arr.push({ip:jQuery(this).find('input[id=ip]').val(),port:jQuery(this).find('input[id=port]').val()});
	   }
       });
   	
	   if(re){
			var arr=Array();
			jQuery('#host_table tr').each(function(){
					if(jQuery(this).find('#ip').size()>0){
						arr.push(jQuery(this).find('#ip').val()+'/'+jQuery(this).find('#GatewaygroupNeedRegister').val());
					}
			});
	 
	 arr=arr.join(',');
	 var data=jQuery.ajaxData("<?php echo $this->webroot?>ajaxvalidates/ip4r/noDomain?ip="+arr);
			data='['+data+']';
			data=eval(data);
			data=data[0];
			for(var i in data){
				if(data[i]==false){
						var eq=parseInt(i)+1;
						jQuery('#host_table tr').eq(eq).find('#ip,#GatewaygroupNeedRegister').jGrowlError(jQuery('#host_table tr').eq(eq).find('#ip').val()+'/'+jQuery('#host_table tr').eq(eq).find('#GatewaygroupNeedRegister').val()+' is not ip!');
						re=false;
					}
				}
			}
return re;
		});
});
</script>

