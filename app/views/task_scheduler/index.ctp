<style type="text/css">
    .product_list input {
        display:block; float:left;margin-left: 50px;
    }
    .product_list label {
        display:block; float:left;margin-left: 10px;
    }
</style>
<div id="title">
    <h1>Task Scheduler</h1>
</div>

<div id="container">
    
    <table class="list">
        <thead>
            <tr>
                <th>Name</th>
                <th>Run at</th>
                <th>Last Run</th>
                <th>Active</th>
                <th>Action</th>
            </tr>
        </thead>
        
        <tbody>
            <?php foreach ($taskSchedulers as $taskScheduler): ?>
            <tr>
                <td><?php echo $taskScheduler['TaskScheduler']['name'] ?></td>
                <td><?php echo $taskScheduler['TaskScheduler']['run_at'] ?></td>
                <td><?php echo $taskScheduler['TaskScheduler']['last_run'] ?></td>
                <td>
                    <a href="<?php echo $this->webroot ?>task_scheduler/change_status/<?php echo $taskScheduler['TaskScheduler']['id'] ?>">
                        <?php if ($taskScheduler['TaskScheduler']['active']): ?>
                        <img src="<?php echo $this->webroot ?>images/flag-1.png" />
                        <?php else: ?>
                        <img src="<?php echo $this->webroot ?>images/flag-0.png" />
                        <?php endif; ?>
                    </a>
                </td>
                <td>
                    <a title="Manual Run" href="<?php echo $this->webroot ?>task_scheduler/run/<?php echo $taskScheduler['TaskScheduler']['id'] ?>">
                        <img src="<?php echo $this->webroot ?>images/run.png">
                    </a>
                    <a href="###" edited_value="<?php echo $taskScheduler['TaskScheduler']['id'] ?>" class="edited_item">
                        <img src="<?php echo $this->webroot ?>images/editicon.gif">
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
        <tr>
            <td colspan="5">
                <button id="refresh">Refresh</button>
            </td>
        </tr>
        </tfoot>
    </table>
    
</div>

<div id="dd"> </div>  

<link rel="stylesheet" type="text/css" href="<?php echo $this->webroot?>easyui/themes/default/easyui.css">
<link rel="stylesheet" type="text/css" href="<?php echo $this->webroot?>easyui/themes/icon.css">
<script type="text/javascript" src="<?php echo $this->webroot?>easyui/jquery.easyui.min.js"></script>
<script src="<?php echo $this->webroot  ?>js/jquery.jgrowl.js" type="text/javascript"></script>

<script>
$(function() {
    var $dd = $('#dd');
    var $edited_item = $('.edited_item');
    
    $edited_item.click(function() {
        var edited_value = $(this).attr('edited_value');
        
        $dd.dialog({  
            title: 'Task Scheduler',  
            width: 500,  
            height: 400,  
            closed: false,  
            cache: false,  
            href: '<?php echo $this->webroot?>task_scheduler/edit/' + edited_value,  
            modal: true,
            buttons:[{
                    text:'Save',
                    handler:function(){
                        $('#myform').submit();
                    }
            },{
                    text:'Close',
                    handler:function(){
                        $dd.dialog('close');
                    }
            }]
        });
        
        $dd.dialog('refresh', '<?php echo $this->webroot?>task_scheduler/edit/' + edited_value);  
        
        
    });
    
    $('#refresh').click(function() {
        $.get('<?php echo $this->webroot ?>task_scheduler/refresh', function(data) {
            showMessages("[{'field':'#ingrLimit','code':'201','msg':'Succeeded!'}]");
        });
    });
});
</script>