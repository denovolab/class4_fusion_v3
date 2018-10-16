<div id="title">
    <h1>
        <?php __('Finance'); ?>
        &gt;&gt;
        <?php __('View Past Due Notification Log');?>
    </h1>
    <ul id="title-menu">
       <li>
            <a href="javascript:history.go(-1)" class="link_back">
                <img width="16" height="16" src="<?php echo $this->webroot ?>images/icon_back_white.png" alt="Back">
                &nbsp;Back         
            </a>
       </li>
    </ul>
</div>

<div id="container">

<table class="form">
    <tbody>
        <tr>
            <td>Suject</td>
            <td><?php echo $data[0][0]['mail_sub'] ?></td>
        </tr>
        <tr>
            <td>Content</td>
            <td><?php echo str_replace("\n", "<br />", $data[0][0]['mail_content']); ?></td>
        </tr>
        <tr>
            <td>Invoice File</td>
            <td>
                <a href="<?php echo $this->webroot; ?>upload/invoice/<?php echo $data[0][0]['pdf_file'] ?>">Download</a>
            </td>
        </tr>
    </tbody>
</table>

</div>