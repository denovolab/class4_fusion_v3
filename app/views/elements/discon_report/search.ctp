<fieldset class="query-box" style=" clear:both;overflow:hidden;margin-top:10px;">
    <div class="search_title"><img src="<?php
        echo $this->webroot ?>images/search_title_icon.png"/>
        <?php __('search') ?>
    </div>
    <?php echo $this->element('search_report/search_js'); ?>
    <?php
    if ($rate_type == 'org') {
        $url = '/disconnectreports/summary_reports/org/';
    } else {
        $url = '/disconnectreports/summary_reports/term/';
    }
    echo $form->create('Cdr', array('type' => 'get', 'url' => $url, 'onsubmit' => "if ($('#query-output').val() == 'web') loading();")); ?>
    <?php echo $this->element('search_report/search_hide_input'); ?>
    <table class="form">
        <tbody>
        <tr class="period-block">
            <td class="label" style="width:80px"><?php __('time') ?>
                :
            </td>
            <td colspan="8" style="width:auto;">
                <table class="in-date">
                    <tbody>
                    <tr>
                        <td style="padding-right: 15px;"><?php
                            $r = array('custom' => __('custom', true), 'curDay' => __('today', true), 'prevDay' => __('yesterday', true), 'curWeek' => __('currentweek', true), 'prevWeek' => __('previousweek', true), 'curMonth' => __('currentmonth', true),
                                'prevMonth' => __('previousmonth', true), 'curYear' => __('currentyear', true), 'prevYear' => __('previousyear', true));
                            if (!empty($_GET)) {
                                if (isset($_GET['smartPeriod'])) {
                                    $s = $_GET['smartPeriod'];
                                } else {
                                    $s = 'curDay';
                                }
                            } else {
                                $s = 'curDay';
                            }
                            echo $form->input('smartPeriod',
                                array('options' => $r, 'label' => false,
                                    'onchange' => 'setPeriod(this.value)', 'id' => 'query-smartPeriod', 'style' => 'width:100px;', 'name' => 'smartPeriod',
                                    'div' => false, 'type' => 'select', 'selected' => $s)); ?></td>
                        <td><input type="text" style="width: 80px;" id="query-start_date-wDt"
                                   class="wdate in-text input" onChange="setPeriod('custom')" readonly="readonly"
                                   onkeydown="setPeriod('custom')" value="" name="start_date"></td>
                        <td><input type="text" id="query-start_time-wDt" onChange="setPeriod('custom')"
                                   onKeyDown="setPeriod('custom')" readonly="readonly" style="width: 60px;"
                                   value="00:00:00" name="start_time" class="input in-text">
                            &nbsp;&nbsp;&mdash;&nbsp;&nbsp;
                            <input type="text" id="query-stop_date-wDt" class="wdate in-text input" style="width: 80px;"
                                   onchange="setPeriod('custom')"
                                   readonly="readonly"
                                   onkeydown="setPeriod('custom')" value="" name="stop_date"></td>
                        <td><input type="text" id="query-stop_time-wDt" onChange="setPeriod('custom')"
                                   readonly="readonly"
                                   onkeydown="setPeriod('custom')" style="width: 60px;" value="23:59:59"
                                   name="stop_time" class="input in-text">
                            &nbsp;&nbsp;in&nbsp;&nbsp;
                            <select id="query-tz" style="width: 100px;" name="query[tz]" class="input in-select">
                                <option value="-1200">GMT -12:00</option>
                                <option value="-1100">GMT -11:00</option>
                                <option value="-1000">GMT -10:00</option>
                                <option value="-0900">GMT -09:00</option>
                                <option value="-0800">GMT -08:00</option>
                                <option value="-0700">GMT -07:00</option>
                                <option value="-0600">GMT -06:00</option>
                                <option value="-0500">GMT -05:00</option>
                                <option value="-0400">GMT -04:00</option>
                                <option value="-0300">GMT -03:00</option>
                                <option value="-0200">GMT -02:00</option>
                                <option value="-0100">GMT -01:00</option>
                                <option value="+0000">GMT +00:00</option>
                                <option value="+0100">GMT +01:00</option>
                                <option selected="selected" value="+0200">GMT +02:00</option>
                                <option value="+0300">GMT +03:00</option>
                                <option value="+0330">GMT +03:30</option>
                                <option value="+0400">GMT +04:00</option>
                                <option value="+0500">GMT +05:00</option>
                                <option value="+0600">GMT +06:00</option>
                                <option value="+0700">GMT +07:00</option>
                                <option value="+0800">GMT +08:00</option>
                                <option value="+0900">GMT +09:00</option>
                                <option value="+1000">GMT +10:00</option>
                                <option value="+1100">GMT +11:00</option>
                                <option value="+1200">GMT +12:00</option>
                            </select></td>
                        <td><?php
                            $r = array('' => __('alltime', true), 'YYYY-MM-DD HH24:00:00' => __('byhours', true), 'YYYY-MM-DD' => __('byday', true), 'YYYY-MM' => __('bymonth', true), 'YYYY' => __('byyear', true));
                            if (!empty($_GET)) {
                                if (isset($_GET['group_by_date'])) {
                                    $s = $_GET['group_by_date'];
                                } else {
                                    $s = '';
                                }
                            } else {
                                $s = '';
                            }
                            echo $form->input('group_by_date',
                                array('options' => $r, 'label' => false, 'id' => 'query-group_by_date', 'style' => 'width: 100px;', 'name' => 'group_by_date',
                                    'div' => false, 'type' => 'select', 'selected' => $s)); ?></td>
                        <td>
                            <select id="query-output" onChange="repaintOutput();" name="query[output]"
                                    class="input in-select">
                                <option selected="selected" value="web">
                                    <?php __('web') ?>
                                </option>
                                <?php if ($_SESSION['role_menu']['Statistics']['disconnectreports']['model_x']) { ?>
                                    <option value="csv">Excel CSV</option>
                                    <option value="xls">Excel XLS</option>
                                <?php } ?>
                                <!--<option value="delayed">Delayed CSV</option>
                                -->
                            </select>
                            <input type="submit" value="<?php echo __('query', true); ?>" class="input in-submit">
                        </td>
                    </tr>
                    </tbody>
                </table>
            </td>
        </tr>
        <?php if ($rate_type == 'org') { ?>
            <tr class="period-block" style="height:20px; line-height:20px;">
                <td colspan="2" style="text-align:center; font-size:14px;"><b><?php echo __('Inbound', true); ?></b>
                </td>
                <td class="in-out_bound">&nbsp;</td>
                <td class="label">&nbsp;</td>
                <td class="value"></td>
                <td style="width:3px;">&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>

                <?php echo $this->element('search_report/orig_carrier_select'); ?>
                <td class="in-out_bound">&nbsp;</td>
                <td class="label"> <?php echo __('class4-server', true); ?>:</td>
                <td class="value" colspan="4"><?php
                    echo $form->input('server_ip',
                        array('options' => $server, 'empty' => ' ', 'label' => false, 'div' => false, 'type' => 'select'));
                    ?>
                    <?php echo $this->element('search_report/ss_clear_input_select'); ?></td>
                <td style="width:3px;">&nbsp;</td>
            </tr>
            <tr>
                <td class="label"><?php __('ingress') ?>
                    <span class="tooltip" id="ht-100013-tooltip"><b>Use pre-assigned</b> &mdash; means usage of code decks assigned to each pulled client or rate table.<br>
<br>
If you will <b>specify</b> a code deck, all code names will be rewritten using names from selected code deck, so all data will be unified by code names.</span>:
                </td>
                <td class="value"><?php
                    echo $form->input('ingress_alias',
                        array('options' => $ingress, 'label' => false, 'div' => false, 'type' => 'select', 'onchange' => 'getTechPrefix(this);'));
                    ?>
                    <?php echo $this->element('search_report/ss_clear_input_select'); ?></td>
                <td class="in-out_bound">&nbsp;</td>
                <td class="label"></td>
                <td class="value"></td>
                <td style="width:3px;">&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td lass="label">Tech Prefix</td>
                <td class="value">
                    <select name="route_prefix" id="CdrRoutePrefix">
                        <option value="">
                            All
                        </option>
                        <?php
                        if (!empty($tech_perfix)) {
                            foreach ($tech_perfix as $te) {
                                if ($_GET['route_prefix'] == $te[0]['tech_prefix']) {
                                    echo "<option selected value='" . $te[0]['tech_prefix'] . "'>" . $te[0]['tech_prefix'] . "</option>";
                                } else {
                                    echo "<option value='" . $te[0]['tech_prefix'] . "'>" . $te[0]['tech_prefix'] . "</option>";
                                }
                            }
                        }
                        ?>
                    </select>
                </td>
                <td class="in-out_bound">&nbsp;</td>
            </tr>
            <tr>
                <?php echo $this->element('search_report/search_orig_country'); ?>
                <td class="in-out_bound">&nbsp;</td>
<!--                <td class="label">--><?php //echo __('dnis', true); ?><!-- :</td>-->
<!--                <td class="value"><input type="text" id="query-dst_number" value="" name="query[dst_number]"-->
<!--                                         class="input in-text">-->
<!--                    --><?php //echo $this->element('search_report/ss_clear_input_select'); ?><!--</td>-->
                <td></td>
                <td></td>
                <td></td>
                <td style="width:3px;">&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <?php echo $this->element('search_report/search_orig_code_name'); ?>
                <td class="in-out_bound">&nbsp;</td>
                <td></td>
                <td></td>
                <td></td>
<!--                <td class="label">--><?php //echo __('ani', true); ?><!--:</td>-->
<!--                <td class="value"><input type="text" id="query-src_number" value="" name="query[src_number]"-->
<!--                                         class="input in-text">-->
<!--                    --><?php //echo $this->element('search_report/ss_clear_input_select'); ?><!--</td>-->
                <td style="width:3px;">&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
<!--            <tr>-->
<!--                --><?php //echo $this->element('search_report/search_orig_code'); ?>
<!--                <td class="in-out_bound">&nbsp;</td>-->
<!--                <td class="label"></td>-->
<!--                <td class="value"></td>-->
<!--                <td style="width:3px;">&nbsp;</td>-->
<!--                <td>&nbsp;</td>-->
<!--                <td>&nbsp;</td>-->
<!--            </tr>-->
            <tr>
                <td class="label">Rate Type</td>
                <td class="value">
                    <select name="orig_rate_type" style="width:120px;">
                        <option value="0" <?php echo $common->set_get_select('orig_rate_type', 0); ?>>All</option>
                        <option value="1" <?php echo $common->set_get_select('orig_rate_type', 1); ?>>A-Z</option>
                        <option value="2" <?php echo $common->set_get_select('orig_rate_type', 2); ?>>US</option>
                        <option value="3" <?php echo $common->set_get_select('orig_rate_type', 3); ?>>OCN-LATA</option>
                    </select>
                    <?php echo $this->element('search_report/ss_clear_input_select'); ?>
                </td>
                <td class="in-out_bound label">&nbsp;</td>
                <td class="label">Rate Type</td>
                <td class="value" colspan="4">
                    <select name="term_rate_type" style="width:120px;">
                        <option value="0" <?php echo $common->set_get_select('term_rate_type', 0); ?>>All</option>
                        <option value="1" <?php echo $common->set_get_select('term_rate_type', 1); ?>>A-Z</option>
                        <option value="2" <?php echo $common->set_get_select('term_rate_type', 2); ?>>US</option>
                        <option value="3" <?php echo $common->set_get_select('term_rate_type', 3); ?>>OCN-LATA</option>
                    </select>
                    <?php echo $this->element('search_report/ss_clear_input_select'); ?>
                </td>
            </tr>
            <tr>
                <td class="label">Rate Table</td>
                <td class="value">
                    <select style="width:120px;" id="ingress_rate_table" name="ingress_rate_table">
                        <option value="all">
                            All
                        </option>
                        <?php
                        if (!empty($ingress_options['rate_tables'])) {
                            foreach ($ingress_options['rate_tables'] as $te) {
                                $isSelected = $_GET['ingress_rate_table'] == $te[0]['rate_table_id'] ? 'selected' : '';
                                echo "<option value='" . $te[0]['rate_table_id'] . "' " . $isSelected . ">" . $te[0]['rate_table_name'] . "</option>";
                            }
                        } else {
                            foreach ($rate_tables as $rate_table) {
                                $isSelected = $_GET['ingress_rate_table'] == $rate_table[0]['id'] ? 'selected' : '';
                                echo "<option value='" . $rate_table[0]['id'] . "' " . $isSelected . ">" . $rate_table[0]['name'] . "</option>";
                            }
                        }

                        ?>
                    </select>
                    <?php echo $this->element('search_report/ss_clear_input_select'); ?>
                </td>
                <td class="in-out_bound label">&nbsp;</td>
                <td class="label"></td>
                <td class="value">
                </td>
            </tr>
            <tr>
                <td class="label">Routing Plan:</td>
                <td class="value">
                    <select style="width:120px;" id="ingress_routing_plan" name="ingress_routing_plan">
                        <option value="all">
                            All
                        </option>
                        <?php
                        if (!empty($ingress_options['routing_plans'])) {
                            foreach ($ingress_options['routing_plans'] as $te) {
                                if ($_GET['ingress_routing_plan'] == $te[0]['route_strategy_id']) {
                                    echo "<option selected value='" . $te[0]['route_strategy_id'] . "'>" . $te[0]['route_strategy_name'] . "</option>";
                                } else {
                                    echo "<option value='" . $te[0]['route_strategy_id'] . "'>" . $te[0]['route_strategy_name'] . "</option>";
                                }
                            }
                        } else {
                            foreach ($routing_plans as $routing_plan) {
                                $isSelected = $_GET['ingress_routing_plan'] == $routing_plan[0]['id'] ? 'selected' : '';
                                echo "<option value='" . $routing_plan[0]['id'] . "' " . $isSelected . ">" . $routing_plan[0]['name'] . "</option>";
                            }
                        }

                        ?>
                    </select>
                    <?php echo $this->element('search_report/ss_clear_input_select'); ?>
                </td>
                <td class="in-out_bound label">&nbsp;</td>
                <td class="label"></td>
                <td class="value">
                </td>
            </tr>
            <!--
<tr>

<td class="label"> <?php __('codedecks') ?><span class="tooltip" id="ht-100001-tooltip"><b>Use pre-assigned</b> &mdash; means usage of code decks assigned to each pulled client or rate table.<br><br>If you will <b>specify</b> a code deck, all code names will be rewritten using names from selected code deck, so all data will be unified by code names.</span>:</td>
<td class="value">
<?php echo $form->input('code_deck', array('options' => $code_deck, 'empty' => ' ', 'label' => false, 'div' => false, 'type' => 'select')); ?>
</td>
</tr>
-->
            <!--
<tr>
<?php if ($rate_type == 'term') { ?>
<td class="label"><?php __('egress') ?></td>
<td class="value"><?php echo $form->input('egress_alias',
                array('options' => $egress, 'empty' => ' ', 'label' => false, 'div' => false, 'type' => 'select')); ?></td>
<?php } ?>
<?php if ($rate_type == 'org') { ?>
<td class="label"><?php __('ingress') ?>
<span class="tooltip" id="ht-100013-tooltip"><b>Use pre-assigned</b> &mdash; means usage of code decks assigned to each pulled client or rate table.<br>
<br>
If you will <b>specify</b> a code deck, all code names will be rewritten using names from selected code deck, so all data will be unified by code names.</span>:</td>
<td class="value"><?php
                echo $form->input('ingress_alias',
                    array('options' => $ingress, 'empty' => ' ', 'label' => false, 'div' => false, 'type' => 'select'));
                ?></td>
<?php } ?>
</tr>
-->
            <!--
            <tr>
            <td class="label"><span rel="helptip" class="helptip" id="ht-100002">Interval (sec)</span><span class="tooltip" id="ht-100002-tooltip">Duration interval in seconds</span>:</td>
            <td class="value"><input type="text" id="query-interval_from" class="in-digits input in-text" style="width: 53px;" value="" name="query[interval_from]">
            &mdash;
            <input type="text" id="query-interval_to" class="in-digits input in-text" style="width: 54px;" value="" name="query[interval_to]"></td>
            </tr>
            -->
        <?php }
        if ($rate_type == 'term') { ?>

            <tr class="period-block" style="height:20px; line-height:20px;">
                <td colspan="2" style="text-align:center;font-size:14px;"><b><?php echo __('Outbound', true); ?></b>
                </td>
                <td class="in-out_bound">&nbsp;</td>
                <td class="label">&nbsp;</td>
                <td class="value"></td>
                <td style="width:3px;">&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <?php echo $this->element('search_report/term_carrier_select'); ?>
                <td class="in-out_bound">&nbsp;</td>
                <td class="label"> <?php echo __('class4-server', true); ?>:</td>
                <td class="value" colspan="4"><?php
                    echo $form->input('server_ip',
                        array('options' => $server, 'empty' => ' ', 'label' => false, 'div' => false, 'type' => 'select'));
                    ?>
                    <?php echo $this->element('search_report/ss_clear_input_select'); ?></td>
                <td style="width:3px;">&nbsp;</td>
            </tr>
            <tr>
                <td class="label"><?php __('egress') ?></td>
                <td class="value"><?php echo $form->input('egress_alias',
                        array('options' => $egress, 'empty' => ' ', 'label' => false, 'div' => false, 'type' => 'select')); ?><?php echo $this->element('search_report/ss_clear_input_select'); ?></td>
                <td class="in-out_bound">&nbsp;</td>
                <td class="label"><?php __('output') ?></td>
                <td class="value"><select id="query-output" onChange="repaintOutput();" name="query[output]"
                                          class="input in-select">
                        <option selected="selected" value="web">
                            <?php __('web') ?>
                        </option>
                        <?php if ($_SESSION['role_menu']['Statistics']['disconnectreports']['model_x']) { ?>
                            <option value="csv">Excel CSV</option>
                            <option value="xls">Excel XLS</option>
                        <?php } ?>
                        <!--<option value="delayed">Delayed CSV</option>
                        -->
                    </select></td>
                <td style="width:3px;">&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <?php echo $this->element('search_report/search_term_country'); ?>
                <td class="in-out_bound">&nbsp;</td>
<!--                <td class="label">--><?php //echo __('dnis', true); ?><!-- :</td>-->
<!--                <td class="value"><input type="text" id="query-dst_number" value="" name="query[dst_number]"-->
<!--                                         class="input in-text">-->
<!--                    --><?php //echo $this->element('search_report/ss_clear_input_select'); ?><!--</td>-->
                <td style="width:3px;">&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <?php echo $this->element('search_report/search_term_code_name'); ?>
                <td class="in-out_bound">&nbsp;</td>
<!--                <td class="label">--><?php //echo __('ani', true); ?><!--:</td>-->
<!--                <td class="value"><input type="text" id="query-src_number" value="" name="query[src_number]"-->
<!--                                         class="input in-text">-->
<!--                    --><?php //echo $this->element('search_report/ss_clear_input_select'); ?><!--</td>-->
                <td style="width:3px;">&nbsp;</td>
                <td style="width:3px;">&nbsp;</td>
                <td style="width:3px;">&nbsp;</td>
                <td style="width:3px;">&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
<!--            <tr>-->
<!--                --><?php //echo $this->element('search_report/search_term_code'); ?>
<!--                <td class="in-out_bound">&nbsp;</td>-->
<!--                <td class="label"></td>-->
<!--                <td class="value"></td>-->
<!--                <td style="width:3px;">&nbsp;</td>-->
<!--                <td>&nbsp;</td>-->
<!--                <td>&nbsp;</td>-->
<!--            </tr>-->
            <!--
<tr>
<td class="label"> <?php __('codedecks') ?><span class="tooltip" id="ht-100001-tooltip"><b>Use pre-assigned</b> &mdash; means usage of code decks assigned to each pulled client or rate table.<br><br>If you will <b>specify</b> a code deck, all code names will be rewritten using names from selected code deck, so all data will be unified by code names.</span>:</td>
<td class="value">
<?php echo $form->input('code_deck', array('options' => $code_deck, 'empty' => ' ', 'label' => false, 'div' => false, 'type' => 'select')); ?>
</td>
</tr>
-->
            <!--
<tr>
<?php if ($rate_type == 'term') { ?>
<td class="label"><?php __('egress') ?></td>
<td class="value"><?php echo $form->input('egress_alias',
                array('options' => $egress, 'empty' => ' ', 'label' => false, 'div' => false, 'type' => 'select')); ?></td>
<?php } ?>
<?php if ($rate_type == 'org') { ?>
<td class="label"><?php __('ingress') ?>
<span class="tooltip" id="ht-100013-tooltip"><b>Use pre-assigned</b> &mdash; means usage of code decks assigned to each pulled client or rate table.<br>
<br>
If you will <b>specify</b> a code deck, all code names will be rewritten using names from selected code deck, so all data will be unified by code names.</span>:</td>
<td class="value"><?php
                echo $form->input('ingress_alias',
                    array('options' => $ingress, 'empty' => ' ', 'label' => false, 'div' => false, 'type' => 'select'));
                ?></td>
<?php } ?>
</tr>
-->
            <!--
            <tr>
            <td class="label"><span rel="helptip" class="helptip" id="ht-100002">Interval (sec)</span><span class="tooltip" id="ht-100002-tooltip">Duration interval in seconds</span>:</td>
            <td class="value"><input type="text" id="query-interval_from" class="in-digits input in-text" style="width: 53px;" value="" name="query[interval_from]">
            &mdash;
            <input type="text" id="query-interval_to" class="in-digits input in-text" style="width: 54px;" value="" name="query[interval_to]"></td>
            </tr>
            -->
        <?php } ?>

        <?php //echo $this->element('report/group_by');?>
        </tbody>
    </table>
    <div class="group_by">
        Group By
    </div>
    <table class="form" style="width:100%">
        <tr>
            <td class="label">Group By #1:</td>
            <td class="value">
                <select name="group_select[]" style="width:140px;">
                    <option value="" <?php echo $common->set_get_select_mul('group_select', '', 0, TRUE); ?>></option>
                    <option value="ingress_client_id" <?php echo $common->set_get_select_mul('group_select', 'ingress_client_id', 0); ?>>
                        ingress Carrier
                    </option>
                    <option value="ingress_id" <?php echo $common->set_get_select_mul('group_select', 'ingress_id', 0); ?>>
                        Ingress Trunk
                    </option>
                    <?php if (Configure::read('statistics.group_all')): ?>
                        <option value="ingress_country" <?php echo $common->set_get_select_mul('group_select', 'ingress_country', 0); ?>>
                            ingress Country
                        </option>
                        <option value="ingress_code_name" <?php echo $common->set_get_select_mul('group_select', 'ingress_code_name', 0); ?>>
                            ingress Code Name
                        </option>
                        <option value="ingress_code" <?php echo $common->set_get_select_mul('group_select', 'ingress_code', 0); ?>>
                            ingress Code
                        </option>
                        <option value="ingress_rate" <?php echo $common->set_get_select_mul('group_select', 'ingress_rate', 0); ?>>
                            ingress Rate
                        </option>
                    <?php endif; ?>
                    <option value="egress_client_id" <?php echo $common->set_get_select_mul('group_select', 'egress_client_id', 0); ?>>
                        egress Carrier
                    </option>
                    <option value="egress_id" <?php echo $common->set_get_select_mul('group_select', 'egress_id', 0); ?>>
                        egress Trunk
                    </option>
                    <?php if (Configure::read('statistics.group_all')): ?>
                        <option value="egress_country" <?php echo $common->set_get_select_mul('group_select', 'egress_country', 0); ?>>
                            egress Country
                        </option>
                        <option value="egress_code_name" <?php echo $common->set_get_select_mul('group_select', 'egress_code_name', 0); ?>>
                            egress Code Name
                        </option>
                        <option value="egress_code" <?php echo $common->set_get_select_mul('group_select', 'egress_code', 0); ?>>
                            egress Code
                        </option>
                    <?php endif; ?>
                    <!-- <option value="origination_destination_host_name" <?php echo $common->set_get_select_mul('group_select', 'origination_destination_host_name', 0); ?>>Orig Server</option>
