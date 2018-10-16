<div>
<?php echo $this->element("xpage")?>
<table class="list">

	<thead>
	<tr>
<!--    <td>
        <?php echo $appCommon->show_order('currency_id',__('ID',true));?>
    </td>-->
    <td>
        <?php echo $appCommon->show_order('code',__('Name',true));?>
    </td>
    <td>
        <?php echo $appCommon->show_order('rate',__('Rates',true));?>
    </td>
    <td>
        <?php echo $appCommon->show_order('last_modify',__('lastupdateat',true));?>
    </td>
    <td>
       <?php echo $appCommon->show_order('usage', __('usage',true));?>
    </td>
    <td>Update By</td>
   <?php  if ($_SESSION['role_menu']['Switch']['currs']['model_w']) {?>
    <td>
       <?php echo __('active');?>
    </td>
    
    <td class="last"><?php echo __('action')?></td><?php }?>
		</tr>
	</thead>
	<tbody>
	<?php foreach($this->data as $list) {?>
	<tr>
<!--		<td><?php echo $list['Curr']['currency_id']?></td>-->
		<td><?php echo $list['Curr']['code']?></td>

		<td style="color:green"><?php echo round($list['Curr']['rate'], 4);?></td>
		<td><?php echo $list['Curr']['last_modify']?></td>
		<td>
                    <a href="<?php echo $this->webroot ?>rates/rates_list?search_currency=<?php echo $list['Curr']['currency_id'] ?>&advsearch=1">
	<?php echo $list['Curr']['rates']?>
                    </a>
                <td><?php echo $list['Curr']['update_by']?></td>
	</td>
		<?php  if ($_SESSION['role_menu']['Switch']['currs']['model_w']) {?>
		<td><?php echo $appCurrs->active($list)?></td>
		
		<td>
			<a class="history"     href="<?php echo $this->webroot?>currs/history/<?php echo $list['Curr']['currency_id']?>" title="View change history">
		    	<img src="<?php echo $this->webroot?>images/bRates.gif"/>
		    </a>
    		
    		<a title="<?php echo __('edit')?>" class="edit" href="<?php echo $this->webroot?>currs/edit/<?php echo $list['Curr']['currency_id']?>" currency_id="<?php echo $list['Curr']['currency_id']?>">
    			<img src="<?php echo $this->webroot?>images/editicon.gif" />
    		</a>
    		<a  title="<?php echo __('del')?>" href="javascript:void(0)" onclick="ex_delConfirm(this,'<?php echo $this->webroot?>currs/del_currency/<?php echo $list['Curr']['currency_id']?>','currency <?php echo$list['Curr']['code']?>');">
    			<img src="<?php echo $this->webroot?>images/delete.png" />
    		</a>
    	</td><?php }?>
	</tr>
		<?php }?>		
	</tbody>
</table>
<?php echo $this->element("xpage")?>
</div>
<script type="text/javascript">
jQuery.fn.active=function(){
	
	jQuery(this).addClass('active');
	jQuery(this).click(function(){
		if(confirm("Are you sure you would like to inactive the selected currency?")){
		var that=this;
                var name = $.trim($(this).parent().parent().find('td:eq(0)').text());
		jQuery.get(jQuery(this).attr('href'),function(data){
			if(data==1){
				jQuery.jGrowl('The Currency[' + name +'] is inactived successfully!',{theme:'jmsg-success'});
				jQuery(that).unactive().disabled().attr('title','Disabled');
				jQuery(that).find('img').attr('src','<?php echo $this->webroot?>images/flag-0.png');
			}else{
				jQuery.jGrowl('active fail',{theme:'jmsg-error'});
			}
		});
		}
		return false;
	});
	return jQuery(this);
}
jQuery.fn.unactive=function(){
	jQuery(this).unbind('click').remove('active');
	return jQuery(this);
}
jQuery.fn.disabled=function(){
	
	jQuery(this).click(function(){
		if(confirm(" Are you sure you would like to active the selected currency?")){
		var that=this;
                var name = $.trim($(this).parent().parent().find('td:eq(0)').text());
		jQuery.get(jQuery(this).attr('href'),function(data){
			if(data==1){
				jQuery.jGrowl('The Currency [' + name +'] is actived successfully!',{theme:'jmsg-success'});
				jQuery(that).undisabled().active().attr('title','Active');
				jQuery(that).find('img').attr('src','<?php echo $this->webroot?>images/flag-1.png');
			}else{
				jQuery.jGrowl('disabled fail',{theme:'jmsg-error'});
			}
		});
		}
		return false;
	});
	return jQuery(this);
	
}
jQuery.fn.undisabled=function(){
	jQuery(this).unbind('click').remove('disabled');
	return jQuery(this);
}
jQuery(document).ready(
	function(){
		jQuery('.active').active();
		jQuery('.disabled').disabled();
	}
);
</script>
<script type="text/javascript">
//,
//onsubmit:function(options){
// var re =true;
// re=jsAdd.onsubmit(options);
// if(jQuery('#CurrCode').val()!=''){
//var data=jQuery.ajaxData("<?php echo $this->webroot?>currs/check_repeat_name/"+jQuery('#CurrCode').val()+"/"+currency_id);
//if(!data.indexOf("false")){
//jQuery.jGrowlError(jQuery('#CurrCode').val()+" is already in use! ");
//re=false;
//					}
//   	}
//return re;
//
//}
jQuery('.edit').click(
		function(){
			var action=jQuery(this).attr('href');
			var currency_id=jQuery(this).attr('currency_id');
			jQuery(this).parent().parent().trAdd(
				{
					ajax:"<?php echo $this->webroot?>currs/js_save/"+currency_id,
					action:action,
					saveType:'edit'
				}
			);
			return false;
		}
);
jQuery('.history').click(
	function(){
		if(jQuery(this).attr('history')!="true"){
			jQuery(this).attr('history',"true");
			var td=jQuery('<tr/>').append('<td colspan=7>').insertAfter(jQuery(this).parent().parent()).find('td:nth-child(1)');
			var href=jQuery(this).attr('href');
			jQuery.get(href,function(data){td.append(data)});
		}else{
			jQuery(this).removeAttr('history');
			jQuery(this).parent().parent().next().remove();
		}
		return false;
	}
);
</script>




