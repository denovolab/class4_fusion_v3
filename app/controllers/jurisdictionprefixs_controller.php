<?php

class JurisdictionprefixsController extends AppController
{

    var $uses = array('Jurisdictionprefix');

    function index()
    {
        $this->redirect('view');
    }

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
            $limit = $this->Session->read('sst_locationPrefixes');
            $this->Session->write('executable', $limit['executable']);
            $this->Session->write('writable', $limit['writable']);
        }

        parent::beforeFilter();
    }

    public function validate_jur()
    {
        $flag = 'true';
        $tmp = (isset($_POST ['rates'])) ? $_POST ['rates'] : '';
        $size = count($tmp);
        foreach ($tmp as $el)
        {
            $this->data['Jurisdiction'] = $el;
            if ($this->data['Jurisdiction']['alias'] == '')
            {
                $this->Jurisdictioncountry->create_json_array('#ClientOrigRateTableId', 101, 'Please fill \"alais\" field correctly (only  digits allowed).');
                $flag = 'false';
            } else
            {
                $c = $this->check_alias($this->data['Jurisdiction']['id'], $this->data['Jurisdiction']['alias']);
                if ($c != 0)
                {
                    $this->create_json_array('#ClientName', 301, __('checkclientname', true));
                    $error_flag = 'false';
                }
            }
            $c = $this->check_name($this->data['Jurisdiction']['id'], $this->data['Jurisdiction']['name']);
            if ($c != 0)
            {
                $this->create_json_array('#ClientName', 301, __('checkclientname', true));
                $error_flag = 'false';
            }

            return $flag;
        }
    }

    public function get_jurisdiction_id($name, $country_id)
    {
        # first add
        $return = 0;
        $list = $this->Jurisdictionprefix->query("select  count(*)   from  jurisdiction;");
        if (count($list[0][0]) == 0 || empty($list[0][0]['count']))
        {
            $return = 0;
        }
        #
        $list = $this->Jurisdictionprefix->query("select  id   from  jurisdiction  where  name='$name' and jurisdiction_country_id = $country_id limit 1;");

        $list_tmp = $this->Jurisdictionprefix->query("select  id   from  jurisdiction  where  name='$name';");


        if ($list && count($list[0][0]) && !empty($list[0][0]['id']))
        {
            $return = $list[0][0]['id'];
        } elseif (count($list_tmp[0][0]) && !empty($list_tmp[0][0]['id']))
        {
            $return = -1;
        }
        if ($return == 0)
        {
            $this->data['Jurisdiction']['name'] = $this->data['Jurisdiction']['alias'] = $name;
            $this->data['Jurisdiction']['jurisdiction_country_id'] = $country_id;
            $return_tmp = $this->Jurisdiction->save($this->data ['Jurisdiction']);
            if (!empty($return_tmp))
            {
                $list = $this->Jurisdictionprefix->query("select id from  jurisdiction  where  name='$name' and jurisdiction_country_id = $country_id limit 1;");
                if (count($list[0][0]) && !empty($list[0][0]['id']))
                {
                    $return = $list[0][0]['id'];
                }
            }
        }
        return $return;
    }

    public function get_jurisdiction_country_id($name)
    {
        # first add
        $return = 0;
        $list = $this->Jurisdictionprefix->query("select  count(*)   from  jurisdiction_country;");
        if (count($list[0][0]) == 0 || empty($list[0][0]['count']))
        {
            $return = 0;
        }
        #
        $list = $this->Jurisdictionprefix->query("select  id   from  jurisdiction_country  where  name='$name' limit 1;");
        if ($list && count($list[0][0]) && !empty($list[0][0]['id']))
        {
            $return = $list[0][0]['id'];
        }
//		else{
//			$list=$this->Jurisdictionprefix->query("select  max(jurisdiction_country_id)   from  jurisdiction_prefix ;");
//			$t=$list[0][0]['max'];
//			return intval($t)+1;
//		}
        //var_dump($return);
        if ($return == 0)
        {
            $this->data['Jurisdictioncountry']['name'] = $name;
            $return_tmp = $this->Jurisdictioncountry->save($this->data ['Jurisdictioncountry']); //var_dump($return_tmp);
            if (!empty($return_tmp))
            {
                $list = $this->Jurisdictionprefix->query("select id   from  jurisdiction_country  where  name='$name' limit 1;");
                if (count($list[0][0]) && !empty($list[0][0]['id']))
                {
                    $return = $list[0][0]['id'];
                }
            }
        }
        return $return;
    }

    public function add()
    {
        $this->autoRender = false;
        $this->autoLayout = false;
        if (!$_SESSION['role_menu']['Switch']['jurisdictionprefixs']['model_w'])
        {
            $this->redirect_denied();
        }


        $datas = $_POST['rates'];


        foreach ($datas as $item)
        {
            
            $item['ocn'] = empty($item['ocn']) ? 'NULL': $item['ocn'];
            $item['lata'] = empty($item['lata']) ? 'NULL': $item['lata'];
            if (!empty($item['id']))
            {
                $sql1 = "SELECT id FROM jurisdiction_country WHERE name = '{$item['jurisdiction_country_name']}'";
                $result1 = $this->Jurisdictionprefix->query($sql1);
                if (count($result1))
                {
                    $jurisdiction_country_id = $result1[0][0]['id'];
                } else
                {
                    $sql2 = "INSERT INTO jurisdiction_country(name) VALUES ('{$item['jurisdiction_country_name']}') RETURNING id";
                    $result2 = $this->Jurisdictionprefix->query($sql2);
                    $jurisdiction_country_id = $result2[0][0]['id'];
                }
                $sql3 = "SELECT id FROM jurisdiction WHERE name = '{$item['jurisdiction_name']}' AND jurisdiction_country_id = {$jurisdiction_country_id}";
                $result3 = $this->Jurisdictionprefix->query($sql3);
                if (count($result3))
                {
                    $jurisdiction_name_id = $result3[0][0]['id'];
                } else
                {
                    $sql4 = "INSERT INTO jurisdiction(name, jurisdiction_country_id) VALUES('{$item['jurisdiction_name']}',
                                    {$jurisdiction_country_id}) RETURNING id";
                    $result4 = $this->Jurisdictionprefix->query($sql4);
                    $jurisdiction_name_id = $result4[0][0]['id'];
                }
                $sql5 = "UPDATE jurisdiction_prefix SET prefix = '{$item['prefix']}', jurisdiction_id = {$jurisdiction_name_id},
                        jurisdiction_country_id = {$jurisdiction_country_id}, jurisdiction_name = '{$item['jurisdiction_name']}', jurisdiction_country_name = '{$item['jurisdiction_country_name']}',ocn={$item['ocn']}, lata = {$item['lata']}
                         WHERE id = {$item['id']}";
                $this->Jurisdictionprefix->query($sql5);
            } else
            {
                $sql1 = "SELECT id FROM jurisdiction_country WHERE name = '{$item['jurisdiction_country_name']}'";
                $result1 = $this->Jurisdictionprefix->query($sql1);
                if (count($result1))
                {
                    $jurisdiction_country_id = $result1[0][0]['id'];
                } else
                {
                    $sql2 = "INSERT INTO jurisdiction_country(name) VALUES ('{$item['jurisdiction_country_name']}') RETURNING id";
                    $result2 = $this->Jurisdictionprefix->query($sql2);
                    $jurisdiction_country_id = $result2[0][0]['id'];
                }
                $sql3 = "SELECT id FROM jurisdiction WHERE name = '{$item['jurisdiction_name']}' AND jurisdiction_country_id = {$jurisdiction_country_id}";
                $result3 = $this->Jurisdictionprefix->query($sql3);
                if (count($result3))
                {
                    $jurisdiction_name_id = $result3[0][0]['id'];
                } else
                {
                    $sql4 = "INSERT INTO jurisdiction(name, jurisdiction_country_id) VALUES('{$item['jurisdiction_name']}',
                                    {$jurisdiction_country_id}) RETURNING id";
                    $result4 = $this->Jurisdictionprefix->query($sql4);
                    $jurisdiction_name_id = $result4[0][0]['id'];
                }
                $sql5 = "INSERT INTO jurisdiction_prefix(prefix, jurisdiction_id, jurisdiction_country_id, jurisdiction_name, jurisdiction_country_name,ocn, lata)
                            VALUES('{$item['prefix']}', {$jurisdiction_name_id}, {$jurisdiction_country_id}, '{$item['jurisdiction_name']}', '{$item['jurisdiction_country_name']}', {$item['ocn']}, {$item['lata']})";

                $this->Jurisdictionprefix->query($sql5);
            }
        }

        //$this->Jurisdictionprefix->create_json_array('#ClientOrigRateTableId', 201, 'Jurisdiction, action successfully !');
        $this->Jurisdictionprefix->create_json_array('#ClientOrigRateTableId', 201, 'Succeeded');
        $this->Session->write("m", Jurisdictionprefix::set_validator());
        //$this->redirect("/jurisdictionprefixs/view?page={$_GET['page']}&size={$_GET['size']}");
        $this->redirect("/jurisdictionprefixs/view");
    }

    public function delete($id)
    {
        $this->autoRender = false;
        $this->autoLayout = false;
        $this->Jurisdictionprefix->query("DELETE FROM jurisdiction_prefix WHERE id = {$id}");
    }

    public function view()
    {
        $this->pageTitle = "Switch/Jurisdiction ";
        $this->set('p', $this->Jurisdictionprefix->view($this->_order_condtions(array('id', 'jurisdiction_name', 'prefix', 'jurisdiction_country_name'))));
    }

    public function view_rate_table($country_id)
    {
        $this->pageTitle = "Switch/Jurisdiction ";
        $this->set('p', $this->Jurisdictionprefix->view_rate_table($country_id, $this->_order_condtions(array('id', 'jurisdiction_name', 'prefix', 'jurisdiction_country_name'))));
    }

