
START TRANSACTION;

INSERT INTO `fields` VALUES (NULL, 'error_titles_ARRAY_ABK16', 'arrays', 'error_titles_ARRAY_ABK16', 'script', '2015-04-01 12:02:51');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Bookings', 'script');



INSERT INTO `fields` VALUES (NULL, 'error_bodies_ARRAY_ABK16', 'arrays', 'error_bodies_ARRAY_ABK16', 'script', '2015-04-01 12:05:01');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Below you can find out all of your bookings and their status. Under the Add Booking tab you can add a booking. To Edit a booking just click on the edit icon next to it.', 'script');



INSERT INTO `fields` VALUES (NULL, 'install_link', 'backend', 'Install / Link', 'script', '2015-04-01 12:08:29');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Link', 'script');



INSERT INTO `fields` VALUES (NULL, 'install_example_tab_1', 'backend', 'Install / Example Tab 1', 'script', '2015-04-01 12:10:06');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'JavaScript', 'script');



INSERT INTO `fields` VALUES (NULL, 'install_example_tab_2', 'backend', 'Install / Example Tab 2', 'script', '2015-04-01 12:10:20');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'URL', 'script');



INSERT INTO `fields` VALUES (NULL, 'install_example_tab_3', 'backend', 'Install / Example Tab 3', 'script', '2015-04-01 12:10:29');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Link', 'script');



COMMIT;