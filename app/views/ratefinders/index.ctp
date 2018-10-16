<div id="title">
    <h1><?php echo __('Tools',true);?> &gt;&gt; <?php echo __('Rate Finder',true);?></h1>
    <ul id="title-menu">
    <li>
	<a href="<?php echo $this->webroot ?>ratefinders" class="link_back"><img width="16" height="16" alt="Back" src="<?php echo $this->webroot ?>images/icon_back_white.png">&nbsp;<?php echo __('goback',true);?></a>    </li>
    </ul>
</div>
<style>
.trunk_list{
    overflow: hidden; width:1250px;
}
.trunk_list li{
    padding:0px 2px;margin-top:2px;white-space:nowrap;width:300px;float:left;

}
</style>
<div class="container">
    <?php if(isset($p)): ?>
    <?php
        if(!empty($p)) :
        $data = $p->getDataArray();
    ?>
    <div style="margin:10px;">
        <a class='input in-submit' href="<?php echo $this->webroot ?>ratefinders/down">Download</a> 
    </div>
    <div id="toppage"></div>
    <table class="list">
        <thead>
            <tr>
                <tr>
                    <td><?php echo __('code name',true);?></td>
                    <?php 
                        if($sorted_type == 'false') {  
                        $j = 4;
                    ?>
                    <td><?php echo __('code',true);?></td>
                    <?php } else { $j = 3; } ?>
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
                <?php for($i=0;$i<=$maxfields+$j;$i++): ?>
		<?php if($i > $j): ?>
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
        <div class="msg" style="width:550px;">No Egress Found For Specified Code and Rate can be found.
</div>
        <?php endif; ?>
    <?php else: ?>
    <form name="myform" id="myform" method="post">
    <table class="list">
        <tbody>
            <tr>
                <td><?php echo __('country',true);?></td>
                <td><input type="text" name="country" /></td>
                <td><?php echo __('code_name',true);?></td>
                <td><input type="text" name="codename" /></td>
            </tr>
            <tr>
                <td><?php echo __('code',true);?></td>
                <td><input type="text" name="code" /></td>
                <td><?php echo __('Limiting Rate',true);?></td>
                <td><input type="text" name="rate" /></td>
            </tr>
            <tr>
                <td><?php echo __('Egress Trunk Type',true);?></td>
                <td colspan="3">
                    <input type="radio" name="egress_trunk_type" value="0" /> <?php echo __('From All Active Egress Trunk',true);?>
                    &nbsp;
                    <input type="radio" name="egress_trunk_type" value="1" /> <?php echo __('From All Egress Trunk',true);?>
                    &nbsp;
                    <input type="radio" name="egress_trunk_type" value="2" checked="checked" /> <?php echo __('From Selected Egress Trunk',true);?>
                    &nbsp;
                    <input type="radio" name="sort_type" value="false" checked="checked"  /> <?php echo __('Code Level',true);?>
                    &nbsp;
                    <input type="radio" name="sort_type" value="true"  /> <?php echo __('Code Name Level',true);?>
                </td>
            </tr>
            <tr>
                <td><?php echo __('egress',true);?></td>
                <td colspan="3" style="text-align:left;">
                    <div class="trunk_list">    
                        <ul>
                            <?php foreach($trunks as $trunk): ?>
                            <li>
                                <label>
                                    <input class="border_no" type="checkbox" value="<?php echo $trunk[0]['resource_id'] ?>" name="trunks[]">
                                    &nbsp;<?php echo $trunk[0]['alias'];  ?>
                                </label>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </td>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4"><input type="submit" value="<?php echo __('submit',true);?>"  /></td>
            </tr>
        </tfoot>
    </table>
    </form>
    <?php endif; ?>
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
});
</script>