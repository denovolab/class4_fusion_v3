<style type="text/css">
.resetdiv {
  background-color: #FFFFFF;
  border: 1px solid #999999;
  font-size: 12px;
  font-weight: bold;
  line-height: 30px;
  margin-left: -250px !important;
  margin-top: -180px !important;
  position: fixed !important;
  text-align: center;
}    
</style>

<div id="title">
 <h1><?php  __('Management')?> &gt;&gt;<?php echo __('client')?></h1>
        <ul id="title-search">
            <form method="get" id="myform1">
                <li>
                    <span style="float:left; height:25px; line-height:25px; font-size:14px; color:#EEEEEE; font-weight:bold;">Client Type:</span>
                    <select id="filter_client_type" name="filter_client_type" class="input in-select select">
                        <option value="0" <?php echo $common->set_get_select('filter_client_type', 0) ?>>All</option>
                        <option value="1" <?php echo $common->set_get_select('filter_client_type', 1, TRUE) ?>>All Active Clients</option>
                        <option value="2" <?php echo $common->set_get_select('filter_client_type', 2) ?>>All Inactive Clients</option>
                    </select>	
                </li>
                <li> 
                    <input type="text" id="search-_q" class="in-search default-value input in-text defaultText in-input" title="Search" value="Search" name="search">
                </li>
                    <input type="submit" id="submit" class="search_submit input in-submit" value="" name="submit">
            </form>
        </ul>
	<ul id="title-menu">
	<?php 
	$login_type=$_SESSION['login_type'];
	if($login_type==1){?>
	<?php  if ($_SESSION['role_menu']['Management']['clients']['model_w']) {?>
            
            <!--<li>
	    	<a class="link_btn" href="<?php echo $this->webroot?>ratemailhistorys">
	    		<?php echo __('Rate Amendment History',true);?>
	    	</a>
	    </li>-->
            
		<li>
	 		<?php echo $this->element("createnew",Array('url'=>'clients/add'))?>
	 	</li>
	 	<?php }?>
	
		<?php if($this->params['hasGet']){?>
	    <li>
	    	<a class="link_back" href="<?php echo $this->webroot?>clients/index">
	    		<img width="16" height="16"  alt="" src="<?php echo $this->webroot?>images/icon_back_white.png">&nbsp;<?php echo __('goback',true);?>
	    	</a>
	    </li>
	    <?php }?>
	    <?php  if ($_SESSION['role_menu']['Management']['clients']['model_x']) {?>
	    <li>
       		<a class="list-export" href="<?php echo $this->webroot?>downloads/carrier"><!--<img width="16" height="16" alt="" src="<?php echo $this->webroot?>images/export.png">--> Export</a>
      </li>
      <li>
            <a href="<?php echo $this->webroot?>clients/upload_reset_balance" title="Upload" class="link_btn">
                <img width="16" height="16" src="<?php echo $this->webroot?>images/upload_icon.png" alt="">Upload Reset Balance           
            </a>
        </li>
     <?php  }?>
       	<?php }?>
	</ul>
</div>

