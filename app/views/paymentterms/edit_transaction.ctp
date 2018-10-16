<div id="title">
 <h1><?php  __('Management')?> &gt;&gt;<?php echo __('Edit Transaction Fee')?></h1>
 
</div>
<style>
    .list1 td{ line-height:2;}
</style>
<div id="container">
    <form method="post" id="addForm">
        <input type ="hidden" id="id" name="id" value="<?php if(!empty($res[0])){echo $res[0][0]['id'];}?>">
    <table class="list1">
            <tr>
                <td >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    Name:<input value="<?php if(!empty($res[0])){echo $res[0][0]['name'];}?>" id="name" type="text" name="name"></td>
            </tr>
            
            <tr>
               
                <td >
                     Default:
                    <select id="default" name="is_default">
                        <?php
                            if($res[0][0]['is_default']){
                        ?>
                            <option value="t" selected>True</option>
                            <option value="f">False</option>
                        <?php         
                            }else{
                        ?>
                            <option value="t">True</option>
                            <option value="f" selected>False</option>
                        <?php    
                            }
                        ?>
                    </select>
                </td>
            </tr>
            
            <tr>
                <td>
                    <br/>
                    <input type="submit" value="submit" class="in-submit">
                    <a href="<?php echo $this->webroot;?>paymentterms/edit_transaction/<?php if(!empty($res[0])){echo $res[0][0]['id'];}?>"><input type="button" value="reset" class="in-submit"></a>
                </td>
            </tr>
    </table>
    </form>
</div>

<script>
    $(function (){
        $("#addForm").submit(function (){
            var name =$("#name").val();
            var is_default = $("#default").val();
            var id = $("#id").val();
            
            if(name == ''){
                jQuery.jGrowl("This Transaction Fee can not be empty!",{theme:'jmsg-error'});
                return false;
            }
            
            var flag = false;
            $.ajax({
                'url' : '<?php echo $this->webroot?>paymentterms/check_transaction1/'+name+"/"+is_default+"/"+id,
                'type' : 'POST',
                'dataType' : 'text',
                'data' : {},
                'async' : false,
                'success' :function (data){
                    if(data == 'name_no'){
                        flag = false;
                        jQuery.jGrowl("This Transaction Fee already exists!",{theme:'jmsg-error'});
                    }else if(data == 'default_no'){
                        flag = false;
                        jQuery.jGrowl("Default Transaction Fee already exists!",{theme:'jmsg-error'});
                        
                    }else if(data == 'yes'){
                        flag = true;
                    }
                }
            });
            
            return flag;
        });
    });
</script>


