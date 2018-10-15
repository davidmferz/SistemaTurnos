<?php
	include 'conexion.php';
	sleep(2);
	$nombre=$_POST['nom1'];
	$jsondata=array();

	$resultado=mysqli_query($con,"INSERT INTO tipoturno(nombre) VALUES('$nombre')");

	if ($resultado!=true) {
		$jsondata["resultado"]=mysqli_error($con);
	}

	else{
		$jsondata["resultado"]=$resultado;
	}

	echo json_encode($jsondata);
?>