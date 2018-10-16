<div id="title">
    <h1>
        <?php __('System')?>
        &gt;&gt;
        <?php __('Rate sending template'); ?>
    </h1>
</div>

<div id="container">
    <ul class="tabs">
        <li>
            <a href="<?php echo $this->webroot ?>rates/rate_sending">
                <img width="16" height="16" src="<?php echo $this->webroot; ?>images/list.png">Rate sending   		
            </a>
        </li>
        <li>
            <a href="<?php echo $this->webroot ?>rates/rate_templates">
                <img width="16" height="16" src="<?php echo $this->webroot; ?>images/list.png">Template  		
            </a>
        </li>
        <li class="active">
            <a href="<?php echo $this->webroot ?>rates/rate_sending_logging">
                <img width="16" height="16" src="<?php echo $this->webroot; ?>images/list.png">Log  		
            </a>
        </li>
    </ul>
    <?php
        $data =$p->getDataArray();
    ?>
    <div id="toppage"></div>
    <table class="list">
        <thead>
            <tr>
                <td>Date Time</td>
                <td>Record</td>
                <td>File</td>
                <td>Status</td>
            </tr>
        </thead>
        <tbody>
            <?php foreach($data as $item): ?>
            <tr>
                <td><?php echo $item[0]['log_datetime']; ?></td>
                <td><?php echo substr($item[0]['data'], 0, 160);; ?></td>
                <td><a href="<?php echo $this->webroot . '/rates/get_file/' . base64_encode($item[0]['file']); ?>">Download</a></td>
                <td><?php echo $status[$item[0]['status']]; ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div id="tmppage"><?php echo $this->element('page');?></div>
</div>