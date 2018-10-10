<form action="" method="post" class="pj-form form">
	<input type="hidden" name="room_add" value="1" />
	<input type="hidden" name="booking_id" value="<?php echo @$_GET['booking_id']; ?>" />
	<input type="hidden" name="hash" value="<?php echo @$_GET['hash']; ?>" />
	
	<p>
		<label class="title"><?php __('booking_room'); ?></label>
		<?php
		if($tpl['cnt_rooms'] > 0)
		{ 
			?>
			<select name="room_id" class="pj-form-field required" data-msg-required="<?php __('lblFieldRequired');?>">
				<option value="">-- <?php __('lblChoose'); ?> --</option>
				<?php
				$hasSelected = FALSE;
				foreach ($tpl['room_arr'] as $item)
				{
					if ((int) $item['max_bookings'] >= (int) $item['cnt'])
					{
						continue;
					}
					$selected = NULL;
					if ($item['id'] == @$_GET['room_id'])
					{
						$selected = ' selected="selected"';
						$hasSelected = TRUE;
					}
					?><option value="<?php echo $item['id']; ?>" data-adults="<?php echo (int) $item['adults']; ?>" data-children="<?php echo (int) $item['children']; ?>"<?php echo $selected; ?>><?php echo pjSanitize::html($item['name']); ?></option><?php
				}
				?>
			</select>
			<?php
		}else{
			$message = __('lblNoRoomMessage', true);
			$message = str_replace("{STAG}", '<a href="'.$_SERVER['PHP_SELF'].'?controller=pjAdminRooms&amp;action=pjActionCreate">', $message);
			$message = str_replace("{ETAG}", '</a>', $message);
			?><span class="block t5"><?php echo $message;?></span><?php
		} 
		?>
	</p>
	<?php 
	if (isset($tpl['dates_not_set']))
	{
		$titles = __('error_titles', true);
		$bodies = __('error_bodies', true);
		pjUtil::printNotice(@$titles['ABK22'], @$bodies['ABK22']);
	} else {
		?>
		<input type="hidden" name="date_from" value="<?php echo pjSanitize::html(@$_GET['date_from']); ?>" />
		<input type="hidden" name="date_to" value="<?php echo pjSanitize::html(@$_GET['date_to']); ?>" />
		<?php
	}
	?>
	<div class="room_details" style="display: <?php echo $hasSelected ? 'block' : 'none'; ?>">
		<p>
			<label class="title"><?php __('rooms_adults'); ?></label>
			<input type="text" name="adults" class="pj-form-field w80" data-max="" value="1" readonly="readonly" />
		</p>
		<p>
			<label class="title"><?php __('rooms_children'); ?></label>
			<input type="text" name="children" class="pj-form-field w80" data-max="" value="0" readonly="readonly" />
		</p>
		<p>
			<label class="title"><?php __('rooms_number'); ?></label>
			<span id="room_number_holder">
			<?php
			if (isset($tpl['room_number_arr']) && !empty($tpl['room_number_arr']))
			{
				?>
				<select name="room_number_id" class="pj-form-field required" data-msg-required="<?php __('lblFieldRequired');?>">
				<option value="">-- <?php __('lblChoose'); ?> --</option>
				<?php
				foreach ($tpl['room_number_arr'] as $item)
				{
					?><option value="<?php echo $item['id']; ?>"<?php echo $item['id'] != @$_GET['room_number_id'] ? NULL : ' selected="selected"'; ?>><?php echo pjSanitize::html($item['number']); ?></option><?php
				}
				?>
				</select>
				<?php
			} else {
				?>
				<span class="left">
					<?php __('booking_no_rooms'); ?>
					<input type="hidden" name="rn_id" value="" class="required" data-msg-required="<?php __('lblFieldRequired');?>"/>
				</span>
				<?php
			}
			?>
			</span>
		</p>
	</div>
</form>