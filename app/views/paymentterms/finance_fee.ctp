<?php 
//获取当月天数
# $numDay = date("t",mktime(0,0,0,date('m'),date('d'),date('Y')));
  $numDay=31;
  $arrayDay=array();
  for($i=1; $i<=$numDay;$i++){
  		if($i==1){
  			$arrayDay[1]='1 st';
  			}
  		if($i==2){
  			$arrayDay[2]='2 nd';
  			}
  		else{
  			$arrayDay[$i]=$i.' th';
  			}
       }
  $arrayWeekDay=Array(0=>'Sunday',1=>'Monday',2=>'Tuesday',3=>'Wendsday',4=>'Thursday',5=>'Friday',6=>'Saturday');
?>
<div id="title">
  <h1><?php echo __('Finance')?>&gt;&gt;<?php echo __('Finance Fee')?></h1>
  <?php echo $this->element("search")?>
  <?php $w = $session->read('writable');?>
  <ul id="title-menu">
  <?php  if ($_SESSION['role_menu']['Switch']['paymentterms']['model_w']) {?>
    
    	<li>
    		<?php echo $this->element("createnew",Array('url'=>'paymentterms/add_payment_term_exchange'))?>
    	</li>
    <?php }?>
  </ul>
</div>

<!--

<style type="text/css">
#usagebox {
    position:absolute;
    border:2px #0a0 solid;
    background-color:#F1F1F1;
    width:400px;
    height:200px;
    left:30%;
    top:30%;
}
#usagebox h1 {
    font-size: 16px;
    padding:5px;
    line-height: 16px;
}
#usagebox ol {
    overflow: auto;
    height:170px;
}
#usagebox ol li {
    list-style-type:decimal;
    margin-left:45px;
}
</style>

<div id="usagebox">
    <h1></h1>
    <ol>

    </ol>
</div>

<script type="text/javascript">
jQuery(function($){
    $('#usagebox').css({opacity:.8, display:'none'});
});
function getUsage(id, name) {
    $('#usagebox').show();
    $('#usagebox h1').text(name);
    $.ajax({
        url:'<?php echo $this->webroot ?>paymentterms/getuseage/' + id,
        type:'get',
        dataType:'json',
        success:function(data) {
            $('#usagebox ol').empty();
            $.each(data, function(index, value) {
                $('#usagebox ol').append('<li>'+value['name']+'</li>');
            });
        }
    });
}
</script>
-->

<div id="container">
<?php $d = $p->getDataArray();if (count($d) == 0) {?>
<div class="msg"  id="msg_div"><?php echo __('no_data_found')?></div>
<?php } else {
?>
<div class="msg"  id="msg_div"  style="display: none;"><?php echo __('no_data_found')?></div>
<?php }?>
<?php $d = $p->getDataArray();if (count($d) == 0) {?>
<div  id="list_div"  style="display: none;">
<?php } else {?>
<div id="list_div">
<?php }?>
<div id="toppage"></div>
<table class="list">

	<thead>
		<tr>
