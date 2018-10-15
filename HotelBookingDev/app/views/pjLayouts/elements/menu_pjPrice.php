<div class="ui-tabs ui-widget ui-widget-content ui-corner-all b10">
	<ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">
		<li class="ui-state-default ui-corner-top"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminRooms&amp;action=pjActionIndex"><?php __('menuRooms'); ?></a></li>
		<li class="ui-state-default ui-corner-top ui-tabs-active ui-state-active"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjPrice"><?php __('menuPrice'); ?></a></li>
		<li class="ui-state-default ui-corner-top"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminLimits&amp;action=pjActionIndex"><?php __('menuLimits'); ?></a></li>
		<li class="ui-state-default ui-corner-top"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminDiscounts&amp;action=pjActionIndex"><?php __('menuDiscounts'); ?></a></li>
		<li class="ui-state-default ui-corner-top"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminExtras&amp;action=pjActionIndex"><?php __('menuExtras'); ?></a></li>
		<li class="ui-state-default ui-corner-top"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminRestrictions&amp;action=pjActionIndex"><?php __('menuRestrictions'); ?></a></li>
	</ul>
</div>

<?php
/*$titles = __('error_titles', true);
$bodies = __('error_bodies', true);
pjUtil::printNotice(@$titles['AR10'], @$bodies['AR10']);*/
?>

<fieldset class="fieldset white">
	<legend><?php __('room_select'); ?></legend>
	<select name="ch_room_id" class="pj-form-field" data-url="<?php echo PJ_INSTALL_URL; ?>index.php?controller=pjPrice&action=pjActionIndex&room_id=">
		<option value="">-- <?php __('room_select'); ?> --</option>
		<?php
		$room_id = (isset($_GET['room_id']) && (int) $_GET['room_id'] > 0) ? (int) $_GET['room_id'] : $controller->getForeignIdPrice();
		foreach ($tpl['room_arr'] as $item)
		{
			?><option value="<?php echo $item['id']; ?>"<?php echo $room_id != $item['id'] ? NULL : ' selected="selected"'; ?>><?php echo stripslashes($item['name']); ?></option><?php
		}
		?>
	</select>
</fieldset>