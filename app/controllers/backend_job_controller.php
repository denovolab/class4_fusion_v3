<?php

class BackendJobController extends AppController
{
    var $name = "BackendJob";
    var $uses = array('Class4Log');
    var $helpers = array ('Javascript', 'Html');
    
    public function beforeFilter() 
    {
        parent::beforeFilter();
        $this->checkSession("login_type"); 
    }
    
    public function create_cdr_report_table()
    {
        $this->autoRender = false;
        $this->autoLayout = false;
        $script_path = Configure::read('script.path');
        $script_file = $script_path . DS . 'class4_create_table.pl';
        $script_conf = Configure::read('script.conf');
        $cmd = "{$script_file} -c {$script_conf} & > /dev/null 2>&1";
        shell_exec($cmd);
        $this->Session->write('m', $this->Class4Log->create_json(201, __('Success!', true)));
        $this->redirect('/backend_job/trigger');
    }
    
    public function update_db_record()
    {
        $this->autoRender = false;
        $this->autoLayout = false;
        $script_path = Configure::read('script.path');
        $script_file = $script_path . DS . 'class4_update_record.pl';
        $script_conf = Configure::read('script.conf');
        $cmd = "{$script_file} -c {$script_conf} & > /dev/null 2>&1";
        shell_exec($cmd);
        $this->Session->write('m', $this->Class4Log->create_json(201, __('Success!', true)));
        $this->redirect('/backend_job/trigger');
    }
    
    public function alert_route()
    {
        $this->autoRender = false;
        $this->autoLayout = false;
        $script_path = Configure::read('script.path');
        $script_file = $script_path . DS . 'class4_alert_route.pl';
        $script_conf = Configure::read('script.conf');
        $cmd = "{$script_file} -c {$script_conf} & > /dev/null 2>&1";
        shell_exec($cmd);
        $this->Session->write('m', $this->Class4Log->create_json(201, __('Success!', true)));
        $this->redirect('/backend_job/trigger');
    }
    
    public function cdr_report()
    {
        if($this->RequestHandler->ispost())
        {
            $script_path = Configure::read('script.path');
            $script_file = $script_path . DS . 'class4_cdr_report.pl';
            $script_conf = Configure::read('script.conf');
            $start_time = $_POST['start_time'];
            $end_time = $_POST['end_time'];
            $cmd = "{$script_file} -c {$script_conf} -s '{$start_time}' -e '{$end_time}' & > /dev/null 2>&1";
            shell_exec($cmd);
            $this->Session->write('m', $this->Class4Log->create_json(201, __('Success!', true)));
            $this->redirect('/backend_job/trigger');
        }
    }
    
    public function ftp_cdr()
    {
        if($this->RequestHandler->ispost())
        {
            $script_path = Configure::read('script.path');
            $script_file = $script_path . DS . 'class4_ftp_cdr.pl';
            $script_conf = Configure::read('script.conf');
            $start_time = $_POST['start_time'];
            $end_time = $_POST['end_time'];
            $cmd = "{$script_file} -c {$script_conf} -s '{$start_time}' -e '{$end_time}' & > /dev/null 2>&1";
            shell_exec($cmd);
            $this->Session->write('m', $this->Class4Log->create_json(201, __('Success!', true)));
            $this->redirect('/backend_job/trigger');
        }
    }
    
    public function cdr_import()
    {
        $this->autoRender = false;
        $this->autoLayout = false;
        $script_path = Configure::read('script.path');
        $script_file = $script_path . DS . 'class4_cdr_import.pl';
        $script_conf = Configure::read('script.conf');
        $cmd = "{$script_file} -c {$script_conf} & > /dev/null 2>&1";
        shell_exec($cmd);
        $this->Session->write('m', $this->Class4Log->create_json(201, __('Success!', true)));
        $this->redirect('/backend_job/trigger');
    }
    
    public function dns_dig()
    {
        $this->autoRender = false;
        $this->autoLayout = false;
        $script_path = Configure::read('script.path');
        $script_file = $script_path . DS . 'class4_dns_dig.pl';
        $script_conf = Configure::read('script.conf');
        $cmd = "{$script_file} -c {$script_conf} & > /dev/null 2>&1";
        shell_exec($cmd);
        $this->Session->write('m', $this->Class4Log->create_json(201, __('Success!', true)));
        $this->redirect('/backend_job/trigger');
    }
    
