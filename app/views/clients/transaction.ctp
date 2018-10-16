<div id="title">
 <h1><?php  __('Management')?> &gt;&gt;<?php echo __('Transaction')?></h1>
</div>

<div id="container">
     <?php
        $data = $p->getDataArray();
    ?>
    <div id="toppage"></div>
    <?php
        if(count($data) == 0){
    ?>
        <div class="msg"><?php echo __('no_data_found')?></div>
    <?php    
        }else{
    ?>
    <table class="list">
        <thead>
           
            <tr>
                <td><span>Date</span></td>
                <td><span>Buy</span></td>
                <td><span>Sell</span></td>
                <td><span>Deposit</span></td>
                <td><span>Withdraw</span></td>
                <td><span>Balance</span></td>
            </tr>
        </thead>
        <tbody>
            <?php foreach($data as $item): ?>
            <tr>
                <td><?php echo $item[0]['date']?></td>
                <td><?php echo number_format($item[0]['buy'],2)?></td>
                <td><?php echo number_format($item[0]['sell'],2)?></td>
                <td><?php echo number_format($item[0]['wire_in'],2)?></td>
                 <td><?php echo number_format($item[0]['wire_out'],2)?></td>
                 <td><?php echo number_format($item[0]['bod_balance'],2)?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div id="tmppage"> <?php echo $this->element('page');?> </div>
    <?php }?>
</div>


