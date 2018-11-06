function crearArchivo(){
	var nombre="en java";
	$.ajax({
		url: 'crearArchivo.php',
        type: 'POST',
        dataType: 'json',
        contentType: "application/x-www-form-urlencoded; charset=UTF-8",
        data: {nombre1: nombre},
	})
	.done(function(datos){
		console.log("se hizo"+nombre);
	})
	.fail(function(datos){
		console.log("error "+datos.resultado);
	})
	.always(function(){
		console.log("terminó");
	})
}