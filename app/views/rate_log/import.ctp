<div id="title">
    <h1><?php echo __('Log',true);?>&gt;&gt;<?php echo __('Rate Import Log',true);?></h1>
    <ul id="title-menu">
        <li>
            <a href="###" id="refresh_btn" class="link_btn">
                <img width="10" height="5"  src="<?php echo $this->webroot ?>images/refresh.png"><?php echo __('Refresh',true);?>
            </a>
        </li>
        <?php if ($rate_table_id != null) : ?>
        <li>
            <a href="<?php echo $this->webroot ?>clientrates/view/<?php echo $rate_table_id; ?>" class="link_back_new">
                <img width="10" height="5"  src="<?php echo $this->webroot ?>images/icon_back_white.png"><?php echo __('goback',true);?>
            </a>
        </li>
        <?php endif; ?>
    </ul>
</div>

<div id="container">
    <?php echo $this->element("xpage")?>
    <table class="list">
        <thead style="background:none;">
            <tr>
                <td rowspan="2">Rate Table</td>
                <td rowspan="2">File Name</td>
                <td rowspan="2">User</td>
                <td rowspan="2">Status</td>
                <td colspan="4">Records</td>
                <td rowspan="2">Method</td>
                <td rowspan="2">Start Time</td>
                <td rowspan="2">Finish Time</td>
                <td rowspan="2">Upload Time</td>
                <td rowspan="2">Upload File</td>
                <td rowspan="2">Error File</td>
            </tr>
            <tr>
                <td>Delete</td>
                <td>Update</td>
                <td>Insert</td>
                <td>Error</td>
            </tr>
        </thead>
        
        <tbody>
            <?php foreach($this->data as $item): ?>
            <tr>
                <td>
                    <?php
                    /*
                    <?php if ((int)$item['ImportRateStatus']['status'] < 6): ?>
                    <a href="<?php echo $this->webroot ?>rate_log/stop/<?php echo $item['rate_table']['rate_table_id'] ?>">
                        <img src="<?php echo $this->webroot ?>images/stop.png" />
                    </a>
                    <?php endif; ?>
                    */
                    ?>
                    <?php echo $item['rate_table']['name']; ?>
                </td>
                <td><?php echo basename($item['ImportRateStatus']['upload_file_name']); ?></td>
                <td><?php echo $item['users']['name']; ?></td>
                <td><?php echo $status[$item['ImportRateStatus']['status']]; ?></td>
                <td><?php echo $item['ImportRateStatus']['delete_queue']; ?></td>
                <td><?php echo $item['ImportRateStatus']['update_queue']; ?></td>
                <td><?php echo $item['ImportRateStatus']['insert_queue']; ?></td>
                <td><?php echo $item['ImportRateStatus']['error_counter'] > $item['ImportRateStatus']['reimport_counter'] ? $item['ImportRateStatus']['error_counter'] : $item['ImportRateStatus']['reimport_counter']; ?></td>
                <td><?php echo $item['ImportRateStatus']['method']; ?></td>
                <td><?php echo date('Y-m-d H:i:s', $item['ImportRateStatus']['start_epoch']); ?></td>
                <td><?php echo date('Y-m-d H:i:s', $item['ImportRateStatus']['end_epoch']); ?></td>
                <td><?php echo $item['ImportRateStatus']['time']; ?></td>
                <td><a href="<?php echo $this->webroot; ?>rate_log/get_file/?file=<?php echo base64_encode($item['ImportRateStatus']['local_file']); ?>">Export</a></td>
                <td><a href="<?php echo $this->webroot; ?>rate_log/get_file/?file=<?php echo base64_encode($item['ImportRateStatus']['error_log_file']); ?>">Export</a></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php echo $this->element("xpage")?>
</div>

<script>
    $(function() {
        $('#refresh_btn').click(function() {
            window.location.reload();
        });
    });
    </script>