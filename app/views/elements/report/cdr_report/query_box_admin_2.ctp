<fieldset class="query-box" style=" clear:both;overflow:hidden;margin-top:10px;">
  <div class="search_title"><img src="<?php
echo $this->webroot?>images/search_title_icon.png" />
    <?php __('search')?>
  </div>
  <?php echo $this->element('search_report/search_js');?>
  <?php 	echo $this->element('search_report/search_js_show');?>
  <?php
$url="/".$this->params['url']['url'];
//if($rate_type=='spam'){$url='/cdrreports/summary_reports/spam/';}else{$url='/cdrreports/summary_reports/';}
echo $form->create ('Cdr', array ('type'=>'get','url' => $url ,'id'=>'report_form',
'onsubmit'=>"if ($('#query-output').val() == 'web') loading();"));?>
  <?php echo $appCommon->show_page_hidden();?> <?php echo $this->element('search_report/search_hide_input');?>
  <table class="form" style="width: 100%">
    <tbody>
      <?php echo $this->element('report/form_period',array('group_time'=>false, 'gettype'=>'<select id="query-output"
				onchange="repaintOutput();" name="query[output]"
				class="input in-select">
            <option value="web">Web</option>
            <option value="csv">Excel CSV</option>
            <option value="xls">Excel XLS</option>
            <option value="email">Email when done</option>
          </select>'
          ))?>
    </tbody>
  </table>
  <table class="form">
        <td><?php echo __('ani',true);?></td>
      <td>
            <input type="text" id="query-src_number"
				name="query[src_number]" class="input in-text"><?php echo $this->element('search_report/ss_clear_input_select');?>
        </td>
        <td><?php echo __('dnis',true);?></td>
        <td>
            <input type="text" id="query-dst_number" 
				name="query[dst_number]" class="input in-text"><?php echo $this->element('search_report/ss_clear_input_select');?>
        </td>
        <td><?php echo __('ip',true);?></td>
        <td>
            <input type="text" id="query-dst_number" value="<?php echo isset($_GET ['query'] ['origination_source_host_name']) ? $_GET ['query'] ['origination_source_host_name']: '' ?>"
				name="query[origination_source_host_name]" class="input in-text"><?php echo $this->element('search_report/ss_clear_input_select');?>
        </td>
  </table>
</fieldset>
<?php echo $form->end();?>

<div id="pop-div" class="pop-div" style="width: 320px; height: 80px; position: absolute; left: 50%; top: 50%; z-index: 9999; margin-top: 0px;display:none;">
    <label style="color:red;">You did not set your email address!</label>
    <input type="text" id="send_email" />
    <input type="button" value="submit" id="send_email_btn" />
</div>

<script type="text/javascript">
    function getTechPrefix(obj){
           $("#CdrRoutePrefix").empty();
           $("#CdrRoutePrefix").append("<option value=''>All</option>");
        if($(obj).val() != '0'){
            $.post("<?php echo $this->webroot?>cdrreports/getTechPerfix", {ingId:$(obj).val()}, 
            function(data){
                $.each(eval(data),
                    function (index,content){
                       $("#CdrRoutePrefix").append("<option value='"+content[0]['tech_prefix']+"'>"+content[0]['tech_prefix']+"</option>");
                    }
                );
            });
            
        }
    }
    
    $(function() {
        $('#formquery').click(function() {
            if($('#query-output').val() == 'email') {
                $.ajax({
                    'url'      : '<?php echo $this->webroot ?>cdrreports/check_email',
                    'type'     : 'GET',
                    'dataType' : 'text',
                    'async'    : false,
                    'success'  : function(data) {
                        if(data == '0') {
                            $('#pop-div').show();
                            $('#send_email_btn').click(function() {
                                var email = $('#send_email').val();
                                $.ajax({
                                    'url'      : '<?php echo $this->webroot ?>cdrreports/update_email',
                                    'type'     : 'POST',
                                    'dataType' : 'text',
                                    'data'     : {'email':email},
                                    'success'  : function(data) {
                                        $('#report_form').submit();
                                    }
                                });
                            });
                        } else {
                                $('#report_form').submit();
                        }
                        return false;
                    }
                });
                return false;
            }
        });
    });
</script>