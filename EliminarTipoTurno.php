<?php
 include 'conexion.php';

 $eliminar=$_POST['idEliminar1']; //idEliminar es el dato7texo que se va ha eliminar en la tabla 
 $tabla=$_POST['tabla1'];
 $jsondata    = array();

 		//$=mysqli_query($con,"SELECT * FROM tipoturno WHERE id =".$id);
     	$resultado=mysqli_query($con,"DELETE FROM $tabla WHERE nombre = '$eliminar'");

    if ($resultado!=true) {
		$jsondata["resultado"]=mysqli_error($con);
	}

	else{
		$jsondata["resultado"]=$resultado;
	}
    

 echo json_encode($jsondata);

	

?>