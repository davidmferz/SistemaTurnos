<?php
	include 'conexion.php';
	header("Content-Type: text/html;charset=utf-8");
	$tabla=$_GET['tabla'];
	try 
	{
		//PARA QUE RECONOCER LOS ACENTOS
		$acentos = $con->query("SET NAMES 'utf8'");
		
		$resultado=mysqli_query($con,"SELECT ID, Nombre FROM $tabla WHERE ID>=1 ORDER BY Nombre");

		if(mysqli_num_rows($resultado)>0)
		{
			$registros = "[";
			foreach ($resultado as $res) 
			{
				if ($registros != "[") 
				{
					$registros .= ",";
				}
					
				$registros .= '{"name": "'.$res["Nombre"].'"';
				$registros .= ',"id": "'.$res["ID"].'"}';
					
			}
				
			$registros .= "]";

		}
		else
		{
			$registros = '[{"name":"no se encontro"}]';
		}		
		
		echo ($registros); 
	}
	catch (Exception $e)
	{
		echo "Error: ".mysqli_error($con);
	};
?>