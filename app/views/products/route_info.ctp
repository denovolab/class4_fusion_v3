<style type="text/css">
    a:hover {
        text-decoration: none;
    }
</style>
<script type="text/javascript">
    //提示消息居中显示
    jQuery.jGrowl.defaults.position = 'top-center';
</script>
<div id="title">
    <h1>
        <?php __('Routing')?>
        &gt;&gt;<?php echo __('product')?> <font class="editname" title="Name"><?php echo empty($name[0][0]['name'])||$name[0][0]['name']==''?'':'['.$name[0][0]['name'].']' ?> </font> </h1>
    <ul id="title-menu">
        <?php if (isset($edit_return)) {?>
        <li> <a class="link_back" href="<?php echo $this->webroot?>products/route_info/<?php echo $id?>"> <img width="16" height="16"  alt="" src="<?php echo $this->webroot?>images/icon_back_white.png"> &nbsp;<?php echo __('gobackall')?> </a> </li>
        <?php }?>
        <li> <a class="link_back" href="<?php echo $this->webroot?>products/product_list"> <img width="10" height="5" alt="" src="<?php echo $this->webroot?>images/icon_back_white.png"> <?php echo __('goback')?> </a> </li>

        <?php  if ($_SESSION['role_menu']['Routing']['products']['model_w']) {?>
        <li><a class="link_btn" id="add" href="<?php echo $this->webroot?>products/add_route/<?php echo $id?>"><img width="16" height="16" alt="" src="<?php echo $this->webroot?>images/add.png"> <?php echo __('createnew')?></a></li>
        <li><a class="link_btn" rel="popup" href="javascript:void(0)" onclick="deleteAll('<?php echo $this->webroot?>/products/delall?id='+<?php echo $id?>);" ><img width="16" height="16" alt="" src="<?php echo $this->webroot?>images/delete.png"> <?php echo __('deleteall')?></a></li>
        <li><a class="link_btn" rel="popup" href="javascript:void(0)" onclick="ex_deleteSelected('routetab','<?php echo $this->webroot?>/products/delselected?id=<?php echo $id?>','static route');"><img width="16" height="16" alt="" src="<?php echo $this->webroot?>images/delete.png"> <?php echo __('deleteselected')?></a></li>
        <?php }?>
        <li>
            <form>
                <input type="text" id="search-_q" class="in-search default-value input in-text defaultText" title="<?php echo __('prefixsearch')?>" value="<?php if (!empty($search)) echo $search;?>" name="search">
            </form>
        </li>
    </ul>
