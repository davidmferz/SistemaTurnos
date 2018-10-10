<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjFront extends pjAppController
{
	public $defaultFields = 'fields';
	
	public $defaultCaptcha = 'HBooking_Captcha';
	
	public $defaultLocale = 'HBooking_LocaleId';
	
	public $defaultStore = 'HBooking_Store';
	
	public $defaultVoucher = 'HBooking_Voucher';
	
	private $defaultStatus = 'HBooking_Status';
	
	public function __construct()
	{
		$this->setLayout('pjActionFront');
		self::allowCORS();
		
		if (!isset($_SESSION[$this->defaultStatus]) || !in_array($_SESSION[$this->defaultStatus], array(1,0)))
		{
			$_SESSION[$this->defaultStatus] = 0;
			$login_api = dirname(dirname(PJ_INSTALL_PATH)) . '/login-api.php';
			if (is_file($login_api))
			{
				$apiLogin = include $login_api;
				if (is_object($apiLogin))
				{
					$apiData = $apiLogin->checkStatus()->getResult();
					$this->set('apiData', @$apiData['result']);
						
					if (!empty($apiData)
							&& isset($apiData['status'], $apiData['result'])
							&& $apiData['status'] == 'OK'
							&& isset($apiData['result']['status'], $apiData['result']['days_left'])
							&& $apiData['result']['status'] == 'trial'
							&& $apiData['result']['days_left'] < 0
					)
					{
						$_SESSION[$this->defaultStatus] = 1;
					}
				}
			}
		}
	}
	
	public function afterFilter()
	{
		if (!isset($_GET['hide']) || (isset($_GET['hide']) && (int) $_GET['hide'] !== 1) &&
			in_array($_GET['action'], array('pjActionSearch', 'pjActionRooms',
				'pjActionExtras', 'pjActionCheckout', 'pjActionPreview', 'pjActionBooking')))
		{
			$locale_arr = pjLocaleModel::factory()->select('t1.*, t2.file, t2.title')
				->join('pjLocaleLanguage', 't2.iso=t1.language_iso', 'left outer')
				->where('t2.file IS NOT NULL')
				->orderBy('t1.sort ASC')->findAll()->getData();
			
			$this->set('locale_arr', $locale_arr);
		}
	}
	
	public function beforeFilter()
	{
		if (isset($_GET['cid']) && (int) $_GET['cid'] > 0)
		{
			$OptionModel = pjOptionModel::factory();
			$this->option_arr = $OptionModel->getPairs($_GET['cid']);
			$this->set('option_arr', $this->option_arr);
			$this->setTime();
			
			self::setLastUsage($this->option_arr, $this->getForeignId());
		}
		
		if (!isset($_SESSION[$this->defaultLocale]))
		{
			$locale_arr = pjLocaleModel::factory()->where('is_default', 1)->limit(1)->findAll()->getData();
			if (count($locale_arr) === 1)
			{
				$this->setLocaleId($locale_arr[0]['id']);
			}
		}
		if(isset($_GET['locale']) && (int) $_GET['locale'] > 0 && (!isset($_SESSION[$this->defaultLocale]) || (isset($_SESSION[$this->defaultLocale]) && $_SESSION[$this->defaultLocale] != (int) $_GET['locale'] ) ) )
		{
			$_SESSION[$this->defaultLocale] = (int) $_GET['locale'];
		}
		
		if (!in_array($_GET['action'], array('pjActionLoadCss')))
		{
			if(isset($_GET['locale']) && (int) $_GET['locale'] > 0 && $_GET['action'] == 'pjActionLoad')
			{
				$this->loadSetFields(true);
			}else{
				$this->loadSetFields();
			}
		}
	}
	
	public function beforeRender()
	{
		if (isset($_GET['iframe']))
		{
			$this->setLayout('pjActionIframe');
		}
	}
	
	public function pjActionCancel()
	{
		$this->setLayout('pjActionEmpty');
		
		$pjBookingModel = pjBookingModel::factory();
				
		if (isset($_POST['booking_cancel']))
		{
			$booking_arr = $pjBookingModel
				->select('t1.*, t2.content AS `country`, t3.content AS `cancel_subject_admin`, t4.content AS `cancel_tokens_admin`')
				->join('pjMultiLang', "t2.model='pjCountry' AND t2.foreign_id=t1.c_country AND t2.locale=t1.locale_id AND t2.field='name'", 'left outer')
				->join('pjMultiLang', "t3.model='pjCalendar' AND t3.foreign_id=t1.calendar_id AND t3.field='cancel_subject_admin' AND t3.locale=t1.locale_id", 'left outer')
				->join('pjMultiLang', "t4.model='pjCalendar' AND t4.foreign_id=t1.calendar_id AND t4.field='cancel_tokens_admin' AND t4.locale=t1.locale_id", 'left outer')
				->find($_POST['id'])
				->getData();
			if (!empty($booking_arr))
			{
				$pjBookingModel
					->reset()
					->where(sprintf("SHA1(CONCAT(`id`, `created`, '%s')) = ", PJ_SALT), $_POST['hash'])
					->limit(1)
					->modifyAll(array('status' => 'cancelled'));
					 
				$user_arr = pjUserModel::factory()->find(1)->getData();
				if (!empty($user_arr) && !empty($user_arr['email']))
				{
					$pjEmail = new pjEmail();
					if ($this->option_arr['o_send_email'] == 'smtp')
					{
						$pjEmail
							->setTransport('smtp')
							->setSmtpHost($this->option_arr['o_smtp_host'])
							->setSmtpPort($this->option_arr['o_smtp_port'])
							->setSmtpUser($this->option_arr['o_smtp_user'])
							->setSmtpPass($this->option_arr['o_smtp_pass'])
							->setSender($this->option_arr['o_smtp_user']);
					}
						
					$booking_arr['extras'] = pjBookingExtraModel::factory()
						->select('t1.*, t2.content AS `name`')
						->join('pjMultiLang', sprintf("t2.model='pjExtra' AND t2.foreign_id=t1.extra_id AND t2.field='name' AND t2.locale='%u'", $booking_arr['locale_id']), 'left join')
						->where('t1.booking_id', $booking_arr['id'])
						->findAll()
						->getData();
						
					$booking_arr['rooms'] = pjBookingRoomModel::factory()
						->select('t1.*, COUNT(t1.id) AS `cnt`, t2.content AS `name`')
						->join('pjMultiLang', sprintf("t2.model='pjRoom' AND t2.foreign_id=t1.room_id AND t2.field='name' AND t2.locale='%u'", $booking_arr['locale_id']), 'left join')
						->where('t1.booking_id', $booking_arr['id'])
						->groupBy('t1.booking_id, t1.room_id')
						->findAll()
						->getData();

					$tokens = pjAppController::getTokens($booking_arr, $this->option_arr);
					$subject = str_replace($tokens['search'], $tokens['replace'], $booking_arr['cancel_subject_admin']);
					$message = str_replace($tokens['search'], $tokens['replace'], $booking_arr['cancel_tokens_admin']);
					
					$pjEmail
						->setContentType('text/html')
						->setTo($user_arr['email'])
						->setFrom($user_arr['email'])
						->setSubject($subject)
						->send($message);
				}
				pjUtil::redirect($_SERVER['PHP_SELF'] . '?controller=pjFront&action=pjActionCancel&cid='.$_POST['cid'].'&err=5');
			}
		} else {
			if (isset($_GET['hash']) && isset($_GET['id']))
			{
				$arr = $pjBookingModel
					->select("t1.*, '*********' AS `cc_type`, '*********' AS `cc_num`, '*********' AS `cc_code`,
						'**' AS `cc_exp_month`, '****' AS `cc_exp_year`, t2.content AS `country_title`")
					->join('pjMultiLang', "t2.model='pjCountry' AND t2.foreign_id=t1.c_country AND t2.field='name' AND t2.locale=t1.locale_id", 'left outer')
					->find($_GET['id'])
					->getData();

				if (empty($arr))
				{
					$this->set('status', 2);
				} else {
					if ($arr['status'] == 'cancelled')
					{
						$this->set('status', 4);
					} else {
						$hash = sha1($arr['id'] . $arr['created'] . PJ_SALT);
						if ($_GET['hash'] != $hash)
						{
							$this->set('status', 3);
						} else {
							
							$room_arr = pjBookingRoomModel::factory()
								->select('t1.*, t2.content AS `name`')
								->join('pjMultiLang', sprintf("t2.model='pjRoom' AND t2.foreign_id=t1.room_id AND t2.field='name' AND t2.locale='%u'", $arr['locale_id']), 'left outer')
								->join('pjRoom', 't3.id=t1.room_id', 'inner')
								->where('t1.booking_id', $arr['id'])
								->findAll()
								->getData();
							
							$_rooms = $_adults = $_children = 0;
							$num_nights = ceil((strtotime($arr['date_to']) - strtotime($arr['date_from'])) / 86400);
							if ($this->option_arr['o_price_based_on'] == 'days')
							{
								$num_nights += 1;
							}
							
							foreach ($room_arr as $room)
							{
								$_rooms += 1;
								$_adults += $room['adults'];
								$_children += $room['children'];
							}
								
							$arr['_nights'] = $num_nights;
							$arr['_persons'] = $_adults + $_children;
							$arr['_rooms'] = $_rooms;
								
							$arr['room_arr'] = array();
							foreach ($room_arr as $room)
							{
								if (!isset($arr['room_arr'][$room['room_id']]))
								{
									$arr['room_arr'][$room['room_id']] = array();
								}
								$arr['room_arr'][$room['room_id']][] = $room;
							}
							
							$arr['extra_arr'] = pjBookingExtraModel::factory()
								->select('t1.*, t2.content AS `name`, t3.per')
								->join('pjMultiLang', sprintf("t2.model='pjExtra' AND t2.foreign_id=t1.extra_id AND t2.field='name' AND t2.locale='%u'", $arr['locale_id']), 'left outer')
								->join('pjExtra', 't3.id=t1.extra_id', 'inner')
								->where('t1.booking_id', $arr['id'])
								->findAll()
								->getData();
								
							$this->set('arr', $arr);
						}
					}
				}
			} elseif (!isset($_GET['err'])) {
				$this->set('status', 1);
			}
			$this->appendCss('index.php?controller=pjFront&action=pjActionLoadCss&cid='.$_GET['cid'].'&layout=1', PJ_INSTALL_URL, true);
		}
	}
	
	public function pjActionCaptcha()
	{
		$this->setAjax(true);
		$this->setLayout('pjActionEmpty');
	
		header("Cache-Control: max-age=3600, private");
		
		$pjCaptcha = new pjCaptcha(PJ_WEB_PATH . 'obj/Anorexia.ttf', $this->defaultCaptcha, 6);
		$pjCaptcha
			->setImage(PJ_IMG_PATH . 'button.png')
			->init(@$_GET['rand']);
		exit;
	}
	
	public function pjActionCheckCaptcha()
	{
		$this->setAjax(true);
		
		if ($this->isXHR())
		{
			echo isset($_SESSION[$this->defaultCaptcha]) && isset($_GET['captcha']) && $_SESSION[$this->defaultCaptcha] == strtoupper($_GET['captcha']) ? 'true' : 'false';
		}
		exit;
	}
	
	public function pjActionConfirmAuthorize()
	{
		$this->setAjax(true);
		
		if (pjObject::getPlugin('pjAuthorize') === NULL)
		{
			$this->log('Authorize.NET plugin not installed');
			exit;
		}
		
		if (!isset($_POST['x_invoice_num']))
		{
			$this->log('Missing arguments');
			exit;
		}
		
		$pjInvoiceModel = pjInvoiceModel::factory();
		$pjBookingModel = pjBookingModel::factory();
		
		$invoice_arr = $pjInvoiceModel
			->where('t1.uuid', $_POST['x_invoice_num'])
			->limit(1)
			->findAll()
			->getData();
		if (!empty($invoice_arr))
		{
			$invoice_arr = $invoice_arr[0];
			$booking_arr = $pjBookingModel
				->select("t1.*, t2.content AS country,
						AES_DECRYPT(t1.cc_type, '".PJ_SALT."') AS `cc_type`,
						AES_DECRYPT(t1.cc_num, '".PJ_SALT."') AS `cc_num`,
						AES_DECRYPT(t1.cc_exp_month, '".PJ_SALT."') AS `cc_exp_month`,
						AES_DECRYPT(t1.cc_exp_year, '".PJ_SALT."') AS `cc_exp_year`,
						AES_DECRYPT(t1.cc_code, '".PJ_SALT."') AS `cc_code`")
				->join('pjMultiLang', "t2.model='pjCountry' AND t2.foreign_id=t1.c_country AND t2.locale=t1.locale_id AND t2.field='name'", 'left outer')
				->where('t1.uuid', $invoice_arr['order_id'])
				->limit(1)
				->findAll()
				->getData();
			if (!empty($booking_arr))
			{
				$booking_arr = $booking_arr[0];
				$calendar_arr = pjCalendarModel::factory()
					->select(sprintf("t1.*, t2.content AS payment_subject_client, t3.content AS payment_tokens_client,
						t4.content AS payment_subject_admin, t5.content AS payment_tokens_admin, t6.content AS `payment_sms_admin`,
						(SELECT GROUP_CONCAT(`email`) FROM `%1\$s` WHERE `status` = 'T') AS `email`,
						(SELECT GROUP_CONCAT(`phone`) FROM `%1\$s` WHERE `status` = 'T' AND `phone` IS NOT NULL) AS `phone`",
						pjUserModel::factory()->getTable()))
					->join('pjMultiLang', sprintf("t2.model='pjCalendar' AND t2.foreign_id=t1.id AND t2.field='payment_subject_client' AND t2.locale='%u'", $booking_arr['locale_id']), 'left outer')
					->join('pjMultiLang', sprintf("t3.model='pjCalendar' AND t3.foreign_id=t1.id AND t3.field='payment_tokens_client' AND t3.locale='%u'", $booking_arr['locale_id']), 'left outer')
					->join('pjMultiLang', sprintf("t4.model='pjCalendar' AND t4.foreign_id=t1.id AND t4.field='payment_subject_admin' AND t4.locale='%u'", $booking_arr['locale_id']), 'left outer')
					->join('pjMultiLang', sprintf("t5.model='pjCalendar' AND t5.foreign_id=t1.id AND t5.field='payment_tokens_admin' AND t5.locale='%u'", $booking_arr['locale_id']), 'left outer')
					->join('pjMultiLang', sprintf("t6.model='pjCalendar' AND t6.foreign_id=t1.id AND t6.field='payment_sms_admin' AND t6.locale='%u'", $booking_arr['locale_id']), 'left outer')
					->find($booking_arr['calendar_id'])
					->toArray('email', ',')
					->toArray('phone', ',')
					->getData();
				
				$params = array(
					'transkey' => $this->option_arr['o_authorize_transkey'],
					'x_login' => $this->option_arr['o_authorize_merchant_id'],
					'md5_setting' => $this->option_arr['o_authorize_hash'],
					'key' => md5($this->option_arr['private_key'] . PJ_SALT)
				);
				
				$response = $this->requestAction(array('controller' => 'pjAuthorize', 'action' => 'pjActionConfirm', 'params' => $params), array('return'));
				if ($response !== FALSE && $response['status'] === 'OK')
				{
					$pjBookingModel
						->reset()
						->set('id', $booking_arr['id'])
						->modify(array('status' => $this->option_arr['o_status_if_paid']));
						
					$pjInvoiceModel
						->reset()
						->set('id', $invoice_arr['id'])
						->modify(array('status' => 'paid', 'modified' => ':NOW()'));
						
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
						
					pjFront::pjActionConfirmSend($this->option_arr, $booking_arr, $calendar_arr, 'payment');
				} elseif (!$response) {
					$this->log('Authorization failed');
				} else {
					$this->log('Booking not confirmed. ' . $response['response_reason_text']);
				}
			} else {
				$this->log('Booking not found');
			}
		} else {
			$this->log('Invoice not found');
		}
		exit;
	}

	public function pjActionConfirmPaypal()
	{
		$this->setAjax(true);
		
		if (pjObject::getPlugin('pjPaypal') === NULL)
		{
			$this->log('Paypal plugin not installed');
			exit;
		}
		
		if (!isset($_POST['custom']))
		{
			$this->log('Missing parameters');
			exit;
		}
		
		$pjInvoiceModel = pjInvoiceModel::factory();
		$pjBookingModel = pjBookingModel::factory();
		
		$invoice_arr = $pjInvoiceModel
			->where('t1.uuid', $_POST['custom'])
			->limit(1)
			->findAll()
			->getData();

		if (!empty($invoice_arr))
		{
			$invoice_arr = $invoice_arr[0];
			$booking_arr = $pjBookingModel
				->select("t1.*, t2.content AS country,
						AES_DECRYPT(t1.cc_type, '".PJ_SALT."') AS `cc_type`,
						AES_DECRYPT(t1.cc_num, '".PJ_SALT."') AS `cc_num`,
						AES_DECRYPT(t1.cc_exp_month, '".PJ_SALT."') AS `cc_exp_month`,
						AES_DECRYPT(t1.cc_exp_year, '".PJ_SALT."') AS `cc_exp_year`,
						AES_DECRYPT(t1.cc_code, '".PJ_SALT."') AS `cc_code`")
				->join('pjMultiLang', "t2.model='pjCountry' AND t2.foreign_id=t1.c_country AND t2.locale=t1.locale_id AND t2.field='name'", 'left outer')
				->where('t1.uuid', $invoice_arr['order_id'])
				->limit(1)
				->findAll()
				->getData();
			
			if (!empty($booking_arr))
			{
				$booking_arr = $booking_arr[0];
				$calendar_arr = pjCalendarModel::factory()
					->select(sprintf("t1.*, t2.content AS payment_subject_client, t3.content AS payment_tokens_client,
						t4.content AS payment_subject_admin, t5.content AS payment_tokens_admin, t6.content AS `payment_sms_admin`,
						(SELECT GROUP_CONCAT(`email`) FROM `%1\$s` WHERE `status` = 'T') AS `email`,
						(SELECT GROUP_CONCAT(`phone`) FROM `%1\$s` WHERE `status` = 'T' AND `phone` IS NOT NULL) AS `phone`",
						pjUserModel::factory()->getTable()))
					->join('pjMultiLang', sprintf("t2.model='pjCalendar' AND t2.foreign_id=t1.id AND t2.field='payment_subject_client' AND t2.locale='%u'", $booking_arr['locale_id']), 'left outer')
					->join('pjMultiLang', sprintf("t3.model='pjCalendar' AND t3.foreign_id=t1.id AND t3.field='payment_tokens_client' AND t3.locale='%u'", $booking_arr['locale_id']), 'left outer')
					->join('pjMultiLang', sprintf("t4.model='pjCalendar' AND t4.foreign_id=t1.id AND t4.field='payment_subject_admin' AND t4.locale='%u'", $booking_arr['locale_id']), 'left outer')
					->join('pjMultiLang', sprintf("t5.model='pjCalendar' AND t5.foreign_id=t1.id AND t5.field='payment_tokens_admin' AND t5.locale='%u'", $booking_arr['locale_id']), 'left outer')
					->join('pjMultiLang', sprintf("t6.model='pjCalendar' AND t6.foreign_id=t1.id AND t6.field='payment_sms_admin' AND t6.locale='%u'", $booking_arr['locale_id']), 'left outer')
					->find($booking_arr['calendar_id'])
					->toArray('email', ',')
					->toArray('phone', ',')
					->getData();
					
				$params = array(
					'txn_id' => @$booking_arr['txn_id'],
					'paypal_address' => $this->option_arr['o_paypal_address'],
					'deposit' => @$booking_arr['deposit'],
					'currency' => $this->option_arr['o_currency'],
					'key' => md5($this->option_arr['private_key'] . PJ_SALT)
				);
	
				$response = $this->requestAction(array('controller' => 'pjPaypal', 'action' => 'pjActionConfirm', 'params' => $params), array('return'));
				if ($response !== FALSE && $response['status'] === 'OK')
				{
					$this->log('Booking confirmed');
					$pjBookingModel
						->reset()
						->set('id', $booking_arr['id'])
						->modify(array(
							'status' => $this->option_arr['o_status_if_paid'],
							'txn_id' => $response['transaction_id'],
							'processed_on' => ':NOW()'
						));
					
					$pjInvoiceModel
						->reset()
						->set('id', $invoice_arr['id'])
						->modify(array('status' => 'paid', 'modified' => ':NOW()'));
						
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
						
					pjFront::pjActionConfirmSend($this->option_arr, $booking_arr, $calendar_arr, 'payment');
				} elseif (!$response) {
					$this->log('Authorization failed');
				} else {
					$this->log('Booking not confirmed');
				}
			} else {
				$this->log('Booking not found');
			}
		} else {
			$this->log('Invoice not found');
		}
		exit;
	}
	
	private static function pjActionConfirmSend($option_arr, $booking_arr, $calendar_arr, $type)
	{
		if (!in_array($type, array('confirm', 'payment')))
		{
			return false;
		}
		$Email = new pjEmail();
		$Email->setContentType('text/html');
		if ($option_arr['o_send_email'] == 'smtp')
		{
			$Email
				->setTransport('smtp')
				->setSmtpHost($option_arr['o_smtp_host'])
				->setSmtpPort($option_arr['o_smtp_port'])
				->setSmtpUser($option_arr['o_smtp_user'])
				->setSmtpPass($option_arr['o_smtp_pass'])
				->setSender($option_arr['o_smtp_user'])
			;
		}
		$tokens = pjAppController::getTokens($booking_arr, $option_arr);

		switch ($type)
		{
			case 'confirm':
				$subject_client = str_replace($tokens['search'], $tokens['replace'], $calendar_arr['confirm_subject_client']);
				$message_client = str_replace($tokens['search'], $tokens['replace'], $calendar_arr['confirm_tokens_client']);
				
				$subject_admin = str_replace($tokens['search'], $tokens['replace'], $calendar_arr['confirm_subject_admin']);
				$message_admin = str_replace($tokens['search'], $tokens['replace'], $calendar_arr['confirm_tokens_admin']);
				
				// Client
				if (isset($booking_arr['c_email']) && !empty($booking_arr['c_email']))
				{
					$Email
						->setTo($booking_arr['c_email'])
						->setFrom(!empty($calendar_arr['email']) ? $calendar_arr['email'][0] : $booking_arr['c_email'])
						->setSubject($subject_client)
						->send($message_client);
				}
				// Admin
				foreach ($calendar_arr['email'] as $email)
				{
					$Email
						->setTo($email)
						->setFrom($email)
						->setSubject($subject_admin)
						->send($message_admin);
				}
				
				# SMS
				if (pjObject::getPlugin('pjSms') !== NULL && isset($calendar_arr['phone']) && !empty($calendar_arr['phone']))
				{
					$dispatcher = new pjDispatcher();
					$controller = $dispatcher->createController(array('controller' => 'pjFront'));
					foreach ($calendar_arr['phone'] as $phone)
					{
						$phone = preg_replace('/\D/', '', $phone);
						if (empty($phone))
						{
							continue;
						}
						$controller->requestAction(array('controller' => 'pjSms', 'action' => 'pjActionSend', 'params' => array(
							'number' => $phone,
							'text' => str_replace($tokens['search'], $tokens['replace'], @$calendar_arr['confirm_sms_admin']),
							'type' => 'unicode',
							'key' => md5($option_arr['private_key'] . PJ_SALT)
						)), array('return'));
					}
				}
				break;
			case 'payment':
				$subject_client = str_replace($tokens['search'], $tokens['replace'], $calendar_arr['payment_subject_client']);
				$message_client = str_replace($tokens['search'], $tokens['replace'], $calendar_arr['payment_tokens_client']);
				
				$subject_admin = str_replace($tokens['search'], $tokens['replace'], $calendar_arr['payment_subject_admin']);
				$message_admin = str_replace($tokens['search'], $tokens['replace'], $calendar_arr['payment_tokens_admin']);

				// Client
				if (isset($booking_arr['c_email']) && !empty($booking_arr['c_email']))
				{
					$Email
						->setTo($booking_arr['c_email'])
						->setFrom(!empty($calendar_arr['email']) ? $calendar_arr['email'][0] : $booking_arr['c_email'])
						->setSubject($subject_client)
						->send($message_client);
				}
				// Admin
				foreach ($calendar_arr['email'] as $email)
				{
					$Email
						->setTo($email)
						->setFrom($email)
						->setSubject($subject_admin)
						->send($message_admin);
				}
				# SMS
				if (pjObject::getPlugin('pjSms') !== NULL && isset($calendar_arr['phone']) && !empty($calendar_arr['phone']))
				{
					$dispatcher = new pjDispatcher();
					$controller = $dispatcher->createController(array('controller' => 'pjFront'));
					foreach ($calendar_arr['phone'] as $phone)
					{
						$phone = preg_replace('/\D/', '', $phone);
						if (empty($phone))
						{
							continue;
						}
						$controller->requestAction(array('controller' => 'pjSms', 'action' => 'pjActionSend', 'params' => array(
							'number' => $phone,
							'text' => str_replace($tokens['search'], $tokens['replace'], @$calendar_arr['payment_sms_admin']),
							'type' => 'unicode',
							'key' => md5($option_arr['private_key'] . PJ_SALT)
						)), array('return'));
					}
				}
				break;
		}
	}
	
	public function pjActionLoad()
	{
		ob_start();
		header("Content-Type: text/javascript; charset=utf-8");
	}

	public function pjActionLoadCss()
	{
		$theme = isset($_GET['theme']) && strlen($_GET['theme']) > 0 ? (int) $_GET['theme'] : (int) $this->option_arr['o_theme'];
		$arr = array();
		
		$arr[] = array('file' => 'bootstrap-datetimepicker.min.css', 'path' => PJ_LIBS_PATH . 'pjQ/bootstrap/css/');
		$arr[] = array('file' => 'jquery.fancybox-1.3.4.min.css', 'path' => PJ_LIBS_PATH . 'pjQ/fancybox/');
		
		$arr[] = array('file' => 'HotelBooking.css', 'path' => PJ_CSS_PATH);
		
		if ($theme > 0 && $theme <= 10)
		{
			$arr[] = array('file' => sprintf('theme%u.css', $theme), 'path' => PJ_CSS_PATH . 'themes/');
			$arr[] = array('file' => sprintf('theme%u.txt', $theme), 'path' => PJ_CSS_PATH . 'themes/');
		}
		
		header("Content-Type: text/css; charset=utf-8");
		$cid = (int) @$_GET['cid'];
		foreach ($arr as $item)
		{
			ob_start();
			@readfile($item['path'] . $item['file']);
			$string = ob_get_contents();
			ob_end_clean();
			
			if ($string !== FALSE)
			{
				echo str_replace(
					array(
						'../fonts/fontawesome-',
						'images/ui-',
						"url('fancybox",
						"url('blank.gif",
						"src='fancybox/",
						"app/web/img/",
						"pjWrapper"
					),
					array(						
						PJ_THIRD_PARTY_PATH . 'font-awesome/fonts/fontawesome-',
						PJ_LIBS_PATH . 'pjQ/css/images/ui-',
						"url('" . PJ_LIBS_PATH . "pjQ/fancybox/fancybox",
						"url('" . PJ_LIBS_PATH . "pjQ/fancybox/blank.gif",
						"src='" . PJ_LIBS_PATH . "pjQ/fancybox/",
						PJ_INSTALL_URL . PJ_IMG_PATH,
						"pjWrapperHotelBooking_" . $theme
					),
					$string) . "\n";
			}
		}
		exit;
	}
	
	public function pjActionLocale()
	{
		$this->setAjax(true);
	
		if ($this->isXHR())
		{
			if (isset($_GET['locale_id']))
			{
				$this->pjActionSetLocale($_GET['locale_id']);

				$this->loadSetFields(true);
				
				$day_names = __('day_names', true);
				ksort($day_names, SORT_NUMERIC);
				
				$months = __('months', true);
				ksort($months, SORT_NUMERIC);
				
				pjAppController::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => 'Locale have been changed.', 'opts' => array(
					'day_names' => array_values($day_names),
					'month_names' => array_values($months)
				)));
			}
		}
		exit;
	}
	
	private function pjActionSetLocale($locale)
	{
		if ((int) $locale > 0)
		{
			$this->setLocaleId($locale);
		}
		return $this;
	}
	
	public function pjActionGetTerms()
	{
		$this->setAjax(true);
	
		if ($this->isXHR())
		{
			if (isset($_GET['cid']) && (int) $_GET['cid'] > 0)
			{
				$this->set('calendar_arr', pjCalendarModel::factory()
					->select('t1.*, t2.content AS terms_url, t3.content AS terms_body')
					->join('pjMultiLang', "t2.model='pjCalendar' AND t2.foreign_id=t1.id AND t2.field='terms_url' AND t2.locale='".$this->getLocaleId()."'", 'left outer')
					->join('pjMultiLang', "t3.model='pjCalendar' AND t3.foreign_id=t1.id AND t3.field='terms_body' AND t3.locale='".$this->getLocaleId()."'", 'left outer')
					->find($_GET['cid'])
					->getData()
				);
			}
		}
	}

	public function pjActionGetRoom()
	{
		$this->setAjax(true);
	
		if ($this->isXHR())
		{
			if (isset($_GET['room_id']) && (int) $_GET['room_id'] > 0)
			{
				if (isset($_SESSION[$this->defaultStore]) && isset($_SESSION[$this->defaultStore]['date_from']) && !empty($_SESSION[$this->defaultStore]['date_from']))
				{
					$date_from = $_SESSION[$this->defaultStore]['date_from'];
					$date_to = $_SESSION[$this->defaultStore]['date_to'];
					$pjRoomModel = pjRoomModel::factory();
					if (!isset($_GET['cnt']) || (int) $_GET['cnt'] === 0)
					{
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
							->find($_GET['room_id'])
							->toArray('gallery', '~:~')
							->toArray('medium', '~:~')
							->toArray('large', '~:~')
							->toArray('alt', '~:~')
							->getData();
					} else {
						$arr = $pjRoomModel
							->select(sprintf("t1.*, t2.content AS `name`, t3.content AS `description`,								
								(SELECT MIN(LEAST(`mon`, `tue`, `wed`, `thu`, `fri`, `sat`, `sun`)) FROM `%1\$s` AS `TP` WHERE `foreign_id` = `t1`.`id` AND ((`TP`.date_from <= '$date_to' ANd `TP`.date_to >= '$date_from') OR ( (`TP`.date_from IS NULL OR `TP`.date_from = '0000-00-00') OR (`TP`.date_to IS NULL OR `TP`.date_to = '0000-00-00') )) LIMIT 1) AS `price_from`
								",
								pjPriceModel::factory()->getTable()
							))
							->join('pjMultiLang', "t2.model='pjRoom' AND t2.foreign_id=t1.id AND t2.field='name' AND t2.locale='".$this->getLocaleId()."'", 'left outer')
							->join('pjMultiLang', "t3.model='pjRoom' AND t3.foreign_id=t1.id AND t3.field='description' AND t3.locale='".$this->getLocaleId()."'", 'left outer')
							->find($_GET['room_id'])
							->getData();
					}
					
					# --
					if (!empty($arr))
					{
						$nightMode = $this->option_arr['o_price_based_on'] == "nights";
						
						$hours = $this->option_arr['o_pending_time'] / 60;
						$remainder = $this->option_arr['o_pending_time'] % 60;
						if ($remainder === 0)
						{
							$pending_time = sprintf("%u:00:00", $hours);
						} else {
							$pending_time = sprintf("%u:%u:00", $hours, $remainder);
						}
					
						$tmp = pjBookingRoomModel::factory()
							->select('t1.*, t2.date_from, t2.date_to')
							->join('pjBooking', sprintf("t2.id=t1.booking_id AND (t2.status = 'confirmed' OR (t2.status = 'pending' AND t2.created >= SUBTIME(NOW(), '%5\$s'))) AND t2.date_from %3\$s '%2\$s' AND t2.date_to %4\$s '%1\$s'",
								$pjRoomModel->escapeStr($_SESSION[$this->defaultStore]['date_from']),
								$pjRoomModel->escapeStr($_SESSION[$this->defaultStore]['date_to']),
								$nightMode ? "<" : "<=",
								$nightMode ? ">" : ">=",
								$pjRoomModel->escapeStr($pending_time)), 'inner')
							->where('t1.room_id', $_GET['room_id'])
							->findAll()
							->getData();
							
						$sum = array();
						$dt_from = strtotime($_SESSION[$this->defaultStore]['date_from']);
						$dt_to = strtotime($_SESSION[$this->defaultStore]['date_to']);
						for ($i = $dt_from; $i <= $dt_to; $i += 86400)
						{
							$sum[date("Y-m-d", $i)] = 0;
						}
						foreach ($tmp as $value)
						{
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
								$sum[$iso] += 1;
							}
						}
						$arr['max_bookings'] = isset($sum) && !empty($sum) ? ceil(max($sum)) : 0;
						
						# Restrictions
						$restrictions = pjRestrictionRoomModel::factory()
							->select('t3.room_id, COUNT(t3.room_id) AS `cnt`')
							->join('pjRestriction', "t2.id=t1.restriction_id", 'inner')
							->join('pjRoomNumber', "t3.id=t1.room_number_id", 'inner')
							->join('pjRoom', "t4.id=t3.room_id", 'inner')
							->where(sprintf('t2.date_from %s', $nightMode ? "<" : "<="), $_SESSION[$this->defaultStore]['date_to'])
							->where(sprintf('t2.date_to %s', $nightMode ? ">" : ">="), $_SESSION[$this->defaultStore]['date_from'])
							->where('t3.room_id', $arr['id'])
							->groupBy('t3.room_id')
							->findAll()
							->getDataPair('room_id', 'cnt');
							# Restrictions
						
						$arr['unavailable_cnt'] = isset($restrictions[$arr['id']]) ? (int) $restrictions[$arr['id']] : 0;
						$arr['real_price_from'] = $this->getRoomPrice(
								$arr['id'],
								$_SESSION[$this->defaultStore]['date_from'],
								$_SESSION[$this->defaultStore]['date_to'],
								($this->option_arr['o_price_based_on'] == 'nights'),
								$_SESSION[$this->defaultStore]['adults'],
								$_SESSION[$this->defaultStore]['children']
						);
					}
					# --
					
					$this->set('room_arr', $arr);
					
					if (!isset($_SESSION[$this->defaultStore]))
					{
						$_SESSION[$this->defaultStore] = array();
					}
					if (!isset($_SESSION[$this->defaultStore]['rooms']))
					{
						$_SESSION[$this->defaultStore]['rooms'] = array();
					}
					if (!isset($_SESSION[$this->defaultStore]['rooms'][$_GET['room_id']]))
					{
						$_SESSION[$this->defaultStore]['rooms'][$_GET['room_id']] = array();
					}
	
					$price = $this->getRoomPrice(
						$_GET['room_id'],
						$_SESSION[$this->defaultStore]['date_from'],
						$_SESSION[$this->defaultStore]['date_to'],
						($this->option_arr['o_price_based_on'] == 'nights'),
						@$_GET['adults'],
						@$_GET['children']
					);
					
					for ($i = 1; $i <= $_GET['cnt']; $i++)
					{
						$_SESSION[$this->defaultStore]['rooms'][$_GET['room_id']][$i] = array(
							//'price' => $price
							'price' => isset($_SESSION[$this->defaultStore]['content'][$_GET['room_id']][$i]['raw_price']) ? $_SESSION[$this->defaultStore]['content'][$_GET['room_id']][$i]['raw_price'] : $price
						);
					}
					
					if (isset($_GET['cnt']) && (int) $_GET['cnt'] === 0 && isset($_GET['adults']) && isset($_GET['children']))
					{
						if (isset($_SESSION[$this->defaultStore]['all_rooms']) && isset($_SESSION[$this->defaultStore]['all_rooms'][$_GET['room_id']]))
						{
							$_SESSION[$this->defaultStore]['all_rooms'][$_GET['room_id']] = NULL;
							unset($_SESSION[$this->defaultStore]['all_rooms'][$_GET['room_id']]);
						}
						if (isset($_SESSION[$this->defaultStore]['content']) && isset($_SESSION[$this->defaultStore]['content'][$_GET['room_id']]))
						{
							$_SESSION[$this->defaultStore]['content'][$_GET['room_id']] = NULL;
							unset($_SESSION[$this->defaultStore]['content'][$_GET['room_id']]);
						}
					}
					
					$limit_arr = array();
					if (!empty($_SESSION[$this->defaultStore]['date_to']) && !empty($_SESSION[$this->defaultStore]['date_from']))
					{
						 $limit_arr = pjLimitModel::factory()
							->where('t1.room_id', $_GET['room_id'])
						 	->where('t1.date_from <=', $_SESSION[$this->defaultStore]['date_to'])
							->where('t1.date_to >=', $_SESSION[$this->defaultStore]['date_from'])
							->findAll()
							->getData();
					}
					$this->set('limit_arr', $limit_arr);
				}else{
					pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => ''));
				}
			}
		}
	}
	
	public function pjActionGetPrice()
	{
		$this->setAjax(true);
	
		if ($this->isXHR())
		{
			$total = 0;
			$room = $this->getRoomPrice(
				$_GET['room_id'],
				$_SESSION[$this->defaultStore]['date_from'],
				$_SESSION[$this->defaultStore]['date_to'],
				($this->option_arr['o_price_based_on'] == 'nights'),
				$_GET['adults'],
				$_GET['children']
			);
			foreach ($_SESSION[$this->defaultStore]['rooms'][$_GET['room_id']] as $k => $v)
			{
				if ($_GET['index'] == $k)
				{
					$_SESSION[$this->defaultStore]['rooms'][$_GET['room_id']][$k]['price'] = $room;
					$total += $room;
				} else {
					$total += $v['price'];
				}
			}
			$format_total = pjUtil::formatCurrencySign(number_format($total, 2), $this->option_arr['o_currency']);
			$format_room = pjUtil::formatCurrencySign(number_format($room, 2), $this->option_arr['o_currency']);
			pjAppController::jsonResponse(compact('total', 'room', 'format_total', 'format_room'));
		}
		exit;
	}
	
	protected function pjActionAdultsChildren()
	{
		$adults = $children = 0;
		if (isset($_SESSION[$this->defaultStore]) && isset($_SESSION[$this->defaultStore]['all_rooms']) &&
			!empty($_SESSION[$this->defaultStore]['all_rooms']) && $this->pjActionCheckAvailability($_GET['cid']))
		{
			
			foreach ($_SESSION[$this->defaultStore]['all_rooms'] as $room_id => $room_arr)
			{
				foreach ($room_arr as $index => $room_info)
				{
					$adults += $room_info['adults'];
					$children += $room_info['children'];
				}
			}
		}
		$text = str_replace(
			array(
				'{XA}',
				'{ADULTS}',
				'{XC}',
				'{CHILDREN}'
			),
			array(
				$adults,
				$adults != 1 ? pjMultibyte::strtolower(__('front_adults', true)) : pjMultibyte::strtolower(__('front_adult', true)),
				$children,
				$children != 1 ? pjMultibyte::strtolower(__('front_children', true)) : pjMultibyte::strtolower(__('front_child', true))
			),
			__('front_rooms_accommodate_text', true)
		);
		
		return compact('adults', 'children', 'text');
	}
	
	public function pjActionSetPrice()
	{
		$this->setAjax(true);
	
		if ($this->isXHR())
		{
			if (!isset($_SESSION[$this->defaultStore]['all_rooms']))
			{
				$_SESSION[$this->defaultStore]['all_rooms'] = array();
			}
			$_SESSION[$this->defaultStore]['all_rooms'][$_POST['room_id']] = array();
			$i = 1;
			if (isset($_POST['adults']))
			{
				foreach ($_POST['adults'] as $k => $v)
				{
					$_SESSION[$this->defaultStore]['all_rooms'][$_POST['room_id']][$i] = array(
						'adults' => $_POST['adults'][$k],
						'children' => $_POST['children'][$k]
					);
					$i++;
				}
			} else {
				unset($_SESSION[$this->defaultStore]['all_rooms'][$_POST['room_id']]);
			}
			# addition
			$content = isset($_SESSION[$this->defaultStore]['all_rooms'][$_POST['room_id']]) ? $_SESSION[$this->defaultStore]['all_rooms'][$_POST['room_id']] : array();
			if (isset($_POST['adults']))
			{
				$i = 1;
				foreach ($_POST['adults'] as $k => $v)
				{
					$price = $this->getRoomPrice(
						$_POST['room_id'],
						$_SESSION[$this->defaultStore]['date_from'],
						$_SESSION[$this->defaultStore]['date_to'],
						($this->option_arr['o_price_based_on'] == 'nights'),
						$_POST['adults'][$k],
						$_POST['children'][$k]
					);
					$content[$i]['price'] = pjUtil::formatCurrencySign(number_format($price, 2), $this->option_arr['o_currency']);
					$content[$i]['raw_price'] = $price;
					$i++;
				}
			}
			$_SESSION[$this->defaultStore]['content'][$_POST['room_id']] = $content;
			
			$ac_arr = $this->pjActionAdultsChildren();

			pjAppController::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => '',
				'id' => $_POST['room_id'],
				'content' => $content,
				'persons' => $ac_arr['text']
			));
		}
		exit;
	}
	
	public function pjActionHandleExtras()
	{
		$this->setAjax(true);
	
		if ($this->isXHR())
		{
			if (!isset($_POST['extra_id']) || !isset($_POST['checked']))
			{
				pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => 'Missing parameters.'));
			}
			
			if ((int) $_POST['checked'] === 1)
			{
				// Add
				if (!isset($_SESSION[$this->defaultStore]))
				{
					$_SESSION[$this->defaultStore] = array();
				}
				if (!isset($_SESSION[$this->defaultStore]['extras']))
				{
					$_SESSION[$this->defaultStore]['extras'] = array();
				}
				if (!array_key_exists($_POST['extra_id'], $_SESSION[$this->defaultStore]['extras']))
				{
					$arr = pjExtraModel::factory()
						->select('t1.*, t2.content AS `name`')
						->join('pjMultiLang', sprintf("t2.model='pjExtra' AND t2.foreign_id=t1.id AND t2.field='name' AND t2.locale='%u'", $this->getLocaleId()), 'left outer')
						->find($_POST['extra_id'])->getData();
					if (!empty($arr))
					{
						$_SESSION[$this->defaultStore]['extras'][$_POST['extra_id']] = $arr;
						pjAppController::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => 'Extra has been added.'));
					}
				}
			} elseif ((int) $_POST['checked'] === 0) {
				// Remove
				if (isset($_SESSION[$this->defaultStore]) && is_array($_SESSION[$this->defaultStore]) &&
					isset($_SESSION[$this->defaultStore]['extras']) && is_array($_SESSION[$this->defaultStore]['extras']) &&
					array_key_exists($_POST['extra_id'], $_SESSION[$this->defaultStore]['extras']))
				{
					$_SESSION[$this->defaultStore]['extras'][$_POST['extra_id']] = NULL;
					unset($_SESSION[$this->defaultStore]['extras'][$_POST['extra_id']]);
					pjAppController::jsonResponse(array('status' => 'OK', 'code' => 201, 'text' => 'Extra has been removed.'));
				}
			}
			
			pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 101, 'text' => 'Error'));
		}
		exit;
	}

	public function pjActionGetPaymentForm()
	{
		$this->setAjax(true);
		
		if ($this->isXHR())
		{
			$booking_arr = pjBookingModel::factory()
				->find($_GET['booking_id'])->getData();
			
			$invoice_arr = pjInvoiceModel::factory()->find($_GET['invoice_id'])->getData();

			switch ($_GET['payment_method'])
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
						'submit_class' => 'hbSelectorButton hbButton hbButtonOrange'
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
						'submit_class' => 'hbSelectorButton hbButton hbButtonOrange'
					));
					break;
			}
			
			$this
				->set('booking_arr', $booking_arr)
				->set('get', $_GET);
		}
	}
	
	public function pjActionProcessOrder()
	{
		$this->setAjax(true);
	
		if ($this->isXHR())
		{
			if (!isset($_POST['step_preview']) || !isset($_SESSION[$this->defaultStore]) || empty($_SESSION[$this->defaultStore]))
			{
				pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 104, 'text' => __('system_104', true)));
			}
			
			$STORE = $_SESSION[$this->defaultStore];
			if ((int) $this->option_arr['o_bf_captcha'] === 3 && (!isset($STORE['form']) || !isset($STORE['form']['captcha']) || !pjCaptcha::validate(strtoupper($STORE['form']['captcha']), $_SESSION[$this->defaultCaptcha])))
			{
				pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 105, 'text' => __('system_105', true)));
			}
			$_SESSION[$this->defaultCaptcha] = NULL;
			unset($_SESSION[$this->defaultCaptcha]);
				
			if (!$this->pjActionCheckAvailability($_GET['cid']))
			{
				pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 108, 'text' => __('system_108', true)));
			}
			
			$FORM = $STORE['form'];
			
			$data = array();
			$data['date_from'] = $STORE['date_from'];
			$data['date_to'] = $STORE['date_to'];
			$data['calendar_id'] = $_GET['cid'];
			$data['uuid'] = pjUtil::uuid();
			$data['status'] = $this->option_arr['o_status_if_not_paid'];
			$data['ip'] = $_SERVER['REMOTE_ADDR'];
			$data['locale_id'] = $this->getLocaleId();
			
			if ((int) $this->option_arr['o_bf_arrival'] !== 1)
			{
				$iso_time = pjUtil::formatTime($FORM['c_arrival'], 'h:i A');
				$data['c_arrival'] = $iso_time;
			}
			
			$data = array_merge($FORM, $data);
			
			if (isset($data['payment_method']) && $data['payment_method'] != 'creditcard')
			{
				unset($data['cc_type']);
				unset($data['cc_num']);
				unset($data['cc_exp_month']);
				unset($data['cc_exp_year']);
				unset($data['cc_code']);
			}
			
			$session_prices = $this->calPrices($STORE['all_rooms'], $STORE['date_from'], $STORE['date_to'], @$_SESSION[$this->defaultVoucher], isset($STORE['extras']) ? $STORE['extras'] : array(), $STORE['_nights'], $this->option_arr, 'front');
			$data = array_merge($data, $session_prices);

			if (isset($_SESSION[$this->defaultVoucher], $_SESSION[$this->defaultVoucher]['voucher_code']))
			{
				$data['voucher'] = $_SESSION[$this->defaultVoucher]['voucher_code'];
			}
			
			$pjBookingModel = pjBookingModel::factory();
			if (!$pjBookingModel->validates($data))
			{
				pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 106, 'text' => __('system_106', true)));
			}
			
			$booking_id = $pjBookingModel->setAttributes($data)->insert()->getInsertId();
			if ($booking_id === false || (int) $booking_id <= 0)
			{
				pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 107, 'text' => __('system_107', true)));
			}
			
			$pjRoomNumberModel = pjRoomNumberModel::factory();
			$pjBookingRoomModel = pjBookingRoomModel::factory();
			$pjRoomModel = pjRoomModel::factory();
			$nightMode = $this->option_arr['o_price_based_on'] == "nights";
			if ($nightMode)
			{
				$pjRoomModel->select(sprintf("t1.*, (SELECT GROUP_CONCAT(`TRN`.id SEPARATOR '~:~')
					FROM `%5\$s` AS `TRN`
					WHERE `TRN`.`room_id` = `t1`.`id` AND `TRN`.id NOT IN (SELECT `TBR`.`room_number_id`
					FROM `%1\$s` AS `TBR`
					INNER JOIN `%2\$s` AS `TB` ON
						`TB`.`id` = `TBR`.`booking_id` AND
						`TB`.`status` IN ('confirmed', 'pending') AND
						TB.date_from < '%4\$s' AND TB.date_to > '%3\$s'
					WHERE `TBR`.`room_id` = `t1`.`id`)) AS `room_number_ids`", 
					$pjBookingRoomModel->getTable(), $pjBookingModel->getTable(), $pjRoomModel->escapeStr($STORE['date_from']), $pjRoomModel->escapeStr($STORE['date_to']), $pjRoomNumberModel->getTable()));
			} else {
				$pjRoomModel->select(sprintf("t1.*, (SELECT GROUP_CONCAT(`TRN`.id SEPARATOR '~:~')
					FROM `%5\$s` AS `TRN`
					WHERE `TRN`.`room_id` = `t1`.`id` AND `TRN`.id NOT IN (SELECT `TBR`.`room_number_id`
					FROM `%1\$s` AS `TBR`
					INNER JOIN `%2\$s` AS `TB` ON
						`TB`.`id` = `TBR`.`booking_id` AND
						`TB`.`status` IN ('confirmed', 'pending') AND
						TB.date_from <= '%4\$s' AND TB.date_to >= '%3\$s'
					WHERE `TBR`.`room_id` = `t1`.`id`)) AS `room_number_ids`", 
					$pjBookingRoomModel->getTable(), $pjBookingModel->getTable(), $pjRoomModel->escapeStr($STORE['date_from']), $pjRoomModel->escapeStr($STORE['date_to']), $pjRoomNumberModel->getTable()));
			}
			$avail_room_number_arr = $pjRoomModel->whereIn('t1.id', array_keys($STORE['content']))->findAll()->toArray('room_number_ids', '~:~')->getDataPair('id');
			
			$restrictions = pjRestrictionRoomModel::factory()
				->select("t3.room_id, GROUP_CONCAT(`t3`.id SEPARATOR '~:~') AS `unavail_ids`")
				->join('pjRestriction', "t2.id=t1.restriction_id", 'inner')
				->join('pjRoomNumber', "t3.id=t1.room_number_id", 'inner')
				->join('pjRoom', "t4.id=t3.room_id", 'inner')
				->where(sprintf('t2.date_from %s', $nightMode ? "<" : "<="), $STORE['date_to'])
				->where(sprintf('t2.date_to %s', $nightMode ? ">" : ">="), $STORE['date_from'])
				->groupBy('t3.room_id')
				->findAll()
				->toArray('unavail_ids', '~:~')
				->getDataPair('room_id', 'unavail_ids');
			
			$pjBookingRoomModel->setBatchFields(array('booking_id', 'room_id', 'room_number_id', 'adults', 'children', 'price'));
			foreach ($STORE['content'] as $room_id => $rooms_arr)
			{
				$_arr_diff = $avail_room_number_arr[$room_id]['room_number_ids'];
				if(isset($restrictions[$room_id]) && count($restrictions[$room_id]) > 0)
				{
					$_arr_diff = array_diff($avail_room_number_arr[$room_id]['room_number_ids'], $restrictions[$room_id]);
				}
				$arr_diff = array_values($_arr_diff);
				$i = 0;
				foreach ($rooms_arr as $index => $room)
				{
					$pjBookingRoomModel->addBatchRow(array($booking_id, (int) $room_id, $arr_diff[$i], (int) $room['adults'], (int) $room['children'], (float) $room['raw_price']));
					$i += 1;
				}
			}
			$pjBookingRoomModel->insertBatch();
			
			if (isset($STORE['extras']) && is_array($STORE['extras']))
			{
				$pjBookingExtraModel = pjBookingExtraModel::factory()->setBatchFields(array('booking_id', 'extra_id', 'price'));
				foreach ($STORE['extras'] as $extra)
				{
					$pjBookingExtraModel->addBatchRow(array($booking_id, $extra['id'], $extra['price']));
				}
				$pjBookingExtraModel->insertBatch();
			}
			
			$invoice_arr = $this->pjActionGenerateInvoice($booking_id);
			
			# Email --
			$booking_arr = $pjBookingModel
				->reset()
				->select("t1.*, t2.content AS country,
						AES_DECRYPT(t1.cc_type, '".PJ_SALT."') AS `cc_type`,
						AES_DECRYPT(t1.cc_num, '".PJ_SALT."') AS `cc_num`,
						AES_DECRYPT(t1.cc_exp_month, '".PJ_SALT."') AS `cc_exp_month`,
						AES_DECRYPT(t1.cc_exp_year, '".PJ_SALT."') AS `cc_exp_year`,
						AES_DECRYPT(t1.cc_code, '".PJ_SALT."') AS `cc_code`")
				->join('pjMultiLang', "t2.model='pjCountry' AND t2.foreign_id=t1.c_country AND t2.locale=t1.locale_id AND t2.field='name'", 'left outer')
				->find($booking_id)
				->getData();
				
			if (!empty($booking_arr))
			{
				if (isset($pjBookingExtraModel))
				{
					$pjBookingExtraModel->reset();
				} else {
					$pjBookingExtraModel = pjBookingExtraModel::factory();
				}
				
				$booking_arr['extras'] = $pjBookingExtraModel
					->select('t1.*, t2.content AS `name`')
					->join('pjMultiLang', "t2.model='pjExtra' AND t2.foreign_id=t1.extra_id AND t2.field='name' AND t2.locale='".$booking_arr['locale_id']."'", 'left join')
					->where('t1.booking_id', $booking_id)
					->findAll()
					->getData();
					
				$booking_arr['rooms'] = $pjBookingRoomModel
					->reset()
					->select('t1.*, COUNT(t1.id) AS `cnt`, t2.content AS `name`')
					->join('pjMultiLang', "t2.model='pjRoom' AND t2.foreign_id=t1.room_id AND t2.field='name' AND t2.locale='".$booking_arr['locale_id']."'", 'left join')
					->where('t1.booking_id', $booking_id)
					->groupBy('t1.booking_id, t1.room_id')
					->findAll()
					->getData();
				
				$calendar_arr = pjCalendarModel::factory()
					->select(sprintf("t1.*, t2.content AS confirm_subject_client, t3.content AS confirm_tokens_client,
						t4.content AS confirm_subject_admin, t5.content AS confirm_tokens_admin, t6.content AS `confirm_sms_admin`,
						(SELECT GROUP_CONCAT(`email`) FROM `%1\$s` WHERE `status` = 'T') AS `email`,
						(SELECT GROUP_CONCAT(`phone`) FROM `%1\$s` WHERE `status` = 'T' AND `phone` IS NOT NULL) AS `phone`",
						pjUserModel::factory()->getTable()))
					->join('pjMultiLang', sprintf("t2.model='pjCalendar' AND t2.foreign_id=t1.id AND t2.field='confirm_subject_client' AND t2.locale='%u'", $booking_arr['locale_id']), 'left outer')
					->join('pjMultiLang', sprintf("t3.model='pjCalendar' AND t3.foreign_id=t1.id AND t3.field='confirm_tokens_client' AND t3.locale='%u'", $booking_arr['locale_id']), 'left outer')
					->join('pjMultiLang', sprintf("t4.model='pjCalendar' AND t4.foreign_id=t1.id AND t4.field='confirm_subject_admin' AND t4.locale='%u'", $booking_arr['locale_id']), 'left outer')
					->join('pjMultiLang', sprintf("t5.model='pjCalendar' AND t5.foreign_id=t1.id AND t5.field='confirm_tokens_admin' AND t5.locale='%u'", $booking_arr['locale_id']), 'left outer')
					->join('pjMultiLang', sprintf("t6.model='pjCalendar' AND t6.foreign_id=t1.id AND t6.field='confirm_sms_admin' AND t6.locale='%u'", $booking_arr['locale_id']), 'left outer')
					->find($booking_arr['calendar_id'])
					->toArray('email', ',')
					->toArray('phone', ',')
					->getData();
			}
			pjFront::pjActionConfirmSend($this->option_arr, $booking_arr, @$calendar_arr, 'confirm');
			# Email ---
			
			$_SESSION[$this->defaultStore] = NULL;
			unset($_SESSION[$this->defaultStore]);
			
			$_SESSION[$this->defaultVoucher] = NULL;
			unset($_SESSION[$this->defaultVoucher]);
			
			pjAppController::jsonResponse(array(
				'status' => 'OK',
				'code' => 201,
				'text' => __('system_201', true),
				'invoice_uuid' => @$invoice_arr['data']['uuid']
			));
		}
		exit;
	}

	public function pjActionApplyCode()
	{
		$this->setAjax(true);
		
		if ($this->isXHR())
		{
			if (!isset($_POST['code']) || !pjValidation::pjActionNotEmpty($_POST['code']))
			{
				pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 104, 'text' => __('system_104', true)));
			}
			
			$pre = array(
				'date_from' => $_SESSION[$this->defaultStore]['date_from'],
				'date_to' => $_SESSION[$this->defaultStore]['date_to']
			);
			$room_ids = array_keys($_SESSION[$this->defaultStore]['all_rooms']);
			
			$response = pjAppController::getDiscount(array_merge($_POST, $pre), $this->option_arr);
			if ($response['status'] == 'OK')
			{
				$intersect = array_intersect(array_keys($response['voucher_rooms']), $room_ids);
				if (empty($response['voucher_room']) || !empty($intersect))
				{
					$_SESSION[$this->defaultVoucher] = array(
						'voucher_code' => $response['voucher_code'],
						'voucher_rooms' => $response['voucher_rooms']
					);
				}
			}
			pjAppController::jsonResponse($response);
		}
		exit;
	}
	
	public function pjActionRemoveCode()
	{
		$this->setAjax(true);
		
		if ($this->isXHR())
		{
			if (isset($_SESSION[$this->defaultVoucher]) && !empty($_SESSION[$this->defaultVoucher]))
			{
				$_SESSION[$this->defaultVoucher] = NULL;
				unset($_SESSION[$this->defaultVoucher]);
			}
			pjAppController::jsonResponse(array('status' => 'OK', 'code' => 205, 'text' => __('system_205', true)));
		}
		exit;
	}
	
	public function pjActionGetAdultsChildren()
	{
		$this->setAjax(true);
	
		if ($this->isXHR())
		{
			pjAppController::jsonResponse($this->pjActionAdultsChildren());
		}
		exit;
	}

	protected function pjActionCheckAvailability($cid)
	{
		$STORE =& $_SESSION[$this->defaultStore];
		if (is_null($STORE) || empty($STORE) || !isset($STORE['date_from'], $STORE['date_to'], $STORE['guests'], $STORE['all_rooms']))
		{
			return FALSE;
		}
		$nightMode = $this->option_arr['o_price_based_on'] == "nights";
		
		$hours = $this->option_arr['o_pending_time'] / 60;
		$remainder = $this->option_arr['o_pending_time'] % 60;
		if ($remainder === 0)
		{
			$pending_time = sprintf("%u:00:00", $hours);
		} else {
			$pending_time = sprintf("%u:%u:00", $hours, $remainder);
		}
		
		$pjRoomModel = pjRoomModel::factory();
		
		$arr = pjRoomModel::factory()
			->select(sprintf("t1.*,
				(SELECT COUNT(*)
					FROM `%1\$s` AS `TBR`
					INNER JOIN `%2\$s` AS `TB` ON
						`TB`.`id` = `TBR`.`booking_id` AND
						(`TB`.`status` = 'confirmed' OR (`TB`.`status` = 'pending' AND `TB`.`created` >= SUBTIME(NOW(), '%7\$s'))) AND
						`TB`.`date_from` %5\$s '%4\$s' AND
						`TB`.`date_to` %6\$s '%3\$s'
					WHERE `TBR`.`room_id` = `t1`.`id`
					LIMIT 1) AS `bookings`
				",
				pjBookingRoomModel::factory()->getTable(),
				pjBookingModel::factory()->getTable(),
				$pjRoomModel->escapeStr($STORE['date_from']),
				$pjRoomModel->escapeStr($STORE['date_to']),
				$nightMode ? "<" : "<=",
				$nightMode ? ">" : ">=",
				$pjRoomModel->escapeStr($pending_time)
			))
			->where('t1.id > 0')
			->where('t1.calendar_id', $cid)
			->where('t1.max_people * t1.cnt >=', $STORE['guests'])
			->findAll()
			->getDataPair('id');
		# --
		$tmp = pjBookingRoomModel::factory()
			->select('t1.*, t2.date_from, t2.date_to')
			->join('pjBooking', sprintf("t2.id=t1.booking_id AND (t2.status = 'confirmed' OR (t2.status = 'pending' AND t2.created >= SUBTIME(NOW(), '%5\$s'))) AND t2.date_from %3\$s '%2\$s' AND t2.date_to %4\$s '%1\$s'",
				$pjRoomModel->escapeStr($STORE['date_from']),
				$pjRoomModel->escapeStr($STORE['date_to']),
				$nightMode ? "<" : "<=",
				$nightMode ? ">" : ">=",
				$pjRoomModel->escapeStr($pending_time)), 'inner')
			->findAll()
			->getData();

		$sum = array();
		$init = array();
		$dt_from = strtotime($STORE['date_from']);
		$dt_to = strtotime($STORE['date_to']);
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
		
		foreach ($arr as $k => $room)
		{
			$arr[$k]['max_bookings'] = isset($sum[$room['id']]) && !empty($sum[$room['id']]) ? ceil(max($sum[$room['id']])) : 0;
		}
		# --

		foreach ($STORE['all_rooms'] as $room_id => $rooms)
		{
			if (!isset($arr[$room_id]) || $arr[$room_id]['cnt'] - $arr[$room_id]['max_bookings'] < count($rooms))
			{
				return FALSE;
			}
		}
		
		return TRUE;
	}

	static protected function setLastUsage($opts, $foreign_id)
	{
		if (is_array($opts)
			&& isset($opts['o_last_usage_time'])
			&& preg_match('/^\d{10}$/', $opts['o_last_usage_time'])
			&& date("Y-m-d") != date("Y-m-d", $opts['o_last_usage_time']))
		{
			# API ->
			$apiLogin = include dirname(dirname(PJ_INSTALL_PATH)) . '/login-api.php';
			if (is_object($apiLogin))
			{
				$apiLogin->setUsage();
			}
			# API <-
	
			# Update option
			pjOptionModel::factory()
				->where('foreign_id', $foreign_id)
				->where('`key`', 'o_last_usage_time')
				->limit(1)
				->modifyAll(array('value' => time()));
			return TRUE;
		}
		return FALSE;
	}
	
	public function isXHR()
	{
		// CORS
		return parent::isXHR() || isset($_SERVER['HTTP_ORIGIN']);
	}
	
	static protected function allowCORS()
	{
		$origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : '*';
		header("Access-Control-Allow-Origin: $origin");
		header("Access-Control-Allow-Credentials: true");
		header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
		header("Access-Control-Allow-Headers: Origin, X-Requested-With");
	}
}
?>