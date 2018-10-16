
		    <script type="text/javascript">
		    //&lt;![CDATA[
		    	var currentTime = 1281602534;
		    	var L = {"loadingPanel":"Please Wait...","deleteConfirm":"Are you sure to delete this item?","hide-all":"hide all"};
		    //]]&gt;


			    function one2many(){
				    		var tmp = document.getElementById('tmp');
				    		var newtmp = tmp.cloneNode(true);
				    		newtmp.style.display="block";
				    		newtmp.className = "newc";
				    		
				    		newtmp.getElementsByTagName("a")[0].onclick = function(){
					    		return function(){
					    			if (confirm("清除该联系人?")){
				    					newtmp.parentNode.removeChild(newtmp);
						    			}
						    		};
					    		}();
					    		
					   tmp.parentNode.appendChild(newtmp);
				       }

			    function clear_(){
				    	if ($('.newc')[0]){
					    	if (confirm("将清除您输入的收信人,是否继续?")){
					    		$('.newc').css('display','none');
					    		$('#addn').fadeOut();
						   } else {
										$('input[name=msg_type]').attr('checked',true);						    	
							   	} 
					   } else {
					  			$('#addn').fadeOut();
						  }
				       }

			   function send_msg(){
				   var num = '';
				   var phone_reg = /^1[3|4|5|8][0-9]\d{4,8}$/;
				   var type = document.getElementsByName("msg_type");
						if (type[1].checked == true){
							$('.newc').each(function(){
								if (this.style.display != 'none'){
									var i = this.getElementsByTagName('input')[0];
									if (i.value.length > 0 && phone_reg.test(i.value)){
										num += i.value.trim()+',';
									}
								}
							});

							var v = document.getElementById('p').value;
							if (v.length > 0 && phone_reg.test(v)){
								num += v;
							} else {
								num = num.substring(0,num.lastIndexOf(','));
							}
						} else {
							var v = document.getElementById('p').value;
							if (v.length > 0 && phone_reg.test(v)){
								num += v;
							}
						}
						if (num.length == 0){
							jQuery.jGrowl('请输入您要发送的号码',{theme:'jmsg-alert'});
							return false;
						} 

						if (document.getElementById('msgcontent').value == ""){
							jQuery.jGrowl('请输入短信内�?,{theme:'jmsg-alert'});
							return false;
						}
					var t = type[1].checked == true?1:5;
						var r_url = "<?php echo $this->webroot?>/selfhelps/check_balance?msg_type="+t+"&nums="+num+"&content="+document.getElementById('msgcontent').value;
						jQuery.post(r_url,function(data){
							if (data.trim()!='false') {
								var url = "<?php echo $msg_url?>&mobile="+num+"&service=BZ&mtype=XXXF&msgid="+new Date().getTime()+"&message="+data.trim();
								jQuery.get(url);
								jQuery.jGrowl('信息已发�?,{theme:'jmsg-success'});
							} else {
								jQuery.jGrowl('对不�?您的余额已不�?请即时充�?,{theme:'jmsg-alert'});
							}
						});
				    }

		    </script>
<?php if (!isset($msg_url)) {?>
	<div id="container"><div class="msg">对不�?短信功能暂时停用,对您造成的不便我们非常抱�?</div></div>
<?php } else {?>
<div id="container">
<div style="width:100%;height:100%;">
<dl style="margin-left:30%;width: 500px; height: auto; " class="tooltip-styled" id="viewmessage">
<div class="msg" style="padding-top:3px;text-align: center; margin-top:0px; font-size: 16px;"><?php echo __('sendmsg')?></div>
<div style="color:red">注意：格式不正确的号码将被忽�?/div>
	<div style="margin-top: 10px;">
		<?php echo __('phone')?>:<input class="input in-text" id="p"/><input type="radio" checked name="msg_type"  value="5" onclick="clear_();"/><?php echo __('onetoone')?>&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio"  name="msg_type" value="1" onclick="$('#addn').fadeIn();"/><?php echo __('onetomany')?>
		&nbsp;&nbsp;<a id="addn" style="display:none" href="javascript:void(0)" onclick="one2many();"><img src="<?php echo $this->webroot?>images/add.png"/><?php echo __('addnum')?></a>
		<div id="tmp" style="display:none"><?php echo __('phone')?>:<input class="input in-text" class="created"/><a href="javascript:void(0)"><img src="<?php echo $this->webroot?>images/delete.png"/></a></div>
	</div>
	<div style="margin-top: 10px;">
		<textarea name="msgcontent" cols="30" rows="6" style="float: left; width: 400px; height: 94px;" class="input in-text in-textarea" id="msgcontent"></textarea>
	</div>
	<?php
	if (isset($nomsg)){
		echo "<span style='margin-left:20%;color:red'>您的上级代理的余额已经不�?你当前不能发送短�?/span>";
	} else {
		$can_msg = $session->read('can_msg');
		if ($can_msg == true){ 
	?>
		<div style="text-align:center;margin-top: 10px;"><input type="button" onclick="send_msg();" value="<?php echo __('send')?>"/></div>
	<?php
		} else {
			echo "<span style='margin-left:20%;color:red'>对不�?您尚且没有发送短信的权限,请联系管理员</span>";
		} }
	?>
</dl>
</div>
</div>
<?php }?>
