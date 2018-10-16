<style type="text/css">
    #error_info {
        background:white;width:300px;height:200px;display:none;
        overflow:hide;word-wrap: break-word; padding:20px;
    }
</style>

<div id="title">
    <h1><?php echo __('Switch',true);?>&gt;&gt;<?php echo __('CDR Import',true);?></h1>
    <ul id="title-menu">
        <li>
            <a href="<?php echo $this->webroot ?>copycdr" title="Show All" class="link_btn">
                Show All
            </a>
        </li>
        <li>
            <a href="<?php echo $this->webroot ?>copycdr?showerror=1" title="Show Errors" class="link_btn">
                Show Errors
            </a>
        </li>
    </ul>
</div>

<div id="container">
    <?php echo $this->element("xpage")?>
    <table class="list">
        <thead>
            <tr>
                <td>CDR File Name</td>
                <td>Status</td>
                <td>Copy Time</td>
                <td>Finish Time</td>
                <td>Error Info</td>
            </tr>
        </thead>
        
        <tbody>
            <?php foreach($this->data as $item): ?>
            <tr>
                <td><?php echo $item['CdrLog']['cdr_filename']; ?></td>
                <td><?php echo $status[(string)$item['CdrLog']['status']]; ?></td>
                <td><?php echo $item['CdrLog']['copy_time']; ?></td>
                <td><?php echo $item['CdrLog']['finish_time']; ?></td>
                <td>
                    <a href="###" class="showerror" style="display:block;cursor: pointer;" control="<?php echo $item['CdrLog']['id']; ?>">
                        <?php echo substr($item['CdrLog']['error_info'], 1, 30); ?>
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php echo $this->element("xpage")?>
</div>

<div id="error_info">
</div>

<script>
jQuery.fn.center = function(f) {  
    return this.each(function(){  
        var p = f===false?document.body:this.parentNode;  
        if ( p.nodeName.toLowerCase()!= "body" && jQuery.css(p,"position") == 'static' )  
            p.style.position = 'relative';  
        var s = this.style;  
        s.position = 'absolute';  
        if(p.nodeName.toLowerCase() == "body")  
            var w=$(window);  
        if(!f || f == "horizontal") {  
            s.left = "0px";  
            if(p.nodeName.toLowerCase() == "body") {  
                var clientLeft = w.scrollLeft() - 10 + (w.width() - parseInt(jQuery.css(this,"width")))/2;  
                s.left = Math.max(clientLeft,0) + "px";  
            }else if(((parseInt(jQuery.css(p,"width")) - parseInt(jQuery.css(this,"width")))/2) > 0)  
                s.left = ((parseInt(jQuery.css(p,"width")) - parseInt(jQuery.css(this,"width")))/2) + "px";  
        }  
        if(!f || f == "vertical") {  
            s.top = "0px";  
            if(p.nodeName.toLowerCase() == "body") {  
                var clientHeight = w.scrollTop() - 10 + (w.height() - parseInt(jQuery.css(this,"height")))/2;  
                s.top = Math.max(clientHeight,0) + "px";  
            }else if(((parseInt(jQuery.css(p,"height")) - parseInt(jQuery.css(this,"height")))/2) > 0)  
                s.top = ((parseInt(jQuery.css(p,"height")) - parseInt(jQuery.css(this,"height")))/2) + "px";  
        }  
    });  
};  

$(function() {
    $('.showerror').click(function() {
        var control_id = $(this).attr('control');
        $.ajax({
            'url' : '<?php echo $this->webroot ?>copycdr/get_error_info_detail',
            'type' : 'POST',
            'dataType' : 'text',
            'data' : {'id' : control_id},
            'success' : function(data) {
                $('#error_info').text(data);
                $('#error_info').center().css('opacity', .8).show();
            }
        });
    });
    
    $('#container').click(function() {
        $('#error_info').hide();
    });
});

</script>