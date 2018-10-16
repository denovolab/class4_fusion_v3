
<div id="noRows" class="msg"><?php echo __('no_data_found',true);?></div>
<form id="objectForm" method="post" action="<?php echo $this->webroot?>jurisdictionprefixs/add?page=<?php echo $p->getCurrPage()?>&size=<?php echo $p->getPageSize()?>">
    <input type="hidden" id="delete_rate_id" value="" name="delete_rate_id" class="input in-hidden">

    <input type="hidden" value="1" name="page" class="input in-hidden">
    <table style="display: none;" class="list list-form" id="tabid">
        <thead>
            <tr>
                <?php  if ($_SESSION['role_menu']['Switch']['jurisdictionprefixs']['model_w']) {?>
                <td style="width:14%;text-align:center;" class="value"><input type="checkbox" id="selectAll" value=""></input></td><!--		全选    -->
                <?php }?>
                <!--<td style="width:14%;text-align:center;" class="value"><?php echo $appCommon->show_order('id',__('ID',true))?></td>-->

                <td style="width:14%;text-align:center;" class="value"><?php echo $appCommon->show_order('jurisdiction_country_name',__('Country',true))?></td>
                <td style="width:14%;text-align:center;" class="value"><?php echo $appCommon->show_order('jurisdiction_name',__('State',true))?></td>

                <td style="width:14%;text-align:center;" class="value"><?php echo $appCommon->show_order('prefix',__('Prefix',true))?></td>
                <td style="width:14%;text-align:center;" class="value"><?php echo $appCommon->show_order('prefix',__('OCN',true))?></td>
                <td style="width:14%;text-align:center;" class="value"><?php echo $appCommon->show_order('prefix',__('LATA',true))?></td>
                <!--<td style="width:14%;text-align:center;" class="value"><?php echo $appCommon->show_order('alias',__('Alias',true))?></td>-->
                <?php  if ($_SESSION['role_menu']['Switch']['jurisdictionprefixs']['model_w']) {?>
                <td style="width:14%;text-align:center;" class="last"><?php echo __('action')?></td><?php }?>
            </tr>
        </thead>
        <tbody id="rows">
            <tr id="tpl">
                <!--   增加复选框    -->
                <?php  if ($_SESSION['role_menu']['Switch']['jurisdictionprefixs']['model_w']) {?> <td style="width:14%;text-align:center;" class="value"><input type="checkbox" value="" name="id"></input></td>
                <?php }?>
                <!--<td style="width:14%;text-align:center;" class="value">
                 <small id="tpl-id-text"><?php echo __('code_name',true);?></small>
                    
                </td>-->
        <input type="hidden" name="id" /> 
        <td style="width:14%;text-align:center;" class="value"><input type="text"  rel="format_name"  name="jurisdiction_country_name" style="text-align:right;" maxlength="256" /></td>
        <td style="width:14%;text-align:center;" class="value"><input type="text"  rel="format_name"  name="jurisdiction_name" style="text-align:right;" maxlength="256"/></td>
        <td style="width:14%;text-align:center;" class="value"><input type="text" rel="format_number" name="prefix" style="font-weight:bold;text-align:right;" /></td>
        <td style="width:14%;text-align:center;" class="value"><input type="text" rel="format_number" name="ocn" style="font-weight:bold;text-align:right;" /></td>
        <td style="width:14%;text-align:center;" class="value"><input type="text" rel="format_number" name="lata" style="font-weight:bold;text-align:right;" /></td>
        <!--<td style="width:14%;text-align:center;" class="value"><input type="text"  rel="format_name"  name="alias" style="text-align:right;" /></td>-->
        <?php  if ($_SESSION['role_menu']['Switch']['jurisdictionprefixs']['model_w']) {?>
        <td style="width:14%;text-align:center;" class="value">
            <a href="#" id="tpl-delete-row" name= 'jurisdiction_name'  ><img src="<?php echo $this->webroot?>images/delete.jpg" width="16" height="16" /></a></td>
        <?php }?>
        </tr>
        </tbody>
    </table>
