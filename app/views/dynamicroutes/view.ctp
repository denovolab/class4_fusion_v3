<script src="<?php echo $this->webroot?>js/ajaxTable.js" type="text/javascript"></script>
<?php $d = $p->getDataArray(); ?>
<div id="title">
    <h1>
        <?php __('Routing')  ;  ?>
        &gt;&gt;<?php echo __('DynamicRouting')?></h1>
    <?php $w = $session->read('writable');?>
    <ul id="title-search">
        <li>
            <?php //****************************模糊搜索**************************?>
            <form  action="<?php echo $this->webroot?>dynamicroutes/view"  method="get">
                <input type="text" id="search-_q" class="in-search default-value input in-text defaultText" title="<?php echo __('search')?>" 
                       value="<?php if(isset($_POST['search'])){echo $_POST['search'];}else{ echo '';}?>"  onclick="this.value=''" name="search">
            </form>
        </li>
        <li title="<?php echo __('advancedsearch')?> " onClick="advSearchToggle();" id="title-search-adv" style="display: list-item;"></li>
    </ul>
    <ul id="title-menu">
        <?php if (isset($edit_return)) {?>
        <li> <a class="link_back" href="<?php echo $this->webroot?>dynamicroutes/view"> <img width="16" height="16"  alt="" src="<?php echo $this->webroot?>images/icon_back_white.png">&nbsp;<?php echo __('gobackall')?> </a> </li>
        <?php }?>
        <?php  if ($_SESSION['role_menu']['Routing']['dynamicroutes']['model_w']) {?>
        <li> <?php echo $this->element("createnew",Array('url'=>'dynamicroutes/add'))?> </li>
            <?php if (count($d) > 0): ?>
        <li>
            <a href="###" class="link_btn" id="delete_selected" rel="popup">
                <img width="16" height="16" src="<?php echo $this->webroot ?>images/delete.png" alt="">Delete Selected
            </a>
        </li>
            <?php endif; ?>
        <?php }?>
    </ul>
