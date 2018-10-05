<?php

	 include 'conexion.php';
	 $jsondata    = array();

	 $nombre=$_POST['nombre'];
	 $resultado=mysqli_query($con,"INSERT INTO tipoturno(nombre) VALUES ('$nombre')");

	 
	 if ($resultadoX!=true) {
		$jsondata["resultadoX"]=mysqli_error($con);
			

		}
		else{


		$jsondata["resultadoX"]=$resultadoX;
	 
	}


 ?>