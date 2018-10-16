<div id="title">
    <h1>Statistics &gt;&gt; Simple CDR Export</h1>
</div>

<div id="container">
    <?php echo $this->element('quickcdr/tabs', array('active' => 'form')); ?>
    <form method="post">
        <table class="form list">
            <tbody>
                <?php if (isset($_SESSION ['sst_client_id'])): ?>
                <input type="hidden" name="type" value="0">
                <?php else: ?>
                <tr>
                    <th>Type</th>
                    <td>
                        <select name="type" id="type">
                            <option value="0">Ingress</option>
                            <option value="1">Egress</option>
                        </select>
                       
                    </td>
                </tr>
                 <?php endif; ?>
                <?php if (isset($_SESSION ['sst_client_id'])): ?>
                <input type="hidden" name="client_id" value="<?php echo $_SESSION ['sst_client_id']; ?>">
                <?php else: ?>
                <tr>
                    <th>Client</th>
                    <td>
                        <select name="client_id">
                            <?php foreach ($clients as $client_id => $client_name): ?>
                            <option value="<?php echo $client_id ?>"><?php echo $client_name ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                </tr>
                <?php endif; ?>
                <tr>
                    <th>Start Date</th>
                    <td>
                        <input type="text" name="start_date" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd'});" value="<?php echo date("Y-m-d", strtotime("-1 days")); ?>">
                    </td>
                </tr>
                <tr>
                    <th>End Date</th>
                    <td>
                        <input type="text" name="end_date" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd'});" value="<?php echo date("Y-m-d", strtotime("-1 days")); ?>">
                    </td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="2">
                        <input type="submit" value="Submit">
                    </td>
                </tr>
            </tfoot>
        </table>
    </form>
</div>
