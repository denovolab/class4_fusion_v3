<style type="text/css">
    #note_window {
        border:1px solid #ccc;
        border-radius: 15px;
        background:#fff;
        max-width:500px;
        max-height: 200px;
        width:500px;
        height:200px;
        display:none;
    }
    
    #note_window p {
        padding:10px;
    }
    
    #note_window h1 {
        text-align:right;
        padding-right:20px;
        paddign-top:10px;
    }
    .list .jsp_resourceNew_style_2 tbody td {font-size: 12px;}
    .list .jsp_resourceNew_style_2 tbody td:hover {font-size: 12px;}
</style>

<?php echo $this->element('magic_css_three');?>
<div id="title">
  <h1>
    <?php  __('Finance')?>
    &gt;&gt;
    <?php __('payment')?>
  </h1>
    <?php
        if(!isset($this->params['pass'][0])) {
            $this->params['pass'][0] = 'incoming';
        }
    ?>
  <ul id="title-menu">
    <?php if (isset($extraSearch)) {?>
    <li> <a class="link_back" href="<?php echo $backurl?>" > <img width="16" height="16" alt="<?php echo __('goback',true);?>" src="<?php echo $this->webroot?>images/icon_back_white.png"> &nbsp;<?php echo __('goback',true);?> </a> </li>
    <?php }?>
    <?php  if ($_SESSION['role_menu']['Finance']['resclis']['model_w']) {?>
    <li> <a class="link_btn" id="add" href="<?php echo $this->webroot?>transactions/add_payment/<?php echo $type =='incoming' ? 'received' : 'sent'; ?>" > <img width="16" height="16"  alt="" src="<?php echo $this->webroot?>images/add.png">&nbsp;
      <?php __('createnew')?>
      </a> </li>
    <?php }?>
    <li>
        <a style="cursor:pointer;display:block;" id="export_excel_btn" class="list-export">
            &nbsp;<?php __('Export'); ?>        
        </a>
    </li>
  </ul>
  <?php  $action=isset($_SESSION['sst_statis_smslog'])?$_SESSION['sst_statis_smslog']:'';
    $w=isset($action['writable'])?$action['writable']:'';?>
</div>

