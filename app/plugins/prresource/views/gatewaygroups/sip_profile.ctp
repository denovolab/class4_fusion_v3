<div id="title">
    <h1>
		 Carrier [<?php echo $client_name ?>] >>Edit Egress PASS [<?php echo $res['Gatewaygroup']['alias']; ?>] 
    </h1>
</div>

<div id="container">
	<?php echo  $this->element('egress_tab',array('active_tab'=>'sip_profile'));?>
	
		
		<?php echo $form->create ('Gatewaygroup', array ('url' => '/' . $this->params['url']['url']));?>
		<div id="support_panel" style="text-align:center;padding:20px;">
            <table class="list">
                        <thead>
                            <tr>
                                <th>VoIP Gateway Name</th>
                                <th>Ingress Trunk</th>
                                <th>SIP Profile Name</th>
                            </tr>
                        </thead>
                        
                        <tbody>
                            <?php
                                foreach($switch_profiles as $switch_profile):
                                $profiles = $switch_profile['profiles'];
                                $ingress_id = isset($use_ingresses[$switch_profile['id']]) ? $use_ingresses[$switch_profile['id']] : NULL;
                            ?>
                            <tr>
                                <td><?php 
                                    echo $switch_profile['name'] 
                                    ?>
                                    <input type="hidden" name="server_names[]" value="<?php echo $switch_profile['name'] ?>" />
                                </td>
                                <td>
                                    <select name="ingress[]">
                                        <option></option>
                                        <?php foreach($ingresses as $key => $val): ?>
                                        <option value="<?php echo $key ?>" <?php if ($ingress_id != NULL && $key == $ingress_id) echo 'selected="selected"'; ?>><?php echo $val ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                                <td>
                                    <select name="profiles[]">
                                        <option></option>
                                        <?php foreach($profiles as $profile): ?>
                                        <option value="<?php echo $profile[0] ?>" <?php if (in_array($profile[0],$sip_profiles)) echo 'selected="selected"'  ?>><?php echo $profile[1] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                            </tr>
                            <?php
                                endforeach;
                            ?>
                        </tbody>
                    </table>
            <br />
            <br />
            <br />
            
            <div class="button-groups">
            	<input type="submit" value="Submit" />
            </div>
        </div>
        <?php echo $form->end(); ?>
</div>