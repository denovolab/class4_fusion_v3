
<style type="text/css">
    .label{ width:100px; text-align:right;}
	.value{ text-align:left !important; }
	.input_width{ width:50px;}
	.select_width{ width:50px;}
</style>
   
    <div id="add_rountingplan" style="padding:0px 10px;">
    	<div style="height:30px; line-height:25px; float:left;">
        <label><?php echo __('name',true);?>:</label><input class="input in-text" id="name1" type="text" name="name" />
        <input type="button" id="addroute_strategy" class="input in-submit" value="<?php echo __('submit',true);?>" />
        <input type="button" id="addroute_route_record" style="width:auto; display:none;" class="input in-submit" value="Create New Route Table" />
        </div>
        <div id="editor1" style=" clear:both;">
        
       
        <form action="post" name="myform" id="myform">
            <input type="hidden" name="route_strategy_id" id="route_strategy_id" />
        <table id="resource_table1" class="list">
            <thead>
               <tr>
                <td><?php echo __('Prefix',true);?></td>
                <td><?php echo __('type',true);?></td>
                <td><?php echo __('Static Table',true);?></td>
                <td><?php echo __('Dynamic Table',true);?></td>
                <td><?php echo __('action',true);?></td>
               </tr>
            </thead>
            <tbody>
               <tr class="row">
                <td><input type="text" maxlength="16" style="width:80px;" class="input in-text" name="prefix[]"  /></td>
                <td>
                    <select class="select in-select" name="routetype[]">
                        <option value="1">Dynamic Routing</option>
                        <option value="2">Static Routing</option>
                        <option value="3">Static Routing After Dynamic routing</option>
                    </select>
                </td>
                <td>
                    <select class="select in-select" name="static[]"></select>
                </td>
                <td>
                    <select class="select in-select" name="dynamic[]">
                </td>
                <td><img class="del" style="cursor:pointer;" src="<?php echo $this->webroot ?>images/delete.png" /></td>
               <tr>
            </tbody>
        </table>
        <div id="form_footer">
                <button id="save" class="input in-submit" style="width:150px;"><?php echo __('Save and return',true);?></button>
              </div>
                  
        </form>
        </div>
    </div>
    <script type="text/javascript" src="<?php echo $this->webroot?>js/jquery.livequery.js"></script>
    <script type="text/javascript">
    $(document).ready(function() {
		var route_strategy_id;
        $('#addroute_strategy').click(function() {
			if($("#name1").val()==''){
				alert('The name cannot be null!');
				return false;
			}
            $.ajax({
                url:'<?php echo $this->webroot ?>clients/addroute_strategy',
                type:'post',
                dataType:'text',
                data:{name:$('#name1').val()},
                success:function(data) {
                    data = data.replace(/(^\s*)|(\s*$)/g,"");
                    if(data == '0') {
                        alert('The name has exists!');
                    } else {
						
						route_strategy_id = data;
						$('#route_strategy_id').val(data);
                        $('#name1').attr('readonly','readonly');
                        $('#addroute_strategy').css('display','none');
						$('#addroute_route_record').show();
						$('#resource_table1 tbody').empty();
						$('#editor1').show();
						$('#pop-div').css({'width':'900px','height':'auto','left':'30%'});

                    }
                }
            });
			return false;
        });
		
		$('#editor1').hide();
        
			
        var $row = $('.row').remove();
        getSelectStatic();
        getSelectDynamic();
        
        $('img.del', $row).click(function() {
            $(this).parent().parent().remove();
        });
  
        $('td:nth-child(2) select',$row).change(function() {
            if($(this).val() == '2') {
                $(this).parent().next().find('select').show().siblings().show(); 
                $(this).parent().next().next().find('select').hide().siblings().hide();
            } else if($(this).val() == '3') {
                $(this).parent().next().find('select').show().siblings().show(); 
                $(this).parent().next().next().find('select').show().siblings().show();
            } else if($(this).val() == '1') {
                $(this).parent().next().find('select').hide().siblings().hide(); 
                $(this).parent().next().next().find('select').show().siblings().show();
            }
        });
      
       
        $('#addroute_route_record').click(function() {
            $new = $row.clone(true);
            $new.appendTo('#resource_table1 tbody');
            $('td:nth-child(3) select',$new).hide();
        });
		
		
		
        function getSelectStatic() {
            $.getJSON('<?php echo $this->webroot ?>clients/getstaticroute', function(data) {
                var $statictables = $('select[name=static[]]',$row);
                $statictables.each(function(index) {
                    var $statictable = $(this);
                    $statictable.parent().find('img').remove();
                    $('<img id="addstatic" style="cursor:pointer;" src="<?php echo $this->webroot?>images/add.png" />').appendTo($statictable.parent()).hide()
                        .click(function() {
                            $(this).prev().addClass('clicked');
                            window.open('<?php echo $this->webroot?>clients/addstatictable', 'addstatictable', 
        'height=680,width=680,top=0,left=0,toolbar=no,menubar=no,scrollbars=no, resizable=no,location=no, status=no')
                        });
                    $statictable.empty();
                    $.each(data, function(idx, item) {
                        var option = "<option value='" + item['id'] + "'>" + item['name'] + "</option>";
                        $statictable.append(option);
                    });
                })
            })    
        }
        
        function getSelectDynamic() {
            $.getJSON('<?php echo $this->webroot ?>clients/getdynamicroute', function(data) {
                var $dynamictables = $('select[name=dynamic[]]',$row);
                
                $dynamictables.each(function(index) {
                    var $dynamictable = $(this);
                    $dynamictable.parent().find('img').remove();
                    $('<img id="adddynamic" style="cursor:pointer;" src="<?php echo $this->webroot?>images/add.png"  onclick="showDiv2(\'pop-div-dynamic\',\'500\',\'250\',\'<?php echo $this->webroot?>clients/adddynamictable\');"/>').appendTo($dynamictable.parent()).click(function() {
                                $(this).prev().addClass('clicked_1');
								$("#pop-div").hide();
                                //window.open('<?php echo $this->webroot?>clients/adddynamictable', 'adddynamictable', 'height=680,width=680,top=0,left=0,toolbar=no,menubar=no,scrollbars=no, resizable=no,location=no, status=no')
                            });
							
                    $dynamictable.empty();
                    $.each(data, function(idx, item) {
                        var option = "<option value='" + item['id'] + "'>" + item['name'] + "</option>";
                        $dynamictable.append(option);
                    });
                })
            })    
        }
        /*
        $('#save').click(function() {
             $.ajax({
                 url:'<?php echo $this->webroot ?>clients/sendroutingplan',
                 type:'post',
                 dataType:'text',
                 data:$('#myform').serialize(),
                 success:function(data) {
                     window.opener.test($('#name1').val());
                     window.opener=null;      
                     window.open('','_self');      
                     window.close();
                 }
             });
        });
        */
		
		$('#save').click(function() {
                $.ajax({
                    url:"<?php echo $this->webroot ?>clients/sendroutingplan",
                    type:"POST",
                    dataType:"text",
                    data:$('#myform').serialize(),
                    success:function(data) {
						data = data.replace(/(^\s*)|(\s*$)/g,"");
						test4($('#name1').val());
						
						$("#pop-div").hide();
						$("#pop-clarity").hide();
                    }
                });
                return false;
            });
			
			
	});
        

    
    
    function staticback(id) {
        $.getJSON('<?php echo $this->webroot ?>clients/getstaticroute', function(data) {
            var $statictables = $('.clicked');
            $statictables.each(function(index) {
                var $statictable = $(this);
                $statictable.parent().find('img').remove();
                $('<img id="addstatic" style="cursor:pointer;" src="<?php echo $this->webroot?>images/add.png" />').appendTo($statictable.parent())
                    .click(function() {
                        $(this).prev().addClass('clicked');
                        window.open('<?php echo $this->webroot?>clients/addstatictable', 'addstatictable', 
    'height=680,width=680,top=0,left=0,toolbar=no,menubar=no,scrollbars=no, resizable=no,location=no, status=no')
                    });
                $statictable.empty();
                $statictable.removeClass('clicked');
                $.each(data, function(idx, item) {
                    var $option = $("<option value='" + item['id'] + "'>" + item['name'] + "</option>");
                    if(item['id'] == id) {
                        $option.attr('selected','selected');
                    }
                    $statictable.append($option);
                });
            })
        })  
    }
    /*
    function dynamicback(id) {
        $.getJSON('<?php echo $this->webroot ?>clients/getdynamicroute', function(data) {
            var $dynamictables = $('.clicked');
            $dynamictables.each(function(index) {
                var $dynamictable = $(this);
                $dynamictable.parent().find('img').remove();
                $('<img id="adddynamic" style="cursor:pointer;" src="<?php echo $this->webroot?>images/add.png" />').appendTo($dynamictable.parent())
                .click(function() {
                            $(this).prev().addClass('clicked');
                            window.open('<?php echo $this->webroot?>clients/adddynamictable', 'adddynamictable', 'height=680,width=680,top=0,left=0,toolbar=no,menubar=no,scrollbars=no, resizable=no,location=no, status=no');
                });
                $dynamictable.empty();
                $dynamictable.removeClass('clicked');
                $.each(data, function(idx, item) {
                    var $option = $("<option value='" + item['id'] + "'>" + item['name'] + "</option>");
                    if(item['id'] == id) {
                        $option.attr('selected','selected');
                    }
                    $dynamictable.append($option);
                });
            });
        });  
    }
	*/

