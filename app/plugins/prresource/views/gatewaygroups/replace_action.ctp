<div id="title">
    <h1>
        Carrier [<?php echo $client_name ?>] >>Edit <?php echo ucfirst($type); ?> Replace Action[<?php echo $res['Gatewaygroup']['alias']; ?>] 
    </h1>
</div>

<div id="container">
    <?php if ($type == 'egress'): ?>
    <?php echo  $this->element('egress_tab',array('active_tab'=>'replace_action'));?>
    <?php else: ?>
    <?php echo  $this->element('ingress_tab',array('active_tab'=>'replace_action'));?>
    <?php endif; ?>
    <form id="myform" method="post">
        <fieldset>
            <legend>        
                <a href="###" id="add_replace_action">
                    <img src="<?php echo $this->webroot ?>images/add.png"> Add</a>
            </legend>
            <table id="replace_actions" class="list">
                <thead>
                    <tr>
                        <th>ANI Prefix</th>
                        <th colspan="3">Criteria</th>
                        <th>Action</th>
                    </tr>
                    <tr>
                        <th></th>
                        <th>ANI</th>
                        <th>ANI Min Length</th>
                        <th>ANI Max Length</th>
                        <th></th>
                    </tr>
                </thead>

                <tbody>
                    <tr id="clone" style="display: none;">
                        <td>
                            <input type="text" name="ani_prefix[]" />
                        </td>
                        <td>
                            <input type="text" name="ani[]" />
                        </td>
                        <td>
                            <input type="text" name="ani_min_length[]" />
                        </td>
                        <td>
                            <input type="text" name="ani_max_length[]" />
                        </td>
                        <td>
                            <a class="delete_replace_action" href="###">
                                <img width="16" height="16" src="<?php echo $this->webroot ?>images/delete.png">
                            </a>
                        </td>
                    </tr>

                    <?php foreach($result as $item): ?>
                    <tr>
                        <td>
                            <input type="text" name="ani_prefix[]" value="<?php echo $item[0]['ani_prefix']; ?>" />
                        </td>
                        <td>
                            <input type="text" name="ani[]" value="<?php echo $item[0]['ani']; ?>" />
                        </td>
                        <td>
                            <input type="text" name="ani_min_length[]" value="<?php echo $item[0]['ani_min_length']; ?>" />
                        </td>
                        <td>
                            <input type="text" name="ani_max_length[]" value="<?php echo $item[0]['ani_max_length']; ?>" />
                        </td>
                        <td>
                            <a class="delete_replace_action" href="###">
                                <img width="16" height="16" src="<?php echo $this->webroot ?>images/delete.png">
                            </a>
                        </td>
                    </tr>
                    <?php endforeach;?>
                </tbody>
            </table>
            <div id="form_footer">
                <input type="submit" class="input in-submit" value="Submit">

                <input type="reset" class="input in-submit in-reset" value="Reset">
            </div>
        </fieldset>
    </form>
</div>

<script>
    $(function() {
        var $clone = $('#clone').remove();
        var $add_replace_action = $('#add_replace_action');
        var $replace_actions = $('#replace_actions');
        var $delete_replace_action = $('.delete_replace_action');
        
        
        $add_replace_action.click(function() {
            $("tbody", $replace_actions).prepend($clone.clone(true).show());
        });
        
        $delete_replace_action.live('click', function() {
            $(this).parent().parent().remove();
        });
        
    });
</script>