</form>
<script language="JavaScript">
    var lastId = 0;
    var eRows = $('#rows');
    var eTpl = $('#tpl').unbind();

    function addItem(row, append) 
    {
        lastId++;
        // defaults
        if (!row || !row['id']) {
  	 
            row = {
                'effective_date': '2010-12-26 00:00:00+0100',
                'time_profile_id': '',
                'rate': '0.0000',
                'min_time': '1',
                'seconds': '60',
                'interval': '1',
                'grace_time': '0',
                'intra_rate': '0.0000',
                'inter_rate': '0.0000',
                'local_rate': '0.0000'
            };
        }
        // fix row values
        for (k in row) { if (row[k] == null) row[k] = ''; }
        // prepare row
        var prefixId = 'row-'+lastId;
        var prefixName = 'rates['+lastId+']';
        var tRow = eTpl.clone(true).attr('id', prefixId);//临时准备的行
        jQuery(tRow).find('input[rel=format_number]').xkeyvalidate({type:'Num'}).attr('maxLength','16');
        jQuery(tRow).find('input[rel=format_name]').xkeyvalidate({type:'strNum'}).attr('maxLength','256');
        // set names / values
        tRow.find('input,select').each(function () {
            var el = $(this);
            var field = el.attr('name');
            el.attr({id: prefixId+'-'+field, name: prefixName+'['+field+']'}).val(row[field]);
        });
    

        // set text labels
        tRow.find('#tpl-id-text').text(row['id'] ? row['id'] : '');

        if (row['id']) {
            tRow.appendTo(eRows);
        } else {
            tRow.prependTo(eRows);
        }
    
        // styles
        if (!row['id']) {
            initForms(tRow); 
            initList();
        }
        $('#noRows').hide();
        $('.list-form').show();
        $('#toppage').show();
        $('#tmppages').show();
    }
    jQuery('input[type=text],input[type=password]').addClass('input in-input in-text');
    jQuery('input[type=button],input[type=submit]').addClass('input in-submit');
    jQuery('select').addClass('select in-select');
    jQuery('textarea').addClass('textarea in-textarea');

    //$('#rows').find('input[rel*=format_number]').live('keyup',function(){filter_chars(this);});
    $('#tpl-delete-row').live('click', function () {
        var del_rate_id=$(this).closest('tr').find('input[name*=id]').val();
        
        if(confirm(" Are you sure to delete prefix "+jQuery(this).closest('tr').find('input[name*=jurisdiction_name]').val() +" ?")){
            var $this = $(this);
            $.get('<?php echo $this->webroot; ?>jurisdictionprefixs/delete/' + del_rate_id, function(data) {
                $this.closest('tr').remove();
            });
        }
        if(jQuery('#rows tr').size()==0){
            $('#noRows').show();
            $('.list-form').hide();
            $('#toppage').hide();
            $('#tmppages').hide();
        }
        return false;
    });

        <?php 
    $mydata =$p->getDataArray();
    foreach ($mydata  as  $key =>$value){
			
        $id=!empty($value[0]['id'])?$value[0]['id']:'';
        $prefix=!empty($value[0]['prefix'])?$value[0]['prefix']:'';
        $alias=!empty($value[0]['alias'])?$value[0]['alias']:'';
			
				
        $jurisdiction_country_name=!empty($value[0]['jurisdiction_country_name'])?$value[0]['jurisdiction_country_name']:'';
        $jurisdiction_name=!empty($value[0]['jurisdiction_name'])?$value[0]['jurisdiction_name']:'';
        $ocn = !empty($value[0]['ocn'])?$value[0]['ocn']:'';
        $lata = !empty($value[0]['lata'])?$value[0]['lata']:'';
        echo "addItem({\"id\":\"$id\",\"jurisdiction_country_name\":\"$jurisdiction_country_name\",\"jurisdiction_name\":\"$jurisdiction_name\",\"prefix\":\"$prefix\",\"alias\":\"$alias\", \"ocn\":\"{$ocn}\", \"lata\":\"{$lata}\"}, 1);\n"; 
		
    }
        ?>
        eRows.hide();
    eTpl.remove();
    eRows.show();
</script>
<script type="text/javascript">
    <!--
    jQuery(document).ready(function(){
        jQuery('#selectAll').selectAll('input[type=checkbox]');
    });
    //-->
</script>

