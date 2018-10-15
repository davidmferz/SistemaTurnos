<div class="panel panel-default clearfix pjHbPanel">
	<?php include dirname(__FILE__) . '/elements/head.php'; ?>

	<div class="panel-body pjHbPanelBody pjHbPanelBooking">
	<?php
	if (isset($tpl['status']) && $tpl['status'] == 'OK')
	{
		$STORE = @$_SESSION[$controller->defaultStore];
		$VOUCHER = @$_SESSION[$controller->defaultVoucher];
		$session_prices = $tpl['session_prices'];
		?>
		<h3 class="pjHbPanelBookingTitle"><?php __('front_your_booking'); ?></h3>

		<div class="row">
			<div class="col-xs-6">&nbsp;</div><!-- /.col-md-6 -->

			<div class="col-xs-6 text-right" style="white-space: nowrap;">
				<a href="#" class="hbSelectorSearch"><?php __('front_change_dates'); ?></a>
			</div><!-- /.col-md-6 -->
			<?php 
			if (isset($STORE['date_from']) && isset($STORE['date_to']))
			{
				?>
				<div class="col-xs-6" style="white-space: nowrap;">
				<?php __('front_check_in'); ?>:
				</div><!-- /.col-md-6 -->

				<div class="col-xs-6 text-right pjHbPanelBookingMeta" style="white-space: nowrap;">
				<?php echo pjUtil::formatDate($STORE['date_from'], 'Y-m-d', $tpl['option_arr']['o_date_format']); ?>
				</div><!-- /.col-md-6 -->

				<div class="col-xs-6" style="white-space: nowrap;">
				<?php __('front_check_out'); ?>:
				</div><!-- /.col-md-6 -->

				<div class="col-xs-6 text-right pjHbPanelBookingMeta" style="white-space: nowrap;">
				<?php echo pjUtil::formatDate($STORE['date_to'], 'Y-m-d', $tpl['option_arr']['o_date_format']); ?>
				</div><!-- /.col-md-6 -->

				<div class="col-xs-6">
				<?php __('front_for'); ?>:
				</div><!-- /.col-md-6 -->

				<div class="col-xs-6 text-right pjHbPanelBookingMeta" style="white-space: nowrap;">
				<?php
				printf("%u %s, %u %s",
					$STORE['_rooms'],
					(int) $STORE['_rooms'] === 1 ? __('front_room_singular', true) : __('front_room_plural', true),
					$STORE['_nights'],
					(int) $STORE['_nights'] === 1 ? __('front_night_singular', true) : __('front_night_plural', true)
				);
				?>
				</div><!-- /.col-md-6 -->
				<?php 
			}
			?>
		</div><!-- /.row -->
		<?php
		if (isset($STORE['content']))
		{
			foreach ($STORE['content'] as $room_id => $item)
			{
				?>
				<hr>
				<div class="row">
					<div class="col-sm-6 col-xs-12"><strong><?php echo pjSanitize::html($tpl['room_arr'][$room_id]['name']); ?></strong></div><!-- /.col-md-6 -->
					<div class="col-sm-6 col-xs-12 text-right"><a href="#" class="hbSelectorRooms"><?php __('front_change_rooms'); ?></a></div><!-- /.col-sm-6 -->
				</div><!-- /.row -->
				<?php
				foreach ($item as $index => $info)
				{
					?>
					<hr>
					<div class="row">
						<div class="col-sm-6 col-xs-12">
						<?php printf("%u %s, %u %s",
							$info['adults'],
							$info['adults'] != 1 ? pjMultibyte::strtolower(__('front_adults', true)) : pjMultibyte::strtolower(__('front_adult', true)),
							$info['children'],
							$info['children'] != 1 ? pjMultibyte::strtolower(__('front_children', true)) : pjMultibyte::strtolower(__('front_child', true))
						); ?>
						</div><!-- /.col-sm-6 col-xs-12 -->
		
						<div class="col-sm-6 col-xs-12 text-right pjHbPanelBookingMeta">
						<?php echo
						isset($session_prices['rooms_price_stack'][$room_id][$index]) ?
							pjUtil::formatCurrencySign(number_format($session_prices['rooms_price_stack'][$room_id][$index], 2), $tpl['option_arr']['o_currency']) :
							$info['price'];
						?>
						</div><!-- /.col-sm-6 col-xs-12 -->
					</div><!-- /.row -->					
					<?php
				}
			}
		}
	} elseif (isset($tpl['status']) && $tpl['status'] == 'ERR') {
		?>
		<div class="alert alert-danger" role="alert"><?php echo pjSanitize::html($tpl['text']); ?></div>
		<button type="button" class="btn btn-default hbSelectorRooms" role="button"><?php __('front_btn_back'); ?></button>
		<?php
	}
	?>
	</div><!-- /.panel-body pjHbPanelBody pjHbPanelBooking -->
