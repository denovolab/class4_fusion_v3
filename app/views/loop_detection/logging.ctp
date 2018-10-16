<div id="title">
    <h1><?php __('Loop Detection'); ?></h1>
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
            <th><?php __('Execution Time'); ?></th>
            <th><?php __('End Time'); ?></th>
            <th><?php __('Loop Count'); ?></th>
        </thead>
        <tbody>
            <?php foreach($this->data as $item): ?>
            <tr>
                <td><?php echo $item['LoopDetectionLog']['execution_time']; ?></td>
                <td><?php echo $item['LoopDetectionLog']['end_time']; ?></td>
                <td>
                    <a href="<?php echo $this->webroot; ?>loop_detection/logging_detail/<?php echo $item['LoopDetectionLog']['id'] ?>">
                        <?php echo count($item['LoopDetectionLogDetail']); ?></a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php echo $this->element("xpage")?>
    <?php endif; ?>
</div>