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
	include PJ_VIEWS_PATH . 'pjAdminRooms/elements/menu.php';
	?>
	
	<div class="ui-tabs ui-widget ui-widget-content ui-corner-all b10">
		<ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">
			<li class="ui-state-default ui-corner-top"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminDiscounts&amp;action=pjActionIndex"><?php __('menuPackage'); ?></a></li>
			<li class="ui-state-default ui-corner-top"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminDiscounts&amp;action=pjActionFrees"><?php __('menuFreeNight'); ?></a></li>
			<li class="ui-state-default ui-corner-top ui-tabs-active ui-state-active"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminDiscounts&amp;action=pjActionCodes"><?php __('menuPromoCode'); ?></a></li>
		</ul>
	</div>
	
	<?php pjUtil::printNotice(@$titles['AD11'], @$bodies['AD11']); ?>
	
	<?php
	if ($tpl['is_empty'])
	{
		?>
		<div class="empty-page">
			<div class="empty-text"><?php __('discount_code_empty_msg'); ?></div>
			<div class="empty-btn"><a href="<?php echo $_SERVER['PHP_SELF']; ?>" class="pj-button code-add"><?php __('discount_add_code'); ?></a></div>
		</div>
		<?php
	}
	?>
	
	<div<?php echo $tpl['is_empty'] ? ' style="display:none"' : NULL; ?>>
		<input type="button" value="<?php __('discount_add_code'); ?>" class="pj-button code-add b10" />
		<div id="grid_codes"></div>
	</div>
	<div id="dialogAddCode" style="display: none" title="<?php __('discount_add_code'); ?>"></div>
	<script type="text/javascript">
	var pjGrid = pjGrid || {};
	pjGrid.jqDateFormat = "<?php echo pjUtil::jqDateFormat($tpl['option_arr']['o_date_format']); ?>";
	pjGrid.jsDateFormat = "<?php echo pjUtil::jsDateFormat($tpl['option_arr']['o_date_format']); ?>";
	var myLabel = myLabel || {};
	myLabel.rooms = <?php echo $tpl['rooms']; ?>;
	myLabel.room = "<?php __('limit_room'); ?>";
	myLabel.date_from = "<?php __('limit_date_from'); ?>";
	myLabel.date_to = "<?php __('limit_date_to'); ?>";
	myLabel.code = "<?php __('discount_code'); ?>";
	myLabel.type = "<?php __('discount_type'); ?>";
	myLabel.types = <?php echo pjAppController::jsonEncode(__('discount_types', true)); ?>;
	myLabel.discount = "<?php __('discount_discount'); ?>";
	myLabel.delete_selected = "<?php __('delete_selected'); ?>";
	myLabel.delete_confirmation = "<?php __('delete_confirmation'); ?>";
	</script>
	<?php
}
?>