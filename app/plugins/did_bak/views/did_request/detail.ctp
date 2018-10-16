<div id="title">
    <h1><?php echo __('DID Management',true);?>&gt;&gt;<?php echo __('Request Report Detail',true);?></h1>
    <ul id="title-menu">
        <li>
            <a href="<?php echo $this->webroot ?>did/did_request/index/<?php echo $type ?>" class="link_back_new"> 
                <img width="16" height="16" src="<?php echo $this->webroot ?>images/icon_back_white.png" alt="Back">&nbsp;Back
            </a>
        </li>
    </ul>
</div>

<div id="container">
    <?php
    if(empty($this->data)): 
    ?>
    <div class="msg"><?php echo __('no_data_found',true);?></div>
    <table class="list" style="display:none;">
        <thead>
            <tr>
                <td>Request</td>
                <td>DID</td>
                <td>Status</td>
                <td>Date Assigned</td>
                <td>Country</td>
                <td>Rate Center</td>
                <td>State</td>
                <td>LATA</td>
                <td>Trunk</td>
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
                <td>DID</td>
                <td>Status</td>
                <td>Date Assigned</td>
                <td>Country</td>
                <td>Rate Center</td>
                <td>State</td>
                <td>LATA</td>
                <td>Trunk</td>
            </tr>
        </thead>

        <tbody>
            <?php foreach($this->data as $item): ?>
            <tr>
                <td><?php echo $item['DidRequest']['id']; ?></td>
                <td><?php echo $item['DidRequestDetail']['number']; ?></td>
                <td><?php echo $status[$item['DidRequestDetail']['status']]; ?></td>
                <td><?php echo $item['DidRequestDetail']['assigned_time']; ?></td>
                <td><?php echo $item['DidRespoitory']['country']; ?></td>
                <td><?php echo $item['DidRespoitory']['rate_center']; ?></td>
                <td><?php echo $item['DidRespoitory']['state']; ?></td>
                <td><?php echo $item['DidRespoitory']['lata']; ?></td>
                <td><?php echo $item['Resource']['alias']; ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php echo $this->element("xpage")?>
    <?php endif; ?>
</div>
