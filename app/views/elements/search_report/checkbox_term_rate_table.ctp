 <div id="s-rate_tables_term">
            <div style="padding:0px 2px 3px 6px;">
            <label for="s-rate_tables-all">  <?php echo __('Term Rate Table',true);?></label></div>
            <div class="cb_select input">
            <?php
            foreach ($all_rate_table  as $key=>$value){
            	?>
           <div><input type="checkbox"    name="query[supp_term_rate][]" value="<?php echo  $key?>"   
           <?php if(!empty($_GET['query']['supp_term_rate'])){ if(in_array($key,$_GET['query']['supp_term_rate'])){   echo  "checked=checked"; } }?>
           
           
           id="cb_rt_<?php echo $key?>" class="input in-checkbox"> 
           <label for="cb_rt_<?php echo $key?>"><?php echo  $value?></label></div>
                                <?php }?>
                          
                            </div>
        </div>