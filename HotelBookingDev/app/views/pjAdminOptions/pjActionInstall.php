<?php
if (isset($tpl['status']))
{
	$status = __('status', true);
	switch ($tpl['status'])
	{
		case 2:
			pjUtil::printNotice(NULL, $status[2]);
			break;
	}
} else {
	$titles = __('error_titles', true);
	$bodies = __('error_bodies', true);
	if (isset($_GET['err']))
	{
		pjUtil::printNotice(@$titles[$_GET['err']], @$bodies[$_GET['err']], true, true, true);
	}
	
	pjUtil::printNotice(__('lblInstallJs1_title', true), __('lblInstallJs1_body', true), false, false);
	?>
	
	<div class="float_left w700">
				
		<div class="install-method pj-form form">
			<div class="install-heading"><?php __('install_method_1'); ?></div>
			<div class="install-hint"><?php __('install_method_1_hint'); ?></div>
			<?php
			if(count($tpl['locale_arr']) > 1)
			{ 
				?>
				<p>
					<label class="title"><?php __('install_language'); ?></label>
					<select class="pj-form-field w200" name="install_locale">
						<option value="">-- <?php __('install_all_languages'); ?> --</option>
						<?php
						foreach ($tpl['locale_arr'] as $locale)
						{
							?><option value="<?php echo $locale['id']; ?>"><?php echo pjSanitize::html($locale['title']); ?></option><?php
						}
						?>
					</select>
				</p>
				<?php
			}else{
				?><input type="hidden" name="install_locale" value="<?php echo $controller->getLocaleId();?>"/><?php
			} 
			?>
			<p>
				<label class="title"><?php __('install_hide_language'); ?></label>
				<span class="left">
					<input type="checkbox" name="install_hide" value="1" />
				</span>
			</p>
			<div class="install-code">
				<textarea class="pj-form-field textarea_install" id="install_code" style="overflow: auto; height:120px">
&lt;link href="<?php echo PJ_INSTALL_URL.PJ_FRAMEWORK_LIBS_PATH . 'pj/css/'; ?>pj.bootstrap.min.css" type="text/css" rel="stylesheet" /&gt;
&lt;link href="<?php echo PJ_INSTALL_URL; ?>index.php?controller=pjFront&action=pjActionLoadCss&cid=<?php echo $controller->getForeignId(); ?>&theme=<?php echo $tpl['option_arr']['o_theme']; ?>" type="text/css" rel="stylesheet" /&gt;
&lt;script type="text/javascript" src="<?php echo PJ_INSTALL_URL; ?>index.php?controller=pjFront&action=pjActionLoad&cid=<?php echo $controller->getForeignId(); ?>&theme=<?php echo $tpl['option_arr']['o_theme']; ?>"&gt;&lt;/script&gt;</textarea>
			</div>
		</div>
	
		<div class="install-method">
			<div class="install-heading"><?php __('install_method_2'); ?></div>
			<div class="install-hint"><?php __('install_method_2_hint'); ?></div>
			<div class="install-code">
		 		<a target="_blank" href="<?php echo PJ_INSTALL_URL; ?>book.html"><?php echo PJ_INSTALL_URL; ?>book.html</a>
			</div>
		</div>
	
		<div class="install-method">
			<div class="install-heading"><?php __('install_method_3'); ?></div>
			<div class="install-hint"><?php __('install_method_3_hint'); ?></div>
			<div class="install-code">
				<textarea class="pj-form-field textarea_install" style="overflow: auto; height:65px">&lt;a href="<?php echo PJ_INSTALL_URL; ?>book.html"&gt;<?php __('install_link'); ?>&lt/a&gt;</textarea>
			</div>
		</div>
	</div>
	
	<div class="float_right w250">
		
		<div class="install-segment">
			<div class="install-title"><?php __('install_review'); ?></div>
			<div class="install-text"><?php __('install_review_hint'); ?></div>
			<div class="install-example">
				<a href="#" class="btn-example"><img src="<?php echo PJ_INSTALL_URL.PJ_IMG_PATH; ?>backend/install-example.png" alt="" /></a>
			</div>
		</div>
	</div>
	<br class="clear_both"/>

	<div style="display:none" id="hidden_code">&lt;link href="<?php echo PJ_INSTALL_URL.PJ_FRAMEWORK_LIBS_PATH . 'pj/css/'; ?>pj.bootstrap.min.css" type="text/css" rel="stylesheet" /&gt;
&lt;link href="<?php echo PJ_INSTALL_URL; ?>index.php?controller=pjFront&action=pjActionLoadCss&cid=<?php echo $controller->getForeignId(); ?>&theme=<?php echo $tpl['option_arr']['o_theme']; ?>" type="text/css" rel="stylesheet" /&gt;
&lt;script type="text/javascript" src="<?php echo PJ_INSTALL_URL; ?>index.php?controller=pjFront&action=pjActionLoad&cid=<?php echo $controller->getForeignId(); ?>&theme=<?php echo $tpl['option_arr']['o_theme']; ?>{LOCALE}{HIDE}"&gt;&lt;/script&gt;</div>

	<div id="dialogExample" title="<?php __('install_example_title', false, true); ?>" style="display:none"></div>
	<?php
}
?>