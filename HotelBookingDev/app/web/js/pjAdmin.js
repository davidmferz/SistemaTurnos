var jQuery_1_8_2 = jQuery_1_8_2 || $.noConflict();
(function ($, undefined) {
	$(function () {
		"use strict";
		var $frmLoginAdmin = $("#frmLoginAdmin"),
			$frmForgotAdmin = $("#frmForgotAdmin"),
			$frmUpdateProfile = $("#frmUpdateProfile"),
			dialog = ($.fn.dialog !== undefined),
			tabs = ($.fn.tabs !== undefined),
			validate = ($.fn.validate !== undefined);
		
		if ($frmLoginAdmin.length > 0 && validate) {
			$frmLoginAdmin.validate({
				rules: {
					login_email: {
						required: true,
						email: true
					},
					login_password: "required"
				},
				errorPlacement: function (error, element) {
					error.insertAfter(element.parent());
				},
				onkeyup: false,
				errorClass: "err",
				wrapper: "em"
			});
		}
		
		if ($frmForgotAdmin.length > 0 && validate) {
			$frmForgotAdmin.validate({
				rules: {
					forgot_email: {
						required: true,
						email: true
					}
				},
				errorPlacement: function (error, element) {
					error.insertAfter(element.parent());
				},
				onkeyup: false,
				errorClass: "err",
				wrapper: "em"
			});
		}
		
		if ($frmUpdateProfile.length > 0 && validate) {
			$frmUpdateProfile.validate({
				rules: {
					"email": {
						required: true,
						email: true,
						remote: "index.php?controller=pjAdmin&action=pjActionProfileEmail"
					},
					"password": "required",
					"name": "required"
				},
				messages: {
					"email": {
						remote: myLabel.email_taken
					}
				},
				errorPlacement: function (error, element) {
					error.insertAfter(element.parent());
				},
				onkeyup: false,
				errorClass: "err",
				wrapper: "em"
			});
		}
		
		$("#content").on("click", ".welcome-skip", function (e) {
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			if ($dialogWelcomeDone.length > 0 && dialog) {
				$dialogWelcomeDone.data("index", $(this).data("index")).dialog("open");
			}
			return false;
		}).on("click", ".welcome-switch", function (e) {
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			if ($dialogWelcomeSwitch.length > 0 && dialog) {
				$dialogWelcomeSwitch.dialog("open");
			}
			return false;
		});
		
		if (tabs) {
			$("#bookings").tabs();
		}
	});
})(jQuery_1_8_2);