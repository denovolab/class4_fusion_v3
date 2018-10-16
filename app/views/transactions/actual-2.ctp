<div id="title">
    <h1> <?php echo __('Finance',true);?>&gt;&gt;<?php echo __('Actual Transaction',true);?> </h1>
</div>

<div class="container">
   
    <fieldset style=" clear:both;overflow:hidden;margin-top:10px;" class="query-box">
        <div class="search_title">
          <img src="<?php echo $this->webroot; ?>images/search_title_icon.png">
          <?php echo __('Search',true);?>  
        </div>
        <div style="margin:0px auto; text-align:center;">
        <form name="myform" method="get">
            <input type="hidden" name="query" value="1" />
            Period:
            <input type="text" name="start" style="width:120px;" onclick="WdatePicker({startDate:'%y-%M-%d',dateFmt:'yyyy-MM-dd',lang:'en'})" />
            ~
            <input type="text" name="end" style="width:120px;" onclick="WdatePicker({dateFmt:'yyyy-MM-dd',lang:'en'})" />
            <?php echo __('carrier',true);?>:
            <select id="client" name="client_id">
                <?php foreach($clients as $client): ?>
                <option value="<?php echo $client[0]['client_id'] ?>"><?php echo $client[0]['name'] ?></option>
                <?php endforeach; ?>
            </select>
            <?php echo __('type',true);?>:
            <select id="type" name="type">
                <option value="0">All</option>
                <option value="1">payment received</option>
                <option value="2">payment sent</option>
                <option value="3">invoice received</option>
                <option value="4">invoice sent</option>
                <option value="5">credit note received</option>
                <option value="6">credit note sent</option>
                <option value="7">debit note received</option>
                <option value="8">debit note sent</option>
                <option value="9">reset</option>
                <option value="10">egress actual usage</option>
                <option value="11">ingress actual usage</option>
            </select>
            <input type="submit" value="<?php echo __('submit',true);?>" />
        </form>
        </div>
   </fieldset>
</div>

