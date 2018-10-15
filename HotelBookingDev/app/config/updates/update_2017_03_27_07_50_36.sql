
START TRANSACTION;

ALTER TABLE `rooms` ADD COLUMN `max_people` smallint(5) unsigned DEFAULT NULL AFTER `children`;

INSERT INTO `fields` VALUES (NULL, 'rooms_max_occupancy', 'backend', 'Label / Max occupancy', 'script', '2017-03-27 07:19:20');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Max occupancy', 'script');

INSERT INTO `fields` VALUES (NULL, 'front_accommodation_up_to_guests', 'backend', 'Frontend / Accommodation', 'script', '2017-03-27 07:26:39');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'This room accommodates up to {MAX} guests', 'script');

INSERT INTO `fields` VALUES (NULL, 'front_accommodation_up_to_guest', 'backend', 'Frontend / Accommodation', 'script', '2017-03-27 07:27:00');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'This room accommodates up to 1 guest', 'script');

INSERT INTO `fields` VALUES (NULL, 'front_max_occupancy_message', 'backend', 'Frontend / Accommodation', 'script', '2017-03-27 07:36:55');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Summary of adults and children should not exceed maximum occupancy.', 'script');

COMMIT;