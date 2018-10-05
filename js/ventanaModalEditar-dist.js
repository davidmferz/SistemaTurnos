"use strict";

var Ventana = function Ventana(props) {
	return React.createElement(
		"div",
		{ className: "modal", id: props.id, tabIndex: "-1", role: "dialog" },
		React.createElement(
			"div",
			{ className: "modal-dialog", role: "document" },
			React.createElement(
				"div",
				{ className: "modal-content" },
				React.createElement(
					"div",
					{ className: "modal-header" },
					React.createElement(
						"h5",
						{ className: "modal-title" },
						props.titulo
					),
					React.createElement(
						"button",
						{ type: "button", className: "close", "data-dismiss": "modal", "aria-label": "Close" },
						React.createElement(
							"span",
							{ "aria-hidden": "true" },
							"\xD7"
						)
					)
				),
				React.createElement(
					"div",
					{ className: "modal-body" },
					React.createElement("br", null),
					React.createElement(
						"form",
						{ id: "form-" + props.id },
						React.createElement(
							"div",
							{ className: "form-group" },
							React.createElement("input", { type: "text", className: "form-control", id: "nombre",
								name: "nombre", placeholder: "Ingrese Nombre" })
						)
					)
				),
				React.createElement(
					"div",
					{ className: "modal-footer" },
					React.createElement(
						"button",
						{ type: "button", className: "btn btn-primary", onClick: props.funcion },
						"Editar"
					),
					React.createElement(
						"button",
						{ type: "button", className: "btn btn-secondary", "data-dismiss": "modal" },
						"Close"
					)
				)
			)
		)
	);
};

/* 	Aqui se crean las 4 ventanas Modales para
	-Tipo de Turno
	-Area que remite
	-Area que beneficia
	-Departamento responsable de Antencion
*/

ReactDOM.render(React.createElement(
	"div",
	null,
	React.createElement(Ventana, { id: "vtnTurnoEditar", titulo: "Tipo de Turno", funcion: "enviarTurno" }),
	React.createElement(Ventana, { id: "vtnRemiteEditar", titulo: "Area que Remite", funcion: "enviarRemite" }),
	React.createElement(Ventana, { id: "vtnBeneficiaEditar", titulo: "Area que Beneficia", funcion: "enviarBeneficia" }),
	React.createElement(Ventana, { id: "vtnAtencionEditar", titulo: "Departamento Responsable de Atenci\xF3n", funcion: "enviarAtencion" })
), document.getElementById('ventanaEditar'));