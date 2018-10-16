<script src="<?php echo $this->webroot?>js/ajaxTable.js" type="text/javascript"></script>

<?php 			
    $mydata =$p->getDataArray();
    $loop = count($mydata); 
    
    ?>
<div id="title">
    <h1><?php __('Monitoring')?>&gt;&gt;<?php echo __('Rule')?></h1>  
    <ul id="title-search">
        <li>
            <?php //Pr($searchkey);    //****************************模糊搜索**************************?>
            <form  action="<?php echo $this->webroot;?>alerts/rule"  method="get">
                <input type="text" id="search-_q_j" class="in-search default-value input in-text defaultText" title="<?php echo __('search')?>..." value="<?php if(isset($searchkey)){echo $searchkey;}else{ echo __('pleaseinputkey');}?>"  onclick="this.value=''" name="searchkey">
            </form>
        </li>
    </ul>
    <ul id="title-menu">
        <?php if (isset($edit_return)) {?>
        <li>
            <a  class="link_back"href="<?php echo $this->webroot;?>alerts/rule">
                <img width="16" height="16"  alt="" src="<?php echo $this->webroot?>images/icon_back_white.png">
                &nbsp;<?php echo __('goback')?>
            </a>
        </li>
        <?php }?>
        <?php  if ($_SESSION['role_menu']['Monitoring']['alerts:rule']['model_w']) {?>
        <li>
            <a  class="link_btn"title="<?php echo __('creataction')?>"  href="<?php echo $this->webroot?>alerts/add_rule">
                <img width="16" height="16" alt="" src="<?php echo $this->webroot?>images/add.png"><?php echo __('createnew')?>
            </a>
        </li>
        <?php if ($loop > 0) : ?>
        <li><a  class="link_btn" rel="popup" href="javascript:void(0)" onclick="deleteAll('<?php echo $this->webroot?>alerts/delete_all');"><img width="16" height="16" alt="" src="<?php echo $this->webroot?>images/delete.png"> <?php echo __('deleteall')?></a></li>
        <li><a class="link_btn" rel="popup" href="javascript:void(0)" onclick="deleteSelected('ruleId','<?php echo $this->webroot?>alerts/delete_selected','rate table');"><img width="16" height="16" alt="" src="<?php echo $this->webroot?>images/delete.png"> <?php echo __('deleteselected')?></a></li>
        <?php endif; ?>
        <?php }?>
    </ul>
