<div id="title">
    <h1>Statistics &gt;&gt; Active Call Report</h1>
</div>

<div id="container">
    <table class="list">
        <thead>
            <tr>
                <?php foreach($fields_keys as $index => $field): ?>
                <?php if ($show_fields[$index]): ?>
                <td><?php echo $fields[$field]; ?></td>
                <?php endif; ?>
                <?php endforeach; ?>
                <td>Action</td>
            </tr>
        </thead>
        
        <tbody>
            <?php foreach($data as $row): ?>
            <tr>
                <?php foreach($row as $key => $item):  ?>
                <?php if ($show_fields[$key]): ?>
                <td><?php echo $item; ?></td>
                <?php endif; ?>
                <?php endforeach; ?>
                <td>
                    <a href="<?php echo $this->webroot ?>active_calls/kill/<?php echo $row[$termination_uuid_a]  ?>">
                        <img src="<?php echo $this->webroot ?>images/kill.png">
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    
    <fieldset class="query-box">
        <h1 class="search_title">
            <img src="<?php echo $this->webroot; ?>images/search_title_icon.png">&nbsp;
            Search  
        </h1>
        <form action="<?php echo $this->webroot; ?>active_calls/reports" method="get" id="myform">
            <input type="hidden" name="query" value="1" />
            <table class="form">
                <tbody>
                    <tr class="period-block">
                        <td style="text-align:center;font-size:14px;" colspan="2" class="label"><b>Inbound</b></td>
                        <td style="text-align:center;font-size:14px;" colspan="2" class="label"><b>Outbound</b></td>
                        <td style="text-align:center;font-size:14px;" colspan="2" class="label"><b>Show Fields</b></td>
                    </tr>
                    <tr>
                        <td>Carrier</td>
                        <td>
                            <select name="orig_carrier" id="orig_carrier">
                                <option></option>
                                <?php foreach($clients as $k => $v): ?>
                                <option value="<?php echo $k; ?>" <?php echo $common->set_get_select('orig_carrier', $k) ?>><?php echo $v; ?></option>
                                <?php endforeach; ?>
                            </select>
                            <?php echo $this->element('search_report/ss_clear_input_select');?>
                        </td>
                        <td>Carrier</td>
                        <td>
                            <select name="term_carrier" id="term_carrier">
                                <option></option>
                                <?php foreach($clients as $k => $v): ?>
                                <option value="<?php echo $k; ?>" <?php echo $common->set_get_select('term_carrier', $k) ?>><?php echo $v; ?></option>
                                <?php endforeach; ?>
                            </select>
                            <?php echo $this->element('search_report/ss_clear_input_select');?>
                        </td>
                        <td rowspan="4">
                            <select name="show_fields[]" multiple="multiple" style="width:400px;height:200px;">
                                <?php foreach($fields as $k => $v): ?>
                                <?php if (!in_array($v, $ignore_fields)): ?>
                                <option value="<?php echo $k; ?>" <?php if ($show_fields_assoc[$k]) echo 'selected="selected"'; ?>><?php echo $v; ?></option>
                                <?php endif; ?>
                                <?php endforeach; ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>Ingress</td>
                        <td>
                            <select name="ingress" id="ingress_resource">
                                <option></option>
                                <?php foreach($ingress_resources as $k => $v): ?>
                                <option value="<?php echo $k; ?>" <?php echo $common->set_get_select('ingress', $k) ?>><?php echo $v; ?></option>
                                <?php endforeach; ?>
                            </select>
                            <?php echo $this->element('search_report/ss_clear_input_select');?>
                        </td>
                        <td>Egress</td>
                        <td>
                            <select name="egress" id="egress_resource">
                                <option></option>
                                <?php foreach($egress_resources as $k => $v): ?>
                                <option value="<?php echo $k; ?>" <?php echo $common->set_get_select('egress', $k) ?>><?php echo $v; ?></option>
                                <?php endforeach; ?>
                            </select>
                            <?php echo $this->element('search_report/ss_clear_input_select');?>
                        </td>
                    </tr>
                    <tr>
                        <td>IP</td>
                        <td>
                            <select name="orig_ip" id="orig_ip">
                                <option></option>
                                <?php foreach($ingress_resource_ips as $k => $v): ?>
                                <option value="<?php echo $k; ?>" <?php echo $common->set_get_select('orig_ip', $k) ?>><?php echo $v; ?></option>
                                <?php endforeach; ?>
                            </select>
                            <?php echo $this->element('search_report/ss_clear_input_select');?>
                        </td>
                        <td>IP</td>
                        <td>
                            <select name="term_ip" id="term_ip">
                                <option></option>
                                <?php foreach($egress_resource_ips as $k => $v): ?>
                                <option value="<?php echo $k; ?>" <?php echo $common->set_get_select('term_ip', $k) ?>><?php echo $v; ?></option>
                                <?php endforeach; ?>
                            </select>
                            <?php echo $this->element('search_report/ss_clear_input_select');?>
                        </td>
                    </tr>
                    <tr>
                        <td>ANI</td>
                        <td>
                            <input type="text" name="ani"  value="<?php echo $common->set_get_value('dnis') ?>" />
                            <?php echo $this->element('search_report/ss_clear_input_select');?>
                        </td>
                        <td>DNIS</td>
                        <td>
                            <input type="text" name="dnis" value="<?php echo $common->set_get_value('dnis') ?>" />
                            <?php echo $this->element('search_report/ss_clear_input_select');?>
                        </td>
                    </tr>
                </tbody>
            </table>
            <div id="form_footer"><input type="submit" class="input in-submit" value="Query"></div>
        </form>
    </fieldset>
