<form method="post" id="update_in_form">
<input type="hidden" name="rate_table_id" value="<?php echo $rate_table_id; ?>" />
<table>
    <tr>
        <td>Country Code</td>
        <td><input type="text" name="jurisdiction_prefix" value="<?php echo $data['jurisdiction_prefix'] ?>" /></td>
    </tr>
    <tr>
        <td>Max Code Length W/O Country Code</td>
        <td><input type="text" name="noprefix_max_length" value="<?php echo $data['noprefix_max_length'] ?>" /></td>
    </tr>
    <tr>
        <td>Min Code Length W/O Country Code</td>
        <td><input type="text" name="noprefix_min_length" value="<?php echo $data['noprefix_min_length'] ?>" /></td>
    <tr>
    <tr>
        <td>Max Code Length With Country Code</td>
        <td><input type="text" name="prefix_max_length" value="<?php echo $data['jprefix_max_length'] ?>" /></td>
    <tr>
    <tr>
        <td>Min Code Length With Country Code</td>
        <td><input type="text" name="prefix_min_length" value="<?php echo $data['prefix_min_length'] ?>" /></td>
    </tr>
    <tr>
        <td colspan="2">
            <input type="button" id="update_in" value="Update" />
        </td>
    </tr>
</table>
</form>
