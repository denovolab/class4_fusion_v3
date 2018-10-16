<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <?php
        echo $html->charset ();
        ?>
        <title> <?php
            echo $title_for_layout;
            ?> :: <?php __( Configure::read ( 'project_name' ) )?></title>
        <script language="javascript" type="text/javascript">
            <!--
            var startDate;//开始加载时间
            var endDate ;//加载完成时间
            //求页面开加载的时间
            startDate=new Date();
            //**************************************
            //-->
        </script>

        <link href="<?php echo $this->webroot?>css/base_1.css" type="text/css" rel="stylesheet" media="all" />
        <link href="<?php echo $this->webroot?>css/main.css" media="all" rel="stylesheet" type="text/css" />
        <!--
        <link href="<?php echo $this->webroot?>css/base.css" type="text/css" rel="stylesheet" media="all" />
        -->
        <!--
        <link href="<?php echo $this->webroot?>css/allPage.css" type="text/css"	rel="stylesheet" media="all" />
        -->
        <!--<link href="<?php //echo $this->webroot?>css/shared.css" media="all" type="text/css" rel="stylesheet" />
        -->
        <!--
        <link href="<?php echo $this->webroot?>css/popup.css" media="all" rel="stylesheet" type="text/css" />
        -->
        <!--
        <link href="<?php echo $this->webroot?>css/styles.css" rel="stylesheet" type="text/css" />
        -->
        <!--
        <link href="<?php echo $this->webroot?>css/form.css" type="text/css" rel="stylesheet" media="all" />
        -->
        <!--
        <link href="<?php echo $this->webroot?>css/ipcentrex.css" type="text/css" rel="stylesheet" media="all" />
        -->
        <!--
        <link href="<?php echo $this->webroot?>css/list.css" media="all" rel="stylesheet" type="text/css" />
        -->
        <!--
        <link href="<?php echo $this->webroot?>css/select.css" rel="stylesheet" type="text/css" />
        -->
        <!--
        <link href="<?php echo $this->webroot?>calendar/calendar.css" type="text/css" rel="stylesheet" />
        -->
        <!--
        <link href="<?php echo $this->webroot?>themes/base/jquery.ui.all.css" rel="stylesheet" media="all" type="text/css" />
        -->
        <!--
        <link href="<?php echo $this->webroot?>css/jquery.css" rel="stylesheet"type="text/css" />
        -->
        <!--消息提示样式-->

        <link href="<?php echo $this->webroot?>css/jquery.jgrowl.css"	media="all" rel="stylesheet" type="text/css" />
        <!--弹出框-->
        <link  href="<?php echo $this->webroot?>themes/base/jquery.ui.all.css"  rel="stylesheet" media="all" type="text/css"/>
        <!--jquery界面样式-->
        <!--
        <link href="<?php echo $this->webroot?>css/jquery-ui.css" media="all" rel="stylesheet" type="text/css" />
        -->
        <!--
        <link href="<?php echo $this->webroot?>css/print.css" media="print" rel="stylesheet" type="text/css" />
        -->

        <!--
        <link href="<?php echo $this->webroot?>css/new_skin.css" type="text/css" rel="stylesheet" />
        -->

        <link rel="stylesheet" type="text/css" href="<?php echo $this->webroot?>easyui/themes/default/easyui.css">
            <link rel="stylesheet" type="text/css" href="<?php echo $this->webroot?>easyui/themes/icon.css">
                <script type="text/javascript" src="<?php echo $this->webroot?>easyui/jquery-1.7.2.min.js"></script>
                <script type="text/javascript" src="<?php echo $this->webroot?>easyui/jquery.easyui.min.js"></script>
                <script src="<?php echo $this->webroot?>js/jquery-ui-1.8.2.custom.min.js" type="text/javascript"></script>
                <script  src="<?php echo $this->webroot?>js/en.js" type="text/javascript"></script>
                <script src="<?php echo $this->webroot?>js/app.js?t=<?php time ()?>>" type="text/javascript"></script>
                <script src="<?php echo $this->webroot?>js/sst.js" type="text/javascript"></script>
                <script src="<?php echo $this->webroot?>js/jquery.jgrowl.js" type="text/javascript"></script>
                <script src="<?php echo $this->webroot?>js/jquery.tooltip.js" type="text/javascript"></script>

                <script src="<?php echo $this->webroot?>calendar/calendar.js" type="text/javascript"></script>
                <script src="<?php echo $this->webroot?>calendar/calendar-setup.js" type="text/javascript"></script>
                <script src="<?php echo $this->webroot?>calendar/calendar-en.js" type="text/javascript"></script>
                <script src="<?php echo $this->webroot?>js/util.js" type="text/javascript"></script>
                <script src="<?php echo $this->webroot?>js/select.js" type="text/javascript"></script>
                <script src="<?php echo $this->webroot?>js/swfobject.js" type="text/javascript"></script>
                <script src="<?php echo $this->webroot?>js/xtable.js" type="text/javascript"></script>
                <script src="<?php echo $this->webroot?>js/jquery.ui.dialog.js"></script>
                <script src="<?php echo $this->webroot?>js/My97DatePicker/WdatePicker.js" type="text/javascript"></script>
                <script src="<?php echo $this->webroot?>js/newExcanvas.js"></script>
                <script src="<?php echo $this->webroot?>js/bb-functions.js"></script>
                <script src="<?php echo $this->webroot?>js/bb-interface.js"></script>


                <!--localdata.js自动完成,搜索必须用到-->
                <script src="<?php echo $this->webroot?>js/jquery.autocomplete.js"></script>
                <script src="<?php echo $this->webroot?>js/localdata.js"></script>
                <script type="text/javascript">
                    jQuery(function($) {
                        $("#query-country").focus().autocomplete(countries);
                        $("#query-country_term").focus().autocomplete(countries);
                        $("#query-code_name").focus().autocomplete(cities);
                        $("#query-code_name_term").focus().autocomplete(cities);
                        $("#query-code").focus().autocomplete(codes);
                        $("#query-code_term").focus().autocomplete(codes);
                        $("#query-id_clients_name").focus().autocomplete(names);
                        $("#query-id_rates_name").focus().autocomplete(rates);
                        $("#query-id_rates_name_term").focus().autocomplete(rates_term);
                    });
                </script>
                <script>    
                    jQuery(document).ready(function($){
                        $(".link_back").click(function(event){
                            window.history.back();
                            event.preventDefault();
                        });
                    });
                </script>

                <!--png图片在IE6下不失真调用的js-->
                <script src="<?php echo $this->webroot?>js/DD_belatedPNG_0.0.8a-min.js"></script>
                <!--[if IE 6]>
                <script type="text/javascript">
                    DD_belatedPNG.fix('#logo,img,.sort_asc,.sort_sctive,.sort_dsc');
                </script>
                <![endif]-->




                <script type="text/javascript">
                    jQuery('#topmenu').find('a').map(function(){
                        jQuery(this).attr('style','width:95%');
                    });
                </script>
                <script type="text/javascript">
                    /*control the topmenu-menu hidden or display*/
                    jQuery(function($){
                        $('#topmenu-menu > li').each(function(){
                            if ($(this).children('ul').children('li').length == 0){
                                $(this).css('display','none');
                            }
                        });
                    });
                </script>

                <?php
                echo $html->meta ( 'icon' );
                echo $scripts_for_layout;
                ?>	

                <?php
                if (strpos ( $_SERVER ['QUERY_STRING'], 'protype' ) !== false) :
                ?>

                <script type="text/javascript">
                    jQuery(document).ready(function(){
                        jQuery('ul#topnav li,ul#topnav li ul,ul#topnav li ul li a').click(function(){jQuery('ul#topnav li,ul#topnav li ul,ul#topnav li ul li a').removeClass('hover');jQuery(this).addClass('hover');});
                    });
                </script>
                <style type="text/css">
                    a,a:link,a:visited,a:active,a:hover {
                        text-decoration: none;
                    }
                </style>
                <?php
                endif;
                ?>
                <script type="text/javascript">
                    var webroot='<?php echo $this->webroot?>';
                    var currentTime = '<?php echo time();?>';
                    var L = {"loadingPanel":"Please Wait...","deleteConfirm":"Are you sure you want to delete this item?","hide-all":"hide all"};
                    jQuery(document).ready(
                    function(){
                        jQuery('input[type=text],input[type=password]').addClass('input in-input in-text');
                        jQuery('input[type=button],input[type=submit]').addClass('input in-submit');
                        jQuery('select').addClass('select in-select');
                        jQuery('textarea').addClass('textarea in-textarea');
                    }
                );
                </script>
                <script type="text/javascript">
                    jQuery(document).ready(function(){
                        jQuery('#search-_q').click(
                        function(){
                            jQuery(this).removeClass('defaultText');
                        }
                    ).blur(
                        function(){
                            jQuery(this).addClass('defaultText');
                        }
                    );
                    });
                </script>		

                <?php if(isset($appCommon)):?>	
                <?php 
                echo $appCommon->auto_load_js()?>
                <?php endif;?>
                <?php
                echo $html->meta ( 'icon' );
                echo $scripts_for_layout;
                ?>	
                <style type="text/css">
