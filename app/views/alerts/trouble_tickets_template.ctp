<div id="title">
    <h1><?php echo __('Trouble Tickets',true);?>&gt;&gt;<?php echo __('Mail Templates',true);?></h1>
    <ul id="title-menu">
        <li>
            <a href="<?php echo $this->webroot; ?>alerts/trouble_tickets_template_create" title="create action" id="add" class="link_btn">
                <img height="16" width="16" src="<?php echo $this->webroot; ?>images/add.png" alt="">Create New            
            </a>
        </li>
    </ul>
</div>

<div id="container">
    <ul class="tabs">
        <li>
            <a href="<?php echo $this->webroot; ?>alerts/rule">
                <img width="16" height="16" src="<?php echo $this->webroot; ?>images/ruler.png">Rule			
            </a>
        </li>
        <li>
            <a href="<?php echo $this->webroot; ?>alerts/action">
                <img width="16" height="16" src="<?php echo $this->webroot; ?>images/action.png">Action			
            </a>
        </li>
        <li>
            <a href="<?php echo $this->webroot; ?>alerts/condition">
                <img width="16" height="16" src="<?php echo $this->webroot; ?>images/condition.png">Condition			
            </a>
        </li>
        <li>
            <a href="<?php echo $this->webroot; ?>alerts/block_ani">
                <img width="16" height="16" src="<?php echo $this->webroot; ?>images/fail.png">Block			
            </a>
        </li>
        <li>
            <a href="<?php echo $this->webroot; ?>alerts/trouble_tickets">
                <img width="16" height="16" src="<?php echo $this->webroot; ?>images/trouble_tickets.png">Trouble Tickets			
            </a>
        </li>
        <li class="active">
            <a href="<?php echo $this->webroot; ?>alerts/trouble_tickets_template">
                <img width="16" height="16" src="<?php echo $this->webroot; ?>images/email.gif">Trouble Tickets Mail Template			
            </a>
        </li>
    </ul> 
    <?php
    if(empty($this->data)): 
    ?>
    <div class="msg"><?php echo __('no_data_found',true);?></div>
    <table class="list" style="display:none;">
        <thead>
             <tr>
                <td>Name</td>
                <td>Title</td>
                <td>Created At</td>
                <td>Updated At</td>
                <td>Created By</td>
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
                <td>Name</td>
                <td>Created At</td>
                <td>Updated At</td>
                <td>Created By</td>
                <td>Action</td>
            </tr>
        </thead>

        <tbody>
            <?php foreach($this->data as $item): ?>
            <tr>
                <td><?php echo $item['TroubleTicketsTemplate']['name']; ?></td>
                <td><?php echo $item['TroubleTicketsTemplate']['created_at']; ?></td>
                <td><?php echo $item['TroubleTicketsTemplate']['updated_at']; ?></td>
                <td><?php echo $item['TroubleTicketsTemplate']['updated_by']; ?></td>
                <td>
                    <a title="Edit"  href="<?php echo $this->webroot ?>alerts/trouble_tickets_template_edit/<?php echo $item['TroubleTicketsTemplate']['id']; ?>">
                        <img src="<?php echo $this->webroot?>images/editicon.gif"/>
                    </a>

                    <a title="Delete" onclick="return confirm('Are your sure do this?')" class="delete" href='<?php echo $this->webroot ?>alerts/trouble_tickets_template_delete/<?php echo $item['TroubleTicketsTemplate']['id']; ?>'>
                       <img src="<?php echo $this->webroot?>images/delete.png "/>
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php echo $this->element("xpage")?>
    <?php endif; ?>
</div>
