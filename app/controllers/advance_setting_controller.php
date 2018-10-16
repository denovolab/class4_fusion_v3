<?php 

class AdvanceSettingController extends AppController
{
    var $name = "AdvanceSetting";
    var $uses = array('Systemparam');
    var $components = array('RequestHandler');
    var $helpers = array('javascript', 'html', 'Common');
    
    public function beforeFilter() {
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
        $this->redirect('/advance_setting/web');
    }
    
    public function script()
    {
        $data = array();
        
        $conf_file = Configure::read('script.conf');
        $conf_contents = file_get_contents($conf_file);
        
        $allow_keys = array(
            'web_url',
            'log_file_dir',
            'log_level',
            'db_name',
            'db_host',
            'db_port',
            'db_username',
            'db_password',
            'remote_ip',
            'remote_port',
            'recover_bill_ip',
            'recover_bill_port',
            'recover_local_ip',
            'cdr_softswitch'
        );
        
        if ($this->RequestHandler->isPost())
        {
            foreach ($allow_keys as $allow_key)
            {
                switch ($allow_key)
                {
                    case 'remote_ip':
                        $conf_contents = preg_replace('/remote_ip\s+(.*)/', "remote_ip {$_POST[$allow_key]}',", $conf_contents);
                        break;
                    case 'remote_port':
                        $conf_contents = preg_replace('/remote_port\s+(.*)/', "remote_port {$_POST[$allow_key]}',", $conf_contents);
                        break;
                    default:
                        $conf_contents = preg_replace('/' . $allow_key .'=(.*)/', "{$allow_key}={$_POST[$allow_key]}',", $conf_contents);
                        break;
                }
            }
            file_put_contents($conf_file, $conf_contents);
            $this->Systemparam->create_json_array('', 201, __('The setting of Script is modified successfully!',true));
             $this->Session->write("m",Systemparam::set_validator());
             $this->redirect('/advance_setting/script');
        }
        
        
        $pattern = '/(.*?)=(.*)/';
        preg_match_all($pattern, $conf_contents, $matches, PREG_SET_ORDER);
        
        foreach ($matches as $item)
        {
            if (in_array($item[1], $allow_keys))
            {
                $data[$item[1]] = $item[2];
            }
        }   
        
        $pattern = '/remote_ip\s+(.*)/';
        preg_match($pattern, $conf_contents, $matches);
        $data['remote_ip'] = $matches[1];
        $pattern = '/remote_port\s+(.*)/';
        preg_match($pattern, $conf_contents, $matches);
        $data['remote_port'] = $matches[1];
        
        $this->set('data', $data);
    }
    
