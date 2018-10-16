<style type="text/css">
    #advsearch .input {
        font-size: 0.87em;
        width: 100px;
    }
    #advsearch {
        text-align:right;
        display: none;
        margin-bottom: 10px;
        position: relative;
    }
    .list img {
        vertical-align: middle;
    }
    .form .value, .list-form .value{ text-align:center;}
</style>
<script type="text/javascript">
    $(function () {
        $('#b-me-full legend select').bind('change', watchAction);
        watchAction();
        $('#actionPanelEdit select[name*=action]').bind('change', function () {
            var field = $(this).attr('name');
            watchField(field.substring(0, field.indexOf('_action')));
        }).each(function () {
            var field = $(this).attr('name');
            watchField(field.substring(0, field.indexOf('_action')));
        });
    });

</script>
<?php $mydata =$p->getDataArray();?>

<div id="title">
    <h1> <?php __('Switch'); ?>&gt;&gt;<?php echo __('Editing rates',true);?> <font class="editname"> <?php echo empty($name[0][0]['name'])||$name[0][0]['name']==''?'':'['.$name[0][0]['name'].']' ?> </font> </h1>
    <ul id="title-search">
        <li>
            <form action="" method="get" id="likesearch"  >
                <input type="text" id="search-_q_rate"
                       value="<?php if(!empty($_GET['search']['_q'])){echo $_GET['search']['_q'] ;}else{ echo '';}   ?>"
                       class="in-search default-value input in-text defaultText"  name="search[_q]" />
            </form>
        </li>
        <li class="opened" style="display: list-item;" id="title-search-adv" onClick="advSearchToggle();" title="advanced search"></li>
    </ul>
    <script type="text/javascript">
        jQuery(document).ready(function(){
  		
            //$('#id_time_profiles_eq,#search-state_eq').hide();  
            advSearchToggle();
        });
    </script>
    <ul id="title-menu" >
        <!--<li><a onClick="showadd();return false;" class="link_btn" href="#"> <img width="10" height="10" src="<?php echo $this->webroot?>images/add.png"> Add </a></li> -->   
        <?php if(@$_GET['filter_effect_date'] == 'all'): ?>
        <li><a  onclick="show_current_rate();" class="link_btn" href="#"> <img width="10" height="10" src="<?php echo $this->webroot?>images/list.png"> Show  Current </a> </li>
        <?php else: ?>
        <li><a  onclick="show_all_rate();" class="link_btn" href="#"> <img width="10" height="10" src="<?php echo $this->webroot?>images/list.png"> Show  All </a> </li>
        <?php endif; ?>
        <?php  if ($_SESSION['role_menu']['Switch']['clientrates']['model_w']) {?>
        <li>

            <a onClick="addItem();return false;" class="link_btn" href="#"> <img width="10" height="10" src="<?php echo $this->webroot?>images/add.png"> Create New </a>

        </li>
        <li> <a class="link_btn" onClick="return confirm('Are you sure to remove all?')" href="<?php echo $this->webroot?>clientrates/mass_delete/<?php echo $this->params['pass'][0]?>" > <img width="16" height="16" src="<?php echo $this->webroot?>images/delete.png" alt=""/> Delete All </a> </li>
        <!--<li> <a onclick="ex_deleteSelected('list','<?php echo $this->webroot?>/clientrates/delete_all/','rates');" href="javascript:void(0)" rel="popup" class="link_btn" style="float:left;"> <img width="16" height="16" src="<?php echo $this->webroot?>images/delete.png" alt=""/> Delete Selected </a> </li>
        -->
        <?php }?>
        <li><a class="link_btn delete_selected" rel="popup" href="###"><img width="16" height="16" alt="" src="<?php echo $this->webroot?>images/delete.png"> <?php echo __('deleteselected')?></a></li>
        <li>
            <?php // pr($this->params['pass'][1]);  ?>
            <?php if(1/*!isset($this->params['pass'][1]) ||empty($this->params['pass'][1])*/){ ?>
            <a class="link_back" href="<?php echo $this->webroot?>rates/rates_list"> <img width="16" height="16" alt="<?php echo __('goback',true);?>" src="<?php echo $this->webroot?>images/icon_back_white.png">&nbsp;<?php echo __('goback',true);?> </a>
            <?php }else{?>
            <a href="<?php echo $this->webroot?>rates/currency/<?php echo $this->params['pass'][1]?>/currs/currency_list"> <img width="16" height="16" alt="<?php echo __('goback',true);?>" src="<?php echo $this->webroot?>images/icon_back_white.png">&nbsp;<?php echo __('goback',true);?> </a>
            <?php }?>
        </li>

    </ul>