</div>
<div id="container">

    <ul class="tabs">
        <li class="active">
            <a href="<?php echo $this->webroot; ?>alerts/rule">
                <img width="16" height="16" src="<?php echo $this->webroot; ?>images/ruler.png">Rule			
            </a>
        </li>
        <li>
            <a href="<?php echo $this->webroot; ?>alerts/action">
                <img width="16" height="16" src="<?php echo $this->webroot; ?>images/action.png">Action			
            </a>
        </li>
        <li>
            <a href="<?php echo $this->webroot; ?>alerts/condition">
                <img width="16" height="16" src="<?php echo $this->webroot; ?>images/condition.png">Condition			
            </a>
        </li>
        <li>
            <a href="<?php echo $this->webroot; ?>alerts/block_ani">
                <img width="16" height="16" src="<?php echo $this->webroot; ?>images/fail.png">Block			
            </a>
        </li>
        <li>
            <a href="<?php echo $this->webroot; ?>alerts/trouble_tickets">
                <img width="16" height="16" src="<?php echo $this->webroot; ?>images/trouble_tickets.png">Trouble Tickets			
            </a>
        </li>
        <li>
            <a href="<?php echo $this->webroot; ?>alerts/trouble_tickets_template">
                <img width="16" height="16" src="<?php echo $this->webroot; ?>images/email.gif">Trouble Tickets Mail Template			
            </a>
        </li>
    </ul>    


    <?php 
    if(empty($mydata)){
    ?>
    <div class="msg"><?php echo __('no_data_found',true);?></div>
    <?php }else{

    ?>
    <div id="toppage"></div>
    <table class="list">

        <thead>
            <tr>

                <td><?php if($_SESSION['login_type']=='1'){?>
                    <input id="selectAll" class="select" type="checkbox" onclick="checkAllOrNot(this,'ruleId');" value=""/>
                    <?php }?></td>
                <td ><?php echo __('Rule Name');?> </td>
                <td > <?php echo __('Condition'); ?>  </td>
                <td > <?php echo __('Action'); ?>  </td>
                <td colspan="4"><?php echo __('Monitor Target'); ?></td>
                <td colspan="2"><?php echo __('Statistics Collection') ?></td>
                <td > <?php echo __('Orig/Term'); ?>  </td>
                <td > <?php echo __('Last Run'); ?>  </td>
                <td> <?php echo __('Next Run'); ?>  </td>
                <td> <?php echo __('Update By'); ?>  </td>
                <td> <?php echo __('Update At'); ?>  </td>
                <?php  if ($_SESSION['role_menu']['Monitoring']['alerts:rule']['model_w']) {?>
                <td class="last"><?php echo __('action',true);?></td>
                <?php }?>

            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td><?php echo __('Trunk'); ?></td>
                <td><?php echo __('Host'); ?></td>
                <td><?php echo __('SRC DNIS'); ?></td>
                <td><?php echo __('DEST DNIS'); ?></td>
                <td><?php echo __('Sample Size'); ?></td>
                <td><?php echo __('Frequency'); ?></td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
        </thead>
        <tbody  id="ruleId">
            <?php 
            $week_arr = array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');		 	
            for ($i=0;$i<$loop;$i++){
            ?>
            <tr class="row-1">

                <td style="text-align:center"><?php if($_SESSION['login_type']=='1'){?><input class="select" type="checkbox" value="<?php echo $mydata[$i][0]['id']?>"/>
                    <?php }?></td>

                <td align="center">

                    <a title="" href="<?php echo $this->webroot?>alerts/add_rule/<?php echo $mydata[$i][0]['id']?>">
                        <?php echo $mydata[$i][0]['name'];?>
                    </a>

                </td>

                <td class="condition" style="cursor:pointer;"><?php echo isset($name_join_arr['condition'][$mydata[$i][0]['alert_condition_id']]) ? $name_join_arr['condition'][$mydata[$i][0]['alert_condition_id']] : ''; ?></td>
                <td class="action" style="cursor:pointer;"><?php echo isset($name_join_arr['action'][$mydata[$i][0]['alert_action_id']]) ? $name_join_arr['action'][$mydata[$i][0]['alert_action_id']] : ''; ?></td>

                <?php
                /*
                <td > <?php echo $mydata[$i][0]['is_origin']?'Orig':'Term'; ?>  </td>
                * 
                */?>
                <td > <?php 

                    if(!empty($name_join_arr['resource'][$mydata[$i][0]['res_id']])){
                    echo $name_join_arr['resource'][$mydata[$i][0]['res_id']];
                    }


                    ?>  </td>

                <td > <?php echo $mydata[$i][0]['host_id']==0?'All':$mydata[$i][0]['host_id']; ?>  </td>
                <?php
                if($mydata[$i][0]['monitor_type'] == 0):
                ?>
                <td> 
                    <?php 
                    if ($mydata[$i][0]['apply_type'] == 0)
                    {
                        echo 'Apply To All';
                    } 
                    else if($mydata[$i][0]['apply_type'] == 1)
                    {
                        echo $mydata[$i][0]['ani']; 
                    }
                    else 
                    {
                    echo '<s>' . $mydata[$i][0]['ani'] . '</s>'; 
                    }
                    ?>  
                </td>
                <td> <?php echo $mydata[$i][0]['dnis']; ?>  </td>
                <?php
                else:
                ?>
                <td> <?php echo $mydata[$i][0]['source_code_name']; ?> </td>
                <td> 
                    <a href="###" detail="<?php echo $mydata[$i][0]['destination_code_name']; ?>">
                        <?php echo substr($mydata[$i][0]['destination_code_name'], 0, 10); ?>
                    </a>
                </td>
                <?php
                endif;
                ?>
                <td><?php echo $mydata[$i][0]['sample_size']; ?>  </td>
                <td> 
                    <?php 
                    if ($mydata[$i][0]['freq_type'] == 1)
                    {
                    echo 'every ' . $mydata[$i][0]['freq_value'] . ' minute(s)'; 
                    }
                    elseif ($mydata[$i][0]['freq_type'] == 2)
                    {
                    $week_time_arr = explode('!', $mydata[$i][0]['weekday_time']);
                    echo 'every '; 
                    $arr_week = explode(',', $week_time_arr[0]);
                    $arr_time = explode(',', $week_time_arr[1]);
                    foreach($arr_week as $key => $item) {
                    echo $week_arr[$item] . '(' . $arr_time[$key] . ')&nbsp;';
                    }
                    }
                    else
                    {
                    echo "Never";
                    }
                    ?>  
                </td>
                <td>
                    <?php
                    if($mydata[$i][0]['is_origin']) {
                    echo 'Orig';
                    } else {
                    echo 'Term';
                    }/* elseif ($mydata[$i][0]['is_origin'] == 2) {
                    echo 'Both';
                    }*/
                    ?>
                </td>

                <td><?php echo $mydata[$i][0]['last_runtime']; ?>  </td>
                <td> <?php echo $mydata[$i][0]['next_runtime']; ?>  </td>
                <td><?php echo $mydata[$i][0]['update_by']; ?> </td>
                <td><?php echo $mydata[$i][0]['update_at']; ?> </td>
                <?php  if ($_SESSION['role_menu']['Monitoring']['alerts:rule']['model_w']) {?>
                <td class="last">
                    <?php 
                    if (Configure::read('system.type') == 2):
                    ?>
                    <a title="Trigger" href="<?php echo $this->webroot?>alerts/rule_trigger/<?php echo  $mydata[$i][0]['id']?>" >
                        <img src="<?php echo $this->webroot?>images/trigger.png"/>
                    </a>
                    <?php endif; ?>
                    <a title="Edit" href="<?php echo $this->webroot?>alerts/add_rule/<?php echo  $mydata[$i][0]['id']?>" >
                        <img src="<?php echo $this->webroot?>images/editicon.gif "/>
                    </a> 
                    <?php if($mydata[$i][0]['status'] == 1): ?>
                    <a title="Stop" href="<?php echo $this->webroot?>alerts/rule_status/<?php echo  $mydata[$i][0]['id']?>/0" >
                        <img static="0" src="<?php echo $this->webroot; ?>images/flag-1.png" title="Click to stop" />
                    </a> 
                    <?php elseif($mydata[$i][0]['status'] == 0): ?>
                    <a title="Resume" href="<?php echo $this->webroot?>alerts/rule_status/<?php echo  $mydata[$i][0]['id']?>/1" >
                        <img static="0" src="<?php echo $this->webroot; ?>images/flag-0.png" title="Click to resume" />
                    </a> 
                    <?php endif; ?>
                    <a href="#" title="Delete"  onclick="dele_tr('<?php echo $this->webroot?>alerts/delete_alert_rule/<?php echo $mydata[$i][0]['id'] ?>',this,'rule <?php echo $mydata[$i][0]['name']?>')   ">
                        <img src="<?php echo $this->webroot?>images/delete.png	"/>

                    </a> 
                </td>
                <?php }?>
            </tr>
            <?php }?>
        </tbody>
    </table>
</div>
<div>
    <div id="tmppage">

        <?php echo $this->element('page');?>



    </div>

    <?php }?>
