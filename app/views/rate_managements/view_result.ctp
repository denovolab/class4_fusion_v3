<div class="dialog_form">
    <table class="list">
        <tr>
            <th>Rate Table</th>
            <td><?php echo $log['rate_table']['name']; ?></td>
        </tr>
        <tr>
            <th>File Name</th>
            <td><?php echo basename($log['ImportRateStatus']['upload_file_name']); ?></td>
        </tr>
        <tr>
            <th>Status</th>
            <td><?php echo $status[$log['ImportRateStatus']['status']]; ?></td>
        </tr>
        <tr>
            <th>Records</th>
            <td>Delete:<?php echo $log['ImportRateStatus']['delete_queue']; ?>&nbsp;Update:<?php echo $log['ImportRateStatus']['update_queue']; ?>&nbsp;Insert:<?php echo $log['ImportRateStatus']['insert_queue']; ?></td>
        </tr>
        <tr>
            <th>Start Time</th>
            <td><?php echo date('Y-m-d H:i:s', $log['ImportRateStatus']['start_epoch']); ?></td>
        </tr>
        <tr>
            <th>Finish Time</th>
            <td><?php echo date('Y-m-d H:i:s', $log['ImportRateStatus']['end_epoch']); ?></td>
        </tr>
    </table>
</div>