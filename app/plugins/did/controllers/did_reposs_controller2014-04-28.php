<?php

class DidRepossController extends DidAppController
{

    var $name = 'DidReposs';
    var $uses = array('did.DidRepos', 'ImportExportLog', 'did.DidAssign');
    var $helpers = array('javascript', 'html', 'Common');

    function beforeFilter()
    {
        $this->checkSession("login_type"); //核查用户身份
        parent::beforeFilter();
    }
    
    public function _get_data($ingress_id = '')
    {
        $this->set('ingresses', $this->DidRepos->get_ingress($ingress_id));
        $this->set('egresses', $this->DidRepos->get_egress());
    }

    function index($ingress_id = null)
    {
        $this->pageTitle = "Origination/DID Repository";
        
      
        $this->paginate = array(
            'limit' => 100,
            'order' => array(
                'DidRepos.number' => 'asc',
            ),
        );
        
        if(isset($_GET['search']) && !empty($_GET['search']))
        {
            $this->paginate['conditions'][] = array("DidRepos.number::text like '%{$_GET['search']}%'");
        }
        
        
        if ($ingress_id != null) 
        {
            $this->paginate['conditions'][] = array("DidRepos.ingress_id = {$ingress_id}");
            $this->set('vendor_name', $this->DidRepos->get_vendor_name($ingress_id));
        }
        
        
        if (isset($_GET['advsearch']))
        {
            $ingress_id = $_GET['ingress_id'];
            $egress_id  = $_GET['egress_id'];
            $number     = $_GET['number'];
            $show       = $_GET['show'];
            
            if (!empty($ingress_id)) 
            {
                $this->paginate['conditions'][] = array("DidRepos.ingress_id = {$ingress_id}");
            }
            if (!empty($egress_id)) 
            {
                $this->paginate['conditions'][] = array("DidRepos.egress_id = {$egress_id}");
            }
            if (!empty($number)) 
            {
                $this->paginate['conditions'][] = array("DidRepos.number like %'{$number}'%");
            }
            
            if (!empty($show)) 
            {
                if ($show == 1) {
                    $this->paginate['conditions'][] = array("DidRepos.status = 2");
                } else {
                    $this->paginate['conditions'][] = array("DidRepos.status = 1");
                }
            }
        }
        
                
        if ($this->RequestHandler->isPost())
        {
            if (isset($_POST['export_csv'])) {
                $query = $this->paginate;
                unset($query['limit']);
                $this->data = $this->DidRepos->find('all', $query);
                header("Pragma: public");
                header("Expires: 0");
                header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
                header("Content-Type: aplication/vnd.ms-excel");
                header("Content-Type: application/octet-stream");
                header("Content-Type: application/download");
                header("Content-Disposition: attachment;filename=DID_Repository.xls");
                header("Content-Transfer-Encoding: binary ");
                 Configure::write('debug', 0);
                 $this->autoLayout = FALSE;
                 $this->_get_data();
                 $this->render('export_csv');
                 
            }
        }
        
        $this->_get_data();
        $this->data = $this->paginate('DidRepos');
    }
    
