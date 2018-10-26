function altaTurno(){
	
	var nfolio = document.getElementById('folio').value;
	var ndocumento = document.getElementById('documento').value;
	var fecharecibe = document.getElementById('fechaRecibe').value;
	var fechadocumento = document.getElementById('fechaDocumento').value;
	var tipoturno = document.getElementById('select-Turno').value;
	var arearemite = document.getElementById('select-Remite').value;
	var arearbeneficia = document.getElementById('select-Beneficia').value;
	var ratencion = document.getElementById('select-Atencion').value;
	var iatencion = document.getElementById('exampleFormControlTextarea1').value;
	//var druta = document.getElementById('');
	console.log(nfolio+"\n"+
		ndocumento+"\n"+
		fecharecibe+"\n"+
		fechadocumento+"\n"+
		tipoturno+"\n"+
		arearemite+"\n"+
		arearbeneficia+"\n"+
		ratencion+"\n"+
		iatencion);

	$.ajax({
			url: 'buscaid.php',
			type: 'POST',
			dataType: 'json',
			data: {
					tipoturno1: tipoturno,
					arearemite1: arearemite,
					arearbeneficia1: arearbeneficia,
					ratencion1: ratencion},
			beforeSend: function(){
				console.log("Se está procesando la informacion ");
			},
			})
			.done(function(datos) {
				$.ajax({
							url: 'agregarTurno.php',
							type: 'POST',
							dataType: 'json',
							data: {nfolio1: nfolio,
									ndocumento1: ndocumento,
									fecharecibe1: fecharecibe,
									fechadocumento1: fechadocumento,
									tipoturno1: datos.resultadoturno,
									arearemite1: datos.resultadoremite,
									arearbeneficia1: datos.resultadobeneficiada,
									ratencion1: datos.resultadoatencion,
									iatencion1: iatencion},
							beforeSend: function(){
								console.log("Se está procesando la informacion ");
							},
							})
							.done(function(datos) {
							   //console.log("exitoX: "+datos.resultado);
							   alert("Se agregó correctamente");
							   
							})
							.fail(function(datos) {
							  console.log("aqui ya no ");
							})
							.always(function() {
							  console.log("complete");
					});
			})
			.fail(function(datos) {
			  console.log("error en "+datos.resultado);
			})
			.always(function() {
			  console.log("complete");
	});


	

}