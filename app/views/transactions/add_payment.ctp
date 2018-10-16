<div id="title"> 


    <h1>Finance &gt;&gt; Payment &gt;&gt; Add Payment</h1>
    <ul id="title-menu">
        <a href="<?php echo $this->webroot; ?>transactions/payment/<?php echo $type == 'received' ? 'incoming' : 'outgoing' ?>" class="link_back_new">
            <img width="16" src="<?php echo $this->webroot; ?>images/icon_back_white.png" heigh="16">
            &nbsp; Back
        </a>
    </ul>
</div>

<div id="container">
    <form action="<?php echo $this->webroot; ?>transactions/add_payment/<?php echo $type ?>" method="post" id="myform">
    <table class="form" style="width:600px;">
        <tr>
            <td>Carrier:</td>
            <td>
                <select id="client_id" name="client_id">
                    <?php foreach ($carriers as $carrier): ?>
                    <option value="<?php echo $carrier['Client']['client_id'] ?>"><?php echo $carrier['Client']['name'] ?></option>
                    <?php endforeach; ?>
                </select>
            </td>
        </tr>
        <tr>
            <td>Payment Received At:</td>
            <td>
                <input type="text" name="received_at" onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss',lang:'en'})" value="<?php echo date("Y-m-d H:i:s"); ?>" />
            </td>
        </tr>
        <tr>
            <td>Note:</td>
            <td>
                <input type="text" name="note" />
            </td>
        </tr>
        <tr>
            <td>Amount</td>
            <td>
                <input type="text" id="amount" name="amount" />
            </td>
        </tr>
        <tr>
            <td>Type:</td>
            <td>
                <select id="payment_type" name="type">
                    <option value="0">Prepayment</option>
                    <option value="1">Invoice Payment</option>
                </select>
            </td>
        </tr>
    </table>

    <table id="invoice_table" class="list">
        <thead>
            <tr>
                <th>Invoice Number</th>
                <th>Invoice Amount</th>
                <th>Pay Amount</th>
                <th>Period</th>
                <th>Given Amount</th>
                <th>
                    <a id="add_invoice" href="###">
                        <img src="<?php echo $this->webroot ?>images/add.png" style="width:auto;height:auto;"> Add Invoice
                    </a>
                </th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <input type="hidden" class="remain_amount" name="remain_amount" value="0">
                <td>-</td>
                <td>-</td>
                <td>-</td>
                <td>-</td>
                <td><span class="remain_amount"></span></td>
                <td></td>
            </tr>
        </tbody>
    </table>

    <div style="text-align: center">
        <input  type="submit" value="Submit" />
    </div>
    </form>
</div>

