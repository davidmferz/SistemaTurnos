<?php
if (isset($tpl['room_number_arr']) && !empty($tpl['room_number_arr']))
{
	?>
	<select name="room_number_id" class="pj-form-field required" data-msg-required="<?php __('lblFieldRequired');?>">
	<option value="">-- <?php __('lblChoose'); ?> --</option>
	<?php
	foreach ($tpl['room_number_arr'] as $item)
	{
		?><option value="<?php echo $item['id']; ?>"><?php echo pjSanitize::html($item['number']); ?></option><?php
	}
	?>
	</select>
	<?php
} else {
	?>
	<span class="left">
		<?php __('booking_no_rooms'); ?>
		<input type="hidden" name="rn_id" value="" class="required" data-msg-required="<?php __('lblFieldRequired');?>" />
	</span>
	<?php
}
?>