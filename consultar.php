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
    
    <!--BOOSTRAP-->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

    <!-- JQuery           -->
    <script src="js/jquery-3.2.1.min.js"></script>

    <!-- JS de Bootstrap  -->
   <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link rel="stylesheet" href="css/animate.css">
    <link rel="stylesheet" href="css/estilos.css">

    <!--Consulta select-->
    <script src="js/consulta_Select.js"></script>

    <!--INSTALACION DE REACT JS-->
    <script crossorigin src="https://unpkg.com/react@16/umd/react.development.js"></script>
    <script crossorigin src="https://unpkg.com/react-dom@16/umd/react-dom.development.js"></script>

    <script>var Sid = null;</script>

</head>
<body>

    <div class="container-fluid">

        <!--TITULO-->
        <div class="row" style="text-align: center; margin-bottom: 15px;">
                <!--TITULO DE Sistema Turnos-->
                <div class="col-md-1 align-text-top" style="text-align: center; margin-top: 25px;">
                    <img src="img/logo.png" alt="Imagen no encontrada" style="width: 100%">
                </div>
                <div class="col-md-11 align-middle" style="text-align: left; margin-top: 35px;">
                    <h3 style="margin-top: 0px;">Sistema de Turnos</h3>
                    <!--LINEA DE COLORES-->
                    <div class="row">
                            <div class="panel-heading col-md-3" style="background-color: #2C5234; border-color: #2C5234; margin-bottom: 35px; padding: 4px;" >
                            </div>
                            
                            <div class="panel-heading col-md-8" style="background-color: #9C8412;border-color: #9C8412; margin-bottom: 35px; padding: 4px;" >              
                            </div>  
                    </div>
                </div>
        </div>
        
        <div style="margin-left: 15px;">
            <h4>Inicio sesión como: <b> <?php echo $bienvenido."  " ?> </b> <a href="salir.php" style="color:red"> salir</a></h4>
        </div>
    
        <div class="row">
            <div class="col-md-12" style="margin: 0px 35px;">
                <a href="index001.php"><img src="img/regresar.png" alt="imagen">Regresar</a>
            </div>
        </div>
        
        <!--COMO DESEA REALIZAR LA BUSQUEDA-->
        <div class="panel-body animated bounceIn" id="panel-principal" style="display: block;">
            <!--COMO DESEA REALIZAR LA CONSULTA-->
            <div class="row" id="capa" style="display: block;  margin: 20px 0px;">
                <div class="col-md-12" style="text-align: left; color: #1976d2">
                    <h2>Filtro de busqueda</h2>
                </div>
                <div class="col-md-12">
                    <select class="form-control form-control-lg" name="opc_buscar" id="opc_buscar">
                        <option value="0">Seleccionar una opción</option>
                        <option value="1">Tipo de Turno</option>
                        <option value="2">Área que Remite</option>
                        <option value="3">Área que Beneficia</option>
                        <option value="4">Departamento Responsable de Atención</option>
                        <option value="5">No. de Folio</option>
                        <option value="6">No. de Documento</option>
                    </select>
                </div>
            </div>

            <!--TIPO DE TURNO-->
            <div class="row animated" id="capa1" style="display: none;margin:15px;">
                <div class="col-md-12" style="color: #1976d2">
                    <h2>Tipo de Turno</h2>
                </div>
                <div class="col-md-8 form-group">
                    <div class="input-group">
                        <span class="input-group-addon">
                            <span class="fa fa-building-o"></span>
                        </span>
                        <select class="form-control form-control-lg" id="tipoTurno" name="tipoTurno" onchange="">
                        <option value="0">Seleccionar una opción</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4" style="text-align: right;">
                    <button type="button" class="btn btn-link" onclick="mostrar_opc(1)">Regresar</button>
                </div>
            </div>
            
            <!--AREA QUE REMITE-->
            <div class="row animated" id="capa2" style="display: none;margin:15px;">
                <div class="col-md-12" style="color: #1976d2">
                    <h2>Área que Remite</h2>
                </div>
                <div class="col-md-8 form-group">
                    <div class="input-group">
                        <span class="input-group-addon">
                            <span class="fa fa-building-o"></span>
                        </span>
                        <select class="form-control form-control-lg" id="areaRemite" name="areaRemite">
                        <option value="0">Seleccionar una opción</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4" style="text-align: right;">
                    <button type="button" class="btn btn-link" onclick="mostrar_opc(2)">Regresar</button>
                </div>
            </div>

            <!--ÁREA QUE BENEFICIA-->
            <div class="row animated" id="capa3" style="display: none;margin:15px;">
                <div class="col-md-12" style="color: #1976d2">
                    <h2>Área que Beneficia</h2>
                </div>
                <div class="col-md-8 form-group">
                    <div class="input-group">
                        <span class="input-group-addon">
                            <span class="fa fa-building-o"></span>
                        </span>
                        <select class="form-control form-control-lg" id="areaBeneficia" name="areaBeneficia">
                        <option value="0">Seleccionar una opción</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4" style="text-align: right;">
                    <button type="button" class="btn btn-link" onclick="mostrar_opc(3)">Regresar</button>
                </div>
            </div>

            <!--DEPARTAMENTO RESPONSABLE DE ATENCIÓN-->
            <div class="row animated" id="capa4" style="display: none;margin:15px;">
                <div class="col-md-12" style="color: #1976d2">
                    <h2>Departamento Responsable de Atención</h2>
                </div>
                <div class="col-md-8 form-group">
                    <div class="input-group">
                        <span class="input-group-addon">
                            <span class="fa fa-building-o"></span>
                        </span>
                        <select class="form-control form-control-lg" id="departamento" name="departamento">
                        <option value="0">Seleccionar una opción</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4" style="text-align: right;">
                    <button type="button" class="btn btn-link" onclick="mostrar_opc(4)">Regresar</button>
                </div>
            </div>

            <!--NO. DE FOLIO-->
            <div class="row animated" id="capa5" style="display: none;margin:15px;">
                <div class="col-md-12" style="color: #1976d2">
                    <h2>No. de Folio</h2>
                </div>
                <div class="col-md-8 form-group">
                    <div class="input-group">
                        <span class="input-group-addon">
                            <span class="fa fa-building-o"></span>
                        </span>
                        <input type="text" class="form-control" id="no_Folio" name="no_Folio" placeholder="Ingrese Número de Folio" required="Campo obligatorio" maxlength="200">
                    </div>
                </div>
                <div class="col-md-4" style="text-align: right;">
                    <button type="button" class="btn btn-link" onclick="mostrar_opc(5)">Regresar</button>
                </div>
            </div>

            <!--NO. DE DOCUMENTO-->
            <div class="row animated" id="capa6" style="display: none;margin:15px;">
                <div class="col-md-12" style="color: #1976d2">
                    <h2>No. de Documento</h2>
                </div>
                <div class="col-md-8 form-group">
                    <div class="input-group">
                        <span class="input-group-addon">
                            <span class="fa fa-building-o"></span>
                        </span>
                        <input type="text" class="form-control" id="no_Documento" name="no_Documento" placeholder="Ingrese Número de Documento" required="Campo obligatorio" maxlength="200">
                    </div>
                </div>
                <div class="col-md-4" style="text-align: right;">
                    <button type="button" class="btn btn-link" onclick="mostrar_opc(6)">Regresar</button>
                </div>
            </div>

            <!--TABLA DE CONSULTA-->
            <div class="animated bounceIn" id="datos" style="margin:25px;">

            </div>
        </div>
    </div>

    <!--DESDE AQUI COMIENZA EL FORMULARIO SE CARGA CON REACT-->
    <div id="formularioTurno" style="display: none;">

    </div><!--AQUI TERMINA EL FORMULARIO-->
    
