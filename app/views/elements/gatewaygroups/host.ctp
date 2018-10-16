<?php  if ($_SESSION['role_menu']['Routing']['gatewaygroups:view']['model_w']) {?><a id="addHost" onclick="return false;" href="#"> <img  src="<?php echo $this->webroot?>images/add.png"> Add Host </a>
<?php }?>
<table class="list list-form"  id="host_table">
    <thead>
        <tr>
            <td style="width:35%;"><span rel="helptip" class="helptip" id="ht-100002" title="Name of an account in JeraSoft yht system (for statistics and reports)"><?php echo __('ip',true);?></span><!-- <span class="tooltip" id="ht-100002-tooltip"</span>--></td>
            <?php if($$hel->isIngress(@$type)){?>
            <td style="width:35%;"><span rel="helptip" class="helptip" id="ht-100003" title="Gateway IP-adress. You can specify multiple adresses by dividing them with ";""><?php echo __('Netmask',true);?></span><!-- <span class="tooltip" id="ht-100003-tooltip">--></span></td>
            <?php }?>
            <td style="width:35%;"><span rel="helptip" class="helptip" id="ht-100004" title="Technical prefix, that is used to identify users, when multiple clients use same gateway"><?php echo __('port',true);?></span><!-- <span class="tooltip" id="ht-100004-tooltip"></span>--></td>
            <?php  if ($_SESSION['role_menu']['Routing']['gatewaygroups:view']['model_w']) {?>
            <td  style="width: 80px;" class="last"><?php echo __('action',true);?></td>
            <?php }?>
        </tr>
    </thead>
    <tbody class="rows" id="rows-ip">
        <?php 
        if(isset($ips)){
        for ($i = 0; $i < count($ips['ip']); $i++) {

        ?>
        <tr>
            <td class="value"><input type="text" style="width: 200px;" onkeyup="value=value.replace(/[^\w\.\/]/ig,'')" name="accounts[ip][]" class="host_ip" id="ip" value="<?php echo $ips['ip'][$i];?>"></td>
            <?php if($$hel->isIngress(@$type)){?>
            <td><?php echo $xform->input('need_register.',Array('value'=>$ips['need_register'][$i],'name'=>'accounts[need_register][]','options'=>array('32'=>32,'31'=>31,'30'=>30,'29'=>29,'28'=>28,'27'=>27,'26'=>26,'25'=>25,'24'=>24),'style'=>'width:200px'))?></td>
            <?php }?>
            <td class="value"><input type="text" name="accounts[port][]" id="port"  class="host_port"  maxlength="16" value="<?php echo $ips['need_register'][$i]; ?>"></td>

            <td style="width: 55px;" class="value last"><a href="#" title="delete" rel="delete" onclick="$(this).closest('tr').remove();"> <img style="margin-top:5px;" width="16" height="16" src="<?php echo $this->webroot?>images/delete.png"> </a></td>
        </tr>
        <?php } ?>
        <?php } ?>
        <?php if(isset($hosts) && is_array($hosts)){?>
        <?php foreach($hosts as $host){?>
        <?php
        if( array_keys_value($host,'0.ip')){
        $ip=array_keys_value($host,'0.ip');
        }else{ 
        $ip=array_keys_value($host,'0.fqdn');
        }
        ?>
        <?php $ip=split("/",$ip)?>
        <tr>
            <td class="value"><input type="text" onkeyup="value=value.replace(/[^\w\.\/]/ig,'')" style="width: 200px;" name="accounts[ip][]" id="ip" value="<?php echo array_keys_value($ip,0)?>"></td>
            <?php if($$hel->isIngress(@$type)){?>
            <td><?php echo $xform->input('need_register.',Array('value'=>array_keys_value($ip,1),'name'=>'accounts[need_register][]','options'=>array('32'=>32,'31'=>31,'30'=>30,'29'=>29,'28'=>28,'27'=>27,'26'=>26,'25'=>25,'24'=>24),'style'=>'width:200px'))?></td>
            <?php }?>
            <td class="value"><input type="text" name="accounts[port][]" id="port" maxlength="16" value="<?php echo array_keys_value($host,'0.port')?>"></td>
            <?php  if ($_SESSION['role_menu']['Routing']['gatewaygroups:view']['model_w']) {?>
            <td class="last">

                <input type="hidden" name ="accounts[resource_ip_id][]" value="<?php echo array_keys_value($host,'0.resource_ip_id')?>" />
                <a title="Capacity" href="<?php echo $this->webroot?>gatewaygroups/add_host_time/<?php echo array_keys_value($host,'0.resource_ip_id')?>/<?php echo array_keys_value($host,'0.fqdn')?>"> <img width="16" height="16" src="<?php echo $this->webroot?>images/bDrPlans.png"> </a>
                &nbsp;&nbsp;
                <a href="###" title="delete" rel="delete" onclick="$(this).closest('tr').remove();"> <img style="margin-top:5px;" width="16" height="16" src="<?php echo $this->webroot?>images/delete.png"> </a></td><?php }?>
        </tr>
        <?php }?>
        <?php }?>
        <tr id="mb" style="">
            <td class="value"><input type="text" onkeyup="value=value.replace(/[^\w\.\/]/ig,'')" style="width: 200px;" name="accounts[ip][]" class="host_ip" id="ip"></td>
            <?php if($$hel->isIngress(@$type)){?>
            <td><?php echo $xform->input('need_register.',Array('name'=>'accounts[need_register][]','options'=>array('32'=>32,'31'=>31,'30'=>30,'29'=>29,'28'=>28,'27'=>27,'26'=>26,'25'=>25,'24'=>24),'style'=>'width:200px'))?></td>
            <?php }?>
            <td class="value"><input type="text" name="accounts[port][]" id="port" class="host_port" maxlength="16"></td>
            <?php  if ($_SESSION['role_menu']['Routing']['gatewaygroups:view']['model_w']) {?> 
            <td style="width: 55px; text-align:center;" class="last"><a href="#" title="delete" rel="delete" onclick="$(this).closest('tr').remove();"> <img width="16" height="16" src="<?php echo $this->webroot?>images/delete.png"> </a></td>
            <?php }?>
        </tr>
    </tbody>
