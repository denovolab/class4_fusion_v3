<style type="text/css">
#add_panel {display:none;}
</style>

<div id="title">
    <h1>
        <?php __('Routing')  ;  ?>
        &gt;&gt;
        <?php echo __('Trunk Priority')?>
    </h1>
    
    <ul id="title-menu">
        <li>
            <a id="add" class="link_btn" href="##">
                <img width="16" height="16" alt="" src="<?php echo $this->webroot; ?>images/add.png">
                <?php __('Create New');?>
            </a>
        </li>
        <li>
            <a class="link_back" href="<?php echo $this->webroot; ?>dynamicroutes/view">
                <img height="16" width="16" src="<?php echo $this->webroot; ?>images/icon_back_white.png" alt="Back">
                <?php __('Back');?>
            </a>
        </li>
    </ul>
    <ul id="title-search">
        <form method="get" onsubmit="loading();">
        <li> 
            <input type="text" id="search-_q" class="in-search default-value input in-text defaultText in-input" title="Search" value="Search" name="search" />
            <input class="search_submit input in-submit" type="submit" value="" name="submit">
        </li>
        <li title="advanced search »" onclick="advSearchToggle();" id="title-search-adv" style="display: list-item;" class=" "></li>
        </form>
    </ul>
</div>

<div id="container">
    <fieldset style="margin-left: 1px; width: 100%; display: <?php  echo isset($url_get['advsearch']) ? 'block' :'none';?>;" id="advsearch" class="title-block">
    <form method="get" action="">
      <input type="hidden" name="advsearch" class="input in-hidden">
      <table style="width:100%">
        <tr>
          <td><?php echo __('ASR range',true);?>:</td>
          <td style="text-align: left;">
            <input type="text" style="width:120px;"name="asr_min" value="<?php echo $common->set_get_value('asr_min') ?>" />
            <br />
            ~
            <br />
            <input type="text" style="width:120px;"name="asr_max" value="<?php echo $common->set_get_value('asr_max') ?>" >
          </td>
          <td style="width:10%;text-align: right;"><?php echo __('ACD range',true);?>:</td>
          <td style="text-align: left;">
           <input type="text" style="width:120px;"name="acd_min" value="<?php echo $common->set_get_value('acd_min') ?>" />
            <br />
            ~
            <br />
           <input type="text" style="width:120px;"name="acd_max" value="<?php echo $common->set_get_value('acd_max') ?>" />
          </td>
          <td style="width:10%;text-align: right;"><?php echo __('ALOC range',true);?>:</td>
          <td style="text-align: left;">
            <input type="text" style="width:120px;"name="aloc_min" value="<?php echo $common->set_get_value('aloc_min') ?>" />
            <br />
            ~
            <br />
            <input type="text" style="width:120px;"name="aloc_max" value="<?php echo $common->set_get_value('aloc_max') ?>" />
          </td>
          <td style="width:10%;text-align: right;"><?php echo __('PDD range',true);?>:</td>
          <td style="text-align: left;">
            <input type="text" style="width:120px;"name="pdd_min" value="<?php echo $common->set_get_value('pdd_min') ?>" />
            <br />
            ~
            <br />
            <input type="text" style="width:120px;"name="pdd_max" value="<?php echo $common->set_get_value('pdd_max') ?>" />
          </td>
          <td style="width:10%;text-align: right;"><?php echo __('ABR range',true);?>:</td>
          <td style="text-align: left;">
            <input type="text" style="width:120px;"name="abr_min" value="<?php echo $common->set_get_value('abr_min') ?>" />
            <br />
            ~
            <br />
            <input type="text" style="width:120px;"name="abr_max" value="<?php echo $common->set_get_value('abr_max') ?>" />
          </td>
        </tr>
        <tr>
            <td colspan="10" style="text-align:right;"><input type="submit" value="Query" /></td>
        </tr>
      </table>
    </form>
  </fieldset>
