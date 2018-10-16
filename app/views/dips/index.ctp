<!--导入所有reoprt页面的input和select样式文件-->
<?php echo $this->element('magic_css');?>

<div id="title">
<h1><?php __('Statistics')?>&gt;&gt;
 <font color= 'red' title="Name"> </font>
 <?php if($rate_type=='all'){  echo "LRN Dipping Record";  
 echo  (empty($name)||$name=='')? "  ":" <font color ='red' title='Name'>[".$name."]</font>";
 }?>
 <?php if($rate_type=='spam'){  
 	        echo "Spam Report ";
           
 }?></h1>
<ul id="title-menu">
<?php echo  $this->element('spam/refresh_select')?>
	<li><a class="link_back" onclick="history.go(-1);"> <img width="16" height="16"
		src="<?php echo $this->webroot?>images/icon_back_white.png" alt="">
	&nbsp;<?php echo __('goback',true);?> </a></li>
   
</ul>
</div>
<div id="container">
<div  id="refresh_div">
<?php
if (isset ( $exception_msg ) && $exception_msg) :	?>
	<?php	echo $this->element ( 'common/exception_msg' );?>		
<?php endif;?>
<?php echo $this->element('report/real_period')?>
<?php echo $this->element('report/cdr_report/cdr_table2')?>
</div>
<!--生成图片报表-->
<?php //echo $this->element("report/image_report")?>
<!--//生成图片报表-->
<?php
if($_SESSION['login_type']==1){
	echo $this->element('report/cdr_report/query_box_admin_1');
}else{
	
	echo $this->element('report/cdr_report/query_box_carrier');
}
?>
<div style="display: none;" id="charts_holder">
<script	type="text/javascript">


//<![CDATA[
function showClients_term ()
{
    ss_ids_custom['client_term'] = _ss_ids_client_term;
    winOpen('<?php echo $this->webroot?>clients/ss_client_term?types=2&type=0', 500, 530);

}

tz = $('#query-tz').val();
function showClients ()
{
    ss_ids_custom['client'] = _ss_ids_client;
    winOpen('<?php echo $this->webroot?>clients/ss_client?types=2&type=0', 500, 530);
}



function repaintOutput() {
    if ($('#query-output').val() == 'web') {
        $('#output-sub').show();
    } else {
        $('#output-sub').hide();
			}
}
repaintOutput();
//]]>
</script></div>


<?php echo  $this->element('spam/refresh_spam_js')?>
</div>