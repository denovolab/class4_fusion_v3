<div id="title">
    <h1>Origination &gt;&gt; Edit Client</h1>
    <ul id="title-menu">
        <li> 
            <a href="<?php echo $this->webroot ?>did/clients" class="link_back_new">
                <img width="16" height="16" src="<?php echo $this->webroot ?>images/icon_back_white.png" alt="Back">&nbsp;Back
            </a> 
        </li>
    </ul>
</div>

<div id="container">
    <form id="myform" method="post">
        <table class="form">
            <tbody>
                <tr>
                    <td>Vendor Name:</td>
                    <td>
                        <input type="text" id="vendor_name" name="name" value="<?php echo $client['name'] ?>" autocomplete="off">
                    </td>
                </tr>
                <tr>
                    <td>Login Username:</td>
                    <td>
                        <input type="text" name="login_username" value="<?php echo $client['login_username'] ?>" autocomplete="off">
                    </td>
                </tr>
                <tr>
                    <td>Login Password:</td>
                    <td>
                        <input type="password" name="login_password">
                    </td>
                </tr>
                <tr>
                    <td>Pricing Rule:</td>
                    <td>
                        <select name="pricing_rule">
                            <?php 
                                foreach($routing_rules as $key => $item): 
                            ?>
                            <option value="<?php echo $key; ?>" <?php if($client['billing_rule'] == $key) echo 'selected="selected"' ?>><?php echo $item; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>IP Addresses:</td>
                    <td>
                        <input type="text" name="ip_addresses[]" style="width:150px;" value="<?php echo $client['resource_ips'][0] ?>">
                    </td>
                </tr>
                <tr>
                    <td>Call Limit:</td>
                    <td>
                        <input type="text" name="call_limit"  value="<?php echo $client['call_limit'] ?>">
                    </td>
                </tr>
                <tr>
                    <td>Media Type:</td>
                    <td>
                        <select name="media_type">
                            <option value="2" <?php if ($client['media_type'] == 2) echo 'selected="selected"' ?>>Bypass Media</option>
                            <option value="1" <?php if ($client['media_type'] == 1) echo 'selected="selected"' ?>>Proxy Media</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>T.38:</td>
                    <td>
                        <input type="checkbox" name="t_38" <?php if($client['t_38']) echo 'checked="checked"' ?>>
                    </td>
                </tr>
                <tr>
                    <td>RFC 2833:</td>
                    <td>
                        <input type="checkbox" name="rfc2833" <?php if($client['t_38']) echo 'checked="checked"' ?>>
                    </td>
                </tr>
                <tr>
                    <td>Auto Invoicing:</td>
                    <td>
                        <input type="checkbox" name="auto_invoicing" <?php if($client['auto_invoicing']) echo 'checked="checked"' ?>>
                    </td>
                </tr>
            </tbody>
        </table>
        <div id="form_footer"> 
            <input type="submit" class="input in-submit" value="Submit"> 
        </div>
    </form>
</div>

<script>
    
    
    $(function() {
        var $add_ip = $('#add_ip');
        var $ip_delete = $('.ip_delete');
        var $myform = $('#myform');
        var $vendor_name = $('#vendor_name');
        
        $add_ip.click(function() {
            var $this = $(this);
            var $parent = $this.parents('tr');
            var $clone = $parent.next().clone();
            $parent.after($clone);
            $clone.show();
        });
        
        $ip_delete.live('click', function() {
            $(this).parents('tr').remove();
        });
        
        $myform.submit(function() {
            // check if exists client name
            var name = $vendor_name.val();
            var flag = true;
            
            var name_data=jQuery.ajaxData("<?php echo $this->webroot; ?>clients/check_name/"+name + '/<?php echo $client['client_id']  ?>');
            name_data=name_data.replace(/\n|\r|\t/g,"");
            if(name_data == 'false'){
                jQuery.jGrowlError(name + " is already in use!");
                flag = false;
            }
            
            return flag;
        });
    });
</script>
