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
	<input type="hidden" name="add_restriction" value="1" />
	<p>
		<label class="title"><?php __('restriction_rooms'); ?></label>
		<span class="inline_block">
			<?php
			if(!empty($tpl['room_number_arr']))
			{ 
				?>
				<select name="room_number_id[]" id="ar_room_number_id" class="pj-form-field required" multiple="multiple" data-choose="-- <?php __('lblChoose');?> --" data-checkall="<?php __('lblCheckAll');?>" data-uncheckall="<?php __('lblUnCheckAll');?>" data-msg-required="<?php __('lblFieldRequired');?>">
				<?php
				foreach ($tpl['room_number_arr'] as $item)
				{
					?><option value="<?php echo $item['id']; ?>"><?php echo pjSanitize::html($item['name'] . " - " . $item['number']); ?></option><?php
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
		<label class="title"><?php __('restriction_date_from'); ?></label>
		<span class="pj-form-field-custom pj-form-field-custom-after">
			<input type="text" name="date_from" id="ar_date_from" class="pj-form-field pointer w80 datepick required" data-msg-required="<?php __('lblFieldRequired');?>" readonly="readonly" rel="<?php echo $week_start; ?>" rev="<?php echo $jqDateFormat; ?>" data-months="<?php echo join(',', $months);?>" data-shortmonths="<?php echo join(',', $short_months);?>" data-day="<?php echo join(',', $days);?>" data-daymin="<?php echo join(',', $short_days);?>"/>
			<span class="pj-form-field-after"><abbr class="pj-form-field-icon-date"></abbr></span>
		</span>
	</p>
	<p>
		<label class="title"><?php __('restriction_date_to'); ?></label>
		<span class="pj-form-field-custom pj-form-field-custom-after">
			<input type="text" name="date_to" id="ar_date_to" class="pj-form-field pointer w80 datepick required" data-msg-required="<?php __('lblFieldRequired');?>" readonly="readonly" rel="<?php echo $week_start; ?>" rev="<?php echo $jqDateFormat; ?>" data-months="<?php echo join(',', $months);?>" data-shortmonths="<?php echo join(',', $short_months);?>" data-day="<?php echo join(',', $days);?>" data-daymin="<?php echo join(',', $short_days);?>"/>
			<span class="pj-form-field-after"><abbr class="pj-form-field-icon-date"></abbr></span>
		</span>
	</p>
	<p>
		<label class="title"><?php __('restriction_type'); ?></label>
		<span class="inline_block">
			<select name="restriction_type" id="ar_restriction_type" class="pj-form-field required" data-msg-required="<?php __('lblFieldRequired');?>">
				<option value="">-- <?php __('lblChoose'); ?> --</option>
				<?php
				foreach (__('restriction_types', true) as $k => $v)
				{
					?><option value="<?php echo pjSanitize::html($k); ?>"><?php echo pjSanitize::html($v); ?></option><?php
				}
				?>
			</select>
		</span>
	</p>
</form>