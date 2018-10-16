<style>
    #container input {
        width:100px;
    }
</style>
<div id="title">
 <h1><?php  __('Management')?> &gt;&gt;<?php echo __('Partition Plan')?></h1>
 <!--<ul id="title-search">
        <form method="get" id="myform1">

                <li> 
                    <span style="float:left; height:25px; line-height:25px; font-size:14px; color:#EEEEEE; font-weight:bold;">Name:</span><input type="text" id="search-_q" class="in-search default-value input in-text defaultText in-input" title="Search" value="Search" name="search">
                </li>
                    <input type="submit" id="submit" class="search_submit input in-submit" value="" name="submit">
        </form>
    </ul>
--> 
    <ul id="title-menu">
        <li>
        <a id="add" class="link_btn" href="javascript:void(0);" onclick="add();">
            <img width="16" height="16" alt="" src="<?php echo $this->webroot?>/images/add.png">
            Create New
        </a>
        </li>
    </ul>
 
</div>





<div id="container">
    
    
     <?php
        $data = $p->getDataArray();
        
        
        $type = array(
            0=>'By Month',
            1=>'By Minutes'
        );
        
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
                <td>Amount(USD)</td>
                <td>Type</td>
                <td>Port</td>
                <td>Minutes</td>
                <td class="last">Action</td>
            </tr>
        </thead>
        <tbody>
            <?php foreach($data as $item): ?>
            <tr>
                <td><?php echo $item[0]['money']?></td>
                <td><?php echo $type[$item[0]['type']]?></td>
                <td><?php 
                    
                    if($item[0]['type'] == 0){
                            echo $item[0]['port'];
                     }
                    
                        //echo $item[0]['port']
                    ?>
                </td>
                <td><?php 
                        if($item[0]['type'] == 1){
                            echo $item[0]['minutes'];
                        }
                    ?></td>
                <td class="last">
                    <a title="edit" href="javascript:void(0);" onclick="edit_key(this,'<?php echo $item[0]['id']?>')" ><img src="<?php echo $this->webroot.'/images/editicon.gif'?>"></a>
                    <a title="delete" href="javascript:void(0);" onclick="del('<?php echo $item[0]['id']?>')"><img src="<?php echo $this->webroot.'/images/delete.png'?>"></a>
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
                <td><input class='in-text' type='text' name='money'></td>\n\
                <td><select onchange='check_min();' class='in-select type'  name='type'><option value=0>By Month</option><option value=1>By Minutes</option></select></td>\n\
                <td><input class='in-text' type='text' name='port'></td>\n\
                <td><input class='in-text' type='text' name='minutes'></td>\n\
                <td><a title='save' href='javascript:void(0);' onclick='add_key(this)'><img src ='<?php echo $this->webroot.'/images/menuIcon_004.gif';?>'></a>\n\
                <a title='cancel' href='javascript:void(0);' onclick='del_add_key(this)'><img src ='<?php echo $this->webroot.'/images/delete.png';?>'></a></td>\n\
            </tr>");
                        }else{
                            $(".msg").hide();
                            $("#container").append("<table id=\"key_list\" cellspacing=\"0\" cellpadding=\"0\" class=\"list\">\n\
    <thead>\n\
        <tr>\n\
            <td>Amount(USD)</td>\n\
            <td>Type</td>\n\
            <td>Port</td>\n\
            <td>Minutes</td>\n\
            <td>Action</td>\n\
        </tr>\n\
    </thead>\n\
    <tbody>\n\
        <tr>\n\
            <td><input class='in-text' type='text' name='money'></td>\n\
            <td><select onchange='check_min();' class='in-select type'  name='type'><option value=0>By Month</option><option value=1>By Minutes</option></select></td>\n\
            <td><input class='in-text' type='text' name='port'></td>\n\
            <td><input class='in-text' type='text' name='minutes'></td>\n\
            <td><a title='save' href='javascript:void(0);' onclick='add_key(this)'><img src ='<?php echo $this->webroot.'/images/menuIcon_004.gif';?>'></a>\n\
                            <a title='cancel' href='javascript:void(0);' onclick='del_add_key(this)'><img src ='<?php echo $this->webroot.'/images/delete.png';?>'></a></td>\n\
            </tr>\n\
    </tbody>\n\
</table>");
                        }
                        
                        check_min()
                        
                        
                    
    }
    
    function check_min(){
        
        $(".type").each(function(index,content){
            var tr = $(content).parent().parent();
        
            if($(content).val() == 0){
                tr.find("input[name=port]").show();
                tr.find("input[name=minutes]").hide();
            }else{
                tr.find("input[name=port]").hide();
                tr.find("input[name=minutes]").show();
            }
            
        });
        
        
        
        
        
        
    }
    
    function add_key(obj){
            var tr = $(obj).parent().parent();
           
            var money = tr.find("input[name=money]").val();
            var port = tr.find("input[name=port]").val();
            var minutes = tr.find("input[name=minutes]").val();
            var type = tr.find("select[name=type]").val();
           
           
            $.ajax({
                'url':"<?php echo $this->webroot.'partition/add_plan';?>",
                'type':'post',
                'data':{'money':money,'port':port,'minutes':minutes,'type':type},
                'dataType':'json',
                'async':false,
                'success':function (data){
                    if(data['status'] == 'success'){
                        jQuery.jGrowl('The plan [' + money + '] is created successfully.!',{theme:'jmsg-success'});
                        //location = "<?php echo $this->webroot.'clients/product_list_first';?>";
                        window.setTimeout(function() {window.location.reload(true)},3000);
                    }else if(data['status'] == 'isEmpty'){
                        
                        if(type == 0){
                            jQuery.jGrowl('The Amount and Port can not be empty!',{theme:'jmsg-error'});
                        }else{
                            jQuery.jGrowl('The Amount and Minutes can not be empty!',{theme:'jmsg-error'});
                        }
                        
                        
                    }else if(data['status'] == 'email_error'){
                        
                        if(type == 0){
                            jQuery.jGrowl('The Amount and Port must be numeric.',{theme:'jmsg-error'});
                        }else{
                            jQuery.jGrowl('The Amount and Minutes must be numeric.',{theme:'jmsg-error'});
                        }
                        
                    }else{
                         //jQuery.jGrowl('The plan [' + money + '] or Domain ['+domain+'] is already exists!',{theme:'jmsg-error'});
                    }
                }
            });
            
        }
    
    
    function del(id){
        if(confirm("Are you want to delete this record!")){
            location = "<?php echo $this->webroot?>/partition/del_plan/"+id;
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
                    jQuery.jGrowl('The Product[' + name + '] is modified successfully.',{theme:'jmsg-success'});
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
               var money = $.trim(tr.cells[0].innerHTML);
               var type = $.trim(tr.cells[1].innerHTML);
               
               var port = $.trim(tr.cells[2].innerHTML);
               var minutes = $.trim(tr.cells[3].innerHTML);
               
               var select_1 = "";
               var select_2 = "";
               
               if(type == 'By Month'){
                   select_1 = "selected";
               }else{
                   select_2 = "selected";
               }
               
               
               tr.cells[0].innerHTML = "<input type='text' class='in-text' value='"+money+"' name='money'>";
               tr.cells[1].innerHTML = "<select type='text' onchange='check_min();' class='in-select type' name='type'>\n\
                                        <option "+select_1+" value=0>By Month</option>\n\
                                        <option "+select_2+" value=1>By Minutes</option>\n\
                                        </select>";
               
               tr.cells[2].innerHTML = "<input type='text' class='in-text' value='"+port+"' name='port'>";
               tr.cells[3].innerHTML = "<input type='text' class='in-text' value='"+minutes+"' name='minutes'>";
               
               
               
               tr.cells[4].innerHTML = "<a title='save edit' href='javascript:void(0);' onclick='save_edit(this,"+id+")'><img src ='<?php echo $this->webroot.'/images/menuIcon_004.gif'?>'></a>\n\
            <a title='cancel' href='javascript:void(0);' onclick='del_edit_key(this)' ><img src ='<?php echo $this->webroot.'/images/delete.png'?>'></a>";
               
               
               check_min();
               
               //$(tr.cells[7]).find('select option[text='+status+']').attr('selected','true');
               
            }else{
                jQuery.jGrowl('You must first save!',{theme:'jmsg-error'});
                return false;
            }
            
        }
    
    function save_edit(obj,id){
            var tr = $(obj).parent().parent();
            
             //var money = tr.find("input[name=money]").val();
             //var cost_min = tr.find("input[name=cost_min]").val();
             
            var money = tr.find("input[name=money]").val();
            var port = tr.find("input[name=port]").val();
            var minutes = tr.find("input[name=minutes]").val();
            var type = tr.find("select[name=type]").val();
             
             
             
            $.ajax({
                'url':"<?php echo $this->webroot.'partition/save_plan';?>",
                'type':'post',
                'data':{'money':money,'port':port,'id':id,'minutes':minutes,"type":type},
                'dataType':'json',
                'async':false,
                'success':function (data){
                    if(data['status'] == 'success'){
                        jQuery.jGrowl('The Plan [' + money + '] is modified successfully.',{theme:'jmsg-success'});
                        window.setTimeout(function() {window.location.reload(true)},3000);
                    }else if(data['status'] == 'isEmpty'){
                        
                        if(type == 0){
                            jQuery.jGrowl('The Amount and Port can not be empty!',{theme:'jmsg-error'});
                        }else{
                            jQuery.jGrowl('The Amount,Port,Minutes can not be empty!',{theme:'jmsg-error'});
                        }
                        
                        
                    }else if(data['status'] == 'email_error'){
                        
                        if(type == 0){
                            jQuery.jGrowl('The Amount and Port must be numeric.',{theme:'jmsg-error'});
                        }else{
                            jQuery.jGrowl('The Amount,Port,Minutes must be numeric.',{theme:'jmsg-error'});
                        }
                        
                    }else{
                         //jQuery.jGrowl('The plan [' + money + '] or Domain ['+domain+'] is already exists!',{theme:'jmsg-error'});
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

