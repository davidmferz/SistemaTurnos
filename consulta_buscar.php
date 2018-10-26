<?php
  	include 'conexion.php';

 	$salida = "";
	 //CONSULTA PARA OBTENER: 
	 // -Fecha que se recibe
	 // -Fecha del documento
	 // -Área que remite
	 // -Departamento responsable de atención


	 if(isset($_POST['consulta']))
	 {
	 	$q = mysqli_real_escape_string($con, $_POST['consulta']);
	 	$opcion = $_POST['opc'];

	 	switch ($opcion) 
	 	{
	 	 	case '1':
	 	 		$query  = "SELECT turno.id, fechaRecibe, fechaDocumento, arearemite.nombre AS nomrem, responsableatencion.nombre AS nomat FROM turno
				INNER JOIN arearemite ON turno.idAreaRemite = arearemite.Id
				INNER JOIN responsableatencion ON turno.idResponsableAtencion = responsableatencion.Id
				INNER JOIN tipoturno ON turno.idTipoTurno = tipoturno.Id
				WHERE tipoturno.nombre LIKE '%".$q."%' ORDER BY fechaRecibe DESC";
	 	 	break;

	 	 	case '2':
	 	 		$query  = "SELECT turno.id, fechaRecibe, fechaDocumento, arearemite.nombre AS nomrem, responsableatencion.nombre AS nomat FROM turno
				INNER JOIN arearemite ON turno.idAreaRemite = arearemite.Id
				INNER JOIN responsableatencion ON turno.idResponsableAtencion = responsableatencion.Id
				WHERE arearemite.nombre LIKE '%".$q."%' ORDER BY fechaRecibe DESC";
	 	 	break;

	 	 	case '3':
	 	 		$query  = "SELECT turno.id, fechaRecibe, fechaDocumento, arearemite.nombre AS nomrem, responsableatencion.nombre AS nomat FROM turno
				INNER JOIN arearemite ON turno.idAreaRemite = arearemite.Id
				INNER JOIN responsableatencion ON turno.idResponsableAtencion = responsableatencion.Id
				INNER JOIN areabeneficiada ON turno.idAreaBeneficia = areabeneficiada.Id
				WHERE areabeneficiada.nombre LIKE '%".$q."%' ORDER BY fechaRecibe DESC";
	 	 	break;
	 	 	
	 	 	case '4':
	 	 		$query  = "SELECT turno.id, fechaRecibe, fechaDocumento, arearemite.nombre AS nomrem, responsableatencion.nombre AS nomat FROM turno
				INNER JOIN arearemite ON turno.idAreaRemite = arearemite.Id
				INNER JOIN responsableatencion ON turno.idResponsableAtencion = responsableatencion.Id
				WHERE responsableatencion.nombre LIKE '%".$q."%' ORDER BY fechaRecibe DESC";
	 	 	break;

	 	 	case '5':
	 	 		$query  = "SELECT turno.id, fechaRecibe, fechaDocumento, arearemite.nombre AS nomrem, responsableatencion.nombre AS nomat FROM turno
				INNER JOIN arearemite ON turno.idAreaRemite = arearemite.Id
				INNER JOIN responsableatencion ON turno.idResponsableAtencion = responsableatencion.Id
				WHERE turno.nFolio LIKE '%".$q."%' ORDER BY fechaRecibe DESC";
	 	 	break;

	 	 	case '6':
	 	 		$query  = "SELECT turno.id, fechaRecibe, fechaDocumento, arearemite.nombre AS nomrem, responsableatencion.nombre AS nomat FROM turno
				INNER JOIN arearemite ON turno.idAreaRemite = arearemite.Id
				INNER JOIN responsableatencion ON turno.idResponsableAtencion = responsableatencion.Id
				WHERE turno.nDocumento LIKE '%".$q."%' ORDER BY fechaRecibe DESC";
	 	 	break;

	 	 	default:
	 	 		$salida.="Error en el switch";
	 	 	break;
	 	 }
	 }
	 else
	 {
	 	$query  = "SELECT turno.id, fechaRecibe, fechaDocumento, arearemite.nombre AS nomrem, responsableatencion.nombre AS nomat FROM turno
			INNER JOIN arearemite ON turno.idAreaRemite = arearemite.Id
			INNER JOIN responsableatencion ON turno.idResponsableAtencion = responsableatencion.Id
			ORDER BY fechaRecibe DESC";
	 }

  	$resultado = mysqli_query($con,$query);

	if($resultado)
	{
	  		$salida.="<table class='table table-striped'>
	  				<thead>
	  					<tr>
	  						<th scope='col'>Fecha que se recibe</th>
	  						<th scope='col'>Fecha del documento</th>
	  						<th scope='col'>Área que remite</th>
	  						<th scope='col'>Departamento responsable de atención</th>
	  					<tr>
	  				</thead>
	  				<tbody>";
	  	   if(isset($_POST['opc']))
	  	   {
	  	   		//Resaltar las letras que coincidan con la busqueda
	  	   		switch ($_POST['opc']) 
	  	   		{
	  	   			case '1':
	  	   			   foreach ($resultado as $fila) 
				  	   {
				  	   		$salida.="<tr>
				  	   					<td onclick='llenar(".$fila['id'].")'>".$fila['fechaRecibe']."</td>
					  	   				<td onclick='llenar(".$fila['id'].")'>".$fila['fechaDocumento']."</td>
					  	   				<td onclick='llenar(".$fila['id'].")'>".$fila['nomrem']."</td>
					  	   				<td onclick='llenar(".$fila['id'].")'>".$fila['nomat']."</td> 
					  	   			</tr>";
				  	   }	
	  	   			break;

	  	   			case '2':
	  	   			   foreach ($resultado as $fila) 
				  	   {
				  	   		$salida.="<tr>
					  	   				<td onclick='llenar(".$fila['id'].")'>".$fila['fechaRecibe']."</td>
					  	   				<td onclick='llenar(".$fila['id'].")'>".$fila['fechaDocumento']."</td>
					  	   				<td onclick='llenar(".$fila['id'].")'>".$fila['nomrem']."</td>
					  	   				<td onclick='llenar(".$fila['id'].")'>".$fila['nomat']."</td> 
					  	   			</tr>";
				  	   }	
	  	   			break;

	  	   			case '3':
	  	   			   foreach ($resultado as $fila) 
				  	   {
				  	   		$salida.="<tr>
					  	   				<td onclick='llenar(".$fila['id'].")'>".$fila['fechaRecibe']."</td>
					  	   				<td onclick='llenar(".$fila['id'].")'>".$fila['fechaDocumento']."</td>
					  	   				<td onclick='llenar(".$fila['id'].")'>".$fila['nomrem']."</td>
					  	   				<td onclick='llenar(".$fila['id'].")'>".$fila['nomat']."</td> 
					  	   			</tr>";
				  	   }	
	  	   			break;

	  	   			case '4':
	  	   			   foreach ($resultado as $fila) 
				  	   {
				  	   		$salida.="<tr>
					  	   				<td onclick='llenar(".$fila['id'].")'>".$fila['fechaRecibe']."</td>
					  	   				<td onclick='llenar(".$fila['id'].")'>".$fila['fechaDocumento']."</td>
					  	   				<td onclick='llenar(".$fila['id'].")'>".$fila['nomrem']."</td>
					  	   				<td onclick='llenar(".$fila['id'].")'>".$fila['nomat']."</td> 
					  	   			</tr>";
				  	   }	
	  	   			break;

	  	   			case '5':
	  	   			   foreach ($resultado as $fila) 
				  	   {
				  	   		$salida.="<tr>
					  	   				<td onclick='llenar(".$fila['id'].")'>".$fila['fechaRecibe']."</td>
					  	   				<td onclick='llenar(".$fila['id'].")'>".$fila['fechaDocumento']."</td>
					  	   				<td onclick='llenar(".$fila['id'].")'>".$fila['nomrem']."</td>
					  	   				<td onclick='llenar(".$fila['id'].")'>".$fila['nomat']."</td> 
					  	   			</tr>";
				  	   }	
	  	   			break;

	  	   			case '6':
	  	   			   foreach ($resultado as $fila) 
				  	   {
				  	   		$salida.="<tr>
					  	   				<td onclick='llenar(".$fila['id'].")'>".$fila['fechaRecibe']."</td>
					  	   				<td onclick='llenar(".$fila['id'].")'>".$fila['fechaDocumento']."</td>
					  	   				<td onclick='llenar(".$fila['id'].")'>".$fila['nomrem']."</td>
					  	   				<td onclick='llenar(".$fila['id'].")'>".$fila['nomat']."</td> 
					  	   			</tr>";
				  	   }	
	  	   			break;
	  	   		}
	  	   }
	  	   else
	  	   {
	  	   	   foreach ($resultado as $fila) 
		  	   {
		  	   		$salida.="<tr>
			  	   				<td onclick='llenar(".$fila['id'].")'>".$fila['fechaRecibe']."</td>
			  	   				<td onclick='llenar(".$fila['id'].")'>".$fila['fechaDocumento']."</td>
			  	   				<td onclick='llenar(".$fila['id'].")'>".$fila['nomrem']."</td>
			  	   				<td onclick='llenar(".$fila['id'].")'>".$fila['nomat']."</td> 
			  	   			</tr>";
		  	   }
	  	   }
	  	   
	  	   $salida.="</tbody></table>";
	}
	else
	{
	  $salida.="No hay datos </br>"." ".$resultado->error;
	}

	echo $salida;
	mysqli_close ($con);
  ?>