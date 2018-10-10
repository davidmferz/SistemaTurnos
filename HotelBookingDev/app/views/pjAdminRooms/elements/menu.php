<?php
$active = " ui-tabs-active ui-state-active";
?>
<div class="ui-tabs ui-widget ui-widget-content ui-corner-all b10">
	<ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">
		<li class="ui-state-default ui-corner-top<?php echo $_GET['controller'] != 'pjAdminRooms' || $_GET['action'] != 'pjActionIndex' ? NULL : $active; ?>"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminRooms&amp;action=pjActionIndex"><?php __('menuRooms'); ?></a></li>
		<?php
		if ($_GET['controller'] == 'pjAdminRooms' && in_array($_GET['action'], array('pjActionCreate', 'pjActionUpdate')))
		{
			switch ($_GET['action'])
			{
				case 'pjActionCreate':
					?><li class="ui-state-default ui-corner-top ui-tabs-active ui-state-active"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminRooms&amp;action=pjActionCreate"><?php __('rooms_add'); ?></a></li><?php
					break;
				case 'pjActionUpdate':
					?><li class="ui-state-default ui-corner-top ui-tabs-active ui-state-active"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminRooms&amp;action=pjActionUpdate&amp;id=<?php echo $tpl['arr']['id']; ?>"><?php __('rooms_update'); ?></a></li><?php
					break;
			}
		} else {
			if (isset($_SESSION[$controller->defaultRoom]) && (int) $_SESSION[$controller->defaultRoom] > 0)
			{
				?><li class="ui-state-default ui-corner-top<?php echo $_GET['controller'] != 'pjPrice' ? NULL : $active; ?>"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjPrice"><?php __('menuPrice'); ?></a></li><?php
			} else {
				?><li class="ui-state-default ui-corner-top<?php echo $_GET['controller'] != 'pjAdminRooms' || $_GET['action'] != 'pjActionPrices' ? NULL : $active; ?>"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminRooms&amp;action=pjActionPrices"><?php __('menuPrice'); ?></a></li><?php
			}
			?>
			<li class="ui-state-default ui-corner-top<?php echo $_GET['controller'] != 'pjAdminLimits' ? NULL : $active; ?>"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminLimits&amp;action=pjActionIndex"><?php __('menuLimits'); ?></a></li>
			<li class="ui-state-default ui-corner-top<?php echo $_GET['controller'] != 'pjAdminDiscounts' ? NULL : $active; ?>"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminDiscounts&amp;action=pjActionIndex"><?php __('menuDiscounts'); ?></a></li>
			<li class="ui-state-default ui-corner-top<?php echo $_GET['controller'] != 'pjAdminExtras' ? NULL : $active; ?>"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminExtras"><?php __('menuExtras'); ?></a></li>
			<li class="ui-state-default ui-corner-top<?php echo $_GET['controller'] != 'pjAdminRestrictions' ? NULL : $active; ?>"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminRestrictions&amp;action=pjActionIndex"><?php __('menuRestrictions'); ?></a></li>
			<?php 
		}
		?>
	</ul>
</div>