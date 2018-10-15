<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjAdmin extends pjAppController
{
	public $defaultUser = 'admin_user';
	
	public $requireLogin = true;
	
	public function __construct($requireLogin=null)
	{
		$this->setLayout('pjActionAdmin');
		
		if (!is_null($requireLogin) && is_bool($requireLogin))
		{
			$this->requireLogin = $requireLogin;
		}
		
		if ($this->requireLogin)
		{
			if (!$this->isLoged() && !in_array(@$_GET['action'], array('pjActionLogin', 'pjActionForgot', 'pjActionPreview', 'pjActionContact')))
			{
				pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdmin&action=pjActionLogin");
			}
		}
	}
	public function beforeFilter()
	{
		parent::beforeFilter();
	}
	
	public function beforeRender()
	{

	}
		
	public function pjActionIndex()
	{
		$this->checkLogin();
		
		if ($this->isAdmin() || $this->isEditor())
		{
			$this->notConfirmed();
							
			$pjBookingModel = pjBookingModel::factory();
			$pjRoomModel = pjRoomModel::factory();
			$pjRoomNumberModel = pjRoomNumberModel::factory();
			$pjBookingRoomModel = pjBookingRoomModel::factory();
			$pjMultiLangModel = pjMultiLangModel::factory();
			
			$today = date('Y-m-d');
			$nightMode = $this->option_arr['o_price_based_on'] == 'nights';
			
			$latest_booking_arr = array();
			$latest_count = $pjBookingModel->findCount()->getData();
			if ($latest_count > 0)
			{
				$statement = sprintf("SELECT TB.*, DATEDIFF(`date_to`, `date_from`) AS nights,
					(SELECT GROUP_CONCAT(CONCAT_WS(:ws, `m`.`content`, `TRN`.`number`) SEPARATOR :sep) 
						FROM `%3\$s` AS `TBR` 
						INNER JOIN `%1\$s` AS `TR` ON `TR`.id = `TBR`.`room_id` 
						INNER JOIN `%4\$s` AS `TRN` ON `TRN`.id = `TBR`.`room_number_id`
						LEFT OUTER JOIN `%5\$s` AS `m` ON m.model=:model AND m.foreign_id=TR.id AND m.field=:field AND m.locale=:locale
						WHERE `TBR`.`booking_id`=`TB`.`id`) AS `rooms`
					FROM `%2\$s` AS TB 
					WHERE 1 
					ORDER BY created DESC 
					LIMIT 0,2",
					$pjRoomModel->getTable(),
					$pjBookingModel->getTable(),
					$pjBookingRoomModel->getTable(),
					$pjRoomNumberModel->getTable(),
					$pjMultiLangModel->getTable()
				);
				$latest_booking_arr = $pjBookingModel->reset()->prepare($statement)->exec(array(
					'model' => 'pjRoom',
					'field' => 'name',
					'locale' => $this->getLocaleId(),
					'sep' => '<br>',
					'ws' => ' '
				))->getData();
			}
			$this->set('latest_booking_arr', $latest_booking_arr);
			
			$arrivals_arr = array();
			$arrival_count = $pjBookingModel
				->reset()
				->where('t1.status', 'confirmed')
				->where('t1.date_from', $today)
				->findCount()
				->getData();
			if ($arrival_count > 0)
			{
				$statement = sprintf("SELECT TB.*, DATEDIFF(`date_to`, `date_from`) AS `nights`,
					(SELECT GROUP_CONCAT(CONCAT_WS(:ws, `m`.`content`, `TRN`.`number`) SEPARATOR :sep) 
						FROM `%3\$s` AS `TBR` 
						INNER JOIN `%1\$s` AS `TR` ON `TR`.id = `TBR`.`room_id` 
						INNER JOIN `%4\$s` AS `TRN` ON `TRN`.id = `TBR`.`room_number_id` 
						LEFT OUTER JOIN `%5\$s` AS `m` ON m.model=:model AND m.foreign_id=TR.id AND m.field=:field AND m.locale=:locale
						WHERE `TBR`.`booking_id`=`TB`.`id`
					) AS `rooms`
					FROM `%2\$s` AS TB 
					WHERE `date_from` = :today 
					AND `status` = :confirmed
					ORDER BY created DESC",
					$pjRoomModel->getTable(),
					$pjBookingModel->getTable(),
					$pjBookingRoomModel->getTable(),
					$pjRoomNumberModel->getTable(),
					$pjMultiLangModel->getTable()
				);
				$arrivals_arr = $pjBookingModel->reset()->prepare($statement)->exec(array(
					'today' => $today,
					'confirmed' => 'confirmed',
					'ws' => ' ',
					'sep' => '<br>',
					'model' => 'pjRoom',
					'field' => 'name',
					'locale' => $this->getLocaleId()
				))->getData();
			}
			$this->set('arrival_count', $arrival_count);
			$this->set('arrivals_arr', $arrivals_arr);
			
			$departure_arr = array();
			$departure_count = $pjBookingModel->reset()->where('t1.status', 'confirmed')->where('t1.date_to', $today)->findCount()->getData();
			if ($departure_count > 0)
			{
				$statement = sprintf("SELECT TB.*, DATEDIFF(`date_to`, `date_from`) AS `nights`,
						(SELECT GROUP_CONCAT(CONCAT_WS(:ws, `m`.`content`, `TRN`.`number`) SEPARATOR :sep) 
							FROM `%3\$s` AS `TBR` 
							INNER JOIN `%1\$s` AS `TR` ON `TR`.id = `TBR`.`room_id` 
							INNER JOIN `%4\$s` AS `TRN` ON `TRN`.id = `TBR`.`room_number_id`
							LEFT OUTER JOIN `%5\$s` AS `m` ON m.model=:model AND m.foreign_id=TR.id AND m.field=:field AND m.locale=:locale 
							WHERE `TBR`.`booking_id`=`TB`.`id`) AS `rooms`
						FROM `%2\$s` AS `TB`
						WHERE `date_to` = :today 
						AND `status` = :status
						ORDER BY `created` DESC",
					$pjRoomModel->getTable(),
					$pjBookingModel->getTable(),
					$pjBookingRoomModel->getTable(),
					$pjRoomNumberModel->getTable(),
					$pjMultiLangModel->getTable()
				);
				$departure_arr = $pjBookingModel->reset()->prepare($statement)->exec(array(
					'status' => 'confirmed',
					'today' => $today,
					'ws' => ' ',
					'sep' => '<br>',
					'model' => 'pjRoom',
					'field' => 'name',
					'locale' => $this->getLocaleId()
				))->getData();
			}
			$this->set('departure_count', $departure_count);
			$this->set('departure_arr', $departure_arr);
			
			$statement = sprintf("SELECT 1,
				(SELECT COUNT(room_id) FROM `%3\$s` AS `t1` INNER JOIN `%1\$s` AS `t2` ON t2.id = t1.room_id WHERE 1 AND t1.booking_id IN (
					SELECT `TB`.`id` 
					FROM `%2\$s` AS TB 
					WHERE TB.status = :confirmed 
					AND TB.`date_from` <= :today 
					AND :today <= TB.`date_to`) LIMIT 1) AS `booked_today`,
				(SELECT COUNT(room_id) FROM `%3\$s` AS `t1` INNER JOIN `%1\$s` AS `t2` ON t2.id = t1.room_id WHERE 1 AND t1.booking_id IN (
					SELECT `TB`.`id` 
					FROM `%2\$s` AS TB 
					WHERE TB.status = :pending 
					AND TB.`date_from` <= :today 
					AND :today <= TB.`date_to`) LIMIT 1) AS `pending_today`,
				(SELECT SUM(`cnt`) FROM `%1\$s` AS `TR` WHERE 1) AS `total_rooms`",
				$pjRoomModel->getTable(),
				$pjBookingModel->getTable(),
				$pjBookingRoomModel->getTable()
			);
			$this->set('arr', $pjBookingModel->reset()->prepare($statement)->exec(array(
				'today' => $today,
				'confirmed' => 'confirmed',
				'pending' => 'pending'
			))->getDataIndex(0));
			
			$room_count = $pjRoomModel->findCount()->getData();
			if ($room_count > 0)
			{
				$statement = sprintf("SELECT TR.*, m.content AS `name`,
							(SELECT COUNT(room_id) FROM `%3\$s` AS TBR WHERE TBR.room_id=TR.id AND TBR.booking_id IN (SELECT TB.id FROM `%2\$s` AS TB WHERE TB.`date_from` %5\$s :today AND :today %6\$s TB.`date_to` AND TB.status=:confirmed)) AS `booked_rooms`,
							(SELECT COUNT(room_id) FROM `%3\$s` AS TBR WHERE TBR.room_id=TR.id AND TBR.booking_id IN (SELECT TB.id FROM `%2\$s` AS TB WHERE TB.`date_from` %5\$s :today AND :today %6\$s TB.`date_to` AND TB.status=:pending)) AS `pending_rooms` 
						FROM `%1\$s` AS TR
						LEFT OUTER JOIN `%4\$s` AS `m` ON m.model=:model AND m.foreign_id=TR.id AND m.field=:field AND m.locale=:locale
						WHERE 1 
						ORDER BY `name` ASC",
					$pjRoomModel->getTable(),
					$pjBookingModel->getTable(),
					$pjBookingRoomModel->getTable(),
					$pjMultiLangModel->getTable(),
					$nightMode ? '<=' : '<',
					$nightMode ? '<' : '<'
				);
				$this->set('avail_room_arr', $pjRoomModel->prepare($statement)->exec(array(
					'pending' => 'pending',
					'confirmed' => 'confirmed',
					'today' => $today,
					'model' => 'pjRoom',
					'field' => 'name',
					'locale' => $this->getLocaleId(),
				))->getData());
			}
			
			# Restrictions
			$restrictions = pjRestrictionRoomModel::factory()
				->select('t3.room_id, COUNT(t3.room_id) AS `cnt`')
				->join('pjRestriction', "t2.id=t1.restriction_id", 'inner')
				->join('pjRoomNumber', "t3.id=t1.room_number_id", 'inner')
				->join('pjRoom', "t4.id=t3.room_id", 'inner')
				->where(sprintf('t2.date_from %s', $nightMode ? "<" : "<="), $today)
				->where(sprintf('t2.date_to %s', $nightMode ? ">" : ">="), $today)
				->groupBy('t3.room_id')
				->findAll()
				->getDataPair('room_id', 'cnt');
			$this->set('restrictions', $restrictions);
			# Restrictions
			
			$conditions = NULL;
			# breakfast today
			#$conditions = " AND `TB`.`date_from` < :today AND :today <= `TB`.`date_to`";
			# sleeping today
			$conditions = " AND `TB`.`date_from` <= :today AND :today < `TB`.`date_to`";
			$conditions .= " AND `TB`.`status`= :confirmed";
			$statement = sprintf("SELECT 1,
					  (SELECT SUM(TBR.`adults` + TBR.`children`) FROM `%3\$s` AS `TBR` WHERE `TBR`.`booking_id` IN(SELECT `TB`.`id` FROM `%2\$s` AS `TB` WHERE 1 %4\$s)) AS `guests`,
					  (SELECT SUM(TBR.`adults`) FROM `%3\$s` AS `TBR` WHERE `TBR`.`booking_id` IN(SELECT `TB`.`id` FROM `%2\$s` AS `TB` WHERE 1 %4\$s)) AS `total_adults`,
					  (SELECT SUM(TBR.`children`) FROM `%3\$s` AS `TBR` WHERE `TBR`.`booking_id` IN(SELECT `TB`.`id` FROM `%2\$s` AS `TB` WHERE 1 %4\$s)) AS `total_children`",
				$pjRoomModel->getTable(),
				$pjBookingModel->getTable(),
				$pjBookingRoomModel->getTable(),
				$conditions
			);	
			$sleeping = $pjBookingModel->reset()->prepare($statement)->exec(array(
				'confirmed' => 'confirmed',
				'today' => $today
			))->getDataIndex(0);
			$this->set('sleeping', $sleeping);
			
			$conditions = NULL;
			$conditions = " AND `TB`.`date_from` = :today";
			$conditions .= " AND `TB`.`status` = :confirmed";
			$statement = sprintf("SELECT 1,
					(SELECT SUM(TBR.`adults` + TBR.`children`) FROM `%3\$s` AS `TBR` WHERE `TBR`.`booking_id` IN(SELECT `TB`.`id` FROM `%2\$s` AS `TB` WHERE 1 %4\$s)) AS `guests`,
					(SELECT SUM(TBR.`adults`) FROM `%3\$s` AS `TBR` WHERE `TBR`.`booking_id` IN(SELECT `TB`.`id` FROM `%2\$s` AS `TB` WHERE 1 %4\$s)) AS `total_adults`,
					(SELECT SUM(TBR.`children`) FROM `%3\$s` AS `TBR` WHERE `TBR`.`booking_id` IN(SELECT `TB`.`id` FROM `%2\$s` AS `TB` WHERE 1 %4\$s)) AS `total_children`",
				$pjRoomModel->getTable(),
				$pjBookingModel->getTable(),
				$pjBookingRoomModel->getTable(),
				$conditions
			);	
				
			$arrive_today = $pjBookingModel->reset()->prepare($statement)->exec(array(
				'today' => $today,
				'confirmed' => 'confirmed'
			))->getData();
			
			$this->set('arrive_today', $arrive_today);
				
			$conditions = NULL;
			$conditions = " AND `TB`.`date_to` = :today";
			$conditions .= " AND `TB`.`status` = :confirmed";
			$statement = sprintf("SELECT 1,
					(SELECT SUM(TBR.`adults` + TBR.`children`) FROM `%3\$s` AS `TBR` WHERE `TBR`.`booking_id` IN(SELECT `TB`.`id` FROM `%2\$s` AS `TB` WHERE 1 %4\$s)) AS `guests`,
					(SELECT SUM(TBR.`adults`) FROM `%3\$s` AS `TBR` WHERE `TBR`.`booking_id` IN(SELECT `TB`.`id` FROM `%2\$s` AS `TB` WHERE 1 %4\$s)) AS `total_adults`,
					(SELECT SUM(TBR.`children`) FROM `%3\$s` AS `TBR` WHERE `TBR`.`booking_id` IN(SELECT `TB`.`id` FROM `%2\$s` AS `TB` WHERE 1 %4\$s)) AS `total_children`",
				$pjRoomModel->getTable(),
				$pjBookingModel->getTable(),
				$pjBookingRoomModel->getTable(),
				$conditions
			);					
			$leave_today = $pjBookingModel->reset()->prepare($statement)->exec(array(
				'today' => $today,
				'confirmed' => 'confirmed'
			))->getData();
			$this->set('leave_today',  $leave_today);
			
			$this->appendJs('jsapi', 'https://www.google.com/', TRUE);
			$this->appendJs('pjAdmin.js');
			$this->appendJs('pjAdminDashboard.js');
		} else {
			$this->set('status', 2);
		}
	}
	
	public function pjActionChartGet()
	{
		$this->setAjax(true);
		
		if ($this->isXHR() && $this->isLoged())
		{
			$min = 1;
			$max = 7;
			if (isset($_GET['type']))
			{
				switch ($_GET['type'])
				{
					case 2:
						$min = -7;
						$max = -1;
						break;
					case 1:
					default:
						$min = 1;
						$max = 7;
						break;
				}
			}
			
			$iso_date = date("Y-n-j");
			list($y, $m, $d) = explode("-", $iso_date);
				
			$bookings = array();
			
			$rooms = pjRoomModel::factory()
				->select('t1.id, t2.content AS `name`')
				->join('pjMultiLang', sprintf("t2.model='pjRoom' AND t2.foreign_id=t1.id AND t2.field='name' AND t2.locale='%u'", $this->getLocaleId()), 'left outer')
				->orderBy('`name` ASC')
				->findAll()
				->getData();
			
			$pjBookingRoomModel = pjBookingRoomModel::factory();
			foreach (range($min, $max) as $i)
			{
				$time = mktime(0, 0, 0, $m, $d+$i, $y);
				$date = date("Y-m-d", $time);
			
				$bookings[$date] = $pjBookingRoomModel
					->reset()
					->select("t1.room_id, COUNT(t1.room_id) AS `cnt`")
					->join('pjBooking', "t2.id=t1.booking_id", 'inner')
					->join('pjRoom', 't3.id=t1.room_id', 'inner')
					->where('t2.status', 'confirmed')
					->where(sprintf("('%s' BETWEEN t2.date_from AND t2.date_to)", $pjBookingRoomModel->escapeStr($date)))
					->groupBy('t1.room_id')
					->findAll()
					->getData();
			}
			
			$result = array('cols' => array(), 'rows' => array());
			$result['cols'][] = array(
				'id' => 0,
				'label' => 'Date',
				'type' => 'string'
			);
			foreach ($rooms as $room)
			{
				$result['cols'][] = array(
					'id' => $room['id'],
					'label' => $room['name'],
					'type' => 'number'
				);
			}
			
			$i = 0;
			foreach ($bookings as $date => $group)
			{
				$result['rows'][$i] = array('c' => array());
				foreach ($result['cols'] as $c => $col)
				{
					if ($c == 0)
					{
						$result['rows'][$i]['c'][0] = array('v' => $date, 'f' => pjUtil::formatDate($date, 'Y-m-d', 'd/m'));
					} else {
						$result['rows'][$i]['c'][$c] = array('v' => 0);
						foreach ($group as $item)
						{
							if ($item['room_id'] == $col['id'])
							{
								$result['rows'][$i]['c'][$c] = array('v' => $item['cnt']);
							}
						}
					}
				}
				$i += 1;
			}

			pjAppController::jsonResponse($result);
		}
		exit;
	}
	
	public function pjActionForgot()
	{
		$this->setLayout('pjActionAdminLogin');
		
		if (isset($_POST['forgot_user']))
		{
			if (!isset($_POST['forgot_email']) || !pjValidation::pjActionNotEmpty($_POST['forgot_email']) || !pjValidation::pjActionEmail($_POST['forgot_email']))
			{
				pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdmin&action=pjActionForgot&err=AA10");
			}
			$pjUserModel = pjUserModel::factory();
			$user = $pjUserModel
				->where('t1.email', $_POST['forgot_email'])
				->limit(1)
				->findAll()
				->getData();
				
			if (count($user) != 1)
			{
				pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdmin&action=pjActionForgot&err=AA10");
			} else {
				$user = $user[0];
				
				$Email = new pjEmail();
				$Email
					->setContentType('text/html')
					->setTo($user['email'])
					->setFrom($user['email'])
					->setSubject(__('emailForgotSubject', true));
				
				if ($this->option_arr['o_send_email'] == 'smtp')
				{
					$Email
						->setTransport('smtp')
						->setSmtpHost($this->option_arr['o_smtp_host'])
						->setSmtpPort($this->option_arr['o_smtp_port'])
						->setSmtpUser($this->option_arr['o_smtp_user'])
						->setSender($this->option_arr['o_smtp_user'])
						->setSmtpPass($this->option_arr['o_smtp_pass'])
					;
				}
				
				$body = str_replace(
					array('{Name}', '{Password}'),
					array($user['name'], $user['password']),
					__('emailForgotBody', true)
				);

				if ($Email->send($body))
				{
					$err = "AA11";
				} else {
					$err = "AA12";
				}
				pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdmin&action=pjActionForgot&err=$err");
			}
		} else {
			$this->appendJs('jquery.validate.min.js', PJ_THIRD_PARTY_PATH . 'validate/');
			$this->appendJs('pjAdmin.js');
		}
	}
	
	public function pjActionMessages()
	{
		$this->setAjax(true);
		header("Content-Type: text/javascript; charset=utf-8");
	}
	
	public function pjActionLogin()
	{
		$this->setLayout('pjActionAdminLogin');
		
		if (isset($_POST['login_user']))
		{
			if (!isset($_POST['login_email']) || !isset($_POST['login_password']) ||
				!pjValidation::pjActionNotEmpty($_POST['login_email']) ||
				!pjValidation::pjActionNotEmpty($_POST['login_password']) ||
				!pjValidation::pjActionEmail($_POST['login_email']))
			{
				// Data not validate
				pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdmin&action=pjActionLogin&err=4");
			}
			$pjUserModel = pjUserModel::factory();

			$user = $pjUserModel
				->where('t1.email', $_POST['login_email'])
				->where(sprintf("t1.password = AES_ENCRYPT('%s', '%s')", $pjUserModel->escapeStr($_POST['login_password']), PJ_SALT))
				->limit(1)
				->findAll()
				->getData();

			if (count($user) != 1)
			{
				# Login failed
				pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdmin&action=pjActionLogin&err=1");
			} else {
				$user = $user[0];
				unset($user['password']);
															
				if (!in_array($user['role_id'], array(1,2)))
				{
					# Login denied
					pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdmin&action=pjActionLogin&err=2");
				}
				
				if ($user['status'] != 'T')
				{
					# Login forbidden
					pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdmin&action=pjActionLogin&err=3");
				}
				
				# Login succeed
				$last_login = date("Y-m-d H:i:s");
    			$_SESSION[$this->defaultUser] = $user;
    			
    			# API <--
    			if (isset($apiLogin) && is_object($apiLogin))
    			{
    				$apiLogin->lastLogin();
    			}
    			# API -->
    			
    			# Update
    			$data = array();
    			$data['last_login'] = $last_login;
    			$pjUserModel->reset()->setAttributes(array('id' => $user['id']))->modify($data);

    			if ($this->isAdmin() || $this->isEditor())
    			{
	    			pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdmin&action=pjActionIndex");
    			}
			}
		} else {
			$this->appendJs('jquery.validate.min.js', PJ_THIRD_PARTY_PATH . 'validate/');
			$this->appendJs('pjAdmin.js');
		}
	}
	
	public function pjActionLogout()
	{
		if ($this->isLoged())
        {
        	unset($_SESSION[$this->defaultUser]);
        }
       	pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdmin&action=pjActionLogin");
	}
	
	public function pjActionProfileEmail()
	{
		$this->setAjax(true);
		
		if ($this->isXHR() && $this->isLoged())
		{
			if (isset($_GET['email']) && !empty($_GET['email']))
			{
				echo 0 == pjUserModel::factory()
					->where('t1.email', $_GET['email'])
					->where('t1.id !=', $this->getUserId())
					->findCount()
					->getData() ? 'true' : 'false';
			} else {
				echo 'false';
			}
		}
		exit;
	}
	
	public function pjActionProfile()
	{
		$this->checkLogin();
		
		if ($this->isEditor())
		{
			if (isset($_POST['profile_update']))
			{
				$pjUserModel = pjUserModel::factory();
				$arr = $pjUserModel->find($this->getUserId())->getData();
				$data = array();
				$data['role_id'] = $arr['role_id'];
				$data['status'] = $arr['status'];
				$post = array_merge($_POST, $data);
				
				$required = array('role_id', 'email', 'password', 'name', 'status');
				foreach ($required as $index)
				{
					if (!array_key_exists($index, $post))
					{
						pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdmin&action=pjActionProfile&err=AA16");
					}
				}
				
				if (!$pjUserModel->validates($post))
				{
					pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdmin&action=pjActionProfile&err=AA16");
				}
				
				if (0 != $pjUserModel->reset()->where('t1.email', $_POST['email'])->where('t1.id !=', $this->getUserId())->findCount()->getData())
				{
					pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdmin&action=pjActionProfile&err=AA15");
				}
				
				$pjUserModel->reset()->set('id', $this->getUserId())->modify($post);
				
				pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdmin&action=pjActionProfile&err=AA13");
			} else {
				$this->set('arr', pjUserModel::factory()->find($this->getUserId())->getData());
				$this->appendJs('jquery.validate.min.js', PJ_THIRD_PARTY_PATH . 'validate/');
				$this->appendJs('pjAdmin.js');
			}
		} else {
			$this->set('status', 2);
		}
	}
	
	protected function notConfirmed()
	{
		pjBookingModel::factory()
			->where('`status`', 'pending')
			->where(sprintf("UNIX_TIMESTAMP(NOW()) >= UNIX_TIMESTAMP(DATE_ADD(`created`, INTERVAL %u MINUTE))", $this->option_arr['o_pending_time']))
			->modifyAll(array('status' => 'not_confirmed'));
	}
}
?>