</div>
<div id="container">
    <ul class="tabs">
        <li class="active"><a href="<?php echo $this->webroot ?>products/route_info/<?php echo $id?>"><img width="16" height="16" src="<?php echo $this->webroot ?>images/menuIcon.gif"> <?php echo __('List',true);?></a></li>
        <?php  if ($_SESSION['role_menu']['Routing']['products']['model_x']) {?>
        <li ><a href="<?php echo $this->webroot ?>uploads/static_route/<?php echo $id?>"><img width="16" height="16" src="<?php echo $this->webroot ?>images/import.png"> <?php echo __('import',true);?></a></li>
        <li  ><a href="<?php echo $this->webroot ?>down/static_route/<?php echo $id?>"><img width="16" height="16" src="<?php echo $this->webroot ?>images/export.png"> <?php echo __('export',true);?></a></li>
        <?php }?>
    </ul>
    <?php $d = $p->getDataArray();if (count($d) == 0) {?>
    <div class="msg"><?php echo __('no_data_found')?></div>
    <table class="list" id="fdf" style="display:none">
        <col style="width: 2%;">
        <col style="width: 6%;">
        <col style="width: 6%;">
        <col style="width: 6%;">
        <col style="width: 6%;">
        <col style="width: 6%;">
        <col style="width: 6%;">
        <col style="width: 6%;">
        <col style="width: 6%;">
        <col style="width: 6%;">
        <col style="width: 6%;">
        <col style="width: 6%;">
        <col style="width: 6%;">
        <col style="width: 10%;">
        <thead>
            <tr>
                <td><input id="selectAll" type="checkbox" onclick="checkAllOrNot(this,'routetab');" value=""/></td>
                <!--<td><?php echo $appCommon->show_order('item_id',__('ID',true))?></td>-->
                <td><?php echo ($code_type == 0)?$appCommon->show_order('digits',__('Code',true)):"Code Name";?>
                </td>
                <!--<td><?php echo $appCommon->show_order('route_type',__('Type',true))?></td>-->
                <td><?php echo $appCommon->show_order('strategy',__('Strategy',true))?></td>
                <td><?php echo $appCommon->show_order('time_profile',__('Time Profile',true))?></td>
                <td colspan="8"><?php echo __('Trunk List',true);?></td>
                <td><?php echo __('Update At',true);?></td>
                <td><?php echo __('Update By',true);?></td>
                <td class="last"><?php echo __('action')?></td>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
    <?php } else {?>
    <div id="toppage">


    </div>
    <div id="cover"></div>
    <div id="uploadroute"  style="display:none;height: auto;z-index:99;position: absolute;left:30%;top: 20%;" class="form_panel_upload">
        <form action="<?php echo $this->webroot?>products/upload/<?php echo $id?>" method="post" enctype="multipart/form-data" id="productFile">
            <span class="wordFont1 marginSpan1"><?php echo __('selectfile')?>:</span>
            <div style="height: 100px;" class="up_panel_upload">
                <input style="margin-top:10px;" type="file" value="Upload" size="45" class="input in-text" id="browse" name="browse">
                <div style="margin-top:20px;">
                    <input type="radio" title="This action takes each record from the csv and adds it to the table, if the prefix already exists then it will be replaced with the one contained in the table !" checked="" value="1" name="handleStyle">
                    <span><?php echo __('overwrite')?></span>
                    <input style="margin-left:10px;" type="radio" title="This action will remove all matching prefixes from the table !" value="2" name="handleStyle">
                    <span><?php echo __('remove')?></span>
                    <input style="margin-left:10px;" type="radio" title="This action will fresh prefixes from the table !" value="3" name="handleStyle">
                    <span><?php echo __('clearrefresh')?></span>
                    <input style="margin-left:10px;" type="checkbox" checked onclick="if(this.value=='false')this.value='true';else this.value='false';document.getElementById('isRoll').value=this.value;">
                    <input type="hidden" value="true" name="isRoll" id="isRoll"/>
                    <span><?php echo __('rollbackonfail')?> </span> </div>
            </div>
            <div class="form_panel_button_upload"> <span style="float:left"> <?php echo __('downloadtempfile')?><a href="<?php echo $this->webroot?>products/downloadtemplate" style="color:red"><?php echo __('clickhere')?></a></span>
                <input type="submit" class="input in-button" value="<?php echo __('upload')?>"/>
                <input type="button" onclick="closeCover('uploadroute')" style="margin-bottom:6px;" class="input in-button" value="<?php echo __('cancel')?>"/>
            </div>
        </form>
    </div>
    <div id="uploadroute_error"  style="display:none;height: auto;z-index:99;position: absolute;left:30%;top: 20%;" class="form_panel_upload"> <span class="wordFont1 marginSpan1"><span style="color:red" id="affectrows"></span>&nbsp;&nbsp;<?php echo __('erroroccured')?>:</span>
        <div style="height: auto;text-align:left;" id="route_upload_errorMsg" class="up_panel_upload"></div>
        <div class="form_panel_button_upload"> <span style="float:left"><?php echo __('downloadtempfile')?> .<a href="<?php echo $this->webroot?>products/downloadtemplate" style="color:red"><?php echo __('clickhere')?></a></span>
            <input type="button" onclick="closeCover('uploadroute_error')" style="margin-bottom:6px;" class="input in-button" value="<?php echo __('close')?>"/>
        </div>
    </div>
    <table class="list">
        <thead>
            <tr>
                <td><input id="selectAll" type="checkbox" onclick="checkAllOrNot(this,'routetab');" value=""/></td>
                <!--<td><?php echo $appCommon->show_order('item_id',__('ID',true))?></td>-->
                <td><?php echo ($code_type == 0)?$appCommon->show_order('digits',__('Code',true)):$appCommon->show_order('code_name',"Code Name");?></td>
                <!--<td><?php echo $appCommon->show_order('route_type',__('Type',true))?></td>-->
                <td><?php echo $appCommon->show_order('strategy',__('Strategy',true))?></td>
                <td><?php echo $appCommon->show_order('time_profile',__('Time Profile',true))?></td>
                <td colspan="8"><?php echo __('Trunk List',true);?></td>
                <td><?php echo __('Update At',true);?></td>
                <td><?php echo __('Update By',true);?></td>
                <?php  if ($_SESSION['role_menu']['Routing']['products']['model_w']) {?> <td class="last"><?php echo __('action')?></td>
                <?php }?>
            </tr>
        </thead>
        <tbody id="routetab">
            <?php
            $mydata = $p->getDataArray();
            //var_dump($mydata);
            $loop = count($mydata);
            for ($i = 0;$i<$loop;$i++) { 
            ?>
            <tr class="row-1">
                <td style="text-align:center"><input class="select chkitem"  type="checkbox" value="<?php echo $mydata[$i][0]['item_id']?>"/></td>
                <!--<td class="in-decimal"><?php echo $mydata[$i][0]['item_id']?></td>-->
                <td>
                    <?php if($code_type == 0){
                    echo $mydata[$i][0]['digits'];
                    }else{
                    echo $mydata[$i][0]['code_name'];
                    }
                    ?>
                </td>
                <td><?php echo _filter_array(Array(1=>'Top-Down',0=>'By Percentage',2=>'Round-Robin'),$mydata[$i][0]['strategy'])?></td>
                <td><?php echo $mydata[$i][0]['time_profile']?></td>
                <td colspan="8"><?php echo str_replace('"', '', trim($mydata[$i][0]['alias'],'{}'));?></td>
                <td><?php echo $mydata[$i][0]['update_at']?></td>
                <td><?php echo $mydata[$i][0]['update_by']?></td>
                <?php  if ($_SESSION['role_menu']['Routing']['products']['model_w']) {?>
                <td class="last">
                    <a class="qos_edit" item="<?php echo $mydata[$i][0]['item_id']?>" href="###" title="QoS parameters">
                        <img src="<?php echo $this->webroot?>images/dynamic_qos.png">
                    </a>
                    <a title="Edit"  href="###"> <img src="<?php echo $this->webroot?>images/editicon.gif" /> </a> 
                    <?php
                    if($code_type == 0){
                    ?>
                    <a title="<?php echo __('del')?>" href="javascript:void(0)" onclick="ex_delConfirm(this,'<?php echo $this->webroot?>products/del/<?php echo $mydata[$i][0]['item_id'];?>/<?php echo $id?>','static route  <?php echo $code_type==0? $mydata[$i][0]['digits']:$mydata[$i][0]['code_name']?>');"> <img src="<?php echo $this->webroot?>images/delete.png" /> </a></td>
                <?php
                }else{
                ?>
        <a title="<?php echo __('del')?>" href="javascript:void(0)" onclick="ex_delConfirm(this,'<?php echo $this->webroot?>products/del_code_name/<?php echo $mydata[$i][0]['item_id'];?>/<?php echo $id?>','static route  <?php echo $code_type==0? $mydata[$i][0]['digits']:$mydata[$i][0]['code_name']?>');"> <img src="<?php echo $this->webroot?>images/delete.png" /> </a></td>
        <?php    
        }

        ?>

        <?php }?>
        </tr>
        <?php }?>
        </tbody>
    </table>
    <div id="tmppage"> <?php echo $this->element('page');?> </div>
    <div style="clear:both; height:10px;"></div>
    <fieldset id="b-me">
        <legend> <a href="#" onclick="$('#b-me').hide();$('#b-me-full').show();return false;"> <span id="ht-100007"  >Mass Edit »</span> </a> </legend>
    </fieldset>
    <form action="<?php echo $this->webroot?>products/<?php echo $code_type == 0 ?'updateselect':'updateselectCodeName' ?>/<?php echo array_keys_value($this->params,'pass.0')?>" method="post" id="actionForm">
        <input type="hidden" class="input in-hidden" name="select_id" value="" id="id"/>
        <input type="hidden" class="input in-hidden" name="stage" value="preview" id="stage_param"/>
        <fieldset id="b-me-full" style="display: none;">
            <legend> <a href="#" id="manyup" onclick="$('#b-me').show();$('#b-me-full').hide();return false;">Mass Edit Action:</a>
                <select class="input in-select select" name="action" id="action">
                    <option value="update">update current Static Route Table</option>
                    <option value="delete">delete found Static Route Table</option>
                </select>
            </legend>
            <div style="display: block;" id="actionPanelEdit">
                <table cellspacing="0" cellpadding="0" border="0" class="mylist">
                    <tbody>
                        <tr>
                            <td class="label" style="width:18%;min-width:204px;"><label> <span id="ht-100008" class="helptip" rel="helptip"><?php echo __('Strategy',true);?></span> <span id="ht-100008-tooltip" class="tooltip"></span>: </label>
                                <select class="input in-select select" name="route_strategy_options" id="route_strategy_options">
                                    <option value="none">preserve</option>
                                    <option value="set">set to</option>
                                </select></td>
                            <td class="value" style="width:15%"><select class="input in-select select" name="strategy" id="strategy">
                                    <option value="1">» Top-Down</option>
                                    <option value="0">» By Percentage </option>
                                    <option value="2">» Round Robin</option>
                                </select></td>
                            <td class="label" style="width:15%"><label> <span id="ht-100009" class="helptip" rel="helptip"><?php echo __('Time Profile',true);?></span>: </label>
                                <select class="input in-select select" name="route_time_profile_options" id="route_time_profile_options">
                                    <option value="none">preserve</option>
                                    <option value="set">set to</option>
                                </select></td>
                            <td class="value" style="width:15%"><?php echo $form->input('time_profile',Array('div'=>false,'style'=>'width:80px','name'=>'time_profile','label'=>false,'options'=>$appProduct->_get_select_options($TimeProfile,'TimeProfile','time_profile_id','name')))?></td>
                        </tr>
                    </tbody>
                </table>
                <?php
                $trunks  = $appProduct->_get_select_options($Resource,'Resource','resource_id','alias');
                //ksort($trunks);
                ?>
                <table>
                    <tr style="height:0px">
                        <td colspan=15><div style="padding:5px;display:block">
                                <div style="float:left;">
                                    <input type="button" value="Add" id="addbtn1" />
                                </div>
                                <table class="mylist" id="tbl1">
                                    <thead>
                                        <tr>
                                            <td><?php echo __('Trunk Number',true);?></td>
                                            <td><?php echo __('Trunk',true);?></td>
                                            <td><?php echo __('Percentage',true);?></td>
                                            <td><?php echo __('action',true);?></td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="row-1">
                                            <td style="width:19%">1</td>
                                            <td style="width:27%"><?php echo $form->input('trunk',Array('div'=>false,'style'=>'width:200px','label'=>false,'name'=>"trunk[]",'options'=>$trunks))?></td>
                                            <td style="width:27%"><input type="text" id="percentage" name="percent[]" /></td>
                                            <td style="width:10%"><img src="<?php echo $this->webroot?>images/delete.png"  onclick="$(this).closest('tr').remove();" /></td>
                                        </tr>
                                    </tbody>
                                </table>
                                <?php  if ($_SESSION['role_menu']['Routing']['products']['model_w']) {?><input id="uptbtn" type="button" value="<?php echo __('submit',true);?>" /><?php }?>
                            </div></td>
                    </tr>
                </table>
                <script type="text/javascript">
                    jQuery('#actionPanelEdit #strategy').change(function(){
                        if(jQuery(this).val()=='0'){
                            jQuery('#actionPanelEdit').find('input[id^=percentage]').show().val('');
                        }else{
                            jQuery('#actionPanelEdit').find('input[id^=percentage]').hide();
                        }
                    });
                </script> 
            </div>
        </fieldset>
    </form>
    <?php }?>
