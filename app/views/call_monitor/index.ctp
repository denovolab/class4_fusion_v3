<div id="title">
  <h1>
    <?php __('Tools')?>
    &gt;&gt;<?php echo __('Call Monitor',true);?></h1>
</div>

<div id="container">
     <?php
        if(empty($this->data)): 
    ?>
    <div class="msg"><?php echo __('no_data_found',true);?></div>
    <table class="list" style="display:none;">
        
        <thead>
            <tr>
                <td>ID</td>
                <td>Start Time</td>
                <td>End Time</td>
                <td>ANI</td>
                <td>DNIS</td>
                <td>Remote Ip</td>
                <td>Remote Port</td>
                <td>Status</td>
                <td>Action</td>
            </tr>
        </thead>
        
        <tbody>
         
        </tbody>
    </table>
    <?php else: ?>
    <?php echo $this->element("xpage")?>
    <table class="list">
        <thead>
            <tr>
                <td>ID</td>
                <td>Start Time</td>
                <td>End Time</td>
                <td>ANI</td>
                <td>DNIS</td>
                <td>Remote Ip</td>
                <td>Remote Port</td>
                <td>Status</td>
                <td>Action</td>
            </tr>
        </thead>
        
        <tbody>
            <?php foreach($this->data as $item): ?>
            <tr>
                <td>#<?php echo $item['CallMonitor']['id']; ?></td>
                <td><?php echo $item['CallMonitor']['start_time']; ?></td>
                <td><?php echo $item['CallMonitor']['end_time']; ?></td>
                <td><?php echo $item['CallMonitor']['ani']; ?></td>
                <td><?php echo $item['CallMonitor']['dnis']; ?></td>
                <td><?php echo $item['CallMonitor']['remote_ip']; ?></td>
                <td><?php echo $item['CallMonitor']['remote_port']; ?></td>
                <td><?php echo $item['CallMonitor']['status'] == 0 ? 'Running' : 'Halted'; ?></td>
                <td>
                    <?php if ($item['CallMonitor']['status'] == 0): ?>
                    <a title="Stop"  href="<?php echo $this->webroot ?>call_monitor/stop/<?php echo $item['CallMonitor']['id']?>" >
                        <img src="<?php echo $this->webroot?>images/stop.png"/>
                    </a>
                    <?php 
                    else:
                    $start_time = $item['CallMonitor']['start_time'];
                    $end_time   = $item['CallMonitor']['end_time'];
                    $start_time = explode(' ', $start_time);
                    $end_time = explode(' ', $end_time);
                    ?>
                    <a title="View" target="_blank"  href="<?php echo $this->webroot ?>cdrreports/summary_reports?smartPeriod=custom&min_start_date=<?php echo $start_time[0] ?>&min_start_time=<?php echo substr($start_time[1], 0, 8); ?>&max_stop_date=<?php echo $start_time[0] ?>&max_stop_time=<?php echo substr($end_time[1], 0, 8); ?>&open_callmonitor=1" >
                        <img src="<?php echo $this->webroot?>images/view.png"/>
                    </a>
                    <?php endif; ?>
                </td>
            </tr>
           
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php echo $this->element("xpage")?>
    <?php endif; ?>
    <?php //if ($count == 0): ?>
    <fieldset class="query-box" style=" clear:both;overflow:hidden;margin-top:10px;">
        <div class="search_title"><img src="<?php echo $this->webroot ?>images/control_panel.png">
            Panel
        </div>
        <form method="post">
        <table class="form" style="width: 100%">
            <tbody>
                <tr>
                    <td>Server:</td>
                    <td>
                        <select name="server">
                            <?php foreach ($servers as $server): ?>
                            <option value="<?php echo $server[0]['id'] ?>"><?php echo $server[0]['sip_ip'] ?>:<?php echo $server[0]['sip_port'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                    <td>ANI:</td>
                    <td>
                        <input type="text" name="ani" style="width:120px;">
                    </td>
                    <td>DNIS:</td>
                    <td>
                        <input type="text" name="dnis" style="width:120px;">
                    </td>
                    <td>Remote IP:</td>
                    <td>
                        <input type="text" name="remote_ip" style="width:120px;">
                    </td>
                    <td>Remote Port:</td>
                    <td>
                        <input type="text" name="remote_port" style="width:120px;">
                    </td>
                    <td>
                        <input type="submit" value="Submit">
                    </td>
                </tr>
            </tbody>
        </table>
        </form>
    </fieldset>
    <?php //endif; ?>
</div>