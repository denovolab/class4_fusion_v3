<div id="cover"></div>
<div id="cover_tmp"></div>
<div id="title">
  <h1>
    <?php  __('Management')?>
    &gt;&gt;
   <?php echo __('Logs Manage',true);?> 
  </h1>
  <ul id="title-search">
    <li>
      <form   id="like_form"  action=""  method="get">
        <input type="text" id="search-_q" class="in-search default-value input in-text defaultText" title="<?php echo __('namesearch')?>" value="<?php if (!empty($search)) echo $search;?>" name="search">
      </form>
    </li>
    <li title="advanced search »" onclick="advSearchToggle();" id="title-search-adv" style="display: list-item;" class=" "></li>
  </ul>
</div>
<div id="container">
  <fieldset style="margin-left: 1px; width: 100%; display: <?php echo isset($url_get['advsearch']) ? 'block' :'none';?>;" id="advsearch" class="title-block">
    <form method="get" action="">
      <input type="hidden" name="advsearch" class="input in-hidden">
      <table style="width:100%">
        <tr>
          <td style="width:5%;text-align: right;"><?php echo __('type',true);?>:</td>
          <td style="text-align: left;">
          <select name="type" class="select in-select" id="type">
          	<option value="">===Select===</option>
          	<option value="country"<?php if(!empty($_REQUEST['type'])&&$_REQUEST['type']=='country'){echo "selected=selected";} ?>>Country</option>
            <option value="code_name"<?php if(!empty($_REQUEST['type'])&&$_REQUEST['type']=='code_name'){echo "selected=selected";} ?>>Destination</option>
            <option value="code"<?php if(!empty($_REQUEST['type'])&&$_REQUEST['type']=='code'){echo "selected=selected";} ?>>Code</option>
            <option value="order"<?php if(!empty($_REQUEST['type'])&&$_REQUEST['type']=='order'){echo "selected=selected";} ?>>Order ID</option>
          </select>
          </td>
          <td style="width:5%;text-align: right;"><?php echo __('Value',true);?>:</td>
          <td style="text-align: left;"><input type="text" class="input in-text" style="width:120px;" name="search_val" value="<?php echo !empty($_REQUEST['search_val']) ? $_REQUEST['search_val'] : ''; ?>"  id="search_val"></td>
          <td style="width:5%;text-align: right;"><?php echo __('Time',true);?>:</td>
          <td style="text-align: left;">
          	
              <input type="text" readonly onFocus="WdatePicker({maxDate:'#F{$dp.$D(\'end_date\')}',dateFmt:'yyyy-MM-dd HH:mm:ss'});" id="start_date" style="width:120px;" name="start_date" class="input in-text wdate" value="<?php echo !empty($_REQUEST['start_date']) ? $_REQUEST['start_date'] : date("Y-m-d 00:00:00"); ?>">
              --
              <input type="text" readonly onFocus="WdatePicker({minDate:'#F{$dp.$D(\'start_date\')}',dateFmt:'yyyy-MM-dd HH:mm:ss'});" id="end_date" style="width:120px;"  name="end_date" class="wdate input in-text" value="<?php echo !empty($_REQUEST['end_date']) ? $_REQUEST['end_date'] : date("Y-m-d 23:59:59"); ?>">
           
          </td>
          <td style="width:5%;text-align: right;"><?php echo __('name',true);?>:</td>
          <td style="text-align: left;"><input type="text" class="input in-text" style="width:120px;" name="name" value="<?php echo !empty($_REQUEST['name']) ? $_REQUEST['name'] : ''; ?>"  id="query-id_clients_name"></td>
          <td><input type="submit" class="input in-submit" value="<?php echo __('submit',true);?>"></td>
        </tr>
        
      </table>
    </form>
  </fieldset>
  
   <?php 			$mydata =$p->getDataArray();
			$loop = count($mydata); 
			if(empty($mydata)){
			?>
<div class="msg">No data found.</div>
<?php }else{

?>
  <div id="toppage"></div>

 
      <table class="list">

        <thead>
          <tr>
            <td><?php echo __('Search Type',true);?></td>
            <td><?php echo __('Search Value',true);?></td>
            <td><?php echo __('Search Time',true);?></td>
            <td><?php echo __('name',true);?></td>
           
          </tr>
         
        </thead>
        <tbody>
          <?php
		$mydata = $p->getDataArray();
		$loop = count($mydata);
		for ($i=0;$i<$loop;$i++) {
	?>
          <tr class="row-1">
            
            <td><?php echo $mydata[$i][0]['type'];?></td>
            <td><?php echo $mydata[$i][0]['search_val'];?></td>
            <td><?php echo $mydata[$i][0]['search_time'];?></td>
            <td><?php echo $mydata[$i][0]['name'];?></td>
           
          </tr>
         
          <?php } ?>
        </tbody>
      </table>

  <div id="tmppage"> <?php echo $this->element('page')?> </div>

  
  <?php }?>
</div>
<script language="Javascript" type="text/javascript">
function regenerate(invoice_id)
{
	$.get("<?php echo $this->webroot?>pr/pr_invoices/regenerate", {"invoice_id":invoice_id}, function(d){
		alert(d);
		window.location.reload();
	});
	
}
</script>