<!--  <ul class="tabs">
    <li class="active"><a href="###"><img width="16" height="16" src="<?php echo $this->webroot; ?>images/menuIcon.gif"> List</a></li>
    <li><a href="<?php echo $this->webroot; ?>dynamicroutes/qos_import/<?php echo $this->params['pass'][0];  ?>"><img width="16" height="16" src="<?php echo $this->webroot; ?>images/import.png"> Import</a></li>
    <li><a href="###"><img width="16" height="16" src="<?php echo $this->webroot; ?>images/export.png"> export</a></li>
  </ul>-->
    <?php
        $qoss =$p->getDataArray();
    ?>
    <div id="toppage"></div>
    <table class="list">
        <thead>
            <tr>
                <td>Prefix</td>
                <td>Min ASR</td>
                <td>Max ASR</td>
                <td>Min ABR</td>
                <td>Max ABR</td>
                <td>Min ACD</td>
                <td>Max ACD</td>
                <td>Min PDD</td>
                <td>Max PDD</td>
                <td>Min ALOC</td>
                <td>Max ALOC</td>
                <td>Max Price</td>
                <td>Action</td>
            </tr>
        </thead>
        <tbody>
            <tr id="add_panel">
                <td>
                    <input type="text" name="digits" style="width:60px;" />
                           
                </td>
                <td>
                    <input type="text" name="min_asr" style="width:40px;" />
                </td>
                <td>
                    <input type="text" name="max_asr" style="width:40px;" />
                </td>
                <td>
                    <input type="text" name="min_abr" style="width:40px;" />
                </td>
                <td>
                    <input type="text" name="max_abr" style="width:40px;" />
                </td>
                <td>
                    <input type="text" name="min_acd" style="width:40px;" />
                </td>
                <td>
                    <input type="text" name="max_acd" style="width:40px;" />
                </td>
                <td>
                    <input type="text" name="min_pdd" style="width:40px;" />
                </td>
                <td>
                    <input type="text" name="max_pdd" style="width:40px;" />
                </td>
                <td>
                    <input type="text" name="min_aloc" style="width:40px;" />
                </td>
                <td>
                    <input type="text" name="max_aloc" style="width:40px;" />
                </td>
                <td>
                    <input type="text" name="limit_price" style="width:40px;" />
                </td>
                <td>
                    <a href="###" id="save" title="<?php __('Save') ?>">
                        <img src="<?php echo $this->webroot; ?>images/menuIcon_004.gif">
                    </a>
                    <a href="###" id="cancel" title="<?php __('Cancel') ?>">
                        <img src="<?php echo $this->webroot; ?>images/delete.png">
                    </a>
                </td>
            </tr>
            <?php foreach($qoss as $qos): ?>
            <tr>
                <td><?php echo $qos[0]['digits']; ?></td>
                <td><?php echo $qos[0]['min_asr']; ?></td>
                <td><?php echo $qos[0]['max_asr']; ?></td>
                <td><?php echo $qos[0]['min_abr']; ?></td>
                <td><?php echo $qos[0]['max_abr']; ?></td>
                <td><?php echo $qos[0]['min_acd']; ?></td>
                <td><?php echo $qos[0]['max_acd']; ?></td>
                <td><?php echo $qos[0]['min_pdd']; ?></td>
                <td><?php echo $qos[0]['max_pdd']; ?></td>
                <td><?php echo $qos[0]['min_aloc']; ?></td>
                <td><?php echo $qos[0]['max_aloc']; ?></td>
                <td><?php echo $qos[0]['limit_price']; ?></td>
                <td>
                    <a href="###" class="edit" control="<?php echo $qos[0]['id']; ?>">
                        <img src="<?php echo $this->webroot; ?>images/editicon.gif" title="<?php __('Edit') ?>">
                    </a>
                    <a href="<?php echo $this->webroot; ?>dynamicroutes/delete_qos/<?php echo $qos[0]['id']; ?>/<?php echo $dynamic_id ?>" title="<?php __('Delete') ?>">
                        <img src="<?php echo $this->webroot; ?>images/delete.png">
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div id="tmppage"> <?php echo $this->element('page');?> </div>
</div>

<script type="text/javascript">
    
function checkint(input, name)
{
    var re = /^[0-9]+$/; //判断字符串是否为数字 //判断正整数 /^[1-9]+[0-9]*]*$/
    if (!re.test(input))
    {
        jQuery.jGrowl(name + " must be an integer.",{theme:'jmsg-error'}); 
        return false;
    }
    return true;
} 

function checklarge(input, max, name)
{
    if(parseInt(input) > parseInt(max)) {
        jQuery.jGrowl(name + " can not greater than " + max + " .",{theme:'jmsg-error'}); 
        return false;
    }
    return true;    
}

function checkless(input, min, name) {
    if(parseInt(input) < parseInt(min)) {
        jQuery.jGrowl(name + " can not less than " + min + " .",{theme:'jmsg-error'}); 
        return false;
    }
    return true;  
}

