var jQuery_1_8_2 = jQuery_1_8_2 || $.noConflict();
(function ($, undefined) {
	$(function () {
		"use strict";
		var $frmCreateRoom = $("#frmCreateRoom"),
			$frmUpdateRoom = $("#frmUpdateRoom"),
			$gallery = $("#gallery"),
			gallery = ($.fn.gallery !== undefined),
			validate = ($.fn.validate !== undefined),
			spinner = ($.fn.spinner !== undefined),
			datagrid = ($.fn.datagrid !== undefined),
			v = {
				rules: {
					room_number: {
						required: function () {
							if ($('#cnt').val() != '') {
								var result = false;
								$('.number-field').each(function(i, ele) {
								    if ($(ele).val() == '') {
								    	result = true;
								    }
								});
								return result;
							} else {
								return false;
							}
						}
					}
				},
				errorPlacement: function (error, element) {
					error.insertAfter(element.parent());
				},
				onkeyup: false,
				errorClass: "err",
				wrapper: "em",
				ignore: ".ignore",
				invalidHandler: function (event, validator) {
				    if (validator.numberOfInvalids()) {
				    	var $icons = $("a.pj-form-langbar-item");
				    	if ($icons.length > 1 && !$icons.first().hasClass("pj-form-langbar-item-active")) {
				    		$icons.first().trigger("click");
				    	}
				    }
				}
			};
		
		if ($frmCreateRoom.length > 0 && validate) {
			$frmCreateRoom.validate(v);
		}
		if ($frmUpdateRoom.length > 0 && validate) {
			$frmUpdateRoom.validate(v);
		}
		
		if (spinner) {
			$("#max_people").spinner({
				min: 1
			});
			$("#adults").spinner({
				min: 1
			});
			$("#children").spinner({
				min: 0
			});
			$("#cnt").spinner({
				min: 0,
				stop: function (event, ui) {
					loadRoomNumber();
				}
			});
		}
		
		$("#content").on("change", "select[name='change_room_id']", function (e) {
			var $this = $(this),
				room_id = $this.find("option:selected").val();
			if (parseInt(room_id, 10) > 0) {
				window.location.href = $this.data("href") + room_id;
			}
		});
		
		function loadRoomNumber() {
			var number_of_rooms = parseInt($('#cnt').val(), 10),
				$hbRoomNumber = $('#hbRoomNumber'),
				$roomNo = $(".room-no"),
				i = 1,
				type = '',
				existing_number = $hbRoomNumber.find('input').length,
				tmp = 1;
			if (number_of_rooms == 0) {
				$roomNo.hide();
				$hbRoomNumber.prev().html($hbRoomNumber.data("enter"));
				$hbRoomNumber.parent().siblings().html('');
			} else {
				$roomNo.show();
				$hbRoomNumber.prev().html($hbRoomNumber.data("note"));
				$hbRoomNumber.parent().siblings().html($hbRoomNumber.data("numbers"));
			}
			if ($('.field-name').val() != '') {
				var str = $('.field-name').val(),
					words = str.split(' ');
				if (words.length > 1) {
					$.each(words, function() {
				        var first_letter = this.substring(0,1);
				        type += first_letter.toUpperCase();
				    });
				} else {
					type += words[0].substring(0,1).toUpperCase();
				}
			}
			if (existing_number == 0) {
				$hbRoomNumber.html("");
			}
			if (existing_number < number_of_rooms && existing_number > 0) {
				tmp = existing_number + 1;
			}
			if (existing_number > number_of_rooms) {
				$('.number-field').each(function(i, ele) {
					var index = parseInt($(ele).attr('data-index'),10)
				    if (index > number_of_rooms) {
				    	$(this).remove();
				    }
				});
			} else {
				if (existing_number != number_of_rooms) {
					for (i = tmp; i <= number_of_rooms; i++) {
						$hbRoomNumber.append('<input type="text" name="number[new_'+i+']" value="'+type+i+'" class="pj-form-field w50 number-field" data-index="'+i+'" />');
					}
				}
			}
		}
		
		if ($gallery.length > 0 && gallery) {
			$gallery.gallery({
				compressUrl: "index.php?controller=pjGallery&action=pjActionCompressGallery&foreign_id=" + myGallery.foreign_id + "&hash=" + myGallery.hash,
				getUrl: "index.php?controller=pjGallery&action=pjActionGetGallery&foreign_id=" + myGallery.foreign_id + "&hash=" + myGallery.hash,
				deleteUrl: "index.php?controller=pjGallery&action=pjActionDeleteGallery",
				emptyUrl: "index.php?controller=pjGallery&action=pjActionEmptyGallery&foreign_id=" + myGallery.foreign_id + "&hash=" + myGallery.hash,
				rebuildUrl: "index.php?controller=pjGallery&action=pjActionRebuildGallery&foreign_id=" + myGallery.foreign_id + "&hash=" + myGallery.hash,
				resizeUrl: "index.php?controller=pjGallery&action=pjActionCrop&model=pjRoom&id={:id}&foreign_id=" + myGallery.foreign_id + "&hash=" + myGallery.hash + ($frmUpdateRoom.length > 0 ? "&query_string=" + encodeURIComponent("controller=pjAdminRooms&action=pjActionUpdate&id=" + myGallery.foreign_id + "&tab=4") : ""),
				rotateUrl: "index.php?controller=pjGallery&action=pjActionRotateGallery",
				sortUrl: "index.php?controller=pjGallery&action=pjActionSortGallery",
				updateUrl: "index.php?controller=pjGallery&action=pjActionUpdateGallery",
				uploadUrl: "index.php?controller=pjGallery&action=pjActionUploadGallery&foreign_id=" + myGallery.foreign_id + "&hash=" + myGallery.hash,
				watermarkUrl: "index.php?controller=pjGallery&action=pjActionWatermarkGallery&foreign_id=" + myGallery.foreign_id + "&hash=" + myGallery.hash
			});
		}
		
		function formatImage (str, obj) {
			return ['<a href="index.php?controller=pjAdminRooms&action=pjActionUpdate&id=', obj.id,'"><img src="', str, '" alt="" style="max-width: 100px"></a>'].join("");
		}
		
		if ($("#grid").length > 0 && datagrid) {
			
			var $grid = $("#grid").datagrid({
				buttons: [{type: "price", url: "index.php?controller=pjPrice&action=pjActionIndex&room_id={:id}"},
				          {type: "edit", url: "index.php?controller=pjAdminRooms&action=pjActionUpdate&id={:id}"},
				          {type: "delete", url: "index.php?controller=pjAdminRooms&action=pjActionDeleteRoom&id={:id}"}
				          ],
				columns: [{text: myLabel.image, type: "text", sortable: false, editable: false, width: 100, renderer: formatImage},
				          {text: myLabel.name, type: "text", sortable: true, editable: true, width: 450, editableWidth: 240},
				          {text: myLabel.adults, type: "spinner", min: 1, sortable: true, editable: true, width:80, editableWidth: 60},
				          {text: myLabel.children, type: "spinner", min: 0, sortable: true, editable: true, width:80, editableWidth: 60},
				          {text: myLabel.cnt, type: "spinner", min: 0, sortable: true, editable: false, width: 100, editableWidth: 60},
				          ],
				dataUrl: "index.php?controller=pjAdminRooms&action=pjActionGetRoom",
				dataType: "json",
				fields: ['image', 'name', 'adults', 'children', 'cnt'],
				paginator: {
					actions: [
					   {text: myLabel.delete_selected, url: "index.php?controller=pjAdminRooms&action=pjActionDeleteRoomBulk", render: true, confirmation: myLabel.delete_confirmation}
					],
					gotoPage: true,
					paginate: true,
					total: true,
					rowCount: true
				},
				saveUrl: "index.php?controller=pjAdminRooms&action=pjActionSaveRoom&id={:id}",
				select: {
					field: "id",
					name: "record[]"
				}
			});
		}
	});
})(jQuery_1_8_2);