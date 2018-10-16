<?php

App::import('Model', 'ImportExportLog');

class UploadsController extends AppController
{

    var $name = 'Uploads';
    var $helpers = array('AppUploads');
    var $uses = array();
    var $components = array("RequestHandler");
    var $foreign_id;
    var $statistics = array(
        'success' => 0,
        'failure' => 0,
        'duplicate' => 0,
        'error_file' => '',
        'log_id' => 0,
    );

    const CSV_DELIMITER = ",";

    public function beforeFilter()
    {
        if (isset($_POST['PHPSESSID']))
        {
            session_id($_POST['PHPSESSID']);
            session_start();
        }
        if( $this->params['action'] == 'async_upload')
        {
            Configure::load('myconf');
            return true;
        }
        $this->checkSession("login_type"); //核查用户身份
        parent::beforeFilter(); //调用父类方法
    }

    function egress_action()
    {
        $this->set('type', 8);
        $this->set('example_file', 'resource_action');
        $this->pageTitle = "Upload/Egress Action";
        if (Configure::read('project_name') == 'exchange')
        {
            $this->_upload("ResourceDirection", '', $this->webroot . "gatewaygroups/view_egress");
        } else
        {
            $this->_upload("ResourceDirection", '', $this->webroot . "prresource/gatewaygroups/view_egress");
        }
    }

    function ingress_action()
    {
        $this->set('type', 8);
        $this->set('example_file', 'resource_action');
        if (Configure::read('project_name') == 'exchange')
        {
            $this->_upload("ResourceDirection", '', $this->webroot . "gatewaygroups/view_ingress");
        } else
        {
            $this->_upload("ResourceDirection", '', $this->webroot . "prresource/gatewaygroups/view_ingress");
        }
    }

    function carrier()
    {
        $this->_upload("Carrier", array(), $this->webroot . "clients/view");
    }

    function egress_host($id = null)
    {
        $this->set('type', 9);
        $this->set('example_file', 'host');
        $this->pageTitle = "Upload/Egress Host";
        if (Configure::read('project_name') == 'exchange')
        {
            $this->_upload("ResourceIp", array('egress' => true), $this->webroot . "gatewaygroups/view_egress");
        } else
        {
            $this->_upload("ResourceIp", array('egress' => true), $this->webroot . "prresource/gatewaygroups/view_egress");
        }
    }

    function ingress_host($id = null)
    {
        $this->set('type', 9);
        $this->set('example_file', 'host');
        $this->pageTitle = "Upload/Ingress Host";
        if (Configure::read('project_name') == 'exchange')
        {
            $this->_upload("ResourceIp", '', $this->webroot . "gatewaygroups/view_ingress");
        } else
        {
            $this->_upload("ResourceIp", '', $this->webroot . "prresource/gatewaygroups/view_ingress");
        }
    }

    function ingress_tran($id = null)
    {
        $this->set('type', 7);
        $this->set('example_file', 'resource_digit_mapping');
        if (Configure::read('project_name') == 'exchange')
        {
            $this->_upload("ResourceTranslation", array("resource_id" => $id), $this->webroot . "gatewaygroups/view_ingress");
        } else
        {

            $this->_upload("ResourceTranslation", array("resource_id" => $id), $this->webroot . "prresource/gatewaygroups/view_ingress");
        }
    }

    function block_list($id = null)
    {
        $this->set('example_file', 'resource_block');
        $this->pageTitle = "Upload/Block List";
        $this->set('module', 'Routing');
        $this->set('action', 'Block list');
        $this->set('type', 1);
        $this->_upload("ResourceBlock", array('block' => true), $this->webroot . "blocklists/index");
    }
    
    function special_code()
    {
        $this->set('example_file', 'special_code');
        $this->pageTitle = "Upload/Special Code";
        $this->set('module', 'Origination');
        $this->set('action', 'Special Code');
        $this->set('type', 13);
        $this->_upload("DidSpecialCode", array('block' => true), $this->webroot . "did/billing_rule/special_code");
    }

    function egress()
    {
        $this->set('example_file', 'egress');
        $this->set('type', 11);
        if (Configure::read('project_name') == 'exchange')
        {
            $this->_upload("Egress", array('egress' => true), $this->webroot . "gatewaygroups/view_egress");
        } else
        {
            $this->_upload("Egress", array('egress' => true), $this->webroot . "prresource/gatewaygroups/view_egress");
        }
    }

    function ingress()
    {
        $this->set('example_file', 'ingress');
        $this->set('type', 10);
        if (Configure::read('project_name') == 'exchange')
        {
            $this->_upload("Ingress", array('ingress' => true), $this->webroot . "gatewaygroups/view_ingress");
        } else
        {
            $this->_upload("Ingress", array('ingress' => true), $this->webroot . "prresource/gatewaygroups/view_ingress");
        }
    }

    function code_deck($id = null)
    {
        $this->set('type', 4);
        $this->set('example_file', 'code_deck');
        $this->set('name', $this->select_name($id, 'Code'));
        $this->set('module', 'Switch');
        $this->set('action', 'Code Deck List');
        $id = intval($id);
        if ($id <= 0)
        {
            $this->_upload_null();
        } else
        {
            $this->set('id', $id);
            $this->_upload("Code", array("code_deck_id" => $id), $this->webroot . "codedecks/codes_list/" . $id);
        }
    }