<div id="container">
 <ul class="tabs">
        <li <?php if(!isset($this->params['pass'][0]) || $this->params['pass'][0] == 'incoming') echo 'class="active"'; ?>>
            <a href="<?php echo $this->webroot; ?>transactions/payment/incoming">
                <img width="16" height="16" src="<?php echo $this->webroot; ?>images/list.png"><?php echo __('Received',true);?>				
            </a>
        </li>
        <li <?php if(isset($this->params['pass'][0]) && $this->params['pass'][0] == 'outgoing') echo 'class="active"'; ?>>
            <a href="<?php echo $this->webroot; ?>transactions/payment/outgoing">
                 <img width="16" height="16" src="<?php echo $this->webroot; ?>images/menuIcon.gif"><?php echo __('Sent',true);?>			
            </a>  
        </li>
 </ul>
    <?php
         $data =$p->getDataArray();
         $i = 0;
    ?>
     <?php if(count($data)==0):?>
     <center>
    <div class="msg">No Payment Record for the period of  
        <?php echo isset($_GET['start'])?$_GET['start']:date("Y-m-d",  strtotime("-1 day")); ?> - <?php echo isset($_GET['start'])?$_GET['end']:date("Y-m-d"); ?>
    </div>
     </center>
    <?php else: ?>
    <div id="toppage"></div>
    <table class="list">
        <thead>
            <tr>
                <td></td>
                <td><?php echo __('Payment ID',true);?></td>
                <td><?php echo __('Entered Time',true);?></td>
                <td><?php echo __('Receiving Time',true);?></td>
                <td><?php echo __('Carrier',true);?></td>
                <td><?php echo __('Amount',true);?></td>
                <td><?php echo __('action',true);?></td>
            </tr>
        </thead>
        
            <?php foreach($data as $item): ?>
            <tbody id="resInfo<?php echo $i?>">
             <tr class="row-<?php echo $i%2 +1;?>">
                 <td>
                    <img id="image<?php echo $i; ?>"  onclick="pull('<?php echo $this->webroot?>',this,<?php echo $i;?>)"  class="jsp_resourceNew_style_1"  src="<?php echo $this->webroot?>images/+.gif" title="<?php  __('View All')?>"/>
                </td>
                <td>#<?php echo $item[0]['client_payment_id']; ?></td>
                <td><?php echo substr_replace($item[0]['payment_time'],"",19,-3); ?></td>
                <td><?php echo $item[0]['receiving_time']; ?></td>
                <td><?php echo $item[0]['client']; ?></td>
                <td><?php echo number_format($item[0]['amount'], 5); ?></td>
              <td>
                  <a title="Email To Carrier" href="<?php echo $this->webroot; ?>transactions/notify_carrier/<?php echo $item[0]['client_payment_id']; ?>/<?php echo $this->params['pass'][0] ?>">
                            <img width="16" height="16" src="<?php echo $this->webroot; ?>images/email.gif">
                    </a>
                  <a title="Show Note" href="###" class="note" control="<?php echo $item[0]['client_payment_id']; ?>" title="<?php __('Note'); ?>">
                            <img width="16" height="16" src="<?php echo $this->webroot; ?>images/note.png">
                    </a>
                  <?php if($_SESSION['role_menu']['Payment_Invoice']['delete_payment'] == 1): ?>
                    <a title="Delete" href="<?php echo $this->webroot; ?>transactions/delete_payment/<?php echo $item[0]['client_payment_id']; ?>" onclick="return confirm('Are you sure?')">
                            <img width="16" height="16" src="<?php echo $this->webroot; ?>images/delete.png">
                    </a>
                  <?php endif; ?>
                </td>
            </tr>
            <tr style="height:auto">
                <td colspan="7">
                    <div id="ipInfo<?php echo $i?>" class="jsp_resourceNew_style_2" style="padding:5px"> 
                        <table>
                            <tr>
                                <td>Invoice Number</td>
                                <td>Amount</td>
                                <td>Invoice Period</td>
                                <td>Due Date</td>
                                <td>Total Paid Amount</td>
                                <td>Due Amount</td>
                                <td>Current Payment Paid Amount</td>
                            </tr>
                            <?php foreach($item[0]['invoices'] as $invoice): ?>
                            <tr>
                                <?php if ($invoice[0]['invoice_number'] == null): ?>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>
                                    <?php echo $invoice[0]['amount'] ?>
                                    <a href="###" class="payment_to_invoice" amount="<?php echo $invoice[0]['amount']  ?>" payment="<?php echo $invoice[0]['id'] ?>" client_id="<?php echo $item[0]['client_id']; ?>" title="Payment To Invoice">
                                        <img src="<?php echo $this->webroot ?>images/add.png">
                                    </a>
                                </td>
                                <?php else: ?>
                                <td><?php echo $invoice[0]['invoice_number'] ?></td>
                                <td><?php echo $invoice[0]['total_amount'] ?></td>
                                <td><?php echo $invoice[0]['invoice_start'] ?>~<?php echo $invoice[0]['invoice_end'] ?></td>
                                <td><?php echo $invoice[0]['due_date'] ?></td>
                                <td><?php echo $invoice[0]['pay_amount'] ?></td>
                                <td><?php echo $invoice[0]['total_amount'] - $invoice[0]['pay_amount'] ?></td>
                                <td><?php echo $invoice[0]['amount'] ?></td>
                                <?php endif; ?>
                            </tr>
                            <?php endforeach; ?>
                        </table>
                    </div>
                </td>
            </tr>
            </tbody>
            <?php
                $i++;
                endforeach; 
            ?>
    </table>
   
    <div id="tmppage"> <?php echo $this->element('page');?> </div>
    <?php endif; ?>
    <br />
    <fieldset style=" clear:both;overflow:hidden;margin-top:10px;" class="query-box">
        <div class="search_title">
          <img src="<?php echo $this->webroot; ?>images/search_title_icon.png">
          <?php echo __('Search',true);?>  
        </div>
        <div style="margin:0px auto; text-align:center;">
        <form name="myform" method="get" id="myform">
            <input type="hidden" id="is_export" name="is_export" value="0" />
            Period:
            <input type="text" name="start" style="width:120px;" onclick="WdatePicker({dateFmt:'yyyy-MM-dd',lang:'en'})" value="<?php echo isset($_GET['start'])?$_GET['start']:date("Y-m-d",  strtotime("-1 day")); ?>" />
            ~
            <input type="text" name="end" style="width:120px;" onclick="WdatePicker({dateFmt:'yyyy-MM-dd',lang:'en'})" value="<?php echo isset($_GET['start'])?$_GET['end']:date("Y-m-d"); ?>" />
            GMT:
            <select name="gmt" id='gmt'>
                <option value="-1200">GMT -12:00</option>
                <option value="-1100">GMT -11:00</option>
                <option value="-1000">GMT -10:00</option>
                <option value="-0900">GMT -09:00</option>
                <option value="-0800">GMT -08:00</option>
                <option value="-0700">GMT -07:00</option>
                <option value="-0600">GMT -06:00</option>
                <option value="-0500">GMT -05:00</option>
                <option value="-0400">GMT -04:00</option>
                <option value="-0300">GMT -03:00</option>
                <option value="-0200">GMT -02:00</option>
                <option value="-0100">GMT -01:00</option>
                <option value="+0000" selected="selected" >GMT +00:00</option>
                <option value="+0100">GMT +01:00</option>
                <option value="+0200">GMT +02:00</option>
                <option value="+0300">GMT +03:00</option>
                <option value="+0330">GMT +03:30</option><option value="+0400">GMT +04:00</option><option value="+0500">GMT +05:00</option><option value="+0600">GMT +06:00</option><option value="+0700">GMT +07:00</option><option value="+0800">GMT +08:00</option><option value="+0900">GMT +09:00</option><option value="+1000">GMT +10:00</option><option value="+1100">GMT +11:00</option><option value="+1200">GMT +12:00</option>
            </select>
            
            <?php echo __('carrier',true);?>:
            <select name="client_id">
                <option value="">All</option>
                <?php foreach($clients as $client): ?>
                <option value="<?php echo $client[0]['client_id'] ?>" <?php if(isset($_GET['client_id']) && $_GET['client_id']==$client[0]['client_id']){echo "selected=\"selected\"";}?>><?php echo $client[0]['name'] ?></option>
                <?php endforeach; ?>
            </select>
            <?php echo __('Amount',true);?>:
            <input type="text" name="amount_a" value="<?php echo $amount_a ?>" />
            ~
            <input type="text" name="amount_b" value="<?php echo $amount_b ?>" />
            <input type="submit" value="<?php echo __('submit',true);?>" />
        </form>
        </div>
   </fieldset>
