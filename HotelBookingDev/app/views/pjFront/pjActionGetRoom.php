<?php
if (!isset($item))
{
	$item = $tpl['room_arr'];
}
if (!isset($STORE))
{
	$STORE = @$_SESSION[$controller->defaultStore];
}

$limited = false;
$min_nights = null;
$max_nights = null;

if (isset($tpl['limit_arr']) && isset($STORE['_nights']) && isset($STORE['_start_on']))
{
	foreach ($tpl['limit_arr'] as $limit)
	{
		if ($limit['room_id'] != $item['id'])
		{
			continue;
		}
		$outOfRange = ($STORE['_nights'] < $limit['min_nights'] || $STORE['_nights'] > $limit['max_nights']);
		if ($limit['start_on'] == 7 && $outOfRange)
		{
			$limited = true;
			if($STORE['_nights'] < $limit['min_nights'])
			{
				$min_nights = $limit['min_nights'];
			}
			if($STORE['_nights'] > $limit['max_nights'])
			{
				$max_nights = $limit['max_nights'];
			}
			break;
		}
		if ($limit['start_on'] != 7 && $limit['start_on'] == $STORE['_start_on'] && $outOfRange)
		{
			$limited = true;
			if($STORE['_nights'] < $limit['min_nights'])
			{
				$min_nights = $limit['min_nights'];
			}
			if($STORE['_nights'] > $limit['max_nights'])
			{
				$max_nights = $limit['max_nights'];
			}
			break;
		}
	}
}
?>
<br>
<div class="panel panel-default pjHbProduct">
	<div class="panel-heading">
		<strong><?php echo pjSanitize::html($item['name']); ?></strong>
	</div><!-- /.panel-heading -->
	
	<div class="panel-body">
		<div class="row">
			<div class="col-md-4 col-sm-12 col-xs-12">
				<?php 
				if ($item['cnt'] > $item['max_bookings'] + $item['unavailable_cnt'] && !$limited)
				{
					?>
					<div class="row">
						<div class="col-sm-6 col-xs-12"><?php __('front_rooms_select'); ?></div><!-- /.col-md-6 -->
						<div class="col-sm-6 col-xs-12">
							<select name="room_id[<?php echo $item['id']; ?>]" class="form-control hbSelectorRoomCnt" data-id="<?php echo $item['id']; ?>">
								<?php
								$cnt = isset($_GET['cnt']) && (int) $_GET['cnt'] > 0 ? (int) $_GET['cnt'] : count(@$STORE['all_rooms'][$item['id']]);
								foreach (range(0, $item['cnt'] - $item['max_bookings'] - $item['unavailable_cnt']) as $i)
								{
									?><option value="<?php echo $i; ?>"<?php echo $cnt != $i ? NULL : ' selected="selected"'; ?>><?php echo $i; ?></option><?php
								}
								?>
							</select>
							<a href="#" class="hbSelectorEditRoom" style="display: <?php echo $cnt > 0 ? NULL : 'none'; ?>"><?php __('front_edit_room'); ?></a>
						</div><!-- /.col-md-6 -->
					</div><!-- /.row -->
					<?php
				} else {
					?>
					<div class="row">
						<div class="col-sm-12 col-xs-12">
							<?php
							if($limited == false)
							{ 
								__('front_na');
							}else{
								if($min_nights == null && $max_nights == null)
								{
									__('front_na');
								}else{
									if($min_nights != null)
									{
										printf(__('front_min_booking_nights', true),$min_nights);
									}
									if($max_nights != null)
									{
										printf(__('front_max_booking_nights', true),$max_nights);
									}
								}
								
							} 
							?>
						</div><!-- /.col-sm-12 -->
					</div><!-- /.row -->
					<?php
				}
				$max_icons = array();
				for($i = 1; $i <= (int) $item['max_people']; $i++)
				{
					$max_icons[] = '<span class="glyphicon glyphicon-user"  aria-hidden="true"></span>';
				}
				$max_occupancy = (int) $item['max_people'] != 1 ? __('front_accommodation_up_to_guests', true) : __('front_accommodation_up_to_guest', true);
				$max_occupancy = str_replace("{MAX}", join("", $max_icons), $max_occupancy);
				if((int) $item['max_people'] == 1)
				{
					$max_occupancy = str_replace("1", '<span class="glyphicon glyphicon-user"  aria-hidden="true"></span>', $max_occupancy);
				}
				?>
				<hr>
				<div class="row">
					<div class="col-sm-12 col-xs-12 pjHbAccommodate"><strong><?php echo $max_occupancy; ?></strong></div>
					
				</div><!-- /.row -->
				<?php 
				if ($item['cnt'] > $item['max_bookings'] && !$limited)
				{
					if (isset($STORE['content']) && isset($STORE['content'][$item['id']]) &&
						!empty($STORE['content'][$item['id']])/* && !isset($_GET['adults']) && !isset($_GET['children'])*/)
					{
						?>
						<hr>
						<div class="well">
						<?php
						foreach ($STORE['content'][$item['id']] as $index => $data)
						{
							printf('<p>%u %s, %u %s x <span class="pjHbProductPrice">%s</span></p>',
								$data['adults'],
								$data['adults'] != 1 ? pjMultibyte::strtolower(__('front_adults', true)) : pjMultibyte::strtolower(__('front_adult', true)),
								$data['children'],
								$data['children'] != 1 ? pjMultibyte::strtolower(__('front_children', true)) : pjMultibyte::strtolower(__('front_child', true)),
								$data['price']
							);
						}
						?>
							<input type="button" class="btn btn-default hbSelectorExtras" value="<?php __('front_btn_continue'); ?>" />
						</div>
						<?php
					} else {
						?>
						<hr>
						<div class="row">
							<div class="col-sm-6"><?php __('front_price_from'); ?></div><!-- /.col-md-6 -->
							<div class="col-sm-6"><strong class="pjHbProductPrice"><?php echo pjUtil::formatCurrencySign(number_format($item['price_from'], 0), $tpl['option_arr']['o_currency']); ?></strong></div><!-- /.col-md-6 -->
						</div><!-- /.row -->
						<?php
					}
				}
				?>
				<br>
			</div><!-- /.col-md-4 -->

			<div class="col-md-8 col-sm-12 col-xs-12">
			<?php
			if (isset($_GET['cnt']) && $_GET['cnt'] > 0)
			{
				?>
				<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
					<input type="hidden" name="room_id" value="<?php echo $tpl['room_arr']['id']; ?>" />
					<div class="table-responsive">
					<table class="table">
						<thead>
							<tr>
								<th><?php __('front_rooms'); ?></th>
								<th><?php __('front_adults'); ?></th>
								<th><?php __('front_children'); ?></th>
								<th class="text-right text-default"><?php __('front_price'); ?> <span class="text-danger">*</span></th>
							</tr>
						</thead>
						<tbody>
						<?php
						$total = 0;
						foreach (range(1, $_GET['cnt']) as $j)
						{
							?>
							<tr data-max="<?php echo (int) $item['max_people'];?>">
								<td><?php __('front_room'); ?> <?php echo $j; ?></td>
								<td>
									<select class="form-control hbSelectorPeople" name="adults[]" data-index="<?php echo $j; ?>">
									<?php
									foreach (range(1, $tpl['room_arr']['adults']) as $i)
									{
										$selected = (
											isset($STORE['all_rooms'][$_GET['room_id']][$j]) &&
											$STORE['all_rooms'][$_GET['room_id']][$j]['adults'] == $i
										);
										?><option value="<?php echo $i; ?>"<?php echo !$selected ? NULL : ' selected="selected"'; ?>><?php echo $i; ?></option><?php
									}
									?>
									</select>
								</td>
								<td>
									<select class="form-control hbSelectorPeople" name="children[]"  data-index="<?php echo $j; ?>">
									<?php
									foreach (range(0, $tpl['room_arr']['children']) as $i)
									{
										$selected = (
											isset($STORE['all_rooms'][$_GET['room_id']][$j]) &&
											$STORE['all_rooms'][$_GET['room_id']][$j]['children'] == $i
										);
										?><option value="<?php echo $i; ?>"<?php echo !$selected ? NULL : ' selected="selected"'; ?>><?php echo $i; ?></option><?php
									}
									?>
									</select>
								</td>
								<td class="text-right hbSelectorPrice"><?php
								if (isset($STORE['content'][$_GET['room_id']][$j]['raw_price']))
								{
									$price = $STORE['content'][$_GET['room_id']][$j]['raw_price'];
								} else {
									$price = $STORE['rooms'][$tpl['room_arr']['id']][$j]['price'];
								}
								echo pjUtil::formatCurrencySign(number_format($price, 2), $tpl['option_arr']['o_currency']);
								?></td>
							</tr>
							<?php 
							$total += $price;
						}
						?>
						</tbody>
					</table><!-- /.table -->
					</div><!-- /.table-responsive -->
					<hr>
					<p class="text-right">
						<button type="button" class="btn btn-default hbSelectorCancelRoom"><?php __('front_btn_cancel'); ?></button>
						<?php if ((int) $tpl['option_arr']['o_accept_bookings'] === 1) : ?>
						<button type="button" class="btn btn-default hbSelectorBook"><?php __('front_btn_book'); ?></button>
						<?php endif; ?>
					</p>
					<p><small><span class="text-danger">*</span><?php __('front_price_note'); ?></small></p>
				</form>
				<?php 
			} else {
				$src = PJ_INSTALL_URL . PJ_IMG_PATH . 'frontend/hb-noimg.jpg';
				if (!empty($item['image']) && is_file($item['image']))
				{
					$src = PJ_INSTALL_URL . $item['image'];
				}
				?>
				<div class="row">
					<div class="col-sm-5 col-xs-12">
						<img src="<?php echo $src; ?>" class="img-responsive hbSelectorImg" alt="<?php echo pjSanitize::html($item['name']); ?>">
						<br>
					</div><!-- /.col-md-5 col-xs-12 -->
					<div class="col-sm-7 col-xs-12">
					<?php
					if (isset($item['gallery']) && !empty($item['gallery']))
					{
						foreach ($item['gallery'] as $key => $pic)
						{
							?><a href="<?php echo PJ_INSTALL_URL . @$item['large'][$key]; ?>" rel="group-<?php echo $item['id']; ?>" class="hbSelectorThumb" data-path="<?php echo PJ_INSTALL_URL . @$item['medium'][$key]; ?>"><img src="<?php echo PJ_INSTALL_URL . $pic; ?>" alt="<?php echo pjSanitize::html(@$item['alt'][$key]); ?>" class="img-thumbnail"></a>
							<?php 
						}
					}
					?>
					</div><!-- /.col-md-7 col-xs-12 -->
				</div><!-- /.row -->
				<hr>
				<p><?php echo nl2br(pjSanitize::html($item['description'])); ?></p>
				<?php
			}
			?>
			</div><!-- /.col-md-8 -->
		</div><!-- /.row -->
	</div><!-- /.panel-body -->
</div><!-- /.panel -->