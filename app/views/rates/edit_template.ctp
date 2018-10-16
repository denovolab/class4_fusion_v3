<style type="text/css">
    #myform {
        width:800px;
        margin:0 auto;
    }    
    #myform label {
        float:left;
        width:200px;
        text-align:right;
        padding-right:50px;
    }
    #myform input.input {
        width:300px;
    }
    #myform p.submit {
        text-align:center;
    }
</style>
<div id="title">
    <h1>
        <?php __('System')?>
        &gt;&gt;
        <?php __('Edit template'); ?>
    </h1>
    <ul id="title-menu">
            <li>
                    <a class="link_back" href="<?php echo $this->webroot ?>rates/rate_templates">
    <img width="16" height="16" src="<?php echo $this->webroot ?>images/icon_back_white.png" alt="Back">
    &nbsp;Back</a>    	</li>
    </ul>
</div>

<div id="container">
    <ul class="tabs">
        <li>
            <a href="<?php echo $this->webroot ?>rates/rate_sending">
                <img width="16" height="16" src="<?php echo $this->webroot; ?>images/list.png">Rate sending   		
            </a>
        </li>
        <li class="active">
            <a href="<?php echo $this->webroot ?>rates/rate_templates">
                <img width="16" height="16" src="<?php echo $this->webroot; ?>images/list.png">Template  		
            </a>
        </li>
        <li>
            <a href="<?php echo $this->webroot ?>rates/rate_sending_logging">
                <img width="16" height="16" src="<?php echo $this->webroot; ?>images/list.png">Log  		
            </a>
        </li>
    </ul>
    
    <div id="myform">
        <form method="post">
        <p>
            <label>Name:</label>
            <input type="text" name="name" value="<?php echo $data[0][0]['name']; ?>" />
        </p>
        <p>
            <label>Suject:</label>
            <input type="text" name="subject" value="<?php echo $data[0][0]['subject']; ?>" />
        </p>
        <p>
            <label>Content:</label>
            <textarea name="content" style="width:500px;height:100px;"><?php echo $data[0][0]['content']; ?></textarea>
        </p>
        <p style="text-align:center;">
            <input type="submit" style="width:auto;" value="Submit" />
        </p>
        </form>
    </div>
    
</div>