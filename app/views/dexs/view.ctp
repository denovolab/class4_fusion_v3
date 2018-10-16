<div id="title">
<h1>
    <?php  __('Finance')?>
    &gt;&gt;
    <?php echo __('Domestic Exchange',true);?>
</h1>
<ul id="title-menu">
 
    <li> <a class="link_btn" title="<?php echo __('createroletitle')?>"  href="<?php echo $this->webroot?>dexs/add"> <img width="16" height="16" alt="" src="<?php echo $this->webroot?>images/add.png"><?php echo __('createnew')?> </a> </li>
  

	<li><a class="link_back" onclick="history.go(-1);"> <img width="16" height="16"
		src="<?php echo $this->webroot?>images/icon_back_white.png" alt="">
	&nbsp;<?php echo __('goback',true);?> </a></li>
   
</ul>
</div>

<div id="container">
<!-- <div id="toppage"></div>-->
 <?php 			$mydata =$p->getDataArray();
			$loop = count($mydata); 
			if(empty($mydata)){
			?>
<div class="msg"><?php echo __('no_data_found',true);?></div>
<?php }else{

?>
    <table class="list">
      <thead>
        <tr>
          <td width="15%"><span> <?php echo __('DEX Name',true);?> </span></td>
          <td><span> <?php echo __('egress',true);?> </span></td>
          <td width="10%"><span> <?php echo __('Prefix',true);?> </span></td>
          <td width="10%"><span> <?php echo __('action',true);?> </span></td>
        </tr>
      </thead>
      <tbody>
      <?php 
      for ($i=0;$i<$loop;$i++){
      	
      			?>
        <tr>
          <td><span><?php echo $mydata[$i][0]['dex_name']; ?></span></a></td>
          <td><div class="table_li">
              <ul>
                <li><?php echo $mydata[$i][0]['resource_alias']; ?></li>
                
              </ul>
            </div></td>
          <td><span><?php echo $mydata[$i][0]['dex_prefix']; ?></span></td>
          
          <td>
          <a href="<?php echo $this->webroot;?>dexs/add/<?php echo $mydata[$i][0]['id'];?>" title="Add"><img width="16" height="16" src="<?php echo $this->webroot;?>images/add.png" alt="Add"/></a>
          &nbsp;&nbsp;&nbsp;&nbsp;
          <a href="<?php echo $this->webroot;?>dexs/ex_dele_dex_resource/<?php echo $mydata[$i][0]['dex_resource_id'];?>" title="Del Resource"><img width="16" height="16" src="<?php echo $this->webroot;?>images/delete.png" alt="Del Resource"/></a>
          </td>
        </tr>
        <?php }?>
      </tbody>
    </table>
    <div id="tmppage"> 
  <?php echo $this->element('page');?>
  </div>
  <?php } ?>
</div>
