const FormularioTurnos = props =>{
    return(
        <div className="container mt-5">
        <div className="card mt-2">
          <div className="card-header text-center">
            Agregar un nuevo Turno
          </div>
          
          <div className="card-body">
                <form id="formTurno" enctype="multipart/form-data">
                    <br/>
                   
                    <div className="row">
                        <div className="col-md-6">
                            <div className="form-group row">
                              <label htmlFor="folio" className="col-sm-4 col-form-label">No. Folio</label>
                              <div className="col-sm-8">
                                <input type="text" className="form-control" name="folio" id="folio" placeholder="No. Folio"/>
                              </div>
                            </div>
                        </div>
                        
                        <div className="col-md-6">
                            <div className="form-group row">
                              <label htmlFor="documento" className="col-sm-4 col-form-label">No. Documento</label>
                              <div className="col-sm-8">
                                <input type="text" className="form-control" name="documento" id="documento" placeholder="No. Documento"/>
                              </div>
                            </div>
                        </div>
                    </div>
                    
                    <br/>
                    
                    <div className="row">
                        <div className="col-md-6">
                            <div className="form-group row">
                              <label htmlFor="fechaRecibe" className="col-sm-4 col-form-label">Fecha que se Recibe</label>
                              <div className="col-sm-8">
                                <input type="date" className="form-control" name="fechaRecibe" id="fechaRecibe"/>
                              </div>
                            </div>
                        </div>
                        <div className="col-md-6">
                            <div className="form-group row">
                              <label htmlFor="fechaDocumento" className="col-sm-4 col-form-label">Fecha del Documento</label>
                              <div className="col-sm-8">
                                <input type="date" className="form-control" name="fechaDocumento" id="fechaDocumento"/>
                              </div>
                            </div>
                        </div>
                    </div>
                    <br/>
                    <br/>

                    
                    <div className="form-group">
                        <label htmlFor="exampleFormControlSelect1">Tipos de Turnos</label>
                        <select className="form-control" id="select-Turno">
                            <option value="0">Seleccione una opcion</option>
                        </select>
                    </div>
                    
                   
                    <div className="form-group">
                        <label htmlFor="exampleFormControlSelect1">Area que Remite</label>
                        <select className="form-control" id="select-Remite">
                          <option value="0">Seleccione una opcion</option>
                        </select>
                    </div>
                                 
                    
                    <div className="form-group">
                        <label htmlFor="exampleFormControlSelect1">Area que Beneficia</label>
                        <select className="form-control" id="select-Beneficia">
                          <option value="0">Seleccione una opcion</option>
                        </select>
                    </div>
                                
                    
                    <div className="form-group">
                        <label htmlFor="exampleFormControlSelect1">Departamento responsable de atención</label>
                        <select className="form-control" id="select-Atencion">
                          <option value="0">Seleccione una opcion</option>
                        </select>
                    </div>
                                
                    
                    <div className="form-group">
                        <label htmlFor="exampleFormControlTextarea1">Instrucciones de Atención</label>
                        <textarea className="form-control" id="instrucciones" rows="3"></textarea>
                    </div>
                                 
                    
                    <div className="form-group">
                        <label htmlFor="exampleFormControlFile1">Documento</label>
                        <input type="file" className="form-control-file" id="archivo" name="archivo"/>
                    </div>

                    <div>
                        <button type="button" id="submit" className="btn btn-primary btn-lg btn-block" onClick={agregarTurno2}>Enviar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
        
    )
};

ReactDOM.render(<FormularioTurnos/>, document.getElementById('formularioTurno'));


