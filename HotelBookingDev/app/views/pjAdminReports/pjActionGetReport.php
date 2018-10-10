<table class="tblReport" cellpadding="0" cellspacing="0" style="width: 100%;">
	<tr>
		<td class="title-column"><?php __('report_total');?><br><span><?php __('report_bookings_received');?></span></td>
		<td colspan="3" class="content-cell"><label><span><?php echo (int) @$tpl['received_arr']['bookings']?></span><abbr><?php @$tpl['received_arr']['bookings'] != 1 ? __('report_bookings') : __('report_booking'); ?></abbr></label></td>
		<td colspan="3" class="content-cell"><label><span><?php echo (int) @$tpl['received_arr']['guests']?></span><abbr><?php @$tpl['received_arr']['guests'] != 1 ? __('report_guests') : __('report_guest'); ?></abbr></label></td>
		<td colspan="3" class="content-cell"><label><span><?php echo (int) @$tpl['received_arr']['nights']?></span><abbr><?php @$tpl['received_arr']['nights'] != 1 ? __('report_nights') : __('report_night'); ?></abbr></label></td>
		<td colspan="3" class="content-cell"><label><span><?php echo pjUtil::formatCurrencySign(round(@$tpl['received_arr']['total']), $tpl['option_arr']['o_currency']);?></span></label></td>
	</tr>
	<tr>
		<td colspan="13" class="empty-row">&nbsp;</td>
	</tr>
	<tr>
		<td class="title-column"><?php __('report_total');?><br><span><?php __('report_confirmed_bookings');?></span></td>
		<td colspan="3" class="content-cell"><label><span><?php echo (int) @$tpl['confirmed_arr']['bookings'];?></span><abbr><?php @$tpl['confirmed_arr']['bookings'] != 1 ? __('report_bookings') : __('report_booking'); ?></abbr></label></td>
		<td colspan="3" class="content-cell"><label><span><?php echo (int) @$tpl['confirmed_arr']['guests'];?></span><abbr><?php @$tpl['confirmed_arr']['guests'] != 1 ? __('report_guests') : __('report_guest'); ?></abbr></label></td>
		<td colspan="3" class="content-cell"><label><span><?php echo (int) @$tpl['confirmed_arr']['nights'];?></span><abbr><?php @$tpl['confirmed_arr']['nights'] != 1 ? __('report_nights') : __('report_night'); ?></abbr></label></td>
		<td colspan="3" class="content-cell"><label><span><?php echo pjUtil::formatCurrencySign(round(@$tpl['confirmed_arr']['total']), $tpl['option_arr']['o_currency']);?></span></label></td>
	</tr>
	<tr>
		<td colspan="13" class="empty-row">&nbsp;</td>
	</tr>
	<tr>
		<td class="title-column"><?php __('report_total');?><br><span><?php __('report_cancelled_bookings');?></span></td>
		<td colspan="3" class="content-cell"><label><span><?php echo (int) @$tpl['cancelled_arr']['bookings'];?></span><abbr><?php @$tpl['cancelled_arr']['bookings'] != 1 ? __('report_bookings') : __('report_booking'); ?></abbr></label></td>
		<td colspan="3" class="content-cell"><label><span><?php echo (int) @$tpl['cancelled_arr']['guests'];?></span><abbr><?php @$tpl['cancelled_arr']['guests'] != 1 ? __('report_guests') : __('report_guest'); ?></abbr></label></td>
		<td colspan="3" class="content-cell"><label><span><?php echo (int) @$tpl['cancelled_arr']['nights'];?></span><abbr><?php @$tpl['cancelled_arr']['nights'] != 1 ? __('report_nights') : __('report_night'); ?></abbr></label></td>
		<td colspan="3" class="content-cell"><label><span><?php echo pjUtil::formatCurrencySign(@round($tpl['cancelled_arr']['total']), $tpl['option_arr']['o_currency']);?></span></label></td>
	</tr>
	<tr>
		<td colspan="13" class="empty-row">&nbsp;</td>
	</tr>
	<tr>
		<td rowspan="2" class="title-column separator"><?php __('report_rooms'); ?></td>
		<td colspan="4" class="content-cell split"><?php __('report_bookings_received'); ?></td>
		<td colspan="4" class="content-cell split"><?php __('report_confirmed_bookings'); ?></td>
		<td colspan="4" class="content-cell"><?php __('report_cancelled_bookings'); ?></td>
	</tr>
	<tr class="separator">
		<td><?php __('report_booked'); ?></td>
		<td><?php __('report_guests'); ?></td>
		<td><?php __('report_nights'); ?></td>
		<td class="split"><?php __('report_amount'); ?></td>
		<td><?php __('report_booked'); ?></td>
		<td><?php __('report_guests'); ?></td>
		<td><?php __('report_nights'); ?></td>
		<td class="split"><?php __('report_amount'); ?></td>
		<td><?php __('report_booked'); ?></td>
		<td><?php __('report_guests'); ?></td>
		<td><?php __('report_nights'); ?></td>
		<td><?php __('report_amount'); ?></td>
	</tr>
	<?php
	foreach($tpl['room_arr'] as $k => $v)
	{
		?>
		<tr class="<?php echo $k < count($tpl['room_arr']) - 1 ? ' separator' : NULL;?>">
			<td class="title-column"><span><?php echo $v['type'];?></span></td>
			<td class="content-cell"><?php echo !empty($v['bookings']) ? $v['bookings'] : 0;?></td>
			<td class="content-cell"><?php echo !empty($v['guests']) ? $v['guests'] : 0;?></td>
			<td class="content-cell"><?php echo !empty($v['nights']) ? $v['nights'] : 0;?></td>
			<td class="content-cell split"><?php echo pjUtil::formatCurrencySign(!empty($v['total']) ? round($v['total']) : 0, $tpl['option_arr']['o_currency']);?></td>
			<td class="content-cell"><?php echo !empty($tpl['room_confirmed_arr'][$k]['bookings']) ? $tpl['room_confirmed_arr'][$k]['bookings'] : 0;?></td>
			<td class="content-cell"><?php echo !empty($tpl['room_confirmed_arr'][$k]['guests']) ? $tpl['room_confirmed_arr'][$k]['guests'] : 0;?></td>
			<td class="content-cell"><?php echo !empty($tpl['room_confirmed_arr'][$k]['nights']) ? $tpl['room_confirmed_arr'][$k]['nights'] : 0;?></td>
			<td class="content-cell split"><?php echo pjUtil::formatCurrencySign(!empty($tpl['room_confirmed_arr'][$k]['total']) ? round($tpl['room_confirmed_arr'][$k]['total']) : 0, $tpl['option_arr']['o_currency']);?></td>
			<td class="content-cell"><?php echo !empty($tpl['room_cancelled_arr'][$k]['bookings']) ? $tpl['room_cancelled_arr'][$k]['bookings'] : 0;?></td>
			<td class="content-cell"><?php echo !empty($tpl['room_cancelled_arr'][$k]['guests']) ? $tpl['room_cancelled_arr'][$k]['guests'] : 0;?></td>
			<td class="content-cell"><?php echo !empty($tpl['room_cancelled_arr'][$k]['nights']) ? $tpl['room_cancelled_arr'][$k]['nights'] : 0;?></td>
			<td class="content-cell split"><?php echo pjUtil::formatCurrencySign(!empty($tpl['room_cancelled_arr'][$k]['total']) ? round($tpl['room_cancelled_arr'][$k]['total']) : 0, $tpl['option_arr']['o_currency']);?></td>
		</tr>
		<?php
	} 
	?>
	<tr>
		<td colspan="13" class="empty-row">&nbsp;</td>
	</tr>
	<tr class="height_row separator">
		<td colspan="9" class="title-column"><?php __('report_rooms_per_booking'); ?></td>
		<td colspan="3" class="title-column"><span class="normal"><?php __('report_bookings'); ?></span></td>
		<td class="title-column"><span class="normal">%</span></td>
	</tr>
	<?php
	$total = $tpl['room_per_arr'][0]['one_room'] + $tpl['room_per_arr'][0]['two_room'] + $tpl['room_per_arr'][0]['more_room'];
	$one_percent = $two_percent = $more_percent = '0%';
	if($total > 0)
	{
		$one_percent = round($tpl['room_per_arr'][0]['one_room'] * 100 / $total) . '%';
		$two_percent = round($tpl['room_per_arr'][0]['two_room'] * 100 / $total) . '%';
		$more_percent = round($tpl['room_per_arr'][0]['more_room'] * 100 / $total) . '%';
	}
	?>
	<tr class="height_row separator">
		<td colspan="9" class="title-column"><span><?php __('report_one_room'); ?></span></td>
		<td colspan="3" class="content-column"><span class="normal"><?php echo $tpl['room_per_arr'][0]['one_room'];?></span></td>
		<td class="content-column"><span class="normal"><?php echo $one_percent;?></span></td>
	</tr>
	<tr class="height_row separator">
		<td colspan="9" class="title-column"><span><?php __('report_two_room'); ?></span></td>
		<td colspan="3" class="content-column"><span class="normal"><?php echo $tpl['room_per_arr'][0]['two_room'];?></span></td>
		<td class="content-column"><span class="normal"><?php echo $two_percent;?></span></td>
	</tr>
	<tr class="height_row">
		<td colspan="9" class="title-column"><span><?php __('report_more_room'); ?></span></td>
		<td colspan="3" class="content-column"><span class="normal"><?php echo $tpl['room_per_arr'][0]['more_room'];?></span></td>
		<td class="content-column"><span class="normal"><?php echo $more_percent;?></span></td>
	</tr>
	<tr>
		<td colspan="13" class="empty-row">&nbsp;</td>
	</tr>
	<tr class="height_row separator">
		<td colspan="9" class="title-column"><?php __('report_nights_per_booking'); ?></td>
		<td colspan="3" class="title-column"><span class="normal"><?php __('report_bookings'); ?></span></td>
		<td class="title-column"><span class="normal">%</span></td>
	</tr>
	<?php
	foreach(__('report_nights_arr', true) as $k => $v)
	{
		$percent = '0%';
		if ($tpl['total_nights'] > 0)
		{
			$percent = round(@$tpl['night_arr'][$k] * 100 / $tpl['total_nights']) . '%';
		}
		?>
		<tr class="height_row separator">
			<td colspan="9" class="title-column"><span><?php echo $v;?></span></td>
			<td colspan="3" class="content-column"><span class="normal"><?php echo isset($tpl['night_arr'][$k]) ? $tpl['night_arr'][$k] : 0;?></span></td>
			<td class="content-column"><span class="normal"><?php echo $percent;?></span></td>
		</tr>
		<?php
	} 
	?>
	<tr>
		<td colspan="13" class="empty-row">&nbsp;</td>
	</tr>
	<tr class="height_row separator">
		<td colspan="9" class="title-column"><?php __('report_guests_per_booking');?> &nbsp;<span class="smaller"><?php __('report_only_adults');?></span></td>
		<td colspan="3" class="title-column"><span class="normal"><?php __('report_bookings'); ?></span></td>
		<td class="title-column"><span class="normal">%</span></td>
	</tr>
	<?php
	foreach(__('report_guests_arr', true) as $k => $v)
	{
		$percent = '0%';
		if ($tpl['total_bookings'] > 0)
		{
			$percent = round(@$tpl['guest_arr'][$k] * 100 / $tpl['total_bookings']) . '%';
		}
		?>
		<tr class="height_row separator">
			<td colspan="9" class="title-column"><span><?php echo $v;?></span></td>
			<td colspan="3" class="content-column"><span class="normal"><?php echo isset($tpl['guest_arr'][$k]) ? $tpl['guest_arr'][$k] : 0;?></span></td>
			<td class="content-column"><span class="normal"><?php echo $percent;?></span></td>
		</tr>
		<?php
	} 
	?>
	<tr>
		<td colspan="13" class="empty-row">&nbsp;</td>
	</tr>
	
	<tr class="height_row separator">
		<td colspan="9" class="title-column"><?php __('report_adults_vs_children'); ?></td>
		<td colspan="3" class="title-column"><span class="normal"><?php __('report_count'); ?></span></td>
		<td class="title-column"><span class="normal">%</span></td>
	</tr>
	<?php
	$percent = '0%';
	if($tpl['total_guests'] > 0)
	{
		$percent = round($tpl['total_adults'] * 100 / $tpl['total_guests']) . '%';
	} 
	?>
	<tr class="height_row separator">
		<td colspan="9" class="title-column"><span><?php __('report_adults_guests'); ?></span></td>
		<td colspan="3" class="content-column"><span class="normal"><?php echo $tpl['total_adults'];?></span></td>
		<td class="content-column"><span class="normal"><?php echo $percent;?></span></td>
	</tr>
	<?php
	$percent = '0%';
	if($tpl['total_guests'] > 0)
	{
		$percent = round($tpl['total_children'] * 100 / $tpl['total_guests']) . '%';
	} 
	?>
	<tr class="height_row">
		<td colspan="9" class="title-column"><span><?php __('report_children_guests'); ?></span></td>
		<td colspan="3" class="content-column"><span class="normal"><?php echo $tpl['total_children'];?></span></td>
		<td class="content-column"><span class="normal"><?php echo $percent;?></span></td>
	</tr>
</table>
<br/>
<?php
if ($_GET['action'] != 'pjActionPrintReport')
{ 
	?>
	<a target="_blank" href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminReports&action=pjActionPrintReport&from=<?php echo isset($_GET['from']) ? $_GET['from'] : $created_date; ?>&to=<?php echo isset($_GET['to']) ? $_GET['to'] : $current_date; ?>" class="pj-button"><?php __('btnPrint'); ?></a>
	<br/><br/>
	<?php
}
?>