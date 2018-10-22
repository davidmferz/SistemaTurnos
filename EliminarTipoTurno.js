function nuevoTurno(e)
{
	//e.preventDefault();//evita que se recarge la pagina
	//var formData = new FormData(document.getElementById("form-vtnTurno"));
	var vtnTurnoEliminar= document.getElementById("select-vtnTurnoEliminar").value;
	//var tipo=document.getElementsByTagName("FORM");
	
	var tipo=document.getElementsByTagName("FORM");
	console.log(tipo);
	console.log("entre");
	console.log(nom);
	
	$.ajax({
		url: 'EliminarTipoTurno.php',
		type: 'POST',
		dataType: 'json',
		data: {nom1: nom},
		beforeSend: function(){
     	console.log("Se está procesando la informacion ");
    },
	})
	.done(function(datos) {
		/* Se hizo bien 
		   LIMPIAMOS LOS DATOS DEL FORMULARIO
		 */ 
		 console.log("exitoX: "+datos.TipoturnoEliminar);
		 //console.log("resultado: "+datos.resultadoX);
        //document.getElementById("form-vtnTurno").reset();
	})
	.fail(function(datos) {
		console.log("error en "+datos.TipoturnoEliminar);
		
	})
	.always(function() {
		console.log("complete");
		
	});
}