function test5(id) {
        $.getJSON('<?php echo $this->webroot ?>clients/getdynamicroute', function(data) {
            var $dynamictables = $('.clicked_1');
            $dynamictables.each(function(index) {
                var $dynamictable = $(this);
                $dynamictable.parent().find('img').remove();
				$('<img id="adddynamic" style="cursor:pointer;" src="<?php echo $this->webroot?>images/add.png"  onclick="showDiv2(\'pop-div-dynamic\',\'500\',\'250\',\'<?php echo $this->webroot?>clients/adddynamictable\');"/>').appendTo($dynamictable.parent())
                .click(function() {
                            $(this).prev().addClass('clicked');
                           // window.open('<?php echo $this->webroot?>clients/adddynamictable', 'adddynamictable', 'height=680,width=680,top=0,left=0,toolbar=no,menubar=no,scrollbars=no, resizable=no,location=no, status=no');
                });
                $dynamictable.empty();
                $dynamictable.removeClass('clicked_1');
                $.each(data, function(idx, item) {
                    var $option = $("<option value='" + item['id'] + "'>" + item['name'] + "</option>");
                    if(item['id'] == id) {
                        $option.attr('selected','selected');
                    }
                    $dynamictable.append($option);
                });
            });
        }); 
		
    }
	
 

    </script>



