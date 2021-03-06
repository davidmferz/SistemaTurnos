
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
								<select className="form-control grupos" id={"select-"+props.id} onChange={cargar} name="nombre">
									<option value="0">Seleccione una opcion</option>
								</select>
							</div>
							<div className="form-group">
								<input type="text" className="form-control" name="nombre2" id={"text-"+props.id} placeholder="Nombre"/>
							</div>
							{props.id == "vtnAtencionEditar"  &&
								<div className="form-group">
									<input type="checkbox" id={"check-"+props.id} defaultChecked={false} onChange={cambio}/>
									<label> ¿Desea cambiar la contraseña?</label>
								</div>}
							{props.id == "vtnAtencionEditar"  &&
								<div className="form-group" id="camcon">
								</div>}
				      		{props.id == "vtnAtencionEditar"  &&
								<div className="form-group" id="camcon1">
								</div>}
						</form>
				      	<div className="modal-footer">
					        <button type="button" id="editar" className="btn btn-primary" onClick={actualiza}>Editar</button>
					        <button type="button" className="btn btn-secondary" data-dismiss="modal" onClick={limpia}>Close</button>
				     	</div>
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
		<Ventana id="vtnTurnoEditar" titulo="Tipo de Turno"/>
		<Ventana id="vtnRemiteEditar" titulo="Area que Remite"/>
		<Ventana id="vtnBeneficiaEditar" titulo="Area que Beneficia"/>
		<Ventana id="vtnAtencionEditar" titulo="Departamento Responsable de Atención"/>
	</div>, 
	document.getElementById('ventanaEditar')
);
