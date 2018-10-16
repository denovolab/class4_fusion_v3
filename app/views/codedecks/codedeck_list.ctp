<div id="cover"></div>
<?php $d = $p->getDataArray(); ?>
<?php $w = $session->read('writable');?>
<div id="title">
    <h1>
        <?php __('Switch')?>&gt;&gt;
        <?php echo __('codedecks')?>
    </h1>
    <ul id="title-search">
        <li>
            <form>
                <input type="text" id="search-_q" class="in-search default-value input in-text defaultText" title="<?php echo __('namesearch')?>" value="<?php if (!empty($search)) echo $search;?>" name="search">
            </form>
        </li>
    </ul>
    <ul id="title-menu">
        <?php if (isset($edit_return)) {?>
        <li>
            <a class="link_back"href="<?php echo $this->webroot?>/codedecks/codedeck_list">
                <img width="16" height="16"  alt="" src="<?php echo $this->webroot?>images/icon_back_white.png">
                &nbsp;<?php echo __('goback')?>
            </a>
        </li>
        <?php }?>
        <?php if(array_keys_value($this->params,'url.viewType')=='rate'){?>
        <li>
            <a class="link_back" href="<?php echo $this->webroot?>rates/rates_list">
                <img width="16" height="16" src="<?php echo $this->webroot?>images/icon_back_white.png" alt=""/>
                 <?php echo __('goback',true);?>
            </a>
        </li>
        <?php }?>

        <!-- <li> <a class="link_btn" id="addcounty" href="#" ><img width="16" height="16"  src="<?php echo $this->webroot?>images/add.png"> <?php echo __('Add Country',true);?></img></a></li> -->
        <?php  if ($_SESSION['role_menu']['Switch']['codedecks']['model_w']) {?>
        <li><a class="link_btn" id="add" href="<?php echo $this->webroot?>codedecks/add_codedeck" ><img width="16" height="16" alt="" src="<?php echo $this->webroot?>images/add.png"> <?php echo __('createnew')?></a></li>
        <?php if (count($d) > 0): ?>
        <li><a class="link_btn" rel="popup" href="#" onclick="deleteAll('<?php echo $this->webroot ?>codedecks/del_code_deck/all');" ><img width="16" height="16" alt="" src="<?php echo $this->webroot?>images/delete.png"> <?php echo __('deleteall')?></a></li>
        <li><a class="link_btn"rel="popup" href="#" onclick="ex_deleteSelected('producttab','<?php echo $this->webroot ?>codedecks/del_code_deck/selected','code deck');"><img width="16" height="16" alt="" src="<?php echo $this->webroot?>images/delete.png"> <?php echo __('deleteselected')?></a></li>
        <?php endif ?>
        <?php }?>
    </ul>
