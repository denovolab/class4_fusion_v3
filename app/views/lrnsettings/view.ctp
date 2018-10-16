<?php if (isset($error) && false): ?>
    <div style="padding:20px;font-size:16px;color:red;text-align:center">
        <?php echo $error; ?>
    </div>
    <?php return ?>
<?php endif ?>

<div id="title">
    <h1><?php __('Configuration') ?> &gt;&gt;<?php __('LRNSetting') ?></h1>
</div>
<div class="container">
    <div>
        <table class="form" style="margin-left:10%;width:70%" >
            <tbody>
                <tr>
                    <td class="label" style="width:10%"><?php echo __('Primary IP', true); ?>:</td>
                    <td class="value" style="width:40%">
                        <input type="text" id="ip1" value="<?php if (isset($post[0][0]['ip1'])) {
    echo $post[0][0]['ip1'];
} ?>" name="ip1" class="input in-text">
                    </td>
                    <td class="label" style="width:10%"><?php echo __('Secondary IP', true); ?>:</td>
                    <td class="value" style="width:40%">
                        <input type="text" id="ip2" value="<?php if (isset($post[0][0]['ip2'])) {
    echo $post[0][0]['ip2'];
} ?>" name="ip2" class="input in-text">
                    </td>
                </tr>
                <tr>
                    <td class="label"><?php echo __('Primary Port', true); ?>:</td>
                    <td class="value "><input  type="text" id="port1" 
                                               value="<?php
                                               if (isset($post[0][0]['port1'])) {
                                                   echo $post[0][0]['port1'];
                                               }
?>" 
                                               name="port1" class="input in-text"></td>

                    <td class="label"><?php echo __('Secondary Port', true); ?>:</td>
                    <td class="value">
                        <input  type="text" id="port2" 
                                value="<?php
                                if (isset($post[0][0]['port2'])) {
                                    echo $post[0][0]['port2'];
                                }
?>" 
                                name="port2" class="input in-text">
                    </td>
                </tr>


                <tr>
                    <td class="label "><?php echo __('Timeout ms', true); ?>:</td>
                    <td class="value ">
                        <input  type="text" id="timeout1" 

                                value="<?php
                                if (isset($post[0][0]['timeout1'])) {
                                    echo $post[0][0]['timeout1'];
                                }
?>" 
                                name="timeout1" class="input in-text">
                    </td>
                    <td class="label "><?php echo __('Retries', true); ?>:</td>
                    <td class="value ">
                        <input type="text" id="timeout2" 
                               value="<?php
                               if (isset($post[0][0]['timeout2'])) {
                                   echo $post[0][0]['timeout2'];
                               }
?>" 
                               name="timeout2" class="input in-text">
                    </td>
                </tr>
            </tbody></table>
    </div>
        <?php if ($_SESSION['role_menu']['Configuration']['lrnsettings']['model_w']) { ?>
        <div id="form_footer">
            <input type="button" value="<?php echo __('submit') ?>" onclick="javascript:postLimit();return false;" class="input in-submit">
        </div>
        <?php } ?>
    <div id="lrn_testing" style="text-align:center;">
        <?php __('Timeout') ?>:
        <input type="text" id="timeout" value="3000" />ms 
        <input type="button" value="Test" id="test_lrn_btn" />
        &nbsp;&nbsp;
        <img src="<?php echo $this->webroot ?>images/progress.gif" id="progress_img" style="display:none;" />
    </div>
    
    
    <table id="lrn_test_result" class="list" style="display:none;">
        <thead>
            <tr>
                <th><?php __('LRN Server') ?></th>
                <th><?php __('Response Time'); ?></th>
                <th><?php __('Result') ?></th>
            </tr>
        </thead>
        <tbody>
            
        </tbody>
    </table>
</div>


<script type="text/javascript">
    function postLimit(){
		

			
        var ip1 = $('#ip1').val();
        var ip2 = $('#ip2').val();
        var port1 = $('#port1').val();
        var port2 = $('#port2').val();
        var timeout1 = $('#timeout1').val();
        var timeout2 = $('#timeout2').val();

        var pattern=/^(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])$/;
        var pattern1=/^\d{1,6}$/;

        if(!pattern.test(ip1)){
            showMessages("[{'field':'#ip1','code':'101','msg':'<?php echo __('inputip', true) ?>'}]");
				
            return false ;
        }


        if(!pattern1.test(port1)){
            showMessages("[{'field':'#ip2','code':'101','msg':'<?php echo __('inputport', true) ?>'}]");
            return false ;
        }



        if(!pattern1.test(timeout1)){
            showMessages("[{'field':'#ip2','code':'101','msg':'<?php echo __('timeoutinvalid', true) ?>'}]");
				
            return false ;
        }


				
        $.post("<?php echo $this->webroot ?>lrnsettings/ajax_update.json",
        {ip1:ip1,
            ip2:ip2,
            port1:port1,
            port2:port2,
            timeout1:timeout1,
            timeout2:timeout2
        },
        function(text){

            showMessages("[{'code':'201','msg':'<?php echo __('lrnsucc', true) ?>'}]");
        },
        'json');
			
		
	
    }

</script>

<script type="text/javascript">
    jQuery(document).ready(function(){
        jQuery('#ip1,#ip2').xkeyvalidate({type:'checkIp'})
    })
</script>

<script type="text/javascript">
    $(function() {
        var $lrn_test_result = $('#lrn_test_result');
        var $lrn_test_result_tbody = $("tbody", $lrn_test_result);
        var okimg = "<img src='<?php echo $this->webroot ?>images/flag-1.png'>";
        var nookimg = "<img src='<?php echo $this->webroot ?>images/flag-0.png'>";
        
        $('#test_lrn_btn').click(function() {
            var timeout = $('#timeout').val();
            $lrn_test_result_tbody.empty()
            $.ajax({
                'url' : '<?php echo $this->webroot; ?>lrnsettings/testing',
                'type' : 'POST',
                'dataType' : 'json',
                'data' : {'timeout':timeout},
                'beforeSend' : function() {
                    $('#progress_img').show();
                },
                'success' : function(data) {
                    $('#progress_img').hide();
                    $lrn_test_result.show();
                    $.each(data, function(index, item) {
                        var $tr = $('<tr>');
                        $tr.append("<td>" + item['lrn_server'] + "</td>");
                        $tr.append("<td>" + item['execute_time'].toFixed(5) + "</td>");
                        if (item['is_ok']) {
                            var show_work = "<a title='It works'>" + okimg + "</a>";
                            $tr.append("<td>" + show_work + "</td>");
                        }
                        else
                        {
                            var show_error = '';
                            if (item['is_timeout'])
                                show_error = "<a title='Timeout'>" + nookimg + "</a>";
                            else
                                show_error = "<a title='Out of work'>" + nookimg + "</a>";
                            $tr.append("<td>" + show_error + "</td>");
                        }
                        $lrn_test_result_tbody.append($tr);
                    });
                    $lrn_test_result.show();
                }
            });        
        });
    
        $("#toggle_btn").toggle(function() {
            $("#sip_pannel").slideUp();
            $("img", $(this)).attr('src', "<?php echo $this->webroot; ?>images/bullet_toggle_plus.png");
        }, function() {
            $("#sip_pannel").slideDown();
            $("img", $(this)).attr('src', "<?php echo $this->webroot; ?>images/bullet_toggle_minus.png");
        });
    });
</script>

