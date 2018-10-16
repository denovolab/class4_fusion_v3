<style>
table.list table.list thead td{
	height:10px
}
</style>
<div>
<table class="list">
	<thead>
	<tr>
		<td style="width:20%"><?php echo __('Modified To',true);?></td>
		<td style="width:58%"><?php echo __('Modified At',true);?></td>
		<td style="width:20%"><?php echo __('Previous Rate',true);?></td>
	</tr>
	</thead>
	<tbody>
	<?php foreach($this->data as $list){?>
	<tr>
		<td><?php echo $list['Currupdate']['rate']?></td>
		<td><?php echo $list['Currupdate']['modify_time']?></td>
		<td><?php echo $list['Currupdate']['last_rate']?></td>
	</tr>
	<?php }?>
	</tbody>
</table>
</div>