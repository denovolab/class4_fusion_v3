<style>
#optional_col span {
}
</style>
<script type="text/javascript">
function get_codename()
{
	var country = $("#filter_country").val();
	
	$.getJSON("<?php echo $this->webroot?>order/order_browsers/ajax_codename_options?country="+country,{},function(d){
		$("#filter_code_name option").remove();
		$("<option value=''></option>").appendTo("#filter_code_name");
		$.each(d,function(idx,item){
			$("<option value='"+item+"'>"+item+" </option>").appendTo("#filter_code_name");
		});	
	});
}
function get_code()
{
	var codename = $("#filter_code_name").val();
	
	$.getJSON("<?php echo $this->webroot?>order/order_browsers/ajax_code_options?codename="+codename,{},function(d){
		$("#filter_code option").remove();
		$("<option value=''></option>").appendTo("#filter_code");
		$.each(d,function(idx,item){
			$("<option value='"+item+"'>"+item+" </option>").appendTo("#filter_code");
		});	
	});
}
</script>
<div id="title" style="text-align: justify;">
  <ul style="padding-top:10px;display: inline;  z-index:1;list-style-type:none">
    <h1>
      <?php if($do_action=='buy')echo 'Sell' ;else echo 'Buy'?>
      &gt;&gt;Select Posted <?php echo Inflector::humanize($do_action)?> </h1>
  </ul>
  <ul id="title-menu">
    <li><a class="link_btn" href="<?php echo $this->webroot;?>order/order_browsers/buy">Buy Order</a> </li><li> <a  class="link_btn" href="<?php echo $this->webroot;?>order/order_browsers/sell">Sell Order</a></li>
  </ul>
</div>

  

