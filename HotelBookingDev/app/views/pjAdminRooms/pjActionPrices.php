<?php
if (isset($tpl['status']))
{
	$status = __('status', true);
	switch ($tpl['status'])
	{
		case 2:
			pjUtil::printNotice(NULL, $status[2]);
			break;
	}
} else {
	include PJ_VIEWS_PATH . 'pjAdminRooms/elements/menu.php';
	?>
	<fieldset class="fieldset white">
		<legend><?php __('room_select'); ?></legend>
		<select name="ch_room_id" class="pj-form-field" data-url="<?php echo PJ_INSTALL_URL; ?>index.php?controller=pjPrice&action=pjActionIndex&room_id=">
			<option value="">-- <?php __('room_select'); ?> --</option>
			<?php
			foreach ($tpl['room_arr'] as $item)
			{
				?><option value="<?php echo $item['id']; ?>"><?php echo stripslashes($item['name']); ?></option><?php
			}
			?>
		</select>
	</fieldset>
	<?php
}
?>