<option value="termination_source_host_name" <?php echo $common->set_get_select_mul('group_select', 'termination_source_host_name', 0); ?>>Term Server</option>-->
                </select>
            </td>
            <td class="label">Group By #2:</td>
            <td class="value">
                <select name="group_select[]" style="width:140px;">
                    <option value="" <?php echo $common->set_get_select_mul('group_select', '', 1, TRUE); ?>></option>
                    <option value="ingress_client_id" <?php echo $common->set_get_select_mul('group_select', 'ingress_client_id', 1); ?>>
                        ingress Carrier
                    </option>
                    <option value="ingress_id" <?php echo $common->set_get_select_mul('group_select', 'ingress_id', 1); ?>>
                        Ingress Trunk
                    </option>
                    <?php if (Configure::read('statistics.group_all')): ?>
                        <option value="ingress_country" <?php echo $common->set_get_select_mul('group_select', 'ingress_country', 1); ?>>
                            ingress Country
                        </option>
                        <option value="ingress_code_name" <?php echo $common->set_get_select_mul('group_select', 'ingress_code_name', 1); ?>>
                            ingress Code Name
                        </option>
                        <option value="ingress_code" <?php echo $common->set_get_select_mul('group_select', 'ingress_code', 1); ?>>
                            ingress Code
                        </option>
                        <option value="ingress_rate" <?php echo $common->set_get_select_mul('group_select', 'ingress_rate', 1); ?>>
                            ingress Rate
                        </option>
                    <?php endif; ?>
                    <option value="egress_client_id" <?php echo $common->set_get_select_mul('group_select', 'egress_client_id', 1); ?>>
                        egress Carrier
                    </option>
                    <option value="egress_id" <?php echo $common->set_get_select_mul('group_select', 'egress_id', 1); ?>>
                        Egress Trunk
                    </option>
                    <?php if (Configure::read('statistics.group_all')): ?>
                        <option value="egress_country" <?php echo $common->set_get_select_mul('group_select', 'egress_country', 1); ?>>
                            egress Country
                        </option>
                        <option value="egress_code_name" <?php echo $common->set_get_select_mul('group_select', 'egress_code_name', 1); ?>>
                            egress Code Name
                        </option>
                        <option value="egress_code" <?php echo $common->set_get_select_mul('group_select', 'egress_code', 1); ?>>
                            egress Code
                        </option>
                    <?php endif; ?>
                    <!-- <option value="origination_destination_host_name" <?php echo $common->set_get_select_mul('group_select', 'origination_destination_host_name', 1); ?>>Orig Server</option>