<!--		    <td><?php echo $appCommon->show_order('payment_term_id',__('ID',true))?></td>-->
		 		 <td><?php echo $appCommon->show_order('name','Payment Term Alias')?></td>
		   <td>Invoicing Cycle</td>
		    <td><?php echo $appCommon->show_order('grace_days','Grace Period(Days)')?></td>
		     <td><?php echo $appCommon->show_order('notify_days',' Notify(Days)')?></td>
		      <td><?php echo $appCommon->show_order('clients','Usage Count')?></td>
              <td><?php echo $appCommon->show_order('finance_rate',' Finance Rate')?></td>
		   <?php  if ($_SESSION['role_menu']['Switch']['paymentterms']['model_w']) {?> <td class="last"><?php echo __('action')?></td>
           <?php }?>
		</tr>
	</thead>
	<tbody id="producttab">
		<?php 
			$mydata =$p->getDataArray();
			$loop = count($mydata); 
			for ($i=0;$i<$loop;$i++) {?>
				<tr class="row-1" style="text-align: center;">
<!--		    <td  style="text-align:center;"><?php echo $mydata[$i][0]['payment_term_id']?></td>-->
		    <td style="font-weight: bold;"> 
							<?php echo $mydata[$i][0]['name']?>
			 	 </td>
		    <td><?php 
                                        
		    			if ($mydata[$i][0]['type'] == 1){ 
		    					echo str_replace('X',$mydata[$i][0]['days'],__('everyxdays',true));
		    					echo "&nbsp;&nbsp;&nbsp;";
		    					echo $mydata[$i][0]['days'];
                                                        echo "&nbsp;&nbsp;&nbsp;";
                                                        echo 'day(s)';
		    			}elseif($mydata[$i][0]['type']==2){
		    					//echo str_replace('X',$arrayDay[$mydata[$i][0]['days']],__('onxdayofmonth',true));
		    					echo "Every";
                                                        echo "&nbsp;&nbsp;&nbsp;";
		    					//echo $arrayDay[$mydata[$i][0]['days']];
                                                        $prDate = explode(' ',$arrayDay[$mydata[$i][0]['days']]);
                                                        echo $prDate[0]."<sup>".$prDate[1]."</sup>"."&nbsp;&nbsp;&nbsp;of&nbsp;&nbsp;&nbsp;the&nbsp;&nbsp;&nbsp;month";
                                                        
                                                        
		    			}elseif($mydata[$i][0]['type']==3){
		    					//echo str_replace('X',$arrayWeekDay[$mydata[$i][0]['days']],__('onxdayofweek',true));
		    					echo "Every";
                                                        echo "&nbsp;&nbsp;&nbsp;";
		    					echo $arrayWeekDay[$mydata[$i][0]['days']];
                                                        echo "&nbsp;&nbsp;&nbsp;";
                                                        echo "of"."&nbsp;&nbsp;&nbsp;"."the"."&nbsp;&nbsp;&nbsp;"."week";
		    			}else{
		    					//echo str_replace('X',$mydata[$i][0]['more_days'],__('someonxdayofmonth',true));
		    					
                                                        echo "Every";
                                                        echo "&nbsp;&nbsp;&nbsp;";
                                                        
                                                        $new_date = array();
                                                        $mydates_array = explode(',',$mydata[$i][0]['more_days']);
                                                        foreach($mydates_array as $key => $value){
                                                            $val_arr =  explode(' ',$arrayDay[$value]);
                                                            $new_date[$key] = $val_arr[0]."<sup>".$val_arr[1]."</sup>";
                                                        }
                                                        
                                                        echo implode(',',$new_date);
                                                        echo "&nbsp;&nbsp;&nbsp;of&nbsp;&nbsp;&nbsp;the&nbsp;&nbsp;&nbsp;month";
                                                        
                                                        
                                                        
		    				}
		    				?>
		    </td>
		    <td><?php echo $mydata[$i][0]['grace_days']?></td>
		    <td><?php echo $mydata[$i][0]['notify_days']?></td>
		    <td><a href="<?php echo $this->webroot ?>clients/index?adv_search=1&filter_payment_term_id=<?php echo $mydata[$i][0]['payment_term_id'];  ?>"><?php echo $mydata[$i][0]['clients']?></a></td>
            <td><?php 
					printf("%0.3f%%", $mydata[$i][0]['finance_rate']);  
				?></td>
            
		   <?php  if ($_SESSION['role_menu']['Switch']['paymentterms']['model_w']) {?>
            <td class="last">
		    		
		    		<a class="edit" title="<?php echo __('edit')?>"  href="<?php echo $this->webroot?>paymentterms/edit_payment_term_exchange/<?php echo $mydata[$i][0]['payment_term_id']?>" payment_term_id="<?php echo $mydata[$i][0]['payment_term_id']?>">
		    			<img src="<?php echo $this->webroot?>images/editicon.gif" />
		    		</a>
		    		<a title="<?php echo __('del')?>"  href="#" onclick="delConfirm_ex(this,'<?php echo $this->webroot?>paymentterms/del_term/<?php echo $mydata[$i][0]['payment_term_id']?>','<?php echo $mydata[$i][0]['name']?>');">
		    			<img src="<?php echo $this->webroot?>images/delete.png" />
		    		</a>
		    </td>
            <?php }?>
				</tr>
		<?php }?>		
	</tbody>
	<tbody>
