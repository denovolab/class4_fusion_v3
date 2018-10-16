<div id="title">
    <h1>
        <?php __('Finance'); ?>
        &gt;&gt;
        <?php __('Past Due Notification Log');?>
    </h1>
    <ul id="title-menu">
       <li>
            <a href="javascript:history.go(-1)" class="link_back">
                <img width="16" height="16" src="<?php echo $this->webroot ?>images/icon_back_white.png" alt="Back">
                &nbsp;Back         
            </a>
       </li>
    </ul>
</div>

<div id="container">
    <?php
        $data =$p->getDataArray();
    ?>
    <div id="toppage"></div>
    <table class="list">
        <thead>
            <tr>
                <td><?php __('Sent Date Time'); ?></td>
                <td><?php __('Carrier'); ?></td>
                <td><?php __('Amount'); ?></td>
                <td><?php __('Due Date'); ?></td>
                <td><?php __('Action'); ?></td>
            </tr>
        </thead>
        
        <tbody>
            <?php foreach($data as $item): ?>
            <tr>
                <td><?php echo $item[0]['send_time']; ?></td>
                <td><?php echo $item[0]['carrier_name']; ?></td>
                <td><?php echo $item[0]['total_amount']; ?></td>
                <td><?php echo $item[0]['due_date']; ?></td>
                <td>
                    <a href="<?php echo $this->webroot ?>finances/view_past_due_log/<?php echo $item[0]['id']; ?>" title="<?php __('View Email'); ?>">
                        
                        <img src="<?php echo $this->webroot; ?>images/detail.png" />
                    </a>
                    <a href="<?php echo $this->webroot ?>finances/resend_past_due/<?php echo $item[0]['id']; ?>" title="<?php __('Resend') ?>">
                        <img src="<?php echo $this->webroot; ?>images/send_rate.png" />
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div id="tmppage"> <?php echo $this->element('page');?> </div>
</div>