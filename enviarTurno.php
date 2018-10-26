<?php
	/*
		Codigo necesario para insertar en la la tabla tipoTurno
	*/

	include 'conexion.php';

	$nombre=$_POST['nom1'];
	$tipo=$_POST['tipo1'];
	$jsondata=array();

	switch ($tipo) {
    case "vtnTurno":
        $resultado=mysqli_query($con,"INSERT INTO tipoturno(nombre) VALUES('$nombre')");
        break;
    case "vtnRemite":
        $resultado=mysqli_query($con,"INSERT INTO arearemite(nombre) VALUES('$nombre')");
        break;
    case "vtnBeneficia":
        $resultado=mysqli_query($con,"INSERT INTO areabeneficiada(nombre) VALUES('$nombre')");
        break;
    case "vtnAtencion":
        $resultado=mysqli_query($con,"INSERT INTO 	responsableatencion(nombre) VALUES('$nombre')");
        break;
}

	

	if ($resultado!=true) {
		$jsondata["resultado"]=mysqli_error($con);
	}

	else{
		$jsondata["resultado"]=$resultado;
	}

	echo json_encode($jsondata);
	
?>