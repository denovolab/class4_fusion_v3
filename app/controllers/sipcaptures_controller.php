<?php



class SipcapturesController extends AppController

{



    var $name = 'Sipcaptures';

    var $uses = array('Capture', 'Systemparam', "Resource");

    var $helpers = array('html', 'javascript', 'AppCommon', 'AppSipCapture', 'Ajax');

    var $components = array('PhpCommon');



    function index()

    {



        $this->redirect('sip_capture');

    }



    function find_file($server_ip,  $server_port, $file_name)

    {

        //$server_ip='192.168.1.115';

        //$dir = Configure::read('sip_capture.pcap_dir');

        //$arr = explode('.', $server_ip);

       // $ip = join('', $arr);

        //$file_name='2011-02-23-0953111804289383.pcap';

        list($y, $m, $d) = explode("-", $file_name);

        //$file_dir=$dir.DS.$ip.DS.$d.DS.$file_name;

        //$file_dir=$dir."sip_capture02".DS.$file_name;

        //$file_dir = $dir.DS.$d.DS.basename($file_name);

        //echo $file_dir. "<br />";

        //$filename = $dir  . DS . $ip . DS . $d.  DS . basename($file_name);

        //$sql = "select sip_path from server_platform where ip = '$server_ip' and port = $server_port limit 1";

        $sql = "select sip_capture_path from switch_profile where sip_ip = '$server_ip' and sip_port = $server_port limit 1";

        $result = $this->Capture->query($sql);

        $path = $result[0][0]['sip_capture_path'];

        $filename = $path . DS . $d . DS . basename($file_name);

        $is_debug = Configure::read('sip_capture.is_debug');

        if ($is_debug)

        {

            printf("%s <br />\n", $filename);

        }

        return $filename;

    }



    function find_file_size($file_name, $server_ip, $server_port)

    {



        $file_dir = $this->find_file($server_ip, $server_port, $file_name);



        return filesize($file_dir);

    }



    public function test()

    {

        $this->autoRender = false;

        $this->autoLayout = false;

        $capture = $this->Capture->find('all', array('conditions' => array('flag' => 2)));

        print_r($capture);

    }



    #stop capture



    function stop($id = null)

    {

        //$this->check_expired_record();

        //Configure::write('debug', 0);

        $this->layout = false;

        $this->autoRender = false;

        if (!empty($id))

        {

            $id = (int) $id;

        }



        if ($id > 0)

        {

            $capture = $this->Capture->find('first', array('conditions' => "capture_id = $id"));

            if ($capture['Capture']['flag'] == 2)

            {

                $file_name = $capture['Capture']['file_name'];

                $sock = $this->open_sock();

                $stop_ack = $this->send_cmd($sock, "stop\r\n{$file_name}\r\n", array($capture['Capture']['server_ip'], $capture['Capture']['server_port']));

                $this->close_sock($sock);

                usleep(3000000);

                $server_ip = $capture['Capture']['server_ip'];

                $server_port = $capture['Capture']['server_port'];

                $file_dir = $this->find_file($server_ip, $server_port,$file_name);

                $file_size = filesize($file_dir);

                $capture['Capture']['file_size'] = intval($file_size);

                $capture['Capture']['flag'] = 1;

                $this->Capture->save($capture);

            }

        } 

        else

        {



            $result = $this->Capture->find('all', array('conditions' => array('flag' => 2)));

            foreach ($result as $item)

            {

                $file_name = $item['Capture']['file_name'];

                $sock = $this->open_sock();

                $stop_ack = $this->send_cmd($sock, "stop\r\n{$file_name}\r\n", array($item['Capture']['server_ip'], $item['Capture']['server_port']));

                $this->close_sock($sock);

                usleep(3000000);

                $server_ip = $item['Capture']['server_ip'];

                $server_port = $item['Capture']['server_port'];

                $file_dir = $this->find_file($server_ip, $server_port,$file_name);

                $file_size = filesize($file_dir);

                $item['Capture']['file_size'] = intval($file_size);

                $item['Capture']['flag'] = 1;

                $this->Capture->save($item);

            }

        }

        echo true;

    }



    # 模拟sip测试



    public function start_capture($command_and_paramters, $time, $server_one)

    {





        $sock = $this->open_sock();



        # 1. Get authorized nonce  C-->S:



        $str = $this->send_cmd($sock, "nonce\r\n", $server_one);



        list($ack, $nonce) = explode("\r\n", $str);



        //pr($nonce);//验证码



        $md5_num = $nonce . "denovo" . "54wOlspwW892";



        $nciphertest = md5($md5_num);







        #2. Start capture  ncapture_interval  -- capture  time(s)

        //$time=60;

        //$command_and_paramters="ngrep  -d any -pq \"sip\" host 192.168.1.115 or 192.168.1.115 and port 5066 or 5065";

        //	$command_and_paramters="ngrep  -d any -pq \"sip\" host 192.168.1.115 or 192.168.1.115 and port 5066 or 5065";



        $str = $this->send_cmd($sock, "start\r\n{$time}\r\n{$command_and_paramters}\r\n{$nciphertest}\r\n$nonce\r\n", $server_one);



        list($ack, $flag, $file) = explode("\r\n", $str);



        #3. Query capture result  remaining_time=0 ==> end  ?-1==>fail  else remailtime 

        //$file="2011-02-10-1557261804289383.pcap";

//			$str=$this->send_cmd($sock,"query\r\n{$file}\r\n");

//			list($ack,$sec)=explode("\r\n",$str);

//			pr($sec);

        #4. Stop capture

        //$stop_ack=$this->send_cmd($sock,"stop\r\n{$file}\r\n");



        $this->close_sock($sock);



        //TODO ID



        return $file;

    }



   



