<?php if ((int) $tpl['option_arr']['o_multi_lang'] === 1 && count($tpl['lp_arr']) > 1) : ?>
<div class="multilang b10"></div>
<?php endif; ?>

<form action="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminOptions&amp;action=pjActionUpdate" method="post" class="form pj-form clear_both">
	<input type="hidden" name="options_update" value="1" />
	<input type="hidden" name="next_action" value="pjActionIndex" />
	<input type="hidden" name="tab" value="<?php echo @$_GET['tab']; ?>" />
	
	<div id="tabs">
		<ul>
			<li><a href="#tabs-1"><?php __('tabEmails'); ?></a></li>
			<li><a href="#tabs-2"><?php __('tabSms'); ?></a></li>
		</ul>
		<div id="tabs-1">
			<style type="text/css">
			.mce-tinymce.mce-container.mce-panel{
				float: left;
				margin-right: 3px;
			}
			</style>
			<p>
				<label class="title" style="width: 180px">Email message:</label>
				<select name="email_type" class="pj-form-field">
				<option value="">-- Choose email --</option>
				<?php 
				$items = array(
					'confirm_subject_client' => __('confirmation_client_confirmation', true),
					'confirm_subject_admin' => __('confirmation_admin_confirmation', true),
					'payment_subject_client' => __('confirmation_client_payment', true),
					'payment_subject_admin' => __('confirmation_admin_payment', true),
					'cancel_subject_admin' => __('confirmation_admin_cancel', true)
				);
				foreach ($items as $key => $val)
				{
					?><option value="<?php echo $key; ?>"><?php echo pjSanitize::html($val); ?></option><?php
				}
				?>
				</select>
			</p>
			<div id="boxEmails"></div>
		</div>
		<div id="tabs-2">
			<fieldset class="fieldset white">
				<legend><?php __('confirm_sms_admin'); ?></legend>
				<?php
				foreach ($tpl['lp_arr'] as $v)
				{
					?>
					<div class="pj-multilang-wrap" data-index="<?php echo $v['id']; ?>" style="display: <?php echo (int) $v['is_default'] === 0 ? 'none' : NULL; ?>">
						<p class="block t5">
							<label class="title" style="width: 180px"><?php __('sms_body'); ?></label>
							<textarea name="i18n[<?php echo $v['id']; ?>][confirm_sms_admin]" class="pj-form-field w500 h100"><?php echo stripslashes(str_replace(array('\r\n', '\n'), '&#10;', @$tpl['arr']['i18n'][$v['id']]['confirm_sms_admin'])); ?></textarea>
							<?php if ((int) $tpl['option_arr']['o_multi_lang'] === 1 && count($tpl['lp_arr']) > 1) : ?>
							<span class="pj-multilang-input"><img src="<?php echo PJ_INSTALL_URL . PJ_FRAMEWORK_LIBS_PATH . 'pj/img/flags/' . $v['file']; ?>" alt="" /></span>
							<?php endif; ?>
						</p>
					</div>
					<?php
				}
				?>
			</fieldset>
			<fieldset class="fieldset white">
				<legend><?php __('payment_sms_admin'); ?></legend>
				<?php
				foreach ($tpl['lp_arr'] as $v)
				{
					?>
					<div class="pj-multilang-wrap" data-index="<?php echo $v['id']; ?>" style="display: <?php echo (int) $v['is_default'] === 0 ? 'none' : NULL; ?>">
						<p class="block t5">
							<label class="title" style="width: 180px"><?php __('sms_body'); ?></label>
							<textarea name="i18n[<?php echo $v['id']; ?>][payment_sms_admin]" class="pj-form-field w500 h100"><?php echo stripslashes(str_replace(array('\r\n', '\n'), '&#10;', @$tpl['arr']['i18n'][$v['id']]['payment_sms_admin'])); ?></textarea>
							<?php if ((int) $tpl['option_arr']['o_multi_lang'] === 1 && count($tpl['lp_arr']) > 1) : ?>
							<span class="pj-multilang-input"><img src="<?php echo PJ_INSTALL_URL . PJ_FRAMEWORK_LIBS_PATH . 'pj/img/flags/' . $v['file']; ?>" alt="" /></span>
							<?php endif; ?>
						</p>
					</div>
					<?php
				}
				?>
			</fieldset>
			<p>
				<input type="submit" class="pj-button" value="<?php __('btnSave', false, true); ?>" />
			</p>
		</div>
	</div>
</form>

<?php $locale = isset($_GET['locale']) && (int) $_GET['locale'] > 0 ? (int) $_GET['locale'] : @$tpl['lp_arr'][0]['id']; ?>
<script type="text/javascript">
<?php if ((int) $tpl['option_arr']['o_multi_lang'] === 1) : ?>
(function ($) {
	$(function() {
		$(".multilang").multilang({
			langs: <?php echo $tpl['locale_str']; ?>,
			flagPath: "<?php echo PJ_FRAMEWORK_LIBS_PATH; ?>pj/img/flags/",
			select: function (event, ui) {
				//callback
			}
		});
		$(".multilang").find("a[data-index='<?php echo $locale; ?>']").trigger("click");
	});
})(jQuery_1_8_2);
<?php endif; ?>
</script>