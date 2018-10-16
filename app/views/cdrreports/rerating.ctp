<!--导入所有reoprt页面的input和select样式文件-->
<?php echo $this->element('magic_css');?>
<div id="title">
<h1><?php __('Statistics')?>&gt;&gt;
 <font  class="editname"> </font>
 CDR Rerating</h1>

</div>
 <?php
$url="/".$this->params['url']['url'];
//if($rate_type=='spam'){$url='/cdrreports/summary_reports/spam/';}else{$url='/cdrreports/summary_reports/';}
echo $form->create ('Cdr', array ('type'=>'get','url' => $url ,'id'=>'report_form',
'onsubmit'=>"if ($('#query-output').val() == 'web') loading();"));?>
  <?php echo $appCommon->show_page_hidden();?> <?php echo $this->element('search_report/search_hide_input');?>   
<div id="container">
    <ul class="tabs">
        <li class="active">
            <a href="<?php echo $this->webroot ?>cdrreports/rerating">
                <img width="16" height="16" src="/Class4/images/list.png">Rerate CDR		
            </a>
        </li>
        <li>
            <a href="<?php echo $this->webroot ?>cdrreports/rerating_list">
                <img width="16" height="16" src="/Class4/images/list.png">Rerate Result 		
            </a>
        </li>
    </ul>
    
 
    
<?php
if (isset ( $exception_msg ) && $exception_msg) :	?>
	<?php	echo $this->element ( 'common/exception_msg' );?>		
<?php endif;?>
<?php echo $this->element('report/real_period')?>
<?php   if($action_type=='query')
						{
							echo 	$this->element('report/cdr_report/cdr_table3');
							}
							
				 if($action_type=='Process')
						{
							echo 	$this->element('report/cdr_report/process_table');
					}
							
			if($action_type=='Rerating')
								{
										echo 	$this->element('report/cdr_report/cdr_rerate_table');
						
						} ?>
<?php
	echo $this->element('report/cdr_report/query_box_rerating');

?>
<div style="display: none;" id="charts_holder">
<?php echo $this->element('search_report/search_js_show');?>




</div>
<script type="text/javascript">

jQuery(document).ready(function(){
	 jQuery(#query-src_number,#query-interval_from,#query-interval_to).xkeyvalidate({type:'checkNum'});	
});

</script>

</div>
 <?php echo $form->end();?> 