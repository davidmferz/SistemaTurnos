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
      <input type="text" id="idx" name="idx" style="display: none">
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

    <div id="ventanaEliminar">
        
    </div>

    <!-- DESDE AQUI COMIENZA LA VENTANA MODAL DE LA OPCION DE EDITAR -->
    <div class="modal fade" id="VentanaModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-Imagen" id="modalImagen" style="text-align: center;">
                    <!--Esta parte se carga dinamicamente-->
                    ...
                </div>

                <div class="modal-body" id="modalBody" style="text-align: justify;">
                    <!--Esta parte se carga dinamicamente-->
                    ...
                </div>
                <div class="modal-footer" id="modalBotones">
                    <!--Esta parte se carga dinamicamente-->
                    ...
                </div>
            </div>
        </div>
    </div><!--AQUI TERMINA LA VENTANA MODAL DE LA OPCION DE EDITAR-->

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
            <button type="button" class="btn btn-link" data-toggle="modal" data-target="#vtnTurno" onclick="consultaAgregar('vtnTurno')">
              <img src="img/agregar.png" alt="Imagen">
            </button>
          </td>
          <td>
            <button type="button" class="btn btn-link" onclick="consulta_Select('tipoturno','vtnTurnoEditar')" data-toggle="modal" data-target="#vtnTurnoEditar">
              <img src="img/editar.png" alt="Imagen">
            </button>
          </td>
          <td>
            <button type="button" class="btn btn-link" onclick="consulta_Select('tipoturno','vtnTurnoEliminar')" data-toggle="modal" data-target="#vtnTurnoEliminar">
              <img src="img/eliminar_32.png" alt="Imagen">
            </button>
          </td>
         

        </tr>
        <tr>
          <th scope="row">Area que Remite</th>
          <td>
            <button type="button" class="btn btn-link" data-toggle="modal" data-target="#vtnRemite" onclick="consultaAgregar('vtnRemite')">
              <img src="img/agregar.png" alt="Imagen">
            </button>
          </td>
          <td>
            <button type="button" class="btn btn-link" onclick="consulta_Select('arearemite','vtnRemiteEditar')" data-toggle="modal" data-target="#vtnRemiteEditar">
              <img src="img/editar.png" alt="Imagen">
            </button>
          </td>

           <td>
            <button type="button" class="btn btn-link" onclick="consulta_Select('arearemite','vtnRemiteEliminar')" data-toggle="modal" data-target="#vtnRemiteEliminar">
              <img src="img/eliminar_32.png" alt="Imagen">
            </button>
          </td>

        </tr>
        <tr>
          <th scope="row">Area que Beneficia</th>
          <td>
            <button type="button" class="btn btn-link" data-toggle="modal" data-target="#vtnBeneficia" onclick="consultaAgregar('vtnBeneficia')">
                <img src="img/agregar.png" alt="Imagen">
            </button>
          </td>
          <td>
            <button type="button" class="btn btn-link" onclick="consulta_Select('areabeneficiada','vtnBeneficiaEditar')" data-toggle="modal" data-target="#vtnBeneficiaEditar">
              <img src="img/editar.png" alt="Imagen">
            </button>
          </td>

            <td>
            <button type="button" class="btn btn-link" onclick="consulta_Select('areabeneficiada','vtnBeneficiaEliminar')" data-toggle="modal" data-target="#vtnBeneficiaEliminar">
              <img src="img/eliminar_32.png" alt="Imagen">
            </button>
          </td>

        </tr>
        <tr>
          <th scope="row">Departamento Responsable de Atención</th>
          <td>
            <button type="button" class="btn btn-link" data-toggle="modal" data-target="#vtnAtencion" onclick="consultaAgregar('vtnAtencion')">
                <img src="img/agregar.png" alt="Imagen">
            </button>
          </td>
          <td>
            <button type="button" class="btn btn-link" onclick="consulta_Select('responsableatencion','vtnAtencionEditar')" data-toggle="modal" data-target="#vtnAtencionEditar">
              <img src="img/editar.png" alt="Imagen">
            </button>
          </td>
          <!--elim-->
          <td>
            <button type="button" class="btn btn-link" onclick="consulta_Select('responsableatencion','vtnAtencionEliminar')" data-toggle="modal" data-target="#vtnAtencionEliminar">
              <img src="img/eliminar_32.png" alt="Imagen">
            </button>
          </td>


        </tr>
      </tbody>
    </table>

    </div><!--Aqui termina el container-->


