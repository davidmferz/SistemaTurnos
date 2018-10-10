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
	$today = pjUtil::formatDate(date('Y-m-d'), 'Y-m-d', $tpl['option_arr']['o_date_format']);
	$avail_room_arr = array();
	$avail_today = 0;
	if (isset($tpl['avail_room_arr']))
	{
		foreach ($tpl['avail_room_arr'] as $v)
		{
			$cnt = $v['cnt']- $v['booked_rooms'] - $v['pending_rooms'];
			if(isset($tpl['restrictions']) && isset($tpl['restrictions'][$v['id']]) && (int) $tpl['restrictions'][$v['id']] > 0)
			{
				$cnt = $cnt - (int) $tpl['restrictions'][$v['id']];
			}
			$avail_today += $cnt;
			$avail_room_arr[] = '<div class="dashboard-row"><label>'.$v['name'].'</label><span>'.$cnt.'</span></div>';
		}
	}
	?>
	<div class="dashoard-columns">
		<div class="dashboard-column">
			<div class="dashboard-column-inner">
				<div class="dashboard-block dashboard-outline">
					<div class="dashboard-row"><label><?php __('dash_room_booked_today'); ?>:</label><span><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBookings&amp;action=pjActionIndex&amp;date_from=<?php echo $today;?>&amp;date_to=<?php echo $today;?>&amp;status=confirmed"><?php echo (int) @$tpl['arr']['booked_today'];?></a></span></div>
					<div class="dashboard-row"><label><?php __('dash_pending_rooms_today'); ?>:</label><span><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBookings&amp;action=pjActionIndex&amp;date_from=<?php echo $today;?>&amp;date_to=<?php echo $today;?>&amp;status=pending"><?php echo (int) @$tpl['arr']['pending_today'];?></a></span></div>
					<div class="dashboard-row"><label><?php __('dash_available_rooms_today'); ?>:</label><span><?php echo $avail_today;?></span></div>
				</div>
				<div class="dashboard-block dashboard-outline dashboard-block-even">
					<div class="dashboard-h2"><?php __('dash_available_rooms_type'); ?>:</div>
					<?php
					echo implode("", $avail_room_arr); 
					?>
					<div class="dashboard-row"><a class="dashboard-link-calendar" href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBookings&action=pjActionCalendar"><?php __('dash_view_calendar'); ?></a></div>
				</div>
				<div class="dashboard-block dashboard-outline">
					<div class="dashboard-h2"><?php __('dash_guests'); ?>:</div>
					<div class="dashboard-row"><label><?php __('dash_staying_tonight'); ?></label><span><?php echo !empty($tpl['sleeping']['guests']) ? $tpl['sleeping']['guests'] : 0;?></span></div>
					<div class="dashboard-row"><label class="dashboard-subitem"><?php __('dash_adults'); ?></label><span><?php echo !empty($tpl['sleeping']['total_adults']) ? $tpl['sleeping']['total_adults'] : 0;?></span></div>
					<div class="dashboard-row"><label class="dashboard-subitem"><?php __('dash_children'); ?></label><span><?php echo !empty($tpl['sleeping']['total_children']) ? $tpl['sleeping']['total_children'] : 0;?></span></div>
				</div>
				<div class="dashboard-block dashboard-outline">
					<div class="dashboard-row"><label><?php __('dash_arriving_today'); ?></label><span><?php echo !empty($tpl['arrive_today'][0]['guests']) ? $tpl['arrive_today'][0]['guests'] : 0;?></span></div>
					<div class="dashboard-row"><label class="dashboard-subitem"><?php __('dash_adults'); ?></label><span><?php echo !empty($tpl['arrive_today'][0]['total_adults']) ? $tpl['arrive_today'][0]['total_adults'] : 0;?></span></div>
					<div class="dashboard-row"><label class="dashboard-subitem"><?php __('dash_children'); ?></label><span><?php echo !empty($tpl['arrive_today'][0]['total_children']) ? $tpl['arrive_today'][0]['total_children'] : 0;?></span></div>
				</div>
				<div class="dashboard-block">
					<div class="dashboard-row"><label><?php __('dash_leaving_today'); ?></label><span><?php echo !empty($tpl['leave_today'][0]['guests']) ? $tpl['leave_today'][0]['guests'] : 0;?></span></div>
					<div class="dashboard-row"><label class="dashboard-subitem"><?php __('dash_adults'); ?></label><span><?php echo !empty($tpl['leave_today'][0]['total_adults']) ? $tpl['leave_today'][0]['total_adults'] : 0;?></span></div>
					<div class="dashboard-row"><label class="dashboard-subitem"><?php __('dash_children'); ?></label><span><?php echo !empty($tpl['leave_today'][0]['total_children']) ? $tpl['leave_today'][0]['total_children'] : 0;?></span></div>
				</div>
			</div>
		</div>
		
		<div class="dashboard-column">
			<div id="bookings">
				<ul>
					<li><a href="#bookings-1"><?php __('dash_tab_arrivals'); ?></a></li>
					<li><a href="#bookings-2"><?php __('dash_tab_departures'); ?></a></li>
					<li><a href="#bookings-3"><?php __('dash_tab_latest'); ?></a></li>
				</ul>
				<div id="bookings-1" class="dashboard-column-inner">
				<?php
				if (isset($tpl['arrivals_arr']) && !empty($tpl['arrivals_arr']))
				{
					foreach ($tpl['arrivals_arr'] as $k => $v)
					{
						$name_arr = array();
						if (!empty($v['c_fname']))
						{
							$name_arr[] = pjSanitize::clean($v['c_fname']);
						}
						if (!empty($v['c_lname']))
						{
							$name_arr[] = pjSanitize::clean($v['c_lname']);
						}
						?>
						<div class="dashboard-block dashboard-outline<?php echo $k % 2 == 0 ? ' dashboard-block-odd' : ' dashboard-block-even'?>">
							<div class="dashboard-booking-row"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBookings&amp;action=pjActionUpdate&amp;id=<?php echo $v['id']; ?>"><?php __('dash_id'); ?>: <?php echo pjSanitize::html($v['uuid']);?></a></div>
							<div class="dashboard-booking-row"><?php echo join(' ', $name_arr);?></div>
							<?php
							if(!empty($v['c_phone']))
							{ 
								?><div class="dashboard-booking-row"><?php echo pjSanitize::clean($v['c_phone']);?></div><?php
							} 
							?>
							<div class="dashboard-booking-row"><span class="dashboard-booking-label"><?php __('dash_stay'); ?>:</span> <?php __('dash_to'); ?> <?php echo pjUtil::formatDate($v['date_to'], 'Y-m-d', $tpl['option_arr']['o_date_format']);?>, <?php echo $v['nights'] . ' '; $v['nights'] != 1 ? __('dash_nights') : __('dash_night');?></div>
							<div class="dashboard-booking-row"><span class="dashboard-booking-label"><?php __('dash_rooms'); ?>:</span></div>
							<div class="dashboard-booking-row"><?php echo $v['rooms'];?></div>
						</div>
						<?php
					}
					?><div class="dashboard-block"><a class="dashboard-link-bookings" href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBookings&amp;action=pjActionIndex&amp;date_from=<?php echo urlencode($today); ?>"><?php __('dash_view_all_arrivals'); ?></a></div><?php
				} else {
					?><label class="dashboard-block dashboard-na"><?php __('dash_no_arrivals'); ?></label><?php
				} 
				?>
				</div>
				<div id="bookings-2" class="dashboard-column-inner">
				<?php
				if (isset($tpl['departure_arr']) && !empty($tpl['departure_arr']))
				{
					foreach ($tpl['departure_arr'] as $k => $v)
					{
						$name_arr = array();
						if (!empty($v['c_fname']))
						{
							$name_arr[]  = pjSanitize::clean($v['c_fname']);
						}
						if (!empty($v['c_lname']))
						{
							$name_arr[]  = pjSanitize::clean($v['c_lname']);
						}
						?>
						<div class="dashboard-block dashboard-outline<?php echo $k % 2 == 0 ? ' dashboard-block-odd' : ' dashboard-block-even'?>">
							<div class="dashboard-booking-row"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBookings&amp;action=pjActionUpdate&amp;id=<?php echo $v['id']; ?>"><?php __('dash_id'); ?>: <?php echo pjSanitize::html($v['uuid']);?></a></div>
							<div class="dashboard-booking-row"><?php echo join(' ', $name_arr);?></div>
							<?php
							if(!empty($v['c_phone']))
							{ 
								?><div class="dashboard-booking-row"><?php echo pjSanitize::clean($v['c_phone']);?></div><?php
							} 
							?>
							<div class="dashboard-booking-row"><span class="dashboard-booking-label"><?php __('dash_rooms'); ?>:</span></div>
							<div class="dashboard-booking-row"><?php echo $v['rooms'];?></div>
						</div>
						<?php
					}
					?><div class="dashboard-block"><a class="dashboard-link-bookings" href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBookings&amp;action=pjActionIndex&amp;date_to=<?php echo urlencode($today); ?>"><?php __('dash_view_all_departures'); ?></a></div><?php
				} else {
					?><label class="dashboard-block dashboard-na"><?php __('dash_no_departures'); ?></label><?php
				} 
				?>
				</div>
				<div id="bookings-3" class="dashboard-column-inner">
				<?php
				if (isset($tpl['latest_booking_arr']) && !empty($tpl['latest_booking_arr']))
				{
					foreach ($tpl['latest_booking_arr'] as $k => $v)
					{
						$name_arr = array();
						if (!empty($v['c_fname']))
						{
							$name_arr[]  = pjSanitize::clean($v['c_fname']);
						}
						if (!empty($v['c_lname']))
						{
							$name_arr[]  = pjSanitize::clean($v['c_lname']);
						}
						?>
						<div class="dashboard-block dashboard-outline<?php echo $k % 2 == 0 ? ' dashboard-block-odd' : ' dashboard-block-even'?>">
							<div class="dashboard-booking-row"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBookings&amp;action=pjActionUpdate&amp;id=<?php echo $v['id']; ?>"><?php __('dash_id'); ?>: <?php echo pjSanitize::html($v['uuid']);?></a></div>
							<div class="dashboard-booking-row"><?php echo join(' ', $name_arr);?></div>
							<?php
							if (!empty($v['c_phone']))
							{ 
								?><div class="dashboard-booking-row"><?php echo pjSanitize::clean($v['c_phone']);?></div><?php
							} 
							?>
							<div class="dashboard-booking-row"><span class="<?php echo $v['status']?>"><?php echo @$HB_LANG['booking_statuses'][$v['status']];?></span></div>
							<div class="dashboard-booking-row"><span class="dashboard-booking-label"><?php __('dash_stay'); ?>:</span> <?php __('dash_from'); ?> <?php echo pjUtil::formatDate($v['date_from'], 'Y-m-d', $tpl['option_arr']['o_date_format']);?> <?php __('dash_to'); ?> <?php echo pjUtil::formatDate($v['date_to'], 'Y-m-d', $tpl['option_arr']['o_date_format']);?>, <?php echo $v['nights'] . ' '; $v['nights'] != 1 ? __('dash_nights') : __('dash_night'); ?></div>
							<div class="dashboard-booking-row"><span class="dashboard-booking-label"><?php __('dash_rooms'); ?>:</span></div>
							<div class="dashboard-booking-row"><?php echo $v['rooms'];?></div>
						</div>
						<?php
					}
					?><div class="dashboard-block"><a class="dashboard-link-bookings" href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBookings&amp;action=pjActionIndex"><?php __('dash_view_all_bookings'); ?></a></div><?php
				} else {
					?><label class="dashboard-block dashboard-na"><?php __('dash_no_bookings'); ?></label><?php
				} 
				?>
				</div>
			</div>
		</div>
		
		<div class="dashboard-column">
			<div id="charts">
				<ul>
					<li><a href="#charts-1"><?php __('dash_tab_upcoming'); ?></a></li>
					<li><a href="#charts-2"><?php __('dash_tab_past'); ?></a></li>
				</ul>
				<div id="charts-1">
					<div id="chart-1" style="height: 450px">
						<?php
						if(isset($tpl['avail_room_arr']))
						{ 
							?><div class="dashboard-chart-loading"></div><?php
						}else{
							?><div class="l10"><?php __('lblNoData');?></div><?php
						} 
						?>
					</div>
					<div id="chart-2" style="height: 450px"></div>
				</div>
				<div id="charts-2">
					<?php
					if(!isset($tpl['avail_room_arr']))
					{ 
						?><div class="l10"><?php __('lblNoData');?></div><?php
					} 
					?>
				</div>	
			</div>
		</div>
	</div>
	<?php
}
?>
<script type="text/javascript">
var myLabel = myLabel || {};
myLabel.room_avail = "<?php echo isset($tpl['avail_room_arr']) ? 'true' : 'false'; ?>";
</script>