    public function finance_transaction()
    {
        if($this->RequestHandler->ispost())
        {
            $script_path = Configure::read('script.path');
            $script_file = $script_path . DS . 'class4_transaction.pl';
            $script_conf = Configure::read('script.conf');
            $start_time = $_POST['start_time'];
            $cmd = "{$script_file} -c {$script_conf} -s '{$start_time}'' & > /dev/null 2>&1";
            shell_exec($cmd);
            $this->Session->write('m', $this->Class4Log->create_json(201, __('Success!', true)));
            $this->redirect('/backend_job/trigger');
        }
    }
    
    public function low_balance_alert()
    {
        $this->autoRender = false;
        $this->autoLayout = false;
        $script_path = Configure::read('script.path');
        $script_file = $script_path . DS . 'class4_low_balance_alert.pl';
        $script_conf = Configure::read('script.conf');
        $cmd = "{$script_file} -c {$script_conf} & > /dev/null 2>&1";
        shell_exec($cmd);
        $this->Session->write('m', $this->Class4Log->create_json(201, __('Success!', true)));
        $this->redirect('/backend_job/trigger');
    }
    
    
    public function real_cdr()
    {
        $this->autoRender = false;
        $this->autoLayout = false;
        $script_path = Configure::read('script.path');
        $script_file = $script_path . DS . 'class4_real_cdr.pl';
        $script_conf = Configure::read('script.conf');
        $cmd = "{$script_file} -c {$script_conf} & > /dev/null 2>&1";
        shell_exec($cmd);
        $this->Session->write('m', $this->Class4Log->create_json(201, __('Success!', true)));
        $this->redirect('/backend_job/trigger');
    }
    
    

    public function trigger()
    {
        
        $jobs = array(
            1 => array(
                'name' => 'create cdr/report table',
                'count' => 0,
                'url' => 'create_cdr_report_table',
                'executable' => true,
            ),
            array(
                'name' => 'update db record',
                'count' => 0,
                'url' => 'update_db_record',
                'executable' => true,
            ),
            array(
                'name' => 'upload check',
                'count' => 0,
                'url' => '',
                'executable' => false,
            ),
            array(
                'name' => 'alert route',
                'count' => 0,
                'url' => 'alert_route',
                'executable' => true,
            ),
            array(
                'name' => 'cdr report',
                'count' => 0,
                'url' => 'cdr_report',
                'executable' => true,
            ),
            array(
                'name' => 'create invoice',
                'count' => 0,
                'url' => '',
                'executable' => false,
            ),
            array(
                'name' => 'qos report',
                'count' => 0,
                'url' => 'create_cdr_report_table',
                'executable' => false,
            ),
            array(
                'name' => 'ftp cdr',
                'count' => 0,
                'url' => 'ftp_cdr',
                'executable' => true,
            ),
            array(
                'name' => 'rerate cdr',
                'count' => 0,
                'url' => 'create_cdr_report_table',
                'executable' => false,
            ),
            array(
                'name' => 'cdr import',
                'count' => 0,
                'url' => 'cdr_import',
                'executable' => true,
            ),
            array(
                'name' => 'dns dig',
                'count' => 0,
                'url' => 'dns_dig',
                'executable' => true,
            ),
            array(
                'name' => 'finance transaction',
                'count' => 0,
                'url' => 'finance_transaction',
                'executable' => true,
            ),
            array(
                'name' => 'summary report',
                'count' => 0,
                'url' => '',
                'executable' => false,
            ),
            array(
                'name' => 'low balance alert',
                'count' => 0,
                'url' => 'low_balance_alert',
                'executable' => true,
            ),
            array(
                'name' => 'real cdr',
                'count' => 0,
                'url' => 'real_cdr',
                'executable' => true,
            ),
            array(
                'name' => 'cdr download',
                'count' => 0,
                'url' => '',
                'executable' => false,
            )
        );
        
        
        foreach($jobs as $key => $job)
        {
            $sql = "select run_pid from class4_log where run_type = {$key} and run_pid > 0";
            $result = $this->Class4Log->query($sql);
            foreach($result as $item)
            {
                $pid = $item[0]['run_pid'];
                if (is_dir("/proc/{$pid}"))
                {
                    $jobs[$key]['count'] += 1;
                }
            }
        }
        
        $this->set('jobs', $jobs);
        
        /*
        foreach($jobs as $job_name => $job_file)
        {
            $job_file = $script_path . DS . $job_file;
            $cmd = "ps -eo stat,cmd | grep {$job_file}";
            $result = shell_exec($cmd);
            $line_count = explode("\n", $result, 3);
        }
        */
    }
    