<script>

var tabla2 = null;
var vtn = null;
  function consulta_Select(tabla, select)
    {
      tabla2=tabla;
      vtn=select;
      var aux = "select-";
      aux += vtn;
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
                    var t_re=document.getElementById(aux);
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
      console.log(valor);
      console.log(nuevo);
      console.log(tabla2);
      
      

      var llamada = $.ajax({
        url:"editar.php",
        type:"POST",
        dataType:"json",
        contentType: "application/x-www-form-urlencoded; charset=UTF-8",
        data:{valor1 : valor, nuevo1 : nuevo, tabla1 :  tabla2},
        beforeSend: function()
        {
          var r= confirm("Realmente desea editar el campo\n"+valor+" por "+nuevo);
          if (r==true) {
            

          }
          else
          {
            
            llamada.abort();
          }
        },
      })
      .done(function(datos) {
         console.log("exitoX: "+datos.resultado);
         consulta_Select(tabla2, vtn);
      })
      .fail(function(datos) {
        console.log("error en "+datos.resultado);
      })
      .always(function() {
        console.log("complete");
    });

      /*document.getElementById("modalImagen").innerHTML=`<img src="img/Informacion-128.png" alt="Imagen no econtrada">`; 
      if(!(nuevo.trim() == ""))
      {
        console.log("si entro");
        document.getElementById("modalTitle").innerHTML="<h4>¿Esta seguro?<h4>";
        document.getElementById("modalBody").innerHTML="¿Esta seguro en cambiar <b>"+valor+"</b> a <b>"+nuevo+"</b>";
        
        document.getElementById("modalBotones").innerHTML=`
            <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="limpiar()">NO, Cancelar
            </button>
            <button type="button" class="btn btn-primary" data-dismiss="modal" 
                onclick="editar('${valor}','${nuevo}')">Si, Cambiar
            </button>
        `;
      }
      else
      {
        console.log("no entro");
        document.getElementById("modalTitle").innerHTML="¿Estas seguro?";
        document.getElementById("modalBody").innerHTML="El campo esta vacio, por favor intente de nuevo";
      }
      console.log("no hizo nada :´(");*/
    }

  function limpia()
  {
    var aux2 = "text-";
    aux2 += vtn;
    document.getElementById(aux2).value=null;
  }

  /*function editar(valor,nuevo)
  {
    var boton = document.getElementById("btnRecursos");
    $.ajax({
        url:"EditarRecursos.php?id="+id+"&nombre="+t_recurso,
        type:"GET",
        dataType:"HTML",
        cache:false,
        contentType: false,
        encode:true,
        processData: false,
        beforeSend: function()
        {
                     
        },
        success: function(datos)
        {
            listaRecursos();
            document.getElementById("otro1").value="";
            boton.innerHTML=`
                <div style="color:#fff; padding-bottom: 0.4em;">-</div>
                 <button type="button" id="btnAgregar" class="btn btn-success media-middle" name="btnIngresar" onclick="otro()">Agregar
                 </button>
            `;
           llenar(Sid);
        }
    });
  }*/

</script>

<script>
  function consultaAgregar(ventana)
  {
    var vent=ventana;
    document.getElementById("idx").value=vent;
  }
</script>

<!-- VENTANA MODAL ENLACE -->
    <script src="js/nuevoTurno.js"></script>
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