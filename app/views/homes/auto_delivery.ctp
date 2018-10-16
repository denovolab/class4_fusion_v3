
<div id="title">
    <h1><?php echo __('Statistics'); ?> &gt;&gt; <?php echo __('Dashboard'); ?> &gt;&gt; <?php echo __('Auto Delivery'); ?></h1>
</div>

<div id="container">
    <ul class="tabs">
        <li>
            <a href="<?php echo $this->webroot ?>homes/dashbroad">
                <img width="16" height="16" src="<?php echo $this->webroot ?>images/stock.png">Dashboard</a>
        </li>
        <li>
            <a href="<?php echo $this->webroot ?>homes/report">
                <img width="16" height="16" src="<?php echo $this->webroot ?>images/report.png">Report
            </a>
        </li>
        <li>
            <a href="<?php echo $this->webroot ?>homes/search_charts">
                <img width="16" height="16" src="<?php echo $this->webroot ?>images/showcharts.png">Charts
            </a>
        </li>
        <li class="active">
            <a href="<?php echo $this->webroot ?>homes/auto_delivery">
                <img width="16" height="16" src="<?php echo $this->webroot ?>images/delivery.png">Auto Delivery
            </a>
        </li>
    </ul>
    
    <form method="post">
    <table class="list">
        <tr>
            <td>Group By:</td>
            <td>
                <select name="group_by">
                    <option value="0" <?php echo $data[0][0]['auto_delivery_group_by'] == 0 ? 'selected':''  ?>>Country</option>
                    <option value="1" <?php echo $data[0][0]['auto_delivery_group_by'] == 1 ? 'selected':''  ?>>Code Name</option>
                    <option value="2" <?php echo $data[0][0]['auto_delivery_group_by'] == 2 ? 'selected':''  ?>>Code</option>
                    <option value="3" <?php echo $data[0][0]['auto_delivery_group_by'] == 3 ? 'selected':''  ?>>Trunk</option>
                </select>
            </td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>Time Zone:</td>
            <td>
                <select name="timezone" id="timezone">
                    <option value="-12:00">GMT -12:00</option>
                    <option value="-11:00">GMT -11:00</option>
                    <option value="-10:00">GMT -10:00</option>
                    <option value="-09:00">GMT -09:00</option>
                    <option value="-08:00">GMT -08:00</option>
                    <option value="-07:00">GMT -07:00</option>
                    <option value="-06:00">GMT -06:00</option>
                    <option value="-05:00">GMT -05:00</option>
                    <option value="-04:00">GMT -04:00</option>
                    <option value="-03:00">GMT -03:00</option>
                    <option value="-02:00">GMT -02:00</option>
                    <option value="-01:00">GMT -01:00</option>
                    <option value="+00:00">GMT +00:00</option>
                    <option value="+01:00">GMT +01:00</option>
                    <option value="+02:00">GMT +02:00</option>
                    <option value="+03:00">GMT +03:00</option>
                    <option value="+04:00">GMT +04:00</option>
                    <option value="+05:00">GMT +05:00</option>
                    <option value="+06:00">GMT +06:00</option>
                    <option value="+07:00">GMT +07:00</option>
                    <option value="+08:00">GMT +08:00</option>
                    <option value="+09:00">GMT +09:00</option>
                    <option value="+10:00">GMT +10:00</option>
                    <option value="+11:00">GMT +11:00</option>
                    <option value="+12:00">GMT +12:00</option>
                </select>
            </td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>Email address:</td>
            <td>
                <input type="text" name="email_address" style="width:400px;" value="<?php echo $data[0][0]['auto_delivery_address']; ?>" />
            </td>
            <td>Separated by ;</td>
        </tr>
        <tr>
            <td>Email Subject:</td>
            <td>
                <input type="text" name="auto_delivery_subject" style="width:400px;" value="<?php echo $data[0][0]['auto_delivery_subject']; ?>"  />
            </td>
            <td>{date}, {timezone}</td>
        </tr>
        <tr>
            <td>Email Content:</td>
            <td>
                <textarea style="width:400px;height:200px;" name="auto_delivery_content"><?php echo $data[0][0]['auto_delivery_content']; ?></textarea>
            </td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td colspan="3"><input type="submit" value="Submit" />&nbsp;&nbsp;<input type="button" id="testing" value="Test" /></td>
        </tr>
    </table>
    </form>
   
    
</div>

<script>
$(function() {
    $('#timezone option[value=<?php echo $data[0][0]['auto_delivery_timezone'];?>]').attr('selected', true);
    
    $('#testing').click(function() {
        $.ajax({
            'url' : '<?php echo $this->webroot ?>/homes/auto_delivery_test',
            'type' : 'POST',
            'dataType' : 'text',
            'success' : function(data) {
                jQuery.jGrowl('The Auto Delivery is sent successfully!',{theme:'jmsg-success'});
            }
        });
    });
});
</script>