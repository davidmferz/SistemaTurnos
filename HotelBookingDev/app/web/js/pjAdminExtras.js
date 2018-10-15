var jQuery_1_8_2 = jQuery_1_8_2 || $.noConflict();
(function ($, undefined) {
	$(function () {
		"use strict";
		var datagrid = ($.fn.datagrid !== undefined),
			validate = ($.fn.validate !== undefined),
			dialog = ($.fn.dialog !== undefined),
			$frmCreateExtra = $("#frmCreateExtra"),
			$frmUpdateExtra = $("#frmUpdateExtra");

		if ($frmCreateExtra.length > 0 && validate) {
			$frmCreateExtra.validate({
				errorPlacement: function (error, element) {
					error.insertAfter(element.parent());
				},
				onkeyup: false,
				errorClass: "err",
				wrapper: "em"
			});
		}
		if ($frmUpdateExtra.length > 0 && validate) {
			$frmUpdateExtra.validate({
				errorPlacement: function (error, element) {
					error.insertAfter(element.parent());
				},
				onkeyup: false,
				errorClass: "err",
				wrapper: "em"
			});
		}
		if ($("#grid").length > 0 && datagrid) {
			var $grid = $("#grid").datagrid({
				buttons: [{type: "edit", url: "index.php?controller=pjAdminExtras&action=pjActionUpdate&id={:id}"},
				          {type: "delete", url: "index.php?controller=pjAdminExtras&action=pjActionDeleteExtra&id={:id}"}
				          ],
				columns: [{text: myLabel.extra, type: "text", sortable: true, editable: true, width: 300},
				          {text: myLabel.per, type: "select", sortable: true, editable: true, width: 130, editableWidth: 130, options: [{
				        	  label: myLabel.per_booking, value: "booking"
				          }, {
				        	  label: myLabel.per_day, value: "day"
				          }, {
				        	  label: myLabel.per_person, value: "person"
				          }, {
				        	  label: myLabel.per_day_person, value: "day_person"
				          }]},
				          {text: myLabel.price, type: "text", sortable: true, editable: true, width: 80, editableWidth: 70},
				          {text: myLabel.status, type: "select", sortable: true, editable: true, options: [{
				        	  label: myLabel.active, value: "T"
				          }, {
				        	  label: myLabel.inactive, value: "F"
				          }], applyClass: "pj-status"}],
				dataUrl: "index.php?controller=pjAdminExtras&action=pjActionGetExtra",
				dataType: "json",
				fields: ['name', 'per', 'price', 'status'],
				paginator: {
					actions: [
					   {text: myLabel.delete_selected, url: "index.php?controller=pjAdminExtras&action=pjActionDeleteExtraBulk", render: true, confirmation: myLabel.delete_confirmation}
					],
					gotoPage: true,
					paginate: true,
					total: true,
					rowCount: true
				},
				saveUrl: "index.php?controller=pjAdminExtras&action=pjActionSaveExtra&id={:id}",
				select: {
					field: "id",
					name: "record[]"
				}
			});
		}
	});
})(jQuery_1_8_2);