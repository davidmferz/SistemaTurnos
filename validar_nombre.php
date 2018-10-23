<?php
	include 'conexion.php';

	$nombre = $_POST['nombre1'];
	$tabla = $_POST['tabla1'];
	$jsondata    = array();

	$resultado=mysqli_query($con, "SELECT nombre from $tabla WHERE nombre='".$nombre."'");

	

	if(mysqli_num_rows($resultado) == 0)
	{
		//No se repite el nombre
		$jsondata['resultado']=false;
	}
	else
	{
		//Se repite el nombre
		$jsondata['resultado']=true;
	}

	echo json_encode($jsondata);
?>