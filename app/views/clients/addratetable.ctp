<style type="text/css">
	.label{ width:100px; text-align:right;}
	.value{ text-align:left !important; }
	.input_width{ width:50px;}
	.select_width{ width:50px;}
</style>
<div id="add_rate">
<form method="post" name="myform" id="myform1">
              	<table class="form">
                	<tr>
                        <td class="label"><?php echo __('name',true);?>:</td>
                        <td class="value"><input type="text" class="input in-text" name="name"  style="width:130px;"/></td>
                        <td class="label"><?php echo __('Code Deck',true);?>:</td>
                        <td class="value"><select class="select in-select" name="codedeck" style="width:130px;">
                        <option value=""></option>
                        <?php foreach($code_deck_result as $code_deck): ?>
                        <option value="<?php echo $code_deck[0]['id']; ?>"><?php echo $code_deck[0]['name']; ?></option>
                        <?php endforeach; ?>
                      </select></td>
                    </tr>
                    <tr>
                    	<td class="label"><?php echo __('Currency',true);?>:</td>
                        <td class="value">
                          <select class="select in-select" name="currency" style="width:130px;">
                            <?php foreach($currency_result as $currency): ?>
                            <option value="<?php echo $currency[0]['id']; ?>"><?php echo $currency[0]['name']; ?></option>
                            <?php endforeach; ?>
                          </select>
                          </td>
                    	<td class="label"><?php echo __('Jurisdiction Country',true);?>:</td>
                        <td class="value">
                          <select class="select in-select" name="jurcountry" style="width:130px;">
                            <option value=""></option>
                            <?php foreach($jurcountry_result as $jurcountry): ?>
                            <option value="<?php echo $jurcountry[0]['id']; ?>"><?php echo $jurcountry[0]['name']; ?></option>
                            <?php endforeach; ?>
                          </select>
                          </td>
                    </tr>
                    <tr>
                        <td class="label"><?php echo __('Rate Type',true);?>:</td>
                        <td class="value">
                          <select class="select in-select" name="ratetype" style="width:130px;">
                            <option value="0">DNIS</option>
                            <option value="1">LRN</option>
                            <option value="2">LRN BLOCK</option>
                          </select>
                          </td>
                         <td colspan="2"></td>
                    </tr>
                    <tr>
                    	<td colspan="4"><button id="firstbtn" class="input in-submit">Continue</button></td>
                    </tr>
                </table>
                   
              </form>
      	<div id="editor">
            <h1 style="text-align:left;">
              <button id="addratebtn" class="input in-submit"><?php echo __('add',true);?></button>
            </h1>
            <form method="post" name="myform2" id="myform2">
              <table id="list" class="list list-form form">
                <thead>
                  <tr>
                    <td><?php echo __('code',true);?></td>
                    <td><?php echo __('code_name',true);?></td>
                    <td><?php echo __('country',true);?></td>
                    <td><?php echo __('rate',true);?></td>
                    <td><?php echo __('Intra Rate',true);?></td>
                    <td><?php echo __('Inter Rate',true);?></td>
                    <td><?php echo __('Min Time',true);?></td>
                    <td><?php echo __('Interval',true);?></td>
                    <td><?php echo __('Effective Date',true);?></td>
                    <td><?php echo __('end_date',true);?></td>
                    <td><?php echo __('Profile',true);?></td>
                    <td><?php echo __('Time Zone',true);?></td>
                    <td></td>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td><input type="text" class="input in-input in-text input_width" name="code[]" /></td>
                    <td><input type="text" class="input in-input in-text input_width" name="codename[]" /></td>
                    <td><input type="text" class="input in-input in-text input_width" name="country[]" /></td>
                    <td><input type="text" class="input in-input in-text input_width" name="rate[]"/></td>
                    <td><input type="text" class="input in-input in-text input_width" name="intrarate[]" value="0.000000" /></td>
                    <td><input type="text" class="input in-input in-text input_width" name="interrate[]" value="0.000000"/></td>
                    <td><input type="text" class="input in-input in-text input_width" name="min_time[]" value="1"/></td>
                    <td><input type="text" class="input in-input in-text input_width" name="interval[]" value="1"/></td>
                    <td><input type="text" class="input in-input in-text" onFocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'});" name="effectdate[]" value="<?php echo date('Y-m-d 00:00:00')?>"  style="width:100px;"/></td>
                    <td><input type="text" class="input in-input in-text" onFocus="WdatePicker({startDate:'%y-%M-01 23:59:59',dateFmt:'yyyy-MM-dd HH:mm:ss',alwaysUseStartDate:false})" name="endate[]" style="width:100px;" /></td>
                    <td><select class="in-decimal select in-select select_width" name="profile[]">
                        <option></option>
                        <?php foreach($profile_result as $profile): ?>
                        <option value="<?php echo $profile[0]['id']; ?>"><?php echo $profile[0]['name']; ?></option>
                        <?php endforeach; ?>
                      </select></td>
                    <td><select class="in-decimal select in-select select_width" name="timezone[]">
                        <option value=""> </option>
                        <option value="-12:00">-12:00</option>
                        <option value="-11:00">-11:00</option>
                        <option value="-10:00">-10:00</option>
                        <option value="-09:30">-09:30</option>
                        <option value="-09:00">-09:00</option>
                        <option value="-08:00">-08:00</option>
                        <option value="-07:00">-07:00</option>
                        <option value="-06:00">-06:00</option>
                        <option value="-05:00">-05:00</option>
                        <option value="-04:30">-04:30</option>
                        <option value="-04:00">-04:00</option>
                        <option value="-03:30">-03:30</option>
                        <option value="-03:00">-03:00</option>
                        <option value="-02:00">-02:00</option>
                        <option value="-01:00">-01:00</option>
                        <option value="00:00">00:00</option>
                        <option value="0">00:00</option>
                        <option value="01:00">01:00</option>
                        <option value="02:00">02:00</option>
                        <option value="03:00">03:00</option>
                        <option value="03:30">03:30</option>
                        <option value="04:00">04:00</option>
                        <option value="04:30">04:30</option>
                        <option value="05:00">05:00</option>
                        <option value="05:30">05:30</option>
                        <option value="06:00">06:00</option>
                        <option value="06:30">06:30</option>
                        <option value="07:00">07:00</option>
                        <option value="08:00">08:00</option>
                        <option value="09:00">09:00</option>
                        <option value="09:30">09:30</option>
                        <option value="10:00">10:00</option>
                        <option value="10:30">10:30</option>
                        <option value="11:00">11::00</option>
                        <option value="11:30">11::30</option>
                        <option value="12:00">12::00</option>
                      </select></td>
                    <td><img id="delrate" style="cursor:pointer;" src="<?php echo $this->webroot; ?>images/delete.jpg" /></td>
                  </tr>
                </tbody>
              </table>
              <div id="form_footer">
                <button id="sub" class="input in-submit" style="width:150px;"><?php echo __('Submit and return',true);?></button>
              </div>
            </form>
      	</div>
	<script type="text/javascript">
        jQuery(function($) {
            var rate_table_id;
            $('#firstbtn').click(function() {
                $.ajax({
                    url:"<?php echo $this->webroot; ?>clients/addratetable_first",
                    type:"POST",
                    dataType:"text",
                    data:$('#myform1').serialize(),
                    success:function(data) {
                        data = data.replace(/(^\s*)|(\s*$)/g,"");
                        if(data == "0") {
                            alert("The name exists!");
                        } else {
                            rate_table_id = data;
                            if($('select[name=jurcountry]').val() == '') {
                                $('input[name=intrarate[]]').remove();
                                $('input[name=interrate[]]').remove();
                            }
                            $('#myform1').remove();
                            $('#editor').show();
							$('#pop-div').css({'width':'900px','height':'auto','left':'30%'});
                        }                    
                    }
                });
                return false;
            });
            
            $('#editor').hide();
    
            $('#addratebtn').click(function() {
                $('#list tbody tr:first-child').clone(true).appendTo('#list tbody');
            });
    
            $('#delrate').click(function() {
                $(this).parent().parent().remove();
            });
            
            $('#sub').click(function() {
                $.ajax({
                    url:"<?php echo $this->webroot; ?>clients/addratetable_second/" + rate_table_id,
                    type:"POST",
                    dataType:"text",
                    data:$('#myform2').serialize(),
                    success:function(data) {
						test3(rate_table_id);
						//test2(rate_table_id);
						$("#pop-div").hide();
						$("#pop-clarity").hide();
						
						/*
						window.opener.test2(rate_table_id);
                        window.opener=null;      
                        window.open('','_self');      
                        window.close();
						
						window.location.reload();
						*/
                    }
                });
                return false;
            });
            
        });
	
    </script> 
    
 </div> 