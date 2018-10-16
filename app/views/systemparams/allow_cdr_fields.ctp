<div id="title">
  <h1>
    <?php __('Configuration')?>
    &gt;&gt;
    <?php echo __('Carrier Portal Allowed CDR Fields')?>
  </h1>
</div>

<div id="container" style="text-align:center;">
    <form method="post">
    <h1 style="text-align:left;font-size:14px;">
        Carrier Portal Allowed CDR Fields:
    </h1>
    <select name="allow_cdr_fields[]" multiple="multiple" style="width:50%;height:500px;">
        <?php foreach ($fields as $key=>$field): ?>
        <option value="<?php echo $key ?>" <?php if(in_array($key, $allow_cdr_fields)) echo 'selected="selected"'; ?>><?php echo $field ?></option>
        <?php endforeach; ?>
    </select>
    <br />
    <input type="submit" value="Submit" />
    </form>
</div>