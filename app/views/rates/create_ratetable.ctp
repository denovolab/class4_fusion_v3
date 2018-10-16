<style type="text/css">
    .ocn_lata {display:none;}
</style>

<div id="title">
    <h1> <?php __('Switch'); ?> &gt;&gt;<?php __('Create Rate Table'); ?></h1>
    <ul id="title-menu"> 
        <a class="link_back" href="<?php echo $this->webroot?>rates/rates_list"> <img width="16" height="16" alt="<?php echo __('goback',true);?>" src="<?php echo $this->webroot?>images/icon_back_white.png">&nbsp;<?php echo __('goback',true);?> </a>
    </ul>
</div>
<div id="container">
    <form method="post" action="">
    <table class="list">
        <tr>
            <td style="text-align:right;">
                <?php __('Rate Table Name'); ?>:
            </td>
            <td>
                <input type="text"  name="rate_table_name" />
            </td>
            <td colspan="6">
                &nbsp;
            </td>
        </tr>
        <tr>
            <td style="text-align:right;">
                <?php __('Code Deck'); ?>:
            </td>
            <td>
                <select name="code_deck">
                    <option selected="selected"></option>
                    <?php foreach($code_decks as $code_deck): ?>
                    <option value="<?php echo $code_deck[0]['code_deck_id']; ?>"><?php echo $code_deck[0]['name']; ?></option>
                    <?php endforeach; ?>
                </select>
            </td>
            <td style="text-align:right;">
                <?php __('Currency'); ?>:
            </td>
            <td>
                <select name="currency">
                    <?php foreach($currencies as $currency): ?>
                    <option value="<?php echo $currency[0]['currency_id']; ?>"><?php echo $currency[0]['code']; ?></option>
                    <?php endforeach; ?>
                </select>
            </td>
            <td style="text-align:right;">
                <?php __('Type'); ?>:
            </td>
            <td>
                <select name="type">
                    <option value="0">DNIS</option>
                    <option value="1">LRN</option>
                    <option value="2">LRN BLOCK</option>
                </select>
            </td>
            <td style="text-align:right;">
                <?php __('Rate Type'); ?>:
            </td>
            <td>
