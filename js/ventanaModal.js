
const Ventana = props =>{
	return(
		<div className="modal" id={props.id} tabIndex="-1" role="dialog">
		  <div className="modal-dialog" role="document">
		    <div className="modal-content">
		      <div className="modal-header">
		        <h5 className="modal-title">{props.titulo}</h5>
		        <button type="button" className="close" data-dismiss="modal" aria-label="Close">
		          <span aria-hidden="true">&times;</span>
		        </button>
		      </div>
		      <div className="modal-body">
		      	<br/>
		        <form id={"form-"+props.id}>
					<div className="form-group">
						<input type="text" className="form-control" id="nombre" placeholder="Ingrese Nombre"/>
					</div>
				</form>
		      </div>
		      <div className="modal-footer">
		        <button type="button" className="btn btn-primary" onClick={props.funcion}>Guardar</button>
		        <button type="button" className="btn btn-secondary" data-dismiss="modal">Close</button>
		      </div>
		    </div>
		  </div>
		</div>
	)
};

/* 	Aqui se crean las 4 ventanas Modales para
	-Tipo de Turno
	-Area que remite
	-Area que beneficia
	-Departamento responsable de Antencion
*/

ReactDOM.render(
	<div>
		<Ventana id="vtnTurno" titulo="Tipo de Turno" titulo2="como estas" funcion="Enviar" />
		<Ventana id="vtnRemite" titulo="Area que Remite" titulo2="como estas" funcion="Enviar" />
		<Ventana id="vtnBeneficia" titulo="Area que Beneficia" titulo2="como estas" funcion="Enviar" />
		<Ventana id="vtnAtencion" titulo="Departamento Responsable de Atención" titulo2="como estas" funcion="Enviar" />
	</div>, 
	document.getElementById('ventana')
);
