<script src="<?php echo $this->webroot?>js/BubbleSort.js" type="text/javascript"></script>
<?php $d = $p->getDataArray(); ?>
<div id="cover"></div> 
<div id="title">
    <h1>
        <?php __('Routing')?>&gt;&gt;
        <?php echo __('product')?>   


        <?php 
        $codeDecks =array();
        if(!empty($code_decks)){
        foreach($code_decks as $code_deck){
        $codeDecks[$code_deck[0]['code_deck_id']] = $code_deck[0]['name'];
        }
        }

        ?>
    </h1>
    <ul id="title-search">
        <li>
            <form>
                <input type="text" id="search-_q" class="in-search default-value input in-text defaultText" title="<?php echo __('namesearch')?>" value="<?php if (!empty($search)) echo $search;?>" name="search">
            </form>
        </li>
    </ul>
    <?php $w = $session->read('writable')?>
    <ul id="title-menu">
        <?php  if ($_SESSION['role_menu']['Routing']['products']['model_w']) {?>
        <?php if ($w == true) {?>
        <li>
            <a class="link_btn" rel="popup"  href="javascript:void(0)" onclick="cover('addproduct')">
                <img width="16" height="16" alt="" src="<?php echo $this->webroot?>images/add.png"><?php echo __('createnew')?>
            </a>
        </li>
        <?php }?>
        <?php if(Configure::read('project_name')!='partition'){?>
        <li><a class="link_btn" id="swap" href="javascript:void(0)" onclicks="getAllProducts()"><img width="16" height="16" alt="" src="<?php echo $this->webroot?>images/icon9.gif"> <?php echo __('swap')?></a></li>
        <?php }?>

        <?php if (count($d) > 0): ?>
        <?php  if ($_SESSION['role_menu']['Routing']['products']['model_w']) {?> <li><a class="link_btn" rel="popup" href="javascript:void(0)" onclick="deleteAll('<?php echo $this->webroot?>/products/del_all_pro');" ><img width="16" height="16" alt="" src="<?php echo $this->webroot?>images/delete.png"> <?php echo __('deleteall')?></a></li>
        <li><a class="link_btn" rel="popup" href="javascript:void(0)" onclick="ex_deleteSelected('producttab','<?php echo $this->webroot?>/products/del_selected_pro','static route table');"><img width="16" height="16" alt="" src="<?php echo $this->webroot?>images/delete.png"> <?php echo __('deleteselected')?></a></li>
        <?php }?>
        <?php endif; ?>
        
        <?php }?>
    </ul>
