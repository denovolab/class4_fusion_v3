                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                       <!--导入所有reoprt页面的input和select样式文件-->
<?php echo $this->element('magic_css');?>

<div id="title">
<?php if ($session->read('login_type') == 3): ?>

     <h1><?php echo __('Reports',true);?>&gt;&gt;<?php echo $cdr_type == 'term_service_buy' ? 'Term. Service Buy' : 'Term. Service Sell';?></h1>   
    <?php else: ?>
<h1><?php __('Statistics')?>&gt;&gt;
 <font class="editname"> </font>
 <?php if($rate_type=='all'){   __('cdrSearchtools'); 
 echo  (empty($name)||$name=='')? "  ":" <font class='editname'>[".$name."]</font>";
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
<?php endif; ?>
</div>
<div id="container">
    <?php if($rate_type != 'spam'): ?>
    
    <?php if ($session->read('login_type') == 3): ?>

        <?php echo $this->element('report/cdr_report/cdr_report_user_tab', array('active' => $cdr_type))?>
    <?php else: ?>

    <ul class="tabs">
        <li class="active">
            <a href="<?php echo $this->webroot; ?>cdrreports/summary_reports">
                <img width="16" height="16" src="<?php echo $this->webroot; ?>images/list.png">
                CDR Search
            </a>
        </li>
        <li>
            <a href="<?php echo $this->webroot; ?>cdrreports/export_log">
                <img width="16" height="16" src="<?php echo $this->webroot; ?>images/export.png">
                CDR Export Log
            </a>
        </li>
        <li>
            <a href="<?php echo $this->webroot; ?>cdrreports/mail_send_log">
                <img width="16" height="16" src="<?php echo $this->webroot; ?>images/menuIcon.gif">
                Mail CDR Log
            </a>
        </li>
    </ul>
    <?php endif; ?>
    
    <?php endif; ?>
    
<div  id="refresh_div">
<?php
if (isset ( $exception_msg ) && $exception_msg) :	?>
	<?php	echo $this->element ( 'common/exception_msg' );?>		
<?php endif;?>
<?php if($show_nodata): ?>
<?php echo $this->element('report/real_period')?>
<?php endif; ?>
<?php echo $this->element('report/cdr_report/cdr_table')?>
</div>
<!--生成图片报表-->
<?php //echo $this->element("report/image_report")?>
<!--//生成图片报表-->
<?php
if($_SESSION['login_type']==1){
        if($rate_type == 'spam') {
            echo $this->element('report/cdr_report/query_box_admin_2');
        } else {
            echo $this->element('report/cdr_report/query_box_admin');
        }
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