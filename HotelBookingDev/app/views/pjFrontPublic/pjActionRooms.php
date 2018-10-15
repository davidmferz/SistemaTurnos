<div class="panel panel-default clearfix pjHbPanel">
	<?php include dirname(__FILE__) . '/elements/head.php'; ?>

	<div class="panel-body pjHbPanelBody">
	<?php 
	$STORE = @$_SESSION[$controller->defaultStore];
	$isOK = isset($tpl['status']) && $tpl['status'] == 'OK';
	$isStep = isset($STORE['step_search']);
	if ($isOK)
	{
		if ($isStep)
		{
			list($fw, $fn, $fj, $fS, $fY) = explode("-", date("w-n-j-S-Y", strtotime($STORE['date_from'])));
			list($tw, $tn, $tj, $tS, $tY) = explode("-", date("w-n-j-S-Y", strtotime($STORE['date_to'])));
			$days = __('days', true);
			$months = __('months', true);
			$suffix = __('day_suffix', true);
			?>
			<p>
				<strong class="pjHbPanelTitle"><?php __('front_available_rooms'); ?></strong> 
				<br>
				<?php printf("%s, %s %u%s, %u", $days[$fw], $months[$fn], $fj, $suffix[$fS], $fY); ?>
				<?php __('front_to'); ?> 
				<?php printf("%s, %s %u%s, %u", $days[$tw], $months[$tn], $tj, $suffix[$tS], $tY); ?> (<a href="<?php echo pjUtil::getReferer(); ?>#!/Search" class="hbSelectorSearch"><?php __('front_change_dates'); ?></a>)
			</p>
			<?php
		} else {
			?>
			<div class="alert alert-danger" role="alert"><?php __('front_not_found'); ?></div>
			<button type="button" class="btn btn-default hbSelectorSearch"><?php __('front_btn_back'); ?></button>
			<?php
		}
	} elseif (isset($tpl['status']) && $tpl['status'] == 'ERR') {
		?>
		<div class="alert alert-danger" role="alert"><?php echo pjSanitize::html($tpl['text']); ?></div>
		<button type="button" class="btn btn-default hbSelectorSearch"><?php __('front_btn_back'); ?></button>
		<?php
	}
	?>
	</div><!-- /.panel-body pjHbPanelBody -->
</div><!-- /.panel pjHbPanel -->

<br>
<?php
if ($isOK && $isStep)
{
	if (isset($tpl['arr']) && !empty($tpl['arr']))
	{
		foreach ($tpl['arr'] as $item)
		{
			?>
			<div class="hbRoomItem"><?php include PJ_VIEWS_PATH . 'pjFront/pjActionGetRoom.php'; ?></div>
			<?php
		}
		?>
		<div class="hbRoomsButtons">
			<div class="hbSelectorAccommodate alert alert-info" role="alert" style="display: <?php echo (isset($STORE['content']) && !empty($STORE['content'])) ? NULL : 'none'; ?>"></div>
			<div class="row">
				<div class="col-xs-4">
					<button type="button" class="btn btn-default hbSelectorSearch"><?php __('front_btn_back'); ?></button>
				</div>
				<div class="col-xs-8 text-right">
					<?php if ((int) $tpl['option_arr']['o_accept_bookings'] === 1) : ?>
					<button type="button" class="btn btn-default hbSelectorExtras" style="display: <?php echo (isset($STORE['content']) && !empty($STORE['content'])) ? NULL : 'none'; ?>"><?php __('front_btn_continue'); ?></button>
					<?php endif; ?>
				</div>
			</div>
		</div>
		<br>
		<?php
	} else {
		?>
		<div class="alert alert-danger" role="alert"><?php __('front_not_found'); ?></div>
		<button type="button" class="btn btn-default hbSelectorSearch"><?php __('front_btn_back'); ?></button>
		<?php
	}
}
?>