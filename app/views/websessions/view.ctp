
<div id="title">
  <h1><?php echo __('System',true);?>&gt;&gt;
    <?php echo __('User Sign-On History',true);?> 
    <!--  <a title="add to smartbar" href="<?php echo $this->webroot?>clients/view">
      <img width="10" height="10" alt="+" src="<?php echo $this->webroot?>images/qb-plus.png"></a>--> 
  </h1>
  <ul id="title-search">
    <li>
      <?php //****************************模糊搜索**************************?>
      <form action="" method="get">
        <input type="hidden" name="advsearch" value="1"/>
        <input type="text" id="search-_q" class="in-search default-value input in-text defaultText" title="<?php echo __('namesearch')?>" value="<?php if (!empty($search)) echo $search;?>" name="search">
      </form>
    </li>
   <!--
    <li title="<?php echo __('advancedsearch')?> »" onClick="advSearchToggle();" id="title-search-adv" style="display: list-item;"></li>
    -->
  </ul>
  <ul id="title-menu">
  </ul>
</div>
<div id="container">
  <?php //*********************  条件********************************?>
  <fieldset class="title-block" id="advsearch">
    <form action="" method="get">
      <input type="hidden" name="advsearch" value="1"/>
      <table  style="width: 645px;">
        <tbody>
          <tr>
            <td style="display:none; "><label><?php echo __('username')?>:</label>
              <input name="name" id="name"/></td>
            <td><label><?php echo __('host',true);?>:</label>
              <input name="host" type="text" id="host"  value="<?php if(isset($_GET['host'])&&!empty($_GET['host'])) echo $_GET['host'];?>"/></td>
            <td class="buttons"><input type="submit" class="input in-submit" value="<?php echo __('submit')?>"></td>
          </tr>
        </tbody>
      </table>
    </form>
  </fieldset>
  <?php //*********************查询条件********************************?>
  <div id="toppage"></div>
  <table class="list">
    <col width="6%">
    <col width="8%">
    <col width="8%">
    <col width="28%">
    
    <!--<col width="8%">
-->
    <thead>
      <tr>
        <td ><?php echo $appCommon->show_order('create_time','Login At')?></td>
        <td >&nbsp;<?php echo __('User',true);?></td>
        <td ><?php echo __('host',true);?></td>
        <td><?php echo __('Agent',true);?></td>
        <td  class="last"><?php echo __('Result',true);?></td>
      </tr>
    </thead>
    <tbody>
      <?php 
					$mydata =$p->getDataArray();
					$loop = count($mydata); 
					for ($i=0;$i<$loop;$i++) {?>
      <tr class="row-1">
        <td align="center"><?php echo $mydata[$i][0]['create_time']?></td>
        <td align="center"><?php echo $mydata[$i][0]['user_name']?></td>
        <td align="center"><?php echo $mydata[$i][0]['host']?></td>
        <td align="center"><?php echo $mydata[$i][0]['agent']?></td>
        <td align="center"><?php echo $mydata[$i][0]['msg'] == '' ? 'Success' : $mydata[$i][0]['msg']?></td>
      </tr>
      <?php }?>
    </tbody>
    <tbody>
    </tbody>
  </table>
  <div id="tmppage"> <?php echo $this->element('page');?> </div>
  <br />
  <div class="search_title"><img src="<?php
echo $this->webroot?>images/search_title_icon.png" />
    <?php __('search')?>
  </div>
  <div id="search">
  <form name="myform" method="get"> 
  <input type="hidden" name="issearch"value="TRUE" />
  <table class="form">
  	<tbody>
    <tr>
     <td class="label"><?php echo __('Login At',true);?>:</td>
      <td class="value"><?php echo __('From',true);?><input type="text" name="start" onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss',lang:'en'})" style="width:120px;" />
        to<input type="text" name="end" onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss',lang:'en'})"  style="width:120px;" /></td>
        
        <td class="label"><?php echo __('User',true);?>:</td>
      <td class="value"><input type="text" name="user" /></td>
        
        <td class="label"><?php echo __('host',true);?>:</td>
      <td class="value"><input type="text" name="host" /></td>
        <td class="label"><input type="submit" value="<?php echo __('Search',true);?>" class="input in-submit" /></td><td class="value"></td>
    </tr>
    </tbody>
  </table>
  </form>
  </div>


</div>
<?php
			if (!empty($searchform)) {
				//将用户刚刚输入的数据显示到页面上
				$d = array_keys($searchform);
			 foreach($d as $k) { ?>
<script>if (document.getElementById("<?php echo $k?>"))document.getElementById("<?php echo $k?>").value = "<?php echo $searchform[$k]?>";</script>
<?php }?>
<script>document.getElementById("advsearch").style.display="block";</script>
<?php }?>
