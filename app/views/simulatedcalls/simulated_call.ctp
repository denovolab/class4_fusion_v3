<div id="title">
    <h1><?php __('Tools') ?>&gt;&gt;<?php echo __('simulattedcall') ?>

    </h1>
</div>
<style type="text/css">
    .form_input {
        float: left;
        width: 300px;
    }

    .container ul {
        padding-left: 20px;
    }

    .container ul li {
        padding: 3px;
    }
</style>
<div class="container">


    <?php

    /**
     * function xml2array
     *
     * This function is part of the PHP manual.
     *
     * The PHP manual text and comments are covered by the Creative Commons
     * Attribution 3.0 License, copyright (c) the PHP Documentation Group
     *
     * @author  k dot antczak at livedata dot pl
     * @date    2011-04-22 06:08 UTC
     * @link    http://www.php.net/manual/en/ref.simplexml.php#103617
     * @license http://www.php.net/license/index.php#doc-lic
     * @license http://creativecommons.org/licenses/by/3.0/
     * @license CC-BY-3.0 <http://spdx.org/licenses/CC-BY-3.0>
     */
    function xml2array ( $xmlObject, $out = array () )
    {
        foreach ( (array) $xmlObject as $index => $node )
            $out[$index] = ( is_object ( $node ) ) ? xml2array ( $node ) : $node;

        return $out;
    }

    function _getOrderedData($array)
    {
        $rateRecords = [];
        $emptyRecords = [];

        foreach ($array as $item) {
            if (isset($item->{'Rate'})) {
                array_push($rateRecords, $item);
            } else {
                array_push($emptyRecords, $item);
            }
        }

        for ($i = 0; $i < count($rateRecords); $i++) {
            for ($j = count($rateRecords) - 2; $j >= $i; $j--) {
                if (xml2array($rateRecords[$j])['Rate'] < xml2array($rateRecords[$j + 1])['Rate']) {
                    $temp = $rateRecords[$j];
                    $rateRecords[$j] = $rateRecords[$j + 1];
                    $rateRecords[$j + 1] = $temp;
                }
            }
        }

        return array_merge($rateRecords, $emptyRecords);
    }

    function strip_invalid_xml_chars2($in)
    {
        $out = "";
        $length = strlen($in);
        for ($i = 0; $i < $length; $i++) {
            $current = ord($in{$i});
            if (($current == 0x9) || ($current == 0xA)
                || ($current == 0xD)
                || (($current >= 0x20)
                    && ($current <= 0xD7FF))
                || (($current >= 0xE000)
                    && ($current <= 0xFFFD))
                || (($current >= 0x10000)
                    && ($current <= 0x10FFFF))) {
                $out .= chr($current);
            } else {
                $out .= " ";
            }
        }
        return $out;
    }

    function recursion($element)
    {
        if ($element->getName() != 'root') {
            echo "<li>";
            echo str_replace('-', ' ', $element->getName());
            if (trim($element) != '') {
                echo ' = ' . $element;
            }
        }
        if ($element->count()) {
            foreach ($element->children() as $chldren) {
                echo "<ul>";
                recursion($chldren);
                echo "</ul>";
            }
        }
        echo "</li>";
    }


    if (isset($xdata)) {
        ?>
        <?php
        $xdata = strip_invalid_xml_chars2($xdata);
        $string = <<<XML
<?xml version='1.0'?> 
<root>
$xdata
</root>
XML;
        $xml = simplexml_load_string($string);
        /*
                echo "<ul>";

                recursion($xml);

                echo "</ul>";
        */


        ?>

        <script type="text/javascript">
            $(document).ready(function () {
                $('.container li > ul').hide();
                $('<img src="<?php echo $this->webroot . 'images/+.gif' ?>" />').prependTo('.container li:has(ul)').css('cursor', 'pointer').toggle(function () {
                    $(this).attr('src', '<?php echo $this->webroot . 'images/-.gif' ?>').siblings().show();
                }, function () {
                    $(this).attr('src', '<?php echo $this->webroot . 'images/+.gif' ?>').siblings().hide();
                });
            });
        </script>

        <table class="list">
            <tbody>
            <tr>
                <td>Ingress Trunk</td>
                <td><?php echo $xml->{'Origination-Trunk'}->{'Trunk-Name'}; ?></td>
                <td>Ingress Host</td>
                <td><?php echo $_POST['data']['host']; ?></td>
                <td>Ingress ANI</td>
                <td><?php echo $xml->{'Origination-SRC-ANI'}; ?></td>
                <td>Ingress DNIS</td>
                <td><?php echo $xml->{'Origination-SRC-DNIS'}; ?></td>
            </tr>
            <tr>
                <td>Route Prefix</td>
                <td>-</td>
                <td>Routing Plan</td>
                <td><?php echo $xml->{'Origination-Trunk'}->{'Route-Strategy-Name'}; ?></td>
                <td>Static Route</td>
                <td><?php echo $xml->{'Origination-Trunk'}->{'Static-Route-Name'}; ?></td>
                <td>Dynamic Route</td>
                <td><?php echo $xml->{'Origination-Trunk'}->{'Dynamic-Route-Name'}; ?></td>
            </tr>

            <tr>
                <td>Ingress Rate</td>
                <td><?php echo isset($xml->{'Origination-Trunk-Rate'}->{'Rate'}) ? $xml->{'Origination-Trunk-Rate'}->{'Rate'} : '' ?></td>
                <td>LRN Num</td>
                <td><?php echo isset($xml->{'Origination-Respond-LRN-DNIS'}) ? $xml->{'Origination-Respond-LRN-DNIS'} : '' ?></td>
                <td>Jurisdiction</td>
                <td><?php echo isset($xml->{'Origination-Trunk-Rate'}->{'Rate-Type'}) ? $xml->{'Origination-Trunk-Rate'}->{'Rate-Type'} : '' ?></td>
                <td>Release Cause</td>
                <td><?php echo isset($xml->{'Global-Route-State'}->{'Origination-State'}) ? $xml->{'Global-Route-State'}->{'Origination-State'} : ''; ?></td>
            </tr>
            </tbody>
        </table>

        <table class="list table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
            <thead>
            <tr>
                <th><?php __('Egress Trunk') ?></th>
                <th><?php __('Egress Host') ?></th>
                <th><?php __('Term ANI') ?></th>
                <th><?php __('Term DNIS') ?></th>
                <th><?php __('Term Rate') ?></th>
                <th><?php __('Release Cause') ?></th>
            </tr>
            </thead>
            <tbody>

            <?php
            if (isset($xml->{'Termination-Route'})) {
                foreach ($xml->{'Termination-Route'}->{'Termination-Trunk'} as $item): ?>
                    <tr>
                        <td><?php echo $item->{'Trunk-Name'} ?></td>
                        <td><?php echo $item->{'Termination-Host'}->{'Host-IP'} ?></td>
                        <td><?php echo $item->{'Final-ANI'}->{'ANI'} ?></td>
                        <td><?php echo $item->{'Final-DNIS'}->{'DNIS'} ?></td>
                        <td><?php echo $item->{'Trunk-Rate'}->{'Rate'} ?></td>
                        <td><?php __('normal') ?></td>
                    </tr>
                <?php endforeach;
            }
            ?>

            <?php

            $tempData = _getOrderedData($xml->{'Global-Route-State'}->{'Termination-Trunk'});
            foreach ($tempData as $item): ?>
                <?php if (strnatcasecmp($item->{'Cause'}, 'normal') != 0): ?>
                    <tr>
                        <td><?php
                            //                                        if (isset($item->{'Cause'}))
                            //                                        {
                            //                                            echo isset($xml->{'Termination-Route'}->{'Termination-Trunk'}->{'Trunk-Name'}) ? $xml->{'Termination-Route'}->{'Termination-Trunk'}->{'Trunk-Name'} : '';
                            //                                        }
                            //                                        else
                            //                                        {
                            echo $item->{'Trunk-Name'};
                            //                                        }
                            ?>
                        </td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td><?php echo !empty($item->{'Rate'}) ? $item->{'Rate'} : '-' ?></td>
                        <td>
                            <?php
                            echo isset($item->{'Cause'}) ? $item->{'Cause'} : '';
                            ?>
                        </td>
                    </tr>
                <?php endif; ?>
            <?php endforeach; ?>
            </tbody>
        </table>
        <?php
    }
    ?>
    <form method="post" action="">
        <div style="width:400px;margin:0px auto;">


            <table style="text-align:left;">
                <tr>
                    <td class="label" style="width:160px;"><?php echo __('ingress', true); ?>:</td>
                    <td>
                        <?php echo $form->input("ingress", array('onchange' => "chageSimulateIngress(this,'host')", 'class' => "input in-select form_input", 'options' => $appResource->format_ingress_options($ingress), 'label' => false, 'div' => false, 'type' => "select", 'empty' => array('' => "Select Ingress"))); ?>

                    </td>
                </tr>
                <tr>
                    <td class="label"><?php echo __('host', true); ?>:</td>
                    <td>
                        <?php echo $form->input("host", array('class' => "input in-select form_input", 'options' => $appSimulateCall->format_host_options($selected_hosts), 'label' => false, 'div' => false, 'type' => "select", 'empty' => false)); ?>
                    </td>
                </tr>
                <tr>
                    <td class="label"><?php echo __('origani') ?>:</td>
                    <td>
                        <?php echo $form->input("ani", array('class' => "input in-select form_input", 'label' => false, 'div' => false)); ?>
                    </td>
                </tr>
                <tr>
                    <td class="label"><?php echo __('origdnis') ?>:</td>
                    <td>
                        <?php echo $form->input("dnis", array('class' => "input in-select form_input", 'label' => false, 'div' => false)); ?>
                    </td>
                </tr>
                <tr>
                    <td class="label"><?php echo __('Time', true); ?>:</td>
                    <td><?php echo $form->input("time", array('onfocus' => "WdatePicker({dateFmt:'yyyy-MM-dd HH:00:00'});", 'value' => date('Y-m-d H:i:s'), 'style' => 'width:302px;', 'class' => "input in-text wdate", 'readonly' => true, 'label' => false, 'div' => false, 'type' => 'text')) ?></td>
                </tr>
                <tr style="display:none;">
                    <td class="label"><?php echo __('Routing Plan', true); ?>:</td>
                    <td>
                        <?php echo $form->input("route_strategy", array('class' => "input in-select form_input", 'options' => $appSimulateCall->format_route_strategy_options($route_strategies), 'label' => false, 'div' => false, 'type' => "select", 'empty' => '')); ?>
                    </td>
                </tr>
                <tr style="text-align:center;">
                    <td colspan="2">
                        <?php if ($_SESSION['role_menu']['Tools']['simulatedcalls']['model_r'] && $_SESSION['role_menu']['Tools']['simulatedcalls']['model_x']) { ?>
                            <input type="submit" value="<?php echo __('submit') ?>" class="input in-submit">

                            <input type="reset" value="<?php echo __('reset') ?>" class="input in-submit"
                                   style="margin-left: 20px;">
                        <?php } ?>

                    </td>
                </tr>
            </table>
        </div>
    </form>
</div>
<script type="text/javascript">
    function chageSimulateIngress(ingress, hostId) {
        var host = document.getElementById(hostId);
        host.options.length = 0;
        if (ingress.value.length >= 1) {
            jQuery.getJSON("<?php echo $this->webroot ?>simulatedcalls/get_ingress_by_resource?r_id=" + ingress.value, {}, function (data) {
                var datas = data;//eval(data);
                var loop = datas.length;
                for (var i = 0; i < loop; i++) {

                    var d = datas[i];
                    var option = document.createElement("option");
                    option.innerHTML = d.ip + ":" + d.port;
                    host.appendChild(option);
                }
            });
        }
    }
</script>
