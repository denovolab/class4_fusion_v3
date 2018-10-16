<div id="title">
    <h1><?php __('Statistics');?> &gt;&gt; <?php __('Loop Detection');?></h1>
</div>

<div id="container">
    
    <div id="search_panel" style="text-align:right;">
        <form method="get" name="myform">
        <?php __('Detect For:'); ?>
        <select name="duration">
            <option value="5" <?php if($duration == 5) echo 'selected'; ?>>5 min</option>
            <option value="10" <?php if($duration == 10) echo 'selected'; ?>>10 min</option>
            <option value="15" <?php if($duration == 15) echo 'selected'; ?>>15 min</option>
            <option value="30" <?php if($duration == 30) echo 'selected'; ?>>30 min</option>
        </select>
        
        <?php __('Threshold:'); ?>
        <input type="text" name="threshold" value="<?php echo $threshold; ?>" />
        <input type="submit" value="<?php __('Query'); ?>" />
        </form>
    </div>
    
    <table class="list">
        <thead>
            <tr>
                <td><?php __('Ingress Trunk'); ?></td>
                <td><?php __('Egress Trunk'); ?></td>
                <td><?php __('Orig ANI'); ?></td>
                <td><?php __('Orig DNIS'); ?></td>
                <td><?php __('Counts'); ?></td>
                <td><?php __('Action'); ?></td>
            </tr>
        </thead>
        
        <tbody>
            <?php foreach($data as $item): ?>
            <tr>
                <td><?php echo $item[0]['ingress_trunk']; ?></td>
                <td><?php echo $item[0]['egress_trunk']; ?></td>
                <td class="ani"><?php echo $item[0]['origination_source_number']; ?></td>
                <td class="dnis"><?php echo $item[0]['origination_destination_number']; ?></td>
                <td><?php echo $item[0]['count']; ?></td>
                <td>
                    <a href="###" class="blockbtn" ingress_id="<?php echo $item[0]['ingress_id']; ?>" egress_id="<?php echo $item[0]['egress_id']; ?>">
                        <input type="button" value="Block" />
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    
</div>

<script type="text/javascript">
    $(function() {
        $('a.blockbtn').click(function() {
            var $this = $(this);
            var ingress_id = $this.attr('ingress_id');
            var egress_id = $this.attr('egress_id');
            var ani = $this.parent().prev().prev().prev().text();
            var dnis = $this.parent().prev().prev().text();
            $.ajax({
                'url' : '<?php echo $this->webroot; ?>loopdetection/put_block_list',
                'type' : 'POST',
                'dataType' : 'text',
                'data' : {'ingress_id':ingress_id, 'egress_id':egress_id, 'ani':ani, 'dnis':dnis},
                'success' : function(data) {
                    jQuery.jGrowl('Succeeded',{theme:'jmsg-success'});
                    window.setTimeout("window.location.reload();", 3000);
                }
            });
        });
    });
</script>