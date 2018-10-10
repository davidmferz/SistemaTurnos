<?php
if (pjObject::getPlugin('pjOneAdmin') !== NULL && $controller->isAdmin())
{
	$controller->requestAction(array('controller' => 'pjOneAdmin', 'action' => 'pjActionMenu'));
}
?>

<div class="leftmenu-top"></div>
<div class="leftmenu-middle">
	<ul class="menu">
		<li><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdmin&amp;action=pjActionIndex" class="<?php echo $_GET['controller'] == 'pjAdmin' && in_array($_GET['action'], array('pjActionIndex', 'pjActionWelcome')) ? 'menu-focus' : NULL; ?>"><span class="menu-dashboard">&nbsp;</span><?php __('menuDashboard'); ?></a></li>
		<li><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBookings&amp;action=pjActionCalendar" class="<?php echo $_GET['controller'] == 'pjAdminBookings' || ($_GET['controller'] == 'pjInvoice' && in_array($_GET['action'], array('pjActionInvoices', 'pjActionUpdate', 'pjActionCreateInvoice'))) ? 'menu-focus' : NULL; ?>"><span class="menu-bookings">&nbsp;</span><?php __('menuBookings'); ?></a></li>
		<?php
		if ($controller->isEditor())
		{
			?><li><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdmin&amp;action=pjActionProfile" class="<?php echo $_GET['controller'] == 'pjAdmin' && $_GET['action'] == 'pjActionProfile' ? 'menu-focus' : NULL; ?>"><span class="menu-users">&nbsp;</span><?php __('menuProfile'); ?></a></li><?php
		}
		if ($controller->isAdmin())
		{
			?>
			<li><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminRooms" class="<?php echo in_array($_GET['controller'], array('pjAdminRooms', 'pjPrice', 'pjAdminLimits', 'pjAdminDiscounts', 'pjAdminExtras', 'pjAdminRestrictions')) ? 'menu-focus' : NULL; ?>"><span class="menu-rooms">&nbsp;</span><?php __('menuRooms'); ?></a></li>
			<li><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminOptions&amp;tab=1" class="<?php echo ($_GET['controller'] == 'pjAdminOptions' && in_array($_GET['action'], array('pjActionIndex'))) || in_array($_GET['controller'], array('pjAdminLocales', 'pjBackup', 'pjLocale', 'pjSms', 'pjCountry')) || ($_GET['controller'] == 'pjInvoice' && in_array($_GET['action'], array('pjActionIndex'))) ? 'menu-focus' : NULL; ?>"><span class="menu-options">&nbsp;</span><?php __('menuOptions'); ?></a></li>
			<li><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminReports" class="<?php echo $_GET['controller'] == 'pjAdminReports' ? 'menu-focus' : NULL; ?>"><span class="menu-reports">&nbsp;</span><?php __('menuReports'); ?></a></li>
			<li><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminUsers&amp;action=pjActionIndex" class="<?php echo $_GET['controller'] == 'pjAdminUsers' ? 'menu-focus' : NULL; ?>"><span class="menu-users">&nbsp;</span><?php __('menuUsers'); ?></a></li>
			<li><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminOptions&amp;action=pjActionPreview" class="<?php echo $_GET['controller'] == 'pjAdminOptions' && $_GET['action'] == 'pjActionPreview' ? 'menu-focus' : NULL; ?>"><span class="menu-preview">&nbsp;</span><?php __('menuPreview'); ?></a></li>
			<li><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminOptions&amp;action=pjActionInstall" class="<?php echo $_GET['controller'] == 'pjAdminOptions' && $_GET['action'] == 'pjActionInstall' ? 'menu-focus' : NULL; ?>"><span class="menu-install">&nbsp;</span><?php __('menuInstall'); ?></a></li>
			<?php
		}
		?>
		<li><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdmin&amp;action=pjActionLogout"><span class="menu-logout">&nbsp;</span><?php __('menuLogout'); ?></a></li>
	</ul>
</div>
<div class="leftmenu-bottom"></div>