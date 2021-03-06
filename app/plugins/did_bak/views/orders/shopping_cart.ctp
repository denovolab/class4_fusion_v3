<div id="title">
    <h1><?php echo __('DID Orders',true);?>&gt;&gt;<?php echo __('Shopping Cart',true);?></h1>
    <ul id="title-menu">
        <li>
            <a href="<?php echo $this->webroot ?>did/orders/browse" title="Shopping Cart" class="link_back_new">
                <img width="16" height="16" src="<?php echo $this->webroot ?>images/icon_back_white.png" alt="">Back</a>
        </li>
    </ul>
</div>

<div id="container">
    <?php echo $this->element("shop_cart_tab", array('active' => 'single'))?>
   <form method="post">
   <table  class="list">
       <thead>
           <tr>
               <th>DID</th>
               <th>Country</th>
               <th>Rate Center</th>
               <th>State</th>
               <th>City</th>
               <th>LATA</th>
               <th>Trunk/IP Address/Prefix</th>
               <th>Remove</th>
           </tr>
       </thead>
       
       <tbody>
           <?php foreach($data as $item): ?>
           <tr>
               <td><?php echo $item['number']; ?></td>
               <td><?php echo $item['country']; ?></td>
               <td><?php echo $item['rate_center']; ?></td>
               <td><?php echo $item['state']; ?></td>
               <td><?php echo $item['city']; ?></td>
               <td><?php echo $item['lata']; ?></td>
               <td>
                   <select name="egresses_id[]" style="width:auto;">
                       <?php foreach($egresses as $egress): ?>
                       <option value="<?php echo $egress[0]['resource_id'] ?>"><?php echo $egress[0]['name'] ?>/<?php echo $egress[0]['ip'] ?>/<?php echo $egress[0]['prefix'] ?></option>
                       <?php endforeach; ?>
                   </select>
               </td>
               <td>
                   <!--
                   <a href="<?php echo $this->webroot ?>did/orders/delete_cart_item/<?php echo $item['number']; ?>">
                       <img height="16" width="16" src="<?php echo $this->webroot ?>images/delete.png">
                   </a>
                   --->
                   <input type="checkbox" name="remove[]" value="1" />
               </td>
           </tr>
           <?php endforeach; ?>
       </tbody>
   </table>
    <div style="text-align:center">
        <input type="submit" value="Submit" />
    </form>
    </div>
</div>
