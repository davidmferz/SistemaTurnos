function llenarF(id)
    {
        //REMOVEMOS LA ANIMACION CON LA QUE INICIA LA PAGINA PARA PONER UNA ANIMACION DE SALIDA
        $('#panel-principal').removeClass("bounceIn");
        $('#datos').removeClass("bounceIn");

        //AGREGAMOS LAS ANIMACIONES DE SALIDA
        $('#datos').addClass('bounceOut');
        $('#panel-principal').addClass('bounceOut').one(animationEnd, function(){
          $("#formularioTurno").css("display", "block");

          //OCULTAMOS FILTRO DE BUSQUEDA Y TABLA PARA QUE NO OCUPEN ESPACIO EN EL NAVEGADOR
          $('#panel-principal').css("display", "none");
          $('#datos').css("display", "none");

          $('#panel-principal').removeClass("bounceOut");
          $('#datos').removeClass("bounceOut");
          window.scrollTo(0,300);
        });

        //document.getElementById("FormTurno").reset();
        $.ajax({
            url: 'llenar.php',
            type: 'POST',
            dataType: 'json',
            data: {id: id},
        })
        .done(function(datos) 
        {
            $("#folio").val(datos.folio);
            $("#documento").val(datos.documento);
            $("#fechaRecibe").val(datos.fechaRecibe);
            $("#fechaDocumento").val(datos.fechaDocumento);
            $("#instrucciones").val(datos.instruccionesAten);
            $("#archivo").val(datos.documentoRuta);

            //Llenar los select de tipos de turnos
            $("#select-Turno option[value="+datos.tipoTurno+"]").attr("selected",true);

            //Llenar los select de area que remite
            $("#select-Remite option[value="+datos.areaRemite+"]").attr("selected",true);
            
            //Llenar los select de area que beneficia
            $("#select-Beneficia option[value="+datos.areaBeneficia+"]").attr("selected",true);
            
            //Llenar los select de departamento responsable de atencion
            $("#select-Atencion option[value="+datos.responsableAtencion+"]").attr("selected",true);

            /*
            //ARCHIVO: EmpresaContrato.pdf
            $('#MostrarContrato').removeClass("alert-danger");
            $('#MostrarContrato').removeClass("alert-success");

            if(datos.EmpresaContrato == "NO SE ENCONTRO ARCHIVO")
            {
                $mensaje="NO SE TIENE REGISTRO";
                $('#MostrarContrato').addClass("alert-danger");            
            }
            else
            {
                $mensaje="Se tiene registro del arhivo  <a href='mostrarPDF.php?id="+datos.EmpresaContrato+"' target='_blank'><button type='button' class='btn btn-link' >Mostrar PDF</button></a>"
                $('#MostrarContrato').addClass("alert-success");
            }
            document.getElementById("MostrarContrato").innerHTML=$mensaje;
            */
        })
        .fail(function(XMLHttpRequest,datos) 
        {
            console.log("Error en la funcion llenarF()  "+XMLHttpRequest.responseText);
        });
    }