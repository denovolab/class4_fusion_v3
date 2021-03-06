<div id="title">
 <h1><?php  __('Management')?> &gt;&gt;<?php echo __('Edit Finance Fee')?></h1>
 
</div>
<style>
    .list1 td{ line-height:2;}
</style>
<div id="container">
    <form method="post" id="addForm">
        <input type="hidden" value="<?php if(!empty($transaction_fee_items)){echo $transaction_fee_items[0][0]['transaction_fee_id'];}?>" id="transaction_fee_id" name="transaction_fee_id" >
    <table class="list1">
            <tr>
                <td >
                    Use Fee:<input id="use_fee" value="<?php if(!empty($transaction_fee_items)){echo $transaction_fee_items[0][0]['use_fee'];}?>" type="text" name="use_fee" style="width:220px;"></td>
            </tr>
            
            <tr>
                <td >
                    Default:
                    <select id="trans_id" name="trans_id" style="width:220px;">
                        <?php
                            foreach($payment_terms as $payment_term){
                            if($payment_term[0]['payment_term_id'] == $transaction_fee_items[0][0]['trans_id']){
                        ?>
                        <option selected value="<?php echo $payment_term[0]['payment_term_id']?>">
                            <?php echo $payment_term[0]['name']?>
                        </option>
                        <?php
                            }else{
                        ?>
                            <option value="<?php echo $payment_term[0]['payment_term_id']?>">
                                <?php echo $payment_term[0]['name']?>
                            </option>
                        <?php        
                            }
                        
                            }
                        ?>
                    </select>
                </td>
            </tr>
            
            <tr>
                <td>
                    <br/>
                    <input type="submit" value="submit" class="in-submit">
                    <a href="<?php echo $this->webroot;?>paymentterms/add_finance_item/<?php echo $id;?>"><input type="button" value="reset" class="in-submit"></a>
                </td>
            </tr>
    </table>
    </form>
</div>

<script>
    $(function (){
        $("#addForm").submit(function (){
            var transaction_fee_id =$("#transaction_fee_id").val();
            var trans_id = $("#trans_id").val();
            var use_fee = $("#use_fee").val();
            
            
            if(use_fee == ''){
                jQuery.jGrowl("This Transaction Fee Item can not be empty!",{theme:'jmsg-error'});
                return false;
            }
            
            if(use_fee > 100){
                jQuery.jGrowl("This Use Fee can not is greater than 100!",{theme:'jmsg-error'});
                return false;
            }
            
            var flag = false;
            $.ajax({
                'url' : '<?php echo $this->webroot?>paymentterms/check_finance_item1/'+trans_id+"/"+transaction_fee_id+"/"+"<?php echo $transaction_fee_items[0][0]['id']?>",
                'type' : 'POST',
                'dataType' : 'text',
                'data' : {},
                'async' : false,
                'success' :function (data){
                    if(data == 'no'){
                        flag = false;
                        jQuery.jGrowl("This Transaction Fee Item already exists!",{theme:'jmsg-error'});
                    }else if(data == 'yes'){
                        flag = true;
                    }
                }
            });
            
            return flag;
        });
    });
</script>


