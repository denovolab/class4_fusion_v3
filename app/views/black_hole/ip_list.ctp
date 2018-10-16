<style>
    div.modal {
        position: absolute;
        left: 0;
        top: 0;
        height: 100%;
        width: 100%;
        background: rgba(136, 136, 136, 0.7);
    }

    div.modal form {
        background: #fff;
        padding: 10px;
        width: 40%;
        margin: 0 auto;
        margin-top: 100px;
    }

    .modal-header {
        padding: 0 10px;
        height: 29px;
        line-height: 29px;
        border-color: #ffffff;
        background-image: none !important;
        filter: none !important;
        box-shadow: none;
        -moz-box-shadow: none;
        -webkit-box-shadow: none;
        background: #ffffff;
        -webkit-border-radius: 0 0 0 0;
        -moz-border-radius: 0 0 0 0;
        border-radius: 0 0 0 0;
    }

    .modal-header .close {
        color: #7c7c7c;
        text-shadow: none;
        background: none;
        float: right;
        margin: 0px;
        width: 20px;
    }

    .close::after {
        clear: both;
    }

    .widget-body {
        padding: 20px 0px;
    }

    .modal-footer {
        text-align: right;
    }
</style>

<div id="title">
    <h1>
        Monitoring&gt;&gt;'Block Media IP'
    </h1>
    <ul id="title-menu">
        <li><a data-toggle="modal" class="link_btn" href="<?php echo $this->webroot?>black_hole/upload"><img width="16" height="16" src="<?php echo $this->webroot?>images/import.png"> <?php echo __('Upload')?></a></li>
        <li><a data-toggle="modal" class="link_btn" href="#myModalCreateNew"><img width="16" height="16" src="<?php echo $this->webroot?>images/add.png"> <?php echo __('Create New')?></a></li>
        <li><a data-toggle="modal" class="link_btn" href="#myModalCreateNetMask"><img width="16" height="16" src="<?php echo $this->webroot?>images/add.png"> <?php echo __('Create New By NetMask')?></a></li>
        <li><a data-toggle="modal" class="link_btn" href="#myModalDeleteNetMask"><img width="16" height="16" src="<?php echo $this->webroot?>images/delete.png"> <?php echo __('Delete By NetMask')?></a></li>
    </ul>
</div>

<div id="container">
    <?php if (!count($this->data)): ?>
        <div class="msg center"><br /><h2><?php echo __('no_data_found', true); ?></h2></div>
    <?php else: ?>
        <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
            <thead>
            <tr>
                <th><?php echo $appCommon->show_order('ip', __('IP', true)) ?></th>
                <th><?php __('Owner') ?></th>
                <th><?php echo $appCommon->show_order('create_time', __('Created On', true)) ?></th>
                <th><?php __('Created By') ?></th>
                <th><?php __('Action'); ?></th>
            </tr>
            </thead>

            <tbody>
            <?php foreach ($this->data as $item): ?>
                <tr>
                    <td><?php echo $item['SpamTrafficIp']['ip']; ?></td>
                    <td><?php echo $item['SpamTrafficIp']['brief']; ?></td>
                    <td><?php echo $item['SpamTrafficIp']['create_time']; ?></td>
                    <td><?php echo isset($item['SpamTrafficIp']['created_by'])?$item['SpamTrafficIp']['created_by']:'admin'; ?></td>
                    <td>
                        <a title="Delete" onclick="return myconfirm('<?php __('sure to delete') ?>', this)"
                           class="delete" href='<?php echo $this->webroot ?>black_hole/delete_ip/<?php echo base64_encode($item['SpamTrafficIp']['ip']) ?>'>
                            <img width="16" height="16" src="<?php echo $this->webroot?>images/delete.png">
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <div class="row-fluid separator">
            <div class="pagination pagination-large pagination-right margin-none">
                <?php echo $this->element('xpage'); ?>
            </div>
        </div>
        <div class="clearfix"></div>
    <?php endif; ?>