    function digit_translation($id = null)
    {
        $this->set('type', 7);
        $this->set('example_file', 'digit_translation');
        $id = intval($id);
        $this->set("name", $this->select_name($id, "DigitTranslation"));
        if ($id <= 0)
        {
            $this->_download_null();
        } else
        {
            $this->set('id', $id);
            $this->_upload("DigitTranslation", array("translation_id" => $id), $this->webroot . "digits/translation_details/" . $id);
        }
    }

    function rate($id = null)
    {
        $id = intval($id);
        $this->set("name", $this->select_name($id, "RateTable"));
        if ($id < 0)
        {
            $this->_upload_null();
        } else
        {
            $this->set('id', $id);
            $this->_upload("RateTable", array('rate_table_id' => $id), $this->webroot . "clientrates/view/" . $id);
        }
    }

    function route_plan($id = null)
    {
        $this->set('type', 2);
        $this->set('example_file', 'route_plan');
        $id = intval($id);
        $this->set('name', $this->select_name($id, "Route"));
        if ($id < 0)
        {
            $this->_upload_null();
        } else
        {
            $this->set('id', $id);
            $this->_upload("Route", array('route_strategy_id' => $id), $this->webroot . "routestrategys/routes_list/" . $id);
        }
    }
    
    

    function jur_country($id = null)
    {
        $this->set('type', 5);
        $this->set('example_file', 'jurisdiction');
        $this->pageTitle = "Upload/Jur Country";
        $id = intval($id);
        $this->set("control_name", "jur_country");
        if ($id < 0)
        {
            $this->_upload_null();
        } else
        {
            $this->set('id', $id);
            $this->_upload("JurisdictionUpload", array('id' => $id), $this->webroot . "jurisdictionprefixs/view");
        }
    }

    function static_route($id = null)
    {
        $this->set('type', 3);
        $this->set('example_file', 'static_route');
        $id = intval($id);
        $this->set('name', $this->select_name($id, 'Productitem'));
        if ($id < 0)
        {
            $this->_upload_null();
        } else
        {
            $this->set('id', $id);
            $this->_upload("Productitem", array('product_id' => $id), $this->webroot . "products/route_info/" . $id);
        }
    }
    
  

    function _send_file($download_file, $file_name)
    {
        header("Content-type: application/octet-stream;charset=utf8");
        header("Accept-Ranges: bytes");
        header("Content-Disposition: attachment; filename=" . $file_name);
        echo file_get_contents($download_file);
        exit();
        return true;
    }

    function download_error_file($id = null)
    {
        $id = intval($id);
        if ($id < 0)
        {
            $this->_upload_null();
        } else
        {
            $this->_catch_exception_msg(array('UploadsController', '_download_error_file_impl'), $id);
            $this->render('uploads');
        }
    }

    function download_original_file($id = null)
    {
        $id = intval($id);
        if ($id < 0)
        {
            $this->_upload_null();
        } else
        {
            $this->_catch_exception_msg(array('UploadsController', '_download_original_file_impl'), $id);
            $this->render('uploads');
        }
    }

    function _download_original_file_impl($id)
    {
        $log_model = new ImportExportLog ();
        $log = $log_model->find('first', array('conditions' => "id = $id"));

        if ($log && $log['ImportExportLog']['file_path'] && file_exists($log['ImportExportLog']['file_path']))
        {
            Configure::write('debug', 0);
            $this->autoRender = false;
            $this->layout = '';
            $this->_send_file($log['ImportExportLog']['file_path'], 'upload_' . str_replace(' ', '_', $log['ImportExportLog']['time']) . '.' . pathinfo($log['ImportExportLog']['file_path'], PATHINFO_EXTENSION));
            exit();
        } else
        {
            throw new Exception("Error File not found.");
        }
    }

    function download_db_error_file($id = null)
    {
        $id = intval($id);
        if ($id < 0)
        {
            $this->_upload_null();
        } else
        {
            $this->_catch_exception_msg(array('UploadsController', '_download_db_error_file_impl'), $id);
            $this->render('uploads');
        }
    }

    function _download_db_error_file_impl($id)
    {
        $log_model = new ImportExportLog ();
        $log = $log_model->find('first', array('conditions' => "id = $id"));
        if ($log && $log['ImportExportLog']['db_error_file_path'] && file_exists($log['ImportExportLog']['db_error_file_path']))
        {
            Configure::write('debug', 0);
            $this->autoRender = false;
            $this->layout = '';
            $this->_send_file($log['ImportExportLog']['db_error_file_path'], 'upload_' . str_replace(' ', '_', $log['ImportExportLog']['time']) . '.' . pathinfo($log['ImportExportLog']['file_path'], PATHINFO_EXTENSION));
            exit();
        } else
        {
            throw new Exception("Error File not found.");
        }
    }

