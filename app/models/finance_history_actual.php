<?php

class FinanceHistoryActual extends AppModel
{
    var $name = 'FinanceHistoryActual';
    var $useTable = 'balance_history_actual';
    var $primaryKey = 'id';
    
    public function get_current_finance_detail($client_id) {
            $sql = <<<EOT
   select * from current_balance_detail({$client_id}) as (actual_ingress_balance numeric,actual_egress_balance numeric,
actual_balance numeric,mutual_ingress_balance numeric, mutual_egress_balance numeric, 
mutual_balance numeric,payment_received numeric,credit_note_sent numeric,debit_note_sent numeric,unbilled_incoming_traffic numeric,
short_charges numeric,payment_sent numeric,credit_note_received numeric,
debit_note_received numeric,unbilled_outgoing_traffic numeric,	invoice_set numeric,invoice_received numeric) 
EOT;
            $data = $this->query($sql);
            return $data[0][0];
        }
    
}