</div>
<div id="myModalCreateNew" class="modal hide">
    <form method="post" action="<?php echo $this->webroot ?>black_hole/save_ip">
        <div class="modal-header">
            <button data-dismiss="modal" class="close" type="button">&times;</button>
            <h3><?php __('Create New'); ?></h3>
        </div>
        <div class="separator"></div>
        <div class="widget-body">

            <table class="table table-bordered">
                <tr>
                    <td class="align_right"><?php echo __('IP')?> </td>
                    <td>
                        <input class="input in-text validate[required,custom[ipv4],ajax[ajaxIPCheck]]" name="ip" class="ip" type="text" >
                    </td>
                </tr>
            </table>

        </div>
        <div class="modal-footer">
            <input type="submit" class="btn btn-primary sub" value="<?php __('Submit'); ?>">
        </div>
    </form>
</div>

<div id="myModalCreateNetMask" class="modal hide">
    <form method="post" action="<?php echo $this->webroot ?>black_hole/save_ip_by_netmask">
    <div class="modal-header">
        <button data-dismiss="modal" class="close" type="button">&times;</button>
        <h3><?php __('Create New By NetMask'); ?></h3>
    </div>
    <div class="separator"></div>
        <div class="widget-body">
            <table class="table table-bordered">
                <tr>
                    <td class="align_right"><?php echo __('IP')?> </td>
                    <td>
                        <input class="input in-text validate[required,custom[ipv4]]" name="netMask_ip" type="text" >/
                        <input class="input in-text width25 validate[required,custom[integer],min[0],max[31]]" name="netMask" maxlength="2" type="text" />
                    </td>
                </tr>
                <tr class="content">
                    <td class="align_right"><?php echo __('Hostname')?> </td>
                    <td><textarea class="input in-textarea" style="width: 100%;" class="detail" name="detail"></textarea></td>
                </tr>
            </table>
        </div>
        <div class="modal-footer">
            <input type="submit" class="btn btn-primary sub" value="<?php __('Submit'); ?>">
        </div>
    </form>
</div>

<div id="myModalDeleteNetMask" class="modal hide">
    <form method="post" action="<?php echo $this->webroot ?>black_hole/delete_ip_by_netmask">
    <div class="modal-header">
        <button data-dismiss="modal" class="close" type="button">&times;</button>
        <h3><?php __('Delete By NetMask'); ?></h3>
    </div>
    <div class="separator"></div>
        <div class="widget-body">
            <table class="table table-bordered">
                <tr>
                    <td class="align_right"><?php echo __('IP')?> </td>
                    <td>
                        <input class="input in-text validate[required,custom[ipv4]]" name="netMask_ip" type="text" >/
                        <input class="input in-text width25 validate[required,custom[integer],min[0],max[31]]" name="netMask" maxlength="2" type="text" />
                    </td>
                </tr>
            </table>
        </div>
        <div class="modal-footer">
            <input type="submit" class="btn btn-primary sub" value="<?php __('Submit'); ?>">
        </div>
    </form>
</div>
<script type="text/javascript">
    $(function() {

        $.extend($.validationEngineLanguage.allRules,{ "ajaxIPCheck": {
            "url": "<?php echo $this->webroot; ?>black_hole/ajax_check_ip",
            "alertText": "* <?php __('IP already exists'); ?>",
            "alertTextLoad": "* <?php __('Validating, please wait'); ?>"}
        });

        $("#myModalCreateNetMask").find('.sub').click(function(){
            if($("#myModalCreateNetMask").find("input[name='netMask_ip']").validationEngine('validate')){
                return;
            }
            if($("#myModalCreateNetMask").find("input[name='netMask']").validationEngine('validate')){
                return;
            }
        });

        setTimeout(function(){
            $('.ColVis_collection .ColVis_radio input').each(function(index, val){
                if(!$(this).is(':checked')){
                    $(this).click();
                }
            })
        }, 1000);

        $("a[data-toggle=modal]").click(function () {
            $("div#" + $(this).attr('href')).show('fast');
        });

        $(".close").click(function () {
            $(this).parent().parent().parent().hide();
        });
    });
</script>


