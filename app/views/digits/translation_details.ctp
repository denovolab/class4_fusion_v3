<script src="<?php echo $this->webroot?>js/BubbleSort.js" type="text/javascript"></script>

<div id="title">
    <h1> <?php echo __('Routing',true);?>&gt;&gt;
        <?php echo __('Detail',true);?>: <font class="editname" title="Name"> <?php echo empty($name[0][0]['name'])||$name[0][0]['name']==''? '': "[".$name[0][0]['name']."]" ; ?> </font> </h1>
    <ul id="title-search">
        <li>
            <form>
                <input type="text" id="search-_q" class="in-search default-value input in-text defaultText" title="<?php echo __('prefixsearch')?>" value="<?php if (!empty($search)) echo $search;?>" name="search">
            </form>
        </li>
    </ul>
    <ul id="title-menu">

        <?php  if ($_SESSION['role_menu']['Routing']['digits']['model_w']) {?>
        <li><a class="link_btn" id="add" href="<?php echo $this->webroot?>digits/add_tran_detail/<?php echo $id?>"><img width="16" height="16" alt="" src="<?php echo $this->webroot?>images/add.png"> <?php echo __('createnew')?></a></li>
        <li><a class="link_btn" rel="popup" href="javascript:void(0)" onclick="deleteAll('<?php echo $this->webroot?>digits/del_all_details/<?php echo $id?>');" ><img width="16" height="16" alt="" src="<?php echo $this->webroot?>images/delete.png"> <?php echo __('deleteall')?></a></li>
        <li><a class="link_btn" rel="popup" href="javascript:void(0)" onclick="ex_deleteSelected('tran_details_tab','<?php echo $this->webroot?>digits/del_selected_details?id='+<?php echo $id?>,'routing detail');"><img width="16" height="16" alt="" src="<?php echo $this->webroot?>images/delete.png"> <?php echo __('deleteselected')?></a></li>
        <?php }?>
        <li> <a class="link_back" href="<?php echo $this->webroot?>digits/view"> <img width="10" height="5" alt="" src="<?php echo $this->webroot?>images/icon_back_white.png"> <?php echo __('goback')?> </a> </li>
    </ul>
