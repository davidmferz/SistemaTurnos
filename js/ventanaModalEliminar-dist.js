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
							React.createElement(
								"select",
								{ className: "form-control grupos", id: "select-" + props.id, name: "nombre" },
								React.createElement(
									"option",
									{ value: "0" },
									"Seleccione una opcion"
								)
							),
							React.createElement("input", { type: "text", id: "idx", value: props.id, name: "idx" })
						)
					)
				),
				React.createElement(
					"div",
					{ className: "modal-footer" },
					React.createElement(
						"button",
						{ type: "button", className: "btn btn-primary", onClick: elimina },
						"Eliminar"
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
	React.createElement(Ventana, { id: "vtnTurnoEliminar", titulo: "Eliminar Tipo de Turno" }),
	React.createElement(Ventana, { id: "vtnRemiteEliminar", titulo: "Eliminar Area que Remite" }),
	React.createElement(Ventana, { id: "vtnBeneficiaEliminar", titulo: "Eliminar Area que Beneficia" }),
	React.createElement(Ventana, { id: "vtnAtencionEliminar", titulo: "Eliminar Departamento Responsable de Atenci\xF3n" })
), document.getElementById('ventanaEliminar'));