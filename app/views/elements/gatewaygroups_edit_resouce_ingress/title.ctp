	<div id="title">
   <h1>
       <?php if(isset($_GET['query']['id_clients'])):?><?php echo __('Carrier'); echo ' ['.$c[$_GET['query']['id_clients']].'] ';?><?php else:?><?php echo __('Carrier',true);?> [<?php print($c[array_keys_value($post,'Gatewaygroup.client_id')]); ?>] <?php endif;?>
       &gt;&gt;<?php echo __('edit',true);?> <?php __('Ingress')?> <font  class="editname" title="Name">   <?php echo empty($post['Gatewaygroup']['alias'])||$post['Gatewaygroup']['alias']==''?'':"[".$post['Gatewaygroup']['alias']."]"?> </font>
   
   </h1>
    <ul id="title-menu">
    	<li>
    	
    	<?php $project_name=Configure::read('project_name');
    	if($project_name=='exchange'){
    	?>
			<a class="link_back" href="<?php echo $this->webroot?>gatewaygroups/view_ingress?<?php echo $this->params['getUrl']?>">
				<img width="16" height="16" alt="<?php echo __('goback',true);?>" src="<?php echo $this->webroot?>images/icon_back_white.png"/> <?php echo __('goback',true);?>
			</a>
			
			<?php }else{?>
						<a class="link_back" href="<?php echo $this->webroot?>prresource/gatewaygroups/view_ingress?<?php echo $this->params['getUrl']?>">
				<img width="16" height="16" alt="<?php echo __('goback',true);?>" src="<?php echo $this->webroot?>images/icon_back_white.png"/> <?php echo __('goback',true);?>
			</a>
			<?php }?>
		</li>  		
	</ul>
</div>