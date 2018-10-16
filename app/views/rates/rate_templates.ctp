<div id="title">
    <h1>
        <?php __('System')?>
        &gt;&gt;
        <?php __('Rate sending template'); ?>
    </h1>
</div>

<div id="container">
    <ul class="tabs">
        <li>
            <a href="<?php echo $this->webroot ?>rates/rate_sending">
                <img width="16" height="16" src="<?php echo $this->webroot; ?>images/list.png">Rate sending   		
            </a>
        </li>
        <li class="active">
            <a href="<?php echo $this->webroot ?>rates/rate_templates">
                <img width="16" height="16" src="<?php echo $this->webroot; ?>images/list.png">Template  		
            </a>
        </li>
        <li>
            <a href="<?php echo $this->webroot ?>rates/rate_sending_logging">
                <img width="16" height="16" src="<?php echo $this->webroot; ?>images/list.png">Log  		
            </a>
        </li>
    </ul>
    
    <table class="list">
        <thead>
            <tr>
                <td>Name</td>
                <td>Subject</td>
                <td>Content</td>
                <td>Action</td>
            </tr>
        </thead>
        <tbody>
            <?php foreach($data as $item): ?>
            <tr>
                <td><?php echo $item[0]['name']; ?></td>
                <td><?php echo $item[0]['subject']; ?></td>
                <td><?php echo substr($item[0]['content'], 0, 20);; ?></td>
                <td>
                    <a href="<?php echo $this->webroot ?>rates/edit_template/<?php echo $item[0]['id']; ?>">
                        <img src="<?php echo $this->webroot ?>images/editicon.gif">
                    </a> 
                    <a href="<?php echo $this->webroot ?>rates/delete_template/<?php echo $item[0]['id']; ?>">
                        <img src="<?php echo $this->webroot ?>images/delete.png">
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>