</div>
<div id="container">
    <ul class="tabs">
        <li  class="active"><a href="<?php echo $this->webroot ?>digits/translation_details/<?php echo $id?>"><img width="16" height="16" src="<?php echo $this->webroot ?>images/menuIcon.gif"> List</a></li>
        <?php  if ($_SESSION['role_menu']['Routing']['digits']['model_x']) {?>
        <li><a href="<?php echo $this->webroot ?>uploads/digit_translation/<?php echo $id?>"><img width="16" height="16" src="<?php echo $this->webroot ?>images/import.png"> Import</a></li>
        <li ><a href="<?php echo $this->webroot ?>down/digit_mapping_down/<?php echo $id?>"><img width="16" height="16" src="<?php echo $this->webroot ?>images/export.png"> Export</a></li>
        <?php }?>
    </ul>
    <?php $d = $p->getDataArray();if (count($d) == 0) {?>
    <div class="msg"><?php echo __('no_data_found')?></div>
    <table class="list" style="display:none">
        <col style="width: 5%;">
        <col style="width: 14%;">
        <col style="width: 14%;">
        <col style="width: 14%;">
        <col style="width: 14%;">
        <col style="width: 14%;">
        <col style="width: 14%;">
        <col style="width: 11%;">
        <thead>
            <tr>
                <td><input type="checkbox" onclick="checkAllOrNot(this,'tran_details_tab');" value=""/></td>
                <td><?php echo __('origani')?>&nbsp; <a class="sort_asc sort_sctive" href="javascript:void(0)" onclick="my_sort('ani','asc');"> <img width="10" height="7" src="<?php echo $this->webroot?>images/p.png"> </a> &nbsp;<a class="sort_dsc"  href="javascript:void(0)" onclick="my_sort('ani','desc');"> <img width="10" height="7" src="<?php echo $this->webroot?>images/p.png"> </a></td>
                <td><?php echo __('origdnis')?>&nbsp;<a class="sort_asc sort_sctive" href="javascript:void(0)" onclick="my_sort('dnis','asc');"> <img width="10" height="7" src="<?php echo $this->webroot?>images/p.png"> </a> &nbsp; <a  class="sort_dsc" href="javascript:void(0)" onclick="my_sort('dnis','desc');"> <img width="10" height="7" src="<?php echo $this->webroot?>images/p.png"> </a></td>
                <td><?php echo __('translatedani')?>&nbsp;<a class="sort_asc sort_sctive" href="javascript:void(0)" onclick="my_sort('action_ani','asc');"> <img width="10" height="7" src="<?php echo $this->webroot?>images/p.png"> </a> &nbsp; <a class="sort_dsc"  href="javascript:void(0)" onclick="my_sort('action_ani','desc');"> <img width="10" height="7" src="<?php echo $this->webroot?>images/p.png"> </a></td>
                <td><?php echo __('translateddnis')?>&nbsp;<a class="sort_asc sort_sctive" href="javascript:void(0)" onclick="my_sort('action_dnis','asc');"> <img width="10" height="7" src="<?php echo $this->webroot?>images/p.png"> </a> &nbsp; <a class="sort_dsc"  href="javascript:void(0)" onclick="my_sort('action_dnis','desc');"> <img width="10" height="7" src="<?php echo $this->webroot?>images/p.png"> </a></td>
                <td><?php echo __('aniaction')?>&nbsp;<a class="sort_asc sort_sctive" href="javascript:void(0)" onclick="my_sort('ani_method','asc');"> <img width="10" height="7" src="<?php echo $this->webroot?>images/p.png"> </a> &nbsp; <a  class="sort_dsc" href="javascript:void(0)" onclick="my_sort('ani_method','desc');"> <img width="10" height="7" src="<?php echo $this->webroot?>images/p.png"> </a></td>
                <td><?php echo __('dnisaction')?>&nbsp; <a class="sort_asc sort_sctive" href="javascript:void(0)" onclick="my_sort('dnis_method','asc');"> <img width="10" height="7" src="<?php echo $this->webroot?>images/p.png"> </a> &nbsp;<a class="sort_dsc"  href="javascript:void(0)" onclick="my_sort('dnis_method','desc');"> <img width="10" height="7" src="<?php echo $this->webroot?>images/p.png"> </a></td>
                <?php  if ($_SESSION['role_menu']['Routing']['digits']['model_w']) {?>
                <td class="last"><?php echo __('action')?></td>
                <?php }?>
            </tr>
        </thead>
        <tbody id="tran_details_tab">
        </tbody>
    </table>
    <?php } else {?>
    <div id="toppage"></div>
    <div id="cover"></div>
    <div id="uploadroute"  style="overflow:hidden;width:500px;display:none;height: auto;z-index:99;position: absolute;left:30%;top: 20%;" class="form_panel_upload">
        <form action="<?php echo $this->webroot?>digits/tran_upload/<?php echo $id?>" method="post" enctype="multipart/form-data" id="productFile">
            <span class="wordFont1 marginSpan1"><?php echo __('selectfile')?>:</span>
            <div style="width:100%;height:60px;" class="up_panel_upload">
                <input style="margin-top:10px;" type="file" value="Upload" size="45" class="input in-text" id="browse" name="browse">
                <div>
                    <input style="margin-left:50%;" type="checkbox" checked onclick="if(this.value=='false')this.value='true';else this.value='false';document.getElementById('isRoll').value=this.value;">
                    <input type="hidden" value="true" name="isRoll" id="isRoll"/>
                    <span><?php echo __('rollbackonfail')?> </span> </div>
            </div>
            <div class="form_panel_button_upload"> <span style="float:left"><?php echo __('downloadtempfile')?> .<a href="<?php echo $this->webroot?>products/downloadtemplate/t" style="color:red"><?php echo __('clickhere')?></a></span>
                <input type="submit" class="input in-button" value="<?php echo __('upload')?>"/>
                <input type="button" onclick="closeCover('uploadroute')" style="margin-bottom:6px;" class="input in-button" value="<?php echo __('cancel')?>"/>
            </div>
        </form>
    </div>
    <div id="uploadroute_error"  style="display:none;height: auto;z-index:99;position: absolute;left:30%;top: 20%;" class="form_panel_upload"> <span class="wordFont1 marginSpan1"><span style="color:red" id="affectrows"></span>&nbsp;&nbsp;<?php echo __('erroroccured')?>:</span>
        <div style="height: auto;text-align:left;" id="route_upload_errorMsg" class="up_panel_upload"></div>
        <div class="form_panel_button_upload"> <span style="float:left"><?php echo __('downloadtempfile')?> .<a href="<?php echo $this->webroot?>products/downloadtemplate/t" style="color:red"><?php echo __('clickhere')?></a></span>
            <input type="button" onclick="closeCover('uploadroute_error')" style="margin-bottom:6px;" class="input in-button" value="Close"/>
        </div>
    </div>
    <table class="list">

        <thead>
            <tr>
                <td><input type="checkbox" onclick="checkAllOrNot(this,'tran_details_tab');" value=""/></td>
                <td><?php echo __('origani')?>&nbsp;<a class="sort_asc sort_sctive" href="javascript:void(0)" onclick="my_sort('ani','asc');"> <img width="10" height="7" src="<?php echo $this->webroot?>images/p.png"> </a> <a class="sort_dsc"  href="javascript:void(0)" onclick="my_sort('ani','desc');"> <img width="10" height="7" src="<?php echo $this->webroot?>images/p.png"> </a></td>
                <td><?php echo __('origdnis')?>&nbsp;<a class="sort_asc sort_sctive" href="javascript:void(0)" onclick="my_sort('dnis','asc');"><img width="10" height="7" src="<?php echo $this->webroot?>images/p.png" /></a> <a  class="sort_dsc" href="javascript:void(0)" onclick="my_sort('dnis','desc');"> <img width="10" height="7" src="<?php echo $this->webroot?>images/p.png"> </a></td>
                <td><?php echo __('translatedani')?>&nbsp;<a class="sort_asc sort_sctive" href="javascript:void(0)" onclick="my_sort('action_ani','asc');"> <img width="10" height="7" src="<?php echo $this->webroot?>images/p.png"> </a> <a  class="sort_dsc" href="javascript:void(0)" onclick="my_sort('action_ani','desc');"> <img width="10" height="7" src="<?php echo $this->webroot?>images/p.png"> </a></td>
                <td><?php echo __('translateddnis')?>&nbsp; <a class="sort_asc sort_sctive" href="javascript:void(0)" onclick="my_sort('action_dnis','asc');"> <img width="10" height="7" src="<?php echo $this->webroot?>images/p.png"> </a><a class="sort_dsc"  href="javascript:void(0)" onclick="my_sort('action_dnis','desc');"> <img width="10" height="7" src="<?php echo $this->webroot?>images/p.png"> </a></td>
                <td><?php echo __('aniaction')?>&nbsp; <a class="sort_asc sort_sctive" href="javascript:void(0)" onclick="my_sort('ani_method','asc');"> <img width="10" height="7" src="<?php echo $this->webroot?>images/p.png"> </a> <a class="sort_dsc"  href="javascript:void(0)" onclick="my_sort('ani_method','desc');"> <img width="10" height="7" src="<?php echo $this->webroot?>images/p.png"> </a></td>
                <td><?php echo __('dnisaction')?>&nbsp; <a class="sort_asc sort_sctive" href="javascript:void(0)" onclick="my_sort('dnis_method','asc');"> <img width="10" height="7" src="<?php echo $this->webroot?>images/p.png"> </a><a class="sort_dsc"  href="javascript:void(0)" onclick="my_sort('dnis_method','desc');"> <img width="10" height="7" src="<?php echo $this->webroot?>images/p.png"> </a></td>
                <?php  if ($_SESSION['role_menu']['Routing']['digits']['model_w']) {?> <td class="last"><?php echo __('action')?></td>
                <?php }?>
            </tr>
        </thead>
        <tbody id="tran_details_tab">
            <?php
            $mydata = $p->getDataArray();
            $loop = count($mydata);
            for ($i = 0;$i<$loop;$i++) { 
            ?>
            <tr class="row-1">
                <td style="text-align:center"><input type="checkbox" value="<?php echo $mydata[$i][0]['ref_id']?>"/></td>
                <td  style="font-weight: bold;"><?php echo $mydata[$i][0]['ani']?></td>
                <td><?php echo $mydata[$i][0]['dnis']?></td>
                <td><?php echo $mydata[$i][0]['action_ani']?></td>
                <td><?php echo $mydata[$i][0]['action_dnis']?></td>
                <td><?php 
                    if ($mydata[$i][0]['ani_method'] == 0) echo __('ignore');
                    else if ($mydata[$i][0]['ani_method'] == 1) echo __('compare');
                    else if ($mydata[$i][0]['ani_method'] == 2) echo __('replace');
                    ?></td>
                <td><?php 
                    if ($mydata[$i][0]['dnis_method'] == 0) echo __('ignore');
                    else if ($mydata[$i][0]['dnis_method'] == 1) echo __('compare');
                    else if ($mydata[$i][0]['dnis_method'] == 2) echo __('replace');
                    ?></td>
                <?php  if ($_SESSION['role_menu']['Routing']['digits']['model_w']) {?>
                <td><a title="Edit" ref_id="<?php echo $mydata[$i][0]['ref_id']?>" style="float:left;margin-left:5px;" href="#<?php echo $this->webroot?>digits/edit_tran_detail/<?php echo $mydata[$i][0]['ref_id']?>"> <img src="<?php echo $this->webroot?>images/editicon.gif" /> </a> <a title="delete" style="float:left;margin-left:5px;" href="javascript:void(0)" onclick="ex_delConfirm(this,'<?php echo $this->webroot?>digits/del_tran_detail/<?php echo $mydata[$i][0]['ref_id'];?>/<?php echo $id?>','number translation');"> <img src="<?php echo $this->webroot?>images/delete.png" /> </a></td>
                <?php }?>
            </tr>
            <?php }?>
        </tbody>
        <tbody>
        </tbody>
    </table>
    <div id="tmppage"> <?php echo $this->element('page');?> </div>
    <?php }?>