</div>
<div id="container">
    <?php //*********************  条件********************************?>
    <fieldset class="title-block" id="advsearch">
        <form>
            <table  style="width: 580px;">
                <tbody>
                    <tr>
                        <td style="display:none; "><label> <?php echo __('route_name')?>:</label>
                            <input   type="text"   name="name"   /></td>
                        <td><label><?php echo __('routingrule')?>:</label>
                            <?php 
                            $arr1=array('4'=>__('routerule1',true),'5'=>__('routerule2',true),'6'=>__('routerule3',true));
                            echo $form->input('routing_rule',
                            array('options'=>$arr1,'name'=>'routing_rule','empty'=>'','label'=>false ,
                            'div'=>false,'type'=>'select','value'=>$routing_rule));
                            ?>
                            <script type="text/javascript">
                                jQuery(document).ready(function(){jQuery('#routing_rule').val('<?php echo $routing_rule?>')});
                            </script></td>
                        <td class="buttons"><input type="submit" value="Search" class="input in-submit"></td>
                    </tr>
                </tbody>
            </table>
        </form>
    </fieldset>
    <?php if (count($d) == 0) {?>
    <div class="msg"><?php echo __('no_data_found')?></div>
    <table class="list" style="display:none">
        <thead>
            <tr>
                    <td><input type="checkbox" id="selectall" /></td>
                    <td ><?php echo __('findegress')?></td>
                    <!--	    			<td> <?php echo $appCommon->show_order('dynamic_route_id',__('ID',true))?></td>-->
                    <td><?php echo $appCommon->show_order('name',__('Name'),true)?></td>
                    <td><?php echo $appCommon->show_order('routing_rule',__('Routing Rule',true))?></td>
                    <td><?php echo $appCommon->show_order('time_profile_id',__('Time Profile',true))?></td>
                    <td><?php echo $appCommon->show_order('use_count',__('Usage Count',true))?></td>
                    <td><?php echo $appCommon->show_order('lcr_flag',__('QoS Cycle',true))?></td>
                    <td><?php echo __('Update At',true);?></td>
                    <td><?php echo __('Update By',true);?></td>
                    <?php  if ($_SESSION['role_menu']['Routing']['dynamicroutes']['model_w']) {?>
                    <td class="last"  style="text-align: center;"><?php __('action')?></td>
                    <?php }?>
                </tr>
        </thead>
    </table>
    <?php } else {?>
    <div id="toppage"></div>
    <div style="height:10px"></div>
    <?php //*********************表格�?************************************?>
    <div>
        <table class="list">
            <thead>
                <tr>
                    <td><input type="checkbox" id="selectall" /></td>
                    <td ><?php echo __('findegress')?></td>
                    <!--	    			<td> <?php echo $appCommon->show_order('dynamic_route_id',__('ID',true))?></td>-->
                    <td><?php echo $appCommon->show_order('name',__('Name'),true)?></td>
                    <td><?php echo $appCommon->show_order('routing_rule',__('Routing Rule',true))?></td>
                    <td><?php echo $appCommon->show_order('time_profile_id',__('Time Profile',true))?></td>
                    <td><?php echo $appCommon->show_order('use_count',__('Usage Count',true))?></td>
                    <td><?php echo $appCommon->show_order('lcr_flag',__('QoS Cycle',true))?></td>
                    <td><?php echo __('Update At',true);?></td>
                    <td><?php echo __('Update By',true);?></td>
                    <?php  if ($_SESSION['role_menu']['Routing']['dynamicroutes']['model_w']) {?>
                    <td class="last"  style="text-align: center;"><?php __('action')?></td>
                    <?php }?>
                </tr>
            </thead>
            <?php //*********************表格�?************************************?>
            <?php //*********************循环输出的动态部�?************************************?>
            <?php 	$mydata =$p->getDataArray();	$loop = count($mydata); for ($i=0;$i<$loop;$i++) {?>
            <tbody id="resInfo<?php echo $i?>">
                <tr class="row-<?php echo $i%2 +1;?>">
                    <td><input control="<?php echo $mydata[$i][0]['dynamic_route_id']?>" type="checkbox" /></td>
                    <td  align="center"  style="font-weight: bold;"><img   id="image<?php echo $i; ?>"  onclick="pull('<?php echo $this->webroot?>',this,<?php echo $i;?>)"    class=" jsp_resourceNew_style_1"  src="<?php echo $this->webroot?>images/+.gif" title="<?php  __('findegress')?>"/></td >
                    <!--		    		<td  align="center">
                                                          <?php echo $mydata[$i][0]['dynamic_route_id']?>	
                                          </td>-->
                    <td  align="center"  style="font-weight: bold;"><?php  if ($_SESSION['role_menu']['Routing']['dynamicroutes']['model_w']) {?>
                        <a id='<?php echo $mydata[$i][0]['dynamic_route_id']?>'  href="<?php echo $this->webroot?>dynamicroutes/edit/<?php echo $mydata[$i][0]['dynamic_route_id']?>"  title="edit"> <?php echo $mydata[$i][0]['name']?>
                           <?php }else{echo $mydata[$i][0]['name'];}?>
                    </a></td >
                <td align="center">
                    <?php if($mydata[$i][0]['routing_rule']==4){ echo __('routerule1');?>
                    <?php }if($mydata[$i][0]['routing_rule']==5){ echo  __('routerule2'); ?>
                    <?php }if($mydata[$i][0]['routing_rule']==6){ echo  __('routerule3'); ?>
                    <?php }?></td>
                <td><?php echo $mydata[$i][0]['time_profile_id']?>
                    <?php if(empty($mydata[$i][0]['time_profile_id'])){echo '';}?></td>
                <td><a  href="<?php echo  $this->webroot?>routestrategys/dynamic_strategy_list/<?php echo $mydata[$i][0]['dynamic_route_id']?>"  target="blank"> <?php echo$mydata[$i][0]['use_count']?></a></td>
                <td><?php echo isset($mydata[$i][0]['lcr_flag']) ? $mydata[$i][0]['lcr_flag'] : ''; ?></td>
                <td><?php echo $mydata[$i][0]['update_at']; ?></td>
                <td><?php echo $mydata[$i][0]['update_by']; ?></td>
                <?php  if ($_SESSION['role_menu']['Routing']['dynamicroutes']['model_w']) {?>
                <td style="text-align: center;">
                    <a title="<?php __('QoS parameters') ?>" href="<?php echo $this->webroot ?>dynamicroutes/qos/<?php echo $mydata[$i][0]['dynamic_route_id']?>">
                        <img src="<?php echo $this->webroot ?>images/dynamic_qos.png" />
                    </a>
                    <a title="<?php __('Trunk Priority') ?>" href="<?php echo $this->webroot ?>dynamicroutes/priority/<?php echo $mydata[$i][0]['dynamic_route_id']?>">
                        <img src="<?php echo $this->webroot ?>images/resource_pri.png" />
                    </a>
                    <a title="<?php __('Override') ?>" href="<?php echo $this->webroot ?>dynamicroutes/override/<?php echo $mydata[$i][0]['dynamic_route_id']?>">
                        <img src="<?php echo $this->webroot ?>images/dynamic_override.png" />
                    </a>
                    <a id='<?php echo $mydata[$i][0]['dynamic_route_id']?>'  href="<?php echo $this->webroot?>dynamicroutes/edit/<?php echo $mydata[$i][0]['dynamic_route_id']?>"  title="edit"> 
                       <img  title="<?php echo __('edit')?>"   src="<?php echo $this->webroot?>images/editicon.gif" > 
                    </a> 
                    <a onClick="return confirm(' Are you sure to delete,dynamic routing <?php echo $mydata[$i][0]['name']?> ? ');" href="<?php echo $this->webroot?>dynamicroutes/del/<?php echo $mydata[$i][0]['dynamic_route_id']?>/<?php echo $mydata[$i][0]['name']?>" title="<?php echo __('delete')?>"> 
                        <img  title="<?php __('delete')?>" src="<?php echo $this->webroot?>images/delete.png" > 
                    </a>
                </td>
                <?php }?>
            </tr>
            <tr style="height:auto">
                <td colspan=10>
                    
                    <div id="ipInfo<?php echo $i?>" class=" jsp_resourceNew_style_2" style="padding:5px"> 
                        <table>
                        <tr>
                          <td><?php echo __('id',true);?></td>
                          <td><?php echo __('Carriers',true);?></td>
                          <td><?php echo __('Egress Trunk Name',true);?></td>
                          <?php  if ($_SESSION['role_menu']['Routing']['dynamicroutes']['model_w']) {?>
                          <td><?php echo __('active',true);?></td>
                          <?php }?>
                        </tr>
                        <?php if(!empty($mydata[$i][0]['slist'])){?>
                        <?php foreach($mydata[$i][0]['slist'] as $list){?>
                        <tr>
                          <td><?php echo $list[0]['resource_id']?></td>
                          <td><?php echo $list[0]['name']?></td>
                          <td><?php echo $list[0]['alias']?></td>
                          <?php  if ($_SESSION['role_menu']['Routing']['dynamicroutes']['model_w']) {?>
                          <td><a style="<?php if($list[0]['active']!=1){ echo 'display:none';}?> "
                                                  onclick="return active(this,'<?php echo $this->webroot?>gatewaygroups/dis_able/<?php echo $list[0]['resource_id']?>/view_egress')"
                                                      href='#' title="<?php echo __('disable')?>"> <img  title="<?php echo __('wangtodisable')?>" src="<?php echo $this->webroot?>images/flag-1.png"> </a> <a style="<?php if($list[0]['active']==1){ echo 'display:none';} ?> "   
                                                               onclick="return disable(this,'<?php echo $this->webroot?>gatewaygroups/active/<?php echo $list[0]['resource_id']?>/view_egress')" 
                                                               href='#' title="<?php echo __('disable')?>"> <img  title="<?php echo __('wangtoactive')?>" src="<?php echo $this->webroot?>images/flag-0.png"> </a></td>
                          <?php }?>
                        </tr>
                        <?php }?>
                        <?php }?>
                      </table>
                    </div>
                
                </td>
            </tr>
        </tbody>
        <?php }?>
    </table>
</div>
<?php //*****************************************循环输出的动态部�?************************************?>
<div style="height:10px"></div>
<div id="tmppage"> <?php echo $this->element('page');?> </div>
<?php echo $this->element('dynamicroutes/massedit'); ?> </div>

<?php }?>
</div>

