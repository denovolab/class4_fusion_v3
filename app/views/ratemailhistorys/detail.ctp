<div id="title">
    <h1><?php echo __('Management',true);?>&gt;&gt;<?php echo __('Rate Delivery History',true);?></h1>
</div>
<div class="container">
    <table class="list">
    	<thead>
        	<tr>
            	<td><?php echo __('Mail Content',true);?></td>
                <td><?php echo __('File List',true);?></td>
            </tr>
        </thead>
        <tbody>
        	<tr>
            	<td><?php echo $data['Ratemailhistory']['mail_content'] ?></td>
                <td style="width:40% !important;">
                <div  class="reatemail">
                <ul>
                	<?php
						$files = explode(',',$data['Ratemailhistory']['files']);
					?>
					<?php foreach($files as $file): ?>
                    <li><a href="<?php echo $this->webroot; ?>ratemailhistorys/down/<?php echo base64_encode($file); ?>"><?php echo basename($file); ?></a></li>
                    <?php endforeach; ?>
                   
                </ul>
                </div>
    			</td>
            </tr>
        </tbody>
    </table>

</div>