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
							React.createElement("input", { type: "text", className: "form-control", id: "text-" + props.id, name: "nombre", placeholder: "Ingrese Nombre" })
						),
						props.id == "vtnAtencion" && React.createElement(
							"div",
							{ className: "form-group" },
							React.createElement("input", { type: "password", className: "form-control", id: "pass-" + props.id, name: "contrase\xF1a", placeholder: "Ingrese Contrase\xF1a" })
						),
						props.id == "vtnAtencion" && React.createElement(
							"div",
							{ className: "form-group" },
							React.createElement("input", { type: "password", className: "form-control", id: "conpass-" + props.id, name: "concontrase\xF1a", placeholder: "Confirme Contrase\xF1a" })
						)
					)
				),
				React.createElement(
					"div",
					{ className: "modal-footer" },
					React.createElement(
						"button",
						{ type: "button", className: "btn btn-primary", onClick: agregar },
						"Guardar"
					),
					React.createElement(
						"button",
						{ type: "button", className: "btn btn-secondary", "data-dismiss": "modal", onClick: limpia },
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
	React.createElement(Ventana, { id: "vtnTurno", titulo: "Tipo de Recurso" }),
	React.createElement(Ventana, { id: "vtnRemite", titulo: "Area que Remite" }),
	React.createElement(Ventana, { id: "vtnBeneficia", titulo: "Area que Beneficia" }),
	React.createElement(Ventana, { id: "vtnAtencion", titulo: "Departamento Responsable de Atenci\xF3n" })
), document.getElementById('ventana'));