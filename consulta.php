<?php
  	include 'conexion.php';
  	$idC=$_GET['idC'];
 	$salida = "";
	 //CONSULTA PARA OBTENER Id y Nombre de: 
	 // -Tipo de turno
	 // -Area que remite
	 // -Area que beneficia
	 // -Departamento responsable de atencion

 	if ($idC==1)
 	{
		$query  = "SELECT Id, nombre from tipoturno";
	}
	else
	{
		if ($idC==2)
	 	{
			$query  = "SELECT Id, nombre from arearemite";
		}
		else
		{
			if ($idC==3)
		 	{
				$query  = "SELECT Id, nombre from areabeneficiada";
			}
			else
			{
				if ($idC==4)
			 	{
					$query  = "SELECT Id, nombre from responsableatencion";
				}
			}
		}
	}

  	$resultado = mysqli_query($con,$query);

	if($resultado)
	{
	  $salida.="";
	}
	else
	{
	  $salida.="No hay datos </br>"." ".$resultado->error;

	}

	echo $salida;
	mysqli_close ($con);
  ?>