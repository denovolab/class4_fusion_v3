<script src="<?php echo $this->webroot?>js/ajaxTable.js" type="text/javascript"></script>
<div id="title">
   <h1><?php __('Monitoring')?>&gt;&gt;<?php echo __('Condition')?></h1>  
		<ul id="title-search">
        	<li>
        		<?php //Pr($searchkey);    //****************************模糊搜索**************************?>
        		<form  action="<?php echo $this->webroot;?>alerts/condition"  method="get">
        			<input type="text" id="search-_q_j" class="in-search default-value input in-text defaultText" title="<?php echo __('search')?>..." value="<?php if(isset($searchkey)){echo $searchkey;}else{ echo __('pleaseinputkey');}?>"  onclick="this.value=''" name="searchkey">
        		</form>
        	</li>
       </ul>
       <ul id="title-menu">
        	<?php  if ($_SESSION['role_menu']['Monitoring']['alerts:condition']['model_w']) {?>
        	<li>
        		<a class="link_btn" title="<?php echo __('createcondition')?>"  href="<?php echo $this->webroot?>alerts/add_condition">
       				<img width="16" height="16" alt="" src="<?php echo $this->webroot?>images/add.png"><?php echo __('createnew')?>
       			</a>
       		</li>
            <?php }?>
			<?php if (isset($edit_return)) {?>
        	<li>
    			<a class="link_back" href="<?php echo $this->webroot;?>alerts/condition">
    				<img width="16" height="16"  alt="" src="<?php echo $this->webroot?>images/icon_back_white.png">
    				&nbsp;<?php echo __('goback')?>
    			</a>
    		</li>
        	<?php }?>
       	</ul>
    </div>
<div id="container">
    <ul class="tabs">
        <li>
            <a href="<?php echo $this->webroot; ?>alerts/rule">
                <img width="16" height="16" src="<?php echo $this->webroot; ?>images/ruler.png">Rule			
            </a>
        </li>
        <li>
            <a href="<?php echo $this->webroot; ?>alerts/action">
                <img width="16" height="16" src="<?php echo $this->webroot; ?>images/action.png">Action			
            </a>
        </li>
        <li class="active">
            <a href="<?php echo $this->webroot; ?>alerts/condition">
                <img width="16" height="16" src="<?php echo $this->webroot; ?>images/condition.png">Condition			
            </a>
        </li>
        <li>
            <a href="<?php echo $this->webroot; ?>alerts/block_ani">
                <img width="16" height="16" src="<?php echo $this->webroot; ?>images/fail.png">Block			
            </a>
        </li>
        <li>
            <a href="<?php echo $this->webroot; ?>alerts/trouble_tickets">
                <img width="16" height="16" src="<?php echo $this->webroot; ?>images/trouble_tickets.png">Trouble Tickets			
            </a>
        </li>
        <li>
            <a href="<?php echo $this->webroot; ?>alerts/trouble_tickets_template">
                <img width="16" height="16" src="<?php echo $this->webroot; ?>images/email.gif">Trouble Tickets Mail Template			
            </a>
        </li>
    </ul> 
