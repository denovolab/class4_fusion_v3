<script src="<?php echo $this->webroot?>js/ajaxTable.js" type="text/javascript"></script>
<div id="title">
            <h1>
        <span></span>
      <?php echo __('egresscall')?>     

                        </h1>
        

    
<ul id="title-search">
        <li>
        <?php //****************************模糊搜索**************************?>
        <form  action="<?php echo $this->webroot?>complains/view"  method="post">
        <input type="text" id="search-_q" class="in-search default-value input in-text defaultText" title="<?php echo __('search')?>..." 
        value="<?php if(isset($searchkey)){echo $searchkey;}else{ echo __('pleaseinputkey');}?>"  onclick="this.value=''" name="searchkey">
        </form>
        </li>
        
        
        <li title="<?php echo __('advancedsearch')?> »" onclick="advSearchToggle();" id="title-search-adv" style="display: list-item;"></li>
   </ul>
    
        <ul id="title-menu">
      <li><a href="<?php echo $this->webroot?>gatewaygroups/egress_report"  style="width: 125px;">
      <img width="16" height="16" alt="" src="<?php echo $this->webroot?>/images/list.png"> 
      <?php __('returnrouteusage')?>
      </a></li>
     
       </ul>
        

    </div>

<div id="container">

	<?php //*********************  条件********************************?>
<fieldset class="title-block" id="advsearch">
	<?php echo $form->create ('Complain', array ('action' => 'view' ,'onsubmit'=>""));?>
<table>
<tbody>

<tr>
    <td><label><?php echo __('titile')?>:</label>
        
   		<?php echo $form->input('title',  
 		array(   'label'=>false ,
 	
 		'div'=>false,'type'=>'text'));?>
		
    

    </td>
    <td><label><?php echo __('createtime')?>:</label>
    
	    		<?php echo $form->input('ingress_res_id',
 		array('options'=>$ingress,'empty'=>__('All',true),'label'=>false ,
 		
 		'div'=>false,'type'=>'select'));?>
    </td><!--
    <td><label><?php echo __('modifytime')?>:</label>
    
					    		<?php 
					    		echo $form->input('client_id',
 		array('options'=>$client,'label'=>false ,
 		
 		'empty'=>__('All',true),'onchange'=>'ajax_ingress("'.$this->webroot.'",this.value)',
 		
 		'div'=>false,'type'=>'select'));?>

    </td>
    --><td><label><?php echo __('modifytime')?>:</label>
    
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
<col width="10%">
<col width="10%">
<col width="12%">
<col width="12%">
<col width="12%">


<thead>
<tr>

  
    <td ><?php echo __('ani')?></td>
    <td ><?php echo __('dnis')?></td>
     <td ><?php echo __('calleeani')?></td>
      <td ><?php echo __('calleednis')?></td>
      <td ><?php echo __('start_date')?></td>

    
  
</tr>
 
</thead>
<tbody>




	<?php 
					$mydata =$p->getDataArray();
					$loop = count($mydata); 
					for ($i=0;$i<$loop;$i++) {?>
<tr class="row-1">
<td><?php  echo $mydata[$i][0]['origination_source_number'];?></td>
<td><?php  echo $mydata[$i][0]['origination_destination_number'];?></td>
<td><?php  echo $mydata[$i][0]['termination_source_number'];?></td>
<td><?php  echo $mydata[$i][0]['termination_destination_number'];?></td>
<td><?php  echo $mydata[$i][0]['answer_time_of_date'];?></td>



  


                
  

</tr>


	<?php }?>

</tbody><tbody>
</tbody></table>
<div id="tmppage">
<?php echo $this->element('page');?>


</div>


</div>
<div>

</div>