<script>
    $(function() {
        var $add_invoice = $('#add_invoice');
        var $amount      = $('#amount');
        var $client_id   = $('#client_id');
        var invoices     = new Array();
        var $invoice_table = $('#invoice_table tbody');
        var $invoice_paid = $('.invoice_paid');
        var $remain_amount = $('.remain_amount');
        var $delete_invoice = $('.delete_invoice');
        var $myform = $('#myform');
        var $payment_type = $('#payment_type');
        var loading = false;

        function refresh_amount()
        {
            var total_amount = Number($amount.val());
            var $invoice_amounts = $('.invoice_paid');
            var paid_amount = 0;
            $.each($invoice_amounts, function(index, item) {
                paid_amount += Number($(this).val());
            });
            var remain_amount = total_amount - paid_amount;
            if (remain_amount < 0 && $payment_type.val() == '1') {
                 jQuery.jGrowl("The Remain Amount must be greater or equal than 0!",{theme:'jmsg-error'});
            }
            $remain_amount.val(remain_amount).text(remain_amount);
        }

        function invoice_amount_change(event)
        {
            var $this = $(this);
            var amount = Number($this.val());
            if ((amount < 0 || isNaN(amount)) && $payment_type.val() == '1') {
                jQuery.jGrowl("The Invoice Amount must be greater than 0!",{theme:'jmsg-error'});
                $this.val(1);
            }
            var $tr = $this.parents('tr');
            var invoice_amount = Number($('td:eq(1)', $tr).text());
            var invoice_pay    = Number($('td:eq(2)', $tr).text());
            var should_pay     = invoice_amount - invoice_pay;
            if (amount > should_pay) {
                jQuery.jGrowl("The Invoice Amount can not be greater than should pay!",{theme:'jmsg-error'});
                $this.val(should_pay);
                $this.focus();
            }

            refresh_amount();
        }

        $add_invoice.click(function() {
            if (loading) return;

            loading = true;
            var $this = $(this);
            $this.css('visibility', 'hidden');
            $.ajax({
                'url'     : '<?php echo $this->webroot ?>transactions/get_one_invoice',
                'type'    : 'POST',
                'dataType': 'json',
                'data'    : {'invoices[]' : invoices, 'client_id' : $client_id.val(), 'type' : "<?php echo $type ?>"},
                'success' : function(data) {

                    $.each(data, function(index, item) {
                        invoices.push(item[0]['invoice_number']);
                        var $tr = $('<tr />');
                        $tr.append('<input type="hidden" class="invoice_number" name="invoice_number[]" value="' + item[0]['invoice_number'] + '">');
                        $tr.append('<td>' + item[0]['invoice_number'] + '</td>');
                        $tr.append('<td>' + item[0]['total_amount'] + '</td>');
                        $tr.append('<td>' + item[0]['pay_amount'] + '</td>');
                        $tr.append('<td>' + item[0]['invoice_start'] + '~' +  item[0]['invoice_end'] + '</td>');
                        $tr.append('<td><input class="invoice_paid input in-text in-input" type="text" name="invoice_paid[]" /></td>');
                        $tr.append('<td><a number="' + item[0]['invoice_number'] + '" class="delete_invoice" href="###"><img src="<?php echo $this->webroot ?>images/delete.png"></a></td>');
                        $invoice_table.prepend($tr);
                        $amount.change();
                    })
                    loading = false;
                }
            });
            $this.css('visibility', 'visible');
        });

        $invoice_paid.live('keyup', invoice_amount_change);
        $amount.bind('keyup', refresh_amount);

        function delete_invoice(event) {
            var $this = $(this);
            var invoice_number = $this.attr('number');
            for (var i = 0; i < invoices.length; i++) {
                if (invoices[i] == invoice_number) {
                    invoices.splice(i, 1);
                }
            }
            $this.parents('tr').remove();
            refresh_amount();
            return false;
        }

        $delete_invoice.live('click', delete_invoice);

        $myform.submit(function() {
            var total_amount = Number($amount.val());
            var remain_amount = Number($remain_amount.val());
            var invoice_type = $payment_type.val();
            if (total_amount < 0 && invoice_type == '1') {
                jQuery.jGrowl("The Invoice Amount can not be less than 0!",{theme:'jmsg-error'});
                return false;
            }
            if (remain_amount < 0 && invoice_type == '1') {
                jQuery.jGrowl("The Remain Amount can not be less than 0!",{theme:'jmsg-error'});
                return false;
            }

        });

        function refresh()
        {
            invoices = new Array();
            var type = $payment_type.val();
            $('tr:not(:last)', $invoice_table).remove();
            if (type == 0) {
                $invoice_table.parent().hide();
            } else {
                $invoice_table.parent().show();
            }
        }

        $client_id.bind('change', refresh);
        $payment_type.bind('change', refresh).trigger('change');

        $amount.bind('change', function() {
            var val = $(this).val();
            if (val <= 0) {
                $('.invoice_paid').attr('disabled', true);
            } else {
                $('.invoice_paid').removeAttr('disabled');
            }
        }).triggered('change');

    });
</script>



