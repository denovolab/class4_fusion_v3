<div id="title">
    <h1><?php echo __('DID Management',true);?>&gt;&gt;<?php echo __('Request Report',true);?></h1></h1>
</div>

<div id="container">
    <?php echo $this->element("did_request_tab", array('active' => $current))?>
    <?php
    if(empty($this->data)): 
    ?>
    <div class="msg"><?php echo __('no_data_found',true);?></div>
    <table class="list" style="display:none;">
        <thead>
            <tr>
                <td>Request</td>
                <td>User Name</td>
                <td>Order Date</td>
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
                <td>Request</td>
                <td>User Name</td>
                <td>Order Date</td>
                <td>Action</td>
            </tr>
        </thead>

        <tbody>
            <?php foreach($this->data as $item): ?>
            <tr>
                <td><?php echo $item['DidRequest']['id']; ?></td>
                <td><?php echo $item['User']['name']; ?></td>
                <td><?php echo $item['DidRequest']['created_time']; ?></td>
                <td>
                    <?php if ($_SESSION['login_type'] == 1 && $current == 'active'): ?>
                    <a href="<?php echo $this->webroot ?>did/did_request/assign/<?php echo $item['DidRequest']['id']; ?>/<?php echo $current; ?>" title="Confirm">
                        <img src="<?php echo $this->webroot ?>images/assign.png" />
                    </a>
                    <?php endif; ?>
                    <a href="<?php echo $this->webroot ?>did/did_request/email/<?php echo $item['DidRequest']['id']; ?>/<?php echo $current; ?>" title="Email Me">
                        <img src="<?php echo $this->webroot ?>images/email.gif" />
                    </a>
                    <a href="<?php echo $this->webroot ?>did/did_request/detail/<?php echo $item['DidRequest']['id']; ?>/<?php echo $current; ?>" title="View Details">
                        <img src="<?php echo $this->webroot ?>images/detail.png" />
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php echo $this->element("xpage")?>
    <?php endif; ?>
</div>

