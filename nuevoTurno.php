<?php
	//Agregar un nuevo turno
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

    <!--BOOSTRAP-->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

    <!--CSS-->
    <script src="css/estilos.css"></script>
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
    </div>
    
    <div class="container mt-5">
        <div class="card mt-2">
          <div class="card-header text-center">
            Agregar un nuevo Turno
          </div>
          <div class="card-body">
                <div class="form-group">
                    <label for="exampleFormControlSelect1">Tipos de Turnos</label>
                    <select class="form-control" id="exampleFormControlSelect1">
                        <option value="0">Seleccione una opcion</option>
                        <option value="1">Sistema Unico de Gestion</option>
                        <option value="2">Oficio de Espacio Academico</option>
                        <option value="3">Oficio Externo</option>
                        <option value="4">Indicacion Directa</option>
                        <option value="2">Secretaria de Administracion</option>
                    </select>
                </div>

              <div class="form-group">
                <label for="exampleFormControlSelect1">Area que Remite</label>
                <select class="form-control" id="exampleFormControlSelect1">
                  <option value="0">Seleccione una opcion</option>
                </select>
              </div>

              <div class="form-group">
                <label for="exampleFormControlSelect1">Area que Beneficia</label>
                <select class="form-control" id="exampleFormControlSelect1">
                  <option value="0">Seleccione una opcion</option>
                </select>
              </div>

              <div class="form-group">
                <label for="exampleFormControlSelect1">Departamento responsable de atención</label>
                <select class="form-control" id="exampleFormControlSelect1">
                  <option value="0">Seleccione una opcion</option>
                </select>
              </div>

              <div class="form-group">
                <label for="exampleFormControlTextarea1">Instrucciones de Atención</label>
                <textarea class="form-control" id="exampleFormControlTextarea1" rows="3"></textarea>
              </div>
             
             <div class="form-group">
                <label for="exampleFormControlFile1">Documento</label>
                <input type="file" class="form-control-file" id="exampleFormControlFile1">
              </div>

            </div><!--CARD BODY TERMINA-->
        </div><!--CARD TERMINA-->
    </div><!--CONTAINER TERMINA-->
    <!-- BOOSTRAP Y JQUERY -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    
    <!-- JQuery-->
    <script src="js/jquery-3.2.1.min.js"></script>
</body>
</html>