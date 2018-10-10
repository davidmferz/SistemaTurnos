<div class="hbContainerInner">
<?php
if (isset($tpl['get']['payment_method']))
{
	switch ($tpl['get']['payment_method'])
	{
		case 'paypal':
			?><div class="hbNotice"><?php __('system_203'); ?></div><?php
			if (pjObject::getPlugin('pjPaypal') !== NULL)
			{
				$controller->requestAction(array('controller' => 'pjPaypal', 'action' => 'pjActionForm', 'params' => $tpl['params']));
			}
			break;
		case 'authorize':
			?><div class="hbNotice"><?php __('system_203'); ?></div><?php
			if (pjObject::getPlugin('pjAuthorize') !== NULL)
			{
				$controller->requestAction(array('controller' => 'pjAuthorize', 'action' => 'pjActionForm', 'params' => $tpl['params']));
			}
			break;
		case 'bank':
			?>
			<div class="hbNotice">
				<?php __('system_202'); ?>
				<br /><br />
				<?php echo nl2br(pjSanitize::html($tpl['option_arr']['o_bank_account'])); ?>
			</div>
			<?php
			break;
		case 'creditcard':
		case 'cash':
		default:
			?><div class="hbNotice"><?php __('system_202'); ?></div><?php
	}
}
?>
</div>