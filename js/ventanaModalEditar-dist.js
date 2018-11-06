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
						{ type: "button", className: "close", "data-dismiss": "modal", "aria-label": "Close", onClick: limpia },
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
								{ className: "form-control grupos", id: "select-" + props.id, onChange: cargar, name: "nombre" },
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
							React.createElement("input", { type: "text", className: "form-control", name: "nombre2", id: "text-" + props.id, placeholder: "Nombre" })
						),
						props.id == "vtnAtencionEditar" && React.createElement(
							"div",
							{ className: "form-group" },
							React.createElement("input", { type: "checkbox", id: "check-" + props.id, defaultChecked: false, onChange: cambio }),
							React.createElement(
								"label",
								null,
								" \xBFDesea cambiar la contrase\xF1a?"
							)
						),
						props.id == "vtnAtencionEditar" && React.createElement("div", { className: "form-group", id: "camcon" }),
						props.id == "vtnAtencionEditar" && React.createElement("div", { className: "form-group", id: "camcon1" })
					),
					React.createElement(
						"div",
						{ className: "modal-footer" },
						React.createElement(
							"button",
							{ type: "button", id: "editar", className: "btn btn-primary", onClick: actualiza },
							"Editar"
						),
						React.createElement(
							"button",
							{ type: "button", className: "btn btn-secondary", "data-dismiss": "modal", onClick: limpia },
							"Close"
						)
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
	React.createElement(Ventana, { id: "vtnTurnoEditar", titulo: "Tipo de Turno" }),
	React.createElement(Ventana, { id: "vtnRemiteEditar", titulo: "Area que Remite" }),
	React.createElement(Ventana, { id: "vtnBeneficiaEditar", titulo: "Area que Beneficia" }),
	React.createElement(Ventana, { id: "vtnAtencionEditar", titulo: "Departamento Responsable de Atenci\xF3n" })
), document.getElementById('ventanaEditar'));