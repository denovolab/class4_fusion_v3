<div class="dialog_form">
    <form method="post" id="mail_form" action="<?php echo $this->webroot?>rate_managements/email/<?php echo $id; ?>">
    <table class="list">
        <tr>
            <td>Type:</td>
            <td>
                <select id="email_type" class="input in-select select" name="type">
                    <option value="0">Success Rate Notice</option>
                    <option value="1">Failed Rate Notice</option>
                </select>
            </td>
        </tr>
        <tr>
            <td>To:</td>
            <td>
                <select class="input in-select select" name="notice">
                    <option value="0">Email to Client</option>
                    <option value="1">Email to Rate Admin</option>
                    <option value="2">Email to Both</option>
                </select>
            </td>
        </tr>
        <tr id="failure_reasons_layout">
            <td>Failure Reasons:</td>
            <td>
                <textarea class="input in-textarea textarea" name="failure_reasons"></textarea>
            </td>
        </tr>
    </table>
    </form>
</div>