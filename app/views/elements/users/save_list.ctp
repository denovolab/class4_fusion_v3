<?php $user_id = array_keys_value($this->params, 'pass.0') ?>


<?php echo $form->create('User', array('action' => 'add')); ?>

<table>
    <col width="50%"/>
    <col width="50%"/>
    <tbody>
        <tr>
            <td class="first" style="vertical-align:top"><table class="form">
                    <tbody>
                        <tr>
                            <td class="label label2"><?php echo __('username') ?>:</td>
                            <td class="value value2"><input  type="hidden"   id="user_id" value="<?php echo array_keys_value($this->params, 'pass.0'); ?>">
                                <?php echo $form->input('name', array('label' => false, 'div' => false, 'type' => 'text', 'maxLength' => '256')); ?></td>
                        </tr>
                        <tr>
                            <td class="label label2"><?php echo __('New', true); ?> <?php echo __('password') ?>:</td>
                            <td class="value value2"><?php echo $form->input('password', array('label' => false, 'maxLength' => '66', 'div' => false, 'type' => 'password')); ?></td>
                        </tr>
                        <tr>
                            <td class="label label2"> <?php echo __('Confirm Password', true); ?>:</td>
                            <td class="value value2"><?php echo $form->input('repassword', array('label' => false, 'maxLength' => '66', 'div' => false, 'type' => 'password', 'name' => '')); ?></td>
                        </tr>
                        <tr>
                            <td class="label label2"><?php echo __('fullname') ?>:</td>
                            <td class="value value2"><?php echo $form->input('fullname', array('label' => false, 'div' => false, 'type' => 'text', 'maxLength' => '256')); ?></td>
                        </tr>
                        <tr>
                            <td class="label label2"><?php echo __('email') ?>:</td>
                            <td class="value value2"><?php echo $form->input('email', array('label' => false, 'div' => false, 'type' => 'text')); ?></td>
                        </tr>
                        <tr>
                            <td class="label label2"><?php __('Landing Page'); ?></td>
                            <td class="value value2">
                                <?php echo $form->input('default_mod2', array('options' => $pris, 'div' => false, 'label' => false, 'type' => 'select', 'style' => 'width:120px;')); ?>
                                <select name="data[User][default_mod]" id="default_mod" style="width:120px;">

                                </select>
                            </td>
                        </tr>
                        <?php $t = $session->read('login_type'); ?>
                    </tbody>
                </table>
            </td>
            <td class="last"  style="vertical-align:top">
                <table class="form">
                    <tbody>
                        <?php if ($t == 3) { ?>
                            <tr>
                                <td class="label label2"><?php echo __('client') ?>:</td>
                                <td class="value value2">
                                    <?php echo $form->input('client_id', array('options' => $appUsers->_get_select_options($ClientList, 'Client', 'client_id', 'name'), 'label' => false, 'div' => false, 'type' => 'select')); ?>
                                </td>
                            </tr>
                        <?php } ?>
                        <tr>
                            <td valign="top" class="label label2"><?php echo __('Activate') ?>:</td>

                            <td class="value value2">
                                <?php echo $form->checkbox('active') ?>

                            </td>               
                        </tr>
                        <tr>
                            <td valign="top" class="label label2"><?php echo __('Role') ?>:</td>
                            <td class="value value2">
                                <?php
                                //echo $form->input('role_id',Array('options'=>$appUsers->_get_select_options($RoleList,'Role','role_id','role_name'),'div'=>false,'label'=>false));
                                echo $form->input('role_id', Array('options' => $role, 'div' => false, 'label' => false));
                                ?>
                            </td>
                        </tr>

                        <?php echo $this->element('users/auth_ip') ?>

                    </tbody>
                </table></td>
        </tr>
            <tr>
                <td colspan="2">
                    <?php if (isset($limits)): ?>
                        <select name="control_carrier[]" style="width:800px;height:500px;" multiple="multiple">
                            <?php foreach ($client as $key => $item): ?>
                                <option value="<?php echo $key; ?>" <?php if (in_array($key, $limits)) echo 'selected="selected"'; ?>><?php echo $item; ?></option>
                            <?php endforeach; ?>
                        </select>
                    <?php else: ?>
                        <select name="control_carrier[]" style="width:800px;height:500px;" multiple="multiple">
                            <?php foreach ($client as $key => $item): ?>
                                <option value="<?php echo $key; ?>"><?php echo $item; ?></option>
                            <?php endforeach; ?>
                        </select>
                    <?php endif; ?>
                </td>
            </tr>
        
    </tbody>
