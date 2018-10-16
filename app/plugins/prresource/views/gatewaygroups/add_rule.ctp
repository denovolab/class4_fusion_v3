<div id="title">
  <h1>
        <?php echo __('Carrier',true);?>[<?php echo $client_name ?>]&gt;&gt;
        		<?php
        			if($type=="egress") {
        					echo		"Egress Trunk";
        			} else {
        					echo "Add Ingress Trunk";
        						}
        		?>
                      
       	<?php echo  $this->element('title_name',array('name'=>$name));?>
        &gt;&gt;Fail-over Rule
  </h1>
  <ul id="title-menu">

			<li>
    			<a class="link_back" href="<?php echo $this->webroot?>prresource/gatewaygroups/view_egress?<?php echo $this->params['getUrl']?>">
    				<img width="16" height="16" src="<?php echo $this->webroot?>images/icon_back_white.png" alt="">
    				&nbsp;<?php __('back')?>    			</a>
    		</li>
  </ul>
</div>
<div id="container">
<?php
								if($type=="egress") {
        					echo $this->element('egress_tab',array('active_tab'=>'rule'));
        			} else {
        					echo $this->element("ingress_tab",array('active_tab'=>'rule'));
        						}
?>
<form  action="<?php echo $this->webroot?>prresource/gatewaygroups/add_rule_post/"   id="hostForm"  method="post">

	
	<?php echo  $appGetewaygroup->echo_resource_hidden($resource_id,$gress);?>
	<input type="hidden" name="addtype" value="<?php echo $type; ?>" />
	<input type="hidden" id="delete_rate_id" value="" name="delete_rate_id" class="input in-hidden">
	<fieldset>
		<legend><?php  if ($_SESSION['role_menu']['Routing']['gatewaygroups:view']['model_w']) {?>
        <a onclick="addItem('ip');return false;" href="#">

<img src="<?php echo $this->webroot?>images/add.png"> <?php echo __('Add fail-over Rule',true);?></a>
<?php }?></legend>
<center>
<table class="list list-form"  id="list_table">
<thead>
    <tr>
        <td width="10%"><?php echo __('Route Type',true);?></td>
        <td width="20%"> <?php echo __('Code',true);?></td>
         <td width="30%"><?php echo __('Response',true);?></td>
    
        <?php  if ($_SESSION['role_menu']['Routing']['gatewaygroups:view']['model_w']) {?><td width="10%" class="last"></td>
        <?php }?>
    </tr>
</thead>
<tbody class="rows" id="rows-ip">
	<?php  
		$size=count($host);
  	for($i=0;$i<$size;$i++){
  	?>
  <tr class="row-<?php echo  $i%2+1?>" id="row-<?php echo $i+1?>" style="">
  	<td style="width: 200px;" class="value">
      <input type="hidden" name="accounts[<?php echo $i+1?>][id]" id="ip-id-<?php echo $i+1?>" value="<?php echo $host[$i][0]['id']?>" class="input in-hidden">
				<?php 
    			$ii=$i+1;
    			if($type == 'ingress') {
    				$t=array('3'=>'Stop');
    			}	 else {
    			$t=array('1'=>'Fail to Next Host','2'=>'Fail to Next Trunk','3'=>'Stop');
    			}
    			echo $form->input('client_id',array('options'=>$t, 'rel'=>'stop_return','id'=>"ip-route_type-$ii",'name'=>"accounts[$ii][route_type]",'selected'=>$host[$i][0]['route_type'],
    			'style'=>'width: 180px;',
    			'label'=>false, 'class' =>'select' ,'div'=>false,'type'=>'select'));
    			?>
    </td>
    <td style="width: 350px;" class="value">
       <input type="text" value="<?php echo $host[$i][0]['reponse_code'];?>" class="input in-text" name="accounts[<?php echo $i+1?>][reponse_code]" id="ip-port-<?php echo $i+1?>" check="code"  maxLength='16'>
    </td>
    <td style="width: 350px;" class="value">
       <input type="text" value="<?php echo $host[$i][0]['return_code'];?>" style="width: 100px;<?php if($host[$i][0]['route_type']!=3){  echo "display:none";}?>"  class="input in-text" name="accounts[<?php echo $i+1?>][return_code]" id="ip-return_code-<?php echo $i+1?>" check="Num" maxLength='16'>
       <input type="text" value="<?php echo $host[$i][0]['return_string'];?>"  style="width: 150px;<?php if($host[$i][0]['route_type']!=3){  echo "display:none";}?>"  class="input in-text" name="accounts[<?php echo $i+1?>][return_string]" id="ip-return_string-<?php echo $i+1?>"  />
	  </td>
    <?php  if ($_SESSION['role_menu']['Routing']['gatewaygroups:view']['model_w']) {?><td style="width: 200px;" class="value last">
    		<a href="#" id="tpl-delete-row" rel="delete" value="<?php echo $host[$i][0]['reponse_code'] ?>" >
    			<img width="16" height="16" src="<?php echo $this->webroot?>images/delete.png">
    		</a>
    </td>
    <?php }?>
		</tr>
 <?php }?> 
