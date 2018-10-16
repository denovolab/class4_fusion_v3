
<tr class="period-block">
  <td class="label" style="width:80px; text-align:right;"><?php __('time')?>
    :</td>
  <td colspan="8" style="width:auto;"><table class="in-date" style="width: 98%;">
      <tbody>
        <tr>
          <td style="width:100px; text-align: left;">
<?php
    $r=array('custom'=>__('custom',true),'curDay'=>__('today',true),'prevDay'=>__('yesterday',true),'curWeek'=>__('currentweek',true),    'prevWeek'=>__('previousweek',true),'curMonth'=>__('currentmonth',true),'prevMonth'=>__('previousmonth',true),'curYear'=>__('currentyear',true),'prevYear'=>__('previousyear',true)); 		
    if(!empty($_POST)){
            if(isset($_POST['smartPeriod'])){
                    $s=$_POST['smartPeriod'];
            }else{
                    $s='curDay';
            }
    }else{

            $s='curDay';
    }
    echo $form->input('smartPeriod',
                    array('options'=>$r,'label'=>false ,
                    'onchange'=>'setPeriod(this.value)','id'=>'query-smartPeriod','name'=>'smartPeriod','style'=>'width:80px;','div'=>false,'type'=>'select','selected'=>$s));
?>
          </td>
          <td><input type="text" id="query-start_date-wDt"
							class="in-text input" onchange="setPeriod('custom')"
							readonly="readonly" onkeydown="setPeriod('custom')" value="<?php if(isset($_GET['start_date'])) echo $_GET['start_date'] ?>"
							name="start_date" style="width: 80px;" >&nbsp;<input type="text" id="query-start_time-wDt"
							onchange="setPeriod('custom')" onkeydown="setPeriod('custom')"
							readonly="readonly" style="width: 60px;" value="00:00:01"
							name="start_time" class="input in-text"></td><td style="width:auto;">&mdash;</td><td><input type="text" id="query-stop_date-wDt"
							class="in-text input" onchange="setPeriod('custom')"
							readonly="readonly" onkeydown="setPeriod('custom')" value=""
							name="stop_date" style="width: 80px;">&nbsp;<input type="text" id="query-stop_time-wDt"
							onchange="setPeriod('custom')" readonly="readonly"
							onkeydown="setPeriod('custom')" style="width: 60px;"
							value="23:59:59" name="stop_time" class="input in-text"></td><td>in</td><td><select id="query-tz"
							style="width: 80px;" name="query[tz]" class="input in-select">
              <option value="-1200">GMT -12:00</option>
              <option value="-1100">GMT -11:00</option>
              <option value="-1000">GMT -10:00</option>
              <option value="-0900">GMT -09:00</option>
              <option value="-0800">GMT -08:00</option>
              <option value="-0700">GMT -07:00</option>
              <option value="-0600">GMT -06:00</option>
              <option value="-0500">GMT -05:00</option>
              <option value="-0400">GMT -04:00</option>
              <option value="-0300">GMT -03:00</option>
              <option value="-0200">GMT -02:00</option>
              <option value="-0100">GMT -01:00</option>
              <option value="+0000">GMT +00:00</option>
              <option value="+0100">GMT +01:00</option>
              <option value="+0200">GMT +02:00</option>
              <option value="+0300">GMT +03:00</option>
              <option value="+0330">GMT +03:30</option>
              <option value="+0400">GMT +04:00</option>
              <option value="+0500">GMT +05:00</option>
              <option value="+0600">GMT +06:00</option>
              <option value="+0700">GMT +07:00</option>
              <option value="+0800">GMT +08:00</option>
              <option value="+0900">GMT +09:00</option>
              <option value="+1000">GMT +10:00</option>
              <option value="+1100">GMT +11:00</option>
              <option value="+1200">GMT +12:00</option>
            </select></td>
          <?php if($group_time && 'usagedetails' != $this->params['controller']){?>
          <td><?php
										$r=array(''=>__('alltime',true),'YYYY-MM-DD  HH24:00:00'=>__('byhours',true),'YYYY-MM-DD'=>__('byday',true),    'YYYY-MM'=>__('bymonth',true),    'YYYY'=>__('byyear',true) ); 		 		
										if(!empty($_GET)){
											if(isset($_GET['group_by_date'])){
												$s=$_GET['group_by_date'];
											}else{
												$s='';
											}
										}else{
											$s='';
										}
										echo $form->input('group_by_date',
					 					array('options'=>$r,'label'=>false ,'id'=>'query-group_by_date','style'=>'width: 80px;','name'=>'group_by_date',
					 					'div'=>false,'type'=>'select','selected'=>$s));
					 				?></td>
          <?php }?>
          <td>
             <?php echo $gettype ?>
              <input type="submit" value="<?php echo __('query',true);?>" id="formquery"  	class="input in-submit">
          
          </td>
        </tr>
      </tbody>
    </table></td>
</tr>