<script type="text/javascript">
    jQuery(document).ready(function(){
        jQuery('#add').click(function(){
            jQuery('table.list').show();
            jQuery('div.msg').hide();
            jQuery('table.list').trAdd({
                ajax:'<?php echo $this->webroot?>dynamicroutes/js_save',
                action:'<?php echo $this->webroot?>dynamicroutes/add',
                tag:'.add',
                id:'0',
                callback:function(options){
                    jQuery('#'+options.log).find('img[id^=image]').click();
                    jQuery('select[id^=Carriers]').each(
                    function(){
                        var temp=jQuery(this).parent().parent().find('select[id^=resource_id]').val();
                        jQuery(this).change();
                        jQuery(this).parent().parent().find('select[id^=resource_id]').val(temp);
                    }
                );
                },
                onsubmit:trAdd.onsubmit,
                removeCallback:function(){
                    if(jQuery('table.list tbody').size()==0){
                        jQuery('table.list').hide();
                    }
                }
            });
            return false;
        });
        jQuery('a[title=edit]').each(function(){
            jQuery(this).click(function(){
                id=jQuery(this).attr('id');
                jQuery(this).parent().parent().parent().trAdd({
                    ajax:'<?php echo $this->webroot?>dynamicroutes/js_save/'+id,
                    action:'<?php echo $this->webroot?>dynamicroutes/edit/'+id,
                    tag:'.add',
                    id:id,
                    saveType:'edit',
                    callback:function(options){
                        jQuery('#'+options.log).find('img[id^=image]').click();
                        jQuery('select[id^=Carriers]').each(
                        function(){
                            //var temp=jQuery(this).parent().parent().find('select[id^=resource_id]').val();
                            //jQuery(this).change();
                            //jQuery(this).parent().parent().find('select[id^=resource_id]').val(temp);
                        }
                    );
                    },
                    onsubmit:trAdd.onsubmit
                });	
                return false;
            });
        });
        
        $('#delete_selected').click(function() {
            var result = confirm("Are you sure do this?");
            if (!result)
                return;
            
            var ids = new Array();
            
            $('table.list tbody input:checkbox:checked').each(function() {
                ids.push(parseInt($(this).attr('control')));
            });
            
            $.ajax({
                'url' : '<?php echo $this->webroot; ?>dynamicroutes/delete_selected',
                'type' : 'POST',
                'dataType' : 'text',
                'data' : {'ids[]' : ids},
                'success' : function(data) {
                    jQuery.jGrowl("Your options are deleted succesfully",{theme:'jmsg-success'});
                    window.setTimeout("window.location.reload()", 3000);
                }
            });
            
        });
    
    
        $('#selectall').change(function() {
            $('table.list tbody input:checkbox').attr('checked', $(this).attr('checked'));
        });
    });
