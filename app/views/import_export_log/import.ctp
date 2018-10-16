<style>
    .list thead td:hover {
        background-image:url("../images/list-row-hover.png");
    }
</style>
<div id="title">
    <h1><?php echo __('Log',true);?>&gt;&gt;<?php echo __('Import Log',true);?></h1>
    <?php echo $this->element("search")?>
</div>
<div id="container">
    <?php if(empty($logs)):?>
    <?php echo $this->element('common/no_result')?>
    <?php else:?>
    <?php echo $this->element("xpage")?>

    <table class="list">
        <thead style="background:url(''); ">
            <tr>
                <!--<td rowspan='2'>
                <?php echo $appCommon->show_order('id',__('ID',true)) ?>
                </td>-->
                <td rowspan='2'><?php echo __('User',true);?></td>
                <td rowspan='2'><?php echo __('Object',true);?></td>
                <td rowspan='2'><?php echo __('status',true);?></td>

                <td colspan="3"><?php echo __('Records',true);?></td>



                <td rowspan='2'><?php echo __('Method',true);?></td>
                <td rowspan='2'>
                    <?php echo $appCommon->show_order('time',__('Upload Time',true)) ?>
                </td>
                <td rowspan='2'><?php echo $appCommon->show_order('finished_time',__('Finished Time',true)) ?></td><!--
                <td>Rollback On Error</td>
                <td>With Headers</td>
                -->


                <?php  if ($_SESSION['role_menu']['Log']['import_export_log:import']['model_x']) {?>
                <td rowspan='2'><?php echo __('Upload File',true);?></td>

                <td rowspan='2'><?php echo __('Error File',true);?></td>
                <td rowspan='2'><?php echo __('DB Error File',true);?></td>

                <td rowspan='2'><?php echo __('ReProcess',true);?></td>
                <?php }?>
            </tr>

            <tr>
                <td><?php echo __('Success',true);?></td>
                <td><?php echo __('Failure',true);?></td>
                <td><?php echo __('Duplicate',true);?></td>
            </tr>
        </thead>
        <tbody>
            <?php $m = new ImportExportLog;?>
            <?php foreach($logs as $log):?>
            <tr id="log_<?php echo $log['ImportExportLog']['id']?>">
                <!--<td><?php echo $log['ImportExportLog']['id']?></td>-->
                <td><?php echo $log['User']['name']?></td>
                <td><?php echo  $appImportExportLog->format_object($log['ImportExportLog']['obj'],$log['ImportExportLog']['foreign_name'])?></td>
                <td><?php echo $appImportExportLog->display_status($log['ImportExportLog']['status'],$log['ImportExportLog']['error_file_path'],$log['ImportExportLog']['db_error_file_path'])?></td>

                <td><?php echo $log['ImportExportLog']['success_numbers']?></td>
                <td><?php echo $log['ImportExportLog']['error_row']?></td>
                <td><?php echo $log['ImportExportLog']['duplicate_numbers']?></td>


                <td><?php echo $log['ImportExportLog']['duplicate_type']?></td>
                <td><?php echo $log['ImportExportLog']['time']?></td>
                <td><?php echo $log['ImportExportLog']['finished_time']?></td>
                <!--<td><?php echo array_keys_value($log,'ImportExportLog.ext_attributes.rollback_on_error') ? 'on' : 'off'?></td>
                <td><?php echo array_keys_value($log,'ImportExportLog.ext_attributes.with_headers') ? 'on' : 'off'?></td>
                -->
                <?php  if ($_SESSION['role_menu']['Log']['import_export_log:import']['model_x']) {?>
                <?php if(!empty($log['ImportExportLog']['file_path'])):?>
                <td><a href="<?php echo $this->webroot?>uploads/download_original_file/<?php echo $log['ImportExportLog']['id']?>"><?php echo __('download',true);?></a></td>
                <?php else:?>
                <td></td>
                <?php endif;?>


                <?php if(!empty($log['ImportExportLog']['error_file_path'])):?>
                <td><a href="<?php echo $this->webroot?>uploads/download_error_file/<?php echo $log['ImportExportLog']['id']?>"><?php echo __('download',true);?></a></td>
                <?php else:?>
                <td></td>
                <?php endif;?>


                <?php if(!empty($log['ImportExportLog']['db_error_file_path'])):?>
                <td><a href="<?php echo $this->webroot?>uploads/download_db_error_file/<?php echo $log['ImportExportLog']['id']?>"><?php echo __('download',true);?></a></td>
                <?php else:?>
                <td></td>
                <?php endif;?>



                <?php if(!empty($log['ImportExportLog']['error_file_path']) && !$m->is_processing($log)):?>
                <td><a href="<?php echo $this->webroot?>uploads/reprocess/<?php echo $log['ImportExportLog']['id']?>" target="blank"><?php echo __('Process',true);?></a></td>
                <?php else:?>
                <td></td>
                <?php endif;?>
                <?php }?>
            </tr>
            <?php endforeach;?>
        </tbody>
    </table>
    <?php echo $this->element("xpage")?>
    <?php endif;?>
</div>
