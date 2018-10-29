"use strict";

var FormularioTurnos = function FormularioTurnos(props) {
    return React.createElement(
        "div",
        { className: "container mt-5" },
        React.createElement(
            "div",
            { className: "card mt-2" },
            React.createElement(
                "div",
                { className: "card-header text-center" },
                "Agregar un nuevo Turno"
            ),
            React.createElement(
                "div",
                { className: "card-body" },
                React.createElement(
                    "form",
                    { id: "formTurno", encType: "multipart/form-data" },
                    React.createElement("br", null),
                    React.createElement(
                        "div",
                        { className: "row" },
                        React.createElement(
                            "div",
                            { className: "col-md-6" },
                            React.createElement(
                                "div",
                                { className: "form-group row" },
                                React.createElement(
                                    "label",
                                    { htmlFor: "folio", className: "col-sm-4 col-form-label" },
                                    "No. Folio"
                                ),
                                React.createElement(
                                    "div",
                                    { className: "col-sm-8" },
                                    React.createElement("input", { type: "text", className: "form-control", name: "folio", id: "folio", placeholder: "No. Folio" })
                                )
                            )
                        ),
                        React.createElement(
                            "div",
                            { className: "col-md-6" },
                            React.createElement(
                                "div",
                                { className: "form-group row" },
                                React.createElement(
                                    "label",
                                    { htmlFor: "documento", className: "col-sm-4 col-form-label" },
                                    "No. Documento"
                                ),
                                React.createElement(
                                    "div",
                                    { className: "col-sm-8" },
                                    React.createElement("input", { type: "text", className: "form-control", name: "documento", id: "documento", placeholder: "No. Documento" })
                                )
                            )
                        )
                    ),
                    React.createElement("br", null),
                    React.createElement(
                        "div",
                        { className: "row" },
                        React.createElement(
                            "div",
                            { className: "col-md-6" },
                            React.createElement(
                                "div",
                                { className: "form-group row" },
                                React.createElement(
                                    "label",
                                    { htmlFor: "fechaRecibe", className: "col-sm-4 col-form-label" },
                                    "Fecha que se Recibe"
                                ),
                                React.createElement(
                                    "div",
                                    { className: "col-sm-8" },
                                    React.createElement("input", { type: "date", className: "form-control", name: "fechaRecibe", id: "fechaRecibe" })
                                )
                            )
                        ),
                        React.createElement(
                            "div",
                            { className: "col-md-6" },
                            React.createElement(
                                "div",
                                { className: "form-group row" },
                                React.createElement(
                                    "label",
                                    { htmlFor: "fechaDocumento", className: "col-sm-4 col-form-label" },
                                    "Fecha del Documento"
                                ),
                                React.createElement(
                                    "div",
                                    { className: "col-sm-8" },
                                    React.createElement("input", { type: "date", className: "form-control", name: "fechaDocumento", id: "fechaDocumento" })
                                )
                            )
                        )
                    ),
                    React.createElement("br", null),
                    React.createElement("br", null),
                    React.createElement(
                        "div",
                        { className: "form-group" },
                        React.createElement(
                            "label",
                            { htmlFor: "exampleFormControlSelect1" },
                            "Tipos de Turnos"
                        ),
                        React.createElement(
                            "select",
                            { className: "form-control", id: "select-Turno" },
                            React.createElement(
                                "option",
                                { value: "0" },
                                "Seleccione una opcion"
                            )
                        )
                    ),
                    React.createElement(
                        "div",
                        { className: "form-group" },
                        React.createElement(
                            "label",
                            { htmlFor: "exampleFormControlSelect1" },
                            "Area que Remite"
                        ),
                        React.createElement(
                            "select",
                            { className: "form-control", id: "select-Remite" },
                            React.createElement(
                                "option",
                                { value: "0" },
                                "Seleccione una opcion"
                            )
                        )
                    ),
                    React.createElement(
                        "div",
                        { className: "form-group" },
                        React.createElement(
                            "label",
                            { htmlFor: "exampleFormControlSelect1" },
                            "Area que Beneficia"
                        ),
                        React.createElement(
                            "select",
                            { className: "form-control", id: "select-Beneficia" },
                            React.createElement(
                                "option",
                                { value: "0" },
                                "Seleccione una opcion"
                            )
                        )
                    ),
                    React.createElement(
                        "div",
                        { className: "form-group" },
                        React.createElement(
                            "label",
                            { htmlFor: "exampleFormControlSelect1" },
                            "Departamento responsable de atenci\xF3n"
                        ),
                        React.createElement(
                            "select",
                            { className: "form-control", id: "select-Atencion" },
                            React.createElement(
                                "option",
                                { value: "0" },
                                "Seleccione una opcion"
                            )
                        )
                    ),
                    React.createElement(
                        "div",
                        { className: "form-group" },
                        React.createElement(
                            "label",
                            { htmlFor: "exampleFormControlTextarea1" },
                            "Instrucciones de Atenci\xF3n"
                        ),
                        React.createElement("textarea", { className: "form-control", id: "exampleFormControlTextarea1", rows: "3" })
                    ),
                    React.createElement(
                        "div",
                        { className: "form-group" },
                        React.createElement(
                            "label",
                            { htmlFor: "exampleFormControlFile1" },
                            "Documento"
                        ),
                        React.createElement("input", { type: "file", className: "form-control-file", id: "archivo", name: "archivo" })
                    ),
                    React.createElement(
                        "div",
                        null,
                        React.createElement(
                            "button",
                            { type: "button", id: "submit", className: "btn btn-primary btn-lg btn-block", onClick: agregarTurno2 },
                            "Enviar"
                        )
                    )
                )
            )
        )
    );
};

ReactDOM.render(React.createElement(FormularioTurnos, null), document.getElementById('formularioTurno'));