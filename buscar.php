<?php
	include 'conexion.php';
	header("Content-Type: text/html;charset=utf-8");
	$tabla=$_GET['tabla'];
	$name=$_GET['name'];
	try 
	{
		//PARA QUE RECONOCER LOS ACENTOS
		$acentos = $con->query("SET NAMES 'utf8'");
		
		$resultado=mysqli_query($con,"SELECT id, nombre FROM $tabla WHERE nombre=$name");
		
		echo ($resultado); 
	}
	catch (Exception $e)
	{
		echo "Error: ".mysqli_error($con);
	};
?>