<style>
    #container input {
        width:100px;
    }
</style>
<div id="title">
 <h1><?php  __('Management')?> &gt;&gt;<?php echo __('Agents')?></h1>
 <ul id="title-search">
        <form method="get" id="myform1">

                <li> 
                    <span style="float:left; height:25px; line-height:25px; font-size:14px; color:#EEEEEE; font-weight:bold;">Name:</span><input type="text" id="search-_q" class="in-search default-value input in-text defaultText in-input" title="Search" value="Search" name="search">
                </li>
                    <input type="submit" id="submit" class="search_submit input in-submit" value="" name="submit">
        </form>
    </ul>
 
    <ul id="title-menu">
        <li>
        <a id="add" class="link_btn" href="javascript:void(0);" onclick="add();">
            <img width="16" height="16" alt="" src="<?php echo $this->webroot?>/images/add.png">
            Create New
        </a>
        </li>
    </ul>
 
</div>
<?php
    $status = array(
        0=>'Inactive',
        1=>'Active'
    );

?>
<div id="container">
     <?php
        $data = $p->getDataArray();
    ?>
    <div id="toppage"></div>
    <?php
        if(count($data) == 0){
    ?>
        <div class="msg"><?php echo __('no_data_found')?></div>
    <?php    
        }else{
    ?>
    <table class="list" id="key_list" >
        <thead>
           
            <tr>
                <td>Name</td>
                <td>Password</td>
                <td>Company</td>
                <td>Email</td>
                <td>Phone Number</td>
                <td>Domain Name</td>
                <td>Last Login Time</td>
                <td>Status</td>
                <td>Is Default</td>
                <td>Ip</td>
                <td class="last">Action</td>
            </tr>
        </thead>
        <tbody>
            <?php foreach($data as $item): ?>
            <tr>
                <td><?php echo $item[0]['name']?></td>
                <td>******</td>
                <td><?php echo $item[0]['company_name']?></td>
                <td><?php echo $item[0]['email']?></td>
                <td><?php echo $item[0]['phone_number']?></td>
                <td><?php echo $item[0]['domain_name']?></td>
                <td><?php echo $item[0]['last_login_time']?></td>
                <td><?php echo $status[$item[0]['status']]?></td>
                <td><?php echo $item[0]['is_default']?'YES':'NO'; ?></td>
                <td><?php echo $item[0]['ip']?></td>
                <td class="last">
                    <a title="edit" href="javascript:void(0);" onclick="edit_key(this,'<?php echo $item[0]['client_id']?>')" ><img src="<?php echo $this->webroot.'/images/editicon.gif'?>"></a>
                    <a href="javascript:void(0);" onclick="change_pwd(this,'<?php echo $item[0]['client_id']?>')" ><img src="<?php echo $this->webroot.'/images/menuIcon_014.gif';?>" title=" Change password"></a>
                    <!--<a title="delete" href="javascript:void(0);" onclick="del('<?php echo $item[0]['client_id']?>', '<?php echo $item[0]['name']?>')"><img src="<?php echo $this->webroot.'/images/delete.png'?>"></a>-->
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div id="tmppage"> <?php echo $this->element('page');?> </div>
    <?php }?>
</div>

