<style>
    #container input {
        width:100px;
    }
</style>
<div id="title">
 <h1><?php  __('Management')?> &gt;&gt;<?php echo __('Users')?></h1>
 <ul id="title-search">
        <form method="get" id="myform1">

                <li> 
                    <span style="float:left; height:25px; line-height:25px; font-size:14px; color:#EEEEEE; font-weight:bold;">Name:</span><input type="text" id="search-_q" class="in-search default-value input in-text defaultText in-input" title="Search" value="Search" name="search">
                </li>
                    <input type="submit" id="submit" class="search_submit input in-submit" value="" name="submit">
        </form>
    </ul>
 
    <!--<ul id="title-menu">
        <li>
        <a id="add" class="link_btn" href="javascript:void(0);" onclick="add();">
            <img width="16" height="16" alt="" src="<?php echo $this->webroot?>/images/add.png">
            Create New
        </a>
        </li>
    </ul>
    -->
</div>
<?php
    $status = array(
        0=>'Inactive',
        1=>'Active'
    );

?>
<div id="container">
    
    <?php 
        if($type == 'agent'){
            echo $this->element('tabs',Array('tabs'=>Array('Agent User'=>Array('url'=>'exchangeuser/index/agent','active'=>true),'Partition User'=>Array('url'=>'exchangeuser/index/partition'),'Exchange User'=>Array('url'=>'exchangeuser/index/exchange') )));
        }else if($type == 'partition'){
            echo $this->element('tabs',Array('tabs'=>Array('Agent User'=>Array('url'=>'exchangeuser/index/agent'),'Partition User'=>Array('url'=>'exchangeuser/index/partition','active'=>true),'Exchange User'=>Array('url'=>'exchangeuser/index/exchange') )));
        }else if($type == 'exchange'){
            echo $this->element('tabs',Array('tabs'=>Array('Agent User'=>Array('url'=>'exchangeuser/index/agent'),'Partition User'=>Array('url'=>'exchangeuser/index/partition'),'Exchange User'=>Array('url'=>'exchangeuser/index/exchange','active'=>true) )));
        }
    ?>
    
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
                <td>User Name</td>
                <td>Role Name</td>
                <td>Last Login Time</td>
                <td class="last">Action</td>
            </tr>
        </thead>
        <tbody>
            <?php foreach($data as $item): ?>
            <tr>
                <td><?php echo $item[0][$field]?></td>
                <td><?php echo empty($item[0]['role_name'])?'Default':$item[0]['role_name'];?></td>
                <td><?php echo $item[0]['last_login_time']?></td>
                <td class="last">
                    <a type="<?php echo $type;?>" role_id="<?php echo $item[0]['role_id']?>" title="edit" href="javascript:void(0);" onclick="edit_key(this,'<?php echo $item[0]['client_id']?>')" ><img src="<?php echo $this->webroot.'/images/editicon.gif'?>"></a>
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
   
   
    
    function edit_key(obj,id){
            var tr = $(obj).parent().parent();
            
            var edit = $("#key_list").find("a[title='save edit']");
            
            if(edit.length == 0){
               str_edit = tr.html();
               tr = tr.get(0);
               
               
               var role_id = $(obj).attr('role_id');
               var type =  $(obj).attr('type');
               var select_str = "<option value='0'>Default</option>"
               $.ajax({
                'url':"<?php echo $this->webroot.'exchangeuser/get_roles';?>",
                'type':'post',
                'data':{'type':type},
                'dataType':'json',
                'async':false,
                'success':function (data){
                    $.each(data,function(index,content){
                        if(content[0]['role_id'] == role_id){
                            select_str += "<option selected value='"+content[0]['role_id']+"'>"+content[0]['role_name']+"</option>";
                        }else{
                            select_str += "<option  value='"+content[0]['role_id']+"'>"+content[0]['role_name']+"</option>";
                        }
                        
                    });
                    
                    
                    tr.cells[1].innerHTML = "<select name='role' class = 'in-select'>"+select_str+"</select>";
                    tr.cells[3].innerHTML = "<a type='"+type+"' title='save edit' href='javascript:void(0);' onclick='save_edit(this,"+id+")'><img src ='<?php echo $this->webroot.'/images/menuIcon_004.gif'?>'></a>\n\
                    <a title='cancel' href='javascript:void(0);' onclick='del_edit_key(this)' ><img src ='<?php echo $this->webroot.'/images/delete.png'?>'></a>";
                }
            });
               
               
               
               
            }else{
                jQuery.jGrowl('You must first save!',{theme:'jmsg-error'});
                return false;
            }
            
        }
    
    function save_edit(obj,id){
            var tr = $(obj).parent().parent();
            
            var type =  $(obj).attr('type');
            var name = tr.find("td").eq(0).html();
            
            var role_id = tr.find("select[name=role]").val();
            
            $.ajax({
                'url':"<?php echo $this->webroot.'exchangeuser/save_user';?>",
                'type':'post',
                'data':{'id':id,'type':type,'role_id':role_id},
                'dataType':'json',
                'async':false,
                'success':function (data){
                    
                    if(data['status'] == 'success'){
                        jQuery.jGrowl('The User [' + name + '] is modified successfully.',{theme:'jmsg-success'});
                        window.setTimeout(function() {window.location.reload(true)},3000);
                    }else{
                        jQuery.jGrowl('The User [' + name + '] is modified unsuccessfully.',{theme:'jmsg-error'});
                    }
                }
            });
        }
    
        function del_edit_key(obj){
            var tr = $(obj).parent().parent();
            tr.html(str_edit);
    }
    
    
</script>

