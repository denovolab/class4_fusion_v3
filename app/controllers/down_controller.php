<?php 


class DownController extends AppController
{
    var $name = "Down";
    var $uses = array('Cdr');
    
    function beforeFilter()
    {
        Configure::load('myconf');
    	return true;
    }
    
    public function index()
    {
    	
    }
    
    
    
    public function block()
    {
        $fields = $this->get_schema_block();
    	
    	if($this->RequestHandler->isPost())
    	{
    		$table = "resource_block";
    		$where = "";
    		$this->handle_data($fields, $table, $where, 'Block');
    	}
    	
    	$keys = array_keys($fields);
    	$this->set('fields', $keys);
    	$this->set('header', 'Routing &gt;&gt; Block &gt;&gt; Export');
    }
    
    public function routing_plan($id)
    {
        $fields = $this->get_schema_route_plan();
    	
    	if($this->RequestHandler->isPost())
    	{
    		$table = "route";
    		$where = "where  route_strategy_id  = $id";
    		$this->handle_data($fields, $table, $where, 'Routing_Plan');
    	}
    	
    	$keys = array_keys($fields);
    	$this->set('fields', $keys);
        $sql = "select name from route_strategy where route_strategy_id=$id";
        $result = $this->Cdr->query($sql);
        $name = $result[0][0]['name'];
    	$this->set('header', 'Routing &gt;&gt; Routing Plan [' . $name . '] &gt;&gt; Export');
        $this->set('id', $id);
    }
    
    public function static_route($route_id)
    {
        $fields = $this->get_schema_static_route();
    	
    	if($this->RequestHandler->isPost())
    	{
    		$table = "product_items_resource  left join product_items on product_items_resource.item_id = product_items.item_id";
    		$where = "where product_id = $route_id";
    		$this->handle_data($fields, $table, $where,'Static_Route');
    	}
    	
    	$keys = array_keys($fields);
    	$this->set('fields', $keys);
        $sql = "select name from product where product_id=$route_id";
        $result = $this->Cdr->query($sql);
        $name = $result[0][0]['name'];
    	$this->set('header', 'Routing &gt;&gt; Edit Static Route [' . $name . '] &gt;&gt; Export');
        $this->set('route_id', $route_id);
    }
    
    public function code_deck($code_deck_id)
    {
        $fields = $this->get_schema_code_deck();
    	
    	if($this->RequestHandler->isPost())
    	{
    		$table = "code";
    		$where = "where code_deck_id = {$code_deck_id}";
    		$this->handle_data($fields, $table, $where,'Code_Deck');
    	}
    	
    	$keys = array_keys($fields);
    	$this->set('fields', $keys);
        $sql = "select name from code_deck where code_deck_id=$code_deck_id";
        $result = $this->Cdr->query($sql);
        $name = $result[0][0]['name'];
    	$this->set('header', 'Switch &gt;&gt; Edit Code Deck List [' . $name . '] &gt;&gt; Export');
        $this->set('code_deck_id', $code_deck_id);
    }
    
    public function jurisdiction()
    {
        $fields = $this->get_schema_jurisdiction();
    	
    	if($this->RequestHandler->isPost())
    	{
    		$table = "jurisdiction_prefix";
    		$where = "";
    		$this->handle_data($fields, $table, $where, 'Jurisdiction');
    	}
    	
    	$keys = array_keys($fields);
    	$this->set('fields', $keys);
    	$this->set('header', 'Switch &gt;&gt; Jurisdiction');
    }
    
    public function digit_mapping()
    {
        //TODO 如何区分 egress_ngress
        $fields = $this->get_schema_digit_mapping();
    	
    	if($this->RequestHandler->isPost())
    	{
    		$table = "resource_translation_ref";
    		$where = "";
    		$this->handle_data($fields, $table, $where, 'Digits_Mapping');
    	}
    	
    	$keys = array_keys($fields);
    	$this->set('fields', $keys);
    	$this->set('header', 'Routing &gt;&gt; Ingress Trunk');
    }
    
