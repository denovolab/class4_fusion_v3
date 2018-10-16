<div id="title">
  <h1>
    <?php  __('Finance')?>
    &gt;&gt;
    <?php __('Invoices')?>
  </h1>
    <ul id="title-search">
        <li style="display: list-item;" id="title-search-adv" onclick="advSearchToggle();" title="advanced search »"></li>
    </ul>
  <ul id="title-menu">
    	    <li> <a href="<?php echo $this->webroot; ?>pr/pr_invoices/add_incoming" class="link_btn"> <img width="16" height="16" src="<?php echo $this->webroot; ?>/images/add.png" alt=""><?php echo __('Create New',true);?> </a> </li>
            <li>
                <form action="" method="get" id="export_panel">
                    <input type="hidden" name="is_export" value="1" />
                    <a class="list-export" id="export_excel_btn" style="cursor:pointer;display:block;">
                        &nbsp;<?php __('Export'); ?>
                    </a>
                </form>
            </li>
  </ul>
    
</div>
<div id="container">
    <fieldset style="margin-left: 1px; width: 100%; display: <?php  echo isset($url_get['advsearch']) ? 'block' :'none';?>;" id="advsearch" class="title-block">
    <form method="get" action="" id="search_panel">
      <input type="hidden" name="advsearch" class="input in-hidden">
      <table>
        <tr>
            <td>Carrier</td>
            <td>
                <select name="client">
                    <option value="">All</option>
                    <?php foreach($clients as $client): ?>
                    <option <?php if(isset($_GET['client']) && $_GET['client'] == $client[0]['client_id']) echo 'selected="selected"'; ?> value="<?php echo $client[0]['client_id'] ?>"><?php echo $client[0]['name'] ?></option>
                    <?php endforeach; ?>
                </select>
            </td>
            <td>
                <input type="submit" value="Search" />
            </td>
        </tr>
      </table>
    </form>
  </fieldset>
