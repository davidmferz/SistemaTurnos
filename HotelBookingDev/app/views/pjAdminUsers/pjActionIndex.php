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
	$u_statarr = __('u_statarr', true);
	?>
	<div class="ui-tabs ui-widget ui-widget-content ui-corner-all b10">
		<ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">
			<li class="ui-state-default ui-corner-top ui-tabs-active ui-state-active"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminUsers&amp;action=pjActionIndex"><?php __('menuUsers'); ?></a></li>
			<li class="ui-state-default ui-corner-top"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminUsers&amp;action=pjActionCreate"><?php __('lblAddUser'); ?></a></li>
		</ul>
	</div>
	
	<?php
	pjUtil::printNotice(@$titles['AU11'], @$bodies['AU11']);
	if ($tpl['is_empty'])
	{
		?>
		<div class="empty-page">
			<div class="empty-text"><?php __('users_empty'); ?></div>
			<div class="empty-btn"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminUsers&amp;action=pjActionCreate" class="pj-button"><?php __('users_add'); ?></a></div>
		</div>
		<?php
	} else {
		?>
		<div class="b10">
			<form action="" method="get" class="float_left pj-form frm-filter">
				<input type="text" name="q" class="pj-form-field pj-form-field-search w150" placeholder="<?php __('btnSearch'); ?>" />
			</form>
			<?php
			$filter = __('filter', true);
			?>
			<div class="float_right t5">
				<a href="#" class="pj-button btn-all"><?php __('lblAll'); ?></a>
				<a href="#" class="pj-button btn-filter btn-status" data-column="status" data-value="T"><?php echo $filter['active']; ?></a>
				<a href="#" class="pj-button btn-filter btn-status" data-column="status" data-value="F"><?php echo $filter['inactive']; ?></a>
			</div>
			<br class="clear_both" />
		</div>
	
		<div id="grid"></div>
		<script type="text/javascript">
		var pjGrid = pjGrid || {};
		pjGrid.jsDateFormat = "<?php echo pjUtil::jsDateFormat($tpl['option_arr']['o_date_format']); ?>";
		pjGrid.currentUserId = <?php echo (int) $_SESSION[$controller->defaultUser]['id']; ?>;
		var myLabel = myLabel || {};
		myLabel.name = "<?php __('lblName'); ?>";
		myLabel.email = "<?php __('email'); ?>";
		myLabel.created = "<?php __('lblUserCreated'); ?>";
		myLabel.role = "<?php __('lblRole'); ?>";
		myLabel.confirmed = "<?php __('lblIsActive'); ?>";
		myLabel.revert_status = "<?php __('revert_status'); ?>";
		myLabel.exported = "<?php __('lblExport'); ?>";
		myLabel.active = "<?php echo $u_statarr['T']; ?>";
		myLabel.inactive = "<?php echo $u_statarr['F']; ?>";
		myLabel.delete_selected = "<?php __('delete_selected'); ?>";
		myLabel.delete_confirmation = "<?php __('delete_confirmation'); ?>";
		myLabel.status = "<?php __('lblStatus'); ?>";
		</script>
		<?php
	}
}
?>