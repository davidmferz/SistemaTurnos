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
			<li class="ui-state-default ui-corner-top ui-tabs-active ui-state-active"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminDiscounts&amp;action=pjActionFrees"><?php __('menuFreeNight'); ?></a></li>
			<li class="ui-state-default ui-corner-top"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminDiscounts&amp;action=pjActionCodes"><?php __('menuPromoCode'); ?></a></li>
		</ul>
	</div>
	
	<?php pjUtil::printNotice(@$titles['AD10'], @$bodies['AD10']); ?>
	
	<?php
	if ($tpl['is_empty'])
	{
		?>
		<div class="empty-page">
			<div class="empty-text"><?php __('discount_free_empty'); ?></div>
			<div class="empty-btn"><a href="<?php echo $_SERVER['PHP_SELF']; ?>" class="pj-button free-add"><?php __('discount_add_free'); ?></a></div>
		</div>
		<?php
	}
	?>
	
	<div<?php echo $tpl['is_empty'] ? ' style="display:none"' : NULL; ?>>
		<input type="button" value="<?php __('discount_add_free'); ?>" class="pj-button free-add b10" />
		<div id="grid_frees"></div>
	</div>
	<div id="dialogAddFree" style="display: none" title="<?php __('discount_add_free'); ?>"></div>
	<script type="text/javascript">
	var pjGrid = pjGrid || {};
	pjGrid.jqDateFormat = "<?php echo pjUtil::jqDateFormat($tpl['option_arr']['o_date_format']); ?>";
	pjGrid.jsDateFormat = "<?php echo pjUtil::jsDateFormat($tpl['option_arr']['o_date_format']); ?>";
	var myLabel = myLabel || {};
	myLabel.rooms = <?php echo $tpl['rooms']; ?>;
	myLabel.room = "<?php __('limit_room'); ?>";
	myLabel.date_from = "<?php __('limit_date_from'); ?>";
	myLabel.date_to = "<?php __('limit_date_to'); ?>";
	myLabel.free_nights = "<?php __('discount_free_nights'); ?>";
	myLabel.min_length = "<?php __('discount_min_length'); ?>";
	myLabel.max_length = "<?php __('discount_max_length'); ?>";
	myLabel.delete_selected = "<?php __('delete_selected'); ?>";
	myLabel.delete_confirmation = "<?php __('delete_confirmation'); ?>";
	</script>
	<?php
}
?>