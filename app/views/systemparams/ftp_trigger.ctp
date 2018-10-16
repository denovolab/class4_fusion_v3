<div id="title">
    <h1><?php echo __('Configuration'); ?> &gt;&gt; <?php echo __('FTP Configuration'); ?></h1>
</div>

<div id="container">
    
    <ul class="tabs">
        <li>
            <a href="<?php echo $this->webroot ?>systemparams/ftp_conf">
                <img width="16" height="16" src="<?php echo $this->webroot ?>/images/config.png">
                FTP Config
            </a>
        </li>
        <li class="active">
            <a href="<?php echo $this->webroot ?>systemparams/ftp_trigger">
                <img width="16" height="16" src="/Class4/images/execute.png">
                Trigger
            </a>
        </li>
        <!--
        <li>
            <a href="<?php echo $this->webroot ?>systemparams/ftp_log">
                <img width="16" height="16" src="/Class4/images/log.png">
                Log
            </a>
        </li>
        <li>
            <a href="<?php echo $this->webroot ?>systemparams/ftp_server_log">
                <img width="16" height="16" src="<?php echo $this->webroot; ?>images/ftp.gif">
                Ftp Server Log
            </a>
        </li>
        -->
    </ul>
    
    <form method="post">
    <table class="list list-form">
        <tbody>
            <tr>
                <td><?php __('Alias'); ?></td>
                <td>
                    <select name="alias">
                        <?php foreach($ftp_list as $ftp): ?>
                        <option value="<?php echo $ftp[0]['id']; ?>"><?php echo $ftp[0]['alias']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td><?php __('Start Date') ?></td>
                <td>
                    <input type="text" name="start_time" onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:00:00'});" value="<?php echo date('Y-m-d 00:00:00'); ?>" />
                </td>
            </tr>
            <tr>
                <td><?php __('End Date') ?></td>
                <td>
                    <input type="text" name="end_time" onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:59:59'});" value="<?php echo date('Y-m-d 23:59:59'); ?>" />
                </td>
            </tr>
            <tr>
                <td><?php __('Time zone') ?></td>
                <td>
                    <select id="query-tz" name="gmt" class="input in-select select" style="width:100px;">
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
                        <option value="+0000" selected>GMT +00:00</option>
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
                <td>
                    <?php __('File Breakdown'); ?>
                </td>
                <td>
                    <select name="file_breakdown">
                        <option value="0">As one big file</option>
                        <option value="1">As hourly file</option>
                        <option value="2">As daily file</option>
                    </select>
                </td>
            </tr>
        </tbody>
        <tfoot>
        <td colspan="2">
            <input type="submit" value="Execute" />
        </td>
        </tfoot>
    </table>
    </form>
    
</div>

<script type="text/javascript" src="<?php echo $this->webroot ?>js/fields_sendrate.js"></script>