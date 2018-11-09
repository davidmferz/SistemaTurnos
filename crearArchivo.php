<?php
	include 'conexion.php';

	$nombre="en php";
	$nom=$_POST['nombre1'];
	$jsondata=array();

	$contenido = file_get_contents("indexPrueba.php");

	//$contenido="<html><head><title>HOLA</title></head><body><b>".$nombre."</b><b>".$nom."</b></body></html>";
	$resultado=mysqli_query($con,"SELECT Id FROM responsableatencion WHERE Id = (SELECT MAX(Id) from responsableatencion)");
	
	//if(mysqli_num_rows($resultado) > 0)
	//{
		foreach ($resultado as $fila) 
		{
			$ni=$fila["Id"];
		}

	//}
	
	$file=fopen("index00".$ni.".php","a+");
	fwrite($file,$contenido);
	fclose($file);

	echo json_encode($jsondata);
?>