$(function() {
    $('#add').click(function() {
        $('#add_panel').show();
    });

    $('#cancel').click(function() {
        $('#add_panel input').val('');
        $('#add_panel').hide();
    });

    $('#save').click(function() {
        var digits = $('#add_panel input:eq(0)').val();
        var min_asr = $('#add_panel input:eq(1)').val();
        var max_asr = $('#add_panel input:eq(2)').val();
        var min_abr = $('#add_panel input:eq(3)').val();
        var max_abr = $('#add_panel input:eq(4)').val();
        var min_acd = $('#add_panel input:eq(5)').val();
        var max_acd = $('#add_panel input:eq(6)').val();
        var min_pdd = $('#add_panel input:eq(7)').val();
        var max_pdd = $('#add_panel input:eq(8)').val();
        var min_aloc = $('#add_panel input:eq(9)').val();
        var max_aloc = $('#add_panel input:eq(10)').val();
        var limit_price = $('#add_panel input:eq(11)').val();
        var dynamic_id = <?php echo $dynamic_id ?>;
        if(!checkint(digits, "Prefix")) {
            return false;
        }
        if(!checklarge(min_asr, 100, "Min ASR")) {
            return false;
        }
        if(!checklarge(max_asr, 100, "MAX ASR")) {
            return false;
        }
        if(!checkless(max_asr, min_asr, "MAX ASR")) {
            return false;
        }
        if(!checkless(max_abr, min_abr, "MAX ABR")) {
            return false;
        }
        
        if(!checkless(max_acd, min_acd, "MAX ACD")) {
            return false;
        }
        
        if(!checkless(max_pdd, min_pdd, "MAX PDD")) {
            return false;
        }
        
        if(!checkless(max_aloc, min_aloc, "MAX ALOC")) {
            return false;
        }
        
        
        if(!checklarge(min_abr, 100, "Min ABR")) {
            return false;
        }
        if(!checklarge(max_abr, 100, "MAX ABR")) {
            return false;
        }
        $.ajax({
            url      : '<?php echo $this->webroot; ?>dynamicroutes/create_qos',
            type     : 'POST',
            dataType : 'text',
            data     : {digits:digits,min_asr:min_asr,max_asr:max_asr,min_abr:min_abr,max_abr:max_abr,min_acd:min_acd,max_acd:max_acd,min_pdd:min_pdd,max_pdd:max_pdd,min_aloc:min_aloc,max_aloc:max_aloc,limit_price:limit_price,dynamic_id:dynamic_id},
            success  : function(data) {
                if($.trim(data) == '2') {
                    jQuery.jGrowl("The field Prefix duplicate!",{theme:'jmsg-error'}); 
                } else {
                    window.location.reload();
                }
            }
        });
    });

    var editHandle = function() {
        var $this = $(this);
        var $tr = $this.parents('tr').clone(true);
        
        $this.html('<img title="Save" src="<?php echo $this->webroot; ?>images/menuIcon_004.gif" />');
        $this.next().click(function() {
            $this.parents('tr').replaceWith($tr);
            return false;
        }).find('img').attr('title', 'Cancel');
        var $prefix = $this.parent().siblings('td:eq(0)'); 
        var $min_asr = $this.parent().siblings('td:eq(1)'); 
        var $max_asr = $this.parent().siblings('td:eq(2)'); 
        var $min_abr = $this.parent().siblings('td:eq(3)'); 
        var $max_abr = $this.parent().siblings('td:eq(4)'); 
        var $min_acd = $this.parent().siblings('td:eq(5)'); 
        var $max_acd = $this.parent().siblings('td:eq(6)'); 
        var $min_pdd = $this.parent().siblings('td:eq(7)'); 
        var $max_pdd = $this.parent().siblings('td:eq(8)'); 
        var $min_aloc = $this.parent().siblings('td:eq(9)'); 
        var $max_aloc = $this.parent().siblings('td:eq(10)'); 
        var $limit_price = $this.parent().siblings('td:eq(11)'); 
        $prefix.html('<input style="width:60px;" class="input in-text in-input" value="'+$.trim($prefix.text())+'">');
        $min_asr.html('<input style="width:40px;" class="input in-text in-input" value="'+$.trim($min_asr.text())+'">');
        $max_asr.html('<input style="width:40px;" class="input in-text in-input" value="'+$.trim($max_asr.text())+'">');
        $min_abr.html('<input style="width:40px;" class="input in-text in-input" value="'+$.trim($min_abr.text())+'">');
        $max_abr.html('<input style="width:40px;" class="input in-text in-input" value="'+$.trim($max_abr.text())+'">');
        $min_acd.html('<input style="width:40px;" class="input in-text in-input" value="'+$.trim($min_acd.text())+'">');
        $max_acd.html('<input style="width:40px;" class="input in-text in-input" value="'+$.trim($max_acd.text())+'">');
        $min_pdd.html('<input style="width:40px;" class="input in-text in-input" value="'+$.trim($min_pdd.text())+'">');
        $max_pdd.html('<input style="width:40px;" class="input in-text in-input" value="'+$.trim($max_pdd.text())+'">');
        $min_aloc.html('<input style="width:40px;" class="input in-text in-input" value="'+$.trim($min_aloc.text())+'">');
        $max_aloc.html('<input style="width:40px;" class="input in-text in-input" value="'+$.trim($max_aloc.text())+'">');
        $limit_price.html('<input style="width:40px;" class="input in-text in-input" value="'+$.trim($limit_price.text())+'">');
        $this.unbind('click');
        $this.bind('click', function() {
            var qos_id = $this.attr('control');
            var prefix = $prefix.find('input').val();
            var min_asr = $min_asr.find('input').val();
            var max_asr = $max_asr.find('input').val();
            var min_abr = $min_abr.find('input').val();
            var max_abr = $max_abr.find('input').val();
            var min_acd = $min_acd.find('input').val();
            var max_acd = $max_acd.find('input').val();
            var min_pdd = $min_pdd.find('input').val();
            var max_pdd = $max_pdd.find('input').val();
            var min_aloc = $min_aloc.find('input').val();
            var max_aloc = $max_aloc.find('input').val();
            var limit_price = $limit_price.find('input').val();
            var dynamic_id = <?php echo $dynamic_id ?>;
            if(!checkint(prefix, "Prefix")) {
                return false;
            }
            if(!checklarge(min_asr, 100, "Min ASR")) {
                return false;
            }
            if(!checklarge(max_asr, 100, "MAX ASR")) {
                return false;
            }
            

            if(!checkless(max_asr, min_asr, "MAX ASR")) {
                return false;
            }

            if(!checkless(max_abr, min_abr, "MAX ABR")) {
                return false;
            }

            if(!checkless(max_acd, min_acd, "MAX ACD")) {
                return false;
            }

            if(!checkless(max_pdd, min_pdd, "MAX PDD")) {
                return false;
            }

            if(!checkless(max_aloc, min_aloc, "MAX ALOC")) {
                return false;
            }


            if(!checklarge(min_abr, 100, "Min ABR")) {
                return false;
            }
            if(!checklarge(max_abr, 100, "MAX ABR")) {
                return false;
            }
            $.ajax({
                'url'       :   '<?php echo $this->webroot ?>dynamicroutes/update_qos',
                'type'      :   'POST',
                'dataType'  :   'text',
                'data'      :   {qos_id:qos_id, prefix:prefix,min_asr:min_asr,max_asr:max_asr,min_abr:min_abr,max_abr:max_abr,min_acd:min_acd,max_acd:max_acd,min_pdd:min_pdd,max_pdd:max_pdd,min_aloc:min_aloc,max_aloc:max_aloc,limit_price:limit_price,dynamic_id:dynamic_id},
                'success'   :   function(data) {
                    if($.trim(data) == '1') {
                        jQuery.jGrowl("Succeeded",{theme:'jmsg-success'}); 
                        $prefix.text(prefix);
                        $min_asr.text(min_asr);
                        $max_asr.text(max_asr);
                        $min_abr.text(min_abr);
                        $max_abr.text(max_abr);
                        $min_acd.text(min_acd);
                        $max_acd.text(max_acd);
                        $min_pdd.text(min_pdd);
                        $max_pdd.text(max_pdd);
                        $min_aloc.text(min_aloc);
                        $max_aloc.text(max_aloc);
                        $limit_price.text(limit_price);
                        $this.html('<img title="Edit" src="<?php echo $this->webroot; ?>images/editicon.gif" />');
                        $this.unbind('click');
                        $this.bind('click', editHandle);
                    } else {
                        jQuery.jGrowl("Prefix duplicate!",{theme:'jmsg-error'}); 
                    }
                }
            });
        });
    }
    
    $('a.edit').bind('click', editHandle);
});
</script>