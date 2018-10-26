<?php
	include 'conexion.php';

	

	$nombre1 = $_POST['tipoturno1'];
	$nombre2 = $_POST['arearemite1'];
	$nombre3 = $_POST['arearbeneficia1'];
	$nombre4 = $_POST['ratencion1'];
	$jsondata    = array();

	$resultado=mysqli_query($con, "SELECT Id from tipoturno WHERE nombre='".$nombre1."'");
	if(mysqli_num_rows($resultado) > 0)
	{
		foreach ($resultado as $fila) 
		{
			$jsondata['resultadoturno']= $fila["Id"];
		}

	}
	else
	{
		$jsondata['resultadoturno']=0;
	}

	


	$resultado=mysqli_query($con, "SELECT Id from arearemite WHERE nombre='".$nombre2."'");
	if(mysqli_num_rows($resultado) > 0)
	{
		foreach ($resultado as $fila) 
		{
			$jsondata['resultadoremite']= $fila["Id"];
		}

	}
	else
	{
		$jsondata['resultadoremite']=0;
	}

	$resultado=mysqli_query($con, "SELECT Id from areabeneficiada WHERE nombre='".$nombre3."'");
	

	if(mysqli_num_rows($resultado) > 0)
	{
		foreach ($resultado as $fila) 
		{
			$jsondata['resultadobeneficiada']= $fila["Id"];
		}

	}
	else
	{
		$jsondata['resultadobeneficiada']=0;
	}

	$resultado=mysqli_query($con, "SELECT Id from responsableatencion WHERE nombre='".$nombre4."'");
	

	if(mysqli_num_rows($resultado) > 0)
	{
		foreach ($resultado as $fila) 
		{
			$jsondata['resultadoatencion']= $fila["Id"];
		}

	}
	else
	{
		$jsondata['resultadoatencion']=0;
	}

	echo json_encode($jsondata);
?>