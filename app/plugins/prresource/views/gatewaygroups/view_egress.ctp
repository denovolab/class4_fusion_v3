<script src="<?php echo $this->webroot?>js/ajaxTable.js" type="text/javascript"></script>
<?php $mydata =$p->getDataArray();	$loop = count($mydata); ?>
<?php $w = $session->read('writable');?>
<div id="title">
    <h1>
        <?php __('Routing') ?>&gt;&gt;<?php echo __('egress') ?>
        <!--<font class="editname"  title="Name"><?php echo empty($name) || $name == '' ? '' : '[' . $name . ']'; ?></font>   -->
    </h1>
    <ul id="title-search">
        <li>
            <form  action=""  method="get">
                <input type="text" id="search-_q" class="in-search default-value input in-text defaultText" title="<?php echo __('search') ?>..." 
                       value="<?php if (isset($_POST['search'])) {
                       echo $_POST['search'];
                       } else {
                       echo '';
                       } ?>"  onclick="this.value=''" name="search">
            </form>
        </li>
        <!--  <li title="<?php echo __('advancedsearch') ?> »" onclick="advSearchToggle();" id="title-search-adv" style="display: list-item;"></li>-->
    </ul>
    <ul id="title-menu">


        <?php if(isset($_GET['viewtype'])&&$_GET['viewtype']=='client'){?>
        <li>
            <a  class="link_back" href="<?php echo $this->webroot?>clients/index" class="link_back"><img width="16" height="16" alt="<?php echo __('goback',true);?>" src="<?php echo $this->webroot?>images/icon_back_white.png">&nbsp;<?php echo __('goback',true);?></a>		</li>
        <?php }?>  
        <?php  if ($_SESSION['role_menu']['Routing']['gatewaygroups:view']['model_w']) {?>
        <?php if ($w == true) {?>
        <li>
            <a class="link_btn" id="add" href="<?php echo $this->webroot?>prresource/gatewaygroups/add_resouce_egress<?php  if(isset($_GET ['query'] ['id_clients'])) {echo "?query[id_clients]={$_GET ['query'] ['id_clients']}&viewtype=client";}?>">
               <img width="16" height="16" alt="" src="<?php echo $this->webroot?>images/add.png">Create new
            </a>
        </li>
        <?php }?>

        <?php if ($loop > 0) : ?>

        <li>
            <a rel="popup" class="link_btn" href="javascript:void(0)" onclick="deleteSelected('mytable','<?php echo $this->webroot ?>prresource/gatewaygroups/del_selected?type=view_egress','egress trunk');">
                <img width="16" height="16" alt="" src="<?php echo $this->webroot?>images/delete.png"> <?php echo __('deleteselected')?>
            </a>
        </li>
        <?php endif; ?>
        <?php }?>
    </ul>
