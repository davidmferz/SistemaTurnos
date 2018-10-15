<?php
$months = __('months', true);
$short_months = __('short_months', true);
ksort($months);
ksort($short_months);
$days = __('days', true);
$short_days = __('short_days', true);

$week_start = isset($tpl['option_arr']['o_week_start']) && in_array((int) $tpl['option_arr']['o_week_start'], range(0,6)) ? (int) $tpl['option_arr']['o_week_start'] : 0;
$jqDateFormat = pjUtil::jqDateFormat($tpl['option_arr']['o_date_format']);
$STORE = @$_SESSION[$controller->defaultStore];
?>
<div class="panel panel-default clearfix pjHbPanel">
	<?php include dirname(__FILE__) . '/elements/head.php'; ?>

	<div class="panel-body pjHbPanelBody">
		<h4 class="pjHbPanelTitle"><?php __('front_title'); ?></h4>
		<br>
		<div class="row">
			<form action="<?php echo PJ_INSTALL_URL; ?>index.php?controller=pjFrontPublic&amp;action=pjActionRoom" method="post" class="hbSelectorSearchForm pjHbFormCheck">
				<input type="hidden" name="step_search" value="1" />
				<div id="pjHbCalendarLocale" style="display: none;" data-months="<?php echo implode("_", $months);?>" data-days="<?php echo implode("_", $short_days);?>"></div>
				<div class="col-lg-2 col-md-2 col-sm-2">
					<div class="form-group">
						<label><?php __('front_guests'); ?></label>

						<select class="form-control" name="guests" data-msg-required="<?php __('front_validate_guests', false, true); ?>">
						<?php
						foreach (range(1, 10) as $i)
						{
							?><option value="<?php echo $i; ?>"<?php echo isset($STORE['guests']) ? ($STORE['guests'] == $i ? ' selected="selected"' : NULL) : NULL;?>><?php echo $i; ?></option><?php
						}
						?>
						</select>
					</div>
				</div><!-- /.col-md-3 -->
				
				<div class="col-lg-4 col-md-4 col-sm-4">
					<div class="form-group pjHbCalendar">
						<label for="from"><?php __('front_check_in'); ?></label>

						<div class="input-group pjHbCalendarFrom">
							<input type='text' class="form-control" name="date_from" autocomplete="off" value="<?php echo pjUtil::formatDate(isset($STORE['date_from']) && !empty($STORE['date_from']) ? $STORE['date_from'] : date("Y-m-d", strtotime("+1 day")), "Y-m-d", $tpl['option_arr']['o_date_format']); ?>" data-dformat="<?php echo $jqDateFormat; ?>" data-fday="<?php echo $week_start; ?>" data-msg-required="<?php __('front_validate_date_from', false, true); ?>" readonly="true" />

							<span class="input-group-addon">
								<span class="glyphicon glyphicon-calendar" aria-hidden="true"></span>
							</span>
						</div>
					</div>
				</div><!-- /.col-md-3 -->
				<?php
				$date_to = pjUtil::formatDate(isset($STORE['date_to']) && !empty($STORE['date_to']) ? $STORE['date_to'] : date("Y-m-d", strtotime("+2 day")), "Y-m-d", $tpl['option_arr']['o_date_format']);
				$min_to = isset($STORE['date_from']) && !empty($STORE['date_from']) ? $STORE['date_from'] : date("Y-m-d", strtotime("1 day")); 
				if($tpl['option_arr']['o_price_based_on'] == 'nights' && (isset($STORE['date_from']) && isset($STORE['date_to']) && $STORE['date_from'] == $STORE['date_to']) )
				{
					$date_to = date($tpl['option_arr']['o_date_format'], strtotime($STORE['date_to']) + 86400);
				}
				?>
				<div class="col-lg-4 col-md-4 col-sm-4">
					<div class="form-group pjHbCalendar">
						<label for="to"><?php __('front_check_out'); ?></label>
						
						<div class="input-group pjHbCalendarTo" data-year="<?php echo date('Y', strtotime($min_to));?>" data-month="<?php echo date('n', strtotime($min_to));?>" data-day="<?php echo date('j', strtotime($min_to));?>">
							<input type='text' class="form-control" name="date_to" autocomplete="off" value="<?php echo $date_to; ?>" data-dformat="<?php echo $jqDateFormat; ?>" data-fday="<?php echo $week_start; ?>" data-msg-required="<?php __('front_validate_date_to', false, true); ?>" readonly="true"/>

							<span class="input-group-addon">
								<span class="glyphicon glyphicon-calendar" aria-hidden="true"></span>
							</span>
						</div>
					</div>
				</div><!-- /.col-md-2 -->
				
				<div class="col-lg-2 col-sm-4">
					<div class="form-group">
						<label>&nbsp;</label>
						
						<div class="input-group">
							<button type="submit" class="btn btn-default btn-block"><?php __('front_search'); ?></button>
						</div>
					</div>
				</div><!-- /.col-md-2 -->
			</form>
		</div><!-- /.row -->
	</div><!-- /.panel-body pjHbPanelBody -->
</div><!-- /.panel pjHbPanel -->