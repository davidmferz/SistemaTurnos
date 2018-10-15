var jQuery_1_8_2 = jQuery_1_8_2 || $.noConflict();
(function ($, undefined) {
	$(function () {
		"use strict";
		var validator,
			booking_id = "",
			hash = "",
			$frmCreateBooking = $("#frmCreateBooking"),
			$frmUpdateBooking = $("#frmUpdateBooking"),
			$dialogRoomEdit = $("#dialogRoomEdit"),
			$dialogRoomDelete = $("#dialogRoomDelete"),
			$dialogRoomAdd = $("#dialogRoomAdd"),
			$dialogConfirmation = $("#dialogConfirmation"),
			$mceEditor = $(".mceEditor"),
			autocomplete = ($.fn.autocomplete !== undefined),
			tabs = ($.fn.tabs !== undefined),
			dialog = ($.fn.dialog !== undefined),
			spinner = ($.fn.spinner !== undefined),
			validate = ($.fn.validate !== undefined),
			datepicker = ($.fn.datepicker !== undefined),
			datagrid = ($.fn.datagrid !== undefined),
			tipsy = ($.fn.tipsy !== undefined),
			$tabs = $("#tabs"),
			tOpt = {
				select: function (event, ui) {
					$(":input[name='tab_id']").val(ui.panel.id);
				}
			},
			tipsyOpts = {
				offset: 1,
				opacity: 1,
				html: true,
				gravity: "s"
			};
		
		if (tabs) {
			$tabs.tabs(tOpt);
		}
		
		if ($frmCreateBooking.length > 0 && validate) {
			$frmCreateBooking.validate({
				errorPlacement: function (error, element) {
					if (element.attr("name") === "date_from") {
						error.insertAfter(element.parent().parent());
					} else {
						error.insertAfter(element.parent());
					}
				},
				onkeyup: false,
				errorClass: "err",
				wrapper: "em",
				ignore: "",
				invalidHandler: function (event, validator) {
				    if (validator.numberOfInvalids()) {
				    	var index = $(validator.errorList[0].element, this).closest("div[id^='tabs-']").index();
				    	if ($tabs.length > 0 && tabs && index !== -1) {
				    		$tabs.tabs(tOpt).tabs("option", "active", index-1);
				    	}
				    };
				}
			});
			hash = $frmCreateBooking.find("input[name='hash']").val();
			getBookingRooms.call(null, booking_id, hash);
			if (tipsy) {
				$frmCreateBooking.find("i.t").tipsy($.extend({}, tipsyOpts, {className: 'booking-status-tooltip'}));
			}
			if ($("#existing_search").length > 0 && autocomplete) {
				
				var cache = {}, lastXhr;
				$( "#existing_search" ).autocomplete({
					
					minLength: 2,
					source: function(request, response) {
						lastXhr = $.getJSON("index.php?controller=pjAdminBookings&action=pjActionGetClients", request, function(data, status, xhr) {
							if (xhr === lastXhr) {
								response(data);
							}
						});
					},
					select: function(event, ui) {
						$("#existing_search").val("");
						$.get("index.php?controller=pjAdminBookings&action=pjActionFillClient", {
							"booking_id": ui.item.value,
						}).done(function (data) {
							$frmCreateBooking.find("select[name='c_title']").val(data.c_title);
							$frmCreateBooking.find("input[name='c_fname']").val(data.c_fname);							
							$frmCreateBooking.find("input[name='c_lname']").val(data.c_lname);
							$frmCreateBooking.find("input[name='c_phone']").val(data.c_phone);
							$frmCreateBooking.find("input[name='c_email']").val(data.c_email);
							$frmCreateBooking.find("input[name='c_company']").val(data.c_company);
							$frmCreateBooking.find("input[name='c_address_1']").val(data.c_address_1);
							$frmCreateBooking.find("input[name='c_city']").val(data.c_city);
							$frmCreateBooking.find("input[name='c_state']").val(data.c_state);
							$frmCreateBooking.find("input[name='c_zip']").val(data.c_zip);
							$frmCreateBooking.find("select[name='c_country']").val(data.c_country);
							$frmCreateBooking.find("textarea[name='c_notes']").val(data.c_notes);
						});
						event.preventDefault();
					}
				});
			}
		}
		if ($frmUpdateBooking.length > 0 && validate) {
			$frmUpdateBooking.validate({
				errorPlacement: function (error, element) {
					if (element.attr("name") === "date_from") {
						error.insertAfter(element.parent().parent());
					} else {
						error.insertAfter(element.parent());
					}
				},
				onkeyup: false,
				errorClass: "err",
				wrapper: "em",
				ignore: "",
				invalidHandler: function (event, validator) {
				    if (validator.numberOfInvalids()) {
				    	var index = $(validator.errorList[0].element, this).closest("div[id^='tabs-']").index();
				    	if ($tabs.length > 0 && tabs && index !== -1) {
				    		$tabs.tabs(tOpt).tabs("option", "active", index-1);
				    	}
				    };
				}
			});
			if (tipsy) {
				$frmUpdateBooking.find("i.t").tipsy($.extend({}, tipsyOpts, {className: 'booking-status-tooltip'}));
			}
			booking_id = $frmUpdateBooking.find("input[name='id']").val();
			getBookingRooms.call(null, booking_id, hash);
			runPriceAgent.call(null, $frmUpdateBooking);
			
			$frmUpdateBooking.on("keyup", "#total", function () {
				handleBalance.call(null, myLabel.invoice_total, parseFloat($(this).val()));
			}).on("change", ':input[name^="extra_id"], :input[name="voucher"]', function () {
				runPriceAgent.call(null, $frmUpdateBooking);
			});
		}
		
		function runPriceAgent($frm) {
			$.post("index.php?controller=pjAdminBookings&action=pjActionGetPriceAgent", $frm.serialize()).done(function (data) {
				if (data.status === "OK") {
					var x, $parent;
					for (x in data.data) {
						if (data.data.hasOwnProperty(x)) {
							$parent = $frm.find(':input[name="' + x + '"]').parent();
							$parent.siblings('i.t').remove();
							switch (data.data[x].status) {
								case "ERR":
									$parent.after(['<i class="t t-warn" title="', data.data[x].tooltip, '"></i>'].join(""));
									break;
							} 
						}
					}
					
					$frm.find("i.t-warn").tipsy(tipsyOpts);
				}
			});
		}
		
		function getAvailability() {
			$.get("index.php?controller=pjAdminBookings&action=pjActionGetAvailability", {
				year: arguments[0],
				month: arguments[1],
				day: arguments[2],
				direction: arguments[3]
			}).done(function (data) {
				$("#boxAvailability").html(data);
			});
		}
		
		function handleBalance(invoice_total, amount) {
			if (invoice_total < amount) {
				$(".btnBalancePayment").show();
				$(".btnAddInvoice").hide();
			} else {
				$(".btnBalancePayment").hide();
				$(".btnAddInvoice").show();
			}
		}
		
		$("#content").on("click", ".btnConfirmation", function () {
			if (dialog && $dialogConfirmation.length > 0) {
				$dialogConfirmation.dialog("open");
			}
		}).on("click", ".cal-date", function (e) {
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			
			var $this = $(this),
				content = $grid_avail.datagrid("option", "content"),
				cache = $grid_avail.datagrid("option", "cache"),
				obj = {};
			
			obj.uuid = "";
			obj.status = "confirmed";
			obj.iso_date = $this.data("iso_date");
			$.extend(cache, obj);
			$grid_avail.datagrid("option", "cache", cache);
			$grid_avail.datagrid("load", "index.php?controller=pjAdminBookings&action=pjActionGetBooking", "id", "ASC", content.page, content.rowCount);
			
			return false;
		}).on("click", ".cal-room", function (e) {
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			
			var $this = $(this),
				content = $grid_avail.datagrid("option", "content"),
				cache = $grid_avail.datagrid("option", "cache"),
				obj = {};
			
			obj.uuid = "";
			obj.status = "confirmed";
			obj.iso_date = $this.data("iso_date");
			obj.room_id = $this.data("room_id");
			$.extend(cache, obj);
			$grid_avail.datagrid("option", "cache", cache);
			$grid_avail.datagrid("load", "index.php?controller=pjAdminBookings&action=pjActionGetBooking", "id", "ASC", content.page, content.rowCount);
			
			return false;
		}).on("click", ".prev30, .next30", function (e) {
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			var rel = $(this).attr("rel").split("|"),
				d = rel[0].split("-");
			getAvailability.apply(null, [d[0], d[1], d[2], rel[1]]);
			return false;
		}).on("focusin", ".datepick", function (e) {
			var $this = $(this);
			$this.datepicker({
				dateFormat: $this.attr("rev"),
				firstDay: $this.attr("rel"),
				dayNames: ($this.data("day")).split(","),
			    monthNames: ($this.data("months")).split(","),
			    monthNamesShort: ($this.data("shortmonths")).split(","),
			    dayNamesMin: ($this.data("daymin")).split(","),
				onSelect: function (dateText, inst) {
					if (inst.id == 'selected_date') {
						getCalendar('date');
					}
					if (inst.id == "date_from") {
						var based_on = $this.attr('data-based');
						if(based_on == 'days')
						{
							var nextDate = new Date(inst.selectedYear, inst.selectedMonth, Number(inst.selectedDay));
						}else{
							var nextDate = new Date(inst.selectedYear, inst.selectedMonth, Number(inst.selectedDay) + 1);
						}
						$("#date_to")
							.trigger("focusin")
							.datepicker("option", "minDate", nextDate)
							.datepicker("setDate", nextDate);
					}
					if ($frmUpdateBooking.length > 0 && (inst.id == "date_from" || inst.id == "date_to")) {
						runPriceAgent.call(null, $frmUpdateBooking);
					}
					if ($frmCreateBooking.length > 0 || $frmUpdateBooking.length > 0) {
						$('#date_from').valid();
						$('#date_to').valid();
					}
				}
			});
		}).on("change", "#payment_method", function () {
			if ($("option:selected", this).val() == 'creditcard') {
				$(".hbCC").show();
			} else {
				$(".hbCC").hide();
			}
		}).on("click", ".room-edit", function (e) {
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			if ($dialogRoomEdit.length > 0 && dialog) {
				$dialogRoomEdit.data("id", $(this).data("id")).dialog("open");
			}
			return false;
		}).on("click", ".room-delete", function (e) {
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			if ($dialogRoomDelete.length > 0 && dialog) {
				$dialogRoomDelete.data("id", $(this).data("id")).dialog("open");
			}
			return false;
		}).on("click", ".room-add", function (e) {
			if ($dialogRoomAdd.length > 0 && dialog) {
				$dialogRoomAdd.dialog("open");
			}
		}).on("click", ".booking-recalc", function (e) {
			recalcPrice.call(this);
		}).on("click", ".btnAddInvoice", function () {
			$("#frmAddInvoice").trigger("submit");
		}).on("click", ".btnBalancePayment", function () {
			$("#frmBalancePayment").trigger("submit");
			
		}).on("click", "#hb_prev_week", function (e) {
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			getCalendar('prev');
			return false;
		}).on("click", "#hb_next_week", function (e) {
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			getCalendar('next');
			return false;
		}).on("click", "#hb_prev_date", function (e) {
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			getCalendar('prev_date');
			return false;
		}).on("click", "#hb_next_date", function (e) {
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			getCalendar('next_date');
			return false;
		}).on("change", "#room_id", function (e) {
			getCalendar('room');
		}).on("click", ".hbCalendarTip, .hbCalendarRestriction", function (e) {
			var href = $(this).attr("data-href");
			window.location.href = href;
		}).on("click", ".client-details", function (e) {
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			$("#tabs").tabs("option", "active", 1);
			return false;
		});

		function getCalendar(mode) {
			var selected_date = $('#selected_date').val(),
				room_id = $('#room_id').val(),
				opts = {},
				param_str = '';
			if (mode == 'room' || mode == 'date') {
				opts = {
					room_id: room_id,
					selected_date: selected_date
				};
				param_str = "&room_id=" + room_id + "&selected_date=" + selected_date;
			} else if (mode == 'next') {
				opts = {
					room_id: room_id,
					week_start_date: $('#hb_next_week').attr('data-week_start'),
					selected_date: selected_date
				};
				param_str = "&room_id=" + room_id + "&selected_date=" + selected_date + "&week-start_date=" + $('#hb_prev_week').attr('data-week_start');
			} else if (mode == 'prev') {
				opts = {
					room_id: room_id,
					week_start_date: $('#hb_prev_week').attr('data-week_start'),
					selected_date: selected_date
				};
				param_str = "&room_id=" + room_id + "&selected_date=" + selected_date + "&week-start_date=" + $('#hb_prev_week').attr('data-week_start');
			} else if (mode == 'next_date') {
				opts = {
					room_id: room_id,
					week_start_date: $('#hb_next_date').attr('data-week_start'),
					selected_date: selected_date
				};
				param_str = "&room_id=" + room_id + "&selected_date=" + selected_date + "&week-start_date=" + $('#hb_next_date').attr('data-week_start');
			} else if (mode == 'prev_date') {
				opts = {
					room_id: room_id,
					week_start_date: $('#hb_prev_date').attr('data-week_start'),
					selected_date: selected_date
				};
				param_str = "&room_id=" + room_id + "&selected_date=" + selected_date + "&week-start_date=" + $('#hb_prev_date').attr('data-week_start');
			}
			
			$('.hb-loader').css('display', 'block');
			$.get("index.php?controller=pjAdminBookings&action=pjActionGetCalendar", opts).done(function (data) {
				$("#boxCalendar").html(data);
				$('#hb_print_calendar').attr('href', $('#hb_print_calendar').data("href") + param_str)
				$(".hbCalendarTip").tipsy({
					offset: 1,
					opacity: 1,
					html: true,
					gravity: "s",
					className: "hbTipsy"
				});
				$('.hb-loader').css('display', 'none');
			});
		}
		
		if ($('#boxCalendar').length > 0) {
			$(".hbCalendarTip").tipsy({
				offset: 1,
				opacity: 1,
				html: true,
				gravity: "s",
				className: "hbTipsy"
			});
		}
		
		function onChange() {
			var $this = $(this),
				$option = $this.find("option:selected"),
				$form = $this.closest("form"),
				$details = $form.find(".room_details"),
				$frm = $frmUpdateBooking.length > 0 ? $frmUpdateBooking : $frmCreateBooking;
			
			$details.hide();
			destroySpinner.call(null, $form);
			$form.find("input[name='adults']").val('1').data("max", $option.data("adults"));
			$form.find("input[name='children']").val('0').data("max", $option.data("children"));
			attachSpinner.call(null, $form);
			$details.show();
			
			$.get("index.php?controller=pjAdminBookings&action=pjActionGetRoomNumbers", {
				booking_id: booking_id,
				room_id: $option.val(),
				date_from: $frm.find('input[name="date_from"]').val(),
				date_to: $frm.find('input[name="date_to"]').val(),
				hash: $frm.find('input[name="hash"]').val()
			}).done(function (data) {
				$("#room_number_holder").html(data);
			});
		}
		
		function attachSpinner($container) {
			if (!spinner) {
				return false;
			}
			var $adults = $container.find("input[name='adults']"),
				$children = $container.find("input[name='children']");
			if ($adults.length > 0) {
				$adults.spinner({
					min: 1,
					max: $adults.data("max")
				});
			}
			if ($children.length > 0) {
				$children.spinner({
					min: 0,
					max: $children.data("max")
				});
			}
			
			return true;
		}
		
		function destroySpinner($container) {
			if (!spinner) {
				return false;
			}
			$container.find("input[name='adults']").spinner("destroy");
			$container.find("input[name='children']").spinner("destroy");
			
			return true;
		}
		
		function recalcPrice() {
			$.post("index.php?controller=pjAdminBookings&action=pjActionRecalcPrice", $(this).closest("form").serialize()).done(function (response) {
				if (response.status === "OK") {
					$("#room_price").val(response.data.room_price.toFixed(2));
					$("#extra_price").val(response.data.extra_price.toFixed(2));
					$("#total").val(response.data.total.toFixed(2));
					$("#tax").val(response.data.tax.toFixed(2));
					$("#security").val(response.data.security.toFixed(2));
					$("#deposit").val(response.data.deposit.toFixed(2));
					$("#discount").val(response.data.discount.toFixed(2));
					
					if ($frmUpdateBooking.length > 0) {
						runPriceAgent.call(null, $frmUpdateBooking);
					}
				}
			});
		}
		
		if ($dialogRoomAdd.length > 0 && dialog) {
			$dialogRoomAdd.dialog({
				autoOpen: false,
				draggable: false,
				resizable: false,
				modal: true,
				width: 500,
				open: function () {
					$dialogRoomAdd.html("");
					var $frm = $frmUpdateBooking.length > 0 ? $frmUpdateBooking : $frmCreateBooking;
					
					var search = window.location.search,
						tmp = search.match(/&room_id=(\d+)/),
						room_id = tmp ? tmp[1] : undefined,
						tmp = search.match(/&room_number_id=(\d+)/),
						room_number_id = tmp ? tmp[1] : undefined;
					
					$.get("index.php?controller=pjAdminBookings&action=pjActionAddBookingRoom", {
						"booking_id": booking_id,
						"hash": hash,
						"date_from": $frm.find("input[name='date_from']").val(),
						"date_to": $frm.find("input[name='date_to']").val(),
						"room_id": room_id,
						"room_number_id": room_number_id
					}).done(function (data) {
						$dialogRoomAdd.html(data);
						attachSpinner.call(null, $dialogRoomAdd);
						validator = $dialogRoomAdd.find("form").validate({
							ignore: ".ignore"
						});
						$dialogRoomAdd.dialog("option", "position", "center");
					});
				},
				buttons: {
					'Save': function () {
						if (validator.form()) {
							$.post("index.php?controller=pjAdminBookings&action=pjActionAddBookingRoom", $dialogRoomAdd.find("form").serialize()).done(function (data) {
								if (data.status !== undefined && data.status === 'OK') {
									getBookingRooms.call(null, booking_id, hash);
									if ($frmUpdateBooking.length > 0) {
										runPriceAgent.call(null, $frmUpdateBooking);
									}
								}
							}).always(function () {
								$dialogRoomAdd.dialog("close");
							});
						}
					},
					'Cancel': function () {
						$(this).dialog("close");
					}
				}
			}).on("change", "select[name='room_id']", onChange);
		}
		
		if ($dialogRoomEdit.length > 0 && dialog) {
			$dialogRoomEdit.dialog({
				autoOpen: false,
				draggable: false,
				resizable: false,
				modal: true,
				width: 500,
				open: function () {
					$dialogRoomEdit.html("");
					$.get("index.php?controller=pjAdminBookings&action=pjActionUpdateBookingRoom", {
						"id": $dialogRoomEdit.data("id")
					}).done(function (data) {
						$dialogRoomEdit.html(data);
						attachSpinner.call(null, $dialogRoomEdit);
						validator = $dialogRoomEdit.find("form").validate({
							ignore: ".ignore"
						});
						$dialogRoomEdit.dialog("option", "position", "center");
					});
				},
				buttons: {
					'Update': function () {
						if (validator.form()) {
							$.post("index.php?controller=pjAdminBookings&action=pjActionUpdateBookingRoom", $dialogRoomEdit.find("form").serialize()).done(function (data) {
								if (data.status !== undefined && data.status === 'OK') {
									getBookingRooms.call(null, booking_id, hash);
									if ($frmUpdateBooking.length > 0) {
										runPriceAgent.call(null, $frmUpdateBooking);
									}
								}
							}).always(function () {
								$dialogRoomEdit.dialog("close");
							});
						}
					},
					'Cancel': function () {
						$(this).dialog("close");
					}
				}
			}).on("change", "select[name='room_id']", onChange);
		}
		
		if ($dialogRoomDelete.length > 0 && dialog) {
			$dialogRoomDelete.dialog({
				autoOpen: false,
				draggable: false,
				resizable: false,
				modal: true,
				buttons: {
					'Delete': function () {
						$.post("index.php?controller=pjAdminBookings&action=pjActionDeleteBookingRoom", {
							"id": $dialogRoomDelete.data("id")
						}).done(function (data) {
							if (data.status !== undefined && data.status == 'OK') {
								getBookingRooms.call(null, booking_id, hash);
								if ($frmUpdateBooking.length > 0) {
									runPriceAgent.call(null, $frmUpdateBooking);
								}
							}
						}).always(function () {
							$dialogRoomDelete.dialog("close");
						});
					},
					'Cancel': function () {
						$(this).dialog("close");
					}
				}
			});
		}
		
		if ($dialogConfirmation.length > 0 && dialog) {
			$dialogConfirmation.dialog({
				autoOpen: false,
				draggable: false,
				resizable: false,
				modal: true,
				width: 640,
				open: function () {
					$dialogConfirmation.html("");
					$.get("index.php?controller=pjAdminBookings&action=pjActionConfirmation", {
						"booking_id": $frmUpdateBooking.find("input[name='id']").val()
					}).done(function (data) {
						$dialogConfirmation.html(data);
						validator = $dialogConfirmation.find("form").validate({
							
						});
						$dialogConfirmation.dialog("option", "position", "center");
						attachTinyMce.call(null);
					});
				},
				buttons: {
					'Send': function () {
						if (validator.form()) {
							$('#confirm_message').html( tinymce.get('confirm_message').getContent() );
							$("#dialogConfirmation").next(".ui-dialog-buttonpane").find(".ui-button").attr("disabled", true).addClass("ui-state-disabled");
							$.post("index.php?controller=pjAdminBookings&action=pjActionConfirmation", $dialogConfirmation.find("form").serialize()).done(function (data) {
								$("#dialogConfirmation").next(".ui-dialog-buttonpane").find(".ui-button").removeAttr("disabled").removeClass("ui-state-disabled");
								$dialogConfirmation.dialog("close");
							})
						}
					},
					'Cancel': function () {
						$(this).dialog("close");
					}
				}
			});
		}
		
		function formatDates(val, obj) {
			return [$.datagrid._formatDate(val, pjGrid.jsDateFormat), ' - ', $.datagrid._formatDate(obj.date_to, pjGrid.jsDateFormat)].join("");
		}
		
		function formatRooms(val, obj) {
			var arr = [], i, iCnt;
			for (i = 0, iCnt = obj.rooms.length; i < iCnt; i++) {
				arr.push([obj.rooms[i].cnt, " x ", obj.rooms[i].name, ' - ', obj.rooms[i].room_number].join(""));
			}
			
			return arr.join("<br>");
		}
		function formatBid (str, obj) {
			return ['<a href="index.php?controller=pjAdminBookings&action=pjActionUpdate&id=', obj.id, '">', obj.uuid, '</a><br>', obj.created].join("");
		}
		
		if ($("#grid").length > 0 && datagrid) {
			
			var cache = {},
				search = window.location.search,
				_status = search.match(/&status=(\w+)/),
				_date_from = search.match(/&date_from=([\d\-\.\/]+)/),
				_date_to = search.match(/&date_to=([\d\-\.\/]+)/);
			if (_status) {
				cache.status = _status[1];
			}
			if (_date_from) {
				cache.date_from = _date_from[1];
			}
			if (_date_to) {
				cache.date_to = _date_to[1];
			}
			
			var $grid = $("#grid").datagrid({
				buttons: [{type: "edit", url: "index.php?controller=pjAdminBookings&action=pjActionUpdate&id={:id}"},
				          {type: "delete", url: "index.php?controller=pjAdminBookings&action=pjActionDeleteBooking&id={:id}"}
				          ],
				columns: [{text: myLabel.id, type: "text", sortable: true, editable: false, width: 110, renderer: formatBid},
				          {text: myLabel.stay, type: "text", sortable: true, editable: false, renderer: formatDates, width: 150},
				          {text: myLabel.client, type: "text", sortable: true, editable: false, width: 225, editableWidth: 200},
				          {text: myLabel.rooms, type: "text", sortable: false, editable: false, renderer: formatRooms, width: 235},
				          {text: myLabel.status, type: "select", sortable: true, editable: true, width: 125, editableWidth: 120, options: [
				                                                                                     {label: myLabel.cancelled, value: "cancelled"}, 
				                                                                                     {label: myLabel.pending, value: "pending"},
				                                                                                     {label: myLabel.not_confirmed, value: "not_confirmed"},
				                                                                                     {label: myLabel.confirmed, value: "confirmed"}
				                                                                                     ], applyClass: "pj-status"}],
				dataUrl: "index.php?controller=pjAdminBookings&action=pjActionGetBooking",
				dataType: "json",
				fields: ['id', 'date_from', 'c_email', 'id', 'status'],
				paginator: {
					actions: [
					   {text: myLabel.delete_selected, url: "index.php?controller=pjAdminBookings&action=pjActionDeleteBookingBulk", render: true, confirmation: myLabel.delete_confirmation},
					   {text: myLabel.exported, url: "index.php?controller=pjAdminBookings&action=pjActionExportBooking", ajax: false}
					],
					gotoPage: true,
					paginate: true,
					total: true,
					rowCount: true
				},
				saveUrl: "index.php?controller=pjAdminBookings&action=pjActionSaveBooking&id={:id}",
				select: {
					field: "id",
					name: "record[]"
				},
				cache: cache
			});
			
			var m = window.location.href.match(/room_id=(\d+)&iso_date=(\d{4}-\d{2}-\d{2})/);
			if (m !== null) {
				var content = $grid.datagrid("option", "content"),
					cache = $grid.datagrid("option", "cache");
				$.extend(cache, {
					room_id: m[1],
					iso_date: m[2]
				});
				$grid.datagrid("option", "cache", cache);
				$grid.datagrid("load", "index.php?controller=pjAdminBookings&action=pjActionGetBooking", "created", "DESC", content.page, content.rowCount);
			}
		}
		
		if ($("#grid_avail").length > 0 && datagrid) {
			
			var $grid_avail = $("#grid_avail").datagrid({
				buttons: [{type: "edit", url: "index.php?controller=pjAdminBookings&action=pjActionUpdate&id={:id}"},
				          {type: "delete", url: "index.php?controller=pjAdminBookings&action=pjActionDeleteBooking&id={:id}"}
				          ],
				columns: [{text: myLabel.dates, type: "text", sortable: true, editable: false, renderer: formatDates, width: 140},
				          {text: myLabel.client, type: "text", sortable: true, editable: true, renderer: formatClient, width: 225, editableWidth: 200},
				          {text: myLabel.rooms, type: "text", sortable: false, editable: false, renderer: formatRooms, width: 125},
				          {text: myLabel.status, type: "select", sortable: true, editable: true, width: 100, options: [
				                                                                                     {label: myLabel.cancelled, value: "cancelled"}, 
				                                                                                     {label: myLabel.pending, value: "pending"},
				                                                                                     {label: myLabel.confirmed, value: "confirmed"}
				                                                                                     ], applyClass: "pj-status"}],
				dataUrl: "index.php?controller=pjAdminBookings&action=pjActionGetBooking&uuid=-1",
				dataType: "json",
				fields: ['date_from', 'c_email', 'id', 'status'],
				paginator: {
					actions: [
					   {text: myLabel.delete_selected, url: "index.php?controller=pjAdminBookings&action=pjActionDeleteBookingBulk", render: true, confirmation: myLabel.delete_confirmation},
					   {text: myLabel.exported, url: "index.php?controller=pjAdminBookings&action=pjActionExportBooking", ajax: false}
					],
					gotoPage: true,
					paginate: true,
					total: true,
					rowCount: true
				},
				saveUrl: "index.php?controller=pjAdminBookings&action=pjActionSaveBooking&id={:id}",
				select: {
					field: "id",
					name: "record[]"
				}
			});
		}
		
		function formatDefault (str) {
			return myLabel[str] || str;
		}
		
		function formatId (str) {
			return ['<a href="index.php?controller=pjInvoice&action=pjActionUpdate&id=', str, '">#', str, '</a>'].join("");
		}
		
		function formatTotal (str, obj) {
			return obj.total_formated;
		}
		
		function formatCreated(str) {
			if (str === null || str.length === 0) {
				return myLabel.empty_datetime;
			}
			
			if (str === '0000-00-00 00:00:00') {
				return myLabel.invalid_datetime;
			}
			
			if (str.match(/\d{4}-\d{2}-\d{2}\s\d{2}:\d{2}:\d{2}/) !== null) {
				var x = str.split(" "),
					date = x[0],
					time = x[1],
					dx = date.split("-"),
					tx = time.split(":"),
					y = dx[0],
					m = parseInt(dx[1], 10) - 1,
					d = dx[2],
					hh = tx[0],
					mm = tx[1],
					ss = tx[2];
				return $.datagrid.formatDate(new Date(y, m, d, hh, mm, ss), pjGrid.jsDateFormat + ", hh:mm:ss");
			}
		}
		
		if ($("#grid_invoices").length > 0 && datagrid) {
			var $grid_invoices = $("#grid_invoices").datagrid({
				buttons: [{type: "edit", url: "index.php?controller=pjInvoice&action=pjActionUpdate&id={:id}", title: "Edit"},
				          {type: "delete", url: "index.php?controller=pjInvoice&action=pjActionDelete&id={:id}", title: "Delete"}],
				columns: [
				    {text: myLabel.num, type: "text", sortable: true, editable: false, renderer: formatId},
				    {text: myLabel.order_id, type: "text", sortable: true, editable: false},
				    {text: myLabel.issue_date, type: "date", sortable: true, editable: false, renderer: $.datagrid._formatDate, dateFormat: pjGrid.jsDateFormat},
				    {text: myLabel.due_date, type: "date", sortable: true, editable: false, renderer: $.datagrid._formatDate, dateFormat: pjGrid.jsDateFormat},
				    {text: myLabel.created, type: "text", sortable: true, editable: false, renderer: formatCreated},
				    {text: myLabel.status, type: "text", sortable: true, editable: false, renderer: formatDefault},	
				    {text: myLabel.total, type: "text", sortable: true, editable: false, align: "right", renderer: formatTotal}
				],
				dataUrl: "index.php?controller=pjInvoice&action=pjActionGetInvoices&q=" + $frmUpdateBooking.find("input[name='uuid']").val(),
				dataType: "json",
				fields: ['id', 'order_id', 'issue_date', 'due_date', 'created', 'status', 'total'],
				paginator: {
					actions: [
					   {text: myLabel.delete_title, url: "index.php?controller=pjInvoice&action=pjActionDeleteBulk", render: true, confirmation: myLabel.delete_body}
					],
					gotoPage: true,
					paginate: true,
					total: true,
					rowCount: true
				},
				select: {
					field: "id",
					name: "record[]"
				}
			});
		}
		
		function getBookingRooms(booking_id, hash){
			$.get("index.php?controller=pjAdminBookings&action=pjActionGetBookingRooms", {
				"booking_id": booking_id,
				"hash": hash
			}).done(function (data) {
				$("#boxRooms").html(data);				
			});
		}
		
		$(document).on("click", ".btn-all", function (e) {
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			$(this).addClass("pj-button-active").siblings(".pj-button").removeClass("pj-button-active");
			var content = $grid.datagrid("option", "content"),
				cache = $grid.datagrid("option", "cache");
			$.extend(cache, {
				status: "",
				q: ""
			});
			$grid.datagrid("option", "cache", cache);
			$grid.datagrid("load", "index.php?controller=pjAdminBookings&action=pjActionGetBooking", "id", "ASC", content.page, content.rowCount);
			return false;
		}).on("click", ".btn-filter", function (e) {
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			var $this = $(this),
				content = $grid.datagrid("option", "content"),
				cache = $grid.datagrid("option", "cache"),
				obj = {};
			$this.addClass("pj-button-active").siblings(".pj-button").removeClass("pj-button-active");
			obj.status = "";
			obj[$this.data("column")] = $this.data("value");
			$.extend(cache, obj);
			$grid.datagrid("option", "cache", cache);
			$grid.datagrid("load", "index.php?controller=pjAdminBookings&action=pjActionGetBooking", "id", "ASC", content.page, content.rowCount);
			return false;
		}).on("submit", ".frm-filter", function (e) {
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			var $this = $(this),
				content = $grid.datagrid("option", "content"),
				cache = $grid.datagrid("option", "cache");
			$.extend(cache, {
				q: $this.find("input[name='q']").val()
			});
			$grid.datagrid("option", "cache", cache);
			$grid.datagrid("load", "index.php?controller=pjAdminBookings&action=pjActionGetBooking", "id", "ASC", content.page, content.rowCount);
			return false;
		}).on("click", ".pj-form-field-icon-date", function (e) {
			var $dp = $(this).parent().siblings("input[type='text']");
			if ($dp.hasClass("hasDatepicker")) {
				$dp.datepicker("show");
			} else {
				$dp.trigger("focusin").datepicker("show");
			}
		}).on("click", ".pj-button-detailed, .pj-button-detailed-arrow", function (e) {
			e.stopPropagation();
			$(".pj-form-filter-advanced").toggle();
		}).on("submit", ".frm-filter-advanced", function (e) {
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			var obj = {},
				$this = $(this),
				arr = $this.serializeArray(),
				content = $grid.datagrid("option", "content"),
				cache = $grid.datagrid("option", "cache");
			for (var i = 0, iCnt = arr.length; i < iCnt; i++) {
				obj[arr[i].name] = arr[i].value;
			}
			$.extend(cache, obj);
			$grid.datagrid("option", "cache", cache);
			$grid.datagrid("load", "index.php?controller=pjAdminBookings&action=pjActionGetBooking", "id", "DESC", content.page, content.rowCount);
			return false;
		}).on("reset", ".frm-filter-advanced", function (e) {
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			$(".pj-button-detailed").trigger("click");
			return false;
		});
		
		function attachTinyMce(options) {
			if (window.tinymce !== undefined) {
				tinymce.EditorManager.editors = [];
				var defaults = {
					selector: "textarea.mceEditor",
					theme: "modern",
					width: 610,
					height: 330,
					plugins: [
				         "advlist autolink link image lists charmap print preview hr anchor pagebreak",
				         "searchreplace visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
				         "save table contextmenu directionality emoticons template paste textcolor"
				    ],
				    toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | print preview media fullpage | forecolor backcolor emoticons"
				};
				
				var settings = $.extend({}, defaults, options);
				
				tinymce.init(settings);
			}
		}
	});
})(jQuery_1_8_2);