<option value="termination_source_host_name" <?php echo $common->set_get_select_mul('group_select', 'termination_source_host_name', 1); ?>>Term Server</option>-->
                </select>
            </td>
            <td class="label">Group By #3:</td>
            <td class="value">
                <select name="group_select[]" style="width:140px;">
                    <option value="" <?php echo $common->set_get_select_mul('group_select', '', 2, TRUE); ?>></option>
                    <option value="ingress_client_id" <?php echo $common->set_get_select_mul('group_select', 'ingress_client_id', 2); ?>>
                        ingress Carrier
                    </option>
                    <option value="ingress_id" <?php echo $common->set_get_select_mul('group_select', 'ingress_id', 2); ?>>
                        Ingress Trunk
                    </option>
                    <?php if (Configure::read('statistics.group_all')): ?>
                        <option value="ingress_country" <?php echo $common->set_get_select_mul('group_select', 'ingress_country', 2); ?>>
                            ingress Country
                        </option>
                        <option value="ingress_code_name" <?php echo $common->set_get_select_mul('group_select', 'ingress_code_name', 2); ?>>
                            ingress Code Name
                        </option>
                        <option value="ingress_code" <?php echo $common->set_get_select_mul('group_select', 'ingress_code', 2); ?>>
                            ingress Code
                        </option>
                        <option value="ingress_rate" <?php echo $common->set_get_select_mul('group_select', 'ingress_rate', 2); ?>>
                            ingress Rate
                        </option>
                    <?php endif; ?>
                    <option value="egress_client_id" <?php echo $common->set_get_select_mul('group_select', 'egress_client_id', 2); ?>>
                        egress Carrier
                    </option>
                    <option value="egress_id" <?php echo $common->set_get_select_mul('group_select', 'egress_id', 2); ?>>
                        Egress Trunk
                    </option>
                    <?php if (Configure::read('statistics.group_all')): ?>
                        <option value="egress_country" <?php echo $common->set_get_select_mul('group_select', 'egress_country', 2); ?>>
                            egress Country
                        </option>
                        <option value="egress_code_name" <?php echo $common->set_get_select_mul('group_select', 'egress_code_name', 2); ?>>
                            egress Code Name
                        </option>
                        <option value="egress_code" <?php echo $common->set_get_select_mul('group_select', 'egress_code', 2); ?>>
                            egress Code
                        </option>
                    <?php endif; ?>
                    <!-- <option value="origination_destination_host_name" <?php echo $common->set_get_select_mul('group_select', 'origination_destination_host_name', 2); ?>>Orig Server</option>
