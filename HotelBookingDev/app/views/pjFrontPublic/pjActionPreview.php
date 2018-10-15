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
		<button type="button" class="btn btn-default hbSelectorCheckout"><?php __('front_btn_back'); ?></button>
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
	<div class="form-horizontal">
		<h3 class="pjHbFormCheckOutTitle"><?php __('front_personal'); ?></h3>
		<br>
		<?php if ((int) $tpl['option_arr']['o_bf_title'] !== 1) : ?>
		<div class="form-group">
			<label class="col-sm-3 col-xs-12 control-label"><?php __('booking_c_title'); ?><?php if ((int) $tpl['option_arr']['o_bf_title'] === 3) : ?> <span class="text-danger">*</span><?php endif; ?></label>
			
			<div class="col-sm-9 col-xs-12">
				<p class="form-control-static"><?php
				$personal_titles = __('personal_titles', true);
				echo @$personal_titles[@$FORM['c_title']];
				?></p>
			</div><!-- /.col-sm-10 -->
		</div>
		<?php endif; ?>
		<?php if ((int) $tpl['option_arr']['o_bf_fname'] !== 1) : ?>
		<div class="form-group">
			<label class="col-sm-3 col-xs-12 control-label"><?php __('booking_c_fname'); ?><?php if ((int) $tpl['option_arr']['o_bf_fname'] === 3) : ?> <span class="text-danger">*</span><?php endif; ?></label>
			
			<div class="col-sm-9 col-xs-12">
			 	<p class="form-control-static"><?php echo pjSanitize::html(@$FORM['c_fname']); ?></p>
			</div><!-- /.col-sm-10 -->
		</div>
		<?php endif; ?>
		<?php if ((int) $tpl['option_arr']['o_bf_lname'] !== 1) : ?>
		<div class="form-group">
			<label class="col-sm-3 col-xs-12 control-label"><?php __('booking_c_lname'); ?><?php if ((int) $tpl['option_arr']['o_bf_lname'] === 3) : ?> <span class="text-danger">*</span><?php endif; ?></label>
			
			<div class="col-sm-9 col-xs-12">
				<p class="form-control-static"><?php echo pjSanitize::html(@$FORM['c_lname']); ?></p>
			</div><!-- /.col-sm-10 -->
		</div>
		<?php endif; ?>
		<?php if ((int) $tpl['option_arr']['o_bf_phone'] !== 1) : ?>
		<div class="form-group">
			<label class="col-sm-3 col-xs-12 control-label"><?php __('booking_c_phone'); ?><?php if ((int) $tpl['option_arr']['o_bf_phone'] === 3) : ?> <span class="text-danger">*</span><?php endif; ?></label>
			
			<div class="col-sm-9 col-xs-12">
				<p class="form-control-static"><?php echo pjSanitize::html(@$FORM['c_phone']); ?></p>
			</div><!-- /.col-sm-10 -->
		</div>
		<?php endif; ?>
		<?php if ((int) $tpl['option_arr']['o_bf_email'] !== 1) : ?>
		<div class="form-group">
			<label class="col-sm-3 col-xs-12 control-label"><?php __('booking_c_email'); ?><?php if ((int) $tpl['option_arr']['o_bf_email'] === 3) : ?> <span class="text-danger">*</span><?php endif; ?></label>
			
			<div class="col-sm-9 col-xs-12">
				<p class="form-control-static"><?php echo pjSanitize::html(@$FORM['c_email']); ?></p>
			</div><!-- /.col-sm-10 -->
		</div>
		<?php endif; ?>
		<?php if ((int) $tpl['option_arr']['o_bf_arrival'] !== 1) : ?>
		<div class="form-group">
			<label class="col-sm-3 col-xs-12 control-label"><?php __('booking_arrival'); ?><?php if ((int) $tpl['option_arr']['o_bf_arrival'] === 3) : ?> <span class="text-danger">*</span><?php endif; ?></label>
			
			<div class="col-sm-9 col-xs-12">
				<p class="form-control-static"><?php echo pjSanitize::html(@$FORM['c_arrival']); ?></p>
			</div><!-- /.col-sm-10 -->
		</div>
		<?php endif; ?>
		<?php if ((int) $tpl['option_arr']['o_bf_notes'] !== 1) : ?>
		<div class="form-group">
			<label class="col-sm-3 col-xs-12 control-label"><?php __('booking_notes'); ?><?php if ((int) $tpl['option_arr']['o_bf_notes'] === 3) : ?> <span class="text-danger">*</span><?php endif; ?></label>
			
			<div class="col-sm-9 col-xs-12">
				<p class="form-control-static"><?php echo pjSanitize::html(@$FORM['c_notes']); ?></p>
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
				<p class="form-control-static"><?php echo pjSanitize::html(@$FORM['c_company']); ?></p>
			</div>
		</div>
		<?php endif; ?>
		<?php if ((int) $tpl['option_arr']['o_bf_address'] !== 1) : ?>
		<div class="form-group">
			<label class="col-sm-3 col-xs-12 control-label"><?php __('booking_c_address_1'); ?><?php if ((int) $tpl['option_arr']['o_bf_address'] === 3) : ?> <span class="text-danger">*</span><?php endif; ?></label>
			
			<div class="col-sm-9 col-xs-12">
				<p class="form-control-static"><?php echo pjSanitize::html(@$FORM['c_address_1']); ?></p>
			</div><!-- /.col-sm-10 -->
		</div>
		<?php endif; ?>
		<?php if ((int) $tpl['option_arr']['o_bf_city'] !== 1) : ?>
		<div class="form-group">
			<label class="col-sm-3 col-xs-12 control-label"><?php __('booking_c_city'); ?><?php if ((int) $tpl['option_arr']['o_bf_city'] === 3) : ?> <span class="text-danger">*</span><?php endif; ?></label>
			
			<div class="col-sm-9 col-xs-12">
				<p class="form-control-static"><?php echo pjSanitize::html(@$FORM['c_city']); ?></p>
			</div><!-- /.col-sm-10 -->
		</div>
		<?php endif; ?>
		<?php if ((int) $tpl['option_arr']['o_bf_state'] !== 1) : ?>
		<div class="form-group">
			<label class="col-sm-3 col-xs-12 control-label"><?php __('booking_c_state'); ?><?php if ((int) $tpl['option_arr']['o_bf_state'] === 3) : ?> <span class="text-danger">*</span><?php endif; ?></label>
			
			<div class="col-sm-9 col-xs-12">
				<p class="form-control-static"><?php echo pjSanitize::html(@$FORM['c_state']); ?></p>
			</div><!-- /.col-sm-10 -->
		</div>
		<?php endif; ?>
		<?php if ((int) $tpl['option_arr']['o_bf_zip'] !== 1) : ?>
		<div class="form-group">
			<label class="col-sm-3 col-xs-12 control-label"><?php __('booking_c_zip'); ?><?php if ((int) $tpl['option_arr']['o_bf_zip'] === 3) : ?> <span class="text-danger">*</span><?php endif; ?></label>
			
			<div class="col-sm-9 col-xs-12">
				<p class="form-control-static"><?php echo pjSanitize::html(@$FORM['c_zip']); ?></p>
			</div><!-- /.col-sm-10 -->
		</div>
		<?php endif; ?>
		<?php if (((int) $tpl['option_arr']['o_bf_country'] !== 1) && isset($tpl['country_arr']['name'])) : ?>
		<div class="form-group">
			<label class="col-sm-3 col-xs-12 control-label"><?php __('booking_c_country'); ?><?php if ((int) $tpl['option_arr']['o_bf_country'] === 3) : ?> <span class="text-danger">*</span><?php endif; ?></label>
			
			<div class="col-sm-9 col-xs-12">
				<p class="form-control-static"><?php echo pjSanitize::html($tpl['country_arr']['name']); ?></p>
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
				<p class="form-control-static"><?php
				$booking_payments = __('booking_payments', true);
				echo @$booking_payments[@$FORM['payment_method']];
				?></p>
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
					<p class="form-control-static"><?php
					$booking_cc_types = __('booking_cc_types', true);
					echo @$booking_cc_types[@$FORM['cc_type']];
					?></p>
				</div><!-- /.col-sm-10 -->
			</div>
	
			<div class="form-group">
				<label class="col-sm-3 col-xs-12 control-label"><?php __('booking_cc_num'); ?> <span class="text-danger">*</span></label>
				
				<div class="col-sm-9 col-xs-12">
					<p class="form-control-static"><?php echo pjSanitize::html(@$FORM['cc_num']); ?></p>
				</div><!-- /.col-sm-10 -->
			</div>
	
			<div class="form-group">
				<label class="col-sm-3 col-xs-12 control-label"><?php __('booking_cc_code'); ?> <span class="text-danger">*</span></label>
				
				<div class="col-sm-9 col-xs-12">
					<p class="form-control-static"><?php echo pjSanitize::html(@$FORM['cc_code']); ?></p>
				</div><!-- /.col-sm-10 -->
			</div>
	
			<div class="form-group">
				<label class="col-sm-3 col-xs-12 control-label"><?php __('booking_cc_exp'); ?> <span class="text-danger">*</span></label>
				
				<div class="col-sm-9 col-xs-12">
					<p class="form-control-static"><?php 
					$months = __('months', true);
					echo @$months[@$FORM['cc_exp_month']]; ?>
					<?php echo @$FORM['cc_exp_year']; ?></p>
				</div><!-- /.col-sm-10 -->
			</div>
		</div><!-- /.hbSelectorCCard -->
		<?php endif; ?>
	</div>
	
	<form action="" method="post" class="hbForm">
		<input type="hidden" name="step_preview" value="1" />
		<div class="hbSelectorError alert alert-danger" role="alert" style="display: none"></div>
		<div class="row">
			<div class="col-xs-4">
				<button type="button" class="btn btn-default hbSelectorCheckout"><?php __('front_btn_back'); ?></button>
			</div>
			<div class="col-xs-8 text-right">
			<?php
			if (isset($STORE['step_search']) && isset($STORE['step_rooms']) && isset($STORE['step_extras']) && isset($STORE['step_checkout']))
			{
				?><button type="submit" class="btn btn-default"><?php __('front_btn_confirm'); ?></button><?php
			}
			?>
			</div><!-- /.col-sm-8 -->
		</div>
	</form>
	<?php 
}
?>