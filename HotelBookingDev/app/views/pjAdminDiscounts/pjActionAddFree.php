<?php
$week_start = isset($tpl['option_arr']['o_week_start']) && in_array((int) $tpl['option_arr']['o_week_start'], range(0,6)) ? (int) $tpl['option_arr']['o_week_start'] : 0;
$jqDateFormat = pjUtil::jqDateFormat($tpl['option_arr']['o_date_format']);
$months = __('months', true);
$short_months = __('short_months', true);
ksort($months);
ksort($short_months);
$days = __('days', true);
$short_days = __('short_days', true);
?>
<form action="" method="post" class="pj-form form">
	<input type="hidden" name="add_free" value="1" />
	<p>
		<label class="title"><?php __('limit_room'); ?></label>
		<span class="inline_block">
			<?php
			if(!empty($tpl['room_arr']))
			{ 
				?>
				<select name="room_id" class="pj-form-field required" data-msg-required="<?php __('lblFieldRequired');?>">
					<option value="">-- <?php __('lblChoose'); ?> --</option>
					<?php
					foreach ($tpl['room_arr'] as $item)
					{
						?><option value="<?php echo $item['id']; ?>"><?php echo stripslashes($item['name']); ?></option><?php
					}
					?>
				</select>
				<?php
			}else{
				$message = __('lblNoRoomMessage', true);
				$message = str_replace("{STAG}", '<a href="'.$_SERVER['PHP_SELF'] . '?controller=pjAdminRooms&amp;action=pjActionCreate">', $message);
				$message = str_replace("{ETAG}", '</a>', $message);
				?><label class="block t5"><?php echo $message;?></label><?php
			} 
			?>
		</span>
	</p>
	<p>
		<label class="title"><?php __('limit_date_from'); ?></label>
		<span class="pj-form-field-custom pj-form-field-custom-after">
			<input type="text" id="date_from" name="date_from" class="pj-form-field pointer w80 datepick required" readonly="readonly" rel="<?php echo $week_start; ?>" rev="<?php echo $jqDateFormat; ?>" data-msg-required="<?php __('lblFieldRequired');?>" data-months="<?php echo join(',', $months);?>" data-shortmonths="<?php echo join(',', $short_months);?>" data-day="<?php echo join(',', $days);?>" data-daymin="<?php echo join(',', $short_days);?>"/>
			<span class="pj-form-field-after"><abbr class="pj-form-field-icon-date"></abbr></span>
		</span>
	</p>
	<p>
		<label class="title"><?php __('limit_date_to'); ?></label>
		<span class="pj-form-field-custom pj-form-field-custom-after">
			<input type="text" id="date_to" name="date_to" class="pj-form-field pointer w80 datepick required" readonly="readonly" rel="<?php echo $week_start; ?>" rev="<?php echo $jqDateFormat; ?>" data-msg-required="<?php __('lblFieldRequired');?>" data-months="<?php echo join(',', $months);?>" data-shortmonths="<?php echo join(',', $short_months);?>" data-day="<?php echo join(',', $days);?>" data-daymin="<?php echo join(',', $short_days);?>"/>
			<span class="pj-form-field-after"><abbr class="pj-form-field-icon-date"></abbr></span>
		</span>
	</p>
	<p>
		<label class="title"><?php __('discount_min_length'); ?></label>
		<span class="inline_block">
			<input type="text" name="min_length" class="pj-form-field w80" readonly="readonly" />
		</span>
	</p>
	<p>
		<label class="title"><?php __('discount_max_length'); ?></label>
		<span class="inline_block">
			<input type="text" name="max_length" class="pj-form-field w80" readonly="readonly" />
		</span>
	</p>
	<p>
		<label class="title"><?php __('discount_free_nights'); ?></label>
		<span class="inline_block">
			<input type="text" name="free_nights" class="pj-form-field w80" readonly="readonly" />
		</span>
	</p>
</form>