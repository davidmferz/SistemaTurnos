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
	$days = __('days', true);
	include PJ_VIEWS_PATH . 'pjAdminRooms/elements/menu.php';
	?>
	<div class="ui-tabs ui-widget ui-widget-content ui-corner-all b10">
		<ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">
			<li class="ui-state-default ui-corner-top ui-tabs-active ui-state-active"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminDiscounts&amp;action=pjActionIndex"><?php __('menuPackage'); ?></a></li>
			<li class="ui-state-default ui-corner-top"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminDiscounts&amp;action=pjActionFrees"><?php __('menuFreeNight'); ?></a></li>
			<li class="ui-state-default ui-corner-top"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminDiscounts&amp;action=pjActionCodes"><?php __('menuPromoCode'); ?></a></li>
		</ul>
	</div>
	
	<?php pjUtil::printNotice(@$titles['AD12'], @$bodies['AD12']); ?>
	
	<?php
	if ($tpl['is_empty'])
	{
		?>
		<div class="empty-page">
			<div class="empty-text"><?php __('discount_packages_empty_msg'); ?></div>
			<div class="empty-btn"><a href="<?php echo $_SERVER['PHP_SELF']; ?>" class="pj-button package-add"><?php __('discount_add_package'); ?></a></div>
		</div>
		<?php
	}
	?>
	
	<div<?php echo $tpl['is_empty'] ? ' style="display:none"' : NULL; ?>>
		<input type="button" value="<?php __('discount_add_package'); ?>" class="pj-button package-add b10" />
		<div id="grid_packages"></div>
	</div>
	
	<div id="dialogAddPackage" style="display: none" title="<?php __('discount_add_package'); ?>"></div>
	<div id="dialogPackageItems" style="display: none" title="<?php __('prices_based_on_adults_children'); ?>"></div>
	<div id="dialogPackageAddItem" style="display: none" title="<?php __('discount_enter_price'); ?>"></div>
	<script type="text/javascript">
	var pjGrid = pjGrid || {};
	pjGrid.jqDateFormat = "<?php echo pjUtil::jqDateFormat($tpl['option_arr']['o_date_format']); ?>";
	pjGrid.jsDateFormat = "<?php echo pjUtil::jsDateFormat($tpl['option_arr']['o_date_format']); ?>";
	var myLabel = myLabel || {};
	myLabel.adults = "<?php __('rooms_adults'); ?>";
	myLabel.children = "<?php __('rooms_children'); ?>";
	myLabel.price = "<?php __('discount_price'); ?>";
	myLabel.rooms = <?php echo $tpl['rooms']; ?>;
	myLabel.room = "<?php __('limit_room'); ?>";
	myLabel.date_from = "<?php __('limit_date_from'); ?>";
	myLabel.date_to = "<?php __('limit_date_to'); ?>";
	myLabel.start_day = "<?php __('discount_start_day'); ?>";
	myLabel.end_day = "<?php __('discount_end_day'); ?>";
	myLabel.total_price = "<?php __('discount_total_price'); ?>";
	myLabel.more_price = "<?php __('more_prices'); ?>";
	myLabel.mon = "<?php echo $days[1]; ?>";
	myLabel.tue = "<?php echo $days[2]; ?>";
	myLabel.wed = "<?php echo $days[3]; ?>";
	myLabel.thu = "<?php echo $days[4]; ?>";
	myLabel.fri = "<?php echo $days[5]; ?>";
	myLabel.sat = "<?php echo $days[6]; ?>";
	myLabel.sun = "<?php echo $days[0]; ?>";
	myLabel.delete_selected = "<?php __('delete_selected'); ?>";
	myLabel.delete_confirmation = "<?php __('delete_confirmation'); ?>";
	</script>
	<?php
}
?>