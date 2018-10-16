<style type="text/css">
    #multiple {display:none;}
</style>
<div id="title">
    <h1><?php echo __('DID Management', true); ?>&gt;&gt;<?php echo __('LNP Request', true); ?></h1></h1>
</div>

<div id="container">
    <?php echo $this->element("lnp_request_tab", array('active' => 'log'))?>
    <?php
    if(empty($this->data)): 
    ?>
    <div class="msg"><?php echo __('no_data_found',true);?></div>
    <table class="list" style="display:none;">
        <thead>
            <tr>
                <td>Request Date</td>
                <td>Request #</td>
                <td>Number of DIDs</td>
                <td>Status</td>
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
                <td>Request Date</td>
                <td>Request #</td>
                <td>Number of DIDs</td>
                <td>Status</td>
                <td>Action</td>
            </tr>
        </thead>

        <tbody>
            <?php foreach($this->data as $item): ?>
            <tr>
                <td><?php echo $item['LnpRequest']['request_date']; ?></td>
                <td><?php echo $item['LnpRequest']['id']; ?></td>
                <td><?php echo $item['LnpRequest']['count']; ?></td>
                <td><?php echo $status[$item['LnpRequest']['status']]; ?></td>
                <td>
                    <?php if ($_SESSION['login_type'] == 1): ?>
                    <a href="<?php echo $this->webroot ?>did/lnp_request/assign/<?php echo $item['LnpRequest']['id']; ?>" title="Confirm">
                        <img src="<?php echo $this->webroot ?>images/assign.png" />
                    </a>
                    <?php endif; ?>
                    <a href="<?php echo $this->webroot ?>did/lnp_request/get_file/<?php echo $item['LnpRequest']['id']; ?>" title="Confirm">
                        <img src="<?php echo $this->webroot ?>images/document.png" />
                    </a>
                    <a href="<?php echo $this->webroot ?>did/lnp_request/detail/<?php echo $item['LnpRequest']['id']; ?>" title="View Details">
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
