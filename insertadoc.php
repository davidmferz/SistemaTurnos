<?php
	include 'conexion.php';
	$ruta="hola";
	$resultado=mysqli_query($con,"INSERT INTO turno(documentoRuta) VALUES('$ruta')"); 
?>