<option value="termination_source_host_name" <?php echo $common->set_get_select_mul('group_select', 'termination_source_host_name', 2); ?>>Term Server</option>-->
                </select>
            </td>
        </tr>
        <tr>
            <td class="label">Group By #4:</td>
            <td class="value">
                <select name="group_select[]" style="width:140px;">
                    <option value="" <?php echo $common->set_get_select_mul('group_select', '', 3, TRUE); ?>></option>
                    <option value="ingress_client_id" <?php echo $common->set_get_select_mul('group_select', 'ingress_client_id', 3); ?>>
                        ingress Carrier
                    </option>
                    <option value="ingress_id" <?php echo $common->set_get_select_mul('group_select', 'ingress_id', 3); ?>>
                        Ingress Trunk
                    </option>
                    <?php if (Configure::read('statistics.group_all')): ?>
                        <option value="ingress_country" <?php echo $common->set_get_select_mul('group_select', 'ingress_country', 3); ?>>
                            ingress Country
                        </option>
                        <option value="ingress_code_name" <?php echo $common->set_get_select_mul('group_select', 'ingress_code_name', 3); ?>>
                            ingress Code Name
                        </option>
                        <option value="ingress_code" <?php echo $common->set_get_select_mul('group_select', 'ingress_code', 3); ?>>
                            ingress Code
                        </option>
                        <option value="ingress_rate" <?php echo $common->set_get_select_mul('group_select', 'ingress_rate', 3); ?>>
                            ingress Rate
                        </option>
                    <?php endif; ?>
                    <option value="egress_client_id" <?php echo $common->set_get_select_mul('group_select', 'egress_client_id', 3); ?>>
                        egress Carrier
                    </option>
                    <option value="egress_id" <?php echo $common->set_get_select_mul('group_select', 'egress_id', 3); ?>>
                        Egress Trunk
                    </option>
                    <?php if (Configure::read('statistics.group_all')): ?>
                        <option value="egress_country" <?php echo $common->set_get_select_mul('group_select', 'egress_country', 3); ?>>
                            egress Country
                        </option>
                        <option value="egress_code_name" <?php echo $common->set_get_select_mul('group_select', 'egress_code_name', 3); ?>>
                            egress Code Name
                        </option>
                        <option value="egress_code" <?php echo $common->set_get_select_mul('group_select', 'egress_code', 3); ?>>
                            egress Code
                        </option>
                    <?php endif; ?>
                    <!-- <option value="origination_destination_host_name" <?php echo $common->set_get_select_mul('group_select', 'origination_destination_host_name', 3); ?>>Orig Server</option>
