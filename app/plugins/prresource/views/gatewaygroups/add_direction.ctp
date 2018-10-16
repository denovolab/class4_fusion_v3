<div id="title">
  <h1>
      <?php echo __('Carrier',true);?>[<?php echo $client_name ?>]&gt;&gt; <?php echo __($smaill_title. ' Trunk',true);?><?php echo  $this->element('title_name',array('name'=>$name));?>&gt;&gt; 
 		 <?php __('Action')?>
  </h1>
  <ul id="title-menu">
			<li>
    		<a class="link_back" href="javascript:history.back()">
    			<img width="16" height="16" src="<?php echo $this->webroot?>images/icon_back_white.png" alt="">
    			&nbsp;<?php echo __('goback',true);?>
    		</a>
    	</li>
  </ul>
</div>
<div id="container">
		
    <?php if(	 $gress=='ingress'){?>
<?php echo $this->element("ingress_tab",array('active_tab'=>'action'))?>
    <?php }else{?>
    <?php echo $this->element("egress_tab",array('active_tab'=>'action'))?>

    <?php }?>
  
<form action="<?php echo $this->webroot ?>prresource/gatewaygroups/add_direction_post/<?php  if(!empty($resource_id)){echo  $resource_id ;}else{  echo '';}?>"   method="post"  id="trans_form">
<?php echo  $appGetewaygroup->echo_resource_hidden($resource_id,$gress);?>
<fieldset>
<legend>
<?php  if ($_SESSION['role_menu']['Routing']['gatewaygroups:view']['model_w']) {?><a onclick="addItem('ip');return false;" href="#">
<img src="<?php echo $this->webroot?>images/add.png"> <?php echo __('add',true);?> <?php __('Action')?></a>
<?php }?></legend>
<table class="list list-form"   id="list_table" >
<thead>
    <tr>
        <td width="12%"><?php __('timeprofile')?></td>
        <td width="8%"><?php __('Target')?></td>
        <td width="8%"><?php __('code')?></td>
        <td width="8%"><?php __('action')?></td>
        <td width="8%"><?php __('Chars to Add')?></td>
        <td width="8%"><?php __('Num of chars to Del')?></td>
        <td width="8%"><?php __('numbertype')?></td>
        <td width="8%"><?php __('numberlength')?></td>
       <?php  if ($_SESSION['role_menu']['Routing']['gatewaygroups:view']['model_w']) {?> 
        <td width="8%" class="last">&nbsp;</td>
       <?php }?>
    </tr>