</div><!-- /.panel pjHbPanel -->

<?php
if (isset($tpl['status']) && $tpl['status'] == 'OK')
{
	?>
	<div class="row pjHbExtras">
		<div class="col-md-4 col-sm-12 col-xs-12">
			<br>
			<br>
			<br>

			<p><?php __('front_extras_text'); ?></p>
		</div><!-- /.col-md-4 col-sm-12 col-xs-12 -->

		<div class="col-md-8 col-sm-12 col-xs-12">
			<?php if (!empty($tpl['extra_arr'])) : ?>
			<h3 class="pjHbExtrasTitle"><?php __('front_extras'); ?></h3>
			<?php endif; ?>

			<div class="table-responsive">
				<table class="table">
					<tbody>
					<?php
					$extraCost = 0;
					$extra_per = __('extra_per', true);
					foreach ($tpl['extra_arr'] as $extra)
					{
						?>
						<tr>
							<td><?php echo $extra['name']; ?> (<?php echo @$extra_per[$extra['per']]; ?>)</td>
							<td>
							<?php
							if (!isset($STORE['extras']) || !array_key_exists($extra['id'], $STORE['extras']))
							{
								?><input type="checkbox" name="extra[]" value="<?php echo $extra['id']; ?>" class="hbSelectorExtra" /><?php
							} else {
								switch ($extra['per'])
								{
									case 'day':
										$extraCost += $extra['price'] * $STORE['_nights'];
										break;
									case 'booking':
										$extraCost += $extra['price'];
										break;
									case 'person':
										$extraCost += $extra['price'] * $STORE['_persons'];
										break;
									case 'day_person':
										$extraCost += $extra['price'] * $STORE['_nights'] * $STORE['_persons'];
										break;
								}
								?><input type="checkbox" name="extra[]" value="<?php echo $extra['id']; ?>" checked="checked" class="hbSelectorExtra" /><?php
							}
							?>
							</td>
							<td class="text-right pjHbExtraPrice"><?php echo pjUtil::formatCurrencySign(number_format($extra['price'], 2), $tpl['option_arr']['o_currency']); ?></td>
						</tr>
						<?php 
					}
					?>
						<tr>
							<td><?php __('front_room_price'); ?></td>
							<td></td>
							<td class="text-right pjHbExtraPrice"><?php
								$rentalPrice = $session_prices['room_price'];
								echo pjUtil::formatCurrencySign(number_format(floatval($rentalPrice), 2), $tpl['option_arr']['o_currency']); ?></td>
						</tr>
						<?php if (!empty($tpl['extra_arr'])) : ?>
						<tr>
							<td><?php __('front_extras_price'); ?></td>
							<td></td>
							<td class="text-right pjHbExtraPrice"><?php echo pjUtil::formatCurrencySign(number_format(floatval($extraCost), 2), $tpl['option_arr']['o_currency']); ?></td>
						</tr>
						<?php endif; ?>
					</tbody>
				</table><!-- /.table -->
			</div><!-- /.table-responsive -->
			<?php
			if (isset($VOUCHER) && !empty($VOUCHER))
			{
				?>
				<div class="table-responsive">
					<table class="table">
						<tbody>
							<tr>
								<td><?php __('front_discount'); ?>
									<?php /* echo $VOUCHER['voucher_type'] == 'percent' ? sprintf(" (%s%%)", $VOUCHER['voucher_discount']) : NULL; */ ?>
									<a href="#" class="btn btn-default btn-xs hbSelectorRemoveCode"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a>
								</td>
								<td class="text-right pjHbExtraPrice"><?php echo pjUtil::formatCurrencySign(number_format($session_prices['discount'], 2), $tpl['option_arr']['o_currency']); ?></td>
							</tr>
						</tbody>
					</table><!-- /.table -->
				</div><!-- /.table-responsive -->
				<?php
			} else {
				?>
				<div class="row">
					<div class="col-sm-6 col-sm-offset-6 col-xs-12">
						<form action="" method="post" class="hbSelectorVoucherForm">
							<input type="hidden" name="hb_voucher" value="1" />
							<div class="row">
								<div class="col-sm-6">
									<div class="form-group">
										<input type="text" class="form-control" name="code" autocomplete="off" maxlength="50" data-msg-required="<?php __('front_validate_promo', false, true); ?>">
									</div><!-- /.form-group -->
								</div><!-- /.col-sm-6 -->
								<div class="col-sm-6">
									<div class="form-group">
										<button type="submit" class="btn btn-block btn-default"><?php __('front_btn_apply'); ?></button>
									</div><!-- /.form-group -->
								</div><!-- /.col-sm-6 -->
								<div class="col-sm-12 col-xs-12 hbSelectorVoucherError" style="display:none">
									<div role="alert" class="alert alert-danger"><?php __('front_voucher_error'); ?></div>
								</div>
							</div><!-- /.row -->
						</form>
					</div><!-- /.col-sm-6 -->
				</div><!-- /.row -->
				<br>
				<?php 
			}
			?>
			<div class="table-responsive">
				<table class="table">
					<tbody>
						<?php 
						if ($session_prices['tax'] > 0)
						{
							?>
							<tr>
								<td><?php __('front_tax'); ?></td>
								<td class="text-right pjHbExtraPrice"><?php echo pjUtil::formatCurrencySign(number_format(floatval($session_prices['tax']), 2), $tpl['option_arr']['o_currency']); ?></td>
							</tr>
							<?php
						}
						?>
						<tr>
							<td><?php __('front_total'); ?></td>
							<td class="text-right pjHbExtraPrice"><strong><?php echo pjUtil::formatCurrencySign(number_format($session_prices['total'], 2), $tpl['option_arr']['o_currency']); ?></strong></td>
						</tr>
						<?php
						if ($session_prices['security'] > 0)
						{
							?>
							<tr>
								<td><?php __('front_security'); ?></td>
								<td class="text-right pjHbExtraPrice"><?php echo pjUtil::formatCurrencySign(number_format(floatval($session_prices['security']), 2), $tpl['option_arr']['o_currency']); ?></td>
							</tr>
							<?php
						}
						?>
						<tr>
							<td><?php __('front_deposit'); ?></td>
							<td class="text-right pjHbExtraPrice">
								<?php echo pjUtil::formatCurrencySign(number_format(floatval($session_prices['deposit']), 2), $tpl['option_arr']['o_currency']); ?>
							</td>
						</tr>
					</tbody>
				</table><!-- /.table -->
			</div><!-- /.table-responsive -->
			<?php
			if (isset($STORE['step_search']) && isset($STORE['step_rooms']))
			{
				?>
				<div class="row">
					<div class="col-xs-6">
						<button type="button" role="button" class="btn btn-default hbSelectorRooms"><?php __('front_btn_back'); ?></button>
					</div><!-- /.col-xs-6 -->
					<div class="col-xs-6 text-right">
						<button type="button" role="button" class="btn btn-default hbSelectorCheckout"><?php __('front_btn_checkout'); ?></button>
					</div><!-- /.col-xs-6 -->
				</div><!-- /.row -->
				<?php 
			}
			?>
		</div><!-- /.col-md-8 -->
	</div><!-- /.row -->
	<?php 
}
?>