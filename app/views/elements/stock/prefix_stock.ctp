<style type="text/css">
    #flashcontent {float:left;}
    #control{float:right;}
</style>
<script type="text/javascript" src="<?php echo $this->webroot; ?>stock/swfobject.js"></script>

<div id="stock">
    <div id="flashcontent">
            <strong><?php echo __('viewcharts')?></strong>
    </div>
    <div id="control">
        <select>
            <option value="0">Please select...</option>
            <option value="prefix_stats_call">Calls</option>
            <option value="prefix_stats_cps">CPS</option>
            <option value="prefix_stats_acd">ACD</option>
            <option value="prefix_stats_pdd">PDD</option>
            <option value="prefix_stats_asr">ABR</option>
        </select>
    </div>
</div>

<script type="text/javascript">
// <![CDATA[	
function showstock(name, proc_id) {
    var so = new SWFObject("<?php echo $this->webroot; ?>stock/amstock.swf", "amstock", "1200", "500", "8", "#FFFFFF");
    so.addVariable("path", "<?php echo $this->webroot; ?>upload/stock/");
    so.addVariable("settings_file", encodeURIComponent("<?php echo $this->webroot; ?>stocks/createxml/" + name + '/' + proc_id +  '.xml'));
    so.addVariable("preloader_color", "#999999");
    so.addParam("wmode","transparent");  
    so.write("flashcontent");
}
$(function() {
    $('#control select').change(function() {
        if($(this).val() == "0"){
            return false;
        }
        showstock($(this).val(), '<?php echo $this->params['pass'][0] ?>');
    });
    $('#control select').change();
});
// ]]>
</script>