    function _download_error_file_impl($id)
    {
        $log_model = new ImportExportLog ();
        $log = $log_model->find('first', array('conditions' => "id = $id"));

        if ($log && $log['ImportExportLog']['error_file_path'] && file_exists($log['ImportExportLog']['error_file_path']))
        {
            Configure::write('debug', 0);
            $this->autoRender = false;
            $this->layout = '';
            $this->_send_file($log['ImportExportLog']['error_file_path'], 'error_file.txt');
            //$this->_send_file($log['ImportExportLog']['error_file_path'], 'upload_' . str_replace(' ', '_', $log['ImportExportLog']['time']) . '.' . pathinfo($log['ImportExportLog']['file_path'], PATHINFO_EXTENSION));
            exit();
        } else
        {
            throw new Exception("Error File not found.");
        }
    }

//	function reprocess($id=null){
//		$id = intval($id);
//		if($id < 0){
//			$this->_upload_null();
//		}else{
//			$this->_catch_exception_msg(array('UploadsController','_reprocess_impl'),$id);
//			$this->set('statistics',$this->statistics);
//			$this->render('uploads');
//		}
//	}

    function _reprocess_impl($id)
    {
        $log_model = new ImportExportLog ();
        $log = $log_model->find('first', array('conditions' => "id = $id"));
        if ($log && !$log_model->is_processing($log) && $log['ImportExportLog']['error_file_path'] && file_exists($log['ImportExportLog']['error_file_path']))
        {
            $this->loadModel($log['ImportExportLog']['obj']);
            $model = $this->{$log['ImportExportLog']['obj']};
            if (array_keys_value($log, 'ImportExportLog.ext_attributes.rollback_on_error'))
            {
                $upload_file = $log['ImportExportLog']['file_path'];
            } else
            {
                $upload_file = empty($log['ImportExportLog']['error_file_path']) ?
                        $log['ImportExportLog']['file_path'] :
                        $log['ImportExportLog']['error_file_path'];
            }
            $save_ext_attributes = array_keys_value($log, 'ImportExportLog.ext_attributes.save_ext_attributes', array());
            $this->statistics['log_id'] = $log['ImportExportLog']['id'];
            $this->_process($model, $upload_file, $save_ext_attributes, $log);
        } else
        {
            throw new Exception("Permission denied.");
        }
    }

    function aaa()
    {
        $shell = APP . 'csv2sql.php';
        $php_path = Configure::read('php_exe_path');
        $logfile = '/tmp/csv2sql.log';
        pr("$php_path {$shell} > {$logfile} &");
    }

    function _exe_upload_shell($model_name)
    {
        $shell = APP . 'csv2sql.php';
        //exec("php {$shell}");
        //pr($shell);
        if (file_exists($shell))
        {
            $php_path = Configure::read('php_exe_path');
            $logfile = '/tmp/csv2sql.' . $model_name . '_' . date("YmdHis") . '.log';
            `$php_path {$shell} > {$logfile} &`;
        }
    }

    function _upload($model_name, $save_ext_attributes = array(), $back_url = '')
    {
        //		apd_set_pprof_trace();
        if ($this->RequestHandler->isPost())
        {
            ini_set("max_execution_time", "3600");
            $this->_do_upload($model_name, $save_ext_attributes); #upload
            $this->set('statistics', $this->statistics);
        }
        $this->_render_upload_page($model_name, $back_url);
    }

    function _render_upload_page($model_name, $back_url = null)
    {
        $this->set('back_url', $back_url);
        $this->render('uploads');
    }

    #

    function _do_upload($model_name, $save_ext_attributes = array())
    {
        $this->_catch_exception_msg(array('UploadsController', '_do_upload_impl'), $model_name, $save_ext_attributes);
    }

    function _do_upload_impl($model_name, $save_ext_attributes = array())
    {
        /*
        $this->loadModel($model_name);
        $model = $this->{$model_name};
        if (isset($_FILES ["file"]) && isset($_FILES ["file"]["name"]) && empty($_FILES ["file"]["name"]))
        {
            throw new Exception("Upload Error, Please Choose a CSV File. And Try It Again.");
        }
        if (!(isset($_FILES ["file"]) && isset($_FILES ["file"] ["error"]) && $_FILES ["file"] ["error"] == UPLOAD_ERR_OK))
        {

            throw new Exception("Upload Error, Please Try It Again.");
        }
         * 
         */
        //$upload_file = $this->_move_upload_file($model);
        
        $path = APP . 'tmp' . DS . 'upload' . DS . 'csv';
        $upload_file = $path . DS . trim($_POST['myfile_guid']) .".csv";
        $this->_log($model_name, $upload_file, ImportExportLog::STATUS_PROCESSING, '', $save_ext_attributes);
        //	$this->_process($model,$upload_file,$save_ext_attributes);# process  upload  file
    }

    #process  upload file