<ul class="tabs">
    <li><a href="<?php echo $this->webroot?>pr/pr_invoices/view/0"><img width="16" height="16" src="<?php echo $this->webroot?>images/invoice1.png"><?php echo __('Auto Inbound Invoice')?></a></li>
    <li><a href="<?php echo $this->webroot?>pr/pr_invoices/view/1"><img width="16" height="16" src="<?php echo $this->webroot?>images/invoice2.png"><?php echo __('Manual Inbound Invoice')?></a></li>
    <li><a href="<?php echo $this->webroot?>pr/pr_invoices/view/2"><img width="16" height="16" src="<?php echo $this->webroot?>images/invoice3.png"><?php echo __('Auto Outbound Invoice')?></a></li>
    <li><a href="<?php echo $this->webroot?>pr/pr_invoices/view/3"><img width="16" height="16" src="<?php echo $this->webroot?>images/invoice4.png"><?php echo __('Vendor Invoice')?></a></li>
    <li class="active"><a href="<?php echo $this->webroot?>pr/pr_invoices/incoming_invoice"><img width="16" height="16" src="<?php echo $this->webroot?>images/invoice5.png"> <?php echo __('Incoming Outbound Invoice')?></a></li>
  </ul>
  <?php $d = $p->getDataArray();if (count($d) == 0) {?>
  <div class="msg"><?php echo __('no_data_found')?></div>
  <?php } else {?>
<div id="toppage"></div>
<form action="<?php echo $this->webroot ?>pr/pr_invoices/incoming_invoice_mass_edit" method="post">
<table class="list">

        <thead>
          <tr>
            <?php  if ($_SESSION['role_menu']['Finance']['pr_invoices']['model_x']) {?>
            <td>
            <input type="checkbox" id="selectAll"  value="1" name="selector" class="input in-checkbox"></td>
            <?php }?>
            <td><?php echo $appCommon->show_order('invoice_number',__('Invoice No',true))?></td>
           <?php  if ($_SESSION['role_menu']['Finance']['pr_invoices']['model_x']) {?>
           <td>&nbsp;</td>
           <?php }?>
            <td><?php echo $appCommon->show_order('client',__('Carriers',true))?></td>
            <td><?php echo $appCommon->show_order('disputed',__('Invoice Period ',true))?></td>
            <td><?php echo $appCommon->show_order('total_amount',__('Amt Gross',true))?></td>
            <td>&nbsp;<?php echo __('Amt Paid')?></td>
            <td><?php echo $appCommon->show_order('due_date',__('Due Date',true))?></td>
            <td></td>
			
           <?php  if ($_SESSION['role_menu']['Finance']['pr_invoices']['model_w']) {?>
            <td class="last"><?php echo __('action',true);?></td>
			<?php }?>
          </tr>
          <tr>
          <?php  if ($_SESSION['role_menu']['Finance']['pr_invoices']['model_x']) {?>
          	<td>&nbsp;</td>
		  <?php }?>
            <td><?php echo $appCommon->show_order('invoice_time',__('Invoice Date',true))?></td>
            
             <?php  if ($_SESSION['role_menu']['Finance']['pr_invoices']['model_x']) {?>
             <td>&nbsp;</td>
             <?php }?>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <?php  if ($_SESSION['role_menu']['Finance']['pr_invoices']['model_x']) {?><td>&nbsp;</td><?php }?>
           <?php  if ($_SESSION['role_menu']['Finance']['pr_invoices']['model_w']) {?> <td>&nbsp;</td>
            <td class="last">&nbsp;</td>
            <?php }?>
          </tr>
        </thead>
        <tbody>
          <?php
		$mydata = $p->getDataArray();
		$loop = count($mydata);
		for ($i=0;$i<$loop;$i++) { 
		$state = $mydata[$i][0]['state'];
	?>
          <tr class="<?php echo ($state == -1) ? 'row-2 row-3' : 'row-1'; ?>">
            <?php  if ($_SESSION['role_menu']['Finance']['pr_invoices']['model_x']) {?>
            <td align="center"><input type="checkbox" 
	    value="<?php echo $mydata[$i][0]['invoice_id']?>"
	    id="ids-<?php echo $mydata[$i][0]['invoice_id']?>" name="ids[]" class="input in-checkbox to_be_selected"></td>
        <?php }?>
            <td rel="tooltip" id="ci_<?php echo $i?>">
            <?php  if ($_SESSION['role_menu']['Finance']['pr_invoices']['model_w']) {?>
            <a  href="<?php echo $this->webroot?>invoices/edit/<?php echo  $mydata[$i][0]['invoice_id']?>" class="link_width"><b><?php echo $mydata[$i][0]['invoice_number']?></b></a>
            <?php }else{echo $mydata[$i][0]['invoice_number'];}?>
            <br>
              <small title=""><?php echo $mydata[$i][0]['invoice_time']?></small></td>
            <?php  if ($_SESSION['role_menu']['Finance']['pr_invoices']['model_x']) {?>
<!--            <td><a title="<?php echo __('download')?>" 
     href="<?php echo $this->webroot.'pr/pr_invoices/createpdf_invoice/'.$mydata[$i][0]['invoice_number']?>/2" > <img width="16" height="16" src="<?php echo $this->webroot?>images/download.png"> </a></td>-->
            <td>
                <?php if ($mydata[$i][0]['pdf_path'] != 'NULL'): ?>
                <a href="<?php echo $this->webroot; ?>upload/incoming_invoice/<?php echo $mydata[$i][0]['pdf_path']  ?>"><img width="16" height="16" src="<?php echo $this->webroot?>images/download.png"> </a>
                <?php endif; ?>
            </td>
     <?php }?>
            <td style="text-align:left;"><?php if (empty($mydata[$i][0]['res'])) {?>
              <img width="16" height="16" title="Client" src="<?php echo $this->webroot?>images/menuIcon.gif"> <?php echo $mydata[$i][0]['client']?></td>
            <?php } else {
    									echo $mydata[$i][0]['res'];
    								}
    				?>
      
            <td align="center"><small> <?php echo $mydata[$i][0]['invoice_start']?><br>
              <?php echo $mydata[$i][0]['invoice_end']?> </small></td>
            <td align="right"><strong><?php echo number_format($mydata[$i][0]['total_amount'], 2);?></strong> <br>
<!--              <small title="Paid" class="zero">Credit: <?php echo empty($mydata[$i][0]['client_credit'])?'0':$mydata[$i][0]['client_credit'] < 0 ? '(' . str_replace('-', '', $mydata[$i][0]['client_credit']) .')':$mydata[$i][0]['client_credit'];?></small> <br>
              <small title="Paid" class="zero">Offset: <?php echo empty($mydata[$i][0]['client_credit'])?'0':$mydata[$i][0]['client_offset'] < 0 ? '(' . str_replace('-', '', $mydata[$i][0]['client_offset']) .')':$mydata[$i][0]['client_offset'];?></small> <br>
              <small title="Paid" class="zero">Paid: <?php echo (empty($mydata[$i][0]['invoice_payment'])?'0':$mydata[$i][0]['invoice_payment']) < 0 ? '(' . str_replace('-', '', $mydata[$i][0]['invoice_payment']) .')':$mydata[$i][0]['invoice_payment'];?></small> <br>
              <small title="Paid" class="zero">Owe: <?php echo empty($mydata[$i][0]['invoice_payment'])?abs($mydata[$i][0]['total_amount']):number_format(abs($mydata[$i][0]['total_amount'])+$mydata[$i][0]['client_offset']-$mydata[$i][0]['invoice_payment']-$mydata[$i][0]['client_credit'],5,'.',',');?> </small> <br>
              <small title="Paid" class="zero">Balance: <?php echo (empty($mydata[$i][0]['balance']) ? 0 : $mydata[$i][0]['balance']) < 0 ? '(' . str_replace('-', '', $mydata[$i][0]['balance']) .')':$mydata[$i][0]['balance'];?></small>-->
            </td>
            <td align="center"><?php if ($mydata[$i][0]['paid'] == false) {?>
              <img width="16" height="16" title="Invoice is not paid" src="<?php echo $this->webroot?>images/status_notclosed.gif">
              <?php } else {?>
              <img width="16" height="16" title="Invoice is not paid" src="<?php echo $this->webroot?>images/status_closed.gif">
              <?php }?></td>
            <td align="center"><span class="warn">
              <?php 
        	if(strpos($mydata[$i][0]['due_inteval'], 'days') && $mydata[$i][0]['due_inteval']<0){
        	 echo abs($mydata[$i][0]['due_inteval']);
        			 ?>
              days ago
              <?php }?>
              </span> <br>
              <small><?php echo $mydata[$i][0]['due_date']?></small></td>
           
           <?php  if ($_SESSION['role_menu']['Finance']['pr_invoices']['model_w']) {?>
            
           <td><a  href="<?php echo $this->webroot?>pr/pr_invoices/payment_to_invoice/<?php echo $mydata[$i][0]['invoice_id']?>/2/<?php echo $mydata[$i][0]['client_id']?>/incoming_invoice"> <img width="16" height="16" src="<?php echo $this->webroot?>images/balanceOperations.gif"> </a></td>
            
            
            <td class="last">
                
                <a href="<?php echo $this->webroot?>pr/pr_invoices/credit_note/<?php echo $mydata[$i][0]['invoice_number'];?>" title="Credit Note">
                    <img src="<?php echo $this->webroot;?>images/add.png" />
                </a>
                
                <a href="<?php echo $this->webroot?>pr/pr_invoices/debit/<?php echo $mydata[$i][0]['invoice_number'];?>" title="Debit">
                    <img src="<?php echo $this->webroot;?>images/add.png" />
                </a>
                <!--
                    <a href="<?php echo $this->webroot; ?>pr/pr_invoices/edit_incoming/<?php echo $mydata[$i][0]['invoice_id'];?>">
                        <img src="<?php echo $this->webroot; ?>images/editicon.gif">
                    </a>
                -->
                <a href="<?php echo $this->webroot; ?>pr/pr_invoices/recon/<?php echo $mydata[$i][0]['invoice_id'];?>" title="<?php echo __('Reconcile',true);?>">
                    <img src="<?php echo $this->webroot; ?>images/reconcile.png" />
                </a>   
                
                <a href="###" title="Payment List" class="payment_list" invoice_id="<?php echo $mydata[$i][0]['invoice_id'];?>">
                    <img src="<?php echo $this->webroot; ?>images/payment.png">
                </a>
                <a href="###" title="Apply Payment" class="apply_payment" invoice_id="<?php echo $mydata[$i][0]['invoice_id'];?>">
                    <img src="<?php echo $this->webroot; ?>images/assign_payment.png">
                </a>
                
                <a href="<?php echo $this->webroot; ?>pr/pr_invoices/delete_incoming/<?php echo $mydata[$i][0]['invoice_id'];?>" title="<?php echo __('Delete',true);?>">
                    <img src="<?php echo $this->webroot; ?>images/delete.png" />
                </a>   
            </td><?php }?>
          </tr>
  
          <?php } ?>
        </tbody>
        </table>
        <div style="margin: 10px 0px;">&nbsp;
        Action:
        <select style="width: 150px;" class="input in-select select" name="action" id="action">
          <option value=""></option>
          <option value="1">Delete Selected</option>
        </select>
        <input type="submit" class="input in-submit" value="Submit">
      </div>
</form>
<div id="tmppage"> <?php echo $this->element('page')?> </div>
<?php } ?>
</div>
<div id="dd"> </div>
<link rel="stylesheet" type="text/css" href="<?php echo $this->webroot?>easyui/themes/default/easyui.css">
<link rel="stylesheet" type="text/css" href="<?php echo $this->webroot?>easyui/themes/icon.css">
<script type="text/javascript" src="<?php echo $this->webroot?>easyui/jquery.easyui.min.js"></script>

