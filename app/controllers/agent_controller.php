
<?php

class AgentController extends AppController
{

    var $name = 'Agent';
    var $helpers = array('javascript', 'html', 'AppClients', 'Common');
    var $uses = array("prresource.Gatewaygroup","Agent", "Client", 'Clients', 'Credit', 'Orderuser');


    function index()
    {
        $this->pageTitle = 'Management/Agents';
        
        $where = "1=1";
        if(!empty($_GET['search'])){
            $where .= " and name ilike '%{$_GET['search']}%' ";
        }
        
        $sql = "select count(*) from agent_client where {$where} ";


        $temp = isset($_SESSION['paging_row']) ? $_SESSION['paging_row'] : 100;
        empty($_GET['page']) ? $currPage = 1 : $currPage = $_GET['page'];
        empty($_GET['size']) ? $pageSize = $temp : $pageSize = $_GET['size'];
        $_SESSION['paging_row'] = $pageSize;

        $count = $this->Client->query($sql);
        $count = $count[0][0]['count'];
        require_once 'MyPage.php';
        $page = new MyPage ();
        $page->setTotalRecords($count);
        $page->setCurrPage($currPage);
        $page->setPageSize($pageSize);
        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();
        $offset = $currPage * $pageSize;

        $sql = "SELECT * from agent_client  where {$where} order by name LIMIT {$pageSize} OFFSET {$offset}";



        //echo $sql;
        $data = $this->Client->query($sql);

        //echo $count;
        //var_dump($data);

        $page->setDataArray($data);
        $this->set('p', $page);
    }
    
    public function add_agent(){
        $this->autoLayout = FALSE;
        $this->autoRender = FALSE;
        Configure::write('debug', 0);
        
        $agent_name = $_POST['name'];
        $pwd = md5($_POST['pwd']);
        $company_name = $_POST['company_name'];
        $email = $_POST['email'];
        $phone_number = $_POST['phone_number'];
        $domain = $_POST['domain'];
        $status = $_POST['status'];
        $ip = $_POST['ip'];
        $data = array('status'=>'');

         if(!preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $email)){
            $data['status'] = "email_error";
        }else{
            if (!empty($agent_name))
            {
                $res = $this->Client->query("select count(*) from agent_client  where name = '{$agent_name}' or domain_name = '{$domain}' ");
                if ($res[0][0]['count'] != 0)
                {
                    $data['status'] = "isHave";
                } else
                {
                    $res = $this->Client->query("insert into agent_client (ip,name,pwd,company_name,email,phone_number,domain_name,status) 
                                        values 
                                        ('{$ip}','{$agent_name}','{$pwd}','{$company_name}' ,'{$email}','{$phone_number}','{$domain}',{$status})");
                    $this->Client->logging(0, 'Agent', "Agent Name:{$agent_name}");
                    $this->Client->create_json_array('', 201, __('The Agent [' . $agent_name . '] is created successfully', true));
                    $data['status'] = "success";
                }
            } else
            {
                $data['status'] = "isEmpty";
            }
        }
        echo json_encode($data);
    }
    
