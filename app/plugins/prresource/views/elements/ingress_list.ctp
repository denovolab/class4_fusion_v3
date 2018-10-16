
<?php $w = $session->read('writable');?>

<?php //*********************  鏉′欢********************************?>
<!--
<fieldset class="title-block" id="advsearch"  style="width: 100%;clear:both;">
         <form  action=""  method="get">
<table>
<tbody>
<tr>
        <td id="client_cell" class="value" style="text-align:right;width:600px">
    <label><?php echo __('Carriers')?>:</label>
    <input class="input in-hidden" name="query[id_clients]" value="" id="query-id_clients" type="hidden" style="width:120px">
    <input type="text" id="query-id_clients_name" onclick="showClients()" style="width:120px;" readonly="1" value="" name="query[id_clients_name]" class="input in-text">        
    <img width="25" height="25" onclick="showClients()" class="img-button" src="<?php echo $this->webroot ?>images/search-small.png">
    <img width="25" height="25" onclick="ss_clear('client', _ss_ids_client)" class="img-button" src="<?php echo $this->webroot ?>images/delete-small.png">
                &nbsp;&nbsp;&nbsp;&nbsp;
    <label>Ip:</label>
        <?php echo $xform->search('filter_ip',Array("style"=>"width:120px",'value'=>$$hel->_get('filter_ip')))?>
  </td>
  <td class="buttons" style="width:50px"><input type="submit" value="Search" class="input in-submit"></td>
