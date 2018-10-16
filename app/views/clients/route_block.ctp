<div id="title">
    <h1><?php  __('Management')?> &gt;&gt;<?php echo __('Route Block')?></h1>


    <ul id="title-search">
        <form method="get" id="myform1">
                <li> 
                    <span style="float:left; height:25px; line-height:25px; font-size:14px; color:#EEEEEE; font-weight:bold;">Code Name:</span><input type="text" id="search-_q" class="in-search default-value input in-text defaultText in-input" title="Search" value="Search" name="search">
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

<div id="container">
    <?php
    $data = $p->getDataArray();
    ?>
    <div id="toppage"></div>
    <?php
    
    if(count($data) == 0){
    ?>
    <div  class="msg"><?php echo __('no_data_found')?></div>
    <?php    
    }else{
    ?>
    <table class="list" id="key_list">
        <thead>
            <tr>
                <td>Carrier Name</td>
                <td>Egress Trunk</td>
                <td>Code Name</td>
                <td>Update On</td>
                <td>Create By</td>
                <td class="last">Action</td>
            </tr>
        </thead>
        <tbody>
            <?php foreach($data as $item): ?>
            <tr>
                <td><?php echo $item[0]['name']?></td>
                <td><?php echo $item[0]['alias']?></td>
                <td><?php echo $item[0]['code_name']?></td>
                <td><?php echo $item[0]['create_on']?></td>
                <td><?php echo $item[0]['create_by']?></td>
                <td class="last">
                    <!--<a title="edit" href="javascript:void(0);" onclick="edit_key(this,'<?php echo $item[0]['id']?>')" ><img src="<?php echo $this->webroot.'/images/editicon.gif'?>"></a>-->
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
    
    var country_str = "";
       
    <?php
        foreach($code_name as $value){
    ?>
        country_str += "<option value='<?php echo $value[0]['name']; ?>'><?php echo $value[0]['name'];?></option>";
    <?php         
        }
    ?>

    var rate_table_str = "";

    <?php
        foreach($egress_trunk as $value){
    ?>
        rate_table_str += "<option value=<?php echo $value[0]['resource_id']; ?>><?php echo $value[0]['alias'];?></option>";
    <?php         
        }
    ?>
    
    function add(){
       
       
       
       
       
       if($(".msg").length == 0 || $(".msg").css('display')=='none'){
                            $("#key_list").append("<tr>\n\
                <td></td>\n\
                <td><select name='egress_id' class='in-select'>"+rate_table_str+"</select></td>\n\
                <td><select name='code_name' class='in-select'>"+country_str+"</select></td>\n\
                <td></td>\n\
                <td></td>\n\
                <td><a title='save' href='javascript:void(0);' onclick='add_key(this)'><img src ='<?php echo $this->webroot.'/images/menuIcon_004.gif';?>'></a>\n\
<a title='cancel' href='javascript:void(0);' onclick='del_add_key(this)'><img src ='<?php echo $this->webroot.'/images/delete.png';?>'></a></td>\n\
            </tr>");
                        }else{
                            $(".msg").hide();
                            $("#container").append("<table id=\"key_list\" cellspacing=\"0\" cellpadding=\"0\" class=\"list\">\n\
    <thead>\n\
        <tr>\n\
            <th>Carrier Name</th>\n\
            <th>Egress Trunk</th>\n\
            <th>Code Name</th>\n\
            <th>Update On</th>\n\
            <th>Create By</th>\n\
            <th>Action</th>\n\
        </tr>\n\
    </thead>\n\
    <tbody>\n\
        <tr>\n\
            <td></td>\n\
            <td><select name='egress_id' class='in-select'>"+rate_table_str+"</select></td>\n\
            <td><select name='code_name' class='in-select'>"+country_str+"</select></td>\n\
            <td></td>\n\
            <td></td>\n\
            <td><a title='save' href='javascript:void(0);' onclick='add_key(this)'><img src ='<?php echo $this->webroot.'/images/menuIcon_004.gif';?>'></a>\n\
                            <a title='cancel' href='javascript:void(0);' onclick='del_add_key(this)'><img src ='<?php echo $this->webroot.'/images/delete.png';?>'></a></td>\n\
            </tr>\n\
    </tbody>\n\
</table>");
                        }
                    
    }
    
    
    function add_key(obj){
            var tr = $(obj).parent().parent();
            var egress_id = tr.find("select[name=egress_id]").val();
            var code_name = tr.find("select[name=code_name]").val();
            $.ajax({
                'url':"<?php echo $this->webroot.'clients/add_route_block';?>",
                'type':'post',
                'data':{egress_id:egress_id,code_name:code_name},
                'dataType':'text',
                'async':false,
                'success':function (data){
                    if(data == 'success'){
                        jQuery.jGrowl('Succeeded',{theme:'jmsg-success'});
                        //location = "<?php echo $this->webroot.'clients/product_list_first';?>";
                        window.setTimeout(function() {window.location.reload(true)},3000);
                    }else if(data == 'no_client'){
                        jQuery.jGrowl('The Client of this egress trunk is no exists!',{theme:'jmsg-error'});
                    }else{
                         jQuery.jGrowl('The Route Block is already exists!',{theme:'jmsg-error'});
                    }
                }
            });
            
        }
    
    
    function del(id){
        if(confirm("Are you want to delete this record!")){
            location = "<?php echo $this->webroot?>/clients/del_route_block/"+id;
        }
    }
    
    function edit_key(obj,id){
            var tr = $(obj).parent().parent();
            
            var edit = $("#key_list").find("a[title='save edit']");
            
            if(edit.length == 0){
               str_edit = tr.html();
               tr = tr.get(0);
               var rout_name = tr.cells[1].innerHTML;
               var rate_table_name = tr.cells[2].innerHTML;
               
               
               tr.cells[1].innerHTML = "<select class='in-select' name='egress_id'>"+rate_table_str+"</select>";
               tr.cells[2].innerHTML = "<select class='in-select' name='code_name'>"+country_str+"</select>";
               tr.cells[4].innerHTML = "<a title='save edit' href='javascript:void(0);' onclick='save_edit(this,"+id+")'><img src ='<?php echo $this->webroot.'/images/menuIcon_004.gif'?>'></a>\n\
            <a title='cancel' href='javascript:void(0);' onclick='del_edit_key(this)' ><img src ='<?php echo $this->webroot.'/images/delete.png'?>'></a>";
               
               $(tr.cells[1]).find('select option[text='+rout_name+']').attr('selected','true');
               $(tr.cells[2]).find('select option[text='+rate_table_name+']').attr('selected','true');
               
               
            }else{
                jQuery.jGrowl('You must first save!',{theme:'jmsg-error'});
                return false;
            }
            
        }
    
    function save_edit(obj,id){
            var tr = $(obj).parent().parent();
            
            var egress_id = tr.find("select[name=egress_id]").val();
            var code_name = tr.find("select[name=code_name]").val();
            
            $.ajax({
                'url':"<?php echo $this->webroot.'clients/save_route_block';?>",
                'type':'post',
                'data':{egress_id:egress_id,code_name:code_name,id:id},
                'dataType':'text',
                'async':false,
                'success':function (data){
                    if(data == 'success'){
                        jQuery.jGrowl('modified successfully.',{theme:'jmsg-success'});
                        window.setTimeout(function() {window.location.reload(true)},3000);
                    }else if(data == 'no_client'){
                        jQuery.jGrowl('The Client of this egress trunk is no exists!',{theme:'jmsg-error'});
                    }else{
                        jQuery.jGrowl('this record is already exists!',{theme:'jmsg-error'});
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


