<script src="<?php echo $this->webroot?>js/ajaxTable.js" type="text/javascript"></script>
<div id="title">
   <h1><?php __('Monitoring')?>&gt;&gt;<?php echo __('Rule Execution Log')?></h1>  
		<ul id="title-search"><!--
        	<li>
        		<?php //Pr($searchkey);    //****************************模糊搜索**************************?>
        		<form  action="<?php echo $this->webroot;?>alerts/view_log"  method="get">
        			<input type="text" id="search-_q_j" class="in-search default-value input in-text defaultText" title="<?php echo __('search')?>..." value="<?php if(isset($searchkey)){echo $searchkey;}else{ echo __('pleaseinputkey');}?>"  onclick="this.value=''" name="searchkey">
        		</form>
        	</li>
       --></ul>
       <ul id="title-menu">
        	<?php if (isset($edit_return)) {?>
        	<!--<li>
    			<a href="<?php echo $this->webroot;?>alerts/view_log">
    				<img width="16" height="16"  alt="" src="<?php echo $this->webroot?>images/rerating_queue.png">
    				&nbsp;<?php echo __('goback')?>
    			</a>
    		</li>
        	--><?php }?>
        	<!--<li>
        		<a title="<?php echo __('creataction')?>"  href="<?php echo $this->webroot?>alerts/add_action">
       				<img width="16" height="16" alt="" src="<?php echo $this->webroot?>images/add.png"><?php echo __('createnew')?>
       			</a>
       		</li>
       --></ul>
    </div>
<div id="container">
<?php 			$mydata =$p->getDataArray();
			$loop = count($mydata); 
			if(empty($mydata)){
			?>
<div class="msg"><?php echo __('no_data_found',true);?></div>
<?php }else{
?>
<div id="toppage"></div>
<table class="list">
<col width="25%">
<col width="25%">
<col width="25%">
<col width="25%">
<thead>
<tr>
 			<td ><?php echo __('Rule Name',true);?> </td>
 			<td ><?php echo __('start_time',true);?> </td>
 			<td ><?php echo __('end_time',true);?> </td>
		 <td > <?php echo __('Problem Count',true); ?>  </td>
                 <td><?php echo __('Action',true); ?> </td>
		</tr>
</thead>
<tbody>
		<?php 

			for ($i=0;$i<$loop;$i++){
		?>
		<tr class="row-1">
		  <td align="center">			    
					<?php echo $mydata[$i][0]['name']?>
			</td>
		  <td align="center">			    
					<?php echo $mydata[$i][0]['start_time']?>
			</td>
			<td align="center">			    
					<?php echo $mydata[$i][0]['end_time']?>
			</td>
                        <!--
			<td align="center" class="getevent" style="cursor:pointer;" control="<?php echo $mydata[$i][0]['id']?>">			    
					<?php echo $mydata[$i][0]['cnt']?>
			</td>
                        -->
                        <td align="center">			    
					<?php echo $mydata[$i][0]['cnt']?>
			</td>
                        <td>
                            <a href="<?php echo $this->webroot ?>alerts/delete_log/<?php echo $mydata[$i][0]['id']?>" title="Delete">
                                <img src="<?php echo $this->webroot ?>images/delete.png">
                            </a>
                            <a href="###" class="viewDetial" title="View" control="<?php echo $mydata[$i][0]['id']?>">
                                <img src="<?php echo $this->webroot ?>images/view.png">
                            </a>
                        </td>
		</tr>
			<?php }?>
		</tbody>
		</table>
	</div>
<div>
<div id="tmppage">

<?php echo $this->element('page');?>



</div>

<?php }?>
</div>

<div id="dd"> </div> 

<div id="dd2" class="easyui-dialog" title="Destination" closed="true" style="width:400px;height:200px;"  
        data-options="iconCls:'icon-save',closed:true,resizable:true">  
    <div id="dd2_content" class="dialog_form">
        
    </div>
</div>  

<link rel="stylesheet" type="text/css" href="<?php echo $this->webroot?>easyui/themes/default/easyui.css">
<link rel="stylesheet" type="text/css" href="<?php echo $this->webroot?>easyui/themes/icon.css">
<script type="text/javascript" src="<?php echo $this->webroot?>easyui/jquery.easyui.min.js"></script>

<script type="text/javascript">
$(function() {
    /*
    $('.getevent').hover(function(e){
        $('.tooltips').remove();
        var xx = e.originalEvent.x || e.originalEvent.layerX || 0;
        var yy = e.originalEvent.y || e.originalEvent.layerY || 0; 
        var eid = $.trim($(this).attr('control'));
        if($.trim($(this).text()) == 0) {
            return;
        }
        $.ajax({
           'url' : '<?php echo $this->webroot; ?>alerts/get_events/' + eid,
           'type' : 'GET',
           'dataType' : 'json',
           'success' : function(data) {
               var $ul = $('<ul />').css({
                   'position': 'absolute',
                   'left' : xx,
                   'top' : yy,
                   'opacity' : 1
               });
               $ul.addClass('tooltips');
               $.each(data, function(index, value) {
                   tmp = '';
                   if(value[0]['event'] == 'email' && value[0]['email_addr'] != 'null') {
                       tmp = ' to ' + value[0]['email_addr']; 
                   }
                   $ul.append('<li>' + value[0]['event'] + tmp + '</li>');
               });
               $('body').append($ul);
              
           }
        });
    }, function(e){
        $('.tooltips').remove();
    }); */
    
  
    var $viewDetial = $('.viewDetial');
    var $view_code_name = $('.view_code_name');
    var $dd = $('#dd');
    var $dd2 = $('#dd2');
    var $dd2_content = $('#dd2_content');
    
    $viewDetial.click(function() {
        var $this = $(this);
        var control = $this.attr('control');

        $dd.dialog({  
            title: 'Rule Execution Log',  
            width: 800,  
            height: 600,  
            closed: false,  
            cache: false,  
            resizable: true,
            href: '<?php echo $this->webroot?>alerts/get_log_info/' + control,  
            modal: true,                
            buttons:[{
                    text:'Close',
                    handler:function(){
                        $dd.dialog('close');
                    }
            }]
        });

        $dd.dialog('refresh', '<?php echo $this->webroot?>alerts/get_log_info/' + control);  
    });
    
    $view_code_name.live('click', function() {
        var $this = $(this);
        var full = $this.attr('full');
        full = full.replace(/,/g, "<br />");
        $dd2_content.html(full);
        $dd2.dialog('open'); 
    });
    
});
</script>