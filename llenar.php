<?php
	include 'conexion.php';
	$jsondata    = array();

	$resultado=mysqli_query($con,"SELECT * FROM turno WHERE id =".$_POST['id']);

	if(mysqli_num_rows($resultado)>0)
	{
		foreach ($resultado as $fila) 
		{
			$jsondata['id']                   = $fila['id'];
			$jsondata['folio']               = $fila['nFolio'];
			$jsondata['documento']           = $fila['nDocumento'];
			$jsondata['fechaRecibe']          = $fila['fechaRecibe'];
			$jsondata['fechaDocumento']    	  = $fila['fechaDocumento'];
			$jsondata['instruccionesAten']    = $fila['instruccionesAten'];
			$jsondata['documentoRuta']        = $fila['documentoRuta'];
			$jsondata['idTipoTurno']      	  = $fila['idTipoTurno'];
			$jsondata['idAreaRemite'] 		  = $fila['idAreaRemite'];
			$jsondata['idAreaBeneficia']      = $fila['idAreaBeneficia'];
			$jsondata['idResponsableAtencion']  = $fila['idResponsableAtencion'];
		}
	}

	//TIPO DE TURNO
	$resultado=mysqli_query($con,"SELECT * FROM tipoturno WHERE id =".$jsondata['idTipoTurno']);

	if(mysqli_num_rows($resultado)>0)
	{
		foreach ($resultado as $fila) 
		{
			$jsondata['tipoTurno'] =$fila['nombre'];
		}
	}

	//AREA QUE REMITE
	$resultado=mysqli_query($con,"SELECT * FROM arearemite WHERE id =".$jsondata['idAreaRemite']);

	if(mysqli_num_rows($resultado)>0)
	{
		foreach ($resultado as $fila) 
		{
			$jsondata['areaRemite'] =$fila['nombre'];
		}
	}

	//AREA QUE BENEFICIA
	$resultado=mysqli_query($con,"SELECT * FROM areabeneficiada WHERE id=".$jsondata['idAreaBeneficia']);

	if(mysqli_num_rows($resultado)>0)
	{
		foreach ($resultado as $fila) 
		{
			$jsondata['areaBeneficia'] =$fila['nombre'];
		}
	}

	//DEPARTAMENTO RESPONSABLE DE ATENCION
	$resultado=mysqli_query($con,"SELECT * FROM responsableatencion WHERE id=".$jsondata['idResponsableAtencion']);

	if(mysqli_num_rows($resultado)>0)
	{
		foreach ($resultado as $fila) 
		{
			$jsondata['responsableAtencion'] =$fila['nombre'];
		}
	}

	//REMPLAZAMOS EL " / " POR  " - " 
	//EJEMPLO UAEM/SAD/001/2018 = UAEM-SAD-001-2018 
	//YA QUE LAS CARPETAS SE CREARON CON EL - YA QUE EL / NO SE PUEDE CREAR.
	$posicion=0;
	$cadena=$jsondata['documentoRuta'];

	if($cadena != "")
	{
		do{
			$posicion=strpos($cadena, "/", $posicion+1);
			if($posicion != 0)
			{
				$cadena[$posicion]="-";	
			}
		}while($posicion != false); 

		//RUTA DEL ARCHIVO EJEMPLO $ruta= archivosTurnos/UAEM-SAD-001-2018/
		$ruta = "archivosTurnos/".$cadena."/";


		//ARCHIVO: Documento.pdf
		//LEEMOS EL ARCHIVO PDF DEL DOCUMENTO $archivo=archivosTurnos/UAEM-SAD-001-2018/Documento.pdf
		$archivo = $ruta."Documento.pdf";

		//VERIFICAMOS QUE EXISTA Documento.pdf
		if(file_exists($archivo))
		{
			$jsondata['ruta']=$archivo;
		}
		else
		{
			$jsondata['ruta']="NO SE ENCONTRO ARCHIVO";
		}
	}
	else
	{
		$jsondata['ruta']="NO SE ENCONTRO ARCHIVO";
	}

    echo json_encode($jsondata);
	exit();	
?>