<?php

class TaskSchedulerController extends AppController
{
    var $name = "TaskScheduler";
    var $uses = array('TaskScheduler');
    
    public function beforeFilter() 
    {
        parent::beforeFilter();
        $this->checkSession("login_type"); 
    }
    
    public function index()
    {    
        $taskSchedulers = $this->TaskScheduler->find('all', array(
            'order' => array('TaskScheduler.id'),            
        ));
        
        $days = array(
            'monday',
            'tuesday',
            'wednesday',
            'thursday',
            'friday',
            'saturday',
            'sunday',
        );
        
        foreach ($taskSchedulers as &$taskScheduler)
        {
            $times_arr = array();
            
            if ($taskScheduler['TaskScheduler']['minute_type'] == 0)
            {
                array_push($times_arr, "every {$taskScheduler['TaskScheduler']['minute']} minute(s)");
            }
            else
            {
                array_push($times_arr, "{$taskScheduler['TaskScheduler']['minute']} minute(s)");
            }
            
            if ($taskScheduler['TaskScheduler']['hour_type'] == 0)
            {
                array_push($times_arr, "every {$taskScheduler['TaskScheduler']['hour']} hour(s)");
            }
            else
            {
                array_push($times_arr, "{$taskScheduler['TaskScheduler']['hour']} hour(s)");
            }
            
            if ($taskScheduler['TaskScheduler']['day_type'] == 0)
            {
                array_push($times_arr, "every {$taskScheduler['TaskScheduler']['hour']} day(s)");
            }
            else
            {
                array_push($times_arr, "{$taskScheduler['TaskScheduler']['day']} day(s)");
            }
            
            if ($taskScheduler['TaskScheduler']['week'] != NULL)
            {
                array_push($times_arr, $days[$taskScheduler['TaskScheduler']['week']]);
            }
            
            $taskScheduler['TaskScheduler']['run_at'] = implode(', ', $times_arr);
        }
        
        $this->set('taskSchedulers', $taskSchedulers);
    }
    
    public function edit($task_id)
    {
        Configure::write('debug', 0);
        $this->autoLayout = false;
        $taskScheduler = $this->TaskScheduler->findById($task_id);
        if ($this->RequestHandler->isPost()) {
            $this->data['TaskScheduler']['id'] = $task_id;
            $this->TaskScheduler->save($this->data);
            $this->refresh();
            $this->TaskScheduler->create_json_array("", 201, 'The Task Scheduler [' . $taskScheduler['TaskScheduler']['name'] . '] is modified successfully !');
            $this->xredirect(array('controller' => 'task_scheduler', 'action' => 'index')); 
        }
        $this->set('taskScheduler', $taskScheduler);
    }
    
    public function change_status($task_id)
    {
        Configure::write('debug', 0);
        $this->autoLayout = false;
        $this->autoRender = false;
        $taskScheduler = $this->TaskScheduler->findById($task_id);
        
        if ($taskScheduler['TaskScheduler']['active']) {
            $taskScheduler['TaskScheduler']['active'] = false;
            $this->TaskScheduler->create_json_array("", 201, 'The Task Scheduler [' . $taskScheduler['TaskScheduler']['name'] . '] is inactived successfully !');
        } else {
            $taskScheduler['TaskScheduler']['active'] = true;
            $this->TaskScheduler->create_json_array("", 201, 'The Task Scheduler [' . $taskScheduler['TaskScheduler']['name'] . '] is actived successfully !');
        }     
        
        $this->TaskScheduler->save($taskScheduler);
        
        $this->refresh();
        $this->xredirect(array('controller' => 'task_scheduler', 'action' => 'index')); 
    }
    
    public function run($task_id)
    {
        $this->autoLayout = false;
        $this->autoRender = false;
        $taskScheduler = $this->TaskScheduler->findById($task_id);
        $script_path = Configure::read('script.path');
        $script_conf = Configure::read('script.conf');
        $cmd = "perl {$script_path}/{$taskScheduler['TaskScheduler']['script_name']} -c {$script_conf} -a > /dev/null 2 >&1 &";
        shell_exec($cmd);
        $this->TaskScheduler->create_json_array("", 201, 'The Task Scheduler [' . $taskScheduler['TaskScheduler']['name'] . '] is run successfully !');
        $this->xredirect(array('controller' => 'task_scheduler', 'action' => 'index')); 
    }
    
    public function refresh()
    {
        //Configure::write('debug', 0);
        $this->autoLayout = false;
        $this->autoRender = false;
        
        $taskSchedulers = $this->TaskScheduler->find('all', array(
            'order' => array('TaskScheduler.id'),         
            'conditions' => array('TaskScheduler.active' => true),
        ));
        
        $script_path = Configure::read('script.path');
        $script_conf = Configure::read('script.conf');
        
        App::import('Vendor', 'crontab');
        
        $crontab = new Crontab();
        $crontab->on('* * * * *');
        
        foreach($taskSchedulers as $taskScheduler)
        {
            $timeCode = array();
            if ($taskScheduler['TaskScheduler']['minute_type'] == 0)
                if (empty($taskScheduler['TaskScheduler']['minute']) || $taskScheduler['TaskScheduler']['minute'] == '*')
                     array_push($timeCode, "*");
                else
                     array_push($timeCode, "*/{$taskScheduler['TaskScheduler']['minute']}");
            else
                array_push($timeCode, "{$taskScheduler['TaskScheduler']['minute']}");
            
            if ($taskScheduler['TaskScheduler']['hour_type'] == 0)
                if (empty($taskScheduler['TaskScheduler']['hour']) || $taskScheduler['TaskScheduler']['hour'] == '*')
                     array_push($timeCode, "*");
                else
                     array_push($timeCode, "*/{$taskScheduler['TaskScheduler']['hour']}");
            else
                array_push($timeCode, "{$taskScheduler['TaskScheduler']['hour']}");
                
            if ($taskScheduler['TaskScheduler']['day_type'] == 0)
                if (empty($taskScheduler['TaskScheduler']['day']) || $taskScheduler['TaskScheduler']['day'] == '*')
                     array_push($timeCode, "*");
                else
                     array_push($timeCode, "*/{$taskScheduler['TaskScheduler']['day']}");
            else
                array_push($timeCode, "{$taskScheduler['TaskScheduler']['day']}");
                
            array_push($timeCode, "*");
            if (empty($taskScheduler['TaskScheduler']['week']) || $taskScheduler['TaskScheduler']['week'] == '*')
                array_push($timeCode, "*");
            else
                array_push($timeCode, "{$taskScheduler['TaskScheduler']['week']}");
                
            $cmd = "perl {$script_path}/{$taskScheduler['TaskScheduler']['script_name']} -c {$script_conf} -a";
            $timeCode = implode(" ", $timeCode);
            
            $crontab->on($timeCode)->doJob($cmd);
            
        }
        
        $crontab->activate(false);
        
        
    }
    
}