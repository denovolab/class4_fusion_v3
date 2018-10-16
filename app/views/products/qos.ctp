
<div class="dialog_form">
<form action="<?php echo $this->webroot ?>products/qos/<?php echo $this->params['pass'][0] ?>/<?php echo $this->params['pass'][1] ?>" method="post">
    <p>
        <label class="min">Min ASR</label>
        <span>
            <input type="text" name="min_asr" data-options="required:true,validType:'email'" class="input in-text in-input easyui-validatebox" value="<?php echo $data[0][0]['min_asr'] ?>" />
        </span>
    </p>
    <p>
        <label class="min">Max ASR</label>
        <span>
            <input type="text" name="max_asr" data-options="validType:'number'" class="input in-text in-input easyui-validatebox" value="<?php echo $data[0][0]['max_asr'] ?>" />
        </span>
    </p>
    <p>
        <label class="min">Min ABR</label>
        <span>
            <input type="text" name="min_abr" data-options="validType:'number'" class="input in-text in-input easyui-validatebox" value="<?php echo $data[0][0]['min_abr'] ?>" />
        </span>
    </p>
    <p>
        <label class="min">Max ABR</label>
        <span>
            <input type="text" name="max_abr" data-options="validType:'number'" class="input in-text in-input easyui-validatebox" value="<?php echo $data[0][0]['max_abr'] ?>" />
        </span>
    </p>
    <p>
        <label class="min">Min ACD</label>
        <span>
            <input type="text" name="min_acd" data-options="validType:'number'" class="input in-text in-input easyui-validatebox" value="<?php echo $data[0][0]['min_acd'] ?>" />
        </span>
    </p>
    <p>
        <label class="min">Max ACD</label>
        <span>
            <input type="text" name="max_acd" data-options="validType:'number'" class="input in-text in-input easyui-validatebox" value="<?php echo $data[0][0]['max_acd'] ?>" />
        </span>
    </p>
    <p>
        <label class="min">Min PDD</label>
        <span>
            <input type="text" name="min_pdd" data-options="validType:'number'" class="input in-text in-input easyui-validatebox" value="<?php echo $data[0][0]['min_pdd'] ?>" />
        </span>
    </p>
    <p>
        <label class="min">Max PDD</label>
        <span>
            <input type="text" name="max_pdd" data-options="validType:'number'" class="input in-text in-input easyui-validatebox" value="<?php echo $data[0][0]['max_pdd'] ?>" />
        </span>
    </p>
    <p>
        <label class="min">Min ALOC</label>
        <span>
            <input type="text" name="min_aloc" data-options="validType:'number'" class="input in-text in-input easyui-validatebox" value="<?php echo $data[0][0]['min_aloc'] ?>" />
        </span>
    </p>
    <p>
        <label class="min">MAX ALOC</label>
        <span>
            <input type="text" name="max_aloc" data-options="validType:'number'" class="input in-text in-input easyui-validatebox" value="<?php echo $data[0][0]['max_aloc'] ?>" />
        </span>
    </p>
    <p>
        <label class="min">Max Price</label>
        <span>
            <input type="text" name="max_price" data-options="validType:'number'" class="input in-text in-input easyui-validatebox" value="<?php echo $data[0][0]['limit_price'] ?>" />
        </span>
    </p>
    <p>
        <input type="submit" class="input in-button in-submit" value="Submit" />
    </p>

</form>
<div>