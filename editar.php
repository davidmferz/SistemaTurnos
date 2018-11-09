<?php
	include 'conexion.php';
	$tabla=$_POST['tabla1'];
	$valor=$_POST['valor1'];
	$nuevo=$_POST['nuevo1'];
	$pass=$_POST['pass1'];
	$n=$_POST['n1'];
	$jsondata=array();

	if ($tabla == "responsableatencion" && $n == 1)
	{
		if ($nuevo === $valor)
		{
			$resultado=mysqli_query($con,"UPDATE $tabla SET contrasenia='$pass' WHERE nombre='$valor'");
		}
		else
		{
			$resultado=mysqli_query($con,"UPDATE $tabla SET nombre='$nuevo', contrasenia='$pass' WHERE nombre='$valor'");
		}
	}
	else
	{
		$resultado=mysqli_query($con,"UPDATE $tabla SET nombre='$nuevo' WHERE nombre='$valor'");
		$jsondata["res"]=$resultado;
	}

	if ($resultado!=true)
	{
		$jsondata["resultado"]=mysqli_error($con);
	}

	else
	{
		$jsondata["resultado"]=$resultado;
	}

	echo json_encode($jsondata);
?>