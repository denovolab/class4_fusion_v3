<style type="text/css">
    #multiple {display:none;}
</style>
<div id="title">
    <h1><?php echo __('DID Management', true); ?>&gt;&gt;<?php echo __('LNP Request', true); ?></h1>
    <ul id="title-menu">
        <li>
            <a href="<?php echo $this->webroot ?>did/lnp_request/index" class="link_back_new"> 
                <img width="16" height="16" src="<?php echo $this->webroot ?>images/icon_back_white.png" alt="Back">&nbsp;Back
            </a>
        </li>
    </ul>
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
                <td>Number</td>
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
                <td>Number</td>
                <td>Status</td>
            </tr>
        </thead>

        <tbody>
            <?php foreach($this->data as $item): ?>
            <tr>
                <td><?php echo $item['LnpRequestDetail']['number']; ?></td>
                <td><?php echo $status[$item['LnpRequestDetail']['status']]; ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php echo $this->element("xpage")?>
    <?php endif; ?>
</div>