<div id="container">
    <?php
    	$is_exchange = Configure::read('system.type') === 2 ? TRUE : FALSE;
        $data =$p->getDataArray();
    ?>
    <div id="toppage"></div>
    <table class="list">
        <thead>
            <tr>
                <td><?php echo $appCommon->show_order('name',__('Name',true));?></td>
                <td>Last Login Time</td>
                <td colspan="3"><?php echo __('Mutual Balance',true);?></td>
                <td colspan="3"><?php echo __('Available Balance',true);?></td>
                <td><?php echo $appCommon->show_order('allowed_credit',__('Credit Limit',true))?></td>
                <td><?php echo $appCommon->show_order('egress_count',__('egress',true));?></td>
                <td><?php echo $appCommon->show_order('ingress_count', __('ingress',true));?></td>
                <td><?php echo $appCommon->show_order('update_at', __('Update At',true));?></td>
                <td><?php echo $appCommon->show_order('update_by', __('Update By',true));?></td>
                <td class="last"><?php echo __('action')?></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td><?php echo __('Ingress',true);?></td>
                <td><?php echo __('Egress',true);?></td>
                <td><?php echo __('Total',true);?></td>
                <td><?php echo __('Ingress',true);?></td>
                <td><?php echo __('Egress',true);?></td>
                <td><?php echo __('Balance',true);?></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td class="last"></td>
            </tr>
        </thead>
        <tbody>
            <?php foreach($data as $item): ?>
            <tr style="<?php if($item[0]['status'] == 0) echo 'background:#ccc;';?>">
                <td><a  href="<?php echo $this->webroot?>clients/edit/<?php echo $item[0]['client_id']?>" style="width:100%;display:block;"><?php echo $item[0]['name']; ?></a></td>
                <td><a  href="<?php echo $this->webroot?>clients/edit/<?php echo $item[0]['client_id']?>" style="width:100%;display:block;"><?php echo $item[0]['last_login_time']; ?></a></td>
                <td>
                    <a href="<?php echo $this->webroot?>finances/get_mutual_ingress_detail/<?php echo $item[0]['client_id']?>">
                    <?php echo $item[0]['mutual_ingress_balance'] < 0 ? '('.str_replace('-','',number_format($item[0]['mutual_ingress_balance'], 3)).')' : number_format($item[0]['mutual_ingress_balance'], 3); ?>
                    </a>
                </td>
                <td>
                    <a href="<?php echo $this->webroot?>finances/get_mutual_egress_detail/<?php echo $item[0]['client_id']?>">
                    <?php echo $item[0]['mutual_egress_balance'] < 0 ? '('.str_replace('-','',number_format($item[0]['mutual_egress_balance'], 3)).')' : number_format($item[0]['mutual_egress_balance'], 3); ?></td>
                    </a>
                <td>
                    <a href="<?php echo $this->webroot?>finances/get_mutual_ingress_egress_detail/<?php echo $item[0]['client_id']?>">
                     <?php echo $item[0]['mutual_total_balance'] < 0 ? '('.str_replace('-','',number_format($item[0]['mutual_total_balance'], 3)).')' : number_format($item[0]['mutual_total_balance'], 3); ?></td>
                    </a>
                </td>
                <td>
                    <a href="<?php echo $this->webroot?>finances/get_actual_ingress_detail/<?php echo $item[0]['client_id']?>">
                    <?php echo $item[0]['ingress_balance'] < 0 ? '('.str_replace('-','',number_format($item[0]['ingress_balance'], 3)).')' : number_format($item[0]['ingress_balance'], 3); ?>
                    </a>
                </td>
                <td>
                    <a href="<?php echo $this->webroot?>finances/get_actual_egress_detail/<?php echo $item[0]['client_id']?>">
                    <?php echo $item[0]['egress_balance'] < 0 ? '('.str_replace('-','',number_format($item[0]['egress_balance'], 3)).')' : number_format($item[0]['egress_balance'], 3); ?>
                    </a>
                </td>
                <td>
                    <a href="<?php echo $this->webroot?>finances/get_actual_ingress_egress_detail/<?php echo $item[0]['client_id']?>">
                    <?php echo $item[0]['balance'] < 0 ? '('.str_replace('-','',number_format($item[0]['balance'], 3)).')' : number_format($item[0]['balance'], 3); ?>
                    </a>
                </td>
                <td><?php echo number_format(abs($item[0]['allowed_credit']), 3); ?></td>
                <td><a class='egress_count' style="width:100%;display:block;" href='<?php echo $this->webroot?>prresource/gatewaygroups/view_egress?query[id_clients]=<?php echo $item[0]['client_id']?>&viewtype=client'><?php echo $item[0]['egress_count'] ?></a></td>
                <td><a class='ingress_count' style="width:100%;display:block;" href='<?php echo $this->webroot?>prresource/gatewaygroups/view_ingress?query[id_clients]=<?php echo $item[0]['client_id']?>&viewtype=client'><?php echo $item[0]['ingress_count'] ?></a></td>
                <td><?php echo $item[0]['update_at'] ?></td>
                <td><?php echo $item[0]['update_by'] ?></td>
                <td>
                    <div  class="action_icons" style="width:220px;">
                        <?php if($_SESSION['role_menu']['Payment_Invoice']['reset_balance'] == 1): ?>
                         <a title='Reset Balance' class="reset" control="<?php echo $item[0]['client_id']?>" href="###"> <img width="16" height="16" src="<?php echo $this->webroot?>images/balanceOperations.gif"> </a>
                        <?php endif; ?>
                        <a title='<?php __('viewcdrlist')?>' href="<?php echo $this->webroot?>cdrreports/summary_reports/client/<?php    echo $item[0]['client_id']?>"> <img width="16" height="16" src="<?php echo $this->webroot?>images/bDrPlans.png"> </a>
                        
                        <?php  if ($_SESSION['role_menu']['Management']['clients']['model_w']) {?>
<!--                        <a title='Send Rate' href="<?php echo $this->webroot?>ratemails/sendrate/<?php  echo $item[0]['client_id']?>"> <img width="16" height="16" src="<?php echo $this->webroot?>images/send_rate.png"> </a>-->
<!--                        <a title='Send Daily Summary Report' control="--><?php //echo $item[0]['client_id']?><!--" class="daily_btn" href="--><?php //echo $this->webroot?><!--clients/summary_reporter/--><?php //echo $item[0]['client_id']?><!--"> -->
<!--                            <img width="16" height="16" src="--><?php //echo $this->webroot?><!--images/statistics.png"> -->
<!--                        </a>-->
                        <a title='Low Balance Alert' href="<?php echo $this->webroot?>clients/low_balance_alert/<?php echo $item[0]['client_id']?>"> 
                            <img width="16" height="16" src="<?php echo $this->webroot?>images/low_balance.png"> 
                        </a>
                        <?php  }?>

                        <?php  if ($_SESSION['role_menu']['Management']['clients']['model_w']) {?>
                                <a style="display:block;" href="<?php echo $this->webroot?>homes/auth_user?client_id=<?php echo $item[0]['client_id']?>&lang=<?php echo $lang?>"> <img width="16" height="16" src="<?php echo $this->webroot?>images/bLogins.gif"> </a>
                                <?php if (Configure::read('system.type') == 2): ?><a title="exchange"  style="display:block;"  href="<?php echo $this->webroot?>clients/admin_login?client_id=<?php echo $item[0]['client_id']?>&lang=<?php echo $lang?>"> <img width="16" height="16" src="<?php echo $this->webroot?>images/bLogins.gif"> </a><?php endif; ?>
                                <a title="view transaction" style="display:block;" href="<?php echo $this->webroot?>clients/transaction/<?php echo $item[0]['client_id']?>"> <img width="16" height="16" src="<?php echo $this->webroot?>images/t-out.gif"> </a>
                                
                        <a   href='#'  rel="update_password"> <img  title=" Change password" src="<?php echo $this->webroot?>images/menuIcon_014.gif"> </a>
                        <?php if ( $item[0]['status']==1){?>
                        <a   onclick="return confirm('Are you sure to inactive the selected <?php echo $item[0]['name'] ?>?')"   href="<?php echo $this->webroot?>clients/dis_able/<?php echo $item[0]['client_id']?>?<?php echo $$hel->getParams('getUrl')?>" > <img  title=" <?php echo 'Click to inactive';?>" src="<?php echo $this->webroot?>images/flag-1.png"> </a>
                        <?php }else{?>
                        <a  onclick="return confirm('Are you sure to active the selected <?php echo $item[0]['name'] ?>?')" href="<?php echo  $this->webroot?>clients/active/<?php echo $item[0]['client_id']?>?<?php echo $$hel->getParams('getUrl')?>"  > <img  title=" <?php echo 'Click to active';?>" src="<?php echo $this->webroot?>images/flag-0.png" static="0" > </a>
                        <?php }?>
                        <a title="<?php echo __('edit')?>"  href="<?php echo $this->webroot?>clients/edit/<?php echo $item[0]['client_id']?>"> <img  src="<?php echo $this->webroot?>images/editicon.gif"> </a> <a title="<?php echo __('del')?>" client_name='<?php echo $item[0]['name']?>' client_id='<?php echo $item[0]['client_id']?>	' onClick="delete_client(this);return false;return confirm('Are you sure to delete , carriers <?php echo $item[0]['name'] ?> ?');" href="<?php echo $this->webroot?>clients/del/<?php echo $item[0]['client_id']?>"> <img width="16" height="16" src="<?php echo $this->webroot?>images/delete.png"> </a> 
                        <?php }?>
                        <input  type="hidden"  name="hidden_client_id"    value="<?php echo $item[0]['client_id']?>"/>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div id="tmppage"> <?php echo $this->element('page');?> </div>
