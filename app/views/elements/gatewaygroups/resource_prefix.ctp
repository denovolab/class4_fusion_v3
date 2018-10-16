<?php  if ($_SESSION['role_menu']['Routing']['gatewaygroups:view']['model_w']) {?><a id="add_resource_prefix" onclick="return false;" href="#">
   <img src="<?php echo $this->webroot?>images/add.png"> <?php echo __('Add Resource Prefix',true);?>
</a>
<?php }?>
<table class="list list-form"  id="resource_table" >
<thead>
<tr>
<!--   <td> 
     ID
   </td>-->
   <td style="width:25%;"> 
     <?php echo __('Rate Table',true);?>
   </td>
   <td style="width:25%;"><?php echo __('Route Plan',true);?></td>
   <td style="width:25%;"><?php echo __('Tech Prefix',true);?></td>
   <?php  if ($_SESSION['role_menu']['Routing']['gatewaygroups:view']['model_w']) {?><td><?php echo __('action',true);?></td><?php }?>
</tr>
</thead>
<tbody>
<?php  
$data_list=array();
$data_list= isset($resouce_prefix_list)?$resouce_prefix_list:$data_list;
 foreach($data_list as $key=>$resouce){
?>
	<tr>
    
<!--		<td>
		<?php echo $resouce[0]['id'] ?>
		</td> -->
		<input type="hidden" value="<?php echo $resouce[0]['id'] ?>" name="resource[id][]"/>
		<td>
    <?php #echo $xform->input('tech_prefix',Array('name'=>'resource[tech_prefix]','options'=>""))?>
    <?php echo $form->input('currency_id',
 		array('options'=>$rate,'label'=>false ,'div'=>false,'type'=>'select','autocomplete'=>"off",'id'=>'reource_prefix_rate_'.$key,'name'=>"resource[rate_table_id][]",'selected'=>$resouce[0]['rate_table_id'],'style'=>'width:200px'));?>
    
      
   </td> 
   <td>
     <select name="resource[route_strategy_id][]"  autocomplete="off" style="width:200px;">
      <?php foreach ($rout_list as $value ){ ?>
        <option value="<?php echo $value[0]['id'] ?>" <?php echo $value[0]['id']==$resouce[0]['route_strategy_id']?'selected':''; ?>><?php echo $value[0]['name'] ?></option>
      <?php } ?>
     </select>
    <?php #echo $xform->input('route_strategy_id',Array('name'=>'resource[route_strategy_id]','options'=>""))?>      
   </td>  
   <td class="value">
     <?php #echo $xform->input('rate_table_id',Array('name'=>'resource[rate_table_id]','options'=>""))?>
     <input type="text" class="input in-input in-text" name="resource[tech_prefix][]" value="<?php echo $resouce[0]['tech_prefix'] ?>"/>
   </td>  
    <?php  if ($_SESSION['role_menu']['Routing']['gatewaygroups:view']['model_w']) {?><td>
       <a href="###" title="Delete" rel="delete" onClick="dele_tr('<?php echo $this->webroot?>/gatewaygroups/delete_resource_prefix/<?php echo $resouce[0]['id'] ?>',this,'Resource Prefix <?php echo $resouce[0]['tech_prefix'] ?>')">
         <img alt="" src="<?php echo $this->webroot ?>images/delete.png" stlye="display:inline">
       </a>  
   </td>
   <?php }?>
   
	</tr>
	<?php } ?>
	<tr id="mb" >
<!--        <td>
		<?php #echo $resource[0]['id'] ?>
		
		</td> -->
                <input type="hidden" value="" name="resource[id][]"/>
		<td>
    <?php #echo $xform->input('tech_prefix',Array('name'=>'resource[tech_prefix]','options'=>""))?>
      <select id="ratetable"   name="resource[rate_table_id][]"  style="width:200px;">
          <?php foreach ($rate_table as $value ){ ?>
            <option value="<?php echo $value[0]['id']?>" ><?php echo $value[0]['name']?></option>
         <?php }?>
     </select>
                     <img id="addratetable" style="cursor:pointer;" src="<?php echo $this->webroot?>images/add.png"  onclick="showDiv('pop-div','500','200','<?php echo $this->webroot?>clients/addratetable');" />
   </td> 
   <td>
     <select name="resource[route_strategy_id][]" style="width:200px;" >
      <?php foreach ($rout_list as $value ){ ?>
        <option value="<?php echo $value[0]['id'] ?>"><?php echo $value[0]['name'] ?></option>
      <?php } ?>
     </select>
    <?php #echo $xform->input('route_strategy_id',Array('name'=>'resource[route_strategy_id]','options'=>""))?>      
   </td>  
   <td class="value">
     <?php #echo $xform->input('rate_table_id',Array('name'=>'resource[rate_table_id]','options'=>""))?>
     <input type="text"  class="input in-input in-text" id="tech_prefix" name="resource[tech_prefix][]" />   
   </td>  
   <?php  if ($_SESSION['role_menu']['Routing']['gatewaygroups:view']['model_w']) {?> <td>
       <a  title="Delete" onclick="$(this).closest('tr').remove();">
         <img alt=""  src="<?php echo $this->webroot ?>images/delete.png" style="display:inline">
       </a>  
   </td>
   <?php }?>
   
	</tr>
</tbody>
</table>
<script type="text/javascript" src="<?php echo $this->webroot?>js/jquery.livequery.js"></script>
<script type="text/javascript">

jQuery(document).ready(
	function(){
		   var mb=jQuery('#mb').remove();
	    jQuery('#add_resource_prefix').click(function(){
	           mb.clone(true).appendTo('#resource_table tbody');
	           return false;
	    });
            
        $('#addratetable').live('click', function() {
            $(this).prev().addClass('clicked');
           // window.open('<?php echo $this->webroot?>clients/addratetable', 'addratetable', 'height=800,width=1000,top=0,left=0,toolbar=no,menubar=no,scrollbars=no, resizable=no,location=no, status=no');
         });
});

 function test2(id) {
  $('#ratetable').livequery(function() {
      var $ratetable = $(this);
      $ratetable.empty();
      $.getJSON('<?php echo $this->webroot ?>clients/getratetable', function(data) {
          $.each(data, function(idx, item) {
            var option = $("<option value='" + item['id'] + "'>" + item['name'] + "</option>");
            if($ratetable.hasClass('clicked')) {
                if(item['id'] == id) {
                    option.attr('selected','selected');
                }
            }
            $ratetable.append(option);
         });
        $ratetable.removeClass('clicked');
      })
  }); 
}


function test3(id) {
      var $ratetable = $("#ratetable");
      $.getJSON('<?php echo $this->webroot ?>clients/getratetable', function(data) {
          $.each(data, function(idx, item) {
            var option = $("<option value='" + item['id'] + "'>" + item['name'] + "</option>");
                if(item['id'] == id) {
                    option.attr('selected','selected');
                }
            $ratetable.append(option);
         });
      }) 
}
</script>