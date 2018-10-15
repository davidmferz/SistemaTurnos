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
	include PJ_VIEWS_PATH . 'pjAdminRooms/elements/menu.php';

	if (isset($_GET['err']))
	{
		pjUtil::printNotice(@$titles[$_GET['err']], @$bodies[$_GET['err']], true, true, true);
	}
	pjUtil::printNotice(@$titles['AR09'], @$bodies['AR09']);
	
	if ($tpl['is_empty'])
	{
		?>
		<div class="empty-page">
			<div class="empty-text"><?php __('rooms_empty_msg'); ?></div>
			<div class="empty-btn"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminRooms&amp;action=pjActionCreate" class="pj-button"><?php __('room_add'); ?></a></div>
		</div>
		<?php
	} else {
		?>
		<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get" class="b10">
			<input type="hidden" name="controller" value="pjAdminRooms" />
			<input type="hidden" name="action" value="pjActionCreate" />
			<button type="submit" class="pj-button"><?php __('room_add'); ?></button>
		</form>
	
		<div id="grid"></div>
		<script type="text/javascript">
		var myLabel = myLabel || {};
		myLabel.image = "<?php __('rooms_image', false, true); ?>";
		myLabel.name = "<?php __('rooms_name', false, true); ?>";
		myLabel.adults = "<?php __('rooms_adults', false, true); ?>";
		myLabel.children = "<?php __('rooms_children', false, true); ?>";
		myLabel.cnt = "<?php __('rooms_cnt', false, true); ?>";
		myLabel.delete_selected = "<?php __('delete_selected', false, true); ?>";
		myLabel.delete_confirmation = "<?php __('delete_confirmation', false, true); ?>";
		</script>
		<?php
	}
}
?>