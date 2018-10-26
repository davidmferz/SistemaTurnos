<?php 
	include 'conexion.php';

	$nfolio=$_POST['nfolio1'];
	$ndocumento=$_POST['ndocumento1'];
	$fecharecibe=$_POST['fecharecibe1'];
	$fechadocumento=$_POST['fechadocumento1'];
	$tipoturno=$_POST['tipoturno1'];
	$arearemite=$_POST['arearemite1'];
	$arearbeneficia=$_POST['arearbeneficia1'];
	$ratencion=$_POST['ratencion1'];
	$iatencion=$_POST['iatencion1'];
	$jsondata=array();

	$resultado=mysqli_query($con,"INSERT INTO turno(nFolio,
		nDocumento,
		fechaRecibe,
		fechaDocumento,
		idTipoTurno,
		idAreaRemite,
		idAreaBeneficia,
		idResponsableAtencion,
		instruccionesAten) 
		VALUES('$nfolio',
		'$ndocumento',
		'$fecharecibe',
		'$fechadocumento',
		$tipoturno,
		$arearemite,
		$arearbeneficia,
		$ratencion,
		'$iatencion'

	)");

	if ($resultado!=true) {
		$jsondata["resultado"]=mysqli_error($con);
	}

	else{
		$jsondata["resultado"]=$resultado;
	}

	echo json_encode($jsondata);
	
?>