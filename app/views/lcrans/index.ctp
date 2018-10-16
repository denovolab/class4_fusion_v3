<style type="text/css">
.checkboxdiv {
	float:left;
    width:500px;
    margin:10px;
}
.checkboxdiv label{
    display:block;
}
.checkboxothers {
	float:left;
	margin-top:75px;
	margin-left:150px;
    text-align: left;
    overflow:hidden;
}
.cb_select label{ float:left;width:230px;}

</style>


<div id="title">
    <h1> <?php echo __('Tools',true);?>&gt;&gt;<?php echo __('LCR Analysis',true);?> </h1>
</div>

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
		    <td><?php echo __('Min',true);?></td>
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

    <fieldset class="query-box">
        <div class="search_title">
            <img src="<?php echo $this->webroot ?>images/search_title_icon.png">Query
        </div>
        
        <div class="form">
            <form method="post" name="myform" id="myform">
                <div id="s-rate_tables" class="checkboxdiv">
                    <div class="cb_select input">
                        <?php foreach($trunks as $trunk): ?>
                        <label><input type="checkbox" name="trunks[]" value="<?php echo $trunk[0]['resource_id']; ?>"
                                <?php if(isset($_POST['trunks'])):if(in_array($trunk[0]['resource_id'], $_POST['trunks'])) echo 'checked="checked"';endif; ?> 

                                 />&nbsp;<?php echo $trunk[0]['alias'] ?></label>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="checkboxothers">
                        Type:
                        <select name="type">
                            <option value="false" <?php if(isset($_POST['type']) && $_POST['type'] == "false") echo 'selected="selected"' ?>>Standard View</option>
                            <option value="true" <?php if(isset($_POST['type']) && $_POST['type'] == "true") echo 'selected="selected"' ?>>LCR View</option>
                        </select>
                        Show Type:
                        <select name="show_type">
                            <option value="0">WEB</option>
                            <option value="1">CSV</option>
                        </select>
                </div>
                <div id="form_footer">
                     	<input type="submit" value="<?php echo __('query',true);?>" />&nbsp;&nbsp;&nbsp;&nbsp;
                    </div>
            </form>
        </div>
    </fieldset>

</div>

<script type="text/javascript">
    $('#myform').bind('submit',function() {
        var size = $('input[name=trunks[]][checked]').size();
        if(size == 0) {
            alert("Please select egress trunks!");
            return false;
        }
    });

    $('.addtrunk').click(function() {
        var text = $(this).text();
	var textarr = text.split('(');
	text = textarr[0];
	var code = $(this).parent().parent().find("td:first-child").text();
	window.open('<?php echo $this->webroot ?>lcrans/add_trunk/' + code + '/' + text, 'clientcdr', 
        'height=220,width=400,top=0,left=0,toolbar=no,menubar=no,scrollbars=no, resizable=no,location=no, status=no');
	return false;
    });
</script>
