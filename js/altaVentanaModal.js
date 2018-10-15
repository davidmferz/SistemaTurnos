
//Funcion para dar  de alta TIPO DE TURNO
function enviarTurno(e)
{
	e.preventDefault();//evita que se recarge la pagina
	var formData = new FormData(document.getElementById("form-vtnTurno"));
	console.log("entre");
	alert("entré");
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


//Funcion para dar de alta AREA QUE REMITE 
function enviarRemite(e)
{
	e.preventDefault();//evita que se recarge la pagina
	var formData = new FormData(document.getElementById("form-vtnRemite"));

	$.$.ajax({
		url: 'enviarRemite.php',
		type: 'POST',
		dataType: 'json',
		data: formData,
	})
	.done(function() {
		/* Se hizo bien 
		   LIMPIAMOS LOS DATOS DEL FORMULARIO
		 */ 
        document.getElementById("form-vtnRemite").reset();
	})
	.fail(function() {
		console.log("error");
	})
	.always(function() {
		console.log("complete");
	});
}


//Funcion para dar de alta AREA QUE REMITE 
function enviarRemite(e)
{
	e.preventDefault();//evita que se recarge la pagina
	var formData = new FormData(document.getElementById("form-vtnRemite"));

	$.$.ajax({
		url: 'enviarRemite.php',
		type: 'POST',
		dataType: 'json',
		data: formData,
	})
	.done(function() {
		/* Se hizo bien 
		   LIMPIAMOS LOS DATOS DEL FORMULARIO
		 */ 
        document.getElementById("form-vtnRemite").reset();
	})
	.fail(function() {
		console.log("error");
	})
	.always(function() {
		console.log("complete");
	});
}


//Funcion para dar de alta AREA QUE BENEFICIA
function enviarBeneficia(e)
{
	e.preventDefault();//evita que se recarge la pagina
	var formData = new FormData(document.getElementById("form-vtnBeneficia"));

	$.$.ajax({
		url: 'enviarBeneficia.php',
		type: 'POST',
		dataType: 'json',
		data: formData,
	})
	.done(function() {
		/* Se hizo bien 
		   LIMPIAMOS LOS DATOS DEL FORMULARIO
		 */ 
        document.getElementById("form-vtnBeneficia").reset();
	})
	.fail(function() {
		console.log("error");
	})
	.always(function() {
		console.log("complete");
	});
}

//Funcion para dar de alta DEPARTAMENTO RESPONSABLE DE ATENCION
function enviarAtencion(e)
{
	e.preventDefault();//evita que se recarge la pagina
	var formData = new FormData(document.getElementById("form-vtnAtencion"));

	$.$.ajax({
		url: 'enviarAtencion.php',
		type: 'POST',
		dataType: 'json',
		data: formData,
	})
	.done(function() {
		/* Se hizo bien 
		   LIMPIAMOS LOS DATOS DEL FORMULARIO
		 */ 
        document.getElementById("form-vtnAtencion").reset();
	})
	.fail(function() {
		console.log("error");
	})
	.always(function() {
		console.log("complete");
	});
}

function editarTurno(e)
{
	e.preventDefault();//evita que se recarge la pagina
	var formData = new FormData(document.getElementById("form-vtnTurnoEditar"));

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
        document.getElementById("form-vtnTurnoEditar").reset();
	})
	.fail(function() {
		console.log("error");
	})
	.always(function() {
		console.log("complete");
	});
}