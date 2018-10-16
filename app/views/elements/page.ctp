<div class="xpage">
  <ul class="list-meta" id="page" style="display:block;">
    <li class="list-meta-plist numbers" id="allpages">
      <?php 
  	$last=(int)$p->getTotalPages();
   $web_root=$this->webroot;
   $base_href = $appCommon->base_href();
		$request_string = $appCommon->_request_string(array('page','size'));
		$req_str=!empty($request_string)?"&{$request_string}":'';
   $size=(int)$p->getPageSize();
   $curr=(int)$p->getCurrPage();#当前页
   $prev=$curr-1;
   $next=($curr+1)>$last?$last:($curr+1);
		
   $first_page= <<<EOD
   <ul class="pagination">
  <li><a id="first_page" class="page-first" href="{$web_root}{$base_href}?page=1&size={$size}{$req_str}"><img src="{$this->webroot}images/btn_first_active.jpg"/></a></li>
EOD;
   $prev_page= <<<EOD
   		<li><a id="prev_page" class="page-prev"href="{$web_root}{$base_href}?page={$prev}&size={$size}{$req_str}"><img src="{$this->webroot}images/btn_prev_active.jpg"/></a></li>
EOD;
   $next_page= <<<EOD
   	<li> <a id="next_page" class="page-next" href="{$web_root}{$base_href}?page={$next}&size={$size}{$req_str}"><img src="{$this->webroot}images/btn_next_active.jpg"/></a></li>
EOD;
   	
   $last_page= <<<EOD
   		<li> 	<a id="last_page" class="page-last"	href="{$web_root}{$base_href}?page={$last}&size={$size}{$req_str}"><img src="{$this->webroot}images/btn_last_active.jpg"/></a></li>
EOD;
   
      echo $first_page;
      echo $prev_page;
   	
   	if ($p->getTotalPages() >= 10){?>
      <?php

				$page_code=$curr;
				$page_code_t=0;
			if($curr>$last-10){
			$page_code_t=$last-10;
			
	
}
				
   for ($i = 0;$i<10;$i++) {
   
						if($curr<=$last-10){
	   			 $page_code=$curr+$i;
	
	
}else{
	
	$page_code=$i+1+$page_code_t;
	
}

if($page_code>$last){
	break;
	
}
						$k=$page_code; 
   				$style='class="page"';
   				if($k==$curr){	$style='class="page active"'."style='color:red'";}
   				$page_href= <<<EOD
				<li>	<a {$style}		id="p{$page_code}"		href="{$web_root}{$base_href}?page={$k}&size={$size}{$req_str}">{$page_code}   </a>	</li>
				
EOD;
echo $page_href;
   }?>
      <?php } else

   	
   	
   	{
   		?>
      <?php
   			for ($i = 0;$i<(int)$p->getTotalPages();$i++) {
   				$k=$i+1; 
   					$style='class="page"';
   				if($k==$curr){
   					$style='class="page active"'."style='color:red'";
   				}
   				$page_href= <<<EOD
				<li>	<a   {$style}   id = "p{$k}"			href="{$web_root}{$base_href}?page={$k}&size={$size}{$req_str}" >{$k}</a></li>
EOD;

   				echo $page_href;
   				?>
      <?php }
   	
   	}
   	
   	echo $next_page;
      echo $last_page;
	  $actionurl =
"{$web_root}{$base_href}?".(urldecode($request_string));
   	?>
  </ul>
  </li>
  <li class="list-meta-ipp">Rows: <span><?php echo (int)$p->getPageSize()?></span></li>
  <li class="list-meta-ippa"><input type="hidden" id="page_url" value="<?php echo $actionurl;?>" />
    <form   action="" method="get">
      Rows
      <select name="size" class="input in-select" onchange="">
        <option value="10">10</option>
        <option value="15">15</option>
        <option value="20">20</option>
        <option value="35">35</option>
        <option value="50">50</option>
        <option value="100" selected="selected">100</option>
      </select>
    </form>
  </li>
  <li class="list-meta-pnum pages">Pages: <span><?php echo (int)($p->getTotalPages());?></span></li>
  <li class="list-meta-pgo inputPages">
    <form id="gopageform" name="gopageform"  method="get" onsubmit="gopage_form();return false;">
      <span style="float:left; height:18px; line-height:18px; display:block;">Go2:</span>
      <input type="text" style="height:13px float:left;" id="gopage" value="" name="page" class="input in-text in-input">
      <input type="hidden" name="size" id="pagesize"/>
    </form>
  </li>

    
  </ul>
</div>
<script type="text/javascript">
jQuery(function($) {
    $('select[name=size] option[value=<?php echo (int)$p->getPageSize()?>]').attr('selected','selected');
});
</script>
