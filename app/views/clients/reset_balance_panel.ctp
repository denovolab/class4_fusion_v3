<form method="post" id="reset_balance_form">
    <input type="hidden" name="client_id" value="<?php echo $client_id; ?>" />
    <table>
        <tr>
            <td style="text-align: right;">Balance:</td>
            <td><input type="text" id="balance" class="input in-text in-input" style="width:120px;" name="balance" /></td>
        </tr>
        <tr>
            <td style="text-align: right">Begin Time:</td>
            <td><input type="text" id="begin_time" class="input in-text in-input" style="width:120px;" onclick="WdatePicker({dateFmt:'yyyy-MM-dd',lang:'en'})" name="begin_time" /></td>
        </tr>
        
        <tr>
            <td style="text-align: right;">Description:</td>
            <td><input type="text" class="input in-text in-input" style="width:120px;" name="description" /></td>
        </tr>
        <tr>
            <td colspan="2">
                <input type="button" id="reset_balance_btn" class="input in-submit in-button" value="Reset" />
            </td>
            
        </tr>
    </table>

</form>
