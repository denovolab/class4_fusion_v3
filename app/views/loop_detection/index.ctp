<div id="title">
    <h1><?php __('Loop Detection'); ?></h1>
</div>

<div id="container">
    <?php echo $this->element('loop_detection/tab', array('current_page' => 0)); ?>
    <form method="post" id="myform">
    <table class="list">
        <tr>
            <td><?php __('Interval'); ?></td>
            <td>
                <input type="text" name="interval" value="<?php echo isset($data[0][0]['interval']) ? $data[0][0]['interval'] : ''  ?>" />min
            </td>
        </tr>
        <tr>
            <td><?php __('Occurrence Threshold'); ?></td>
            <td>
                <input type="text" name="threshold" value="<?php echo isset($data[0][0]['interval']) ? $data[0][0]['threshold'] : ''  ?>" />
            </td>
        </tr>
        <tr>
            <td><?php __('Block Duration'); ?></td>
            <td>
                <input type="radio" name="block_length" value="0" <?php if ((isset($data[0][0]['block_length']) && $data[0][0]['block_length'] == 0) || !isset($data[0][0]['block_length'])) echo 'checked="checked"';   ?> />Block Forever
                <input type="radio" name="block_length" value="1" <?php if (isset($data[0][0]['block_length']) && $data[0][0]['block_length'] == 1) echo 'checked="checked"';   ?> />Block for 
                <span id="block_for_span">
                    <input type="text" name="block_for" value="<?php echo isset($data[0][0]['block_for']) ? $data[0][0]['block_for'] : ''  ?>"  />min
                </span>
            </td>
        </tr>
        <!--
        <tr>
            <th colspan="2" style="text-align:left;padding-left:20px;">Loop Avoidance</th>
        </tr>
        <tr>
            <td colspan="2">
                <input type="checkbox" name="avoid_same" <?php if ($data[0][0]['avoid_same']) echo 'checked="checked"';  ?> /><?php __('Avoid sending calls to the same carrier'); ?>
            </td>
        </tr>
        -->
        <tr>
            <td colspan="2">
                <input type="submit" value="Submit"/>
                <input type="reset"  value="Reset" />
            </td>
        </tr>
    </table>
    </form>    
</div>

<script type="text/javascript">
    $(function() {
        var $block_for_span = $('#block_for_span');
        var $block_length = $('input[name=block_length]');
        var $myform = $('#myform');
        var $interval = $('input[name=interval]');
        var $threshold = $('input[name=threshold]');
        var $block_for = $('input[name=block_for]');
        
        $block_length.change(function() {
            var $this = $(this);
            var val = parseInt($this.val());
            if (val == 1 && $this.attr('checked')) {
                $block_for_span.show();
            } else {
                $block_for_span.hide();
            }
        }).trigger('change');
        
        $myform.submit(function() {
            var flag = true;
            var val = $interval.val();
            if (val == '' || isNaN(val))
            {
                jQuery.jGrowl('The field Interval must be numeric only.',{theme:'jmsg-error'});
                flag = false;
            }
            val = $threshold.val();
            if (val == '' || isNaN(val))
            {
                jQuery.jGrowl('The field Block for Min.  must be numeric only.',{theme:'jmsg-error'});
                flag = false;
            }
            if ($block_length == 1)
            {
                val = $block_for.val();
                if (val == '' || isNaN(val))
                {
                    jQuery.jGrowl('The field Occurrence Threshold must be numeric only.',{theme:'jmsg-error'});
                    flag = false;
                }
            }
            return flag;
        });
    });
</script>