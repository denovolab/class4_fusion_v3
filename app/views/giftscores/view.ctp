<script src="<?php echo $this->webroot?>js/ajaxTable.js" type="text/javascript"></script>
<div id="title">
            <h1>
      <?php echo __('pointchangeamount')?>     
  </h1>
<ul id="title-search"><!--
        <li>
        <?php //****************************模糊搜索**************************?>
        <form  action="<?php echo $this->webroot?>blocklists/view"  method="post">
        <input type="text" id="search-_q" class="in-search default-value input in-text defaultText" title="<?php echo __('search')?>..." 
        value="<?php if(isset($searchkey)){echo $searchkey;}else{ echo __('pleaseinputkey');}?>"  onclick="this.value=''" name="searchkey">
        </form>
        </li>
        
        --><!--
        <li title="<?php echo __('advancedsearch')?> »" onclick="advSearchToggle();" id="title-search-adv" style="display: list-item;"></li>
    --></ul>
    
        <ul id="title-menu">
        
        <li>
  	 		<a class="link_back" href="<?php echo $this->webroot?>/salestrategs/view">
  	 			<img width="10" height="5" src="<?php echo $this->webroot?>images/icon_back_white.png" alt="">
  	 			<?php echo __('goback',true);?>  	 		</a>
  	 </li>
        
  
     
       <li><a class="link_btn"  href="<?php echo $this->webroot?>giftscores/add/<?php echo $id?>">
       <img width="16" height="16" alt="" src="<?php echo $this->webroot?>images/add.png"><?php echo __('createnew')?></a></li><!--
       <li><a href="<?php echo $this->webroot?>/clients/view"><img width="16" height="16" alt="" src="<?php echo $this->webroot?>images/list.png"> DL History</a></li>
       
        --></ul>
        

    </div>

<div id="container">

	<?php //*********************  条件********************************?>
<fieldset class="title-block" id="advsearch">
	<?php echo $form->create ('Salestrategy', array ('action' => 'view' ,'onsubmit'=>""));?>
<table>
<tbody>

<tr>
    <td><label><?php echo __('egress')?>:</label>
        
   		<?php echo $form->input('engress_res_id',  
 		array(   'options'=>$egress,'empty'=>__('pleaseselectengress',true),'label'=>false ,
 	
 		'div'=>false,'type'=>'select'));?>
		
    

    </td>
    <td><label><?php echo __('ingress')?>:</label>
    
	    		<?php echo $form->input('ingress_res_id',
 		array('options'=>$ingress,'empty'=>__('All',true),'label'=>false ,
 		
 		'div'=>false,'type'=>'select'));?>
    </td><!--
    <td><label><?php echo __('clientname')?>:</label>
    
					    		<?php 
					    		echo $form->input('client_id',
 		array('options'=>$client,'label'=>false ,
 		
 		'empty'=>__('All',true),'onchange'=>'ajax_ingress("'.$this->webroot.'",this.value)',
 		
 		'div'=>false,'type'=>'select'));?>

    </td>
    --><td><label><?php echo __('prefix')?>:</label>
    
       		<?php echo $form->input('digit',
 		array('label'=>false ,'div'=>false,'type'=>'text'));?>
    
    </td>
<td class="buttons"><input type="submit" class="input in-submit" value="Search"></td>
</tr>
</tbody></table>
<?php echo $form->end();?></fieldset>

	<?php //*********************查询条件********************************?>

		<div id="toppage"></div>
<table class="list">
<col width="16%">
<col width="18%">
<col width="12%">




<thead>
<tr>

  
    <td ><?php echo __('bonus')?>(<?php __('a')?>)</td>
     <td ><?php echo __('gift_amount')?>(<?php __('money')?>)</td>
     

    <td  class="last"><?php echo __('action')?></td>
    
  
</tr>
 
</thead>
<tbody>




	<?php 
					$mydata =$p->getDataArray();
					$loop = count($mydata); 
					for ($i=0;$i<$loop;$i++) {?>
<tr class="row-1">
<td>
<?php  if(empty($mydata[$i][0]['bonus_credit'])){echo "0";}else{echo $mydata[$i][0]['bonus_credit'];} ?>
</td>
<td><?php   $my_pi = number_format($mydata[$i][0]['gift_amount'], 3);  echo  $my_pi;?></td>



      <td class="last">
      <a title="<?php echo __('edit')?>"   style="float: left;margin-left: 40px;" href="<?php echo $this->webroot?>giftscores/edit/<?php echo $mydata[$i][0]['sales_strategy_points_id']?>/<?php echo $id?>">
      <img width="16" height="16" src="<?php echo $this->webroot?>images/editicon.gif"></a>
          <a title="<?php echo __('del')?>"  onclick="return confirm('<?php echo __('confirmdel')?>');" 
    
     href="<?php echo $this->webroot?>giftscores/del/<?php echo $mydata[$i][0]['sales_strategy_points_id']?>/<?php echo $id?>"><img width="16" height="16" src="<?php echo $this->webroot?>images/delete.png"></a>
     
     
      
      </td>
  


                
  

</tr>


	<?php }?>

</tbody><tbody>
</tbody></table>



</div>
<div>
<div id="tmppage">
<?php echo $this->element('page');?>


</div>
</div>

<div id="livemargins_control" style="position: absolute; display: none; z-index: 9999;"><img width="77" height="5" style="position: absolute; left: -77px; top: -5px;" src="chrome://livemargins/skin/monitor-background-horizontal.png">	
<img style="position: absolute; left: 0pt; top: -5px;" src="chrome://livemargins/skin/monitor-background-vertical.png">
	<img style="position: absolute; left: 1px; top: 0pt; opacity: 0.5; cursor: pointer;" onmouseout="this.style.opacity=0.5" onmouseover="this.style.opacity=1" src="chrome://livemargins/skin/monitor-play-button.png" id="monitor-play-button"></div>