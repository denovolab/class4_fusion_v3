<div id="title">
  <h1>
    
    <?php
    if ($session->read('login_type') == 3):
    ?>
    <?php __('Billing')?>
    &gt;&gt;
    <?php __('Balance')?>[<?php echo $client_name ?>]
    <?php else: ?>
    <?php __('Finance')?>
    &gt;&gt;
    <?php __('Overall Actual Balance')?>[<?php echo $client_name ?>]
    <?php 
    endif;
    ?>
  </h1>
  <ul id="title-menu">
      <?php if ($session->read('login_type') != 3): ?>
         <li>
            <a class="link_btn" id="massadd" href="###">
                    <img width="16" height="16" src="<?php echo $this->webroot?>images/add.png"><?php __('Mass Add')?>
            </a>
        </li>
        <?php if($_SESSION['role_menu']['Payment_Invoice']['reset_balance'] == 1): ?>
        <li>
            <a class="link_btn" id="regenerate" href="###">
                    <img width="16" height="16" src="<?php echo $this->webroot?>images/refresh.png"><?php __('Regenerate')?>
            </a>
        </li>
        <li>
            <a class="link_btn" id="regenerate_reset" href="###">
                    <img width="16" height="16" src="<?php echo $this->webroot?>images/refresh.png"><?php __('Regenerate And Reset')?>
            </a>
        </li>
        <?php endif;?>
    <li>
        <a class="link_back" href="<?php echo $this->webroot; ?>clients/index"> 
            <img width="16" height="16" alt="Back" src="<?php echo $this->webroot; ?>images/icon_back_white.png">&nbsp;Back 
        </a>
    </li>
    <?php endif; ?>
    <li>
        <a href="<?php echo $this->webroot; ?>finances/get_actual_ingress_egress_detail/<?php echo $client_id ?>?export=1&start_time=<?php echo $start_time ?>&end_time=<?php echo $end_time ?>" title="Export" class="link_btn">
            <img width="16" height="16" src="<?php echo $this->webroot; ?>images/export.png" alt="Export">
            Export            
        </a>
    </li>
  </ul>
</div>



<div id="container">
    <table class="list">
        <thead>
            <tr>
                <td colspan="6">Beginning Balance on <?php echo $start_time; ?> 00:00:00 is <?php echo $begin_balance; ?></td>
                <td colspan="7">Ending Balance on <?php echo $end_time; ?> 23:59:59 is <?php echo $end_balance; ?></td>
            </tr>
            <tr>
                <td></td>
                <td colspan="5">Ingress</td>
                <td colspan="4">Egress</td>
                <td></td>
            </tr>
            <tr>
                <td>Date</td>
                <td>Payment Received</td>
                <td>Credit Note Sent</td>
                <td>Debit Note Sent</td>
                <td>Unbilled Incoming Traffic</td>
                <td>Short Charges</td>
                <td>Payment Sent</td>
                <td>Credit Note Received</td>
                <td>Debit Note Received</td>
                <td>Unbilled Outgoing Traffic</td>
                <td>Balance</td>
            </tr>
        </thead>    
        
           <tbody>
            <?php foreach($financehistories as $financehistory): ?>
            <tr>
                <td><?php echo $financehistory['FinanceHistoryActual']['date'];  ?></td>
                <td><?php echo $financehistory['FinanceHistoryActual']['payment_received'];  ?></td>
                <td><?php echo $financehistory['FinanceHistoryActual']['credit_note_sent'];  ?></td>
                <td><?php echo $financehistory['FinanceHistoryActual']['debit_note_sent'];  ?></td>
                <td><?php echo $financehistory['FinanceHistoryActual']['unbilled_incoming_traffic'];  ?></td>
                <td><?php echo $financehistory['FinanceHistoryActual']['short_charges'];  ?></td>
                <td><?php echo $financehistory['FinanceHistoryActual']['payment_sent'];  ?></td>
                <td><?php echo $financehistory['FinanceHistoryActual']['credit_note_received'];  ?></td>
                <td><?php echo $financehistory['FinanceHistoryActual']['debit_note_received'];  ?></td>
                <td><?php echo $financehistory['FinanceHistoryActual']['unbilled_outgoing_traffic'];  ?></td>
                <td><?php echo $financehistory['FinanceHistoryActual']['actual_balance'];  ?></td>
            </tr>
            <?php endforeach; ?>
            <?php if (count($type_sum)): ?>
            <tr>
                <td>Total:</td>
                <td><?php echo $type_sum['payment_received'] ?></td>
                <td><?php echo $type_sum['credit_note_sent'] ?></td>
                <td><?php echo $type_sum['debit_note_sent'] ?></td>
                <td><?php echo $type_sum['unbilled_incoming_traffic'] ?></td>
                <td><?php echo $type_sum['short_charges'] ?></td>
                <td><?php echo $type_sum['payment_sent'] ?></td>
                <td><?php echo $type_sum['credit_note_received'] ?></td>
                <td><?php echo $type_sum['debit_note_received'] ?></td>
                <td><?php echo $type_sum['unbilled_outgoing_traffic'] ?></td>
                <td></td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>
    
    <fieldset style=" clear:both;overflow:hidden;margin-top:10px;" class="query-box">
        <div class="search_title">
            <img src="<?php echo $this->webroot; ?>images/search_title_icon.png">
          Search  
        </div>
        <div style="margin:0px auto; text-align:center;">
        <form name="myform" method="get">
            Period:
            <input type="text" class="input in-text in-input" name="start_time" style="width:120px;" onclick="WdatePicker({dateFmt:'yyyy-MM-dd',lang:'en'})" value="<?php echo $start_time; ?>">
            ~
            <input type="text" class="input in-text in-input" name="end_time" style="width:120px;" onclick="WdatePicker({dateFmt:'yyyy-MM-dd',lang:'en'})" value="<?php echo $end_time; ?>">
            <input type="submit" class="input in-submit" value="Submit">
        </form>
        </div>
   </fieldset>
