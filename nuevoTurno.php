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
    //modificacion
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>SistemaTurnos</title>

    <!-- JQuery-->
    <script src="js/jquery-3.2.1.min.js"></script>

    <!--BOOSTRAP-->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

    <!--JQUERY -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>

    <!--CSS-->
    <link rel="stylesheet" type="text/css" href="css/estilos.css">

    <!--Consulta select-->
    <script src="js/consulta_Select.js"></script>

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
<<<<<<< HEAD
                <div class="form-group">
                    <label for="exampleFormControlSelect1">Tipos de Turnos</label>
                    <select class="form-control" id="tipoturno">
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
                <select class="form-control" id="arearemite">
                  <option value="0">Seleccione una opcion</option>
                </select>
              </div>

              <div class="form-group">
                <label for="exampleFormControlSelect1">Area que Beneficia</label>
                <select class="form-control" id="areabeneficiada">
                  <option value="0">Seleccione una opcion</option>
                </select>
              </div>

              <div class="form-group">
                <label for="exampleFormControlSelect1">Departamento responsable de atención</label>
                <select class="form-control" id="responsableatencion">
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
=======
                
                <form action="" method="">
                    <br>
                    <!--No Folio y No. Documento-->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row">
                              <label for="folio" class="col-sm-4 col-form-label" style="padding-right: 0px;">No. Folio</label>
                              <div class="col-sm-8">
                                <input type="text" class="form-control" name="folio" id="folio" placeholder="No. Folio">
                              </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group row">
                              <label for="documento" class="col-sm-4 col-form-label" style="padding-right: 0px;">No. Documento</label>
                              <div class="col-sm-8">
                                <input type="text" class="form-control" name="documento" id="documento" placeholder="No. Documento">
                              </div>
                            </div>
                        </div>
                    </div>
                    
                    <br>
                    <!-- FECHA QUE SE RECIBE  Y FECHA DEL DOCUMENTO-->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row">
                              <label for="fechaRecibe" class="col-sm-4 col-form-label" style="padding-right: 0px;">Fecha que se Recibe</label>
                              <div class="col-sm-8">
                                <input type="date" class="form-control" name="fechaRecibe" id="fechaRecibe">
                              </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group row">
                              <label for="fechaDocumento" class="col-sm-4 col-form-label" style="padding-right: 0px;">Fecha del Documento</label>
                              <div class="col-sm-8">
                                <input type="date" class="form-control" name="fechaDocumento" id="fechaDocumento">
                              </div>
                            </div>
                        </div>
                    </div>
                    <br>
                    <br>

                    <!--TIPO DE TURNO-->
                    <div class="form-group">
                        <label for="exampleFormControlSelect1">Tipos de Turnos</label>
                        <select class="form-control" id="select-Turno">
                            <option value="0">Seleccione una opcion</option>
                        </select>
                    </div>
                    
                    <!--AREA QUE REMITE-->
                    <div class="form-group">
                        <label for="exampleFormControlSelect1">Area que Remite</label>
                        <select class="form-control" id="select-Remite">
                          <option value="0">Seleccione una opcion</option>
                        </select>
                    </div>
                                 
                    <!--AREA QUE BENEFICIA-->
                    <div class="form-group">
                        <label for="exampleFormControlSelect1">Area que Beneficia</label>
                        <select class="form-control" id="select-Beneficia">
                          <option value="0">Seleccione una opcion</option>
                        </select>
                    </div>
                                
                    <!--DEPARTAMENTO RESPONSABLE DE ATENCION-->
                    <div class="form-group">
                        <label for="exampleFormControlSelect1">Departamento responsable de atención</label>
                        <select class="form-control" id="select-Atencion">
                          <option value="0">Seleccione una opcion</option>
                        </select>
                    </div>
                                
                    <!--INSTRUCCIONES DE ATENCION-->
                    <div class="form-group">
                        <label for="exampleFormControlTextarea1">Instrucciones de Atención</label>
                        <textarea class="form-control" id="exampleFormControlTextarea1" rows="3"></textarea>
                    </div>
                                 
                    <!--DOCUMENTO-->
                    <div class="form-group">
                        <label for="exampleFormControlFile1">Documento</label>
                        <input type="file" class="form-control-file" id="exampleFormControlFile1">
                    </div>
                </form>
>>>>>>> 7861b72eb1d989b7f45e50918b9c28a97364eb83

            </div><!--CARD BODY TERMINA-->
        </div><!--CARD TERMINA-->


    </div><!--CONTAINER TERMINA-->

<<<<<<< HEAD
<script>
    
    $(document).ready(function()
    {
        consulta_Select("tipoturno", "tipoturno");
        consulta_Select("arearemite", "arearemite");
        consulta_Select("areabeneficiada", "areabeneficiada");
        consulta_Select("responsableatencion", "responsableatencion");
    });

    function consulta_Select(tabla, select)
    {
      $.ajax({
          url:"consulta_Select.php?tabla="+tabla,
          type:"GET",
          dataType:"json",
          cache:false,
          contentType: false,
          encode:true,
          processData: false,
          beforeSend: function()
          {
               
          },
          success: function(datos)
          {
              var t_re=document.getElementById(select);
              t_re.innerHTML="<option value='0'>Seleccione una opción</option>";
              for (i in datos) 
              {
                  t_re.innerHTML +=`<option value='${datos[i].name}'>${datos[i].name}</option>`;
              }
          },
          error: function(XMLHttpRequest)
          {
             console.log("error"+XMLHttpRequest); 
          }
      });
    }

</script>
    

    <!-- BOOSTRAP Y JQUERY -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>

=======
   
     <!--Esta funcion debe de ir despues de jque-->
     <script type="text/javascript">
        $( document ).ready(function() {
            consulta_Select('tipoturno','Turno');
            consulta_Select('arearemite','Remite');
            consulta_Select('areabeneficiada','Beneficia');
            consulta_Select('responsableatencion','Atencion');
        });
    </script>

   <!-- jQuery first, then Popper.js, then Bootstrap JS -->
>>>>>>> 7861b72eb1d989b7f45e50918b9c28a97364eb83
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script type="text/javascript" src="js/bootstrap.min.js"></script>
    <script src="js/jquery-3.2.1.min.js"></script>
    
</body>
</html>