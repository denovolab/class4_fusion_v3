<style>
    tr.tr-update {
        transition: background-color 0.5s ease;
    }
</style>

<div id="title">
    <h1>CDR Export Log</h1>
</div>

<div id="container">
    <ul class="tabs">
        <li>
            <a href="<?php echo $this->webroot; ?>cdrreports/summary_reports">
                <img width="16" height="16" src="<?php echo $this->webroot; ?>images/list.png">
                CDR Search
            </a>
        </li>
        <li class="active">
            <a href="<?php echo $this->webroot; ?>cdrreports/export_log">
                <img width="16" height="16" src="<?php echo $this->webroot; ?>images/export.png">
                CDR Export Log
            </a>
        </li>
        <li>
            <a href="<?php echo $this->webroot; ?>cdrreports/mail_send_log">
                <img width="16" height="16" src="<?php echo $this->webroot; ?>images/menuIcon.gif">
                Mail CDR Log
            </a>
        </li>
    </ul>

    <?php if (empty($this->data)): ?>
        <div class="msg">No data found</div>
    <?php else: ?>
        <?php echo $this->element("xpage")?>
        <table class="list">
            <thead>
            <tr>
                <td>Job ID</td>
                <td>Tiggered Start Time</td>
                <td>Tiggered End Time</td>
                <td>Start Time</td>
                <td>End Time</td>
                <td>Status</td>
                <td>Progress</td>
                <!--<td>File Size</td>-->
                <td>Action</td>
            </tr>
            </thead>

            <tbody>
            <?php foreach($this->data as $item): ?>
                <?php

                $not_error  = 't';
                if(!empty($item['CdrExportLog']['backend_pid']) && (($item['CdrExportLog']['status'] == 1) || ($item['CdrExportLog']['status'] == 2)  ) ){
                    $sql = "SELECT pg_terminate_backend({$item['CdrExportLog']['backend_pid']})";
                    $not_error = $CdrExportLog->query($sql);
                    $not_error = $not_error[0][0]['pg_terminate_backend'];
                }

                ?>
                <tr <?php if ($item['CdrExportLog']['status'] != 4 and $item['CdrExportLog']['status'] != 5) { echo "class='tr-update' data-id='{$item['CdrExportLog']['id']}'"; } ?> >
                    <td><?php echo $item['CdrExportLog']['id']; ?></td>
                    <td><?php echo $item['CdrExportLog']['export_time']; ?></td>
                    <td><?php echo $item['CdrExportLog']['finished_time']; ?></td>
                    <td><?php echo $item['CdrExportLog']['cdr_start_time']; ?></td>
                    <td><?php echo $item['CdrExportLog']['cdr_end_time']; ?></td>
                    <td><?php
                        /*if(!$not_error){
                            echo "<span style='color:red;' >ERROR</span>";
                        }else{*/
                        echo $status[$item['CdrExportLog']['status']];
                        //}
                        ?></td>
                    <td><?php echo $item['CdrExportLog']['completed_days'] ?>/<?php echo $item['CdrExportLog']['total_days'] ?></td>
                    <td>

                        <img class="td_loading" style="display: none;"  title="loading"  src="<?php echo  $this->webroot.'images/rate-progress.gif'?>"   />

                        <?php
                        if(!$not_error){
                            ?>
                            <!--<a class="views" title="Restart" href="<?php echo $this->webroot ?>cdrreports/restart_export?key=<?php echo base64_encode($item['CdrExportLog']['id']);  ?>">
                        <img src="<?php echo $this->webroot ?>images/bLogins.gif">
                    </a>-->
                            <?php
                        }else{
                            ?>
                            <?php if ($item['CdrExportLog']['status'] == 4): ?>
                                <a class="views" title="Download All" href="<?php echo $this->webroot ?>cdrreports/export_log_down?key=<?php echo base64_encode($item['CdrExportLog']['id']);  ?>">
                                    <img src="<?php echo $this->webroot ?>images/export.png">
                                </a>
                                <a class="views"  title="Rerun" href="<?php echo $this->webroot ?>cdrreports/export_log_rerun?key=<?php echo base64_encode($item['CdrExportLog']['id']);  ?>">
                                    <img src="<?php echo $this->webroot ?>images/egress.png">
                                </a>
                            <?php endif; ?>
                            <?php if ($item['CdrExportLog']['status'] != 4 and $item['CdrExportLog']['status'] != 5): ?>
                                <a class="views"  title="Kill Job" href="<?php echo $this->webroot ?>cdrreports/export_log_kill?key=<?php echo base64_encode($item['CdrExportLog']['id']);  ?>">
                                    <img src="<?php echo $this->webroot ?>images/stop.png">
                                </a>
                            <?php endif; ?>
                            <?php
                        }
                        ?>
                        <?php if ($item['CdrExportLog']['status'] == 3): ?>
                            <a class="views"  title="View" onclick="get_view('<?php echo base64_encode($item['CdrExportLog']['id']);  ?>',this);" href="javascript:void(0)">
                                <img src="<?php echo $this->webroot ?>images/bullet_toggle_plus.png">
                            </a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <?php echo $this->element("xpage")?>
    <?php endif; ?>
