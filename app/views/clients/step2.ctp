<style type="text/css">
.container {
    text-align:center;
    height:100px;
    line-height:100px;
}
.container input {
    width:140px;
}
</style>

<div class="container">

    <input type="button" id="egress" value="<?php echo __('Add Egress Trunk',true);?>" />

    <input type="button" id="ingress" value="<?php echo __('Add Ingress Trunk',true);?>" />

    <input type="button" id="cancel" value="<?php echo __('Finish',true);?>" />

</div>

<script type="text/javascript">
jQuery(function($) {
    $('#ingress').click();
    $('#egress').click(function() {
        window.location.href = "<?php echo $this->webroot ?>clients/addegress/<?php echo $client_id ?>";
    });
    $('#ingress').click(function() {
        window.location.href = "<?php echo $this->webroot ?>clients/addingress/<?php echo $client_id ?>";
    });
    $('#cancel').click(function() {
        window.location.href = "<?php echo $this->webroot ?>clients/index";
    });
});
</script>