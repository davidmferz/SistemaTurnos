
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
						<select className="form-control grupos" id={"select-"+props.id} name="nombre">
						 <option value="0">Seleccione una opcion</option>

						 </select>
						 <input type="text" id="idx" value={props.id} name="idx"/>
					</div>
				</form>
		      </div>
		      <div className="modal-footer">
		        <button type="button" className="btn btn-primary" onClick={props.id}>Eliminar</button>
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
		<Ventana id="vtnTurnoEliminar"titulo="Eliminar Tipo de Turno"  funcion="eliminarTurno()"/>
		<Ventana id="vtnRemiteEliminar" titulo="Eliminar Area que Remite"  funcion="eliminarRemite()"/>
		<Ventana id="vtnBeneficiaEliminar" titulo="Eliminar Area que Beneficia"  funcion="eliminarBeneficia()"/>
		<Ventana id="vtnAtencionEliminar" titulo="Eliminar Departamento Responsable de Atención" funcion="eliminarAtencion()"/>
	</div>, 
	document.getElementById('ventanaEliminar')
);
