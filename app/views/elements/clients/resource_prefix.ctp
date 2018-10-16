<a id="add_resource_prefix" onclick="return false;" href="#">
   <img  src="<?php echo $this->webroot?>images/add.png"> <?php echo __('Add Resource Prefix',true);?>
</a>
<table class="list list-form"  id="resource_table">
<thead>
<tr>
   <td> 
     <?php echo __('id',true);?>
   </td>
   <td> 
     <?php echo __('Rate Table',true);?>
   </td>
   <td><?php echo __('Route Plan',true);?></td>
   <td> <?php echo __('tech_prefix',true);?></td>
   <td class="last"><?php echo __('action',true);?></td>
</tr>
</thead>
<tbody>

	<tr id="mb" >
		<td>
		<?php #echo $resouce[0]['id'] ?>
		<input type="hidden" value="" name="resource[id][]"></input>
		</td> 
		<td>
    <?php #echo $xform->input('tech_prefix',Array('name'=>'resource[tech_prefix]','options'=>""))?>
      <select id="ratetable"  name="resource[rate_table_id][]" >
          <?php foreach ($rate_table as $value ){ ?>
            <option value="<?php echo $value[0]['id']?>" ><?php echo $value[0]['name']?></option>
         <?php }?>
     </select>
     <img id="addratetable" style="cursor:pointer;" src="<?php echo $this->webroot?>images/add.png" onclick="showDiv('pop-div','500','200','<?php echo $this->webroot?>clients/addratetable');" />               
   </td> 
   <td>
     <select id="routeplan" name="resource[route_strategy_id][]">
      <?php foreach ($rout_list as $value ){ ?>
        <option value="<?php echo $value[0]['id'] ?>"><?php echo $value[0]['name'] ?></option>
      <?php } ?>
     </select>    
       <img id="addrouteplan" style="cursor:pointer;" src="<?php  echo $this->webroot?>images/add.png"  onclick="showDiv('pop-div','500','200','<?php echo $this->webroot?>clients/addroutingplan');"/>
   </td>  
   <td >
     <input type="text" name="resource[tech_prefix][]" />  
   </td>  
    <td class="last">
       <a  title="Delete" onclick="$(this).closest('tr').remove();">
         <img alt=""  src="<?php echo $this->webroot ?>images/delete.png" style="display:inline">
       </a>  
   </td>
   
	</tr>
</tbody>
</table>
<script type="text/javascript" src="<?php echo $this->webroot?>js/jquery.livequery.js"></script>
<script type="text/javascript">
<!--
jQuery(document).ready(
	function(){
		   var mb=jQuery('#mb').remove();
	    jQuery('#add_resource_prefix').click(function(){
	           mb.clone(true).appendTo('#resource_table tbody');
	           return false;
	    });
});
//-->
$(document).ready(function() {
    $('#addrouteplan').live('click', function() {
        $(this).prev().addClass('clicked');
       // window.open('<?php echo $this->webroot?>clients/addroutingplan', 'addroutingplan',    'height=600,width=600,top=0,left=0,toolbar=no,menubar=no,scrollbars=no, resizable=no,location=no, status=no');
    });
    
    $('#addratetable').live('click', function() {
        $(this).prev().addClass('clicked');
        //window.open('<?php echo $this->webroot?>clients/addratetable', 'addratetable',        'height=800,width=1000,top=0,left=0,toolbar=no,menubar=no,scrollbars=no, resizable=no,location=no, status=no');
    });
    
    
});
 
 
 function test(name) {
    $('#routeplan').livequery(function() {  
           var $routeplan = $(this);
           $.getJSON('<?php echo $this->webroot ?>clients/getrouteplan', function(data) {
                    $.each(data, function(idx, item) {
                        var option = $("<option value='" + item['id'] + "'>" + item['name'] + "</option>");
                        if($routeplan.hasClass('clicked')) {
                            if(item['name'] == name) {
                                option.attr('selected','selected');
                            }
                        }
                        $routeplan.append(option);
                    });
                   $routeplan.removeClass('clicked');
            });  
            
    });
 
}

function test2(id) {
  $('#ratetable').livequery(function() {
      var $ratetable = $(this);
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

 function test4(name) {
    $('#routeplan').livequery(function() {  
           var $routeplan = $(this);
           $.getJSON('<?php echo $this->webroot ?>clients/getrouteplan', function(data) {
                    $.each(data, function(idx, item) {
                        var option = $("<option value='" + item['id'] + "'>" + item['name'] + "</option>");
                        if($routeplan.hasClass('clicked')) {
                            if(item['name'] == name) {
                                option.attr('selected','selected');
                            }
                        }
                        $routeplan.append(option);
                    });
                   $routeplan.removeClass('clicked');
            });  
            
    });
 
}
</script>







