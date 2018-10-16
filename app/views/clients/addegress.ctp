<div id="title">
    <h1>
        Management &gt;&gt; Carrier [<?php echo $client_name ?>] &gt;&gt; Add Egress Trunk 
    </h1>
    <ul id="title-menu" />

    <li>
        <a href="<?php echo $this->webroot?>prresource/gatewaygroups/view_egress?<?php echo $$hel->getParams('getUrl')?>" class="link_back"><img width="16" height="16" alt="Back" src="<?php echo $this->webroot?>images/icon_back_white.png">&nbsp;<?php echo __('goback',true);?></a>		</li>
</ul>
</div>
<div class="container">

    <?php echo $form->create ('Client', array ('id' => 'myform','action' => 'addegress/'.$this->params['pass'][0] ));?> <?php echo $form->input('ingress',array('label'=>false ,'value'=>'false','div'=>false,'type'=>'hidden'));?> <?php echo $form->input('egress',array('label'=>false ,'value'=>'true','div'=>false,'type'=>'hidden'));?>
    <input type="hidden" name="is_finished" id="is_finished" value="0" />
    <table class="cols" style="width:80%">
        <col width="55%"/><col width="38%"/><col width="27%"/>
        <tr>
            <td><!-- COLUMN 1 -->
                <?php //**********系统信息**************?>

                <table class="form">
                    <tr>
                        <td><?php echo __('Egress Name',true);?>:</td>
                        <td>
                            <?php echo $form->input('alias',array('id'=>'alias','label'=>false ,'div'=>false,'type'=>'text','maxlength'=>'256'));?>
                        </td>
                    </tr>
                    <!--
                    <tr>
                        <td><?php echo __('carrier',true);?>:</td>
                        <td>
                            <?php echo $form->input('carrier',array('id'=>'alias','value'=>$name,'readonly'=>'readonly','label'=>false ,'div'=>false,'type'=>'text','maxlength'=>'256'));?>
                        </td>
                    </tr>
                    -->
                </table>

                <table class="form">
                    <tr>
                        <td><?php __('rateTable')?>:</td>
                        <td>
                            <?php echo $form->input('rate_table_id',array('options'=>$rate,
                            'empty'=>'  ','label'=>false, 'class' =>'select' ,'div'=>false,'type'=>'select'));?>
                            <img id="addratetable" style="cursor:pointer;" src="<?php echo $this->webroot?>images/add.png" onclick="showDiv('pop-div','500','200','<?php echo $this->webroot?>clients/addratetable');"/> 
                        </td>
                    </tr>
                </table>

            </td>
        </tr>
    </table>
    <?php echo $this->element("clients/host")?>
    <?php if($$hel->_get('viewtype')=='wizard'){?>
    <div id="form_footer">
        <input type="submit"    onclick="seleted_codes();jQuery('#GatewaygroupAddResouceEgressForm').attr('action','?nextType=egress&<?php echo $$hel->getParams('getUrl')?>')" value="<?php echo __('Next Egress')?>" style="width:80px" />
        <input type="submit"    onclick="seleted_codes();jQuery('#GatewaygroupAddResouceEgressForm').attr('action','?nextType=ingress&<?php echo $$hel->getParams('getUrl')?>')" value="<?php echo __('Next Ingress')?>" style="width:80px"/>
        <input type="button"  value="<?php echo __('End')?>" class="input in-submit" onclick="location='<?php echo $this->webroot?>clients/index?filter_id=<?php echo $$hel->_get('query.id_clients')?>'"/>
    </div>
    <?php }else{?>
    <div id="form_footer">
        <input type="submit" id="submit_form" style="width:auto;" value="<?php echo __('Add Egress Trunk')?>" class="input in-submit"/>
        <input type="button" id="ingress" style="width:140px;" value="<?php echo __('Add Ingress Trunk',true);?>" />
        <!--    <input type="reset"  value="<?php echo __('reset')?>"  class="input in-submit"/>-->
        <input type="button" id="back" value="<?php echo __('Finished')?>" />
    </div>
    <?php }?>
    <?php echo $form->end();?>

    <!-----------Add Rate Table----------->
    <div id="pop-div" class="pop-div" style="display:none;">
        <div class="pop-thead">
            <span></span>
            <span class="float_right"><a href="javascript:closeDiv('pop-div')" id="pop-close" class="pop-close">&nbsp;</a></span>
        </div>
        <div class="pop-content" id="pop-content"></div>
    </div>
    <div id="pop-clarity" class="pop-clarity" style="display:none;"></div>
</div>

