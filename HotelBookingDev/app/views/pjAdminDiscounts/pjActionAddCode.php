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
	<input type="hidden" name="add_code" value="1" />
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
		<label class="title"><?php __('discount_code'); ?></label>
		<span class="inline_block">
			<input type="text" name="promo_code" class="pj-form-field w100 required" data-msg-required="<?php __('lblFieldRequired');?>"/>
		</span>
	</p>
	<p>
		<label class="title"><?php __('discount_discount'); ?></label>
		<span class="inline_block">
			<input type="text" name="discount" class="pj-form-field align_right w70 number required" data-msg-required="<?php __('lblFieldRequired');?>"/>
			<select name="type" class="pj-form-field required" data-msg-required="<?php __('lblFieldRequired');?>">
			<?php
			foreach (__('discount_types', true) as $k => $v)
			{
				?><option value="<?php echo $k; ?>"><?php echo $k == 'amount' ? $tpl['option_arr']['o_currency'] : $v; ?></option><?php
			}
			?>
			</select>
		</span>
	</p>
</form>