</table>
<div id="form_footer">
    <input type="submit" value="<?php echo __('submit') ?>" class="input in-submit"/>
</div>
<?php echo $form->end(); ?> 
<script type="text/javascript">
    jQuery(document).ready(function(){
        jQuery('#UserName,#UserFullname').xkeyvalidate({type:'strNum'})
        jQuery('#UserEmail').xkeyvalidate({type:'Email'});
        jQuery('#UserAddForm').submit(function(){
            var re =true ;
       
            if(jQuery('#UserName').val()==''){
                jQuery('#UserName').addClass('invalid');
                jQuery.jGrowl('The field User name cannot be NULL.',{theme:'jmsg-error'});
                re= false;
            }
<?php if ($user_id != '') { ?>
                if($('#UserPassword').val()!=''){
                    if(jQuery('#UserRepassword').val()==''){
                        jQuery('#UserRepassword').addClass('invalid');
                        jQuery.jGrowl('The field Confirm password cannot be NULL.',{theme:'jmsg-error'});
                        re= false;
                    }
                    if($('#UserRepassword').val()!=$('#UserPassword').val()){
                        jQuery.jGrowl('Confirm password does not match!',{theme:'jmsg-error'});
                        re=false;
                    }
                }
<?php } else { ?>
                if(jQuery('#UserPassword').val()==''){
                    jQuery('#UserPassword').addClass('invalid');
                    jQuery.jGrowl('The field Password cannot be NULL.',{theme:'jmsg-error'});
                    re= false;
                }else if(jQuery('#UserRepassword').val()==''){
                    jQuery('#UserRepassword').addClass('invalid');
                    jQuery.jGrowl('The field Confirm password cannot be NULL.',{theme:'jmsg-error'});
                    re= false;
                }
                if(!password_same(jQuery('#UserPassword'),jQuery('#UserRepassword'))){
                    re=false;
                }
<?php } ?>
            if($("#UserEmail").val()!=""){		
                if(!jQuery.xkeyvalidate('#UserEmail',{type:'Email'}))
                {
                    re= false;
                }
            }
            
            var role_id = $("#UserRoleId").val();
            var default_mod = $("#default_mod").val();
            
            if (default_mod != '') {
                $.ajax({
                    'url' : '<?php echo $this->webroot ?>users/check_module',
                    'type' : 'POST',
                    'async' : false,
                    'dataType' : 'json',
                    'data' : {'role_id' : role_id, 'module' : default_mod},
                    'success' : function(data) {
                        if(data.count == 0)
                        {
                            jQuery('#default_mod').addClass('invalid');
                            jQuery.jGrowl('The Role has not the appropriate permissions to access the page.',{theme:'jmsg-error'});
                            re = false;
                        }
                    }
                });
            }
            
            return re;
        });
        jQuery('#UserPassword').val('');

    });     
</script>

<script>
    $(function() {
        var $default_mod = $('#default_mod');
        var $deafult_mod2 = $('#UserDefaultMod2');
        $deafult_mod2.change(function() {
            var module_id = $(this).val();
            $.ajax({
                'url' : '<?php echo $this->webroot; ?>users/get_child_pri',
                'type' : 'POST',
                'dataType' : 'json',
                'data' : {'module_id':module_id},
                'success' : function(data) {
                    $default_mod.empty();
                    $.each(data, function(index, item) {
                        var selected = '';
<?php if (isset($this->data['User']['default_mod'])): ?>
                            if (<?php echo $this->data['User']['default_mod'] ?> == item[0]['id'])
                            selected = 'selected="selected"';
<?php endif; ?>
                        $default_mod.append('<option value="' + item[0]['id'] +'" ' + selected +'>' + item[0]['pri_val'] +'</option>');
                    });
                }
            });
        });
        $deafult_mod2.change();
        
        
        
    });
</script>
