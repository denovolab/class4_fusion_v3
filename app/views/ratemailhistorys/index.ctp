<div id="title">
    <h1><?php echo __('Management',true);?>&gt;&gt;<?php echo __('Rate Delivery History',true);?></h1>
<ul id="title-menu"><li>
<a class="link_back" href="#"   onclick="history.go(-1);">
	    				<img width="16" height="16" src="<?php echo $this->webroot?>images/icon_back_white.png" alt="">
	    				&nbsp;<?php echo __('goback',true);?>    			
	    			</a></li></ul>
</div>
<?php
    $data =$p->getDataArray();
?>
<div class="container">
    <div id="toppage"></div>
    <table class="list">
        <thead>
            <tr>
                <td><?php echo __('Send Date',true); echo $appCommon->show_order('send_date',__(' ',true));?></td>
                <td><?php echo __('Send To',true);?></td>
                <td><?php echo __('action',true);?></td>
            </tr>
        </thead>
        <tbody>
            <?php foreach($data as $item): ?>
            <tr>
                <td><?php echo $item['Ratemailhistory']['send_date'] ?></td>
                <td><?php echo $item['Ratemailhistory']['send_to'] ?></td>
                <td>
                    <a href="<?php echo $this->webroot; ?>ratemailhistorys/detail/<?php echo $item['Ratemailhistory']['id'] ?>">
                        <img width="16" height="16" src="<?php echo $this->webroot; ?>images/attached_cdr.gif">
                    </a>
                    <a href="<?php echo $this->webroot; ?>ratemailhistorys/delete/<?php echo $item['Ratemailhistory']['id'] ?>">
                        <img width="16" height="16" src="<?php echo $this->webroot; ?>images/delete.png">
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div id="tmppage"> <?php echo $this->element('page');?> </div>
</div>

