<style type="text/css">
    select.in-select {margin-bottom: 0px;}
    .button_group {text-align: center;}
</style>

<div id="title">
    <h1>
         File Format
    </h1>
</div>

<div id="container">
    
    <form method="post"> 
    <table class="list">
        <thead>
            <?php
                $headers = array_shift($table);
                foreach($headers as $header):
            ?>
            <th>
                <select name="columns[]">
                    <?php foreach($columns as $column): ?>
                    <option <?php if (strtolower($header) == $column) echo 'selected="selected"'; ?>><?php echo $column; ?></option>
                    <?php endforeach; ?>
                    <option value="" <?php if ($header == '') echo 'selected="selected"'; ?>>ignore</option>
                    <option value="<?php echo $header; ?>" <?php if ($header != '' && !in_array($header, $columns)) echo 'selected="selected"'; ?>>unkown</option>
                </select>
            </th>
            <?php
                endforeach;
            ?>
        </thead>
        
        
        <tbody>
            <?php foreach($table as $row): ?>
            <tr>
            <?php foreach($row as $field): ?>
                <td><?php echo $field; ?></td>
            <?php endforeach;?>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="button_group">
        <input type="submit" value="Submit" />
        
        <input type="reset" value="Reset" />
    </div>
    </form>
</div>