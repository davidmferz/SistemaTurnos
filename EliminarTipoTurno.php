<?php
 include 'conexion.php';

 $eliminarTurno=$_POST['idEliminar'];
 $jsondata    = array();
	



     		//$=mysqli_query($con,"SELECT * FROM tipoturno WHERE id =".$id);
     		$TipoturnoEliminar=mysqli_query($con,"DELETE FROM tipoturno WHERE Id=".$eliminarTurno);
     		
    $jsondata["TipoturnoEliminar"]=$TipoturnoEliminar;
    

 echo json_encode($jsondata);

?>


