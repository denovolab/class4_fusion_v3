<div id="title">
       <h1> <?php __('Switch') ?>&gt;&gt;<?php echo __('Editing rates',true);?>
       
       	<font class="editname">
    						<?php echo empty($name[0][0]['name'])||$name[0][0]['name']==''?'':'['.$name[0][0]['name'].']' ?>
    				</font>&gt;&gt<?php __('Simulate') ?>
       </h1>
        <ul id="title-menu">
    		<li>
    			<a class="link_back" href="javascript:void(0)" onclick="history.go(-1)">
    				<img width="16" height="16" src="<?php echo $this->webroot?>images/icon_back_white.png" alt="">
    				&nbsp;<?php echo __('goback',true);?>    			</a>
    		</li>
  		</ul>


</div>

<div id="container">

<ul class="tabs">
    <li ><a href="<?php echo $this->webroot?>clientrates/view/<?php echo $table_id?>/"><img width="16" height="16" src="<?php echo $this->webroot?>images/menuIcon.gif"> <?php echo __('Rates',true);?></a></li>
    <?php if ($jur_type == 3 || $jur_type == 4): ?>
    <li><a href="<?php echo $this->webroot?>clientrates/view/<?php echo $table_id?>/<?php echo $currency?>/npan"><img width="16" height="16" src="<?php echo $this->webroot?>images/menuIcon.gif"><?php echo __('NPANXX Rate',true);?> </a></li>
    <?php endif; ?>
    <li  class="active"><a href="<?php echo $this->webroot?>clientrates/simulate/<?php echo $table_id?>/"><img width="16" height="16" src="<?php echo $this->webroot?>images/simulate.gif"> <?php echo __('Simulate',true);?></a></li>   
    <li><a href="<?php echo $this->webroot?>clientrates/import/<?php echo $table_id?>"><img width="16" height="16" src="<?php echo $this->webroot?>images/import.png"> <?php echo __('import',true);?></a></li> 
    <li><a href="<?php echo $this->webroot?>downloads/rate/<?php echo $table_id?>"><img width="16" height="16" src="<?php echo $this->webroot?>images/export.png"> <?php echo __('export',true);?></a></li>   

    
    </ul>
<form  id="from" style="margin: 15px 0pt 10px;" method="post" action="<?php echo $this->webroot?>clientrates/simulate/<?php  echo  $table_id?>">
    <h1>
            <input type="button" id="controlladd" value="Add" />
        <?php  if ($_SESSION['role_menu']['Switch']['rates']['model_w']) {?>
            <input type="submit" value="Process" class="input in-submit">
        <?php }?>
    </h1>
<input type="hidden" id="id" value="148" name="id" class="input in-hidden">
<input type="hidden" id="process" value="1" name="process" class="input in-hidden">
<table class="list" id="controltable">
<tbody>
		<tr>

    <td class="label"><?php echo __('Date',true);?>:</td>
    <td class="value" style="width:100px">
	<input type="text" name="date[]" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd'});" value="<?php echo date("Y-m-d") ?>" id="search-now-wDt">
    </td>
    <td clas="label"><?php echo __('Time',true);?>:</td>
    <td class="value" style="width:100px">
        <input type="text" name="time[]" onfocus="WdatePicker({dateFmt:'HH:mm:ss'});" value="00:00:00" />
    </td>
    <td style="width:150px" class="value"><select id="tz" name="tz[]" class="input in-select"><option value="-1200">GMT -12:00</option><option value="-1100">GMT -11:00</option><option value="-1000">GMT -10:00</option><option value="-0900">GMT -09:00</option><option value="-0800">GMT -08:00</option><option value="-0700">GMT -07:00</option><option value="-0600">GMT -06:00</option><option value="-0500">GMT -05:00</option><option value="-0400">GMT -04:00</option><option value="-0300">GMT -03:00</option><option value="-0200">GMT -02:00</option><option value="-0100">GMT -01:00</option><option value="+0000">GMT +00:00</option><option value="+0100">GMT +01:00</option><option value="+0200">GMT +02:00</option><option value="+0300">GMT +03:00</option><option value="+0330">GMT +03:30</option><option value="+0400">GMT +04:00</option><option value="+0500">GMT +05:00</option><option value="+0600">GMT +06:00</option><option value="+0700">GMT +07:00</option><option value="+0800">GMT +08:00</option><option value="+0900">GMT +09:00</option><option value="+1000">GMT +10:00</option><option value="+1100">GMT +11:00</option><option value="+1200">GMT +12:00</option></select></td>
    <td class="label"><?php echo __('ani',true);?>:</td>
    <td class="value" style="width:100px"><input type="text" id="ani"  name="ani[]" class="input in-text"></td>
    <td class="label"><?php echo __('dnis',true);?>:</td>
    <td class="value" style="width:100px"><input type="text" id="dnis"   name="dnis[]" class="input in-text"></td>
    <td class="label"><?php echo __('Duration',true);?>:</td>
    <td class="value" style="width:150px"><input type="text" id="duration" value="60"  name="duration[]"> sec</td>
    
</tr>
</tbody></table>

</form>
  
    
<?php if(isset($data)): ?>

    <table class="list">
        <thead>
            <tr>
                <td><?php echo __('Date',true);?></td>
                <td><?php echo __('ani',true);?></td>
                <td><?php echo __('dnis',true);?></td>
                <td><?php echo __('Rate',true);?></td>
                <td><?php echo __('Cost',true);?></td>
            </tr>
        </thead>
        
        <tbody>
            <?php foreach($data as $val): ?>
            <tr>
                <td><?php echo $val['date']; ?></td>
                <td><?php echo $val['ani']; ?></td>
                <td><?php echo $val['dnis']; ?></td>
                <td><?php echo $val['rate']; ?></td>
                <td><?php echo $val['cost']; ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>    
    
<?php endif; ?>


</div>
<script type="text/javascript">
<!--
   jQuery(document).ready(function(){
     jQuery('#from').submit(function(){

		var ret = true; 
       if(/\D/.test(jQuery('#ani').val())){
					   
		jQuery('#ani').addClass('invalid');
		jQuery.jGrowl('ANI, must be whole number!  ',{theme:'jmsg-error'});
		ret= false;			   
      }
      
      if(/\D/.test(jQuery('#dnis').val())){
					   
		jQuery('#dnis').addClass('invalid');
		jQuery.jGrowl('DNIS, must be whole number!  ',{theme:'jmsg-error'});
		ret= false;			   
      }

       if(/\D/.test(jQuery('#duration').val())){
					   
		jQuery('#duration').addClass('invalid');
		jQuery.jGrowl('Duration, must be whole number!  ',{theme:'jmsg-error'});
		ret= false;			   
      }

	  return ret;
    });
  });

//-->

$(function() {
    $('#controlladd').click(function() {
        var $tr = $('#controltable tbody tr:last').clone(true);
        $tr.find('#tz option[value=<?php echo $dzone; ?>]').attr('selected','selected');
        $tr.appendTo('#controltable tbody');
    });    
    
    $('#tz option[value=<?php echo $dzone; ?>]').attr('selected','selected');
});
</script>





