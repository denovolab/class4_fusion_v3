<div id="title">
    <h1>Billing &gt;&gt;Online Payment</h1>
</div>

<div id="container">

    <ul class="tabs">
        <li class="active">
            <a id="paypal" href="###">
                Paypal		
            </a>
        </li>
<!--        --><?php //if (Configure::read('payline.yourpay_enabled')): ?>
<!--            <li>-->
<!--                <a id="credit_card" href="###">-->
<!--                    Credit Card		-->
<!--                </a>-->
<!--            </li>-->
<!--        --><?php //endif; ?>
        <li>
            <a target="_blank" href="<?php echo $this->webroot; ?>payment_history">
                Auto Payment Log		
            </a>
        </li>
            
    </ul>

    <form id="myform" action="<?php echo $this->webroot; ?>clients/client_pay_do" method="post">
        
        <input type="hidden" id="platform" name="platform" value="0" />
        
        <table id="paypal_table" class="list" border="0" cellspacing="0" cellpadding="0">
            <tbody>
                <td>Payment Amount: ($ USD)</td>
                <td>
                    <input type="text" name="chargetotal2" />
                </td>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="2">
                        <input type="submit" value="Submit" />
                    </td>
                </tr>
            </tfoot>
        </table>

<!--        <table id="credit_card_table" class="list" border="0" cellspacing="0" cellpadding="0">-->
<!--            <tbody>-->
<!--                <tr>-->
<!--                    <th colspan="2">Credit Card Information</th>-->
<!--                </tr>-->
<!--                <tr>-->
<!--                    <td>Credit Card Type:</td>-->
<!--                    <td>-->
<!--                        <input type="radio" name="credit_card_type" checked="checked" value="0" />Visa <input type="radio" name="credit_card_type" value="1" />MasterCard <input type="radio" name="credit_card_type" value="2" />American Express <input type="radio" name="credit_card_type" value="3" />Discover-->
<!--                    </td>-->
<!--                </tr>-->
<!--                <tr>-->
<!--                    <td>Credit Card Account:</td>-->
<!--                    <td>-->
<!--                        <input type="text" name="cardnumber" />-->
<!--                    </td>-->
<!--                </tr>-->
<!--                <tr>-->
<!--                    <td>Credit Card Code:</td>-->
<!--                    <td>-->
<!--                        <input type="text" name="cvmvalue" />-->
<!--                    </td>-->
<!--                </tr>-->
<!--                <tr>-->
<!--                    <td>Credit Card Expiration Date:</td>-->
<!--                    <td>-->
<!--                        Month-->
<!--                        <select name="cardexpmonth">-->
<!--                            <option value="01" selected="1">01</option>-->
<!--                            <option value="02">02</option>-->
<!--                            <option value="03">03</option>-->
<!--                            <option value="04">04</option>-->
<!--                            <option value="05">05</option>-->
<!--                            <option value="06">06</option>-->
<!--                            <option value="07">07</option>-->
<!--                            <option value="08">08</option>-->
<!--                            <option value="09">09</option>-->
<!--                            <option value="10">10</option>-->
<!--                            <option value="11">11</option>-->
<!--                            <option value="12">12</option>-->
<!--                        </select>-->
<!--                        Year-->
<!--                        <select name="cardexpyear">-->
<!--                            --><?php //for ($i = 0; $i <= 99; $i++): ?>
<!--                                <option>--><?php //printf("%02d", $i); ?><!--</option>-->
<!--                            --><?php //endfor; ?>
<!--                        </select>-->
<!--                    </td>-->
<!--                </tr>-->
<!--                <tr>-->
<!--                    <td>Payment Amount: ($ USD)</td>-->
<!--                    <td>-->
<!--                        <input type="text" name="chargetotal1" />-->
<!--                    </td>-->
<!--                </tr>-->
<!--                <tr>-->
<!--                    <th colspan="2">Credit Card Billing Address</th>-->
<!--                </tr>-->
<!--                <tr>-->
<!--                    <td>Street Address 1:</td>-->
<!--                    <td>-->
<!--                        <input type="text" name="address1" />-->
<!--                    </td>-->
<!--                </tr>-->
<!--                <tr>-->
<!--                    <td>Street Address 2:</td>-->
<!--                    <td>-->
<!--                        <input type="text" name="address2" />-->
<!--                    </td>-->
<!--                </tr>-->
<!--                <tr>-->
<!--                    <td>City:</td>-->
<!--                    <td>-->
<!--                        <input type="text" name="city" />-->
<!--                    </td>-->
<!--                </tr>-->
<!--                <tr>-->
<!--                    <td>-->
<!--                        <table style="font-size:inherit;color:#2D3238;">-->
<!--                            <tr>-->
<!--                                <td>State/Province:</td>-->
<!--                                <td>-->
<!--                                    <input type="text" name="state_province" />-->
<!--                                </td>-->
<!--                            </tr>-->
<!--                        </table>-->
<!--                    </td>-->
<!--                    <td>-->
<!--                        <table style="font-size:inherit;color:#2D3238;">-->
<!--                            <tr> -->
<!--                                <td>Zip/Postal Code:</td>-->
<!--                                <td>-->
<!--                                    <input type="text" name="zip_code" />-->
<!--                                </td>-->
<!--                            </tr>-->
<!--                        </table>-->
<!--                    </td>-->
<!--                </tr>-->
<!--                <tr>-->
<!--                    <td>Country:</td>-->
<!--                    <td>-->
<!--                        <input type="text" name="country" />-->
<!--                    </td>-->
<!--                </tr>-->
<!--            </tbody>-->
<!--            <tfoot>-->
<!--                <tr>-->
<!--                    <td colspan="2">-->
<!--                        <input type="submit" value="Submit" />-->
<!--                    </td>-->
<!--                </tr>-->
<!--            </tfoot>-->
<!--        </table>-->


    </form>


