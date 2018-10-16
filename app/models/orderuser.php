<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of clients
 *
 * @author hewenxiang
 */
class Orderuser extends AppModel {
    var $name = 'Orderuser';
    var $useTable = 'order_user';
    
        function insert_user($client_id) {
        $data = array(
            'Orderuser'=>array(
                    "name" => $_POST['username'],
                    "password" => md5($_POST['password']),
                    "question" => $_POST['security_question'],
                    "answer" => md5($_POST['security_answer']),
                    'corporate_contact_name' => $_POST['corporatecontactname'],
                    'corporate_contact_phone' => $_POST['corporatecontactphone'],
                    'corporate_contact_cell' => $_POST['corporatecontactcell'],
                    'corporate_contact_email' => $_POST['corporatecontactemail'],
                    //'mail_validate_code' => md5($_POST['letters']),
                    'company_name' => $_POST['company_name'],
                    'addr1' => $_POST['address1'],
                    'addr2' => $_POST['address2'],
                    'city' => $_POST['city'],
                    'province' => $_POST['stateorprovince'],
                    'post_code' => $_POST['ziporpostcode'],
                    'country' => $_POST['country'],
                    'alternate_emails' => $_POST['alternateemail'],
                    'corporate_contact_fax' => $_POST['corporatecontactfax'],
                    'corporate_registration_country' => $_POST['countryorregion'],
                    'primary_contact_name' => $_POST['primary_contact_name'],
                    'primary_job_tite' => $_POST['primary_job_tite'],
                    'primary_email' => $_POST['primary_email'],
                    'primary_fax' => $_POST['primary_fax'],
                    'primary_phone' => $_POST['primary_phone'],
                    'primary_mobile' => $_POST['primary_mobile'],
                    'primary_yahoo' => $_POST['primary_yahoo'],
                    'primary_msn' => $_POST['primary_msn'],
                    'primary_skype' => $_POST['primary_skype'],
                    'primary_aql' => $_POST['primary_aql'],
                    'primary_icq' => $_POST['primary_icq'],
                    'primary_qq' => $_POST['primary_qq'],
                    'technical_contact_name' => $_POST['technical_contact_name'],
                    'technical_job_tite' => $_POST['technical_job_tite'],
                    'technical_email' => $_POST['technical_email'],
                    'technical_fax' => $_POST['technical_fax'],
                    'technical_phone' => $_POST['technical_phone'],
                    'technical_mobile' => $_POST['technical_mobile'],
                    'technical_yahoo' => $_POST['technical_yahoo'],
                    'technical_msn' => $_POST['technical_msn'],
                    'technical_skype' => $_POST['technical_skype'],
                    'technical_aql' => $_POST['technical_aql'],
                    'technical_icq' => $_POST['technical_icq'],
                    'technical_qq' => $_POST['technical_qq'],
                    'billing_contact_name' => $_POST['billing_contact_name'],
                    'billing_job_tite' => $_POST['billing_job_tite'],
                    'billing_email' => $_POST['billing_email'],
                    'billing_fax' => $_POST['billing_fax'],
                    'billing_phone' => $_POST['billing_phone'],
                    'billing_mobile' => $_POST['billing_mobile'],
                    'billing_yahoo' => $_POST['billing_yahoo'],
                    'billing_msn' => $_POST['billing_msn'],
                    'billing_skype' => $_POST['billing_skype'],
                    'billing_aql' => $_POST['billing_aql'],
                    'billing_icq' => $_POST['billing_icq'],
                    'billing_qq' => $_POST['billing_qq'],
                    'paypal' => $_POST['paypal'],
                    'bank_name' => $_POST['bankname'],
                    'bank_address' => $_POST['bankaddress'],
                    'bank_account_name' => $_POST['accountname'],
                    'bank_routing_number' => $_POST['routingnumber'],
                    'bank_account_number' => $_POST['accountnumber'],
                    'bank_swift' => $_POST['swiftiban'],
                    'bank_notes' => $_POST['notes'],
                    //'currency_preference' => $_POST['currency_preference'],
                    'intermediately_bank' => $_POST['intermediately_bank'],
                    'ach' => $_POST['ach'],
                    'client_id' => $client_id,
                    'status' => 0,
                )
            );
        //var_dump($data);
        return $this->save($data);
    }
    function edit_user() {
        $data = array(
            'Orderuser'=>array(
                    'id' => $_POST['id'],
                    //"name" => $_POST['username'],
                    "password" => md5($_POST['password']),
                    //"question" => $_POST['security_question'],
                    //"answer" => md5($_POST['security_answer']),
                    'corporate_contact_name' => $_POST['corporatecontactname'],
                    'corporate_contact_phone' => $_POST['corporatecontactphone'],
                    'corporate_contact_cell' => $_POST['corporatecontactcell'],
                    'corporate_contact_email' => $_POST['corporatecontactemail'],
                    //'mail_validate_code' => md5($_POST['letters']),
                    'company_name' => $_POST['company_name'],
                    'addr1' => $_POST['address1'],
                    'addr2' => $_POST['address2'],
                    'city' => $_POST['city'],
                    'province' => $_POST['stateorprovince'],
                    'post_code' => $_POST['ziporpostcode'],
                    'country' => $_POST['country'],
                    'alternate_emails' => $_POST['alternateemail'],
                    'corporate_contact_fax' => $_POST['corporatecontactfax'],
                    'corporate_registration_country' => $_POST['countryorregion'],
                    'primary_contact_name' => $_POST['primary_contact_name'],
                    'primary_job_tite' => $_POST['primary_job_tite'],
                    'primary_email' => $_POST['primary_email'],
                    'primary_fax' => $_POST['primary_fax'],
                    'primary_phone' => $_POST['primary_phone'],
                    'primary_mobile' => $_POST['primary_mobile'],
                    'primary_yahoo' => $_POST['primary_yahoo'],
                    'primary_msn' => $_POST['primary_msn'],
                    'primary_skype' => $_POST['primary_skype'],
                    'primary_aql' => $_POST['primary_aql'],
                    'primary_icq' => $_POST['primary_icq'],
                    'primary_qq' => $_POST['primary_qq'],
                    'technical_contact_name' => $_POST['technical_contact_name'],
                    'technical_job_tite' => $_POST['technical_job_tite'],
                    'technical_email' => $_POST['technical_email'],
                    'technical_fax' => $_POST['technical_fax'],
                    'technical_phone' => $_POST['technical_phone'],
                    'technical_mobile' => $_POST['technical_mobile'],
                    'technical_yahoo' => $_POST['technical_yahoo'],
                    'technical_msn' => $_POST['technical_msn'],
                    'technical_skype' => $_POST['technical_skype'],
                    'technical_aql' => $_POST['technical_aql'],
                    'technical_icq' => $_POST['technical_icq'],
                    'technical_qq' => $_POST['technical_qq'],
                    'billing_contact_name' => $_POST['billing_contact_name'],
                    'billing_job_tite' => $_POST['billing_job_tite'],
                    'billing_email' => $_POST['billing_email'],
                    'billing_fax' => $_POST['billing_fax'],
                    'billing_phone' => $_POST['billing_phone'],
                    'billing_mobile' => $_POST['billing_mobile'],
                    'billing_yahoo' => $_POST['billing_yahoo'],
                    'billing_msn' => $_POST['billing_msn'],
                    'billing_skype' => $_POST['billing_skype'],
                    'billing_aql' => $_POST['billing_aql'],
                    'billing_icq' => $_POST['billing_icq'],
                    'billing_qq' => $_POST['billing_qq'],
                    'paypal' => $_POST['paypal'],
                    'bank_name' => $_POST['bankname'],
                    'bank_address' => $_POST['bankaddress'],
                    'bank_account_name' => $_POST['accountname'],
                    'bank_routing_number' => $_POST['routingnumber'],
                    'bank_account_number' => $_POST['accountnumber'],
                    'bank_swift' => $_POST['swiftiban'],
                    'bank_notes' => $_POST['notes'],
                    //'currency_preference' => $_POST['currency_preference'],
                    'intermediately_bank' => $_POST['intermediately_bank'],
                    'ach' => $_POST['ach'],
                    'client_id' => $_POST['client_id'],
                    
                )
            );
        return $this->save($data);
    }
}

?>
