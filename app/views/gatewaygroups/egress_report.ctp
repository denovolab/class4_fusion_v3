<script src="<?php echo $this->webroot?>js/ajaxTable.js" type="text/javascript"></script>
<?php $w = $session->read('writable');?>
<div id="title">
  <h1>
    <?php __('Statistics')?>
    &gt;&gt;
    <?php __('routeusage')?>
  </h1>
  <ul id="title-search">
      <li>
          <form action="" method="get" id="server_form">
          <font class="fwhite"><?php __('Switch Server') ?>:</font>
          <select style="width:180px;" name="server_info" id="server_info" class="input in-select select">
              <?php foreach($server_infos as $server_info) : ?>
<!--              <option value="<?php echo $server_info[0]['server_id'] ?>" <?php if($server_info[0]['server_id'] == $server_id) echo 'selected="selected"'; ?>><?php echo $server_info[0]['ip'] . ':' . $server_info[0]['port'] ?></option>-->
              <option value="<?php echo $server_info[0]['id'] ?>" <?php if($server_info[0]['id'] == $server_id) echo 'selected="selected"'; ?>><?php echo $server_info[0]['profile_name'] ?></option>
              <?php endforeach; ?>
          </select>
          </form>
      </li>
    <li>
      <form  action=""  method="get">
        <input type="text" id="search-_q" class="in-search default-value input in-text defaultText" title="<?php echo __('search')?>..." value="<?php if(isset($_POST['search'])){echo $_POST['search'];}else{ echo '';}?>"  onclick="this.value=''" name="search">
        <input type="submit" name="submit" value="" class="search_submit"/>
      </form>
    </li>
    <li title="<?php echo __('advancedsearch')?> »" onclick="advSearchToggle();" id="title-search-adv" style="display: list-item;"></li>
  </ul>
  <ul id="title-menu">
  </ul>
</div>
<div id="container">
  <fieldset class="title-block" id="advsearch"  style="width: 100%">
    <form  action=""  method="get">
      <table style=" text-align:left; width:70%;">
        <tbody>
          <tr>
            <td class="label"><?php echo __('Trunk',true);?>:</td>
               <td class="value">
<!--                   <input  name="id" value="" id="id" type="text" class="input in-text in-input">-->
                   <select name="id" id="id">
                       <option></option>
                       <?php foreach($resources as $resource): ?>
                       <option <?php if(isset($_GET['id']) && $_GET['id'] == $resource[0]['alias']) echo 'selected' ?>><?php echo $resource[0]['alias'] ?></option>
                       <?php endforeach; ?>
                   </select>
               </td>
            <td class="label"><?php  __('Carriers')?></td>
            <td id="client_cell" class="value"><input type="hidden" id="query-id_clients" value="" name="query[id_clients]" class="input in-hidden">
              <!--  
              <input type="text" id="query-id_clients_name" onclick="showClients()"  readonly="1" value="" name="query[id_clients_name]" class="input in-text" >
              <img width="9" height="9" onclick="showClients()" class="img-button" src="<?php echo $this->webroot?>images/search-small.png"> <img width="9" height="9" onclick="ss_clear('client', _ss_ids_client)" class="img-button" src="<?php echo $this->webroot?>images/delete-small.png"></td>
              -->
              <select name="query[id_clients]">
                  <option></option>
                  <?php foreach($clients as $client): ?>
                  <option value="<?php echo $client[0]['client_id'] ?>" <?php if(isset($_GET['query']['id_clients_name']) && $_GET['query']['id_clients_name'] == $client[0]['client_id']) echo 'selected' ?>><?php echo $client[0]['name'] ?></option>
                  <?php endforeach; ?>
              </select>
            <td class="buttons"><input type="submit" value="Search" class="input in-submit"></td>
          </tr>
        </tbody>
      </table>
    </form>
  </fieldset>
  <div> 
      <ul class="tabs">
        <li <?php if($type == 'egress'): ?> class="active"<?php endif; ?>><a href="<?php echo $this->webroot ?>gatewaygroups/egress_report/egress"><img src="<?php echo $this->webroot ?>images/egress.png" alt=""><?php __('Egress Trunk') ?></a></li> 
        <li <?php if($type == 'ingress'): ?> class="active"<?php endif; ?>><a href="<?php echo $this->webroot ?>gatewaygroups/egress_report/ingress"><img src="<?php echo $this->webroot ?>images/ingress.png" alt=""><?php __('Ingress Trunk') ?></a></li> 
     </ul>
      <?php if (empty($lists)): ?>
      <div class="msg"><?php echo __('no_data_found',true);?></div>
      <?php else: ?>
      <?php echo $this->element("xpage")?>
    <table class="list"  style="border:1px solid #809DBA;height: 14px;">
      <thead>
        <tr>
          <td><?php echo __('host_ip')?>&nbsp;</td>
          <td> <?php echo __('Trunk',true);?></td>