</thead>
<tbody class="rows" id="rows-ip">
<?php  $size=count($host);
  for($i=0;$i<$size;$i++){?>
  	<?php $iadd=$i+1?>
  	<tr class="row-<?php echo  $i%2+1?>" id="row-<?php echo $i+1?>" style="">
    <td style="width: 200px;" class="value">
       <input type="hidden" name="accounts[<?php echo $i+1?>][id]" id="ip-id-<?php echo $i+1?>" value="<?php echo $host[$i][0]['direction_id']?>" class="input in-hidden">
    			<?php 
    			$ii=$i+1;
    		
    			echo $form->input('client_id',array('options'=>$timepro,  'id'=>"ip-time_profile_id-$ii",'name'=>"accounts[$ii][time_profile_id]",'selected'=>$host[$i][0]['time_profile_id'],
    			'style'=>'205px;',
    			'label'=>false, 'class' =>'select' ,'div'=>false,'type'=>'select'));?>
    </td>
    <td style="width: 350px;" class="value">
				<?php 
    			$t=array('0'=>__('ani',true),'1'=>__('dnis',true));
    			echo $form->input('client_id',array('options'=>$t,  'id'=>"ip-type-$ii",'name'=>"accounts[$ii][type]",'selected'=>$host[$i][0]['type'],
    			'style'=>'205px;',
    			'label'=>false, 'class' =>'select' ,'div'=>false,'type'=>'select'));
    			?>
    </td>
    <td style="width: 300px;" class="value"><input type="text"  mycheck="num_code"   value="<?php echo $host[$i][0]['dnis']?>"  class="input in-text" name="accounts[<?php echo $i+1?>][dnis]" id="ip-dnis-<?php echo $i+1?>" ></td>
    <td style="width: 350px;" class="value">
				<?php 
    			$t=array('1'=>__('AddPrefix',true),'3'=>__('DelPrefix',true),'2'=>__('Addsuffix',true),'4'=>__('Delsuffix',true));
    			echo $form->input('client_id',array('options'=>$t,  'id'=>"ip-action-$ii",'name'=>"accounts[$ii][action]",'selected'=>$host[$i][0]['action'],
    			'style'=>'205px;','onchange'=>'PrefixChange(this)',
    			'label'=>false, 'class' =>'select' ,'div'=>false,'type'=>'select'));
    			?>
    </td>
    <td style="width: 300px;" class="value"><input id="digits" type="text" check="MyNum" maxLength="16"   value="<?php echo $host[$i][0]['digits']?>"  class="input in-text" name="accounts[<?php echo $i+1?>][digits]" id="ip-digits-<?php echo $i+1?>"></td>
    <td><?php echo $xform->input('deldigits',Array('name'=>"accounts[{$iadd}][deldigits]",'selected'=>$host[$i][0]['digits'],'options'=>Array(''=>'',1=>1,2=>2,3=>3,4=>4,5=>5,6=>6,7=>7,8=>8,9=>9, 10=>10, 11=>11, 12=>12, 13=>13, 14=>14, 15=>15, 16=>16, 17=>17, 18=>18, 19=>19, 20=>20)))?></td>
    <td style="width: 350px;" class="value">
    <?php 
    		$t=array('0'=>'all','1'=>'>','2'=>'=','3'=>'<');
    		echo $form->input('number_type',array('options'=>$t,  'id'=>"ip-number_type-$ii",'name'=>"accounts[$ii][number_type]",'selected'=>$host[$i][0]['number_type'],
    		'style'=>'205px;',
    		'label'=>false, 'class' =>'select' ,'div'=>false,'type'=>'select', 'class'=>'number_type'));
    		?>
     </td>
     <td style="width: 300px;" class="value">
        <input type="text" check='Num' value="<?php echo $host[$i][0]['number_length']?>"  class="input in-text" name="accounts[<?php echo $i+1?>][number_length]" id="ip-number_length-<?php echo $i+1?>" check="Num">
     </td>
    	<?php  if ($_SESSION['role_menu']['Routing']['gatewaygroups:view']['model_w']) {?>
        <td style="width: 200px;" class="value last">
            
            <a href="###" class="sortup">
                <img src="<?php echo $this->webroot ?>images/sort-up.gif" />
            </a>
            <a href="###" class="sortdown">
                <img src="<?php echo $this->webroot ?>images/sort-down.gif" />
            </a>
    			<a href="#" rel="delete"  onclick="if(confirm('Are you sure do this?'))  $(this).closest('tr').remove();">
    					<img width="16" height="16" src="<?php echo $this->webroot?>/images/delete.png">
    			</a>
    	</td>
        <?php }?> 
		</tr>
  	
  	
  	
 <?php }?> 
