<?php

class InvoiceLog extends AppModel
{

    var $name = 'InvoiceLog';
    var $useTable = 'invoice_log';
    var $primaryKey = 'id';
    
    
    public function get_invoices($log_id)
    {
        $sql = "select invoice.invoice_id,client.name, invoice.total_amount, 
invoice.invoice_start, invoice.invoice_end, invoice.due_date, invoice.status
from invoice
left join client on invoice.client_id 
 = client.client_id
where invoice_log_id = {$log_id}";
        return $this->query($sql);
    }
    
}