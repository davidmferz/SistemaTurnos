<?php 
$front_steps = __('front_steps', true);
$current_step = $front_steps['search'];

$step1 = ' disabled';
$step2 = ' disabled';
$step3 = ' disabled';
$step4 = ' disabled';
$step5 = ' disabled';
$step6 = ' disabled';

$STORE = @$_SESSION[$controller->defaultStore];

if ($_GET['action'] == 'pjActionSearch')
{
	$step1 = ' pjHbBtnActive';
	$current_step = $front_steps['search'];
} elseif (isset($STORE['step_search'])) {
	$step1 = ' pjHbBtnPassed';
}
if ($_GET['action'] == 'pjActionRooms')
{
	$step2 = ' pjHbBtnActive';
	$current_step = $front_steps['rooms'];
} elseif (isset($STORE['step_rooms'])) {
	$step2 = ' pjHbBtnPassed';
}
if ($_GET['action'] == 'pjActionExtras')
{
	$step3 = ' pjHbBtnActive';
	$current_step = $front_steps['extras'];
} elseif (isset($STORE['step_extras'])) {
	$step3 = ' pjHbBtnPassed';
}
if ($_GET['action'] == 'pjActionCheckout')
{
	$step4 = ' pjHbBtnActive';
	$current_step = $front_steps['checkout'];
} elseif (isset($STORE['step_checkout'])) {
	$step4 = ' pjHbBtnPassed';
}
if ($_GET['action'] == 'pjActionPreview')
{
	$step5 = ' pjHbBtnActive';
	$current_step = $front_steps['preview'];
} elseif (isset($STORE['step_preview'])) {
	$step5 = ' pjHbBtnPassed';
}
if ($_GET['action'] == 'pjActionBooking')
{
	$step6 = ' pjHbBtnActive';
	$current_step = $front_steps['booking'];
}
?>
<div class="btn-group pjHbNav">
	<button type="button" class="btn btn-default dropdown-toggle pjHbBtnNav" data-toggle="dropdown" aria-expanded="false">
		<?php echo pjSanitize::html($current_step); ?>
		<span class="caret"></span>
	</button>

	<ul class="dropdown-menu text-uppercase" role="menu">
		<li><a href="#" class="btn btn-link pjHbBtn hbSelectorSearch<?php echo $step1; ?>" role="button" style="text-align: left"><?php echo pjSanitize::html(@$front_steps['search']); ?></a></li>
		<li><a href="#" class="btn btn-link pjHbBtn hbSelectorRooms<?php echo $step2; ?>" role="button" style="text-align: left"><?php echo pjSanitize::html(@$front_steps['rooms']); ?></a></li>
		<li><a href="#" class="btn btn-link pjHbBtn hbSelectorExtras<?php echo $step3; ?>" role="button" style="text-align: left"><?php echo pjSanitize::html(@$front_steps['extras']); ?></a></li>
		<li><a href="#" class="btn btn-link pjHbBtn hbSelectorCheckout<?php echo $step4; ?>" role="button" style="text-align: left"><?php echo pjSanitize::html(@$front_steps['checkout']); ?></a></li>
		<li><a href="#" class="btn btn-link pjHbBtn hbSelectorPreview<?php echo $step5; ?>" role="button" style="text-align: left"><?php echo pjSanitize::html(@$front_steps['preview']); ?></a></li>
		<li><a href="#" class="btn btn-link pjHbBtn<?php echo $step6; ?>" role="button" style="text-align: left"><?php echo pjSanitize::html(@$front_steps['booking']); ?></a></li>
	</ul><!-- /.dropdown-menu text-uppercase -->
</div><!-- /.btn-group pjHbNav -->