</div>
<div id="container">
    <ul class="tabs">
        <li><a href="<?php echo $this->webroot?>clientrates/view/<?php echo $table_id?>/<?php echo $currency?>"><img width="16" height="16" src="<?php echo $this->webroot?>images/menuIcon.gif"><?php echo __('Rates',true);?> </a></li>
        <?php if ($jur_type == 3 || $jur_type == 4): ?>
        <li class="active"><a href="<?php echo $this->webroot?>clientrates/view/<?php echo $table_id?>/<?php echo $currency?>/npan"><img width="16" height="16" src="<?php echo $this->webroot?>images/menuIcon.gif"><?php echo __('NPANXX Rate',true);?> </a></li>
        <?php endif; ?>
        <li><a href="<?php echo $this->webroot?>clientrates/simulate/<?php echo $table_id?>"><img width="16" height="16" src="<?php echo $this->webroot?>images/simulate.gif"> <?php echo __('Simulate',true);?></a></li>
        <?php  if ($_SESSION['role_menu']['Switch']['clientrates']['model_x']) {?>
        <li><a href="<?php echo $this->webroot?>clientrates/import/<?php echo $table_id?>"><img width="16" height="16" src="<?php echo $this->webroot?>images/import.png"> <?php echo __('import',true);?></a></li>
        <li><a href="<?php echo $this->webroot?>downloads/rate/<?php echo $table_id?>"><img width="16" height="16" src="<?php echo $this->webroot?>images/export.png"> <?php echo __('export',true);?></a></li>
        <?php }?>
    </ul>
    <div id="advsearch" class="jalign" style="display:;clear:both;">
        <form style="margin:10px"  action="get"  id="advsearch_form">
            <input type="hidden" id="id" value="7" name="id" class="input in-hidden">
            <input type="hidden" id="is_down" name="is_down" class="input in-hidden" value="0">
            <table >
                <tbody >
                    <tr>
                        <td style="text-align:right"><?php echo __('Profile',true);?>:</td>
                        <td class="value" style="text-align:left"><?php 
                            $sel=!empty($_GET['id_time_profiles_eq'])?$_GET['id_time_profiles_eq']:'';
                            echo $form->input('client_id',array('options'=>$timepro,'empty'=>'','style'=>'width:200px;','id'=>"id_time_profiles_eq",'name'=>"id_time_profiles_eq", 'selected'=>$sel,
                            'label'=>false, 'class' =>'select' ,'div'=>false,'type'=>'select'));
                            ?></td>
                        <td style="text-align:right"><?php echo __('Grace Time',true);?>:</td>
                        <td class="value" style="text-align:left" ><input type="text" id="search-grace_time_gte" class="in-decimal input in-text" value="" name="search[grace_time_gte]">
                            &ndash;
                            <input type="text" id="search-grace_time_lte" class="in-decimal input in-text" value="" name="search[grace_time_lte]"></td>
                        <td style="text-align:right;" ><?php echo __('Rate',true);?>:</td>
                        <td class="value" style="text-align:left;"><input type="text" id="search-rate_per_min_gte" class="input in-text" 
                                                                          value="<?php if(!empty($_GET['search']['rate_per_min_gte'])){echo $_GET['search']['rate_per_min_gte'] ;}else{ echo '';}   ?>"
                                                                          name="search[rate_per_min_gte]">
                            &ndash;
                            <input type="text" id="search-rate_per_min_lte" class="in-decimal input in-text" 
                                   value="<?php if(!empty($_GET['search']['rate_per_min_lte'])){echo $_GET['search']['rate_per_min_lte'] ;}else{ echo '';}   ?>"
                                   name="search[rate_per_min_lte]"></td>
                    </tr>
                    <tr>
                        <td style="text-align:right;"><?php echo __('Setup Fee',true);?>:</td>
                        <td class="value" style="text-align:left"><input type="text" id="search-pay_setup_gte" class="in-decimal input in-text" 
                                                                         value="<?php if(!empty($_GET['search']['pay_setup_gte'])){echo $_GET['search']['pay_setup_gte'] ;}else{ echo '';}   ?>"
                                                                         name="search[pay_setup_gte]">
                            &ndash;
                            <input type="text" id="search-pay_setup_lte" class="in-decimal input in-text" 
                                   value="<?php if(!empty($_GET['search']['pay_setup_lte'])){echo $_GET['search']['pay_setup_lte'] ;}else{ echo '';}   ?>"
                                   name="search[pay_setup_lte]"></td>
                        <td style="text-align:right"><?php echo __('Min Time',true);?>:</td>
                        <td class="value" style="text-align:left" ><input type="text" id="search-min_time_gte" class="in-decimal input in-text" value="" name="search[min_time_gte]">
                            &ndash;
                            <input type="text" id="search-min_time_lte" class="in-decimal input in-text" value="" name="search[min_time_lte]"></td>
                        <td style="text-align:right"><?php echo __('Interval',true);?>:</td>
                        <td class="value" style="text-align:left" ><input type="text" id="search-pay_interval_gte" class="in-decimal input in-text" value="" name="search[pay_interval_gte]">
                            &ndash;
                            <input type="text" id="search-pay_interval_lte" class="in-decimal input in-text" value="" name="search[pay_interval_lte]"></td>
                    </tr>
                    <tr>

                        <td style="text-align:right;"><?php echo __('Time',true);?></td>
                        <td class="value" style="text-align: left;" ><?php 
                            $st=array('current'=>'current on','new'=>'future for', 'old'=>'old for','all'=>'all', 'in'=>'in', 'testers'=>'testers');
                            $sel=!empty($_GET['search']['state_eq'])?$_GET['search']['state_eq']:'all';
                            echo $form->input('status',array('options'=>$st,'label'=>false,'div'=>false,'selected'=>$sel,'type'=>'select','name'=>'search[state_eq]','id'=>'search-state_eq','style'=>'width:200px'))
                            ?>
                        </td>
                        <td  colspan="2" class="value" style=" text-align:right !important;display:none;" id="timenow">
                            <input type="text" name="search[now]" 
                                   onfocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'});" class="in-decimal input in-text in-input wdate" readonly="readonly"
                                   value="<?php if(!empty($_GET['search']['now'])){echo $_GET['search']['now'] ;}else{ echo date ( "Y-m-d  H:i:s" );}?>"
                                   id="search-now-wDt">
                                   &ndash;
                                   <input type="text" name="search[now2]" 
                                   onfocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'});"class="in-decimal input in-text in-input wdate" readonly="readonly" value="<?php if(!empty($_GET['search']['now2'])){echo $_GET['search']['now2'] ;}else{ echo date ( "Y-m-d  H:i:s" );}?>"
                                   id="search-now2-wDt"></td>

                        <td class="value" colspan="2" style="text-align:right;"><?php 
                            $sw=array('html'=>'HTML','csv'=>'CSV');
                            $sel=!empty($_GET['search']['show'])?$_GET['search']['show']:'html';
                            echo $form->input('status',array('options'=>$sw,'label'=>false,'div'=>false,'selected'=>$sel,'type'=>'select','name'=>'search[show]','id'=>'search-show','style'=>'width:120px'))
                            ?>&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" value="Search" onclick='jQuery("#advsearch").xForm({method:"get"})' class="input in-submit"></td>
                    </tr>
                </tbody>
            </table>
        </form>
    </div>

    <div id="top-control"  style="margin-top: -45px;"> </div>
    <div id="toppage"></div>
    <div id="noRows" class="msg"><?php echo __('no_data_found',true);?></div>
    <form id="objectForm" method="post" action="<?php echo $this->webroot?>clientrates/add_rate?page=<?php echo $p->getCurrPage()?>&size=<?php echo $p->getPageSize()?>">
        <input type="hidden" id="delete_rate_id" value="" name="delete_rate_id" class="input in-hidden">
        <input type="hidden" id="id" value="<?php echo $table_id?>" name="id" class="input in-hidden">
        <input type="hidden" value="1" name="page" class="input in-hidden">
        <div id="showadd" style="display:none;">
            <table class="list list-form">
                <tbody>
                    <tr>
                        <td>Rate Table Name:<input class="input in-text in-input" readonly value="<?=$addShowResult['name']?>" /></td>
                        <td>Code Deck:<input class="input in-text in-input" readonly value="<?=$addShowResult['code_deck_id']?>" /></td>
                        <td>Currency:<input class="input in-text in-input" readonly value="<?=$addShowResult['currency']?>" /></td>
                        <td>Type:<input class="input in-text in-input" readonly value="<?=$addShowResult['rate_type']?>" /></td>
                        <td>Jurisdiction:<input class="input in-text in-input" readonly value="<?=$addShowResult['jurisdiction_country_id']?>" /></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <table id="list" style="display: none;" class="list list-form">
            <thead>
                <tr>
                    <td width="25"><input id="selectAll" name="checkbox" type="checkbox"/></td>
                    <td><?php echo $appCommon->show_order('code',__('code',true))?></td>
                    <td><?php echo $appCommon->show_order('code_name',__('code name',true))?></td>
                    <td><?php echo $appCommon->show_order('country',__('Country',true))?></td>
                    <td><?php echo __('Rate',true);?></td>
                    <?php if($appRate->is_show_jur_rate($table_id)){?>
                    <td><?php echo __('Intra Rate',true);?></td>
                    <td><?php echo __('Inter Rate',true);?></td>    
                    <?php }?>
                    <td><?php echo $appCommon->show_order('effective_date',__('Effective Date',true))?></td>
                    <td><?php echo $appCommon->show_order('end_date',__('End Date',true))?></td>
                    <td><span rel="helptip" class="helptip" id="ht-100006"><?php echo __('Extra Fields',true);?></span><span class="tooltip" id="ht-100006-tooltip">Min Time / Interval / Grace Time / Profile / Notes</span></td>
                </tr>
            </thead>
            <tbody id="rows">
                <tr id="tpl">
                    <td><input class="selected" type="checkbox"/><input type="hidden" name="rate_id" /></td>
                    <td class="value">
                        <input type="text" name="code" style="_width:60px; _float:left;font-weight:bold;" />
                    </td>
                    <td class="value" id="tpl-code_name-write"><input type="text" name="code_name" class="code_name-input code_name" style="_float:left; _width:80px;" />
                    </td>
                    <td class="value"><input type="text" name="country" class="country-input country"  style="_float:left; _width:80px;"  />
                    </td>
                    <td class="value" id="tpl-code_name-read"><small id="tpl-code_name-text">code_name</small>
                        <input type="hidden" name="code_name" /></td>
                    <td class="value" ><input type="text" rel="format_number" name="rate" style="font-weight:bold;text-align:right;" /></td>
                    <?php if($appRate->is_show_jur_rate($table_id)){?>
                    <td><input type="text"  rel="format_number" class="in-decimal" style="width:60px;" name="intra_rate"/>
                        &nbsp;&nbsp;&nbsp;</td>
                    <td><input type="text" rel="format_number" class="in-decimal" style="width:60px;" name="inter_rate"/>
                        &nbsp;&nbsp;&nbsp;</td>
                    <?php }?>
                    <td class="value">
                        <input type="text" name="effective_date" style="width:140px;"   class="input in-text wdate" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'});"  />
                        <select name="effective_date_timezone" style="width:100px;">
                            <option value="-12">GMT -12:00</option>
                            <option value="-11">GMT -11:00</option>
                            <option value="-10">GMT -10:00</option>
                            <option value="-09">GMT -09:00</option>
                            <option value="-08">GMT -08:00</option>
                            <option value="-07">GMT -07:00</option>
                            <option value="-06">GMT -06:00</option>
                            <option value="-05">GMT -05:00</option>
                            <option value="-04">GMT -04:00</option>
                            <option value="-03">GMT -03:00</option>
                            <option value="-02">GMT -02:00</option>
                            <option value="-01">GMT -01:00</option>
                            <option value="+00"   selected="selected">GMT +00:00</option>
                            <option value="+01">GMT +01:00</option>
                            <option value="+02">GMT +02:00</option>
                            <option value="+03">GMT +03:00</option>
                            <option value="+03">GMT +03:30</option>
                            <option value="+04">GMT +04:00</option>
                            <option value="+05">GMT +05:00</option>
                            <option value="+06">GMT +06:00</option>
                            <option value="+07">GMT +07:00</option>
                            <option value="+08">GMT +08:00</option>
                            <option value="+09">GMT +09:00</option>
                            <option value="+10">GMT +10:00</option>
                            <option value="+11">GMT +11:00</option>
                            <option value="+12">GMT +12:00</option>
                            <option value=""></option>
                        </select>
                    </td>
                    <td class="value" >
                        <input type="text" name="end_date" style="width:140px;"  class="input in-text wdate" onFocus="WdatePicker({startDate:'%y-%M-01 23:59:59',dateFmt:'yyyy-MM-dd HH:mm:ss',alwaysUseStartDate:false})" />
                        <select name="end_date_timezone" style="width:100px;">
                            <option value="-12">GMT -12:00</option>
                            <option value="-11">GMT -11:00</option>
                            <option value="-10">GMT -10:00</option>
                            <option value="-09">GMT -09:00</option>
                            <option value="-08">GMT -08:00</option>
                            <option value="-07">GMT -07:00</option>
                            <option value="-06">GMT -06:00</option>
                            <option value="-05">GMT -05:00</option>
                            <option value="-04">GMT -04:00</option>
                            <option value="-03">GMT -03:00</option>
                            <option value="-02">GMT -02:00</option>
                            <option value="-01">GMT -01:00</option>
                            <option value="+00"   selected="selected">GMT +00:00</option>
                            <option value="+01">GMT +01:00</option>
                            <option value="+02">GMT +02:00</option>
                            <option value="+03">GMT +03:00</option>
                            <option value="+03">GMT +03:30</option>
                            <option value="+04">GMT +04:00</option>
                            <option value="+05">GMT +05:00</option>
                            <option value="+06">GMT +06:00</option>
                            <option value="+07">GMT +07:00</option>
                            <option value="+08">GMT +08:00</option>
                            <option value="+09">GMT +09:00</option>
                            <option value="+10">GMT +10:00</option>
                            <option value="+11">GMT +11:00</option>
                            <option value="+12">GMT +12:00</option>
                            <option value=""></option>
                        </select>
                    </td>
                    <td id="tpl-params-block"><a href="#" class="tpl-params-link" title="Additional properties"><small id="tpl-params-text"></small> <b class="neg">&raquo;</b></a>
                        <div class="tooltip-box params-block" style="display:none;">
                            <table id="xs">
                                <tr>

                                    <td><?php echo __('Setup Fee',true);?>:
                                        <input type="text" style="width:80px;"  rel="format_number"  name="setup_fee" style="text-align:right;" /></td>
                                    <td><?php echo __('Min Time',true);?>:
                                        <input id="min_time" style="width:80px;"  type="text" name="min_time"    class="in-decimal" />
                                        sec</td>
                                    <td><?php echo __('Interval',true);?>:
                                        <input type="text" style="width:80px;"  name="interval" class="in-decimal" />
                                        sec</td>
                                    <td><?php echo __('Grace Time',true);?>:
                                        <input id="grace_time"  style="width:80px;"  type="text" name="grace_time" class="in-decimal" />
                                        sec</td>
                                    <td><?php echo __('Seconds',true);?>:
                                        <input type="text"  style="width:80px;"  name="seconds" class="in-decimal" />
                                        sec</td>
                                    <td><?php echo __('Profile',true);?>:
                                        <?php 
                                        echo $form->input('client_id',array('options'=>$timepro,  'id'=>"time_profile_id",'name'=>"time_profile_id",'empty'=>' ',
                                        'label'=>false, 'class' =>'select' ,'div'=>false,'type'=>'select','class'=>"in-decimal"));
                                        ?>
                                        &nbsp;&nbsp;&nbsp; </td>
                                    
                                    <!--<td><?php echo __('Time Zone',true);?>:
                                    <?php 
                                                                                                                  echo $form->input('client_id',array('options'=>$appRate->get_time_zone(),  'id'=>"zone",'name'=>"zone",'empty'=>' ',
                                                                                                  'label'=>false, 'class' =>'select' ,'div'=>false,'type'=>'select','class'=>"in-decimal"));
                                                                                                                  ?>
                                      &nbsp;&nbsp;&nbsp; </td>-->
                                    <?php if($appRate->is_show_jur_rate($table_id)){?>
                                    <!--
                                      <td>Intra rate:</td>
                                      <td><input type="text"  rel="format_number" class="in-decimal" name="intra_rate"/>
                                        &nbsp;&nbsp;&nbsp;</td>
                                      <td>Inter rate:</td>
                                      <td><input type="text" rel="format_number" class="in-decimal" name="inter_rate"/>
                                        &nbsp;&nbsp;&nbsp;</td>
                                    -->
                                    <td><?php echo __('Local Rate',true);?>:
                                        <input type="text" style="width:80px;"   class="in-decimal"  rel="format_number" name="local_rate" />
                                        &nbsp;&nbsp;&nbsp;</td>
                                </tr>
                                <?php }?>
                            </table>
                        </div></td>
                    
                </tr>
            </tbody>
        </table>
    </form>
    <div id="tmppage"> <?php echo $this->element('page');?> </div>
    
    <script type="text/javascript">
        function mass_fun(){
            var re=true;
	
            var arrs;
            arrs=Array();
            jQuery('#list tbody tr.row-1,#list tbody tr.row-2').each(
            function(){
                if (jQuery(this).find("input[type='checkbox']").attr("checked") == true)
                {
                    var rate_id=jQuery(this).find('input[name*=rate_id]').val();
                    var arr;
                    arr=Array(rate_id);				
                    arrs.push(arr);
                }
            }			
        );
            $("#rate_ids").attr('value',arrs);
            //alert(arrs);
            if (0)//($("#action").val() != 'delete')
            {
                if(! /^\d+(\.)?\d+$/.test(jQuery('#inter_rate_value:visible').val())){
                    jQuery.jGrowl("The field Intrastate Rate must be numeric only!",{theme:'jmsg-error'});
                    re =false;
                }

                if(! /^\d+(\.)?\d+$/.test(jQuery('#local_rate_value:visible').val())){
                    jQuery.jGrowl("The field Local rate must be numeric only!",{theme:'jmsg-error'});
                    re =false;
                }

                if(! /^\d+(\.)?\d+$/.test(jQuery('#intra_rate_value:visible').val())){
                    jQuery.jGrowl("Interstate Rate, must contain numeric characters only!",{theme:'jmsg-error'});
                    re =false;
                }
            }
            return re;
        }

        function valiReport(){
            var arrs;
            arrs=Array();
            var re=true;
            jQuery('#list tbody tr.row-1,#list tbody tr.row-2').each(
            function(){
                //	var code_name=jQuery(this).find('input[id*=code]').val();
                var effective_date=jQuery(this).find('input[id*=effective_date]').val();
                var end_date=jQuery(this).find('input[id*=end_date]').val();
                var time_profile_id=jQuery(this).find('select[id*=time_profile_id]').val();
                var time_profile=jQuery(this).find('select[id*=time_profile_id]').find("option:selected").text();
                var arr;
                arr=Array(effective_date,end_date,time_profile_id);
                for(var i in arrs){
                    if(!isReport(arrs[i],arr)){
                        re=true;
                        time_profile=jQuery.trim(time_profile);
                        if(time_profile==''){
                            time_profile='is already in use!';

                        }
                        //	jQuery.jGrowlError(code_name+' '+time_profile );
                        //	break;
                    }
                }
                arrs.push(arr);
            }
        );
	


            jQuery('input[id$=-code]:visible').each(function(){
                if(/\D/.test(jQuery(this).val())){
                    jQuery.jGrowl('Code , must be whole number! ',{theme:'jmsg-error'});               
                    re = false;
                }
            });
            jQuery('input[id$=min_time]').each(function(){
                if(/\D/.test(jQuery(this).val())){
                    jQuery.jGrowl("Min Time, must be whole number!",{theme:'jmsg-error'});
                    re =false;
                }
            });

            jQuery('input[id$=interval]').each(function(){
                if(/\D/.test(jQuery(this).val())){
                    jQuery.jGrowl("Interval, must be whole number! ",{theme:'jmsg-error'});
                    re =false;
                }
            });
            jQuery('input[id$=grace_time]').each(function(){
                if(/\D/.test(jQuery(this).val())){
                    jQuery.jGrowl("Grace Time, must be whole number!  ",{theme:'jmsg-error'});
                    re =false;
                }


            }) 
            jQuery('input[id$=seconds]').each(function(){
                if(/\D/.test(jQuery(this).val())){
                    jQuery.jGrowl("Seconds, must be whole number!",{theme:'jmsg-error'});
                    re =false;
                }
            });
	
            return re;
        }


        function isReport(arr1,arr2){
            if(arr2[0]=='' && arr2[3]==''){
                return true;
            }
            if(arr1[0]==arr2[0] && arr1[3]==arr2[3]){
                return false;
            }
            return true;
        }
        jQuery(document).ready(function(){
            jQuery('#selectAll').selectAll('.selected');
        });

    </script> 
    <script type="text/javascript">
        //<[!CDATA[
        function watchAction()
        {
            var sAction = $('#action').val();
            if (sAction == 'delete') {
                $('#actionPanelEdit').hide();
                $('#actionPanelDelete').show();
            } else {
                $('#actionPanelEdit').show();
                $('#actionPanelDelete').hide();
            }
    
            var elAction = $('#effective_from_action').get(0);
            if (sAction == 'insert' && elAction.options.length == 2) {
                // elAction.options[0] = null;
            } else if (sAction == 'update' && elAction.options.length == 1) {
                var elOption = document.createElement('option');
                elOption.value = 'none';
                elOption.innerHTML = 'preserve';
                elAction.insertBefore(elOption, elAction.options[0]);
                elAction.selectedIndex = 0;
            }
            watchField('effective_from');
        }
        function watchField(fname)
        {
            if ($('#'+fname+'_action').val() == 'none') {
                $('#'+fname+'_value').hide();
            } else {
                $('#'+fname+'_value').show();
            }
        }

        function actionProcess()
        {
            if (!confirm('Continue with specified parameters?')) {
                return false;
            }
            if(! mass_fun() && $("#action").val() != 'delete'){
                return false;
            }
            $('#action_preview').attr('disabled', 'disabled');
            $('#action_process').attr('disabled', 'disabled');
            $('#stage_param').val('process');
            //鍋氭牎楠?
  


  
            $('#actionForm').submit();
        }
        //]]>
    </script> 
    <script type="text/javascript">
        function codename_all_callback(code_name){
            var data=jQuery.ajaxData('<?php echo $this->webroot?>codedecks/find_codename/'+code_name);
            data=eval(data);
            jQuery('#list tr:visible').each(function(){
                if(jQuery(this).find('td:nth-child(3)').find('input').val()==code_name){
                    jQuery(this).remove();
                }
            });
            for(var i in data){
                var obj=data[i].Code;
                var code=obj.code;
                var code_name=obj.name;
                var country=obj.country;

                var tr=addItem();
                jQuery(tr).find('input[id*=-code]').val(code);
                jQuery(tr).find('input[id*=-code_name]').val(code_name);
                jQuery(tr).find('input[id*=-country]').val(country);
                jQuery(tr).find('input[id$=-rate]').val(jQuery(CodeNameTr).find('input[id$=-rate]').val());
                jQuery(tr).find('input[id$=-setup_fee]').val(jQuery(CodeNameTr).find('input[id$=-setup_fee]').val());
                jQuery(tr).find('input[id$=-effective_date]').val(jQuery(CodeNameTr).find('input[id$=-effective_date]').val());
                jQuery(tr).find('input[id$=-end_date]').val(jQuery(CodeNameTr).find('input[id$=-end_date]').val());
                CodeNameTr.remove();
            }
        }
        function country_all_callback(country){
            var data=jQuery.ajaxData('<?php echo $this->webroot?>codedecks/find_country/'+country);
            data=eval(data);
            jQuery('#list tr:visible').each(function(){
                if(jQuery(this).find('td:nth-child(4)').find('input').val()==country){
                    jQuery(this).remove();
                }
            });
            for(var i in data){
                var obj=data[i].Code;
                var code=obj.code;
                var code_name=obj.name;
                var country=obj.country;
                var tr=addItem();
                jQuery(tr).find('input[id*=-code]').val(code);
                jQuery(tr).find('input[id*=-code_name]').val(code_name);
                jQuery(tr).find('input[id*=-country]').val(country);
                jQuery(tr).find('input[id$=-rate]').val(jQuery(CodeNameTr).find('input[id$=-rate]').val());
                jQuery(tr).find('input[id$=-setup_fee]').val(jQuery(CodeNameTr).find('input[id$=-setup_fee]').val());
                jQuery(tr).find('input[id$=-effective_date]').val(jQuery(CodeNameTr).find('input[id$=-effective_date]').val());
                jQuery(tr).find('input[id$=-end_date]').val(jQuery(CodeNameTr).find('input[id$=-end_date]').val());
                CodeNameTr.remove();
            }
        }
    </script>
    
  
    <script type="text/javascript">
        jQuery('#sub').click(
        function(){
            if(valiReport()){
                jQuery('#objectForm').submit();
            }else{
                jQuery(this).removeAttr('disabled');
            }
			
	
        });
        var lastId = 0;
        var eRows = $('#rows');
        var eTpl = $('#tpl').unbind();
        var profiles=<?php echo $t?>;
        //var profiles = {"2":"business time","3":"non-business time","4":"weekends","8":"1200","9":"all time","10":"OFFLINE","11":"Ramka Tutulik","15":"fulltime","1":"all time 2"};
    </script> 
    <script type="text/javascript">
        function showadd(){
            document.getElementById('showadd').style.display='block';
        }
        function addItem(rows, append) 
        {
            lastId++;
            if (lastId == 1) {
                $('#noRows').hide();
                $('.list-form').show();
            }
            if (!rows || !rows['rate_id']) {
                row = {
                    'effective_date': '<?php echo date('Y-m-d 00:00:00')?>',
                    'time_profile_id': '',
                    'rate': '0.000000',
                    'min_time': '1',
                    'seconds': '60',
                    'interval': '1',
                    'grace_time': '0',
                    'intra_rate': '',
                    'inter_rate': '',
                    'local_rate': ''
                };
            }else{
                row=rows;
            }
            if (profiles[row['time_profile_id']] == undefined) {
                row['time_profile_id'] = '';
            }
            for (k in row) { if (row[k] == null) row[k] = ''; }
            if(row['rate'] != '') {
                row['rate'] = printf('%.6f', row['rate']);
            }
            row['setup_fee'] = printf('%.6f', row['setup_fee']);
            var prefixId = 'row-'+lastId;
            var prefixName = 'rates['+lastId+']';
            var tRow = eTpl.clone().attr('id', prefixId).show();//涓存椂鍑嗗鐨勮
            if (row['rate_id']) {
                tRow.find('#tpl-code_name-read').unbind().remove();
                $('<input type="hidden" name="currentItems[]" />').val(row['rate_id']).appendTo($('#objectForm'));
                if (row['code_simulated']) {
                    tRow.find('#tpl-code_name-read').addClass('s-none');
                }
            } else {
                tRow.find('#tpl-code_name-read').unbind().remove();
            }
            tRow.find('input,select').each(function () {
                var el = $(this);
                var field = el.attr('name');
                el.attr({id: prefixId+'-'+field, name: prefixName+'['+field+']'}).val(row[field]);
            });
            tRow.find('#tpl-code_name-text').text(row['code_name'] ? row['code_name'] : '');
            buildParams(tRow);
            if (row['rate_id']) {
                tRow.appendTo(eRows);
            } else {
                tRow.prependTo(eRows);
            }
            if (!row['rate_id']) {
                initForms(tRow); 
                initList();
            }
            if(!rows){
                tRow.find('input.selected').hide();
            }else{
                tRow.find('input.selected').val(rows.rate_id);
            }
            return tRow;
        }

        function buildParams(row)
        {
            var s = '';
            s = row.find('input[name*=min_time]').val() + ' / ' 
                + row.find('input[name*=interval]').val() + ' / '
                + row.find('input[name*=grace_time]').val() + ' / '
                + profiles[row.find('select[name*=time_profile_id]').val()];
    
            row.find('#tpl-params-text').html(s);
            if ($(row).find('input[name*=notes]').val() == '') {
                row.find('#tpl-params-block').find('b').hide();
            } else {
                row.find('#tpl-params-block').find('b').show();
            }
            return s;
        }
        function hideParams()
        {
            $('.tempcls').remove();
            $('#rows div.params-block:visible').hide().attr('id', '').each(function () {
                buildParams($(this).parent().parent());
            });
        }
        function findCode(rowId, type) 
        {
            var _ss_ids = {};
            if (type != 'code_name') {
                _ss_ids['code'] = rowId+'-code';
            }
            _ss_ids['code_name'] = rowId+'-code_name';
            _ss_ids['country']=rowId+'-country';
            ss_code(1, _ss_ids,undefined,"<?php echo array_keys_value($mydata,'0.0.code_deck_id')?>");
        }
        function findCodeName(rowId,type,tr){
            var _ss_ids = {};
            CodeNameTr=jQuery(tr);
            _ss_ids['code'] = rowId+'-code';
            _ss_ids['code_name'] = rowId+'-code_name';
            _ss_ids['country']=rowId+'-country';
            ss_codename_all(1, _ss_ids,undefined,"<?php echo array_keys_value($mydata,'0.0.code_deck_id')?>");
        }
        function findCountry(rowId,type,tr){
            var _ss_ids = {};
            CodeNameTr=jQuery(tr);
            _ss_ids['code'] = rowId+'-code';
            _ss_ids['code_name'] = rowId+'-code_name';
            _ss_ids['country']=rowId+'-country';
            ss_country_all(1, _ss_ids,undefined,"<?php echo array_keys_value($mydata,'0.0.code_deck_id')?>");
        }

        // live event handlers
        $('#rows #tpl-params-block div').live('click', function (e) {
            e.stopPropagation();
        });
        $('#rows #tpl-params-block div a').live('click', function () {
            hideParams();
            return false;
        });

        $('#rows .tpl-params-link').live('click', function () {
            var vis = 0;
            var div = $(this).parent().find('div');
            if (div.is(':visible')) vis = 1;
            hideParams();
            if (!vis) {
                div.attr({'id':'tooltip','height':'300px'}).show();
                $(this).parent().parent().after('<tr class="tempcls"><td colspan="9" height="55px"></td></tr>');
            }
            return false;
        });

        $('#rows #tpl-code-search').live('click', function () {
            findCode($(this).closest('tr').attr('id'), 'code');
        });
        $('#rows #tpl-code_name-search').live('click', function () {
            findCodeName($(this).closest('tr').attr('id'), 'code',$(this).closest('tr'));
        });

        $('#rows #tpl-country-search').live('click', function () {
            findCountry($(this).closest('tr').attr('id'), 'code',$(this).closest('tr'));
        });
        //
        $('#rows').find('input[rel*=format_number]').live('keyup',function(){
            jQuery(this).xkeyvalidate({type:'Ip'});
            //filter_chars(this);
        });

        $('#rows #tpl-delete-row').live('click', function () {
            if(confirm(" Are you sure to delete rates "+$(this).closest('tr').find('input[name*=code]').val())){
                var del_rate_id=$(this).closest('tr').find('input[name*=rate_id]').val();


	
                if(del_rate_id!=null&& del_rate_id!=''){
                    var del_val=$('#delete_rate_id').val()+","+del_rate_id;
                    $('#delete_rate_id').val(del_val);
                    $.ajax({
                        url:"<?php echo $this->webroot?>clientrates/ajax_delete_rate.json",
                        data:{rate_id:del_rate_id},
                        type:'POST',
                        async:false,
                        success:function(text){
                            if(text=='1'){
                                showMessages("[{'field':'#ingrLimit','code':'201','msg':'this  rate  delete   success'}]");
                            }
                        },
                        error:function(XmlHttpRequest){showMessages("[{'field':'#ingrLimit','code':'101','msg':'"+XmlHttpRequest.responseText+"'}]");}
                    });
		
                }

	
                $(this).closest('tr').remove();
                //璁板綍鍒犻櫎鐨刬d
            }
            return false;
        });
        $(window).click(hideParams);
        // fill itesm
            <?php 
        foreach ($mydata  as  $key =>$value){
            $time_profile_id= !empty($value[0]['time_profile_id'])?$value[0]['time_profile_id']:'';
            $rate_id=!empty($value[0]['rate_id'])?$value[0]['rate_id']:'';
            $rate_table_id=!empty($value[0]['rate_table_id'])?$value[0]['rate_table_id']:'';
            $code=!empty($value[0]['code'])?$value[0]['code']:'';
            $rate=!empty($value[0]['rate'])?$value[0]['rate']:'';
            $setup_fee=!empty($value[0]['setup_fee'])?$value[0]['setup_fee']:'0.000000';
            $effective_date=!empty($value[0]['effective_date'])?$appCommon->del_date_timezone($value[0]['effective_date']):'';
            $effective_date_timezone='+00';
            $end_date=!empty($value[0]['end_date'])?$appCommon->del_date_timezone($value[0]['end_date']):'';
            $end_date_timezone='+00';
            $min_time=!empty($value[0]['min_time'])?$value[0]['min_time']:'0';
            $grace_time=!empty($value[0]['grace_time'])?$value[0]['grace_time']:'0';
            $interval=!empty($value[0]['interval'])?$value[0]['interval']:'0';
            $seconds=!empty($value[0]['seconds'])?$value[0]['seconds']:'0';
            $code_name=!empty($value[0]['code_name'])?$value[0]['code_name']:'';
            $intra_rate=!empty($value[0]['intra_rate'])?$value[0]['intra_rate']:"''";
            $inter_rate=!empty($value[0]['inter_rate'])?$value[0]['inter_rate']:"''";
            $local_rate=!empty($value[0]['local_rate'])?$value[0]['local_rate']:"''";
            $country=!empty($value[0]['country'])?$value[0]['country']:'';
            $zone=$value[0]['zone'];
            $ocn = $value[0]['ocn'];
            $lata = $value[0]['lata'];
            echo "addItem({\"rate_id\":\"$rate_id\",\"rate_table_id\":\"$rate_table_id\",\"time_profile_id\":\"$time_profile_id\"	,\"code\":\"$code\",\"effective_date\":\"$effective_date\", \"effective_date_timezone\" : \"$effective_date_timezone\" ,\"end_date\":\"$end_date\",\"end_date_timezone\" : \"$end_date_timezone\",\"rate\":\"$rate\",\"setup_fee\":\"$setup_fee\",\"interval\":\"$interval\",\"min_time\":\"$min_time\",\"grace_time\":\"$grace_time\",\"seconds\":$seconds,\"code_name\":\"$code_name\",	\"intra_rate\":$intra_rate,\"inter_rate\":$inter_rate,\"local_rate\":$local_rate,country:\"$country\",zone:'$zone', ocn:'$ocn', lata:'$lata'}, 1);\n"; 
		
        }
        if(empty($mydata)){echo "$('#toppage').remove();$('#tmppage').remove();";}
            ?>
            eRows.hide();
        eRows.show();
        eTpl.hide();
        jQuery(document).ready(function(){eTpl.remove()});
    </script> 
    <script type="text/javascript">
        function aa (){
            if(jQuery('#search-state_eq').attr('value')=='all'){
                /* jQuery('#search-now-wDt').attr('style','display:none'); 
                     jQuery('#search-now2-wDt').attr('style','display:none'); */	  
                jQuery('#timenow').attr('style','display:none'); 
            }else{
                /*jQuery('#search-now-wDt').attr('style','display:inlike');
                     jQuery('#search-now2-wDt').attr('style','display:inlike');	*/
                jQuery('#timenow').attr('style','display:inlike').css('text-align','right');    
            }	
        }

        jQuery('#action_preview').click(function(){
            var ret=true;
            ret=mass_fun();
            return ret;
        });

        jQuery('#search-state_eq').change(function(){
            aa();
        });

        jQuery(document).ready(function(){
            $('#ratetable_info_btn').click(function() {
                $('#ratetable_info').toggle();
            });
    
            $('.set_to_null').click(function() {
                $(this).prev().val('');
            });

            aa();
            jQuery('#advsearch').attr("style","display:none");
            jQuery('#rate_per_min_value,#pay_setup_value,#intra_rate_value').xkeyvalidate({type:'Ip'});
            jQuery('#min_time_value,#pay_interval_value,#grace_time_value,#inter_rate_value').xkeyvalidate({type:'Num'})
        });


        $(function() {
            $('.delete_selected').click(function() {
                var delete_ids = new Array();
                $('#list tbody input:checkbox:checked').each(function(index, item) {
                    delete_ids.push($(this).val());
                });
                deleteSelected('list','<?php echo $this->webroot; ?>clientrates/mass_delete/<?php echo $this->params['pass'][0]?>/' + delete_ids);
            });
            $('.country').live('click', function(){
                $(this).autocomplete(countries)
            });

            $('.code_name').live('click', function(){
                $(this).autocomplete(cities)
            });
            
            $('#is_down').value('0') 
            
            $('#actionForm').submit(function() {
                $('#is_down').value('1');
                $('#advsearch_form').submit();
                return false;
            });
        });

    </script> 
</div>
