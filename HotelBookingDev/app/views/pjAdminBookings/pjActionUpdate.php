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
	$week_start = isset($tpl['option_arr']['o_week_start']) && in_array((int) $tpl['option_arr']['o_week_start'], range(0,6)) ? (int) $tpl['option_arr']['o_week_start'] : 0;
	$jqDateFormat = pjUtil::jqDateFormat($tpl['option_arr']['o_date_format']);
	
	$months = __('months', true);
	$short_months = __('short_months', true);
	ksort($months);
	ksort($short_months);
	$days = __('days', true);
	$short_days = __('short_days', true);
	?>
	<div class="ui-tabs ui-widget ui-widget-content ui-corner-all b10">
		<ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">
			<li class="ui-state-default ui-corner-top"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBookings&amp;action=pjActionCalendar"><?php __('menuCalendar'); ?></a></li>
			<li class="ui-state-default ui-corner-top"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBookings&amp;action=pjActionIndex"><?php __('menuBookings'); ?></a></li>
			<li class="ui-state-default ui-corner-top"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBookings&amp;action=pjActionCreate"><?php __('booking_add'); ?></a></li>
			<li class="ui-state-default ui-corner-top ui-tabs-active ui-state-active"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBookings&amp;action=pjActionUpdate&amp;id=<?php echo $tpl['arr']['id']; ?>"><?php __('booking_update'); ?></a></li>
			<li class="ui-state-default ui-corner-top"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjInvoice&amp;action=pjActionInvoices"><?php __('plugin_invoice_menu_invoices'); ?></a></li>
		</ul>
	</div>

	<form action="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBookings&amp;action=pjActionUpdate" method="post" id="frmUpdateBooking" class="form pj-form">
		<input type="hidden" name="booking_update" value="1" />
		<input type="hidden" name="id" value="<?php echo $tpl['arr']['id']; ?>" />
		
		<div id="tabs">
			<ul>
				<li><a href="#tabs-1"><?php __('booking_tab_details'); ?></a></li>
				<li><a href="#tabs-2"><?php __('booking_tab_client'); ?></a></li>
				<?php
				if (pjObject::getPlugin('pjInvoice') !== NULL)
				{
					?><li><a href="#tabs-3"><?php __('plugin_invoice_menu_invoices'); ?></a></li><?php
				}
				?>
			</ul>
		
			<div id="tabs-1">
				<?php pjUtil::printNotice(@$titles['ABK11'], @$bodies['ABK11']); ?>
				<fieldset class="fieldset sky w380 float_right">
					<legend><?php __('booking_payment_details'); ?></legend>
					<p>
						<label class="title"><?php __('booking_payment_method'); ?></label>
						<span class="inline_block">
							<select name="payment_method" id="payment_method" class="pj-form-field required" data-msg-required="<?php __('lblFieldRequired');?>">
								<option value="">-- <?php __('lblChoose'); ?>--</option>
								<?php
								foreach (__('booking_payments', true) as $k => $v)
								{
									?><option value="<?php echo $k; ?>"<?php echo $k != $tpl['arr']['payment_method'] ? NULL : ' selected="selected"'; ?>><?php echo $v; ?></option><?php
								}
								?>
							</select>
						</span>
					</p>
					<p class="hbCC" style="display: <?php echo $tpl['arr']['payment_method'] != 'creditcard' ? 'none' : 'block'; ?>">
						<label class="title"><?php __('booking_cc_type'); ?></label>
						<span class="inline_block">
							<select name="cc_type" class="pj-form-field w140">
								<option value="">---</option>
								<?php
								foreach (__('booking_cc_types', true) as $k => $v)
								{
									?><option value="<?php echo $k; ?>"<?php echo $k != $tpl['arr']['cc_type'] ? NULL : ' selected="selected"'; ?>><?php echo $v; ?></option><?php
								}
								?>
							</select>
						</span>
					</p>
					<p class="hbCC" style="display: <?php echo $tpl['arr']['payment_method'] != 'creditcard' ? 'none' : 'block'; ?>">
						<label class="title"><?php __('booking_cc_num'); ?></label>
						<span class="inline_block">
							<input type="text" name="cc_num" id="cc_num" class="pj-form-field w120 digits" value="<?php echo pjSanitize::html($tpl['arr']['cc_num']); ?>" />
						</span>
					</p>
					<p class="hbCC" style="display: <?php echo $tpl['arr']['payment_method'] != 'creditcard' ? 'none' : 'block'; ?>">
						<label class="title"><?php __('booking_cc_code'); ?></label>
						<span class="inline_block">
							<input type="text" name="cc_code" id="cc_code" class="pj-form-field w120 digits" value="<?php echo pjSanitize::html($tpl['arr']['cc_code']); ?>" />
						</span>
					</p>
					<p class="hbCC" style="display: <?php echo $tpl['arr']['payment_method'] != 'creditcard' ? 'none' : 'block'; ?>">
						<label class="title"><?php __('booking_cc_exp'); ?></label>
						<span class="inline_block">
							<?php
							echo pjTime::factory()
								->attr('name', 'cc_exp_month')
								->attr('id', 'cc_exp_month')
								->attr('class', 'pj-form-field')
								->prop('format', 'M')
								->prop('selected', $tpl['arr']['cc_exp_month'])
								->month();
							?>
							<?php
							echo pjTime::factory()
								->attr('name', 'cc_exp_year')
								->attr('id', 'cc_exp_year')
								->attr('class', 'pj-form-field')
								->prop('left', 0)
								->prop('right', 10)
								->prop('selected', $tpl['arr']['cc_exp_year'])
								->year();
							?>
						</span>
					</p>
					<p>
						<label class="title"><?php __('booking_room_price'); ?></label>
						<span class="pj-form-field-custom pj-form-field-custom-before">
							<span class="pj-form-field-before"><abbr class="pj-form-field-icon-text"><?php echo pjUtil::formatCurrencySign(NULL, $tpl['option_arr']['o_currency'], ""); ?></abbr></span>
							<input type="text" name="room_price" id="room_price" class="pj-form-field align_right w70 number" value="<?php echo number_format((float) $tpl['arr']['room_price'], 2, '.', ''); ?>" />
						</span>
					</p>
					<p>
						<label class="title"><?php __('booking_extra_price'); ?></label>
						<span class="pj-form-field-custom pj-form-field-custom-before">
							<span class="pj-form-field-before"><abbr class="pj-form-field-icon-text"><?php echo pjUtil::formatCurrencySign(NULL, $tpl['option_arr']['o_currency'], ""); ?></abbr></span>
							<input type="text" name="extra_price" id="extra_price" class="pj-form-field align_right w70 number" value="<?php echo number_format((float) $tpl['arr']['extra_price'], 2, '.', ''); ?>" />
						</span>
					</p>
					<p>
						<label class="title"><?php __('booking_total'); ?></label>
						<span class="pj-form-field-custom pj-form-field-custom-before">
							<span class="pj-form-field-before"><abbr class="pj-form-field-icon-text"><?php echo pjUtil::formatCurrencySign(NULL, $tpl['option_arr']['o_currency'], ""); ?></abbr></span>
							<input type="text" name="total" id="total" class="pj-form-field align_right w70 number" value="<?php echo number_format((float) $tpl['arr']['total'], 2, '.', ''); ?>" />
						</span>
					</p>
					<p>
						<label class="title"><?php __('booking_tax'); ?></label>
						<span class="pj-form-field-custom pj-form-field-custom-before">
							<span class="pj-form-field-before"><abbr class="pj-form-field-icon-text"><?php echo pjUtil::formatCurrencySign(NULL, $tpl['option_arr']['o_currency'], ""); ?></abbr></span>
							<input type="text" name="tax" id="tax" class="pj-form-field align_right w70 number" value="<?php echo number_format((float) $tpl['arr']['tax'], 2, '.', ''); ?>" />
						</span>
					</p>
					<p>
						<label class="title"><?php __('booking_security'); ?></label>
						<span class="pj-form-field-custom pj-form-field-custom-before">
							<span class="pj-form-field-before"><abbr class="pj-form-field-icon-text"><?php echo pjUtil::formatCurrencySign(NULL, $tpl['option_arr']['o_currency'], ""); ?></abbr></span>
							<input type="text" name="security" id="security" class="pj-form-field align_right w70 number" value="<?php echo number_format((float) $tpl['arr']['security'], 2, '.', ''); ?>" />
						</span>
					</p>
					<p>
						<label class="title"><?php __('booking_deposit'); ?></label>
						<span class="pj-form-field-custom pj-form-field-custom-before">
							<span class="pj-form-field-before"><abbr class="pj-form-field-icon-text"><?php echo pjUtil::formatCurrencySign(NULL, $tpl['option_arr']['o_currency'], ""); ?></abbr></span>
							<input type="text" name="deposit" id="deposit" class="pj-form-field align_right w70 number" value="<?php echo number_format((float) $tpl['arr']['deposit'], 2, '.', ''); ?>" />
						</span>
					</p>
					<p>
						<label class="title"><?php __('booking_voucher'); ?></label>
						<span class="inline_block">
							<input type="text" name="voucher" id="voucher" class="pj-form-field w100" value="<?php echo pjSanitize::html($tpl['arr']['voucher']); ?>" />
						</span>
					</p>
					<p>
						<label class="title"><?php __('booking_discount'); ?></label>
						<span class="pj-form-field-custom pj-form-field-custom-before">
							<span class="pj-form-field-before"><abbr class="pj-form-field-icon-text"><?php echo pjUtil::formatCurrencySign(NULL, $tpl['option_arr']['o_currency'], ""); ?></abbr></span>
							<input type="text" name="discount" id="discount" class="pj-form-field align_right w70 number" value="<?php echo number_format((float) $tpl['arr']['discount'], 2, '.', ''); ?>" />
						</span>
					</p>
					<?php
					$createInvoice = $balancePayment = false;
					$statuses = __('plugin_invoice_statuses', true);
					
					$createInvoice = isset($tpl['invoice_arr']) && count($tpl['invoice_arr']) >= 2;
					$balancePayment = isset($tpl['invoice_arr']) && count($tpl['invoice_arr']) === 1 && $tpl['invoice_arr'][0]['total'] < $tpl['arr']['total'];
					?>
					<p>
						<label class="title">&nbsp;</label>
						<input type="submit" value="<?php __('btnSave'); ?>" class="pj-button" />
						<input type="button" value="<?php __('booking_recalc'); ?>" class="pj-button booking-recalc" />
					</p>
				</fieldset>
				
				<fieldset class="fieldset white w560 float_left">
					<legend><?php __('booking_booking_details'); ?></legend>
					<p>
						<label class="title"><?php __('booking_client'); ?></label>
						<span class="left"><?php 
						if (!empty($tpl['arr']['c_fname']) || !empty($tpl['arr']['c_lname']))
						{
							$client_link = pjSanitize::html($tpl['arr']['c_fname'] . " " . $tpl['arr']['c_lname']);
						} else {
							$client_link = __('booking_client_add', true);
						}
						?><a href="<?php echo $_SERVER['PHP_SELF']; ?>" class="client-details"><?php echo $client_link; ?></a></span>
					</p>
					<p>
						<label class="title"><?php __('booking_status'); ?></label>
						<span class="inline_block">
							<select name="status" id="status" class="pj-form-field w150 required float_left" data-msg-required="<?php __('lblFieldRequired');?>">
								<option value="">-- <?php __('lblChoose'); ?>--</option>
								<?php
								foreach (__('booking_statuses', true) as $k => $v)
								{
									?><option value="<?php echo $k; ?>"<?php echo $k != $tpl['arr']['status'] ? NULL : ' selected="selected"'; ?>><?php echo $v; ?></option><?php
								}
								?>
							</select>
							<i class="t t-note" title="<?php __('booking_status_hint', false, true); ?>"></i>
						</span>
					</p>
					<p>
						<label class="title"><?php __('booking_arrival_date_time'); ?></label>
						<span>
							<span class="pj-form-field-custom pj-form-field-custom-after align_middle">
								<input type="text" name="date_from" id="date_from" class="pj-form-field pointer w80 datepick required" data-based="<?php echo $tpl['option_arr']['o_price_based_on'];?>" data-msg-required="<?php __('lblFieldRequired');?>" readonly="readonly" rel="<?php echo $week_start; ?>" rev="<?php echo $jqDateFormat; ?>" value="<?php echo pjUtil::formatDate($tpl['arr']['date_from'], 'Y-m-d', $tpl['option_arr']['o_date_format']); ?>" data-months="<?php echo join(',', $months);?>" data-shortmonths="<?php echo join(',', $short_months);?>" data-day="<?php echo join(',', $days);?>" data-daymin="<?php echo join(',', $short_days);?>"/>
								<span class="pj-form-field-after"><abbr class="pj-form-field-icon-date"></abbr></span>
							</span>
							<span class="inline_block l5">
							<?php
							echo pjTime::factory()
								->attr('name', 'hour')
								->attr('id', 'hour')
								->attr('class', 'pj-form-field')
								->prop('selected', substr($tpl['arr']['c_arrival'], 0, 2))
								->hour();
							?>
							<?php
							echo pjTime::factory()
								->attr('name', 'minute')
								->attr('id', 'minute')
								->attr('class', 'pj-form-field')
								->prop('step', 1)
								->prop('selected', substr($tpl['arr']['c_arrival'], 3, 2))
								->minute();
							?>
							</span>
						</span>
					</p>
					<p>
						<label class="title"><?php __('booking_departure_date'); ?></label>
						<span class="pj-form-field-custom pj-form-field-custom-after">
							<input type="text" name="date_to" id="date_to" class="pj-form-field pointer w80 datepick required" readonly="readonly" rel="<?php echo $week_start; ?>" rev="<?php echo $jqDateFormat; ?>" value="<?php echo pjUtil::formatDate($tpl['arr']['date_to'], 'Y-m-d', $tpl['option_arr']['o_date_format']); ?>" data-msg-required="<?php __('lblFieldRequired');?>" data-months="<?php echo join(',', $months);?>" data-shortmonths="<?php echo join(',', $short_months);?>" data-day="<?php echo join(',', $days);?>" data-daymin="<?php echo join(',', $short_days);?>"/>
							<span class="pj-form-field-after"><abbr class="pj-form-field-icon-date"></abbr></span>
						</span>
					</p>
					<p>
						<label class="title"><?php __('booking_uuid'); ?></label>
						<span class="inline_block">
							<input type="text" name="uuid" id="uuid" class="pj-form-field w150 required" value="<?php echo pjSanitize::html($tpl['arr']['uuid']); ?>" data-msg-required="<?php __('lblFieldRequired');?>"/>
						</span>
					</p>
					<p>
						<label class="title">&nbsp;</label>
						<input type="submit" value="<?php __('btnSave', false, true); ?>" class="pj-button" />
					</p>
				</fieldset>
				
				<fieldset class="fieldset white w560 float_left">
					<legend><?php __('booking_rooms_extras'); ?></legend>
					<div id="boxRooms"></div>
					<table cellpadding="0" cellspacing="0" class="pj-table b10" style="width: 100%">
						<thead>
							<tr>
								<th><?php __('booking_extras'); ?></th>
								<th class="w30">&nbsp;</th>
							</tr>
						</thead>
						<tbody>
						<?php
						if (isset($tpl['extra_arr']) && !empty($tpl['extra_arr']))
						{
							$per = __('extra_per', true);
							foreach ($tpl['extra_arr'] as $k => $extra)
							{
								?>
								<tr>
									<td><?php echo pjSanitize::html($extra['name']); ?> / <?php echo pjUtil::formatCurrencySign(number_format($extra['price'], 2), $tpl['option_arr']['o_currency']); ?> - <?php echo @$per[$extra['per']]; ?>
									<?php
									if (isset($tpl['be_arr'][$extra['id']]) && $tpl['be_arr'][$extra['id']] != $extra['price'])
									{
										?>(<span class="red"><?php echo pjUtil::formatCurrencySign(number_format($tpl['be_arr'][$extra['id']], 2), $tpl['option_arr']['o_currency']); ?></span>)<?php
									}
									?></td>
									<td class="align_center"><input type="checkbox" class="align_middle" name="extra_id[<?php echo $extra['id']; ?>]" value="<?php echo $extra['per']; ?>|<?php echo $extra['price']; ?>"<?php echo !array_key_exists($extra['id'], $tpl['be_arr']) ? NULL : ' checked="checked"'; ?> /></td>
								</tr>
								<?php
							}
						} else {
							?>
							<tr>
								<td colspan="2"><?php __('booking_extra_note'); ?></td>
							</tr>
							<?php
						}
						?>
						</tbody>
					</table>
					<input type="submit" value="<?php __('btnSave', false, true); ?>" class="pj-button" />
				</fieldset>
			</div>
			<div id="tabs-2">
				<?php pjUtil::printNotice(@$titles['ABK12'], @$bodies['ABK12']); ?>
				<fieldset class="fieldset white">
					<legend><?php __('booking_client_details'); ?></legend>
					<div class="float_left w340">
						<p>
							<label class="title"><?php __('booking_c_title'); ?></label>
							<span class="inline_block">
								<select name="c_title" id="c_title" class="pj-form-field w100">
								<?php
								foreach (__('personal_titles', true) as $k => $v)
								{
									?><option value="<?php echo $k; ?>"<?php echo $k != $tpl['arr']['c_title'] ? NULL : ' selected="selected"'; ?>><?php echo stripslashes($v); ?></option><?php
								}
								?>
								</select>
							</span>
						</p>
						<p>
							<label class="title"><?php __('booking_c_fname'); ?></label>
							<span class="inline_block">
								<input type="text" name="c_fname" id="c_fname" class="pj-form-field w160" value="<?php echo pjSanitize::html($tpl['arr']['c_fname']); ?>" />
							</span>
						</p>
						<p>
							<label class="title"><?php __('booking_c_lname'); ?></label>
							<span class="inline_block">
								<input type="text" name="c_lname" id="c_lname" class="pj-form-field w160" value="<?php echo pjSanitize::html($tpl['arr']['c_lname']); ?>" />
							</span>
						</p>
						<p>
							<label class="title"><?php __('booking_c_phone'); ?></label>
							<span class="pj-form-field-custom pj-form-field-custom-before">
								<span class="pj-form-field-before"><abbr class="pj-form-field-icon-phone"></abbr></span>
								<input type="text" name="c_phone" id="c_phone" class="pj-form-field w130" value="<?php echo pjSanitize::html($tpl['arr']['c_phone']); ?>" />
							</span>
						</p>
						<p>
							<label class="title"><?php __('booking_c_email'); ?></label>
							<span class="pj-form-field-custom pj-form-field-custom-before">
								<span class="pj-form-field-before"><abbr class="pj-form-field-icon-email"></abbr></span>
								<input type="text" name="c_email" id="c_email" class="pj-form-field required email w130" value="<?php echo pjSanitize::html($tpl['arr']['c_email']); ?>" />
							</span>
						</p>
						<p>
							<label class="title"><?php __('booking_c_company'); ?></label>
							<span class="inline_block">
								<input type="text" name="c_company" id="c_company" class="pj-form-field w160" value="<?php echo pjSanitize::html($tpl['arr']['c_company']); ?>" />
							</span>
						</p>
						<p>
							<label class="title"><?php __('booking_created'); ?></label>
							<span class="left"><?php echo !empty($tpl['arr']['created']) ?
								date($tpl['option_arr']['o_date_format'], strtotime($tpl['arr']['created'])) . ", " . date("H:i", strtotime($tpl['arr']['created'])) :
								'---'; ?></span>
						</p>
						<p>
							<label class="title"><?php __('booking_ip'); ?></label>
							<span class="left"><?php echo $tpl['arr']['ip']; ?></span>
						</p>
						<p>
							<label class="title">&nbsp;</label>
							<input type="submit" value="<?php __('btnSave'); ?>" class="pj-button" />
							<input type="button" value="<?php __('booking_confirmation'); ?>" class="pj-button btnConfirmation" style="padding: 4px 5px" />
						</p>
					</div>
					<div class="float_right w340">
						<p>
							<label class="title"><?php __('booking_c_address_1'); ?></label>
							<span class="inline_block">
								<input type="text" name="c_address_1" id="c_address_1" class="pj-form-field w160" value="<?php echo pjSanitize::html($tpl['arr']['c_address_1']); ?>" />
							</span>
						</p>
						<p>
							<label class="title"><?php __('booking_c_city'); ?></label>
							<span class="inline_block">
								<input type="text" name="c_city" id="c_city" class="pj-form-field w160" value="<?php echo pjSanitize::html($tpl['arr']['c_city']); ?>" />
							</span>
						</p>
						<p>
							<label class="title"><?php __('booking_c_state'); ?></label>
							<span class="inline_block">
								<input type="text" name="c_state" id="c_state" class="pj-form-field w160" value="<?php echo pjSanitize::html($tpl['arr']['c_state']); ?>" />
							</span>
						</p>
						<p>
							<label class="title"><?php __('booking_c_zip'); ?></label>
							<span class="inline_block">
								<input type="text" name="c_zip" id="c_zip" class="pj-form-field w160" value="<?php echo pjSanitize::html($tpl['arr']['c_zip']); ?>" />
							</span>
						</p>
						<p>
							<label class="title"><?php __('booking_c_country'); ?></label>
							<span class="inline_block">
								<select name="c_country" id="c_country" class="pj-form-field w160">
									<option value="">-- <?php __('lblChoose'); ?> --</option>
									<?php
									foreach ($tpl['country_arr'] as $item)
									{
										?><option value="<?php echo $item['id']; ?>"<?php echo $item['id'] != $tpl['arr']['c_country'] ? NULL : ' selected="selected"'; ?>><?php echo stripslashes($item['name']); ?></option><?php
									}
									?>
								</select>
							</span>
						</p>
						<p>
							<label class="block b5"><?php __('opt_o_bf_notes');?></label>
							<textarea name="c_notes" id="c_notes" class="pj-form-field w300 h100"><?php echo pjSanitize::html($tpl['arr']['c_notes']); ?></textarea>
						</p>
					</div>
					<br class="clear_both" />
				</fieldset>
			
			</div>
			<?php
			if (pjObject::getPlugin('pjInvoice') !== NULL)
			{
				?>
				<div id="tabs-3">
					<?php pjUtil::printNotice(@$titles['ABK13'], @$bodies['ABK13']); ?>
					<input type="button" value="<?php __('booking_balance_payment'); ?>" class="pj-button btnBalancePayment" style="display: <?php echo $balancePayment ? NULL : 'none'; ?>" />
					<input type="button" value="<?php __('lblReservationCreateInvoice'); ?>" class="pj-button btnAddInvoice" style="display: <?php echo !$balancePayment ? NULL : 'none'; ?>" />
					<div id="grid_invoices" class="t10 b10"></div>
				</div>
				<?php
			}
			?>
		</div>
	</form>
	
	<div id="dialogRoomEdit" style="display: none" title="<?php __('booking_room_edit'); ?>"></div>
	<div id="dialogRoomDelete" style="display: none" title="<?php __('booking_room_delete'); ?>"><?php __('delete_confirmation'); ?></div>
	<div id="dialogRoomAdd" style="display: none" title="<?php __('booking_room_add'); ?>"></div>
	<div id="dialogConfirmation" title="<?php __('booking_confirmation_title'); ?>" style="display: none"></div>
	<?php
	if (pjObject::getPlugin('pjInvoice') !== NULL)
	{
		$map = array(
			'confirmed' => 'paid',
			'pending' => 'not_paid',
			'cancelled' => 'cancelled'
		);
		
		$balanceSubtotal = $tpl['arr']['total'];
		$balanceTax = ($balanceSubtotal * $tpl['option_arr']['o_tax']) / 100;
		
		$balanceTotal = $balanceSubtotal + $balanceTax - $tpl['invoice_arr'][0]['total'];
		$balancePaidDeposit = $tpl['arr']['deposit'];
		$balanceAmountDue = $tpl['arr']['total'] + $tpl['arr']['security'] + $tpl['arr']['tax'] - $tpl['invoice_arr'][0]['total'] - $balancePaidDeposit;
		?>
		<form action="<?php echo PJ_INSTALL_URL; ?>index.php" method="get" target="_blank" style="display: inline" id="frmBalancePayment">
			<input type="hidden" name="controller" value="pjInvoice" />
			<input type="hidden" name="action" value="pjActionCreateInvoice" />
			<input type="hidden" name="tmp" value="<?php echo md5(uniqid(rand(), true)); ?>" />
			<input type="hidden" name="uuid" value="<?php echo pjUtil::uuid(); ?>" />
			<input type="hidden" name="order_id" value="<?php echo pjSanitize::html($tpl['arr']['uuid']); ?>" />
			<input type="hidden" name="issue_date" value="<?php echo date('Y-m-d'); ?>" />
			<input type="hidden" name="due_date" value="<?php echo date('Y-m-d'); ?>" />
			<input type="hidden" name="status" value="<?php echo @$map[$tpl['arr']['status']]; ?>" />
			<input type="hidden" name="subtotal" value="<?php echo number_format($balanceSubtotal, 2, '.', ''); ?>" />
			<input type="hidden" name="discount" value="0.00" />
			<input type="hidden" name="tax" value="<?php echo number_format($balanceTax, 2, '.', ''); ?>" />
			<input type="hidden" name="shipping" value="0.00" />
			<input type="hidden" name="total" value="<?php echo number_format($balanceTotal, 2, '.', ''); ?>" />
			<input type="hidden" name="paid_deposit" value="<?php echo number_format($balancePaidDeposit, 2, '.', ''); ?>" />
			<input type="hidden" name="amount_due" value="<?php echo number_format($balanceAmountDue, 2, '.', ''); ?>" />
			<input type="hidden" name="currency" value="<?php echo pjSanitize::html($tpl['option_arr']['o_currency']); ?>" />
			<input type="hidden" name="notes" value="<?php echo pjSanitize::html($tpl['arr']['c_notes']); ?>" />
			<input type="hidden" name="b_company" value="<?php echo pjSanitize::html($tpl['arr']['c_company']); ?>" />
			<input type="hidden" name="b_billing_address" value="<?php echo pjSanitize::html($tpl['arr']['c_address_1']); ?>" />
			<input type="hidden" name="b_name" value="<?php echo pjSanitize::html($tpl['arr']['c_fname'] . " " . $tpl['arr']['c_lname']); ?>" />
			<input type="hidden" name="b_address" value="<?php echo pjSanitize::html($tpl['arr']['c_address_2']); ?>" />
			<input type="hidden" name="b_street_address" value="<?php echo pjSanitize::html($tpl['arr']['c_address_3']); ?>" />
			<input type="hidden" name="b_city" value="<?php echo pjSanitize::html($tpl['arr']['c_city']); ?>" />
			<input type="hidden" name="b_state" value="<?php echo pjSanitize::html($tpl['arr']['c_state']); ?>" />
			<input type="hidden" name="b_zip" value="<?php echo pjSanitize::html($tpl['arr']['c_zip']); ?>" />
			<input type="hidden" name="b_phone" value="<?php echo pjSanitize::html($tpl['arr']['c_phone']); ?>" />
			<input type="hidden" name="b_email" value="<?php echo pjSanitize::html($tpl['arr']['c_email']); ?>" />
			<input type="hidden" name="items[0][name]" value="<?php echo __('lblBookingDeposit', true);?>" />
			<input type="hidden" name="items[0][description]" value="<?php echo sprintf("%s - %s",
								pjUtil::formatDate($tpl['arr']['date_from'], 'Y-m-d', $tpl['option_arr']['o_date_format']),
								pjUtil::formatDate($tpl['arr']['date_to'], 'Y-m-d', $tpl['option_arr']['o_date_format'])); ?>" />
			<input type="hidden" name="items[0][qty]" value="1" />
			<input type="hidden" name="items[0][unit_price]" value="<?php echo number_format($balanceTotal, 2, '.', ''); ?>" />
			<input type="hidden" name="items[0][amount]" value="<?php echo number_format($balanceTotal, 2, '.', ''); ?>" />
		</form>
			
		<form action="<?php echo PJ_INSTALL_URL; ?>index.php" method="get" target="_blank" style="display: inline" id="frmAddInvoice">
			<input type="hidden" name="controller" value="pjInvoice" />
			<input type="hidden" name="action" value="pjActionCreateInvoice" />
			<input type="hidden" name="tmp" value="<?php echo md5(uniqid(rand(), true)); ?>" />
			<input type="hidden" name="uuid" value="<?php echo pjUtil::uuid(); ?>" />
			<input type="hidden" name="order_id" value="<?php echo pjSanitize::html($tpl['arr']['uuid']); ?>" />
			<input type="hidden" name="issue_date" value="<?php echo date('Y-m-d'); ?>" />
			<input type="hidden" name="due_date" value="<?php echo date('Y-m-d'); ?>" />
			<input type="hidden" name="status" value="<?php echo @$map[$tpl['arr']['status']]; ?>" />
			<input type="hidden" name="subtotal" value="0.00" />
			<input type="hidden" name="discount" value="0.00" />
			<input type="hidden" name="tax" value="0.00" />
			<input type="hidden" name="shipping" value="0.00" />
			<input type="hidden" name="total" value="0.00" />
			<input type="hidden" name="paid_deposit" value="0.00" />
			<input type="hidden" name="amount_due" value="0.00" />
			<input type="hidden" name="currency" value="<?php echo pjSanitize::html($tpl['option_arr']['o_currency']); ?>" />
			<input type="hidden" name="notes" value="<?php echo pjSanitize::html($tpl['arr']['c_notes']); ?>" />
			<input type="hidden" name="b_company" value="<?php echo pjSanitize::html($tpl['arr']['c_company']); ?>" />
			<input type="hidden" name="b_billing_address" value="<?php echo pjSanitize::html($tpl['arr']['c_address_1']); ?>" />
			<input type="hidden" name="b_name" value="<?php echo pjSanitize::html($tpl['arr']['c_fname'] . " " . $tpl['arr']['c_lname']); ?>" />
			<input type="hidden" name="b_address" value="<?php echo pjSanitize::html($tpl['arr']['c_address_2']); ?>" />
			<input type="hidden" name="b_street_address" value="<?php echo pjSanitize::html($tpl['arr']['c_address_3']); ?>" />
			<input type="hidden" name="b_city" value="<?php echo pjSanitize::html($tpl['arr']['c_city']); ?>" />
			<input type="hidden" name="b_state" value="<?php echo pjSanitize::html($tpl['arr']['c_state']); ?>" />
			<input type="hidden" name="b_zip" value="<?php echo pjSanitize::html($tpl['arr']['c_zip']); ?>" />
			<input type="hidden" name="b_phone" value="<?php echo pjSanitize::html($tpl['arr']['c_phone']); ?>" />
			<input type="hidden" name="b_email" value="<?php echo pjSanitize::html($tpl['arr']['c_email']); ?>" />
			<input type="hidden" name="items[0][name]" value="<?php echo __('lblBookingDeposit', true);?>" />
			<input type="hidden" name="items[0][description]" value="<?php echo sprintf("%s - %s",
								pjUtil::formatDate($tpl['arr']['date_from'], 'Y-m-d', $tpl['option_arr']['o_date_format']),
								pjUtil::formatDate($tpl['arr']['date_to'], 'Y-m-d', $tpl['option_arr']['o_date_format'])); ?>" />
			<input type="hidden" name="items[0][qty]" value="1" />
			<input type="hidden" name="items[0][unit_price]" value="0.00" />
			<input type="hidden" name="items[0][amount]" value="0.00" />
		</form>
		<?php
	}
	?>
	<script type="text/javascript">
	var pjGrid = pjGrid || {};
	pjGrid.jqDateFormat = "<?php echo pjUtil::jqDateFormat($tpl['option_arr']['o_date_format']); ?>";
	pjGrid.jsDateFormat = "<?php echo pjUtil::jsDateFormat($tpl['option_arr']['o_date_format']); ?>";
	var myLabel = myLabel || {};
	myLabel.num = "<?php __('plugin_invoice_i_num'); ?>";
	myLabel.order_id = "<?php __('plugin_invoice_i_order_id'); ?>";
	myLabel.issue_date = "<?php __('plugin_invoice_i_issue_date'); ?>";
	myLabel.due_date = "<?php __('plugin_invoice_i_due_date'); ?>";
	myLabel.created = "<?php __('plugin_invoice_i_created'); ?>";
	myLabel.status = "<?php __('plugin_invoice_i_status'); ?>";
	myLabel.total = "<?php __('plugin_invoice_i_total'); ?>";
	myLabel.delete_title = "<?php __('plugin_invoice_i_delete_title'); ?>";
	myLabel.delete_body = "<?php __('plugin_invoice_i_delete_body'); ?>";
	myLabel.paid = "<?php echo $statuses['paid']; ?>";
	myLabel.not_paid = "<?php echo $statuses['not_paid']; ?>";
	myLabel.cancelled = "<?php echo $statuses['cancelled']; ?>";
	myLabel.btnContinue = "<?php __('btnContinue'); ?>";
	myLabel.btnCancel = "<?php __('btnCancel'); ?>";
	myLabel.invoice_total = <?php echo isset($tpl['invoice_arr']) && count($tpl['invoice_arr']) === 1 ? (float) $tpl['invoice_arr'][0]['total'] : 0; ?>;
	myLabel.empty_date = "<?php __('gridEmptyDate'); ?>";
	myLabel.invalid_date = "<?php __('gridInvalidDate'); ?>";
	myLabel.empty_datetime = "<?php __('gridEmptyDatetime'); ?>";
	myLabel.invalid_datetime = "<?php __('gridInvalidDatetime'); ?>";
	</script>
	<?php
}
?>