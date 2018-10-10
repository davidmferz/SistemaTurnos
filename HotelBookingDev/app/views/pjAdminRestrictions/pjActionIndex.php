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
	pjUtil::printNotice(@$titles['ART10'], @$bodies['ART10']);
	
	if ($tpl['is_empty'])
	{
		?>
		<div class="empty-page">
			<div class="empty-text"><?php __('restriction_empty_msg'); ?></div>
			<div class="empty-btn"><a href="<?php echo $_SERVER['PHP_SELF']; ?>" class="pj-button restriction-add"><?php __('restriction_add'); ?></a></div>
		</div>
		<?php
	}
	?>
	
	<div<?php echo $tpl['is_empty'] ? ' style="display:none"' : NULL; ?>>
		<input type="button" value="<?php __('restriction_add'); ?>" class="pj-button restriction-add b10" />
		<div id="grid"></div>
	</div>
	
	<div id="dialogAddRestriction" style="display: none" title="<?php __('restriction_add'); ?>"></div>
	<div id="dialogUpdateRestriction" style="display: none" title="<?php __('restriction_update'); ?>"></div>
	<div id="dialogErrorRestriction" style="display: none" title=""></div>
	<script type="text/javascript">
	var pjGrid = pjGrid || {};
	pjGrid.jqDateFormat = "<?php echo pjUtil::jqDateFormat($tpl['option_arr']['o_date_format']); ?>";
	pjGrid.jsDateFormat = "<?php echo pjUtil::jsDateFormat($tpl['option_arr']['o_date_format']); ?>";
	var myLabel = myLabel || {};
	myLabel.room = <?php __encode('restriction_rooms'); ?>;
	myLabel.date_from = <?php __encode('restriction_date_from'); ?>;
	myLabel.room_count = <?php echo $tpl['room_count']; ?>;
	myLabel.date_to = <?php __encode('restriction_date_to'); ?>;
	myLabel.types = <?php echo $tpl['types']; ?>;
	myLabel.type = <?php __encode('restriction_type'); ?>;
	myLabel.delete_selected = <?php __encode('delete_selected'); ?>;
	myLabel.delete_confirmation = <?php __encode('delete_confirmation'); ?>;
	</script>
	<?php
}
?>