<script type="text/javascript">
blocklist={
	trAddCallback:function(options){
		jQuery('input[type=text],input[type=password]').addClass('input in-input in-text');
		jQuery('input[type=button],input[type=submit]').addClass('input in-submit');
		jQuery('select').addClass('select in-select');
		jQuery('textarea').addClass('textarea in-textarea');
		jQuery('#'+options.log+' #ResourceBlockEgressClientId').change(
			function(){
				var id=jQuery(this).val();
				if(id=='')
				{
					jQuery('#'+options.log+' #ResourceBlockEngressResId').html('');
					return;
				}
				jQuery.get("<?php echo $this->webroot?>trunks/ajax_options",{type:'egress','filter_id':id},function(data){
						jQuery('#'+options.log+' #ResourceBlockEngressResId').html('');
						var arr=eval(data);
						//jQuery('<option/>').html('select').val('').appendTo('#'+options.log+' #ResourceBlockEngressResId');
						for(i in arr){
							jQuery('<option/>').val(arr[i].resource_id).html(arr[i].alias).appendTo('#'+options.log+' #ResourceBlockEngressResId');
						}
						jQuery('#'+options.log+' #ResourceBlockEngressResId').val(jQuery('#'+options.log+' #ResourceBlockEngressResId').attr('v')).attr('v','');
				});
			}
		).change();
		jQuery('#'+options.log+' #ResourceBlockIngressClientId').change(
				function(){
					var id=jQuery(this).val();
					if(id=='')
					{
						jQuery('#'+options.log+' #ResourceBlockIngressResId').html('');
						return;
					}
					jQuery.get("<?php echo $this->webroot?>trunks/ajax_options",{'filter_id':id, 'trunk_type2':0, 'show_type' : <?php echo $type; ?>},function(data){
						jQuery('#'+options.log+' #ResourceBlockIngressResId').html('');
						var arr=eval(data);
						//jQuery('<option/>').html('select').val('').appendTo('#'+options.log+' #ResourceBlockIngressResId');
						for(i in arr){
							jQuery('<option/>').val(arr[i].resource_id).html(arr[i].alias).appendTo('#'+options.log+' #ResourceBlockIngressResId');
						}
						jQuery('#'+options.log+' #ResourceBlockIngressResId').val(jQuery('#'+options.log+' #ResourceBlockIngressResId').attr('v')).attr('v','');
					});
				}
		).change();
		jQuery('#ResourceBlockDigit').xkeyvalidate({type:'Num'});
		if(jQuery('table.list').css('display')=="none"){
			jQuery('table.list').show();
		}
	},
	trAddOnsubmit:function(options){
		var re=true;
		var Digit=jQuery('#'+options.log).find('#ResourceBlockDigit').val();
		var Egress=jQuery('#'+options.log).find('#ResourceBlockEgressClientId').val();
		var Ingress=jQuery('#'+options.log).find('#ResourceBlockIngressClientId').val();
		var EgressTrunk=jQuery('#'+options.log).find('#ResourceBlockEgressResId').val();
		var IngressTrunk=jQuery('#'+options.log).find('#ResourceBlockIngressResId').val();
		var data=jQuery.ajaxData('<?php echo $this->webroot?>blocklists/ajaxValidateRepeat?id='+options.id+'&digit='+Digit+'&egress_trunk='+EgressTrunk+'&ingress_trunk='+IngressTrunk);
		
                if(!data.indexOf('false')){
			jQuery.jGrowlError(Digit+' is already in use! ');
			jQuery('#'+options.log).find('#ResourceBlockDigit').addClass('invalid');
			jQuery('#'+options.log).find('#ResourceBlockEgressClientId').addClass('invalid');
			jQuery('#'+options.log).find('#ResourceBlockIngressClientId').addClass('invalid');
			re=false;
		}
		return re;
	}
}
</script>