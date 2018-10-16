<div id="title">
    <h1>Statistics &gt;&gt; Simple CDR Export</h1>
</div>

<div id="container">
    <?php echo $this->element('quickcdr/tabs', array('active' => 'log')); ?>
    <?php echo $this->element("xpage") ?>
    <table class="list">
        <thead>
            <tr>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Type</th>
                <th>Client</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($this->data as $item): ?>
                <tr>
                    <td><?php echo $item['Quickcdr']['start_date'] ?></td>
                    <td><?php echo $item['Quickcdr']['end_date'] ?></td>
                    <td><?php echo (int)$item['Quickcdr']['type'] == 0 ? 'Ingress' : 'Egress' ?></td>
                    <td><?php echo $clients[$item['Quickcdr']['client_id']] ?></td>
                    <td><?php echo $status[$item['Quickcdr']['status']] ?></td>
                    <td>
                        <?php if ($item['Quickcdr']['status'] == 2): ?>
                        <a href="<?php echo $this->webroot ?>quickcdr/export/<?php echo $item['Quickcdr']['id'] ?>" title="Download">
                            <img src="<?php echo $this->webroot ?>images/file.png">
                        </a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php echo $this->element("xpage") ?>
</div>

<div id="dd">
</div>
<link rel="stylesheet" type="text/css" href="<?php echo $this->webroot?>easyui/themes/default/easyui.css">
<link rel="stylesheet" type="text/css" href="<?php echo $this->webroot?>easyui/themes/icon.css">
<script type="text/javascript" src="<?php echo $this->webroot?>easyui/jquery.easyui.min.js"></script>
<script>
    jQuery(function($) {
        var $email_out = $('.email_out');
        var $dd = $('#dd');
        
        $email_out.click(function() {
            var $this = $(this);
            var control = $this.attr('control');

            $dd.dialog({
                title: 'Email out',
                width: 400,
                height: 200,
                closed: false,
                cache: false,
                resizable: true,
                href: '<?php echo $this->webroot ?>hung_calls_detection/send_huang_call_email/' + control,
                modal: true
            });

            $dd.dialog('refresh', '<?php echo $this->webroot ?>hung_calls_detection/send_huang_call_email/' + control);
        });
    });
</script>