<ul class="tabs">
    <li><a href="<?php echo $this->webroot ?>clientrates/view/<?php echo $id?>"><img width="16" height="16" src="<?php echo $this->webroot ?>images/menuIcon.gif"> <?php echo __('Rates',true);?></a></li>
    <?php if ($jur_type == 3 || $jur_type == 4): ?>
    <li><a href="<?php echo $this->webroot?>clientrates/view/<?php echo $table_id?>/<?php echo $currency?>/npan"><img width="16" height="16" src="<?php echo $this->webroot?>images/menuIcon.gif"><?php echo __('NPANXX Rate',true);?> </a></li>
    <?php endif; ?>
    <li><a href="<?php echo $this->webroot ?>clientrates/simulate/<?php echo $id?>"><img width="16" height="16" src="<?php echo $this->webroot ?>images/simulate.gif"> <?php echo __('Simulate',true);?></a></li>   
    <li><a href="<?php echo $this->webroot ?>clientrates/import/<?php echo $id?>"><img width="16" height="16" src="<?php echo $this->webroot ?>images/import.png"> <?php echo __('import',true);?></a></li> 
    <li class="active"><a href="<?php echo $this->webroot ?>downloads/rate/<?php echo $id?>"><img width="16" height="16" src="<?php echo $this->webroot ?>images/export.png"> <?php echo __('export',true);?></a></li>   
</ul>