<!--          <td><?php __('GatewayType')?></td>-->
          <td> Call Capacity <a  class="sort_asc sort_sctive" href="?order=capacity&sc=asc"> <img height="10" width="10" src="<?php echo $this->webroot?>images/p.png"> </a> <a class="sort_dsc" href="?order=capacity&sc=desc"> <img height="10" width="10" src="<?php echo $this->webroot?>images/p.png"> </a></td>
          <td> CPS <a  class="sort_asc sort_sctive"  href="?order=capacity&sc=asc"> <img height="10" width="10" src="<?php echo $this->webroot?>images/p.png"> </a> <a class="sort_dsc" href="?order=capacity&sc=desc"> <img height="10" width="10" src="<?php echo $this->webroot?>images/p.png"> </a></td>
          <td><?php echo __('ofingress')?> <a class="sort_asc sort_sctive" onclick="return false;" href="?order=ip_cnt&sc=asc"> <img height="10" width="10" src="<?php echo $this->webroot?>images/p.png"> </a> <a class="sort_dsc" onclick="return false;" href="?order=ip_cnt&sc=desc"> <img height="10" width="10" src="<?php echo $this->webroot?>images/p.png"> </a></td>
<!--          <td>--><?php //echo __('usage')?><!-- <a class="sort_asc sort_sctive" onclick="return false;" href="?order=cdr_cnt&sc=asc"> <img height="10" width="10" src="--><?php //echo $this->webroot?><!--images/p.png"> </a> <a class="sort_dsc" onclick="return false;" href="?order=cdr_cnt&sc=desc"> <img height="10" width="10" src="--><?php //echo $this->webroot?><!--images/p.png"> </a></td>-->
          <td>24Hr Max Calls</td>
          <td>24Hr Max CPS</td>
          <td>24Hr Max Channel</td>
          <td>% Capacity Used</td>
        </tr>
      </thead>
      <?php     $mydata =$lists; $flag = true;  $loop = count($mydata); for ($i=0;$i<$loop;$i++) {?>
      <tbody>
        <tr class="row-<?php echo $i%2+1;?>">
          <td  align="center"  style="font-weight: bold;"><img   id="image<?php echo $i; ?>"    onclick="pull('<?php echo $this->webroot?>',this,<?php echo $i;?>)" class=" jsp_resourceNew_style_1"  src="<?php echo $this->webroot?>images/+.gif"   title="<?php echo __('viewip')?>"/></td >
          <td  align="center">
              <?php if($type == 'egress'): ?> 
              <a  style="width:80%;display:block" href="<?php echo $this->webroot?>prresource/gatewaygroups/edit_resouce_egress/<?php echo $mydata[$i]['Resource']['resource_id']?>?<?php echo $appCommon->get_request_str()?>"  class="link_width" title="<?php echo __('edit')?>">
                                                <?php echo $mydata[$i]['Resource']['alias']?>
                 </a>
              <?php else: ?>
              <a  style="width:80%;display:block" href="<?php echo $this->webroot?>prresource/gatewaygroups/edit_resouce_ingress/<?php echo $mydata[$i]['Resource']['resource_id']?>?<?php echo $appCommon->get_request_str()?>"  class="link_width" title="<?php echo __('edit')?>">
                                                <?php echo $mydata[$i]['Resource']['alias']?>
                 </a>
              <?php endif; ?>          
                  </td>
          <!--<td><?php if($mydata[$i]['Resource']['ingress']){__('ingress');}?>
            <?php if($mydata[$i]['Resource']['egress']){__('egress');}?></td>-->
          <td  align="center"><?php  if(empty($mydata[$i]['Resource']['capacity'])) {echo "Unlimited";}else{echo number_format( $mydata[$i]['Resource']['capacity'],0); }?></td>
          <td  align="center"><?php  if(empty($mydata[$i]['Resource']['cps_limit'])) {echo "Unlimited";}else{echo number_format( $mydata[$i]['Resource']['cps_limit'],0); }?></td>
          <td align="center"><?php echo count($lists[$i]['ResourceIp'])?></td>
<!--          <td align="center"><a  href="--><?php //echo $this->webroot?><!--realcdrreports/summary_reports/--><?php //echo $type ?><!--/--><?php //echo $mydata[$i]['Resource']['resource_id']?><!--?type=egress_report"  title="--><?php //echo __("{$type}call")?><!--">-->
<!--            --><?php
//            if($flag) {
//            $usage = $common->get_trunk_count($type, $mydata[$i]['Resource']['resource_id'], $ip, $port);
//                if($usage == 'error')
//                {
//                    $flag = false;
//                    echo 0;
//                } else {
//                    echo $usage;
//                }
//            } else {
//                echo 0;
//            }
//
//            ?>
<!--              </a>-->
<!--          </td>-->

            <?php
                $sql = "select max(call) as call24, max(cps) as cps24, max(channels) as channel24 from qos_resource where 

res_id = {$mydata[$i]['Resource']['resource_id']} and 

report_time between CURRENT_TIMESTAMP - interval '24 hours'  and CURRENT_TIMESTAMP";

                $result = $Resource->query($sql);
                
                
            ?>
            <td><?php echo $result[0][0]['call24'] ?></td>
            <td><?php echo $result[0][0]['cps24'] ?></td>
            <td><?php echo $result[0][0]['channel24'] ?></td>
            <td>
                <?php echo empty($mydata[$i]['Resource']['capacity']) ? 'Unlimited' :  $usage /$mydata[$i]['Resource']['capacity']  ?>
            </td>
        </tr>

        <tr style="height:0px">
          <td colspan=20><div id="ipInfo<?php echo $i?>" style="display:none;margin:10px" class="jsp_resourceNew_style_2" >
              <table>
                <tr>
                  <th><?php echo __('host',true);?></th>
                  <th><?php echo __('ip',true);?></th>
                  <th><?php echo __('port',true);?></th>
                </tr>
                <?php $iR=0?>
                <?php foreach($lists[$i]['ResourceIp'] as $resourceIp):?>
                <?php $iR++?>
                <tr>
                  <td><?php echo $iR?></td>
                  <td><?php echo $resourceIp['ip']?></td>
                  <td><?php echo $resourceIp['port']?></td>
                </tr>
                <?php endforeach?>
              </table>
            </div>
            <div style="height: 0px; clear: right;"></div></td>
        </tr>
      </tbody>
      <?php }?>
    </table>
    <?php echo $this->element("xpage")?> </div>
    <?php endif; ?>
</div>
<script type="text/javascript">
//<![CDATA[

var _ss_ids_client = {'id_clients': 'query-id_clients', 'id_clients_name': 'query-id_clients_name'};

function showClients ()
{
    ss_ids_custom['client'] = _ss_ids_client;
    winOpen('<?php echo $this->webroot?>clients/ss_client?types=2&type=0', 500, 530);

}



function repaintOutput() {
    if ($('#query-output').val() == 'web') {
        $('#output-sub').show();
    } else {
        $('#output-sub').hide();
    }
}
repaintOutput();
//]]>

$(function() {
    $('#server_info').change(function (){
        $('#server_form').submit();
    });
});

</script> 


