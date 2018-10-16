<div id="title">
 <h1><?php  __('Finance')?> &gt;&gt;<?php echo __('Finance Fee Item')?></h1>
<ul id="title-menu">
     <li>
          <?php
                if(count($data) == 0){
                    echo $this->element("createnew",Array('url'=>'paymentterms/add_finance_item/'.$id));
                }
            ?>
     </li>
 </ul>
</div>

<div id="container">
    <div id="toppage"></div>
    <?php
        if(count($data) == 0){
    ?>
        <div class="msg"><?php echo __('no_data_found')?></div>
    <?php    
        }else{
    ?>
    <table class="list">
        <thead>
            <tr>
                <td>Name</td>
                <td >Use Fee(%)</td>
                <td>Action</td>
            </tr>
        </thead>
        <tbody>
            <?php foreach($data as $item): ?>
            <tr>
                <td><?php echo $item[0]['name']?></td>
                <td><?php echo $item[0]['use_fee']?></td>
                <td>
                    <a href="<?php echo $this->webroot;?>paymentterms/edit_finance_item/<?php echo $item[0]['id']; ?>">
                        <img src="<?php echo $this->webroot?>images/editicon.gif">
                    </a>
                    <a href="javascript:void(0);" onclick="del(<?php echo $item[0]['id']?>)">
                        <img src="<?php echo $this->webroot?>images/delete.jpg">
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php }?>
</div>

<script>
    function del(id){
        if(confirm('Are you sure to delete?')){
            location = "<?php echo $this->webroot?>paymentterms/delete_finance_item/"+id+"/<?php echo $id;?>";
        }
    }
</script>


