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
                
    </select>'))?>
        <tr>
            <?php echo $this->element('search_report/orig_carrier_select');?>
            <td class="in-out_bound">&nbsp;</td>

            <td class="label"><?php echo __('Ingress',true);?>:</td>
            <td class="value">
            <?php 
            echo $form->input('ingress_alias',
            array('options'=>$ingress,'label'=>false ,'div'=>false,'type'=>'select'));
            ?>
            <?php echo $this->element('search_report/ss_clear_input_select');?></td>
            <td class="in-out_bound">&nbsp;</td>
            <td class="label"><?php echo __('Class4-server',true);?>:</td>
        <td class="value"><?php 
					 				echo $form->input('server_ip',array('options'=>$server,'empty'=>'','label'=>false ,'div'=>false,'type'=>'select'));
					 		?></td>
        </tr>
        
        <tr>
            <td class="label"><?php echo __('dnis',true);?> :</td>
            <td class="value"><input type="text" id="query-dst_number" value=""
                                    name="query[dst_number]" class="input in-text"><?php echo $this->element('search_report/ss_clear_input_select');?></td>
            <td class="in-out_bound">&nbsp;</td>
            <td class="label"><?php echo __('ani',true);?>:</td>
            <td class="value"><input type="text" id="query-src_number" value=""
                                    name="query[src_number]" class="input in-text">
                                    <?php echo $this->element('search_report/ss_clear_input_select');?></td>
            <td class="in-out_bound">&nbsp;</td>
            <?php  if ($_SESSION['role_menu']['Statistics']['cdrreports:spam']['model_x']) {?>
        <td class="label"></td>
        <td class="value"></td>
          <?php }?>
         </tr>
         
         <tr>
            <td class="label"><?php echo __('LRN number vendor',true);?> :</td>
            <td class="value">
                <select name="query[lrn_number_vendor]" id="query-number-vendor" class="input in-select">
                    <option value=""></option>
                    <option value="1">Client</option>
                    <option value="2">NPDB</option>
                    <option value="3">Cache</option>
                </select><?php echo $this->element('search_report/ss_clear_input_select');?>
            </td>
             <td class="in-out_bound">&nbsp;</td>
            <td class="label"><?php echo __('LRN number',true);?> :</td>
            <td class="value"><input type="text" id="query-number" value=""
                                    name="query[lrn_number]" class="input in-text"><?php echo $this->element('search_report/ss_clear_input_select');?></td>
                                     <td class="in-out_bound">&nbsp;</td>
            <td class="label"></td>
            <td class="value"></td>
         </tr>

      <!--<tr>
        <td colspan="8">
            <table style="width: 100%;">
            <tr  class="period-block">
              <td class="label"  style=" width:100px;text-align: left;"><span  style="color: #568ABC; font-size: 1.15em;font-weight: bold;"><?php echo __('Suppress Filter',true);?> </span></td>
              <td style="width:auto;"  colspan="5"></td>
              
            </tr>
             <tr> 
                <td >&nbsp;</td>
              <td style="width:350px;"><?php echo $this->element("search_report/checkbox_orig_host")?></td>
             <td ></td>
              <td></td>
            </tr>
          </table>
        </td>
      </tr>-->
    </tbody>
  </table>
</fieldset>
<?php echo $form->end();?>