</div>
<script type="text/javascript">
    jQuery('#add').click(function(){
        jQuery('table.list').show().trAdd({
            action:'<?php echo $this->webroot?>digits/js_save/<?php echo $$hel->getPass(0) ?>',
            ajax:'<?php echo $this->webroot?>digits/js_save/<?php echo $$hel->getPass(0) ?>',
            callback:trAdd_callback,
            onsubmit:trAdd_onsubmit,
            removeCallback:function(){if(jQuery('table.list tr').size()==1){jQuery('table.list').hide()}}
        });
        return false;
    });
    jQuery('a[title=Edit]').click(function(){
        var ref_id=jQuery(this).attr('ref_id');
        jQuery(this).parent().parent().trAdd({
            action:'<?php echo $this->webroot?>digits/js_save/<?php echo $$hel->getPass(0) ?>/'+ref_id,
            ajax:'<?php echo $this->webroot?>digits/js_save/<?php echo $$hel->getPass(0) ?>/'+ref_id,
            saveType:'edit',
            callback:trAdd_callback,
            onsubmit:trAdd_onsubmit
        });
        return false;
    });
    function trAdd_callback(options){
        //jQuery('table.list').find('input[id!=TranslationItemAni]').xkeyvalidate({type:'Num'});
    }
    function trAdd_onsubmit(options){
        var re=true;
        var ani =jQuery('table.list').find('input[id=TranslationItemDnis]').val();
        var dnis = jQuery('table.list').find('input[id=TranslationItemDnis]').val();
        var t_ani = jQuery('table.list').find('input[id=TranslationItemActionAni]').val();
        var t_dnis = jQuery('table.list').find('input[id=TranslationItemActionDnis]').val();
	
        if(jQuery('table.list').find('input[id=TranslationItemAni]').val()=='' && jQuery('table.list').find('input[id=TranslationItemDnis]').val()=='' && jQuery('table.list').find('input[id=TranslationItemActionAni]').val()==''&&jQuery('table.list').find('input[id=TranslationItemActionDnis]').val()==''){
            jQuery.jGrowlError('The ANI, DNIS, Translated ANI,and Translated DNIS, Cannot Be Null !');
            re=false;
        }
        check_ani_dnis=/^(\w|#)*$/;
        if(!check_ani_dnis.test(jQuery('table.list').find('input[id=TranslationItemAni]').val())){
            jQuery.jGrowlError('ANI must be (a-z,A-Z,0-9,#)!');
            re=false;
        }
	
        if(!check_ani_dnis.test(jQuery('table.list').find('input[id=TranslationItemDnis]').val())){
            jQuery.jGrowlError('DNIS must be (a-z,A-Z,0-9,#)!');
            re=false;
        }
	
        if(!check_ani_dnis.test(jQuery('table.list').find('input[id=TranslationItemActionAni]').val())){
            jQuery.jGrowlError('Translated ANI must be (a-z,A-Z,0-9,#)!');
            re=false;
        }
	
	
        if(!check_ani_dnis.test(jQuery('table.list').find('input[id=TranslationItemActionDnis]').val())){
            jQuery.jGrowlError('Translated DNIS must be (a-z,A-Z,0-9,#)!');
            re=false;
        }
	
        var data=  jQuery.ajaxData("<?php echo $this->webroot?>digits/check_repeat/"+ani+"/"+dnis+"/"+t_ani+"/"+t_dnis);
        if(!data.indexOf('false')){
            jQuery.jGrowlError("The ANI, DNIS, Translated ANI,and Translated DNIS combination cannot be duplicate!");
            re=false;
        }
	
        return re;
    }
</script> 
<!-- 上传文件 如果有错误信息则显示 -->
<?php 
$upload_error = $session->read('upload_digit_error');
if (!empty($upload_error)) {
$session->del('upload_digit_error');
$affectRows = $session->read('upload_commited_rows');
$session->del('upload_commited_rows');
?>
<script type="text/javascript">
    //<![CDATA[
    //提交的行数
    document.getElementById("affectrows").innerHTML = "<?php echo $affectRows?>";
    //错误信息
    var errormsg = eval("<?php echo $upload_error?>");
    var loop = errormsg.length;
    var msg = "";
    for (var i = 1;i<=loop; i++) {
        msg += errormsg[i-1].row+"<?php echo __('row')?>"+":"+errormsg[i-1].name+errormsg[i-1].msg+"&nbsp;&nbsp;&nbsp;&nbsp;";
        if (i % 2 == 0) {
            msg += "<br/>";
        }
		
        if (i == loop) {
            msg += "<p>&nbsp;&nbsp;<p/>";
        }
        document.getElementById('route_upload_errorMsg').innerHTML = msg;
    }
    cover('uploadroute_error');
    //]]>
</script>
<?php 
}
?>
