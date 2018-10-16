<div id="title">
	<h1><?php echo __('Log',true);?>&gt;&gt;<?php echo __('Export Log',true);?></h1>
	<?php echo $this->element("search")?>
        <ul id="title-menu">
    		<li>
    					    			<font class="fwhite">Refresh Every:</font>
      <select id="changetime" class="input in-select select">
        <option value="180">3 minutes</option>
        <option value="300">5 minutes</option>
        <option value="800">15 minutes</option>
      </select>
	    			    		</li>
  		</ul>
</div>
<div id="container">
<?php if(empty($logs)):?>
	<?php echo $this->element('common/no_result')?>
<?php else:?>
<?php echo $this->element("xpage")?>
	<table class="list">
	<thead>
	<tr>
		<td><?php echo $appCommon->show_order('id','ID');?></td>
		<td><?php echo __('User',true);?></td>
		<td><?php echo __('Object',true);?></td>
		<td><?php echo __('status',true);?></td>
		<td><?php echo $appCommon->show_order('time','Download Time')?></td>
                <td><?php echo __('File',true);?></td>
	</tr>
	</thead>
	<tbody>
	<?php $m = new ImportExportLog;?>
	<?php foreach($logs as $log):?>
	<tr id="log_<?php echo $log['ImportExportLog']['id']?>">
		<td><?php echo $log['ImportExportLog']['id']?></td>
		<td><?php echo $log['User']['name']?></td>

		<td><?php echo $appImportExportLog->format_object($log['ImportExportLog']['obj'],$log['ImportExportLog']['foreign_name'])
		// $log['ImportExportLog']['obj']?>
		</td>
		<td><?php echo $appImportExportLog->display_export_status($log['ImportExportLog']['status'],$log['ImportExportLog']['error_file_path'],$log['ImportExportLog']['db_error_file_path'])?></td>
		<td><?php echo $log['ImportExportLog']['time']?></td>
                <?php if(file_exists($dbpath . DS . $log['ImportExportLog']['file_path'])): ?>
                <td><a href="<?php echo $this->webroot ?>cdrreports/get_file/<?php echo base64_encode($dbpath . DS . $log['ImportExportLog']['file_path']) ?>">Download</td>
                <?php else: ?>
                <td>-</td>
                <?php endif; ?>
	</tr>
	<?php endforeach;?>
	</tbody>
	</table>
<?php echo $this->element("xpage")?>
<?php endif;?>
</div>

<script type="text/javascript">
$(function() {
    var interv = null;

    $('#changetime').change(function() {
        if(interv) 
            window.clearInterval(interv);
        var time = $(this).val() * 1000;
        interv = window.setInterval("loading();window.location.reload()", time); 
    });

    $('#changetime').change();

});
</script>