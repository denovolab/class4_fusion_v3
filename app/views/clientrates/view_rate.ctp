<div id="title">
  <h1><?php echo __('Rate Table',true);?></h1>
  <ul id="title-search">
      <li>
        <form action="" method="get">
             <input type="text" id="search-_q" class="in-search default-value input in-text defaultText in-input" title="Search..." value="" onclick="this.value=''" name="name">
        </form>
      </li>
   <li title="Advanced Search »" onclick="advSearchToggle();" id="title-search-adv" style="display: list-item; "></li>
  </ul>
</div>

<div id="container">
    <ul class="tabs">
        <li <?php if ($active_type == 0) echo 'class="active"' ?>><a href="<?php echo $this->webroot ?>clientrates/view_rate"> <img alt="" src="<?php echo $this->webroot ?>images/menuIcon.gif">Egress Rate</a></li> 
        <li <?php if ($active_type == 1) echo 'class="active"' ?>><a href="<?php echo $this->webroot ?>clientrates/view_rate/1"><img alt="" src="<?php echo $this->webroot ?>images/menuIcon.gif">Ingress Rate</a></li> 
    </ul>
  
    <?php
          if(!empty($p)):
                $rate_tables = $p->getDataArray();
    ?>
    <table id="mytable" class="list">
        <thead>
            <tr>
                <td><?php echo __('name',true);?></td>
                <td><?php echo __('Code Deck',true);?></td>
                <td><?php echo __('Currency',true);?></td>
                <td><?php echo __('Jurisdiction Country',true);?></td>
            <tr>
        </thead>

        <tbody>
            <?php foreach($rate_tables as $rate_table): ?>
            <tr>
                <td><a href="<?php echo $this->webroot ?>clientrates/view_rate_detail/<?php echo base64_encode($rate_table[0]['rate_table_id']) ?>"><?php echo $rate_table[0]['name'] ?></a></td>
                <td><?php echo $rate_table[0]['code_deck'] ?></td>
                <td><?php echo $rate_table[0]['currency'] ?></td>
                <td><?php echo $rate_table[0]['jurisdiction'] ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div id="tmppage">
        <?php echo $this->element('page');?>
    </div>  
    <?php else: ?>
        <h1 style="text-align:center"><?php echo __('no_data_found',true);?></h1>
    <?php endif; ?>
</div>