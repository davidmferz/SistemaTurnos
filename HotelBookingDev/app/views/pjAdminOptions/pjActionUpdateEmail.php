<?php
if (!empty($_GET['type']))
{
	foreach ($tpl['lp_arr'] as $v)
	{
		?>
		<div class="pj-multilang-wrap" data-index="<?php echo $v['id']; ?>" style="display: <?php echo (int) $v['is_default'] === 0 ? 'none' : NULL; ?>">
			<p class="block t5">
				<label class="title" style="width: 180px"><?php __('confirmation_subject'); ?></label>
				<input type="text" name="i18n[<?php echo $v['id']; ?>][<?php echo $tpl['email_subject']; ?>]" class="pj-form-field w500 b10" value="<?php echo pjSanitize::html(@$tpl['arr']['i18n'][$v['id']][$tpl['email_subject']]); ?>" />
				<?php if ((int) $tpl['option_arr']['o_multi_lang'] === 1): ?>
				<span class="pj-multilang-input"><img src="<?php echo PJ_INSTALL_URL . PJ_FRAMEWORK_LIBS_PATH . 'pj/img/flags/' . $v['file']; ?>" alt="" /></span>
				<?php endif; ?>
			</p>
			<div class="block t5 overflow">
				<label class="title" style="width: 180px"><?php __('confirmation_body'); ?></label>
				<textarea name="i18n[<?php echo $v['id']; ?>][<?php echo $tpl['email_body']; ?>]" class="pj-form-field w500 h230 mceEditor"><?php echo stripslashes(@$tpl['arr']['i18n'][$v['id']][$tpl['email_body']]); ?></textarea>
				<?php if ((int) $tpl['option_arr']['o_multi_lang'] === 1): ?>
				<span class="pj-multilang-input"><img src="<?php echo PJ_INSTALL_URL . PJ_FRAMEWORK_LIBS_PATH . 'pj/img/flags/' . $v['file']; ?>" alt="" /></span>
				<?php endif; ?>
			</div>
		</div>
		<?php
	}
	?>
	<p></p>
	<p>
		<label class="title" style="width: 180px">&nbsp;</label>
		<input type="submit" class="pj-button" value="<?php __('btnSave', false, true); ?>" />
	</p>
	<?php 
}
?>