div#title {background:none;background-color:#<?php echo $appCommon->get_bar_color() ?>;}
</style>
                </head>
                <body>
                    <div id="header">
                        <?php  

                        $login_type=$session->read('login_type');
                        if($login_type==1){ echo $this->element("uploads/show_upload_log");}?>
                        <div id="logo">
	<a href="#"> 
            
                <?php 
                    $logo_path = APP . 'webroot' .DS. 'upload'  . DS . 'images' . DS . 'logo.png';
        
                    if(file_exists($logo_path))
                    {
                        $logo = $this->webroot . 'upload/images/logo.png';
                    }
                    else
                    {
                        $logo = $this->webroot . 'images/logo.png';
                    }
                ?>
		<img src="<?php echo $logo ?>" alt=""/>
	</a>
</div>
                        <ul id="header-menu">
                            <?php  if($login_type==1){?>
                            <li id="upload_process"  rel="tooltip_ext"  style="height:16px; overflow:hidden;color: #333; font-weight:bold;"><img src="<?php echo $this->webroot?>images/102.gif" alt=""/>View Upload process</li>
                            <?php }?>

                            <li>
                                <?php
                                //echo $session->read('sst_client_id');
                                $project_name=Configure::read('project_name');
                                ?>		
                                <?php echo __('Role')?>:<span style="color: #333; font-weight:bold;">
                                    <?php $t= $session->read('login_type'); 
                                    if($t==1){
                                    echo  !empty($_SESSION['sst_role_name']) ? $_SESSION['sst_role_name'] : $_SESSION['role_menu']['role_name'];
                                    }

                                    if($t==3){__('clients');}

                                    ?></span></li>
                            <li><span style="color: #333; font-weight:bold;"><?php echo $session->read('sst_user_name')?></span></li>
                            <li><a href="<?php echo $this->webroot?>users/changepassword"><?php echo __('change_pass')?></a></li>
                            <li><a href="<?php echo $this->webroot?>homes/logout"><strong><?php echo __('logout')?></strong></a></li>
                        </ul>
                        <!--
                        <div id="header-status-tooltip" class="tooltip"></div>
                        <div class="header-alert" id="header-limit-day" rel="tooltip"></div>
                        -->
                    </div>


                    <?php //**********************************************公具条***************************************************?>
                    <div id="topmenu">
                        <?php echo $this->element("project_menu/admin_menu")?>
                        <?php echo $this->element("project_menu/exchange_menu")?>
                        <?php echo $this->element("project_menu/partition_menu")?>
                        <?php echo $this->element("project_menu/wholesales_menu")?>
                        <?php 	$login_type = $session->read('login_type');
                        if($login_type!=1&& $login_type!=3){ ?>		
                        <ul class="topmenu-left" id="topmenu-menu" style="float:right">		
                            <?php //***************************路由伙伴模块模块?>
                            <?php  $read=$session->read("sst_clent_read");  
                            if(!empty($read)){?>
                            <li><span><?php echo __('wholesale')?></span>
                                <ul style="display: none;">
                                    <?php
                                    $module=$session->read("sst_clent");
                                    $size=count($module);
                                    for($i=0;$i<$size;$i++){
                                    if(!empty($module[$i][0]['readable'])){
                                    ?>
                                    <li>
                                        <a href="<?php echo $this->webroot?><?php echo $module[$i][0]['func_url']?>">
                                            <?php echo __($module[$i][0]['key_118n'])?>
                                        </a>
                                    </li>
                                    <?php }}?>
                                </ul>
                            </li>
                            <?php }?>
                            <?php //***************************统计模块	?>
                            <?php  $read=$session->read("sst_summary_read");  
                            if(!empty($read)){?>
                            <li><span><?php echo __('statis')?></span>
                                <ul>
                                    <?php
                                    $module=$session->read("sst_summary");
                                    $size=count($module);
                                    for($i=0;$i<$size;$i++){
                                    if(!empty($module[$i][0]['readable'])){
                                    ?>
                                    <li>
                                        <a href="<?php echo $this->webroot?><?php echo $module[$i][0]['func_url']?>">
                                            <?php echo __($module[$i][0]['key_118n'])?>
                                        </a>
                                    </li>
                                    <?php }}?>
                                </ul>
                            </li>
                            <?php }?>
                            <?php //***************************工具模块?>
                            <?php  $read=$session->read("sst_tools_read");  
                            if(!empty($read)){?>
                            <li><span><?php echo __('tool')?></span>
                                <ul>
                                    <?php
                                    $module=$session->read("sst_tools");
                                    $size=count($module);
                                    for($i=0;$i<$size;$i++){
                                    if(!empty($module[$i][0]['readable'])){
                                    ?>
                                    <li>
                                        <a href="<?php echo $this->webroot?><?php echo $module[$i][0]['func_url']?>">
                                            <?php echo __($module[$i][0]['key_118n'])?>
                                        </a></li>
                                    <?php }}?>
                                </ul>
                            </li>
                            <?php }?>
                            <?php //**************************路由设置**************************?>
                            <?php  $read=$session->read("sst_routeconfig_read");  
                            if(!empty($read)){?>
                            <li><span><?php echo __('routeSet')?></span>
                                <ul>
                                    <?php $module=$session->read("sst_routeconfig");
                                    $size=count($module);
                                    for($i=0;$i<$size;$i++){
                                    if(!empty($module[$i][0]['readable'])){
                                    ?>
                                    <li>
                                        <a href="<?php echo $this->webroot?><?php echo $module[$i][0]['func_url']?>">
                                            <?php echo __($module[$i][0]['key_118n'])?>
                                        </a>
                                    </li>
                                    <?php }}?>
                                </ul>
                            </li>
                            <?php }?>
                            <?php //**************************系统配置模块**************************?>
                            <?php  $read=$session->read("sst_sysconfig_read");  
                            if(!empty($read)){?>
                            <li><span><?php echo __('systemc')?></span>
                                <ul style="display: none;">
                                    <?php
                                    $module=$session->read("sst_sysconfig");
                                    $size=count($module);
                                    for($i=0;$i<$size;$i++){
                                    if(!empty($module[$i][0]['readable'])){
                                    ?>
                                    <li><a
                                            href="<?php echo $this->webroot?><?php echo $module[$i][0]['func_url']?>">

                                            <?php echo __($module[$i][0]['key_118n'])?>
                                        </a></li>
                                    <?php }}?>
                                </ul>
                            </li>
                            <?php }?>
                            <?php //**************************系统管理模块**************************?>
                            <?php  $read=$session->read("sst_sysmanager_read");  
                            if(!empty($read)){?>
                            <li><span><?php echo __('system')?></span>
                                <ul style="display: none;">
                                    <?php
                                    $module=$session->read("sst_sysmanager");
                                    $size=count($module);
                                    for($i=0;$i<$size;$i++){
                                    if(!empty($module[$i][0]['readable'])){
                                    ?>
                                    <li><a
                                            href="<?php echo $this->webroot?><?php echo $module[$i][0]['func_url']?>">

                                            <?php echo __($module[$i][0]['key_118n'])?>
                                        </a></li>
                                    <?php }	}?>
                                </ul>
                            </li>
                            <?php }?>
                            <?php //**************************策略管理模块**************************?>
                            <?php  $read=$session->read("sst_stratemanager_read");  
                            if(!empty($read)){?>
                            <li><span><?php echo __('system')?></span>
                                <ul style="display: none;">
                                    <?php
                                    $module=$session->read("sst_stratemanager");
                                    $size=count($module);
                                    for($i=0;$i<$size;$i++){
                                    if(!empty($module[$i][0]['readable'])){
                                    ?>
                                    <li><a
                                            href="<?php echo $this->webroot?><?php echo $module[$i][0]['func_url']?>">

                                            <?php echo __($module[$i][0]['key_118n'])?>
                                        </a></li>
                                    <?php }	}?>
                                </ul>
                            </li>
                            <?php }?>
                            <?php //**************************充值管理模块**************************?>
                            <?php  $read=$session->read("sst_refillmanager_read");  
                            if(!empty($read)){?>
                            <li><span><?php echo __('refillm')?></span>
                                <ul style="display: none;">
                                    <?php
                                    $module=$session->read("sst_refillmanager");
                                    $size=count($module);
                                    for($i=0;$i<$size;$i++){
                                    if(!empty($module[$i][0]['readable'])){
                                    ?>
                                    <li><a
                                            href="<?php echo $this->webroot?><?php echo $module[$i][0]['func_url']?>">

                                            <?php echo __($module[$i][0]['key_118n'])?>
                                        </a></li>
                                    <?php }	}?>
                                </ul>
                            </li>
                            <?php }?>
                            <?php  } ?>

                            <div mb:format="%m/%d/%Y %H:%M:%S %z" mb:tz="<?php echo array_keys_value($_SESSION,'sys_timezone')?>" mb:stamp="<?php echo time();?>" id="topmenu-time" class="topmenu-right">
                                <?php 
                                //$time_zone = $_SESSION['sys_timezone'];
                                if(isset($_SESSION['sys_timezone']))
                                {
                                $time_zone = '+0';
                                if (preg_match('/\+0?\d{1,2}00/', $_SESSION['sys_timezone']))
                                {
                                $time_zone = preg_replace("/\+0?(\d{1,2})00/", "-\\1", $_SESSION['sys_timezone']);
                                }
                                if (preg_match('/\-0?\d{1,2}00/', $_SESSION['sys_timezone']))
                                {
                                $time_zone = preg_replace("/\-0?(\d{1,2})00/", "+\\1", $_SESSION['sys_timezone']);
                                }
                                date_default_timezone_set("Etc/GMT".$time_zone);
                                echo date("m/d/Y H:i:s");
                                echo ' ', $_SESSION['sys_timezone'];

                                }
                                ?>
                            </div>

                    </div>

                    <?php $session->flash ();?>


                    <div id="title">
                        <h1><?php echo __('Statistics'); ?> &gt;&gt; <?php echo __('Dashboard'); ?> &gt;&gt; <?php echo __('Report'); ?></h1>
                        <ul id="title-menu">
                            <a class="link_back" href="<?php echo $this->webroot; ?>homes/report">
                                <img width="16" height="16" src="<?php echo $this->webroot; ?>images/icon_back_white.png" alt="Back">
                                    &nbsp;Back</a>
                        </ul>
                    </div>

                    <div id="container" style="overflow:hidden;">
                        <ul class="tabs">
                            <li>
                                <a href="<?php echo $this->webroot ?>homes/dashbroad">
                                    <img width="16" height="16" src="<?php echo $this->webroot ?>images/stock.png">Dashboard</a>
                            </li>
                            <li class="active">
                                <a href="<?php echo $this->webroot ?>homes/report">
                                    <img width="16" height="16" src="<?php echo $this->webroot ?>images/report.png">Report
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo $this->webroot ?>homes/search_charts">
                                    <img width="16" height="16" src="<?php echo $this->webroot ?>images/showcharts.png">Charts
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo $this->webroot ?>homes/auto_delivery">
                                    <img width="16" height="16" src="<?php echo $this->webroot ?>images/delivery.png">Auto Delivery
                                </a>
                            </li>
                        </ul>
                        <h1 style="padding:0;margin:10px;width:100%;font-size:14px;text-align:left;">
                            <?php
                            echo $start_time . '&nbsp' . $end_time . '&nbsp' . $timezone . '(GMT)';
                            ?>
                        </h1>
                        <h2 id="showorhiden" style="padding:0;margin:10px;width:100%;font-size:12px;text-align:center;cursor:pointer;">
                            Show Short/Long Call
                        </h2>
                        <table id="test"></table>
                        <div class="msg" style="display:none;">No Result Is Available.</div>
                        <script>
                            $(function() {
                                var ishide = true;
                                $('#test').treegrid({
                                    title:'Results',
                                    iconCls:'icon-search',
                                    width:'100%',
                                    height:460,
                                    title:false,
                                    nowrap: false,
                                    rownumbers: true,
                                    animate:true,
                                    collapsible:true,
                                    url:'<?php echo $this->webroot ?>reportss/get_ingress_data?<?php echo $param;?>',
                                    idField:'id',
                                    treeField:'originator',
                                    frozenColumns:[[
                                            {title:'Originator',field:'originator',width:300,
                                                formatter:function(value){
                                                    return '<span style="color:red">'+value+'</span>';
                                                }
                                            }
                                        ]],
                                    columns:[[
                                            {field:'atts',title:'Atts',width:100},
                                            {field:'cc',title:'Cc',width:100,rowspan:2},
                                            {field:'mins',title:'Mins',width:100,rowspan:2},
                                            {field:'abr',title:'ABR',width:100,rowspan:2},
                                            {field:'asr',title:'ASR',width:100,rowspan:2},
                                            {field:'acd',title:'ACD',width:100,rowspan:2},
                                            {field:'pdd',title:'PDD',width:100,rowspan:2},
                                            {field:'cps',title:'CPS',width:100,rowspan:2},
                                            {field:'rev',title:'Rev',width:100,rowspan:2},
                                            {field:'cost',title:'Cost',width:100,rowspan:2},
                                            {field:'margin',title:'Margin',width:100,rowspan:2},
                                            {field:'call_6s',title:'Call(6s)',width:100,rowspan:2},
                                            {field:'call_12s',title:'Call(12s)',width:100,rowspan:2},
                                            {field:'call_18s',title:'Call(18s)',width:100,rowspan:2},
                                            {field:'call_24s',title:'Call(24s)',width:100,rowspan:2},
                                            {field:'call_30s',title:'Call(30s)',width:100,rowspan:2},
                                            {field:'call_2h',title:'Call(2h)',width:100,rowspan:2},
                                            {field:'call_3h',title:'Call(3h)',width:100,rowspan:2},
                                            {field:'call_4h',title:'Call(4h)',width:100,rowspan:2},
                                            {field:'max_channel_usage',title:'Max Channel Usage',width:130,rowspan:2},
                                            {field:'max_channel_allowed',title:'Max Channel Allowed',width:140,rowspan:2},
                                            {field:'percentage_of_trunk_usage',title:'Percentage Of Trunk Usage',width:160,rowspan:2}
                                        ]],
                                    onLoadSuccess:function(row,param){
                                        if(param[0].id == 0 && !param[0].children[0].children) 
                                        {
                                             $('.panel').hide();
                                             $('#showorhiden').hide();
                                             $('.msg').show();
                                        }
                                        $('#loading').hide();
                                        if(ishide) {
                                            $('td[field=call_6s]').hide();
                                            $('td[field=call_12s]').hide();
                                            $('td[field=call_18s]').hide();
                                            $('td[field=call_24s]').hide();
                                            $('td[field=call_30s]').hide();
                                            $('td[field=call_2h]').hide();
                                            $('td[field=call_3h]').hide();
                                            $('td[field=call_4h]').hide();
                                        }
                                    },
                                    onBeforeLoad:function(row,param){
                                        
                                        if (row){
                                            var url = '';
                                            if(row.type && row.type == 'country') {
                                                url = '<?php echo $this->webroot ?>reportss/get_ingress_data2?ingress_id='+row.ingress_id+'&country='+row.originator+'&<?php echo $param;?>';
                                            } else if(row.type && row.type == 'code_name') {
                                                url = '<?php echo $this->webroot ?>reportss/get_ingress_data3?ingress_id='+row.ingress_id+'&country='+row.country+'&code_name='+row.originator+'&<?php echo $param;?>';
                                            } else {
                                                url = '<?php echo $this->webroot ?>reportss/get_ingress_data1?ingress_id='+row.ingress_id+'&<?php echo $param;?>';
                                            }
                                            $(this).treegrid('options').url = url;
                                        } else {
                                            $(this).treegrid('options').url = '<?php echo $this->webroot ?>reportss/get_ingress_data?<?php echo $param;?>';
                                            $('#loading').show();
                                        }
                                    }
                                });
                                
                                $('#showorhiden').toggle(function(){
                                    $(this).text('Hide Short/Long Call');
                                    $('td[field=call_6s]').show();
                                    $('td[field=call_12s]').show();
                                    $('td[field=call_18s]').show();
                                    $('td[field=call_24s]').show();
                                    $('td[field=call_30s]').show();
                                    $('td[field=call_2h]').show();
                                    $('td[field=call_3h]').show();
                                    $('td[field=call_4h]').show();
                                    ishide = false;
                                }, function() {
                                    $(this).text('Show Short/Long Call');
                                    $('td[field=call_6s]').hide();
                                    $('td[field=call_12s]').hide();
                                    $('td[field=call_18s]').hide();
                                    $('td[field=call_24s]').hide();
                                    $('td[field=call_30s]').hide();
                                    $('td[field=call_2h]').hide();
                                    $('td[field=call_3h]').hide();
                                    $('td[field=call_4h]').hide();
                                    ishide = true;
                                });
                            });
                        </script>

                    </div>


                    <div id="footer">