<option value="termination_source_host_name" <?php echo $common->set_get_select_mul('group_select', 'termination_source_host_name', 3); ?>>Term Server</option>-->
                </select>
            </td>
            <td class="label">Group By #5:</td>
            <td class="value">
                <select name="group_select[]" style="width:140px;">
                    <option value="" <?php echo $common->set_get_select_mul('group_select', '', 4, TRUE); ?>></option>
                    <option value="ingress_client_id" <?php echo $common->set_get_select_mul('group_select', 'ingress_client_id', 4); ?>>
                        ingress Carrier
                    </option>
                    <option value="ingress_id" <?php echo $common->set_get_select_mul('group_select', 'ingress_id', 4); ?>>
                        Ingress Trunk
                    </option>
                    <?php if (Configure::read('statistics.group_all')): ?>
                        <option value="ingress_country" <?php echo $common->set_get_select_mul('group_select', 'ingress_country', 4); ?>>
                            ingress Country
                        </option>
                        <option value="ingress_code_name" <?php echo $common->set_get_select_mul('group_select', 'ingress_code_name', 4); ?>>
                            ingress Code Name
                        </option>
                        <option value="ingress_code" <?php echo $common->set_get_select_mul('group_select', 'ingress_code', 4); ?>>
                            ingress Code
                        </option>
                        <option value="ingress_rate" <?php echo $common->set_get_select_mul('group_select', 'ingress_rate', 4); ?>>
                            ingress Rate
                        </option>
                    <?php endif; ?>
                    <option value="egress_client_id" <?php echo $common->set_get_select_mul('group_select', 'egress_client_id', 4); ?>>
                        egress Carrier
                    </option>
                    <option value="egress_id" <?php echo $common->set_get_select_mul('group_select', 'egress_id', 4); ?>>
                        Egress Trunk
                    </option>
                    <?php if (Configure::read('statistics.group_all')): ?>
                        <option value="egress_country" <?php echo $common->set_get_select_mul('group_select', 'egress_country', 4); ?>>
                            egress Country
                        </option>
                        <option value="egress_code_name" <?php echo $common->set_get_select_mul('group_select', 'egress_code_name', 4); ?>>
                            egress Code Name
                        </option>
                        <option value="egress_code" <?php echo $common->set_get_select_mul('group_select', 'egress_code', 4); ?>>
                            egress Code
                        </option>
                    <?php endif; ?>
                    <!-- <option value="origination_destination_host_name" <?php echo $common->set_get_select_mul('group_select', 'origination_destination_host_name', 4); ?>>Orig Server</option>
