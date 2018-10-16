<script src="<?php echo $this->webroot?>js/ajaxTable.js" type="text/javascript"></script>
<div id="title">
    <h1>Origination &gt;&gt; Clients</h1>
    <ul id="title-menu">
       <li>
            <a href="<?php echo $this->webroot ?>did/clients/add" title="Click here to create a new vendor" class="link_btn">
                <img width="16" height="16" src="<?php echo $this->webroot ?>images/add.png" alt="">Create New       			
            </a>
        </li>
    </ul>
</div>

<div id="container">
    <?php
    if(empty($this->data)): 
    ?>
    <div class="msg"><?php echo __('no_data_found',true);?></div>
    <?php else: ?>
    
    <?php echo $this->element("xpage")?>
    <table class="list">
        <thead>
            <tr>
                <th>View IP</th>
                <th>Name</th>
                <th>Balance</th>
                <th>Update At</th>
                <th>Update By</th>
                <th>Action</th>
            </tr>
        </thead>
        
        
        <?php 
        $count = count($this->data);
        for($i = 0; $i < $count; $i++): 
        ?>
        <tbody id="resInfo<?php echo $i?>">
            <tr class="row-<?php echo $i%2 +1;?>">
                <td>
                    <img id="image<?php echo $i; ?>"  onclick="pull('<?php echo $this->webroot?>',this,<?php echo $i;?>)"  class="jsp_resourceNew_style_1"  src="<?php echo $this->webroot?>images/+.gif" title="<?php  __('View All')?>"/>
                </td>
                <td><?php echo $this->data[$i]['Client']['name'] ?></td>
                <td>
                    <?php echo $this->data[$i]['Balance']['balance'] < 0 ? '('.str_replace('-','',number_format($this->data[$i]['Balance']['balance'], 3)).')' : number_format($this->data[$i]['Balance']['balance'], 3); ?>
                </td>
                <td><?php echo $this->data[$i]['Client']['update_at'] ?></td>
                <td><?php echo $this->data[$i]['Client']['update_by'] ?></td>
                <td>
                    <a href="<?php echo $this->webroot ?>did/clients/edit/<?php echo $this->data[$i]['Client']['client_id'] ?>" title="Edit"> 
                        <img src="<?php echo $this->webroot ?>images/editicon.gif"> 
                    </a>
                    
                    <a href="<?php echo  $this->webroot?>did/clients/delete/<?php echo $this->data[$i]['Client']['client_id'] ?>" onclick="return confirm('Are you sure?');">
                        <img width="16" height="16" src="<?php echo  $this->webroot?>images/delete.png">
                    </a>
                    <?php if ( $this->data[$i]['Client']['status']==1){?>
                    <a   onclick="return confirm('Are you sure to inactive the selected <?php echo $this->data[$i]['Client']['name'] ?>?')"   href="<?php echo $this->webroot?>did/vendors/disable/<?php echo $this->data[$i]['Client']['client_id']?>" > <img  title=" <?php echo 'Click to inactive';?>" src="<?php echo $this->webroot?>images/flag-1.png"> </a>
                    <?php }else{?>
                    <a  onclick="return confirm('Are you sure to active the selected <?php echo $this->data[$i]['Client']['name'] ?>?')" href="<?php echo  $this->webroot?>did/vendors/enable/<?php echo $this->data[$i]['Client']['client_id']?>"  > <img  title=" <?php echo 'Click to active';?>" src="<?php echo $this->webroot?>images/flag-0.png" static="0" > </a>
                    <?php }?>
                    
                    <a target="_blank" href="<?php echo $this->webroot ?>did/did_assign/index/<?php echo $this->data[$i]['Client']['egress_id'] ?>" title="View DID"> 
                        <img src="<?php echo $this->webroot ?>images/viewdid.png"> 
                    </a>
                </td>
            </tr>
            <tr style="height:auto">
                <td colspan="6">
                    <div id="ipInfo<?php echo $i?>" class=" jsp_resourceNew_style_2" style="padding:5px"> 
                        <table>
                            <tr>
                                <td>IP</td>
                            </tr>
                            <?php foreach($this->data[$i]['ResourceIps'] as $ip): ?>
                            <tr>
                                <td><?php echo $ip; ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </table>
                    </div>
                </td>
            </tr>
        </tbody>
        <?php endfor; ?>
    
    </table>
    <?php echo $this->element("xpage")?>
    <?php endif; ?>
</div>