</script> 
<script type="text/javascript">
    var trAdd={
        onsubmit:function(options){
            var xform=jQuery('#'+options.log);
            var re=true;
            if(xform.find('#DynamicrouteName').val()==''){
                jQuery.jGrowlError('The field Name cannot be NULL.');
                xform.find('#DynamicrouteName').addClass('invalid');
                re=false;
            }else{
                if(!/^(\w|\-|\_)*$/.test(xform.find('#DynamicrouteName').val())){
                    jQuery.jGrowlError('Name, allowed characters: a-z,A-Z,0-9,-,_,space, maximum of 100 characters!');
                    return false;
                }
            }
            if(xform.find('#egressSelect').val()==''){
                jQuery.jGrowlError('Egress can not be null!');
                xform.find('#egressSelect').addClass('invalid');
                re=false;
            }
            var arr=Array();
            if(xform.find('select[id=egressSelect]').size()==0){
                jQuery.jGrowlError("<?php __('routenameexist')?>");
                re=false;
            }
            xform.find('select[id=egressSelect]').each(
            function(){
                for(var i in arr){
                    if(arr[i]==jQuery(this).val()){
                        jQuery.jGrowlError('egress is repeat');
                        re=false;
                        return;
                    }
                }
                arr.push(jQuery(this).val());
            }
        );
            var name=xform.find('#DynamicrouteName').val();
            var data=jQuery.ajaxData("<?php echo $this->webroot?>dynamicroutes/checkName/"+options.id+"?name="+name);
            if(data=='false'){
                jQuery.jGrowlError(name+' is already in use! ');
                re=false;
            }
            return re;
        }
    }
    function callBack(options){
        var div=options.div;
        div.html('');
        div.append('<div style="height:35px;font-weight:bold;font-size:20px;text-align:right"><input type="button" value="Order">&nbsp;&nbsp;<a href="#" onclick="return false;"><img onclick="jQuery(this).parent().parent().parent().remove();return false;" src="<?php echo $this->webroot?>images/delete.png" title="Close"/></a></div>');
        div.append('<table>');
        div.find('table').append('<tr style="height:25px"><td><img class="up" src="<?php echo $this->webroot?>images/list-sort-asc.png"/><img class="down" src="<?php echo $this->webroot?>images/list-sort-desc.png"/></td><td><input type="checkbox" name="id" value="dynamic_route_id"/>&nbsp;&nbsp;</td><td style="text-align:left"><span style="font-size:16px;font-weight:bold">Id</span>&nbsp;&nbsp;</td><td>&nbsp;&nbsp;<select name="id_type"><option value="desc">Desc</option><option value="asc">Asc</option></select></td></tr>');
        div.find('table').append('<tr style="height:25px"><td><img class="up" src="<?php echo $this->webroot?>images/list-sort-asc.png"/><img class="down" src="<?php echo $this->webroot?>images/list-sort-desc.png"/></td><td><input type="checkbox" name="name" value="name"/>&nbsp;&nbsp;</td><td style="text-align:left"><span style="font-size:16px;font-weight:bold">Name</span>&nbsp;&nbsp;</td><td>&nbsp;&nbsp;<select name="name_type"><option value="desc">Desc</option><option value="asc">Asc</option></select></td></div>');
        div.find('table').append('<tr style="height:25px"><td><img class="up" src="<?php echo $this->webroot?>images/list-sort-asc.png"/><img class="down" src="<?php echo $this->webroot?>images/list-sort-desc.png"/></td><td><input type="checkbox" name="routing_rule" value="routing_rule"/>&nbsp;&nbsp;</td><td style="text-align:left"><span style="font-size:16px;font-weight:bold">Routing rule</span>&nbsp;&nbsp;</td><td>&nbsp;&nbsp;<select  name="routing_rule_type"><option value="desc">Desc</option><option value="asc">Asc</option></select></td></tr>');
        div.find('table').append('<tr style="height:25px"><td><img class="up" src="<?php echo $this->webroot?>images/list-sort-asc.png"/><img class="down" src="<?php echo $this->webroot?>images/list-sort-desc.png"/></td><td><input type="checkbox" name="time_profile" value="time_profile"/>&nbsp;&nbsp;</td><td style="text-align:left"><span style="font-size:16px;font-weight:bold">Time Profile</span>&nbsp;&nbsp;</td><td>&nbsp;&nbsp;<select  name="time_profile_type"><option value="desc">Desc</option><option value="asc">Asc</option></select></td></tr>');
        div.find('table').append('<tr style="height:25px"><td><img class="up" src="<?php echo $this->webroot?>images/list-sort-asc.png"/><img class="down" src="<?php echo $this->webroot?>images/list-sort-desc.png"/></td><td><input type="checkbox" name="usage_count" value="usage_count"/>&nbsp;&nbsp;</td><td style="text-align:left"><span style="font-size:16px;font-weight:bold">Usage Count</span>&nbsp;&nbsp;</td><td>&nbsp;&nbsp;<select name="usage_count_type"><option value="desc">Desc</option><option value="asc">Asc</option></select></td></tr>');
        div.find('table img.up').click(function(){
            jQuery(this).parent().parent().prev().before(jQuery(this).parent().parent());
            div.find('table tr img').show();
            div.find('table tr:nth-child(1) img.up').hide();
            div.find('table tr:last img.down').hide();
        });
        div.find('table img.down').click(function(){
            jQuery(this).parent().parent().next().after(jQuery(this).parent().parent());
            div.find('table tr img').show();
            div.find('table tr:nth-child(1) img.up').hide();
            div.find('table tr:last img.down').hide();
        });
        div.find('table tr:nth-child(1) img.up').hide();
        div.find('table tr:last img.down').hide();
        jQuery(div).find('input[type=button]').click(function(){
            temp=Array();
            div.find('table tr').each(
            function(){
                if(jQuery(this).find('input').attr('checked')){
                    temp.push(jQuery(this).find('input').val()+'-'+jQuery(this).find('select').val());
                }
            }
        );
            location="?order_by="+temp.join(';')+"&search=<?php echo array_keys_value($this->params,'url.search')?>";
        });
    }

