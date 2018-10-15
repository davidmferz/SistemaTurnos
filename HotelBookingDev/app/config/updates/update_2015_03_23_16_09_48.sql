
START TRANSACTION;

INSERT INTO `fields` VALUES (NULL, 'front_steps_ARRAY_search', 'arrays', 'Frontend / Step 1', 'script', '2015-03-23 12:03:27');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Step 1 - When and Who', 'script');



INSERT INTO `fields` VALUES (NULL, 'front_steps_ARRAY_rooms', 'arrays', 'Frontend / Step 2', 'script', '2015-03-23 12:03:51');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Step 2 - Rooms', 'script');



INSERT INTO `fields` VALUES (NULL, 'front_steps_ARRAY_extras', 'arrays', 'Frontend / Step 3', 'script', '2015-03-23 12:04:22');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Step 3 - Extras', 'script');



INSERT INTO `fields` VALUES (NULL, 'front_steps_ARRAY_checkout', 'arrays', 'Frontend / Step 4', 'script', '2015-03-23 12:04:46');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Step 4 - Checkout', 'script');



INSERT INTO `fields` VALUES (NULL, 'front_steps_ARRAY_preview', 'arrays', 'Frontend / Step 5', 'script', '2015-03-23 12:05:02');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Step 5 - Preview', 'script');



INSERT INTO `fields` VALUES (NULL, 'front_steps_ARRAY_booking', 'arrays', 'Frontend / Step 6', 'script', '2015-03-23 12:05:23');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Step 6 - Finish', 'script');



INSERT INTO `fields` VALUES (NULL, 'front_booking_contact', 'backend', 'Frontend / Booking contact', 'script', '2015-03-23 13:03:40');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'If you have any questions, please contact us.', 'script');



INSERT INTO `fields` VALUES (NULL, 'front_booking_success', 'backend', 'Frontend / Booking success', 'script', '2015-03-23 13:04:50');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Your reservation has been successfully sent to the administrator.', 'script');



INSERT INTO `fields` VALUES (NULL, 'front_booking_uid', 'backend', 'Frontend / Booking success', 'script', '2015-03-23 13:05:48');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Your reservation ID is %s.', 'script');



INSERT INTO `fields` VALUES (NULL, 'opt_o_theme', 'backend', 'Options / Theme', 'script', '2015-03-23 13:41:35');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Theme', 'script');



INSERT INTO `fields` VALUES (NULL, 'install_theme', 'backend', 'Install / Theme', 'script', '2015-03-23 14:07:50');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Theme', 'script');



INSERT INTO `fields` VALUES (NULL, 'install_select_theme', 'backend', 'Install / Select theme', 'script', '2015-03-23 14:08:01');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Select theme', 'script');



INSERT INTO `fields` VALUES (NULL, 'extra_add', 'backend', 'Extras / Add Extra', 'script', '2015-03-23 14:35:20');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', '+ Add Extra', 'script');



INSERT INTO `fields` VALUES (NULL, 'rooms_image', 'backend', 'Rooms / Room image', 'script', '2015-03-23 14:51:01');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Picture', 'script');



INSERT INTO `fields` VALUES (NULL, 'room_add', 'backend', 'Rooms / Add room', 'script', '2015-03-23 15:00:43');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', '+ Add Room', 'script');



COMMIT;