</div>
<div id="container">
    <?php if (count($d) == 0) {?>
    <div class="msg"  id="msg_div"><?php echo __('no_data_found')?></div>
    <?php } else {
    ?>
    <div class="msg"  id="msg_div"  style="display: none;"><?php echo __('no_data_found')?></div>
    <?php }?>
    <?php if (count($d) == 0) {?>
    <div  id="list_div"  style="display: none;">
        <?php } else {?>
        <div id="list_div">
            <?php }?>
            <div id="toppage"></div>
            <div id="addcodedeck" style="display:none;background:buttonface;position:absolute;left:40%;top:30%;z-idnex:99;width:300px;height:130px;border:2px solid lightgray;">
                <div style="background:lightblue;width:100%;height:25px;font-size: 16px;"><?php echo __('newcodedeck')?></div>
                <div style="margin-top:10px;margin-left:10px;">
                    <p><?php echo __('codedeckname')?>:<input class="input in-text" id="cname"/></p>
                </div>
                <div style="margin-top:10px; margin-left:25%;width:150px;height:auto;">
                    <input type="button" onclick="add('cname','<?php echo $this->webroot?>codedecks/add_codedeck');" value="<?php echo __('submit')?>" class="input in-button">
                    <input type="button" onclick="closeCover('addcodedeck');" value="<?php echo __('cancel')?>" class="input in-button">
                </div>
            </div>
            <div id="editcodedeck" style="display:none;background:buttonface;position:absolute;left:40%;top:30%;z-idnex:99;width:300px;height:130px;border:2px solid lightgray;">
                <div style="background:lightblue;width:100%;height:25px;font-size: 16px;"><?php echo __('editcodedeck')?></div>
                <div style="margin-top:10px;margin-left:10px;">
                    <p><?php echo __('codedeckname')?>:<input class="input in-text" id="cname_e"/></p>
                </div>
                <div style="margin-top:10px; margin-left:25%;width:150px;height:auto;">
                    <input type="button" onclick="add(['cname_e','codedeckid'],'<?php echo $this->webroot?>codedecks/edit_codedeck');" value="<?php echo __('submit')?>" class="input in-button">
                    <input type="button" onclick="closeCover('editcodedeck');" value="<?php echo __('cancel')?>" class="input in-button">
                </div>
            </div>
            <input type="hidden" value="" id="codedeckid"/>
            <table class="list">

                <thead>
                    <tr>
                        <?php  if ($_SESSION['role_menu']['Switch']['codedecks']['model_w']) {?><td><input type="checkbox" onclick="checkAllOrNot(this,'producttab');" value=""/></td>
                        <?php }?>
                        <!--		<td>
                                           <?php echo $appCommon->show_order('code_deck_id',__('ID',true)) ?>
                                         </td>-->
                        <td>
                            <?php echo $appCommon->show_order('name',__('codedeckname',true))?>
                        </td>
                        <td>
                            <?php echo $appCommon->show_order('codes',__('ofcodes',true))?>
                        </td>
                        <td>
                            <?php echo $appCommon->show_order('usage',__('usage',true))?>
                        </td>
                        <td><?php echo __('Update At',true);?></td>
                        <td><?php echo __('Update By',true);?></td>
                        <?php  if ($_SESSION['role_menu']['Switch']['codedecks']['model_w']) {?><td class="last"><?php echo __('action')?></td>
                        <?php }?>
                    </tr>
                </thead>
                <tbody id="producttab">
                    <?php 
                    $mydata =$p->getDataArray();
                    $loop = count($mydata); 
                    for ($i=0;$i<$loop;$i++) {?>
                    <tr class="row-1">
                        <?php  if ($_SESSION['role_menu']['Switch']['codedecks']['model_w']) {?><td style="text-align:center"><input type="checkbox" value="<?php echo $mydata[$i][0]['code_deck_id']?>"/></td>
                        <?php }?>
                        <!--				<td class="in-decimal" style="text-align:center"><?php echo $mydata[$i][0]['code_deck_id']?></td>-->
                        <td style="font-weight: bold;"><a style="width:80%;display:block" href="<?php echo $this->webroot?>codedecks/codes_list/<?php echo $mydata[$i][0]['code_deck_id']?>" class="link_width"><?php echo $mydata[$i][0]['name']?></a></td>


                        <td>

                            <?php echo $mydata[$i][0]['codes']?>

                        </td>
                        <td>
                            <a style="width:80%;display:block" href="<?php echo $this->webroot?>rates/code_rates_list/<?php echo $mydata[$i][0]['code_deck_id']?>/<?php echo $mydata[$i][0]['name']?>" class="link_width" >
                                <?php echo $mydata[$i][0]['usage']?></a>
                        </td>
                        <td>

                            <?php echo $mydata[$i][0]['update_at']?>

                        </td>
                        <td>

                            <?php echo $mydata[$i][0]['update_by']?>

                        </td>
                        <?php  if ($_SESSION['role_menu']['Switch']['codedecks']['model_w']) {?>
                        <td class="last">
                            <?php if ($w == true) {?><a title="<?php echo __('edit')?>" id="<?php echo $mydata[$i][0]['code_deck_id']?>" style="" class="edit" href="<?php echo $this->webroot?>codedecks/edit_codedeck" >

                                <img src="<?php echo $this->webroot?>images/editicon.gif" />
                            </a>
                            <a title="<?php echo __('del')?>" style="margin-left:20px;" href="javascript:void(0)" onclick="ex_delConfirm(this,'<?php echo $this->webroot?>codedecks/del_code_deck/<?php echo $mydata[$i][0]['code_deck_id']?>','code deck  <?php echo $mydata[$i][0]['name']?>');">
                                <img src="<?php echo $this->webroot?>images/delete.png" />
                            </a><?php }?>
                        </td>
                        <?php }?>
                    </tr>
                    <?php }?>		
                </tbody>
                <tbody>
                </tbody>
            </table>
            <?php #增加国家**************************************** ?>
            <div id="country" style="display:none; z-index: 1002; outline: 0px none; position: absolute; height: auto; width: 350px; top: 319px; left: 461px;" class="ui-dialog ui-widget ui-widget-content ui-corner-all  ui-draggable ui-resizable" tabindex="-1" role="dialog" aria-labelledby="ui-dialog-title-dialog-form"><div class="ui-dialog-titlebar ui-widget-header ui-corner-all ui-helper-clearfix" unselectable="on" style="-moz-user-select: none;"><span class="ui-dialog-title" id="ui-dialog-title-dialog-form" unselectable="on" style="-moz-user-select: none;">&nbsp;</span><a href="#" id="close" class="ui-dialog-titlebar-close ui-corner-all" role="button" unselectable="on" style="-moz-user-select: none;"><span class="ui-icon ui-icon-closethick" unselectable="on" style="-moz-user-select: none;">close</span></a></div><div id="dialog-form" class="ui-dialog-content ui-widget-content" style="width: auto; min-height: 0px; height: 121px;">
                    <form action="<?php echo $this->webroot?>codedecks/add_code_country" method="get" id="countryForm">
                        <fieldset>
                            <label for="country"><?php echo __('Add Country',true);?></label><br>
                            <input type="text" class="text ui-widget-content ui-corner-all input in-input in-text" value="" id="addcoun" name="country">
                        </fieldset>
                        <fieldset style="text-align: center;">
                            <button id="create-country" type="submit" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" role="button" aria-disabled="false"><span class="ui-button-text"><?php echo __('submit',true);?></span></button> <button id="create-cancel" type="button" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" role="button" aria-disabled="false"><span class="ui-button-text"><?php echo __('cancel',true);?></span></button>
                        </fieldset>
                    </form>
                    <p class="validateTips"></p>
                </div>
                <div class="ui-resizable-handle ui-resizable-n" unselectable="on" style="-moz-user-select: none;"></div>
                <div class="ui-resizable-handle ui-resizable-e" unselectable="on" style="-moz-user-select: none;"></div><div class="ui-resizable-handle ui-resizable-s" unselectable="on" style="-moz-user-select: none;"></div>
                <div class="ui-resizable-handle ui-resizable-w" unselectable="on" style="-moz-user-select: none;"></div><div class="ui-resizable-handle ui-resizable-se ui-icon ui-icon-gripsmall-diagonal-se ui-icon-grip-diagonal-se" style="z-index: 1001; -moz-user-select: none;" unselectable="on"></div><div class="ui-resizable-handle ui-resizable-sw" style="z-index: 1002; -moz-user-select: none;" unselectable="on"></div>
                <div class="ui-resizable-handle ui-resizable-ne" style="z-index: 1003; -moz-user-select: none;" unselectable="on"></div><div class="ui-resizable-handle ui-resizable-nw" style="z-index: 1004; -moz-user-select: none;" unselectable="on"></div>
            </div>
            <?php #＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊?>
            <div id="tmppage">
                <?php echo $this->element('page');?>
            </div>
        </div>
    </div>
    <!--<script type="text/javascript">
            function edit_code_deck(name,code_id){
                    document.getElementById("cname_e").value = name;
                    document.getElementById("codedeckid").value = code_id;
                    cover('editcodedeck');
            }
    </script>
    --><!-- 添加不成功时 显示原来输入的名称 -->
    <!--<?php
            $n = $session->read('backform');
            $e_id = $session->read('code_deck_id_e');
            
            if (!empty($n)) {
                    $div = $session->read('add_or_edit_codedeck');
                    $session->del('add_or_edit_codedeck');
                    $session->del('backform');
                    $name = "cname";
                    $reseller = "reseller";
    ?>
                                    <script type="text/javascript">
                                                            cover("<?php echo $div?>");
                                                            <?php 
                                                                    if ($div == 'editcodedeck') {
                                                                            $name .= "_e";
                                                                            $reseller .= "_e";
                                                                    }
                                                            ?>
                                                            document.getElementById("<?php echo $name?>").value="<?php echo $n[0]?>";
                                                            document.getElementById("<?php echo $reseller?>").value="<?php echo $n[1]?>";
                                                            <?php if (!empty($e_id)) {?>
                                                                            document.getElementById("codedeckid").value = "<?php echo $e_id?>";
                                                            <?php }?>
                                    </script>
    <?php }?>
    --><script type="text/javascript">
        <!--
        jQuery(document).ready(function(){
            jQuery('#cname,#cname_e').xkeyvalidate({type:'strNum'});

        } );
        //-->
    </script>

    <script type="text/javascript">
        jQuery(document).ready(function(){
            jQuery('#add').click(
            function(){
                jQuery('#list_div').show();
                jQuery('#msg_div').remove();
                var action=jQuery(this).attr('href');
                jQuery('table.list').trAdd({
                    action:action,
                    ajax:'<?php echo $this->webroot?>codedecks/js_save',
                    onsubmit:function(options){return jsAdd.onsubmit(options);}
                });
                return false;
            }
        );


            //修改方法```
            jQuery('.edit').click(
            function(){
                var id=jQuery(this).attr('id');
                var action=jQuery(this).attr('href')+"/"+id;
                jQuery(this).parent().parent().trAdd(
                {
                    ajax:"<?php echo $this->webroot?>codedecks/js_save/"+id,
                    action:action,
                    saveType:'edit',
                    onsubmit:function(options){return jsAdd.onsubmit(options);}
                }
            );
                return false;
            }
        );
        });
    </script>

    <script type="text/javascript">
        var jsAdd={
            onsubmit:function(options){
                var re=true;
                var tr=jQuery('#'+options.log);
                var name=tr.find('#CodedeckName').val();
	
                if(name==''){
                    jQuery.jGrowlError('The field name cannot be NULL.');
                    re=false;
                }
		
                if(/[^0-9a-zA-Z\-_.\s]/.test(name)){
                    jQuery.jGrowlError('Name,allowed characters:a-z,A-Z,0-9,-,_,space,maximum of 16 characters in length! ');
                    re=false;
                }
                return re;
            }
        }
    </script>

    <script type="text/javascript">
        <!--
        jQuery('#addcounty').click(function(){
            jQuery('#country').attr("style","display: block;blockheight:auto;left:460px;outline:0 none;	possition :absolute;	top:100px;	width:350px;x-index:1002;");
        });
        jQuery('#close,#create-cancel').click(function(){
            jQuery('#country').attr("style","display:none");
			
        });
        jQuery('#countryForm').submit(function() {
            jQuery('#country').attr("style","display:none");
            // 提交表单
            jQuery(this).ajaxSubmit();
            // 为了防止普通浏览器进行表单提交和产生页面导航（防止页面刷新？）返回false
        });
        //-->
    </script>



