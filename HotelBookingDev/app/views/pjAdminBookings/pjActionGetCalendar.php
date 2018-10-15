<?php
$jqDateFormat = pjUtil::jqDateFormat($tpl['option_arr']['o_date_format']);
$week_start_date = $tpl['week_start_date'];
$week_end_date = $tpl['week_end_date']; 

$months = __('months', true);
$short_months = __('short_months', true);
ksort($months);
ksort($short_months);
$days = __('days', true);
$short_days = __('short_days', true);

$selected_date = pjUtil::formatDate(date('Y-m-d'), 'Y-m-d', $tpl['option_arr']['o_date_format']);
if (isset($_GET['selected_date']) && !empty($_GET['selected_date']))
{
	$selected_date = $_GET['selected_date'];
}
$br = __('booking_restrictions', true);
?>
<table class="tblCalendar" cellpadding="0" cellspacing="0" style="width: 100%;">
	<tr class="hbInvisibleRow">
		<td style="width:216px;">&nbsp;</td>
		<?php
		for($i = 0; $i < 14; $i++)
		{
			?><td style="width: 52px;">&nbsp;</td><?php
		} 
		?>
	</tr>
	<tr class="hbMonthRow">
		<td style="width:216px;"><span style="font-weight:normal;font-size:14px"><?php __('booking_jump_to'); ?>:</span>
			<span class="pj-form-field-custom pj-form-field-custom-after align_middle">
				<input type="text" id="selected_date" name="selected_date" class="pj-form-field w80 datepick pointer required" data-msg-required="<?php __('lblFieldRequired');?>" readonly="readonly" rev="<?php echo $jqDateFormat; ?>" value="<?php echo pjSanitize::html($selected_date); ?>" data-months="<?php echo join(',', $months);?>" data-shortmonths="<?php echo join(',', $short_months);?>" data-day="<?php echo join(',', $days);?>" data-daymin="<?php echo join(',', $short_days);?>"/>
				<span class="pj-form-field-after"><abbr class="pj-form-field-icon-date"></abbr></span>
			</span>
		</td>
		<td colspan="2">
			<a id="hb_prev_week" class="prev-week pj-table-icon-first" title="<?php __('lblPreviousWeek');?>" href="javascript:void(0)" data-week_start="<?php echo date('Y-m-d', strtotime($week_start_date . " -7 days")) ?>"></a>
			<a id="hb_prev_date" class="prev-week pj-table-icon-prev" title="<?php __('lblPreviousDate');?>" href="javascript:void(0)" data-week_start="<?php echo date('Y-m-d', strtotime($week_start_date . " -1 days")) ?>"></a>
		</td>
		<td colspan="10" align="center"><?php echo $tpl['month_label'];?></td>
		<td colspan="2">
			<a id="hb_next_date" class="next-week pj-table-icon-next" title="<?php __('lblNextDate');?>" href="javascript:void(0)" data-week_start="<?php echo date('Y-m-d', strtotime($week_start_date . " +1 days")) ?>"></a>
			<a id="hb_next_week" class="next-week pj-table-icon-last" title="<?php __('lblNextWeek');?>" href="javascript:void(0)" data-week_start="<?php echo date('Y-m-d', strtotime($week_end_date . " +1 days")) ?>"></a>
		</td>
	</tr>
	<tr class="hbDayRow">
		<td><?php __('booking_filter'); ?>:
			<select id="room_id" name="room_id" class="pj-form-field w150">
				<option value="">-- <?php __('lblAll'); ?> --</option>
				<?php
				if (isset($tpl['room_arr']) && !empty($tpl['room_arr']))
				{
					foreach ($tpl['room_arr'] as $v)
					{
						?><option value="<?php echo $v['id']; ?>"<?php echo $v['id'] != @$_GET['room_id'] ? NULL : ' selected="selected"'; ?>><?php echo pjSanitize::html($v['type']); ?></option><?php
					}
				}
				?>
			</select>
		</td>
		<?php
			$days = __('days', true);
			$current_date = date('Y-m-d');
			$selected_date = date('Y-m-d');
			if (isset($_GET['selected_date']) && !empty($_GET['selected_date']))
			{
				$selected_date = pjUtil::formatDate($_GET['selected_date'], $tpl['option_arr']['o_date_format']);
			}
			$_columns = array();
			for ($i = 0; $i < 7; $i++)
			{
				$week_date_timestamp = strtotime($tpl['week_start_date'] . " +$i days");
				?><td colspan="2" class="day<?php echo $week_date_timestamp == strtotime($selected_date) ? ' hbDateFocus' : null; ?><?php echo $week_date_timestamp == strtotime($current_date) ? ' hbCurrentDate' : null; ?>"><span><?php echo date('d', $week_date_timestamp); ?></span><br/><?php echo $days[date('w', $week_date_timestamp)]; ?></td><?php
				$_columns[$i] = $week_date_timestamp;
			} 
			?>
		</tr>
		<?php
		$start_timestamp = strtotime($tpl['week_start_date']);
		foreach($tpl['room_number_arr'] as $v)
		{ 
			?>
			<tr class="hbRoomRow">
				<td><span class="hbNumber"><?php echo $v['number']; ?></span><span class="hbType"><?php echo $v['type']; ?></span></td>
				<?php
				if (isset($tpl['rows'][$v['id']]))
				{
					$column_arr = $tpl['rows'][$v['id']];
					$num_of_cols = 0;
					foreach ($column_arr as $index => $col)
					{
						if (!empty($col['content']))
						{
							$content = $col['content'];
							if (!isset($content['restriction_type']))
							{
								$booking_id = $content['id'];
								$name_arr = array();
								$tooltip_arr = array();
								if (!empty($content['c_fname']))
								{
									$name_arr[] = pjSanitize::clean($content['c_fname']);
								}
								if (!empty($content['c_lname']))
								{
									$name_arr[] = pjSanitize::clean($content['c_lname']);
								}
								if (!empty($content['c_email']))
								{
									$tooltip_arr[] = pjSanitize::clean($content['c_email']);
								}
								if (!empty($content['c_phone']))
								{
									$tooltip_arr[] = __('booking_c_phone', true) . ': '.pjSanitize::clean($content['c_phone']);
								}
								$tooltip_arr[] = __('booking_adults', true) . ': '.pjSanitize::clean($content['adults']) . ', ' . __('booking_children', true) . ': '.pjSanitize::clean($content['children']);
								$other_arr = array();
								if (isset($tpl['other_rooms'][$booking_id]))
								{
									$other_rooms = $tpl['other_rooms'][$booking_id];
									foreach($other_rooms as $r)
									{
										list($rid, $number) = explode("~:~", $r);
										$other_arr[] = $number;
									}
								}
								$tooltip_arr[] = __('booking_rooms', true) . ': ' . (!empty($other_arr) ? join(', ', $other_arr) : 'no');
								
								?><td colspan="<?php echo $col['colspan'];?>" class="hb<?php echo $content['status'];?> hbCalendarTip" title="<?php echo join("<br/>", $tooltip_arr);?>" data-href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBookings&amp;action=pjActionUpdate&amp;id=<?php echo $booking_id; ?>"><?php echo join(" ", $name_arr) . '<br/>ID: ' . $content['uuid']; ?></td><?php
							} else {
								?><td colspan="<?php echo $col['colspan'];?>" class="hb_<?php echo $content['restriction_type']; ?> hbCalendarRestriction" data-href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminRestrictions&amp;action=pjActionIndex"><?php echo @$br[$content['restriction_type']]; ?></td><?php
							}
							$num_of_cols += $col['colspan'];
						} else {
							?><td colspan="<?php echo $col['colspan'];?>"><?php 
							if ($col['colspan'] > 1 || (!isset($column_arr[$index + 1]) || ($column_arr[$index + 1]['colspan'] > 1 && empty($column_arr[$index + 1]['content']))))
							{
								$tmp_index = intval($num_of_cols / 2);
								?><a title="<?php __('booking_add', false, true); ?>" class="booking-add" href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBookings&amp;action=pjActionCreate&amp;date_from=<?php echo isset($_columns[$tmp_index]) ? date("Y-m-d", $_columns[$tmp_index]) : NULL; ?>&amp;room_id=<?php echo $v['room_id']; ?>&amp;room_number_id=<?php echo $v['id']; ?>"></a><?php 
							} else {
								if (isset($column_arr[$index]) && isset($column_arr[$index+1]) && empty($column_arr[$index+1]['content']))
								{
									?><a title="<?php __('booking_add', false, true); ?>" class="booking-add" href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBookings&amp;action=pjActionCreate&amp;date_from=<?php echo isset($_columns[$index]) ? date("Y-m-d", $_columns[$index]) : NULL; ?>&amp;room_id=<?php echo $v['room_id']; ?>&amp;room_number_id=<?php echo $v['id']; ?>"></a><?php
								}else{
									echo '&nbsp;';
								}
							}
							?></td><?php
							$num_of_cols += $col['colspan'];
						}
					}
				} else { 
					foreach($_columns as $timestamp)
					{
						?><td colspan="2"><a title="<?php __('booking_add', false, true); ?>" class="booking-add" href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBookings&amp;action=pjActionCreate&amp;date_from=<?php echo date("Y-m-d", $timestamp); ?>&amp;room_id=<?php echo $v['room_id']; ?>&amp;room_number_id=<?php echo $v['id']; ?>"></a></td><?php
					}
				} 
				?>
			</tr>
			<?php
		} 
		?>
	</table>