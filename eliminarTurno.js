function eliminarTurno(tabla2, vtn)
{
	var aux = "select-";
    aux += vtn;
	console.log("hola dianis");
	//e.preventDefault();//evita que se recarge la pagina
	//var formData = new FormData(document.getElementById("form-vtnTurno"));
	console.log(tabla2);
	console.log(aux); 
	var idEliminar= document.getElementById(aux).value;
	console.log("entre");
	console.log(idEliminar);
	

	var llamada=$.ajax({
		url: 'eliminarTipoTurno.php',
		type: 'POST',
		dataType: 'json',
		data: {idEliminar1: idEliminar, tabla1: tabla2},
		beforeSend: function(){
     	console.log("Se está procesando la informacion ");
     	var r=confirm("Realmente desea eliminar el campo\n"+idEliminar);
     	if (r==true) {

     	}else{
     		llamada.abort();
     	}
    },
	})
	.done(function(datos) {
		/* Se hizo bien 
		   LIMPIAMOS LOS DATOS DEL FORMULARIO
		 */ 
		 console.log("exitoX: "+datos.resultado);
		 //console.log("resultado: "+datos.resultadoX);
        //document.getElementById("form-vtnTurno").reset();
        //consulta_Select(tabla2, vtn);

        alert("Se eliminó correctamente");
	})
	.fail(function(datos) {
		console.log("error en "+datos.resultado);
		
	})
	.always(function() {
		console.log("complete");
		
	});
}