<!DOCTYPE html>
<html>
	<head>
		<title>Hotel Booking System - Preview</title>
		<meta charset="utf-8">
	    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	    <meta name="viewport" content="width=device-width, initial-scale=1">
	    <link href="core/framework/libs/pj/css/pj.bootstrap.min.css" type="text/css" rel="stylesheet" />
	    <link href="index.php?controller=pjFront&action=pjActionLoadCss&cid=<?php echo @$_GET['cid']; ?><?php echo isset($_GET['theme']) && strlen($_GET['theme']) > 0 ? '&theme=' . (int) $_GET['theme'] : NULL; ?>" type="text/css" rel="stylesheet" />
	<head>
	<body>
		<script type="text/javascript" src="index.php?controller=pjFront&action=pjActionLoad&cid=<?php echo @$_GET['cid']; ?><?php echo isset($_GET['theme']) && strlen($_GET['theme']) > 0 ? '&theme=' . (int) $_GET['theme'] : NULL; ?>"></script>
	</body>
</html>