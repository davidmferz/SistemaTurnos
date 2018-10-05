function nuevoTurno()
{
	//e.preventDefault();//evita que se recarge la pagina
	var formData = new FormData(document.getElementById("form-vtnTurno"));
	console.log("entre");
	$.$.ajax({
		url: 'enviarTurno.php',
		type: 'POST',
		dataType: 'json',
		data: formData,
	})
	.done(function() {
		/* Se hizo bien 
		   LIMPIAMOS LOS DATOS DEL FORMULARIO
		 */ 
		 console.log("resultado: "+datos.resultadoX);
        document.getElementById("form-vtnTurno").reset();
	})
	.fail(function(datos) {
		console.log("error");
		console.log("resultado: "+datos.resultadoX);
	})
	.always(function() {
		console.log("complete");
		console.log("resultado: "+datos.resultadoX);
	});
}