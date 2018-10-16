<style type="text/css">
    #multiple {display:none;}
</style>
<div id="title">
    <h1><?php echo __('DID Management', true); ?>&gt;&gt;<?php echo __('LNP Request', true); ?></h1></h1>
</div>

<div id="container">
    <?php echo $this->element("lnp_request_tab", array('active' => 'submit'))?>
    <form enctype="multipart/form-data" action="<?php echo $this->webroot; ?>did/lnp_request/push" method="post">
        <table class="list">
            <tr>
                <td>Choose Type of LNP Request:</td>
                <td>
                    <input type="radio" name="request_type" value="0" checked="checked" /> Single or Range
                    <input type="radio" name="request_type" value="1" /> Multiple Comma Separated
                </td>
            </tr>
            <tr>
                <td>Sample LOA Templates:</td>
                <td></td>
            </tr>
        </table>

        <table id="single" class="list">
            <tr>
                <td colspan="2">Single or Range Number(s):</td>
            </tr>
            <tr>
                <td>Number to Port:</td>
                <td>
                    <input type="text" name="number_to_port" />
                </td>
            </tr>
            <tr>
                <td>Range To:</td>
                <td>
                    <input type="text" name="range_to" />
                </td>
            </tr>
        </table>

        <table id="multiple" class="list">
            <tr>
                <td colspan="2">Multiple Number(s) Request:</td>
            </tr>
            <tr>
                <td>
                    <textarea name="multiple_numbers_request" style="width:400px;height:100px;"></textarea>
                </td>
                <td>
                    <p>
                        - All Numbers should have SAME BTN and <br />
                        Physical Address.
                    </p>
                    <p>
                        - Only for multiple Numbers Orders (Comma<br />
                        Separated, Max 49 numbers).
                    </p>
                </td>
            </tr>
        </table>

        <table class="list">
            <tr>
                <td>
                    <input type="file" name="upload_file" />
                </td>
            </tr>
            <tr>
                <td>
                    <input type="submit" value="Submit" />
                </td>
            </tr>
        </table>
    </form>
</div>

<script>
$(function() {
    var $single = $('#single');
    var $multiple = $('#multiple');
    
    $('input[name=request_type]').change(function() {
        var val = $(this).val();
        if (val == 0) {
            $single.show();
            $multiple.hide();
        } else {
            $single.hide();
            $multiple.show();
        }
    });
});
</script>