<?php //  鐢╥d="tpl-ip"琛ㄧず  鍑嗗澶嶅埗鐨刪ang ?>
	 <tr  style="display:none" id="tpl-ip" class="  row-2">
    <td class="value"  style="width: 200px;">
       	<?php 
      			echo $form->input('client_id',array('options'=>$timepro,'name'=>"_accounts[%n][time_profile_id]",'style'=>'205px;',
    				'label'=>false, 'class' =>'select' ,'div'=>false,'type'=>'select'));
      				?>
    </td>
    <td class="value"  style="width: 300px;">
		     <?php 
		    			$t=array('0'=>__('ani',true),'1'=>__('dnis',true));
		    			echo $form->input('client_id',array('options'=>$t, 'name'=>"_accounts[%n][type]",
		    			'style'=>'205px;',
		    			'label'=>false, 'class' =>'select' ,'div'=>false,'type'=>'select'));
		    		?>
    	</td>
     <td style="width: 300px;" class="value"><input type="text"  class="input in-text" name="_accounts[%n][dnis]"  mycheck="num_code"  ></td>
     <td style="width: 350px;" class="value">
					<?php 
		    	  	$t=array('1'=>__('AddPrefix',true),'3'=>__('DelPrefix',true),'2'=>__('Addsuffix',true),'4'=>__('Delsuffix',true));
		    			echo $form->input('client_id',array('options'=>$t,  'name'=>"_accounts[%n][action]",
		    			'style'=>'205px;','onchange'=>'PrefixChange(this)',
		    			'label'=>false, 'class' =>'select' ,'div'=>false,'type'=>'select'));
		    		?>
     </td>
     <td class="value"  style="width: 300px;"><input id="digits" type="text" name="_accounts[%n][digits]" class="input in-text" check="MyNum"></td>
     <td><?php echo $xform->input('deldigits',Array('name'=>"_accounts[%n][deldigits]",'disabled'=>'disabled','options'=>Array(''=>'',1=>1,2=>2,3=>3,4=>4,5=>5,6=>6,7=>7,8=>8,9=>9, 10=>10, 11=>11, 12=>12, 13=>13, 14=>14, 15=>15, 16=>16, 17=>17, 18=>18, 19=>19, 20=>20)))?></td>
     <td style="width: 350px;" class="value">
					<?php 
			    		$t=array('0'=>'all','1'=>'>','2'=>'=','3'=>'<');
			    		echo $form->input('client_id',array('options'=>$t,  'name'=>"_accounts[%n][number_type]",
			    		'style'=>'205px;',
			    		'label'=>false, 'class' =>'number_type' ,'div'=>false,'type'=>'select','check'=>'Num'));
			    	?>
     </td>
	    <td class="value"  style="width: 300px;"><input type="text" name="_accounts[%n][number_length]" class="input in-text" check="Num"></td>
	    <?php  if ($_SESSION['role_menu']['Routing']['gatewaygroups:view']['model_w']) {?>
            <td class="value last"  style="width: 200px;">
                <a href="###" class="sortup">
                <img src="<?php echo $this->webroot ?>images/sort-up.gif" />
            </a>
            <a href="###" class="sortdown">
                <img src="<?php echo $this->webroot ?>images/sort-down.gif" />
            </a>
                <a rel="delete" href="#"><img width="16" height="16" src="<?php echo $this->webroot?>/images/delete.png"></a></td>
        <?php }?>
			</tr>
</tbody>
</table>
</fieldset>
<?php  if ($_SESSION['role_menu']['Routing']['gatewaygroups:view']['model_w']) {?>
<div id="form_footer">
        <input type="submit" value="<?php __('Submit')?>"  class="input in-submit">
        
   <input type="reset" value="<?php echo __('Reset')?>"    class="input in-submit">
    </div><?php }?>
</form>
<script type="text/javascript">
jQuery('select[id^=ip-action]').each(function(){jQuery(this).change()}); 
	function PrefixChange(obj){
	  if(jQuery(obj).val()==3 || jQuery(obj).val()==4){
		  	jQuery(obj).parent().parent().find('input[id^=ip-digits],input[id=digits]').attr('disabled','disabled').val('');
		  	jQuery(obj).parent().parent().find('select[id^=ip-deldigits],select[id=deldigits]').removeAttr('disabled').find('option[value=]').remove();
	  }else{
		  	jQuery(obj).parent().parent().find('input[id^=ip-digits],input[id=digits]').removeAttr('disabled');
		  	jQuery(obj).parent().parent().find('select[id^=ip-deldigits],select[id=deldigits]').attr('disabled','disabled').append('<option/>').val('');
	  	}
	}
</script>
<script type="text/javascript" language="JavaScript">
//<![CDATA[
var pro_arr = new Array();
function check_sel()
{
	var ret = true;
$("#rows-ip tr").each(function(i){
		if ($("#ip-time_profile_id-"+(i+1)).val()==undefined)
		{
		}
		else
		{
			var this_selval = $("#ip-time_profile_id-"+(i+1)).val();		
			$.grep(pro_arr,function(val,key){
			//杩囨护鍑芥暟鏈変袱涓弬鏁?绗竴涓负褰撳墠鍏冪礌,绗簩涓负鍏冪礌绱㈠紩
			  	if(val==this_selval){
			  		$("#ip-time_profile_id-"+(i+1)).addClass('invalid');
			  		$("#ip-time_profile_id-"+(key+1)).addClass('invalid');
			  		alert('Time profile');
			  		ret = false;
				}
			});
			pro_arr.push(this_selval);
			//alert($("#ip-time_profile_id-"+(i+1)).val());
		}
	});
	return ret;
}