<script type="text/javascript">
    $(function() {
        $('#export_excel_btn').click(function() {
            $('#export_panel').submit();
        });
        
        $('#selectAll').change(function() {
            $(".to_be_selected").attr('checked', $(this).attr('checked'));
        });
        
        var $dd = $('#dd');
    var $payment_list = $('.payment_list');
    var $apply_payment = $('.apply_payment');
    
    $payment_list.click(function() {
            var invoice_id = $(this).attr('invoice_id');
            $dd.dialog({  
               title: 'Payment List',  
               width: 960,  
               height: 600,  
               closed: false,  
               cache: false,  
               resizable: true,
               href: '<?php echo $this->webroot?>pr/pr_invoices/get_invoice_payments/' + invoice_id,  
               modal: true,
               buttons:[{
                       text:'Close',
                       handler:function(){
                           $dd.dialog('close');
                       }
               }]
           });

           $dd.dialog('refresh', '<?php echo $this->webroot?>pr/pr_invoices/get_invoice_payments/' + invoice_id);  
       });
       
    $apply_payment.click(function() {
       var invoice_id = $(this).attr('invoice_id');
            $dd.dialog({  
               title: 'Apply Payment',  
               width: 960,  
               height: 600,  
               closed: false,  
               cache: false,  
               resizable: true,
               href: '<?php echo $this->webroot?>pr/pr_invoices/apply_payment/' + invoice_id + '/incoming',  
               modal: true,
               buttons:[{
                       text:'Submit',
                       handler:function(){
                           var $payment_form = $('#payment_form');
                           $payment_form.submit();
                       }
               },{
                       text:'Close',
                       handler:function(){
                           $dd.dialog('close');
                       }
               }]
           });

           $dd.dialog('refresh', '<?php echo $this->webroot?>pr/pr_invoices/apply_payment/' + invoice_id+ '/incoming');  
   });   
       
       
    });
</script>