</table>
<script type="text/javascript">
    jQuery(document).ready(function(){
        var mb=jQuery('#mb').remove();
        jQuery('#addHost').click(function(){
            mb.clone(true).appendTo('#host_table tbody');
            return false;
        });
        jQuery('input[id=port]').xkeyvalidate({type:'Num'});
        jQuery('form[id^=Gatewaygroup]').submit(function(){
            var re=true;
            if(jQuery('input[id=alias]:last').val()==''){
                jQuery('input[id=alias]:last').jGrowlError('Name is required');
                re=false;
            }
                                                
            if(parseInt(jQuery('#wait_ringtime180').val()) != 0) {                                    
                if(parseInt(jQuery('#wait_ringtime180').val()) < 1000 || parseInt(jQuery('#wait_ringtime180').val()) > 60000) {
                    jQuery(this).addClass('invalid');
                    jQuery(this).jGrowlError('PDD Timeout must a number less than 60000 and greater than 1000!');
                    re =false;
                }
            }
            jQuery('input[id=port]').each(function(){
                if(jQuery(this).val()!='' && isNaN(jQuery(this).val())){
                    jQuery(this).jGrowlError('Port,must be whole number! ');
                    re=false;
                }
            });
            jQuery('input[id=ip]').each(function(){

                var arr = jQuery(this).val().split('.');
  
                for(var i=0;i<arr.length;i++) {
                    if(isNaN(arr[i])||arr[i] > 255||((arr.length-1)!=3)) {
                        jQuery(this).jGrowlError('Invalid IP Address.');
                        re = false;
                        break;
                    }
                }
                
                /*
                                if(jQuery(this).val()!=''||!jQuery(this).val()){
                    if(!/^(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])$/.test(jQuery(this).val())){
                           jQuery(this).jGrowlError('IPs/FQDN must be a valid format ');
                           re = false;  
                        } 
                }
                 */
                if(jQuery(this).val().indexOf('.')==-1 || jQuery(this).val().indexOf('/')!=-1){
                    jQuery(this).jGrowlError('Invalid IP Address.');
                    re=false;
                }
            });
            /*
            var arr= new Array();
            jQuery('#host_table tr').each(function(){
                for(var i in arr){
                    if (arr[i].ip)
                    {
                        if(jQuery(this).find('input.host_ip').val()==arr[i].ip && jQuery(this).find('input.host_port').val()==arr[i].port){
                            jQuery.jGrowlError('IP Address '+arr[i].ip+" must be unique!");
                            re=false;
                            return;
                        }
                    }
                }
                if(jQuery(this).find('input.host_ip').val()!=''){
                    arr.push({ip:jQuery(this).find('input.host_ip').val(),port:jQuery(this).find('input.host_port').val()});
                }
            });
            */
            if(re){
                var arr=Array();
                jQuery('#host_table tr').each(function(){
                    if(jQuery(this).find('#ip').size()>0){
                        arr.push(jQuery(this).find('#ip').val()+'/'+jQuery(this).find('#GatewaygroupNeedRegister').val());
                    }
                });
                arr=arr.join(',');
                var data=jQuery.ajaxData("<?php echo $this->webroot?>ajaxvalidates/ip4r/noDomain?ip="+arr);
                data='['+data+']';
                data=eval(data);
                data=data[0];
                for(var i in data){
                    if(data[i]==false){
                        var eq=parseInt(i)+1;
                        jQuery('#host_table tr').eq(eq).find('#ip,#GatewaygroupNeedRegister').jGrowlError(jQuery('#host_table tr').eq(eq).find('#ip').val()+'/'+jQuery('#host_table tr').eq(eq).find('#GatewaygroupNeedRegister').val()+' is not ip!');
                        re=false;
                    }
                }
            }
            return re;
        });
    });
</script>