<?php 			$mydata =$p->getDataArray();
			$loop = count($mydata); 
			if(empty($mydata)){
			?>
<div class="msg"><?php echo __('no_data_found',true);?></div>
<?php }else{

?>
<div id="toppage"></div>
<table class="list">
<col width="16%">
<col width="18%">
<col width="12%">
<col width="8%">
<thead>
<tr>
 			<td ><?php echo $appCommon->show_order('name',__('Condition Name',true));?> </td>
		 <td > <?php echo __('ACD'); ?>  </td>
		 <td > <?php echo __('ASR'); ?>	</td>
		 <td><?php echo __('Margin');?></td>
                 <td > <?php echo __('ABR'); ?>	</td>
                 <td><?php echo __('Occurence Of A specific ANI'); ?></td>
                 <?php
                 /*
		 <td><?php echo __('Combination');?></td> */?>
		 <td style="width:200px;"><?php echo __('OR / AND');?></td>
                 <td><?php echo __('Update By'); ?></td>
                 <td><?php echo __('Update At'); ?></td>
		 <?php  if ($_SESSION['role_menu']['Monitoring']['alerts:condition']['model_w']) {?><td style="width:100px;"> <?php echo __('action',true);?> </td>
         <?php }?>
		</tr>
	</thead>
	<tbody>
		<?php 

		for ($i=0;$i<$loop;$i++){
		?>
		<tr class="row-1">
		  <td align="center">
			    
                <a title=""  class="link_width"  href="<?php echo $this->webroot?>alerts/add_condition/<?php echo $mydata[$i][0]['id']?>">

					<?php echo $mydata[$i][0]['name']?>
				</a>
                
			</td>
		  <td>
		    <?php
				if($mydata[$i][0]['acd_comparator']==0){
					echo "ACD LESS THAN ".$mydata[$i][0]['acd_value_min'].' min';
				}elseif ($mydata[$i][0]['acd_comparator']==1){
					echo  $mydata[$i][0]['acd_value_min']." LESS THAN ACD LESS THAN ".$mydata[$i][0]['acd_value_max'];
				} else {
                                    echo 'Ignore';
                                }
		    ?>
		</td>
		<td>
		    <?php
				if($mydata[$i][0]['asr_comparator']==0){
					echo "ASR <= ".($mydata[$i][0]['asr_value_min'] * 100).'%';
				}elseif ($mydata[$i][0]['asr_comparator']==1){
					echo  ( "ASR BETWEEN " .$mydata[$i][0]['asr_value_min'] * 100)." AND ".($mydata[$i][0]['asr_value_max'] * 100)."%";
				} else {
                                    echo 'Ignore';
                                }
		    ?>
		</td>
		<td>
		    <?php
				if($mydata[$i][0]['margin_comparator']==0){
					echo "Margin <= ".($mydata[$i][0]['margin_value_min'] * 100).'%';
				}elseif ($mydata[$i][0]['margin_comparator']==1){
					echo  ("Margin BETWEEN ".$mydata[$i][0]['margin_value_min'] * 100)." AND ".($mydata[$i][0]['margin_value_max'] * 100)."%";
				} else {
                                    echo 'Ignore';
                                }
		    ?>
		</td>
                <td>
		    <?php
				if($mydata[$i][0]['abr_comparator']==0){
					echo "ABR <= ".($mydata[$i][0]['abr_value_min'] * 100).'%';
				}elseif ($mydata[$i][0]['abr_comparator']==1){
					echo  ("ABR BETWEEN ".$mydata[$i][0]['abr_value_min'] * 100)." AND ".($mydata[$i][0]['abr_value_max'] * 100)."%";
				} else {
                                    echo 'Ignore';
                                }
		    ?>
		</td>
                <td>
		    <?php
				if($mydata[$i][0]['special_ani_comparator']==0){
					echo "ANI >= ".($mydata[$i][0]['special_ani_value'] );
				}elseif ($mydata[$i][0]['special_ani_comparator']==1){
					echo "ANI <= ".($mydata[$i][0]['special_ani_value'] );
				} else {
                                    echo 'Ignore';
                                }
		    ?>
		</td>
		<td>
		<?php 
   if ($mydata[$i][0]['for_all'] == 0)
   		{
   		if($mydata[$i][0]['acd_comparator']==0){
					echo "ACD LESS THAN ".$mydata[$i][0]['acd_value_min'].' min';
				}elseif($mydata[$i][0]['acd_comparator']==1){
					echo  "ACD BETWEEN ".$mydata[$i][0]['acd_value_min']."% AND ".$mydata[$i][0]['acd_value_max'];
				} else {
                                        echo 'Ignore';
                                }
				echo "<br />or<br />";
   		if($mydata[$i][0]['asr_comparator']==0){
					echo "ASR <= ".($mydata[$i][0]['asr_value_min'] * 100).'%';
				}elseif($mydata[$i][0]['asr_comparator']==1){
					echo  ("ASR BETWEEN ".$mydata[$i][0]['asr_value_min'] * 100)."% AND ".($mydata[$i][0]['asr_value_max'] * 100)."%";
				} else {
                                        echo 'Ignore';
                                }
				echo "<br />or<br />";
   		if($mydata[$i][0]['margin_comparator']==0){
					echo "Margin <= ".($mydata[$i][0]['margin_value_min'] * 100).'%';
				}elseif($mydata[$i][0]['margin_comparator']==1){
					echo  ("Margin BETWEEN ".$mydata[$i][0]['margin_value_min'] * 100)."% AND ".($mydata[$i][0]['margin_value_max'] * 100)."%";
				} else {
                                        echo 'Ignore';
                                }
   		}
   		?>
		</td>
                <td>
                    <?php echo $mydata[$i][0]['update_by'] ?>
                </td>
                <td>
                    <?php echo $mydata[$i][0]['update_at'] ?>
                </td>
        <?php  if ($_SESSION['role_menu']['Monitoring']['alerts:condition']['model_w']) {?>
		<td> 
	   <?php	#操作 ?>
	   <a style="margin-left:15px;" href="<?php echo $this->webroot?>alerts/add_condition/<?php echo $mydata[$i][0]['id'] ?> " title="Edit"  >
	     <img src="<?php echo $this->webroot?>images/editicon.gif" /> 
	   </a>	
        <a style="margin-left:15px;" href="#" title="Delete" control="<?php echo $mydata[$i][0]['id']?>" class="delete">
           <img src="<?php echo $this->webroot ?>images/delete.png "/>
        </a>

		</td>
        <?php }?>
		</tr>
			<?php }?>
		</tbody>
		</table>
	</div>
<div>
<div id="tmppage">

<?php echo $this->element('page');?>



</div>

<?php }?>
</div>

<script type="text/javascript">
$(function() {
    $('.delete').click(function() {
        var $this = $(this);
        var condition_id = $this.attr('control');
        $.ajax({
            url : '<?php echo $this->webroot; ?>alerts/condition_used/' + condition_id,
            type : 'GET',
            dataType : 'json',
            success : function(data) {
                var result = false;
                if(data.length > 0) {
                    result = window.confirm("The Math Condition is being used by the following Rules:\n" + data.join(', ') + "\nDeleting this Match Condition will causes the above rules to be removed.");
                } else {
                    result = window.confirm("Are your sure?");
                }
                if(result) 
                    window.location.href = "<?php echo $this->webroot; ?>alerts/ex_dele_condititon/" + condition_id;
                else
                    return false;
            }
        });
    });
});
</script>