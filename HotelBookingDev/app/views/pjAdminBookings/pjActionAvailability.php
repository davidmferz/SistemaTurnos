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
	$bs = __('booking_statuses', true);
	?>
	<style type="text/css">
	.cal-program span{
		cursor: default !important;
	}
	</style>
	
	<div class="ui-tabs ui-widget ui-widget-content ui-corner-all b10">
		<ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">
			<li class="ui-state-default ui-corner-top ui-tabs-active ui-state-active"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBookings&amp;action=pjActionAvailability"><?php __('menuAvailability'); ?></a></li>
			<li class="ui-state-default ui-corner-top"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBookings&amp;action=pjActionIndex"><?php __('menuBookings'); ?></a></li>
			<li class="ui-state-default ui-corner-top"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBookings&amp;action=pjActionCreate"><?php __('booking_add'); ?></a></li>
			<li class="ui-state-default ui-corner-top"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjInvoice&amp;action=pjActionInvoices"><?php __('plugin_invoice_menu_invoices'); ?></a></li>
		</ul>
	</div>
	<?php
	pjUtil::printNotice(@$titles['ABK09'], @$bodies['ABK09']);
	?>
	<div id="boxAvailability"><?php include dirname(__FILE__) . '/pjActionGetAvailability.php'; ?></div>
	
	<div id="grid_avail" class="t20"></div>
	<script type="text/javascript">
	var pjGrid = pjGrid || {};
	pjGrid.jsDateFormat = "<?php echo pjUtil::jsDateFormat($tpl['option_arr']['o_date_format']); ?>";
	var myLabel = myLabel || {};
	myLabel.dates = "<?php __('booking_dates'); ?>";
	myLabel.client = "<?php __('booking_client'); ?>";
	myLabel.rooms = "<?php __('booking_rooms'); ?>";
	myLabel.cancelled = "<?php echo $bs['cancelled']; ?>";
	myLabel.pending = "<?php echo $bs['pending']; ?>";
	myLabel.confirmed = "<?php echo $bs['confirmed']; ?>";
	myLabel.status = "<?php __('lblStatus'); ?>";
	myLabel.exported = "<?php __('lblExport'); ?>";
	myLabel.delete_selected = "<?php __('delete_selected'); ?>";
	myLabel.delete_confirmation = "<?php __('delete_confirmation'); ?>";
	</script>
	<?php
}
?>