</script> 

<script type="text/javascript">


jQuery(function() {
    
    $('.client_options').live('change', function() {
        var $this = $(this);
        value=$this.val();
        var data=jQuery.ajaxData({'async' : false,'url':'<?php echo $this->webroot?>/trunks/ajax_options?filter_id='+value+'&type=egress&trunk_type2=0'});
        data=eval(data);
        var temp1=$this.parent().parent().find('select').eq(1).val();

        $this.parent().parent().find('select').eq(1).html('');
        jQuery('<option>').appendTo($this.parent().parent().find('select').eq(1));
        for(var i in data){
            var temp=data[i];
            jQuery('<option>').html(temp.alias).val(temp.resource_id).appendTo($this.parent().parent().find('select').eq(1));
        }
        $this.parent().parent().find('select').eq(1).val(temp1);
    });
    
    $('#additem').live('click', function() {
        $('#cloned').clone(true).appendTo('#tblwa').find('option[value=""]').attr('selected', true).end().find('option[value=0]').attr('selected', true);
    });
    
    
    $('#add_all').live('click', function() {
        var $tblwa = $('#tblwa');
        $.ajax({
            'url' : '<?php echo $this->webroot; ?>dynamicroutes/get_all_egress',
            'type' : 'POST',
            'dataType' : 'json',
            'success' : function(data)
            {
                var rows = new Array();
                $.each(data, function(key, value) {
                    var newel = $('#cloned').clone(true);
                    $('.client_options option[value="'  + value[0]['client_id'] + '"]', newel).attr('selected', true);
                    $('.client_options', newel).change();
                    $('#egressSelect option[value="'  + value[0]['resource_id'] + '"]', newel).attr('selected', true);
                    $tblwa.append(newel);
                });
            }
        });
    });
});
</script>
