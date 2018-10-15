/*!
 * Hotel Booking v3.0
 * http://www.phpjabbers.com/hotels-booking-system/
 * 
 * Copyright 2013, StivaSoft Ltd.
 * http://www.phpjabbers.com/license-agreement.php
 * http://www.phpjabbers.com/licence-explained.php
 * 
 * Date: Thu Jun 27 17:26:40 2013 +0200
 */
(function (window, undefined){
	"use strict";

	pjQ.$.ajaxSetup({
		xhrFields: {
			withCredentials: true
		}
	});
	
	pjQ.$.validator.setDefaults({
	    highlight: function(element) {
	        pjQ.$(element).closest('.form-group').removeClass('has-success').addClass('has-error');
	    },
	    unhighlight: function(element) {
	    	pjQ.$(element).closest('.form-group').removeClass('has-error').addClass("has-success");
	    },
	    errorElement: 'span',
	    errorClass: 'help-block',
	    errorPlacement: function(error, element) {
	        if(element.parent('.input-group').length || element.prop('type') === 'checkbox') {
	            error.insertAfter(element.parent());
	        } else {
	            error.insertAfter(element);
	        }
	    }
	});
	
	var document = window.document,
		validate = (pjQ.$.fn.validate !== undefined),
		dialog = (pjQ.$.fn.dialog !== undefined),
		datepicker = (pjQ.$.fn.datepicker !== undefined),
		fancybox = (pjQ.$.fn.fancybox !== undefined),
		routes = [
	          {pattern: /^#!\/Search$/, eventName: "loadSearch"},
	          {pattern: /^#!\/Rooms\/date_from:([0-9\.\-\/]{8,10})?\/date_to:([0-9\.\-\/]{8,10})?\/guests:(\d+)?$/, eventName: "loadRooms"},
	          {pattern: /^#!\/Rooms$/, eventName: "loadRooms"},
	          {pattern: /^#!\/Extras$/, eventName: "loadExtras"},
	          {pattern: /^#!\/Checkout$/, eventName: "loadCheckout"},
	          {pattern: /^#!\/Preview$/, eventName: "loadPreview"},
	          {pattern: /^#!\/Booking\/([A-Z]{2}\d{10})$/, eventName: "loadBooking"}
	    ],
	    defaults = {
	    	scrollOffset: 80,
	    	scrollTop: true,
	    	scrollSpeed: 1000,
	    	scrollCounter: 0
	    };
	
	function log() {
		if (window && window.console && window.console.log) {
			window.console.log.apply(window.console, arguments);
		}
	}
	
	function assert() {
		if (window && window.console && window.console.assert) {
			window.console.assert.apply(window.console, arguments);
		}
	}
	
	function hashBang(value) {
		if (value !== undefined && value.match(/^#!\//) !== null) {
			if (window.location.hash == value) {
				return false;
			}
			window.location.hash = value;
			return true;
		}
		
		return false;
	}
	
	function onHashChange() {
		var i, iCnt, m;
		for (i = 0, iCnt = routes.length; i < iCnt; i++) {
			m = window.location.hash.match(routes[i].pattern);
			if (m !== null) {
				pjQ.$(window).trigger(routes[i].eventName, m.slice(1));
				break;
			}
		}
		if (m === null) {
			pjQ.$(window).trigger("loadInit");
		}
	}
	
	pjQ.$(window).on("hashchange", function (e) {
    	onHashChange.call(null);
    });
	
	function HotelBooking(opts) {
		if (!(this instanceof HotelBooking)) {
			return new HotelBooking(opts);
		}
		this.reset.call(this);
		this.init.call(this, opts);
		return this;
	}
	
	HotelBooking.timeToISO = function (time) {
		var dt = new Date(time),
			m = (dt.getMonth() + 1).toString(),
			d = dt.getDate().toString();
		m = m.length === 1 ? "0" + m : m;
		d = d.length === 1 ? "0" + d : d;
		
		return [dt.getFullYear(), m, d].join("-");
	};

	HotelBooking.prototype = {
		reset: function () {
			this.opts = null;
			this.$body = pjQ.$("html, body");
			this.$container = null;
			this.container = null;
			this.uuid = "";
			this.date_from = "";
			this.date_to = "";
			this.guests = 1;
			this.adults = 1;
			this.children = 0;
			this.notScroll = false;
			this.startDt = null;
			this.endDt = null;
			return this;
		},
		init: function (opts) {
			var self = this;
			this.opts = pjQ.$.extend({}, defaults, opts);
			
			this.container = document.getElementById("hbContainer_" + this.opts.cid);
			this.$container = pjQ.$(this.container);
			
			pjQ.$("html").attr('dir',self.opts.direction);
			
			// Event delegation
			this.$container.on("click.hb", ".hbSelectorLocale", function (e) {
				if (e && e.preventDefault) {
					e.preventDefault();
				}
				var locale = pjQ.$(this).data("id");
				var dir = pjQ.$(this).data("dir");
				self.opts.direction = dir;
				self.opts.locale = locale;
				pjQ.$(this).addClass("hbLocaleFocus").parent().parent().find("a.hbSelectorLocale").not(this).removeClass("hbLocaleFocus");
				
				self.disableButtons.call(self);
				var qs = {
						"cid": self.opts.cid,
						"locale_id": locale
					};
				if(self.opts.session_id != '')
				{
					qs.session_id = self.opts.session_id;
				}
				pjQ.$.get([self.opts.folder, "index.php?controller=pjFront&action=pjActionLocale"].join(""), qs).done(function (data) {
					pjQ.$("html").attr('dir',dir);
					if (data.status === "OK" && data.opts) {
						if (data.opts.day_names) {
							self.opts.day_names = data.opts.day_names;
						}
						if (data.opts.month_names) {
							self.opts.month_names = data.opts.month_names;
						}
					}
					onHashChange.call(null);
				}).fail(function () {
					self.enableButtons.call(self);
				});
				return false;
			}).on("mouseenter.hb", "input.hbButtonOrange", function (e) {
				pjQ.$(this).addClass("hbButtonOrangeHover");
			}).on("mouseleave.hb", "input.hbButtonOrange", function (e) {
				pjQ.$(this).removeClass("hbButtonOrangeHover");
			}).on("mouseenter.hb", "input.hbButtonGray", function (e) {
				pjQ.$(this).addClass("hbButtonGrayHover");
			}).on("mouseleave.hb", "input.hbButtonGray", function (e) {
				pjQ.$(this).removeClass("hbButtonGrayHover");
			}).on("change.hb", ".hbSelectorRoomCnt", function () {
				var $select = pjQ.$(this),
					$item = $select.closest(".hbRoomItem"),
					room_id = $select.data("id"),
					cnt = $select.find("option:selected").val();
				
				self.disableButtons.call(self);
				var ajax_url = [self.opts.folder, "index.php?controller=pjFront&action=pjActionGetRoom"].join("");
				if(self.opts.session_id != '')
				{
					ajax_url = [self.opts.folder, "index.php?controller=pjFront&action=pjActionGetRoom", "&session_id=", self.opts.session_id].join("");
				}
				pjQ.$.get(ajax_url, {
					"cid": self.opts.cid,
					"room_id": room_id,
					"cnt": cnt,
					"adults": self.adults,
					"children": self.children
				}).done(function (data) {
					self.enableButtons.call(self);
					if (data.code != undefined && data.code == 100) 
					{
						hashBang("#!/Search");
					}else{
						$item.html(data);
						if (parseInt(cnt, 10) === 0 && fancybox) {
							pjQ.$(".hbSelectorThumb").fancybox({
								openEffect	: 'none',
								closeEffect	: 'none'
							});
						}
						self.getAdultsChildren.call(self, function (resp) {
							if (resp.adults > 0 || resp.children > 0) {
								self.$container
									.find(".hbSelectorAccommodate").html(resp.text).show()
									.end()
									.find(".hbSelectorExtras").show();
							} else {
								self.$container
									.find(".hbSelectorAccommodate").hide().html("")
									.end()
									.find(".hbSelectorExtras").hide();
							}
						});
					}
				}).fail(function () {
					self.enableButtons.call(self);
				});
			}).on("click.hb", ".hbSelectorCancelRoom", function () {
				var $this = pjQ.$(this),
					$item = $this.closest(".hbRoomItem"),
					room_id = $this.closest("form").find("input[name='room_id']").val();
				
				self.disableButtons.call(self);
				var ajax_url = [self.opts.folder, "index.php?controller=pjFront&action=pjActionGetRoom"].join("");
				if(self.opts.session_id != '')
				{
					ajax_url = [self.opts.folder, "index.php?controller=pjFront&action=pjActionGetRoom", "&session_id=", self.opts.session_id].join("");
				}
				pjQ.$.get(ajax_url, {
					"cid": self.opts.cid,
					"room_id": room_id,
					"cnt": 0
				}).done(function (data) {
					self.enableButtons.call(self);
					$item.html(data);
					if (fancybox) {
						pjQ.$(".hbSelectorThumb").fancybox({
							openEffect	: 'none',
							closeEffect	: 'none'
						});
					}
				}).fail(function () {
					self.enableButtons.call(self);
				});
			}).on("click.hb", ".hbSelectorEditRoom", function (e) {
				if (e && e.preventDefault) {
					e.preventDefault();
				}
				pjQ.$(this).siblings(".hbSelectorRoomCnt").trigger("change");
				return false;
			}).on("change.hb", ".hbSelectorPeople", function () {
				var $this = pjQ.$(this),
					$tr = $this.closest("tr"),
					$frm = $this.closest("form");
				
				var adults = parseInt($tr.find("select[name='adults[]']").find("option:selected").val(), 10);
				var children = parseInt($tr.find("select[name='children[]']").find("option:selected").val(), 10);
				var max_people = parseInt($tr.attr('data-max'), 10);
				
				if(max_people >= adults + children)
				{
					self.disableButtons.call(self);
					var ajax_url = [self.opts.folder, "index.php?controller=pjFront&action=pjActionGetPrice"].join("");
					if(self.opts.session_id != '')
					{
						ajax_url = [self.opts.folder, "index.php?controller=pjFront&action=pjActionGetPrice", "&session_id=", self.opts.session_id].join("");
					}
					pjQ.$.get(ajax_url, {
						"cid": self.opts.cid,
						"adults": $tr.find("select[name='adults[]']").find("option:selected").val(),
						"children": $tr.find("select[name='children[]']").find("option:selected").val(),
						"room_id": $this.closest("form").find("input[name='room_id']").val(),
						"index": $this.data("index")
					}).done(function (data) {
						self.enableButtons.call(self);
						$this.closest("form").find(".hbSelectorTotal").html(data.format_total);
						$tr.find(".hbSelectorPrice").html(data.format_room);
					}).fail(function () {
						self.enableButtons.call(self);
					});
				}else{
					pjQ.$('#pjHbModalMaxOccupancy').modal('show');
					$frm.find(".hbSelectorBook").prop("disabled", true);
				}
				
			}).on("click.hb", ".hbSelectorBook", function (e) {
				if (e && e.preventDefault) {
					e.preventDefault();
				}
				var $button = pjQ.$(this),
					$item = $button.closest(".hbRoomItem"),
					$form = $button.closest("form");
				
				var ajax_url = [self.opts.folder, "index.php?controller=pjFront&action=pjActionSetPrice", "&cid=", self.opts.cid].join("");
				if(self.opts.session_id != '')
				{
					ajax_url = [self.opts.folder, "index.php?controller=pjFront&action=pjActionSetPrice", "&cid=", self.opts.cid, "&session_id=", self.opts.session_id].join("");
				}
				self.disableButtons.call(self);
				pjQ.$.post(ajax_url, $form.serialize()).done(function (data) {
					if (data.status == "OK") {
						var get_room_url = [self.opts.folder, "index.php?controller=pjFront&action=pjActionGetRoom"].join("");
						if(self.opts.session_id != '')
						{
							get_room_url = [self.opts.folder, "index.php?controller=pjFront&action=pjActionGetRoom", "&session_id=", self.opts.session_id].join("");
						}
						pjQ.$.get(get_room_url, {
							"cid": self.opts.cid,
							"room_id": $form.find("input[name='room_id']").val(),
							"cnt": 0
						}).done(function (response) {
							self.enableButtons.call(self);
							$item.html(response);
							if (fancybox) {
								pjQ.$(".hbSelectorThumb").fancybox({
									openEffect	: 'none',
									closeEffect	: 'none'
								});
							}
							
							self.getAdultsChildren.call(self, function (resp) {
								if (resp.adults > 0 || resp.children > 0) {
									self.$container
										.find(".hbSelectorAccommodate").html(resp.text).show()
										.end()
										.find(".hbSelectorExtras").show();
								} else {
									self.$container
										.find(".hbSelectorAccommodate").hide().html("")
										.end()
										.find(".hbSelectorExtras").hide();
								}
							});
						}).fail(function () {
							self.enableButtons.call(self);
						});
					} else {
						self.enableButtons.call(self);
					}
				}).fail(function () {
					self.enableButtons.call(self);
				});
				return false;
			}).on("click.hb", ".hbSelectorThumb", function (e) {
				if (e && e.preventDefault) {
					e.preventDefault();
				}
				var $this = pjQ.$(this),
					path = $this.data("path");
				if (path.length > 0) {
					$this.closest(".hbRoomPics").find(".hbSelectorImg").attr("src", path);
				}
				return false;
			}).on("change.hb", "select[name='payment_method']", function () {
				switch (pjQ.$(this).find("option:selected").val()) {
				case "creditcard":
					self.$container
						.find(".hbSelectorCCard").show()
						.end()
						.find(".hbSelectorBank").hide();
					break;
				case "bank":
					self.$container
						.find(".hbSelectorBank").show()
						.end()
						.find(".hbSelectorCCard").hide();
					break;
				default:
					self.$container.find(".hbSelectorCCard, .hbSelectorBank").hide();
				}
			}).on("click.hb", ".hbSelectorRooms", function (e) {
				if (e && e.preventDefault) {
					e.preventDefault();
				}
				self.disableButtons.call(self);
				if (!hashBang(["#!/Rooms/date_from:", self.date_from, "/date_to:", self.date_to, "/guests:", self.guests].join(""))) {
					self.enableButtons.call(self);
				}
				return false;
			}).on("click.hb", ".hbSelectorSearch", function (e) {
				if (e && e.preventDefault) {
					e.preventDefault();
				}
				self.disableButtons.call(self);
				if (!hashBang("#!/Search")) {
					self.enableButtons.call(self);
				}
				return false;
			}).on("click.hb", ".hbSelectorExtras", function (e) {
				if (e && e.preventDefault) {
					e.preventDefault();
				}
				self.disableButtons.call(self);
				hashBang("#!/Extras");
				return false;
			}).on("click.hb", ".hbSelectorCheckout", function (e) {
				if (e && e.preventDefault) {
					e.preventDefault();
				}
				self.disableButtons.call(self);
				hashBang("#!/Checkout");
				return false;
			}).on('click.hb', '.pjHbCalendar .input-group-addon', function (e) {
				pjQ.$(this).siblings().focus();
			}).on('focusin.hb', '.pjHbTimePick', function (e) {
				pjQ.$('.pjHbTimePick').datetimepicker({
					format: 'LT',
					allowInputToggle: true
				});
			}).on('click.hb', '.pjHbTimePick .input-group-addon', function (e) {
				pjQ.$(this).siblings().focus();
			}).on("change.hb", ".hbSelectorExtra", function () {
				self.disableButtons.call(self);
				self.handleExtras.call(self, this).done(function (data) {
					if (data.status == 'OK') {
						self.notScroll = true;
						self.loadExtras.call(self);
					} else {
						self.enableButtons.call(self);
					}
				}).fail(function () {
					self.enableButtons.call(self);
				});
			}).on("click.hb", ".hbSelectorRemoveCode", function (e) {
				if (e && e.preventDefault) {
					e.preventDefault();
				}
				var ajax_url = [self.opts.folder, "index.php?controller=pjFront&action=pjActionRemoveCode", "&cid=", self.opts.cid].join("");
				if(self.opts.session_id != '')
				{
					ajax_url = [self.opts.folder, "index.php?controller=pjFront&action=pjActionRemoveCode", "&cid=", self.opts.cid, "&session_id=", self.opts.session_id].join("");
				}
				self.disableButtons.call(self);
				pjQ.$.get(ajax_url).done(function (data) {
					self.loadExtras.call(self);
				}).fail(function () {
					self.enableButtons.call(self);
				});
				return false;
			}).on("click.hb", ".hbSelectorSearchSubmit, .hbSelectorCheckoutSubmit, .hbSelectorPreviewSubmit", function (e) {
				if (e && e.preventDefault) {
					e.preventDefault();
				}
				pjQ.$(this).closest("form").trigger("submit");
				return false;
			}).on("click.hb", "#pjHbCaptchaImage", function (e) {
				if (e && e.preventDefault) {
					e.preventDefault();
				}
				var $captcha = pjQ.$(this);
				var $form = $captcha.closest("form");
				$captcha.attr("src", $captcha.attr("src").replace(/(&rand=)\d+/g, '\$1' + Math.ceil(Math.random() * 99999)));
				pjQ.$('#pjHbCaptchaField').val("").removeData("previousValue");
				return false;
			});
			//Custom events
			pjQ.$(window).on("loadInit", this.container, function (e) {
				self.loadSearch.call(self);
			}).on("loadSearch", this.container, function (e) {
				self.loadSearch.call(self);
			}).on("loadRooms", this.container, function (e, date_from, date_to, guests) {
				self.date_from = date_from;
				self.date_to = date_to;
				self.guests = guests;
				self.loadRooms.call(self);
			}).on("loadExtras", this.container, function (e) {
				self.loadExtras.call(self);
			}).on("loadCheckout", this.container, function (e) {
				self.loadCheckout.call(self);
			}).on("loadPreview", this.container, function (e) {
				self.loadPreview.call(self);
			}).on("loadBooking", this.container, function (e, uuid) {
				self.uuid = uuid;
				self.loadBooking.call(self);
			});
			
			if (window.location.hash.length === 0) {
				this.loadSearch.call(this);
			} else {
				onHashChange.call(null);
			}
		},
		disableCheckboxes: function () {
			this.$container.find("input[type='checkbox']").attr("disabled", "disabled");
		},
		enableCheckboxes: function () {
			this.$container.find("input[type='checkbox']").removeAttr("disabled");
		},
		disableButtons: function () {
			var $el;
			this.$container.find(".btn").each(function (i, el) {
				$el = pjQ.$(el).prop("disabled", true);
			});
			this.disableCheckboxes.call(this);
		},
		enableButtons: function () {
			this.$container.find(".btn").prop("disabled", false);
			this.enableCheckboxes.call(this);
		},
		getAdultsChildren: function (callback) {
			var self = this;
			var ajax_url = [self.opts.folder, "index.php?controller=pjFront&action=pjActionGetAdultsChildren", "&cid=", self.opts.cid].join("");
			if(self.opts.session_id != '')
			{
				ajax_url = [self.opts.folder, "index.php?controller=pjFront&action=pjActionGetAdultsChildren", "&cid=", self.opts.cid, "&session_id=", self.opts.session_id].join("");
			}
			pjQ.$.get(ajax_url).done(function (data) {
				if (callback !== undefined && typeof callback === "function") {
					callback.call(self, data);
				}
			});
		},
		handleExtras: function (el) {
			var self = this;
			var $el = pjQ.$(el);
			var ajax_url = [self.opts.folder, "index.php?controller=pjFront&action=pjActionHandleExtras", "&cid=", self.opts.cid].join("");
			if(self.opts.session_id != '')
			{
				ajax_url = [self.opts.folder, "index.php?controller=pjFront&action=pjActionHandleExtras", "&cid=", self.opts.cid, "&session_id=", self.opts.session_id].join("");
			}
			var jqXhr = pjQ.$.post(ajax_url, {
				extra_id: $el.val(),
				checked: $el.is(":checked") ? 1 : 0
			});
			
			return jqXhr;
		},
		loadSearch: function () {
			var self = this;
			var ajax_url = [self.opts.folder, "index.php?controller=pjFrontPublic&action=pjActionSearch"].join("");
			if(self.opts.session_id != '')
			{
				ajax_url = [self.opts.folder, "index.php?controller=pjFrontPublic&action=pjActionSearch", "&session_id=", self.opts.session_id].join("");
			}
			pjQ.$.get(ajax_url, {
				"cid": this.opts.cid,
				"locale": this.opts.locale,
				"hide": this.opts.hide,
				"theme": this.opts.theme
			}).done(function (data) {
				self.$container.html(data);
				
				self.scrollTop.call(self);
				
				if (validate) {
					self.$container.find("form").validate({
						rules: {
							adults: {
								required: true,
								digits: true
							},
							date_from: "required",
							date_to: "required"
						},
						submitHandler: function (form) {
							self.disableButtons.call(self);
							var $form = pjQ.$(form);
							hashBang(["#!/Rooms/date_from:", $form.find("input[name='date_from']").val(), "/date_to:", $form.find("input[name='date_to']").val(), "/guests:", $form.find("select[name='guests']").val()].join(""));
							return false;
						}
					});
				}
				if(pjQ.$('#pjHbCalendarLocale').length > 0)
				{
					moment.updateLocale('en', {
						week: { dow: self.opts.week_start },
						months : pjQ.$('#pjHbCalendarLocale').data('months').split("_"),
				        weekdaysMin : pjQ.$('#pjHbCalendarLocale').data('days').split("_")
					});
					
					if(pjQ.$('.pjHbCalendarFrom').length > 0)
					{
						var currentDate = new Date();
						pjQ.$('.pjHbCalendarFrom').datetimepicker({
							format: self.opts.momentDateFormat.toUpperCase(),
							locale: moment.locale('en'),
							allowInputToggle: true,
							minDate: new Date(currentDate.getFullYear(), currentDate.getMonth(), currentDate.getDate()),
							ignoreReadonly: true
						});
						pjQ.$('.pjHbCalendarFrom').on('dp.change', function (e) {
							
							var toDate = new Date(e.date);
							var min_date = e.date;
							toDate.setDate(toDate.getDate());
							if(self.opts.price_based_on == 'nights')
							{
								toDate.setDate(toDate.getDate() + 1);
								min_date = new Date(toDate.getFullYear(), toDate.getMonth(), toDate.getDate());
							}
							var momentDate = new moment(toDate);
							pjQ.$('.pjHbCalendarTo').datetimepicker().children('input').val(momentDate.format(self.opts.momentDateFormat.toUpperCase()));
							pjQ.$('.pjHbCalendarTo').data("DateTimePicker").minDate(min_date);
						});
					}
					if(pjQ.$('.pjHbCalendarTo').length > 0)
					{
						var year = parseInt(pjQ.$('.pjHbCalendarTo').eq(0).attr('data-year'),10),
							month = parseInt(pjQ.$('.pjHbCalendarTo').eq(0).attr('data-month'),10),
							day = parseInt(pjQ.$('.pjHbCalendarTo').eq(0).attr('data-day'),10);
						if(self.opts.price_based_on == 'nights')
						{
							day = day + 1;
						}
						var fromDate = new Date(year, month - 1, day);
						pjQ.$('.pjHbCalendarTo').datetimepicker({
							format: self.opts.momentDateFormat.toUpperCase(),
							locale: moment.locale('en'),
							allowInputToggle: true,
							ignoreReadonly: true,
							useCurrent: false,
							minDate: new Date(fromDate.getFullYear(), fromDate.getMonth(), fromDate.getDate())
						});
					}
				}
			}).fail(function () {
				self.enableButtons.call(self);
			});
		},
		loadRooms: function () {
			
			var self = this;
			var ajax_url = [self.opts.folder, "index.php?controller=pjFrontPublic&action=pjActionRooms"].join("");
			if(self.opts.session_id != '')
			{
				ajax_url = [self.opts.folder, "index.php?controller=pjFrontPublic&action=pjActionRooms", "&session_id=", self.opts.session_id].join("");
			}
			pjQ.$.get(ajax_url, {
				"cid": this.opts.cid,
				"locale": this.opts.locale,
				"hide": this.opts.hide,
				"theme": this.opts.theme,
				"date_from": this.date_from,
				"date_to": this.date_to,
				"guests": this.guests
			}).done(function (data) {
				self.$container.html(data);
				
				self.scrollTop.call(self);
				
				if (fancybox) {
					pjQ.$(".hbSelectorThumb").fancybox({
						openEffect	: 'none',
						closeEffect	: 'none'
					});
				}
				
				self.getAdultsChildren.call(self, function (resp) {
					if (resp.adults > 0 || resp.children > 0) {
						self.$container
							.find(".hbSelectorAccommodate").html(resp.text).show()
							.end()
							.find(".hbSelectorExtras").show();
					} else {
						self.$container
							.find(".hbSelectorAccommodate").hide().html("")
							.end()
							.find(".hbSelectorExtras").hide();
					}
				});
			}).fail(function () {
				self.enableButtons.call(self);
			});
		},
		scrollTop: function () {
			if (this.opts.scrollTop && this.opts.scrollCounter > 0) {
				this.$body.animate({
					scrollTop: this.$container.offset().top - this.opts.scrollOffset
				}, this.opts.scrollSpeed);
			}
			this.opts.scrollCounter += 1;
		},
		bindValidationVoucher: function (callback) {
			var self = this;
			if (validate) {
				self.$container.find(".hbSelectorVoucherForm").on("keyup.hb", 'input[name="code"]', function (e) {
					if ( pjQ.$(this).val().length === 0 ) {
						pjQ.$(".hbSelectorVoucherError").hide();
					}
				}).validate({
					rules: {
						"code": "required"
					},
					onclick: false,
					onfocusout: false,
					onkeyup: false,
					submitHandler: function (form) {
						self.disableButtons.call(self);
						var ajax_url = [self.opts.folder, "index.php?controller=pjFront&action=pjActionApplyCode", "&cid=", self.opts.cid].join("");
						if(self.opts.session_id != '')
						{
							ajax_url = [self.opts.folder, "index.php?controller=pjFront&action=pjActionApplyCode", "&cid=", self.opts.cid, "&session_id=", self.opts.session_id].join("");
						}
						pjQ.$.post(ajax_url, pjQ.$(form).serialize()).done(function (data) {
							if (typeof callback === "function") {
								callback.call(self, form, data);
							}
						}).fail(function () {
							self.enableButtons.call(self);
						});
						return false;
					}
				});
			}
		},
		loadExtras: function () {
			
			var self = this;
			var ajax_url = [self.opts.folder, "index.php?controller=pjFrontPublic&action=pjActionExtras"].join("");
			if(self.opts.session_id != '')
			{
				ajax_url = [self.opts.folder, "index.php?controller=pjFrontPublic&action=pjActionExtras", "&session_id=", self.opts.session_id].join("");
			}
			pjQ.$.get(ajax_url, {
				"cid": this.opts.cid,
				"locale": this.opts.locale,
				"hide": this.opts.hide,
				"theme": this.opts.theme
			}).done(function (data) {
				self.$container.html(data);
				if(self.notScroll == false)
				{
					self.scrollTop.call(self);
				}else{
					self.notScroll = false;
				}
				self.bindValidationVoucher.call(self, function (form, data) {
					if (data.status == "OK") {
						self.loadExtras.call(self);
					} else if (data.status == "ERR") {
						pjQ.$(form).find(".hbSelectorVoucherError").show();
						self.enableButtons.call(self);
					}
				});
			}).fail(function () {
				self.enableButtons.call(self);
			});
		},
		loadCheckout: function () {
			
			var self = this;
			var ajax_url = [self.opts.folder, "index.php?controller=pjFrontPublic&action=pjActionCheckout"].join("");
			if(self.opts.session_id != '')
			{
				ajax_url = [self.opts.folder, "index.php?controller=pjFrontPublic&action=pjActionCheckout", "&session_id=", self.opts.session_id].join("");
			}
			pjQ.$.get(ajax_url, {
				"cid": this.opts.cid,
				"locale": this.opts.locale,
				"hide": this.opts.hide,
				"theme": this.opts.theme
			}).done(function (data) {
				self.$container.html(data);
				
				self.scrollTop.call(self);
				var captcha_url = self.opts.folder + "index.php?controller=pjFront&action=pjActionCheckCaptcha";
				if(self.opts.session_id != '')
				{
					captcha_url += "&session_id=" + self.opts.session_id
				}
				if (validate) {
					self.$container.find("form.hbSelectorFormCheckout").validate({
						rules: {
							cc_num: {
								creditcard: true
							},
							captcha: {
								required: true,
								minlength: 6,
								maxlength: 6,
								remote: captcha_url
							}
						},
						onkeyup: false,
						submitHandler: function (form) {
							self.disableButtons.call(self);
							var post_url = [self.opts.folder, "index.php?controller=pjFrontPublic&action=pjActionCheckout", "&cid=", self.opts.cid, "&locale=", self.opts.locale,"&hide=", self.opts.hide,"&theme=", self.opts.theme,].join("");
							if(self.opts.session_id != '')
							{
								post_url = [self.opts.folder, "index.php?controller=pjFrontPublic&action=pjActionCheckout", "&cid=", self.opts.cid, "&locale=", self.opts.locale,"&hide=", self.opts.hide,"&theme=", self.opts.theme, "&session_id=", self.opts.session_id].join("");
							}
							pjQ.$.post(post_url, pjQ.$(form).serialize()).done(function (data) {
								if (data.status == "OK") {
									hashBang("#!/Preview");
								}else{
									pjQ.$('#pjHbModalWrongCaptcha').find('.text-danger').html(data.text);
									pjQ.$('#pjHbModalWrongCaptcha').modal('show');
									self.enableButtons.call(self);
								}
							}).fail(function () {
								self.enableButtons.call(self);
							});
							return false;
						}
					});
				}
			}).fail(function () {
				self.enableButtons.call(self);
			});
		},
		loadPreview: function () {
			
			var self = this;
			var ajax_url = [self.opts.folder, "index.php?controller=pjFrontPublic&action=pjActionPreview"].join("");
			if(self.opts.session_id != '')
			{
				ajax_url = [self.opts.folder, "index.php?controller=pjFrontPublic&action=pjActionPreview", "&session_id=", self.opts.session_id].join("");
			}
			pjQ.$.get(ajax_url, {
				"cid": this.opts.cid,
				"locale": this.opts.locale,
				"hide": this.opts.hide,
				"theme": this.opts.theme
			}).done(function (data) {
				self.$container.html(data);
				
				self.scrollTop.call(self);
				
				if (validate) {
					self.$container.find("form").validate({
						submitHandler: function (form) {
							self.disableButtons.call(self);
							var post_url = [self.opts.folder, "index.php?controller=pjFront&action=pjActionProcessOrder", "&cid=", self.opts.cid].join("");
							if(self.opts.session_id != '')
							{
								post_url = [self.opts.folder, "index.php?controller=pjFront&action=pjActionProcessOrder", "&cid=", self.opts.cid, "&session_id=", self.opts.session_id].join("");
							}
							pjQ.$.post(post_url, pjQ.$(form).serialize()).done(function (data) {
								if (data.status == "OK") {
									hashBang(["#!/Booking/", data.invoice_uuid].join(""));
								} else if (data.status == "ERR") {
									self.$container.find(".hbSelectorError").html(data.text).show();
									self.enableButtons.call(self);
								}
							}).fail(function () {
								self.enableButtons.call(self);
							});
							return false;
						}
					});
				}
			}).fail(function () {
				self.enableButtons.call(self);
			});
		},
		loadBooking: function () {
			
			var self = this;
			var ajax_url = [self.opts.folder, "index.php?controller=pjFrontPublic&action=pjActionBooking"].join("");
			if(self.opts.session_id != '')
			{
				ajax_url = [self.opts.folder, "index.php?controller=pjFrontPublic&action=pjActionBooking", "&session_id=", self.opts.session_id].join("");
			}
			pjQ.$.get(ajax_url, {
				"cid": this.opts.cid,
				"locale": this.opts.locale,
				"hide": this.opts.hide,
				"theme": this.opts.theme,
				"uuid": this.uuid
			}).done(function (data) {
				self.$container.html(data);
				
				self.scrollTop.call(self);
				
				var $paypal = self.$container.find("form[name='hbPaypal']"),
					$autorize = self.$container.find("form[name='hbAuthorize']");
				if ($paypal.length) {
					$paypal.trigger('submit');
				} else if ($autorize.length) {
					$autorize.trigger('submit');
				}
				
			}).fail(function () {
				self.enableButtons.call(self);
			});
		}
	};
	
	// expose
	window.HotelBooking = HotelBooking;
})(window);