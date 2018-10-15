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
	if (isset($_GET['err']))
	{
		$titles = __('error_titles', true);
		$bodies = __('error_bodies', true);
		pjUtil::printNotice(@$titles[$_GET['err']], @$bodies[$_GET['err']], true, true, true);
	}
	$ep = __('extra_per', true);
	$filter = __('filter', true);
	
	include PJ_VIEWS_PATH . 'pjAdminRooms/elements/menu.php';
	pjUtil::printNotice(__('extra_index_title', true), __('extra_index_body', true));
	
	if ($tpl['is_empty'])
	{
		?>
		<div class="empty-page">
			<div class="empty-text"><?php __('extra_empty_msg'); ?></div>
			<div class="empty-btn"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminExtras&amp;action=pjActionCreate" class="pj-button"><?php __('extra_add'); ?></a></div>
		</div>
		<?php
	} else {
		?>
		<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get">
			<input type="hidden" name="controller" value="pjAdminExtras" />
			<input type="hidden" name="action" value="pjActionCreate" />
			<button type="submit" class="pj-button"><?php __('extra_add'); ?></button>
			<p>&nbsp;</p>
		</form>

		<div id="grid"></div>
		<script type="text/javascript">
		var myLabel = myLabel || {};
		myLabel.extra = "<?php __('extra_name'); ?>";
		myLabel.per = "<?php __('extra_per_lbl'); ?>";
		myLabel.price = "<?php __('extra_price'); ?>";
		myLabel.status = "<?php __('lblStatus'); ?>";
		myLabel.per_day = "<?php echo $ep['day']; ?>";
		myLabel.per_person = "<?php echo $ep['person']; ?>";
		myLabel.per_day_person = "<?php echo $ep['day_person']; ?>";
		myLabel.per_booking = "<?php echo $ep['booking']; ?>";
		myLabel.active = "<?php echo $filter['active']; ?>";
		myLabel.inactive = "<?php echo $filter['inactive']; ?>";
		myLabel.delete_selected = "<?php __('delete_selected'); ?>";
		myLabel.delete_confirmation = "<?php __('delete_confirmation'); ?>";
		</script>
		<?php
	}
}
?>