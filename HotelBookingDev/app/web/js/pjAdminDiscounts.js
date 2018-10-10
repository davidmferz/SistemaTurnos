var jQuery_1_8_2 = jQuery_1_8_2 || $.noConflict();
(function ($, undefined) {
	$(function () {
		"use strict";
		var $dialogAddPackage = $("#dialogAddPackage"),
			$dialogAddFree = $("#dialogAddFree"),
			$dialogAddCode = $("#dialogAddCode"),
			$dialogPackageItems = $("#dialogPackageItems"),
			$dialogPackageAddItem = $("#dialogPackageAddItem"),
			dialog = ($.fn.dialog !== undefined),
			datepicker = ($.fn.datepicker !== undefined),
			validate = ($.fn.validate !== undefined),
			spinner = ($.fn.spinner !== undefined),
			datagrid = ($.fn.datagrid !== undefined);
		
		$("#content").on("click", ".package-add", function (e) {
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			if ($dialogAddPackage.length > 0 && dialog) {
				$dialogAddPackage.dialog("open");
			}
			return false;
		}).on("click", ".free-add", function (e) {
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			if ($dialogAddFree.length > 0 && dialog) {
				$dialogAddFree.dialog("open");
			}
			return false;
		}).on("click", ".code-add", function (e) {
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			if ($dialogAddCode.length > 0 && dialog) {
				$dialogAddCode.dialog("open");
			}
			return false;
		}).on("click", ".package-items", function (e) {
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			if ($dialogPackageItems.length > 0 && dialog) {
				$dialogPackageItems.data("package_id", $(this).data("id")).dialog("open");
			}
			return false;
		});
		
		function formatItems(val, obj) {
			return ['<a href="#" class="pj-button package-items" data-id="', obj.id, '">'+myLabel.more_price+'</a>'].join("");
		}
		
		if ($("#grid_packages").length > 0 && datagrid) {
			
			var $grid_packages = $("#grid_packages").datagrid({
				buttons: [{type: "delete", url: "index.php?controller=pjAdminDiscounts&action=pjActionDeletePackage&id={:id}"}],
				columns: [
				          {text: myLabel.room, type: "select", sortable: true, editable: true, options: myLabel.rooms, width: 160, editableWidth: 140},
				          {text: myLabel.date_from, type: "date", sortable: true, editable: false, width: 100, editableWidth: 80, 
								jqDateFormat: pjGrid.jqDateFormat, 
								renderer: $.datagrid._formatDate, 
								editableRenderer: $.datagrid._formatDate,
								dateFormat: pjGrid.jsDateFormat
				          },
				          {text: myLabel.date_to, type: "date", sortable: true, editable: false, width: 100, editableWidth: 80,
								jqDateFormat: pjGrid.jqDateFormat, 
								renderer: $.datagrid._formatDate, 
								editableRenderer: $.datagrid._formatDate,
								dateFormat: pjGrid.jsDateFormat		
				          },
				          {text: myLabel.start_day, type: "select", sortable: true, editable: true, width: 110, editableWidth: 100, options: [
                             {label: myLabel.mon, value: 1},
                             {label: myLabel.tue, value: 2},
                             {label: myLabel.wed, value: 3},
                             {label: myLabel.thu, value: 4},
                             {label: myLabel.fri, value: 5},
                             {label: myLabel.sat, value: 6},
                             {label: myLabel.sun, value: 0}
                           ]},
                           {text: myLabel.end_day, type: "select", sortable: true, editable: true, width: 110, editableWidth: 100, options: [
	                           {label: myLabel.mon, value: 1},
	                           {label: myLabel.tue, value: 2},
	                           {label: myLabel.wed, value: 3},
	                           {label: myLabel.thu, value: 4},
	                           {label: myLabel.fri, value: 5},
	                           {label: myLabel.sat, value: 6},
	                           {label: myLabel.sun, value: 0}
	                         ]},
				          {text: myLabel.total_price, type: "text", sortable: true, editable: true, width: 110, editableWidth: 90},
				          {text: "", type: "text", sortable: false, editable: false, renderer: formatItems}
				          ],
				dataUrl: "index.php?controller=pjAdminDiscounts&action=pjActionGetPackages",
				dataType: "json",
				fields: ['room_id', 'date_from', 'date_to', 'start_day', 'end_day', 'total_price', 'id'],
				paginator: {
					actions: [
					   {text: myLabel.delete_selected, url: "index.php?controller=pjAdminDiscounts&action=pjActionDeletePackageBulk", render: true, confirmation: myLabel.delete_confirmation}
					],
					gotoPage: true,
					paginate: true,
					total: true,
					rowCount: true
				},
				saveUrl: "index.php?controller=pjAdminDiscounts&action=pjActionSavePackage&id={:id}",
				select: {
					field: "id",
					name: "record[]"
				}
			});
		}
		
		if ($("#grid_frees").length > 0 && datagrid) {
			
			var $grid_frees = $("#grid_frees").datagrid({
				buttons: [{type: "delete", url: "index.php?controller=pjAdminDiscounts&action=pjActionDeleteFree&id={:id}"}],
				columns: [
				          {text: myLabel.room, type: "select", sortable: true, editable: true, options: myLabel.rooms, width: 160, editableWidth: 140},
				          {text: myLabel.date_from, type: "date", sortable: true, editable: false, width: 100, editableWidth: 80, 
								jqDateFormat: pjGrid.jqDateFormat, 
								renderer: $.datagrid._formatDate, 
								editableRenderer: $.datagrid._formatDate,
								dateFormat: pjGrid.jsDateFormat
				          },
				          {text: myLabel.date_to, type: "date", sortable: true, editable: false, width: 100, editableWidth: 80,
								jqDateFormat: pjGrid.jqDateFormat, 
								renderer: $.datagrid._formatDate, 
								editableRenderer: $.datagrid._formatDate,
								dateFormat: pjGrid.jsDateFormat		
				          },
				          {text: myLabel.min_length, type: "spinner", min: 0, max: 255, step: 1, sortable: true, editable: true, width: 110, editableWidth: 50},
				          {text: myLabel.max_length, type: "spinner", min: 0, max: 255, step: 1, sortable: true, editable: true, width: 110, editableWidth: 50},
				          {text: myLabel.free_nights, type: "spinner", min: 0, max: 255, step: 1, sortable: true, editable: true, width: 110, editableWidth: 50}
				          ],
				dataUrl: "index.php?controller=pjAdminDiscounts&action=pjActionGetFrees",
				dataType: "json",
				fields: ['room_id', 'date_from', 'date_to', 'min_length', 'max_length', 'free_nights'],
				paginator: {
					actions: [
					   {text: myLabel.delete_selected, url: "index.php?controller=pjAdminDiscounts&action=pjActionDeleteFreeBulk", render: true, confirmation: myLabel.delete_confirmation}
					],
					gotoPage: true,
					paginate: true,
					total: true,
					rowCount: true
				},
				saveUrl: "index.php?controller=pjAdminDiscounts&action=pjActionSaveFree&id={:id}",
				select: {
					field: "id",
					name: "record[]"
				}
			});
		}

		if ($("#grid_codes").length > 0 && datagrid) {
			
			var $grid_codes = $("#grid_codes").datagrid({
				buttons: [{type: "delete", url: "index.php?controller=pjAdminDiscounts&action=pjActionDeleteCode&id={:id}"}],
				columns: [
				          {text: myLabel.room, type: "select", sortable: true, editable: true, options: myLabel.rooms, width: 260, editableWidth: 240},
				          {text: myLabel.date_from, type: "date", sortable: true, editable: false, width: 120, editableWidth: 80, 
								jqDateFormat: pjGrid.jqDateFormat, 
								renderer: $.datagrid._formatDate, 
								editableRenderer: $.datagrid._formatDate,
								dateFormat: pjGrid.jsDateFormat
				          },
				          {text: myLabel.date_to, type: "date", sortable: true, editable: false, width: 120, editableWidth: 80,
								jqDateFormat: pjGrid.jqDateFormat, 
								renderer: $.datagrid._formatDate, 
								editableRenderer: $.datagrid._formatDate,
								dateFormat: pjGrid.jsDateFormat		
				          },
				          {text: myLabel.code, type: "text", sortable: true, editable: true, width: 120, editableWidth: 90},
				          {text: myLabel.type, type: "select", sortable: true, editable: true, width: 110, editableWidth: 100, options: [
		                     {label: myLabel.types.amount, value: "amount"},
		                     {label: myLabel.types.percent, value: "percent"}
		                   ]},
				          {text: myLabel.discount, type: "text", sortable: true, editable: true, width: 110, editableWidth: 80}
				          ],
				dataUrl: "index.php?controller=pjAdminDiscounts&action=pjActionGetCodes",
				dataType: "json",
				fields: ['room_id', 'date_from', 'date_to', 'promo_code', 'type', 'discount'],
				paginator: {
					actions: [
					   {text: myLabel.delete_selected, url: "index.php?controller=pjAdminDiscounts&action=pjActionDeleteCodeBulk", render: true, confirmation: myLabel.delete_confirmation}
					],
					gotoPage: true,
					paginate: true,
					total: true,
					rowCount: true
				},
				saveUrl: "index.php?controller=pjAdminDiscounts&action=pjActionSaveCode&id={:id}",
				select: {
					field: "id",
					name: "record[]"
				}
			});
		}
		
		if ($dialogAddPackage.length > 0 && dialog) {
			$dialogAddPackage.dialog({
				autoOpen: false,
				resizable: false,
				draggable: false,
				modal: true,
				width: 400,
				open: function () {
					$dialogAddPackage.html("");
					$.get("index.php?controller=pjAdminDiscounts&action=pjActionAddPackage").done(function (data) {
						$dialogAddPackage.html(data);
						$dialogAddPackage.dialog("option", "position", "center");
						$dialogAddPackage.find("form").validate({
							errorPlacement: function (error, element) {
								error.insertAfter(element.parent());
							},
							onkeyup: false,
							errorClass: "err",
							wrapper: "em",
							submitHandler: function (form) {
								
								$.post("index.php?controller=pjAdminDiscounts&action=pjActionAddPackage", $dialogAddPackage.find("form").serialize()).done(function (data) {
									$(".empty-page").remove();
									$("#grid_packages").parent().show();
									var content = $grid_packages.datagrid("option", "content");
									$grid_packages.datagrid("load", "index.php?controller=pjAdminDiscounts&action=pjActionGetPackages", "date_from", "ASC", content.page, content.rowCount);
								}).always(function () {
									$dialogAddPackage.dialog("close");
								});
								
								return false;
							}
						});
					});
				},
				buttons: {
					'Save': function () {
						$dialogAddPackage.find("form").submit();
					},
					'Cancel': function () {
						$(this).dialog("close");
					}
				}
			});
		}
		
		if ($dialogAddFree.length > 0 && dialog) {
			$dialogAddFree.dialog({
				autoOpen: false,
				resizable: false,
				draggable: false,
				modal: true,
				width: 400,
				open: function () {
					$dialogAddFree.html("");
					$.get("index.php?controller=pjAdminDiscounts&action=pjActionAddFree").done(function (data) {
						$dialogAddFree.html(data);
						$dialogAddFree.find("input[name='min_length'], input[name='max_length'], input[name='free_nights']").spinner({
							min: 0,
							max: 255,
							step: 1
						});
						$dialogAddFree.dialog("option", "position", "center");
						$dialogAddFree.find("form").validate({
							errorPlacement: function (error, element) {
								error.insertAfter(element.parent());
							},
							onkeyup: false,
							errorClass: "err",
							wrapper: "em",
							submitHandler: function (form) {
								
								$.post("index.php?controller=pjAdminDiscounts&action=pjActionAddFree", $dialogAddFree.find("form").serialize()).done(function (data) {
									$(".empty-page").remove();
									$("#grid_frees").parent().show();
									var content = $grid_frees.datagrid("option", "content");
									$grid_frees.datagrid("load", "index.php?controller=pjAdminDiscounts&action=pjActionGetFrees", "date_from", "ASC", content.page, content.rowCount);
								}).always(function () {
									$dialogAddFree.dialog("close");
								});
								
								return false;
							}
						});
					});
				},
				buttons: {
					'Save': function () {
						$dialogAddFree.find("form").submit();
					},
					'Cancel': function () {
						$(this).dialog("close");
					}
				}
			});
		}
		
		if ($dialogAddCode.length > 0 && dialog) {
			$dialogAddCode.dialog({
				autoOpen: false,
				resizable: false,
				draggable: false,
				modal: true,
				width: 400,
				open: function () {
					$dialogAddCode.html("");
					$.get("index.php?controller=pjAdminDiscounts&action=pjActionAddCode").done(function (data) {
						$dialogAddCode.html(data);
						$dialogAddCode.dialog("option", "position", "center");
						$dialogAddCode.find("form").validate({
							errorPlacement: function (error, element) {
								error.insertAfter(element.parent());
							},
							onkeyup: false,
							errorClass: "err",
							wrapper: "em",
							submitHandler: function (form) {
								
								$.post("index.php?controller=pjAdminDiscounts&action=pjActionAddCode", $dialogAddCode.find("form").serialize()).done(function (data) {
									$(".empty-page").remove();
									$("#grid_codes").parent().show();
									var content = $grid_codes.datagrid("option", "content");
									$grid_codes.datagrid("load", "index.php?controller=pjAdminDiscounts&action=pjActionGetCodes", "date_from", "ASC", content.page, content.rowCount);
								}).always(function () {
									$dialogAddCode.dialog("close");
								});
								
								return false;
							}
						});
					});
				},
				buttons: {
					'Save': function () {
						$dialogAddCode.find("form").submit();
					},
					'Cancel': function () {
						$(this).dialog("close");
					}
				}
			});
		}
		
		if ($dialogPackageItems.length > 0 && dialog) {
			var $grid_items;
			$dialogPackageItems.dialog({
				autoOpen: false,
				resizable: false,
				draggable: false,
				modal: true,
				width: 400,
				open: function () {
					$dialogPackageItems.html("");
					$.get("index.php?controller=pjAdminDiscounts&action=pjActionPackageItems").done(function (data) {
						$dialogPackageItems.html(data);
						
						$grid_items = $("#grid_items").datagrid({
							buttons: [{type: "delete", url: "index.php?controller=pjAdminDiscounts&action=pjActionDeletePackageItem&id={:id}"}],
							columns: [
							          {text: myLabel.adults, type: "spinner", sortable: true, editable: true, width: 100, editableWidth: 60},
							          {text: myLabel.children, type: "spinner", sortable: true, editable: true, width: 100, editableWidth: 60},
							          {text: myLabel.price, type: "text", sortable: true, editable: true, width: 110, editableWidth: 80}
							          ],
							dataUrl: "index.php?controller=pjAdminDiscounts&action=pjActionGetPackageItems&package_id=" + $dialogPackageItems.data("package_id"),
							dataType: "json",
							fields: ['adults', 'children', 'price'],
							paginator: false,
							saveUrl: "index.php?controller=pjAdminDiscounts&action=pjActionSavePackageItem&id={:id}",
							select: {
								field: "id",
								name: "record[]"
							},
							onRender: function () {
								$dialogPackageItems.dialog("option", "position", "center");
							}
						});
					});
				},
				close: function () {
					$grid_items.datagrid("destroy");
				},
				buttons: {
					'+ Add': function () {
						$dialogPackageAddItem.data("package_id", $dialogPackageItems.data("package_id")).dialog("open");
					},
					'Close': function () {
						$(this).dialog("close");
					}
				}
			});
		}
		
		if ($dialogPackageAddItem.length > 0 && dialog) {
			$dialogPackageAddItem.dialog({
				autoOpen: false,
				resizable: false,
				draggable: false,
				modal: true,
				width: 400,
				open: function () {
					$dialogPackageAddItem.html("");
					$.get("index.php?controller=pjAdminDiscounts&action=pjActionPackageAddItem", {
						package_id: $dialogPackageAddItem.data("package_id")
					}).done(function (data) {
						$dialogPackageAddItem.html(data);
						$dialogPackageAddItem.find("input[name='adults']").spinner({
							min: 1,
							step: 1
						});
						$dialogPackageAddItem.find("input[name='children']").spinner({
							min: 0,
							step: 1
						});
						$dialogPackageAddItem.find("form").validate({
							errorPlacement: function (error, element) {
								error.insertAfter(element.parent());
							},
							onkeyup: false,
							errorClass: "err",
							wrapper: "em",
							submitHandler: function (form) {
								$.post("index.php?controller=pjAdminDiscounts&action=pjActionPackageAddItem", $(form).serialize()).done(function (data) {
									$dialogPackageAddItem.dialog("close");
									if (data.status == "OK") {
										var content = $grid_items.datagrid("option", "content");
										$grid_items.datagrid("load", "index.php?controller=pjAdminDiscounts&action=pjActionGetPackageItems&package_id=" + $dialogPackageAddItem.data("package_id"), "adults", "ASC", content.page, content.rowCount);
									}
								});
								return false;
							}
						});
						$dialogPackageAddItem.dialog("option", "position", "center");
					});
				},
				buttons: {
					'Save': function () {
						$dialogPackageAddItem.find("form").trigger("submit");
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
			var $dp = $(this).parent().siblings("input[type='text']");
			if ($dp.hasClass("hasDatepicker")) {
				$dp.datepicker("show");
			} else {
				$dp.trigger("focusin").datepicker("show");
			}
		});
	});
})(jQuery_1_8_2);