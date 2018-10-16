<?php

class QuickcdrController extends AppController
{
    var $name = 'Quickcdr';
    var $uses = array('Quickcdr');
    
    public function beforeFilter()
    {
        if( $this->params['action'] == 'export') {
            Configure::load("myconf");
            return true;
        }
        
        parent::beforeFilter();
    }
    
    
    public function index()
    {
        $this->pageTitle = "Statistics/Simple CDR Export";
        if ($this->RequestHandler->isPost())
        {
            if (isset($_SESSION['sst_client_id'])) {
                $_POST['user_id'] = $_SESSION ['sst_user_id'];
            }    
            
            $this->Quickcdr->save(array('Quickcdr' => $_POST));
            $this->redirect("/quickcdr/logging");
        }
        
        $clients = $this->Quickcdr->get_clients();
        $this->set("clients", $clients);
    }  
    
    public function logging()
    {
        $this->pageTitle = "Statistics/Simple CDR Export";
        $this->paginate = array(
            'limit' => 100,
            'order' => array(
                'Quickcdr.id' => 'desc',
            ),
        );
        
        if (isset($_SESSION['sst_client_id'])) {
            $this->paginate['conditions'] = array('Quickcdr.user_id' => $_SESSION ['sst_user_id']);
        } else {
           $this->paginate['conditions'] = array('Quickcdr.user_id IS NULL'); 
        }
        
        $this->data = $this->paginate('Quickcdr');
        
        $clients = $this->Quickcdr->get_clients();
        $this->set("clients", $clients);
        $this->set("status", array('waiting', 'In progress', 'Done'));
    }
    
    public function export($id)
    {
        $this->autoRender = false;
        $this->autoLayout = false;
        $quickcdr = $this->Quickcdr->findById($id);
        if ($quickcdr['Quickcdr']['status'] != 2) {
            echo "The CDRs is still in progress, Please try again later.";
            exit;
        }
        $file_path = $quickcdr['Quickcdr']['file_path'];
        
        $local_file = APP . 'tmp/ftp/' . $id . 'csv.gz'; 
        
        if (!file_exists($local_file))
        {
            $conn_id = ftp_connect(Configure::read('cdr_ftp.ip'), Configure::read('cdr_ftp.port'));
            // Set the network timeout to 10 seconds
            ftp_set_option($conn_id, FTP_TIMEOUT_SEC, 1000);
            if (!$conn_id) {
                echo "Ftp connection failed!<br>";
            }
            $login_result = ftp_login($conn_id, Configure::read('cdr_ftp.username'), Configure::read('cdr_ftp.password'));
            ftp_pasv($conn_id, true);
            
            if (!$login_result)
            {
                echo "Ftp login failed!<br>";
            }
            $get_result = ftp_get($conn_id, $local_file, $file_path, FTP_BINARY);
            
            if (!$get_result)
            {
                echo "ftp get file failed!<br>";
            }
            
        }

        
        ob_clean();
        
        $file_name = "{$quickcdr['Quickcdr']['client_id']}_{$quickcdr['Quickcdr']['start_date']}_{$quickcdr['Quickcdr']['end_date']}.csv.gz";
        $encoded_filename = rawurlencode($file_name);
            
        header("Content-type: application/octet-stream");

        //处理中文文件名
        $ua = $_SERVER["HTTP_USER_AGENT"];
        if (preg_match("/MSIE/", $ua)) {
            header('Content-Disposition: attachment; filename="' . $encoded_filename . '"');
        } else if (preg_match("/Firefox/", $ua)) {
            header("Content-Disposition: attachment; filename*=\"utf8''" . $file_name . '"');
        } else {
            header('Content-Disposition: attachment; filename="' . $file_name . '"');
        }
        readfile($local_file);
        
        
//        $ftp_uri = "ftp://" . Configure::read('cdr_ftp.username') .":" . Configure::read('cdr_ftp.password') . "@" . 
//                Configure::read('cdr_ftp.ip') . ':' . Configure::read('cdr_ftp.port') . "/" . $file_path ;
//        $this->redirect($ftp_uri);
    }
          
}

