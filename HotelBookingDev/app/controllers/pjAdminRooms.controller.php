<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjAdminRooms extends pjAdmin
{
	public function pjActionCreate()
	{
		$this->checkLogin();
		
		if ($this->isAdmin())
		{
			if (isset($_POST['room_create']))
			{
				$data = array();
				$data['calendar_id'] = $this->getForeignId();
				$room_id = pjRoomModel::factory(array_merge($_POST, $data))->insert()->getInsertId();
				if ($room_id !== false && (int) $room_id > 0)
				{
					if (isset($_POST['i18n']))
					{
						pjMultiLangModel::factory()->saveMultiLang($_POST['i18n'], $room_id, 'pjRoom');
					}
					if (isset($_POST['number']) && !empty($_POST['number']))
					{
						$pjRoomNumberModel = pjRoomNumberModel::factory();
						$pjRoomNumberModel->setBatchFields(array('room_id', 'number'));
						foreach ($_POST['number'] as $number)
						{
							$pjRoomNumberModel->addBatchRow(array($room_id, $number));
						}
						$pjRoomNumberModel->insertBatch();
					}
					pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminRooms&action=pjActionUpdate&id=$room_id&&err=AR03");
				} else {
					pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminRooms&action=pjActionIndex&err=AR04");
				}
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
				$this->set('lp_arr', $locale_arr);
				$this->set('locale_str', pjAppController::jsonEncode($lp_arr));
				
				$this->appendJs('jquery.validate.min.js', PJ_THIRD_PARTY_PATH . 'validate/');
				$this->appendJs('jquery.multilang.js', PJ_FRAMEWORK_LIBS_PATH . 'pj/js/');
				$this->appendJs('jquery.tipsy.js', PJ_THIRD_PARTY_PATH . 'tipsy/');
				$this->appendCss('jquery.tipsy.css', PJ_THIRD_PARTY_PATH . 'tipsy/');
				$this->appendJs('pjAdminRooms.js');
			}
		} else {
			$this->set('status', 2);
		}
	}
	
	public function pjActionDeleteRoom()
	{
		$this->setAjax(true);
	
		if ($this->isXHR() && $this->isLoged())
		{
			if (pjRoomModel::factory()->set('id', $_GET['id'])->erase()->getAffectedRows() == 1)
			{
				pjMultiLangModel::factory()->where('model', 'pjRoom')->where('foreign_id', $_GET['id'])->eraseAll();
				$pjGalleryModel = new pjGalleryModel();
				$gallery_arr = $pjGalleryModel->where('foreign_id', $_GET['id'])->findAll()->getData();
				if (!empty($gallery_arr))
				{
					$pjGalleryModel->eraseAll();
					foreach ($gallery_arr as $item)
					{
						@clearstatcache();
						if (!empty($item['small_path']) && is_file($item['small_path']))
						{
							@unlink($item['small_path']);
						}
						@clearstatcache();
						if (!empty($item['medium_path']) && is_file($item['medium_path']))
						{
							@unlink($item['medium_path']);
						}
						@clearstatcache();
						if (!empty($item['large_path']) && is_file($item['large_path']))
						{
							@unlink($item['large_path']);
						}
						@clearstatcache();
						if (!empty($item['source_path']) && is_file($item['source_path']))
						{
							@unlink($item['source_path']);
						}
					}
				}
				$response = array('status' => 'OK', 'code' => 200, 'text' => '');
			} else {
				$response = array('status' => 'ERR', 'code' => 100, 'text' => '');
			}
			pjAppController::jsonResponse($response);
		}
		exit;
	}
	
	public function pjActionDeleteRoomBulk()
	{
		$this->setAjax(true);
	
		if ($this->isXHR() && $this->isLoged())
		{
			if (isset($_POST['record']) && count($_POST['record']) > 0)
			{
				pjRoomModel::factory()->whereIn('id', $_POST['record'])->eraseAll();
				pjMultiLangModel::factory()->where('model', 'pjRoom')->whereIn('foreign_id', $_POST['record'])->eraseAll();
				$pjGalleryModel = new pjGalleryModel();
				$gallery_arr = $pjGalleryModel->whereIn('foreign_id', $_POST['record'])->findAll()->getData();
				if (!empty($gallery_arr))
				{
					$pjGalleryModel->eraseAll();
					foreach ($gallery_arr as $item)
					{
						@clearstatcache();
						if (!empty($item['small_path']) && is_file($item['small_path']))
						{
							@unlink($item['small_path']);
						}
						@clearstatcache();
						if (!empty($item['medium_path']) && is_file($item['medium_path']))
						{
							@unlink($item['medium_path']);
						}
						@clearstatcache();
						if (!empty($item['large_path']) && is_file($item['large_path']))
						{
							@unlink($item['large_path']);
						}
						@clearstatcache();
						if (!empty($item['source_path']) && is_file($item['source_path']))
						{
							@unlink($item['source_path']);
						}
					}
				}
				pjAppController::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => ''));
			}
			pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => ''));
		}
		exit;
	}
	
	public function pjActionGetRoom()
	{
		$this->setAjax(true);
	
		if ($this->isXHR() && $this->isLoged())
		{
			$pjRoomModel = pjRoomModel::factory()
				->join('pjMultiLang', "t2.model='pjRoom' AND t2.foreign_id=t1.id AND t2.field='name' AND t2.locale='".$this->getLocaleId()."'", 'left outer')
				->join('pjMultiLang', "t3.model='pjRoom' AND t3.foreign_id=t1.id AND t3.field='name' AND t3.locale='".$this->getLocaleId()."'", 'left outer');
				
			if (isset($_GET['q']) && !empty($_GET['q']))
			{
				$q = str_replace(array('_', '%'), array('\_', '\%'), $pjRoomModel->escapeStr($_GET['q']));
				$pjRoomModel->where('t2.content LIKE', "%$q%");
				$pjRoomModel->orWhere('t3.content LIKE', "%$q%");
			}
				
			$column = 'name';
			$direction = 'ASC';
			if (isset($_GET['direction']) && isset($_GET['column']) && in_array(strtoupper($_GET['direction']), array('ASC', 'DESC')))
			{
				$column = $_GET['column'];
				$direction = strtoupper($_GET['direction']);
			}

			$total = $pjRoomModel->findCount()->getData();
			$rowCount = isset($_GET['rowCount']) && (int) $_GET['rowCount'] > 0 ? (int) $_GET['rowCount'] : 10;
			$pages = ceil($total / $rowCount);
			$page = isset($_GET['page']) && (int) $_GET['page'] > 0 ? intval($_GET['page']) : 1;
			$offset = ((int) $page - 1) * $rowCount;
			if ($page > $pages)
			{
				$page = $pages;
			}

			$data = $pjRoomModel
				->select(sprintf("t1.id, t1.adults, t1.children, t1.cnt, t2.content AS name,
					(SELECT `small_path` FROM `%s` WHERE `foreign_id` = t1.id ORDER BY `sort` ASC LIMIT 1) AS `image`
				", pjGalleryModel::factory()->getTable()))
				->orderBy("$column $direction")
				->limit($rowCount, $offset)
				->findAll()
				->getData();
				
			pjAppController::jsonResponse(compact('data', 'total', 'pages', 'page', 'rowCount', 'column', 'direction'));
		}
		exit;
	}
	
	public function pjActionIndex()
	{
		$this->checkLogin();
		
		if ($this->isAdmin())
		{
			$this->set('is_empty', 0 == pjRoomModel::factory()->findCount()->getData());
			
			$this->appendJs('jquery.datagrid.js', PJ_FRAMEWORK_LIBS_PATH . 'pj/js/');
			$this->appendJs('pjAdminRooms.js');
			$this->appendJs('index.php?controller=pjAdmin&action=pjActionMessages', PJ_INSTALL_URL, true);
		} else {
			$this->set('status', 2);
		}
	}
	
	public function pjActionPrices()
	{
		$this->checkLogin();
		
		if ($this->isAdmin())
		{
			$this->set('room_arr', pjRoomModel::factory()
				->select('t1.*, t2.content AS name')
				->join('pjMultiLang', "t2.model='pjRoom' AND t2.foreign_id=t1.id AND t2.field='name' AND t2.locale='".$this->getLocaleId()."'", 'left outer')
				->orderBy('`name` ASC')
				->findAll()
				->getData()
			);
		} else {
			$this->set('status', 2);
		}
	}
	
	public function pjActionSaveRoom()
	{
		$this->setAjax(true);
	
		if ($this->isXHR() && $this->isLoged())
		{
			$pjRoomModel = pjRoomModel::factory();
			if (!in_array($_POST['column'], $pjRoomModel->getI18n()))
			{
				$pjRoomModel->where('id', $_GET['id'])->limit(1)->modifyAll(array($_POST['column'] => $_POST['value']));
			} else {
				pjMultiLangModel::factory()->updateMultiLang(array($this->getLocaleId() => array($_POST['column'] => $_POST['value'])), $_GET['id'], 'pjRoom');
			}
			pjAppController::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => ''));
		}
		exit;
	}
	
	public function pjActionUpdate()
	{
		$this->checkLogin();
		
		if ($this->isAdmin())
		{
			if (isset($_POST['room_update']))
			{
				pjRoomModel::factory()->where('id', $_POST['id'])->limit(1)->modifyAll($_POST);
				if (isset($_POST['i18n']))
				{
					pjMultiLangModel::factory()->updateMultiLang($_POST['i18n'], $_POST['id'], 'pjRoom');
				}
				if (isset($_POST['number']) && !empty($_POST['number']))
				{
					$pjRoomNumberModel = pjRoomNumberModel::factory();
					$existing_id_arr = $pjRoomNumberModel->where('t1.room_id', $_POST['id'])->orderBy('t1.id ASC')->findAll()->getDataPair(null, 'id');
					$number_id_arr = array();
					foreach ($_POST['number'] as $key => $val)
					{
						$rn = array();
						$rn['room_id'] = $_POST['id'];
						$rn['number'] = $val;
						if (strpos($key, 'new') !== false)
						{
							$pjRoomNumberModel->reset()->setAttributes($rn)->insert();
						} else {
							$number_id_arr[] = $key;
							$pjRoomNumberModel->reset()->set('id', $key)->modify($rn);
						}
					}
					$remove_id_arr = array_diff($existing_id_arr, $number_id_arr);
					if (!empty($remove_id_arr))
					{
						$pjRoomNumberModel->reset()->whereIn('id', $remove_id_arr)->eraseAll();
					}
				}
				pjUtil::redirect(PJ_INSTALL_URL . "index.php?controller=pjAdminRooms&action=pjActionIndex&err=AR01");
				
			} else {
				$arr = pjRoomModel::factory()->find($_GET['id'])->getData();
				if (count($arr) === 0)
				{
					pjUtil::redirect(PJ_INSTALL_URL. "index.php?controller=pjAdminRooms&action=pjActionIndex&err=AR08");
				}
				$arr['i18n'] = pjMultiLangModel::factory()->getMultiLang($arr['id'], 'pjRoom');
				$this->set('arr', $arr);
				
				$locale_arr = pjLocaleModel::factory()->select('t1.*, t2.file')
					->join('pjLocaleLanguage', 't2.iso=t1.language_iso', 'left outer')
					->where('t2.file IS NOT NULL')
					->orderBy('t1.sort ASC')->findAll()->getData();
				
				$lp_arr = array();
				foreach ($locale_arr as $item)
				{
					$lp_arr[$item['id']."_"] = $item['file']; //Hack for jquery $.extend, to prevent (re)order of numeric keys in object
				}
				$this->set('lp_arr', $locale_arr);
				$this->set('locale_str', pjAppController::jsonEncode($lp_arr));
				
				$this->set('number_arr', pjRoomNumberModel::factory()->where('t1.room_id', $arr['id'])->findAll()->getData());
				
				# Gallery plugin
				$this->appendCss('pj-gallery.css', pjObject::getConstant('pjGallery', 'PLUGIN_CSS_PATH'));
				$this->appendJsFromPlugin('ajaxupload.js', 'ajaxupload', 'pjGallery');
				$this->appendJs('jquery.gallery.js', pjObject::getConstant('pjGallery', 'PLUGIN_JS_PATH'));
				
				$this->appendJs('jquery.validate.min.js', PJ_THIRD_PARTY_PATH . 'validate/');
				$this->appendJs('jquery.multilang.js', PJ_FRAMEWORK_LIBS_PATH . 'pj/js/');
				$this->appendJs('jquery.tipsy.js', PJ_THIRD_PARTY_PATH . 'tipsy/');
				$this->appendCss('jquery.tipsy.css', PJ_THIRD_PARTY_PATH . 'tipsy/');
				$this->appendJs('pjAdminRooms.js');
				$this->appendJs('index.php?controller=pjAdmin&action=pjActionMessages', PJ_INSTALL_URL, true);
			}
		} else {
			$this->set('status', 2);
		}
	}
}
?>