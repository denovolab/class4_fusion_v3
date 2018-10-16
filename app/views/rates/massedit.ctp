<style type="text/css">
.list input {
    font-size:1.0em;
}
.in-text, .in-password, .in-textarea {
   margin:0;padding:5px;
}
dl {
    padding:5px;overflow:hidden;
}
dl dt {
    font-size:14px;font-weight:bold;padding:1px;color:red;cursor:pointer;
}
dl dd {
    clear:both;height:30px;display:none;
}
dl dd span{
    width:24.5%;display:block;float:left;border:1px solid #ccc;height:30px;line-height:30px;text-align:center;
}
dl dd span .input {
    margin-top:2px;
}
dl dd span img {
    margin-top:6px;
}
#add2 {
    display:none;float:right;margin-right:500px;
}
.extra {
    display:none;
}
</style>
<div id="title">
 <h1>Rate &gt;&gt;Mass Edit</h1>
  <ul id="title-menu">
   <?php  if ($_SESSION['role_menu']['Switch']['rates']['model_w']) {?>
   <li>
     <a href="###" id="add1" class="link_btn">
         <img width="16" height="16" alt="" src="<?php echo $this->webroot; ?>images/add.png">
  	 Create New
     </a>
   </li>
   <?php }?>
    <li>
    <a class="link_back" href="<?php echo $this->webroot?>rates/rates_list"> <img width="16" height="16" alt="<?php echo __('goback',true);?>" src="<?php echo $this->webroot?>images/icon_back_white.png">&nbsp;<?php echo __('goback',true);?> </a>
    </li>
  </ul>
</div>
<div class="container">
    <form id="massform" name="massform" method="post">
    <table class="list">
        <thead>
            <tr>
                <td><?php echo __('Code',true);?></td>
                <td><?php echo __('code_name',true);?></td>
                <td><?php echo __('country',true);?></td>
                <td>Rate</td>
                <td>Setup Fee</td>
                <td>Effective Date</td>
                <td>End Date</td>
                <td>Extra Fields</td>
                <td>End Break-out</td>
                <td></td>
            </tr>
        </thead>

        <tbody>
            <tr>
                <td><input type="text" class="input in-input in-text" style="font-weight:bold;width:100px;" name="code[]" /></td>
                <td><input type="text" class="input in-input in-text" style="width:100px;" name="codename[]" /></td>
                <td><input type="text" class="input in-input in-text" style="width:100px;" name="country[]" /></td>
                <td><input type="text" class="input in-input in-text" value="0.000000" style="width:100px;" name="rate[]" /></td>
                <td><input type="text" class="input in-input in-text" value="0.000000" style="width:100px;" name="setupfee[]" /></td>
                <td><input type="text" class="input in-input in-text" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'});" value="<?php echo date("Y-m-d 00:00:00") ?>" style="width:100px;" name="effectdate[]" /></td>
                <td><input type="text" class="input in-input in-text" onfocus="WdatePicker({startDate:'%y-%M-01 23:59:59',dateFmt:'yyyy-MM-dd HH:mm:ss',alwaysUseStartDate:false})" style="width:100px;" name="enddate[]" /></td>
                <td><a href="###" class="tpl-params-link"><b class="neg">»</b><b class="neg">»</b><b class="neg">»</b></a></td>
                <td>
                    <input type="checkbox" class="input in-input in-text" name="endbreakout[]" />
                    <input type="hidden" name="endbreakouts[]" />
                </td>
                <td><img src="<?php echo $this->webroot ?>images/delete.jpg" /></td>
            </tr>
            <tr class="extra">
                <td colspan="10">
                    Min Time: <input type="text" class="input in-input in-text" value="1" style="font-weight:bold;width:100px;" name="mintime[]" />sec &nbsp;	
                    Interval: <input type="text" class="input in-input in-text" value="1" style="font-weight:bold;width:100px;" name="interval[]" />sec &nbsp;	
                    Grace Time: <input type="text" class="input in-input in-text" value="0" style="font-weight:bold;width:100px;" name="gracetime[]" />sec &nbsp;	
                    Seconds: <input type="text" class="input in-input in-text" value="60"  style="font-weight:bold;width:100px;" name="seconds[]" />sec &nbsp;	
                    Profile: <select class="in-decimal select in-select" name="profile[]"></select> &nbsp;   	
                    Time Zone: <select class="in-decimal select in-select" name="timezone[]">
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
                              </select> &nbsp;
                       Local rate: <input type="text" class="input in-input in-text" value="0.000000"  style="font-weight:bold;width:100px;" name="localrate[]" />
                </td>
            </tr>
        </tbody>

        <tfoot>
        <?php  if ($_SESSION['role_menu']['Switch']['rates']['model_w']) {?>
            <tr>
                <td colspan="10"><input type="submit" value="<?php echo __('submit',true);?>" /></td>
            </tr>
            <?php }?>
        </tfoot>
    </table>
    </form>
    
    <form id="onlyform" name="onlyform" action="<?php echo $this->webroot ?>rates/masseditend/<?php echo $ids; ?>" method="post">
    <dl id="only">
        <dt><?php echo __('End Code',true);?>...<span>
        <?php  if ($_SESSION['role_menu']['Switch']['rates']['model_w']) {?>
        <input id="add2" class="input in-submit" type="button" value="Add">
        <?php }?>
        </span></dt>
        <dd><span><?php echo __('Code',true);?></span><span>End Date</span><span>End Breakout</span><span></span></dd>
        <dd id="clone">
            <span><input type="text" class="input in-input in-text" style="font-weight:bold;width:150px;" name="code[]" /></span>
            <span><input type="text" onfocus="WdatePicker({startDate:'%y-%M-01 23:59:59',dateFmt:'yyyy-MM-dd HH:mm:ss',alwaysUseStartDate:false})" class="input in-input in-text" style="width:150px;" name="enddate[]" /></span>
            <span>
                <input type="checkbox" class="input in-input in-text" name="endbreakout[]" />
                <input type="hidden" name="endbreakouts[]" />
            </span>
            <span><img class="delete" style="cursor:pointer;" src="<?php echo $this->webroot ?>images/delete.jpg" /></span>
        </dd>
        <?php  if ($_SESSION['role_menu']['Switch']['rates']['model_w']) {?>
        <dd id="btndd" style="margin:5px auto; text-align:center;">
             <input type="submit" id="onlybtn" value="<?php echo __('submit',true);?>" />
        </dd>
        <?php }?>
    </dl>
    </form>
