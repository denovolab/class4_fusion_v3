<form method="post">
    <table class="list">
        <tr>
            <td>Name</td>
            <td>
                <input type="text" style="width:320px;"  name="name" value="<?php echo isset($template) ? $template['TroubleTicketsTemplate']['name'] : '' ?>" />
            </td>
        </tr>
        <tr>
            <td>Title</td>
            <td>
                <input type="text" style="width:320px;"  name="title" value="<?php echo isset($template) ? $template['TroubleTicketsTemplate']['title'] : '' ?>" />
            </td>
        </tr>
        <tr>
            <td>Content</td>
            <td>
                <textarea name="content" style="width:500px;height:200px;"><?php echo isset($template) ? $template['TroubleTicketsTemplate']['content'] : '' ?></textarea>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <input type="submit" value="Submit" />
                <input type="reset" value="Reset" />
            </td>
        </tr>
    </table>
</form>