</div>
    <div id="dd">&nbsp;</div>   

<script type="text/javascript">					
    $(document).ready(function() {                    
        $('#addbtn1').click(function() {
            var row = $('#tbl1 tbody tr:last-child').clone();
            var num = $('td:first-child', row).text();
            $('td:first-child', row).text(++num);
            $('#tbl1 tbody').append(row);
        });
			
			
        $('#uptbtn').click(function() {
				
            var $checkbox = $('.chkitem:checked');
            var arr = new Array();
            $checkbox.each(function(index) {
                //if($(this).attr('checked')==true) {
                //var tmp = $(this).parent().siblings('td.in-decimal').text();
                var tmp = $(this).val();
                arr.push(tmp);
                //}
                if(arr.length == 0){
                    return false;
                }	
            });
            var sid = arr.join(',');
            $('#actionForm input[name=select_id]').val(sid);
            if(jQuery('#strategy').val()==0){
                var percentage=0;
                jQuery('input[id^=percentage]').each(function(){
                    var value=jQuery(this).val();
                    if(value==''){
                        value=0;
                    }
                    percentage+=parseInt(value);
                });
                if(parseInt(percentage)!=100){
                    jQuery.jGrowlError('The sum of all percentage must be equal to 100');
                    return false;
                }
            }
                                
            var num = $("table.list :input[@type=checkbox][checked]").size();                                
            var msg = "You must select at least one record that you want to modify.";
            if(num==0){
                alert(msg);
                return false;
            }
            $('#actionForm').submit();
        });
			
    });
		
						