<!--                <input type="checkbox" id="is_us_jur" name="is_us_jur" />-->
                <select name="rate_type" id="rate_type">
                    <option value="0">A-Z</option>
                    <option value="1">US Non-JD</option>
                    <option value="2">US JD</option>
                    <option value="3">OCN-LATA-JD</option>
                    <option value="4">OCN-LATA-NON-JD</option>
                </select>
            </td>
        </tr>
    </table>
    <div id="buttons" style="text-align:right;margin:10px;">
        <input type="button" id="new" value="Create New" style="width:auto;" />
        <input type="button" id="delete_selected" value="Delete Selected" style="width:auto;" />
        <input type="button" id="delete_all" value="Delete All" style="width:auto;" />
    </div>
    <table id="ratelist" class="list list-form">
        <thead>
            <tr>
                <td><input type="checkbox" /></td>
                <td><?php __('Code'); ?></td>
                <td><?php __('Code Name'); ?></td>
                <td><?php __('Country'); ?></td>
                <td><?php __('OCN'); ?></td>
                <td><?php __('LATA'); ?></td>
                <td><?php __('Rate'); ?></td>
                <td><?php __('Intra Rate'); ?></td>
                <td><?php __('Inter Rate'); ?></td>
                <td><?php __('Effective Date'); ?></td>
                <td><?php __('End Date'); ?></td>
                <td><?php __('Extra Fields'); ?></td>
                <td>&nbsp;</td>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><input type="checkbox" /></td>
                <td><input type="text" name="code[]" style="width:100px;" /></td>
                <td><input type="text" class="code_name" name="code_name[]" style="width:100px;" /></td>
                <td><input type="text" class="country" name="country[]" style="width:100px;" /></td>
                <td><input type="text"  rel="format_number" name="ocn[]" style="width:100px;"  /></td>
                <td><input type="text"  rel="format_number" name="lata[]" style="width:100px;"  /></td>
                    </span>
                <td><input type="text" name="rate[]" style="width:100px;" /></td>
                <td><input type="text" name="intra_rate[]" style="width:100px;" /></td>
                <td><input type="text" name="inter_rate[]" style="width:100px;" /></td>
                <td>
                    <input type="text" name="effective_date[]" style="width:120px;" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'});" value="<?php echo date("Y-m-d 00:00:00") ?>" />
                    <select name="effective_date_gmt[]" style="width:100px;">
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
                        <option selected="selected" value="+00">GMT +00:00</option>
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
                <td>
                    <input type="text" name="end_date[]" style="width:120px;" onfocus="WdatePicker({startDate:'%y-%M-01 23:59:59',dateFmt:'yyyy-MM-dd HH:mm:ss',alwaysUseStartDate:false})" />
                    <select name="end_date_gmt[]" style="width:100px;">
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
                        <option selected="selected" value="+00">GMT +00:00</option>
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
                <td>
                    <a class="tpl-params-link" title="Additional properties" href="###">
                        <small id="tpl-params-text">1 / 1 / 0 / undefined</small>
                        <b class="neg">»</b>
                    </a>
                </td>
                <td>
                    <a href="###" class="deletebtn">
                        <img height="16" width="16" src="<?php echo $this->webroot; ?>images/delete.jpg">
                    </a>
                </td>
            </tr>
            <tr style="display:none">
                <td colspan="11">
                    <?php __('Setup Fee'); ?>:
                    <input type="text" name="setup_fee[]" style="width:80px;" value="0.000000" />
                    <?php __('Min Time'); ?>:
                    <input type="text" name="min_time[]" style="width:80px;" value="1"  />sec
                    <?php __('Interval'); ?>:
                    <input type="text" name="interval[]" style="width:80px;" value="1"  />
                    <?php __('Grace Time'); ?>:
                    <input type="text" name="grace_time[]" style="width:80px;" value="0"   />sec
                    <?php __('Seconds'); ?>:
                    <input type="text" name="second[]" style="width:80px;" value="60"  />sec
                    <?php __('Profile'); ?>:
                    <select name="profile[]">
                        <option></option>
                        <?php foreach($timeprofiles as $timeprofile): ?>
                        <option value="<?php echo $timeprofile[0]['time_profile_id']; ?>"><?php echo $timeprofile[0]['name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                    
                    <?php __('Local Rate'); ?>:
                    <input type="text" name="local_rate[]" style="width:80px;"  />
                </td>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="11"> <input type="submit" value="Submit" />&nbsp;&nbsp;&nbsp;<input type="button" id="backbtn" value="Cancel" /></td>
            </tr>   
        </tfoot>
    </table>
    </form>
</div>




<script type="text/javascript">
$(function() {
    
    var tr1 = $('#ratelist tbody tr:first').remove();
    var tr2 = $('#ratelist tbody tr:first').remove();
    
     $('#rate_type').change(function() {
        if($(this).val() == '2' || $(this).val() == '3') {
            $('#ratelist td:nth-child(8)').show();
            $('#ratelist td:nth-child(9)').show();
            tr1.find('td:nth-child(8)').show();
            tr1.find('td:nth-child(9)').show();
        } else {
            $('#ratelist td:nth-child(8)').hide();
            $('#ratelist td:nth-child(9)').hide();
            tr1.find('td:nth-child(8)').hide();
            tr1.find('td:nth-child(9)').hide();
        }
        if($(this).val() == '3' || $(this).val() == '4') {
            $('#ratelist td:nth-child(2)').hide();
            $('#ratelist td:nth-child(3)').hide();
            $('#ratelist td:nth-child(4)').hide();
            $('#ratelist td:nth-child(5)').show();
            $('#ratelist td:nth-child(6)').show();
            tr1.find('td:nth-child(2)').hide();
            tr1.find('td:nth-child(3)').hide();
            tr1.find('td:nth-child(4)').hide();
            tr1.find('td:nth-child(5)').show();
            tr1.find('td:nth-child(6)').show();
        } else { 
            $('#ratelist td:nth-child(2)').show();
            $('#ratelist td:nth-child(3)').show();
            $('#ratelist td:nth-child(4)').show();
            $('#ratelist td:nth-child(5)').hide();
            $('#ratelist td:nth-child(6)').hide();
            tr1.find('td:nth-child(2)').show();
            tr1.find('td:nth-child(3)').show();
            tr1.find('td:nth-child(4)').show();
            tr1.find('td:nth-child(5)').hide();
            tr1.find('td:nth-child(6)').hide();
        }
    });
    

    $('#rate_type').change();
    

    $('.country').live('click', function(){
        $(this).autocomplete(countries)
    });

    $('.code_name').live('click', function(){
        $(this).autocomplete(cities)
    });

    $('#new').click(function() {
        tr1.clone(true).appendTo('#ratelist tbody');
        tr2.clone(true).appendTo('#ratelist tbody');
        $('#rate_type').change();
        jQuery('#ratelist').find('input[rel=format_number]').xkeyvalidate({type:'Num'}).attr('maxLength','16');
    });

    $('.tpl-params-link').live('click', function() {
        var mintime = $(this).parent().parent().next().find('input[name=min_time[]]').val();
        var interval = $(this).parent().parent().next().find('input[name=interval[]]').val();
        var gracetime = $(this).parent().parent().next().find('input[name=grace_time[]]').val();
        var profile = $(this).parent().parent().next().find('select option:selected').text();
        $(this).find('small').text(mintime + ' / ' + interval + ' / ' + gracetime + ' / ' + profile);
        $(this).parent().parent().next().toggle().trigger('click');
    });

    $('.deletebtn').live('click', function() {
        $(this).parent().parent().next().remove().end().remove();
    });

    $('#ratelist thead input:checkbox').click(function() {
        $('#ratelist tbody input:checkbox').attr('checked', $(this).attr('checked'));
    });

    $('#delete_selected').click(function() {
        $('#ratelist tbody tr:has(input:checked)').next().remove().end().remove();
    });

    $('#delete_all').click(function() {
        $('#ratelist tbody').empty(); 
    });

    $('#backbtn').click(function() {
        window.location.href = "<?php echo $this->webroot ?>rates/rates_list";
    });
   
});
</script>