//delete all
    public function del_all_jur()
    {
        if (!$_SESSION['role_menu']['Switch']['jurisdictionprefixs']['model_w'])
        {
            $this->redirect_denied();
        }
        $this->Jurisdictionprefix->begin();
        $qs_c = 0;
        $qs = $this->Jurisdictionprefix->query("delete from jurisdiction_prefix");
        $qs_c += count($qs);
//		$qs = $this->Product->query("delete from resource_product_ref");
//		$qs_c += count($qs);
        if ($qs_c == 0)
        {
            $this->Jurisdictionprefix->create_json_array('', 201, __('delallprosuc', true));
            $this->Jurisdictionprefix->commit();
        } else
        {
            $this->Jurisdictionprefix->create_json_array('', 101, __('delallprofail', true));
            $this->Jurisdictionprefix->rollback();
        }
        $this->Session->write('m', Jurisdictionprefix::set_validator());
        $this->redirect('/jurisdictionprefixs/view');
    }

    //select delete
    public function del_selected_jur()
    {
        if (!$_SESSION['role_menu']['Switch']['jurisdictionprefixs']['model_w'])
        {
            $this->redirect_denied();
        }
        $ids = $_REQUEST['ids'];

        $this->Jurisdictionprefix->begin();
        $qs_c = 0;
        $qs = $this->Jurisdictionprefix->query("delete from jurisdiction_prefix where id in ($ids)");
        $qs_c += count($qs);
//		$qs =	$this->Product->query("delete from resource_product_ref where product_id in ($ids)");
//		$qs_c += count($qs);
        if ($qs_c == 0)
        {
            $this->Jurisdictionprefix->create_json_array('', 201, __('delselprosuc', true));
            $this->Jurisdictionprefix->commit();
        } else
        {
            $this->Jurisdictionprefix->create_json_array('', 101, __('delselprofail', true));
            $this->Jurisdictionprefix->rollback();
        }

        $this->Session->write('m', Jurisdictionprefix::set_validator());
        $this->redirect('/jurisdictionprefixs/view');
    }

}

?>