    public function digit_mapping_down($translation_id)
    {
        //TODO 如何区分 egress_ngress
        $fields = $this->get_schema_digit_mapping_2();
    	
    	if($this->RequestHandler->isPost())
    	{
    		$table = "translation_item ";
    		$where = "where translation_id = {$translation_id}";
    		$this->handle_data($fields, $table, $where, 'Digits_Mapping');
    	}
    	$sql = "select translation_name as name from digit_translation where translation_id = {$translation_id}";
        $result = $this->Cdr->query($sql);
        $name = $result[0][0]['name'];
    	$keys = array_keys($fields);
    	$this->set('fields', $keys);
    	$this->set('header', 'Routing &gt;&gt; Digit Mapping [' . $name . ']');
        $this->set('id', $translation_id);
    }
    
    
    public function action()
    {
        //TODO 如何区分 egress_ngress
        $fields = $this->get_schema_action();
    	
    	if($this->RequestHandler->isPost())
    	{
            $type = $_POST['type'];
    		$table = "resource_direction inner join resource on resource_direction.resource_id = resource.resource_id and $type = true";
    		$where = "";
    		$this->handle_data($fields, $table, $where,'Resouce_Action');
    	}
    	
    	$keys = array_keys($fields);
    	$this->set('fields', $keys);
    	$this->set('header', 'Routing &gt;&gt; Ingress Trunk');
    }
    
    public function host()
    {
        //TODO 如何区分 egress_ngress
        $fields = $this->get_schema_host();
    	
    	if($this->RequestHandler->isPost())
    	{
                $type = $_POST['type'];
    		$table = "resource_ip inner join resource on resource_ip.resource_id = resource.resource_id and $type = true";
    		$where = "";
    		$this->handle_data($fields, $table, $where, 'Resource_Host');
    	}
    	
    	$keys = array_keys($fields);
    	$this->set('fields', $keys);
    	$this->set('header', 'Routing &gt;&gt; Ingress Trunk');
    }
    
    public function ingress()
    {
    	$fields = $this->get_schema_ingress();
    	
    	if($this->RequestHandler->isPost())
    	{
    		$table = "resource left join resource_prefix on resource_prefix.resource_id = resource.resource_id";
    		$where = "where ingress = true";
    		$this->handle_data($fields, $table, $where, 'Ingress');
    	}
    	
    	$keys = array_keys($fields);
    	$this->set('fields', $keys);
    	$this->set('header', 'Routing &gt;&gt; Ingress Trunk');
    }
    
    
    public function egress()
    {
    	$fields = $this->get_schema_egress();
    	
    	if($this->RequestHandler->isPost())
    	{
    		$table = "resource";
    		$where = "where egress = true";
    		$this->handle_data($fields, $table, $where, 'Egress');
    	}
    	
    	$keys = array_keys($fields);
    	$this->set('fields', $keys);
    	$this->set('header', 'Routing &gt;&gt; Egress Trunk');
    }
    
    public function down_csv($filename, $header_text, $footer_text)
    {
    	Configure::write('debug', 0);
    	$this->autoLayout = false;
    	$this->autoRender = false;
    	ob_clean();
    	$db_path = Configure::read('database_export_path');
    	$real_path = $db_path . DS . $filename;
    	header("Content-type:text/csv");
    	header("Content-Disposition:attachment;filename=".$filename);
    	header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
    	header('Expires:0');
    	header('Pragma:public');
    	if($header_text)
    		echo $header_text . "\n";
    	readfile($real_path);
        if($footer_text)
                echo $footer_text . "\n";
    	exit();
    }
    
    public function down_xls($filename, $header_text, $footer_text)
    {
    	Configure::write('debug', 0);
    	$this->autoLayout = false;
    	$this->autoRender = false;
    	ob_clean();
    	$db_path = Configure::read('database_export_path');
    	$real_path = $db_path . DS . $filename;
    	$new_filename_info = pathinfo($filename);
    	$filename = $new_filename_info['filename'] . '.xls';
    	header("Pragma: public");
    	header("Expires: 0");
    	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    	header("Content-Type: application/force-download");
    	header("Content-Type: application/octet-stream");
    	header("Content-Type: application/download");;
    	header("Content-Disposition: attachment;filename=".$filename);
    	header("Content-Transfer-Encoding: binary ");
        if($header_text)
    		echo $header_text . "\n";
    	$handle = fopen($real_path, "r");
    	while ($row = fgetcsv($handle, 1000 , ","))
    	{
    		echo implode("\t", $row);
    		echo "\n";
    	}
    	fclose($handle);
        if($footer_text)
                echo $footer_text . "\n";
    	exit();
    }
    