<option value="termination_source_host_name" <?php echo $common->set_get_select_mul('group_select', 'termination_source_host_name', 4); ?>>Term Server</option>-->
                </select>
            </td>
            <td class="label">Group By #6:</td>
            <td class="value">
                <select name="group_select[]" style="width:140px;">
                    <option value="" <?php echo $common->set_get_select_mul('group_select', '', 5, TRUE); ?>></option>
                    <option value="ingress_client_id" <?php echo $common->set_get_select_mul('group_select', 'ingress_client_id', 5); ?>>
                        ingress Carrier
                    </option>
                    <option value="ingress_id" <?php echo $common->set_get_select_mul('group_select', 'ingress_id', 5); ?>>
                        Ingress Trunk
                    </option>
                    <?php if (Configure::read('statistics.group_all')): ?>
                        <option value="ingress_country" <?php echo $common->set_get_select_mul('group_select', 'ingress_country', 5); ?>>
                            ingress Country
                        </option>
                        <option value="ingress_code_name" <?php echo $common->set_get_select_mul('group_select', 'ingress_code_name', 5); ?>>
                            ingress Code Name
                        </option>
                        <option value="ingress_code" <?php echo $common->set_get_select_mul('group_select', 'ingress_code', 5); ?>>
                            ingress Code
                        </option>
                        <option value="ingress_rate" <?php echo $common->set_get_select_mul('group_select', 'ingress_rate', 5); ?>>
                            ingress Rate
                        </option>
                    <?php endif; ?>
                    <option value="egress_client_id" <?php echo $common->set_get_select_mul('group_select', 'egress_client_id', 5); ?>>
                        egress Carrier
                    </option>
                    <option value="egress_id" <?php echo $common->set_get_select_mul('group_select', 'egress_id', 5); ?>>
                        Egress Trunk
                    </option>
                    <?php if (Configure::read('statistics.group_all')): ?>
                        <option value="egress_country" <?php echo $common->set_get_select_mul('group_select', 'egress_country', 5); ?>>
                            egress Country
                        </option>
                        <option value="egress_code_name" <?php echo $common->set_get_select_mul('group_select', 'egress_code_name', 5); ?>>
                            egress Code Name
                        </option>
                        <option value="egress_code" <?php echo $common->set_get_select_mul('group_select', 'egress_code', 5); ?>>
                            egress Code
                        </option>
                    <?php endif; ?>
                    <!-- <option value="origination_destination_host_name" <?php echo $common->set_get_select_mul('group_select', 'origination_destination_host_name', 5); ?>>Orig Server</option>