</div>
<div id="container">
    <div id="cover"></div>
    <dl id="edit_ip" class="tooltip-styled" style="display:none;position:absolute;left:40%;top:30%;z-idnex:99;width:300px;height:auto;">
        <dd style="text-align:center;width:100%;height:25px;font-size: 16px;"><?php echo __('register')?></dd>
        <dd style="margin-top:10px;">
            <span>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo __('username')?></span>:<input id="ip_username" name="ip_username" style="height:20px;width:200px;float:right">
            <input id="ip_id" style="display:none"/>
        </dd>
        <dd style="margin-top:20px;">
            <span>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo __('password')?></span>:<input id="ip_pass" name="ip_pass" style="height:20px;width:200px;float:right">
        </dd>
        <dd style="margin-top:10px; margin-left:26%;width:150px;height:auto;">
            <input type="button" onclick="updateIp();" value="<?php echo __('submit')?>" class="input in-button">
            <input type="button" onclick="closeCover('edit_ip');" value="<?php echo __('cancel')?>" class="input in-button">
        </dd>
    </dl>
    <div style="border-left-width: 10px; border-right-width: 10px;border-color:#809DBA #809DBA #FFFFFF">
        <ul class="tabs">
            <?php if(isset($_GET['viewtype'])&&$_GET['viewtype']=='client'){?>
            <li><a href="<?php echo $this->webroot ?>clients/edit/<?php echo $_GET ['query'] ['id_clients']?>?<?php echo $this->params['getUrl']?>"><img alt="" src="<?php echo $this->webroot?>images/bDR.gif"/> <?php __('basicinfo')?></a></li>   
            <li class="active"><a href="<?php echo $this->webroot?>prresource/gatewaygroups/view_egress?<?php echo $this->params['getUrl']?>?>&viewtype=client"> <img alt="" src="<?php echo $this->webroot?>images/egress.png"/><?php __('egress')?></a></li> 
            <li><a href="<?php echo $this->webroot?>prresource/gatewaygroups/view_ingress?<?php echo $this->params['getUrl']?>?>&viewtype=client"> <img alt="" src="<?php echo $this->webroot?>images/ingress.png"/><?php __('ingress')?></a></li> 
            <?php }else{?>
            <li class="active"><a href="<?php echo $this->webroot?>prresource/gatewaygroups/view_egress?<?php echo $this->params['getUrl']?>"> <img alt="" src="<?php echo $this->webroot?>images/egress.png"/><?php __('egress')?></a></li> 
            <li  ><a href="<?php echo $this->webroot?>prresource/gatewaygroups/view_ingress"><img alt="" src="<?php echo $this->webroot?>images/ingress.png"/><?php __('ingress')?></a></li> 
            <?php }?>
        </ul>
        <ul class="tabs" style="border:0px;margin-left:20px">
            <li class="hover"><a onclick="second_tab_change('list',this)" href="#">Egress List</a></li> 
            <?php  if ($_SESSION['role_menu']['Routing']['gatewaygroups:view']['model_x']) {?>
            <li><a onclick="second_tab_change('egress_import',this)" href="#<?php echo $this->webroot ?>uploads/egress"><?php echo __('Egress Import',true);?></a></li>
            <li><a onclick="second_tab_change('egress_export',this)" href="#<?php echo $this->webroot?>downloads/egress"><?php echo __('Egress Export',true);?></a></li>
            <li><a onclick="second_tab_change('egress_import_host',this)" href="#<?php echo $this->webroot?>uploads/egress_host"> <?php echo __('Import Host',true);?></a></li> 
            <li><a onclick="second_tab_change('egress_export_host',this)" href="#<?php echo $this->webroot?>downloads/egress_host"> <?php echo __('Export Host',true);?></a></li>   
            <li><a onclick="second_tab_change('egress_import_action',this)" href="#<?php echo $this->webroot?>uploads/egress_action"> <?php echo __('Import Action',true);?></a></li> 
            <li><a onclick="second_tab_change('egress_export_action',this)" href="#<?php echo $this->webroot?>downloads/egress_action"> <?php echo __('Export Action',true);?></a></li>
            <?php }?>  
        </ul>
        <div id="list" class="second_tab" style="display:none">
            <?php echo $this->element("view_egress_list")?>
        </div>
        <div id="egress_import_host" class="second_tab" style="display:none">
            <?php echo $this->element("egress_import_host")?>
        </div>
        <div id="egress_export_host" class="second_tab" style="display:none">
            <?php echo $this->element("egress_export_host")?>
        </div>
        <div id="egress_import_action" class="second_tab" style="display:none">		
            <?php echo $this->element("egress_import_action")?>
        </div>
        <div id="egress_export_action" class="second_tab" style="display:none">		
            <?php echo $this->element("egress_export_action")?>
        </div>
        <div id="egress_import" class="second_tab" style="display:none">		
            <?php echo $this->element("egress_import")?>
        </div>
        <div id="egress_export" class="second_tab" style="display:none">		
            <?php echo $this->element("egress_export")?>
        </div>
    </div>
</div>
<script type="text/javascript">
    jQuery('#list').show();
    function second_tab_change(id,obj){
        jQuery('.second_tab').hide();
        jQuery('#'+id).show();
        jQuery(obj).parent().parent().find('li').removeClass('hover');
        jQuery(obj).parent().addClass('hover');
    }
</script>
<script type="text/javascript">
    tz = $('#query-tz').val();
    var _ss_ids_client = {'id_clients': 'query-id_clients', 'id_clients_name': 'query-id_clients_name', 'account': 'query-account', 'tz': 'query-tz', 'id_currencies': 'query-id_currencies'};
    function showClients ()
    {
        ss_ids_custom['client'] = _ss_ids_client;
        winOpen('<?php echo $this->webroot ?>clients/ss_client?types=2&type=0', 500, 530);
    }

    //]]>
    jQuery(document).ready(
    function(){                    
            <?php if(!empty($_GET['search'])):?>
            jQuery('td.last div').each(function(index){
            var url = jQuery('a:first-child',jQuery(this)).attr('href');
            jQuery('a:first-child',jQuery(this)).attr('href',url + '?jump=no&search=<?php echo $_GET['search'];?>');
        });                                           
            <?php endif; ?>                     
            jQuery('table tbody:nth-child(2n) tr').addClass('row-1').remove('row-2');
        jQuery('table tbody:nth-child(2n+1) tr').addClass('row-2').remove('row-1');
    }
);
</script>
