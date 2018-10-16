<div class="xpage">
  <ul class="list-meta xpage">
    <li id="numbers" class="list-meta-plist numbers">
      <ul class="pagination">
        <?php echo htmlspecialchars_decode($xpaginator->first("<img src={$this->webroot}images/btn_first_active.jpg>"));?>
        <?php echo htmlspecialchars_decode($xpaginator->prev("<img src={$this->webroot}images/btn_prev_active.jpg>"));?>
        <?php echo htmlspecialchars_decode($xpaginator->numbers(Array(),true));?>
        <?php echo htmlspecialchars_decode($xpaginator->next("<img src={$this->webroot}images/btn_next_active.jpg>"));?>
        <?php echo htmlspecialchars_decode($xpaginator->last("<img src={$this->webroot}images/btn_last_active.jpg>"));?>
      </ul>
    </li>
    <li id='limit' class="list-meta-ipp limit">Rows: <span><?php echo $xpaginator->limit()?></span></li>
    <li id='inputLimit' class="list-meta-ippa inputLimit"> Rows
      <?php $options=Array(10=>10,15=>15,20=>20,35=>35,50=>50,100=>100)?>
      <?php echo $form->input('size',Array('type'=>'select','class'=>'input','options'=>$options,'div'=>false,'label'=>false,'selected'=>$xpaginator->limit()))?> </li>
    <li id='pages' class="list-meta-pnum pages">Pages: <span><?php echo $xpaginator->xpageCount()//xpageCount 获取分页总页数?></span></li>
    <li id='inputPages' class="list-meta-pgo inputPages"> <span style="float:left; height:18px; line-height:18px; display:block;">Go:</span>
      <input type="text" class="input in-text in-input" name="page" value="1" id="gopage" style="height:13px; float:left;"/>
    </li>
  </ul>
</div>
<script type="text/javascript">

/*change the records and pages*/
jQuery('.xpage .pages').each(function(){
	jQuery(this).click(
		function(){
			jQuery(this).hide();
			jQuery(this).parent().find('.inputPages').show().find('input')[0].focus();
			//.find('input')[0].focus();
		}
	);
});
jQuery('.xpage .inputPages input').blur(
	function(){
		jQuery(this).parent().hide();
		jQuery(this).parent().parent().find('.pages').show();
	}
).keyup(function(e){
	if(e.which==13){
		location='<?php echo $xpaginator->xurl(Array('del'=>Array('page')))?>/page:'+jQuery(this).val()+'?<?php echo $this->params['getUrl']?>';
	}
});
jQuery('.xpage .limit').click(
		function(){
			jQuery(this).hide();
			jQuery(this).parent().find('.inputLimit').show().find('select')[0].focus();
		}
	);
jQuery('.xpage .inputLimit select').blur(
	function(){
		jQuery(this).parent().hide();
		jQuery(this).parent().parent().find('.limit').show();
	}
).change(function(){location='<?php echo $xpaginator->xurl(Array('del'=>Array('limit')))?>/limit:'+jQuery(this).val()+'?<?php echo $this->params['getUrl']?>';});

</script>