</div>


<script>
    $(function() {
        var $paypal = $('#paypal');
        var $credit_card = $('#credit_card');
        var $paypal_table = $('#paypal_table');
        var $credit_card_table = $('#credit_card_table');
        var $platform = $('#platform');
        
        $paypal.click(function() {
            if (!$paypal.parent().hasClass('active'))
            {
                $paypal.parent().addClass('active');
            }
            $credit_card.parent().removeClass('active');
            $paypal_table.show();
            $credit_card_table.hide();
            $platform.val(0);
        });
    
        $credit_card.click(function() {
            if (!$credit_card.parent().hasClass('active'))
            {
                $credit_card.parent().addClass('active');
            }
            $paypal.parent().removeClass('active');
            $paypal_table.hide();
            $credit_card_table.show();
            $platform.val(1);
        });
        
        $paypal.click();
	
        $(".method:eq(0)").trigger('click');
	
        $('#myform').submit(function() {
            var flag = true;
            if ($(".method:eq(1)").attr('checked'))
            {
                if($('input[name=cardnumber]').val() == '')
                {
                    jQuery.jGrowl('Card Number cant not be empty!',{theme:'jmsg-error'});
                    flag = false;
                }
                if($('input[name=cardexpmonth]').val() == '')
                {
                    jQuery.jGrowl('Card Expire Month cant not be empty!',{theme:'jmsg-error'});
                    flag = false;
                }
                if($('input[name=cardexpyear]').val() == '')
                {
                    jQuery.jGrowl('Card Expire Year cant not be empty!',{theme:'jmsg-error'});
                    flag = false;
                }
                
                    if(isNaN($('input[name=chargetotal1]').val()) || $('input[name=chargetotal1]').val() == '')
                    {
                        jQuery.jGrowl('Amount is invalid!',{theme:'jmsg-error'});
                        flag = false;
                    }
            } else {
                if(isNaN($('input[name=chargetotal2]').val()) || $('input[name=chargetotal2]').val() == '')
                    {
                        jQuery.jGrowl('Amount is invalid!',{theme:'jmsg-error'});
                        flag = false;
                    }
            }
		
                
		
            


            return flag;
        });
    });
</script>