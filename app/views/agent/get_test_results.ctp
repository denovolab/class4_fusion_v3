<style>
    #container input {
        width:100px;
    }
</style>
<div id="title">
 <h1><?php  __('Management')?> &gt;&gt;<?php echo __('Agents')?></h1>

 
 <ul id="title-search">
        <form method="get" id="myform1">
           <li>
                            
                            <!--<span style="float:left; height:25px; line-height:25px; font-size:14px; color:#EEEEEE; font-weight:bold;">Output:</span>
                            <select name="out_put" style="width:100px;" class="in-select select" id="output">
                                <option value="web">Web</option>
                                <option value="csv">Excel CSV</option>
                                <option value="xls">Excel XLS</option>
                            </select>
                            -->
               
                            <select id="smartPeriod" class="input in-select" name="query[smartPeriod]" onChange="setPeriods(this.value)" id="query-smartPeriod">
                                <option value="custom" label="custom">custom</option>
                                <option value="curDay" label="today">today</option>
                                <option value="prevDay" label="yesterday">yesterday</option>
                                <option value="curWeek" label="current week">current week</option>
                                <option value="prevWeek" label="previous week">previous week</option>
                                <option value="curMonth" label="current month">current month</option>
                                <option value="prevMonth" label="previous month">previous month</option>
                                <option value="curYear" label="current year">current year</option>
                                <option value="prevYear" label="previous year">previous year</option>
                            </select>
                        
                            <input type="text" id="query-start_date-wDt1" onFocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:00:00'});" class="input in-text" value="<?php echo date('Y-m-d 00:00:00');?>" readonly="readonly" name="start_date" style="width:140px;" />
                         &mdash;&nbsp;
                            <input type="text" id="query-stop_date-wDt1"  onfocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:00:00'});" class="input in-text" value="<?php echo date('Y-m-d 23:59:59');?>" readonly="readonly"   name="stop_date" style="width:140px;" />
                          in:
                            <select id="tz" name="tz" class="input in-select">
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
                                <option selected="selected" value="+0000">GMT +00:00</option>
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
                            </select>
                </li>       

                <li> 
                    <span style="float:left; height:25px; line-height:25px; font-size:14px; color:#EEEEEE; font-weight:bold;">Code Name:</span><input type="text" id="search-_q" class="in-search default-value input in-text defaultText in-input" title="Search" value="Search" name="search">
                </li>
                    <input type="submit" id="submit" class="search_submit input in-submit" value="" name="submit">
        </form>
    </ul>
 
 
 
</div>
<?php
    $status = array(
        0=>'Inactive',
        1=>'Active'
    );

?>
<div id="container">
     <?php
        $data = $p->getDataArray();
    ?>
    <div id="toppage"></div>
    <?php
        if(count($data) == 0){
    ?>
        <div class="msg"><?php echo __('no_data_found')?></div>
    <?php    
        }else{
    ?>
    <table class="list" id="key_list" >
        <thead>
            <tr>
                <td>Create Time</td>
                <td>Country</td>
                <td>Code Name</td>
                <td>Code</td>
                <td>Egress Id</td>
                <td>Client</td>
                <td>Egress Ip</td>
                <td>Egress Alias</td>
                <td>Dnis</td>
                <td>Duration</td>
                
            </tr>
        </thead>
        <tbody>
            <?php foreach($data as $item): ?>
            <tr>
                <td><?php echo $item[0]['test_time']?></td>
                <td><?php echo $item[0]['country']?></td>
                <td><?php echo $item[0]['code_name']?></td>
                <td><?php echo $item[0]['code']?></td>
                <td><?php echo $item[0]['egress_id']?></td>
                <td><?php echo $item[0]['name']?></td>
                <td><?php echo $item[0]['egress_ip']?></td>
                <td><?php echo $item[0]['egress_alias']?></td>
                <td><?php echo $item[0]['dnis']?></td>
                <td><?php echo $item[0]['duration']; ?></td>
                
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div id="tmppage"> <?php echo $this->element('page');?> </div>
    <?php }?>
</div>

