<div id="title">
    <h1>Log&gt;&gt;Rate Export Log</h1>
</div>

<div id="container">
    <table class="list">
        <thead>
            <tr>
                <td>ID</td>
                <td>Rate Table</td>
                <td>Triggered Time</td>
                <td>Finished Time</td>
                <td>Completed Rows</td>
                <td>Status</td>
                <td>File</td>
            </tr>
        </thead>
        <tbody>
        <?php
        $statuses = array(
            0 => 'Waiting',
            1 => 'Process',
            2 => 'Done'
        )
        ?>
        <?php foreach ($data as $item): ?>
            <tr class="row-1">
                <td><?php echo $item['ImportExportLog']['id'] ?></td>
                <td><?php echo $item['RateTable']['name'] ?></td>
                <td><?php echo $item['ImportExportLog']['time'] ?></td>
                <td><?php echo $item['ImportExportLog']['finished_time'] ? $item['ImportExportLog']['finished_time'] : '--' ?></td>
                <td><?php echo $item['ImportExportLog']['success_numbers'] ?></td>
                <td><?php echo $statuses[$item['ImportExportLog']['status']] ?></td>
                <td>
                <?php if ($item['ImportExportLog']['status'] == 2): ?>
                    <a href="<?php echo $this->webroot; ?>rates/download_exported_rate/<?php echo base64_encode($item['ImportExportLog']['id']) ?>">Download</a>
                <?php else: ?>
                    --
                <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>