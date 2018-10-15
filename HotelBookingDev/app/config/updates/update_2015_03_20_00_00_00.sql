
START TRANSACTION;

DROP TABLE IF EXISTS `rooms_numbers`;
CREATE TABLE IF NOT EXISTS `rooms_numbers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `room_id` int(10) unsigned DEFAULT NULL,
  `number` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `room_id` (`room_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

ALTER TABLE `bookings_rooms` ADD `room_number_id` INT(10) UNSIGNED NULL AFTER `room_id`, ADD INDEX (`room_number_id`);

ALTER TABLE `bookings_rooms_temp` ADD `room_number_id` INT(10) UNSIGNED NULL AFTER `room_id`;

COMMIT;