    function _process($model, $upload_file, $save_ext_attributes = array(), $log = null)
    {
        $model_name = $model->alias;
        $schema = $this->_get_schema($model);
        $fd = fopen($upload_file, "r");

        $data = '';
        $fields = array_keys($schema);
        $i = 0;
        $d = array();
        $col_num = 0;
        $this->_log($model_name, $upload_file, ImportExportLog::STATUS_PROCESSING, '', $save_ext_attributes);
        $duplicate_type = $this->_get_duplicate_type($log);
        $err_fd = $this->_new_error_file($model);
        $err_fd->open('w');
        $err_fd_handle = $err_fd->handle;
        $this->statistics['error_file'] = $err_fd->path;

        //  如果有 headers ,则 验证上传的数据列 是否合法
        if ($this->_is_with_headers($log))
        {
            $header_data = array();
            foreach ($fields as $field)
            {
                $f = $schema[$field];
                $d = isset($f['name']) ? Inflector::humanize($f['name']) : Inflector::humanize($field);
                $header_data[] = $d;
            }
            fputcsv($err_fd_handle, $header_data, self::CSV_DELIMITER, '"');
            //$fd->write(join(self::CSV_DELIMITER,$data)."\n");	
            # check column
            if (!$this->_check_upload_columns($schema, $fields, $fd))
            {
                throw new Exception("Format Error, Please Check And Try It Again.");
            }
        }

        # 当 rollback_on_error 被选中， 而且保存时发生错误，
        # on_error 为 true, 此时，发生错误 以后的所有数据，
        # 只验证数据的正确性，而不检查重复 ， 保存
        $on_error = false;

        $rollback_on_error = $this->_is_rollback_on_error($log);
        $col_num = count($fields);
        if ($rollback_on_error)
        {
            $model->begin();
        }
        $multi_record_method = "get_multi_record_for_upload";
        while ($csv_data = fgetcsv($fd, 1024, self::CSV_DELIMITER))
        {
            if (method_exists($model, $multi_record_method))
            {
                $datas = $model->{$multi_record_method}($csv_data);
            } else
            {
                $datas = array($csv_data);
            }
            foreach ($datas as $data)
            {
                try
                {

                    $this->_save_one_record($on_error, $model, $data, $fields, $schema, $duplicate_type, $save_ext_attributes);
                    if ($rollback_on_error && $on_error)
                    {
                        $data = $this->_format_error_file_record($data, $col_num);
                        fputcsv($err_fd_handle, $data, self::CSV_DELIMITER, '"');
                    }
                } catch (Exception $e)
                {
                    # 在最后一列加上错误信息，  在规定的列数后面加任何信息将会丢掉，这里添加 此信息，只是为了方便人员修改数据
                    $data = $this->_format_error_file_record($data, $col_num);
                    $data[] = $e->getMessage();
                    fputcsv($err_fd_handle, $data, self::CSV_DELIMITER, '"');
                    if ($rollback_on_error && !$on_error)
                    {
                        $model->rollback();
                        $on_error = true;
                    }
                }
            }
        }
        if ($rollback_on_error && !$on_error)
        {
            $model->commit();
        }
        $err_fd->close();
        fclose($fd);
        $this->_log($model_name, $upload_file, ImportExportLog::STATUS_SUCCESS, $err_fd->path);
    }

    function _format_error_file_record($data, $col_num)
    {
        # 错误处理				
        $data_num = count($data);
        # 上传的列数过多， 则删除
        if ($data_num > $col_num)
        {
            $data = array_slice($data, 0, $col_num);
        }
        # 上传的列数太少， 则添加空列
        if ($data_num < $col_num)
        {
            for ($ti = 0; $ti < $col_num - $data_num; $ti++)
            {
                $data[] = '';
            }
        }
        return $data;
    }

    #save  data to  database

    function _save_one_record($on_error, $model, $data, $fields, $schema, $duplicate_type = 'ignore', $save_ext_attributes = array())
    {


        $d = $save_ext_attributes;
        $col_num = count($fields);
        $ext_errors = array();
        for ($i = 0; $i < $col_num; $i++)
        {
            $data[$i] = trim($data[$i]);
            $field_name = $fields[$i];
            if (!isset($data[$i]) || $data[$i] === '')
            {
                $d[$field_name] = isset($schema[$field_name]['default']) ? $schema[$field_name]['default'] : '';
            } else
            {
                $method = 'format_' . $field_name . '_for_upload';
                if (method_exists($model, $method))
                {
                    try
                    {
                        $d[$field_name] = $model->{$method}($data[$i], $data);
                    } catch (Exception $e)
                    {
                        $ext_errors[$field_name] = $e->getMessage();
                    }
                } else
                {
                    $f = $schema[$field_name];
                    if (!empty($f) && isset($f['type']) && $f['type'] == 'boolean')
                    {
                        if (strtolower($data[$i]) === 't')
                        {
                            $d[$field_name] = true;
                        } else
                        {
                            $d[$field_name] = false;
                        }
                    } else
                    {
                        $d[$field_name] = $data[$i];
                    }
                }
            }
        }
        $d = array($model->alias => $d);
        $model->id = null;
        $model->set($d);
        $success = $model->validates();
        if (!$success || !empty($ext_errors))
        {
            # 验证失败
//		$this->log($errors,'upload');
//		$this->log($model->validationErrors,'upload');
            $this->statistics['failure'] = $this->statistics['failure'] + 1;
            throw new Exception(join(', ', array_values(array_merge($model->validationErrors, $ext_errors))));
        }
        if ($on_error)
        {
            $this->statistics['success'] = 0;
            $this->statistics['failure'] = $this->statistics['failure'] + 1;
            return true;
        }
//		# 如果重复， 则返回重复的记录 ， 否返回空
        $duplicate_method = "check_duplicate_for_upload";
        if (method_exists($model, $duplicate_method))
        {

            $record = $model->{$duplicate_method}($d);
            if (!empty($record))
            {
                # 如果重复
                $this->statistics['duplicate'] = $this->statistics['duplicate'] + 1;
                $record_id = $record[$model->alias][$model->primaryKey];
                # 忽略

                if ($duplicate_type == 'ignore')
                {
                    return true;
                }
                if ($duplicate_type == 'overwrite')
                {
                    $d[$model->alias][$model->primaryKey] = $record_id;
                }
                if ($duplicate_type == 'delete')
                {
                    $model->del($record_id);
                }
            }
        }
        $saved_data = $model->save($d, false);
        if ($saved_data)
        {
            # 保存成功
            $saved_data[$model->alias][$model->primaryKey] = $model->id;
            $after_save_method = "after_save_for_upload";
            if (method_exists($model, $after_save_method))
            {
                $model->{$after_save_method}($saved_data);
            }
            $this->statistics['success'] = $this->statistics['success'] + 1;
            return true;
        } else
        {
            # 保存失败
            $this->statistics['failure'] = $this->statistics['failure'] + 1;
            $db = & ConnectionManager::getDataSource($model->useDbConfig);
            throw new Exception($db->error);
        }
    }