    #建立sock



    public function open_sock()

    {







        #设置$socket 发送超时1秒，接收超时3秒：



        $commonProtocol = getprotobyname("udp");



        $sock = @socket_create(AF_INET, SOCK_DGRAM, $commonProtocol);



        socket_set_option($sock, SOL_SOCKET, SO_RCVTIMEO, array("sec" => 4, "usec" => 0));



        socket_set_option($sock, SOL_SOCKET, SO_SNDTIMEO, array("sec" => 5, "usec" => 0));



        if (!$sock)

        {



            echo "socket create failure";

        }



        return $sock;

    }



    public function _get_real_sip_server_info($server)

    {

        //$sql = "SELECT sip_ip, sip_port FROM server_platform WHERE ip = '{$server[0]}' and port = {$server[1]} and server_type = 2 limit 1";

        $sql = "SELECT sip_capture_ip as sip_ip, sip_capture_port as sip_port FROM switch_profile WHERE sip_ip = '{$server[0]}' and sip_port = {$server[1]}";

        $result = $this->Capture->query($sql);

        return array(

            $result[0][0]['sip_ip'],

            $result[0][0]['sip_port'],

        );

    }



    public function send_cmd($sock, $buf, $server_one)

    {



        //$server_ip = Configure::read('sip_capture.host_ip');

        //$port = Configure::read('sip_capture.port');

        //$server_ip="192.168.1.118";



        list($server_ip, $port) = $this->_get_real_sip_server_info($server_one);



        

        if (!@socket_sendto($sock, $buf, strlen($buf), 0, $server_ip, $port))

        {



            echo "send error\n";



            socket_close($sock);



        }







        $buf = "";



        $msg = ""; #server返回信息



        



        if (!@socket_recvfrom($sock, $msg, 256, 0, $server_ip, $port))

        {



            echo "received error!";



            socket_close($sock);



        }







        echo "<span  style='color:green'>Server  return ===>" . trim($msg) . "</span>\n";



        echo "<hr/>";







        return $msg;

    }



    public function close_sock($sock)

    {



        socket_close($sock);

    }



    //读取该模块的执行和修改权限



    public function beforeFilter()

    {



        $this->checkSession("login_type"); //核查用户身份



        $login_type = $this->Session->read('login_type');



        if ($login_type == 1)

        {



            //admin



            $this->Session->write('executable', true);



            $this->Session->write('writable', true);

        } else

        {



            $limit = $this->Session->read('sst_tools_sipcapture');



            $this->Session->write('executable', $limit['executable']);



            $this->Session->write('writable', $limit['writable']);

        }



        parent::beforeFilter();

    }



    function check_expired_record()

    {



        $list = $this->Capture->query("select *  from    capture   where   (extract (epoch from  now())::bigint-extract (epoch from capture_time )::bigint)>time_val;");



        if (!empty($list))

        {

            $s = count($list);

            for ($i = 0; $i < $s; $i++)

            {



                $file_name = $list[$i][0]['file_name'];



                $server_ip = $list[$i][0]['server_ip'];

                

                $server_port = $list[$i][0]['server_port'];



                $file_size = $this->find_file_size($file_name, $server_ip, $server_port);



                if (empty($file_size))

                {



                    $file_size = 0;

                }



                $this->Capture->query("update  capture  set  file_size=$file_size where file_name='$file_name' ;");

            }

        }

        $list = $this->Capture->query("update  capture  set  flag=1    where   (extract (epoch from  now())::bigint-extract (epoch from capture_time )::bigint)>time_val;");

    }



    function sip_capture()

    {



        $this->pageTitle = "Tools/SIP Capture";



        //$this->check_expired_record();



        $this->set('p', $this->Capture->get_all_capture(

                        $this->_order_condtions(array('user_name', 'capture_time', 'time_val', 'src_ip', 'src_port', 'dest_ip', 'dest_port', 'key_word', 'file_size'))

                ));



        $this->set('servers', $this->Systemparam->find_all_class4());



        $this->set('ingress_alias', $this->Resource->find_ingress_alias());



        $this->set('egress_alias', $this->Resource->find_egress_alias());

    }



    #开始抓包



    function start()

