<script type="text/javascript">
    function add(){
        if(jQuery('.add').html()!=null){
            return;
        }
        jQuery('table.list').show();
        jQuery('div.msg').hide();
        jQuery('table.list tbody').append(
        jQuery('<tr/>').append(
        jQuery('<td/>').html('<input style="display: none;">')
    ).append(
        jQuery('<td/>').append(jQuery('<input class="marginTop9 width90 input in-text" maxLength="256">').xkeyvalidate({type:'strName'}))
    ).append(
        jQuery('<td/>').html('<input style="display: none;">')
    ).append(jQuery('<td />')).append(jQuery('<td />')).append(
        jQuery('<td/>').html('<a onclick="save_code(this.parentNode.parentNode);" href="#" style="" class="marginTop9"><img src="<?php echo $this->webroot?>images/menuIcon_004.gif"></a><a onclick="jQuery(&quot;#rec_strategy&quot;).removeAttr(&quot;add&quot;);this.parentNode.parentNode.parentNode.removeChild(this.parentNode.parentNode)" href="javascript:void(0)" style=" margin-left: 10px;" class="marginTop9"><img src="<?php echo $this->webroot?>images/delete.png"></a>')
    ).addClass('add')
    );
        jQuery('table.list tr:nth-child(2n+1)').addClass('row-1').removeClass('row-2');
        jQuery('table.list tr:nth-child(2n)').addClass('row-2').removeClass('row-1');
        return; 
    }
    function save_code(tr){
        var params = {
            name :tr.cells[1].getElementsByTagName('input')[0].value
        };
        if(/[^0-9A-Za-z-\_\s]+/.test(params['name'])){
            jQuery(tr.cells[1].getElementsByTagName('input')[0]).addClass('invalid');
            jQuery.jGrowl('Name,allowed characters: a-z,A-Z,0-9,-,_,space, maximum of 16 characters in length!',{theme:'jmsg-error'});
            return false;
        }
        jQuery.post('<?php echo $this->webroot?>routestrategys/add',params,function(data){
            var  tmp = data.split("|");
            var p = {theme:'jmsg-success',beforeClose:function(){location.href='<?php echo $this->webroot?>routestrategys/routes_list/' + tmp[2].trim();},life:100};            
            if (tmp[1].trim() == 'false') {
                p = {theme:'jmsg-alert',life:500};
            }
            jQuery.jGrowl(tmp[0],p);
        });
    }
    function edit(currRow){
        var columns = [{},{className:' width90 input in-text  check_strNum'},{},{},{},{}];
        editRow(currRow,columns);
        var btn = currRow.cells[5].getElementsByTagName("a")[0];
        if (btn){
            var cancel = currRow.cells[5].getElementsByTagName("a")[1].cloneNode(true);
            cancel.title = "<?php __('cancel')?>";
            cancel.getElementsByTagName("img")[0].src="<?php echo $this->webroot?>images/rerating_queue.png";
            cancel.onclick = function(){location.reload();}
            currRow.cells[5].appendChild(cancel);
            btn.getElementsByTagName("img")[0].src="<?php echo $this->webroot?>images/menuIcon_004.gif";
            jQuery(btn).attr('style','margin-left:31px');
            btn.onclick = function(){
                var location = "";
                var params = {
                    name :currRow.cells[1].getElementsByTagName('input')[0].value,
                    id :currRow.cells[0].getElementsByTagName('input')[0].value};
                jQuery.post('<?php echo $this->webroot?>/routestrategys/update',params,function(data){
                    var p = {theme:'jmsg-success',beforeClose:function(){
			    	
                            var str=location.toString();
                            if(str.indexOf("?")!='-1'){
							
                                location=location;
							
                            }else{
                                location=location.toString()+"?edit_id="+params.id;
                            }
										    
                        },life:100};
                    var  tmp = data.split("|");
                    if (tmp[1].trim() == 'false') p = {theme:'jmsg-alert',life:500};
                    jQuery.jGrowl(tmp[0],p);
                    window.location.reload();
                });
                //window.location = location;
            }	
        }
        jQuery('input.check_strNum').xkeyvalidate({type:'strNum'}).attr('maxLength','256');
    }
</script>
<div id="cover"></div>
<?php
    $mydata =$p->getDataArray();
    $loop = count($mydata); 