    public function web()
    {
        $db_keys = array(
            'host'      => 'web_db_host',
            'login'     => 'web_db_user',
            'database'  => 'web_db_name',
            'password'  => 'web_db_password',
            'port'      => 'web_db_port',
        );
        $myconf_keys = array(
            'backend.ip'   => 'switch_ip',
            'backend.port' => 'switch_port',
            'script.path' => 'script_path',
            'script.conf' => 'script_conf',
            'sip_capture_ip' => 'sip_capture_ip',  // - 
            'sip_capture_port' => 'sip_capture_port', // -
            'active_call.test_local_ip' => 'web_server_ip',
            'active_call.test_local_port' => 'web_server_port',
            'active_call.active_call_server_ip' => 'active_server_ip',
            'active_call.active_call_server_port' => 'active_server_port',
            'billing_server_ip' => 'billing_server_ip',  // -
            'billing_server_port' => 'billing_server_port', // -
            'php_path' => 'php_path',  // -
            'statistics.group_all' => 'group_all',  // -
            'web_db_export_path' => 'web_db_export_path',
            'cdr.tmp' => 'cdr_tmp_path',
            'license.path' => 'license_path',
        );
        $db_file_path     = APP . DS . 'config' . DS . 'database.php';
        $db_file_contents = file_get_contents($db_file_path);
        $myconf_file_path = APP .DS .'config'. DS . 'myconf.php';
        $myconf_file_contents = file_get_contents($myconf_file_path);
        
        if ($this->RequestHandler->isPost())
        {
             foreach ($db_keys as $key => $item)
             {
                 $db_file_contents = preg_replace('/\'' . $key . '\'\s+=>\s+\'(.*)\'/', "'{$key}' => '{$_POST[$item]}'", $db_file_contents);
             }
             file_put_contents($db_file_path, $db_file_contents);
             
             foreach ($myconf_keys as $key => $item)
             {
                 switch($item)
                 {
                     case 'sip_capture_ip':
                         $myconf_file_contents = preg_replace('/\'host_ip\' => \'(.*)\',/', "'host_ip' => '{$_POST[$item]}',", $myconf_file_contents);
                         break;
                     case 'sip_capture_port':
                         $myconf_file_contents = preg_replace('/\'port\' => \'(.*)\',/', "'port' => '{$_POST[$item]}',", $myconf_file_contents);
                         break;
                     case 'billing_server_ip':
                         $myconf_file_contents = preg_replace('/\$config\[\'active_call\'\]\[\'billing_server\'\]\s+=\s+array\(.*\'(.*)\',.*\);/ms', "\$config['active_call']['billing_server'] = array(\n'{$_POST[$item]}:{$_POST['billing_server_port']}',\n);", $myconf_file_contents);
                         break;
                     case 'billing_server_port':
                         break;
                     case 'php_path':
                         $myconf_file_contents = preg_replace('/Configure::write\(\'php_exe_path\', \'(.*)\'\);/', "Configure::write('php_exe_path', '{$_POST[$item]}');", $myconf_file_contents);
                         break;
                     case 'web_db_export_path':
                         $myconf_file_contents = preg_replace('/Configure::write\(\'database_export_path\', \'(.*)\'\);/', "Configure::write('database_export_path', '{$_POST[$item]}');", $myconf_file_contents);
                         break;
                     default:
                         $keys = explode('.', $key);
                         if (count($keys) == 2) {
                             $value = $_POST[$item];
                             if ($value === 'true')
                             {
                                 $value = 'TRUE';
                             }
                             else if ($value === 'false')
                             {
                                 $value = 'FALSE';
                             }
                             else
                             {
                                 $value = "'" . $value . "'";
                             }
                             $myconf_file_contents = preg_replace('/\$config\[\'(\w+)\'\]\[\'(\w+)\'\]\s+=\s+(.*);/', "\$config['{$keys[0]}']['{$keys[1]}'] = '{$value}';", $myconf_file_contents);
                         }
                         break;
                 }
             }
             file_put_contents($myconf_file_path, $myconf_file_contents);
             $this->Systemparam->create_json_array('', 201, __('The setting of Web is modified successfully!',true));
             $this->Session->write("m",Systemparam::set_validator());
             $this->redirect('/advance_setting/web');
        }
        $data = array();
        
        
        
        $pattern = '/\'(\w.*)\'\s+=>\s+\'(.*)\'/';
        preg_match_all($pattern, $db_file_contents, $matches);
        foreach ($matches[1] as $key => $item)
        {
            if (array_key_exists($item, $db_keys))
            {
                $data[$db_keys[$item]] = $matches[2][$key];
            }
        }        
        
        
        $pattern = '/\$config\[\'(\w+)\'\]\[\'(\w+)\'\]\s+=\s+(.*);/';
        preg_match_all($pattern, $myconf_file_contents, $matches, PREG_SET_ORDER);
        //print_r($matches);
        
        
        
        foreach ($matches as $item)
        {
            $key = $item[1] . "." . $item[2];
            if (array_key_exists($key, $myconf_keys))
            {
                $data[$myconf_keys[$key]] = trim($item[3], "'\"");
            }
        }
        
        $pattern = '/\'host_ip\' => \'(.*)\',/';
        preg_match($pattern, $myconf_file_contents, $matches);        
        $data['sip_capture_ip'] = $matches[1];        
        $pattern = '/\'port\' => \'(.*)\',/';
        preg_match($pattern, $myconf_file_contents, $matches);        
        $data['sip_capture_port'] = $matches[1];
        
        $pattern = '/\$config\[\'active_call\'\]\[\'billing_server\'\]\s+=\s+array\(.*\'(.*)\',.*\);/ms';
        preg_match($pattern, $myconf_file_contents, $matches);        
        $billing_server = explode(':', $matches[1]);
        $data['billing_server_ip'] = $billing_server[0];
        $data['billing_server_port'] = $billing_server[1];
        
        $pattern = '/Configure::write\(\'php_exe_path\', \'(.*)\'\);/';
        preg_match($pattern, $myconf_file_contents, $matches);  
        $data['php_path'] = $matches[1];
        
        $pattern = '/Configure::write\(\'database_export_path\', \'(.*)\'\);/';
        preg_match($pattern, $myconf_file_contents, $matches);  
        $data['web_db_export_path'] = $matches[1];
        
        
        $this->set('data', $data);
    }
    
}