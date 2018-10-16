<?php

class AlertsController extends AppController
{

    var $name = 'Alerts';
    //var $helpers = array('javascript','html','AppAlerts');
    var $components = array('RequestHandler');
    var $uses = array("Condition", "Action", "AlertRule", "AlertReport", 'BlockAni', 'ResourceBlock', 'TroubleTicketsTemplate', 'BlockTicket');

    function index()
    {
        $this->redirect('condition');
    }

    public function beforeFilter()
    {
        $this->checkSession("login_type"); //核查用户身份
        parent::beforeFilter();
    }
    
    public function rule_trigger($id)
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $script_path = Configure::read('script.path');
        $script_conf = Configure::read('script.conf');
        $script_name = $script_path . DS . "exchange_alert_route.pl";
        $cmd = "{$script_name} -c {$script_conf} -r {$id} > /dev/null 2>&1 &";
        shell_exec($cmd);
        $alert_rule = $this->AlertRule->FindById($id);
        $this->AlertRule->create_json_array('', 201, 'The Rule [' . $alert_rule['AlertRule']['name'] . '] is triggered successfully.');
        $this->Session->write('m', AlertRule::set_validator());
        $this->xredirect("/alerts/rule");
    }
    
    public function put_into_exclude_anis($id)
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $block_ani = $this->BlockAni->findById($id);
        $ani = $block_ani['BlockAni']['ani'];
        $action_id = $block_ani['BlockAni']['action_id'];
        
        $sql = "select exclude_ani from alert_action where id = {$action_id}";
        $result = $this->Action->query($sql);
        $excluded_anis = $result[0][0]['exclude_ani'];
        $excluded_anis = explode(',', $excluded_anis);
        array_push($excluded_anis, $ani);
        
        $excluded_anis = implode(',', $excluded_anis);
        $sql = "update alert_action set exclude_ani = '{$excluded_anis}' where id = {$action_id}";
        $this->Action->query($sql);
        
        $this->BlockAni->del($id);
        
        $this->BlockAni->create_json_array('', 201, 'The Block ANI [' . $block_ani['BlockAni']['ani'] . '] is excluded successfully.');
        $this->Session->write('m', BlockAni::set_validator());
        $this->xredirect("/alerts/block_ani");
    }
    
    public function change_exclude_ani()
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $id = $_POST['id'];
        $anis = $_POST['anis'];
        $anis_arr = explode("\n", $anis);
        foreach($anis_arr as $key => $anis_item)
        {
            if (!trim($anis_item))
                unset ($anis_arr[$key]);
        }
        $anis = implode(',', $anis_arr);
        $sql = "update alert_action set exclude_ani = '{$anis}' where id = {$id}";
        $this->Action->query($sql);
        echo 1;
    }
    
    public function get_exclude_anis()
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $id = $_POST['id'];
        $sql = "select exclude_ani from alert_action where id = {$id}";
        $result = $this->Action->query($sql);
        $excluded_anis = $result[0][0]['exclude_ani'];
        $excluded_anis = str_replace(',', "\n", $excluded_anis);
        echo json_encode(array(
            'data' => $excluded_anis,
        ));
    }
    
    public function trouble_tickets()
    {
        $this->pageTitle = "Trouble Tickets";
        
        $conditions = array();
        
        if (isset($_GET['search_type']))
        {
            $search_type = (int)$_GET['search_type'];
            if ($search_type == 0)
            {
                $conditions['code_name ILIKE'] = '%' . $_GET['search'] . '%';
            }
            elseif ($search_type == 1)
            {
                $conditions['rule_name ILIKE'] = '%' . $_GET['search'] . '%';
            }
            
            
        }
        if (isset($_GET['ingress_trunk']) && $_GET['ingress_trunk'] != 0)
                $conditions['ingress'] = $_GET['ingress_trunk'] ;
        if (isset($_GET['egress_trunk']) && $_GET['egress_trunk'] != 0)
            $conditions['egress'] = $_GET['egress_trunk'] ;
        if (isset($_GET['code']) && $_GET['code'])
            $conditions['dnis'] = $_GET['code'] ;
        
        $this->paginate = array(
            'fields' => array('BlockTicket.ingress', 'BlockTicket.egress', 'MAX("BlockTicket"."unblock_time") as "BlockTicket__unblock_time"',
                              'MAX("BlockTicket"."blocked_time") as "BlockTicket__blocked_time"', 
                               'BlockTicket.code_name', 'BlockTicket.rule_name', 'BlockTicket.block', 'max("BlockTicket"."start_time") as "BlockTicket__start_time"', 'max("BlockTicket"."end_time") as "BlockTicket__end_time"', 'sum("BlockTicket"."calls") as "BlockTicket__calls"', 'sum("BlockTicket"."not_zero_calls") as "BlockTicket__not_zero_calls"'),
            'limit' => 100,
            'order' => array(
                //'id' => 'desc',
            ),
            'group' => array('BlockTicket.code_name', 'BlockTicket.ingress', 'BlockTicket.egress', 'BlockTicket.rule_name', 'BlockTicket.block'),
            'conditions' => $conditions,
        );
        $this->data = $this->paginate('BlockTicket');
        $this->set('resources', $this->BlockAni->get_resources());
        $this->set('ingresses', $this->BlockAni->get_resource_by_type('ingress'));
        $this->set('egresses', $this->BlockAni->get_resource_by_type('egress'));
    }
    
    
    public function trouble_tickets_template()
    {
        $this->pageTitle = "Trouble Tickets/Mail Templates";
        $this->paginate = array(
            'limit' => 100,
            'order' => array(
                'id' => 'desc',
            ),
        );
        $this->data = $this->paginate('TroubleTicketsTemplate');
    }
    
    public function trouble_tickets_template_create()
    {
        $this->pageTitle = "Trouble Tickets/Mail Templates";
        if ($this->RequestHandler->isPost())
        {
            $name  = $_POST['name'];
            $title = $_POST['title'];
            $content = $_POST['content'];
            $updated_by = $_SESSION['sst_user_name'];
            $data = array(
                'TroubleTicketsTemplate' => array (
                    'name' => $name,
                    'title' => $title,
                    'content' => $content,
                    'updated_by' => $updated_by,
                ),
            );
            $this->TroubleTicketsTemplate->save($data);
            $this->TroubleTicketsTemplate->create_json_array('', 201, 'The Email Template [' . $name . '] is created successfully!');
            $this->Session->write('m', TroubleTicketsTemplate::set_validator());
            $this->redirect('/alerts/trouble_tickets_template');
        }
    }
    
    public function trouble_tickets_template_edit($id)
    {
        $this->pageTitle = "Trouble Tickets/Mail Templates";
        if ($this->RequestHandler->isPost())
        {
            $name  = $_POST['name'];
            $title = $_POST['title'];
            $content = $_POST['content'];
            $updated_at = date("Y-m-d H:i:sO");
            $updated_by = $_SESSION['sst_user_name'];
            $data = array(
                'TroubleTicketsTemplate' => array (
                    'name' => $name,
                    'title' => $title,
                    'content' => $content,
                    'updated_by' => $updated_by,
                    'updated_at' => $updated_at,
                    'id'         => $id,
                ),
            );
            $this->TroubleTicketsTemplate->save($data);
            $this->TroubleTicketsTemplate->create_json_array('', 201, 'The Email Template [' . $name . '] is modified successfully!');
            $this->Session->write('m', TroubleTicketsTemplate::set_validator());
            $this->redirect('/alerts/trouble_tickets_template');
        }
        $template = $this->TroubleTicketsTemplate->findById($id);
        $this->set('template', $template);
    }
    
    public function trouble_tickets_template_delete($id)
    {
        $this->autoRender = false;
        $this->autoLayout = false;
        $template = $this->TroubleTicketsTemplate->findById($id);
        $this->TroubleTicketsTemplate->create_json_array('', 201, 'The Email Template [' . $template['TroubleTicketsTemplate']['name'] . '] is deleted successfully!');
        $this->TroubleTicketsTemplate->del($id);
    }
    
    
    public function block_ani()
    {
        $this->pageTitle = "Monitoring/Block ANI";
        
        $conditions = array();
        
        if (isset($_GET['search']) && $_GET['search'] != 'Search')
            $conditions['ani ILIKE'] = '%' .  $_GET['search'] . '%';
        
        if (isset($_GET['ingress_trunk']) && $_GET['ingress_trunk'] != 0)
                $conditions['ingress'] = $_GET['ingress_trunk'] ;
        if (isset($_GET['egress_trunk']) && $_GET['egress_trunk'] != 0)
            $conditions['egress'] = $_GET['egress_trunk'] ;
        
        $this->paginate = array(
            'limit' => 100,
            'order' => array(
                'id' => 'desc',
            ),
            'conditions' => $conditions,
        );
        $this->data = $this->paginate('BlockAni');
        
       
        
        foreach ($this->data as &$item)
        {
            $item = array_merge($item, $this->AlertRule->findById($item['BlockAni']['rule_id']));
        }
        
        
        $this->set('resources', $this->BlockAni->get_resources());
        $this->set('ingresses', $this->BlockAni->get_resource_by_type('ingress'));
        $this->set('egresses', $this->BlockAni->get_resource_by_type('egress'));
    }
    
    public function block_ani_delete($id)
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $id   = (int)$id;
        $block_ani = $this->BlockAni->findById($id);
        $condition = array();
        if (!empty($block_ani['BlockAni']['ingress']))
        {
                $condition['ingress_res_id'] = $block_ani['BlockAni']['ingress'];
                $condition['ingress_client_id'] = $this->BlockAni->get_client_id($block_ani['BlockAni']['ingress']);
        }
        if (!empty($block_ani['BlockAni']['egress']))
        {
                $condition['ingress_res_id'] = $block_ani['BlockAni']['egress'];
                $condition['ingress_client_id'] = $this->BlockAni->get_client_id($block_ani['BlockAni']['egress']);
        }
        if (!empty($block_ani['BlockAni']['ani']))
                $condition['ani_prefix'] = $block_ani['BlockAni']['ani'];
        $this->ResourceBlock->deleteAll($condition);
        $this->BlockAni->del($id);
        $this->BlockAni->create_json_array('', 201, 'The Block ANI [' . $block_ani['BlockAni']['ani'] . '] is deleted successfully.');
        $this->Session->write('m', BlockAni::set_validator());
        $this->xredirect("/alerts/block_ani");
    }
    
    public function trouble_tickets_delete($id)
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        //$id   = (int)$id;
        $block_anis = $this->BlockTicket->findAllByCodeName($id);
        $condition = array();
        foreach($block_anis as $block_ani)
        {
            if (!empty($block_ani['BlockTicket']['ingress']))
            {
                    $condition['ingress_res_id'] = $block_ani['BlockTicket']['ingress'];
                    $condition['ingress_client_id'] = $this->BlockAni->get_client_id($block_ani['BlockTicket']['ingress']);
            }
            if (!empty($block_ani['BlockTicket']['egress']))
            {
                    $condition['ingress_res_id'] = $block_ani['BlockTicket']['egress'];
                    $condition['ingress_client_id'] = $this->BlockAni->get_client_id($block_ani['BlockTicket']['egress']);
            }
            if (!empty($block_ani['BlockTicket']['dnis']))
                    $condition['digit'] = $block_ani['BlockTicket']['dnis'];
            $this->ResourceBlock->deleteAll($condition);
            $this->BlockTicket->del($block_ani['BlockTicket']['id']);
        }
        $this->BlockTicket->create_json_array('', 201, 'The Trouble Ticket Code Name [' . $block_anis . '] is deleted successfully.');
        $this->Session->write('m', BlockTicket::set_validator());
        $this->xredirect("/alerts/trouble_tickets");
    }
    
    public function block_ani_delete_selected()
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $ids = $_POST['ids'];
        $type = (int)$type;
        foreach ($ids as $id)
        {
            $block_ani = $this->BlockAni->findById($id);
            $condition = array();
            if (!empty($block_ani['BlockAni']['ingress']))
            {
                    $condition['ingress_res_id'] = $block_ani['BlockAni']['ingress'];
                    $condition['ingress_client_id'] = $this->BlockAni->get_client_id($block_ani['BlockAni']['ingress']);
            }
            if (!empty($block_ani['BlockAni']['egress']))
            {
                    $condition['ingress_res_id'] = $block_ani['BlockAni']['egress'];
                    $condition['ingress_client_id'] = $this->BlockAni->get_client_id($block_ani['BlockAni']['egress']);
            }
            if (!empty($block_ani['BlockAni']['ani']))
                    $condition['ani_prefix'] = $block_ani['BlockAni']['ani'];
            $this->ResourceBlock->deleteAll($condition);
            $this->BlockAni->del($id);
        }
    }
    
    public function block_ani_delete_all()
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        /*
        $block_anis = $this->BlockAni->find('all');
        foreach($block_anis as $block_ani)
        {
            $condition = array();
            if (!empty($block_ani['BlockAni']['ingress']))
            {
                    $condition['ingress_res_id'] = $block_ani['BlockAni']['ingress'];
                    $condition['ingress_client_id'] = $this->BlockAni->get_client_id($block_ani['BlockAni']['ingress']);
            }
            if (!empty($block_ani['BlockAni']['egress']))
            {
                    $condition['ingress_res_id'] = $block_ani['BlockAni']['egress'];
                    $condition['ingress_client_id'] = $this->BlockAni->get_client_id($block_ani['BlockAni']['egress']);
            }
            if (!empty($block_ani['BlockAni']['ani']))
                    $condition['ani_prefix'] = $block_ani['BlockAni']['ani'];
            $this->ResourceBlock->deleteAll($condition);
            $this->BlockAni->del($block_ani['BlockAni']['id']);
        }
         * 
         */
        $this->ResourceBlock->query("DELETE FROM resource_block WHERE block_log_id IS NOT NULL");
        $this->BlockAni->query("DELETE FROM block_ani");
        $this->BlockAni->create_json_array('', 201, __('All block are deleted successfully', true));
        $this->Session->write('m', BlockAni::set_validator());
        $this->xredirect("/alerts/block_ani");
    }
    
    public function trouble_tickets_delete_selected()
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $ids = $_POST['ids'];
        $type = (int)$type;
        foreach ($ids as $id)
        {
            $block_anis = $this->BlockTicket->findAllById($id);
            $condition = array();
            foreach($block_anis as $block_ani)
            {
                if (!empty($block_ani['BlockTicket']['ingress']))
                {
                        $condition['ingress_res_id'] = $block_ani['BlockTicket']['ingress'];
                        $condition['ingress_client_id'] = $this->BlockAni->get_client_id($block_ani['BlockTicket']['ingress']);
                }
                if (!empty($block_ani['BlockTicket']['egress']))
                {
                        $condition['ingress_res_id'] = $block_ani['BlockTicket']['egress'];
                        $condition['ingress_client_id'] = $this->BlockAni->get_client_id($block_ani['BlockTicket']['egress']);
                }
                if (!empty($block_ani['BlockTicket']['dnis']))
                        $condition['digit'] = $block_ani['BlockTicket']['dnis'];
                $this->ResourceBlock->deleteAll($condition);
                $this->BlockTicket->del($block_ani['BlockTicket']['id']);
            }
        }
    }
    
    public function trouble_tickets_delete_all()
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $block_anis = $this->BlockTicket->find('all');
        foreach($block_anis as $block_ani)
        {
            $condition = array();
            if (!empty($block_ani['BlockTicket']['ingress']))
            {
                    $condition['ingress_res_id'] = $block_ani['BlockTicket']['ingress'];
                    $condition['ingress_client_id'] = $this->BlockAni->get_client_id($block_ani['BlockTicket']['ingress']);
            }
            if (!empty($block_ani['BlockTicket']['egress']))
            {
                    $condition['ingress_res_id'] = $block_ani['BlockTicket']['egress'];
                    $condition['ingress_client_id'] = $this->BlockAni->get_client_id($block_ani['BlockTicket']['egress']);
            }
            if (!empty($block_ani['BlockTicket']['dnis']))
                    $condition['digit'] = $block_ani['BlockTicket']['dnis'];
            $this->ResourceBlock->deleteAll($condition);
            $this->BlockTicket->del($block_ani['BlockTicket']['id']);
        }
        $this->BlockTicket->create_json_array('', 201, __('All trouble tickets are deleted successfully', true));
        $this->Session->write('m', BlockTicket::set_validator());
        $this->xredirect("/alerts/trouble_tickets");
    }
    
    public function block_ani_change($id, $type)
    {
        //Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $type = (int)$type;
        $id   = (int)$id;
        
        $block_ani = $this->BlockAni->findById($id);
        $condition = array();
        if (!empty($block_ani['BlockAni']['ingress']))
        {
                $condition['ingress_res_id'] = $block_ani['BlockAni']['ingress'];
                $condition['ingress_client_id'] = $this->BlockAni->get_client_id($block_ani['BlockAni']['ingress']);
        }
        if (!empty($block_ani['BlockAni']['egress']))
        {
                $condition['ingress_res_id'] = $block_ani['BlockAni']['egress'];
                $condition['ingress_client_id'] = $this->BlockAni->get_client_id($block_ani['BlockAni']['egress']);
        }
        if (!empty($block_ani['BlockAni']['ani']))
                $condition['ani_prefix'] = $block_ani['BlockAni']['ani'];
        
        if ($type === 1)
        {
            $this->ResourceBlock->deleteAll($condition);
            $block_ani['BlockAni']['block'] = false;
            $block_ani['BlockAni']['unblock_time'] = 'now()';
            $this->BlockAni->create_json_array('', 201, __('UnBlocked successfully', true));
        } 
        else if ($type === 2)
        {
            $new_block = array(
                'ResourceBlock' => $condition,
            );
            $this->ResourceBlock->save($new_block);
            $block_ani['BlockAni']['block'] = true;
            $block_ani['BlockAni']['unblock_time'] = null;
            $this->BlockAni->create_json_array('', 201, __('Blocked successfully', true));
        }
        $this->BlockAni->save($block_ani);
        $this->Session->write('m', BlockAni::set_validator());
        $this->xredirect("/alerts/block_ani");
    }
    
    
    public function trouble_block_ani_change($id, $type)
    {
        //Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $type = (int)$type;
        //$id   = (int)$id;
        
        $block_anis = $this->BlockTicket->findAllByCodeName($id);
        foreach($block_anis as $block_ani)
        {
            $condition = array();
            if (!empty($block_ani['BlockTicket']['ingress']))
            {
                    $condition['ingress_res_id'] = $block_ani['BlockTicket']['ingress'];
                    $condition['ingress_client_id'] = $this->BlockAni->get_client_id($block_ani['BlockTicket']['ingress']);
            }
            if (!empty($block_ani['BlockTicket']['egress']))
            {
                    $condition['ingress_res_id'] = $block_ani['BlockTicket']['egress'];
                    $condition['ingress_client_id'] = $this->BlockAni->get_client_id($block_ani['BlockTicket']['egress']);
            }
            if (!empty($block_ani['BlockTicket']['dnis']))
                    $condition['digit'] = $block_ani['BlockTicket']['dnis'];


            if ($type === 1)
            {
                $this->ResourceBlock->deleteAll($condition);
                $block_ani['BlockTicket']['block'] = false;
                $block_ani['BlockTicket']['unblock_time'] = 'now()';
            } 
            else if ($type === 2)
            {
                $new_block = array(
                    'ResourceBlock' => $condition,
                );
                $this->ResourceBlock->save($new_block);
                $block_ani['BlockTicket']['block'] = true;
                $block_ani['BlockTicket']['unblock_time'] = null;
            }
            $this->BlockTicket->save($block_ani);
            $this->Session->write('m', BlockTicket::set_validator());
            
            
        }
        if ($type == 1)
            $this->BlockTicket->create_json_array('', 201, __('Unblocked successfully', true));
        else
            $this->BlockTicket->create_json_array('', 201, __('Blocked successfully', true));
        $this->xredirect("/alerts/trouble_tickets");
    }
    
    public function block_unblock_all($type = 1)
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $type = (int)$type;
        if ($type == 1)
        {
            $condition_d = array(
                'block' => true,
            );
            $this->BlockAni->create_json_array('', 201, __('Unblocked successfully', true));
            $sql = "delete from resource_block where log_id is not null";
            //$this->ResourceBlock->deleteAll(
            //    array('ResourceBlock.block_log_id' => 'IS NOT NULL')
            //);
            $this->ResourceBlock->query($sql);
            $sql = "update block_ani set block = false ,unblock_time = CURRENT_TIMESTAMP(0)";
            //$this->BlockAni->updateAll(
            //     array('BlockAni.block' => FALSE)
            //);
            $this->BlockAni->query($sql);
            $this->Session->write('m', BlockAni::set_validator());
            $this->redirect('/alerts/block_ani');
            return;
        }
        else
        {
            $condition_d = array(
                'block' => false,
            );
                $this->BlockAni->create_json_array('', 201, __('Blocked successfully', true));
        }
        $block_anis = $this->BlockAni->find('all', array('conditions' => $condition_d));
        foreach($block_anis as $block_ani)
        {
            $condition = array();
            if (!empty($block_ani['BlockAni']['ingress']))
            {
                    $condition['ingress_res_id'] = $block_ani['BlockAni']['ingress'];
                    $condition['ingress_client_id'] = $this->BlockAni->get_client_id($block_ani['BlockAni']['ingress']);
            }
            if (!empty($block_ani['BlockAni']['egress']))
            {
                    $condition['ingress_res_id'] = $block_ani['BlockAni']['egress'];
                    $condition['ingress_client_id'] = $this->BlockAni->get_client_id($block_ani['BlockAni']['egress']);
            }
            if (!empty($block_ani['BlockAni']['ani']))
                    $condition['ani_prefix'] = $block_ani['BlockAni']['ani'];
            
            if ($type === 1)
            {
                $this->ResourceBlock->deleteAll($condition);
                $block_ani['BlockAni']['block'] = false;
            } 
            else if ($type === 2)
            {
                $new_block = array(
                    'ResourceBlock' => $condition,
                );
                $this->ResourceBlock->save($new_block);
                $block_ani['BlockAni']['block'] = true;
            }
            $this->BlockAni->save($block_ani);
        }
        $this->Session->write('m', BlockAni::set_validator());
        $this->redirect('/alerts/block_ani');
    }
    
    public function trouble_block_unblock_all($type = 1)
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $type = (int)$type;
        if ($type == 1)
        {
            $condition_d = array(
                'block' => true,
            );
                $this->BlockTicket->create_json_array('', 201, __('Unblocked successfully', true));
        }
        else
        {
            $condition_d = array(
                'block' => false,
            );
                $this->BlockTicket->create_json_array('', 201, __('Blocked successfully', true));
        }
        $block_anis = $this->BlockTicket->find('all', array('conditions' => $condition_d));
        foreach($block_anis as $block_ani)
        {
            $condition = array();
            if (!empty($block_ani['BlockTicket']['ingress']))
            {
                    $condition['ingress_res_id'] = $block_ani['BlockTicket']['ingress'];
                    $condition['ingress_client_id'] = $this->BlockAni->get_client_id($block_ani['BlockTicket']['ingress']);
            }
            if (!empty($block_ani['BlockTicket']['egress']))
            {
                    $condition['ingress_res_id'] = $block_ani['BlockTicket']['egress'];
                    $condition['ingress_client_id'] = $this->BlockAni->get_client_id($block_ani['BlockTicket']['egress']);
            }
            if (!empty($block_ani['BlockTicket']['dnis']))
                    $condition['digit'] = $block_ani['BlockTicket']['dnis'];
            
            if ($type === 1)
            {
                $this->ResourceBlock->deleteAll($condition);
                $block_ani['BlockTicket']['block'] = false;
            } 
            else if ($type === 2)
            {
                $new_block = array(
                    'ResourceBlock' => $condition,
                );
                $this->ResourceBlock->save($new_block);
                $block_ani['BlockTicket']['block'] = true;
            }
            $this->BlockTicket->save($block_ani);
        }
        $this->Session->write('m', BlockTicket::set_validator());
        $this->redirect('/alerts/trouble_tickets');
    }
    
    public function block_unblock_selected($type = 1)
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $ids = $_POST['ids'];
        $type = (int)$type;
        foreach ($ids as $id)
        {
            $id   = (int)$id;
            $block_ani = $this->BlockAni->findById($id);
            $condition = array();
            if (!empty($block_ani['BlockAni']['ingress']))
            {
                    $condition['ingress_res_id'] = $block_ani['BlockAni']['ingress'];
                    $condition['ingress_client_id'] = $this->BlockAni->get_client_id($block_ani['BlockAni']['ingress']);
            }
            if (!empty($block_ani['BlockAni']['egress']))
            {
                    $condition['ingress_res_id'] = $block_ani['BlockAni']['egress'];
                    $condition['ingress_client_id'] = $this->BlockAni->get_client_id($block_ani['BlockAni']['egress']);
            }
            if (!empty($block_ani['BlockAni']['ani']))
                    $condition['ani_prefix'] = $block_ani['BlockAni']['ani'];

            if ($type === 1)
            {
                if ($block_ani['BlockAni']['block'] == false)
                    continue;
                $this->ResourceBlock->deleteAll($condition);
                $block_ani['BlockAni']['block'] = false;
                $block_ani['BlockAni']['unblock_time'] = date("Y-m-d H:i:s");
                $this->BlockAni->create_json_array('', 201, __('Unblocked successfully', true));
            } 
            else if ($type === 2)
            {
                if ($block_ani['BlockAni']['block'] == true)
                    continue;
                $new_block = array(
                    'ResourceBlock' => $condition,
                );
                $this->ResourceBlock->save($new_block);
                $block_ani['BlockAni']['block'] = true;
                $this->BlockAni->create_json_array('', 201, __('Blocked successfully', true));
            }
            $this->BlockAni->save($block_ani);
        }
    }
    
    public function trouble_block_unblock_selected($type = 1)
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $ids = $_POST['ids'];
        $type = (int)$type;
        foreach ($ids as $id)
        {
            $id   = (int)$id;
            $block_anis = $this->BlockTicket->findAllByCodeName($id);
            $condition = array();
            foreach($block_anis as $block_ani)
            {
                if (!empty($block_ani['BlockTicket']['ingress']))
                {
                        $condition['ingress_res_id'] = $block_ani['BlockTicket']['ingress'];
                        $condition['ingress_client_id'] = $this->BlockAni->get_client_id($block_ani['BlockTicket']['ingress']);
                }
                if (!empty($block_ani['BlockTicket']['egress']))
                {
                        $condition['ingress_res_id'] = $block_ani['BlockTicket']['egress'];
                        $condition['ingress_client_id'] = $this->BlockAni->get_client_id($block_ani['BlockTicket']['egress']);
                }
                if (!empty($block_ani['BlockTicket']['dnis']))
                        $condition['digit'] = $block_ani['BlockTicket']['dnis'];

                if ($type === 1)
                {
                    if ($block_ani['BlockTicket']['block'] == false)
                        continue;
                    $this->ResourceBlock->deleteAll($condition);
                    $block_ani['BlockTicket']['block'] = false;
                } 
                else if ($type === 2)
                {
                    if ($block_ani['BlockTicket']['block'] == true)
                        continue;
                    $new_block = array(
                        'ResourceBlock' => $condition,
                    );
                    $this->ResourceBlock->save($new_block);
                    $block_ani['BlockTicket']['block'] = true;
                }
            }
            if ($type == 1)
            {
                    $this->BlockTicket->create_json_array('', 201, __('Unblocked successfully', true));
            }
            else if ($type == 2)
            {
                    $this->BlockTicket->create_json_array('', 201, __('Blocked successfully', true));
            }
            $this->BlockTicket->save($block_ani);
        }
    }

    private function _getResourceHostArr()
    {
        $return[0] = 'ALL';
        $sql = "select resource_ip_id as id, ip from resource_ip";
        $results = $this->Action->query($sql);
        foreach ($results as $k => $v)
        {
            $return[$v[0]['id']] = $v[0]['ip'];
        }
        return $return;
    }

    private function _getResourcePortArr()
    {
        $return[0] = 'All';
        $sql = "select resource_ip_id as id ,port from resource_ip";
        $port_list = $this->Action->query($sql);
        foreach ($port_list as $port_value)
        {
            $return[$port_value[0]['id']] = $port_value[0]['port'];
        }
        return $return;
    }

    private function _getResourceHostPortArr()
    {
        $return[0] = 'ALL';
        $sql = "select resource_ip_id as id, ip, port from resource_ip";
        $results = $this->Action->query($sql);
        foreach ($results as $k => $v)
        {
            $return[$v[0]['id']] = $v[0]['ip'] . ":" . $v[0]['port'];
        }
        return $return;
    }

    private function _getResourceNameArr($type = 'all')
    {
        $return = array();
        switch ($type)
        {
            case 'ingress':
            case 'egress':
                $sql = "select resource_id as id, alias from resource where " . addslashes(trim($type)) . " = true ORDER by alias ASC"; // and disable_by_alert = false";
                break;
            case 'all':
            default:
                $sql = "select resource_id as id, alias from resource ORDER by alias ASC";
        }
        $results = $this->Action->query($sql);
        $return[0] = 'All';
        foreach ($results as $k => $v)
        {
            $return[$v[0]['id']] = $v[0]['alias'];
        }
        return $return;
    }

    private function _getRouteNameArr()
    {
        $return = array();
        $sql = "select route_strategy_id as id, name from route_strategy";
        $results = $this->Action->query($sql);
        foreach ($results as $k => $v)
        {
            $return[$v[0]['id']] = $v[0]['name'];
        }
        return $return;
    }

    private function _getProductNameArr()
    {
        $return = array();
        $sql = "select product_id as id, name from product";
        $results = $this->Action->query($sql);
        foreach ($results as $k => $v)
        {
            $return[$v[0]['id']] = $v[0]['name'];
        }
        return $return;
    }

    private function _getResourceRouteInfoArr()
    {
        $sql = "select * from route";
        $results = $this->Action->query($sql);
        foreach ($results as $k => $v)
        {
            $return[$v[0]['route_strategy_id']] = $v[0];
        }
        return $return;
    }

    //-----------------------alert condition 
    public function condition_used($condition_id)
    {
        Configure::write('debug', 0);
        $this->autoLayout = false;
        $this->autoRender = false;
        $sql = "SELECT name FROM alert_rule WHERE alert_condition_id = {$condition_id}";
        $result = $this->Action->query($sql);
        $arr = array();
        foreach ($result as $item)
            array_push($arr, $item[0][name]);
        echo json_encode($arr);
    }

    public function action_used($action_id)
    {
        Configure::write('debug', 0);
        $this->autoLayout = false;
        $this->autoRender = false;
        $sql = "SELECT name FROM alert_rule WHERE alert_action_id = {$action_id}";
        $result = $this->Action->query($sql);
        $arr = array();
        foreach ($result as $item)
            array_push($arr, $item[0][name]);
        echo json_encode($arr);
    }

    public function condition()
    {
        $currPage = 1;
        $pageSize = 10;
        $search_arr = array();
        if (!empty($_REQUEST['searchkey']))   //模糊查询
        {
            $search_type = 0;
            $search_arr['name'] = !empty($_REQUEST['searchkey']) ? $_REQUEST['searchkey'] : '';
            $search_arr['acd'] = (!empty($_REQUEST['searchkey']) && is_numeric($_REQUEST['searchkey'])) ? $_REQUEST['searchkey'] : 0;
            $search_arr['asr'] = (!empty($_REQUEST['searchkey']) && is_numeric($_REQUEST['searchkey'])) ? $_REQUEST['searchkey'] / 100 : 0;
            $search_arr['margin'] = (!empty($_REQUEST['searchkey']) && is_numeric($_REQUEST['searchkey'])) ? $_REQUEST['searchkey'] / 100 : 0;
        } else                      //按条件搜索
        {
            $search_type = 1;
            $search_arr['name'] = !empty($_REQUEST['name']) ? $_REQUEST['name'] : '';
            $search_arr['acd'] = (!empty($_REQUEST['acd']) && is_numeric($_REQUEST['acd'])) ? $_REQUEST['acd'] : 0;
            $search_arr['asr'] = (!empty($_REQUEST['asr']) && is_numeric($_REQUEST['asr'])) ? $_REQUEST['asr'] / 100 : 0;
            $search_arr['margin'] = (!empty($_REQUEST['margin']) && is_numeric($_REQUEST['margin'])) ? $_REQUEST['margin'] / 100 : 0;
        }

//			if (! empty ( $_REQUEST ['page'] )) {
//				$currPage = $_REQUEST ['page'];
//			}
//			
//			if (! empty ( $_REQUEST ['size'] )) {
//				$pageSize = $_REQUEST ['size'];
//			}
        $temp = isset($_SESSION['paging_row']) ? $_SESSION['paging_row'] : 10;

        empty($_GET['page']) ? $currPage = 1 : $currPage = $_GET['page'];
        empty($_GET['size']) ? $pageSize = $temp : $pageSize = $_GET['size'];

        $_SESSION['paging_row'] = $pageSize;

        $results = $this->Condition->ListCondition($currPage, $pageSize, $search_arr, $search_type);
        $this->set('p', $results);
    }

    public function add_condition($id = null)
    {
        if (!$_SESSION['role_menu']['Monitoring']['alerts:condition']['model_w'])
        {
            $this->redirect_denied();
        }
        $this->pageTitle = "Add/Action Condition";
        $id = empty($this->params['pass'][0]) ? null : $this->params['pass'][0];
        $this->_catch_exception_msg(array($this, '_add_condition_impl'), array('id' => $id));
        $this->_render_condition_save_options();
        $this->render('add_condition');
        $this->Session->write('m', Condition::set_validator());
    }

    public function del_condititon($dele_id = null, $fist_id = null)
    {
        if (!$_SESSION['role_menu']['Monitoring']['alerts:condition']['model_w'])
        {
            $this->redirect_denied();
        }
        //删除
        if (!empty($dele_id))
        {
            $del_sql = "delete  from alert_condition where   alert_condition.id=$dele_id";
            $this->Condition->query($del_sql);
            $this->Condition->create_json_array('', 201, 'Succeeded');
        }
        $this->Session->write('m', Condition::set_validator());
        $this->referer("/alerts/condition/$fist_id");
    }

