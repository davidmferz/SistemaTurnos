
const Ventana = props =>{
	return(
		<div className="modal" id={props.id} tabIndex="-1" role="dialog">
		  <div className="modal-dialog" role="document">
		    <div className="modal-content">
		      <div className="modal-header">
		        <h5 className="modal-title">{props.titulo}</h5>
		        <button type="button" className="close" data-dismiss="modal" aria-label="Close" onClick={limpia}>
		          <span aria-hidden="true">&times;</span>
		        </button>
		      </div>
		      <div className="modal-body">
		      	<br/>
		        <form id={"form-"+props.id}>
					<div className="form-group">
						<input type="text" className="form-control" id={"text-"+props.id} name="nombre" placeholder="Ingrese Nombre"/>
					</div>
					{props.id == "vtnAtencion"  &&
					<div className="form-group">
						<input type="password" className="form-control" id={"pass-"+props.id} name="contraseña" placeholder="Ingrese Contraseña"/>
					</div>}
					{props.id == "vtnAtencion"  &&
					<div className="form-group">
						<input type="password" className="form-control" id={"conpass-"+props.id} name="concontraseña" placeholder="Confirme Contraseña"/>
					</div>}
				</form>
		      </div>
		      <div className="modal-footer">
		        <button type="button" className="btn btn-primary" onClick={agregar}>Guardar</button>
		        <button type="button" className="btn btn-secondary" data-dismiss="modal" onClick={limpia}>Close</button>
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
		<Ventana id="vtnTurno" titulo="Tipo de Turno"/>
		<Ventana id="vtnRemite" titulo="Area que Remite"/>
		<Ventana id="vtnBeneficia" titulo="Area que Beneficia"/>
		<Ventana id="vtnAtencion" titulo="Departamento Responsable de Atención"/>
	</div>, 
	document.getElementById('ventana')
);
