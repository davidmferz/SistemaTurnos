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
	$bs = __('booking_statuses', true);
	$week_start = isset($tpl['option_arr']['o_week_start']) && in_array((int) $tpl['option_arr']['o_week_start'], range(0,6)) ? (int) $tpl['option_arr']['o_week_start'] : 0;
	$jqDateFormat = pjUtil::jqDateFormat($tpl['option_arr']['o_date_format']);
	$months = __('months', true);
	$short_months = __('short_months', true);
	ksort($months);
	ksort($short_months);
	$days = __('days', true);
	$short_days = __('short_days', true);
	?>
	<style>
	.pj-status{
		width: 98px !important;
	}
	.pj-status-confirmed{background-position: 85px 3px;}
	.pj-status-pending{background-position: 85px 3px;}
	.pj-status-cancelled{background-position: 85px 3px;}
	</style>
	<div class="ui-tabs ui-widget ui-widget-content ui-corner-all b10">
		<ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">
			<li class="ui-state-default ui-corner-top"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBookings&amp;action=pjActionCalendar"><?php __('menuCalendar'); ?></a></li>
			<li class="ui-state-default ui-corner-top ui-tabs-active ui-state-active"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBookings&amp;action=pjActionIndex"><?php __('menuBookings'); ?></a></li>
			<li class="ui-state-default ui-corner-top"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBookings&amp;action=pjActionCreate"><?php __('booking_add'); ?></a></li>
			<li class="ui-state-default ui-corner-top"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjInvoice&amp;action=pjActionInvoices"><?php __('plugin_invoice_menu_invoices'); ?></a></li>
		</ul>
	</div>
	<?php
	pjUtil::printNotice(@$titles['ABK16'], @$bodies['ABK16']);
	
	if ($tpl['is_empty'])
	{
		?>
		<div class="empty-page">
			<div class="empty-text"><?php __('booking_empty_msg'); ?></div>
			<div class="empty-btn"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBookings&amp;action=pjActionCreate" class="pj-button"><?php __('booking_add_plus'); ?></a></div>
		</div>
		<?php
	} else {
		?>
		<div class="b10">
			<form action="" method="get" class="float_left pj-form frm-filter">
				<input type="text" name="q" class="pj-form-field pj-form-field-search w150" placeholder="<?php __('btnSearch'); ?>" />
				<button type="button" class="pj-button pj-button-detailed"><span class="pj-button-detailed-arrow"></span></button>
			</form>
			<div class="float_right t5">
				<a href="#" class="pj-button btn-all"><?php __('lblAll'); ?></a>
				<a href="#" class="pj-button btn-filter btn-status" data-column="status" data-value="confirmed"><?php echo $bs['confirmed']; ?></a>
				<a href="#" class="pj-button btn-filter btn-status" data-column="status" data-value="pending"><?php echo $bs['pending']; ?></a>
				<a href="#" class="pj-button btn-filter btn-status" data-column="status" data-value="not_confirmed"><?php echo $bs['not_confirmed']; ?></a>
				<a href="#" class="pj-button btn-filter btn-status" data-column="status" data-value="cancelled"><?php echo $bs['cancelled']; ?></a>
			</div>
			<br class="clear_both" />
		</div>
	
		<div class="pj-form-filter-advanced" style="display: none">
			<span class="pj-menu-list-arrow"></span>
			<form action="" method="get" class="form pj-form pj-form-search frm-filter-advanced">
				<div class="float_left w400">
					<p>
						<label class="title"><?php __('booking_search_arrival_departure'); ?></label>
						<span class="pj-form-field-custom pj-form-field-custom-after">
							<input type="text" name="date_from" class="pj-form-field pointer w80 datepick" readonly="readonly" rel="<?php echo $week_start; ?>" rev="<?php echo $jqDateFormat; ?>" value="<?php echo isset($_GET['date_from']) ? pjSanitize::html($_GET['date_from']) : NULL; ?>" data-months="<?php echo join(',', $months);?>" data-shortmonths="<?php echo join(',', $short_months);?>" data-day="<?php echo join(',', $days);?>" data-daymin="<?php echo join(',', $short_days);?>"/>
							<span class="pj-form-field-after"><abbr class="pj-form-field-icon-date"></abbr></span>
						</span>
						<span class="pj-form-field-custom pj-form-field-custom-after">
							<input type="text" name="date_to" class="pj-form-field pointer w80 datepick" readonly="readonly" rel="<?php echo $week_start; ?>" rev="<?php echo $jqDateFormat; ?>" value="<?php echo isset($_GET['date_to']) ? pjSanitize::html($_GET['date_to']) : NULL; ?>" data-months="<?php echo join(',', $months);?>" data-shortmonths="<?php echo join(',', $short_months);?>" data-day="<?php echo join(',', $days);?>" data-daymin="<?php echo join(',', $short_days);?>"/>
							<span class="pj-form-field-after"><abbr class="pj-form-field-icon-date"></abbr></span>
						</span>
					</p>
					<p>
						<label class="title"><?php __('booking_search_room'); ?></label>
						<select name="room_id" class="pj-form-field w150">
							<option value="">-- <?php __('lblChoose'); ?> --</option>
							<?php
							foreach ($tpl['room_arr'] as $room)
							{
								?><option value="<?php echo $room['id']; ?>"<?php echo isset($_GET['room_id']) && $_GET['room_id'] == $room['id'] ? ' selected="selected"' : NULL; ?>><?php echo pjSanitize::html($room['name']); ?></option><?php
							}
							?>
						</select>
					</p>
					<p>
						<label class="title"><?php __('booking_search_c_name'); ?></label>
						<input type="text" name="c_name" class="pj-form-field w150" />
					</p>
					<p>
						<label class="title"><?php __('booking_search_c_email'); ?></label>
						<input type="text" name="c_email" class="pj-form-field w150" />
					</p>
					<p>
						<label class="title"><?php __('booking_search_created'); ?></label>
						<span class="pj-form-field-custom pj-form-field-custom-after">
							<input type="text" name="created_from" class="pj-form-field pointer w80 datepick" readonly="readonly" rel="<?php echo $week_start; ?>" rev="<?php echo $jqDateFormat; ?>" data-months="<?php echo join(',', $months);?>" data-shortmonths="<?php echo join(',', $short_months);?>" data-day="<?php echo join(',', $days);?>" data-daymin="<?php echo join(',', $short_days);?>" />
							<span class="pj-form-field-after"><abbr class="pj-form-field-icon-date"></abbr></span>
						</span>
						<span class="pj-form-field-custom pj-form-field-custom-after">
							<input type="text" name="created_to" class="pj-form-field pointer w80 datepick" readonly="readonly" rel="<?php echo $week_start; ?>" rev="<?php echo $jqDateFormat; ?>" data-months="<?php echo join(',', $months);?>" data-shortmonths="<?php echo join(',', $short_months);?>" data-day="<?php echo join(',', $days);?>" data-daymin="<?php echo join(',', $short_days);?>" />
							<span class="pj-form-field-after"><abbr class="pj-form-field-icon-date"></abbr></span>
						</span>
					</p>
					<p>
						<label class="title">&nbsp;</label>
						<input type="submit" value="<?php __('btnSearch'); ?>" class="pj-button" />
						<input type="reset" value="<?php __('btnCancel'); ?>" class="pj-button" />
					</p>
				</div>
				<div class="float_right w300">
					<p>
						<label class="title" style="width: 110px"><?php __('booking_status'); ?></label>
						<select name="status" class="pj-form-field w150">
							<option value="">-- <?php __('lblChoose'); ?> --</option>
							<?php
							foreach (__('booking_statuses', true) as $k => $v)
							{
								?><option value="<?php echo $k; ?>"<?php echo isset($_GET['status']) && $_GET['status'] == $k ? ' selected="selected"' : NULL; ?>><?php echo pjSanitize::html($v); ?></option><?php
							}
							?>
						</select>
					</p>
					<p>
						<label class="title" style="width: 110px"><?php __('booking_payment_method'); ?></label>
						<select name="payment_method" class="pj-form-field w150">
							<option value="">-- <?php __('lblChoose'); ?> --</option>
							<?php
							foreach (__('booking_payments', true) as $k => $v)
							{
								?><option value="<?php echo $k; ?>"<?php echo isset($_GET['payment_method']) && $_GET['payment_method'] == $k ? ' selected="selected"' : NULL; ?>><?php echo pjSanitize::html($v); ?></option><?php
							}
							?>
						</select>
					</p>
					<p>
						<label class="title" style="width: 110px"><?php __('booking_search_price'); ?></label>
						<span class="pj-form-field-custom pj-form-field-custom-before">
							<span class="pj-form-field-before"><abbr class="pj-form-field-icon-text"><?php echo pjUtil::formatCurrencySign(NULL, $tpl['option_arr']['o_currency'], ""); ?></abbr></span>
							<input type="text" name="total_from" class="pj-form-field number w50" />
						</span>
						<span class="pj-form-field-custom pj-form-field-custom-before">
							<span class="pj-form-field-before"><abbr class="pj-form-field-icon-text"><?php echo pjUtil::formatCurrencySign(NULL, $tpl['option_arr']['o_currency'], ""); ?></abbr></span>
							<input type="text" name="total_to" class="pj-form-field number w50" />
						</span>
					</p>
					<p>
						<label class="title" style="width: 110px"><?php __('booking_uuid'); ?></label>
						<input type="text" name="uuid" class="pj-form-field w150" />
					</p>
					<p>
						<label class="title" style="width: 110px"><?php __('booking_voucher'); ?></label>
						<input type="text" name="voucher" class="pj-form-field w150" />
					</p>
				</div>
				<br class="clear_both" />
			</form>
		</div>

		<div id="grid"></div>
		<script type="text/javascript">
		var pjGrid = pjGrid || {};
		pjGrid.jsDateFormat = "<?php echo pjUtil::jsDateFormat($tpl['option_arr']['o_date_format']); ?>";
		var myLabel = myLabel || {};
		myLabel.id = "<?php __('booking_id'); ?>";
		myLabel.stay = "<?php __('booking_stay'); ?>";
		myLabel.client = "<?php __('booking_client'); ?>";
		myLabel.rooms = "<?php __('booking_rooms'); ?>";
		myLabel.cancelled = "<?php echo $bs['cancelled']; ?>";
		myLabel.pending = "<?php echo $bs['pending']; ?>";
		myLabel.confirmed = "<?php echo $bs['confirmed']; ?>";
		myLabel.not_confirmed = "<?php echo $bs['not_confirmed']; ?>";
		myLabel.status = "<?php __('lblStatus'); ?>";
		myLabel.exported = "<?php __('lblExport'); ?>";
		myLabel.delete_selected = "<?php __('delete_selected'); ?>";
		myLabel.delete_confirmation = "<?php __('delete_confirmation'); ?>";
		</script>
		<?php
	}
}
?>