    public function save_pwd()
    {
        $this->autoLayout = FALSE;
        $this->autoRender = FALSE;
        Configure::write('debug', 0);
        $data = array('status'=>'');
        $id = $_POST['id'];
        $pwd = md5($_POST['pwd']);
        $agent_name = $_POST['name'];
      
        $this->Client->query("update agent_client set pwd = '{$pwd}' where client_id = {$id} ");
        $this->Client->logging(2, 'Agent', "Agent Name:{$agent_name} (Change password)");
        $this->Client->create_json_array('', 201, __('The Agent [' . $agent_name . '] is modified successfully!', true));
        $data['status'] = "success";
        
        echo json_encode($data);
    }

    
    public function save_agent()
    {
        $this->autoLayout = FALSE;
        $this->autoRender = FALSE;
        Configure::write('debug', 0);
        $data = array('status'=>'');
        $id = $_POST['id'];
        $agent_name = $_POST['name'];
        $company_name = $_POST['company_name'];
        $email = $_POST['email'];
        $phone_number = $_POST['phone_number'];
        $domain = $_POST['domain'];
        $status = $_POST['status'];
        $ip = $_POST['ip'];
        
        if(!preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $email)){
            $data['status'] = "email_error";
        }else{
            if (!empty($agent_name))
            {
                $res = $this->Client->query("select count(*) from agent_client  where (name = '{$agent_name}' or domain_name = '{$domain}') and client_id != {$id} ");

                if ($res[0][0]['count'] != 0)
                {
                    $data['status'] = "isHave";
                } else
                {
                    $this->Client->query("update agent_client set ip='{$ip}', name = '{$agent_name}',
                    company_name = '{$company_name}' , email = '{$email}' 
                    ,phone_number = '{$phone_number}',domain_name = '{$domain}',status = {$status}
                    where client_id = {$id} ");
                    $this->Client->logging(2, 'Agent', "Agent Name:{$agent_name}");
                    $this->Client->create_json_array('', 201, __('The Agent [' . $agent_name . '] is modified successfully!', true));
                    $data['status'] = "success";
                }
            } else
            {
                $data['status'] = "isEmpty";
            }
        }
        
        echo json_encode($data);
    }
    

    function default_agent(){
        $this->pageTitle = 'Management/Agents';
        
        $sql = "SELECT * from agent_client  where is_default = 't'  ";

        //echo $sql;
        $data = $this->Client->query($sql);
        
        if(!empty($_POST)){
            
            $name = $_POST['name'];
            $email = $_POST['email'];
            $phone_number = $_POST['phone_number'];
            $domain_name = $_POST['domain_name'];
            $company_name = $_POST['company_name'];
            $ip = $_POST['ip'];
            
            $res = $this->Client->query("select count(*) from agent_client  where (name = '{$name}' or domain_name = '{$domain_name}') and is_default = 'f' ");
            
            if($res[0][0]['count'] == 0){
                if(!preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $email)){
                    $this->Agent->create_json_array('', 101, __('The email field must contain a valid email address!', true));
                }else{
                    if(count($data) == 0){
                        $defatul_agent = array(
                            'name' => $name,
                            'email' => $email,
                            'phone_number' => $phone_number,
                            'domain_name' => $domain_name,
                            'company_name' => $company_name,
                            'status' =>1,
                            'is_default'=>'t',
                            'ip'=>$ip
                        );
                    }else{
                        $defatul_agent = array(
                            'client_id'=>$data[0][0]['client_id'],
                            'name' => $name,
                            'email' => $email,
                            'phone_number' => $phone_number,
                            'domain_name' => $domain_name,
                            'company_name' => $company_name,
                            'ip'=>$ip
                        );
                    }

                    $this->Agent->save($defatul_agent);

                    $this->Agent->create_json_array('', 201, __('The Default Agent [' . $name . '] is created successfully!', true));
                }
            }else{
                $this->Agent->create_json_array('', 101, __('The Agent[' . $name . '] or Domain['.$domain_name.'] is already exists!', true));
            }
        }
        
        
        
        if(count($data) == 0){
            $data = array(
                'name'=>'',
                'email'=>'',
                'phone_number'=>'',
                'company_name'=>'',
                'domain_name'=>'',
                'ip'=>''
            );
        }else{
            $data = $data[0][0];
        }
        
