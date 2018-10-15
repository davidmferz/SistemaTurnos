var jQuery_1_8_2 = jQuery_1_8_2 || $.noConflict();
(function ($, undefined) {
	$(function () {
		"use strict";
				
		$("#content").on("focusin", ".datepick", function (e) {
			var minDate, 
				maxDate,
				$this = $(this),
				custom = {},
				o = {
					dateFormat: $this.attr("rev")
				};
			switch ($this.attr("name")) {
				case "from":
					if ($(".datepick[name='to']").val() != '') {
						maxDate = $(".datepick[name='to']").datepicker({
							dateFormat: $this.attr("rev")
						}).datepicker("getDate");
						$(".datepick[name='to']").datepicker("destroy").removeAttr("id");
						if (maxDate !== null) {
							custom.maxDate = maxDate;
						}
					}
					break;
				case "to":
					if ($(".datepick[name='from']").val() != '') {
						minDate = $(".datepick[name='from']").datepicker({
							dateFormat: $this.attr("rev")
						}).datepicker("getDate");
						$(".datepick[name='from']").datepicker("destroy").removeAttr("id");
						if (minDate !== null) {
							custom.minDate = minDate;
						}
					}
					break;
			}
			$this.datepicker($.extend(o, custom));
		}).on("click", ".hb-calendar-icon", function (e) {
			var $dp = $(this).siblings("input[type='text']");
			if ($dp.hasClass("hasDatepicker")) {
				$dp.datepicker("show");
			} else {
				if (!$dp.is('[disabled=disabled]')) {
					$dp.trigger("focusin").datepicker("show");
				}
			}
		});
		
		$(document).on("click", ".pj-form-field-icon-date", function (e) {
			var $dp = $(this).parent().siblings("input[type='text']");
			if ($dp.hasClass("hasDatepicker")) {
				$dp.datepicker("show");
			} else {
				$dp.trigger("focusin").datepicker("show");
			}
		});
	});
})(jQuery_1_8_2);