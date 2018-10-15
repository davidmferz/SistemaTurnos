function nuevoTurno(e)
{
	//e.preventDefault();//evita que se recarge la pagina
	//var formData = new FormData(document.getElementById("form-vtnTurno"));
	var nom= document.getElementById("nombre").value;
	console.log("entre");
	console.log(nom);
	
	$.ajax({
		url: 'enviarTurno.php',
		type: 'POST',
		dataType: 'json',
		data: {nom1: nom},
		beforeSend: function(){
     	console.log("Se está procesando la info ");
    },
	})
	.done(function(datos) {
		/* Se hizo bien 
		   LIMPIAMOS LOS DATOS DEL FORMULARIO
		 */ 
		 console.log("exito");
		 //console.log("resultado: "+datos.resultadoX);
        //document.getElementById("form-vtnTurno").reset();
	})
	.fail(function(datos) {
		console.log("error en "+datos.resultado);
		
	})
	.always(function() {
		console.log("complete");
		
	});
}