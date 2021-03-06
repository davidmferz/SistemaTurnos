<?php
	//PAGINA DEL ADMINISTRADOR
	session_start();
	if(!$_SESSION)
	{
		header('Location: index.php');
	}
    else
    {
      $bienvenido=$_SESSION["nombre"];  
    }
?>

<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title>SistemaTurnos</title>
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<!--BOOSTRAP-->
	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">

	<!--CSS-->
	<link rel="stylesheet" type="text/css" href="css/estilos.css">

    <!--INSTALACION DE REACT -->
    <script crossorigin src="https://unpkg.com/react@16/umd/react.development.js"></script>
    <script crossorigin src="https://unpkg.com/react-dom@16/umd/react-dom.development.js"></script>

    <script src="js/altaVentanaModal.js"></script>

    <!--VALIDAR EL NOMBRE NO SEA EL MISMO-->
    <script src="validar.js"></script>

    <script src="eliminarTurno.js"></script>
    <script src="nuevoTurno.js"></script>
    
    <!--SE AGREGA la funcion consulta_Select-->
    <script src="js/consulta_Select.js"></script>
    <script src="crearArchivo.js"></script>
    
</head>
<body>
	<div class="container-fluid">

		<!--TITULO-->
		<div class="row" style="margin-bottom: 15px;">
                <!--TITULO DE SIEObras-->
                <div class="col-md-1 align-text-top" style="text-align: center; margin-top: 25px;">
                    <img src="img/logo.png" alt="Imagen no encontrada" style="width: 100%">
                </div>
                <div class="col-md-11 align-middle" style="text-align: left; margin-top: 35px;">
                    <h3 style="margin-top: 0px;">Sistema de Turnos</h3>
                    <!--LINEA DE COLORES-->
                    <div class="row">
	                	    <div class="panel-heading col-md-3" style="background-color: #2C5234; border-color: #2C5234; margin-bottom: 35px; padding: 4px;" >
	                	    </div>
	                	    
	                	    <div class="panel-heading col-md-7" style="background-color: #9C8412;border-color: #9C8412; margin-bottom: 35px; padding: 4px;" >              
	                	    </div>  
	                </div>
                </div>
        </div>
        
        <div style="margin-left: 15px;">
            <h5>Inicio sesión como: <b> <?php echo $bienvenido."  " ?> </b> <a href="salir.php" style="color:red"> salir</a></h5>
        </div>

	<br><br><br>
    
    </div><!--Aqui termina el container-->

<script>

var tabla2 = null;
var vtn = null;
  
  function consulta(tabla,select)
  {
    tabla2 = tabla;
    vtn = select;
    var aux = "select-";
    aux += vtn;
    consulta_Select(tabla,aux);
  }

  function cargar()
  {
    var aux = "select-";
    aux += vtn;
    var aux2 = "text-";
    aux2 += vtn;
    var dato = document.getElementById(aux).value;
    document.getElementById(aux2).value=dato;
  }

  function actualiza()
  {
    var aux = "select-";
    aux += vtn;
    var aux2 = "text-";
    aux2 += vtn;
    var valor = document.getElementById(aux).value;
    var nuevo = document.getElementById(aux2).value;
    validar_nombre(aux, aux2, tabla2, vtn);
  }

  function limpia()
  {
    var aux2 = "text-";
    aux2 += vtn;
    document.getElementById(aux2).value=null;
  }

  function elimina()
  {
    eliminarTurno(tabla2, vtn);
  }

  function agregar(){
    console.log(tabla2+vtn);
    nuevoTurno(tabla2,vtn);
  }

  function consultaAgregar(tabla, vtnm)
  {
    tabla2=tabla;
    vtn=vtnm;
  }
</script>

    <!-- VENTANA MODAL ENLACE -->
    <script src="js/ventanaModal-dist.js"></script>
    <script src="js/ventanaModalEditar-dist.js"></script>
    <script src="js/ventanaModalEliminar-dist.js"></script>
    
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script type="text/javascript" src="js/bootstrap.min.js"></script>
    <script src="js/jquery-3.2.1.min.js"></script>
</body>
</html>