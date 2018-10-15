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
	<input type="hidden" name="update_limit" value="1" />
	<input type="hidden" name="id" value="<?php echo pjSanitize::html($tpl['arr']['id']); ?>" />
	<p>
		<label class="title"><?php __('limit_room'); ?></label>
		<span class="inline_block">
			<?php
			if(!empty($tpl['room_arr']))
			{ 
				?>
				<select name="room_id" id="al_room_id" class="pj-form-field required" data-msg-required="<?php __('lblFieldRequired');?>">
					<option value="">-- <?php __('lblChoose'); ?> --</option>
					<?php
					foreach ($tpl['room_arr'] as $item)
					{
						?><option value="<?php echo $item['id']; ?>"<?php echo $item['id'] == $tpl['arr']['room_id'] ? ' selected="selected"' : NULL;?>><?php echo stripslashes($item['name']); ?></option><?php
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
			<input type="text" name="date_from" id="al_date_from" class="pj-form-field pointer w80 datepick required" value="<?php echo !empty($tpl['arr']['date_from']) ? pjUtil::formatDate($tpl['arr']['date_from'], 'Y-m-d', $tpl['option_arr']['o_date_format']) : NULL; ?>" data-msg-required="<?php __('lblFieldRequired');?>" readonly="readonly" rel="<?php echo $week_start; ?>" rev="<?php echo $jqDateFormat; ?>" data-months="<?php echo join(',', $months);?>" data-shortmonths="<?php echo join(',', $short_months);?>" data-day="<?php echo join(',', $days);?>" data-daymin="<?php echo join(',', $short_days);?>"/>
			<span class="pj-form-field-after"><abbr class="pj-form-field-icon-date"></abbr></span>
		</span>
	</p>
	<p>
		<label class="title"><?php __('limit_date_to'); ?></label>
		<span class="pj-form-field-custom pj-form-field-custom-after">
			<input type="text" name="date_to" id="al_date_to" class="pj-form-field pointer w80 datepick required" value="<?php echo !empty($tpl['arr']['date_to']) ? pjUtil::formatDate($tpl['arr']['date_to'], 'Y-m-d', $tpl['option_arr']['o_date_format']) : NULL; ?>" data-msg-required="<?php __('lblFieldRequired');?>" readonly="readonly" rel="<?php echo $week_start; ?>" rev="<?php echo $jqDateFormat; ?>" data-months="<?php echo join(',', $months);?>" data-shortmonths="<?php echo join(',', $short_months);?>" data-day="<?php echo join(',', $days);?>" data-daymin="<?php echo join(',', $short_days);?>"/>
			<span class="pj-form-field-after"><abbr class="pj-form-field-icon-date"></abbr></span>
		</span>
	</p>
	<p>
		<label class="title"><?php __('limit_start_on'); ?></label>
		<span class="inline_block">
			<select name="start_on" id="al_start_on" class="pj-form-field">
				<option value="7"><?php __('limit_any_day'); ?></option>
				<?php
				foreach (__('days', true) as $k => $v)
				{
					?><option value="<?php echo $k; ?>"<?php echo $k==$tpl['arr']['start_on'] ? ' selected="selected"' : NULL;?>><?php echo $v; ?></option><?php
				}
				?>
			</select>
		</span>
	</p>
	<p>
		<label class="title"><?php __('limit_min_nights'); ?></label>
		<span class="block">
			<input type="text" name="min_nights" id="al_min_nights" class="pj-form-field w80 required" value="<?php echo $tpl['arr']['min_nights'];?>" readonly="readonly" />
		</span>
	</p>
	<p>
		<label class="title"><?php __('limit_max_nights'); ?></label>
		<span class="block">
			<input type="text" name="max_nights" id="al_max_nights" class="pj-form-field w80 required nightsValidate" value="<?php echo $tpl['arr']['max_nights'];?>" readonly="readonly" data-msg-nightsValidate="<?php __('lblNightsValidation');?>"/>
		</span>
	</p>
</form>