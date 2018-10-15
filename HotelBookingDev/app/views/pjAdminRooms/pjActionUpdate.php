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
	include PJ_VIEWS_PATH . 'pjAdminRooms/elements/menu.php';
	$titles = __('error_titles', true);
	$bodies = __('error_bodies', true);
	if (isset($_GET['err']))
	{
		pjUtil::printNotice(@$titles[$_GET['err']], @$bodies[$_GET['err']], true, true, true);
	}
	pjUtil::printNotice(@$titles['AR07'], sprintf(@$bodies['AR07'], 'index.php?controller=pjPrice&action=pjActionIndex&room_id='.$tpl['arr']['id']), false, true);
	?>
	<?php if ((int) $tpl['option_arr']['o_multi_lang'] === 1 && count($tpl['lp_arr']) > 1) : ?>
	<div class="multilang"></div>
	<?php endif;?>
	
	<form action="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminRooms&amp;action=pjActionUpdate" method="post" id="frmUpdateRoom" class="form pj-form">
		<input type="hidden" name="room_update" value="1" />
		<input type="hidden" name="id" value="<?php echo $tpl['arr']['id']; ?>" />
		<?php
		foreach ($tpl['lp_arr'] as $k => $v)
		{
		?>
			<p class="pj-multilang-wrap" data-index="<?php echo $v['id']; ?>" style="display: <?php echo (int) $v['is_default'] === 0 ? 'none' : NULL; ?>">
				<label class="title"><?php __('rooms_name'); ?>:</label>
				<span class="inline_block">
					<input type="text" name="i18n[<?php echo $v['id']; ?>][name]" class="pj-form-field w300<?php echo (int) $v['is_default'] === 0 ? NULL : ' required'; ?><?php echo $k != 0 ? NULL : ' field-name'; ?>" value="<?php echo pjSanitize::html(@$tpl['arr']['i18n'][$v['id']]['name']); ?>" data-msg-required="<?php __('lblFieldRequired');?>"/>
					<?php if ((int) $tpl['option_arr']['o_multi_lang'] === 1 && count($tpl['lp_arr']) > 1) : ?>
					<span class="pj-multilang-input"><img src="<?php echo PJ_INSTALL_URL . PJ_FRAMEWORK_LIBS_PATH . 'pj/img/flags/' . $v['file']; ?>" alt="" /></span>
					<?php endif;?>
				</span>
			</p>
			<?php
		}
		foreach ($tpl['lp_arr'] as $v)
		{
		?>
			<p class="pj-multilang-wrap" data-index="<?php echo $v['id']; ?>" style="display: <?php echo (int) $v['is_default'] === 0 ? 'none' : NULL; ?>">
				<label class="title"><?php __('rooms_description'); ?>:</label>
				<span class="inline_block">
					<textarea name="i18n[<?php echo $v['id']; ?>][description]" class="pj-form-field w500 h150"><?php echo pjSanitize::html(@$tpl['arr']['i18n'][$v['id']]['description']); ?></textarea>
					<?php if ((int) $tpl['option_arr']['o_multi_lang'] === 1 && count($tpl['lp_arr']) > 1) : ?>
					<span class="pj-multilang-input"><img src="<?php echo PJ_INSTALL_URL . PJ_FRAMEWORK_LIBS_PATH . 'pj/img/flags/' . $v['file']; ?>" alt="" /></span>
					<?php endif;?>
				</span>
			</p>
			<?php
		}
		?>
		<p>
			<label class="title"><?php __('rooms_max_occupancy'); ?></label>
			<span class="inline_block">
				<input type="text" name="max_people" id="max_people" class="pj-form-field w60" value="<?php echo (int) $tpl['arr']['max_people']; ?>" readonly="readonly" />
			</span>
		</p>
		<p>
			<label class="title"><?php __('rooms_adults'); ?></label>
			<span class="inline_block">
				<input type="text" name="adults" id="adults" class="pj-form-field w60" value="<?php echo (int) $tpl['arr']['adults']; ?>" readonly="readonly" />
			</span>
		</p>
		<p>
			<label class="title"><?php __('rooms_children'); ?></label>
			<span class="inline_block">
				<input type="text" name="children" id="children" class="pj-form-field w60" value="<?php echo (int) $tpl['arr']['children']; ?>" readonly="readonly" />
			</span>
		</p>
		<p>
			<label class="title"><?php __('rooms_cnt'); ?></label>
			<span class="inline_block">
				<input type="text" name="cnt" id="cnt" class="pj-form-field w60" value="<?php echo (int) $tpl['arr']['cnt']; ?>" readonly="readonly" />
			</span>
		</p>
		<p class="room-no" style="display: <?php echo (int) $tpl['arr']['cnt'] > 0 ? NULL : 'none'; ?>">
			<label class="title"><?php __('room_numbers'); ?></label>
			<span class="left block w550 float_left">
				<span><?php __('room_numbers_note'); ?></span>
				<span id="hbRoomNumber" class="hbRoomNumber block t5" data-numbers="<?php __('room_numbers', false, true); ?>" data-enter="<?php __('room_numbers_enter', false, true); ?>" data-note="<?php __('room_numbers_note', false, true); ?>">
					<?php
					if (isset($tpl['number_arr']) && !empty($tpl['number_arr']))
					{
						foreach($tpl['number_arr'] as $k => $v)
						{
							?><input type="text" name="number[<?php echo $v['id'];?>]" value="<?php echo $v['number']?>" class="pj-form-field w50 number-field" data-index="<?php echo $k + 1;?>" /><?php
						}
					} 
					?>
				</span>
			</span>
			<input type="hidden" name="room_number" id="room_number"/>
		</p>
		<p>
			<label class="title">&nbsp;</label>
			<input type="submit" value="<?php __('btnSave'); ?>" class="pj-button" />
		</p>
		<?php
		$info = __('info', true);
		pjUtil::printNotice($info['room_photos_title'], $info['room_photos_body']);
		?>
		<div id="gallery"></div>
	</form>
	
	<script type="text/javascript">
	var myGallery = myGallery || {};
	myGallery.foreign_id = "<?php echo $tpl['arr']['id']; ?>";
	myGallery.hash = "";
	var pjLocale = pjLocale || {};
	pjLocale.langs = <?php echo $tpl['locale_str']; ?>;
	pjLocale.flagPath = "<?php echo PJ_FRAMEWORK_LIBS_PATH; ?>pj/img/flags/";
	(function ($) {
		$(function() {
			$(".multilang").multilang({
				langs: pjLocale.langs,
				flagPath: pjLocale.flagPath,
				tooltip: "",
				select: function (event, ui) {
					// Callback, e.g. ajax requests or whatever
				}
			});
		});
	})(jQuery_1_8_2);
	</script>
	<?php
	if (isset($_GET['tab']) && (int) $_GET['tab'] > 0)
	{
		$tab_id = (int) $_GET['tab'];
		$tab_id = $tab_id < 0 ? 0 : $tab_id;
		?>
		<script type="text/javascript">
		(function ($) {
			$(function () {
				$("#tabs").tabs("option", "active", <?php echo $tab_id; ?>);
			});
		})(jQuery_1_8_2);
		</script>
		<?php
	}
}
?>