</div>
<div id="dd"> </div> 
<link rel="stylesheet" type="text/css" href="<?php echo $this->webroot?>easyui/themes/default/easyui.css">
<link rel="stylesheet" type="text/css" href="<?php echo $this->webroot?>easyui/themes/icon.css">
<script type="text/javascript" src="<?php echo $this->webroot?>easyui/jquery.easyui.min.js"></script>

<script>
    var $dd = $('#dd');
    var $massadd = $('#massadd');
    var $regenerate = $('#regenerate');
    var $regenerate_reset = $('#regenerate_reset');
    
    $regenerate.click(function() {
        $.post("<?php echo $this->webroot?>finances/regenerate/<?php echo $client_id; ?>/2", function(data) {
            jQuery.jGrowl('Succeeded!',{theme:'jmsg-success'});
            window.setTimeout("window.location.reload()", 3000);
        }, 'json');
    });
    
    $regenerate_reset.click(function() {
        $.post("<?php echo $this->webroot?>finances/regenerate_reset/<?php echo $client_id; ?>/2", function(data) {
            jQuery.jGrowl('Succeeded!',{theme:'jmsg-success'});
            window.setTimeout("window.location.reload()", 3000);
        }, 'json');
    });
    
    
    $(function() {
        $massadd.click(function() {
            
                var $delete = $('.delete');
                var $payment_panel_received = null;
                var $payment_panel_sent = null;
                var $invoice_panel = null;
                var $massadd_panel = null;
                var $payment_type = $('.payment_type');
                var $back_url = null;
                var $myform = null;
            
                $dd.dialog({  
                title: 'Mass Add',  
                width: 960,  
                height: 600,  
                closed: false,  
                cache: false,  
                resizable: true,
                href: '<?php echo $this->webroot?>finances/mass_add/<?php echo $client_id; ?>',  
                modal: true,
                toolbar: [{
                    text:'Add Payment Received',
                    iconCls:'icon-add',
                    handler:function(){
                            $payment_panel_received.clone().appendTo($massadd_panel);
                    }
                }, '-',{
                    text:'Add Payment Sent',
                    iconCls:'icon-add',
                    handler:function(){
                            $payment_panel_sent.clone().appendTo($massadd_panel);
                    }
                }, '-',{
                    text:'Add Incoming Invoice',
                    iconCls:'icon-add',
                    handler:function(){
                            $invoice_panel.clone().appendTo($massadd_panel);
                    }
                }],
                buttons:[{
                        text:'Save',
                        handler:function(){
                            $myform.submit();
                        }
                },{
                        text:'Close',
                        handler:function(){
                            $dd.dialog('close');
                        }
                }],
                onLoad: function() {
                    $massadd_panel = $('#massadd_panel');
                    $payment_panel_received = $('#payment_panel_received').remove();
                    $payment_panel_sent = $('#payment_panel_sent').remove();
                    $invoice_panel = $('#invoice_panel').remove();
                    $myform = $('#myform');
                    $back_url = $("#back_url").val("<?php echo $this->params['url']['url']; ?>");
                    
                    $payment_type.live('change', function() {
                        var $this = $(this);
                        
                        if ($this.val() == 0) {
                            $this.parent().next().hide();
                        } else {
                            $this.parent().next().show();
                        }
                    });
                        
                    $delete.live('click', function() {
                        $(this).parents('ul').remove();
                    });
                }
            });

            $dd.dialog('refresh', '<?php echo $this->webroot?>finances/mass_add/<?php echo $client_id; ?>');  
        });
    });
</script>