<option value="termination_source_host_name" <?php echo $common->set_get_select_mul('group_select', 'termination_source_host_name', 5); ?>>Term Server</option>-->
                </select>
            </td>
        </tr>
    </table>
    <?php echo $form->end(); ?>
</fieldset>


<script type="text/javascript">
    var $routeprefix = $("#CdrRoutePrefix");
    var $ingress_rate_table = $('#ingress_rate_table');
    var $ingress_routing_plan = $('#ingress_routing_plan');

    function getTechPrefix(obj) {
        var $this = $(obj);
        var val = $this.val();
        $routeprefix.empty();
        $ingress_rate_table.empty();
        $ingress_routing_plan.empty();
        $routeprefix.append("<option value='all'>All</option>");
        $ingress_rate_table.append("<option value='all'>All</option>");
        $ingress_routing_plan.append("<option value='all'>All</option>");
        if (val != '0') {
            $.post("<?php echo $this->webroot?>cdrreports/getTechPerfix", {ingId: val},
                function (data) {
                    $.each(data.prefixes,
                        function (index, content) {
                            $routeprefix.append("<option value='" + content[0]['tech_prefix'] + "'>" + content[0]['tech_prefix'] + "</option>");
                        }
                    );
                    $.each(data.rate_tables,
                        function (index, content) {
                            $ingress_rate_table.append("<option value='" + content[0]['rate_table_id'] + "'>" + content[0]['rate_table_name'] + "</option>");
                        }
                    );
                    $.each(data.routing_plans,
                        function (index, content) {
                            $ingress_routing_plan.append("<option value='" + content[0]['route_strategy_id'] + "'>" + content[0]['route_strategy_name'] + "</option>");
                        }
                    );
                }, 'json');
        }
    }
</script>
