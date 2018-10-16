<?php

class CreditManagementsController extends AppController
{
    var $name = "CreditManagements";
    var $uses = array('Client');
    var $components = array('RequestHandler');
    var $helpers = array('javascript', 'html', 'Common');
    
    public function beforeFilter() 
    {
        $this->checkSession("login_type");
        $login_type = $this->Session->read('login_type');
        if ($login_type == 1) {
            $this->Session->write('executable', true);
            $this->Session->write('writable', true);
        } else {
            $limit = $this->Session->read('sst_config_CodeDeck');
            $this->Session->write('executable', $limit['executable']);
            $this->Session->write('writable', $limit['writable']);
        }
        parent::beforeFilter();
    }
    
    public function index()
    {
        if ($_SESSION['login_type'] != 1)
        {
            $this->redirect('/clients/carrier/');
        }
        $this->pageTitle = 'Management/Carriers';
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
        $data = $this->Client->getclients2($sst_user_id, $where, $pageSize, $offset);
        
        
        $page->setDataArray($data);
        $this->set('p', $page);
    }
    
    public function action_edit_panel($client_id)
    {
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        if ($this->isPost()) {
            $this->data['Client']['client_id'] = $client_id;
            $this->Session->write('m', $this->Client->create_json(201, __('The Client [' . $this->data['Client']['name'] . '] is modified successfully!', true)));
            $this->Client->save($this->data);
            $this->xredirect("/credit_managements/index/");
        }
        $this->data = $this->Client->find('first', Array('conditions' => Array('client_id' => $client_id)));
    }
}