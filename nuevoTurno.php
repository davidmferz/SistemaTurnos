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

    <!--INSTALACION DE REACT -->
    <script crossorigin src="https://unpkg.com/react@16/umd/react.development.js"></script>
    <script crossorigin src="https://unpkg.com/react-dom@16/umd/react-dom.development.js"></script>

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
    

    <div id="formularioTurno">
        
    </div>


     <!--Esta funcion debe de ir despues de jque-->
    <script src="altaTurno2.js"></script>
    
    <script>
        function agregarTurno2()
        {
            altaTurno2();
        }
    </script>

    <script>

    /*$("#miFormTurno").on("submit", function(e)//FUNCION AJAX PARA DAR DE ALTA UN NUEVO REPORTE
    {

        e.preventDefault();//evita que se recarge la pagina
        var nfolio = document.getElementById('folio').value;
        var ndocumento = document.getElementById('documento').value;
        var fecharecibe = document.getElementById('fechaRecibe').value;
        var fechadocumento = document.getElementById('fechaDocumento').value;
        var tipoturno = document.getElementById('select-Turno').value;
        var arearemite = document.getElementById('select-Remite').value;
        var arearbeneficia = document.getElementById('select-Beneficia').value;
        var ratencion = document.getElementById('select-Atencion').value;
        var iatencion = document.getElementById('exampleFormControlTextarea1').value;
        //var druta = document.getElementById('');
        var formData = new FormData(document.getElementById("miFormTurno"));

        console.log(nfolio+"\n"+
            ndocumento+"\n"+
            fecharecibe+"\n"+
            fechadocumento+"\n"+
            tipoturno+"\n"+
            arearemite+"\n"+
            arearbeneficia+"\n"+
            ratencion+"\n"+
            iatencion
        );

        $.ajax({
            url: 'buscaid.php',
            type: 'POST',
            dataType: 'json',
            data: {
                tipoturno1: tipoturno,
                arearemite1: arearemite,
                arearbeneficia1: arearbeneficia,
                ratencion1: ratencion
            },
            beforeSend: function(){
                console.log("Se está buscando el ID ");
            },
        })
        .done(function(datos) {
            $.ajax({
                url: 'agregarTurno.php',
                type: 'POST',
                dataType: 'json',
                data: {nfolio1: nfolio,
                    ndocumento1: ndocumento,
                    fecharecibe1: fecharecibe,
                    fechadocumento1: fechadocumento,
                    tipoturno1: datos.resultadoturno,
                    arearemite1: datos.resultadoremite,
                    arearbeneficia1: datos.resultadobeneficiada,
                    ratencion1: datos.resultadoatencion,
                    iatencion1: iatencion
                },
                beforeSend: function()
                {
                    console.log("Se está intentando hacer la inserción");
                },
            })
            .done(function() {
                console.log("se insertó");
                $.ajax(
                {
                    url: 'agregarDocumento.php',
                    type: 'POST',
                    contentType: "application/x-www-form-urlencoded; charset=UTF-8",
                    data: formData,
                    cache:false,
                    contentType: false,
                    encode:true,
                    processData: false,
                    beforeSend: function()
                    {
                        console.log("trabajando en el documento");
                    },                                
                })
                .done(function(trae)
                {
                    console.log("a webito");
                    console.log(trae.destino);
                })
                .fail(function()
                {
                    console.log("no cargó la imagen");
                })
                .always(function()
                {
                    console.log("terminó");
                });
            })
            .fail(function() 
            {
                console.log("aqui ya no ");
            })
            .always(function() 
            {
                console.log("complete");
            });
        })
        .fail(function(datos) 
        {
            console.log("error en "+datos.resultado);
        })
        .always(function() 
        {
            console.log("completado1");
        });
        
    });
    */
    </script>

     <script type="text/javascript">
        $( document ).ready(function() {
            consulta_Select('tipoturno','Turno');
            consulta_Select('arearemite','Remite');
            consulta_Select('areabeneficiada','Beneficia');
            consulta_Select('responsableatencion','Atencion');
        });
    </script>

   <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script type="text/javascript" src="js/bootstrap.min.js"></script>
    <script src="js/jquery-3.2.1.min.js"></script>
    
     <!--FORMULARIO DE NUEVO TURNO-->
    <script src="js/formularioTurno-dist.js"></script> 
</body>
</html>