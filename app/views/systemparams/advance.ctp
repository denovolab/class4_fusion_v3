<div id="title"> 
    <h1><?php __('Configuration') ?> &gt;&gt; <?php __('Advance System Setting'); ?></h1> 
</div> 

<div id="container"> 
    <form enctype="multipart/form-data" action="" method="post"> 
        <table class="list list-form"> 
            <thead> 
                <tr> 
                    <td colspan="3" class="last">Database</td> 
                </tr> 
            </thead> 
            <tbody> 
                <tr class="row-1"> 
                    <td class="label">Database Host:</td> 
                    <td class="value"><input type="text" name="web_db_host" class="input in-text"></td> 
                    <td class="help last">*</td> 
                </tr> 
                <tr class="row-2"> 
                    <td class="label">Database User:</td> 
                    <td class="value"><input type="text" name="web_db_user" class="input in-text"></td> 
                    <td class="help last">*</td> 
                </tr> 
                <tr class="row-1"> 
                    <td class="label">Database Password:</td> 
                    <td class="value"><input type="text" name="web_db_password" class="input in-text"></td> 
                    <td class="help last">*</td> 
                </tr> 
                <tr class="row-2"> 
                    <td class="label">Database Port:</td> 
                    <td class="value"><input type="text" name="web_db_port" class="input in-text"></td> 
                    <td class="help last">*</td> 
                </tr> 
                <tr class="row-1"> 
                    <td class="label">Database Export Path:</td> 
                    <td class="value"><input type="text" name="web_db_export_path" class="input in-text"></td> 
                    <td class="help last">*</td> 
                </tr> 
            </tbody>
        </table>
        
        <table class="list list-form"> 
            <thead> 
                <tr> 
                    <td colspan="3" class="last">Switch</td> 
                </tr> 
            </thead> 
            <tbody> 
                <tr class="row-1"> 
                    <td class="label">Switch IP:</td> 
                    <td class="value"><input type="text" name="switch_ip" class="input in-text"></td> 
                    <td class="help last">*</td> 
                </tr> 
                <tr class="row-2"> 
                    <td class="label">Switch Port:</td> 
                    <td class="value"><input type="text" name="switch_port" class="input in-text"></td> 
                    <td class="help last">*</td> 
                </tr> 
            </tbody>
        </table>
        
         <table class="list list-form"> 
            <thead> 
                <tr> 
                    <td colspan="3" class="last">Script</td> 
                </tr> 
            </thead> 
            <tbody> 
                <tr class="row-1"> 
                    <td class="label">Script Path:</td> 
                    <td class="value"><input type="text" name="script_path" class="input in-text"></td> 
                    <td class="help last">*</td> 
                </tr> 
                <tr class="row-2"> 
                    <td class="label">Script Setting File:</td> 
                    <td class="value"><input type="text" name="script_conf" class="input in-text"></td> 
                    <td class="help last">*</td> 
                </tr> 
            </tbody>
        </table>
        
        <table class="list list-form"> 
            <thead> 
                <tr> 
                    <td colspan="3" class="last">Sip Capture</td> 
                </tr> 
            </thead>
            <tbody> 
                <tr class="row-1"> 
                    <td class="label">Sip Capture IP:</td> 
                    <td class="value"><input type="text" name="sip_capture_ip" class="input in-text"></td> 
                    <td class="help last">*</td> 
                </tr> 
                <tr class="row-2"> 
                    <td class="label">Sip Capture Port:</td> 
                    <td class="value"><input type="text" name="sip_capture_port" class="input in-text"></td> 
                    <td class="help last">*</td> 
                </tr> 
            </tbody>
        </table>
        
        <table class="list list-form"> 
            <thead> 
                <tr> 
                    <td colspan="3" class="last">Active Call</td> 
                </tr> 
            </thead> 
            <tbody> 
                <tr class="row-1"> 
                    <td class="label">Web Server IP:</td> 
                    <td class="value"><input type="text" name="web_server_ip" class="input in-text"></td> 
                    <td class="help last">*</td> 
                </tr> 
                <tr class="row-1"> 
                    <td class="label">Web Server Port:</td> 
                    <td class="value"><input type="text" name="web_server_port" class="input in-text"></td> 
                    <td class="help last">*</td> 
                </tr> 
                <tr class="row-1"> 
                    <td class="label">Active Server IP:</td> 
                    <td class="value"><input type="text" name="active_server_ip" class="input in-text"></td> 
                    <td class="help last">*</td> 
                </tr> 
                <tr class="row-1"> 
                    <td class="label">Active Server Port:</td> 
                    <td class="value"><input type="text" name="active_server_port" class="input in-text"></td> 
                    <td class="help last">*</td> 
                </tr> 
                <tr class="row-1"> 
                    <td class="label">Billing Server IP:</td> 
                    <td class="value"><input type="text" name="billing_server_ip" class="input in-text"></td> 
                    <td class="help last">*</td> 
                </tr> 
                <tr class="row-1"> 
                    <td class="label">Billing Server Port:</td> 
                    <td class="value"><input type="text" name="billing_server_port" class="input in-text"></td> 
                    <td class="help last">*</td> 
                </tr> 
            </tbody>
        </table>
        
        <table class="list list-form"> 
            <thead> 
                <tr> 
                    <td colspan="3" class="last">Other</td> 
                </tr> 
            </thead> 
            <tbody> 
                <tr class="row-1"> 
                    <td class="label">PHP Interpreter Path:</td> 
                    <td class="value"><input type="text" name="php_path" class="input in-text"></td> 
                    <td class="help last">*</td> 
                </tr> 
                <tr class="row-2"> 
                    <td class="label">Statistics All Group:</td> 
                    <td class="value">
                        <select name="group_all">
                            <option value="true">Yes</option>
                            <option value="false">No</option>
                        </select>
                    </td> 
                    <td class="help last">*</td> 
                </tr> 
            </tbody>
        </table>
        <div class="form-buttons"><input type="submit" value="Submit" class="input in-submit"></div> 
    </form>
</div>