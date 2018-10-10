<!doctype html>
<html>
	<head>
		<title>Hotel Booking Script by PHPJabbers.com</title>
		<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
		<?php
		foreach ($controller->getCss() as $css)
		{
			echo '<link type="text/css" rel="stylesheet" href="'.(isset($css['remote']) && $css['remote'] ? NULL : PJ_INSTALL_URL).$css['path'].htmlspecialchars($css['file']).'" />';
		}
		foreach ($controller->getJs() as $js)
		{
			echo '<script src="'.(isset($js['remote']) && $js['remote'] ? NULL : PJ_INSTALL_URL).$js['path'].htmlspecialchars($js['file']).'"></script>';
		}
		?>
		<!--[if gte IE 9]>
  		<style type="text/css">.gradient {filter: none}</style>
		<![endif]-->
	</head>
	<body>
		<div id="container">
    		<div id="header">
    			<div id="logo">
					<a href="https://www.phpjabbers.com/hotels-booking-system/" target="_blank" rel="nofollow">Hotel Booking Script</a>
					<span>v<?php echo PJ_SCRIPT_VERSION;?></span>
				</div>
			</div>
			
			<?php 
			$include_menu = dirname(dirname(PJ_INSTALL_PATH)) . '/include-menu2.php';
			if (is_file($include_menu))
			{
				include $include_menu;
			}
			?>
			<div id="middle">
				<div id="leftmenu">
					<?php require PJ_VIEWS_PATH . 'pjLayouts/elements/leftmenu.php'; ?>
				</div>
				<div id="right">
					<div class="content-top"></div>
					<div class="content-middle" id="content">
					<?php require $content_tpl; ?>
					</div>
					<div class="content-bottom"></div>
				</div> <!-- content -->
				<div class="clear_both"></div>
			</div> <!-- middle -->
		
		</div> <!-- container -->
		<div id="footer-wrap">
			<div id="footer">
			   	<p>Copyright &copy; <?php echo date("Y"); ?> <a href="https://www.PHPJabbers.com" target="_blank">PHPJabbers.com</a></p>
	        </div>
        </div>
	</body>
</html>