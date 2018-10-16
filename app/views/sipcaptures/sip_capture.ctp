<style type="text/css">
    .form_input {float:left;width:200px;}
</style>
<div id="title">
    <h1><?php __('Tools')?>&gt;&gt;<?php __("SIPCapture")?></h1>
</div>
<div class="container">
    <form method="post" action="" id="sip_capture">
        <table style="width:800px;margin:0px auto;" >				
            <tr><td>
                    <table style="text-align: left; width: 696px;">

                        <tr>
                            <td class="label"><?php echo __('Keyword',true);?>:</td>
                            <td>
                                <?php echo $form->input("keyword",array('class'=>"input in-select form_input " ,'label'=>false,'div'=>false,'default' => 'SIP'));?>					
                            </td>
                        </tr>

                        <tr>
                            <td class="label"><?php echo __('Term DNIS',true);?>:</td>
                            <td>

                                <?php echo $form->input("dnis",array('class'=>"input in-select form_input " ,'style'=>"color: red;",'label'=>false,'div'=>false));?>
                            </td>

                            <td class="label"  style="width: 80px;text-align: right;"><?php echo __('Orig DNIS',true);?>:</td>
                            <td>


                                <?php echo $form->input("ani",array('class'=>"input in-select form_input " ,'style'=>"color: red;",'label'=>false,'div'=>false));?>
                            </td>
                        </tr>	
                        <tr>
                            <td class="label" ><?php echo __('Ingress Trunk',true);?>:</td>
                            <td>
                                <?php echo $form->input("ingress_alias",array('class'=>"input in-select form_input" ,'options' => $appSipCapture->format_server_options($ingress_alias),'label'=>false,'div'=>false,'type' => "select",'empty' => false));?>	    
                            </td>


                            <td class="label" id="sourceport" style="width: 80px;text-align: right;" ><?php echo __('Source Server',true);?>:</td>
                            <td>
                                <select name="data[sourceHostPort]" id="sourceHostPort" class='input in-select form_input'>

                                </select>
                                <!--<?php echo $form->input("source_host",array('class'=>"input in-select form_input " ,'label'=>false,'div'=>false));?>
                                <?php echo $form->input("source_port",array('class'=>"input in-select form_input " ,'label'=>false,'div'=>false));?>
                                -->
                            </td>	

                        </tr>
                        <tr id="desthost">
                            <td class="label" style="width: 80px;text-align: right;"><?php echo __('egress',true);?>:</td>
                            <td>
                                <?php echo $form->input("egress_alias",array('class'=>"input in-select form_input" ,'options' => $appSipCapture->format_server_options($egress_alias),'label'=>false,'div'=>false,'type' => "select",'empty' => false));?>	    
                            </td>

                            <td class="label"  style="width: 80px;text-align: right;">
                                <?php echo __('Target Server',true);?>:
                            </td>
                            <td>
                                <select name="data[destHostPort]" class='input in-select form_input' id="targetHostPort"></select>
                                <!-- <?php echo $form->input("dest_host",array('class'=>"input in-select form_input " ,'label'=>false,'div'=>false));?>
                                 <?php echo $form->input("dest_port",array('class'=>"input in-select form_input " ,'label'=>false,'div'=>false));?>
                                -->
                            </td>

                        </tr>

                        <tr>
                            <td class="label"><?php echo __('Duration',true);?>:</td>
                            <td><?php echo $form->input("remain_time",array('class'=>"input in-select form_input " ,'style'=>"color: red;",'label'=>false,'div'=>false,'default'=>60));?><?php echo __('Seconds',true);?></td>

                            <td class="label"    style="width: 80px;text-align: right;"><?php echo __('Server',true);?>:</td>
                            <td>
                                <?php echo $form->input("server",array('class'=>"input in-select form_input" ,'options' => $appSipCapture->format_server_options($servers),'label'=>false,'div'=>false,'type' => "select",'empty' => false,'selected'=>'all'));?>	    		
                            </td>
                        </tr>
                    </table>
                </td></tr>
            <?php  if ($_SESSION['role_menu']['Tools']['sipcaptures']['model_r']&&$_SESSION['role_menu']['Tools']['sipcaptures']['model_x']) {?>
            <tr><td colspan="2"  style="text-align: center;">			
                    <input id="start_cap" type="button"  value="Start">
                    <input id="stop_cap" type="button" value="Stop">
                </td></tr>
            <?php }?>

            <tr><td>			
                    &nbsp;
                </td></tr>
        </table>
    </form>

    <?php $d = $p->getDataArray();if (count($d) == 0) {?>
    <!--
<div class="msg"  id="msg_div"><?php echo __('no_data_found')?></div>
    -->
    <?php } else {

    ?>
    <div id="toppage"></div>
    <table class="list">
        <!--caption>Sip Capture Information</caption-->
        <thead>
            <tr>
                <td><?php echo $appCommon->show_order('user_name',__(''))?></td>
                <td><?php echo $appCommon->show_order('capture_time',__(''))?></td>
                <td><?php echo $appCommon->show_order('Duration (seconds)',__(''))?></td>
                <td><?php echo $appCommon->show_order('Source IP',__(''))?></td>
                <td><?php echo $appCommon->show_order('Source Port',__(''))?></td>
                <td><?php echo $appCommon->show_order('Target IP',__(''))?></td>
                <td><?php echo $appCommon->show_order('Target Port',__(''))?></td>
                <td><?php echo $appCommon->show_order('Server IP',__(''))?></td>
                <td><?php echo $appCommon->show_order('Server Port',__(''))?></td>
                <td><?php echo $appCommon->show_order('key_word',__(''))?></td>
                <td><?php echo $appCommon->show_order('file_size',__(''))?></td>
                <td><?php echo __('Operation',true);?></td>
            </tr>	
        </thead>
        <tbody>
            <?php $m = new Capture;?>

            <?php 
            $mydata =$p->getDataArray();
            $loop = count($mydata); 

            for ($i=0;$i<$loop;$i++) {?>
            <tr>
                <td><?php echo $mydata[$i][0]['user_name']?></td>
                <td><?php echo $mydata[$i][0]['capture_time']?></td>
                <td><?php echo $mydata[$i][0]['time_val']?></td>
                <td><?php echo $mydata[$i][0]['src_ip']?></td>
                <td><?php echo $mydata[$i][0]['src_port']?></td>
                <td><?php echo $mydata[$i][0]['dest_ip']?></td>
                <td><?php echo $mydata[$i][0]['dest_port']?></td>
                <td><?php echo $mydata[$i][0]['server_ip']?></td>
                <td><?php echo $mydata[$i][0]['server_port']?></td>
                <td><?php echo $mydata[$i][0]['key_word']?></td>
                <td>
                    <?php 
                    
                    $file_path = @$appSipCapture->find_file($mydata[$i][0]['server_ip'], $mydata[$i][0]['server_port'], $mydata[$i][0]['file_name']);
                    
                    $mydata[$i][0]['file_size'] = is_file($file_path) ? @filesize($file_path) : 0 ;
                    
                    echo $appCommon->to_readable_size($mydata[$i][0]['file_size'])
                    ?>
                </td>
                <td>
                    <?php if($m->is_ready_to_view($mydata[$i][0]['file_size'],$mydata[$i][0]['flag'])):?>

                    <?php  if ($_SESSION['role_menu']['Tools']['sipcaptures']['model_r']&&$_SESSION['role_menu']['Tools']['sipcaptures']['model_x']) {?>
                    <a href="<?php echo $this->webroot?>sipcaptures/download/<?php echo $mydata[$i][0]['capture_id']?>">Download</a>
                    <?php }?> 
                    | 

                    <?php  if ($_SESSION['role_menu']['Tools']['sipcaptures']['model_x']) {?>
                    <a href="<?php echo $this->webroot?>sipcaptures/view/<?php echo $mydata[$i][0]['capture_id']?>" target="_blank"><?php echo __('View',true);?></a>
                    <?php }?>
                    <?php else:?>
                    <?php echo __('Not Available',true);?>
                    <?php endif;?>

                    <?php if($m->is_ready_to_stop($mydata[$i][0]['flag'])):?>
                    <?php  if ($_SESSION['role_menu']['Tools']['sipcaptures']['model_x']) {?>
                    <span  id="stop_<?php echo $mydata[$i][0]['capture_id']?>" > 
                        <a href="#" onclick="return stopCapture('<?php echo $mydata[$i][0]['capture_id']?>')" target="_blank"><?php echo __('Stop',true);?></a></span>
                    <?php }?>
                    <?php endif;?> 
                </td>
            </tr>
            <?php }?>
        </tbody>	
    </table>
    <div id="tmppage">
        <?php echo $this->element('page');?>
    </div>

    <?php }?>
