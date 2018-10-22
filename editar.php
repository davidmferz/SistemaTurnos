<?php
	include 'conexion.php';
	$tabla=$_POST['tabla1'];
	$valor=$_POST['valor1'];
	$nuevo=$_POST['nuevo1'];
	$jsondata=array();
	
	$resultado=mysqli_query($con,"UPDATE $tabla SET nombre='$nuevo' WHERE nombre='$valor'");
		
	if ($resultado!=true) {
		$jsondata["resultado"]=mysqli_error($con);
	}

	else{
		$jsondata["resultado"]=$resultado;
	}

	echo json_encode($jsondata);
?>