    {

        $this->layout = false;

        $this->autoRender = false;

        $capture_errors = array();

        //数据验证

        $ingress_id = $this->data['ingress_alias'];

        $egress_id = $this->data['egress_alias'];



        $server_list = array();

        $server_post = $this->data['server'];

        if ($server_post == 'all')

        {

            //$server_result = $this->Capture->query("select ip,port from  server_platform where server_type=2 ");

            $server_result = $this->Capture->query("select sip_ip as ip, sip_port as port from switch_profile");

            foreach ($server_result as $server_item)

            {

                array_push($server_list, array(

                    $server_item[0]['ip'],

                    $server_item[0]['port'],

                ));

            }

        } else

        {

            array_push($server_list, explode(':', $server_post));

        }

        

        $source_host_port = explode(":", $this->data['sourceHostPort']);



        if (count($source_host_port) == 2 && $source_host_port[0] == 0 && $source_host_port[1] == 0)

        {

            $is_source_all_ip = true;

        } else

        {

            $is_source_all_ip = false;

        }



        $source_host = $this->PhpCommon->format_ip($source_host_port[0]);



        if (!empty($source_host) && !$this->PhpCommon->is_ip($source_host))

        {



            $capture_errors['sourcehostv'] = __('invalidsourceip', true);



            $source_host = null;

        }







        $source_port = $this->PhpCommon->format_port($source_host_port[1]);



        if ((!empty($source_port) && !$this->PhpCommon->is_port($source_port)))

        {



            $capture_errors['source_port'] = __('invalidsourceport', true);



            $source_port = null;

        }





        $dest_host_port = explode(":", $this->data['destHostPort']);



        if (count($dest_host_port) == 2 && $dest_host_port[0] == 0 && $dest_host_port[1] == 0)

        {

            $is_dest_all_ip = true;

        } else

        {

            $is_dest_all_ip = false;

        }



        $dest_host = $this->PhpCommon->format_ip($dest_host_port[0]);



        if (!empty($dest_host) && !$this->PhpCommon->is_ip($dest_host))

        {



            $capture_errors['desthostv'] = __('invaliddestip', true);



            $dest_host = null;

        }



        $dest_port = $this->PhpCommon->format_port($dest_host_port[1]);







        if (!empty($dest_port) && !$this->PhpCommon->is_port($dest_port))

        {



            $capture_errors['dest_port'] = __('invaliddestport', true);



            $dest_port = null;

        }







        if (!empty($capture_errors))

        {



            echo 'false';



            return;

        }



        

       

        $remain_time = (int) $this->data['remain_time'];



        $remain_time = $remain_time > 0 ? $remain_time : 60;



        $remain_time = $remain_time > 18000 ? 18000 : $remain_time;



        $source_port = $source_port == 0 ? '' : $source_port;



        $dest_port = $dest_port == 0 ? '' : $dest_port;



        $keyword = $this->data['keyword'];



        $keyword = empty($keyword) ? "SIP" : $keyword;



        $d = array();



        $d['ani'] = isset($this->data['ani']) ? $this->data['ani'] : null;



        $d['dnis'] = isset($this->data['dnis']) ? $this->data['dnis'] : null;







        $d['time_val'] = $remain_time;



        $d['user_id'] = $this->Session->read('sst_user_id');



        //$d['file_name'] = "pcap_".time().".pcap";

        //$server_ip_arr = explode(':', $this->data['server']);

        //$d['server_ip'] = $server_ip_arr[0];



        $d['src_ip'] = $source_host;



        $d['src_port'] = $source_port;



        $d['dest_ip'] = $dest_host;



        $d['dest_port'] = $dest_port;



        $d['key_word'] = $keyword;



        $d['view'] = false;



        $d['pid'] = 0;



        //$d['server_port'] = $server_ip_arr[1];



        foreach ($server_list as $server_one)

        {

            list($d['server_ip'], $d['server_port']) = $server_one;

            



            $capture = $this->Capture->create_new($d); //add capture info  to database  return capture model

            

            //$this->Capture->save($capture);

            

            //$capture['Capture']['capture_id'] = $this->Capture->getLastInsertID();



            if ($capture)

            {



                $shell = Configure::read('sip_capture.timeout_shell');



                if (empty($shell))

                {



                    $shell = APP . 'vendors/shells/timeout.sh';

                }



                $dir = Configure::read('sip_capture.pcap_dir');



                if (empty($dir))

                {



                    $dir = APP . "tmp/capture_files"; #perl脚本的位置

                }



                $ngrep = Configure::read('sip_capture.ngrep');



                if (empty($ngrep))

                {



                    $ngrep = "/usr/bin/ngrep";

                }



                $id = $capture['Capture']['capture_id'];







                #组装ngrep命令

                //$command_and_paramters="ngrep -pq \"sip\" host 192.168.1.115 or 192.168.1.116 and port 5060 or 9900";



                $cmd_options = '';



                $t_ip = '';



                $t_port = array();







                if ($capture['Capture']['src_ip'] && !$capture['Capture']['dest_ip'])

                {



                    $t_ip = '  ' . $capture['Capture']['src_ip'];

                }







                if (!$capture['Capture']['src_ip'] && $capture['Capture']['dest_ip'])

                {



                    $t_ip = '  ' . $capture['Capture']['dest_ip'];

                }



                if ($capture['Capture']['src_ip'] && $capture['Capture']['dest_ip'])

                {



                    $t_ip = '  ' . $capture['Capture']['src_ip'] . ' or ' . $capture['Capture']['dest_ip'];

                }











                if ($capture['Capture']['src_port'] && $capture['Capture']['dest_port'])

                {



                    $t_port = '  ' . $capture['Capture']['src_port'] . ' or ' . $capture['Capture']['dest_port'];

                }







                if (!$capture['Capture']['src_port'] && $capture['Capture']['dest_port'])

                {



                    $t_port = '  ' . $capture['Capture']['dest_port'];

                }







                if ($capture['Capture']['src_port'] && !$capture['Capture']['dest_port'])

                {



                    $t_port = '  ' . $capture['Capture']['src_port'];

                }



                if (!empty($t_port))

                {



                    $cmd_options = '  host  ' . $t_ip . '	and	' . 'port' . $t_port;

                }



                #

                //$new_server = "{$this->data['server']} : {$d['server_port']}";











                $arr = array(

                    'src_ip' => $capture['Capture']['src_ip'],

                    'dest_ip' => $capture['Capture']['dest_ip'],

                    'src_port' => $capture['Capture']['src_port'],

                    'dest_port' => $capture['Capture']['dest_port'],

                    'keyword' => $d['key_word'],

                    'ani' => $d['ani'],

                    'dnis' => $d['dnis'],

                    'server' => $server_one

                );







                $command_and_paramters = $this->create_ngrep_cmd($arr, $is_source_all_ip, $is_dest_all_ip, $ingress_id, $egress_id, $server_one);

                

                

                

                $file_name = $this->start_capture($command_and_paramters, $remain_time, $server_one);







                #update file_name



                $this->Capture->query("update  capture  set file_name='$file_name' where capture_id = {$capture['Capture']['capture_id']} ");











                //$cmd = "sh " .$shell ." $remain_time $id  '$capture_file' " . $cmd;

                //pclose(popen($cmd . '&','r'));

                //			echo $cmd;



                echo "true";

            } else

            {



                echo "false";

            }

        }

    }



