<?php

class InvoiceHistoryController extends AppController
{

    var $name = 'InvoiceHistory';
    var $uses = array('InvoiceHistory', 'Client');
    var $components = array();

    public function beforeFilter()
    {
        $this->checkSession("login_type"); //核查用户身份exprot
        parent::beforeFilter(); //调用父类方法
    }
    
    public function index()
    {
        $this->pageTitle = 'Finance/Carrier Invoice History';
        $temp = isset($_SESSION['paging_row']) ? $_SESSION['paging_row'] : 100;
        empty($_GET['page']) ? $currPage = 1 : $currPage = $_GET['page'];
        empty($_GET['size']) ? $pageSize = $temp : $pageSize = $_GET['size'];
        $_SESSION['paging_row'] = $pageSize;
        $where = "";
        if (isset($_GET['submit']))
        {
            $filter_type = $_GET['filter_client_type'];
            $client_name = $_GET['search'];
            switch ((int) $filter_type)
            {
                case 1:
                    $where = " AND client.status = true";
                    break;
                case 2:
                    $where = " AND client.status = false";
                    break;
            }

            if (!empty($client_name) && $client_name != 'Search')
                $where .= " AND client.name ilike '%{$client_name}%'";
        } else
        {
            $where = " AND client.status = true";
        }
        $sst_user_id = $_SESSION['sst_user_id'];
        
        $count = $this->Client->getclients_count($sst_user_id, $where);
        require_once 'MyPage.php';
        $page = new MyPage ();
        $page->setTotalRecords($count);
        $page->setCurrPage($currPage);
        $page->setPageSize($pageSize);
        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();
        $offset = $currPage * $pageSize;
        $order_by = 'order by client.name ASC';
        if (isset($_GET['order_by']))
        {
            $order_by_arr = explode('-', $_GET['order_by']);
            if (count($order_by_arr) == 2)
                $order_by = "order by " . $order_by_arr[0] . ' ' . $order_by_arr[1];
        }
        $data = $this->Client->getclients($sst_user_id, $order_by, $where, $pageSize, $offset);
        
        foreach ($data as &$item) {
            $item[0]['last_invoice_date'] = $this->InvoiceHistory->get_max_invoice_date($item[0]['client_id']);
        }
        $page->setDataArray($data);
        $this->set('p', $page);
    }
    
    public function view($client_id)
    {
        $this->pageTitle = 'Finance/Carrier Invoice History';
        $this->paginate = array(
            'limit' => 100,
            'order' => array(
                'last_invoice_for' => 'desc',
            ),
            'conditions' => array(
                'client_id' => $client_id,
            )
        );
       $this->data = $this->paginate('InvoiceHistory');
       $this->set('client', $this->Client->findByClientId($client_id));
    }
    
    public function trigger()
    {
        $script_path =  Configure::read('script.path');
        $exec_path = $script_path . DS . "class4_invoice.pl";
        $exec_conf_path = Configure::read('script.conf');
        $cmd = "perl $exec_conf_path -c $exec_conf_path -a > /dev/null 2>&1 &";
        shell_exec($cmd);
        $this->InvoiceHistory->create_json_array('', 201, 'This Invoice is starting to generate successfully!');
        $this->Session->write("m", InvoiceHistory::set_validator());
        $this->redirect('/invoice_history');
    }

}