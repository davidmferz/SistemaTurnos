<?php
	/*
		Codigo necesario para insertar en la la tabla tipoTurno
	*/

	include 'conexion.php';

	$nombre=$_POST['nom1'];
	$jsondata=array();

	$resultado=mysqli_query($con,"INSERT INTO tipoTurno(nombre) VALUES('$nombre')");

	if ($resultado!=true) {
		$jsondata["resultado"]=mysqli_error($con);
	}

	else{
		$jsondata["resultado"]=$resultado;
	}

	echo json_encode($jsondata);
	
?>