</tbody>
<tbody>
<?php //  鐢╥d="tpl-ip"琛ㄧず  鍑嗗澶嶅埗鐨刪ang ?>
		<tr style="display:none;" id="tpl-ip" class="  row-2">
		    <td class="value"  style="width: 200px;">
		      <select style="width: 180px;"   rel='stop_return'    name="_accounts[%n][route_type]"   id="route_type0" class="netmask0 input in-select"   >
					<?php
							if($type == "ingress") 
							{
					?>
						<option value="3">Stop</option>
					<?php
						 } else {       
					 ?>
							<option value="1">Fail to Next Host</option>
							<option value="2">Fail to Next Trunk</option>
							<option value="3">Stop</option>
					<?php
						}
					?>
						</select>
		    </td>
		    <td class="value"  style="width: 350px;">
		    		<input type="text" name="_accounts[%n][reponse_code]" class="input in-text"  maxLength="16" check="code" value="">
		    </td>
		    <td class="value"  style="width: 350px;">
		    			<?php
		    						if($type=="ingress") {
		    				?>
		    				<input type="text"   style="width: 100px;display: inline;" name="_accounts[%n][return_code]" class="input in-text" check="Num" maxLength="16" value="">
		       <input type="text"  style="width: 150px;display: inline;" name="_accounts[%n][return_string]" class="input in-text"  value="">
		       				<?php
		       						} else {
		       				?>
		       <input type="text"   style="width: 100px;display: none;" name="_accounts[%n][return_code]" class="input in-text" check="Num" maxLength="16" value="">
		       <input type="text"  style="width: 150px;display: none;" name="_accounts[%n][return_string]" class="input in-text"  value="">
		  				<?php
		  						}
		  					?>
		    </td>
		    <td class="value last"  style="width: 200px;">
		    		<a href="#" rel="delete" id="tpl-delete-row"  >
		    			<img width="16" height="16" src="<?php echo $this->webroot?>images/delete.png">
		    		</a>
		    </td>
		</tr>
</tbody>
</table>
</center>
</fieldset>
<?php  if ($_SESSION['role_menu']['Routing']['gatewaygroups:view']['model_w']) {?><div id="form_footer">
        <input type="submit" value="<?php echo __('submit',true);?>" class="input in-submit">
       
   <input type="reset" value="<?php echo __('reset')?>"    class="input in-submit">
    </div><?php }?>