    function create_ngrep_cmd($arr, $is_source_all_ip, $is_dest_all_ip, $ingress_id, $egress_id, $server_one)

    {







        $quotein = "";







        $quoteout = array();







        $quoteout2 = array();







        if (!empty($arr['keyword']))

        {



            $quotein .= $arr['keyword'];

        }







        if (!empty($arr['dnis']) || !empty($arr['ani']))

        {



            $quotein .= ":";



            $quotein .= "(";



            $inarr = array();



            if (!empty($arr['dnis']))

            {



                array_push($inarr, $arr['dnis']);

            }







            if (!empty($arr['ani']))

            {



                array_push($inarr, $arr['ani']);

            }



            $instr = implode("|", $inarr);



            $quotein .= $instr;



            $quotein .= ")";

        }







        $serverstr = '';





        $server = $server_one;



        //host server_ip and ip1 or ip2 and port server_port and port1 or port2



        /*

         * ngrep -d any -S 2000 -W byline  -wi 'SIP:(111|222)' host 192.168.1.8 or 192.168.1.8 and port 7300 or 9301 and host 192.168.1.115 and port 5024 

         */



        $serverstr_arr = array();





        if ($is_source_all_ip)

        {

            $sql = "select ip,port from resource_ip  where resource_id = {$ingress_id}";

            $result = $this->Capture->query($sql);

            foreach ($result as $item)

            {

                $temp = "host {$server[0]} and {$item[0]['ip']} and port {$server[1]} and {$item[0]['port']}";

                array_push($serverstr_arr, $temp);

            }

        } elseif (!empty($arr['src_ip']))

        {

            $temp = "host {$server[0]} and {$arr['src_ip']} and port {$server[1]} and {$arr['src_port']}";

            array_push($serverstr_arr, $temp);

        } 


        if ($is_dest_all_ip)

        {

            $sql = "select ip,port from resource_ip  where resource_id = {$egress_id}";

            $result = $this->Capture->query($sql);

            foreach ($result as $item)

            {

                $temp = "host {$server[0]} and {$item[0]['ip']} and port {$server[1]} and {$item[0]['port']}";

                array_push($serverstr_arr, $temp);

            }

        } elseif (!empty($arr['dest_ip']))

        {

            $temp = "host {$server[0]} and {$arr['dest_ip']} and port {$server[1]} and {$arr['dest_port']}";

            array_push($serverstr_arr, $temp);

        }

        

        

        if (empty($serverstr_arr)) {

            $temp = "host {$server[0]} and port {$server[1]}";

            array_push($serverstr_arr, $temp);

        }
        
        
        if (empty($serverstr_arr)) {
            $temp = "host {$server[0]} and port {$server[1]}";
            array_push($serverstr_arr, $temp);
        }



        $serverstr = implode(' or ', $serverstr_arr);



        /*



          if (!empty($arr['src_ip']) && !empty($arr['dest_ip'])) {

          //$serverstr = "host {$arr['src_ip']} or {$arr['dest_ip']} and port {$arr['src_port']} or {$arr['dest_port']} and host $server[0] and port $server[1]";

          //$serverstr = "host {$server[0]} and {$arr['src_ip']} or {$arr['dest_ip']} and port {$server[1]} and {$arr['src_port']} or {$arr['dest_port']}";

          $serverstr = "host {$server[0]} and {$arr['src_ip']} and port {$server[1]} and {$arr['src_port']}  or host {$server[0]} and {$arr['dest_ip']} and port {$server[1]} and {$arr['dest_port']}";

          } elseif (!empty($arr['src_ip'])) {

          //$serverstr = "host $server[0] and {$arr['src_ip']} and port $server[1] and {$arr['src_port']}";

          //$serverstr = "host {$server[0]} and {$arr['src_ip']} and port {$server[1]} and {$arr['src_port']}";

          $serverstr = "host {$server[0]} and {$arr['src_ip']} and port {$server[1]} and {$arr['src_port']}";

          } elseif (!empty($arr['dest_ip'])) {

          //$serverstr = "host $server[0] and {$arr['dest_ip']} and port $server[1] and {$arr['dest_port']}";

          //$serverstr = "host {$server[0]} and {$arr['dest_ip']} and port {$server[1]} and {$arr['dest_port']}";

          $serverstr = "host {$server[0]} and {$arr['dest_ip']} and port {$server[1]} and {$arr['dest_port']}";

          }

         * 

         */



        $base = "ngrep -d any -S 2000 -W byline  -wi '" . $quotein . "' {$serverstr}";



        return $base;

    }



//	function stop($id=null){

//		Configure::write('debug', 0);

//		$this->layout = '';

//		$this->autoRender = false;

//		if(!empty($id)){

//			$id = (int)$id;

//		}

//		if($id > 0 || $id == null){

//			if($id == null){

//				$capture = $this->Capture->find('first',array('conditions' => "pid > 0"));

//			}else{

//				$capture = $this->Capture->find('first',array('conditions' => "capture_id = $id"));

//			}

//			if($this->Capture->is_ready_to_stop($capture)){

//				`kill -9 {$capture['Capture']['pid']}`;

//			}else{

//				echo false;

//			}

//		}else{

//			echo 'false';

//		}		

//	}	















