<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjAdminDiscounts extends pjAdmin
{
	public function pjActionAddPackage()
	{
		$this->setAjax(true);
	
		if ($this->isXHR() && $this->isLoged())
		{
			if (isset($_POST['add_package']))
			{
				$data = array();
				$data['date_from'] = pjUtil::formatDate($_POST['date_from'], $this->option_arr['o_date_format']);
				$data['date_to'] = pjUtil::formatDate($_POST['date_to'], $this->option_arr['o_date_format']);
				$id = pjDiscountPackageModel::factory(array_merge($_POST, $data))->insert()->getInsertId();
				if ($id !== false && (int) $id > 0)
				{
					pjAppController::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => ''));
				}
				pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => ''));
			}
			
			$this->set('room_arr', pjRoomModel::factory()
				->select('t1.*, t2.content AS name')
				->join('pjMultiLang', "t2.model='pjRoom' AND t2.foreign_id=t1.id AND t2.field='name' AND t2.locale='".$this->getLocaleId()."'", 'left outer')
				->where('t1.calendar_id', $this->getForeignId())
				->orderBy('`name` ASC')
				->findAll()
				->getData()
			);
		}
	}
	
	public function pjActionAddFree()
	{
		$this->setAjax(true);
	
		if ($this->isXHR() && $this->isLoged())
		{
			if (isset($_POST['add_free']))
			{
				$data = array();
				$data['date_from'] = pjUtil::formatDate($_POST['date_from'], $this->option_arr['o_date_format']);
				$data['date_to'] = pjUtil::formatDate($_POST['date_to'], $this->option_arr['o_date_format']);
				$id = pjDiscountFreeModel::factory(array_merge($_POST, $data))->insert()->getInsertId();
				if ($id !== false && (int) $id > 0)
				{
					pjAppController::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => ''));
				}
				pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => ''));
			}
			
			$this->set('room_arr', pjRoomModel::factory()
				->select('t1.*, t2.content AS name')
				->join('pjMultiLang', "t2.model='pjRoom' AND t2.foreign_id=t1.id AND t2.field='name' AND t2.locale='".$this->getLocaleId()."'", 'left outer')
				->where('t1.calendar_id', $this->getForeignId())
				->orderBy('`name` ASC')
				->findAll()
				->getData()
			);
		}
	}
	
	public function pjActionAddCode()
	{
		$this->setAjax(true);
	
		if ($this->isXHR() && $this->isLoged())
		{
			if (isset($_POST['add_code']))
			{
				$data = array();
				$data['date_from'] = pjUtil::formatDate($_POST['date_from'], $this->option_arr['o_date_format']);
				$data['date_to'] = pjUtil::formatDate($_POST['date_to'], $this->option_arr['o_date_format']);
				$id = pjDiscountCodeModel::factory(array_merge($_POST, $data))->insert()->getInsertId();
				if ($id !== false && (int) $id > 0)
				{
					pjAppController::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => ''));
				}
				pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => ''));
			}
			
			$this->set('room_arr', pjRoomModel::factory()
				->select('t1.*, t2.content AS name')
				->join('pjMultiLang', "t2.model='pjRoom' AND t2.foreign_id=t1.id AND t2.field='name' AND t2.locale='".$this->getLocaleId()."'", 'left outer')
				->where('t1.calendar_id', $this->getForeignId())
				->orderBy('`name` ASC')
				->findAll()
				->getData()
			);
		}
	}
	
	public function pjActionCodes()
	{
		$this->checkLogin();
		
		if ($this->isAdmin())
		{
			$this->set('is_empty', 0 == pjDiscountCodeModel::factory()->findCount()->getData());
			
			$room_arr = pjRoomModel::factory()
				->select('t1.*, t2.content AS name')
				->join('pjMultiLang', "t2.model='pjRoom' AND t2.foreign_id=t1.id AND t2.field='name' AND t2.locale='".$this->getLocaleId()."'", 'left outer')
				->where('t1.calendar_id', $this->getForeignId())
				->orderBy('`name` ASC')
				->findAll()
				->getData();
			
			$rooms = array();
			foreach ($room_arr as $room)
			{
				$rooms[] = array(
					'value' => $room['id'],
					'label' => $room['name']
				);
			}
			$this->set('rooms', pjAppController::jsonEncode($rooms));
			
			$this->appendJs('jquery.datagrid.js', PJ_FRAMEWORK_LIBS_PATH . 'pj/js/');
			$this->appendJs('jquery.validate.min.js', PJ_THIRD_PARTY_PATH . 'validate/');
			$this->appendJs('pjAdminDiscounts.js');
			$this->appendJs('index.php?controller=pjAdmin&action=pjActionMessages', PJ_INSTALL_URL, true);
		} else {
			$this->set('status', 2);
		}
	}
	
	public function pjActionDeleteCode()
	{
		$this->setAjax(true);
	
		if ($this->isXHR() && $this->isLoged())
		{
			if (pjDiscountCodeModel::factory()->set('id', $_GET['id'])->erase()->getAffectedRows() == 1)
			{
				$response = array('status' => 'OK', 'code' => 200, 'text' => '');
			} else {
				$response = array('status' => 'ERR', 'code' => 100, 'text' => '');
			}
			pjAppController::jsonResponse($response);
		}
		exit;
	}
	
	public function pjActionDeleteCodeBulk()
	{
		$this->setAjax(true);
	
		if ($this->isXHR() && $this->isLoged())
		{
			if (isset($_POST['record']) && !empty($_POST['record']))
			{
				if (pjDiscountCodeModel::factory()->whereIn('id', $_POST['record'])->eraseAll()->getAffectedRows() > 0)
				{
					pjAppController::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => ''));
				}
			}
			pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => ''));
		}
		exit;
	}
	
	public function pjActionDeleteFree()
	{
		$this->setAjax(true);
	
		if ($this->isXHR() && $this->isLoged())
		{
			if (pjDiscountFreeModel::factory()->set('id', $_GET['id'])->erase()->getAffectedRows() == 1)
			{
				$response = array('status' => 'OK', 'code' => 200, 'text' => '');
			} else {
				$response = array('status' => 'ERR', 'code' => 100, 'text' => '');
			}
			pjAppController::jsonResponse($response);
		}
		exit;
	}
	
	public function pjActionDeleteFreeBulk()
	{
		$this->setAjax(true);
	
		if ($this->isXHR() && $this->isLoged())
		{
			if (isset($_POST['record']) && !empty($_POST['record']))
			{
				if (pjDiscountFreeModel::factory()->whereIn('id', $_POST['record'])->eraseAll()->getAffectedRows() > 0)
				{
					pjAppController::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => ''));
				}
			}
			pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => ''));
		}
		exit;
	}
	
	public function pjActionDeletePackage()
	{
		$this->setAjax(true);
	
		if ($this->isXHR() && $this->isLoged())
		{
			if (pjDiscountPackageModel::factory()->set('id', $_GET['id'])->erase()->getAffectedRows() == 1)
			{
				pjDiscountPackageItemModel::factory()->where('package_id', $_GET['id'])->eraseAll();
				$response = array('status' => 'OK', 'code' => 200, 'text' => '');
			} else {
				$response = array('status' => 'ERR', 'code' => 100, 'text' => '');
			}
			pjAppController::jsonResponse($response);
		}
		exit;
	}
	
	public function pjActionDeletePackageBulk()
	{
		$this->setAjax(true);
	
		if ($this->isXHR() && $this->isLoged())
		{
			if (isset($_POST['record']) && !empty($_POST['record']))
			{
				if (pjDiscountPackageModel::factory()->whereIn('id', $_POST['record'])->eraseAll()->getAffectedRows() > 0)
				{
					pjDiscountPackageItemModel::factory()->whereIn('package_id', $_POST['record'])->eraseAll();
					pjAppController::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => ''));
				}
			}
			pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => ''));
		}
		exit;
	}
	
	public function pjActionDeletePackageItem()
	{
		$this->setAjax(true);
	
		if ($this->isXHR() && $this->isLoged())
		{
			if (pjDiscountPackageItemModel::factory()->set('id', $_GET['id'])->erase()->getAffectedRows() == 1)
			{
				$response = array('status' => 'OK', 'code' => 200, 'text' => '');
			} else {
				$response = array('status' => 'ERR', 'code' => 100, 'text' => '');
			}
			pjAppController::jsonResponse($response);
		}
		exit;
	}
	
	public function pjActionFrees()
	{
		$this->checkLogin();
		
		if ($this->isAdmin())
		{
			$this->set('is_empty', 0 == pjDiscountFreeModel::factory()->findCount()->getData());
			
			$room_arr = pjRoomModel::factory()
				->select('t1.*, t2.content AS name')
				->join('pjMultiLang', "t2.model='pjRoom' AND t2.foreign_id=t1.id AND t2.field='name' AND t2.locale='".$this->getLocaleId()."'", 'left outer')
				->where('t1.calendar_id', $this->getForeignId())
				->orderBy('`name` ASC')
				->findAll()
				->getData();
			
			$rooms = array();
			foreach ($room_arr as $room)
			{
				$rooms[] = array(
					'value' => $room['id'],
					'label' => $room['name']
				);
			}
			$this->set('rooms', pjAppController::jsonEncode($rooms));
			
			$this->appendJs('jquery.datagrid.js', PJ_FRAMEWORK_LIBS_PATH . 'pj/js/');
			$this->appendJs('jquery.validate.min.js', PJ_THIRD_PARTY_PATH . 'validate/');
			$this->appendJs('pjAdminDiscounts.js');
			$this->appendJs('index.php?controller=pjAdmin&action=pjActionMessages', PJ_INSTALL_URL, true);
		} else {
			$this->set('status', 2);
		}
	}
	
	public function pjActionIndex()
	{
		$this->checkLogin();
		
		if ($this->isAdmin())
		{
			$this->set('is_empty', 0 == pjDiscountPackageModel::factory()->findCount()->getData());
			
			$room_arr = pjRoomModel::factory()
				->select('t1.*, t2.content AS name')
				->join('pjMultiLang', "t2.model='pjRoom' AND t2.foreign_id=t1.id AND t2.field='name' AND t2.locale='".$this->getLocaleId()."'", 'left outer')
				->where('t1.calendar_id', $this->getForeignId())
				->orderBy('`name` ASC')
				->findAll()
				->getData();
			
			$rooms = array();
			foreach ($room_arr as $room)
			{
				$rooms[] = array(
					'value' => $room['id'],
					'label' => $room['name']
				);
			}
			$this->set('rooms', pjAppController::jsonEncode($rooms));
			
			$this->appendJs('jquery.datagrid.js', PJ_FRAMEWORK_LIBS_PATH . 'pj/js/');
			$this->appendJs('jquery.validate.min.js', PJ_THIRD_PARTY_PATH . 'validate/');
			$this->appendJs('pjAdminDiscounts.js');
			$this->appendJs('index.php?controller=pjAdmin&action=pjActionMessages', PJ_INSTALL_URL, true);
		} else {
			$this->set('status', 2);
		}
	}

	public function pjActionGetCodes()
	{
		$this->setAjax(true);
		
		if ($this->isXHR() && $this->isLoged())
		{
			$pjDiscountCodeModel = pjDiscountCodeModel::factory();
				
			$column = 'date_from';
			$direction = 'ASC';
			if (isset($_GET['direction']) && isset($_GET['column']) && in_array(strtoupper($_GET['direction']), array('ASC', 'DESC')))
			{
				$column = $_GET['column'];
				$direction = strtoupper($_GET['direction']);
			}

			$total = $pjDiscountCodeModel->findCount()->getData();
			$rowCount = isset($_GET['rowCount']) && (int) $_GET['rowCount'] > 0 ? (int) $_GET['rowCount'] : 10;
			$pages = ceil($total / $rowCount);
			$page = isset($_GET['page']) && (int) $_GET['page'] > 0 ? intval($_GET['page']) : 1;
			$offset = ((int) $page - 1) * $rowCount;
			if ($page > $pages)
			{
				$page = $pages;
			}

			$data = $pjDiscountCodeModel
				->orderBy("$column $direction")->limit($rowCount, $offset)->findAll()->getData();
				
			pjAppController::jsonResponse(compact('data', 'total', 'pages', 'page', 'rowCount', 'column', 'direction'));
		}
		exit;
	}
	
	public function pjActionGetFrees()
	{
		$this->setAjax(true);
		
		if ($this->isXHR() && $this->isLoged())
		{
			$pjDiscountFreeModel = pjDiscountFreeModel::factory();
				
			$column = 'date_from';
			$direction = 'ASC';
			if (isset($_GET['direction']) && isset($_GET['column']) && in_array(strtoupper($_GET['direction']), array('ASC', 'DESC')))
			{
				$column = $_GET['column'];
				$direction = strtoupper($_GET['direction']);
			}

			$total = $pjDiscountFreeModel->findCount()->getData();
			$rowCount = isset($_GET['rowCount']) && (int) $_GET['rowCount'] > 0 ? (int) $_GET['rowCount'] : 10;
			$pages = ceil($total / $rowCount);
			$page = isset($_GET['page']) && (int) $_GET['page'] > 0 ? intval($_GET['page']) : 1;
			$offset = ((int) $page - 1) * $rowCount;
			if ($page > $pages)
			{
				$page = $pages;
			}

			$data = $pjDiscountFreeModel
				->orderBy("$column $direction")->limit($rowCount, $offset)->findAll()->getData();
				
			pjAppController::jsonResponse(compact('data', 'total', 'pages', 'page', 'rowCount', 'column', 'direction'));
		}
		exit;
	}

	public function pjActionGetPackages()
	{
		$this->setAjax(true);
		
		if ($this->isXHR() && $this->isLoged())
		{
			$pjDiscountPackageModel = pjDiscountPackageModel::factory();
				
			$column = 'date_from';
			$direction = 'ASC';
			if (isset($_GET['direction']) && isset($_GET['column']) && in_array(strtoupper($_GET['direction']), array('ASC', 'DESC')))
			{
				$column = $_GET['column'];
				$direction = strtoupper($_GET['direction']);
			}

			$total = $pjDiscountPackageModel->findCount()->getData();
			$rowCount = isset($_GET['rowCount']) && (int) $_GET['rowCount'] > 0 ? (int) $_GET['rowCount'] : 10;
			$pages = ceil($total / $rowCount);
			$page = isset($_GET['page']) && (int) $_GET['page'] > 0 ? intval($_GET['page']) : 1;
			$offset = ((int) $page - 1) * $rowCount;
			if ($page > $pages)
			{
				$page = $pages;
			}

			$data = $pjDiscountPackageModel
				->orderBy("$column $direction")->limit($rowCount, $offset)->findAll()->getData();
				
			pjAppController::jsonResponse(compact('data', 'total', 'pages', 'page', 'rowCount', 'column', 'direction'));
		}
		exit;
	}
	
	public function pjActionGetPackageItems()
	{
		$this->setAjax(true);
		
		if ($this->isXHR() && $this->isLoged())
		{
			$pjDiscountPackageItemModel = pjDiscountPackageItemModel::factory()
				->where('t1.package_id', $_GET['package_id']);
				
			$column = 'adults';
			$direction = 'ASC';
			if (isset($_GET['direction']) && isset($_GET['column']) && in_array(strtoupper($_GET['direction']), array('ASC', 'DESC')))
			{
				$column = $_GET['column'];
				$direction = strtoupper($_GET['direction']);
			}

			$total = $pjDiscountPackageItemModel->findCount()->getData();
			$rowCount = isset($_GET['rowCount']) && (int) $_GET['rowCount'] > 0 ? (int) $_GET['rowCount'] : 10;
			$pages = ceil($total / $rowCount);
			$page = isset($_GET['page']) && (int) $_GET['page'] > 0 ? intval($_GET['page']) : 1;
			$offset = ((int) $page - 1) * $rowCount;
			if ($page > $pages)
			{
				$page = $pages;
			}

			$data = $pjDiscountPackageItemModel
				->orderBy("$column $direction")->findAll()->getData();
				
			pjAppController::jsonResponse(compact('data', 'total', 'pages', 'page', 'rowCount', 'column', 'direction'));
		}
		exit;
	}
	
	public function pjActionPackageAddItem()
	{
		$this->setAjax(true);
		
		if ($this->isXHR() && $this->isLoged())
		{
			if (isset($_POST['add_item']))
			{
				$insert_id = pjDiscountPackageItemModel::factory($_POST)->insert()->getInsertId();
				if ($insert_id !== false && (int) $insert_id > 0)
				{
					pjAppController::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => ''));
				}
				pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => ''));
			}
		}
	}
	
	public function pjActionPackageItems()
	{
		$this->setAjax(true);
	}
	
	public function pjActionSaveCode()
	{
		$this->setAjax(true);
	
		if ($this->isXHR() && $this->isLoged())
		{
			$pjDiscountCodeModel = pjDiscountCodeModel::factory();
			if (!in_array($_POST['column'], $pjDiscountCodeModel->getI18n()))
			{
				$record = $pjDiscountCodeModel->find($_GET['id'])->getData();
				if (in_array($_POST['column'], array('date_from', 'date_to')))
				{
					$_POST['value'] = pjUtil::formatDate($_POST['value'], $this->option_arr['o_date_format']);
					
					if ($_POST['column'] == 'date_from')
					{
						$date_from = $_POST['value'];
						$date_to = $record['date_to'];
					} elseif ($_POST['column'] == 'date_to') {
						$date_from = $record['date_from'];
						$date_to = $_POST['value'];
					}

					if (strtotime($date_from) > strtotime($date_to))
					{
						pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 101, 'text' => 'Invalid date range'));
					}
				}
				$pjDiscountCodeModel->set('id', $_GET['id'])->modify(array($_POST['column'] => $_POST['value']));
			} else {
				pjMultiLangModel::factory()->updateMultiLang(array($this->getLocaleId() => array($_POST['column'] => $_POST['value'])), $_GET['id'], 'pjDiscountCode');
			}
			pjAppController::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => ''));
		}
		exit;
	}
	
	public function pjActionSaveFree()
	{
		$this->setAjax(true);
	
		if ($this->isXHR() && $this->isLoged())
		{
			$pjDiscountFreeModel = pjDiscountFreeModel::factory();
			if (!in_array($_POST['column'], $pjDiscountFreeModel->getI18n()))
			{
				$record = $pjDiscountFreeModel->find($_GET['id'])->getData();
				if (in_array($_POST['column'], array('date_from', 'date_to')))
				{
					$_POST['value'] = pjUtil::formatDate($_POST['value'], $this->option_arr['o_date_format']);
					
					if ($_POST['column'] == 'date_from')
					{
						$date_from = $_POST['value'];
						$date_to = $record['date_to'];
					} elseif ($_POST['column'] == 'date_to') {
						$date_from = $record['date_from'];
						$date_to = $_POST['value'];
					}

					if (strtotime($date_from) > strtotime($date_to))
					{
						pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 101, 'text' => 'Invalid date range'));
					}
				}
				if (in_array($_POST['column'], array('min_nights', 'max_nights', 'free_nights')))
				{
					if (!is_numeric($_POST['value']) || (int) $_POST['value'] < 0 || (int) $_POST['value'] > 255)
					{
						pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => 'Value out of range'));
					}
				}
				$pjDiscountFreeModel->set('id', $_GET['id'])->modify(array($_POST['column'] => $_POST['value']));
			} else {
				pjMultiLangModel::factory()->updateMultiLang(array($this->getLocaleId() => array($_POST['column'] => $_POST['value'])), $_GET['id'], 'pjDiscountFree');
			}
			pjAppController::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => ''));
		}
		exit;
	}
	
	public function pjActionSavePackage()
	{
		$this->setAjax(true);
	
		if ($this->isXHR() && $this->isLoged())
		{
			$pjDiscountPackageModel = pjDiscountPackageModel::factory();
			if (!in_array($_POST['column'], $pjDiscountPackageModel->getI18n()))
			{
				$record = $pjDiscountPackageModel->find($_GET['id'])->getData();
				if (in_array($_POST['column'], array('date_from', 'date_to')))
				{
					$_POST['value'] = pjUtil::formatDate($_POST['value'], $this->option_arr['o_date_format']);
					
					if ($_POST['column'] == 'date_from')
					{
						$date_from = $_POST['value'];
						$date_to = $record['date_to'];
					} elseif ($_POST['column'] == 'date_to') {
						$date_from = $record['date_from'];
						$date_to = $_POST['value'];
					}

					if (strtotime($date_from) > strtotime($date_to))
					{
						pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 101, 'text' => 'Invalid date range'));
					}
				}
				if (in_array($_POST['column'], array('start_day', 'end_day')))
				{
					if (!is_numeric($_POST['value']) || (int) $_POST['value'] < 0 || (int) $_POST['value'] > 6)
					{
						pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => 'Value out of range'));
					}
				}
				$pjDiscountPackageModel->set('id', $_GET['id'])->modify(array($_POST['column'] => $_POST['value']));
			} else {
				pjMultiLangModel::factory()->updateMultiLang(array($this->getLocaleId() => array($_POST['column'] => $_POST['value'])), $_GET['id'], 'pjDiscountPackage');
			}
			pjAppController::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => ''));
		}
		exit;
	}
	
	public function pjActionSavePackageItem()
	{
		$this->setAjax(true);
	
		if ($this->isXHR() && $this->isLoged())
		{
			$pjDiscountPackageItemModel = pjDiscountPackageItemModel::factory();
			if (!in_array($_POST['column'], $pjDiscountPackageItemModel->getI18n()))
			{
				$record = $pjDiscountPackageItemModel->find($_GET['id'])->getData();
				if (in_array($_POST['column'], array('adults')))
				{
					if ((int) $_POST['value'] <= 0)
					{
						pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 101, 'text' => 'Invalid number of adults'));
					}
				}
				$pjDiscountPackageItemModel->set('id', $_GET['id'])->modify(array($_POST['column'] => $_POST['value']));
			} else {
				pjMultiLangModel::factory()->updateMultiLang(array($this->getLocaleId() => array($_POST['column'] => $_POST['value'])), $_GET['id'], 'pjDiscountPackageItem');
			}
			pjAppController::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => ''));
		}
		exit;
	}
}
?>