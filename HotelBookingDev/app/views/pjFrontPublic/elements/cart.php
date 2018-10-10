<h3 class="pjHbPanelBookingTitle"><?php __('front_your_booking'); ?></h3>

<div class="row">
	<div class="col-xs-6">&nbsp;</div><!-- /.col-md-6 -->

	<div class="col-xs-6 text-right" style="white-space: nowrap;">
	<a href="#" class="hbSelectorSearch"><?php __('front_change_dates'); ?></a>
	</div><!-- /.col-md-6 -->
	<?php 
	if (isset($STORE['date_from']) && isset($STORE['date_to']))
	{
		?>
		<div class="col-xs-6" style="white-space: nowrap;">
		<?php __('front_check_in'); ?>:
		</div><!-- /.col-md-6 -->

		<div class="col-xs-6 text-right pjHbPanelBookingMeta" style="white-space: nowrap;">
			<?php echo pjUtil::formatDate($STORE['date_from'], 'Y-m-d', $tpl['option_arr']['o_date_format']); ?>
		</div><!-- /.col-md-6 -->

		<div class="col-xs-6" style="white-space: nowrap;">
		<?php __('front_check_out'); ?>:
		</div><!-- /.col-md-6 -->

		<div class="col-xs-6 text-right pjHbPanelBookingMeta" style="white-space: nowrap;">
		<?php echo pjUtil::formatDate($STORE['date_to'], 'Y-m-d', $tpl['option_arr']['o_date_format']); ?>
		</div><!-- /.col-md-6 -->

		<div class="col-xs-6">
		<?php __('front_for'); ?>:
		</div><!-- /.col-md-6 -->

		<div class="col-xs-6 text-right pjHbPanelBookingMeta" style="white-space: nowrap;">
		<?php
		printf("%u %s, %u %s",
			$STORE['_rooms'],
			(int) $STORE['_rooms'] === 1 ? __('front_room_singular', true) : __('front_room_plural', true),
			$STORE['_nights'],
			(int) $STORE['_nights'] === 1 ? __('front_night_singular', true) : __('front_night_plural', true)
		);
		?>
		</div><!-- /.col-md-6 -->
		<?php 
	}
	?>
</div><!-- /.row -->
<?php
if (isset($STORE['content']))
{
	foreach ($STORE['content'] as $room_id => $item)
	{
		?>
		<hr>
		<div class="row">
			<div class="col-sm-6"><strong><?php echo pjSanitize::html($tpl['room_arr'][$room_id]['name']); ?></strong></div><!-- /.col-md-6 -->
			<div class="col-sm-6 text-right"><a href="#" class="hbSelectorRooms"><?php __('front_change_rooms'); ?></a></div><!-- /.col-sm-6 -->
		</div><!-- /.row -->
		<div class="row">
		<?php
		foreach ($item as $index => $info)
		{
			?>
			<div class="col-sm-6"><?php printf("%u %s, %u %s",
				$info['adults'],
				$info['adults'] != 1 ? pjMultibyte::strtolower(__('front_adults', true)) : pjMultibyte::strtolower(__('front_adult', true)),
				$info['children'],
				$info['children'] != 1 ? pjMultibyte::strtolower(__('front_children', true)) : pjMultibyte::strtolower(__('front_child', true))
			);
			?></div><!-- /.col-sm-6 -->
			<div class="col-sm-6 text-right pjHbPanelBookingMeta"><?php
			echo isset($tpl['rooms_price_stack'][$room_id][$index]) ?
				pjUtil::formatCurrencySign(number_format($tpl['rooms_price_stack'][$room_id][$index], 2), $tpl['option_arr']['o_currency']) :
				$info['price'];
			?></div><!-- /.col-sm-6 -->
			<?php
		}
		?></div><?php
	}
}
if (isset($STORE['extras']) && !empty($STORE['extras']))
{
	?>
	<hr>
	<div class="row">
		<div class="col-sm-6"><strong><?php __('front_extras'); ?></strong></div>
	</div>
	<?php
	$extra_per = __('extra_per', true);
	foreach ($STORE['extras'] as $extra_id => $extra)
	{
		switch ($extra['per'])
		{
			case 'day':
				$extraCost = $extra['price'] * $STORE['_nights'];
				break;
			case 'booking':
				$extraCost = $extra['price'];
				break;
			case 'person':
				$extraCost = $extra['price'] * $STORE['_persons'];
				break;
			case 'day_person':
				$extraCost = $extra['price'] * $STORE['_nights'] * $STORE['_persons'];
				break;
		}
		?>
		<div class="row">
			<div class="col-sm-8"><?php echo pjSanitize::html($extra['name']); ?> (<?php echo @$extra_per[$extra['per']]; ?>)</div>
			<div class="col-sm-4 text-right pjHbPanelBookingMeta"><?php echo pjUtil::formatCurrencySign(number_format($extraCost, 2), $tpl['option_arr']['o_currency']); ?></div>
		</div>
		<?php 
	}
}
?>
<hr>
<div class="row">
<?php 
if ($tpl['session_prices']['tax'] > 0)
{
	?>
	<div class="col-sm-6">
	<?php __('front_tax'); ?>
	</div><!-- /.col-sm-6 -->

	<div class="col-sm-6 text-right">
		<strong><?php echo pjUtil::formatCurrencySign(number_format($tpl['session_prices']['tax'], 2), $tpl['option_arr']['o_currency']); ?></strong>
	</div><!-- /.col-sm-6 -->
	<?php
}
?>
	<div class="col-sm-6">
	<?php __('front_total'); ?>
	</div><!-- /.col-sm-6 -->

	<div class="col-sm-6 text-right">
		<strong><?php echo pjUtil::formatCurrencySign(number_format($tpl['session_prices']['total'], 2), $tpl['option_arr']['o_currency']); ?></strong>
	</div><!-- /.col-sm-6 -->
<?php 
if ($tpl['session_prices']['security'] > 0)
{
	?>
	<div class="col-sm-6">
	<?php __('front_security'); ?>
	</div><!-- /.col-sm-6 -->

	<div class="col-sm-6 text-right">
		<strong><?php echo pjUtil::formatCurrencySign(number_format($tpl['session_prices']['security'], 2), $tpl['option_arr']['o_currency']); ?></strong>
	</div><!-- /.col-sm-6 -->
	<?php
}
?>
	<div class="col-sm-6">
	<?php __('front_deposit'); ?>
	</div><!-- /.col-sm-6 -->

	<div class="col-sm-6 text-right pjHbPanelBookingMeta">
	<?php echo pjUtil::formatCurrencySign(number_format($tpl['session_prices']['deposit'], 2), $tpl['option_arr']['o_currency']); ?>
	</div><!-- /.col-sm-6 -->
</div><!-- /.row -->