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
	$week_start = isset($tpl['option_arr']['o_week_start']) && in_array((int) $tpl['option_arr']['o_week_start'], range(0,6)) ? (int) $tpl['option_arr']['o_week_start'] : 0;
	$jqDateFormat = pjUtil::jqDateFormat($tpl['option_arr']['o_date_format']);
	$months = __('months', true);
	$short_months = __('short_months', true);
	ksort($months);
	ksort($short_months);
	$days = __('days', true);
	$short_days = __('short_days', true);
	$hash = md5(uniqid(rand(), true));
	?>
	<div class="ui-tabs ui-widget ui-widget-content ui-corner-all b10">
		<ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">
			<li class="ui-state-default ui-corner-top"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBookings&amp;action=pjActionCalendar"><?php __('menuCalendar'); ?></a></li>
			<li class="ui-state-default ui-corner-top"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBookings&amp;action=pjActionIndex"><?php __('menuBookings'); ?></a></li>
			<li class="ui-state-default ui-corner-top ui-tabs-active ui-state-active"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBookings&amp;action=pjActionCreate"><?php __('booking_add'); ?></a></li>
			<li class="ui-state-default ui-corner-top"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjInvoice&amp;action=pjActionInvoices"><?php __('plugin_invoice_menu_invoices'); ?></a></li>
		</ul>
	</div>
	
	<form action="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBookings&amp;action=pjActionCreate" method="post" id="frmCreateBooking" class="form pj-form">
		<input type="hidden" name="booking_create" value="1" />
		<input type="hidden" name="hash" value="<?php echo $hash; ?>" />
		<div id="tabs">
			<ul>
				<li><a href="#tabs-1"><?php __('booking_tab_details'); ?></a></li>
				<li><a href="#tabs-2"><?php __('booking_tab_client'); ?></a></li>
			</ul>
		
			<div id="tabs-1">
				<?php pjUtil::printNotice(@$titles['ABK14'], @$bodies['ABK14']); ?>
								
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
									?><option value="<?php echo $k; ?>"><?php echo $v; ?></option><?php
								}
								?>
							</select>
						</span>
					</p>
					<p class="hbCC" style="display: none">
						<label class="title"><?php __('booking_cc_type'); ?></label>
						<span class="inline_block">
							<select name="cc_type" class="pj-form-field w140">
								<option value="">---</option>
								<?php
								foreach (__('booking_cc_types', true) as $k => $v)
								{
									?><option value="<?php echo $k; ?>"><?php echo $v; ?></option><?php
								}
								?>
							</select>
						</span>
					</p>
					<p class="hbCC" style="display: none">
						<label class="title"><?php __('booking_cc_num'); ?></label>
						<span class="inline_block">
							<input type="text" name="cc_num" id="cc_num" class="pj-form-field w120 digits" />
						</span>
					</p>
					<p class="hbCC" style="display: none">
						<label class="title"><?php __('booking_cc_code'); ?></label>
						<span class="inline_block">
							<input type="text" name="cc_code" id="cc_code" class="pj-form-field w120 digits" />
						</span>
					</p>
					<p class="hbCC" style="display: none">
						<label class="title"><?php __('booking_cc_exp'); ?></label>
						<span class="inline_block">
							<?php
							echo pjTime::factory()
								->attr('name', 'cc_exp_month')
								->attr('id', 'cc_exp_month')
								->attr('class', 'pj-form-field')
								->prop('format', 'M')
								->month();
							?>
							<?php
							echo pjTime::factory()
								->attr('name', 'cc_exp_year')
								->attr('id', 'cc_exp_year')
								->attr('class', 'pj-form-field')
								->prop('left', 0)
								->prop('right', 10)
								->year();
							?>
						</span>
					</p>
					<p>
						<label class="title"><?php __('booking_room_price'); ?></label>
						<span class="pj-form-field-custom pj-form-field-custom-before">
							<span class="pj-form-field-before"><abbr class="pj-form-field-icon-text"><?php echo pjUtil::formatCurrencySign(NULL, $tpl['option_arr']['o_currency'], ""); ?></abbr></span>
							<input type="text" name="room_price" id="room_price" class="pj-form-field align_right w70 number" />
						</span>
					</p>
					<p>
						<label class="title"><?php __('booking_extra_price'); ?></label>
						<span class="pj-form-field-custom pj-form-field-custom-before">
							<span class="pj-form-field-before"><abbr class="pj-form-field-icon-text"><?php echo pjUtil::formatCurrencySign(NULL, $tpl['option_arr']['o_currency'], ""); ?></abbr></span>
							<input type="text" name="extra_price" id="extra_price" class="pj-form-field align_right w70 number" />
						</span>
					</p>
					<p>
						<label class="title"><?php __('booking_total'); ?></label>
						<span class="pj-form-field-custom pj-form-field-custom-before">
							<span class="pj-form-field-before"><abbr class="pj-form-field-icon-text"><?php echo pjUtil::formatCurrencySign(NULL, $tpl['option_arr']['o_currency'], ""); ?></abbr></span>
							<input type="text" name="total" id="total" class="pj-form-field align_right w70 number" />
						</span>
					</p>
					<p>
						<label class="title"><?php __('booking_tax'); ?></label>
						<span class="pj-form-field-custom pj-form-field-custom-before">
							<span class="pj-form-field-before"><abbr class="pj-form-field-icon-text"><?php echo pjUtil::formatCurrencySign(NULL, $tpl['option_arr']['o_currency'], ""); ?></abbr></span>
							<input type="text" name="tax" id="tax" class="pj-form-field align_right w70 number" />
						</span>
					</p>
					<p>
						<label class="title"><?php __('booking_security'); ?></label>
						<span class="pj-form-field-custom pj-form-field-custom-before">
							<span class="pj-form-field-before"><abbr class="pj-form-field-icon-text"><?php echo pjUtil::formatCurrencySign(NULL, $tpl['option_arr']['o_currency'], ""); ?></abbr></span>
							<input type="text" name="security" id="security" class="pj-form-field align_right w70 number" />
						</span>
					</p>
					<p>
						<label class="title"><?php __('booking_deposit'); ?></label>
						<span class="pj-form-field-custom pj-form-field-custom-before">
							<span class="pj-form-field-before"><abbr class="pj-form-field-icon-text"><?php echo pjUtil::formatCurrencySign(NULL, $tpl['option_arr']['o_currency'], ""); ?></abbr></span>
							<input type="text" name="deposit" id="deposit" class="pj-form-field align_right w70 number" />
						</span>
					</p>
					<p>
						<label class="title"><?php __('booking_voucher'); ?></label>
						<span class="inline_block">
							<input type="text" name="voucher" id="voucher" class="pj-form-field w100" />
						</span>
					</p>
					<p>
						<label class="title"><?php __('booking_discount'); ?></label>
						<span class="pj-form-field-custom pj-form-field-custom-before">
							<span class="pj-form-field-before"><abbr class="pj-form-field-icon-text"><?php echo pjUtil::formatCurrencySign(NULL, $tpl['option_arr']['o_currency'], ""); ?></abbr></span>
							<input type="text" name="discount" id="discount" class="pj-form-field align_right w70 number" />
						</span>
					</p>
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
						<span class="left"><a href="<?php echo $_SERVER['PHP_SELF']; ?>" class="client-details"><?php __('booking_client_add'); ?></a></span>
					</p>
					<p>
						<label class="title"><?php __('booking_status'); ?></label>
						<span class="inline_block">
							<select name="status" id="status" class="pj-form-field w150 required float_left"  data-msg-required="<?php __('lblFieldRequired');?>">
								<option value="">-- <?php __('lblChoose'); ?>--</option>
								<?php
								foreach (__('booking_statuses', true) as $k => $v)
								{
									?><option value="<?php echo $k; ?>"><?php echo $v; ?></option><?php
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
								<input type="text" name="date_from" id="date_from" class="pj-form-field pointer w80 datepick required" data-based="<?php echo $tpl['option_arr']['o_price_based_on'];?>" data-msg-required="<?php __('lblFieldRequired');?>" readonly="readonly" rel="<?php echo $week_start; ?>" rev="<?php echo $jqDateFormat; ?>" value="<?php echo !isset($_GET['date_from']) ? NULL : pjUtil::formatDate($_GET['date_from'], 'Y-m-d', $tpl['option_arr']['o_date_format']); ?>" data-months="<?php echo join(',', $months);?>" data-shortmonths="<?php echo join(',', $short_months);?>" data-day="<?php echo join(',', $days);?>" data-daymin="<?php echo join(',', $short_days);?>"/>
								<span class="pj-form-field-after"><abbr class="pj-form-field-icon-date"></abbr></span>
							</span>
							<span class="inline_block l5">
							<?php
							echo pjTime::factory()
								->attr('name', 'hour')
								->attr('id', 'hour')
								->attr('class', 'pj-form-field')
								->hour();
							?>
							<?php
							echo pjTime::factory()
								->attr('name', 'minute')
								->attr('id', 'minute')
								->attr('class', 'pj-form-field')
								->prop('step', 1)
								->minute();
							?>
							</span>
						</span>
					</p>
					<p>
						<label class="title"><?php __('booking_departure_date'); ?></label>
						<span class="pj-form-field-custom pj-form-field-custom-after">
							<input type="text" name="date_to" id="date_to" class="pj-form-field pointer w80 datepick required"  data-msg-required="<?php __('lblFieldRequired');?>" readonly="readonly" rel="<?php echo $week_start; ?>" rev="<?php echo $jqDateFormat; ?>" data-months="<?php echo join(',', $months);?>" data-shortmonths="<?php echo join(',', $short_months);?>" data-day="<?php echo join(',', $days);?>" data-daymin="<?php echo join(',', $short_days);?>"/>
							<span class="pj-form-field-after"><abbr class="pj-form-field-icon-date"></abbr></span>
						</span>
					</p>
					<p>
						<label class="title"><?php __('booking_uuid'); ?></label>
						<span class="inline_block">
							<input type="text" name="uuid" id="uuid" class="pj-form-field w150 required" value="<?php echo pjUtil::uuid(); ?>"  data-msg-required="<?php __('lblFieldRequired');?>"/>
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
									<td><?php echo pjSanitize::html($extra['name']); ?> / <?php echo pjUtil::formatCurrencySign(number_format($extra['price'], 2), $tpl['option_arr']['o_currency']); ?> - <?php echo @$per[$extra['per']]; ?></td>
									<td class="align_center"><input type="checkbox" class="align_middle" name="extra_id[<?php echo $extra['id']; ?>]" value="<?php echo $extra['per']; ?>|<?php echo $extra['price']; ?>" /></td>
								</td>
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
				<?php pjUtil::printNotice(@$titles['ABK15'], @$bodies['ABK15']); ?>
		
				<fieldset class="fieldset white">
					<legend><?php __('booking_client_details'); ?></legend>
					<div class="overflow">
						<p>
							<label class="title"><?php __('booking_existing_client'); ?></label>
							<span class="inline_block">
								<input type="text" name="existing_search" id="existing_search" class="pj-form-field w400" placeholder="<?php __('booking_search_hint');?>"/>
							</span>
						</p>
					</div>
					<div class="float_left w340">
						<p>
							<label class="title"><?php __('booking_c_title'); ?></label>
							<span class="inline_block">
								<select name="c_title" id="c_title" class="pj-form-field w100">
								<?php
								foreach (__('personal_titles', true) as $k => $v)
								{
									?><option value="<?php echo $k; ?>"><?php echo stripslashes($v); ?></option><?php
								}
								?>
								</select>
							</span>
						</p>
						<p>
							<label class="title"><?php __('booking_c_fname'); ?></label>
							<span class="inline_block">
								<input type="text" name="c_fname" id="c_fname" class="pj-form-field w160" />
							</span>
						</p>
						<p>
							<label class="title"><?php __('booking_c_lname'); ?></label>
							<span class="inline_block">
								<input type="text" name="c_lname" id="c_lname" class="pj-form-field w160" />
							</span>
						</p>
						<p>
							<label class="title"><?php __('booking_c_phone'); ?></label>
							<span class="pj-form-field-custom pj-form-field-custom-before">
								<span class="pj-form-field-before"><abbr class="pj-form-field-icon-phone"></abbr></span>
								<input type="text" name="c_phone" id="c_phone" class="pj-form-field w130" />
							</span>
						</p>
						<p>
							<label class="title"><?php __('booking_c_email'); ?></label>
							<span class="pj-form-field-custom pj-form-field-custom-before">
								<span class="pj-form-field-before"><abbr class="pj-form-field-icon-email"></abbr></span>
								<input type="text" name="c_email" id="c_email" class="pj-form-field required email w130" data-msg-required="<?php __('lblFieldRequired');?>" data-msg-email="<?php __('lblEmailInvalid');?>"/>
							</span>
						</p>
						<p>
							<label class="title"><?php __('booking_c_company'); ?></label>
							<span class="inline_block">
								<input type="text" name="c_company" id="c_company" class="pj-form-field w160" />
							</span>
						</p>
						<p>
							<label class="title">&nbsp;</label>
							<input type="submit" value="<?php __('btnSave'); ?>" class="pj-button" />
						</p>
					</div>
					<div class="float_right w340">
						<p>
							<label class="title"><?php __('booking_c_address_1'); ?></label>
							<span class="inline_block">
								<input type="text" name="c_address_1" id="c_address_1" class="pj-form-field w160" />
							</span>
						</p>
						<p>
							<label class="title"><?php __('booking_c_city'); ?></label>
							<span class="inline_block">
								<input type="text" name="c_city" id="c_city" class="pj-form-field w160" />
							</span>
						</p>
						<p>
							<label class="title"><?php __('booking_c_state'); ?></label>
							<span class="inline_block">
								<input type="text" name="c_state" id="c_state" class="pj-form-field w160" />
							</span>
						</p>
						<p>
							<label class="title"><?php __('booking_c_zip'); ?></label>
							<span class="inline_block">
								<input type="text" name="c_zip" id="c_zip" class="pj-form-field w160" />
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
										?><option value="<?php echo $item['id']; ?>"><?php echo stripslashes($item['name']); ?></option><?php
									}
									?>
								</select>
							</span>
						</p>
						<p>
							<label class="block b5"><?php __('opt_o_bf_notes');?></label>
							<textarea name="c_notes" id="c_notes" class="pj-form-field w300 h100"></textarea>
						</p>
					</div>
					<br class="clear_both" />
					
				</fieldset>
		
			</div>
		</div>
		
	</form>
	
	<div id="dialogRoomEdit" style="display: none" title="<?php __('booking_room_edit'); ?>"></div>
	<div id="dialogRoomDelete" style="display: none" title="<?php __('booking_room_delete'); ?>"><?php __('delete_confirmation'); ?></div>
	<div id="dialogRoomAdd" style="display: none" title="<?php __('booking_room_add'); ?>"></div>
	<?php
}
?>