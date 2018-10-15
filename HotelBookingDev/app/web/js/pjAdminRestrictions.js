var jQuery_1_8_2 = jQuery_1_8_2 || $.noConflict();
(function ($, undefined) {
	$(function () {
		"use strict";
		var $dialogAddRestriction = $("#dialogAddRestriction"),
			$dialogUpdateRestriction = $("#dialogUpdateRestriction"),
			$dialogErrorRestriction = $("#dialogErrorRestriction"),
			dialog = ($.fn.dialog !== undefined),
			datepicker = ($.fn.datepicker !== undefined),
			multiselect = ($.fn.multiselect !== undefined),
			validate = ($.fn.validate !== undefined),
			datagrid = ($.fn.datagrid !== undefined);
		
		function formatRooms(str, obj) {
			if (obj.rooms.length) {
				return obj.rooms.join("<br>");
			}
			return "";
		}
		
		if ($("#grid").length > 0 && datagrid) {
			
			var $grid = $("#grid").datagrid({
				buttons: [{type: "edit", url: "index.php?controller=pjAdminRestrictions&action=pjActionUpdateRestriction&id={:id}"},
				          {type: "delete", url: "index.php?controller=pjAdminRestrictions&action=pjActionDeleteRestriction&id={:id}"}],
				columns: [{text: myLabel.date_from, type: "date", sortable: true, editable: false, width: 110, editableWidth: 100, 
								jqDateFormat: pjGrid.jqDateFormat, 
								renderer: $.datagrid._formatDate, 
								editableRenderer: $.datagrid._formatDate,
								dateFormat: pjGrid.jsDateFormat
				          },
				          {text: myLabel.date_to, type: "date", sortable: true, editable: false, width: 110, editableWidth: 100,
								jqDateFormat: pjGrid.jqDateFormat, 
								renderer: $.datagrid._formatDate, 
								editableRenderer: $.datagrid._formatDate,
								dateFormat: pjGrid.jsDateFormat		
				          },
				          {text: myLabel.room, type: "text", sortable: false, editable: false, width: 400, renderer: formatRooms},
				          {text: myLabel.type, type: "select", sortable: true, editable: true, width: 200, editableWidth: 180, options: myLabel.types}
				          ],
				dataUrl: "index.php?controller=pjAdminRestrictions&action=pjActionGetRestrictions",
				dataType: "json",
				fields: ['date_from', 'date_to', 'id', 'restriction_type'],
				paginator: {
					actions: [
					   {text: myLabel.delete_selected, url: "index.php?controller=pjAdminRestrictions&action=pjActionDeleteRestrictionBulk", render: true, confirmation: myLabel.delete_confirmation}
					],
					gotoPage: true,
					paginate: true,
					total: true,
					rowCount: true
				},
				saveUrl: "index.php?controller=pjAdminRestrictions&action=pjActionSaveRestriction&id={:id}",
				select: {
					field: "id",
					name: "record[]"
				}
			});
		}
		if($dialogErrorRestriction.length > 0 && dialog)
		{
			var btnObj = {
					'OK': function () {
						$dialogErrorRestriction.dialog("close");
					}
				};
			$dialogErrorRestriction.dialog({
				autoOpen: false,
				resizable: false,
				draggable: false,
				modal: true,
				width: 500,
				buttons: btnObj
			});
		}
		if ($dialogAddRestriction.length > 0 && dialog) {
			var btnObj = {
					'Save': function () {
						$dialogAddRestriction.find("form").trigger("submit");
					},
					'Cancel': function () {
						$dialogAddRestriction.dialog("close");
					}
				};
			if(myLabel.room_count == 0)
			{
				btnObj = {
						'Cancel': function () {
							$dialogAddRestriction.dialog("close");
						}
					};
			}
			$dialogAddRestriction.dialog({
				autoOpen: false,
				resizable: false,
				draggable: false,
				modal: true,
				width: 500,
				open: function () {
					$dialogAddRestriction.html("");
					$.get("index.php?controller=pjAdminRestrictions&action=pjActionAddRestriction").done(function (data) {
						$dialogAddRestriction.html(data);
						if (multiselect) {
							$dialogAddRestriction.find("#ar_room_number_id").multiselect({
								close: function(){
									$dialogAddRestriction.find("#ar_room_number_id").valid();
								},
								noneSelectedText: $dialogAddRestriction.find("#ar_room_number_id").attr('data-choose'),
								checkAllText: $dialogAddRestriction.find("#ar_room_number_id").attr('data-checkall'),
								uncheckAllText: $dialogAddRestriction.find("#ar_room_number_id").attr('data-uncheckall'),
							});
						}
						
						$dialogAddRestriction.find("form").validate({
							errorPlacement: function (error, element) {
								error.insertAfter(element.parent());
							},
							ignore: '',
							onkeyup: false,
							errorClass: "err",
							wrapper: "em",
							submitHandler: function (form) {
								$.post("index.php?controller=pjAdminRestrictions&action=pjActionAddRestriction", $dialogAddRestriction.find("form").serialize()).done(function (data) {
									if(data.code == 200)
									{
										$(".empty-page").remove();
										$("#grid").parent().show();
										var content = $grid.datagrid("option", "content");
										$grid.datagrid("load", "index.php?controller=pjAdminRestrictions&action=pjActionGetRestrictions", "date_from", "ASC", content.page, content.rowCount);
									}else{
										$dialogErrorRestriction.html(data.text);
										$dialogErrorRestriction.dialog('option', 'title', data.title);
										$dialogErrorRestriction.dialog('open');
									}
								}).always(function () {
									$dialogAddRestriction.dialog("close");
								});
								return false;
							}
						});
						
						$dialogAddRestriction.dialog("option", "position", "center");
					});
				},
				buttons: btnObj
			});
		}
		
		if ($dialogUpdateRestriction.length > 0 && dialog) {
			$dialogUpdateRestriction.dialog({
				autoOpen: false,
				resizable: false,
				draggable: false,
				modal: true,
				width: 500,
				open: function () {
					$dialogUpdateRestriction.html("");
					$.get("index.php?controller=pjAdminRestrictions&action=pjActionUpdateRestriction", {
						"id": $dialogUpdateRestriction.data("id")
					}).done(function (data) {
						$dialogUpdateRestriction.html(data);
						if (multiselect) {
							$dialogUpdateRestriction.find("#ar_room_number_id").multiselect();
						}
						
						$dialogUpdateRestriction.find("form").validate({
							errorPlacement: function (error, element) {
								error.insertAfter(element.parent());
							},
							ignore: '',
							onkeyup: false,
							errorClass: "err",
							wrapper: "em",
							submitHandler: function (form) {
								$.post("index.php?controller=pjAdminRestrictions&action=pjActionUpdateRestriction", $dialogUpdateRestriction.find("form").serialize()).done(function (data) {
									if(data.code == 200)
									{
										$(".empty-page").remove();
										$("#grid").parent().show();
										var content = $grid.datagrid("option", "content");
										$grid.datagrid("load", "index.php?controller=pjAdminRestrictions&action=pjActionGetRestrictions", "date_from", "ASC", content.page, content.rowCount);
									}else{
										$dialogErrorRestriction.html(data.text);
										$dialogErrorRestriction.dialog('option', 'title', data.title);
										$dialogErrorRestriction.dialog('open');
									}
								}).always(function () {
									$dialogUpdateRestriction.dialog("close");
								});
								return false;
							}
						});
						
						$dialogUpdateRestriction.dialog("option", "position", "center");
					});
				},
				buttons: {
					'Save': function () {
						$dialogUpdateRestriction.find("form").trigger("submit");
					},
					'Cancel': function () {
						$(this).dialog("close");
					}
				}
			});
		}
		
		$(document).on("focusin", ".datepick", function (e) {
			if (datepicker) {
				var minDate, maxDate,
					$this = $(this),
					custom = {},
					o = {
						firstDay: $this.attr("rel"),
						dateFormat: $this.attr("rev"),
						dayNames: ($this.data("day")).split(","),
					    monthNames: ($this.data("months")).split(","),
					    monthNamesShort: ($this.data("shortmonths")).split(","),
					    dayNamesMin: ($this.data("daymin")).split(","),
						onClose: function (dateTimeText){
							$this.valid();
						}
				};
				switch ($this.attr("name")) {
				case "date_from":
					if($(".datepick[name='date_to']").val() != '')
					{
						maxDate = $(".datepick[name='date_to']").datepicker({
							firstDay: $this.attr("rel"),
							dateFormat: $this.attr("rev")
						}).datepicker("getDate");
						$(".datepick[name='date_to']").datepicker("destroy").removeAttr("id");
						if (maxDate !== null) {
							custom.maxDate = maxDate;
						}
					}
					break;
				case "date_to":
					if($(".datepick[name='date_from']").val() != '')
					{
						minDate = $(".datepick[name='date_from']").datepicker({
							firstDay: $this.attr("rel"),
							dateFormat: $this.attr("rev")
						}).datepicker("getDate");
						$(".datepick[name='date_from']").datepicker("destroy").removeAttr("id");
						if (minDate !== null) {
							custom.minDate = minDate;
						}
					}
					break;
				}
				$(this).datepicker($.extend(o, custom));
				
			}
		}).on("click", ".pj-form-field-icon-date", function (e) {
			if (datepicker) {
				var $dp = $(this).parent().siblings("input[type='text']");
				if ($dp.hasClass("hasDatepicker")) {
					$dp.datepicker("show");
				} else {
					$dp.trigger("focusin").datepicker("show");
				}
			}
		}).on("click", ".restriction-add", function (e) {
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			if ($dialogAddRestriction.length > 0 && dialog) {
				$dialogAddRestriction.dialog("open");
			}
			return false;
		}).on("click", ".pj-table-icon-edit", function (e) {
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			var obj = $(this).data('id');
			if ($dialogUpdateRestriction.length > 0 && dialog) {
				$dialogUpdateRestriction.data('id', obj.id).dialog("open");
			}
			return false;
		});
	});
})(jQuery_1_8_2);