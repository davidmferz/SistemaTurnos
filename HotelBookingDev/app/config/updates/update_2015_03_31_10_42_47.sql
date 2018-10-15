
START TRANSACTION;

INSERT INTO `fields` VALUES (NULL, 'install_example_title', 'backend', 'Install / Example', 'script', '2015-03-31 07:18:01');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Example', 'script');



INSERT INTO `fields` VALUES (NULL, 'install_method_2_hint', 'backend', 'Install / Method 2 (hint)', 'script', '2015-03-31 07:35:56');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Use the following URL which will open your online hotel booking software.', 'script');



INSERT INTO `fields` VALUES (NULL, 'install_method_3', 'backend', 'Install / Method 3', 'script', '2015-03-31 07:34:10');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Insert a link to your Hotel Booking Software front-end', 'script');



INSERT INTO `fields` VALUES (NULL, 'install_method_3_hint', 'backend', 'Install / Method 3 (Hint)', 'script', '2015-03-31 07:35:27');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Copy and paste the code below into your website content and it will open your online hotel booking software.', 'script');



INSERT INTO `fields` VALUES (NULL, 'install_method_2', 'backend', 'Install / Method 2', 'script', '2015-03-31 07:36:23');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Booking URL', 'script');



INSERT INTO `fields` VALUES (NULL, 'install_method_1', 'backend', 'Install / Method 1', 'script', '2015-03-31 07:36:44');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Embed the software front-end directly into your website', 'script');



INSERT INTO `fields` VALUES (NULL, 'install_method_1_hint', 'backend', 'Install / Method 1 (Hint)', 'script', '2015-03-31 07:39:48');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Copy and paste this code into the web page HTML where you wish the Hotel Booking Software front-end to show.', 'script');



INSERT INTO `fields` VALUES (NULL, 'install_review', 'backend', 'Install / Review', 'script', '2015-03-31 08:13:49');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Review an example', 'script');



INSERT INTO `fields` VALUES (NULL, 'install_review_hint', 'backend', 'Install / Review (Hint)', 'script', '2015-03-31 08:14:25');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'of how to embed the front-end code into your web page', 'script');



INSERT INTO `fields` VALUES (NULL, 'install_request', 'backend', 'Install / Request help', 'script', '2015-03-31 08:15:03');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Request integration help for free', 'script');



INSERT INTO `fields` VALUES (NULL, 'install_request_hint', 'backend', 'Install / Request help (Hint)', 'script', '2015-03-31 08:15:31');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'We''ll integrate the Food Ordering System into your website for free.', 'script');



INSERT INTO `fields` VALUES (NULL, 'install_contact', 'backend', 'Install / Contact Us', 'script', '2015-03-31 08:15:58');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Contact Us', 'script');



INSERT INTO `fields` VALUES (NULL, 'rooms_empty', 'backend', 'Rooms / Empty', 'script', '2015-03-31 09:00:41');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'There is no room types set yet.', 'script');



INSERT INTO `fields` VALUES (NULL, 'extra_empty', 'backend', 'Extras / Empty', 'script', '2015-03-31 09:03:53');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'There is no extras set yet.', 'script');



INSERT INTO `fields` VALUES (NULL, 'booking_empty', 'backend', 'Bookings / Empty', 'script', '2015-03-31 09:09:25');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'There is no bookings set yet.', 'script');



INSERT INTO `fields` VALUES (NULL, 'booking_add_plus', 'backend', 'Bookings / Add booking', 'script', '2015-03-31 09:09:55');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', '+ Add Booking', 'script');



INSERT INTO `fields` VALUES (NULL, 'users_empty', 'backend', 'Users / Empty', 'script', '2015-03-31 09:13:27');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'There is no users set yet.', 'script');



INSERT INTO `fields` VALUES (NULL, 'users_add', 'backend', 'Users / Add user', 'script', '2015-03-31 09:17:04');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', '+ Add User', 'script');



INSERT INTO `fields` VALUES (NULL, 'limit_empty', 'backend', 'Limits / Empty', 'script', '2015-03-31 09:23:08');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'There is no booking limits to any of your room types set yet.', 'script');



INSERT INTO `fields` VALUES (NULL, 'discount_packages_empty', 'backend', 'Discounts / Packages Empty', 'script', '2015-03-31 09:37:40');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'There is no discount packages to any of your room types set yet.', 'script');



INSERT INTO `fields` VALUES (NULL, 'discount_free_empty', 'backend', 'Discounts / Free night (Empty)', 'script', '2015-03-31 09:46:12');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'There is no free night to any of your room types set yet.', 'script');



INSERT INTO `fields` VALUES (NULL, 'discount_code_empty', 'backend', 'Discounts / Promo code (Empty)', 'script', '2015-03-31 09:45:41');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'There is no promo codes to any of your room types set yet.', 'script');



COMMIT;