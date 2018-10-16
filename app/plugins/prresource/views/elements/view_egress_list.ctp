<?php if(!isset($w)){ $w = $session->read('writable');}?>
<?php 
$mydata =$p->getDataArray();	$loop = count($mydata); 
if (count($loop) == 0) {?>
<div class="msg"><?php echo __('no_data_found')?></div>
<?php } else {?>
<div id="toppage"></div>	
<?php //*********************鏌ヨ鏉′欢********************************?>
<?php //*********************琛ㄦ牸澶?************************************?>
<div>	
    <table id="mytable" class="list">
        <thead>
            <tr>
                <?php if($w){?>
                <td style="width:6%"><input type="checkbox" onclick="checkAll(this,'mytable');" value=""/></td>
                <?php }?>
                <td style="width:6%">
                    <?php echo __('host_ip')?>&nbsp;
                </td>
                <td style="width:6%">	<?php echo $appCommon->show_order('ID','Egress ID')?> </td>
                <td style="width:6%">	<?php echo $appCommon->show_order('alias','Egress Name')?> </td>
                <!--    		<td style="width:6%">	<?php echo $appCommon->show_order('resource_id','ID')?> </td>-->
                <td style="width:6%">	<?php echo $appCommon->show_order('client_id','Carriers')?> </td>
                <td style="width:6%">	<?php echo $appCommon->show_order('capacity','Call limit')?> </td>
                <td style="width:6%">	<?php echo $appCommon->show_order('cps_limit','CPS Limit')?> </td>

<!--                <td style="width:6%">	<?php echo $appCommon->show_order('ip_cnt','Trunk Count')?> </td>-->
                <td style="width:6%">	<?php __('Usage Count'); ?>
                <td style="width:6%"><?php __('rateTable')?></td>
                <td style="width:6%"><?php __('proto')?></td>
                <td style="width:6%"><?php __('pddtimeout')?></td>
                <td style="width:6%">Update At</td>
                <td style="width:6%">Update By</td>
                <?php  if ($_SESSION['role_menu']['Routing']['gatewaygroups:view']['model_w']) {?>
                <?php if($w){?>
                <td class="last" style="width:10%"><?php echo __('action')?></td>
                <?php }?>
                <?php }?>
            </tr>
        </thead>
        <tbody>
            <?php 	for ($i=0;$i<$loop;$i++) {?>

            <tr style="<?php if($mydata[$i][0]['active'] == 0) echo 'background:#ccc;';?>">
                <?php if($w){?>
                <td style="text-align:center"><input type="checkbox" value="<?php echo $mydata[$i][0]['resource_id']?>"/></td>
                <?php }?>
                
                <td  align="center"  style="font-weight: bold;">
                    <img   id="image<?php echo $i; ?>"  		onclick="pull('<?php echo $this->webroot?>',this,<?php echo $i;?>)"    class=" jsp_resourceNew_style_1"  src="<?php echo $this->webroot?>images/+.gif"   title="<?php echo __('viewip')?>"/>
                </td>
                <td><?php echo $mydata[$i][0]['resource_id']?></td>
                <td  align="center">

                    <a  style="width:80%;display:block" href="<?php echo $this->webroot?>prresource/gatewaygroups/edit_resouce_egress/<?php echo $mydata[$i][0]['resource_id']?>?<?php echo $appCommon->get_request_str()?>"  class="link_width" title="<?php echo __('edit')?>">
                        <?php echo $mydata[$i][0]['alias']?>	
                    </a>


                </td>
                <!--		  			 <td  align="center">
                                                                        <?php echo $mydata[$i][0]['resource_id']?>	
                                                 </td>-->
                <td  align="center">
                    <a  style="width:90%;display:block"href="<?php echo $this->webroot?>clients/index?filter_id=<?php echo $mydata[$i][0]['client_id']?>" class="link_width" title="<?php echo __('edit')?>">
                        <?php echo $mydata[$i][0]['client_name']?>	
                    </a>
                </td>
                <td  align="center"><?php  if(empty($mydata[$i][0]['capacity'])) {echo "Unlimited";}else{echo  $mydata[$i][0]['capacity']; }?></td>
                <td ><?php  if(empty($mydata[$i][0]['cps_limit'])) {echo "Unlimited";}else{echo  $mydata[$i][0]['cps_limit']; }?></td>

<!--                <td align="center"><?php echo array_keys_value_empty($mydata,$i.'.0.ip_cnt',0)?></td>-->
                <td align="center">
                    <a href="<?php echo $this->webroot ?>dynamicroutes/view?resource_id=<?php echo $mydata[$i][0]['resource_id']?>" title="Dynamic Usage Count"> 
                    <?php echo array_keys_value_empty($mydata,$i.'.0.dynamic_count',0)?>
                    </a>
                    /
                    <a href="<?php echo $this->webroot ?>products/product_list?resource_id=<?php echo $mydata[$i][0]['resource_id']?>" title="Product Usage Count"> 
                    <?php echo array_keys_value_empty($mydata,$i.'.0.static_count',0)?>
                    </a>
                </td>

                <td><a style="width:90%;display:block" href="<?php echo $this->webroot?>rates/rates_list?id=<?php echo $mydata[$i][0]['rate_table_id']?>" class="link_width"><?php echo $mydata[$i][0]['rate_table_name']?></a></td>
                <td><?php echo $appGetewaygroup->proto($mydata[$i])?></td>
                <td><?php echo $mydata[$i][0]['wait_ringtime180']?></td>
                <td><?php echo $mydata[$i][0]['update_at']?></td>
                <td><?php echo $mydata[$i][0]['update_by']?></td>
                <?php  if ($_SESSION['role_menu']['Routing']['gatewaygroups:view']['model_w']) {?>

                <td align="center" style="text-align:center">
                    <div  class="action_icons"> 


                        <?php if($mydata[$i][0]['active']==1){?>
                        <a onclick="return confirm('Are you sure you would like to inactive the selected <?php echo $mydata[$i][0]['alias']?>!');"  
                           href="<?php echo $this->webroot?>gatewaygroups/dis_able/<?php echo $mydata[$i][0]['resource_id']?>/view_egress" title="<?php echo __('disable')?>">
                            <img  title="<?php echo 'Click to inactive';?>" src="<?php echo $this->webroot?>images/flag-1.png">
                        </a>
                        <?php }else{?>
                        <a  onclick="return confirm('Are you sure you would like to active the selected <?php echo $mydata[$i][0]['alias']?>!');"  
                            href="<?php echo $this->webroot?>gatewaygroups/active/<?php echo $mydata[$i][0]['resource_id']?>/view_egress" title="<?php echo __('disable')?>">
                            <img  title="<?php echo 'Click to active';?>" src="<?php echo $this->webroot?>images/flag-0.png"><?php }?>
                        </a>


                        <a   target="_blank" href="<?php echo $this->webroot?>prresource/gatewaygroups/edit_resouce_egress/<?php echo $mydata[$i][0]['resource_id']?>?<?php echo $this->params['getUrl']?>"  title="<?php echo __('edit')?>">
                            <img  title="<?php echo __('edit')?>"   src="<?php echo $this->webroot?>images/editicon.gif" > 
                        </a>
                        <a  onclick="return confirm('Are you sure to delete ,egress trunk  <?php echo $mydata[$i][0]['alias']?>		 ?');" href="<?php echo $this->webroot?>prresource/gatewaygroups/del/<?php echo $mydata[$i][0]['resource_id']?>/view_egress?<?php echo $this->params['getUrl']?>" title="<?php echo __('del')?>">
                            <img  title="<?php echo __('del')?>" 
                                  src="<?php echo $this->webroot?>images/delete.png" >
                        </a>
                    </div>    
                </td>
                <?php }?>
            </tr>
            <tr style="height:0px;border:0px;">
                <td colspan='20'>
                    <div id="ipInfo<?php echo $i?>" class=" jsp_resourceNew_style_2" >
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
            <?php }?></tbody>
    </table>
</div>
<div id="tmppage">
    <?php echo $this->element('page');?>
</div>
<?php }?>
