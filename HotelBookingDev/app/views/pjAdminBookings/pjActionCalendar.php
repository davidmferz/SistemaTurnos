<div class="ui-tabs ui-widget ui-widget-content ui-corner-all b10">
	<ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">
		<li class="ui-state-default ui-corner-top ui-tabs-active ui-state-active"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBookings&amp;action=pjActionCalendar"><?php __('menuCalendar'); ?></a></li>
		<li class="ui-state-default ui-corner-top"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBookings&amp;action=pjActionIndex"><?php __('menuBookings'); ?></a></li>
		<li class="ui-state-default ui-corner-top"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBookings&amp;action=pjActionCreate"><?php __('booking_add'); ?></a></li>
		<li class="ui-state-default ui-corner-top"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjInvoice&amp;action=pjActionInvoices"><?php __('plugin_invoice_menu_invoices'); ?></a></li>
	</ul>
</div>
<?php
if (!empty($tpl['room_arr'])) 
{
	$s_date = pjUtil::formatDate(date('Y-m-d'), 'Y-m-d', $tpl['option_arr']['o_date_format']);
	if (isset($tpl['selected_date']))
	{
		$s_date = $tpl['selected_date'];
	}
	$titles = __('error_titles', true);
	$bodies = __('error_bodies', true);
	pjUtil::printNotice(@$titles['ABK21'], @$bodies['ABK21']);
	?>
	<div class="boxOuter">
		<div class="hb-loader"></div>
		<div id="boxCalendar"><?php include dirname(__FILE__) . '/pjActionGetCalendar.php'; ?></div>
		<div class="hbCalendarLegend">
			<a data-href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBookings&action=pjActionPrintCalendar" id="hb_print_calendar" target="_blank" href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBookings&action=pjActionPrintCalendar&selected_date=<?php echo $s_date;?>" class="pj-button inline_block"><?php __('btnPrintCalendar'); ?></a>
			<div class="confirmed"><abbr></abbr><span><?php __('booking_legend_confirmed'); ?></span></div>
			<div class="pending"><abbr></abbr><span><?php __('booking_legend_pending'); ?></span></div>
			<div class="unavailable"><abbr></abbr><span><?php __('booking_legend_unavailable'); ?></span></div>
		</div>
	</div>
	<?php
	
} else {
	$titles = __('error_titles', true);
	$bodies = __('error_bodies', true);
	pjUtil::printNotice(@$titles['ABK20'], @$bodies['ABK20'], false);
	if($controller->isAdmin())
	{
		?>
		<div class="empty-page">
			<div class="empty-text"><?php echo @$titles['ABK20']; ?></div>
			<div class="empty-btn"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminRooms&amp;action=pjActionCreate" class="pj-button"><?php __('room_add'); ?></a></div>
		</div>
		<?php
	}
}
?>