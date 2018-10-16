<?php $d = $p->getDataArray(); ?>
<?php if($_SESSION['login_type']==1){?>
<script language="JavaScript" type="text/javascript">
function generate_tmp(){
   var tab = document.getElementById("ratetab");
   var inputs = tab.getElementsByTagName("input");
   var ids = '';
   var count = 0;
  
   for (var i = 0;i<inputs.length;i++) {
	   if (inputs[i].type=='checkbox' && inputs[i].checked == true){
		   ids += inputs[i].value+",";
		   count ++;
	   }
   }
   if (count <=0){
		jQuery.jGrowl('Please select at least one templates rates table ',{theme:'jmsg-alert'});
		return false;
	}
	ids = ids.substring(0,ids.lastIndexOf(","));
	document.getElementById("g_ids").value = ids;
	if (count  ==-1){
		cover('copyratetmp');
		document.getElementById('tmpid').value=ids;
		return false;
	}
	cover('generatert');
}
		
function generate_rt(){
	var rt_n = document.getElementById("pname_g").value;
    if (!rt_n){jQuery.jGrowl('Please enter name for the news rate table to be created!',{theme:'jmsg-alert'});
		return false;
	}
	var cur = document.getElementById("g_currency").value;
    var r_url = "<?php echo $this->webroot?>/rates/generate_tmp?rt_n="+rt_n+"&cur="+cur+"&way="+$('input[name=way]:checked').val()+"&ids="+$('#g_ids').val();
    location = r_url;
}

function copy_rate(){
	var v = document.getElementById("tmpid").value;
	var n = document.getElementById("pname").value;
	if (!n){
		jQuery.jGrowl('<?php echo __('enterratename')?>',{theme:'jmsg-alert'});
	}
	else{
		location = '<?php echo $this->webroot?>/rates/copy_tmp?id='+v+'&name='+n;
	}
}
			
function save_rate(tr){
	var curr_url='<?php echo $_SESSION['curr_url'];?>';
	var name = tr.cells[2].getElementsByTagName("input")[0].value;
    if (!name){
         jQuery.jGrowl('<?php echo __('enterratename')?>',{theme:'jmsg-alert'});
	}
    else {
	 	var c = tr.cells[3].getElementsByTagName("select")[0].value;
	 	var cu = tr.cells[4].getElementsByTagName("select")[0].value;
	 	var country = tr.cells[6].getElementsByTagName("select")[0].value;
        if (!cu){
           jQuery.jGrowl('<?php echo __('nocurrency')?>',{theme:'jmsg-alert'});
           return false;
        }
		url = "<?php echo $this->webroot ?>rates/add_tmp?n="+name+"&c="+c+"&cu="+cu+"&country="+country+"";
		location=url;
	}
	    return false;
}
</script>

<div id="title">
  <h1>
    <?php __('System')?>
    &gt;&gt;<?php echo __('rateTable')?></h1>
  <?php if($_SESSION['login_type']=='1'){?>
  <ul id="title-search">
    <li>
      <form>
        <input type="text" id="search-_q" class="in-search default-value input in-text defaultText" title="<?php echo __('namesearch')?>" value="<?php if (!empty($search)) echo $search;?>" name="search">
      </form>
    </li>
    <li style="display: list-item;" id="title-search-adv" onclick="advSearchToggle();" title="advanced search »" class=""></li>
  </ul>
  <ul id="title-menu">
  <?php  if ($_SESSION['role_menu']['Switch']['rates']['model_w']) {?>
    <li><a  class="link_btn" href="<?php echo $this->webroot ?>rates/create_ratetable"><img width="16" height="16" alt="" src="<?php echo $this->webroot?>images/add.png" /> <?php echo __('createnew')?></a></li>
<!--    <li><a  id="add"  class="link_btn" href="javascript:void(0)"><img width="16" height="16" alt="" src="<?php echo $this->webroot?>images/add.png" /> <?php echo __('createnew')?></a></li>-->
    <li><a class="link_btn" href="javascript:void(0)" onclick="generate_tmp();" ><img width="16" height="16" alt="" src="<?php echo $this->webroot?>images/menuIcon_025.gif"> <?php echo __('generatert')?></a></li>
    <?php if (count($d) > 0) : ?>
    <li><a  class="link_btn" rel="popup" href="javascript:void(0)" onclick="deleteAll('<?php echo $this->webroot?>/rates/delete_all');"><img width="16" height="16" alt="" src="<?php echo $this->webroot?>images/delete.png"> <?php echo __('deleteall')?></a></li>
    <li><a class="link_btn" rel="popup" href="javascript:void(0)" onclick="deleteSelected('ratetab','<?php echo $this->webroot?>/rates/delete_selected','rate table');"><img width="16" height="16" alt="" src="<?php echo $this->webroot?>images/delete.png"> <?php echo __('deleteselected')?></a></li>
    <?php endif; ?>
    <?php }?>
    <?php if (isset($curr_search)) { ?>
    <li> <?php echo $appCommon->show_back_href();?> </li>
    <?php }?>
  </ul>
  <?php }?>
</div>


<dl id="copyratetmp" class="tooltip-styled" style="display:none;position:absolute;left:40%;top:30%;z-idnex:99;width:300px;height:100px;">
  <dd style="text-align:center;width:100%;height:25px;font-size: 16px;"><?php echo __('copyratetmp')?>
  <span class="float_right"><a href="javascript:closeCover('copyratetmp');" id="pop-close" class="pop-close">&nbsp;</a></span>
  </dd>
  <dd style="margin-top:10px;margin-left:5%;"> <?php echo __('ratetmpname')?>:
    <input class="input in-text" id="pname"/>
  </dd>
  <dd>
    <input style="display:none" id="tmpid"/>
  </dd>
  <dd style="margin-left: 20%; width:200px;height:auto; ">
    <input type="button" onclick="copy_rate();" value="<?php echo __('submit')?>" class="input in-button" >
    <input type="button" onclick="closeCover('copyratetmp');" value="<?php echo __('cancel')?>" class="input in-button"  >
  </dd>
</dl>
<dl id="generatert" class="tooltip-styled" style="display:none; position:absolute;margin:auto;left:400px;top:200px;z-idnex:99;width:350px;height:250px;">
  <dd style="text-align:center;width:100%;height:30px;font-size: 16px;"><?php echo __('generatert')?>
  <span class="float_right"><a href="javascript:closeCover('generatert')" id="pop-close" class="pop-close">&nbsp;</a></span>
  
  </dd>
  
  <dd>
    <table style="margin:10px">
      <tr>
        <td style="text-align:right"> Input <?php echo __('ratetmpname')?>:</td>
        <td style="text-align:left"><script type="text/javascript">
						var _ss_ids_rate = {'id_rates': 'query-id_rates', 'id_rates_name': 'query-id_rates_name'};
						
					</script>
          <input type="text" id="query-id_rates_name" ondblclick="ss_rate(_ss_ids_rate)" value="" name="data[name]" class="input in-text">
         <a href="#"onclick="ss_rate(_ss_ids_rate)"  > <img width="25" height="25" class="img-button" src="<?php echo $this->webroot?>images/search-small.png"/> </a><a href="#"  onclick="ss_clear('card', _ss_ids_rate)"><img width="25" height="25" class="img-button" src="<?php echo $this->webroot?>images/delete-small.png"/> </a></td>
      </tr>
      <tr>
        <td style="text-align:right"><?php echo __('currency')?>:</td>
        <td style="text-align:left"><select  id="g_currency" name="data[currency]" style="width:180px">
            <?php for ($i=0;$i<count($currs_s);$i++) { ?>
            <option value="<?php echo $currs_s[$i][0]['currency_id']?>"><?php echo $currs_s[$i][0]['code']?></option>
            <?php }?>
          </select></td>
      </tr>
      <tr>
        <td style="text-align:right;"><?php echo __('type')?>:</td>
        <td style="text-align:left"><select  name="data[type]" style="width: 180px" onchange="copy_change_type(this)">
            <option value=1>By minimum</option>
            <option value=2>By maximum</option>
            <option value=3>On average</option>
            <option value=4>Percentage increase</option>
            <option value=5>By number</option>
          </select>
          <input style="display:none;width:180px" type="text" name=data[type_num] /></td>
      </tr>
      <tr>
        <td style="text-align:right">Same code of:</td>
        <td style="text-align:left"><select name='data[code_type]'  style="width:180px">
            <option value='ignore'>ignore</option>
            <option value='overwrite'>overwrite</option>
          </select></td>
      </tr>
    </table>
  </dd>
  <dd>
    <input id="g_ids" value="" type="hidden" name='data[ids]' style="display:none"/>
  </dd>
  <dd style="margin-top:10px; margin-left:25%;width:150px;height:auto;">
    <input type="button" onclick="jQuery('#generatert').xForm({action:'<?php echo $this->webroot?>/rates/generate_tmp',onsubmit:function(options){if(jQuery('#query-id_rates_name').val()==''){jQuery('#query-id_rates_name').jGrowlError('Please enter name for the news rate table to be created!');return false;}return true;}})" value="<?php echo __('submit')?>" class="input in-button">
    <input type="button" onclick="closeCover('generatert');" value="<?php echo __('cancel')?>" class="input in-button">
  </dd>
</dl>
<div id="container">
  <fieldset class="title-block" id="advsearch" style="display:none;width:100%;margin-left:1px;">
    <form method="get">
      <input type="hidden" name="advsearch" value="1"/>
      <table  style="width:auto;">
        <tbody>
          <tr>
            <td style="display: none;"><label style="padding-top:3px;"><?php echo __('ratetmpname')?>:</label>
              <input type="text" name="name" class="input in-text"/></td>
            <td><label style="padding-top:3px;"><?php echo __('codedecks')?>:</label>
              <select id="search_code_deck" class="select in-select" name="search_code_deck">
                <option value=""><?php echo __('select')?></option>
                <?php for ($i=0;$i<count($codecs_s);$i++) { ?>
                <option value="<?php echo $codecs_s[$i][0]['code_deck_id']?>"><?php echo $codecs_s[$i][0]['name']?></option>
                <?php } ?>
              </select></td>
            <td><label style="padding-top:3px;"><?php echo __('currency')?>:</label>
              <select id="search_currency" name="search_currency" class="select in-select">
                <option value=""><?php echo __('select')?></option>
                <?php for ($i=0;$i<count($currs_s);$i++) { ?>
                <option value="<?php echo $currs_s[$i][0]['currency_id']?>"><?php echo $currs_s[$i][0]['code']?></option>
                <?php } ?>
              </select></td>
            <td></td>
            <td class="buttons"><input type="submit" value="<?php echo __('search')?>" class="input in-submit"></td>
          </tr>
        </tbody>
      </table>
    </form>
    <?php
	if (!empty($searchForm)) {
		$d = array_keys($searchForm);
		foreach($d as $k) {?>
    <script type="text/javascript">
				if (document.getElementById("<?php echo $k?>")){
					document.getElementById("<?php echo $k?>").value = "<?php echo $searchForm[$k]?>";
				}
			</script>
    <?php }?>
    <script type="text/javascript">document.getElementById("advsearch").style.display='block';</script>
    <?php }?>
  </fieldset>
  <div id="tmpres" style="display:none;">
    <select  class="in-select input" style="width:100px;height:20px;">
      <?php for ($i=0;$i<count($r_reseller);$i++){ ?>
      <option value="<?php echo $r_reseller[$i][0]['reseller_id']?>">
      <?php
							$space = "";
							for ($j=0;$j<$r_reseller[$i][0]['spaces'];$j++) {
								$space .= "&nbsp;&nbsp;";
							}
							if ($i==0){
								echo "{$r_reseller[$i][0]['name']}";
							} else {
								echo "&nbsp;&nbsp;".$space."↳".$r_reseller[$i][0]['name'];
							}
					?>
      </option>
      <?php } ?>
      <option value=""><?php echo __('anyreseller')?></option>
    </select>
  </div>
  <div id="cover"></div>
  <?php if (count($d) == 0) {?>
  <div class="msg"  id="msg_div"><?php echo __('no_data_found')?></div>
  <?php } else {?>
  <div class="msg"  id="msg_div"  style="display: none;"><?php echo __('no_data_found')?></div>
  <?php }?>
  <?php if (count($d) == 0) {?>
  <div  id="list_div"  style="display: none;">
    <?php } else {?>
    <div   id="list_div">
      <?php }?>
      <div id="toppage"></div>
      <?php  if ($_SESSION['role_menu']['Switch']['rates']['model_w']) {?><div style="padding:5px;text-align:right"><button id="msedit" class="input in-submit" style="width:100px;">Mass Edit</button></div>
      <?php }?>
      <div>
        <table class="list">

          <thead>
            <tr>
              <?php  if ($_SESSION['role_menu']['Switch']['rates']['model_w']) {?>
              <td><?php if($_SESSION['login_type']=='1'){?>
                <input id="selectAll" class="select" type="checkbox" onclick="checkAllOrNot(this,'ratetab');" value=""/>
                <?php }?></td>
                <?php }?>
<!--              <td><?php echo $appCommon->show_order('rate_table_id',__('ID',true))?></td>-->
              <td><?php echo $appCommon->show_order('name',__('Name',true))?></td>
              <td><?php echo $appCommon->show_order('code_deck',__('Code Deck',true))?></td>
              <td><?php echo $appCommon->show_order('currency',__('Currency',true))?></td>
              <td><?php __('Usage Count') ?></td>
              <td><?php echo $appCommon->show_order('rate_type',__('Billing Method',true))?></td>
              <td><?php echo $appCommon->show_order('jurisdiction_country_id',__('Rate Type',true))?></td>
              <td><?php echo $appCommon->show_order('lnp_dipping_rate',__('Dip Charge',true))?></td>
              <td><?php echo __('Update At',true);?></td>
              <td><?php echo __('Update By',true);?></td>
              <td style="display: none;"><a href="javascript:void(0)" onclick="my_sort('use_gift_money','asc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>&nbsp;<?php echo __('use_gift_money')?>&nbsp;<a href="javascript:void(0)" onclick="my_sort('use_gift_money','desc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a></td>
              <td  style="display: none;"><a href="javascript:void(0)" onclick="my_sort('use_money_order','asc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>&nbsp;<?php echo __('use_money_order')?>&nbsp;<a href="javascript:void(0)" onclick="my_sort('use_money_order','desc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a></td>
             <?php  if ($_SESSION['role_menu']['Switch']['rates']['model_w']) {?> <td class="last"><?php echo __('action')?></td>
             <?php }?>
            </tr>
          </thead>
          <tbody id="ratetab">
            <?php 
			$mydata =$p->getDataArray();
			$loop = count($mydata); 
			for ($i=0;$i<$loop;$i++) {?>
            <tr class="row-1"  id="i_<?php echo $loop-$i?>">
              <?php  if ($_SESSION['role_menu']['Switch']['rates']['model_w']) {?><td style="text-align:center"><?php if($_SESSION['login_type']=='1'){?>
                <input class="select" type="checkbox" value="<?php echo $mydata[$i][0]['rate_table_id']?>"/>
                <?php }?></td>
                <?php }?>
<!--              <td class="in-decimal" style="text-align:center"><?php echo $mydata[$i][0]['rate_table_id']?></td>-->
              <td style="font-weight: bold;"><a title="<?php echo __('viewrates')?>" class="link_width" 
		    		href="<?php echo $this->webroot?>clientrates/view/<?php echo $mydata[$i][0]['rate_table_id']?>/"> <?php echo $mydata[$i][0]['name']?> </a>
                    </td>
              <td><a style="width:100%;display:block" href="<?php echo $this->webroot?>codedecks/codedeck_list?edit_id=<?php echo $mydata[$i][0]['code_deck_id']?>&viewType=rate"><?php echo $mydata[$i][0]['code_deck']?></a></td>
              <td><?php echo $mydata[$i][0]['currency']?></td>
              <td>
                  <a target="_blank" href="<?php echo $this->webroot ?>prresource/gatewaygroups/view_egress?rate_table_id=<?php echo $mydata[$i][0]['rate_table_id']?>" title="Egress Trunk Usage Count">
                  <?php echo $mydata[$i][0]['egress_count']?>
                  </a>
                  /
                  <a target="_blank" href="<?php echo $this->webroot ?>prresource/gatewaygroups/view_ingress?rate_table_id=<?php echo $mydata[$i][0]['rate_table_id']?>" title="Ingress Trunk Usage Count">
                  <?php echo $mydata[$i][0]['ingress_count']?>
                  </a>
              </td>
              <td><?php echo $billing_methods[$mydata[$i][0]['rate_type']]?></td>
<!--              <td><a  href="<?php echo  $this->webroot?>jurisdictionprefixs/view_rate_table/<?php echo $mydata[$i][0]['jurisdiction_country_id']?>"   target="blank"> <?php echo $appRate->show_jurisdiction_country($jurisdiction_countries,$mydata[$i][0]['jurisdiction_country_id'])?> </a></td>-->
              <td><?php echo $jur_lists[$mydata[$i][0]['jur_type']];?></td>
              <td><?php echo $mydata[$i][0]['lnp_dipping_rate']?></td>
              <td><?php echo $mydata[$i][0]['update_at']?></td>
              <td><?php echo $mydata[$i][0]['update_by']?></td>
        <?php  if ($_SESSION['role_menu']['Switch']['rates']['model_w']) {?>
              <td   class="last" ><a title="<?php echo __('viewrates')?>" style="float:left;margin-left:5px;"
		    		href="<?php echo $this->webroot?>clientrates/view/<?php echo $mydata[$i][0]['rate_table_id']?>/"> <img src="<?php echo $this->webroot?>images/bOrigTariffs.gif" /> </a>
                <?php if($_SESSION['login_type']==1){?>
                <a title="<?php echo __('edit')?>" style="float:left;margin-left:5px;" href="#" id='edit' rate_table_id='<?php echo $mydata[$i][0]['rate_table_id']?>'> <img src="<?php echo $this->webroot?>images/editicon.gif" /> </a> 
                <a item="<?php echo $mydata[$i][0]['rate_table_id']?>" title="<?php echo __('del')?>" style="float:left;margin-left:5px;" href="javascript:void(0)" class="delbtn">
                    <img src="<?php echo $this->webroot?>images/delete.png" /> 
                </a>  
                <a title="<?php echo __('Indeterminate')?>" control="<?php echo $mydata[$i][0]['rate_table_id']?>" class="show_indeterminate" style="float:left;margin-left:5px;" href="###"> <img src="<?php echo $this->webroot?>images/indeteminate.png" width="16" height="16" /> </a> <a title="<?php echo __('copyratetmp')?>" style="float:left;margin-left:5px;" href="javascript:void(0)" onclick="cover('copyratetmp');document.getElementById('tmpid').value='<?php echo $mydata[$i][0]['rate_table_id']?>';"> <img src="<?php echo $this->webroot?>images/copy.png" width="16" height="16" /> </a>
                <?php }?></td>
              <td style="display:none"><?php echo $mydata[$i][0]['reseller_id']?></td>
            </tr>
            <?php }?>
            <tr style="display:none;">
              <td><dl id="i_<?php echo $loop-$i?>-tooltip" class="tooltip">
                  <dd><?php echo  __('createdate')?>:</dd>
                  <dd><?php echo $mydata[$i][0]['create_time']?></dd>
                  <dd><?php echo __('updateat')?>:</dd>
                  <dd><?php echo $mydata[$i][0]['modify_time']?></dd>
                </dl></td>
            </tr>
            <?php }?>
          </tbody>
         
        </table>
      </div>
      <div id="tmppage"> <?php echo $this->element('page');?> </div>
    </div>
  </div>
  <script type="text/javascript" src="<?php echo $this->webroot;?>js/jquery.center.js"></script>
  <script type="text/javascript">
function copy_change_type(obj){
	if(jQuery(obj).val()==4 || jQuery(obj).val()==5){
		jQuery(obj).parent().find('input').show();
	}else{
		jQuery(obj).parent().find('input').hide().val('');
	}
}
</script> 
  <script type="text/javascript">
jQuery(document).ready(function(){
	jQuery('#selectAll').selectAll('.select');
	jQuery('#add').click(function(){
		$("#msg_div").hide();
		$("#list_div").show();
			jQuery('#ratetab').trAdd({
                                insertNumber :'first',
				action:"<?php echo $this->webroot?>rates/save",
				ajax:"<?php echo $this->webroot?>rates/js_save",
				onsubmit:function(){
				      var ret = true;
                      var rate_name =jQuery('#RateName').val();
					  if(rate_name.length>36||/[^0-9A-Za-z-\_\s]+/.test(rate_name)){
				                jQuery('#RateName').addClass('invalid');
								jQuery.jGrowl('Name,allowed characters:a-z,A-Z,0-9,-,_,space,maximum of 16 characters in length! ',{theme:'jmsg-error'});
								ret = false;
				     }
							var currency = $("#RateCurrencyId").val();
							if (currency=='0' || currency == null)
							{
								jQuery.jGrowl('The field Currency cannot be NULL.',{theme:'jmsg-error'});
								ret = false;
							}

					  return ret ;
					
			    }
			});
	});
	jQuery('a[id=edit]').click(function(){
		var rate_table_id=jQuery(this).attr('rate_table_id');
		jQuery(this).parent().parent().trAdd({
			action:"<?php echo $this->webroot?>rates/save/"+rate_table_id,
			ajax:"<?php echo $this->webroot?>rates/js_save/"+rate_table_id,
			saveType:'edit',
			onsubmit:function(){
				var re=true;
							
                                var rate_name=jQuery('#RateName').val();
				var name_length = rate_name.length;
				if(/[^0-9A-Za-z-\_\s]+/.test(rate_name)||name_length>36)
				{
				        jQuery('#RateName').addClass('invalid');
						jQuery.jGrowl('Name,allowed characters:a-z,A-Z,0-9,-,_,space,maximum of 16 characters in length! ',{theme:'jmsg-error'})

					  re= false;
				}
				var currency = $("#RateCurrencyId").val();
				if (currency=='0' || currency == null)
				{
					jQuery.jGrowl('The field Currency cannot be NULL.',{theme:'jmsg-error'});
					re = false;
				}
                                var RateLnpDippingRate = $('#RateLnpDippingRate').val();
                                if(isNaN(RateLnpDippingRate)) {
                                    jQuery.jGrowl('The field Dip Charge must be number.',{theme:'jmsg-error'});
					re = false;
                                }
		        return re;
			}
	});
	});
});
</script> 

<script type="text/javascript" language="javascript">
var _move=false;//移动标记
var _x,_y;//鼠标离控件左上角的相对位置
$(document).ready(function(){
    $("#generatert").click(function(){
        //alert("click");//点击（松开后触发）
        }).mousedown(function(e){
        _move=true;
        _x=e.pageX-parseInt($("#generatert").css("left"));
        _y=e.pageY-parseInt($("#generatert").css("top"));
        $("#generatert").fadeTo(20, 0.25);//点击后开始拖动并透明显示
    });
    $(document).mousemove(function(e){
        if(_move){
            var x=e.pageX-_x;//移动时根据鼠标位置计算控件左上角的绝对位置
            var y=e.pageY-_y;
            $("#generatert").css({top:y,left:x});//控件新位置
        }
    }).mouseup(function(){
    _move=false;
    $("#generatert").fadeTo("fast", 1);//松开鼠标后停止移动并恢复成不透明
  });
});
</script>

<script type="text/javascript">
$(function() {
    $('#msedit').click(function() {
        var selected_arr = new Array();
        $('#ratetab input[type=checkbox]').each(function(index){
            var $this = $(this);
            if($this.attr('checked')) {
                selected_arr.push($this.val());
            }
        });
        if(selected_arr.length === 0) {
            showMessages("[{'field':'#msedit','code':'101','msg':'You must select at least one item!'}]");
            return false;
        }
        window.location.href = "<?php echo $this->webroot ?>rates/massedit/" + selected_arr.join(',');
    });
});
</script>


</div>
<div id="pop-div" class="pop-div" style="display:none;">
	<div class="pop-thead">
    	<span></span>

        <span class="float_right"><a href="javascript:closeDiv('pop-div')" id="pop-close" class="pop-close">&nbsp;</a></span>
    </div>
    <div class="pop-content" id="pop-content"></div>
</div>
<?php }else{ echo  $this->element('rate/client_rate');}?>

<div id="dd" class="easyui-dialog" title="This Rate Table is used be the following trunks:" closed="true" style="width:400px;height:200px;"  
        data-options="iconCls:'icon-save',resizable:true,modal:true">  
    <div class="product_list">
    <table id="useditem" class="list">
        <thead>
            <tr>
                <td>Carrier Name</td>
                <td>Trunk Name</td>
                <td>Type</td>
                <td>Active</td>
            </tr>
        </thead>
        <tbody>
            
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4">
                    <input id="delcontinue" type="button" value="Continue" />
                    <input id="delcancel" type="button" value="Cancel" />
                </td>
            </tr>
        </tfoot>
    </table>
    </div>
</div>  


<link rel="stylesheet" type="text/css" href="<?php echo $this->webroot; ?>easyui/themes/default/easyui.css">
<link rel="stylesheet" type="text/css" href="<?php echo $this->webroot; ?>easyui/themes/icon.css">
<script type="text/javascript" src="<?php echo $this->webroot; ?>easyui/jquery.easyui.min.js"></script>

<script type="text/javascript">
$(function() {
    function preventInput(event) {
        if($(this).parent().prev().find('select').val() == '')
            event.preventDefault();
    }
    $('#RateLnpDippingRate').live('keydown', preventInput);
    $('#RateLnpDippingRate').live('keyup', preventInput);
    
    $('.show_indeterminate').click(function() {
        showDiv('pop-div','500','230','<?php echo $this->webroot; ?>rates/indeteminate/' + $(this).attr('control'));
    });
    
    $('#update_in').live('click', function() {
        var form_serial = $('#update_in_form').serialize();
        $.ajax({
            url : '<?php echo $this->webroot; ?>rates/update_indeter?' + form_serial,
            method : 'GET',
            dataType : 'text',
            success : function(data) {
                jQuery.jGrowl('Sucessfully!',{theme:'jmsg-success'});
            }
        });
    })
    
    var $dd = $('#dd');
    var $useditems = $('#useditem tbody');
    var $delcontinue = $('#delcontinue');
    var $delcancel = $('#delcancel');
    
    $('a.delbtn').click(function() {
        var $this = $(this);
        var item = $this.attr('item');
        
        $.ajax({
            'url'     : '<?php echo $this->webroot ?>rates/checkused',
            'type'    : 'POST',
            'dataType': 'json',
            'data'    : {'rate_table_id' : item},
            'success' : function(data) {
                if(data.length) 
                {
                    $useditems.empty();
                    $.each(data, function(idx, item) {
                           var $tr = $('<tr></tr>');
                           $tr.append('<td>' + item['client_name'] +'</td><td>' + item['resource_name'] + '</td><td>' + item['type'] + '</td><td>' + item['is_active'] + '</td>');

                           $useditems.append($tr);
                    });
                    
                    $delcontinue.unbind('click');
                    
                    $delcontinue.click(function() {
                        window.location.href = '<?php echo $this->webroot ?>/rates/del_rate_tmp/' + item;
                    });
                    
                    $delcancel.click(function() {
                        $delcontinue.unbind('click');
                        $dd.dialog('close');
                    });
                    $dd.dialog('open');
                } else {
                    if (confirm("Are you sure do this?")) {
                        window.location.href = '<?php echo $this->webroot ?>/rates/del_rate_tmp/' + item;
                    }
                }
            }
        });
        
        return false;
    });
});
</script>