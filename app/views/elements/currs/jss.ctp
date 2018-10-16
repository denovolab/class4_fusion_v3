<script type="text/javascript">
var jsAdd={
	onsubmit:function(options){
		var re=true;
		var tr=jQuery('#'+options.log);
		var CurrCode=tr.find('#CurrCode').val();
		var CurrRate=tr.find('#CurrRate').val();
		if(CurrCode==''){
			jQuery.jGrowlError('The field Name cannot be NULL.');
			re=false;
		}
		if(CurrRate==''){
			jQuery.jGrowlError('The field Rates cannot be NULL.');
			re=false;
		}
		if(/[^0-9.]/.test(CurrRate)){
			jQuery.jGrowlError('The field Rate must be numeric only.');
			re=false;
		}
		if(/\W/.test(CurrCode)){
			jQuery.jGrowlError('Name Can only a-z,A-Z,0-9,-,_,<space>!');
			re=false;
		}
		return re;
	}
}
</script>