<script>
   $(function(){
        //setPeriods("curDay");
        $("#smartPeriod").attr('value',"<?php if(isset($_GET['query']['smartPeriod'])){ echo $_GET['query']['smartPeriod']; }else{echo 'custom';}?>");
        $("#tz").attr('value',"<?php if(isset($_GET['tz'])){echo $_GET['tz'];}else{echo '+0000';}?>");
        $("#query-start_date-wDt1").attr('value',"<?php if(isset($_GET['start_date'])){echo $_GET['start_date'];}else{echo date('Y-m-d 00:00:00');}?>");
        $("#query-stop_date-wDt1").attr('value',"<?php if(isset($_GET['stop_date'])){echo $_GET['stop_date'];}else{echo date('Y-m-d 23:59:59');}?>");
        
    });
    
    
    var currentTime = "<?php echo time(); ?>";
    function printf(fstring)
  { var pad = function(str,ch,len)
      { var ps='';
        for(var i=0; i<Math.abs(len); i++) ps+=ch;
        return len>0?str+ps:ps+str;
      }
    var processFlags = function(flags,width,rs,arg)
      { var pn = function(flags,arg,rs)
          { if(arg>=0)
              { if(flags.indexOf(' ')>=0) rs = ' ' + rs;
                else if(flags.indexOf('+')>=0) rs = '+' + rs;
              }
            else
                rs = '-' + rs;
            return rs;
          }
        var iWidth = parseInt(width,10);
        if(width.charAt(0) == '0')
          { var ec=0;
            if(flags.indexOf(' ')>=0 || flags.indexOf('+')>=0) ec++;
            if(rs.length<(iWidth-ec)) rs = pad(rs,'0',rs.length-(iWidth-ec));
            return pn(flags,arg,rs);
          }
        rs = pn(flags,arg,rs);
        if(rs.length<iWidth)
          { if(flags.indexOf('-')<0) rs = pad(rs,' ',rs.length-iWidth);
            else rs = pad(rs,' ',iWidth - rs.length);
          }
        return rs;
      }
    var converters = new Array();
    converters['c'] = function(flags,width,precision,arg)
      { if(typeof(arg) == 'number') return String.fromCharCode(arg);
        if(typeof(arg) == 'string') return arg.charAt(0);
        return '';
      }
    converters['d'] = function(flags,width,precision,arg)
      { return converters['i'](flags,width,precision,arg);
      }
    converters['u'] = function(flags,width,precision,arg)
      { return converters['i'](flags,width,precision,Math.abs(arg));
      }
    converters['i'] =  function(flags,width,precision,arg)
      { var iPrecision=parseInt(precision);
        var rs = ((Math.abs(arg)).toString().split('.'))[0];
        if(rs.length<iPrecision) rs=pad(rs,' ',iPrecision - rs.length);
        return processFlags(flags,width,rs,arg);
      }
    converters['E'] = function(flags,width,precision,arg)
      { return (converters['e'](flags,width,precision,arg)).toUpperCase();
      }
    converters['e'] =  function(flags,width,precision,arg)
      { iPrecision = parseInt(precision);
        if(isNaN(iPrecision)) iPrecision = 6;
        rs = (Math.abs(arg)).toExponential(iPrecision);
        if(rs.indexOf('.')<0 && flags.indexOf('#')>=0) rs = rs.replace(/^(.*)(e.*)$/,'$1.$2');
        return processFlags(flags,width,rs,arg);
      }
    converters['f'] = function(flags,width,precision,arg)
      { iPrecision = parseInt(precision);
        if(isNaN(iPrecision)) iPrecision = 6;
        rs = (Math.abs(arg)).toFixed(iPrecision);
        if(rs.indexOf('.')<0 && flags.indexOf('#')>=0) rs = rs + '.';
        return processFlags(flags,width,rs,arg);
      }
    converters['G'] = function(flags,width,precision,arg)
      { return (converters['g'](flags,width,precision,arg)).toUpperCase();
      }
    converters['g'] = function(flags,width,precision,arg)
      { iPrecision = parseInt(precision);
        absArg = Math.abs(arg);
        rse = absArg.toExponential();
        rsf = absArg.toFixed(6);
        if(!isNaN(iPrecision))
          { rsep = absArg.toExponential(iPrecision);
            rse = rsep.length < rse.length ? rsep : rse;
            rsfp = absArg.toFixed(iPrecision);
            rsf = rsfp.length < rsf.length ? rsfp : rsf;
          }
        if(rse.indexOf('.')<0 && flags.indexOf('#')>=0) rse = rse.replace(/^(.*)(e.*)$/,'$1.$2');
        if(rsf.indexOf('.')<0 && flags.indexOf('#')>=0) rsf = rsf + '.';
        rs = rse.length<rsf.length ? rse : rsf;
        return processFlags(flags,width,rs,arg);
      }
    converters['o'] = function(flags,width,precision,arg)
      { var iPrecision=parseInt(precision);
        var rs = Math.round(Math.abs(arg)).toString(8);
        if(rs.length<iPrecision) rs=pad(rs,' ',iPrecision - rs.length);
        if(flags.indexOf('#')>=0) rs='0'+rs;
        return processFlags(flags,width,rs,arg);
      }
    converters['X'] = function(flags,width,precision,arg)
      { return (converters['x'](flags,width,precision,arg)).toUpperCase();
      }
    converters['x'] = function(flags,width,precision,arg)
      { var iPrecision=parseInt(precision);
        arg = Math.abs(arg);
        var rs = Math.round(arg).toString(16);
        if(rs.length<iPrecision) rs=pad(rs,' ',iPrecision - rs.length);
        if(flags.indexOf('#')>=0) rs='0x'+rs;
        return processFlags(flags,width,rs,arg);
      }
    converters['s'] = function(flags,width,precision,arg)
      { var iPrecision=parseInt(precision);
        var rs = arg;
        if(rs.length > iPrecision) rs = rs.substring(0,iPrecision);
        return processFlags(flags,width,rs,0);
      }
    farr = fstring.split('%');
    retstr = farr[0];
    fpRE = /^([-+ #]*)(\d*)\.?(\d*)([cdieEfFgGosuxX])(.*)$/;
    for(var i=1; i<farr.length; i++)
      { fps=fpRE.exec(farr[i]);
        if(!fps) continue;
        if(arguments[i]!=null) retstr+=converters[fps[4]](fps[1],fps[2],fps[3],arguments[i]);
        retstr += fps[5];
      }
    return retstr;
  }

    /**
     * Calculates period from current time
     */
    function calcPeriod(type)
    {
        period = new Array();
        period['startDate'] = '';
        period['startTime'] = '00:00:00';
        period['stopDate']  = '';
        period['stopTime']  = '23:59:59';

        if (currentTime == undefined) {
            cur_dt = new Date();
        } else {
            cur_dt = new Date(currentTime*1000);
        }

        stop_dt  = new Date(cur_dt);
        start_dt = new Date(cur_dt);

        switch(type) {
            case 'curYear':
                start_dt.setDate(1);
                start_dt.setMonth(0);
                stop_dt = new Date(start_dt);
                stop_dt.setYear(stop_dt.getFullYear()+1);
                stop_dt.setDate(stop_dt.getDate()-1);
            break;
            case 'prevYear':
                start_dt.setYear(start_dt.getFullYear()-1);
                start_dt.setDate(1);
                start_dt.setMonth(0);
                stop_dt = new Date(start_dt);
                stop_dt.setYear(stop_dt.getFullYear()+1);
                stop_dt.setDate(stop_dt.getDate()-1);
            break;
            case 'curMonth':
                start_dt.setDate(1);
            break;
            case 'prevMonth':
                start_dt.setDate(1);
                start_dt.setMonth(start_dt.getMonth()-1);
                stop_dt = new Date(start_dt);
                stop_dt.setMonth(stop_dt.getMonth()+1);
                stop_dt.setDate(stop_dt.getDate()-1);
            break;
            case 'curWeek':
                dow = cur_dt.getDay();
                if (dow == 0) { dow = 7; }
                start_dt = new Date(cur_dt.getTime() - (dow-1)*24*3600*1000);
            break;
            case 'prevWeek':
                dow = cur_dt.getDay();
                if (dow == 0) { dow = 7; }
                start_dt = new Date(cur_dt.getTime() - (dow-1)*24*3600*1000);
                start_dt.setDate(start_dt.getDate()-7);
                stop_dt = new Date(start_dt);
                stop_dt.setDate(stop_dt.getDate()+6);
            break;
            case 'curDay':
            break;
            case 'prevDay':
                start_dt.setDate(start_dt.getDate()-1);
                stop_dt = new Date(start_dt);
            break;
        }

        period['startDate'] = printf('%04d-%02d-%02d', start_dt.getFullYear(), start_dt.getMonth()+1, start_dt.getDate());
        period['stopDate']  = printf('%04d-%02d-%02d', stop_dt.getFullYear(),  stop_dt.getMonth()+1,  stop_dt.getDate());

        return period;
    }


    function setPeriods(type, current)
    {

     $("#query-start_date-wDt1").click(function(){WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'});});
     $("#query-stop_date-wDt1").click(function(){WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'});});
     if (type != 'custom') {
         document.getElementById("query-start_date-wDt1").onfocus="";//开始日期
         document.getElementById("query-stop_date-wDt1").onfocus="";//结果日期
         period = calcPeriod(type, current);//通过选择的值计算时间
         $('#query-start_date-wDt1').val(period['startDate']+' '+period['startTime']);
         $('#query-stop_date-wDt1').val(period['stopDate']+' '+period['stopTime']);
     }
     $('#query-smartPeriod').val(type);
    }
</script>

