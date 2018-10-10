<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjAdminBookings extends pjAdmin
{
	private function _calendar($selected_date, $room_id=null, $_week_start_date=null)
	{
		$pjBookingModel = pjBookingModel::factory();
		$pjRoomModel = pjRoomModel::factory();
		$pjRoomNumberModel = pjRoomNumberModel::factory();
		$pjBookingRoomModel = pjBookingRoomModel::factory();
	
		$day = date("w", strtotime($selected_date));
	
		if ($day > 1)
		{
			$days = $day - 1;
			$week_start_date = date("Y-m-d", strtotime($selected_date . "-$days days"));
			$week_end_date = date("Y-m-d", strtotime($selected_date . "-$days days") + 24 * 60 * 60 * 6);
		} else {
			$week_start_date = $selected_date;
			$week_end_date = date("Y-m-d", strtotime($week_start_date) + 24 * 60 * 60 * 6);
		}
		if ($_week_start_date != null)
		{
			$week_start_date = $_week_start_date;
			$week_end_date = date("Y-m-d", strtotime($week_start_date) + 24 * 60 * 60 * 6);
		}
	
		$month_arr = __('months', true);
		$start_month = date('n', strtotime($week_start_date));
		$end_month = date('n', strtotime($week_end_date));
		$month_label = $month_arr[$start_month] . ', ' . date('Y', strtotime($week_start_date));
		if($start_month != $end_month)
		{
			$month_label = $month_arr[$start_month] . ', ' . date('Y', strtotime($week_start_date)) . ' - ' . $month_arr[$end_month] . ', ' . date('Y', strtotime($week_end_date));
		}
	
		if ($room_id != null)
		{
			$pjRoomNumberModel->where('t1.room_id', $room_id);
		}
		$room_number_arr = $pjRoomNumberModel
			->select('t1.*, t3.content AS `type`')
			->join('pjRoom', 't2.id=t1.room_id', 'inner')
			->join('pjMultiLang', sprintf("t3.model='pjRoom' AND t3.foreign_id=t2.id AND t3.field='name' AND t3.locale='%u'", $this->getLocaleId()), 'left outer')
			->orderBy('`type` ASC, t1.id ASC')
			->findAll()
			->getData();

		$_arr = $pjBookingModel
			->select(sprintf("t1.*, 
				(SELECT GROUP_CONCAT(CONCAT_WS('~:~', TRN.id, TRN.number) SEPARATOR '~::~') 
					FROM `%s` AS `TBR`
					INNER JOIN `%s` AS `TRN` ON TBR.room_number_id=TRN.id 
					WHERE TBR.booking_id=t1.id
				) AS `rooms`		
			", $pjBookingRoomModel->getTable(), $pjRoomNumberModel->getTable()))
			->whereIn('t1.status', array('confirmed', 'pending'))
			->where('t1.date_from <=', $week_end_date)
			->where('t1.date_to >=', $week_start_date)
			->orderBy('t1.date_from ASC')
			->findAll()
			->getData();	
	
		$other_rooms = array();
		foreach ($_arr as $v)
		{
			if (!empty($v['rooms']))
			{
				$other_rooms[$v['id']] =  explode("~::~", $v['rooms']);
			}
		}

		$pjBookingModel->reset();
		if ($room_id != null)
		{
			$pjBookingModel
				->select('t1.id, t1.uuid, t1.date_from, t1.date_to, t1.c_fname, t1.c_lname, t1.c_email, t1.c_phone, t1.status, DATEDIFF(`date_to`, `date_from`) AS `nights`, t2.room_number_id, t2.adults, t2.children')
				->join('pjBookingRoom', sprintf("t2.booking_id=t1.id AND t2.room_id='%u'", $room_id), 'inner');
		} else {
			$pjBookingModel
				->select('t1.id, t1.uuid, t1.date_from, t1.date_to, t1.c_fname, t1.c_lname, t1.c_email, t1.c_phone, t1.status, DATEDIFF(`date_to`, `date_from`) AS `nights`, t2.room_number_id, t2.adults, t2.children')
				->join('pjBookingRoom', 't2.booking_id=t1.id', 'inner');
		}
		$booking_arr = $pjBookingModel
			->whereIn('t1.status', array('confirmed', 'pending'))
			->where('t1.date_from <=', $week_end_date)
			->where('t1.date_to >=', $week_start_date)
			->orderBy('t1.date_from ASC')
			->findAll()
			->getData();

		$br_arr = array();
		foreach ($booking_arr as $v)
		{
			$br_arr[$v['room_number_id']][] = $v;
		}
		$rows = array();
		
		# Restriction
		# Add restrictions as bookings. Then in presentation layer, just check for specific index (restriction_type)
		$rr_arr = pjRestrictionRoomModel::factory()
			->select('t1.room_number_id, t2.date_from, t2.date_to, t2.restriction_type')
			->join('pjRestriction', 't2.id=t1.restriction_id', 'inner')
			->where('t2.date_from <=', $week_end_date)
			->where('t2.date_to >=', $week_start_date)
			->orderBy('t2.date_from ASC')
			->findAll()
			->getData();

		$tmp_week_start_date = strtotime($week_start_date);
		$tmp_week_end_date = strtotime($week_end_date);
		foreach ($rr_arr as $item)
		{
			if (!isset($br_arr[$item['room_number_id']]))
			{
				$br_arr[$item['room_number_id']] = array();
			}
			
			if (strtotime($item['date_from']) < $tmp_week_start_date)
			{
				$item['date_from'] = date("Y-m-d", $tmp_week_start_date - 86400);
			}
			if (strtotime($item['date_to']) > $tmp_week_end_date)
			{
				$item['date_to'] = date("Y-m-d", $tmp_week_end_date + 86400);
			}
			$br_arr[$item['room_number_id']][] = $item;
			
			/*Re-order the bookings in each row in case retriction goes before actual bookings*/
			$tmp_arr = $br_arr[$item['room_number_id']];
			pjUtil::sortMultiDimensionsArray($tmp_arr, 'date_from');
			$key = 0;
			foreach($tmp_arr as $v)
			{
				$br_arr[$item['room_number_id']][$key] = $v;
				$key++;
			}
		}
		
		$nightMode = $this->option_arr['o_price_based_on'] == "nights";
		
		# Restriction
		$wsd = strtotime($week_start_date);
		
		foreach ($br_arr as $k => $v)
		{
			$cols = 0;
			$start_timestamp = $wsd;
				
			foreach ($v as $index => $booking)
			{
				$from_timestamp = strtotime($booking['date_from']);
				$to_timestamp = strtotime($booking['date_to']);
				$nights = ceil(abs($to_timestamp - $from_timestamp) / 86400);
				if($nightMode == false)
				{
					$nights = $nights + 1;
				}
				$nights = $nights > 7 ? 7 : $nights; # Fix
	
				if ($index == 0)
				{
					if ($from_timestamp < $start_timestamp)
					{
						$from_timestamp = $start_timestamp;
						$nights = ceil(abs($to_timestamp - $from_timestamp) / 86400);
						if($nightMode == false)
						{
							$nights = $nights + 1;
						}
						$nights = $nights > 7 ? 7 : $nights; # Fix
						
						if($nightMode == true)
						{
							$rows[$k][] = array('colspan' => $nights*2+1, 'content' => $booking);
							$cols += $nights*2 + 1;
						}else{
							$rows[$k][] = array('colspan' => $nights*2, 'content' => $booking);
							$cols += $nights*2;
						}
					} else if($from_timestamp == $start_timestamp) {
						$from_timestamp = $start_timestamp;
						$nights = ceil(abs($to_timestamp - $from_timestamp) / 86400);
						$nights = $nights > 7 ? 7 : $nights; # Fix
						if($nightMode == true)
						{
							$rows[$k][] = array('colspan' => 1, 'content' => array());
							$cols += 1;
						}else{
							$nights = $nights + 1;
						}
						if ($cols + $nights*2 <= 14)
						{
							$rows[$k][] = array('colspan' => $nights*2, 'content' => $booking);
							$cols += $nights*2;
						} else {
							$rows[$k][] = array('colspan' => 14 - $cols, 'content' => $booking);
							$cols += 14 - $cols;
						}
					} else {
						$days = ceil(abs($from_timestamp - $start_timestamp) / 86400);
						for ($i = 1; $i <= $days; $i++)
						{
							$rows[$k][] = array('colspan' => 2, 'content' => array());
							$cols += 2;
						}
						if ($cols + $nights*2 + 1 <= 14)
						{
							if($nightMode == true)
							{
								$rows[$k][] = array('colspan' => 1, 'content' => array());
								$rows[$k][] = array('colspan' => $nights*2, 'content' => $booking);
								$cols += $nights*2 + 1;
							}else{
								$rows[$k][] = array('colspan' => $nights*2, 'content' => $booking);
								$cols += $nights*2;
							}
						} else {
							$rows[$k][] = array('colspan' => 1, 'content' => array());
							$rows[$k][] = array('colspan' => 14 - $cols-1, 'content' => $booking);
							$cols += 14 - $cols;
						}
					}
				} else if ($index > 0) {
					$previous_booking = $v[$index - 1];
					$start_timestamp = strtotime($previous_booking['date_to']);
					
					if ($from_timestamp == $start_timestamp)
					{
						$from_timestamp = $start_timestamp;
						$nights = ceil(abs($to_timestamp - $from_timestamp) / 86400);
						$nights = $nights > 7 ? 7 : $nights; # Fix
						
						if ($cols + $nights*2 <= 14)
						{
							$rows[$k][] = array('colspan' => $nights*2, 'content' => $booking);
							$cols += $nights*2;
						} else {
							$rows[$k][] = array('colspan' => 14 - $cols, 'content' => $booking);
							$cols += 14 - $cols;
						}
					} else {
						$days = ceil(abs($from_timestamp - $start_timestamp) / 86400);
						# Fix
						if($nightMode == true)
						{
							$rows[$k][] = array('colspan' => 1, 'content' => array());
							$cols += 1;
						}
						
						if($nightMode == true)
						{
							$floor = floor($days/2);
						}
						$floor = $days - 1;
						if ($floor > 0)
						{
							foreach (range(1, $floor) as $i)
							{
								$rows[$k][] = array('colspan' => 2, 'content' => array());
								$cols += 2;
							}
						}
						if ($cols + $nights*2 + 1 <= 14)
						{
							if($nightMode == true)
							{
								$rows[$k][] = array('colspan' => 1, 'content' => array());
								$rows[$k][] = array('colspan' => $nights*2, 'content' => $booking);
								$cols += $nights*2 + 1;
							}else{
								$rows[$k][] = array('colspan' => $nights*2, 'content' => $booking);
								$cols += $nights*2;
							}
						} else {
							if($nightMode == true)
							{
								$rows[$k][] = array('colspan' => 1, 'content' => array());
								$rows[$k][] = array('colspan' => 14 - $cols - 1, 'content' => $booking);
							}else{
								$rows[$k][] = array('colspan' => 14 - $cols, 'content' => $booking);
							}
							$cols += 14 - $cols;
						}
						# End fix
					}
				}
			}
			$left_columns = 14 - $cols;
			if ($left_columns == 1)
			{
				$rows[$k][] = array('colspan' => 1, 'content' => array());
			} else if ($left_columns > 1) {
				if($nightMode == true)
				{
					$rows[$k][] = array('colspan' => 1, 'content' => array());
				}
				for($i = 1 ; $i <= floor($left_columns/2); $i++)
				{
					$rows[$k][] = array('colspan' => 2, 'content' => array());
				}
			}
		}
		$room_arr = $pjRoomModel
			->reset()
			->select('t1.*, t2.content AS `type`')
			->join('pjMultiLang', sprintf("t2.model='pjRoom' AND t2.foreign_id=t1.id AND t2.locale='%u' AND t2.field='name'", $this->getLocaleId()), 'left outer')
			->orderBy('`type` ASC')
			->findAll()
			->getData();
	
		return compact('week_start_date', 'week_end_date', 'month_label', 'room_number_arr', 'rows', 'other_rooms', 'room_arr');
	}
	
	public function pjActionCalendar()
	{
		$this->checkLogin();
		
		if ($this->isAdmin() || $this->isEditor())
		{
			$this->notConfirmed();
			
			$selected_date = date('Y-m-d');
			if (isset($_GET['selected_date']) && !empty($_GET['selected_date']))
			{
				if (isset($_GET['format']) && !empty($_GET['format']))
				{
					$selected_date = pjUtil::formatDate($_GET['selected_date'], $_GET['format']);
				} else {
					$selected_date = pjUtil::formatDate($_GET['selected_date'], $this->option_arr['o_date_format']);
				}
				$this->set('selected_date', pjUtil::formatDate($selected_date, 'Y-m-d', $this->option_arr['o_date_format']));
			}
			$params = $this->_calendar($selected_date, null, null);

			$this->set('week_start_date', $params['week_start_date']);
			$this->set('week_end_date', $params['week_end_date']);
			$this->set('month_label', $params['month_label']);
			$this->set('room_number_arr', $params['room_number_arr']);
			$this->set('rows', $params['rows']);
			$this->set('other_rooms', $params['other_rooms']);
			$this->set('room_arr', $params['room_arr']);

			$this->appendJs('jquery.tipsy.js', PJ_THIRD_PARTY_PATH . 'tipsy/');
			$this->appendCss('tipsy.css');
			$this->appendJs('pjAdminBookings.js');
		} else {
			$this->set('status', 2);
		}
	}
	
	public function pjActionGetCalendar()
	{
		$this->setAjax(true);
	
		if ($this->isXHR() && $this->isLoged())
		{
			$selected_date = null;
			$room_id = null;
			$week_start_date = null;
			if (isset($_GET['selected_date']) && !empty($_GET['selected_date']))
			{
				$selected_date = pjUtil::formatDate($_GET['selected_date'], $this->option_arr['o_date_format']);
			} else {
				$selected_date = date('Y-m-d');
			}
			if (isset($_GET['week_start_date']) && !empty($_GET['week_start_date']))
			{
				$week_start_date = $_GET['week_start_date'];
			}
			if (isset($_GET['room_id']) && !empty($_GET['room_id']))
			{
				$room_id = intval($_GET['room_id']);
			}
			$params = $this->_calendar($selected_date, $room_id, $week_start_date);
			
			$this->set('week_start_date', $params['week_start_date']);
			$this->set('week_end_date', $params['week_end_date']);
			$this->set('month_label', $params['month_label']);
			$this->set('room_number_arr', $params['room_number_arr']);
			$this->set('rows', $params['rows']);
			$this->set('other_rooms', $params['other_rooms']);
			$this->set('room_arr', $params['room_arr']);
		}
	}
	
	public function pjActionPrintCalendar()
	{
		$this->checkLogin();
		
		if ($this->isAdmin())
		{
			$this->setLayout('pjActionPrint');
			
			$selected_date = null;
			$room_id = null;
			$week_start_date = null;
			if (isset($_GET['selected_date']) && !empty($_GET['selected_date']))
			{
				$selected_date = pjUtil::formatDate($_GET['selected_date'], $this->option_arr['o_date_format']);
			} else {
				$selected_date = date('Y-m-d');
			}
			if (isset($_GET['week_start_date']) && !empty($_GET['week_start_date']))
			{
				$week_start_date = $_GET['week_start_date'];
			}
			if (isset($_GET['room_id']) && !empty($_GET['room_id']))
			{
				$room_id = intval($_GET['room_id']);
					
				$room_arr = pjRoomModel::factory()->find($room_id)->getData();
				if (empty($room_arr))
				{
					pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminBookings&action=pjActionPrintCalendar&err=TODO");
				}
				$this->set('room_arr', $room_arr);
			}
			$params = $this->_calendar($selected_date, $room_id, $week_start_date);
				
			$this->set('week_start_date', $params['week_start_date']);
			$this->set('week_end_date', $params['week_end_date']);
			$this->set('month_label', $params['month_label']);
			$this->set('room_number_arr', $params['room_number_arr']);
			$this->set('rows', $params['rows']);
			$this->set('other_rooms', $params['other_rooms']);
			$this->set('rooms_arr', $params['room_arr']);
		} else {
			$this->set('status', 2);
		}
	}
	
	public function pjActionAddBookingRoom()
	{
		$this->setAjax(true);
		
		if ($this->isXHR() && $this->isLoged())
		{
			$pjBookingRoomTempModel = pjBookingRoomTempModel::factory();
			
			if (isset($_POST['room_add']))
			{
				$date_from = pjUtil::formatDate($_POST['date_from'], $this->option_arr['o_date_format']);
				$date_to = pjUtil::formatDate($_POST['date_to'], $this->option_arr['o_date_format']);
				
				$data = array();
				$data['price'] = $this->getRoomPrice($_POST['room_id'], $date_from, $date_to, ($this->option_arr['o_price_based_on'] == 'nights'), $_POST['adults'], $_POST['children']);
				$data = array_merge($_POST, $data);
				
				$insert_id = $pjBookingRoomTempModel->setAttributes($data)->insert()->getInsertId();
				if ($insert_id !== false && (int) $insert_id > 0)
				{
					pjAppController::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => ''));
				}
				pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => ''));
			}
			
			$nightMode = $this->option_arr['o_price_based_on'] == "nights";
			$condition1 = $condition2 = $condition3 = NULL;
			if (isset($_GET['booking_id']) && (int) $_GET['booking_id'] > 0)
			{
				$condition1 = sprintf(" AND `booking_id` = '%u'", (int) $_GET['booking_id']);
				$condition3 = sprintf(" AND br.booking_id != '%u'", (int) $_GET['booking_id']);
			} elseif (isset($_GET['hash']) && !empty($_GET['hash'])) {
				$condition1 = sprintf(" AND `hash` = '%s'", $pjBookingRoomTempModel->escapeStr($_GET['hash']));
				$condition3 = " AND `booking_id` IS NOT NULL";
			}
			
			$pjRoomModel = pjRoomModel::factory();
			
			$isDateCorrect = isset($_GET['date_from']) && isset($_GET['date_to']) && !empty($_GET['date_from']) && !empty($_GET['date_to']);
			if ($isDateCorrect)
			{
				$date_from = pjUtil::formatDate($_GET['date_from'], $this->option_arr['o_date_format']);
				$date_to = pjUtil::formatDate($_GET['date_to'], $this->option_arr['o_date_format']);
				
				if ($nightMode)
				{
					$condition2 = sprintf(" AND b.date_from < '%s' AND b.date_to > '%s' ", $date_to, $date_from);
				} else {
					$condition2 = sprintf(" AND b.date_from <= '%s' AND b.date_to >= '%s' ", $date_to, $date_from);
				}
			} else {
				$pjRoomModel->where('t1.id', -99);
				$this->set('dates_not_set', 1);
			}
			
			$room_arr = $pjRoomModel
				->select(sprintf("t1.*, t2.content AS name,
					(SELECT COUNT(*)
						FROM `%6\$s` AS `br`
						INNER JOIN `%2\$s` AS `b` ON b.id = br.booking_id
							AND b.status = 'confirmed'
							%4\$s
						WHERE br.room_id = t1.id
						%5\$s
						LIMIT 1) AS `other_booking_cnt`,
					(SELECT COUNT(*)
						FROM `%1\$s`
						WHERE `room_id` = t1.id
						%3\$s
						LIMIT 1) AS `current_booking_cnt`",
					$pjBookingRoomTempModel->getTable(), pjBookingModel::factory()->getTable(), $condition1, $condition2, $condition3, pjBookingRoomModel::factory()->getTable()))
				->join('pjMultiLang', "t2.model='pjRoom' AND t2.foreign_id=t1.id AND t2.field='name' AND t2.locale='".$this->getLocaleId()."'", 'left outer')
				->where('t1.calendar_id', $this->getForeignId())
				->orderBy('`name` ASC')
				->findAll()
				->getData();
			# --
			$tmp = pjBookingRoomModel::factory()
				->select('t1.*, t2.date_from, t2.date_to')
				->join('pjBooking', sprintf("t2.id=t1.booking_id AND (t2.status = 'confirmed' OR (t2.status = 'pending' AND t2.created >= SUBTIME(NOW(), '01:00:00'))) AND t2.date_from %3\$s '%2\$s' AND t2.date_to %4\$s '%1\$s'",
					$pjRoomModel->escapeStr($date_from),
					$pjRoomModel->escapeStr($date_to),
					$nightMode ? "<" : "<=",
					$nightMode ? ">" : ">="), 'inner')
				->findAll()
				->getData();
				
			$sum = array();
			$init = array();
			$dt_from = strtotime($date_from);
			$dt_to = strtotime($date_to);
			for ($i = $dt_from; $i <= $dt_to; $i += 86400)
			{
				$init[date("Y-m-d", $i)] = 0;
			}
				
			foreach ($tmp as $value)
			{
				if (!isset($sum[$value["room_id"]]))
				{
					$sum[$value["room_id"]] = $init;
				}
				$from = strtotime($value["date_from"]);
				$to = strtotime($value["date_to"]);
				for ($z = $from; $z <= $to; $z += 86400)
				{
					if (($z >= $dt_from && $z < $dt_to) === FALSE)
					{
						continue;
					}
					$iso = date("Y-m-d", $z);
					if ($z == $to && $nightMode)
					{
						continue;
					}
					$sum[$value["room_id"]][$iso] += 1;
				}
			}
			foreach($room_arr as $k => $room)
			{
				$room_arr[$k]['max_bookings'] = isset($sum[$room['id']]) && !empty($sum[$room['id']]) ? ceil(max($sum[$room['id']])) : 0;
			}
				
			$this->set('room_arr', $room_arr);
			$this->set('cnt_rooms', $pjRoomModel->reset()->findCount()->getData());
			
			if (isset($_GET['room_id']) && (int) $_GET['room_id'] > 0 && $isDateCorrect)
			{
				$this->set('room_number_arr', $this->getRoomNumbers($_GET['room_id'], $_GET['date_from'], $_GET['date_to'], $_GET['booking_id'], $_GET['hash']));
			}
		}
	}
	
	public function pjActionAvailability()
	{
		$this->checkLogin();
		
		if (!$this->isAdmin() && !$this->isEditor())
		{
			$this->set('status', 2);
			return;
		}
		$time = time();
		$next_ts = strtotime(date("Y-m-1",$time) . " +1 month");
		$num_days = ($next_ts - $time) / (24*60*60);
		if($num_days <= 3)
		{
			$time = $time - ((3-$num_days)*24*60*60);
		}
		
		list($year, $month, $day) = explode("-", date("Y-n-j", $time));
		$arr = $this->pjActionGetAvail($year, $month, $day);
		$this->set('arr', $arr);
		$this->set('time', $time);
		
		$this->appendJs('jquery.datagrid.js', PJ_FRAMEWORK_LIBS_PATH . 'pj/js/');
		$this->appendJs('pjAdminBookings.js');
		$this->appendJs('index.php?controller=pjAdmin&action=pjActionMessages', PJ_INSTALL_URL, true);
	}
	
	public function pjActionGetAvailability()
	{
		$this->setAjax(true);
	
		if ($this->isXHR() && $this->isLoged())
		{
			$this->set('arr', $this->pjActionGetAvail($_GET['year'], $_GET['month'], $_GET['day'], $_GET['direction']));
		}
	}
	
	private function pjActionGetAvail($year, $month, $day, $direction=NULL)
	{
		$time = mktime(0, 0, 0, (int) $month, (int) $day, (int) $year);
		if (!is_null($direction))
		{
			switch ($direction)
			{
				case 'next':
					$time = strtotime("+30 day", $time);
					break;
				case 'prev':
					$time = strtotime("-30 day", $time);
					break;
			}
		}
		
		$pjRoomModel = pjRoomModel::factory()
			->select("t1.*, t2.content AS `name`")
			->join('pjMultiLang', "t2.model='pjRoom' AND t2.foreign_id=t1.id AND t2.field='name' AND t2.locale='".$this->getLocaleId()."'", 'left outer');
		$pjBookingRoomModel = pjBookingRoomModel::factory();

		$arr = $pjRoomModel->orderBy('`name` ASC')->groupBy('t1.id')->findAll()->getData();
		foreach ($arr as $k => $room)
		{
			$arr[$k]['date_arr'] = $pjBookingRoomModel->getInfo(
				$room['id'],
				date("Y-m-d", $time),
				date("Y-m-d", strtotime("+30 day", $time)),
				$this->option_arr
			);
		}

		return $arr;
	}
	
	public function pjActionConfirmation()
	{
		$this->setAjax(true);
	
		if ($this->isXHR())
		{
			if (isset($_POST['send_confirm']) && !empty($_POST['to']) && !empty($_POST['from']) &&
				!empty($_POST['subject']) && !empty($_POST['message']))
			{
				$Email = new pjEmail();
				$Email->setContentType('text/html');
				if ($this->option_arr['o_send_email'] == 'smtp')
				{
					$Email
						->setTransport('smtp')
						->setSmtpHost($this->option_arr['o_smtp_host'])
						->setSmtpPort($this->option_arr['o_smtp_port'])
						->setSmtpUser($this->option_arr['o_smtp_user'])
						->setSmtpPass($this->option_arr['o_smtp_pass'])
						->setSender($this->option_arr['o_smtp_user']);
				}
				
				$subject = $_POST['subject'];
				$message = $_POST['message'];
				if (get_magic_quotes_gpc())
		    	{
		    		$subject = stripslashes($_POST['subject']);
					$message = stripslashes($_POST['message']);
		    	}
				
				$r = $Email
					->setTo($_POST['to'])
					->setFrom($_POST['from'])
					->setSubject($subject)
					->send($message);
					
				if ($r)
				{
					pjAppController::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => 'Email has been sent.'));
				}
				pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => 'Email failed to send.'));
			}
			
			if (isset($_GET['booking_id']) && (int) $_GET['booking_id'] > 0)
			{
				$booking_arr = pjBookingModel::factory()
					->select("t1.*, t2.content AS `country`, t3.content AS `confirm_subject_client`, t4.content AS `confirm_tokens_client`, t6.email AS `admin_email`,
								AES_DECRYPT(t1.cc_type, '".PJ_SALT."') AS `cc_type`,
								AES_DECRYPT(t1.cc_num, '".PJ_SALT."') AS `cc_num`,
								AES_DECRYPT(t1.cc_exp_month, '".PJ_SALT."') AS `cc_exp_month`,
								AES_DECRYPT(t1.cc_exp_year, '".PJ_SALT."') AS `cc_exp_year`,
								AES_DECRYPT(t1.cc_code, '".PJ_SALT."') AS `cc_code`")
					->join('pjMultiLang', "t2.model='pjCountry' AND t2.foreign_id=t1.c_country AND t2.locale=t1.locale_id AND t2.field='name'", 'left outer')
					->join('pjMultiLang', "t3.model='pjCalendar' AND t3.foreign_id=t1.calendar_id AND t3.field='confirm_subject_client' AND t3.locale=t1.locale_id", 'left outer')
					->join('pjMultiLang', "t4.model='pjCalendar' AND t4.foreign_id=t1.calendar_id AND t4.field='confirm_tokens_client' AND t4.locale=t1.locale_id", 'left outer')
					->join('pjCalendar', 't5.id=t1.calendar_id', 'left outer')
					->join('pjUser', 't6.id=t5.user_id', 'left outer')
					->find($_GET['booking_id'])
					->getData();
				
				if (!empty($booking_arr))
				{
					$booking_arr['extras'] = pjBookingExtraModel::factory()
						->select('t1.*, t2.content AS `name`')
						->join('pjMultiLang', "t2.model='pjExtra' AND t2.foreign_id=t1.extra_id AND t2.field='name' AND t2.locale='".$booking_arr['locale_id']."'", 'left join')
						->where('t1.booking_id', $booking_arr['id'])
						->findAll()
						->getData();
						
					$booking_arr['rooms'] = pjBookingRoomModel::factory()
						->select('t1.*, COUNT(t1.id) AS `cnt`, t2.content AS `name`')
						->join('pjMultiLang', "t2.model='pjRoom' AND t2.foreign_id=t1.room_id AND t2.field='name' AND t2.locale='".$booking_arr['locale_id']."'", 'left join')
						->where('t1.booking_id', $booking_arr['id'])
						->groupBy('t1.booking_id, t1.room_id')
						->findAll()
						->getData();
				}
					
				$tokens = pjAppController::getTokens($booking_arr, $this->option_arr);
				
				$subject_client = str_replace($tokens['search'], $tokens['replace'], $booking_arr['confirm_subject_client']);
				$message_client = str_replace($tokens['search'], $tokens['replace'], $booking_arr['confirm_tokens_client']);
				
				$this->set('arr', array(
					'to' => $booking_arr['c_email'],
					'from' => !empty($booking_arr['admin_email']) ? $booking_arr['admin_email'] : $booking_arr['c_email'],
					'message' => $message_client,
					'subject' => $subject_client
				));
			} else {
				exit;
			}
		}
	}
	
	public function pjActionCreate()
	{
		$this->checkLogin();
		
		if ($this->isAdmin() || $this->isEditor())
		{
			if (isset($_POST['booking_create']))
			{
				$data = array();
				$data['locale_id'] = $this->getLocaleId();
				$data['calendar_id'] = $this->getForeignId();
				$data['date_from'] = pjUtil::formatDate($_POST['date_from'], $this->option_arr['o_date_format']);
				$data['date_to'] = pjUtil::formatDate($_POST['date_to'], $this->option_arr['o_date_format']);
				$data['c_arrival'] = sprintf("%s:%s:00", $_POST['hour'], $_POST['minute']);
				
				# Check room availability
				$pjBookingRoomModel = pjBookingRoomModel::factory();
				$pjBookingRoomTempModel = pjBookingRoomTempModel::factory();
				
				$nightMode = $this->option_arr['o_price_based_on'] == "nights";
				if ($nightMode)
				{
					$condition = sprintf(" AND b.date_from < '%s' AND b.date_to > '%s' ", $data['date_to'], $data['date_from']);
				} else {
					$condition = sprintf(" AND b.date_from <= '%s' AND b.date_to >= '%s' ", $data['date_to'], $data['date_from']);
				}
				
				$tmp_arr = $pjBookingRoomTempModel
					->select(sprintf("t1.room_id, COUNT(*) AS `current_booking_cnt`, t2.cnt AS `total_cnt`,
						(SELECT COUNT(*)
						FROM `%1\$s` AS `br`
						INNER JOIN `%2\$s` AS `b` ON b.id = br.booking_id
							AND b.status = 'confirmed'
							%3\$s
						WHERE br.room_id = t1.room_id
						LIMIT 1) AS `other_booking_cnt`
					", $pjBookingRoomModel->getTable(), pjBookingModel::factory()->getTable(), $condition))
					->join('pjRoom', 't1.room_id=t2.id', 'inner')
					->where('t1.hash', $_POST['hash'])
					->where('t1.booking_id IS NULL')
					->groupBy('t1.room_id, t1.hash')
					->findAll()
					->getData();
				
				# Check room availability
				
				$date_from = $data['date_from'];
				$date_to = $data['date_to'];
				$pjRoomModel = pjRoomModel::factory();
				# --
				$tmp = pjBookingRoomModel::factory()
					->select('t1.*, t2.date_from, t2.date_to')
					->join('pjBooking', sprintf("t2.id=t1.booking_id AND (t2.status = 'confirmed' OR (t2.status = 'pending' AND t2.created >= SUBTIME(NOW(), '01:00:00'))) AND t2.date_from %3\$s '%2\$s' AND t2.date_to %4\$s '%1\$s'",
					$pjRoomModel->escapeStr($date_from),
					$pjRoomModel->escapeStr($date_to),
					$nightMode ? "<" : "<=",
					$nightMode ? ">" : ">="), 'inner')
					->findAll()
					->getData();
					
				$sum = array();
				$init = array();
				$dt_from = strtotime($date_from);
				$dt_to = strtotime($date_to);
				for ($i = $dt_from; $i <= $dt_to; $i += 86400)
				{
					$init[date("Y-m-d", $i)] = 0;
				}
					
				foreach ($tmp as $value)
				{
					if (!isset($sum[$value["room_id"]]))
					{
						$sum[$value["room_id"]] = $init;
					}
					$from = strtotime($value["date_from"]);
					$to = strtotime($value["date_to"]);
					for ($z = $from; $z <= $to; $z += 86400)
					{
						if (($z >= $dt_from && $z < $dt_to) === FALSE)
						{
							continue;
						}
						$iso = date("Y-m-d", $z);
						if ($z == $to && $nightMode)
						{
							continue;
						}
						$sum[$value["room_id"]][$iso] += 1;
					}
				}
				foreach ($tmp_arr as $item)
				{
					$max_bookings = isset($sum[$item['room_id']]) && !empty($sum[$item['room_id']]) ? ceil(max($sum[$item['room_id']])) : 0;
					if ($max_bookings > (int) $item['total_cnt'])
					{
						$pjBookingRoomTempModel
							->reset()
							->where('`hash`', $_POST['hash'])
							->where('`booking_id` IS NULL')
							->eraseAll();
							
						pjUtil::redirect($_SERVER['PHP_SELF'] . '?controller=pjAdminBookings&action=pjActionIndex&err=ABK10');
						break;
					}
				}
				unset($tmp_arr);
				# Check room availability
				
				$id = pjBookingModel::factory(array_merge($_POST, $data))->insert()->getInsertId();
				if ($id !== false && (int) $id > 0)
				{
					$err = 'ABK03';
					if (isset($_POST['i18n']))
					{
						pjMultiLangModel::factory()->saveMultiLang($_POST['i18n'], $id, 'pjBooking');
					}
					
					$statement = sprintf("INSERT INTO `%s` (`booking_id`, `room_id`, `room_number_id`, `adults`, `children`, `price`)
						SELECT :booking_id, `room_id`, `room_number_id`, `adults`, `children`, `price` FROM `%s` WHERE `booking_id` IS NULL AND `hash` = :hash",
						$pjBookingRoomModel->getTable(), $pjBookingRoomTempModel->getTable()
					);
					$pjBookingRoomModel->reset()->prepare($statement)->exec(array('booking_id' => $id, 'hash' => $_POST['hash']));
						
					$pjBookingRoomTempModel
						->reset()
						->where('`hash`', $_POST['hash'])
						->where('`booking_id` IS NULL')
						->eraseAll();
						
					if (isset($_POST['extra_id']) && !empty($_POST['extra_id']))
					{
						$pjBookingExtraModel = pjBookingExtraModel::factory();
						foreach ($_POST['extra_id'] as $extra_id => $extra)
						{
							list(, $price) = explode("|", $extra);
							$pjBookingExtraModel->addBatchRow(array($id, $extra_id, $price));
						}
						$pjBookingExtraModel
							->setBatchFields(array('booking_id', 'extra_id', 'price'))
							->insertBatch();
					}
					
					$invoice_arr = $this->pjActionGenerateInvoice($id);
					
				} else {
					$err = 'ABK04';
				}
				pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminBookings&action=pjActionIndex&err=$err");
			} else {
				$locale_arr = pjLocaleModel::factory()->select('t1.*, t2.file')
					->join('pjLocaleLanguage', 't2.iso=t1.language_iso', 'left outer')
					->where('t2.file IS NOT NULL')
					->orderBy('t1.sort ASC')->findAll()->getData();
						
				$lp_arr = array();
				foreach ($locale_arr as $item)
				{
					$lp_arr[$item['id']."_"] = $item['file']; //Hack for jquery $.extend, to prevent (re)order of numeric keys in object
				}
				
				$this
					->set('lp_arr', $locale_arr)
					->set('locale_str', pjAppController::jsonEncode($lp_arr))
					->set('country_arr', pjCountryModel::factory()
						->select('t1.id, t2.content AS name')
						->join('pjMultiLang', "t2.model='pjCountry' AND t2.foreign_id=t1.id AND t2.field='name' AND t2.locale='".$this->getLocaleId()."'", 'left outer')
						->where('t1.status', 'T')
						->orderBy('`name` ASC')->findAll()->getData()
					)
					->set('extra_arr', pjExtraModel::factory()
						->select('t1.*, t2.content AS name')
						->join('pjMultiLang', "t2.model='pjExtra' AND t2.foreign_id=t1.id AND t2.field='name' AND t2.locale='".$this->getLocaleId()."'", 'left outer')
						->where('t1.calendar_id', $this->getForeignId())
						->orderBy('`name` ASC')
						->findAll()
						->getData()
					)
					->appendJs('jquery.validate.min.js', PJ_THIRD_PARTY_PATH . 'validate/')
					->appendJs('jquery.multilang.js', PJ_FRAMEWORK_LIBS_PATH . 'pj/js/')
					->appendJs('jquery.tipsy.js', PJ_THIRD_PARTY_PATH . 'tipsy/')
					->appendCss('tipsy.css')
					->appendJs('pjAdminBookings.js');
			}
		} else {
			$this->set('status', 2);
		}
	}
	
	public function pjActionGetClients()
	{
		$this->setAjax(true);
		if ($this->isXHR() && $this->isLoged())
		{
			if (isset($_GET['term']) && !empty($_GET['term']))
			{
				$pjBookingModel = pjBookingModel::factory();
				
				$q = $pjBookingModel->escapeStr($_GET['term']);
				$q = str_replace(array('_', '%'), array('\_', '\%'), trim($q));
				$pjBookingModel->where('t1.c_fname LIKE', "%$q%");
				$pjBookingModel->orWhere('t1.c_lname LIKE', "%$q%");
				$pjBookingModel->orWhere('t1.c_phone LIKE', "%$q%");
				$pjBookingModel->orWhere('t1.c_email LIKE', "%$q%");
				
				$arr = $pjBookingModel
					->select("t1.id, t1.c_fname, t1.c_lname, t1.c_phone, t1.c_email")
					->orderBy("c_fname ASC")
					->findAll()->getData();
				
				$_arr = array();
				foreach ($arr as $v)
				{
					$name_arr = array();
					$label_arr = array();
					if(!empty($v['c_fname']))
					{
						$name_arr[] = pjSanitize::html($v['c_fname']);
					}
					if(!empty($v['c_lname']))
					{
						$name_arr[] = pjSanitize::html($v['c_lname']);
					}
					$label_arr[] = join(" ", $name_arr);
					if(!empty($v['c_email']))
					{
						$label_arr[] = pjSanitize::html($v['c_email']);
					}
					if(!empty($v['c_phone']))
					{
						$label_arr[] = pjSanitize::html($v['c_phone']);
					}
					$_arr[] = array('label' => join(" | ", $label_arr), 'value' => $v['id']);
				}
					
				pjAppController::jsonResponse($_arr);
			}
		}
		exit;
	}
	public function pjActionFillClient()
	{
		$this->setAjax(true);
		if ($this->isXHR() && $this->isLoged())
		{
			if (isset($_GET['booking_id']) && (int) $_GET['booking_id'] > 0)
			{
				$arr = pjBookingModel::factory()->find($_GET['booking_id'])->getData();
				 	
				pjAppController::jsonResponse($arr);
			}
		}
		exit;
	}
	public function pjActionDeleteBooking()
	{
		$this->setAjax(true);
	
		if ($this->isXHR() && $this->isLoged())
		{
			if (isset($_GET['id']) && (int) $_GET['id'] > 0)
			{
				$pjBookingModel = pjBookingModel::factory();
				$arr = $pjBookingModel->find($_GET['id'])->getData();
				$uuid = $arr['uuid'];
				if (pjBookingModel::factory()->set('id', $_GET['id'])->erase()->getAffectedRows() == 1)
				{
					pjBookingExtraModel::factory()->where('booking_id', $_GET['id'])->eraseAll();
					pjBookingRoomModel::factory()->where('booking_id', $_GET['id'])->eraseAll();
					pjBookingRoomTempModel::factory()->where('booking_id', $_GET['id'])->eraseAll();
					pjMultiLangModel::factory()->where('model', 'pjBooking')->where('foreign_id', $_GET['id'])->eraseAll();
	
					$pjInvoiceModel = pjInvoiceModel::factory();
					$invoice_id_arr = $pjInvoiceModel->where('order_id', $uuid)->findAll()->getDataPair(null, 'id');
					if(!empty($invoice_id_arr))
					{
						pjInvoiceItemModel::factory()->whereIn('invoice_id', $invoice_id_arr)->eraseAll();
						$pjInvoiceModel->reset()->where('order_id', $uuid)->eraseAll();
					}
					
					pjAppController::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => 'Booking has been deleted.'));
				} else {
					pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => 'Booking has not been deleted.'));
				}
			}
			pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 101, 'text' => 'Missing or empty parameters.'));
		}
		exit;
	}
	
	public function pjActionDeleteBookingBulk()
	{
		$this->setAjax(true);
	
		if ($this->isXHR() && $this->isLoged())
		{
			if (isset($_POST['record']) && !empty($_POST['record']))
			{
				$pjBookingModel = pjBookingModel::factory();
				$uuid_arr = $pjBookingModel->whereIn('id', $_POST['record'])->findAll()->getDataPair(null, 'uuid');
				
				$pjBookingModel->reset()->whereIn('id', $_POST['record'])->limit(count($_POST['record']))->eraseAll();
				pjBookingExtraModel::factory()->whereIn('booking_id', $_POST['record'])->eraseAll();
				pjBookingRoomModel::factory()->whereIn('booking_id', $_POST['record'])->eraseAll();
				pjBookingRoomTempModel::factory()->whereIn('booking_id', $_POST['record'])->eraseAll();
				pjMultiLangModel::factory()->where('model', 'pjBooking')->whereIn('foreign_id', $_POST['record'])->eraseAll();
				
				if(!empty($uuid_arr))
				{
					$pjInvoiceModel = pjInvoiceModel::factory();
					$invoice_id_arr = $pjInvoiceModel->whereIn('order_id', $uuid_arr)->findAll()->getDataPair(null, 'id');
					if(!empty($invoice_id_arr))
					{
						pjInvoiceItemModel::factory()->whereIn('invoice_id', $invoice_id_arr)->eraseAll();
						$pjInvoiceModel->reset()->whereIn('order_id', $uuid_arr)->eraseAll();
					}
				}
				
				pjAppController::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => 'Booking(s) has been deleted.'));
			}
			pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => 'Missing or empty parameters.'));
		}
		exit;
	}
	
	public function pjActionDeleteBookingRoom()
	{
		$this->setAjax(true);
		
		if ($this->isXHR() && $this->isLoged())
		{
			if (isset($_POST['id']) && (int) $_POST['id'] > 0)
			{
				if (pjBookingRoomTempModel::factory()->set('id', $_POST['id'])->erase()->getAffectedRows() == 1)
				{
					pjAppController::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => ''));
				}
			}
			pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => ''));
		}
		exit;
	}
	
	public function pjActionExportBooking()
	{
		$this->checkLogin();
		
		if (isset($_POST['record']) && is_array($_POST['record']))
		{
			$arr = pjBookingModel::factory()->whereIn('id', $_POST['record'])->findAll()->getData();
			$csv = new pjCSV();
			$csv
				->setHeader(true)
				->setName("Bookings-".time().".csv")
				->process($arr)
				->download();
		}
		exit;
	}
	
	public function pjActionGetBooking()
	{
		$this->setAjax(true);
	
		if ($this->isXHR() && $this->isLoged())
		{
			$pjBookingModel = pjBookingModel::factory();
				
			if (isset($_GET['q']) && !empty($_GET['q']))
			{
				$q = $pjBookingModel->escapeStr($_GET['q']);
				$q = str_replace(array('_', '%'), array('\_', '\%'), trim($q));
				$pjBookingModel->where('t1.c_fname LIKE', "%$q%");
				$pjBookingModel->orWhere('t1.c_lname LIKE', "%$q%");
				$pjBookingModel->orWhere('t1.c_email LIKE', "%$q%");
				$pjBookingModel->orWhere("(lower(concat_ws(' ', t1.c_fname, t1.c_lname)) LIKE lower('%$q%'))");
			}
			
			// Advanced search
			if (isset($_GET['uuid']) && !empty($_GET['uuid']))
			{
				$q = $pjBookingModel->escapeStr($_GET['uuid']);
				$q = str_replace(array('_', '%'), array('\_', '\%'), trim($q));
				$pjBookingModel->where('t1.uuid LIKE', "%$q%");
			}
			
			if (isset($_GET['voucher']) && !empty($_GET['voucher']))
			{
				$q = $pjBookingModel->escapeStr($_GET['voucher']);
				$q = str_replace(array('_', '%'), array('\_', '\%'), trim($q));
				$pjBookingModel->where('t1.voucher LIKE', "%$q%");
			}
			
			if (isset($_GET['c_name']) && !empty($_GET['c_name']))
			{
				$q = $pjBookingModel->escapeStr($_GET['c_name']);
				$q = str_replace(array('_', '%'), array('\_', '\%'), trim($q));
				$pjBookingModel->where('t1.c_fname LIKE', "%$q%");
				$pjBookingModel->orWhere('t1.c_lname LIKE', "%$q%");
			}
			
			if (isset($_GET['c_email']) && !empty($_GET['c_email']))
			{
				$q = $pjBookingModel->escapeStr($_GET['c_email']);
				$q = str_replace(array('_', '%'), array('\_', '\%'), trim($q));
				$pjBookingModel->where('t1.c_email LIKE', "%$q%");
			}
			
			if (isset($_GET['status']) && in_array($_GET['status'], array('confirmed', 'not_confirmed', 'cancelled', 'pending')))
			{
				$pjBookingModel->where('t1.status', $_GET['status']);
			}
			
			if (isset($_GET['payment_method']) && in_array($_GET['payment_method'], array('paypal','authorize','creditcard','bank','cash')))
			{
				$pjBookingModel->where('t1.payment_method', $_GET['payment_method']);
			}
			
			if (isset($_GET['iso_date']) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $_GET['iso_date']))
			{
				$pjBookingModel->where(sprintf("'%s' BETWEEN t1.date_from AND t1.date_to", $pjBookingModel->escapeStr($_GET['iso_date'])));
			}
			
			if (isset($_GET['date_from']) && !empty($_GET['date_from']) && isset($_GET['date_to']) && !empty($_GET['date_to']))
			{
				$pjBookingModel->where('t1.date_from <=', pjUtil::formatDate($_GET['date_to'], $this->option_arr['o_date_format']));
				$pjBookingModel->where('t1.date_to >=', pjUtil::formatDate($_GET['date_from'], $this->option_arr['o_date_format']));
			} else {
				if (isset($_GET['date_from']) && !empty($_GET['date_from']))
				{
					$pjBookingModel->where('t1.date_from >=', pjUtil::formatDate($_GET['date_from'], $this->option_arr['o_date_format']));
				}
				if (isset($_GET['date_to']) && !empty($_GET['date_to']))
				{
					$pjBookingModel->where('t1.date_to <=', pjUtil::formatDate($_GET['date_to'], $this->option_arr['o_date_format']));
				}
			}

			if (isset($_GET['total_from']) && strlen($_GET['total_from']) > 0)
			{
				$pjBookingModel->where('t1.total >=', (float) $_GET['total_from']);
			}
			if (isset($_GET['total_to']) && strlen($_GET['total_to']) > 0)
			{
				$pjBookingModel->where('t1.total <=', (float) $_GET['total_to']);
			}
			
			if (isset($_GET['created_from']) && !empty($_GET['created_from']))
			{
				$pjBookingModel->where('t1.created >=', pjUtil::formatDate($_GET['created_from'], $this->option_arr['o_date_format']));
			}
			
			if (isset($_GET['created_to']) && !empty($_GET['created_to']))
			{
				$pjBookingModel->where('t1.created <=', pjUtil::formatDate($_GET['created_to'], $this->option_arr['o_date_format']));
			}
			
			if (isset($_GET['room_id']) && (int) $_GET['room_id'] > 0)
			{
				$pjBookingModel->where(sprintf("t1.id IN (SELECT `booking_id` FROM `%s` WHERE `room_id` = '%u')", pjBookingRoomModel::factory()->getTable(), (int) $_GET['room_id']));
			}
				
			$column = 'created';
			$direction = 'DESC';
			if (isset($_GET['direction']) && isset($_GET['column']) && in_array(strtoupper($_GET['direction']), array('ASC', 'DESC')))
			{
				$column = $_GET['column'];
				$direction = strtoupper($_GET['direction']);
			}

			$total = $pjBookingModel->findCount()->getData();
			$rowCount = isset($_GET['rowCount']) && (int) $_GET['rowCount'] > 0 ? (int) $_GET['rowCount'] : 10;
			$pages = ceil($total / $rowCount);
			$page = isset($_GET['page']) && (int) $_GET['page'] > 0 ? intval($_GET['page']) : 1;
			$offset = ((int) $page - 1) * $rowCount;
			if ($page > $pages)
			{
				$page = $pages;
			}

			$pjBookingModel->select("t1.id, t1.uuid, t1.created, t1.date_from, t1.date_to, CONCAT_WS(' ', t1.c_fname, t1.c_lname) AS c_name, t1.c_email, t1.status")
				->orderBy("$column $direction")->limit($rowCount, $offset)->findAll();
				
			$data = $pjBookingModel->getData();
			
			$ids = $pjBookingModel->getDataPair(null, 'id');
			if (!empty($ids))
			{
				$br_arr = pjBookingRoomModel::factory()
					->select("t1.booking_id, COUNT(t1.id) AS `cnt`, t2.content AS name, GROUP_CONCAT(t3.number SEPARATOR ', ') AS `room_number`")
					->join('pjMultiLang', "t2.model='pjRoom' AND t2.foreign_id=t1.room_id AND t2.field='name' AND t2.locale='".$this->getLocaleId()."'", 'left outer')
					->join('pjRoomNumber', 't3.id=t1.room_number_id', 'left outer')
					->whereIn('t1.booking_id', $ids)
					->groupBy('t1.booking_id, t1.room_id')
					->findAll()
					->getData();
				foreach ($data as $k => $v)
				{
					$created = strtotime($v['created']);
					
					$data[$k]['created'] = sprintf("%s, %s", 
						pjUtil::formatDate(date('Y-m-d', $created), 'Y-m-d', $this->option_arr['o_date_format']),
						date("H:i", $created)
					);
					$data[$k]['rooms'] = array();
					foreach ($br_arr as $bk => $bv)
					{
						if ($v['id'] == $bv['booking_id'])
						{
							$data[$k]['rooms'][] = $bv;
						}
					}
					$client_arr = array();
					if(!empty($v['c_name']))
					{
						$client_arr[] = pjSanitize::clean($v['c_name']);
					}
					if(!empty($v['c_email']))
					{
						$client_arr[] = '<span class="link-color">' . pjSanitize::clean($v['c_email']) . '</span>';
					}
					$data[$k]['c_email'] = implode("<br/>", $client_arr);
				}
			}
				
			pjAppController::jsonResponse(compact('data', 'total', 'pages', 'page', 'rowCount', 'column', 'direction'));
		}
		exit;
	}
	
	public function pjActionGetBookingRooms()
	{
		$this->setAjax(true);
	
		if ($this->isXHR() && $this->isLoged())
		{
			$is_bid = isset($_GET['booking_id']) && (int) $_GET['booking_id'] > 0;
			$is_hash = isset($_GET['hash']) && !empty($_GET['hash']);
			if ($is_bid || $is_hash)
			{
				$pjBookingRoomTempModel = pjBookingRoomTempModel::factory();
				if ($is_bid)
				{
					$pjBookingRoomTempModel->where('t1.booking_id', $_GET['booking_id']);
				} elseif ($is_hash)	{
					$pjBookingRoomTempModel->where('t1.hash', $_GET['hash']);
				}
				$arr = $pjBookingRoomTempModel
					->select('t1.*, t2.content AS name, t3.number')
					->join('pjMultiLang', "t2.model='pjRoom' AND t2.foreign_id=t1.room_id AND t2.field='name' AND t2.locale='".$this->getLocaleId()."'", 'left outer')
					->join('pjRoomNumber', 't3.id=t1.room_number_id', 'left outer')
					->orderBy("`name` ASC")->findAll()->getData();
				
				$this->set('arr', $arr);
			}
		}
	}
	
	private function getRoomNumbers($room_id, $date_from, $date_to, $booking_id, $hash)
	{
		$date_from = pjUtil::formatDate($date_from, $this->option_arr['o_date_format']);
		$date_to = pjUtil::formatDate($date_to, $this->option_arr['o_date_format']);
		
		$pjRoomNumberModel = pjRoomNumberModel::factory();
		
		$pjRestrictionRoomModel = pjRestrictionRoomModel::factory();
		if($this->option_arr['o_price_based_on'] == "nights")
		{
			$pjRestrictionRoomModel->where('t2.date_from <', $date_to);
			$pjRestrictionRoomModel->where('t2.date_to >', $date_from);
		}else{
			$pjRestrictionRoomModel->where('t2.date_from <=', $date_to);
			$pjRestrictionRoomModel->where('t2.date_to >=', $date_from);
		}
		$rr_arr = $pjRestrictionRoomModel
			->select('t1.room_number_id')
			->join('pjRestriction', "t2.id=t1.restriction_id AND t2.restriction_type != 'web'", 'inner')
			->findAll()
			->getDataPair(null, 'room_number_id');
		if (!empty($rr_arr))
		{
			$pjRoomNumberModel->whereNotIn('t1.id', $rr_arr);
		}
		

		$is_bid = (int) $booking_id > 0;
		$is_hash = !empty($hash);
		
		$room_number_id_arr = array();
		if ($is_bid || $is_hash)
		{
			$pjBookingRoomTempModel = pjBookingRoomTempModel::factory();
			if ($is_bid)
			{
				$pjBookingRoomTempModel->where('t1.booking_id', $booking_id);
			} elseif ($is_hash)	{
				$pjBookingRoomTempModel->where('t1.hash', $hash);
			}
			$room_number_id_arr = $pjBookingRoomTempModel->findAll()->getDataPair(null, 'room_number_id');
		}
		if (!empty($room_number_id_arr))
		{
			$pjRoomNumberModel->whereNotIn('t1.id', $room_number_id_arr);
		}
		
		$nightMode = $this->option_arr['o_price_based_on'] == "nights";
		$op1 = $nightMode ? "<" : "<=";
		$op2 = $nightMode ? ">" : ">=";
		
		$hours = $this->option_arr['o_pending_time'] / 60;
		$remainder = $this->option_arr['o_pending_time'] % 60;
		if ($remainder === 0)
		{
			$pending_time = sprintf("%u:00:00", $hours);
		} else {
			$pending_time = sprintf("%u:%u:00", $hours, $remainder);
		}
		
		$pjBookingRoomModel = pjBookingRoomModel::factory();
		$booking_rooms = $pjBookingRoomModel
			->join('pjBooking', sprintf("t2.id=t1.booking_id AND (t2.status = 'confirmed'".  ($is_bid ? " OR t2.id='".$booking_id."'" : NULL) ." OR (t2.status = 'pending' AND t2.created >= SUBTIME(NOW(), '%5\$s'))) AND t2.date_from %3\$s '%2\$s' AND t2.date_to %4\$s '%1\$s'",
				$pjBookingRoomModel->escapeStr($date_from),
				$pjBookingRoomModel->escapeStr($date_to),
				$nightMode ? "<" : "<=",
				$nightMode ? ">" : ">=",
				$pjBookingRoomModel->escapeStr($pending_time)), 'inner')
			->findAll()
			->getDataPair(null, 'room_number_id');
		
		if (!empty($booking_rooms))
		{
			$pjRoomNumberModel->whereNotIn('t1.id', $booking_rooms);
		} 
		
		return $pjRoomNumberModel->where('t1.room_id', $room_id)->orderBy('t1.id ASC')->findAll()->getData();
	}
	
	public function pjActionGetRoomNumbers()
	{
		$this->setAjax(true);
		
		if ($this->isXHR() && $this->isLoged())
		{
			if (isset($_GET['room_id']))
			{
				$room_number_arr = $this->getRoomNumbers($_GET['room_id'], $_GET['date_from'], $_GET['date_to'], $_GET['booking_id'], $_GET['hash']);
				
				$this->set('room_number_arr', $room_number_arr);
			}
		}
	}
	
	public function pjActionIndex()
	{
		$this->checkLogin();
		
		if ($this->isAdmin() || $this->isEditor())
		{
			$is_empty = (0 == pjBookingModel::factory()->findCount()->getData());
			$this->set('is_empty', $is_empty);
			
			if (!$is_empty)
			{
				$this->notConfirmed();
			
				$this->set('room_arr', pjRoomModel::factory()
					->select('t1.*, t2.content AS `name`')
					->join('pjMultiLang', "t2.model='pjRoom' AND t2.foreign_id=t1.id AND t2.field='name' AND t2.locale='".$this->getLocaleId()."'", 'left outer')
					->orderBy('`name` ASC')
					->findAll()
					->getData());
			}
			$this->appendJs('jquery.datagrid.js', PJ_FRAMEWORK_LIBS_PATH . 'pj/js/');
			$this->appendJs('pjAdminBookings.js');
			$this->appendJs('index.php?controller=pjAdmin&action=pjActionMessages', PJ_INSTALL_URL, true);
		} else {
			$this->set('status', 2);
		}
	}
	
	private function getPrice($data)
	{
		if (!isset($data['date_from']) || !isset($data['date_to']) || empty($data['date_from']) || empty($data['date_to']))
		{
			return array('status' => 'ERR', 'code' => 100, 'text' => 'Not enough data.');
		}
			
		$room_price = $extra_price = $total = $security = $tax = $deposit = $discount = 0;
			
		$date_from = pjUtil::formatDate($data['date_from'], $this->option_arr['o_date_format']);
		$date_to = pjUtil::formatDate($data['date_to'], $this->option_arr['o_date_format']);
		
		$_nights = ceil((strtotime($date_to) - strtotime($date_from)) / 86400);
		if ($this->option_arr['o_price_based_on'] == 'days')
		{
			$_nights += 1;
		}
		$_persons = 0;
			
		$pjBookingRoomTempModel = pjBookingRoomTempModel::factory();
		if (isset($data['id']) && (int) $data['id'] > 0)
		{
			$pjBookingRoomTempModel->where('t1.booking_id', $data['id']);
		} elseif (isset($data['hash']) && !empty($data['hash'])) {
			$pjBookingRoomTempModel->where('t1.hash', $data['hash']);
		}
		$pjBookingRoomTempModel->findAll();
			
		$room_ids = $pjBookingRoomTempModel->getDataPair(null, 'room_id');
		$room_ids = array_unique($room_ids);
			
		if (empty($room_ids))
		{
			return array('status' => 'OK', 'code' => 201, 'text' => 'Rooms no found. All the prices equals to zero.', 'data' => 
				compact('total', 'tax', 'security', 'deposit', 'room_price', 'extra_price', 'discount'));
			//return array('status' => 'ERR', 'code' => 101, 'text' => 'No rooms found.');
		}
			
		$voucher_arr = array();
		if (!empty($data['voucher']))
		{
			$code = $data['voucher'];
			$response = pjAppController::getDiscount(compact('date_from', 'date_to', 'code'));
			if ($response['status'] == 'OK')
			{
				$intersect = array_intersect(array_keys($response['voucher_rooms']), $room_ids);
				if (empty($response['voucher_room']) || !empty($intersect))
				{
					$voucher_arr = array(
						'voucher_code' => $response['voucher_code'],
						'voucher_rooms' => $response['voucher_rooms']
					);
				}
			}
		}
			
		$booking_rooms = $pjBookingRoomTempModel->getData();
		$session_prices = $this->calPrices($booking_rooms, $date_from, $date_to, $voucher_arr, $data, $_nights, $this->option_arr, 'back');
			
		return array('status' => 'OK', 'code' => 200, 'text' => 'Booking price has been retrieved.', 'data' =>$session_prices);
	}
	
	public function pjActionGetPriceAgent()
	{
		$this->setAjax(true);
		
		if ($this->isXHR() && $this->isLoged())
		{
			if (!isset($_POST['id']))
			{
				pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => 'Missing, empty or invalid parameters.'));
			}
			
			$current = pjBookingModel::factory()
				->select('`room_price`, `extra_price`, `total`, `deposit`, `tax`, `security`, `discount`')
				->find($_POST['id'])
				->getData();
			if (empty($current))
			{
				pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 101, 'text' => 'Booking not found.'));
			}
			#$current = array_map('floatval', $current);
			$current = array_map('floatval', $_POST);
			
			$formPrice = $this->getPrice($_POST);
			if ($formPrice['status'] === 'ERR')
			{
				pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 102, 'text' => ''));
			}
			$real = array_map('floatval', $formPrice['data']);

			$result = array(
				'status' => 'OK',
				'code' => 200,
				'text' => 'Price comparisson has been finished.',
				'data' => array(
					'room_price' => array('status' => 'OK'),
					'extra_price' => array('status' => 'OK'),
					'total' => array('status' => 'OK'),
					'deposit' => array('status' => 'OK'),
					'tax' => array('status' => 'OK'),
					'security' => array('status' => 'OK'),
					'discount' => array('status' => 'OK')
				)
			);
			
			$tooltip = str_replace('{BUTTON}', __('booking_recalc', true), __('booking_amount_tooltip', true));
			
			if ($current['room_price'] != $real['room_price'])
			{
				$result['data']['room_price'] = array('status' => 'ERR', 'current' => $current['room_price'], 'real' => $real['room_price'], 'tooltip' => str_replace('{AMOUNT}', pjUtil::formatCurrencySign(number_format($real['room_price'], 2, '.', ','), $this->option_arr['o_currency']), $tooltip));
			}
			if ($current['extra_price'] != $real['extra_price'])
			{
				$result['data']['extra_price'] = array('status' => 'ERR', 'current' => $current['extra_price'], 'real' => $real['extra_price'], 'tooltip' => str_replace('{AMOUNT}', pjUtil::formatCurrencySign(number_format($real['extra_price'], 2, '.', ','), $this->option_arr['o_currency']), $tooltip));
			}
			if ($current['total'] != $real['total'])
			{
				$result['data']['total'] = array('status' => 'ERR', 'current' => $current['total'], 'real' => $real['total'], 'tooltip' => str_replace('{AMOUNT}', pjUtil::formatCurrencySign(number_format($real['total'], 2, '.', ','), $this->option_arr['o_currency']), $tooltip));
			}
			if ($current['deposit'] != $real['deposit'])
			{
				$result['data']['deposit'] = array('status' => 'ERR', 'current' => $current['deposit'], 'real' => $real['deposit'], 'tooltip' => str_replace('{AMOUNT}', pjUtil::formatCurrencySign(number_format($real['deposit'], 2, '.', ','), $this->option_arr['o_currency']), $tooltip));
			}
			if ($current['tax'] != $real['tax'])
			{
				$result['data']['tax'] = array('status' => 'ERR', 'current' => $current['tax'], 'real' => $real['tax'], 'tooltip' => str_replace('{AMOUNT}', pjUtil::formatCurrencySign(number_format($real['tax'], 2, '.', ','), $this->option_arr['o_currency']), $tooltip));
			}
			if ($current['security'] != $real['security'])
			{
				$result['data']['security'] = array('status' => 'ERR', 'current' => $current['security'], 'real' => $real['security'], 'tooltip' => str_replace('{AMOUNT}', pjUtil::formatCurrencySign(number_format($real['security'], 2, '.', ','), $this->option_arr['o_currency']), $tooltip));
			}
			if ($current['discount'] != $real['discount'])
			{
				$result['data']['discount'] = array('status' => 'ERR', 'current' => $current['discount'], 'real' => $real['discount'], 'tooltip' => str_replace('{AMOUNT}', pjUtil::formatCurrencySign(number_format($real['discount'], 2, '.', ','), $this->option_arr['o_currency']), $tooltip));
			}
			
			pjAppController::jsonResponse($result);
		}
		exit;
	}
	
	public function pjActionRecalcPrice()
	{
		$this->isAjax = true;
	
		if ($this->isXHR() && $this->isLoged())
		{
			pjAppController::jsonResponse($this->getPrice($_POST));
		}
		exit;
	}
	
	public function pjActionSaveBooking()
	{
		$this->setAjax(true);
	
		if ($this->isXHR() && $this->isLoged())
		{
			$pjBookingModel = pjBookingModel::factory();
			if (!in_array($_POST['column'], $pjBookingModel->getI18n()))
			{
				$pjBookingModel->where('id', $_GET['id'])->limit(1)->modifyAll(array($_POST['column'] => $_POST['value']));
			} else {
				pjMultiLangModel::factory()->updateMultiLang(array($this->getLocaleId() => array($_POST['column'] => $_POST['value'])), $_GET['id'], 'pjBooking');
			}
			pjAppController::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => ''));
		}
		exit;
	}
	
	public function pjActionUpdate()
	{
		$this->checkLogin();
		
		if ($this->isAdmin() || $this->isEditor())
		{
			$pjBookingModel = pjBookingModel::factory();
			if (isset($_REQUEST['id']) && (int) $_REQUEST['id'] > 0)
			{
				$pjBookingModel->where('t1.id', $_REQUEST['id']);
			} elseif (isset($_GET['uuid']) && !empty($_GET['uuid'])) {
				$pjBookingModel->where('t1.uuid', $_GET['uuid']);
			}
			$arr = $pjBookingModel
				->limit(1)
				->findAll()
				->getData();
				
			if (empty($arr))
			{
				pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminBookings&action=pjActionIndex&err=ABK08");
			}
			$arr = $arr[0];
			
			if (isset($_POST['booking_update']))
			{
				$data = array();
				$data['date_from'] = pjUtil::formatDate($_POST['date_from'], $this->option_arr['o_date_format']);
				$data['date_to'] = pjUtil::formatDate($_POST['date_to'], $this->option_arr['o_date_format']);
				$data['c_arrival'] = sprintf("%s:%s:00", $_POST['hour'], $_POST['minute']);
				
				# Check room availability
				$pjBookingRoomTempModel = pjBookingRoomTempModel::factory();
				$pjBookingRoomModel = pjBookingRoomModel::factory();
				$nightMode = $this->option_arr['o_price_based_on'] == "nights";
				if ($nightMode)
				{
					$condition = sprintf(" AND b.date_from < '%s' AND b.date_to > '%s' ", $data['date_to'], $data['date_from']);
				} else {
					$condition = sprintf(" AND b.date_from <= '%s' AND b.date_to >= '%s' ", $data['date_to'], $data['date_from']);
				}
				
				$tmp_arr = $pjBookingRoomTempModel
					->select(sprintf("t1.room_id, COUNT(*) AS `current_booking_cnt`, t2.cnt AS `total_cnt`,
						(SELECT COUNT(*)
						FROM `%1\$s` AS `br`
						INNER JOIN `%2\$s` AS `b` ON b.id = br.booking_id
							AND b.status = 'confirmed'
							%4\$s
						WHERE br.room_id = t1.room_id
						AND br.booking_id != '%3\$u'
						LIMIT 1) AS `other_booking_cnt`
					", $pjBookingRoomModel->getTable(), pjBookingModel::factory()->getTable(), $arr['id'], $condition))
					->join('pjRoom', 't1.room_id=t2.id', 'inner')
					->where('t1.booking_id', $arr['id'])
					->where('t1.hash IS NULL')
					->groupBy('t1.room_id, t1.hash')
					->findAll()
					->getData();

				$pjRoomModel = pjRoomModel::factory();
				$date_from = $data['date_from'];
				$date_to = $data['date_to'];
				$tmp = pjBookingRoomModel::factory()
					->select('t1.*, t2.date_from, t2.date_to')
					->join('pjBooking', sprintf("t2.id=t1.booking_id AND (t2.status = 'confirmed' OR (t2.status = 'pending' AND t2.created >= SUBTIME(NOW(), '01:00:00'))) AND t2.date_from %3\$s '%2\$s' AND t2.date_to %4\$s '%1\$s'",
						$pjRoomModel->escapeStr($date_from),
						$pjRoomModel->escapeStr($date_to),
						$nightMode ? "<" : "<=",
						$nightMode ? ">" : ">="), 'inner')
					->findAll()
					->getData();
					
				$sum = array();
				$init = array();
				$dt_from = strtotime($date_from);
				$dt_to = strtotime($date_to);
				for ($i = $dt_from; $i <= $dt_to; $i += 86400)
				{
					$init[date("Y-m-d", $i)] = 0;
				}
					
				foreach ($tmp as $value)
				{
					if (!isset($sum[$value["room_id"]]))
					{
						$sum[$value["room_id"]] = $init;
					}
					$from = strtotime($value["date_from"]);
					$to = strtotime($value["date_to"]);
					for ($z = $from; $z <= $to; $z += 86400)
					{
						if (($z >= $dt_from && $z < $dt_to) === FALSE)
						{
							continue;
						}
						$iso = date("Y-m-d", $z);
						if ($z == $to && $nightMode)
						{
							continue;
						}
						$sum[$value["room_id"]][$iso] += 1;
					}
				}
				$isValid = true;
				foreach ($tmp_arr as $item)
				{
					$max_bookings = isset($sum[$item['room_id']]) && !empty($sum[$item['room_id']]) ? ceil(max($sum[$item['room_id']])) : 0;
					if ($max_bookings > (int) $item['total_cnt'])
					{
						$isValid = false;
						break;
					}
				}
				unset($tmp_arr);
				
				if (!$isValid)
				{
					$pjBookingRoomTempModel->reset()->where('booking_id', $arr['id'])->eraseAll();
					pjUtil::redirect($_SERVER['PHP_SELF'] . '?controller=pjAdminBookings&action=pjActionIndex&err=ABK10');
				}
				# Check room availability
				
				# Temp
				$pjBookingRoomModel->where('booking_id', $arr['id'])->eraseAll();
				
				$statement = sprintf("INSERT INTO `%s` (`booking_id`, `room_id`, `room_number_id`, `adults`, `children`, `price`)
					SELECT `booking_id`, `room_id`, `room_number_id`, `adults`, `children`, `price` FROM `%s` WHERE `booking_id` = :booking_id",
					$pjBookingRoomModel->getTable(), $pjBookingRoomTempModel->getTable()
				);
				$pjBookingRoomModel->reset()->prepare($statement)->exec(array('booking_id' => $arr['id']));
				$pjBookingRoomTempModel->reset()->where('booking_id', $arr['id'])->eraseAll();
				# Temp
				
				$pjBookingModel->reset()->set('id', $arr['id'])->modify(array_merge($_POST, $data));
				if (isset($_POST['i18n']))
				{
					pjMultiLangModel::factory()->updateMultiLang($_POST['i18n'], $arr['id'], 'pjBooking');
				}
				
				$pjBookingExtraModel = pjBookingExtraModel::factory();
				$pjBookingExtraModel->where('booking_id', $arr['id'])->eraseAll()->reset();
				if (isset($_POST['extra_id']) && !empty($_POST['extra_id']))
				{
					foreach ($_POST['extra_id'] as $extra_id => $extra)
					{
						list(, $price) = explode("|", $extra);
						$pjBookingExtraModel->addBatchRow(array($arr['id'], $extra_id, $price));
					}
					$pjBookingExtraModel
						->setBatchFields(array('booking_id', 'extra_id', 'price'))
						->insertBatch();
				}
				
				pjUtil::redirect(sprintf("%s?controller=pjAdminBookings&action=pjActionUpdate&id=%u&err=ABK01", $_SERVER['PHP_SELF'], $arr['id']));
				
			} else {
				$arr['i18n'] = pjMultiLangModel::factory()->getMultiLang($arr['id'], 'pjBooking');
				
				$locale_arr = pjLocaleModel::factory()->select('t1.*, t2.file')
					->join('pjLocaleLanguage', 't2.iso=t1.language_iso', 'left outer')
					->where('t2.file IS NOT NULL')
					->orderBy('t1.sort ASC')->findAll()->getData();
				
				$lp_arr = array();
				foreach ($locale_arr as $item)
				{
					$lp_arr[$item['id']."_"] = $item['file']; //Hack for jquery $.extend, to prevent (re)order of numeric keys in object
				}
				
				$this->set('invoice_arr', pjInvoiceModel::factory()
					->where('t1.order_id', $arr['uuid'])
					->findAll()
					->getData()
				);
				
				$pjBookingRoomTempModel = pjBookingRoomTempModel::factory();
				# Temp 1. Delete rooms from tmp
				$pjBookingRoomTempModel->where('booking_id', $arr['id'])->eraseAll();
				# Temp 2. Copy rooms to tmp
				$statement = sprintf("INSERT INTO `%s` (`booking_id`, `room_id`, `room_number_id`, `adults`, `children`, `price`)
					SELECT `booking_id`, `room_id`, `room_number_id`, `adults`, `children`, `price` FROM `%s` WHERE `booking_id` = :booking_id",
					$pjBookingRoomTempModel->getTable(),
					pjBookingRoomModel::factory()->getTable()
				);
				$pjBookingRoomTempModel->reset()->prepare($statement)->exec(array('booking_id' => $arr['id']));
			
				$this
					->set('arr', $arr)
					->set('lp_arr', $locale_arr)
					->set('locale_str', pjAppController::jsonEncode($lp_arr))
					->set('country_arr', pjCountryModel::factory()
						->select('t1.id, t2.content AS name')
						->join('pjMultiLang', "t2.model='pjCountry' AND t2.foreign_id=t1.id AND t2.field='name' AND t2.locale='".$this->getLocaleId()."'", 'left outer')
						->orderBy('`name` ASC')->findAll()->getData()
					)
					->set('extra_arr', pjExtraModel::factory()
						->select('t1.*, t2.content AS name')
						->join('pjMultiLang', "t2.model='pjExtra' AND t2.foreign_id=t1.id AND t2.field='name' AND t2.locale='".$this->getLocaleId()."'", 'left outer')
						->where('t1.calendar_id', $this->getForeignId())
						->orderBy('`name` ASC')
						->findAll()
						->getData()
					)
					->set('be_arr', pjBookingExtraModel::factory()->where('t1.booking_id', $arr['id'])->findAll()->getDataPair('extra_id', 'price'))
					->appendJs('jquery.validate.min.js', PJ_THIRD_PARTY_PATH . 'validate/')
					->appendJs('jquery.multilang.js', PJ_FRAMEWORK_LIBS_PATH . 'pj/js/')
					->appendJs('jquery.tipsy.js', PJ_THIRD_PARTY_PATH . 'tipsy/')
					->appendJs('tinymce.min.js', PJ_THIRD_PARTY_PATH . 'tinymce/')
					->appendCss('tipsy.css')
					->appendJs('jquery.datagrid.js', PJ_FRAMEWORK_LIBS_PATH . 'pj/js/')
					->appendJs('pjAdminBookings.js')
					->appendJs('index.php?controller=pjAdmin&action=pjActionMessages', PJ_INSTALL_URL, true);
			}
		} else {
			$this->set('status', 2);
		}
	}

	public function pjActionUpdateBookingRoom()
	{
		$this->setAjax(true);
		
		if ($this->isXHR() && $this->isLoged())
		{
			if (isset($_POST['room_update']))
			{
				if (pjBookingRoomTempModel::factory()->set('id', $_POST['id'])->modify($_POST)->getAffectedRows() == 1)
				{
					pjAppController::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => ''));
				}
				pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => ''));
			}

			$arr = pjBookingRoomTempModel::factory()
				->select('t1.*, t2.adults AS max_adults, t2.children AS max_children, t3.content AS `name`')
				->join('pjRoom', 't1.room_id=t2.id', 'left outer')
				->join('pjMultiLang', "t3.model='pjRoom' AND t3.foreign_id=t1.room_id AND t3.field='name' AND t3.locale='".$this->getLocaleId()."'", 'left outer')
				->find($_GET['id'])->getData();
			$this
				->set('arr', $arr)
				->set('room_number_arr', pjRoomNumberModel::factory()->where('t1.room_id', $arr['room_id'])->orderBy('t1.id ASC')->findAll()->getData());
		}
	}
}
?>