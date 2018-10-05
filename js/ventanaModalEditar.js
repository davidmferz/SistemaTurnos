
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
						<select className="form-control grupos" id="turno" name="turno">
						 <option value="0">Seleccione una opcion</option>
						 </select>
					</div>
				</form>
		      </div>
		      <div className="modal-footer">
		        <button type="button" className="btn btn-primary" onClick={props.funcion}>Editar</button>
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
		<Ventana id="vtnTurnoEditar" titulo="Tipo de Turno"  funcion="editarTurno()"/>
		<Ventana id="vtnRemiteEditar" titulo="Area que Remite"  funcion="enviarRemite()"/>
		<Ventana id="vtnBeneficiaEditar" titulo="Area que Beneficia"  funcion="enviarBeneficia()"/>
		<Ventana id="vtnAtencionEditar" titulo="Departamento Responsable de Atención" funcion="enviarAtencion()"/>
	</div>, 
	document.getElementById('ventanaEditar')
);
