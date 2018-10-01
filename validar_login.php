<?php
	session_start();
	$pass = $_POST['pass'];

	if ($pass == "74st") 	
	{
		
		$_SESSION["user"] = 0; //inicio sesio
		echo (1);
		
	}
	else
	{
		echo (0);
	}
?>