</div>
<div id="container">

    <div id="toppage"></div>
    <div id="swapproduct" style="display:none;background:buttonface;position:absolute;left:40%;top:30%;z-idnex:99;width:400px;height:140px;border:2px solid lightgray;">
        <div style="background:lightblue;width:100%;height:25px;font-size: 16px;"><?php echo __('swaping')?>&nbsp;<span style="color:red;float:right;" id="loading"></span></div>&nbsp;
        <div style="margin-top:10px;margin-left:10px;">
            <?php echo __('tobeswapped')?>:&nbsp;&nbsp;&nbsp;&nbsp;
            <select style="width:150px;" class="in-select" id="productA">
            </select>
        </div>
        <div style="margin-top:10px;margin-left:10px;">
            <?php echo __('selectapro')?>:&nbsp;&nbsp;&nbsp;&nbsp;
            <select style="width:150px;" class="in-select" id="productB">
            </select>
        </div>
        <div style="margin-top:10px; margin-left:30%;width:150px;height:auto;">
            <input type="button" onclick="swapproducts();" value="<?php echo __('submit')?>" class="input in-submit">
            <input type="button" onclick="closeCover('swapproduct');" value="<?php echo __('cancel')?>" class="input in-submit">
        </div>
    </div>
    <?php if (count($d) == 0) {?>
    <div class="msg"><?php echo __('no_data_found')?></div>
    <div>
        <table class="list" style="display:none">

            <thead>
                <tr>
                    <td><input type="checkbox" onclick="checkAllOrNot(this,'producttab');" value=""/></td>
                    <!--		<td><?php echo $appCommon->show_order('product_id',' ID ')?></td>-->
                    <td><?php echo $appCommon->show_order('name',__('produname',true)) ?></td>
                    <td>Code Type</td>
                    <td>Code Deck</td>
                    <td>Route LRN</td>
                    <td><?php echo $appCommon->show_order('modify_time',__('updateat',true))?></td>
                    <td><?php echo $appCommon->show_order('routes',__('ofroutes',true))?></td>
                    <td><?php echo $appCommon->show_order('ingress',__('routecount',true))?></td>
                    <td><?php echo __('Update By',true);?></td>
                    <?php  if ($_SESSION['role_menu']['Routing']['products']['model_w']) {?><td class="last"><?php echo __('action')?></td><?php }?>
                </tr>
            </thead>
        </table>
    </div>
    <?php } else {?>

    <div>
        <table class="list" id="content">
            <thead>
                <tr>
                    <td><input type="checkbox" onclick="checkAllOrNot(this,'producttab');" value=""/></td>
                    <!--		<td><?php echo $appCommon->show_order('product_id',__('ID',true))?></td>-->
                    <td><?php echo $appCommon->show_order('name',__('produname',true)) ?></td>
                    <td>Code Type</td>
                    <td>Code Deck</td>
                    <td>Route LRN</td>
                    <td><?php echo $appCommon->show_order('modify_time',__('updateat',true))?></td>
                    <td><?php echo $appCommon->show_order('routes',__('ofroutes',true))?></td>
                    <td><?php echo $appCommon->show_order('ingress',__('routecount',true))?></td>
                    <td><?php echo __('Update By',true);?></td>
                    <?php  if ($_SESSION['role_menu']['Routing']['products']['model_w']) {?><td class="last"><?php echo __('action')?></td><?php }?>
                </tr>
            </thead>
            <tbody id="producttab">
                <?php 
                $mydata =$p->getDataArray();
                $loop = count($mydata); 
                for ($i=0;$i<$loop;$i++) {?>
                <tr id="line<?php echo $i+1;?>" onclick="lineclick(this);">

                    <td style="text-align:center"><input type="checkbox" value="<?php echo $mydata[$i][0]['id']?>"/></td>
                    <!--		    		<td class="in-decimal"  style="text-align: center;"><?php echo $mydata[$i][0]['id']?></td>-->
                    <td style="font-weight: bold;">
                        <a style="width:80%;display:block" href="<?php echo $this->webroot?>products/route_info/<?php echo $mydata[$i][0]['id']?>" class="link_width" >
                            <?php echo substr($mydata[$i][0]['name'],0,40)?>
                        </a>
                    </td>
                    <td><?php
                        if($mydata[$i][0]['code_type'] == 0){
                        echo "By Code";
                        }else{
                        echo "By Code Name";
                        }
                        ?>   
                    </td>
                    <td>
                        <?php
                        if(!empty($mydata[$i][0]['code_deck_id']) && !empty($codeDecks[$mydata[$i][0]['code_deck_id']])){
                        echo $codeDecks[$mydata[$i][0]['code_deck_id']];
                        }
                        ?>
                    </td>
                    <td>
                        <?php echo $mydata[$i][0]['route_lrn'] == 0 ? 'DNIS' : 'LRN'; ?>
                    </td>
                    <td><?php echo $mydata[$i][0]['m_time']?></td>
                    <td><a style="width:80%;display:block" href="<?php echo $this->webroot?>products/route_info/<?php echo $mydata[$i][0]['id']?>" class="link_width" ><?php echo $mydata[$i][0]['routes']?></a></td>

                    <td align="center"><a style="width:80%;display:block" href="<?php echo $this->webroot?>routestrategys/strategy_list?filter_static=<?php echo $mydata[$i][0]['id']?>" class="link_width"><?php echo $mydata[$i][0]['ingress']?></a></td>
                    <td><?php echo $mydata[$i][0]['update_by']?></td>
                    <?php  if ($_SESSION['role_menu']['Routing']['products']['model_w']) {?> <td >
                        <a id="edit"  title="<?php echo __('edit')?>" role_id="<?php echo $mydata[$i][0]['id'] ?>"  style="margin-left:10px;" href="javascript:void(0)" bonclick="modifyName('<?php echo $this->webroot?>',this,'products','<?php echo __('pro_update_success')?>',3);">
                            <img src="<?php echo $this->webroot?>images/editicon.gif" />
                        </a>
                        <a title="<?php echo __('del')?>" style="margin-left:10px;" href="javascript:void(0)" onclick="ex_delConfirm(this,'<?php echo $this->webroot?>products/delbyid/<?php echo $mydata[$i][0]['id']?>','static route table <?php echo $mydata[$i][0]['name'] ?>');">
                            <img src="<?php echo $this->webroot?>images/delete.png" />
                        </a>
                        <a title="<?php echo __('Copy')?>" style="margin-left:10px;" href="javascript:void(0)" onclick="jQuery('#copyratetmp').show().find('#tmpid').val('<?php echo $mydata[$i][0]['id']?>')">
                            <img src="<?php echo $this->webroot?>images/copy.png" width="16" height="16" />
                        </a>
                    </td>
                    <?php }?>
                </tr>
                <?php }?>
            </tbody>
        </table>
    </div>
    <div id="tmppage">
        <?php echo $this->element('page');?>
    </div>
    <?php }?>
