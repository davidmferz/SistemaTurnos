<form action="" method="post" class="pj-form form">
	<input type="hidden" name="room_update" value="1" />
	<input type="hidden" name="id" value="<?php echo $tpl['arr']['id']; ?>" />
	<p>
		<label class="title"><?php __('booking_room'); ?></label>
		<span class="left"><?php echo pjSanitize::html($tpl['arr']['name']); ?></span>
	</p>
	<div class="room_details" style="display: block">
		<p>
			<label class="title"><?php __('rooms_adults'); ?></label>
			<input type="text" name="adults" class="pj-form-field w80" data-max="<?php echo (int) $tpl['arr']['max_adults']; ?>" value="<?php echo (int) $tpl['arr']['adults']; ?>" readonly="readonly" />
		</p>
		<p>
			<label class="title"><?php __('rooms_children'); ?></label>
			<input type="text" name="children" class="pj-form-field w80" data-max="<?php echo (int) $tpl['arr']['max_children']; ?>" value="<?php echo (int) $tpl['arr']['children']; ?>" readonly="readonly" />
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
					?><option value="<?php echo $item['id']; ?>"<?php echo $item['id'] != $tpl['arr']['room_number_id'] ? NULL : ' selected="selected"'; ?>><?php echo pjSanitize::html($item['number']); ?></option><?php
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
			</span>
		</p>
		<p>
			<label class="title"><?php __('rooms_price'); ?></label>
			<span class="left"><?php echo pjUtil::formatCurrencySign(number_format($tpl['arr']['price'], 2, '.', ','), $tpl['option_arr']['o_currency']); ?></span>
		</p>
	</div>
</form>