</tbody>
</table>
<div id="tmppage">
<?php echo $this->element('page');?>
</div>
</div>
</div>
<script type="text/javascript"><!--
/*
 * 删除一行
 */
function delConfirm_ex(obj,url,name){
	if (confirm("Are you sure to delete  payment term "+name+"?")) {
		obj.href = url;
	}
}

function  validation_data(){
	 var ret =true;
	 //var grace_days = jQuery('#PaymenttermGraceDays').val();				
		if(jQuery('#PaymenttermGraceDays').val()!=''&&jQuery('#PaymenttermGraceDays')!=null){
			 if(/\D/.test(jQuery('#PaymenttermGraceDays').val())){
				      jQuery('#PaymenttermGraceDays').addClass('invalid');
				      jQuery.jGrowl("Grace Period(Days) , must be whole number! ",{theme:'jmsg-error'});
				      ret= false;
		      }
	   if(jQuery('#PaymenttermGraceDays').val()>90){
		     jQuery('#PaymenttermGraceDays').addClass('invalid');
	      jQuery.jGrowl("Grace Period (Days), required  0 to 90! ",{theme:'jmsg-error'});
	      ret= false;     
		    	      }
		    }
		else
		{
			jQuery('#PaymenttermGraceDays').addClass('invalid');
		   jQuery.jGrowl("Grace Period(Days) , can't be empty! ",{theme:'jmsg-error'});
		   ret= false;
		}
	 if(jQuery('#PaymenttermNotifyDays').val()!=''||jQuery('#PaymenttermNotifyDays')!=null){
		    if(/\D/.test(jQuery('#PaymenttermNotifyDays').val())){
			      jQuery('#PaymenttermNotifyDays').addClass('invalid');
			      jQuery.jGrowl(" Notify(Days), must be whole number! ",{theme:'jmsg-error'});
			      ret= false;
		          }
		  if(jQuery('#PaymenttermNotifyDays').val()>90){
		   jQuery('#PaymenttermNotifyDays').addClass('invalid');
		   jQuery.jGrowl("Notify(Days),required 0 to 90!",{theme:'jmsg-error'});
		   ret= false;     
			 }
	}


	 if(jQuery('#PaymenttermType').val()=='4'){
			if(jQuery('#PaymenttermDays2').val()==''||jQuery('#PaymenttermDays2')==null){
				   jQuery('#PaymenttermDays2').addClass('invalid');
   				   jQuery.jGrowl("Some Day of month,must be between 0 to 31!",{theme:'jmsg-error'});
      				ret= false;     

				}
			
          var arr=jQuery('#PaymenttermDays2').val().split(',');
          if(arr.length>=1){
   				 for(var i=0;i<arr.length;i++){  if(arr[i]>31){ 
     				   jQuery('#PaymenttermDays2').addClass('invalid');
       				   jQuery.jGrowl("Some Day of month,must be between 0 to 31!",{theme:'jmsg-error'});
          				ret= false;
          				break;

         } }; 

              }

		 }
	 
	 if(jQuery('#PaymenttermType').val()=='1'){
			if(jQuery('#PaymenttermDays4').val()!=''&&jQuery('#PaymenttermDays4')!=null){
		//		 if(jQuery('#PaymenttermDays2').val()>32){
		//			   jQuery('#PaymenttermDays2').addClass('invalid');
		//			   jQuery.jGrowl("Period,must be between 0 to 31!",{theme:'jmsg-error'});
		//			   ret= false;     
		//			}
			}
			else
			{
						jQuery('#PaymenttermDays2').addClass('invalid');
				   //jQuery.jGrowl("Period,can't be empty!",{theme:'jmsg-error'});
				   ret= false; 
			}
	 }
	
	return ret;
}