//J删除
    public function ex_dele_condititon($dele_id = null)
    {
        if (!$_SESSION['role_menu']['Monitoring']['alerts:condition']['model_w'])
        {
            $this->redirect_denied();
        }
        if (!empty($dele_id))
        {
            $delete_sql = "delete from alert_condition where id =$dele_id";
            $this->Condition->query($delete_sql);
            $this->Condition->create_json_array('', 201, 'Deleted successfully');
        }
        $this->xredirect("/alerts/condition");
    }

    public function delete_alert_action($alert_action_id = null)
    {
        Configure::write('debug', 0);
        $this->autoRender = false; 
        $this->autoLayout = false;
        if (!$_SESSION['role_menu']['Monitoring']['alerts:action']['model_w'])
        {
            $this->redirect_denied();
        }
        if (!empty($alert_action_id))
        {
            $dele_sql = "delete from alert_action where id=$alert_action_id";
            $this->Condition->query($dele_sql);
            $this->Condition->create_json_array('', 201, 'Deleted successfully');
        }
        $this->xredirect("/alerts/action");
    }

    public function delete_alert_rule($alert_rule_id = null)
    {
        Configure::write('debug', 0);
        $this->autoRender = false; 
        $this->autoLayout = false;
        if (!$_SESSION['role_menu']['Monitoring']['alerts:rule']['model_w'])
        {
            $this->redirect_denied();
        }
        if (!empty($alert_rule_id))
        {
            $sql = "select name from alert_rule where id = {$alert_rule_id}";
            $result = $this->Condition->query($sql);
            $sql = "delete from alert_rule where id =$alert_rule_id";
            $this->Condition->query($sql);
            $this->Condition->create_json_array('', 201, 'The Rule[' .$result[0][0]['name']. '] is deleted successfully.');
        }
    }

    function edit_condititon($id = null)
    {
        //EDIT
        if (!$_SESSION['role_menu']['Monitoring']['alerts:condition']['model_w'])
        {
            $this->redirect_denied();
        }
    }

    function _add_condition_impl($params = array())
    {
        #post

        if ($this->RequestHandler->isPost())
        {

            //var_dump($this->params['form']);exit;
            $this->_create_or_update_condition_data($this->params['form']);
        }
        #get
        else
        {
            if (isset($params['id']) && !empty($params['id']))
            {
                //$this->data = $this->Condition->query("select * from alert_condition where id = {$params['id']}");
                $this->data = $this->Condition->find("first", Array('conditions' => array('Condition.id' => $params['id'])));
                if (empty($this->data))
                {
                    throw new Exception("Permission denied");
                } else
                {
                    $this->set('p', $this->data['Condition']);
                }
            } else
            {
                
            }
        }
    }

    function _create_or_update_condition_data($params = array())
    {   #update
        if (isset($params['condition_id']) && !empty($params['condition_id']))
        {
            $id = (int) $params ['condition_id'];
//							if(!$this->check_form($id)){
//								return;
//							}			
            $this->data['Condition'] = $this->data['Alert'];
            $this->data ['Condition'] ['id'] = $id;
            $this->data['Alert'] = null;
            unset($this->data['Alert']);
            if ($this->data['Condition']['acd_comparator'] == 1)
            {
                $this->data['Condition']['acd_value_min'] = $params['acd_min_1'];
                $this->data['Condition']['acd_value_max'] = $params['acd_max_1'];
            } else
            {
                $this->data['Condition']['acd_value_min'] = $params['acd_min_0'];
            }
            if ($this->data['Condition']['asr_comparator'] == 1)
            {
                $this->data['Condition']['asr_value_min'] = $params['asr_min_1'] / 100;
                $this->data['Condition']['asr_value_max'] = $params['asr_max_1'] / 100;
            } else
            {
                $this->data['Condition']['asr_value_min'] = $params['asr_min_0'] / 100;
            }
            if ($this->data['Condition']['margin_comparator'] == 1)
            {
                $this->data['Condition']['margin_value_min'] = $params['margin_min_1'] / 100;
                $this->data['Condition']['margin_value_max'] = $params['margin_max_1'] / 100;
            } else
            {
                $this->data['Condition']['margin_value_min'] = $params['margin_min_0'] / 100;
            }
            
            if ($this->data['Condition']['abr_comparator'] == 1)
            {
                $this->data['Condition']['abr_value_min'] = $params['abr_min_1'] / 100;
                $this->data['Condition']['abr_value_max'] = $params['abr_max_1'] / 100;
            } else
            {
                $this->data['Condition']['abr_value_min'] = $params['abr_min_0'] / 100;
            }
            
            if ($this->data['Condition']['special_ani_comparator'] == 1)
            {
                $this->data['Condition']['special_ani_value'] = $params['special_ani_value_1'];
            } else
            {
                $this->data['Condition']['special_ani_value'] = $params['special_ani_value_0'];
            }
            $this->data['Condition']['update_by'] = $_SESSION['sst_user_name'];
            $this->data['Condition']['update_at'] = date('Y-m-d H:i:sO');
            if ($this->Condition->save($this->data))
            {
                //$this->Condition->create_json_array('',201,'Condition,Edit successfully');
                $this->Condition->create_json_array('', 201, 'The Condition [' . $this->data['Condition']['name'] . '] is modified successfully.');
                $this->Session->write('m', Condition::set_validator());

                $this->xredirect("/alerts/condition");
                //$this->redirect ( array ('id' => $id ) );
            }
        }
        # add
        else
        {
//						if(!$this->check_form('')){
//								return;
//							}								
            $this->data['Condition'] = $this->data['Alert'];
            $this->data['Alert'] = null;
            unset($this->data['Alert']);
            if ($this->data['Condition']['acd_comparator'] == 1)
            {
                $this->data['Condition']['acd_value_min'] = $params['acd_min_1'];
                $this->data['Condition']['acd_value_max'] = $params['acd_max_1'];
            } else
            {
                $this->data['Condition']['acd_value_min'] = $params['acd_min_0'];
            }
            if ($this->data['Condition']['asr_comparator'] == 1)
            {
                $this->data['Condition']['asr_value_min'] = $params['asr_min_1'] / 100;
                $this->data['Condition']['asr_value_max'] = $params['asr_max_1'] / 100;
            } else
            {
                $this->data['Condition']['asr_value_min'] = $params['asr_min_0'] / 100;
            }
            if ($this->data['Condition']['margin_comparator'] == 1)
            {
                $this->data['Condition']['margin_value_min'] = $params['margin_min_1'] / 100;
                $this->data['Condition']['margin_value_max'] = $params['margin_max_1'] / 100;
            } else
            {
                $this->data['Condition']['margin_value_min'] = $params['margin_min_0'] / 100;
            }
            $this->data['Condition']['update_by'] = $_SESSION['sst_user_name'];
            $this->data['Condition']['update_at'] = date('Y-m-d H:i:sO');
            if ($this->Condition->save($this->data))
            {
                $id = $this->Condition->getlastinsertId();
                if (isset($_GET['flag']))
                {
                    $this->xredirect("/alerts/add_rule");
                }
                $this->Condition->create_json_array('', 201, 'Condition，
create successfully');
                $this->Session->write('m', Condition::set_validator());
                $this->xredirect('/alerts/condition');
                //		$this->redirect ( array ('id' => $id ) );
            }
        }
    }

    function _render_condition_save_options()
    {
        $this->loadModel('Condition');
        $this->set('ConditionList', $this->Condition->find('all')); //,Array('fields'=>Array('id','name'))));
    }

//--------------------------------alert condition end
//--------------------------------alert action start
    public function action()
    {
        $currPage = 1;
        $pageSize = 10;
        $search_arr = array();
        if (!empty($_REQUEST['searchkey']))   //模糊查询
        {
            $search_type = 0;
            $search_arr['name'] = !empty($_REQUEST['searchkey']) ? $_REQUEST['searchkey'] : '';
        } else                      //按条件搜索
        {
            $search_type = 1;
            $search_arr['name'] = !empty($_REQUEST['name']) ? $_REQUEST['name'] : '';
        }

//			if (! empty ( $_REQUEST ['page'] )) {
//				$currPage = $_REQUEST ['page'];
//			}
//			
//			if (! empty ( $_REQUEST ['size'] )) {
//				$pageSize = $_REQUEST ['size'];
//			}

        $temp = isset($_SESSION['paging_row']) ? $_SESSION['paging_row'] : 10;

        empty($_GET['page']) ? $currPage = 1 : $currPage = $_GET['page'];
        empty($_GET['size']) ? $pageSize = $temp : $pageSize = $_GET['size'];

        $_SESSION['paging_row'] = $pageSize;
        $results = $this->Action->ListAction($currPage, $pageSize, $search_arr, $search_type);
        $this->set('p', $results);

        $send_mail_type = array(
            'None', 'System\'s NOC', 'Partner\'s NOC', 'Both NOC'
        );

        $disable_route_target = array(
            'None', 'Entire Trunk', 'Entire Host'
        );

        $change_prioprity = array(
            'None', 'Trunk', "Host"
        );
        $templates = $this->TroubleTicketsTemplate->find('list');
        $templates = array(null => '') + $templates;
        $this->set('templates', $templates);

        $this->set('send_mail_type', $send_mail_type);
        $this->set('disable_route_target', $disable_route_target);
        $this->set('change_prioprity', $change_prioprity);
    }

    public function add_action($id = null)
    {
        if (!$_SESSION['role_menu']['Monitoring']['alerts:action']['model_w'])
        {
            $this->redirect_denied();
        }
        $this->pageTitle = "Add/Edit Action";
        $id = empty($this->params['pass'][0]) ? null : $this->params['pass'][0];
        $this->_catch_exception_msg(array($this, '_add_action_impl'), array('id' => $id));
        $this->_render_action_save_options();
        $this->render('add_action');
        $this->Session->write('m', Action::set_validator());
    }

    function _add_action_impl($params = array())
    {
        #post

        if ($this->RequestHandler->isPost())
        {
            $this->_create_or_update_action_data($this->params['form']);
        }
        #get
        else
        {
            if (isset($params['id']) && !empty($params['id']))
            {
                $this->data = $this->Action->find("first", Array('conditions' => array('Action.id' => $params['id'])));
                if (empty($this->data))
                {
                    throw new Exception("Permission denied");
                } else
                {
                    $this->set('p', $this->data['Action']); //pr($this->data['Action']);
                }
            } else
            {
                
            }
        }
    }

    function _create_or_update_action_data($params = array())
    {   #update
        if (isset($params['action_id']) && !empty($params['action_id']))
        {

            $id = (int) $params ['action_id'];
//							if(!$this->check_form($id)){
//								return;
//							}			
            $this->data['Action'] = $this->data['Alert'];
            $this->data ['Action'] ['id'] = $id;
            $this->data['Alert'] = null;
            unset($this->data['Alert']);

            if (!preg_match("/^[0-9]+$/", $this->data['Action']['disable_duration']))
            {
                $this->Action->create_json_array('#AlertDisableDuration', 101, 'disable duration must be integer.');
                $this->Session->write('m', Action::set_validator());
                $this->redirect(array('id' => $id));
            }
            if (!preg_match("/^[0-9]+$/", $this->data['Action']['pri_chg_duration']))
            {
                $this->Action->create_json_array('#AlertPriChgDuration', 101, 'pri_chg duration must be integer.');
                $this->Session->write('m', Action::set_validator());
                $this->redirect(array('id' => $id));
            }
            if (!preg_match("/^[0-9]+$/", $this->data['Action']['code_trunk_disable_duration']))
            {
                $this->Action->create_json_array('#AlertCodeTrunkDisableDuration', 101, 'Code Trunk Disable Duration must be integer.');
                $this->Session->write('m', Action::set_validator());
                $this->redirect(array('id' => $id));
            }
            if ($this->data['Action']['email_notification'] == 0)
            {
                $this->data['Action']['email_to_noc'] = TRUE;
                $this->data['Action']['email_to_carrier'] = FALSE;
            } elseif ($this->data['Action']['email_notification'] == 1)
            {
                $this->data['Action']['email_to_carrier'] = TRUE;
                $this->data['Action']['email_to_noc'] = FALSE;
            } elseif ($this->data['Action']['email_notification'] == 2)
            {
                $this->data['Action']['email_to_carrier'] = TRUE;
                $this->data['Action']['email_to_noc'] = TRUE;
            }
            $this->data['Action']['update_by'] = $_SESSION['sst_user_name'];
            $this->data['Action']['update_at'] = date("Y-m-d H:i:sO");
            if ($this->Action->save($this->data))
            {
                //$this->Action->create_json_array('',201,'Action,Edit successfully');
                $this->Action->create_json_array('', 201, 'The Condition [' . $this->data['Action']['name'] . '] is modified successfully.');
                $this->Session->write('m', Action::set_validator());
                $this->xredirect('/alerts/action');
                //	$this->redirect ( array ('id' => $id ) );
            }
        }
        # add
        else
        {
//						if(!$this->check_form('')){
//								return;
//							}								
            $this->data['Action'] = $this->data['Alert'];
            $this->data['Alert'] = null;
            unset($this->data['Alert']);
            if ($this->data['Action']['email_notification'] == 0)
            {
                $this->data['Action']['email_to_noc'] = TRUE;
                $this->data['Action']['email_to_carrier'] = FALSE;
            } elseif ($this->data['Action']['email_notification'] == 1)
            {
                $this->data['Action']['email_to_carrier'] = TRUE;
                $this->data['Action']['email_to_noc'] = FALSE;
            } elseif ($this->data['Action']['email_notification'] == 2)
            {
                $this->data['Action']['email_to_carrier'] = TRUE;
                $this->data['Action']['email_to_noc'] = TRUE;
            }
            $this->data['Action']['update_by'] = $_SESSION['sst_user_name'];
            $this->data['Action']['update_at'] = date("Y-m-d H:i:sO");
            if ($this->Action->save($this->data))
            {
                $id = $this->Action->getlastinsertId();
                if (isset($_GET['flag']))
                    $this->xredirect('/alerts/add_rule');
                $this->Action->create_json_array('', 201, 'The ' . $this->data['Action']['name'] . ' is added successfully.');
                $this->Session->write('m', Action::set_validator());

                $this->xredirect('/alerts/action');
                //$this->redirect ( array ('id' => $id ) );
            }
        }
    }

    function _render_action_save_options()
    {
        $this->loadModel('Action');
        $this->set('ActionList', $this->Condition->find('all')); //,Array('fields'=>Array('id','name'))));
    }

    //-------------------------------alert action end
    //--------------------------------alert rule start
    public function rule()
    {
        $currPage = 1;
        $pageSize = 10;
        $search_arr = array();
        if (!empty($_REQUEST['searchkey']))   //模糊查询
        {
            $search_type = 0;
            $search_arr['name'] = !empty($_REQUEST['searchkey']) ? $_REQUEST['searchkey'] : '';
        } else                      //按条件搜索
        {
            $search_type = 1;
            $search_arr['name'] = !empty($_REQUEST['name']) ? $_REQUEST['name'] : '';
        }
//			
//			if (! empty ( $_REQUEST ['page'] )) {
//				$currPage = $_REQUEST ['page'];
//			}
//			
//			if (! empty ( $_REQUEST ['size'] )) {
//				$pageSize = $_REQUEST ['size'];
//			}

        $temp = isset($_SESSION['paging_row']) ? $_SESSION['paging_row'] : 10;

        empty($_GET['page']) ? $currPage = 1 : $currPage = $_GET['page'];
        empty($_GET['size']) ? $pageSize = $temp : $pageSize = $_GET['size'];

        $_SESSION['paging_row'] = $pageSize;
        $results = $this->AlertRule->ListRule($currPage, $pageSize, $search_arr, $search_type);
        $this->set('p', $results);
        $name_join_arr['action'] = $this->Action->getActionNameArr();
        $name_join_arr['condition'] = $this->Condition->getConditionNameArr();
        $name_join_arr['resource'] = $this->_getResourceNameArr();
        $name_join_arr['host'] = $this->_getResourceHostArr();
        $name_join_arr['port'] = $this->_getResourcePortArr();
        $this->set('name_join_arr', $name_join_arr);
    }

    public function add_rule($id = null)
    {
        if (!$_SESSION['role_menu']['Monitoring']['alerts:rule']['model_w'])
        {
            $this->redirect_denied();
        }
        //print_r($_POST);exit;
        $this->pageTitle = "Add/Edit Rule";
        $id = empty($this->params['pass'][0]) ? null : $this->params['pass'][0];
        $this->_catch_exception_msg(array($this, '_add_rule_impl'), array('id' => $id));
        $this->_render_rule_save_options();
        $this->render('add_rule');
        $this->Session->write('m', AlertRule::set_validator());
    }

    public function get_ingress_prefix()
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $res_id = $_POST['res_id'];
        $sql = "SELECT id, tech_prefix FROM resource_prefix WHERE resource_id = {$res_id}";
        $data = $this->AlertRule->query($sql);
        echo json_encode($data);
    }

    public function get_code_name_by_prefix()
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $prefix_id = $_POST['prefix_id'];
        $sql = "SELECT distinct code_name FROM rate WHERE rate_table_id = (select rate_table_id from resource_prefix where id = {$prefix_id})";
        $data = $this->AlertRule->query($sql);
        echo json_encode($data);
    }

    
    
    function _add_rule_impl($params = array())
    {
        #post

        if ($this->RequestHandler->isPost())
        {
            $post = array();
            parse_str(file_get_contents("php://input"), $post);
            $this->data = $post['data'];
            
            $this->_create_or_update_rule_data($this->params['form']);
        }
        #get
        else
        {
            if (isset($params['id']) && !empty($params['id']))
            {
                $this->data = $this->AlertRule->find("first", Array('conditions' => array('AlertRule.id' => $params['id'])));
                if (empty($this->data))
                {
                    throw new Exception("Permission denied");
                } else
                {
                    $this->set('pp', $this->data['AlertRule']); //pr($this->data['Action']);									
                }
            } else
            {
                
            }
            $name_join_arr['action'] = $this->Action->getActionNameArr();
            $name_join_arr['condition'] = $this->Condition->getConditionNameArr();
            $name_join_arr['resource'] = $this->_getResourceNameArr();
            $name_join_arr['resource_ingress'] = $this->_getResourceNameArr('ingress');
            $name_join_arr['resource_egress'] = $this->_getResourceNameArr('egress');
            $name_join_arr['resource_all'] = $this->_getResourceNameArr('all');
            //$name_join_arr['host'] = $this->_getResourceHostArr();
            //$name_join_arr['port']=$this->_getResourcePortArr();
            $name_join_arr['host_port'] = $this->_getResourceHostPortArr();
            $this->set('name_join_arr', $name_join_arr);
        }
    }
    
    
    

    function _create_or_update_rule_data($params = array())
    {   #update
        if (isset($params['action_id']) && !empty($params['action_id']))
        {
//							}		
            $id = (int) $params ['action_id'];
//							if(!$this->check_form($id)){
//								return;
//							}			
            $this->data['AlertRule'] = $this->data['Alert'];
            if (empty($this->data['Alert']['switch_ip']))
            {

                unset($this->data['Alert']['switch_ip']);
                unset($this->data['AlertRule']['switch_ip']);
            }
            $this->data ['AlertRule'] ['id'] = $id;
            //echo $this->data['AlertRule']['is_origin'];exit;                                                     
            //$this->data['AlertRule']['is_origin'] = $this->data['AlertRule']['is_origin'] == '0' ? true : false;
            if ($this->data['AlertRule']['is_origin'] === '1')
            {
                $this->data['AlertRule']['res_id'] = $this->data['AlertRule']['res_id'];
            } elseif ($this->data['AlertRule']['is_origin'] === '0')
            {
                $this->data['AlertRule']['res_id'] = $this->data['AlertRule']['res_id_1'];
            } elseif ($this->data['AlertRule']['is_origin'] == '2')
            {
                $this->data['AlertRule']['res_id'] = $this->data['AlertRule']['res_id_2'];
            }
            if ($this->data['AlertRule']['freq_type'] == 1)
            {
                $this->data['AlertRule']['freq_value'] = $this->data['AlertRule']['freq_value_0'];
            } elseif ($this->data['AlertRule']['freq_type'] == 2)
            {
                $weeks = implode(',', $this->data['AlertRule']['week']);
                $time = implode(',', $this->data['AlertRule']['time']);
                $this->data['AlertRule']['weekday_time'] = $weeks . '!' . $time;
            }
            $this->data['AlertRule']['update_by'] = $_SESSION['sst_user_name'];
            $this->data['AlertRule']['update_at'] = date("Y-m-d H:i:sO");
            $this->data['AlertRule']['destination_code_name'] = implode(',', $this->data['Alert']['destination_code_name']);
            $this->data['AlertRule']['mail_duration'] = empty($this->data['Alert']['mail_duration'])? NULL : $this->data['Alert']['mail_duration'];
            $this->data['Alert'] = null;
            unset($this->data['Alert']);
            if ($this->AlertRule->save($this->data))
            {
                //pr('add');                                                                
                //$this->AlertRule->create_json_array('',201,'Rule , Edit successfullyfully');
                $this->AlertRule->create_json_array('', 201, 'The Condition [' . $this->data['AlertRule']['name'] . '] is modified successfully.');
                $this->xredirect('/alerts/rule');
                //	$this->redirect ( array ('id' => $id ) );
            }
        }
        # add
        else
        {
//						if(!$this->check_form('')){
//								return;
            $this->data['AlertRule'] = $this->data['Alert'];



            if (empty($this->data['Alert']['switch_ip']))
            {

                unset($this->data['Alert']['switch_ip']);
                unset($this->data['AlertRule']['switch_ip']);
            }

            //$this->data['AlertRule']['monitor_type'] = $_POST['monitor_type'];


            if ($this->data['AlertRule']['is_origin'] === '1')
            {
                $this->data['AlertRule']['res_id'] = $this->data['AlertRule']['res_id'];
            } elseif ($this->data['AlertRule']['is_origin'] === '0')
            {
                $this->data['AlertRule']['res_id'] = $this->data['AlertRule']['res_id_1'];
            } elseif ($this->data['AlertRule']['is_origin'] == '2')
            {
                $this->data['AlertRule']['res_id'] = $this->data['AlertRule']['res_id_2'];
            }
            if ($this->data['AlertRule']['freq_type'] == 1)
            {
                $this->data['AlertRule']['freq_value'] = $this->data['AlertRule']['freq_value_0'];
            } elseif ($this->data['AlertRule']['freq_type'] == 2)
            {
                $weeks = implode(',', $this->data['AlertRule']['week']);
                $time = implode(',', $this->data['AlertRule']['time']);
                $this->data['AlertRule']['weekday_time'] = $weeks . '!' . $time;
            }
            $this->data['AlertRule']['update_by'] = $_SESSION['sst_user_name'];
            $this->data['AlertRule']['update_at'] = date("Y-m-d H:i:sO");
            $this->data['AlertRule']['destination_code_name'] = implode(',', $this->data['Alert']['destination_code_name']);
            $this->data['AlertRule']['mail_duration'] = empty($this->data['Alert']['mail_duration'])? NULL : $this->data['Alert']['mail_duration'];
            $this->data['Alert'] = null;
            unset($this->data['Alert']);
            

            if ($this->AlertRule->save($this->data))
            {
                $id = $this->AlertRule->getlastinsertId();
                $this->AlertRule->create_json_array('', 201, 'The ' . $this->data['AlertRule']['name'] . ' is created successfully.');

                $this->xredirect('/alerts/rule');
                //	$this->redirect ( array ('id' => $id ) );
            }
        }
    }

    function _render_rule_save_options()
    {
        $this->loadModel('AlertRule');
        $option['joins'] = array(
            array(
                'table' => 'resource_prefix',
                'alias' => 'Prefix',
                'type' => 'LEFT',
                'conditions' => array(
                    'Prefix.resource_id = AlertRule.ingress_trunk_prefix'
                )
            )
        );
        $data = $this->AlertRule->find('all');
        $this->set('RuleList', $data); //,Array('fields'=>Array('id','name'))));

        $sql = "select distinct name from code where 
code_deck_id  = (select code_deck_id from code_deck where client_id = 0)
ORDER BY name";
        $codenames = $this->AlertRule->query($sql);

        $this->set('codenames', $codenames);
    }

    function find_host()
    {
        Configure::write('debug', 0);
        $type = isset($_REQUEST['type']) ? $_REQUEST['type'] : 0;
        $res_id = intval($_REQUEST['res_id']);

        if (!empty($res_id))
        {
            $this->set('host', $this->AlertRule->query("select resource_ip_id,ip,port from resource_ip where resource_id=" . intval($res_id)));
        }
    }

    //-------------------------------alert rule end
    //--------------------------------alert report start
    public function report()
    {
        $this->pageTitle = "Disable Trunk Report";
        $event_type = empty($this->params['pass'][0]) ? 1 : $this->params['pass'][0];

        if($event_type == 1)
        {
            $header = 'Disabled Ingress Trunk';
        }
        else
        {
            $header = 'Disabled Egress Trunk';
        }
        
        $this->set('header', $header);
        
        $currPage = 1;
        $pageSize = 10;
        $search_arr = array();
        if (!empty($_REQUEST['searchkey']))   //模糊查询
        {
            $search_type = 0;
            $search_arr['name'] = !empty($_REQUEST['searchkey']) ? $_REQUEST['searchkey'] : '';
        } else                      //按条件搜索
        {
            $search_type = 1;
            $search_arr['name'] = !empty($_REQUEST['name']) ? $_REQUEST['name'] : '';
        }

//			if (! empty ( $_REQUEST ['page'] )) {
//				$currPage = $_REQUEST ['page'];
//			}
//			
//			if (! empty ( $_REQUEST ['size'] )) {
//				$pageSize = $_REQUEST ['size'];
//			}
        $temp = isset($_SESSION['paging_row']) ? $_SESSION['paging_row'] : 10;

        empty($_GET['page']) ? $currPage = 1 : $currPage = $_GET['page'];
        empty($_GET['size']) ? $pageSize = $temp : $pageSize = $_GET['size'];

        $_SESSION['paging_row'] = $pageSize;
        $results = array();
        if ($event_type == 1)
        {
            $res_type = empty($this->params['url']['res_type']) ? 1 : $this->params['url']['res_type'];
            $results = $this->AlertReport->disable_trunk_report($currPage, $pageSize, $search_arr, $search_type, $event_type, $res_type);
        }
        $this->set('p', $results);
        $name_join_arr['action'] = $this->Action->getActionNameArr();
        $name_join_arr['action_info'] = $this->Action->getActionInfoArr();
        $name_join_arr['condition'] = $this->Condition->getConditionNameArr();
        $name_join_arr['rule'] = $this->AlertRule->getRuleNameArr();
        $name_join_arr['resource'] = $this->_getResourceNameArr();
        $name_join_arr['host'] = $this->_getResourceHostArr();
        //$name_join_arr['route'] = $this->_getResourceRouteInfoArr();
        $this->set('name_join_arr', $name_join_arr);
    }

    public function problem_report()
    {
        $this->pageTitle = "Problem Trunk Report";
        $event_type = 0;
        $res_type = empty($this->params['pass'][0]) ? 1 : $this->params['pass'][0];

        if($res_type == 1)
        {
            $header = 'Problem Ingress Trunk';
        }
        else
        {
            $header = 'Problem Egress Trunk';
        }
        $this->set('header', $header);
        
        $currPage = 1;
        $pageSize = 10;
        $search_arr = array();
        if (!empty($_REQUEST['searchkey']))   //模糊查询
        {
            $search_type = 0;
            $search_arr['name'] = !empty($_REQUEST['searchkey']) ? $_REQUEST['searchkey'] : '';
        } else                      //按条件搜索
        {
            $search_type = 1;
            $search_arr['name'] = !empty($_REQUEST['name']) ? $_REQUEST['name'] : '';
        }

        $search_where = "";

        if (isset($_GET['adv_search']))
        {
            if (!empty($_GET['s_rule']))
                $search_where .= " and alert_event.alert_rule_id = {$_GET['s_rule']}";
            if (!empty($_GET['s_client']))
                $search_where .= " and resource.client_id = {$_GET['s_client']}";
            if (!empty($_GET['s_trunk']))
                $search_where .= " and alert_event.res_id = {$_GET['s_trunk']}";
            if (!empty($_GET['start_date']))
                $search_where .= " and event_time >= '{$_GET['start_date']}'";
            if (!empty($_GET['end_date']))
                $search_where .= " and event_time < '{$_GET['end_date']}'";
            if ($_GET['isDelete'] == 1)
            {
                $sql = "DELETE FROM alert_event WHERE alert_event.id in (SELECT 

alert_event.id

FROM alert_event JOIN alert_rule 

ON alert_event.alert_rule_id = alert_rule.id JOIN resource 

ON alert_event.res_id = resource.resource_id 

WHERE resource.ingress = true {$search_where})";
                $this->AlertReport->query($sql);
                $this->AlertReport->create_json_array('', 201, 'Succeeded');
            }
        }


        $temp = isset($_SESSION['paging_row']) ? $_SESSION['paging_row'] : 10;

        empty($_GET['page']) ? $currPage = 1 : $currPage = $_GET['page'];
        empty($_GET['size']) ? $pageSize = $temp : $pageSize = $_GET['size'];
        $_SESSION['paging_row'] = $pageSize;
        $results = $this->AlertReport->disable_trunk_report($currPage, $pageSize, $search_arr, $search_type, $event_type, $res_type, $search_where);
        $this->set('p', $results);
        $name_join_arr['action'] = $this->Action->getActionNameArr();
        $name_join_arr['client'] = $this->Action->getClientNameArr();
        $name_join_arr['action_info'] = $this->Action->getActionInfoArr();
        $name_join_arr['condition'] = $this->Condition->getConditionNameArr();
        $name_join_arr['rule'] = $this->AlertRule->getRuleNameArr();
        $name_join_arr['resource'] = $this->_getResourceNameArr();
        $name_join_arr['host'] = $this->_getResourceHostArr();
        //$name_join_arr['route'] = $this->_getResourceRouteInfoArr();
        $this->set('name_join_arr', $name_join_arr);
    }

    public function priority_report()
    {
        $this->pageTitle = "Priority Report";
        $event_type = empty($this->params['pass'][0]) ? 7 : $this->params['pass'][0];
            $header = 'Priority Trunk';
        $this->set('header', $header);
        $currPage = 1;
        $pageSize = 10;
        $search_arr = array();
        if (!empty($_REQUEST['searchkey']))   //模糊查询
        {
            $search_type = 0;
            $search_arr['name'] = !empty($_REQUEST['searchkey']) ? $_REQUEST['searchkey'] : '';
        } else                      //按条件搜索
        {
            $search_type = 1;
            $search_arr['name'] = !empty($_REQUEST['name']) ? $_REQUEST['name'] : '';
        }

//			if (! empty ( $_REQUEST ['page'] )) {
//				$currPage = $_REQUEST ['page'];
//			}
//			
//			if (! empty ( $_REQUEST ['size'] )) {
//				$pageSize = $_REQUEST ['size'];
//			}
        $temp = isset($_SESSION['paging_row']) ? $_SESSION['paging_row'] : 10;

        empty($_GET['page']) ? $currPage = 1 : $currPage = $_GET['page'];
        empty($_GET['size']) ? $pageSize = $temp : $pageSize = $_GET['size'];

        $_SESSION['paging_row'] = $pageSize;
        $results = array();
        if ($event_type == 7)
        {
            $results = $this->AlertReport->disable_trunk_report($currPage, $pageSize, $search_arr, $search_type, $event_type);
        }
        $this->set('p', $results);
        $name_join_arr['action'] = $this->Action->getActionNameArr();
        $name_join_arr['action_info'] = $this->Action->getActionInfoArr();
        $name_join_arr['condition'] = $this->Condition->getConditionNameArr();
        $name_join_arr['rule'] = $this->AlertRule->getRuleNameArr();
        $name_join_arr['resource'] = $this->_getResourceNameArr();
        $name_join_arr['host'] = $this->_getResourceHostArr();
        //$name_join_arr['route'] = $this->_getResourceRouteInfoArr();
        $this->set('name_join_arr', $name_join_arr);
    }

    public function alternative_route_report()
    {
        $this->pageTitle = "Alternative Route Report";
        $event_type = empty($this->params['pass'][0]) ? 9 : $this->params['pass'][0];

        $currPage = 1;
        $pageSize = 10;
        $search_arr = array();
        if (!empty($_REQUEST['searchkey']))   //模糊查询
        {
            $search_type = 0;
            $search_arr['name'] = !empty($_REQUEST['searchkey']) ? $_REQUEST['searchkey'] : '';
        } else                      //按条件搜索
        {
            $search_type = 1;
            $search_arr['name'] = !empty($_REQUEST['name']) ? $_REQUEST['name'] : '';
        }

//			if (! empty ( $_REQUEST ['page'] )) {
//				$currPage = $_REQUEST ['page'];
//			}
//			
//			if (! empty ( $_REQUEST ['size'] )) {
//				$pageSize = $_REQUEST ['size'];
//			}
        $temp = isset($_SESSION['paging_row']) ? $_SESSION['paging_row'] : 10;

        empty($_GET['page']) ? $currPage = 1 : $currPage = $_GET['page'];
        empty($_GET['size']) ? $pageSize = $temp : $pageSize = $_GET['size'];

        $_SESSION['paging_row'] = $pageSize;
        $results = array();
        if ($event_type == 9)
        {
            $results = $this->AlertReport->disable_trunk_report($currPage, $pageSize, $search_arr, $search_type, $event_type);
        }
        $this->set('p', $results);
        //$name_join_arr['rule'] = $this->AlertRule->getRuleNameArr();
        $name_join_arr['resource'] = $this->_getResourceNameArr();
        $name_join_arr['product'] = $this->_getProductNameArr();
        $this->set('name_join_arr', $name_join_arr);
    }

    public function no_destination_report()
    {
        $this->pageTitle = "No Destination Report";
        $currPage = 1;
        $pageSize = 10;
        $temp = isset($_SESSION['paging_row']) ? $_SESSION['paging_row'] : 10;

        empty($_GET['page']) ? $currPage = 1 : $currPage = $_GET['page'];
        empty($_GET['size']) ? $pageSize = $temp : $pageSize = $_GET['size'];

        $_SESSION['paging_row'] = $pageSize;
        $results = array();
        /*
          if ($event_type == 10)
          {
          $results = $this->AlertReport->disable_trunk_report ($currPage, $pageSize, $search_arr, $search_type, $event_type);
          }
         */
        $results = $this->AlertReport->non_trunk($currPage, $pageSize);
        $this->set('p', $results);

        $name_join_arr['product'] = $this->_getProductNameArr();
        $this->set('name_join_arr', $name_join_arr);
    }

    //-------------------------------view execution log end
    
    public function get_log_info($id)
    {
        $this->autoLayout = false;
        $sql = <<<EOT
   select 
event_time as "time",  
destination_code_name as destination,
(select alias from resource where resource_id = alert_event.res_id) as trunk,
(select name from client where client_id = (select client_id from resource where resource_id = alert_event.res_id)) as carrier,
alert_event.event_type,
alert_event.email_addr
from  
alert_event 
left join alert_rule on alert_event.alert_rule_id = alert_rule.id 
where alert_exec_id = $id order by 1 desc
EOT;
        $result = $this->AlertReport->query($sql);
        $this->set('result', $result);
    }
    
    public function delete_log($id)
    {
        Configure::write('debug', 0);
        $this->autoLayout = false;
        $this->autoRender = false;        
        $sql = "delete from alert_exec_log where id = {$id}";
        $this->AlertReport->query($sql);
        $this->AlertRule->create_json_array('', 201, 'The Rule Execution Log is deleted successfully!');
        $this->Session->write('m', AlertRule::set_validator());
        $this->xredirect("/alerts/view_log");
        
    }

    public function view_log()
    {
        $this->pageTitle = "View Execution Log";
        //$event_type = empty($this->params['pass'][0]) ? 10 : $this->params['pass'][0];

        $currPage = 1;
        $pageSize = 10;
        $search_arr = array();
        if (!empty($_REQUEST['searchkey']))   //模糊查询
        {
            $search_type = 0;
            $search_arr['name'] = !empty($_REQUEST['searchkey']) ? $_REQUEST['searchkey'] : '';
        } else                      //按条件搜索
        {
            $search_type = 1;
            $search_arr['name'] = !empty($_REQUEST['name']) ? $_REQUEST['name'] : '';
        }

//			if (! empty ( $_REQUEST ['page'] )) {
//				$currPage = $_REQUEST ['page'];
//			}
//			
//			if (! empty ( $_REQUEST ['size'] )) {
//				$pageSize = $_REQUEST ['size'];
//			}
        $temp = isset($_SESSION['paging_row']) ? $_SESSION['paging_row'] : 10;

        empty($_GET['page']) ? $currPage = 1 : $currPage = $_GET['page'];
        empty($_GET['size']) ? $pageSize = $temp : $pageSize = $_GET['size'];

        $_SESSION['paging_row'] = $pageSize;
        $results = array();
        $results = $this->AlertReport->ViewExecutionLog($currPage, $pageSize, $search_arr, $search_type);
        
        $this->set('p', $results);
        //$name_join_arr['action'] = $this->Action->getActionNameArr();
        //$name_join_arr['action_info'] = $this->Action->getActionInfoArr();
        //$name_join_arr['condition'] = $this->Condition->getConditionNameArr();
        //$name_join_arr['rule'] = $this->AlertRule->getRuleNameArr();
        //$name_join_arr['resource'] = $this->_getResourceNameArr();
        //$name_join_arr['host'] = $this->_getResourceHostArr();
        //$name_join_arr['route'] = $this->_getResourceRouteInfoArr();
        //$name_join_arr['product'] = $this->_getProductNameArr();
        //$this->set('name_join_arr', $name_join_arr);
    }

    public function list_action()
    {
        $this->pageTitle = "View Execution Log Action";
        $search_arr['alert_exec_id'] = empty($this->params['pass'][0]) ? 0 : $this->params['pass'][0];

        $currPage = 1;
        $pageSize = 10;

//			if (! empty ( $_REQUEST ['page'] )) {
//				$currPage = $_REQUEST ['page'];
//			}
//			
//			if (! empty ( $_REQUEST ['size'] )) {
//				$pageSize = $_REQUEST ['size'];
//			}
        $temp = isset($_SESSION['paging_row']) ? $_SESSION['paging_row'] : 10;

        empty($_GET['page']) ? $currPage = 1 : $currPage = $_GET['page'];
        empty($_GET['size']) ? $pageSize = $temp : $pageSize = $_GET['size'];

        $_SESSION['paging_row'] = $pageSize;
        $results = array();
        $results = $this->AlertReport->ListEvent($currPage, $pageSize, $search_arr);
        $this->set('p', $results);
        //$name_join_arr['action'] = $this->Action->getActionNameArr();
        $name_join_arr['action_info'] = $this->Action->getActionInfoArr();
        //$name_join_arr['condition'] = $this->Condition->getConditionNameArr();
        //$name_join_arr['rule'] = $this->AlertRule->getRuleNameArr();
        $name_join_arr['resource'] = $this->_getResourceNameArr();
        //$name_join_arr['host'] = $this->_getResourceHostArr();
        //$name_join_arr['route'] = $this->_getResourceRouteInfoArr();
        $name_join_arr['product'] = $this->_getProductNameArr();
        $this->set('name_join_arr', $name_join_arr);
    }

    //-------------------------------view execution log end

    public function get_condition($name)
    {
        Configure::write('debug', 0);
        $this->autoRender = FALSE;
        $this->autoLayout = FALSE;
        $sql = "
SELECT 
	CASE acd_comparator WHEN 0 THEN  acd_value_min || ' <= ACD'
			    WHEN 1 THEN  acd_value_min || ' <= ACD <= ' || acd_value_max
			    WHEN 2 THEN  'Ignore'
	END AS acd,
	CASE asr_comparator WHEN 0 THEN  asr_value_min || ' <= ASR'
			    WHEN 1 THEN  asr_value_min || ' <= ASR <= ' || asr_value_max
			    WHEN 2 THEN  'Ignore'
	END AS asr,
	CASE margin_comparator WHEN 0 THEN  margin_value_min || ' <= Margin'
			    WHEN 1 THEN  margin_value_min || ' <= Margin <= ' || margin_value_max
			    WHEN 2 THEN  'Ignore'
	END AS margin
FROM alert_condition WHERE name = '{$name}'";
        $result = $this->Condition->query($sql);
        echo json_encode($result);
    }

    public function get_action($name)
    {
        Configure::write('debug', 0);
        $this->autoRender = FALSE;
        $this->autoLayout = FALSE;
        $sql = "
SELECT 
	CASE WHEN email_to_noc = true THEN 'email to noc,' ELSE '' END || 
	CASE WHEN email_to_carrier = true THEN 'email to carrier,' ELSE '' END ||
	CASE WHEN disable_host = true THEN 'disable host,' ELSE '' END ||
	CASE WHEN disable_resource = true THEN 'disable resource,' ELSE '' END ||
	CASE WHEN disable_code_trunk = true THEN 'disable code trunk' ELSE '' END AS content
FROM alert_action WHERE name = '{$name}'";
        $result = $this->Condition->query($sql);
        echo json_encode($result);
    }

    public function rule_status($id, $status)
    {
        Configure::write('debug', 0);
        $this->autoRender = FALSE;
        $this->autoLayout = FALSE;
        $sql = "update alert_rule set status = {$status} where id = {$id}";
        $this->AlertRule->query($sql);
        $this->AlertRule->create_json_array('', 201, 'The status is changed successfully!');
        $this->Session->write('m', AlertRule::set_validator());
        $this->xredirect("/alerts/rule");
    }

    public function get_events($id)
    {
        Configure::write('debug', 0);
        $this->autoRender = FALSE;
        $this->autoLayout = FALSE;
        $sql = "SELECT 
    CASE event_type 
        WHEN 1 THEN 'disable trunk'
	WHEN 2 THEN 'disable host'
	WHEN 3 THEN 'enable trunk'
	WHEN 4 THEN 'enable host'
	WHEN 5 THEN 'disable code trunk'
	WHEN 6 THEN 'enable code trunk'
	WHEN 7 THEN 'change priority'
	WHEN 8 THEN 'email'
	WHEN 9 THEN 'change to old priority'
    END AS event,email_addr
FROM
	alert_event
WHERE
	alert_exec_id = {$id}";
        $result = $this->AlertReport->query($sql);
        echo json_encode($result);
    }

    public function delete_all()
    {
        if (!$_SESSION['role_menu']['Monitoring']['alerts:rule']['model_w'])
        {
            $this->redirect_denied();
        }

        if ($this->AlertReport->deleteAll() != true)
            $this->Condition->create_json_array('', 101, "can not delete");
        else
            $this->Condition->create_json_array('', 201, __('All alert_rule is deleted successfully.', true));

        $this->Session->write('m', AlertRule::set_validator());
        $this->redirect('/alerts/rule');
    }

    public function delete_selected()
    {
        if (!$_SESSION['role_menu']['Monitoring']['alerts:rule']['model_w'])
        {
            $this->redirect_denied();
        }
        $ids = $_REQUEST['ids'];
        $arrName = $this->AlertReport->getNameByID($ids);


        foreach ($arrName as $name)
        {
            $tip.=$name[0]['name'] . ",";
        }
        $tip = '[' . substr($tip, 0, -1) . ']';
        $r = $this->AlertReport->deleteSelected($ids);

        if ($r != true)
            $this->Condition->create_json_array('', 101, "can not delete");
        else
            $this->Condition->create_json_array('', 201, 'The alert_rule ' . $tip . ' is deleted successfully!');

        $this->Session->write('m', AlertRule::set_validator());
        $this->redirect('/alerts/rule');
    }

    public function action_edit_panel($id = null)
    {
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        if ($this->isPost())
        {
            if ($id != null)
                $this->data['Action']['id'] = $id;
            $this->data['Action']['update_by'] = $_SESSION['sst_user_name'];
            $this->data['Action']['update_at'] = date('Y-m-d H:i:sO');
            if ($this->data['Action']['email_notification'] == 1)
            {
                $this->data['Action']['email_to_noc'] = TRUE;
                $this->data['Action']['email_to_carrier'] = FALSE;
            } elseif ($this->data['Action']['email_notification'] == 2)
            {
                $this->data['Action']['email_to_carrier'] = TRUE;
                $this->data['Action']['email_to_noc'] = FALSE;
            } elseif ($this->data['Action']['email_notification'] == 3)
            {
                $this->data['Action']['email_to_carrier'] = TRUE;
                $this->data['Action']['email_to_noc'] = TRUE;
            }
            $this->Action->save($this->data);
            if ($id != null)
                $this->Session->write('m', $this->Action->create_json(201, __('The alert action [' . $this->data['Action']['name'] . '] is modified successfully!', true)));
            else
                $this->Session->write('m', $this->Action->create_json(201, __('The alert action [' . $this->data['Action']['name'] . '] is created successfully!', true)));
            $this->xredirect("/alerts/action");
        }
        
        $templates = $this->TroubleTicketsTemplate->find('list', array('fields' => array("TroubleTicketsTemplate.name")));
        
        $templates = array(null => '') + $templates;
        $this->set('templates', $templates);
        $this->data = $this->Action->find('first', Array('conditions' => Array('id' => $id)));
    }

}

?>
