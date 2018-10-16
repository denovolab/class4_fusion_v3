<div id="title">
  <h1><?php echo __('Rate',true);?></h1>
</div>

<div id="container">

    <ul class="tabs">
        <li><a href="<?php echo $this->webroot ?>clientrates/view_rate_egress"> <img alt="" src="<?php echo $this->webroot ?>images/menuIcon.gif">Egress Rate</a></li> 
        <li class="active"><a href="<?php echo $this->webroot ?>clientrates/view_rate_ingress"><img alt="" src="<?php echo $this->webroot ?>images/menuIcon.gif">Ingress Rate</a></li> 
    </ul>
    <?php
        if(!empty($p)):
            $data = $p->getDataArray();
    ?>
    <table id="mytable" class="list">
        <thead>
            <tr>
                <td><?php echo __('Tech Prefix',true);?></td>
            <tr>
        </thead>

        <tbody>
            <?php foreach($data as $item): ?>
            <tr>
                <td><a href="<?php echo $this->webroot; ?>/clientrates/view_rate_detail/<?php echo base64_encode($item[0]['rate_table_id']) ?>"><?php echo $item[0]['tech_prefix'] == '' ? 'None' : $item[0]['tech_prefix'] ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div id="tmppage">
        <?php echo $this->element('page');?>
    </div>  
    <?php else: ?>
        <h1 style="text-align:center"><?php echo __('no_data_found',true);?></h1>
    <?php  endif; ?>
</div>