</script> 
<script type="text/javascript">
    var code_type = '<?php echo $code_type?>';
    var onsubmit=function(options){ 
        var re=true;
        var digits=jQuery('#'+options.log).find('#digits').val();
        var time_profile = jQuery('#'+options.log).find('#time_profile_id').val();
        var strategy = jQuery('#'+options.log).find('#strategy').val();
        
        
        if(strategy == "0")
        {
            //var $percentages = jQuery('#'+options.log).find('#percentage[]');
            //alert($percentages);
            var $percentages = $('input[name=percentage[]]');
            var flag = false;
            $percentages.each(function(index, item) {
                if(isNaN($(this).val()))
                {
                    flag = true;
                    return true;
                }
            });
            if (flag)
            {
                jQuery.jGrowlError('Percentage must be whole number!');
            }
        }
        
        if(!options.route_info_id){
            options.route_info_id='';
        }
        if(digits=="") {
            digits = "empty";
        }
        var data=jQuery.ajaxData("<?php echo $this->webroot?>products/check_route_info_name/"+options.route_info_id+"?name="+digits+"&product_id=<?php echo array_keys_value($this->params,'pass.0') ?>");
        //if(digits==''){
        //	jQuery.jGrowlError('Prefix, cannot be null！');
        //	re=false;
        //}
        if(code_type ==0 && digits!='empty'){
            if(!parseInt(digits)){
                jQuery.jGrowlError('Prefix, must be whole number!');
                re=false;
            }
        }
	
	
		
                
        //时间段是否有交集
        var time_profile_diff =  $.ajaxData("<?php echo $this->webroot?>products/check_time_profile/"+ digits + '/' +time_profile+"/<?php echo array_keys_value($this->params,'pass.0') ?>/"+options.route_info_id);        
                   
              
        if(time_profile_diff == "not" && data.indexOf('false')!=-1){
            jQuery.jGrowlError(digits+' use the same time profile');
            re=false;
        }
              
                
                
        var temp_arr=Array();
        jQuery('select[id^=Trunk]').each(function(){

            if(jQuery(this).val()==''){
                return;
            }
            for(var i in temp_arr){
                if(jQuery(this).val()==temp_arr[i]){
                    jQuery.jGrowlError("Trunk "+temp_arr[i]+' is repeat!');
                    re=false;
                    return ;
                }
            }
            temp_arr.push(jQuery(this).val());
        });
        if(jQuery('#strategy').val()==0){
            var percentage=0;
            jQuery('input[id^=percentage]').each(function(){
                var value=jQuery(this).val();
                if(value==''){
                    value=0;
                }
                percentage+=parseInt(value);
            });
            if(parseInt(percentage)!=100){
                jQuery.jGrowlError('The sum of all percentage must be equal to 100');
                re=false;
            }
        }
        return re;
    }
    jQuery('a[title=Edit]').click(function(){
        var route_info_id=jQuery(this).parent().parent().find('input:nth-child(1)').val();
        var code_name=jQuery(this).parent().parent().get(0).cells[1].innerHTML;
        
        jQuery(this).parent().parent().trAdd({
            ajax:(code_type==0)?"<?php echo $this->webroot?>products/js_save_prefix/"+route_info_id:"<?php echo $this->webroot?>products/js_save_code_name/<?php echo $id;?>/"+$.trim(code_name),
            action:(code_type==0)?"<?php echo $this->webroot?>products/edit_route/"+route_info_id+"/<?php echo array_keys_value($this->params,'pass.0')?>":"<?php echo $this->webroot?>products/edit_route_code_name/"+$.trim(code_name)+"/<?php echo array_keys_value($this->params,'pass.0')?>",
            tag:'form>table tr',
            callback:function(){
                strategy(jQuery('#strategy'));
            },
            onsubmit:onsubmit,
            saveType:'edit',
            route_info_id:route_info_id
        });
        return false;
    });


    jQuery('#add').click(function(){
        
        
        jQuery('table.list').show();
        jQuery('table.list').trAdd({
            ajax:(code_type==0)?"<?php echo $this->webroot?>products/js_save_prefix":"<?php echo $this->webroot?>products/js_save_code_name/<?php echo $id;?>",
            action:(code_type==0)?"<?php echo $this->webroot?>products/add_route/<?php echo array_keys_value($this->params,'pass.0')?>":"<?php echo $this->webroot?>products/add_route_code_name/<?php echo array_keys_value($this->params,'pass.0')?>",
            tag:'form > table tr',
            onsubmit:onsubmit,
            callback:function(){
                strategy(jQuery('#strategy'));
            },
            removeCallback:function(){if(jQuery('table.list tr').size()<2){jQuery('table.list tr').hide();}}
        });
        return false;
    });
