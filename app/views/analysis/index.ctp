<div id="title">
    <h1><?php echo __('Tool',true);?>&gt;&gt;<?php echo __('Rate Analysis',true);?></h1>
    <ul id="title-menu">
    <li>
	<a href="<?php echo $this->webroot ?>ratefinders" class="link_back"><img width="16" height="16" alt="Back" src="<?php echo $this->webroot ?>images/icon_back_white.png">&nbsp;<?php echo __('goback',true);?></a>    </li>
    </ul>
</div>
<style>
.trunk_list{
    
}
.trunk_list li{
    float:left;padding:0px 2px;margin-top:2px;white-space:nowrap;width:300px;

}
</style>
<div class="container">
     <?php if(isset($p)): ?>
    <?php
        if(!empty($p)) :
        $data = $p->getDataArray();
    ?>
    <div id="toppage"></div>
    <table class="list">
        <thead>
            <tr>
                <tr>
                    <td><?php echo __('code',true);?></td>
		    <td><?php echo __('min',true);?></td>
		    <td><?php echo __('max',true);?></td>
		    <td><?php echo __('avg',true);?></td>
                    <?php for($i=0;$i<$maxfields;$i++): ?>
                    <td>Trunk-<?php echo $i+1 ;?></td>
                    <?php endfor; ?>
                </tr>
            </tr>
        </thead>
        <tbody>
            <?php foreach($data as $item): ?>
            <tr>
                <?php for($i=0;$i<=$maxfields+3;$i++): ?>
		<?php if($i > 3): ?>
		<td><a href="###" class="addtrunk"><?php echo isset($item[$i])?$item[$i]:'&nbsp'; ?></a></td>
		<?php else: ?>
                <td><?php echo isset($item[$i])?$item[$i]:'&nbsp'; ?></td>
		<?php endif ?>
                <?php endfor; ?>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div id="tmppage"><?php echo $this->element('page');?></div>
        <?php else: ?>
        <div class="msg" style="width:550px;">No Egress Found For Specified Code and Rate can be found.</div>
        <?php endif; ?>
    <?php endif; ?>
    <form name="myform" id="myform" method="post">
    <fieldset class="query-box">
        <div class="search_title"><img src="<?php echo $this->webroot; ?>images/search_title_icon.png">
            <?php echo __('query',true);?>
        </div>

            <table class="list">
                <tr>
                    <td>
                        <?php echo __('Get Margin For',true);?>
                    </td>
                    <td>
                        <?php echo __('Trunk',true);?>:
                        <select name="trunks" id="trunks">
                            <option></option>
                            <?php foreach($trunks as $trunk): ?>
                            <option value="<?php echo $trunk[0]['resource_id'] ?>"><?php echo $trunk[0]['alias'] ?></option>
                            <?php endforeach; ?>
                        </select>
                        <?php echo __('Rate Table',true);?>:
                        <select name="ratetables" id="ratetables">
                            <option></option>    
                        </select>
                    </td>
                    <td>
                        <?php echo __('Report Type',true);?>:
                    </td>
                    <td>
                        <select name="report_type">
                            <option value="0">Standard view</option>
                            <option value="1">Rate Comparation</option>
                        </select>
                    </td>
                    <td>
                        <?php echo __('Show Type',true);?>:
                    </td>
                    <td>
                        <select name="show_type">
                            <option value="0">WEB</option>
                            <option value="1">CSV</option>
                        </select>
                    </td>
                </tr>
                <tr>
                <td></td>
                    <td colspan="5">
                        <label style="float:left;color:#FF6D06;font-weight:bold;"><input type="checkbox" id="select_all" style="cursor:pointer;" /><?php echo __('Select All',true);?></label>
                <label style="float:left;margin:0 10px;">|</label>
                <label style="float:left"><a id="select_reverse" style="cursor:pointer;"><?php echo __('Unselected',true);?></a></label>
                    </td>
                    
                </tr>
                <tr>
                <td><?php echo __('rate table',true);?></td>
                	<td colspan="5" style="text-align:left !important;">
                    	<div style="height:150px;overflow-y:scroll;margin-top:10px;">
                <?php foreach($ratetables as $ratetable): ?>
                <label style="width:250px;float:left;"><input type="checkbox" name="ratetable[]" value="<?php echo $ratetable[0]['id'] ?>" /><?php echo $ratetable[0]['name'] ?></label>
                <?php endforeach; ?>
            </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="6"><input type="submit" value="<?php echo __('submit',true);?>" /></td>
                </tr>
            </table>

    </fieldset>
    </form>
</div>
<script>
$(function() {
    $('input[name=egress_trunk_type]').click(function() {
        if($(this).val() != '2') {
            $('table.list tr:nth-child(4)').hide();
        } else {
            $('table.list tr:nth-child(4)').show();
        }
    });
    
    $('#select_all').click(function() {
        $("input[name='ratetable[]']").attr("checked",$(this).attr("checked"));
    });
    
    $('#select_reverse').click(function() {
        $("input[name='ratetable[]']").each(function(idx, item) {
            $(item).attr("checked",!$(item).attr("checked"));
        });
    });
    
    $('#trunks').change(function() {
        var resource_id = $(this).val();
        $.ajax({
            'url' : '<?php $this->webroot; ?>analysis/ready_ratetables/' + resource_id,
            'type' : 'GET',
            'dataType' : 'json',
            'success' : function(data) {
                var ratetables = $('#ratetables');
                ratetables.empty();
                ratetables.append('<option></option>');
                $.each(data, function(idx, item){
                    ratetables.append('<option value="'+item[0]['id']+'">'+item[0]['name']+'</option>');
                });
            }
        });
    });
});
</script>