</div>
<?php echo $this->element("clients/update_password")?>
<div id="daily_summary_report_popup_window" style="background:#fff;padding:30px;display:none;">
    <p>
        <input type="hidden" name="daily_summary_report_client" value="0">
        <input type="text" name="daily_summary_report_time" value="<?php echo date("Y-m-d", strtotime('-1 days')); ?>" onclick="WdatePicker({dateFmt:'yyyy-MM-dd',lang:'en'})">
        <input type="button" id="daily_summary_report_ok" value="Send Now">
        <input type="button" id="daily_summary_report_close" value="Close">
    </p>
</div>

<div id="pop-div" class="pop-div" style="display:none;">
	<div class="pop-thead">
    	<span></span>

        <span class="float_right"><a href="javascript:closeDiv('pop-div')" id="pop-close" class="pop-close">&nbsp;</a></span>
    </div>
    <div class="pop-content" id="pop-content"></div>
</div>
<scirpt type="text/javascript" src="<?php $this->webroot ?>js/jquery.center.js"></scirpt>
<script type="text/javascript">
var options;
var del_id;
var del_name;
function delete_client(obj){
	del_id=jQuery(obj).attr('client_id');
	del_name=jQuery(obj).attr('client_name');
	options=jQuery.xshow({
		width:'350px',
		left:'30%',
		html:'<div style="font-size:14px">When the carrier is removed, all the relevant CDRs will be deleted.Please choose one of the following options:</div>'+
		'<div>'+
		'<br/>'+
		'<div style="font-size:12px"><a href="#" onclick="ajax_del_client(1,this);return false;">1. Confirm to delete without exporting CDRs</a></div>'+
		'<br/>'+
		'<div style="font-size:12px"><a href="#" onclick="ajax_del_client(2,this);return false;">2. Export all CDRs before deleting  </a></div>'+
		'<br/>'+
		'</div>'
	});
}




