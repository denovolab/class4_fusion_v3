<style type="text/css">
.group-title.bottom {
-moz-border-radius:0 0 6px 6px;
border-top:1px solid #809DBA;
margin:15px auto 10px;
}
.list td.in-decimal {
text-align:center;
}

.value input, .value select, .value textarea, .value .in-text, .value .in-password, .value .in-textarea, .value .in-select {
-moz-box-sizing:border-box;
width:100px;;
}

.list {
font-size:1em;
margin:0 auto 20px;
width: 100%;
}

#container .form {
margin:0 auto;
width:750px;
}
</style>
<div id="title">
            <h1><?php echo __('Staistics'); ?>&gt;&gt;<?php echo __('Egress Trunk Report',true);?></h1>
    <?php echo  $this->element('qos/title_menu_ul');?>
    </div>
<div id="container">
<ul class="tabs">
      <li ><a href="<?php echo $this->webroot?>/monitorsreports/globalstats"><img width="16" height="16" src="<?php echo $this->webroot?>images/list.png"><?php echo __('globlestatus')?></a></li>
    		<li  ><a href="<?php echo $this->webroot?>/monitorsreports/productstats"> <img width="16" height="16" src="<?php echo $this->webroot?>images/menuIcon.gif"> <?php echo __('routestatus')?></a>  </li>
     	<li><a href="<?php echo $this->webroot?>/monitorsreports/ingress/ingress"><img width="16" height="16" src="<?php echo $this->webroot?>images/bDR.gif"><?php echo __('ingress')?></a>        </li>
      <li  class="active"><a href="<?php echo $this->webroot?>/monitorsreports/egress/egress"><img width="16" height="16" src="<?php echo $this->webroot?>images/bNotes.gif"><?php echo __('egress')?></a>        </li>
 </ul>
    
    
               
            
           
           
           
    

            
 
<?php echo  $this->element('qos/list_table',array('name_param'=>"host",'name_118n'=> __('egress')))?>
 
        
        

 
  
        
        
        


    
      

</div>
<div>

</div>


	