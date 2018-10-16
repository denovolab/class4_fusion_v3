<div id="title">
    <h1><?php echo __('Tool',true);?>&gt;&gt;<?php echo __('Routing Analysis',true);?></h1>
    <?php if (isset($p)): ?>
    <ul id="title-menu">
        <li>
            <a href="<?php echo $this->webroot ?>routing_analysis" class="link_back_new"> 
                <img width="16" height="16" src="<?php echo $this->webroot ?>images/icon_back_white.png" alt="Back">&nbsp;Back 
            </a>
        </li>
    </ul>
    <?php 
        endif;
    ?>
</div>

<div id="container">
    <?php 
        if (isset($p)):
        $mydata = $p->getDataArray();
        $count  = count($mydata);
        if ($count == 0):
    ?>
    <div class="msg"><?php echo __('no_data_found')?></div>
    <?php else: ?>
      <div id="toppage"></div>
      <table class="list">
          <thead>
              <tr>
                  <th>Egress Carrier</th>
                  <th>Egress Trunk</th>
                  <th>Egress Code Name</th>
                  <th>Egress Code</th>
                  <th>Egress Rate</th>
              </tr>
          </thead>
          <tbody>
              <?php for($i = 0; $i < $count; $i++): ?>
              <tr>
                  <td><?php echo $egress_infos[$mydata[$i][0]['rate_table_id']]['client_name'] ?></td>
                  <td><?php echo $egress_infos[$mydata[$i][0]['rate_table_id']]['trunk_name'] ?></td>
                  <td><?php echo $mydata[$i][0]['code_name']; ?></td>
                  <td><?php echo $mydata[$i][0]['code']; ?></td>
                  <td><?php echo number_format($mydata[$i][0]['rate'], 5); ?></td>
              </tr>
              <?php endfor; ?>
          </tbody>
      </table>     
      <div id="tmppage"> <?php echo $this->element('page')?> </div>
    <?php 
        endif;
        else:
    ?>
    <form method="get">
    <table class="list">
        <tr>
            <td>Ingress Carrier</td>
            <td>
                <select id="carrier" name="carrier">
                    <option></option>
                    <?php foreach($carriers as $carrier): ?>
                    <option value="<?php echo $carrier[0]['client_id'] ?>"><?php echo $carrier[0]['name'] ?></option>
                    <?php endforeach; ?>
                </select>
            </td>
        </tr>
        <tr>
            <td>Ingress Trunk</td>
            <td>
                <select id="ingress_trunk" name="ingress_trunk">
                </select>
            </td>
        </tr>
        <tr>
            <td>Prefix</td>
            <td>
                <select id="ingress_prefix" name="ingress_prefix">
                </select>
            </td>
        </tr>
        <tr>
            <td>Code</td>
            <td>
                <input type="text" name="code">
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <input type="submit" name="submit" value="Submit" />
            </td>
        </tr>
    </table>
    </form>
      <?php 
      endif; 
      ?>
</div>

<script>
$(function() {
    var $carrier = $('#carrier');
    var $ingress_trunk = $('#ingress_trunk');
    var $ingress_prefix = $('#ingress_prefix');
    
    $carrier.change(function() {
        var $this = $(this);
        var client_id = $this.val();
        if (client_id != '')
        {
            $.ajax({
                'url'      : '<?php echo $this->webroot ?>routing_analysis/get_ingress_trunks',
                'type'     : 'POST',
                'dataType' : 'json',
                'data'     : {'client_id' : client_id},
                'success'  : function(data) {
                    $ingress_trunk.empty();
                    $ingress_trunk.append('<option></option>')
                    $.each(data, function(key, item) {
                        $ingress_trunk.append('<option value="' + item[0]['resource_id'] +'">' + item[0]['alias'] +'</option>')
                    });
                }
            });
        }
    }).trigger('change');
    
    $ingress_trunk.change(function() {
        var $this = $(this);
        var ingress_id = $this.val();
        if (ingress_id != '')
        {
            $.ajax({
                'url'      : '<?php echo $this->webroot ?>routing_analysis/get_ingress_prefixes',
                'type'     : 'POST',
                'dataType' : 'json',
                'data'     : {'ingress_id' : ingress_id},
                'success'  : function(data) {
                    $ingress_prefix.empty();
                    $.each(data, function(key, item) {
                        var item_name = item[0]['tech_prefix'];
                        if (item_name == '')
                        {
                            item_name = 'NONE';
                        }
                        $ingress_prefix.append('<option value="' + item[0]['route_strategy_id'] +'">' + item_name +'</option>')
                    });
                }
            });
        }
    }).trigger('change');
    
});
</script>