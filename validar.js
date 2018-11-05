function validar_nombre(aux, aux2, tabla2, vtn)
{
	var nuevo = document.getElementById(aux2).value;
	var valor = document.getElementById(aux).value;
    console.log(nuevo);
    console.log(valor);
    console.log(tabla2);

	if(nuevo.length <= 1)
	{
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
				alert("El nombre se repite");
			}
			else
			{
				console.log("No se repite el nombre");	
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
			        	alert("Se edito con exito");
			           console.log("exitoX: "+datos.resultado);
			           limpia();
			           //consulta_Select(tabla2, vtn);			           
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