</div>
<dl id="copyratetmp" class="tooltip-styled" style="position: absolute; left: 40%; top: 30%; width: 300px; height: 100px; z-index: 99;display:none">
    <dd style="text-align: center; width: 100%; height: 25px; font-size: 16px;">Copy Static Route
        <span class="float_right"><a href="javascript:closeDiv('copyratetmp')" id="pop-close" class="pop-close">&nbsp;</a></span>
    </dd>
    <dd style="margin-top: 10px; margin-left: 10%;">
        <?php echo __('name',true);?>:&nbsp;&nbsp;<input class="input in-text in-input" id="pname"/>
    </dd>
    <dd style="padding:5px">
        <input class="input in-input in-text" style="display:none;" id="tmpid"/>
    </dd>
    <dd style="margin-left: 20%; width: 200px; height: auto;padding:5px">
        <input type="button" onclick="copy_route();" value="<?php echo __('submit',true);?>" class="input in-button in-submit"/>
        <input type="button" onclick="jQuery('#copyratetmp').hide()" value="<?php echo __('cancel',true);?>" class="input in-button in-submit"/>
    </dd>
</dl>
<script type="text/javascript">
    function copy_route(){
        var id=jQuery('#copyratetmp').find('#tmpid').val();
        var name=jQuery('#copyratetmp').find('#pname').val();
        if(name==""){
            jQuery.jGrowlError('Name is required');
            return false;
        }else{
            if(!/^(\w|\-|\_)*$/.test(name)){
                jQuery.jGrowlError('Name, allowed characters: a-z,A-Z,0-9,-,_,space, maximum of 100 characters!');
                return false;
            }
        }
        var data=jQuery.ajaxData('<?php echo $this->webroot?>/products/check_name?name='+name);
        if(!data.indexOf('false')){
            jQuery.jGrowlError('name is repeat');
            return;
		
        }
        location="<?php echo $this->webroot?>products/copy?id="+id+"&name="+name;
    }


    //该方法覆盖默认方法
    function order(){
        var div=jQuery('<div/>')
        var input=jQuery("<input type='checkbox'>");
        var label=jQuery('<label/>').append('id');
        var select=jQuery('<select style="width:60px"/>').append("<option value='asc'>asc</option><option value='desc'>desc</option>");
        div.append(input).append('&nbsp;&nbsp;&nbsp;&nbsp;').append(select);
        jQuery.float({innerHtml:div});
    }
    function cover(){
        if(jQuery('tr[add=add]').size()>0){
            return;
        }
        if(jQuery('.msg').size()>0){
            jQuery('table.list').show();
        }
        jQuery('div.msg').hide();
        var tr=jQuery('<tr/>').attr('add','add');
        for(i=0;i<11;i++){
            var td=jQuery('<td/>');
            if(i==1){
                td.append("<input maxlength=\"100\"  id=\"pname\" class=\"input in-text\"/>");
            }
            if(i==2){
                td.append("<select onchange= 'changeCodeType(this);' class='select in-select' id='code_type'><option value=0>By Code</option><option value=1>By Code Name</option></select>");
            }
            if(i==3){
                var code_deck = document.getElementById('add_code_deck');
                td.append(code_deck.innerHTML);
            }
            if(i==4){
                td.append("<select class='select in-select' id='route_lrn'><option value=0>DNIS</option><option value=1>LRN</option></select>");
            }
            if(i==9){
                td.append("<a title=\"Save\" href=\"# \" id=\"save\" onclick=\"add(new Array('pname','code_type','code_deck_id','route_lrn'),'<?php echo $this->webroot?>/products/add_product');\">"+
                    "<img src=\"<?php echo $this->webroot?>images/menuIcon_004.gif\" height=\"16\" width=\"16\">"+
                    "</a>"+
                    "<a title=\"delete\"  href=\"# \"style=\"margin-left:20px\" id=\"delete\" onclick=\"closeCover(this)\"><img src=\"<?php echo $this->webroot?>images/delete.png\" height=\"16\" width=\"16\"></a>"
            );
            }
            tr.append(td);
        }
        jQuery('table.list').append(tr);
    }
    function closeCover(a){
        if(jQuery('.msg').size()>0){
            jQuery('table.list').hide();
        }
        jQuery(a).parent().parent().remove();
    }
    jQuery(document).ready(
    function(){
        jQuery('#pname').xkeyvalidate({type:'strName'});
			
        var options=Array();
        var webroot='<?php echo $this->webroot?>';
        jQuery.ajax(
        {
            url:webroot+"products/getallproducts",
            async:false,
            success:function(data){
                var dataarr=eval(data);
                for(i in dataarr)
                {
                    var obj=dataarr[i];
                    obj.value=obj.id;
                    obj.key=obj.name;
                    options.push(obj);
                }
            }
        });
        jQuery('#swap').xshowadd(
        {
							
            width:'400px',
            height:'150px',
            title:'<div style="ing-left:20px;text-align:left;background:#9DC0E0;font-weight:bolder;height:22px"><span>Swap<span></div>',
            posts:[
                {'label':'The Product to be swaped:&nbsp;&nbsp;','id':'productA','name':'productA','fin':'select','options':options},
                {'label':'&nbsp;&nbsp;Select a product to swap:&nbsp;&nbsp;','id':'productB','name':'productB','fin':'select','options':options}
            ],
            callBack:function(data){
                jQuery('#container').html(data);
                jQuery.xcloseadd();
            }
        }
    );
    }

	
);