</div>
<script type="text/javascript" src="<?php echo $this->webroot?>js/gateway.js"></script>
<script type="text/javascript" src="<?php echo $this->webroot?>js/jquery.base64.min.js"></script>
<script type="text/javascript" src="<?php echo $this->webroot?>js/jquery.livequery.js"></script>
<script type="text/javascript">
    jQuery(document).ready(
    function(){
        jQuery('#totalCall,#totalCPS').xkeyvalidate({type:'Num'});
        jQuery('#alias').xkeyvalidate({type:'strNum'});
        jQuery("form[id^=ClientAddegress]").submit(function(){
            var pa="/[^0-9A-Za-z-\_\s]+/";
            var re =true;
            if(jQuery('#alias').val()==''){
                jQuery(this).addClass('invalid');
                jQuery(this).jGrowlError('The field Egress Name cannot be NULL.');
                return false;
                                       
            }else if(/[^0-9A-Za-z-\_\s]/.test(jQuery("#alias").val())){
                jQuery(this).addClass('invalid');
                jQuery(this).jGrowlError('Egress Name, allowed characters: a-z,A-Z,0-9,-,_,space, maximum  of 256 characters in length!');
                return false;
				  
            }

            if(jQuery('#totalCall').val()!=''){
                if(/\D/.test(jQuery('#totalCall'.val()))){
                    jQuery(this).addClass('invalid');
                    jQuery(this).jGrowlError('Call limit, must be whole number! ');
                    return false;
                }	  
            }

            if(jQuery('#totalCPS').val()!=''){
                if(/\D/.test(jQuery('#totalCPS').val())){
                    jQuery(this).addClass('invalid');
                    jQuery(this).jGrowlError('CPS Limit, must be whole number!');
                    return false;
                }	   
		 	   
            }
            if(jQuery('#ip:visible').val()!=''||!jQuery('#ip:visible').val()){

                if(!/^([\w-]+\.)+((com)|(net)|(org)|(gov\.cn)|(info)|(cc)|(com\.cn)|(net\.cn)|(org\.cn)|(name)|(biz)|(tv)|(cn)|(mobi)|(name)|(sh)|(ac)|(io)|(tw)|(com\.tw)|(hk)|(com\.hk)|(ws)|(travel)|(us)|(tm)|(la)|(me\.uk)|(org\.uk)|(ltd\.uk)|(plc\.uk)|(in)|(eu)|(it)|(jp))$/.test(ip))
                {

                } 
                if(!/^(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])(\.(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])){3}$/.test(jQuery('#ip:visible').val())||

                    !/[a-zA-Z0-9][-a-zA-Z0-9]{0,62}(\.[a-zA-Z0-9][-a-zA-Z0-9]{0,62})+\.?/.test(jQuery('#ip:visible').val())

            ){
				  
                    jQuery(this).addClass('invalid');
                    jQuery(this).jGrowlError('IPs/FQDN must be a valid format ');
                    return false;
				  
                } 
                if(jQuery('#port:visible').val()!=''||!jQuery('#port:visible').val()){
                    if(/\D/.test(jQuery('#port:visible').val())){
                        jQuery(this).addClass('invalid');
                        //	jQuery(this).jGrowlError('Port,must be whole number!');
                        //		re = false;					
                    }	 
				 
				 
                } 
			  
            }  


            return re;

        });
					
    }
				
);
                
    jQuery(function($){
        $('#ingress').click(function() {
            window.location.href = "<?php echo $this->webroot ?>clients/addingress/<?php echo $client_id ?>";
        });
        $('#addratetable').live('click', function() {
            $(this).prev().addClass('clicked');
            //window.open('<?php echo $this->webroot?>clients/addratetable', 'addratetable',       'height=800,width=1000,top=0,left=0,toolbar=no,menubar=no,scrollbars=no, resizable=no,location=no, status=no');
        });
    });
 
    function test2(id) {
        $('#ClientRateTableId').livequery(function() {
            var $ratetable = $(this);
            $ratetable.empty();
            $.getJSON('<?php echo $this->webroot ?>clients/getratetable', function(data) {
                $.each(data, function(idx, item) {
                    var option = $("<option value='" + item['id'] + "'>" + item['name'] + "</option>");
                    if($ratetable.hasClass('clicked')) {
                        if(item['id'] == id) {
                            option.attr('selected','selected');
                        }
                    }
                    $ratetable.append(option);
                });
                $ratetable.removeClass('clicked');
            })
        });  
    }
    function test3(id) {
        var $ratetable = $("#ClientRateTableId");
        $.getJSON('<?php echo $this->webroot ?>clients/getratetable', function(data) {
            $.each(data, function(idx, item) {
                var option = $("<option value='" + item['id'] + "'>" + item['name'] + "</option>");
                if(item['id'] == id) {
                    option.attr('selected','selected');
                }
                $ratetable.append(option);
            });
        }) 
    }
</script>
<script type="text/javascript">
    $(function() {
        $('#back').click(function() {
            $('#is_finished').val('1');
            $('#myform').submit();
        });
    });    
</script>