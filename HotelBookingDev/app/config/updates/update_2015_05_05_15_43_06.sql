
START TRANSACTION;

CREATE TABLE IF NOT EXISTS `restrictions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `date_from` date DEFAULT NULL,
  `date_to` date DEFAULT NULL,
  `restriction_type` enum('unavailable','web','external') DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `restrictions_rooms` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `restriction_id` int(10) unsigned DEFAULT NULL,
  `room_number_id` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `restriction_room` (`restriction_id`,`room_number_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;


INSERT INTO `fields` VALUES (NULL, 'menuRestrictions', 'backend', 'Menu Unavailable', 'script', '2015-05-05 11:56:09');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Unavailable', 'script');



INSERT INTO `fields` VALUES (NULL, 'restriction_rooms', 'backend', 'Restrictions / Room(s)', 'script', '2015-05-05 12:05:41');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Room(s)', 'script');



INSERT INTO `fields` VALUES (NULL, 'restriction_date_from', 'backend', 'Restrictions / Date from', 'script', '2015-05-05 12:06:01');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Date from', 'script');



INSERT INTO `fields` VALUES (NULL, 'restriction_date_to', 'backend', 'Restrictions / Date to', 'script', '2015-05-05 12:06:11');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Date to', 'script');



INSERT INTO `fields` VALUES (NULL, 'restriction_type', 'backend', 'Restrictions / Type', 'script', '2015-05-05 12:06:23');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Type', 'script');



INSERT INTO `fields` VALUES (NULL, 'restriction_types_ARRAY_unavailable', 'arrays', 'Restrictions / Types (Unavailable)', 'script', '2015-05-05 12:06:52');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Unavailable', 'script');



INSERT INTO `fields` VALUES (NULL, 'restriction_types_ARRAY_web', 'arrays', 'Restrictions / Types (Stop from Web)', 'script', '2015-05-05 12:07:12');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Stop from Web', 'script');



INSERT INTO `fields` VALUES (NULL, 'restriction_types_ARRAY_external', 'arrays', 'Restrictions / Types (External Booking)', 'script', '2015-05-05 12:07:32');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'External Booking', 'script');



INSERT INTO `fields` VALUES (NULL, 'restriction_add', 'backend', 'Restrictions / + Add Unavailable', 'script', '2015-05-05 12:10:50');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', '+ Add', 'script');



INSERT INTO `fields` VALUES (NULL, 'restriction_empty', 'backend', 'Restrictions / Empty', 'script', '2015-05-05 12:11:40');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'There is no unavailable rooms set yet.', 'script');



INSERT INTO `fields` VALUES (NULL, 'restriction_update', 'backend', 'Restrictions / Update Unavailable', 'script', '2015-05-05 13:21:05');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Update Unavailable', 'script');



INSERT INTO `fields` VALUES (NULL, 'error_titles_ARRAY_ART10', 'arrays', 'error_titles_ARRAY_ART10', 'script', '2015-05-05 13:37:14');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Set unavailable rooms', 'script');



INSERT INTO `fields` VALUES (NULL, 'error_bodies_ARRAY_ART10', 'arrays', 'error_bodies_ARRAY_ART10', 'script', '2015-05-05 13:38:33');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Some text goes here.', 'script');



INSERT INTO `fields` VALUES (NULL, 'booking_legend_unavailable', 'backend', 'Bookings / Legend: Unavailable', 'script', '2015-05-05 13:41:07');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Unavailable', 'script');



INSERT INTO `fields` VALUES (NULL, 'booking_restrictions_ARRAY_unavailable', 'arrays', 'Bookings / Restrictions (Unavailable)', 'script', '2015-05-05 15:17:48');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Unavailable', 'script');



INSERT INTO `fields` VALUES (NULL, 'booking_restrictions_ARRAY_web', 'arrays', 'Bookings / Restrictions (Stop from Web)', 'script', '2015-05-05 15:18:14');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Stop from Web', 'script');



INSERT INTO `fields` VALUES (NULL, 'booking_restrictions_ARRAY_external', 'arrays', 'Bookings / Restrictions (External Booking)', 'script', '2015-05-05 15:18:32');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'External Booking', 'script');



INSERT INTO `fields` VALUES (NULL, 'calendar_out_of_order', 'backend', 'Calendar / Out of order', 'script', '2015-05-05 15:28:30');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Out of order', 'script');



COMMIT;