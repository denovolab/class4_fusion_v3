<div id="title"> 
    <h1><?php __('Tool') ?> &gt;&gt; <?php __('Disk Space Monitor'); ?></h1> 
</div> 

<div id="container">
    <table class="list">
        <thead>
            <tr>
                <th>Purpose</th>
                <th>Path</th>
                <td>Total Space</td>
                <td>Available Space</td>
            </tr>
        </thead>
        <tbody>
            <?php foreach($data as $item): ?>
            <tr>
                <?php foreach($item as $value): ?>
                <td><?php echo $value; ?></td>
                <?php endforeach; ?>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>