<style type="text/css">
#report_box {
    border:8px solid #80b000;
    width:600px;
    margin:0 auto;
    border-radius:6px;
    overflow:hidden;
}
#report_box h1 {
    color:#fff;
    font-size:14px;
    background:#80b000;
    height:30px;
    line-height:20px;
}
#report_box ul {
    padding:20px;
}
#report_box ul li label {
    float:left;
    width:120px;
    text-align: right;
    padding-right:20px;
}
#report_box ul li p {float:left;}
#report_box ul li {
    clear: both;
    height:40px;
    line-height: 30px;
}
</style>

<div id="title">
    <h1><?php echo __('Statistics'); ?> &gt;&gt; <?php echo __('Dashboard'); ?> &gt;&gt; <?php echo __('Charts'); ?></h1>
</div>

<div id="container">
    <ul class="tabs">
        <li>
            <a href="<?php echo $this->webroot ?>homes/dashbroad">
                <img width="16" height="16" src="<?php echo $this->webroot ?>images/stock.png">Dashboard</a>
        </li>
        <li>
            <a href="<?php echo $this->webroot ?>homes/report">
                <img width="16" height="16" src="<?php echo $this->webroot ?>images/report.png">Report
            </a>
        </li>
        <li class="active">
            <a href="<?php echo $this->webroot ?>homes/search_charts">
                <img width="16" height="16" src="<?php echo $this->webroot ?>images/showcharts.png">Charts
            </a>
        </li>
        <li>
            <a href="<?php echo $this->webroot ?>homes/auto_delivery">
                <img width="16" height="16" src="<?php echo $this->webroot ?>images/delivery.png">Auto Delivery
            </a>
        </li>
    </ul>
    <form style="text-align:center;" action="<?php echo $this->webroot ?>homes/show_charts" method="post">
    <div id="report_box">
        <h1>Charts Search</h1>
        <ul>
            <li>
                <label>Statistical Information</label>
                <p>
                    <select name="type">
                        <option value="0">ASR</option>
                        <option value="1">ACD</option>
                        <option value="2">Total Calls</option>
                        <option value="3">Total Billable Time</option>
                        <option value="4">PDD</option>
                        <option value="5">Total Cost</option>
                        <option value="6">Margin</option>
                        <option value="7">Call attemp</option>
                    </select>
                </p>
            </li>
            <li>
                <label>Report Type</label>
                <p>
                    <select name="report_type">
                        <option value="0">Origination</option>
                        <option value="1">Termination</option>
                    </select>
                </p>
            </li>
            <li>
                <label>Group Time</label>
                <p>
                    <select name="group_time">
                        <option value="0">Daily</option>
                        <option value="1">Houly</option>
                    </select>
                </p>
            </li>
            <li>
                <label>Timezone</label>
                <p>
                    <select name="timezone">
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
                </p>
            </li>
            <li>
                <label>Start Date/Time</label>
                <p>
                    <input type="text" name="start_time" onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss',lang:'en'})" value="<?php echo $date. ' ' . "00:00:00"; ?>" />
                </p>
            </li>
            <li>
                <label>End Date/Time</label>
                <p>
                    <input type="text" name="end_time" onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss',lang:'en'})" value="<?php echo $date. ' ' . "23:59:59"; ?>" />
                </p>
            </li>
            <li>
                <label>Country</label>
                <p>
                    <input type="text" name="country" id="query-country" />
                </p>
            </li>
            <li>
                <label>Destination</label>
                <p>
                    <input type="text" name="destination" id="query-code_name" />
                </p>
            </li>
            <li>
                <label>Orig Filter</label>
                <p>
                    <select name="ingress_trunk">
                        <option selected></option>
                        <?php foreach($ingress_trunks as $trunks): ?>
                        <option value="<?php echo $trunks[0]['resource_id'] ?>"><?php echo $trunks[0]['alias'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </p>
            </li>
            <li>
                <label>Term Filter</label>
                <p>
                    <select name="egress_trunk">
                        <option selected></option>
                        <?php foreach($egress_trunks as $trunks): ?>
                        <option value="<?php echo $trunks[0]['resource_id'] ?>"><?php echo $trunks[0]['alias'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </p>
            </li>
        </ul>
    </div>
    <br />
    <input type="submit" value="View" />
    </form>
    
</div>