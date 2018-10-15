<?php if ((!isset($_GET['hide']) || (int) $_GET['hide'] === 0) && isset($tpl['locale_arr']) && is_array($tpl['locale_arr']) && count($tpl['locale_arr']) > 1) : ?>
<div class="btn-group pjHbLanguage">
	<button type="button" class="btn btn-default dropdown-toggle pjHbBtnNav" data-toggle="dropdown" aria-expanded="false">
		<?php
		$selected_title = null;
		$selected_src = NULL;
		foreach ($tpl['locale_arr'] as $locale)
		{
			if ($controller->getLocaleId() == $locale['id'])
			{
				$selected_title = $locale['language_iso'];
				$lang_iso = explode("-", $selected_title);
				if(isset($lang_iso[1]))
				{
					$selected_title = $lang_iso[1];
				}
				if (!empty($locale['flag']) && is_file(PJ_INSTALL_PATH . $locale['flag']))
				{
					$selected_src = PJ_INSTALL_URL . $locale['flag'];
				} elseif (!empty($locale['file']) && is_file(PJ_FRAMEWORK_LIBS_PATH . 'pj/img/flags/' . $locale['file'])) {
					$selected_src = PJ_INSTALL_URL . PJ_FRAMEWORK_LIBS_PATH . 'pj/img/flags/' . $locale['file'];
				}
				break;
			}
		}
		?> 
		<img src="<?php echo $selected_src; ?>" alt="">
		<span class="title"><?php echo mb_strtoupper($selected_title, 'UTF-8'); ?></span>
		<span class="caret"></span>
	</button>
	<ul class="dropdown-menu" role="menu">
	<?php
	foreach ($tpl['locale_arr'] as $locale)
	{
		$selected_src = NULL;
		if (!empty($locale['flag']) && is_file(PJ_INSTALL_PATH . $locale['flag']))
		{
			$selected_src = PJ_INSTALL_URL . $locale['flag'];
		} elseif (!empty($locale['file']) && is_file(PJ_FRAMEWORK_LIBS_PATH . 'pj/img/flags/' . $locale['file'])) {
			$selected_src = PJ_INSTALL_URL . PJ_FRAMEWORK_LIBS_PATH . 'pj/img/flags/' . $locale['file'];
		}
		?>
		<li>
			<a href="#" class="hbSelectorLocale<?php echo $controller->getLocaleId() == $locale['id'] ? ' pjHbBtnActive' : NULL; ?>" data-id="<?php echo $locale['id']; ?>" data-dir="<?php echo $locale['dir']; ?>">
				<img src="<?php echo $selected_src; ?>" alt="">
				<?php echo pjSanitize::html($locale['name']); ?>
			</a>
		</li>
		<?php
	}
	?>
	</ul>
</div>
<?php endif; ?>