</script> 
<script type="text/javascript">
    jQuery(document).ready(function(){
        jQuery('#selectAll').selectAll('.select');
    });
    jQuery(document).ready(function(){
        jQuery('#route_strategy_options').change(function(){
            if(jQuery(this).val()=='none'){
                jQuery('#strategy').hide().val('').change();
            }else{
                jQuery('#strategy').show();
            }
        }).change();
        jQuery('#route_time_profile_options').change(function(){
            if(jQuery(this).val()=='none'){
                jQuery('#time_profile').hide().val('');
            }else{
                jQuery('#time_profile').show();
            }
        }).change();
		
        jQuery('input[id^=percentage]').xkeyvalidate({type:'Int'});
    });
</script> 

<!-- 上传文件 如果有错误信息则显示 -->
<?php 
$upload_error = $session->read('upload_route_error');
if (!empty($upload_error)) {
$session->del('upload_route_error');
$affectRows = $session->read('upload_commited_rows');
$session->del('upload_commited_rows');
?>
<script language=JavaScript>
    //提交的行数
    document.getElementById("affectrows").innerHTML = "<?php echo $affectRows?>";
    //错误信息
    var errormsg = eval("<?php echo $upload_error?>");
    var loop = errormsg.length;
    var msg = "";
    for (var i = 1;i<=loop; i++) {
        msg += errormsg[i-1].row+"<?php echo __('row')?>"+" : "+errormsg[i-1].name+errormsg[i-1].msg+"&nbsp;&nbsp;&nbsp;&nbsp;";
        if (i % 2 == 0) {
            msg += "<br/>";
        }

        if (i == loop) {
            msg += "<p>&nbsp;&nbsp;<p/>";
        }
        document.getElementById('route_upload_errorMsg').innerHTML = msg;
    }
    cover('uploadroute_error');
</script>
<?php }?>

