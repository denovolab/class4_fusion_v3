<div id="title">
    <h1><?php __('Tool'); ?>&gt;&gt;<?php __('Rerating List'); ?></h1>
    <ul id="title-menu">
        <li>
            <a href="<?php echo $this->webroot ?>cdrreports/rerating" class="link_back"> <img width="16" height="16" src="<?php echo $this->webroot ?>images/icon_back_white.png" alt="Back">&nbsp;Back </a>
        </li>
    </ul>
</div>

<div id="container">
    <ul class="tabs">
        <li>
            <a href="<?php echo $this->webroot ?>cdrreports/rerating">
                <img width="16" height="16" src="/Class4/images/list.png">Rerate CDR		
            </a>
        </li>
        <li class="active">
            <a href="<?php echo $this->webroot ?>cdrreports/rerating_list">
                <img width="16" height="16" src="/Class4/images/list.png">Rerate Result 		
            </a>
        </li>
    </ul>
    <?php
        $data = $p->getDataArray();
    ?>
    <?php
        if(empty($data)):
    ?>
    <div id="list" class="second_tab" style="">
        <div class="msg">No data found</div>
    </div>
      <?php else: ?>
    <div id="toppage"></div>
    <table id="list" class="list">
        <thead>
            <tr>
                <td><?php __('ID'); ?></td>
                <td><?php __('Create Time'); ?></td>
                <td><?php __('Finish Time'); ?></td>
                <td><?php __('Status'); ?></td>
                <td><?php __('Rerate Type'); ?></td>
                <td><?php __('Rerate Rate Time'); ?></td>
                <td><?php __('Rate table'); ?></td>
                <td><?php __('CDR Backup File'); ?></td>
                <td><?php __('Start Time'); ?></td>
                <td><?php __('End Time'); ?></td>
                <td><?php __('Download'); ?></td>
<!--                <td><?php __('Condition'); ?></td>-->
            </tr>
        </thead>
        <tbody>
            <?php foreach($data as $item): ?>
            <tr>
                <td><?php echo $item[0]['id']; ?></td>
                <td><?php echo $item[0]['create_time']; ?></td>
                <td><?php echo $item[0]['finish_time']; ?></td>
                <td><?php echo $status[$item[0]['status']]; ?></td>
                <td><?php echo $item[0]['rerate_type'] == '1' ? 'Origination' : 'Termination'; ?></td>
                <td><?php echo $item[0]['rerate_rate_time']; ?></td>
                <td><?php echo $item[0]['rate_table_name']; ?></td>
                <td><?php echo $item[0]['cdr_backup_file']; ?></td>
                <td><?php echo $item[0]['start_time']; ?></td>
                <td><?php echo $item[0]['end_time']; ?></td>
                <td>
                    <?php
                        if(!empty($item[0]['cdr_backup_file'])) {
                            echo "<a href='" . $this->webroot . "cdrreports/download?file=" . $item[0]['cdr_backup_file'] . "'>Download</a>";
                        }
                    ?>
                </td>
                <!--<td><?php //echo $item[0]['where_condition']; ?></td>-->
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div id="tmppage"><?php echo $this->element('page');?></div>
    <?php endif; ?>
</div>