<script type="text/javascript">
/*jQuery公共弹出层*/
function showDiv2(pop_id,pop_width,pop_height,pop_url){
	var pop_obj = $("#"+pop_id);
	var margin_left=pop_width/2;
	var margin_top=pop_height/2;
	
	pop_obj.css("width",pop_width+"px");
	pop_obj.css("height",pop_height+"px");
	pop_obj.css("position","fixed!important");
	pop_obj.css({"position": "absolute","display":"","left":"50%","top":"50%","z-index":"9999"});
	pop_obj.css("margin-left","-"+margin_left+"px!important");//FF IE7 该值为本身高的一半
	pop_obj.css("margin-top","-"+margin_top+"px!important");//FF IE7 该值为本身高的一半
	pop_obj.css("margin-top","0px");

	if(pop_url!=''){
		$.get(pop_url,function(data){
			//3.接受从服务器端返回的数据
			//alert(data);
			//4.将服务器端的返回的数据显示到页面上
			//取到用来显示结果信息的节点
			var resultObj = $("#pop-content-dynamic");
			resultObj.html(data);
		});
	}

	pop_obj.show();
	$("#pop-clarity-dynamic").show();
}

function closeDiv2(pop_id){
	$("#"+pop_id).hide();
	$("#pop-clarity-dynamic").hide();
}
</script>