</div>

<div id="note_window">
    <h1>
        <a href="###" id="note_window_close">
            <img src="<?php echo $this->webroot ?>images/delete.png" />
        </a>
    </h1>
    <p>
        
    </p>
</div>
<div id="dd"> </div>

<script src="<?php echo $this->webroot?>js/ajaxTable.js" type="text/javascript"></script>
<scirpt type="text/javascript" src="<?php $this->webroot ?>js/jquery.center.js"></scirpt>
<link rel="stylesheet" type="text/css" href="<?php echo $this->webroot?>easyui/themes/default/easyui.css">
<link rel="stylesheet" type="text/css" href="<?php echo $this->webroot?>easyui/themes/icon.css">
<script type="text/javascript" src="<?php echo $this->webroot?>easyui/jquery.easyui.min.js"></script>
<script>
$(function() {
    var $note_window = $('#note_window');
    var $note_window_close = $('#note_window_close');
    var $payment_to_invoice = $('.payment_to_invoice');
    var $dd = $('#dd');
    
    $('.note').click(function() {
        var payment_id = ($(this).attr('control'));
        
        $.ajax({
            'url' : '<?php echo $this->webroot ?>/transactions/get_note',
            'type' : 'POST',
            'dataType' : 'text',
            'data' : {'id' : payment_id},
            'success' : function(data) {
                $('p', $note_window).text(data);
                $note_window.center().fadeIn();
            }
        });
        
        return false;
        
    });
    
    
            
        $payment_to_invoice.click(function() {
            var invoices = new Array();
            var $this = $(this);
            var payment_invoice_id = $this.attr('payment');
            var client_id = $this.attr('client_id');
            var $invoice_table = null;
            var total_amount = Number($this.attr('amount'));
            var $invoice_form = null;
            var $delete_invoice = null;

            $dd.dialog({  
               title: 'Payment To Invoice',  
               width: 960,  
               height: 600,  
               closed: false,  
               cache: false,  
               resizable: true,
               href: '<?php echo $this->webroot?>transactions/payment_to_invoice/' + payment_invoice_id + '/' + client_id + '/<?php echo $type =='incoming' ? 'received' : 'sent'; ?>',  
               modal: true,
               toolbar: [{
                    text:'Add Invoice',
                    iconCls:'icon-add',
                    handler:function(){
                           var $this = $(this);
                           $this.css('visibility', 'hidden');
                           $.ajax({
                                'url'     : '<?php echo $this->webroot ?>transactions/get_one_invoice',
                                'type'    : 'POST',
                                'dataType': 'json',
                                'data'    : {'invoices[]' : invoices, 'client_id' : client_id, 'type' : "<?php echo $type =='incoming' ? 'received' : 'sent'; ?>"},
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
                                    })
                                    $this.css('visibility', 'visible');
                                }
                            });
                        
                    }
                }],
               buttons:[{
                       text:'Submit',
                       handler:function(){
                           var $invoice_paid = $('.invoice_paid');
                           var paid_amount = 0;
                            $.each($invoice_paid, function(index, item) {
                                paid_amount += Number($(this).val());
                            });
                            var remain_amount = total_amount - paid_amount;
                            
                            if (remain_amount < 0) {
                                 jQuery.jGrowl("The Remain Amount must be greater or equal than 0!",{theme:'jmsg-error'});
                                 return false;
                            }
                            
                            $invoice_form.submit();
                       }
               },{
                       text:'Close',
                       handler:function(){
                           $dd.dialog('close');
                       }
               }],
               onLoad : function() {
                    $invoice_table = $("#invoice_list tbody");
                    $delete_invoice = $('.delete_invoice');
                    $delete_invoice.live('click', function() {
                        $(this).parents("tr").remove();
                    });
                    $invoice_form = $('#invoice_form');
               }
           });

           $dd.dialog('refresh', '<?php echo $this->webroot?>transactions/payment_to_invoice/' + payment_invoice_id + '/' + client_id + '/<?php echo $type =='incoming' ? 'received' : 'sent'; ?>');  
       });
    
    
    
    
    $note_window_close.click(function() {
        $note_window.hide('slow');
    });
    
    
    
    <?php
        if(isset($_GET['gmt'])) echo "$('#gmt').val('{$_GET['gmt']}');";
    ?>
    $('#export_excel_btn').click(function() {
        $('#is_export').val('1');
        $('#myform').submit();
    });
});    
</script>