<script>
        
        $( document ).ready(function() {
            consultar();
            consulta_Select("tipoturno", "tipoTurno");
            consulta_Select("arearemite", "areaRemite");
            consulta_Select("areabeneficiada", "areaBeneficia");
            consulta_Select("responsableatencion", "departamento");
            consulta_Select("tipoturno", "select-Turno");
            consulta_Select("arearemite", "select-Remite");
            consulta_Select("areabeneficiada", "select-Beneficia");
            consulta_Select("responsableatencion", "select-Atencion");
        });

    //CUANDO EL USUARIO ELIJE UN TURNO
    $.fn.extend({
        animateCss: function(animationName, callback) {
            var animationEnd = (function(el) {
                var animations = {
                    animation: 'animationend',
                    OAnimation: 'oAnimationEnd',
                    MozAnimation: 'mozAnimationEnd',
                    WebkitAnimation: 'webkitAnimationEnd',
                };

                for (var t in animations) {
                    if (el.style[t] !== undefined) {
                      return animations[t];
                    }
                }
            })(document.createElement('div'));
            this.addClass('animated ' + animationName).one(animationEnd, function() {
                $(this).removeClass('animated ' + animationName);
                if (typeof callback === 'function') callback();
            });
            return this;
        },
    });

    var animationEnd = (function(el) {
      var animations = {
        animation: 'animationend',
        OAnimation: 'oAnimationEnd',
        MozAnimation: 'mozAnimationEnd',
        WebkitAnimation: 'webkitAnimationEnd',
      };

      for (var t in animations) {
        if (el.style[t] !== undefined) {
          return animations[t];
        }
      }
    })(document.createElement('div'));


    function consultar(idC, consulta, opc)
    {
        $.ajax({
            url: 'consulta_buscar.php',
            type: 'POST',
            dataType: 'html',
            data: {idC: idC, consulta: consulta, opc: opc},
        })
        .done(function(respuesta) 
        {
            console.log("Regrese la consulta de tabla");
            $("#datos").html(respuesta);
        })
        .fail(function() 
        {
            console.log("error");
        });
    }

    function llenar(id)
    {
        $("#formularioTurno").css("display", "block");
        
        $("input[type='text']").prop('disabled', "true");
        $("input[type='textarea']").prop('disabled', "true");
        $("input[type='number']").prop('disabled', "true");
        $("input[type='date']").prop('disabled', "true");
        $("input[type='file']").prop('disabled', "true");
        $("select").prop('disabled', "true");
        $("button[type='button']").css('display', "none");
        $("textarea").prop('disabled', "true");
        llenarF(id);
    }

    $(opc_buscar).change(function()//PARA MOSTRAR EL FITRO DE BUSQUEDA SELECCIONADO
    {

        var opc =document.getElementById("opc_buscar").value;
        if(opc != 0)
        {
            //Removemos la clase con la que inicia
            $("#capa").removeClass("bounceInUp");
            //Agregamos la clase con la que sale
            $("#capa").addClass("bounceOutLeft");
            //Ocultamos para que no ocupe espacio
            $("#capa").css("display", "none");
            switch(opc)
            {
                case '1':
                    //Removemos la clase con la que se fue anteriormente
                    $("#capa1").removeClass("bounceOutRight");
                    //Mostramos la nueva clase
                    $("#capa1").css("display", "block");
                    //Agregamos la animacion de entrada
                    $("#capa1").addClass("bounceInRight");

                break;

                case '2':
                    //Removemos la clase con la que se fue anteriormente
                    $("#capa2").removeClass("bounceOutRight");
                    //Mostramos la nueva clase
                    $("#capa2").css("display", "block");
                    //Agregamos la animacion de entrada
                    $("#capa2").addClass("bounceInRight");
                break;

                case '3':
                    //Removemos la clase con la que se fue anteriormente
                    $("#capa3").removeClass("bounceOutRight");
                    //Mostramos la nueva clase
                    $("#capa3").css("display", "block");
                    //Agregamos la animacion de entrada
                    $("#capa3").addClass("bounceInRight");
                break;

                case '4':
                    //Removemos la clase con la que se fue anteriormente
                    $("#capa4").removeClass("bounceOutRight");
                    //Mostramos la nueva clase
                    $("#capa4").css("display", "block");
                    //Agregamos la animacion de entrada
                    $("#capa4").addClass("bounceInRight");
                break;

                case '5':
                    //Removemos la clase con la que se fue anteriormente
                    $("#capa5").removeClass("bounceOutRight");
                    //Mostramos la nueva clase
                    $("#capa5").css("display", "block");
                    //Agregamos la animacion de entrada
                    $("#capa5").addClass("bounceInRight");
                break;

                case '6':
                    //Removemos la clase con la que se fue anteriormente
                    $("#capa6").removeClass("bounceOutRight");
                    //Mostramos la nueva clase
                    $("#capa6").css("display", "block");
                    //Agregamos la animacion de entrada
                    $("#capa6").addClass("bounceInRight");
                break;
            }
        }
    });

    function mostrar_opc(opc)//PARA REGRESAR AL FITRO DE BUSQUEDA ANTERIOR 
    {
        switch(opc)
        {
            case 1:
                //Removemos la clase con la que inicia
                $("#capa1").removeClass("bounceInRight");
                //Agregamos la clase con la que sale
                $("#capa1").addClass("bounceOutRight");
                 //Ocultamos la capa 
                $("#capa1").css("display", "none");
            break;

            case 2:
                //Removemos la clase con la que inicia
                $("#capa2").removeClass("bounceInRight");
                //Agregamos la clase con la que sale
                $("#capa2").addClass("bounceOutRight");
                 //Ocultamos la capa 
                $("#capa2").css("display", "none");
            break;

            case 3:
                //Removemos la clase con la que inicia
                $("#capa3").removeClass("bounceInRight");
                //Agregamos la clase con la que sale
                $("#capa3").addClass("bounceOutRight");
                 //Ocultamos la capa 
                $("#capa3").css("display", "none");
            break;

            case 4:
                //Removemos la clase con la que inicia
                $("#capa4").removeClass("bounceInRight");
                //Agregamos la clase con la que sale
                $("#capa4").addClass("bounceOutRight");
                 //Ocultamos la capa 
                $("#capa4").css("display", "none");
            break;

            case 5:
                //Removemos la clase con la que inicia
                $("#capa5").removeClass("bounceInRight");
                //Agregamos la clase con la que sale
                $("#capa5").addClass("bounceOutRight");
                 //Ocultamos la capa 
                $("#capa5").css("display", "none");
            break;

            case 6:
                //Removemos la clase con la que inicia
                $("#capa6").removeClass("bounceInRight");
                //Agregamos la clase con la que sale
                $("#capa6").addClass("bounceOutRight");
                 //Ocultamos la capa 
                $("#capa6").css("display", "none");
            break;
        }
        //Mostramos el menu de opc
        $("#capa").css("display", "block");
        $("#capa").removeClass("bounceOutLeft");
        $("#capa").addClass("bounceInLeft");

        //RESETEAMOS EL VALOR DEL SELECT
        $('#opc_buscar').val($('#opc_buscar > option:first').val());
        document.getElementsByTagName("text").innetHTML = null;
        consultar();
    }

     //LIKE TIPO DE TURNO
    $(document).on('change','#tipoTurno', function(){
        var valor = $(this).val();
        var idC = "";
        if(valor != "")
        {
           consultar(idC,valor,"1");
        }
        else
        {
            consultar();
        }
    });

    //LIKE ÁREA QUE REMITE
    $(document).on('change','#areaRemite', function(){
        var valor = $(this).val();
        var idC = "";
        if(valor != "")
        {
           consultar(idC,valor,"2");
        }
        else
        {
            consultar();
        }
    });

    //LIKE ÁREA QUE BENEFICIA
    $(document).on('change','#areaBeneficia', function(){
        var valor = $(this).val();
        var idC = "";
        if(valor != "")
        {
           consultar(idC,valor,"3");
        }
        else
        {
            consultar();
        }
    });

    //LIKE DEPARTAMENTO
    $(document).on('change','#departamento', function(){
        var valor = $(this).val();
        var idC = "";
        if(valor != "")
        {
           consultar(idC,valor,"4");
        }
        else
        {
            consultar();
        }
    });

    //LIKE  NO. DE FOLIO
    $(document).on('keyup','#no_Folio', function(){
        var valor = $(this).val();
        var idC = "";
        if(valor != "")
        {
           consultar(idC,valor,"5");
        }
        else
        {
            consultar();
        }
    });

    //LIKE NO. DE DOCUMENTO
    $(document).on('keyup','#no_Documento', function(){
        var valor = $(this).val();
        var idC = "";
        if(valor != "")
        {
           consultar(idC,valor,"6");
        }
        else
        {
            consultar();
        }
    });

    function agregarTurno2()
    {

    }


    </script>
    
    <!--LLAMA A QUE SE PINTE EL FORMULARIO -->
    <script src="js/formularioTurno-dist.js"></script>

    <!--SE LLENA EL FORMULARIO -->
    <script src="js/llenarFormularioTurno.js"></script>

</body>
</html>