<?php
    if(Configure::read('is_copyright_hypelink')):
?>
<span><strong><a href="http://www.denovolab.com">DeNovoLab</a>@2010-2012 All Rights Reserved. </strong></span>
<?php
else:
?>
<span><strong>DeNovoLab@2010-2012 All Rights Reserved. </strong></span>
<?php endif; ?>
</div>
                    <!-- All messages -->
                    <?php  
                    if(empty($m)||$m==''){
                    $m=$session->read('m');
                    $session->del('m');
                    }
                    if (!empty($m)) {
                    ?>	
                    <script type="text/javascript">
                        showMessages("<?php echo $m?>");
                    </script>
                    <?php } ?>


                    <?php echo   $appCommon->show_form_value();?>
                    <script type="text/javascript">
                            <?php if(!empty($p)):?>
                            if(document.getElementById("toppage")!=undefined && document.getElementById("tmppage")){
                            document.getElementById("toppage").appendChild(document.getElementById("tmppage").cloneNode(true));
                            var size = document.getElementsByName("size");
                            for (var i = 0;i<size.length;i++)
                                size[i].value = "<?php echo $p->getPageSize();?>";
                        }
                            <?php endif;?>
                            <?php if(!empty($search)){?>
                                $('#advsearch').show();
                            $('#title-search-adv').addClass('opened');
                                <?php }?>
                    </script>
                    <div class="  viewport-bottom"
                         style="display: none; top: 409px; left: 915px; right: auto;"
                         id="tooltip">
                        <h3 style="display: none;"></h3>
                        <div class="body">
                            <dl id="pi-11510-tooltip" class=" ">
                                <dt>Not Active:</dt>
                                <dd>DID-Spain-Madrid</dd>
                                <dd>Testprodukt</dd>
                            </dl>
                        </div>
                        <div style="display: none;" class="url"></div>

                    </div>
                    <div id="loading"></div>

                    <!--    <?php echo $cakeDebug;?>-->
                </body>
                </html>

