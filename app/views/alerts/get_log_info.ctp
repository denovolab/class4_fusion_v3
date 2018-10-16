<div class="dialog_form">

    <table class="list">
        <thead>
            <tr>
                <td>Carrier</td>
                <td>Trunk</td>
                <td>Destination</td>
                <td>Action Executed</td>
                <td>Time</td>
            </tr>
        </thead>

        <tbody>
            <?php foreach($result as $item): ?>
            <tr>
                <td><?php echo $item[0]['carrier'] ?></td>
                <td><?php echo $item[0]['trunk'] ?></td>
                <td>
                <?php 
                if(strlen($item[0]['destination']) > 10)
                    echo "<a href='###' full='" .$item[0]['destination'] ."' title='Show All' class='view_code_name'>" . substr($item[0]['destination'], 0 ,10) . "..." . "</a>";
                else
                    echo $item[0]['destination'];
                ?>
                </td>
                <td>
                <?php 
                    if ($item[0]['event_type'] == 8) {
                        echo 'Email to ' .  $item[0]['email_addr'];
                    }
                ?>
                </td>
                <td><?php echo $item[0]['time'] ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    
</div>