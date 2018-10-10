<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjAdminReports extends pjAdmin
{
	private function getReport($from, $to, $option_arr)
	{
		$result = array();
		
		$pjBookingModel = pjBookingModel::factory();
		$pjRoomModel = pjRoomModel::factory();
		$pjBookingRoomModel = pjBookingRoomModel::factory();
		
		$conditions = NULL;
		if (!empty($from) && !empty($to))
		{
			$from = pjUtil::formatDate($from, $option_arr['o_date_format']);
			$to = pjUtil::formatDate($to, $option_arr['o_date_format']);
			$conditions = " AND ((`date_from` BETWEEN :from AND :to) OR (`date_to` BETWEEN :from AND :to) OR (`date_from` < :from AND `date_to` > :to) OR (`date_from` > :from AND `date_to` < :to))";
		} else {
			if (!empty($from))
			{
				$from = pjUtil::formatDate($from, $option_arr['o_date_format']);
				$conditions = " AND `date_from` >= :from";
			} elseif (!empty($to)) {
				$to = pjUtil::formatDate($to, $option_arr['o_date_format']);
				$conditions = " AND `date_to` <= :to";
			}
		}
		
		$statement = sprintf("SELECT TB.*, (DATEDIFF(`date_to`, `date_from`)*(SELECT COUNT(TBR.id) FROM `%3\$s` AS `TBR` WHERE `TB`.`id`= `TBR`.`booking_id`)) AS `nights`,
							   (SELECT SUM(TBR.`adults` + TBR.`children`) FROM `%3\$s` AS `TBR` WHERE `TB`.`id` = `TBR`.`booking_id`) AS `guests`,
							   (SELECT SUM(TBR.`adults`) FROM `%3\$s` AS `TBR` WHERE `TB`.`id` = `TBR`.`booking_id`) AS `total_adults`,
							   (SELECT SUM(TBR.`children`) FROM `%3\$s` AS `TBR` WHERE `TB`.`id` = `TBR`.`booking_id`) AS `total_children`
						FROM `%2\$s` AS TB WHERE 1 %4\$s",
			$pjRoomModel->getTable(),
			$pjBookingModel->getTable(),
			$pjBookingRoomModel->getTable(),
			$conditions
		);
	
		$arr = $pjBookingModel->prepare($statement)->exec(array(
			'from' => $from,
			'to' => $to
		))->getData();
		
		$received_arr = array();
		$confirmed_arr = array();
		$cancelled_arr = array();
		$night_arr = array();
		$guest_arr = array();
		$total_adults = 0;
		$total_children = 0;
		$total_nights = 0;
		$total_guests = 0;
		$total_bookings = 0;
		
		foreach($arr as $v)
		{
			$received_arr['bookings'] = isset($received_arr['bookings']) ? $received_arr['bookings'] + 1 : 1;
			$received_arr['guests'] = isset($received_arr['guests']) ? $received_arr['guests'] + $v['guests'] : $v['guests'];
			$received_arr['nights'] = isset($received_arr['nights']) ? $received_arr['nights'] + $v['nights'] : $v['nights'];
			$received_arr['total'] = isset($received_arr['total']) ? $received_arr['total'] + $v['total'] : $v['total'];
			if($v['status'] == 'confirmed')
			{
				$confirmed_arr['bookings'] = isset($confirmed_arr['bookings']) ? $confirmed_arr['bookings'] + 1 : 1;
				$confirmed_arr['guests'] = isset($confirmed_arr['guests']) ? $confirmed_arr['guests'] + $v['guests'] : $v['guests'];
				$confirmed_arr['nights'] = isset($confirmed_arr['nights']) ? $confirmed_arr['nights'] + $v['nights'] : $v['nights'];
				$confirmed_arr['total'] = isset($confirmed_arr['total']) ? $confirmed_arr['total'] + $v['total'] : $v['total'];
			}
			if($v['status'] == 'cancelled')
			{
				$cancelled_arr['bookings'] = isset($cancelled_arr['bookings']) ? $cancelled_arr['bookings'] + 1 : 1;
				$cancelled_arr['guests'] = isset($cancelled_arr['guests']) ? $cancelled_arr['guests'] + $v['guests'] : $v['guests'];
				$cancelled_arr['nights'] = isset($cancelled_arr['nights']) ? $cancelled_arr['nights'] + $v['nights'] : $v['nights'];
				$cancelled_arr['total'] = isset($cancelled_arr['total']) ? $cancelled_arr['total'] + $v['total'] : $v['total'];
			}
			if($v['status'] == 'confirmed')
			{
				if(!empty($v['nights']))
				{
					if($v['nights'] <= 6)
					{
						$night_arr[$v['nights']] = isset($night_arr[$v['nights']]) ? $night_arr[$v['nights']] + 1 : 1;
					}else{
						$night_arr[7] = isset($night_arr[7]) ? $night_arr[7] + 1 : 1;
					}
					$total_nights++;
				}
			
				if(!empty($v['total_adults']))
				{
					if($v['total_adults'] <= 6)
					{
						$guest_arr[$v['total_adults']] = isset($guest_arr[$v['total_adults']]) ? $guest_arr[$v['total_adults']] + 1 : 1;
					}else{
						$guest_arr[7] = isset($guest_arr[7]) ? $guest_arr[7] + 1 : 1;
					}
					$total_bookings++;
					$total_adults += $v['total_adults'];
					$total_guests += $v['total_adults'];
				}
				if(!empty($v['total_children']))
				{
					$total_children += $v['total_children'];
					$total_guests += $v['total_children'];
				}
			}
		}
		
		$result['received_arr'] = $received_arr;
		$result['confirmed_arr'] = $confirmed_arr;
		$result['cancelled_arr'] = $cancelled_arr;
		$result['night_arr'] = $night_arr;
		$result['guest_arr'] = $guest_arr;
		$result['total_adults'] = $total_adults;
		$result['total_children'] = $total_children;
		$result['total_nights'] = $total_nights;
		$result['total_guests'] = $total_guests;
		$result['total_bookings'] = $total_bookings;
		
		$statement = sprintf("SELECT t1.*, m.content AS `type`,
			(SELECT COUNT(*) FROM `%1\$s` AS `TBR`
				INNER JOIN `%2\$s` AS `TB` ON `TB`.`id` = `TBR`.`booking_id` %3\$s
				WHERE `TBR`.`room_id` = `t1`.`id` LIMIT 1) AS `bookings`,
			(SELECT SUM(`adults` + `children`) FROM `%1\$s` AS `TBR`
				INNER JOIN `%2\$s` AS `TB` ON `TB`.`id` = `TBR`.`booking_id` %3\$s
				WHERE `TBR`.`room_id` = `t1`.`id` LIMIT 1) AS `guests`,
			(SELECT SUM(DATEDIFF(`date_to`, `date_from`)) FROM `%1\$s` AS `TBR`
				INNER JOIN `%2\$s` AS `TB` ON `TB`.`id` = `TBR`.`booking_id` %3\$s
				WHERE `TBR`.`room_id` = `t1`.`id` LIMIT 1) AS `nights`
			FROM `%4\$s` AS `t1`
			LEFT OUTER JOIN `%5\$s` AS `m` ON m.model=:model AND m.foreign_id=t1.id AND m.field=:field AND m.locale=:locale
			WHERE 1", $pjBookingRoomModel->getTable(), $pjBookingModel->getTable(), $conditions, $pjRoomModel->getTable(), pjMultiLangModel::factory()->getTable());
		
		$result['room_arr'] = $pjRoomModel->prepare($statement)->exec(array(
			'from' => $from,
			'to' => $to,
			'model' => 'pjRoom',
			'field' => 'name',
			'locale' => $this->getLocaleId()
		))->getData();
		
		$result['room_arr'] = $this->calReportPrice($conditions, $result['room_arr'], '', $from, $to);

		$statement = sprintf("SELECT t1.*, 
			(SELECT COUNT(*) FROM `%1\$s` AS `TBR`
				INNER JOIN `%2\$s` AS `TB` ON `TB`.`id` = `TBR`.`booking_id` AND `TB`.`status` = :status %3\$s
				WHERE `TBR`.`room_id` = `t1`.`id` LIMIT 1) AS `bookings`,
			(SELECT SUM(`adults` + `children`) FROM `%1\$s` AS `TBR`
				INNER JOIN `%2\$s` AS `TB` ON `TB`.`id` = `TBR`.`booking_id` AND `TB`.`status` = :status %3\$s
				WHERE `TBR`.`room_id` = `t1`.`id` LIMIT 1) AS `guests`,
			(SELECT SUM(DATEDIFF(`date_to`, `date_from`)) FROM `%1\$s` AS `TBR`
				INNER JOIN `%2\$s` AS `TB` ON `TB`.`id` = `TBR`.`booking_id` AND `TB`.`status` = :status %3\$s
				WHERE `TBR`.`room_id` = `t1`.`id` LIMIT 1) AS `nights`
			FROM `%4\$s` AS `t1`
			WHERE 1", $pjBookingRoomModel->getTable(), $pjBookingModel->getTable(), $conditions, $pjRoomModel->getTable());
		$result['room_confirmed_arr'] = $pjRoomModel->prepare($statement)->exec(array(
			'from' => $from,
			'to' => $to,
			'status' => 'confirmed'
		))->getData();
		
		$result['room_confirmed_arr'] = $this->calReportPrice($conditions, $result['room_confirmed_arr'], 'confirmed', $from, $to);
		
		$statement = sprintf("SELECT t1.*,
			(SELECT COUNT(*) FROM `%1\$s` AS `TBR`
				INNER JOIN `%2\$s` AS `TB` ON `TB`.`id` = `TBR`.`booking_id` AND `TB`.`status` = :status %3\$s
				WHERE `TBR`.`room_id` = `t1`.`id` LIMIT 1) AS `bookings`,
			(SELECT SUM(`adults` + `children`) FROM `%1\$s` AS `TBR`
				INNER JOIN `%2\$s` AS `TB` ON `TB`.`id` = `TBR`.`booking_id` AND `TB`.`status` = :status %3\$s
				WHERE `TBR`.`room_id` = `t1`.`id` LIMIT 1) AS `guests`,
			(SELECT SUM(DATEDIFF(`date_to`, `date_from`)) FROM `%1\$s` AS `TBR`
				INNER JOIN `%2\$s` AS `TB` ON `TB`.`id` = `TBR`.`booking_id` AND `TB`.`status` = :status %3\$s
				WHERE `TBR`.`room_id` = `t1`.`id` LIMIT 1) AS `nights`
			FROM `%4\$s` AS `t1`
			WHERE 1", $pjBookingRoomModel->getTable(), $pjBookingModel->getTable(), $conditions, $pjRoomModel->getTable());
		$result['room_cancelled_arr'] = $pjRoomModel->prepare($statement)->exec(array(
			'from' => $from,
			'to' => $to,
			'status' => 'cancelled'
		))->getData();
		
		$result['room_cancelled_arr'] = $this->calReportPrice($conditions, $result['room_cancelled_arr'], 'cancelled', $from, $to);
		
		$statement = sprintf("SELECT 1,
			(SELECT COUNT(*) FROM `%2\$s` AS t1 WHERE 1 AND t1.status=:status %4\$s AND 1 = (SELECT COUNT(*) FROM `%3\$s` AS t2 WHERE t2.booking_id = t1.id GROUP BY t2.booking_id LIMIT 1) LIMIT 1) AS `one_room`,
			(SELECT COUNT(*) FROM `%2\$s` AS t1 WHERE 1 AND t1.status=:status %4\$s AND 2 = (SELECT COUNT(*) FROM `%3\$s` AS t2 WHERE t2.booking_id = t1.id GROUP BY t2.booking_id LIMIT 1) LIMIT 1) AS `two_room`,
			(SELECT COUNT(*) FROM `%2\$s` AS t1 WHERE 1 AND t1.status=:status %4\$s AND 2 < (SELECT COUNT(*) FROM `%3\$s` AS t2 WHERE t2.booking_id = t1.id GROUP BY t2.booking_id LIMIT 1) LIMIT 1) AS `more_room`
			",
			$pjRoomModel->getTable(),
			$pjBookingModel->getTable(),
			$pjBookingRoomModel->getTable(),
			$conditions
		);
		$result['room_per_arr'] = $pjBookingModel->prepare($statement)->exec(array(
			'from' => $from,
			'to' => $to,
			'status' => 'confirmed'
		))->getData();
		
		return $result;
	}
	
	public function pjActionIndex()
	{
		$this->checkLogin();
		
		if ($this->isAdmin())
		{
			if (isset($_GET['from']) && isset($_GET['to']))
			{
				$result = $this->getReport($_GET['from'], $_GET['to'], $this->option_arr);
				
				$this->set('received_arr', $result['received_arr']);
				$this->set('confirmed_arr', $result['confirmed_arr']);
				$this->set('cancelled_arr', $result['cancelled_arr']);
				$this->set('night_arr', $result['night_arr']);
				$this->set('guest_arr', $result['guest_arr']);
				$this->set('total_adults', $result['total_adults']);
				$this->set('total_children', $result['total_children']);
				$this->set('total_nights', $result['total_nights']);
				$this->set('total_guests', $result['total_guests']);
				$this->set('total_bookings', $result['total_bookings']);
				$this->set('room_arr', $result['room_arr']);
				$this->set('room_confirmed_arr', $result['room_confirmed_arr']);
				$this->set('room_cancelled_arr', $result['room_cancelled_arr']);
				$this->set('room_per_arr', $result['room_per_arr']);
			}
			$this->appendJs('pjAdminReports.js');
		} else {
			$this->set('status', 2);
		}
	}
	
	public function pjActionPrintReport()
	{
		$this->checkLogin();
		
		if ($this->isAdmin())
		{
			$this->setLayout('pjActionPrint');
			
			if (isset($_GET['from']) && isset($_GET['to']))
			{
				$result = $this->getReport($_GET['from'], $_GET['to'], $this->option_arr);
				
				$this->set('received_arr', $result['received_arr']);
				$this->set('confirmed_arr', $result['confirmed_arr']);
				$this->set('cancelled_arr', $result['cancelled_arr']);
				$this->set('night_arr', $result['night_arr']);
				$this->set('guest_arr', $result['guest_arr']);
				$this->set('total_adults', $result['total_adults']);
				$this->set('total_children', $result['total_children']);
				$this->set('total_nights', $result['total_nights']);
				$this->set('total_guests', $result['total_guests']);
				$this->set('total_bookings', $result['total_bookings']);
				$this->set('room_arr', $result['room_arr']);
				$this->set('room_confirmed_arr', $result['room_confirmed_arr']);
				$this->set('room_cancelled_arr', $result['room_cancelled_arr']);
				$this->set('room_per_arr', $result['room_per_arr']);
			}				
		} else {
			$this->set('status', 2);
		}
	}
	
	private function calReportPrice($conditions, $arr, $type, $from, $to)
	{
		$pjBookingModel = pjBookingModel::factory();
		$pjRoomModel = pjRoomModel::factory();
		$pjBookingRoomModel = pjBookingRoomModel::factory();
		
		$status_str = '';
		if ($type != '')
		{
			$status_str = " AND `TB`.`status` = :type";
		}
		
		$sql = sprintf("SELECT `TBR`.*, `TB`.`date_from`, `TB`.`date_to` 
			FROM `%1\$s` AS `TBR`
			INNER JOIN `%2\$s` AS `TB` ON `TB`.`id` = `TBR`.`booking_id`$status_str %3\$s", $pjBookingRoomModel->getTable(), $pjBookingModel->getTable(), $conditions);
		$_arr = $pjBookingRoomModel->prepare($sql)->exec(array(
			'type' => $type,
			'from' => $from,
			'to' => $to
		))->getData();
		
		if (!empty($_arr))
		{
			$_price_arr = array();
			foreach ($_arr as $k => $v)
			{
				$price = $this->getRoomPrice($v['room_id'],
							$v['date_from'],
							$v['date_to'],
							($this->option_arr['o_price_based_on'] == 'nights'),
							$v['adults'], $v['children']);
				$_arr[$k]['price'] = $price;
				if (isset($_price_arr[$v['room_id']]))
				{
					$_price_arr[$v['room_id']] += $price;
				} else {
					$_price_arr[$v['room_id']] = $price;
				}
			}
			foreach ($arr as $k => $v)
			{
				if (isset($_price_arr[$v['id']]))
				{
					$arr[$k]['total'] = $_price_arr[$v['id']];
				} else {
					$arr[$k]['total'] = 0;
				}
			}
		} else {
			foreach ($arr as $k => $v)
			{
				$arr[$k]['total'] = 0;
			}
		}
		return $arr;
	}
}
?>