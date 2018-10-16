<div id="title">
    <h1>Tools&gt;&gt;<?php __('CDR Reconciliation'); ?></h1>
    <ul id="title-menu">
        <li>
            <a href="<?php echo $this->webroot; ?>cdrmatchs/index" class="link_btn"><img width="16" height="16" src="<?php echo $this->webroot; ?>images/add.png" alt=""> Create New</a>
        </li>
        <li>
            <a href="<?php echo $this->webroot ?>cdrmatchs/index" class="link_back"> <img width="16" height="16" src="<?php echo $this->webroot ?>images/icon_back_white.png" alt="Back">&nbsp;Back </a>
        </li>
    </ul>
</div>

<?php
        $data =$p->getDataArray();
?>

<div id="container">
    <div id="toppage"></div>
    <table class="list">
        <thead>
            <tr>
                <td><?php __('Create Time') ?></td>
                <td><?php __('Status') ?></td>
                <td><?php __('Finish Time') ?></td>
                <td><?php __('Format') ?></td>
                <!--<td><?php __('Rate') ?></td>
                <td><?php __('Duration Diff') ?></td>
                <td><?php __('Calltime Diff') ?></td>-->
                <td><?php __('Diff Report File') ?></td>
                <td><?php __('Diff CDR File') ?></td>
            </tr>
        </thead>

        <tbody>
            <?php foreach($data as $item) :?>
            <tr>
                <td><?php echo $item[0]['create_time']; ?></td>
                <td><?php echo $status[$item[0]['status']]; ?></td>
                <td><?php echo $item[0]['finish_time']; ?></td>
                <td><?php echo $item[0]['format'] == 0 ? 'Line-by-Line' : 'Aggregated Comparison'; ?></td>
                <!--<td><?php echo $item[0]['is_rate'] == 0 ? 'False' : 'True'; ?></td>
                <td><?php echo $item[0]['duration_diff']; ?></td>
                <td><?php echo $item[0]['calltime_diff']; ?></td>-->
                <td>
                    <?php
                        if(!empty($item[0]['diff_report_file'])) {
                            echo "<a href='" . $this->webroot . "cdrmatchs/download?file=" . $item[0]['diff_report_file'] . "'>Download</a>";
                        }
                    ?>
                </td>
                <td>
                    <?php
                        if(!empty($item[0]['diff_cdr_file'])) {
                            echo "<a href='" . $this->webroot . "cdrmatchs/download?file=" . $item[0]['diff_cdr_file'] . "'>Download</a>";
                        }
                    ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div id="tmppage"> <?php echo $this->element('page');?> </div>
</div>