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
                <img width="16" height="16" alt="<?php __('Create New');?>" src="<?php echo $this->webroot; ?>images/add.png">
                <?php __('Create New');?>
            </a>
        </li>
        <li>
            <a id="delete_selected" class="link_btn" href="###">
                <img width="16" height="16" src="<?php echo $this->webroot; ?>images/delete.png" alt="<?php __('Delete Selected');?>">
                <?php __('Delete Selected');?>
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
          <td><?php echo __('Egress Trunk',true);?>:</td>
          <td style="text-align: left;">
              <select name="egress_trunk">
                  <option selected="selected"></option>
                  <?php foreach($egress_trunks as $key => $egress_trunk): ?>
                  <option value="<?php echo $key ?>" <?php echo $common->set_get_select('egress_trunk', $key); ?>><?php echo $egress_trunk ?></option>
                  <?php endforeach; ?>
              </select>
          </td>
          <td style="text-align: right;"><?php echo __('Priority Range',true);?>:</td>
          <td style="text-align: left;">
           <input type="text" style="width:120px;"name="p_start" value="<?php echo $common->set_get_value('p_start') ?>" />
            ~
           <input type="text" style="width:120px;"name="p_end" value="<?php echo $common->set_get_value('p_end') ?>" />
          </td>
           <td style="text-align:right;"><input type="submit" value="Query" /></td>
        </tr>
      </table>
    </form>
  </fieldset>
    <?php
        $pris =$p->getDataArray();
    ?>
    <div id="toppage"></div>
    <table class="list">
        <thead>
            <tr>
                <td><input id="chk" type="checkbox" /></td>
                <td>Prefix</td>
                <td>Egress Trunk</td>
                <td>Priority</td>
                <td>Action</td>
            </tr>
        </thead>
        <tbody>
            <tr id="add_panel">
                <td>
                </td>
                <td>
                    <input type="text" name="digits" />
                </td>
                <td>
                    <select name="egress_trunk">
                        <?php foreach($egress_trunks as $key => $val): ?>
                        <option value="<?php echo $key ?>"><?php echo $val ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
                <td>
                    <select name="resource_pri">
                        <?php for($i=0;$i<10;$i++):?>
                        <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                        <?php endfor; ?>
                    </select>
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
            <?php foreach($pris as $pri): ?>
            <tr>
                <td>
                    <input type="checkbox" value="<?php echo $pri[0]['id']; ?>" />
                </td>
                <td><?php echo $pri[0]['digits']; ?></td>
                <td><?php echo $egress_trunks[$pri[0]['resource_id']]; ?></td>
                <td><?php echo $pri[0]['resource_pri']; ?></td>
                <td>
                    <a href="###" class="edit" control="<?php echo $pri[0]['id']; ?>">
                        <img src="<?php echo $this->webroot; ?>images/editicon.gif" title="<?php __('Edit') ?>">
                    </a>
                    <a href="<?php echo $this->webroot; ?>dynamicroutes/delete_priority/<?php echo $pri[0]['id']; ?>/<?php echo $dynamic_id ?>" title="<?php __('Delete') ?>">
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
        var resource_pri = $('#add_panel select:eq(1)').val();
        var egress_trunk = $('#add_panel select:eq(0)').val();
        var dynamic_id = <?php echo $dynamic_id ?>;
        if(!checkint(digits, "Prefix")) {
            return false;
        }
        $.ajax({
            url      : '<?php echo $this->webroot; ?>dynamicroutes/create_pri',
            type     : 'POST',
            dataType : 'text',
            data     : {digits:digits,resource_pri:resource_pri,egress_trunk:egress_trunk,dynamic_id:dynamic_id},
            success  : function(data) {
                if($.trim(data) == '2') {
                    jQuery.jGrowl("Prefix duplicate!",{theme:'jmsg-error'}); 
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
        var $prefix = $this.parent().siblings('td:eq(1)'); 
        var $trunk = $this.parent().siblings('td:eq(2)'); 
        var $resource_pri = $this.parent().siblings('td:eq(3)'); 
        $prefix.html('<input class="input in-text in-input" value="'+$.trim($prefix.text())+'">');
        var trunk_tag = $('#add_panel select:eq(0)').clone(true);
        trunk_tag.find('option[text="'+$trunk.text()+'"]').attr('selected', true);
        $trunk.html(trunk_tag);
        var resource_pri_tag = $('#add_panel select:eq(1)').clone(true);
        resource_pri_tag.find('option[text="'+$resource_pri.text()+'"]').attr('selected', true);
        $resource_pri.html(resource_pri_tag);
        $trunk.html(trunk_tag);
        $this.unbind('click');
        $this.bind('click', function() {
            var id = $this.attr('control');
            var prefix = $prefix.find('input').val();
            var trunk = $trunk.find('select').val();
            var resource_pri = $resource_pri.find('select').val();
            var dynamic_id = <?php echo $dynamic_id ?>;
            if(!checkint(digits, "Prefix")) {
                return false;
            }
            $.ajax({
                'url'       :   '<?php echo $this->webroot ?>dynamicroutes/update_pri',
                'type'      :   'POST',
                'dataType'  :   'text',
                'data'      :   {id:id, prefix:prefix, trunk:trunk, resource_pri:resource_pri, dynamic_id:dynamic_id},
                'success'   :   function(data) {
                    if($.trim(data) == '1') {
                        jQuery.jGrowl("Succeeded",{theme:'jmsg-success'}); 
                        $prefix.text(prefix);
                        $trunk.text($trunk.find('select option:selected').text());
                        $resource_pri.text($resource_pri.find('select option:selected').text());
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
    
    $('#chk').click(function() {
        $('table.list tbody input:checkbox').attr('checked', $(this).attr('checked'));
    });
    
    $('#delete_selected').click(function() {
        var delete_list = new Array();
        $('table.list tbody input:checkbox:checked').each(function(index, item) {
            delete_list.push($(this).val());
        });
        var delete_str = delete_list.join(',');
        $.ajax({
            'url' : "<?php echo $this->webroot ?>dynamicroutes/delete_mul_priority/<?php echo $dynamic_id ?>",
            'type' : 'POST',
            'dataType' : 'text',
            'data' : {'ids':delete_str},
            'success' : function(data) {
                jQuery.jGrowl("Successfully!",{theme:'jmsg-success'}); 
                window.setTimeout('window.location.reload();', 1000);
            }
        });
    });
});
</script>