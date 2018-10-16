<style type="text/css">
.form .value, .list-form .value{text-align:left;}
</style>
<?php $mydata =$p->getDataArray();	$loop = count($mydata);
if($loop==0){?>
<center>
<div class="msg"><?php echo __('no_data_found',true);?></div>
</center>
<?php }else{?>
<div id="toppage"></div>
<div style="width:100%;">
<table class="list nowrap with-fields" style="width: 100%">
	<thead>
		<tr>
<?php 

 	$c=count($show_field_array);
 	//$currency_code='';
 	for ($ii=0;$ii<$c;$ii++){
 		$order_href=$appCommon->show_order($show_field_array[$ii],$appCdr->format_cdr_field($show_field_array[$ii]));
 		
/*
                if($show_field_array[$ii]=='ingress_client_cost'||$show_field_array[$ii]=='egress_cost'||$show_field_array[$ii]=='egress_rate'||$show_field_array[$ii]=='ingress_client_rate'){
 			  $currency_code=$appCommon->show_sys_curr();
 		}else{
 			$currency_code='';
 		} * 
 */
 		echo  "<td rel='8'>&nbsp;&nbsp; ".$order_href."  &nbsp;&nbsp;</td>";

 	}
?>
  </tr>
	</thead>
	<tbody>
		<?php 	 for ($i=0;$i<$loop;$i++) { ?>
      <tr style="color: #4B9100">
  <?php 
  for ($ii=0;$ii<$c;$ii++){
 		$f=$show_field_array[$ii];
 		if($f=='ingress_client_cost'||$f=='egress_cost'||$f=='ingress_client_rate'||$f=='egress_rate'){
 			$field=$appCommon->currency_rate_conversion($mydata[$i][0][$f]);
 		} elseif ($f == 'egress_erro_string') {
                   // echo $mydata[$i][0][$f].'<br />';
 			$field = $appCommon->convert_error($mydata[$i][0][$f]);
 		}
                elseif($f == 'egress_dnis_type') {
                    $field = $appCommon->convert_dnis_type($mydata[$i][0][$f]);
                }
                elseif($f == 'ingress_dnis_type') {
                    $field = $appCommon->convert_dnis_type($mydata[$i][0][$f]);
                }

  		else{
                         //echo 'A'.'<br />';
 			 $field=$appCommon->cutomer_cdr_field($f,$mydata[$i][0][$f]);
 		}
 		if(trim($field)==''){
			echo  "<td  class='in-decimal'  style='text-align:center;color:#6694E3;'><strong  style='color:red;'>".__('Unknown',true)."</strong></td>";
 		}else{	
 			echo  " <td  class='in-decimal'  style='text-align:center;color:#6694E3;white-space:nowrap;overflow:hidden; width:auto;'>".$field ."</td>";}
    	}
         ?>
    </tr>
  <?php }?>
 </tbody>
</table>
</div>
<div id="tmppage"><?php echo $this->element('page');?></div>
<?php }?>