<?php
	//PAGINA DEL ADMINISTRADOR
	session_start();
	if(!$_SESSION)
	{
		header('Location: index.php');
	}
    else
    {
        switch ($_SESSION["user"]) 
        {
            case 0://Direccion de Obra Universitara
                $bienvenido="Administrador";  
            break;

        }
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
    <!--OPCIONES-->
    <div class="row mt-5">
    	<div class="col-md-6 text-center">
    		<a href="nuevoTurno.php"><img src="img/mas.png" alt="imagen"></a>
    		<h5>Agregar un turno</h5>
    	</div>
    	<div class="col-md-6 text-center">
    		<a href="#"><img src="img/buscar.png" alt="imagen"></a>
    		<h5>Buscar un turno</h5>
    	</div>
    </div>

     <!--
    VENTANA MODAL para agregar: 
            *Tipo de recurso.
            *Area que remite.
            *Area que Beneficia.
            *Departamento de Atencion. 
    -->
    <div id="ventana">
        
    </div>
    
    <div id="ventanaEditar">
        
    </div>


    

    <table class="table">
      <thead>
        <tr>
          <th scope="col"> </th>
          <th scope="col">Agregar</th>
          <th scope="col">Editar</th>
          <th scope="col">Eliminar</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <th scope="row">Tipo de Turno</th>
          <td>
            <button type="button" class="btn btn-link" data-toggle="modal" data-target="#vtnTurno">
              <img src="img/agregar.png" alt="Imagen">
            </button>
          </td>
          <td>
            <button type="button" class="btn btn-link" data-toggle="modal" data-target="#vtnTurnoEditar">
              <img src="img/editar.png" alt="Imagen">
            </button>
          </td>
          <td>@mdo</td>
        </tr>
        <tr>
          <th scope="row">Area que Remite</th>
          <td>
            <button type="button" class="btn btn-link" data-toggle="modal" data-target="#vtnRemite">
              <img src="img/agregar.png" alt="Imagen">
            </button>
          </td>
          <td>
            <button type="button" class="btn btn-link" data-toggle="modal" data-target="#vtnRemiteEditar">
              <img src="img/editar.png" alt="Imagen">
            </button>
          </td>
          <td>@fat</td>
        </tr>
        <tr>
          <th scope="row">Area que Beneficia</th>
          <td>
            <button type="button" class="btn btn-link" data-toggle="modal" data-target="#vtnBeneficia">
                <img src="img/agregar.png" alt="Imagen">
            </button>
          </td>
          <td>
            <button type="button" class="btn btn-link" data-toggle="modal" data-target="#vtnBeneficiaEditar">
              <img src="img/editar.png" alt="Imagen">
            </button>
          </td>
          <td>@twitter</td>
        </tr>
        <tr>
          <th scope="row">Departamento Responsable de Atención</th>
          <td>
            <button type="button" class="btn btn-link" data-toggle="modal" data-target="#vtnAtencion">
                <img src="img/agregar.png" alt="Imagen">
            </button>
          </td>
          <td>
            <button type="button" class="btn btn-link" data-toggle="modal" data-target="#vtnAtencionEditar">
              <img src="img/editar.png" alt="Imagen">
            </button>
          </td>
          <td>@twitter</td>
        </tr>
      </tbody>
    </table>

    </div><!--Aqui termina el container-->
    

    <!-- VENTANA MODAL ENLACE -->
    <script src="js/ventanaModal-dist.js"></script>
    <script src="js/ventanaModalEditar-dist.js"></script>
   
    
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script type="text/javascript" src="js/bootstrap.min.js"></script>
</body>
</html>