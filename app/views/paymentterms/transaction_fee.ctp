<script>
    var str = "";
    function del(id){
        if(confirm("do you want to deleted!")){
            location = "<?php echo $this->webroot?>paymentterms/del_transaction_fee/"+id
        }
    }
    
    
    function upd(obj,id){
        var tr = $(obj).parent().parent().parent();
        str = tr.html();
        tr = tr.get(0);
        
        tr.innerHTML = "<td><input type='text' name='min_rate' value='"+tr.cells[0].innerHTML+"' class='in-text'></td>\n\
                        <td><input type='text' name='max_rate' value='"+tr.cells[1].innerHTML+"' class='in-text'></td>\n\
                        <td><input type='text' name='charge_value' value='"+(tr.cells[2].innerHTML.split('%'))[0]+"' class='in-text'></td>\n\
                        <td><img onclick= \"save(this,"+id+")\" src=\"<?php echo $this->webroot?>images/menuIcon_004.gif\" />\n\
                        <img onclick= \"notEdit(this)\" src=\"<?php echo $this->webroot?>images/delete.png\" /></td>"
        //tr.cells[0].innerHTML = "";
    }
    
    function save (obj,upd_id){
        var min_rate = $($(obj).parent().parent().get(0).cells[0]).find('input').val();
        var max_rate = $($(obj).parent().parent().get(0).cells[1]).find('input').val();
        var charge_value = $($(obj).parent().parent().get(0).cells[2]).find('input').val();
        
        if(min_rate == '' || isNaN(min_rate)){
            jQuery.jGrowl("Min Rate Must be digital",{theme:'jmsg-error'});
            return false;
        }
        if(max_rate == '' || isNaN(max_rate)){
            jQuery.jGrowl("Max Rate Must be digital!",{theme:'jmsg-error'});
            return false;
        }
        if(charge_value == '' || isNaN(charge_value)){
            jQuery.jGrowl("Charge Value Must be digital!",{theme:'jmsg-error'});
            return false;
        }
        
        if(min_rate > max_rate || min_rate == max_rate){
            jQuery.jGrowl("Min Rate must be less than Max Rate",{theme:'jmsg-error'});
            return false;
        }
        
        if(charge_value < 0 || charge_value > 100){
            jQuery.jGrowl("Charge Value Must be more than 100 less than zero!",{theme:'jmsg-error'});
            return false;
        }
        
        $.post("<?php echo $this->webroot?>paymentterms/update_transaction_fee",
                {min:min_rate,max:max_rate,charge:charge_value,id:upd_id},
                function(data){
                    location.reload();
                   /* if(data == 'yes'){
                         location.reload() 
                    }else{
                         location.reload() 
                    }*/
                }
        );
        
        
    }
    
    function save1(obj){
        var min_rate = $($(obj).parent().parent().get(0).cells[0]).find('input').val();
        var max_rate = $($(obj).parent().parent().get(0).cells[1]).find('input').val();
        var charge_value = $($(obj).parent().parent().get(0).cells[2]).find('input').val();
        
        if(min_rate == '' || isNaN(min_rate)){
            jQuery.jGrowl("Min Rate Must be digital",{theme:'jmsg-error'});
            return false;
        }
        if(max_rate == '' || isNaN(max_rate)){
            jQuery.jGrowl("Max Rate Must be digital!",{theme:'jmsg-error'});
            return false;
        }
        if(charge_value == '' || isNaN(charge_value)){
            jQuery.jGrowl("Charge Value Must be digital!",{theme:'jmsg-error'});
            return false;
        }
        
        if(min_rate > max_rate || min_rate == max_rate){
            jQuery.jGrowl("Min Rate must be less than Max Rate",{theme:'jmsg-error'});
            return false;
        }
        
        if(charge_value < 0 || charge_value > 100){
            jQuery.jGrowl("Charge Value Must be more than 100 less than zero!",{theme:'jmsg-error'});
            return false;
        }
        
        $.post("<?php echo $this->webroot?>paymentterms/add_transaction_fee",
                {min:min_rate,max:max_rate,charge:charge_value},
                function(data){
                    //alert(data);
                    location.reload();
                   /* if(data == 'yes'){
                         location.reload() 
                    }else{
                         location.reload() 
                    }*/
                }
        );
        
        
    }
    
    function notEdit(obj){
        location.reload();
    }
    
    function notEdit1(obj){
        $(obj).parent().parent().remove();
    }
    
    function addTransaction(){
        $("#list").append("<tr><td><input type='text' name='min_rate'  class='in-text'></td>\n\
                            <td><input type='text' name='max_rate'  class='in-text'></td>\n\
                            <td><input type='text' name='charge_value'  class='in-text'></td>\n\
                            <td><img onclick= \"save1(this)\" src=\"<?php echo $this->webroot?>images/menuIcon_004.gif\" />\n\
                            <img onclick= \"notEdit1(this)\" src=\"<?php echo $this->webroot?>images/delete.png\" /></td></tr>");
    }
</script>



<div id="title">
  <h1><?php echo __('Transaction')?>&gt;&gt;<?php echo __('Transaction Fee')?></h1>
  <?php //echo $this->element("search")?>
  <?php $w = $session->read('writable');?>
  <ul id="title-menu">
  <?php  if ($_SESSION['role_menu']['Switch']['paymentterms']['model_w']) {?>
    	<li><!--href="/Class4/paymentterms/add_transaction_fee"-->
            <a id="add" class="link_btn" onclick="addTransaction()" style="cursor: pointer" >
                <img width="16" height="16" alt="" src="/Class4/images/add.png">
                Create New
            </a>
    	</li>
    <?php }?>
  </ul>
</div>

<div id="container">
<div id="list_div">
<div id="toppage"></div>
<?php
    if(count($res) == 0){
?>
<div class="msg"  id="msg_div"><?php echo __('no_data_found')?></div>
<?php    
    }else{
?>
<table class="list" id="list">
	<thead>
		<tr>
                    <td> Min Rate </td>
                    <td> Max Rate </td>
                    <td> Charge Value(%) </td>
                    <td> Action </td>
                </tr>
	</thead>
        <tbody>
                <?php
                    foreach($res as $re){
                ?>
                <tr>
                    <td><?php echo $re[0]['min_rate']?></td>
                    <td><?php echo $re[0]['max_rate']?></td>
                    <td><?php  echo  round($re[0]['charge_value'],3)."%"?></td>
                    <td>
                        <a class="edit" title="edit">
                            <img onclick= "upd(this,<?php echo $re[0]['id']?>)" src="<?php echo $this->webroot?>images/editicon.gif" />
                        </a>
                        <a title="delete" >
                            <img onclick= "del(<?php echo $re[0]['id']?>)"  src="<?php echo $this->webroot?>images/delete.png" />
                        </a>
                        
                    </td>
                </tr>
                <?php        
                    }
                ?>     
	<tbody>
</table>
<?php        
    }
?> 
</div>
</div>