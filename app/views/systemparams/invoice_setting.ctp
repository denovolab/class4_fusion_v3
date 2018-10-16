<style type="text/css">
    .tags {cursor:pointer;color:red;}
</style>

<div id="title">
    <h1><?php echo __('Configuration'); ?> &gt;&gt; <?php echo __('Invoice Setting'); ?></h1>
</div>

<div id="container">
    <form method="post" enctype="multipart/form-data">
    <table class="list list-form">
        <thead>
            <tr>
                <th colspan="3">Invoice Setting</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Invoice Logo:</td>
                <td>
                    <input type="file" name="logoimg" />
                </td>
                <td>
                    <img src="<?php echo $logo; ?>" />
                </td>
            </tr>
            <tr>
                <td>Invoice Number Convention:</td>
                <td>
                    <input type="text" name="invoice_name" value="<?php echo $data[0][0]['invoice_name'] ?>" />
                </td>
                <td>&nbsp</td>
            </tr>
            <tr>
                <td>Overlap Invoice Protection:</td>
                <td>
                    <input type="checkbox" name="overlap_invoice_protection" <?php if ($data[0][0]['overlap_invoice_protection']) echo 'checked="checked"'; ?> />
                </td>
                <td>&nbsp</td>
            </tr>
            <tr>
                <td>PDF Template Place -> Billing Details Location:</td>
                <td>
                    <select name="tpl_number">
                        <option value="2" <?php  echo $data[0][0]['tpl_number'] == 2 ? 'selected':'' ?>>middle</option>
                        <option value="0" <?php  echo $data[0][0]['tpl_number'] == 0 ? 'selected':'' ?>>buttom</option>
                        <option value="1" <?php  echo $data[0][0]['tpl_number'] == 1 ? 'selected':'' ?>>top</option>
                    </select>
                </td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>PDF Template Info -> Billing Details:</td>
                <td>
                    <textarea style="height:150px;width:450px;" name="pdf_tpl" wrap="virtual"><?php echo $data[0][0]['pdf_tpl'] ?></textarea>
                </td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>Company info</td>
                <td>
                    <textarea style="height:150px;width:450px;" id="company_info" name="company_info" wrap="virtual"><?php echo $data[0][0]['company_info'] ?></textarea>
                </td>
                <td>
                   &nbsp;
                </td>
            </tr>
            <tr>
                <td>Invoice CDR Fields</td>
                <td>
                    <?php
                        $send_cdr_fields = explode(',', $data[0][0]['send_cdr_fields']);
                    ?>
                    <select name="cdr_fields[]" multiple="multiple" style="width:450px;height:400px;">
                        <?php foreach($cdr_fields as $cdr_field_key=>$cdr_field): ?>
                        <option value="<?php echo $cdr_field_key; ?>" <?php if (in_array($cdr_field_key, $send_cdr_fields)) echo 'selected="selected"' ?>><?php echo $cdr_field ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
                <td>
                   &nbsp;
                </td>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3">
                    <input type="submit" value="Submit" />
                </td>
            </tr>
        </tfoot>
    </table>
    </form>
</div>

