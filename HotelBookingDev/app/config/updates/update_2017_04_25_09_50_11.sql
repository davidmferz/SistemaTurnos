
START TRANSACTION;

INSERT INTO `fields` VALUES (NULL, 'booking_empty_msg', 'backend', 'Bookings / Empty', 'script', '2017-04-25 09:40:22');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'There are no bookings set yet.', 'script');

INSERT INTO `fields` VALUES (NULL, 'rooms_empty_msg', 'backend', 'Rooms / Empty', 'script', '2017-04-25 09:41:13');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'There are no room types set yet.', 'script');

INSERT INTO `fields` VALUES (NULL, 'limit_empty_msg', 'backend', 'Limits / Empty', 'script', '2017-04-25 09:42:01');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'There are no booking limits to any of your room types set yet.', 'script');

INSERT INTO `fields` VALUES (NULL, 'extra_empty_msg', 'backend', 'Extras / Empty', 'script', '2017-04-25 09:42:34');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'There are no extras set yet.', 'script');

INSERT INTO `fields` VALUES (NULL, 'discount_packages_empty_msg', 'backend', 'Discounts / Packages Empty', 'script', '2017-04-25 09:43:28');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'There are no discount packages to any of your room types set yet.', 'script');

INSERT INTO `fields` VALUES (NULL, 'discount_code_empty_msg', 'backend', 'Discounts / Promo code (Empty)', 'script', '2017-04-25 09:44:44');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'There are no promo codes to any of your room types set yet.', 'script');

INSERT INTO `fields` VALUES (NULL, 'restriction_empty_msg', 'backend', 'Restrictions / Empty', 'script', '2017-04-25 09:45:19');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'There are no unavailable rooms set yet.', 'script');

COMMIT;