    public function chech_num($number)
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $is_exists = $this->DidRepos->check_num($number);
        if ($is_exists)
            echo 'true';
        else
            echo 'false';
    }

    public function action_edit_panel($ingress_id = '', $number = null)
    {
        Configure::write('debug', 0);
        if ($ingress_id == '0') $ingress_id = '';
        
        $this->_get_data($ingress_id);
        $this->layout = 'ajax';
        if ($this->isPost())
        {
            if ($number != null)
            {
                $this->data['DidRepos']['number'] = $number;
                $this->Session->write('m', $this->DidRepos->create_json(201, __('The number of [' . $this->data['DidRepos']['number'] . '] is modified successfully!', true)));
            }
            else{
                $this->data['DidRepos']['status'] = 1;
                $this->Session->write('m', $this->DidRepos->create_json(201, __('The number of [' . $this->data['DidRepos']['number'] . '] is created successfully!', true)));
            }
            $this->DidRepos->save($this->data);
            $this->xredirect("/did/did_reposs/index/" . $ingress_id);
        }
        $this->data = $this->DidRepos->find('first', Array('conditions' => Array('number' => $number)));
    }
    
    public function change_status($number, $status, $ingress_id = '')
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        /*
        $product_id = $this->DidAssign->check_default_static();
        if ($status == 0)
        {
            $this->DidAssign->delete_number($number, $product_id);
        }
        else
        {
            $item_id = $this->DidAssign->add_new_number($number, $product_id);
            $this->DidAssign->add_new_resouce($item_id, $egress_id);
            $this->DidAssign->add_assign($number, $egress_id);
        }
         * 
         */
        if ($status == 0)
        {
            $this->Session->write('m', $this->DidRepos->create_json(201, __('The  status of  number  [' . $number . '] is inactived successfully!', true)));
        }
        else
        {
            $this->Session->write('m', $this->DidRepos->create_json(201, __('The  status of  number  [' . $number . '] is actived successfully!', true)));
        }
        $sql = "update did_assign set status = {$status} where number = '{$number}';
        update ingress_did_repository set status = {$status} where number = '{$number}';";
        $this->DidRepos->query($sql);
        $this->Session->write('m', $this->DidRepos->create_json(201, __('The  status of  number  [' . $number . '] is changed successfully!', true)));
        $this->xredirect("/did/did_reposs/index/" . $ingress_id);
    }
    
    public function delete_uploaded()
    {
        if ($this->RequestHandler->ispost())
        {
            Configure::load('myconf');
            $path = APP . 'tmp' . DS . 'upload' . DS . 'csv';
            $upload_file = $path . DS . trim($_POST['myfile_guid']) .".csv";
            $user_id = 0;
            if (isset($_SESSION ['sst_user_id']))
            {
                $user_id = $_SESSION ['sst_user_id'];
            }
            App::import('Model', 'ImportExportLog');
            $export_log = new ImportExportLog();
            $data = array();
            $data ['ImportExportLog']['ext_attributes'] = array();
            $data ['ImportExportLog']['time'] = gmtnow();
            $data ['ImportExportLog']['obj'] = 'DID Delete Uploaded';
            $data ['ImportExportLog']['file_path'] = $upload_file;
            $error_file = $upload_file . '.error';
            new File($error_file, true, 0777);
            $data ['ImportExportLog']['error_file_path'] = $error_file;
            $data ['ImportExportLog']['user_id'] = $user_id;
            $data ['ImportExportLog']['log_type'] = ImportExportLog::LOG_TYPE_IMPORT;
            $data ['ImportExportLog']['upload_type'] = '13';
            $export_log->save($data);
            $script_path = Configure::read('script.path');
            $perl_path = $script_path . DS . 'class4_did_delete_uploaded.pl';
            $perl_conf  = $script_path . DS . 'class4.conf';
            $id = $export_log->id;
            $cmd = "perl $perl_path -c $perl_conf -i {$id}&";
            if (Configure::read('cmd.debug'))
            {
                echo $cmd;exit;
            }
            shell_exec($cmd);
            $this->set('upload_id', $id);
        }
        $this->set('example', $this->webroot . 'example' . DS . 'did_delete_uploaded.csv');
    }
    
    public function upload()
    {
        $this->set('type', 14);
        if ($this->RequestHandler->ispost())
        {
            Configure::load('myconf');
            //$upload_path = Configure::read('did.upload_path');
            //$file_name = date('Y-m-d_H:i:s') . '_' . uniqid() . '.csv';
            //$dest_file_path = $upload_path . DS . $file_name;
            //$result = move_uploaded_file($_FILES['upfile']['tmp_name'], $dest_file_path);
            //if ($result)
            //{
                $ingress_id = $_POST['ingress_id'];
                $path = APP . 'tmp' . DS . 'upload' . DS . 'csv';
                $upload_file = $path . DS . trim($_POST['myfile_guid']) .".csv";
                
                $resource_name = $this->DidRepos->get_resource_name($ingress_id);
                
                $user_id = 0;
                if (isset($_SESSION ['sst_user_id']))
                {
                    $user_id = $_SESSION ['sst_user_id'];
                }
                App::import('Model', 'ImportExportLog');
                $export_log = new ImportExportLog();
                $data = array();
                $data ['ImportExportLog']['ext_attributes'] = array();
                $data ['ImportExportLog']['time'] = gmtnow();
                $data ['ImportExportLog']['obj'] = 'DID';
                $data ['ImportExportLog']['file_path'] = $upload_file;
                $error_file = $upload_file . '.error';
                new File($error_file, true, 0777);
                $data ['ImportExportLog']['error_file_path'] = $error_file;
                $data ['ImportExportLog']['user_id'] = $user_id;
                $data ['ImportExportLog']['log_type'] = ImportExportLog::LOG_TYPE_IMPORT;
                $data ['ImportExportLog']['upload_type'] = '13';
                $data ['ImportExportLog']['foreign_id'] = $ingress_id;
                $data ['ImportExportLog']['foreign_name'] = $resource_name;
                $export_log->save($data);
                $script_path = Configure::read('script.path');
                $perl_path = $script_path . DS . 'class4_upload_check.pl';
                $perl_conf  = $script_path . DS . 'class4.conf';
                $id = $export_log->id;
                $cmd = "perl $perl_path -c $perl_conf -i {$id} -f {$ingress_id} &";
                if (Configure::read('cmd.debug'))
                {
                    echo $cmd;exit;
                }
                shell_exec($cmd);
                $this->set('upload_id', $id);
            //}
        }
        
        $this->_get_data();
    }
    
    public function delete($number, $ingress_id = '')
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $product_id = $this->DidAssign->check_default_static();
        $this->DidAssign->delete_number($number, $product_id);
        $this->DidAssign->del($number);
        $this->DidRepos->del($number);
        $this->Session->write('m', $this->DidRepos->create_json(201, __('The  number  [' . $number . '] is deleted successfully!', true)));
        $this->xredirect("/did/did_reposs/index/". $ingress_id);
    }
    
    public function mutiple_delete()
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $selecteds = $_POST['selecteds'];
        $product_id = $this->DidAssign->check_default_static();
        foreach ($selecteds as $number) {
            $this->DidAssign->delete_number($number, $product_id);
            $this->DidAssign->del($number);
            $this->DidRepos->del($number);
        }
        
        echo json_encode(array('stauts' => 1));
    }
    

}