</div>

<script type="text/javascript">
    $(function() {
        $('.condition').hover(function(e){
            $('.tooltips').remove();
            var xx = e.originalEvent.x || e.originalEvent.layerX || 0;
            var yy = e.originalEvent.y || e.originalEvent.layerY || 0; 
            var condition_id = $.trim($(this).text());
            $.ajax({
                'url' : '<?php echo $this->webroot; ?>alerts/get_condition/' + condition_id,
                'type' : 'GET',
                'dataType' : 'json',
                'success' : function(data) {
                    var $ul = $('<ul />').css({
                        'position': 'absolute',
                        'left' : xx,
                        'top' : yy,
                        'opacity' : 1
                    });
                    $ul.addClass('tooltips');
                    $ul.append('<li>ACD:' + data[0][0]['acd'] + '</li>');
                    $ul.append('<li>ASR:' + data[0][0]['asr'] + '</li>');
                    $ul.append('<li>Margin:' + data[0][0]['margin'] + '</li>');
                    $('body').append($ul);
              
                }
            });
        }, function(e){
            $('.tooltips').remove();
        });
    
    
        $('.action').hover(function(e){
            $('.tooltips').remove();
            var xx = e.originalEvent.x || e.originalEvent.layerX || 0;
            var yy = e.originalEvent.y || e.originalEvent.layerY || 0; 
            var condition_id = $.trim($(this).text());
            $.ajax({
                'url' : '<?php echo $this->webroot; ?>alerts/get_action/' + condition_id,
                'type' : 'GET',
                'dataType' : 'json',
                'success' : function(data) {
                    var $ul = $('<ul />').css({
                        'position': 'absolute',
                        'left' : xx,
                        'top' : yy,
                        'opacity' : 1
                    });
                    $ul.addClass('tooltips');
                    var arr = data[0][0]['content'].split(',');
                    for(var v in arr) {
                        $ul.append('<li>' + arr[v]+ '</li>');
                    }
                    $('body').append($ul);
            
                }
            });
        }, function(e){
            $('.tooltips').remove();
        });
    
    });
</script>