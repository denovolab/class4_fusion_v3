<div class="dialog_form">
<input type="text" id="search_name" class="in-text input in-input" value="<?php echo $name; ?>">
    
<table class="list">
    <thead>
        <tr>
            <th>Name</th>
            <th>Active</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($clients as $client): ?>
        <tr>
            <td><?php echo $client[0]['name'] ?></td>
            <td><?php echo $client[0]['status'] == 1 ? 'Yes' : 'No' ?></td>
            <td>
                <a class="assign_client" client_id="<?php echo $client[0]['client_id'] ?>" client_name="<?php echo $client[0]['name'] ?>" href="###">
                    <img src="<?php echo $this->webroot ?>images/add.png">
                </a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
</div>