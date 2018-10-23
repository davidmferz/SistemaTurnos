function validar_nombre(aux, aux2, tabla2, vtn)
{
	var nuevo = document.getElementById(aux2).value;
	var valor = document.getElementById(aux).value;
    console.log(nuevo);
    console.log(valor);
    console.log(tabla2);

	$(aux2).removeClass("alert-danger");
	$(aux2).removeClass("alert-success");

	if(nuevo.length <= 1)
	{
		$(aux2).addClass("alert-danger");
		alert("El campo no debe estar vacío");
	}
	else
	{
			$.ajax({
			url: 'validar_nombre.php',
			type: 'POST',
			dataType: 'JSON',
			data: {nombre1: nuevo, tabla1: tabla2},
		})
		.done(function(datos) {
			if(datos.resultado)
			{
				console.log("Se repite el nombre");
				$(aux2).addClass("alert-danger");
				alert("El nombre se repite");
			}
			else
			{
				console.log("No se repite el nombre");
				$(aux2).addClass("alert-success");	
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
			           limpia();
			        })
			        .fail(function(datos) {
			          console.log("error en "+datos.resultado);
			        })
			        .always(function() {
			          console.log("complete");
			      });
			}
			
		})
		.fail(function(XMLHttpRequest) {
			console.log("error: "+XMLHttpRequest.responseText);
		})
		.always(function() {
			
		});

	}
}