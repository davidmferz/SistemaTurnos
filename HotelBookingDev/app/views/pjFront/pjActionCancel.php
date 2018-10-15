<!doctype html>
<html>
	<head>
		<title><?php __('cancel_title'); ?></title>
		<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
		<?php
		foreach ($controller->getCss() as $css)
		{
			echo '<link type="text/css" rel="stylesheet" href="'.(isset($css['remote']) && $css['remote'] ? NULL : PJ_INSTALL_URL).$css['path'].htmlspecialchars($css['file']).'" />';
		}
		$cid = isset($_GET['cid']) && (int) $_GET['cid'] > 0 ? (int) $_GET['cid'] : 1;
		?>
	</head>
	<body>
		<div id="hbContainer_<?php echo $cid; ?>" class="hbContainer">
			<div class="hbContainerInner">
			<?php
			$cancel_err = __('cancel_err', true);
			if (isset($tpl['status']))
			{
				?><div class="hbNotice"><?php
				switch ($tpl['status'])
				{
					case 1:
						echo $cancel_err[1];
						break;
					case 2:
						echo $cancel_err[2];
						break;
					case 3:
						echo $cancel_err[3];
						break;
					case 4:
						echo $cancel_err[4];
						break;
				}
				?></div><?php
			} else {

				if (isset($_GET['err']))
				{
					?><div class="hbNotice"><?php
					switch ((int) $_GET['err'])
					{
						case 5:
							echo $cancel_err[5];
							break;
					}
					?></div><?php
				}

				if (isset($tpl['arr']))
				{
					?>
					<div class="hbFormBox">
						<div class="hbFormBoxTitle"><?php __('front_booking_details'); ?></div>
						<div class="hbFormBoxContent">
						
							<table cellpadding="0" cellspacing="0" style="width: 100%" class="hbTable">
								<tbody>
									<?php
									if (isset($tpl['arr']['date_from']) && isset($tpl['arr']['date_to']))
									{
										?>
										<tr>
											<td style="width: 50%"><?php __('front_check_in'); ?>:</td>
											<td class="hbAlignRight"><?php echo pjUtil::formatDate($tpl['arr']['date_from'], 'Y-m-d', $tpl['option_arr']['o_date_format']); ?></td>
										</tr>
										<tr>
											<td><?php __('front_check_out'); ?>:</td>
											<td class="hbAlignRight"><?php echo pjUtil::formatDate($tpl['arr']['date_to'], 'Y-m-d', $tpl['option_arr']['o_date_format']); ?></td>
										</tr>
										<tr>
											<td class="hbBorderBottom"><?php __('front_for'); ?>:</td>
											<td class="hbBorderBottom hbAlignRight"><?php
											printf("%u %s, %u %s",
												$tpl['arr']['_rooms'],
												(int) $tpl['arr']['_rooms'] === 1 ? __('front_room_singular', true) : __('front_room_plural', true),
												$tpl['arr']['_nights'],
												(int) $tpl['arr']['_nights'] === 1 ? __('front_night_singular', true) : __('front_night_plural', true)
											);
											?></td>
										</tr>
										<?php
									}
									?>
								</tbody>
							</table>
						
							<?php
							if (isset($tpl['arr']['room_arr']) && !empty($tpl['arr']['room_arr']))
							{
								foreach ($tpl['arr']['room_arr'] as $item)
								{
									?>
									<table cellpadding="0" cellspacing="0" style="width: 100%" class="hbTable">
										<thead>
											<tr>
												<th class="hbBold" style="width: 50%"><?php echo pjSanitize::html($item[0]['name']); ?></th>
											</tr>
										</thead>
										<tbody>
										<?php
										foreach ($item as $index => $info)
										{
											?>
											<tr>
												<td style="width: 50%"><?php printf("%u %s, %u %s",
													$info['adults'],
													$info['adults'] != 1 ? pjMultibyte::strtolower(__('front_adults', true)) : pjMultibyte::strtolower(__('front_adult', true)),
													$info['children'],
													$info['children'] != 1 ? pjMultibyte::strtolower(__('front_children', true)) : pjMultibyte::strtolower(__('front_child', true))
												); ?></td>
											</tr>
											<?php
										}
										?>
										</tbody>
									</table>
									<?php
								}
							}
							
							if (isset($tpl['arr']['extra_arr']) && !empty($tpl['arr']['extra_arr']))
							{
								?>
								<table cellpadding="0" cellspacing="0" style="width: 100%" class="hbTable">
									<thead>
										<tr>
											<th class="hbBold" style="width: 50%"><?php __('front_extras'); ?></th>
										</tr>
									</thead>
									<tbody>
								<?php
								$extra_per = __('extra_per', true);
								foreach ($tpl['arr']['extra_arr'] as $extra)
								{
									?>
									<tr>
										<td style="width: 50%"><?php printf("%s (%s)", $extra['name'], @$extra_per[$extra['per']]); ?></td>
									</tr>
									<?php
								}
								?>
									</tbody>
								</table>
								<?php
							}
							?>
							
							<table cellpadding="0" cellspacing="0" style="width: 100%" class="hbTable">
								<thead>
									<tr>
										<th colspan="2" class="hbBold hbBorderBottom"></th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td style="width: 50%"><?php __('front_room_price'); ?>:</td>
										<td class="hbAlignRight"><?php echo pjUtil::formatCurrencySign(number_format(floatval($tpl['arr']['room_price']), 2), $tpl['option_arr']['o_currency']); ?></td>
									</tr>
									<?php if ($tpl['arr']['extra_price'] > 0) : ?>
									<tr>
										<td><?php __('front_extras_price'); ?>:</td>
										<td class="hbAlignRight"><?php echo pjUtil::formatCurrencySign(number_format(floatval($tpl['arr']['extra_price']), 2), $tpl['option_arr']['o_currency']); ?></td>
									</tr>
									<?php endif; ?>
									<?php if ($tpl['arr']['tax'] > 0) : ?>
									<tr>
										<td><?php __('front_tax'); ?>:</td>
										<td class="hbAlignRight"><?php echo pjUtil::formatCurrencySign(number_format(floatval($tpl['arr']['tax']), 2), $tpl['option_arr']['o_currency']); ?></td>
									</tr>
									<?php endif; ?>
									<tr>
										<td><?php __('front_total'); ?>:</td>
										<td class="hbAlignRight"><?php echo pjUtil::formatCurrencySign(number_format(floatval($tpl['arr']['total']), 2), $tpl['option_arr']['o_currency']); ?></td>
									</tr>
									<?php if ($tpl['arr']['security'] > 0) : ?>
									<tr>
										<td><?php __('front_security'); ?>:</td>
										<td class="hbAlignRight"><?php echo pjUtil::formatCurrencySign(number_format(floatval($tpl['arr']['security']), 2), $tpl['option_arr']['o_currency']); ?></td>
									</tr>
									<?php endif; ?>
									<tr>
										<td><?php __('front_deposit'); ?>:</td>
										<td class="hbAlignRight"><?php echo pjUtil::formatCurrencySign(number_format(floatval($tpl['arr']['deposit']), 2), $tpl['option_arr']['o_currency']); ?></td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
							
					<div class="hbFormBox">
						<div class="hbFormBoxTitle"><?php __('front_personal'); ?></div>
						<div class="hbFormBoxContent">
							<?php if ((int) $tpl['option_arr']['o_bf_title'] !== 1) : ?>
							<div class="hbRow">
								<label class="hbLabel"><?php __('booking_c_title'); ?></label>
								<span class="hbValue"><?php
								$pt = __('personal_titles', true);
								echo @$pt[$tpl['arr']['c_title']]; ?>
								</span>
							</div>
							<?php endif; ?>
							<?php if ((int) $tpl['option_arr']['o_bf_fname'] !== 1) : ?>
							<div class="hbRow hbBefore">
								<label class="hbLabel"><?php __('booking_c_fname'); ?></label>
								<span class="hbValue"><?php echo pjSanitize::html(@$tpl['arr']['c_fname']); ?></span>
							</div>
							<?php endif; ?>
							<?php if ((int) $tpl['option_arr']['o_bf_lname'] !== 1) : ?>
							<div class="hbRow hbBefore">
								<label class="hbLabel"><?php __('booking_c_lname'); ?></label>
								<span class="hbValue"><?php echo pjSanitize::html(@$tpl['arr']['c_lname']); ?></span>
							</div>
							<?php endif; ?>
							<?php if ((int) $tpl['option_arr']['o_bf_phone'] !== 1) : ?>
							<div class="hbRow hbBefore">
								<label class="hbLabel"><?php __('booking_c_phone'); ?></label>
								<span class="hbValue"><?php echo pjSanitize::html(@$tpl['arr']['c_phone']); ?></span>
							</div>
							<?php endif; ?>
							<?php if ((int) $tpl['option_arr']['o_bf_email'] !== 1) : ?>
							<div class="hbRow hbBefore">
								<label class="hbLabel"><?php __('booking_c_email'); ?></label>
								<span class="hbValue"><?php echo pjSanitize::html(@$tpl['arr']['c_email']); ?></span>
							</div>
							<?php endif; ?>
							<?php if ((int) $tpl['option_arr']['o_bf_arrival'] !== 1) : ?>
							<div class="hbRow hbBefore">
								<label class="hbLabel"><?php __('booking_arrival'); ?></label>
								<?php list($hour, $minute,) = explode(":", $tpl['arr']['c_arrival']); ?>
								<span class="hbValue"><?php printf("%s:%s", $hour, $minute); ?></span>
							</div>
							<?php endif; ?>
							<?php if ((int) $tpl['option_arr']['o_bf_notes'] !== 1) : ?>
							<div class="hbRow hbBefore">
								<label class="hbLabel"><?php __('booking_notes'); ?></label>
								<span class="hbValue"><?php echo pjSanitize::html(@$tpl['arr']['c_notes']); ?></span>
							</div>
							<?php endif; ?>
						</div>
					</div>
					
					<div class="hbFormBox">
						<div class="hbFormBoxTitle"><?php __('front_billing'); ?></div>
						<div class="hbFormBoxContent">
							<?php if ((int) $tpl['option_arr']['o_bf_company'] !== 1) : ?>
							<div class="hbRow">
								<label class="hbLabel"><?php __('booking_c_company'); ?></label>
								<span class="hbValue"><?php echo pjSanitize::html(@$tpl['arr']['c_company']); ?></span>
							</div>
							<?php endif; ?>
							<?php if ((int) $tpl['option_arr']['o_bf_address'] !== 1) : ?>
							<div class="hbRow hbBefore">
								<label class="hbLabel"><?php __('booking_c_address_1'); ?></label>
								<span class="hbValue"><?php echo pjSanitize::html(@$tpl['arr']['c_address_1']); ?></span>
							</div>
							<?php endif; ?>
							<?php if ((int) $tpl['option_arr']['o_bf_city'] !== 1) : ?>
							<div class="hbRow hbBefore">
								<label class="hbLabel"><?php __('booking_c_city'); ?></label>
								<span class="hbValue"><?php echo pjSanitize::html(@$tpl['arr']['c_city']); ?></span>
							</div>
							<?php endif; ?>
							<?php if ((int) $tpl['option_arr']['o_bf_state'] !== 1) : ?>
							<div class="hbRow hbBefore">
								<label class="hbLabel"><?php __('booking_c_state'); ?></label>
								<span class="hbValue"><?php echo pjSanitize::html(@$tpl['arr']['c_state']); ?></span>
							</div>
							<?php endif; ?>
							<?php if ((int) $tpl['option_arr']['o_bf_zip'] !== 1) : ?>
							<div class="hbRow hbBefore">
								<label class="hbLabel"><?php __('booking_c_zip'); ?></label>
								<span class="hbValue"><?php echo pjSanitize::html(@$tpl['arr']['c_zip']); ?></span>
							</div>
							<?php endif; ?>
							<?php if ((int) $tpl['option_arr']['o_bf_country'] !== 1) : ?>
							<div class="hbRow hbBefore">
								<label class="hbLabel"><?php __('booking_c_country'); ?></label>
								<span class="hbValue"><?php echo pjSanitize::html(@$tpl['arr']['country_title']); ?></span>
							</div>
							<?php endif; ?>
						</div>
					</div>
					
					<?php if ((int) $tpl['option_arr']['o_disable_payments'] === 0) : ?>
					<div class="hbFormBox">
						<div class="hbFormBoxTitle"><?php __('front_payment'); ?></div>
						<div class="hbFormBoxContent">
							<div class="hbRow">
								<label class="hbLabel"><?php __('booking_payment_method'); ?></label>
								<span class="hbValue">
								<?php
								$bp = __('booking_payments', true);
								echo @$bp[$tpl['arr']['payment_method']];
								?>
								</span>
							</div>
							<div class="hbRow hbBefore hbSelectorCCard" style="display: <?php echo @$tpl['arr']['payment_method'] != 'creditcard' ? 'none' : NULL; ?>">
								<label class="hbLabel"><?php __('booking_cc_type'); ?></label>
								<span class="hbValue"><?php echo pjSanitize::html(@$tpl['arr']['cc_type']); ?></span>
							</div>
							<div class="hbRow hbBefore hbSelectorCCard" style="display: <?php echo @$tpl['arr']['payment_method'] != 'creditcard' ? 'none' : NULL; ?>">
								<label class="hbLabel"><?php __('booking_cc_num'); ?></label>
								<span class="hbValue"><?php echo pjSanitize::html(@$tpl['arr']['cc_num']); ?></span>
							</div>
							<div class="hbRow hbBefore hbSelectorCCard" style="display: <?php echo @$tpl['arr']['payment_method'] != 'creditcard' ? 'none' : NULL; ?>">
								<label class="hbLabel"><?php __('booking_cc_code'); ?></label>
								<span class="hbValue"><?php echo pjSanitize::html(@$tpl['arr']['cc_code']); ?></span>
							</div>
							<div class="hbRow hbBefore hbSelectorCCard" style="display: <?php echo @$tpl['arr']['payment_method'] != 'creditcard' ? 'none' : NULL; ?>">
								<label class="hbLabel"><?php __('booking_cc_exp'); ?></label>
								<span class="hbValue">
								<?php
								printf("%s/%s", $tpl['arr']['cc_exp_month'], $tpl['arr']['cc_exp_year']);
								?>
								</span>
							</div>
						</div>
					</div>
					<?php endif; ?>
					<form action="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjFront&amp;action=pjActionCancel&amp;cid=<?php echo $_GET['cid']; ?>" method="post" class="hbForm">
						<input type="hidden" name="booking_cancel" value="1" />
						<input type="hidden" name="id" value="<?php echo $_GET['id']; ?>" />
						<input type="hidden" name="cid" value="<?php echo $_GET['cid']; ?>" />
						<input type="hidden" name="hash" value="<?php echo $_GET['hash']; ?>" />
						<input type="submit" value="<?php __('cancel_confirm', false, true); ?>" class="hbButton hbButtonOrange" />
					</form>
					<?php
				}
			}
			?>
			</div>
		</div>
		
	</body>
</html>