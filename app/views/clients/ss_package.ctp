<?php echo $this->element("selectheader")?>

<div id="title">
        <h1>选择 套餐</h1>
</div>
<div class="container">
                
<ul class="tabs">
    <li class="active"><a href="">
    <img src="<?php echo $this->webroot?>images/menuIcon.gif" height="16" width="16"/> 套餐</a></li>      
</ul>

<script type="text/javascript">var smartSearch = 2;</script>
<div class="panel">
<form id="smartSearch3"  action="<?php echo $this->webroot?>/clients/ss_client"  method="post">
<input class="input in-hidden" name="type" value="0" id="type" type="hidden">
<input class="input in-hidden" name="types" value="10" id="types" type="hidden">
<input class="input in-hidden" name="noall" value="" id="noall" type="hidden"><table class="form"><tbody><tr>
    <td class="value"><input name="searchkey"   onclick="this.value=''"  title="Search"  value="<?php if(isset($searchkey)){echo $searchkey;}else{ echo __('pleaseinputkey');}?>"  class="in-search get-focus input in-text" id="search-_q" type="text"/></td>
</tr></tbody></table>
</form>
</div>
<div id="container"><!-- DYNAREA -->
<div id="toppage"></div>
<table class="list">
<thead>
<tr>
    <td width="10%">套餐<?php echo __('id',true);?></td>
    <td class="last" width="90%">套餐名字</td>
</tr>
</thead>
<tbody>


<?php 	$mydata =$p->getDataArray();	$loop = count($mydata); for ($i=0;$i<$loop;$i++) {?>
<tr class="s-active row-1" onclick='opener.ss_process("package", {"id_packages":"<?php echo $mydata[$i][0]['package_id']?>","id_packages_name":"<?php echo $mydata[$i][0]['name']?>","id_accounts":"","account":"","tz":"+0300","id_currencies":"","id_code_decks":null,"autoinvoice_cdr_output":"xls","autoinvoice_cdr_file":"","autoinvoice_output":"pdf","id_dr_plans":""});winClose();' style="cursor: pointer;">
    <td align="right"><?php echo $mydata[$i][0]['package_id']?></td>
    <td class="last" align="left">
  
        <?php echo $mydata[$i][0]['name']?>    </td>
</tr>

<?php }?>

</tbody>
</table>

			<div id="tmppage">
<?php echo $this->element('page');?>

</div>
</div>
</div>
<?php echo $this->element("selectfooter")?>