jQuery(document).ready(function(){
    jQuery('#trans_form').submit(function(){
          var flag=true;
          var arr = new Array();
          var isRepeat=false;
          jQuery('input[mycheck=num_code]:visible').each(function(){
              var code = jQuery(this).val();
              if(code!=''){
                 if(/[^0-9\+\*\#\.\-]/.test(code)){
			              jQuery.jGrowl('Code, must be number or +-*#! ',{theme:'jmsg-error'});
			          	    flag=false; 
                                               }
                                        }
                             });
           jQuery('input[id*=ip-number_length]:visible').each(function(){
                 var num_length = jQuery(this).val();
                 if(/\D/.test(num_length)){
                	 jQuery.jGrowl('Number Length, must be whole number!',{theme:'jmsg-error'});
		          	    flag=false; 
                                              }
               
                               });
                         
          
        //   jQuery('#digits').val();

           if( jQuery('#digits').val()!=''){
               if(/[^0-9\+\*\#\.\-]/.test( jQuery('#digits').val())){
			              jQuery.jGrowl('Chars to Add, must be number or +-*#! ',{theme:'jmsg-error'});
			          	    flag=false; 
                                             }
                                      }
           jQuery('input[id*=ip-digits-]').each(function(){
                var code_to_add = jQuery(this).val();
                if(code_to_add!=''){
                    if(/[^0-9\+\*\#\.\-]/.test(code_to_add)){
   			              jQuery.jGrowl('Chars to Add, must be whole number! ',{theme:'jmsg-error'});
   			          	    flag=false; 
                                                  }
                                           }

                              })

          
      		 jQuery('#rows-ip').find('tr').each(function(){
					   var time_profile_id=jQuery(this).find('select[id^=ip-time_profile_id]').val();
							var type=jQuery(this).find('select[id^=ip-type]').val();
							var dnis=jQuery(this).find('select[id^=ip-dnis]').val();
							for(var i in arr){
								if(arr[i].time_profile_id==time_profile_id && arr[i].type==type && arr[i].dnis==dnis){
									//isRepeat=true;
									break;
								}
                                     
                               

							}
							arr.push({time_profile_id:time_profile_id,type:type,dnis:dnis});
          			});
  				if(isRepeat){
                	jQuery.jGrowl('Time Profile  Happen  Repeat.',{theme:'jmsg-error'});
                 	flag=false;
  				}
      		return flag;
      	});  
});
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
    // fix row values
    for (k in row) { if (row[k] == null) row[k] = ''; }
    // prepare row
    var tRow = $('#tpl-'+type).clone(true);//澶嶅埗鍑嗗濂界殑琛?
    tRow.attr('id', 'row-'+lastId).show();//璁剧疆鏄剧ず

    // set names / values寰幆琛屽唴鐨勬瘡涓〃鍗曞厓绱?
    tRow.find('input,select').each(function () {
        var el = $(this);//褰撳墠琛ㄥ崟鍏冪礌
        //鍑嗗琛岀殑鍚嶅瓧  _accounts[%n][id]  鏇挎崲涓篴ccounts[6][id]
        var name = $(this).attr('name').substring(1).replace('%n', lastId);//璁剧疆鍚嶅瓧(灏嗗悕瀛椾腑鐨?n鏇挎崲涓簂astId)  accounts[6][id]
        var field = name.substring(name.lastIndexOf('[')+1, name.length-1);  //id
        el.attr('id', type+'-'+field+'-'+lastId);//璁剧疆id  ip-id-6
        el.attr('name', name);

//        瀵筩heckbox鐨勫鐞?
        if (el.attr('type') == 'checkbox') {
//缁檆heckbox娉ㄥ唽浜嬩欢
        if(field=='need_register'){
        	    el.click(function () {
        	    	if($(this).attr("checked")==true){
        	    		$(this).attr("value",'true');}else{$(this).attr("value",'false');
        	    					}
        	    				  });
            		}

            
            if (typeof(row[field]) == 'object') {
                el.attr('checked', jQuery.inArray(1*el.attr('value'), row[field]) != -1 ? 'checked' : '');
                el.attr('name', el.attr('name') + '[]');
            } else {
                el.attr('checked', row[field] ? 'checked' : '');
            				  }
        } else {
            el.val(row[field]);
        			}
    });
    // remove of the row  缁欏垹闄ょ敤鐨勫浘鐗囬摼鎺ユ敞鍐屼簨浠?
    tRow.find('a[rel=delete]').click(function () {
        $(this).closest('tr').remove(); //鎵惧埌浠栨渶闈犺繎鐨則r鍒犻櫎涔?
        return false;
   		  });
    buildParams(tRow);
    if (row['id']) {
        tRow.appendTo($('#rows-'+type));
    } else {
        tRow.prependTo($('#rows-'+type));//<tbody class="rows" id="rows-ip">  灏唗r鍔犲叆tbody
          }
    // styles
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
    row.find('input[name*=dr_groups]').each(function() {
        if ($(this).attr('checked')) {
            if (dr_group != '') dr_group += ', ';
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

//live event handlers
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
    if (div.is(':visible')) vis = 1;
    hideParams();
    if (!vis) {
        div.attr('id', 'tooltip').show();
    }
    return false;
});
$('.rows #tpl-delete-row').live('click', function () {
    $(this).closest('tr').remove();
    return false;
});
$(window).click(hideParams);

    addItem('name', {"id":33066,"name":"account_3","ips":null,"tech_prefix":null,"password":null,"id_voip_hosts":null,"proxy_mode":null,"auth_type":"name","ani":null,"accname":"account_3","protocol":null,"port":null,"orig_enabled":true,"term_enabled":true,"orig_capacity":null,"term_capacity":null,"orig_rate_table":null,"term_rate_table":null,"id_dr_plans":null,"dr_groups":[]});
    addItem('ani', {"id":33067,"name":"account_4","ips":null,"tech_prefix":null,"password":null,"id_voip_hosts":22,"proxy_mode":null,"auth_type":"ani","ani":null,"accname":null,"protocol":null,"port":null,"orig_enabled":true,"term_enabled":true,"orig_capacity":null,"term_capacity":null,"orig_rate_table":null,"term_rate_table":null,"id_dr_plans":null,"dr_groups":[]});


//]]>
</script>
</div>
<script type="text/javascript">
<!--

jQuery.xkeyvalidatesfuns.MyNum=function(that,obj){
	var value=jQuery(that).val();
	var re=true;
	while(/[^-\.\#\+\*\d]/g.test(value))
	{
		value=value.replace(/[^-\.\#\+\*\d]/g,'');
		re=false;
	}
	jQuery(that).val(value);
	return re;
}
jQuery.fn.xkeyvalidatesfuns.MyNum=function(that,obj){
	jQuery(that).each(function(){
		jQuery(this).keyup(function(e){
			if(e.which==37 || e.which==38 || e.which==39 || e.which==40){return;}
			jQuery.xkeyvalidatesfuns.MyNum(jQuery(this),obj);
		});
	});
}

  jQuery(document).ready(function(){
      jQuery('input[check=Num]').xkeyvalidate({type:'Num'}).attr('maxLength','10')
   
     });     

  jQuery(document).ready(function(){
      jQuery('input[check=MyNum]').xkeyvalidate({type:'MyNum'}).attr('maxLength','10')
   
     });     

jQuery(document).ready(function(){
	jQuery('#checkForm').submit(function(){
		   te=true;
		   re=true;
      jQuery('input[check=Num]').map(function(){
    	    if(/\D/.test(jQuery(this).val())){
   	    	  jQuery(this).addClass('invalid');
	          jQuery.jGrowl('must contain numeric characters only.',{theme:'jmsg-error'});
	          te = false ;
                        }
                 }); 
      jQuery('input[check=MyNum]').map(function(){
    	    if(!/[^-\.\#\+\*\d]/g.test(jQuery(this).val())){
   	    	  jQuery(this).addClass('invalid');
  	          jQuery.jGrowl('must contain numeric characters only.',{theme:'jmsg-error'});
  	          te = false ;
                        }
                 }); 
      	re = check_sel();
      	if (re==false)
      	{
          	te=false;
      	}
       return te;                   
	});
})



//-->
</script>
<script type="text/javascript">
$(function() {
    $('.sortup').click(function() {
        var $tr = $(this).parent().parent();
        if($tr.prevAll().length == 0) {
            showMessages("[{'field':'','code':'101','msg':'Sorry,could not  move! '}]");
        } else {
           $tr.insertBefore($tr.prev()); 
        }
    });
    
    $('.number_type').change(function() {
        var $this = $(this);
        if ($this.val() == '0')
        {
            $this.parent('td').next().find('input').hide();
        }
        else
        {
            $this.parent('td').next().find('input').show();
        }
            
    }).trigger('change');
    
    $('.sortdown').click(function() {
        var $tr = $(this).parent().parent();
        if($tr.nextAll().length == 0) {
            showMessages("[{'field':'','code':'101','msg':'Sorry,could not  move! '}]");
        } else {
           $tr.insertAfter($tr.next()); 
        }
    });
});
</script>