</script>

<!-- 添加不成功时 显示原来输入的名称 -->
<?php
$n = $session->read('product_name');
if (!empty($n)) {
$session->del('product_name');
?>
<script>
    cover('addproduct');
    document.getElementById("pname").value="<?php echo $n?>";
</script>
<?php }?>
<dl id="addproduct" class="tooltip-styled" style="display:none;position:absolute;left:40%;top:30%;z-idnex:99;width:200px;height:100px;">
    <dd style="text-align:center;width:100%;height:25px;font-size: 16px;"><?php echo __('addproduct')?></dd>
    <dd style="margin-top:10px;"><?php echo __('produname')?>:<input class="input in-text" id="pname"maxLength="100" /></dd>
    <dd style="margin-top:10px; margin-left:10%;width:150px;height:auto;">
        <input type="button" onclick="add('pname','<?php echo $this->webroot?>products/add_product');"style=" width: 60px;" value="<?php echo __('submit')?>" class="input in-submit" />
        <input type="button" onclick="closeCover('addproduct');" value="<?php echo __('cancel')?>" class="input in-submit" style=" width: 60px;" />
    </dd>
</dl>
<div id="add_code_deck" style="display: none;">
    <?php echo $xform->input("code_deck_id",Array("name"=>"code_deck_id","options"=>$codeDecks,"style"=>"display:none;width:150px"))?>
</div>
<script type="text/javascript" >
    jQuery('a[id=edit]').click(function(){
        var  role_id = jQuery(this).attr('role_id');
        jQuery(this).parent().parent().trAdd({
            action:"<?php echo $this->webroot?>products/modifyname",	 
            ajax:"<?php echo $this->webroot ?>products/data_edit/"+role_id,
            saveType:"edit"
        });		
    });

</script>


<script>
    function changeCodeType(obj){
        $code_deck = $(obj).parent().next().find('select');
        if($(obj).val() == 0){
            $code_deck.hide();
            $code_deck.append('<option value="0"></option>');
            $code_deck.attr('value',"0");
        }
       
        if($(obj).val() == 1){
            $code_deck.show();
            //$code_deck.attr
            $code_deck.find('option[value="0"]').remove();
        }
    }
</script>


