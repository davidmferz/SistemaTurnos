<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjAdminRestrictions extends pjAdmin
{
	public function pjActionAddRestriction()
	{
		$this->setAjax(true);
	
		if ($this->isXHR() && $this->isLoged())
		{
			if (isset($_POST['add_restriction']))
			{
				if (!(isset($_POST['date_from'], $_POST['date_to'], $_POST['room_number_id'], $_POST['restriction_type']) 
					&& !empty($_POST['room_number_id'])
					&& !empty($_POST['date_from'])
					&& !empty($_POST['date_to'])
					&& !empty($_POST['restriction_type'])))
				{
					pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 101, 'text' => 'Missing, empty or invalid parameters.'));
				}
				$data = array();
				$data['date_from'] = pjUtil::formatDate($_POST['date_from'], $this->option_arr['o_date_format']);
				$data['date_to'] = pjUtil::formatDate($_POST['date_to'], $this->option_arr['o_date_format']);
				if (strtotime($data['date_from']) > strtotime($data['date_to']))
				{
					$tmp = $data['date_from'];
					$data['date_from'] = $data['date_to'];
					$data['date_to'] = $tmp;
				}
				
				$pjRoomModel = pjRoomModel::factory();
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
						$pjRoomModel->escapeStr($data['date_from']),
						$pjRoomModel->escapeStr($data['date_to']),
						$nightMode ? "<" : "<=",
						$nightMode ? ">" : ">=",
						$pjRoomModel->escapeStr($pending_time)), 'inner')
					->whereIn('t1.room_number_id', $_POST['room_number_id'])
					->findAll()
					->getData();
				
				if(!empty($tmp))
				{
					pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 100, 'title'=>__('lblRestrictionNotAdded', true), 'text' => __('system_130', true)));
				}
				
				$id = pjRestrictionModel::factory(array_merge($_POST, $data))->insert()->getInsertId();
				if ($id !== false && (int) $id > 0)
				{
					$pjRestrictionRoomModel = pjRestrictionRoomModel::factory();
					foreach ($_POST['room_number_id'] as $room_number_id)
					{
						$pjRestrictionRoomModel->addBatchRow(array($id, $room_number_id));
					}
					$pjRestrictionRoomModel->setBatchFields(array('restriction_id', 'room_number_id'))->insertBatch();
					
					pjAppController::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => 'Restriction has been added.'));
				}
				pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => 'Restriction has not been added.'));
			}
			
			$this->set('room_number_arr', pjRoomNumberModel::factory()
				->select('t1.*, t3.content AS `name`')
				->join('pjRoom', sprintf("t2.id=t1.room_id AND t2.calendar_id='%u'", $this->getForeignId()), 'inner')
				->join('pjMultiLang', sprintf("t3.model='pjRoom' AND t3.foreign_id=t1.room_id AND t3.field='name' AND t3.locale='%u'", $this->getLocaleId()), 'left outer')
				->orderBy('`name` ASC, t1.number ASC')
				->findAll()
				->getData()
			);
		}
	}
	
	public function pjActionDeleteRestriction()
	{
		$this->setAjax(true);
	
		if ($this->isXHR() && $this->isLoged())
		{
			if (!(isset($_GET['id']) && (int) $_GET['id'] > 0))
			{
				pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 101, 'text' => 'Missing, empty or invalid parameters.'));
			}
			if (pjRestrictionModel::factory()->set('id', $_GET['id'])->erase()->getAffectedRows() == 1)
			{
				pjRestrictionRoomModel::factory()->where('restriction_id', $_GET['id'])->eraseAll();
				pjAppController::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => 'Restriction has been deleted.'));
			}
			pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => 'Restriction has not been deleted.'));
		}
		exit;
	}
	
	public function pjActionDeleteRestrictionBulk()
	{
		$this->setAjax(true);
	
		if ($this->isXHR() && $this->isLoged())
		{
			if (isset($_POST['record']) && !empty($_POST['record']))
			{
				if (pjRestrictionModel::factory()->whereIn('id', $_POST['record'])->eraseAll()->getAffectedRows() > 0)
				{
					pjRestrictionRoomModel::factory()->whereIn('restriction_id', $_POST['record'])->eraseAll();
					pjAppController::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => 'Restriction(s) has been deleted.'));
				}
			}
			pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => 'Restriction(s) has not been deleted.'));
		}
		exit;
	}
	
	public function pjActionIndex()
	{
		$this->checkLogin();
		
		if ($this->isAdmin())
		{
			$this->set('is_empty', 0 == pjRestrictionModel::factory()->findCount()->getData());
			
			$types = array();
			foreach (__('restriction_types', true) as $k => $v)
			{
				$types[] = array(
					'value' => $k,
					'label' =>$v
				);
			}
			$this->set('types', pjAppController::jsonEncode($types));
			$this->set('room_count', pjRoomNumberModel::factory()
					->join('pjRoom', sprintf("t2.id=t1.room_id AND t2.calendar_id='%u'", $this->getForeignId()), 'inner')
					->join('pjMultiLang', sprintf("t3.model='pjRoom' AND t3.foreign_id=t1.room_id AND t3.field='name' AND t3.locale='%u'", $this->getLocaleId()), 'left outer')
					->orderBy('`name` ASC, t1.number ASC')
					->findCount()
					->getData()
			);
			$this->appendJs('jquery.datagrid.js', PJ_FRAMEWORK_LIBS_PATH . 'pj/js/');
			$this->appendJs('jquery.validate.min.js', PJ_THIRD_PARTY_PATH . 'validate/');
			$this->appendJs('jquery.multiselect.min.js', PJ_THIRD_PARTY_PATH . 'multiselect/');
			$this->appendCss('jquery.multiselect.css', PJ_THIRD_PARTY_PATH . 'multiselect/');
			$this->appendJs('pjAdminRestrictions.js');
			$this->appendJs('index.php?controller=pjAdmin&action=pjActionMessages', PJ_INSTALL_URL, true);
		} else {
			$this->set('status', 2);
		}
	}
	
	public function pjActionGetRestrictions()
	{
		$this->setAjax(true);
		
		if ($this->isXHR() && $this->isLoged())
		{
			$pjRestrictionModel = pjRestrictionModel::factory();
				
			$column = 'date_from';
			$direction = 'ASC';
			if (isset($_GET['direction']) && isset($_GET['column']) && in_array(strtoupper($_GET['direction']), array('ASC', 'DESC')))
			{
				$column = $_GET['column'];
				$direction = strtoupper($_GET['direction']);
			}

			$total = $pjRestrictionModel->findCount()->getData();
			$rowCount = isset($_GET['rowCount']) && (int) $_GET['rowCount'] > 0 ? (int) $_GET['rowCount'] : 10;
			$pages = ceil($total / $rowCount);
			$page = isset($_GET['page']) && (int) $_GET['page'] > 0 ? intval($_GET['page']) : 1;
			$offset = ((int) $page - 1) * $rowCount;
			if ($page > $pages)
			{
				$page = $pages;
			}

			/*$room_arr = pjRoomModel::factory()
				->select('t1.*, t2.content AS name')
				->join('pjMultiLang', "t2.model='pjRoom' AND t2.foreign_id=t1.id AND t2.field='name' AND t2.locale='".$this->getLocaleId()."'", 'left outer')
				->where('t1.calendar_id', $this->getForeignId())
				->orderBy('`name` ASC')
				->findAll()
				->getData();*/
			
			$pjRestrictionModel->orderBy("$column $direction")->limit($rowCount, $offset)->findAll();
			$data = $pjRestrictionModel->getData();
			
			if (!empty($data))
			{
				$rr = pjRestrictionRoomModel::factory()
					->select('t1.*, t2.number, t4.content AS `name`')
					->join('pjRoomNumber', 't2.id=t1.room_number_id', 'inner')
					->join('pjRoom', 't3.id=t2.room_id', 'inner')
					->join('pjMultiLang', sprintf("t4.model='pjRoom' AND t4.foreign_id=t2.room_id AND t4.field='name' AND t4.locale='%u'", $this->getLocaleId()), 'left outer')
					->whereIn('restriction_id', $pjRestrictionModel->getDataPair(null, 'id'))
					->findAll()
					->getData();
				
				foreach ($data as &$item)
				{
					$item['rooms'] = array();
					foreach ($rr as $r)
					{
						if ($item['id'] == $r['restriction_id'])
						{
							$item['rooms'][] = sprintf('%s - %s', $r['name'], $r['number']);
						}
					}
				}
			}
				
			pjAppController::jsonResponse(compact('data', 'total', 'pages', 'page', 'rowCount', 'column', 'direction'));
		}
		exit;
	}
	
	public function pjActionSaveRestriction()
	{
		$this->setAjax(true);
	
		if ($this->isXHR() && $this->isLoged())
		{
			$pjRestrictionModel = pjRestrictionModel::factory();
			if (!in_array($_POST['column'], $pjRestrictionModel->getI18n()))
			{
				$limit = $pjRestrictionModel->find($_GET['id'])->getData();
				if (in_array($_POST['column'], array('date_from', 'date_to')))
				{
					$_POST['value'] = pjUtil::formatDate($_POST['value'], $this->option_arr['o_date_format']);
					
					if ($_POST['column'] == 'date_from')
					{
						$date_from = $_POST['value'];
						$date_to = $limit['date_to'];
					} elseif ($_POST['column'] == 'date_to') {
						$date_from = $limit['date_from'];
						$date_to = $_POST['value'];
					}

					if (strtotime($date_from) > strtotime($date_to))
					{
						pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 101, 'text' => 'Invalid date range'));
					}
				}
				$pjRestrictionModel->set('id', $_GET['id'])->modify(array($_POST['column'] => $_POST['value']));
			} else {
				pjMultiLangModel::factory()->updateMultiLang(array($this->getLocaleId() => array($_POST['column'] => $_POST['value'])), $_GET['id'], 'pjRestriction');
			}
			pjAppController::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => ''));
		}
		exit;
	}
	
	public function pjActionUpdateRestriction()
	{
		$this->setAjax(true);
	
		if ($this->isXHR() && $this->isLoged())
		{
			if (isset($_POST['update_restriction']))
			{
				if (!(isset($_POST['date_from'], $_POST['date_to'], $_POST['room_number_id'], $_POST['restriction_type'], $_POST['id'])
					&& (int) $_POST['id'] > 0 
					&& !empty($_POST['room_number_id'])
					&& !empty($_POST['date_from'])
					&& !empty($_POST['date_to'])
					&& !empty($_POST['restriction_type'])))
				{
					pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 101, 'text' => 'Missing, empty or invalid parameters.'));
				}
				$data = array();
				$data['date_from'] = pjUtil::formatDate($_POST['date_from'], $this->option_arr['o_date_format']);
				$data['date_to'] = pjUtil::formatDate($_POST['date_to'], $this->option_arr['o_date_format']);
				if (strtotime($data['date_from']) > strtotime($data['date_to']))
				{
					$tmp = $data['date_from'];
					$data['date_from'] = $data['date_to'];
					$data['date_to'] = $tmp;
				}
				
				$pjRoomModel = pjRoomModel::factory();
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
						$pjRoomModel->escapeStr($data['date_from']),
						$pjRoomModel->escapeStr($data['date_to']),
						$nightMode ? "<" : "<=",
						$nightMode ? ">" : ">=",
						$pjRoomModel->escapeStr($pending_time)), 'inner')
					->whereIn('t1.room_number_id', $_POST['room_number_id'])
					->findAll()
					->getData();
				
				if(!empty($tmp))
				{
					pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 100, 'title'=>__('lblRestrictionNotUpdated', true), 'text' => __('system_131', true)));
				}
				
				pjRestrictionModel::factory()->set('id', $_POST['id'])->modify(array_merge($_POST, $data));
				
				$pjRestrictionRoomModel = pjRestrictionRoomModel::factory();
				$pjRestrictionRoomModel->where('restriction_id', $_POST['id'])->eraseAll()->reset();
				foreach ($_POST['room_number_id'] as $room_number_id)
				{
					$pjRestrictionRoomModel->addBatchRow(array($_POST['id'], $room_number_id));
				}
				$pjRestrictionRoomModel->setBatchFields(array('restriction_id', 'room_number_id'))->insertBatch();
				
				pjAppController::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => 'Restriction has been updated.'));
			}
			if (isset($_GET['id']))
			{
				$this->set('arr', pjRestrictionModel::factory()->find($_GET['id'])->getData());
				$this->set('rr_arr', pjRestrictionRoomModel::factory()->where('t1.restriction_id', $_GET['id'])->findAll()->getDataPair(null, 'room_number_id'));
				$this->set('room_number_arr', pjRoomNumberModel::factory()
					->select('t1.*, t3.content AS `name`')
					->join('pjRoom', sprintf("t2.id=t1.room_id AND t2.calendar_id='%u'", $this->getForeignId()), 'inner')
					->join('pjMultiLang', sprintf("t3.model='pjRoom' AND t3.foreign_id=t1.room_id AND t3.field='name' AND t3.locale='%u'", $this->getLocaleId()), 'left outer')
					->orderBy('`name` ASC, t1.number ASC')
					->findAll()
					->getData()
				);
			}
		}
	}
}
?>