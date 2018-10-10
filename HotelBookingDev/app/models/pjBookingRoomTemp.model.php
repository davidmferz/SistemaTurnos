<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjBookingRoomTempModel extends pjAppModel
{
	protected $primaryKey = 'id';
	
	protected $table = 'bookings_rooms_temp';
	
	protected $schema = array(
		array('name' => 'id', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'booking_id', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'room_id', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'room_number_id', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'hash', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'adults', 'type' => 'smallint', 'default' => ':NULL'),
		array('name' => 'children', 'type' => 'smallint', 'default' => ':NULL'),
		array('name' => 'price', 'type' => 'decimal', 'default' => ':NULL')
	);
	
	public static function factory($attr=array())
	{
		return new pjBookingRoomTempModel($attr);
	}
	
	public function getInfo($room_id, $date_from, $date_to, $option_arr=array(), $id=NULL)
	{
		$arr = array();
		
		$this->reset()
			->join('pjBooking', "t2.id=t1.booking_id AND t2.status!='cancelled'", 'inner');
		if (!is_null($id))
		{
			$this->where('t1.booking_id !=', $id);
		}
		
		$r_arr = $this
			->select('t1.*, t2.date_from, t2.date_to, t2.status')
			->where('t1.room_id', $room_id)
			->findAll()
			->getData();

		if (empty($r_arr))
		{
			return array();
		}
		
		$nightlyMode = $option_arr['o_price_based_on'] == 'nights';
		$offset = $nightlyMode ? 86400 : 0;
		
		$status = 'cancelled';
		$info = $this
			->prepare(sprintf("SELECT MIN(`t2`.`date_from`) AS `min`, MAX(`t2`.`date_to`) AS `max`,
					DATEDIFF(MAX(`t2`.`date_to`), MIN(`t2`.`date_from`)) AS `diff`
				FROM `%s` AS `t1`
				INNER JOIN `%s` AS `t2` ON `t2`.`id` = `t1`.`booking_id` AND `t2`.`status` != :status
				WHERE `t1`.`room_id` = :room_id
				%s
				AND ((`t2`.`date_from` BETWEEN :date_from AND :date_to) OR (`t2`.`date_to` BETWEEN :date_from AND :date_to))
				LIMIT 1", $this->getTable(), pjBookingModel::factory()->getTable(), (!is_null($id) ? " AND `id` != :id" : NULL)))
			->exec(array_merge(compact('room_id', 'status', 'date_from', 'date_to'), array('id' => !is_null($id) ? $id : NULL)))
			->getData();

		if (count($info) !== 1 || !isset($info[0]))
		{
			return array();
		}
		
		$info = $info[0];
		if ((int) $info['diff'] <= 0)
		{
			return array();
		}
		
		list($y, $m, $d) = explode("-", $info['min']);
		foreach (range(0, $info['diff']) as $i)
		{
			$timestamp = mktime(0, 0, 0, $m, $d + $i, $y);
			$count = 0;
			#$arr[$timestamp]['count'] = 0; //availability
			$arr[$timestamp]['status'] = 1; //availability
			//$arr[$timestamp]['date'] = date("Y-m-d", $timestamp); //availability
			$arr[$timestamp]['is_change_over'] = 0;//$i > 0 && $i < $info['diff'] ? 0 : 1; //availability
			$confirmed = $pending = 0;
			foreach ($r_arr as $booking)
			{
				list($sy, $sm, $sd) = explode("-", $booking['date_from']);
				list($ey, $em, $ed) = explode("-", $booking['date_to']);
				
				$b_from = mktime(0, 0, 0, $sm, $sd, $sy);
				$b_to = mktime(0, 0, 0, $em, $ed, $ey);
				if ($b_from < $timestamp && $b_to - $offset > $timestamp)
				{
					#$arr[$timestamp]['count'] += 1; //availability
					$count += 1;
					$arr[$timestamp]['booking_id'] = $booking['id'];
					switch ($booking['status'])
					{
						case 'confirmed':
							$confirmed += 1;
							break;
						case 'pending':
							$pending += 1;
							break;
					}
				}
				if ($b_from < $timestamp && $b_to > $timestamp)
				{
					$arr[$timestamp]['in'] = array('id' => $booking['id'], 'status' => $booking['status']);
				}
				if ($b_from == $timestamp)
				{
					$arr[$timestamp]['start'] = array('id' => $booking['id'], 'status' => $booking['status']);
					#$arr[$timestamp]['count'] += 1; //availability
					$count += 1;
					$arr[$timestamp]['booking_id'] = $booking['id'];
					switch ($booking['status'])
					{
						case 'confirmed':
							$confirmed += 1;
							break;
						case 'pending':
							$pending += 1;
							break;
					}
				}
				if ($b_to - $offset == $timestamp && $b_from != $timestamp)
				{
					#$arr[$timestamp]['count'] += 1; //availability
					$count += 1;
					$arr[$timestamp]['booking_id'] = $booking['id'];
					switch ($booking['status'])
					{
						case 'confirmed':
							$confirmed += 1;
							break;
						case 'pending':
							$pending += 1;
							break;
					}
				}
				if ($b_to == $timestamp)
				{
					$arr[$timestamp]['end'] = array('id' => $booking['id'], 'status' => $booking['status']);
				}
				
				if ($nightlyMode && ($b_from == $timestamp || $b_to == $timestamp))
				{
					$arr[$timestamp]['is_change_over'] = 1;
				}
			}
			
			if ($count == 0)
			{
				//free
				$arr[$timestamp]['status'] = 1;
			#} elseif ($count > 0 && $count < (int) $option_arr['o_bookings_per_day']) {
			} elseif ($count > 0 && $count < 1) {
				//partial booked
				$arr[$timestamp]['status'] = 3;
			} else {
				//fully booked
				$arr[$timestamp]['status'] = 2;
				unset($arr[$timestamp]['booking_id']);
			}
			$arr[$timestamp]['confirmed'] = $confirmed;
			$arr[$timestamp]['pending'] = $pending;
			$arr[$timestamp]['dt'] = date("d.m.Y", $timestamp);
		}
		
		return $arr;
	}
}
?>