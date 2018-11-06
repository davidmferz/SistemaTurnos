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
	
	if(nom.length <= 1)
	{
		alert("El campo no debe estar vacío");
	}
	else
	{
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
	//aqui termina else	
	}
}

function nuevoAtencion(tabla, vtn, pass, confi)
{
	var aux="text-";
	aux += vtn;
	var nom = document.getElementById(aux).value;
	var con = document.getElementById(pass).value;
	var con1 = document.getElementById(confi).value;
	console.log(tabla);
	console.log(aux);
	console.log("el tipo de venatana es: "+vtn);
	console.log("el nombre que se va a guardar es "+nom);
	
	if(nom.length <= 1)
	{
		alert("El campo nombre no debe estar vacío");
	}
	else
	{
		if (con.length <= 1)
		{
			alert("El campo contraseña no debe estar vacío");
		}
		else
		{
			if (con1.length <= 1)
			{
				alert("Confirme la contraseña");
			}
			else
			{
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

							if (con === con1)
							{
								$.ajax({
							        url: 'enviarTurno.php',
									type: 'POST',
									dataType: 'json',
									data: {nom1: nom, tipo1: vtn, pass1: con},
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
							else
							{
								console.log("La contraseña es diferente");
								alert("La contraseña no coincide");
							}
						}
					})
					.fail(function(XMLHttpRequest) {
						console.log("error: "+XMLHttpRequest.responseText);
					})
					.always(function() {
						
					});
			}
		}
	}
}