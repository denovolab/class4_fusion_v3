<div id="title">
    <h1><?php __('Auto CDR Generation Format'); ?></h1>
</div>

<div id="container">
    <form method="post" id="myform">
    <table class="list">
        <thead>
            <tr>
                <td colspan="2"><?php __('Ingress CDR Fields') ?></td>
                <td colspan="2"><?php __('Egress CDR Fields') ?></td>
            </tr>
        </thead>
        
        <tbody>
            <tr>
                <td>
                    <select multiple="multiple" id="incoming_select" style="width:300px;height:300px;">
<?php foreach($incoming_cdr_fields as $key => $incoming_cdr_field): ?>
<option value="<?php echo $key ?>"><?php echo $incoming_cdr_field ?></option>
<?php endforeach; ?>
                    </select>
                </td>
                <td>
                    <select multiple="multiple" id="incoming_list" name="incoming_list[]" style="width:300px;height:300px;">
<?php foreach($incoming_data as $key => $incoming_item): ?>
<option value="<?php echo $key ?>"><?php echo $incoming_item ?></option>
<?php endforeach; ?>                        
                    </select>
                </td>
                <td>
                    <select multiple="multiple" id="outgoing_select"  style="width:300px;height:300px;">
<?php foreach($outgoing_cdr_fields as $key => $outgoing_cdr_field): ?>
<option value="<?php echo $key ?>"><?php echo $outgoing_cdr_field ?></option>
<?php endforeach; ?>                       
                    </select>
                </td>
                <td>
                    <select multiple="multiple" id="outgoing_list" name="outgoing_list[]" style="width:300px;height:300px;">
<?php foreach($outgoing_data as $key => $outgoing_item): ?>
<option value="<?php echo $key ?>"><?php echo $outgoing_item ?></option>
<?php endforeach; ?>                           
                    </select>
                </td>
            </tr>
        </tbody>
        
        <tfoot>
            <tr>
                <td colspan="4">
                    <input type="submit" value="Submit" />
                </td>
            </tr>
        </tfoot>
    </table>
    </form>
</div>

<script>
$(function() {
    var $incoming_select = $('#incoming_select');
    var $incoming_list   = $('#incoming_list');
    var $outgoing_select = $('#outgoing_select');
    var $outgoing_list   = $('#outgoing_list');
    
    $('option', $incoming_select).live('dblclick', function() {
        var $option = $(this).remove();
        $incoming_list.append($option);
        
    });
    
    $('option', $incoming_list).live('dblclick', function() {
        var $option = $(this).remove();
        $incoming_select.append($option);
        
    });
    
    $('option', $outgoing_select).live('dblclick', function() {
        var $option = $(this).remove();
        $outgoing_list.append($option);
        
    });
    
    $('option', $outgoing_list).live('dblclick', function() {
        var $option = $(this).remove();
        $outgoing_select.append($option);
        
    });
    
    $('#myform').submit(
        function() {
            $('option', $incoming_list).attr('selected', true);
            $('option', $outgoing_list).attr('selected', true);
        }
    );  
    
    
});    
</script>