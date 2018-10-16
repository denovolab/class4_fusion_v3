<script src="<?php echo $this->webroot?>js/ajaxTable.js" type="text/javascript"></script>
<div id="title">
   <h1><?php __('Monitoring')?>&gt;&gt;<?php echo __('No Destination Report')?></h1>  
		<ul id="title-search">
        	<!--<li>
        		<?php //Pr($searchkey);    //****************************模糊搜索**************************?>
        		<form  action="<?php echo $this->webroot;?>alerts/action"  method="get">
        			<input type="text" id="search-_q_j" class="in-search default-value input in-text defaultText" title="<?php echo __('search')?>..." value="<?php if(isset($searchkey)){echo $searchkey;}else{ echo __('pleaseinputkey');}?>"  onclick="this.value=''" name="searchkey">
        		</form>
        	</li>
       </ul>
       <ul id="title-menu">
        	<?php if (isset($edit_return)) {?>
        	<li>
    			<a href="<?php echo $this->webroot;?>alerts/action">
    				<img width="16" height="16"  alt="" src="<?php echo $this->webroot?>images/rerating_queue.png">
    				&nbsp;<?php echo __('gobackall')?>
    			</a>
    		</li>
        	<?php }?>
        	<li>
        		<a title="<?php echo __('creataction')?>"  href="<?php echo $this->webroot?>alerts/add_action">
       				<img width="16" height="16" alt="" src="<?php echo $this->webroot?>images/add.png"><?php echo __('createnew')?>
       			</a>
       		</li>
       --></ul>
    </div>
<div id="container">
<?php 
$event_type = array_keys_value($this->params,'pass.0');
$element_arr = Array('Disabled Ingress Trunk'=>Array('url'=>'alerts/report/1?res_type=1'), 'Disabled Egress Trunk'=>Array('url'=>'alerts/report/1?res_type=2'));
$element_arr['Problem Ingress Trunk'] = Array('url'=>'alerts/problem_report/1');
$element_arr['Problem Egress Trunk'] = Array('url'=>'alerts/problem_report/2');
$element_arr['Priority Trunk'] = Array('url'=>'alerts/priority_report');
$element_arr['No Alternative Trunk Route'] = Array('url'=>'alerts/alternative_route_report');
$element_arr['No Egress Trunk Route'] = Array('url'=>'alerts/no_destination_report', 'active'=>true);
echo $this->element('tabs', array('tabs'=>$element_arr));
?>
<?php 			
			$mydata =$p->getDataArray();
			$loop = count($mydata); 
			if(empty($mydata)){
			?>
<div class="msg"><?php echo __('no_data_found',true);?></div>
<?php }else{

?>
<div id="toppage"></div>
<table class="list">
<col width="25%">
<col width="25%">
<thead>
<tr>
 			<td ><?php __('Name');?> </td>
 			<td ><?php __('code');?> </td>
                        <td ><?php __('Type');?> </td>

		</tr>
</thead>
<tbody>
		<?php 
//var_dump($name_join_arr['route']);
			for ($i=0;$i<$loop;$i++){
		?>
		<tr class="row-1">
		  
		  <td><?php echo $mydata[$i][0]['name']?>			
			</td>	
			<td><?php echo $mydata[$i][0]['digits']?>
			</td>	
                        <td><?php echo $mydata[$i][0]['type']?>
			</td>
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