</div>


<script>


    function down_select(obj){
        var checkbox = $(obj).parents('table').find('tbody').find('.my_select');
        var id = "";
        $.each(checkbox,function(index,content){
            if(content.checked){
                if(id == ""){
                    id += content.id;
                }else{
                    id += "_" + content.id;
                }
            }
        });


        if(id == ''){
            jQuery.jGrowl("At least one file select.",{theme:'jmsg-error'});
        }else{
            location = "<?php echo $this->webroot?>cdrreports/export_log_down_select/"+id;
        }

    }


    function selectCheckbox(obj){
        var checkbox = $(obj).parents('table').eq(0).find("input[type=checkbox]");

        if(obj.checked){
            $.each(checkbox,function(index,content){
                content.checked = true;
            });
        }else{
            $.each(checkbox,function(index,content){
                content.checked = false;
            });
        }

        //$("input:checkbox, input:radio, input:file").uniform();
    }



    function get_view(id,obj){

        if($(obj).parents('tr').eq(0).next().find('.viewss').length == 0){

            $.ajax({
                'url':'<?php echo $this->webroot ?>cdrreports/get_view?key='+id,
                'dataType':'html',
                'type':'post',
                'data':{'id':id},
                'beforeSend':function(){
                    //$(obj).parents('td').eq(0).find('.td_loading').show();
                    //$(obj).parents('td').eq(0).find('.views').hide();
                },
                'success':function(data){
                    //$(obj).parents('td').eq(0).find('.td_loading').hide();
                    //$(obj).parents('td').eq(0).find('.views').show();
                    $(obj).parents('tr').eq(0).after("<tr><td colspan='8'>"+data+"</td></tr>");
                },
                'error': function(XMLHttpRequest, textStatus, errorThrown) {
                    if(XMLHttpRequest.status == 500){

                    }
                    // $(obj).parents('td').eq(0).find('.td_loading').hide();
                    //$(obj).parents('td').eq(0).find('.views').show();
                    //alert(XMLHttpRequest.status);
                }
            });


        }else{
            $(obj).parents('tr').eq(0).next().remove();
        }
    }
</script>

<script>
    function updateRows() {
        var indexes = [];

        $('.tr-update').each(function (key, item) {
            indexes.push($(item).attr('data-id'));
        });

        if (indexes.length > 0) {
            $.ajax({
                url: "<?php echo $this->webroot; ?>cdrreports/get_export_rows",
                type: "POST",
                format: 'json',
                data: {
                    "ids[]": indexes
                },
                success: function (response) {
                    if (response.length > 0) {
                        let data = JSON.parse(response);

                        for (var item in data) {
                            $('.tr-update[data-id=' + data[item].CdrExportLog.id + ']').find("td:eq(2)").html(data[item].CdrExportLog.finished_time);
                            $('.tr-update[data-id=' + data[item].CdrExportLog.id + ']').find("td:eq(5)").html(data[item].CdrExportLog.textStatus);
                            $('.tr-update[data-id=' + data[item].CdrExportLog.id + ']').find("td:eq(6)").html(data[item].CdrExportLog.completed_days + "/" + data[item].CdrExportLog.total_days);
                            let textAction = '';

                            if (data[item].CdrExportLog.status == 4) {
                                textAction += '<a class="views" title="Download All" href="<?php echo $this->webroot ?>cdrreports/export_log_down?key=' + btoa(data[item].CdrExportLog.id) + '">' +
                                    '<img src="<?php echo $this->webroot ?>images/export.png">' +
                                    '</a>' +
                                    '<a class="views"  title="Rerun" href="<?php echo $this->webroot ?>cdrreports/export_log_rerun?key=' + btoa(data[item].CdrExportLog.id) + '">' +
                                    '<img src="<?php echo $this->webroot ?>images/egress.png">' +
                                    '</a>';
                            }
                            if (data[item].CdrExportLog.status != 4 && data[item].CdrExportLog.status != 5) {
                                textAction += '<a class="views"  title="Kill Job" href="<?php echo $this->webroot ?>cdrreports/export_log_kill?key=' + btoa(data[item].CdrExportLog.id) + '">' +
                                    '<img src="<?php echo $this->webroot ?>images/stop.png">' +
                                    '</a>';
                            }
                            if (data[item].CdrExportLog.status == 3) {
                                textAction += '<a class="views"  title="View" onclick="get_view(' + data[item].CdrExportLog.id + ',this);" href="javascript:void(0)">' +
                                    '<img src="<?php echo $this->webroot ?>images/bullet_toggle_plus.png">' +
                                    '</a>';
                            }
                            $('.tr-update[data-id=' + data[item].CdrExportLog.id + ']').find("td:eq(7)").html(textAction);
                        }
                    }
                }
            });
        }
    }

    $(document).ready(function () {
        setInterval(function () {
            updateRows();
        }, 5000);
    });
</script>