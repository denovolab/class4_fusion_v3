
	<table class="form">
		<col style="width:37%;"/>
		<col style="width:62%;"/>
		
		<tr>
    		<td colspan="2" class="value">
   		 <div class="cb_select" style="height:30px; line-height:30px;text-align: left; border:none;">
             <div> 
              <div style="display:none;">
              <?php empty($post['Gatewaygroup']['lnp'])?$au='false':$au='checked'; echo $form->checkbox('lnp',array('checked'=>$au,'style'=>'margin-left:40px'))
              		?>
             <!--           
            <label for="cp_modules-c_invoices"><?php echo __('lrn',true);?></label>
	       <?php empty($post['Gatewaygroup']['lrn_block'])?$au='false':$au='checked'; echo $form->checkbox('lrn_block',array('checked'=>$au,'style'=>'margin-left:40px'))
	       				?>-->
             <label for="cp_modules-c_stats_summary"><?php echo __('Block LRN',true);?></label>
             	       <?php empty($post['Gatewaygroup']['dnis_only'])?$au='false':$au='checked'; echo $form->checkbox('dnis_only',array('checked'=>$au,'style'=>'margin-left:40px'))
	       				?>
             <label for="cp_modules-c_stats_summary"><?php echo __('DNIS Only',true);?></label>
             </div>
             </div>
         </div>
		   </td>
		</tr>
	</table>
