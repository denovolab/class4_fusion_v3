<style type="text/css">
table.list tbody td.last {text-align:left;}
</style>
<div id="title">
    <h1> <?php __('Tools'); ?>&gt;&gt;<?php __('Rerating'); ?> </h1>
</div>
<div id="container">
    <form method="post" enctype="multipart/form-data">
        <table class="list">
            <tbody>
            <tr>
                <td>
                    <span>Rerating Type</span>
                </td>
                <td>
                <select name="type">
                    <option value="1">Origination</option>
                    <option value="2">Termination</option>
                </select> 
                </td>
            </tr>
            <tr>
                <td>
                    <span>Rerating CDR File</span>
                </td>
                <td>
                    <input type="file" name="upfile" />
                </td>
            </tr>
            <tr>
                <td>
                    <span>Rerating Time</span>
                </td>
                <td>
                    <input type="text" name="time" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'});" />
                </td>
            </tr>
            <tr>
                <td>
                    <span>Rate Table</span>
                </td>
                <td>
                    <select name="rate_table_id">
                        <option></option>
                        <?php foreach($ratetables as $ratetable): ?>
                        <option value="<?php echo $ratetable[0]['rate_table_id']; ?>"><?php echo $ratetable[0]['name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>
                    <span>Client CDR Table Name</span>
                </td>
                <td>
                    <input type="text" name="table_name" />
                </td>
            </tr>
            <tr>
                <td colspan="2" align="center">
                    <input type="submit" value="Submit" />
                </td>
            </tr>
         <tbody>
     </table>
    </form>
</div>