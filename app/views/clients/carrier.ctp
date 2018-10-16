<div id="title">
     <h1><?php  __('Management')?> &gt;&gt;<?php echo __('client')?></h1>
</div>

<div id="container">
    
    <table class="list list-form">
        <tbody>
            <tr>
                <th><?php __('Name') ?></th>
                <td><?php echo $data['name'] ?></td>
            </tr>
            <tr>
                <th><?php __('Mutual Balance') ?></th>
                <td>
                    <a href="<?php echo $this->webroot ?>finances/get_mutual_ingress_egress_detail/<?php echo $_SESSION['sst_client_id'] ?>" target="_blank">
                    <?php
                        $mutal_total = $data['mutual_total_balance'] ;
                        echo $mutal_total < 0 ? '('.str_replace('-','',number_format($mutal_total, 3)).')' : number_format($mutal_total, 3);
                    ?>
                    </a>
                </td>
            </tr>
            <tr>
                <th><?php __('Actual Balance') ?></th>
                <td>
                    <a href="<?php echo $this->webroot ?>finances/get_actual_ingress_egress_detail/<?php echo $_SESSION['sst_client_id'] ?>" target="_blank">
                    <?php 
                    $item_balance = $data['balance'] ;
                    echo $item_balance < 0 ? '('.str_replace('-','',number_format($item_balance, 3)).')' : number_format($item_balance, 3); 
                    ?>
                    </a>
                </td>
            </tr>
            <tr>
                <th><?php __('Account Type') ?></th>
                <td>
                    <?php echo $data['mode'] == '1' ? 'Prepaid': 'Postpaid' ?>
                </td>
            </tr>
            <tr>
                <th><?php __('Available Credit') ?></th>
                <td><?php echo abs($data['allowed_credit']); ?></td>
            </tr>
            <tr>
                <th><?php __('Egress Trunk') ?></th>
                <td>
                    <?php echo $data['egress_count'] ?>
                </td>
            </tr>
            <tr>
                <th><?php __('Ingress Trunk') ?></th>
                <td>
                    <?php echo $data['ingress_count'] ?>
                </td>
            </tr>
        </tbody>
    </table>
    
    <!--
    <table class="list">
        <thead>
            <tr>
                <td><?php __('Name') ?></td>
                <td><?php __('Mutual Balance') ?></td>
                <td><?php __('Actual Balance') ?></td>
                <td><?php __('Mode') ?></td>
                <td><?php __('Egress Trunk') ?></td>
                <td><?php __('Ingress Trunk') ?></td>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><?php echo $data['name'] ?></td>
                <td>
                    <?php
                        $mutal_total = $data['mutual_ingress'] + $data['mutual_engress'];
                        echo $mutal_total < 0 ? '('.str_replace('-','',number_format($mutal_total, 3)).')' : number_format($mutal_total, 3);
                    ?>
                </td>
                <td>
                    <?php 
                    $item_balance = $data['balance'] + abs($data['allowed_credit']);
                    echo $item_balance < 0 ? '('.str_replace('-','',number_format($item_balance, 3)).')' : number_format($item_balance, 3); 
                    ?>
                </td>
                <td>
                    <?php echo $data['mode'] == '1' ? 'Prepaid': 'Postpaid' ?>
                </td>
                <td><?php echo $data['egress_count'] ?></td>
                <td><?php echo $data['ingress_count'] ?></td>
            </tr>
        </tbody>
    </table>
    -->
</div>