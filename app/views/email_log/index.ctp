<div id="title">
    <h1><?php echo __('Log',true);?>&gt;&gt;<?php echo __('Email Log',true);?></h1>
</div>

<div id="container">
    <?php echo $this->element("xpage")?>
    <table class="list">
        <thead>
            <tr>
                <td>Sent Time</td>
                <td>Carrier</td>
                <td>Type</td>
                <td>Email Address</td>
                <td>Attachments</td>
            </tr>
        </thead>
        
        <tbody>
            <?php foreach($this->data as $item): ?>
            <tr>
                <td><?php echo $item['EmailLog']['send_time']; ?></td>
                <td><?php echo $item['client']['name']; ?></td>
                <td><?php echo $types[$item['EmailLog']['type']]; ?></td>
                <td><?php echo $item['EmailLog']['email_addresses']; ?></td>
                <td>
                    <?php 
                        $files = explode(';', $item['EmailLog']['files']); 
                        foreach ($files as $file):
                            if(!empty($file))
                            echo '<a href="' . $this->webroot .'email_log/get_file/' . base64_encode($file)  .'">Download</a>&nbsp;'; 
                        endforeach;
                    ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php echo $this->element("xpage")?>
</div>

<fieldset class="query-box" style=" clear:both;overflow:hidden;margin-top:10px;">
        <div class="search_title">
            <img src="<?php echo $this->webroot?>images/search_title_icon.png">
          Search  
        </div>
        <div style="margin:0px auto; text-align:center;">
        <form method="get" name="myform">
            Carrier:
            <select name="client">
                <option value="">All</option>
                <?php foreach($clients as $client): ?>
                <option <?php if(isset($_GET['client']) && $_GET['client'] == $client[0]['client_id']) echo 'selected="selected"'; ?> value="<?php echo $client[0]['client_id'] ?>"><?php echo $client[0]['name'] ?></option>
                <?php endforeach; ?>
            </select>
            Type
            <select name="type">
                <option value="">All</option>
                <?php foreach ($types as $key=>$value): ?>
                <option value="<?php echo $key; ?>" <?php if(isset($_GET['type']) && $_GET['type'] == $key) echo 'selected="selected"'; ?>><?php echo $value; ?></option>
                <?php endforeach; ?>
            </select>
            Period:
            <input type="text" value="<?php echo $start_time ?>" onclick="WdatePicker({dateFmt:'yyyy-MM-dd',lang:'en'})" style="width:120px;" name="start_time" class="input in-text in-input">
            ~
            <input type="text" value="<?php echo $end_time; ?>" onclick="WdatePicker({dateFmt:'yyyy-MM-dd',lang:'en'})" style="width:120px;" name="end_time" class="input in-text in-input">
            <input type="submit" value="Submit" class="input in-submit">
        </form>
        </div>
   </fieldset>