function ajax_del_client(type,obj){
	//jQuery(obj).parent().parent().parent().html('<span style="font-size:16px;color:red">Is deleted, please wait. . . .</span>');
        //jQuery(obj).parent().parent().parent().html('<div id="loading" style="display: block;"></div>');
        jQuery(obj).parent().parent().parent().remove();
        var $waiting = $('<div id="loading" style="display: block;"></div>');
        jQuery('body').prepend($waiting);
	if(type==1){
		var url="<?php echo $this->webroot?>clients/ajax_del/"+del_id+"/true";
		var data=jQuery.ajaxData(url);
		if(data.indexOf('true')==-1){
                    $waiting.remove();
			jQuery.jGrowlError('delete fail!');
		}else{
                        $waiting.remove();
			jQuery.jGrowlSuccess('The '+del_name+' is deleted successfully.');
                        
                        window.setTimeout('window.location.reload();', 2000);
		}
	}
	if(type==2){
		var url="<?php echo $this->webroot?>clients/ajax_del/"+del_id;
		var data=jQuery.ajaxData(url);
		if(data.indexOf('true')==-1){
                        $waiting.remove();
			jQuery.jGrowlError('delete fail!');
		}else{
                        $waiting.remove();
			jQuery.jGrowlSuccess('Carrier '+del_name+' is successfully removed.');
			window.open('<?php echo $this->webroot?>clients/down_client_cdr/'+del_id);
		}
	}
	jQuery('#list_div').html(jQuery.ajaxData());
	jQuery.closexshow(options);
}
$(function() {
    $('.reset').click(function() {
        var client_id = $(this).attr('control');
        var popWindow = showDiv('pop-div','340','160','<?php echo $this->webroot; ?>clients/reset_balance_panel/' + $(this).attr('control'));
        popWindow.center();
    });
    
    $('.daily_btn').click(function() {
        $('input[name=daily_summary_report_client]').val($(this).attr('control'));
        $('#daily_summary_report_popup_window').center().show();
        
        return false;
        
    });
    
    $('#daily_summary_report_ok').click(function() {
        $.ajax({
            'url':"<?php echo $this->webroot?>clients/summary_reporter",
            'type' : "POST",
            'dataType' : 'text',
            'data' : {'date': $('input[name=daily_summary_report_time]').val(), 'client_id' : $('input[name=daily_summary_report_client]').val()},
            'success' : function(data) {
                jQuery.jGrowl(data,{theme:'jmsg-success'});
                $('#daily_summary_report_popup_window').hide();    
            }
        });
    });
    
    $('#daily_summary_report_close').click(function() {
        $('#daily_summary_report_popup_window').hide();    
    });
    
    
    $('#filter_client_type').change(function() {
        $('#filter_client_type').submit();
        $('#submit').click();
    });
    
    $('#myform1').submit(function() {
        loading();
        return true;
    });

    $('#reset_balance_btn').live('click', function() {
        
        var val1 = $('#balance').val();
        
        if(val1 == '' || $('#begin_time').val() == '') {
            jQuery.jGrowl('Can not be empty!',{theme:'jmsg-alert'});
            return;
        }
        
        if(val1.indexOf(',') != -1 ) {
            jQuery.jGrowl('Can not separate by comma!',{theme:'jmsg-alert'});
            return;
        }
        
        if(isNaN(val1)) {
            jQuery.jGrowl('The balance must be a number!',{theme:'jmsg-alert'});
            return;
        }
        var serial = $('#reset_balance_form').serialize();
        $.ajax(
            {
                url:'<?php echo $this->webroot ?>clients/set_balance',
                type:'post',
                dataType:'text',
                data:serial,
                success:function(data) {
                    jQuery.jGrowl('Succeeded',{theme:'jmsg-success'});
                    window.setTimeout("window.location.reload();", 3000)
                }
            }
        );
    });

});
</script>  