    function download($id = null)

    {



        Configure::write('debug', 0);



        $this->layout = '';



        $this->autoRender = false;



        $id = (int) $id;



        if ($id > 0)

        {



            $capture = $this->Capture->find('first', array('conditions' => "capture_id = $id"));



            if (!empty($capture))

            {



                $filename = $capture['Capture']['file_name'];



                list($y, $m, $d) = explode("-", $filename);



                $dir = Configure::read('sip_capture.pcap_dir');



                $file_dir = $dir . "sip_capture" . $d . DS . $filename;



                if (empty($dir))

                {



                    $dir = APP . "tmp/capture_files";

                }







                $file_dir = $this->find_file($capture['Capture']['server_ip'], $capture['Capture']['server_port'], $filename);



                $this->_send_file($file_dir);

            }

        }

    }



    function view($id = null)

    {



        Configure::write('debug', 0);



        //$this->layout = '';

        //$this->autoRender = false;



        $id = (int) $id;



        if ($id > 0)

        {



            $capture = $this->Capture->find('first', array('conditions' => "capture_id = $id"));



            //pr($capture);



            if (!(empty($capture)))

            {



                #pcap  file 



                $filename = $capture['Capture']['file_name'];









                $ani = $capture['Capture']['ani'];



                $dnis = $capture['Capture']['dnis'];







                $file_dir = $this->find_file($capture['Capture']['server_ip'], $capture['Capture']['server_port'],$filename);











                //	$dir = Configure::read('sip_capture.pcap_dir');

//				if($ani!=''&&$dnis!=''){

//                                                                    #perl scenario run .pacp file

//                                    $sip_scenario = Configure::read('sip_capture.single');

//

//                                    pr($sip_scenario);

//                                            if(empty($sip_scenario)){

//                                            $sip_scenario = APP.'vendors/shells/single.pl';

//

//                                    }

//                                    $tmp_dir = '/tmp/sip_scenario/'.gmdate("Y-m-d",time());	

//                                    `mkdir -p $tmp_dir`;

//

//                                    `cd $tmp_dir;perl $sip_scenario -include:expression:"(?i)^invite.*($ani|$dnis)@" {$file_dir}`;

//                                    echo file_get_contents($tmp_dir.DS.preg_replace('/\.pcap$/i','.html',$filename));

//				}else{

                #perl scenario run .pacp file



                $sip_scenario = Configure::read('sip_capture.sip_scenario');



                if (empty($sip_scenario))

                {



                    $sip_scenario = APP . 'vendors/shells/sip_scenario.pl';

                }



                $tmp_dir = '/tmp/sip_scenario/' . gmdate("Y-m-d", time());



                `mkdir -p $tmp_dir`;



                `cd $tmp_dir;perl $sip_scenario {$file_dir}`;



                //echo file_get_contents("/tmp/sip_scenario/2011-08-02/2011-08-02-0841162089018456.html");



                $this->set('param', $tmp_dir . DS . preg_replace('/\.pcap$/i', '.html', basename($filename)));







//				}

            }

        }

    }



