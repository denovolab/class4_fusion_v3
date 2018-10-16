<style type="text/css">
            .list thead{
                background:url("") repeat scroll 0 0 transparent;
            }
            .list thead td:hover{
                background-image:url("");
            }
        </style>
<div id="title">
 <h1><?php  __('Management')?> &gt;&gt;<?php echo __('Import Log')?></h1>
</div>

<div id="container">
     <?php
        $data = $p->getDataArray();
    ?>
    <div id="toppage"></div>
    <?php
        if(count($data) == 0){
    ?>
        <div class="msg"><?php echo __('no_data_found')?></div>
    <?php    
        }else{
    ?>
    <table class="list" >
                <thead>
                    <tr>
                       <td rowspan="2">File Name</td>
                       <td rowspan="2">Status</td>
                       <td colspan="5">Records</td>
                       <td rowspan="2">Method</td>
                       <td rowspan="2">Upload Time</td>
                       <!--<td rowspan="2">Finished Time</td>-->
                       <td rowspan="2">Upload File</td>
                       <td rowspan="2">Error File</td>
                    </tr>
                    
                    <tr>
                       <td>Delete</td>
                       <td>Update</td>
                       <td>Insert</td>
                       <td>Error</td>
                       <td>Reimport</td>
                    </tr>
                </thead>
        <tbody>
            <?php foreach($data as $item): ?>
                    <tr>
                        <td><?=$item[0]['upload_file_name']?></td>
                        <td><?=$item[0]['status']?></td>
                        <td><?=$item[0]['delete_queue']?></td>
                        <td><?=$item[0]['update_queue']?></td>
                        <td><?=$item[0]['insert_queue']?></td>
                        
                        <td><?=$item[0]['error_counter']?></td>
                        <td><?=$item[0]['reimport_counter']?></td>
                        <td>
                            <?php
                                if($item[0]['method'] == 0){
                                    echo "Ignore";
                                }else if($item[0]['method'] == 1){
                                    echo "Delete Existing Records";
                                }else if($item[0]['method'] == 2){
                                    echo "Update Existing Records";
                                }
                            ?>
                        </td>
                        <td><?=$item[0]['time']?></td>
                        <!--<td><?=$log['time']?></td>-->
                        <td>
                            <a href="<?php  echo $this->webroot.'/exchange_import_log/download?file='. urlencode($item[0]['local_file'])?>">Export</a>
                        </td>
                        <td>
                            <a href="<?php  echo $this->webroot.'/exchange_import_log/download?file='. urlencode($item[0]['error_log_file'])?>">Export</a>
                        </td>
                    </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div id="tmppage"> <?php echo $this->element('page');?> </div>
    <?php }?>
</div>