</div>

<script type="text/javascript">
$(function() {
    $('dl dt').click(function() {
        $('dl dd').toggle();
        $('#add2').toggle();
    });

    $('table.list tbody tr td input[name=endbreakouts[]]').val(false);
    $('#only dd span input[name=endbreakouts[]]').val(false);

    $('table.list tbody tr td input[name=endbreakout[]]').live('click', function() {
        $(this).next().val($(this).attr('checked'));
    });

    $('#only dd span input[name=endbreakout[]]').live('click', function() {
        $(this).next().val($(this).attr('checked'));
    });

    $('#add1').click(function() {
        var temp1 = $('table.list tbody tr:first-child').clone(true).appendTo('table.list tbody');
        var temp2 = $('table.list tbody tr:nth-child(2)').clone(true).hide().appendTo('table.list tbody');
        temp1.find("input[name=code[]], input[name=codename[]], input[name=enddate[]]").val('');
        temp1.find("input[name=rate[]], input[name=setupfee[]]").val('0.000000');
        temp1.find("input[name=endbreakout[]]").val(false);
        temp1.find("input[name=effectdate[]]").val('<?php echo date("Y-m-d 00:00:00") ?>');
        temp1.find("input[name=endbreakout[]]").attr('checked', false);
        temp2.find("input[name=mintime[]], input[name=interval[]]").val('1');
        temp2.find("input[name=gracetime[]]").val('0');
        temp2.find("input[name=seconds[]]").val('60');
        temp2.find("input[name=profile[]], input[name=timezone[]]").val('');
        temp2.find("input[name=localrate[]]").val('0.000000');
    });

    $('#add2').click(function() {
        var temp1 = $('#clone').clone(true).insertBefore('#btndd');
        temp1.find('input[name=code[]], input[name=enddate[]]').val('');
        temp1.find("input[name=endbreakout[]]").attr('checked', false);
        return false;
    });

    $('img.delete').live('click', function() {
        if($('#only dd').size() > 3)  {
            $(this).parent().parent().remove();
        }
    });

    $('td.last img').css({'cursor':'pointer'}).live('click', function() {
        if($('table.list tbody tr').size() > 2) {
            $(this).parent().parent().next().remove();
            $(this).parent().parent().remove();
        }
    });

    $('a.tpl-params-link').live('click', function() {
        $(this).parent().parent().next().toggle();
    });
});



$(function() {
    $('#massform').submit(function() {
         var more = false;
         if(isMathOrEqual($(this))) {
            showMessages("[{'field':'','code':'101','msg':'The code must be unique！'}]");
            return false;
         }
         $(this).submit();
    });

    $('#onlyform').submit(function() {
        var flag = true;
        var inputcode = $("input[name=code[]]", $(this));
        var codes = new Object();
        inputcode.each(function(index) {
            codes[$(this).val()] = '1';
        });
        var k = 0;
        for(var i in codes) {
            k++;
        }
        if(inputcode.length > k) {
            showMessages("[{'field':'','code':'101','msg':'The code must be unique！'}]");
            flag = false;
        }

        $('#only dd span input[name=enddate[]]').each(function() {
            if($(this).val() == '') {
                showMessages("[{'field':'','code':'101','msg':'The end date required！'}]");
                flag = false;
            }
        });

        if(!flag) {
            return false;
        }
    });
});

function isMathOrEqual($this) {
    var flag = false;
    $('input[name=code[]]', $this).each(function(index) {
         var match_arr = new Array();
         $(this).parent().parent().siblings().each(function(index) {
            match_arr.push($('input[name=code[]]', $(this)).val());
         });
         if($(this).parent().parent().find('input[name=endbreakout[]]').attr('checked')) {
            if(isMatch(match_arr, $(this).val())) {
                flag = true;
            }
         } else {
            if(isEqual(match_arr, $(this).val())) {
                flag = true;
            }
         }
     }); 
     return flag;
}


function isMatch(arr, val) {
    var reg = new RegExp('^'+val);
    if(arr[0] == undefined) {
        return false;
    }
    for(var i=0;i<arr.length;i++) {
        return reg.test(arr[i]);
    }
}

function isEqual(arr, val) {
    if(arr.length == 1) {
        return false;
    }
    for(var i=0;i<arr.length;i++) {
        return arr[i] == val;
    }
}

</script>