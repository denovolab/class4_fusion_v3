<div id="title">
 <h1><?php  __('Finance')?> &gt;&gt;<?php echo __('All Transaction Fee')?></h1>
 <?php echo $this->element("search")?>
 <ul id="title-menu">
     <li>
	 		<?php echo $this->element("createnew",Array('url'=>'paymentterms/add_transaction'))?>
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
        <div class="msg"><?php echo __('no_data_found')?></div>
    <?php    
        }else{
    ?>
    <table class="list">
        <thead>
            <tr>
<!--                <td>id</td>-->
                <td>Name</td>
                <td >Is Default</td>
                <td>Action</td>
            </tr>
        </thead>
        <tbody>
            <?php foreach($data as $item): ?>
            <tr>
<!--                <td><?php echo $item[0]['id']?></td>-->
                <td><?php echo $item[0]['name']?></td>
                <td ><?php
                        if($item[0]['is_default']){
                    ?>
                    <img title="default transaction fee" src ="<?php echo $this->webroot?>images/flag-1.png">
                    <?php
                        }else{
                    ?>
                       <img title="not default transaction fee" src ="<?php echo $this->webroot?>images/flag-0.png"> 
                    <?php
                        }
                    ?>
                </td>
                <td>
                    <a href="<?php echo $this->webroot;?>paymentterms/view_finance_fee_item/<?php echo $item[0]['id']; ?>">
                        Finance Fee
                    </a>
                    |
                    <a href="<?php echo $this->webroot;?>paymentterms/view_transaction_fee_item/<?php echo $item[0]['id']; ?>">
                        Transaction Fee
                    </a>
                    
                    <a href="<?php echo $this->webroot;?>paymentterms/edit_transaction/<?php echo $item[0]['id']; ?>">
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
    <div id="tmppage"> <?php echo $this->element('page');?> </div>
    <?php }?>
</div>

<script>
    function del(id){
        if(confirm('Are you sure to delete?')){
            location = "<?php echo $this->webroot?>paymentterms/delete_transaction/"+id;
        }
    }
    
</script>


