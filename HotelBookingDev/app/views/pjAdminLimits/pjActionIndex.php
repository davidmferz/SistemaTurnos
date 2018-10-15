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
	$days = __('days', true);
	include PJ_VIEWS_PATH . 'pjAdminRooms/elements/menu.php';
	pjUtil::printNotice(@$titles['AL10'], @$bodies['AL10']);
	
	if ($tpl['is_empty'])
	{
		?>
		<div class="empty-page">
			<div class="empty-text"><?php __('limit_empty_msg'); ?></div>
			<div class="empty-btn"><a href="<?php echo $_SERVER['PHP_SELF']; ?>" class="pj-button limit-add"><?php __('limit_add'); ?></a></div>
		</div>
		<?php
	}
	?>
	
	<div<?php echo $tpl['is_empty'] ? ' style="display:none"' : NULL; ?>>
		<input type="button" value="<?php __('limit_add'); ?>" class="pj-button limit-add b10" />
		<div id="grid"></div>
	</div>
	
	<div id="dialogAddLimit" style="display: none" title="<?php __('limit_add'); ?>"></div>
	<div id="dialogUpdateLimit" style="display: none" title="<?php __('limit_update'); ?>"></div>
	<script type="text/javascript">
	var pjGrid = pjGrid || {};
	pjGrid.jqDateFormat = "<?php echo pjUtil::jqDateFormat($tpl['option_arr']['o_date_format']); ?>";
	pjGrid.jsDateFormat = "<?php echo pjUtil::jsDateFormat($tpl['option_arr']['o_date_format']); ?>";
	var myLabel = myLabel || {};
	myLabel.rooms = <?php echo $tpl['rooms']; ?>;
	myLabel.room = "<?php __('limit_room'); ?>";
	myLabel.date_from = "<?php __('limit_date_from'); ?>";
	myLabel.date_to = "<?php __('limit_date_to'); ?>";
	myLabel.start_on = "<?php __('limit_start_on'); ?>";
	myLabel.min_nights = "<?php __('limit_min_nights'); ?>";
	myLabel.max_nights = "<?php __('limit_max_nights'); ?>";
	myLabel.any = "<?php __('limit_any_day'); ?>";
	myLabel.mon = "<?php echo $days[1]; ?>";
	myLabel.tue = "<?php echo $days[2]; ?>";
	myLabel.wed = "<?php echo $days[3]; ?>";
	myLabel.thu = "<?php echo $days[4]; ?>";
	myLabel.fri = "<?php echo $days[5]; ?>";
	myLabel.sat = "<?php echo $days[6]; ?>";
	myLabel.sun = "<?php echo $days[0]; ?>";
	myLabel.delete_selected = "<?php __('delete_selected'); ?>";
	myLabel.delete_confirmation = "<?php __('delete_confirmation'); ?>";
	</script>
	<?php
}
?>