</tr>
</tbody></table>
</form></fieldset>
-->
<?php 
$mydata =$p->getDataArray();
if (count($mydata) == 0) {
?>
<div class="msg"><?php echo __('no_data_found')?></div>
<?php } else {?>
<div id="toppage"></div>	
<?php //*********************鏌ヨ鏉′欢********************************?>
<?php //*********************琛ㄦ牸澶?************************************?>
<div>	
    <table class="list">
        <thead>
            <tr>
                <td style="width:6%"><input type="checkbox" onclick="checkAll(this,'container');" value=""/></td>
                <td style="width:6%"><?php echo __('host_ip')?>&nbsp;</td>
                <td style="width:6%">	<?php echo $appCommon->show_order('ID','Ingress ID')?> </td>
                <td style="width:6%">	<?php echo $appCommon->show_order('alias',__('Ingress Name',true))?> </td>
                <!-- 	    		<td style="width:6%">	<?php echo $appCommon->show_order('resource_id',__('ID',true))?> </td>   -->
                <td style="width:6%">	<?php echo $appCommon->show_order('client_id',__('Carriers',true))?> </td>
                <td style="width:6%">	<?php echo $appCommon->show_order('capacity',__('Call limit',true))?> </td>
                <td style="width:6%">	<?php echo $appCommon->show_order('cps_limit',__('CPS Limit',true))?> </td>

                <td style="width:6%">	<?php echo $appCommon->show_order('ip_cnt',__('Trunk Count',true))?> </td>
                <td style="width:6%">	<?php echo $appCommon->show_order('profit_margin',__('Profit Margin',true))?> </td>
                <td style="width:6%"><?php echo __('Used By',true);?> </td>
                <td style="width:6%"><?php __('Routing Plan')?></td>
                <td style="width:6%"><?php __('proto')?></td>
                <td style="width:6%"><?php __('pddtimeout')?></td>
                <td style="width:6%"><?php echo __('Update At',true);?></td>
                <td style="width:6%"><?php echo __('Update By',true);?></td>
                <?php  if ($_SESSION['role_menu']['Routing']['gatewaygroups:view']['model_w']) {?><td style="width:10%" class="last"><?php echo __('action')?></td>
                <?php }?>
            </tr>
        </thead>
        <?php 	$loop = count($mydata); for ($i=0;$i<$loop;$i++) {?>
        <tbody>
            <tr class="row-<?php echo $i%2+1;?>">
                <td style="text-align:center"><input type="checkbox" value="<?php echo $mydata[$i][0]['resource_id']?>"/></td>
                <td  align="center"  style="font-weight: bold;"><img   id="image<?php echo $i; ?>"  		onclick="pull('<?php echo $this->webroot?>',this,<?php echo $i;?>)"    class=" jsp_resourceNew_style_1"  src="<?php echo $this->webroot?>images/+.gif"   title="<?php echo __('viewip')?>"/></td >
                <td><?php echo $mydata[$i][0]['resource_id']?></td>
                <td  align="center">

                    <a style="width:90%;display:block" href="<?php echo $this->webroot?>prresource/gatewaygroups/edit_resouce_ingress/<?php echo $mydata[$i][0]['resource_id']?>?<?php echo $appCommon->get_request_str()?>"  title="<?php echo __('edit')?>">
                        <?php echo $mydata[$i][0]['alias']?>	
                    </a>

                </td>
                <!--		   </td>
                                          <td  align="center">
                                                        <?php echo $mydata[$i][0]['resource_id']?>	
                                    </td>-->
                <td  align="center">
                    <a style="width:90%;display:block" href="<?php echo $this->webroot?>clients/index?filter_id=<?php echo $mydata[$i][0]['client_id']?>"  title="<?php echo __('edit')?>">
                        <?php echo $mydata[$i][0]['client_name']?>	
                    </a>
                </td>
                <td  align="center"><?php  if(empty($mydata[$i][0]['capacity'])) {echo "Unlimited";}else{echo  $mydata[$i][0]['capacity']; }?></td>
                <td ><?php  if(empty($mydata[$i][0]['cps_limit'])) {echo "Unlimited";}else{echo  $mydata[$i][0]['cps_limit']; }?></td>

                <td align="center"><?php echo array_keys_value_empty($mydata,$i.'.0.ip_cnt',0)?></td>
                <td><?php echo empty($mydata[$i][0]['profit_margin']) ? '' : $mydata[$i][0]['profit_margin'].'%';?></td>
                <td align="center"><?php  if(empty($mydata[$i][0]['client_id'])) {echo 0;}else{echo  1; }?></td>
                <td><?php echo $mydata[$i][0]['rs_cnt']?></td>

                <td><?php echo $appGetewaygroup->proto($mydata[$i])?></td>
                <td><?php echo $mydata[$i][0]['wait_ringtime180']?></td>
                <td><?php echo $mydata[$i][0]['update_at']?></td>
                <td><?php echo $mydata[$i][0]['update_by']?></td>
                <?php  if ($_SESSION['role_menu']['Routing']['gatewaygroups:view']['model_w']) {?>
                <td align="center"> 
                    <div >
                        <?php if($mydata[$i][0]['active']==1){?>
                        <a onclick="return confirm('Are you sure you would like to inactive the selected <?php echo $mydata[$i][0]['alias']?>!');"  
                           href="<?php echo $this->webroot?>gatewaygroups/dis_able/<?php echo $mydata[$i][0]['resource_id']?>/view_ingress" title="<?php echo __('disable')?>">
                            <img  title="<?php echo 'Click to inactive';?>" src="<?php echo $this->webroot?>images/flag-1.png">
                        </a>
                        <?php }else{?>
                        <a onclick="return confirm('Are you sure you would like to active the selected <?php echo $mydata[$i][0]['alias']?>!');"  
                           href="<?php echo $this->webroot?>gatewaygroups/active/<?php echo $mydata[$i][0]['resource_id']?>/view_ingress" title="<?php echo __('disable')?>">
                            <img  title="<?php echo 'Click to active';?>" src="<?php echo $this->webroot?>images/flag-0.png"><?php }?>
                        </a>

                        <a href="<?php echo $this->webroot?>prresource/gatewaygroups/edit_resouce_ingress/<?php echo $mydata[$i][0]['resource_id']?>?<?php echo $this->params['getUrl']?>"  title="<?php echo __('edit')?>">
                            <img  title="<?php echo __('edit')?>" src="<?php echo $this->webroot?>images/editicon.gif" > </a>
                        <a onclick="return confirm('Are you sure to delete , ingress trunk <?php echo $mydata[$i][0]['alias']?>	  ');"
                           href="<?php echo $this->webroot?>prresource/gatewaygroups/del/<?php echo $mydata[$i][0]['resource_id']?>/view_ingress?<?php echo $this->params['getUrl']?>" title="<?php echo __('del')?>">
                            <img  title="<?php echo __('del')?>"  src="<?php echo $this->webroot?>images/delete.png" >
                        </a>
                    </div>
                </td>
                <?php }?>
            </tr>
            <tr style="height:0px">
                <td colspan=20>
                    <div id="ipInfo<?php echo $i?>" class=" jsp_resourceNew_style_2">
                        <table>
                            <tr>
                                <td>
                                    <div id="ipTable<?php echo $i?>" class=" jsp_resourceNew_style_3"></div>
                                    <script type="text/javascript">
                                        createTable('<?php echo $this->webroot?>',<?php echo $mydata[$i][0]['resource_id']?>,<?php echo $i?>);
                                    </script>
                                </td>
                            </tr>
                        </table>
                    </div>
                </td>
            </tr>
        </tbody>
        <?php }?>
    </table>
</div>
<?php //*********************琛ㄦ牸澶?************************************?>		
<div id="tmppage"><?php echo $this->element('page');?></div>
<?php }?>