
START TRANSACTION;

ALTER TABLE  `bookings` CHANGE  `status`  `status` ENUM(  'confirmed',  'cancelled',  'pending',  'not_confirmed' ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT  'pending';

ALTER TABLE  `bookings` DROP  `currency` ;

ALTER TABLE  `bookings_rooms` ADD  `price` DECIMAL( 9, 2 ) UNSIGNED NULL ;

ALTER TABLE  `bookings_rooms_temp` ADD  `price` DECIMAL( 9, 2 ) UNSIGNED NULL ;

COMMIT;