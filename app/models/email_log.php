<?php

class EmailLog extends AppModel {
    var $name = 'EmailLog';
    var $useTable = "email_log"; 
    var $primaryKey = "id";
    
    public function get_carriers()
    {
        $clients = $this->query("select client_id, name from client order by name asc");
        return $clients;
    }
}


?>