</form>
<script type="text/javascript" language="JavaScript">
    
	var lastId = '<?php  echo   $size;?>';
	var dr_groups = [];
	function addItem(type, row)
	{
	   lastId++;
	   if (!row || !row['id']) {
	     row = {
	       'auth_type': type,
	       'name': 'account_' + lastId,
	       'proxy_mode': '',
	       'orig_enabled': 1,
	       'term_enabled': 1
	        	};
	    	}
	   for (k in row) { 
		   if (row[k] == null){ 
			   row[k] = '';
			   } 
		   }
	   var tRow = $('#tpl-'+type).clone(true);//澶嶅埗鍑嗗濂界殑琛?
	   tRow.attr('id', 'row-'+lastId).show();//璁剧疆鏄剧ず
	   tRow.find('input,select').each(function () {
	      var el = $(this);//褰撳墠琛ㄥ崟鍏冪礌
	      var name = $(this).attr('name').substring(1).replace('%n', lastId);//璁剧疆鍚嶅瓧(灏嗗悕瀛椾腑鐨?n鏇挎崲涓簂astId)  accounts[6][id]
	      var field = name.substring(name.lastIndexOf('[')+1, name.length-1);  //id
	      el.attr('id', type+'-'+field+'-'+lastId);//璁剧疆id  ip-id-6
	      el.attr('name', name);
	      el.attr('style', $(this).attr('style'));
	      if (el.attr('type') == 'checkbox') {
	        if(field=='need_register'){
	        	 el.click(function () {
		        	 if($(this).attr("checked")==true){
		        	   $(this).attr("value",'true');
		        	 }else{
		            	$(this).attr("value",'false');
		            	   		  }
	        	    		 });
	  	    	   		}
	   			if (typeof(row[field]) == 'object') {
	         	el.attr('checked', jQuery.inArray(1*el.attr('value'), row[field]) != -1 ? 'checked' : '');
	         	el.attr('name', el.attr('name') + '[]');
	      		}else {
	         	el.attr('checked', row[field] ? 'checked' : '');
	            		}
	    		}else{
	        el.val(row[field]);
	        		}
	    	});
	   tRow.find('a[rel=delete]').click(function () {
	      $(this).closest('tr').remove(); //鎵惧埌浠栨渶闈犺繎鐨則r鍒犻櫎涔?
	      return false;
	    	});
	   buildParams(tRow);
	   if (row['id']) {
	      tRow.appendTo($('#rows-'+type));
	   }else {
	      tRow.prependTo($('#rows-'+type));//<tbody class="rows" id="rows-ip">  灏唗r鍔犲叆tbody
	    	}
	   if (!row['id']) {
	      initForms(tRow);
	      initList();
	    	}
	}
	function buildParams(row)
	{
	    var s = '';
	    if (row.find('input[name*=orig_capacity]').val()){ s += ' / OC: '+row.find('input[name*=orig_capacity]').val();}
	    if (row.find('input[name*=term_capacity]').val()) s += ' / TC: '+row.find('input[name*=term_capacity]').val();
	    if (row.find('select[name*=protocol]').val()) s += ' / '+row.find('select[name*=protocol] :selected').text();
	    if (row.find('input[name*=proxy_mode]').val()) s += ' / P: '+row.find('input[name*=proxy_mode]').val();
	    if (row.find('select[name*=id_dr_plans]').val()) s += '<br/>RP: '+row.find('select[name*=id_dr_plans] :selected').text();
	    if (row.find('select[name*=orig_rate_table]').val()) s += '<br/>Orig RT: '+row.find('select[name*=orig_rate_table] :selected').text();
	    if (row.find('select[name*=term_rate_table]').val()) s += '<br/>Term RT: '+row.find('select[name*=term_rate_table] :selected').text();
	    var dr_group = '';
	    row.find('input[name*=dr_groups]').each(function(){
	       if ($(this).attr('checked')) {
	         if (dr_group != ''){ 
	           dr_group += ', ';
	          			   }
	        	dr_group += dr_groups[$(this).val()];
	       	 		  }
	    	  });
	    if (dr_group != '') {
	      s += '<br/>G: ' + dr_group;
	    	  }
		  if (s.substring(0, 3) == ' / ') {
			  	s = s.substring(3);
			 }
	    if (s.substring(0, 5) == '<br/>') {
	       s = s.substring(5);
	    	 }
		 	 if (!s) {
	       s = '&mdash; &raquo;';
	    	}
	   row.find('#tpl-params-text').html(s);
	   return s;
	}
	function hideParams()
	{
	  $('.rows div.params-block:visible').hide().attr('id', '').each(function () {
	     buildParams($(this).parent().parent());
	    });
	}
	$('.rows #tpl-params-block div').live('click', function (e) {
	    e.stopPropagation();
	});
	$('.rows #tpl-params-block div a').live('click', function () {
	    hideParams();
	    return false;
	});
	$('.rows #tpl-params-link').live('click', function () {
	    var vis = 0;
	    var div = $(this).parent().find('div');
	    if (div.is(':visible')){
	     	vis = 1;
	     	  }
	    hideParams();
	    if (!vis) { 
	      div.attr('id', 'tooltip').show();
	      	 }
	    return false;
	});
	$('.rows').find('select[rel*=stop]').live('change',function(){
			var type=$(this).val();
			if(type==3){
				$(this).closest('tr').find('input[name*=return_code]').show();
				$(this).closest('tr').find('input[name*=return_string]').show();
			}else{
				$(this).closest('tr').find('input[name*=return_code]').hide();
				$(this).closest('tr').find('input[name*=return_string]').hide();
			}
	});
	$('.rows #tpl-delete-row').live('click', function () { 
		if(confirm("Are you sure to delete rule  "+jQuery(this).attr('value'))){
		var del_rate_id=$(this).closest('tr').find('input[name*=id]').val();
		if(del_rate_id!=null&& del_rate_id!=''){
			var del_val=$('#delete_rate_id').val()+","+del_rate_id;
			$('#delete_rate_id').val(del_val);
		}
	  $(this).closest('tr').remove();
	  return false;
	}
 });
	$(window).click(hideParams);
</script>
<script type="text/javascript">
  jQuery(document).ready(function(){
      
      
   	jQuery('input[check=Num]').xkeyvalidate({type:'Num'});
   	jQuery('input[check=code]').xkeyvalidate({type:'code'});
     jQuery('#hostForm').submit(function(){
       var null_flag=true;
       var flag=true;
       var error_flag =true;
       var arr = new Array();
       $('#list_table').find('input[check=code]:visible').each(function (){
         if($(this).val()==null||$(this).val()==''){
           null_flag=false;
           $(this).attr('class','invalid');
           				}
          arr.push($(this).val());
          if(/\D/.test(jQuery(this).val()) ){
              jQuery(this).addClass('invalid');
              error_flag =false;
                          }



          
            		});
        var arr2=$.uniqueArray(arr);
        if(arr.length!=arr2.length){
          $('#list_table').find('input[id^=ip-reponse_code]').each(function (){
            	flag=false;
            			});
          if(null_flag==true){
            jQuery.jGrowl('Reponse Code  happen  repeat.',{theme:'jmsg-error'});
            			}
         			}
		     if(!error_flag){
		    	 jQuery.jGrowl('Code, must be whole number!',{theme:'jmsg-error'});
		             }
        if(null_flag==false){
          jQuery.jGrowl('Reponse Code  is  not null.',{theme:'jmsg-error'});
                	}
					if(null_flag==true&&flag==true&&error_flag){
						return true;
					}else{
						return false;
					}
      		 });  
	 });
</script>
</div>
