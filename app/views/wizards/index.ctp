<style type="text/css">
    td {
        padding:5px;
    }
    .select_mul {width:300px; height:200px;}
</style>


<div id="title">
    <h1><?php echo __('Management'); ?> &gt;&gt; <?php echo __('Wizard'); ?></h1>
</div>

<div id="container">
    
    <div style="margin:0 auto;">
    
    <form method="post" id="myform">
        <table class="list">
            <tr>
                <td>
                    <?php __('Carrier Name') ?>
                </td>
                <td>
                    <select name="client_type" id="client_type">
                        <option value="0" selected="selected">New Carrier</option>
                        <option value="1">Existing Carrier</option>
                    </select>
                </td>
                <td>
                    <input id="client_name" type="text" name="client_name" />
                    <select id="client" name="client" style="display:none;">
                        <?php foreach($clients as $client): ?>
                        <option value="<?php echo $client[0]['client_id'] ?>"><?php echo $client[0]['name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
                <td></td>
            </tr>
            
            <tr id="credit_limit_tr">
                <td>
                    <?php __('Credit Limit') ?>
                </td>
                <td>
                    <input type="text" id="credit_limit" name="credit_limit" />
                </td>
                <td colspan="2"></td>
            </tr>
            
            <tr>
                <td>
                    <?php __('Trunk Name') ?>
                </td>
                <td>
                    <select name="trunk_type" id="trunk_type">
                        <option value="0">Ingress</option>
                        <option value="1">Egress</option>
                    </select>
                </td>
                <td>
                    <input type="text" name="trunk_name" id="trunk_name" />
                </td>
                <td></td>
            </tr>
            
            <tr>
                <td><?php __('CPS Limit') ?></td>
                <td>
                    <input type="text" name="cps_limit" id="cps_limit" />
                </td>
                <td><?php __('Call Limit') ?></td>
                <td>
                    <input type="text" name="call_limit" id="call_limit" />
                </td>
            </tr>

            <tr>
                <td colspan="2">
                    <?php __('IP List'); ?>
                    <a href="###" id="add_ip_port" title="Add New...">
                        <img src="<?php echo $this->webroot; ?>images/add.png" />
                    </a>
                </td>
                <td colspan="2"><?php __('Codecs'); ?></td>
            </tr>    
            
            <tr>
                <td colspan="2" style="vertical-align:top;">
                    <!--<textarea name="ip_lists" class="select_mul" style="width:200px;"></textarea>-->
                    <table id="ip_table">
                        <tr>
                            <td>IP</td><td>Port</td><td></td>
                        </tr>
                        <tr>
                            <td>
                                <input type="text" name="ips[]" />
                            </td>
                            <td>
                                <input type="text" name="ports[]" />
                            </td>
                            <td>
                                <a title="delete" href="###" class="delete_ip_port">
                                    <img width="16" height="16" src="<?php echo $this->webroot; ?>images/delete.png"> 
                                </a>
                            </td>
                        </tr>
                    </table>
                </td>
                <td colspan="2">
                    <select name="codecs[]" multiple="multiple" class="select_mul">
                        <?php foreach($codecses as $codecs): ?>
                        <option value="<?php echo $codecs[0]['id'] ?>"><?php echo $codecs[0]['name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                    <a title="Press CTR and click the left mouse button to choose more" style="cursor:help;">
                        <img src="<?php echo $this->webroot; ?>images/help.png" />
                    </a>
                </td>
            </tr>
            
            <tr id="host_routing_tr">
                <td><?php __('Host Routing'); ?></td>
                <td>
                    <select name="host_routing">
                        <option value="2">Round Robin</option>
                        <option value="1">Top Down</option>
                    </select>
                </td>
                <td colspan="2"></td>
            </tr>
            
            <tr id="rate_table_tr">
                <td>
                    <?php __('Rate Table'); ?>
                </td>
                <td>
                    <select id="rate_table" name="rate_table">
                        
                    </select>
                    <a href="<?php echo $this->webroot ?>rates/create_ratetable" target="_blank" id="add_ratetable" title="Add New...">
                        <img src="<?php echo $this->webroot; ?>images/add.png" />
                    </a>
                    <a href="###" target="_blank" id="refresh_ratetable" title="Refresh...">
                        <img src="<?php echo $this->webroot; ?>images/refresh.png" />
                    </a>
                </td>
                <td colspan="2"></td>
            </tr>

            <tr id="routing_type_tr">
                <td><?php __('Routing'); ?></td>
                <td>
                    <select name="routing_type" id="routing_type">
                        <option value="0">Static Routing</option>
                        <option value="1">Dynamic Routing</option>
                    </select>
                </td>
                <td colspan="2"></td>
            </tr>
            
            <tr id="egress_trunks_tr">
                <td>
                    <?php __('Egress Trunk List'); ?>
                    <a href="<?php echo $this->webroot ?>prresource/gatewaygroups/add_resouce_egress" target="_blank" id="add_egress_trunk" title="Add New...">
                        <img src="<?php echo $this->webroot; ?>images/add.png" />
                    </a>
                    <a href="###" target="_blank" id="refresh_egress_trunk" title="Refresh...">
                        <img src="<?php echo $this->webroot; ?>images/refresh.png" />
                    </a>
                </td>
                <td>
                    <select style="float:left;display:block;" multiple="multiple" name="egress_trunks_wait" id="egress_trunks_wait" class="select_mul">
                        
                    </select>
                    <p style="float:left;margin:60px 0 0 30px;">
                        <a href="###" onclick="DoAdd();"><img src="<?php echo $this->webroot; ?>images/arrow_right.png"></a>
                        <br>
                        <br>
                        <a href="###" onclick="DoDel();"><img src="<?php echo $this->webroot; ?>images/arrow_left.png"></a>
                    </p>
                </td>
                <td>
                    <select style="float:left;display:block;" multiple="multiple" name="egress_trunks[]" id="egress_trunks" class="select_mul">
                        
                    </select>
                    <p style="float:left;margin:60px 0 0 30px;">
                        <a href="###" onclick="moveOption('egress_trunks','up');"><img src="<?php echo $this->webroot; ?>images/arrow_up.png"></a>
                        <br>
                        <br>
                        <a href="###" onclick="moveOption('egress_trunks','down');"><img src="<?php echo $this->webroot; ?>images/arrow_down.png"></a>
                    </p>
                </td>
                <td></td>
            </tr>
            
            <tr style="text-align:center;">
                <td colspan="4">
                    <input type="button" id="subbtn" value="Submit">
                </td>
            </tr>
    
        </table>
    
    </form>
        
    </div>
    
</div>

<script type="text/javascript" src="<?php echo $this->webroot ?>js/codecs.js"></script>
<script type="text/javascript">
$(function() {
    /* variables  */
    var $rate_table = $('#rate_table');
    var $egress_trunks_wait = $('#egress_trunks_wait');
    var $credit_limit_tr = $('#credit_limit_tr');
    var $client_name = $('#client_name');
    var $client = $('#client');
    var $refresh_ratetable = $('#refresh_ratetable');
    var $refresh_egress_trunk = $('#refresh_egress_trunk');
    var $routing_type_tr = $('#routing_type_tr');
    var $trunk_type = $('#trunk_type');
    var $egress_trunks_tr = $('#egress_trunks_tr');
    var $rate_table_tr = $('#rate_table_tr');
    var $ip_table = $('#ip_table');
    var $host_routing_tr = $('#host_routing_tr');
    var $myform = $('#myform');
    var $client_type = $('#client_type');
    var $trunk_name = $('#trunk_name');
    var $subbtn = $('#subbtn');
    var $credit_limit = $('#credit_limit');
    
    var ip_table_tr = $('tr:eq(1)', $ip_table).remove();
    
    $client_type.change(function() {
        var val = $(this).val();
        if(val == 0) {
            $client_name.show();
            $credit_limit_tr.show();
            $client.hide();
        } else {
            $client_name.hide();
            $credit_limit_tr.hide();
            $client.show();
        }
    });
    
    $client_type.change();
    
    $('#add_ip_port').click(function() {
        ip_table_tr.clone(true).appendTo($ip_table);
    });
    
    
    $('.delete_ip_port').live('click', function() {
        if($('tr', $ip_table).size() > 1) {
            $(this).parent().parent().remove();
        }
    });
    
    
    $('#routing_type').change(function() {
        var val = $(this).val();
        if(val == 0) {
            $host_routing_tr.show();
        } else {
            $host_routing_tr.hide();
        }
    });
    
    $trunk_type.change(function() {
        var val = $(this).val();
        if (val == 0) {
            // ingress
            $egress_trunks_tr.show();
            $routing_type_tr.show();
            $rate_table_tr.show();            
        } else {
            // egress
            $egress_trunks_tr.hide();
            $routing_type_tr.hide();
            $rate_table_tr.hide();
            $host_routing_tr.hide();
        }
    });
    
    $trunk_type.change();
    
    
    $refresh_ratetable.click(function() {
        refresh_ratetable();
            return false;
    });
    
    $refresh_egress_trunk.click(function() {
        refresh_ratetable();
            return false;
    });
    
    $subbtn.click(function() {
        var client_name = '';
        if ($client_type.val() == '0') {
            client_name = $client_name.val();
            if($.trim(client_name) == '') {
                jQuery.jGrowl("The Carrier's name can not empty!",{theme:'jmsg-alert'});
                return false;
            }
        }
        var trunk_name = $trunk_name.val();
        
        var credit_limt = $credit_limit.val();
        
        if($.trim(trunk_name) == '') {
                jQuery.jGrowl("Trunk's name can not empty!",{theme:'jmsg-alert'});
                return false;
        }
        
       
        if(jQuery('#credit_limit').val() != '') {
            if(! /\d+|\./.test(jQuery('#credit_limit').val())){
                jQuery('#credit_limit').addClass('invalid');
                jQuery.jGrowl('Credit Limit must contain numeric characters only!',{theme:'jmsg-error'});
                return false;
            }
        }
        if(jQuery('#cps_limit').val() != '') {
            if(! /\d+|\./.test(jQuery('#cps_limit').val())){
                jQuery('#cps_limit').addClass('invalid');
                jQuery.jGrowl('CPS Limit must contain numeric characters only!',{theme:'jmsg-error'});
                return false;
            }
        }
        if(jQuery('#call_limit').val() != '') {
            if(! /\d+|\./.test(jQuery('#call_limit').val())){
                jQuery('#call_limit').addClass('invalid');
                jQuery.jGrowl('Call Limit must contain numeric characters only!',{theme:'jmsg-error'});
                return false;
            }
        }
        $.ajax({
            'url' : "<?php echo $this->webroot ?>wizards/check_exists",
            'async' : false,
            'type' : 'POST',
            'dataType' : 'text',
            'data' : {'trunk_name':trunk_name,'client_name':client_name},
            'success' : function(data) {
                var result = parseInt(data);
                if(result == 1) {
                    jQuery.jGrowl("The Trunk's name already exists!",{theme:'jmsg-alert'});
                    return false;
                } else if(result == 2) {
                    jQuery.jGrowl("The Carrier's name already exists!",{theme:'jmsg-alert'});
                } else {
                    $myform.submit();
                }
            }
        });
        return false;
    });
    
    
    
    refresh_ratetable();
    refresh_egress_trunk();
    
    function refresh_ratetable() {
        $.ajax({
            'url' : '<?php echo $this->webroot; ?>wizards/get_ratetable',
            'type' : 'GET',
            'dataType' : 'json',
            'success' : function(data) {
                $rate_table.empty();
                $.each(data, function(index, item) {
                    $rate_table.append('<option value="' + item[0]['rate_table_id'] + '">' + item[0]['name'] + '</option>');
                });
            }
        });
    }
    
    function refresh_egress_trunk() {
        $.ajax({
            'url' : '<?php echo $this->webroot; ?>wizards/get_egress',
            'type' : 'GET',
            'dataType' : 'json',
            'success' : function(data) {
                $egress_trunks_wait.empty();
                $.each(data, function(index, item) {
                    $egress_trunks_wait.append('<option value="' + item[0]['resource_id'] + '">' + item[0]['alias'] + '</option>');
                });
            }
        });
    }
    
});    
</script>