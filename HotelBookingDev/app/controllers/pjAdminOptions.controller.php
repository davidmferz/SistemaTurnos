<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjAdminOptions extends pjAdmin
{
	public function pjActionIndex()
	{
		$this->checkLogin();

		if ($this->isAdmin())
		{
			if (isset($_GET['tab']) && in_array((int) $_GET['tab'], array(5,6)))
			{
				$locale_arr = pjLocaleModel::factory()->select('t1.*, t2.file')
					->join('pjLocaleLanguage', 't2.iso=t1.language_iso', 'left outer')
					->where('t2.file IS NOT NULL')
					->orderBy('t1.sort ASC')->findAll()->getData();
						
				$lp_arr = array();
				foreach ($locale_arr as $v)
				{
					$lp_arr[$v['id']."_"] = $v['file']; //Hack for jquery $.extend, to prevent (re)order of numeric keys in object
				}
				$this->set('lp_arr', $locale_arr);
				$this->set('locale_str', pjAppController::jsonEncode($lp_arr));
				
				$arr = array();
				$arr['i18n'] = pjMultiLangModel::factory()->getMultiLang($this->getForeignId(), 'pjCalendar');
				$this->set('arr', $arr);
				
				$this->appendJs('jquery.multilang.js', PJ_FRAMEWORK_LIBS_PATH . 'pj/js/');
				$this->appendJs('tinymce.min.js', PJ_THIRD_PARTY_PATH . 'tinymce/');
			} else {
				$tab_id = isset($_GET['tab']) && (int) $_GET['tab'] > 0 ? (int) $_GET['tab'] : 1;
				$arr = pjOptionModel::factory()
					->where('foreign_id', $this->getForeignId())
					->where('tab_id', $tab_id)
					->orderBy('t1.order ASC')
					->findAll()
					->getData();
				$this->set('arr', $arr);
				
				$tmp = $this->models['Option']->reset()->where('foreign_id', $this->getForeignId())->findAll()->getData();
				$o_arr = array();
				foreach ($tmp as $item)
				{
					$o_arr[$item['key']] = $item;
				}
				$this->set('o_arr', $o_arr);
			}
			$this->appendJs('jquery.tipsy.js', PJ_THIRD_PARTY_PATH . 'tipsy/');
			$this->appendCss('jquery.tipsy.css', PJ_THIRD_PARTY_PATH . 'tipsy/');
			$this->appendJs('pjAdminOptions.js');
		} else {
			$this->set('status', 2);
		}
	}
	
	public function pjActionInstall()
	{
		$this->checkLogin();
		
		if ($this->isAdmin())
		{
			$locale_arr = pjLocaleModel::factory()->select('t1.*, t2.title')
				->join('pjLocaleLanguage', 't2.iso=t1.language_iso', 'left outer')
				->orderBy('t1.sort ASC')->findAll()->getData();
			$this->set('locale_arr', $locale_arr);
			
			$this->appendCss('codemirror.css', PJ_THIRD_PARTY_PATH . 'codemirror/lib/');
			$this->appendJs('codemirror.js', PJ_THIRD_PARTY_PATH . 'codemirror/lib/');
			
			$this->appendJs('pjAdminOptions.js');
		} else {
			$this->set('status', 2);
		}
	}
	
	public function pjActionPreview()
	{
		if (isset($_GET['use']) && (int) $_GET['use'] > 0)
		{
			$pjOptionModel = pjOptionModel::factory()
				->where('foreign_id', $this->getForeignId())
				->where('`key`', 'o_theme');
			
			$option_arr = $pjOptionModel
				->limit(1)
				->findAll()
				->getDataIndex(0);
			
			if ($option_arr !== FALSE && !empty($option_arr))
			{
				$pattern = '/^(\d+(\|\d+)*::)\d+$/';
				
				preg_match($pattern, $option_arr['value'], $m);
					
				$value = preg_replace($pattern, "\${1}" . (int) $_GET['use'], $option_arr['value']);
				
				$pjOptionModel
					->limit(1)
					->modifyAll(array(
						'value' => $value
					));
			}
			
			pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminOptions&action=pjActionPreview");
		}
		
		if (isset($_GET['theme']))
		{
			$this->setAjax(true);
			$this->setLayout('pjActionEmpty');
		} else {
			$this->checkLogin();
			
			if ($this->isAdmin())
			{
				$this->appendJs('pjAdminOptions.js');
			} else {
				$this->set('status', 2);
			}
		}
	}
	
	public function pjActionUpdate()
	{
		$this->checkLogin();

		if ($this->isAdmin())
		{
			if (isset($_POST['options_update']))
			{
				if (isset($_POST['tab']) && in_array($_POST['tab'], array(5, 6)))
				{
					if (isset($_POST['i18n']))
					{
						pjMultiLangModel::factory()->updateMultiLang($_POST['i18n'], $this->getForeignId(), 'pjCalendar');
					}
				} else {
					$OptionModel = new pjOptionModel();
					$OptionModel
						->where('foreign_id', $this->getForeignId())
						->where('type', 'bool')
						->where('tab_id', $_POST['tab'])
						->modifyAll(array('value' => '1|0::0'));
						
					foreach ($_POST as $key => $value)
					{
						if (preg_match('/value-(string|text|int|float|enum|bool|color)-(.*)/', $key) === 1)
						{
							list(, $type, $k) = explode("-", $key);
							if (!empty($k))
							{
								$OptionModel
									->reset()
									->where('foreign_id', $this->getForeignId())
									->where('`key`', $k)
									->limit(1)
									->modifyAll(array('value' => $value));
							}
						}
					}
				}
				if (isset($_POST['tab']))
				{
					switch ($_POST['tab'])
					{
						case '1':
							$err = 'AO01';
							break;
						case '2':
							$err = 'AO02';
							break;
						case '3':
							$err = 'AO03';
							break;
						case '4':
							$err = 'AO04';
							break;
						case '5':
							$err = 'AO05&email_type='.@$_POST['email_type'];
							break;
						case '6':
							$err = 'AO06';
							break;
						case '7':
							$err = 'AO07';
							break;
					}
				}
				pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminOptions&action=" . @$_POST['next_action'] . "&tab=" . @$_POST['tab'] . "&err=$err");
			}
		} else {
			$this->set('status', 2);
		}
	}
	
	public function pjActionUpdateEmail()
	{
		$this->setAjax(true);
		
		if ($this->isXHR() && $this->isLoged())
		{
			$locale_arr = pjLocaleModel::factory()->select('t1.*, t2.file')
				->join('pjLocaleLanguage', 't2.iso=t1.language_iso', 'left outer')
				->where('t2.file IS NOT NULL')
				->orderBy('t1.sort ASC')->findAll()->getData();
			
			$lp_arr = array();
			foreach ($locale_arr as $v)
			{
				$lp_arr[$v['id']."_"] = $v['file']; //Hack for jquery $.extend, to prevent (re)order of numeric keys in object
			}
			$this->set('lp_arr', $locale_arr);
			$this->set('locale_str', pjAppController::jsonEncode($lp_arr));
			
			$arr = array();
			$arr['i18n'] = pjMultiLangModel::factory()->getMultiLang($this->getForeignId(), 'pjCalendar');
			$this
				->set('arr', $arr)
				->set('email_subject', $_GET['type'])
				->set('email_body', preg_replace('/^(\w+)_subject_(\w+)$/', "\${1}_tokens_\${2}", $_GET['type']));
		}
	}
	
	public function pjActionGetExample()
	{
		$this->setAjax(true);
		
		if ($this->isXHR() && $this->isLoged())
		{
			$content = @file_get_contents(PJ_VIEWS_PATH . 'pjAdminOptions/elements/example-1.php');
			if ($content !== FALSE)
			{
				$this->set('example_1', str_replace(array('{PJ_INSTALL_URL}', '{PJ_FRAMEWORK_LIBS_PATH}'), array(PJ_INSTALL_URL, PJ_FRAMEWORK_LIBS_PATH), $content));
			}
			
			/*$content = @file_get_contents(PJ_VIEWS_PATH . 'pjAdminOptions/elements/example-2.php');
			if ($content !== FALSE)
			{
				$this->set('example_2', str_replace('{PJ_INSTALL_URL}', PJ_INSTALL_URL, $content));
			}*/
			
			$content = @file_get_contents(PJ_VIEWS_PATH . 'pjAdminOptions/elements/example-3.php');
			if ($content !== FALSE)
			{
				$this->set('example_3', str_replace(
					array('{PJ_INSTALL_URL}', '{A_TEXT}', '{PJ_FRAMEWORK_LIBS_PATH}'), 
					array(PJ_INSTALL_URL, __('install_link', true), PJ_FRAMEWORK_LIBS_PATH), 
					$content
				));
			}
		}
	}
}
?>