jQuery('#add').click(
	function(){
		jQuery('#list_div').show();
		jQuery('#msg_div').remove();
		var action=jQuery(this).attr('href');
		jQuery('table.list').trAdd(
			{
				'action':action,
				'ajax':'<?php echo $this->webroot?>paymentterms/js_save_1',
				'callback':function(options){
						jQuery('#PaymenttermType').change(function(){
								if(jQuery(this).val()==2){
                                                                    
                                                                        
									jQuery('#PaymenttermDays').show().attr('name','data[Paymentterm][days]');
									jQuery('#PaymenttermDays2').hide().attr('name','data[Paymentterm][days2]').val('');
									jQuery('#PaymenttermDays3').hide().attr('name','data[Paymentterm][days3]').val('');
                                                                        jQuery('#PaymenttermDays4').hide().attr('name','data[Paymentterm][days4]').val('');
								}else if(jQuery(this).val()==1){
                                                                        jQuery('#PaymenttermDays4').show().attr('name','data[Paymentterm][days]');
                                                                    
									jQuery('#PaymenttermDays2').hide().attr('name','data[Paymentterm][days]').val('');
									jQuery('#PaymenttermDays').hide().attr('name','data[Paymentterm][days2]').val('');
									jQuery('#PaymenttermDays3').hide().attr('name','data[Paymentterm][days3]').val('');
								}else if(jQuery(this).val()==3){
									jQuery('#PaymenttermDays3').show().attr('name','data[Paymentterm][days]');
									jQuery('#PaymenttermDays').hide().attr('name','data[Paymentterm][days1]').val('');
									jQuery('#PaymenttermDays2').hide().attr('name','data[Paymentterm][days2]').val('');
                                                                        jQuery('#PaymenttermDays4').hide().attr('name','data[Paymentterm][days4]').val('');
								}else{
									jQuery('#PaymenttermDays2').show().attr('name','data[Paymentterm][days]');
									jQuery('#PaymenttermDays').hide().attr('name','data[Paymentterm][days1]').val('');
									jQuery('#PaymenttermDays3').hide().attr('name','data[Paymentterm][days3]').val('');
                                                                        jQuery('#PaymenttermDays4').hide().attr('name','data[Paymentterm][days4]').val('');
								}
						}).change();
						jQuery('input[type=text],input[type=password]').addClass('input in-input in-text');
						jQuery('input[type=button],input[type=submit]').addClass('input in-submit');
						jQuery('select').addClass('select in-select');
						jQuery('textarea').addClass('textarea in-textarea');
				},
				'onsubmit':function(options){
					var re=true;
					var type=jQuery('#'+options.log).find('#PaymenttermType').val();
					var PaymenttermName=jQuery('#'+options.log).find('#PaymenttermName').val();
					var PaymenttermGraceDays=jQuery('#'+options.log).find('#PaymenttermGraceDays').val();
					var PaymenttermNotifyDays=jQuery('#'+options.log).find('#PaymenttermNotifyDays').val();
					var Days4=jQuery('#'+options.log).find('#PaymenttermDays4').val();
                                        var PaymenttermFinanceRate = jQuery('#'+options.log).find('#PaymenttermFinanceRate').val();
					if(type==1){
					if((Days4==null||Days4=='') && jQuery('#PaymenttermType').val()==1 ){
						jQuery.jGrowlError('Period must contain numeric characters only');
						jQuery('#'+options.log).find('#PaymenttermDays4').addClass('invalid');
						re=false;
						}
					}
					if(/\D/.test(Days4)  && jQuery('#PaymenttermType').val()==1){
						jQuery.jGrowlError(' Period must contain numeric characters only');
						jQuery('#'+options.log).find('#PaymenttermDays4').addClass('invalid');
						re=false;
					}

                                        re=validation_data();
                                        
                                        if((PaymenttermFinanceRate==null||PaymenttermFinanceRate=='')){
						jQuery.jGrowlError('Finance Rate contain numeric characters only');
						jQuery('#'+options.log).find('#Finance Rate').addClass('invalid');
						re=false;
                                        }
                                        
					if(/\D/.test(PaymenttermFinanceRate) ){
                                                    jQuery.jGrowlError('Finance Rate must contain numeric characters only');
						jQuery('#'+options.log).find('#PaymenttermFinanceRate').addClass('invalid');
						re=false;
					}
				/*if(/\D/.test(PaymenttermNotifyDays)){
						jQuery.jGrowlError('Grace Period must contain numeric characters only');
						jQuery('#'+options.log).find('#PaymenttermNotifyDays').addClass('invalid');
						re=false;
					}*/
					if(jQuery('#'+options.log).find('#PaymenttermType').val()==1 && /[^\d]/.test(jQuery('#'+options.log).find('#PaymenttermDays4').val()))
					{
						jQuery.jGrowlError('this is must number!');
						jQuery('#'+options.log).find('#PaymenttermDays4').addClass('invalid');
						re=false;
					}
					if(jQuery('#'+options.log).find('#PaymenttermType').val()==4 && /[^\d,]/.test(jQuery('#'+options.log).find('#PaymenttermDays2').val()))
					{
						jQuery.jGrowlError('this is must number!');
						jQuery('#'+options.log).find('#PaymenttermDays2').addClass('invalid');
						re=false;
                                                
					}
                                        
					if(jQuery('#'+options.log).find('#PaymenttermType').val()==1 && jQuery('#'+options.log).find('#PaymenttermDays4').val()>90){
						jQuery.jGrowlError('Grace Period is max 90');
						jQuery('#'+options.log).find('#PaymenttermDays4').addClass('invalid');
						re=false;
					} 
					if(PaymenttermName==null||PaymenttermName==''){
						jQuery.jGrowlError(' Payment name is required！');
						jQuery('#'+options.log).find('#PaymenttermName').addClass('invalid');
						return false;
					}
                                       
					var data=jQuery.ajaxData("<?php echo $this->webroot?>paymentterms/paymentterm_name?paymentterm_name="+PaymenttermName);
					if(!data.indexOf('false')){
						jQuery.jGrowlError(PaymenttermName+' name is already in use!');
						re=false;
                                                
					}
                                        
					if(/[^0-9A-Za-z-\_\s]+/.test(jQuery('#PaymenttermName').val())||jQuery('#PaymenttermName').val().length>100){
			    	     jQuery('#PaymenttermName').addClass('invalid');
					      jQuery.jGrowl(" Name, allowed characters: a-z,A-Z,0-9,-,_,space, maximum  of 100 characters in length.",{theme:'jmsg-error'});
			         re= false;
	                   
	       		    }           
					return re;
				}
			}
		);
		return false;
	}
);
jQuery('.edit').click(
	function(){
		var action=jQuery(this).attr('href');
		var payment_term_id=jQuery(this).attr('payment_term_id');
		
		jQuery(this).parent().parent().trAdd(
		{
				'action':action,
				'ajax':'<?php echo $this->webroot?>paymentterms/js_save_1?id='+payment_term_id,
				'saveType':'edit',
				'callback':function(options){
						jQuery('#PaymenttermType').change(function(){
							if(jQuery(this).val()==2){
                                                                    
                                                                        
									jQuery('#PaymenttermDays').show().attr('name','data[Paymentterm][days]');
									jQuery('#PaymenttermDays2').hide().attr('name','data[Paymentterm][days2]').val('');
									jQuery('#PaymenttermDays3').hide().attr('name','data[Paymentterm][days3]').val('');
                                                                        jQuery('#PaymenttermDays4').hide().attr('name','data[Paymentterm][days4]').val('');
								}else if(jQuery(this).val()==1){
                                                                        jQuery('#PaymenttermDays4').show().attr('name','data[Paymentterm][days]');
                                                                    
									jQuery('#PaymenttermDays2').hide().attr('name','data[Paymentterm][days]').val('');
									jQuery('#PaymenttermDays').hide().attr('name','data[Paymentterm][days2]').val('');
									jQuery('#PaymenttermDays3').hide().attr('name','data[Paymentterm][days3]').val('');
								}else if(jQuery(this).val()==3){
									jQuery('#PaymenttermDays3').show().attr('name','data[Paymentterm][days]');
									jQuery('#PaymenttermDays').hide().attr('name','data[Paymentterm][days1]').val('');
									jQuery('#PaymenttermDays2').hide().attr('name','data[Paymentterm][days2]').val('');
                                                                        jQuery('#PaymenttermDays4').hide().attr('name','data[Paymentterm][days4]').val('');
								}else{
									jQuery('#PaymenttermDays2').show().attr('name','data[Paymentterm][days]');
									jQuery('#PaymenttermDays').hide().attr('name','data[Paymentterm][days1]').val('');
									jQuery('#PaymenttermDays3').hide().attr('name','data[Paymentterm][days3]').val('');
                                                                        jQuery('#PaymenttermDays4').hide().attr('name','data[Paymentterm][days4]').val('');
								}
						}).change();
						jQuery('input[type=text],input[type=password]').addClass('input in-input in-text');
						jQuery('input[type=button],input[type=submit]').addClass('input in-submit');
						jQuery('select').addClass('select in-select');
						jQuery('textarea').addClass('textarea in-textarea');
				},
				'onsubmit':function(options){
					var re=true;
					var PaymenttermName=jQuery('#'+options.log).find('#PaymenttermName').val();
					var PaymenttermGraceDays=jQuery('#'+options.log).find('#PaymenttermGraceDays').val();
					var PaymenttermNotifyDays=jQuery('#'+options.log).find('#PaymenttermNotifyDays').val();
					var PaymenttermPaymentTermId=jQuery('#'+options.log).find('#PaymenttermPaymentTermId').val();
			
					if(jQuery('#'+options.log).find('#PaymenttermType').val()==1 && /[^\d]/.test(jQuery('#'+options.log).find('#PaymenttermDays4').val()))
					{
						jQuery.jGrowlError('this is must number!');
						jQuery('#'+options.log).find('#PaymenttermDays4').addClass('invalid');
						re=false;
					}
					if(jQuery('#'+options.log).find('#PaymenttermType').val()==4 && /[^\d,]/.test(jQuery('#'+options.log).find('#PaymenttermDays2').val()))
					{
						jQuery.jGrowlError('this is must number!');
						jQuery('#'+options.log).find('#PaymenttermDays2').addClass('invalid');
						re=false;
					}
					if(jQuery('#'+options.log).find('#PaymenttermType').val()==1 && jQuery('#'+options.log).find('#PaymenttermDays4').val()>90){
						jQuery.jGrowlError('Grace Period is max 90');
						jQuery('#'+options.log).find('#PaymenttermDays4').addClass('invalid');
						re=false;
					}
					var Days4=jQuery('#'+options.log).find('#PaymenttermDays4').val();
					if(Days4==null||Days4=='' && jQuery('#PaymenttermType').val()==1){
						jQuery.jGrowlError('Period must contain numeric characters only');
						jQuery('#'+options.log).find('#PaymenttermDays4').addClass('invalid');
						re=false;
					}
					if(jQuery.ajaxData('<?php echo $this->webroot?>paymentterms/checkName?name='+PaymenttermName+'&id='+PaymenttermPaymentTermId)=='false')
					{
						jQuery.jGrowlError(PaymenttermName+' name is already in use!');
						jQuery('#'+options.log).find('#PaymenttermName').addClass('invalid');
						re= false;
					}
					if(/[^0-9A-Za-z-\_\s]+/.test(jQuery('#PaymenttermName').val())||jQuery('#PaymenttermName').val().length>100){
			    	     jQuery('#PaymenttermName').addClass('invalid');
					      jQuery.jGrowl(" Name, allowed characters: a-z,A-Z,0-9,-,_,space, maximum  of 100 characters in length.",{theme:'jmsg-error'});
			         re= false;
	                   
	       		    }
					re=validation_data();
					return re;
				}
		});
		return false;
	}
);

--></script>
</div>
