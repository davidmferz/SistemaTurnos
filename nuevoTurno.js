function nuevoTurno(tabla, vtn)
{
	//e.preventDefault();//evita que se recarge la pagina
	//var formData = new FormData(document.getElementById("form-vtnTurno"));
	var auxiliar="text-";
	//var tipo= document.getElementById("idx").value;
	var tipo=vtn;
	auxiliar += tipo;
	var nom= document.getElementById(auxiliar).value;
	console.log(tabla);
	console.log(auxiliar);
	console.log("el tipo de venatana es: "+tipo);
	console.log("el nombre que se va a guardar es "+nom);
	


	$.ajax({
	url: 'validar_nombre.php',
	type: 'POST',
	dataType: 'JSON',
	data: {nombre1: nom, tabla1: tabla},
	beforeSend: function(){
		console.log("si entre");
	}
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
					
				$.ajax({
			        url: 'enviarTurno.php',
					type: 'POST',
					dataType: 'json',
					data: {nom1: nom, tipo1: tipo},
					beforeSend: function(){
			     	console.log("Se está procesando la informacion ");
			      },
				})
			        .done(function(datos) {
			           console.log("exitoX: "+datos.resultado);
			           alert("Se agregó correctamente");
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





	/*
	$.ajax({
		url: 'enviarTurno.php',
		type: 'POST',
		dataType: 'json',
		data: {nom1: nom, tipo1: tipo},
		beforeSend: function(){
     	console.log("Se está procesando la informacion ");
    },
	})
	.done(function(datos) {
		/* Se hizo bien 
		   LIMPIAMOS LOS DATOS DEL FORMULARIO
		 
		 console.log("exitoX: "+datos.resultado);
		 
		 //console.log("resultado: "+datos.resultadoX);
        //document.getElementById("form-vtnTurno").reset();
	})
	.fail(function(datos) {
		console.log("error en "+datos.resultado);
		
	})
	.always(function() {
		console.log("complete");
		
	});
	*/
}