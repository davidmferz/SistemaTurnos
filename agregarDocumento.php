<?php 
	include 'conexion.php';

	//$druta=$_POST['druta1']

	//$jsondata=array();

	/*$carpeta="archivosTurnos/";
	
	opendir($carpeta);
	$destino=$carpeta.$_FILES['archivo']['name'];
	copy($_FILES['archivo']['tmp_name'],$destino);
	*/
	mysqli_query($con,"INSERT INTO turno(documentoRuta) VALUES ('ruta')");

	
	/*$archi=$_FILES["archivo"]["name"];
	$ruta=$_FILES["archivo"]["tmp_name"];
	$destino="archivosTurnos/".$archi;
	copy($ruta,$destino);
	$jsondata["destino"]=$destino;
	$resultado=mysqli_query($con,"INSERT INTO turno(documentoRuta) VALUES ('$destino')");
	

	if ($resultado!=true) {
		$jsondata["resultado"]=mysqli_error($con);
	}

	else{
		$jsondata["resultado"]=$resultado;
	}

	
	*/
?>