    /*







      public function sip_capture() {



      Configure::write('debug', 0);



      $currPage = 1;



      $pageSize = 100;



      $search = null;







      $user_id = $this->Session->read('user_id');



      if (! empty ( $_REQUEST ['page'] )) {



      $currPage = $_REQUEST ['page'];



      }







      if (! empty ( $_REQUEST ['size'] )) {



      $pageSize = $_REQUEST ['size'];



      }







      if (!empty($_REQUEST['search'])) {



      $search = $_REQUEST['search'];



      $this->set('search',$search);



      }







      //分页信息



      require_once 'MyPage.php';



      $page = new MyPage();







      $totalrecords = 0;







      if (!empty($search)) {



      $totalrecords = $this->Productitem->query("select count(capture_id) as c from capture where user_id = '$user_id'");



      } else {



      $totalrecords = $this->Productitem->query("select count(capture_id) as c from capture where user_id = '$user_id'");



      }







      $page->setTotalRecords($totalrecords[0][0]['c']);//总记录数



      $page->setCurrPage($currPage);//当前页



      $page->setPageSize($pageSize);//页大小







      //$page = $page->checkRange($page);//检查当前页范围







      $currPage = $page->getCurrPage()-1;



      $pageSize = $page->getPageSize();







      //查询Product



      $sql = "



      select capture_id,capture_time,time_val,src_ip,src_port,



      dest_ip,dest_port,key_word,file_size,view



      from capture where user_id = '$user_id' order by capture_time desc";







      if (!empty($search)) {



      $sql .= " where name like '%$search%' limit '$pageSize' offset '$currPage'";



      } else {



      $sql .= " limit '$pageSize' offset '$currPage'";



      }







      $results = $this->Productitem->query($sql);







      $page->setDataArray($results);//Save Data into $page







      $this->set('p',$page);



      }











      //发送请求开始抓包



      public function start_capture() {



      //Configure::write('debug', 0);



      //数据验证



      $field1= $_REQUEST['srcIp'];



      if (!empty($field1)) {



      $ips = $this->Productitem->query("select count(ip4r('$field1'::ip4r)) as c");



      if ($ips[0][0]['c'] != 1){echo __('invalidsourceip',true)."|sourcehostv";exit();}



      }







      $field2 = $_REQUEST['srcPort'];



      if (!empty($field2)) {



      if (!preg_match('/^[0-9]+$/',$field2)) {echo __('invalidsourceport',true)."|sourceportv";exit();}



      }







      $field3= $_REQUEST['destIp'];



      if (!empty($field3)) {



      $ips = $this->Productitem->query("select count(ip4r('$field3'::ip4r))");



      if (count($ips) == 0){echo __('invaliddestip',true)."|desthostv";exit();}



      }







      $field4 = $_REQUEST['destPort'];



      if (!empty($field4)) {



      if (!preg_match('/^[0-9]+$/',$field4)) {echo __('invaliddestport',true)."|destportv";exit();}



      }







      $field5 = $_REQUEST['durations'];



      if (!preg_match('/^[0-9]+$/',$field5)){echo __('invalidtimeformat',true)."|duration";exit();}







      $field6 = $_REQUEST['fsize'];



      if (!preg_match('/^[0-9]+$/',$field6)){echo __('invalidtimeformat',true)."|capacitylimit";exit();}







      ////////////////////////////////////







      $param = ''; //抓包的参数







      $keyWord = "SIP"; //默认抓包关键字



      if (! empty ( $_REQUEST ['keyword'] )) {



      $keyWord = $_REQUEST ['keyword'];



      }



      $this->Session->write('capture_key_word',$keyWord);







      $durations = $_REQUEST ['durations']; //抓包时长











      $fsize = $_REQUEST ['fsize']; //抓包允许的文件大小  超过则停止抓包











      $param .= "kw=" . $keyWord . "&due=" . $durations . "&fsize=" . $fsize;







      //	pr($param);







      //源网关或端口条件



      $src = null;







      if (! empty ( $_REQUEST ['srcIp'] )) { //有源网关的条件



      $srcIp = $_REQUEST ['srcIp'];







      $param .= "&srcIp=" . $srcIp;







      $src = " src host " . $srcIp;







      $this->Session->write('capture_srcip',$srcIp);



      if (! empty ( $_REQUEST ['srcPort'] )) { //有源端口的条件



      $srcPort = $_REQUEST ['srcPort'];



      $src = $src . " and src port " . $srcPort;



      $param .= "&srcPort=" . $srcPort;



      $this->Session->write('capture_srcport',$srcPort);



      }



      } else {



      if (! empty ( $_REQUEST ['srcPort'] )) { //只有源端口没有源网关的条件



      $srcPort = $_REQUEST ['srcPort'];



      $src = " src port " . $srcPort;



      $param .= "&srcPort=" . $srcPort;



      $this->Session->write('capture_srcport',$srcPort);



      }



      }







      //目标网关或目标端口条件



      $det = null;







      if (! empty ( $_REQUEST ['destIp'] )) { //有目标网关的条件



      $destIp = $_REQUEST ['destIp'];



      $det = " dst host " . $destIp;



      $param .= "&destIp=" . $destIp;



      $this->Session->write('capture_destip',$destIp);



      if (! empty ( $_REQUEST ['destPort'] )) { //有目标端口的条件



      $destPort = $_REQUEST ['destPort'];



      $det = $det . " and dst port " . $destPort;



      $param .= "&destPort=" . $destPort;



      $this->Session->write('capture_destport',$destPort);



      }



      } else {



      if (! empty ( $_REQUEST ['destPort'] )) { //只有目标端口没有目标网关的条件



      $destPort = $_REQUEST ['destPort'];



      $det = " dst port " . $destPort;



      $param .= "&destPort=" . $destPort;



      $this->Session->write('capture_destport',$destPort);



      }



      }







      if ($src != null) { //用户既输入了源条件也输入了目标条件的情况



      $this->captureSip = "ngrep -q  " . $keyWord . " " . "\"(" . $src . ")\"";



      if ($det != null) {



      $this->captureSip .= " and " . "\"(" . $det . ")\"";



      }



      } else {



      if ($det != null) { //用户只输入了目标条件的情况



      $this->captureSip = "ngrep -q  " . $keyWord . "\"(" . $det . ")\"";



      } else { //无限制条件



      $this->captureSip = "ngrep -q  " . $keyWord;



      }



      }







      $kill_file_name = time()."_kill";//杀掉ngrep进程的文件名字



      $pcap_file_name = time()."_pcap";//pcap文件的名字



      $this->Session->write('start_capture_time',time());



      $param .= "&st=".time()."&pf=".$pcap_file_name."&kf=".$kill_file_name."&cmd=" . $this->captureSip."&uuid=".$this->Session->read('user_id');







      pr($param);



      //使用Socket模拟多线程  开启另一个进程执行抓包



      $this->session_socket = fsockopen ( __('capturesocket',true), __('captureport',true), $errno, $errmsg );



      if (! $this->session_socket) {



      echo __ ( 'cannotcapture', true ) . "|false";



      } else {



      echo __('capturing',true)."|true";



      fputs ( $this->session_socket, "POST /exchange/sipcaptures/capture HTTP/1.1\r\n" );



      fputs ( $this->session_socket, "Host: ".__('capturesocket',true)."\r\n" );



      fputs ( $this->session_socket, "Content-type: application/x-www-form-urlencoded\r\n" );



      fputs ( $this->session_socket, "Content-length: " . strlen ( $param ) . "\r\n" );



      fputs ( $this->session_socket, "Connection: close\r\n\r\n" );



      fputs ( $this->session_socket, $param );



      $this->Session->write('kill_file_name',$kill_file_name);



      $this->Session->write('pcap_file_name',$pcap_file_name);



      fclose ( $this->session_socket );







      $sql="insert into capture(user_id,capture_time,file_name,src_ip,src_port,dest_ip,dest_port,time_val,key_word,file_size,view)";



      $this->Productitem->query($sql);



      fputs($kill_fp,"psql -h $ch -U $cu -d $cdb -c \"insert into capture (user_id,capture_time,file_name,



      src_ip,src_port,dest_ip,dest_port,time_val,key_word,file_size,view)



      values ('\${1}','\${2}','\${3}',\${4},\${5},\${6},\${7},\${8},'\${9}','\${10}','\${11}')\"");



      }



      }







      //停止抓包



      public function stop_capture() {



      $kf = $this->Session->read('kill_file_name');//杀掉进程的shell脚本文件名



      $user_id = $this->Session->read('user_id');



      $capture_time = date('Y-m-d H:i:s',$this->Session->read('start_capture_time')+6*60*60);



      $file_name = $this->Session->read('pcap_file_name').".pcap";



      $tmps = $this->Session->read('capture_srcip');



      $srcip = empty($tmps)?'null':"\\'".$tmps."\\'";







      $tmpp = $this->Session->read('capture_srcport');



      $srcport = empty($tmpp)?'null':$tmpp;







      $tmpd = $this->Session->read('capture_destip');



      $destip = empty($tmpd)?'null':"\\'".$tmpd."\\'";







      $tmptp = $this->Session->read('capture_destport');



      $destport = empty($tmptp)?'null':$tmptp;



      $durations = (time()+6*60*60)-strtotime($capture_time);



      $keyword = $this->Session->read('capture_key_word');



      $fz = filesize($this->file_dir.$this->capture_dir.$file_name);



      $v = $fz > 0?'true':'false';







      $this->Session->delete('kill_file_name');



      $this->Session->delete('start_capture_time');



      $this->Session->delete('pcap_file_name');



      $this->Session->delete('capture_srcip');



      $this->Session->delete('capture_srcport');



      $this->Session->delete('capture_destip');



      $this->Session->delete('capture_destport');



      $this->Session->delete('capture_key_word');







      system($this->file_dir.$this->capture_dir.$kf.".sh '$user_id' '$capture_time' '$file_name' $srcip '$srcport' $destip '$destport' '$durations' '$keyword' '$fz' '$v'");//杀掉正在抓包的进程



      $this->redirect('/sipcaptures/sip_capture');



      }







      //抓取文件



      public function capture() {



      Configure::write ( 'debug', 0 );



      $srcIp = empty ( $_REQUEST ['srcIp'] ) ? 'null' :  "\\'$_REQUEST ['srcIp']\\'";



      $srcPort = empty ( $_REQUEST ['srcPort'] ) ? 'null' : "$_REQUEST ['srcPort']";



      $destIp = empty ( $_REQUEST ['destIp'] ) ? 'null' :  "\\'$_REQUEST ['destIp']\\'" ;



      $destPort = empty ( $_REQUEST ['destPort'] ) ? 'null' :  "$_REQUEST ['destPort']";



      $keyWord = $_REQUEST ['kw'];



      $due = $_REQUEST ['due'];



      $fsize = $_REQUEST ['fsize'];//最大文件大小



      $cmd = $_REQUEST ['cmd'];//执行的命令



      $kf = $_REQUEST['kf'];//杀掉进程的shell脚本文件名



      $pf = $_REQUEST['pf'];//pcap包文件的名字



      $st = date('Y-m-d H:i:s',$_REQUEST['st']);



      $v = time()-$_REQUEST['st'];







      //capture_files文件夹不存在则创建一个



      //并复制sip_scenario.pl文件到该创建的文件夹下面



      if (!is_dir($this->file_dir.$this->capture_dir)) {



      system("cd $this->file_dir"."tmp \r\n mkdir capture_files \r\n");



      system("cp $this->file_dir"."sip_scenario.pl $this->file_dir".$this->capture_dir);



      system("rm -rf $this->file_dir"."tmp/?");



      }







      //如果pl文件不存在  则从根目录下copy一份过来



      if (!file_exists($this->file_dir.$this->capture_dir."sip_scenario.pl")) {



      system("cp $this->file_dir"."sip_scenario.pl $this->file_dir".$this->capture_dir);



      }







      //抓包生成的文件



      $now_time = time ();



      $_fileName = $this->file_dir.$this->capture_dir.$pf.".pcap";



      $fp = fopen ( "$_fileName", "w+" );



      chmod("$_fileName",0777);//赋权限



      fclose ( $fp );







      //建立sh可执行文件  将抓到包的数据写入到上面生成的文件中



      $sh = (time ()."_exec");



      $sh_file = $this->file_dir.$this->capture_dir.$sh.".sh";



      $sh_fp = fopen ( "$sh_file", "w+" );



      fputs ( $sh_fp, "#!/bin/sh\n" );



      fputs ( $sh_fp, "sudo $cmd -O $_fileName -t >/dev/null &\n" );



      fclose ( $sh_fp );



      chmod("$sh_file",0777);//赋权限



      system("$sh_file");











      //建立杀掉进程的sh可执行文件



      $_kill = $this->file_dir.$this->capture_dir.$kf.".sh";



      $kill_fp = fopen ( "$_kill", "w+" );



      $kill_command = "ps ax |grep $pf".".pcap |awk '{print $1}' |xargs kill -2 \n";



      fputs ( $kill_fp, "#!/bin/sh\n" );



      fputs ( $kill_fp, "$kill_command" );



      fputs ( $kill_fp, "cd $this->file_dir$this->capture_dir \n" );



      fputs ( $kill_fp, "perl ".$this->file_dir.$this->capture_dir."sip_scenario.pl " . $this->file_dir.$this->capture_dir.$pf.".pcap \n" );







      $ch = __('capture_host',true);//主机



      $cu = __('capture_user',true);//登录用户



      $cdb = __('capture_db',true);//数据库名称







      fputs($kill_fp,"psql -h $ch -U $cu -d $cdb -c \"insert into capture (user_id,capture_time,file_name,



      src_ip,src_port,dest_ip,dest_port,time_val,key_word,file_size,view)



      values ('\${1}','\${2}','\${3}',\${4},\${5},\${6},\${7},\${8},'\${9}','\${10}','\${11}')\"");



      fclose ( $kill_fp );



      chmod("$_kill",0777);//赋权限







      //检查是否需要stop



      while ( (filesize ( $_fileName ) / 1024 / 1024) < $fsize) {



      sleep(1);



      clearstatcache();



      }







      $fileSize = filesize ( $_fileName ); //文件大小







      $user_id = $_REQUEST['uuid'];







      $view = $fileSize > 0 ? 'true' : 'false';



      system("$_kill '$user_id' '$st' '$pf.pcap' $srcIp '$srcPort' $destIp '$destPort' '$v' '$keyWord' '$fileSize' '$view'");



      }















      function del($id){



      if (empty($id)) $this->redirect('/sipcaptures/sip_capture');







      $f_names = $this->Productitem->query("select file_name from capture where capture_id = '$id'");







      if (count($f_names) > 0) {



      $f_name = $this->file_dir.$this->capture_dir.$f_names[0][0]['file_name'];







      //删除生成的HTML文件



      $o_name = $this->file_dir.$this->capture_dir.$f_names[0][0]['file_name'];



      $only_name = str_replace(".pcap","",$o_name);



      if (file_exists($only_name.".html"))unlink($only_name.".html");



      if (file_exists($only_name.".txt"))unlink($only_name.".txt");



      if (file_exists($only_name."_index.html"))unlink($only_name."_index.html");



      if (file_exists($only_name."_indexhtml.html"))unlink($only_name."_indexhtml.html");











      //如果文件存在则删除该文件   pcap文件



      if (file_exists($f_name))unlink($f_name);







      //如果有sh脚本存在 一并删除 删除sh脚本



      $sh_file = str_replace("_pcap.pcap","_exec.sh",$f_name);



      if (file_exists($sh_file))unlink($sh_file);







      $another_sh_file = str_replace("exec.sh","kill.sh",$sh_file);



      if (file_exists($another_sh_file))unlink($another_sh_file);











      //从数据库中删除Capture记录



      $this->Productitem->query("delete from capture where capture_id = '$id'");



      }



      $this->redirect('/sipcaptures/sip_capture');



      }







      //下载抓到的包



      function down_pcap($id){



      $f_names = $this->Productitem->query("select file_name from capture where capture_id = '$id'");



      if (count($f_names) > 0) {



      $f_name = $this->file_dir.$this->capture_dir.$f_names[0][0]['file_name'];



      $this->Productitem->download_file($f_name);



      } else {



      $this->Session->write('m',$this->Productitem->create_json(101,__('nofile',true)));



      }



      $this->redirect('/sipcaptures/sip_capture');



      }











      function get_file(){



      Configure::write('debug', 0);



      $id = $_REQUEST['id'];



      if (empty($id)) echo __('nofile',true);



      else {



      $f_names = $this->Productitem->query("select file_name from capture where capture_id = '$id'");



      if (count($f_names) == 0) echo __('nofile',true);



      else{



      $n = $this->file_dir.$this->capture_dir.str_replace(".pcap","_indexhtml.html",$f_names[0][0]['file_name']);



      $fp = fopen("$n","r");



      if (!$fp){



      echo __('nofile',true);



      exit();



      }else{



      system("sudo chmod -R 777 $this->file_dir"."views");



      $fpp = fopen($this->file_dir."views/sipcaptures/view_html.ctp","w");



      while(!feof($fp)){



      $buf = fgets($fp);



      fputs($fpp,$buf);



      }



      }



      }



      }



      }







      function view_html(){



      $this->layout = '';



      }



     */



    function ladder()

    {

        

    }



    function get_host_post()

    {



        Configure::write('debug', 0);



        $this->layout = '';



        $this->autoRender = false;







        $ingress = $_POST['ingress'];



        if ($ingress != "")

        {



            $str_sql = "select ip,port from resource_ip  where resource_id = " . $ingress;



            $ip_port = $this->Capture->query($str_sql);







            array_unshift($ip_port, array(

                array('ip' => 'All', 'port' => 'All')

            ));



            echo json_encode($ip_port);

        } else

        {



            echo "";

        }



        //$this->layout = 'ajax';

    }



}


