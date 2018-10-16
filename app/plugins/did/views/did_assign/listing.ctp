<div id="title">
    <h1><?php echo __('DID Management',true);?>&gt;&gt;<?php echo __('DID Listing',true);?></h1></h1>
</div>

<div id="container">
    <?php //echo $this->element("did_client_tab", array('active' => 'listing'))?>
    <?php
    if(empty($this->data)): 
    ?>
    <div class="msg"><?php echo __('no_data_found',true);?></div>
    <table class="list" style="display:none;">
        <thead>
            <tr>
                <td>DID</td>
                <td>Egress Trunk</td>
                <td>Created Time</td>
                <td>Assigned Time</td>
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
                <td>DID</td>
                <td>Egress Trunk</td>
                <td>Created Time</td>
                <td>Assigned Time</td>
                <!--<td>Action</td>-->
            </tr>
        </thead>

        <tbody>
            <?php foreach($this->data as $item): ?>
            <tr>
                <td><?php echo $item['DidAssign']['number']; ?></td>
                <td><?php echo $egresses[$item['DidAssign']['egress_id']]; ?></td>
                <td><?php echo $item['DidAssign']['created_time']; ?></td>
                <td><?php echo $item['DidAssign']['assigned_time']; ?></td>
                <!--
                <td>
                    <?php if($item['DidAssign']['status'] == 0): ?>
                    <a href="<?php echo $this->webroot ?>did/did_assign/change_status/<?php echo $item['DidAssign']['number']; ?>/1"> 
                        <img src="<?php echo $this->webroot ?>images/flag-0.png" title="Active">
                    </a>
                    <?php elseif($item['DidAssign']['status'] == 1): ?>
                    <a href="<?php echo $this->webroot ?>did/did_assign/change_status/<?php echo $item['DidAssign']['number']; ?>/0"> 
                        <img src="<?php echo $this->webroot ?>images/flag-1.png" title="Unactive">
                    </a>
                    <?php endif; ?>
                </td>
                -->
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php echo $this->element("xpage")?>
    <?php endif; ?>
</div>