<script>
    
   var str_edit = '';
   
   function add(){
       
       if($(".msg").length == 0 || $(".msg").css('display')=='none'){
                            $("#key_list").append("<tr>\n\
                <td><input class='in-text' type='text' name='name'></td>\n\
                <td><input class='in-text' type='password' name='pwd'></td>\n\
                <td><input class='in-text' type='text' name='company_name'></td>\n\
                <td><input class='in-text' type='text' name='email'></td>\n\
                <td><input class='in-text' type='text' name='phone_number'></td>\n\
                <td><input class='in-text' type='text' name='domain_name'></td><td></td>\n\
                <td><select name='status' class = 'in-select' ><option value='0'>Inactive</option><option value='1'>Active</option></select></td>\n\
                <td></td>\n\
                <td><input class='in-text' type='text' name='ip'></td><td><a title='save' href='javascript:void(0);' onclick='add_key(this)'><img src ='<?php echo $this->webroot.'/images/menuIcon_004.gif';?>'></a>\n\
                <a title='cancel' href='javascript:void(0);' onclick='del_add_key(this)'><img src ='<?php echo $this->webroot.'/images/delete.png';?>'></a></td>\n\
            </tr>");
                        }else{
                            $(".msg").hide();
                            $("#container").append("<table id=\"key_list\" cellspacing=\"0\" cellpadding=\"0\" class=\"list\">\n\
    <thead>\n\
        <tr>\n\
            <td>Name</td>\n\
            <td>Password</td>\n\
            <td>Company</td>\n\
            <td>Email</td>\n\
            <td>Phone Number</td>\n\
            <td>Domain Name</td>\n\
            <td>Last Login Time</td>\n\
            <td>Status</td>\n\
            <td>Is Default</td><td>Ip</td><td>Action</td>\n\
        </tr>\n\
    </thead>\n\
    <tbody>\n\
        <tr>\n\
            <td><input class='in-text' type='text' name='name'></td>\n\
            <td><input class='in-text' type='password' name='pwd'></td>\n\
            <td><input class='in-text' type='text' name='company_name'></td>\n\
            <td><input class='in-text' type='text' name='email'></td>\n\
            <td><input class='in-text' type='text' name='phone_number'></td>\n\
            <td><input class='in-text' type='text' name='domain_name'></td>\n\
            <td></td>\n\
            <td><select name='status' class = 'in-select'><option value='0'>Inactive</option><option value='1'>Active</option></select></td>\n\
            <td></td><td><input class='in-text' type='text' name='ip'></td>\n\
            <td><a title='save' href='javascript:void(0);' onclick='add_key(this)'><img src ='<?php echo $this->webroot.'/images/menuIcon_004.gif';?>'></a>\n\
                            <a title='cancel' href='javascript:void(0);' onclick='del_add_key(this)'><img src ='<?php echo $this->webroot.'/images/delete.png';?>'></a></td>\n\
            </tr>\n\
    </tbody>\n\
</table>");
                        }
                    
    }
    
    
    
    function add_key(obj){
            var tr = $(obj).parent().parent();
           
            var name = tr.find("input[name=name]").val();
            var pwd = tr.find("input[name=pwd]").val();
            var company_name = tr.find("input[name=company_name]").val();
            var email = tr.find("input[name=email]").val();
            var phone_number = tr.find("input[name=phone_number]").val();
            var domain = tr.find("input[name=domain_name]").val();
            var status = tr.find("select[name=status]").val();
            var ip = tr.find("input[name=ip]").val();
           
            $.ajax({
                'url':"<?php echo $this->webroot.'agent/add_agent';?>",
                'type':'post',
                'data':{'ip':ip,'name':name,'pwd':pwd,'company_name':company_name,'email':email,'phone_number':phone_number,'domain':domain,'status':status},
                'dataType':'json',
                'async':false,
                'success':function (data){
                    if(data['status'] == 'success'){
                        jQuery.jGrowl('The Agent [' + name + '] is created successfully.!',{theme:'jmsg-success'});
                        //location = "<?php echo $this->webroot.'clients/product_list_first';?>";
                        window.setTimeout(function() {window.location.reload(true)},3000);
                    }else if(data['status'] == 'isEmpty'){
                        jQuery.jGrowl('Agent Name can not be empty!',{theme:'jmsg-error'});
                    }else if(data['status'] == 'email_error'){
                        jQuery.jGrowl('The email field must contain a valid email address!',{theme:'jmsg-error'});
                    }else{
                         jQuery.jGrowl('The Agent[' + name + '] or Domain ['+domain+'] is already exists!',{theme:'jmsg-error'});
                    }
                }
            });
            
        }
    
    
    function del(id, product_name){
        if(confirm("Are you want to delete this record!")){
            location = "<?php echo $this->webroot?>/clients/del_product/"+id + '/' + product_name;
        }
    }
    
    function change_pwd(obj,id){
        
            var tr = $(obj).parent().parent();
            var edit = $("#key_list").find("a[title='save edit']");
            
            if(edit.length == 0){
               str_edit = tr.html();
               tr = tr.get(0);
               tr.cells[1].innerHTML = "<input class='in-text' type='password' name='pwd'>";
               tr.cells[10].innerHTML = "<a title='save edit' href='javascript:void(0);' onclick='save_pwd(this,"+id+")'><img src ='<?php echo $this->webroot.'/images/menuIcon_004.gif'?>'></a>\n\
            <a title='cancel' href='javascript:void(0);' onclick='del_edit_key(this)' ><img src ='<?php echo $this->webroot.'/images/delete.png'?>'></a>";
            }
    }
    function save_pwd(obj,id){
        var tr = $(obj).parent().parent();
        var pwd = tr.find("input[name=pwd]").val();
        tr = tr.get(0);
        var name = $.trim(tr.cells[0].innerHTML);
        $.ajax({
            'url':"<?php echo $this->webroot.'agent/save_pwd';?>",
            'type':'post',
            'data':{'pwd':pwd,'id':id,'name':name},
            'dataType':'json',
            'async':false,
            'success':function (data){
                if(data['status'] == 'success'){
                    jQuery.jGrowl('The Agent [' + name + '] is modified successfully.',{theme:'jmsg-success'});
                    $(tr.cells[1]).html("******");
                    window.setTimeout(function() {window.location.reload(true)},3000);
                }
            }
        });
    }
    
    function edit_key(obj,id){
            var tr = $(obj).parent().parent();
            
            var edit = $("#key_list").find("a[title='save edit']");
            
            if(edit.length == 0){
               str_edit = tr.html();
               tr = tr.get(0);
               var name = $.trim(tr.cells[0].innerHTML);
               var company_name = $.trim(tr.cells[2].innerHTML);
               var email = $.trim(tr.cells[3].innerHTML);
               var phone_number = $.trim(tr.cells[4].innerHTML);
               var domain_name = $.trim(tr.cells[5].innerHTML);
               var status = $.trim(tr.cells[7].innerHTML);
               var ip = $.trim(tr.cells[9].innerHTML);
               
               tr.cells[0].innerHTML = "<input type='text' class='in-text' value='"+name+"' name='name'>";
               tr.cells[1].innerHTML = "";
               tr.cells[2].innerHTML = "<input class='in-text' type='text' value='"+company_name+"'  name='company_name'>";
               tr.cells[3].innerHTML = "<input class='in-text' type='text' value='"+email+"'  name='email'>";
               tr.cells[4].innerHTML = "<input class='in-text' type='text' value='"+phone_number+"'  name='phone_number'>";
               tr.cells[5].innerHTML = "<input class='in-text' type='text' value='"+domain_name+"'  name='domain_name'>";
               tr.cells[9].innerHTML = "<input class='in-text' type='text' value='"+ip+"'  name='ip'>";
               tr.cells[7].innerHTML = "<select name='status' class = 'in-select'><option value='0'>Inactive</option><option value='1'>Active</option></select>";
               tr.cells[10].innerHTML = "<a title='save edit' href='javascript:void(0);' onclick='save_edit(this,"+id+")'><img src ='<?php echo $this->webroot.'/images/menuIcon_004.gif'?>'></a>\n\
            <a title='cancel' href='javascript:void(0);' onclick='del_edit_key(this)' ><img src ='<?php echo $this->webroot.'/images/delete.png'?>'></a>";
               
               $(tr.cells[7]).find('select option[text='+status+']').attr('selected','true');
               
            }else{
                jQuery.jGrowl('You must first save!',{theme:'jmsg-error'});
                return false;
            }
            
        }
    
    function save_edit(obj,id){
            var tr = $(obj).parent().parent();
            
            var name = tr.find("input[name=name]").val();
            var company_name = tr.find("input[name=company_name]").val();
            var email = tr.find("input[name=email]").val();
            var phone_number = tr.find("input[name=phone_number]").val();
            var domain = tr.find("input[name=domain_name]").val();
            var ip = tr.find("input[name=ip]").val();
            var status = tr.find("select[name=status]").val();
            $.ajax({
                'url':"<?php echo $this->webroot.'agent/save_agent';?>",
                'type':'post',
                'data':{'ip':ip,'name':name,'company_name':company_name,'email':email,'phone_number':phone_number,'domain':domain,'id':id,'status':status},
                'dataType':'json',
                'async':false,
                'success':function (data){
                    if(data['status'] == 'success'){
                        jQuery.jGrowl('The Agent [' + name + '] is modified successfully.',{theme:'jmsg-success'});
                        window.setTimeout(function() {window.location.reload(true)},3000);
                    }else if(data['status'] == 'isEmpty'){
                        jQuery.jGrowl('The Agent [' + name + '] can not be empty!',{theme:'jmsg-error'});
                    }else if(data['status'] == 'email_error'){
                        jQuery.jGrowl('The email field must contain a valid email address!',{theme:'jmsg-error'});
                    }else{
                        jQuery.jGrowl('The Agent [' + name + '] or Domain ['+domain+'] is already exists!',{theme:'jmsg-error'});
                    }
                }
            });
        }
    
    function del_add_key(obj){
            var tr = $(obj).parent().parent().parent();
            if(tr.find('tr').length == 1){
                $('.msg').show();
                $(obj).parent().parent().parent().parent().remove();
            }else{
                $(obj).parent().parent().remove();
            }
    }
        
        function del_edit_key(obj){
            var tr = $(obj).parent().parent();
            tr.html(str_edit);
    }
    
    
</script>

