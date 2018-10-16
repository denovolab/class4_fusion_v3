<?php

class LrnsettingsController extends AppController
{

    var $name = 'Lrnsettings';
    var $components = array('PhpTelnet');

    function index()
    {
        $this->redirect('view');
    }

    /*
     * 向后台发送命令  返回模拟结果
     */

    public function view()
    {
        $this->pageTitle = "Configuration/LRN Setting";
        $this->set('post', $this->Lrnsetting->findlrn());
    }
    

    public function testing()
    {
        Configure::write('debug', 0);
        putenv('PATH=/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin');
        putenv('LC_ALL=en_US.UTF-8');
        putenv('LANG=en_US.UTF-8');
        $this->autoLayout = false;
        $this->autoRender = false;
        Configure::load('myconf');
        $script_path = Configure::read('script.path');
        $script_conf = Configure::read('script.conf');
        //$script_conf = '/home/hewx/htdocs/class4-script/class4.conf';
        $local_ip = Configure::read('lrn.ip');
        $timeout = empty($_POST['timeout']) ? 500 : $_POST['timeout'];
        
        $cmd = "python3 $script_path/class4_lrn_test.py -c $script_conf -t $timeout -i $local_ip";
        $result = shell_exec($cmd);
        echo $result;
    }

    //读取该模块的执行和修改权限
    public function beforeFilter()
    {
        $this->checkSession("login_type"); //核查用户身份
        $login_type = $this->Session->read('login_type');
        if ($login_type == 1) {
            //admin
            $this->Session->write('executable', true);
            $this->Session->write('writable', true);
        } else {
            $limit = $this->Session->read('sst_retail_rcardpools');
            $this->Session->write('executable', $limit ['executable']);
            $this->Session->write('writable', $limit ['writable']);
        }
        parent::beforeFilter();
    }

    //更新系统容量设置
    public function ajax_update()
    {
        if (!$_SESSION['role_menu']['Configuration']['lrnsettings']['model_w']) {
            $this->redirect_denied();
        }
        Configure::write('debug', 0);

        $ip1 = !empty($_POST['ip1']) ? $_POST['ip1'] : NULL;
        $ip2 = !empty($_POST['ip2']) ? $_POST['ip2'] : NULL;

        $port1 = !empty($_POST['port1']) ? $_POST['port1'] : NULL;
        $port2 = !empty($_POST['port2']) ? $_POST['port2'] : NULL;

        $timeout1 = !empty($_POST['timeout1']) ? $_POST['timeout1'] : NULL;
        $timeout2 = !empty($_POST['timeout2']) ? $_POST['timeout2'] : NULL;

        $this->data['Lrnsetting']['ip1'] = $ip1;
        $this->data['Lrnsetting']['ip2'] = $ip2;
        $this->data['Lrnsetting']['port1'] = $port1;
        $this->data['Lrnsetting']['port2'] = $port2;
        $this->data['Lrnsetting']['timeout1'] = $timeout1;
        $this->data['Lrnsetting']['timeout2'] = $timeout2;
        //	$sql="insert into lrn (ip1,ip2,port1,port2,timeout1,timeout2)values('$ip1'::ip4r,'$ip2'::ip4r,$port1,$port2,$timeout1,$timeout2) ";
        //echo $sql;
        $this->Lrnsetting->query("delete from lrn ");
        $this->Lrnsetting->save($this->data);

        //	$this->send(" update_lnp_conf \n"."\n");
        $this->set('extensionBeans', '1');
    }

    /*
     * 发送命令到Socket 并返回执行命令结果
     */

    private function send($cmd = null)
    {
        if (!$_SESSION['role_menu']['Configuration']['lrnsettings']['model_x']) {
            $this->redirect_denied();
        }
        $result = $this->PhpTelnet->getResult("api " . $cmd);
        return $result;
    }

}