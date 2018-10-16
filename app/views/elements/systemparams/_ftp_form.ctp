<form id="myform" method="post">
    <table class="list list-form">
        <tbody>
            <tr>
                <td><?php __('Alias') ?></td>
                <td>
                    <input type="text" name="alias" value="<?php echo isset($data[0][0]['alias']) ? $data[0][0]['alias'] : '' ?>" />
                </td>
            </tr>
            <tr>
                <td><?php __('Server IP') ?></td>
                <td>
                    <input type="text" name="server_ip" value="<?php echo isset($data[0][0]['server_ip']) ? $data[0][0]['server_ip'] : '' ?>" />
                </td>
            </tr>
            <tr>
                <td><?php __('Server Port') ?></td>
                <td>
                    <input type="text" name="server_port" value="<?php echo isset($data[0][0]['server_port']) ?$data[0][0]['server_port'] : ''  ?>" />
                </td>
            </tr>
            <tr>
                <td><?php __('Server Directory') ?></td>
                <td>
                    <input type="text" name="server_dir" value="<?php echo isset($data[0][0]['server_dir']) ?$data[0][0]['server_dir'] : ''  ?>" />
                </td>
            </tr>
            <tr>
                <td><?php __('User Name') ?></td>
                <td>
                    <input type="text" name="username" value="<?php echo isset($data[0][0]['username']) ? $data[0][0]['username'] : '' ?>" />
                </td>
            </tr>
            <tr>
                <td><?php __('Password') ?></td>
                <td>
                    <input type="text" name="password" value="<?php echo isset($data[0][0]['password']) ? $data[0][0]['password'] : '' ?>" />
                </td>
            </tr>
            <tr>
                <td><?php __('Frequency') ?></td>
                <td>
                    <select id="frequency" name="frequency">
                        <option value="1" <?php echo $data[0][0]['frequency'] == 1 ? 'selected="selected"' : '' ?>>Daily</option>
                        <option value="2" <?php echo $data[0][0]['frequency'] == 2 ? 'selected="selected"' : '' ?>>Weekly</option>
                        <option value="3" <?php echo $data[0][0]['frequency'] == 3 ? 'selected="selected"' : '' ?>>Houly</option>
                    </select>
                    <select id="every_hours" name="every_hours">
                        <option value="1" <?php echo $data[0][0]['every_hours'] == 1 ? 'selected="selected"' : '' ?>>1 Hour</option>
                        <option value="2" <?php echo $data[0][0]['every_hours'] == 2 ? 'selected="selected"' : '' ?>>2 Hours</option>
                        <option value="3" <?php echo $data[0][0]['every_hours'] == 3 ? 'selected="selected"' : '' ?>>3 Hours</option>
                        <option value="4" <?php echo $data[0][0]['every_hours'] == 4 ? 'selected="selected"' : '' ?>>4 Hours</option>
                        <option value="6" <?php echo $data[0][0]['every_hours'] == 6 ? 'selected="selected"' : '' ?>>6 Hours</option>
                        <option value="8" <?php echo $data[0][0]['every_hours'] == 8 ? 'selected="selected"' : '' ?>>8 Hours</option>
                        <option value="12" <?php echo $data[0][0]['every_hours'] == 12 ? 'selected="selected"' : '' ?>>12 Hours</option>
                    </select>
                </td>
            </tr>
            <tr id="execute_on_tr">
                <td><?php __('FTP Execute on') ?></td>
                <td>
                    <input type="text" name="time" onfocus="WdatePicker({dateFmt:'HH:00'});" value="<?php echo isset($data[0][0]['time']) ? $data[0][0]['time'] : '00:00' ?>" />
                </td>
            </tr>
            <tr>
                <td><?php __('Maximum lines per file') ?></td>
                <td>
                    <input type="text" name="max_lines" value="<?php echo isset($data[0][0]['max_lines']) ?$data[0][0]['max_lines'] : '10000'  ?>" />
                </td>
            </tr>
            <tr>
                <td><?php __('File Breakdown'); ?></td>
                <td>
                    <select name="file_breakdown">
                        <option value="0" <?php echo $data[0][0]['file_breakdown'] == 0 ? 'selected="selected"' : '' ?>>As one big file</option>
                        <option value="1" <?php echo $data[0][0]['file_breakdown'] == 1 ? 'selected="selected"' : '' ?>>As hourly file</option>
                        <option value="2" <?php echo $data[0][0]['file_breakdown'] == 2 ? 'selected="selected"' : '' ?>>As daily file</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <table class="list">
                         <tr>
                            <td><?php __('Originating Trunk');  ?></td>
                            <td>
                                <select style="width:400px; height:200px;" name="ingresses[]" multiple="multiple">
                                    <?php 
                                        $ingress_arr = explode(',', $data[0][0]['ingresses']);
                                    ?>
                                    <?php foreach($ingresses as $ingress): ?>
                                    <option value="<?php echo $ingress[0]['resource_id']; ?>" <?php if(in_array($ingress[0]['resource_id'], $ingress_arr) || $data[0][0]['ingresses_all']) echo 'selected="selected"'; ?>><?php echo $ingress[0]['alias']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <input type="checkbox" name="ingresses_all" <?php if ($data[0][0]['ingresses_all']) echo 'checked="checked"'; ?> />All
                            </td>
                            <td><?php __('Terminating Trunk');  ?></td>
                            <td>
                                <select style="width:400px; height:200px;" name="egresses[]" multiple="multiple">
                                    <?php
                                        $egress_arr = explode(',', $data[0][0]['egresses']);
                                    ?>
                                    <?php foreach($egresses as $egress): ?>
                                    <option value="<?php echo $egress[0]['resource_id']; ?>" <?php if(in_array($egress[0]['resource_id'], $egress_arr) || $data[0][0]['egresses_all']) echo 'selected="selected"'; ?>><?php echo $egress[0]['alias']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <input type="checkbox" name="egresses_all" <?php if ($data[0][0]['egresses_all']) echo 'checked="checked"'; ?> />All
                            </td>
                        </tr>
                    </table>                    
                </td>
            </tr>
            
            <tr>
                <td colspan="2">
                    <table class="list">
                         <tr>
                            <td><?php __('Fields');  ?></td>
                            <td>
                                <select id="columns_select" multiple="multiple" style="width:180px;height:300px;">
                                    <?php foreach($back_selects as $key => $back_select): ?>
                                    <option value="<?php echo $key ?>"><?php echo $back_select ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                            <td class="value value4">
                                <input type="button" class="input in-submit in-button" value="Add" onclick="DoAdd();" style="width: 48px; height: 25px; margin-left: 0px;">
                                <br><br>
                                <input type="button" class="input in-submit in-button" value="Delete" onclick="DoDel();" style="width: 48px; height: 25px; margin-left: 0px;">
                            </td>
                            <td>
                                <?php
                                    $fields = explode(',', $data[0][0]['fields']);
                                ?>
                                <select id="columns" name="fields[]" multiple="multiple" style="width:180px;height:300px;">
                                    <?php 
                                        foreach($fields as $field):
                                        if (!empty($field)):

                                    ?>
                                    <option value="<?php echo $field ?>"><?php echo $back_selects[$field] ?></option>
                                    <?php
                                        endif;
                                        endforeach;
                                    ?>
                                </select>
                            </td>
                            <td class="value value4">
                                <input type="button" value="Up" onclick="moveOption('select2','up');" style="width: 48px; height: 25px; margin-left: 0px;" class="input in-submit in-button">
                                <br><br>
                                <input type="button" value="Down" onclick="moveOption('select2','down');" style="width: 48px; height: 25px; margin-left: 0px;" class="input in-submit in-button">
                            </td>
                        </tr>
                    </table>                    
                </td>
            </tr>
            
            <tr>
                <td><?php __('Include Headers'); ?></td>
                <td>
                    <select name="contain_headers">
                        <option value="true" <?php if($data[0][0]['contain_headers']) echo 'selected="selected"' ?>>Yes</option>
                        <option value="false" <?php if(!$data[0][0]['contain_headers']) echo 'selected="selected"' ?>>No</option>
                    </select>
                </td>
            </tr>
            <!--
            <tr>
                <td><?php __('Headers') ?></td>
                <td>
                    <textarea name="headers" style="width:600px;"><?php echo $data[0][0]['headers'] ?></textarea> 
                </td>
            </tr>
            -->
            <?php
            /*
            <tr>
                <td colspan="2">
                    <table class="list">
                         <tr>
                            <td><?php __('Originating Carrier');  ?></td>
                            <td>
                                <select style="width:400px; height:200px;" name="ingress_carriers[]" multiple="multiple">
                                    <?php 
                                        $ingress_carrier_arr = explode(',', $data[0][0]['ingress_carriers']);
                                    ?>
                                    <?php foreach($ingress_carriers as $ingress_carrer): ?>
                                    <option value="<?php echo $ingress_carrer[0]['client_id']; ?>" <?php if(in_array($ingress_carrer[0]['client_id'], $ingress_carrier_arr)) echo 'selected="selected"'; ?>><?php echo $ingress_carrer[0]['name']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <input type="checkbox" name="ingress_carriers_all" <?php if ($data[0][0]['ingress_carriers_all']) echo 'checked="checked"'; ?> />All
                            </td>
                            <td><?php __('Terminating Carrier');  ?></td>
                            <td>
                                <select style="width:400px; height:200px;" name="egress_carriers[]" multiple="multiple">
                                    <?php
                                        $egress_carrier_arr = explode(',', $data[0][0]['egress_carriers']);
                                    ?>
                                    <?php foreach($egress_carriers as $egress_carrer): ?>
                                    <option value="<?php echo $egress_carrer[0]['client_id']; ?>" <?php if(in_array($egress_carrer[0]['client_id'], $egress_carrier_arr)) echo 'selected="selected"'; ?>><?php echo $egress_carrer[0]['name']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <input type="checkbox" name="egress_carriers_all" <?php if ($data[0][0]['egress_carriers_all']) echo 'checked="checked"'; ?> />All
                            </td>
                        </tr>
                    </table>                    
                </td>
            </tr>
            */
            ?>
            <tr>
                <td>Duration</td>
                <td>
                    <select name="duration">
                        <option value="0" <?php if ($data[0][0]['duration'] == 0) echo 'selected="selected"'; ?>>All</option>
                        <option value="1" <?php if ($data[0][0]['duration'] == 1) echo 'selected="selected"'; ?>>Non-zero</option>
                        <option value="2" <?php if ($data[0][0]['duration'] == 2) echo 'selected="selected"'; ?>>Zero</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <table class="list">
                         <tr>
                            <td><?php __('Originating Release Cause');  ?></td>
                            <td>
                                <select name="ingress_release_cause[]" multiple="multiple" style="width:100%;height:200px;">
                                    <option value="0">All</option>
                                    <option value="200">success</option>
                                    <option value="300">multiple</option>
                                    <option value="301">moved permanently</option>
                                    <option value="302">moved temporaily</option>
                                    <option value="305">use proxy</option>
                                    <option value="380">alternative service</option>
                                    <option value="400">bad request</option>
                                    <option value="401">unauthorized</option>
                                    <option value="402">payment required</option>
                                    <option value="403">forbidden</option>
                                    <option value="404">not found</option>
                                    <option value="405">method no allowed</option>
                                    <option value="406">not acceptable</option>
                                    <option value="407">proxy authentication required</option>
                                    <option value="408">request timeout</option>
                                    <option value="410">gone</option>
                                    <option value="413">request entity too large</option>
                                    <option value="414">request-url too long</option>
                                    <option value="415">unsupported media type</option>
                                    <option value="416">unsupported url scheme</option>
                                    <option value="420">bad extension</option>
                                    <option value="421">extension required</option>
                                    <option value="423">interval too brief</option>
                                    <option value="480">temporarily unavailable</option>
                                    <option value="481">call/transaction does not exist</option>
                                    <option value="482">loop detected</option>
                                    <option value="483">too many hops</option>
                                    <option value="484">address incomplete</option>
                                    <option value="485">ambiguous</option>
                                    <option value="486">busy here</option>
                                    <option value="487">request terminated</option>
                                    <option value="488">not acceptable here</option>
                                    <option value="491">request pending</option>
                                    <option value="493">undecipherable</option>
                                    <option value="500">server internal error</option>
                                    <option value="501">not implemented</option>
                                    <option value="502">bad gateway</option>
                                    <option value="503">service unavailable</option>
                                    <option value="504">server time-out </option>
                                    <option value="505">version not supported </option>
                                    <option value="513">message too large </option>
                                    <option value="600">busy everywhere </option>
                                    <option value="603">decline </option>
                                    <option value="604">does not exist anywhere</option>
                                    <option value="606">not acceptable</option>
                                </select>
                            </td>
                            <td><?php __('Terminating Release Cause');  ?></td>
                            <td>
                                <select name="egress_release_cause[]" multiple="multiple" style="width:100%;height:200px;">
                                    <option value="0">All</option>
                                    <option value="200">success</option>
                                    <option value="300">multiple</option>
                                    <option value="301">moved permanently</option>
                                    <option value="302">moved temporaily</option>
                                    <option value="305">use proxy</option>
                                    <option value="380">alternative service</option>
                                    <option value="400">bad request</option>
                                    <option value="401">unauthorized</option>
                                    <option value="402">payment required</option>
                                    <option value="403">forbidden</option>
                                    <option value="404">not found</option>
                                    <option value="405">method no allowed</option>
                                    <option value="406">not acceptable</option>
                                    <option value="407">proxy authentication required</option>
                                    <option value="408">request timeout</option>
                                    <option value="410">gone</option>
                                    <option value="413">request entity too large</option>
                                    <option value="414">request-url too long</option>
                                    <option value="415">unsupported media type</option>
                                    <option value="416">unsupported url scheme</option>
                                    <option value="420">bad extension</option>
                                    <option value="421">extension required</option>
                                    <option value="423">interval too brief</option>
                                    <option value="480">temporarily unavailable</option>
                                    <option value="481">call/transaction does not exist</option>
                                    <option value="482">loop detected</option>
                                    <option value="483">too many hops</option>
                                    <option value="484">address incomplete</option>
                                    <option value="485">ambiguous</option>
                                    <option value="486">busy here</option>
                                    <option value="487">request terminated</option>
                                    <option value="488">not acceptable here</option>
                                    <option value="491">request pending</option>
                                    <option value="493">undecipherable</option>
                                    <option value="500">server internal error</option>
                                    <option value="501">not implemented</option>
                                    <option value="502">bad gateway</option>
                                    <option value="503">service unavailable</option>
                                    <option value="504">server time-out </option>
                                    <option value="505">version not supported </option>
                                    <option value="513">message too large </option>
                                    <option value="600">busy everywhere </option>
                                    <option value="603">decline </option>
                                    <option value="604">does not exist anywhere</option>
                                    <option value="606">not acceptable</option>
                                </select>
                            </td>
                        </tr>
                    </table>                    
                </td>
            </tr>
            <tr>
                <td><?php __('File Type'); ?></td>
                <td>
                    <select name="file_type">
                        <option value="1" <?php if($data[0][0]['file_type'] == 1) echo 'selected="selected"' ?>>gz</option>
                        <option value="2" <?php if($data[0][0]['file_type'] == 2) echo 'selected="selected"' ?>>tar.gz</option>
                        <option value="3" <?php if($data[0][0]['file_type'] == 3) echo 'selected="selected"' ?>>tar.bz2</option>
                    </select>
                </td>
            </tr>
        </tbody>
        <tfoot>
        <td colspan="2">
            <input type="submit" value="Submit" />
        </td>
        </tfoot>
    </table>
    </form>

<script type="text/javascript">
    $(function() {
        $('#myform').submit(function() {
            $('#columns option').attr('selected', true);
        });
         
        <?php 
            $ingress_release_causes = explode(',', $data[0][0]['ingress_release_cause']);
            $egress_release_causes  = explode(',', $data[0][0]['egress_release_cause']);
            foreach ($ingress_release_causes as $ingress_release_cause): 
        ?> 
            $('select[name=ingress_release_cause[]] option[value="<?php echo $ingress_release_cause; ?>"]').attr('selected', true);
        <?php 
            endforeach; 
            foreach ($egress_release_causes as $egress_release_cause): 
        ?>
            $('select[name=egress_release_cause[]] option[value="<?php echo $egress_release_cause; ?>"]').attr('selected', true);    
        <?php 
            endforeach;
        ?>
    });
</script>