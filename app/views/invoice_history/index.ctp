
<div id="title">
 <h1><?php  __('Finance')?> &gt;&gt;<?php echo __('Carrier Invoice History')?></h1>
 <ul id="title-search">
            <form method="get" id="myform1">
                <input type="hidden" name="filter_client_type" value="1">
                <li> 
                    <input type="text" id="search-_q" class="in-search default-value input in-text defaultText in-input" title="Search" value="Search" name="search">
                </li>
                    <input type="submit" id="submit" class="search_submit input in-submit" value="" name="submit">
            </form>
        </ul>
 
 <ul id="title-menu">
     <li>
        <a class="link_btn" href="<?php echo $this->webroot?>invoice_history/trigger">
                <img width="16" height="16" src="<?php echo $this->webroot?>images/trigger.png">&nbsp;<?php echo __('Trigger',true);?>
        </a>
    </li>
 </ul>
</div>

<div id="container">
    <?php
        $data =$p->getDataArray();
    ?>
    <?php if (empty($data)): ?>
    <div class="msg">No data found</div>
    <?php else: ?>
    <div id="toppage"></div>
    <table class="list">
        <thead>
            <tr>
                <th><?php echo __('Name',true);?></th>
                <th><?php echo __('Last Invoice For',true);?></th>
                <th><?php __('action')?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($data as $item): ?>
            <tr>
                <td><?php echo $item[0]['name'] ?></td>
                <td><?php echo $item[0]['last_invoice_date'] ?></td>
                <td>
                    <a target="_blank" href="<?php echo $this->webroot ?>invoice_history/view/<?php echo $item[0]['client_id'] ?>" title="<?php __('View'); ?>">
                        <img src="<?php echo $this->webroot; ?>images/view.png">
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div id="tmppage"> <?php echo $this->element('page');?> </div>
    <?php endif; ?>
</div>