<link rel="stylesheet" type="text/css" href="<?php echo $this->webroot; ?>easyui/themes/default/easyui.css">
<link rel="stylesheet" type="text/css" href="<?php echo $this->webroot; ?>easyui/themes/icon.css">
<script type="text/javascript" src="<?php echo $this->webroot; ?>easyui/jquery.easyui.min.js"></script>

<script>
function checkint(input, name)
{
    var re = /^[0-9]+$/; //判断字符串是否为数字 //判断正整数 /^[1-9]+[0-9]*]*$/
    if (!re.test(input))
    {
        jQuery.jGrowl(name + " must be numberic.",{theme:'jmsg-error'}); 
        return false;
    }
    return true;
} 

function checklarge(input, max, name)
{
    if(parseInt(input) > parseInt(max)) {
        jQuery.jGrowl(name + " can not greater than " + max + " .",{theme:'jmsg-error'}); 
        return false;
    }
    return true;    
}

function checkless(input, min, name) {
    if(parseInt(input) < parseInt(min)) {
        jQuery.jGrowl(name + " can not less than " + min + " .",{theme:'jmsg-error'}); 
        return false;
    }
    return true;  
}

$(function() {

    // extend validatebox alpha
    $.extend($.fn.validatebox.defaults.rules, {  
        number: {  
            validator: function(value){  
                return /^[0-9].*$/.test(value);  
            },  
            message: 'Field must contain only numbers.'  
        }  
    });
    
    $('.qos_edit').click(function() {

        var item_id = $(this).attr('item');

        $('#dd').dialog({  
            title: 'Trunk Priority',  
            width: 400,  
            height: 300,  
            closed: false,  
            cache: false,  
            href: '<?php echo $this->webroot ?>products/qos/' + item_id + '/<?php echo $this->params['pass'][0] ?>',  
            modal: true,
            onBeforeOpen : function() {
                $('#dd').dialog('refresh');
            },
            onLoad: function() {

                var $dialog_form = $('form', '.dialog_form');

                $dialog_form.submit(function() {

                    var min_asr = $("input[name=min_asr]", $dialog_form).val();
                    var max_asr = $("input[name=max_asr]", $dialog_form).val();
                    var min_abr = $("input[name=min_abr]", $dialog_form).val();
                    var max_abr = $("input[name=max_abr]", $dialog_form).val();
                    var min_acd = $("input[name=min_acd]", $dialog_form).val();
                    var max_acd = $("input[name=max_acd]", $dialog_form).val();
                    var min_pdd = $("input[name=min_pdd]", $dialog_form).val();
                    var max_pdd = $("input[name=max_pdd]", $dialog_form).val();
                    var min_aloc = $("input[name=min_aloc]", $dialog_form).val();
                    var max_aloc = $("input[name=max_aloc]", $dialog_form).val();
                    var max_price = $("input[name=max_price]", $dialog_form).val();

                

                    if (min_asr != '')
                    {
                        if(!checkint(min_asr, "Min ASR")) {
                            return false;
                        }
                        if(!checklarge(min_asr, 100, "Min ASR")) {
                            return false;
                        }
                    }
                    if (max_asr != '')
                    {
                        if(!checkint(max_asr, "Max ASR")) {
                            return false;
                        }
                        if(!checklarge(max_asr, 100, "MAX ASR")) {
                            return false;
                        }
                    }
                    if (min_abr != '')
                    {
                        if(!checkint(min_abr, "Min ABR")) {
                            return false;
                        }
                        if(!checklarge(min_abr, 100, "Min ABR")) {
                            return false;
                        }
                    }
                    if (max_abr != '')
                    {
                        if(!checkint(max_abr, "Max ABR")) {
                            return false;
                        }
                        if(!checklarge(max_abr, 100, "Max ABR")) {
                            return false;
                        }
                    }
                    if (min_acd != '')
                    {
                        if(!checkint(min_acd, "Min ACD")) {
                            return false;
                        }
                    }
                    if (max_acd != '')
                    {
                        if(!checkint(max_acd, "Max ACD")) {
                            return false;
                        }
                    }
                    if (min_pdd != '')
                    {
                        if(!checkint(min_pdd, "Min PDD")) {
                            return false;
                        }
                    }
                    if (max_pdd != '')
                    {
                        if(!checkint(max_pdd, "Max PDD")) {
                            return false;
                        }
                    }
                    if (min_aloc != '')
                    {
                        if(!checkint(min_aloc, "Min ALOC")) {
                            return false;
                        }
                    }
                    if (max_aloc != '')
                    {
                        if(!checkint(max_aloc, "Max ALOC")) {
                            return false;
                        }
                    }
                    if (max_price != '')
                    {
                        if(!checkint(max_price, "Max Price")) {
                            return false;
                        }
                    }
                   

                });
                
            }
        }); 

        

        return false;

    });

    

    
    

});
</script>