    #

    function _check_upload_columns($schema, $fields, $fd)
    {
        $data = fgetcsv($fd, 1024, self::CSV_DELIMITER);
        $col_num = count($fields);
        if ($col_num > count($data))
        {
            return false;
        }
        for ($i = 0; $i < $col_num; $i++)
        {
            $field_name = $fields[$i];
            $name = Inflector::humanize($data[$i]);
//		pr("'{$data[$i]}' => '{$fields[$i]}'");
//		pr($schema[$fields[$i]]);								
            if (!( $data[$i] == $fields[$i] || # 列名是否与字段名相同
                    $name == Inflector::humanize($field_name) || # 是否与humanize 之后的字段名相同
                    ( isset($schema[$fields[$i]]['name']) && (  # 如果有名字
                    $data[$i] == $schema[$field_name]['name'] || #   是否与名字相同
                    $name == Inflector::humanize($schema[$field_name]['name']) ) # 是否与humanize 之后的名字相同
                    )
                    ))
            {
                return false;
            }
        }
        return true;
    }

    function _get_schema($model)
    {
        if (isset($model->upload_schema))
        {
            return $model->upload_schema;
        }
        if (isset($model->default_schema))
        {
            return $model->default_schema;
        }
        return $model->schema();
    }

    function _move_upload_file($model)
    {
        $model_name = $model->alias;
        App::import("Core", "Folder");
        $path = APP . 'tmp' . DS . 'upload' . DS . $model_name . DS . gmdate("Y-m-d", time());
        
        if (new Folder($path, true, 0777))
        {
            $file = $path . DS . time() . ".csv";
            move_uploaded_file($_FILES ["file"] ["tmp_name"], $file);
            return $file;
        } else
        {
            throw new Exception("Create File Error,Please Contact Administrator.");
        }
    }
    

#    function analysis_file($type, $filename)
#    {
#        //ini_set('auto_detect_line_endings', true);
#        
#        $path = APP . 'tmp' . DS . 'upload' . DS . 'csv';
#        
#        $type = (int)$type;
#        
#        switch ($type)
#        {
#            case 1:
#                $schema = $this->requestAction('/down/get_schema_block');
#                break;
#            case 2:
#                $schema = $this->requestAction('/down/get_schema_route_plan');
#                break;
#            case 3:
#                $schema = $this->requestAction('/down/get_schema_static_route');
#                break;
#            case 4:
#                $schema = $this->requestAction('/down/get_schema_code_deck');
#                break;
#            case 5:
#                $schema = $this->requestAction('/down/get_schema_jurisdiction');
#                break;
#            case 6:
#                $schema = $this->requestAction('/down/get_schema_digit_mapping');
#                break;
#            case 7:
#                $schema = $this->requestAction('/down/get_schema_digit_mapping_2');
#                break;
#            case 8:
#                $schema = $this->requestAction('/down/get_schema_action');
#                break;
#            case 9:
#                $schema = $this->requestAction('/down/get_schema_host');
#                break;
#            case 10:
#                $schema = $this->requestAction('/down/get_schema_ingress');
#                break;
#            case 11:
#                $schema = $this->requestAction('/down/get_schema_egress');
#                break;
#            case 12:
#                $this->loadModel('RateTable');
#                $schema = $this->RateTable->default_schema;
#                $path = Configure::read('rateimport.put');
#                break;
#            case 13:
#                $schema = $this->requestAction('/down/get_schema_special_code');
#                break;
#            case 14:
#                $schema = $this->requestAction('/down/get_schema_did');
#                break;
#        }
#        
#        
#        $fields = array_keys($schema);
#        
#        $abspath = $path . DS . $filename .".csv";
#        
#        $cmd = "sed -i 's/\\r/\\n/g' {$abspath}";
#        $result = shell_exec($cmd);
#        
#        if ($this->RequestHandler->ispost())
#        {
#            $log_model = new ImportExportLog ();
#            $new_columns = $_POST['columns'];
#            $new_columns_str = implode(',', $new_columns);
#            $cmd = "sed -i '1s/.*/{$new_columns_str}/g' {$abspath}";
#            shell_exec($cmd);
#            $this->Session->write('m', $log_model->create_json(201, __('Your fields is modified successfully', true)));
#        }
#        
#        
#        $table = array();
#        $row = 1;
#        
#        $handle = popen("head -n 21 {$abspath}", "r");
#        
#        while ($row <= 21 && $data = fgetcsv($handle, 1000, ","))
#        {
#            $row++;
#            array_push($table, $data);
#        }
#        
#        pclose($handle);
#        $this->set('table', $table);
#        $this->set('columns', $fields);
#    }

function analysis_file($type, $filename)
{
//ini_set('auto_detect_line_endings', true);
 
$path = APP . 'tmp' . DS . 'upload' . DS . 'csv';
 
$type = (int)$type;
 
switch ($type)
{
case 1:
$schema = $this->requestAction('/down/get_schema_block');
break;
case 2:
$schema = $this->requestAction('/down/get_schema_route_plan');
break;
case 3:
$schema = $this->requestAction('/down/get_schema_static_route');
break;
case 4:
$schema = $this->requestAction('/down/get_schema_code_deck');
break;
case 5:
$schema = $this->requestAction('/down/get_schema_jurisdiction');
break;
case 6:
$schema = $this->requestAction('/down/get_schema_digit_mapping');
break;
case 7:
$schema = $this->requestAction('/down/get_schema_digit_mapping_2');
break;
case 8:
$schema = $this->requestAction('/down/get_schema_action');
break;
case 9:
$schema = $this->requestAction('/down/get_schema_host');
break;
case 10:
$schema = $this->requestAction('/down/get_schema_ingress');
break;
case 11:
$schema = $this->requestAction('/down/get_schema_egress');
break;
case 12:
$this->loadModel('RateTable');
$schema = $this->RateTable->default_schema;
$path = Configure::read('rateimport.put');
break;
case 13:
$schema = $this->requestAction('/down/get_schema_special_code');
break;
case 14:
$schema = $this->requestAction('/down/get_schema_did');
break;
}
 
 
$fields = array_keys($schema);
 
$abspath = $path . DS . $filename .".csv";
 
$cmds = array();
array_push($cmds, "'s/\\r/\\n/g'");
array_push($cmds, "'/^$/d'");
$cmd_str = implode(' -e ', $cmds);
$cmd2 = "sed -i -e {$cmd_str} {$abspath}";
shell_exec($cmd2);
 
if ($this->RequestHandler->ispost())
{
$log_model = new ImportExportLog ();
$new_columns = $_POST['columns'];
$new_columns_str = implode(',', $new_columns);
$cmd = "sed -i '1s/.*/{$new_columns_str}/g' {$abspath}";
shell_exec($cmd);
$this->Session->write('m', $log_model->create_json(201, __('Your fields
is modified successfully', true)));
}
 
 
$table = array();
$row = 1;
 
$handle = popen("head -n 21 {$abspath}", "r");
 
while ($row <= 21 && $data = fgetcsv($handle, 1000, ","))
{
$row++;
array_push($table, $data);
}
 
pclose($handle);
$this->set('table', $table);
$this->set('columns', $fields);
}
       
    function async_upload()
    {
        /*
        if(isset($_COOKIE['PHPSESSID'])) {
            session_id($_COOKIE['PHPSESSID']);
        }
         * 
         */
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        App::import("Core", "Folder");
        $path = APP . 'tmp' . DS . 'upload' . DS . 'csv';
        if (!empty($_FILES)) {
                $fileName = time() . '_' .uniqid();
                $targetFile = $path . DS  . $fileName  . ".csv";

                // Validate the file type
                $fileTypes = array('csv', 'xls', 'xlsx', 'txt'); // File extensions
                $fileParts = pathinfo($_FILES['Filedata']['name']);
                /*
                if (in_array($fileParts['extension'],$fileTypes) or TRUE) {
                            new Folder($path, true, 0777);
                            move_uploaded_file($_FILES['Filedata']['tmp_name'],$targetFile);
                            echo $fileName;
                } else {
                        echo 'Invalid file type.' . $fileParts['extension'];
                }
                */
                if (in_array($fileParts['extension'],$fileTypes)) {
                        if ($fileParts['extension'] == 'xls' || $fileParts['extension']  == 'xlsx') {
                            $targetFile1 = $path . '/' . $fileName . ".". $fileParts['extension'];
                            move_uploaded_file($_FILES['Filedata']['tmp_name'],$targetFile1);
                            Configure::load('myconf');
                            $script_path = Configure::read('script.path');
                            $script_file_path = $script_path . DS . "xls2csv.pl";
                            $cmd = "perl $script_file_path -s '$targetFile1' -d {$targetFile} -t {$fileParts['extension']} ";
                            shell_exec($cmd);
                            file_put_contents('/tmp/test', $cmd);
                        } else {
                            move_uploaded_file($_FILES['Filedata']['tmp_name'],$targetFile);
                        }
                        echo $fileName;
                } else {
                        $this->header('HTTP/1.1 403 Forbidden');
                        echo 'Sorry! We are unable to recognize your file　format.';
                }
        }
    }

    function _new_error_file($model)
    {
        $model_name = $model->alias;
        App::import("Core", "File");
        $file = APP . 'tmp' . DS . 'upload' . DS . $model_name . DS . gmdate("Y-m-d", time()) . DS . 'error' . DS . time() . '.csv';
        $file = new File($file, true, 0777);
        if ($file)
        {
            return $file;
        } else
        {
            throw new Exception("Create Error File Error,Please Contact Administrator.");
        }
    }

    #

    function _get_duplicate_type($log = null)
    {
        if ($log)
        {
            return array_keys_value($log, 'ImportExportLog.duplicate_type');
        }
        $duplicate_type = strtolower(trim($this->params['form']['duplicate_type']));
        if (!in_array($duplicate_type, array('ignore', 'overwrite', 'delete', 'delete all', 'delete_all')))
        {
            $duplicate_type = 'ignore';
        }
        return $duplicate_type;
    }

    function _is_rollback_on_error($log = null)
    {
        if ($log)
        {
            return array_keys_value($log, 'ImportExportLog.ext_attributes.rollback_on_error');
        }
        if (isset($this->params['form']['rollback_on_error']) && $this->params['form']['rollback_on_error'] == 'on')
        {
            return true;
        }
        return false;
    }

    function _is_with_headers($log = null)
    {
        if ($log)
        {
            return array_keys_value($log, 'ImportExportLog.ext_attributes.with_headers');
        }
        if (isset($this->params['form']['with_headers']) && $this->params['form']['with_headers'] == 'on')
        {
            return true;
        }
        return false;
    }

    function upload_type_array($model)
    {
        $arr = array(
            'Ingress' => '1',
            'Egress' => '2',
            'ResourceIp' => '3',
            'ResourceDirection' => '4',
            'ResourceTranslation' => '5',
            'DigitTranslation' => '6',
            'ResourceBlock' => '7',
            'JurisdictionUpload' => '8',
            'Code' => '9',
            'RateTable' => '10',
            'Productitem' => '11',
            'Route' => '12',
            'DidSpecialCode' => '14',
            'DID'   => '13',
        );

        if (isset($arr[$model]))
        {
            return $arr[$model];
        } else
        {

            return null;
        }
    }

    function get_foreign_name($model_name, $save_ext_attributes)
    {
        
        if (empty($save_ext_attributes) || count($save_ext_attributes) == 0)
        {
            return '';
        } else
        {
            foreach ($save_ext_attributes as $key => $value)
            {
                $id = $value;
            }
        }
        
        App::import("Model", $model_name);
        $model = new $model_name();
        
        if (method_exists($model, 'get_foreign_name'))
        {
            $id = intval($id);
            $list = $model->get_foreign_name($id);
            if (!empty($list[0][0]['name']))
            {
                return $list[0][0]['name'];
            }
        } else
        {
            
            return '';
        }
    }

    function _log($model_name, $upload_file, $status = ImportExportLog::STATUS_SUCCESS, $error_file = '', $save_ext_attributes = array())
    {     
            
        $user_id = 0;
        if (isset($_SESSION ['sst_user_id']))
        {
            $user_id = $_SESSION ['sst_user_id'];
        }
        $export_log = new ImportExportLog();
        $data = array();
        $data ['ImportExportLog'] = array();
        //$data ['ImportExportLog']['status']= $status;
        if (isset($this->statistics['log_id']))
        {
            $id = (int) $this->statistics['log_id'];
        } else
        {
            $id = 0;
        }
        if ($id <= 0)
        {
            $data ['ImportExportLog']['ext_attributes'] = array();
            $data ['ImportExportLog']['ext_attributes']['save_ext_attributes'] = $save_ext_attributes;
            $data ['ImportExportLog']['ext_attributes']['rollback_on_error'] = $this->_is_rollback_on_error();
            $data ['ImportExportLog']['ext_attributes']['with_headers'] = $this->_is_with_headers();
            $data ['ImportExportLog']['duplicate_type'] = $this->_get_duplicate_type();
            //$data ['ImportExportLog']['auto_enddate'] = isset($this->params['form']['auto_enddate']) && $this->params['form']['auto_enddate'] == '0' ? 0 : 1;
            //$data ['ImportExportLog']['custom_end_date'] = isset($this->params['form']['is_custom_enddate']) && $this->params['form']['is_custom_enddate'] != '0' ? $this->params['form']['custom_date'] : NULL;
            $data ['ImportExportLog']['error_rollback'] = $this->_is_rollback_on_error();
            $data ['ImportExportLog']['time'] = gmtnow();
            $data ['ImportExportLog']['obj'] = $model_name;
            $data ['ImportExportLog']['file_path'] = $upload_file;
            if (empty($error_file))
            {
                $error_file = $upload_file . '.error';
                new File($error_file, true, 0777);
            }
            $data ['ImportExportLog']['error_file_path'] = $error_file;
            $data ['ImportExportLog']['user_id'] = $user_id;              
            $data ['ImportExportLog']['log_type'] = ImportExportLog::LOG_TYPE_IMPORT;
            $data ['ImportExportLog']['upload_type'] = $this->upload_type_array($model_name);
            $data ['ImportExportLog']['upload_table'] = strtolower($model_name) . time();
            $data ['ImportExportLog']['foreign_id'] = $save_ext_attributes;
            $data ['ImportExportLog']['foreign_name'] = $this->get_foreign_name($model_name, $save_ext_attributes);
            
            if (!empty($save_ext_attributes))
            {
                foreach ($save_ext_attributes as $key => $value)
                {
                    $data ['ImportExportLog']['foreign_id'] = $value;
                }
            }
            
        } else
        {
            $data ['ImportExportLog']['id'] = $id;
            $data ['ImportExportLog']['finished_time'] = gmtnow();
            if ($this->statistics['failure'] > 0)
            {
                $data ['ImportExportLog']['error_file_path'] = $error_file;
            } else
            {
                $data ['ImportExportLog']['error_file_path'] = '';
            }
        }

        
        //pr($export_log);
        $export_log->save($data);
        
        $this->statistics['log_id'] = $export_log->id;
        //$export_log->query("UPDATE import_export_logs set auto_enddate = {$data ['ImportExportLog']['auto_enddate']} WHERE id = {$export_log->id}");
        //pr($this->statistics);
        //$this->_exe_upload_shell($model_name);
        $this->_exec_script($export_log->id, $save_ext_attributes);
    }
    
    public function _exec_script($id, $attrs)
    {
        $script_path = Configure::read('script.path');
        $perl_path = $script_path . DS . 'class4_upload_check.pl';
        $perl_conf  = Configure::read('script.conf');
        if(is_array($attrs) && count($attrs))
        {
            $foreign_id = array_shift($attrs);
            if (trim($foreign_id) == '') $foreign_id = '0';
            $foreign_param = "-f {$foreign_id}";
        }
        else
        {
            $foreign_param = "";
        }
        $cmd = "perl $perl_path -c $perl_conf -i {$id} {$foreign_param} > /dev/null 2>&1 &";
        if (Configure::read('cmd.debug')) {
            echo $cmd;exit;
        }
        
        shell_exec($cmd);
    }

    function reprocess($id = null)
    {
        $this->loadModel('ImportExportLog');
        $list = $this->ImportExportLog->find('first', array('conditions' => array('ImportExportLog.id' => $id)));
        $data = $list['ImportExportLog'];
        $data['id'] = false;
        $model_name = $data ['obj'];
        $data ['upload_table'] = strtolower($model_name) . time();

        $this->ImportExportLog->save($data);
        //	pr($list);
        $this->_exe_upload_shell($model_name);
        $this->redirect("/import_export_log/import");
    }

    function _upload_null()
    {
        
    }

    function get_upload_log()
    {
        //$id=693;
        Configure::write('debug', 0);
        $this->layout = "ajax";
        $id = $this->_get("id");
        if (!empty($id))
        {
            $log_model = new ImportExportLog ();
            $log = $log_model->find('first', array('conditions' => "id = $id"));
        }
//			var $statistics = array(
//		'success' => 0,
//		'failure' => 0,
//		'duplicate' => 0,
//		'error_file' => '',
//		'log_id' => 0,
//	);

        $this->statistics['success'] = $log['ImportExportLog']['success_numbers'];

        #对费率的特殊处理
        if ($log['ImportExportLog']['upload_type'] == 10)
        {
            $this->statistics['success'] = $log['ImportExportLog']['php_process_number'];
        }
        $this->statistics['failure'] = $log['ImportExportLog']['error_row'];
        $this->statistics['duplicate'] = $log['ImportExportLog']['duplicate_numbers'];
        $this->statistics['log_id'] = $log['ImportExportLog']['id'];
        $this->statistics['error_file'] = $log['ImportExportLog']['error_file_path']; #web check error
        $this->statistics['db_error_file'] = $log['ImportExportLog']['db_error_file_path']; #database check  error
        $this->statistics['status'] = $log['ImportExportLog']['status'];
        $this->set('statistics', $this->statistics);
    }

    function get_upload_process_log()
    {
        Configure::write('debug', 0);
    }

    function output_upload_status()
    {
        
    }

    public function select_name($id, $modelName = null)
    {
        $sql = '';
        $name = '';
        if ($modelName == 'DigitTranslation')
        {
            $sql = "select translation_name as name from digit_translation where translation_id = $id";
        } else if ($modelName == 'RateTable')
        {
            $sql = "select name from rate_table where rate_table_id=$id";
        } else if ($modelName == 'Productitem')
        {
            $sql = "select name from  product where product_id=$id";
        } else if ($modelName == "Route")
        {
            $sql = "  select name from route_strategy where  route_strategy_id=$id";
        } else if ($modelName == 'Code')
        {
            $sql = "select name from code_deck where code_deck_id=$id";
        }
        $this->loadModel($modelName);
        if (!empty($id))
        {
            $name = $this->$modelName->query($sql);
        }
        return $name;
    }

}

?>