</div>

<script type="text/javascript">
$(function() {
    var $orig_carrier = $('#orig_carrier');
    var $term_carrier = $('#term_carrier');
    var $ingress_resource = $('#ingress_resource');
    var $egress_resource = $('#egress_resource');
    var $orig_ip = $('#orig_ip');
    var $term_ip = $('#term_ip');
    
    $orig_carrier.change(function() {
        var $this = $(this);
        var client_id = $this.val();
        $('option:gt(0)', $ingress_resource).remove();
        $.ajax({
            'url'      : '<?php echo $this->webroot ?>active_calls/get_resources',
            'type'     : 'POST',
            'dataType' : 'json',
            'data'     : {'client_id' : client_id, 'type' : 'ingress'},
            'success'  : function(data) {
                $.each(data, function(index, item) {
                    $ingress_resource.append("<option value='"+ index +"'>" + item +"</option>");
                });
            }
        });
    });
    
    $term_carrier.change(function() {
        var $this = $(this);
        var client_id = $this.val();
        $('option:gt(0)', $egress_resource).remove();
        $.ajax({
            'url'      : '<?php echo $this->webroot ?>active_calls/get_resources',
            'type'     : 'POST',
            'dataType' : 'json',
            'data'     : {'client_id' : client_id, 'type' : 'egress'},
            'success'  : function(data) {
                $.each(data, function(index, item) {
                    $egress_resource.append("<option value='"+ index +"'>" + item +"</option>");
                });
            }
        });
    });
    
    $ingress_resource.change(function() {
        var $this = $(this);
        var resource_id = $this.val();
        $('option:gt(0)', $orig_ip).remove();
        $.ajax({
            'url'      : '<?php echo $this->webroot ?>active_calls/get_resource_ips',
            'type'     : 'POST',
            'dataType' : 'json',
            'data'     : {'resource_id' : resource_id, 'type' : 'ingress'},
            'success'  : function(data) {
                $.each(data, function(index, item) {
                    $orig_ip.append("<option value='"+ index +"'>" + item +"</option>");
                });
            }
        });
    });
    
    $egress_resource.change(function() {
        var $this = $(this);
        var resource_id = $this.val();
        $('option:gt(0)', $term_ip).remove();
        $.ajax({
            'url'      : '<?php echo $this->webroot ?>active_calls/get_resource_ips',
            'type'     : 'POST',
            'dataType' : 'json',
            'data'     : {'resource_id' : resource_id, 'type' : 'egress'},
            'success'  : function(data) {
                $.each(data, function(index, item) {
                    $term_ip.append("<option value='"+ index +"'>" + item +"</option>");
                });
            }
        });
    });
    
    $('#myform').click(function() {
        //loading();
    });
});
</script>