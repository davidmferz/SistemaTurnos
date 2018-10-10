<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjAdminLimits extends pjAdmin
{
	public function pjActionAddLimit()
	{
		$this->setAjax(true);
	
		if ($this->isXHR() && $this->isLoged())
		{
			if (isset($_POST['add_limit']))
			{
				$data = array();
				$data['date_from'] = pjUtil::formatDate($_POST['date_from'], $this->option_arr['o_date_format']);
				$data['date_to'] = pjUtil::formatDate($_POST['date_to'], $this->option_arr['o_date_format']);
				$limit_id = pjLimitModel::factory(array_merge($_POST, $data))->insert()->getInsertId();
				if ($limit_id !== false && (int) $limit_id > 0)
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
	
	public function pjActionUpdateLimit()
	{
		$this->setAjax(true);
	
		if ($this->isXHR() && $this->isLoged())
		{
			if (isset($_POST['update_limit']))
			{
				$data = array();
				$data['date_from'] = pjUtil::formatDate($_POST['date_from'], $this->option_arr['o_date_format']);
				$data['date_to'] = pjUtil::formatDate($_POST['date_to'], $this->option_arr['o_date_format']);
				$limit_id = pjLimitModel::factory(array_merge($_POST, $data))->insert()->getInsertId();
				
				pjLimitModel::factory()->set('id', $_POST['id'])->modify(array_merge($_POST, $data));
				
				pjAppController::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => ''));
			}
			$arr = pjLimitModel::factory()->find($_GET['id'])->getData();
			$this->set('arr', $arr);
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
	
	public function pjActionDeleteLimit()
	{
		$this->setAjax(true);
	
		if ($this->isXHR() && $this->isLoged())
		{
			if (pjLimitModel::factory()->set('id', $_GET['id'])->erase()->getAffectedRows() == 1)
			{
				$response = array('status' => 'OK', 'code' => 200, 'text' => '');
			} else {
				$response = array('status' => 'ERR', 'code' => 100, 'text' => '');
			}
			pjAppController::jsonResponse($response);
		}
		exit;
	}
	
	public function pjActionDeleteLimitBulk()
	{
		$this->setAjax(true);
	
		if ($this->isXHR() && $this->isLoged())
		{
			if (isset($_POST['record']) && !empty($_POST['record']))
			{
				if (pjLimitModel::factory()->whereIn('id', $_POST['record'])->eraseAll()->getAffectedRows() > 0)
				{
					pjAppController::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => ''));
				}
			}
			pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => ''));
		}
		exit;
	}
	
	public function pjActionIndex()
	{
		$this->checkLogin();
		
		if ($this->isAdmin())
		{
			$this->set('is_empty', 0 == pjLimitModel::factory()->findCount()->getData());
			
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
			$this->appendJs('additional-methods.min.js', PJ_THIRD_PARTY_PATH . 'validate/');
			$this->appendJs('pjAdminLimits.js');
			$this->appendJs('index.php?controller=pjAdmin&action=pjActionMessages', PJ_INSTALL_URL, true);
		} else {
			$this->set('status', 2);
		}
	}
	
	public function pjActionGetLimits()
	{
		$this->setAjax(true);
		
		if ($this->isXHR() && $this->isLoged())
		{
			$pjLimitModel = pjLimitModel::factory();
				
			$column = 'date_from';
			$direction = 'ASC';
			if (isset($_GET['direction']) && isset($_GET['column']) && in_array(strtoupper($_GET['direction']), array('ASC', 'DESC')))
			{
				$column = $_GET['column'];
				$direction = strtoupper($_GET['direction']);
			}

			$total = $pjLimitModel->findCount()->getData();
			$rowCount = isset($_GET['rowCount']) && (int) $_GET['rowCount'] > 0 ? (int) $_GET['rowCount'] : 10;
			$pages = ceil($total / $rowCount);
			$page = isset($_GET['page']) && (int) $_GET['page'] > 0 ? intval($_GET['page']) : 1;
			$offset = ((int) $page - 1) * $rowCount;
			if ($page > $pages)
			{
				$page = $pages;
			}

			$data = $pjLimitModel
				->orderBy("$column $direction")->limit($rowCount, $offset)->findAll()->getData();
				
			pjAppController::jsonResponse(compact('data', 'total', 'pages', 'page', 'rowCount', 'column', 'direction'));
		}
		exit;
	}
	
	
	public function pjActionSaveLimit()
	{
		$this->setAjax(true);
	
		if ($this->isXHR() && $this->isLoged())
		{
			$pjLimitModel = pjLimitModel::factory();
			if (!in_array($_POST['column'], $pjLimitModel->getI18n()))
			{
				$limit = $pjLimitModel->find($_GET['id'])->getData();
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
					
					/*$response = $this->pjActionCheckDt($date_from, $date_to, $limit['calendar_id'], $limit['id']);
					if ($response['status'] != 'OK')
					{
						pjAppController::jsonResponse($response);
					}*/
				}
				if (in_array($_POST['column'], array('min_nights', 'max_nights')))
				{
					if (!is_numeric($_POST['value']) || (int) $_POST['value'] < 0 || (int) $_POST['value'] > 255)
					{
						pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => 'Value out of range'));
					}
				}
				if (in_array($_POST['column'], array('start_on')))
				{
					if (!is_numeric($_POST['value']) || (int) $_POST['value'] < 0 || (int) $_POST['value'] > 7)
					{
						pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => 'Value out of range'));
					}
				}
				$pjLimitModel->set('id', $_GET['id'])->modify(array($_POST['column'] => $_POST['value']));
			} else {
				pjMultiLangModel::factory()->updateMultiLang(array($this->getLocaleId() => array($_POST['column'] => $_POST['value'])), $_GET['id'], 'pjLimit');
			}
			pjAppController::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => ''));
		}
		exit;
	}
}
?>