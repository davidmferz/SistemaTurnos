<?php
if (!isset($tpl['arr']) || empty($tpl['arr']))
{
	//$titles = __('error_titles', true);
	//$bodies = __('error_bodies', true);
	//pjUtil::printNotice(@$titles['AR20'], @$bodies['AR20']);
} else {
	if (isset($_GET['month']) && isset($_GET['year']) && isset($_GET['day']))
	{
		$time = mktime(0, 0, 0, (int) $_GET['month'], (int) $_GET['day'], (int) $_GET['year']);
		switch ($_GET['direction'])
		{
			case 'next':
				$time = strtotime("+30 day", $time);
				break;
			case 'prev':
				$time = strtotime("-30 day", $time);
				break;
		}
	} else {
		$time = $tpl['time'];
	}
	list($year, $month, $day, $numOfDaysInCurrentMonth) = explode("-", date("Y-n-j-t", $time));
	# 1st month
	$remainDaysInCurrentMonth = $numOfDaysInCurrentMonth - $day + 1;
	if ($remainDaysInCurrentMonth > 30)
	{
		$remainDaysInCurrentMonth = 30;
	}
	# 2nd month
	$numOfDayInNextMonth = date("t", mktime(0, 0, 0, $month + 1, 1, $year));
	$remainDaysInNextMonth = $numOfDayInNextMonth >= 30 - $remainDaysInCurrentMonth ? 30 - $remainDaysInCurrentMonth : $numOfDayInNextMonth;
	
	# 3rd month (Only if current date is 31 Jan, in non leap year)
	$remainDaysInSubsequentMonth = 0;
	if (30 > $remainDaysInCurrentMonth + $remainDaysInNextMonth)
	{
		$remainDaysInSubsequentMonth = 30 - $remainDaysInCurrentMonth - $remainDaysInNextMonth;
	}
	?>
	<div class="float_right overflow b10">
		<a href="#" class="prev30" rel="<?php echo $year; ?>-<?php echo $month; ?>-<?php echo $day; ?>|prev"><?php __('booking_prev30'); ?></a> |
		<a href="#" class="next30" rel="<?php echo $year; ?>-<?php echo $month; ?>-<?php echo $day; ?>|next"><?php __('booking_next30'); ?></a>
	</div>
	
	<div class="cal-container">
		<div class="cal-calendars">
			<div class="cal-title" style="height: 64px"></div>
			<?php
			foreach ($tpl['arr'] as $k => $room)
			{
				?><div class="cal-title"><?php
				if ($controller->isAdmin())
				{
					?><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminRooms&amp;action=pjActionUpdate&amp;id=<?php echo $room['id']; ?>"><?php echo pjSanitize::html($room['name']); ?></a><?php
				} else {
					echo pjSanitize::html($room['name']);
				}
				?></div><?php
			}
			?>
		</div>
		<div class="cal-dates">
			<div class="cal-scroll">
			<?php
			$haystack = array(
				'calendarStatus1' => 'abCalendarDate',
				'calendarStatus2' => 'abCalendarReserved',
				'calendarStatus3' => 'abCalendarPending',
				'calendarStatus_1_2' => 'abCalendarReservedNightsStart',
				'calendarStatus_1_3' => 'abCalendarPendingNightsStart',
				'calendarStatus_2_1' => 'abCalendarReservedNightsEnd',
				'calendarStatus_2_3' => 'abCalendarNightsReservedPending',
				'calendarStatus_3_1' => 'abCalendarPendingNightsEnd',
				'calendarStatus_3_2' => 'abCalendarNightsPendingReserved'
			);
			
			$months = __('months', true);
			foreach ($tpl['arr'] as $k => $room)
			{
				if ($k == 0)
				{
					?>
					<div class="cal-head">
						<div class="cal-head-row">
							<span style="width: <?php echo 44 * $remainDaysInCurrentMonth - 3; ?>px"><?php echo $months[$month]; ?> <?php echo $year; ?></span>
							<?php
							if ($remainDaysInNextMonth > 0)
							{
								?><span style="width: <?php echo 44 * $remainDaysInNextMonth - 3; ?>px"><?php echo $months[$month + 1 < 13 ? $month + 1 : 1]; ?> <?php echo $month + 1 < 13 ? $year : $year + 1; ?></span><?php
							}
							if ($remainDaysInSubsequentMonth > 0)
							{
								?><span style="width: <?php echo 44 * $remainDaysInSubsequentMonth - 3; ?>px"><?php echo $months[$month + 2 < 13 ? $month + 2 : 1]; ?> <?php echo $month + 2 < 13 ? $year : $year + 1; ?></span><?php
							}
							?>
						</div>
						<div class="cal-head-row">
						<?php
						# Current month
						foreach (range(1, $remainDaysInCurrentMonth) as $i)
						{
							$timestamp = mktime(0, 0, 0, $month, $i + $day - 1, $year);
							$suffix = date("S", $timestamp);
							$iso_date = date("Y-m-d", $timestamp);
							?><a href="#" class="cal-date" data-iso_date="<?php echo $iso_date; ?>"><?php echo ($i + $day - 1) . $suffix; ?></a><?php
						}
						# Next month
						if ($remainDaysInNextMonth > 0)
						{
							foreach (range(1, $remainDaysInNextMonth) as $i)
							{
								$timestamp = mktime(0, 0, 0, $month + 1, $i, $year);
		    	    			$suffix = date("S", $timestamp);
		    	    			$iso_date = date("Y-m-d", $timestamp);
								?><a href="#" class="cal-date" data-iso_date="<?php echo $iso_date; ?>"><?php echo $i . $suffix; ?></a><?php
							}
						}
						# Subsequent month
						if ($remainDaysInSubsequentMonth > 0)
						{
							foreach (range(1, $remainDaysInSubsequentMonth) as $i)
							{
								$timestamp = mktime(0, 0, 0, $month + 2, $i, $year);
		    	    			$suffix = date("S", $timestamp);
		    	    			$iso_date = date("Y-m-d", $timestamp);
								?><a href="#" class="cal-date" data-iso_date="<?php echo $iso_date; ?>"><?php echo $i . $suffix; ?></a><?php
							}
						}
						?>
						</div>
					</div>
					<?php
				}
				?>
				<div class="cal-program cal-id-<?php echo $room['id']; ?>">
				<?php
				$date_arr = $room['date_arr'];
				/*if ((int) $room['o_bookings_per_day'] === 1)
				{
					$date_arr = pjUtil::fixSingleDay($date_arr);
				}*/
				
				# Current month
				foreach (range(1, $remainDaysInCurrentMonth) as $d)
				{
					$timestamp = mktime(0, 0, 0, $month, $d + $day - 1, $year);
	    	    	$iso_date = date("Y-m-d", $timestamp);
	    	    	
	    	    	$booked = isset($date_arr[$timestamp]['confirmed']) ? (int) $date_arr[$timestamp]['confirmed'] : 0;
	    	    	$available = $room['cnt'] - $booked;
	    	    	$available = $available < 0 ? 0 : $available;
	    	    	$class = $available == 0 ? ' cal-full' : NULL;
					?><a href="#" class="cal-room<?php echo $class; ?>" data-room_id="<?php echo $room['id']; ?>" data-iso_date="<?php echo $iso_date; ?>"><?php printf("%u/%u", $booked, $available); ?></a><?php
				}
				# Next month
				if ($remainDaysInNextMonth > 0)
				{
					foreach (range(1, $remainDaysInNextMonth) as $d)
					{
						$timestamp = mktime(0, 0, 0, $month + 1, $d, $year);
		    	    	$iso_date = date("Y-m-d", $timestamp);
		    	    	
						$booked = isset($date_arr[$timestamp]['confirmed']) ? (int) $date_arr[$timestamp]['confirmed'] : 0;
	    	    		$available = $room['cnt'] - $booked;
	    	    		$available = $available < 0 ? 0 : $available;
	    	    		$class = $available == 0 ? ' cal-full' : NULL;
						?><a href="#" class="cal-room<?php echo $class; ?>" data-room_id="<?php echo $room['id']; ?>" data-iso_date="<?php echo $iso_date; ?>"><?php printf("%u/%u", $booked, $available); ?></a><?php
					}
				}
				# Subsequent month
				if ($remainDaysInSubsequentMonth > 0)
				{
					foreach (range(1, $remainDaysInSubsequentMonth) as $d)
					{
						$timestamp = mktime(0, 0, 0, $month + 2, $d, $year);
		    	    	$iso_date = date("Y-m-d", $timestamp);
		    	    	
		    	    	$booked = isset($date_arr[$timestamp]['confirmed']) ? (int) $date_arr[$timestamp]['confirmed'] : 0;
	    	    		$available = $room['cnt'] - $booked;
	    	    		$available = $available < 0 ? 0 : $available;
	    	    		$class = $available == 0 ? ' cal-full' : NULL;
						?><a href="#" class="cal-room<?php echo $class; ?>" data-room_id="<?php echo $room['id']; ?>" data-iso_date="<?php echo $iso_date; ?>"><?php printf("%u/%u", $booked, $available); ?></a><?php
					}
				}
				?>
				</div>
				<?php
			}
			?>
			</div>
		</div>
	</div>
	<?php
}
?>