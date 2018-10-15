<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjFrontPublic extends pjFront
{	
	public function __construct()
	{
		parent::__construct();
		
		$this->setAjax(true);
		
		$this->setLayout('pjActionEmpty');
	}
	
	public function pjActionCheckout()
	{
		if ($this->isXHR() || isset($_GET['_escaped_fragment_']))
		{
			if (isset($_POST['step_checkout']))
			{
				if ((int) $this->option_arr['o_bf_captcha'] === 3 && (!isset($_POST['captcha'])  || (isset($_POST['captcha']) && empty($_POST['captcha'])) || (isset($_POST['captcha']) && !pjCaptcha::validate(strtoupper($_POST['captcha']), $_SESSION[$this->defaultCaptcha])) ))
				{
					pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 105, 'text' => __('system_105', true)));
				}
				
				$_SESSION[$this->defaultStore]['form'] = $_POST;
				$_SESSION[$this->defaultStore]['step_checkout'] = 1;
				
				pjAppController::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => __('system_200', true)));
			} else {
				$isRoomSelected = isset($_SESSION[$this->defaultStore], $_SESSION[$this->defaultStore]['all_rooms']) && !empty($_SESSION[$this->defaultStore]['all_rooms']);
				$isBookingAccepted = (int) $this->option_arr['o_accept_bookings'] === 1;
				$isAvailable = $this->pjActionCheckAvailability($_GET['cid']);
				if ($isRoomSelected && $isBookingAccepted && $isAvailable)
				{
					$this->set('country_arr', pjCountryModel::factory()
						->select('t1.*, t2.content AS name')
						->join('pjMultiLang', "t2.model='pjCountry' AND t2.foreign_id=t1.id AND t2.field='name' AND t2.locale='".$this->getLocaleId()."'", 'left outer')
						->where('t1.status', 'T')
						->orderBy('`name` ASC')
						->findAll()
						->getData()
					);
					
					$this->set('room_arr', pjRoomModel::factory()
						->select('t1.*, t2.content AS name')
						->join('pjMultiLang', "t2.model='pjRoom' AND t2.foreign_id=t1.id AND t2.field='name' AND t2.locale='".$this->getLocaleId()."'", 'left outer')
						->whereIn('t1.id', array_keys($_SESSION[$this->defaultStore]['all_rooms']))
						->where('t1.calendar_id', $_GET['cid'])
						->findAll()
						->getDataPair('id')
					);
					
					$this->set('extra_arr', pjExtraModel::factory()
						->select('t1.*, t2.content AS name')
						->join('pjMultiLang', "t2.model='pjExtra' AND t2.foreign_id=t1.id AND t2.field='name' AND t2.locale='".$this->getLocaleId()."'", 'left outer')
						->where('t1.calendar_id', $_GET['cid'])
						->where('t1.status', 'T')
						->findAll()
						->getData()
					);
					
					$STORE = $_SESSION[$this->defaultStore];
					$session_prices = $this->calPrices($STORE['all_rooms'], $STORE['date_from'], $STORE['date_to'], @$_SESSION[$this->defaultVoucher], isset($STORE['extras']) ? $STORE['extras'] : array(), $STORE['_nights'], $this->option_arr, 'front');
					$this->set('session_prices', $session_prices);
					
					$this->set('calendar_arr', pjCalendarModel::factory()
							->select('t1.*, t2.content AS terms_url, t3.content AS terms_body')
							->join('pjMultiLang', "t2.model='pjCalendar' AND t2.foreign_id=t1.id AND t2.field='terms_url' AND t2.locale='".$this->getLocaleId()."'", 'left outer')
							->join('pjMultiLang', "t3.model='pjCalendar' AND t3.foreign_id=t1.id AND t3.field='terms_body' AND t3.locale='".$this->getLocaleId()."'", 'left outer')
							->find($_GET['cid'])
							->getData()
					);
					
					if (isset($_SESSION[$this->defaultStore]['step_search']) && isset($_SESSION[$this->defaultStore]['step_rooms']))
					{
						$_SESSION[$this->defaultStore]['step_extras'] = 1;
					}
					$this->set('status', 'OK');
				} else {
					$this->set('status', 'ERR');
					switch (FALSE)
					{
						case $isRoomSelected:
							$this->set('code', 108)->set('text', __('system_108', true));
							break;
						case $isBookingAccepted:
							$this->set('code', 109)->set('text', __('system_109', true));
							break;
						case $isAvailable:
							$this->set('code', 110)->set('text', __('system_110', true));
							break;
					}
				}
			}
		}
	}
	
	public function pjActionExtras()
	{
		if ($this->isXHR() || isset($_GET['_escaped_fragment_']))
		{
			$_rooms = $_adults = $_children = 0;
			$rooms_price = $discount = 0;
			if (isset($_SESSION[$this->defaultStore]) &&
				isset($_SESSION[$this->defaultStore]['all_rooms']) &&
				!empty($_SESSION[$this->defaultStore]['all_rooms']) &&
				(int) $this->option_arr['o_accept_bookings'] === 1 &&
				$this->pjActionCheckAvailability($_GET['cid']))
			{
				
				$num_nights = ceil((strtotime($_SESSION[$this->defaultStore]['date_to']) - strtotime($_SESSION[$this->defaultStore]['date_from'])) / 86400);
				if ($this->option_arr['o_price_based_on'] == 'days')
				{
					$num_nights += 1;
				}
				$_SESSION[$this->defaultStore]['_nights'] = $num_nights;
				
				
				$rooms_price_stack = array();
				foreach ($_SESSION[$this->defaultStore]['all_rooms'] as $room_id => $room_arr)
				{
					$rooms_price_stack[$room_id] = array();
					foreach ($room_arr as $index => $room_info)
					{
						$_rooms += 1;
						$_adults += $room_info['adults'];
						$_children += $room_info['children'];
					}
				}

				$_SESSION[$this->defaultStore]['_rooms'] = $_rooms;
				$_SESSION[$this->defaultStore]['_persons'] = $_adults + $_children;
				$_SESSION[$this->defaultStore]['_adults'] = $_adults;
				$_SESSION[$this->defaultStore]['_children'] = $_children;
				$_SESSION[$this->defaultStore]['step_rooms'] = 1;
				
				$STORE = $_SESSION[$this->defaultStore];
				$session_prices = $this->calPrices($STORE['all_rooms'], $STORE['date_from'], $STORE['date_to'], @$_SESSION[$this->defaultVoucher], isset($STORE['extras']) ? $STORE['extras'] : array(), $STORE['_nights'], $this->option_arr, 'front');
				$this->set('session_prices', $session_prices);
				
				$this->set('room_arr', pjRoomModel::factory()
					->select('t1.*, t2.content AS name')
					->join('pjMultiLang', "t2.model='pjRoom' AND t2.foreign_id=t1.id AND t2.field='name' AND t2.locale='".$this->getLocaleId()."'", 'left outer')
					->whereIn('t1.id', array_keys($_SESSION[$this->defaultStore]['all_rooms']))
					->where('t1.calendar_id', $_GET['cid'])
					->findAll()
					->getDataPair('id')
				);
				
				$this->set('extra_arr', pjExtraModel::factory()
					->select('t1.*, t2.content AS name')
					->join('pjMultiLang', "t2.model='pjExtra' AND t2.foreign_id=t1.id AND t2.field='name' AND t2.locale='".$this->getLocaleId()."'", 'left outer')
					->where('t1.calendar_id', $_GET['cid'])
					->where('t1.status', 'T')
					->findAll()
					->getData()
				);
				
				$this->set('status', 'OK');
			} else {
				$this->set('status', 'ERR')->set('code', 101)->set('text', __('system_101', true));
			}
		}
	}
	
	public function pjActionPreview()
	{
		if ($this->isXHR() || isset($_GET['_escaped_fragment_']))
		{
			if (isset($_SESSION[$this->defaultStore]['form']['c_country']) && (int) $_SESSION[$this->defaultStore]['form']['c_country'] > 0)
			{
				$this->set('country_arr', pjCountryModel::factory()
					->select('t1.*, t2.content AS name')
					->join('pjMultiLang', "t2.model='pjCountry' AND t2.foreign_id=t1.id AND t2.field='name' AND t2.locale='".$this->getLocaleId()."'", 'left outer')
					->find($_SESSION[$this->defaultStore]['form']['c_country'])
					->getData()
				);
			}
			
			if (isset($_SESSION[$this->defaultStore]) && isset($_SESSION[$this->defaultStore]['step_checkout']) &&
				isset($_SESSION[$this->defaultStore]['all_rooms']) &&
				!empty($_SESSION[$this->defaultStore]['all_rooms']) &&
				(int) $this->option_arr['o_accept_bookings'] === 1 &&
				$this->pjActionCheckAvailability($_GET['cid']))
			{
				
				$num_nights = ceil((strtotime($_SESSION[$this->defaultStore]['date_to']) - strtotime($_SESSION[$this->defaultStore]['date_from'])) / 86400);
				if ($this->option_arr['o_price_based_on'] == 'days')
				{
					$num_nights += 1;
				}
				$_SESSION[$this->defaultStore]['_nights'] = $num_nights;
									
				$this->set('status', 'OK');

				$this->set('room_arr', pjRoomModel::factory()
					->select(sprintf("t1.*, t2.content AS `name`,
						(SELECT `small_path` FROM `%1\$s` WHERE `foreign_id` = t1.id ORDER BY `sort` ASC LIMIT 1) AS `image`
						", pjGalleryModel::factory()->getTable()))
					->join('pjMultiLang', "t2.model='pjRoom' AND t2.foreign_id=t1.id AND t2.field='name' AND t2.locale='".$this->getLocaleId()."'", 'left outer')
					->whereIn('t1.id', array_keys($_SESSION[$this->defaultStore]['all_rooms']))
					->where('t1.calendar_id', $_GET['cid'])
					->findAll()
					->getDataPair('id')
				);
				
				$STORE = $_SESSION[$this->defaultStore];
				$session_prices = $this->calPrices($STORE['all_rooms'], $STORE['date_from'], $STORE['date_to'], @$_SESSION[$this->defaultVoucher], isset($STORE['extras']) ? $STORE['extras'] : array(), $STORE['_nights'], $this->option_arr, 'front');
				$this->set('session_prices', $session_prices);
				
			} else {
				$this->set('status', 'ERR')->set('code', 103)->set('text', __('system_103', true));
			}
		}
	}
	
	public function pjActionRooms()
	{
		if ($this->isXHR() || isset($_GET['_escaped_fragment_']))
		{
			if (isset($_GET['date_from'], $_GET['date_to'], $_GET['guests']))
			{
				if($this->option_arr['o_price_based_on'] == "nights" && $_GET['date_from'] == $_GET['date_to'])
				{
					$this->set('status', 'ERR')->set('code', 132)->set('text', __('system_132', true));
				}else{
					$date_from = pjUtil::formatDate($_GET['date_from'], $this->option_arr['o_date_format']);
					$date_to = pjUtil::formatDate($_GET['date_to'], $this->option_arr['o_date_format']);
					$guests = (int) $_GET['guests'];
					
					if (isset($_SESSION[$this->defaultStore]) &&
						isset($_SESSION[$this->defaultStore]['date_from']) &&
						isset($_SESSION[$this->defaultStore]['date_to']) &&
						isset($_SESSION[$this->defaultStore]['guests']) &&
						(
							$_SESSION[$this->defaultStore]['date_from'] != $date_from ||
							$_SESSION[$this->defaultStore]['date_to'] != $date_to ||
							$_SESSION[$this->defaultStore]['guests'] != $guests
						))
					{
						$_SESSION[$this->defaultStore] = array();
						$_SESSION[$this->defaultVoucher] = array();
					}
					
					if (!isset($_SESSION[$this->defaultStore]))
					{
						$_SESSION[$this->defaultStore] = array();
					}
					$_SESSION[$this->defaultStore]['date_from'] = $date_from;
					$_SESSION[$this->defaultStore]['date_to'] = $date_to;
					$_SESSION[$this->defaultStore]['guests'] = $guests;
					$_SESSION[$this->defaultStore]['step_search'] = 1;
				
					$num_nights = ceil((strtotime($_SESSION[$this->defaultStore]['date_to']) - strtotime($_SESSION[$this->defaultStore]['date_from'])) / 86400);
					if ($this->option_arr['o_price_based_on'] == 'days')
					{
						$num_nights += 1;
					}
					$_SESSION[$this->defaultStore]['_nights'] = $num_nights;
					$_SESSION[$this->defaultStore]['_start_on'] = date("w", strtotime($date_from));
					
					$nightMode = $this->option_arr['o_price_based_on'] == "nights";
					
					$pjRoomModel = pjRoomModel::factory();
					$arr = $pjRoomModel
						->select(sprintf("t1.*, t2.content AS `name`, t3.content AS `description`,
							(SELECT `medium_path` FROM `%1\$s` WHERE `foreign_id` = `t1`.`id` ORDER BY `sort` ASC LIMIT 1) AS `image`,
							(SELECT GROUP_CONCAT(COALESCE(`small_path`, '') SEPARATOR '~:~') FROM `%1\$s` WHERE `foreign_id` = `t1`.`id` GROUP BY `foreign_id` LIMIT 3) AS `gallery`,
							(SELECT GROUP_CONCAT(COALESCE(`medium_path`, '') SEPARATOR '~:~') FROM `%1\$s` WHERE `foreign_id` = `t1`.`id` GROUP BY `foreign_id` LIMIT 3) AS `medium`,
							(SELECT GROUP_CONCAT(COALESCE(`large_path`, '') SEPARATOR '~:~') FROM `%1\$s` WHERE `foreign_id` = `t1`.`id` GROUP BY `foreign_id` LIMIT 3) AS `large`,
							(SELECT GROUP_CONCAT(COALESCE(`alt`, '') SEPARATOR '~:~') FROM `%1\$s` WHERE `foreign_id` = `t1`.`id` GROUP BY `foreign_id` LIMIT 3) AS `alt`,
							(SELECT MIN(LEAST(`mon`, `tue`, `wed`, `thu`, `fri`, `sat`, `sun`)) FROM `%2\$s` AS `TP` WHERE `foreign_id` = `t1`.`id` AND ((`TP`.date_from <= '$date_to' ANd `TP`.date_to >= '$date_from') OR ( (`TP`.date_from IS NULL OR `TP`.date_from = '0000-00-00') OR (`TP`.date_to IS NULL OR `TP`.date_to = '0000-00-00') )) LIMIT 1) AS `price_from`
							",
							pjGalleryModel::factory()->getTable(),
							pjPriceModel::factory()->getTable()
						))
						->join('pjMultiLang', "t2.model='pjRoom' AND t2.foreign_id=t1.id AND t2.field='name' AND t2.locale='".$this->getLocaleId()."'", 'left outer')
						->join('pjMultiLang', "t3.model='pjRoom' AND t3.foreign_id=t1.id AND t3.field='description' AND t3.locale='".$this->getLocaleId()."'", 'left outer')
						->where('t1.id > 0')
						->where('t1.calendar_id', $_GET['cid'])
						->where('t1.max_people * t1.cnt >=', $_GET['guests'])
						->findAll()
						->toArray('gallery', '~:~')
						->toArray('medium', '~:~')
						->toArray('large', '~:~')
						->toArray('alt', '~:~')
						->getData();
					# --
					$hours = $this->option_arr['o_pending_time'] / 60;
					$remainder = $this->option_arr['o_pending_time'] % 60;
					if ($remainder === 0)
					{
						$pending_time = sprintf("%u:00:00", $hours);
					} else {
						$pending_time = sprintf("%u:%u:00", $hours, $remainder);
					}
					$tmp = pjBookingRoomModel::factory()
						->select("t1.*, t2.date_from, t2.date_to")
						->join('pjBooking', sprintf("t2.id=t1.booking_id AND (t2.status = 'confirmed' OR (t2.status = 'pending' AND t2.created >= SUBTIME(NOW(), '%5\$s'))) AND t2.date_from %3\$s '%2\$s' AND t2.date_to %4\$s '%1\$s'",
							$pjRoomModel->escapeStr($date_from),
							$pjRoomModel->escapeStr($date_to),
							$nightMode ? "<" : "<=",
							$nightMode ? ">" : ">=",
							$pjRoomModel->escapeStr($pending_time)), 'inner')
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
							if (($z >= $dt_from && $z <= $dt_to) === FALSE)
							{
								continue;
							}
							if ($z == $to && $nightMode)
							{
								continue;
							}
							
							$iso = date("Y-m-d", $z);
							$sum[$value["room_id"]][$iso] += 1;
						}
					}
					# Restrictions
					$restrictions = pjRestrictionRoomModel::factory()
						->select('t3.room_id, COUNT(t3.room_id) AS `cnt`')
						->join('pjRestriction', "t2.id=t1.restriction_id", 'inner')
						->join('pjRoomNumber', "t3.id=t1.room_number_id", 'inner')
						->join('pjRoom', "t4.id=t3.room_id", 'inner')
						->where(sprintf('t2.date_from %s', $nightMode ? "<" : "<="), $date_to)
						->where(sprintf('t2.date_to %s', $nightMode ? ">" : ">="), $date_from)
						->groupBy('t3.room_id')
						->findAll()
						->getDataPair('room_id', 'cnt');
					# Restrictions
					
					foreach ($arr as $k => $room)
					{
						$arr[$k]['max_bookings'] = isset($sum[$room['id']]) && !empty($sum[$room['id']]) ? ceil(max($sum[$room['id']])) : 0;
						$arr[$k]['unavailable_cnt'] = isset($restrictions[$room['id']]) ? (int) $restrictions[$room['id']] : 0;
						$arr[$k]['real_price_from'] = $this->getRoomPrice(
								$room['id'],
								$date_from,
								$date_to,
								($this->option_arr['o_price_based_on'] == 'nights'),
								null,
								null
						);
					}
					# --
	
					$limit_arr = array();
					if (!empty($date_to) && !empty($date_from))
					{
						 $limit_arr = pjLimitModel::factory()
							->where('t1.date_from <=', $date_to)
							->where('t1.date_to >=', $date_from)
							->findAll()
							->getData();
					}
					$this->set('limit_arr', $limit_arr);
						
					$this->set('arr', $arr)->set('status', 'OK');
				}
			} else {
				$this->set('status', 'ERR')->set('code', 100)->set('text', __('system_100', true));
			}
		}
	}
	
	public function pjActionRouter()
	{
		$this->setAjax(false);

		if (isset($_GET['_escaped_fragment_']))
		{
			$templates = array('Search', 'Rooms', 'Extras', 'Checkout', 'Preview', 'Booking');
			preg_match('/^\/(\w+).*/', $_GET['_escaped_fragment_'], $m);
			if (isset($m[1]) && in_array($m[1], $templates))
			{
				$template = 'pjAction'.$m[1];
			
				if (method_exists($this, $template))
				{
					$this->$template();
				}
				$this->setTemplate('pjFrontPublic', $template);
			}
		}
	}
	
	public function pjActionSearch()
	{
		if ($this->isXHR() || isset($_GET['_escaped_fragment_']))
		{
			
		}
	}
	
	public function pjActionBooking()
	{
		if ($this->isXHR() || isset($_GET['_escaped_fragment_']))
		{
			$invoice_arr = pjInvoiceModel::factory()
				->where('t1.uuid', $_GET['uuid'])
				->limit(1)
				->findAll()
				->getDataIndex(0);
			
			if ($invoice_arr !== FALSE && !empty($invoice_arr))
			{
				$booking_arr = pjBookingModel::factory()
					->where('t1.uuid', $invoice_arr['order_id'])
					->limit(1)
					->findAll()
					->getDataIndex(0);
			
				if ($booking_arr !== FALSE && !empty($booking_arr))
				{
					switch ($booking_arr['payment_method'])
					{
						case 'paypal':
							$this->set('params', array(
								'name' => 'hbPaypal',
								'id' => 'hbPaypal',
								'target' => '_self',
								'business' => $this->option_arr['o_paypal_address'],
								'item_name' => $booking_arr['uuid'],
								//'custom' => $booking_arr['id'],
								'custom' => $invoice_arr['uuid'],
								'amount' => $booking_arr['deposit'],
								'currency_code' => $this->option_arr['o_currency'],
								'return' => $this->option_arr['o_thankyou_page'],
								'notify_url' => PJ_INSTALL_URL . 'index.php?controller=pjFront&action=pjActionConfirmPaypal&cid=' . $_GET['cid'],
								'submit' => __('front_payment_paypal_submit', true),
								'submit_class' => 'btn btn-default'
							));
							break;
						case 'authorize':
							$this->set('params', array(
								'name' => 'hbAuthorize',
								'id' => 'hbAuthorize',
								'timezone' => $this->option_arr['o_authorize_tz'],
								'transkey' => $this->option_arr['o_authorize_key'],
								'x_login' => $this->option_arr['o_authorize_mid'],
								'x_description' => $booking_arr['uuid'],
								'x_amount' => $booking_arr['deposit'],
								//'x_invoice_num' => $booking_arr['id'],
								'x_invoice_num' => $invoice_arr['uuid'],
								'x_receipt_link_url' => $this->option_arr['o_thankyou_page'],
								'x_relay_url' => PJ_INSTALL_URL . 'index.php?controller=pjFront&action=pjActionConfirmAuthorize&cid=' . $_GET['cid'],
								'submit' => __('front_payment_authorize_submit', true),
								'submit_class' => 'btn btn-default'
							));
							break;
					}
					
					$this->set('booking_arr', $booking_arr);
				}
				$this->set('invoice_arr', $invoice_arr);
			}
		}
	}
}
?>