    public function logging()
    {
    	$this->pageTitle = "Configuration/Log";
    	$this->paginate = array(
    			'limit' => 100,
    			'order' => array(
    					'id' => 'desc',
    			),
    	);
    	
    	if(isset($_GET['job_name']) && !empty($_GET['job_name']))
    	{
    		$this->paginate['conditions'][] = "run_type = {$_GET['job_name']}";
    	}
    	
    	if(isset($_GET['start_time']) && !empty($_GET['start_time']))
    	{
    		$this->paginate['conditions'][] = "start_time >= '{$_GET['start_time']}'";
    	}
    	
    	if(isset($_GET['start_time']) && !empty($_GET['end_time']))
    	{
    		$this->paginate['conditions'][] = "end_time <= '{$_GET['end_time']}'";
    	}
    	
    	$job_names = array(
    		1 => 'create cdr/report table',
    		2 => 'update db record',
    		3 => 'upload check',
    		4 => 'alert route',
    		5 => 'cdr report',
    		6 => 'create invoice',
    		7 => 'qos report',
    		8 => 'ftp cdr',
    		9 => 'rerate cdr',
    		10 => 'cdr import',
    		11 => 'dns dig',
                12 => 'finance transaction',
                13 => 'summary report',
                14 => 'low balance alert',
                15 => 'real cdr',
                16 => 'cdr download'
    	);
    	$this->set('jobs_names', $job_names);
    	$this->data = $this->paginate('Class4Log');
    }
    
    public function schedule()
    {
    	$this->pageTitle = "Configuration/Schedule";
        
    	App::import("Vendor","crontab_manager",array('file'=>"net" . DS . "crontab_manager.php"));
    	$crontab = new CrontabManager(Configure::read('ssh.host'), Configure::read('ssh.port'), 
                Configure::read('ssh.username'), Configure::read('ssh.pubkeyfile'), Configure::read('ssh.privkeyfile'));
    	
    	if ($this->RequestHandler->ispost())
    	{
    		/*
                $new_cronjobs = array(
                                '0 0 1 * * home/path/to/command/the_command.sh',
                                '30 8 * * 6 home/path/to/command/the_command.sh >/dev/null 2>&1'
                );
                $crontab->append_cronjob($new_cronjobs);
                */
    		$new_cronjobs = array();
    		$len = count($_POST['minutes']);
    		for ($i = 0; $i < $len; $i++)
    		{
    			$cronjob = "{$_POST['minutes'][$i]} {$_POST['hours'][$i]} {$_POST['days'][$i]} {$_POST['months'][$i]} {$_POST['weeks'][$i]} {$_POST['commands'][$i]}";
    			array_push($new_cronjobs, $cronjob);
    		}
    		$crontab->append_cronjob($new_cronjobs);
    		
    		$this->Session->write('m', $this->Class4Log->create_json(201, __('Success!', true)));
    		$this->redirect('/backend_job/schedule');
    	}
		else
		{
			$crontab->write_to_file();
                        
                        $file = $crontab->get_file();
			
			$handle = fopen($file, 'r');
			
			//$handle = fopen('/home/hewenxiang/htdocs/Class4/app/vendors/net/crontab.txt', 'r');
			
			$crontabs = array();
			
			while ($line = fgets($handle, 1024))
			{
				$line = trim($line);
				if (!empty($line))
				{
					$line_split = explode(' ', $line);
					$execution_param = array();
					$len = count($line_split);
					for ($i = 5; $i < $len; $i++)
					{
						array_push($execution_param, $line_split[$i]);
						unset($line_split[$i]);
					}
					$line_split[5] = implode(' ', $execution_param);
					array_push($crontabs, $line_split);
					unset($line_split);
				}
			}
			
			fclose($handle);
			$this->set('data', $crontabs);
		}
    }

}

?>