    public function handle_data($schema, $table, $where, $name)
    {
   		$filename = $name . '_' . date('Y_m_d_H_i_s') . '_' . uniqid() . '.csv';
    	$with_header = isset($_POST['with_header']) ? 'WITH CSV HEADER' : '';
    	$header_text = !empty($_POST['header_text']) ? $_POST['header_text'] : false;
    	$footer_text = !empty($_POST['footer_text']) ? $_POST['footer_text'] : false;
    	$data_format = $_POST['data_format'];
    	$fields = $_POST['fields'];
    	$field_arr = array();
    	foreach($fields as $field)
    	{
    		if(!empty($field))
    			array_push($field_arr, "{$schema[$field]} as {$field}");
    	}
    	if (count($field_arr))
    	{
    		$sql = "COPY (SELECT " . implode(',', $field_arr) .
    			   #" FROM {$table} {$where}) TO '/tmp/exports/{$filename}' {$with_header}";
                           " FROM {$table} {$where}) TO '" . Configure::read('database_actual_export_path') . "/{$filename}' {$with_header} "; 

                        #        " FROM {$table} {$where}) TO '" . Configure::read('database_export_path') . "/{$filename}' {$with_header}";
    	
#echo $sql;exit;
        	$this->Cdr->query($sql);
                $sql = "insert into import_export_logs
(log_type, file_path, user_id, status, time, obj) values (0, '{$filename}', {$_SESSION['sst_user_id']}, 6, CURRENT_TIMESTAMP(0), '{$name}')";
                $this->Cdr->query($sql);
    		if($data_format == 0)
    		{
    			$this->down_csv($filename, $header_text, $footer_text);
    		}
    		else
    		{
    			$this->down_xls($filename, $header_text, $footer_text);
    		}
    	}
    	else
    	{
    		exit();
    	}
    }
    
    
    public function get_schema_ingress()
    {
        $fields = array(
            'trunk_id' => 'resource.resource_id',
            'trunk_name' => 'alias',
            'carrier_name' => '(SELECT name FROM client WHERE client_id = resource.client_id)',
            'media_type' => "(case media_type when 1 then 'proxy' when 2 then 'bypass' end)",
            'cps_limit'  => "cps_limit",
            'call_limit' => 'capacity',
            'protocol'   => "(case proto when 1 then 'sip' when 2 then 'h323' when 3 then 'all' end)",
            'pdd_timeout' => 'ring_timeout',
            'ignore_early_media' => "(case when ignore_ring = false and ignore_early_media = false then 'None' when ignore_ring = true and ignore_early_media = true then '180 and 183' when ignore_ring =true and ignore_early_media = false then '180' when ignore_ring = false and ignore_early_media = true then '183' end)",
            'active' => "(case active when true then 'true' else 'false' end)",
            't38' => "(case t38 when true then 'enable' else 'disable' end)",
            'rfc2833' => "(case rfc_2833 when true then 'true' else 'false' end)",
            'dip_from' => "(case lnp_dipping when true then 'client' else 'server' end)",
            'min_duration' => 'delay_bye_second',
            'max_duration' => 'max_duration',
            'lrn_block' => "(case lrn_block when true then 'true' else 'false' end)",
            'rate_table_name' => '(SELECT name FROM rate_table WHERE rate_table_id = resource_prefix.rate_table_id)',
            'route_strategy_name' => '(SELECT name FROM route_strategy WHERE route_strategy_id = resource_prefix.route_strategy_id)',
            'tech_prefix' => 'resource_prefix.tech_prefix',
        );
        
        return $fields;
    }
    
    
    public function get_schema_egress()
    {
        $fields = array(
            'trunk_id' => 'resource_id',
            'trunk_name' => 'alias',
            'carrier_name' => '(SELECT name FROM client WHERE client_id = resource.client_id)',
            'media_type' => "(case media_type when 1 then 'proxy' when 2 then 'bypass' end)",
            'call_limit' => 'capacity',
            'cps_limit'  => "cps_limit",
            'protocol'   => "(case proto when 1 then 'sip' when 2 then 'h323' when 3 then 'all' end)",
            'pdd_timeout' => 'ring_timeout',
            'active' => "(case active when true then 'true' else 'false' end)",
            't38' => "(case t38 when true then 'enable' else 'disable' end)",
            'rate_table_name' => '(select name from rate_table where rate_table_id = resource.rate_table_id)',
            'host_route_strategy' => "(case res_strategy when 1 then 'top-down' else 'round-robin' end)",
            'rfc2833' => "(case rfc_2833 when true then 'true' else 'false' end)",
            'pass_dip_head' => "(case lnp_dipping when true then 'true' else 'false' end)",
            'min_duration' => 'delay_bye_second',
            'max_duration' => 'max_duration',
            'lrn_block' => "(case lrn_block when true then 'true' else 'false' end)",
        );
        
        return $fields;
    }
    
    
    public function get_schema_host()
    {
        $fields = array(
            'trunk_name' => '(select alias from resource where resource_id = resource_ip.resource_id)',
            'ip' => 'ip',
            'port' => "port"
        );
        
        return $fields;
    }
    
