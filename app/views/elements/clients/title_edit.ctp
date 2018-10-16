<div id="title">
	<h1><?php  __('Management')?>&gt;&gt;<?php echo empty($post['Client']['name'])||$post['Client']['name']==''? '':  "Edit  Carrier:<font class='editname' title='Name'>[" .$post['Client']['name']."]</font> " ;?></h1>
    
	<ul id="title-menu">
		<li>
			<?php echo $this->element("xback",Array('backUrl'=>'clients/index'))?>
    	</li>
 	</ul>
</div>