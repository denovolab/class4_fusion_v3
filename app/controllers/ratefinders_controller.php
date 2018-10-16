<?php

class RatefindersController extends AppController {
    
    var $name = "Ratefinders"; 
    var $helpers = array('Javascript','Html', 'Text'); 
    var $components = array('RequestHandler');  
    var $uses = array('Clients'); 
    
    public function index() {
        if($this->RequestHandler->isPost()) {
            $trunks = array();
            $egress_trunk_type = $_POST['egress_trunk_type'];
            if($egress_trunk_type == 0) {
                $results = $this->Clients->query("select resource_id from resource where egress = true and active = true");
                foreach($results as $item) {
                    array_push($trunks, $item[0]['resource_id']);
                }
            } elseif ($egress_trunk_type == 1) {
                $results = $this->Clients->query("select resource_id from resource where egress = true");
                foreach($results as $item) {
                    array_push($trunks, $item[0]['resource_id']);
                }
            } elseif ($egress_trunk_type == 2) {
                $trunks = isset($_POST['trunks'])?$_POST['trunks']:array();
                if(count($trunks) == 0) {
                    $this->Clients->create_json_array('',101,__('You must select one egress trunk at least!',true));
                    $this->Session->write("m",Clients::set_validator ());
                    $this->redirect ( '/ratefinders/index/' ); 
                }
            }
            /*
            if(count($trunks) == 0) {
                $results = $this->clients->query("select resource_id from resource where egress = true and active = true");
                foreach($results as $item) {
                    array_push($trunks, $item[0]['resource_id']);
                }
            }
            */
            $trunkstr = implode(",",$trunks);
            $country = empty($_POST['country']) ? '' : $_POST['country'];
            $code = empty($_POST['code']) ? '' : $_POST['code'];
            $codename = empty($_POST['codename']) ? '' : $_POST['codename'];
            $rate = empty($_POST['rate']) ? '' : $_POST['rate'];
            $sort_type = $_POST['sort_type'];
            $cmd = "lcr_analysis false|{$sort_type}|{$country}|{$code}|{$codename}|{$rate}|{$trunkstr}";
            $fileinfo = $this->_socket($cmd);
            $fileinfo = trim($fileinfo) . "," .$sort_type;
            if (empty($fileinfo)) {
                $this->set('p', '');
            } else {
                setcookie('ratefinder', trim($fileinfo));
                $fileinfo_arr = explode(",", $fileinfo);
                $filename = $fileinfo_arr[0];
                $filelines = $fileinfo_arr[1];
                $maxfields = $fileinfo_arr[2];
                $sorted_type = $fileinfo_arr[3];
                $this->_ready($filename, $filelines, $maxfields, $sorted_type);
            }
        } else {
            if(isset($_GET['page']) && isset($_COOKIE['ratefinder'])) {
                $fileinfo = $_COOKIE['ratefinder'];
                $fileinfo_arr = explode(",", $fileinfo);
                $filename = $fileinfo_arr[0];
                $filelines = $fileinfo_arr[1];
                $maxfields = $fileinfo_arr[2];
                $sorted_type = $fileinfo_arr[3];
                $this->_ready($filename, $filelines, $maxfields, $sorted_type);
            }
        }
        $trunks = $this->Clients->get_egress_trunk();
        $this->set('trunks', $trunks);
    }
    
    public function _ready($filename,$filelines,$maxfields, $sorted_type) {
        $temp = isset ($_SESSION['paging_row']) ? $_SESSION['paging_row'] : 100;
        empty($_GET['page'])?$currPage = 1:$currPage = $_GET['page'];
        empty($_GET['size'])?$pageSize = $temp:	$pageSize = $_GET['size'];
        $_SESSION['paging_row'] = $pageSize;
        //$startline = ($currPage - 1) * $pageSize;
        require_once 'MyPage.php';
        $page = new MyPage ();
        $page->setTotalRecords ( $filelines ); //总记录数
        $page->setCurrPage ( $currPage ); //当前页
        $page->setPageSize ( $pageSize ); //页大小
        $currPage = $page->getCurrPage()-1;
	$pageSize = $page->getPageSize();
        $result = $this->_line_content($filename, $currPage, $pageSize, $filelines);
        $page->setDataArray ( $result );
        $this->set('p',$page);
        $this->set('maxfields',$maxfields);
        $this->set('sorted_type', $sorted_type);
    }
    
    public function _line_content($filename, $startline, $endline, $filelines) {
        $arr = array();
        $fp = fopen($filename, "r");
        for ($i = 0; $i <= $startline - 1; $i++) {
            fgets($fp);
        }
        //for ($i = $startline; $i <= $endline; $i++) {
        for ($i = 1; $i <= $endline; $i++) {
            array_push($arr, fgetcsv($fp));
            if($filelines == $i) break;
        }
        return $arr;
    }
    
    public function _socket($cmd) {
        $content = "";
        $socket = socket_create(AF_INET, SOCK_STREAM, getprotobyname("tcp"));
        if(socket_connect($socket, Configure::read("backend.ip"), Configure::read("backend.port"))) {
                socket_write($socket, $cmd, strlen($cmd));
        }
        while ($out = socket_read($socket, 2048)) {
           $content .= $out;
           if(strpos($out, "~!@#$%^&*()") !== FALSE) {
               break;
           }
           unset($out);
        }
        $content = strstr($content, "~!@#$%^&*()", TRUE);
        return $content;
    }
    
    public function down() {
        Configure::write('debug', 0);
        $this->autoLayout = FALSE;
        $this->autoRender = FALSE;
        header("Content-Type: text/csv");   
        header("Content-Disposition: attachment; filename=rate_finder.csv");   
        header('Cache-Control:must-revalidate,post-check=0,pre-check=0');   
        header('Expires:0');   
        header('Pragma:public');   
        $fileinfo = $_COOKIE['ratefinder'];
        $fileinfo_arr = explode(",", $fileinfo);
        $filename = $fileinfo_arr[0];
        $filelines = $fileinfo_arr[1];
        $maxfields = $fileinfo_arr[2];
        $sorted_type = $fileinfo_arr[3];
        echo 'Code Name';
        if ($sorted_type == 'false') {
            echo ',Code';
        } 
        echo ',Min,Max,Avg,';
        for($i=1;$i<=$maxfields;$i++){
            echo 'Trunk-' . $i .',';
        }
        echo "\n";
        echo file_get_contents($filename);
    }
    
}

?>
