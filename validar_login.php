<?php
	
	session_start();
	include 'conexion.php';
	$user = $_POST['user'];
	$pass = $_POST['pass'];

	$log =mysqli_query($con,"SELECT Id, nombre FROM responsableatencion WHERE nombre='$user' AND contrasenia='$pass'");//selecciono la tabla de datos donde el campo nombre sea igual al usuario, lo mismo pasa con password

		if (mysqli_num_rows($log)>0) 	
		{
			$row = mysqli_fetch_assoc($log);
			$_SESSION["nombre"] = $row['nombre'];//inicio sesion
			$_SESSION["pagina"] = "index00".$row['Id'].".php";
			echo (1);
		}
		else
		{
			echo (0);
		}
?>