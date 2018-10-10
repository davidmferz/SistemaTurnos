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
	$jqDateFormat = pjUtil::jqDateFormat($tpl['option_arr']['o_date_format']);
	$current_user = $_SESSION[$controller->defaultUser];
	$created_date = pjUtil::formatDate(date('Y-m-d', strtotime($current_user['created'])), 'Y-m-d', $tpl['option_arr']['o_date_format']);
	$current_date = pjUtil::formatDate(date('Y-m-d'), 'Y-m-d', $tpl['option_arr']['o_date_format']);

	$titles = __('error_titles', true);
	$bodies = __('error_bodies', true);
	pjUtil::printNotice(@$titles['ART01'], @$bodies['ART01']);
	?>
	<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get" class="form">
		<input type="hidden" name="controller" value="pjAdminReports" />
		<input type="hidden" name="action" value="pjActionIndex" />

		<p>
			<label class="title"><?php __('booking_arrival_date'); ?></label>
			<span class="pj-form-field-custom pj-form-field-custom-after">
				<input type="text" name="from" class="pj-form-field w80 datepick pointer required" data-msg-required="<?php __('lblFieldRequired');?>" readonly="readonly" rev="<?php echo $jqDateFormat; ?>" value="<?php echo isset($_GET['from']) ? $_GET['from'] : $created_date; ?>" />
				<span class="pj-form-field-after"><abbr class="pj-form-field-icon-date"></abbr></span>
			</span>
		</p>
		<p>
			<label class="title"><?php __('booking_departure_date'); ?></label>
			<span class="pj-form-field-custom pj-form-field-custom-after">
				<input type="text" name="to" class="pj-form-field w80 datepick pointer required" data-msg-required="<?php __('lblFieldRequired');?>" readonly="readonly" rev="<?php echo $jqDateFormat; ?>" value="<?php echo isset($_GET['to']) ? $_GET['to'] : $current_date; ?>" />
				<span class="pj-form-field-after"><abbr class="pj-form-field-icon-date"></abbr></span>
			</span>
		</p>
		<p>
			<label class="title">&nbsp;</label>
			<input type="submit" value="<?php __('btnGenerate', false, true); ?>" class="hbButton button_generate pj-button" />
		</p>
	</form>
	<?php
	if (isset($_GET['from'], $_GET['to']))
	{ 
		include dirname(__FILE__) . '/pjActionGetReport.php';
	}
}
?>