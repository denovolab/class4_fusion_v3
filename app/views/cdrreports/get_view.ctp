 <?php if (empty($logs)): ?>
    <div class="msg viewss">No data found</div>
    <?php else: ?>
    <table class="list viewss">
        
        <tbody>
            <?php foreach($logs as $item): ?>
            <tr>
                <td>
                    <a class="views" title="Download" href="<?php echo $this->webroot ?>cdrreports/export_log_item_down?key=<?php echo base64_encode($id);?>&file=<?php echo urlencode($item); ?>">
                        <?php echo $item; ?> <img src="<?php echo $this->webroot ?>images/export.png">
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
      <?php endif; ?>
