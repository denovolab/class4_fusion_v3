<style type="text/css">
    .form .label2 {
    font-size: 12px;
    width: 40%;
}
</style>
<div id="title">
    <h1> <?php echo __('Finance',true);?>&gt;&gt;<?php echo __('Mutual Transaction',true);?> </h1>
</div>

<div class="container">
    <?php
        if(!empty($data)):
         $type_total = array(0,0,0,0,0,0,0,0,0,0,0,0);
    ?>
    <div id="toppage"></div>
    <table class="list">
        <thead>
            <tr>
                <th><?php echo __('Begin Date',true);?></th>
                <td><?php echo $startdate.$gmt; ?></td>
                <td></td>
                <th><?php __('Begin Balance');?></th>
                <td><?php echo round($begin_balance, 2)?></td>
            </tr>
            <tr>
                <td><?php echo __('Date',true);?></td>
                <td><?php echo __('Type',true);?></td>
                <td><?php echo __('Carrier',true);?></td>
                <td><?php echo __('Amount',true);?></td>
                <td><?php __('Balance'); ?></td>
            </tr>
        </thead>

        <tbody>
        <?php foreach($data as $item): ?>
            <tr>
                <td><?php echo date("Y-m-d H:i:sO", strtotime($item[0]['a'])); ?></td>
                <td><?php echo $all_type[$item[0]['b']]; ?></td>
                <td><?php echo $item[0]['c'] ?></td>
                <td><?php echo round($item[0]['d'], 2);$type_total[$item[0]['b']] += $item[0]['d'];  ?></td>
                <td><?php echo round($common->total_balance_for_mutual($item[0]['d'], $item[0]['b'], $begin_balance), 2); ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
   <table class="list">
        <tr>
            <td>payment received total:</td>
            <td><?php echo round($type_total[1], 2); ?></td>
            <td>payment sent total:</td>
            <td><?php echo round($type_total[2], 2); ?></td>
<!--        <td>invoice received:</td>
            <td><?php echo $type_total[3]; ?></td>
            <td>invoice sent:</td>
            <td><?php echo $type_total[4]; ?></td>   -->
            <td>credit note received:</td>
            <td><?php echo round($type_total[5], 2); ?></td>
            <td>credit note sent:</td>
            <td><?php echo round($type_total[6], 2); ?></td>
             <td>ingress reset:</td>
            <td><?php echo round($type_total[9], 2); ?></td>
        </tr>
        <tr>
            <td>debit note received:</td>
            <td><?php echo round($type_total[7], 2); ?></td>
            <td>debit note sent:</td>
            <td><?php echo round($type_total[8], 2); ?></td>
            <td>invoice received:</td>
            <td><?php echo round($type_total[3], 2); ?></td>
            <td>invoice sent:</td>
            <td><?php echo round($type_total[4], 2); ?></td>
            <td></td>
            <td></td>
        </tr>
    </table>
    
    <?php
        endif;
    ?>
     <br />
    
    <form name="myform" method="get" id="myform">
    <input type="hidden" name="search" value="1" />
    <input type="hidden" id="is_down" name="is_down" value="0" />
    <table class="form">
        <tr>
            <td><?php __('Carrier'); ?>:</td>
            <td style="width:200px;">
                <select id="client" name="client_id" style="width:150px;">
                    <?php foreach($clients as $client): ?>
                    <option value="<?php echo $client[0]['client_id'] ?>"><?php echo $client[0]['name'] ?></option>
                    <?php endforeach; ?>
                </select>
            </td>
        </tr>
        <tr>
            <td><?php __('Type'); ?>:</td>
            <td>
                <select id="type" name="type" style="width:150px;">
                <option value="0">All</option>
                <option value="1">payment received</option>
                <option value="2">payment sent</option>
                <option value="3">invoice received</option>
                <option value="4">invoice sent</option>
                <option value="5">credit note received</option>
                <option value="6">credit note sent</option>
                <option value="7">debit note received</option>
                <option value="8">debit note sent</option>
                <option value="9">reset</option>
            </select>
            </td>
        </tr>
        <tr>
            <td><?php __('Time Zone'); ?>:</td>
            <td>
                <select style="width:100px;" class="input in-select select" name="gmt" id="query-tz">
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
                    <option value="+0200">GMT +02:00</option>
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
                </select>
            </td>
        </tr>
        <tr>
            <td><?php __('Period'); ?>:</td>
            <td>
                <input type="text" name="start" style="width:120px;" onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'});" value="<?php echo $startdate; ?>" />
                ~
                <input type="text" name="end" style="width:120px;" onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'});" value="<?php echo $enddate; ?>" />
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <input type="submit" id="btnsub" value="<?php echo __('submit',true);?>" />
                <input type="button" value="Download" id="download" />
            </td>
        </tr>
    </table>
    </form>
    <!--
    <br />
    <fieldset style=" clear:both;overflow:hidden;margin-top:10px;" class="query-box">
        <div class="search_title">
          <img src="<?php echo $this->webroot; ?>images/search_title_icon.png">
          <?php echo __('Search',true);?>  
        </div>
        <div style="margin:0px auto; text-align:center;">
        <form name="myform" method="get" id="myform">
            <input type="hidden" name="search" value="1" />
            <input type="hidden" id="is_down" name="is_down" value="0" />
            Period:
            <input type="text" name="start" style="width:120px;" onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'});" value="<?php echo $startdate; ?>" />
            ~
            <input type="text" name="end" style="width:120px;" onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'});" value="<?php echo $enddate; ?>" />
            <?php __('in'); ?>
            <select style="width:100px;" class="input in-select select" name="gmt" id="query-tz">
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
                <option value="+0200">GMT +02:00</option>
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
            </select>
            <?php echo __('carrier',true);?>:
            <select id="client" name="client_id">
                <?php foreach($clients as $client): ?>
                <option value="<?php echo $client[0]['client_id'] ?>"><?php echo $client[0]['name'] ?></option>
                <?php endforeach; ?>
            </select>
            <?php echo __('type',true);?>:
            <select id="type" name="type">
                <option value="0">All</option>
                <option value="1">payment received</option>
                <option value="2">payment sent</option>
                <option value="3">invoice received</option>
                <option value="4">invoice sent</option>
                <option value="5">credit note received</option>
                <option value="6">credit note sent</option>
                <option value="7">debit note received</option>
                <option value="8">debit note sent</option>
                <option value="9">reset</option>
            </select>
            <input type="submit" id="btnsub" value="<?php echo __('submit',true);?>" />
            <input type="button" value="Download" id="download" />
        </form>
        </div>
   </fieldset>
    -->
</div>

<script type="text/javascript">
$(function() {
    <?php
        if(isset($_GET['client_id']))
            echo "$('#client option[value={$_GET['client_id']}]').attr('selected', true);\n";
        if(isset($_GET['type']))
            echo "$('#type option[value={$_GET['type']}]').attr('selected', true);\n";
    ?>
    
   $('#btnsub').click(function() {
        $('#is_down').val('0');
        $('form').submit();
   });
   
   $('#download').click(function() {
        $('#is_down').val('1');
        $('form').submit();
   });
    
    
});
</script>