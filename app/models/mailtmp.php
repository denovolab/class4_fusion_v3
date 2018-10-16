<?php
	class Mailtmp extends AppModel{
		var $name = 'Mailtmp';
		var $useTable = 'mail_tmplate';
		var $primaryKey = 'id';
                
                public function get_mail_senders()
                {
                    $sql = "select * from mail_sender order by email asc";
                    return $this->query($sql);
                }
	} 
?>