</div>
<script type="text/javascript">
    (function(startButtonId,stopButtonId,activeButtonId){
        var _startButton = $(startButtonId);
        var _stopButton = $(stopButtonId);
        var _activeButton = activeButtonId;
        var _timeoutHander = null;
        var _remain_time = $('#remain_time');
        var _doStop = false;
        var doDecrementTime = function(){
            
            var second = parseInt(_remain_time.val());		
            if(second > 0){
                _remain_time.val(--second);
                _timeoutHander = setTimeout(doDecrementTime,1000);
            }else{
                doStopCap();
            }
        }
        var doStartCap = function (){
            disableStart();
            $.post('<?php echo Router::url(array('plugin' => null, 'controller' => $this->params['controller'], 'action' => 'start'))?>',$('#sip_capture').serialize(),
            function(data){
                var second = parseInt(_remain_time.val());		
                if(second <= 0 || second > 18000){
                    _remain_time.val(18000);
                }		
                _timeoutHander = setTimeout(doDecrementTime,1000);
            }
        );
		
        }
        var doStopCap = function(){
            //		disableStop();		
            if(_timeoutHander){
                clearTimeout(_timeoutHander);
            }
            if(!_doStop){
                _doStop = true;
                loading();
                $.post('<?php echo Router::url(array('plugin' => null, 'controller' => $this->params['controller'], 'action' => 'stop'))?>',{},
                function(data){
                    // 留点时间给后台写数据。 至少一秒
                    setTimeout(function(){					
                        window.location = window.location;
                    },2000);
                }
            );
            }
        }

        var disableStop = function(){
            _startButton.show();
            _stopButton.hide();;
            _activeButton == startButtonId;
        }

        var disableStart = function(){
            _startButton.hide();
            _stopButton.show();		
            _activeButton == stopButtonId
        }
	
        if(_activeButton == startButtonId){
            disableStop();
        }
        if(_activeButton == stopButtonId){
            disableStart();
        }
        _startButton.bind('click',doStartCap);
        _stopButton.bind('click',doStopCap);
        
        
        
        
        //alert($("#ingress_alias option:selected").val());
        //初始化Source Server数据
        $.post('<?php echo Router::url(array('plugin' => null, 'controller' => $this->params['controller'], 'action' => 'get_host_post'))?>',
        {ingress:$("#ingress_alias option:selected").val()},
        function (data){
            setDataToSelect(data,"sourceHostPort");
        } 
    );
        
        //给ingress 添加ajax事件
        $("#ingress_alias").change(function (){
            
            $.post('<?php echo Router::url(array('plugin' => null, 'controller' => $this->params['controller'], 'action' => 'get_host_post'))?>',
            {ingress:$("#ingress_alias option:selected").val()},
            function (data){
                setDataToSelect(data,"sourceHostPort");
            } 
        );
        });
        
        //初始化Target Server数据
        $.post('<?php echo Router::url(array('plugin' => null, 'controller' => $this->params['controller'], 'action' => 'get_host_post'))?>',
        {ingress:$("#egress_alias option:selected").val()},
        function (data){
            setDataToSelect(data,"targetHostPort");
        } 
    );
                
        //给egress 添加ajax事件
        $("#egress_alias").change(function (){
            $.post('<?php echo Router::url(array('plugin' => null, 'controller' => $this->params['controller'], 'action' => 'get_host_post'))?>',
            {ingress:$("#egress_alias option:selected").val()},
            function (data){
                setDataToSelect(data,"targetHostPort");
            } 
        );
            
        });
        
        //给select换值
        function setDataToSelect(data,obj){
            $("#" + obj).empty();
            var json_data = eval(data);
            $.each(json_data, function(index,content){
                if (content[0].ip == 'All') 
                    $("#" + obj).append("<option value='0:0'>All</option>");
                else
                    $("#" + obj).append("<option value='"+content[0].ip+":"+content[0].port+"'>"+content[0].ip+":"+content[0].port+"</option>");
            });
            
        }
        
        
        
        
        
        
		
    })('#start_cap','#stop_cap','#start_cap');

    var stopCapture = function(id){
        jQuery.post('<?php echo $this->webroot?>sipcaptures/stop/'+id,function(data){

            alert(data);


        });
        jQuery('#stop_'+id).remove();
        window.location = window.location;
        return false;
    }
</script>
