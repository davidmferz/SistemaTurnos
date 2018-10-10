<table cellpadding="0" cellspacing="0" class="pj-table b10" style="width: 100%">
	<thead>
		<tr>
			<th><?php __('booking_room'); ?></th>
			<th><?php __('rooms_number'); ?></th>
			<th class="w70 align_center"><?php __('rooms_adults'); ?></th>
			<th class="w70 align_center"><?php __('rooms_children'); ?></th>
			<th class="w80 align_right"><?php __('rooms_price'); ?></th>
			<th class="w60">&nbsp;</th>
		</tr>
	</thead>
	<tbody>
<?php
if (isset($tpl['arr']) && !empty($tpl['arr']))
{
	foreach ($tpl['arr'] as $item)
	{
		?>
		<tr>
			<td><?php echo pjSanitize::html($item['name']); ?></td>
			<td><?php echo pjSanitize::html($item['number']); ?></td>
			<td class="align_center"><?php echo $item['adults']; ?></td>
			<td class="align_center"><?php echo $item['children']; ?></td>
			<td class="align_right"><?php echo pjUtil::formatCurrencySign(number_format($item['price'], 2, '.', ','), $tpl['option_arr']['o_currency']); ?></td>
			<td>
				<a href="<?php echo $_SERVER['PHP_SELF']; ?>" data-id="<?php echo $item['id']; ?>" class="pj-table-icon-edit room-edit"></a>
				<a href="<?php echo $_SERVER['PHP_SELF']; ?>" data-id="<?php echo $item['id']; ?>" class="pj-table-icon-delete room-delete"></a>
			</td>
		</tr>
		<?php
	}
} else {
	$titles = __('error_titles', true);
	$bodies = __('error_bodies', true);
	?>
	<tr>
		<td colspan="6"><?php echo @$bodies['ABK06']; ?> <input type="button" value="<?php __('booking_add_room'); ?>" class="pj-button room-add l5" /></td>
	</tr>
	<?php
}
?>
	</tbody>
</table>
<?php 
if (isset($tpl['arr']) && !empty($tpl['arr']))
{
	?>
	<div class="b10">
		<input type="button" value="<?php __('booking_add_room'); ?>" class="pj-button room-add" />
	</div>
	<?php
}
?>