<script src="<?php echo $this->webroot?>js/ajaxTable.js" type="text/javascript"></script>
<div id="title">
   <h1><?php __('Monitoring')?>&gt;&gt;<?php echo $header?></h1>  
		<!--<ul id="title-search">
        	<li>
        		<?php //Pr($searchkey);    //****************************模糊搜索**************************?>
        		<form  action="<?php echo $this->webroot;?>alerts/report"  method="get">
        			<input type="text" id="search-_q_j" class="in-search default-value input in-text defaultText" title="<?php echo __('search')?>..." value="<?php if(isset($searchkey)){echo $searchkey;}else{ echo __('pleaseinputkey');}?>"  onclick="this.value=''" name="searchkey">
        		</form>
        	</li>
       </ul>
       -->
</div>
<div id="container">

<?php 
$res_type = array_keys_value($this->params,'pass.0');
$res_type = empty($res_type) ? 1 : $res_type;
$element_arr = Array('Disabled Ingress Trunk'=>Array('url'=>'alerts/report/1?res_type=1'), 'Disabled Egress Trunk'=>Array('url'=>'alerts/report/1?res_type=2'));
if ($res_type == 1)
{
	$element_arr['Problem Ingress Trunk'] = Array('url'=>'alerts/problem_report/1', 'active'=>true);
	$element_arr['Problem Egress Trunk'] = Array('url'=>'alerts/problem_report/2');
}
else
{
	$element_arr['Problem Ingress Trunk'] = Array('url'=>'alerts/problem_report/1');
	$element_arr['Problem Egress Trunk'] = Array('url'=>'alerts/problem_report/2', 'active'=>true);
}
$element_arr['Priority Trunk'] = Array('url'=>'alerts/priority_report');
$element_arr['Alternative Route Trunk'] = Array('url'=>'alerts/alternative_route_report');
$element_arr['No Destination'] = Array('url'=>'alerts/no_destination_report');
echo $this->element('tabs', array('tabs'=>$element_arr));

?>
    <fieldset class="title-block" id="advsearch" style="margin: 0pt 0pt 10px; width: 100%; display: block;">
        <form method="get" id="queryform">
        <input type="hidden" name="adv_search" value="1" class="input in-hidden">
        <input type="hidden" id="isDelete" name="isDelete" value="0" class="input in-hidden" />
        <table style="width: auto;">
            <tbody>
                <tr>
                    <td>Rule:</td>
                    <td>
                        <select name="s_rule">
                            <option></option>
                            <?php foreach($name_join_arr['rule'] as $k => $v): ?>
                            <option value="<?php echo $k; ?>" <?php if(isset($_GET['s_rule']) && $_GET['s_rule'] == $k) echo 'selected'; ?>><?php echo $v; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                    <td>Carrier:</td>
                    <td>
                        <select name="s_client">
                            <option></option>
                            <?php foreach($name_join_arr['client'] as $k => $v): ?>
                            <option value="<?php echo $k; ?>" <?php if(isset($_GET['s_client']) && $_GET['s_client'] == $k) echo 'selected'; ?>><?php echo $v; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                    <td>Trunk:</td>
                    <td>
                        <select name="s_trunk">
                            <option></option>
                            <?php foreach($name_join_arr['resource'] as $k => $v): ?>
                            <option value="<?php echo $k; ?>" <?php if(isset($_GET['s_trunk']) && $_GET['s_trunk'] == $k) echo 'selected'; ?>><?php echo $v; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                    <td>Begin Date:</td>
                    <td><input type="text" class="wdate input in-text in-input" type="text" onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss',lang:'en'})" name="start_date" value="<?php echo isset($_GET['start_date']) ? $_GET['start_date'] : ''; ?>" /></td>
                    <td>End Date:</td>
                    <td><input type="text" class="wdate input in-text in-input" type="text" onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss',lang:'en'})" name="end_date" value="<?php echo isset($_GET['end_date']) ? $_GET['end_date'] : ''; ?>" /></td>
                    <td><input type="submit" value="Submit" />&nbsp;<input type="button" id="deleteall" value="Delete All" /></td>
                </tr>
            </tbody>
        </table>
        </form>
    </fieldset>
<?php 			$mydata =$p->getDataArray();
			$loop = count($mydata); 
			if(empty($mydata)){
			?>
<div class="msg"><?php echo __('no_data_found',true);?></div>
<?php }else{

?>
<div id="toppage"></div>
<table class="list">
<!--
<col width="9%">
<col width="9%">
<col width="9%">
<col width="9%">
<col width="9%">
<col width="9%">
<col width="9%">
<col width="9%">
<col width="9%">
<col width="9%">
<col width="9%">
-->
<thead>
<tr>
 			<td ><?php echo __('Rule',true);?></td>
		 <td > <?php echo __('Trunk',true); ?>  </td>
                 <td > <?php echo 'Is Disabled'; ?>  </td>
		 <td > <?php echo __('Host'); ?>  </td>
		 <td > <?php echo __('action',true);?>  </td>
		 <td > <?php echo __('Executing Time'); ?>  </td>
		 <td > <?php echo __('Code'); ?>  </td>
		 <td > <?php echo __('Old Priority'); ?>  </td>
		 <td > <?php echo __('New Priority'); ?>  </td>
		</tr>
</thead>
<tbody>
		<?php 

			for ($i=0;$i<$loop;$i++){
		?>
		<tr class="row-1">
		  <td > <?php echo $mydata[$i][0]['rule_name']; ?>  </td>
		  <td > <?php echo $mydata[$i][0]['alias']; ?>  </td>
                  <td > <?php echo $mydata[$i][0]['bool']; ?>  </td>
		 <td > <?php echo $mydata[$i][0]['host_id']==0?'All':$mydata[$i][0]['host_id']; ?>  </td>
		 <td > <?php echo !empty($name_join_arr['action_info'][$mydata[$i][0]['alert_action_id']]) ? $name_join_arr['action'][$mydata[$i][0]['alert_action_id']] : ''; ?>  </td>
		 <td > <?php echo $mydata[$i][0]['event_time']; ?>  </td>
		 
		 <td> <?php echo !empty($mydata[$i][0]['route_strategy_id']) && !empty($name_join_arr['route'][$mydata[$i][0]['route_strategy_id']]['digits']) ? $name_join_arr['route'][$mydata[$i][0]['route_strategy_id']]['digits'] : ''; ?>  </td>	
		 <td > <?php echo $mydata[$i][0]['old_priority']; ?>  </td>
		 <td > <?php echo $mydata[$i][0]['new_priority']; ?>  </td>	
		</tr>
			<?php }?>
		</tbody>
		</table>
	</div>
	<div>
<div id="tmppage">

<?php echo $this->element('page');?>



</div>

<?php }?>
</div>

<script type="text/javascript">
$(function() {
    $('#deleteall').click(function() {
        $('#isDelete').val('1');
        $('#queryform').submit();
    });
});
</script>