        $this->set('default', $data);
    }
    
     public function agent_finance(){
        $this->pageTitle = 'Management/Agent Finance';
        
        $where = "1=1";
        /*if(!empty($_GET['submit'])){
            $agent_name .= " and name ilike '%{$_GET['submit']}%' ";
        }*/
        
        if (isset($_GET['submit']))
        {
            $name = $_GET['search'];
            $start_date = $_GET['start_date'];
            $end_date = $_GET['stop_date'];
            $tz = $_GET['tz'];

            if (!empty($name) && $name != 'Search')
            {
                $where = "action_time between '{$start_date} {$tz}' and  '{$end_date} {$tz}' and action_number like '%{$name}%'";
            } else
            {
                $where = "action_time between '{$start_date} {$tz}' and  '{$end_date} {$tz}'";
            }
        }
        
        $sql = "select count(*) from exchange_finance_agent where {$where} ";


        $temp = isset($_SESSION['paging_row']) ? $_SESSION['paging_row'] : 100;
        empty($_GET['page']) ? $currPage = 1 : $currPage = $_GET['page'];
        empty($_GET['size']) ? $pageSize = $temp : $pageSize = $_GET['size'];
        $_SESSION['paging_row'] = $pageSize;

        $count = $this->Client->query($sql);
        $count = $count[0][0]['count'];
        require_once 'MyPage.php';
        $page = new MyPage ();
        $page->setTotalRecords($count);
        $page->setCurrPage($currPage);
        $page->setPageSize($pageSize);
        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();
        $offset = $currPage * $pageSize;

        $sql = "SELECT * from exchange_finance_agent  
        where {$where} order by id desc LIMIT {$pageSize} OFFSET {$offset}";



        //echo $sql;
        $data = $this->Client->query($sql);

        //echo $count;
        //var_dump($data);

        $page->setDataArray($data);
        $this->set('p', $page);
        
    }
    
    public function confirm_finance(){
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        
        $id = $_POST['id'];
        
        $this->Client->query('begin');
            $this->Client->query("update exchange_finance_agent set 
                    status = 1,actual_amount = total_amount,action_fee = 0,
                complete_time = current_timestamp(2) where id = {$id} ");
            $this->Client->query("update exchange_finance_agent_clients set 
                    actual_amount = amount,action_fee = 0
                where exchange_finance_agent_id = {$id}");
        
               
        $clients =  $this->Client->query("select * from exchange_finance_agent_clients where exchange_finance_agent_id = {$id} ");
        
        foreach($clients as $client){
            $sql = "UPDATE client_balance SET balance = balance::numeric+{$client[0]['actual_amount']}, 
                    ingress_balance = ingress_balance::numeric+{$client[0]['actual_amount']} WHERE client_id = '{$client[0]['client_id']}'";
            $this->Client->query($sql);
        }
        
        $this->Client->query('commit');
        
        echo json_encode(array('status'=>'success'));
        
    }
    
    public function get_agent_finance_message(){
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $id = $_POST['id'];
        
        $clients =  $this->Client->query("select exchange_finance_agent_clients.*, order_user.name
                from exchange_finance_agent_clients
                left join order_user on order_user.client_id = exchange_finance_agent_clients.client_id
                where exchange_finance_agent_id = {$id} ");
        
        echo json_encode($clients);
        
    }
    
    public function agent_set(){
        Configure::write('debug', 0);
        
        $this->pageTitle = 'Management/Agents Setting';
      
        
        $sql = "select count(*) from exchange_agent_email_type ";


        $temp = isset($_SESSION['paging_row']) ? $_SESSION['paging_row'] : 100;
        empty($_GET['page']) ? $currPage = 1 : $currPage = $_GET['page'];
        empty($_GET['size']) ? $pageSize = $temp : $pageSize = $_GET['size'];
        $_SESSION['paging_row'] = $pageSize;

        $count = $this->Client->query($sql);
        $count = $count[0][0]['count'];
        require_once 'MyPage.php';
        $page = new MyPage ();
        $page->setTotalRecords($count);
        $page->setCurrPage($currPage);
        $page->setPageSize($pageSize);
        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();
        $offset = $currPage * $pageSize;

        $sql = "SELECT * from exchange_agent_email_type order by id LIMIT {$pageSize} OFFSET {$offset}";



        //echo $sql;
        $data = $this->Client->query($sql);

        //echo $count;
        //var_dump($data);

        $page->setDataArray($data);
        $this->set('p', $page);
        
        
        
        
    }
    
     public function add_agent_set(){
        $this->autoLayout = FALSE;
        $this->autoRender = FALSE;
        Configure::write('debug', 0);
        
        
        $email_type = $_POST['email_type'];
        $email_action = $_POST['email_action'];
        
        $data = array('status'=>'');

       
       
        $res = $this->Client->query("insert into exchange_agent_email_type (email_type,email_action) 
                            values 
                            ({$email_type},{$email_action})");
        //$this->Client->logging(0, 'Agent Setting', "Agent Name:{$agent_name}");
        //$this->Client->create_json_array('', 201, __('The action created successfully', true));
        $data['status'] = "success";
        
        
        echo json_encode($data);
    }
    
    public function delete_agent_set($id){
        $res = $this->Client->query("delete from exchange_agent_email_type where id = {$id} ");
        $this->redirect("/agent/agent_set/");
    }
    
    public function save_agent_set()
    {
        $this->autoLayout = FALSE;
        $this->autoRender = FALSE;
        Configure::write('debug', 0);
        $data = array('status'=>'');
        $id = $_POST['id'];
        
        $email_type = $_POST['email_type'];
        $email_action = $_POST['email_action'];
        
        
        
        $this->Client->query("update exchange_agent_email_type set email_type = {$email_type},
        email_action = {$email_action} 
        where id = {$id} ");
        //$this->Client->logging(2, 'Agent', "Agent Name:{$agent_name}");
        //$this->Client->create_json_array('', 201, __('The Agent [' . $agent_name . '] is modified successfully!', true));
        $data['status'] = "success";
               
        
        echo json_encode($data);
    }
    
    public function trunks(){
        
        //var_dump($this->post->get());
        
        $this->pageTitle = 'Management/Agent Egress Trunk List';
        
        $where = " egress = 't' ";
        /*if(!empty($_GET['submit'])){
            $agent_name .= " and name ilike '%{$_GET['submit']}%' ";
        }*/
        
        if (isset($_GET['submit']))
        {
            $name = $_GET['search'];

            if (!empty($name) && $name != 'Search')
            {
                $where .= " and resource.alias ilike '%{$name}%' ";
            } 
        }
        
        $sql = "select count(*) from resource where {$where} ";

        $temp = isset($_SESSION['paging_row']) ? $_SESSION['paging_row'] : 100;
        empty($_GET['page']) ? $currPage = 1 : $currPage = $_GET['page'];
        empty($_GET['size']) ? $pageSize = $temp : $pageSize = $_GET['size'];
        $_SESSION['paging_row'] = $pageSize;

        $count = $this->Client->query($sql);
        $count = $count[0][0]['count'];
        require_once 'MyPage.php';
        $page = new MyPage ();
        $page->setTotalRecords($count);
        $page->setCurrPage($currPage);
        $page->setPageSize($pageSize);
        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();
        $offset = $currPage * $pageSize;
        
        $default_agent = $this->Client->query("select * from agent_client where is_default = 't' ");
        $default_agent_name = $default_agent[0][0]['name'];
        
        $default_agent_name = 'admin';
        
        /*
         * 
         * case agent_client.name 
            when (NULL) then '{$default_agent_name}' 
            else agent_client.name 
            end 
            as agent_name 
         * 
         */
        
        $sql = " SELECT resource.*,client.name as client_name,agent_client.name as agent_name
            
        from resource
        left join client on resource.client_id = client.client_id
        left join agent_client_client on agent_client_client.client_id = client.client_id
        left join agent_client on agent_client.client_id = agent_client_client.agent_client_id
        where {$where}   order by resource.alias LIMIT {$pageSize} OFFSET {$offset} ";

        //echo $sql;
        $data = $this->Client->query($sql);
        
        
        //$data['agent_name'] = $default_agent_name;
        //var_dump($data[0][0]);
        //echo $count;
        //var_dump($data);
        $this->set('p', $page);
        $page->setDataArray($data);
        $this->set('default_agent_name', $default_agent_name);
    }
    
    
    public function exchange_login_log(){
        
        //var_dump($this->post->get());
        
        $this->pageTitle = 'Management/Login Log';
        
        
        $where = "1=1";
    
        if (isset($_GET['submit']))
        {
            $name = $_GET['search'];
            $start_date = $_GET['start_date'];
            $end_date = $_GET['stop_date'];
            $tz = $_GET['tz'];

            if (!empty($name) && $name != 'Search')
            {
                $where = "login_time between '{$start_date} {$tz}' and  '{$end_date} {$tz}' and login_name like '%{$name}%'";
            } else
            {
                $where = "login_time between '{$start_date} {$tz}' and  '{$end_date} {$tz}'";
            }
        }
        
       
        $sql = "select count(*) from exchange_agent_partition_login_log where {$where} ";

        $temp = isset($_SESSION['paging_row']) ? $_SESSION['paging_row'] : 100;
        empty($_GET['page']) ? $currPage = 1 : $currPage = $_GET['page'];
        empty($_GET['size']) ? $pageSize = $temp : $pageSize = $_GET['size'];
        $_SESSION['paging_row'] = $pageSize;

        $count = $this->Client->query($sql);
        $count = $count[0][0]['count'];
        require_once 'MyPage.php';
        $page = new MyPage ();
        $page->setTotalRecords($count);
        $page->setCurrPage($currPage);
        $page->setPageSize($pageSize);
        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();
        $offset = $currPage * $pageSize;
        
        $sql = " SELECT * from exchange_agent_partition_login_log
        where {$where}   order by login_time desc LIMIT {$pageSize} OFFSET {$offset} ";

        $data = $this->Client->query($sql);
      
        $this->set('p', $page);
        $page->setDataArray($data);
    }
    
    public function get_test_results(){
        $this->pageTitle = 'Management/Agents';
        
        $where = " test_time::date=current_date ";

        //var_dump($_GET);
        $start_date = date("Y-m-d 00:00:00");
        $end_date = date("Y-m-d 23:59:59");
        if (isset($_GET['search']))
        {
            $name = $_GET['search'];
            $start_date = $_GET['start_date'];
            $end_date = $_GET['stop_date'];
            $tz = $_GET['tz'];

            if (!empty($name) && $name != 'Search')
            {
                $where = "test_time between '{$start_date} {$tz}' and  '{$end_date} {$tz}' and exchange_agent_vendor_test_result.code_name like '%{$name}%'";
            } else
            {
                $where = "test_time between '{$start_date} {$tz}' and  '{$end_date} {$tz}'";
            }
        }
        
        $sql = "select count(*) from exchange_agent_vendor_test_result where {$where} ";


        $temp = isset($_SESSION['paging_row']) ? $_SESSION['paging_row'] : 100;
        empty($_GET['page']) ? $currPage = 1 : $currPage = $_GET['page'];
        empty($_GET['size']) ? $pageSize = $temp : $pageSize = $_GET['size'];
        $_SESSION['paging_row'] = $pageSize;

        $count = $this->Client->query($sql);
        $count = $count[0][0]['count'];
        require_once 'MyPage.php';
        $page = new MyPage ();
        $page->setTotalRecords($count);
        $page->setCurrPage($currPage);
        $page->setPageSize($pageSize);
        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();
        $offset = $currPage * $pageSize;

        $sql = "SELECT exchange_agent_vendor_test_result.*,order_user.name from exchange_agent_vendor_test_result
                left join order_user on order_user.client_id = exchange_agent_vendor_test_result.client_id
                where {$where} order by exchange_agent_vendor_test_result.country,exchange_agent_vendor_test_result.code_name,exchange_agent_vendor_test_result.code LIMIT {$pageSize} OFFSET {$offset}";



        //echo $sql;
        $data = $this->Client->query($sql);

        //echo $count;
        //var_dump($data);

        $page->setDataArray($data);
        $this->set('p', $page);
    }
    
    

}

?>
