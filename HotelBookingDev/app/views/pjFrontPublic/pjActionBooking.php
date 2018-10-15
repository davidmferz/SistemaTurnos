<div class="panel panel-default clearfix pjHbPanel">
	<?php include dirname(__FILE__) . '/elements/head.php'; ?>

	<div class="panel-body text-center pjHbPanelBody pjHbPanelBodyLast">
	<?php
	if (isset($tpl['booking_arr']) && !empty($tpl['booking_arr']))
	{
		switch ($tpl['booking_arr']['payment_method'])
		{
			case 'paypal':
				?><div class="alert alert-info" role="alert"><?php __('system_203'); ?></div><?php
				if (pjObject::getPlugin('pjPaypal') !== NULL)
				{
					$controller->requestAction(array('controller' => 'pjPaypal', 'action' => 'pjActionForm', 'params' => $tpl['params']));
				}
				break;
			case 'authorize':
				?><div class="alert alert-info" role="alert"><?php __('system_203'); ?></div><?php
				if (pjObject::getPlugin('pjAuthorize') !== NULL)
				{
					$controller->requestAction(array('controller' => 'pjAuthorize', 'action' => 'pjActionForm', 'params' => $tpl['params']));
				}
				break;
			case 'bank':
				?>
				<p><?php __('front_booking_success'); ?></p>
				<p><?php printf(__('front_booking_uid', true), $tpl['booking_arr']['uuid']); ?></p>
				<p><?php __('front_booking_contact'); ?></p>
				
				<div class="alert alert-info" role="alert">
				<?php echo nl2br(pjSanitize::html($tpl['option_arr']['o_bank_account'])); ?>
				</div>
				<p><button type="button" class="btn btn-default text-capitalize hbSelectorSearch"><?php __('front_start_new_booking'); ?></button></p>
				<?php
				break;
			case 'creditcard':
			case 'cash':
			default:
				?>
				<p><?php __('front_booking_success'); ?></p>
				<p><?php printf(__('front_booking_uid', true), $tpl['booking_arr']['uuid']); ?></p>
				<p><?php __('front_booking_contact'); ?></p>
				<p><button type="button" class="btn btn-default text-capitalize hbSelectorSearch"><?php __('front_start_new_booking'); ?></button></p>
				<?php
		}
	}
	?>
	</div><!-- /.panel-body pjHbPanelBody -->
</div><!-- /.panel pjHbPanel -->