?>
<?php $w = $session->read('writable')?>
<div id="title">

    <?php  echo $this->element('h1_title',array('param1'=>'Routing','param2'=>'Routing Strategies')) ?>

    <ul id="title-search">
        <li>
            <form>
                <input type="text" id="search-_q" class="in-search default-value input in-text defaultText" title="<?php echo __('namesearch')?>" value="<?php if (!empty($search)) echo $search;?>" name="search">
            </form>
        </li>
        <!--  <li title="advanced search »" onclick="advSearchToggle();" id="title-search-adv" style="display: list-item;"></li>-->
    </ul>
    <ul id="title-menu">
        <?php if (isset($edit_return)) {?>
        <li> <a class="link_back" href="<?php echo $this->webroot?>routestrategys/strategy_list"> <img width="16" height="16"  alt="" src="<?php echo $this->webroot?>images/icon_back_white.png"> &nbsp;<?php echo __('gobackall')?> </a> </li>
        <?php }?>
        <?php  if ($_SESSION['role_menu']['Routing']['routestrategys']['model_w']) {?>

        <li><a class="link_btn" id="add" href="#" onclick="add();"><img width="16" height="16" alt="" src="<?php echo $this->webroot?>images/add.png"> <?php echo __('createnew')?></a></li>
        <?php if($loop > 0): ?>
        <li><a rel="popup"class="link_btn" href="#" onClick="deleteAll('<?php echo $this->webroot?>/routestrategys/del_strategy/all/<?php echo isset($_GET['filter_static']) ? $_GET['filter_static'] : ''; ?>');" ><img width="16" height="16" alt="" src="<?php echo $this->webroot?>images/delete.png"> <?php echo __('deleteall')?></a></li>
        <li> <a rel="popup"class="link_btn" href="#" onClick="deleteSelected('rec_strategy','<?php echo $this->webroot?>/routestrategys/del_strategy/selected','routing plan');"><img width="16" height="16" alt="" src="<?php echo $this->webroot?>images/delete.png"> <?php echo __('deleteselected')?></a></li>
        <?php endif; ?>
        <?php }?>
    </ul>
</div>
<div id="container">
    <?php
    if($loop > 0):
    ?>
    <div id="toppage"></div>

    <table class="list">

        <thead>
            <tr>
                <td><input type="checkbox" onclick="checkAllOrNot(this,'rec_strategy');" value=""/></td>
                <!--<td><?php echo $appCommon->show_order('route_strategy_id',__('ID',true))?></td>-->
                <td><?php echo $appCommon->show_order('name',__('Name',true))?></td>
                <td><?php echo $appCommon->show_order('routes',__('Usage Count',true))?></td>
                <td><?php echo __('Update At',true);?></td>
                <td><?php echo __('Update By',true);?></td>
                <?php  if ($_SESSION['role_menu']['Routing']['routestrategys']['model_w']) {?> <td class="last"><?php echo __('action')?></td><?php }?>
            </tr>
        </thead>
        <tbody id="rec_strategy">
            <?php 
            for ($i=0;$i<$loop;$i++) {?>
            <tr class="row-1">
                <td style="text-align:center"><input type="checkbox" value="<?php echo $mydata[$i][0]['route_strategy_id']?>"/></td>
                <!--<td><?php echo $mydata[$i][0]['route_strategy_id']?></td>-->
                <td style="font-weight: bold;"><a style="width:80%;display:block" href="<?php echo $this->webroot?>routestrategys/routes_list/<?php echo $mydata[$i][0]['route_strategy_id']?>" class="link_width"><?php echo $mydata[$i][0]['name']?></a></td>
                <td><a href="<?php echo $this->webroot ?>prresource/gatewaygroups/view_ingress?resource_prefix=<?php echo $mydata[$i][0]['route_strategy_id']?>"><?php echo $mydata[$i][0]['routes']?></a></td>
                <td><?php echo $mydata[$i][0]['update_at']; ?></td>
                <td><?php echo $mydata[$i][0]['update_by']; ?></td>
                <?php  if ($_SESSION['role_menu']['Routing']['routestrategys']['model_w']) {?><td align="center">
                    <a title="<?php echo __('edit')?>" href="javascript:void(0)" onclick="edit(this.parentNode.parentNode)"> <img src="<?php echo $this->webroot?>images/editicon.gif" /> </a> <a title="<?php echo __('del')?>"  href="javascript:void(0)" style="margin-left: 10px;" onclick="del(this,'<?php echo $this->webroot?>routestrategys/del_strategy/<?php echo $mydata[$i][0]['route_strategy_id']?>','<?php echo $mydata[$i][0]['name']?>');"> <img src="<?php echo $this->webroot?>images/delete.png" /> </a>
                </td>
                <?php }?>
            </tr>
            <?php }?>
        </tbody>
    </table>
    <div id="tmppage"> <?php echo $this->element('page');?> </div>
    <?php else: ?>
    <table class="list" style="display:none;">
        <thead>
            <tr>
                <td><input type="checkbox" onclick="checkAllOrNot(this,'rec_strategy');" value=""/></td>
                <!--<td><?php echo $appCommon->show_order('route_strategy_id',__('ID',true))?></td>-->
                <td><?php echo $appCommon->show_order('name',__('Name',true))?></td>
                <td><?php echo $appCommon->show_order('routes',__('Usage Count',true))?></td>
                <td><?php echo __('Update At',true);?></td>
                <td><?php echo __('Update By',true);?></td>
                <?php  if ($_SESSION['role_menu']['Routing']['routestrategys']['model_w']) {?> <td class="last"><?php echo __('action')?></td><?php }?>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
    <div class="msg"><?php echo __('no_data_found')?></div>
    <?php endif; ?>
</div>
<script type="text/javascript">
    function del(obj,url,d_name){ 
        if(confirm("Are you sure to delete,routing plan  "+ d_name))
            location=url;
    }
</script>