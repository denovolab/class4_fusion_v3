<style type="text/css">
#massedit h1 {
    color:#FF6D06;
    font-size:16px;
    cursor:pointer;
}
#editor {
    padding:30px;
}
</style>
<div id="massedit">
    <?php  if ($_SESSION['role_menu']['Routing']['dynamicroutes']['model_w']) {?><h1>Mass Edit »</h1><?php }?>
    <div id="editor">
        <form action="" method="post" name="myform" id="myform">
            <p>
                <label><?php echo __('Routing rule',true);?>:
                    <select name="routingrule">
                        <option selected="selected" value="4">Largest ASR</option>
                        <option value="5">Largest ACD</option>
                        <option value="6">LCR</option>
                    </select></label>
                <label><?php echo __('Time Profile',true);?>:
                        <select name="timeprofile">
                            <?php foreach($user as $k=>$v): ?>
                                <option value="<?php echo $k; ?>"><?php echo $v; ?></option>
                            <?php endforeach; ?>
                        </select>
                </label>
            </p>
            <p>
               <?php  if ($_SESSION['role_menu']['Routing']['dynamicroutes']['model_w']) {?> <h1><button id="addtrunk" class="input in-button"><?php echo __('add',true);?></button></h1><?php }?>
                <table id="tbl" class="mylist">
                    <thead>
                        <tr>
                            <td width="40%"><?php echo __('Carriers',true);?></td>
                            <td width="40%"><?php echo __('Trunk Name',true);?></td>
                            <td width="20%"></td>
                        <tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td width="40%">
                                <select  onchange="updatetrunks(this)" name="carriers">
                                    <?php foreach($carriers as $carrier): ?>
                                        <option value="<?php echo $carrier[0]['id']; ?>"><?php echo $carrier[0]['name']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                            <td width="40%">
                                <select name="trunks[]">
                                </select>
                            </td>
                            <td width="20%">
                                <a href="###" onclick="removeitem(this);"><img src="<?php echo $this->webroot; ?>images/delete.png" title="Delete"></a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </p>
           <?php  if ($_SESSION['role_menu']['Routing']['dynamicroutes']['model_w']) {?> <p style="text-align:center; margin-top:10px;">
                <label><a id="massbtn" class="input in-button"><?php echo __('submit',true);?></a></label>
            </p>
            <?php }?>
        </form> 
    </div>
</div>

<script type="text/javascript">
jQuery(function($) {
    $('#editor').hide();
    
    $('#massedit > h1').click(function() {
       $('#editor').slideToggle();
    });
    $('#tbl select').prepend('<option selected="selected" value=""></option>');
    $('#selectall').click(function() {
        if($(this).attr('checked')) {
             $('.mylist input[type=checkbox]').attr('checked','true');
        } else {
             $('.mylist input[type=checkbox]').removeAttr('checked');  
        }
    });
    $('#addtrunk').click(function() {
        $('#tbl tbody tr').clone(true).appendTo('#tbl tbody');
        return false;
    });
    
    $('#massbtn').click(function() {
        var arr = new Array();
        $('.list tbody input[type=checkbox]:checked').each(function() {
            //if($(this).attr('checked')) {
                arr.push($(this).attr('control'));
            //}
        }); 
        if(arr.length == 0) {
            return false;
        }
        $.ajax({
            url:'<?php echo $this->webroot; ?>dynamicroutes/massedit',
            type:"POST",
            dataType:'text',
            data:$('#myform').serialize()+"&ids="+arr.join(","),
            success:function(data) {
                window.location.reload();
            }
        });
        return false;
    });
});
function updatetrunks(elem) {
    $.getJSON('<?php echo $this->webroot?>/trunks/ajax_options?filter_id='+$(elem).val()+'&type=egress', function(data) {
     $brother = $(elem).parent().next().find('select'); 
     $brother.empty();
     $.each(data, function(idx, item){
            $('<option value="'+item.resource_id+'">'+item.alias+'</option>')
                    .appendTo($brother);
        });
    })    
}

function removeitem(elem) {
    if($('#tbl tbody tr').length == 1) {
        return;
    }
    $(elem).parent().parent().remove();
}
</script>