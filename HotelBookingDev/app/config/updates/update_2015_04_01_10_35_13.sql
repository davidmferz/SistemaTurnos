
START TRANSACTION;

INSERT INTO `options` (`foreign_id`, `key`, `tab_id`, `value`, `label`, `type`, `order`, `is_visible`, `style`) VALUES
(1, 'o_theme', 1, '0|1|2|3|4|5|6|7|8|9|10::0', 'Default|Theme 1|Theme 2|Theme 3|Theme 4|Theme 5|Theme 6|Theme 7|Theme 8|Theme 9|Theme 10', 'enum', 5, 1, NULL),
(1, 'o_pending_time', 3, '60', NULL, 'int', 12, 1, NULL);

DELETE FROM `options` WHERE `key` = 'o_layout';

INSERT INTO `fields` VALUES (NULL, 'front_extras_text', 'frontend', 'Frontend / Extras text', 'script', '2015-04-01 07:34:56');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Est et excepturi aliquid impedit, reprehenderit soluta obcaecati dolor quasi saepe ducimus tempora perferendis iure totam ratione ipsa eaque cupiditate voluptatum quod, officiis placeat sapiente praesentium delectus. Dolorum quasi voluptate maxime odio ex, molestiae deleniti iusto eos totam ab illo recusandae sint?', 'script');



INSERT INTO `fields` VALUES (NULL, 'booking_client_add', 'backend', 'Bookings / Add client details', 'script', '2015-04-01 08:00:53');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Add client details', 'script');



INSERT INTO `fields` VALUES (NULL, 'error_titles_ARRAY_AO35', 'arrays', 'error_titles_ARRAY_AO35', 'script', '2015-04-01 10:33:58');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Confirmation', 'script');



INSERT INTO `fields` VALUES (NULL, 'error_bodies_ARRAY_AO35', 'arrays', 'error_bodies_ARRAY_AO35', 'script', '2015-04-01 10:34:04');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Email notifications will be sent to people who make a reservation after reservation form is completed or/and payment is made. If you leave subject field blank no email will be sent. You can use tokens in the email messages to personalize them.\r\n<br /><br /><br />\r\n<table width="100%" border="0" cellspacing="0" cellpadding="0">  \r\n<tbody><tr>    \r\n<td width="33%" valign="top">{Title} - customer title;<br />{FirstName} - customer first name;<br />{LastName} - customer last name; <br />{Phone} - customer phone number; <br />{Email} - customer e-mail address; <br />{ArrivalTime} - arrival time; <br />{Notes} - additional notes; <br />{Company} - company; <br />{Address} - address; <br />{City} - city; <br />{State} - state;</td>\r\n<td width="33%" valign="top">{Zip} - zip code; <br />{Country} - country; <br />{BookingID} - Booking ID; <br />{DateFrom} - Booking start date; <br />{DateTo} - Booking end date; <br />{Rooms} - selected rooms; <br />{Extras} - selected room extras;<br />{CCType} - CC type; <br />{CCNum} - CC number; <br />{CCExpMonth} - CC exp.month; <br />{CCExpYear} - CC exp.year;</td>\r\n<td width="33%" valign="top">{CCSec} - CC sec. code; <br />{PaymentMethod} - selected payment method; <br />{Deposit} - Deposit amount; <br />{SecurityDeposit} - Security deposit amount; <br />{Tax} - Tax amount; <br />{Total} - Total amount; <br />{RoomPrice} - Room(s) price; <br />{ExtraPrice} - Rooms extras price; <br />{Discount} - Discount amount; <br />{CancelURL} - Link for booking cancellation;</td>\r\n</tr></tbody></table>', 'script');



COMMIT;