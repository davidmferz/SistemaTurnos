<?php
  session_start();
  if(!$_SESSION)
  {
    //Si tiene que estar aqui
  }
    else
    {
        switch ($_SESSION["user"]) 
        {
            case 0://Administrador
                header('Location: index000.php');   
            break;
        }
    }
  
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Inicio</title>

  <!--BOOSTRAP-->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
  <!--COSAS PARA EL MATERIAL DESING-->
  <link rel="stylesheet" href="//fonts.googleapis.com/icon?family=Material+Icons">
  <link href='http://fonts.googleapis.com/css?family=Roboto:400,300,300italic,500,400italic,700,700italic' rel='stylesheet' type='text/css'>
  <link rel="stylesheet" href="//storage.googleapis.com/code.getmdl.io/1.0.1/material.teal-red.min.css" />
  <script src="//storage.googleapis.com/code.getmdl.io/1.0.1/material.min.js"></script>

  <!--CSS-->
  <script src="css/estilos.css"></script>
</head>
<body>
  
  <div class="row">
    <div class="col-md-6">
      <div class="centrar" style="margin: auto;">
          <div style="margin-top: 28px;">
              <div class="panel-heading col-md-8" style="margin-bottom: 35px; padding: 4px;" >              
                              </div>
          </div>
          <div style="margin-right: 28px;">
            <!--TITULO DE SIEObras-->
            <div class="logo" style="text-align: center;">
              <img src="img/logo.png" alt="Imagen no encontrada" style="max-width: 200px;">
            </div>
            <div class="col-md-12" style="text-align: center;">
              <br>
              <h2></h2>
              <h4>Sistema de Turnos</h4>
            </div>

            <!--LINEA DE COLORES-->
                    <div class="row">
                        <div class="col-md-4" style="background-color: #2C5234; border-color: #2C5234; margin-bottom: 35px; padding: 4px;" >
                        </div>
                        
                        <div class="col-md-8" style="background-color: #9C8412;border-color: #9C8412; margin-bottom: 35px; padding: 4px;" >              
                        </div>  
                    </div>                                     
          </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="m-t-4" style="text-align: center; margin-top: 75px">
        <div class="m-4">
          <img src="img/user4-128.png" alt="Imagen">
        </div>
        <form id="miFormAltas" class="m-4">
          <div class="espacio">
            <select class="form-control" name="user">
            <option value="0">Administrador</option>
            
          </select>
          </div>
          <div class="espacio">
            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
              <input class="mdl-textfield__input color" name="pass" type="password" id="password"/>
              <label class="mdl-textfield__label color" for="password">Contraseña</label>
            </div>
          </div>
          <div class="row" id="mensaje">
            
          </div>
          <div class="row">
            <div class="col-md-12" style="text-align: center;">
              <button type="submit" class="btn btn-outline-success color">Enviar</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
  <!-- BOOSTRAP Y JQUERY -->
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>

  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
  
  <!-- JQuery-->
    <script src="js/jquery-3.2.1.min.js"></script>

  <!--VALIDAR EL LOGIN--> 
  <script>
        $("#miFormAltas").on("submit", function(e)//FUNCION AJAX PARA DAR DE ALTA UNA NUEVA OBRA
        {
             e.preventDefault();//evita que se recarge la pagina
             var formData = new FormData(document.getElementById("miFormAltas"));
           
            $.ajax({
                    url:"validar_login.php",
                    type:"POST",
                    dataType:"html",
                    contentType: "application/x-www-form-urlencoded; charset=UTF-8",
                    data:formData,
                    cache:false,
                    contentType: false,
                    encode:true,
                    processData: false,
                    beforeSend: function()
                    {
                        
                    },
                    success: function(datos)
                    {
                        if(datos=="1")
                        {
                          location.reload();
                        }
                        else
                        {
                          alert("Usuario y/o Contraseña incorrectos, intente de nuevo");
                        }                           
                    },
                    error: function(XMLHttpRequest,data)
                    {
                      
                    } 
                });
             
          });
    </script>
</body>
</html>