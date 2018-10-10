<div class="panel panel-default clearfix pjHbPanel">
	<?php include dirname(__FILE__) . '/elements/head.php'; ?>

	<div class="panel-body pjHbPanelBody pjHbPanelBooking">
	<?php
	if (isset($tpl['status']) && $tpl['status'] == 'OK')
	{
		$STORE = @$_SESSION[$controller->defaultStore];
		$FORM = @$_SESSION[$controller->defaultStore]['form'];
		
		include dirname(__FILE__) . '/elements/cart.php';
	} elseif (isset($tpl['status']) && $tpl['status'] == 'ERR') {
		?>
		<div class="alert alert-danger" role="alert"><?php echo pjSanitize::html($tpl['text']); ?></div>
		<button type="button" class="btn btn-default hbSelectorExtras"><?php __('front_btn_back'); ?></button>
		<?php
	}
	?>
	</div><!-- /.panel-body pjHbPanelBody pjHbPanelBooking -->
</div><!-- /.panel pjHbPanel -->

<?php
if (isset($tpl['status']) && $tpl['status'] == 'OK')
{
	?>
	<br>
	<form action="" class="form-horizontal pjHbFormCheckOut hbSelectorFormCheckout" data-toggle="validator" role="form" method="post">
		<input type="hidden" name="step_checkout" value="1" />
		<h3 class="pjHbFormCheckOutTitle"><?php __('front_personal'); ?></h3>

		<br>
		<?php if ((int) $tpl['option_arr']['o_bf_title'] !== 1) : ?>
		<div class="form-group">
			<label class="col-sm-3 col-xs-12 control-label"><?php __('booking_c_title'); ?><?php if ((int) $tpl['option_arr']['o_bf_title'] === 3) : ?> <span class="text-danger">*</span><?php endif; ?></label>
			
			<div class="col-sm-9 col-xs-12">
				<select class="form-control" name="c_title"<?php echo (int) $tpl['option_arr']['o_bf_title'] === 3 ? ' required' : NULL; ?> data-msg-required="<?php __('front_validate_title', false, true); ?>">
					<option value="">-- <?php __('front_select_title'); ?> --</option>
					<?php
					foreach (__('personal_titles', true) as $k => $v)
					{
						?><option value="<?php echo $k; ?>"<?php echo @$FORM['c_title'] != $k ? NULL : ' selected="selected"'; ?>><?php echo $v; ?></option><?php
					}
					?>
				</select>
			</div><!-- /.col-sm-10 -->
		</div>
		<?php endif; ?>
		<?php if ((int) $tpl['option_arr']['o_bf_fname'] !== 1) : ?>
		<div class="form-group">
			<label class="col-sm-3 col-xs-12 control-label"><?php __('booking_c_fname'); ?><?php if ((int) $tpl['option_arr']['o_bf_fname'] === 3) : ?> <span class="text-danger">*</span><?php endif; ?></label>
			
			<div class="col-sm-9 col-xs-12">
				<input type="text" class="form-control" name="c_fname" value="<?php echo pjSanitize::html(@$FORM['c_fname']); ?>"<?php echo (int) $tpl['option_arr']['o_bf_fname'] === 3 ? ' required' : NULL; ?> data-msg-required="<?php __('front_validate_fname', false, true); ?>">
			</div><!-- /.col-sm-10 -->
		</div>
		<?php endif; ?>
		<?php if ((int) $tpl['option_arr']['o_bf_lname'] !== 1) : ?>
		<div class="form-group">
			<label class="col-sm-3 col-xs-12 control-label"><?php __('booking_c_lname'); ?><?php if ((int) $tpl['option_arr']['o_bf_lname'] === 3) : ?> <span class="text-danger">*</span><?php endif; ?></label>
			
			<div class="col-sm-9 col-xs-12">
				<input type="text" class="form-control" name="c_lname" value="<?php echo pjSanitize::html(@$FORM['c_lname']); ?>"<?php echo (int) $tpl['option_arr']['o_bf_lname'] === 3 ? ' required' : NULL; ?> data-msg-required="<?php __('front_validate_lname', false, true); ?>">
			</div><!-- /.col-sm-10 -->
		</div>
		<?php endif; ?>
		<?php if ((int) $tpl['option_arr']['o_bf_phone'] !== 1) : ?>
		<div class="form-group">
			<label class="col-sm-3 col-xs-12 control-label"><?php __('booking_c_phone'); ?><?php if ((int) $tpl['option_arr']['o_bf_phone'] === 3) : ?> <span class="text-danger">*</span><?php endif; ?></label>
			
			<div class="col-sm-9 col-xs-12">
				<input type="text" class="form-control" name="c_phone" value="<?php echo pjSanitize::html(@$FORM['c_phone']); ?>"<?php echo (int) $tpl['option_arr']['o_bf_phone'] === 3 ? ' required' : NULL; ?> data-msg-required="<?php __('front_validate_phone', false, true); ?>">
			</div><!-- /.col-sm-10 -->
		</div>
		<?php endif; ?>
		<?php if ((int) $tpl['option_arr']['o_bf_email'] !== 1) : ?>
		<div class="form-group">
			<label class="col-sm-3 col-xs-12 control-label"><?php __('booking_c_email'); ?><?php if ((int) $tpl['option_arr']['o_bf_email'] === 3) : ?> <span class="text-danger">*</span><?php endif; ?></label>
			
			<div class="col-sm-9 col-xs-12">
				<input type="email" class="form-control" name="c_email" value="<?php echo pjSanitize::html(@$FORM['c_email']); ?>"<?php echo (int) $tpl['option_arr']['o_bf_email'] === 3 ? ' required' : NULL; ?> data-msg-required="<?php __('front_validate_email', false, true); ?>" data-msg-email="<?php __('front_validate_email_invalid', false, true); ?>">
			</div><!-- /.col-sm-10 -->
		</div>
		<?php endif; ?>
		<?php if ((int) $tpl['option_arr']['o_bf_arrival'] !== 1) : ?>
		<div class="form-group">
			<label class="col-sm-3 col-xs-12 control-label"><?php __('booking_arrival'); ?><?php if ((int) $tpl['option_arr']['o_bf_arrival'] === 3) : ?> <span class="text-danger">*</span><?php endif; ?></label>
			
			<div class="col-sm-9 col-xs-12">
				<div class="row">
					<div class="col-sm-3 col-xs-12">
						<div class='input-group pjHbTimePick'>
							<input type='text' class="form-control" autocomplete="off" name="c_arrival" value="<?php echo pjSanitize::html(@$FORM['c_arrival']); ?>"<?php echo (int) $tpl['option_arr']['o_bf_arrival'] === 3 ? ' required' : NULL; ?> data-msg-required="<?php __('front_validate_arrival', false, true); ?>" />

							<span class="input-group-addon">
								<span class="glyphicon glyphicon-time"></span>
							</span>
						</div>
					</div><!-- /.col-sm-3 -->
				</div><!-- /.row -->
			</div><!-- /.col-sm-10 -->
		</div>
		<?php endif; ?>
		<?php if ((int) $tpl['option_arr']['o_bf_notes'] !== 1) : ?>
		<div class="form-group">
			<label class="col-sm-3 col-xs-12 control-label"><?php __('booking_notes'); ?><?php if ((int) $tpl['option_arr']['o_bf_notes'] === 3) : ?> <span class="text-danger">*</span><?php endif; ?></label>
			
			<div class="col-sm-9 col-xs-12">
				<textarea class="form-control" name="c_notes"<?php echo (int) $tpl['option_arr']['o_bf_notes'] === 3 ? ' required' : NULL; ?> data-msg-required="<?php __('front_validate_notes', false, true); ?>"><?php echo pjSanitize::html(@$FORM['c_notes']); ?></textarea>
			</div>
		</div>
		<?php endif; ?>
		<br>
		<hr>
		<br>
		<h3 class="pjHbFormCheckOutTitle"><?php __('front_billing'); ?></h3>
		<br>
		<?php if ((int) $tpl['option_arr']['o_bf_company'] !== 1) : ?>
		<div class="form-group">
			<label class="col-sm-3 col-xs-12 control-label"><?php __('booking_c_company'); ?><?php if ((int) $tpl['option_arr']['o_bf_company'] === 3) : ?> <span class="text-danger">*</span><?php endif; ?></label>
			
			<div class="col-sm-9 col-xs-12">
				<input type="text" name="c_company" class="form-control" value="<?php echo pjSanitize::html(@$FORM['c_company']); ?>"<?php echo (int) $tpl['option_arr']['o_bf_company'] === 3 ? ' required' : NULL; ?> data-msg-required="<?php __('front_validate_company', false, true); ?>" />
			</div>
		</div>
		<?php endif; ?>
		<?php if ((int) $tpl['option_arr']['o_bf_address'] !== 1) : ?>
		<div class="form-group">
			<label class="col-sm-3 col-xs-12 control-label"><?php __('booking_c_address_1'); ?><?php if ((int) $tpl['option_arr']['o_bf_address'] === 3) : ?> <span class="text-danger">*</span><?php endif; ?></label>
			
			<div class="col-sm-9 col-xs-12">
				<input type="text" class="form-control" name="c_address_1" value="<?php echo pjSanitize::html(@$FORM['c_address_1']); ?>"<?php echo (int) $tpl['option_arr']['o_bf_address'] === 3 ? ' required' : NULL; ?> data-msg-required="<?php __('front_validate_address', false, true); ?>">
			</div><!-- /.col-sm-10 -->
		</div>
		<?php endif; ?>
		<?php if ((int) $tpl['option_arr']['o_bf_city'] !== 1) : ?>
		<div class="form-group">
			<label class="col-sm-3 col-xs-12 control-label"><?php __('booking_c_city'); ?><?php if ((int) $tpl['option_arr']['o_bf_city'] === 3) : ?> <span class="text-danger">*</span><?php endif; ?></label>
			
			<div class="col-sm-9 col-xs-12">
				<input type="text" class="form-control" name="c_city" value="<?php echo pjSanitize::html(@$FORM['c_city']); ?>"<?php echo (int) $tpl['option_arr']['o_bf_city'] === 3 ? ' required' : NULL; ?> data-msg-required="<?php __('front_validate_city', false, true); ?>">
			</div><!-- /.col-sm-10 -->
		</div>
		<?php endif; ?>
		<?php if ((int) $tpl['option_arr']['o_bf_state'] !== 1) : ?>
		<div class="form-group">
			<label class="col-sm-3 col-xs-12 control-label"><?php __('booking_c_state'); ?><?php if ((int) $tpl['option_arr']['o_bf_state'] === 3) : ?> <span class="text-danger">*</span><?php endif; ?></label>
			
			<div class="col-sm-9 col-xs-12">
				<input type="text" class="form-control" name="c_state" value="<?php echo pjSanitize::html(@$FORM['c_state']); ?>"<?php echo (int) $tpl['option_arr']['o_bf_state'] === 3 ? ' required' : NULL; ?> data-msg-required="<?php __('front_validate_state', false, true); ?>">
			</div><!-- /.col-sm-10 -->
		</div>
		<?php endif; ?>
		<?php if ((int) $tpl['option_arr']['o_bf_zip'] !== 1) : ?>
		<div class="form-group">
			<label class="col-sm-3 col-xs-12 control-label"><?php __('booking_c_zip'); ?><?php if ((int) $tpl['option_arr']['o_bf_zip'] === 3) : ?> <span class="text-danger">*</span><?php endif; ?></label>
			
			<div class="col-sm-9 col-xs-12">
				<input type="text" class="form-control" name="c_zip" value="<?php echo pjSanitize::html(@$FORM['c_zip']); ?>"<?php echo (int) $tpl['option_arr']['o_bf_zip'] === 3 ? ' required' : NULL; ?> data-msg-required="<?php __('front_validate_zip', false, true); ?>">
			</div><!-- /.col-sm-10 -->
		</div>
		<?php endif; ?>
		<?php if ((int) $tpl['option_arr']['o_bf_country'] !== 1) : ?>
		<div class="form-group">
			<label class="col-sm-3 col-xs-12 control-label"><?php __('booking_c_country'); ?><?php if ((int) $tpl['option_arr']['o_bf_country'] === 3) : ?> <span class="text-danger">*</span><?php endif; ?></label>
			
			<div class="col-sm-9 col-xs-12">
				<select class="form-control" name="c_country"<?php echo (int) $tpl['option_arr']['o_bf_country'] === 3 ? ' required' : NULL; ?> data-msg-required="<?php __('front_validate_country', false, true); ?>">
					<option value="">-- <?php __('front_select_country'); ?> --</option>
					<?php
					foreach ($tpl['country_arr'] as $country)
					{
						?><option value="<?php echo $country['id']; ?>"<?php echo @$FORM['c_country'] != $country['id'] ? NULL : ' selected="selected"'; ?>><?php echo pjSanitize::html($country['name']); ?></option><?php
					}
					?>
				</select>
			</div><!-- /.col-sm-10 -->
		</div>
		<?php endif; ?>

		<?php if ((int) $tpl['option_arr']['o_disable_payments'] === 0) : ?>
		<br>
		<hr>
		<br>
		<h3 class="pjHbFormCheckOutTitle"><?php __('front_payment'); ?></h3>
		<br>
		<div class="form-group">
			<label class="col-sm-3 col-xs-12 control-label"><?php __('booking_payment_method'); ?> <span class="text-danger">*</span></label>
			
			<div class="col-sm-9 col-xs-12">
				<select class="form-control" name="payment_method" required data-msg-required="<?php __('front_validate_payment', false, true); ?>">
					<option value="">-- <?php __('front_select_payment'); ?> --</option>
					<?php
					foreach (__('booking_payments', true) as $k => $v)
					{
						if ((int) $tpl['option_arr']['o_allow_' . $k] === 1)
						{
							?><option value="<?php echo $k; ?>"<?php echo @$FORM['payment_method'] != $k ? NULL : ' selected="selected"'; ?>><?php echo $v; ?></option><?php
						}
					}
					?>
				</select>
			</div><!-- /.col-sm-10 -->
		</div>

		<div class="hbSelectorBank" style="display: <?php echo @$FORM['payment_method'] != 'bank' ? 'none' : NULL; ?>">
			<br>
			<hr>
			<br>
			<h3 class="pjHbFormCheckOutTitle"><?php __('booking_bank_account'); ?></h3>
			<br>
			<div class="form-group">
				<label class="col-sm-3 col-xs-12 control-label"><?php __('booking_bank_account'); ?></label>
				<div class="col-sm-9 col-xs-12">
					<p class="form-control-static"><?php echo nl2br(pjSanitize::html($tpl['option_arr']['o_bank_account'])); ?></p>
				</div>
			</div>
		</div>
		<div class="hbSelectorCCard" style="display: <?php echo @$FORM['payment_method'] != 'creditcard' ? 'none' : NULL; ?>">
			<br>
			<hr>
			<br>
			<h3 class="pjHbFormCheckOutTitle"><?php __('booking_cc'); ?></h3>
			<br>
			<div class="form-group">
				<label class="col-sm-3 col-xs-12 control-label"><?php __('booking_cc_type'); ?> <span class="text-danger">*</span></label>
				
				<div class="col-sm-9 col-xs-12">
					<select class="form-control" name="cc_type" required data-msg-required="<?php __('front_validate_cc_type', false, true); ?>">
						<option value="">-- <?php __('front_select_cc_type'); ?> --</option>
						<?php
						foreach (__('booking_cc_types', true) as $k => $v)
						{
							?><option value="<?php echo $k; ?>"<?php echo @$FORM['cc_type'] != $k ? NULL : ' selected="selected"'; ?>><?php echo $v; ?></option><?php
						}
						?>
					</select>
				</div><!-- /.col-sm-10 -->
			</div>
	
			<div class="form-group">
				<label class="col-sm-3 col-xs-12 control-label"><?php __('booking_cc_num'); ?> <span class="text-danger">*</span></label>
				
				<div class="col-sm-9 col-xs-12">
					<input type="text" class="form-control" name="cc_num" value="<?php echo pjSanitize::html(@$FORM['cc_num']); ?>" required data-msg-required="<?php __('front_validate_cc_num', false, true); ?>">
				</div><!-- /.col-sm-10 -->
			</div>
	
			<div class="form-group">
				<label class="col-sm-3 col-xs-12 control-label"><?php __('booking_cc_code'); ?> <span class="text-danger">*</span></label>
				
				<div class="col-sm-9 col-xs-12">
					<input type="text" class="form-control" name="cc_code" value="<?php echo pjSanitize::html(@$FORM['cc_code']); ?>" required data-msg-required="<?php __('front_validate_cc_code', false, true); ?>">
				</div><!-- /.col-sm-10 -->
			</div>
	
			<div class="form-group">
				<label class="col-sm-3 col-xs-12 control-label"><?php __('booking_cc_exp'); ?> <span class="text-danger">*</span></label>
				
				<div class="col-sm-9 col-xs-12">
					<div class="row">
						<div class="col-sm-3 col-xs-6">
							<select class="form-control" name="cc_exp_month" required data-msg-required="<?php __('front_validate_cc_exp_month', false, true); ?>">
							<?php 
							$months = __('months', true);
							ksort($months);
							foreach ($months as $k => $v)
							{
								?><option value="<?php echo $k; ?>"<?php echo $k != @$FORM['cc_exp_month'] ? NULL : ' selected="selected"'; ?>><?php echo pjSanitize::html($v); ?></option><?php
							}
							?>
							</select>
						</div><!-- /.col-sm-3 col-xs-6 -->
	
						<div class="col-sm-3 col-xs-6">
							<select class="form-control" name="cc_exp_year" required data-msg-required="<?php __('front_validate_cc_exp_year', false, true); ?>">
							<?php 
							$y = date("Y");
							foreach (range($y, $y + 5) as $i)
							{
								?><option value="<?php echo $i; ?>"<?php echo $i != @$FORM['cc_exp_year'] ? NULL : ' selected="selected"'; ?>><?php echo $i; ?></option><?php
							}
							?>
							</select>
						</div><!-- /.col-sm-3 col-xs-6 -->
					</div><!-- /.row -->
				</div><!-- /.col-sm-10 -->
			</div>
		</div><!-- /.hbSelectorCCard -->
		<?php endif; ?>

		<?php if ((int) $tpl['option_arr']['o_bf_captcha'] !== 1) : ?>
		<br>
		<hr>
		<br>
		<h3 class="pjHbFormCheckOutTitle"><?php __('front_human_verification'); ?></h3>
		<br>
		<div class="form-group">
			<label class="col-sm-3 col-xs-12 control-label"><?php __('front_captcha'); ?><?php if ((int) $tpl['option_arr']['o_bf_captcha'] === 3) : ?> <span class="text-danger">*</span><?php endif; ?></label>
			
			<div class="col-sm-9 col-xs-12">
				<div class="row">
					<div class="col-sm-3 col-xs-6">
						<input type="text" class="form-control" id="pjHbCaptchaField" name="captcha" maxlength="6" autocomplete="off"<?php echo (int) $tpl['option_arr']['o_bf_captcha'] === 3 ? ' required' : NULL; ?> data-msg-required="<?php __('front_validate_captcha', false, true); ?>" data-msg-remote="<?php __('front_validate_captcha_remote', false, true); ?>">
					</div><!-- /.col-sm-2 -->
					
					<div class="col-sm-3 col-xs-6">
						<img id="pjHbCaptchaImage" src="<?php echo PJ_INSTALL_URL; ?>index.php?controller=pjFront&action=pjActionCaptcha&amp;rand=<?php echo rand(1, 999999); ?><?php echo isset($_GET['session_id']) ? '&session_id' . pjObject::escapeString($_GET['session_id']) : NULL;?>" alt="Captcha" style="cursor: pointer;">
					</div><!-- /.col-sm-2 -->
				</div><!-- /.row -->
			</div><!-- /.col-sm-10 -->
		</div>
		<?php endif; ?>

		<?php if ((int) $tpl['option_arr']['o_bf_terms'] !== 1) : ?>
		<br>
		<hr>
		<br>
		<h3 class="pjHbFormCheckOutTitle"><?php __('front_terms'); ?></h3>
		<br>
		<div class="form-group">
			<label class="col-sm-3 col-xs-12 control-label"><?php __('front_terms_label'); ?><?php if ((int) $tpl['option_arr']['o_bf_terms'] === 3) : ?> <span class="text-danger">*</span><?php endif; ?></label>
			
			<div class="col-sm-9 col-xs-12">
				<div>
					<input type="checkbox" id="pjHbTerms_<?php echo @$_GET['cid']; ?>" name="terms" value="1"<?php echo (int) $tpl['option_arr']['o_bf_terms'] === 3 ? ' required' : NULL; ?> data-msg-required="<?php __('front_validate_terms', false, true); ?>"> <label for="pjHbTerms_<?php echo @$_GET['cid']; ?>" class="control-label"><?php __('front_terms_note'); ?></label>
					<?php
					$t_url = $tpl['calendar_arr']['terms_url'];
					$t_body = trim($tpl['calendar_arr']['terms_body']);
					if (!empty($t_url) && preg_match('/^http(s)?:\/\//i', $t_url))
					{
						?>
						<p><a href="<?php echo pjSanitize::html($t_url); ?>" target="_blank"><?php __('front_terms_link'); ?></a></p>
						<?php
					}else{
						?>
						<p><a data-toggle="modal" href="<?php echo PJ_INSTALL_URL; ?>index.php?controller=pjFront&action=pjActionGetTerms&cid=<?php echo @$_GET['cid']; ?>" data-target="#myModal_<?php echo @$_GET['cid']; ?>"><?php __('front_terms_link'); ?></a></p>
						<?php
					} 
					?>
				</div>
			</div>
		</div>
		
		<div class="modal fade" id="myModal_<?php echo @$_GET['cid']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		    <div class="modal-dialog">
		        <div class="modal-content">
		        </div><!-- /.modal-content -->
		    </div><!-- /.modal-dialog -->
		</div><!-- /.modal -->
		<?php endif; ?>
			
		<br>
		<br>
		
		<div class="row">
			<div class="col-xs-4 col-xs-12">
				<button type="button" class="btn btn-default hbSelectorExtras"><?php __('front_btn_back'); ?></button>
			</div><!-- /.col-sm-4 -->

			<div class="col-xs-8 col-xs-12 text-right">
			<?php
			if (isset($STORE['step_search']) && isset($STORE['step_rooms']) && isset($STORE['step_extras']))
			{
				?><button type="submit" class="btn btn-default"><?php __('front_btn_preview'); ?></button><?php
			}
			?>
			</div><!-- /.col-sm-8 -->
		</div><!-- /.row -->
	</form>
	<?php 
}
?>