    public function get_schema_action()
    {
        $fields = array(
            'trunk_name' => '(select alias from resource where resource_id = resource_direction.resource_id)',
            'time_profile_name' => '(select name from time_profile where time_profile_id = resource_direction.time_profile_id)',
            'target' => "(case type when 0 then 'ani' else 'dnis' end)",
            'code' => 'dnis',
            'action' => "(case action when 1 then 'add_prefix' when 2 then 'add_suffix' when 3 then 'del_prefix' when 4 then 'del_suffix' end)",
            'chars' => 'digits',
            'number_type' => "(case number_type when 0 then 'all' when 1 then '>' when 2 then '=' when 3 then '<' end)",
            'number_length' => 'number_length',
        );
        
        return $fields;
    }
    
    public function get_schema_digit_mapping()
    {
        $fields = array(
            'trunk_name' => '(select alias from resource where resource_id = resource_translation_ref.resource_id)',
            'translation_name' => '(select translation_name from digit_translation where translation_id = resource_translation_ref.translation_id)',
            'time_profile_name' => "(select name from time_profile where time_profile_id = resource_translation_ref.time_profile_id)",
        );
        
        return $fields;
    }
    
    public function get_schema_block()
    {
        $fields = array(
            'trunk_name' => '(select alias from resource where resource_id = resource_block.ingress_res_id  or resource_id = resource_block.engress_res_id)',
            'ani' => 'ani_prefix',
            'dnis' => "digit",
            'time_profile_name' => "(select name from time_profile where time_profile_id = resource_block.time_profile_id)",
            'ani_method' => "(case ani_method when 0 then '<' when 1 then '=' when 2 then '>' end)",
            'ani_length' => 'ani_length',
            'dnis_method' => "(case dnis_method when 0 then '<' when 1 then '=' when 2 then '>' end)",
            'dnis_length' => 'dnis_length',
        );
        
        return $fields;
    }
    
    public function get_schema_jurisdiction()
    {
        $fields = array(
            'country' => 'jurisdiction_country_name',
            'state' => 'jurisdiction_name',
            'prefix' => 'prefix',
            'ocn' => 'ocn',
            'lata' => 'lata',
        );
        return $fields;
    }
    
    public function get_schema_code_deck()
    {
        $fields = array(
            'code' => 'code',
            'name' => 'name',
            'country' => 'country',
        );
        
        return $fields;
    }
    
    public function get_schema_special_code()
    {
        $fields = array(
                'code' => 'Code',
                'pricing' => 'Pricing',
            );
        
        return $fields; 
    }
    
    public function get_schema_did()
    {
        $fields = array(
                'number' => 'number',
            );
        
        return $fields; 
    }
    
    public function get_schema_static_route()
    {
        $fields = array(
            'code' => 'digits',
            'strategy' => "(case strategy when 0 then 'percentage' when 1 then 'top-down' when 2 then 'round-robin' end)",
            'trunk_name' => '(SELECT alias from resource where resource_id = product_items_resource.resource_id)',
            'percentage' => 'by_percentage',
            'time_profile_name' => "(select name from time_profile where time_profile_id = product_items.time_profile_id)",
        );
        
        return $fields;
    }
    
    public function get_schema_route_plan()
    {
        $fields = array(
            'ani' => "ani_prefix",
            'ani_min_length' => "ani_min_length",
            'ani_max_length' => "ani_max_length",
            'prefix' => 'digits',
            'route_type' => "(case route_type when 1 then 'dynamic' when 2 then 'static' when 3 then 'dynamic-static' when 4 then 'static-dynamic' end)",
            'dynamic_route_name' => "(select name from dynamic_route where dynamic_route.dynamic_route_id = route.dynamic_route_id)",
            'static_route_name' => "(select name from product where product_id = route.static_route_id)",
            'intra_static_route_name' => "(select name from product where product_id = route.intra_static_route_id)",
            'inter_static_route_name' => "(select name from product where product_id = route.inter_static_route_id)",
            'jurisdiction_country'    => " (select name from jurisdiction_country where jurisdiction_country.id = route.jurisdiction_country_id)",
        );
        
        return $fields;
    }
    
    public function get_schema_reset_balance()
    {
        $fields = array(
            'name' => 'name',
            'begin_date' => 'begin_date',
            'balance' => 'balance',
        );
        return $fields;
    }
    
    public function get_schema_digit_mapping_2()
    {
        $fields = array(
            'ani' => 'ani',
            'dnis' => 'dnis',
            'translated_ani' => 'action_ani',
            'translated_dnis' => 'action_dnis',
            'ani_method' => "(case ani_method when 0 then 'ignore' when 1 then 'compare' when 2 then 'replace' end)",
            'dnis_method' => "(case dnis_method when 0 then 'ignore' when 1 then 'compare' when 2 then 'replace' end)",
        );
        
        return $fields;
    }
    
}


?>
