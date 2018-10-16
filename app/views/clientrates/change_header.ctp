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
    
    <form  id="myform" method="post" action="<?php echo $this->webroot ?>clientrates/import/<?php echo $rate_table_id; ?>"> 
        <input type="hidden" name="cmd" value="<?php echo $cmd ?>">
        <input type="hidden" name="end_effective_date" value="<?php echo $end_effective_date ?>">
        <input type="hidden" name="abspath" value="<?php echo $abspath; ?>">
        <input type="hidden" name="is_ocn_lata" value="<?php echo $is_ocn_lata; ?>">
        <input type="hidden" name="date_format" value="<?php echo $date_format; ?>">
        <input type="hidden" name="rates_file_cmd" value="<?php echo $rates_file_cmd; ?>">
    <table class="list">
        <thead>
            <?php
                $headers = array_shift($table);
                foreach($headers as $header):
            ?>
            <th>
                <select class="columns" name="columns[]">
                    <?php foreach($columns as $column): 
                        $header = strtolower($header);
                        ?>
                    <option value="<?php echo $column; ?>" <?php if ($header == $column) echo 'selected="selected"'; ?>><?php echo $column; ?></option>
                    <?php endforeach; ?>
                    <option value="" <?php if ($header == '') echo 'selected="selected"'; ?>>ignore</option>
                    <option value="unkown" <?php if ($header != '' && !in_array($header, $columns)) echo 'selected="selected"'; ?>>unkown</option>
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

<script>
    $(function() {
        var $myform = $('#myform');
        var $columns = $('.columns');
        
        $myform.submit(function() {
            var flag = true;
            var effective_selected = false;
            var rate_selected = false;
            $columns.each(function() {
                var val = $(this).val();
                if (val == 'unkown')
                {
                    $.jGrowl("There is unkown field!",{theme:'jmsg-error'});
                    flag = false;
                    return;
                }
                if (val == 'effective_date')
                    effective_selected = true;
                
                //if (val == 'rate')
                    //rate_selected = true;
            });
            
            if (!effective_selected)
            {
                $.jGrowl("You have not selected the field of effective!",{theme:'jmsg-error'});
                flag = false;
            }
            
            
            //if (!rate_selected)
            //{
            //   $.jGrowl("You have not selected the field of rate!",{theme:'jmsg-error'});
            //    flag = false;
            //}
            return flag;
        });
    });
</script>