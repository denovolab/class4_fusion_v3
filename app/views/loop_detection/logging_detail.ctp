<div id="title">
    <h1><?php __('Loop Detection'); ?></h1>
    <ul id="title-menu">
        <li>
            <a href="<?php echo $this->webroot; ?>loop_detection/logging" class="link_back_new">
            <img width="16" height="16" alt="Back" src="<?php echo $this->webroot; ?>images/icon_back_white.png">&nbsp;Back</a>       
        </li>
    </ul>
</div>

<div id="container">
    <?php echo $this->element('loop_detection/tab', array('current_page' => 1)); ?>
    <?php
        if(empty($this->data)): 
    ?>
    <div class="msg"><?php echo __('no_data_found',true);?></div>
    <?php else: ?>
    <?php echo $this->element("xpage")?>
    <table class="list">
        <thead>
            <th><?php __('ANI'); ?></th>
            <th><?php __('DNIS'); ?></th>
            <th><?php __('Occurrence Count'); ?></th>
            <th><?php __('Interval Starts ON'); ?></th>
            <th><?php __('Interval Ends ON'); ?></th>
        </thead>
        <tbody>
            <?php foreach($this->data as $item): ?>
            <tr>
                <td><?php echo $item['LoopDetectionLogDetail']['ani']; ?></td>
                <td><?php echo $item['LoopDetectionLogDetail']['dnis']; ?></td>
                <td><?php echo $item['LoopDetectionLogDetail']['occurrence_count']; ?></td>
                <td><?php echo $item['LoopDetectionLogDetail']['interval_starts_on']; ?></td>
                <td><?php echo $item['LoopDetectionLogDetail']['interval_end_on']; ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php echo $this->element("xpage")?>
    <?php endif; ?>
</div>