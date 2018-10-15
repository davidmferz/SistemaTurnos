
START TRANSACTION;

INSERT INTO `fields` VALUES (NULL, 'booking_arrival_date', 'backend', 'Bookings / Arrival date', 'script', '2015-03-25 08:16:49');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Arrival date', 'script');



INSERT INTO `fields` VALUES (NULL, 'booking_departure_date', 'backend', 'Bookings / Departure date', 'script', '2015-03-25 08:17:12');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Departure date', 'script');



INSERT INTO `fields` VALUES (NULL, 'booking_search_arrival_departure', 'backend', 'Booking Search / Arrival / Departure', 'script', '2015-03-25 08:19:11');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Arrival / Departure', 'script');



INSERT INTO `fields` VALUES (NULL, 'error_titles_ARRAY_AO40', 'arrays', 'error_titles_ARRAY_AO40', 'script', '2015-03-24 09:25:54');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Preview front end', 'script');



INSERT INTO `fields` VALUES (NULL, 'error_bodies_ARRAY_AO40', 'arrays', 'error_bodies_ARRAY_AO40', 'script', '2015-03-25 08:04:55');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Click on the thumbnail below to see available color themes. To put the booking engine on your website go to <a href="index.php?controller=pjAdminOptions&action=pjActionInstall">Install</a> page.', 'script');



INSERT INTO `fields` VALUES (NULL, 'error_bodies_ARRAY_ABK22', 'arrays', 'error_bodies_ARRAY_ABK22', 'script', '2015-03-25 08:13:27');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'You need to select Arrival and Departure dates first.', 'script');



INSERT INTO `fields` VALUES (NULL, 'error_titles_ARRAY_ABK22', 'arrays', 'error_titles_ARRAY_ABK22', 'script', '2015-03-25 08:14:06');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'What''s first?', 'script');



INSERT INTO `fields` VALUES (NULL, 'error_titles_ARRAY_ART01', 'arrays', 'error_titles_ARRAY_ART01', 'script', '2015-03-25 08:21:39');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Generate a report', 'script');



INSERT INTO `fields` VALUES (NULL, 'error_bodies_ARRAY_ART01', 'arrays', 'error_bodies_ARRAY_ART01', 'script', '2015-03-25 08:21:51');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Select a date range and click on the GENERATE button to view information about all bookings made for the selected period.', 'script');



INSERT INTO `fields` VALUES (NULL, 'booking_extras', 'backend', 'Bookings / Extras', 'script', '2015-03-25 08:23:54');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Extras', 'script');



INSERT INTO `fields` VALUES (NULL, 'booking_jump_to', 'backend', 'Bookings / Jump to', 'script', '2015-03-25 09:02:28');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Jump to', 'script');



INSERT INTO `fields` VALUES (NULL, 'booking_filter', 'backend', 'Bookings / Filter', 'script', '2015-03-25 09:02:44');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Filter', 'script');



INSERT INTO `fields` VALUES (NULL, 'btnPrintCalendar', 'backend', 'Button / Print Calendar', 'script', '2015-03-25 09:04:32');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Print Calendar', 'script');



COMMIT;