<?php
if (isset($_GET['err']))
{
	if (empty($tpl['room_arr']))
	{
		?><div class="hbPrintTitle">Room type with such ID doesn't exist.</div><?php
	} else {
		?><div class="hbPrintTitle">You don't have permission to print calendar of this room type.</div><?php
	}
}else{
 
	?>
	<div class="hbPrintHeading">Print Calendar</div>
	<?php
	$week_start_date = $tpl['week_start_date'];
	$week_end_date = $tpl['week_end_date']; 
	if (isset($_GET['room_id']) && !empty($_GET['room_id']))
	{
		?>
		<div class="hbPrintTitle"><?php __('room_limit_select_room'); ?>: <?php echo $tpl['room_arr']['type'];?></div>
		<?php
	}
	?>
	<table class="tblCalendar" cellpadding="0" cellspacing="0" style="width: 100%;">
		<tr class="hbMonthRow">
			<td style="width:216px;">&nbsp;</td>
			<td colspan="14" align="center"><?php echo $tpl['month_label'];?></td>
		</tr>
		<tr class="hbDayRow">
			<td><b><?php __('booking_room'); ?></b></td>
			<?php
				$selected_date = date('Y-m-d');
				if (isset($_GET['selected_date']) && !empty($_GET['selected_date']))
				{
					$selected_date = pjUtil::formatDate($_GET['selected_date'], $tpl['option_arr']['o_date_format']);
				}
				$_columns = array();
				for($i = 0; $i < 7; $i++)
				{
					$week_date_timestamp = strtotime($tpl['week_start_date'] . " +$i days");
					?><td colspan="2" class="day<?php echo $week_date_timestamp == strtotime($selected_date) ? ' hbDateFocus' : null; ?>"><span><?php echo date('d', $week_date_timestamp); ?></span><br/><?php echo date('D', $week_date_timestamp); ?></td><?php
					$_columns[$i] = $week_date_timestamp;
				} 
				?>
			</tr>
			<?php
			$start_timestamp = strtotime($tpl['week_start_date']);
						
			foreach($tpl['room_number_arr'] as $v)
			{ 
				?>
				<tr class="hbRoomRow">
					<td><span class="hbNumber"><?php echo $v['number']; ?></span><span class="hbType"><?php echo $v['type']; ?></span></td>
					<?php
					if(isset($tpl['rows'][$v['id']]))
					{
						$column_arr = $tpl['rows'][$v['id']];
						foreach($column_arr as $col)
						{
							if(!empty($col['content']))
							{
								$content = $col['content'];
								$booking_id = $content['id'];
								$name_arr = array();
	
								if(!empty($content['c_fname']))
								{
									$name_arr[] = pjSanitize::clean($content['c_fname']);
								}
								if(!empty($content['c_lname']))
								{
									$name_arr[] = pjSanitize::clean($content['c_lname']);
								}
								if(!empty($name_arr))
								{
									?><td colspan="<?php echo $col['colspan']?>" class="hb<?php echo $content['status'];?>"><?php echo join(" ", $name_arr) . '<br/>ID: ' . $content['uuid'];?></td><?php
								}else{
									?><td colspan="<?php echo $col['colspan']?>" class="hb_<?php echo $content['restriction_type'];?>"><?php echo @$content['restriction_type'];?></td><?php
								}
							}else{
								?><td colspan="<?php echo $col['colspan']?>">&nbsp;</td><?php
							}
						}
					}else{ 
						foreach($_columns as $timestamp)
						{
							?><td colspan="2">&nbsp;</td><?php
						}
					} 
					?>
				</tr>
				
				<?php
			} 
			?>
		<tr class="hbInvisibleRow">
			<td style="width:216px;">&nbsp;</td>
			<?php
			for($i = 0; $i < 14; $i++)
			{
				?><td style="width: 52px;">&nbsp;</td><?php
			} 
			?>
		</tr>
	</table>
	<?php
} 
?>