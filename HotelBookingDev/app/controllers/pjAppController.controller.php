<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjAppController extends pjController
{
	public $models = array();

	public $defaultLocale = 'admin_locale_id';
	
	public $defaultRoom = 'admin_room_id';
	
	public $defaultFields = 'fields';
	
	public $defaultFieldsIndex = 'fields_index';
	
	protected function loadSetFields($force=FALSE, $locale_id=NULL, $fields=NULL)
	{
		if (is_null($locale_id))
		{
			$locale_id = $this->getLocaleId();
		}
		
		if (is_null($fields))
		{
			$fields = $this->defaultFields;
		}
		
		$registry = pjRegistry::getInstance();
		if ($force
				|| !isset($_SESSION[$this->defaultFieldsIndex])
				|| $_SESSION[$this->defaultFieldsIndex] != $this->option_arr['o_fields_index']
				|| !isset($_SESSION[$fields])
				|| empty($_SESSION[$fields]))
		{
			pjAppController::setFields($locale_id);
	
			# Update session
			if ($registry->is('fields'))
			{
				$_SESSION[$fields] = $registry->get('fields');
			}
			$_SESSION[$this->defaultFieldsIndex] = $this->option_arr['o_fields_index'];
		}
	
		if (isset($_SESSION[$fields]) && !empty($_SESSION[$fields]))
		{
			# Load fields from session
			$registry->set('fields', $_SESSION[$fields]);
		}
				
		return TRUE;
	}
	
	public function isOneAdminReady()
	{
		return $this->isAdmin();
	}
	
	public function isInvoiceReady()
	{
		return $this->isAdmin() || $this->isEditor();
	}
	
	public function isCountryReady()
    {
    	return $this->isAdmin();
    }
    
	public function isPriceReady()
	{
		return $this->isAdmin();
	}
	
	public function isEditor()
	{
		return $this->getRoleId() == 2;
	}
	
	public static function setTimezone($timezone="UTC")
    {
    	if (in_array(version_compare(phpversion(), '5.1.0'), array(0,1)))
		{
			date_default_timezone_set($timezone);
		} else {
			$safe_mode = ini_get('safe_mode');
			if ($safe_mode)
			{
				putenv("TZ=".$timezone);
			}
		}
    }

	public static function setMySQLServerTime($offset="-0:00")
    {
		pjAppModel::factory()->prepare("SET SESSION time_zone = :offset;")->exec(compact('offset'));
    }
    
	public function setTime()
	{
		if (isset($this->option_arr['o_timezone']))
		{
			$offset = $this->option_arr['o_timezone'] / 3600;
			if ($offset > 0)
			{
				$offset = "-".$offset;
			} elseif ($offset < 0) {
				$offset = "+".abs($offset);
			} elseif ($offset === 0) {
				$offset = "+0";
			}
	
			pjAppController::setTimezone('Etc/GMT' . $offset);
			if (strpos($offset, '-') !== false)
			{
				$offset = str_replace('-', '+', $offset);
			} elseif (strpos($offset, '+') !== false) {
				$offset = str_replace('+', '-', $offset);
			}
			pjAppController::setMySQLServerTime($offset . ":00");
		}
	}
    
    public function beforeFilter()
    {
    	$this->appendJs('jquery.min.js', PJ_THIRD_PARTY_PATH . 'jquery/');
    	$dm = new pjDependencyManager(PJ_THIRD_PARTY_PATH);
    	$dm->load(PJ_CONFIG_PATH . 'dependencies.php')->resolve();
    	$this->appendJs('jquery-migrate.min.js', $dm->getPath('jquery_migrate'), FALSE, FALSE);
    	$this->appendJs('pjAdminCore.js');
    	$this->appendCss('reset.css');
    	
    	$this->appendJs('js/jquery-ui.custom.min.js', PJ_THIRD_PARTY_PATH . 'jquery_ui/');
		$this->appendCss('css/smoothness/jquery-ui.min.css', PJ_THIRD_PARTY_PATH . 'jquery_ui/');

		$this->appendCss('pj-all.css', PJ_FRAMEWORK_LIBS_PATH . 'pj/css/');
		$this->appendCss('admin.css');
		
    	if ($_GET['controller'] != 'pjInstaller')
		{
			$this->models['Option'] = pjOptionModel::factory();
			$this->option_arr = $this->models['Option']->getPairs(self::getForeignId());
			$this->set('option_arr', $this->option_arr);
			$this->setTime();
			
			if (!isset($_SESSION[$this->defaultLocale]))
			{
				$locale_arr = pjLocaleModel::factory()->where('is_default', 1)->limit(1)->findAll()->getData();
				if (count($locale_arr) === 1)
				{
					$this->setLocaleId($locale_arr[0]['id']);
				}
			}
			$this->loadSetFields();
		}
		
		if ($_GET['controller'] == 'pjPrice' && @$_GET['action'] == 'pjActionIndex')
		{
			if (isset($_GET['room_id']) && (int) $_GET['room_id'] > 0)
			{
				$_SESSION[$this->defaultRoom] = (int) $_GET['room_id'];
			}
			
			$this->set('room_arr', pjRoomModel::factory()
				->select('t1.*, t2.content AS name')
				->join('pjMultiLang', "t2.model='pjRoom' AND t2.foreign_id=t1.id AND t2.field='name' AND t2.locale='".$this->getLocaleId()."'", 'left outer')
				->orderBy('`name` ASC')
				->findAll()
				->getData()
			);
			
			if ($this->getForeignIdPrice() !== FALSE)
			{
				foreach ($this->tpl['room_arr'] as $room)
				{
					if ($room['id'] == $this->getForeignIdPrice())
					{
						$this->option_arr['o_bf_adults_max'] = $room['adults'];
						$this->option_arr['o_bf_children_max'] = $room['children'];
						$this->set('option_arr', $this->option_arr);
						break;
					}
				}
			}
		}
    }
    
    public function getForeignId()
    {
    	return 1;
    }
    
	public function getForeignIdPrice()
	{
		return isset($_SESSION[$this->defaultRoom]) && (int) $_SESSION[$this->defaultRoom] > 0 ? (int) $_SESSION[$this->defaultRoom] : false;
	}
    
    public static function setFields($locale)
    {
    	if(isset($_SESSION['lang_show_id']) && (int) $_SESSION['lang_show_id'] == 1)
		{
			$fields = pjMultiLangModel::factory()
				->select('CONCAT(t1.content, CONCAT(":", t2.id, ":")) AS content, t2.key')
				->join('pjField', "t2.id=t1.foreign_id", 'inner')
				->where('t1.locale', $locale)
				->where('t1.model', 'pjField')
				->where('t1.field', 'title')
				->findAll()
				->getDataPair('key', 'content');
		}else{
			$fields = pjMultiLangModel::factory()
				->select('t1.content, t2.key')
				->join('pjField', "t2.id=t1.foreign_id", 'inner')
				->where('t1.locale', $locale)
				->where('t1.model', 'pjField')
				->where('t1.field', 'title')
				->findAll()
				->getDataPair('key', 'content');
		}
		$registry = pjRegistry::getInstance();
		$tmp = array();
		if ($registry->is('fields'))
		{
			$tmp = $registry->get('fields');
		}
		$arrays = array();
		foreach ($fields as $key => $value)
		{
			if (strpos($key, '_ARRAY_') !== false)
			{
				list($prefix, $suffix) = explode("_ARRAY_", $key);
				if (!isset($arrays[$prefix]))
				{
					$arrays[$prefix] = array();
				}
				$arrays[$prefix][$suffix] = $value;
			}
		}
		require PJ_CONFIG_PATH . 'settings.inc.php';
		$fields = array_merge($tmp, $fields, $settings, $arrays);
		$registry->set('fields', $fields);
    }

    public static function jsonDecode($str)
	{
		$Services_JSON = new pjServices_JSON();
		return $Services_JSON->decode($str);
	}
	
	public static function jsonEncode($arr)
	{
		$Services_JSON = new pjServices_JSON();
		return $Services_JSON->encode($arr);
	}
	
	public static function jsonResponse($arr)
	{
		header("Content-Type: application/json; charset=utf-8");
		echo pjAppController::jsonEncode($arr);
		exit;
	}

	public function pjActionAfterInstall()
	{
		pjInvoiceConfigModel::factory()->set('id', 1)->modify(array(
			'o_booking_url' => "index.php?controller=pjAdminBookings&action=pjActionUpdate&uuid={ORDER_ID}"
		));
		
		$query = sprintf("UPDATE `%s`
			SET `content` = :content
			WHERE `model` = :model
			AND `foreign_id` = (SELECT `id` FROM `%s` WHERE `key` = :key LIMIT 1)
			AND `field` = :field",
			pjMultiLangModel::factory()->getTable(), pjFieldModel::factory()->getTable()
		);
		pjAppModel::factory()->prepare($query)->exec(array(
			'content' => 'Booking URL - Token: {ORDER_ID}',
			'model' => 'pjField',
			'field' => 'title',
			'key' => 'plugin_invoice_i_booking_url'
		));
		
		$query = sprintf("UPDATE `%s`
			SET `label` = :label
			WHERE `key` = :key
			LIMIT 1",
			pjFieldModel::factory()->getTable()
		);
		pjAppModel::factory()->prepare($query)->exec(array(
			'label' => 'Invoice plugin / Booking URL - Token: {ORDER_ID}',
			'key' => 'plugin_invoice_i_booking_url'
		));
	}
	
	public function pjActionCheckInstall()
	{
		$this->setLayout('pjActionEmpty');
		
		$result = array('status' => 'OK', 'code' => 200, 'text' => 'Operation succeeded', 'info' => array());
		$folders = array();
		foreach ($folders as $dir)
		{
			if (!is_writable($dir))
			{
				$result['status'] = 'ERR';
				$result['code'] = 101;
				$result['text'] = 'Permission requirement';
				$result['info'][] = sprintf('Folder \'<span class="bold">%1$s</span>\' is not writable. You need to set write permissions (chmod 777) to directory located at \'<span class="bold">%1$s</span>\'', $dir);
			}
		}
		
		return $result;
	}
	
	public function getLocaleId()
	{
		return isset($_SESSION[$this->defaultLocale]) && (int) $_SESSION[$this->defaultLocale] > 0 ? (int) $_SESSION[$this->defaultLocale] : false;
	}
	
	public function getDirection()
	{
		$dir = 'ltr';
		if($this->getLocaleId() != false)
		{
			$locale_arr = pjLocaleModel::factory()->find($this->getLocaleId())->getData();
			$dir = $locale_arr['dir'];
		}
		return $dir;
	}
	
	public function setLocaleId($locale_id)
	{
		$_SESSION[$this->defaultLocale] = (int) $locale_id;
	}
	
	public function getRoomPrice($room_id, $date_from, $date_to, $night_mode=true, $adults=null, $children=null)
    {
    	list($startY, $startM, $startD) = explode("-", $date_from);
    	$from = strtotime($date_from);
    	$to = strtotime($date_to);
    	$nights = ceil((strtotime($date_to) - $from) / 86400);
    	if (!$night_mode)
    	{
    		$nights += 1;
    	}
    	list($txtDayOfWeek, $startDay) = explode("-", date("D-w", $from));
    	$isoDayOfWeek = $startDay > 0 ? $startDay : 7;
    	
    	
    	$pjPriceModel = pjPriceModel::factory();
    	$price_arr = $pjPriceModel
    		->where('t1.foreign_id', $room_id)
    		
			->where('t1.date_from <=', $date_to)
			->where('t1.date_to >=', $date_from)
			->findAll()
			->getData();
    	
		$default_price_arr = $pjPriceModel
			->reset()
			->where('t1.foreign_id', $room_id)
			->where("(t1.date_from IS NULL OR t1.date_from = '0000-00-00')")
			->where("(t1.date_to IS NULL OR t1.date_to = '0000-00-00')")
			->findAll()
			->getData();
		
    	foreach ($price_arr as $k => $item)
    	{
			$price_arr[$k]['ts_from'] = strtotime($item['date_from']);
			$price_arr[$k]['ts_to'] = strtotime($item['date_to']);
    	}
    	$mask = array(1 => 'mon', 2 => 'tue', 3 => 'wed', 4 => 'thu', 5 => 'fri', 6 => 'sat', 7 => 'sun');

    	# Discounts
    	$discount_price = 0;
    	$pjDiscountPackageModel = pjDiscountPackageModel::factory();
    	$pjDiscountPackageItemModel = pjDiscountPackageItemModel::factory();
    	
		$d_arr = $pjDiscountPackageItemModel
			->select('t1.price AS total_price, t2.start_day, t2.end_day, t2.date_from, t2.date_to')
			->join('pjDiscountPackage', 't2.id=t1.package_id', 'inner')
			->where('t1.adults', $adults)
			->where('t1.children', $children)
			->where('t2.room_id', $room_id)
			->where('t2.date_from <=', $date_from)
			->where('t2.date_to >=', $date_to)
			->orderby('t2.date_from ASC')
			->findAll()
			->getData();
		
		if (empty($d_arr))
		{
			$d_arr = $pjDiscountPackageModel
				->select('t1.total_price, t1.start_day, t1.end_day, t1.date_from, t1.date_to')
				->where('t1.room_id', $room_id)
				->where('t1.date_from <=', $date_from)
				->where('t1.date_to >=', $date_to)
				->orderby('t1.date_from ASC')
				->findAll()
				->getData();
		}

    	if (!empty($d_arr))
		{
			$endDay = date("w", $to);
    		$_end = $endDay > 0 ? $endDay : 7;
    		$_start = $isoDayOfWeek;
			$tmp = $d_arr;
			$br = 0;
			foreach ($tmp as $k => $d_arr)
			{
				$d_start = $d_arr['start_day'] > 0 ? $d_arr['start_day'] : 7;
				$d_end = $d_arr['end_day'] > 0 ? $d_arr['end_day'] : 7;
				if ((float) $d_arr['total_price'] > 0 && ($br == 0 || ($_start == $d_start && $_end == $d_end)))
				{
		    		$discount_price = (float) $d_arr['total_price'];
		    		$discountPackageStart = strtotime($d_arr['date_from']);
					$discountPackageEnd = strtotime($d_arr['date_to']);
					
					$discount_ts = array();
					$discount_pretty = array();
					$d_start = $d_arr['start_day'];
					$d_end = $d_arr['end_day'];
					
					$scenario_1 = ($discountPackageEnd - $discountPackageStart > $to - $from);
					$scenario_2 = ($discountPackageEnd - $discountPackageStart < $to - $from);
					$scenario_3 = ($discountPackageEnd - $discountPackageStart == $to - $from);

					$daysAreInsufficient = (($to - $from)/86400 < ($discountPackageEnd - $discountPackageStart)/86400 && ($discountPackageEnd - $discountPackageStart)/86400 <= 7);

					if ($d_start != 7 && $d_end != 7
						&& (($scenario_1 && !$daysAreInsufficient)
							xor ($scenario_2 && !$daysAreInsufficient)
							xor ($scenario_3 && $discountPackageStart == $from && $discountPackageEnd == $to)
							)
					)
					{
						$d_start = $d_start > 0 ? $d_start : 7;
						$d_end = $d_end > 0 ? $d_end : 7;
						
						if ($d_start == $d_end)
						{
							$d_end -= 1;
							if ($d_end == 0)
							{
								$d_end += 7;
							}
						}
						
						$index = -1;
						$is_open = false;
						for ($i = $discountPackageStart; $i <= $discountPackageEnd; $i = strtotime('+1 day', $i))
						{
							$weekday = date("w", $i);
							$weekday = $weekday > 0 ? $weekday : 7;
							//if ($weekday == $d_start && ($discountPackageEnd - $i)/86400 +1 >= 7 - $d_start)
							if ($weekday == $d_start)
							{
								$is_open = true;
								$index += 1;
								$discount_pretty[$index] = array();
								$discount_ts[$index] = array();
							}
							
							if ($is_open)
							{
								if($i==$to && $night_mode){
								} else {
									$discount_pretty[$index][] = date("d.m.Y - l", $i);
									$discount_ts[$index][] = $i;
								}
							}
	
							if ($weekday == $d_end)
							{
								$is_open = false;
							}
						}
					}
					
					$br++;
				}
			}
		}
		
		# Discounts
    	$price = $discount = 0;
    	$j = $isoDayOfWeek;
    	$season = $pricePerNight = array();
    	#---
    	$ins = array();
		if (isset($discount_ts))
		{
			foreach ($discount_ts as $d_key => $times)
			{
				foreach ($times as $_k => $_v)
				{
					$ins[$d_key][$_k] = 0;
				}
			}
		}
    	foreach (range(1, $nights) as $i)
    	{
    		$date = mktime(0, 0, 0, $startM, $startD + ($i - 1), $startY);
    		
    		if (isset($discount_ts))
    		{
	    		foreach ($discount_ts as $d_key => $times)
	    		{
	    			if ($date >= min($times) && $date <= max($times))
	    			{
	    				$ins[$d_key][ array_search($date, $times) ] = 1;
	    				break;
	    			}
	    		}
    		}
    	}

    	$discount_multiple = 0;
		if ($discount_price > 0 && isset($discount_ts) && !empty($discount_ts) && !empty($ins))
		{
			foreach ($ins as $ii)
			{
				if (!in_array(0, $ii))
				{
					$discount_multiple += 1;
				}
			}
		}
    	#---
    	foreach (range(1, $nights) as $i)
    	{
    		if ($j > 7)
    		{
    			$j = 1;
    		}
    		$date = mktime(0, 0, 0, $startM, $startD + ($i - 1), $startY);
    		
    		$in = false;
    		if (isset($discount_ts) && !empty($ins))
    		{
	    		foreach ($discount_ts as $d_key => $times)
	    		{
	    			if ($date >= min($times) && $date <= max($times) && !in_array(0, $ins[$d_key]))
	    			{
	    				$in = true;
	    				break;
	    			}
	    		}
    		}

    		# Season price
    		foreach ($price_arr as $k => $item)
    		{
    			if ($date >= $item['ts_from'] && $date <= $item['ts_to'])
    			{
    				if (!is_null($adults) && (int) $adults > -1 && $adults != $item['adults'])
    				{
    					continue;
    				}
    				if (!is_null($children) && (int) $children > -1 && $children != $item['children'])
    				{
    					continue;
    				}
    				if (isset($discountPackageStart) && isset($discountPackageEnd) &&
    					$date >= $discountPackageStart && $date <= $discountPackageEnd &&
    					$in)
    				{
    				} else{
    					$price += $item[$mask[$j]];
    				}
    				$pricePerNights[$i] = $item[$mask[$j]];
    				$season[$i] = true;
    				break;
    			}
    		}
    		# Default price (season)
    		if (!isset($season[$i]))
    		{
	    		foreach ($price_arr as $k => $item)
	    		{
	    			if ($date >= $item['ts_from'] && $date <= $item['ts_to'])
	    			{
	    				if (isset($discountPackageStart) && isset($discountPackageEnd) &&
	    					$date >= $discountPackageStart && $date <= $discountPackageEnd &&
	    					$in)
	    				{
	    				} else{
	    					$price += $item[$mask[$j]];
	    				}
	    				$pricePerNights[$i] = $item[$mask[$j]];
	    				$season[$i] = true;
	    				break;
	    			}
	    		}
    		}
    		# Default price (adults, children)
    		if (!isset($season[$i]))
    		{
    			foreach ($default_price_arr as $k => $item)
	    		{
	    			if (!is_null($adults) && (int) $adults > -1 && $adults != $item['adults'])
    				{
    					continue;
    				}
    				if (!is_null($children) && (int) $children > -1 && $children != $item['children'])
    				{
    					continue;
    				}
    				if (isset($discountPackageStart) && isset($discountPackageEnd) &&
    					$date >= $discountPackageStart && $date <= $discountPackageEnd &&
    					$in)
    				{
    				} else{
    					$price += $item[$mask[$j]];
    				}
    				$pricePerNights[$i] = $item[$mask[$j]];
    				$season[$i] = true;
    				break;
	    		}
    		}
    		# Default price (general)
    		if (!isset($season[$i]))
    		{
	    		foreach ($default_price_arr as $k => $item)
	    		{
	    			if ((int) $item['adults'] === 0 && (int) $item['children'] === 0)
	    			{
	    				if (isset($discountPackageStart) && isset($discountPackageEnd) &&
	    					$date >= $discountPackageStart && $date <= $discountPackageEnd &&
	    					$in)
	    				{
	    				} else {
	    					$price += $item[$mask[$j]];
	    				}
	    				$pricePerNights[$i] = $item[$mask[$j]];
	    				$season[$i] = true;
	    				break;
	    			}
	    		}
    		}
    		$j++;
    	}
    	$pjDiscountFreeModel = pjDiscountFreeModel::factory();
		
		$free_arr = $pjDiscountFreeModel
			->select('t1.min_length, t1.max_length, t1.free_nights')
			->where('t1.room_id', $room_id)
			->where('t1.date_from <=', $date_to)
			->where('t1.date_to >=', $date_from)
			->where('t1.min_length <=', $nights)
			->where('t1.max_length >=', $nights)
			->limit(1)
			->findAll()
			->getData()
		;
		
		if (!empty($free_arr))
		{
			$free_arr = $free_arr[0];
			for ($i = $nights; $i > $nights - (int) $free_arr['free_nights']; $i--)
			{
				$discount += $pricePerNights[$i];
    		}
		}

		$price += $discount_price * $discount_multiple;
    	$price -= $discount;
    	$price = $price > 0 ? $price : 0;
    	return $price;
    }
    
	public static function getTokens($booking_arr, $option_arr)
    {
    	$room_arr = $extra_arr = array();
    	
    	if (isset($booking_arr['rooms']) && !empty($booking_arr['rooms']))
    	{
    		foreach ($booking_arr['rooms'] as $room)
    		{
    			$room_arr[] = sprintf("%u x %s", $room['cnt'], $room['name']);
    		}
    	}
    	
    	if (isset($booking_arr['extras']) && !empty($booking_arr['extras']))
    	{
    		foreach ($booking_arr['extras'] as $extra)
    		{
    			$extra_arr[] = $extra['name'];
    		}
    	}
    	
    	$rooms = join("; ", $room_arr);
    	$extras = join("; ", $extra_arr);
		$pt = __('personal_titles', true);
		$cancelURL = PJ_INSTALL_URL . 'index.php?controller=pjFront&action=pjActionCancel&cid='.$booking_arr['calendar_id'].'&id='.$booking_arr['id'].'&hash='.sha1($booking_arr['id'].$booking_arr['created'].PJ_SALT);

		$c_arrival = date($option_arr['o_time_format'], strtotime($booking_arr['c_arrival']));
		
    	$search = array(
    		'{Title}', '{FirstName}', '{LastName}', '{Phone}', '{Email}',
    		'{ArrivalTime}', '{Notes}', '{Company}', '{Address}', '{City}',
    		'{State}', '{Zip}', '{Country}',
    		'{PaymentMethod}', '{Deposit}',
    		'{SecurityDeposit}', '{Rooms}', '{Extras}',
    		'{CCType}', '{CCNum}', '{CCExpMonth}', '{CCExpYear}', '{CCSec}',
    		'{Tax}', '{Total}', '{RoomPrice}', '{ExtraPrice}', '{Discount}',
    		'{DateFrom}', '{DateTo}', '{BookingID}',
    		'{CancelURL}'
    	);
		$replace = array(
			@$pt[$booking_arr['c_title']], pjSanitize::clean($booking_arr['c_fname']), pjSanitize::clean($booking_arr['c_lname']), pjSanitize::clean($booking_arr['c_phone']), pjSanitize::clean($booking_arr['c_email']),
			$c_arrival, pjSanitize::clean($booking_arr['c_notes']), pjSanitize::clean($booking_arr['c_company']), pjSanitize::clean($booking_arr['c_address_1']), pjSanitize::clean($booking_arr['c_city']),
			$booking_arr['c_state'], $booking_arr['c_zip'], @$booking_arr['country'],
			$booking_arr['payment_method'], $booking_arr['deposit'] . " " . $option_arr['o_currency'],
			$booking_arr['security'] . " " . $option_arr['o_currency'], $rooms, $extras,
			$booking_arr['cc_type'], $booking_arr['cc_num'], $booking_arr['cc_exp_month'], $booking_arr['cc_exp_year'], $booking_arr['cc_code'],
			$booking_arr['tax'] . " " . $option_arr['o_currency'], $booking_arr['total'] . " " . $option_arr['o_currency'], $booking_arr['room_price'] . " " . $option_arr['o_currency'], $booking_arr['extra_price'] . " " . $option_arr['o_currency'], $booking_arr['discount'] . " " . $option_arr['o_currency'],
			pjUtil::formatDate($booking_arr['date_from'], 'Y-m-d', $option_arr['o_date_format']), pjUtil::formatDate($booking_arr['date_to'], 'Y-m-d', $option_arr['o_date_format']), $booking_arr['uuid'],
			$cancelURL
		);
		return compact('search', 'replace');
    }
    
	public static function getDiscount($data)
	{
		if (!isset($data['code']) || empty($data['code']))
		{
			// Missing params
			return array('status' => 'ERR', 'code' => 100, 'text' => 'Voucher code couldn\'t be empty.');
		}
		$arr = pjDiscountCodeModel::factory()
			->where('t1.promo_code', $data['code'])
			->findAll()
			->getData();
			
		if (empty($arr))
		{
			// Not found
			return array('status' => 'ERR', 'code' => 101, 'text' => 'Voucher not found.');
		}
		
		$rooms = array();
		foreach ($arr as $item)
		{
			if (strtotime($item['date_from']) <= strtotime($data['date_to']) && strtotime($item['date_to']) >= strtotime($data['date_from']))
			{
				$rooms[$item['room_id']] = array(
					'voucher_type' => $item['type'],
					'voucher_discount' => $item['discount']
				);
			}
		}
		if (empty($rooms))
		{
			return array('status' => 'ERR', 'code' => 102, 'text' => '');
		}
		
		return array(
			'status' => 'OK',
			'code' => 200,
			'text' => 'Voucher code has been applied.',
			'voucher_code' => $data['code'],
			'voucher_rooms' => $rooms
		);
	}

	protected function pjActionGenerateInvoice($booking_id)
	{
		if (!isset($booking_id) || (int) $booking_id <= 0)
		{
			return array('status' => 'ERR', 'code' => 400, 'text' => 'ID is not set ot invalid.');
		}
		$arr = pjBookingModel::factory()->find($booking_id)->getData();
		if (empty($arr))
		{
			return array('status' => 'ERR', 'code' => 404, 'text' => 'Booking not found.');
		}
		$map = array(
			'confirmed' => 'paid',
			'pending' => 'not_paid',
			'cancelled' => 'cancelled'
		);
		
		//$deposit = $this->option_arr['o_deposit_type'] == 'percent' ? ($arr['total'] * $this->option_arr['o_deposit']) / 100 : $this->option_arr['o_deposit'];
		
		$subtotal = $arr['deposit'] - ((float) $this->option_arr['o_tax'] * $arr['tax'])/100;
		$tax = 0;
		if ((float) $this->option_arr['o_tax'] > 0 && $subtotal > 0)
		{
			$tax = ($subtotal * (float) $this->option_arr['o_tax']) / 100;
		}
		
		$response = $this->requestAction(
			array(
	    		'controller' => 'pjInvoice',
	    		'action' => 'pjActionCreate',
	    		'params' => array(
    				'key' => md5($this->option_arr['private_key'] . PJ_SALT),
					
					'uuid' => pjUtil::uuid(),
					'order_id' => $arr['uuid'],
					'foreign_id' => $arr['calendar_id'],
					'issue_date' => ':CURDATE()',
					'due_date' => ':CURDATE()',
					'created' => ':NOW()',
					
					'status' => @$map[$arr['status']],
	    			'payment_method' => $arr['payment_method'],
					//'subtotal' => $deposit + $arr['security'],
					'subtotal' => $arr['room_price'],
					
					//'tax' => ($deposit * $this->option_arr['o_tax']) / 100,
					'tax' => $arr['tax'],
					
					'total' => $arr['total'],
					'paid_deposit' => $arr['deposit'],
					//'amount_due' => $arr['total'] + $arr['security'] + $arr['tax'] - $arr['deposit'],
					'amount_due' => $arr['total'] + $arr['security'] - $arr['deposit'],
					'currency' => $this->option_arr['o_currency'],
					'notes' => $arr['c_notes'],
					'b_billing_address' => $arr['c_address_1'],
					'b_company' => $arr['c_company'],
					'b_name' => $arr['c_fname'] . " " . $arr['c_lname'],
					'b_address' => $arr['c_address_2'],
					'b_street_address' => $arr['c_address_3'],
					'b_city' => $arr['c_city'],
					'b_state' => $arr['c_state'],
					'b_zip' => $arr['c_zip'],
					'b_phone' => $arr['c_phone'],
					'b_email' => $arr['c_email'],
					'items' => array(
						array(
							'name' => __('lblBookingDeposit', true),
							'description' => sprintf("%s - %s",
								pjUtil::formatDate($arr['date_from'], 'Y-m-d', $this->option_arr['o_date_format']),
								pjUtil::formatDate($arr['date_to'], 'Y-m-d', $this->option_arr['o_date_format'])
							),
							'qty' => 1,
							'unit_price' => $arr['deposit'],
							'amount' => $arr['deposit']
						),
						array(
							'name' => __('lblSecurityDeposit', true),
							'description' => NULL,
							'qty' => 1,
							'unit_price' => $arr['security'],
							'amount' => $arr['security']
						)
					)
					
    			)
    		),
    		array('return')
		);

		return $response;
	}
	
	public static function getMonthStatus($calendar_id, $month, $year)
	{
		$numOfDays = date("t", mktime(0, 0, 0, $month, 1, $year));
		$_arr = array();
		foreach (range(1, $numOfDays) as $i)
		{
			$_arr[date("Y-m-d", mktime(0, 0, 0, $month, $i, $year))] = array('code' => 1, 'text' => 'free');
		}
		//TODO
		return $_arr;
	}
	
	public function calPrices($booking_rooms, $date_from, $date_to, $voucher_arr, $extras, $nights, $option_arr, $from)
	{
		$_persons = 0;
		$room_price = $extra_price = $total = $security = $tax = $deposit = $discount = 0;
		$rooms_price_stack = array();
		
		if(isset($booking_rooms) && $from == 'front')
		{
			foreach ($booking_rooms as $room_id => $room_arr)
			{
				$rooms_price_stack[$room_id] = array();
				foreach ($room_arr as $index => $room_info)
				{
					$amount = $this->getRoomPrice($room_id, $date_from, $date_to, ($option_arr['o_price_based_on'] == 'nights'), $room_info['adults'], $room_info['children']);
					$room_price += $amount;
					$discount += pjUtil::getDiscount($amount, $room_id, $voucher_arr);
					$_persons += $room_info['adults'] + $room_info['children'];
					$rooms_price_stack[$room_id][$index] = $amount;
				}
			}
		}else{
			foreach ($booking_rooms as $room_info)
			{
				$amount = $this->getRoomPrice($room_info['room_id'], $date_from, $date_to, ($option_arr['o_price_based_on'] == 'nights'), $room_info['adults'], $room_info['children']);
				$room_price += $amount;
				$discount += pjUtil::getDiscount($amount, $room_info['room_id'], $voucher_arr);
				$_persons += $room_info['adults'] + $room_info['children'];
			}
		}
		
		if (isset($extras['extra_id']) && is_array($extras['extra_id']) && count($extras['extra_id']) > 0)
		{
			foreach ($extras['extra_id'] as $extra_id => $extra_str)
			{
				list($_per, $_price) = explode("|", $extra_str);
				switch ($_per)
				{
					case 'day':
						$extra_price += $_price * $nights;
						break;
					case 'booking':
						$extra_price += $_price;
						break;
					case 'person':
						$extra_price += $_price * $_persons;
						break;
					case 'day_person':
						$extra_price += $_price * $nights * $_persons;
						break;
				}
			}
		}else{
			if (isset($extras) && is_array($extras) && count($extras) > 0)
			{
				foreach ($extras as $extra)
				{
					switch ($extra['per'])
					{
						case 'day':
							$extra_price += $extra['price'] * $nights;
							break;
						case 'booking':
							$extra_price += $extra['price'];
							break;
						case 'person':
							$extra_price += $extra['price'] * $_persons;
							break;
						case 'day_person':
							$extra_price += $extra['price'] * $nights * $_persons;
							break;
					}
				}
			}
		}
		$total = $extra_price + $room_price;
		$total -= $discount;
		$total > 0 ? $total : 0;
		if ((float) $option_arr['o_tax'] > 0 && $total > 0)
		{
			$tax = ($total * (float) $option_arr['o_tax']) / 100;
			$total += $tax;
		}
			
		if ((float) $option_arr['o_security'] > 0 && $total > 0)
		{
			$security = (float) $option_arr['o_security'];
		}
			
		if (isset($option_arr['o_require_all_within'])
				&& (int) $option_arr['o_require_all_within'] > 0
				&& strtotime(date("Y-m-d")) + (int) $option_arr['o_require_all_within'] * 86400 >= strtotime($date_from))
		{
			$deposit = $total;
		} else {
			switch ($option_arr['o_deposit_type'])
			{
				case 'percent':
					$deposit = ($total * $option_arr['o_deposit']) / 100;
					break;
				case 'amount':
					$deposit = $option_arr['o_deposit'];
					break;
			}
		}
		$deposit += $security;
		
		$result['room_price'] = $room_price;
		$result['extra_price'] = $extra_price;
		$result['total'] = $total;
		$result['security'] = $security;
		$result['tax'] = $tax;
		$result['deposit'] = $deposit;
		$result['discount'] = $discount;
		$result['rooms_price_stack'] = $rooms_price_stack;
		
		return $result;
	}
}
?>