<div class="container" >
  <div align="left">
    <table>
    <form action="" method="GET">
      <tr>
        <td>
            <?php echo $xform->search('filter_country',Array('empty'=>'Filter country','options'=>$appOrderBrowsers->format_country_options(ClassRegistry::init('Code')->find_countries()),'style'=>'width:215px', 'onchange'=>"get_codename();"))?>
         </td>
         <td>
			<?php 
			echo $xform->search('filter_code_name',Array('empty'=>'Filter Code Name','options'=>array(),'style'=>'width:160px','onchange'=>"get_code();"));
			
			
			//echo $xform->search('filter_code_name',Array('empty'=>'Filter Code Name','options'=>$appOrderBrowsers->format_code_name_options(ClassRegistry::init('Code')->find_code_names()),'style'=>'width:310px'));?>
           </td>
           <td>
           <?php echo __('match type',true);?>:<select name="match_type">
           <option value="all">All</option>
           	<option value="auto_match">Match</option>
            <option value="no_match">No Match</option>
           </select>
           </td>
           
           <td>
			<?php 
			echo $xform->search('filter_code',Array('empty'=>'Filter Code','options'=>array(),'style'=>'width:120px'));
			
			
			
			//echo $xform->search('filter_code',Array('empty'=>'Filter Code','options'=>$appOrderBrowsers->format_code_4_options(ClassRegistry::init('Code')->find_codes()),'style'=>'width:120px'));?>
            &nbsp;&nbsp;&nbsp;&nbsp;
            <?php echo $xform->search('filter_client_id',Array('empty'=>'Filter Client','options'=>$appOrderBrowsers->format_client_options(ClassRegistry::init('Code')->find_order_users()),'style'=>'width:120px'))?>
            </td>
            <?php if ($do_action=='sell'){?>
            <td>
            <select id="filterSelect" class="select in-select" style="width:120px" name="filter_is_select">
            <option value="" <?php if (!isset($_REQUEST['filter_is_select']) || $_REQUEST['filter_is_select'] == '') echo 'selected'; ?>>ALL</option>
                <option value="0" <?php if ( isset($_REQUEST['filter_is_select']) && '0' == $_REQUEST['filter_is_select']) echo 'selected'; ?>>No selected</option>
                <option value="1" <?php if ( isset($_REQUEST['filter_is_select']) && '1' == $_REQUEST['filter_is_select']) echo 'selected'; ?>>Cancel selected</option>
                <option value="2" <?php if ( isset($_REQUEST['filter_is_select']) && '2' == $_REQUEST['filter_is_select']) echo 'selected'; ?>>Selected</option>
                <option value="3" <?php if ( isset($_REQUEST['filter_is_select']) && '3' == $_REQUEST['filter_is_select']) echo 'selected'; ?>>Confirm selected</option>
            </select>
            </td>
            <?php }?>
         </tr>
         <tr>
            <td> 
			<?php echo $appOrderBrowsers->filter_asr()?>
            &nbsp;&nbsp;&nbsp;&nbsp;
			<?php echo $appOrderBrowsers->filter_acd()?>
            </td>
            <td>
            <input type="text" readonly onfocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'});" id="start_date" style="width:140px;" name="filter_start_date" class="input in-text wdate" value="<?php echo empty($_REQUEST['filter_start_date']) ? '' : $_REQUEST['filter_start_date'];?>">
            </td><td>----&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <input type="text" readonly onfocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'});" id="end_date" style="width:140px;" name="filter_end_date" class="wdate input in-text" value="<?php echo empty($_REQUEST['filter_end_date']) ? '' : $_REQUEST['filter_end_date'];?>">
            </td>
            <td>
            <select id="filterActive" class="select in-select" style="width:120px" name="filter_active">
            			<option value="" <?php if (empty($_REQUEST['filter_active'])) echo 'selected'; ?>>ALL</option>
                <option value="t" <?php if ( !empty($_REQUEST['filter_active']) && 't' == $_REQUEST['filter_active']) echo 'selected'; ?>>Active</option>
                <option value="f" <?php if (!empty($_REQUEST['filter_active']) && 'f' == $_REQUEST['filter_active']) echo 'selected'; ?>>Inactive</option>
            </select>
             &nbsp;&nbsp;&nbsp;&nbsp;
            <select id="filterPurged" class="select in-select" style="width:120px" name="filter_purged">
            		<option value="" <?php if (empty($_REQUEST['filter_purged'])) echo 'selected'; ?>>ALL</option>
                <option value="t"  <?php if ( !empty($_REQUEST['filter_purged']) && 't' == $_REQUEST['filter_purged']) echo 'selected'; ?>>Purged</option>
                <option value="f"  <?php if ( !empty($_REQUEST['filter_purged']) && 'f' == $_REQUEST['filter_purged']) echo 'selected'; ?>>Unpurged</option>
            </select>
            </td>
            <td rowspan="2">
			<?php echo $form->submit('',array('label'=>false,'div'=>false,'class'=>"input in-submit"))?>
            </td>
            </tr>
          </form>
          <tr>
        <td colspan="3"><ul  id="optional_col" style="display: inline;  z-index:1;list-style-type:none">
            <li>
              <input type="checkbox" value="asr" id="optional_col_asr"  <?php echo $appOrderBrowsers->show_order_list_col_show('asr',true) ? 'checked' : '';?>>
              <label for="optional_col_asr"><?php echo __('asr',true);?></label>
              </li>
              <li>
              <input type="checkbox" value="acd" id="optional_col_acd"  <?php echo $appOrderBrowsers->show_order_list_col_show('acd',true) ? 'checked' : '';?> >
              <label for="optional_col_acd"><?php echo __('acd',true);?></label>
             </li>
              <li>
              <input type="checkbox" value="cli" id="optional_col_cli"  <?php echo $appOrderBrowsers->show_order_list_col_show('cli',false) ? 'checked' : '';?>>
              <label for="optional_col_cli"><?php echo __('cli',true);?></label>
              </li>
              <li>
              <input type="checkbox" value="g729" id="optional_col_g729"  <?php echo $appOrderBrowsers->show_order_list_col_show('g729',false) ? 'checked' : '';?>>
              <label for="optional_col_g729"><?php echo __('G729',true);?></label>
             </li>
              <li>
              <input type="checkbox" value="fax" id="optional_col_fax"  <?php echo $appOrderBrowsers->show_order_list_col_show('fax',false) ? 'checked' : '';?>>
              <label for="optional_col_fax"><?php echo __('fax',true);?></label>
              </li>
              <li>
              <input type="checkbox" value="create_time" id="optional_col_create_time"  <?php echo $appOrderBrowsers->show_order_list_col_show('create_time',true) ? 'checked' : '';?> >
              <label for="optional_col_create_time"><?php echo __('start_time',true);?></label>
              </li>
          </ul></td>
          
      </tr>
    </table>
  </div>
  <div id="toppage"></div>
  <?php echo $this->element("order_browsers/order_list",array('is_private' => false))?>
  <div id="tmppage"> <?php echo $this->element('page');?> </div>
</div>
<script type="text/javascript">
(function($){
		$(document).ready(function(){
			$('#optional_col input[type=checkbox]').bind('click',function(){
				if(this.checked){
					$("td[rel=order_list_col_"+this.value+"]").show();
				}else{
					$("td[rel=order_list_col_"+this.value+"]").hide();
				}
				var val = this.checked ? 'true' : 'false';
				var col = this.value;
				App.Common.updateDivByAjax("<?php echo Router::url(array('plugin'=> $this->plugin,'controller'=>$this->params['controller'],'action'=>'ajax_def_col'))?>","none",{'action':'browsers','col_name':col,'value':val});
			});	
		});
	}
)(jQuery);
</script> 
