
START TRANSACTION;

UPDATE `multi_lang` SET `content` = 'Booking cancelled' WHERE `foreign_id` = 1 AND `model` = "pjCalendar" AND `field` = "cancel_subject_admin";

UPDATE `multi_lang` SET `content` = '<p>Booking has been cancelled.</p>\r\n<p>ID: <strong>{BookingID}</strong></p>' WHERE `foreign_id` = 1 AND `model` = "pjCalendar" AND `field` = "cancel_tokens_admin";

UPDATE `multi_lang` SET `content` = 'New booking received {BookingID}' WHERE `foreign_id` = 1 AND `model` = "pjCalendar" AND `field` = "confirm_sms_admin";

UPDATE `multi_lang` SET `content` = 'New booking received' WHERE `foreign_id` = 1 AND `model` = "pjCalendar" AND `field` = "confirm_subject_admin";

UPDATE `multi_lang` SET `content` = 'Booking confirmation' WHERE `foreign_id` = 1 AND `model` = "pjCalendar" AND `field` = "confirm_subject_client";

UPDATE `multi_lang` SET `content` = '<p>New booking has been made</p>\r\n<p>&nbsp;</p>\r\n<p>ID: {BookingID}</p>\r\n<p>Start date: <strong>{DateFrom}</strong>, End date: <strong>{DateTo}</strong></p>\r\n<p>Arrival time: <strong>{ArrivalTime}</strong></p>\r\n<p>Room and room extras {Rooms} {Extras}</p>\r\n<p>&nbsp;</p>\r\n<p><strong>Personal details:</strong></p>\r\n<p>Name: {FirstName} {LastName}</p>\r\n<p>Phone: {Phone}, Email: {Email}</p>\r\n<p>&nbsp;</p>\r\n<p>This is the price for your booking is Room(s) price: {RoomPrice}</p>\r\n<p>Room extras: <em>{ExtraPrice}</em></p>\r\n<p>Discount: <em>{Discount}</em></p>\r\n<p>Tax: <em>{Tax}</em></p>\r\n<p>Total: <strong>{Total}</strong></p>\r\n<p>Security deposit: <strong>{SecurityDeposit}</strong></p>\r\n<p>Deposit required to confirm your booking: <strong>{Deposit}</strong></p>\r\n<p>Additional notes: {Notes}</p>' WHERE `foreign_id` = 1 AND `model` = "pjCalendar" AND `field` = "confirm_tokens_admin";

UPDATE `multi_lang` SET `content` = '<p>Thank you for your booking.</p>\r\n<p>&nbsp;</p>\r\n<p>ID: {BookingID}</p>\r\n<p>Start date: <strong>{DateFrom}</strong>, End date: <strong>{DateTo}</strong></p>\r\n<p>Arrival time: <strong>{ArrivalTime}</strong></p>\r\n<p>Room and room extras {Rooms} {Extras}</p>\r\n<p>&nbsp;</p>\r\n<p><strong>Personal details:</strong></p>\r\n<p>Name: {FirstName} {LastName}</p>\r\n<p>Phone: {Phone}, Email: {Email}</p>\r\n<p>&nbsp;</p>\r\n<p>This is the price for your booking is Room(s) price: {RoomPrice}</p>\r\n<p>Room extras: <em>{ExtraPrice}</em></p>\r\n<p>Discount: <em>{Discount}</em></p>\r\n<p>Tax: <em>{Tax}</em></p>\r\n<p>Total: <strong>{Total}</strong></p>\r\n<p>Security deposit: <strong>{SecurityDeposit}</strong></p>\r\n<p>Deposit required to confirm your booking: <strong>{Deposit}</strong></p>\r\n<p>Additional notes: {Notes}</p>\r\n<p>&nbsp;</p>\r\n<p>&nbsp;</p>\r\n<p>Thank you,</p>\r\n<p>Hotel Management.</p>' WHERE `foreign_id` = 1 AND `model` = "pjCalendar" AND `field` = "confirm_tokens_client";

UPDATE `multi_lang` SET `content` = 'New payment received {BookingID}' WHERE `foreign_id` = 1 AND `model` = "pjCalendar" AND `field` = "payment_sms_admin";

UPDATE `multi_lang` SET `content` = 'New payment received' WHERE `foreign_id` = 1 AND `model` = "pjCalendar" AND `field` = "payment_subject_admin";

UPDATE `multi_lang` SET `content` = 'Payment received' WHERE `foreign_id` = 1 AND `model` = "pjCalendar" AND `field` = "payment_subject_client";

UPDATE `multi_lang` SET `content` = '<p>Booking deposit has been paid.</p>\r\n<p>ID: <strong>{BookingID}</strong></p>' WHERE `foreign_id` = 1 AND `model` = "pjCalendar" AND `field` = "payment_tokens_admin";

UPDATE `multi_lang` SET `content` = '<p>We''ve received the payment for your booking and it is now confirmed.</p>\r\n<p>&nbsp;</p>\r\n<p>ID: <strong>{BookingID}</strong></p>\r\n<p>&nbsp;</p>\r\n<p>Thank you,</p>\r\n<p>Hotel Management.</p>' WHERE `foreign_id` = 1 AND `model` = "pjCalendar" AND `field` = "payment_tokens_client";

UPDATE `multi_lang` SET `content` = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin bibendum euismod odio non cursus. Vivamus cursus turpis neque. Integer semper varius eros eu congue. Quisque eu tortor lectus. Vivamus tristique sem a tellus dictum posuere. Curabitur ultrices pharetra est, facilisis tempus nibh suscipit id. Nulla dignissim placerat ipsum. Etiam mattis varius purus, eu posuere sapien condimentum eu. Etiam quam odio, suscipit eget egestas id, ultrices a nisi. Vivamus facilisis sem nec purus dapibus, vel molestie massa hendrerit. Cras accumsan gravida quam, sit amet ornare erat condimentum sed. Fusce adipiscing imperdiet imperdiet. Praesent commodo consectetur suscipit.\r\n\r\nAenean nec leo aliquet dui tristique porta eget quis lectus. Aliquam id pharetra felis. Vestibulum suscipit tellus elit, vitae imperdiet nulla ornare ac. Integer non nibh vitae justo aliquet faucibus. Suspendisse pulvinar tempor dui, porttitor elementum turpis adipiscing non. Nulla massa tellus, suscipit sit amet velit ac, viverra lobortis est. Sed sit amet tristique diam. Duis vel nisi vel dolor euismod feugiat. Proin porttitor eros mi, sed ultrices eros egestas sit amet. Curabitur lacinia euismod nisi et malesuada.\r\n\r\nAliquam scelerisque iaculis nunc nec dictum. Aliquam quis mollis purus. Donec vitae urna mi. Donec quis molestie purus, et condimentum erat. Suspendisse sed enim semper, pretium lorem ut, aliquam metus. Donec aliquam dignissim volutpat. Aenean purus arcu, fermentum sit amet nunc id, venenatis sodales sapien. Nunc non tellus iaculis massa suscipit ullamcorper faucibus a odio. Integer bibendum, nisl vel ullamcorper semper, augue risus lobortis quam, et semper eros sem facilisis lectus. Quisque convallis euismod porta. Duis interdum, nibh vel faucibus varius, odio nulla molestie leo, sed ornare massa odio non lectus. Etiam ornare mi eu sapien scelerisque tempus.' WHERE `foreign_id` = 1 AND `model` = "pjCalendar" AND `field` = "terms_body";

UPDATE `multi_lang` SET `content` = '' WHERE `foreign_id` = 1 AND `model` = "pjCalendar" AND `field` = "terms_url";

COMMIT;