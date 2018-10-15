var jQuery_1_8_2 = jQuery_1_8_2 || $.noConflict();
(function ($, undefined) {
	$(function () {
		"use strict";
		$(".pj-table tbody tr").hover(
			function () {
				$(this).addClass("pj-table-row-hover");
			}, 
			function () {
				$(this).removeClass("pj-table-row-hover");
			}
		);
		$(".pj-button").hover(
			function () {
				$(this).addClass("pj-button-hover");
			}, 
			function () {
				$(this).removeClass("pj-button-hover");
			}
		);
		$(".pj-checkbox").hover(
				function () {
					$(this).addClass("pj-checkbox-hover");
				}, 
				function () {
					$(this).removeClass("pj-checkbox-hover");
				}
			);
		$("#content").on("click", ".notice-close", function (e) {
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			$(this).closest(".notice-box").fadeOut();
			return false;
		}).on("change", "select[name='ch_room_id']", function () {
			var $this = $(this),
				room_id = $this.find("option:selected").val();
			if (room_id.length > 0) {
				window.location.href = $this.data("url") + room_id;
			}
		});
	});
})(jQuery_1_8_2);