<script type="text/javascript">
    function strategy(obj,val){
        var value =jQuery(obj).val();
        if(val){
            value=val;
        }
        if(value==1||value==2){
            jQuery('.dd').hide().val('');
        }
        if(value==0){
            jQuery('.dd').show();
        }
    }
    function client(obj){
        value=jQuery(obj).val();
        var data=jQuery.ajaxData('<?php echo $this->webroot ?>/trunks/ajax_options?filter_id='+value+'&type=egress&trunk_type2=0');
        data=eval(data);
        var temp=jQuery(obj).parent().parent().find('select').eq(1).val();
        jQuery(obj).parent().parent().find('select').eq(1).html('');
        jQuery('<option>').appendTo(jQuery(obj).parent().parent().find('select').eq(1));
        for(var i in data){
            var temp=data[i];
            jQuery('<option>').html(temp.alias).val(temp.resource_id).appendTo(jQuery(obj).parent().parent().find('select').eq(1));
        }
        jQuery(obj).parent().parent().find('select').eq(1).val(temp);
                                
    }
</script> 
<script type="text/javascript">
			
    $(document).ready(function() {
        $('#addbtn').live('click',function() {
            var $tr = $('#tbl tbody tr:last-child');
            var row = $tr.clone(true);
            if($tr.hasClass('row-1')) {
                row.removeClass('row-1').addClass('row-2');	
            } else {
                row.removeClass('row-2').addClass('row-1');
            }
            var num = $('td:first-child', row).text();
            $('td:first-child', row).text(++num);
            $('#tbl tbody').append(row);
		$('#tbl tr:last').find('select[name=Carriers1]').val('0');
            $('#tbl tr:last').find('select[name=resource_id[]]').empty();
            $('#tbl tr:last').find('input[name=percentage[]]').val('');					
        });
    });
                        
    $(document).ready(function() {
        $('.changeup').live('click', function() {
            var $pr = $(this).parent().parent().prev();
            var $tr = $(this).parent().parent().insertBefore($pr);
        });
        $('.changedown').live('click', function() {
            var $pr = $(this).parent().parent().next();
            var $tr = $(this).parent().parent().insertAfter($pr);
        });
    });
                        
    function get_rate(obj) {
        var val = $(obj).val();
        var prefix = $("input[name=digits]").val();
        //if(val != '') {
        var data=jQuery.ajaxData('<?php echo $this->webroot ?>/products/get_rate/' + val + '/' + prefix);
        if(isNaN(data)) data = 0;
        $(obj).parent().next().text(new Number(data).toFixed(5));
        //}
    }

</script> 
